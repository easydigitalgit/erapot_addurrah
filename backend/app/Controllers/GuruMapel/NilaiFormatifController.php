<?php

namespace App\Controllers\GuruMapel;

use App\Controllers\GuruMapelBaseController;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

class NilaiFormatifController extends GuruMapelBaseController
{
    public function index(): string
    {
        $db = \Config\Database::connect();
        $userId = session()->get('id');

        $dataGuru = $db->table('guru_tendik')->select('id')->where('user_id', $userId)->get()->getRowArray();
        $guruId = $dataGuru ? $dataGuru['id'] : 0;

        // AMBIL ID TAHUN AJARAN AKTIF
        $taAktif = $db->table('tahun_ajaran')->where('status', 'Aktif')->get()->getRowArray();
        $id_ta_aktif = $taAktif ? $taAktif['id'] : 0;
        $semester_aktif = $taAktif ? $taAktif['semester'] : 'Ganjil'; // Tambahan Mesin Waktu

        $builder = $db->table('guru_mapel gm');
        $builder->select('gm.mapel_id, gm.rombel_id, m.nama_mapel, r.nama_rombel as nama_kelas, r.tingkat, u.nama_lengkap as wali_kelas');
        $builder->join('mata_pelajaran m', 'm.id = gm.mapel_id', 'left');
        $builder->join('rombel r', 'r.id = gm.rombel_id', 'left');
        $builder->join('guru_tendik u', 'u.id = r.wali_kelas_id', 'left');
        $builder->where(['gm.guru_id' => $guruId, 'r.id_tahun_ajaran' => $id_ta_aktif]);

        $allPenugasan = $builder->get()->getResultArray();

        $activeRombelId = $this->request->getGet('rombel') ?? ($allPenugasan[0]['rombel_id'] ?? 0);
        $activeMapelId  = $this->request->getGet('mapel')  ?? ($allPenugasan[0]['mapel_id'] ?? 0);

        $assignment = array_filter($allPenugasan, function ($p) use ($activeRombelId, $activeMapelId) {
            return $p['rombel_id'] == $activeRombelId && $p['mapel_id'] == $activeMapelId;
        });
        $assignment = reset($assignment);

        $jumlah_siswa = 0;
        if ($activeRombelId > 0 && $id_ta_aktif > 0) {
            // MENGGUNAKAN MESIN WAKTU
            $jumlah_siswa = $db->table('anggota_rombel ar')
                               ->join('siswa s', 's.id = ar.siswa_id')
                               ->where('ar.rombel_id', $activeRombelId)
                               ->where('ar.tahun_ajaran_id', $id_ta_aktif)
                               ->where('ar.semester', $semester_aktif)
                               ->where('s.status_siswa', 'Aktif')
                               ->countAllResults();
        }

        $tahun_ajaran_list = $db->table('tahun_ajaran')->orderBy('id', 'DESC')->get()->getResultArray();

        $data = [
            'user'        => session()->get('nama_lengkap') ?? session()->get('username') ?? 'Guru Mapel',
            'navigations' => $this->getSidebarMenu(),
            'color'       => $this->getColor(),
            'allRombel'   => $allPenugasan,
            'tahun_ajaran_list' => $tahun_ajaran_list,
            'info'        => [
                'mapel_id'   => $activeMapelId,
                'rombel_id'  => $activeRombelId,
                'mapel_nama' => $assignment['nama_mapel'] ?? 'Belum Pilih Mapel',
                'kelas_nama' => ($assignment['tingkat'] ?? '') . ' ' . ($assignment['nama_kelas'] ?? 'Belum Pilih Kelas'),
                'wali_kelas' => $assignment['wali_kelas'] ?? 'Belum Diset',
                'jml_siswa'  => $jumlah_siswa
            ]
        ];

        return view('GuruMapel/nilai-formatif', $data);
    }

