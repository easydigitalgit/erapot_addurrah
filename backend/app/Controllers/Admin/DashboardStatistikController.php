<?php
namespace App\Controllers\Admin;

use App\Controllers\AdminBaseController;
use App\Models\Admin\SiswaModel;
use App\Models\Admin\GuruTendikModel;
use App\Models\Admin\RombelModel;

class DashboardStatistikController extends AdminBaseController
{
    public function index(): string
    {
        $db = \Config\Database::connect();
        
        $siswaModel = new SiswaModel();
        $guruTendikModel = new GuruTendikModel();
        $rombelModel = new RombelModel();

        // 1. AMBIL TAHUN AJARAN AKTIF DARI DATABASE
        $taAktif = $db->table('tahun_ajaran')->where('status', 'Aktif')->get()->getRowArray();
        $tahun_ajaran = $taAktif ? $taAktif['tahun'] : 'Belum Diset';
        $semester_aktif = $taAktif ? $taAktif['semester'] : 'Belum Diset';
        $idTaAktif = $taAktif ? $taAktif['id'] : null;

        // 2. KIRAAN ASAS (BASIC STATS)
        $totalSiswa = $siswaModel->where('status_siswa', 'Aktif')->countAllResults();
        $totalGuruTendik = $guruTendikModel->countAllResults();
        $totalRombel = $rombelModel->countAllResults();

        // 3. DATA PROGRES GURU & NOTIFIKASI PENDING GRADES
        $progressGuru = [];
        $guruSudahInput = 0;
        $guruBelumInput = 0; 
        
        if ($db->tableExists('guru_tendik') && $db->tableExists('nilai_akademik')) {
            $semuaGuru = $db->table('guru_tendik')->select('id, nama_lengkap')->get()->getResultArray();
            
            foreach ($semuaGuru as $guru) {
                // Periksa jumlah rekod nilai yang telah dimasukkan oleh guru ini di tahun ajaran aktif
                $jumlahInput = $db->table('nilai_akademik')
                                  ->where('guru_id', $guru['id'])
                                  ->where('semester', $semester_aktif)
                                  ->countAllResults(); 
                
                $status = 'Belum Mulai';
                $peratus = 0;
                
                if ($jumlahInput > 20) { 
                    $status = 'Selesai';
                    $peratus = 100;
                    $guruSudahInput++;
                } elseif ($jumlahInput > 0) {
                    $status = 'Proses';
                    $peratus = min(round(($jumlahInput / 20) * 100), 99);
                    $guruSudahInput++;
                } else {
                    $guruBelumInput++; // Hitung guru yang belum input sama sekali
                }

                if ($peratus > 0) { 
                    $parts = explode(' ', trim($guru['nama_lengkap']));
                    $inisial = strtoupper(substr($parts[0], 0, 1) . (isset($parts[1]) ? substr($parts[1], 0, 1) : ''));

                    $progressGuru[] = [
                        'nama'    => $guru['nama_lengkap'],
                        'inisial' => $inisial,
                        'peratus' => $peratus,
                        'status'  => $status
                    ];
                }
            }
        }

        // 4. STATISTIK TAHFIDZ & KARAKTER 
        $avgTahfidz = 0; 
        if ($db->tableExists('setoran_tahfidz') && $totalSiswa > 0) {
            $pelajarAktifTahfidz = $db->table('setoran_tahfidz')
                                      ->select('siswa_id')
                                      ->distinct()
                                      ->countAllResults();
                                      
            if ($pelajarAktifTahfidz > 0) {
                $avgTahfidz = min(round(($pelajarAktifTahfidz / $totalSiswa) * 100), 100); 
            }
        }

        $statKarakter = ['sangat_baik' => 0, 'baik' => 0, 'perlu_binaan' => 0];
        if ($db->tableExists('catatan_akhlak')) {
            $statKarakter['sangat_baik'] = $db->table('catatan_akhlak')->where('kategori_akhlak', 'Sangat Baik')->countAllResults();
            $statKarakter['baik'] = $db->table('catatan_akhlak')->where('kategori_akhlak', 'Baik')->countAllResults();
            $statKarakter['perlu_binaan'] = $db->table('catatan_akhlak')->where('kategori_akhlak', 'Perlu Pembinaan')->countAllResults();
        }

        // 5. NOTIFIKASI SISTEM (System Notifications) Dinamis
        // A. Cek Rombel yang belum punya Wali Kelas (Report Validation Issue)
        $rombelTanpaWali = $db->table('rombel')
                              ->where('wali_kelas_id IS NULL')
                              ->where('id_tahun_ajaran', $idTaAktif)
                              ->countAllResults();

        // B. Pesan Notifikasi Dinamis
        $notifPendingGrades = $guruBelumInput > 0 
            ? "Ada $guruBelumInput guru yang belum mengisi nilai di semester ini. Harap kirim pengingat." 
            : "Semua guru terpantau sudah mulai mengisi nilai akademik.";
        
        $notifValidation = $rombelTanpaWali > 0 
            ? "Terdapat $rombelTanpaWali rombel yang belum memiliki wali kelas. Laporan tidak bisa divalidasi." 
            : "Semua rombel sudah memiliki wali kelas. Data siap divalidasi.";
            
        $notifBackup = ($taAktif && $taAktif['is_locked'] == 1) 
            ? "Tahun Ajaran ini sudah dikunci. Tidak perlu backup darurat." 
            : "Disarankan melakukan backup database bulan ini sebelum mencetak rapor.";

        // 6. BUNGKUS DATA KE VIEW
        $data = [
            'title'             => 'Dashboard Statistik',
            'user'              => session()->get('username') ?? 'Admin',
            'navigations'       => $this->getSidebarMenu(),
            'color'             => $this->getColor(),
            'total_siswa'       => $totalSiswa,
            'total_guru_tendik' => $totalGuruTendik,
            'total_rombel'      => $totalRombel,
            'guru_sudah_input'  => $guruSudahInput,
            'progress_guru'     => $progressGuru,
            'avg_tahfidz'       => $avgTahfidz,
            'stat_karakter'     => $statKarakter,
            'school_name'       => 'SMPIT Ad Durrah',
            'academic_year'     => $tahun_ajaran,
            'semester'          => $semester_aktif,
            
            // PERBAIKAN: Penamaan Key disamakan dengan yang ada di file View (_msg)
            'notif_grades_msg'  => $notifPendingGrades,
            'notif_grades_err'  => $guruBelumInput > 0, // Boolean
            'notif_valid_msg'   => $notifValidation,
            'notif_valid_err'   => $rombelTanpaWali > 0, // Boolean
            'notif_backup'      => $notifBackup
        ];

        return view('admin/dashboard-statistik', $data); 
    }
}