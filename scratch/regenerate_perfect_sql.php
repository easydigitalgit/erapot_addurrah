<?php
require 'backend/vendor/autoload.php';

$spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load('docs/Data Siswa Dapodik Perkelas TA 2026-2027.xlsx');

// Mappings without the duplicate ID 42 (because Fathir Al Ghifary is already ID 44)
$mappings = [
    // --- KELAS VII ---
    77 => [ // VII AMAZONITE
        540 => ['sheet' => 'VII', 'row' => 15, 'jk' => 'L'], // EMERY ALDRIC MUSYAFFA
        550 => ['sheet' => 'VII', 'row' => 25, 'jk' => 'L'], // Muhammad Nadhif Al Ghifari
        551 => ['sheet' => 'VII', 'row' => 26, 'jk' => 'L']  // MUHAMMAD NASRUL HAMZAH
    ],
    78 => [ // VII OPAL
        451 => ['sheet' => 'VII', 'row' => 42, 'jk' => 'L'], // FABIAN ARYA BIMANTARA
        453 => ['sheet' => 'VII', 'row' => 46, 'jk' => 'L'], // FATIH ALMANAR HAFIZ
        461 => ['sheet' => 'VII', 'row' => 48, 'jk' => 'L']  // JAFIN SYAHZAN
    ],
    80 => [ // VII CARNELIAN
        439 => ['sheet' => 'VII', 'row' => 100, 'jk' => 'P'], // ASHIKA ZALIKA ZAHRA TARIGAN
        440 => ['sheet' => 'VII', 'row' => 101, 'jk' => 'P'], // Ashillah Ratu Balqis
        454 => ['sheet' => 'VII', 'row' => 113, 'jk' => 'P']  // KHAIRIN NAFISAH LUBIS
    ],
    81 => [ // VII RUBY
        629 => ['sheet' => 'VII', 'row' => 158, 'jk' => 'P'], // PUAN ZYA AZ-ZHARA
        630 => ['sheet' => 'VII', 'row' => 159, 'jk' => 'P']  // Qaisara Talita Zahran
    ],

    // --- KELAS VIII ---
    72 => [ // VIII GRANIT
        6  => ['sheet' => 'VIII', 'row' => 12, 'jk' => 'L'], // ARKAAN FAIZ
        29 => ['sheet' => 'VIII', 'row' => 35, 'jk' => 'L']  // RIVALDO ZEIN MASIHOR
    ],
    73 => [ // VIII TOPAZ (ID 42 dihilangkan karena Fathir Al Ghifary sudah aktif di ID 44)
        36 => ['sheet' => 'VIII', 'row' => 46, 'jk' => 'L'], // Andrian Saputra
        39 => ['sheet' => 'VIII', 'row' => 49, 'jk' => 'L'], // ATHALLAH ALSULTHAN NAINGGOLAN
        40 => ['sheet' => 'VIII', 'row' => 52, 'jk' => 'L'], // FADHIL RAJABI
        46 => ['sheet' => 'VIII', 'row' => 57, 'jk' => 'L'], // ILHAM HAFIZ
        51 => ['sheet' => 'VIII', 'row' => 61, 'jk' => 'L'], // Mhd Justicio Pradipto
        52 => ['sheet' => 'VIII', 'row' => 64, 'jk' => 'L'], // MUHAMMAD ALIF YUSUF ILHAM
        54 => ['sheet' => 'VIII', 'row' => 68, 'jk' => 'L']  // Muhammad Raihan Rasyiddin
    ],
    74 => [ // VIII INTAN
        68 => ['sheet' => 'VIII', 'row' => 80, 'jk' => 'P'], // AURELIA MUTIARA RAMDANI
        74 => ['sheet' => 'VIII', 'row' => 86, 'jk' => 'P']  // Khanza Aya Diyya
    ],
    75 => [ // VIII KYANITE
        91 => ['sheet' => 'VIII', 'row' => 105, 'jk' => 'P'], // AFIQAH AZZAHRA
        94 => ['sheet' => 'VIII', 'row' => 108, 'jk' => 'P'], // Asyifa Naurah Feirus
        99 => ['sheet' => 'VIII', 'row' => 114, 'jk' => 'P']  // KANZA LATISHA
    ],

    // --- KELAS IX ---
    67 => [ // IX FAIRUZ
        164 => ['sheet' => 'IX', 'row' => 8,  'jk' => 'L'], // AULIA ARBIE
        166 => ['sheet' => 'IX', 'row' => 10, 'jk' => 'L'], // DAFFA AHZA DANISH
        176 => ['sheet' => 'IX', 'row' => 20, 'jk' => 'L'], // MUHAMMAD AZKAR
        187 => ['sheet' => 'IX', 'row' => 31, 'jk' => 'L']  // RIFKI ZULKARNAIN NASUTION
    ],
    68 => [ // IX EMERALD
        137 => ['sheet' => 'IX', 'row' => 43, 'jk' => 'L']  // ARJUSE FARISI
    ],
    69 => [ // IX BIDURI
        208 => ['sheet' => 'IX', 'row' => 89, 'jk' => 'P'], // Keysha Yuna Putri
        209 => ['sheet' => 'IX', 'row' => 90, 'jk' => 'P'], // KHARISMA AZURA
        211 => ['sheet' => 'IX', 'row' => 92, 'jk' => 'P']  // MALAIKA ZAHFIRA NASUTION
    ],
    70 => [ // IX CRYSTAL
        222 => ['sheet' => 'IX', 'row' => 107, 'jk' => 'P'], // AISADINA YASMINE AZWANA
        233 => ['sheet' => 'IX', 'row' => 117, 'jk' => 'P'], // LUTHFIYAH HANIFAH LUBIS
        243 => ['sheet' => 'IX', 'row' => 127, 'jk' => 'P'], // SAKINAH BALQIS SEPTIKA
        246 => ['sheet' => 'IX', 'row' => 130, 'jk' => 'P']  // SYAKIRA ALPIQA LUBIS
    ],
    71 => [ // IX DIAMOND
        256 => ['sheet' => 'IX', 'row' => 144, 'jk' => 'P'], // Ayra Raeesa Febia
        267 => ['sheet' => 'IX', 'row' => 155, 'jk' => 'P']  // MIFTHA ACHZUHRA
    ]
];

