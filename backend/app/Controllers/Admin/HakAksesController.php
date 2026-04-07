<?php

namespace App\Controllers\Admin;

use App\Controllers\AdminBaseController;

class HakAksesController extends AdminBaseController
{
    public function index(): string
    {
        $db = \Config\Database::connect();

        $roles = $db->table('roles')->orderBy('id', 'ASC')->get()->getResultArray();

        $auditLogs = $db->table('audit_logs')
            ->select('audit_logs.*, users.username')
            ->join('users', 'users.id = audit_logs.user_id', 'left')
            ->orderBy('audit_logs.created_at', 'DESC')
            ->limit(5)
            ->get()->getResultArray();

        $totalLogs = $db->table('audit_logs')->countAllResults();

        $data = [
            'user'        => 'Admin',
            'navigations' => $this->getSidebarMenu(),
            'color'       => $this->getColor(),
            'roles'       => $roles,
            'auditLogs'   => $auditLogs,
            'totalLogs'   => $totalLogs
        ];

        return view('admin/hak-akses', $data);
    }

    public function getRolePermissions($role_id)
    {
        if (!$this->request->isAJAX()) return $this->response->setStatusCode(404);

        $db = \Config\Database::connect();

        $permissions = $db->table('role_permissions')
            ->where('role_id', $role_id)
            ->get()->getResultArray();

        return $this->response->setJSON([
            'status'  => 'success',
            'data'    => $permissions,
            'message' => 'Hak akses berhasil dimuat'
        ]);
    }

    public function saveRolePermissions()
    {
        if (!$this->request->isAJAX()) return $this->response->setStatusCode(404);

        $role_id = $this->request->getPost('role_id');
        $permissionsRaw = $this->request->getPost('permissions');
        $permissionsData = json_decode($permissionsRaw, true);

        if (!$role_id || empty($permissionsData)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Data matriks kosong atau tidak valid.']);
        }

        $db = \Config\Database::connect();
        $builder = $db->table('role_permissions');

        $db->transStart();

        // 1. Ambil modul lama sebelum dihapus untuk dibersihkan dari Cache
        $savedModules = $builder->select('module_name')->where('role_id', $role_id)->get()->getResultArray();
        foreach ($savedModules as $mod) {
            session()->remove('rbac_' . $role_id . '_' . $mod['module_name']);
        }

        // 2. Bersihkan permission lama di DB
        $builder->where('role_id', $role_id)->delete();

        // 3. Siapkan data baru (Casting boolean JS ke Integer 1/0)
        $insertData = [];
        foreach ($permissionsData as $module => $actions) {
            $insertData[] = [
                'role_id'     => $role_id,
                'module_name' => $module,
                'can_view'    => (isset($actions['view']) && $actions['view'] == true) ? 1 : 0,
                'can_create'  => (isset($actions['create']) && $actions['create'] == true) ? 1 : 0,
                'can_update'  => (isset($actions['update']) && $actions['update'] == true) ? 1 : 0,
                'can_delete'  => (isset($actions['delete']) && $actions['delete'] == true) ? 1 : 0,
                'can_special' => (isset($actions['special']) && $actions['special'] == true) ? 1 : 0,
            ];

            // Bersihkan Cache modul baru yang akan dimasukkan
            session()->remove('rbac_' . $role_id . '_' . $module);
        }

        // 4. Eksekusi Insert
        if (!empty($insertData)) {
            $builder->insertBatch($insertData);
        }

        // 5. Catat Audit Log
        $admin_id = session()->get('id') ?? session()->get('user_id') ?? 1;

        $auditData = [
            'user_id'     => $admin_id,
            'action'      => 'UPDATE_PERMISSION',
            'description' => "Memperbarui matriks hak akses dinamis untuk Role ID: " . $role_id,
            'ip_address'  => $this->request->getIPAddress(),
            'created_at'  => date('Y-m-d H:i:s')
        ];
        $db->table('audit_logs')->insert($auditData);

        // ---> TAMBAHKAN BARIS INI: Pasang Alarm Global di Server <---
        cache()->save('rbac_last_update_role_' . $role_id, time(), 2592000); // Simpan selama 30 hari

        $db->transComplete();

        if ($db->transStatus() === false) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Gagal menyimpan ke database.']);
        }

        return $this->response->setJSON(['status' => 'success', 'message' => 'Hak akses berhasil disimpan!']);
    }

    public function addRole()
    {
        if (!$this->request->isAJAX()) return $this->response->setStatusCode(404);

        $db = \Config\Database::connect();
        $data = [
            'role_name'   => $this->request->getPost('role_name'),
            'description' => $this->request->getPost('description'),
            'status'      => $this->request->getPost('status') ?? 'active'
        ];

        $db->table('roles')->insert($data);
        $insertId = $db->insertID();

        if ($insertId) {
            return $this->response->setJSON(['status' => 'success', 'message' => 'Role berhasil ditambahkan!', 'id' => $insertId]);
        }
        return $this->response->setJSON(['status' => 'error', 'message' => 'Gagal menambahkan role.']);
    }

    // API Endpoint: Mengambil Semua Data Audit Log untuk Modal
    public function getAllAuditLogs()
    {
        if (!$this->request->isAJAX()) return $this->response->setStatusCode(404);

        $db = \Config\Database::connect();

        $auditLogs = $db->table('audit_logs')
            // PERBAIKAN: Menambahkan select dan join ke tabel users
            ->select('audit_logs.*, users.username')
            ->join('users', 'audit_logs.user_id = users.id', 'left')
            ->orderBy('audit_logs.created_at', 'DESC')
            ->limit(100)
            ->get()->getResultArray();

        return $this->response->setJSON([
            'status' => 'success',
            'data'   => $auditLogs
        ]);
    }
}
