<?php

namespace App\Controllers\Admin;

use App\Controllers\AdminBaseController;
use App\Models\Admin\MataPelajaranModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;

class MataPelajaranController extends AdminBaseController
{
    protected $mapelModel;

    public function __construct()
    {
        $this->mapelModel = new MataPelajaranModel();
    }

    public function index(): string
    {
        // 1. Ambil Semua Data
        $rawMapel = $this->mapelModel->findAll();

        // 2. Hitung Statistik (DINAMIS)
        $stats = [
            'total' => count($rawMapel),
            // Umum: Gabungan Kelompok A dan B, Wajib, Umum
            'umum'  => $this->mapelModel->groupStart()
                            ->where('kelompok', 'A')
                            ->orWhere('kelompok', 'B')
                            ->orWhere('kelompok', 'Umum')
                        ->groupEnd()
                        ->countAllResults(),
            // Keislaman: Kelompok C
            'islam' => $this->mapelModel->groupStart()
                            ->where('kelompok', 'C')
                            ->orWhere('kelompok', 'Keislaman')
                        ->groupEnd()
                        ->countAllResults(),
            // Lokal: Mulok
            'lokal' => $this->mapelModel->groupStart()
                            ->where('kelompok', 'Mulok')
                            ->orWhere('kelompok', 'Lokal')
                        ->groupEnd()
                        ->countAllResults(),
        ];

        // 3. Format Data untuk Tabel JS
        $formattedMapel = array_map(function($row) {
            $namaKurikulum = 'Lainnya';
            if ($row['kurikulum_id'] == 1) { $namaKurikulum = 'Kurikulum 2013'; }
            elseif ($row['kurikulum_id'] == 2) { $namaKurikulum = 'Kurikulum Merdeka'; }
            
            return [
                'id'            => $row['id'],
                'code'          => $row['kode_mapel'],
                'name'          => $row['nama_mapel'],
                'group'         => $row['kelompok'],
                'groupColor'    => $this->getGroupColor($row['kelompok']),
                'curriculum'    => $namaKurikulum,
                'curriculum_id' => $row['kurikulum_id'],
                'hours'         => $row['jp_minggu'],
                'status'        => $row['status']
            ];
        }, $rawMapel);

        // 4. Kirim ke View
        $data = [
            'user'        => 'Admin',
            'navigations' => $this->getSidebarMenu(),
            'color'       => $this->getColor(),
            'mapelData'   => $formattedMapel,
            'stats'       => $stats 
        ];

        return view('admin/mata-pelajaran', $data);
    }