$sqlSiswa = [];
$sqlOrtu = [];

foreach ($mappings as $rombel_id => $students) {
    foreach ($students as $id => $info) {
        $sheet = $spreadsheet->getSheetByName($info['sheet']);
        $r = $info['row'];
        $jk = $info['jk'];

        $nama = addslashes(trim((string)$sheet->getCell('B' . $r)->getValue()));
        $nis = addslashes(trim((string)$sheet->getCell('C' . $r)->getValue()));
        $nisn = addslashes(trim((string)$sheet->getCell('D' . $r)->getValue()));
        $tempat_lahir = addslashes(trim((string)$sheet->getCell('E' . $r)->getValue()));

        $tglRaw = $sheet->getCell('F' . $r)->getValue();
        if (is_numeric($tglRaw)) {
            $tgl_lahir = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($tglRaw)->format('Y-m-d');
        } else {
            $tgl_lahir = date('Y-m-d', strtotime((string)$tglRaw));
        }

        $nik = addslashes(trim((string)$sheet->getCell('G' . $r)->getValue()));
        $alamat = addslashes(trim((string)$sheet->getCell('H' . $r)->getValue()));
        $dusun = addslashes(trim((string)$sheet->getCell('I' . $r)->getValue()));
        $kelurahan = addslashes(trim((string)$sheet->getCell('J' . $r)->getValue()));
        $kecamatan = addslashes(trim((string)$sheet->getCell('K' . $r)->getValue()));
        $kode_pos = addslashes(trim((string)$sheet->getCell('L' . $r)->getValue()));
        $jenis_tinggal = addslashes(trim((string)$sheet->getCell('M' . $r)->getValue()));
        $transportasi = addslashes(trim((string)$sheet->getCell('N' . $r)->getValue()));
        $hp = addslashes(trim((string)$sheet->getCell('O' . $r)->getValue()));

        $sekolah_asal = addslashes(trim((string)$sheet->getCell('AI' . $r)->getValue()));
        $anak_ke = (int)$sheet->getCell('AJ' . $r)->getValue() ?: 'NULL';
        $saudara = (int)$sheet->getCell('AK' . $r)->getValue() ?: 'NULL';

        $sqlSiswa[] = "($id, $rombel_id, '$nis', '$nisn', '$nik', '$nama', '$jk', '$tempat_lahir', '$tgl_lahir', 'Islam', '$alamat', '$dusun', '$kelurahan', '$kecamatan', '$kode_pos', '$jenis_tinggal', '$transportasi', '$hp', '$sekolah_asal', 'Aktif', $anak_ke, $saudara)";

        // Data Orang Tua
        $nama_ayah = addslashes(trim((string)$sheet->getCell('P' . $r)->getValue()));
        $thn_ayah = addslashes(trim((string)$sheet->getCell('Q' . $r)->getValue()));
        $pend_ayah = addslashes(trim((string)$sheet->getCell('R' . $r)->getValue()));
        $pek_ayah = addslashes(trim((string)$sheet->getCell('S' . $r)->getValue()));
        $gaji_ayah = addslashes(trim((string)$sheet->getCell('T' . $r)->getValue()));
        $gaji_ayah = str_replace(['–', '—'], '-', $gaji_ayah);
        $nik_ayah = addslashes(trim((string)$sheet->getCell('U' . $r)->getValue()));

        $nama_ibu = addslashes(trim((string)$sheet->getCell('V' . $r)->getValue()));
        $thn_ibu = addslashes(trim((string)$sheet->getCell('W' . $r)->getValue()));
        $pend_ibu = addslashes(trim((string)$sheet->getCell('X' . $r)->getValue()));
        $pek_ibu = addslashes(trim((string)$sheet->getCell('Y' . $r)->getValue()));
        $gaji_ibu = addslashes(trim((string)$sheet->getCell('Z' . $r)->getValue()));
        $gaji_ibu = str_replace(['–', '—'], '-', $gaji_ibu);
        $nik_ibu = addslashes(trim((string)$sheet->getCell('AA' . $r)->getValue()));

        $nama_wali = addslashes(trim((string)$sheet->getCell('AB' . $r)->getValue()));
        $thn_wali = addslashes(trim((string)$sheet->getCell('AC' . $r)->getValue()));
        $pend_wali = addslashes(trim((string)$sheet->getCell('AD' . $r)->getValue()));
        $pek_wali = addslashes(trim((string)$sheet->getCell('AE' . $r)->getValue()));
        $gaji_wali = addslashes(trim((string)$sheet->getCell('AF' . $r)->getValue()));
        $gaji_wali = str_replace(['–', '—'], '-', $gaji_wali);
        $nik_wali = addslashes(trim((string)$sheet->getCell('AG' . $r)->getValue()));

        $sqlOrtu[] = "($id, '$nama_ayah', '$thn_ayah', '$pend_ayah', '$pek_ayah', '$gaji_ayah', '$nik_ayah', '$nama_ibu', '$thn_ibu', '$pend_ibu', '$pek_ibu', '$gaji_ibu', '$nik_ibu', '$nama_wali', '$thn_wali', '$pend_wali', '$pek_wali', '$gaji_wali', '$nik_wali')";
    }
}

