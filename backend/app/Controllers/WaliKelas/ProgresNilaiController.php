<?php
namespace App\Controllers\WaliKelas;

use App\Controllers\WaliKelasBaseController;

class ProgresNilaiController extends WaliKelasBaseController
{
    public function index()
    {
        $db = \Config\Database::connect();
        $userId = session()->get('user_id');
        $tahun_ajaran = session()->get('tahun_ajaran') ?? '2024/2025';
        $semester = session()->get('semester') ?? 'Ganjil';

        // 1. Ambil Warna Tema
        $sekolah = $db->table('sekolah')->select('nama_sekolah, warna_primary, warna_secondary')->get()->getRowArray();
        $warna_primary = $sekolah ? $sekolah['warna_primary'] : '#10b981';
        $warna_secondary = $sekolah ? $sekolah['warna_secondary'] : '#ecfdf5';
        $nama_sekolah = $sekolah ? $sekolah['nama_sekolah'] : 'SMPIT Ad Durrah';

        $guru = $db->table('guru_tendik')->where('user_id', $userId)->get()->getRowArray();
        $rombel = null;
        
        $statistik_umum = [
            'total_mapel' => 0, 'rata_kelas' => 0,
            'mapel_aman' => 0, 'mapel_rawan' => 0,
            'persen_aman' => 0, 'persen_rawan' => 0
        ];

        $subjectsData = [];
        $studentsData = [];

        if ($guru) {
            // 2. Cari Rombel dengan Logika Pintar
            $ta_aktif = $db->table('tahun_ajaran')->where('tahun', $tahun_ajaran)->where('semester', $semester)->get()->getRowArray();
            if(!$ta_aktif) $ta_aktif = $db->table('tahun_ajaran')->where('status', 'Aktif')->get()->getRowArray();
            $id_ta = $ta_aktif ? $ta_aktif['id'] : 0;

            $rombel = $db->table('rombel')->where('wali_kelas_id', $guru['id'])->where('id_tahun_ajaran', $id_ta)->get()->getRowArray();

            // PERBAIKAN: AUTO-RADAR (Jika di tahun aktif tidak ada, cari di tahun lain)
            if (!$rombel) {
                $rombel = $db->table('rombel')
                             ->where('wali_kelas_id', $guru['id'])
                             ->orderBy('id', 'DESC') // Ambil yang paling baru
                             ->get()->getRowArray();
                
                if ($rombel) {
                    // Beri tahu user bahwa data ini dari tahun ajaran yang berbeda jika perlu (opsional di Controller)
                }
            }

            if ($rombel) {
                $ta_asli = $db->table('tahun_ajaran')->where('id', $rombel['id_tahun_ajaran'])->get()->getRowArray();
                $rombel['semester'] = $ta_asli ? $ta_asli['semester'] : 'Ganjil';
                $rombel['tahun_ajaran'] = $ta_asli ? $ta_asli['tahun'] : '2024/2025';

                // 3. AMBIL SEMUA DATA MASTER (Siswa & Jadwal Mapel)
                $siswaList = $db->table('siswa')
                                ->select('id, nama_lengkap')
                                ->where('rombel_id', $rombel['id'])
                                ->where('status_siswa', 'Aktif')
                                ->orderBy('nama_lengkap', 'ASC')
                                ->get()->getResultArray();
                
                $siswaIds = array_column($siswaList, 'id');

                $jadwalMapel = [];
                // PERBAIKAN ANTI-GAGAL: Pencarian Mapel Tiga Lapis (Triple Fallback)
                if ($db->tableExists('mata_pelajaran')) {
                    // Lapis 1: Berdasarkan Kurikulum Rombel
                    $kurikulum_id = $rombel['kurikulum_id'] ?? 0;
                    if ($kurikulum_id > 0) {
                        $jadwalMapel = $db->table('mata_pelajaran')
                                          ->select('id as mapel_id, nama_mapel')
                                          ->where('kurikulum_id', $kurikulum_id)
                                          ->where('status', 'Aktif')
                                          ->get()->getResultArray();
                    }
                                      
                    // Lapis 2: Berdasarkan Jadwal Pelajaran jika Lapis 1 Kosong
                    if (empty($jadwalMapel) && $db->tableExists('jadwal_pelajaran')) {
                        $jadwalMapel = $db->table('jadwal_pelajaran')
                                          ->select('mata_pelajaran.id as mapel_id, mata_pelajaran.nama_mapel')
                                          ->join('mata_pelajaran', 'mata_pelajaran.id = jadwal_pelajaran.mapel_id')
                                          ->where('jadwal_pelajaran.rombel_id', $rombel['id'])
                                          ->groupBy(['mata_pelajaran.id', 'mata_pelajaran.nama_mapel'])
                                          ->get()->getResultArray();
                    }

                    // Lapis 3: Berdasarkan Sisa-Sisa Nilai Existing jika Lapis 1 & 2 Kosong
                    if (empty($jadwalMapel) && !empty($siswaIds) && $db->tableExists('nilai_sumatif')) {
                        $existIds = $db->table('nilai_sumatif')
                                       ->select('mapel_id')
                                       ->whereIn('siswa_id', $siswaIds)
                                       ->groupBy('mapel_id')
                                       ->get()->getResultArray();
                        
                        if (!empty($existIds)) {
                            $mapelIdsFound = array_column($existIds, 'mapel_id');
                            $jadwalMapel = $db->table('mata_pelajaran')
                                              ->select('id as mapel_id, nama_mapel')
                                              ->whereIn('id', $mapelIdsFound)
                                              ->get()->getResultArray();
                        }
                    }
                }

                // 4. INISIALISASI STRUKTUR ARRAY AGAR TIDAK ADA YANG HILANG
                $mapelGrouped = [];
                foreach ($jadwalMapel as $jm) {
                    $mapelGrouped[$jm['mapel_id']] = [
                        'nama_mapel' => $jm['nama_mapel'],
                        'nilai_list' => []
                    ];
                }

                $siswaGrouped = [];
                foreach ($siswaList as $siswa) {
                    $siswaGrouped[$siswa['id']] = [
                        'id' => $siswa['id'],
                        'name' => $siswa['nama_lengkap']
                    ];
                    // Siapkan slot nilai kosong untuk semua mapel
                    foreach ($jadwalMapel as $jm) {
                        $siswaGrouped[$siswa['id']][$jm['nama_mapel']] = '-';
                    }
                }

                // 5. TARIK DATA NILAI DARI DATABASE
                if (!empty($siswaIds) && $db->tableExists('nilai_sumatif')) {
                    $allNilai = $db->table('nilai_sumatif')
                                   ->select('siswa_id, mapel_id, nilai')
                                   ->whereIn('siswa_id', $siswaIds)
                                   ->get()->getResultArray();

                    foreach ($allNilai as $n) {
                        $s_id = $n['siswa_id'];
                        $m_id = $n['mapel_id'];
                        $nilai = (float)$n['nilai'];

                        if (isset($mapelGrouped[$m_id])) {
                            $mapelGrouped[$m_id]['nilai_list'][] = $nilai;
                            $nama_mapel = $mapelGrouped[$m_id]['nama_mapel'];
                            if (isset($siswaGrouped[$s_id])) {
                                $siswaGrouped[$s_id][$nama_mapel] = $nilai;
                            }
                        }
                    }
                }

                // 6. OLAH STATISTIK UNTUK VIEW
                $statistik_umum['total_mapel'] = count($mapelGrouped);
                $total_nilai_semua_mapel = 0;

                $icons = ['📚', '🔢', '🌍', '🧪', '🗺', '📖', '🎨', '💻', '💡', '🏆'];
                $colors = ['#3b82f6', '#ef4444', '#8b5cf6', '#f59e0b', '#06b6d4', '#10b981', '#ec4899', '#6366f1', '#14b8a6', '#f43f5e'];
                $index = 0;

                foreach ($mapelGrouped as $m_id => $dataMapel) {
                    $list_nilai = $dataMapel['nilai_list'];
                    $jumlah_siswa_dinilai = count($list_nilai);
                    
                    $highest = $jumlah_siswa_dinilai > 0 ? max($list_nilai) : 0;
                    $lowest = $jumlah_siswa_dinilai > 0 ? min($list_nilai) : 0;
                    $avg = $jumlah_siswa_dinilai > 0 ? round(array_sum($list_nilai) / $jumlah_siswa_dinilai) : 0;
                    
                    $total_nilai_semua_mapel += $avg;

                    // Status Dinamis Baru
                    if ($jumlah_siswa_dinilai == 0) {
                        $status = 'Belum Dinilai';
                    } elseif ($avg < 60) { 
                        $status = 'Kritis'; 
                        $statistik_umum['mapel_rawan']++;
                    } elseif ($avg < 75) { 
                        $status = 'Rawan'; 
                        $statistik_umum['mapel_rawan']++; 
                    } else { 
                        $status = 'Aman'; 
                        $statistik_umum['mapel_aman']++; 
                    }

                    $subjectsData[] = [
                        'id'      => $m_id,
                        'name'    => $dataMapel['nama_mapel'],
                        'average' => $avg,
                        'highest' => $highest,
                        'lowest'  => $lowest,
                        'trend'   => $jumlah_siswa_dinilai == 0 ? 'stable' : ($avg >= 75 ? 'up' : ($avg >= 60 ? 'stable' : 'down')),
                        'status'  => $status,
                        'color'   => $colors[$index % count($colors)],
                        'icon'    => $icons[$index % count($icons)]
                    ];
                    $index++;
                }

                if ($statistik_umum['total_mapel'] > 0) {
                    // Hitung rata-rata kelas hanya dari mapel yang sudah dinilai
                    $mapel_dinilai = $statistik_umum['total_mapel'] - ($statistik_umum['total_mapel'] - ($statistik_umum['mapel_aman'] + $statistik_umum['mapel_rawan']));
                    
                    if($mapel_dinilai > 0) {
                         $statistik_umum['rata_kelas'] = round($total_nilai_semua_mapel / $mapel_dinilai, 1);
                         $statistik_umum['persen_aman'] = round(($statistik_umum['mapel_aman'] / $mapel_dinilai) * 100, 1);
                         $statistik_umum['persen_rawan'] = round(($statistik_umum['mapel_rawan'] / $mapel_dinilai) * 100, 1);
                    }
                }

                $studentsData = array_values($siswaGrouped);
            }
        }

        $data = [
            'title'          => 'Progres Nilai Mata Pelajaran',
            'user'           => session()->get('nama_lengkap') ?? 'Wali Kelas',
            'nama_sekolah'   => $nama_sekolah,
            'navigations'    => $this->getSidebarMenu(),
            'rombel'         => $rombel,
            'statistik_umum' => $statistik_umum,
            'subjectsData'   => json_encode($subjectsData),
            'studentsData'   => json_encode($studentsData),
            'color'          => [
                'warna_primary'   => $warna_primary, 
                'warna_secondary' => $warna_secondary
            ]
        ];

        return view('WaliKelas/progres-nilai', $data); 
    }
}