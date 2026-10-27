<?php
$pdo = new PDO('mysql:host=localhost;dbname=raporsmpit', 'root', '');

$ta_id = 10;
$kategori = 'Tengah Semester';

echo "=== AUDIT KELENGKAPAN NILAI RAPOR SELURUH SISWA KELAS 8 (TA 10, Tengah Semester) ===" . PHP_EOL;

// 1. Ambil semua rombel kelas 8
$rombels = $pdo->query("SELECT id, nama_rombel FROM rombel WHERE tingkat = 'VIII' ORDER BY nama_rombel")->fetchAll(PDO::FETCH_ASSOC);

// 2. Ambil semua mapel umum (bukan tahfidz/bpi) yang berstatus Aktif
$mapels = $pdo->query("SELECT id, nama_mapel FROM mata_pelajaran WHERE status = 'Aktif' AND LOWER(nama_mapel) NOT IN ('tahfidz', 'tahfiz', 'bpi') ORDER BY nomor_urut, id")->fetchAll(PDO::FETCH_ASSOC);

$totalSiswa = 0;
$siswaDenganNilaiLengkap = 0;
$siswaAdaNilaiKosong = [];

foreach ($rombels as $r) {
    echo "\n------------------------------------------------------------\n";
    echo "ROMBEL: {$r['nama_rombel']} (ID: {$r['id']})\n";
    echo "------------------------------------------------------------\n";

    // Ambil siswa di rombel ini
    $stmtSiswa = $pdo->prepare("
        SELECT s.id, s.nama_lengkap 
        FROM siswa s 
        JOIN anggota_rombel ar ON ar.siswa_id = s.id AND ar.tahun_ajaran_id = ? 
        WHERE ar.rombel_id = ? 
        ORDER BY s.nama_lengkap
    ");
    $stmtSiswa->execute([$ta_id, $r['id']]);
    $siswas = $stmtSiswa->fetchAll(PDO::FETCH_ASSOC);

    echo "Total siswa terdaftar: " . count($siswas) . " siswa\n";

    // Cari mapel apa saja yang berlaku untuk rombel ini
    // (Mapel yang setidaknya ada siswa di rombel ini yang punya nilai formatif/sumatif/rapor)
    $stmtMapelRombel = $pdo->prepare("
        SELECT DISTINCT m.id, m.nama_mapel
        FROM mata_pelajaran m
        WHERE m.status = 'Aktif'
          AND LOWER(m.nama_mapel) NOT IN ('tahfidz', 'tahfiz', 'bpi')
          AND (
              EXISTS (SELECT 1 FROM nilai_formatif nf JOIN anggota_rombel ar ON ar.siswa_id = nf.siswa_id AND ar.tahun_ajaran_id = ? WHERE ar.rombel_id = ? AND nf.mapel_id = m.id AND nf.tahun_ajaran_id = ?)
              OR
              EXISTS (SELECT 1 FROM nilai_sumatif ns JOIN anggota_rombel ar ON ar.siswa_id = ns.siswa_id AND ar.tahun_ajaran_id = ? WHERE ar.rombel_id = ? AND ns.mapel_id = m.id AND ns.tahun_ajaran_id = ?)
              OR
              EXISTS (SELECT 1 FROM nilai_rapor nr JOIN anggota_rombel ar ON ar.siswa_id = nr.siswa_id AND ar.tahun_ajaran_id = ? WHERE ar.rombel_id = ? AND nr.mapel_id = m.id AND nr.tahun_ajaran_id = ?)
          )
        ORDER BY m.nomor_urut, m.id
    ");
    $stmtMapelRombel->execute([$ta_id, $r['id'], $ta_id, $ta_id, $r['id'], $ta_id, $ta_id, $r['id'], $ta_id]);
    $mapelRombel = $stmtMapelRombel->fetchAll(PDO::FETCH_ASSOC);

    echo "Mata pelajaran aktif di rombel ini (" . count($mapelRombel) . " mapel):\n";
    $mapelNames = array_column($mapelRombel, 'nama_mapel');
    echo implode(', ', $mapelNames) . "\n\n";

    // Cek tiap siswa
    foreach ($siswas as $s) {
        $totalSiswa++;
        $missingMapel = [];

        foreach ($mapelRombel as $m) {
            // Cek di nilai_rapor
            $stmtCek = $pdo->prepare("
                SELECT nilai_akhir 
                FROM nilai_rapor 
                WHERE siswa_id = ? AND mapel_id = ? AND tahun_ajaran_id = ? AND kategori = ?
            ");
            $stmtCek->execute([$s['id'], $m['id'], $ta_id, $kategori]);
            $na = $stmtCek->fetchColumn();

            if ($na === false || $na === null || $na === '') {
                $missingMapel[] = $m['nama_mapel'];
            }
        }

        if (empty($missingMapel)) {
            $siswaDenganNilaiLengkap++;
        } else {
            $siswaAdaNilaiKosong[] = [
                'rombel' => $r['nama_rombel'],
                'siswa_id' => $s['id'],
                'nama' => $s['nama_lengkap'],
                'missing' => $missingMapel
            ];
            echo "  [PERHATIAN] {$s['nama_lengkap']} (ID {$s['id']}): Masih KOSONG di mapel: " . implode(', ', $missingMapel) . "\n";
        }
    }
}

echo "\n============================================================\n";
echo "REKAPITULASI AUDIT KELAS 8:\n";
echo "============================================================\n";
echo "Total Siswa Kelas 8      : $totalSiswa siswa\n";
echo "Siswa 100% Nilai Lengkap : $siswaDenganNilaiLengkap siswa\n";
echo "Siswa dengan Nilai Kosong: " . count($siswaAdaNilaiKosong) . " siswa\n";