$fullSql = "-- ==========================================================================\n";
$fullSql .= "-- SCRIPT RESTORE MASSAL 39 SISWA DAPODIK (15 KELAS) TA 2026/2027\n";
$fullSql .= "-- Sumber: Data Siswa Dapodik Perkelas TA 2026-2027.xlsx\n";
$fullSql .= "-- Target: Tabel `siswa` dan `orangtua_wali` (Database: raporsmpit)\n";
$fullSql .= "-- Catatan: Menggunakan INSERT INTO ... ON DUPLICATE KEY UPDATE & SET FOREIGN_KEY_CHECKS=0\n";
$fullSql .= "-- ==========================================================================\n\n";
$fullSql .= "SET FOREIGN_KEY_CHECKS = 0;\n\n";

$fullSql .= "-- 1. BERSIHKAN RECORD DANGLING ANGGOTA_ROMBEL\n";
$fullSql .= "DELETE FROM anggota_rombel WHERE id IN (747, 749, 615);\n\n";

$fullSql .= "-- 2. RESTORE DATA SISWA KE TABEL SISWA (39 SISWA)\n";
$fullSql .= "INSERT INTO siswa (id, rombel_id, nis, nisn, nik, nama_lengkap, jenis_kelamin, tempat_lahir, tanggal_lahir, agama, alamat_siswa, dusun, kelurahan, kecamatan, kode_pos, jenis_tinggal, alat_transportasi, no_hp, asal_sekolah, status_siswa, anak_ke, jml_saudara_kandung) VALUES\n";
$fullSql .= implode(",\n", $sqlSiswa);
$fullSql .= "\nON DUPLICATE KEY UPDATE rombel_id=VALUES(rombel_id), status_siswa=VALUES(status_siswa), nama_lengkap=VALUES(nama_lengkap), nis=VALUES(nis), nisn=VALUES(nisn), nik=VALUES(nik);\n\n";

$fullSql .= "-- 3. RESTORE DATA ORANG TUA KE TABEL ORANGTUA_WALI\n";
$fullSql .= "INSERT INTO orangtua_wali (siswa_id, nama_ayah, tahun_lahir_ayah, pendidikan_ayah, pekerjaan_ayah, penghasilan_ayah, nik_ayah, nama_ibu, tahun_lahir_ibu, pendidikan_ibu, pekerjaan_ibu, penghasilan_ibu, nik_ibu, nama_wali, tahun_lahir_wali, pendidikan_wali, pekerjaan_wali, penghasilan_wali, nik_wali) VALUES\n";
$fullSql .= implode(",\n", $sqlOrtu);
$fullSql .= "\nON DUPLICATE KEY UPDATE nama_ayah=VALUES(nama_ayah), nama_ibu=VALUES(nama_ibu);\n\n";

$fullSql .= "SET FOREIGN_KEY_CHECKS = 1;\n";

file_put_contents('docs/perbaikan_semua_kelas_dapodik_2026_2027.sql', $fullSql);
echo "Successfully generated clean docs/perbaikan_semua_kelas_dapodik_2026_2027.sql with " . count($sqlSiswa) . " students!\n";
