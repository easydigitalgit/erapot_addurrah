<?php

namespace App\Controllers\GuruMapel;

use App\Controllers\GuruMapelBaseController;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

class NilaiKolektifController extends GuruMapelBaseController
{
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    public function index()
    {
        $this->data['title'] = 'Upload Nilai Kolektif';
        $this->data['color'] = $this->getColor();

        $userId = session()->get('id');

        // 🚀 TANGKAP FILTER TAHUN AJARAN DARI URL
        $idTaGet = $this->request->getGet('ta');
        
        $this->data['tahun_ajaran'] = $this->db->table('tahun_ajaran')->orderBy('id', 'DESC')->get()->getResultArray();
        $this->data['fTA'] = $this->db->fieldExists('tahun', 'tahun_ajaran') ? 'tahun' : 'tahun_ajaran';

        if ($idTaGet) {
            $ta_terpilih = $this->db->table('tahun_ajaran')->where('id', $idTaGet)->get()->getRowArray();
        } else {
            $ta_terpilih = $this->db->table('tahun_ajaran')->where('status', 'Aktif')->get()->getRowArray();
        }
        $idTaAktif = $ta_terpilih ? $ta_terpilih['id'] : 0;
        $this->data['id_ta_aktif'] = $idTaAktif; // Lempar ke View

        $dataGuru = $this->db->table('guru_tendik')
            ->select('id')
            ->where('user_id', $userId)
            ->get()
            ->getRowArray();

        $guruId = $dataGuru ? $dataGuru['id'] : 0;

        // 🚀 FILTER ROMBEL & MAPEL BERDASARKAN TA YANG DIPILIH
        $fTA_GM = $this->db->fieldExists('tahun_ajaran_id', 'guru_mapel') ? 'tahun_ajaran_id' : 'tahun_ajaran';

        $this->data['rombels'] = $this->db->table('guru_mapel')
            ->select('rombel.id, rombel.tingkat, rombel.nama_rombel')
            ->join('rombel', 'rombel.id = guru_mapel.rombel_id')
            ->where('guru_mapel.guru_id', $guruId)
            ->where('guru_mapel.status', 'active')
            ->where('guru_mapel.' . $fTA_GM, $idTaAktif) // <-- FILTER TA
            ->groupBy('rombel.id')
            ->orderBy('rombel.tingkat', 'ASC')
            ->orderBy('rombel.nama_rombel', 'ASC')
            ->get()->getResultArray();

        $this->data['mapels'] = $this->db->table('guru_mapel')
            ->select('mata_pelajaran.id, mata_pelajaran.nama_mapel')
            ->join('mata_pelajaran', 'mata_pelajaran.id = guru_mapel.mapel_id')
            ->where('guru_mapel.guru_id', $guruId)
            ->where('guru_mapel.status', 'active')
            ->where('guru_mapel.' . $fTA_GM, $idTaAktif) // <-- FILTER TA
            ->groupBy('mata_pelajaran.id')
            ->orderBy('mata_pelajaran.nama_mapel', 'ASC')
            ->get()->getResultArray();

        return view('GuruMapel/nilai-kolektif', $this->data);
    }

