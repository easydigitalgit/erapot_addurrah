<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class RemoveUniqueFromOrangtuaWali extends Migration
{
    public function up()
    {
        $db = \Config\Database::connect();
        
        $allIndexes = $db->query("SHOW INDEX FROM orangtua_wali")->getResultArray();
        
        $hasNonUniqueUserId = false;
        $uniqueIndexesToDrop = [];

        foreach ($allIndexes as $idx) {
            $kName = $idx['Key_name'];
            $col = $idx['Column_name'];
            $isUnique = ($idx['Non_unique'] == 0);

            if ($col === 'user_id' && !$isUnique) {
                $hasNonUniqueUserId = true;
            }

            if ($isUnique && $kName !== 'PRIMARY') {
                if ($col === 'user_id' || $col === 'email_ortu') {
                    $uniqueIndexesToDrop[$kName] = $col;
                }
            }
        }

        // 1. Tambah index non-unique untuk user_id jika belum ada
        if (!$hasNonUniqueUserId) {
            $db->query("ALTER TABLE orangtua_wali ADD INDEX idx_orangtua_wali_user_id (user_id)");
        }

        // 2. Hapus semua unique index pada user_id dan email_ortu
        foreach ($uniqueIndexesToDrop as $kName => $col) {
            $db->query("ALTER TABLE orangtua_wali DROP INDEX `{$kName}`");
        }
    }

    public function down()
    {
        // Tidak perlu rollback agar relasi data siswa saudara kandung tidak error
    }
}
