<?php

namespace App\Controllers\Admin;

use App\Controllers\AdminBaseController;
use App\Models\Admin\KurikulumModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;

class KurikulumController extends AdminBaseController
{
    protected $kurikulumModel;

    public function __construct()
    {
        $this->kurikulumModel = new KurikulumModel();
    }

    public function index(): string
    {
        // Ambil semua data kurikulum dari database
        $daftarKurikulum = $this->kurikulumModel->findAll();

        // Ambil data kurikulum yang statusnya AKTIF
        $activeKurikulum = $this->kurikulumModel->where('status', 'Aktif')->first();

        $data = [
            'user'            => 'Admin',
            'navigations'     => $this->getSidebarMenu(),
            'color'           => $this->getColor(),
            'kurikulum'       => $daftarKurikulum,
            'activeKurikulum' => $activeKurikulum
        ];

        return view('admin/kurikulum', $data);
    }

    public function store()
    {
        if (!$this->request->isAJAX()) return $this->response->setJSON(['status' => 'error', 'message' => 'Invalid request']);

        $nama_kurikulum = $this->request->getPost('curriculum_name');
        $jenis          = $this->request->getPost('curriculum_type');
        $tahun          = $this->request->getPost('year_start');

        if (empty($nama_kurikulum) || empty($jenis) || empty($tahun)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Semua kolom wajib (Nama, Jenis, Tahun) harus diisi!']);
        }

        $data = [
            'nama_kurikulum' => $nama_kurikulum,
            'jenis'          => $jenis,
            'tahun_berlaku'  => $tahun,
            'status'         => 'Non-aktif'
        ];

        if ($this->kurikulumModel->insert($data)) {
            return $this->response->setJSON(['status' => 'success', 'message' => 'Kurikulum baru berhasil ditambahkan!']);
        } else {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Gagal menyimpan ke database.']);
        }
    }

