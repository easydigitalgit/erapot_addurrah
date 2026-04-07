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

        // 2. KIRAAN ASAS (BASIC STATS - Berdasarkan TA Aktif)
        $totalSiswa = $db->table('siswa s')
            ->join('rombel r', 'r.id = s.rombel_id')
            ->where(['s.status_siswa' => 'Aktif', 'r.id_tahun_ajaran' => $idTaAktif])
            ->countAllResults();
        $totalGuruTendik = $guruTendikModel->countAllResults();
        $totalRombel = $rombelModel->where('id_tahun_ajaran', $idTaAktif)->countAllResults();

        // 3. DATA PROGRES GURU (Berdasarkan Penugasan Guru Mapel di TA Aktif)
        $progressGuru = [];
        $guruSudahInput = 0;
        $guruBelumInput = 0;

        // Ambil daftar guru yang memiliki penugasan di tahun ajaran ini
        $guruTugas = $db->table('guru_mapel gm')
            ->select('gm.guru_id, gt.nama_lengkap')
            ->join('guru_tendik gt', 'gt.id = gm.guru_id')
            ->join('rombel r', 'r.id = gm.rombel_id')
            ->where('r.id_tahun_ajaran', $idTaAktif)
            ->groupBy('gm.guru_id')
            ->get()->getResultArray();

        $tabelAcuan = $db->tableExists('nilai_akademik') ? 'nilai_akademik' : ($db->tableExists('nilai_formatif') ? 'nilai_formatif' : 'nilai_sumatif');
        $fieldTA    = $db->fieldExists('tahun_ajaran_id', $tabelAcuan) ? 'tahun_ajaran_id' : 'tahun_ajaran';
        $fieldSmt   = $db->fieldExists('semester', $tabelAcuan) ? 'semester' : null;

        foreach ($guruTugas as $g) {
            // 1. Hitung total kelas yang diampu guru ini di TA aktif
            $totalKelas = $db->table('guru_mapel gm')
                ->join('rombel r', 'r.id = gm.rombel_id')
                ->where(['gm.guru_id' => $g['guru_id'], 'r.id_tahun_ajaran' => $idTaAktif])
                ->countAllResults();

            if ($totalKelas == 0) continue;

            // 2. Hitung berapa kelas yang sudah mulai diisi nilainya
            $kelasSelesai = 0;
            $penugasanGuru = $db->table('guru_mapel gm')
                ->join('rombel r', 'r.id = gm.rombel_id')
                ->where(['gm.guru_id' => $g['guru_id'], 'r.id_tahun_ajaran' => $idTaAktif])
                ->get()->getResultArray();

            foreach ($penugasanGuru as $p) {
                $qCek = $db->table($tabelAcuan)
                    ->where(['guru_id' => $g['guru_id'], 'mapel_id' => $p['mapel_id'], 'rombel_id' => $p['rombel_id']]);
                
                if ($fieldTA === 'tahun_ajaran_id') {
                    $qCek->where('tahun_ajaran_id', $idTaAktif);
                } else {
                    $qCek->where('tahun_ajaran', $tahun_ajaran);
                }
                
                if ($fieldSmt && $semester_aktif !== 'Belum Diset') {
                    $qCek->where('semester', $semester_aktif);
                }

                if ($qCek->countAllResults() > 0) {
                    $kelasSelesai++;
                }
            }

            // 3. Kalkulasi Persentase
            $peratus = round(($kelasSelesai / $totalKelas) * 100);
            $status = ($peratus >= 100) ? 'Selesai' : (($peratus > 0) ? 'Proses' : 'Belum Mulai');

            if ($peratus > 0) {
                $guruSudahInput++;
                
                $parts = explode(' ', trim($g['nama_lengkap']));
                $inisial = strtoupper(substr($parts[0], 0, 1) . (isset($parts[1]) ? substr($parts[1], 0, 1) : ''));

                $progressGuru[] = [
                    'nama'    => $g['nama_lengkap'],
                    'inisial' => $inisial,
                    'peratus' => $peratus,
                    'status'  => $status
                ];
            } else {
                $guruBelumInput++;
            }
        }

        // 4. STATISTIK TAHFIDZ & KARAKTER (Filter Tahun Aktif melalui Join Rombel)
        $avgTahfidz = 0;
        if ($db->tableExists('setoran_tahfidz') && $totalSiswa > 0) {
            $pelajarAktifTahfidz = $db->table('setoran_tahfidz st')
                ->join('siswa s', 's.id = st.siswa_id')
                ->join('rombel r', 'r.id = s.rombel_id')
                ->where('r.id_tahun_ajaran', $idTaAktif)
                ->select('st.siswa_id')
                ->distinct()
                ->countAllResults();

            if ($pelajarAktifTahfidz > 0) {
                $avgTahfidz = min(round(($pelajarAktifTahfidz / $totalSiswa) * 100), 100);
            }
        }

        $statKarakter = ['sangat_baik' => 0, 'baik' => 0, 'perlu_binaan' => 0];
        if ($db->tableExists('catatan_akhlak')) {
            $baseKarakter = $db->table('catatan_akhlak ca')
                ->join('siswa s', 's.id = ca.siswa_id')
                ->join('rombel r', 'r.id = s.rombel_id')
                ->where('r.id_tahun_ajaran', $idTaAktif);

            $statKarakter['sangat_baik'] = (clone $baseKarakter)->where('ca.kategori_akhlak', 'Sangat Baik')->countAllResults();
            $statKarakter['baik'] = (clone $baseKarakter)->where('ca.kategori_akhlak', 'Baik')->countAllResults();
            $statKarakter['perlu_binaan'] = (clone $baseKarakter)->where('ca.kategori_akhlak', 'Perlu Pembinaan')->countAllResults();
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

        // 6. AMBIL NAMA SEKOLAH DARI DATABASE (Tabel identitas_sekolah)
        $sekolahData = $db->table('sekolah')->get()->getRowArray() ?? [];
        $namaSekolah = !empty($sekolahData['nama_sekolah']) ? $sekolahData['nama_sekolah'] : 'SMPIT Ad Durrah';

        // 7. AMBIL NAMA ASLI USER LANGSUNG DARI DATABASE (BYPASS SESSION)
        $userId = session()->get('id');
        // Asumsi nama tabel user kamu adalah 'users'. Sesuaikan jika namanya berbeda (misal: 'akun' atau 'user')
        $userData = $db->table('users')->select('nama_lengkap')->where('id', $userId)->get()->getRowArray();

        $namaAsliUser = !empty($userData['nama_lengkap']) ? $userData['nama_lengkap'] : (session()->get('username') ?? 'Admin');
        // 8. BUNGKUS DATA KE VIEW

        $data = [
            'title'             => 'Dashboard Statistik',
            'user'              => $namaAsliUser, // Nama Lengkap
            'school_name'       => $namaSekolah,  // Nama Sekolah Dinamis
            'navigations'       => $this->getSidebarMenu(),
            'color'             => $this->getColor(),
            'total_siswa'       => $totalSiswa,
            'total_guru_tendik' => $totalGuruTendik,
            'total_rombel'      => $totalRombel,
            'guru_sudah_input'  => $guruSudahInput,
            'progress_guru'     => $progressGuru,
            'avg_tahfidz'       => $avgTahfidz,
            'stat_karakter'     => $statKarakter,
            'academic_year'     => $tahun_ajaran,
            'semester'          => $semester_aktif,

            // Notifikasi
            'notif_grades_msg'  => $notifPendingGrades,
            'notif_grades_err'  => $guruBelumInput > 0,
            'notif_valid_msg'   => $notifValidation,
            'notif_valid_err'   => $rombelTanpaWali > 0,
            'notif_backup'      => $notifBackup
        ];

        return view('admin/dashboard-statistik', $data);
    }
}
