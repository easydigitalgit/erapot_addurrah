<?php

namespace App\Controllers\Admin;

use App\Controllers\AdminBaseController;
use App\Models\Admin\MasterLmModel;
use App\Models\Admin\MataPelajaranModel;
use App\Models\Admin\TahunAjaranModel;
use App\Models\Admin\RombelModel; 

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;

class MasterLmController extends AdminBaseController
{
    protected $lmModel;
    protected $mapelModel;
    protected $taModel;
    protected $rombelModel;
    protected $db;

    public function __construct()
    {
        $this->lmModel     = new MasterLmModel();
        $this->mapelModel  = new MataPelajaranModel();
        $this->taModel     = new TahunAjaranModel();
        $this->rombelModel = new RombelModel();
        $this->db          = \Config\Database::connect();
    }

    public function index(): string
    {
        $mapelList = $this->mapelModel->orderBy('nama_mapel', 'ASC')->findAll();
        
        $rombelRaw = $this->rombelModel->orderBy('tingkat', 'ASC')->findAll();
        $tingkatRaw = array_unique(array_column($rombelRaw, 'tingkat'));
        
        $romanMap = ['VII' => 7, 'VIII' => 8, 'IX' => 9, 'X' => 10, 'XI' => 11, 'XII' => 12];
        $tingkatList = [];
        foreach ($tingkatRaw as $t) {
            $val = strtoupper(trim($t));
            if (isset($romanMap[$val])) {
                $tingkatList[] = $romanMap[$val];
            } else {
                $tingkatList[] = (int)$val;
            }
        }
        $tingkatList = array_unique(array_filter($tingkatList));
        sort($tingkatList);

        if (empty($tingkatList)) {
            $tingkatList = [7, 8, 9]; 
        }

        $data = [
            'title'          => 'Master Template Deskripsi Rapor (LM)',
            'user'           => session()->get('nama_lengkap') ?? 'Admin',
            'navigations'    => $this->getSidebarMenu(),
            'color'          => $this->getColor(),
            'mapelList'      => $mapelList,
            'tingkatList'    => $tingkatList,
            'semesterList'   => ['Ganjil', 'Genap']
        ];

        return view('admin/master-lm/index', $data);
    }

    public function getData()
    {
        $data = $this->lmModel->getAllLM();
        return $this->response->setJSON(['status' => 'success', 'data' => $data]);
    }

    public function store()
    {
        if ($this->request->isAJAX()) {
            $mapel_id = $this->request->getPost('mapel_id');
            $tingkat  = $this->request->getPost('tingkat');
            $semester = $this->request->getPost('semester'); 
            $kode_lm  = trim(strtoupper($this->request->getPost('kode_lm')));
            $kategori = $this->request->getPost('kategori') ?: 'Akhir';

            $cek = $this->lmModel->where('mapel_id', $mapel_id)
                                 ->where('tingkat', $tingkat)
                                 ->where('semester', $semester)
                                 ->where('kategori', $kategori)
                                 ->where('kode_lm', $kode_lm)
                                 ->first();
            
            if ($cek) return $this->response->setJSON(['status' => 'error', 'message' => "Kode $kode_lm untuk Mapel ini di kelas, semester, & kategori tersebut sudah ada!"]);

            $taAktif = $this->taModel->where('status', 'Aktif')->first();
            $ta_id_dummy = $taAktif ? $taAktif['id'] : 1; 

            $data = [
                'tahun_ajaran_id' => $ta_id_dummy,
                'mapel_id'        => $mapel_id,
                'tingkat'         => $tingkat,
                'semester'        => $semester,
                'kategori'        => $kategori,
                'kode_lm'         => $kode_lm,
                'deskripsi_lm'    => trim($this->request->getPost('deskripsi_lm')),
                'deskripsi_a'     => trim($this->request->getPost('deskripsi_a')),
                'deskripsi_b'     => trim($this->request->getPost('deskripsi_b')),
                'deskripsi_c'     => trim($this->request->getPost('deskripsi_c')),
                'deskripsi_d'     => trim($this->request->getPost('deskripsi_d')),
                'status'          => 'Aktif'
            ];

            if ($this->lmModel->insert($data)) {
                return $this->response->setJSON(['status' => 'success', 'message' => 'Data LM berhasil ditambahkan.']);
            }
            return $this->response->setJSON(['status' => 'error', 'message' => 'Gagal menyimpan data.']);
        }
    }

    public function update()
    {
        if ($this->request->isAJAX()) {
            $id = $this->request->getPost('id');

            $data = [
                'mapel_id'        => $this->request->getPost('mapel_id'),
                'tingkat'         => $this->request->getPost('tingkat'),
                'semester'        => $this->request->getPost('semester'),
                'kategori'        => $this->request->getPost('kategori') ?: 'Akhir',
                'kode_lm'         => trim(strtoupper($this->request->getPost('kode_lm'))),
                'deskripsi_lm'    => trim($this->request->getPost('deskripsi_lm')),
                'deskripsi_a'     => trim($this->request->getPost('deskripsi_a')),
                'deskripsi_b'     => trim($this->request->getPost('deskripsi_b')),
                'deskripsi_c'     => trim($this->request->getPost('deskripsi_c')),
                'deskripsi_d'     => trim($this->request->getPost('deskripsi_d'))
            ];

            if ($this->lmModel->update($id, $data)) {
                return $this->response->setJSON(['status' => 'success', 'message' => 'Data LM berhasil diperbarui.']);
            }
            return $this->response->setJSON(['status' => 'error', 'message' => 'Gagal memperbarui data.']);
        }
    }

