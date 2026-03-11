<?php

namespace App\Controllers\Tahfidz;

use App\Controllers\TahfidzBaseController;

class DashboardController extends TahfidzBaseController
{
    public function index()
    {
        $db = \Config\Database::connect();
        $hari_ini = date('Y-m-d');
        
        // 1. Hitung Total Siswa Aktif
        $total_siswa = $db->table('siswa')->countAllResults();

        // 2. Hitung Setoran Hari Ini
        $setoran_hari_ini = 0;
        if ($db->tableExists('setoran_tahfidz')) {
            $setoran_hari_ini = $db->table('setoran_tahfidz')
                                   ->where('tanggal', $hari_ini)
                                   ->countAllResults();
        }

        // 3. Kalkulasi Persentase Keaktifan Hari Ini
        $persentase = ($total_siswa > 0) ? round(($setoran_hari_ini / $total_siswa) * 100) : 0;

        // 4. Hitung Target Tercapai (Simulasi: Siswa yang sudah setor >= 5 kali)
        $target_tercapai = 0;
        if ($db->tableExists('setoran_tahfidz')) {
            $target_tercapai = $db->table('setoran_tahfidz')
                                  ->select('siswa_id')
                                  ->groupBy('siswa_id')
                                  ->having('COUNT(id) >=', 5)
                                  ->countAllResults();
        }

        // 5. Data Live Feed: 6 Setoran Terakhir
        $setoran_terakhir = [];
        if ($db->tableExists('setoran_tahfidz')) {
            $setoran_terakhir = $db->table('setoran_tahfidz')
                                   ->select('setoran_tahfidz.*, siswa.nama_lengkap')
                                   ->join('siswa', 'siswa.id = setoran_tahfidz.siswa_id', 'left')
                                   ->orderBy('setoran_tahfidz.created_at', 'DESC')
                                   ->limit(6)
                                   ->get()
                                   ->getResultArray();
        }

        // =========================================================================
        // 6. FITUR CERDAS: DETEKSI SANTRI PERLU PERHATIAN KHUSUS
        // =========================================================================
        $perhatian = [];
        if ($db->tableExists('setoran_tahfidz') && $db->tableExists('siswa')) {
            
            // Batas waktu "Beberapa Minggu" -> Kita atur 14 Hari (2 Minggu) ke belakang
            $tanggal_batas = date('Y-m-d', strtotime('-14 days')); 

            // Query Cerdas (AI-like) dengan MySQL
            $sqlPerhatian = "
                SELECT 
                    s.id, 
                    s.nama_lengkap, 
                    r.nama_rombel,
                    (SELECT st1.predikat FROM setoran_tahfidz st1 WHERE st1.siswa_id = s.id ORDER BY st1.tanggal DESC, st1.id DESC LIMIT 1) as predikat_terakhir,
                    (SELECT st2.ayat FROM setoran_tahfidz st2 WHERE st2.siswa_id = s.id ORDER BY st2.tanggal DESC, st2.id DESC LIMIT 1) as ayat_terakhir,
                    (SELECT st3.tanggal FROM setoran_tahfidz st3 WHERE st3.siswa_id = s.id ORDER BY st3.tanggal DESC, st3.id DESC LIMIT 1) as tanggal_terakhir
                FROM siswa s
                LEFT JOIN rombel r ON r.id = s.rombel_id
                WHERE s.status_siswa = 'Aktif'
                HAVING 
                    -- ATURAN 1: Sudah 2 minggu tidak setor ATAU belum pernah setor sama sekali
                    (tanggal_terakhir IS NULL OR tanggal_terakhir < '$tanggal_batas') 
                    
                    -- ATURAN 2: Setoran terakhir mendapat predikat buruk
                    OR predikat_terakhir IN ('Kurang Lancar', 'Belum Hafal') 
                    
                    -- ATURAN 3: Kuantitas setoran <= 5 ayat
                    OR (
                        ayat_terakhir IS NOT NULL AND ayat_terakhir != ''
                        AND (
                            -- Jika formatnya '1-5', MySQL akan menghitung (5 - 1) + 1 = 5 ayat
                            (LOCATE('-', ayat_terakhir) > 0 AND (CAST(SUBSTRING_INDEX(ayat_terakhir, '-', -1) AS SIGNED) - CAST(SUBSTRING_INDEX(ayat_terakhir, '-', 1) AS SIGNED) + 1) <= 5)
                            -- Jika tidak ada strip ('-') berarti cuma setor 1 ayat (otomatis masuk target perhatian)
                            OR LOCATE('-', ayat_terakhir) = 0
                        )
                    )
                ORDER BY tanggal_terakhir ASC
                LIMIT 6
            ";

            $rawPerhatian = $db->query($sqlPerhatian)->getResultArray();

            // Menerjemahkan hasil ke UI yang sudah Mas buat di dashboard.php
            foreach($rawPerhatian as $p) {
                $alasan = '';
                $predikat_ui = 'Kurang Lancar'; 

                // Identifikasi alasan masuk ke daftar ini
                if (empty($p['tanggal_terakhir']) || $p['tanggal_terakhir'] < $tanggal_batas) {
                    $alasan = 'Jarang Setor (Terakhir: ' . ($p['tanggal_terakhir'] ? date('d M', strtotime($p['tanggal_terakhir'])) : 'Belum Ada') . ')';
                    $predikat_ui = 'Belum Hafal'; // Memaksa warna merah (Rose) di UI
                } 
                elseif (in_array($p['predikat_terakhir'], ['Kurang Lancar', 'Belum Hafal'])) {
                    $alasan = 'Predikat: ' . $p['predikat_terakhir'];
                    $predikat_ui = $p['predikat_terakhir']; 
                } 
                else {
                    $alasan = 'Setoran Terlalu Sedikit (Ayat ' . $p['ayat_terakhir'] . ')';
                    $predikat_ui = 'Kurang Lancar'; // Memaksa warna kuning (Amber) di UI
                }

                $perhatian[] = [
                    'nama_lengkap' => $p['nama_lengkap'],
                    // Kita tempelkan alasannya di belakang nama rombel agar otomatis muncul di UI
                    'nama_rombel' => ($p['nama_rombel'] ?? '-') . ' • ' . $alasan,
                    'predikat' => $predikat_ui,
                    'created_at' => date('Y-m-d H:i:s')
                ];
            }
        }

        // 7. Data Distribusi Juz
        $distribusi = ['juz30' => 0, 'juz29' => 0, 'juz28' => 0];
        if ($db->tableExists('setoran_tahfidz')) {
            $juz30 = $db->table('setoran_tahfidz')->like('surah', 'An-Naba')->orLike('surah', 'Al-Ikhlas')->countAllResults();
            $juz29 = $db->table('setoran_tahfidz')->like('surah', 'Al-Mulk')->orLike('surah', 'Al-Mursalat')->countAllResults();
            $juz28 = $db->table('setoran_tahfidz')->like('surah', 'Al-Mujadilah')->orLike('surah', 'At-Tahrim')->countAllResults();
            
            $total_juz = $juz30 + $juz29 + $juz28;
            if ($total_juz > 0) {
                $distribusi['juz30'] = round(($juz30 / $total_juz) * 100);
                $distribusi['juz29'] = round(($juz29 / $total_juz) * 100);
                $distribusi['juz28'] = round(($juz28 / $total_juz) * 100);
            } else {
                $distribusi = ['juz30' => 65, 'juz29' => 25, 'juz28' => 10]; // Data dummy
            }
        }

        $data = [
            'user'             => session()->get('nama_lengkap') ?? session()->get('username') ?? 'Ustadz/ah',
            'color'            => $this->getColor(), 
            'navigations'      => $this->getSidebarMenu(),
            'total_siswa'      => $total_siswa,
            'setoran_hari_ini' => $setoran_hari_ini,
            'persentase'       => $persentase,
            'target_tercapai'  => $target_tercapai,
            'setoran_terakhir' => $setoran_terakhir,
            'perhatian'        => $perhatian, // List anak yang difilter oleh sistem baru
            'distribusi'       => $distribusi
        ];

        return view('tahfidz/dashboard', $data);
    }

