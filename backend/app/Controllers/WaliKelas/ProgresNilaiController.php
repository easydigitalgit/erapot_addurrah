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

                // PERBAIKAN ANTI-GAGAL: Pencarian Mapel Tiga Lapis (Triple Fallback)
                $jadwalMapel = $this->_getJadwalMapel($rombel, $siswaIds);

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

    private function _getJadwalMapel($rombel, $siswaIds)
    {
        $db = \Config\Database::connect();
        $jadwalMapel = [];
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
        return $jadwalMapel;
    }

    public function syncSemuaMapel()
    {
        $db = \Config\Database::connect();
        $userId = session()->get('user_id');
        $kategori = $this->request->getPost('kategori') ?? 'Akhir Semester';
        $kategoriDB = (stripos($kategori, 'tengah') !== false) ? 'Tengah' : 'Akhir';

        $guru = $db->table('guru_tendik')->where('user_id', $userId)->get()->getRowArray();
        if (!$guru) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Data guru tidak ditemukan.']);
        }

        // Ambil tahun ajaran & rombel dari session
        $tahun_ajaran = session()->get('tahun_ajaran') ?? '2024/2025';
        $semester = session()->get('semester') ?? 'Ganjil';
        
        $ta_aktif = $db->table('tahun_ajaran')->where('tahun', $tahun_ajaran)->where('semester', $semester)->get()->getRowArray();
        if(!$ta_aktif) $ta_aktif = $db->table('tahun_ajaran')->where('status', 'Aktif')->get()->getRowArray();
        $ta_id = $ta_aktif ? $ta_aktif['id'] : 0;

        if (!$ta_id) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Tahun ajaran tidak aktif/tidak ditemukan.']);
        }

        $rombel = $db->table('rombel')->where('wali_kelas_id', $guru['id'])->where('id_tahun_ajaran', $ta_id)->get()->getRowArray();
        if (!$rombel) {
            // Fallback cari rombel terbaru jika tidak ketemu
            $rombel = $db->table('rombel')
                         ->where('wali_kelas_id', $guru['id'])
                         ->orderBy('id', 'DESC')
                         ->get()->getRowArray();
        }

        if (!$rombel) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Rombel untuk Wali Kelas tidak ditemukan.']);
        }

        $rombel_id = $rombel['id'];

        $siswaList = $db->table('siswa')
                        ->select('id, nama_lengkap as nama_siswa')
                        ->where('rombel_id', $rombel_id)
                        ->where('status_siswa', 'Aktif')
                        ->get()->getResultArray();
        
        if (empty($siswaList)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Tidak ada siswa aktif di kelas ini.']);
        }

        $siswaIds = array_column($siswaList, 'id');
        $list_mapel = $this->_getJadwalMapel($rombel, $siswaIds);

        if (empty($list_mapel)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Tidak ada mata pelajaran yang terdaftar untuk kelas ini.']);
        }

        // --- 1. AMBIL ATURAN BOBOT DARI DATABASE ---
        $queryBobot = $db->table('setting_bobot_nilai')->get()->getResultArray();
        $bobot = [
            'tengah_semester' => ['nh' => 35, 'uh' => 35, 'sts' => 30],
            'akhir_semester'  => ['nh' => 30, 'uh' => 30, 'sts' => 15, 'sas' => 25]
        ];
        foreach ($queryBobot as $row) {
            if (isset($bobot[$row['kategori']][$row['sub_kategori']])) {
                $bobot[$row['kategori']][$row['sub_kategori']] = (float)$row['bobot'];
            }
        }

        // --- 2. AMBIL ATURAN PREDIKAT DARI DATABASE ---
        $aturanPredikat = $db->table('setting_aturan_nilai')->orderBy('nilai_max', 'DESC')->get()->getResultArray();

        // Bersihkan tingkat rombel
        $tingkatClean = 0;
        $tingkatStr = strtoupper(trim((string)$rombel['tingkat']));
        $angka = preg_replace('/[^0-9]/', '', $tingkatStr);
        if (!empty($angka)) {
            $tingkatClean = (int) $angka;
        } else {
            if (preg_match('/\b(VII|VIII|IX|X|XI|XII)\b/', $tingkatStr, $matches)) {
                $romToNum = ['VII' => 7, 'VIII' => 8, 'IX' => 9, 'X' => 10, 'XI' => 11, 'XII' => 12];
                $tingkatClean = $romToNum[$matches[1]] ?? 0;
            }
        }

        $db->transBegin();
        $jumlah_mapel_disinkron = 0;

        try {
            foreach ($list_mapel as $mapel) {
                $mapel_id = $mapel['mapel_id'];

                // Ambil Data Master LM untuk Auto Deskripsi Capaian per mapel
                $allLMs = [];
                if ($db->tableExists('master_lm') && !empty($tingkatClean)) {
                    $qLm = $db->table('master_lm')
                        ->where('mapel_id', $mapel_id)
                        ->where('tingkat', $tingkatClean)
                        ->where('semester', $semester);

                    if ($db->fieldExists('kategori', 'master_lm')) {
                        $qLm->groupStart()
                            ->where('kategori', $kategoriDB)
                            ->orWhere('kategori', $kategori)
                            ->orWhere('kategori', '')
                            ->orWhere('kategori', null)
                            ->groupEnd();
                    }
                    $allLMs = $qLm->orderBy('id', 'ASC')->get()->getResultArray();
                }

                $qAllFormatif = $db->table('nilai_formatif')
                    ->where('mapel_id', $mapel_id)
                    ->where('tahun_ajaran_id', $ta_id)
                    ->where('rombel_id', $rombel_id);

                if ($db->fieldExists('kategori', 'nilai_formatif')) {
                    $qAllFormatif->groupStart()
                        ->where('kategori', $kategori)
                        ->orWhere('kategori', $kategoriDB)
                        ->groupEnd();
                }
                $all_formatif = $qAllFormatif->get()->getResultArray();

                $qAllSumatif = $db->table('nilai_sumatif')
                    ->where('mapel_id', $mapel_id)
                    ->where('tahun_ajaran_id', $ta_id);

                if ($db->fieldExists('kategori', 'nilai_sumatif')) {
                    $qAllSumatif->groupStart()
                        ->where('kategori', $kategori)
                        ->orWhere('kategori', 'Tengah Semester')
                        ->orWhere('kategori', 'Tengah')
                        ->orWhere('kategori', 'Akhir Semester')
                        ->orWhere('kategori', 'Akhir')
                        ->orWhere('kategori', '')
                        ->orWhere('kategori', null)
                        ->groupEnd();
                }
                $all_sumatif = $qAllSumatif->get()->getResultArray();

                // Deteksi Progres Dinamis
                $pembagi = $this->_getPembagiDinamis($all_formatif);

                foreach ($siswaList as $siswa) {
                    $siswa_id = $siswa['id'];

                    $sum_nh = 0;
                    $sum_uh = 0;
                    $sum_sts = 0;
                    $count_sts = 0;
                    $sum_pas = 0;
                    $count_pas = 0;
                    $sum_sas = 0;
                    $count_sas = 0;

                    $lm_scores = [];

                    foreach ($all_formatif as $f) {
                        if ($f['siswa_id'] == $siswa_id) {
                            $jenis = strtoupper(trim($f['jenis_penilaian'] ?? ''));
                            $nilai = (float)($f['nilai_angka'] ?? 0);
                            $pert = (int)($f['pertemuan'] ?? 0);

                            if (strpos($jenis, 'UH') !== false || strpos($jenis, 'ULANGAN') !== false) {
                                $sum_uh += $nilai;
                            } else {
                                $sum_nh += $nilai;
                            }

                            if (!isset($lm_scores[$pert])) $lm_scores[$pert] = ['sum' => 0, 'count' => 0];
                            $lm_scores[$pert]['sum'] += $nilai;
                            $lm_scores[$pert]['count']++;
                        }
                    }

                    foreach ($all_sumatif as $s) {
                        if ($s['siswa_id'] == $siswa_id) {
                            $jenis = strtoupper(trim($s['jenis_sumatif'] ?? ($s['jenis_penilaian'] ?? '')));
                            $nilai = isset($s['nilai']) ? (float)$s['nilai'] : (float)($s['nilai_angka'] ?? 0);

                            if (strpos($jenis, 'STS') !== false || strpos($jenis, 'PTS') !== false) {
                                $sum_sts += $nilai;
                                $count_sts++;
                            } elseif (strpos($jenis, 'PAS') !== false) {
                                $sum_pas += $nilai;
                                $count_pas++;
                            } elseif (strpos($jenis, 'SAS') !== false) {
                                $sum_sas += $nilai;
                                $count_sas++;
                            }
                        }
                    }

                    $avg_nh  = $sum_nh / $pembagi['nh'];
                    $avg_uh  = $sum_uh / $pembagi['uh'];
                    $avg_sts = $count_sts > 0 ? ($sum_sts / $count_sts) : 0;
                    $avg_pas = $count_pas > 0 ? ($sum_pas / $count_pas) : 0;
                    $avg_sas = $count_sas > 0 ? ($sum_sas / $count_sas) : 0;

                    if ($kategori === 'Tengah Semester') {
                        $w_nh  = $bobot['tengah_semester']['nh'] / 100;
                        $w_uh  = $bobot['tengah_semester']['uh'] / 100;
                        $w_sts = $bobot['tengah_semester']['sts'] / 100;

                        $kalkulasi = ($avg_nh * $w_nh) + ($avg_uh * $w_uh) + ($avg_sts * $w_sts);
                        $rata_formatif = round(($avg_nh + $avg_uh) / 2, 1);
                        $rata_sumatif = round($avg_sts, 1);
                    } else {
                        $w_nh  = $bobot['akhir_semester']['nh'] / 100;
                        $w_uh  = $bobot['akhir_semester']['uh'] / 100;
                        $w_sts = $bobot['akhir_semester']['sts'] / 100;
                        $w_sas = $bobot['akhir_semester']['sas'] / 100;

                        $val_sts = $avg_pas;
                        $val_sas = $avg_sas;

                        $c_nh  = floor(round($avg_nh * $w_nh, 4) * 10) / 10;
                        $c_uh  = floor(round($avg_uh * $w_uh, 4) * 10) / 10;
                        $c_sts = floor(round($val_sts * $w_sts, 4) * 10) / 10;
                        $c_sas = floor(round($val_sas * $w_sas, 4) * 10) / 10;

                        $kalkulasi = $c_nh + $c_uh + $c_sts + $c_sas;
                        $rata_formatif = round(($avg_nh + $avg_uh) / 2, 1);
                        $rata_sumatif = round(($val_sts + $val_sas) / 2, 1);
                    }

                    $nilai_akhir = number_format($kalkulasi, 1, '.', '');

                    $predikat = '-';
                    if (!empty($aturanPredikat)) {
                        foreach ($aturanPredikat as $aturan) {
                            if (floor($nilai_akhir) >= $aturan['nilai_min'] && floor($nilai_akhir) <= $aturan['nilai_max']) {
                                $predikat = $aturan['deskripsi_predikat'];
                                break;
                            }
                        }
                        if ($predikat === '-') {
                            $predikat = 'Perlu Bimbingan';
                        }
                    } else {
                        if ($nilai_akhir >= 90) $predikat = 'Sangat Baik';
                        elseif ($nilai_akhir >= 80) $predikat = 'Baik';
                        elseif ($nilai_akhir >= 70) $predikat = 'Cukup';
                        else $predikat = 'Perlu Bimbingan';
                    }

                    // Deskripsi Tertinggi & Terendah
                    $max_score = -1;
                    $min_score = 101;
                    $max_pert = null;
                    $min_pert = null;

                    foreach ($lm_scores as $pert => $data_score) {
                        $avg_pert = $data_score['sum'] / $data_score['count'];
                        if ($avg_pert > $max_score) {
                            $max_score = $avg_pert;
                            $max_pert = $pert;
                        }
                        if ($avg_pert < $min_score) {
                            $min_score = $avg_pert;
                            $min_pert = $pert;
                        }
                    }

                    $deskripsi_tertinggi = '';
                    $deskripsi_terendah = '';

                    foreach ($allLMs as $lm) {
                        $angka_lm = (int) preg_replace('/[^0-9]/', '', $lm['kode_lm']);
                        $materi = trim(lcfirst($lm['deskripsi_lm'] ?? ''));

                        if ($materi) {
                            if ($angka_lm === $max_pert) {
                                $deskripsi_tertinggi = "Menunjukkan penguasaan yang sangat baik dalam " . $materi;
                            }
                            if ($angka_lm === $min_pert && $min_score < 75) {
                                $deskripsi_terendah = "Perlu pendampingan lebih lanjut dalam " . $materi;
                            }
                        }
                    }

                    $dataUpsert = [
                        'siswa_id'        => $siswa_id,
                        'rombel_id'       => $rombel_id,
                        'tahun_ajaran_id' => $ta_id,
                        'mapel_id'        => $mapel_id,
                        'kategori'        => $kategori,
                        'rata_formatif'   => $rata_formatif,
                        'rata_sumatif'    => $rata_sumatif,
                        'nilai_akhir'     => $nilai_akhir,
                        'predikat'        => $predikat
                    ];

                    if ($db->fieldExists('deskripsi_tertinggi', 'nilai_rapor')) {
                        $dataUpsert['deskripsi_tertinggi'] = $deskripsi_tertinggi;
                    }
                    if ($db->fieldExists('deskripsi_terendah', 'nilai_rapor')) {
                        $dataUpsert['deskripsi_terendah'] = $deskripsi_terendah;
                    }

                    $existing = $db->table('nilai_rapor')->where([
                        'siswa_id'        => $siswa_id,
                        'tahun_ajaran_id' => $ta_id,
                        'mapel_id'        => $mapel_id,
                        'kategori'        => $kategori
                    ])->get()->getRowArray();

                    if ($existing) {
                        $db->table('nilai_rapor')->where('id', $existing['id'])->update($dataUpsert);
                    } else {
                        $db->table('nilai_rapor')->insert($dataUpsert);
                    }
                }
                $jumlah_mapel_disinkron++;
            }

            $db->transCommit();
            return $this->response->setJSON([
                'status' => 'success',
                'message' => "Berhasil mensinkronisasi nilai rapor untuk {$jumlah_mapel_disinkron} mata pelajaran secara massal."
            ]);

        } catch (\Throwable $e) {
            $db->transRollback();
            return $this->response->setJSON(['status' => 'error', 'message' => 'Error Server: ' . $e->getMessage()]);
        }
    }

    private function _getPembagiDinamis($formatifs)
    {
        $max_nh_pert = 0;
        $max_uh_pert = 0;

        foreach ($formatifs as $f) {
            $jenis = strtoupper(trim($f['jenis_penilaian'] ?? ''));
            $pert = (int)($f['pertemuan'] ?? 0);
            $nilai = (float)($f['nilai_angka'] ?? 0);

            if ($nilai > 0) {
                if (strpos($jenis, 'UH') !== false || strpos($jenis, 'ULANGAN') !== false) {
                    if ($pert > $max_uh_pert) $max_uh_pert = $pert;
                } else {
                    if ($pert > $max_nh_pert) $max_nh_pert = $pert;
                }
            }
        }

        return [
            'nh' => $max_nh_pert > 0 ? $max_nh_pert : 1,
            'uh' => $max_uh_pert > 0 ? $max_uh_pert : 1,
            'ada_nh' => $max_nh_pert > 0,
            'ada_uh' => $max_uh_pert > 0
        ];
    }
}