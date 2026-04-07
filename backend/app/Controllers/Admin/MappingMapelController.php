<?php

namespace App\Controllers\Admin;

use App\Controllers\AdminBaseController;
use App\Models\Admin\GuruMapelModel;
use App\Models\Admin\GuruTendikModel;
use App\Models\Admin\MataPelajaranModel;
use App\Models\Admin\RombelModel;
use App\Models\Admin\TahunAjaranModel;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;

class MappingMapelController extends AdminBaseController
{
    protected $guruMapelModel;
    protected $guruModel;
    protected $mapelModel;
    protected $rombelModel;
    protected $tahunAjaranModel;

    public function __construct()
    {
        $this->guruMapelModel   = new GuruMapelModel();
        $this->guruModel        = new GuruTendikModel();
        $this->mapelModel       = new MataPelajaranModel();
        $this->rombelModel      = new RombelModel();
        $this->tahunAjaranModel = new TahunAjaranModel();
    }

    public function index(): string
    {
        $db = \Config\Database::connect();
        
        // --- 1. TANGKAP FILTER TAHUN AJARAN ---
        $id_ta_get = $this->request->getGet('ta');
        if ($id_ta_get) {
            $activeTahunRow = $this->tahunAjaranModel->where('id', $id_ta_get)->first();
        } else {
            $activeTahunRow = $this->tahunAjaranModel->where('status', 'Aktif')->first();
        }
        
        $idTaAktif = $activeTahunRow ? $activeTahunRow['id'] : 0;
        $tahunAjaranAktif = $activeTahunRow ? $activeTahunRow['tahun'] . ' (' . $activeTahunRow['semester'] . ')' : 'Belum Diset';
        
        // --- 2. FILTER MAPPING BERDASARKAN TA ---
        // (Pastikan GuruMapelModel punya field tahun_ajaran_id. Jika tidak, abaikan where ini di modelnya nanti)
        $rawMappings = $db->table('guru_mapel gm')
            ->select('gm.*, gt.nama_lengkap as teacher, gt.nik, m.nama_mapel as mapel, r.nama_rombel, r.tingkat, ta.tahun as ta_tahun, ta.semester as ta_semester')
            ->join('guru_tendik gt', 'gt.id = gm.guru_id', 'left')
            ->join('mata_pelajaran m', 'm.id = gm.mapel_id', 'left')
            ->join('rombel r', 'r.id = gm.rombel_id', 'left')
            ->join('tahun_ajaran ta', 'ta.id = gm.tahun_ajaran_id', 'left')
            ->where('gm.tahun_ajaran_id', $idTaAktif) // SUNTIKAN FILTER TA
            ->get()->getResultArray();

        $mapelData = $db->table('mata_pelajaran')->select('id, kode_mapel')->get()->getResultArray();
        $mapelDict = array_column($mapelData, 'kode_mapel', 'id');
        
        $guruData = $db->table('guru_tendik')
                       ->select('guru_tendik.id, users.foto_profil')
                       ->join('users', 'users.id = guru_tendik.user_id', 'left')
                       ->get()->getResultArray();
        $guruPhotoDict = array_column($guruData, 'foto_profil', 'id');

        $jadwals = [];
        if ($db->tableExists('jadwal_pelajaran')) {
            $jadwals = $db->table('jadwal_pelajaran')->where('id_tahun_ajaran', $idTaAktif)->get()->getResultArray(); // FILTER JADWAL
        }

        $formattedData = array_map(function ($row) use ($rawMappings, $mapelDict, $guruPhotoDict, $jadwals) {
            $kode_mapel = $mapelDict[$row['mapel_id']] ?? '-';
            $foto = $guruPhotoDict[$row['guru_id']] ?? null;

            $list_rombel = [];
            foreach ($rawMappings as $rm) {
                if ($rm['guru_id'] == $row['guru_id'] && $rm['mapel_id'] == $row['mapel_id']) {
                    $list_rombel[] = $rm['tingkat'] . ' ' . str_replace($rm['tingkat'] . '-', '', $rm['nama_rombel']);
                }
            }
            $total_rombel = count(array_unique($list_rombel));
            $rombel_detail_str = implode(', ', array_unique($list_rombel));

            $hari_mengajar = [];
            foreach ($jadwals as $j) {
                $g_id = $j['guru_id'] ?? $j['id_guru'] ?? null;
                $m_id = $j['mapel_id'] ?? $j['id_mapel'] ?? null;
                $r_id = $j['rombel_id'] ?? $j['id_rombel'] ?? null;
                $h = $j['hari'] ?? null;

                if ($g_id == $row['guru_id'] && $m_id == $row['mapel_id'] && $r_id == $row['rombel_id'] && $h) {
                    $hariNormal = ucfirst(strtolower(trim($h)));
                    if (in_array($hariNormal, ["Jum'at", "Jum`at", "Jum at", "Jumat"])) {
                        $hariNormal = "Jumat"; 
                    }
                    $hari_mengajar[] = $hariNormal;
                }
            }
            
            $hari_mengajar = array_unique($hari_mengajar);
            
            $orderHari = ['Senin'=>1, 'Selasa'=>2, 'Rabu'=>3, 'Kamis'=>4, 'Jumat'=>5, 'Sabtu'=>6, 'Minggu'=>7];
            usort($hari_mengajar, function($a, $b) use ($orderHari) {
                return ($orderHari[$a] ?? 99) <=> ($orderHari[$b] ?? 99);
            });
            
            $hari_string = empty($hari_mengajar) ? '' : implode(', ', $hari_mengajar);

            return [
                'id'          => $row['id'],
                'teacher'     => $row['teacher'],
                'teacher_id'  => $row['guru_id'],
                'foto'        => $foto,
                'nik'         => $row['nik'] ?? '-',
                'mapel'       => $row['mapel'],
                'mapel_id'    => $row['mapel_id'],
                'kode_mapel'  => $kode_mapel,
                'level'       => $row['tingkat'],
                'rombel'      => str_replace($row['tingkat'] . '-', '', $row['nama_rombel']),
                'rombel_id'   => $row['rombel_id'],
                'rombel_full' => $row['nama_rombel'],
                'jam'         => $row['jam_per_minggu'],
                'tahunAjaran' => (!empty($row['ta_tahun'])) ? $row['ta_tahun'] . ' (' . $row['ta_semester'] . ')' : '-', // Fix UI
                'catatan'     => $row['catatan'] ?? '',
                'status'      => $row['status'],
                'total_rombel'=> $total_rombel,
                'list_rombel' => $rombel_detail_str,
                'hari_masuk'  => $hari_string
            ];
        }, $rawMappings);

        $totalGuru = $this->guruModel->countAllResults();
        $totalMapel = $this->mapelModel->countAllResults();
        $totalMapping = $this->guruMapelModel->where('status', 'active')->where('tahun_ajaran_id', $idTaAktif)->countAllResults();

        // --- 3. FILTER ROMBEL BERDASARKAN TA ---
        $rombelRaw = $this->rombelModel->where('id_tahun_ajaran', $idTaAktif)->orderBy('tingkat', 'ASC')->findAll();
        $allRombelIds = array_column($rombelRaw, 'id');
        
        $mappedRombelIds = $this->guruMapelModel->where('tahun_ajaran_id', $idTaAktif)->findColumn('rombel_id') ?? [];
        $unmappedRombelCount = count(array_diff($allRombelIds, array_unique($mappedRombelIds)));

        $tingkatList = array_unique(array_column($rombelRaw, 'tingkat'));

        $mapelRaw = $this->mapelModel->orderBy('nama_mapel', 'ASC')->findAll();
        $uniqueMapel = [];
        $seenMapel = [];
        foreach ($mapelRaw as $m) {
            $namaNormalized = trim(strtolower($m['nama_mapel']));
            if (!in_array($namaNormalized, $seenMapel)) {
                $seenMapel[] = $namaNormalized;
                $uniqueMapel[] = $m;
            }
        }

        $data = [
            'user'            => 'Admin',
            'navigations'     => $this->getSidebarMenu(),
            'color'           => $this->getColor(),
            'mappingData'     => $formattedData,
            'stats'           => [
                'total_guru'    => $totalGuru,
                'total_mapel'   => $totalMapel,
                'total_mapping' => $totalMapping,
                'empty_rombel'  => $unmappedRombelCount
            ],
            'guruList'        => $this->guruModel->orderBy('nama_lengkap', 'ASC')->findAll(),
            'mapelList'       => $uniqueMapel, 
            'rombelList'      => $rombelRaw, // SEKARANG HANYA MUNCUL ROMBEL DARI TA TERPILIH
            'tingkatList'     => $tingkatList,
            'tahunAjaranList' => $this->tahunAjaranModel->orderBy('tahun', 'DESC')->findAll(),
            'tahunAjaranAktif'=> $tahunAjaranAktif,
            'idTaAktif'       => $idTaAktif // Lempar ke View agar dropdown Select Option tahu mana yg aktif
        ];

        return view('admin/mapping-mapel', $data);
    }

