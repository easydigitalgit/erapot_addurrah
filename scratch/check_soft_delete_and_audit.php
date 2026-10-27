<?php
$pdo = new PDO('mysql:host=127.0.0.1;dbname=raporsmpit', 'root', '');
$tables = ['siswa', 'anggota_rombel', 'nilai_formatif', 'nilai_sumatif', 'nilai_rapor', 'master_lm', 'audit_logs', 'riwayat_perubahan_nilai', 'login_logs'];

foreach ($tables as $t) {
    echo "=== Table: $t ===\n";
    try {
        $stmt = $pdo->query("SHOW COLUMNS FROM $t");
        $cols = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $colNames = array_column($cols, 'Field');
        echo "Columns: " . implode(', ', $colNames) . "\n";
        $hasDeleted = in_array('deleted_at', $colNames) ? 'YES' : 'NO';
        echo "Has deleted_at: $hasDeleted\n";
        
        $count = $pdo->query("SELECT count(*) as c FROM $t")->fetch(PDO::FETCH_ASSOC)['c'];
        echo "Row count: $count\n\n";
    } catch (\Exception $e) {
        echo "Error/Not found: " . $e->getMessage() . "\n\n";
    }
}

