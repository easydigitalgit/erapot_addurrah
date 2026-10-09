<?php

namespace App\Controllers\Admin;

use App\Controllers\AdminBaseController;
use App\Models\Admin\OrangTuaModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;

class OrangTuaController extends AdminBaseController
{
    protected $orangTuaModel;

    public function __construct()
    {
        $this->orangTuaModel = new OrangTuaModel();
    }

    // Update Index agar dropdown kelas muncul isinya
    public function index(): string
    {
        $db = \Config\Database::connect();

        // 1. Ambil daftar tingkat untuk filter
        $tingkat = $db->table('rombel')->select('tingkat')->distinct()->orderBy('tingkat', 'ASC')->get()->getResultArray();

        // 2. HITUNG STATISTIK (LOGIKA BARU)

        // A. Total Data
        $totalParents = $this->orangTuaModel->countAllResults();

        // B. Akun Aktif (Join ke tabel users)
        $activeAccounts = $this->orangTuaModel
            ->join('users', 'users.id = orangtua_wali.user_id')
            ->where('users.is_active', 1)
            ->countAllResults();

        // C. Belum Aktivasi / Nonaktif
        // (Bisa dihitung dari Total - Aktif, atau query langsung biar pasti)
        $inactiveAccounts = $this->orangTuaModel
            ->join('users', 'users.id = orangtua_wali.user_id')
            ->where('users.is_active', 0)
            ->countAllResults();

        // D. Siswa Terhubung
        // Hitung ada berapa siswa_id unik di tabel orangtua
        $connectedStudents = $this->orangTuaModel->select('siswa_id')->distinct()->countAllResults();

        $data = [
            'user' => 'Admin',
            'navigations' => $this->getSidebarMenu(),
            'parents' => $this->orangTuaModel->getParentsComplete(),
            'color' => $this->getColor(),
            'tingkat_sekolah' => $tingkat,

            // Kirim Data Statistik ke View
            'stats' => [
                'total'     => $totalParents,
                'active'    => $activeAccounts,
                'inactive'  => $inactiveAccounts,
                'connected' => $connectedStudents
            ]
        ];
        return view('admin/orangtua', $data);
    }

