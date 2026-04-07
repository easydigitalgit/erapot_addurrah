<?php

namespace App\Controllers\GuruMapel;

use App\Controllers\GuruMapelBaseController;

class DashboardController extends GuruMapelBaseController
{
    public function index(): string
    {
        $db = \Config\Database::connect();
        $userId = session()->get('id');

        // =========================================================
        // 1. AMBIL TAHUN AJARAN AKTIF
        // =========================================================
        $ta_aktif = $db->table('tahun_ajaran')->where('status', 'Aktif')->get()->getRowArray();
        $id_ta_aktif = $ta_aktif ? $ta_aktif['id'] : 0;
        $tahun_ajaran_str = $ta_aktif ? $ta_aktif['tahun'] : '';
        $semester_str = $ta_aktif ? $ta_aktif['semester'] : '';

        // =========================================================
        // 2. CARI IDENTITAS GURU
        // =========================================================
        $dataGuru = $db->table('guru_tendik')
            ->select('id, nama_lengkap')
            ->where('user_id', $userId)
            ->get()
            ->getRowArray();

        $guruId = $dataGuru ? $dataGuru['id'] : 0;

        // =========================================================
        // 3. AMBIL PENUGASAN GURU
        // =========================================================
        $kelas_assigned = $db->table('guru_mapel gm')
            ->select('gm.rombel_id, gm.mapel_id, gm.jam_per_minggu, m.nama_mapel, r.nama_rombel, r.tingkat')
            ->join('mata_pelajaran m', 'm.id = gm.mapel_id', 'left')
            ->join('rombel r', 'r.id = gm.rombel_id', 'left')
            ->where(['gm.guru_id' => $guruId, 'gm.status' => 'active', 'r.id_tahun_ajaran' => $id_ta_aktif])
            ->get()->getResultArray();

        // =========================================================
        // 4. INISIALISASI COUNTER
        // =========================================================
        $total_siswa_keseluruhan = 0;
        $total_nilai_akumulasi = 0;
        $count_nilai = 0;
        $siswa_sudah_dinilai = 0;
        $harian_terisi = 0;
        $sumatif_terisi = 0;
        $proyek_terisi = 0;
        $siswa_kurang_perhatian = 0;

        $kelas_ajar_data = [];
        $rata_per_kelas = [];
        $semua_mapel = [];
        $mapel_utama = count($kelas_assigned) > 0 ? $kelas_assigned[0]['nama_mapel'] : 'Belum Diset';

        // =========================================================
        // 5. LOOPING KALKULASI DATA PER KELAS DARI TABEL SUMBER
        // =========================================================
        foreach ($kelas_assigned as $kelas) {
            if (!in_array($kelas['nama_mapel'], $semua_mapel)) {
                $semua_mapel[] = $kelas['nama_mapel'];
            }

            // Hitung siswa aktif di rombel
            $siswa_di_kelas = $db->table('siswa')
                ->select('id')
                ->where(['rombel_id' => $kelas['rombel_id'], 'status_siswa' => 'Aktif'])
                ->get()->getResultArray();

            $jml_siswa = count($siswa_di_kelas);
            $total_siswa_keseluruhan += $jml_siswa;

            $kelas_total_nilai = 0;
            $kelas_count_nilai = 0;
            $siswa_ids_graded_in_class = [];

            if ($jml_siswa > 0) {
                $siswa_ids = array_column($siswa_di_kelas, 'id');

                // A. HITUNG NILAI FORMATIF (Harian/Tugas)
                $formatif_data = [];
                if ($db->tableExists('nilai_formatif')) {
                    $formatif_data = $db->table('nilai_formatif')
                        ->where('mapel_id', $kelas['mapel_id'])
                        ->where('tahun_ajaran_id', $id_ta_aktif)
                        ->whereIn('siswa_id', $siswa_ids)
                        ->get()->getResultArray();
                }

                // B. HITUNG NILAI SUMATIF (UTS/UAS)
                $sumatif_data = [];
                if ($db->tableExists('nilai_sumatif')) {
                    $sumatif_data = $db->table('nilai_sumatif')
                        ->where('mapel_id', $kelas['mapel_id'])
                        ->where('tahun_ajaran_id', $id_ta_aktif)
                        ->whereIn('siswa_id', $siswa_ids)
                        ->get()->getResultArray();
                }

                // C. HITUNG NILAI PROYEK
                $proyek_data = [];
                if ($db->tableExists('nilai_proyek')) {
                    $proyek_data = $db->table('nilai_proyek')
                        ->join('penilaian_proyek pp', 'pp.id = nilai_proyek.proyek_id')
                        ->where('pp.mapel_id', $kelas['mapel_id'])
                        ->where('pp.tahun_ajaran_id', $id_ta_aktif)
                        ->whereIn('nilai_proyek.siswa_id', $siswa_ids)
                        ->get()->getResultArray();
                }

                // Menggabungkan dan Menghitung Rekap Per Siswa
                foreach ($siswa_ids as $sid) {
                    $nilai_anak = [];

                    // Cek Formatif Siswa Ini
                    $f_anak = array_filter($formatif_data, function ($f) use ($sid) {
                        return $f['siswa_id'] == $sid;
                    });
                    if (count($f_anak) > 0) {
                        $harian_terisi++;
                        $siswa_ids_graded_in_class[$sid] = true;
                        foreach ($f_anak as $fa) {
                            $nilai_anak[] = (float)$fa['nilai_angka'];
                        }
                    }

                    // Cek Sumatif Siswa Ini
                    $s_anak = array_filter($sumatif_data, function ($s) use ($sid) {
                        return $s['siswa_id'] == $sid;
                    });
                    if (count($s_anak) > 0) {
                        $sumatif_terisi++;
                        $siswa_ids_graded_in_class[$sid] = true;
                        foreach ($s_anak as $sa) {
                            $nilai_anak[] = (float)$sa['nilai'];
                        }
                    }

                    // Cek Proyek Siswa Ini
                    $p_anak = array_filter($proyek_data, function ($p) use ($sid) {
                        return $p['siswa_id'] == $sid;
                    });
                    if (count($p_anak) > 0) {
                        $proyek_terisi++;
                        $siswa_ids_graded_in_class[$sid] = true;
                        foreach ($p_anak as $pa) {
                            $nilai_anak[] = (float)$pa['nilai_akhir'];
                        }
                    }

                    // Hitung rata-rata gabungan jika anak ini punya nilai
                    if (count($nilai_anak) > 0) {
                        $rata = array_sum($nilai_anak) / count($nilai_anak);
                        $total_nilai_akumulasi += $rata;
                        $count_nilai++;

                        $kelas_total_nilai += $rata;
                        $kelas_count_nilai++;

                        if ($rata < 70) $siswa_kurang_perhatian++;
                    }
                }

                $siswa_sudah_dinilai += count($siswa_ids_graded_in_class);

                // AMBIL JADWAL UNTUK KELAS & MAPEL INI
                $jadwal_mapel_ini = [];
                if ($db->tableExists('jadwal_pelajaran')) {
                    $jadwal_mapel_ini = $db->table('jadwal_pelajaran')
                        ->select('hari, jam_mulai, jam_selesai')
                        ->where([
                            'guru_id'         => $guruId,
                            'rombel_id'       => $kelas['rombel_id'],
                            'mapel_id'        => $kelas['mapel_id'],
                            'id_tahun_ajaran' => $id_ta_aktif
                        ])
                        ->orderBy('FIELD(hari, "Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu", "Minggu")')
                        ->orderBy('jam_mulai', 'ASC')
                        ->get()->getResultArray();
                }

                // Masukkan Data Kelas untuk Card
                $kelas_ajar_data[] = [
                    'id'           => $kelas['rombel_id'],
                    'nama_kelas'   => $kelas['nama_rombel'],
                    'tingkat'      => $kelas['tingkat'],
                    'jumlah_siswa' => $jml_siswa,
                    'progress'     => round((count($siswa_ids_graded_in_class) / $jml_siswa) * 100),
                    'jadwal'       => $jadwal_mapel_ini // <-- Inject jadwal ke array data
                ];

                if ($kelas_count_nilai > 0) {
                    $rata_per_kelas[] = [
                        'nama' => 'Kelas ' . $kelas['nama_rombel'],
                        'rata' => number_format($kelas_total_nilai / $kelas_count_nilai, 1)
                    ];
                }
            }
        }

        // =========================================================
        // 6. KALKULASI FINAL PERSENTASE (LUAR LOOP)
        // =========================================================
        $siswa_belum_dinilai = $total_siswa_keseluruhan - $siswa_sudah_dinilai;
        $rata_rata_semua = $count_nilai > 0 ? number_format($total_nilai_akumulasi / $count_nilai, 1) : 0;
        $persen_lengkap = $total_siswa_keseluruhan > 0 ? round(($siswa_sudah_dinilai / $total_siswa_keseluruhan) * 100) : 0;

        $pct_harian = $total_siswa_keseluruhan > 0 ? round(($harian_terisi / $total_siswa_keseluruhan) * 100) : 0;
        $pct_sumatif = $total_siswa_keseluruhan > 0 ? round(($sumatif_terisi / $total_siswa_keseluruhan) * 100) : 0;
        $pct_proyek = $total_siswa_keseluruhan > 0 ? round(($proyek_terisi / $total_siswa_keseluruhan) * 100) : 0;

        // =========================================================
        // 7. JADWAL HARI INI
        // =========================================================
        date_default_timezone_set('Asia/Jakarta');
        $hariIndo = ['Sunday' => 'Minggu', 'Monday' => 'Senin', 'Tuesday' => 'Selasa', 'Wednesday' => 'Rabu', 'Thursday' => 'Kamis', 'Friday' => 'Jumat', 'Saturday' => 'Sabtu'][date('l')];

        $jadwal_hari_ini = [];
        if ($db->tableExists('jadwal_pelajaran')) {
            $jadwal_hari_ini = $db->table('jadwal_pelajaran jp')
                ->select('jp.jam_ke, jp.jam_mulai, jp.jam_selesai, r.nama_rombel as nama_kelas')
                ->join('rombel r', 'r.id = jp.rombel_id', 'left')
                ->where(['jp.guru_id' => $guruId, 'jp.hari' => $hariIndo, 'jp.id_tahun_ajaran' => $id_ta_aktif])
                ->orderBy('jp.jam_mulai', 'ASC')
                ->get()->getResultArray();
        }

        // =========================================================
        // 8. SIAPKAN DATA KE VIEW
        // =========================================================
        $data = [
            'user'           => $dataGuru['nama_lengkap'] ?? session()->get('username') ?? 'Guru Mapel',
            'navigations'    => $this->getSidebarMenu(),
            'color'          => $this->getColor(),
            'jumlah_mapel'   => count($semua_mapel),
            'list_mapel'     => empty($semua_mapel) ? 'Belum Ada Mapel' : implode(', ', $semua_mapel),
            'mapel_utama'    => $mapel_utama,
            'jumlah_kelas'   => count($kelas_assigned),
            'total_siswa'    => $total_siswa_keseluruhan,
            'quick_stats'    => [
                'persen_lengkap' => $persen_lengkap > 100 ? 100 : $persen_lengkap,
                'belum_dinilai'  => $siswa_belum_dinilai < 0 ? 0 : $siswa_belum_dinilai,
                'rata_rata'      => $rata_rata_semua
            ],
            'status_input'   => [
                'harian_pct'   => $pct_harian > 100 ? 100 : $pct_harian,
                'harian_done'  => $harian_terisi > $total_siswa_keseluruhan ? $total_siswa_keseluruhan : $harian_terisi,
                'sumatif_pct'  => $pct_sumatif > 100 ? 100 : $pct_sumatif,
                'sumatif_done' => $sumatif_terisi > $total_siswa_keseluruhan ? $total_siswa_keseluruhan : $sumatif_terisi,
                'proyek_pct'   => $pct_proyek > 100 ? 100 : $pct_proyek,
                'proyek_done'  => $proyek_terisi > $total_siswa_keseluruhan ? $total_siswa_keseluruhan : $proyek_terisi,
            ],
            'kelas_ajar'      => $kelas_ajar_data,
            'jadwal_hari_ini' => $jadwal_hari_ini,
            'insights'        => [
                'rata_kelas'       => $rata_per_kelas,
                'kurang_perhatian' => $siswa_kurang_perhatian,
                'proyek_kosong'    => ($total_siswa_keseluruhan - $proyek_terisi) < 0 ? 0 : ($total_siswa_keseluruhan - $proyek_terisi)
            ]
        ];

        return view('GuruMapel/dashboard', $data);
    }
}
