<?php
// File: app/Controllers/GuruMapel/AkunSayaController.php
namespace App\Controllers\GuruMapel;

use App\Controllers\GuruMapelBaseController;
use App\Models\Admin\UserModel;

class AkunSayaController extends GuruMapelBaseController
{
    public function index()
    {
        $userId = session()->get('id') ?? session()->get('user_id');
        $userModel = new UserModel();
        $user = $userModel->find($userId);

        if (!$user) return redirect()->to('/logout');

        $db = \Config\Database::connect();

        // ======================================================================
        // PERBAIKAN: Gunakan variabel $user, bukan $userData yang tidak terdefinisi
        // ======================================================================
        $guruTendikData = $db->table('guru_tendik')->where('user_id', $userId)->get()->getRowArray();

        if ($guruTendikData) {
            $user['nama_lengkap']    = !empty($user['nama_lengkap']) ? $user['nama_lengkap'] : $guruTendikData['nama_lengkap'];
            $user['no_hp']           = !empty($user['no_hp']) ? $user['no_hp'] : $guruTendikData['no_hp'];
            $user['no_darurat']      = !empty($user['no_darurat']) ? $user['no_darurat'] : $guruTendikData['no_darurat'];
            $user['alamat_domisili'] = !empty($user['alamat_domisili']) ? $user['alamat_domisili'] : $guruTendikData['alamat_domisili'];
            $user['email']           = !empty($user['email']) ? $user['email'] : $guruTendikData['email'];
        }

        // Ambil Log Login
        $loginLogs = $db->table('login_logs')
            ->where('user_id', $userId)
            ->orderBy('created_at', 'DESC')
            ->limit(5)
            ->get()->getResultArray();

        // Ambil Preferensi
        $prefs = $db->table('user_preferences')->where('user_id', $userId)->get()->getRowArray();
        if (!$prefs) {
            $prefs = ['notif_login' => 0, 'two_factor' => 0, 'bahasa' => 'id', 'theme' => 'light', 'notif_email' => 0, 'notif_sistem' => 0, 'notif_update' => 0];
        }

        // // AKTIFKAN MULTI-BAHASA (LOCALE)
        // $bahasaTerpilih = $prefs['bahasa'] ?? 'id';
        // \Config\Services::language()->setLocale($bahasaTerpilih);

        // Ambil Semua Role
        $dbRoles = method_exists($userModel, 'getUserRoles') ? $userModel->getUserRoles($userId) : [];
        $roleIds = array_column($dbRoles, 'role_id');
        if (!empty($user['role_id']) && !in_array($user['role_id'], $roleIds)) {
            $dbRoles[] = [
                'role_id' => $user['role_id'],
                'role_name' => session()->get('role_label')
            ];
        }

        $data = [
            'title'       => lang('Admin/Akun.page_title') ?? 'Akun Saya',
            'user'        => $user,  // KUNCI UTAMA: Kirim $user yang sudah terisi!
            'login_logs'  => $loginLogs,
            'prefs'       => $prefs,
            'semua_role'  => $dbRoles,
            'navigations' => $this->getSidebarMenu(),
            'color'       => $this->getColor(),
        ];

        return view('GuruMapel/akun-saya', $data);
    }

    // ==========================================
    // PERBAIKAN: FUNGSI UPDATE SINKRON 2 TABEL
    // ==========================================
    public function updateProfile()
    {
        if (!$this->request->isAJAX()) return $this->response->setStatusCode(403)->setJSON(['status' => 'error', 'message' => 'Akses ditolak']);

        $userId = session()->get('id') ?? session()->get('user_id');
        $userModel = new UserModel();
        $db = \Config\Database::connect();

        // Ambil data dari request POST
        $dataToUpdate = [
            'nama_lengkap'    => $this->request->getPost('nama_lengkap'),
            'email'           => $this->request->getPost('email'),
            'no_hp'           => $this->request->getPost('no_hp'),
            'no_darurat'      => $this->request->getPost('no_darurat'),
            'alamat_domisili' => $this->request->getPost('alamat')
        ];

        // Validasi sederhana (pastikan email tidak dipakai user lain)
        $cekEmail = $userModel->where('email', $dataToUpdate['email'])->where('id !=', $userId)->first();
        if ($cekEmail) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Email sudah digunakan oleh akun lain!']);
        }

