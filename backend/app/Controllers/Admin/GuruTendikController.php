<?php

namespace App\Controllers\Admin;

use App\Controllers\AdminBaseController;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;

class GuruTendikController extends AdminBaseController
{
    public function index()
    {
        $db = \Config\Database::connect();

        // ========================================================================
        // 1. TOTAL GURU (Hybrid: Jabatannya ada kata 'Guru' ATAU masuk di guru_mapel)
        // ========================================================================
        $queryGuru = $db->query("
            SELECT COUNT(DISTINCT gt.id) as total 
            FROM guru_tendik gt
            LEFT JOIN master_jabatan mj ON mj.id = gt.jabatan_id
            LEFT JOIN guru_mapel gm ON gm.guru_id = gt.id
            WHERE mj.nama_jabatan LIKE '%Guru%' OR gm.guru_id IS NOT NULL
        ");
        $totalGuruMapel = $queryGuru->getRow()->total ?? 0;

        // ========================================================================
        // 2. TOTAL WALI KELAS (Hybrid: Jabatannya 'Wali Kelas' ATAU masuk di rombel)
        // ========================================================================
        $queryWali = $db->query("
            SELECT COUNT(DISTINCT gt.id) as total 
            FROM guru_tendik gt
            LEFT JOIN master_jabatan mj ON mj.id = gt.jabatan_id
            LEFT JOIN rombel r ON r.wali_kelas_id = gt.id
            WHERE mj.nama_jabatan LIKE '%Wali Kelas%' OR (r.wali_kelas_id IS NOT NULL AND r.wali_kelas_id != 0)
        ");
        $waliKelas = $queryWali->getRow()->total ?? 0;

        // ========================================================================
        // 3. TOTAL TAHFIZ (Berdasarkan Jabatan Tahfiz / Tahfidz)
        // ========================================================================
        $queryTahfiz = $db->query("
            SELECT COUNT(DISTINCT gt.id) as total 
            FROM guru_tendik gt
            LEFT JOIN master_jabatan mj ON mj.id = gt.jabatan_id
            WHERE mj.nama_jabatan LIKE '%Tahfiz%' OR mj.nama_jabatan LIKE '%Tahfidz%'
        ");
        $totalTahfiz = $queryTahfiz->getRow()->total ?? 0;

        // ========================================================================
        // 4. TOTAL TENDIK / STAFF (Logika Eliminasi: Selain Guru, Wali, dan Tahfiz)
        // ========================================================================
        $queryTendik = $db->query("
            SELECT COUNT(DISTINCT gt.id) as total 
            FROM guru_tendik gt
            LEFT JOIN master_jabatan mj ON mj.id = gt.jabatan_id
            WHERE mj.nama_jabatan NOT LIKE '%Guru%' 
              AND mj.nama_jabatan NOT LIKE '%Wali Kelas%' 
              AND mj.nama_jabatan NOT LIKE '%Tahfiz%' 
              AND mj.nama_jabatan NOT LIKE '%Tahfidz%'
              AND mj.nama_jabatan IS NOT NULL
        ");
        $totalTendik = $queryTendik->getRow()->total ?? 0;

        $mapelList = $db->table('mata_pelajaran')->where('status', 'Aktif')->orderBy('nama_mapel', 'ASC')->get()->getResultArray();
        $jabatanList = $db->table('master_jabatan')->orderBy('nama_jabatan', 'ASC')->get()->getResultArray();
        $statusList = $db->table('master_status_pegawai')->orderBy('id', 'ASC')->get()->getResultArray();
        $sekolahData = $db->table('sekolah')->select('nama_sekolah')->get()->getRowArray();

        $data = [
            'title'          => 'Manajemen Guru & Tendik',
            'total_guru'     => $totalGuruMapel,
            'total_tendik'   => $totalTendik,
            'total_tahfiz'   => $totalTahfiz,
            'wali_kelas'     => $waliKelas,
            'navigations'    => $this->getSidebarMenu(),
            'color'          => $this->getColor(),
            'mapel_list'     => $db->table('mata_pelajaran')->where('status', 'Aktif')->orderBy('nama_mapel', 'ASC')->get()->getResultArray(),
            'jabatan_list'   => $db->table('master_jabatan')->orderBy('nama_jabatan', 'ASC')->get()->getResultArray(),
            'status_list'    => $db->table('master_status_pegawai')->orderBy('id', 'ASC')->get()->getResultArray(),
            'nama_sekolah'   => $sekolahData['nama_sekolah'] ?? 'Sekolah Anda'
        ];

        return view('admin/guru-tendik', $data);
    }

    public function getAll()
    {
        $db = \Config\Database::connect();

        $dataGuru = $db->table('guru_tendik')
            ->select('guru_tendik.*, users.is_active, users.username, users.foto_profil, master_jabatan.nama_jabatan as nama_jabatan_master, mata_pelajaran.nama_mapel as nama_mapel_master')
            ->join('users', 'users.id = guru_tendik.user_id', 'left')
            ->join('master_jabatan', 'master_jabatan.id = guru_tendik.jabatan_id', 'left')
            ->join('mata_pelajaran', 'mata_pelajaran.id = guru_tendik.mapel_id', 'left')
            ->orderBy('guru_tendik.id', 'DESC')
            ->get()->getResultArray();

        $waliKelasList = $db->table('rombel')->select('wali_kelas_id, nama_rombel, tingkat')->where('wali_kelas_id IS NOT NULL')->where('wali_kelas_id !=', 0)->get()->getResultArray();
        $waliMap = [];
        foreach ($waliKelasList as $wk) {
            $waliMap[$wk['wali_kelas_id']][] = $wk['tingkat'] . ' ' . $wk['nama_rombel'];
        }

        $assignments = $db->table('guru_mapel gm')->select('gm.guru_id, r.nama_rombel, r.tingkat')->join('rombel r', 'r.id = gm.rombel_id', 'left')->get()->getResultArray();
        $kelasMap = [];
        foreach ($assignments as $assign) {
            $gid = $assign['guru_id'];
            if (!isset($kelasMap[$gid])) $kelasMap[$gid] = [];
            $kelasMap[$gid][] = $assign['tingkat'] . ' ' . $assign['nama_rombel'];
        }

        foreach ($dataGuru as &$guru) {
            $gid = $guru['id'];

            // AMAN: Memakai data Master karena kolom lama sudah dihapus
            $guru['jabatan'] = $guru['nama_jabatan_master'] ?? '-';
            $guru['jabatan_asli'] = $guru['jabatan'];
            $guru['mapel_utama'] = $guru['nama_mapel_master'] ?? '-';

            if (isset($waliMap[$gid])) {
                // Jika dia sudah punya kelas, tampilkan kelasnya
                $guru['jabatan'] = $guru['jabatan_asli']; 
                $guru['info_wali'] = implode(', ', $waliMap[$gid]);
            } else {
                if (stripos($guru['jabatan_asli'], 'Wali Kelas') !== false) {
                    // Biarkan jabatannya TETAP Wali Kelas, jangan diturunkan pangkatnya!
                    $guru['jabatan'] = $guru['jabatan_asli']; 
                    $guru['info_wali'] = 'Belum Bimbing Kelas';
                } else {
                    $guru['info_wali'] = null;
                }
            }

            if (isset($kelasMap[$gid]) && !empty($kelasMap[$gid])) {
                $uniqueClasses = array_unique($kelasMap[$gid]);
                $guru['kelas_mengajar'] = implode(', ', $uniqueClasses);
            } else {
                $guru['kelas_mengajar'] = '-';
            }
        }
            return $this->response->setJSON([
            'rows'  => $dataGuru,
            'stats' => $this->_getHybridStats()
        ]);
    }

    public function show($id)
    {
        $db = \Config\Database::connect();
        $data = $db->table('guru_tendik')->select('guru_tendik.*, users.foto_profil')
            ->join('users', 'users.id = guru_tendik.user_id', 'left')
            ->where('guru_tendik.id', $id)
            ->get()->getRowArray();
        if ($data) return $this->response->setJSON($data);
        return $this->response->setJSON(['status' => 'error', 'message' => 'Data tidak ditemukan'])->setStatusCode(404);
    }

    public function store()
    {
        $rules = [
            'fullname'   => 'required',
            'email'      => 'required|valid_email|is_unique[users.email]',
            'nik'        => 'required|is_unique[guru_tendik.nik]',
            'jabatan_id' => 'required'
        ];

        $fileFoto = $this->request->getFile('photo');
        if ($fileFoto && $fileFoto->isValid()) {
            $rules['photo'] = 'max_size[photo,2048]|is_image[photo]|mime_in[photo,image/jpg,image/jpeg,image/png,image/webp]';
        }

        if (!$this->validate($rules)) {
            $errs = $this->validator->getErrors();
            return $this->response->setJSON(['status' => 'error', 'message' => 'Validasi gagal: ' . reset($errs)]);
        }

        $db = \Config\Database::connect();
        $db->transBegin();

        try {
            $fileFoto = $this->request->getFile('photo');
            $namaFotoDB = null;

            if ($fileFoto && $fileFoto->isValid() && !$fileFoto->hasMoved()) {
                // Pastikan folder ada dan beri izin tulis (0777)
                $uploadPath = FCPATH . 'assets/uploads/avatars/';
                if (!is_dir($uploadPath)) {
                    mkdir($uploadPath, 0777, true);
                }

                $newName = $fileFoto->getRandomName();
                $namaFotoBaru = pathinfo($newName, PATHINFO_FILENAME) . '.webp';
                $savePath = $uploadPath . $namaFotoBaru;

                try {
                    // Coba konversi ke WebP
                    \Config\Services::image()
                        ->withFile($fileFoto->getTempName())
                        ->convert(IMAGETYPE_WEBP)
                        ->save($savePath, 75);
                    $namaFotoDB = $namaFotoBaru;
                } catch (\Throwable $e) {
                    // Jika library WebP gagal/tidak ada, simpan file asli saja
                    $fileFoto->move($uploadPath, $newName);
                    $namaFotoDB = $newName;
                }
            }

            $emailInput = trim($this->request->getPost('email'));
            $fullnameInput = trim($this->request->getPost('fullname'));

            $jabatanId = $this->request->getPost('jabatan_id');
            $jabatanId = !empty($jabatanId) ? (int)$jabatanId : null;

            $gender = $this->request->getPost('gender');
            $gender = in_array($gender, ['L', 'P']) ? $gender : null;

            $masterJabatan = $db->table('master_jabatan')->where('id', $jabatanId)->get()->getRowArray();
            $namaJabatan = $masterJabatan ? $masterJabatan['nama_jabatan'] : null;

            // --- LOGIKA MAPEL CERDAS ---
            $mapelInput = trim($this->request->getPost('subject'));
            $mapelId = null;
            $namaMapel = '-';

            if (!empty($mapelInput) && $mapelInput !== '-') {
                if (is_numeric($mapelInput)) {
                    $masterMapel = $db->table('mata_pelajaran')->where('id', $mapelInput)->get()->getRowArray();
                } else {
                    $masterMapel = $db->table('mata_pelajaran')->where('nama_mapel', $mapelInput)->get()->getRowArray();
                }

                if (!empty($masterMapel)) {
                    $mapelId = $masterMapel['id'];
                    $namaMapel = $masterMapel['nama_mapel'];
                } else {
                    $namaMapel = $mapelInput;
                }
            }
            // ---------------------------

            $roleId = 2; // Default Guru
            if (stripos($namaJabatan, 'Tendik') !== false || stripos($namaJabatan, 'Staf') !== false || stripos($namaJabatan, 'Operator') !== false) $roleId = 1;
            elseif (stripos($namaJabatan, 'Tahfiz') !== false) $roleId = 7;
            elseif (stripos($namaJabatan, 'Wali Kelas') !== false) $roleId = 3;

            $existingUser = $db->table('users')->where('email', $emailInput)->get()->getRowArray();
            $userId = null;

            if ($existingUser) {
                $userId = $existingUser['id'];
                $cekRole = $db->table('user_roles')->where('user_id', $userId)->where('role_id', $roleId)->countAllResults();
                if ($cekRole == 0) $db->table('user_roles')->insert(['user_id' => $userId, 'role_id' => $roleId]);
                if ($namaFotoDB) $db->table('users')->where('id', $userId)->update(['foto_profil' => $namaFotoDB]);
            } else {
                $userData = [
                    'username'    => trim($this->request->getPost('nuptk')) ?: trim($this->request->getPost('nik')),
                    'email'       => $emailInput,
                    'password'    => password_hash('12345678', PASSWORD_BCRYPT),
                    'role_id'     => $roleId,
                    'is_active'   => 1,
                    'foto_profil' => $namaFotoDB
                ];

                if (!$db->table('users')->insert($userData)) {
                    throw new \Exception("Gagal membuat akun user.");
                }
                $userId = $db->insertID();
                $db->table('user_roles')->insert(['user_id' => $userId, 'role_id' => $roleId]);
            }

            $guruData = [
                'user_id'            => $userId,
                'nuptk'              => trim($this->request->getPost('nuptk')) ?: null,
                'nik'                => trim($this->request->getPost('nik')) ?: null,
                'nama_lengkap'       => $fullnameInput,                
                'email'              => $emailInput,
                'nama_pasangan'      => trim($this->request->getPost('nama_pasangan')) ?: null,
                'jumlah_anak'        => trim($this->request->getPost('jumlah_anak')) !== '' ? (int)$this->request->getPost('jumlah_anak') : null,
                'jenis_kelamin'      => $gender,
                'status_marital'     => trim($this->request->getPost('status_marital')) ?: null,
                'pendidikan_terakhir'=> trim($this->request->getPost('pendidikan_terakhir')) ?: null,
                'jurusan_prodi'      => trim($this->request->getPost('jurusan_prodi')) ?: null,
                'tmt_ad_durrah'      => trim($this->request->getPost('tmt_ad_durrah')) ?: null,
                'tempat_lahir'       => trim($this->request->getPost('tempat_lahir')) ?: null,
                'tanggal_lahir'      => trim($this->request->getPost('tanggal_lahir')) ?: null,
                'suku'               => trim($this->request->getPost('suku')) ?: null,
                'golongan_darah'     => trim($this->request->getPost('golongan_darah')) ?: null,
                'alamat_ktp'         => trim($this->request->getPost('alamat_ktp')) ?: null,
                'status_kepegawaian' => trim($this->request->getPost('employment_status')) ?: null,
                'jabatan_id'         => $jabatanId,                
                'mapel_id'           => $mapelId,
                'alamat_domisili'    => trim($this->request->getPost('alamat_domisili')) ?: null,
                'no_hp'              => trim($this->request->getPost('phone')) ?: null,
                'no_darurat'         => trim($this->request->getPost('no_darurat')) ?: null,
            ];

            if (!$db->table('guru_tendik')->insert($guruData)) {
                throw new \Exception("Gagal simpan biodata guru.");
            }

            $db->transCommit();
            return $this->response->setJSON(['status' => 'success', 'message' => 'Data Pegawai Berhasil Disimpan & Akun Terhubung!']);
        } catch (\Throwable $e) {
            $db->transRollback();
            return $this->response->setJSON(['status' => 'error', 'message' => 'System Error: ' . $e->getMessage()]);
        }
    }

    public function update($id)
    {
        $rules = [
            'fullname'   => 'required',
            'nik'        => "permit_empty|is_unique[guru_tendik.nik,id,{$id}]",
            'email'      => "permit_empty|valid_email|is_unique[guru_tendik.email,id,{$id}]",
            'jabatan_id' => 'required'
        ];

        $fileFoto = $this->request->getFile('photo');
        if ($fileFoto && $fileFoto->isValid()) {
            $rules['photo'] = 'max_size[photo,2048]|is_image[photo]|mime_in[photo,image/jpg,image/jpeg,image/png,image/webp]';
        }

        if (!$this->validate($rules)) {
            $errors = $this->validator->getErrors();
            return $this->response->setJSON(['status' => 'error', 'errors' => $errors, 'message' => 'Validasi gagal: ' . reset($errors)]);
        }

        $db = \Config\Database::connect();
        $db->transBegin();

        try {
            $existing = $db->table('guru_tendik')->where('id', $id)->get()->getRowArray();
            if (!$existing) throw new \Exception('Data tidak ditemukan');

            $userRecord = $db->table('users')->where('id', $existing['user_id'])->get()->getRowArray();
            $namaFotoDB = $userRecord['foto_profil'] ?? null;

            $fileFoto = $this->request->getFile('photo');

            if ($fileFoto && $fileFoto->isValid() && !$fileFoto->hasMoved()) {
                $uploadPath = FCPATH . 'assets/uploads/avatars/';
                
                // Hapus foto lama jika ada
                if (!empty($namaFotoDB) && file_exists($uploadPath . $namaFotoDB)) {
                    unlink($uploadPath . $namaFotoDB);
                }
                
                if (!is_dir($uploadPath)) mkdir($uploadPath, 0777, true);

                $newName = $fileFoto->getRandomName();
                $namaFotoBaru = pathinfo($newName, PATHINFO_FILENAME) . '.webp';
                $savePath = $uploadPath . $namaFotoBaru;

                try {
                    \Config\Services::image()
                        ->withFile($fileFoto->getTempName())
                        ->convert(IMAGETYPE_WEBP)
                        ->save($savePath, 75);
                    $namaFotoDB = $namaFotoBaru;
                } catch (\Throwable $e) {
                    $fileFoto->move($uploadPath, $newName);
                    $namaFotoDB = $newName;
                }
            }

            $jabatanId = $this->request->getPost('jabatan_id');
            $jabatanId = !empty($jabatanId) ? (int)$jabatanId : null;

            $gender = $this->request->getPost('gender');
            $gender = in_array($gender, ['L', 'P']) ? $gender : null;

            $masterJabatan = $db->table('master_jabatan')->where('id', $jabatanId)->get()->getRowArray();
            $namaJabatan = $masterJabatan ? $masterJabatan['nama_jabatan'] : null;

            // --- LOGIKA MAPEL CERDAS ---
            $mapelInput = trim($this->request->getPost('subject'));
            $mapelId = null;
            $namaMapel = '-';

            if (!empty($mapelInput) && $mapelInput !== '-') {
                if (is_numeric($mapelInput)) {
                    $masterMapel = $db->table('mata_pelajaran')->where('id', $mapelInput)->get()->getRowArray();
                } else {
                    $masterMapel = $db->table('mata_pelajaran')->where('nama_mapel', $mapelInput)->get()->getRowArray();
                }

                if (!empty($masterMapel)) {
                    $mapelId = $masterMapel['id'];
                    $namaMapel = $masterMapel['nama_mapel'];
                } else {
                    $namaMapel = $mapelInput;
                }
            }
            // ---------------------------

            $dataUpdate = [
                'nuptk'              => trim($this->request->getPost('nuptk')) ?: null,
                'nik'                => trim($this->request->getPost('nik')) ?: null,
                'nama_lengkap'       => trim($this->request->getPost('fullname')),                
                'email'              => trim($this->request->getPost('email')),
                'nama_pasangan'      => trim($this->request->getPost('nama_pasangan')) ?: null,
                'jumlah_anak'        => trim($this->request->getPost('jumlah_anak')) !== '' ? (int)$this->request->getPost('jumlah_anak') : null,
                'jenis_kelamin'      => $gender,
                'status_marital'     => trim($this->request->getPost('status_marital')) ?: null,
                'pendidikan_terakhir'=> trim($this->request->getPost('pendidikan_terakhir')) ?: null,
                'jurusan_prodi'      => trim($this->request->getPost('jurusan_prodi')) ?: null,
                'tmt_ad_durrah'      => trim($this->request->getPost('tmt_ad_durrah')) ?: null,
                'tempat_lahir'       => trim($this->request->getPost('tempat_lahir')) ?: null,
                'tanggal_lahir'      => trim($this->request->getPost('tanggal_lahir')) ?: null,
                'suku'               => trim($this->request->getPost('suku')) ?: null,
                'golongan_darah'     => trim($this->request->getPost('golongan_darah')) ?: null,
                'alamat_ktp'         => trim($this->request->getPost('alamat_ktp')) ?: null,
                'status_kepegawaian' => trim($this->request->getPost('employment_status')) ?: null,
                'jabatan_id'         => $jabatanId,                
                'mapel_id'           => $mapelId,
                'alamat_domisili'    => trim($this->request->getPost('alamat_domisili')) ?: null,
                'no_hp'              => trim($this->request->getPost('phone')) ?: null,
                'no_darurat'         => trim($this->request->getPost('no_darurat')) ?: null,
            ];

            if (!$db->table('guru_tendik')->where('id', $id)->update($dataUpdate)) {
                throw new \Exception("Gagal update biodata.");
            }

            // 1. Tentukan Role ID
            $roleId = 2; // Default Guru
            if (stripos($namaJabatan, 'Tendik') !== false || stripos($namaJabatan, 'Staf') !== false || stripos($namaJabatan, 'Operator') !== false) $roleId = 1;
            elseif (stripos($namaJabatan, 'Tahfiz') !== false) $roleId = 7;
            elseif (stripos($namaJabatan, 'Wali Kelas') !== false) $roleId = 3;

            // 2. Logika Auto-Provisioning (Cek apakah sudah punya akun atau belum)
            if (!empty($existing['user_id'])) {
                // JIKA SUDAH PUNYA AKUN: Update datanya
                $userUpdate = ['email' => $dataUpdate['email']];
                $newUsername = $dataUpdate['nuptk'] ?: $dataUpdate['nik'];
                
                if (!empty($newUsername)) {
                    $userUpdate['username'] = $newUsername;
                }
                if ($namaFotoDB) {
                    $userUpdate['foto_profil'] = $namaFotoDB; // Update foto jika ada foto baru
                }
                
                $db->table('users')->where('id', $existing['user_id'])->update($userUpdate);
                $db->table('user_roles')->where('user_id', $existing['user_id'])->update(['role_id' => $roleId]);
                
            } else {
                // JIKA BELUM PUNYA AKUN: Buatkan akun otomatis!
                $userData = [
                    'username'    => $dataUpdate['nuptk'] ?: $dataUpdate['nik'],
                    'email'       => $dataUpdate['email'],
                    'password'    => password_hash('12345678', PASSWORD_BCRYPT), // Password default
                    'role_id'     => $roleId,
                    'is_active'   => 1,
                    'foto_profil' => $namaFotoDB
                ];

                // Simpan ke tabel users
                $db->table('users')->insert($userData);
                $newUserId = $db->insertID(); 
                
                // Berikan akses
                $db->table('user_roles')->insert(['user_id' => $newUserId, 'role_id' => $roleId]);
                
                // Tautkan ID User yang baru dibuat ini ke profil guru! (PENTING AGAR FOTO MUNCUL)
                $db->table('guru_tendik')->where('id', $id)->update(['user_id' => $newUserId]);
            }

            $db->transCommit();
            return $this->response->setJSON(['status' => 'success', 'message' => 'Data & Jabatan berhasil diperbarui!']);
        } catch (\Throwable $e) {
            $db->transRollback();
            return $this->response->setJSON(['status' => 'error', 'message' => 'System Error: ' . $e->getMessage()]);
        }
    }

    public function delete($id = null)
    {
        if ($id === null) $id = $this->request->getPost('id');

        $db = \Config\Database::connect();
        $data = $db->table('guru_tendik')->where('id', $id)->get()->getRowArray();

        if ($data) {
            if (!empty($data['user_id'])) {
                $userRecord = $db->table('users')->where('id', $data['user_id'])->get()->getRowArray();
                if ($userRecord && !empty($userRecord['foto_profil']) && file_exists(FCPATH . 'assets/uploads/avatars/' . $userRecord['foto_profil'])) {
                    unlink(FCPATH . 'assets/uploads/avatars/' . $userRecord['foto_profil']);
                }
                $db->table('user_roles')->where('user_id', $data['user_id'])->delete();
                $db->table('users')->where('id', $data['user_id'])->delete();
            }

            $db->table('guru_tendik')->where('id', $id)->delete();
            return $this->response->setJSON(['status' => 'success', 'message' => 'Data berhasil dihapus']);
        }
        return $this->response->setJSON(['status' => 'error', 'message' => 'Gagal menghapus data']);
    }

    public function bulkDelete()
    {
        $json = $this->request->getJSON();
        $ids = $json->ids ?? [];

        if (empty($ids)) return $this->response->setJSON(['status' => 'error', 'message' => 'Tidak ada data yang dipilih']);

        $db = \Config\Database::connect();
        $db->transStart();

        try {
            $guruList = $db->table('guru_tendik')->whereIn('id', $ids)->get()->getResultArray();
            $userIds = array_column($guruList, 'user_id');

            if (!empty($userIds)) {
                $users = $db->table('users')->whereIn('id', $userIds)->get()->getResultArray();
                foreach ($users as $u) {
                    if (!empty($u['foto_profil']) && file_exists(FCPATH . 'assets/uploads/avatars/' . $u['foto_profil'])) {
                        unlink(FCPATH . 'assets/uploads/avatars/' . $u['foto_profil']);
                    }
                }
                $db->table('user_roles')->whereIn('user_id', $userIds)->delete();
                $db->table('users')->whereIn('id', $userIds)->delete();
            }

            $db->table('guru_tendik')->whereIn('id', $ids)->delete();

            $db->transComplete();
            return $this->response->setJSON(['status' => 'success', 'message' => count($ids) . ' data berhasil dihapus permanen.']);
        } catch (\Throwable $e) {
            $db->transRollback();
            return $this->response->setJSON(['status' => 'error', 'message' => 'Error: ' . $e->getMessage()]);
        }
    }

    public function export()
    {
        $db = \Config\Database::connect();
        $ids = $this->request->getGet('ids');

        $builder = $db->table('guru_tendik gt')
            ->select('gt.*, mj.nama_jabatan as jabatan_asli')
            ->join('master_jabatan mj', 'mj.id = gt.jabatan_id', 'left')
            ->join('mata_pelajaran mp', 'mp.id = gt.mapel_id', 'left'); // Tambah JOIN ini

        if ($ids) {
            $idArray = explode(',', $ids);
            $builder->whereIn('gt.id', $idArray);
        }
        $dataGuru = $builder->get()->getResultArray();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'Nama Lengkap');
        $sheet->setCellValue('B1', 'NUPTK');
        $sheet->setCellValue('C1', 'NIK');
        $sheet->setCellValue('D1', 'Email');
        $sheet->setCellValue('E1', 'No HP');
        $sheet->setCellValue('F1', 'Jabatan');
        $sheet->setCellValue('G1', 'Mapel Utama');
        $sheet->setCellValue('H1', 'Status Kepegawaian');

        $row = 2;
        foreach ($dataGuru as $guru) {
            $sheet->setCellValue('A' . $row, $guru['nama_lengkap']);
            $sheet->setCellValue('B' . $row, $guru['nuptk']);
            $sheet->setCellValueExplicit('C' . $row, $guru['nik'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet->setCellValue('D' . $row, $guru['email']);
            $sheet->setCellValueExplicit('E' . $row, $guru['no_hp'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet->setCellValue('F' . $row, $guru['jabatan_asli']);
            $sheet->setCellValue('G' . $row, $guru['mapel_asli'] ?? '-');
            $sheet->setCellValue('H' . $row, $guru['status_kepegawaian']);
            $row++;
        }

        foreach (range('A', 'H') as $col) $sheet->getColumnDimension($col)->setAutoSize(true);

        $filename = 'Data_Guru_Tendik_' . date('Y-m-d_H-i') . '.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }

    public function downloadTemplate()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $headers = ['Nama Lengkap', 'NUPTK', 'NIK', 'Email', 'No HP', 'Jabatan', 'Mapel Utama', 'Status Kepegawaian'];
        $col = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($col . '1', $header);
            $sheet->getColumnDimension($col)->setAutoSize(true);
            $col++;
        }

        $sheet->setCellValue('A2', 'Contoh: Budi Santoso, S.Pd');
        $sheet->setCellValue('B2', '19800101...');
        $sheet->setCellValueExplicit('C2', '320101...', \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
        $sheet->setCellValue('D2', 'budi@sekolah.sch.id');
        $sheet->setCellValueExplicit('E2', '08123456789', \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
        $sheet->setCellValue('F2', 'Pembina Tahfiz');
        $sheet->setCellValue('G2', '-');
        $sheet->setCellValue('H2', 'Tetap');

        $filename = 'Template_Import_Guru.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }

    public function import()
    {
        $file = $this->request->getFile('file_excel');

        if ($file && $file->isValid() && !$file->hasMoved()) {
            $extension = $file->getExtension();
            if (!in_array($extension, ['xls', 'xlsx'])) return $this->response->setJSON(['status' => 'error', 'message' => 'Format file harus .xls atau .xlsx']);

            try {
                $spreadsheet = IOFactory::load($file->getTempName());
                $sheet = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);

                $db = \Config\Database::connect();
                $db->transStart();

                $count = 0;
                foreach ($sheet as $idx => $row) {
                    if ($idx == 1) continue;
                    if (empty($row['A']) || empty($row['D'])) continue;

                    $emailInput = trim($row['D']);
                    $jabatanInput = trim($row['F']);

                    $mj = $db->table('master_jabatan')->where('nama_jabatan', $jabatanInput)->get()->getRowArray();
                    if ($mj) {
                        $jabatanId = $mj['id'];
                    } else {
                        $db->table('master_jabatan')->insert(['nama_jabatan' => $jabatanInput, 'status' => 'Aktif']);
                        $jabatanId = $db->insertID();
                    }

                    $roleId = 2;
                    if (stripos($jabatanInput, 'Tendik') !== false || stripos($jabatanInput, 'Staf') !== false) $roleId = 1;
                    if (stripos($jabatanInput, 'Wali Kelas') !== false) $roleId = 3;
                    if (stripos($jabatanInput, 'Tahfiz') !== false) $roleId = 7;

                    $existingUser = $db->table('users')->where('email', $emailInput)->get()->getRowArray();
                    $userId = null;

                    if ($existingUser) {
                        $userId = $existingUser['id'];
                        $cekRole = $db->table('user_roles')->where(['user_id' => $userId, 'role_id' => $roleId])->countAllResults();
                        if ($cekRole == 0) $db->table('user_roles')->insert(['user_id' => $userId, 'role_id' => $roleId]);
                    } else {
                        $userData = [
                            'username'  => $row['B'] ?: $row['C'],
                            'email'     => $emailInput,
                            'password'  => password_hash('12345678', PASSWORD_BCRYPT),
                            'role_id'   => $roleId,
                            'is_active' => 1
                        ];
                        $db->table('users')->insert($userData);
                        $userId = $db->insertID();
                        $db->table('user_roles')->insert(['user_id' => $userId, 'role_id' => $roleId]);
                    }

                    $cekGuru = $db->table('guru_tendik')->where('nik', trim($row['C']))->orWhere('email', $emailInput)->countAllResults();
                    if ($cekGuru > 0) continue;

                    // --- Logika Import Mapel ---
                    $mapelInput = trim($row['G']);
                    $mapelId = null;
                    if (!empty($mapelInput) && $mapelInput !== '-') {
                        $mp = $db->table('mata_pelajaran')->where('nama_mapel', $mapelInput)->get()->getRowArray();
                        if ($mp) {
                            $mapelId = $mp['id'];
                        } else {
                            $db->table('mata_pelajaran')->insert(['nama_mapel' => $mapelInput, 'status' => 'Aktif', 'kkm' => 75]);
                            $mapelId = $db->insertID();
                        }
                    }

                    $guruData = [
                        'user_id'            => $userId,
                        'nama_lengkap'       => trim($row['A']),
                        'nuptk'              => trim($row['B']),
                        'nik'                => trim($row['C']),
                        'email'              => $emailInput,
                        'no_hp'              => trim($row['E']),
                        'jabatan_id'         => $jabatanId,                        
                        'mapel_id'           => $mapelId,
                        'status_kepegawaian' => trim($row['H']),
                    ];
                    $db->table('guru_tendik')->insert($guruData);
                    $count++;
                }

                $db->transComplete();

                if ($db->transStatus() === FALSE) {
                    return $this->response->setJSON(['status' => 'error', 'message' => 'Gagal menyimpan data import.']);
                }

                return $this->response->setJSON(['status' => 'success', 'message' => "Berhasil mengimport $count data pegawai baru."]);
            } catch (\Throwable $e) {
                return $this->response->setJSON(['status' => 'error', 'message' => $e->getMessage()]);
            }
        }

        return $this->response->setJSON(['status' => 'error', 'message' => 'File tidak valid']);
    }
    public function toggleStatus($id)
    {
        $db = \Config\Database::connect();
        $guru = $db->table('guru_tendik')->where('id', $id)->get()->getRowArray();

        if ($guru && !empty($guru['user_id'])) {
            $user = $db->table('users')->where('id', $guru['user_id'])->get()->getRowArray();
            if ($user) {
                // Balikkan status: Jika 1 jadi 0, jika 0 jadi 1
                $newStatus = ($user['is_active'] == 1) ? 0 : 1;
                $db->table('users')->where('id', $guru['user_id'])->update(['is_active' => $newStatus]);

                $statusText = $newStatus == 1 ? 'diaktifkan' : 'dinonaktifkan';
                return $this->response->setJSON(['status' => 'success', 'message' => "Akun berhasil $statusText!"]);
            }
        }
        return $this->response->setJSON(['status' => 'error', 'message' => 'Gagal mengubah status, data tidak valid.']);
    }

    private function _getHybridStats()
    {
        $db = \Config\Database::connect();

        // 1. Tarik Data Guru
        $qGuru = $db->query("SELECT DISTINCT gt.nama_lengkap, mj.nama_jabatan FROM guru_tendik gt LEFT JOIN master_jabatan mj ON mj.id = gt.jabatan_id LEFT JOIN guru_mapel gm ON gm.guru_id = gt.id WHERE mj.nama_jabatan LIKE '%Guru%' OR gm.guru_id IS NOT NULL")->getResultArray();

        // 2. Tarik Data Wali Kelas
        $qWali = $db->query("SELECT DISTINCT gt.nama_lengkap, mj.nama_jabatan FROM guru_tendik gt LEFT JOIN master_jabatan mj ON mj.id = gt.jabatan_id LEFT JOIN rombel r ON r.wali_kelas_id = gt.id WHERE mj.nama_jabatan LIKE '%Wali Kelas%' OR (r.wali_kelas_id IS NOT NULL AND r.wali_kelas_id != 0)")->getResultArray();

        // 3. Tarik Data Tahfiz
        $qTahfiz = $db->query("SELECT DISTINCT gt.nama_lengkap, mj.nama_jabatan FROM guru_tendik gt LEFT JOIN master_jabatan mj ON mj.id = gt.jabatan_id WHERE mj.nama_jabatan LIKE '%Tahfiz%' OR mj.nama_jabatan LIKE '%Tahfidz%'")->getResultArray();

        // 4. Tarik Data Tendik
        $qTendik = $db->query("SELECT DISTINCT gt.nama_lengkap, mj.nama_jabatan FROM guru_tendik gt LEFT JOIN master_jabatan mj ON mj.id = gt.jabatan_id WHERE mj.nama_jabatan NOT LIKE '%Guru%' AND mj.nama_jabatan NOT LIKE '%Wali Kelas%' AND mj.nama_jabatan NOT LIKE '%Tahfiz%' AND mj.nama_jabatan NOT LIKE '%Tahfidz%' AND mj.nama_jabatan IS NOT NULL")->getResultArray();

        return [
            // Angka untuk ditampilkan di layar (Card)
            'total_guru'   => count($qGuru),
            'total_tahfiz' => count($qTahfiz),
            'total_tendik' => count($qTendik),
            'wali_kelas'   => count($qWali),
            
            // Data mentah untuk di-Debug ke Console
            'debug' => [
                'list_guru'   => $qGuru,
                'list_wali'   => $qWali,
                'list_tahfiz' => $qTahfiz,
                'list_tendik' => $qTendik
            ]
        ];
    }
}
