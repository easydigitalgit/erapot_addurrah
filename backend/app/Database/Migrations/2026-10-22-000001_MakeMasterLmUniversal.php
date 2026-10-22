<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class MakeMasterLmUniversal extends Migration
{
    public function up()
    {
        $db = \Config\Database::connect();

        if ($db->tableExists('master_lm')) {
            // 1. Pastikan kolom kategori ada
            if (!$db->fieldExists('kategori', 'master_lm')) {
                $db->query("ALTER TABLE master_lm ADD COLUMN kategori ENUM('Tengah', 'Akhir') DEFAULT 'Akhir' AFTER semester");
            }

            // 2. Ubah tahun_ajaran_id agar NULLable untuk mendukung konsep Master LM Universal
            $field = $db->query("SHOW COLUMNS FROM master_lm LIKE 'tahun_ajaran_id'")->getRowArray();
            if ($field && strtoupper($field['Null']) === 'NO') {
                $db->query("ALTER TABLE master_lm MODIFY COLUMN tahun_ajaran_id INT NULL DEFAULT NULL");
            }
        }
    }

    public function down()
    {
        // Tidak perlu rollback agar relasi master_lm tetap universal
    }
}
