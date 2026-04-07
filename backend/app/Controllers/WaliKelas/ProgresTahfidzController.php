<?php
namespace App\Controllers\WaliKelas;

use App\Controllers\WaliKelasBaseController;

class ProgresTahfidzController extends WaliKelasBaseController
{
    private function getRombelWaliKelas() {
        $db = \Config\Database::connect();
        $userId = session()->get('user_id') ?? session()->get('id_user');
        
        $guru = $db->table('guru_tendik')->where('user_id', $userId)->get()->getRowArray();
        
        if ($guru) {
            $sess_ta  = session()->get('tahun_ajaran');
            $sess_smt = session()->get('semester');

            if ($sess_ta && $sess_smt) {
                $ta_aktif = $db->table('tahun_ajaran')->where('tahun', $sess_ta)->where('semester', $sess_smt)->get()->getRowArray();
            } else {
                $ta_aktif = $db->table('tahun_ajaran')->where('status', 'Aktif')->get()->getRowArray();
            }

            $id_ta = $ta_aktif ? $ta_aktif['id'] : 0;

            // Prioritas: TA Aktif/Pilihan
            $rombel = $db->table('rombel')
                         ->where('wali_kelas_id', $guru['id'])
                         ->where('id_tahun_ajaran', $id_ta)
                         ->get()->getRowArray();
            
                         
            return $rombel; 
        }
        return null; 
    }

    private function getJuzFromSurah($surahName) {
        $db = \Config\Database::connect();
        // Cari ID Surah berdasarkan nama
        $surah = $db->table('ref_surah')
                    ->like('nama_surah', trim($surahName))
                    ->get()->getRowArray();
        
        if ($surah) {
            $surahId = $surah['id'];
            // Cari Juz yang mengandung surah ini
            $juz = $db->table('ref_juz')
                      ->where('mulai_surah_id <=', $surahId)
                      ->where('sampai_surah_id >=', $surahId)
                      ->get()->getRowArray();
            
            if ($juz) {
                // Return angka juz dari string "Juz X"
                return (int) filter_var($juz['nama_juz'], FILTER_SANITIZE_NUMBER_INT);
            }
        }
        return 0;
    }

    public function index(): string
    {
        $db = \Config\Database::connect();
        $rombel = $this->getRombelWaliKelas();
        $rombel_id = $rombel ? $rombel['id'] : 0;
        
        $sekolah = $db->table('sekolah')->select('nama_sekolah, warna_primary, warna_secondary')->get()->getRowArray();
        $color = [
            'warna_primary'   => $sekolah ? $sekolah['warna_primary'] : '#10b981',
            'warna_secondary' => $sekolah ? $sekolah['warna_secondary'] : '#ecfdf5',
        ];

        $tahfizData = [];

        if ($rombel_id > 0) {
            // Ambil target juz untuk rombel ini
            $target = $db->table('target_tahfidz')
                         ->where('tingkat', $rombel['tingkat'])
                         ->where('status', 'Aktif')
                         ->get()->getRowArray();
            $juzTargetDefault = $target ? (int)$target['minimal_hafalan'] : 5;

            $students = $db->table('siswa')
                           ->select('siswa.id, siswa.nama_lengkap as name, siswa.nisn, siswa.foto_siswa, users.foto_profil')
                           ->join('users', 'users.id = siswa.user_id', 'left')
                           ->where('siswa.rombel_id', $rombel_id)
                           ->where('siswa.status_siswa', 'Aktif')
                           ->orderBy('siswa.nama_lengkap', 'ASC')
                           ->get()->getResultArray();

            $siswaIds = array_column($students, 'id');

            if (!empty($siswaIds)) {
                $setoranMap = [];
                if ($db->tableExists('setoran_tahfidz')) {
                    $allSetoran = $db->table('setoran_tahfidz')
                                     ->whereIn('siswa_id', $siswaIds)
                                     ->orderBy('tanggal', 'DESC') 
                                     ->orderBy('id', 'DESC') 
                                     ->get()->getResultArray();
                                     
                    foreach ($allSetoran as $setoran) {
                        $setoranMap[$setoran['siswa_id']][] = $setoran;
                    }
                }

                $nilaiMap = [];
                if ($db->tableExists('nilai_tahfidz')) {
                    $allNilai = $db->table('nilai_tahfidz')
                                   ->whereIn('siswa_id', $siswaIds)
                                   ->orderBy('created_at', 'ASC') 
                                   ->get()->getResultArray();
                                   
                    foreach ($allNilai as $n) {
                        // Gunakan nilai_rata_rata atau nilai_setoran jika 'nilai' tidak ada
                        $val = $n['nilai_rata_rata'] ?? $n['nilai_setoran'] ?? $n['nilai'] ?? 0;
                        $nilaiMap[$n['siswa_id']][] = (int)$val;
                    }
                }

                foreach ($students as $s) {
                    $sId = $s['id'];
                    $juzCurrent = 0;
                    $surahCurrent = 'Belum Ada';
                    $ayahCurrent = 0;
                    $lastUpdate = date('Y-m-d');
                    $juzTarget = $juzTargetDefault; 
                    
                    $riwayat_10 = []; 
                    if (isset($setoranMap[$sId])) {
                        $riwayat_10   = array_slice($setoranMap[$sId], 0, 10);
                        $lastSetoran  = $setoranMap[$sId][0]; 
                        
                        // Infer Juz from Surah Name if 'juz' column missing
                        $juzCurrent   = (float)($lastSetoran['juz'] ?? $this->getJuzFromSurah($lastSetoran['surah'] ?? ''));
                        $surahCurrent = $lastSetoran['surah'] ?? 'Belum Ada'; 
                        $ayahCurrent  = $lastSetoran['ayat'] ?? 0;
                        $lastUpdate   = date('Y-m-d', strtotime($lastSetoran['tanggal']));
                    }

                    $testResults = [0, 0, 0];
                    if (isset($nilaiMap[$sId])) {
                        $riwayat = array_slice($nilaiMap[$sId], -3);
                        $testResults = array_pad($riwayat, -3, 0); 
                    }

                    $progress = 0;
                    if ($juzTarget > 0) {
                        $progress = min(round(($juzCurrent / $juzTarget) * 100), 100);
                    }

                    $foto_profil = $s['foto_profil'] ?? '';
                    $foto_siswa  = $s['foto_siswa'] ?? '';
                    $foto_fix    = !empty($foto_profil) ? $foto_profil : $foto_siswa;

                    $tahfizData[] = [
                        'id'              => $sId,
                        'name'            => $s['name'],
                        'foto_fix'        => $foto_fix, 
                        'juzTarget'       => $juzTarget,
                        'juzCurrent'      => $juzCurrent,
                        'surahCurrent'    => $surahCurrent,
                        'ayahCurrent'     => $ayahCurrent,
                        'progress'        => $progress,
                        'lastUpdate'      => $lastUpdate,
                        'status'          => 'Aktif',
                        'testResults'     => $testResults,
                        'riwayat_lengkap' => $riwayat_10 
                    ];
                }
            }
        }

        $data = [
            'title'       => 'Progres Tahfidz',
            'user'        => session()->get('nama_lengkap') ?? 'Wali Kelas',
            'navigations' => $this->getSidebarMenu(),
            'color'       => $color,
            'tahfizData'  => json_encode($tahfizData)
        ];
        
        return view('WaliKelas/progres-tahfidz', $data); 
    }
}