<?php
namespace App\Controllers\GuruMapel;

use App\Controllers\GuruMapelBaseController;
use App\Models\GuruMapel\BankSoalModel;
use App\Models\GuruMapel\PaketSoalModel;

class BankSoalController extends GuruMapelBaseController
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

        // 2. Ambil penugasan Guru untuk mendapatkan Mapel dan Tingkat (Filter Tahun Aktif)
        $assignment = $db->table('guru_mapel gm')
            ->select('gm.mapel_id, m.nama_mapel, r.tingkat')
            ->join('mata_pelajaran m', 'm.id = gm.mapel_id', 'left')
            ->join('rombel r', 'r.id = gm.rombel_id', 'left')
            ->where(['gm.guru_id' => $guruId, 'r.id_tahun_ajaran' => $id_ta_aktif])
            ->get()->getRowArray();

        $data = [
            'user' => session()->get('username') ?? 'Guru Mapel',
            'navigations' => $this->getSidebarMenu(),
            'color' => $this->getColor(),
            'info' => [
                'mapel_id'   => $assignment['mapel_id'] ?? 0,
                'mapel_nama' => $assignment['nama_mapel'] ?? 'Belum Pilih Mapel',
                'tingkat'    => $assignment['tingkat'] ?? 'Belum Diset'
            ]
        ];
        return view('GuruMapel/bank-soal', $data); 
    }

    public function getData()
    {
        $mapel_id = $this->request->getGet('mapel_id');
        $tingkat = $this->request->getGet('tingkat');
        $guru_id = session()->get('id');

        $model = new BankSoalModel();
        // Ambil soal berdasarkan Guru, Mapel, dan Tingkat
        $soal = $model->where(['guru_id' => $guru_id, 'mapel_id' => $mapel_id, 'tingkat' => $tingkat])
                      ->orderBy('id', 'DESC')
                      ->findAll();

        return $this->response->setJSON(['status' => 'success', 'data' => $soal]);
    }

    public function store()
    {
        if (!$this->request->isAJAX()) return $this->response->setStatusCode(404);

        $model = new BankSoalModel();
        
        $data = [
            'guru_id'           => session()->get('id'),
            'mapel_id'          => $this->request->getPost('mapel_id'),
            'tingkat'           => $this->request->getPost('tingkat'),
            'jenis'             => $this->request->getPost('jenis'),
            'pertanyaan'        => $this->request->getPost('pertanyaan'),
            'opsi_a'            => $this->request->getPost('opsi_a'),
            'opsi_b'            => $this->request->getPost('opsi_b'),
            'opsi_c'            => $this->request->getPost('opsi_c'),
            'opsi_d'            => $this->request->getPost('opsi_d'),
            'kunci_jawaban'     => $this->request->getPost('kunci_jawaban'),
            'pembahasan'        => $this->request->getPost('pembahasan'),
            'tingkat_kesulitan' => $this->request->getPost('tingkat_kesulitan'),
            'kd'                => $this->request->getPost('kd'),
            'status'            => 'aktif'
        ];

        try {
            $model->insert($data);
            return $this->response->setJSON(['status' => 'success', 'message' => 'Soal berhasil disimpan!']);
        } catch (\Exception $e) {
            return $this->response->setJSON(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    public function delete($id)
    {
        if (!$this->request->isAJAX()) return $this->response->setStatusCode(404);
        
        $model = new BankSoalModel();
        try {
            $model->delete($id);
            return $this->response->setJSON(['status' => 'success', 'message' => 'Soal berhasil dihapus!']);
        } catch (\Exception $e) {
            return $this->response->setJSON(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    public function getPaket()
    {
        $mapel_id = $this->request->getGet('mapel_id');
        $tingkat = $this->request->getGet('tingkat');
        $guru_id = session()->get('id');

        $model = new PaketSoalModel();
        $paket = $model->where(['guru_id' => $guru_id, 'mapel_id' => $mapel_id, 'tingkat' => $tingkat])
                       ->orderBy('id', 'DESC')
                       ->findAll();

        return $this->response->setJSON(['status' => 'success', 'data' => $paket]);
    }

    public function storePaket()
    {
        if (!$this->request->isAJAX()) return $this->response->setStatusCode(404);

        $model = new PaketSoalModel();
        
        $data = [
            'guru_id'          => session()->get('id'),
            'mapel_id'         => $this->request->getPost('mapel_id'),
            'tingkat'          => $this->request->getPost('tingkat'),
            'nama_paket'       => $this->request->getPost('nama_paket'),
            'tanggal'          => $this->request->getPost('tanggal'),
            'kelas_target'     => $this->request->getPost('kelas_target'),
            'kumpulan_soal_id' => $this->request->getPost('kumpulan_soal_id'), // String ID dipisah koma (contoh: "1,4,5")
            'status'           => 'Aktif'
        ];

        try {
            $model->insert($data);
            return $this->response->setJSON(['status' => 'success', 'message' => 'Paket Soal berhasil dibuat!']);
        } catch (\Exception $e) {
            return $this->response->setJSON(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }
    // --- MENGUNDUH TEMPLATE CSV IMPORT SOAL ---
    public function downloadTemplate()
    {
        $header = [
            "Jenis (pg/isian/esai)", 
            "Kesulitan (mudah/sedang/sulit)", 
            "KD/CP", 
            "Pertanyaan", 
            "Opsi A (Khusus PG)", 
            "Opsi B (Khusus PG)", 
            "Opsi C (Khusus PG)", 
            "Opsi D (Khusus PG)", 
            "Kunci Jawaban", 
            "Pembahasan"
        ];
        
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="Template_Bank_Soal.csv"');
        
        $output = fopen('php://output', 'w');
        fputcsv($output, $header);
        // Contoh Data 1 (PG)
        fputcsv($output, ['pg', 'mudah', '3.1', 'Apa ibukota Indonesia?', 'A. Jakarta', 'B. Bandung', 'C. Surabaya', 'D. Medan', 'A. Jakarta', 'Jakarta adalah ibukota resmi Indonesia.']);
        // Contoh Data 2 (Esai)
        fputcsv($output, ['esai', 'sedang', '4.2', 'Jelaskan proses fotosintesis pada tumbuhan!', '', '', '', '', 'Proses pembuatan makanan pada daun...', 'Nilai berdasarkan kelengkapan materi']);
        fclose($output);
        exit();
    }

    // --- PROSES IMPORT FILE CSV KE DATABASE ---
    public function import()
    {
        if (!$this->request->isAJAX()) return $this->response->setStatusCode(404);

        $file = $this->request->getFile('file_import');
        if (!$file || !$file->isValid()) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'File tidak valid.']);
        }
        if ($file->getExtension() != 'csv' && $file->getClientMimeType() != 'text/csv') {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Harap upload file CSV sesuai template!']);
        }

        $mapel_id = $this->request->getPost('mapel_id');
        $tingkat  = $this->request->getPost('tingkat');
        $guru_id  = session()->get('id');

        $model = new BankSoalModel();
        
        try {
            if (($handle = fopen($file->getTempName(), "r")) !== FALSE) {
                $row = 0;
                $dataInsert = [];
                
                while (($data = fgetcsv($handle, 2000, ",")) !== FALSE) {
                    $row++;
                    if ($row == 1) continue; // Skip header
                    if (empty(trim($data[3]))) continue; // Skip jika pertanyaannya kosong

                    $dataInsert[] = [
                        'guru_id'           => $guru_id,
                        'mapel_id'          => $mapel_id,
                        'tingkat'           => $tingkat,
                        'jenis'             => strtolower(trim($data[0] ?? 'pg')),
                        'tingkat_kesulitan' => strtolower(trim($data[1] ?? 'mudah')),
                        'kd'                => trim($data[2] ?? ''),
                        'pertanyaan'        => trim($data[3] ?? ''),
                        'opsi_a'            => trim($data[4] ?? ''),
                        'opsi_b'            => trim($data[5] ?? ''),
                        'opsi_c'            => trim($data[6] ?? ''),
                        'opsi_d'            => trim($data[7] ?? ''),
                        'kunci_jawaban'     => trim($data[8] ?? ''),
                        'pembahasan'        => trim($data[9] ?? ''),
                        'status'            => 'aktif'
                    ];
                }
                fclose($handle);

                if (!empty($dataInsert)) {
                    $model->insertBatch($dataInsert);
                    return $this->response->setJSON(['status' => 'success', 'message' => count($dataInsert) . ' Soal berhasil diimport!']);
                } else {
                    return $this->response->setJSON(['status' => 'error', 'message' => 'Isi file kosong.']);
                }
            }
        } catch (\Exception $e) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Gagal mengimport: ' . $e->getMessage()]);
        }
    }
}