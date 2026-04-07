<?php

namespace App\Controllers\Admin;

use App\Controllers\AdminBaseController;
use App\Models\Admin\RombelModel;
use App\Models\Admin\MataPelajaranModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class MonitoringNilaiSiswaController extends AdminBaseController
{
    protected $rombelModel;
    protected $mapelModel;

    public function __construct()
    {
        $this->rombelModel = new RombelModel();
        $this->mapelModel = new MataPelajaranModel();
    }

    public function index()
    {
        $db = \Config\Database::connect();
        $this->data['title'] = 'Monitoring Nilai Siswa';
        $this->data['color'] = $this->getColor();

        // TANGKAP FILTER DARI GET REQUEST
        $kategori = $this->request->getGet('kategori') ?: 'Akhir Semester';
        $kelas_id = $this->request->getGet('kelas');
        $mapel_id = $this->request->getGet('mapel');

        // AMBIL DATA TAHUN AJARAN UNTUK DROPDOWN
        $this->data['tahun_ajaran'] = $db->table('tahun_ajaran')->orderBy('id', 'DESC')->get()->getResultArray();
        $this->data['fTA'] = $db->fieldExists('tahun', 'tahun_ajaran') ? 'tahun' : 'tahun_ajaran';

        // Tentukan TA Default (Dari Request atau Aktif)
        $ta_id = $this->request->getGet('ta');
        if (!$ta_id) {
            $activeTa = $db->table('tahun_ajaran')->where('status', 'Aktif')->get()->getRowArray();
            $ta_id = $activeTa ? $activeTa['id'] : '';
        }

        $this->data['ta_terpilih']    = $ta_id;
        $this->data['kategori']       = $kategori;
        $this->data['kelas_terpilih'] = $kelas_id;
        $this->data['mapel_terpilih'] = $mapel_id;

        // 🚀 FILTER ROMBEL MENGGUNAKAN MESIN WAKTU (Berdasarkan TA yang Dipilih)
        $this->data['rombels'] = $db->table('rombel')
            ->where('id_tahun_ajaran', $ta_id)
            ->orderBy('tingkat', 'ASC')
            ->orderBy('nama_rombel', 'ASC')
            ->get()->getResultArray();

        // Mapel dibiarkan mengambil semua karena master mapel biasanya statis antar tahun
        $this->data['mapels']  = $this->mapelModel->orderBy('nama_mapel', 'ASC')->findAll();

        $monitoring = [];
        $totalSiswaDinilai = 0;
        $totalPersen = 0;
        $maxPersen = -1;
        $mapelTertinggi = '-';
        $rombelTertinggi = '-';

        if ($db->tableExists('guru_mapel')) {
            $builder = $db->table('guru_mapel gm')
                ->select('gm.guru_id, gm.mapel_id, gm.rombel_id, g.nama_lengkap as guru, g.nuptk, u.foto_profil, m.nama_mapel as mapel, r.nama_rombel as kelas, r.tingkat')
                ->join('guru_tendik g', 'g.id = gm.guru_id', 'left')
                ->join('users u', 'u.id = g.user_id', 'left')
                ->join('mata_pelajaran m', 'm.id = gm.mapel_id', 'left')
                ->join('rombel r', 'r.id = gm.rombel_id', 'left');

            if (!empty($kelas_id)) $builder->where('gm.rombel_id', $kelas_id);
            if (!empty($mapel_id)) $builder->where('gm.mapel_id', $mapel_id);
            if ($db->fieldExists('tahun_ajaran_id', 'guru_mapel') && !empty($ta_id)) {
                $builder->where('gm.tahun_ajaran_id', $ta_id);
            }

            $guruMapel = $builder->get()->getResultArray();

            // Filter progres pengisian tetap menggunakan kategori agar akurat
            $jenisLike = ($kategori === 'Tengah Semester') ? ['pts', 'tengah', 'sts'] : ['pas', 'akhir', 'sas', 'smt'];

            // Ambil semester dari TA aktif untuk keperluan hitung index
            $taInfo = $db->table('tahun_ajaran')->where('id', $ta_id)->get()->getRowArray();
            $semesterAktif = $taInfo ? $taInfo['semester'] : 'Ganjil';

            foreach ($guruMapel as $gm) {
                // MENGGUNAKAN MESIN WAKTU (anggota_rombel)
                $builderSiswa = $db->table('anggota_rombel ar')
                                   ->join('siswa s', 's.id = ar.siswa_id')
                                   ->where('ar.rombel_id', $gm['rombel_id'])
                                   ->where('ar.tahun_ajaran_id', $ta_id)
                                   ->where('ar.semester', $semesterAktif)
                                   ->where('s.status_siswa', 'Aktif');

                $totalSiswa = $builderSiswa->countAllResults(false); // false agar query tidak di-reset
                if ($totalSiswa == 0) continue;

                $siswaIds = $builderSiswa->select('s.id')->get()->getResultArray();
                $sIds = array_column($siswaIds, 'id');

                $sudahDinilai = 0;
                if (!empty($sIds) && $db->tableExists('nilai_sumatif')) {
                    $bSumatif = $db->table('nilai_sumatif')->select('siswa_id')->distinct()
                        ->where('mapel_id', $gm['mapel_id'])
                        ->whereIn('siswa_id', $sIds);

                    if ($db->fieldExists('tahun_ajaran_id', 'nilai_sumatif')) {
                        $bSumatif->where('tahun_ajaran_id', $ta_id);
                    }

                    $bSumatif->groupStart();
                    foreach ($jenisLike as $like) {
                        $bSumatif->orLike('jenis_sumatif', $like);
                    }
                    $bSumatif->groupEnd();
                    $sudahDinilai = $bSumatif->countAllResults();
                }

                $persen = $totalSiswa > 0 ? round(($sudahDinilai / $totalSiswa) * 100) : 0;
                if ($persen > 100) $persen = 100;

                $badge = $persen == 100 ? 'success' : ($persen > 0 ? 'warning' : 'danger');
                $status = $persen == 100 ? 'Selesai' : ($persen > 0 ? 'Proses' : 'Belum');

                $monitoring[] = [
                    'guru_id'       => $gm['guru_id'],
                    'guru'          => $gm['guru'] ?? 'Anonim',
                    'nuptk'         => $gm['nuptk'] ?? '-',
                    'foto_profil'   => $gm['foto_profil'] ?? null,
                    'mapel'         => $gm['mapel'] ?? '-',
                    'mapel_id'      => $gm['mapel_id'],
                    'kelas'         => ($gm['tingkat'] ?? '') . ' - ' . ($gm['kelas'] ?? ''),
                    'rombel_id'     => $gm['rombel_id'],
                    'total_siswa'   => $totalSiswa,
                    'sudah_dinilai' => $sudahDinilai,
                    'persen'        => $persen,
                    'status'        => $status,
                    'badge'         => $badge
                ];

                $totalPersen += $persen;
                $totalSiswaDinilai += $sudahDinilai;

                if ($persen > $maxPersen) {
                    $maxPersen = $persen;
                    $mapelTertinggi = $gm['mapel'];
                    $rombelTertinggi = ($gm['tingkat'] ?? '') . ' - ' . ($gm['kelas'] ?? '');
                }
            }
        }

        $avgProgres = count($monitoring) > 0 ? round($totalPersen / count($monitoring)) : 0;
        $stats = [
            'avg_progres_sekolah' => $avgProgres,
            'total_siswa_dinilai' => $totalSiswaDinilai,
            'mapel_tertinggi'     => $mapelTertinggi,
            'rombel_tertinggi'    => $rombelTertinggi
        ];

        $this->data['monitoring'] = $monitoring;
        $this->data['stats']      = $stats;

        return view('admin/input-nilai-siswa', $this->data);
    }

    private function _getRekapData($rombel_id, $mapel_id, $ta_id, $kategori)
    {
        $db = \Config\Database::connect();

        $kategoriDB = (stripos($kategori, 'tengah') !== false) ? 'Tengah' : 'Akhir';

        $taData = $db->table('tahun_ajaran')->where('id', $ta_id)->get()->getRowArray();
        if (!$taData) return ['is_tahfidz' => false, 'jumlah_lm' => 0, 'data' => []];
        $semesterAktif = $taData['semester'];

        // MENGGUNAKAN MESIN WAKTU (anggota_rombel)
        $builder = $db->table('anggota_rombel ar')
                      ->select('s.id as siswa_id, s.nis, s.nama_lengkap as nama')
                      ->join('siswa s', 's.id = ar.siswa_id')
                      ->where('ar.rombel_id', $rombel_id)
                      ->where('ar.tahun_ajaran_id', $ta_id)
                      ->where('ar.semester', $semesterAktif);
                      
        if ($db->fieldExists('status_siswa', 'siswa')) {
            $builder->where('s.status_siswa', 'Aktif');
        }
        $dataSiswa = $builder->orderBy('s.nama_lengkap', 'ASC')->get()->getResultArray();

        if (empty($dataSiswa)) return ['is_tahfidz' => false, 'jumlah_lm' => 0, 'data' => []];
        $siswaIds = array_column($dataSiswa, 'siswa_id');

        $mapelInfo = $db->table('mata_pelajaran')->where('id', $mapel_id)->get()->getRowArray();
        $isTahfidz = false;
        if ($mapelInfo && (stripos($mapelInfo['nama_mapel'], 'tahfidz') !== false || stripos($mapelInfo['nama_mapel'], 'tahfizh') !== false)) {
            $isTahfidz = true;
        }

        $jumlah_lm = 0;

        if (!$isTahfidz && $db->tableExists('master_lm')) {
            $rombelInfo = $db->table('rombel')->where('id', $rombel_id)->get()->getRowArray();
            $tingkatClean = 0;
            if ($rombelInfo) {
                $tingkatClean = preg_replace('/[^0-9]/', '', (string)$rombelInfo['tingkat']);
                if (empty($tingkatClean)) {
                    $romToNum = ['VII' => 7, 'VIII' => 8, 'IX' => 9, 'X' => 10, 'XI' => 11, 'XII' => 12];
                    $tingkatClean = $romToNum[strtoupper(trim($rombelInfo['tingkat']))] ?? 0;
                }
            }

            if (!empty($tingkatClean)) {
                $bLm = $db->table('master_lm')
                    ->where('mapel_id', $mapel_id)
                    ->where('tingkat', $tingkatClean)
                    ->where('semester', $semesterAktif);

                if ($db->fieldExists('tahun_ajaran_id', 'master_lm')) {
                    $bLm->where('tahun_ajaran_id', $ta_id);
                }

                if ($db->fieldExists('kategori', 'master_lm')) {
                    $bLm->where('kategori', $kategoriDB);
                }
                if ($db->fieldExists('status', 'master_lm')) {
                    $bLm->where('status', 'Aktif');
                }

                $jumlah_lm = $bLm->countAllResults();
            }
        }

        $nilais = [];
        if ($db->tableExists('nilai_formatif')) {
            $bFormatif = $db->table('nilai_formatif')
                ->whereIn('siswa_id', $siswaIds)
                ->where('mapel_id', $mapel_id);

            if ($db->fieldExists('tahun_ajaran_id', 'nilai_formatif')) $bFormatif->where('tahun_ajaran_id', $ta_id);
            if ($db->fieldExists('semester', 'nilai_formatif')) $bFormatif->where('semester', $semesterAktif);
            if ($db->fieldExists('kategori', 'nilai_formatif')) $bFormatif->where('kategori', $kategoriDB);

            $nilais = $bFormatif->get()->getResultArray();
        }

        $sumatifs = [];
        if ($db->tableExists('nilai_sumatif')) {
            $bSumatif = $db->table('nilai_sumatif')->whereIn('siswa_id', $siswaIds)->where('mapel_id', $mapel_id);

            if ($db->fieldExists('tahun_ajaran_id', 'nilai_sumatif')) $bSumatif->where('tahun_ajaran_id', $ta_id);
            if ($db->fieldExists('semester', 'nilai_sumatif')) $bSumatif->where('semester', $semesterAktif);

            // KUNCI PERBAIKAN: Ambil SEMUA data sumatif (PTS maupun PAS) 
            // agar saat filter "Akhir Semester", kolom MID/PTS tetap mendapat datanya.
            $sumatifs = $bSumatif->get()->getResultArray();
        }

        $dataTahfidzDb = [];
        $dataSetoranDb = [];
        if ($isTahfidz) {
            if ($db->tableExists('nilai_tahfidz')) {
                $bTahfidz = $db->table('nilai_tahfidz')->whereIn('siswa_id', $siswaIds);
                if ($db->fieldExists('tahun_ajaran_id', 'nilai_tahfidz')) $bTahfidz->where('tahun_ajaran_id', $ta_id);
                if ($db->fieldExists('semester', 'nilai_tahfidz')) $bTahfidz->where('semester', $semesterAktif);
                if ($db->fieldExists('kategori', 'nilai_tahfidz')) $bTahfidz->where('kategori', $kategoriDB);
                $dataTahfidzDb = $bTahfidz->orderBy('id', 'DESC')->get()->getResultArray();
            }
            if ($db->tableExists('setoran_tahfidz')) {
                $dataSetoranDb = $db->table('setoran_tahfidz')->whereIn('siswa_id', $siswaIds)->orderBy('tanggal', 'DESC')->get()->getResultArray();
            }
        }

        $mappedData = [];
        foreach ($dataSiswa as $siswa) {
            $sId = $siswa['siswa_id'];
            $harian = [];
            $uh = [];
            $pts = '-';
            $pas = '-';

            $detailHarian = [];
            $detailUh     = [];
            for ($i = 1; $i <= $jumlah_lm; $i++) {
                $detailHarian[$i] = '-';
                $detailUh[$i]     = '-';
            }

            foreach ($nilais as $n) {
                if ($n['siswa_id'] == $sId && $n['nilai_angka'] !== null) {
                    $val = (int)$n['nilai_angka'];
                    $jenis = strtolower($n['jenis_penilaian']);
                    $pertemuan = (int)$n['pertemuan'];

                    if ($pertemuan >= 1 && $pertemuan <= $jumlah_lm) {
                        if (strpos($jenis, 'ulangan') !== false || strpos($jenis, 'uh') !== false) {
                            $uh[] = $val;
                            $detailUh[$pertemuan] = $val;
                        } else {
                            $harian[] = $val;
                            $detailHarian[$pertemuan] = $val;
                        }
                    }
                }
            }

            foreach ($sumatifs as $sm) {
                if ($sm['siswa_id'] == $sId && $sm['nilai'] !== null) {
                    $val = (int)$sm['nilai'];
                    $jenis_sm = strtolower($sm['jenis_sumatif']);

                    if ($jenis_sm == 'pts' || strpos($jenis_sm, 'tengah') !== false || strpos($jenis_sm, 'sts') !== false) {
                        $pts = $val;
                    }
                    if ($jenis_sm == 'pas' || $jenis_sm == 'sas' || strpos($jenis_sm, 'akhir') !== false || strpos($jenis_sm, 'smt') !== false) {
                        $pas = $val;
                    }
                }
            }

            $avgHarian = count($harian) > 0 ? round(array_sum($harian) / count($harian)) : '-';
            $avgUh = count($uh) > 0 ? round(array_sum($uh) / count($uh)) : '-';

            $dataTahfidzMapped = [
                'hafalan_terakhir' => '-',
                'rata_nilai_teori' => '0',
                'predikat_tajwid' => '-',
                'predikat_kelancaran' => '-',
                'predikat_makhroj' => '-'
            ];

            if ($isTahfidz) {
                foreach ($dataSetoranDb as $st) {
                    if ($st['siswa_id'] == $sId) {
                        $dataTahfidzMapped['hafalan_terakhir'] = $st['surah'] . ' (' . $st['ayat'] . ')';
                        break;
                    }
                }
                $totalNilaiTahfidz = 0;
                $countTahfidz = 0;
                foreach ($dataTahfidzDb as $tdb) {
                    if ($tdb['siswa_id'] == $sId) {
                        if ($countTahfidz === 0) {
                            $dataTahfidzMapped['predikat_tajwid'] = $tdb['predikat_tajwid'] ?? '-';
                            $dataTahfidzMapped['predikat_kelancaran'] = $tdb['predikat_kelancaran'] ?? '-';
                            $dataTahfidzMapped['predikat_makhroj'] = $tdb['predikat_makhroj'] ?? '-';
                        }
                        $nilaiTeori = isset($tdb['nilai_teori']) ? $tdb['nilai_teori'] : ($tdb['nilai'] ?? 0);
                        $totalNilaiTahfidz += (float)$nilaiTeori;
                        $countTahfidz++;
                    }
                }
                if ($countTahfidz > 0) $dataTahfidzMapped['rata_nilai_teori'] = round($totalNilaiTahfidz / $countTahfidz, 1);
            }

            $mappedData[] = [
                'siswa_id'      => $sId,
                'nis'           => $siswa['nis'],
                'nama'          => $siswa['nama'],
                'rata_harian'   => $avgHarian,
                'rata_uh'       => $avgUh,
                'pts'           => $pts,
                'pas'           => $pas,
                'detail_harian' => $detailHarian,
                'detail_uh'     => $detailUh,
                'is_tahfidz'    => $isTahfidz,
                'tahfidz_hafalan_terakhir'    => $dataTahfidzMapped['hafalan_terakhir'],
                'tahfidz_rata_nilai_teori'    => $dataTahfidzMapped['rata_nilai_teori'],
                'tahfidz_predikat_tajwid'     => $dataTahfidzMapped['predikat_tajwid'],
                'tahfidz_predikat_kelancaran' => $dataTahfidzMapped['predikat_kelancaran'],
                'tahfidz_predikat_makhroj'    => $dataTahfidzMapped['predikat_makhroj']
            ];
        }

        return ['is_tahfidz' => $isTahfidz, 'jumlah_lm' => $jumlah_lm, 'data' => $mappedData];
    }

    public function getSiswaByKelas()
    {
        if (ob_get_length()) ob_clean();
        try {
            $rombel_id = $this->request->getGet('kelas');
            $mapel_id  = $this->request->getGet('mapel');
            $ta_id     = $this->request->getGet('ta');
            $kategori  = $this->request->getGet('kategori') ?: 'Akhir Semester';

            if (!$rombel_id || !$mapel_id || !$ta_id) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Filter parameter belum lengkap.']);
            }

            $rekapResult = $this->_getRekapData($rombel_id, $mapel_id, $ta_id, $kategori);

            return $this->response->setJSON([
                'status'    => 'success',
                'is_tahfidz' => $rekapResult['is_tahfidz'],
                'jumlah_lm' => $rekapResult['jumlah_lm'],
                'data'      => $rekapResult['data']
            ]);
        } catch (\Throwable $e) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Sistem Error: ' . $e->getMessage() . ' di Baris: ' . $e->getLine()
            ]);
        }
    }

    public function export()
    {
        if (ob_get_length()) ob_clean();
        $rombel_id = $this->request->getGet('kelas');
        $mapel_id  = $this->request->getGet('mapel');
        $ta_id     = $this->request->getGet('ta');
        $kategori  = $this->request->getGet('kategori') ?: 'Akhir Semester';

        if (!$rombel_id || !$mapel_id || !$ta_id) {
            return redirect()->back()->with('error', 'Semua Filter harus dipilih terlebih dahulu.');
        }

        $rekapResult = $this->_getRekapData($rombel_id, $mapel_id, $ta_id, $kategori);
        $dataSiswa = $rekapResult['data'];
        $isTahfidz = $rekapResult['is_tahfidz'];

        $rombel = $this->rombelModel->find($rombel_id);
        $mapel  = $this->mapelModel->find($mapel_id);

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'REKAPITULASI NILAI ' . ($isTahfidz ? 'TAHFIDZ' : 'AKADEMIK') . ' - ' . strtoupper($rombel['nama_rombel'] ?? ''));
        $sheet->setCellValue('A2', 'Mata Pelajaran: ' . ($mapel['nama_mapel'] ?? '') . ' (' . $kategori . ')');
        $sheet->setCellValue('A3', 'Waktu Export: ' . date('d-m-Y H:i:s'));

        $sheet->setCellValue('A5', 'NO');
        $sheet->setCellValue('B5', 'NIS');
        $sheet->setCellValue('C5', 'NAMA LENGKAP');

        if ($isTahfidz) {
            $sheet->setCellValue('D5', 'HAFALAN TERAKHIR');
            $sheet->setCellValue('E5', 'RATA-RATA NILAI TEORI');
            $sheet->setCellValue('F5', 'PREDIKAT TAJWID');
            $sheet->setCellValue('G5', 'PREDIKAT KELANCARAN');
            $sheet->setCellValue('H5', 'PREDIKAT MAKHROJ');
        } else {
            $sheet->setCellValue('D5', 'RATA-RATA TUGAS/HARIAN');
            $sheet->setCellValue('E5', 'RATA-RATA ULANGAN');
            $sheet->setCellValue('F5', 'NILAI MID/PTS');

            // HILANGKAN KOLOM 'PAS' JIKA TENGAH SEMESTER
            if ($kategori === 'Akhir Semester') {
                $sheet->setCellValue('G5', 'NILAI AKHIR/PAS');
            }
        }

        $headerStyle = [
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => '3b82f6']],
            'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]
        ];

        if ($isTahfidz) {
            $sheet->getStyle('A5:H5')->applyFromArray($headerStyle);
        } else {
            $rangeHeader = ($kategori === 'Akhir Semester') ? 'A5:G5' : 'A5:F5';
            $sheet->getStyle($rangeHeader)->applyFromArray($headerStyle);
        }

        $column = 6;
        foreach ($dataSiswa as $key => $siswa) {
            $sheet->setCellValue('A' . $column, ($key + 1));
            $sheet->setCellValueExplicit('B' . $column, $siswa['nis'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet->setCellValue('C' . $column, $siswa['nama']);

            if ($isTahfidz) {
                $sheet->setCellValue('D' . $column, $siswa['tahfidz_hafalan_terakhir']);
                $sheet->setCellValue('E' . $column, $siswa['tahfidz_rata_nilai_teori']);
                $sheet->setCellValue('F' . $column, $siswa['tahfidz_predikat_tajwid']);
                $sheet->setCellValue('G' . $column, $siswa['tahfidz_predikat_kelancaran']);
                $sheet->setCellValue('H' . $column, $siswa['tahfidz_predikat_makhroj']);
            } else {
                $sheet->setCellValue('D' . $column, $siswa['rata_harian']);
                $sheet->setCellValue('E' . $column, $siswa['rata_uh']);
                $sheet->setCellValue('F' . $column, $siswa['pts']);

                // HILANGKAN DATA 'PAS' JIKA TENGAH SEMESTER
                if ($kategori === 'Akhir Semester') {
                    $sheet->setCellValue('G' . $column, $siswa['pas']);
                }
            }
            $column++;
        }

        $range = [];
        if ($isTahfidz) {
            $range = range('A', 'H');
        } else {
            $range = ($kategori === 'Akhir Semester') ? range('A', 'G') : range('A', 'F');
        }

        foreach ($range as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $filename = 'Rekap_Nilai_' . str_replace(' ', '_', ($rombel['nama_rombel'] ?? 'Kelas')) . '_' . date('Ymd') . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit();
    }
}
