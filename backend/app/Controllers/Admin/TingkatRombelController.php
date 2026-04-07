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

        $taAktif = $db->table('tahun_ajaran')->where('status', 'Aktif')->get()->getRowArray();
        $idTaAktif = $taAktif ? $taAktif['id'] : null;
        $strTaAktif = $taAktif ? $taAktif['tahun'] : 'Belum Diset';

        $tahun_ajaran_list = $db->table('tahun_ajaran')->orderBy('id', 'DESC')->get()->getResultArray();

        $totalSiswa = $siswaModel->where('status_siswa', 'Aktif')->countAllResults();
        $totalRombel = $rombelModel->countAll();

        $listRombel = $rombelModel
            ->select('rombel.*, guru_tendik.nama_lengkap as nama_wali_kelas, ta.tahun as nama_tahun_ajaran, ta.semester as semester_ta')
            ->select('(SELECT COUNT(*) FROM siswa WHERE siswa.rombel_id = rombel.id AND siswa.status_siswa = "Aktif") as jumlah_siswa')
            ->join('guru_tendik', 'guru_tendik.id = rombel.wali_kelas_id', 'left')
            ->join('tahun_ajaran ta', 'ta.id = rombel.id_tahun_ajaran', 'left')
            ->orderBy('rombel.id_tahun_ajaran', 'DESC')
            ->orderBy('rombel.tingkat', 'ASC')
            ->orderBy('rombel.nama_rombel', 'ASC')
            ->findAll();

        $rawRombelStats = $db->table('rombel')
            ->select('id, tingkat, id_tahun_ajaran, is_lulus')
            ->get()->getResultArray();

        $rawSiswaStats = $db->table('siswa')
            ->select('siswa.id, siswa.jenis_kelamin, siswa.rombel_id, rombel.tingkat, rombel.id_tahun_ajaran, rombel.is_lulus')
            ->join('rombel', 'rombel.id = siswa.rombel_id', 'inner')
            ->where('siswa.status_siswa', 'Aktif')
            ->get()->getResultArray();

        $data = [
            'user'              => 'Admin',
            'navigations'       => $this->getSidebarMenu(),
            'rombel_list'       => $listRombel,
            'guru_list'         => $listGuru,
            'tahun_ajaran_list' => $tahun_ajaran_list,
            'total_siswa'       => $totalSiswa,
            'total_rombel'      => $totalRombel,
            'raw_rombel_stats'  => $rawRombelStats,
            'raw_siswa_stats'   => $rawSiswaStats,
            'idTaAktif'         => $idTaAktif,
            'strTaAktif'        => $strTaAktif,
            'color'             => $this->getColor()
        ];

        return view('admin/tingkat-rombel', $data);
    }

    public function store()
    {
        if (!$this->validate([
            'rombel_name' => 'required',
            'level'       => 'required',
            'id_tahun_ajaran' => 'required',
            'homeroom_teacher' => [
                'rules'  => 'permit_empty|is_unique[rombel.wali_kelas_id]',
                'errors' => [
                    'is_unique' => 'Guru ini sudah menjadi wali kelas di kelas lain.'
                ]
            ],
        ])) {
            return $this->response->setJSON(['status' => 'error', 'message' => implode(', ', $this->validator->getErrors())]);
        }

        $db = \Config\Database::connect();
        $targetTa = $db->table('tahun_ajaran')->where('id', $this->request->getPost('id_tahun_ajaran'))->get()->getRowArray();
        $semester = $targetTa ? $targetTa['semester'] : 'Ganjil';

        $data = [
            'nama_rombel'     => $this->request->getPost('rombel_name'),
            'tingkat'         => $this->request->getPost('level'),
            'wali_kelas_id'   => $this->request->getPost('homeroom_teacher') ?: null,
            'id_tahun_ajaran' => $this->request->getPost('id_tahun_ajaran'),
            'semester'        => $semester,
            'kurikulum_id'    => 1, // Asumsi ID 1 adalah Kurikulum Merdeka di database
            'is_lulus'        => 0
        ];

        if ($db->table('rombel')->insert($data)) {
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
            'id_tahun_ajaran' => 'required',
            'homeroom_teacher' => [
                'rules'  => "permit_empty|is_unique[rombel.wali_kelas_id,id,{$id}]",
                'errors' => [
                    'is_unique' => 'Guru ini sudah menjadi wali kelas di kelas lain.'
                ]
            ],
        ])) {
            return $this->response->setJSON(['status' => 'error', 'message' => implode(', ', $this->validator->getErrors())]);
        }

        $db = \Config\Database::connect();
        $targetTa = $db->table('tahun_ajaran')->where('id', $this->request->getPost('id_tahun_ajaran'))->get()->getRowArray();
        $semester = $targetTa ? $targetTa['semester'] : 'Ganjil';

        $data = [
            'nama_rombel'     => $this->request->getPost('rombel_name'),
            'tingkat'         => $this->request->getPost('level'),
            'wali_kelas_id'   => $this->request->getPost('homeroom_teacher') ?: null,
            'id_tahun_ajaran' => $this->request->getPost('id_tahun_ajaran'),
            'semester'        => $semester
        ];

        if ($db->table('rombel')->where('id', $id)->update($data)) {
            return $this->response->setJSON(['status' => 'success', 'message' => 'Data berhasil diperbarui']);
        } else {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Gagal update database']);
        }
    }

    public function delete()
    {
        $db = \Config\Database::connect();
        $id = $this->request->getPost('id');

        if (!$id) return $this->response->setJSON(['status' => 'error', 'message' => 'ID tidak ditemukan.']);

        try {
            if ($db->table('rombel')->where('id', $id)->delete()) {
                return $this->response->setJSON(['status' => 'success', 'message' => 'Data berhasil dihapus']);
            } else {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Database menolak penghapusan.']);
            }
        } catch (\Exception $e) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Gagal menghapus: Kemungkinan masih ada data siswa/jadwal terhubung ke Rombel ini.']);
        }
    }

    public function show($id)
    {
        $db = \Config\Database::connect();

        $builder = $db->table('rombel');
        $builder->select('rombel.*, guru_tendik.nama_lengkap as nama_wali_kelas, ta.tahun as nama_tahun_ajaran');
        $builder->join('guru_tendik', 'guru_tendik.id = rombel.wali_kelas_id', 'left');
        $builder->join('tahun_ajaran ta', 'ta.id = rombel.id_tahun_ajaran', 'left');
        $builder->where('rombel.id', $id);
        $rombel = $builder->get()->getRowArray();

        if ($rombel) {
            $siswaList = $db->table('siswa')
                ->select('id, nisn, nama_lengkap, jenis_kelamin')
                ->where('rombel_id', $id)
                ->where('status_siswa', 'Aktif')
                ->orderBy('nama_lengkap', 'ASC')
                ->get()->getResultArray();

            $laki = 0;
            $perempuan = 0;
            foreach ($siswaList as $s) {
                $jk = strtoupper(trim($s['jenis_kelamin']));
                if ($jk == 'L') $laki++;
                if ($jk == 'P') $perempuan++;
            }

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

    public function migrate()
    {
        $idLama = $this->request->getPost('rombel_id_lama');
        $jenisMigrasi = $this->request->getPost('jenis_migrasi');

        if (!$idLama || !$jenisMigrasi) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Data request tidak lengkap!']);
        }

        $db = \Config\Database::connect();

        $rombelLama = $db->table('rombel')->where('id', $idLama)->get()->getRowArray();
        if (!$rombelLama) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Rombel asal tidak ditemukan.']);
        }

        try {
            $db->transException(true);
            $db->transStart();

            if ($jenisMigrasi === 'lulus') {
                $db->table('siswa')
                    ->where('rombel_id', $idLama)
                    ->where('status_siswa', 'Aktif')
                    ->update(['status_siswa' => 'Lulus']);

                $db->table('rombel')
                    ->where('id', $idLama)
                    ->update(['is_lulus' => 1]);

                $msg = "Luar Biasa! Seluruh siswa diluluskan dan Status Rombel menjadi LULUS!";
            } else {
                $idTaBaru = $this->request->getPost('target_tahun_ajaran');
                $tingkatBaru = $this->request->getPost('target_tingkat');
                $namaBaru = $this->request->getPost('target_nama_rombel');
                $pindahSiswa = $this->request->getPost('copy_students');

                if (!$idTaBaru || !$tingkatBaru || !$namaBaru) {
                    return $this->response->setJSON(['status' => 'error', 'message' => 'Lengkapi semua form migrasi!']);
                }

                $cekExisting = $db->table('rombel')
                    ->where('nama_rombel', $namaBaru)
                    ->where('id_tahun_ajaran', $idTaBaru)
                    ->get()->getRowArray();

                if ($cekExisting) {
                    return $this->response->setJSON(['status' => 'error', 'message' => 'Rombel dengan nama "' . $namaBaru . '" sudah ada di Tahun Ajaran / Semester tujuan!']);
                }

                $targetTa = $db->table('tahun_ajaran')->where('id', $idTaBaru)->get()->getRowArray();
                $semesterBaru = $targetTa ? $targetTa['semester'] : 'Ganjil';

                $dataBaru = [
                    'nama_rombel' => $namaBaru,
                    'tingkat' => $tingkatBaru,
                    'id_tahun_ajaran' => $idTaBaru,
                    'wali_kelas_id' => null,
                    'kurikulum_id' => $rombelLama['kurikulum_id'] ?? 1,
                    'semester' => $semesterBaru,
                    'is_lulus' => 0
                ];

                $db->table('rombel')->insert($dataBaru);
                $newRombelId = $db->insertID();

                if ($pindahSiswa === '1') {
                    $db->table('siswa')
                        ->where('rombel_id', $idLama)
                        ->where('status_siswa', 'Aktif')
                        ->update(['rombel_id' => $newRombelId]);

                    // --- 🚀 SUNTIKAN MESIN WAKTU ---
                    // Rekam anak-anak yang baru dipindah ke tabel anggota_rombel
                    $siswaPindah = $db->table('siswa')->select('id')->where('rombel_id', $newRombelId)->where('status_siswa', 'Aktif')->get()->getResultArray();
                    $dataAnggotaBaru = [];
                    foreach ($siswaPindah as $sp) {
                        $dataAnggotaBaru[] = [
                            'siswa_id'        => $sp['id'],
                            'rombel_id'       => $newRombelId,
                            'tahun_ajaran_id' => $idTaBaru,
                            'semester'        => $semesterBaru
                        ];
                    }
                    if (!empty($dataAnggotaBaru)) {
                        $db->table('anggota_rombel')->insertBatch($dataAnggotaBaru);
                    }
                }

                $msg = "Migrasi Berhasil! Kelas & Siswa telah dipindahkan. (Wali Kelas dikosongkan untuk menghindari duplikat)";
            }

            $db->transComplete();

            return $this->response->setJSON(['status' => 'success', 'message' => $msg]);
        } catch (\Throwable $e) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'DB Error: ' . $e->getMessage()]);
        }
    }

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

        // --- 🚀 SUNTIKAN MESIN WAKTU ---
        $taAktif = $db->table('tahun_ajaran')->where('status', 'Aktif')->get()->getRowArray();
        if ($taAktif) {
            $db->table('anggota_rombel')->insert([
                'siswa_id'        => $siswaId,
                'rombel_id'       => $rombelId,
                'tahun_ajaran_id' => $taAktif['id'],
                'semester'        => $taAktif['semester']
            ]);
        }

        return $this->response->setJSON(['status' => 'success', 'message' => 'Siswa berhasil ditambahkan ke kelas']);
    }

    public function removeStudentFromRombel()
    {
        $siswaId = $this->request->getPost('siswa_id');
        if (!$siswaId) return $this->response->setJSON(['status' => 'error', 'message' => 'ID Siswa tidak ditemukan']);

        $db = \Config\Database::connect();
        $db->table('siswa')->where('id', $siswaId)->update(['rombel_id' => null]);

        // --- 🚀 SUNTIKAN MESIN WAKTU ---
        // Kita cabut juga dia dari mesin waktu di tahun ajaran yang sedang aktif
        $taAktif = $db->table('tahun_ajaran')->where('status', 'Aktif')->get()->getRowArray();
        if ($taAktif) {
            $db->table('anggota_rombel')
               ->where('siswa_id', $siswaId)
               ->where('tahun_ajaran_id', $taAktif['id'])
               ->where('semester', $taAktif['semester'])
               ->delete();
        }

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
        $taAktif = $db->table('tahun_ajaran')->where('status', 'Aktif')->get()->getRowArray();
        
        $db->transStart();
        foreach ($siswaIds as $id) {
            // Update tabel utama
            $db->table('siswa')->where('id', $id)->update(['rombel_id' => $targetRombelId]);
            
            // --- 🚀 SUNTIKAN MESIN WAKTU ---
            if ($taAktif) {
                $cek = $db->table('anggota_rombel')->where(['siswa_id' => $id, 'tahun_ajaran_id' => $taAktif['id'], 'semester' => $taAktif['semester']])->get()->getRowArray();
                if ($cek) {
                    $db->table('anggota_rombel')->where('id', $cek['id'])->update(['rombel_id' => $targetRombelId]);
                } else {
                    $db->table('anggota_rombel')->insert([
                        'siswa_id'        => $id,
                        'rombel_id'       => $targetRombelId,
                        'tahun_ajaran_id' => $taAktif['id'],
                        'semester'        => $taAktif['semester']
                    ]);
                }
            }
        }
        $db->transComplete();

        if ($db->transStatus() === false) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Gagal memindahkan siswa']);
        }

        return $this->response->setJSON(['status' => 'success', 'message' => 'Siswa berhasil dipindahkan']);
    }

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

        $builder = $db->table('rombel');
        $builder->select('rombel.*, guru_tendik.nama_lengkap as nama_wali, ta.tahun as nama_tahun_ajaran');
        $builder->join('guru_tendik', 'guru_tendik.id = rombel.wali_kelas_id', 'left');
        $builder->join('tahun_ajaran ta', 'ta.id = rombel.id_tahun_ajaran', 'left');

        // 🚀 SUNTIKAN JOIN KURIKULUM
        if ($db->tableExists('kurikulum')) {
            $builder->select('kurikulum.nama_kurikulum');
            $builder->join('kurikulum', 'kurikulum.id = rombel.kurikulum_id', 'left');
        }

        $builder->orderBy('rombel.id_tahun_ajaran', 'DESC')->orderBy('rombel.tingkat', 'ASC')->orderBy('rombel.nama_rombel', 'ASC');
        $dataRombel = $builder->get()->getResultArray();

        $row = 2;
        foreach ($dataRombel as $r) {
            $sheet->setCellValue('A' . $row, $r['id']);
            $sheet->setCellValue('B' . $row, $r['nama_rombel']);
            $sheet->setCellValue('C' . $row, $r['tingkat']);
            $sheet->setCellValue('D' . $row, $r['nama_kurikulum'] ?? 'Kurikulum Merdeka');
            $sheet->setCellValue('E' . $row, $r['nama_tahun_ajaran'] ?? '-');
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
            $countInsert = 0;
            $countUpdate = 0;

            foreach ($sheet as $idx => $row) {
                if ($idx == 1) continue;

                $namaRombel = trim($row['B'] ?? '');
                $tingkat    = trim($row['C'] ?? '');

                if (empty($namaRombel) || empty($tingkat)) continue;

                $idRombel = $row['A'] ?? null;
                $waliId   = trim($row['D'] ?? '');
                if (empty($waliId) || !is_numeric($waliId)) {
                    $waliId = null;
                }

                // 🚀 CARI ID KURIKULUM DULU (Lebih aman ditaruh di luar array)
                $namaKurikulum = trim($row['E'] ?? 'Kurikulum Merdeka');
                $kurikulum_id_val = 1; // Fallback default ID 1
                
                if ($db->tableExists('kurikulum')) {
                    $k = $db->table('kurikulum')->like('nama_kurikulum', $namaKurikulum, 'both')->get()->getRowArray();
                    if ($k) {
                        $kurikulum_id_val = $k['id'];
                    }
                }

                // BARU MASUKKAN KE DALAM ARRAY DATA
                $data = [
                    'nama_rombel'     => $namaRombel,
                    'tingkat'         => $tingkat,
                    'wali_kelas_id'   => $waliId,
                    'kurikulum_id'    => $kurikulum_id_val,
                    'id_tahun_ajaran' => $idTa,
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

    public function migrateMassal()
    {
        $idTaAsal = $this->request->getPost('asal_tahun_ajaran');
        $idTaTujuan = $this->request->getPost('target_tahun_ajaran');

        if (!$idTaAsal || !$idTaTujuan) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Tahun Ajaran Asal dan Tujuan harus dipilih!']);
        }

        if ($idTaAsal == $idTaTujuan) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Tahun Ajaran Tujuan tidak boleh sama dengan Tahun Ajaran Asal!']);
        }

        $db = \Config\Database::connect();

        $rombels = $db->table('rombel')
            ->where('id_tahun_ajaran', $idTaAsal)
            ->where('is_lulus', 0)
            ->get()->getResultArray();

        if (empty($rombels)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Tidak ada rombel aktif yang bisa dimigrasi pada Tahun Ajaran Asal tersebut.']);
        }

        $targetTa = $db->table('tahun_ajaran')->where('id', $idTaTujuan)->get()->getRowArray();
        $semesterBaru = $targetTa ? $targetTa['semester'] : 'Ganjil';

        try {
            $db->transException(true);
            $db->transStart();

            $countLulus = 0;
            $countNaik = 0;
            $countGagalDuplikat = 0;

            foreach ($rombels as $r) {
                $idLama = $r['id'];
                $tingkatLama = strtoupper(trim($r['tingkat']));
                $namaRombel = trim($r['nama_rombel']);

                if ($tingkatLama == 'IX' || $tingkatLama == '9') {
                    $db->table('siswa')
                        ->where('rombel_id', $idLama)
                        ->where('status_siswa', 'Aktif')
                        ->update(['status_siswa' => 'Lulus']);

                    $db->table('rombel')
                        ->where('id', $idLama)
                        ->update(['is_lulus' => 1]);

                    $countLulus++;
                } else {
                    $tingkatBaru = ($tingkatLama == 'VII' || $tingkatLama == '7') ? 'VIII' : 'IX';

                    $cekExisting = $db->table('rombel')
                        ->where('nama_rombel', $namaRombel)
                        ->where('id_tahun_ajaran', $idTaTujuan)
                        ->get()->getRowArray();

                    if (!$cekExisting) {
                        $dataBaru = [
                            'nama_rombel' => $namaRombel,
                            'tingkat' => $tingkatBaru,
                            'id_tahun_ajaran' => $idTaTujuan,
                            'wali_kelas_id' => null,
                            'kurikulum_id' => $r['kurikulum_id'] ?? 1,
                            'semester' => $semesterBaru,
                            'is_lulus' => 0
                        ];

                        $db->table('rombel')->insert($dataBaru);
                        $newRombelId = $db->insertID();

                        $db->table('siswa')
                            ->where('rombel_id', $idLama)
                            ->where('status_siswa', 'Aktif')
                            ->update(['rombel_id' => $newRombelId]);

                        // --- 🚀 SUNTIKAN MESIN WAKTU ---
                        $siswaPindah = $db->table('siswa')->select('id')->where('rombel_id', $newRombelId)->where('status_siswa', 'Aktif')->get()->getResultArray();
                        $dataAnggotaBaru = [];
                        foreach ($siswaPindah as $sp) {
                            $dataAnggotaBaru[] = [
                                'siswa_id'        => $sp['id'],
                                'rombel_id'       => $newRombelId,
                                'tahun_ajaran_id' => $idTaTujuan,
                                'semester'        => $semesterBaru
                            ];
                        }
                        if (!empty($dataAnggotaBaru)) {
                            $db->table('anggota_rombel')->insertBatch($dataAnggotaBaru);
                        }

                        $countNaik++;
                    } else {
                        $countGagalDuplikat++;
                    }
                }
            }

            $db->transComplete();

            $msg = "Sapu Jagat Berhasil! 🚀<br><b>$countNaik</b> Rombel Naik Tingkat.<br><b>$countLulus</b> Rombel Diluluskan.";
            if ($countGagalDuplikat > 0) {
                $msg .= "<br><br><span class='text-xs text-red-500'>($countGagalDuplikat Rombel dilewati karena nama sudah ada di TA Tujuan).</span>";
            }

            return $this->response->setJSON(['status' => 'success', 'message' => $msg]);
        } catch (\Throwable $e) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'DB Error: ' . $e->getMessage()]);
        }
    }
}
