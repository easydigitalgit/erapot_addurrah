<?php
namespace App\Controllers\WaliKelas;

use App\Controllers\WaliKelasBaseController;

class RingkasanKelasController extends WaliKelasBaseController
{
    public function index()
    {
        $db = \Config\Database::connect();
        $userId = session()->get('user_id');

        // 1. AMBIL WARNA TEMA DARI TABEL SEKOLAH
        $sekolah = $db->table('sekolah')->select('warna_primary, warna_secondary')->get()->getRowArray();
        $warna_primary = $sekolah ? $sekolah['warna_primary'] : '#3b82f6';
        $warna_secondary = $sekolah ? $sekolah['warna_secondary'] : '#eff6ff';

        // 2. CARI DATA WALI KELAS (GURU)
        $guru = $db->table('guru_tendik')->where('user_id', $userId)->get()->getRowArray();
        
        $rombel = null;
        $statistik = [
            'total_siswa' => 0,
            'siswa_l' => 0,
            'siswa_p' => 0,
            'hadir_hari_ini' => 0,
            'persen_hadir' => 0,
            'total_pembinaan' => 0,
            'persen_nilai' => 0
        ];

        // Struktur Data Dinamis untuk View
        $pembinaan_siswa = [];
        $progres_mapel = [];

        if ($guru) {
            // 3. CARI ROMBEL YANG DIA PEGANG
            $rombel = $db->table('rombel')
                         ->where('wali_kelas_id', $guru['id'])
                         ->where('tahun_ajaran', session()->get('tahun_ajaran') ?? '2024/2025')
                         ->where('semester', session()->get('semester') ?? 'Ganjil')
                         ->get()->getRowArray();

            if ($rombel) {
                // 4. HITUNG TOTAL SISWA DI ROMBEL TERSEBUT
                $siswaList = $db->table('siswa')
                                ->select('jenis_kelamin')
                                ->where('rombel_id', $rombel['id'])
                                ->where('status_siswa', 'Aktif')
                                ->get()->getResultArray();

                $statistik['total_siswa'] = count($siswaList);
                foreach ($siswaList as $s) {
                    if ($s['jenis_kelamin'] == 'L') $statistik['siswa_l']++;
                    if ($s['jenis_kelamin'] == 'P') $statistik['siswa_p']++;
                }

                // 5. HITUNG KEHADIRAN HARI INI
                if ($db->tableExists('absensi_harian') && $statistik['total_siswa'] > 0) {
                    $hari_ini = date('Y-m-d');
                    $hadir = $db->table('absensi_harian')
                                ->where('rombel_id', $rombel['id'])
                                ->where('tanggal', $hari_ini)
                                ->where('status', 'Hadir')
                                ->countAllResults();
                    
                    $cek_absen = $db->table('absensi_harian')
                                    ->where('rombel_id', $rombel['id'])
                                    ->where('tanggal', $hari_ini)
                                    ->countAllResults();

                    if ($cek_absen > 0) {
                        $statistik['hadir_hari_ini'] = $hadir;
                        $statistik['persen_hadir'] = round(($hadir / $statistik['total_siswa']) * 100, 1);
                    }
                }

                // 6. AMBIL DATA MAPEL KELAS INI 
                if ($db->tableExists('jadwal_pelajaran') && $db->tableExists('mata_pelajaran')) {
                    $jadwal = $db->table('jadwal_pelajaran')
                                 ->select('mata_pelajaran.nama_mapel, guru_tendik.nama_lengkap as nama_guru')
                                 ->join('mata_pelajaran', 'mata_pelajaran.id = jadwal_pelajaran.mapel_id', 'left')
                                 ->join('guru_tendik', 'guru_tendik.id = jadwal_pelajaran.guru_id', 'left')
                                 ->where('jadwal_pelajaran.rombel_id', $rombel['id'])
                                 // PERBAIKAN: Masukkan semua select ke dalam groupBy
                                 ->groupBy(['jadwal_pelajaran.mapel_id', 'mata_pelajaran.nama_mapel', 'guru_tendik.nama_lengkap'])
                                 ->get()->getResultArray();
                                 
                    foreach($jadwal as $j) {
                        $progres_mapel[] = [
                            'mapel' => $j['nama_mapel'],
                            'guru' => $j['nama_guru'] ?? 'Belum Ditentukan',
                            'persentase' => 0, 
                            'status' => 'Belum'
                        ];
                    }
                }
            }
        }

        $data = [
            'title'           => 'Ringkasan Kelas',
            'user'            => session()->get('nama_lengkap') ?? 'Wali Kelas',
            'navigations'     => $this->getSidebarMenu(),
            'guru'            => $guru,
            'rombel'          => $rombel,
            'statistik'       => $statistik,
            'pembinaan_siswa' => $pembinaan_siswa,
            'progres_mapel'   => $progres_mapel,
            'color'           => [
                'warna_primary'   => $warna_primary, 
                'warna_secondary' => $warna_secondary
            ] 
        ];

        return view('WaliKelas/ringkasan-kelas', $data); 
    }
}