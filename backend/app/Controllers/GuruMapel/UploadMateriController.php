<?php
namespace App\Controllers\GuruMapel;

use App\Controllers\GuruMapelBaseController;

class UploadMateriController extends GuruMapelBaseController
{
    public function index(): string
    {
        $db = \Config\Database::connect();
        $userId = session()->get('id');

        // 1. Ambil Identitas Guru & Tahun Ajaran Aktif
        $dataGuru = $db->table('guru_tendik')->select('id')->where('user_id', $userId)->get()->getRowArray();
        $guruId   = $dataGuru ? $dataGuru['id'] : 0;

        $taAktif     = $db->table('tahun_ajaran')->where('status', 'Aktif')->get()->getRowArray();
        $id_ta_aktif = $taAktif ? $taAktif['id'] : 0;

        // 2. Ambil Penugasan Kelas & Mapel (Filter Tahun Aktif)
        $kelas_assigned = $db->table('guru_mapel gm')
            ->select('gm.rombel_id, gm.mapel_id, m.nama_mapel, r.nama_rombel')
            ->join('mata_pelajaran m', 'm.id = gm.mapel_id', 'left')
            ->join('rombel r', 'r.id = gm.rombel_id', 'left')
            ->where(['gm.guru_id' => $guruId, 'r.id_tahun_ajaran' => $id_ta_aktif])
            ->get()->getResultArray();

        $mapel_utama = count($kelas_assigned) > 0 ? $kelas_assigned[0]['nama_mapel'] : lang('GuruMapel/UploadMateri.not_set');
        $mapel_id_utama = count($kelas_assigned) > 0 ? $kelas_assigned[0]['mapel_id'] : 0;
        
        $list_kelas = [];
        $kelas_names = [];
        foreach($kelas_assigned as $k) {
            $list_kelas[] = [
                'id' => $k['rombel_id'],
                'nama' => lang('GuruMapel/UploadMateri.class_prefix') . ' ' . $k['nama_rombel']
            ];
            $kelas_names[] = $k['nama_rombel'];
        }

        // 2. Hitung Total Materi Guru Ini
        $total_materi = 0;
        if ($mapel_id_utama > 0) {
            $total_materi = $db->table('materi_pembelajaran')
                               ->where('guru_id', $userId)
                               ->where('mapel_id', $mapel_id_utama)
                               ->countAllResults();
        }

        // PERBAIKAN: Masukkan langsung ke dalam property $this->data 
        // agar tidak terjadi conflict variabel di View
        $this->data['title']       = lang('GuruMapel/UploadMateri.page_title') . ' - Rapor Digital';
        $this->data['user']        = session()->get('nama_lengkap') ?? session()->get('username') ?? 'Guru Mapel';
        $this->data['color']       = $this->getColor();
        $this->data['info']        = [
            'mapel'          => $mapel_utama,
            'mapel_id'       => $mapel_id_utama,
            'kelas_gabungan' => empty($kelas_names) ? '-' : implode(', ', $kelas_names),
            'total_materi'   => $total_materi
        ];
        $this->data['list_kelas']  = $list_kelas;

        return view('GuruMapel/upload-materi', $this->data); 
    }

    // API Simpan Materi & Upload File
    public function store()
    {
        if (!$this->request->isAJAX()) return $this->response->setStatusCode(404);

        $db = \Config\Database::connect();
        
        $judul = $this->request->getPost('judul');
        $jenis = $this->request->getPost('jenis');
        $deskripsi = $this->request->getPost('deskripsi');
        $rombel_ids = $this->request->getPost('rombel_ids'); // JSON string dari JS
        $tanggal = $this->request->getPost('tanggal');
        $status = $this->request->getPost('status');
        $mapel_id = $this->request->getPost('mapel_id');
        $guru_id = session()->get('id');

        $fileName = null;

        // Proses Upload File jika ada
        if ($file = $this->request->getFile('file_materi')) {
            if ($file->isValid() && !$file->hasMoved()) {
                $fileName = $file->getRandomName();
                $file->move(FCPATH . 'uploads/materi/', $fileName); 
            }
        }

        $dataInsert = [
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

        $db->table('materi_pembelajaran')->insert($dataInsert);

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

    // API Ambil Data Materi
    public function getData()
    {
        if (!$this->request->isAJAX()) return $this->response->setStatusCode(404);

        $db = \Config\Database::connect();
        $guru_id = session()->get('id');
        $mapel_id = $this->request->getGet('mapel_id');

        // Fitur Auto-Publish
        $today = date('Y-m-d');
        $db->table('materi_pembelajaran')
           ->where('guru_id', $guru_id)
           ->where('mapel_id', $mapel_id)
           ->where('status', 'draft')
           ->where('tanggal_publikasi <=', $today)
           ->update(['status' => 'published']);

        $materials = $db->table('materi_pembelajaran')
            ->where('guru_id', $guru_id)
            ->where('mapel_id', $mapel_id)
            ->orderBy('id', 'DESC')
            ->get()->getResultArray();

        $rombels = $db->table('rombel')->select('id, nama_rombel')->get()->getResultArray();
        $rombel_map = [];
        foreach($rombels as $r) {
            $rombel_map[$r['id']] = $r['nama_rombel'];
        }

        foreach($materials as &$m) {
            $rombel_ids = json_decode($m['rombel_ids'], true) ?? [];
            $kelas_names = [];
            foreach($rombel_ids as $r_id) {
                if(isset($rombel_map[$r_id])) {
                    $kelas_names[] = lang('GuruMapel/UploadMateri.class_prefix') . ' ' . $rombel_map[$r_id];
                }
            }
            $m['classes'] = $kelas_names;
        }

        return $this->response->setJSON(['status' => 'success', 'data' => $materials]);
    }

    // API Terbitkan Materi Manual
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