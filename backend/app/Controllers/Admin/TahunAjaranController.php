<?php

namespace App\Controllers\Admin;

use App\Controllers\AdminBaseController;
use App\Models\Admin\TahunAjaranModel;
use App\Models\Admin\SiswaModel;
use App\Models\Admin\GuruTendikModel;

class TahunAjaranController extends AdminBaseController
{
    protected $tahunAjaranModel;
    protected $siswaModel;
    protected $guruModel;

    public function __construct()
    {
        $this->tahunAjaranModel = new TahunAjaranModel();
        $this->siswaModel = new SiswaModel();
        $this->guruModel  = new GuruTendikModel();
    }

    public function index(): string
    {
        $db = \Config\Database::connect();
        $allYears = $this->tahunAjaranModel->orderBy('id', 'DESC')->findAll();
        $activeYear = $this->tahunAjaranModel->where('status', 'Aktif')->first();

        // Mengambil jumlah siswa aktif dan total guru dari database
        $totalSiswa = $this->siswaModel->where('status_siswa', 'Aktif')->countAllResults();
        $totalGuru  = $this->guruModel->countAllResults();

        $historyData = array_map(function ($row) use ($db) {
            $isLocked = ($row['status'] == 'Arsip');
            
            // 1. HITUNG GURU (Dinamis: Aktif vs Arsip)
            if ($row['status'] == 'Aktif') {
                // Untuk tahun aktif, hitung guru yang menjabat Wali Kelas ATAU ada di Jadwal Pelajaran
                $qWali = $db->table('rombel')->select('wali_kelas_id as guru_id')->where('id_tahun_ajaran', $row['id'])->where('wali_kelas_id IS NOT NULL', null, false);
                $qMapel = $db->table('jadwal_pelajaran')->select('guru_id')->where('id_tahun_ajaran', $row['id']);
                
                // Gabungkan dan hitung DISTINCT ID
                $teachersCount = $db->table('(' . $qWali->getCompiledSelect() . ' UNION ' . $qMapel->getCompiledSelect() . ') as t')
                                    ->countAllResults();
            } else {
                // Untuk sejarah, ambil dari tabel riwayat_jabatan_guru
                $teachersCount = $db->table('riwayat_jabatan_guru')
                                    ->where('tahun_ajaran_id', $row['id'])
                                    ->countAllResults();
            }

            // 2. HITUNG SISWA
            $studentsCount = $db->table('siswa s')
                                ->join('rombel r', 'r.id = s.rombel_id')
                                ->where('r.id_tahun_ajaran', $row['id'])
                                ->countAllResults();

            return [
                'id'        => (int) $row['id'],
                'year'      => $row['tahun'],
                'semester'  => $row['semester'],
                'status'    => strtolower($row['status']),
                'students'  => $studentsCount > 0 ? number_format($studentsCount) : '-',
                'teachers'  => $teachersCount > 0 ? number_format($teachersCount) : '-',
                'tgl_mulai' => $row['tgl_mulai'],
                'tgl_akhir' => $row['tgl_akhir'],
                'locked'    => $isLocked
            ];
        }, $allYears);

        // ====================================================================
        // ALGORITMA PERHITUNGAN PROGRESS BAR DINAMIS
        // ====================================================================
        $progressPercent = 0;
        $estimasiText = 'Tanggal mulai atau akhir semester belum diset.';

        if ($activeYear && !empty($activeYear['tgl_mulai']) && !empty($activeYear['tgl_akhir'])) {
            $start = strtotime($activeYear['tgl_mulai'] . ' 00:00:00');
            $end   = strtotime($activeYear['tgl_akhir'] . ' 23:59:59');
            $now   = time();

            if ($now < $start) {
                // Semester belum mulai
                $progressPercent = 0;
                $diffDays = ceil(($start - $now) / 86400);
                $estimasiText = "Estimasi: Semester akan dimulai dalam $diffDays hari.";
            } elseif ($now > $end) {
                // Semester sudah lewat/berakhir
                $progressPercent = 100;
                $estimasiText = "Status: Semester telah berakhir.";
            } else {
                // Semester sedang berjalan
                $totalDuration = max(1, $end - $start); // Mencegah division by zero
                $elapsed = $now - $start;
                $progressPercent = round(($elapsed / $totalDuration) * 100);

                // Menghitung sisa waktu untuk teks estimasi
                $remainingSeconds = $end - $now;
                $remainingDays = ceil($remainingSeconds / 86400);

                if ($remainingDays > 30) {
                    $remainingMonths = floor($remainingDays / 30);
                    $sisaHari = $remainingDays % 30;
                    $teksTambahan = $sisaHari > 0 ? " dan $sisaHari hari" : "";
                    $estimasiText = "Estimasi: $remainingMonths bulan$teksTambahan lagi hingga semester berakhir.";
                } else {
                    $estimasiText = "Estimasi: $remainingDays hari lagi hingga semester berakhir.";
                }
            }
        }
        // ====================================================================

        $data = [
            'user'            => 'Admin',
            'navigations'     => $this->getSidebarMenu(),
            'color'           => $this->getColor(),
            'activeYear'      => $activeYear,
            'yearData'        => $historyData,
            // Lempar variabel dinamis ini ke View
            'progressPercent' => $progressPercent,
            'estimasiText'    => $estimasiText
        ];

        return view('admin/tahun-ajaran', $data);
    }
    public function store()
    {
        $tahun     = $this->request->getPost('new_year');
        $semester  = $this->request->getPost('semester_awal');

        $tgl_mulai = $this->request->getPost('start_date');
        $tgl_akhir = $this->request->getPost('end_date');

        if (!$tahun || !$semester || !$tgl_mulai || !$tgl_akhir) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Data form (termasuk tanggal) tidak boleh kosong!']);
        }

