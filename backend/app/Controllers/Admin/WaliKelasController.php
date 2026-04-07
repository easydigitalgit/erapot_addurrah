<?php

namespace App\Controllers\Admin;

use App\Controllers\AdminBaseController;
use App\Models\Admin\RombelModel;
use App\Models\Admin\GuruTendikModel; // Pastikan model ini ada

class WaliKelasController extends AdminBaseController
{
    protected $rombelModel;
    protected $guruModel;

    public function __construct()
    {
        $this->rombelModel = new RombelModel();
        $this->guruModel   = new GuruTendikModel();
    }

    public function index(): string
    {
        $db = \Config\Database::connect();

        // =====================================================================
        // FIX MUTLAK 1: Tarik Tahun Ajaran yang berstatus 'Aktif' secara dinamis
        // =====================================================================
        $taAktif = $db->table('tahun_ajaran')
            ->where('status', 'Aktif')
            ->get()->getRowArray();
        $strTahunAjaran = $taAktif ? $taAktif['tahun'] . ' (' . $taAktif['semester'] . ')' : 'Belum Diatur';

        // =====================================================================
        // FIX MUTLAK 2: Hitung jumlah siswa riil per Rombel (Status Aktif saja)
        // Mencegah N+1 Query Problem dengan mengelompokkan (GROUP BY)
        // =====================================================================
        $siswaCount = $db->table('siswa')
            ->select('rombel_id, COUNT(id) as total_siswa')
            ->where('status_siswa', 'Aktif')
            ->groupBy('rombel_id')
            ->get()->getResultArray();

        $mapSiswa = [];
        foreach ($siswaCount as $sc) {
            $mapSiswa[$sc['rombel_id']] = $sc['total_siswa'];
        }

        // 1. Ambil Data Rombel (Hanya Tahun Ajaran Aktif)
        $rawRombel = $this->rombelModel
            ->select('rombel.*, guru_tendik.nama_lengkap as nama_wali, guru_tendik.nik, guru_tendik.nuptk')
            ->join('guru_tendik', 'guru_tendik.id = rombel.wali_kelas_id', 'left')
            ->where('rombel.id_tahun_ajaran', $taAktif['id'] ?? 0)
            ->orderBy('rombel.tingkat', 'ASC')
            ->orderBy('rombel.nama_rombel', 'ASC')
            ->findAll();

        // 2. Ambil guru yang secara eksplisit menjabat "Wali Kelas" (Join dengan master_jabatan)
        $guruList = $this->guruModel
            ->select('guru_tendik.id, guru_tendik.nama_lengkap, guru_tendik.nik, guru_tendik.nuptk')
            ->join('master_jabatan', 'master_jabatan.id = guru_tendik.jabatan_id', 'left')
            ->groupStart()
                ->where('master_jabatan.nama_jabatan', 'Wali Kelas')
                // Fallback pencarian manual jika ID tidak pas
                ->orWhere('guru_tendik.jabatan_id', 1) 
            ->groupEnd()
            ->orderBy('guru_tendik.nama_lengkap', 'ASC')
            ->findAll();

        // 3. Hitung Statistik
        $totalRombel = count($rawRombel);
        $assigned    = 0;
        $waliIds     = [];

        // 4. Format Data untuk JS
        $formattedData = array_map(function ($row) use (&$assigned, &$waliIds, $strTahunAjaran, $mapSiswa) {
            $isAssigned = !empty($row['wali_kelas_id']);

            if ($isAssigned) {
                $assigned++;
                if (!in_array($row['wali_kelas_id'], $waliIds)) {
                    $waliIds[] = $row['wali_kelas_id'];
                }
            }

            // Pecah Nama Rombel secara aman (Fallback jika tidak ada tanda strip)
            $parts = explode('-', $row['nama_rombel']);
            $rombelKode = isset($parts[1]) ? $parts[1] : $row['nama_rombel'];

            // Coba ambil NIK, kalau kosong ambil NUPTK, kalau kosong tampilkan strip (-)
            $identitasPegawai = !empty($row['nik']) ? $row['nik'] : (!empty($row['nuptk']) ? $row['nuptk'] : '-');

            return [
                'id'          => $row['id'],
                'level'       => $row['tingkat'], // VII, VIII, IX
                'rombel'      => $rombelKode,     // A, B, C
                'full_rombel' => $row['nama_rombel'],
                'teacher'     => $row['nama_wali'] ?? '',
                'teacher_id'  => $row['wali_kelas_id'] ?? '',
                'nip'         => $identitasPegawai,
                'tahunAjaran' => $strTahunAjaran,              // SUDAH DINAMIS
                'students'    => $mapSiswa[$row['id']] ?? 0,   // SUDAH DINAMIS DARI DB
                'status'      => $isAssigned ? 'assigned' : 'unassigned'
            ];
        }, $rawRombel);

        $stats = [
            'total'      => $totalRombel,
            'assigned'   => $assigned,
            'unassigned' => $totalRombel - $assigned,
            'active'     => count($waliIds)
        ];

        $data = [
            'user'             => 'Admin',
            'navigations'      => $this->getSidebarMenu(),
            'color'            => $this->getColor(),
            'waliKelasData'    => $formattedData,
            'guruList'         => $guruList,
            'stats'            => $stats,
            'tahunAjaranAktif' => $strTahunAjaran
        ];

        return view('admin/wali-kelas', $data);
    }

