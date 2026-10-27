<?php
$pdo = new PDO('mysql:host=localhost;charset=utf8mb4', 'root', '', [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
]);

echo "=== MEMERIKSA DAFTAR TABEL ===" . PHP_EOL;
$tablesLocal = $pdo->query('SHOW TABLES FROM raporsmpit')->fetchAll(PDO::FETCH_COLUMN);
$tablesVps   = $pdo->query('SHOW TABLES FROM raporsmpit_vps')->fetchAll(PDO::FETCH_COLUMN);

$allTables = array_unique(array_merge($tablesLocal, $tablesVps));
sort($allTables);

$diffTables = [];
$counts = [];

foreach ($allTables as $table) {
    $inLocal = in_array($table, $tablesLocal);
    $inVps   = in_array($table, $tablesVps);
    
    $cntLocal = $inLocal ? (int)$pdo->query("SELECT COUNT(*) FROM raporsmpit.`$table`")->fetchColumn() : -1;
    $cntVps   = $inVps ? (int)$pdo->query("SELECT COUNT(*) FROM raporsmpit_vps.`$table`")->fetchColumn() : -1;
    
    $counts[$table] = [
        'in_local' => $inLocal,
        'in_vps'   => $inVps,
        'local_rows' => $cntLocal,
        'vps_rows'   => $cntVps,
        'diff'       => $cntLocal - $cntVps
    ];
}

echo sprintf("%-30s | %-12s | %-12s | %-10s\n", "Nama Tabel", "Lokal Rows", "VPS Rows", "Selisih");
echo str_repeat("-", 70) . PHP_EOL;
foreach ($counts as $tbl => $info) {
    if ($info['diff'] != 0 || !$info['in_local'] || !$info['in_vps']) {
        echo sprintf("%-30s | %-12s | %-12s | %+d\n", 
            $tbl, 
            $info['in_local'] ? $info['local_rows'] : "TIDAK ADA", 
            $info['in_vps'] ? $info['vps_rows'] : "TIDAK ADA", 
            $info['diff']
        );
    }
}

file_put_contents('scratch/table_counts_all.json', json_encode($counts, JSON_PRETTY_PRINT));
echo PHP_EOL . "Total tabel tersimpan di scratch/table_counts_all.json" . PHP_EOL;

