<?php

namespace App\Controllers\GuruMapel;

use App\Controllers\GuruMapelBaseController;

class DashboardController extends GuruMapelBaseController
{
    public function index(): string
    {
        $db = \Config\Database::connect();
        
        $userId = session()->get('id');
        
        // PENTING: Pastikan ini sama dengan data di tabel guru_mapel! 
        // Di screenshot database Anda sebelumnya, datanya adalah 2026/2027.
        $tahun_ajaran = '2026/2027'; 
        $semester = 'Genap';         

        // =========================================================
        // 1. CARI IDENTITAS GURU (GURU_ID) BERDASARKAN USER_ID LOGIN
        // =========================================================
        $dataGuru = $db->table('guru_tendik')
                       ->select('id, nama_lengkap')
                       ->where('user_id', $userId)
                       ->get()
                       ->getRowArray();
                       
        $guruId = $dataGuru ? $dataGuru['id'] : 0; 

        // =========================================================
        // 2. Ambil Penugasan Guru (MENGGUNAKAN GURU_ID)
        // =========================================================
        $builder = $db->table('guru_mapel gm');
        $builder->select('gm.id as penugasan_id, gm.rombel_id, gm.mapel_id, gm.jam_per_minggu, m.nama_mapel, r.nama_rombel, r.tingkat'); 
        $builder->join('mata_pelajaran m', 'm.id = gm.mapel_id', 'left');
        $builder->join('rombel r', 'r.id = gm.rombel_id', 'left'); 
        
        $builder->where('gm.guru_id', $guruId); 
        $builder->where('gm.tahun_ajaran', $tahun_ajaran); // Pastikan tahun ajaran cocok!
        $kelas_assigned = $builder->get()->getResultArray();

        $mapel_utama = count($kelas_assigned) > 0 ? $kelas_assigned[0]['nama_mapel'] : 'Belum Diset';
        
        $total_siswa_keseluruhan = 0;
        $total_nilai_akumulasi = 0;
        $count_nilai = 0;
        
        $siswa_sudah_dinilai = 0;
        $siswa_belum_dinilai = 0;

        $harian_terisi = 0;
        $sumatif_terisi = 0;
        $proyek_terisi = 0;

        $kelas_ajar_data = [];
        $rata_per_kelas = [];
        $siswa_kurang_perhatian = 0;

        // =========================================================
        // 3. Kalkulasi Data per Kelas
        // =========================================================
        foreach ($kelas_assigned as $kelas) {
            $siswa_di_kelas = $db->table('siswa')
                                 ->select('id')
                                 ->where('rombel_id', $kelas['rombel_id'])
                                 ->where('status_siswa', 'Aktif')
                                 ->get()->getResultArray();
            
            $jml_siswa = count($siswa_di_kelas);
            $total_siswa_keseluruhan += $jml_siswa;

            $kelas_progress = 0;
            $kelas_total_nilai = 0;
            $kelas_count_nilai = 0;

            if ($jml_siswa > 0) {
                $siswa_ids = array_column($siswa_di_kelas, 'id');

                $nilai_data = $db->table('nilai_komponen')
                                 ->whereIn('siswa_id', $siswa_ids)
                                 ->where('mapel_id', $kelas['mapel_id'])
                                 ->where('tahun_ajaran', $tahun_ajaran)
                                 ->where('semester', $semester)
                                 ->get()->getResultArray();

                $siswa_graded = [];
                
                foreach ($nilai_data as $n) {
                    $harian = (int)$n['harian'];
                    $uts = (int)$n['uts'];
                    $uas = (int)$n['uas'];
                    $proyek = (int)$n['proyek'];

                    if ($harian > 0) $harian_terisi++;
                    if ($uts > 0 || $uas > 0) $sumatif_terisi++;
                    if ($proyek > 0) $proyek_terisi++;

                    $komponen_count = 0;
                    $sum_nilai = 0;
                    if ($harian > 0) { $sum_nilai += $harian; $komponen_count++; }
                    if ($uts > 0) { $sum_nilai += $uts; $komponen_count++; }
                    if ($uas > 0) { $sum_nilai += $uas; $komponen_count++; }
                    if ($proyek > 0) { $sum_nilai += $proyek; $komponen_count++; }

                    if ($komponen_count > 0) {
                        $rata_anak = $sum_nilai / $komponen_count;
                        $total_nilai_akumulasi += $rata_anak;
                        $count_nilai++;

                        $kelas_total_nilai += $rata_anak;
                        $kelas_count_nilai++;

                        if ($rata_anak < 70) $siswa_kurang_perhatian++;
                        
                        if (!in_array($n['siswa_id'], $siswa_graded)) {
                            $siswa_graded[] = $n['siswa_id'];
                            $siswa_sudah_dinilai++;
                        }
                    }
                }
                
                $kelas_progress = round((count($siswa_graded) / $jml_siswa) * 100);
                
                if ($kelas_count_nilai > 0) {
                    $rata_per_kelas[] = [
                        'nama' => 'Kelas ' . $kelas['nama_rombel'],
                        'rata' => number_format($kelas_total_nilai / $kelas_count_nilai, 1)
                    ];
                }
            }

            $kelas_ajar_data[] = [
                'id' => $kelas['rombel_id'],
                'nama_kelas' => $kelas['nama_rombel'],
                'tingkat' => $kelas['tingkat'] ?? '-',
                'jumlah_siswa' => $jml_siswa,
                'progress' => $kelas_progress
            ];
        }

        $siswa_belum_dinilai = $total_siswa_keseluruhan - $siswa_sudah_dinilai;
        $rata_rata_semua = $count_nilai > 0 ? number_format($total_nilai_akumulasi / $count_nilai, 1) : 0;
        $persen_lengkap = $total_siswa_keseluruhan > 0 ? round(($siswa_sudah_dinilai / $total_siswa_keseluruhan) * 100) : 0;

        $pct_harian = $total_siswa_keseluruhan > 0 ? round(($harian_terisi / $total_siswa_keseluruhan) * 100) : 0;
        $pct_sumatif = $total_siswa_keseluruhan > 0 ? round(($sumatif_terisi / $total_siswa_keseluruhan) * 100) : 0;
        $pct_proyek = $total_siswa_keseluruhan > 0 ? round(($proyek_terisi / $total_siswa_keseluruhan) * 100) : 0;

        // =========================================================
        // 4. AMBIL JADWAL HARI INI (MENGGUNAKAN GURU_ID)
        // =========================================================
        date_default_timezone_set('Asia/Jakarta');
        $hari_ini = date('N'); 
        $jadwal_hari_ini = [];
        
        if ($db->tableExists('jadwal_pelajaran')) {
            $jadwal_hari_ini = $db->table('jadwal_pelajaran jp')
                ->select('jp.jam_mulai, jp.jam_selesai, r.nama_rombel as nama_kelas') 
                ->join('rombel r', 'r.id = jp.rombel_id', 'left')
                ->where('jp.guru_id', $guruId) 
                ->where('jp.hari', $hari_ini)
                ->orderBy('jp.jam_mulai', 'ASC')
                ->get()->getResultArray();
        }

        // Ambil nama user yang paling valid
        $nama_user = session()->get('nama_lengkap');
        if (empty($nama_user)) {
            $nama_user = session()->get('username') ?? ($dataGuru['nama_lengkap'] ?? 'Guru Mapel');
        }

        // =========================================================
        // 5. Siapkan Array Data ke View
        // =========================================================
        $data = [
            'user'        => $nama_user,
            'navigations' => $this->getSidebarMenu(),
            'color'       => $this->getColor(),
            
            'mapel_utama'  => $mapel_utama,
            'jumlah_kelas' => count($kelas_assigned),
            'total_siswa'  => $total_siswa_keseluruhan,
            
            'quick_stats' => [
                'persen_lengkap' => $persen_lengkap,
                'belum_dinilai'  => $siswa_belum_dinilai,
                'rata_rata'      => $rata_rata_semua
            ],
            
            'status_input' => [
                'harian_pct'   => $pct_harian,
                'harian_done'  => $harian_terisi,
                'sumatif_pct'  => $pct_sumatif,
                'sumatif_done' => $sumatif_terisi,
                'proyek_pct'   => $pct_proyek,
                'proyek_done'  => $proyek_terisi,
            ],
            
            'kelas_ajar'      => $kelas_ajar_data,
            'jadwal_hari_ini' => $jadwal_hari_ini,
            
            'insights' => [
                'rata_kelas'       => $rata_per_kelas,
                'kurang_perhatian' => $siswa_kurang_perhatian,
                'proyek_kosong'    => $total_siswa_keseluruhan - $proyek_terisi
            ]
        ];

        return view('GuruMapel/dashboard', $data); 
    }
}