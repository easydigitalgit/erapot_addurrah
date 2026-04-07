<?php

if (!function_exists('has_permission')) {
    /**
     * Mengecek apakah user yang sedang login memiliki hak akses tertentu.
     *
     * @param string $module Nama modul (contoh: 'penilaian', 'akademik', 'pengguna')
     * @param string $action Jenis aksi (contoh: 'view', 'create', 'update', 'delete', 'special')
     * @return bool True jika diizinkan, False jika tidak.
     */
    function has_permission(string $module, string $action = 'view'): bool
    {
        $session = session();
        $role_id = $session->get('role_id');

        // 1. Jika belum login atau tidak punya role, tolak!
        if (!$role_id) {
            return false;
        }

        // 2. JALUR VIP: Super Admin (Role ID 1) selalu diizinkan melakukan apapun!
        if ($role_id == 1) {
            return true;
        }

        // 3. Cek Cache Session untuk menghindari Query Database berulang kali di satu halaman
        // Ini sangat penting untuk kecepatan (performance) web lu!
        $cacheKey = 'rbac_' . $role_id . '_' . $module;
        if (!$session->has($cacheKey)) {
            $db = \Config\Database::connect();
            $permission = $db->table('role_permissions')
                             ->where('role_id', $role_id)
                             ->where('module_name', $module)
                             ->get()
                             ->getRowArray();
                             
            // Simpan hasil query ke session cache
            $session->set($cacheKey, $permission ? $permission : 'none');
        }

        $cachedPermission = $session->get($cacheKey);

        // 4. Jika modul tidak ditemukan di tabel role_permissions, tolak!
        if ($cachedPermission === 'none' || empty($cachedPermission)) {
            return false;
        }

        // 5. Evaluasi berdasarkan action yang diminta
        switch ($action) {
            case 'view':    return ($cachedPermission['can_view'] == 1);
            case 'create':  return ($cachedPermission['can_create'] == 1);
            case 'update':  return ($cachedPermission['can_update'] == 1);
            case 'delete':  return ($cachedPermission['can_delete'] == 1);
            case 'special': return ($cachedPermission['can_special'] == 1);
            default:        return false;
        }
    }
}