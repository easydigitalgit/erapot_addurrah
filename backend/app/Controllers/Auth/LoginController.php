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
        // 1. PASTIKAN PAKAI isLoggedIn
        if (session()->get('isLoggedIn')) {
            $redirectUrl = session()->get('redirect_url') ?? '/admin/dashboard-statistik';
            return redirect()->to($redirectUrl);
        }

        $data = [
            'color' => $this->getColor(),
        ];

        return view('Auth/login', $data);
    }

    /**
     * LOGIKA UTAMA: Cek User -> Cek Password -> Cek Role dari Tabel user_roles
     */
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

        // 1. Cari User (Memaksa return sebagai Array agar $user['password'] terbaca)
        $user = $userModel->asArray()
            ->groupStart()
            ->where('username', $usernameInput)
            ->orWhere('email', $usernameInput)
            ->groupEnd()
            ->first();

        // 2. Cek apakah Username ada?
        if (!$user) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Username tidak ditemukan.']);
        }

        // 3. Verifikasi Password (Bisa nerima Hash bcrypt ATAU ketikan teks biasa)
        $isPasswordValid = false;
        if (password_verify($passwordInput, $user['password']) || $passwordInput === $user['password']) {
            $isPasswordValid = true;
        }

        if (!$isPasswordValid) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Password yang Anda masukkan salah.']);
        }

        // Cek Status Aktif
        if (isset($user['is_active']) && $user['is_active'] == 0) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Akun dinonaktifkan.']);
        }

        // 4. AMBIL ROLE DARI DATABASE (DIPERBAIKI)
        $dbRoles = method_exists($userModel, 'getUserRoles') ? $userModel->getUserRoles($user['id']) : [];

        // Ekstrak semua ID role yang sudah ditemukan dari tabel user_roles
        $roleIds = array_column($dbRoles, 'role_id');

        // SELALU gabungkan role dasar dari tabel users JIKA belum ada di list (Mencegah Duplikat)
        if (!empty($user['role_id']) && !in_array($user['role_id'], $roleIds)) {
            $dbRoles[] = ['role_id' => $user['role_id']];
        }

        if (empty($dbRoles)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Akun valid, tapi tidak memiliki hak akses (Role).']);
        }

        // 5. OLAH DATA ROLE
        $availableRoles = $this->mapRolesForLogin($user['id'], $dbRoles);

        if (empty($availableRoles)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Role tidak dikenali oleh sistem.']);
        }

        // SKENARIO A: Hanya 1 Role -> Langsung Login
        if (count($availableRoles) == 1) {
            $this->setSessionData($user, $availableRoles[0]);

            return $this->response->setJSON([
                'status' => 'success',
                'redirect' => $availableRoles[0]['redirect_url']
            ]);
        }

        // SKENARIO B: Banyak Role -> Kirim Data untuk Popup Modal
        return $this->response->setJSON([
            'status' => 'multi_role',
            'user_id' => $user['id'],
            'roles' => $availableRoles
        ]);
    }

    /**
     * FINALISASI: Dipanggil saat user memilih salah satu role di Modal
     */
    public function setRoleSession()
    {
        $userId = $this->request->getPost('user_id');
        $roleKey = $this->request->getPost('role_key'); // ID Role atau Key Unik

        $userModel = new UserModel();
        $user = $userModel->find($userId);

        if (!$user) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'User tidak valid.']);
        }

        // 1. Ambil ulang role dari tabel user_roles
        $dbRoles = method_exists($userModel, 'getUserRoles') ? $userModel->getUserRoles($userId) : [];

        // 2. TAMBAHAN PENTING: Gabungkan juga role_id utama dari tabel users!
        $roleIds = array_column($dbRoles, 'role_id');
        if (!empty($user['role_id']) && !in_array($user['role_id'], $roleIds)) {
            $dbRoles[] = ['role_id' => $user['role_id']];
        }

        // 3. Petakan role yang sudah digabung
        $availableRoles = $this->mapRolesForLogin($userId, $dbRoles);

        $selectedRole = null;

        foreach ($availableRoles as $role) {
            // Kita bandingkan key (misal 'admin', 'guru', 'wali_kelas')
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

    // --- HELPER FUNCTIONS (Private) ---

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
        // MESIN PELACAK AKTIVITAS LOGIN
        // ==========================================
        $db = \Config\Database::connect();
        $agent = $this->request->getUserAgent();

        $browser = $agent->getBrowser() . ' ' . $agent->getVersion();
        $device = $agent->isMobile() ? $agent->getMobile() : $agent->getPlatform();
        if ($device == 'Unknown Platform') $device = 'Desktop/PC';

        // Cek jika tabel login_logs ada, baru insert (untuk menghindari error)
        if ($db->tableExists('login_logs')) {
             $db->table('login_logs')->insert([
                'user_id'      => $user['id'],
                'ip_address'   => $this->request->getIPAddress(),
                'user_agent'   => $agent->getAgentString(),
                'device_name'  => $device,
                'browser_name' => $browser,
                'status'       => 'Normal'
            ]);
        }
    }

    private function mapRolesForLogin($userId, $dbRoles)
    {
        $roles = [];

        // Penanda untuk memastikan kita tidak meletakkan Wali Kelas 2 kali
        $hasWaliKelasRole = false;

        foreach ($dbRoles as $dbRole) {
            $rid = $dbRole['role_id'];

            // Ambil nama role jika ada di query (untuk antisipasi nama huruf kecil/besar)
            $roleNameStr = strtolower($dbRole['role_name'] ?? $dbRole['nama_role'] ?? '');

            // ROLE 1: ADMIN
            if ($rid == 1) {
                $roles[] = ['role_id' => 1, 'key' => 'admin', 'label' => 'Admin Sekolah', 'redirect_url' => base_url('/admin/dashboard-statistik')];
            }

            // ROLE 2: GURU
            if ($rid == 2) {
                $roles[] = ['role_id' => 2, 'key' => 'guru', 'label' => 'Guru Mapel', 'redirect_url' => base_url('/guru/dashboard')];

                // Cek apakah guru ini adalah Wali Kelas
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

            // ROLE 3: WALI KELAS (Direct)
            if ($rid == 3 && !$hasWaliKelasRole) {
                $roles[] = ['role_id' => 3, 'key' => 'wali_kelas', 'label' => 'Wali Kelas', 'redirect_url' => base_url('/wali/ringkasan-kelas')];
                $hasWaliKelasRole = true;
            }

            // ROLE 4: SISWA
            if ($rid == 4) {
                $roles[] = ['role_id' => 4, 'key' => 'siswa', 'label' => 'Siswa', 'redirect_url' => base_url('/siswa/dashboard')];
            }

            // ROLE 5: ORANG TUA
            if ($rid == 5) {
                $roles[] = ['role_id' => 5, 'key' => 'orang_tua', 'label' => 'Orang Tua', 'redirect_url' => base_url('/orangtua/dashboard')];
            }

            // =========================================================
            // PERBAIKAN: ROLE GURU TAHFIDZ (Bisa ID 6, 7, atau ada kata tahfiz)
            // =========================================================
            if ($rid == 6 || $rid == 7 || strpos($roleNameStr, 'tahfiz') !== false || strpos($roleNameStr, 'tahfidz') !== false) {
                $roles[] = [
                    'role_id'      => $rid, // Menggunakan ID yang ditemukan (6 atau 7)
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

    // =========================================================
    // FUNGSI UNTUK MENGIRIM LINK RESET PASSWORD
    // =========================================================
    public function prosesLupaPassword()
    {
        $usernameOrEmail = $this->request->getPost('username');
        $method = $this->request->getPost('method'); // 'email' atau 'whatsapp'

        $userModel = new UserModel();
        
        // Cari user berdasarkan username atau email
        $user = $userModel->groupStart()
                          ->where('username', $usernameOrEmail)
                          ->orWhere('email', $usernameOrEmail)
                          ->groupEnd()
                          ->first();

        // 1. Validasi User
        if (!$user) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Akun dengan Username/Email tersebut tidak ditemukan.']);
        }

        // =====================================
        // JIKA MEMILIH VIA EMAIL
        // =====================================
        if ($method == 'email') {
            if (empty($user['email'])) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Akun ini belum memiliki alamat email yang terdaftar.']);
            }

            // 1. Buat Token Rahasia
            $token = bin2hex(random_bytes(32));

            // 2. Hubungkan ke Database
            $db = \Config\Database::connect();

            // 3. Hapus token lama jika user pernah request sebelumnya (biar tidak menumpuk)
            $db->table('password_resets')->where('email', $user['email'])->delete();

            // 4. Simpan Token Baru ke Tabel 'password_resets'
            $db->table('password_resets')->insert([
                'email'      => $user['email'],
                'token'      => $token,
                'created_at' => date('Y-m-d H:i:s')
            ]);

            // 5. Siapkan Email
            $emailService = \Config\Services::email();
            
            // --- TAMBAHAN WAJIB UNTUK GMAIL ---
            // Memaksa format baris baru (CRLF) agar Google tidak menolak pesannya
            $emailService->setNewline("\r\n");
            $emailService->setCRLF("\r\n");
            $emailService->setMailType('html');
            
            // PENTING: Gunakan email asli yang ada di .env sebagai pengirim agar tidak masuk Spam
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

            // 6. Eksekusi Pengiriman
            if ($emailService->send()) {
                return $this->response->setJSON(['status' => 'success', 'message' => 'Link reset password telah dikirim ke email Anda!']);
            } else {
                // HIDUPKAN DEBUGGER UNTUK MELIHAT PUNCA SEBENAR
                $errorData = $emailService->printDebugger(['headers']);
                return $this->response->setJSON(['status' => 'error', 'message' => 'Error SMTP: ' . strip_tags($errorData)]);
            }
        } 
        
        // =====================================
        // JIKA MEMILIH VIA WHATSAPP (SIMULASI SEMENTARA)
        // =====================================
        else if ($method == 'whatsapp') {
            return $this->response->setJSON(['status' => 'warning', 'message' => 'Fitur pengiriman otomatis via WhatsApp sedang dalam pemeliharaan. Silakan gunakan opsi Via Email untuk saat ini.']);
        }
    }

    // =========================================================
    // FUNGSI MENAMPILKAN HALAMAN RESET PASSWORD
    // =========================================================
    public function resetPasswordForm($token)
    {
        $db = \Config\Database::connect();
        
        // Cek adakah token wujud di dalam jadual password_resets
        $resetData = $db->table('password_resets')->where('token', $token)->get()->getRowArray();

        if (!$resetData) {
            // Jika tiada (mungkin sudah dipakai atau URL salah)
            return redirect()->to('/login')->with('error', 'Pautan reset password tidak sah atau telah digunakan.');
        }

        // Cek adakah token sudah luput (Lebih dari 1 jam)
        $waktuDibuat = strtotime($resetData['created_at']);
        $sekarang = time();
        
        if ($sekarang - $waktuDibuat > 3600) { // 3600 saat = 1 jam
            // Padam token yang dah luput
            $db->table('password_resets')->where('token', $token)->delete();
            return redirect()->to('/login')->with('error', 'Pautan reset password telah luput. Sila mohon semula.');
        }

        // Jika sah, paparkan halaman borang kata laluan baharu
        $data = [
            'token' => $token,
            'color' => $this->getColor()
        ];

        return view('Auth/reset_password', $data);
    }

    // =========================================================
    // FUNGSI MENYIMPAN PASSWORD BAHARU KE DATABASE
    // =========================================================
    public function updatePasswordFromReset()
    {
        $token = $this->request->getPost('token');
        $newPassword = $this->request->getPost('new_password');
        $confirmPassword = $this->request->getPost('confirm_password');

        // Validasi ringkas
        if (empty($newPassword) || empty($confirmPassword) || empty($token)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Sila isikan semua ruangan.']);
        }

        if ($newPassword !== $confirmPassword) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Pengesahan kata laluan tidak sepadan.']);
        }

        if (strlen($newPassword) < 8) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Kata laluan mestilah sekurang-kurangnya 8 aksara.']);
        }

        // Semak semula token untuk keselamatan
        $db = \Config\Database::connect();
        $resetData = $db->table('password_resets')->where('token', $token)->get()->getRowArray();

        if (!$resetData) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Token tidak sah.']);
        }

        // Cari Pengguna berdasarkan emel dari jadual reset
        $userModel = new UserModel();
        $user = $userModel->where('email', $resetData['email'])->first();

        if (!$user) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Akaun pengguna tidak ditemui.']);
        }

        // 1. Sulitkan (Hash) password baharu
        $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);
        
        // 2. Kemas kini password di dalam jadual users
        $userId = is_array($user) ? $user['id'] : $user->id;
        $userModel->update($userId, ['password' => $hashedPassword]);

        // 3. Padam token dari jadual password_resets (supaya tidak boleh dipakai 2 kali)
        $db->table('password_resets')->where('token', $token)->delete();

        return $this->response->setJSON(['status' => 'success', 'message' => 'Berjaya! Kata laluan anda telah ditukar. Sila log masuk.']);
    }
}