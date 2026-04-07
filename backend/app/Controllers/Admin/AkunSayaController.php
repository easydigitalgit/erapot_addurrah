<?php

namespace App\Controllers\Admin;

use App\Controllers\AdminBaseController;
use App\Models\Admin\UserModel;

class AkunSayaController extends AdminBaseController
{
    public function index()
    {
        $userId = session()->get('id') ?? session()->get('user_id');
        $userModel = new \App\Models\Admin\UserModel(); // Pastikan path model benar
        $userData = $userModel->find($userId);

        if (!$userData) return redirect()->to('/logout');

        $db = \Config\Database::connect();

        // ======================================================================
        // LOGIKA FALLBACK KHUSUS GURU MAPEL (USERS -> GURU_TENDIK)
        // ======================================================================
        $guruTendikData = $db->table('guru_tendik')->where('user_id', $userId)->get()->getRowArray();

        if ($guruTendikData) {
            $userData['nama_lengkap'] = !empty($userData['nama_lengkap']) ? $userData['nama_lengkap'] : $guruTendikData['nama_lengkap'];
            $userData['no_hp']        = !empty($userData['no_hp']) ? $userData['no_hp'] : $guruTendikData['no_hp'];
            $userData['no_darurat']   = !empty($userData['no_darurat']) ? $userData['no_darurat'] : $guruTendikData['no_darurat'];
            $userData['alamat_domisili'] = !empty($userData['alamat_domisili']) ? $userData['alamat_domisili'] : $guruTendikData['alamat_domisili'];
            $userData['email']        = !empty($userData['email']) ? $userData['email'] : $guruTendikData['email'];
        }

        // AMBIL DATA LOG LOGIN TERBARU
        $loginLogs = $db->table('login_logs')
            ->where('user_id', $userId)
            ->orderBy('created_at', 'DESC')
            ->limit(5)
            ->get()->getResultArray();

        // AMBIL PREFERENSI USER
        $prefs = $db->table('user_preferences')->where('user_id', $userId)->get()->getRowArray();
        if (!$prefs) {
            $prefs = ['notif_login' => 0, 'two_factor' => 0, 'bahasa' => 'id', 'theme' => 'light', 'notif_email' => 0, 'notif_sistem' => 0, 'notif_update' => 0];
        }

        // AMBIL SEMUA ROLE USER (Untuk Badge)
        $dbRoles = method_exists($userModel, 'getUserRoles') ? $userModel->getUserRoles($userId) : [];
        $roleIds = array_column($dbRoles, 'role_id');
        if (!empty($userData['role_id']) && !in_array($userData['role_id'], $roleIds)) {
            $dbRoles[] = [
                'role_id' => $userData['role_id'],
                'role_name' => session()->get('role_label')
            ];
        }

        $data = [
            'user'        => $userData,
            'login_logs'  => $loginLogs,
            'prefs'       => $prefs,
            'semua_role'  => $dbRoles,
            'navigations' => $this->getSidebarMenu(),
            'color'       => $this->getColor()
        ];

        return view('admin/akun-saya', $data); // Pastikan view path-nya sesuai struktur lu
    }

    // ==========================================
    // FUNGSI UPDATE PROFIL (SINKRONISASI 2 TABEL)
    // ==========================================
    public function updateProfile()
    {
        // 1. Validasi AJAX
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(403)->setJSON(['status' => 'error', 'message' => 'Akses ditolak']);
        }

        $userId = session()->get('id') ?? session()->get('user_id');
        $userModel = new \App\Models\Admin\UserModel();
        $db = \Config\Database::connect();

        // 2. Tangkap data dari input HTML (name="alamat" di-mapping ke kolom "alamat_domisili")
        $dataToUpdate = [
            'nama_lengkap'    => $this->request->getPost('nama_lengkap'),
            'email'           => $this->request->getPost('email'),
            'no_hp'           => $this->request->getPost('no_hp'),
            'no_darurat'      => $this->request->getPost('no_darurat'),
            'alamat_domisili' => $this->request->getPost('alamat')
        ];

        // 3. Mulai Transaksi Database (Mencegah data belang jika server tiba-tiba mati)
        $db->transStart();

        try {
            // A. Simpan ke tabel prioritas (users)
            $userModel->update($userId, $dataToUpdate);

            // B. Sinkronisasi (Mirroring) ke tabel guru_tendik
            $cekGuru = $db->table('guru_tendik')->where('user_id', $userId)->get()->getRow();
            if ($cekGuru) {
                $db->table('guru_tendik')->where('user_id', $userId)->update($dataToUpdate);
            }

            // C. Perbarui Session Nama Lengkap agar UI Navbar langsung berubah saat itu juga
            if (!empty($dataToUpdate['nama_lengkap'])) {
                session()->set('nama_lengkap', $dataToUpdate['nama_lengkap']);
            }

            $db->transComplete();

            if ($db->transStatus() === false) {
                return $this->response->setStatusCode(500)->setJSON(['status' => 'error', 'message' => 'Gagal melakukan sinkronisasi database.']);
            }

            return $this->response->setJSON(['status' => 'success', 'message' => 'Profil berhasil diperbarui dan disinkronkan!']);
        } catch (\Exception $e) {
            return $this->response->setStatusCode(500)->setJSON(['status' => 'error', 'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage()]);
        }
    }

    // ==========================================
    // FUNGSI GANTI PASSWORD
    // ==========================================
    public function updatePassword()
    {
        if (!$this->request->isAJAX()) return $this->response->setStatusCode(403);

        $userId = session()->get('id') ?? session()->get('user_id');
        $oldPass = $this->request->getPost('old_password');
        $newPass = $this->request->getPost('new_password');

        $userModel = new UserModel();
        $user = $userModel->find($userId);

        // Verifikasi password lama (Bisa hash bcrypt atau text biasa)
        if (!password_verify($oldPass, $user['password']) && $oldPass !== $user['password']) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Password lama Anda salah!']);
        }

        // Simpan password baru
        $userModel->update($userId, ['password' => password_hash($newPass, PASSWORD_DEFAULT)]);

        return $this->response->setJSON(['status' => 'success', 'message' => 'Password berhasil diubah.']);
    }

