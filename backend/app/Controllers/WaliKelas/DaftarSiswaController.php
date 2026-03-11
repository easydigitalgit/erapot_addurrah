<?php
namespace App\Controllers\WaliKelas;

use App\Controllers\WaliKelasBaseController;

class DaftarSiswaController extends WaliKelasBaseController
{
    public function index()
    {
        $db = \Config\Database::connect();
        $userId = session()->get('user_id');

        // 1. Ambil Warna Sekolah
        $sekolah = $db->table('sekolah')->select('warna_primary, warna_secondary')->get()->getRowArray();
        $warna_primary = $sekolah ? $sekolah['warna_primary'] : '#10b981';
        $warna_secondary = $sekolah ? $sekolah['warna_secondary'] : '#ecfdf5';

        $guru = $db->table('guru_tendik')->where('user_id', $userId)->get()->getRowArray();
        
        $rombel = null;
        $siswa_kelas = [];
        $statistik = [
            'total_siswa' => 0, 'hadir_hari_ini' => 0, 
            'persen_hadir' => 0, 'perlu_pembinaan' => 0
        ];

        if ($guru) {
            $rombel = $db->table('rombel')
                         ->where('wali_kelas_id', $guru['id'])
                         ->where('tahun_ajaran', session()->get('tahun_ajaran') ?? '2024/2025')
                         ->where('semester', session()->get('semester') ?? 'Ganjil')
                         ->get()->getRowArray();

            if ($rombel) {
                // 2. Ambil Daftar Siswa
                $siswa_kelas = $db->table('siswa')
                                  ->where('rombel_id', $rombel['id'])
                                  ->where('status_siswa', 'Aktif')
                                  ->orderBy('nama_lengkap', 'ASC')
                                  ->get()->getResultArray();

                $statistik['total_siswa'] = count($siswa_kelas);

                // 3. Looping untuk mengambil data pendukung tiap siswa
                foreach ($siswa_kelas as &$s) {
                    // A. Kehadiran
                    $s['absen_h'] = 0; $s['absen_s'] = 0; $s['absen_i'] = 0; $s['absen_a'] = 0;
                    if ($db->tableExists('absensi_harian')) {
                        $s['absen_h'] = $db->table('absensi_harian')->where(['siswa_id' => $s['id'], 'status' => 'Hadir'])->countAllResults();
                        $s['absen_s'] = $db->table('absensi_harian')->where(['siswa_id' => $s['id'], 'status' => 'Sakit'])->countAllResults();
                        $s['absen_i'] = $db->table('absensi_harian')->where(['siswa_id' => $s['id'], 'status' => 'Izin'])->countAllResults();
                        $s['absen_a'] = $db->table('absensi_harian')->where(['siswa_id' => $s['id'], 'status' => 'Alpha'])->countAllResults();
                    }
                    $total_hari = $s['absen_h'] + $s['absen_s'] + $s['absen_i'] + $s['absen_a'];
                    $s['persen_absen'] = ($total_hari > 0) ? round(($s['absen_h'] / $total_hari) * 100) : 100;
                    $s['rekap_absen'] = "({$s['absen_h']}/{$total_hari})";

                    // B. Tahfidz
                    $tahfidz = $db->tableExists('setoran_tahfidz') ? $db->table('setoran_tahfidz')->where('siswa_id', $s['id'])->orderBy('tanggal', 'DESC')->limit(1)->get()->getRowArray() : null;
                    $s['capaian_tahfidz'] = $tahfidz ? ($tahfidz['surah'] ?? 'Ada Setoran') : 'Proses';

                    // C. Catatan Akhlak
                    $catatan = $db->tableExists('catatan_akhlak') ? $db->table('catatan_akhlak')->where('siswa_id', $s['id'])->orderBy('tanggal', 'DESC')->limit(1)->get()->getRowArray() : null;
                    $s['tipe_catatan'] = $catatan ? $catatan['kategori_akhlak'] : 'Tidak ada';
                    $s['isi_catatan'] = $catatan ? $catatan['catatan'] : 'Belum ada catatan khusus dari wali kelas.';
                    
                    if($s['tipe_catatan'] != 'Tidak ada') $statistik['perlu_pembinaan']++;

                    // D. Akademik (Nilai Mapel & Rata-rata)
                    $s['nilai_mapel'] = [];
                    $s['rata_nilai'] = 0;
                    if ($db->tableExists('nilai_sumatif') && $db->tableExists('mata_pelajaran')) {
                        $nilai_db = $db->table('nilai_sumatif')
                                       ->select('mata_pelajaran.nama_mapel, nilai_sumatif.nilai')
                                       ->join('mata_pelajaran', 'mata_pelajaran.id = nilai_sumatif.mapel_id', 'left')
                                       ->where('nilai_sumatif.siswa_id', $s['id'])
                                       ->get()->getResultArray();
                        
                        $total_nilai = 0;
                        foreach($nilai_db as $ndb) {
                            $mapel = $ndb['nama_mapel'] ?? 'Mapel';
                            $s['nilai_mapel'][$mapel] = $ndb['nilai'];
                            $total_nilai += $ndb['nilai'];
                        }
                        if (count($nilai_db) > 0) {
                            $s['rata_nilai'] = round($total_nilai / count($nilai_db), 1);
                        }
                    }
                }

                // 4. Hitung Absen Hari Ini (Keseluruhan Kelas)
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
            'navigations' => $this->getSidebarMenu(),
            'rombel'      => $rombel,
            'siswa_kelas' => $siswa_kelas,
            'statistik'   => $statistik,
            'color'       => [
                'warna_primary'   => $warna_primary, 
                'warna_secondary' => $warna_secondary
            ]
        ];

        return view('WaliKelas/daftar-siswa', $data); 
    }
}