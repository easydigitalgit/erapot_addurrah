<?php

namespace App\Models\Admin;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table            = 'users';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = ['username', 'email', 'password', 'role_id', 'is_active', 'foto_profil', 'nama_lengkap', 'no_hp', 'no_darurat', 'alamat_domisili'];
    protected $useTimestamps    = true;

    // Fungsi Login (Tetap sama)
    public function getUserByLogin($loginInput)
    {
        return $this->groupStart()
            ->where('username', $loginInput)
            ->orWhere('email', $loginInput)
            ->groupEnd()
            ->first();
    }

    /**
     * INI FUNGSI UTAMANYA
     * Menggabungkan data dari 4 tabel menjadi 1 baris data rapi
     */
    public function getUsersWithDetails($search = null, $role = null, $status = null)
    {
        // 1. SELECT DATA UTAMA
        $this->select('users.id, users.username, users.email, users.role_id, users.is_active, users.created_at, users.foto_profil');

        // TAMBAHKAN BARIS INI UNTUK MENGAMBIL FOTO SISWA:
        $this->select('siswa.foto_siswa');

        // Tarik nama role utama dari tabel roles
        $this->select('roles.role_name');

        // --- TARIK SEMUA ROLE & ID-NYA DARI user_roles ---
        // (GROUP_CONCAT menggunakan separator koma)
        $this->select('(SELECT GROUP_CONCAT(r.role_name SEPARATOR \',\') FROM user_roles ur JOIN roles r ON r.id = ur.role_id WHERE ur.user_id = users.id) as all_roles', false);
        $this->select('(SELECT GROUP_CONCAT(r.id SEPARATOR \',\') FROM user_roles ur JOIN roles r ON r.id = ur.role_id WHERE ur.user_id = users.id) as all_roles_ids', false);
        // ---------------------------------------------------

        // 2. COALESCE (Gabungkan data profil)
        $this->select('COALESCE(guru_tendik.nama_lengkap, siswa.nama_lengkap, orangtua_wali.nama_ayah, users.username) as full_name');
        $this->select('COALESCE(guru_tendik.no_hp, siswa.no_telp_rumah, orangtua_wali.no_hp_ortu, "-") as phone');
        $this->select('COALESCE(guru_tendik.nuptk, guru_tendik.nik, siswa.nis, "-") as nomor_induk');

        // 3. JOIN
        // Join ke tabel roles di sini HANYA untuk mendapatkan nama role_id utama
        $this->join('roles', 'roles.id = users.role_id', 'left');
        $this->join('guru_tendik', 'guru_tendik.user_id = users.id', 'left');
        $this->join('siswa', 'siswa.user_id = users.id', 'left');
        $this->join('orangtua_wali', 'orangtua_wali.user_id = users.id', 'left');

        // 4. FILTERING
        if ($search) {
            $this->groupStart()
                ->like('users.username', $search)
                ->orLike('users.email', $search)
                ->orLike('guru_tendik.nama_lengkap', $search)
                ->orLike('siswa.nama_lengkap', $search)
                ->groupEnd();
        }

        if ($role) {
            $this->where('users.role_id', $role);
        }

        if ($status && $status !== '') {
            if ($status === 'active') {
                $this->where('users.is_active', 1);
            } elseif ($status === 'inactive') {
                $this->where('users.is_active', 0);
            }
        }

        // Urutkan
        $this->orderBy('users.created_at', 'DESC');

        return $this;
    }

    // Hitung Statistik
    public function getStats()
    {
        return [
            'total'       => $this->db->table($this->table)->countAllResults(),
            'super_admin' => $this->db->table($this->table)->where('role_id', 1)->countAllResults(),
            'guru'        => $this->db->table($this->table)->where('role_id', 2)->countAllResults(),
            'wali_kelas'  => $this->db->table($this->table)->where('role_id', 3)->countAllResults(),
            'siswa'       => $this->db->table($this->table)->where('role_id', 4)->countAllResults(),
            'orang_tua'   => $this->db->table($this->table)->where('role_id', 5)->countAllResults(),
            'aktif'       => $this->db->table($this->table)->where('is_active', 1)->countAllResults(),
            'nonaktif'    => $this->db->table($this->table)->where('is_active', 0)->countAllResults(),
        ];
    }

    /**
     * BARU: Mengambil semua role yang dimiliki oleh user tertentu.
     * Digunakan untuk fitur Multi-Role Login.
     */
    public function getUserRoles($userId)
    {
        // Join ke tabel user_roles dan roles untuk dapat nama rolenya
        return $this->db->table('user_roles')
            ->select('user_roles.role_id, roles.role_name')
            ->join('roles', 'roles.id = user_roles.role_id')
            ->where('user_roles.user_id', $userId)
            ->get()
            ->getResultArray();
    }
}
