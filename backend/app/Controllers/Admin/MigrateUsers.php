<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\Admin\UserModel; // Sesuaikan jika namespace Model Anda berbeda

class MigrateUsers extends BaseController
{
    public function index()
    {
        $db = \Config\Database::connect();
        $userModel = new UserModel();
        
        echo "<h1>Mulai Proses Sinkronisasi Akun...</h1>";
        echo "<pre>";

        $inserted = 0;
        $skipped  = 0;

        // =====================================================================
        // 1. MIGRASI DATA GURU & TENDIK (Role ID = 2)
        // =====================================================================
        // Ambil guru yang belum punya user_id (akun)
        $gurus = $db->table('guru_tendik')->where('user_id', NULL)->get()->getResultArray();
        
        foreach ($gurus as $guru) {
            // Tentukan Username (Prioritas: NUPTK -> NIK -> Nama Tanpa Spasi)
            $username = !empty($guru['nuptk']) ? $guru['nuptk'] : 
                        (!empty($guru['nik']) ? $guru['nik'] : 
                        strtolower(str_replace(' ', '', $guru['nama_lengkap'])));
            
            // Email (Jika kosong, buat email dummy)
            $email = !empty($guru['email']) ? $guru['email'] : $username . '@guru.sekolah.id';

            // Cek apakah username/email sudah ada di users
            if ($userModel->where('username', $username)->orWhere('email', $email)->first()) {
                echo "[SKIP] Guru {$guru['nama_lengkap']} (Username/Email sudah ada)\n";
                $skipped++;
                continue;
            }

            // Data Akun Baru
            $userData = [
                'username'  => $username,
                'email'     => $email,
                'password'  => password_hash('guru123', PASSWORD_DEFAULT), // Password Default: guru123
                'role_id'   => 2, // ID Guru
                'is_active' => 1,
            ];

            // Insert ke Users
            $userModel->insert($userData);
            $newUserId = $userModel->getInsertID();

            // Update tabel Guru dengan User ID baru (Link Akun)
            $db->table('guru_tendik')->where('id', $guru['id'])->update(['user_id' => $newUserId]);
            
            echo "[OK] Guru {$guru['nama_lengkap']} -> User ID: $newUserId\n";
            $inserted++;
        }

        // =====================================================================
        // 2. MIGRASI DATA SISWA (Role ID = 4)
        // =====================================================================
        $siswas = $db->table('siswa')->where('user_id', NULL)->get()->getResultArray();

        foreach ($siswas as $siswa) {
            // Username: NIS atau NISN
            $username = !empty($siswa['nis']) ? $siswa['nis'] : 
                        (!empty($siswa['nisn']) ? $siswa['nisn'] : 'siswa'.$siswa['id']);
            
            $email = $username . '@siswa.sekolah.id'; // Email dummy siswa

            if ($userModel->where('username', $username)->first()) {
                echo "[SKIP] Siswa {$siswa['nama_lengkap']} (Username ada)\n";
                $skipped++;
                continue;
            }

            $userData = [
                'username'  => $username,
                'email'     => $email,
                'password'  => password_hash('siswa123', PASSWORD_DEFAULT), // Password Default: siswa123
                'role_id'   => 4, // ID Siswa
                'is_active' => 1,
            ];

            $userModel->insert($userData);
            $newUserId = $userModel->getInsertID();

            $db->table('siswa')->where('id', $siswa['id'])->update(['user_id' => $newUserId]);
            
            echo "[OK] Siswa {$siswa['nama_lengkap']} -> User ID: $newUserId\n";
            $inserted++;
        }

        // =====================================================================
        // 3. MIGRASI DATA ORANG TUA (Role ID = 5)
        // =====================================================================
        // Cek dulu apakah tabel orangtua_wali ada kolom email, kalau tidak pakai nama
        $ortus = $db->table('orangtua_wali')->where('user_id', NULL)->get()->getResultArray();
            
        foreach ($ortus as $ortu) {
            // Username: Nama Ayah (dibersihkan) atau ID
            $namaClean = isset($ortu['nama_ayah']) ? strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $ortu['nama_ayah'])) : 'ortu'.$ortu['id'];
            $username = $namaClean . $ortu['id']; // Tambah ID biar unik
            
            // Cek jika ada kolom email di tabel ortu
            $emailAsli = isset($ortu['email']) ? $ortu['email'] : null;
            $email = $emailAsli ? $emailAsli : $username . '@ortu.sekolah.id';

            if ($userModel->where('username', $username)->first()) {
                continue;
            }

            $userData = [
                'username'  => $username,
                'email'     => $email,
                'password'  => password_hash('ortu123', PASSWORD_DEFAULT), // Password Default: ortu123
                'role_id'   => 5, // ID Ortu
                'is_active' => 1,
            ];

            $userModel->insert($userData);
            $newUserId = $userModel->getInsertID();

            $db->table('orangtua_wali')->where('id', $ortu['id'])->update(['user_id' => $newUserId]);
            
            echo "[OK] Ortu ID {$ortu['id']} -> User ID: $newUserId\n";
            $inserted++;
        }

        echo "</pre>";
        echo "<h2 style='color:green'>Selesai! Berhasil membuat $inserted akun baru.</h2>";
        echo "<a href='".base_url('admin/users')."'>Kembali ke Daftar User</a>";
    }
}