    // ==========================================
    // FUNGSI SIMPAN PREFERENSI & KEAMANAN
    // ==========================================
    public function updatePreferences()
    {
        if (!$this->request->isAJAX()) return $this->response->setStatusCode(403);

        $userId = session()->get('id') ?? session()->get('user_id');
        $db = \Config\Database::connect();

        $bahasa = $this->request->getPost('bahasa');
        $theme  = $this->request->getPost('theme') ?: 'light';

        $data = [
            'user_id'      => $userId,
            'notif_login'  => $this->request->getPost('notif_login') ?: 0,
            'two_factor'   => $this->request->getPost('two_factor') ?: 0,
            'bahasa'       => $bahasa,
            'theme'        => $theme,
            'notif_email'  => $this->request->getPost('notif_email') ?: 0,
            'notif_sistem' => $this->request->getPost('notif_sistem') ?: 0,
            'notif_update' => $this->request->getPost('notif_update') ?: 0,
        ];

        $existing = $db->table('user_preferences')->where('user_id', $userId)->get()->getRow();
        if ($existing) {
            $db->table('user_preferences')->where('user_id', $userId)->update($data);
        } else {
            $db->table('user_preferences')->insert($data);
        }

        // ========================================================
        // KUNCI UTAMANYA: Update Semua Session Bahasa Secara Instan!
        // ========================================================
        session()->set('theme', $theme);
        session()->set('lang', $bahasa); 
        session()->set('locale', $bahasa); 
        session()->set('bahasa', $bahasa); 

        return $this->response->setJSON(['status' => 'success', 'message' => 'Preferensi disimpan.']);
    }

    public function uploadAvatar()
    {
        if (!$this->request->isAJAX()) return $this->response->setStatusCode(403)->setJSON(['status' => 'error', 'message' => 'Akses ditolak']);

        $userId = session()->get('id') ?? session()->get('user_id');
        $userModel = new \App\Models\Admin\UserModel();
        $userData = $userModel->find($userId);

        $fileAvatar = $this->request->getFile('avatar');

        if ($fileAvatar && $fileAvatar->isValid() && !$fileAvatar->hasMoved()) {

            // 1. TENTUKAN FOLDER BARU (KHUSUS ADMIN)
            // 1. TENTUKAN FOLDER UNIVERSAL
            $path = FCPATH . 'assets/uploads/avatars/';
            if (!is_dir($path)) mkdir($path, 0777, true);

            // 2. BACA VARIABEL USER (Mendukung $user maupun $userData)
            $currentUser = isset($userData) ? $userData : (isset($user) ? $user : []);

            // 3. HAPUS FOTO LAMA
            if (!empty($currentUser['foto_profil']) && file_exists($path . $currentUser['foto_profil'])) {
                unlink($path . $currentUser['foto_profil']);
            }

            // 4. SIMPAN FILE ASLI SEMENTARA
            $originalName = $fileAvatar->getRandomName();
            $fileAvatar->move($path, $originalName);

            // 5. SIAPKAN NAMA WEBP BARU
            $webpName = pathinfo($originalName, PATHINFO_FILENAME) . '.webp';
            $finalName = $originalName; // Fallback

            // 6. PROSES KONVERSI KE WEBP
            try {
                \Config\Services::image()
                    ->withFile($path . $originalName)
                    ->fit(250, 250, 'center')
                    ->convert(IMAGETYPE_WEBP)
                    ->save($path . $webpName, 75);

                if (file_exists($path . $webpName)) {
                    unlink($path . $originalName); // Hapus file asli
                    $finalName = $webpName;
                }
            } catch (\Exception $e) {
                // Abaikan jika server tidak dukung WebP
            }

            // 7. UPDATE DATABASE DAN SESSION
            // Pastikan $userModel sudah dideklarasikan di luar blok ini
            $userModel->update($userId, ['foto_profil' => $finalName]);
            session()->set('foto_profil', $finalName);

            // 8. KEMBALIKAN JSON DENGAN JALUR YANG BENAR (avatars)
            return $this->response->setJSON([
                'status'  => 'success',
                'message' => 'Foto profil berhasil disimpan!',
                'new_avatar_url' => base_url('assets/uploads/avatars/' . $finalName),
                'token'   => csrf_hash()
            ]);
        }

        return $this->response->setJSON(['status' => 'error', 'message' => 'Gagal membaca file gambar.']);
    }
}