    public function store()
    {
        if (!$this->request->isAJAX()) return $this->response->setStatusCode(404);

        try {
            $data = [
                'kode_mapel'   => $this->request->getPost('mapel_code'),
                'nama_mapel'   => $this->request->getPost('mapel_name'),
                'kelompok'     => $this->request->getPost('group'),
                'jp_minggu'    => $this->request->getPost('hours'),
                'kurikulum_id' => $this->request->getPost('curriculum'),
                'status'       => 'Aktif'
            ];

            if ($this->mapelModel->insert($data)) {
                return $this->response->setJSON([
                    'status' => 'success', 
                    'message' => 'Mata pelajaran berhasil ditambahkan!'
                ]);
            } else {
                return $this->response->setJSON([
                    'status' => 'error', 
                    'message' => 'Gagal menyimpan ke database. Cek inputan Anda.'
                ]);
            }
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'status' => 'error', 
                'message' => 'System Error: ' . $e->getMessage()
            ]);
        }
    }   

    public function update($id)
    {
        if (!$this->request->isAJAX()) return $this->response->setStatusCode(404);

        try {
            $data = [
                'kode_mapel'   => $this->request->getPost('edit_mapel_code'),
                'nama_mapel'   => $this->request->getPost('edit_mapel_name'),
                'kelompok'     => $this->request->getPost('edit_group'),
                'jp_minggu'    => $this->request->getPost('edit_hours'),
                'kurikulum_id' => $this->request->getPost('edit_curriculum')
            ];

            if ($this->mapelModel->update($id, $data)) {
                return $this->response->setJSON([
                    'status' => 'success', 
                    'message' => 'Mata pelajaran berhasil diperbarui!'
                ]);
            } else {
                return $this->response->setJSON([
                    'status' => 'error', 
                    'message' => 'Gagal memperbarui database.'
                ]);
            }
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'status' => 'error', 
                'message' => 'System Error: ' . $e->getMessage()
            ]);
        }
    }

    public function delete($id)
    {
        if (!$this->request->isAJAX()) return $this->response->setStatusCode(404);

        try {
            if ($this->mapelModel->delete($id)) {
                return $this->response->setJSON(['status' => 'success', 'message' => 'Data mata pelajaran berhasil dihapus']);
            }
            return $this->response->setJSON(['status' => 'error', 'message' => 'Gagal menghapus data dari database']);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'status' => 'error', 
                'message' => 'Tidak dapat dihapus karena Mapel ini sedang digunakan/diampu oleh Guru.'
            ]);
        }
    }
    
    // ==============================================================
    // IMPORT & EXPORT LOGIC
    // ==============================================================
    public function downloadTemplate()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Template Mapel');
        
        $headers = [
            'Kode Mapel (Wajib)', 
            'Nama Mata Pelajaran (Wajib)', 
            'Kelompok (Umum / Keislaman / Lokal)', 
            'ID Kurikulum (1=K13, 2=Merdeka)', 
            'Jam Per Minggu (Angka)'
        ];

        $col = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($col . '1', $header);
            $sheet->getStyle($col . '1')->getFont()->setBold(true);
            $sheet->getStyle($col . '1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FFEFEFEF');
            $sheet->getColumnDimension($col)->setAutoSize(true);
            $col++;
        }

        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Template_Import_Mapel.xlsx"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
        exit;
    }

    public function import()
    {
        if (empty($_FILES)) return $this->response->setJSON(['status' => 'error', 'message' => 'File tidak ditemukan.']);
        
        $file = $this->request->getFile('file_excel');
        if (!$file || !$file->isValid()) return $this->response->setJSON(['status' => 'error', 'message' => 'File corrupt.']);
        
        $ext = strtolower($file->getClientExtension());
        if (!in_array($ext, ['xls', 'xlsx'])) return $this->response->setJSON(['status' => 'error', 'message' => 'Harus format Excel (.xlsx)']);

        $db = \Config\Database::connect();
        $db->transBegin();

        try {
            $spreadsheet = IOFactory::load($file->getTempName());
            $sheet = $spreadsheet->getSheet(0)->toArray(null, true, true, true); 
            
            $countInsert = 0;

            foreach ($sheet as $idx => $row) {
                if ($idx == 1) continue; // Skip header
                
                $kode = trim($row['A'] ?? '');
                $nama = trim($row['B'] ?? '');
                
                if (empty($kode) || empty($nama)) continue;

                $data = [
                    'kode_mapel'   => $kode,
                    'nama_mapel'   => $nama,
                    'kelompok'     => trim($row['C'] ?? 'Umum'),
                    'kurikulum_id' => trim($row['D'] ?? '2'),
                    'jp_minggu'    => trim($row['E'] ?? '2'),
                    'status'       => 'Aktif'
                ];

                $this->mapelModel->insert($data);
                $countInsert++;
            }

            if ($db->transStatus() === false) {
                $db->transRollback();
                return $this->response->setJSON(['status' => 'error', 'message' => 'Gagal menyimpan ke database.']);
            }

            $db->transCommit();
            return $this->response->setJSON(['status' => 'success', 'message' => "$countInsert Mata Pelajaran berhasil diimport!"]);

        } catch (\Throwable $e) {
            $db->transRollback();
            return $this->response->setJSON(['status' => 'error', 'message' => 'System Error: ' . $e->getMessage()]);
        }
    }

    private function getGroupColor($group) {
        $g = strtolower($group);
        if (in_array($g, ['umum', 'a', 'b', 'wajib', 'peminatan'])) return 'emerald'; 
        if (in_array($g, ['keislaman', 'c', 'agama'])) return 'purple';  
        if (in_array($g, ['lokal', 'mulok', 'muatan lokal'])) return 'amber'; 
        return 'gray';
    }
}