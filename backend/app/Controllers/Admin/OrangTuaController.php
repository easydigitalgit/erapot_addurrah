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
    // Method untuk handle Filter AJAX
    public function fetchData()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(404);
        }

        $keyword  = $this->request->getGet('keyword');
        $relation = $this->request->getGet('relation');
        $kelas    = $this->request->getGet('class'); // Sesuai nama param di JS
        $status   = $this->request->getGet('status');

        $data = $this->orangTuaModel->getParentsComplete($keyword, $relation, $kelas, $status);

        return $this->response->setJSON([
            'status' => 'success',
            'data' => $data
        ]);
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
     * DOWNLOAD TEMPLATE IMPORT ORTU (DATA ASLI DARI DB)
     * --------------------------------------------------------------------------
     */
    public function downloadTemplate()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $headers = [
            'ID Ortu (JANGAN UBAH JIKA UPDATE)',
            'ID Siswa (WAJIB ADA - Lihat DB Siswa)',
            'Nama Ayah',
            'Pekerjaan Ayah',
            'Nama Ibu',
            'Pekerjaan Ibu',
            'Nama Wali (Kosongkan jika tdk ada)',
            'Pekerjaan Wali',
            'Email Ortu (Untuk Akun)',
            'No HP Ortu / WhatsApp',
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

        $ortuModel = new \App\Models\Admin\OrangTuaModel();
        $dataOrtu = $ortuModel->findAll();

        $row = 2;
        foreach ($dataOrtu as $ortu) {
            $sheet->setCellValue('A' . $row, $ortu['id']);
            $sheet->setCellValue('B' . $row, $ortu['siswa_id']);
            $sheet->setCellValue('C' . $row, $ortu['nama_ayah']);
            $sheet->setCellValue('D' . $row, $ortu['pekerjaan_ayah']);
            $sheet->setCellValue('E' . $row, $ortu['nama_ibu']);
            $sheet->setCellValue('F' . $row, $ortu['pekerjaan_ibu']);
            $sheet->setCellValue('G' . $row, $ortu['nama_wali']);
            $sheet->setCellValue('H' . $row, $ortu['pekerjaan_wali']);
            $sheet->setCellValue('I' . $row, $ortu['email_ortu']);
            $sheet->setCellValueExplicit('J' . $row, $ortu['no_hp_ortu'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet->setCellValue('K' . $row, $ortu['alamat_orangtua']);
            $row++;
        }

        $filename = 'Data_OrangTua_' . date('Y-m-d') . '.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }

    /**
     * --------------------------------------------------------------------------
     * IMPORT DATA ORANG TUA (SINKRON DENGAN TEMPLATE & NIS SISWA)
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
            return $this->response->setJSON(['status' => 'error', 'message' => 'File gagal diunggah.']);
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
            $siswaModel = new \App\Models\Admin\SiswaModel(); // Dipanggil untuk melacak NIS Siswa

            $countInsert = 0;
            $countUpdate = 0;

            foreach ($sheet as $idx => $row) {
                // Lewati baris 1 (Header/Judul Kolom Excel)
                if ($idx == 1) continue;

                // 1. Kunci Utama: Cari Siswa berdasarkan NIS (Kolom C)
                $nis = trim($row['C'] ?? '');
                if (empty($nis) || $nis == '-') continue; 

                $siswa = $siswaModel->where('nis', $nis)->first();
                if (!$siswa) continue; // Abaikan jika NIS tidak terdaftar di database

                $siswaId = $siswa['id'];

                // 2. Bersihkan Data Kontak untuk Pembuatan Akun
                $hpRaw = preg_replace('/[^0-9]/', '', trim($row['J'] ?? '')); // Kolom J: No HP
                $email = trim($row['K'] ?? ''); // Kolom K: Email

                $userId = null;

                // 3. Buat Akun Login Wali (Bypass jika sudah ada)
                // Username prioritas: No HP. Jika kosong, pakai "W" + NIS Siswa
                $username = !empty($hpRaw) ? $hpRaw : 'W' . $nis;

                $existingUser = $userModel->where('username', $username)->first();
                if (!$existingUser && !empty($email)) {
                    $existingUser = $userModel->where('email', $email)->first();
                }

                if ($existingUser) {
                    $userId = $existingUser['id'];
                } else {
                    $userData = [
                        'username'  => substr($username, 0, 50),
                        'password'  => password_hash('12345678', PASSWORD_BCRYPT), // Default pass: 12345678
                        'role_id'   => 4, // 4 = Role Orang Tua/Wali
                        'is_active' => 1
                    ];
                    if (!empty($email)) $userData['email'] = substr($email, 0, 100);
                    
                    $userModel->insert($userData);
                    $userId = $userModel->getInsertID();
                }

                // 4. Susun Data Orang Tua sesuai Urutan Kolom Template
                $ortuData = [
                    'user_id'         => $userId,
                    'siswa_id'        => $siswaId,
                    'nama_ayah'       => substr(trim($row['D'] ?? '-'), 0, 100),
                    'pekerjaan_ayah'  => substr(trim($row['E'] ?? '-'), 0, 50),
                    'nama_ibu'        => substr(trim($row['F'] ?? '-'), 0, 100),
                    'pekerjaan_ibu'   => substr(trim($row['G'] ?? '-'), 0, 50),
                    'nama_wali'       => substr(trim($row['H'] ?? '-'), 0, 100),
                    'pekerjaan_wali'  => substr(trim($row['I'] ?? '-'), 0, 50),
                    'no_hp_ortu'      => substr($hpRaw, 0, 20),
                    'email_ortu'      => substr($email, 0, 100),
                    'alamat_orangtua' => trim($row['L'] ?? '-')
                ];

                // 5. Simpan (Update jika sudah ada wali untuk siswa ini, Insert jika belum)
                $existingOrtu = $ortuModel->where('siswa_id', $siswaId)->first();

                if ($existingOrtu) {
                    $ortuModel->update($existingOrtu['id'], $ortuData);
                    $countUpdate++;
                } else {
                    $ortuModel->insert($ortuData);
                    $countInsert++;
                }
            }

            if ($db->transStatus() === false) {
                $dbError = $db->error();
                $db->transRollback();
                return $this->response->setJSON(['status' => 'error', 'message' => 'DB Error: ' . ($dbError['message'] ?? 'Gagal menyimpan.')]);
            }

            $db->transCommit();
            return $this->response->setJSON(['status' => 'success', 'message' => "Import Orang Tua Sukses! $countInsert Data Baru, $countUpdate Diperbarui."]);
            
        } catch (\Throwable $e) {
            if (isset($db)) $db->transRollback();
            return $this->response->setJSON(['status' => 'error', 'message' => 'Fatal Error: ' . $e->getMessage()]);
        }
    }

    /**
     * --------------------------------------------------------------------------
     * EXPORT DATA ORANG TUA / WALI KE EXCEL (100% DATA ASLI DB)
     * --------------------------------------------------------------------------
     */
    public function export()
    {
        // Bersihkan output buffer agar file Excel tidak corrupt (Wajib)
        if (ob_get_length()) ob_clean();

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Data Orang Tua');

        // 1. Header menyesuaikan struktur asli tabel orangtua_wali
        $headers = [
            'No',
            'Nama Anak (Siswa)',
            'NIS',
            'Nama Ayah',
            'Pekerjaan Ayah',
            'Nama Ibu',
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

        // 2. Tarik Data Murni dari Database
        $db = \Config\Database::connect();
        $builder = $db->table('orangtua_wali');
        // Join dengan tabel siswa untuk mengekstrak Nama Anak dan NIS sesuai foreign key
        $builder->select('orangtua_wali.*, siswa.nama_lengkap as nama_siswa, siswa.nis');
        $builder->join('siswa', 'siswa.id = orangtua_wali.siswa_id', 'left');
        $builder->orderBy('siswa.nama_lengkap', 'ASC');

        $dataOrtu = $builder->get()->getResultArray();

        // 3. Mapping Data ke Kolom Excel (Tanpa Rekayasa Dummy)
        $row = 2;
        $no = 1;
        foreach ($dataOrtu as $ortu) {
            $sheet->setCellValue('A' . $row, $no++);
            $sheet->setCellValue('B' . $row, $ortu['nama_siswa'] ?? 'Siswa Tidak Ditemukan');
            $sheet->setCellValueExplicit('C' . $row, $ortu['nis'] ?? '-', \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet->setCellValue('D' . $row, $ortu['nama_ayah']);
            $sheet->setCellValue('E' . $row, $ortu['pekerjaan_ayah']);
            $sheet->setCellValue('F' . $row, $ortu['nama_ibu']);
            $sheet->setCellValue('G' . $row, $ortu['pekerjaan_ibu']);
            $sheet->setCellValue('H' . $row, $ortu['nama_wali']);
            $sheet->setCellValue('I' . $row, $ortu['pekerjaan_wali']);
            $sheet->setCellValueExplicit('J' . $row, $ortu['no_hp_ortu'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet->setCellValue('K' . $row, $ortu['email_ortu']);
            $sheet->setCellValue('L' . $row, $ortu['alamat_orangtua']);
            $row++;
        }

        // 4. Proses Download Otomatis
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $filename = 'Data_OrangTua_Wali_' . date('Y-m-d_H-i') . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit;
    }   
}
