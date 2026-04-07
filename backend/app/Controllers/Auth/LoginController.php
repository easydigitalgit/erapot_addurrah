<?php

namespace App\Controllers\Auth;

use App\Controllers\AdminBaseController;
use App\Models\Admin\UserModel;
use App\Models\Admin\GuruTendikModel;
use App\Models\Admin\SiswaModel;
use App\Models\Admin\OrangTuaModel;
use App\Models\Admin\RombelModel;

class LoginController extends AdminBaseController
{
    public function index()
    {
        if (session()->get('isLoggedIn')) {
            $redirectUrl = session()->get('redirect_url') ?? '/admin/dashboard-statistik';
            return redirect()->to($redirectUrl);
        }

        $data = [
            'color' => $this->getColor(),
        ];

        return view('Auth/login', $data);
    }

    public function process()
    {
        if (!$this->validate([
            'username' => 'required',
            'password' => 'required',
        ])) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Username dan Password wajib diisi.']);
        }

        $usernameInput = $this->request->getPost('username');
        $passwordInput = $this->request->getPost('password');

        $userModel = new UserModel();
        $db = \Config\Database::connect();

        $user = $db->table('users')
            ->select('users.*, siswa.nis')
            ->join('siswa', 'siswa.user_id = users.id', 'left')
            ->groupStart()
            ->where('users.username', $usernameInput)
            ->orWhere('users.email', $usernameInput)
            ->orWhere('siswa.nis', $usernameInput)
            ->groupEnd()
            ->get()
            ->getRowArray();

