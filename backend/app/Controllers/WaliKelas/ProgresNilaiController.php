<?php
namespace App\Controllers\WaliKelas;

use App\Controllers\WaliKelasBaseController;

class ProgresNilaiController extends WaliKelasBaseController
{
    public function index()
    {
        $db = \Config\Database::connect();
        $userId = session()->get('user_id');

        // Ambil Warna Tema
        $sekolah = $db->table('sekolah')->select('warna_primary, warna_secondary')->get()->getRowArray();
        $warna_primary = $sekolah ? $sekolah['warna_primary'] : '#10b981';
        $warna_secondary = $sekolah ? $sekolah['warna_secondary'] : '#ecfdf5';

        $guru = $db->table('guru_tendik')->where('user_id', $userId)->get()->getRowArray();
        $rombel = null;
        
        $statistik_umum = [
            'total_mapel' => 0,
            'rata_kelas' => 0,
            'mapel_aman' => 0,
            'mapel_rawan' => 0
        ];

        // Struktur Data Mapel untuk diolah JS
        $subjectsData = [];
        $studentsData = [];

        if ($guru) {
            $rombel = $db->table('rombel')
                         ->where('wali_kelas_id', $guru['id'])
                         ->where('tahun_ajaran', session()->get('tahun_ajaran') ?? '2024/2025')
                         ->where('semester', session()->get('semester') ?? 'Ganjil')
                         ->get()->getRowArray();

            if ($rombel) {
                // 1. Ambil list unik mapel_id DENGAN JOIN KE SISWA
                if ($db->tableExists('nilai_sumatif') && $db->tableExists('mata_pelajaran')) {
                    
                    $mapelList = $db->table('nilai_sumatif')
                                    ->select('nilai_sumatif.mapel_id, mata_pelajaran.nama_mapel')
                                    ->join('mata_pelajaran', 'mata_pelajaran.id = nilai_sumatif.mapel_id')
                                    ->join('siswa', 'siswa.id = nilai_sumatif.siswa_id') // JOIN ke siswa
                                    ->where('siswa.rombel_id', $rombel['id']) // Filter by rombel_id di tabel siswa
                                    ->groupBy('nilai_sumatif.mapel_id')
                                    ->get()->getResultArray();

                    $statistik_umum['total_mapel'] = count($mapelList);
                    $total_nilai_semua_mapel = 0;

                    // Ikon & Warna acak untuk UI
                    $icons = ['📚', '🔢', '🌍', '🧪', '🗺', '📖', '🎨', '💻'];
                    $colors = ['#3b82f6', '#ef4444', '#8b5cf6', '#f59e0b', '#06b6d4', '#10b981', '#ec4899', '#6366f1'];

                    // 2. Looping per Mapel untuk menghitung Rata-rata, Tertinggi, Terendah
                    foreach ($mapelList as $index => $m) {
                        
                        // Cari nilai DENGAN JOIN KE SISWA
                        $nilai = $db->table('nilai_sumatif')
                                    ->join('siswa', 'siswa.id = nilai_sumatif.siswa_id')
                                    ->where('siswa.rombel_id', $rombel['id'])
                                    ->where('nilai_sumatif.mapel_id', $m['mapel_id'])
                                    ->get()->getResultArray();

                        if (count($nilai) > 0) {
                            $total_n = 0; $highest = 0; $lowest = 100;
                            foreach ($nilai as $n) {
                                $val = (float)$n['nilai'];
                                $total_n += $val;
                                if ($val > $highest) $highest = $val;
                                if ($val < $lowest) $lowest = $val;
                            }
                            
                            $avg = round($total_n / count($nilai));
                            $total_nilai_semua_mapel += $avg;

                            // Tentukan Status berdasarkan rata-rata
                            $status = 'Aman';
                            if ($avg < 60) { $status = 'Kritis'; } 
                            elseif ($avg < 75) { $status = 'Rawan'; $statistik_umum['mapel_rawan']++; } 
                            else { $statistik_umum['mapel_aman']++; }

                            // Format Data untuk dikirim ke JS
                            $subjectsData[] = [
                                'id'      => $m['mapel_id'],
                                'name'    => $m['nama_mapel'],
                                'average' => $avg,
                                'highest' => $highest,
                                'lowest'  => $lowest,
                                'trend'   => $avg >= 75 ? 'up' : ($avg >= 60 ? 'stable' : 'down'),
                                'status'  => $status,
                                'color'   => $colors[$index % count($colors)],
                                'icon'    => $icons[$index % count($icons)]
                            ];
                        }
                    }

                    // Setelah perhitungan $avg di dalam looping...
                    if ($statistik_umum['total_mapel'] > 0) {
                        $statistik_umum['rata_kelas'] = round($total_nilai_semua_mapel / $statistik_umum['total_mapel'], 1);
                        // Hitung Persentase Aman & Rawan
                        $statistik_umum['persen_aman'] = round(($statistik_umum['mapel_aman'] / $statistik_umum['total_mapel']) * 100, 1);
                        $statistik_umum['persen_rawan'] = round(($statistik_umum['mapel_rawan'] / $statistik_umum['total_mapel']) * 100, 1);
                    } else {
                        $statistik_umum['persen_aman'] = 0;
                        $statistik_umum['persen_rawan'] = 0;
                    }

                    // 3. Ambil Data Siswa (Untuk Tabel Modal Detail Siswa)
                    $siswaList = $db->table('siswa')
                                    ->where('rombel_id', $rombel['id'])
                                    ->where('status_siswa', 'Aktif')
                                    ->get()->getResultArray();

                    foreach ($siswaList as $siswa) {
                        $stuData = ['id' => $siswa['id'], 'name' => $siswa['nama_lengkap']];
                        
                        // Ambil nilai per mapel untuk siswa ini
                        $nilaiSiswa = $db->table('nilai_sumatif')
                                         ->select('mata_pelajaran.nama_mapel, nilai_sumatif.nilai')
                                         ->join('mata_pelajaran', 'mata_pelajaran.id = nilai_sumatif.mapel_id')
                                         ->where('nilai_sumatif.siswa_id', $siswa['id'])
                                         ->get()->getResultArray();

                        foreach($nilaiSiswa as $ns) {
                            $stuData[$ns['nama_mapel']] = (float)$ns['nilai'];
                        }
                        $studentsData[] = $stuData;
                    }
                }
            }
        }

        $data = [
            'title'          => 'Progres Nilai Mata Pelajaran',
            'user'           => session()->get('nama_lengkap') ?? 'Wali Kelas',
            'navigations'    => $this->getSidebarMenu(),
            'rombel'         => $rombel,
            'statistik_umum' => $statistik_umum,
            'subjectsData'   => json_encode($subjectsData),
            'studentsData'   => json_encode($studentsData),
            'color'          => ['warna_primary' => $warna_primary, 'warna_secondary' => $warna_secondary]
        ];

        return view('WaliKelas/progres-nilai', $data); 
    }
}