    public function downloadTemplate()
    {
        if (ob_get_length()) ob_clean();

        $ta_id     = $this->request->getGet('ta');
        $rombel_id = $this->request->getGet('kelas');
        $mapel_id  = $this->request->getGet('mapel');
        $jenis     = $this->request->getGet('jenis');

        if (!$ta_id || !$rombel_id || !$mapel_id || !$jenis) {
            return redirect()->back()->with('error', 'Parameter tidak lengkap.');
        }

        $taData = $this->db->table('tahun_ajaran')->where('id', $ta_id)->get()->getRowArray();
        $semesterAktif = $taData ? $taData['semester'] : 'Ganjil';
        $kategoriDB = (stripos($jenis, 'tengah') !== false) ? 'Tengah' : 'Akhir';

        $rombel = $this->db->table('rombel')->where('id', $rombel_id)->get()->getRowArray();
        $mapel  = $this->db->table('mata_pelajaran')->where('id', $mapel_id)->get()->getRowArray();

        // 🚀 MENGGUNAKAN MESIN WAKTU UNTUK DOWNLOAD TEMPLATE EXCEL
        $siswa = $this->db->table('anggota_rombel ar')
            ->select('siswa.id, siswa.nis, siswa.nama_lengkap')
            ->join('siswa', 'siswa.id = ar.siswa_id')
            ->where('ar.rombel_id', $rombel_id)
            ->where('ar.tahun_ajaran_id', $ta_id)
            ->where('ar.semester', $semesterAktif)
            ->where('siswa.status_siswa', 'Aktif')
            ->orderBy('siswa.nama_lengkap', 'ASC')
            ->get()->getResultArray();

        // MENGHITUNG JUMLAH LM SECARA DINAMIS
        $jumlah_lm = 1;

        if ($this->db->tableExists('master_lm')) {
            $tingkatClean = 0;
            if ($rombel) {
                $tingkatClean = preg_replace('/[^0-9]/', '', (string)$rombel['tingkat']);
                if (empty($tingkatClean)) {
                    $romToNum = ['VII' => 7, 'VIII' => 8, 'IX' => 9, 'X' => 10, 'XI' => 11, 'XII' => 12];
                    $tingkatClean = $romToNum[strtoupper(trim($rombel['tingkat']))] ?? 0;
                }
            }

            if (!empty($tingkatClean)) {
                $bLm = $this->db->table('master_lm')
                    ->where('mapel_id', $mapel_id)
                    ->where('tingkat', $tingkatClean)
                    ->where('semester', $semesterAktif);

                if ($this->db->fieldExists('tahun_ajaran_id', 'master_lm')) {
                    $bLm->where('tahun_ajaran_id', $ta_id);
                }

                if ($this->db->fieldExists('kategori', 'master_lm')) {
                    $bLm->where('kategori', $kategoriDB);
                }
                if ($this->db->fieldExists('status', 'master_lm')) {
                    $bLm->where('status', 'Aktif');
                }

                $countLm = $bLm->countAllResults();
                if ($countLm > 0) {
                    $jumlah_lm = $countLm;
                }
            }
        }

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'DAFTAR KOLEKTIF NILAI KURIKULUM MERDEKA');
        $sheet->setCellValue('A2', 'SMP SWASTA IT AD DURRAH');
        $sheet->setCellValue('A4', 'KELAS')->setCellValue('C4', ': ' . ($rombel['nama_rombel'] ?? '-'));
        $sheet->setCellValue('A5', 'MATA PELAJARAN')->setCellValue('C5', ': ' . ($mapel['nama_mapel'] ?? '-'));
        $sheet->setCellValue('A6', 'JENIS FORMAT')->setCellValue('C6', ': ' . strtoupper($jenis) . ' SEMESTER');

