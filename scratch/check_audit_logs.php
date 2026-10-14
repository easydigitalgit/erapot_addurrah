<?php
$pdo = new PDO('mysql:host=localhost;dbname=raporsmpit', 'root', '');
$logs = $pdo->query("SELECT * FROM audit_logs WHERE created_at >= '2026-10-08 09:00:00' ORDER BY created_at ASC LIMIT 100")->fetchAll(PDO::FETCH_ASSOC);
echo "Total logs: " . count($logs) . "\n";
foreach (array_slice($logs, 0, 30) as $l) {
    echo "[{$l['created_at']}] User: {$l['user_id']} | Action: {$l['action']} | Description: " . substr($l['description'] ?? '', 0, 80) . "\n";
}
