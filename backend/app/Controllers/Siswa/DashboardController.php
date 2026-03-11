<?php
namespace App\Controllers\Siswa;

use App\Controllers\SiswaBaseController;

class DashboardController extends SiswaBaseController
{
    public function index()
    {
        $db = \Config\Database::connect();
        
        // 1. Ambil ID User dari Session (User yang sedang login)
        $userId = session()->get('id') ?? session()->get('user_id');

       // 2. Query TAHAN BANTING: Mulai dari tabel users, lalu tarik data siswa jika ada
        $siswa = $db->table('users u')
            ->select('u.foto_profil, u.username, u.email as email_akun, s.*, r.nama_rombel, r.tingkat, gt.nama_lengkap as nama_wali_kelas')
            ->join('siswa s', 's.user_id = u.id', 'left')
            ->join('rombel r', 'r.id = s.rombel_id', 'left')
            ->join('guru_tendik gt', 'gt.id = r.wali_kelas_id', 'left')
            ->where('u.id', $userId)
            ->get()
            ->getRowArray();

        $data = [
            'title'      => 'Profil Siswa',
            'user'       => session()->get('username') ?? 'Siswa',
            'color'      => $this->getColor(),
            'siswa'      => $siswa,
        ];

        // Pastikan sidebar menu diload dengan benar
        if (method_exists($this, 'getSidebarMenu')) {
            $data['navigations'] = $this->getSidebarMenu(); 
        }

        return view('Siswa/dashboard', $data);
    }

    public function uploadAvatar()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(403)->setJSON(['status' => 'error', 'message' => 'Akses ditolak']);
        }

        $userId = session()->get('id') ?? session()->get('user_id');
        $userModel = new \App\Models\Admin\UserModel(); // Pastikan namespace modelnya benar
        $userData = $userModel->find($userId);
        
        $fileAvatar = $this->request->getFile('avatar');

        if ($fileAvatar && $fileAvatar->isValid() && !$fileAvatar->hasMoved()) {
            
            // 1. GANTI PATH FOLDER PENYIMPANAN DI SINI
            $path = FCPATH . 'assets/uploads/siswa/';
            
            if (!is_dir($path)) mkdir($path, 0777, true);

            // Auto Delete foto lama (akan otomatis mencari di folder siswa)
            if (!empty($userData['foto_profil']) && file_exists($path . $userData['foto_profil'])) {
                unlink($path . $userData['foto_profil']);
            }

            $newName = $fileAvatar->getRandomName();
            $fileAvatar->move($path, $newName);

            try {
                \Config\Services::image()
                    ->withFile($path . $newName)
                    ->fit(250, 250, 'center')
                    ->save($path . $newName, 70);
            } catch (\Exception $e) {}

            $userModel->update($userId, ['foto_profil' => $newName]);
            session()->set('foto_profil', $newName);

            return $this->response->setJSON([
                'status' => 'success',
                'message' => lang('Siswa/Dashboard.js_photo_updated') ?? 'Foto profil berhasil disimpan!',
                // 2. GANTI PATH URL BALIKAN KE JS DI SINI
                'new_avatar_url' => base_url('assets/uploads/siswa/' . $newName),
                'token' => csrf_hash() // Tambahkan token agar JS tidak error setelah upload
            ]);
        }

        return $this->response->setJSON([
            'status' => 'error', 
            'message' => 'Gagal membaca file gambar.',
            'token' => csrf_hash()
        ]);
    }

    // ========================================================================
    // FITUR BARU: UPDATE PASSWORD
    // ========================================================================
    public function updatePassword()
    {
        if (!$this->request->isAJAX()) return $this->response->setStatusCode(403);

        $oldPass = $this->request->getPost('old_password');
        $newPass = $this->request->getPost('new_password');
        $userId  = session()->get('id') ?? session()->get('user_id');

        $userModel = new \App\Models\Admin\UserModel();
        $user = $userModel->find($userId);

        // Pastikan nama field password di database Anda sesuai (misal: 'password_hash' atau 'password')
        $dbPasswordField = isset($user['password_hash']) ? $user['password_hash'] : $user['password'];

        // Cek password lama
        if (!password_verify($oldPass, $dbPasswordField)) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Password lama yang Anda masukkan salah.',
                'token' => csrf_hash()
            ]);
        }

        // Update ke password baru
        $updateData = [
            'password_hash' => password_hash($newPass, PASSWORD_DEFAULT) 
            // Ganti 'password_hash' dengan 'password' jika nama field di DB Anda 'password'
        ];

        if ($userModel->update($userId, $updateData)) {
            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Password berhasil diperbarui.',
                'token' => csrf_hash()
            ]);
        }

        return $this->response->setJSON([
            'status' => 'error',
            'message' => 'Gagal mengupdate password.',
            'token' => csrf_hash()
        ]);
    }

    // ========================================================================
    // FITUR BARU: UPDATE PREFERENCES (TEMA & BAHASA)
    // ========================================================================
    public function updatePrefs()
    {
        if (!$this->request->isAJAX()) return $this->response->setStatusCode(403);

        $theme = $this->request->getPost('theme');
        $bahasa = $this->request->getPost('bahasa');

        // 1. Simpan ke Session agar langsung terasa efeknya
        session()->set([
            'theme' => $theme,
            'bahasa' => $bahasa
        ]);

        // 2. (Opsional) Simpan ke Database Users jika Anda memiliki kolom preferensi
        // $userId = session()->get('id') ?? session()->get('user_id');
        // $userModel = new \App\Models\Admin\UserModel();
        // $userModel->update($userId, [
        //     'theme' => $theme,
        //     'bahasa' => $bahasa
        // ]);

        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'Preferensi berhasil disimpan.',
            'token' => csrf_hash()
        ]);
    }
}