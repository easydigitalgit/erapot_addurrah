<?php
namespace App\Controllers\WaliKelas;

use App\Controllers\WaliKelasBaseController;

class ProgresTahfidzController extends WaliKelasBaseController
{
    private function getGuruId() {
        $db = \Config\Database::connect();
        $guru = $db->table('guru_tendik')->where('user_id', session()->get('user_id'))->get()->getRowArray();
        return $guru ? $guru['id'] : null;
    }

    private function getRombelIdWaliKelas() {
        $db = \Config\Database::connect();
        $guru_id = $this->getGuruId();
        if ($guru_id) {
            $rombel = $db->table('rombel')
                         ->where('wali_kelas_id', $guru_id)
                         ->where('tahun_ajaran', session()->get('tahun_ajaran') ?? '2024/2025')
                         ->where('semester', session()->get('semester') ?? 'Ganjil')
                         ->get()->getRowArray();
            if ($rombel) return $rombel['id'];
        }
        return 16; // Fallback untuk testing UI
    }

    public function index(): string
    {
        $db = \Config\Database::connect();
        $rombel_id = $this->getRombelIdWaliKelas();
        
        $sekolah = $db->table('sekolah')->select('warna_primary, warna_secondary')->get()->getRowArray();
        $color = [
            'warna_primary'   => $sekolah ? $sekolah['warna_primary'] : '#10b981',
            'warna_secondary' => $sekolah ? $sekolah['warna_secondary'] : '#ecfdf5',
        ];

        // 1. Dapatkan Senarai Pelajar di Kelas Ini
        $students = $db->table('siswa')
                       ->select('id, nama_lengkap as name, nisn')
                       ->where('rombel_id', $rombel_id)
                       ->where('status_siswa', 'Aktif')
                       ->get()->getResultArray();

        $tahfizData = [];

        // 2. LOGIK DINAMIS: Tarik data sebenar dari jadual Tahfidz
        foreach ($students as $s) {
            $juzCurrent = 0;
            $surahCurrent = 'Belum Mulai';
            $ayahCurrent = 0;
            $lastUpdate = date('Y-m-d');
            $juzTarget = 5; // Boleh diubah suai jika ada query ke target_tahfidz
            $testResults = [0, 0, 0]; // Default nilai

            // A. Ambil rekod setoran terakhir (jika jadual setoran wujud)
            if ($db->tableExists('setoran_tahfidz')) {
                $lastSetoran = $db->table('setoran_tahfidz')
                                  ->where('siswa_id', $s['id'])
                                  ->orderBy('tanggal', 'DESC')
                                  ->get()->getRowArray();
                
                if ($lastSetoran) {
                    $juzCurrent   = $lastSetoran['juz'] ?? 0;
                    // Sesuaikan nama field dengan tabel setoran_tahfidz anda (misal: nama_surah/id_surah)
                    $surahCurrent = $lastSetoran['surah'] ?? 'Al-Baqarah'; 
                    $ayahCurrent  = $lastSetoran['ayat'] ?? 0;
                    $lastUpdate   = date('Y-m-d', strtotime($lastSetoran['tanggal']));
                }
            }

            // B. Ambil riwayat ujian/nilai tahfidz (jika jadual nilai wujud)
            if ($db->tableExists('nilai_tahfidz')) {
                $nilaiHistory = $db->table('nilai_tahfidz')
                                   ->where('siswa_id', $s['id'])
                                   ->orderBy('created_at', 'DESC')
                                   ->limit(3)
                                   ->get()->getResultArray();
                
                if (!empty($nilaiHistory)) {
                    $testResults = [];
                    foreach (array_reverse($nilaiHistory) as $n) {
                        $testResults[] = (int)($n['nilai'] ?? 0);
                    }
                    // Pastikan array sentiasa ada 3 elemen untuk carta UI
                    while(count($testResults) < 3) array_unshift($testResults, 0); 
                }
            }

            // Pengiraan Automatik Peratusan (Cegah pembahagian sifar)
            $progress = 0;
            if ($juzTarget > 0) {
                $progress = min(round(($juzCurrent / $juzTarget) * 100), 100);
            }

            $tahfizData[] = [
                'id'           => $s['id'],
                'name'         => $s['name'],
                'juzTarget'    => $juzTarget,
                'juzCurrent'   => $juzCurrent,
                'surahCurrent' => $surahCurrent,
                'ayahCurrent'  => $ayahCurrent,
                'progress'     => $progress,
                'lastUpdate'   => $lastUpdate,
                'status'       => 'Aktif',
                'testResults'  => $testResults
            ];
        }

        $data = [
            'title'       => 'Progres Tahfidz',
            'user'        => session()->get('nama_lengkap') ?? 'Wali Kelas',
            'navigations' => $this->getSidebarMenu(),
            'color'       => $color,
            'tahfizData'  => json_encode($tahfizData)
        ];
        
        return view('WaliKelas/progres-tahfidz', $data); 
    }
}