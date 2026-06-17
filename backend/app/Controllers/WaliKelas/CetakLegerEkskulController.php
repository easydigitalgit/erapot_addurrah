<?php
namespace App\Controllers\WaliKelas;

use App\Controllers\WaliKelasBaseController;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class CetakLegerEkskulController extends WaliKelasBaseController
{
    public function index(): string
    {
        $db = \Config\Database::connect();
        
        $ta_master = $db->table('tahun_ajaran')->orderBy('id', 'DESC')->get()->getResultArray();
        $list_ta_smt = [];
        foreach ($ta_master as $ta) {
            $teks = $ta['tahun'] . ' - ' . $ta['semester'];
            $is_active = ($ta['status'] === 'Aktif');
            if ($is_active) $teks .= ' (Aktif)';
            
            $list_ta_smt[] = [
                'value'     => $ta['id'] . '|' . $ta['tahun'] . '|' . $ta['semester'],
                'text'      => $teks,
                'is_active' => $is_active
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
            'title'       => 'Leger Ekstrakurikuler',
            'user'        => session()->get('nama_lengkap') ?? 'Wali Kelas',
            'navigations' => $this->getSidebarMenu(),
            'color'       => $this->getColor(),
            'rombel'      => $rombel,
            'list_ta_smt' => $list_ta_smt,
            'list_ekskul' => $db->table('master_ekskul')->where('status', 'Aktif')->orderBy('nama_ekskul', 'ASC')->get()->getResultArray(),
            'ta_terpilih' => $ta_terpilih
        ];
        
        return view('WaliKelas/cetak-leger-ekskul', $data); 
    }

    public function getData()
    {
        if (!$this->request->isAJAX()) return $this->response->setStatusCode(404);

        $tahun_ajaran_id = $this->request->getPost('tahun_ajaran_id');
        $semester        = $this->request->getPost('semester');

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
                     ->where('id_tahun_ajaran', $tahun_ajaran_id)
                     ->get()->getRowArray();
                     
        if (!$rombel) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Anda tidak memiliki kelas perwalian pada Tahun Ajaran ini.'
            ]);
        }

        $rombel_id = $rombel['id'];

        $ekskulMaster = $db->table('master_ekskul')->where('status', 'Aktif')->orderBy('nama_ekskul', 'ASC')->get()->getResultArray();
        $ekskulIds    = array_column($ekskulMaster, 'id');

        $builderSiswa = $db->table('anggota_rombel ar')
                           ->select('s.id, s.nis, s.nama_lengkap')
                           ->join('siswa s', 's.id = ar.siswa_id')
                           ->where('ar.rombel_id', $rombel_id)
                           ->where('ar.tahun_ajaran_id', $tahun_ajaran_id)
                           ->where('ar.semester', $semester);
                           
        if ($db->fieldExists('status_siswa', 'siswa')) {
            $builderSiswa->where('s.status_siswa', 'Aktif');
        }
        $siswaList = $builderSiswa->orderBy('s.nama_lengkap', 'ASC')->get()->getResultArray();

        $nilaiEkskul = $db->table('nilai_ekskul')
                          ->where('tahun_ajaran_id', $tahun_ajaran_id)
                          ->where('semester', $semester)
                          ->where('rombel_id', $rombel_id)
                          ->get()->getResultArray();

        $nilaiMap = [];
        foreach ($nilaiEkskul as $n) {
            $nilaiMap[$n['siswa_id']][$n['ekskul_id']] = [
                'predikat'   => $n['predikat'],
                'keterangan' => $n['keterangan'] ?? $n['deskripsi']
            ];
        }

        $leger = [];
        $no = 1;
        $summary = []; 
        foreach ($ekskulIds as $eid) { $summary[$eid] = 0; }

        foreach ($siswaList as $s) {
            $row = [
                'no'    => $no++,
                'nis'   => $s['nis'],
                'nama'  => $s['nama_lengkap'],
                'nilai' => [],
                'total_ekskul' => 0
            ];

            foreach ($ekskulIds as $eid) {
                if (isset($nilaiMap[$s['id']][$eid])) {
                    $row['nilai'][$eid] = $nilaiMap[$s['id']][$eid]['predikat'];
                    $row['total_ekskul']++;
                    $summary[$eid]++;
                } else {
                    $row['nilai'][$eid] = '-';
                }
            }
            $leger[] = $row;
        }

        $kepsek_nama = 'Belum Diatur'; $kepsek_nip = '-';
        $wali_nama = 'Belum Diatur'; $wali_nip = '-';

        if ($db->tableExists('guru_tendik')) {
            $kepsek = $db->table('guru_tendik')->where('jabatan_id', 6)->get()->getRowArray();
            if ($kepsek) {
                $kepsek_nama = $kepsek['nama_lengkap'] ?? 'Belum Diatur';
                $kepsek_nip = !empty($kepsek['nuptk']) ? $kepsek['nuptk'] : ($kepsek['nik'] ?? '-');
            }

            if ($rombel && !empty($rombel['wali_kelas_id'])) {
                $wali = $db->table('guru_tendik')->where('id', $rombel['wali_kelas_id'])->get()->getRowArray();
                if ($wali) {
                    $wali_nama = $wali['nama_lengkap'] ?? 'Belum Diatur';
                    $wali_nip = !empty($wali['nuptk']) ? $wali['nuptk'] : ($wali['nik'] ?? '-');
                }
            }
        }

        return $this->response->setJSON([
            'status'  => 'success',
            'data'    => $leger,
            'summary' => $summary,
            'ekskul'  => $ekskulMaster,
            'info_ttd'=> [
                'kepsek_nama' => $kepsek_nama,
                'kepsek_nip'  => $kepsek_nip,
                'wali_nama'   => $wali_nama,
                'wali_nip'    => $wali_nip
            ]
        ]);
    }

    public function exportExcel()
    {
        $ta_raw    = $this->request->getGet('ta_smt'); 
        
        $ta_parts = explode('|', $ta_raw);
        $ta_id    = $ta_parts[0];
        $ta_tahun = $ta_parts[1] ?? '';
        $semester = $ta_parts[2] ?? '';

        $db = \Config\Database::connect();

        // AMBIL ROMBEL SECARA AMAN BERDASARKAN GURU YANG LOGIN
        $userId = session()->get('id') ?? session()->get('user_id');
        $guru = $db->table('guru_tendik')->where('user_id', $userId)->get()->getRowArray();
        
        if (!$guru) {
            return "Data guru tidak ditemukan.";
        }

        $rombel = $db->table('rombel')
                     ->where('wali_kelas_id', $guru['id'])
                     ->where('id_tahun_ajaran', $ta_id)
                     ->get()->getRowArray();
                     
        if (!$rombel) {
            return "Anda tidak memiliki kelas perwalian pada Tahun Ajaran ini.";
        }

        $rombel_id = $rombel['id'];
        $namaKelas = $rombel['nama_rombel'];

        $ekskulMaster = $db->table('master_ekskul')->where('status', 'Aktif')->orderBy('nama_ekskul', 'ASC')->get()->getResultArray();
        $ekskulIds    = array_column($ekskulMaster, 'id');

        $builderSiswa = $db->table('anggota_rombel ar')
                           ->select('s.id, s.nis, s.nama_lengkap')
                           ->join('siswa s', 's.id = ar.siswa_id')
                           ->where('ar.rombel_id', $rombel_id)
                           ->where('ar.tahun_ajaran_id', $ta_id)
                           ->where('ar.semester', $semester);
                           
        if ($db->fieldExists('status_siswa', 'siswa')) {
            $builderSiswa->where('s.status_siswa', 'Aktif');
        }
        $siswaList = $builderSiswa->orderBy('s.nama_lengkap', 'ASC')->get()->getResultArray();

        $nilaiEkskul = $db->table('nilai_ekskul')
                          ->where('tahun_ajaran_id', $ta_id)
                          ->where('semester', $semester)
                          ->where('rombel_id', $rombel_id)
                          ->get()->getResultArray();

        $nilaiMap = [];
        foreach ($nilaiEkskul as $n) {
            $nilaiMap[$n['siswa_id']][$n['ekskul_id']] = $n['predikat'];
        }

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Leger Ekskul ' . $namaKelas);

        $sheet->mergeCells('A1:F1');
        $sheet->setCellValue('A1', 'LEGER EKSTRAKURIKULER - ' . strtoupper($namaKelas));
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        
        $sheet->mergeCells('A2:F2');
        $sheet->setCellValue('A2', 'TAHUN AJARAN ' . $ta_tahun . ' SEMESTER ' . strtoupper($semester));

        $rowHeader = 4;
        $sheet->setCellValue('A' . $rowHeader, 'NO');
        $sheet->setCellValue('B' . $rowHeader, 'NIS');
        $sheet->setCellValue('C' . $rowHeader, 'NAMA SISWA');
        
        $col = 'D';
        foreach ($ekskulMaster as $e) {
            $sheet->setCellValue($col . $rowHeader, $e['nama_ekskul']);
            $sheet->getColumnDimension($col)->setWidth(15);
            $col++;
        }
        $sheet->setCellValue($col . $rowHeader, 'TOTAL EKSKUL');
        $lastCol = $col;

        $sheet->getStyle("A$rowHeader:{$lastCol}$rowHeader")->applyFromArray([
            'font' => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF']],
            'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['argb' => 'FF1F7A4D']],
            'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]
        ]);

        $rowExcel = 5;
        $summary = [];
        foreach ($ekskulIds as $eid) { $summary[$eid] = 0; }

        foreach ($siswaList as $idx => $s) {
            $sheet->setCellValue('A' . $rowExcel, $idx + 1);
            $sheet->setCellValue('B' . $rowExcel, $s['nis']);
            $sheet->setCellValue('C' . $rowExcel, $s['nama_lengkap']);
            
            $colData = 'D';
            $totalEkskul = 0;
            foreach ($ekskulIds as $eid) {
                if (isset($nilaiMap[$s['id']][$eid])) {
                    $sheet->setCellValue($colData . $rowExcel, $nilaiMap[$s['id']][$eid]);
                    $totalEkskul++;
                    $summary[$eid]++;
                } else {
                    $sheet->setCellValue($colData . $rowExcel, '-');
                }
                $colData++;
            }
            $sheet->setCellValue($colData . $rowExcel, $totalEkskul);
            $rowExcel++;
        }

        $sheet->mergeCells("A$rowExcel:C$rowExcel");
        $sheet->setCellValue("A$rowExcel", 'JUMLAH PESERTA');
        $sheet->getStyle("A$rowExcel")->getFont()->setBold(true);
        
        $colSum = 'D';
        foreach ($ekskulIds as $eid) {
            $sheet->setCellValue($colSum . $rowExcel, $summary[$eid]);
            $colSum++;
        }

        $sheet->getStyle("A4:{$lastCol}{$rowExcel}")->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

        $filename = 'Leger_Ekskul_' . $namaKelas . '_' . date('Ymd') . '.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }
}
