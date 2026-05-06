<?php

namespace App\Controllers\WaliKelas;

use App\Controllers\WaliKelasBaseController;

class DaftarSiswaController extends WaliKelasBaseController
{
    public function index()
    {
        $db = \Config\Database::connect();
        $userId = session()->get('user_id');

        // 1. Ambil Tema & Info Sekolah
        $sekolah = $db->table('sekolah')->select('nama_sekolah, warna_primary, warna_secondary')->get()->getRowArray();
        $warna_primary = $sekolah ? $sekolah['warna_primary'] : '#10b981';
        $warna_secondary = $sekolah ? $sekolah['warna_secondary'] : '#ecfdf5';
        $nama_sekolah = $sekolah ? $sekolah['nama_sekolah'] : 'SMPIT Ad Durrah';

        $guru = $db->table('guru_tendik')->where('user_id', $userId)->get()->getRowArray();

        $rombel = null;
        $siswa_kelas = [];
        $statistik = [
            'total_siswa' => 0,
            'hadir_hari_ini' => 0,
            'persen_hadir' => 0,
            'perlu_pembinaan' => 0
        ];

        // 2. CARI TAHUN AJARAN (Logika Dinamis & Global)
        $ta_id_get = $this->request->getGet('ta');
        $list_ta = $db->table('tahun_ajaran')->orderBy('id', 'DESC')->get()->getResultArray();
        
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

            // 3. CARI ROMBEL
            $rombel = $db->table('rombel')
                ->where('wali_kelas_id', $guru['id'])
                ->where('id_tahun_ajaran', $id_ta)
                ->get()->getRowArray();


            if ($rombel) {
                // Ambil string tahun & semester dari id_tahun_ajaran yang didapat
                $ta_asli = $db->table('tahun_ajaran')->where('id', $rombel['id_tahun_ajaran'])->get()->getRowArray();
                $rombel['semester'] = $ta_asli ? $ta_asli['semester'] : 'Ganjil';
                $rombel['tahun_ajaran'] = $ta_asli ? $ta_asli['tahun'] : '2024/2025';

                // ========================================================
                // 4. Ambil Daftar Siswa (MENGGUNAKAN MESIN WAKTU & JOIN FOTO)
                // ========================================================
                $siswa_kelas = $db->table('anggota_rombel ar')
                    ->select('siswa.*, users.foto_profil')
                    ->join('siswa', 'siswa.id = ar.siswa_id')
                    ->join('users', 'users.id = siswa.user_id', 'left')
                    ->where('ar.rombel_id', $rombel['id'])
                    ->where('ar.tahun_ajaran_id', $id_ta)
                    ->where('ar.semester', $semester)
                    ->where('siswa.status_siswa', 'Aktif')
                    ->orderBy('siswa.nama_lengkap', 'ASC')
                    ->get()->getResultArray();

                $statistik['total_siswa'] = count($siswa_kelas);

                // Kumpulkan ID siswa untuk query borongan (Optimasi)
                $siswaIds = array_column($siswa_kelas, 'id');

                if (!empty($siswaIds)) {
                    // --- BATCH QUERY OPTIMIZATION ---

                    // A. Batch Absensi
                    $absenMap = [];
                    if ($db->tableExists('absensi_harian')) {
                        $absenData = $db->table('absensi_harian')
                            ->select('siswa_id, status, COUNT(id) as total')
                            ->whereIn('siswa_id', $siswaIds)
                            ->groupBy('siswa_id, status')
                            ->get()->getResultArray();
                        foreach ($absenData as $ad) $absenMap[$ad['siswa_id']][$ad['status']] = $ad['total'];
                    }

                    // B. Batch Tahfidz
                    $tahfidzMap = [];
                    if ($db->tableExists('setoran_tahfidz')) {
                        $tahfidzData = $db->table('setoran_tahfidz')
                            ->whereIn('siswa_id', $siswaIds)
                            ->orderBy('tanggal', 'ASC')
                            ->get()->getResultArray();
                        foreach ($tahfidzData as $td) $tahfidzMap[$td['siswa_id']] = $td;
                    }

                    // C. Batch Catatan Akhlak
                    $catatanMap = [];
                    if ($db->tableExists('catatan_akhlak')) {
                        $catatanData = $db->table('catatan_akhlak')
                            ->whereIn('siswa_id', $siswaIds)
                            ->orderBy('tanggal', 'ASC')
                            ->get()->getResultArray();
                        foreach ($catatanData as $cd) $catatanMap[$cd['siswa_id']] = $cd;
                    }

                    // D. Batch Nilai Akademik
                    $nilaiMap = [];
                    if ($db->tableExists('nilai_sumatif') && $db->tableExists('mata_pelajaran')) {
                        $nilaiData = $db->table('nilai_sumatif')
                            ->select('nilai_sumatif.siswa_id, nilai_sumatif.nilai, mata_pelajaran.nama_mapel')
                            ->join('mata_pelajaran', 'mata_pelajaran.id = nilai_sumatif.mapel_id', 'left')
                            ->whereIn('nilai_sumatif.siswa_id', $siswaIds)
                            ->get()->getResultArray();
                        foreach ($nilaiData as $nd) {
                            $nilaiMap[$nd['siswa_id']][] = $nd;
                        }
                    }

                    // 5. Mapping Data ke Siswa (Sangat Cepat di memori PHP)
                    foreach ($siswa_kelas as &$s) {
                        $sId = $s['id'];

                       // DETEKSI LOKASI FOTO (Prioritas: Tabel Siswa, Fallback: Tabel Users)
$s['foto_fix'] = !empty($s['foto_siswa']) ? $s['foto_siswa'] : (!empty($s['foto_profil']) ? $s['foto_profil'] : null);

                        // Map Absensi
                        $s['absen_h'] = $absenMap[$sId]['Hadir'] ?? 0;
                        $s['absen_s'] = $absenMap[$sId]['Sakit'] ?? 0;
                        $s['absen_i'] = $absenMap[$sId]['Izin'] ?? 0;
                        $s['absen_a'] = $absenMap[$sId]['Alpha'] ?? 0;
                        $total_hari = $s['absen_h'] + $s['absen_s'] + $s['absen_i'] + $s['absen_a'];
                        $s['persen_absen'] = ($total_hari > 0) ? round(($s['absen_h'] / $total_hari) * 100) : 100;
                        $s['rekap_absen'] = "({$s['absen_h']}/{$total_hari})";

                        // Map Tahfidz
                        $s['capaian_tahfidz'] = isset($tahfidzMap[$sId]) ? ($tahfidzMap[$sId]['surah'] ?? 'Ada Setoran') : 'Proses';

                        // Map Catatan
                        $s['tipe_catatan'] = isset($catatanMap[$sId]) ? $catatanMap[$sId]['kategori_akhlak'] : 'Tidak ada';
                        $s['isi_catatan'] = isset($catatanMap[$sId]) ? $catatanMap[$sId]['catatan'] : 'Belum ada catatan khusus dari wali kelas.';
                        if ($s['tipe_catatan'] != 'Tidak ada') $statistik['perlu_pembinaan']++;

                        // Map Nilai
                        $s['nilai_mapel'] = [];
                        $s['rata_nilai'] = 0;
                        if (isset($nilaiMap[$sId])) {
                            $total_nilai = 0;
                            foreach ($nilaiMap[$sId] as $ndb) {
                                $mapel = $ndb['nama_mapel'] ?? 'Mapel';
                                $s['nilai_mapel'][$mapel] = $ndb['nilai'];
                                $total_nilai += $ndb['nilai'];
                            }
                            $s['rata_nilai'] = round($total_nilai / count($nilaiMap[$sId]), 1);
                        }
                    }
                }

                // 6. Hitung Absen Hari Ini (Keseluruhan Kelas)
                if ($db->tableExists('absensi_harian') && $statistik['total_siswa'] > 0) {
                    $hari_ini = date('Y-m-d');
                    $hadir = $db->table('absensi_harian')->where('rombel_id', $rombel['id'])->where('tanggal', $hari_ini)->where('status', 'Hadir')->countAllResults();
                    $cek_absen = $db->table('absensi_harian')->where('rombel_id', $rombel['id'])->where('tanggal', $hari_ini)->countAllResults();

                    if ($cek_absen > 0) {
                        $statistik['hadir_hari_ini'] = $hadir;
                        $statistik['persen_hadir'] = round(($hadir / $statistik['total_siswa']) * 100, 1);
                    }
                }
            }
        }

        $data = [
            'title'       => 'Daftar Siswa Kelas Perwalian',
            'user'        => session()->get('nama_lengkap') ?? 'Wali Kelas',
            'namaLengkap' => session()->get('nama_lengkap') ?? session()->get('username') ?? 'Wali Kelas',
            'nama_sekolah' => $nama_sekolah, 
            'navigations' => $this->getSidebarMenu(),
            'rombel'      => $rombel,
            'siswa_kelas' => $siswa_kelas,
            'statistik'   => $statistik,
            'tahun_ajaran' => $tahun_ajaran,
            'semester'     => $semester,
            'list_ta'     => $list_ta ?? [],
            'id_ta'       => $id_ta ?? 0,
            'color'       => [
                'warna_primary'   => $warna_primary,
                'warna_secondary' => $warna_secondary
            ]
        ];
        
        return view('WaliKelas/daftar-siswa', $data);
    }
}
