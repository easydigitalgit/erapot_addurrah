<?php
namespace App\Controllers\OrangTua;

use App\Controllers\OrangTuaBaseController;

class AkunSayaController extends OrangTuaBaseController
{
    public function index()
    {
        $db = \Config\Database::connect();
        $userId = session()->get('user_id') ?? session()->get('id'); 

        // 1. AMBIL DATA DARI MASING-MASING TABEL
        $userRaw = $db->table('users')->where('id', $userId)->get()->getRowArray() ?: [];
        
        $ortuRaw = [];
        if ($db->tableExists('orangtua_wali')) {
            $ortuRaw = $db->table('orangtua_wali')->where('user_id', $userId)->get()->getRowArray() ?: [];
        }

        // Cek Nama 
        $namaFinal = $userRaw['nama_lengkap'] ?? session()->get('nama_lengkap') ?? 'Bapak/Ibu';
        if (!empty($ortuRaw['nama_ayah']) && $ortuRaw['nama_ayah'] !== '-') $namaFinal = $ortuRaw['nama_ayah'];
        elseif (!empty($ortuRaw['nama_ibu']) && $ortuRaw['nama_ibu'] !== '-') $namaFinal = $ortuRaw['nama_ibu'];
        elseif (!empty($ortuRaw['nama_wali']) && $ortuRaw['nama_wali'] !== '-') $namaFinal = $ortuRaw['nama_wali'];

        // Cek No HP & Alamat
        $noHpFinal = $ortuRaw['no_hp_ortu'] ?? $userRaw['no_hp'] ?? '';
        $alamatFinal = $ortuRaw['alamat_orangtua'] ?? $userRaw['alamat'] ?? '';

        $user = [
            'id'           => $userId,
            'foto_profil'  => $userRaw['foto_profil'] ?? null,
            'username'     => $userRaw['username'] ?? '',
            'email'        => $userRaw['email'] ?? $ortuRaw['email_ortu'] ?? '',
            'nama_lengkap' => $namaFinal,
            'no_hp'        => $noHpFinal,
            'alamat'       => $alamatFinal,
            'created_at'   => $userRaw['created_at'] ?? date('Y-m-d H:i:s')
        ];

        // 2. AMBIL DATA MULTI-ROLE
        $semua_role = [];
        if ($db->tableExists('user_roles') && $db->tableExists('roles')) {
            $semua_role = $db->table('user_roles')
                ->select('roles.role_name')
                ->join('roles', 'roles.id = user_roles.role_id')
                ->where('user_roles.user_id', $userId)
                ->get()
                ->getResultArray();
        }

        // =========================================================
        // 3. FOKUS RIWAYAT LOGIN (MENGGUNAKAN TABEL login_logs ASLI)
        // =========================================================
        $login_logs = [];
        if ($db->tableExists('login_logs')) {
            // Kita tembak langsung tabel login_logs sesuai dengan database
            $raw_logs = $db->table('login_logs')
                ->where('user_id', $userId)
                ->orderBy('created_at', 'DESC')
                ->limit(5)
                ->get()
                ->getResultArray();
            
            foreach ($raw_logs as $log) {
                // Di databasemu status isinya "Normal", kita ubah jadi "Berhasil" agar rapi di UI
                $statusDb = $log['status'] ?? 'Berhasil';
                if (strtolower($statusDb) === 'normal' || strtolower($statusDb) === 'sukses') {
                    $statusDb = 'Berhasil';
                }

                $login_logs[] = [
                    'waktu'   => $log['created_at'] ?? null,
                    'ip'      => $log['ip_address'] ?? '127.0.0.1',
                    'device'  => !empty($log['device_name']) ? $log['device_name'] : 'Perangkat Tidak Diketahui',
                    'browser' => !empty($log['browser_name']) ? $log['browser_name'] : 'Browser',
                    'status'  => $statusDb
                ];
            }
        } elseif ($db->tableExists('auth_logins')) {
            // Fallback (Jaga-jaga jika menggunakan auth_logins)
            $raw_logs = $db->table('auth_logins')
                ->where('user_id', $userId)
                ->orderBy('date', 'DESC')
                ->limit(5)
                ->get()
                ->getResultArray();
            
            foreach ($raw_logs as $log) {
                $login_logs[] = [
                    'waktu'   => $log['date'] ?? null,
                    'ip'      => $log['ip_address'] ?? '127.0.0.1',
                    'device'  => 'Perangkat Default',
                    'browser' => 'Browser',
                    'status'  => (isset($log['success']) && $log['success'] == 1) ? 'Berhasil' : 'Gagal'
                ];
            }
        }

        // 4. AMBIL PREFERENSI TEMA
        $prefs = ['theme' => session()->get('theme') ?? 'light', 'bahasa' => session()->get('bahasa') ?? 'id'];
        if ($db->tableExists('user_preferences')) {
            $prefDb = $db->table('user_preferences')->where('user_id', $userId)->get()->getRowArray();
            if ($prefDb) {
                $prefs['theme'] = $prefDb['theme'] ?? 'light';
                $prefs['bahasa'] = $prefDb['bahasa'] ?? 'id';
                session()->set('theme', $prefs['theme']);
                session()->set('bahasa', $prefs['bahasa']);
            }
        }

        $data = [
            'title'       => lang('OrangTua/AkunSaya.page_title') ?? 'Akun Saya - Orang Tua',
            'user'        => $user,
            'semua_role'  => $semua_role,
            'login_logs'  => $login_logs,
            'prefs'       => $prefs,
            'color'       => $this->getColor(),
            'navigations' => $this->getSidebarMenu() 
        ];

        return view('OrangTua/akun-saya', $data);
    }

