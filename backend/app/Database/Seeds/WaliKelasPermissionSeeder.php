<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class WaliKelasPermissionSeeder extends Seeder
{
    public function run()
    {
        $db = \Config\Database::connect();
        $role_id = 3; // Wali Kelas
        $module = 'wali_kelas';

        $data = [
            'role_id'     => $role_id,
            'module_name' => $module,
            'can_view'    => 1,
            'can_create'  => 1,
            'can_update'  => 1,
            'can_delete'  => 1,
            'can_special' => 1
        ];

        // Check if exists
        $exists = $db->table('role_permissions')
                     ->where('role_id', $role_id)
                     ->where('module_name', $module)
                     ->get()->getRow();

        if ($exists) {
            $db->table('role_permissions')
               ->where('id', $exists->id)
               ->update($data);
        } else {
            $db->table('role_permissions')->insert($data);
        }
    }
}
