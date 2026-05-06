<?php

namespace App\Controllers\Admin;

use App\Controllers\AdminBaseController;
use App\Models\Admin\SiswaModel;
use App\Models\Admin\RombelModel;
use App\Models\Admin\ValidasiNilaiModel;
use Mpdf\Mpdf;

class CetakRaporController extends AdminBaseController
{
    protected $siswaModel;
    protected $rombelModel;
    protected $validasiModel;
    protected $db;

    public function __construct()
    {
        $this->siswaModel = new SiswaModel();
        $this->rombelModel = new RombelModel();
        $this->validasiModel = new ValidasiNilaiModel();
        $this->db = \Config\Database::connect();
    }

    public function index()
    {
        $this->data['title'] = 'Cetak Rapor Siswa';
        $this->data['color'] = $this->getColor();

        // 1. Ambil Tahun Ajaran (Dinamis dari GET atau Aktif)
        $id_ta_get = $this->request->getGet('ta');
        if ($id_ta_get) {
            $ta_aktif = $this->db->table('tahun_ajaran')->where('id', $id_ta_get)->get()->getRowArray();
        } else {
            $ta_aktif = $this->db->table('tahun_ajaran')->where('status', 'Aktif')->get()->getRowArray();
        }

        $fTA_TA   = $this->db->fieldExists('tahun', 'tahun_ajaran') ? 'tahun' : 'tahun_ajaran';
        $this->data['tahun_ajaran_aktif'] = $ta_aktif ? ($ta_aktif[$fTA_TA] ?? 'Belum Diatur') : 'Belum Diatur';
        $this->data['semester_aktif']     = $ta_aktif ? ($ta_aktif['semester'] ?? 'Belum Diatur') : 'Belum Diatur';
        $this->data['id_ta_aktif']        = $ta_aktif ? $ta_aktif['id'] : 0;
        $tanggal_rapor_raw = $ta_aktif ? ($ta_aktif['tanggal_rapor'] ?? date('Y-m-d')) : date('Y-m-d');
        $tanggal_rapor_ymd = date('Y-m-d');
        if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $tanggal_rapor_raw)) {
            $tanggal_rapor_ymd = $tanggal_rapor_raw;
        } else {
            $bulanIndo = [
                'Januari' => '01', 'Februari' => '02', 'Maret' => '03', 'April' => '04',
                'Mei' => '05', 'Juni' => '06', 'Juli' => '07', 'Agustus' => '08',
                'September' => '09', 'Oktober' => '10', 'November' => '11', 'Desember' => '12'
            ];
            $parts = explode(' ', trim($tanggal_rapor_raw));
            if (count($parts) >= 3) {
                $d = str_pad($parts[0], 2, '0', STR_PAD_LEFT);
                $m = $bulanIndo[ucfirst(strtolower($parts[1]))] ?? '01';
                $y = $parts[2];
                $tanggal_rapor_ymd = "$y-$m-$d";
            }
        }

        $this->data['tanggal_rapor']      = $tanggal_rapor_ymd;
        $this->data['tempat_rapor']       = $ta_aktif ? ($ta_aktif['tempat_rapor'] ?? 'Surakarta') : 'Surakarta';
        $this->data['list_ta']            = $this->db->table('tahun_ajaran')->orderBy('id', 'DESC')->get()->getResultArray();

        // =========================================================
        // TUGAS 1: FILTER ROMBEL BERDASARKAN TAHUN AJARAN YANG DIPILIH
        // =========================================================
        $this->data['list_rombel'] = $this->db->table('rombel')
            ->select('id, tingkat, nama_rombel')
            ->where('id_tahun_ajaran', $this->data['id_ta_aktif']) // Filter berdasarkan TA aktif di filter
            ->orderBy('tingkat', 'ASC')
            ->orderBy('nama_rombel', 'ASC')
            ->get()->getResultArray();

        // 3. Ambil Identitas Sekolah
        $sekolah = $this->db->table('sekolah s')
            ->select('s.*, k.nama as kabupaten_nama')
            ->join('kabupaten k', 'CONVERT(k.kode USING utf8mb4) COLLATE utf8mb4_general_ci = CONVERT(s.kabupaten USING utf8mb4) COLLATE utf8mb4_general_ci', 'left', false)
            ->get()->getRowArray();

        $this->data['sekolah']       = $sekolah;
        $this->data['tempat_rapor']  = $sekolah['kabupaten_nama'] ?? 'Surakarta';

        // 4. Ambil Data Kepala Sekolah
        $this->data['kepsek'] = $this->db->table('guru_tendik g')
            ->select('g.*')
            ->join('master_jabatan j', 'j.id = g.jabatan_id', 'left')
            ->like('j.nama_jabatan', 'Kepala Sekolah')
            ->get()->getRowArray();

        return view('admin/cetak-rapor', $this->data);
    }

    public function uploadTtdKepsek()
    {
        $file = $this->request->getFile('ttd');
        if (!$file || !$file->isValid()) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'File tidak valid']);
        }

        $kepsek = $this->db->table('guru_tendik g')
            ->select('g.id, g.ttd_digital')
            ->join('master_jabatan j', 'j.id = g.jabatan_id', 'left')
            ->like('j.nama_jabatan', 'Kepala Sekolah')
            ->get()->getRowArray();

        if (!$kepsek) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Data Kepala Sekolah tidak ditemukan.']);
        }

        $uploadPath = FCPATH . 'assets/uploads/ttd/';
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0777, true);
        }

        $newName = 'ttd_kepsek_' . time() . '.webp';

        try {
            $image = \Config\Services::image()->withFile($file->getTempName());
            $image->save($uploadPath . $newName);
        } catch (\Exception $e) {
            $file->move($uploadPath, $newName);
        }

        $this->db->table('guru_tendik')->where('id', $kepsek['id'])->update(['ttd_digital' => $newName]);

        if (!empty($kepsek['ttd_digital']) && file_exists($uploadPath . $kepsek['ttd_digital'])) {
            @unlink($uploadPath . $kepsek['ttd_digital']);
        }

        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'Tanda tangan Kepala Sekolah berhasil diupload',
            'filename' => base_url('assets/uploads/ttd/' . $newName)
        ]);
    }

    private function getWatermarkBase64($text)
    {
        $text = strtoupper($text ?: 'SCHOOL REPORT');
        $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="120" height="18"><text x="50%" y="13" font-family="Arial" font-size="9" font-weight="bold" fill="#DAA520" fill-opacity="0.16" text-anchor="middle">' . $text . '</text></svg>';
        return base64_encode($svg);
    }

    public function getSiswaByRombel()
    {
        $rombel_id = $this->request->getGet('rombel_id');
        $id_ta_get = $this->request->getGet('ta');

        if (!$rombel_id) return $this->response->setJSON(['status' => 'error']);

        if ($id_ta_get) {
            $ta_aktif = $this->db->table('tahun_ajaran')->where('id', $id_ta_get)->get()->getRowArray();
        } else {
            $ta_aktif = $this->db->table('tahun_ajaran')->where('status', 'Aktif')->get()->getRowArray();
        }
        $id_ta = $ta_aktif ? $ta_aktif['id'] : 0;
        $semester = $ta_aktif ? $ta_aktif['semester'] : 'Ganjil';

        $builder = $this->db->table('anggota_rombel ar')
            ->select('siswa.id, siswa.nama_lengkap, siswa.nis')
            ->join('siswa', 'siswa.id = ar.siswa_id')
            ->where('ar.rombel_id', $rombel_id)
            ->where('ar.tahun_ajaran_id', $id_ta)
            ->where('ar.semester', $semester);

        if ($this->db->fieldExists('status_siswa', 'siswa')) {
            $builder->where('siswa.status_siswa', 'Aktif');
        }

        $siswa = $builder->orderBy('siswa.nama_lengkap', 'ASC')->get()->getResultArray();
        $validasi = $this->validasiModel->where('rombel_id', $rombel_id)->first();

        $wali_kelas_nama = 'Belum Diatur';
        $rombel = $this->db->table('rombel')->where('id', $rombel_id)->get()->getRowArray();

        if ($rombel && !empty($rombel['wali_kelas_id'])) {
            $guru = $this->db->table('guru_tendik')->where('id', $rombel['wali_kelas_id'])->get()->getRowArray();
            if ($guru) {
                $wali_kelas_nama = $guru['nama_lengkap'] ?? $guru['nama_guru'] ?? 'Nama Tidak Ditemukan';
            }
        }

        $status_rombel = [
            'is_locked' => $validasi && $validasi['is_locked'] == 1,
            'locked_at' => $validasi ? date('d M Y, H:i', strtotime($validasi['locked_at'])) : '-',
            'validator' => 'Waka Kurikulum',
            'wali_kelas' => $wali_kelas_nama
        ];

        return $this->response->setJSON([
            'status' => 'success',
            'data_siswa' => $siswa,
            'info_rombel' => $status_rombel
        ]);
    }

    public function getCatatanSiswa()
    {
        try {
            $siswa_id = $this->request->getGet('siswa_id');
            $id_ta_get = $this->request->getGet('ta');

            if ($id_ta_get) {
                $ta_aktif = $this->db->table('tahun_ajaran')->where('id', $id_ta_get)->get()->getRowArray();
            } else {
                $ta_aktif = $this->db->table('tahun_ajaran')->where('status', 'Aktif')->get()->getRowArray();
            }

            $fTA_TA   = $this->db->fieldExists('tahun', 'tahun_ajaran') ? 'tahun' : 'tahun_ajaran';
            $tahun    = $ta_aktif ? $ta_aktif[$fTA_TA] : '';
            $semester = $ta_aktif ? $ta_aktif['semester'] : '';
            $id_ta    = $ta_aktif ? $ta_aktif['id'] : 0;

            $fTA_Catatan = $this->db->fieldExists('tahun_ajaran_id', 'catatan_rapor') ? 'tahun_ajaran_id' : 'tahun_ajaran';
            $catatan = $this->db->table('catatan_rapor')
                ->where('siswa_id', $siswa_id)
                ->where($fTA_Catatan, ($fTA_Catatan === 'tahun_ajaran_id' ? $id_ta : $tahun))
                ->where('semester', $semester)
                ->get()->getRowArray();

            if ($catatan && !isset($catatan['catatan_wali_kelas'])) {
                $catatan['catatan_wali_kelas'] = $catatan['catatan_wali'] ?? $catatan['catatan'] ?? '';
            }

            return $this->response->setJSON([
                'status'  => 'success',
                'catatan' => $catatan
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'DB Error Get: ' . $e->getMessage()]);
        }
    }

    public function saveCatatanRapor()
    {
        try {
            $siswaId = $this->request->getPost('siswa_id');
            $id_ta_get = $this->request->getPost('ta');
            if ($id_ta_get) {
                $ta_aktif = $this->db->table('tahun_ajaran')->where('id', $id_ta_get)->get()->getRowArray();
            } else {
                $ta_aktif = $this->db->table('tahun_ajaran')->where('status', 'Aktif')->get()->getRowArray();
            }

            $fTA_TA      = $this->db->fieldExists('tahun', 'tahun_ajaran') ? 'tahun' : 'tahun_ajaran';
            $tahun       = $ta_aktif ? $ta_aktif[$fTA_TA] : '';
            $semester_ta = $ta_aktif ? $ta_aktif['semester'] : '';
            $id_ta_db    = $ta_aktif ? $ta_aktif['id'] : 0;

            $dataCatatan = [
                'siswa_id'        => $siswaId,
                'status_kenaikan' => $this->request->getPost('status_kenaikan')
            ];

            $fTA_Catatan = $this->db->fieldExists('tahun_ajaran_id', 'catatan_rapor') ? 'tahun_ajaran_id' : 'tahun_ajaran';
            $dataCatatan[$fTA_Catatan] = ($fTA_Catatan === 'tahun_ajaran_id' ? $id_ta_db : $tahun);
            $dataCatatan['semester'] = $semester_ta;

            $fieldsCatatan = $this->db->getFieldNames('catatan_rapor');
            if (in_array('catatan_wali_kelas', $fieldsCatatan)) {
                $dataCatatan['catatan_wali_kelas'] = $this->request->getPost('catatan_wali');
            } elseif (in_array('catatan', $fieldsCatatan)) {
                $dataCatatan['catatan'] = $this->request->getPost('catatan_wali');
            }

            $builderCatatan = $this->db->table('catatan_rapor');
            $existCatatan = $builderCatatan->where([
                'siswa_id' => $siswaId,
                $fTA_Catatan => ($fTA_Catatan === 'tahun_ajaran_id' ? $id_ta_db : $tahun),
                'semester' => $semester_ta
            ])->get()->getRow();

            if ($existCatatan) {
                if (!$builderCatatan->where('id', $existCatatan->id)->update($dataCatatan)) {
                    throw new \Exception('Gagal update catatan: ' . json_encode($this->db->error()));
                }
            } else {
                if (!$builderCatatan->insert($dataCatatan)) {
                    throw new \Exception('Gagal insert catatan: ' . json_encode($this->db->error()));
                }
            }

            return $this->response->setJSON(['status' => 'success']);
        } catch (\Exception $e) {
            return $this->response->setJSON(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    public function printPDF($siswa_id, $action = 'preview')
    {
        $jenisRapor = $this->request->getGet('jenis_rapor') ?? 'lengkap';
        $optCover   = $this->request->getGet('cover') === '1';
        $optTtd     = $this->request->getGet('ttd') === '1' || true;
        $optQr      = $this->request->getGet('qr') === '1';
        $kategori   = $this->request->getGet('kategori') ?? 'Akhir Semester';

        $id_ta_get = $this->request->getGet('ta');
        if ($id_ta_get) {
            $ta_aktif = $this->db->table('tahun_ajaran')->where('id', $id_ta_get)->get()->getRowArray();
        } else {
            $ta_aktif = $this->db->table('tahun_ajaran')->where('status', 'Aktif')->get()->getRowArray();
        }

        $fTA_TA       = $this->db->fieldExists('tahun', 'tahun_ajaran') ? 'tahun' : 'tahun_ajaran';
        $tahun_ajaran = $ta_aktif ? $ta_aktif[$fTA_TA] : '2024/2025';
        $semester     = $ta_aktif ? $ta_aktif['semester'] : 'Ganjil';
        $ta_id        = $ta_aktif ? $ta_aktif['id'] : 0;

        $siswa = $this->db->table('siswa')
            ->select('siswa.*, ar.rombel_id as ar_rombel_id, rombel.nama_rombel, rombel.tingkat, guru_tendik.nama_lengkap as wali_kelas, guru_tendik.nuptk as wali_nuptk, guru_tendik.id as wali_id, guru_tendik.ttd_digital as wali_ttd, ortu.nama_ayah, ortu.nama_ibu, ortu.nama_wali, ortu.alamat_orangtua, ortu.pekerjaan_ayah, ortu.pekerjaan_ibu')
            ->join('anggota_rombel ar', "ar.siswa_id = siswa.id AND ar.tahun_ajaran_id = {$ta_id} AND ar.semester = '{$semester}'", 'left')
            ->join('rombel', 'rombel.id = COALESCE(ar.rombel_id, siswa.rombel_id)', 'left')
            ->join('guru_tendik', 'guru_tendik.id = rombel.wali_kelas_id', 'left')
            ->join('orangtua_wali ortu', 'ortu.siswa_id = siswa.id', 'left')
            ->where('siswa.id', $siswa_id)
            ->get()->getRowArray();

        if (!$siswa) return "Data siswa tidak ditemukan.";

        $siswa['rombel_id'] = !empty($siswa['ar_rombel_id']) ? $siswa['ar_rombel_id'] : $siswa['rombel_id'];
        $sekolah = $this->db->table('sekolah s')
            ->select('s.*, k.nama as kabupaten_nama, kec.nama as kecamatan_nama, d.nama as desa_nama, p.nama as provinsi_nama')
            ->join('kabupaten k', 'CONVERT(k.kode USING utf8mb4) COLLATE utf8mb4_general_ci = CONVERT(s.kabupaten USING utf8mb4) COLLATE utf8mb4_general_ci', 'left', false)
            ->join('kecamatan kec', 'CONVERT(kec.kode USING utf8mb4) COLLATE utf8mb4_general_ci = CONVERT(s.kecamatan USING utf8mb4) COLLATE utf8mb4_general_ci', 'left', false)
            ->join('desa d', 'CONVERT(d.kode USING utf8mb4) COLLATE utf8mb4_general_ci = CONVERT(s.desa_id USING utf8mb4) COLLATE utf8mb4_general_ci', 'left', false)
            ->join('propinsi p', 'CONVERT(p.kode USING utf8mb4) COLLATE utf8mb4_general_ci = CONVERT(s.provinsi USING utf8mb4) COLLATE utf8mb4_general_ci', 'left', false)
            ->get()->getRowArray();
        // ============================================================
        // PERBAIKAN TUGAS 2 & 3: TELP/FAX DAN FONT ALAMAT
        // ============================================================
        if ($sekolah) {
            $sekolah['telp'] = !empty($sekolah['telepon']) ? $sekolah['telepon'] : '-';
            $sekolah['no_telp'] = !empty($sekolah['telepon']) ? $sekolah['telepon'] : '-';
            $sekolah['telepon_fax'] = !empty($sekolah['telepon']) ? $sekolah['telepon'] : '-';

            // JURUS PAMUNGKAS: Bersihkan semua tag HTML gaib yang nyangkut!
            if (!empty($sekolah['alamat'])) {
                $sekolah['alamat'] = strip_tags($sekolah['alamat']);
            }
        }

        $kepsek = $this->db->table('guru_tendik g')
            ->select('g.*')
            ->join('master_jabatan j', 'j.id = g.jabatan_id', 'left')
            ->like('j.nama_jabatan', 'Kepala Sekolah')
            ->get()->getRowArray();

        $jadwal_mapel = [];

        if ($this->db->tableExists('jadwal_pelajaran')) {
            $fTA_Jadwal = $this->db->fieldExists('id_tahun_ajaran', 'jadwal_pelajaran') ? 'id_tahun_ajaran' : 'tahun_ajaran_id';
            $jp = $this->db->table('jadwal_pelajaran jp')
                ->select('m.id, m.nama_mapel, m.kkm, m.nomor_urut')
                ->join('mata_pelajaran m', 'CONVERT(m.id USING utf8mb4) COLLATE utf8mb4_general_ci = CONVERT(jp.mapel_id USING utf8mb4) COLLATE utf8mb4_general_ci', 'left', false)
                ->where('jp.rombel_id', $siswa['rombel_id'])
                ->where('jp.' . $fTA_Jadwal, $ta_id)
                ->get()->getResultArray();
            foreach ($jp as $m) {
                if (!empty($m['id'])) $jadwal_mapel[$m['id']] = $m;
            }
        }

        if ($this->db->tableExists('guru_mapel')) {
            $fTA_GM = $this->db->fieldExists('tahun_ajaran_id', 'guru_mapel') ? 'tahun_ajaran_id' : 'tahun_ajaran';
            $gm = $this->db->table('guru_mapel gm')
                ->select('m.id, m.nama_mapel, m.kkm, m.nomor_urut')
                ->join('mata_pelajaran m', 'CONVERT(m.id USING utf8mb4) COLLATE utf8mb4_general_ci = CONVERT(gm.mapel_id USING utf8mb4) COLLATE utf8mb4_general_ci', 'left', false)
                ->where('gm.rombel_id', $siswa['rombel_id'])
                ->where('gm.' . $fTA_GM, ($fTA_GM === 'tahun_ajaran_id' ? $ta_id : $tahun_ajaran))
                ->get()->getResultArray();
            foreach ($gm as $m) {
                if (!empty($m['id'])) $jadwal_mapel[$m['id']] = $m;
            }
        }

        if (empty($jadwal_mapel) && $this->db->tableExists('mata_pelajaran')) {
            $all_mapel = $this->db->table('mata_pelajaran')->get()->getResultArray();
            foreach ($all_mapel as $m) {
                if (!empty($m['id'])) $jadwal_mapel[$m['id']] = $m;
            }
        }

        $nilai_db = $this->db->table('nilai_rapor nr')
            ->select('nr.*, m.nama_mapel, m.kkm, m.nomor_urut')
            ->join('mata_pelajaran m', 'CONVERT(m.id USING utf8mb4) COLLATE utf8mb4_general_ci = CONVERT(nr.mapel_id USING utf8mb4) COLLATE utf8mb4_general_ci', 'left', false)
            ->where('nr.siswa_id', $siswa_id)
            ->where('nr.tahun_ajaran_id', $ta_id)
            ->where('nr.kategori', $kategori)
            ->get()->getResultArray();

        $mapNilai = [];
        foreach ($nilai_db as $nr) {
            $mapNilai[$nr['mapel_id']] = $nr;
            if (!isset($jadwal_mapel[$nr['mapel_id']]) && !empty($nr['nama_mapel'])) {
                $jadwal_mapel[$nr['mapel_id']] = [
                    'id'         => $nr['mapel_id'],
                    'nama_mapel' => $nr['nama_mapel'],
                    'kkm'        => $nr['kkm'],
                    'nomor_urut' => $nr['nomor_urut']
                ];
            }
        }

        $filtered_jadwal = [];
        $kata_kunci_kecuali = ['tahfidz', 'tahfiz', 'tahsin', 'bpi'];

        foreach ($jadwal_mapel as $m) {
            $nama_mapel_lower = strtolower($m['nama_mapel']);
            $is_dikecualikan = false;

            foreach ($kata_kunci_kecuali as $kata) {
                if (strpos($nama_mapel_lower, $kata) !== false) {
                    $is_dikecualikan = true;
                    break;
                }
            }

            if (!$is_dikecualikan) {
                $filtered_jadwal[] = $m;
            }
        }

        $jadwal_mapel = $filtered_jadwal;
        usort($jadwal_mapel, function ($a, $b) {
            $noA = (int)($a['nomor_urut'] ?? 0);
            $noB = (int)($b['nomor_urut'] ?? 0);
            if ($noA !== $noB) return $noA <=> $noB;
            return strcmp($a['nama_mapel'], $b['nama_mapel']);
        });

        $nilaiAkademik = [];
        $tabelAcuan = $this->db->tableExists('nilai_akademik') ? 'nilai_akademik' : ($this->db->tableExists('nilai_formatif') ? 'nilai_formatif' : 'nilai_sumatif');
        $fieldNilai = $this->db->fieldExists('nilai_angka', $tabelAcuan) ? 'nilai_angka' : 'nilai';
        $fieldType  = $this->db->fieldExists('jenis_penilaian', $tabelAcuan) ? 'jenis_penilaian' : ($this->db->fieldExists('jenis_nilai', $tabelAcuan) ? 'jenis_nilai' : 'jenis_sumatif');
        $fieldTA    = $this->db->fieldExists('tahun_ajaran_id', $tabelAcuan) ? 'tahun_ajaran_id' : 'tahun_ajaran';
        $hasSemester = $this->db->fieldExists('semester', $tabelAcuan);

        $nama_siswa_format = ucwords(strtolower(trim($siswa['nama_lengkap'])));
        $target_lm = ($kategori === 'Tengah Semester') ? 'LM 5' : 'LM 8';

        foreach ($jadwal_mapel as $m) {
            $nilai_akhir = null;
            $predikat = '-';
            $deskripsi = '-';

            if (isset($mapNilai[$m['id']])) {
                $nr = $mapNilai[$m['id']];
                $nilai_akhir = $nr['nilai_akhir'];
                $predikat    = $nr['predikat'];

                $deskripsi = $this->getDeskripsiDinamis(
                    $siswa_id,
                    $m['id'],
                    $ta_id,
                    $semester,
                    $siswa['tingkat'],
                    $kategori,
                    $nama_siswa_format,
                    $nilai_akhir
                );
            }

            $nilaiAkademik[] = [
                'nama_mapel' => $m['nama_mapel'],
                'nilai_akhir' => $nilai_akhir !== null ? round($nilai_akhir) : '-',
                'predikat'   => $predikat,
                'deskripsi'  => $deskripsi
            ];
        }

        $fieldCatatanTA = $this->db->tableExists('catatan_rapor') && $this->db->fieldExists('tahun_ajaran_id', 'catatan_rapor') ? 'tahun_ajaran_id' : 'tahun_ajaran';
        $catatanRes = $this->db->table('catatan_rapor')->where([
            'siswa_id' => $siswa_id,
            $fieldCatatanTA => ($fieldCatatanTA === 'tahun_ajaran_id' ? $ta_id : $tahun_ajaran),
            'semester' => $semester
        ])->get()->getRowArray();

        $catatan = $catatanRes;
        if ($catatan && !isset($catatan['catatan_wali_kelas'])) {
            $catatan['catatan_wali_kelas'] = $catatan['catatan_wali'] ?? $catatan['catatan'] ?? '';
        }

        if ($semester === 'Genap' && (stripos($kategori, 'Akhir') !== false || stripos($kategori, 'SAS') !== false)) {
            if (empty($catatan['status_kenaikan']) || $catatan['status_kenaikan'] == '-') {
                $tingkatSekarang = (int) preg_replace('/[^0-9]/', '', $siswa['tingkat']);
                $catatan['status_kenaikan'] = ($tingkatSekarang >= 9) ? "LULUS DARI SATUAN PENDIDIKAN" : "NAIK KE KELAS " . ($tingkatSekarang + 1);
            }
        } else {
            $catatan['status_kenaikan'] = null;
        }

        $sakit = 0;
        $izin = 0;
        $alpha = 0;
        $rombelId = $siswa['rombel_id'] ?? 0;

        if ($this->db->tableExists('absensi_harian')) {
            $sakit = $this->db->table('absensi_harian')->where(['siswa_id' => $siswa_id, 'rombel_id' => $rombelId, 'status' => 'Sakit'])->countAllResults();
            $izin  = $this->db->table('absensi_harian')->where(['siswa_id' => $siswa_id, 'rombel_id' => $rombelId, 'status' => 'Izin'])->countAllResults();
            $alpha = $this->db->table('absensi_harian')->where(['siswa_id' => $siswa_id, 'rombel_id' => $rombelId, 'status' => 'Alpha'])->countAllResults();
        } elseif ($this->db->tableExists('rekap_absensi')) {
            $fieldRekapTA = $this->db->fieldExists('tahun_ajaran_id', 'rekap_absensi') ? 'tahun_ajaran_id' : 'tahun_ajaran';
            $rekap = $this->db->table('rekap_absensi')->where(['siswa_id' => $siswa_id, $fieldRekapTA => ($fieldRekapTA === 'tahun_ajaran_id' ? $ta_id : $tahun_ajaran), 'semester' => $semester])->get()->getRowArray();
            if ($rekap) {
                $sakit = $rekap['sakit'] ?? 0;
                $izin  = $rekap['izin'] ?? 0;
                $alpha = $rekap['alpha'] ?? 0;
            }
        }
        $absen = ['sakit' => $sakit, 'izin' => $izin, 'alpha' => $alpha];

        $ekskul = [];
        if ($this->db->tableExists('nilai_ekskul')) {
            $fieldEkskulTA = $this->db->fieldExists('tahun_ajaran_id', 'nilai_ekskul') ? 'tahun_ajaran_id' : 'tahun_ajaran';
            $valTAEkskul = ($fieldEkskulTA === 'tahun_ajaran_id') ? $ta_id : $tahun_ajaran;

            if ($this->db->tableExists('master_ekskul')) {
                $ekskul_id_field = $this->db->fieldExists('ekskul_id', 'nilai_ekskul') ? 'ekskul_id' : 'id_ekskul';
                $builderEkskul = $this->db->table('nilai_ekskul ne')
                    ->select('me.nama_ekskul as kegiatan, ne.predikat, ne.keterangan, ne.deskripsi')
                    ->join('master_ekskul me', "me.id = ne.$ekskul_id_field", 'left')
                    ->where(['ne.siswa_id' => $siswa_id, 'ne.' . $fieldEkskulTA => $valTAEkskul, 'ne.semester' => $semester]);

                if ($this->db->fieldExists('kategori', 'nilai_ekskul')) {
                    $builderEkskul->where('ne.kategori', $kategori);
                }
                $ekskul = $builderEkskul->get()->getResultArray();
            } else {
                $builderEkskul = $this->db->table('nilai_ekskul')
                    ->select('nama_kegiatan as kegiatan, predikat, keterangan, deskripsi')
                    ->where(['siswa_id' => $siswa_id, $fieldEkskulTA => $valTAEkskul, 'semester' => $semester]);

                if ($this->db->fieldExists('kategori', 'nilai_ekskul')) {
                    $builderEkskul->where('kategori', $kategori);
                }
                $ekskul = $builderEkskul->get()->getResultArray();
            }
        }

        $tahfidz = [];
        if ($this->db->tableExists('nilai_tahfidz')) {
            $fieldTahfidzTA = $this->db->fieldExists('tahun_ajaran_id', 'nilai_tahfidz') ? 'tahun_ajaran_id' : 'tahun_ajaran';
            $valTATahfidz = ($fieldTahfidzTA === 'tahun_ajaran_id') ? $ta_id : $tahun_ajaran;
            $builderTahfidz = $this->db->table('nilai_tahfidz')->where(['siswa_id' => $siswa_id, $fieldTahfidzTA => $valTATahfidz, 'semester' => $semester]);

            if ($this->db->fieldExists('kategori', 'nilai_tahfidz')) {
                $builderTahfidz->where('kategori', $kategori);
            }
            $tahfidz = $builderTahfidz->get()->getRowArray();
        }

        $tglRaporRaw = $this->request->getGet('tgl_rapor') ?? date('Y-m-d');
        $tanggal_rapor_cetak = $tglRaporRaw;
        if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $tglRaporRaw)) {
            $bulanIndo = [
                '01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April',
                '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus',
                '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
            ];
            $split = explode('-', $tglRaporRaw);
            if (count($split) === 3 && isset($bulanIndo[$split[1]])) {
                $tanggal_rapor_cetak = $split[2] . ' ' . $bulanIndo[$split[1]] . ' ' . $split[0];
            }
        }

        $data = [
            'siswa'           => $siswa,
            'nilai'           => $nilaiAkademik,
            'catatan'         => $catatan,
            'absen'           => $absen,
            'ekskul'          => $ekskul,
            'tahfidz'         => $tahfidz,
            'sekolah'         => $sekolah,
            'kepsek'          => $kepsek,
            'tahun_ajaran'    => $tahun_ajaran,
            'semester'        => $semester,
            'kategori'        => $kategori,
            'tanggal_rapor'   => $tanggal_rapor_cetak,
            'tempat_rapor'    => $this->request->getGet('tempat') ?? 'Surakarta',
            'opt_cover'       => $optCover,
            'opt_ttd'         => $optTtd,
            'opt_qr'          => $optQr,
            'id_ta_aktif'     => $ta_id,
            'color'           => $this->getColor(),
            'logo_path'       => FCPATH . 'uploads/logo/' . ($sekolah['logo'] ?? 'none.png'),
            'link_verifikasi' => base_url('validasi/rapor/' . strtr(rtrim(base64_encode($siswa_id . '|' . $ta_id . '|' . str_replace(' ', '_', $kategori)), '='), '+/=', '-_,')),
        ];

        $p_color  = !empty($sekolah['warna_primary']) ? $sekolah['warna_primary'] : '#10b981';
        $wm_text  = strtoupper($sekolah['nama_sekolah'] ?? 'SMPIT AD DURRAH');
        $wm_color = $this->blendWithWhite($p_color, 0.23);

        $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="90" height="18">
                  <text x="50%" y="50%" font-family="Arial" font-size="8" fill="' . $wm_color . '" text-anchor="middle" dominant-baseline="middle">' . $wm_text . '</text>
                </svg>';
        $data['watermark_svg'] = base64_encode($svg);

        if ($jenisRapor === 'akademik') {
            $html = view('admin/print/rapor_akademik', $data);
        } elseif ($jenisRapor === 'karakter') {
            $html = view('admin/print/rapor_karakter', $data);
        } elseif ($jenisRapor === 'tahfidz') {
            $html = view('admin/print/rapor_tahfidz', $data);
        } else {
            $html = view('admin/print/rapor_lengkap', $data);
        }

        $mpdf = new \Mpdf\Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4',
            'margin_top' => 10,
            'margin_bottom' => 10,
            'margin_left' => 15,
            'margin_right' => 15
        ]);

        $mpdf->showWatermarkImage = true;
        $mpdf->SetFooter('Rapor Siswa - ' . ($sekolah['nama_sekolah'] ?? 'SMPIT Ad Durrah') . '||Halaman {PAGENO}');
        $mpdf->WriteHTML($html);

        $namaSiswaFix = str_replace(' ', '_', preg_replace('/[^a-zA-Z0-9 ]/', '', $siswa['nama_lengkap']));
        $nisSiswa     = $siswa['nis'] ?? '000';
        $katShort     = (stripos(($kategori ?? ''), 'Tengah') !== false) ? 'STS' : 'SAS';

        if ($jenisRapor === 'tahfidz') {
            $juzIdRequested = $this->request->getGet('juz') ?? '30';
            $filename = "Rapor_Tahfidz_Juz{$juzIdRequested}_{$nisSiswa}_{$namaSiswaFix}.pdf";
        } else {
            $filename = "rapor_{$nisSiswa}_{$namaSiswaFix}_{$katShort}_Lengkap.pdf";
        }

        $dest = ($action == 'download') ? 'D' : 'I';

        return $this->response
            ->setHeader('Content-Type', 'application/pdf')
            ->setHeader('Content-Disposition', 'inline; filename="' . $filename . '"')
            ->setBody($mpdf->Output($filename, 'S'));
    }

    private function getDeskripsiDinamis($siswa_id, $mapel_id, $tahun_id, $semester, $tingkat, $jenis_rapor, $nama_siswa, $nilai_akhir_fallback = null)
    {
        $tabelAcuan = $this->db->tableExists('nilai_akademik') ? 'nilai_akademik' : ($this->db->tableExists('nilai_formatif') ? 'nilai_formatif' : 'nilai_sumatif');
        $fieldTA    = $this->db->fieldExists('tahun_ajaran_id', $tabelAcuan) ? 'tahun_ajaran_id' : 'tahun_ajaran';
        $fieldNilai = $this->db->fieldExists('nilai_angka', $tabelAcuan) ? 'nilai_angka' : 'nilai';
        $fieldType  = $this->db->fieldExists('jenis_penilaian', $tabelAcuan) ? 'jenis_penilaian' : ($this->db->fieldExists('jenis_nilai', $tabelAcuan) ? 'jenis_nilai' : 'jenis_sumatif');

        if (!$this->db->tableExists('master_lm')) {
            return "-";
        }

        $tingkatClean = preg_replace('/[^0-9]/', '', (string)$tingkat);
        if (empty($tingkatClean)) {
            $romToNum = ['VII' => '7', 'VIII' => '8', 'IX' => '9', 'X' => '10', 'XI' => '11', 'XII' => '12'];
            $tingkatClean = $romToNum[strtoupper($tingkat)] ?? $tingkat;
        }

        $kategoriDB = 'Akhir';
        if (stripos($jenis_rapor, 'Tengah') !== false || stripos($jenis_rapor, 'STS') !== false) {
            $kategoriDB = 'Tengah';
        }

        $rawNilai = $this->db->table($tabelAcuan)
            ->where(['siswa_id' => $siswa_id, 'mapel_id' => $mapel_id, $fieldTA => $tahun_id])
            ->get()->getResultArray();

        if (empty($rawNilai) && $nilai_akhir_fallback === null) return "Kompetensi sudah tercapai sesuai dengan kriteria yang ditetapkan.";

        $nilaiLM = [];
        if (!empty($rawNilai)) {
            foreach ($rawNilai as $rn) {
                $kodeEntry = strtoupper(preg_replace('/[^A-Z0-9]/', '', (string)$rn[$fieldType]));

                $lm = $this->db->table('master_lm')
                    ->where([
                        'mapel_id' => $mapel_id,
                        'tingkat'  => $tingkatClean,
                        'kategori' => $kategoriDB
                    ])
                    ->where("REPLACE(kode_lm, ' ', '') =", $kodeEntry)
                    ->get()->getRowArray();

                if ($lm) {
                    $nilaiLM[] = array_merge($lm, ['nilai_angka' => $rn[$fieldNilai]]);
                }
            }
        }

        if (empty($nilaiLM)) {
            if ($nilai_akhir_fallback === null) return "Kompetensi sudah tercapai sesuai dengan kriteria yang ditetapkan.";

            $allLm = $this->db->table('master_lm')
                ->where([
                    'mapel_id' => $mapel_id,
                    'tingkat'  => $tingkatClean,
                    'kategori' => $kategoriDB
                ])
                ->orderBy('kode_lm', 'ASC')
                ->get()->getResultArray();

            if (empty($allLm)) return "Kompetensi sudah tercapai sesuai dengan kriteria yang ditetapkan.";

            foreach ($allLm as $lm) {
                $nilaiLM[] = array_merge($lm, ['nilai_angka' => $nilai_akhir_fallback]);
            }
        }

        usort($nilaiLM, function ($a, $b) {
            return $b['nilai_angka'] <=> $a['nilai_angka'];
        });

        $best = $nilaiLM[0];
        $worst = $nilaiLM[count($nilaiLM) - 1];

        $getTeks = function ($row) {
            $n = (float)$row['nilai_angka'];
            if ($n >= 90) return $row['deskripsi_a'];
            if ($n >= 80) return $row['deskripsi_b'];
            if ($n >= 75) return $row['deskripsi_c'];
            return $row['deskripsi_d'];
        };

        $teksTinggi = $getTeks($best);
        $teksRendah = $getTeks($worst);

        if (count($nilaiLM) === 1 || $best['id'] === $worst['id']) {
            return $this->formatTeksDeskripsi($teksTinggi, $nama_siswa);
        }

        $deskripsiFinal = $this->formatTeksDeskripsi($teksTinggi, $nama_siswa);

        if ($worst['nilai_angka'] < 80) {
            $deskripsiFinal .= ", namun " . $this->formatTeksDeskripsi($teksRendah, "");
        } else {
            $deskripsiFinal .= " dan " . $this->formatTeksDeskripsi($teksRendah, "");
        }

        return $deskripsiFinal;
    }

    private function formatTeksDeskripsi($teks, $nama)
    {
        if (empty($teks)) return "";
        if (strpos($teks, '[NAMA]') !== false) {
            return str_replace('[NAMA]', $nama, $teks);
        }
        return (!empty($nama) ? $nama . " " : "") . lcfirst($teks);
    }

    private function blendWithWhite($hex, $weight)
    {
        $hex = str_replace("#", "", $hex);
        if (strlen($hex) == 3) {
            $r = hexdec(substr($hex, 0, 1) . substr($hex, 0, 1));
            $g = hexdec(substr($hex, 1, 1) . substr($hex, 1, 1));
            $b = hexdec(substr($hex, 2, 1) . substr($hex, 2, 1));
        } else {
            $r = hexdec(substr($hex, 0, 2));
            $g = hexdec(substr($hex, 2, 2));
            $b = hexdec(substr($hex, 4, 2));
        }
        $r = round($r * $weight + 255 * (1 - $weight));
        $g = round($g * $weight + 255 * (1 - $weight));
        $b = round($b * $weight + 255 * (1 - $weight));
        return sprintf("#%02x%02x%02x", $r, $g, $b);
    }
}
