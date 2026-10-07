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

    // Simulasi mapRolesForLogin untuk user_id = 123 (Ibu Tika)
    $userId = 123;
    $dbRoles = $pdo->query("SELECT * FROM user_roles ur JOIN roles r ON r.id = ur.role_id WHERE ur.user_id = $userId")->fetchAll(PDO::FETCH_ASSOC);
    if (empty($dbRoles)) {
        // Fallback user role
        $uRow = $pdo->query("SELECT role_id FROM users WHERE id = $userId")->fetch(PDO::FETCH_ASSOC);
        if ($uRow) $dbRoles = [['role_id' => $uRow['role_id']]];
    }

    echo "DB Roles for User 123:\n";
    print_r($dbRoles);

    // Test query logic baru
    $guru = $pdo->query("SELECT * FROM guru_tendik WHERE user_id = $userId")->fetch(PDO::FETCH_ASSOC);
    $taAktif = $pdo->query("SELECT * FROM tahun_ajaran WHERE status = 'Aktif'")->fetch(PDO::FETCH_ASSOC);
    $idTaAktif = $taAktif ? $taAktif['id'] : null;

    $stmt = $pdo->prepare("SELECT * FROM rombel WHERE wali_kelas_id = :guru_id AND id_tahun_ajaran = :id_ta ORDER BY id_tahun_ajaran DESC");
    $stmt->execute(['guru_id' => $guru['id'], 'id_ta' => $idTaAktif]);
    $rombel = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$rombel) {
        $stmt2 = $pdo->prepare("SELECT * FROM rombel WHERE wali_kelas_id = :guru_id ORDER BY id_tahun_ajaran DESC");
        $stmt2->execute(['guru_id' => $guru['id']]);
        $rombel = $stmt2->fetch(PDO::FETCH_ASSOC);
    }

    echo "Hasil penentuan rombel:\n";
    print_r($rombel);

    echo "\nLabel role wali kelas yang akan ditampilkan:\n";
    echo "Wali Kelas " . $rombel['nama_rombel'] . "\n";

} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
