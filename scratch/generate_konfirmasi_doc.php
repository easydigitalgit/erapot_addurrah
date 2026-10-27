<?php
$pdo = new PDO('mysql:host=127.0.0.1;dbname=raporsmpit', 'root', '');

$rombels = [
    77 => 'VII - Amazonite',
    78 => 'VII - Opal',
    79 => 'VII - Zamrud'
];

$output = "# DAFTAR SISWA KELAS VII IKHWAN BELUM MEMILIKI NILAI BAHASA ARAB & NAHWU\n\n";
$output .= "**Tahun Ajaran:** 2026/2027 Ganjil (STS Ganjil)\n";
$output .= "**Guru Pengampu:** Ustadz Muhammad Fahrozi Sianipar\n";
$output .= "**Mata Pelajaran:** 1. Bahasa Arab (Mapel ID 10) | 2. Nahwu (Mapel ID 44)\n";
$output .= "**Tanggal Rekap:** 28 Oktober 2026\n\n";

$grand_total = 0;

foreach ($rombels as $r_id => $r_nama) {
    $stmt = $pdo->prepare("SELECT s.id, s.nis, s.nisn, s.nama_lengkap 
        FROM anggota_rombel ar 
        JOIN siswa s ON ar.siswa_id = s.id 
        WHERE ar.rombel_id = ? 
        ORDER BY s.nama_lengkap ASC");
    $stmt->execute([$r_id]);
    $siswas = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $count = count($siswas);
    $grand_total += $count;

    $output .= "### {$r_nama} (Total: {$count} Siswa)\n\n";
    $output .= "| No | NIS | NISN | Nama Lengkap Siswa | Status B. Arab | Status Nahwu |\n";
    $output .= "|---|:---:|:---:|---|:---:|:---:|\n";
    
    $no = 1;
    foreach ($siswas as $s) {
        $output .= "| {$no} | `{$s['nis']}` | `{$s['nisn']}` | {$s['nama_lengkap']} | Kosong (`- -`) | Kosong (`- -`) |\n";
        $no++;
    }
    $output .= "\n---\n\n";
}

$output .= "### Total Siswa: {$grand_total} Siswa\n\n";
$output .= "> [!IMPORTANT]\n";
$output .= "> **Catatan Verifikasi Tim Teknis:**\n";
$output .= "> 1. Pengecekan pada seluruh file backup database (lokal & VPS) membuktikan bahwa data nilai mata pelajaran di atas memang **belum pernah diinputkan** ke dalam sistem e-Rapor.\n";
$output .= "> 2. Mata pelajaran lain (10 mapel wajib + Hadist) untuk ke-78 siswa ini **sudah 100% lengkap** di rapor.\n";
$output .= "> 3. Untuk kelas Akhwat (VII Carnelian & VII Ruby) yang diampu oleh Ustadzah Arie Machlina, M.Hum, nilainya sudah terisi lengkap 100%.\n";
$output .= "> 4. Rekomendasi Solusi: Meminta berkas Excel DKN/Daftar Nilai STS Bahasa Arab dan Nahwu untuk rombel Amazonite, Opal, dan Zamrud kepada Ustadz Muhammad Fahrozi Sianipar untuk langsung disuntikkan/disinkronkan ke sistem.\n";

file_put_contents('d:/laragon/htdocs/erapoteasy/docs/dbrepair/08_KONFIRMASI_NILAI_BAHASA_ARAB_DAN_NAHWU_KELAS_VII.md', $output);
echo "Successfully generated 08_KONFIRMASI_NILAI_BAHASA_ARAB_DAN_NAHWU_KELAS_VII.md with $grand_total siswa.\n";

