<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class KabupatenSeeder extends Seeder
{
    public function run()
    {
        $file = APPPATH . 'Database/Seeds/kabupaten.sql';

        if (!file_exists($file)) {
            echo "File kabupaten.sql tidak ditemukan!\n";
            return;
        }

        echo "Membaca file kabupaten.sql...\n";
        $this->db->table('kabupaten')->truncate();

        $lines = file($file);
        $query = '';

        foreach ($lines as $line) {
            $lineTrim = trim($line);

            // Abaikan komentar dan baris kosong
            if (strpos($lineTrim, '--') === 0 || strpos($lineTrim, '/*') === 0 || $lineTrim == '') {
                continue;
            }

            $query .= $line;

            // Jika menemukan titik koma (tanda query berakhir)
            if (substr($lineTrim, -1, 1) == ';') {
                
                // JURUS PAMUNGKAS: Hanya eksekusi jika query-nya adalah perintah INSERT
                if (stripos(trim($query), 'INSERT') === 0) {
                    $this->db->query($query);
                }
                
                $query = ''; // Kosongkan lagi penampung query-nya
            }
        }

        echo "Data Kabupaten berhasil disemai!\n";
    }
}