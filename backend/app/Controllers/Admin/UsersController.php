<?php

namespace App\Controllers\Admin;

use App\Controllers\AdminBaseController;
use App\Models\Admin\UserModel;

class UsersController extends AdminBaseController
{
    protected $userModel;
    protected $db;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->db = \Config\Database::connect();
    }

    public function index()
    {
        $search = $this->request->getGet('search') ?? '';
        $role   = $this->request->getGet('role') ?? '';
        $status = $this->request->getGet('status') ?? '';

        $dataUsers = $this->userModel->getUsersWithDetails($search, $role, $status)->paginate(10, 'users');
        
        $calonGuru = $this->db->table('guru_tendik')
            ->select('id, nama_lengkap as nama, nuptk, email')
            ->where('user_id', null)
            ->orderBy('nama_lengkap', 'ASC')
            ->get()->getResultArray();

        $calonSiswa = $this->db->table('siswa')
            ->select('id, nama_lengkap as nama, nis, email_siswa as email')
            ->where('user_id', null)
            ->orderBy('nama_lengkap', 'ASC')
            ->get()->getResultArray();

        $calonOrangTua = $this->db->table('orangtua_wali')
            ->select('id, nama_ayah as nama, email_ortu as email') 
            ->where('user_id', null)
            ->orderBy('nama_ayah', 'ASC')
            ->get()->getResultArray();

        $calonTahfidz = $this->db->table('guru_tendik') 
            ->select('id, nama_lengkap as nama, nuptk, email')
            ->where('user_id', null)
            ->like('jabatan', 'tahfi', 'both')
            ->orderBy('nama_lengkap', 'ASC')
            ->get()->getResultArray();

        $data = [
            'title'           => 'Manajemen Pengguna',
            'users'           => $dataUsers, 
            'pager'           => $this->userModel->pager,
            'stats'           => $this->userModel->getStats(),
            'roles'           => $this->db->table('roles')->get()->getResultArray(),
            
            'calon_guru'      => $calonGuru, 
            'calon_siswa'     => $calonSiswa, 
            'calon_orangtua'  => $calonOrangTua,
            'calon_tahfidz'   => $calonTahfidz,
            
            'search_query'    => $search,
            'selected_role'   => $role,
            'selected_status' => $status,
            'color'           => $this->getColor(),
            'navigations'     => $this->getSidebarMenu()
        ];

        return view('admin/users', $data);
    }

    // =========================================================================
    // CRUD: CREATE
    // =========================================================================