    /**
     * MEMPERBARUI DATA KURIKULUM (EDIT)
     */
    public function update()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Invalid request']);
        }

        $id             = $this->request->getPost('edit_curriculum_id');
        $nama_kurikulum = $this->request->getPost('edit_curriculum_name');
        $jenis          = $this->request->getPost('edit_curriculum_type');
        $tahun          = $this->request->getPost('edit_year_start');

        // Validasi form kosong
        if (empty($id) || empty($nama_kurikulum) || empty($jenis) || empty($tahun)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Semua kolom wajib (Nama, Jenis, Tahun) harus diisi!']);
        }

        $data = [
            'nama_kurikulum' => $nama_kurikulum,
            'jenis'          => $jenis,
            'tahun_berlaku'  => $tahun
        ];

        // Eksekusi update ke database
        if ($this->kurikulumModel->update($id, $data)) {
            return $this->response->setJSON(['status' => 'success', 'message' => 'Kurikulum berhasil diperbarui!']);
        } else {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Gagal memperbarui data di database.']);
        }
    }

    public function delete($id)
    {
        if (!$this->request->isAJAX()) return $this->response->setJSON(['status' => 'error', 'message' => 'Invalid request']);

        $data = $this->kurikulumModel->find($id);
        if ($data) {
            if ($data['status'] == 'Aktif') {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Kurikulum yang sedang AKTIF tidak dapat dihapus!']);
            }
            $this->kurikulumModel->delete($id);
            return $this->response->setJSON(['status' => 'success', 'message' => 'Kurikulum berhasil dihapus permanen.']);
        }
        return $this->response->setJSON(['status' => 'error', 'message' => 'Data tidak ditemukan.']);
    }

    // FUNGSI BARU: AKTIFKAN KURIKULUM
    public function activate($id)
    {
        if (!$this->request->isAJAX()) return $this->response->setJSON(['status' => 'error', 'message' => 'Invalid request']);

        $data = $this->kurikulumModel->find($id);
        if ($data) {
            $this->kurikulumModel->update($id, ['status' => 'Aktif']);
            return $this->response->setJSON(['status' => 'success', 'message' => 'Kurikulum berhasil diaktifkan!']);
        }
        return $this->response->setJSON(['status' => 'error', 'message' => 'Data tidak ditemukan.']);
    }

    // FUNGSI BARU: NONAKTIFKAN KURIKULUM
    public function deactivate($id)
    {
        if (!$this->request->isAJAX()) return $this->response->setJSON(['status' => 'error', 'message' => 'Invalid request']);

        $data = $this->kurikulumModel->find($id);
        if ($data) {
            $this->kurikulumModel->update($id, ['status' => 'Non-aktif']);
            return $this->response->setJSON(['status' => 'success', 'message' => 'Kurikulum berhasil dinonaktifkan!']);
        }
        return $this->response->setJSON(['status' => 'error', 'message' => 'Data tidak ditemukan.']);
    }

    public function downloadTemplate()
    {
        if (ob_get_length()) ob_clean();
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Template Kurikulum');

        $headers = ['Nama Kurikulum (Wajib)', 'Jenis (Merdeka / K13 / Lainnya)', 'Tahun Berlaku (Contoh: 2024)', 'Status (Aktif / Non-aktif)'];
        $col = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($col . '1', $header);
            $sheet->getStyle($col . '1')->getFont()->setBold(true);
            $sheet->getStyle($col . '1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FFEFEFEF');
            $sheet->getColumnDimension($col)->setAutoSize(true);
            $col++;
        }

        $sheet->setCellValue('A2', 'Kurikulum Prototipe');
        $sheet->setCellValue('B2', 'Merdeka');
        $sheet->setCellValue('C2', '2025');
        $sheet->setCellValue('D2', 'Non-aktif');

        $writer = new Xlsx($spreadsheet);
        $filename = 'Template_Import_Kurikulum_' . date('Y-m-d') . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit;
    }

    public function import()
    {
        ini_set('memory_limit', '1024M');
        ini_set('max_execution_time', '300');
        if (ob_get_length()) ob_clean();

        if (empty($_FILES)) return $this->response->setJSON(['status' => 'error', 'message' => 'File tidak ditemukan oleh server.']);

        $file = $this->request->getFile('file_excel');
        if (!$file || !$file->isValid()) return $this->response->setJSON(['status' => 'error', 'message' => 'File corrupt atau kosong.']);

        $ext = strtolower($file->getClientExtension());
        if (!in_array($ext, ['xls', 'xlsx'])) return $this->response->setJSON(['status' => 'error', 'message' => 'Format file wajib .xls atau .xlsx']);

        $db = \Config\Database::connect();
        $db->transBegin();

        try {
            $spreadsheet = IOFactory::load($file->getTempName());
            $sheet = $spreadsheet->getSheet(0)->toArray(null, true, true, true);
            $countInsert = 0;

            foreach ($sheet as $idx => $row) {
                if ($idx == 1) continue;
                $nama_kurikulum = trim($row['A'] ?? '');
                if (empty($nama_kurikulum)) continue;

                $jenis = trim($row['B'] ?? 'Lainnya');
                if (!in_array($jenis, ['Merdeka', 'K13', 'Lainnya'])) $jenis = 'Lainnya';

                $tahun = trim($row['C'] ?? date('Y'));
                if (!is_numeric($tahun)) $tahun = date('Y');

                $status = trim($row['D'] ?? 'Non-aktif');
                if (!in_array($status, ['Aktif', 'Non-aktif'])) $status = 'Non-aktif';

                $this->kurikulumModel->insert([
                    'nama_kurikulum' => $nama_kurikulum,
                    'jenis'          => $jenis,
                    'tahun_berlaku'  => $tahun,
                    'status'         => $status
                ]);
                $countInsert++;
            }

            if ($db->transStatus() === false) {
                $db->transRollback();
                return $this->response->setJSON(['status' => 'error', 'message' => 'Gagal memproses data ke database.']);
            }

            $db->transCommit();
            return $this->response->setJSON(['status' => 'success', 'message' => "Import Berhasil! $countInsert Kurikulum baru ditambahkan."]);
        } catch (\Throwable $e) {
            if (isset($db)) $db->transRollback();
            return $this->response->setJSON(['status' => 'error', 'message' => 'System Error: ' . $e->getMessage()]);
        }
    }
}