    // Assign / Update Wali Kelas
    public function update()
    {
        $db = \Config\Database::connect();
        $rombelId = $this->request->getPost('assign_rombel_id'); 
        $guruId   = $this->request->getPost('assign_guru');      

        if (!$guruId) {
            $rombelId = $this->request->getPost('change_rombel_id');
            $guruId   = $this->request->getPost('change_guru');
        }

        // AMBIL DATA TA AKTIF UNTUK RIWAYAT & VALIDASI
        $taAktif = $db->table('tahun_ajaran')->where('status', 'Aktif')->get()->getRowArray();
        $idTaAktif = $taAktif ? $taAktif['id'] : 0;
        $smtAktif  = $taAktif ? $taAktif['semester'] : 'Ganjil';

        // =========================================================================
        // VALIDASI: Pastikan guru tersebut belum menjadi Wali Kelas di Rombel lain 
        // pada TAHUN AJARAN YANG SAMA (Agar tidak terjadi bentrok penugasan aktif)
        // =========================================================================
        $checkExisting = $this->rombelModel->where('wali_kelas_id', $guruId)
                                            ->where('id_tahun_ajaran', $idTaAktif)
                                            ->where('id !=', $rombelId)
                                            ->first();
        if ($checkExisting) {
            $namaR = $checkExisting['nama_rombel'] ?? 'Rombel Lain';
            return $this->response->setJSON(['status' => 'error', 'message' => "Guru ini sudah menjabat sebagai Wali Kelas di $namaR pada tahun ajaran ini!"]);
        }

        if ($this->rombelModel->update($rombelId, ['wali_kelas_id' => $guruId])) {
            
            // LOG KE RIWAYAT JABATAN GURU
            $rombelInfo = $this->rombelModel->find($rombelId);
            $namaRombel = $rombelInfo ? $rombelInfo['nama_rombel'] : 'Rombel';

            // Cek apakah sudah ada catatan riwayat yang identik (Guru + TA + Semester + Rombel)
            // Agar tidak ada duplikasi data riwayat jika ustadz meng-edit penugasan yang sama
            $historyCheck = $db->table('riwayat_jabatan_guru')
                               ->where(['guru_id' => $guruId, 'tahun_ajaran_id' => $idTaAktif, 'semester' => $smtAktif, 'jabatan' => 'Wali Kelas'])
                               ->get()->getRow();
            
            if (!$historyCheck) {
                $db->table('riwayat_jabatan_guru')->insert([
                    'guru_id'         => $guruId,
                    'tahun_ajaran_id' => $idTaAktif,
                    'semester'        => $smtAktif,
                    'jabatan'         => 'Wali Kelas',
                    'keterangan'      => "Menjabat di Kelas $namaRombel",
                    'created_at'      => date('Y-m-d H:i:s')
                ]);
            }

            return $this->response->setJSON(['status' => 'success', 'message' => 'Wali kelas berhasil ditetapkan & Riwayat dicatat']);
        }

        return $this->response->setJSON(['status' => 'error', 'message' => 'Gagal menyimpan data']);
    }

    // Hapus Wali Kelas (Set Null)
    public function delete()
    {
        $rombelId = $this->request->getPost('rombel_id');

        if ($this->rombelModel->update($rombelId, ['wali_kelas_id' => null])) {
            return $this->response->setJSON(['status' => 'success', 'message' => 'Penugasan berhasil dilepas']);
        }

        return $this->response->setJSON(['status' => 'error', 'message' => 'Gagal melepas penugasan']);
    }
}
