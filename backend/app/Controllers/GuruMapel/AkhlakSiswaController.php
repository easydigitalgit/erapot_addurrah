<?php
namespace App\Controllers\GuruMapel;

use App\Controllers\GuruMapelBaseController;

class AkhlakSiswaController extends GuruMapelBaseController
{
    public function index(): string
    {
        $db = \Config\Database::connect();
        $userId = session()->get('id');

        // 1. Ambil Penugasan Kelas & Mapel
        $penugasan = $db->table('guru_mapel gm')
            ->select('gm.rombel_id, gm.mapel_id, m.nama_mapel, r.nama_rombel')
            ->join('mata_pelajaran m', 'm.id = gm.mapel_id', 'left')
            ->join('rombel r', 'r.id = gm.rombel_id', 'left')
            ->where('gm.user_id', $userId)
            ->get()->getRowArray();

        $rombel_id = $penugasan['rombel_id'] ?? 0;
        $mapel_id = $penugasan['mapel_id'] ?? 0;

        // 2. Ambil Daftar Siswa untuk Dropdown "Ganti Siswa"
        $siswas = [];
        if($rombel_id > 0) {
            $siswas = $db->table('siswa')
                ->where('rombel_id', $rombel_id)
                ->where('status_siswa', 'Aktif')
                ->orderBy('nama_lengkap', 'ASC')
                ->get()->getResultArray();
        }

        $data = [
            'user' => session()->get('nama_lengkap') ?? session()->get('username') ?? 'Guru Mapel',
            'navigations' => $this->getSidebarMenu(),
            'color' => $this->getColor(),
            'info' => [
                'kelas' => $penugasan['nama_rombel'] ?? '-',
                'mapel' => $penugasan['nama_mapel'] ?? '-',
                'rombel_id' => $rombel_id,
                'mapel_id' => $mapel_id
            ],
            'siswas' => $siswas
        ];

        return view('GuruMapel/akhlak-siswa', $data); 
    }

    // Mengambil riwayat dan info siswa saat Dropdown diganti
    public function getSiswaData()
    {
        if (!$this->request->isAJAX()) return $this->response->setStatusCode(404);

        try {
            $db = \Config\Database::connect();
            $siswa_id = (int) $this->request->getGet('siswa_id');
            $mapel_id = (int) $this->request->getGet('mapel_id');

            // Data Profil Siswa
            $siswa = $db->table('siswa')->where('id', $siswa_id)->get()->getRowArray();

            // Riwayat Catatan Akhlak Siswa Ini (Tanpa JOIN agar 100% aman)
            $riwayat = $db->table('catatan_akhlak')
                ->where('siswa_id', $siswa_id)
                ->where('mapel_id', $mapel_id)
                ->orderBy('tanggal', 'DESC')
                ->get()->getResultArray();

            // Hitung Statistik Siswa
            $stats = [
                'total_riwayat' => count($riwayat),
                'perlu_pembinaan' => 0
            ];

            foreach($riwayat as $r) {
                // Samakan dengan value tombol di View
                if(in_array($r['status_pembinaan'], ['Perlu Pembinaan', 'Pembinaan Intensif'])) {
                    $stats['perlu_pembinaan']++;
                }
            }

            return $this->response->setJSON([
                'status' => 'success',
                'siswa' => $siswa,
                'riwayat' => $riwayat,
                'stats' => $stats
            ]);

        } catch (\Exception $e) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Database error: ' . $e->getMessage()
            ]);
        }
    }

    // Menyimpan catatan untuk diteruskan ke Wali Kelas
    public function store()
    {
        if (!$this->request->isAJAX()) return $this->response->setStatusCode(404);

        try {
            $db = \Config\Database::connect();
            
            $data = [
                'siswa_id' => $this->request->getPost('siswa_id'),
                'guru_id' => session()->get('id') ?? 1,
                'mapel_id' => $this->request->getPost('mapel_id'),
                'rombel_id' => $this->request->getPost('rombel_id'),
                'kategori_akhlak' => $this->request->getPost('kategori'),
                'status_pembinaan' => $this->request->getPost('status_pembinaan'),
                'tindak_lanjut' => $this->request->getPost('tindak_lanjut'),
                'catatan' => $this->request->getPost('catatan'),
                'tanggal' => date('Y-m-d H:i:s')
            ];

            $db->table('catatan_akhlak')->insert($data);

            return $this->response->setJSON(['status' => 'success']);
        } catch (\Exception $e) {
            return $this->response->setJSON(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }
}