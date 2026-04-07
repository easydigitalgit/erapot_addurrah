<?php
namespace App\Controllers\Admin;

use App\Controllers\AdminBaseController;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class CetakLegerController extends AdminBaseController
{
    public function index(): string
    {
        $db = \Config\Database::connect();
        
        // Tambahkan ->where('nama_mapel !=', 'BPI')
        $list_mapel = $db->table('mata_pelajaran')
                         ->where('status', 'Aktif')
                         ->where('nama_mapel !=', 'BPI') // <-- BLOKIR BPI DI SINI
                         ->orderBy('kelompok', 'ASC')
                         ->orderBy('id', 'ASC')
                         ->get()
                         ->getResultArray();
        
        // --- LOGIKA BARU: GABUNGAN TAHUN AJARAN & SEMESTER ---
        $ta_master = $db->table('tahun_ajaran')->orderBy('id', 'DESC')->get()->getResultArray();
        $list_ta_smt = [];
        
        foreach ($ta_master as $ta) {
            $teks = $ta['tahun'] . ' - ' . $ta['semester'];
            if ($ta['status'] === 'Aktif') {
                $teks .= ' (Aktif)';
            }
            
            $list_ta_smt[] = [
                // Kita gabungkan ID, Tahun, dan Semester pakai pembatas "|" agar mudah dibelah oleh JavaScript nanti
                'value' => $ta['id'] . '|' . $ta['tahun'] . '|' . $ta['semester'],
                'text'  => $teks
            ];
        }
        
        // 🚀 TANGKAP FILTER DARI URL & CARI TA AKTIF
        $getTaSmt = $this->request->getGet('ta'); // Format ekspektasi: ID|Tahun|Semester
        
        if ($getTaSmt) {
            $parts = explode('|', $getTaSmt);
            $idTaAktif = $parts[0] ?? 0;
            $ta_terpilih = $getTaSmt;
        } else {
            // Jika tidak ada di URL, cari TA Aktif di DB
            $ta_aktif_db = $db->table('tahun_ajaran')->where('status', 'Aktif')->get()->getRowArray();
            $idTaAktif = $ta_aktif_db ? $ta_aktif_db['id'] : 0;
            
            $teks = $ta_aktif_db ? ($ta_aktif_db['tahun'] . ' - ' . $ta_aktif_db['semester'] . ' (Aktif)') : '';
            $ta_terpilih = $ta_aktif_db ? ($ta_aktif_db['id'] . '|' . $ta_aktif_db['tahun'] . '|' . $ta_aktif_db['semester']) : '';
        }

        $data = [
            'user' => 'Admin',
            'navigations' => $this->getSidebarMenu(),
            'color' => $this->getColor(),
            // 🚀 HANYA PANGGIL ROMBEL DARI TAHUN AJARAN YANG DIPILIH
            'list_rombel' => $db->table('rombel')->where('id_tahun_ajaran', $idTaAktif)->orderBy('nama_rombel', 'ASC')->get()->getResultArray(),
            
            // Oper data gabungan ke view
            'list_ta_smt' => $list_ta_smt, 
            'list_mapel' => $list_mapel,
            'ta_terpilih' => $ta_terpilih // Lempar value ini ke View agar Dropdown tahu mana yang sedang di-select
        ];
        
        return view('admin/cetak-leger', $data); 
    }

    public function getData()
    {
        if (!$this->request->isAJAX()) return $this->response->setStatusCode(404);

        $tahun_ajaran = $this->request->getPost('tahun_ajaran');
        $semester = $this->request->getPost('semester');
        $rombel_id = $this->request->getPost('rombel_id');
        $kategori = $this->request->getPost('kategori'); // <-- TAMBAHKAN INI

        $db = \Config\Database::connect();
        
        // 1. Ambil ID mapel aktif untuk kerangka data
        $mapelAktif = $db->table('mata_pelajaran')
                         ->where('status', 'Aktif')
                         ->where('nama_mapel !=', 'BPI') // <-- BLOKIR BPI DI SINI
                         ->get()
                         ->getResultArray();
        $mapelIds = array_column($mapelAktif, 'id');

        // 2. Query Nilai Siswa
        $tabelAcuan = $db->tableExists('nilai_akademik') ? 'nilai_akademik' : ($db->tableExists('nilai_formatif') ? 'nilai_formatif' : 'nilai_sumatif');
        $fieldNilai = $db->fieldExists('nilai_angka', $tabelAcuan) ? 'nilai_angka' : 'nilai';
        $fieldSmt   = $db->fieldExists('semester', $tabelAcuan);
        $fieldTA    = $db->fieldExists('tahun_ajaran_id', $tabelAcuan) ? 'tahun_ajaran_id' : 'tahun_ajaran';

        $results = [];
        if ($db->tableExists($tabelAcuan)) {
            $builder = $db->table($tabelAcuan . ' n');
            
            $hasPredikat = $db->fieldExists('predikat', $tabelAcuan);
            $selectPredikat = $hasPredikat ? 'n.predikat' : '"" as predikat';
            
            // MENGGUNAKAN MESIN WAKTU (anggota_rombel)
            $builder->select('n.siswa_id, n.mapel_id, n.' . $fieldNilai . ' as nilai_angka, ' . $selectPredikat . ', s.nis, s.nama_lengkap');
            $builder->join('siswa s', 's.id = n.siswa_id');
            // Join ke tabel anggota_rombel sebagai penjaga keamanan data
            $builder->join('anggota_rombel ar', 'ar.siswa_id = s.id');
            
            // Where Kondisi
            $builder->where('n.' . $fieldTA, $tahun_ajaran);
            
            if ($fieldSmt) {
                $builder->where('n.semester', $semester);
            }
            if ($db->fieldExists('kategori', $tabelAcuan) && !empty($kategori)) {
                $builder->where('n.kategori', $kategori);
            }            
            // Filter berdasarkan mesin waktu
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

        // 3. Susun Data Secara Dinamis
        foreach ($results as $row) {
            $siswaId = $row['siswa_id'];
            
            if (!isset($leger[$siswaId])) {
                $leger[$siswaId] = [
                    'no'   => $no++,
                    'nis'  => $row['nis'],
                    'nama' => $row['nama_lengkap'],
                    'nilai' => [] // Menampung nilai berdasarkan ID Mapel
                ];
                
                // Set default 0 untuk semua mapel yang aktif
                foreach ($mapelIds as $mId) {
                    $leger[$siswaId]['nilai'][$mId] = [
                        'angka' => 0,
                        'predikat' => '-'
                    ];
                }
            }
            
            // Masukkan nilai sesuai ID mapel
            $mapelId = $row['mapel_id'];
            if (in_array($mapelId, $mapelIds)) {
                $leger[$siswaId]['nilai'][$mapelId]['angka'] = (float) $row['nilai_angka'];
                $leger[$siswaId]['nilai'][$mapelId]['predikat'] = $row['predikat'];
            }
            
        }
        // --- MULAI TAMBAHKAN KODE INI ---
        // A. Ambil Info Rombel & Kurikulum (SUNTIKAN ANTI-BUG)
        $builderRombel = $db->table('rombel')
            ->select('rombel.*')
            ->where('rombel.id', $rombel_id);

        // Pasang jembatan ke tabel kurikulum
        if ($db->tableExists('kurikulum')) {
            $builderRombel->select('kurikulum.nama_kurikulum');
            $builderRombel->join('kurikulum', 'kurikulum.id = rombel.kurikulum_id', 'left');
        }
        
        $rombel = $builderRombel->get()->getRowArray();
        
        $nama_rombel = $rombel ? $rombel['nama_rombel'] : '-';
        // Ambil nama_kurikulum, jika kosong jatuhkan ke Kurikulum Merdeka
        $kurikulum = $rombel ? ($rombel['nama_kurikulum'] ?? 'Kurikulum Merdeka') : '-';
                
        // B. Ambil Nama & NIP Wali Kelas
        $wali_kelas_nama = 'Belum Diatur';
        $wali_kelas_nip = '-';
        
        if ($rombel) {
            $id_wali = $rombel['wali_kelas_id'];
            if (!empty($id_wali) && $db->tableExists('guru_tendik')) {
                $dataGuru = $db->table('guru_tendik')->where('id', $id_wali)->get()->getRowArray();
                if ($dataGuru) {
                    $wali_kelas_nama = $dataGuru['nama_lengkap'] ?? $dataGuru['nama_guru'] ?? 'Nama Tidak Ditemukan';
                    $wali_kelas_nip = !empty($dataGuru['nuptk']) ? $dataGuru['nuptk'] : ($dataGuru['nik'] ?? '-');
                }
            }
        }

        // E. AMBIL KEPSEK & WAKA KURIKULUM (JALUR TOL ID 6 & 8)
        $kepsek_nama = 'Belum Diatur'; $kepsek_nip = '-';
        $waka_nama = 'Belum Diatur'; $waka_nip = '-';

        if ($db->tableExists('guru_tendik')) {
            // 1. Cari Kepsek: Langsung tembak jabatan_id = 6
            $kepsek = $db->table('guru_tendik')->where('jabatan_id', 6)->get()->getRowArray();
            if($kepsek) {
                $kepsek_nama = $kepsek['nama_lengkap'] ?? 'Belum Diatur';
                $kepsek_nip = !empty($kepsek['nuptk']) ? $kepsek['nuptk'] : ($kepsek['nik'] ?? '-');
            }

            // 2. Cari Waka Kurikulum: Langsung tembak jabatan_id = 8
            $waka = $db->table('guru_tendik')->where('jabatan_id', 8)->get()->getRowArray();
            if($waka) {
                $waka_nama = $waka['nama_lengkap'] ?? 'Belum Diatur';
                $waka_nip = !empty($waka['nuptk']) ? $waka['nuptk'] : ($waka['nik'] ?? '-');
            }
        }

        // C. Hitung Jumlah Siswa Aktif Menggunakan Mesin Waktu
        $builderSiswa = $db->table('anggota_rombel ar')
                           ->join('siswa s', 's.id = ar.siswa_id')
                           ->where('ar.rombel_id', $rombel_id)
                           ->where('ar.tahun_ajaran_id', $tahun_ajaran)
                           ->where('ar.semester', $semester);
                           
        if ($db->fieldExists('status_siswa', 'siswa')) {
            $builderSiswa->where('s.status_siswa', 'Aktif');
        }
        $jumlah_siswa = $builderSiswa->countAllResults();

        // D. Tentukan Status Data
        $status_text = empty($results) ? 'DATA KOSONG' : 'TERKUNCI';
        $status_color = empty($results) ? 'gray' : 'emerald';

        // GABUNGKAN SEMUA INFO UNTUK DIKIRIM KE JAVASCRIPT
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
        // 1. Ambil Parameter dari URL (GET)
        $tahun_ajaran = $this->request->getGet('tahun_ajaran');
        $semester = $this->request->getGet('semester');
        $rombel_id = $this->request->getGet('rombel_id');
        $kategori = $this->request->getGet('kategori'); // <-- TAMBAHKAN INI (pakai getGet)

        $db = \Config\Database::connect();

        // Ambil info kelas untuk nama file
        $rombel = $db->table('rombel')->where('id', $rombel_id)->get()->getRowArray();
        $namaKelas = $rombel ? $rombel['nama_rombel'] : 'Unknown';

        // 2. Ambil Data Mapel & Nilai
        $mapelAktif = $db->table('mata_pelajaran')
                         ->where('status', 'Aktif')
                         ->where('nama_mapel !=', 'BPI') // <-- BLOKIR BPI DI SINI
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
            
            // MENGGUNAKAN MESIN WAKTU (anggota_rombel)
            $builder->select('n.siswa_id, n.mapel_id, n.' . $fieldNilai . ' as nilai_angka, ' . $selectPredikat . ', s.nis, s.nama_lengkap');
            $builder->join('siswa s', 's.id = n.siswa_id');
            // Join ke tabel anggota_rombel
            $builder->join('anggota_rombel ar', 'ar.siswa_id = s.id');
            
            // Where Kondisi
            $builder->where('n.' . $fieldTA, $tahun_ajaran);
            
            if ($fieldSmt){
                $builder->where('n.semester', $semester);
            }
            if ($db->fieldExists('kategori', $tabelAcuan) && !empty($kategori)) {
                $builder->where('n.kategori', $kategori);
            }            
            // Filter berdasarkan mesin waktu
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

        // Racik Data
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

        // Hitung Rata-rata dan Ranking
        $jmlMapel = count($mapelIds);
        foreach ($leger as &$siswa) {
            $siswa['avg'] = $jmlMapel > 0 ? round($siswa['total'] / $jmlMapel, 1) : 0;
        }
        $legerValues = array_values($leger);
        usort($legerValues, function($a, $b) { return $b['avg'] <=> $a['avg']; }); // Sort by Ranking
        foreach ($legerValues as $index => &$siswa) { $siswa['rank'] = $index + 1; }
        
        // Sort kembali by Abjad untuk tampilan di Excel
        usort($legerValues, function($a, $b) { return strcmp($a['nama'], $b['nama']); });

        // 3. Mulai Buat Excel dengan PhpSpreadsheet
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Leger Nilai ' . $namaKelas);

        // Header Statis
        $sheet->setCellValue('A1', 'No')->mergeCells('A1:A2');
        $sheet->setCellValue('B1', 'NIS')->mergeCells('B1:B2');
        $sheet->setCellValue('C1', 'Nama Siswa')->mergeCells('C1:C2');

        // Header Dinamis (Mapel)
        $col = 'D';
        foreach ($mapelAktif as $mapel) {
            $startCol = $col;
            $sheet->setCellValue($startCol . '1', $mapel['nama_mapel']);
            $col++; // Geser 1 kolom ke kanan
            $sheet->mergeCells($startCol . '1:' . $col . '1');
            
            $sheet->setCellValue($startCol . '2', 'Angka');
            $sheet->setCellValue($col . '2', 'Predikat');
            $col++; // Geser untuk mapel berikutnya
        }

        // Header Rata-rata & Rank
        $sheet->setCellValue($col . '1', 'Rata-rata')->mergeCells($col.'1:'.$col.'2');
        $nextCol = ++$col;
        $sheet->setCellValue($nextCol . '1', 'Peringkat')->mergeCells($nextCol.'1:'.$nextCol.'2');

        // Isi Data Siswa
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

        // Auto-size kolom (Anti-Bug untuk kolom lebih dari Z)
        $hurufKolom = 'A';
        while ($hurufKolom !== $nextCol) {
            $sheet->getColumnDimension($hurufKolom)->setAutoSize(true);
            $hurufKolom++;
        }
        $sheet->getColumnDimension($nextCol)->setAutoSize(true); // Eksekusi kolom terakhir

        // 4. Proses Download File
        $filename = 'Leger_Nilai_Kelas_' . $namaKelas . '_' . str_replace('/', '-', $tahun_ajaran) . '.xlsx';
        
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit();
    }
}