<?php
namespace App\Controllers\GuruMapel;

use App\Models\GuruMapel\RubrikProyekModel;
use App\Controllers\GuruMapelBaseController;
use App\Models\GuruMapel\PenilaianProyekModel;
use App\Models\GuruMapel\NilaiProyekModel;

class ProyekController extends GuruMapelBaseController
{
    public function index(): string
    {
        $db = \Config\Database::connect();
        $userId = session()->get('id');

        $rombel_id = $this->request->getGet('rombel');
        $mapel_id = $this->request->getGet('mapel');

        $builder = $db->table('guru_mapel gm')
            ->select('gm.mapel_id, gm.rombel_id, m.nama_mapel, r.nama_rombel as nama_kelas, r.tingkat')
            ->join('mata_pelajaran m', 'm.id = gm.mapel_id', 'left')
            ->join('rombel r', 'r.id = gm.rombel_id', 'left')
            ->where('gm.user_id', $userId);

        if ($rombel_id && $mapel_id) {
            $builder->where('gm.rombel_id', $rombel_id)->where('gm.mapel_id', $mapel_id);
        }
        
        $assignment = $builder->get()->getRowArray();

        $jumlah_siswa = 0;
        if ($assignment) {
            $jumlah_siswa = $db->table('siswa')->where('rombel_id', $assignment['rombel_id'])->countAllResults();
        }

        $proyekModel = new PenilaianProyekModel();
        $listProyek = [];
        
        if ($assignment) {
            $listProyek = $proyekModel->where([
                'guru_id'   => session()->get('id'),
                'mapel_id'  => $assignment['mapel_id'],
                'rombel_id' => $assignment['rombel_id']
            ])->orderBy('id', 'ASC')->findAll(); 
        }

        $data = [
            'user' => session()->get('username') ?? 'Guru Mapel',
            'navigations' => $this->getSidebarMenu(),
            'color' => $this->getColor(),
            'info' => [
                'mapel_id'   => $assignment['mapel_id'] ?? 0,
                'rombel_id'  => $assignment['rombel_id'] ?? 0,
                'mapel_nama' => $assignment['nama_mapel'] ?? 'Belum Pilih Mapel',
                'kelas_nama' => ($assignment['tingkat'] ?? '') . ' ' . ($assignment['nama_kelas'] ?? 'Belum Pilih Kelas'),
                'jml_siswa'  => $jumlah_siswa
            ],
            'list_proyek' => $listProyek 
        ];

        return view('GuruMapel/proyek', $data); 
    }

    public function getSiswaByRombel()
    {
        $rombel_id = $this->request->getGet('rombel_id');
        
        if (!$rombel_id) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Rombel ID tidak ditemukan']);
        }

        $db = \Config\Database::connect();
        
        $siswa = $db->table('siswa')
                    ->select('id, nis, nama_lengkap as nama')
                    ->where('rombel_id', $rombel_id)
                    ->orderBy('nama_lengkap', 'ASC')
                    ->get()
                    ->getResultArray();

