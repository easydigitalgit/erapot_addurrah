<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class PropinsiSeeder extends Seeder
{
    public function run()
    {
        // Data 34 Provinsi sesuai screenshot (Kode, Nama, Lat, Long)
        // Saya pisahkan lat dan long agar lebih rapi, nanti koordinat digabung otomatis
        $rawData = [
            ['11', 'ACEH', '4.695135', '96.7493993'],
            ['12', 'SUMATERA UTARA', '2.1153547', '99.54509739999999'],
            ['13', 'SUMATERA BARAT', '-0.7399397', '100.8000051'],
            ['14', 'RIAU', '0.2933469', '101.7068294'],
            ['15', 'JAMBI', '-1.6101229', '103.6131203'],
            ['16', 'SUMATERA SELATAN', '-3.3194374', '103.914399'],
            ['17', 'BENGKULU', '-3.792845099999999', '102.2607641'],
            ['18', 'LAMPUNG', '-4.5585849', '105.4068079'],
            ['19', 'KEPULAUAN BANGKA BELITUNG', '-2.7410513', '106.4405872'],
            ['21', 'KEPULAUAN RIAU', '3.9456514', '108.1428669'],
            ['31', 'DKI JAKARTA', '-6.2087634', '106.845599'],
            ['32', 'JAWA BARAT', '-7.090910999999999', '107.668887'],
            ['33', 'JAWA TENGAH', '-7.150975', '110.1402594'],
            ['34', 'DAERAH ISTIMEWA YOGYAKARTA', '-7.875384899999999', '110.4262088'],
            ['35', 'JAWA TIMUR', '-7.5360639', '112.2384017'],
            ['36', 'BANTEN', '-6.4058172', '106.0640179'],
            ['51', 'BALI', '-8.3405389', '115.0919509'],
            ['52', 'NUSA TENGGARA BARAT', '-8.6529334', '117.3616476'],
            ['53', 'NUSA TENGGARA TIMUR', '-8.657381899999999', '121.0793705'],
            ['61', 'KALIMANTAN BARAT', '-0.2787808', '111.4752851'],
            ['62', 'KALIMANTAN TENGAH', '-1.6814878', '113.3823545'],
            ['63', 'KALIMANTAN SELATAN', '-3.0926415', '115.2837585'],
            ['64', 'KALIMANTAN TIMUR', '0.5386586', '116.419389'],
            ['65', 'KALIMANTAN UTARA', '3.0730929', '116.0413889'],
            ['71', 'SULAWESI UTARA', '0.6246932', '123.9750018'],
            ['72', 'SULAWESI TENGAH', '-1.4300254', '121.4456179'],
            ['73', 'SULAWESI SELATAN', '-3.6687994', '119.9740534'],
            ['74', 'SULAWESI TENGGARA', '-4.144909999999999', '122.174605'],
            ['75', 'GORONTALO', '0.5435441999999999', '123.0567693'],
            ['76', 'SULAWESI BARAT', '-2.8441371', '119.2320784'],
            ['81', 'MALUKU', '-3.2384616', '130.1452734'],
            ['82', 'MALUKU UTARA', '1.5709993', '127.8087693'],
            ['91', 'PAPUA', '-4.269928', '138.0803529'],
            ['92', 'PAPUA BARAT', '-1.3361154', '133.1747162'],
        ];

        $data = [];

        // Menyusun array agar persis dengan kolom database
        foreach ($rawData as $row) {
            $data[] = [
                'kode'      => $row[0],
                'nama'      => $row[1],
                'koordinat' => $row[2] . ',' . $row[3], // Gabung Lat & Long
                'latitude'  => $row[2],
                'longitude' => $row[3],
            ];
        }

        // Opsional: Hapus/Kosongkan tabel sebelum diisi ulang (agar tidak duplicate)
        $this->db->table('propinsi')->truncate();

        // Insert massal 34 Provinsi
        $this->db->table('propinsi')->insertBatch($data);

        echo "Selesai! 34 Data Provinsi beserta titik koordinatnya berhasil ditambahkan ke tabel propinsi!\n";
    }
}