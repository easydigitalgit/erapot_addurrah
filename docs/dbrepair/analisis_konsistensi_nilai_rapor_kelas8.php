<?php
require_once __DIR__ . '/../../backend/vendor/autoload.php';
require_once __DIR__ . '/../../backend/vendor/codeigniter4/framework/system/bootstrap.php';

$db = \Config\Database::connect();

// Ambil semua rombel kelas VIII
$rombelList = $db->query("SELECT r.id, r.nama_rombel, r.tingkat, r.id_tahun_ajaran, r.wali_kelas_id, r.semester, ta.tahun, ta.semester as ta_semester, ta.status
FROM rombel r 
LEFT JOIN tahun_ajaran ta ON ta.id = r.id_tahun_ajaran 
WHERE r.tingkat LIKE '%VIII%' OR r.tingkat LIKE '%8%'
ORDER BY r.id_tahun_ajaran DESC, r.id")->getResultArray();

$result = [];
foreach ($rombelList as $r) {
    $siswaList = $db->query("SELECT s.id, s.nis, s.nama_lengkap, s.rombel_id
    FROM siswa s
    WHERE s.rombel_id = " . $db->escape($r['id']) . "
    ORDER BY s.nama_lengkap")->getResultArray();
    
    foreach ($siswaList as $s) {
        $nilai = $db->query("SELECT nr.*, m.nama_mapel, m.kkm
        FROM nilai_rapor nr
        LEFT JOIN mata_pelajaran m ON m.id = nr.mapel_id
        WHERE nr.siswa_id = " . $db->escape($s['id']) . "
        AND nr.tahun_ajaran_id = " . $db->escape($r['id_tahun_ajaran']) . "
        ORDER BY nr.kategori, nr.mapel_id")->getResultArray();
        
        $anggota = $db->query("SELECT ar.* FROM anggota_rombel ar WHERE ar.siswa_id=" . $db->escape($s['id']) . " AND ar.tahun_ajaran_id=" . $db->escape($r['id_tahun_ajaran']))->getRowArray();
        
        $issues = [];
        if (empty($nilai)) {
            $issues[] = 'TIDAK_ADA_NILAI_RAPOR_DI_TA_ROMBEL';
        } else {
            foreach ($nilai as $n) {
                $val = $n['nilai_akhir'];
                if ($val === null || $val === '' || $val === 0 || $val === '0' || $val === 0.0) {
                    $issues[] = "NILAI_KOSONG_ATAU_0: mapel_id={$n['mapel_id']} ({$n['nama_mapel']}) kategori={$n['kategori']} nilai_akhir=" . var_export($val, true);
                }
                if (isset($n['predikat']) && ($n['predikat'] === null || trim((string)$n['predikat']) === '' || $n['predikat'] === '-' )) {
                    $issues[] = "PREDIKAT_KOSONG: mapel_id={$n['mapel_id']} kategori={$n['kategori']}";
                }
            }
        }
        
        $catatan = $db->query("SELECT * FROM catatan_rapor WHERE siswa_id=" . $db->escape($s['id']) . " AND tahun_ajaran_id=" . $db->escape($r['id_tahun_ajaran']))->getResultArray();
        if (empty($catatan)) {
            $issues[] = 'TIDAK_ADA_CATATAN_WALI_KELAS';
        }
        
        $result[] = [
            'rombel_id' => $r['id'],
            'rombel' => $r['nama_rombel'],
            'tingkat' => $r['tingkat'],
            'ta_id' => $r['id_tahun_ajaran'],
            'ta' => $r['tahun'],
            'semester_rombel' => $r['semester'],
            'siswa_id' => $s['id'],
            'nis' => $s['nis'],
            'nama' => $s['nama_lengkap'],
            'jumlah_nilai_rapor' => count($nilai),
            'ada_anggota_rombel' => $anggota ? 'YA' : 'TIDAK',
            'issues' => $issues,
            'issues_count' => count($issues)
        ];
    }
}

file_put_contents(__DIR__ . '/analisis_konsistensi_nilai_rapor_kelas8.json', json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
echo json_encode(['done' => true, 'total' => count($result), 'issues' => array_sum(array_column($result, 'issues_count'))], JSON_PRETTY_PRINT);
