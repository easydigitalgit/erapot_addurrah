<?php
/**
 * ============================================================================
 * SNAPSHOT & RESTORE GENERATOR: DATA SISWA, ROMBEL & ORANG TUA
 * Sistem: e-Rapor SMP IT Ad-Durrah
 * ============================================================================
 * 
 * Penggunaan di VPS via CLI:
 *   1. Jalankan untuk mengambil snapshot:
 *      php capture_siswa_rombel.php
 * 
 *   2. File snapshot SQL otomatis disimpan di folder backups/ atau folder saat ini,
 *      lengkap dengan query INSERT ... ON DUPLICATE KEY UPDATE aman.
 * 
 *   3. Untuk merestore langsung via CLI kapan saja:
 *      mysql -u [user] -p [database] < snapshot_siswa_rombel_YYYYMMDD_HHMMSS.sql
 *      ATAU:
 *      php capture_siswa_rombel.php --restore [nama_file.sql]
 * ============================================================================
 */

// 1. Deteksi Kredensial Database dari .env (CodeIgniter 4)
$envFile = __DIR__ . '/backend/.env';
if (!file_exists($envFile)) {
    $envFile = __DIR__ . '/.env';
}

$dbConfig = [
    'hostname' => 'localhost',
    'database' => 'raporsmpit',
    'username' => 'root',
    'password' => '',
    'port'     => 3306
];

if (file_exists($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        $line = trim($line);
        if (strpos($line, '#') === 0) continue;
        if (preg_match('/^database\.default\.([a-zA-Z0-9_]+)\s*=\s*(.*)$/', $line, $m)) {
            $key = $m[1];
            $val = trim($m[2], " '\t\r\n\"");
            if (isset($dbConfig[$key])) {
                $dbConfig[$key] = $val;
            }
        }
    }
}

// Opsi Override via Command Line Argument
$options = getopt("", ["host:", "db:", "user:", "pass:", "port:", "restore:", "help"]);

if (isset($options['help'])) {
    echo "Penggunaan:\n";
    echo "  php capture_siswa_rombel.php                (Ambil snapshot data saat ini)\n";
    echo "  php capture_siswa_rombel.php --restore=file.sql  (Restore dari snapshot)\n";
    echo "  Opsi koneksi opsional: --host=... --db=... --user=... --pass=... --port=...\n";
    exit(0);
}

if (!empty($options['host'])) $dbConfig['hostname'] = $options['host'];
if (!empty($options['db']))   $dbConfig['database'] = $options['db'];
if (!empty($options['user'])) $dbConfig['username'] = $options['user'];
if (!empty($options['pass'])) $dbConfig['password'] = $options['pass'];
if (!empty($options['port'])) $dbConfig['port']     = $options['port'];

