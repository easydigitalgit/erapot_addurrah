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

    echo "--- TAHUN AJARAN AKTIF ---\n";
    $taAktif = $pdo->query("SELECT * FROM tahun_ajaran WHERE status = 'Aktif'")->fetchAll(PDO::FETCH_ASSOC);
    print_r($taAktif);

    echo "--- GURU TIKA YEARDILA ---\n";
    $gurus = $pdo->query("SELECT gt.*, u.username, u.email FROM guru_tendik gt LEFT JOIN users u ON u.id = gt.user_id WHERE gt.nama_lengkap LIKE '%Tika%' OR gt.nama_lengkap LIKE '%Yeardila%'")->fetchAll(PDO::FETCH_ASSOC);
    print_r($gurus);

    echo "--- ROMBEL BIDURI & CARNELIAN ---\n";
    $rombels = $pdo->query("SELECT r.*, ta.tahun, ta.semester as ta_sem, ta.status as ta_status, gt.nama_lengkap as nama_wali FROM rombel r LEFT JOIN tahun_ajaran ta ON ta.id = r.id_tahun_ajaran LEFT JOIN guru_tendik gt ON gt.id = r.wali_kelas_id WHERE r.nama_rombel LIKE '%Biduri%' OR r.nama_rombel LIKE '%Carnelian%' ORDER BY r.id_tahun_ajaran DESC")->fetchAll(PDO::FETCH_ASSOC);
    print_r($rombels);

    if (!empty($gurus)) {
        $gid = $gurus[0]['id'];
        echo "--- ROMBEL YANG DIWALI-I OLEH TIKA (id: $gid) ---\n";
        $waliRombels = $pdo->query("SELECT r.*, ta.tahun, ta.semester as ta_sem, ta.status as ta_status FROM rombel r LEFT JOIN tahun_ajaran ta ON ta.id = r.id_tahun_ajaran WHERE r.wali_kelas_id = $gid ORDER BY r.id_tahun_ajaran DESC")->fetchAll(PDO::FETCH_ASSOC);
        print_r($waliRombels);
    }

} catch(Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
