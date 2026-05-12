<?php

namespace App\Controllers\OrangTua;

use App\Controllers\OrangTuaBaseController;

class KehadiranController extends OrangTuaBaseController
{
    public function index()
    {
        $db = \Config\Database::connect();
        $userId = session()->get('user_id') ?? session()->get('id');

        // 1. CARI DATA ORANG TUA
        $orangTua = $db->table('orangtua_wali')->where('user_id', $userId)->get()->getRowArray();
        
        $sapaan = 'Bapak/Ibu'; 
        $namaOrangTua = session()->get('nama_lengkap') ?? session()->get('username');
        $siswaId = 0;

        if ($orangTua) {
            $siswaId = $orangTua['siswa_id'] ?? 0;
            if (!empty($orangTua['nama_ayah']) && $orangTua['nama_ayah'] !== '-') { $sapaan = 'Bapak'; $namaOrangTua = $orangTua['nama_ayah']; }
            elseif (!empty($orangTua['nama_ibu']) && $orangTua['nama_ibu'] !== '-') { $sapaan = 'Ibu'; $namaOrangTua = $orangTua['nama_ibu']; }
            elseif (!empty($orangTua['nama_wali']) && $orangTua['nama_wali'] !== '-') { $sapaan = 'Bapak/Ibu'; $namaOrangTua = $orangTua['nama_wali']; }
        }

        // 2. CARI DATA ANAK
        $anak = $db->table('siswa')
                   ->select('siswa.id, siswa.rombel_id, siswa.nama_lengkap, siswa.nis, rombel.nama_rombel as kelas, rombel.tingkat, rombel.semester, users.foto_profil, siswa.foto_siswa')
                   ->join('rombel', 'rombel.id = siswa.rombel_id', 'left')
                   ->join('users', 'users.id = siswa.user_id', 'left')
                   ->where('siswa.id', $siswaId)
                   ->get()->getRowArray();

        if ($anak) {
            // --- LOGIKA HYBRID AVATAR ---
            $fotoProfil = $anak['foto_profil'] ?? '';
            $fotoSiswa  = $anak['foto_siswa'] ?? '';
            $anak['foto_fix'] = !empty($fotoProfil) ? $fotoProfil : (!empty($fotoSiswa) ? $fotoSiswa : null);
            // ----------------------------
        }

        // 3. STATISTIK REAL-TIME DARI DATA HARIAN
        $absen = ['sakit' => 0, 'izin' => 0, 'alpha' => 0];
        $statistik = ['total_hari_sekolah' => 0, 'total_hadir' => 0, 'total_absen' => 0, 'persentase' => 0]; 

        $tahun_ajaran_active = session()->get('tahun_ajaran_id');
        if (!$tahun_ajaran_active && $db->tableExists('tahun_ajaran')) {
            $ta = $db->table('tahun_ajaran')->where('status', 'Aktif')->get()->getRowArray();
            if ($ta) $tahun_ajaran_active = $ta['id'];
        }

        if ($anak && $db->tableExists('rekap_absensi')) {
            $fTA = $db->fieldExists('tahun_ajaran_id', 'rekap_absensi') ? 'tahun_ajaran_id' : 'tahun_ajaran';
            $ta_rec = $db->table('tahun_ajaran')->where('id', $tahun_ajaran_active)->get()->getRowArray();
            $vTA = ($fTA === 'tahun_ajaran_id') ? $tahun_ajaran_active : ($ta_rec ? $ta_rec['tahun'] : '');
            $smt = $ta_rec ? $ta_rec['semester'] : 'Ganjil';

            $rekap = $db->table('rekap_absensi')
                        ->where(['siswa_id' => $anak['id'], $fTA => $vTA, 'semester' => $smt])
                        ->get()->getRowArray();

            if ($rekap) {
                $absen['sakit'] = (int)($rekap['sakit'] ?? 0);
                $absen['izin']  = (int)($rekap['izin'] ?? 0);
                $absen['alpha'] = (int)($rekap['alpha'] ?? 0);
                $hadir_val      = (int)($rekap['hadir'] ?? 0);
                
                $statistik['total_hadir'] = $hadir_val;
                $statistik['total_absen'] = $absen['sakit'] + $absen['izin'] + $absen['alpha'];
                $total_efektif = $statistik['total_hadir'] + $statistik['total_absen'];
                $statistik['total_hari_sekolah'] = $total_efektif;
                
                if ($total_efektif > 0) {
                    $statistik['persentase'] = round(($hadir_val / $total_efektif) * 100, 1);
                }
            }
        }


        // 4. LOGIKA KALENDER HARIAN DARI DATABASE
        $kalender = [];
        
        $bulan_ini = $this->request->getGet('bulan') ?: date('n');
        $tahun_ini = $this->request->getGet('tahun') ?: date('Y');
        
        $jml_hari = cal_days_in_month(CAL_GREGORIAN, $bulan_ini, $tahun_ini);
        $hari_pertama = date('N', strtotime("$tahun_ini-$bulan_ini-01")); 
        
        $nama_bulan_indo = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        $nama_bulan = $nama_bulan_indo[$bulan_ini - 1] . ' ' . $tahun_ini;

        $data_absen_db = [];
        if ($anak && $db->tableExists('absensi_harian')) {
            $riwayat_harian = $db->table('absensi_harian')
                                 ->where('siswa_id', $anak['id'])
                                 ->where('MONTH(tanggal)', $bulan_ini)
                                 ->where('YEAR(tanggal)', $tahun_ini)
                                 ->get()->getResultArray();
            
            foreach ($riwayat_harian as $row) {
                $hari = (int)date('j', strtotime($row['tanggal']));
                $data_absen_db[$hari] = $row['status'];
            }
        }

        for ($i = 1; $i <= $jml_hari; $i++) {
            $hari_ke = date('N', strtotime("$tahun_ini-$bulan_ini-$i"));
            
            $status = 'Belum';
            $warna = 'bg-white dark:bg-slate-800 text-slate-400 border-slate-200 dark:border-slate-700 border-dashed';

            if ($hari_ke == 6 || $hari_ke == 7) { 
                $status = 'Libur';
                $warna = 'bg-slate-100 dark:bg-slate-900/50 text-slate-300 dark:text-slate-600 border-slate-200 dark:border-slate-800 cursor-not-allowed';
            } else {
                if (isset($data_absen_db[$i])) {
                    $status_db = $data_absen_db[$i];
                    if ($status_db == 'Hadir') {
                        $status = 'Hadir'; $warna = 'bg-emerald-50 dark:bg-emerald-900/20 text-emerald-600 dark:text-emerald-400 border-emerald-200 dark:border-emerald-800/50 shadow-sm';
                    } elseif ($status_db == 'Sakit') {
                        $status = 'Sakit'; $warna = 'bg-amber-50 dark:bg-amber-900/20 text-amber-600 dark:text-amber-400 border-amber-200 dark:border-amber-800/50 shadow-sm';
                    } elseif ($status_db == 'Izin') {
                        $status = 'Izin'; $warna = 'bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 border-blue-200 dark:border-blue-800/50 shadow-sm';
                    } elseif ($status_db == 'Alpha') {
                        $status = 'Alpha'; $warna = 'bg-rose-50 dark:bg-rose-900/20 text-rose-600 dark:text-rose-400 border-rose-200 dark:border-rose-800/50 shadow-sm';
                    } elseif ($status_db == 'Libur') {
                        $status = 'Libur'; $warna = 'bg-slate-100 dark:bg-slate-900/50 text-slate-300 dark:text-slate-600 border-slate-200 dark:border-slate-800 cursor-not-allowed';
                    }
                } else {
                    $status = 'Belum'; 
                }
            }

            $is_today = ($i == (int)date('j') && $bulan_ini == (int)date('n') && $tahun_ini == (int)date('Y'));
            
            $kalender[] = [
                'tanggal'  => $i,
                'status'   => $status,
                'warna'    => $warna,
                'is_today' => $is_today
            ];
        }

        $bulan_prev = $bulan_ini - 1; $tahun_prev = $tahun_ini;
        if ($bulan_prev < 1) { $bulan_prev = 12; $tahun_prev--; }
        
        $bulan_next = $bulan_ini + 1; $tahun_next = $tahun_ini;
        if ($bulan_next > 12) { $bulan_next = 1; $tahun_next++; }

        $sekolah = $db->table('sekolah')->get()->getRowArray();
        $color = [
            'warna_primary'   => $sekolah ? $sekolah['warna_primary'] : '#10b981',
            'warna_secondary' => $sekolah ? $sekolah['warna_secondary'] : '#ecfdf5',
        ];

        $data = [
            'title'        => 'Kehadiran Ananda',
            'user'         => $namaOrangTua,
            'sapaan'       => $sapaan,
            'color'        => $color,
            'navigations'  => $this->getSidebarMenu(),
            'anak'         => $anak,
            'absen'        => $absen,
            'statistik'    => $statistik,
            'kalender'     => $kalender,      
            'hari_pertama' => $hari_pertama,  
            'nama_bulan'   => $nama_bulan,
            'nav_prev'     => "?bulan=$bulan_prev&tahun=$tahun_prev",
            'nav_next'     => "?bulan=$bulan_next&tahun=$tahun_next"
        ];

        return view('OrangTua/kehadiran', $data);
    }
}