        return $this->response->setJSON([
            'status' => 'success',
            'data'   => $siswa
        ]);
    }

    public function getRubrik($proyek_id)
    {
        try {
            $rubrikModel = new RubrikProyekModel();
            $dataRubrik = $rubrikModel->where('proyek_id', $proyek_id)->findAll();
            
            return $this->response->setJSON([
                'status' => 'success',
                'data'   => $dataRubrik
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'status' => 'error',
                'message'=> $e->getMessage()
            ]);
        }
    }

    public function simpanRubrik()
    {
        if (!$this->request->isAJAX()) return $this->response->setStatusCode(404);

        $json = $this->request->getJSON();
        
        $proyek_id = $json->proyek_id ?? null;
        $rubrik_items = $json->rubrik_items ?? []; 

        if (!$proyek_id || empty($rubrik_items)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Data tidak lengkap!']);
        }

        $rubrikModel = new RubrikProyekModel();

        try {
            $rubrikModel->where('proyek_id', $proyek_id)->delete();

            $dataInsert = [];
            foreach ($rubrik_items as $item) {
                $dataInsert[] = [
                    'proyek_id'  => $proyek_id,
                    'nama_aspek' => $item->nama_aspek,
                    'bobot'      => $item->bobot
                ];
            }
            
            if(!empty($dataInsert)){
                $rubrikModel->insertBatch($dataInsert);
            }

            return $this->response->setJSON(['status' => 'success', 'message' => 'Rubrik berhasil disimpan!']);
        } catch (\Exception $e) {
            return $this->response->setJSON(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    public function deleteRubrik($id)
    {
        $rubrikModel = new RubrikProyekModel();
        try {
            $cekData = $rubrikModel->find($id);
            if ($cekData) $rubrikModel->delete($id);
            return $this->response->setJSON(['status'  => 'success']);
        } catch (\Exception $e) {
            return $this->response->setJSON(['status'  => 'error']);
        }
    }

    public function simpanProyek()
    {
        if (!$this->request->isAJAX()) return $this->response->setStatusCode(404);

        $proyekModel = new PenilaianProyekModel();

        $nama = $this->request->getPost('nama');
        $tanggal = $this->request->getPost('tanggal');

        if (empty($nama) || empty($tanggal)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Nama dan Tanggal Pelaksanaan wajib diisi!']);
        }

        $dataSimpan = [
            'guru_id'             => session()->get('id'),
            'mapel_id'            => $this->request->getPost('mapel_id'),
            'rombel_id'           => $this->request->getPost('rombel_id'),
            'nama_proyek'         => $nama,
            'jenis'               => $this->request->getPost('jenis'),
            'tanggal_pelaksanaan' => $tanggal,
            'kkm'                 => $this->request->getPost('kkm'),
            'deskripsi'           => $this->request->getPost('deskripsi') ?: '-',
            'status'              => 'Draft'
        ];

        try {
            $proyekModel->insert($dataSimpan);
            $insertId = $proyekModel->getInsertID();

            return $this->response->setJSON([
                'status'  => 'success',
                'message' => 'Proyek berhasil dibuat!',
                'data'    => array_merge(['id' => $insertId], $dataSimpan)
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Gagal menyimpan ke Database: ' . $e->getMessage()
            ]);
        }
    }

    // --- MENGAMBIL NILAI SISWA BERDASARKAN PROYEK ---
    public function getNilaiProyek($proyek_id)
    {
        if (!$this->request->isAJAX()) return $this->response->setStatusCode(404);

        try {
            $model = new NilaiProyekModel();
            $nilai = $model->where('proyek_id', $proyek_id)->findAll();
            return $this->response->setJSON(['status' => 'success', 'data' => $nilai]);
        } catch (\Exception $e) {
            return $this->response->setJSON(['status' => 'success', 'data' => []]); // Kembalikan array kosong jika error agar JS tidak crash
        }
    }

    // --- MENYIMPAN/UPDATE NILAI SATU SISWA ---
    public function simpanNilaiSiswa()
    {
        if (!$this->request->isAJAX()) return $this->response->setStatusCode(404);

        try {
            $model = new NilaiProyekModel();
            
            $proyek_id = $this->request->getPost('proyek_id');
            $siswa_id = $this->request->getPost('siswa_id');
            
            $data = [
                'proyek_id'   => $proyek_id,
                'siswa_id'    => $siswa_id,
                'nilai_json'  => $this->request->getPost('nilai_json'),
                'nilai_akhir' => $this->request->getPost('nilai_akhir'),
                'catatan'     => $this->request->getPost('catatan') ?? ''
            ];

            // Cek apakah siswa ini sudah punya nilai di proyek ini (Upsert logic)
            $existing = $model->where(['proyek_id' => $proyek_id, 'siswa_id' => $siswa_id])->first();
            if ($existing) {
                $model->update($existing['id'], $data);
            } else {
                $model->insert($data);
            }
            return $this->response->setJSON(['status' => 'success', 'message' => 'Nilai tersimpan']);
        } catch (\Exception $e) {
            return $this->response->setJSON(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    // --- MENGHAPUS SISWA DARI KELOMPOK ---
    public function hapusNilaiSiswa()
    {
        if (!$this->request->isAJAX()) return $this->response->setStatusCode(404);

        try {
            $model = new NilaiProyekModel();
            $proyek_id = $this->request->getPost('proyek_id');
            $siswa_id = $this->request->getPost('siswa_id');

            $model->where(['proyek_id' => $proyek_id, 'siswa_id' => $siswa_id])->delete();
            return $this->response->setJSON(['status' => 'success']);
        } catch (\Exception $e) {
            return $this->response->setJSON(['status' => 'error']);
        }
    }   
}