// 2. Koneksi ke Basis Data
try {
    $dsn = "mysql:host={$dbConfig['hostname']};port={$dbConfig['port']};dbname={$dbConfig['database']};charset=utf8mb4";
    $pdo = new PDO($dsn, $dbConfig['username'], $dbConfig['password'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
} catch (PDOException $e) {
    echo "❌ Gagal koneksi ke database: " . $e->getMessage() . "\n";
    exit(1);
}

// 3. Mode Restore
if (!empty($options['restore'])) {
    $restoreFile = $options['restore'];
    if (!file_exists($restoreFile)) {
        echo "❌ File restore tidak ditemukan: $restoreFile\n";
        exit(1);
    }
    echo "⏳ Sedang merestore data dari: $restoreFile ...\n";
    try {
        $pdo->beginTransaction();
        $sql = file_get_contents($restoreFile);
        $pdo->exec($sql);
        $pdo->commit();
        echo "✅ Restore berhasil diselesaikan!\n";
    } catch (Exception $e) {
        $pdo->rollBack();
        echo "❌ Restore gagal: " . $e->getMessage() . "\n";
        exit(1);
    }
    exit(0);
}

// 4. Mode Capture / Snapshot
$timestamp = date('Ymd_His');
$backupDir = __DIR__ . '/backups/snapshots';
if (!is_dir($backupDir)) {
    mkdir($backupDir, 0755, true);
}
$outputFile = "{$backupDir}/snapshot_siswa_rombel_{$timestamp}.sql";

echo "====================================================================\n";
echo " 📸 MEMULAI CAPTURE SNAPSHOT DATA SISWA & ROMBEL\n";
echo " Database: {$dbConfig['database']} @ {$dbConfig['hostname']}\n";
echo " Waktu   : " . date('Y-m-d H:i:s') . "\n";
echo "====================================================================\n";

$sqlOut = "-- ==========================================================================\n";
$sqlOut .= "-- SNAPSHOT DATA SISWA, ROMBEL, ANGGOTA ROMBEL & ORANG TUA\n";
$sqlOut .= "-- Dihasilkan secara otomatis oleh: capture_siswa_rombel.php\n";
$sqlOut .= "-- Database: {$dbConfig['database']}\n";
$sqlOut .= "-- Tanggal : " . date('Y-m-d H:i:s') . "\n";
$sqlOut .= "-- ==========================================================================\n\n";
$sqlOut .= "SET FOREIGN_KEY_CHECKS = 0;\n";
$sqlOut .= "SET SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO';\n";
$sqlOut .= "SET NAMES utf8mb4;\n\n";

// Helper function untuk dump tabel dengan ON DUPLICATE KEY UPDATE
function dumpTableIdempotent($pdo, $tableName, $primaryKey = 'id', $customWhere = '', $updateFields = []) {
    $sql = "-- --------------------------------------------------------------------------\n";
    $sql .= "-- DATA TABEL: `{$tableName}`\n";
    $sql .= "-- --------------------------------------------------------------------------\n";
    
    $query = "SELECT * FROM `{$tableName}` " . ($customWhere ? "WHERE {$customWhere}" : "");
    $stmt = $pdo->query($query);
    $rows = $stmt->fetchAll();
    
    if (empty($rows)) {
        return $sql . "-- (Tabel kosong atau tidak ada data yang memenuhi kriteria)\n\n";
    }
    
    $first = true;
    foreach ($rows as $row) {
        $cols = array_keys($row);
        $escapedCols = array_map(function($c) { return "`$c`"; }, $cols);
        
        $escapedVals = array_map(function($v) {
            if ($v === null) return "NULL";
            return "'" . addslashes($v) . "'";
        }, array_values($row));
        
        // Buat clause update
        $updateParts = [];
        foreach ($cols as $col) {
            if ($col === $primaryKey) continue;
            if (!empty($updateFields) && !in_array($col, $updateFields)) continue;
            $updateParts[] = "`$col` = VALUES(`$col`)";
        }
        $updateClause = implode(', ', $updateParts);
        
        $sql .= "INSERT INTO `{$tableName}` (" . implode(', ', $escapedCols) . ")\n";
        $sql .= "VALUES (" . implode(', ', $escapedVals) . ")\n";
        $sql .= "ON DUPLICATE KEY UPDATE " . ($updateClause ?: "`$primaryKey` = VALUES(`$primaryKey`)") . ";\n";
    }
    
    $sql .= "\n";
    return $sql;
}

// 1. Backup Tabel Tahun Ajaran
echo "-> Menyimpan tabel tahun_ajaran...\n";
$sqlOut .= dumpTableIdempotent($pdo, 'tahun_ajaran', 'id');

// 2. Backup Tabel Rombel
echo "-> Menyimpan tabel rombel...\n";
$sqlOut .= dumpTableIdempotent($pdo, 'rombel', 'id');

// 3. Backup Tabel Siswa
echo "-> Menyimpan tabel siswa...\n";
$sqlOut .= dumpTableIdempotent($pdo, 'siswa', 'id');

// 4. Backup Tabel Anggota Rombel
echo "-> Menyimpan tabel anggota_rombel...\n";
$sqlOut .= dumpTableIdempotent($pdo, 'anggota_rombel', 'id');

// 5. Backup Tabel Orang Tua / Wali
echo "-> Menyimpan tabel orangtua_wali...\n";
$sqlOut .= dumpTableIdempotent($pdo, 'orangtua_wali', 'id');

// 6. Backup Akun User Khusus Siswa & Ortu (Role 3 & 4)
echo "-> Menyimpan tabel users (khusus role siswa & orang tua)...\n";
$sqlOut .= dumpTableIdempotent($pdo, 'users', 'id', "role_id IN (3, 4)");

$sqlOut .= "SET FOREIGN_KEY_CHECKS = 1;\n";

// Simpan File
file_put_contents($outputFile, $sqlOut);

// Tampilkan Ringkasan Data yang Berhasil Dicapture
echo "\n====================================================================\n";
echo " ✅ SNAPSHOT BERHASIL DIBUAT!\n";
echo " 📁 Lokasi File: {$outputFile}\n";
echo " 📦 Ukuran File: " . number_format(filesize($outputFile) / 1024, 2) . " KB\n";
echo "====================================================================\n\n";

echo "📊 REKAP JUMLAH SISWA AKTIF PER ROMBEL SAAT CAPTURE:\n";
echo str_repeat("-", 60) . "\n";
printf("%-5s | %-15s | %-18s | %-10s\n", "ID", "Tingkat", "Nama Rombel", "Siswa Aktif");
echo str_repeat("-", 60) . "\n";

$taAktif = $pdo->query("SELECT id, tahun, semester FROM tahun_ajaran WHERE status = 'Aktif' LIMIT 1")->fetch();
$taId = $taAktif ? $taAktif['id'] : 10;

$rekapStmt = $pdo->query("SELECT r.id, r.tingkat, r.nama_rombel, 
                          (SELECT COUNT(*) FROM siswa WHERE siswa.rombel_id = r.id AND siswa.status_siswa = 'Aktif') as aktif_count
                          FROM rombel r 
                          WHERE r.id_tahun_ajaran = {$taId}
                          ORDER BY r.tingkat ASC, r.nama_rombel ASC");
$totalAktif = 0;
while ($r = $rekapStmt->fetch()) {
    printf("%-5d | %-15s | %-18s | %-10d\n", $r['id'], $r['tingkat'], $r['nama_rombel'], $r['aktif_count']);
    $totalAktif += $r['aktif_count'];
}
echo str_repeat("-", 60) . "\n";
echo " TOTAL SISWA AKTIF DI SELURUH ROMBEL: {$totalAktif} Siswa\n";
echo "====================================================================\n\n";

echo "💡 CARA RESTORE DATA INI NANTI JIKA TERJADI MASALAH:\n";
echo "   Cara 1 (CLI PHP):\n";
echo "      php capture_siswa_rombel.php --restore={$outputFile}\n\n";
echo "   Cara 2 (CLI MySQL):\n";
echo "      mysql -u {$dbConfig['username']} -p {$dbConfig['database']} < {$outputFile}\n\n";
echo "   Cara 3 (phpMyAdmin):\n";
echo "      Buka phpMyAdmin > Pilih database `{$dbConfig['database']}` > Masuk tab Import > Pilih file di atas.\n";