// =========================================================================
    // CRUD: CREATE
    // =========================================================================
    public function store()
    {
        $db = \Config\Database::connect();
        
        $username     = $this->request->getPost('username');
        $nama_lengkap = $this->request->getPost('nama_lengkap');
        $email        = $this->request->getPost('email');
        $password     = $this->request->getPost('password'); 
        $role_id      = $this->request->getPost('role_id');
        
        // Tangkap data relasi wajib dari Javascript
        $linked_id    = $this->request->getPost('linked_id');
        $linked_type  = $this->request->getPost('linked_type');

        if (empty($username) || empty($password) || empty($role_id)) {
            return $this->response->setJSON([
                'status' => 'error', 
                'message' => 'Username, Password, dan Role wajib diisi!'
            ]);
        }

        // BACKEND VALIDATION: Mencegah bypass/injeksi dari luar form
        $roleData = $db->table('roles')->where('id', $role_id)->get()->getRowArray();
        $roleName = $roleData ? strtolower(str_replace(' ', '', $roleData['role_name'])) : '';
        $requiredLinkRoles = ['guru', 'tendik', 'walikelas', 'siswa', 'orangtua', 'wali', 'gurutahfidzh', 'gurutahfidz'];
        
        if (in_array($roleName, $requiredLinkRoles) && empty($linked_id)) {
             return $this->response->setJSON([
                'status'  => 'error', 
                'message' => 'Gagal! Anda WAJIB memilih data dari dropdown pencarian, bukan mengetik manual.'
            ]);
        }

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $userData = [
            'username'     => $username,
            'nama_lengkap' => $nama_lengkap, 
            'email'        => $email,
            'password'     => $hashedPassword, 
            'role_id'      => $role_id,
            'is_active'    => 1
        ];

        // Gunakan Transaction agar jika salah satu gagal, semua dibatalkan
        $db->transStart(); 

        try {
            $exists = $this->userModel->where('username', $username)->first();
            if ($exists) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Username sudah digunakan oleh user lain.']);
            }

            // 1. Simpan ke tabel users
            $db->table('users')->insert($userData);
            $newUserId = $db->insertID(); 

            // 2. Simpan ke tabel user_roles
            if ($db->tableExists('user_roles')) {
                $db->table('user_roles')->insert([
                    'user_id' => $newUserId,
                    'role_id' => $role_id
                ]);
            }

            // 3. INTEGRASI WAJIB: Update user_id ke tabel Master
            if (!empty($linked_id) && !empty($linked_type)) {
                if ($linked_type === 'guru' || $linked_type === 'tahfidz') {
                    $db->table('guru_tendik')->where('id', $linked_id)->update(['user_id' => $newUserId]);
                } elseif ($linked_type === 'siswa') {
                    $db->table('siswa')->where('id', $linked_id)->update(['user_id' => $newUserId]);
                } elseif ($linked_type === 'orangtua') {
                    $db->table('orangtua_wali')->where('id', $linked_id)->update(['user_id' => $newUserId]);
                }
            }

            $db->transComplete(); 

            if ($db->transStatus() === false) {
                return $this->response->setJSON([
                    'status' => 'error', 
                    'message' => 'Terjadi kesalahan saat mengaitkan akun dengan data master.'
                ]);
            }

            return $this->response->setJSON([
                'status' => 'success', 
                'message' => 'Pengguna berhasil ditambahkan dan ditautkan!'
            ]);

        } catch (\Exception $e) {
            $db->transRollback();
            return $this->response->setJSON([
                'status' => 'error', 
                'message' => 'Ralat sistem: ' . $e->getMessage()
            ]);
        }
    }
    // =========================================================================
    // CRUD: UPDATE (FUNGSI BARU UNTUK EDIT USER)
    // =========================================================================
    public function update()
    {
        $id           = $this->request->getPost('id');
        $nama_lengkap = $this->request->getPost('nama_lengkap'); // Tangkap nama_lengkap
        $email        = $this->request->getPost('email');
        $password     = $this->request->getPost('password'); 
        $role_id      = $this->request->getPost('role_id');

        if (empty($id) || empty($role_id)) {
            return $this->response->setJSON([
                'status' => 'error', 
                'message' => 'Data tidak lengkap untuk melakukan update!'
            ]);
        }

        $userData = [
            'nama_lengkap' => $nama_lengkap, // Masukkan ke dalam array update
            'email'        => $email,
            'role_id'      => $role_id
        ];

        if (!empty($password)) {
            $userData['password'] = password_hash($password, PASSWORD_DEFAULT);
        }

        try {
            $this->userModel->update($id, $userData);

            $db = \Config\Database::connect();
            if ($db->tableExists('user_roles')) {
                $db->table('user_roles')->where('user_id', $id)->delete(); 
                $db->table('user_roles')->insert([
                    'user_id' => $id,
                    'role_id' => $role_id
                ]); 
            }

            return $this->response->setJSON([
                'status' => 'success', 
                'message' => 'Data Pengguna berhasil diperbarui!'
            ]);

        } catch (\Exception $e) {
            return $this->response->setJSON([
                'status' => 'error', 
                'message' => 'Ralat sistem: ' . $e->getMessage()
            ]);
        }
    }

    // =========================================================================
    // CRUD: DELETE & STATUS
    // =========================================================================
    public function delete() {
        $id = $this->request->getPost('id');
        
        if(!$id) return $this->response->setJSON(['status' => 'error', 'message' => 'ID tidak ditemukan']);

        try {
            if($this->userModel->delete($id)){
                return $this->response->setJSON(['status' => 'success']);
            } else {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Gagal menghapus data']);
            }
        } catch (\Exception $e) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Gagal menghapus: Data mungkin berelasi dengan tabel lain.']);
        }
    }

    public function deactivate() {
        $id = $this->request->getPost('id');
        if($this->userModel->update($id, ['is_active' => 0])) {
            return $this->response->setJSON(['status' => 'success']);
        }
        return $this->response->setJSON(['status' => 'error', 'message' => 'Gagal update status']);
    }

    public function activate() {
        $id = $this->request->getPost('id');
        if($this->userModel->update($id, ['is_active' => 1])) {
            return $this->response->setJSON(['status' => 'success']);
        }
        return $this->response->setJSON(['status' => 'error', 'message' => 'Gagal update status']);
    }
    
    public function bulkDelete() {
        $json = $this->request->getJSON();
        $ids = $json->ids ?? [];
        
        if (!empty($ids)) {
            try {
                $this->userModel->whereIn('id', $ids)->delete();
                return $this->response->setJSON(['status' => 'success']);
            } catch (\Exception $e) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Gagal menghapus beberapa data.']);
            }
        }
        return $this->response->setJSON(['status' => 'error', 'message' => 'Tidak ada data yang dipilih']);
    }

    // =========================================================================
    // API: INLINE MULTI-ROLE UPDATE DARI TABEL
    // =========================================================================
    public function updateInlineRoles()
    {
        if (!$this->request->isAJAX()) return $this->response->setStatusCode(404);

        $json = $this->request->getJSON();
        $userId = $json->user_id ?? null;
        $roleIds = $json->role_ids ?? [];

        if (empty($userId)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'User ID tidak valid']);
        }

        $db = \Config\Database::connect();
        $db->transStart();

        try {
            // Hapus semua akses role lama user ini
            $db->table('user_roles')->where('user_id', $userId)->delete();

            if (!empty($roleIds)) {
                $insertData = [];
                foreach ($roleIds as $rId) {
                    $insertData[] = ['user_id' => $userId, 'role_id' => $rId];
                }
                // Masukkan peran-peran baru
                $db->table('user_roles')->insertBatch($insertData);

                // Update role_id utama di tabel users (Ambil elemen pertama sebagai role utama)
                $db->table('users')->where('id', $userId)->update(['role_id' => $roleIds[0]]);
            } else {
                // Jika semua centang dilepas, setidaknya beri dia role Kosong (agar tak error)
                $db->table('users')->where('id', $userId)->update(['role_id' => 0]);
            }

            $db->transComplete();

            if ($db->transStatus() === false) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Gagal menyimpan izin akses.']);
            }

            return $this->response->setJSON(['status' => 'success', 'message' => 'Hak akses diperbarui!']);
        } catch (\Exception $e) {
            $db->transRollback();
            return $this->response->setJSON(['status' => 'error', 'message' => 'System Error: ' . $e->getMessage()]);
        }
    }
}