    public function store()
    {
        $guruId      = $this->request->getPost('add_guru');
        $mapelId     = $this->request->getPost('add_mapel');
        $rombelIdsRaw= $this->request->getPost('add_rombel');
        $rombelIds   = is_string($rombelIdsRaw) ? json_decode($rombelIdsRaw, true) : $rombelIdsRaw;
        $jam         = $this->request->getPost('add_jam');
        $catatan     = $this->request->getPost('add_catatan');

        if (empty($guruId) || empty($mapelId) || empty($rombelIds)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Guru, Mapel, dan minimal satu Rombel harus dipilih!']);
        }

        // AMBIL ID TAHUN AJARAN LANGSUNG DARI DATABASE (ANTI-HACK)
        $activeTA = $this->tahunAjaranModel->where('status', 'Aktif')->first();
        if (!$activeTA) return $this->response->setJSON(['status' => 'error', 'message' => 'Tahun Ajaran Aktif tidak ditemukan di sistem!']);
        $taId = $activeTA['id'];

        $successCount = 0; $existCount = 0;

        foreach ($rombelIds as $rombelId) {
            $exists = $this->guruMapelModel->checkDuplicate($guruId, $mapelId, $rombelId);
            if (!$exists) {
                $this->guruMapelModel->insert([
                    'guru_id'         => $guruId,
                    'mapel_id'        => $mapelId,
                    'rombel_id'       => $rombelId,
                    'tahun_ajaran_id' => $taId, // Fix Typo Column
                    'jam_per_minggu'  => $jam,
                    'catatan'         => $catatan,
                    'status'          => 'active'
                ]);

                // LOG KE RIWAYAT JABATAN GURU
                $db = \Config\Database::connect();
                $mInfo = $db->table('mata_pelajaran')->where('id', $mapelId)->get()->getRowArray();
                $rInfo = $db->table('rombel')->where('id', $rombelId)->get()->getRowArray();
                $namaM = $mInfo ? $mInfo['nama_mapel'] : 'Mapel';
                $namaR = $rInfo ? $rInfo['nama_rombel'] : 'Rombel';

                // Cek agar tidak duplikat riwayat di detik yang sama
                $historyCheck = $db->table('riwayat_jabatan_guru')
                                   ->where(['guru_id' => $guruId, 'tahun_ajaran_id' => $taId, 'semester' => $activeTA['semester'], 'jabatan' => 'Guru Mapel', 'keterangan' => "$namaM di Kelas $namaR"])
                                   ->get()->getRow();

                if (!$historyCheck) {
                    $db->table('riwayat_jabatan_guru')->insert([
                        'guru_id'         => $guruId,
                        'tahun_ajaran_id' => $taId,
                        'semester'        => $activeTA['semester'],
                        'jabatan'         => 'Guru Mapel',
                        'keterangan'      => "$namaM di Kelas $namaR",
                        'created_at'      => date('Y-m-d H:i:s')
                    ]);
                }

                $successCount++;
            } else {
                $existCount++;
            }
        }

        if ($successCount > 0) {
            $msg = "$successCount data berhasil disimpan.";
            if ($existCount > 0) $msg .= " ($existCount data dilewati karena sudah ada)";
            return $this->response->setJSON(['status' => 'success', 'message' => $msg]);
        }
        return $this->response->setJSON(['status' => 'error', 'message' => 'Data mapping sudah ada sebelumnya.']);
    }