    private function _getLmData($mapel_id, $rombel_id, $semester, $kategori, $tahun_ajaran_id = null)
    {
        $db = \Config\Database::connect();
        if (!$db->tableExists('master_lm')) return [];

        $rombelInfo = $db->table('rombel')->where('id', $rombel_id)->get()->getRowArray();
        $tingkatClean = 0;

        if ($rombelInfo) {
            $tingkatStr = strtoupper(trim((string)$rombelInfo['tingkat']));
            $angka = preg_replace('/[^0-9]/', '', $tingkatStr);
            if (!empty($angka)) {
                $tingkatClean = (int) $angka;
            } else {
                if (preg_match('/\b(VII|VIII|IX|X|XI|XII)\b/', $tingkatStr, $matches)) {
                    $romToNum = ['VII' => 7, 'VIII' => 8, 'IX' => 9, 'X' => 10, 'XI' => 11, 'XII' => 12];
                    $tingkatClean = $romToNum[$matches[1]] ?? 0;
                }
            }
        }

        if (empty($tingkatClean)) return [];

        $tingkatList = [$tingkatClean];
        if (!empty($rombelInfo['tingkat'])) {
            $tingkatList[] = trim((string)$rombelInfo['tingkat']);
        }
        $tingkatList = array_unique(array_filter($tingkatList));

        $bLm = $db->table('master_lm')
            ->where('mapel_id', $mapel_id)
            ->whereIn('tingkat', $tingkatList)
            ->where('semester', $semester);

        if ($tahun_ajaran_id) {
            $bLm->groupStart()
                ->where('tahun_ajaran_id', $tahun_ajaran_id)
                ->orWhere('tahun_ajaran_id', 0)
                ->orWhere('tahun_ajaran_id', null)
                ->groupEnd();
        }

        if ($db->fieldExists('kategori', 'master_lm')) {
            $bLm->groupStart();
            if (stripos($kategori, 'tengah') !== false) {
                $bLm->whereIn('kategori', ['Tengah', 'STS', 'PTS']);
            } else {
                $bLm->whereIn('kategori', ['Akhir', 'SAS', 'PAS']);
            }
            $bLm->groupEnd();
        }

        if ($db->fieldExists('status', 'master_lm')) {
            $bLm->where('status', 'Aktif');
        }

        return $bLm->orderBy('id', 'ASC')->get()->getResultArray();
    }

    public function getJumlahLmDinamis()
    {
        if (ob_get_length()) ob_clean();

        $rombel_id = $this->request->getGet('rombel_id');
        $mapel_id  = $this->request->getGet('mapel_id');
        $kategori  = $this->request->getGet('kategori');
        $ta_id     = $this->request->getGet('ta_id');

        $db = \Config\Database::connect();
        $taData = $db->table('tahun_ajaran')->where('id', $ta_id)->get()->getRowArray();
        $semesterAktif = $taData ? $taData['semester'] : 'Ganjil';

        $allLMs = $this->_getLmData($mapel_id, $rombel_id, $semesterAktif, $kategori, $ta_id);

        $pertemuan_list = [];
        foreach ($allLMs as $lm) {
            $angka_lm = (int) preg_replace('/[^0-9]/', '', $lm['kode_lm']);
            if ($angka_lm > 0) {
                $pertemuan_list[] = [
                    'pertemuan' => $angka_lm,
                    'materi'    => $lm['deskripsi_lm'] ?? 'Materi Belum Diisi'
                ];
            }
        }

        usort($pertemuan_list, function ($a, $b) {
            return $a['pertemuan'] <=> $b['pertemuan'];
        });

        return $this->response->setJSON([
            'status' => 'success',
            'pertemuan_list' => $pertemuan_list
        ]);
    }

    public function getAssignmentsByYear()
    {
        if (ob_get_length()) ob_clean();
        $db = \Config\Database::connect();
        $userId = session()->get('id');
        $ta_id  = $this->request->getGet('ta_id');

        $dataGuru = $db->table('guru_tendik')->select('id')->where('user_id', $userId)->get()->getRowArray();
        $guruId = $dataGuru ? $dataGuru['id'] : 0;

        $builder = $db->table('guru_mapel gm');
        $builder->select('gm.mapel_id, gm.rombel_id, m.nama_mapel, r.nama_rombel as nama_kelas, r.tingkat, u.nama_lengkap as wali_kelas');
        $builder->join('mata_pelajaran m', 'm.id = gm.mapel_id', 'left');
        $builder->join('rombel r', 'r.id = gm.rombel_id', 'left');
        $builder->join('guru_tendik u', 'u.id = r.wali_kelas_id', 'left');
        $builder->where(['gm.guru_id' => $guruId, 'gm.tahun_ajaran_id' => $ta_id]);

        $assignments = $builder->get()->getResultArray();

        return $this->response->setJSON([
            'status' => 'success',
            'data'   => $assignments
        ]);
    }

