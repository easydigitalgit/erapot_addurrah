<?php

namespace App\Controllers\OrangTua;

use App\Controllers\OrangTuaBaseController;

class DashboardController extends OrangTuaBaseController
{
    public function index()
    {
        $db = \Config\Database::connect();
        $userId = session()->get('user_id') ?? session()->get('id');

        // 1. CARI DATA ORANG TUA DARI TABEL `orangtua_wali`
        $orangTua = $db->table('orangtua_wali')->where('user_id', $userId)->get()->getRowArray();
        
        $sapaan = 'Bapak/Ibu'; 
        $namaOrangTua = session()->get('nama_lengkap') ?? session()->get('username') ?? 'Orang Tua';
        $siswaId = 0;

        if ($orangTua) {
            $siswaId = $orangTua['siswa_id'] ?? 0;
            if (!empty($orangTua['nama_ayah']) && $orangTua['nama_ayah'] !== '-') {
                $sapaan = 'Bapak';
                $namaOrangTua = $orangTua['nama_ayah'];
            } elseif (!empty($orangTua['nama_ibu']) && $orangTua['nama_ibu'] !== '-') {
                $sapaan = 'Ibu';
                $namaOrangTua = $orangTua['nama_ibu'];
            } elseif (!empty($orangTua['nama_wali']) && $orangTua['nama_wali'] !== '-') {
                $sapaan = 'Bapak/Ibu'; 
                $namaOrangTua = $orangTua['nama_wali'];
            }
        }

        // 2. CARI DATA ANAK (Langsung panggil siswa.foto_siswa dan users.foto_profil)
        $anak = $db->table('siswa')
                   ->select("siswa.id, siswa.nama_lengkap, siswa.nis, rombel.nama_rombel as kelas, rombel.wali_kelas_id, users.foto_profil, siswa.foto_siswa")
                   ->join('rombel', 'rombel.id = siswa.rombel_id', 'left')
                   ->join('users', 'users.id = siswa.user_id', 'left')
                   ->where('siswa.id', $siswaId)
                   ->get()
                   ->getRowArray();

       $dataAnak = ['id' => 0, 'nama_lengkap' => 'Data Ananda Belum Terhubung', 'nis' => '-', 'kelas' => '-', 'foto_siswa' => '', 'foto_profil' => '', 'foto_fix' => ''];
        $waliKelas = ['nama_lengkap' => 'Belum ditentukan', 'no_wa' => '', 'pesan_default' => ''];
        $statistik = ['kehadiran' => 0, 'rata_nilai' => 0, 'hafalan_terakhir' => 'Belum ada setoran'];
        $aktivitas = [];

        if ($anak) {
            // --- LOGIKA HYBRID AVATAR ANAK ---
            $fotoProfil = $anak['foto_profil'] ?? '';
            $fotoSiswa  = $anak['foto_siswa'] ?? '';
            $anak['foto_fix'] = !empty($fotoProfil) ? $fotoProfil : (!empty($fotoSiswa) ? $fotoSiswa : null);
            // ---------------------------------
            
            $dataAnak = $anak;

            // 3. AMBIL DATA WALI KELAS
            if (!empty($anak['wali_kelas_id'])) {
                $guru = $db->table('guru_tendik')->where('id', $anak['wali_kelas_id'])->get()->getRowArray();
                if ($guru) {
                    $noWa = preg_replace('/^0/', '62', $guru['no_hp'] ?? '');
                    $waliKelas = [
                        'nama_lengkap'  => $guru['nama_lengkap'],
                        'no_wa'         => $noWa,
                        'pesan_default' => "Assalamu'alaikum Ustadz/ah " . $guru['nama_lengkap'] . ", saya wali murid dari ananda " . $anak['nama_lengkap'] . " ingin berkonsultasi."
                    ];
                }
            }

            $tahun_ajaran_active = session()->get('tahun_ajaran_id');
            $ta = $db->table('tahun_ajaran')->where('status', 'Aktif')->get()->getRowArray();
            if (!$tahun_ajaran_active && $db->tableExists('tahun_ajaran')) {
                if ($ta) $tahun_ajaran_active = $ta['id'];
            }
            $tahun_ajaran_teks = $ta ? $ta['tahun'] : '2025/2026';

            // 4. STATISTIK AKADEMIK
            $tabelAcuan = $db->tableExists('nilai_akademik') ? 'nilai_akademik' : ($db->tableExists('nilai_formatif') ? 'nilai_formatif' : 'nilai_sumatif');
            $fieldNilai = $db->fieldExists('nilai_angka', $tabelAcuan) ? 'nilai_angka' : 'nilai';
            $fieldTA    = $db->fieldExists('tahun_ajaran_id', $tabelAcuan) ? 'tahun_ajaran_id' : 'tahun_ajaran';

            if ($db->tableExists($tabelAcuan)) {
                $qNilai = $db->table($tabelAcuan)->selectAvg($fieldNilai, 'rata_rata')->where('siswa_id', $anak['id']);
                if ($tahun_ajaran_active) {
                    if ($fieldTA == 'tahun_ajaran_id') {
                        $qNilai->where('tahun_ajaran_id', $tahun_ajaran_active);
                    } else {
                        $qNilai->where('tahun_ajaran', $tahun_ajaran_teks);
                    }
                }
                $nilai = $qNilai->get()->getRowArray();
                $statistik['rata_nilai'] = isset($nilai['rata_rata']) ? round($nilai['rata_rata'], 1) : 0;
            }

            // 5. STATISTIK & TIMELINE TAHFIDZ TERAKHIR
            if ($db->tableExists('setoran_tahfidz')) {
                $tahfidTerakhir = $db->table('setoran_tahfidz')
                                     ->where('siswa_id', $anak['id'])
                                     ->orderBy('tanggal', 'DESC')
                                     ->limit(1)
                                     ->get()->getRowArray();
                                     
                if ($tahfidTerakhir) {
                    $statistik['hafalan_terakhir'] = $tahfidTerakhir['surah'] . ' (Ayat ' . $tahfidTerakhir['ayat'] . ')';
                    
                    $aktivitas[] = [
                        'jenis'     => 'tahfidz',
                        'judul'     => 'Setoran Hafalan Al-Quran',
                        'deskripsi' => 'Ananda menyetorkan ' . $tahfidTerakhir['surah'] . ' ayat ' . $tahfidTerakhir['ayat'] . '.',
                        'waktu'     => date('d M Y', strtotime($tahfidTerakhir['tanggal'])),
                        'color'     => 'emerald',
                        'timestamp' => strtotime($tahfidTerakhir['tanggal'])
                    ];
                }
            }

            // 6. STATISTIK KEHADIRAN
            if ($db->tableExists('rekap_absensi')) {
                $qAbsen = $db->table('rekap_absensi')->where('siswa_id', $anak['id']);
                if ($tahun_ajaran_active) $qAbsen->where('tahun_ajaran_id', $tahun_ajaran_active);
                $absen = $qAbsen->get()->getResultArray();
                            
                if (!empty($absen)) {
                    $total_hari = 100; 
                    $tidak_hadir = 0;
                    foreach($absen as $a) {
                        $tidak_hadir += (int)$a['sakit'] + (int)$a['izin'] + (int)$a['alpha'];
                    }
                    $hadir = $total_hari - $tidak_hadir;
                    $statistik['kehadiran'] = ($hadir > 0) ? round(($hadir / $total_hari) * 100) : 0;
                } else {
                    $statistik['kehadiran'] = 100;
                }
            }

            // 7. TIMELINE AKADEMIK TERBARU
            if ($db->tableExists($tabelAcuan)) {
                $nilaiTerbaru = $db->table($tabelAcuan)
                                   ->select($tabelAcuan . '.*, mata_pelajaran.nama_mapel')
                                   ->join('mata_pelajaran', 'mata_pelajaran.id = ' . $tabelAcuan . '.mapel_id', 'left')
                                   ->where('siswa_id', $anak['id'])
                                   ->orderBy($tabelAcuan . '.id', 'DESC')
                                   ->limit(2)
                                   ->get()->getResultArray();

                foreach ($nilaiTerbaru as $n) {
                    $time = isset($n['created_at']) ? strtotime($n['created_at']) : (time() - rand(1000, 50000));
                    $n_val = isset($n[$fieldNilai]) ? $n[$fieldNilai] : 0;
                    
                    $aktivitas[] = [
                        'jenis'     => 'akademik',
                        'judul'     => 'Nilai Baru: ' . ($n['nama_mapel'] ?? 'Pelajaran'),
                        'deskripsi' => 'Ananda mendapatkan nilai ' . $n_val . ' pada tugas/ulangan ini.',
                        'waktu'     => date('d M Y', $time),
                        'color'     => 'blue',
                        'timestamp' => $time
                    ];
                }
            }

            usort($aktivitas, function($a, $b) {
                return $b['timestamp'] <=> $a['timestamp'];
            });
        }

        $sekolah = $db->table('sekolah')->get()->getRowArray();
        $color = [
            'warna_primary'   => !empty($sekolah['warna_primary']) ? $sekolah['warna_primary'] : '#10b981',
            'warna_secondary' => !empty($sekolah['warna_secondary']) ? $sekolah['warna_secondary'] : '#ecfdf5',
        ];

        $data = [
            'title'       => 'Dashboard Wali Murid',
            'user'        => $namaOrangTua,
            'sapaan'      => $sapaan,
            'color'       => $color,
            'navigations' => $this->getSidebarMenu(),
            'anak'        => $dataAnak,
            'wali_kelas'  => $waliKelas,
            'statistik'   => $statistik,
            'aktivitas'   => $aktivitas
        ];

        return view('OrangTua/dashboard', $data);
    }
}