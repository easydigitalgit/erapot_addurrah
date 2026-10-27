<?php
$pdo = new PDO('mysql:host=127.0.0.1;dbname=raporsmpit', 'root', '');

$tables = ['nilai_sumatif', 'nilai_formatif', 'nilai_rapor', 'anggota_rombel', 'master_lm', 'siswa'];

foreach ($tables as $t) {
    echo "=== INDEXES FOR $t ===\n";
    $stmt = $pdo->query("SHOW INDEX FROM $t");
    $indexes = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $indexes[$row['Key_name']][] = [
            'col' => $row['Column_name'],
            'unique' => $row['Non_unique'] == 0 ? 'UNIQUE' : 'INDEX'
        ];
    }
    foreach ($indexes as $name => $cols) {
        $colNames = implode(', ', array_column($cols, 'col'));
        $type = $cols[0]['unique'];
        echo "  - $name ($type): ($colNames)\n";
    }
    echo "\n";
}