    public function update($id = null)
    {
        $id = $id ?? $this->request->getPost('id');
        if (empty($id)) return $this->response->setJSON(['status' => 'error', 'message' => 'ID data tidak ditemukan.']);

        $guruId      = $this->request->getPost('add_guru');
        $mapelId     = $this->request->getPost('add_mapel');
        $rombelIdsRaw= $this->request->getPost('add_rombel');
        $rombelIds   = json_decode($rombelIdsRaw, true);
        if (!is_array($rombelIds)) {
            $rombelIds = [$rombelIdsRaw]; 
        }

        $jam         = $this->request->getPost('add_jam');
        $catatan     = $this->request->getPost('add_catatan');

        if (empty($rombelIds) || empty($rombelIds[0])) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Rombel tidak boleh kosong!']);
        }

        // AMBIL ID TAHUN AJARAN LANGSUNG DARI DATABASE
        $activeTA = $this->tahunAjaranModel->where('status', 'Aktif')->first();
        if (!$activeTA) return $this->response->setJSON(['status' => 'error', 'message' => 'Tahun Ajaran Aktif tidak ditemukan di sistem!']);
        $taId = $activeTA['id'];

        $db = \Config\Database::connect();
        $db->transStart();

        $firstRombelId = array_shift($rombelIds); 
        $this->guruMapelModel->update($id, [
            'guru_id'         => $guruId,
            'mapel_id'        => $mapelId,
            'rombel_id'       => $firstRombelId,
            'tahun_ajaran_id' => $taId, // Fix Typo Column
            'jam_per_minggu'  => $jam,
            'catatan'         => $catatan
        ]);

