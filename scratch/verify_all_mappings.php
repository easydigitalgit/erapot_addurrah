<?php
require 'backend/vendor/autoload.php';

$spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load('docs/Data Siswa Dapodik Perkelas TA 2026-2027.xlsx');

$mappings = [
    // Rombel 77: VII AMAZONITE
    77 => [
        540 => 'EMERY ALDRIC MUSYAFFA',
        550 => 'Muhammad Nadhif Al Ghifari',
        551 => 'MUHAMMAD NASRUL HAMZAH'
    ],
    // Rombel 78: VII OPAL
    78 => [
        451 => 'FABIAN ARYA BIMANTARA',
        453 => 'FATIH ALMANAR HAFIZ',
        461 => 'JAFIN SYAHZAN'
    ],
    // Rombel 80: VII CARNELIAN
    80 => [
        439 => 'ASHIKA ZALIKA ZAHRA TARIGAN',
        440 => 'Ashillah Ratu Balqis',
        454 => 'KHAIRIN NAFISAH LUBIS'
    ],
    // Rombel 81: VII RUBY
    81 => [
        629 => 'PUAN ZYA AZ-ZHARA',
        630 => 'Qaisara Talita Zahran'
    ],
    // Rombel 72: VIII GRANIT
    72 => [
        6  => 'ARKAAN FAIZ',
        29 => 'RIVALDO ZEIN MASIHOR'
    ],
    // Rombel 73: VIII TOPAZ
    73 => [
        36 => 'Andrian Saputra',
        39 => 'ATHALLAH ALSULTHAN NAINGGOLAN',
        40 => 'FADHIL RAJABI',
        42 => 'Fathir Al Ghifary Lesmana',
        46 => 'ILHAM HAFIZ',
        51 => 'Mhd Justicio Pradipto',
        52 => 'MUHAMMAD ALIF YUSUF ILHAM',
        54 => 'Muhammad Raihan Rasyiddin'
    ],
    // Rombel 74: VIII INTAN
    74 => [
        68 => 'AURELIA MUTIARA RAMDANI',
        74 => 'Khanza Aya Diyya'
    ],
    // Rombel 75: VIII KYANITE
    75 => [
        91 => 'AFIQAH AZZAHRA',
        94 => 'Asyifa Naurah Feirus',
        99 => 'KANZA LATISHA'
    ],
    // Rombel 67: IX FAIRUZ
    67 => [
        164 => 'AULIA ARBIE',
        166 => 'DAFFA AHZA DANISH',
        176 => 'MUHAMMAD AZKAR',
        187 => 'RIFKI ZULKARNAIN NASUTION'
    ],
    // Rombel 68: IX EMERALD
    68 => [
        137 => 'ARJUSE FARISI'
    ],
    // Rombel 69: IX BIDURI
    69 => [
        208 => 'Keysha Yuna Putri',
        209 => 'KHARISMA AZURA',
        211 => 'MALAIKA ZAHFIRA NASUTION'
    ],
    // Rombel 70: IX CRYSTAL
    70 => [
        222 => 'AISADINA YASMINE AZWANA',
        233 => 'LUTHFIYAH HANIFAH LUBIS',
        243 => 'SAKINAH BALQIS SEPTIKA',
        246 => 'SYAKIRA ALPIQA LUBIS'
    ],
    // Rombel 71: IX DIAMOND
    71 => [
        256 => 'Ayra Raeesa Febia',
        267 => 'MIFTHA ACHZUHRA'
    ]
];

echo "=== CHECKING MAPPINGS AGAINST DAPODIK ===\n";

$sheetNames = ['VII', 'VIII', 'IX'];
$dapodikByName = [];

