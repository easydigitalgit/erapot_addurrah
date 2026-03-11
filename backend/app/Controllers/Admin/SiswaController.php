<?php

namespace App\Controllers\Admin;


use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;

use App\Controllers\AdminBaseController;
use App\Models\Admin\SiswaModel;

class SiswaController extends AdminBaseController
{
   public function index(): string
    {
        $db = \Config\Database::connect();

        // 1. OPTIMASI QUERY STATISTIK (Hanya 1x Query)
        $stats = $db->table('siswa')
            ->select('
                SUM(CASE WHEN status_siswa = "Aktif" THEN 1 ELSE 0 END) as total_siswa,
                SUM(CASE WHEN status_siswa = "Alumni" THEN 1 ELSE 0 END) as total_alumni,
                SUM(CASE WHEN jenis_kelamin = "L" AND status_siswa = "Aktif" THEN 1 ELSE 0 END) as total_laki,
                SUM(CASE WHEN jenis_kelamin = "P" AND status_siswa = "Aktif" THEN 1 ELSE 0 END) as total_perempuan
            ')
            ->get()
            ->getRowArray();

        // 2. DINAMISASI FILTER TAHUN AJARAN (Berdasarkan raporsmpit(18))
        $tahunAjaran = $db->table('tahun_ajaran')
            ->select('tahun')
            ->distinct()
            ->orderBy('tahun', 'DESC')
            ->get()
            ->getResultArray();

        // 3. DINAMISASI FILTER KELAS
        $tingkatRombel = $db->table('rombel')
            ->select('tingkat')
            ->distinct()
            ->orderBy('tingkat', 'ASC')
            ->get()
            ->getResultArray();

        $data = [
            'user'            => 'Admin',
            'navigations'     => $this->getSidebarMenu(),
            'total_siswa'     => $stats['total_siswa'] ?? 0,
            'total_alumni'    => $stats['total_alumni'] ?? 0,
            'total_laki'      => $stats['total_laki'] ?? 0,
            'total_perempuan' => $stats['total_perempuan'] ?? 0,
            'tahun_ajaran'    => $tahunAjaran,
            'tingkat_rombel'  => $tingkatRombel,
            'color'           => $this->getColor()
        ];

        return view('admin/siswa', $data);
    }

    // --- API: AMBIL SEMUA DATA SISWA (JOIN ROMBEL) ---
    public function getAll()
    {
        $siswaModel = new SiswaModel();
        // PENTING: Pakai function ini agar Nama Rombel muncul (IX - Amazonite)
        $data = $siswaModel->getSiswaLengkap();
        return $this->response->setJSON($data);
    }

    // --- API: AMBIL DAFTAR ROMBEL UNTUK DROPDOWN ---
    public function getRombel()
    {
        $db = \Config\Database::connect();
        $data = $db->table('rombel')
            ->select('id, nama_rombel, tingkat')
            ->orderBy('tingkat', 'ASC')
            ->orderBy('nama_rombel', 'ASC')
            ->get()->getResultArray();
        return $this->response->setJSON($data);
    }

   /// --- SIMPAN DATA BARU (DENGAN PROTEKSI KETAT & PESAN ERROR DETAIL) ---
    public function store()
    {
        $siswaModel = new SiswaModel();
        $db = \Config\Database::connect();
        
        $tglDiterima = $this->request->getPost('tgl_diterima') ?: date('Y-m-d');
        $tahunMasuk  = date('y', strtotime($tglDiterima)); 

        // AUTO ANGKATAN & RESET
        $lastSiswa = $siswaModel->orderBy('id', 'DESC')->first();
        if ($lastSiswa && !empty($lastSiswa['nis']) && strpos($lastSiswa['nis'], '.') !== false) {
            $parts = explode('.', $lastSiswa['nis']);
            $lastAngkatan = (int)$parts[0];
            $lastTahun    = $parts[1];
            $angkatanBaru = ($tahunMasuk > $lastTahun) ? $lastAngkatan + 1 : $lastAngkatan;
        } else {
            $angkatanBaru = 1;
        }

        $prefixNis = sprintf("%02d", $angkatanBaru) . '.' . $tahunMasuk . '.';
        $cekUrutan = $siswaModel->like('nis', $prefixNis, 'after')->orderBy('nis', 'DESC')->first();
        $nextUrut  = $cekUrutan ? ((int)explode('.', $cekUrutan['nis'])[2] + 1) : 1;
        $nisFinal  = $prefixNis . sprintf("%05d", $nextUrut);

        // Upload Foto
        $fileFoto = $this->request->getFile('photo');
        $namaFotoDB = null;
        if ($fileFoto && $fileFoto->isValid() && ! $fileFoto->hasMoved()) {
            $namaFotoDB = $fileFoto->getRandomName();
            $fileFoto->move('uploads/siswa', $namaFotoDB);
        }

        // FUNGSI PEMBERSIH: Mencegah error "Incorrect integer value" di MySQL
        $getNull = function($key) {
            $val = $this->request->getPost($key);
            return ($val === '' || $val === null) ? null : $val;
        };

        // KUMPULKAN SEMUA DATA SISWA
        $dataSiswa = [
            'nis'                   => $nisFinal,
            'nisn'                  => $getNull('nisn'),
            'nik'                   => $getNull('nik'),
            'nama_lengkap'          => $this->request->getPost('nama_lengkap'),
            'jenis_kelamin'         => $getNull('jenis_kelamin'),
            'tempat_lahir'          => $getNull('tempat_lahir'),
            'tanggal_lahir'         => $getNull('tanggal_lahir'),
            'agama'                 => $getNull('agama'),
            'no_kk'                 => $getNull('no_kk'),
            'no_registrasi_akta'    => $getNull('no_registrasi_akta'),
            'status_dalam_keluarga' => $getNull('status_dalam_keluarga'),
            'anak_ke'               => $getNull('anak_ke'),
            'jml_saudara_kandung'   => $getNull('jml_saudara_kandung'),
            'kebutuhan_khusus'      => $getNull('kebutuhan_khusus'),
            'berat_badan'           => $getNull('berat_badan'),
            'tinggi_badan'          => $getNull('tinggi_badan'),
            'lingkar_kepala'        => $getNull('lingkar_kepala'),
            'alamat_siswa'          => $getNull('alamat_siswa'),
            'rt'                    => $getNull('rt'),
            'rw'                    => $getNull('rw'),
            'dusun'                 => $getNull('dusun'),
            'kelurahan'             => $getNull('kelurahan'),
            'kecamatan'             => $getNull('kecamatan'),
            'kode_pos'              => $getNull('kode_pos'),
            'jenis_tinggal'         => $getNull('jenis_tinggal'),
            'alat_transportasi'     => $getNull('alat_transportasi'),
            'jarak_ke_sekolah'      => $getNull('jarak_ke_sekolah'),
            'no_telp_rumah'         => $getNull('no_telp_rumah'),
            'no_hp'                 => $getNull('no_hp'),
            'email_siswa'           => $getNull('email_siswa'),
            'asal_sekolah'          => $getNull('asal_sekolah'),
            'skhun'                 => $getNull('skhun'),
            'no_peserta_un'         => $getNull('no_peserta_un'),
            'no_seri_ijazah'        => $getNull('no_seri_ijazah'),
            'diterima_dikelas'      => $getNull('diterima_dikelas'),
            'tgl_diterima'          => $tglDiterima,
            'rombel_id'             => $getNull('rombel_id'),
            'penerima_kps'          => $getNull('penerima_kps'),
            'no_kps'                => $getNull('no_kps'),
            'penerima_kip'          => $getNull('penerima_kip'),
            'nomor_kip'             => $getNull('nomor_kip'),
            'nama_di_kip'           => $getNull('nama_di_kip'),
            'nomor_kks'             => $getNull('nomor_kks'),
            'layak_pip'             => $getNull('layak_pip'),
            'alasan_layak_pip'      => $getNull('alasan_layak_pip'),
            'foto_siswa'            => $namaFotoDB,
            'status_siswa'          => $this->request->getPost('status_siswa') ?: 'Aktif'
        ];

        // VALIDASI DUPLIKAT MANUAL AGAR ERROR JELAS
        if (!empty($dataSiswa['nisn']) && $siswaModel->where('nisn', $dataSiswa['nisn'])->countAllResults() > 0) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'NISN tersebut sudah terdaftar pada siswa lain!']);
        }
        if (!empty($dataSiswa['nik']) && $siswaModel->where('nik', $dataSiswa['nik'])->countAllResults() > 0) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'NIK tersebut sudah terdaftar pada siswa lain!']);
        }

        $db->transBegin();

        try {
            // 1. BUAT AKUN SISWA YANG AMAN
            $username = !empty($dataSiswa['nisn']) ? $dataSiswa['nisn'] : $dataSiswa['nis'];
            // Jika username kembar, tambahkan angka random
            if ($db->table('users')->where('username', $username)->countAllResults() > 0) {
                $username = $username . rand(10, 99);
            }

            $userData = [
                'username'  => $username,
                'password'  => password_hash('12345678', PASSWORD_BCRYPT),
                'role_id'   => 3,
                'is_active' => 1
            ];
            
            if (!$db->table('users')->insert($userData)) {
                throw new \Exception('Gagal membuat akun login Siswa. ' . ($db->error()['message'] ?? ''));
            }
            $dataSiswa['user_id'] = $db->insertID();

            // INSERT DATA SISWA
            if (!$siswaModel->insert($dataSiswa)) {
                throw new \Exception('Gagal menyimpan profil Siswa. ' . ($db->error()['message'] ?? ''));
            }
            $siswaId = $siswaModel->getInsertID();

            // 2. LOGIKA CERDAS: CEK ORANG TUA SUDAH ADA ATAU BELUM
            $hpOrtu = $this->request->getPost('no_hp_ortu');
            $usernameOrtu = !empty($hpOrtu) ? $hpOrtu : 'W' . $dataSiswa['nis'];
            
            $existingUserOrtu = $db->table('users')->where('username', $usernameOrtu)->get()->getRowArray();
            
            if ($existingUserOrtu) {
                $userIdOrtu = $existingUserOrtu['id'];
            } else {
                $ortuAccount = [
                    'username'  => $usernameOrtu,
                    'password'  => password_hash('12345678', PASSWORD_BCRYPT),
                    'role_id'   => 4,
                    'is_active' => 1
                ];
                if(!$db->table('users')->insert($ortuAccount)){
                    throw new \Exception('Gagal membuat akun login Wali. ' . ($db->error()['message'] ?? ''));
                }
                $userIdOrtu = $db->insertID();
            }

            // Kumpulkan Data Form Orang Tua
            $dataOrtu = [
                'nama_ayah'        => $getNull('nama_ayah') ?: '-',
                'nik_ayah'         => $getNull('nik_ayah'),
                'tahun_lahir_ayah' => $getNull('tahun_lahir_ayah'),
                'pendidikan_ayah'  => $getNull('pendidikan_ayah'),
                'pekerjaan_ayah'   => $getNull('pekerjaan_ayah') ?: '-',
                'penghasilan_ayah' => $getNull('penghasilan_ayah'),
                
                'nama_ibu'         => $getNull('nama_ibu') ?: '-',
                'nik_ibu'          => $getNull('nik_ibu'),
                'tahun_lahir_ibu'  => $getNull('tahun_lahir_ibu'),
                'pendidikan_ibu'   => $getNull('pendidikan_ibu'),
                'pekerjaan_ibu'    => $getNull('pekerjaan_ibu') ?: '-',
                'penghasilan_ibu'  => $getNull('penghasilan_ibu'),
                
                'nama_wali'        => $getNull('nama_wali') ?: '-',
                'nik_wali'         => $getNull('nik_wali'),
                'tahun_lahir_wali' => $getNull('tahun_lahir_wali'),
                'pendidikan_wali'  => $getNull('pendidikan_wali'),
                'pekerjaan_wali'   => $getNull('pekerjaan_wali') ?: '-',
                'penghasilan_wali' => $getNull('penghasilan_wali'),
                
                'no_hp_ortu'       => $hpOrtu,
                'email_ortu'       => $getNull('email_ortu'),
                'alamat_orangtua'  => $getNull('alamat_orangtua')
            ];

            $cekOrtuDB = $db->table('orangtua_wali')->where('user_id', $userIdOrtu)->get()->getRowArray();

            if ($cekOrtuDB) {
                $dataOrtu['siswa_id'] = $siswaId; 
                if (!empty($dataOrtu['email_ortu'])) {
                    $cekEmail = $db->table('orangtua_wali')->where('email_ortu', $dataOrtu['email_ortu'])->where('id !=', $cekOrtuDB['id'])->countAllResults();
                    if ($cekEmail > 0) unset($dataOrtu['email_ortu']);
                }
                $db->table('orangtua_wali')->where('id', $cekOrtuDB['id'])->update($dataOrtu);
            } else {
                $dataOrtu['siswa_id'] = $siswaId;
                $dataOrtu['user_id']  = $userIdOrtu;
                if (!empty($dataOrtu['email_ortu']) && $db->table('orangtua_wali')->where('email_ortu', $dataOrtu['email_ortu'])->countAllResults() > 0) {
                    $dataOrtu['email_ortu'] = null;
                }
                if(!$db->table('orangtua_wali')->insert($dataOrtu)){
                    throw new \Exception('Gagal menyimpan profil Wali. ' . ($db->error()['message'] ?? ''));
                }
            }

            $db->transCommit();
            return $this->response->setJSON(['status' => 'success', 'message' => "Berhasil! Siswa dan Wali berhasil disimpan. NIS: $nisFinal"]);
            
        } catch (\Exception $e) {
            $db->transRollback();
            return $this->response->setJSON(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    // --- UPDATE DATA (DENGAN PROTEKSI KETAT) ---
    public function update($id)
    {
        $siswaModel = new SiswaModel();
        $db = \Config\Database::connect();
        
        $siswaLama = $siswaModel->find($id);
        if (!$siswaLama) return $this->response->setJSON(['status' => 'error', 'message' => 'Data tidak ditemukan.']);

        $getNull = function($key) {
            $val = $this->request->getPost($key);
            return ($val === '' || $val === null) ? null : $val;
        };

        $dataSiswa = [
            'nis'                   => $this->request->getPost('nis'),
            'nisn'                  => $getNull('nisn'),
            'nik'                   => $getNull('nik'),
            'nama_lengkap'          => $this->request->getPost('nama_lengkap'),
            'jenis_kelamin'         => $getNull('jenis_kelamin'),
            'tempat_lahir'          => $getNull('tempat_lahir'),
            'tanggal_lahir'         => $getNull('tanggal_lahir'),
            'agama'                 => $getNull('agama'),
            'no_kk'                 => $getNull('no_kk'),
            'no_registrasi_akta'    => $getNull('no_registrasi_akta'),
            'status_dalam_keluarga' => $getNull('status_dalam_keluarga'),
            'anak_ke'               => $getNull('anak_ke'),
            'jml_saudara_kandung'   => $getNull('jml_saudara_kandung'),
            'kebutuhan_khusus'      => $getNull('kebutuhan_khusus'),
            'berat_badan'           => $getNull('berat_badan'),
            'tinggi_badan'          => $getNull('tinggi_badan'),
            'lingkar_kepala'        => $getNull('lingkar_kepala'),
            'alamat_siswa'          => $getNull('alamat_siswa'),
            'rt'                    => $getNull('rt'),
            'rw'                    => $getNull('rw'),
            'dusun'                 => $getNull('dusun'),
            'kelurahan'             => $getNull('kelurahan'),
            'kecamatan'             => $getNull('kecamatan'),
            'kode_pos'              => $getNull('kode_pos'),
            'jenis_tinggal'         => $getNull('jenis_tinggal'),
            'alat_transportasi'     => $getNull('alat_transportasi'),
            'jarak_ke_sekolah'      => $getNull('jarak_ke_sekolah'),
            'no_telp_rumah'         => $getNull('no_telp_rumah'),
            'no_hp'                 => $getNull('no_hp'),
            'email_siswa'           => $getNull('email_siswa'),
            'asal_sekolah'          => $getNull('asal_sekolah'),
            'skhun'                 => $getNull('skhun'),
            'no_peserta_un'         => $getNull('no_peserta_un'),
            'no_seri_ijazah'        => $getNull('no_seri_ijazah'),
            'diterima_dikelas'      => $getNull('diterima_dikelas'),
            'tgl_diterima'          => $getNull('tgl_diterima'),
            'rombel_id'             => $getNull('rombel_id'),
            'penerima_kps'          => $getNull('penerima_kps'),
            'no_kps'                => $getNull('no_kps'),
            'penerima_kip'          => $getNull('penerima_kip'),
            'nomor_kip'             => $getNull('nomor_kip'),
            'nama_di_kip'           => $getNull('nama_di_kip'),
            'nomor_kks'             => $getNull('nomor_kks'),
            'layak_pip'             => $getNull('layak_pip'),
            'alasan_layak_pip'      => $getNull('alasan_layak_pip'),
            'status_siswa'          => $this->request->getPost('status_siswa') ?: 'Aktif'
        ];

        // VALIDASI DUPLIKAT
        if (!empty($dataSiswa['nisn']) && $siswaModel->where('nisn', $dataSiswa['nisn'])->where('id !=', $id)->countAllResults() > 0) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'NISN tersebut sudah dipakai siswa lain.']);
        }
        if (!empty($dataSiswa['nik']) && $siswaModel->where('nik', $dataSiswa['nik'])->where('id !=', $id)->countAllResults() > 0) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'NIK tersebut sudah dipakai siswa lain.']);
        }

        $fileFoto = $this->request->getFile('photo');
        if ($fileFoto && $fileFoto->isValid() && ! $fileFoto->hasMoved()) {
            if (!empty($siswaLama['foto_siswa']) && file_exists('uploads/siswa/' . $siswaLama['foto_siswa'])) {
                unlink('uploads/siswa/' . $siswaLama['foto_siswa']);
            }
            $namaFotoBaru = $fileFoto->getRandomName();
            $fileFoto->move('uploads/siswa', $namaFotoBaru);
            $dataSiswa['foto_siswa'] = $namaFotoBaru;
        }

        $db->transBegin();

        try {
            if (!$siswaModel->update($id, $dataSiswa)) {
                throw new \Exception('Gagal update data profil siswa. ' . ($db->error()['message'] ?? ''));
            }

            $hpOrtu = $this->request->getPost('no_hp_ortu');
            $dataOrtu = [
                'nama_ayah'        => $getNull('nama_ayah') ?: '-',
                'nik_ayah'         => $getNull('nik_ayah'),
                'tahun_lahir_ayah' => $getNull('tahun_lahir_ayah'),
                'pendidikan_ayah'  => $getNull('pendidikan_ayah'),
                'pekerjaan_ayah'   => $getNull('pekerjaan_ayah') ?: '-',
                'penghasilan_ayah' => $getNull('penghasilan_ayah'),
                
                'nama_ibu'         => $getNull('nama_ibu') ?: '-',
                'nik_ibu'          => $getNull('nik_ibu'),
                'tahun_lahir_ibu'  => $getNull('tahun_lahir_ibu'),
                'pendidikan_ibu'   => $getNull('pendidikan_ibu'),
                'pekerjaan_ibu'    => $getNull('pekerjaan_ibu') ?: '-',
                'penghasilan_ibu'  => $getNull('penghasilan_ibu'),
                
                'nama_wali'        => $getNull('nama_wali') ?: '-',
                'nik_wali'         => $getNull('nik_wali'),
                'tahun_lahir_wali' => $getNull('tahun_lahir_wali'),
                'pendidikan_wali'  => $getNull('pendidikan_wali'),
                'pekerjaan_wali'   => $getNull('pekerjaan_wali') ?: '-',
                'penghasilan_wali' => $getNull('penghasilan_wali'),
                
                'no_hp_ortu'       => $hpOrtu,
                'email_ortu'       => $getNull('email_ortu'),
                'alamat_orangtua'  => $getNull('alamat_orangtua')
            ];

            $cekOrtu = $db->table('orangtua_wali')->where('siswa_id', $id)->get()->getRowArray();
            
            if ($cekOrtu) {
                if (!empty($dataOrtu['email_ortu'])) {
                    $cekEmail = $db->table('orangtua_wali')->where('email_ortu', $dataOrtu['email_ortu'])->where('id !=', $cekOrtu['id'])->countAllResults();
                    if ($cekEmail > 0) unset($dataOrtu['email_ortu']);
                }
                $db->table('orangtua_wali')->where('siswa_id', $id)->update($dataOrtu);
            } else {
                $usernameOrtu = !empty($hpOrtu) ? $hpOrtu : 'W' . $dataSiswa['nis'];
                $existingUserOrtu = $db->table('users')->where('username', $usernameOrtu)->get()->getRowArray();
                if ($existingUserOrtu) {
                    $userIdOrtu = $existingUserOrtu['id'];
                } else {
                    $db->table('users')->insert([
                        'username' => $usernameOrtu, 'password' => password_hash('12345678', PASSWORD_BCRYPT), 'role_id' => 4, 'is_active' => 1
                    ]);
                    $userIdOrtu = $db->insertID();
                }
                
                $dataOrtu['siswa_id'] = $id;
                $dataOrtu['user_id'] = $userIdOrtu;
                
                if (!empty($dataOrtu['email_ortu']) && $db->table('orangtua_wali')->where('email_ortu', $dataOrtu['email_ortu'])->countAllResults() > 0) {
                    $dataOrtu['email_ortu'] = null;
                }
                $db->table('orangtua_wali')->insert($dataOrtu);
            }

            $db->transCommit();
            return $this->response->setJSON(['status' => 'success', 'message' => 'Data Siswa dan Orang Tua berhasil diperbarui.']);
        } catch (\Exception $e) {
            $db->transRollback();
            return $this->response->setJSON(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    // --- HAPUS DATA ---
    public function delete($id)
    {
        $siswaModel = new SiswaModel();
        $siswa = $siswaModel->find($id);

        if ($siswa) {
            if ($siswa['foto_siswa'] && file_exists('uploads/siswa/' . $siswa['foto_siswa'])) {
                unlink('uploads/siswa/' . $siswa['foto_siswa']);
            }
            $siswaModel->delete($id);
            return $this->response->setJSON(['status' => 'success', 'message' => 'Data dihapus.']);
        } else {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Data tidak ditemukan.']);
        }
    }

    /**
     * --------------------------------------------------------------------------
     * DOWNLOAD TEMPLATE IMPORT SISWA (DENGAN DATA ASLI DARI DATABASE)
     * --------------------------------------------------------------------------
     */
    public function downloadTemplate()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // 1. Definisikan Header Kolom Template Siswa (Disesuaikan dengan tabel database)
        // Kita sertakan ID (Kolom A) agar nanti bisa digunakan untuk UPDATE jika ID ada.
        $headers = [
            'ID Siswa (JANGAN UBAH JIKA UPDATE)',
            'Nama Lengkap',
            'NIS',
            'NISN',
            'Email',
            'Jenis Kelamin (L/P)',
            'Tempat Lahir',
            'Tanggal Lahir (YYYY-MM-DD)',
            'Agama',
            'Anak Ke',
            'Status Dalam Keluarga',
            'Alamat',
            'No Telp Rumah',
            'Asal Sekolah',
            'Diterima di Kelas',
            'Tanggal Diterima (YYYY-MM-DD)',
            'Status Siswa (Aktif/Lulus/Pindah/Keluar)'
        ];

        $col = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($col . '1', $header);
            // Styling header agar tebal dan ber-background
            $sheet->getStyle($col . '1')->getFont()->setBold(true);
            $sheet->getStyle($col . '1')->getFill()
                ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                ->getStartColor()->setARGB('FFEFEFEF');
            $sheet->getColumnDimension($col)->setAutoSize(true);
            $col++;
        }

        // 2. Tarik Data Siswa Asli dari Database
        $siswaModel = new \App\Models\Admin\SiswaModel();
        // Ambil semua data siswa, urutkan berdasarkan nama
        $dataSiswa = $siswaModel->orderBy('nama_lengkap', 'ASC')->findAll();

        // 3. Masukkan Data ke Excel (Mulai dari Baris 2)
        $row = 2;
        foreach ($dataSiswa as $siswa) {
            // Kita petakan data dari DB ke kolom Excel
            $sheet->setCellValue('A' . $row, $siswa['id']);
            $sheet->setCellValue('B' . $row, $siswa['nama_lengkap']);

            // Format NIK/NISN/HP sebagai text agar angka '0' di depan tidak hilang
            $sheet->setCellValueExplicit('C' . $row, $siswa['nis'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet->setCellValueExplicit('D' . $row, $siswa['nisn'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);

            $sheet->setCellValue('E' . $row, $siswa['email_siswa']);
            $sheet->setCellValue('F' . $row, $siswa['jenis_kelamin']);
            $sheet->setCellValue('G' . $row, $siswa['tempat_lahir']);
            $sheet->setCellValue('H' . $row, $siswa['tanggal_lahir']);
            $sheet->setCellValue('I' . $row, $siswa['agama']);
            $sheet->setCellValue('J' . $row, $siswa['anak_ke']);
            $sheet->setCellValue('K' . $row, $siswa['status_dalam_keluarga']);
            $sheet->setCellValue('L' . $row, $siswa['alamat_siswa']);

            // Format Telp sebagai String
            $sheet->setCellValueExplicit('M' . $row, $siswa['no_telp_rumah'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);

            $sheet->setCellValue('N' . $row, $siswa['asal_sekolah']);
            $sheet->setCellValue('O' . $row, $siswa['diterima_dikelas']);
            $sheet->setCellValue('P' . $row, $siswa['tgl_diterima']);
            $sheet->setCellValue('Q' . $row, $siswa['status_siswa']);

            $row++;
        }

        // Jika database kosong, berikan 1 baris contoh kosong (opsional)
        if (empty($dataSiswa)) {
            $sheet->setCellValue('A2', '');
            $sheet->setCellValue('B2', 'Contoh: Ahmad Zidan');
            $sheet->setCellValueExplicit('C2', '08.26.0001', \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet->setCellValueExplicit('D2', '0012345678', \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet->setCellValue('E2', 'ahmad@email.com');
            $sheet->setCellValue('F2', 'L');
            $sheet->setCellValue('G2', 'Jakarta');
            $sheet->setCellValue('H2', '2008-05-20');
            $sheet->setCellValue('I2', 'Islam');
            $sheet->setCellValue('J2', '1');
            $sheet->setCellValue('K2', 'Anak Kandung');
            $sheet->setCellValue('L2', 'Jl. Merdeka No. 10');
            $sheet->setCellValueExplicit('M2', '081234567890', \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet->setCellValue('N2', 'SDN 1 Jakarta');
            $sheet->setCellValue('O2', 'VII');
            $sheet->setCellValue('P2', '2023-07-15');
            $sheet->setCellValue('Q2', 'Aktif');
        }

        // 4. Eksekusi Download File
        $filename = 'Data_Siswa_' . date('Y-m-d') . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }

/**
     * --------------------------------------------------------------------------
     * IMPORT DATA SISWA (MODE SMART UPDATE & SUPPORT NIS KEMBAR ANTAR ANGKATAN)
     * --------------------------------------------------------------------------
     */
    public function import()
    {
        ini_set('memory_limit', '2048M'); 
        ini_set('max_execution_time', '600');
        if (ob_get_length()) ob_clean();

        if (empty($_FILES)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'File ditolak server.']);
        }

        $file = $this->request->getFile('file_excel');
        if (!$file || !$file->isValid() || $file->hasMoved()) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'File gagal diunggah.']);
        }

        $db = \Config\Database::connect();
        
        // ==============================================================================
        // AUTO-FIX DB: Tambah Kolom & HAPUS ATURAN UNIQUE PADA NIS
        // ==============================================================================
        try {
            // Hapus aturan UNIQUE pada NIS karena sekolah mengizinkan NIS kembar beda angkatan
            $db->query("ALTER TABLE `siswa` DROP INDEX `nis`");
        } catch (\Exception $e) {
            // Abaikan jika index sudah terhapus sebelumnya
        }

        try {
            $fieldsSiswa = $db->getFieldNames('siswa');
            $newColumnsSiswa = [
                'nik' => 'VARCHAR(50) NULL', 'rt' => 'VARCHAR(10) NULL', 'rw' => 'VARCHAR(10) NULL',
                'dusun' => 'VARCHAR(100) NULL', 'kelurahan' => 'VARCHAR(100) NULL', 'kecamatan' => 'VARCHAR(100) NULL',
                'kode_pos' => 'VARCHAR(20) NULL', 'jenis_tinggal' => 'VARCHAR(100) NULL', 'alat_transportasi' => 'VARCHAR(100) NULL',
                'no_hp' => 'VARCHAR(50) NULL', 'skhun' => 'VARCHAR(100) NULL', 'penerima_kps' => 'VARCHAR(20) NULL',
                'no_kps' => 'VARCHAR(100) NULL', 'no_peserta_un' => 'VARCHAR(100) NULL', 'no_seri_ijazah' => 'VARCHAR(100) NULL',
                'penerima_kip' => 'VARCHAR(20) NULL', 'nomor_kip' => 'VARCHAR(100) NULL', 'nama_di_kip' => 'VARCHAR(150) NULL',
                'nomor_kks' => 'VARCHAR(100) NULL', 'no_registrasi_akta' => 'VARCHAR(100) NULL', 'layak_pip' => 'VARCHAR(20) NULL',
                'alasan_layak_pip' => 'VARCHAR(255) NULL', 'kebutuhan_khusus' => 'VARCHAR(100) NULL', 'no_kk' => 'VARCHAR(50) NULL',
                'berat_badan' => 'INT NULL', 'tinggi_badan' => 'INT NULL', 'lingkar_kepala' => 'INT NULL',
                'jml_saudara_kandung' => 'INT NULL', 'jarak_ke_sekolah' => 'VARCHAR(50) NULL'
            ];
            foreach ($newColumnsSiswa as $col => $type) {
                if (!in_array($col, $fieldsSiswa)) $db->query("ALTER TABLE `siswa` ADD COLUMN `$col` $type");
            }

            $fieldsOrtu = $db->getFieldNames('orangtua_wali');
            $newColumnsOrtu = [
                'tahun_lahir_ayah' => 'VARCHAR(10) NULL', 'pendidikan_ayah' => 'VARCHAR(100) NULL', 
                'penghasilan_ayah' => 'VARCHAR(100) NULL', 'nik_ayah' => 'VARCHAR(50) NULL',
                'tahun_lahir_ibu' => 'VARCHAR(10) NULL', 'pendidikan_ibu' => 'VARCHAR(100) NULL', 
                'penghasilan_ibu' => 'VARCHAR(100) NULL', 'nik_ibu' => 'VARCHAR(50) NULL',
                'tahun_lahir_wali' => 'VARCHAR(10) NULL', 'pendidikan_wali' => 'VARCHAR(100) NULL', 
                'penghasilan_wali' => 'VARCHAR(100) NULL', 'nik_wali' => 'VARCHAR(50) NULL'
            ];
            foreach ($newColumnsOrtu as $col => $type) {
                if (!in_array($col, $fieldsOrtu)) $db->query("ALTER TABLE `orangtua_wali` ADD COLUMN `$col` $type");
            }
        } catch (\Exception $e) {}

        $db->transBegin();

        try {
            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($file->getTempName());
            
            $dbRombel = $db->table('rombel')->get()->getResultArray();
            $mapRombel = [];
            foreach ($dbRombel as $r) {
                $key1 = strtolower(trim($r['tingkat'] . '-' . $r['nama_rombel']));
                $key2 = strtolower(trim($r['tingkat'] . ' ' . $r['nama_rombel']));
                $mapRombel[$key1] = $r['id'];
                $mapRombel[$key2] = $r['id'];
            }

            $countInsert = 0;
            $countUpdate = 0;

            $getInt = function($val) {
                $val = preg_replace('/[^0-9]/', '', explode('.', trim($val))[0]);
                return (empty($val) && $val !== '0') ? null : (int)$val;
            };

            foreach ($spreadsheet->getAllSheets() as $worksheet) {
                $sheet = $worksheet->toArray(null, true, true, true);

                foreach ($sheet as $idx => $row) {
                    $noUrut = trim($row['A'] ?? '');
                    if (!is_numeric($noUrut)) continue; 

                    $namaLengkap = trim($row['B'] ?? '');
                    if (empty($namaLengkap)) continue;

                    // Mengambil NIS dan NISN Asli dari Excel (NIS dibiarkan utuh apa adanya)
                    $nisRaw = trim($row['C'] ?? '');
                    $nisFinal = empty($nisRaw) ? null : substr($nisRaw, 0, 20);

                    $nisnRaw = preg_replace('/\.0$/', '', trim($row['E'] ?? ''));
                    $nisnFinal = empty($nisnRaw) ? null : substr($nisnRaw, 0, 20);

                    $nikRaw = preg_replace('/\.0$/', '', trim($row['H'] ?? ''));
                    $nikFinal = empty($nikRaw) ? null : substr($nikRaw, 0, 50);
                    
                    $emailRaw = trim($row['U'] ?? '');
                    $emailFinal = empty($emailRaw) ? null : substr($emailRaw, 0, 100);

                    $rombelExcel = strtolower(trim($row['AQ'] ?? ''));
                    $rombelId = null;
                    if (!empty($rombelExcel) && isset($mapRombel[$rombelExcel])) {
                        $rombelId = $mapRombel[$rombelExcel];
                    }

                    $tglLahir = null;
                    $tglRaw = trim($row['G'] ?? '');
                    if (!empty($tglRaw) && strtotime($tglRaw) !== false) {
                        $tglLahir = date('Y-m-d', strtotime($tglRaw));
                    }

                    $dataSiswa = [
                        'nama_lengkap'          => substr($namaLengkap, 0, 100),
                        'nis'                   => $nisFinal,
                        'nisn'                  => $nisnFinal,
                        'jenis_kelamin'         => (strtoupper(trim($row['D'] ?? '')) == 'P') ? 'P' : 'L',
                        'tempat_lahir'          => substr(trim($row['F'] ?? ''), 0, 50),
                        'tanggal_lahir'         => $tglLahir,
                        'nik'                   => $nikFinal,
                        'agama'                 => substr(trim($row['I'] ?? 'Islam'), 0, 20),
                        'alamat_siswa'          => trim($row['J'] ?? ''),
                        'rt'                    => substr(trim($row['K'] ?? ''), 0, 10),
                        'rw'                    => substr(trim($row['L'] ?? ''), 0, 10),
                        'dusun'                 => substr(trim($row['M'] ?? ''), 0, 100),
                        'kelurahan'             => substr(trim($row['N'] ?? ''), 0, 100),
                        'kecamatan'             => substr(trim($row['O'] ?? ''), 0, 100),
                        'kode_pos'              => substr(trim($row['P'] ?? ''), 0, 20),
                        'jenis_tinggal'         => substr(trim($row['Q'] ?? ''), 0, 100),
                        'alat_transportasi'     => substr(trim($row['R'] ?? ''), 0, 100),
                        'no_telp_rumah'         => substr(trim($row['S'] ?? ''), 0, 50),
                        'no_hp'                 => substr(preg_replace('/\.0$/', '', trim($row['T'] ?? '')), 0, 50),
                        'email_siswa'           => $emailFinal,
                        'skhun'                 => substr(trim($row['V'] ?? ''), 0, 100),
                        'penerima_kps'          => (strtolower(trim($row['W'] ?? '')) == 'ya') ? 'Ya' : 'Tidak',
                        'no_kps'                => substr(trim($row['X'] ?? ''), 0, 100),
                        'rombel_id'             => $rombelId,
                        'no_peserta_un'         => substr(trim($row['AR'] ?? ''), 0, 100), 
                        'no_seri_ijazah'        => substr(trim($row['AS'] ?? ''), 0, 100), 
                        'penerima_kip'          => (strtolower(trim($row['AT'] ?? '')) == 'ya') ? 'Ya' : 'Tidak',
                        'nomor_kip'             => substr(trim($row['AU'] ?? ''), 0, 100),
                        'nama_di_kip'           => substr(trim($row['AV'] ?? ''), 0, 150),
                        'nomor_kks'             => substr(trim($row['AW'] ?? ''), 0, 100),
                        'no_registrasi_akta'    => substr(trim($row['AX'] ?? ''), 0, 100),
                        'layak_pip'             => (strtolower(trim($row['BB'] ?? '')) == 'ya') ? 'Ya' : 'Tidak',
                        'alasan_layak_pip'      => substr(trim($row['BC'] ?? ''), 0, 255),
                        'kebutuhan_khusus'      => substr(trim($row['BD'] ?? 'Tidak ada'), 0, 100),
                        'asal_sekolah'          => substr(trim($row['BE'] ?? ''), 0, 100),
                        'anak_ke'               => $getInt($row['BF'] ?? ''),
                        'no_kk'                 => substr(preg_replace('/\.0$/', '', trim($row['BI'] ?? '')), 0, 50),
                        'berat_badan'           => $getInt($row['BJ'] ?? ''),
                        'tinggi_badan'          => $getInt($row['BK'] ?? ''),
                        'lingkar_kepala'        => $getInt($row['BL'] ?? ''),
                        'jml_saudara_kandung'   => $getInt($row['BM'] ?? ''),
                        'jarak_ke_sekolah'      => substr(trim($row['BN'] ?? ''), 0, 50),
                        'status_siswa'          => 'Aktif'
                    ];

                    try {
                        // CARI SISWA LAMA DENGAN LOGIKA YANG LEBIH AMAN!
                        $existing = null;
                        
                        // 1. Cari via NISN (Paling akurat)
                        if (!empty($nisnFinal)) {
                            $existing = $db->table('siswa')->where('nisn', $nisnFinal)->get()->getRowArray();
                        }
                        // 2. Cari via NIK
                        if (!$existing && !empty($nikFinal)) {
                            $existing = $db->table('siswa')->where('nik', $nikFinal)->get()->getRowArray();
                        }
                        // 3. Cari via NIS + Nama (Agar NIS yang kembar tapi beda angkatan tidak tertukar)
                        if (!$existing && !empty($nisFinal)) {
                            $existing = $db->table('siswa')
                                           ->where('nis', $nisFinal)
                                           ->where('nama_lengkap', $namaLengkap)
                                           ->get()->getRowArray();
                        }
                        // 4. Cari via Nama Saja
                        if (!$existing) {
                            $existing = $db->table('siswa')->where('nama_lengkap', $namaLengkap)->get()->getRowArray();
                        }

                        $siswaIdForOrtu = null;

                        if ($existing) {
                            // ================= SMART UPDATE =================
                            $dataUpdate = [];
                            foreach ($dataSiswa as $key => $val) {
                                if (empty($existing[$key]) && !empty($val)) {
                                    $dataUpdate[$key] = $val;
                                }
                            }
                            
                            if (!empty($dataUpdate)) {
                                // Cegah error duplikat khusus NISN dan Email jika memang ada yang sama di orang lain
                                if (isset($dataUpdate['nisn']) && $db->table('siswa')->where('nisn', $dataUpdate['nisn'])->where('id !=', $existing['id'])->countAllResults() > 0) unset($dataUpdate['nisn']);
                                if (isset($dataUpdate['email_siswa']) && $db->table('siswa')->where('email_siswa', $dataUpdate['email_siswa'])->where('id !=', $existing['id'])->countAllResults() > 0) unset($dataUpdate['email_siswa']);

                                if (!empty($dataUpdate)) {
                                    if (!$db->table('siswa')->where('id', $existing['id'])->update($dataUpdate)) {
                                        throw new \Exception("DB Update Siswa Error: " . ($db->error()['message'] ?? ''));
                                    }
                                }
                            }
                            $siswaIdForOrtu = $existing['id'];
                            $countUpdate++;
                        } else {
                            // ================= INSERT BARU =================
                            // Hindari error duplikat untuk kolom yang masih Unique
                            if (!empty($dataSiswa['nisn']) && $db->table('siswa')->where('nisn', $dataSiswa['nisn'])->countAllResults() > 0) $dataSiswa['nisn'] = null;
                            if (!empty($dataSiswa['email_siswa']) && $db->table('siswa')->where('email_siswa', $dataSiswa['email_siswa'])->countAllResults() > 0) $dataSiswa['email_siswa'] = null;

                            // Bikin User Akun
                            $username = !empty($dataSiswa['nisn']) ? $dataSiswa['nisn'] : (!empty($dataSiswa['nis']) ? $dataSiswa['nis'] : 'siswa' . time() . $idx);
                            if ($db->table('users')->where('username', substr($username, 0, 50))->countAllResults() > 0) {
                                $username = 'siswa' . time() . $idx;
                            }
                            
                            $userData = [
                                'username'  => substr($username, 0, 50),
                                'password'  => password_hash('12345678', PASSWORD_BCRYPT),
                                'role_id'   => 3,
                                'is_active' => 1
                            ];
                            if (!empty($dataSiswa['email_siswa'])) $userData['email'] = $dataSiswa['email_siswa'];
                            
                            if (!$db->table('users')->insert($userData)) throw new \Exception("Insert User: " . ($db->error()['message'] ?? ''));
                            $dataSiswa['user_id'] = $db->insertID();

                            // Masukkan Siswa (NIS dibiarkan masuk murni apa adanya)
                            if (!$db->table('siswa')->insert($dataSiswa)) throw new \Exception("Insert Siswa: " . ($db->error()['message'] ?? ''));
                            $siswaIdForOrtu = $db->insertID();
                            $countInsert++;
                        }

                        // ================= SIMPAN DATA ORANG TUA =================
                        $dataOrtuExcel = [
                            'siswa_id'         => $siswaIdForOrtu,
                            'nama_ayah'        => substr(trim($row['Y'] ?? ''), 0, 100),
                            'tahun_lahir_ayah' => substr(trim($row['Z'] ?? ''), 0, 10),
                            'pendidikan_ayah'  => substr(trim($row['AA'] ?? ''), 0, 100),
                            'pekerjaan_ayah'   => substr(trim($row['AB'] ?? ''), 0, 50),
                            'penghasilan_ayah' => substr(trim($row['AC'] ?? ''), 0, 100),
                            'nik_ayah'         => substr(preg_replace('/\.0$/', '', trim($row['AD'] ?? '')), 0, 50),
                            'nama_ibu'         => substr(trim($row['AE'] ?? ''), 0, 100),
                            'tahun_lahir_ibu'  => substr(trim($row['AF'] ?? ''), 0, 10),
                            'pendidikan_ibu'   => substr(trim($row['AG'] ?? ''), 0, 100),
                            'pekerjaan_ibu'    => substr(trim($row['AH'] ?? ''), 0, 50),
                            'penghasilan_ibu'  => substr(trim($row['AI'] ?? ''), 0, 100),
                            'nik_ibu'          => substr(preg_replace('/\.0$/', '', trim($row['AJ'] ?? '')), 0, 50),
                            'nama_wali'        => substr(trim($row['AK'] ?? ''), 0, 100),
                            'tahun_lahir_wali' => substr(trim($row['AL'] ?? ''), 0, 10),
                            'pendidikan_wali'  => substr(trim($row['AM'] ?? ''), 0, 100),
                            'pekerjaan_wali'   => substr(trim($row['AN'] ?? ''), 0, 50),
                            'penghasilan_wali' => substr(trim($row['AO'] ?? ''), 0, 100),
                            'nik_wali'         => substr(preg_replace('/\.0$/', '', trim($row['AP'] ?? '')), 0, 50)
                        ];

                        $ortuExist = $db->table('orangtua_wali')->where('siswa_id', $siswaIdForOrtu)->get()->getRowArray();
                        if ($ortuExist) {
                            $dataOrtuUpdate = [];
                            foreach ($dataOrtuExcel as $key => $val) {
                                if (empty($ortuExist[$key]) && !empty($val)) $dataOrtuUpdate[$key] = $val;
                            }
                            if (!empty($dataOrtuUpdate)) $db->table('orangtua_wali')->where('siswa_id', $siswaIdForOrtu)->update($dataOrtuUpdate);
                        } else {
                            $db->table('orangtua_wali')->insert($dataOrtuExcel);
                        }

                    } catch (\Exception $dbRowErr) {
                        $db->transRollback();
                        return $this->response->setJSON([
                            'status' => 'error', 
                            'message' => "<strong>Gagal di Siswa: {$namaLengkap}</strong><br>" . $dbRowErr->getMessage()
                        ]);
                    }
                }
            } 

            if ($db->transStatus() === false) {
                $db->transRollback();
                return $this->response->setJSON(['status' => 'error', 'message' => 'Transaksi database dibatalkan.']);
            }

            $db->transCommit();
            return $this->response->setJSON(['status' => 'success', 'message' => "Import Dapodik Sukses! $countInsert Baru, $countUpdate Diperbarui (NIS diizinkan kembar)."]);
            
        } catch (\Throwable $e) {
            if (isset($db)) $db->transRollback();
            return $this->response->setJSON(['status' => 'error', 'message' => 'Fatal Error: ' . $e->getMessage()]);
        }
    }

    // --- API: AMBIL DAFTAR KECAMATAN DARI DATABASE ---
    public function getKecamatan()
    {
        $db = \Config\Database::connect();
        $data = $db->table('kecamatan')
            ->select('nama')
            ->groupBy('nama')
            ->orderBy('nama', 'ASC')
            ->get()->getResultArray();
            
        return $this->response->setJSON($data);
    }

    // --- API: AMBIL DAFTAR KELURAHAN BERDASARKAN KECAMATAN ---
    public function getKelurahan()
    {
        $kecamatan = $this->request->getGet('kecamatan');
        $db = \Config\Database::connect();
        
        $builder = $db->table('desa')->select('nama')->groupBy('nama')->orderBy('nama', 'ASC');
        
        if (!empty($kecamatan)) {
            $builder->where('kecamatan', $kecamatan);
        }
        
        $data = $builder->get()->getResultArray();
        
        return $this->response->setJSON($data);
    }
}
