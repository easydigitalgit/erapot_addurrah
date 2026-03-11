<?php

namespace App\Controllers\OrangTua;

use App\Controllers\OrangTuaBaseController;

class DashboardController extends OrangTuaBaseController
{
    public function index()
    {
        $db = \Config\Database::connect();
        $userId = session()->get('user_id');

        // 1. CARI DATA ORANG TUA DARI TABEL `orangtua_wali`
        $orangTua = $db->table('orangtua_wali')->where('user_id', $userId)->get()->getRowArray();
        
        // Logika Cerdas Menentukan Sapaan & Nama (Bapak/Ibu)
        $sapaan = 'Bapak/Ibu'; 
        $namaOrangTua = session()->get('nama_lengkap') ?? session()->get('username');
        $siswaId = 0;

        if ($orangTua) {
            $siswaId = $orangTua['siswa_id'];
            
            // Prioritas pengecekan nama dan penentuan gender sapaan
            if (!empty($orangTua['nama_ayah'])) {
                $sapaan = 'Bapak';
                $namaOrangTua = $orangTua['nama_ayah'];
            } elseif (!empty($orangTua['nama_ibu'])) {
                $sapaan = 'Ibu';
                $namaOrangTua = $orangTua['nama_ibu'];
            } elseif (!empty($orangTua['nama_wali'])) {
                $sapaan = 'Bapak/Ibu'; // Wali bisa paman/tante/kakek, jadi default
                $namaOrangTua = $orangTua['nama_wali'];
            }
        }

        // 2. CARI DATA ANAK (SISWA)
        $anak = $db->table('siswa')
                   ->select('siswa.id, siswa.nama_lengkap, siswa.nis, siswa.foto_siswa as foto, rombel.nama_rombel as kelas, rombel.wali_kelas_id')
                   ->join('rombel', 'rombel.id = siswa.rombel_id', 'left')
                   ->where('siswa.id', $siswaId)
                   ->get()->getRowArray();

        // Default Data (Jika anak belum di-mapping di DB)
        $dataAnak = ['id' => 0, 'nama_lengkap' => 'Data Ananda Belum Terhubung', 'nis' => '-', 'kelas' => '-', 'foto' => ''];
        $waliKelas = ['nama_lengkap' => 'Belum ditentukan', 'no_wa' => '', 'pesan_default' => ''];
        $statistik = ['kehadiran' => 0, 'rata_nilai' => 0, 'hafalan_terakhir' => 'Belum ada setoran'];
        $aktivitas = [];

        if ($anak) {
            $dataAnak = $anak;

            // 3. AMBIL DATA WALI KELAS
            if (!empty($anak['wali_kelas_id'])) {
                $guru = $db->table('guru_tendik')->where('id', $anak['wali_kelas_id'])->get()->getRowArray();
                if ($guru) {
                    $noWa = preg_replace('/^0/', '62', $guru['no_hp'] ?? '');
                    $waliKelas = [
                        'nama_lengkap'  => $guru['nama_lengkap'],
                        'no_wa'         => $noWa,
                        'pesan_default' => "Assalamu'alaikum Ustadz/ah " . $guru['nama_lengkap'] . ", saya wali murid dari ananda " . $anak['nama_lengkap'] . " ingin berkonsultasi mengenai perkembangan anak saya."
                    ];
                }
            }

            // 4. STATISTIK AKADEMIK
            if ($db->tableExists('nilai_akademik')) {
                $nilai = $db->table('nilai_akademik')
                            ->selectAvg('nilai_angka', 'rata_rata')
                            ->where('siswa_id', $anak['id'])
                            ->get()->getRowArray();
                $statistik['rata_nilai'] = $nilai['rata_rata'] ? round($nilai['rata_rata'], 1) : 0;
            }

            // 5. STATISTIK & TIMELINE TAHFIDZ TERAKHIR
            if ($db->tableExists('setoran_tahfidz')) {
                $tahfidTerakhir = $db->table('setoran_tahfidz')
                                     ->where('siswa_id', $anak['id'])
                                     ->orderBy('created_at', 'DESC')
                                     ->limit(1)
                                     ->get()->getRowArray();
                                     
                if ($tahfidTerakhir) {
                    $statistik['hafalan_terakhir'] = $tahfidTerakhir['surah'] . ' (Ayat ' . $tahfidTerakhir['ayat'] . ')';
                    
                    $aktivitas[] = [
                        'jenis'     => 'tahfidz',
                        'judul'     => 'Setoran ' . $tahfidTerakhir['jenis_setoran'],
                        'deskripsi' => 'Ananda menyetorkan Surah ' . $tahfidTerakhir['surah'] . ' ayat ' . $tahfidTerakhir['ayat'] . ' dengan predikat ' . $tahfidTerakhir['predikat'] . '.',
                        'waktu'     => date('d M Y, H:i', strtotime($tahfidTerakhir['created_at'])) . ' WIB',
                        'icon'      => '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>',
                        'color'     => 'emerald',
                        'timestamp' => strtotime($tahfidTerakhir['created_at'])
                    ];
                }
            }

            // 6. STATISTIK KEHADIRAN
            if ($db->tableExists('rekap_absensi')) {
                $absen = $db->table('rekap_absensi')->where('siswa_id', $anak['id'])->get()->getRowArray();
                if ($absen) {
                    $total_hari = 100; // Asumsi hari sekolah efektif
                    $tidak_hadir = $absen['sakit'] + $absen['izin'] + $absen['alpha'];
                    $hadir = $total_hari - $tidak_hadir;
                    $statistik['kehadiran'] = ($hadir > 0) ? round(($hadir / $total_hari) * 100) : 0;
                } else {
                    $statistik['kehadiran'] = 100;
                }
            }

            // 7. TIMELINE AKADEMIK TERBARU
            if ($db->tableExists('nilai_akademik')) {
                $nilaiTerbaru = $db->table('nilai_akademik')
                                   ->select('nilai_akademik.*, mata_pelajaran.nama_mapel')
                                   ->join('mata_pelajaran', 'mata_pelajaran.id = nilai_akademik.mapel_id', 'left')
                                   ->where('siswa_id', $anak['id'])
                                   ->orderBy('nilai_akademik.id', 'DESC')
                                   ->limit(2)
                                   ->get()->getResultArray();

                foreach ($nilaiTerbaru as $n) {
                    $time = isset($n['created_at']) ? strtotime($n['created_at']) : (time() - rand(1000, 50000));
                    
                    $aktivitas[] = [
                        'jenis'     => 'akademik',
                        'judul'     => 'Nilai Baru: ' . ($n['nama_mapel'] ?? 'Pelajaran'),
                        'deskripsi' => 'Ananda mendapatkan nilai ' . $n['nilai_angka'] . ' pada tugas/ulangan ini.',
                        'waktu'     => date('d M Y', $time),
                        'icon'      => '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>',
                        'color'     => 'blue',
                        'timestamp' => $time
                    ];
                }
            }

            // Urutkan aktivitas (Paling baru di atas)
            usort($aktivitas, function($a, $b) {
                return $b['timestamp'] <=> $a['timestamp'];
            });
        }

        $data = [
            'title'       => 'Dashboard Wali Murid',
            'user'        => $namaOrangTua,
            'sapaan'      => $sapaan, // VARIABEL SAPAAN BAPAK/IBU DIKIRIM KE VIEW
            'color'       => $this->getColor(),
            'navigations' => $this->getSidebarMenu(),
            'anak'        => $dataAnak,
            'wali_kelas'  => $waliKelas,
            'statistik'   => $statistik,
            'aktivitas'   => $aktivitas
        ];

        return view('OrangTua/dashboard', $data);
    }
}