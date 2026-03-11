<?php

namespace App\Controllers\Admin;

use App\Models\Admin\GuruTendikModel;
use App\Controllers\AdminBaseController;
use App\Models\Admin\SiswaModel;
use App\Models\Admin\RombelModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;

class TingkatRombelController extends AdminBaseController
{
    public function index(): string
    {
        $siswaModel = new SiswaModel();
        $rombelModel = new RombelModel();
        $guruModel = new GuruTendikModel();
        $db = \Config\Database::connect();

        $listGuru = $guruModel->select('id, nama_lengkap')->findAll();

        // 1. AMBIL TAHUN AJARAN AKTIF SECARA DINAMIS
        $taAktif = $db->table('tahun_ajaran')->where('status', 'Aktif')->get()->getRowArray();
        $idTaAktif = $taAktif ? $taAktif['id'] : null;
        $strTaAktif = $taAktif ? $taAktif['tahun'] : 'Belum Diset';

        $totalSiswa = $siswaModel->where('status_siswa', 'Aktif')->countAllResults(); 
        $totalRombel = $rombelModel->countAll();

        // 2. Data Tabel Rombel
        $listRombel = $rombelModel
            ->select('rombel.*, guru_tendik.nama_lengkap as nama_wali_kelas')
            ->select('(SELECT COUNT(*) FROM siswa WHERE siswa.rombel_id = rombel.id AND siswa.status_siswa = "Aktif") as jumlah_siswa')
            ->join('guru_tendik', 'guru_tendik.id = rombel.wali_kelas_id', 'left')
            ->orderBy('rombel.tingkat', 'ASC')
            ->orderBy('rombel.nama_rombel', 'ASC')
            ->findAll();

        // INJEKSI TAHUN AJARAN AKTIF KE DALAM ARRAY (AGAR SELALU DINAMIS)
        foreach ($listRombel as &$r) {
            $r['nama_tahun_ajaran'] = $strTaAktif;
        }

        $stat_7 = [
            'rombel' => $rombelModel->where('tingkat', 'VII')->countAllResults(),
            'siswa' => $db->table('siswa')->join('rombel', 'rombel.id = siswa.rombel_id')->where('rombel.tingkat', 'VII')->where('siswa.status_siswa', 'Aktif')->countAllResults(),
            'laki' => $db->table('siswa')->join('rombel', 'rombel.id = siswa.rombel_id')->where('rombel.tingkat', 'VII')->where('siswa.status_siswa', 'Aktif')->where('siswa.jenis_kelamin', 'L')->countAllResults(),
            'perempuan' => $db->table('siswa')->join('rombel', 'rombel.id = siswa.rombel_id')->where('rombel.tingkat', 'VII')->where('siswa.status_siswa', 'Aktif')->where('siswa.jenis_kelamin', 'P')->countAllResults()
        ];

        $stat_8 = [
            'rombel' => $rombelModel->where('tingkat', 'VIII')->countAllResults(),
            'siswa' => $db->table('siswa')->join('rombel', 'rombel.id = siswa.rombel_id')->where('rombel.tingkat', 'VIII')->where('siswa.status_siswa', 'Aktif')->countAllResults(),
            'laki' => $db->table('siswa')->join('rombel', 'rombel.id = siswa.rombel_id')->where('rombel.tingkat', 'VIII')->where('siswa.status_siswa', 'Aktif')->where('siswa.jenis_kelamin', 'L')->countAllResults(),
            'perempuan' => $db->table('siswa')->join('rombel', 'rombel.id = siswa.rombel_id')->where('rombel.tingkat', 'VIII')->where('siswa.status_siswa', 'Aktif')->where('siswa.jenis_kelamin', 'P')->countAllResults()
        ];

        $stat_9 = [
            'rombel' => $rombelModel->where('tingkat', 'IX')->countAllResults(),
            'siswa' => $db->table('siswa')->join('rombel', 'rombel.id = siswa.rombel_id')->where('rombel.tingkat', 'IX')->where('siswa.status_siswa', 'Aktif')->countAllResults(),
            'laki' => $db->table('siswa')->join('rombel', 'rombel.id = siswa.rombel_id')->where('rombel.tingkat', 'IX')->where('siswa.status_siswa', 'Aktif')->where('siswa.jenis_kelamin', 'L')->countAllResults(),
            'perempuan' => $db->table('siswa')->join('rombel', 'rombel.id = siswa.rombel_id')->where('rombel.tingkat', 'IX')->where('siswa.status_siswa', 'Aktif')->where('siswa.jenis_kelamin', 'P')->countAllResults()
        ];

        $data = [
            'user'         => 'Admin',
            'navigations'  => $this->getSidebarMenu(),
            'rombel_list'  => $listRombel,
            'guru_list'    => $listGuru,
            'total_siswa'  => $totalSiswa,
            'total_rombel' => $totalRombel,
            'stat_7'       => $stat_7,
            'stat_8'       => $stat_8,
            'stat_9'       => $stat_9,
            'idTaAktif'    => $idTaAktif,
            'strTaAktif'   => $strTaAktif,
            'color'        => $this->getColor()
        ];

        return view('admin/tingkat-rombel', $data);
    }

