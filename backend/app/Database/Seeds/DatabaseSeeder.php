<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        echo "Memulai proses Seeding Massal...\n";

        // ==========================================
        // 1. MASTER SISTEM & HAK AKSES (PALING INDUK)
        // ==========================================
        $this->call('RolesSeeder'); // Bikin jabatannya dulu (Admin, Guru, dll)
        echo "-> RolesSeeder selesai.\n";

        $this->call('UsersSeeder'); // Baru bikin orangnya dan tempel jabatannya
        echo "-> UsersSeeder selesai.\n";

        // ==========================================
        // 2. MASTER REFERENSI QURAN
        // ==========================================
        $this->call('RefJuzSeeder');
        echo "-> RefJuzSeeder selesai.\n";

        $this->call('RefSurahSeeder');
        echo "-> RefSurahSeeder selesai.\n";

        // ==========================================
        // 3. MASTER AKADEMIK
        // ==========================================
        $this->call('TahunAjaranSeeder');
        echo "-> TahunAjaranSeeder selesai.\n";

        $this->call('MataPelajaranSeeder');
        echo "-> MataPelajaranSeeder selesai.\n";

        // ==========================================
        // 4. MASTER WILAYAH (URUTAN HIERARKI)
        // ==========================================
        $this->call('PropinsiSeeder');
        echo "-> PropinsiSeeder selesai.\n";

        $this->call('KabupatenSeeder');
        echo "-> KabupatenSeeder selesai.\n";

        $this->call('KecamatanSeeder');
        echo "-> KecamatanSeeder selesai.\n";

        $this->call('DesaSeeder');
        echo "-> DesaSeeder selesai.\n";

        // ==========================================
        // 5. MASTER INSTANSI & SDM
        // ==========================================
        $this->call('SekolahSeeder');
        echo "-> SekolahSeeder selesai.\n";

        $this->call('GuruSeeder');
        echo "-> GuruSeeder selesai.\n";

        // ==========================================
        // 6. MASTER TRANSAKSIONAL AWAL
        // ==========================================
        $this->call('RombelSeeder');
        echo "-> KelasSeeder selesai.\n";

        echo "==============================================\n";
        echo "SELESAI! 🚀 Semua data berhasil disemai dengan urutan hierarki yang SEMPURNA!\n";
    }
}