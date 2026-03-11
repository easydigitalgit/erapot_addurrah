<?php
namespace App\Controllers\Admin;

use App\Controllers\AdminBaseController;

class HakAksesController extends AdminBaseController
{
    public function index(): string
    {
        $db = \Config\Database::connect();
        
        $roles = $db->table('roles')->orderBy('id', 'ASC')->get()->getResultArray();

        // 1. Ambil 5 data audit trail terbaru dari database
        // Kita join dengan tabel users agar tahu siapa username yang mengubahnya
        $auditLogs = $db->table('audit_logs')
                        ->select('audit_logs.*, users.username') 
                        ->join('users', 'users.id = audit_logs.user_id', 'left')
                        ->orderBy('audit_logs.created_at', 'DESC')
                        ->limit(5)
                        ->get()->getResultArray();
                        
        // 2. Hitung total log untuk info di bagian bawah tabel
        $totalLogs = $db->table('audit_logs')->countAllResults();

        $data = [
            'user'        => 'Admin',
            'navigations' => $this->getSidebarMenu(),
            'color'       => $this->getColor(),
            'roles'       => $roles,
            'auditLogs'   => $auditLogs, // Lempar data log ke View
            'totalLogs'   => $totalLogs  // Lempar total log ke View
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

        // (KODE AUDIT TRAIL DIHAPUS DARI SINI)

        return $this->response->setJSON([
            'status'  => 'success',
            'data'    => $permissions, // Kembalikan data aslinya
            'message' => 'Hak akses berhasil dimuat'
        ]);
    }

    public function saveRolePermissions()
    {
        if (!$this->request->isAJAX()) return $this->response->setStatusCode(404);

        $role_id = $this->request->getPost('role_id');
        $permissionsData = json_decode($this->request->getPost('permissions'), true);

        $db = \Config\Database::connect();
        $builder = $db->table('role_permissions');

        $db->transStart(); 

        // 1. Bersihkan permission lama
        $builder->where('role_id', $role_id)->delete();

        // 2. Masukkan permission baru
        $insertData = [];
        foreach ($permissionsData as $module => $actions) {
            $insertData[] = [
                'role_id'     => $role_id,
                'module_name' => $module,
                'can_view'    => $actions['view'] ? 1 : 0,
                'can_create'  => $actions['create'] ? 1 : 0,
                'can_update'  => $actions['update'] ? 1 : 0,
                'can_delete'  => $actions['delete'] ? 1 : 0,
                'can_special' => $actions['special'] ? 1 : 0,
            ];
        }

        if (!empty($insertData)) {
            $builder->insertBatch($insertData);
        }
        
        // =======================================================
        // DI SINILAH TEMPAT AUDIT TRAIL YANG BENAR!
        // =======================================================
        $admin_id = session()->get('id'); 
        
        $auditData = [
            'user_id'     => $admin_id,
            'action'      => 'UPDATE_PERMISSION',
            'description' => "Memperbarui matriks hak akses untuk Role ID: " . $role_id,
            'ip_address'  => $this->request->getIPAddress(),
            'created_at'  => date('Y-m-d H:i:s')
        ];
        
        $db->table('audit_logs')->insert($auditData);
        // =======================================================

        $db->transComplete(); 

        if ($db->transStatus() === false) {
            return $this->response->setJSON([
                'status'  => 'error', 
                'message' => 'Gagal menyimpan perubahan ke database.'
            ]);
        }

        return $this->response->setJSON([
            'status'  => 'success', 
            'message' => 'Hak akses berhasil diperbarui dan dicatat di log.'
        ]);
    }

    public function addRole()
    {
        // (Isi fungsi addRole biarkan sama persis seperti kode Mas sebelumnya)
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
}