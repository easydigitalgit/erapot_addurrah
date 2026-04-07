<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DesaSeeder extends Seeder
{
    public function run()
    {
        $file = APPPATH . 'Database/Seeds/desa.sql';

        if (!file_exists($file)) {
            echo "File desa.sql tidak ditemukan!\n";
            return;
        }

        echo "Membaca file desa.sql (ini mungkin memakan waktu beberapa detik karena datanya masif)...\n";
        $this->db->table('desa')->truncate();

        $lines = file($file);
        $query = '';

        $this->db->disableForeignKeyChecks();

        foreach ($lines as $line) {
            $lineTrim = trim($line);

            if (strpos($lineTrim, '--') === 0 || strpos($lineTrim, '/*') === 0 || $lineTrim == '') {
                continue;
            }

            $query .= $line;

            if (substr($lineTrim, -1, 1) == ';') {
                // Hanya eksekusi perintah INSERT
                if (stripos(trim($query), 'INSERT') === 0) {
                    $this->db->query($query);
                }
                $query = '';
            }
        }

        $this->db->enableForeignKeyChecks();

        echo "LUAR BIASA! 🚀 Berhasil meng-import puluhan ribu data Desa!\n";
    }
}