    public function store()
    {
        if (!$this->validate([
            'rombel_name' => 'required',
            'level'       => 'required',
            'homeroom_teacher' => [
                'rules'  => 'required|is_unique[rombel.wali_kelas_id]',
                'errors' => [
                    'required'  => 'Wali kelas wajib dipilih.',
                    'is_unique' => 'Guru ini sudah menjadi wali kelas di kelas lain.'
                ]
            ],
        ])) {
            return $this->response->setJSON(['status' => 'error', 'message' => implode(', ', $this->validator->getErrors())]);
        }

        $db = \Config\Database::connect();
        $taAktif = $db->table('tahun_ajaran')->where('status', 'Aktif')->get()->getRowArray();
        
        $rombelModel = new RombelModel();
        $data = [
            'nama_rombel'     => $this->request->getPost('rombel_name'),
            'tingkat'         => $this->request->getPost('level'),
            'wali_kelas_id'   => $this->request->getPost('homeroom_teacher'),
            'id_tahun_ajaran' => $taAktif ? $taAktif['id'] : null,
            'semester'        => 'Ganjil',
            'kurikulum'       => 'Kurikulum Merdeka'
        ];

        if ($rombelModel->insert($data)) {
            return $this->response->setJSON(['status' => 'success', 'message' => 'Rombel berhasil ditambahkan']);
        } else {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Gagal menyimpan data']);
        }
    }

    public function update()
    {
        $id = $this->request->getPost('id');
        if (!$id) return $this->response->setJSON(['status' => 'error', 'message' => 'ID tidak ditemukan.']);

        if (!$this->validate([
            'rombel_name' => 'required',
            'level'       => 'required',
            'homeroom_teacher' => [
                'rules'  => "required|is_unique[rombel.wali_kelas_id,id,{$id}]",
                'errors' => [
                    'required'  => 'Wali kelas wajib dipilih.',
                    'is_unique' => 'Guru ini sudah menjadi wali kelas di kelas lain.'
                ]
            ],
        ])) {
            return $this->response->setJSON(['status' => 'error', 'message' => implode(', ', $this->validator->getErrors())]);
        }

        $db = \Config\Database::connect();
        $taAktif = $db->table('tahun_ajaran')->where('status', 'Aktif')->get()->getRowArray();

        $rombelModel = new RombelModel();
        $data = [
            'nama_rombel'     => $this->request->getPost('rombel_name'),
            'tingkat'         => $this->request->getPost('level'),
            'wali_kelas_id'   => $this->request->getPost('homeroom_teacher'),
            'id_tahun_ajaran' => $taAktif ? $taAktif['id'] : null,
        ];

        if ($rombelModel->update($id, $data)) {
            return $this->response->setJSON(['status' => 'success', 'message' => 'Data berhasil diperbarui']);
        } else {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Gagal update database']);
        }
    }

