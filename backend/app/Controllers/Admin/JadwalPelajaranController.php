<?php

namespace App\Controllers\Admin;

use App\Controllers\AdminBaseController;
use App\Models\Admin\JadwalModel;
use App\Models\Admin\MataPelajaranModel;
use App\Models\Admin\GuruTendikModel;
use App\Models\Admin\RombelModel;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class JadwalPelajaranController extends AdminBaseController
{
    public function index()
    {
        $mapelModel  = new MataPelajaranModel();
        $guruModel   = new GuruTendikModel();
        $jadwalModel = new JadwalModel();
        $rombelModel = new RombelModel();

        $dataMapel  = $mapelModel->orderBy('nama_mapel', 'ASC')->findAll();
        $dataGuru   = $guruModel->orderBy('nama_lengkap', 'ASC')->findAll();
        $dataRombel = $rombelModel->orderBy('tingkat', 'ASC')->orderBy('nama_rombel', 'ASC')->findAll();

        $db = \Config\Database::connect();

        $ta_list = $db->table('tahun_ajaran')->orderBy('id', 'DESC')->get()->getResultArray();
        $ta_aktif = $db->table('tahun_ajaran')->where('status', 'Aktif')->get()->getRowArray();

        // 🚀 TANGKAP FILTER DARI URL & FILTER SEMUA DATA
        $getTa = $this->request->getGet('ta');
        $getSemester = $this->request->getGet('semester');

        if ($getTa && $getSemester) {
            $ta_terpilih = $db->table('tahun_ajaran')->where('id', $getTa)->where('semester', $getSemester)->get()->getRowArray();
        } else {
            $ta_terpilih = $ta_aktif;
        }
        
        $idTaFilter = $ta_terpilih ? $ta_terpilih['id'] : ($ta_aktif['id'] ?? 0);
        $semesterFilter = $ta_terpilih ? $ta_terpilih['semester'] : ($ta_aktif['semester'] ?? 'Ganjil');

        $rawJadwal = $jadwalModel->select('jadwal_pelajaran.*, mata_pelajaran.nama_mapel, guru_tendik.nama_lengkap')
            ->join('mata_pelajaran', 'mata_pelajaran.id = jadwal_pelajaran.mapel_id', 'left')
            ->join('guru_tendik', 'guru_tendik.id = jadwal_pelajaran.guru_id', 'left')
            ->where('id_tahun_ajaran', $idTaFilter)
            ->where('semester', $semesterFilter)
            ->findAll();

        // 🚀 FILTER ROMBEL AGAR HANYA MUNCUL KELAS DI TAHUN TERSEBUT
        $dataRombel = $rombelModel->where('id_tahun_ajaran', $idTaFilter)->orderBy('tingkat', 'ASC')->orderBy('nama_rombel', 'ASC')->findAll();

        $finalJadwal = [];
        foreach ($rawJadwal as $row) {
            $row['jam_ke'] = $row['jam_ke'] ?? $this->timeToPeriod($row['jam_mulai']);
            $finalJadwal[] = $row;
        }

        $data = [
            'user'        => 'Admin',
            'navigations' => $this->getSidebarMenu(),
            'data_mapel'  => $dataMapel,
            'data_guru'   => $dataGuru,
            'rombels'     => $dataRombel,
            'list_jadwal' => $finalJadwal,
            'ta_list'     => $ta_list,
            'ta_aktif'    => $ta_aktif,
            'ta_filter_id' => $idTaFilter,
            'ta_filter_semester' => $semesterFilter,
            'color'       => $this->getColor()
        ];

        return view('admin/jadwal-pelajaran', $data);
    }

    private function timeToPeriod($time)
    {
        $map = [
            '07:30:00' => 1,
            '07:30' => 1,
            '08:10:00' => 2,
            '08:10' => 2,
            '08:50:00' => 3,
            '08:50' => 3,
            '09:45:00' => 4,
            '09:45' => 4,
            '10:25:00' => 5,
            '10:25' => 5,
            '11:05:00' => 6,
            '11:05' => 6,
            '13:00:00' => 7,
            '13:00' => 7,
            '13:40:00' => 8,
            '13:40' => 8,
            '14:20:00' => 9,
            '14:20' => 9,   // <-- Tambahan Les 9
            '15:00:00' => 10,
            '15:00' => 10, // <-- Tambahan Les 10
        ];
        return $map[$time] ?? 0;
    }

    private function getWaktuPelajaran($jamKe)
    {
        $jadwal = [
            1 => ['mulai' => '07:30', 'selesai' => '08:10'],
            2 => ['mulai' => '08:10', 'selesai' => '08:50'],
            3 => ['mulai' => '08:50', 'selesai' => '09:30'],
            4 => ['mulai' => '09:45', 'selesai' => '10:25'],
            5 => ['mulai' => '10:25', 'selesai' => '11:05'],
            6 => ['mulai' => '11:05', 'selesai' => '11:45'],
            7 => ['mulai' => '13:00', 'selesai' => '13:40'],
            8 => ['mulai' => '13:40', 'selesai' => '14:20'],
            9 => ['mulai' => '14:20', 'selesai' => '15:00'], // <-- Tambahan Les 9
            10 => ['mulai' => '15:00', 'selesai' => '15:30'], // <-- Tambahan Les 10
        ];
        return $jadwal[$jamKe] ?? ['mulai' => '00:00', 'selesai' => '00:00'];
    }

    public function save()
    {
        if (!$this->validate([
            'id_tahun_ajaran' => 'required',
            'semester'        => 'required',
            'mapel_id'        => 'required',
            'guru_id'         => 'required',
            'hari'            => 'required',
            'jam_ke'          => 'required',
            'rombel_id'       => 'required'
        ])) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Lengkapi data wajib!']);
        }

        $hariInput = $this->request->getVar('hari');
        $jamKe = $this->request->getVar('jam_ke');

        $waktu = $this->getWaktuPelajaranDinamis($jamKe, $hariInput);

        // INTERCEPTOR: Ubah 'Jumat BPI' jadi 'Jumat' untuk Database agar muncul di UI
        $hariDB = ($hariInput === 'Jumat BPI') ? 'Jumat' : $hariInput;

        $data = [
            'id_tahun_ajaran' => $this->request->getVar('id_tahun_ajaran'),
            'semester'        => $this->request->getVar('semester'),
            'rombel_id'       => $this->request->getVar('rombel_id'),
            'mapel_id'        => $this->request->getVar('mapel_id'),
            'guru_id'         => $this->request->getVar('guru_id'),
            'hari'            => $hariDB,
            'jam_ke'          => $jamKe,
            'jam_mulai'       => $waktu['mulai'] . ':00',
            'jam_selesai'     => $waktu['selesai'] . ':00',
            'jenis_jadwal'    => 'Reguler'
        ];

        $jadwalModel = new JadwalModel();

        $cekBentrok = $jadwalModel->where([
            'id_tahun_ajaran' => $data['id_tahun_ajaran'],
            'semester'        => $data['semester'],
            'rombel_id'       => $data['rombel_id'],
            'hari'            => $data['hari'],
            'jam_ke'          => $data['jam_ke']
        ])->first();

        if ($cekBentrok) return $this->response->setJSON(['status' => 'error', 'message' => 'Jadwal bentrok! Sudah ada mapel di hari dan jam tersebut.']);

        try {
            $jadwalModel->insert($data);
            return $this->response->setJSON(['status' => 'success', 'message' => 'Jadwal berhasil disimpan!']);
        } catch (\Exception $e) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Database Error: ' . $e->getMessage()]);
        }
    }

    public function update($id = null)
    {
        if ($id == null) $id = $this->request->getVar('id_jadwal');

        $hariInput = $this->request->getVar('hari');
        $jamKe = $this->request->getVar('jam_ke');

        $waktu = $this->getWaktuPelajaranDinamis($jamKe, $hariInput);
        $hariDB = ($hariInput === 'Jumat BPI') ? 'Jumat' : $hariInput;

        $data = [
            'id_tahun_ajaran' => $this->request->getVar('id_tahun_ajaran'),
            'semester'        => $this->request->getVar('semester'),
            'rombel_id'       => $this->request->getVar('rombel_id'),
            'mapel_id'        => $this->request->getVar('mapel_id'),
            'guru_id'         => $this->request->getVar('guru_id'),
            'hari'            => $hariDB,
            'jam_ke'          => $jamKe,
            'jam_mulai'       => $waktu['mulai'] . ':00',
            'jam_selesai'     => $waktu['selesai'] . ':00'
        ];

        $jadwalModel = new JadwalModel();

        $cekBentrok = $jadwalModel->where([
            'id_tahun_ajaran' => $data['id_tahun_ajaran'],
            'semester'        => $data['semester'],
            'rombel_id'       => $data['rombel_id'],
            'hari'            => $data['hari'],
            'jam_ke'          => $data['jam_ke'],
            'id !='           => $id
        ])->first();

        if ($cekBentrok) return $this->response->setJSON(['status' => 'error', 'message' => 'Gagal update! Jadwal baru berbenturan.']);

        try {
            $jadwalModel->update($id, $data);
            return $this->response->setJSON(['status' => 'success', 'message' => 'Jadwal berhasil diperbarui!']);
        } catch (\Exception $e) {
            return $this->response->setJSON(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    public function delete($id)
    {
        $jadwalModel = new JadwalModel();
        try {
            $jadwalModel->delete($id);
            return $this->response->setJSON(['status' => 'success', 'message' => 'Jadwal berhasil dihapus!']);
        } catch (\Exception $e) {
            return $this->response->setJSON(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    private function getWaktuPelajaranDinamis($jamKe, $hari)
    {
        $hari = strtoupper(trim($hari));

        // 1. JUMAT KHUSUS ADA BPI (10:30 - 15:30)
        if ($hari === 'JUMAT BPI') {
            $jadwalJumatBPI = [
                1 => ['mulai' => '10:30', 'selesai' => '11:00'],
                2 => ['mulai' => '11:00', 'selesai' => '11:30'],
                3 => ['mulai' => '11:30', 'selesai' => '12:00'],
                4 => ['mulai' => '13:30', 'selesai' => '14:00'],
                5 => ['mulai' => '14:00', 'selesai' => '14:30'],
                6 => ['mulai' => '14:30', 'selesai' => '15:00'],
                7 => ['mulai' => '15:00', 'selesai' => '15:30'],
            ];
            return $jadwalJumatBPI[$jamKe] ?? ['mulai' => '00:00', 'selesai' => '00:00'];
        }

        // 2. JUMAT REGULER TANPA BPI (09:30 - 14:10, Les 20 Menit)
        if ($hari === 'JUMAT' || $hari === "JUM'AT") {
            $jadwalJumatBiasa = [
                1 => ['mulai' => '09:30', 'selesai' => '09:50'],
                2 => ['mulai' => '09:50', 'selesai' => '10:10'],
                3 => ['mulai' => '10:10', 'selesai' => '10:30'],
                4 => ['mulai' => '10:30', 'selesai' => '10:50'],
                5 => ['mulai' => '10:50', 'selesai' => '11:10'],
                6 => ['mulai' => '11:10', 'selesai' => '11:30'],
                7 => ['mulai' => '11:30', 'selesai' => '11:50'],
                8 => ['mulai' => '13:30', 'selesai' => '13:50'],
                9 => ['mulai' => '13:50', 'selesai' => '14:10'],
            ];
            return $jadwalJumatBiasa[$jamKe] ?? ['mulai' => '00:00', 'selesai' => '00:00'];
        }

        // 3. JADWAL REGULER (SENIN - KAMIS)
        $jadwalReguler = [
            1 => ['mulai' => '09:30', 'selesai' => '10:00'],
            2 => ['mulai' => '10:00', 'selesai' => '10:30'],
            3 => ['mulai' => '10:30', 'selesai' => '11:00'],
            4 => ['mulai' => '11:00', 'selesai' => '11:30'],
            5 => ['mulai' => '11:30', 'selesai' => '12:00'],
            6 => ['mulai' => '13:30', 'selesai' => '14:00'],
            7 => ['mulai' => '14:00', 'selesai' => '14:30'],
            8 => ['mulai' => '14:30', 'selesai' => '15:00'],
            9 => ['mulai' => '15:00', 'selesai' => '15:30'],
        ];
        return $jadwalReguler[$jamKe] ?? ['mulai' => '00:00', 'selesai' => '00:00'];
    }

    public function downloadTemplate()
    {
        if (ob_get_length()) ob_clean();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Roster Jadwal');

        $rombelModel = new RombelModel();
        $rombels = $rombelModel->orderBy('tingkat', 'ASC')->orderBy('nama_rombel', 'ASC')->findAll();

        $sheet->setCellValue('A1', 'JAM');
        $sheet->setCellValue('B1', 'HARI');
        $sheet->setCellValue('C1', 'LES');

        $colIndex = 4;
        foreach ($rombels as $r) {
            $colLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colIndex);
            $sheet->setCellValue($colLetter . '1', $r['tingkat'] . ' ' . $r['nama_rombel']);
            $colIndex++;
        }

        $colIndex++;
        $sheet->setCellValue(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colIndex) . '1', 'NO');
        $colIndex++;
        $colKode = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colIndex);
        $sheet->setCellValue($colKode . '1', 'KODE MAPEL/KODE GURU');
        $colIndex++;
        $colGuru = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colIndex);
        $sheet->setCellValue($colGuru . '1', 'Nama Guru');
        $colIndex++;
        $colMapel = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colIndex);
        $sheet->setCellValue($colMapel . '1', 'MATA PELAJARAN');

        $lastColLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colIndex);
        $sheet->getStyle('A1:' . $lastColLetter . '1')->getFont()->setBold(true);
        $sheet->getStyle('A1:' . $lastColLetter . '1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FF00E5FF');

        $sheet->setCellValue('A2', '07.30 - 08.10')->setCellValue('B2', 'SENIN')->setCellValue('C2', '1')
            ->setCellValue('D2', 'B.IND/DA')->setCellValue('E2', 'MM/SA');
        $sheet->setCellValue('A3', '08.10 - 08.50')->setCellValue('C3', '2')
            ->setCellValue('D3', 'B.IND/DA')->setCellValue('E3', 'MM/SA');

        $dbGuru = (new GuruTendikModel())->orderBy('nama_lengkap', 'ASC')->findAll();
        $rowKamus = 2;
        foreach ($dbGuru as $index => $g) {
            $sheet->setCellValue(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colIndex - 3) . $rowKamus, $index + 1);
            $sheet->setCellValue($colKode . $rowKamus, 'KODE-' . ($index + 1));
            $sheet->setCellValue($colGuru . $rowKamus, $g['nama_lengkap']);
            $sheet->setCellValue($colMapel . $rowKamus, 'Isi Mapel Disini');
            $rowKamus++;
        }

        foreach (range(1, $colIndex) as $col) {
            $sheet->getColumnDimension(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col))->setAutoSize(true);
        }

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Template_Roster_Jadwal.xlsx"');
        header('Cache-Control: max-age=0');

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
        exit;
    }

    public function getMappingByRombel()
    {
        if (!$this->request->isAJAX()) return $this->response->setJSON(['status' => 'error', 'message' => 'Invalid request']);

        $rombelId = $this->request->getGet('rombel_id');
        $taId = $this->request->getGet('ta_id');
        $semester = $this->request->getGet('semester');

        if (empty($rombelId)) return $this->response->setJSON(['status' => 'error', 'message' => 'Rombel ID kosong']);

        $db = \Config\Database::connect();

        // 1. AMBIL TARGET DARI MENU MAPPING
        $mappingQuery = $db->table('guru_mapel')
            ->select('guru_mapel.id as mapping_id, guru_mapel.guru_id, guru_mapel.mapel_id, guru_mapel.jam_per_minggu as target_jp, guru_tendik.nama_lengkap as nama_guru, mata_pelajaran.nama_mapel')
            ->join('guru_tendik', 'guru_tendik.id = guru_mapel.guru_id', 'left')
            ->join('mata_pelajaran', 'mata_pelajaran.id = guru_mapel.mapel_id', 'left')
            ->where('guru_mapel.rombel_id', $rombelId)
            ->where('guru_mapel.status', 'active');

        if (!empty($taId)) {
            $mappingQuery->where('guru_mapel.tahun_ajaran_id', $taId);
        }

        $mappings = $mappingQuery->get()->getResultArray();

        // 2. DETEKSI KAPASITAS MAKSIMAL (BPI vs REGULER)
        $bpiQuery = $db->table('jadwal_pelajaran')
            ->where('rombel_id', $rombelId)
            ->where('jam_ke', 0)
            ->where('kode_jadwal_excel', 'BPI');
        if (!empty($taId)) $bpiQuery->where('id_tahun_ajaran', $taId);
        if (!empty($semester)) $bpiQuery->where('semester', $semester);

        $hasBpi = $bpiQuery->countAllResults() > 0;
        $maxSlot = $hasBpi ? 43 : 45;

        // 3. HITUNG KAPASITAS KOSONG DI KALENDER UI
        $terisiQuery = $db->table('jadwal_pelajaran')
            ->where('rombel_id', $rombelId)
            ->where('jam_ke >', 0);
        if (!empty($taId)) $terisiQuery->where('id_tahun_ajaran', $taId);
        if (!empty($semester)) $terisiQuery->where('semester', $semester);

        $totalTerisi = $terisiQuery->countAllResults();

        // Slot global yang masih bisa diisi
        $sisaSlotGlobal = max(0, $maxSlot - $totalTerisi);

        $availableMappings = [];
        foreach ($mappings as $map) {
            // 4. HITUNG JADWAL MAPEL (ABAIKAN GURU, MURNI BERDASARKAN MAPEL)
            $jadwalQuery = $db->table('jadwal_pelajaran')
                ->where('rombel_id', $rombelId)
                ->where('mapel_id', $map['mapel_id']);

            if (!empty($taId)) $jadwalQuery->where('id_tahun_ajaran', $taId);
            if (!empty($semester)) $jadwalQuery->where('semester', $semester);

            $terjadwal = $jadwalQuery->countAllResults();

            $sisaJp = $map['target_jp'] - $terjadwal;

            // Opsi B: Tidak boleh minus
            $sisaJp = max(0, $sisaJp);

            // 5. ULTIMATE DYNAMIC CAPPING
            // Sisa JP mapel TIDAK BOLEH melebihi sisa kotak kosong di kalender
            // Jika kalender penuh (Sisa Slot Global = 0), maka semua mapel Sisa JP jadi 0!
            $sisaJp = min($sisaJp, $sisaSlotGlobal);

            $map['sisa_jp'] = $sisaJp;
            $availableMappings[] = $map;
        }

        return $this->response->setJSON(['status' => 'success', 'data' => $availableMappings]);
    }

    // =========================================================================
    // ARSITEKTUR STRICT VALIDATOR (SINGLE SHEET TEMPLATE & HARD STOP X-RAY)
    // =========================================================================
    public function import()
    {
        ini_set('memory_limit', '1024M');
        ini_set('max_execution_time', '300');
        if (ob_get_length()) ob_clean();

        if (empty($_FILES)) return $this->response->setJSON(['status' => 'error', 'message' => 'File tidak terdeteksi.']);
        $file = $this->request->getFile('file_excel');
        if (!$file || !$file->isValid()) return $this->response->setJSON(['status' => 'error', 'message' => 'File corrupt.']);

        $db = \Config\Database::connect();
        $db->transBegin();

        try {
            // 🚀 AMBIL TA DARI INPUT FORM MODAL IMPORT (Bukan Hardcode)
            $idTaForm = $this->request->getPost('ta_id');
            $semesterForm = $this->request->getPost('semester');
            
            $ta_aktif = $db->table('tahun_ajaran')->where('id', $idTaForm)->where('semester', $semesterForm)->get()->getRowArray();
            if (!$ta_aktif) {
                 $ta_aktif = $db->table('tahun_ajaran')->where('status', 'Aktif')->get()->getRowArray();
            }
            if (!$ta_aktif) return $this->response->setJSON(['status' => 'error', 'message' => 'Tahun Ajaran Aktif tidak ditemukan!']);

            $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReaderForFile($file->getTempName());
            $reader->setReadDataOnly(true);
            $spreadsheet = $reader->load($file->getTempName());

            // 1. STRICT SHEET VALIDATION (Hanya memproses 1 sheet aktif)
            $sheet = $spreadsheet->getActiveSheet();
            $sheetData = $sheet->toArray();

            if (empty($sheetData) || count($sheetData) < 2) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Data Excel kosong atau tidak valid! Pastikan Anda menggunakan Template resmi.']);
            }

            $dbMapel  = $db->table('mata_pelajaran')->get()->getResultArray();
            $dbGuru   = $db->table('guru_tendik')->get()->getResultArray();
            $dbRombel = $db->table('rombel')->get()->getResultArray();

            $headerRow = $sheetData[0];

            // 2. MATA PEMINDAI DINAMIS (Mendeteksi posisi Matriks & Kamus)
            $idxJam = -1;
            $idxHari = -1;
            $idxLes = -1;
            $idxKode = -1;
            $idxNamaGuru = -1;
            $idxNamaMapel = -1;
            $rombelCols = [];
            $rombelNames = [];

            foreach ($headerRow as $colIndex => $val) {
                $valStr = strtoupper(trim((string)$val));
                if (empty($valStr)) continue;

                if ($valStr === 'JAM') $idxJam = $colIndex;
                elseif ($valStr === 'HARI') $idxHari = $colIndex;
                elseif ($valStr === 'LES') $idxLes = $colIndex;
                elseif (strpos($valStr, 'KODE MAPEL') !== false || strpos($valStr, 'KODE GURU') !== false) $idxKode = $colIndex;
                elseif (strpos($valStr, 'NAMA GURU') !== false) $idxNamaGuru = $colIndex;
                elseif (strpos($valStr, 'MATA PELAJARAN') !== false) $idxNamaMapel = $colIndex;
                else {
                    $cleanVal = strtolower(trim(preg_replace('/^[789xv]+\s*/i', '', $valStr)));
                    if ($cleanVal === 'emerald') $cleanVal = 'emeral';

                    foreach ($dbRombel as $r) {
                        if (strtolower(trim($r['nama_rombel'])) === $cleanVal) {
                            $rombelCols[$colIndex] = $r['id'];
                            $rombelNames[$colIndex] = $r['tingkat'] . ' ' . $r['nama_rombel'];
                            break;
                        }
                    }
                }
            }

            // Validasi Fatal: Mencegah file salah/template lama masuk
            if ($idxKode === -1 || $idxNamaGuru === -1 || $idxNamaMapel === -1) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Format DITOLAK! Kolom Kamus (KODE MAPEL/NAMA GURU/MATA PELAJARAN) tidak ditemukan. Wajib gunakan Template Resmi!']);
            }
            if ($idxJam === -1 || $idxHari === -1 || $idxLes === -1 || empty($rombelCols)) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Format DITOLAK! Kolom Matriks (JAM/HARI/LES/KELAS) tidak ditemukan.']);
            }

            // -------------------------------------------------------------------------
            // 2.5. ANTI-MERGE CELLS SENSOR (X-RAY STRICT VALIDATION)
            // -------------------------------------------------------------------------
            $mergedCells = $sheet->getMergeCells();
            if (!empty($mergedCells)) {
                $mergeErrors = [];
                // Definisikan Batas Kiri dan Kanan Matriks (Dalam format index 1-based milik Excel)
                $minColIdx = min(array_merge([$idxJam, $idxHari, $idxLes], array_keys($rombelCols))) + 1;
                $maxColIdx = max(array_keys($rombelCols)) + 1;

                foreach ($mergedCells as $range) {
                    if (strpos($range, ':') !== false) {
                        list($start, $end) = explode(':', $range);
                        $startCoord = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::coordinateFromString($start);
                        $endCoord = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::coordinateFromString($end);

                        $startCol = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($startCoord[0]);
                        $endCol = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($endCoord[0]);
                        $endRow = (int)$endCoord[1];

                        // Validasi: Jika kotak yang digabung berakhir di Baris 2 ke atas (Area Data)
                        // DAN kotak tersebut bersinggungan dengan Area Matriks (Kolom Jam s/d Kelas Terakhir)
                        if ($endRow >= 2 && $startCol <= $maxColIdx && $endCol >= $minColIdx) {
                            $mergeErrors[] = "<b>{$range}</b>";
                        }
                    }
                }

                if (!empty($mergeErrors)) {
                    $db->transRollback();
                    $msg = implode(', ', $mergeErrors);
                    return $this->response->setJSON([
                        'status' => 'error',
                        'message' => "<b>GAGAL IMPORT! Dilarang keras menggabungkan (merge) kotak jadwal!</b><br>Terdeteksi sel gabungan pada koordinat: {$msg}.<br>Silakan Unmerge (pisahkan) sel tersebut dan isi kode di setiap kotak secara manual, lalu upload ulang."
                    ]);
                }
            }
            // -------------------------------------------------------------------------

            $kamus = [];
            $hardErrors = [];
            $mapelAliases = [
                'pkn'  => 'pendidikan pancasila',
                'ppkn' => 'pendidikan pancasila',
                'pjok' => 'pendidikan jasmani',
                'pai'  => 'pendidikan agama islam'
            ];

            // 3. PEMROSESAN KAMUS (ANTI-LOOPHOLES)
            for ($i = 1; $i < count($sheetData); $i++) {
                $row = $sheetData[$i];
                $kodeRaw = trim((string)($row[$idxKode] ?? ''));
                $guruRaw = trim((string)($row[$idxNamaGuru] ?? ''));
                $mapelRaw = trim((string)($row[$idxNamaMapel] ?? ''));

                if (empty($kodeRaw) && empty($guruRaw) && empty($mapelRaw)) continue;

                // Bypass Sisa Template Default
                if (stripos($mapelRaw, 'Isi Mapel Disini') !== false) continue;
                if (stripos($kodeRaw, 'KODE-') !== false && empty($guruRaw)) continue;

                if (!empty($kodeRaw) && !empty($guruRaw) && !empty($mapelRaw)) {
                    $guruNameOnly = trim(explode(',', $guruRaw)[0]);
                    $cleanMapel = trim(preg_replace('/\s*\([^)]*\)/', '', $mapelRaw));
                    $searchMapel = strtolower($cleanMapel);
                    $searchMapel = $mapelAliases[$searchMapel] ?? $searchMapel;

                    $gId = null;
                    $mId = null;

                    foreach ($dbGuru as $g) {
                        if (stripos($g['nama_lengkap'], $guruNameOnly) !== false) {
                            $gId = $g['id'];
                            break;
                        }
                    }
                    foreach ($dbMapel as $m) {
                        if (strtolower(trim($m['nama_mapel'])) === $searchMapel || strtolower(trim($m['kode_mapel'])) === $searchMapel) {
                            $mId = $m['id'];
                            break;
                        }
                    }

                    if (!$gId) $hardErrors[] = "KAMUS TYPO - Guru tidak ada di Database: [<b>{$guruNameOnly}</b>]";
                    if (!$mId) $hardErrors[] = "KAMUS TYPO - Mapel tidak ada di Database: [<b>{$cleanMapel}</b>]";

                    if ($gId && $mId) {
                        $kodeLines = explode("\n", str_replace("\r", "", $kodeRaw));
                        $kodeBersih = preg_replace('/[^A-Z0-9]/', '', strtoupper(trim($kodeLines[0])));
                        $kamus[$kodeBersih] = ['guru_id' => $gId, 'mapel_id' => $mId];
                    }
                }
            }

            // 4. MAPEL GLOBAL BAWAAN SISTEM
            $mapelGlobalIds = [];
            foreach ($dbMapel as $m) {
                if (stripos($m['nama_mapel'], 'Tahfizh') !== false || stripos($m['nama_mapel'], 'Tahfidz') !== false) $mapelGlobalIds['TAHFIZH'] = $m['id'];
                if (stripos($m['nama_mapel'], 'BPI') !== false) $mapelGlobalIds['BPI'] = $m['id'];
            }
            if (!isset($mapelGlobalIds['BPI'])) {
                $db->table('mata_pelajaran')->insert(['kode_mapel' => 'BPI-00', 'nama_mapel' => 'BPI', 'kkm' => 75, 'status' => 'Aktif']);
                $mapelGlobalIds['BPI'] = $db->insertID();
            }

            // 5. PEMROSESAN MATRIKS KALENDER
            $insertData = [];
            $currentHari = '';

            for ($i = 1; $i < count($sheetData); $i++) {
                $row = $sheetData[$i];

                $hariCell = trim((string)($row[$idxHari] ?? ''));
                if (!empty($hariCell)) {
                    $currentHari = strtoupper(str_replace(['`', "'", "‘", "’"], "", $hariCell));
                }
                if (empty($currentHari)) continue;

                $jamRaw = trim((string)($row[$idxJam] ?? ''));
                $lesCell = strtoupper(trim((string)($row[$idxLes] ?? '')));

                if (empty($lesCell)) continue;

                // GHOST PROTOCOL: Memusnahkan kegiatan non-akademik
                if (in_array($lesCell, ['DHUHA', 'SABAR (SARAPAN BARENG)', 'SABAR', 'UPACARA/APEL PAGI/LITERASI', 'APEL PAGI/AL KAHFI/PROGRAM BAHASA'])) {
                    continue;
                }

                $jamMulai = '00:00:00';
                $jamSelesai = '00:00:00';
                if (strpos($jamRaw, '-') !== false) {
                    $parts = explode('-', $jamRaw);
                    $jamMulai = date('H:i:s', strtotime(str_replace('.', ':', trim($parts[0]))));
                    $jamSelesai = date('H:i:s', strtotime(str_replace('.', ':', trim($parts[1]))));
                }

                if (in_array($lesCell, ['BPI', 'TAHFIZH'])) {
                    $mId = $mapelGlobalIds[$lesCell] ?? null;
                    if ($mId) {
                        foreach ($rombelCols as $colIndex => $rId) {
                            $insertData[] = [
                                'id_tahun_ajaran'   => $ta_aktif['id'],
                                'semester'          => $ta_aktif['semester'],
                                'rombel_id'         => $rId,
                                'guru_id'           => null,
                                'mapel_id'          => $mId,
                                'kode_jadwal_excel' => $lesCell,
                                'hari'              => ucfirst(strtolower($currentHari)),
                                'jam_ke'            => 0,
                                'jam_mulai'         => $jamMulai,
                                'jam_selesai'       => $jamSelesai,
                                'jenis_jadwal'      => 'Reguler'
                            ];
                        }
                    }
                } else if (is_numeric($lesCell)) {
                    foreach ($rombelCols as $colIndex => $rId) {
                        $kodeRaw = trim((string)($row[$colIndex] ?? ''));

                        // ANTI-LOOPHOLE 3: Bypass "Dirty Blanks" (Spasi siluman / tanda strip)
                        if (strlen($kodeRaw) < 3) continue;

                        $kodeLines = explode("\n", str_replace("\r", "", $kodeRaw));
                        $kodeFirstLine = trim($kodeLines[0]);
                        $kodeBersih = preg_replace('/[^A-Z0-9]/', '', strtoupper($kodeFirstLine));

                        if (isset($kamus[$kodeBersih])) {
                            $insertData[] = [
                                'id_tahun_ajaran'   => $ta_aktif['id'],
                                'semester'          => $ta_aktif['semester'],
                                'rombel_id'         => $rId,
                                'guru_id'           => $kamus[$kodeBersih]['guru_id'],
                                'mapel_id'          => $kamus[$kodeBersih]['mapel_id'],
                                'kode_jadwal_excel' => $kodeRaw,
                                'hari'              => ucfirst(strtolower($currentHari)),
                                'jam_ke'            => (int)$lesCell,
                                'jam_mulai'         => $jamMulai,
                                'jam_selesai'       => $jamSelesai,
                                'jenis_jadwal'      => 'Reguler'
                            ];
                        } else {
                            if (preg_match('/[A-Z]/', $kodeBersih) && strlen($kodeBersih) >= 3) {
                                $rName = $rombelNames[$colIndex] ?? 'Kelas Unknown';
                                $hardErrors[] = "KODE GHAIB - [{$rName}] Hari {$currentHari} Les {$lesCell} => Kode '<b>{$kodeRaw}</b>' tidak ada di Kamus!";
                            }
                        }
                    }
                }
            }

            // 6. HARD STOP EVALUATION (Menegakkan kedisiplinan file user)
            if (!empty($hardErrors)) {
                $db->transRollback();
                $uniqueErrors = array_unique($hardErrors);
                $errorMsg = "<ul class='text-left list-disc pl-4 text-xs space-y-1'>";
                $count = 0;
                foreach ($uniqueErrors as $err) {
                    if ($count >= 7) {
                        $errorMsg .= "<li>...dan " . (count($uniqueErrors) - 7) . " error lainnya.</li>";
                        break;
                    }
                    $errorMsg .= "<li>{$err}</li>";
                    $count++;
                }
                $errorMsg .= "</ul><p class='mt-2 text-xs font-bold text-red-600'>Proses dibatalkan. Perbaiki file Excel sesuai Panduan lalu upload ulang!</p>";

                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => '<b>GAGAL IMPORT! Ditemukan kesalahan data:</b><br>' . $errorMsg
                ]);
            }

            if (empty($insertData)) {
                $db->transRollback();
                return $this->response->setJSON(['status' => 'error', 'message' => 'Gagal! Tidak ada jadwal valid yang terbaca. Pastikan format tabel sesuai template.']);
            }

            // 7. WIPE AND BATCH INSERT
            $db->table('jadwal_pelajaran')
                ->where('id_tahun_ajaran', $ta_aktif['id'])
                ->where('semester', $ta_aktif['semester'])
                ->delete();

            $chunks = array_chunk($insertData, 100);
            foreach ($chunks as $chunk) {
                $db->table('jadwal_pelajaran')->insertBatch($chunk);
            }

            if ($db->transStatus() === false) {
                $db->transRollback();
                return $this->response->setJSON(['status' => 'error', 'message' => 'Database error saat menyimpan jadwal.']);
            }

            $db->transCommit();
            return $this->response->setJSON(['status' => 'success', 'message' => count($insertData) . ' Slot Jadwal berhasil diimpor dengan mematuhi Template Resmi!']);
        } catch (\Throwable $e) {
            $db->transRollback();
            return $this->response->setJSON(['status' => 'error', 'message' => 'System Error: ' . $e->getMessage()]);
        }
    }
}
