<?php
$pdo = new PDO('mysql:host=localhost;dbname=raporsmpit', 'root', '');
$pdo->beginTransaction();

$sql1 = "
DELETE ns_dummy 
FROM nilai_sumatif ns_dummy
JOIN nilai_sumatif ns_real 
  ON ns_dummy.siswa_id = ns_real.siswa_id 
 AND ns_dummy.mapel_id = ns_real.mapel_id 
 AND ns_dummy.tahun_ajaran_id = ns_real.tahun_ajaran_id 
 AND IFNULL(ns_dummy.kategori, '') = IFNULL(ns_real.kategori, '')
 AND ns_dummy.jenis_sumatif = ns_real.jenis_sumatif
 AND ns_dummy.id != ns_real.id
WHERE ns_dummy.nilai = 1.00 
  AND ns_real.nilai > 1.00;
";
$affected1 = $pdo->exec($sql1);
echo "Step 1 (Hapus dummy 1.00) affected: $affected1 baris\n";

$sql2 = "
DELETE ns_old
FROM nilai_sumatif ns_old
JOIN nilai_sumatif ns_new 
  ON ns_old.siswa_id = ns_new.siswa_id 
 AND ns_old.mapel_id = ns_new.mapel_id 
 AND ns_old.tahun_ajaran_id = ns_new.tahun_ajaran_id 
 AND IFNULL(ns_old.kategori, '') = IFNULL(ns_new.kategori, '')
 AND ns_old.jenis_sumatif = ns_new.jenis_sumatif
 AND ns_old.id < ns_new.id
WHERE ns_old.nilai = ns_new.nilai;
";
$affected2 = $pdo->exec($sql2);
echo "Step 2 (Hapus duplikat identik) affected: $affected2 baris\n";

$check = $pdo->query("
    SELECT COUNT(*) as c FROM (
        SELECT siswa_id, mapel_id, tahun_ajaran_id, IFNULL(kategori, '') as kat, jenis_sumatif
        FROM nilai_sumatif
        GROUP BY siswa_id, mapel_id, tahun_ajaran_id, IFNULL(kategori, ''), jenis_sumatif
        HAVING COUNT(*) > 1
    ) sub
")->fetchColumn();
echo "Sisa duplikat setelah eksekusi: $check\n";

$pdo->commit();
echo "TRANSACTION COMMITTED SUCCESSFULLY!\n";