    // --- TAMBAHKAN FUNCTION INI DI PALING BAWAH KELAS ---
    // --- API: AMBIL DATA ORANG TUA (LOGIKA AMAN UNTUK FILTER JS) ---
    public function fetchData()
    {
        if (!$this->request->isAJAX()) return $this->response->setStatusCode(404);

        try {
            // Ambil semua data tanpa parameter agar tidak crash dengan PHP 8.2
            $data = $this->orangTuaModel->getParentsComplete();

            return $this->response->setJSON([
                'status' => 'success',
                'data'   => $data
            ]);
        } catch (\Throwable $e) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Gagal mengambil data: ' . $e->getMessage()
            ]);
        }
    }

   public function store()
    {
        // 1. Validasi Input Wajib Saja (Lainnya Boleh Kosong)
        if (!$this->validate([
            'student'  => 'required',
            'phone'    => 'required',
        ])) {
            return $this->response->setJSON([
                'status' => 'error',
                'message'=> 'Pilih Siswa dan No. HP wajib diisi!'
            ]);
        }

        $db = \Config\Database::connect();
        $siswaId = $this->request->getPost('student');

        // Validasi ID Siswa
        $cekSiswa = $db->table('siswa')->where('id', $siswaId)->countAllResults();
        if ($cekSiswa == 0) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Data Siswa tidak ditemukan.']);
        }

        $db->transBegin();

        try {
            // ============================================
            // STEP 1: BUAT / HUBUNGKAN USER (AKUN LOGIN)
            // ============================================
            $hpRaw = preg_replace('/[^0-9]/', '', $this->request->getPost('phone'));
            $email = $this->request->getPost('email');
            
            // TANGKAP STATUS DARI FORM (Jika kosong, anggap 1/Aktif)
            $statusAkun = $this->request->getPost('status_akun') !== null ? $this->request->getPost('status_akun') : 1;
            
            $username = !empty($hpRaw) ? $hpRaw : 'W' . time(); 

            $existingUser = $db->table('users')->where('username', $username)->get()->getRowArray();
            if (!$existingUser && !empty($email)) {
                $existingUser = $db->table('users')->where('email', $email)->get()->getRowArray();
            }

            if ($existingUser) {
                $newUserId = $existingUser['id'];
                
                // PERBAIKAN: Jika akun sudah ada, perbarui status Aktif/Nonaktif-nya!
                $db->table('users')->where('id', $newUserId)->update([
                    'is_active' => $statusAkun
                ]);
            } else {
                $userData = [
                    'username'  => $username,
                    'password'  => password_hash('12345678', PASSWORD_DEFAULT),
                    'role_id'   => 4, // 4 = Orang Tua
                    'is_active' => $statusAkun // Simpan status
                ];
                if (!empty($email)) $userData['email'] = $email;

                if (!$db->table('users')->insert($userData)) {
                    throw new \Exception('Gagal membuat Akun Login Wali.');
                }
                $newUserId = $db->insertID();
            }

            // ============================================
            // STEP 2: SIMPAN DATA LENGKAP KE TABEL ORTU
            // ============================================
            $existingParentData = $this->orangTuaModel->where('siswa_id', $siswaId)->first();

            $dataToSave = [
                'user_id'          => $newUserId,
                'email_ortu'       => $email,
                'no_hp_ortu'       => $hpRaw,
                'alamat_orangtua'  => $this->request->getPost('address') ?: '-',
                
                // Data Ayah
                'nama_ayah'        => $this->request->getPost('nama_ayah') ?: '-',
                'nik_ayah'         => $this->request->getPost('nik_ayah'),
                'tahun_lahir_ayah' => $this->request->getPost('tahun_lahir_ayah'),
                'pendidikan_ayah'  => $this->request->getPost('pendidikan_ayah'),
                'pekerjaan_ayah'   => $this->request->getPost('pekerjaan_ayah') ?: '-',
                'penghasilan_ayah' => $this->request->getPost('penghasilan_ayah'),

                // Data Ibu
                'nama_ibu'         => $this->request->getPost('nama_ibu') ?: '-',
                'nik_ibu'          => $this->request->getPost('nik_ibu'),
                'tahun_lahir_ibu'  => $this->request->getPost('tahun_lahir_ibu'),
                'pendidikan_ibu'   => $this->request->getPost('pendidikan_ibu'),
                'pekerjaan_ibu'    => $this->request->getPost('pekerjaan_ibu') ?: '-',
                'penghasilan_ibu'  => $this->request->getPost('penghasilan_ibu'),

                // Data Wali
                'nama_wali'        => $this->request->getPost('nama_wali') ?: '-',
                'nik_wali'         => $this->request->getPost('nik_wali'),
                'pekerjaan_wali'   => $this->request->getPost('pekerjaan_wali') ?: '-'
            ];

            if ($existingParentData) {
                // Lakukan UPDATE jika data ortu untuk anak ini sudah ada
                $this->orangTuaModel->update($existingParentData['id'], $dataToSave);
                $msg = 'Data berhasil diperbarui secara lengkap.';
            } else {
                // Lakukan INSERT jika ini pertama kalinya
                $dataToSave['siswa_id'] = $siswaId;
                if (!$this->orangTuaModel->insert($dataToSave)) {
                    throw new \Exception('Gagal menyisipkan data Orang Tua.');
                }
                $msg = 'Data Orang Tua berhasil ditambahkan.';
            }

            $db->transCommit();

            return $this->response->setJSON([
                'status'  => 'success',
                'message' => $msg
            ]);
        } catch (\Exception $e) {
            $db->transRollback();
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }
    // ... method store() di atas ...

    /**
     * AJAX Search Siswa
     * Tugas: Menerima ketikan nama, cari di DB, kirim balik hasilnya.
     */
    public function searchSiswa()
    {
        if ($this->request->isAJAX()) {
            $keyword = $this->request->getGet('term');

            $db      = \Config\Database::connect();
            $builder = $db->table('siswa');

            // PERBAIKAN: Hapus 'kelas' dari sini!
            $query = $builder->select('id, nama_lengkap, nis, nisn')
                ->groupStart()
                ->like('nama_lengkap', $keyword)
                ->orLike('nis', $keyword)
                ->orLike('nisn', $keyword)
                ->groupEnd()
                // ->where('status_siswa', 'Aktif') // Sementara matikan dulu filter aktif biar data pasti muncul
                ->limit(10)
                ->get();

            $data = [];
            foreach ($query->getResult() as $row) {
                // Tampilan: Budi (12345)
                $text = $row->nama_lengkap;
                if (!empty($row->nis)) $text .= ' (' . $row->nis . ')';

                $data[] = [
                    'id'   => $row->id,
                    'text' => $text
                ];
            }

            return $this->response->setJSON($data);
        }
    }

    // METHOD DELETE
    public function delete($id)
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(404);
        }

        $db = \Config\Database::connect();

        // Cari data orang tua dulu untuk dapatkan user_id
        $parent = $this->orangTuaModel->find($id);

        if (!$parent) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Data tidak ditemukan'
            ]);
        }

        // Hapus User (Otomatis data orang tua ikut terhapus karena Cascade)
        // Pastikan tabel users punya primary key 'id'
        $deleted = $db->table('users')->delete(['id' => $parent['user_id']]);

        if ($deleted) {
            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Data berhasil dihapus permanen.'
            ]);
        } else {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Gagal menghapus data dari database.'
            ]);
        }
    }

    // --- NONAKTIFKAN AKUN ORANG TUA SECARA MASSAL ---
    public function bulkDeactivate()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(404);
        }

        $json = $this->request->getJSON();
        $ids = $json->ids ?? [];

        if (empty($ids)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Tidak ada data yang dipilih.']);
        }

        $db = \Config\Database::connect();
        $db->transBegin();

        try {
            // Ambil semua user_id milik orang tua yang dipilih
            $parents = $db->table('orangtua_wali')->whereIn('id', $ids)->get()->getResultArray();
            $userIds = array_filter(array_column($parents, 'user_id')); // Hindari null

            if (!empty($userIds)) {
                // Ubah status is_active menjadi 0 (Nonaktif)
                $db->table('users')->whereIn('id', $userIds)->update(['is_active' => 0]);
            }

            $db->transCommit();
            return $this->response->setJSON(['status' => 'success', 'message' => count($ids) . ' Akun wali berhasil dinonaktifkan.']);
        } catch (\Exception $e) {
            $db->transRollback();
            return $this->response->setJSON(['status' => 'error', 'message' => 'Terjadi kesalahan pada database.']);
        }
    }

    public function show($id)
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(404);
        }

        // PERBAIKAN: Tambahkan 'users.is_active' di akhir select
        $data = $this->orangTuaModel->select('orangtua_wali.*, siswa.nama_lengkap as nama_siswa, siswa.nis, users.email, users.username, users.is_active')
            ->join('siswa', 'siswa.id = orangtua_wali.siswa_id', 'left')
            ->join('users', 'users.id = orangtua_wali.user_id', 'left')
            ->where('orangtua_wali.id', $id)
            ->first();

        if ($data) {
            return $this->response->setJSON(['status' => 'success', 'data' => $data]);
        } else {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Data tidak ditemukan']);
        }
    }

    /**
     * --------------------------------------------------------------------------
     * DOWNLOAD TEMPLATE IMPORT ORTU (BERBASIS NIS & 2 SHEET)
     * --------------------------------------------------------------------------
     */
    public function downloadTemplate()
    {
        $db = \Config\Database::connect();
        $spreadsheet = new Spreadsheet();

        // --- SHEET 1: FORM IMPORT DATA ORANG TUA ---
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Data Orang Tua');

        $headers = [
            'NIS Siswa (Wajib / Kunci Relasi)',
            'Nama Siswa (Lihat Sheet 2)',
            'Nama Ayah',
            'NIK Ayah',
            'Pekerjaan Ayah',
            'Nama Ibu',
            'NIK Ibu',
            'Pekerjaan Ibu',
            'Nama Wali',
            'Pekerjaan Wali',
            'No HP / WA Orang Tua',
            'Email Orang Tua',
            'Alamat Lengkap'
        ];

        $col = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($col . '1', $header);
            $sheet->getStyle($col . '1')->getFont()->setBold(true);
            $sheet->getStyle($col . '1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FFEFEFEF');
            $sheet->getColumnDimension($col)->setAutoSize(true);
            $col++;
        }

        // Baris Contoh (Sample Data)
        $sheet->setCellValueExplicit('A2', '08.26.0001', \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
        $sheet->setCellValue('B2', 'Contoh: Ahmad Zidan');
        $sheet->setCellValue('C2', 'Budi Santoso');
        $sheet->setCellValueExplicit('D2', '3201011122330001', \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
        $sheet->setCellValue('E2', 'Wiraswasta');
        $sheet->setCellValue('F2', 'Siti Aminah');
        $sheet->setCellValueExplicit('G2', '3201014455660002', \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
        $sheet->setCellValue('H2', 'Ibu Rumah Tangga');
        $sheet->setCellValue('I2', '-');
        $sheet->setCellValue('J2', '-');
        $sheet->setCellValueExplicit('K2', '081234567890', \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
        $sheet->setCellValue('L2', 'budi.santoso@email.com');
        $sheet->setCellValue('M2', 'Jl. Kebahagiaan No. 7, Jakarta');

        // --- SHEET 2: REFERENSI DAFTAR SISWA AKTIF ---
        $sheet2 = $spreadsheet->createSheet();
        $sheet2->setTitle('Daftar Siswa');

        $sheet2->setCellValue('A1', 'NIS SISWA (Copy ke Sheet 1 Kolom A)');
        $sheet2->setCellValue('B1', 'NAMA LENGKAP');
        $sheet2->setCellValue('C1', 'KELAS / ROMBEL');
        $sheet2->getStyle('A1:C1')->getFont()->setBold(true);
        $sheet2->getStyle('A1:C1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FFEFEFEF');
        $sheet2->getColumnDimension('A')->setAutoSize(true);
        $sheet2->getColumnDimension('B')->setAutoSize(true);
        $sheet2->getColumnDimension('C')->setAutoSize(true);

        $builder = $db->table('siswa s')
            ->select('s.nis, s.nama_lengkap, r.nama_rombel, r.tingkat')
            ->join('rombel r', 'r.id = s.rombel_id', 'left')
            ->where('s.status_siswa', 'Aktif')
            ->orderBy('r.tingkat', 'ASC')
            ->orderBy('r.nama_rombel', 'ASC')
            ->orderBy('s.nama_lengkap', 'ASC');

        $dataSiswa = $builder->get()->getResultArray();
        $rowSiswa = 2;
        foreach ($dataSiswa as $s) {
            $kelas = ($s['tingkat'] && $s['nama_rombel']) ? ($s['tingkat'] . ' ' . $s['nama_rombel']) : '-';
            $sheet2->setCellValueExplicit('A' . $rowSiswa, $s['nis'] ?? '-', \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet2->setCellValue('B' . $rowSiswa, $s['nama_lengkap']);
            $sheet2->setCellValue('C' . $rowSiswa, $kelas);
            $rowSiswa++;
        }

        $spreadsheet->setActiveSheetIndex(0);

        $filename = 'Template_Import_OrangTua_' . date('Y-m-d') . '.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }

    /**
     * --------------------------------------------------------------------------
     * IMPORT DATA ORANG TUA (BERBASIS NIS SISWA & UPSERT)
     * --------------------------------------------------------------------------
     */
    public function import()
    {
        ini_set('memory_limit', '1024M');
        ini_set('max_execution_time', '300');
        if (ob_get_length()) ob_clean();

        if (empty($_FILES)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'File tidak terdeteksi.']);
        }

        $file = $this->request->getFile('file_excel');
        if (!$file || !$file->isValid() || $file->hasMoved()) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'File gagal diunggah atau corrupt.']);
        }

        $extension = strtolower($file->getClientExtension());
        if (!in_array($extension, ['xls', 'xlsx'])) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Format wajib .xls atau .xlsx']);
        }

        $db = \Config\Database::connect();
        $db->transBegin();

        try {
            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($file->getTempName());
            $sheet = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);

            $ortuModel = new \App\Models\Admin\OrangTuaModel();
            $userModel = new \App\Models\Admin\UserModel();
            $siswaModel = new \App\Models\Admin\SiswaModel();

            $countInsert = 0;
            $countUpdate = 0;
            $errors = [];

            foreach ($sheet as $idx => $row) {
                // Lewati baris 1 (Header/Judul Kolom)
                if ($idx == 1) continue;

                $nis = trim($row['A'] ?? '');
                $namaSiswa = trim($row['B'] ?? '');
                $namaAyah = trim($row['C'] ?? '');
                $nikAyah = trim($row['D'] ?? '');
                $pekerjaanAyah = trim($row['E'] ?? '');
                $namaIbu = trim($row['F'] ?? '');
                $nikIbu = trim($row['G'] ?? '');
                $pekerjaanIbu = trim($row['H'] ?? '');
                $namaWali = trim($row['I'] ?? '');
                $pekerjaanWali = trim($row['J'] ?? '');
                $hpRaw = preg_replace('/[^0-9]/', '', trim($row['K'] ?? ''));
                $email = trim($row['L'] ?? '');
                $alamat = trim($row['M'] ?? '');

                // Lewati baris kosong
                if (empty($nis) && empty($namaSiswa) && empty($namaAyah) && empty($namaIbu)) {
                    continue;
                }

                // 1. Kunci Utama: Cari Siswa berdasarkan NIS (Kolom A) atau Fallback Nama (Kolom B)
                $siswa = null;
                if (!empty($nis) && $nis !== '-') {
                    $siswa = $siswaModel->where('nis', $nis)->first();
                }
                if (!$siswa && !empty($namaSiswa) && stripos($namaSiswa, 'Contoh:') === false) {
                    $siswa = $siswaModel->where('LOWER(nama_lengkap)', strtolower($namaSiswa))->first();
                }

                if (!$siswa) {
                    $errors[] = "Baris $idx: Siswa dengan NIS '{$nis}' / Nama '{$namaSiswa}' tidak ditemukan di database.";
                    continue;
                }

                $siswaId = $siswa['id'];

                // 2. Buat / Sinkronkan Akun Login Orang Tua (User)
                $username = !empty($hpRaw) ? $hpRaw : ('W' . ($siswa['nis'] ?: $siswaId));

                $existingUser = null;
                if (!empty($username)) {
                    $existingUser = $userModel->where('username', $username)->first();
                }
                if (!$existingUser && !empty($email)) {
                    $existingUser = $userModel->where('email', $email)->first();
                }

                if ($existingUser) {
                    $userId = $existingUser['id'];
                } else {
                    $userData = [
                        'username'  => substr($username, 0, 50),
                        'password'  => password_hash('12345678', PASSWORD_BCRYPT),
                        'role_id'   => 4, // Role 4 = Orang Tua
                        'is_active' => 1
                    ];
                    if (!empty($email)) {
                        $userData['email'] = substr($email, 0, 100);
                    }

                    $userModel->insert($userData);
                    $userId = $userModel->getInsertID();
                }

                // 3. Susun Data Orang Tua
                $ortuData = [
                    'user_id'         => $userId,
                    'siswa_id'        => $siswaId,
                    'nama_ayah'       => !empty($namaAyah) ? substr($namaAyah, 0, 100) : '-',
                    'nik_ayah'        => !empty($nikAyah) ? substr($nikAyah, 0, 50) : null,
                    'pekerjaan_ayah'  => !empty($pekerjaanAyah) ? substr($pekerjaanAyah, 0, 50) : '-',
                    'nama_ibu'        => !empty($namaIbu) ? substr($namaIbu, 0, 100) : '-',
                    'nik_ibu'         => !empty($nikIbu) ? substr($nikIbu, 0, 50) : null,
                    'pekerjaan_ibu'   => !empty($pekerjaanIbu) ? substr($pekerjaanIbu, 0, 50) : '-',
                    'nama_wali'       => !empty($namaWali) ? substr($namaWali, 0, 100) : '-',
                    'pekerjaan_wali'  => !empty($pekerjaanWali) ? substr($pekerjaanWali, 0, 50) : '-',
                    'no_hp_ortu'      => substr($hpRaw, 0, 20),
                    'email_ortu'      => substr($email, 0, 100),
                    'alamat_orangtua' => !empty($alamat) ? substr($alamat, 0, 255) : '-'
                ];

                // 4. Upsert ke tabel orangtua_wali
                $existingOrtu = $ortuModel->where('siswa_id', $siswaId)->first();

                if ($existingOrtu) {
                    $ortuModel->update($existingOrtu['id'], $ortuData);
                    $countUpdate++;
                } else {
                    $ortuModel->insert($ortuData);
                    $countInsert++;
                }
            }

            if (!empty($errors) && $countInsert == 0 && $countUpdate == 0) {
                $db->transRollback();
                return $this->response->setJSON([
                    'status'  => 'error',
                    'message' => '<b>Proses import gagal:</b><br><ul><li>' . implode('</li><li>', array_slice($errors, 0, 10)) . '</li></ul>'
                ]);
            }

            $db->transCommit();
            $msg = "Import Orang Tua Sukses! <b>{$countInsert}</b> Data Baru ditambahkan, <b>{$countUpdate}</b> Data Diperbarui.";
            if (!empty($errors)) {
                $msg .= "<br><br><small class='text-amber-600'>Catatan (" . count($errors) . " baris dilewati):<br>" . implode('<br>', array_slice($errors, 0, 5)) . "</small>";
            }

            return $this->response->setJSON(['status' => 'success', 'message' => $msg]);

        } catch (\Throwable $e) {
            if (isset($db)) $db->transRollback();
            return $this->response->setJSON(['status' => 'error', 'message' => 'Fatal Error: ' . $e->getMessage()]);
        }
    }

    /**
     * --------------------------------------------------------------------------
     * EXPORT DATA ORANG TUA / WALI KE EXCEL (FORMAT SINKRON DENGAN TEMPLATE)
     * --------------------------------------------------------------------------
     */
    public function export()
    {
        if (ob_get_length()) ob_clean();

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Data Orang Tua');

        // Header menyesuaikan 100% dengan struktur Template Import
        $headers = [
            'NIS Siswa',
            'Nama Siswa',
            'Nama Ayah',
            'NIK Ayah',
            'Pekerjaan Ayah',
            'Nama Ibu',
            'NIK Ibu',
            'Pekerjaan Ibu',
            'Nama Wali',
            'Pekerjaan Wali',
            'No HP / WhatsApp',
            'Email',
            'Alamat Lengkap'
        ];

        $col = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($col . '1', $header);
            $sheet->getStyle($col . '1')->getFont()->setBold(true);
            $sheet->getStyle($col . '1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FFEFEFEF');
            $sheet->getColumnDimension($col)->setAutoSize(true);
            $col++;
        }

        // Tarik Data dari Database
        $db = \Config\Database::connect();
        $builder = $db->table('orangtua_wali');
        $builder->select('orangtua_wali.*, siswa.nama_lengkap as nama_siswa, siswa.nis');
        $builder->join('siswa', 'siswa.id = orangtua_wali.siswa_id', 'left');
        $builder->orderBy('siswa.nama_lengkap', 'ASC');

        $dataOrtu = $builder->get()->getResultArray();

        $row = 2;
        foreach ($dataOrtu as $ortu) {
            $sheet->setCellValueExplicit('A' . $row, $ortu['nis'] ?? '-', \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet->setCellValue('B' . $row, $ortu['nama_siswa'] ?? 'Siswa Tidak Ditemukan');
            $sheet->setCellValue('C' . $row, $ortu['nama_ayah']);
            $sheet->setCellValueExplicit('D' . $row, $ortu['nik_ayah'] ?? '-', \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet->setCellValue('E' . $row, $ortu['pekerjaan_ayah']);
            $sheet->setCellValue('F' . $row, $ortu['nama_ibu']);
            $sheet->setCellValueExplicit('G' . $row, $ortu['nik_ibu'] ?? '-', \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet->setCellValue('H' . $row, $ortu['pekerjaan_ibu']);
            $sheet->setCellValue('I' . $row, $ortu['nama_wali']);
            $sheet->setCellValue('J' . $row, $ortu['pekerjaan_wali']);
            $sheet->setCellValueExplicit('K' . $row, $ortu['no_hp_ortu'] ?? '-', \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet->setCellValue('L' . $row, $ortu['email_ortu']);
            $sheet->setCellValue('M' . $row, $ortu['alamat_orangtua']);
            $row++;
        }

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $filename = 'Data_OrangTua_Wali_' . date('Y-m-d_H-i') . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit;
    }
}
