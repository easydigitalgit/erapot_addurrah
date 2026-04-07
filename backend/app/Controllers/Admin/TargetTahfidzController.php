<?php

namespace App\Controllers\Admin;

use App\Controllers\AdminBaseController;
use App\Models\Admin\TargetTahfidzModel;
use App\Models\Admin\RefJuzModel;   
use App\Models\Admin\RefSurahModel; 
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;

class TargetTahfidzController extends AdminBaseController
{
    public function index(): string
    {
        $targetModel = new TargetTahfidzModel();
        $juzModel    = new RefJuzModel();
        $surahModel  = new RefSurahModel();
        $db = \Config\Database::connect();

        // 1. AMBIL TAHUN AJARAN & SEMESTER AKTIF
        $ta_aktif = $db->table('tahun_ajaran')
                       ->groupStart()
                           ->where('status', 'Aktif')
                           ->orWhere('status', 'aktif')
                           ->orWhere('status', '1')
                       ->groupEnd()
                       ->get()->getRowArray();
                       
        $tahun_ajaran = $ta_aktif ? $ta_aktif['tahun'] : 'Belum Diset';
        $semester     = $ta_aktif ? $ta_aktif['semester'] : 'Ganjil';

        // 2. HITUNG STATISTIK DINAMIS UNTUK CARDS (DISINKRONKAN DENGAN TABEL)
        
        // Card 2: Total Target (Menghitung SEMUA target aktif, sama seperti di tabel)
        $totalTarget = $db->table('target_tahfidz')
                          ->where('status', 'Aktif')
                          ->countAllResults();

        // Card 3: Tingkat Aktif (Menghitung SEMUA tingkat yang punya target aktif)
        $tingkatRows = $db->table('target_tahfidz')
                          ->select('tingkat')
                          ->where('status', 'Aktif')
                          ->distinct()
                          ->get()->getResultArray();
        
        $tingkatAktifCount = count($tingkatRows);
        
        // Mengurutkan tingkat agar rapi (VII, VIII, IX)
        $arrTingkat = array_column($tingkatRows, 'tingkat');
        sort($arrTingkat);
        $listTingkatStr = !empty($arrTingkat) ? implode(', ', $arrTingkat) : 'Belum Ada';

        // Card 4: Persentase Partisipasi Siswa (Filter Tahun Aktif)
        $id_ta_aktif = $ta_aktif ? $ta_aktif['id'] : 0;
        $totalSiswaAktif = $db->table('siswa s')
                              ->join('rombel r', 'r.id = s.rombel_id')
                              ->where(['s.status_siswa' => 'Aktif', 'r.id_tahun_ajaran' => $id_ta_aktif])
                              ->countAllResults();
        
        $siswaMenyetor = 0;
        if ($db->tableExists('setoran_tahfidz') && $totalSiswaAktif > 0) {
            $siswaMenyetor = $db->table('setoran_tahfidz st')
                               ->join('siswa s', 's.id = st.siswa_id')
                               ->join('rombel r', 'r.id = s.rombel_id')
                               ->where('r.id_tahun_ajaran', $id_ta_aktif)
                               ->select('st.siswa_id')
                               ->distinct()
                               ->countAllResults();
        }
        $persenPartisipasi = ($totalSiswaAktif > 0) ? round(($siswaMenyetor / $totalSiswaAktif) * 100) : 0;

        $stats = [
            'total_target'       => $totalTarget,
            'jml_tingkat'        => $tingkatAktifCount,
            'list_tingkat'       => $listTingkatStr,
            'persen_partisipasi' => $persenPartisipasi
        ];

        // 3. AMBIL DATA TARGET UNTUK TABEL DENGAN JOIN (100% ANTI ERROR)
        $targetsData = $db->table('target_tahfidz t')
                          ->select('t.*, j.nama_juz, sm.nama_surah as surah_mulai, ss.nama_surah as surah_sampai')
                          ->join('ref_juz j', 'j.id = t.juz_id', 'left')
                          ->join('ref_surah sm', 'sm.id = t.surah_mulai_id', 'left')
                          ->join('ref_surah ss', 'ss.id = t.surah_sampai_id', 'left')
                          ->orderBy('t.tingkat', 'ASC')
                          ->orderBy('t.semester', 'ASC')
                          ->get()
                          ->getResultArray();

        // 4. KIRIM KE VIEW
        $data = [
            'user'         => 'Admin',
            'navigations'  => $this->getSidebarMenu(),
            'color'        => $this->getColor(),
            'targets'      => $targetsData,
            'ref_juz'      => $juzModel->findAll(),
            'ref_surah'    => $surahModel->orderBy('no_surah', 'ASC')->findAll(),
            'tahun_ajaran' => $tahun_ajaran,
            'semester'     => $semester,
            'stats'        => $stats
        ];

        return view('admin/target-tahfidz', $data);
    }