    public function delete()
    {
        $rombelModel = new RombelModel();
        $id = $this->request->getPost('id');

        if (!$id) return $this->response->setJSON(['status' => 'error', 'message' => 'ID tidak ditemukan.']);

        try {
            if ($rombelModel->delete($id)) {
                return $this->response->setJSON(['status' => 'success', 'message' => 'Data berhasil dihapus']);
            } else {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Database menolak penghapusan.']);
            }
        } catch (\Exception $e) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Gagal menghapus: Kemungkinan masih ada data terhubung ke Rombel ini.']);
        }
    }

    public function show($id)
    {
        $db = \Config\Database::connect();

        // Ambil Tahun Ajaran Aktif untuk diinjeksikan secara dinamis
        $taAktif = $db->table('tahun_ajaran')->where('status', 'Aktif')->get()->getRowArray();
        $strTaAktif = $taAktif ? $taAktif['tahun'] : 'Belum Diset';

        $builder = $db->table('rombel');
        $builder->select('rombel.*, guru_tendik.nama_lengkap as nama_wali_kelas');
        $builder->join('guru_tendik', 'guru_tendik.id = rombel.wali_kelas_id', 'left');
        $builder->where('rombel.id', $id);
        $rombel = $builder->get()->getRowArray();

        if ($rombel) {
            $siswaList = $db->table('siswa')
                            ->select('id, nisn, nama_lengkap, jenis_kelamin')
                            ->where('rombel_id', $id)
                            ->where('status_siswa', 'Aktif')
                            ->orderBy('nama_lengkap', 'ASC')
                            ->get()->getResultArray();

            $laki = 0; $perempuan = 0;
            foreach($siswaList as $s) {
                $jk = strtoupper(trim($s['jenis_kelamin'])); 
                if($jk == 'L') $laki++;
                if($jk == 'P') $perempuan++;
            }

            // FORCE OVERRIDE TAHUN AJARAN DINAMIS
            $rombel['nama_tahun_ajaran'] = $strTaAktif;

            $rombel['siswa'] = $siswaList;
            $rombel['jumlah_laki'] = $laki;
            $rombel['jumlah_perempuan'] = $perempuan;
            $rombel['jumlah_siswa'] = count($siswaList);
            $rombel['status'] = 'success';

            return $this->response->setJSON($rombel);
        } else {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Data tidak ditemukan']);
        }
    }

    // ==============================================================
    // FUNGSI API UNTUK KELOLA SISWA (TABS)
    // ==============================================================
    public function searchUnassignedStudents()
    {
        $keyword = $this->request->getGet('keyword');
        $db = \Config\Database::connect();
        
        $builder = $db->table('siswa')
                      ->select('id, nisn, nama_lengkap, jenis_kelamin')
                      ->where('status_siswa', 'Aktif')
                      ->groupStart()
                          ->where('rombel_id IS NULL')
                          ->orWhere('rombel_id', 0)
                      ->groupEnd();
                      
        if (!empty($keyword)) {
            $builder->groupStart()
                    ->like('nama_lengkap', $keyword)
                    ->orLike('nisn', $keyword)
                    ->groupEnd();
        }
        
        $data = $builder->orderBy('nama_lengkap', 'ASC')->limit(50)->get()->getResultArray();
        return $this->response->setJSON(['status' => 'success', 'data' => $data]);
    }

    public function addStudentToRombel()
    {
        $siswaId = $this->request->getPost('siswa_id');
        $rombelId = $this->request->getPost('rombel_id');

        if (!$siswaId || !$rombelId) return $this->response->setJSON(['status' => 'error', 'message' => 'Data tidak lengkap']);

        $db = \Config\Database::connect();
        $db->table('siswa')->where('id', $siswaId)->update(['rombel_id' => $rombelId]);
        
        return $this->response->setJSON(['status' => 'success', 'message' => 'Siswa berhasil ditambahkan ke kelas']);
    }

    public function removeStudentFromRombel()
    {
        $siswaId = $this->request->getPost('siswa_id');
        if (!$siswaId) return $this->response->setJSON(['status' => 'error', 'message' => 'ID Siswa tidak ditemukan']);

        $db = \Config\Database::connect();
        $db->table('siswa')->where('id', $siswaId)->update(['rombel_id' => null]);
        
        return $this->response->setJSON(['status' => 'success', 'message' => 'Siswa berhasil dikeluarkan dari kelas']);
    }

    public function transferStudents()
    {
        $siswaIds = $this->request->getPost('siswa_ids'); 
        $targetRombelId = $this->request->getPost('target_rombel_id');

        if (empty($siswaIds) || !$targetRombelId) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Pilih siswa dan kelas tujuan']);
        }

        $db = \Config\Database::connect();
        $db->transStart();
        foreach ($siswaIds as $id) {
            $db->table('siswa')->where('id', $id)->update(['rombel_id' => $targetRombelId]);
        }
        $db->transComplete();

        if ($db->transStatus() === false) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Gagal memindahkan siswa']);
        }

        return $this->response->setJSON(['status' => 'success', 'message' => 'Siswa berhasil dipindahkan']);
    }

    // ==============================================================
    // IMPORT / EXPORT
    // ==============================================================
    public function export()
    {
        if (ob_get_length()) ob_clean();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Data Rombel');

        $headers = ['ID', 'Nama Rombel', 'Tingkat', 'Kurikulum', 'Tahun Ajaran', 'Semester', 'Nama Wali Kelas'];

        $col = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($col . '1', $header);
            $sheet->getStyle($col . '1')->getFont()->setBold(true);
            $sheet->getStyle($col . '1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FFEFEFEF');
            $sheet->getColumnDimension($col)->setAutoSize(true);
            $col++;
        }

        $db = \Config\Database::connect();
        
        $taAktif = $db->table('tahun_ajaran')->where('status', 'Aktif')->get()->getRowArray();
        $strTaAktif = $taAktif ? $taAktif['tahun'] : 'Belum Diset';

        $builder = $db->table('rombel');
        $builder->select('rombel.*, guru_tendik.nama_lengkap as nama_wali');
        $builder->join('guru_tendik', 'guru_tendik.id = rombel.wali_kelas_id', 'left');
        $builder->orderBy('rombel.tingkat', 'ASC')->orderBy('rombel.nama_rombel', 'ASC');
        $dataRombel = $builder->get()->getResultArray();

        $row = 2;
        foreach ($dataRombel as $r) {
            $sheet->setCellValue('A' . $row, $r['id']);
            $sheet->setCellValue('B' . $row, $r['nama_rombel']);
            $sheet->setCellValue('C' . $row, $r['tingkat']);
            $sheet->setCellValue('D' . $row, $r['kurikulum']);
            $sheet->setCellValue('E' . $row, $strTaAktif); // Paksa ke tahun dinamis
            $sheet->setCellValue('F' . $row, $r['semester']);
            $sheet->setCellValue('G' . $row, $r['nama_wali'] ?? 'Belum Ada');
            $row++;
        }

        $writer = new Xlsx($spreadsheet);
        $filename = 'Data_Rombel_' . date('Y-m-d') . '.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
        exit;
    }

    public function downloadTemplate()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Data Rombel');
        
        $headers = [
            'ID Rombel (KOSONGKAN JIKA BARU)', 
            'Nama Rombel (Wajib)', 
            'Tingkat (VII / VIII / IX)', 
            'ID Wali Kelas (Lihat Sheet 2)', 
            'Kurikulum', 
            'Semester (Ganjil / Genap)'
        ];

        $col = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($col . '1', $header);
            $sheet->getStyle($col . '1')->getFont()->setBold(true);
            $sheet->getStyle($col . '1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FFEFEFEF');
            $sheet->getColumnDimension($col)->setAutoSize(true);
            $col++;
        }

        $sheet2 = $spreadsheet->createSheet();
        $sheet2->setTitle('Referensi Wali Kelas');
        $sheet2->setCellValue('A1', 'ID GURU (COPY KE SHEET 1 KOLOM D)');
        $sheet2->setCellValue('B1', 'NAMA GURU');
        $sheet2->getStyle('A1:B1')->getFont()->setBold(true);
        $sheet2->getColumnDimension('A')->setAutoSize(true);
        $sheet2->getColumnDimension('B')->setAutoSize(true);

        $guruModel = new \App\Models\Admin\GuruTendikModel();
        $dataGuru = $guruModel->select('id, nama_lengkap')->orderBy('nama_lengkap', 'ASC')->findAll();
        
        $rowGuru = 2;
        foreach ($dataGuru as $guru) {
            $sheet2->setCellValue('A' . $rowGuru, $guru['id']);
            $sheet2->setCellValue('B' . $rowGuru, $guru['nama_lengkap']);
            $rowGuru++;
        }

        $spreadsheet->setActiveSheetIndex(0);
        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Template_Rombel_' . date('Y-m-d') . '.xlsx"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
        exit;
    }

    public function import()
    {
        ini_set('memory_limit', '1024M');
        ini_set('max_execution_time', '300');
        if (ob_get_length()) ob_clean();

        if (empty($_FILES)) return $this->response->setJSON(['status' => 'error', 'message' => 'File ditolak server.']);
        $file = $this->request->getFile('file_excel');
        if (!$file || !$file->isValid()) return $this->response->setJSON(['status' => 'error', 'message' => 'File corrupt.']);
        $ext = strtolower($file->getClientExtension());
        if (!in_array($ext, ['xls', 'xlsx'])) return $this->response->setJSON(['status' => 'error', 'message' => 'Harus Excel (.xlsx)']);

        $db = \Config\Database::connect();
        $db->transBegin();

        try {
            $taAktif = $db->table('tahun_ajaran')->where('status', 'Aktif')->get()->getRowArray();
            $idTa = $taAktif ? $taAktif['id'] : null;

            $spreadsheet = IOFactory::load($file->getTempName());
            $sheet = $spreadsheet->getSheet(0)->toArray(null, true, true, true); 
            
            $rombelModel = new \App\Models\Admin\RombelModel();
            $countInsert = 0; $countUpdate = 0;

            foreach ($sheet as $idx => $row) {
                if ($idx == 1) continue; 
                
                $namaRombel = trim($row['B'] ?? '');
                $tingkat    = trim($row['C'] ?? '');
                
                if (empty($namaRombel) || empty($tingkat)) continue;

                $idRombel = $row['A'] ?? null;
                $waliId   = trim($row['D'] ?? '');
                if (empty($waliId) || !is_numeric($waliId)) { $waliId = null; }

                $data = [
                    'nama_rombel'     => $namaRombel,
                    'tingkat'         => $tingkat,
                    'wali_kelas_id'   => $waliId,
                    'kurikulum'       => trim($row['E'] ?? 'Kurikulum Merdeka'),
                    'id_tahun_ajaran' => $idTa, // Set Tahun Ajaran Dinamis
                    'semester'        => trim($row['F'] ?? 'Ganjil')
                ];

                if (!empty($idRombel) && is_numeric($idRombel)) {
                    $rombelModel->update($idRombel, $data);
                    $countUpdate++;
                } else {
                    $rombelModel->insert($data);
                    $countInsert++;
                }
            }

            if ($db->transStatus() === false) {
                $db->transRollback();
                return $this->response->setJSON(['status' => 'error', 'message' => 'Gagal Database. Cek duplikasi Wali Kelas.']);
            }

            $db->transCommit();
            return $this->response->setJSON(['status' => 'success', 'message' => "Proses Selesai! $countInsert ditambahkan, $countUpdate diperbarui."]);

        } catch (\Throwable $e) {
            if (isset($db)) $db->transRollback();
            return $this->response->setJSON(['status' => 'error', 'message' => 'System Error: ' . $e->getMessage()]);
        }
    }
}