<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class KecamatanSeeder extends Seeder
{
    public function run()
    {
        $file = APPPATH . 'Database/Seeds/kecamatan.sql';

        if (!file_exists($file)) {
            echo "File kecamatan.sql tidak ditemukan!\n";
            return;
        }

        echo "Membaca file kecamatan.sql...\n";
        $this->db->table('kecamatan')->truncate();

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

        echo "SUPER! Berhasil meng-import data Kecamatan!\n";
    }
}