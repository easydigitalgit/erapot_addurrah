<?php

namespace App\Controllers\GuruMapel;

use App\Controllers\GuruMapelBaseController;
use App\Models\GuruMapel\NilaiSumatifModel;

class NilaiSumatifController extends GuruMapelBaseController
{
    protected $nilaiSumatifModel;
    protected $db;

    public function __construct()
    {
        $this->nilaiSumatifModel = new NilaiSumatifModel();
        $this->db = \Config\Database::connect();
    }

    public function index(): string
    {
        $userId = session()->get('id');

        // 1. CARI IDENTITAS GURU (GURU_ID)
        $dataGuru = $this->db->table('guru_tendik')->select('id')->where('user_id', $userId)->get()->getRowArray();
        $guruId = $dataGuru ? $dataGuru['id'] : 0;

        // 2. Ambil Semua Penugasan (Untuk Dropdown Switcher)
        $builder = $this->db->table('guru_mapel gm');
        $builder->select('gm.mapel_id, gm.rombel_id, m.nama_mapel, r.nama_rombel as kelas_nama, r.tingkat');
        $builder->join('mata_pelajaran m', 'm.id = gm.mapel_id', 'left');
        $builder->join('rombel r', 'r.id = gm.rombel_id', 'left');
        $builder->where('gm.guru_id', $guruId);
        $allPenugasan = $builder->get()->getResultArray();

        // 3. Tentukan Kelas & Mapel Aktif dari URL Parameter GET
        $activeRombelId = $this->request->getGet('rombel') ?? ($allPenugasan[0]['rombel_id'] ?? 0);
        $activeMapelId  = $this->request->getGet('mapel')  ?? ($allPenugasan[0]['mapel_id'] ?? 0);

        // Cari detail spesifik dari penugasan yang aktif
        $assignment = array_filter($allPenugasan, function($p) use ($activeRombelId, $activeMapelId) {
            return $p['rombel_id'] == $activeRombelId && $p['mapel_id'] == $activeMapelId;
        });
        $assignment = reset($assignment);

        // 4. Hitung jumlah siswa di kelas tersebut
        $jumlah_siswa = 0;
        if ($activeRombelId > 0) {
            $jumlah_siswa = $this->db->table('siswa')
                                     ->where('rombel_id', $activeRombelId)
                                     ->where('status_siswa', 'Aktif')
                                     ->countAllResults();
        }

        $data = [
            'user'        => session()->get('nama_lengkap') ?? 'Guru Mapel',
            'navigations' => $this->getSidebarMenu(),
            'color'       => $this->getColor(),
            'allRombel'   => $allPenugasan, // Data untuk dropdown pindah kelas
            'info'        => [
                'mapel_id'   => $activeMapelId,
                'rombel_id'  => $activeRombelId,
                'mapel_nama' => $assignment['nama_mapel'] ?? 'Belum Pilih Mapel',
                'kelas_nama' => ($assignment['tingkat'] ?? '') . ' ' . ($assignment['kelas_nama'] ?? 'Belum Pilih Kelas'),
                'jml_siswa'  => $jumlah_siswa
            ]
        ];

        return view('GuruMapel/nilai-sumatif', $data); 
    }

    public function getNilaiSiswa()
    {
        if (!$this->request->isAJAX()) return $this->response->setStatusCode(404);

        $jenis_sumatif = $this->request->getGet('jenis');
        $mapel_id      = $this->request->getGet('mapel_id');
        $rombel_id     = $this->request->getGet('rombel_id');

        if (!$rombel_id || !$mapel_id) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Kelas atau Mapel tidak valid']);
        }

        $builder = $this->db->table('siswa s');
        $builder->select('s.id as siswa_id, s.nama_lengkap as nama, s.nis, ns.id as nilai_id, ns.nilai, ns.deskripsi, ns.status');
        $builder->join('nilai_sumatif ns', "ns.siswa_id = s.id AND ns.mapel_id = {$mapel_id} AND ns.jenis_sumatif = '{$jenis_sumatif}'", 'left');
        $builder->where('s.rombel_id', $rombel_id);
        $builder->where('s.status_siswa', 'Aktif');
        $builder->orderBy('s.nama_lengkap', 'ASC');
        
        $dataSiswa = $builder->get()->getResultArray();

        return $this->response->setJSON([
            'status' => 'success', 
            'data'   => $dataSiswa
        ]);
    }

    public function saveBulk()
    {
        if (!$this->request->isAJAX()) return $this->response->setStatusCode(404);

        $json = $this->request->getJSON();
        if (!$json || empty($json->data_nilai)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Data nilai kosong!']);
        }

        $jenis_sumatif = $json->jenis_sumatif;
        $mapel_id      = $json->mapel_id;
        $rombel_id     = $json->rombel_id;
        $dataNilai     = $json->data_nilai; 

        $this->db->transStart(); 

        foreach ($dataNilai as $item) {
            $data = [
                'siswa_id'      => $item->siswa_id,
                'mapel_id'      => $mapel_id,
                'rombel_id'     => $rombel_id, // Menambahkan record rombel agar relasi sempurna
                'jenis_sumatif' => $jenis_sumatif,
                'nilai'         => $item->nilai,
                'deskripsi'     => $item->deskripsi,
                'status'        => 'draft'
            ];

            if (!empty($item->nilai_id)) {
                $this->nilaiSumatifModel->update($item->nilai_id, $data);
            } else {
                $this->nilaiSumatifModel->insert($data);
            }
        }

        $this->db->transComplete(); 

        if ($this->db->transStatus() === FALSE) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Gagal menyimpan data.']);
        }

        return $this->response->setJSON(['status' => 'success', 'message' => 'Draft nilai berhasil disimpan.']);
    }

    public function updateStatus()
    {
        if (!$this->request->isAJAX()) return $this->response->setStatusCode(404);

        $json = $this->request->getJSON();
        
        if (!$json || !isset($json->jenis_sumatif) || !isset($json->status) || !isset($json->mapel_id)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Data tidak lengkap.']);
        }

        $jenis_sumatif = $json->jenis_sumatif;
        $status        = $json->status;
        $mapel_id      = $json->mapel_id;
        $rombel_id     = $json->rombel_id;

        try {
            $this->db->table('nilai_sumatif')
                     ->where('mapel_id', $mapel_id)
                     ->where('rombel_id', $rombel_id) // Mengunci status sesuai rombel yang dipilih
                     ->where('jenis_sumatif', $jenis_sumatif)
                     ->update(['status' => $status]);

            return $this->response->setJSON([
                'status'  => 'success', 
                'message' => 'Status berhasil diupdate menjadi ' . $status
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'status'  => 'error', 
                'message' => 'Terjadi kesalahan database: ' . $e->getMessage()
            ]);
        }
    }
}