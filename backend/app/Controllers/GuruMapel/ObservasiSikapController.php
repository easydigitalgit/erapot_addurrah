<?php
namespace App\Controllers\GuruMapel;

use App\Controllers\GuruMapelBaseController;

class ObservasiSikapController extends GuruMapelBaseController
{
    public function index(): string
    {
        $db = \Config\Database::connect();
        $userId = session()->get('id');

        // 1. Ambil Penugasan Kelas
        $penugasan = $db->table('guru_mapel gm')
            ->select('gm.rombel_id, gm.mapel_id, m.nama_mapel, r.nama_rombel')
            ->join('mata_pelajaran m', 'm.id = gm.mapel_id', 'left')
            ->join('rombel r', 'r.id = gm.rombel_id', 'left')
            ->where('gm.user_id', $userId)
            ->get()->getRowArray();

        $rombel_id = $penugasan['rombel_id'] ?? 0;
        $mapel_id = $penugasan['mapel_id'] ?? 0;

        // 2. Ambil Daftar Siswa untuk Modal Dropdown
        $siswa = [];
        if($rombel_id > 0){
            $siswa = $db->table('siswa')
                ->where('rombel_id', $rombel_id)
                ->where('status_siswa', 'Aktif')
                ->orderBy('nama_lengkap', 'ASC')
                ->get()->getResultArray();
        }

        // 3. Hitung Statistik Observasi
        $minggu_ini = $db->table('observasi_sikap')
            ->where('guru_id', $userId)
            ->where('rombel_id', $rombel_id)
            ->where('YEARWEEK(tanggal, 1) = YEARWEEK(CURDATE(), 1)')
            ->countAllResults();

        $pembinaan = $db->table('observasi_sikap')
            ->select('siswa_id')
            ->where('guru_id', $userId)
            ->where('rombel_id', $rombel_id)
            ->where('skala', 'perlu-pembinaan')
            ->distinct()
            ->countAllResults();

        $dominanRow = $db->query("SELECT parameter_sikap, COUNT(*) as total FROM observasi_sikap WHERE guru_id = ? AND rombel_id = ? GROUP BY parameter_sikap ORDER BY total DESC LIMIT 1", [$userId, $rombel_id])->getRowArray();
        $dominan = $dominanRow ? str_replace('-', ' ', $dominanRow['parameter_sikap']) : '-';

        $data = [
            'user' => session()->get('nama_lengkap') ?? 'Guru Mapel',
            'navigations' => $this->getSidebarMenu(),
            'color' => $this->getColor(),
            'info' => [
                'kelas' => $penugasan['nama_rombel'] ?? 'Belum ada',
                'mapel' => $penugasan['nama_mapel'] ?? '-',
                'jml_siswa' => count($siswa),
                'rombel_id' => $rombel_id,
                'mapel_id' => $mapel_id
            ],
            'stats' => [
                'minggu_ini' => $minggu_ini,
                'pembinaan' => $pembinaan,
                'dominan' => ucwords($dominan)
            ],
            'siswas' => $siswa
        ];

        return view('GuruMapel/observasi-sikap', $data); 
    }

    public function getData()
    {
        if (!$this->request->isAJAX()) return $this->response->setStatusCode(404);

        $db = \Config\Database::connect();
        $rombel_id = $this->request->getGet('rombel_id');
        $mapel_id = $this->request->getGet('mapel_id');
        $guru_id = session()->get('id');

        $observasi = $db->table('observasi_sikap os')
            ->select('os.*, s.nama_lengkap')
            ->join('siswa s', 's.id = os.siswa_id')
            ->where('os.rombel_id', $rombel_id)
            ->where('os.mapel_id', $mapel_id)
            ->where('os.guru_id', $guru_id)
            ->orderBy('os.tanggal', 'DESC')
            ->orderBy('os.id', 'DESC')
            ->get()->getResultArray();

        return $this->response->setJSON(['status' => 'success', 'data' => $observasi]);
    }

    public function store()
    {
        if (!$this->request->isAJAX()) return $this->response->setStatusCode(404);

        $db = \Config\Database::connect();
        
        // Ambil array ID Siswa (Karena bisa Input Massal)
        $siswa_ids = json_decode($this->request->getPost('siswa_ids'), true);
        
        $dataInsert = [];
        foreach($siswa_ids as $sid) {
            $dataInsert[] = [
                'siswa_id' => $sid,
                'guru_id' => session()->get('id'),
                'mapel_id' => $this->request->getPost('mapel_id'),
                'rombel_id' => $this->request->getPost('rombel_id'),
                'parameter_sikap' => $this->request->getPost('parameter'),
                'skala' => $this->request->getPost('skala'),
                'catatan' => $this->request->getPost('catatan'),
                'tanggal' => date('Y-m-d') // Catat hari ini
            ];
        }

        $db->table('observasi_sikap')->insertBatch($dataInsert);
        return $this->response->setJSON(['status' => 'success']);
    }

    public function delete()
    {
        if (!$this->request->isAJAX()) return $this->response->setStatusCode(404);
        
        $db = \Config\Database::connect();
        $id = $this->request->getPost('id');
        $db->table('observasi_sikap')->delete(['id' => $id]);
        
        return $this->response->setJSON(['status' => 'success']);
    }
}