<?php

namespace App\Controllers\GuruMapel;

use App\Controllers\GuruMapelBaseController;

class NilaiRaporController extends GuruMapelBaseController
{
    public function index()
    {
        $db = \Config\Database::connect();

        $sekolah = $db->table('sekolah')->select('warna_primary, warna_secondary')->get()->getRowArray();
        $warna_primary = $sekolah ? $sekolah['warna_primary'] : '#3b82f6';
        $warna_secondary = $sekolah ? $sekolah['warna_secondary'] : '#eff6ff';

        $tahun_ajaran_list = $db->table('tahun_ajaran')
            ->orderBy('tahun', 'DESC')
            ->orderBy('semester', 'DESC')
            ->get()->getResultArray();

        $ta_aktif = $db->table('tahun_ajaran')->where('status', 'Aktif')->get()->getRowArray();

        $data = [
            'navigations'       => $this->getSidebarMenu(),
            'title'             => 'Sinkronisasi Nilai Rapor',
            'user'              => session()->get('nama_lengkap') ?? session()->get('username') ?? 'Guru Mapel',
            'tahun_ajaran_list' => $tahun_ajaran_list,
            'id_ta_aktif'       => $ta_aktif ? $ta_aktif['id'] : 0,
            'color'             => [
                'warna_primary'   => $warna_primary,
                'warna_secondary' => $warna_secondary,
            ]
        ];

        return view('GuruMapel/nilai-rapor', $data);
    }

    public function getPenugasan($ta_id)
    {
        $db = \Config\Database::connect();
        $userId = session()->get('id') ?? session()->get('user_id');
        $guru = $db->table('guru_tendik')->where('user_id', $userId)->get()->getRowArray();

        if (!$guru) {
            return $this->response->setJSON([]);
        }

        $guruId = $guru['id'];
        $hasil_penugasan = [];

        if ($db->tableExists('guru_mapel')) {
            $gurumapel = $db->table('guru_mapel gm')
                ->select('gm.rombel_id, r.nama_rombel, gm.mapel_id, m.nama_mapel')
                ->join('rombel r', 'r.id = gm.rombel_id', 'left')
                ->join('mata_pelajaran m', 'm.id = gm.mapel_id', 'left')
                ->where('gm.guru_id', $guruId)
                ->where('gm.tahun_ajaran_id', $ta_id)
                ->where('gm.status', 'active')
                ->get()->getResultArray();

            foreach ($gurumapel as $gm) {
                if (!empty($gm['rombel_id']) && !empty($gm['mapel_id'])) {
                    $key = $gm['rombel_id'] . '_' . $gm['mapel_id'];
                    $hasil_penugasan[$key] = $gm;
                }
            }
        }

        if ($db->tableExists('jadwal_pelajaran')) {
            $jadwal = $db->table('jadwal_pelajaran jp')
                ->select('jp.rombel_id, r.nama_rombel, jp.mapel_id, m.nama_mapel')
                ->join('rombel r', 'r.id = jp.rombel_id', 'left')
                ->join('mata_pelajaran m', 'm.id = jp.mapel_id', 'left')
                ->where('jp.guru_id', $guruId)
                ->where('jp.id_tahun_ajaran', $ta_id)
                ->get()->getResultArray();

            foreach ($jadwal as $j) {
                if (!empty($j['rombel_id']) && !empty($j['mapel_id'])) {
                    $key = $j['rombel_id'] . '_' . $j['mapel_id'];
                    if (!isset($hasil_penugasan[$key])) {
                        $hasil_penugasan[$key] = $j;
                    }
                }
            }
        }

        $final_data = array_values($hasil_penugasan);
        usort($final_data, function ($a, $b) {
            return strcmp($a['nama_rombel'], $b['nama_rombel']);
        });

        return $this->response->setJSON($final_data);
    }