    public function updateProfile()
    {
        if (!$this->request->isAJAX()) return $this->response->setStatusCode(403);

        $db = \Config\Database::connect();
        $userId = session()->get('user_id') ?? session()->get('id');

        $email  = $this->request->getPost('email');
        $nama   = $this->request->getPost('nama_lengkap');
        $no_hp  = $this->request->getPost('no_hp');
        $alamat = $this->request->getPost('alamat');

        $db->table('users')->where('id', $userId)->update([
            'email'        => $email,
            'nama_lengkap' => $nama,
            'updated_at'   => date('Y-m-d H:i:s')
        ]);

        if ($db->tableExists('orangtua_wali')) {
            $cekOrtu = $db->table('orangtua_wali')->where('user_id', $userId)->get()->getRowArray();
            
            $updateOrtu = [
                'no_hp_ortu'      => $no_hp,
                'email_ortu'      => $email,
                'alamat_orangtua' => $alamat
            ];

            if ($cekOrtu) {
                if (!empty($cekOrtu['nama_ayah']) && $cekOrtu['nama_ayah'] !== '-') $updateOrtu['nama_ayah'] = $nama;
                elseif (!empty($cekOrtu['nama_ibu']) && $cekOrtu['nama_ibu'] !== '-') $updateOrtu['nama_ibu'] = $nama;
                elseif (!empty($cekOrtu['nama_wali']) && $cekOrtu['nama_wali'] !== '-') $updateOrtu['nama_wali'] = $nama;
                else $updateOrtu['nama_ayah'] = $nama; 
                
                $db->table('orangtua_wali')->where('user_id', $userId)->update($updateOrtu);
            } else {
                $updateOrtu['user_id'] = $userId;
                $updateOrtu['nama_ayah'] = $nama; 
                $db->table('orangtua_wali')->insert($updateOrtu);
            }
        }

        session()->set('nama_lengkap', $nama);

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => lang('OrangTua/AkunSaya.js_success') ?? 'Profil berhasil diperbarui.',
            'token'   => csrf_hash()
        ]);
    }

    public function uploadAvatar()
    {
        if (!$this->request->isAJAX()) return $this->response->setStatusCode(403);
        $userId = session()->get('user_id') ?? session()->get('id');
        $db = \Config\Database::connect();
        $user = $db->table('users')->where('id', $userId)->get()->getRowArray();
        
        $fileAvatar = $this->request->getFile('avatar');

        if ($fileAvatar && $fileAvatar->isValid() && !$fileAvatar->hasMoved()) {
            $path = FCPATH . 'assets/uploads/avatars/'; 
            if (!is_dir($path)) mkdir($path, 0777, true);

            if (!empty($user['foto_profil']) && file_exists($path . $user['foto_profil'])) {
                unlink($path . $user['foto_profil']);
            }

            $originalName = $fileAvatar->getRandomName();
            $fileAvatar->move($path, $originalName);

            $webpName  = pathinfo($originalName, PATHINFO_FILENAME) . '.webp';
            $finalName = $originalName; // Fallback jika gagal convert

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
            } catch (\Exception $e) {}

            $db->table('users')->where('id', $userId)->update(['foto_profil' => $finalName]);
            session()->set('foto_profil', $finalName);

            return $this->response->setJSON(['status' => 'success', 'message' => 'Foto profil berhasil diperbarui.', 'new_avatar_url' => base_url('assets/uploads/avatars/' . $finalName), 'token' => csrf_hash()]);
        }
        return $this->response->setJSON(['status' => 'error', 'message' => 'Gagal mengunggah foto.', 'token' => csrf_hash()]);
    }

    public function updatePassword()
    {
        if (!$this->request->isAJAX()) return $this->response->setStatusCode(403);
        $oldPass = $this->request->getPost('old_password');
        $newPass = $this->request->getPost('new_password');
        $userId  = session()->get('user_id') ?? session()->get('id');
        $db = \Config\Database::connect();
        $user = $db->table('users')->where('id', $userId)->get()->getRowArray();
        
        $dbPasswordField = $user['password'] ?? '';

        if (!password_verify($oldPass, $dbPasswordField) && $oldPass !== $dbPasswordField) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Password lama salah.', 'token' => csrf_hash()]);
        }
        
        // PERBAIKAN: Mengubah kunci array menjadi 'password' sesuai struktur database
        $db->table('users')->where('id', $userId)->update([
            'password' => password_hash($newPass, PASSWORD_DEFAULT)
        ]);
        
        return $this->response->setJSON(['status' => 'success', 'message' => 'Password berhasil diperbarui.', 'token' => csrf_hash()]);
    }

    public function updatePreferences()
    {
        if (!$this->request->isAJAX()) return $this->response->setStatusCode(403);
        $theme  = $this->request->getPost('theme');
        $bahasa = $this->request->getPost('bahasa');
        $userId = session()->get('user_id') ?? session()->get('id');
        session()->set(['theme' => $theme, 'bahasa' => $bahasa]);

        try {
            $db = \Config\Database::connect();
            if ($db->tableExists('user_preferences')) {
                $cek = $db->table('user_preferences')->where('user_id', $userId)->countAllResults();
                if ($cek > 0) $db->table('user_preferences')->where('user_id', $userId)->update(['theme' => $theme, 'bahasa' => $bahasa]);
                else $db->table('user_preferences')->insert(['user_id' => $userId, 'theme' => $theme, 'bahasa' => $bahasa]);
            }
        } catch (\Exception $e) {}
        return $this->response->setJSON(['status' => 'success', 'message' => 'Preferensi berhasil disimpan.', 'token' => csrf_hash()]);
    }

    protected function getColor()
    {
        try {
            $db = \Config\Database::connect();
            $colorData = $db->table('sekolah')->select('warna_primary, warna_secondary')->get()->getRowArray();
            if ($colorData) return $colorData;
        } catch (\Exception $e) {}
        return ['warna_primary' => '#10b981', 'warna_secondary' => '#ecfdf5'];
    }
}