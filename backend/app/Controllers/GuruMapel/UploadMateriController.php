<?php
namespace App\Controllers\GuruMapel;

use App\Controllers\GuruMapelBaseController;

class UploadMateriController extends GuruMapelBaseController
{
    public function index(): string
    {
        $db = \Config\Database::connect();
        $userId = session()->get('id');

        // 1. Ambil Penugasan Kelas & Mapel (Mengambil semua kelas yang diajar)
        $kelas_assigned = $db->table('guru_mapel gm')
            ->select('gm.rombel_id, gm.mapel_id, m.nama_mapel, r.nama_rombel')
            ->join('mata_pelajaran m', 'm.id = gm.mapel_id', 'left')
            ->join('rombel r', 'r.id = gm.rombel_id', 'left')
            ->where('gm.user_id', $userId)
            ->get()->getResultArray();

        $mapel_utama = count($kelas_assigned) > 0 ? $kelas_assigned[0]['nama_mapel'] : 'Belum Diset';
        $mapel_id_utama = count($kelas_assigned) > 0 ? $kelas_assigned[0]['mapel_id'] : 0;
        
        $list_kelas = [];
        $kelas_names = [];
        foreach($kelas_assigned as $k) {
            $list_kelas[] = [
                'id' => $k['rombel_id'],
                'nama' => 'Kelas ' . $k['nama_rombel']
            ];
            $kelas_names[] = $k['nama_rombel'];
        }

        // 2. Hitung Total Materi Guru Ini
        $total_materi = $db->table('materi_pembelajaran')
                           ->where('guru_id', $userId)
                           ->where('mapel_id', $mapel_id_utama)
                           ->countAllResults();

        $data = [
            'user' => session()->get('nama_lengkap') ?? session()->get('username') ?? 'Guru Mapel',
            'navigations' => $this->getSidebarMenu(),
            'color' => $this->getColor(),
            'info' => [
                'mapel' => $mapel_utama,
                'mapel_id' => $mapel_id_utama,
                'kelas_gabungan' => implode(', ', $kelas_names),
                'total_materi' => $total_materi
            ],
            'list_kelas' => $list_kelas
        ];

        return view('GuruMapel/upload-materi', $data); 
    }

    // API Simpan Materi & Upload File
    public function store()
    {
        if (!$this->request->isAJAX()) return $this->response->setStatusCode(404);

        $db = \Config\Database::connect();
        
        $judul = $this->request->getPost('judul');
        $jenis = $this->request->getPost('jenis');
        $deskripsi = $this->request->getPost('deskripsi');
        $rombel_ids = $this->request->getPost('rombel_ids'); // Bentuknya JSON string dari JS
        $tanggal = $this->request->getPost('tanggal');
        $status = $this->request->getPost('status');
        $mapel_id = $this->request->getPost('mapel_id');
        $guru_id = session()->get('id');

        $fileName = null;

        // Proses Upload File jika ada
        if ($file = $this->request->getFile('file_materi')) {
            if ($file->isValid() && !$file->hasMoved()) {
                $fileName = $file->getRandomName();
                $file->move(FCPATH . 'uploads/materi/', $fileName); // File disimpan di public/uploads/materi/
            }
        }

        $data = [
            'guru_id' => $guru_id,
            'mapel_id' => $mapel_id,
            'judul' => $judul,
            'jenis' => $jenis,
            'deskripsi' => $deskripsi,
            'rombel_ids' => $rombel_ids,
            'file_path' => $fileName,
            'tanggal_publikasi' => $tanggal,
            'status' => $status
        ];

        $db->table('materi_pembelajaran')->insert($data);

        return $this->response->setJSON(['status' => 'success']);
    }

    // API Hapus Materi
    public function delete()
    {
        if (!$this->request->isAJAX()) return $this->response->setStatusCode(404);
        
        $db = \Config\Database::connect();
        $id = $this->request->getPost('id');

        // Hapus file fisik jika ada
        $materi = $db->table('materi_pembelajaran')->where('id', $id)->get()->getRowArray();
        if($materi && $materi['file_path']) {
            $path = FCPATH . 'uploads/materi/' . $materi['file_path'];
            if(file_exists($path)) {
                unlink($path);
            }
        }

        $db->table('materi_pembelajaran')->delete(['id' => $id]);
        
        return $this->response->setJSON(['status' => 'success']);
    }

    public function getData()
    {
        if (!$this->request->isAJAX()) return $this->response->setStatusCode(404);

        $db = \Config\Database::connect();
        $guru_id = session()->get('id');
        $mapel_id = $this->request->getGet('mapel_id');

        // =======================================================
        // FITUR AUTO-PUBLISH BERDASARKAN TANGGAL
        // Mengecek materi 'draft' yang tanggal publikasinya <= hari ini
        // =======================================================
        $today = date('Y-m-d');
        $db->table('materi_pembelajaran')
           ->where('guru_id', $guru_id)
           ->where('mapel_id', $mapel_id)
           ->where('status', 'draft')
           ->where('tanggal_publikasi <=', $today)
           ->update(['status' => 'published']); // Ubah otomatis jadi terbit

        // Setelah auto-publish dieksekusi, baru ambil datanya untuk ditampilkan
        $materials = $db->table('materi_pembelajaran')
            ->where('guru_id', $guru_id)
            ->where('mapel_id', $mapel_id)
            ->orderBy('id', 'DESC')
            ->get()->getResultArray();

        // Ambil nama rombel untuk mengubah ID menjadi Nama Kelas
        $rombels = $db->table('rombel')->select('id, nama_rombel')->get()->getResultArray();
        $rombel_map = [];
        foreach($rombels as $r) {
            $rombel_map[$r['id']] = $r['nama_rombel'];
        }

        // Format data untuk JS
        foreach($materials as &$m) {
            $rombel_ids = json_decode($m['rombel_ids'], true) ?? [];
            $kelas_names = [];
            foreach($rombel_ids as $r_id) {
                if(isset($rombel_map[$r_id])) {
                    $kelas_names[] = $rombel_map[$r_id];
                }
            }
            $m['classes'] = $kelas_names;
        }

        return $this->response->setJSON(['status' => 'success', 'data' => $materials]);
    }

    // --- FUNGSI BARU UNTUK TERBITKAN MATERI SECARA MANUAL VIA TOMBOL ---
    public function updateStatus()
    {
        if (!$this->request->isAJAX()) return $this->response->setStatusCode(404);
        
        $db = \Config\Database::connect();
        $id = $this->request->getPost('id');
        $status = $this->request->getPost('status'); // 'published'

        try {
            $db->table('materi_pembelajaran')
               ->where('id', $id)
               ->update(['status' => $status]);
               
            return $this->response->setJSON(['status' => 'success']);
        } catch (\Exception $e) {
            return $this->response->setJSON(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }
}