    // =========================================================================
    // SMART ALGORITHM: Menghitung Pembagi (Divisor) yang Paling Adil
    // Menggunakan Max Progress Kelas agar anak yang rajin tidak terkena penalti 
    // jika guru belum selesai mengajar materi selanjutnya.
    // =========================================================================
    private function _getPembagiDinamis($formatifs)
    {
        $max_nh_pert = 0;
        $max_uh_pert = 0;

        foreach ($formatifs as $f) {
            $jenis = strtoupper(trim($f['jenis_penilaian'] ?? ''));
            $pert = (int)($f['pertemuan'] ?? 0);
            $nilai = (float)($f['nilai_angka'] ?? 0);

            // SMART CLEANER: Hanya hitung sebagai progres jika ada nilai > 0
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

    public function getData()
    {
        $ta_id     = $this->request->getPost('tahun_ajaran_id');
        $rombel_id = $this->request->getPost('rombel_id');
        $mapel_id  = $this->request->getPost('mapel_id');
        $kategori  = $this->request->getPost('kategori');

        if (empty($ta_id) || empty($rombel_id) || empty($mapel_id) || empty($kategori)) {
            return $this->response->setJSON([]);
        }

        $db = \Config\Database::connect();

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

        // 🚀 MENGGUNAKAN MESIN WAKTU
        $ta_info = $db->table('tahun_ajaran')->where('id', $ta_id)->get()->getRowArray();
        $semester = $ta_info ? $ta_info['semester'] : 'Ganjil';

        $data_siswa = $db->table('anggota_rombel ar')
            ->select('s.id as siswa_id, s.nama_lengkap as nama_siswa, s.nisn, nr.nilai_akhir, nr.predikat')
            ->join('siswa s', 's.id = ar.siswa_id')
            ->join('nilai_rapor nr', "nr.siswa_id = s.id AND nr.mapel_id = {$mapel_id} AND nr.tahun_ajaran_id = {$ta_id} AND nr.kategori = '{$kategori}'", 'left')
            ->where('ar.rombel_id', $rombel_id)
            ->where('ar.tahun_ajaran_id', $ta_id)
            ->where('ar.semester', $semester)
            ->where('s.status_siswa', 'Aktif')
            ->orderBy('s.nama_lengkap', 'ASC')
            ->get()->getResultArray();

        $qFormatif = $db->table('nilai_formatif')
            ->where('mapel_id', $mapel_id)
            ->where('tahun_ajaran_id', $ta_id)
            ->where('rombel_id', $rombel_id);

        if ($db->fieldExists('kategori', 'nilai_formatif')) {
            $qFormatif->groupStart()
                ->where('kategori', $kategori)
                ->orWhere('kategori', $kategori === 'Tengah Semester' ? 'Tengah' : 'Akhir')
                ->groupEnd();
        }

        $formatifs = $qFormatif->get()->getResultArray();

        $qSumatif = $db->table('nilai_sumatif')
            ->where('mapel_id', $mapel_id)
            ->where('tahun_ajaran_id', $ta_id);

        if ($db->fieldExists('kategori', 'nilai_sumatif')) {
            $qSumatif->groupStart()
                ->where('kategori', $kategori)
                ->orWhere('kategori', $kategori === 'Tengah Semester' ? 'Tengah' : 'Akhir')
                ->orWhere('kategori', '')
                ->orWhere('kategori', null)
                ->groupEnd();
        }

        $sumatifs = $qSumatif->get()->getResultArray();

        // Deteksi Progres Dinamis Kelas Ini
        $pembagi = $this->_getPembagiDinamis($formatifs);

        foreach ($data_siswa as &$s) {
            $siswa_id = $s['siswa_id'];

            $sum_nh = 0;
            $sum_uh = 0;
            $sum_sts = 0;
            $count_sts = 0;
            $sum_pas = 0;
            $count_pas = 0;
            $sum_sas = 0;
            $count_sas = 0;

            foreach ($formatifs as $f) {
                if ($f['siswa_id'] == $siswa_id) {
                    $jenis = strtoupper(trim($f['jenis_penilaian'] ?? ''));
                    $nilai = (float)($f['nilai_angka'] ?? 0);

                    if (strpos($jenis, 'UH') !== false || strpos($jenis, 'ULANGAN') !== false) {
                        $sum_uh += $nilai;
                    } else {
                        $sum_nh += $nilai;
                    }
                }
            }

            foreach ($sumatifs as $sum) {
                if ($sum['siswa_id'] == $siswa_id) {
                    $jenis = strtoupper(trim($sum['jenis_sumatif'] ?? ($sum['jenis_penilaian'] ?? '')));
                    $nilai = isset($sum['nilai']) ? (float)$sum['nilai'] : (float)($sum['nilai_angka'] ?? 0);

                    if ($kategori === 'Tengah Semester') {
                        if (strpos($jenis, 'STS') !== false || strpos($jenis, 'PTS') !== false) {
                            $sum_sts += $nilai;
                            $count_sts++;
                        }
                    } else {
                        if (strpos($jenis, 'PAS') !== false) {
                            $sum_pas += $nilai;
                            $count_pas++;
                        } elseif (strpos($jenis, 'SAS') !== false) {
                            $sum_sas += $nilai;
                            $count_sas++;
                        }
                    }
                }
            }

            // Rata-rata Murni (Sebelum diberi Bobot)
            $avg_nh  = $sum_nh / $pembagi['nh'];
            $avg_uh  = $sum_uh / $pembagi['uh'];
            $avg_sts = $count_sts > 0 ? ($sum_sts / $count_sts) : 0;
            $avg_pas = $count_pas > 0 ? ($sum_pas / $count_pas) : 0;
            $avg_sas = $count_sas > 0 ? ($sum_sas / $count_sas) : 0;

            // Kirim nilai mentah ke View untuk referensi/transparansi (opsional)
            $s['rata_nh']  = round($avg_nh, 1);
            $s['rata_uh']  = round($avg_uh, 1);
            $s['rata_sts'] = round($avg_sts, 1);
            $s['rata_pas'] = round($avg_pas, 1);
            $s['rata_sas'] = round($avg_sas, 1);

            // --- 🚀 KALKULASI DINAMIS BERDASARKAN DATABASE ---
            if ($kategori === 'Tengah Semester') {
                $w_nh  = $bobot['tengah_semester']['nh'] / 100;
                $w_uh  = $bobot['tengah_semester']['uh'] / 100;
                $w_sts = $bobot['tengah_semester']['sts'] / 100;

                $kalkulasi = ($avg_nh * $w_nh) + ($avg_uh * $w_uh) + ($avg_sts * $w_sts);
            } else {
                $w_nh  = $bobot['akhir_semester']['nh'] / 100;
                $w_uh  = $bobot['akhir_semester']['uh'] / 100;
                $w_sts = $bobot['akhir_semester']['sts'] / 100;
                $w_sas = $bobot['akhir_semester']['sas'] / 100;

                $avg_ujian_akhir = $count_pas > 0 ? $avg_pas : ($count_sas > 0 ? $avg_sas : 0);
                $kalkulasi = ($avg_nh * $w_nh) + ($avg_uh * $w_uh) + ($avg_sts * $w_sts) + ($avg_ujian_akhir * $w_sas);
            }

            $nilai_akhir = round($kalkulasi);
            $s['nilai_akhir'] = $nilai_akhir;

            // --- 🚀 PREDIKAT DINAMIS ---
            $predikat = '-';
            if (!empty($aturanPredikat)) {
                foreach ($aturanPredikat as $aturan) {
                    if ($nilai_akhir >= $aturan['nilai_min'] && $nilai_akhir <= $aturan['nilai_max']) {
                        $predikat = $aturan['deskripsi_predikat']; // cth: "Sangat Baik"
                        break;
                    }
                }
                if ($predikat === '-') {
                    $predikat = 'Perlu Bimbingan';
                }
            } else {
                // Fallback jika database aturan_nilai kosong
                if ($nilai_akhir >= 90) $predikat = 'Sangat Baik';
                elseif ($nilai_akhir >= 80) $predikat = 'Baik';
                elseif ($nilai_akhir >= 70) $predikat = 'Cukup';
                else $predikat = 'Perlu Bimbingan';
            }
            $s['predikat'] = $predikat;
        }

        return $this->response->setJSON($data_siswa);
    }

    public function syncNilai()
    {
        $ta_id     = $this->request->getPost('tahun_ajaran_id');
        $rombel_id = $this->request->getPost('rombel_id');
        $mapel_id  = $this->request->getPost('mapel_id');
        $kategori  = $this->request->getPost('kategori');
        $kategoriDB = (stripos($kategori, 'tengah') !== false) ? 'Tengah' : 'Akhir';

        if (empty($ta_id) || empty($rombel_id) || empty($mapel_id) || empty($kategori)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Data filter tidak lengkap.']);
        }

        $db = \Config\Database::connect();

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

        $ta_data = $db->table('tahun_ajaran')->where('id', $ta_id)->get()->getRowArray();
        $semester = $ta_data ? $ta_data['semester'] : 'Ganjil';

        $siswa_list = $db->table('siswa')->select('id')->where('rombel_id', $rombel_id)->where('status_siswa', 'Aktif')->get()->getResultArray();

        if (empty($siswa_list)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Tidak ada data siswa aktif di kelas ini.']);
        }

        $rombel = $db->table('rombel')->where('id', $rombel_id)->get()->getRowArray();
        $tingkatClean = 0;
        if ($rombel) {
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
        }

        // Ambil Data Master LM untuk Auto Deskripsi Capaian
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

        $db->transBegin();
        $jumlah_disinkron = 0;

        try {
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
                    ->orWhere('kategori', $kategoriDB)
                    ->groupEnd();
            }

            $all_sumatif = $qAllSumatif->get()->getResultArray();

            // Deteksi Progres Dinamis
            $pembagi = $this->_getPembagiDinamis($all_formatif);

            foreach ($siswa_list as $siswa) {
                $siswa_id = $siswa['id'];

                $sum_nh = 0;
                $sum_uh = 0;
                $sum_sts = 0;
                $count_sts = 0;
                $sum_pas = 0;
                $count_pas = 0;
                $sum_sas = 0;
                $count_sas = 0;

                // Track progress pertemuan tiap anak untuk Deskripsi Tertinggi/Terendah
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

                        // Mengumpulkan nilai per pertemuan untuk mencari nilai Min/Max Kurikulum Merdeka
                        if (!isset($lm_scores[$pert])) $lm_scores[$pert] = ['sum' => 0, 'count' => 0];
                        $lm_scores[$pert]['sum'] += $nilai;
                        $lm_scores[$pert]['count']++;
                    }
                }

                foreach ($all_sumatif as $s) {
                    if ($s['siswa_id'] == $siswa_id) {
                        $jenis = strtoupper(trim($s['jenis_sumatif'] ?? ($s['jenis_penilaian'] ?? '')));
                        $nilai = isset($s['nilai']) ? (float)$s['nilai'] : (float)($s['nilai_angka'] ?? 0);

                        if ($kategori === 'Tengah Semester') {
                            if (strpos($jenis, 'STS') !== false || strpos($jenis, 'PTS') !== false) {
                                $sum_sts += $nilai;
                                $count_sts++;
                            }
                        } else {
                            if (strpos($jenis, 'PAS') !== false) {
                                $sum_pas += $nilai;
                                $count_pas++;
                            } elseif (strpos($jenis, 'SAS') !== false) {
                                $sum_sas += $nilai;
                                $count_sas++;
                            }
                        }
                    }
                }

                // Rata-rata Murni
                $avg_nh  = $sum_nh / $pembagi['nh'];
                $avg_uh  = $sum_uh / $pembagi['uh'];
                $avg_sts = $count_sts > 0 ? ($sum_sts / $count_sts) : 0;
                $avg_pas = $count_pas > 0 ? ($sum_pas / $count_pas) : 0;
                $avg_sas = $count_sas > 0 ? ($sum_sas / $count_sas) : 0;

                // --- 🚀 IMPLEMENTASI RUMUS BARU SAAT SINKRONISASI KE DB ---
                if ($kategori === 'Tengah Semester') {
                    $w_nh  = $bobot['tengah_semester']['nh'] / 100;
                    $w_uh  = $bobot['tengah_semester']['uh'] / 100;
                    $w_sts = $bobot['tengah_semester']['sts'] / 100;

                    $kalkulasi = ($avg_nh * $w_nh) + ($avg_uh * $w_uh) + ($avg_sts * $w_sts);

                    // Simpan rata-rata ke kolom DB (untuk archive)
                    $rata_formatif = round(($avg_nh + $avg_uh) / 2, 1);
                    $rata_sumatif = round($avg_sts, 1);
                } else {
                    $w_nh  = $bobot['akhir_semester']['nh'] / 100;
                    $w_uh  = $bobot['akhir_semester']['uh'] / 100;
                    $w_sts = $bobot['akhir_semester']['sts'] / 100;
                    $w_sas = $bobot['akhir_semester']['sas'] / 100;

                    $avg_ujian_akhir = $count_pas > 0 ? $avg_pas : ($count_sas > 0 ? $avg_sas : 0);
                    $kalkulasi = ($avg_nh * $w_nh) + ($avg_uh * $w_uh) + ($avg_sts * $w_sts) + ($avg_ujian_akhir * $w_sas);

                    // Simpan rata-rata ke kolom DB (untuk archive)
                    $rata_formatif = round(($avg_nh + $avg_uh) / 2, 1);
                    $rata_sumatif = round(($avg_sts + $avg_ujian_akhir) / 2, 1);
                }

                $nilai_akhir = round($kalkulasi);

                // --- 🚀 PREDIKAT DINAMIS ---
                $predikat = '-';
                if (!empty($aturanPredikat)) {
                    foreach ($aturanPredikat as $aturan) {
                        if ($nilai_akhir >= $aturan['nilai_min'] && $nilai_akhir <= $aturan['nilai_max']) {
                            $predikat = $aturan['deskripsi_predikat']; // Cth: "Sangat Baik"
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

                // ===============================================================
                // EKSTRAKSI OTOMATIS DESKRIPSI TERTINGGI & TERENDAH 
                // ===============================================================
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
                        // Pastikan tidak memberikan teks "perlu bimbingan" jika nilainya sudah cukup bagus (Misal minimum dia dapat 80)
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

                // Simpan secara otomatis jika tabel mendukung kolom Deskripsi Capaian
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

                $jumlah_disinkron++;
            }

            $db->transCommit();
            return $this->response->setJSON([
                'status' => 'success',
                'message' => "Selesai! Berhasil mensinkronisasi nilai rapor untuk $jumlah_disinkron siswa. (Kalkulasi Otomatis Berdasarkan Aturan DB)"
            ]);
        } catch (\Throwable $e) {
            $db->transRollback();
            return $this->response->setJSON(['status' => 'error', 'message' => 'Error Server: ' . $e->getMessage()]);
        }
    }
}