        $db->transStart();
        try {
            // 1. Update ke tabel users
            $userModel->update($userId, $dataToUpdate);

            // 2. Sinkronkan ke tabel guru_tendik
            $cekGuru = $db->table('guru_tendik')->where('user_id', $userId)->get()->getRow();
            if ($cekGuru) {
                $db->table('guru_tendik')->where('user_id', $userId)->update($dataToUpdate);
            }

            // 3. Update session nama agar di header/navbar langsung berubah
            if (!empty($dataToUpdate['nama_lengkap'])) {
                session()->set('nama_lengkap', $dataToUpdate['nama_lengkap']);
            }

            $db->transComplete();

            if ($db->transStatus() === false) {
                return $this->response->setStatusCode(500)->setJSON(['status' => 'error', 'message' => 'Gagal melakukan sinkronisasi database.']);
            }

            return $this->response->setJSON(['status' => 'success', 'message' => 'Profil berhasil diperbarui!']);
        } catch (\Exception $e) {
            return $this->response->setStatusCode(500)->setJSON(['status' => 'error', 'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage()]);
        }
    }

    public function updatePassword()
    {
        $userId = session()->get('id') ?? session()->get('user_id');
        $userModel = new UserModel();
        $user = $userModel->find($userId);

        $oldPassword = $this->request->getPost('old_password');
        $newPassword = $this->request->getPost('new_password');

        // Verifikasi password lama
        if (!password_verify($oldPassword, $user['password'])) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Password lama salah!']);
        }

        // Hash password baru dan update
        $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);
        if ($userModel->update($userId, ['password' => $hashedPassword])) {
            return $this->response->setJSON(['status' => 'success', 'message' => 'Password berhasil diubah.']);
        }

        return $this->response->setJSON(['status' => 'error', 'message' => 'Gagal mengubah password.']);
    }

    public function updatePreferences()
    {
        $userId = session()->get('id') ?? session()->get('user_id');
        $db = \Config\Database::connect();

        $bahasaTerpilih = $this->request->getPost('bahasa'); // Tangkap bahasanya

        $data = [
            'user_id'      => $userId,
            'notif_login'  => $this->request->getPost('notif_login'),
            'two_factor'   => $this->request->getPost('two_factor'),
            'bahasa'       => $bahasaTerpilih,
            'theme'        => $this->request->getPost('theme'),
            'notif_email'  => $this->request->getPost('notif_email'),
            'notif_sistem' => $this->request->getPost('notif_sistem'),
            'notif_update' => $this->request->getPost('notif_update'),
        ];

        // ========================================================
        // INI KUNCI UTAMANYA: Update Session Secara Instan!
        // ========================================================
        session()->set('lang', $bahasaTerpilih);
        session()->set('locale', $bahasaTerpilih); 

        // Cek apakah user sudah punya data preferensi
        $cek = $db->table('user_preferences')->where('user_id', $userId)->get()->getRow();

        if ($cek) {
            $db->table('user_preferences')->where('user_id', $userId)->update($data);
        } else {
            $db->table('user_preferences')->insert($data);
        }

        return $this->response->setJSON(['status' => 'success', 'message' => 'Preferensi disimpan!']);
    }

    public function uploadAvatar()
    {
        if (!$this->request->isAJAX()) return $this->response->setStatusCode(403)->setJSON(['status' => 'error', 'message' => 'Akses ditolak']);

        $userId = session()->get('id') ?? session()->get('user_id');
        $userModel = new UserModel();
        $userData = $userModel->find($userId);
        $fileAvatar = $this->request->getFile('avatar');

        if ($fileAvatar && $fileAvatar->isValid() && !$fileAvatar->hasMoved()) {
            // Pastikan folder tujuan ada
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

        return $this->response->setJSON(['status' => 'error', 'message' => 'Gagal mengunggah foto atau file tidak valid.']);
    }
}
