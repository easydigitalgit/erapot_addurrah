<?php

namespace App\Controllers\Admin;

use App\Controllers\AdminBaseController;
use App\Models\Admin\MasterEkskulModel;

class MasterEkskulController extends AdminBaseController
{
    protected $ekskulModel;

    public function __construct()
    {
        $this->ekskulModel = new MasterEkskulModel();
    }

    public function index()
    {
        $data = [
            'title'       => 'Master Ekstrakurikuler',
            'user'        => session()->get('nama_lengkap') ?? 'Admin',
            'navigations' => $this->getSidebarMenu(),
            'color'       => $this->getColor()
        ];

        return view('admin/master-ekskul/index', $data);
    }

    public function getData()
    {
        $db = \Config\Database::connect();
        
        try {
            // ========================================================================
            // FITUR AUTO-FIX DATABASE
            // ========================================================================
            if ($db->tableExists('nilai_ekskul')) {
                $fields = $db->getFieldNames('nilai_ekskul');
                if (!in_array('ekskul_id', $fields)) $db->query("ALTER TABLE `nilai_ekskul` ADD `ekskul_id` INT(11) NOT NULL AFTER `semester`");
                if (!in_array('deskripsi', $fields)) $db->query("ALTER TABLE `nilai_ekskul` ADD `deskripsi` TEXT NULL");
                if (!in_array('predikat', $fields)) $db->query("ALTER TABLE `nilai_ekskul` ADD `predikat` VARCHAR(10) NULL");
            } else {
                $db->query("CREATE TABLE `nilai_ekskul` (
                  `id` int(11) NOT NULL AUTO_INCREMENT,
                  `siswa_id` int(11) NOT NULL,
                  `rombel_id` int(11) NOT NULL,
                  `tahun_ajaran` varchar(20) NOT NULL,
                  `semester` varchar(20) NOT NULL,
                  `ekskul_id` int(11) NOT NULL,
                  `predikat` varchar(5) NOT NULL,
                  `deskripsi` text NOT NULL,
                  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
                  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                  PRIMARY KEY (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
            }

            // 1. Ambil semua data Master Ekskul
            $ekskulList = $this->ekskulModel->orderBy('nama_ekskul', 'ASC')->findAll();

            // Variabel penampung statistik Card
            $total_ekskul = count($ekskulList);
            $ekskul_aktif = 0;
            $ekskul_nonaktif = 0;

            // Cek apakah tabel siswa sudah punya kolom ekskul (mencegah error jika belum terupdate)
            $tabelSiswaFields = $db->getFieldNames('siswa');
            $kolomEkskulAda = in_array('ekskul_1', $tabelSiswaFields);

            // 2. Hitung jumlah siswa per ekskul dengan CERDAS (Dari Profil Siswa)
            foreach ($ekskulList as &$ekskul) {
                // Hitung Statistik untuk Card
                if ($ekskul['status'] === 'Aktif') {
                    $ekskul_aktif++;
                } else {
                    $ekskul_nonaktif++;
                }

                if ($kolomEkskulAda) {
                    // Logika Baru: Hitung siswa yang statusnya Aktif dan memilih ekskul ini di pilihan 1, 2, atau 3
                    $queryTotal = $db->table('siswa')
                                     ->select('COUNT(id) as total')
                                     ->where('status_siswa', 'Aktif')
                                     ->groupStart()
                                         ->where('ekskul_1', $ekskul['id'])
                                         ->orWhere('ekskul_2', $ekskul['id'])
                                         ->orWhere('ekskul_3', $ekskul['id'])
                                     ->groupEnd()
                                     ->get()
                                     ->getRowArray();
                    
                    $ekskul['total_siswa'] = $queryTotal ? (int)$queryTotal['total'] : 0;
                } else {
                    $ekskul['total_siswa'] = 0;
                }
            }

            // Kembalikan Data beserta Statistiknya untuk Card dan Grafik
            return $this->response->setJSON([
                'status' => 'success', 
                'data' => $ekskulList,
                'stats' => [
                    'total' => $total_ekskul,
                    'aktif' => $ekskul_aktif,
                    'nonaktif' => $ekskul_nonaktif
                ]
            ]);

        } catch (\Exception $e) {
            return $this->response->setJSON([
                'status' => 'error', 
                'message' => 'Error Server: ' . $e->getMessage()
            ]);
        }
    }

    public function store()
    {
        if ($this->request->isAJAX()) {
            $nama = trim($this->request->getPost('nama_ekskul'));
            
            if ($this->ekskulModel->where('nama_ekskul', $nama)->first()) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Nama Ekskul sudah ada!']);
            }

            $data = [
                'nama_ekskul' => $nama,
                'status'      => $this->request->getPost('status') ?: 'Aktif'
            ];

            if ($this->ekskulModel->insert($data)) {
                return $this->response->setJSON(['status' => 'success', 'message' => 'Ekstrakurikuler berhasil ditambahkan.']);
            }
            return $this->response->setJSON(['status' => 'error', 'message' => 'Gagal menyimpan data.']);
        }
    }

    public function update()
    {
        if ($this->request->isAJAX()) {
            $id = $this->request->getPost('id');
            $data = [
                'nama_ekskul' => trim($this->request->getPost('nama_ekskul')),
                'status'      => $this->request->getPost('status')
            ];

            if ($this->ekskulModel->update($id, $data)) {
                return $this->response->setJSON(['status' => 'success', 'message' => 'Ekstrakurikuler berhasil diperbarui.']);
            }
            return $this->response->setJSON(['status' => 'error', 'message' => 'Gagal memperbarui data.']);
        }
    }

    public function delete()
    {
        if ($this->request->isAJAX()) {
            $id = $this->request->getPost('id');
            if ($this->ekskulModel->delete($id)) {
                return $this->response->setJSON(['status' => 'success', 'message' => 'Data berhasil dihapus.']);
            }
            return $this->response->setJSON(['status' => 'error', 'message' => 'Gagal menghapus data.']);
        }
    }
}