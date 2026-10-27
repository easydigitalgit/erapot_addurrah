<?php
$pdo = new PDO('mysql:host=localhost;dbname=raporsmpit', 'root', '');

$sql = "
    SELECT siswa_id, mapel_id, tahun_ajaran_id, IFNULL(kategori, '') as kat, jenis_sumatif, COUNT(*) as c
    FROM nilai_sumatif
    GROUP BY siswa_id, mapel_id, tahun_ajaran_id, IFNULL(kategori, ''), jenis_sumatif
    HAVING c > 1
";
$allDups = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);

$deleteIds = [];
$keepIds = [];
foreach ($allDups as $d) {
    $stmt = $pdo->prepare("SELECT id, nilai, updated_at FROM nilai_sumatif WHERE siswa_id = ? AND mapel_id = ? AND tahun_ajaran_id = ? AND IFNULL(kategori, '') = ? AND jenis_sumatif = ? ORDER BY nilai DESC, updated_at DESC, id ASC");
    $stmt->execute([$d['siswa_id'], $d['mapel_id'], $d['tahun_ajaran_id'], $d['kat'], $d['jenis_sumatif']]);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $keepIds[] = $rows[0]['id'];
    for ($i = 1; $i < count($rows); $i++) {
        $deleteIds[] = $rows[$i]['id'];
    }
}

echo "Total delete: " . count($deleteIds) . PHP_EOL;
echo "List IDs to delete: " . implode(',', $deleteIds) . PHP_EOL;