    public function delete()
    {
        if ($this->request->isAJAX()) {
            $id = $this->request->getPost('id');
            if ($this->lmModel->delete($id)) return $this->response->setJSON(['status' => 'success', 'message' => 'Data berhasil dihapus.']);
            return $this->response->setJSON(['status' => 'error', 'message' => 'Gagal menghapus data.']);
        }
    }

    // =========================================================================
    // FUNGSI 1: BUILD TEMPLATE EXCEL (Hanya Menghasilkan File Kosong / Format Input)
    // =========================================================================
    private function buildTemplateExcel($jenis)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->getColumnDimension('A')->setWidth(5);
        $sheet->getColumnDimension('B')->setWidth(30);
        $sheet->getColumnDimension('C')->setWidth(10);
        $sheet->getColumnDimension('D')->setWidth(15);
        $sheet->getColumnDimension('E')->setWidth(35);
        $sheet->getColumnDimension('F')->setWidth(40);
        $sheet->getColumnDimension('G')->setWidth(40);
        $sheet->getColumnDimension('H')->setWidth(40);
        $sheet->getColumnDimension('I')->setWidth(40);
        
        $jenisLabel = strtoupper($jenis) . " SEMESTER";

        $sheet->mergeCells('A1:I1');
        $sheet->setCellValue('A1', 'TEMPLATE IMPORT LINGKUP MATERI (LM)');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        $sheet->mergeCells('A2:I2');
        $sheet->setCellValue('A2', 'SMP SWASTA IT AD DURRAH - ' . $jenisLabel);
        $sheet->getStyle('A2')->getFont()->setBold(true)->setSize(12);
        $sheet->getStyle('A2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        $row = 4;
        $headers = ['NO', 'MATA PELAJARAN', 'KELAS', 'KODE LM', 'JUDUL MATERI / DESKRIPSI LM', 'Sangat Baik (A)', 'Baik (B)', 'Cukup (C)', 'Perlu Bimbingan (D)'];
        $col = 'A';
        foreach ($headers as $h) {
            $sheet->setCellValue($col . $row, $h);
            $sheet->getStyle($col . $row)->applyFromArray([
                'font' => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF']],
                'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['argb' => 'FF00B050']],
                'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]
            ]);
            $col++;
        }

        // Generate data template kosong yang membantu guru
        $row++;
        $mapels = ['Pendidikan Agama Islam', 'Bahasa Indonesia'];
        $no = 1;
        $startLm = ($jenis == 'akhir') ? 5 : 1;
        $endLm = ($jenis == 'akhir') ? 9 : 4;

        foreach ($mapels as $mapel) {
            foreach ([7, 8, 9] as $kelas) {
                for ($lm = $startLm; $lm <= $endLm; $lm++) {
                    $sheet->setCellValue('A' . $row, $no++);
                    $sheet->setCellValue('B' . $row, $mapel);
                    $sheet->setCellValue('C' . $row, $kelas);
                    $sheet->setCellValue('D' . $row, 'LM ' . $lm);
                    $sheet->setCellValue('E' . $row, 'Tulis judul materi di sini...');
                    $row++;
                }
            }
        }

        $endRow = $row - 1;
        $sheet->getStyle("A4:I{$endRow}")->applyFromArray([
            'borders' => ['allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN]]
        ]);

        return $spreadsheet;
    }

    // =========================================================================
    // FUNGSI 2: BUILD EXPORT EXCEL (Mengambil Data Asli dari Database)
    // =========================================================================
    private function buildExportExcel($jenis)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->getColumnDimension('A')->setWidth(5);
        $sheet->getColumnDimension('B')->setWidth(30);
        $sheet->getColumnDimension('C')->setWidth(10);
        $sheet->getColumnDimension('D')->setWidth(15);
        $sheet->getColumnDimension('E')->setWidth(35);
        $sheet->getColumnDimension('F')->setWidth(40);
        $sheet->getColumnDimension('G')->setWidth(40);
        $sheet->getColumnDimension('H')->setWidth(40);
        $sheet->getColumnDimension('I')->setWidth(40);
        
        $jenisLabel = strtoupper($jenis) == 'SEMUA' ? "SEMUA SEMESTER" : strtoupper($jenis) . " SEMESTER";

        $sheet->mergeCells('A1:I1');
        $sheet->setCellValue('A1', 'DATA MASTER LINGKUP MATERI (LM)');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        $sheet->mergeCells('A2:I2');
        $sheet->setCellValue('A2', 'SMP SWASTA IT AD DURRAH - ' . $jenisLabel);
        $sheet->getStyle('A2')->getFont()->setBold(true)->setSize(12);
        $sheet->getStyle('A2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        $row = 4;
        $headers = ['NO', 'MATA PELAJARAN', 'KELAS', 'KODE LM', 'JUDUL MATERI / DESKRIPSI LM', 'Sangat Baik (A)', 'Baik (B)', 'Cukup (C)', 'Perlu Bimbingan (D)'];
        $col = 'A';
        foreach ($headers as $h) {
            $sheet->setCellValue($col . $row, $h);
            $sheet->getStyle($col . $row)->applyFromArray([
                'font' => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF']],
                'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['argb' => 'FF00B050']],
                'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]
            ]);
            $col++;
        }

        $row++;
        $no = 1;
        $lmData = $this->lmModel->getAllLM();
        $startLm = ($jenis == 'akhir') ? 5 : 1;
        $endLm = ($jenis == 'akhir') ? 9 : 4;

        foreach ($lmData as $lm) {
            $sem = $lm['semester'] ?? 'Ganjil';
            if ($jenis != 'semua' && $jenis != 'ALL') {
                preg_match('/LM\s*(\d+)/i', $lm['kode_lm'], $mCodes);
                $codeNum = isset($mCodes[1]) ? (int)$mCodes[1] : 0;
                if ($codeNum < $startLm || $codeNum > $endLm) continue;
            }

            $sheet->setCellValue('A' . $row, $no++);
            $sheet->setCellValue('B' . $row, $lm['nama_mapel']);
            $sheet->setCellValue('C' . $row, $lm['tingkat']);
            $sheet->setCellValue('D' . $row, $lm['kode_lm']);
            $sheet->setCellValue('E' . $row, $lm['deskripsi_lm']);
            $sheet->setCellValue('F' . $row, $lm['deskripsi_a']);
            $sheet->setCellValue('G' . $row, $lm['deskripsi_b']);
            $sheet->setCellValue('H' . $row, $lm['deskripsi_c']);
            $sheet->setCellValue('I' . $row, $lm['deskripsi_d']);
            $row++;
        }

        $endRow = $row - 1;
        if ($endRow >= 4) {
             $sheet->getStyle("A4:I{$endRow}")->applyFromArray([
                 'borders' => ['allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN]]
             ]);
        }
        
        return $spreadsheet;
    }

    public function downloadTemplate($jenis = 'tengah')
    {
        $taAktif = $this->taModel->where('status', 'Aktif')->first();
        $sem     = $taAktif ? $taAktif['semester'] : 'Ganjil';
        
        $spreadsheet = $this->buildTemplateExcel($jenis); 
        $writer = new Xlsx($spreadsheet);
        $filename = 'Template_Import_LM_' . ucfirst($jenis) . '_' . $sem . '_Semester.xlsx';
        
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
        exit;
    }

    public function export($jenis = 'tengah')
    {
        $taAktif = $this->taModel->where('status', 'Aktif')->first();
        $semester = $taAktif ? $taAktif['semester'] : 'Ganjil';
        
        $spreadsheet = $this->buildExportExcel($jenis); // KHUSUS MEMANGGIL FUNGSI EXPORT
        $writer = new Xlsx($spreadsheet);
        $filename = 'Export_Master_LM_' . $semester . '_' . ucfirst($jenis) . 'Semester_' . date('Ymd_His') . '.xlsx';
        
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
        exit;
    }

    // =========================================================================
    // FUNGSI SMART IMPORT (HARD STOP VALIDATION & ANTI-AUTO INSERT)
    // =========================================================================
    public function import()
    {
        if ($this->request->isAJAX()) {
            $file = $this->request->getFile('file_excel');

            if (!$file || !$file->isValid()) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'File tidak valid atau rusak.']);
            }

            try {
                $ext = strtolower($file->getClientExtension());
                $filepath = $file->getTempName();
                $fileName = $file->getClientName(); 
                
                $extractedData = []; 
                $detectedSemester = '';

                // --- SMART DETECTION: KATEGORI & SEMESTER DARI NAMA FILE ---
                // Gunakan regex yang lebih toleran terhadap underscore (_) dan tanda hubung (-)
                $fnLower = strtolower($fileName);

                // 1. Deteksi Semester (Ganjil / Genap)
                if (preg_match('/\b(ganjil|1|i)\b/i', $fnLower)) {
                    $detectedSemester = 'Ganjil';
                }
                if (preg_match('/\b(genap|2|ii)\b/i', $fnLower)) {
                    $detectedSemester = 'Genap';
                }

                // 2. Deteksi Kategori (Tengah / Akhir)
                $detectedKategori = ''; // Awalnya kosong agar tidak tebak-tebakan
                if (preg_match('/\b(tengah|pts|sts|uts)\b/i', $fnLower)) {
                    $detectedKategori = 'Tengah';
                } else if (preg_match('/\b(akhir|pas|pat|sas|uas|ukp)\b/i', $fnLower)) {
                    $detectedKategori = 'Akhir';
                }

                // --- OVERRIDE DARI USER (Jika memilih manual di modal) ---
                $forceSemester = $this->request->getPost('force_semester');
                $forceKategori = $this->request->getPost('force_kategori');

                if ($forceSemester && $forceSemester !== 'auto') {
                    $detectedSemester = $forceSemester;
                }
                if ($forceKategori && $forceKategori !== 'auto') {
                    $detectedKategori = $forceKategori;
                }

                // --- AUTO MIGRATION: Pastikan kolom kategori ada ---
                $this->db = \Config\Database::connect();
                if (!$this->db->fieldExists('kategori', 'master_lm')) {
                    $this->db->query("ALTER TABLE master_lm ADD COLUMN kategori ENUM('Tengah', 'Akhir') DEFAULT 'Akhir' AFTER semester");
                }

                // DEFINE HELPERS IN HIGHER SCOPE
                $cleanStr = function($str) { return strtolower(preg_replace('/[^a-z0-9]/', '', (string)$str)); };
                $normalizeMapel = function($s) use ($cleanStr) {
                    $s = $cleanStr($s);
                    // Normalisasi variasi umum: ts/st -> t
                    $s = str_replace(['ts', 'st'], 't', $s);
                    return $s;
                };

                $refMapels = $this->db->table('mata_pelajaran')->select('id, nama_mapel')->get()->getResultArray();
                $mapelsClean = [];
                foreach($refMapels as $m) {
                    $mapelsClean[$normalizeMapel($m['nama_mapel'])] = $m['id'];
                }

                if ($ext === 'docx' || $ext === 'doc') {
                    $xml = @file_get_contents('zip://' . $filepath . '#word/document.xml');
                    if ($xml === false) {
                         return $this->response->setJSON(['status' => 'error', 'message' => 'Gagal membaca struktur file Word.']);
                    }
                    
                    $xml = str_replace(['</w:p>', '<w:br/>'], "\n", $xml);
                    $text = strip_tags($xml);

                    $lines = explode("\n", $text);
                    
                    // Deteksi Semester & Kategori dari isi dokumen (Hanya 20 baris pertama/header)
                    $headerRows = array_slice($lines, 0, 20);
                    $headerText = implode("\n", $headerRows);

                    if (empty($detectedSemester) && (!$forceSemester || $forceSemester === 'auto')) {
                        if (preg_match('/\b(ganjil|1|i)\b/i', $headerText)) $detectedSemester = 'Ganjil';
                        else if (preg_match('/\b(genap|2|ii)\b/i', $headerText)) $detectedSemester = 'Genap';
                    }

                    // --- VOTING SYSTEM KATEGORI ---
                    if (empty($detectedKategori) || (!$forceKategori || $forceKategori === 'auto')) {
                        $scoreSTS = 0; $scoreSAS = 0;
                        foreach($headerRows as $hRow) {
                            if (preg_match('/\b(tengah|pts|sts|uts|sumatif\s*tengah|penilaian\s*tengah)\b/i', $hRow)) $scoreSTS++;
                            if (preg_match('/\b(akhir|pas|pat|sas|uas|ukp|sumatif\s*akhir|penilaian\s*akhir)\b/i', $hRow)) $scoreSAS++;
                        }
                        
                        if ($scoreSTS > $scoreSAS) $detectedKategori = 'Tengah';
                        else if ($scoreSAS > $scoreSTS) $detectedKategori = 'Akhir';
                    }
                    $currentMapel = ''; $currentKelas = 0; $lmCounter = 1;
                    $lineIdx = 0;

                    foreach ($lines as $line) {
                        $lineIdx++;
                        $line = trim($line);
                        if (empty($line)) continue;

                        // Abaikan Header Dokumen (Hanya berlaku di 10 baris pertama untuk menghindari salah deteksi materi as header)
                        if ($lineIdx < 10) {
                            if (stripos($line, 'Lingkup Materi') !== false || stripos($line, 'Sumatif') !== false || stripos($line, 'Ad Durrah') !== false) continue;
                        }

                        // 1. DETEKSI MATA PELAJARAN (Wajib Format: "1. Nama Mapel" atau "1) Nama Mapel")
                        if (preg_match('/^(\d+)[\.\)]\s*(.+)$/', $line, $matches)) {
                            $potentialMapel = trim($matches[2]); 
                            $cleanPotential = $normalizeMapel($potentialMapel);
                            
                            $foundMapelId = null;
                            if (isset($mapelsClean[$cleanPotential])) {
                                $foundMapelId = $mapelsClean[$cleanPotential];
                            } else {
                                // Fuzzy Stage 1: Contains Normalized
                                foreach ($mapelsClean as $normName => $id) {
                                    if (strlen($normName) > 2 && (stripos($cleanPotential, $normName) !== false || stripos($normName, $cleanPotential) !== false)) {
                                        $foundMapelId = $id;
                                        break;
                                    }
                                }
                                
                                // Fuzzy Stage 2: Sub-Word Match (Cek potongan kata)
                                if (!$foundMapelId) {
                                    foreach ($refMapels as $mDB) {
                                        $dbWords = explode(' ', strtolower(preg_replace('/[^a-z]/', ' ', $mDB['nama_mapel'])));
                                        $xlWords = explode(' ', strtolower(preg_replace('/[^a-z]/', ' ', $potentialMapel)));
                                        $common = array_intersect(array_filter($dbWords), array_filter($xlWords));
                                        if (!empty($common)) {
                                            $foundMapelId = $mDB['id'];
                                            break;
                                        }
                                    }
                                }
                            }

                            if ($foundMapelId) {
                                $currentMapel = $potentialMapel;
                                $currentKelas = 0; 
                                continue;
                            }
                        }

                        // 2. DETEKSI KELAS
                        if (preg_match('/(?:^|[A-Z]\.\s*)Kelas\s+(\d+|VII|VIII|IX|X|XI|XII)/i', $line, $matches)) {
                            $kelasVal = $matches[1];
                            $romanMap = ['VII' => 7, 'VIII' => 8, 'IX' => 9, 'X' => 10, 'XI' => 11, 'XII' => 12];
                            $currentKelas = isset($romanMap[strtoupper($kelasVal)]) ? $romanMap[strtoupper($kelasVal)] : (int)$kelasVal;
                            $lmCounter = 1; 
                            continue;
                        }

                        // 3. EKSTRAKSI MATERI
                        if (!empty($currentMapel) && $currentKelas > 0) {
                            // Bersihkan bullet points termasuk tab/spasi ganda & non-breaking space
                            $cleanLine = preg_replace('/^[•\-\*\.➢\s\t\x{00A0}]+/u', '', $line);
                            if (empty($cleanLine)) continue;
                            
                            if (stripos($cleanLine, 'Kelas ' . $currentKelas) !== false && strlen($cleanLine) < 15) continue;
                            if (preg_match('/^\d+[\.\)]\s*$/', $cleanLine)) continue;

                            $detectedLmId = $lmCounter;
                            $materiText = $cleanLine;

                            if (preg_match('/^(?:LM\s*)?(\d+)[\.\)\s:-]+(.+)$/i', $cleanLine, $matches)) {
                                $detectedLmId = (int)$matches[1];
                                $materiText = trim($matches[2]);
                            }

                            $extractedData[] = [
                                'mapel'  => $currentMapel, 
                                'kelas'  => $currentKelas, 
                                'kode'   => 'LM ' . $detectedLmId, 
                                'materi' => $materiText
                            ];

                            $lmCounter = $detectedLmId + 1;
                        }
                    }
                    if (empty($extractedData)) {
                        return $this->response->setJSON(['status' => 'error', 'message' => 'Gagal menemukan daftar materi di dalam Word.']);
                    }

                } else if (in_array($ext, ['xls', 'xlsx', 'csv'])) {
                    
                    if ($ext === 'csv') $reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
                    elseif ($ext === 'xls') $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
                    else $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();

                    $reader->setReadDataOnly(true);
                    $spreadsheet = $reader->load($filepath);
                    $sheetData = $spreadsheet->getActiveSheet()->toArray();

                    if (count($sheetData) <= 1) return $this->response->setJSON(['status' => 'error', 'message' => 'Data Excel kosong.']);

                    if (empty($detectedSemester) && (!$forceSemester || $forceSemester === 'auto')) {
                        $scanLimitSemester = min(15, count($sheetData)); 
                        for ($r = 0; $r < $scanLimitSemester; $r++) {
                            $rowStr = implode(" ", $sheetData[$r]);
                            if (preg_match('/\b(ganjil|genap)\b/i', $rowStr, $matches)) {
                                $detectedSemester = ucfirst(strtolower($matches[1]));
                                break;
                            }
                        }
                    }

                    // Tambahan Deteksi Kategori di Excel (Limit 15 baris pertama) menggunakan VOTING
                    if (empty($detectedKategori) || (!$forceKategori || $forceKategori === 'auto')) {
                        $scoreSTS = 0; $scoreSAS = 0;
                        $scanLimitKat = min(15, count($sheetData));
                        for ($r = 0; $r < $scanLimitKat; $r++) {
                            $rowStr = implode(" ", $sheetData[$r]);
                            if (preg_match('/\b(tengah|pts|sts|uts|sumatif\s*tengah|penilaian\s*tengah)\b/i', $rowStr)) $scoreSTS++;
                            if (preg_match('/\b(akhir|pas|pat|sas|uas|ukp|sumatif\s*akhir|penilaian\s*akhir)\b/i', $rowStr)) $scoreSAS++;
                        }
                        if ($scoreSTS > $scoreSAS) $detectedKategori = 'Tengah';
                        else if ($scoreSAS > $scoreSTS) $detectedKategori = 'Akhir';
                    }

                    $numCols = count($sheetData[0]);
                    $colScores = [];
                    for($i=0; $i<$numCols; $i++) $colScores[$i] = [
                        'mapel' => 0, 'kelas' => 0, 'kode' => 0, 'materi' => 0,
                        'desc_a' => 0, 'desc_b' => 0, 'desc_c' => 0, 'desc_d' => 0
                    ];

                    $scanLimit = min(30, count($sheetData)); 
                    for ($r = 0; $r < $scanLimit; $r++) {
                        foreach ($sheetData[$r] as $colIdx => $cellValue) {
                            $cellStr = trim((string)$cellValue);
                            if (empty($cellStr)) continue;
                            $cClean = $cleanStr($cellStr);

                            if ($r < 10) { 
                                if (preg_match('/mapel|pelajaran|studi/', $cClean)) $colScores[$colIdx]['mapel'] += 100;
                                if (preg_match('/kelas|tingkat/', $cClean)) $colScores[$colIdx]['kelas'] += 100;
                                if (preg_match('/kode/', $cClean)) $colScores[$colIdx]['kode'] += 100;
                                if (preg_match('/judul|pokok|materi|deskripsi/', $cClean)) $colScores[$colIdx]['materi'] += 100;
                            }

                            foreach($mapelsClean as $m) {
                                if ($m !== '' && (strpos($cClean, $m) !== false || strpos($m, $cClean) !== false)) $colScores[$colIdx]['mapel'] += 15;
                            }

                            if (preg_match('/^lm\s*\d+/i', $cellStr)) $colScores[$colIdx]['kode'] += 20;
                            if (is_numeric($cellStr) && $cellStr >= 1 && $cellStr <= 12) $colScores[$colIdx]['kelas'] += 10;
                            if (strlen($cellStr) > 20) $colScores[$colIdx]['materi'] += 10; 

                            if (preg_match('/(sangat\s*baik|level\s*a|predikat\s*a)/i', $cClean)) $colScores[$colIdx]['desc_a'] += 100;
                            if (preg_match('/(baik|level\s*b|predikat\s*b)/i', $cClean) && !preg_match('/sangat/i', $cClean)) $colScores[$colIdx]['desc_b'] += 100;
                            if (preg_match('/(cukup|level\s*c|predikat\s*c)/i', $cClean)) $colScores[$colIdx]['desc_c'] += 100;
                            if (preg_match('/(perlu\s*bimbingan|level\s*d|predikat\s*d)/i', $cClean)) $colScores[$colIdx]['desc_d'] += 100;
                        }
                    }

                    $idx_mapel = -1; $idx_kelas = -1; $idx_kode = -1; $idx_materi = -1;
                    $idx_a = -1; $idx_b = -1; $idx_c = -1; $idx_d = -1;
                    $mM = 0; $mK = 0; $mKo = 0; $mMa = 0;
                    $mA = 0; $mB = 0; $mC = 0; $mD = 0;
                    
                    foreach ($colScores as $colIdx => $scores) {
                        if ($scores['mapel'] > $mM && $scores['mapel'] >= 10) { $mM = $scores['mapel']; $idx_mapel = $colIdx; }
                        if ($scores['kelas'] > $mK && $scores['kelas'] >= 10) { $mK = $scores['kelas']; $idx_kelas = $colIdx; }
                        if ($scores['kode'] > $mKo && $scores['kode'] >= 10) { $mKo = $scores['kode']; $idx_kode = $colIdx; }
                        if ($scores['materi'] > $mMa && $scores['materi'] >= 10) { $mMa = $scores['materi']; $idx_materi = $colIdx; }
                        if ($scores['desc_a'] > $mA && $scores['desc_a'] >= 10) { $mA = $scores['desc_a']; $idx_a = $colIdx; }
                        if ($scores['desc_b'] > $mB && $scores['desc_b'] >= 10) { $mB = $scores['desc_b']; $idx_b = $colIdx; }
                        if ($scores['desc_c'] > $mC && $scores['desc_c'] >= 10) { $mC = $scores['desc_c']; $idx_c = $colIdx; }
                        if ($scores['desc_d'] > $mD && $scores['desc_d'] >= 10) { $mD = $scores['desc_d']; $idx_d = $colIdx; }
                    }

                    if ($idx_mapel === -1 || $idx_kelas === -1 || $idx_kode === -1 || $idx_materi === -1) {
                        return $this->response->setJSON(['status' => 'error', 'message' => 'Sistem tidak dapat mengimpor format ini. Pastikan ada kolom Mapel, Kelas, Kode LM, dan Judul Materi.']);
                    }

                    for ($i = 0; $i < count($sheetData); $i++) {
                        $row = $sheetData[$i];
                        if (empty(array_filter($row))) continue;

                        $nama_mapel = isset($row[$idx_mapel]) ? trim((string)$row[$idx_mapel]) : '';
                        $tingkat_str= isset($row[$idx_kelas]) ? trim((string)$row[$idx_kelas]) : '';
                        $kode_lm    = isset($row[$idx_kode]) ? trim(strtoupper((string)$row[$idx_kode])) : '';
                        $materi     = isset($row[$idx_materi]) ? trim((string)$row[$idx_materi]) : '';
                        
                        $txtA       = ($idx_a != -1) ? trim((string)$row[$idx_a]) : '';
                        $txtB       = ($idx_b != -1) ? trim((string)$row[$idx_b]) : '';
                        $txtC       = ($idx_c != -1) ? trim((string)$row[$idx_c]) : '';
                        $txtD       = ($idx_d != -1) ? trim((string)$row[$idx_d]) : '';

                        if (empty($nama_mapel) || empty($tingkat_str) || empty($kode_lm) || empty($materi)) continue;
                        if (strtolower($nama_mapel) === 'mata pelajaran' || strtolower($kode_lm) === 'kode lm') continue;

                        preg_match('/(\d+)/', $tingkat_str, $matches);
                        $tingkat = isset($matches[1]) ? (int)$matches[1] : 7;

                        $extractedData[] = [
                            'mapel' => $nama_mapel, 
                            'kelas' => $tingkat, 
                            'kode' => $kode_lm, 
                            'materi' => $materi,
                            'desc_a' => $txtA,
                            'desc_b' => $txtB,
                            'desc_c' => $txtC,
                            'desc_d' => $txtD
                        ];
                    }
                } 
                else {
                    return $this->response->setJSON(['status' => 'error', 'message' => 'Format file tidak didukung.']);
                }

                // =========================================================
                // ARSITEKTUR HARD STOP VALIDATION & ANTI-AUTO INSERT
                // =========================================================
                $db = \Config\Database::connect();
                $db->transBegin(); // MULAI TRANSAKSI BAJA

                $activeTahunRow = $this->taModel->where('status', 'Aktif')->first();
                
                // --- SMART TA MAPPING: Cari ID Tahun Ajaran yang sesuai dengan Semester terdeteksi ---
                $finalTahunAjaranId = $activeTahunRow ? $activeTahunRow['id'] : 1;
                
                if ($activeTahunRow && $detectedSemester !== $activeTahunRow['semester']) {
                    // Cari baris TA lain dengan tahun yang sama tapi semester berbeda
                    $sameYearTa = $this->taModel->where('tahun', $activeTahunRow['tahun'])
                                               ->where('semester', $detectedSemester)
                                               ->first();
                    if ($sameYearTa) {
                        $finalTahunAjaranId = $sameYearTa['id'];
                    }
                }

                if (empty($detectedSemester)) {
                    $detectedSemester = $activeTahunRow ? $activeTahunRow['semester'] : 'Ganjil';
                }

                $insertCount = 0; $updateCount = 0; $skipCount = 0;
                $hardErrors = []; // WADAH PENAMPUNG TYPO MAPEL

                // Cache seluruh master mapel untuk verifikasi super cepat
                $allMapelsDB = $db->table('mata_pelajaran')->get()->getResultArray();

                foreach ($extractedData as $row) {
                    $nama_mapel = trim($row['mapel']);
                    $tingkat    = $row['kelas'];
                    $kode_lm_new= $row['kode']; 
                    $materi     = $row['materi'];

                    $mapel_id = null;

                    // FUZZY SEARCH MATCHING (Mencocokkan tanpa membuat data baru)
                    $nXL = $normalizeMapel($nama_mapel);
                    if (isset($mapelsClean[$nXL])) {
                        $mapel_id = $mapelsClean[$nXL];
                    } else {
                        foreach ($allMapelsDB as $mDB) {
                            $dbNama = strtolower(trim($mDB['nama_mapel']));
                            $xlNama = strtolower($nama_mapel);
                            if (strpos($dbNama, $xlNama) !== false || strpos($xlNama, $dbNama) !== false) {
                                $mapel_id = $mDB['id'];
                                break;
                            }
                        }
                    }

                    if (!$mapel_id) {
                        $hardErrors[] = "<b>{$nama_mapel}</b> (Terdeteksi di Kelas {$tingkat} - {$kode_lm_new})";
                        continue;
                    }

                    $materiLower = mb_strtolower(mb_substr($materi, 0, 1)) . mb_substr($materi, 1);
                    $descA = !empty($row['desc_a']) ? $row['desc_a'] : ("Menunjukkan penguasaan yang sangat baik dalam " . $materiLower . ".");
                    $descB = !empty($row['desc_b']) ? $row['desc_b'] : ("Menunjukkan penguasaan yang baik dalam " . $materiLower . ".");
                    $descC = !empty($row['desc_c']) ? $row['desc_c'] : ("Cukup menguasai dalam " . $materiLower . ", namun perlu peningkatan pemahaman lebih lanjut.");
                    $descD = !empty($row['desc_d']) ? $row['desc_d'] : ("Perlu bimbingan dan pendampingan khusus dalam " . $materiLower . ".");

                    // SMART MAPPING: Cari apakah materi ini SUDAH ADA di database (untuk mapel & tingkat & semester & kategori ini)
                    $matchMateri = $this->lmModel->where([
                        'mapel_id' => $mapel_id,
                        'tingkat'  => $tingkat,
                        'semester' => $detectedSemester,
                        'kategori' => $detectedKategori,
                        'deskripsi_lm' => $materi
                    ])->first();

                    $final_kode_lm = $matchMateri ? $matchMateri['kode_lm'] : $kode_lm_new;

                    // Cek berdasarkan KODE LM (untuk update konten jika teksnya berubah dikit)
                    $existingData = $this->lmModel->where([
                        'mapel_id' => $mapel_id,
                        'tingkat'  => $tingkat,
                        'semester' => $detectedSemester, 
                        'kategori' => $detectedKategori,
                        'kode_lm'  => $final_kode_lm
                    ])->first();
                    
                    // --- FINAL MAPPING: Urutan Prioritas: Force > Detected > Smart LM > Default ---
                    $rowKategori = '';
                    
                    // 1. Prioritas Utama: Force dari User
                    if ($forceKategori && $forceKategori !== 'auto') {
                        $rowKategori = $forceKategori;
                    } 
                    // 2. Prioritas Kedua: Hasil Scan Kata Kunci (Detected)
                    else if (!empty($detectedKategori)) {
                        $rowKategori = $detectedKategori;
                    }
                    // 3. Prioritas Ketiga: Logika Nomor LM (Smart Fallback)
                    else {
                        if (preg_match('/(\d+)/', $final_kode_lm, $nums)) {
                            $num = (int)$nums[1];
                            if ($num >= 1 && $num <= 4) $rowKategori = 'Tengah';
                            else if ($num >= 5) $rowKategori = 'Akhir';
                        }
                    }

                    // 4. Fallback Terakhir jika benar-benar tidak terdeteksi
                    if (empty($rowKategori)) $rowKategori = 'Akhir';
                    
                    $newData = [
                        'tahun_ajaran_id' => $finalTahunAjaranId, 
                        'mapel_id'        => $mapel_id,
                        'tingkat'         => $tingkat,
                        'semester'        => $detectedSemester,
                        'kategori'        => $rowKategori,
                        'kode_lm'         => $final_kode_lm,
                        'deskripsi_lm'    => $materi,
                        'deskripsi_a'     => $descA,
                        'deskripsi_b'     => $descB,
                        'deskripsi_c'     => $descC,
                        'deskripsi_d'     => $descD,
                        'status'          => 'Aktif'
                    ];

                    if ($existingData) {
                        // Update jika ada perubahan teks atau template
                        if (trim($existingData['deskripsi_lm']) != trim($materi) || trim($existingData['deskripsi_a']) != trim($descA)) {
                            unset($newData['tahun_ajaran_id']);
                            $this->lmModel->update($existingData['id'], $newData);
                            $updateCount++;
                        } else {
                            $skipCount++;
                        }
                    } else {
                        $this->lmModel->insert($newData);
                        $insertCount++;
                    }
                }

                // EKSEKUSI HARD STOP JIKA ADA TYPO MAPEL
                if (!empty($hardErrors)) {
                    $db->transRollback(); // BATALKAN SEMUA PROSES SIMPAN

                    $uniqueErrors = array_unique($hardErrors);
                    $errorMsg = "<ul class='text-left list-disc pl-4 text-xs space-y-1 mt-2'>";
                    $count = 0;
                    foreach ($uniqueErrors as $err) {
                        if ($count >= 5) {
                            $errorMsg .= "<li>...dan " . (count($uniqueErrors) - 5) . " mapel typo lainnya.</li>";
                            break;
                        }
                        $errorMsg .= "<li>{$err}</li>";
                        $count++;
                    }
                    $errorMsg .= "</ul><p class='mt-3 text-xs font-bold text-red-600'>Proses dibatalkan 100%. Pastikan nama mapel di file Anda SAMA PERSIS dengan yang terdaftar di Master Mapel web!</p>";
                    
                    return $this->response->setJSON([
                        'status' => 'error', 
                        'message' => '<b>GAGAL IMPORT! Mapel Typo Terdeteksi:</b>' . $errorMsg
                    ]);
                }

                $db->transCommit(); // JIKA AMAN, SIMPAN PERMANEN

                $tipeUpload = ($ext === 'docx' || $ext === 'doc') ? 'File Word' : 'Excel';
                
            $finalMsg = "<div style='text-align:left;'>";
            $finalMsg .= "<b class='text-emerald-600 dark:text-emerald-400'>IMPORT BERHASIL!</b><br>";
            $finalMsg .= "<div class='mt-2 p-3 bg-slate-50 dark:bg-slate-900/50 rounded-xl border border-slate-200 dark:border-slate-700 text-xs text-slate-600 dark:text-slate-300'>";
            $finalMsg .= "📂 Kategori: <b class='text-blue-500'>" . ($detectedKategori == 'Tengah' ? 'Tengah Semester (STS)' : 'Akhir Semester (SAS)') . "</b><br>";
            $finalMsg .= "🎯 Semester: <b class='text-indigo-500'>" . ($detectedSemester ?: 'Ganjil') . "</b><br>";
            $finalMsg .= "<hr class='my-2 border-slate-200 dark:border-slate-700'>";
            $finalMsg .= "✅ <b>$insertCount</b> data materi baru ditambahkan.<br>";
            $finalMsg .= "🔄 <b>$updateCount</b> data materi lama diperbarui.<br>";
            $finalMsg .= "⏭️ <b>$skipCount</b> data dilewati (karena sama persis).";
            $finalMsg .= "</div>";
            $finalMsg .= "</div>";

            return $this->response->setJSON([
                'status' => 'success',
                'message' => $finalMsg
            ]);

            } catch (\Exception $e) {
                if (isset($db)) $db->transRollback();
                return $this->response->setJSON(['status' => 'error', 'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage()]);
            }
        }
    }
}