        // LOG KE RIWAYAT (UPDATE)
        $mInfo = $db->table('mata_pelajaran')->where('id', $mapelId)->get()->getRowArray();
        $rInfo = $db->table('rombel')->where('id', $firstRombelId)->get()->getRowArray();
        $namaM = $mInfo ? $mInfo['nama_mapel'] : 'Mapel';
        $namaR = $rInfo ? $rInfo['nama_rombel'] : 'Rombel';

        $db->table('riwayat_jabatan_guru')->insert([
            'guru_id'         => $guruId,
            'tahun_ajaran_id' => $taId,
            'semester'        => $activeTA['semester'],
            'jabatan'         => 'Guru Mapel',
            'keterangan'      => "$namaM di Kelas $namaR (Update)",
            'created_at'      => date('Y-m-d H:i:s')
        ]);

        foreach ($rombelIds as $rombelId) {
            if (!empty($rombelId)) {
                $exists = $this->guruMapelModel->checkDuplicate($guruId, $mapelId, $rombelId);
                if (!$exists) {
                    $this->guruMapelModel->insert([
                        'guru_id'         => $guruId,
                        'mapel_id'        => $mapelId,
                        'rombel_id'       => $rombelId,
                        'tahun_ajaran_id' => $taId, // Fix Typo Column
                        'jam_per_minggu'  => $jam,
                        'catatan'         => $catatan,
                        'status'          => 'active'
                    ]);

                    // LOG KE RIWAYAT (INSERT BARU DALAM UPDATE MULTI)
                    $rInfo2 = $db->table('rombel')->where('id', $rombelId)->get()->getRowArray();
                    $namaR2 = $rInfo2 ? $rInfo2['nama_rombel'] : 'Rombel';

                    $db->table('riwayat_jabatan_guru')->insert([
                        'guru_id'         => $guruId,
                        'tahun_ajaran_id' => $taId,
                        'semester'        => $activeTA['semester'],
                        'jabatan'         => 'Guru Mapel',
                        'keterangan'      => "$namaM di Kelas $namaR2",
                        'created_at'      => date('Y-m-d H:i:s')
                    ]);
                }
            }
        }

        $db->transComplete();

