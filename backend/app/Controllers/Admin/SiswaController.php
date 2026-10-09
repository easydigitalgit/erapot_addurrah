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

        // ========================================================================
        // FITUR AUTO-FIX DATABASE: Menambahkan 3 kolom ekskul ke tabel siswa
        // ========================================================================
        $fieldsSiswa = $db->getFieldNames('siswa');
        if (!in_array('ekskul_1', $fieldsSiswa))
            $db->query("ALTER TABLE `siswa` ADD `ekskul_1` INT(11) NULL");
        if (!in_array('ekskul_2', $fieldsSiswa))
            $db->query("ALTER TABLE `siswa` ADD `ekskul_2` INT(11) NULL");
        if (!in_array('ekskul_3', $fieldsSiswa))
            $db->query("ALTER TABLE `siswa` ADD `ekskul_3` INT(11) NULL");

        // 1. OPTIMASI QUERY STATISTIK (Hanya 1x Query)
        $stats = $db->table('siswa')
            ->select('
                SUM(CASE WHEN status_siswa = "Aktif" THEN 1 ELSE 0 END) as total_siswa,
                SUM(CASE WHEN status_siswa = "Lulus" THEN 1 ELSE 0 END) as total_alumni,
                SUM(CASE WHEN jenis_kelamin = "L" AND status_siswa = "Aktif" THEN 1 ELSE 0 END) as total_laki,
                SUM(CASE WHEN jenis_kelamin = "P" AND status_siswa = "Aktif" THEN 1 ELSE 0 END) as total_perempuan
            ')
            ->get()
            ->getRowArray();

        // 2. DINAMISASI FILTER TAHUN AJARAN
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

        // 4. AMBIL DAFTAR EKSKUL AKTIF
        $ekskulList = $db->table('master_ekskul')->where('status', 'Aktif')->orderBy('nama_ekskul', 'ASC')->get()->getResultArray();

        $data = [
            'user' => 'Admin',
            'navigations' => $this->getSidebarMenu(),
            'total_siswa' => $stats['total_siswa'] ?? 0,
            'total_alumni' => $stats['total_alumni'] ?? 0,
            'total_laki' => $stats['total_laki'] ?? 0,
            'total_perempuan' => $stats['total_perempuan'] ?? 0,
            'tahun_ajaran' => $tahunAjaran,
            'tingkat_rombel' => $tingkatRombel,
            'ekskulList' => $ekskulList, // Kirim ke View
            'color' => $this->getColor()
        ];

        return view('admin/siswa', $data);
    }

    // --- API: AMBIL SEMUA DATA SISWA (DENGAN SUNTIKAN WALI KELAS & MESIN WAKTU) ---
    public function getAll()
    {
        $db = \Config\Database::connect();
        
        // 1. Deteksi Tahun Ajaran Aktif
        $ta_aktif = $db->table('tahun_ajaran')->where('status', 'Aktif')->get()->getRowArray();
        $ta_id = $ta_aktif ? $ta_aktif['id'] : 0;

        // 2. Bangun Query Anti-Gaib (Konsolidasi Seluruh Data Santri & Ortu)
        $builder = $db->table('siswa s');
        $builder->select('
            s.*, 
            r.nama_rombel, 
            r.tingkat, 
            gt.nama_lengkap as nama_wali_kelas,
            ow.nama_ayah, ow.nik_ayah, ow.tahun_lahir_ayah, ow.pendidikan_ayah, ow.pekerjaan_ayah, ow.penghasilan_ayah,
            ow.nama_ibu, ow.nik_ibu, ow.tahun_lahir_ibu, ow.pendidikan_ibu, ow.pekerjaan_ibu, ow.penghasilan_ibu,
            ow.nama_wali, ow.nik_wali, ow.tahun_lahir_wali, ow.pendidikan_wali, ow.pekerjaan_wali, ow.penghasilan_wali,
            ow.no_hp_ortu, ow.email_ortu, ow.alamat_orangtua
        ');

        // 🚀 JOIN MESIN WAKTU (Ambil Rombel Tahun Ajaran Aktif)
        $builder->join('anggota_rombel ar', "ar.siswa_id = s.id AND ar.tahun_ajaran_id = $ta_id", 'left');
        $builder->join('rombel r', 'r.id = COALESCE(ar.rombel_id, s.rombel_id)', 'left');

        // 🚀 JOIN GURU: Ambil nama lengkap wali kelas
        $builder->join('guru_tendik gt', 'gt.id = r.wali_kelas_id', 'left');

        // 🚀 JOIN ORTU: Ambil data orang tua/wali untuk modal edit
        $builder->join('orangtua_wali ow', 'ow.siswa_id = s.id', 'left');

        $data = $builder->get()->getResultArray();
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

    // --- SIMPAN DATA BARU (DENGAN KOMPRESI WEBP) ---
    public function store()
    {
        $siswaModel = new SiswaModel();
        $db = \Config\Database::connect();

        $tglDiterima = $this->request->getPost('tgl_diterima') ?: date('Y-m-d');
        $tahunMasuk = date('y', strtotime($tglDiterima));

        // AUTO ANGKATAN & RESET
        $lastSiswa = $siswaModel->orderBy('id', 'DESC')->first();
        if ($lastSiswa && !empty($lastSiswa['nis']) && strpos($lastSiswa['nis'], '.') !== false) {
            $parts = explode('.', $lastSiswa['nis']);
            $lastAngkatan = (int) $parts[0];
            $lastTahun = $parts[1];
            $angkatanBaru = ($tahunMasuk > $lastTahun) ? $lastAngkatan + 1 : $lastAngkatan;
        } else {
            $angkatanBaru = 1;
        }

        $prefixNis = sprintf("%02d", $angkatanBaru) . '.' . $tahunMasuk . '.';
        $cekUrutan = $siswaModel->like('nis', $prefixNis, 'after')->orderBy('nis', 'DESC')->first();
        $nextUrut = $cekUrutan ? ((int) explode('.', $cekUrutan['nis'])[2] + 1) : 1;
        $nisFinal = $prefixNis . sprintf("%05d", $nextUrut);

        $fileFoto = $this->request->getFile('photo');
        $namaFotoDB = null;

        // 1. UPLOAD FOTO KE FOLDER AVATARS
        if ($fileFoto && $fileFoto->isValid() && !$fileFoto->hasMoved()) {
            $path = FCPATH . 'assets/uploads/avatars/';
            if (!is_dir($path))
                mkdir($path, 0777, true);

            $newName = $fileFoto->getRandomName();
            $namaFotoDB = pathinfo($newName, PATHINFO_FILENAME) . '.webp';
            $savePath = $path . $namaFotoDB;

            try {
                \Config\Services::image()
                    ->withFile($fileFoto->getTempName())
                    ->convert(IMAGETYPE_WEBP)
                    ->save($savePath, 75);
            } catch (\Exception $e) {
                $fileFoto->move($path, $newName);
                $namaFotoDB = $newName;
            }
        }

        $getNull = function ($key) {
            $val = $this->request->getPost($key);
            return ($val === '' || $val === null) ? null : $val;
        };

        // KUMPULKAN SEMUA DATA SISWA
        $dataSiswa = [
            'nis' => $nisFinal,
            'nisn' => $getNull('nisn'),
            'nik' => $getNull('nik'),
            'nama_lengkap' => $this->request->getPost('nama_lengkap'),
            'jenis_kelamin' => $getNull('jenis_kelamin'),
            'tempat_lahir' => $getNull('tempat_lahir'),
            'tanggal_lahir' => $getNull('tanggal_lahir'),
            'agama' => $getNull('agama'),
            'no_kk' => $getNull('no_kk'),
            'no_registrasi_akta' => $getNull('no_registrasi_akta'),
            'status_dalam_keluarga' => $getNull('status_dalam_keluarga'),
            'anak_ke' => $getNull('anak_ke'),
            'jml_saudara_kandung' => $getNull('jml_saudara_kandung'),
            'kebutuhan_khusus' => $getNull('kebutuhan_khusus'),
            'berat_badan' => $getNull('berat_badan'),
            'tinggi_badan' => $getNull('tinggi_badan'),
            'lingkar_kepala' => $getNull('lingkar_kepala'),
            'alamat_siswa' => $getNull('alamat_siswa'),
            'rt' => $getNull('rt'),
            'rw' => $getNull('rw'),
            'dusun' => $getNull('dusun'),
            'kelurahan' => $getNull('kelurahan'),
            'kecamatan' => $getNull('kecamatan'),
            'kode_pos' => $getNull('kode_pos'),
            'jenis_tinggal' => $getNull('jenis_tinggal'),
            'alat_transportasi' => $getNull('alat_transportasi'),
            'jarak_ke_sekolah' => $getNull('jarak_ke_sekolah'),
            'no_telp_rumah' => $getNull('no_telp_rumah'),
            'no_hp' => $getNull('no_hp'),
            'email_siswa' => $getNull('email_siswa'),
            'asal_sekolah' => $getNull('asal_sekolah'),
            'skhun' => $getNull('skhun'),
            'no_peserta_un' => $getNull('no_peserta_un'),
            'no_seri_ijazah' => $getNull('no_seri_ijazah'),
            'diterima_dikelas' => $getNull('diterima_dikelas'),
            'tgl_diterima' => $tglDiterima,
            'rombel_id' => $getNull('rombel_id'),
            'penerima_kps' => $getNull('penerima_kps'),
            'no_kps' => $getNull('no_kps'),
            'penerima_kip' => $getNull('penerima_kip'),
            'nomor_kip' => $getNull('nomor_kip'),
            'nama_di_kip' => $getNull('nama_di_kip'),
            'nomor_kks' => $getNull('nomor_kks'),
            'layak_pip' => $getNull('layak_pip'),
            'alasan_layak_pip' => $getNull('alasan_layak_pip'),
            'ekskul_1' => $getNull('ekskul_1'),
            'ekskul_2' => $getNull('ekskul_2'),
            'ekskul_3' => $getNull('ekskul_3'),
            'foto_siswa' => $namaFotoDB,
            'status_siswa' => $this->request->getPost('status_siswa') ?: 'Aktif'
        ];

        // Validasi Ekskul Kembar
        $selectedEkskuls = array_filter([$dataSiswa['ekskul_1'], $dataSiswa['ekskul_2'], $dataSiswa['ekskul_3']]);
        if (count($selectedEkskuls) !== count(array_unique($selectedEkskuls))) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Siswa tidak boleh memilih Ekstrakurikuler yang sama lebih dari 1 kali!']);
        }

        if (!empty($dataSiswa['nisn']) && $siswaModel->where('nisn', $dataSiswa['nisn'])->countAllResults() > 0) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'NISN tersebut sudah terdaftar pada siswa lain!']);
        }
        if (!empty($dataSiswa['nik']) && $siswaModel->where('nik', $dataSiswa['nik'])->countAllResults() > 0) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'NIK tersebut sudah terdaftar pada siswa lain!']);
        }
        if (!empty($dataSiswa['nis']) && $siswaModel->where('nis', $dataSiswa['nis'])->countAllResults() > 0) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'NIS tersebut sudah terdaftar pada siswa lain!']);
        }
        // FITUR BARU: CEK NOMOR HP SISWA KEMBAR
        // if (!empty($dataSiswa['no_hp'])) {
        //     $cekHpSiswa = $siswaModel->where('no_hp', $dataSiswa['no_hp'])->first();
        //     if ($cekHpSiswa) {
        //         return $this->response->setJSON([
        //             'status' => 'error',
        //             'message' => 'Gagal! Nomor HP Siswa ini sudah terdaftar pada: <b>' . $cekHpSiswa['nama_lengkap'] . '</b>'
        //         ]);
        //     }
        // }

        $db->transBegin();

        try {
            $username = !empty($dataSiswa['nisn']) ? $dataSiswa['nisn'] : $dataSiswa['nis'];
            if ($db->table('users')->where('username', $username)->countAllResults() > 0) {
                $username = $username . rand(10, 99);
            }

            $userData = [
                'username' => $username,
                'password' => password_hash('12345678', PASSWORD_BCRYPT),
                'role_id' => 3,
                'is_active' => 1,
                'foto_profil' => $namaFotoDB // <-- FOTO DISIMPAN DI SINI
            ];

            if (!$db->table('users')->insert($userData)) {
                throw new \Exception('Gagal membuat akun login Siswa. ' . ($db->error()['message'] ?? ''));
            }
            $dataSiswa['user_id'] = $db->insertID();

            if (!$siswaModel->insert($dataSiswa)) {
                throw new \Exception('Gagal menyimpan profil Siswa. ' . ($db->error()['message'] ?? ''));
            }
            $siswaId = $siswaModel->getInsertID();

            // --- 🚀 SUNTIKAN MESIN WAKTU (Sinkronisasi Rombel) ---
            if (!empty($dataSiswa['rombel_id'])) {
                $taAktif = $db->table('tahun_ajaran')->where('status', 'Aktif')->get()->getRowArray();
                if ($taAktif) {
                    $db->table('anggota_rombel')->insert([
                        'siswa_id'        => $siswaId,
                        'rombel_id'       => $dataSiswa['rombel_id'],
                        'tahun_ajaran_id' => $taAktif['id'],
                        'semester'        => $taAktif['semester']
                    ]);
                }
            }

            $hpOrtu = $this->request->getPost('no_hp_ortu');
            $usernameOrtu = !empty($hpOrtu) ? $hpOrtu : 'W' . $dataSiswa['nis'];

            $existingUserOrtu = $db->table('users')->where('username', $usernameOrtu)->get()->getRowArray();

            if ($existingUserOrtu) {
                $userIdOrtu = $existingUserOrtu['id'];
            } else {
                $ortuAccount = [
                    'username' => $usernameOrtu,
                    'password' => password_hash('12345678', PASSWORD_BCRYPT),
                    'role_id' => 4,
                    'is_active' => 1
                ];
                if (!$db->table('users')->insert($ortuAccount)) {
                    throw new \Exception('Gagal membuat akun login Wali. ' . ($db->error()['message'] ?? ''));
                }
                $userIdOrtu = $db->insertID();
            }

            $dataOrtu = [
                'nama_ayah' => $getNull('nama_ayah') ?: '-',
                'nik_ayah' => $getNull('nik_ayah'),
                'tahun_lahir_ayah' => $getNull('tahun_lahir_ayah'),
                'pendidikan_ayah' => $getNull('pendidikan_ayah'),
                'pekerjaan_ayah' => $getNull('pekerjaan_ayah') ?: '-',
                'penghasilan_ayah' => $getNull('penghasilan_ayah'),

                'nama_ibu' => $getNull('nama_ibu') ?: '-',
                'nik_ibu' => $getNull('nik_ibu'),
                'tahun_lahir_ibu' => $getNull('tahun_lahir_ibu'),
                'pendidikan_ibu' => $getNull('pendidikan_ibu'),
                'pekerjaan_ibu' => $getNull('pekerjaan_ibu') ?: '-',
                'penghasilan_ibu' => $getNull('penghasilan_ibu'),

                'nama_wali' => $getNull('nama_wali') ?: '-',
                'nik_wali' => $getNull('nik_wali'),
                'tahun_lahir_wali' => $getNull('tahun_lahir_wali'),
                'pendidikan_wali' => $getNull('pendidikan_wali'),
                'pekerjaan_wali' => $getNull('pekerjaan_wali') ?: '-',
                'penghasilan_wali' => $getNull('penghasilan_wali'),

                'no_hp_ortu' => $hpOrtu,
                'email_ortu' => $getNull('email_ortu'),
                'alamat_orangtua' => $getNull('alamat_orangtua')
            ];

            // LOGIKA BARU: Selalu insert record baru di orangtua_wali untuk tiap siswa baru
            // Meskipun user_id (akun login) yang sama digunakan (misal: kakak-beradik)
            $dataOrtu['siswa_id'] = $siswaId;
            $dataOrtu['user_id']  = $userIdOrtu;

            if (!$db->table('orangtua_wali')->insert($dataOrtu)) {
                throw new \Exception('Gagal menyimpan profil Wali. ' . ($db->error()['message'] ?? ''));
            }

            $db->transCommit();
            return $this->response->setJSON(['status' => 'success', 'message' => "Berhasil! Siswa dan Wali berhasil disimpan. NIS: $nisFinal"]);
        } catch (\Exception $e) {
            $db->transRollback();
            return $this->response->setJSON(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    // --- UPDATE DATA (DENGAN KOMPRESI WEBP) ---
    public function update($id)
    {
        $siswaModel = new SiswaModel();
        $db = \Config\Database::connect();

        $siswaLama = $siswaModel->find($id);
        if (!$siswaLama)
            return $this->response->setJSON(['status' => 'error', 'message' => 'Data tidak ditemukan.']);

        $getNull = function ($key) {
            $val = $this->request->getPost($key);
            return ($val === '' || $val === null) ? null : $val;
        };

        $dataSiswa = [
            'nis' => $this->request->getPost('nis'),
            'nisn' => $getNull('nisn'),
            'nik' => $getNull('nik'),
            'nama_lengkap' => $this->request->getPost('nama_lengkap'),
            'jenis_kelamin' => $getNull('jenis_kelamin'),
            'tempat_lahir' => $getNull('tempat_lahir'),
            'tanggal_lahir' => $getNull('tanggal_lahir'),
            'agama' => $getNull('agama'),
            'no_kk' => $getNull('no_kk'),
            'no_registrasi_akta' => $getNull('no_registrasi_akta'),
            'status_dalam_keluarga' => $getNull('status_dalam_keluarga'),
            'anak_ke' => $getNull('anak_ke'),
            'jml_saudara_kandung' => $getNull('jml_saudara_kandung'),
            'kebutuhan_khusus' => $getNull('kebutuhan_khusus'),
            'berat_badan' => $getNull('berat_badan'),
            'tinggi_badan' => $getNull('tinggi_badan'),
            'lingkar_kepala' => $getNull('lingkar_kepala'),
            'alamat_siswa' => $getNull('alamat_siswa'),
            'rt' => $getNull('rt'),
            'rw' => $getNull('rw'),
            'dusun' => $getNull('dusun'),
            'kelurahan' => $getNull('kelurahan'),
            'kecamatan' => $getNull('kecamatan'),
            'kode_pos' => $getNull('kode_pos'),
            'jenis_tinggal' => $getNull('jenis_tinggal'),
            'alat_transportasi' => $getNull('alat_transportasi'),
            'jarak_ke_sekolah' => $getNull('jarak_ke_sekolah'),
            'no_telp_rumah' => $getNull('no_telp_rumah'),
            'no_hp' => $getNull('no_hp'),
            'email_siswa' => $getNull('email_siswa'),
            'asal_sekolah' => $getNull('asal_sekolah'),
            'skhun' => $getNull('skhun'),
            'no_peserta_un' => $getNull('no_peserta_un'),
            'no_seri_ijazah' => $getNull('no_seri_ijazah'),
            'diterima_dikelas' => $getNull('diterima_dikelas'),
            'tgl_diterima' => $getNull('tgl_diterima'),
            'rombel_id' => $getNull('rombel_id'),
            'penerima_kps' => $getNull('penerima_kps'),
            'no_kps' => $getNull('no_kps'),
            'penerima_kip' => $getNull('penerima_kip'),
            'nomor_kip' => $getNull('nomor_kip'),
            'nama_di_kip' => $getNull('nama_di_kip'),
            'nomor_kks' => $getNull('nomor_kks'),
            'layak_pip' => $getNull('layak_pip'),
            'alasan_layak_pip' => $getNull('alasan_layak_pip'),
            'ekskul_1' => $getNull('ekskul_1'),
            'ekskul_2' => $getNull('ekskul_2'),
            'ekskul_3' => $getNull('ekskul_3'),
            'status_siswa' => $this->request->getPost('status_siswa') ?: 'Aktif'
        ];

        // Validasi Ekskul Kembar
        $selectedEkskuls = array_filter([$dataSiswa['ekskul_1'], $dataSiswa['ekskul_2'], $dataSiswa['ekskul_3']]);
        if (count($selectedEkskuls) !== count(array_unique($selectedEkskuls))) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Siswa tidak boleh memilih Ekstrakurikuler yang sama lebih dari 1 kali!']);
        }

        if (!empty($dataSiswa['nisn']) && $siswaModel->where('nisn', $dataSiswa['nisn'])->where('id !=', $id)->countAllResults() > 0) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'NISN tersebut sudah dipakai siswa lain.']);
        }
        if (!empty($dataSiswa['nik']) && $siswaModel->where('nik', $dataSiswa['nik'])->where('id !=', $id)->countAllResults() > 0) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'NIK tersebut sudah dipakai siswa lain.']);
        }
        if (!empty($dataSiswa['nis']) && $siswaModel->where('nis', $dataSiswa['nis'])->where('id !=', $id)->countAllResults() > 0) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'NIS tersebut sudah dipakai siswa lain.']);
        }
        // FITUR BARU: CEK NOMOR HP SISWA KEMBAR SAAT EDIT
        // if (!empty($dataSiswa['no_hp'])) {
        //     $cekHpSiswa = $siswaModel->where('no_hp', $dataSiswa['no_hp'])->where('id !=', $id)->first();
        //     if ($cekHpSiswa) {
        //         return $this->response->setJSON([
        //             'status' => 'error',
        //             'message' => 'Gagal! Nomor HP Siswa ini sudah terdaftar pada: <b>' . $cekHpSiswa['nama_lengkap'] . '</b>'
        //         ]);
        //     }
        // }

        // ... (kode $dataSiswa di atasnya) ...

        $fileFoto = $this->request->getFile('photo');
        $userLama = $db->table('users')->where('id', $siswaLama['user_id'])->get()->getRowArray();
        $namaFotoDB = $userLama['foto_profil'] ?? null;

        // 1. UPDATE FOTO KE FOLDER AVATARS
        if ($fileFoto && $fileFoto->isValid() && !$fileFoto->hasMoved()) {
            $path = FCPATH . 'assets/uploads/avatars/';
            if (!is_dir($path))
                mkdir($path, 0777, true);

            if (!empty($namaFotoDB) && file_exists($path . $namaFotoDB)) {
                unlink($path . $namaFotoDB);
            }

            $newName = $fileFoto->getRandomName();
            $namaFotoBaru = pathinfo($newName, PATHINFO_FILENAME) . '.webp';
            $savePath = $path . $namaFotoBaru;

            try {
                \Config\Services::image()
                    ->withFile($fileFoto->getTempName())
                    ->convert(IMAGETYPE_WEBP)
                    ->save($savePath, 75);

                // DUA BARIS INI KUNCINYA:
                $namaFotoDB = $namaFotoBaru; // Update variabel untuk tabel users
                $dataSiswa['foto_siswa'] = $namaFotoBaru; // Update variabel untuk tabel siswa

            } catch (\Exception $e) {
                $fileFoto->move($path, $newName);

                // DUA BARIS INI KUNCINYA:
                $namaFotoDB = $newName;
                $dataSiswa['foto_siswa'] = $newName;
            }
        }

        $db->transBegin();

        try {
            if (!$siswaModel->update($id, $dataSiswa)) {
                throw new \Exception('Gagal update data profil siswa. ' . ($db->error()['message'] ?? ''));
            }

            // --- 🚀 SUNTIKAN MESIN WAKTU (Sinkronisasi Rombel saat Update) ---
            $taAktif = $db->table('tahun_ajaran')->where('status', 'Aktif')->get()->getRowArray();
            if ($taAktif) {
                $cekMesinWaktu = $db->table('anggota_rombel')
                    ->where('siswa_id', $id)
                    ->where('tahun_ajaran_id', $taAktif['id'])
                    ->get()->getRowArray();

                if (!empty($dataSiswa['rombel_id'])) {
                    if ($cekMesinWaktu) {
                        // Jika sudah ada, update rombel-nya
                        $db->table('anggota_rombel')
                            ->where('id', $cekMesinWaktu['id'])
                            ->update(['rombel_id' => $dataSiswa['rombel_id'], 'semester' => $taAktif['semester']]);
                    } else {
                        // Jika belum ada, buat baru
                        $db->table('anggota_rombel')->insert([
                            'siswa_id'        => $id,
                            'rombel_id'       => $dataSiswa['rombel_id'],
                            'tahun_ajaran_id' => $taAktif['id'],
                            'semester'        => $taAktif['semester']
                        ]);
                    }
                } else {
                    // Jika rombel_id di-null-kan, hapus dari mesin waktu tahun ini
                    if ($cekMesinWaktu) {
                        $db->table('anggota_rombel')->where('id', $cekMesinWaktu['id'])->delete();
                    }
                }
            }

            // 2. UPDATE FOTO DI TABEL USERS
            $db->table('users')->where('id', $siswaLama['user_id'])->update(['foto_profil' => $namaFotoDB]);

            // ... (lanjutkan kode ortu di bawahnya) ...

            $hpOrtu = $this->request->getPost('no_hp_ortu');
            $dataOrtu = [
                'nama_ayah' => $getNull('nama_ayah') ?: '-',
                'nik_ayah' => $getNull('nik_ayah'),
                'tahun_lahir_ayah' => $getNull('tahun_lahir_ayah'),
                'pendidikan_ayah' => $getNull('pendidikan_ayah'),
                'pekerjaan_ayah' => $getNull('pekerjaan_ayah') ?: '-',
                'penghasilan_ayah' => $getNull('penghasilan_ayah'),

                'nama_ibu' => $getNull('nama_ibu') ?: '-',
                'nik_ibu' => $getNull('nik_ibu'),
                'tahun_lahir_ibu' => $getNull('tahun_lahir_ibu'),
                'pendidikan_ibu' => $getNull('pendidikan_ibu'),
                'pekerjaan_ibu' => $getNull('pekerjaan_ibu') ?: '-',
                'penghasilan_ibu' => $getNull('penghasilan_ibu'),

                'nama_wali' => $getNull('nama_wali') ?: '-',
                'nik_wali' => $getNull('nik_wali'),
                'tahun_lahir_wali' => $getNull('tahun_lahir_wali'),
                'pendidikan_wali' => $getNull('pendidikan_wali'),
                'pekerjaan_wali' => $getNull('pekerjaan_wali') ?: '-',
                'penghasilan_wali' => $getNull('penghasilan_wali'),

                'no_hp_ortu' => $hpOrtu,
                'email_ortu' => $getNull('email_ortu'),
                'alamat_orangtua' => $getNull('alamat_orangtua')
            ];

            $cekOrtu = $db->table('orangtua_wali')->where('siswa_id', $id)->get()->getRowArray();

            // Cari atau buat akun user untuk orang tua berdasarkan nomor HP (username)
            $usernameOrtu = !empty($hpOrtu) ? $hpOrtu : 'W' . $dataSiswa['nis'];
            $existingUserOrtu = $db->table('users')->where('username', $usernameOrtu)->get()->getRowArray();
            
            if ($existingUserOrtu) {
                $userIdOrtu = $existingUserOrtu['id'];
            } else {
                $db->table('users')->insert([
                    'username' => $usernameOrtu,
                    'password' => password_hash('12345678', PASSWORD_BCRYPT),
                    'role_id' => 4,
                    'is_active' => 1
                ]);
                $userIdOrtu = $db->insertID();
            }

            $dataOrtu['user_id'] = $userIdOrtu;

            if ($cekOrtu) {
                $db->table('orangtua_wali')->where('siswa_id', $id)->update($dataOrtu);
            } else {
                $dataOrtu['siswa_id'] = $id;
                $db->table('orangtua_wali')->insert($dataOrtu);
            }

            $db->transCommit();
            return $this->response->setJSON(['status' => 'success', 'message' => 'Data Siswa dan Orang Tua berhasil diperbarui.']);
        } catch (\Exception $e) {
            $db->transRollback();
            return $this->response->setJSON(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    public function export()
    {
        $db = \Config\Database::connect();
        
        // 1. Ambil Tahun Ajaran Aktif
        $ta_aktif = $db->table('tahun_ajaran')->where('status', 'Aktif')->get()->getRowArray();
        $ta_id = $ta_aktif ? $ta_aktif['id'] : 0;

        // 2. Tarik Data Santri Lengkap dengan Rombelnya
        $builder = $db->table('siswa s');
        $builder->select('s.*, r.nama_rombel, r.tingkat');
        $builder->join('anggota_rombel ar', "ar.siswa_id = s.id AND ar.tahun_ajaran_id = $ta_id", 'left');
        $builder->join('rombel r', 'r.id = COALESCE(ar.rombel_id, s.rombel_id)', 'left');
        $builder->orderBy('r.tingkat', 'ASC');
        $builder->orderBy('r.nama_rombel', 'ASC');
        $builder->orderBy('s.nama_lengkap', 'ASC');
        
        $dataSiswa = $builder->get()->getResultArray();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Data Siswa');

        // 3. Susun Header
        $headers = [
            'No',
            'Nama Lengkap',
            'NIS',
            'NISN',
            'Jenis Kelamin',
            'Tempat Lahir',
            'Tanggal Lahir',
            'Kelas Saat Ini',
            'Agama',
            'Status Siswa',
            'Alamat',
            'Asal Sekolah',
            'Diterima di Kelas',
            'Tgl Diterima'
        ];

        $col = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($col . '1', $header);
            $sheet->getStyle($col . '1')->getFont()->setBold(true);
            $sheet->getStyle($col . '1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FFEFEFEF');
            $sheet->getColumnDimension($col)->setAutoSize(true);
            $col++;
        }

        // 4. Isi Data Asli dari Database
        $row = 2;
        $no = 1;
        foreach ($dataSiswa as $siswa) {
            $sheet->setCellValue('A' . $row, $no++);
            $sheet->setCellValue('B' . $row, $siswa['nama_lengkap']);
            $sheet->setCellValueExplicit('C' . $row, $siswa['nis'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet->setCellValueExplicit('D' . $row, $siswa['nisn'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet->setCellValue('E' . $row, $siswa['jenis_kelamin']);
            $sheet->setCellValue('F' . $row, $siswa['tempat_lahir']);
            $sheet->setCellValue('G' . $row, $siswa['tanggal_lahir']);
            
            $kelas = ($siswa['tingkat'] && $siswa['nama_rombel']) ? $siswa['tingkat'] . ' ' . $siswa['nama_rombel'] : '-';
            $sheet->setCellValue('H' . $row, $kelas);
            
            $sheet->setCellValue('I' . $row, $siswa['agama']);
            $sheet->setCellValue('J' . $row, $siswa['status_siswa']);
            $sheet->setCellValue('K' . $row, $siswa['alamat_siswa']);
            $sheet->setCellValue('L' . $row, $siswa['asal_sekolah']);
            $sheet->setCellValue('M' . $row, $siswa['diterima_dikelas']);
            $sheet->setCellValue('N' . $row, $siswa['tgl_diterima']);
            $row++;
        }

        $filename = 'Export_Data_Siswa_' . date('Y-m-d_His') . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }

function delete($id)
    {
        $db = \Config\Database::connect();
        $siswaModel = new SiswaModel();
        $siswa = $siswaModel->find($id);

        if ($siswa) {
            $userRecord = $db->table('users')->where('id', $siswa['user_id'])->get()->getRowArray();

            // Hapus fisik file dari folder avatars
            if ($userRecord && !empty($userRecord['foto_profil']) && file_exists(FCPATH . 'assets/uploads/avatars/' . $userRecord['foto_profil'])) {
                unlink(FCPATH . 'assets/uploads/avatars/' . $userRecord['foto_profil']);
            }

            // Hapus data (Otomatis terhapus jika FK CASCADE, jika tidak hapus manual)
            $db->table('users')->where('id', $siswa['user_id'])->delete();
            $siswaModel->delete($id);

            return $this->response->setJSON(['status' => 'success', 'message' => 'Data dihapus.']);
        } else {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Data tidak ditemukan.']);
        }
    }

    public function downloadTemplate()
    {
        $db = \Config\Database::connect();
        $spreadsheet = new Spreadsheet();

        // --- SHEET 1: FORM IMPORT DATA SISWA ---
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Data Siswa');

        $headers = [
            'NIS (Kunci Utama / Kosongkan Jika Baru)',
            'NISN',
            'NIK',
            'Nama Lengkap (Wajib)',
            'Jenis Kelamin (L/P)',
            'Tempat Lahir',
            'Tanggal Lahir (YYYY-MM-DD)',
            'Agama',
            'Kelas / Rombel (Lihat Sheet 2)',
            'Alamat Siswa',
            'Kecamatan',
            'Kelurahan / Desa',
            'No HP Siswa',
            'Email Siswa',
            'Asal Sekolah',
            'Diterima di Kelas',
            'Tanggal Diterima (YYYY-MM-DD)',
            'Nama Ayah',
            'Nama Ibu',
            'No HP / WA Orang Tua',
            'Status Siswa (Aktif/Lulus/Pindah/Keluar)'
        ];

        $col = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($col . '1', $header);
            $sheet->getStyle($col . '1')->getFont()->setBold(true);
            $sheet->getStyle($col . '1')->getFill()
                ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                ->getStartColor()->setARGB('FFEFEFEF');
            $sheet->getColumnDimension($col)->setAutoSize(true);
            $col++;
        }

        // Baris Contoh (Sample Row)
        $sheet->setCellValueExplicit('A2', '08.26.0001', \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
        $sheet->setCellValueExplicit('B2', '0012345678', \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
        $sheet->setCellValueExplicit('C2', '3201012345670001', \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
        $sheet->setCellValue('D2', 'Contoh: Ahmad Zidan');
        $sheet->setCellValue('E2', 'L');
        $sheet->setCellValue('F2', 'Jakarta');
        $sheet->setCellValue('G2', '2010-05-20');
        $sheet->setCellValue('H2', 'Islam');
        $sheet->setCellValue('I2', 'VII Safir');
        $sheet->setCellValue('J2', 'Jl. Merdeka No. 10');
        $sheet->setCellValue('K2', 'Kebayoran Baru');
        $sheet->setCellValue('L2', 'Senayan');
        $sheet->setCellValueExplicit('M2', '081234567890', \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
        $sheet->setCellValue('N2', 'ahmad@sekolah.sch.id');
        $sheet->setCellValue('O2', 'SDN 1 Jakarta');
        $sheet->setCellValue('P2', 'VII');
        $sheet->setCellValue('Q2', '2024-07-15');
        $sheet->setCellValue('R2', 'Budi Santoso');
        $sheet->setCellValue('S2', 'Siti Rahmah');
        $sheet->setCellValueExplicit('T2', '081298765432', \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
        $sheet->setCellValue('U2', 'Aktif');

        // --- SHEET 2: REFERENSI ROMBEL & KELAS ---
        $sheet2 = $spreadsheet->createSheet();
        $sheet2->setTitle('Referensi Rombel');

        $sheet2->setCellValue('A1', 'NAMA ROMBEL (Copy ke Sheet 1 Kolom I)');
        $sheet2->setCellValue('B1', 'TINGKAT');
        $sheet2->getStyle('A1:B1')->getFont()->setBold(true);
        $sheet2->getStyle('A1:B1')->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setARGB('FFEFEFEF');
        $sheet2->getColumnDimension('A')->setAutoSize(true);
        $sheet2->getColumnDimension('B')->setAutoSize(true);

        $rombels = $db->table('rombel')->orderBy('tingkat', 'ASC')->orderBy('nama_rombel', 'ASC')->get()->getResultArray();
        $rowRombel = 2;
        foreach ($rombels as $r) {
            $sheet2->setCellValue('A' . $rowRombel, $r['tingkat'] . ' ' . $r['nama_rombel']);
            $sheet2->setCellValue('B' . $rowRombel, $r['tingkat']);
            $rowRombel++;
        }

        // Set active sheet kembali ke Sheet 1
        $spreadsheet->setActiveSheetIndex(0);

        $filename = 'Template_Import_Siswa_' . date('Y-m-d') . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }

    public function import()
    {
        ini_set('memory_limit', '2048M');
        ini_set('max_execution_time', '600');
        if (ob_get_length())
            ob_clean();

        if (empty($_FILES)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'File ditolak server.']);
        }

        $file = $this->request->getFile('file_excel');
        if (!$file || !$file->isValid() || $file->hasMoved()) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'File gagal diunggah atau tidak valid.']);
        }

        $ext = strtolower($file->getClientExtension());
        if (!in_array($ext, ['xls', 'xlsx'])) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Format file harus Excel (.xlsx atau .xls)']);
        }

        $db = \Config\Database::connect();

        try {
            $spreadsheet = IOFactory::load($file->getTempName());
            $worksheet = $spreadsheet->getActiveSheet();
            $sheetData = $worksheet->toArray(null, true, true, true);

            // 1. Ambil Tahun Ajaran Aktif
            $taAktif = $db->table('tahun_ajaran')->where('status', 'Aktif')->get()->getRowArray();

            // 2. Siapkan Mapping Rombel
            $dbRombel = $db->table('rombel')->get()->getResultArray();
            $mapRombel = [];
            foreach ($dbRombel as $r) {
                $nama = strtolower(trim($r['nama_rombel']));
                $tingkat = strtolower(trim($r['tingkat']));

                $mapRombel[$tingkat . ' ' . $nama] = $r['id'];
                $mapRombel[$tingkat . '-' . $nama] = $r['id'];
                $mapRombel[$tingkat . $nama] = $r['id'];
                $mapRombel[$nama] = $r['id'];
            }

            // 3. Helper Parsing Tanggal
            $parseDate = function ($val) {
                if (empty($val)) return null;
                $val = trim((string)$val);
                if (is_numeric($val) && (float)$val > 10000 && (float)$val < 100000) {
                    try {
                        return \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject((float)$val)->format('Y-m-d');
                    } catch (\Throwable $e) {
                    }
                }
                if (preg_match('/^(\d{1,2})[\/\-](\d{1,2})[\/\-](\d{4})$/', $val, $m)) {
                    return sprintf('%04d-%02d-%02d', $m[3], $m[2], $m[1]);
                }
                $ts = strtotime($val);
                if ($ts !== false && $ts > 0) {
                    return date('Y-m-d', $ts);
                }
                return null;
            };

            $db->transBegin();

            $countInsert = 0;
            $countUpdate = 0;
            $errors = [];

            foreach ($sheetData as $idx => $row) {
                // Lewati baris header
                if ($idx == 1) continue;

                $namaLengkap = trim($row['D'] ?? '');
                $nisInput    = trim($row['A'] ?? '');

                // Jika baris kosong, lewati
                if (empty($namaLengkap) && empty($nisInput)) {
                    continue;
                }

                if (empty($namaLengkap)) {
                    $errors[] = "Baris $idx: Nama Lengkap wajib diisi.";
                    continue;
                }

                $nisFinal = !empty($nisInput) ? substr($nisInput, 0, 30) : null;
                $nisnFinal = !empty($row['B']) ? substr(preg_replace('/\.0$/', '', trim($row['B'])), 0, 20) : null;
                $nikFinal = !empty($row['C']) ? substr(preg_replace('/\.0$/', '', trim($row['C'])), 0, 50) : null;
                $jk = (strtoupper(trim($row['E'] ?? 'L')) === 'P') ? 'P' : 'L';
                $tempatLahir = substr(trim($row['F'] ?? ''), 0, 50);
                $tglLahir = $parseDate($row['G'] ?? '');
                $agama = substr(trim($row['H'] ?? 'Islam'), 0, 20) ?: 'Islam';

                // Resolusi Rombel
                $rombelRaw = strtolower(trim($row['I'] ?? ''));
                $rombelId = null;
                if (!empty($rombelRaw) && isset($mapRombel[$rombelRaw])) {
                    $rombelId = $mapRombel[$rombelRaw];
                }

                $alamat = trim($row['J'] ?? '');
                $kecamatan = substr(trim($row['K'] ?? ''), 0, 100);
                $kelurahan = substr(trim($row['L'] ?? ''), 0, 100);
                $noHpSiswa = substr(preg_replace('/\.0$/', '', trim($row['M'] ?? '')), 0, 50);
                $emailSiswa = substr(trim($row['N'] ?? ''), 0, 100);
                $asalSekolah = substr(trim($row['O'] ?? ''), 0, 100);
                $diterimaDiKelas = substr(trim($row['P'] ?? ''), 0, 20);
                $tglDiterima = $parseDate($row['Q'] ?? '') ?: date('Y-m-d');
                $namaAyah = substr(trim($row['R'] ?? ''), 0, 100);
                $namaIbu = substr(trim($row['S'] ?? ''), 0, 100);
                $noHpOrtu = substr(preg_replace('/\.0$/', '', trim($row['T'] ?? '')), 0, 50);
                $statusSiswa = substr(trim($row['U'] ?? 'Aktif'), 0, 20) ?: 'Aktif';

                try {
                    // --- 🔍 PENCARIAN SISWA BERDASARKAN NIS (ACUAN UTAMA) ---
                    $existing = null;
                    if (!empty($nisFinal)) {
                        $existing = $db->table('siswa')->where('nis', $nisFinal)->get()->getRowArray();
                    }
                    if (!$existing && !empty($nisnFinal)) {
                        $existing = $db->table('siswa')->where('nisn', $nisnFinal)->get()->getRowArray();
                    }
                    if (!$existing && !empty($nikFinal)) {
                        $existing = $db->table('siswa')->where('nik', $nikFinal)->get()->getRowArray();
                    }
                    if (!$existing && !empty($namaLengkap) && !empty($tempatLahir)) {
                        $existing = $db->table('siswa')
                            ->where('LOWER(nama_lengkap)', strtolower($namaLengkap))
                            ->where('LOWER(tempat_lahir)', strtolower($tempatLahir))
                            ->get()->getRowArray();
                    }

                    if ($existing) {
                        // ==========================================
                        // KASUS 1: UPDATE SISWA YANG SUDAH ADA
                        // ==========================================
                        $siswaId = $existing['id'];
                        $dataUpdate = [
                            'nama_lengkap'  => $namaLengkap,
                            'jenis_kelamin' => $jk,
                            'tempat_lahir'  => $tempatLahir,
                            'agama'         => $agama,
                            'status_siswa'  => $statusSiswa
                        ];

                        if (!empty($nisnFinal)) $dataUpdate['nisn'] = $nisnFinal;
                        if (!empty($nikFinal)) $dataUpdate['nik'] = $nikFinal;
                        if (!empty($tglLahir)) $dataUpdate['tanggal_lahir'] = $tglLahir;
                        if (!empty($alamat)) $dataUpdate['alamat_siswa'] = $alamat;
                        if (!empty($kecamatan)) $dataUpdate['kecamatan'] = $kecamatan;
                        if (!empty($kelurahan)) $dataUpdate['kelurahan'] = $kelurahan;
                        if (!empty($noHpSiswa)) $dataUpdate['no_hp'] = $noHpSiswa;
                        if (!empty($emailSiswa)) $dataUpdate['email_siswa'] = $emailSiswa;
                        if (!empty($asalSekolah)) $dataUpdate['asal_sekolah'] = $asalSekolah;
                        if (!empty($diterimaDiKelas)) $dataUpdate['diterima_dikelas'] = $diterimaDiKelas;
                        if (!empty($tglDiterima)) $dataUpdate['tgl_diterima'] = $tglDiterima;
                        if (!empty($rombelId)) $dataUpdate['rombel_id'] = $rombelId;

                        $db->table('siswa')->where('id', $siswaId)->update($dataUpdate);

                        // Sinkronisasi Anggota Rombel (Mesin Waktu)
                        if ($taAktif && !empty($rombelId)) {
                            $cekAR = $db->table('anggota_rombel')->where([
                                'siswa_id'        => $siswaId,
                                'tahun_ajaran_id' => $taAktif['id']
                            ])->get()->getRowArray();

                            if ($cekAR) {
                                $db->table('anggota_rombel')->where('id', $cekAR['id'])->update([
                                    'rombel_id' => $rombelId,
                                    'semester'  => $taAktif['semester']
                                ]);
                            } else {
                                $db->table('anggota_rombel')->insert([
                                    'siswa_id'        => $siswaId,
                                    'rombel_id'       => $rombelId,
                                    'tahun_ajaran_id' => $taAktif['id'],
                                    'semester'        => $taAktif['semester']
                                ]);
                            }
                        }

                        // Sinkronisasi Data Orang Tua
                        $ortuExist = $db->table('orangtua_wali')->where('siswa_id', $siswaId)->get()->getRowArray();
                        $dataOrtuUpdate = [];
                        if (!empty($namaAyah)) $dataOrtuUpdate['nama_ayah'] = $namaAyah;
                        if (!empty($namaIbu)) $dataOrtuUpdate['nama_ibu'] = $namaIbu;
                        if (!empty($noHpOrtu)) $dataOrtuUpdate['no_hp_ortu'] = $noHpOrtu;
                        if (!empty($alamat)) $dataOrtuUpdate['alamat_orangtua'] = $alamat;

                        if (!empty($dataOrtuUpdate)) {
                            if ($ortuExist) {
                                $db->table('orangtua_wali')->where('siswa_id', $siswaId)->update($dataOrtuUpdate);
                            } else {
                                $dataOrtuUpdate['siswa_id'] = $siswaId;
                                $db->table('orangtua_wali')->insert($dataOrtuUpdate);
                            }
                        }

                        $countUpdate++;
                    } else {
                        // ==========================================
                        // KASUS 2: INSERT SISWA BARU
                        // ==========================================
                        if (empty($nisFinal)) {
                            $nisFinal = $this->generateNextNisInternal($tglDiterima);
                        }

                        // Buat Akun Login User untuk Siswa
                        $usernameSiswa = $nisFinal;
                        if ($db->table('users')->where('username', substr($usernameSiswa, 0, 50))->countAllResults() > 0) {
                            $usernameSiswa = $usernameSiswa . '_' . rand(10, 99);
                        }

                        $userData = [
                            'username'  => substr($usernameSiswa, 0, 50),
                            'password'  => password_hash('12345678', PASSWORD_BCRYPT),
                            'role_id'   => 3,
                            'is_active' => 1
                        ];
                        if (!empty($emailSiswa)) {
                            $userData['email'] = $emailSiswa;
                        }

                        if (!$db->table('users')->insert($userData)) {
                            throw new \Exception("Gagal membuat akun user untuk {$namaLengkap}: " . ($db->error()['message'] ?? ''));
                        }
                        $userIdSiswa = $db->insertID();

                        // Simpan Data Siswa
                        $dataSiswaBaru = [
                            'user_id'          => $userIdSiswa,
                            'nis'              => $nisFinal,
                            'nisn'             => $nisnFinal,
                            'nik'              => $nikFinal,
                            'nama_lengkap'     => $namaLengkap,
                            'jenis_kelamin'    => $jk,
                            'tempat_lahir'     => $tempatLahir,
                            'tanggal_lahir'    => $tglLahir,
                            'agama'            => $agama,
                            'rombel_id'        => $rombelId,
                            'alamat_siswa'     => $alamat,
                            'kecamatan'        => $kecamatan,
                            'kelurahan'        => $kelurahan,
                            'no_hp'            => $noHpSiswa,
                            'email_siswa'      => $emailSiswa,
                            'asal_sekolah'     => $asalSekolah,
                            'diterima_dikelas' => $diterimaDiKelas,
                            'tgl_diterima'     => $tglDiterima,
                            'status_siswa'     => $statusSiswa
                        ];

                        if (!$db->table('siswa')->insert($dataSiswaBaru)) {
                            throw new \Exception("Gagal menyimpan data siswa {$namaLengkap}: " . ($db->error()['message'] ?? ''));
                        }
                        $siswaId = $db->insertID();

                        // Masukkan ke Anggota Rombel Tahun Ajaran Aktif
                        if ($taAktif && !empty($rombelId)) {
                            $db->table('anggota_rombel')->insert([
                                'siswa_id'        => $siswaId,
                                'rombel_id'       => $rombelId,
                                'tahun_ajaran_id' => $taAktif['id'],
                                'semester'        => $taAktif['semester']
                            ]);
                        }

                        // Akun & Data Orang Tua
                        $usernameOrtu = !empty($noHpOrtu) ? $noHpOrtu : 'W' . $nisFinal;
                        $existingUserOrtu = $db->table('users')->where('username', substr($usernameOrtu, 0, 50))->get()->getRowArray();
                        if ($existingUserOrtu) {
                            $userIdOrtu = $existingUserOrtu['id'];
                        } else {
                            $ortuAccount = [
                                'username'  => substr($usernameOrtu, 0, 50),
                                'password'  => password_hash('12345678', PASSWORD_BCRYPT),
                                'role_id'   => 4,
                                'is_active' => 1
                            ];
                            $db->table('users')->insert($ortuAccount);
                            $userIdOrtu = $db->insertID();
                        }

                        $db->table('orangtua_wali')->insert([
                            'siswa_id'        => $siswaId,
                            'user_id'         => $userIdOrtu,
                            'nama_ayah'       => !empty($namaAyah) ? $namaAyah : '-',
                            'nama_ibu'        => !empty($namaIbu) ? $namaIbu : '-',
                            'no_hp_ortu'      => $noHpOrtu,
                            'alamat_orangtua' => $alamat
                        ]);

                        $countInsert++;
                    }
                } catch (\Throwable $dbRowErr) {
                    $errors[] = "Baris $idx ({$namaLengkap}): " . $dbRowErr->getMessage();
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
            $msg = "Import Siswa Sukses! <b>{$countInsert}</b> Siswa Baru ditambahkan, <b>{$countUpdate}</b> Data Siswa diperbarui.";
            if (!empty($errors)) {
                $msg .= "<br><br><small class='text-amber-600'>Catatan (" . count($errors) . " baris dilewati/error):<br>" . implode('<br>', array_slice($errors, 0, 5)) . "</small>";
            }

            return $this->response->setJSON(['status' => 'success', 'message' => $msg]);
        } catch (\Throwable $e) {
            if (isset($db)) {
                $db->transRollback();
            }
            return $this->response->setJSON(['status' => 'error', 'message' => 'Fatal Error: ' . $e->getMessage()]);
        }
    }

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

    // =========================================================
    // FITUR INTERNAL & API: GENERATE NIS OTOMATIS
    // =========================================================
    private function generateNextNisInternal($tglDiterima = null)
    {
        $siswaModel = new SiswaModel();
        $tgl = $tglDiterima ?: date('Y-m-d');
        $tahunMasuk = date('y', strtotime($tgl));

        $lastSiswa = $siswaModel->orderBy('id', 'DESC')->first();
        if ($lastSiswa && !empty($lastSiswa['nis']) && strpos($lastSiswa['nis'], '.') !== false) {
            $parts = explode('.', $lastSiswa['nis']);
            $lastAngkatan = (int) $parts[0];
            $lastTahun = $parts[1];
            $angkatanBaru = ($tahunMasuk > $lastTahun) ? $lastAngkatan + 1 : $lastAngkatan;
        } else {
            $angkatanBaru = 1;
        }

        $prefixNis = sprintf("%02d", $angkatanBaru) . '.' . $tahunMasuk . '.';
        $cekUrutan = $siswaModel->like('nis', $prefixNis, 'after')->orderBy('nis', 'DESC')->first();
        $nextUrut = $cekUrutan ? ((int) explode('.', $cekUrutan['nis'])[2] + 1) : 1;
        return $prefixNis . sprintf("%05d", $nextUrut);
    }

    public function generateNextNis()
    {
        $tglDiterima = $this->request->getGet('tgl_diterima') ?: date('Y-m-d');
        $nisFinal = $this->generateNextNisInternal($tglDiterima);

        return $this->response->setJSON([
            'status' => 'success',
            'nis'    => $nisFinal
        ]);
    }
}
