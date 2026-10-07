<?php
require __DIR__ . '/../backend/vendor/autoload.php';

$env = file_get_contents(__DIR__ . '/../backend/.env');
function getEnvVal($k, $c) {
    if (preg_match('/^' . preg_quote($k) . '\s*=\s*(.*)$/m', $c, $m)) return trim($m[1], " \t\n\r\0\x0B'\"");
    return null;
}
$h = getEnvVal('database.default.hostname', $env) ?: 'localhost';
$d = getEnvVal('database.default.database', $env) ?: 'raporsmpit';
$u = getEnvVal('database.default.username', $env) ?: 'root';
$p = getEnvVal('database.default.password', $env) ?: '';

try {
    $pdo = new PDO("mysql:host=$h;dbname=$d;charset=utf8mb4", $u, $p, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
    echo "=== DB CONNECTED SUCCESS ===\n\n";
    
    // 1. Check Tahun Ajaran Aktif
    $taList = $pdo->query("SELECT * FROM tahun_ajaran")->fetchAll(PDO::FETCH_ASSOC);
    echo "--- DAFTAR TAHUN AJARAN ---\n";
    foreach ($taList as $t) {
        echo "ID: {$t['id']}, Tahun: {$t['tahun']}, Smt: {$t['semester']}, Status: {$t['status']}\n";
    }
    echo "\n";

    // 2. Check Rombel Kelas 7 / VII
    $rombels = $pdo->query("SELECT r.*, gt.nama_lengkap as nama_wali, ta.tahun, ta.semester as sem_ta FROM rombel r LEFT JOIN guru_tendik gt ON gt.id = r.wali_kelas_id LEFT JOIN tahun_ajaran ta ON ta.id = r.id_tahun_ajaran ORDER BY r.id_tahun_ajaran DESC, r.tingkat ASC, r.nama_rombel ASC")->fetchAll(PDO::FETCH_ASSOC);
    echo "--- DAFTAR ROMBEL KELAS 7 ---\n";
    foreach ($rombels as $r) {
        if (stripos($r['tingkat'], '7') !== false || stripos($r['tingkat'], 'VII') !== false || stripos($r['nama_rombel'], '7') !== false) {
            $c1 = $pdo->query("SELECT COUNT(*) FROM siswa WHERE rombel_id = {$r['id']} AND status_siswa = 'Aktif'")->fetchColumn();
            $c2 = $pdo->query("SELECT COUNT(*) FROM anggota_rombel WHERE rombel_id = {$r['id']}")->fetchColumn();
            $c2_aktif = 0;
            if ($r['id_tahun_ajaran']) {
                $c2_aktif = $pdo->query("SELECT COUNT(*) FROM anggota_rombel ar JOIN siswa s ON s.id = ar.siswa_id WHERE ar.rombel_id = {$r['id']} AND ar.tahun_ajaran_id = {$r['id_tahun_ajaran']} AND s.status_siswa = 'Aktif'")->fetchColumn();
            }
            echo "Rombel ID: {$r['id']} | Nama: {$r['nama_rombel']} | Tingkat: {$r['tingkat']} | TA: {$r['tahun']} ({$r['sem_ta']}) | Wali ID: " . ($r['wali_kelas_id'] ?: 'BELUM ADA') . " (" . ($r['nama_wali'] ?: '-') . ") | siswa.rombel_id: $c1 | anggota_rombel: $c2 (aktif: $c2_aktif)\n";
        }
    }
    echo "\n";

    // 3. Check apakah ada siswa tanpa rombel atau siswa tingkat 7
    $unassigned = $pdo->query("SELECT COUNT(*) FROM siswa WHERE (rombel_id IS NULL OR rombel_id = 0) AND status_siswa = 'Aktif'")->fetchColumn();
    echo "Jumlah Siswa Aktif TANPA Rombel: $unassigned\n\n";

    // 4. Check Roles & Permissions Wali Kelas
    $roles = $pdo->query("SELECT * FROM roles")->fetchAll(PDO::FETCH_ASSOC);
    echo "--- ROLES ---\n";
    foreach ($roles as $ro) {
        echo "ID: {$ro['id']}, Nama: {$ro['nama_role']}\n";
    }
    echo "\n";

    // 5. Cek Siswa baru / per rombel
    $totalSiswaAktif = $pdo->query("SELECT COUNT(*) FROM siswa WHERE status_siswa = 'Aktif'")->fetchColumn();
    echo "Total Siswa Aktif di DB: $totalSiswaAktif\n";

    $siswaByRombel = $pdo->query("SELECT s.rombel_id, r.nama_rombel, r.tingkat, r.id_tahun_ajaran, COUNT(s.id) as total FROM siswa s LEFT JOIN rombel r ON r.id = s.rombel_id GROUP BY s.rombel_id ORDER BY total DESC")->fetchAll(PDO::FETCH_ASSOC);
    echo "--- DISTRIBUSI SISWA BERDASARKAN ROMBEL_ID DI TABEL SISWA ---\n";
    foreach ($siswaByRombel as $sb) {
        echo "Rombel ID: " . ($sb['rombel_id'] ?: 'NULL/0') . " | Nama: " . ($sb['nama_rombel'] ?: '-') . " | Tingkat: " . ($sb['tingkat'] ?: '-') . " | TA ID: " . ($sb['id_tahun_ajaran'] ?: '-') . " | Jumlah: {$sb['total']}\n";
    }

} catch(Exception $e) {
    echo "DB ERROR: " . $e->getMessage() . "\n";
}