        $dataInsert = [
            'tahun'     => $tahun,
            'semester'  => $semester,
            'status'    => 'Arsip',
            'tgl_mulai' => $tgl_mulai,
            'tgl_akhir' => $tgl_akhir
        ];

        try {
            $this->tahunAjaranModel->insert($dataInsert);
            return $this->response->setJSON(['status' => 'success', 'message' => 'Tahun ajaran beserta tanggal berhasil dibuat!']);
        } catch (\Exception $e) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Gagal Insert Database: ' . $e->getMessage()]);
        }
    }

    public function activate()
    {
        $id = $this->request->getPost('id');

        $this->tahunAjaranModel->set('status', 'Arsip')->where('id !=', $id)->update();
        $this->tahunAjaranModel->update($id, ['status' => 'Aktif']);

        return $this->response->setJSON(['status' => 'success', 'message' => 'Tahun ajaran berhasil diaktifkan!']);
    }

    public function changeSemester()
    {
        $activeYear = $this->tahunAjaranModel->where('status', 'Aktif')->first();

        if (!$activeYear) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Tidak ada Tahun Ajaran yang aktif!']);
        }

        $newSemester = ($activeYear['semester'] == 'Ganjil') ? 'Genap' : 'Ganjil';
        $this->tahunAjaranModel->update($activeYear['id'], ['semester' => $newSemester]);

        return $this->response->setJSON([
            'status' => 'success',
            'message' => "Semester berhasil diganti ke $newSemester!"
        ]);
    }

    public function show($id)
    {
        $data = $this->tahunAjaranModel->find($id);
        if ($data) {
            return $this->response->setJSON(['status' => 'success', 'data' => $data]);
        }
        return $this->response->setJSON(['status' => 'error', 'message' => 'Data tidak ditemukan'])->setStatusCode(404);
    }

    public function update()
    {
        $id = $this->request->getPost('id');

        if (!$id) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'ID tidak ditemukan.']);
        }

        $data = [
            'tahun'     => $this->request->getPost('edit_year'),
            'semester'  => $this->request->getPost('edit_semester'),
            'tgl_mulai' => $this->request->getPost('edit_start_date'),
            'tgl_akhir' => $this->request->getPost('edit_end_date')
        ];

        if ($this->tahunAjaranModel->update($id, $data)) {
            return $this->response->setJSON(['status' => 'success', 'message' => 'Data tahun ajaran berhasil diperbarui!']);
        } else {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Gagal memperbarui database.']);
        }
    }

    public function delete()
    {
        $id = $this->request->getPost('id');

        if (!$id) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'ID tidak ditemukan.']);
        }

        $data = $this->tahunAjaranModel->find($id);
        if ($data && $data['status'] == 'Aktif') {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Tahun ajaran yang sedang aktif tidak bisa dihapus!']);
        }

        if ($this->tahunAjaranModel->delete($id)) {
            return $this->response->setJSON(['status' => 'success', 'message' => 'Data tahun ajaran berhasil dihapus!']);
        } else {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Gagal menghapus data dari database.']);
        }
    }

    /**
     * Menonaktifkan sistem: 
     * Mengubah SEMUA tahun ajaran yang berstatus 'Aktif' menjadi 'Arsip'.
     */
    public function deactivateAll()
    {
        try {
            // Lakukan update massal: Ubah Aktif jadi Arsip
            $this->tahunAjaranModel->where('status', 'Aktif')->set(['status' => 'Arsip'])->update();

            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Sistem berhasil dinonaktifkan! Tidak ada tahun ajaran yang aktif saat ini.'
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Gagal menonaktifkan sistem: ' . $e->getMessage()
            ]);
        }
    }
}
