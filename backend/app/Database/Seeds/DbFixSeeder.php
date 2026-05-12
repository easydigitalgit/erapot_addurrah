<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DbFixSeeder extends Seeder
{
    public function run()
    {
        $this->db->query("ALTER TABLE rekap_absensi ADD COLUMN hadir INT DEFAULT 0 AFTER tahun_ajaran_id");
        $this->db->query("ALTER TABLE rekap_absensi ADD COLUMN semester VARCHAR(20) AFTER hadir");
    }
}
