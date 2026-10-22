<?php
$mysqli = new mysqli('127.0.0.1', 'dev', '12345678', 'raporsmpit');
echo "=== CHECK ORPHAN ANGGOTA_ROMBEL IN THOSE 4 CLASSES ===\n";

$rombelCheck = [
    68 => 'Emerald (ARJUSE FARISI)',
    73 => 'Topaz (ATHALLAH ALSULTHAN NAINGGOLAN)',
    69 => 'Biduri (MALAIKA ZAHFIRA NASUTION)',
    67 => 'Fairuz (RIFKI ZULKARNAIN NASUTION)'
];

foreach ($rombelCheck as $rid => $label) {
    echo "\n--- $label (Rombel ID $rid) ---\n";
    $res = $mysqli->query("SELECT ar.id, ar.siswa_id, s.id as sid, s.nis, s.nama_lengkap 
                           FROM anggota_rombel ar 
                           LEFT JOIN siswa s ON ar.siswa_id = s.id 
                           WHERE ar.rombel_id = $rid");
    while ($r = $res->fetch_assoc()) {
        if (!$r['sid']) {
            echo "  ORPHAN in anggota_rombel: ar.id={$r['id']}, siswa_id={$r['siswa_id']}\n";
            // Check if this siswa_id has grades
            $nf = $mysqli->query("SELECT COUNT(*) as c FROM nilai_formatif WHERE siswa_id={$r['siswa_id']}")->fetch_assoc()['c'];
            $nr = $mysqli->query("SELECT COUNT(*) as c FROM nilai_rapor WHERE siswa_id={$r['siswa_id']}")->fetch_assoc()['c'];
            echo "    Grades: NF=$nf, NR=$nr\n";
        }
    }
}
