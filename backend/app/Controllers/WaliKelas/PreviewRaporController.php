<?php

namespace App\Controllers\WaliKelas;

use App\Controllers\WaliKelasBaseController;

class PreviewRaporController extends WaliKelasBaseController
{
    private function getGuru() {
        $db = \Config\Database::connect();
        $userId = session()->get('user_id') ?? session()->get('id_user') ?? session()->get('id');
        return $db->table('guru_tendik')->where('user_id', $userId)->get()->getRowArray();
    }

    private function getRombelWaliKelas($guru_id, $ta_id_override = null) {
        $db = \Config\Database::connect();
        if (!$guru_id) return null;

        $id_ta_aktif = $ta_id_override;
        if (!$id_ta_aktif) {
            $ta_aktif = $db->table('tahun_ajaran')->where('status', 'Aktif')->get()->getRowArray();
            $id_ta_aktif = $ta_aktif ? $ta_aktif['id'] : 0;
        }

        // Deteksi kolom tahun ajaran di tabel rombel (biasanya tahun_ajaran_id atau id_tahun_ajaran)
        $fTA = $db->fieldExists('tahun_ajaran_id', 'rombel') ? 'tahun_ajaran_id' : ($db->fieldExists('id_tahun_ajaran', 'rombel') ? 'id_tahun_ajaran' : 'tahun_ajaran');

        // Cari rombel untuk guru ini di tahun ajaran terpilih
        $rombel = $db->table('rombel')
                     ->where('wali_kelas_id', $guru_id)
                     ->where($fTA, $id_ta_aktif)
                     ->get()->getRowArray();


        if ($rombel) {
            $ta_asli = $db->table('tahun_ajaran')->where('id', $rombel[$fTA] ?? 0)->get()->getRowArray();
            $rombel['tahun_ajaran'] = $ta_asli ? $ta_asli['tahun'] : '2024/2025';
            $rombel['semester']     = $ta_asli ? $ta_asli['semester'] : 'Ganjil';
            $rombel['id_tahun_ajaran'] = $rombel[$fTA] ?? 0; // Kompatibilitas dengan modul lain
            return $rombel;
        }
        return null; 
    }

    public function index()
    {
        $db = \Config\Database::connect();
        $guru = $this->getGuru();
        $ta_id_get = $this->request->getGet('ta');
        $rombel = $this->getRombelWaliKelas($guru ? $guru['id'] : null, $ta_id_get);
        
        $sekolah = $db->table('sekolah')->select('warna_primary, warna_secondary')->get()->getRowArray();
        $color = [
            'warna_primary'   => $sekolah ? $sekolah['warna_primary'] : '#10b981',
            'warna_secondary' => $sekolah ? $sekolah['warna_secondary'] : '#ecfdf5',
        ];

        // MENGAMBIL DATA SISWA
        $students = [];
        if ($rombel) {
            $students = $db->table('siswa')
                           ->select('id, nama_lengkap as name, nis')
                           ->where('rombel_id', $rombel['id'])
                           ->orderBy('nama_lengkap', 'ASC')
                           ->get()->getResultArray();
        }

        $navs = method_exists($this, 'getSidebarMenu') ? $this->getSidebarMenu() : session()->get('menu');

        // MENGAMBIL DAFTAR TAHUN AJARAN (UNTUK DROPDOWN)
        $list_ta = $db->table('tahun_ajaran')->orderBy('id', 'DESC')->get()->getResultArray();
        
        // RESOLUSI TAHUN AJARAN (UNTUK DASHBOARD)
        $id_ta_resolve = $ta_id_get;
        if (!$id_ta_resolve) {
            $ta_aktif = $db->table('tahun_ajaran')->where('status', 'Aktif')->get()->getRowArray();
            $id_ta_resolve = $ta_aktif ? $ta_aktif['id'] : 0;
        }

        $ta_record = $db->table('tahun_ajaran')->where('id', $id_ta_resolve)->get()->getRowArray();
        $fTA_Field = $db->fieldExists('tahun', 'tahun_ajaran') ? 'tahun' : 'tahun_ajaran';
        
        $tahun_ajaran = $ta_record ? ($ta_record[$fTA_Field] ?? '2024/2025') : '2024/2025';
        $semester     = $ta_record ? ($ta_record['semester'] ?? 'Ganjil') : 'Ganjil';

        $sekolah = $db->table('sekolah s')
            ->select('s.*, k.nama as kabupaten_nama')
            ->join('kabupaten k', 'CONVERT(k.kode USING utf8mb4) COLLATE utf8mb4_general_ci = CONVERT(s.kabupaten USING utf8mb4) COLLATE utf8mb4_general_ci', 'left', false)
            ->get()->getRowArray();

        $tanggal_rapor_raw = $ta_record ? ($ta_record['tanggal_rapor'] ?? date('Y-m-d')) : date('Y-m-d');
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

        $data = [
            'title'        => 'Cetak Rapor Kelas',
            'user'         => session()->get('nama_lengkap') ?? 'Wali Kelas',
            'navigations'  => $navs, 
            'color'        => $color,
            'students'     => $students,
            'api_url'      => base_url('wali/preview-rapor'), 
            'rombel_id'    => $rombel ? $rombel['id'] : '',
            'rombel_name'  => $rombel ? $rombel['nama_rombel'] : 'Belum Ada Kelas',
            'tingkat'      => $rombel ? $rombel['tingkat'] : '-',
            'tahun_ajaran' => $tahun_ajaran, 
            'semester'     => $semester,
            'list_ta'      => $list_ta, 
            'tanggal_rapor' => $tanggal_rapor_ymd,
            'tempat_rapor'  => $sekolah ? ($sekolah['kabupaten_nama'] ?? 'Surakarta') : 'Surakarta',
            'wali_kelas'   => $guru ? $guru['nama_lengkap'] : 'Nama Wali Kelas',
            'guru'         => $guru,
            'sekolah'      => $sekolah
        ];
        
        return view('WaliKelas/preview-rapor', $data); 
    }

