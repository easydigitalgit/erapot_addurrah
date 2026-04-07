<?php
namespace App\Controllers\WaliKelas;

use App\Controllers\WaliKelasBaseController;

class RingkasanKelasController extends WaliKelasBaseController
{
    public function index()
    {
        $db = \Config\Database::connect();
        $userId = session()->get('user_id');

        // 1. AMBIL INFO SEKOLAH & TEMA
        $sekolah = $db->table('sekolah')->select('nama_sekolah, warna_primary, warna_secondary')->get()->getRowArray();
        $nama_sekolah = $sekolah ? $sekolah['nama_sekolah'] : 'SMPIT Ad Durrah';
        $warna_primary = $sekolah ? $sekolah['warna_primary'] : '#10b981';
        $warna_secondary = $sekolah ? $sekolah['warna_secondary'] : '#ecfdf5';

        // 2. CARI DATA GURU
        $guru = $db->table('guru_tendik')->where('user_id', $userId)->get()->getRowArray();

        $rombel = null;
        $statistik = [
            'total_siswa' => 0, 'siswa_l' => 0, 'siswa_p' => 0,
            'hadir_hari_ini' => 0, 'persen_hadir' => 0,
            'total_pembinaan' => 0, 'persen_nilai' => 0
        ];
        $pembinaan_siswa = [];
        $progres_mapel = [];

        // 2. CARI TAHUN AJARAN (Logika Dinamis & Global)
        $ta_id_get = $this->request->getGet('ta');
        if ($ta_id_get) {
            $ta_aktif = $db->table('tahun_ajaran')->where('id', $ta_id_get)->get()->getRowArray();
        } else {
            $sess_ta  = session()->get('tahun_ajaran');
            $sess_smt = session()->get('semester');
            if ($sess_ta && $sess_smt) {
                $ta_aktif = $db->table('tahun_ajaran')->where('tahun', $sess_ta)->where('semester', $sess_smt)->get()->getRowArray();
            } else {
                $ta_aktif = $db->table('tahun_ajaran')->where('status', 'Aktif')->get()->getRowArray();
            }
        }

        $id_ta        = $ta_aktif ? $ta_aktif['id'] : 0;
        $tahun_ajaran = $ta_aktif ? $ta_aktif['tahun'] : '2024/2025';
        $semester     = $ta_aktif ? $ta_aktif['semester'] : 'Ganjil';

        if ($guru) {

            // 4. CARI ROMBEL 
            $rombel = $db->table('rombel')
                         ->where('wali_kelas_id', $guru['id'])
                         ->where('id_tahun_ajaran', $id_ta)
                         ->get()->getRowArray();


            if ($rombel) {
                $ta_asli = $db->table('tahun_ajaran')->where('id', $rombel['id_tahun_ajaran'])->get()->getRowArray();
                $rombel['tahun_ajaran'] = $ta_asli ? $ta_asli['tahun'] : '2024/2025';
                $rombel['semester']     = $ta_asli ? $ta_asli['semester'] : 'Ganjil';

                // 5. HITUNG TOTAL SISWA
                $siswaList = $db->table('siswa')
                                ->select('id, jenis_kelamin')
                                ->where('rombel_id', $rombel['id'])
                                ->where('status_siswa', 'Aktif')
                                ->get()->getResultArray();

                $statistik['total_siswa'] = count($siswaList);
                $siswaIds = [];
                foreach ($siswaList as $s) {
                    $siswaIds[] = $s['id'];
                    if ($s['jenis_kelamin'] == 'L') $statistik['siswa_l']++;
                    if ($s['jenis_kelamin'] == 'P') $statistik['siswa_p']++;
                }

                if ($statistik['total_siswa'] > 0) {
                    // 6. HITUNG KEHADIRAN HARI INI
                    if ($db->tableExists('absensi_harian')) {
                        $hari_ini = date('Y-m-d');
                        $absensi_hari_ini = $db->table('absensi_harian')
                                               ->select('status')
                                               ->where('rombel_id', $rombel['id'])
                                               ->where('tanggal', $hari_ini)
                                               ->get()->getResultArray();

                        if (count($absensi_hari_ini) > 0) {
                            $hadir = 0;
                            foreach ($absensi_hari_ini as $absen) {
                                if ($absen['status'] == 'Hadir') $hadir++;
                            }
                            $statistik['hadir_hari_ini'] = $hadir;
                            $statistik['persen_hadir'] = round(($hadir / $statistik['total_siswa']) * 100, 1);
                        }
                    }

                    // 7. SISWA PERLU PEMBINAAN (Optimasi Anti ONLY_FULL_GROUP_BY)
                    if ($db->tableExists('catatan_akhlak')) {
                        $catatan_mentah = $db->table('catatan_akhlak')
                            ->select('catatan_akhlak.siswa_id, siswa.nis, siswa.nama_lengkap as nama, catatan_akhlak.status_pembinaan as kategori')
                            ->join('siswa', 'siswa.id = catatan_akhlak.siswa_id')
                            ->where('catatan_akhlak.rombel_id', $rombel['id'])
                            ->whereIn('catatan_akhlak.status_pembinaan', ['Perlu Pembinaan', 'Proses'])
                            ->orderBy('catatan_akhlak.tanggal', 'DESC')
                            ->get()->getResultArray();

                        $filter_siswa = [];
                        foreach ($catatan_mentah as $c) {
                            $sid = $c['siswa_id'];
                            if (!isset($filter_siswa[$sid])) {
                                $filter_siswa[$sid] = [
                                    'nis'      => $c['nis'],
                                    'nama'     => $c['nama'],
                                    'kategori' => $c['kategori']
                                ];
                            }
                        }

                        $pembinaan_siswa = array_values($filter_siswa);
                        $statistik['total_pembinaan'] = count($pembinaan_siswa);
                    }

                    // 8. PROGRES INPUT NILAI MAPEL (Menggunakan rombel_id sesuai SQL asli)
                   // 8. PROGRES INPUT NILAI MAPEL
                    if ($db->tableExists('jadwal_pelajaran') && $db->tableExists('mata_pelajaran')) {
                        $jadwal = $db->table('jadwal_pelajaran')
                                     ->select('jadwal_pelajaran.mapel_id, mata_pelajaran.nama_mapel, guru_tendik.nama_lengkap as nama_guru')
                                     ->join('mata_pelajaran', 'mata_pelajaran.id = jadwal_pelajaran.mapel_id', 'left')
                                     ->join('guru_tendik', 'guru_tendik.id = jadwal_pelajaran.guru_id', 'left')
                                     ->where('jadwal_pelajaran.rombel_id', $rombel['id'])
                                     ->groupBy(['jadwal_pelajaran.mapel_id', 'mata_pelajaran.nama_mapel', 'guru_tendik.nama_lengkap'])
                                     ->get()->getResultArray();
                        
                        if (!empty($jadwal) && !empty($siswaIds) && $db->tableExists('nilai_sumatif')) {
                            
                            // PERBAIKAN FATAL: Langsung gunakan whereIn siswa_id, aman dan pasti jalan!
                            $semua_nilai = $db->table('nilai_sumatif')
                                              ->select('mapel_id, COUNT(DISTINCT siswa_id) as jumlah_dinilai')
                                              ->whereIn('siswa_id', $siswaIds)
                                              ->groupBy('mapel_id')
                                              ->get()->getResultArray();
                            
                            $map_nilai = array_column($semua_nilai, 'jumlah_dinilai', 'mapel_id');
                            $mapel_selesai = 0;
                            
                            foreach ($jadwal as $j) {
                                $mapel_id = $j['mapel_id'];
                                $jml_dinilai = isset($map_nilai[$mapel_id]) ? $map_nilai[$mapel_id] : 0;
                                
                                $persentase = round(($jml_dinilai / $statistik['total_siswa']) * 100);
                                if ($persentase > 100) $persentase = 100;
                                if ($persentase >= 100) $mapel_selesai++;

                                $progres_mapel[] = [
                                    'mapel'      => $j['nama_mapel'],
                                    'guru'       => $j['nama_guru'] ?? 'Belum Ditentukan',
                                    'persentase' => $persentase, 
                                    'status'     => ($persentase >= 100) ? 'Selesai' : 'Proses'
                                ];
                            }
                            if (count($jadwal) > 0) {
                                $statistik['persen_nilai'] = round(($mapel_selesai / count($jadwal)) * 100);
                            }
                        } else {
                            foreach ($jadwal as $j) {
                                $progres_mapel[] = [
                                    'mapel' => $j['nama_mapel'], 'guru' => $j['nama_guru'] ?? '-', 
                                    'persentase' => 0, 'status' => 'Belum'
                                ];
                            }
                        }
                    }
                }
            }
        }

        $data = [
            'title'           => 'Ringkasan Kelas',
            'user'            => session()->get('nama_lengkap') ?? 'Wali Kelas',
            'nama_sekolah'    => $nama_sekolah, // <--- TAMBAHKAN BARIS INI
            'navigations'     => $this->getSidebarMenu(),
            'guru'            => $guru,
            'rombel'          => $rombel,
            'statistik'       => $statistik,
            'pembinaan_siswa' => $pembinaan_siswa,
            'progres_mapel'   => $progres_mapel,
            'tahun_ajaran'    => $tahun_ajaran,
            'semester'        => $semester,
            'color'           => [
                'warna_primary'   => $warna_primary, 
                'warna_secondary' => $warna_secondary
            ] 
        ];

        return view('WaliKelas/ringkasan-kelas', $data); 
    }
}