        if ($db->transStatus() === false) return $this->response->setJSON(['status' => 'error', 'message' => 'Gagal memperbarui data.']);
        return $this->response->setJSON(['status' => 'success', 'message' => 'Data mapping berhasil diperbarui.']);
    }

    public function delete()
    {
        $id = $this->request->getPost('id');
        if ($this->guruMapelModel->delete($id)) {
            return $this->response->setJSON(['status' => 'success', 'message' => 'Data berhasil dihapus']);
        }
        return $this->response->setJSON(['status' => 'error', 'message' => 'Gagal menghapus data']);
    }

    private function generateKodeMapel($nama_mapel, $kode_excel = '') 
    {
        if (!empty($kode_excel)) {
            $parts = explode('/', $kode_excel);
            $kode = trim(strtoupper($parts[0]));
            return preg_replace('/[^A-Z0-9\.]/', '', $kode);
        }

        $kamus = [
            'matematika' => 'MTK', 'bahasa indonesia' => 'B.IND', 'bahasa inggris' => 'B.ING',
            'bahasa arab' => 'BA', 'ilmu pengetahuan alam' => 'IPA', 'ipa' => 'IPA',
            'ilmu pengetahuan sosial' => 'IPS', 'ips' => 'IPS', 'pendidikan agama islam' => 'PAI',
            'pai' => 'PAI', 'pendidikan jasmani' => 'PJOK', 'pjok' => 'PJOK', 'olahraga' => 'PJOK',
            'pendidikan pancasila' => 'PKN', 'kewarganegaraan' => 'PKN', 'pkn' => 'PKN',
            'ppkn' => 'PPKN', 'seni budaya' => 'SB', 'seni' => 'SB', 'prakarya' => 'PRK',
            'informatika' => 'INF', 'tik' => 'TIK', 'komputer' => 'TIK', 'sejarah kebudayaan islam' => 'SKI',
            'quran hadis' => 'QH', 'akidah akhlak' => 'AA', 'fikih' => 'FKH', 'bimbingan konseling' => 'BK',
            'tahfizh' => 'THF', 'tahfidz' => 'THF'
        ];

        $lower_nama = strtolower(trim($nama_mapel));
        foreach ($kamus as $key => $val) {
            if (strpos($lower_nama, $key) !== false) return $val;
        }

        $words = explode(' ', trim($nama_mapel));
        if (count($words) > 1) {
            $singkatan = '';
            foreach ($words as $w) {
                if (!empty($w)) $singkatan .= strtoupper(substr($w, 0, 1));
            }
            return substr($singkatan, 0, 5); 
        }

        $konsonan = preg_replace('/[aeiouAEIOU\s]/', '', $nama_mapel);
        if (strlen($konsonan) >= 3) return strtoupper(substr($konsonan, 0, 3));
        return strtoupper(substr($nama_mapel, 0, 3));
    }

    public function downloadTemplate()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $headerStyle = [
            'font' => ['bold' => true, 'color' => ['argb' => 'FFFFFF']],
            'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['argb' => '10B981']],
            'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]
        ];

        $headers = ['Kode Mapel', 'Nama Guru', 'Mata Pelajaran', 'Kelas Yang Diampu', 'JP'];
        $column = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($column . '1', $header);
            $sheet->getColumnDimension($column)->setAutoSize(true);
            $column++;
        }
        $sheet->getStyle('A1:E1')->applyFromArray($headerStyle);

        $sheet->setCellValue('A2', 'B.IND/NF');
        $sheet->setCellValue('B2', 'Nina Fitriani, S.Pd, Gr');
        $sheet->setCellValue('C2', 'Bahasa Indonesia');
        $sheet->setCellValue('D2', '8 Biduri, 8 Crystal, 8 Diamond'); 
        $sheet->setCellValue('E2', '4');

        $sheet->setCellValue('G1', 'PANDUAN SMART IMPORT (AI):');
        $sheet->setCellValue('G2', '1. Kolom "JP" Opsional. Jika kosong, sistem otomatis menghitung JP dari Jadwal.');
        $sheet->setCellValue('G3', '2. Anda bisa menulis 1 guru, 1 mapel, dan menggabungkan banyak kelas dengan Koma (,)');
        $sheet->setCellValue('G4', '3. Guru dan Mapel yang belum ada di Database akan OTOMATIS dibuatkan oleh sistem.');
        $sheet->setCellValue('G5', '4. Singkatan Mapel akan otomatis disesuaikan (Contoh: Matematika -> MTK).');
        $sheet->getStyle('G1')->getFont()->setBold(true);

        $writer = new Xlsx($spreadsheet);
        $filename = 'Template_Smart_Mapping_Guru.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
        exit;
    }

   public function import()
    {
        if ($this->request->isAJAX()) {
            $file = $this->request->getFile('file_excel');

            if (!$file || !$file->isValid()) return $this->response->setJSON(['status' => 'error', 'message' => 'File Excel tidak valid.']);

            try {
                $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReaderForFile($file->getTempName());
                $reader->setReadDataOnly(true);
                
                $reader->setLoadSheetsOnly(['Deskripsi']); 
                
                $spreadsheet = $reader->load($file->getTempName());
                $sheetData = $spreadsheet->getActiveSheet()->toArray();

                if (count($sheetData) <= 2) return $this->response->setJSON(['status' => 'error', 'message' => 'Data pada sheet Deskripsi kosong atau nama sheet bukan "Deskripsi".']);

                $db = \Config\Database::connect();
                
                // AMBIL ID TAHUN AJARAN LANGSUNG DARI DATABASE
                $activeTahunRow = $this->tahunAjaranModel->where('status', 'Aktif')->first();
                if (!$activeTahunRow) return $this->response->setJSON(['status' => 'error', 'message' => 'Tidak ada Tahun Ajaran Aktif di database!']);
                $tahun_ajaran_id = $activeTahunRow['id'];
                
                $successCount = 0; $failCount = 0;
                $newGuruCount = 0; $newMapelCount = 0;

                $idx_guru = 1;
                $idx_mapel = 3;
                $idx_jam = 5;
                $idx_rombel = 6;

                for ($i = 2; $i < count($sheetData); $i++) { 
                    $row = $sheetData[$i];
                    if (empty(array_filter($row))) continue;

                    $nama_guru = isset($row[$idx_guru]) ? trim((string)$row[$idx_guru]) : '';
                    $nama_mapel = isset($row[$idx_mapel]) ? trim((string)$row[$idx_mapel]) : '';
                    $kelas_string = isset($row[$idx_rombel]) ? trim((string)$row[$idx_rombel]) : '';
                    $jam_raw = isset($row[$idx_jam]) ? trim((string)$row[$idx_jam]) : '';
                    
                    if (empty($nama_guru) || empty($nama_mapel) || empty($kelas_string)) continue;
                    if ($nama_guru === '0' || $nama_mapel === '0' || $kelas_string === '0') continue;
                    if (strtolower($nama_guru) === 'nama guru') continue;

                    $base_nama_guru = trim(explode(',', $nama_guru)[0]); 
                    $base_nama_mapel = trim(preg_replace('/\s*\([^)]*\)/', '', $nama_mapel)); 
                    
                    $guru = $db->table('guru_tendik')->like('nama_lengkap', $base_nama_guru, 'both')->get()->getRowArray();
                    if ($guru) {
                        $guru_id = $guru['id'];
                    } else {
                        $nik_acak = 'AUTO-' . mt_rand(10000, 99999);
                        $db->table('guru_tendik')->insert(['nama_lengkap' => $nama_guru, 'nik' => $nik_acak]);
                        $guru_id = $db->insertID();
                        $newGuruCount++;
                    }

                    $mapel = $db->table('mata_pelajaran')->like('nama_mapel', $base_nama_mapel, 'both')->get()->getRowArray();
                    if ($mapel) {
                        $mapel_id = $mapel['id'];
                    } else {
                        $kode_mapel = strtoupper(substr(preg_replace('/[^a-zA-Z]/', '', $base_nama_mapel), 0, 3)) . '-' . mt_rand(10,99);
                        $db->table('mata_pelajaran')->insert(['nama_mapel' => $base_nama_mapel, 'kode_mapel' => $kode_mapel, 'kkm' => 75, 'status' => 'Aktif']);
                        $mapel_id = $db->insertID();
                        $newMapelCount++;
                    }

                    $kelas_string = str_replace(["\n", "\r", " dan ", "&"], ",", $kelas_string);
                    $kelasArray = array_map('trim', explode(',', $kelas_string));
                    
                    foreach ($kelasArray as $nama_kelas) {
                        if (empty($nama_kelas)) continue;
                        
                        $nama_kelas_bersih = trim(str_replace(['7', '8', '9', 'Kelas', '-'], '', $nama_kelas));
                        
                        // 🚀 SUNTIKAN ANTI-BUG: Pastikan hanya mencari rombel di Tahun Ajaran Aktif!
                        $rombel = $db->table('rombel')
                            ->where('id_tahun_ajaran', $tahun_ajaran_id)
                            ->like('nama_rombel', $nama_kelas_bersih, 'both')
                            ->get()->getRowArray();

                        if ($rombel) {
                            $rombel_id = $rombel['id'];
                            $jam_per_minggu = is_numeric($jam_raw) && $jam_raw > 0 ? (int)$jam_raw : 4;

                            $exists = $this->guruMapelModel->checkDuplicate($guru_id, $mapel_id, $rombel_id);
                            if (!$exists) {
                                $this->guruMapelModel->insert([
                                    'guru_id'         => $guru_id,
                                    'mapel_id'        => $mapel_id,
                                    'rombel_id'       => $rombel_id,
                                    'tahun_ajaran_id' => $tahun_ajaran_id, // Fix Typo Column
                                    'jam_per_minggu'  => $jam_per_minggu,
                                    'status'          => 'active'
                                ]);

                                // LOG KE RIWAYAT JABATAN GURU (DARI IMPORT)
                                $db->table('riwayat_jabatan_guru')->insert([
                                    'guru_id'         => $guru_id,
                                    'tahun_ajaran_id' => $tahun_ajaran_id,
                                    'semester'        => $activeTahunRow['semester'],
                                    'jabatan'         => 'Guru Mapel',
                                    'keterangan'      => "$base_nama_mapel di Kelas " . ($rombel['nama_rombel'] ?? 'Rombel'),
                                    'created_at'      => date('Y-m-d H:i:s')
                                ]);

                                $successCount++;
                            } else {
                                if ($exists['status'] === 'inactive') {
                                    $this->guruMapelModel->update($exists['id'], ['status' => 'active', 'jam_per_minggu' => $jam_per_minggu]);
                                    
                                    // LOG KE RIWAYAT JABATAN GURU (DARI RE-AKTIVASI IMPORT)
                                    $db->table('riwayat_jabatan_guru')->insert([
                                        'guru_id'         => $guru_id,
                                        'tahun_ajaran_id' => $tahun_ajaran_id,
                                        'semester'        => $activeTahunRow['semester'],
                                        'jabatan'         => 'Guru Mapel',
                                        'keterangan'      => "$base_nama_mapel di Kelas " . ($rombel['nama_rombel'] ?? 'Rombel') . " (Import Re-active)",
                                        'created_at'      => date('Y-m-d H:i:s')
                                    ]);

                                    $successCount++;
                                } else {
                                    $failCount++; 
                                }
                            }
                        } else {
                            $failCount++;
                        }
                    }
                }

                $msg = "Selesai! $successCount Mapping berhasil diimpor.";
                $details = [];
                if ($newGuruCount > 0) $details[] = "$newGuruCount Guru Baru";
                if ($newMapelCount > 0) $details[] = "$newMapelCount Mapel Baru";
                if (!empty($details)) $msg .= " Master data ditambah: " . implode(', ', $details) . ".";
                if ($failCount > 0) $msg .= " ($failCount data dilewati karena kelas tak dikenali / duplikat).";

                return $this->response->setJSON(['status' => $successCount > 0 ? 'success' : 'warning', 'message' => $msg]);

            } catch (\Exception $e) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Gagal memproses file: ' . $e->getMessage()]);
            }
        }
    }
}