    public function uploadTtdWali()
    {
        $file = $this->request->getFile('ttd');
        if (!$file || !$file->isValid()) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'File tidak valid']);
        }

        $guru = $this->getGuru();
        if (!$guru) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Data profil guru tidak ditemukan.']);
        }

        $uploadPath = FCPATH . 'assets/uploads/ttd/';
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0777, true);
        }

        $newName = 'ttd_wali_' . $guru['id'] . '_' . time() . '.webp';
        
        try {
            $image = \Config\Services::image()->withFile($file->getTempName());
            $image->save($uploadPath . $newName); 
        } catch (\Exception $e) {
            $file->move($uploadPath, $newName);
        }

        // Update DB
        $db = \Config\Database::connect();
        $db->table('guru_tendik')->where('id', $guru['id'])->update(['ttd_digital' => $newName]);

        // Hapus file lama jika ada
        if (!empty($guru['ttd_digital']) && file_exists($uploadPath . $guru['ttd_digital'])) {
            @unlink($uploadPath . $guru['ttd_digital']);
        }

        return $this->response->setJSON([
            'status' => 'success', 
            'message' => 'Tanda tangan Anda berhasil diupload',
            'filename' => base_url('assets/uploads/ttd/' . $newName)
        ]);
    }

    public function printPDF($siswa_id, $action = 'preview')
    {
        $db = \Config\Database::connect();

        $jenisRapor = $this->request->getGet('jenis_rapor') ?? 'lengkap';
        $optCover   = $this->request->getGet('cover') === '1';
        $optTtd     = $this->request->getGet('ttd') === '1' || true;
        $optQr      = $this->request->getGet('qr') === '1';
        $ta_id_get  = $this->request->getGet('ta'); // Menangkap Tahun Ajaran Pilihan

        $guru = $this->getGuru();
        $rombelData = $this->getRombelWaliKelas($guru ? $guru['id'] : null, $ta_id_get);
        
        if (!$rombelData) {
            return "Akses Ditolak: Anda belum memiliki kelas wali yang aktif pada periode ini.";
        }

        // --- SISTEM RESOLUSI TAHUN AJARAN (SYNC ADMIN) ---
        $ta_aktif = $db->table('tahun_ajaran')->where('status', 'Aktif')->get()->getRowArray();
        $fTA_TA   = $db->fieldExists('tahun', 'tahun_ajaran') ? 'tahun' : 'tahun_ajaran';
        $tahun_ajaran = $ta_aktif ? $ta_aktif[$fTA_TA] : $rombelData['tahun_ajaran'];
        $semester     = $ta_aktif ? $ta_aktif['semester'] : $rombelData['semester'];
        $ta_id        = $ta_aktif ? $ta_aktif['id'] : 0;

        $siswa = $db->table('siswa')
            ->select('
                siswa.*, 
                rombel.nama_rombel, 
                rombel.tingkat, 
                guru_tendik.id as wali_id, 
                guru_tendik.nama_lengkap as wali_kelas, 
                guru_tendik.nuptk as wali_nuptk,
                guru_tendik.ttd_digital as wali_ttd
            ')
            ->join('rombel', 'CONVERT(rombel.id USING utf8mb4) COLLATE utf8mb4_general_ci = CONVERT(siswa.rombel_id USING utf8mb4) COLLATE utf8mb4_general_ci', 'left', false)
            ->join('guru_tendik', 'CONVERT(guru_tendik.id USING utf8mb4) COLLATE utf8mb4_general_ci = CONVERT(rombel.wali_kelas_id USING utf8mb4) COLLATE utf8mb4_general_ci', 'left', false)
            ->where('siswa.id', $siswa_id)
            ->where('siswa.rombel_id', $rombelData['id']) 
            ->get()->getRowArray();

        if (!$siswa) return "Data siswa tidak ditemukan atau bukan bagian dari kelas Anda.";

        // Mengambil data orang tua agar sinkron dengan format Rapor Lengkap
        $ortu = $db->table('orangtua_wali')->where('siswa_id', $siswa_id)->get()->getRowArray();
        if ($ortu) {
            $siswa['nama_ayah']       = $ortu['nama_ayah'] ?? '-';
            $siswa['nama_ibu']        = $ortu['nama_ibu'] ?? '-';
            $siswa['nama_wali']       = $ortu['nama_wali'] ?? '-';
            $siswa['alamat_orangtua'] = $ortu['alamat_orangtua'] ?? '-';
            $siswa['pekerjaan_ayah']  = $ortu['pekerjaan_ayah'] ?? '-';
            $siswa['pekerjaan_ibu']   = $ortu['pekerjaan_ibu'] ?? '-';
        }

        $sekolah = $db->table('sekolah s')
            ->select('s.*, k.nama as kabupaten_nama, kec.nama as kecamatan_nama, d.nama as desa_nama, p.nama as provinsi_nama')
            ->join('kabupaten k', 'k.kode = s.kabupaten', 'left')
            ->join('kecamatan kec', 'kec.kode = s.kecamatan', 'left')
            ->join('desa d', 'd.kode = s.desa_id', 'left')
            ->join('propinsi p', 'p.kode = s.provinsi', 'left')
            ->get()->getRowArray();

        $kepsek = $db->table('guru_tendik g')
            ->select('g.*')
            ->join('master_jabatan j', 'j.id = g.jabatan_id', 'left')
            ->like('j.nama_jabatan', 'Kepala Sekolah', 'both')
            ->get()->getRowArray();

        $tabelAcuan = $db->tableExists('nilai_akademik') ? 'nilai_akademik' : ($db->tableExists('nilai_formatif') ? 'nilai_formatif' : 'nilai_sumatif');
        $fieldNilai = $db->fieldExists('nilai_angka', $tabelAcuan) ? 'nilai_angka' : 'nilai';
        $fieldType  = $db->fieldExists('jenis_penilaian', $db->tableExists('nilai_akademik') ? 'nilai_akademik' : $tabelAcuan) ? 'jenis_penilaian' : ($db->fieldExists('jenis_nilai', $tabelAcuan) ? 'jenis_nilai' : 'jenis_sumatif');
        $fieldSemester = $db->fieldExists('semester', $tabelAcuan);
        
        $fieldLookUpTA = $db->fieldExists('tahun', 'tahun_ajaran') ? 'tahun' : 'tahun_ajaran';
        // ta_id and tahun_ajaran already defined at top of method matching Admin sync

        $jadwal_mapel = [];
        $kategori     = $this->request->getGet('kategori') ?? 'Akhir Semester'; // Pencocokan dengan Admin get parameter

        // a. Cari dari Tabel Jadwal Pelajaran
        if ($db->tableExists('jadwal_pelajaran')) {
            $fTA_Jadwal = $db->fieldExists('tahun_ajaran_id', 'jadwal_pelajaran') ? 'tahun_ajaran_id' : ($db->fieldExists('id_tahun_ajaran', 'jadwal_pelajaran') ? 'id_tahun_ajaran' : 'tahun_ajaran');
            $jp = $db->table('jadwal_pelajaran jp')
                ->select('m.id, m.nama_mapel, m.kkm, m.nomor_urut')
                ->join('mata_pelajaran m', 'CONVERT(m.id USING utf8mb4) COLLATE utf8mb4_general_ci = CONVERT(jp.mapel_id USING utf8mb4) COLLATE utf8mb4_general_ci', 'left', false)
                ->where('jp.rombel_id', $rombelData['id'])
                ->where('jp.' . $fTA_Jadwal, $ta_id)
                ->get()->getResultArray();
            foreach ($jp as $m) { if (!empty($m['id'])) $jadwal_mapel[$m['id']] = $m; }
        }

        // b. Cari dari Tabel Guru Mapel
        if ($db->tableExists('guru_mapel')) {
            $fTA_GM = $db->fieldExists('tahun_ajaran_id', 'guru_mapel') ? 'tahun_ajaran_id' : 'tahun_ajaran';
            $gm = $db->table('guru_mapel gm')
                ->select('m.id, m.nama_mapel, m.kkm, m.nomor_urut')
                ->join('mata_pelajaran m', 'CONVERT(m.id USING utf8mb4) COLLATE utf8mb4_general_ci = CONVERT(gm.mapel_id USING utf8mb4) COLLATE utf8mb4_general_ci', 'left', false)
                ->where('gm.rombel_id', $rombelData['id'])
                ->where('gm.' . $fTA_GM, $ta_id) // Admin menggunakan ID
                ->where('gm.status', 'active')
                ->get()->getResultArray();
            foreach ($gm as $m) { if (!empty($m['id'])) $jadwal_mapel[$m['id']] = $m; }
        }

        // c. Ambil dari Tabel Nilai Rapor (Primary Source)
        $mapNilai = [];
        if ($db->tableExists('nilai_rapor')) {
            $fTA_NR = $db->fieldExists('tahun_ajaran_id', 'nilai_rapor') ? 'tahun_ajaran_id' : 'tahun_ajaran';
            $nilai_db = $db->table('nilai_rapor nr')
                ->select('nr.*, m.nama_mapel, m.kkm, m.nomor_urut')
                ->join('mata_pelajaran m', 'm.id = nr.mapel_id', 'left')
                ->where([
                    'nr.siswa_id' => $siswa_id, 
                    'nr.' . $fTA_NR => $ta_id, // Admin menggunakan ID
                    'nr.kategori' => $kategori
                ])->get()->getResultArray();
            
            foreach ($nilai_db as $nr) {
                $mapNilai[$nr['mapel_id']] = $nr;
                if (!isset($jadwal_mapel[$nr['mapel_id']]) && !empty($nr['nama_mapel'])) {
                    $jadwal_mapel[$nr['mapel_id']] = ['id' => $nr['mapel_id'], 'nama_mapel' => $nr['nama_mapel'], 'kkm' => $nr['kkm'], 'nomor_urut' => $nr['nomor_urut']];
                }
            }
        }

        // d. FALLBACK: Jika masih kosong, ambil semua mapel
        if (empty($jadwal_mapel) && $db->tableExists('mata_pelajaran')) {
            $all_m = $db->table('mata_pelajaran')->get()->getResultArray();
            foreach ($all_m as $m) { if (!empty($m['id'])) $jadwal_mapel[$m['id']] = $m; }
        }

        // e. FILTER EXCLUDE (Mengecualikan Mapel Tahfidz/Tahsin/BPI)
        $filtered_jadwal = [];
        $kata_kunci_kecuali = ['tahfidz', 'tahfiz', 'tahsin', 'bpi'];
        foreach ($jadwal_mapel as $m) {
            $nama_mapel_lower = strtolower($m['nama_mapel']);
            $is_dikecualikan = false;
            foreach ($kata_kunci_kecuali as $kata) { if (strpos($nama_mapel_lower, $kata) !== false) { $is_dikecualikan = true; break; } }
            if (!$is_dikecualikan) { $filtered_jadwal[] = $m; }
        }
        $jadwal_mapel = $filtered_jadwal;
        usort($jadwal_mapel, function ($a, $b) { 
            $noA = (int)($a['nomor_urut'] ?? 0);
            $noB = (int)($b['nomor_urut'] ?? 0);
            if ($noA !== $noB) return $noA <=> $noB;
            return strcmp($a['nama_mapel'], $b['nama_mapel']); 
        });

        // ======================================================================
        // 5. RAKIT DATA NILAI AKADEMIK & DESKRIPSI (DARI LM TERAKHIR - SYNC ADMIN)
        // ======================================================================
        $nilai = [];
        
        // Perbaikan: Gunakan nama lengkap dan ubah jadi Title Case (cth: ADZIN NAFIS -> Adzin Nafis)
        $nama_siswa_format = ucwords(strtolower(trim($siswa['nama_lengkap'])));

        foreach ($jadwal_mapel as $m) {
            $nilai_akhir = null;
            $predikat = '-';
            $deskripsi = '-';

            if (isset($mapNilai[$m['id']])) {
                $nr = $mapNilai[$m['id']];
                $nilai_akhir = $nr['nilai_akhir'];
                $predikat    = $nr['predikat'] ?? '-';

                // RAKIT DESKRIPSI DINAMIS BERDASARKAN LM (SYNC ADMIN)
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

            $nilai[] = [
                'nama_mapel' => $m['nama_mapel'],
                'nilai_akhir' => $nilai_akhir !== null ? round($nilai_akhir) : '-',
                'kkm'        => $m['kkm'] ?? 75,
                'predikat'   => $predikat,
                'deskripsi'  => $deskripsi
            ];
        }

        // ======================================================================
        // 6. CATATAN WALI KELAS & KENAIKAN KELAS (SYNC ADMIN)
        // ======================================================================
        $catatan = [];
        if ($db->tableExists('catatan_rapor')) {
            $fTA_Catatan = $db->fieldExists('tahun_ajaran_id', 'catatan_rapor') ? 'tahun_ajaran_id' : 'tahun_ajaran';
            $val_ta = ($fTA_Catatan === 'tahun_ajaran_id') ? $ta_id : $tahun_ajaran;
            
            $catatan = $db->table('catatan_rapor')
                ->where(['siswa_id' => $siswa_id, $fTA_Catatan => $val_ta, 'semester' => $semester])
                ->get()->getRowArray();
                
            if ($catatan && !isset($catatan['catatan_wali_kelas'])) {
                $catatan['catatan_wali_kelas'] = $catatan['catatan_wali'] ?? $catatan['catatan'] ?? '';
            }

            // Logika Kenaikan Kelas Otomatis (Hanya untuk Semester Genap - AKHIR SEMESTER)
            if ($semester === 'Genap' && (stripos($kategori, 'Akhir') !== false || stripos($kategori, 'SAS') !== false)) {
                if (empty($catatan['status_kenaikan']) || $catatan['status_kenaikan'] == '-') {
                    $tingkatSekarang = (int) preg_replace('/[^0-9]/', '', $siswa['tingkat']);
                    if ($tingkatSekarang > 0) {
                        $catatan['status_kenaikan'] = ($tingkatSekarang >= 9) ? "LULUS DARI SATUAN PENDIDIKAN" : "NAIK KE KELAS " . ($tingkatSekarang + 1);
                    }
                }
            } else {
                $catatan['status_kenaikan'] = null; // Sembunyikan jika Ganjil atau Tengah Semester (STS)
            }
        }

        // =========================================================================
        // 7. MESIN PENGHITUNG ABSENSI OTOMATIS (SYNC ADMIN)
        // =========================================================================
        $sakit = 0; $izin = 0; $alpha = 0;
        $rombelId = $siswa['rombel_id'] ?? 0;

        if ($db->tableExists('absensi_harian')) {
            $sakit = $db->table('absensi_harian')->where(['siswa_id' => $siswa_id, 'rombel_id' => $rombelId, 'status' => 'Sakit'])->countAllResults();
            $izin  = $db->table('absensi_harian')->where(['siswa_id' => $siswa_id, 'rombel_id' => $rombelId, 'status' => 'Izin'])->countAllResults();
            $alpha = $db->table('absensi_harian')->where(['siswa_id' => $siswa_id, 'rombel_id' => $rombelId, 'status' => 'Alpha'])->countAllResults();
        } elseif ($db->tableExists('rekap_absensi')) {
            $fTA_Rekap = $db->fieldExists('tahun_ajaran_id', 'rekap_absensi') ? 'tahun_ajaran_id' : 'tahun_ajaran';
            $val_ta_rekap = ($fTA_Rekap === 'tahun_ajaran_id') ? $ta_id : $tahun_ajaran;
            $rekap = $db->table('rekap_absensi')->where(['siswa_id' => $siswa_id, $fTA_Rekap => $val_ta_rekap, 'semester' => $semester])->get()->getRowArray();
            if ($rekap) {
                $sakit = $rekap['sakit'] ?? 0;
                $izin  = $rekap['izin'] ?? 0;
                $alpha = $rekap['alpha'] ?? 0;
            }
        } elseif ($db->tableExists('absensi_siswa')) {
            $fTA_AbsSiswa = $db->fieldExists('tahun_ajaran_id', 'absensi_siswa') ? 'tahun_ajaran_id' : 'tahun_ajaran';
            $val_ta_abs = ($fTA_AbsSiswa === 'tahun_ajaran_id') ? $ta_id : $tahun_ajaran; // Admin Sync
            $qAbsen = $db->table('absensi_siswa')->where(['siswa_id' => $siswa_id, $fTA_AbsSiswa => $val_ta_abs, 'semester' => $semester])->get()->getRowArray();
            if ($qAbsen) {
                $sakit = $qAbsen['sakit'] ?? 0;
                $izin  = $qAbsen['izin'] ?? 0;
                $alpha = $qAbsen['alpha'] ?? 0;
            }
        }
        $absen = ['sakit' => $sakit, 'izin' => $izin, 'alpha' => $alpha];

        // =========================================================================
        // 8. EKSKUL & TAHFIDZ (SYNC ADMIN)
        // =========================================================================
        $ekskul = [];
        if ($db->tableExists('nilai_ekskul')) {
            $fTA_Ekskul = $db->fieldExists('tahun_ajaran_id', 'nilai_ekskul') ? 'tahun_ajaran_id' : 'tahun_ajaran';
            $val_ta_ekskul = ($fTA_Ekskul === 'tahun_ajaran_id') ? $ta_id : $tahun_ajaran;
            
            if ($db->tableExists('master_ekskul')) {
                $ekskul_id_field = $db->fieldExists('ekskul_id', 'nilai_ekskul') ? 'ekskul_id' : 'id_ekskul';
                $ekskul = $db->table('nilai_ekskul ne')
                            ->select('me.nama_ekskul as kegiatan, ne.predikat, ne.keterangan')
                            ->join('master_ekskul me', "CONVERT(me.id USING utf8mb4) COLLATE utf8mb4_general_ci = CONVERT(ne.$ekskul_id_field USING utf8mb4) COLLATE utf8mb4_general_ci", 'left', false)
                            ->where(['ne.siswa_id' => $siswa_id, 'ne.' . $fTA_Ekskul => $val_ta_ekskul, 'ne.semester' => $semester])
                            ->get()->getResultArray();
            } else {
                $ekskul = $db->table('nilai_ekskul')
                            ->select('nama_kegiatan as kegiatan, predikat, keterangan')
                            ->where(['siswa_id' => $siswa_id, 'fTA_Ekskul' => $val_ta_ekskul, 'semester' => $semester])
                            ->get()->getResultArray();
            }
        }

        $tahfidz = null;
        $surahList = [];
        $setoranMap = [];
        $juz_info = null;
        $metrics_teori = ['huruf' => '-', 'derajat' => '-', 'taqdir' => '-'];
        $metrics_setoran = ['huruf' => '-', 'derajat' => '-', 'taqdir' => '-'];

        if ($jenisRapor === 'tahfidz') {
            $juz_id = $this->request->getGet('juz') ?? 30;
            $juz_info = $db->table('ref_juz')->where('id', $juz_id)->get()->getRowArray();
            $juz_nama_db = $juz_info ? trim($juz_info['nama_juz']) : "Juz $juz_id";

            $standardMap = $this->getStandardJuzMap();
            $surahDb = $db->table('ref_surah')->get()->getResultArray();
            $surahIdMap = [];
            foreach($surahDb as $s) {
                $alpha = preg_replace('/[^a-z0-9]/', '', strtolower(trim($s['nama_surah'])));
                if ($alpha === 'allahab') $alpha = 'almasad';
                $surahIdMap[$alpha] = $s['id'];
            }

            $blocks = isset($standardMap[$juz_nama_db]) ? $standardMap[$juz_nama_db] : [];
            if (!empty($blocks)) {
                foreach ($blocks as $block) {
                    $ayat = 'Semua';
                    if (preg_match('/^(.*?)\s+([0-9-]+)$/', $block, $m)) { $s_name = trim($m[1]); $ayat = trim($m[2]); }
                    else { $s_name = trim($block); }
                    $alpha_search = preg_replace('/[^a-z0-9]/', '', strtolower($s_name));
                    if ($alpha_search === 'allahab') $alpha_search = 'almasad';
                    $s_id = $surahIdMap[$alpha_search] ?? null;
                    $surahList[] = ['surah_id' => $s_id, 'nama_surah' => $s_name, 'ayat' => $ayat, 'display' => $block];
                }
            } else if ($juz_info && $juz_info['mulai_surah_id'] && $juz_info['sampai_surah_id']) {
                $surahQuery = $db->table('ref_surah')->where("id >=", $juz_info['mulai_surah_id'])->where("id <=", $juz_info['sampai_surah_id'])->orderBy('id', 'ASC')->get()->getResultArray();
                foreach($surahQuery as $s) { $surahList[] = ['surah_id' => $s['id'], 'nama_surah' => $s['nama_surah'], 'ayat' => 'Semua', 'display' => $s['nama_surah']]; }
            }

            $fieldTahfidzTA = $db->fieldExists('tahun_ajaran_id', 'nilai_tahfidz') ? 'tahun_ajaran_id' : 'tahun_ajaran';
            $val_ta_tahfidz = ($fieldTahfidzTA === 'tahun_ajaran_id') ? $ta_id : $tahun_ajaran;
            $tahfidz = $db->table('nilai_tahfidz')->where(['siswa_id' => $siswa_id, $fieldTahfidzTA => $val_ta_tahfidz, 'semester' => $semester])->get()->getRowArray();
            
            if ($tahfidz) {
                $metrics_teori = $this->getGradeMetrics($tahfidz['nilai_teori'] ?? 0);
                $metrics_setoran = $this->getGradeMetrics($tahfidz['nilai_setoran'] ?? 0);
            }

            $tahun_str = explode('/', $tahun_ajaran)[0]; 
            if ($semester === 'Ganjil') {
                $tgl_mulai = $tahun_str . '-07-01'; $tgl_akhir = $tahun_str . '-12-31'; 
            } else {
                $tahun_genap = (int)$tahun_str + 1; $tgl_mulai = $tahun_genap . '-01-01'; $tgl_akhir = $tahun_genap . '-06-30'; 
            }

            $setoran = $db->table('setoran_tahfidz')
                ->where('siswa_id', $siswa_id)
                ->where('juz_id', $juz_id)
                ->where('tanggal >=', $tgl_mulai)->where('tanggal <=', $tgl_akhir)
                ->get()->getResultArray();
            
            foreach($setoran as $st) {
                $db_ayat = (!empty($st['ayat']) && $st['ayat'] !== '-') ? trim($st['ayat']) : 'Semua';
                $setoranMap[$st['surah_id'] . '|' . $db_ayat] = $st['nilai'];
            }
        } else {
            if ($db->tableExists('nilai_tahfidz')) {
                $fieldTahfidzTA = $db->fieldExists('tahun_ajaran_id', 'nilai_tahfidz') ? 'tahun_ajaran_id' : 'tahun_ajaran';
                $tahfidz = $db->table('nilai_tahfidz')->where(['siswa_id' => $siswa_id, $fieldTahfidzTA => $tahun_ajaran, 'semester' => $semester])->get()->getRowArray();
            }
        }

        $kategori    = $this->request->getGet('kategori') ?? 'Akhir Semester';

        // --- SISTEM WATERMARK TEKS (SVG Pattern) ---
        $p_color = !empty($sekolah['warna_primary']) ? $sekolah['warna_primary'] : '#10b981';
        $wm_text = strtoupper($sekolah['nama_sekolah'] ?? 'SMPIT AD DURRAH');
        $wm_color = $this->blendWithWhite($p_color, 0.23); 

        $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="90" height="18">
                  <text x="50%" y="50%" font-family="Arial" font-size="8" fill="' . $wm_color . '" text-anchor="middle" dominant-baseline="middle">' . $wm_text . '</text>
                </svg>';

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
            'nilai'           => $nilai,
            'catatan'         => $catatan,
            'absen'           => $absen,
            'ekskul'          => $ekskul,
            'tahfidz'         => $tahfidz,
            'surahList'       => $surahList,
            'setoranMap'      => $setoranMap,
            'juz_info'        => $juz_info,
            'metrics_teori'   => $metrics_teori,
            'metrics_setoran' => $metrics_setoran,
            'sekolah'         => $sekolah,
            'kepsek'          => $kepsek,
            'tahun_ajaran'    => $tahun_ajaran,
            'semester'        => $semester,
            'kategori'        => $kategori,
            'tanggal_rapor'   => $tanggal_rapor_cetak,
            'tempat_rapor'    => $this->request->getGet('tempat') ?? 'Surakarta',
            'id_ta_aktif'     => $ta_id,
            'link_verifikasi' => base_url('validasi/rapor/' . strtr(rtrim(base64_encode($siswa_id . '|' . $ta_id . '|' . str_replace(' ', '_', $kategori)), '='), '+/=', '-_,')),
            'opt_cover'       => $optCover,
            'opt_ttd'         => $optTtd,
            'opt_qr'          => $optQr,
            'keputusan_kenaikan' => $catatan['status_kenaikan'] ?? '',
            'watermark_svg'    => base64_encode($svg),
            'logo_path'        => FCPATH . 'uploads/logo/' . ($sekolah['logo'] ?? 'none.png'),
            'color'            => ['warna_primary' => $p_color, 'warna_secondary' => ($sekolah['warna_secondary'] ?? '#ecfdf5')],
        ];

        if ($jenisRapor === 'akademik') {
            $html = view('admin/print/rapor_akademik', $data);
        } elseif ($jenisRapor === 'karakter') {
            $html = view('admin/print/rapor_karakter', $data);
        } elseif ($jenisRapor === 'tahfidz') {
            $html = view('admin/print/rapor_tahfidz', $data);
        } else {
            $html = view('admin/print/rapor_lengkap', $data);
        }

        $mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'A4', 'margin_top' => 10, 'margin_bottom' => 10, 'margin_left' => 15, 'margin_right' => 15]);
        $mpdf->showWatermarkImage = true;
        $mpdf->WriteHTML($html);

        // FORMULASI NAMA FILE SESUAI PERMINTAAN PEMBIMBING PKL
        $namaSiswaFix = str_replace(' ', '_', preg_replace('/[^a-zA-Z0-9 ]/', '', $siswa['nama_lengkap']));
        $nisSiswa     = $siswa['nis'] ?? '000';
        $kategori     = $this->request->getGet('kategori') ?? 'Akhir Semester';
        $katShort     = (stripos($kategori, 'Tengah') !== false) ? 'STS' : 'SAS';

        if ($jenisRapor === 'tahfidz') {
            $juzIdRequested = $this->request->getGet('juz') ?? '30';
            $filename = "Rapor_Tahfidz_Juz{$juzIdRequested}_{$nisSiswa}_{$namaSiswaFix}.pdf";
        } else {
            $filename = "rapor_{$nisSiswa}_{$namaSiswaFix}_{$katShort}_Lengkap.pdf";
        }

        return $this->response
            ->setHeader('Content-Type', 'application/pdf')
            ->setHeader('Content-Disposition', 'inline; filename="' . $filename . '"')
            ->setBody($mpdf->Output($filename, 'S'));
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

    private function getDeskripsiDinamis($siswa_id, $mapel_id, $tahun_id, $semester, $tingkat, $jenis_rapor, $nama_siswa, $nilai_akhir_fallback = null)
    {
        $db = \Config\Database::connect();
        $tabelAcuan = $db->tableExists('nilai_akademik') ? 'nilai_akademik' : ($db->tableExists('nilai_formatif') ? 'nilai_formatif' : 'nilai_sumatif');
        $fieldTA    = $db->fieldExists('tahun_ajaran_id', $tabelAcuan) ? 'tahun_ajaran_id' : 'tahun_ajaran';
        $fieldNilai = $db->fieldExists('nilai_angka', $tabelAcuan) ? 'nilai_angka' : 'nilai';
        $fieldType  = $db->fieldExists('jenis_penilaian', $tabelAcuan) ? 'jenis_penilaian' : ($db->fieldExists('jenis_nilai', $tabelAcuan) ? 'jenis_nilai' : 'jenis_sumatif');

        if (!$db->tableExists('master_lm')) {
            return "-";
        }

        // NORMALISASI: Pastikan tingkat hanya angka (misal: Kelas 7 -> 7, VII -> 7)
        $tingkatClean = preg_replace('/[^0-9]/', '', (string)$tingkat);
        if (empty($tingkatClean)) {
            // Fallback untuk Romawi sederhana jika diperlukan
            $romToNum = ['VII' => '7', 'VIII' => '8', 'IX' => '9', 'X' => '10', 'XI' => '11', 'XII' => '12'];
            $tingkatClean = $romToNum[strtoupper($tingkat)] ?? $tingkat;
        }

        // Tentukan kategori pencarian di database (Support: STS, SAS, Tengah, Akhir)
        $kategoriDB = 'Akhir';
        if (stripos($jenis_rapor, 'Tengah') !== false || stripos($jenis_rapor, 'STS') !== false) {
            $kategoriDB = 'Tengah';
        }

        // Ambil data nilai aslinya dulu
        $rawNilai = $db->table($tabelAcuan)
            ->where(['siswa_id' => $siswa_id, 'mapel_id' => $mapel_id, $fieldTA => $tahun_id])
            ->get()->getResultArray();

        if (empty($rawNilai) && $nilai_akhir_fallback === null) return "Kompetensi sudah tercapai sesuai dengan kriteria yang ditetapkan.";

        // Cari deskripsi LM yang COCOK
        $nilaiLM = [];
        if (!empty($rawNilai)) {
            foreach ($rawNilai as $rn) {
                $kodeEntry = strtoupper(preg_replace('/[^A-Z0-9]/', '', (string)$rn[$fieldType]));
                
                // Cari di master_lm dengan pembersihan kode yang sama
                $lm = $db->table('master_lm')
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
            // FALLBACK: Jika tidak ada nilai per-LM, ambil SEMUA materi mapel ini dan gunakan NILAI AKHIR rapor
            if ($nilai_akhir_fallback === null) return "Kompetensi sudah tercapai sesuai dengan kriteria yang ditetapkan.";

            $allLm = $db->table('master_lm')
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

        // Urutkan dari nilai tertinggi ke terendah
        usort($nilaiLM, function($a, $b) { return $b['nilai_angka'] <=> $a['nilai_angka']; });

        $best = $nilaiLM[0];
        $worst = $nilaiLM[count($nilaiLM) - 1];

        // LOGIKA PENENTUAN TEKS BERDASARKAN NILAI (A, B, C, D)
        $getTeks = function($row) {
            $n = (float)$row['nilai_angka'];
            if ($n >= 90) return $row['deskripsi_a'];
            if ($n >= 80) return $row['deskripsi_b'];
            if ($n >= 75) return $row['deskripsi_c'];
            return $row['deskripsi_d'];
        };

        $teksTinggi = $getTeks($best);
        $teksRendah = $getTeks($worst);

        // Jika LM yang tertinggi dan terendah sama (hanya ada 1 LM), atau teksnya sama
        if (count($nilaiLM) === 1 || $best['id'] === $worst['id']) {
            return $this->formatTeksDeskripsi($teksTinggi, $nama_siswa);
        }

        // Jika beda LM
        $deskripsiFinal = $this->formatTeksDeskripsi($teksTinggi, $nama_siswa);
        
        // Tambahkan bagian "perlu peningkatan" jika nilai terendah memang rendah (C atau D)
        if ($worst['nilai_angka'] < 80) {
            $deskripsiFinal .= ", namun " . $this->formatTeksDeskripsi($teksRendah, "");
        } else {
            // Jika nilai terendah pun masih bagus (B), cukup gabungkan dengan kata "dan"
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

    private function getWatermarkBase64($text)
    {
        $text = strtoupper($text ?: 'SCHOOL REPORT');
        $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="120" height="18"><text x="50%" y="13" font-family="Arial" font-size="9" font-weight="bold" fill="#DAA520" fill-opacity="0.16" text-anchor="middle">'.$text.'</text></svg>';
        return base64_encode($svg);
    }
    private function getStandardJuzMap() {
        return [
            "Juz 1"  => ["Al-Fatihah", "Al-Baqarah 1-25", "Al-Baqarah 26-43", "Al-Baqarah 44-59", "Al-Baqarah 60-74", "Al-Baqarah 75-91", "Al-Baqarah 92-105", "Al-Baqarah 106-123", "Al-Baqarah 124-141"],
            "Juz 2"  => ["Al-Baqarah 142-157", "Al-Baqarah 158-176", "Al-Baqarah 177-188", "Al-Baqarah 189-202", "Al-Baqarah 203-218", "Al-Baqarah 219-232", "Al-Baqarah 233-242", "Al-Baqarah 243-252"],
            "Juz 3"  => ["Al-Baqarah 253-262", "Al-Baqarah 263-271", "Al-Baqarah 272-282", "Al-Baqarah 283-286", "Ali 'Imran 1-14", "Ali 'Imran 15-32", "Ali 'Imran 33-51", "Ali 'Imran 52-74", "Ali 'Imran 75-91"],
            "Juz 4"  => ["Ali 'Imran 92-112", "Ali 'Imran 113-132", "Ali 'Imran 133-152", "Ali 'Imran 153-170", "Ali 'Imran 171-185", "Ali 'Imran 186-200", "An-Nisa' 1-11", "An-Nisa' 12-23"],
            "Juz 5"  => ["An-Nisa' 12-23", "An-Nisa' 24-35", "An-Nisa' 36-57", "An-Nisa' 58-73", "An-Nisa' 74-87", "An-Nisa' 88-99", "An-Nisa' 100-113", "An-Nisa' 114-134", "An-Nisa' 135-147"],
            "Juz 6"  => ["An-Nisa' 148-162", "An-Nisa' 163-176", "Al-Ma'idah 1-11", "Al-Ma'idah 12-26", "Al-Ma'idah 27-40", "Al-Ma'idah 41-50", "Al-Ma'idah 51-66", "Al-Ma'idah 67-83"],
            "Juz 7"  => ["Al-Ma'idah 84-96", "Al-Ma'idah 97-108", "Al-Ma'idah 109-120", "Al-An'am 1-12", "Al-An'am 13-35", "Al-An'am 36-58", "Al-An'am 59-73", "Al-An'am 74-94", "Al-An'am 95-110"],
            "Juz 8"  => ["Al-An'am 111-126", "Al-An'am 127-140", "Al-An'am 141-150", "Al-An'am 151-165", "Al-A'raf 1-30", "Al-A'raf 31-46", "Al-A'raf 47-64", "Al-A'raf 65-87"],
            "Juz 9"  => ["Al-A'raf 88-116", "Al-A'raf 117-141", "Al-A'raf 142-155", "Al-A'raf 156-170", "Al-A'raf 171-188", "Al-A'raf 189-206", "Al-Anfal 1-21", "Al-Anfal 22-40"],
            "Juz 10" => ["Al-Anfal 41-60", "Al-Anfal 61-75", "At-Taubah 1-18", "At-Taubah 19-33", "At-Taubah 34-45", "At-Taubah 46-59", "At-Taubah 60-74", "At-Taubah 75-93"],
            "Juz 11" => ["At-Taubah", "Yunus", "Hud"],
            "Juz 12" => ["Hud", "Yusuf"],
            "Juz 13" => ["Yusuf", "Ar-Ra'd", "Ibrahim"],
            "Juz 14" => ["Al-Hijr", "An-Nahl"],
            "Juz 15" => ["Al-Isra'", "Al-Kahf"],
            "Juz 16" => ["Al-Kahf", "Maryam", "Ta Ha"],
            "Juz 17" => ["Al-Anbiya'", "Al-Hajj"],
            "Juz 18" => ["Al-Mu'minun", "An-Nur", "Al-Furqan"],
            "Juz 19" => ["Al-Furqan", "Asy-Syu'ara'", "An-Naml"],
            "Juz 20" => ["An-Naml", "Al-Qasas", "Al-'Ankabut"],
            "Juz 21" => ["Al-'Ankabut", "Ar-Rum", "Luqman", "As-Sajdah", "Al-Ahzab"],
            "Juz 22" => ["Al-Ahzab", "Saba'", "Fatir", "Ya Sin"],
            "Juz 23" => ["Ya Sin", "As-Saffat", "Sad", "Az-Zumar"],
            "Juz 24" => ["Az-Zumar", "Ghafir", "Fussilat"],
            "Juz 25" => ["Fussilat", "Asy-Syura", "Az-Zukhruf", "Ad-Dukhan", "Al-Jasiyah"],
            "Juz 26" => ["Al-Ahqaf", "Muhammad", "Al-Fath", "Al-Hujurat", "Qaf", "Az-Zariyat"],
            "Juz 27" => ["Az-Zariyat", "At-Tur", "An-Najm", "Al-Qamar", "Ar-Rahman", "Al-Waqi'ah", "Al-Hadid"],
            "Juz 28" => ["Al-Mujadilah", "Al-Hashr", "Al-Mumtahanah", "As-Saff", "Al-Jumu'ah", "Al-Munafiqun", "At-Taghabun", "At-Talaq", "At-Tahrim"],
            "Juz 29" => ["Al-Mulk", "Al-Qalam", "Al-Haqqah", "Al-Ma'arij", "Nuh", "Al-Jinn", "Al-Muzzammil", "Al-Muddassir", "Al-Qiyamah", "Al-Insan", "Al-Mursalat"],
            "Juz 30" => ["An-Naba'", "An-Nazi'at", "'Abasa", "At-Takwir", "Al-Infitar", "Al-Mutaffifin", "Al-Insyiqaq", "Al-Buruj", "At-Tariq", "Al-A'la", "Al-Ghasyiyah", "Al-Fajr", "Al-Balad", "Asy-Syams", "Al-Lail", "Ad-Duha", "Asy-Syarh", "At-Tin", "Al-'Alaq", "Al-Qadr", "Al-Bayyinah", "Az-Zalzalah", "Al-'Adiyat", "Al-Qari'ah", "At-Takasur", "Al-'Asr", "Al-Humazah", "Al-Fil", "Quraisy", "Al-Ma'un", "Al-Kausar", "Al-Kafirun", "An-Nasr", "Al-Masad", "Al-Ikhlas", "Al-Falaq", "An-Nas"]
        ];
    }

    private function getGradeMetrics($score)
    {
        if ($score >= 90) {
            return ['huruf' => 'A', 'derajat' => 'Mumtaz', 'taqdir' => 'Lulus'];
        } elseif ($score >= 80) {
            return ['huruf' => 'B', 'derajat' => 'Jayyid Jiddan', 'taqdir' => 'Lulus'];
        } elseif ($score >= 70) {
            return ['huruf' => 'C', 'derajat' => 'Jayyid', 'taqdir' => 'Lulus'];
        } elseif ($score >= 60) {
            return ['huruf' => 'D', 'derajat' => 'Maqbul', 'taqdir' => 'Lulus'];
        } else {
            return ['huruf' => 'E', 'derajat' => 'Mardud', 'taqdir' => "I'adah"];
        }
    }

    public function getCatatanSiswa()
    {
        try {
            $db = \Config\Database::connect();
            $siswaId = $this->request->getGet('siswa_id');
            $id_ta_get = $this->request->getGet('ta');

            if (!$siswaId || !$id_ta_get) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Parameter tidak lengkap']);
            }

            // Resolusi Tahun Ajaran & Semester (Sync Admin)
            $ta_aktif = $db->table('tahun_ajaran')->where('id', $id_ta_get)->get()->getRowArray() 
                      ?? $db->table('tahun_ajaran')->where('status', 'Aktif')->get()->getRowArray();
            
            $fTA_TA   = $db->fieldExists('tahun', 'tahun_ajaran') ? 'tahun' : 'tahun_ajaran';
            $tahun    = $ta_aktif ? $ta_aktif[$fTA_TA] : '';
            $semester = $ta_aktif ? $ta_aktif['semester'] : '';
            $id_ta    = $ta_aktif ? $ta_aktif['id'] : 0;

            // Deteksi nama kolom (Sync Admin)
            $fTA_Catatan = $db->fieldExists('tahun_ajaran_id', 'catatan_rapor') ? 'tahun_ajaran_id' : ($db->fieldExists('id_tahun_ajaran', 'catatan_rapor') ? 'id_tahun_ajaran' : 'tahun_ajaran');
            
            $catatan = $db->table('catatan_rapor')
                ->where('siswa_id', $siswaId)
                ->where($fTA_Catatan, ($fTA_Catatan === 'tahun_ajaran_id' || $fTA_Catatan === 'id_tahun_ajaran' ? $id_ta : $tahun))
                ->where('semester', $semester)
                ->get()->getRowArray();

            if ($catatan && !isset($catatan['catatan_wali_kelas'])) {
                $catatan['catatan_wali_kelas'] = $catatan['catatan_wali'] ?? $catatan['catatan'] ?? '';
            }

            return $this->response->setJSON([
                'status' => 'success',
                'catatan' => $catatan
            ]);
        } catch (\Throwable $e) {
            return $this->response->setJSON(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    public function saveCatatanRapor()
    {
        try {
            $db = \Config\Database::connect();
            $siswaId = $this->request->getPost('siswa_id');
            $id_ta_get = $this->request->getPost('ta');
            $catatanWali = $this->request->getPost('catatan_wali');
            $statusKenaikan = $this->request->getPost('status_kenaikan');

            if (!$siswaId || !$id_ta_get) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Gagal menyimpan: Parameter tidak lengkap']);
            }

            // Resolusi Tahun Ajaran & Semester (Sync Admin)
            $ta_aktif = $db->table('tahun_ajaran')->where('id', $id_ta_get)->get()->getRowArray() 
                      ?? $db->table('tahun_ajaran')->where('status', 'Aktif')->get()->getRowArray();
            
            $fTA_TA   = $db->fieldExists('tahun', 'tahun_ajaran') ? 'tahun' : 'tahun_ajaran';
            $tahun    = $ta_aktif ? $ta_aktif[$fTA_TA] : '';
            $semester = $ta_aktif ? $ta_aktif['semester'] : '';
            $id_ta    = $ta_aktif ? $ta_aktif['id'] : 0;

            $fTA_Catatan = $db->fieldExists('tahun_ajaran_id', 'catatan_rapor') ? 'tahun_ajaran_id' : ($db->fieldExists('id_tahun_ajaran', 'catatan_rapor') ? 'id_tahun_ajaran' : 'tahun_ajaran');
            $dataCatatan = [
                'siswa_id' => $siswaId,
                $fTA_Catatan => ($fTA_Catatan === 'tahun_ajaran_id' || $fTA_Catatan === 'id_tahun_ajaran' ? $id_ta : $tahun),
                'semester'   => $semester,
                'status_kenaikan' => $statusKenaikan
            ];

            // Deteksi field catatan (Sync Admin)
            $fields = $db->getFieldNames('catatan_rapor');
            if (in_array('catatan_wali_kelas', $fields)) {
                $dataCatatan['catatan_wali_kelas'] = $catatanWali;
            } elseif (in_array('catatan_wali', $fields)) {
                $dataCatatan['catatan_wali'] = $catatanWali;
            } elseif (in_array('catatan', $fields)) {
                $dataCatatan['catatan'] = $catatanWali;
            }

            $existing = $db->table('catatan_rapor')
                ->where('siswa_id', $siswaId)
                ->where($fTA_Catatan, ($fTA_Catatan === 'tahun_ajaran_id' || $fTA_Catatan === 'id_tahun_ajaran' ? $id_ta : $tahun))
                ->where('semester', $semester)
                ->get()->getRowArray();

            if ($existing) {
                // Gunakan primary key jika ada, jika tidak gunakan filter where lengkap
                if (isset($existing['id'])) {
                    $db->table('catatan_rapor')->where('id', $existing['id'])->update($dataCatatan);
                } else {
                    $db->table('catatan_rapor')->where([
                        'siswa_id' => $siswaId,
                        $fTA_Catatan => ($fTA_Catatan === 'tahun_ajaran_id' || $fTA_Catatan === 'id_tahun_ajaran' ? $id_ta : $tahun),
                        'semester' => $semester
                    ])->update($dataCatatan);
                }
            } else {
                $db->table('catatan_rapor')->insert($dataCatatan);
            }

            return $this->response->setJSON(['status' => 'success', 'message' => 'Catatan berhasil disimpan']);
        } catch (\Throwable $e) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Server Error: ' . $e->getMessage()]);
        }
    }
}