    public function getStudentsOnly()
    {
        if (ob_get_length()) ob_clean();
        try {
            $db = \Config\Database::connect();
            $rombel_id = $this->request->getGet('rombel_id');
            // Tangkap parameter mesin waktu (pastikan parameter ini dikirim oleh JS frontend Anda)
            $ta_id = $this->request->getGet('ta_id'); 

            if (!$rombel_id) return $this->response->setJSON(['status' => 'error', 'message' => 'Kelas tidak valid']);

            $ta_data = $db->table('tahun_ajaran')->where('id', $ta_id)->get()->getRowArray();
            $semester = $ta_data ? $ta_data['semester'] : 'Ganjil';

            // MENGGUNAKAN MESIN WAKTU
            $builder = $db->table('anggota_rombel ar');
            $builder->select('s.id, s.nis, s.nama_lengkap as name');
            $builder->join('siswa s', 's.id = ar.siswa_id');
            $builder->where('ar.rombel_id', $rombel_id);
            $builder->where('ar.tahun_ajaran_id', $ta_id);
            $builder->where('ar.semester', $semester);

            if ($db->fieldExists('status_siswa', 'siswa')) {
                $builder->where('s.status_siswa', 'Aktif');
            }
            $siswas = $builder->orderBy('s.nama_lengkap', 'ASC')->get()->getResultArray();

            return $this->response->setJSON(['status' => 'success', 'data' => $siswas]);
        } catch (\Throwable $e) {
            return $this->response->setJSON(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    public function getGrades()
    {
        if (ob_get_length()) ob_clean();
        try {
            $db = \Config\Database::connect();

            $jenis            = $this->request->getGet('jenis');
            $pertemuan        = (int)$this->request->getGet('pertemuan');
            $kelas_id         = (int)$this->request->getGet('rombel_id');
            $mapel_id         = (int)$this->request->getGet('mapel_id');
            $tahun_ajaran_id  = (int)$this->request->getGet('tahun_ajaran_id');
            $kategori         = $this->request->getGet('kategori') ?: 'Akhir Semester';
            $kategoriDB       = (stripos($kategori, 'tengah') !== false) ? 'Tengah' : 'Akhir';

            $ta_data = $db->table('tahun_ajaran')->where('id', $tahun_ajaran_id)->get()->getRowArray();
            $semester = $ta_data ? $ta_data['semester'] : 'Ganjil';

            if ($jenis === 'Nilai Harian') $jenis = 'Tugas';
            if ($jenis === 'Ulangan Harian') $jenis = 'Ulangan';

            $nilais = [];
            if ($db->tableExists('nilai_formatif')) {
                $bFormatif = $db->table('nilai_formatif')->where([
                    'mapel_id'        => $mapel_id,
                    'rombel_id'       => $kelas_id,
                    'jenis_penilaian' => $jenis,
                    'pertemuan'       => $pertemuan,
                    'tahun_ajaran_id' => $tahun_ajaran_id,
                    'semester'        => $semester
                ]);

                // Mencegah nilai tidak terbaca jika kategori di DB blank/kosong (Toleransi Error DB)
                if ($db->fieldExists('kategori', 'nilai_formatif')) {
                    $bFormatif->groupStart()
                        ->where('kategori', $kategoriDB)
                        ->orWhere('kategori', $kategori)
                        ->orWhere('kategori', '')
                        ->orWhere('kategori', null)
                        ->groupEnd();
                }

                $nilais = $bFormatif->get()->getResultArray();
            }

            $allLMs = $this->_getLmData($mapel_id, $kelas_id, $semester, $kategori, $tahun_ajaran_id);
            $lmData = null;
            foreach ($allLMs as $lm) {
                $angka_lm = (int) preg_replace('/[^0-9]/', '', $lm['kode_lm']);
                if ($angka_lm === $pertemuan) {
                    $lmData = $lm;
                    break;
                }
            }

            return $this->response->setJSON([
                'status' => 'success',
                'data' => $nilais,
                'lm' => $lmData
            ]);
        } catch (\Throwable $e) {
            return $this->response->setJSON(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    public function saveNilai()
    {
        if (ob_get_length()) ob_clean();
        try {
            $json = $this->request->getJSON();
            if (!$json || empty($json->nilaiData)) return $this->response->setJSON(['status' => 'error', 'message' => 'Data kosong']);

            $db = \Config\Database::connect();
            $userId = session()->get('id');
            $dataGuru = $db->table('guru_tendik')->select('id')->where('user_id', $userId)->get()->getRowArray();
            $guru_id = $dataGuru ? $dataGuru['id'] : 0;

            $mapel_id = (int)($json->mapel_id ?? 0);
            $rombel_id = (int)($json->rombel_id ?? 0);
            $tahun_ajaran_id = (int)($json->tahun_ajaran_id ?? 0);
            $kategori = $json->kategori ?? 'Tengah Semester';
            $kategori_db = (stripos($kategori, 'tengah') !== false) ? 'Tengah' : 'Akhir';

            $ta_data = $db->table('tahun_ajaran')->where('id', $tahun_ajaran_id)->get()->getRowArray();
            $semester = $ta_data ? $ta_data['semester'] : 'Ganjil';

            $status_simpan = $json->status_simpan ?? 'draft';
            $jenis = $json->jenis_penilaian;
            $pertemuan = (int)$json->pertemuan;

            if ($jenis === 'Nilai Harian') $jenis = 'Tugas';
            if ($jenis === 'Ulangan Harian') $jenis = 'Ulangan';

            if (!$db->tableExists('nilai_formatif')) {
                return $this->response->setJSON(['status' => 'error', 'message' => "Tabel nilai_formatif tidak ditemukan di Database!"]);
            }

            $allLMs = $this->_getLmData($mapel_id, $rombel_id, $semester, $kategori);
            $lmData = null;
            foreach ($allLMs as $lm) {
                $angka_lm = (int) preg_replace('/[^0-9]/', '', $lm['kode_lm']);
                if ($angka_lm === $pertemuan) {
                    $lmData = $lm;
                    break;
                }
            }

            $berhasilDisimpan = 0;

            foreach ($json->nilaiData as $siswa_id => $data) {
                if ($data->nilai === "") {
                    $nilai_angka = null;
                    $predikat = null;
                } else {
                    $angka = (int)$data->nilai;
                    if ($angka > 100) $angka = 100;
                    if ($angka < 0) $angka = 0;

                    $nilai_angka = $angka;
                    $predikat = $data->predikat;
                }

                $keterangan = trim((string)($data->keterangan ?? ''));
                if (empty($keterangan) && $lmData && $predikat && $predikat !== '-') {
                    $key = 'deskripsi_' . strtolower($predikat);
                    if (isset($lmData[$key]) && trim((string)$lmData[$key]) !== '') {
                        $keterangan = trim((string)$lmData[$key]);
                    } elseif (isset($lmData['deskripsi_lm']) && trim((string)$lmData['deskripsi_lm']) !== '') {
                        $materi = trim((string)$lmData['deskripsi_lm']);
                        if ($predikat === 'A') $keterangan = "Sangat baik memahami " . $materi;
                        elseif ($predikat === 'B') $keterangan = "Baik memahami " . $materi;
                        elseif ($predikat === 'C') $keterangan = "Cukup memahami " . $materi;
                        else $keterangan = "Perlu bimbingan memahami " . $materi;
                    }
                }

                $payload = [
                    'siswa_id'          => (int)$siswa_id,
                    'guru_id'           => $guru_id,
                    'mapel_id'          => $mapel_id,
                    'rombel_id'         => $rombel_id,
                    'nilai_angka'       => $nilai_angka,
                    'predikat'          => $predikat,
                    'catatan'           => $keterangan,
                    'tahun_ajaran_id'   => $tahun_ajaran_id,
                    'semester'          => $semester,
                    'jenis_penilaian'   => $jenis,
                    'pertemuan'         => $pertemuan,
                    'status_simpan'     => $status_simpan
                ];

                if ($db->fieldExists('kategori', 'nilai_formatif')) $payload['kategori'] = $kategori_db;
                if ($db->fieldExists('nilai_keterampilan', 'nilai_formatif')) $payload['nilai_keterampilan'] = 0;

                // KUNCI PERBAIKAN: RESET QUERY BUILDER DI DALAM PERULANGAN
                $dbTable = $db->table('nilai_formatif');
                $qCek = $dbTable->where([
                    'siswa_id' => (int)$siswa_id,
                    'mapel_id' => $mapel_id,
                    'jenis_penilaian' => $jenis,
                    'pertemuan' => $pertemuan,
                    'tahun_ajaran_id' => $tahun_ajaran_id,
                    'semester' => $semester
                ]);

                if ($db->fieldExists('kategori', 'nilai_formatif')) {
                    $qCek->groupStart()
                        ->where('kategori', $kategori_db)
                        ->orWhere('kategori', $kategori)
                        ->orWhere('kategori', '')
                        ->orWhere('kategori', null)
                        ->groupEnd();
                }

                $cek = $qCek->get()->getRowArray();

                if ($cek) {
                    $db->table('nilai_formatif')->where('id', $cek['id'])->update($payload);
                } else {
                    if ($data->nilai !== "" || $keterangan !== "") {
                        $db->table('nilai_formatif')->insert($payload);
                    }
                }
                $berhasilDisimpan++;
            }
            return $this->response->setJSON(['status' => 'success', 'message' => "Disimpan!"]);
        } catch (\Throwable $e) {
            return $this->response->setJSON(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    // ==========================================================
    // FUNGSI EXPORT/IMPORT EXCEL TIDAK BERUBAH (HANYA DIPERBAIKI QUERY BUILDER NYA SAJA)
    // ==========================================================
    public function downloadTemplate()
    {
        if (ob_get_length()) ob_clean();
        $db = \Config\Database::connect();

        $rombel_id = $this->request->getGet('rombel_id');
        $mapel_id  = $this->request->getGet('mapel_id');
        $jenis     = $this->request->getGet('jenis');
        $kategori  = $this->request->getGet('kategori');
        $pertemuan = $this->request->getGet('pertemuan');

        if (!$rombel_id || !$mapel_id) die("Parameter kelas atau mata pelajaran tidak ditemukan.");

        $rombel = $db->table('rombel')->where('id', $rombel_id)->get()->getRowArray();
        $mapel = $db->table('mata_pelajaran')->where('id', $mapel_id)->get()->getRowArray();
        
        // Karena template 1 pertemuan di frontend biasanya ikut TA aktif/terpilih, 
        // kita tangkap ta_id-nya (pastikan di JS dikirim juga, atau fallback ke TA Aktif)
        $ta_id = $this->request->getGet('ta_id');
        if (!$ta_id) {
            $taAktif = $db->table('tahun_ajaran')->where('status', 'Aktif')->get()->getRowArray();
            $ta_id = $taAktif ? $taAktif['id'] : 0;
            $semester = $taAktif ? $taAktif['semester'] : 'Ganjil';
        } else {
            $taData = $db->table('tahun_ajaran')->where('id', $ta_id)->get()->getRowArray();
            $semester = $taData ? $taData['semester'] : 'Ganjil';
        }

        // MENGGUNAKAN MESIN WAKTU
        $builder = $db->table('anggota_rombel ar')
                      ->select('s.*') // Ambil semua data siswa untuk template
                      ->join('siswa s', 's.id = ar.siswa_id')
                      ->where('ar.rombel_id', $rombel_id)
                      ->where('ar.tahun_ajaran_id', $ta_id)
                      ->where('ar.semester', $semester);
                      
        if ($db->fieldExists('status_siswa', 'siswa')) $builder->where('s.status_siswa', 'Aktif');
        $siswas = $builder->orderBy('s.nama_lengkap', 'ASC')->get()->getResultArray();

        // 👇 TIGA BARIS DI BAWAH INI HARUS DIHAPUS KARENA INI KODE LAMA 👇
        $builder = $db->table('siswa')->where('rombel_id', $rombel_id);
        if ($db->fieldExists('status_siswa', 'siswa')) $builder->where('status_siswa', 'Aktif');
        $siswas = $builder->orderBy('nama_lengkap', 'ASC')->get()->getResultArray();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Template Nilai Formatif');

        $sheet->setCellValue('A1', 'FORMAT IMPORT NILAI FORMATIF (1 PERTEMUAN)');
        $sheet->setCellValue('A2', 'Mata Pelajaran: ' . ($mapel['nama_mapel'] ?? ''));
        $sheet->setCellValue('A3', 'Kelas: ' . ($rombel['nama_rombel'] ?? ''));
        $sheet->setCellValue('A4', 'Jenis: ' . $jenis . ' (' . $kategori . ') - Pertemuan Ke-' . $pertemuan);
        $sheet->getStyle('A1:A4')->getFont()->setBold(true);

        $sheet->setCellValue('A6', 'NO');
        $sheet->setCellValue('B6', 'NIS (JANGAN DIUBAH)');
        $sheet->setCellValue('C6', 'NAMA SISWA (JANGAN DIUBAH)');
        $sheet->setCellValue('D6', 'NILAI ANGKA (0-100)');
        $sheet->setCellValue('E6', 'CATATAN / MATERI');

        $headerStyle = [
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => '10B981']],
            'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]
        ];
        $sheet->getStyle('A6:E6')->applyFromArray($headerStyle);

        $row = 7;
        $no = 1;
        foreach ($siswas as $s) {
            $sheet->setCellValue('A' . $row, $no);
            $sheet->setCellValueExplicit('B' . $row, $s['nis'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet->setCellValue('C' . $row, $s['nama_lengkap']);
            $sheet->getStyle('A' . $row . ':C' . $row)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FFF3F4F6');
            $row++;
            $no++;
        }

        foreach (range('A', 'E') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $namaKelas = preg_replace('/[^A-Za-z0-9\-]/', '_', ($rombel['nama_rombel'] ?? 'Kelas'));
        $filename = 'Format_Nilai_' . $jenis . '_' . str_replace(' ', '_', $kategori) . '_Pertemuan_' . $pertemuan . '_' . $namaKelas . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
        exit;
    }

    public function importExcel()
    {
        if (ob_get_length()) ob_clean();
        try {
            $db = \Config\Database::connect();
            $file = $this->request->getFile('file_excel');
            if (!$file || !$file->isValid()) return $this->response->setJSON(['status' => 'error', 'message' => 'File Excel tidak valid']);

            $mapel_id  = (int)$this->request->getPost('mapel_id');
            $rombel_id = (int)$this->request->getPost('rombel_id');
            $jenis     = $this->request->getPost('jenis_penilaian');
            $kategori  = $this->request->getPost('kategori');
            $kategori_db = (stripos($kategori, 'tengah') !== false) ? 'Tengah' : 'Akhir';
            $pertemuan = (int)$this->request->getPost('pertemuan');
            $tahun_ajaran_id = (int)$this->request->getPost('tahun_ajaran_id');
            $kkm       = (int)$this->request->getPost('kkm') ?: 75;

            if ($jenis === 'Nilai Harian') $jenis = 'Tugas';
            if ($jenis === 'Ulangan Harian') $jenis = 'Ulangan';

            if (!$jenis || !$pertemuan || !$kategori) return $this->response->setJSON(['status' => 'error', 'message' => 'Parameter tidak lengkap.']);

            $ta_data = $db->table('tahun_ajaran')->where('id', $tahun_ajaran_id)->get()->getRowArray();
            $semester = $ta_data ? $ta_data['semester'] : 'Ganjil';

            $userId = session()->get('id');
            $dataGuru = $db->table('guru_tendik')->select('id')->where('user_id', $userId)->get()->getRowArray();
            $guru_id = $dataGuru ? $dataGuru['id'] : 0;

            $allLMs = $this->_getLmData($mapel_id, $rombel_id, $semester, $kategori);
            $lmData = null;
            foreach ($allLMs as $lm) {
                $angka_lm = (int) preg_replace('/[^0-9]/', '', $lm['kode_lm']);
                if ($angka_lm === $pertemuan) {
                    $lmData = $lm;
                    break;
                }
            }

            $spreadsheet = IOFactory::load($file->getTempName());
            $sheet = $spreadsheet->getActiveSheet();
            $highestRow = $sheet->getHighestDataRow();

            $berhasilDisimpan = 0;

            $siswas = $db->table('siswa')->where('rombel_id', $rombel_id)->get()->getResultArray();
            $nisToId = [];
            foreach ($siswas as $s) {
                $nisToId[$s['nis']] = $s['id'];
            }

            for ($row = 7; $row <= $highestRow; $row++) {
                $nis     = trim((string)$sheet->getCell('B' . $row)->getValue());
                $nilai   = trim((string)$sheet->getCell('D' . $row)->getValue());
                $catatan = trim((string)$sheet->getCell('E' . $row)->getValue());

                if (empty($nis) || $nilai === '' || !is_numeric($nilai)) continue;

                if (isset($nisToId[$nis])) {
                    $siswa_id = $nisToId[$nis];
                    $angka = (int)$nilai;

                    if ($angka > 100) $angka = 100;
                    if ($angka < 0) $angka = 0;

                    $predikat = '-';
                    if ($angka >= 90) $predikat = 'A';
                    elseif ($angka >= 80) $predikat = 'B';
                    elseif ($angka >= $kkm) $predikat = 'C';
                    else $predikat = 'D';

                    if (empty($catatan) && $lmData && $predikat !== '-') {
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

                    $payload = [
                        'siswa_id'          => (int)$siswa_id,
                        'guru_id'           => $guru_id,
                        'mapel_id'          => $mapel_id,
                        'rombel_id'         => $rombel_id,
                        'nilai_angka'       => $angka,
                        'predikat'          => $predikat,
                        'catatan'           => $catatan,
                        'tahun_ajaran_id'   => $tahun_ajaran_id,
                        'semester'          => $semester,
                        'jenis_penilaian'   => $jenis,
                        'pertemuan'         => $pertemuan,
                        'status_simpan'     => 'draft'
                    ];

                    if ($db->fieldExists('kategori', 'nilai_formatif')) $payload['kategori'] = $kategori_db;
                    if ($db->fieldExists('nilai_keterampilan', 'nilai_formatif')) $payload['nilai_keterampilan'] = 0;

                    // RESET BUILDER AGAR TIDAK NUMPUK
                    $dbTable = $db->table('nilai_formatif');
                    $qCek = $dbTable->where([
                        'siswa_id' => (int)$siswa_id,
                        'mapel_id' => $mapel_id,
                        'jenis_penilaian' => $jenis,
                        'pertemuan' => $pertemuan,
                        'tahun_ajaran_id' => $tahun_ajaran_id,
                        'semester' => $semester
                    ]);
                    if ($db->fieldExists('kategori', 'nilai_formatif')) {
                        $qCek->groupStart()
                            ->where('kategori', $kategori_db)
                            ->orWhere('kategori', $kategori)
                            ->orWhere('kategori', '')
                            ->orWhere('kategori', null)
                            ->groupEnd();
                    }

                    $cek = $qCek->get()->getRowArray();

                    if ($cek) {
                        $db->table('nilai_formatif')->where('id', $cek['id'])->update($payload);
                    } else {
                        $db->table('nilai_formatif')->insert($payload);
                    }
                    $berhasilDisimpan++;
                }
            }

            return $this->response->setJSON(['status' => 'success', 'message' => "Import berhasil! $berhasilDisimpan nilai tersimpan."]);
        } catch (\Throwable $e) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Error Sistem: ' . $e->getMessage()]);
        }
    }

    public function downloadTemplateAll()
    {
        if (ob_get_length()) ob_clean();
        $db = \Config\Database::connect();

        $rombel_id = $this->request->getGet('rombel_id');
        $mapel_id  = $this->request->getGet('mapel_id');
        $jenis     = $this->request->getGet('jenis');
        $kategori  = $this->request->getGet('kategori');
        $ta_id     = $this->request->getGet('ta_id');

        if (!$rombel_id || !$mapel_id || !$kategori) die("Parameter tidak lengkap.");

        $rombel = $db->table('rombel')->where('id', $rombel_id)->get()->getRowArray();
        $mapel = $db->table('mata_pelajaran')->where('id', $mapel_id)->get()->getRowArray();
        $taData = $db->table('tahun_ajaran')->where('id', $ta_id)->get()->getRowArray();
        $semester = $taData ? $taData['semester'] : 'Ganjil';

        // MENGGUNAKAN MESIN WAKTU
        $builder = $db->table('anggota_rombel ar')
                      ->select('s.*')
                      ->join('siswa s', 's.id = ar.siswa_id')
                      ->where('ar.rombel_id', $rombel_id)
                      ->where('ar.tahun_ajaran_id', $ta_id)
                      ->where('ar.semester', $semester);
                      
        if ($db->fieldExists('status_siswa', 'siswa')) $builder->where('s.status_siswa', 'Aktif');
        $siswas = $builder->orderBy('s.nama_lengkap', 'ASC')->get()->getResultArray();

        $allLMs = $this->_getLmData($mapel_id, $rombel_id, $semester, $kategori, $ta_id);
        $pertemuan_list = [];
        foreach ($allLMs as $lm) {
            $angka_lm = (int) preg_replace('/[^0-9]/', '', $lm['kode_lm']);
            if ($angka_lm > 0 && !in_array($angka_lm, $pertemuan_list)) {
                $pertemuan_list[] = $angka_lm;
            }
        }
        sort($pertemuan_list);
        if (empty($pertemuan_list)) $pertemuan_list = [1];

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Template Semua Pertemuan');

        $sheet->setCellValue('A1', 'FORMAT IMPORT NILAI FORMATIF (SEMUA PERTEMUAN)');
        $sheet->setCellValue('A2', 'Mata Pelajaran: ' . ($mapel['nama_mapel'] ?? ''));
        $sheet->setCellValue('A3', 'Kelas: ' . ($rombel['nama_rombel'] ?? ''));
        $sheet->setCellValue('A4', 'Jenis Penilaian: ' . $jenis . ' (' . $kategori . ')');
        $sheet->setCellValue('A5', '*Keterangan deskripsi akan diisi otomatis oleh sistem berdasarkan Master LM.');
        $sheet->getStyle('A1:A4')->getFont()->setBold(true);
        $sheet->getStyle('A5')->getFont()->setItalic(true)->getColor()->setARGB('FFef4444');

        $sheet->setCellValue('A7', 'NO');
        $sheet->setCellValue('B7', 'NIS (JANGAN DIUBAH)');
        $sheet->setCellValue('C7', 'NAMA SISWA (JANGAN DIUBAH)');

        $colIndex = 4;
        foreach ($pertemuan_list as $p) {
            $colLtr = Coordinate::stringFromColumnIndex($colIndex);
            $sheet->setCellValue($colLtr . '7', 'NILAI PERTEMUAN ' . $p);
            $colIndex++;
        }

        $lastCol = Coordinate::stringFromColumnIndex($colIndex - 1);

        $headerStyle = [
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => '3b82f6']],
            'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]
        ];
        $sheet->getStyle('A7:' . $lastCol . '7')->applyFromArray($headerStyle);

        $row = 8;
        $no = 1;
        foreach ($siswas as $s) {
            $sheet->setCellValue('A' . $row, $no);
            $sheet->setCellValueExplicit('B' . $row, $s['nis'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet->setCellValue('C' . $row, $s['nama_lengkap']);
            $sheet->getStyle('A' . $row . ':C' . $row)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FFF3F4F6');
            $row++;
            $no++;
        }

        foreach (range('A', $lastCol) as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $namaKelas = preg_replace('/[^A-Za-z0-9\-]/', '_', ($rombel['nama_rombel'] ?? 'Kelas'));
        $filename = 'Format_Nilai_Global_' . str_replace(' ', '_', $kategori) . '_' . $namaKelas . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
        exit;
    }

    public function importExcelAll()
    {
        if (ob_get_length()) ob_clean();
        try {
            $db = \Config\Database::connect();
            $file = $this->request->getFile('file_excel_all');
            if (!$file || !$file->isValid()) return $this->response->setJSON(['status' => 'error', 'message' => 'File Excel tidak valid']);

            $mapel_id  = (int)$this->request->getPost('mapel_id');
            $rombel_id = (int)$this->request->getPost('rombel_id');
            $jenis     = $this->request->getPost('jenis_penilaian');
            $kategori  = $this->request->getPost('kategori');
            $kategori_db = (stripos($kategori, 'tengah') !== false) ? 'Tengah' : 'Akhir';
            $tahun_ajaran_id = (int)$this->request->getPost('tahun_ajaran_id');
            $kkm       = (int)$this->request->getPost('kkm') ?: 75;

            if ($jenis === 'Nilai Harian') $jenis = 'Tugas';
            if ($jenis === 'Ulangan Harian') $jenis = 'Ulangan';

            if (!$jenis || !$kategori) return $this->response->setJSON(['status' => 'error', 'message' => 'Parameter tidak lengkap.']);

            $ta_data = $db->table('tahun_ajaran')->where('id', $tahun_ajaran_id)->get()->getRowArray();
            $semester = $ta_data ? $ta_data['semester'] : 'Ganjil';

            $userId = session()->get('id');
            $dataGuru = $db->table('guru_tendik')->select('id')->where('user_id', $userId)->get()->getRowArray();
            $guru_id = $dataGuru ? $dataGuru['id'] : 0;

            $allLMs = $this->_getLmData($mapel_id, $rombel_id, $semester, $kategori, $tahun_ajaran_id);
            $lmCache = [];
            foreach ($allLMs as $lm) {
                $angka_lm = (int) preg_replace('/[^0-9]/', '', $lm['kode_lm']);
                if ($angka_lm > 0) {
                    $lmCache[$angka_lm] = $lm;
                }
            }

            $spreadsheet = IOFactory::load($file->getTempName());
            $sheet = $spreadsheet->getActiveSheet();
            $highestRow = $sheet->getHighestDataRow();
            $highestColumnIndex = Coordinate::columnIndexFromString($sheet->getHighestDataColumn());

            $colMap = [];
            for ($c = 4; $c <= $highestColumnIndex; $c++) {
                $colLtr = Coordinate::stringFromColumnIndex($c);
                $headerVal = trim((string)$sheet->getCell($colLtr . '7')->getValue());

                if (preg_match('/(?:PERTEMUAN|LM)\s+(\d+)/i', $headerVal, $m)) {
                    $colMap[$colLtr] = (int)$m[1];
                }
            }

            $berhasilDisimpan = 0;

            $siswas = $db->table('siswa')->where('rombel_id', $rombel_id)->get()->getResultArray();
            $nisToId = [];
            foreach ($siswas as $s) {
                $nisToId[$s['nis']] = $s['id'];
            }

            for ($row = 8; $row <= $highestRow; $row++) {
                $nis = trim((string)$sheet->getCell('B' . $row)->getValue());
                if (empty($nis) || !isset($nisToId[$nis])) continue;
                $siswa_id = $nisToId[$nis];

                $hasUpdate = false;

                foreach ($colMap as $col => $pertemuan_ke) {
                    $nilai = trim((string)$sheet->getCell($col . $row)->getValue());
                    if ($nilai === '' || !is_numeric($nilai)) continue;

                    $angka = (int)$nilai;
                    if ($angka > 100) $angka = 100;
                    if ($angka < 0) $angka = 0;

                    $predikat = '-';
                    if ($angka >= 90) $predikat = 'A';
                    elseif ($angka >= 80) $predikat = 'B';
                    elseif ($angka >= $kkm) $predikat = 'C';
                    else $predikat = 'D';

                    $catatan = "";
                    if (isset($lmCache[$pertemuan_ke]) && $predikat !== '-') {
                        $lmData = $lmCache[$pertemuan_ke];
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

                    $payload = [
                        'siswa_id'          => (int)$siswa_id,
                        'guru_id'           => $guru_id,
                        'mapel_id'          => $mapel_id,
                        'rombel_id'         => $rombel_id,
                        'nilai_angka'       => $angka,
                        'predikat'          => $predikat,
                        'catatan'           => $catatan,
                        'tahun_ajaran_id'   => $tahun_ajaran_id,
                        'semester'          => $semester,
                        'jenis_penilaian'   => $jenis,
                        'pertemuan'         => $pertemuan_ke,
                        'status_simpan'     => 'draft'
                    ];

                    if ($db->fieldExists('kategori', 'nilai_formatif')) $payload['kategori'] = $kategori_db;
                    if ($db->fieldExists('nilai_keterampilan', 'nilai_formatif')) $payload['nilai_keterampilan'] = 0;

                    // RESET BUILDER AGAR TIDAK NUMPUK
                    $dbTable = $db->table('nilai_formatif');
                    $qCek = $dbTable->where([
                        'siswa_id' => (int)$siswa_id,
                        'mapel_id' => $mapel_id,
                        'jenis_penilaian' => $jenis,
                        'pertemuan' => $pertemuan_ke,
                        'tahun_ajaran_id' => $tahun_ajaran_id,
                        'semester' => $semester
                    ]);

                    if ($db->fieldExists('kategori', 'nilai_formatif')) {
                        $qCek->groupStart()
                            ->where('kategori', $kategori_db)
                            ->orWhere('kategori', $kategori)
                            ->orWhere('kategori', '')
                            ->orWhere('kategori', null)
                            ->groupEnd();
                    }

                    $cek = $qCek->get()->getRowArray();

                    if ($cek) {
                        $db->table('nilai_formatif')->where('id', $cek['id'])->update($payload);
                    } else {
                        $db->table('nilai_formatif')->insert($payload);
                    }
                    $hasUpdate = true;
                }

                if ($hasUpdate) $berhasilDisimpan++;
            }

            return $this->response->setJSON(['status' => 'success', 'message' => "Import Global berhasil! $berhasilDisimpan siswa tersimpan."]);
        } catch (\Throwable $e) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Error Sistem: ' . $e->getMessage()]);
        }
    }
}