    // FUNGSI EXPORT REKAP HARIAN KE FILE CSV (EXCEL) TETAP SAMA
    public function exportRekap()
    {
        $db = \Config\Database::connect();
        $hari_ini = date('Y-m-d');
        
        $setoran = $db->table('setoran_tahfidz')
                      ->select('siswa.nama_lengkap, rombel.nama_rombel, setoran_tahfidz.surah, setoran_tahfidz.ayat, setoran_tahfidz.jenis_setoran, setoran_tahfidz.predikat, setoran_tahfidz.created_at')
                      ->join('siswa', 'siswa.id = setoran_tahfidz.siswa_id', 'left')
                      ->join('rombel', 'rombel.id = siswa.rombel_id', 'left')
                      ->where('setoran_tahfidz.tanggal', $hari_ini)
                      ->orderBy('setoran_tahfidz.created_at', 'DESC')
                      ->get()
                      ->getResultArray();

        $filename = 'Rekap_Setoran_Hari_Ini_' . date('d_M_Y') . '.csv';

        header("Content-Description: File Transfer");
        header("Content-Disposition: attachment; filename=$filename");
        header("Content-Type: application/csv; charset=UTF-8");
        
        $file = fopen('php://output', 'w');
        fputs($file, $bom =( chr(0xEF) . chr(0xBB) . chr(0xBF) ));
        
        $header = array("No", "Waktu Setor", "Nama Santri", "Kelas", "Surah", "Ayat", "Jenis Setoran", "Predikat");
        fputcsv($file, $header);

        $no = 1;
        foreach ($setoran as $row) {
            fputcsv($file, array(
                $no++,
                date('H:i', strtotime($row['created_at'])) . ' WIB',
                $row['nama_lengkap'],
                $row['nama_rombel'] ?? '-',
                $row['surah'],
                $row['ayat'],
                $row['jenis_setoran'],
                $row['predikat']
            ));
        }

        fclose($file);
        exit;
    }
}