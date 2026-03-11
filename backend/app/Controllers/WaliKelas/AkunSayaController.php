<?php
namespace App\Controllers\WaliKelas;

use App\Controllers\WaliKelasBaseController;

class AkunSayaController extends WaliKelasBaseController
{
    public function index(): string
    {
        $db = \Config\Database::connect();
        $userId = session()->get('user_id');
        
        $sekolah = $db->table('sekolah')->select('warna_primary, warna_secondary')->get()->getRowArray();
        $color = [
            'warna_primary'   => $sekolah ? $sekolah['warna_primary'] : '#10b981',
            'warna_secondary' => $sekolah ? $sekolah['warna_secondary'] : '#ecfdf5',
        ];

        // 1. Data Akun (User) & Role
        $user = $db->table('users')
                   ->select('users.*, roles.role_name')
                   ->join('roles', 'roles.id = users.role_id', 'left')
                   ->where('users.id', $userId)
                   ->get()->getRowArray();

        // 2. Data Profil (Guru/Tendik)
        $profile = [];
        if ($db->tableExists('guru_tendik')) {
            $profile = $db->table('guru_tendik')->where('user_id', $userId)->get()->getRowArray();
        }

        // 3. Data Riwayat Aktivitas (Log Terbaru)
        $logs = [];
        if ($db->tableExists('audit_logs')) {
            $logs = $db->table('audit_logs')
                       ->where('user_id', $userId)
                       ->orderBy('created_at', 'DESC')
                       ->limit(5)
                       ->get()->getResultArray();
        }

        // 4. Data Hak Akses (Permissions)
        $permissions = [];
        if ($user && isset($user['role_id']) && $db->tableExists('role_permissions')) {
            $permissions = $db->table('role_permissions')
                              ->where('role_id', $user['role_id'])
                              ->get()->getResultArray();
        }

        $data = [
            'title'       => 'Akun Saya',
            'user'        => session()->get('nama_lengkap') ?? 'Wali Kelas',
            'navigations' => $this->getSidebarMenu(),
            'color'       => $color,
            'userData'    => $user,
            'profile'     => $profile,
            'logs'        => $logs,
            'permissions' => $permissions
        ];
        
        return view('WaliKelas/akun-saya', $data); 
    }

    // ==========================================
    // API: Simpan Profil Peribadi
    // ==========================================
    public function updatePersonal()
    {
        try {
            $json = $this->request->getJSON();
            $db = \Config\Database::connect();
            $userId = session()->get('user_id');

            $dataToUpdate = [
                'nama_lengkap'    => $json->nama_lengkap,
                'email'           => $json->email,
                'no_hp'           => $json->no_hp,
                'no_darurat'      => $json->no_darurat,
                'alamat_domisili' => $json->alamat
            ];

            $guru = $db->table('guru_tendik')->where('user_id', $userId)->get()->getRowArray();
            if ($guru) {
                $db->table('guru_tendik')->where('id', $guru['id'])->update($dataToUpdate);
            }

            $db->table('users')->where('id', $userId)->update(['email' => $json->email]);
            session()->set('nama_lengkap', $json->nama_lengkap);

            // Rekod Aktiviti Log
            if ($db->tableExists('audit_logs')) {
                $db->table('audit_logs')->insert([
                    'user_id' => $userId,
                    'action' => 'UPDATE_PROFILE',
                    'description' => 'Memperbarui data informasi pribadi',
                    'ip_address' => $this->request->getIPAddress()
                ]);
            }

            return $this->response->setJSON(['success' => true, 'message' => 'Profil berhasil diperbarui.']);
        } catch (\Exception $e) {
            return $this->response->setStatusCode(500)->setJSON(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    // ==========================================
    // API: Tukar Password
    // ==========================================
    public function updatePassword()
    {
        try {
            $json = $this->request->getJSON();
            $db = \Config\Database::connect();
            $userId = session()->get('user_id');

            $user = $db->table('users')->where('id', $userId)->get()->getRowArray();

            if (!password_verify($json->oldPassword, $user['password'])) {
                return $this->response->setJSON(['success' => false, 'message' => 'Password lama tidak sesuai!']);
            }

            $newPasswordHash = password_hash($json->newPassword, PASSWORD_DEFAULT);
            $db->table('users')->where('id', $userId)->update(['password' => $newPasswordHash]);

            if ($db->tableExists('audit_logs')) {
                $db->table('audit_logs')->insert([
                    'user_id' => $userId,
                    'action' => 'UPDATE_PASSWORD',
                    'description' => 'Mengubah kata sandi akun',
                    'ip_address' => $this->request->getIPAddress()
                ]);
            }

            return $this->response->setJSON(['success' => true, 'message' => 'Password berhasil diubah. Silakan login kembali.']);
            
        } catch (\Exception $e) {
            return $this->response->setStatusCode(500)->setJSON(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}