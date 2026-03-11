<?php
namespace App\Controllers\Admin;

use App\Controllers\AdminBaseController;
use App\Models\Admin\RombelModel;

class DashboardInsightController extends AdminBaseController
{
    public function index(): string
    {
        $rombelModel = new RombelModel();
        $db = \Config\Database::connect();

        // AMBIL TAHUN AJARAN UNIK DARI TABEL tahun_ajaran
        $tahunQuery = $db->query("SELECT DISTINCT tahun FROM tahun_ajaran ORDER BY tahun DESC")->getResultArray();
        $tahunAjaran = array_column($tahunQuery, 'tahun');

        $data = [
            'user' => 'Admin',
            'navigations' => $this->getSidebarMenu(),
            'color' => $this->getColor(),
            'tahun_ajaran' => $tahunAjaran,
            // Rombel diurutkan berdasarkan tingkat lalu nama
            'rombels' => $rombelModel->orderBy('tingkat', 'ASC')->orderBy('nama_rombel', 'ASC')->findAll()
        ];

        return view('admin/dashboard-insight', $data); 
    }

    public function getChartData()
    {
        if (!$this->request->isAJAX()) return $this->response->setStatusCode(404);

        $tahun     = $this->request->getGet('tahun');
        $semester  = $this->request->getGet('semester');
        $tingkat   = $this->request->getGet('tingkat');
        $rombel_id = $this->request->getGet('rombel_id');

        $db = \Config\Database::connect();

        // ---------------------------------------------------------
        // 1. BASE JOIN & FILTER UTAMA 
        // ---------------------------------------------------------
        $baseJoin = "FROM nilai_akademik n 
                     JOIN rombel r ON n.rombel_id = r.id 
                     LEFT JOIN tahun_ajaran ta ON ta.id = r.id_tahun_ajaran ";

        $whereClause = "WHERE 1=1";
        $bindings = [];

        if (!empty($tahun)) { $whereClause .= " AND ta.tahun = ?"; $bindings[] = $tahun; }
        if (!empty($semester)) { $whereClause .= " AND n.semester = ?"; $bindings[] = $semester; }
        if (!empty($tingkat)) { $whereClause .= " AND r.tingkat = ?"; $bindings[] = $tingkat; }
        if (!empty($rombel_id)) { $whereClause .= " AND n.rombel_id = ?"; $bindings[] = $rombel_id; }

        // ---------------------------------------------------------
        // 2. DATA STATISTIK AKADEMIK
        // ---------------------------------------------------------
        $qTotalSiswa = $db->query("SELECT COUNT(DISTINCT n.siswa_id) as total $baseJoin $whereClause", $bindings)->getRowArray();
        $totalSiswa = (int)($qTotalSiswa['total'] ?? 0);

        $qAvg = $db->query("SELECT AVG(n.nilai_angka) as avg_nilai $baseJoin $whereClause", $bindings)->getRowArray();
        $avgSekolah = round((float)($qAvg['avg_nilai'] ?? 0), 1);

        $qStudentAvgs = $db->query("
            SELECT n.siswa_id, AVG(n.nilai_angka) as avg_siswa 
            $baseJoin $whereClause GROUP BY n.siswa_id
        ", $bindings)->getResultArray();

        $tuntas = 0; $bimbingan = 0; $remedial = 0;
        foreach($qStudentAvgs as $sa) {
            $nilai = (float)$sa['avg_siswa'];
            if($nilai >= 75) $tuntas++;
            else if($nilai >= 70) $bimbingan++;
            else $remedial++;
        }

        $tuntasPct = $totalSiswa > 0 ? round(($tuntas / $totalSiswa) * 100, 1) : 0;
        $bimbinganPct = $totalSiswa > 0 ? round(($bimbingan / $totalSiswa) * 100, 1) : 0;

        // ---------------------------------------------------------
        // 3. STATISTIK TAHFIDZ & KARAKTER (REAL DARI DB)
        // ---------------------------------------------------------
        $tahfidzPct = 0;
        $charExcellent = 0;
        $specialNotes = 0;
        $attendanceRate = 100; 
        
        $studentJoin = "FROM siswa s JOIN rombel r ON s.rombel_id = r.id LEFT JOIN tahun_ajaran ta ON r.id_tahun_ajaran = ta.id";
        $studentWhere = "WHERE s.status_siswa = 'Aktif'";
        $studentBindings = [];

        if (!empty($tahun)) { $studentWhere .= " AND ta.tahun = ?"; $studentBindings[] = $tahun; }
        if (!empty($tingkat)) { $studentWhere .= " AND r.tingkat = ?"; $studentBindings[] = $tingkat; }
        if (!empty($rombel_id)) { $studentWhere .= " AND r.id = ?"; $studentBindings[] = $rombel_id; }

        if ($db->tableExists('setoran_tahfidz') && $totalSiswa > 0) {
            $qTahfidz = $db->query("SELECT COUNT(DISTINCT st.siswa_id) as total FROM setoran_tahfidz st WHERE st.siswa_id IN (SELECT s.id $studentJoin $studentWhere)", $studentBindings)->getRowArray();
            $tahfidzCount = (int)($qTahfidz['total'] ?? 0);
            $tahfidzPct = min(round(($tahfidzCount / $totalSiswa) * 100), 100);
        }

        if ($db->tableExists('catatan_akhlak')) {
            $qChar = $db->query("SELECT ca.kategori_akhlak, COUNT(ca.id) as total FROM catatan_akhlak ca WHERE ca.siswa_id IN (SELECT s.id $studentJoin $studentWhere) GROUP BY ca.kategori_akhlak", $studentBindings)->getResultArray();
            foreach($qChar as $c) {
                if ($c['kategori_akhlak'] == 'Sangat Baik') $charExcellent = $c['total'];
                if ($c['kategori_akhlak'] == 'Perlu Pembinaan') $specialNotes = $c['total'];
            }
        }

        // ---------------------------------------------------------
        // 4. CHART DATA (LEVEL & TREND)
        // ---------------------------------------------------------
        $qLevel = $db->query("
            SELECT r.tingkat, AVG(n.nilai_angka) as avg_nilai 
            $baseJoin $whereClause 
            GROUP BY r.tingkat ORDER BY r.tingkat ASC
        ", $bindings)->getResultArray();
        
        $levelLabels = []; $levelData = [];
        foreach($qLevel as $lvl) {
            $levelLabels[] = 'Kelas ' . $lvl['tingkat'];
            $levelData[] = round((float)$lvl['avg_nilai'], 1);
        }

        $trendWhere = "WHERE 1=1";
        $trendBindings = [];
        if (!empty($tingkat)) { $trendWhere .= " AND r.tingkat = ?"; $trendBindings[] = $tingkat; }
        if (!empty($rombel_id)) { $trendWhere .= " AND n.rombel_id = ?"; $trendBindings[] = $rombel_id; }

        $qTrend = $db->query("
            SELECT ta.tahun as tahun_ajaran, n.semester, AVG(n.nilai_angka) as avg_nilai 
            $baseJoin $trendWhere
            GROUP BY ta.tahun, n.semester
            ORDER BY ta.tahun DESC, n.semester DESC LIMIT 5
        ", $trendBindings)->getResultArray();

        $qTrend = array_reverse($qTrend); 

        $trendLabels = []; $trendData = [];
        foreach($qTrend as $tr) {
            if (!$tr['tahun_ajaran']) continue;
            $thn = explode('/', $tr['tahun_ajaran']);
            $shortThn = isset($thn[1]) ? substr($thn[0], -2) . '/' . substr($thn[1], -2) : $tr['tahun_ajaran'];
            $sem = $tr['semester'] == 'Ganjil' ? 'Sem 1' : 'Sem 2';
            $trendLabels[] = "$sem $shortThn";
            $trendData[] = round((float)$tr['avg_nilai'], 1);
        }

        // ---------------------------------------------------------
        // 5. LOGIC REKOMENDASI DINAMIS (AI SEDERHANA)
        // ---------------------------------------------------------
        $bestClass = $db->query("
            SELECT r.nama_rombel, r.tingkat, AVG(n.nilai_angka) as avg_nilai 
            $baseJoin $whereClause 
            GROUP BY r.id ORDER BY avg_nilai DESC LIMIT 1
        ", $bindings)->getRowArray();

        $goodMsg = "Belum ada data nilai yang cukup untuk dianalisis.";
        if ($bestClass && $bestClass['avg_nilai'] > 0) {
            $goodMsg = "Kelas " . $bestClass['tingkat'] . " " . $bestClass['nama_rombel'] . " menunjukkan rata-rata tertinggi (" . round($bestClass['avg_nilai'], 1) . "). Pertahankan metode dan kualitas pembelajaran.";
        }

        $warnMsg = "Luar biasa! Seluruh siswa saat ini terpantau berada di atas batas minimal ketuntasan.";
        if ($remedial > 0) {
            $warnMsg = "Terdapat " . $remedial . " siswa yang membutuhkan perhatian khusus. Disarankan segera menjadwalkan bimbingan atau remedial terstruktur.";
        } else if ($bimbingan > 0) {
            $warnMsg = "Ada " . $bimbingan . " siswa di ambang batas (Perlu Bimbingan). Pastikan untuk memberikan motivasi ekstra agar tidak turun ke zona remedial.";
        }

        return $this->response->setJSON([
            'status' => 'success',
            'stats' => [
                'avg_sekolah'     => $avgSekolah,
                'total_siswa'     => $totalSiswa,
                'tuntas'          => $tuntas,
                'tuntas_pct'      => $tuntasPct,
                'bimbingan'       => $bimbingan,
                'bimbingan_pct'   => $bimbinganPct,
                'remedial'        => $remedial,
                'tahfidz_pct'     => $tahfidzPct,
                'char_excellent'  => $charExcellent,
                'attendance_rate' => $attendanceRate,
                'special_notes'   => $specialNotes
            ],
            'recommendations' => [
                'good'    => $goodMsg,
                'warning' => $warnMsg
            ],
            'level_chart' => [
                'labels' => empty($levelLabels) ? ['Kosong'] : $levelLabels,
                'data'   => empty($levelData) ? [0] : $levelData
            ],
            'trend_chart' => [
                'labels' => empty($trendLabels) ? ['Kosong'] : $trendLabels,
                'data'   => empty($trendData) ? [0] : $trendData
            ]
        ]);
    }
}