    // ==========================================
    // SISA KODE FUNGSI LAINNYA TETAP SAMA
    // ==========================================
    public function store()
    {
        if (!$this->request->isAJAX()) return $this->response->setStatusCode(404);

        $rules = [
            'tingkat'         => 'required',
            'semester'        => 'required',
            'juz_id'          => 'required|numeric',
            'surah_mulai_id'  => 'required|numeric',
            'surah_sampai_id' => 'required|numeric',
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'status' => 'error',
                'errors' => $this->validator->getErrors(),
                'message' => 'Lengkapi data wajib!'
            ]);
        }

        $model = new TargetTahfidzModel();
        $exists = $model->where('tingkat', $this->request->getPost('tingkat'))
                        ->where('semester', $this->request->getPost('semester'))
                        ->where('juz_id', $this->request->getPost('juz_id'))
                        ->first();

        if ($exists) {
             return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Target untuk Tingkat dan Semester ini pada Juz tersebut sudah ada!'
            ]);
        }

        $data = [
            'tingkat'         => $this->request->getPost('tingkat'),
            'semester'        => $this->request->getPost('semester'),
            'juz_id'          => $this->request->getPost('juz_id'),
            'surah_mulai_id'  => $this->request->getPost('surah_mulai_id'),
            'surah_sampai_id' => $this->request->getPost('surah_sampai_id'),
            'minimal_hafalan' => 100, 
            'status'          => 'Aktif'
        ];

        if ($model->save($data)) {
            return $this->response->setJSON(['status' => 'success', 'message' => 'Target Tahfidz berhasil disimpan!']);
        } else {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Gagal menyimpan ke database.']);
        }
    }

    public function update()
    {
        if (!$this->request->isAJAX()) return $this->response->setStatusCode(404);

        $id = $this->request->getPost('id');
        if (!$id) return $this->response->setJSON(['status' => 'error', 'message' => 'ID tidak ditemukan.']);

        if (!$this->validate(['juz_id' => 'required|numeric'])) {
            return $this->response->setJSON([
                'status' => 'error',
                'errors' => $this->validator->getErrors()
            ]);
        }

        $model = new TargetTahfidzModel();
        $data = [
            'id'              => $id,
            'juz_id'          => $this->request->getPost('juz_id'),
            'surah_mulai_id'  => $this->request->getPost('surah_mulai_id'),
            'surah_sampai_id' => $this->request->getPost('surah_sampai_id'),
        ];

        if ($model->save($data)) {
            return $this->response->setJSON(['status' => 'success', 'message' => 'Target berhasil diperbarui!']);
        } else {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Gagal memperbarui data.']);
        } 
    }

    public function get_surah()
    {
        if (!$this->request->isAJAX()) return $this->response->setStatusCode(404);

        $juz_id = $this->request->getGet('juz_id'); 
        $surahModel = new RefSurahModel();
        $juzModel = new RefJuzModel();

        $juz = $juzModel->find($juz_id);
        if (!$juz) return $this->response->setJSON(['status' => 'error', 'data' => []]);

        preg_match('/\d+/', $juz['nama_juz'], $matches);
        $angka_juz = isset($matches[0]) ? (int)$matches[0] : 0;

        $petaJuz = [
            1 => [1, 2],       2 => [2, 2],       3 => [2, 3],       4 => [3, 4],       5 => [4, 4],
            6 => [4, 5],       7 => [5, 6],       8 => [6, 7],       9 => [7, 8],      10 => [8, 9],
            11 => [9, 11],    12 => [11, 12],    13 => [12, 14],    14 => [15, 16],    15 => [17, 18],
            16 => [18, 20],   17 => [21, 22],    18 => [23, 25],    19 => [25, 27],    20 => [27, 29],
            21 => [29, 33],   22 => [33, 36],    23 => [36, 39],    24 => [39, 41],    25 => [41, 45],
            26 => [46, 51],   27 => [51, 57],    28 => [58, 66],    29 => [67, 77],    30 => [78, 114]
        ];

        if ($angka_juz > 0 && isset($petaJuz[$angka_juz])) {
            $surahs = $surahModel->where('no_surah >=', $petaJuz[$angka_juz][0])
                                 ->where('no_surah <=', $petaJuz[$angka_juz][1])
                                 ->orderBy('no_surah', 'ASC')
                                 ->findAll();
        } else {
            $surahs = $surahModel->orderBy('no_surah', 'ASC')->findAll();
        }

        return $this->response->setJSON(['status' => 'success', 'data' => $surahs]);
    }

    public function downloadTemplate()
    {
        if (ob_get_length()) ob_clean();
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Template Import');
        
        $headers = [
            'Tingkat (VII/VIII/IX)', 'Semester (Ganjil/Genap)', 'ID Juz (Lihat Sheet 2)', 
            'ID Surah Mulai (Lihat Sheet 3)', 'ID Surah Sampai', 'Minimal Hafalan (Contoh: 100)'
        ];

        $col = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($col . '1', $header);
            $sheet->getStyle($col . '1')->getFont()->setBold(true);
            $sheet->getStyle($col . '1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FFEFEFEF');
            $sheet->getColumnDimension($col)->setAutoSize(true);
            $col++;
        }

        $sheet->setCellValue('A2', 'VII');
        $sheet->setCellValue('B2', 'Ganjil');
        $sheet->setCellValue('C2', '30');
        $sheet->setCellValue('D2', '78');
        $sheet->setCellValue('E2', '114');
        $sheet->setCellValue('F2', '100');

        $juzModel = new RefJuzModel();
        $sheetJuz = $spreadsheet->createSheet();
        $sheetJuz->setTitle('Ref Juz');
        $sheetJuz->setCellValue('A1', 'ID JUZ')->setCellValue('B1', 'NAMA JUZ');
        $dataJuz = $juzModel->select('id, nama_juz')->findAll();
        $r = 2; foreach($dataJuz as $j) { $sheetJuz->setCellValue("A$r", $j['id'])->setCellValue("B$r", $j['nama_juz']); $r++; }

        $surahModel = new RefSurahModel();
        $sheetSurah = $spreadsheet->createSheet();
        $sheetSurah->setTitle('Ref Surah');
        $sheetSurah->setCellValue('A1', 'ID SURAH')->setCellValue('B1', 'NAMA SURAH');
        $dataSurah = $surahModel->select('id, nama_surah')->findAll();
        $r = 2; foreach($dataSurah as $s) { $sheetSurah->setCellValue("A$r", $s['id'])->setCellValue("B$r", $s['nama_surah']); $r++; }

        $spreadsheet->setActiveSheetIndex(0);
        $writer = new Xlsx($spreadsheet);
        $filename = 'Template_Import_Target.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
        exit;
    }

    public function import()
    {
        ini_set('memory_limit', '1024M');
        if (ob_get_length()) ob_clean();

        if (empty($_FILES)) return $this->response->setJSON(['status' => 'error', 'message' => 'File tidak ditemukan.']);
        $file = $this->request->getFile('file_excel');
        if (!$file || !$file->isValid()) return $this->response->setJSON(['status' => 'error', 'message' => 'File corrupt.']);
        
        $ext = strtolower($file->getClientExtension());
        if (!in_array($ext, ['xls', 'xlsx'])) return $this->response->setJSON(['status' => 'error', 'message' => 'Harus Excel (.xlsx)']);

        $db = \Config\Database::connect();
        $db->transBegin();

        try {
            $spreadsheet = IOFactory::load($file->getTempName());
            $sheet = $spreadsheet->getSheet(0)->toArray(null, true, true, true); 
            
            $model = new TargetTahfidzModel();
            $countInsert = 0; $countError = 0;

            foreach ($sheet as $idx => $row) {
                if ($idx == 1) continue; 
                
                $tingkat  = trim($row['A'] ?? '');
                $semester = trim($row['B'] ?? '');
                $juzId    = trim($row['C'] ?? '');
                $sMulai   = trim($row['D'] ?? '');
                $sSampai  = trim($row['E'] ?? '');
                $min      = trim($row['F'] ?? '100');

                if(empty($tingkat) || empty($semester) || empty($juzId) || empty($sMulai) || empty($sSampai)) continue;

                $exists = $model->where(['tingkat' => $tingkat, 'semester' => $semester, 'juz_id' => $juzId])->first();
                if($exists) {
                    $countError++;
                    continue;
                }

                $model->insert([
                    'tingkat'         => $tingkat,
                    'semester'        => $semester,
                    'juz_id'          => $juzId,
                    'surah_mulai_id'  => $sMulai,
                    'surah_sampai_id' => $sSampai,
                    'minimal_hafalan' => $min,
                    'status'          => 'Aktif'
                ]);
                $countInsert++;
            }

            if ($db->transStatus() === false) {
                $db->transRollback();
                return $this->response->setJSON(['status' => 'error', 'message' => 'Gagal memasukkan data ke DB.']);
            }

            $db->transCommit();
            return $this->response->setJSON(['status' => 'success', 'message' => "Import Selesai! $countInsert target ditambahkan. $countError dilewati (duplikat)."]);

        } catch (\Throwable $e) {
            if (isset($db)) $db->transRollback();
            return $this->response->setJSON(['status' => 'error', 'message' => 'System Error: ' . $e->getMessage()]);
        }
    }

    public function getRiwayat()
    {
        $model = new TargetTahfidzModel();
        $history = $model->orderBy('updated_at', 'DESC')->findAll(10); 

        $data = [];
        foreach($history as $h) {
            $data[] = [
                'tanggal' => date('d M Y H:i', strtotime($h['updated_at'])),
                'aksi' => $h['created_at'] == $h['updated_at'] ? 'Membuat Target Baru' : 'Memperbarui Target',
                'detail' => "Tingkat {$h['tingkat']} - Sem {$h['semester']} - Juz {$h['juz_id']}",
                'status' => $h['status']
            ];
        }

        return $this->response->setJSON(['status' => 'success', 'data' => $data]);
    }
}