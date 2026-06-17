<?php
namespace App\Controllers\WaliKelas;

use App\Controllers\WaliKelasBaseController;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class CetakLegerController extends WaliKelasBaseController
{
    public function index(): string
    {
        $db = \Config\Database::connect();
        
        $list_mapel = $db->table('mata_pelajaran')
                         ->where('status', 'Aktif')
                         ->where('nama_mapel !=', 'BPI')
                         ->orderBy('kelompok', 'ASC')
                         ->orderBy('id', 'ASC')
                         ->get()
                         ->getResultArray();
        
        $ta_master = $db->table('tahun_ajaran')->orderBy('id', 'DESC')->get()->getResultArray();
        $list_ta_smt = [];
        
        foreach ($ta_master as $ta) {
            $teks = $ta['tahun'] . ' - ' . $ta['semester'];
            if ($ta['status'] === 'Aktif') {
                $teks .= ' (Aktif)';
            }
            
            $list_ta_smt[] = [
                'value' => $ta['id'] . '|' . $ta['tahun'] . '|' . $ta['semester'],
                'text'  => $teks
            ];
        }
        
        $getTaSmt = $this->request->getGet('ta');
        if ($getTaSmt) {
            $parts = explode('|', $getTaSmt);
            $idTaAktif = $parts[0] ?? 0;
            $ta_terpilih = $getTaSmt;
        } else {
            $ta_aktif_db = $db->table('tahun_ajaran')->where('status', 'Aktif')->get()->getRowArray();
            $idTaAktif = $ta_aktif_db ? $ta_aktif_db['id'] : 0;
            $ta_terpilih = $ta_aktif_db ? ($ta_aktif_db['id'] . '|' . $ta_aktif_db['tahun'] . '|' . $ta_aktif_db['semester']) : '';
        }

        // AMBIL ROMBEL UNTUK WALI KELAS INI
        $userId = session()->get('id') ?? session()->get('user_id');
        $guru = $db->table('guru_tendik')->where('user_id', $userId)->get()->getRowArray();
        $rombel = null;
        if ($guru) {
            $rombel = $db->table('rombel')
                         ->where('wali_kelas_id', $guru['id'])
                         ->where('id_tahun_ajaran', $idTaAktif)
                         ->get()->getRowArray();
        }

        $data = [
            'user' => session()->get('nama_lengkap') ?? 'Wali Kelas',
            'navigations' => $this->getSidebarMenu(),
            'color' => $this->getColor(),
            'rombel' => $rombel,
            'list_ta_smt' => $list_ta_smt, 
            'list_mapel' => $list_mapel,
            'ta_terpilih' => $ta_terpilih
        ];
        
        return view('WaliKelas/cetak-leger', $data); 
    }

    public function getData()
    {
        if (!$this->request->isAJAX()) return $this->response->setStatusCode(404);

        $tahun_ajaran = $this->request->getPost('tahun_ajaran');
        $semester = $this->request->getPost('semester');
        $kategori = $this->request->getPost('kategori');

        $db = \Config\Database::connect();
        
        // AMBIL ROMBEL SECARA AMAN BERDASARKAN GURU YANG LOGIN
        $userId = session()->get('id') ?? session()->get('user_id');
        $guru = $db->table('guru_tendik')->where('user_id', $userId)->get()->getRowArray();
        
        if (!$guru) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Data guru tidak ditemukan.'
            ]);
        }

        $rombel = $db->table('rombel')
                     ->where('wali_kelas_id', $guru['id'])
                     ->where('id_tahun_ajaran', $tahun_ajaran)
                     ->get()->getRowArray();
                     
        if (!$rombel) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Anda tidak memiliki kelas perwalian pada Tahun Ajaran ini.'
            ]);
        }

        $rombel_id = $rombel['id'];

        $mapelAktif = $db->table('mata_pelajaran')
                         ->where('status', 'Aktif')
                         ->where('nama_mapel !=', 'BPI')
                         ->get()
                         ->getResultArray();
        $mapelIds = array_column($mapelAktif, 'id');

        $tabelAcuan = $db->tableExists('nilai_akademik') ? 'nilai_akademik' : ($db->tableExists('nilai_formatif') ? 'nilai_formatif' : 'nilai_sumatif');
        $fieldNilai = $db->fieldExists('nilai_angka', $tabelAcuan) ? 'nilai_angka' : 'nilai';
        $fieldSmt   = $db->fieldExists('semester', $tabelAcuan);
        $fieldTA    = $db->fieldExists('tahun_ajaran_id', $tabelAcuan) ? 'tahun_ajaran_id' : 'tahun_ajaran';

        $results = [];
        if ($db->tableExists($tabelAcuan)) {
            $builder = $db->table($tabelAcuan . ' n');
            
            $hasPredikat = $db->fieldExists('predikat', $tabelAcuan);
            $selectPredikat = $hasPredikat ? 'n.predikat' : '"" as predikat';
            
            $builder->select('n.siswa_id, n.mapel_id, n.' . $fieldNilai . ' as nilai_angka, ' . $selectPredikat . ', s.nis, s.nama_lengkap');
            $builder->join('siswa s', 's.id = n.siswa_id');
            $builder->join('anggota_rombel ar', 'ar.siswa_id = s.id');
            
            $builder->where('n.' . $fieldTA, $tahun_ajaran);
            
            if ($fieldSmt) {
                $builder->where('n.semester', $semester);
            }
            if ($db->fieldExists('kategori', $tabelAcuan) && !empty($kategori)) {
                $builder->where('n.kategori', $kategori);
            }            
            $builder->where('ar.rombel_id', $rombel_id);
            $builder->where('ar.tahun_ajaran_id', $tahun_ajaran);
            $builder->where('ar.semester', $semester);
            
            if ($db->fieldExists('status_siswa', 'siswa')) {
                $builder->where('s.status_siswa', 'Aktif');
            } else {
                $builder->where('s.rombel_id', $rombel_id);
            }
            
            $results = $builder->get()->getResultArray();
        }

        $leger = [];
        $no = 1;

        foreach ($results as $row) {
            $siswaId = $row['siswa_id'];
            
            if (!isset($leger[$siswaId])) {
                $leger[$siswaId] = [
                    'no'   => $no++,
                    'nis'  => $row['nis'],
                    'nama' => $row['nama_lengkap'],
                    'nilai' => []
                ];
                
                foreach ($mapelIds as $mId) {
                    $leger[$siswaId]['nilai'][$mId] = [
                        'angka' => 0,
                        'predikat' => '-'
                    ];
                }
            }
            
            $mapelId = $row['mapel_id'];
            if (in_array($mapelId, $mapelIds)) {
                $leger[$siswaId]['nilai'][$mapelId]['angka'] = (float) $row['nilai_angka'];
                $leger[$siswaId]['nilai'][$mapelId]['predikat'] = $row['predikat'];
            }
        }

        // Ambil Info Rombel & Kurikulum
        $builderRombel = $db->table('rombel')
            ->select('rombel.*')
            ->where('rombel.id', $rombel_id);

        if ($db->tableExists('kurikulum')) {
            $builderRombel->select('kurikulum.nama_kurikulum');
            $builderRombel->join('kurikulum', 'kurikulum.id = rombel.kurikulum_id', 'left');
        }
        
        $rombelObj = $builderRombel->get()->getRowArray();
        
        $nama_rombel = $rombelObj ? $rombelObj['nama_rombel'] : '-';
        $kurikulum = $rombelObj ? ($rombelObj['nama_kurikulum'] ?? 'Kurikulum Merdeka') : '-';
                
        $wali_kelas_nama = 'Belum Diatur';
        $wali_kelas_nip = '-';
        
        if ($rombelObj) {
            $id_wali = $rombelObj['wali_kelas_id'];
            if (!empty($id_wali) && $db->tableExists('guru_tendik')) {
                $dataGuru = $db->table('guru_tendik')->where('id', $id_wali)->get()->getRowArray();
                if ($dataGuru) {
                    $wali_kelas_nama = $dataGuru['nama_lengkap'] ?? $dataGuru['nama_guru'] ?? 'Nama Tidak Ditemukan';
                    $wali_kelas_nip = !empty($dataGuru['nuptk']) ? $dataGuru['nuptk'] : ($dataGuru['nik'] ?? '-');
                }
            }
        }

        $kepsek_nama = 'Belum Diatur'; $kepsek_nip = '-';
        $waka_nama = 'Belum Diatur'; $waka_nip = '-';

        if ($db->tableExists('guru_tendik')) {
            $kepsek = $db->table('guru_tendik')->where('jabatan_id', 6)->get()->getRowArray();
            if($kepsek) {
                $kepsek_nama = $kepsek['nama_lengkap'] ?? 'Belum Diatur';
                $kepsek_nip = !empty($kepsek['nuptk']) ? $kepsek['nuptk'] : ($kepsek['nik'] ?? '-');
            }

            $waka = $db->table('guru_tendik')->where('jabatan_id', 8)->get()->getRowArray();
            if($waka) {
                $waka_nama = $waka['nama_lengkap'] ?? 'Belum Diatur';
                $waka_nip = !empty($waka['nuptk']) ? $waka['nuptk'] : ($waka['nik'] ?? '-');
            }
        }

        $builderSiswa = $db->table('anggota_rombel ar')
                           ->join('siswa s', 's.id = ar.siswa_id')
                           ->where('ar.rombel_id', $rombel_id)
                           ->where('ar.tahun_ajaran_id', $tahun_ajaran)
                           ->where('ar.semester', $semester);
                           
        if ($db->fieldExists('status_siswa', 'siswa')) {
            $builderSiswa->where('s.status_siswa', 'Aktif');
        }
        $jumlah_siswa = $builderSiswa->countAllResults();

        $status_text = empty($results) ? 'DATA KOSONG' : 'TERKUNCI';
        $status_color = empty($results) ? 'gray' : 'emerald';

        $info_kelas = [
            'nama_rombel' => $nama_rombel,
            'wali_kelas'  => $wali_kelas_nama,
            'wali_nip'    => $wali_kelas_nip,
            'kepsek_nama' => $kepsek_nama,
            'kepsek_nip'  => $kepsek_nip,
            'waka_nama'   => $waka_nama,
            'waka_nip'    => $waka_nip,
            'jumlah_siswa'=> $jumlah_siswa,
            'kurikulum'   => $kurikulum,
            'status_text' => $status_text,
            'status_color'=> $status_color
        ];

        return $this->response->setJSON([
            'status' => 'success',
            'data' => array_values($leger),
            'info_kelas' => $info_kelas 
        ]);
    }

    public function exportExcel()
    {
        $tahun_ajaran = $this->request->getGet('tahun_ajaran');
        $semester = $this->request->getGet('semester');
        $kategori = $this->request->getGet('kategori');

        $db = \Config\Database::connect();

        // AMBIL ROMBEL SECARA AMAN BERDASARKAN GURU YANG LOGIN
        $userId = session()->get('id') ?? session()->get('user_id');
        $guru = $db->table('guru_tendik')->where('user_id', $userId)->get()->getRowArray();
        
        if (!$guru) {
            return "Data guru tidak ditemukan.";
        }

        $rombel = $db->table('rombel')
                     ->where('wali_kelas_id', $guru['id'])
                     ->where('id_tahun_ajaran', $tahun_ajaran)
                     ->get()->getRowArray();
                     
        if (!$rombel) {
            return "Anda tidak memiliki kelas perwalian pada Tahun Ajaran ini.";
        }

        $rombel_id = $rombel['id'];
        $namaKelas = $rombel['nama_rombel'];

        $mapelAktif = $db->table('mata_pelajaran')
                         ->where('status', 'Aktif')
                         ->where('nama_mapel !=', 'BPI')
                         ->orderBy('kelompok', 'ASC')
                         ->orderBy('id', 'ASC')
                         ->get()
                         ->getResultArray();
        $mapelIds = array_column($mapelAktif, 'id');

        $tabelAcuan = $db->tableExists('nilai_akademik') ? 'nilai_akademik' : ($db->tableExists('nilai_formatif') ? 'nilai_formatif' : 'nilai_sumatif');
        $fieldNilai = $db->fieldExists('nilai_angka', $tabelAcuan) ? 'nilai_angka' : 'nilai';
        $fieldSmt   = $db->fieldExists('semester', $tabelAcuan);
        $fieldTA    = $db->fieldExists('tahun_ajaran_id', $tabelAcuan) ? 'tahun_ajaran_id' : 'tahun_ajaran';

        $results = [];
        if ($db->tableExists($tabelAcuan)) {
            $builder = $db->table($tabelAcuan . ' n');
            
            $hasPredikat = $db->fieldExists('predikat', $tabelAcuan);
            $selectPredikat = $hasPredikat ? 'n.predikat' : '"" as predikat';
            
            $builder->select('n.siswa_id, n.mapel_id, n.' . $fieldNilai . ' as nilai_angka, ' . $selectPredikat . ', s.nis, s.nama_lengkap');
            $builder->join('siswa s', 's.id = n.siswa_id');
            $builder->join('anggota_rombel ar', 'ar.siswa_id = s.id');
            
            $builder->where('n.' . $fieldTA, $tahun_ajaran);
            
            if ($fieldSmt){
                $builder->where('n.semester', $semester);
            }
            if ($db->fieldExists('kategori', $tabelAcuan) && !empty($kategori)) {
                $builder->where('n.kategori', $kategori);
            }            
            $builder->where('ar.rombel_id', $rombel_id);
            $builder->where('ar.tahun_ajaran_id', $tahun_ajaran);
            $builder->where('ar.semester', $semester);
            
            if ($db->fieldExists('status_siswa', 'siswa')) {
                $builder->where('s.status_siswa', 'Aktif');
            } else {
                $builder->where('s.rombel_id', $rombel_id);
            }
            
            $results = $builder->get()->getResultArray();
        }

        $leger = [];
        $no = 1;
        foreach ($results as $row) {
            $siswaId = $row['siswa_id'];
            if (!isset($leger[$siswaId])) {
                $leger[$siswaId] = [
                    'no' => $no++, 'nis' => $row['nis'], 'nama' => $row['nama_lengkap'],
                    'nilai' => [], 'total' => 0
                ];
                foreach ($mapelIds as $mId) {
                    $leger[$siswaId]['nilai'][$mId] = ['angka' => 0, 'predikat' => '-'];
                }
            }
            $mapelId = $row['mapel_id'];
            if (in_array($mapelId, $mapelIds)) {
                $angka = (float) $row['nilai_angka'];
                $leger[$siswaId]['nilai'][$mapelId]['angka'] = $angka;
                $leger[$siswaId]['nilai'][$mapelId]['predikat'] = $row['predikat'];
                $leger[$siswaId]['total'] += $angka;
            }
        }

        $jmlMapel = count($mapelIds);
        foreach ($leger as &$siswa) {
            $siswa['avg'] = $jmlMapel > 0 ? round($siswa['total'] / $jmlMapel, 1) : 0;
        }
        $legerValues = array_values($leger);
        usort($legerValues, function($a, $b) { return $b['avg'] <=> $a['avg']; }); 
        foreach ($legerValues as $index => &$siswa) { $siswa['rank'] = $index + 1; }
        
        usort($legerValues, function($a, $b) { return strcmp($a['nama'], $b['nama']); });

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Leger Nilai ' . $namaKelas);

        $sheet->setCellValue('A1', 'No')->mergeCells('A1:A2');
        $sheet->setCellValue('B1', 'NIS')->mergeCells('B1:B2');
        $sheet->setCellValue('C1', 'Nama Siswa')->mergeCells('C1:C2');

        $col = 'D';
        foreach ($mapelAktif as $mapel) {
            $startCol = $col;
            $sheet->setCellValue($startCol . '1', $mapel['nama_mapel']);
            $col++; 
            $sheet->mergeCells($startCol . '1:' . $col . '1');
            
            $sheet->setCellValue($startCol . '2', 'Angka');
            $sheet->setCellValue($col . '2', 'Predikat');
            $col++; 
        }

        $sheet->setCellValue($col . '1', 'Rata-rata')->mergeCells($col.'1:'.$col.'2');
        $nextCol = ++$col;
        $sheet->setCellValue($nextCol . '1', 'Peringkat')->mergeCells($nextCol.'1:'.$nextCol.'2');

        $rowExcel = 3;
        foreach ($legerValues as $index => $siswa) {
            $sheet->setCellValue('A' . $rowExcel, $index + 1);
            $sheet->setCellValueExplicit('B' . $rowExcel, $siswa['nis'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet->setCellValue('C' . $rowExcel, $siswa['nama']);
            
            $colData = 'D';
            foreach ($mapelIds as $mId) {
                $sheet->setCellValue($colData . $rowExcel, $siswa['nilai'][$mId]['angka']);
                $colData++;
                $sheet->setCellValue($colData . $rowExcel, $siswa['nilai'][$mId]['predikat']);
                $colData++;
            }
            
            $sheet->setCellValue($colData . $rowExcel, $siswa['avg']);
            $nextColData = ++$colData;
            $sheet->setCellValue($nextColData . $rowExcel, $siswa['rank']);
            $rowExcel++;
        }

        $hurufKolom = 'A';
        while ($hurufKolom !== $nextCol) {
            $sheet->getColumnDimension($hurufKolom)->setAutoSize(true);
            $hurufKolom++;
        }
        $sheet->getColumnDimension($nextCol)->setAutoSize(true);

        $filename = 'Leger_Nilai_Kelas_' . $namaKelas . '_' . str_replace('/', '-', $tahun_ajaran) . '.xlsx';
        
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit();
    }
}