        if (!$user) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Username tidak ditemukan.']);
        }

        $isPasswordValid = false;
        if (password_verify($passwordInput, $user['password']) || $passwordInput === $user['password']) {
            $isPasswordValid = true;
        }

        if (!$isPasswordValid) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Password yang Anda masukkan salah.']);
        }

        if (isset($user['is_active']) && $user['is_active'] == 0) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Akun dinonaktifkan.']);
        }

        $dbRoles = method_exists($userModel, 'getUserRoles') ? $userModel->getUserRoles($user['id']) : [];
        $roleIds = array_column($dbRoles, 'role_id');

        if (!empty($user['role_id']) && !in_array($user['role_id'], $roleIds)) {
            $dbRoles[] = ['role_id' => $user['role_id']];
        }

        if (empty($dbRoles)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Akun valid, tapi tidak memiliki hak akses (Role).']);
        }

        $availableRoles = $this->mapRolesForLogin($user['id'], $dbRoles);

        if (empty($availableRoles)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Role tidak dikenali oleh sistem.']);
        }

        if (count($availableRoles) == 1) {
            $this->setSessionData($user, $availableRoles[0]);
            return $this->response->setJSON([
                'status' => 'success',
                'redirect' => $availableRoles[0]['redirect_url']
            ]);
        }

        return $this->response->setJSON([
            'status' => 'multi_role',
            'user_id' => $user['id'],
            'roles' => $availableRoles
        ]);
    }

    public function setRoleSession()
    {
        $userId = $this->request->getPost('user_id');
        $roleKey = $this->request->getPost('role_key');

        $userModel = new UserModel();
        $user = $userModel->find($userId);

        if (!$user) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'User tidak valid.']);
        }

        $dbRoles = method_exists($userModel, 'getUserRoles') ? $userModel->getUserRoles($userId) : [];
        $roleIds = array_column($dbRoles, 'role_id');

        if (!empty($user['role_id']) && !in_array($user['role_id'], $roleIds)) {
            $dbRoles[] = ['role_id' => $user['role_id']];
        }

        $availableRoles = $this->mapRolesForLogin($userId, $dbRoles);
        $selectedRole = null;

        foreach ($availableRoles as $role) {
            if ($role['key'] == $roleKey) {
                $selectedRole = $role;
                break;
            }
        }

        if ($selectedRole) {
            $this->setSessionData($user, $selectedRole);
            return $this->response->setJSON([
                'status' => 'success',
                'redirect' => $selectedRole['redirect_url']
            ]);
        }

        return $this->response->setJSON(['status' => 'error', 'message' => 'Role tidak valid untuk akun ini.']);
    }

    private function setSessionData($user, $roleData)
    {
        session()->destroy();
        session()->start();

        session()->set([
            'isLoggedIn'   => true,
            'id'           => $user['id'],
            'user_id'      => $user['id'],
            'username'     => $user['username'],
            'email'        => $user['email'] ?? '',
            'role_id'      => $roleData['role_id'],
            'role_key'     => $roleData['key'],
            'role_label'   => $roleData['label'],
            'redirect_url' => $roleData['redirect_url'],
            'foto_profil'  => $user['foto_profil'] ?? null
        ]);

        // ==========================================
        // MESIN PELACAK AKTIVITAS LOGIN (100% FIXED)
        // ==========================================
        $db = \Config\Database::connect();
        $agent = $this->request->getUserAgent();

        $browser = $agent->getBrowser() . ' ' . $agent->getVersion();
        $device = $agent->isMobile() ? $agent->getMobile() : $agent->getPlatform();
        if ($device == 'Unknown Platform' || empty($device)) {
            $device = 'Desktop/PC';
        }

        // Penanganan IP Address agar tidak pernah kosong
        $ipAddress = $this->request->getIPAddress();
        if (empty($ipAddress) || $ipAddress == '::1' || $ipAddress == '0.0.0.0') {
            $ipAddress = $_SERVER['HTTP_CLIENT_IP'] ?? $_SERVER['HTTP_X_FORWARDED_FOR'] ?? $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
        }
        if (strpos($ipAddress, ',') !== false) {
            $ipAddress = explode(',', $ipAddress)[0];
        }

        // HAPUS created_at agar Database yang mengatur waktunya (mencegah error insert)
        if ($db->tableExists('login_logs')) {
            $db->table('login_logs')->insert([
                'user_id'      => $user['id'],
                'ip_address'   => substr(trim($ipAddress), 0, 45),
                'user_agent'   => substr($agent->getAgentString(), 0, 255),
                'device_name'  => substr($device, 0, 100),
                'browser_name' => substr($browser, 0, 100),
                'status'       => 'Normal'
            ]);
        }
        // ==========================================

        if ($roleData['key'] === 'admin') {
            session()->set('trigger_auto_backup', true);
        }
    }

    private function mapRolesForLogin($userId, $dbRoles)
    {
        $roles = [];
        $hasWaliKelasRole = false;

        foreach ($dbRoles as $dbRole) {
            $rid = $dbRole['role_id'];
            $roleNameStr = strtolower($dbRole['role_name'] ?? $dbRole['nama_role'] ?? '');

            if ($rid == 1) {
                $roles[] = ['role_id' => 1, 'key' => 'admin', 'label' => 'Admin Sekolah', 'redirect_url' => base_url('/admin/dashboard-statistik')];
            }
            if ($rid == 2) {
                $roles[] = ['role_id' => 2, 'key' => 'guru', 'label' => 'Guru Mapel', 'redirect_url' => base_url('/guru/dashboard')];

                $guruModel = new \App\Models\Admin\GuruTendikModel();
                $guru = $guruModel->where('user_id', $userId)->first();
                if ($guru) {
                    $rombelModel = new \App\Models\Admin\RombelModel();
                    $rombel = $rombelModel->where('wali_kelas_id', $guru['id'])->first();
                    if ($rombel) {
                        $roles[] = ['role_id' => 3, 'key' => 'wali_kelas', 'label' => 'Wali Kelas ' . $rombel['nama_rombel'], 'redirect_url' => base_url('/wali/ringkasan-kelas')];
                        $hasWaliKelasRole = true;
                    }
                }
            }
            if ($rid == 3 && !$hasWaliKelasRole) {
                $roles[] = ['role_id' => 3, 'key' => 'wali_kelas', 'label' => 'Wali Kelas', 'redirect_url' => base_url('/wali/ringkasan-kelas')];
                $hasWaliKelasRole = true;
            }
            if ($rid == 4) {
                $roles[] = ['role_id' => 4, 'key' => 'siswa', 'label' => 'Siswa', 'redirect_url' => base_url('/siswa/dashboard')];
            }
            if ($rid == 5) {
                $roles[] = ['role_id' => 5, 'key' => 'orang_tua', 'label' => 'Orang Tua', 'redirect_url' => base_url('/orangtua/dashboard')];
            }
            if ($rid == 6 || $rid == 7 || strpos($roleNameStr, 'tahfiz') !== false || strpos($roleNameStr, 'tahfidz') !== false) {
                $roles[] = [
                    'role_id'      => $rid,
                    'key'          => 'guru_tahfidz',
                    'label'        => 'Guru Tahfidz',
                    'redirect_url' => base_url('/tahfidz/dashboard')
                ];
            }
        }
        return $roles;
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login')->with('message', 'Anda berhasil keluar.');
    }

    public function prosesLupaPassword()
    {
        $usernameOrEmail = $this->request->getPost('username');
        $method = $this->request->getPost('method');

        $userModel = new UserModel();
        $user = $userModel->groupStart()
            ->where('username', $usernameOrEmail)
            ->orWhere('email', $usernameOrEmail)
            ->groupEnd()
            ->first();

        if (!$user) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Akun dengan Username/Email tersebut tidak ditemukan.']);
        }

        if ($method == 'email') {
            if (empty($user['email'])) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Akun ini belum memiliki alamat email yang terdaftar.']);
            }

            $token = bin2hex(random_bytes(32));
            $db = \Config\Database::connect();
            $db->table('password_resets')->where('email', $user['email'])->delete();
            $db->table('password_resets')->insert([
                'email'      => $user['email'],
                'token'      => $token,
                'created_at' => date('Y-m-d H:i:s')
            ]);

            $emailService = \Config\Services::email();
            $emailService->setNewline("\r\n");
            $emailService->setCRLF("\r\n");
            $emailService->setMailType('html');

            $emailSender = getenv('email.SMTPUser');
            $emailService->setFrom($emailSender, 'Rapor Digital SMPIT Ad Durrah');
            $emailService->setTo($user['email']);
            $emailService->setSubject('Permintaan Reset Password');

            $resetLink = base_url('reset-password/' . $token);
            $pesanHtml = "
                <h3>Halo, " . esc($user['nama_lengkap'] ?? $user['username']) . "!</h3>
                <p>Kami menerima permintaan untuk mereset password akun Rapor Digital Anda.</p>
                <p>Silakan klik tombol di bawah ini untuk membuat password baru:</p>
                <br>
                <a href='{$resetLink}' style='display:inline-block; padding:12px 24px; background-color:#1F7A4D; color:#ffffff; text-decoration:none; border-radius:6px; font-weight:bold;'>Reset Password Sekarang</a>
                <br><br>
                <p><i>Link ini hanya berlaku selama 1 jam. Jika Anda tidak merasa meminta reset password, abaikan saja email ini.</i></p>
                <hr>
                <small>Sistem Rapor Digital SMPIT Ad Durrah</small>
            ";

            $emailService->setMessage($pesanHtml);

            if ($emailService->send()) {
                return $this->response->setJSON(['status' => 'success', 'message' => 'Link reset password telah dikirim ke email Anda!']);
            } else {
                $errorData = $emailService->printDebugger(['headers']);
                return $this->response->setJSON(['status' => 'error', 'message' => 'Error SMTP: ' . strip_tags($errorData)]);
            }
        } else if ($method == 'whatsapp') {
            return $this->response->setJSON(['status' => 'warning', 'message' => 'Fitur pengiriman otomatis via WhatsApp sedang dalam pemeliharaan. Silakan gunakan opsi Via Email untuk saat ini.']);
        }
    }

    public function resetPasswordForm($token)
    {
        $db = \Config\Database::connect();
        $resetData = $db->table('password_resets')->where('token', $token)->get()->getRowArray();

        if (!$resetData) {
            return redirect()->to('/login')->with('error', 'Pautan reset password tidak sah atau telah digunakan.');
        }

        $waktuDibuat = strtotime($resetData['created_at']);
        $sekarang = time();

        if ($sekarang - $waktuDibuat > 3600) {
            $db->table('password_resets')->where('token', $token)->delete();
            return redirect()->to('/login')->with('error', 'Pautan reset password telah luput. Sila mohon semula.');
        }

        $data = [
            'token' => $token,
            'color' => $this->getColor()
        ];
        return view('Auth/reset_password', $data);
    }

    public function updatePasswordFromReset()
    {
        $token = $this->request->getPost('token');
        $newPassword = $this->request->getPost('new_password');
        $confirmPassword = $this->request->getPost('confirm_password');

        if (empty($newPassword) || empty($confirmPassword) || empty($token)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Sila isikan semua ruangan.']);
        }
        if ($newPassword !== $confirmPassword) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Pengesahan kata laluan tidak sepadan.']);
        }
        if (strlen($newPassword) < 8) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Kata laluan mestilah sekurang-kurangnya 8 aksara.']);
        }

        $db = \Config\Database::connect();
        $resetData = $db->table('password_resets')->where('token', $token)->get()->getRowArray();

        if (!$resetData) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Token tidak sah.']);
        }

        $userModel = new UserModel();
        $user = $userModel->where('email', $resetData['email'])->first();

        if (!$user) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Akaun pengguna tidak ditemui.']);
        }

        $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);
        $userId = is_array($user) ? $user['id'] : $user->id;
        $userModel->update($userId, ['password' => $hashedPassword]);
        $db->table('password_resets')->where('token', $token)->delete();

        return $this->response->setJSON(['status' => 'success', 'message' => 'Berjaya! Kata laluan anda telah ditukar. Sila log masuk.']);
    }

    public function checkRbacUpdate()
    {
        if (!session()->get('isLoggedIn')) {
            return $this->response->setJSON(['reload' => false]);
        }

        $role_id = session()->get('role_id');
        $clientLastId = (int) $this->request->getGet('last_id');
        $db = \Config\Database::connect();

        $newUpdate = $db->table('audit_logs')
            ->where('action', 'UPDATE_PERMISSION')
            ->like('description', 'Role ID: ' . $role_id)
            ->where('id >', $clientLastId)
            ->get()
            ->getRowArray();

        if ($newUpdate) {
            foreach (session()->get() as $key => $value) {
                if (strpos($key, 'rbac_' . $role_id . '_') === 0) {
                    session()->remove($key);
                }
            }
            return $this->response->setJSON(['reload' => true]);
        }

        return $this->response->setJSON(['reload' => false]);
    }
}