foreach ($sheetNames as $sn) {
    $sheet = $spreadsheet->getSheetByName($sn);
    $highestRow = $sheet->getHighestDataRow();
    for ($r = 1; $r <= $highestRow; $r++) {
        $nama = trim((string)$sheet->getCell('B' . $r)->getValue());
        if ($nama && $nama !== 'Nama') {
            $dapodikByName[strtolower($nama)] = [
                'sheet' => $sn,
                'row' => $r,
                'nama' => $nama,
                'nipd' => trim((string)$sheet->getCell('C' . $r)->getValue()),
                'nisn' => trim((string)$sheet->getCell('D' . $r)->getValue()),
                'nik' => trim((string)$sheet->getCell('G' . $r)->getValue()),
                'tempat_lahir' => trim((string)$sheet->getCell('E' . $r)->getValue()),
                'tanggal_lahir' => $sheet->getCell('F' . $r)->getValue(),
                'alamat' => trim((string)$sheet->getCell('H' . $r)->getValue()),
                'dusun' => trim((string)$sheet->getCell('I' . $r)->getValue()),
                'kelurahan' => trim((string)$sheet->getCell('J' . $r)->getValue()),
                'kecamatan' => trim((string)$sheet->getCell('K' . $r)->getValue()),
                'kode_pos' => trim((string)$sheet->getCell('L' . $r)->getValue()),
                'jenis_tinggal' => trim((string)$sheet->getCell('M' . $r)->getValue()),
                'transportasi' => trim((string)$sheet->getCell('N' . $r)->getValue()),
                'hp' => trim((string)$sheet->getCell('O' . $r)->getValue()),
                'sekolah_asal' => trim((string)$sheet->getCell('AI' . $r)->getValue()),
                'anak_ke' => trim((string)$sheet->getCell('AJ' . $r)->getValue()),
                'saudara' => trim((string)$sheet->getCell('AK' . $r)->getValue()),
                // Ayah
                'nama_ayah' => trim((string)$sheet->getCell('P' . $r)->getValue()),
                'thn_ayah' => trim((string)$sheet->getCell('Q' . $r)->getValue()),
                'pend_ayah' => trim((string)$sheet->getCell('R' . $r)->getValue()),
                'pek_ayah' => trim((string)$sheet->getCell('S' . $r)->getValue()),
                'gaji_ayah' => trim((string)$sheet->getCell('T' . $r)->getValue()),
                'nik_ayah' => trim((string)$sheet->getCell('U' . $r)->getValue()),
                // Ibu
                'nama_ibu' => trim((string)$sheet->getCell('V' . $r)->getValue()),
                'thn_ibu' => trim((string)$sheet->getCell('W' . $r)->getValue()),
                'pend_ibu' => trim((string)$sheet->getCell('X' . $r)->getValue()),
                'pek_ibu' => trim((string)$sheet->getCell('Y' . $r)->getValue()),
                'gaji_ibu' => trim((string)$sheet->getCell('Z' . $r)->getValue()),
                'nik_ibu' => trim((string)$sheet->getCell('AA' . $r)->getValue()),
                // Wali
                'nama_wali' => trim((string)$sheet->getCell('AB' . $r)->getValue()),
                'thn_wali' => trim((string)$sheet->getCell('AC' . $r)->getValue()),
                'pend_wali' => trim((string)$sheet->getCell('AD' . $r)->getValue()),
                'pek_wali' => trim((string)$sheet->getCell('AE' . $r)->getValue()),
                'gaji_wali' => trim((string)$sheet->getCell('AF' . $r)->getValue()),
                'nik_wali' => trim((string)$sheet->getCell('AG' . $r)->getValue()),
            ];
        }
    }
}

$matchedCount = 0;
$missingCount = 0;

foreach ($mappings as $rid => $pairs) {
    echo "\n--- Rombel ID $rid ---\n";
    foreach ($pairs as $id => $name) {
        $nLower = strtolower($name);
        $d = null;
        if (isset($dapodikByName[$nLower])) {
            $d = $dapodikByName[$nLower];
        } else {
            // fuzzy
            foreach ($dapodikByName as $k => $v) {
                if (stripos($k, $nLower) !== false || stripos($nLower, $k) !== false) {
                    $d = $v;
                    break;
                }
            }
        }

        if ($d) {
            $matchedCount++;
            echo "  ID $id => {$d['nama']} (NIS: {$d['nipd']}, NISN: {$d['nisn']}, Sheet: {$d['sheet']} Row: {$d['row']})\n";
        } else {
            $missingCount++;
            echo "  ❌ ID $id => $name NOT FOUND IN DAPODIK!\n";
        }
    }
}

echo "\nSummary: Matched = $matchedCount, Missing = $missingCount\n";
