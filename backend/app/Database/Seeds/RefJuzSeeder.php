<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class RefJuzSeeder extends Seeder
{
    public function run()
    {
        // Data lengkap dan detail sesuai standar Al-Qur'an dan screenshot database Anda
        $data = [
            ['nama_juz' => 'Juz 1', 'keterangan' => 'Al-Fatihah 1 - Al-Baqarah 141', 'mulai_surah_id' => 1, 'sampai_surah_id' => 2],
            ['nama_juz' => 'Juz 2', 'keterangan' => 'Al-Baqarah 142 - Al-Baqarah 252', 'mulai_surah_id' => 2, 'sampai_surah_id' => 2],
            ['nama_juz' => 'Juz 3', 'keterangan' => 'Al-Baqarah 253 - Ali Imran 92', 'mulai_surah_id' => 2, 'sampai_surah_id' => 3],
            ['nama_juz' => 'Juz 4', 'keterangan' => 'Ali Imran 93 - An-Nisa 23', 'mulai_surah_id' => 3, 'sampai_surah_id' => 4],
            ['nama_juz' => 'Juz 5', 'keterangan' => 'An-Nisa 24 - An-Nisa 147', 'mulai_surah_id' => 4, 'sampai_surah_id' => 4],
            ['nama_juz' => 'Juz 6', 'keterangan' => 'An-Nisa 148 - Al-Maidah 81', 'mulai_surah_id' => 4, 'sampai_surah_id' => 5],
            ['nama_juz' => 'Juz 7', 'keterangan' => 'Al-Maidah 82 - Al-Anam 110', 'mulai_surah_id' => 5, 'sampai_surah_id' => 6],
            ['nama_juz' => 'Juz 8', 'keterangan' => 'Al-Anam 111 - Al-Araf 87', 'mulai_surah_id' => 6, 'sampai_surah_id' => 7],
            ['nama_juz' => 'Juz 9', 'keterangan' => 'Al-Araf 88 - Al-Anfal 40', 'mulai_surah_id' => 7, 'sampai_surah_id' => 8],
            ['nama_juz' => 'Juz 10', 'keterangan' => 'Al-Anfal 41 - At-Taubah 92', 'mulai_surah_id' => 8, 'sampai_surah_id' => 9],
            ['nama_juz' => 'Juz 11', 'keterangan' => 'At-Taubah 93 - Hud 5', 'mulai_surah_id' => 9, 'sampai_surah_id' => 11],
            ['nama_juz' => 'Juz 12', 'keterangan' => 'Hud 6 - Yusuf 52', 'mulai_surah_id' => 11, 'sampai_surah_id' => 12],
            ['nama_juz' => 'Juz 13', 'keterangan' => 'Yusuf 53 - Ibrahim 52', 'mulai_surah_id' => 12, 'sampai_surah_id' => 14],
            ['nama_juz' => 'Juz 14', 'keterangan' => 'Al-Hijr 1 - An-Nahl 128', 'mulai_surah_id' => 15, 'sampai_surah_id' => 16],
            ['nama_juz' => 'Juz 15', 'keterangan' => 'Al-Isra 1 - Al-Kahfi 74', 'mulai_surah_id' => 17, 'sampai_surah_id' => 18],
            ['nama_juz' => 'Juz 16', 'keterangan' => 'Al-Kahfi 75 - Taha 135', 'mulai_surah_id' => 18, 'sampai_surah_id' => 20],
            ['nama_juz' => 'Juz 17', 'keterangan' => 'Al-Anbiya 1 - Al-Hajj 78', 'mulai_surah_id' => 21, 'sampai_surah_id' => 22],
            ['nama_juz' => 'Juz 18', 'keterangan' => 'Al-Muminun 1 - Al-Furqan 20', 'mulai_surah_id' => 23, 'sampai_surah_id' => 25],
            ['nama_juz' => 'Juz 19', 'keterangan' => 'Al-Furqan 21 - An-Naml 55', 'mulai_surah_id' => 25, 'sampai_surah_id' => 27],
            ['nama_juz' => 'Juz 20', 'keterangan' => 'An-Naml 56 - Al-Ankabut 45', 'mulai_surah_id' => 27, 'sampai_surah_id' => 29],
            ['nama_juz' => 'Juz 21', 'keterangan' => 'Al-Ankabut 46 - Al-Ahzab 30', 'mulai_surah_id' => 29, 'sampai_surah_id' => 33],
            ['nama_juz' => 'Juz 22', 'keterangan' => 'Al-Ahzab 31 - Yasin 27', 'mulai_surah_id' => 33, 'sampai_surah_id' => 36],
            ['nama_juz' => 'Juz 23', 'keterangan' => 'Yasin 28 - Az-Zumar 31', 'mulai_surah_id' => 36, 'sampai_surah_id' => 39],
            ['nama_juz' => 'Juz 24', 'keterangan' => 'Az-Zumar 32 - Fussilat 46', 'mulai_surah_id' => 39, 'sampai_surah_id' => 41],
            ['nama_juz' => 'Juz 25', 'keterangan' => 'Fussilat 47 - Al-Jasiyah 37', 'mulai_surah_id' => 41, 'sampai_surah_id' => 45],
            ['nama_juz' => 'Juz 26', 'keterangan' => 'Al-Ahqaf 1 - Az-Zariyat 30', 'mulai_surah_id' => 46, 'sampai_surah_id' => 51],
            ['nama_juz' => 'Juz 27', 'keterangan' => 'Az-Zariyat 31 - Al-Hadid 29', 'mulai_surah_id' => 51, 'sampai_surah_id' => 57],
            ['nama_juz' => 'Juz 28', 'keterangan' => 'Al-Mujadilah 1 - At-Tahrim 24', 'mulai_surah_id' => 58, 'sampai_surah_id' => 66],
            ['nama_juz' => 'Juz 29', 'keterangan' => 'Al-Mulk 1 - Al-Mursalat 50', 'mulai_surah_id' => 67, 'sampai_surah_id' => 77],
            ['nama_juz' => 'Juz 30', 'keterangan' => 'An-Naba 1 - An-Nas 6', 'mulai_surah_id' => 78, 'sampai_surah_id' => 114],
        ];

        // kita "sapu bersih" (truncate) dulu tabelnya agar ID-nya kembali mulai dari 1 dan tidak ada data dobel/berantakan.
        $this->db->table('ref_juz')->truncate();

        // Setelah bersih, masukkan 30 data yang benar di atas secara massal
        $this->db->table('ref_juz')->insertBatch($data);

        echo "Selesai! 30 Data Juz beserta keterangan dan mapping Surahnya berhasil di-generate!\n";
    }
}