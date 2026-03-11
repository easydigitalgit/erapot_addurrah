<?php

namespace App\Controllers\OrangTua;

use App\Controllers\OrangTuaBaseController;

class KehadiranController extends OrangTuaBaseController
{
    public function index()
    {
        $db = \Config\Database::connect();
        $userId = session()->get('user_id');

        // 1. CARI DATA ORANG TUA
        $orangTua = $db->table('orangtua_wali')->where('user_id', $userId)->get()->getRowArray();
        
        $sapaan = 'Bapak/Ibu'; 
        $namaOrangTua = session()->get('nama_lengkap') ?? session()->get('username');
        $siswaId = 0;

        if ($orangTua) {
            $siswaId = $orangTua['siswa_id'];
            if (!empty($orangTua['nama_ayah'])) { $sapaan = 'Bapak'; $namaOrangTua = $orangTua['nama_ayah']; }
            elseif (!empty($orangTua['nama_ibu'])) { $sapaan = 'Ibu'; $namaOrangTua = $orangTua['nama_ibu']; }
            elseif (!empty($orangTua['nama_wali'])) { $sapaan = 'Bapak/Ibu'; $namaOrangTua = $orangTua['nama_wali']; }
        }

        // 2. CARI DATA ANAK
        $anak = $db->table('siswa')
                   ->select('siswa.id, siswa.nama_lengkap, siswa.nis, rombel.nama_rombel as kelas, rombel.tingkat, rombel.semester')
                   ->join('rombel', 'rombel.id = siswa.rombel_id', 'left')
                   ->where('siswa.id', $siswaId)
                   ->get()->getRowArray();

        // 3. STATISTIK TOTAL SEMESTER (Tetap ambil dari rekap_absensi agar grafik tidak error)
        $absen = ['sakit' => 0, 'izin' => 0, 'alpha' => 0];
        $statistik = ['total_hari_sekolah' => 120, 'total_hadir' => 120, 'total_absen' => 0, 'persentase' => 100];

        if ($anak && $db->tableExists('rekap_absensi')) {
            $rekap = $db->table('rekap_absensi')->where('siswa_id', $anak['id'])->where('semester', $anak['semester'] ?? 'Ganjil')->get()->getRowArray();
            if ($rekap) {
                $absen['sakit'] = (int)$rekap['sakit']; $absen['izin'] = (int)$rekap['izin']; $absen['alpha'] = (int)$rekap['alpha'];
                $statistik['total_absen'] = $absen['sakit'] + $absen['izin'] + $absen['alpha'];
                $statistik['total_hadir'] = max(0, $statistik['total_hari_sekolah'] - $statistik['total_absen']);
                $statistik['persentase']  = round(($statistik['total_hadir'] / $statistik['total_hari_sekolah']) * 100, 1);
            }
        }

        // =========================================================================
        // 4. LOGIKA KALENDER HARIAN ASLI DARI DATABASE (Tabel absensi_harian)
        // =========================================================================
        $kalender = [];
        
        // Ambil filter bulan & tahun dari URL (jika tidak ada, gunakan bulan ini)
        $bulan_ini = $this->request->getGet('bulan') ?: date('n');
        $tahun_ini = $this->request->getGet('tahun') ?: date('Y');
        
        $jml_hari = cal_days_in_month(CAL_GREGORIAN, $bulan_ini, $tahun_ini);
        $hari_pertama = date('N', strtotime("$tahun_ini-$bulan_ini-01")); 
        
        $nama_bulan_indo = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        $nama_bulan = $nama_bulan_indo[$bulan_ini - 1] . ' ' . $tahun_ini;

        // Ambil data absen anak pada bulan dan tahun tersebut
        $data_absen_db = [];
        if ($anak && $db->tableExists('absensi_harian')) {
            $riwayat_harian = $db->table('absensi_harian')
                                 ->where('siswa_id', $anak['id'])
                                 ->where('MONTH(tanggal)', $bulan_ini)
                                 ->where('YEAR(tanggal)', $tahun_ini)
                                 ->get()->getResultArray();
            
            // Susun data agar mudah dicari berdasarkan tanggal (hari)
            foreach ($riwayat_harian as $row) {
                $hari = (int)date('j', strtotime($row['tanggal']));
                $data_absen_db[$hari] = $row['status'];
            }
        }

        // Bangun Grid Kalender
        for ($i = 1; $i <= $jml_hari; $i++) {
            $hari_ke = date('N', strtotime("$tahun_ini-$bulan_ini-$i"));
            
            // Default Status jika belum ada di DB
            $status = 'Belum';
            $warna = 'bg-slate-50 text-slate-400 border-slate-200 border-dashed';

            if ($hari_ke == 6 || $hari_ke == 7) { 
                // Jika Sabtu/Minggu otomatis Libur
                $status = 'Libur';
                $warna = 'bg-slate-100 text-slate-300 border-slate-200 cursor-not-allowed';
            } else {
                // Cek apakah tanggal ini sudah diabsen oleh guru di Database
                if (isset($data_absen_db[$i])) {
                    $status_db = $data_absen_db[$i];
                    if ($status_db == 'Hadir') {
                        $status = 'Hadir'; $warna = 'bg-emerald-50 text-emerald-600 border-emerald-300 shadow-sm';
                    } elseif ($status_db == 'Sakit') {
                        $status = 'Sakit'; $warna = 'bg-amber-50 text-amber-600 border-amber-400 shadow-sm';
                    } elseif ($status_db == 'Izin') {
                        $status = 'Izin'; $warna = 'bg-blue-50 text-blue-600 border-blue-400 shadow-sm';
                    } elseif ($status_db == 'Alpha') {
                        $status = 'Alpha'; $warna = 'bg-rose-50 text-rose-600 border-rose-400 shadow-sm';
                    } elseif ($status_db == 'Libur') {
                        $status = 'Libur'; $warna = 'bg-slate-100 text-slate-300 border-slate-200 cursor-not-allowed';
                    }
                } else {
                    // Jika di database tidak ada
                    if ($i > date('j') && $bulan_ini == date('n') && $tahun_ini == date('Y')) {
                        // Jika tanggal di masa depan
                        $status = 'Belum'; 
                    } else {
                        // Jika tanggal di masa lalu tapi tidak ada data
                        $status = 'Belum'; 
                    }
                }
            }

            $kalender[] = [
                'tanggal' => $i,
                'status'  => $status,
                'warna'   => $warna
            ];
        }

        // Variabel untuk navigasi bulan sebelumnya / selanjutnya
        $bulan_prev = $bulan_ini - 1; $tahun_prev = $tahun_ini;
        if ($bulan_prev < 1) { $bulan_prev = 12; $tahun_prev--; }
        
        $bulan_next = $bulan_ini + 1; $tahun_next = $tahun_ini;
        if ($bulan_next > 12) { $bulan_next = 1; $tahun_next++; }

        $data = [
            'title'        => 'Kehadiran Ananda',
            'user'         => $namaOrangTua,
            'sapaan'       => $sapaan,
            'color'        => $this->getColor(),
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