        $headerStyle = [
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => '1E293B']],
            'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER, 'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER],
            'borders' => ['allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN]]
        ];

        $sheet->mergeCells('A8:A9')->setCellValue('A8', 'NO');
        $sheet->mergeCells('B8:B9')->setCellValue('B8', 'NIS');
        $sheet->mergeCells('C8:C9')->setCellValue('C8', 'NAMA LENGKAP');

        $colIndex = 4;

        // 1. FORMATIF NH
        $startNH = $colIndex;
        for ($i = 1; $i <= $jumlah_lm; $i++) {
            $colLtr = Coordinate::stringFromColumnIndex($colIndex);
            $sheet->setCellValue($colLtr . '9', "NH $i");
            $sheet->setCellValue($colLtr . '7', "NH_$i");
            $colIndex++;
        }
        $startLtr = Coordinate::stringFromColumnIndex($startNH);
        $endLtr = Coordinate::stringFromColumnIndex($colIndex - 1);
        $sheet->mergeCells("{$startLtr}8:{$endLtr}8")->setCellValue("{$startLtr}8", 'FORMATIF (Nilai Harian)');

        // 2. FORMATIF UH
        $startUH = $colIndex;
        for ($i = 1; $i <= $jumlah_lm; $i++) {
            $colLtr = Coordinate::stringFromColumnIndex($colIndex);
            $sheet->setCellValue($colLtr . '9', "UH $i");
            $sheet->setCellValue($colLtr . '7', "UH_$i");
            $colIndex++;
        }
        $startLtr = Coordinate::stringFromColumnIndex($startUH);
        $endLtr = Coordinate::stringFromColumnIndex($colIndex - 1);
        $sheet->mergeCells("{$startLtr}8:{$endLtr}8")->setCellValue("{$startLtr}8", 'FORMATIF (Ulangan Harian)');

        // 3. SUMATIF
        $startSumatif = $colIndex;

        if ($jenis == 'tengah') {
            $colLtr = Coordinate::stringFromColumnIndex($colIndex);
            $sheet->setCellValue($colLtr . '9', 'PTS/STS');
            $sheet->setCellValue($colLtr . '7', 'pts');
            $colIndex++;
        } else {
            $colLtr = Coordinate::stringFromColumnIndex($colIndex);
            $sheet->setCellValue($colLtr . '9', 'PAS');
            $sheet->setCellValue($colLtr . '7', 'pas');
            $colIndex++;

            $colLtr = Coordinate::stringFromColumnIndex($colIndex);
            $sheet->setCellValue($colLtr . '9', 'SAS');
            $sheet->setCellValue($colLtr . '7', 'sas');
            $colIndex++;
        }

        $startLtr = Coordinate::stringFromColumnIndex($startSumatif);
        $endLtr = Coordinate::stringFromColumnIndex($colIndex - 1);
        $sheet->mergeCells("{$startLtr}8:{$endLtr}8")->setCellValue("{$startLtr}8", 'SUMATIF');

        $lastColLtr = Coordinate::stringFromColumnIndex($colIndex - 1);
        $sheet->getStyle("A8:{$lastColLtr}9")->applyFromArray($headerStyle);
        $sheet->getRowDimension(7)->setVisible(false);

        $row = 10;
        foreach ($siswa as $idx => $s) {
            $sheet->setCellValue('A' . $row, $idx + 1);
            $sheet->setCellValueExplicit('B' . $row, $s['nis'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet->setCellValue('C' . $row, $s['nama_lengkap']);
            $row++;
        }

        foreach (range('A', $lastColLtr) as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $filename = 'Template_Kolektif_' . ucfirst($jenis) . '_' . str_replace(' ', '_', $rombel['nama_rombel']) . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit();
    }

    public function importExcel()
    {
        if (ob_get_length()) ob_clean();
        try {
            $file = $this->request->getFile('file_excel');

            if (!$file->isValid() || $file->getExtension() !== 'xlsx') {
                return $this->response->setJSON(['status' => 'error', 'message' => 'File tidak valid. Harap upload format .xlsx']);
            }

            // KUNCI PERBAIKAN: Ambil Tahun Ajaran dari JS FormData, BUKAN maksa ke status 'Aktif'
            $ta_id_post = $this->request->getPost('ta_id');
            if (!empty($ta_id_post)) {
                $ta_aktif = $this->db->table('tahun_ajaran')->where('id', $ta_id_post)->get()->getRowArray();
            } else {
                $ta_aktif = $this->db->table('tahun_ajaran')->where('status', 'Aktif')->get()->getRowArray();
            }

            if (!$ta_aktif) return $this->response->setJSON(['status' => 'error', 'message' => 'Data Tahun Ajaran tidak ditemukan.']);

            $tahun_ajaran_id = $ta_aktif['id'];
            $semester = $ta_aktif['semester'];

            $userId = session()->get('id');
            $dataGuru = $this->db->table('guru_tendik')->select('id')->where('user_id', $userId)->get()->getRowArray();
            $guru_id = $dataGuru ? $dataGuru['id'] : $userId;

            $spreadsheet = IOFactory::load($file->getTempName());
            $sheet = $spreadsheet->getActiveSheet();
            $highestRow = $sheet->getHighestDataRow();
            $highestColumnIndex = Coordinate::columnIndexFromString($sheet->getHighestDataColumn());

            $strKelas = trim(str_replace(':', '', (string)$sheet->getCell('C4')->getValue()));
            $strMapel = trim(str_replace(':', '', (string)$sheet->getCell('C5')->getValue()));
            $strJenis = strtolower(trim(str_replace(':', '', (string)$sheet->getCell('C6')->getValue())));

            $rombel = $this->db->table('rombel')->like('nama_rombel', $strKelas)->get()->getRowArray();
            if (!$rombel) return $this->response->setJSON(['status' => 'error', 'message' => 'Gagal membaca Kelas dari file Excel.']);
            $rombel_id = $rombel['id'];

            $mapel = $this->db->table('mata_pelajaran')->like('nama_mapel', $strMapel)->get()->getRowArray();
            if (!$mapel) return $this->response->setJSON(['status' => 'error', 'message' => 'Gagal membaca Mata Pelajaran dari Excel.']);
            $mapel_id = $mapel['id'];

            $kategori_semester = (strpos($strJenis, 'tengah') !== false) ? 'Tengah Semester' : 'Akhir Semester';
            $kategori_db       = (strpos($strJenis, 'tengah') !== false) ? 'Tengah' : 'Akhir';

            $colMap = [];
            for ($c = 4; $c <= $highestColumnIndex; $c++) {
                $colLtr = Coordinate::stringFromColumnIndex($c);
                $kodeRahasia = trim((string)$sheet->getCell($colLtr . '7')->getValue());
                if (!empty($kodeRahasia)) {
                    $colMap[$colLtr] = $kodeRahasia;
                }
            }

            if (empty($colMap)) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Format template Excel rusak. Pastikan Anda tidak menghapus baris atas!']);
            }

            $getSafeValue = function ($colLtr, $rowNum) use ($sheet) {
                try {
                    $val = $sheet->getCell($colLtr . $rowNum)->getCalculatedValue();
                    if (is_string($val)) $val = str_replace(',', '.', $val);
                    return (is_numeric($val) && trim((string)$val) !== '') ? (float)$val : null;
                } catch (\Exception $e) {
                    return null;
                }
            };

            $this->db->transStart();
            $processedCount = 0;

            for ($row = 10; $row <= $highestRow; $row++) {
                $nis = trim((string)$sheet->getCell('B' . $row)->getValue());
                if (empty($nis)) continue;

                $siswa = $this->db->table('siswa')->where('nis', $nis)->where('rombel_id', $rombel_id)->get()->getRowArray();
                if (!$siswa) continue;
                $siswa_id = $siswa['id'];

                $hasUpdate = false;

                foreach ($colMap as $colLetter => $kode) {
                    $val = $getSafeValue($colLetter, $row);
                    if ($val === null) continue;

                    if (strpos($kode, 'NH_') === 0) {
                        $pert = str_replace('NH_', '', $kode);
                        $this->_saveFormatif($siswa_id, $mapel_id, $rombel_id, $guru_id, 'Tugas', $pert, $val, $tahun_ajaran_id, $semester, $kategori_db);
                        $hasUpdate = true;
                    } elseif (strpos($kode, 'UH_') === 0) {
                        $pert = str_replace('UH_', '', $kode);
                        $this->_saveFormatif($siswa_id, $mapel_id, $rombel_id, $guru_id, 'Ulangan', $pert, $val, $tahun_ajaran_id, $semester, $kategori_db);
                        $hasUpdate = true;
                    } elseif ($kode === 'pts' || $kode === 'pas' || $kode === 'sas') {
                        $this->_saveSumatif($siswa_id, $mapel_id, $tahun_ajaran_id, $kategori_semester, $kode, $val);
                        $hasUpdate = true;
                    }
                }

                if ($hasUpdate) $processedCount++;
            }

            $this->db->transComplete();

            if ($this->db->transStatus() === false) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'DATABASE ERROR: Terjadi kesalahan saat menyimpan data.']);
            }

            if ($processedCount === 0) {
                return $this->response->setJSON(['status' => 'warning', 'message' => "Tidak terdeteksi angka nilai baru. Pastikan sel Excel tidak kosong."]);
            }

            return $this->response->setJSON(['status' => 'success', 'message' => "Luar biasa! $processedCount baris data siswa berhasil diimpor!"]);
        } catch (\Exception $e) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'System Error: ' . $e->getMessage()]);
        }
    }

    private function _saveFormatif($siswa_id, $mapel_id, $rombel_id, $guru_id, $jenis_penilaian, $pertemuan, $nilai, $ta_id, $sem, $kategoriDB)
    {
        $angka = (int)$nilai;
        if ($angka > 100) $angka = 100;
        if ($angka < 0) $angka = 0;

        $kkm = 75;
        $predikat = '-';
        if ($angka >= 90) $predikat = 'A';
        elseif ($angka >= 80) $predikat = 'B';
        elseif ($angka >= $kkm) $predikat = 'C';
        else $predikat = 'D';

        static $lm_cache_global = [];
        $cache_key = $mapel_id . '_' . $rombel_id . '_' . $ta_id . '_' . $sem . '_' . $kategoriDB;

        if (!array_key_exists($cache_key, $lm_cache_global)) {
            $rombel = $this->db->table('rombel')->where('id', $rombel_id)->get()->getRowArray();
            $tingkat_asli = $rombel ? trim((string)$rombel['tingkat']) : '';

            $tingkat_search = [$tingkat_asli, (int)$tingkat_asli];
            if (in_array(strtoupper($tingkat_asli), ['7', 'VII'])) $tingkat_search = ['7', 'VII', 'vii', 7];
            elseif (in_array(strtoupper($tingkat_asli), ['8', 'VIII'])) $tingkat_search = ['8', 'VIII', 'viii', 8];
            elseif (in_array(strtoupper($tingkat_asli), ['9', 'IX'])) $tingkat_search = ['9', 'IX', 'ix', 9];

            $lmQuery = $this->db->table('master_lm')
                ->where('mapel_id', $mapel_id)
                ->whereIn('tingkat', $tingkat_search)
                ->where('semester', $sem); // Semester Master

            // Master LM biasanya tidak dipisah per Tahun Ajaran, bisa nullable/0
            if ($this->db->fieldExists('tahun_ajaran_id', 'master_lm')) {
                $lmQuery->groupStart()
                    ->where('tahun_ajaran_id', $ta_id)
                    ->orWhere('tahun_ajaran_id', 0)
                    ->orWhere('tahun_ajaran_id', null)
                    ->groupEnd();
            }

            if ($this->db->fieldExists('kategori', 'master_lm')) {
                $lmQuery->groupStart()
                    ->where('kategori', $kategoriDB)
                    ->orWhere('kategori', '')
                    ->orWhere('kategori', null)
                    ->groupEnd();
            }

            $allLM = $lmQuery->orderBy('id', 'ASC')->get()->getResultArray();

            $lm_mapped = [];
            foreach ($allLM as $lm) {
                $angka_lm = preg_replace('/[^0-9]/', '', $lm['kode_lm']);
                if (!empty($angka_lm)) {
                    $lm_mapped[(int)$angka_lm] = $lm;
                }
            }
            $lm_cache_global[$cache_key] = $lm_mapped;
        }

        $lmData = $lm_cache_global[$cache_key][(int)$pertemuan] ?? null;
        $catatan = "";

        if ($lmData && $predikat !== '-') {
            $key = 'deskripsi_' . strtolower($predikat);
            if (isset($lmData[$key]) && trim((string)$lmData[$key]) !== '') {
                $catatan = trim((string)$lmData[$key]);
            } elseif (isset($lmData['deskripsi_lm']) && trim((string)$lmData['deskripsi_lm']) !== '') {
                $materi = trim((string)$lmData['deskripsi_lm']);
                if ($predikat === 'A') $catatan = "Sangat baik memahami " . $materi;
                elseif ($predikat === 'B') $catatan = "Baik memahami " . $materi;
                elseif ($predikat === 'C') $catatan = "Cukup memahami " . $materi;
                else $catatan = "Perlu bimbingan memahami " . $materi;
            }
        }

        // KUNCI PERBAIKAN: RESET QUERY BUILDER
        $dbTable = $this->db->table('nilai_formatif');
        $qCek = $dbTable->where([
            'siswa_id' => $siswa_id,
            'mapel_id' => $mapel_id,
            'pertemuan' => $pertemuan,
            'jenis_penilaian' => $jenis_penilaian,
            'tahun_ajaran_id' => $ta_id,
            'semester' => $sem
        ]);

        if ($this->db->fieldExists('kategori', 'nilai_formatif')) {
            $qCek->groupStart()
                ->where('kategori', $kategoriDB)
                ->orWhere('kategori', '')
                ->orWhere('kategori', null)
                ->groupEnd();
        }

        $exist = $qCek->get()->getRowArray();

        $dataSimpan = [
            'nilai_angka'     => $nilai,
            'predikat'        => $predikat,
            'catatan'         => $catatan,
            'tahun_ajaran_id' => $ta_id,
            'semester'        => $sem
        ];

        if ($this->db->fieldExists('kategori', 'nilai_formatif')) {
            $dataSimpan['kategori'] = $kategoriDB;
        }

        if ($exist) {
            $this->db->table('nilai_formatif')->where('id', $exist['id'])->update($dataSimpan);
        } else {
            $dataSimpan['siswa_id']          = $siswa_id;
            $dataSimpan['mapel_id']          = $mapel_id;
            $dataSimpan['rombel_id']         = $rombel_id;
            $dataSimpan['guru_id']           = $guru_id;
            $dataSimpan['jenis_penilaian']   = $jenis_penilaian;
            $dataSimpan['pertemuan']         = $pertemuan;
            $dataSimpan['tanggal_penilaian'] = date('Y-m-d');
            $dataSimpan['status_simpan']     = 'draft';

            $this->db->table('nilai_formatif')->insert($dataSimpan);
        }
    }

    private function _saveSumatif($siswa_id, $mapel_id, $ta_id, $kategori_semester, $jenis_sumatif, $nilai)
    {
        $dbTable = $this->db->table('nilai_sumatif');
        $exist = $dbTable->where([
            'siswa_id' => $siswa_id,
            'mapel_id' => $mapel_id,
            'jenis_sumatif' => $jenis_sumatif,
            'tahun_ajaran_id' => $ta_id // Mencegah replace data tahun lalu
        ])->get()->getRowArray();

        if ($exist) {
            $this->db->table('nilai_sumatif')->where('id', $exist['id'])->update([
                'nilai'           => $nilai,
                'tahun_ajaran_id' => $ta_id,
                'kategori'        => $kategori_semester
            ]);
        } else {
            $this->db->table('nilai_sumatif')->insert([
                'siswa_id'        => $siswa_id,
                'mapel_id'        => $mapel_id,
                'tahun_ajaran_id' => $ta_id,
                'kategori'        => $kategori_semester,
                'jenis_sumatif'   => $jenis_sumatif,
                'nilai'           => $nilai
            ]);
        }
    }
}
