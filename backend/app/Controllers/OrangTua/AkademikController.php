<?php

namespace App\Controllers\OrangTua;

use App\Controllers\OrangTuaBaseController;
use Mpdf\Mpdf;

class AkademikController extends OrangTuaBaseController
{
    public function index()
    {
        $db = \Config\Database::connect();
        $userId = session()->get('user_id');

        // ===============================================================================
        // 1. AMBIL TAHUN AJARAN DAN SEMESTER AKTIF DARI DATABASE
        // ===============================================================================
        $ta_aktif = $db->table('tahun_ajaran')->where('status', 'Aktif')->get()->getRowArray();
        
        // PEMISAHAN TIPE DATA (Ini kunci yang menyelesaikan masalahnya!)
        $tahun_ajaran_id   = $ta_aktif ? $ta_aktif['id'] : 0; // Berupa Angka (misal: 9) -> Untuk nilai_formatif & ekskul
        $tahun_ajaran_teks = $ta_aktif ? $ta_aktif['tahun'] : '2025/2026'; // Berupa Teks -> Untuk catatan_rapor
        
        // Deteksi semester dari URL jika ortu pindah tab, jika tidak gunakan semester aktif saat ini
        $semester_aktif = $this->request->getGet('semester') ?? ($ta_aktif ? $ta_aktif['semester'] : 'Genap');
        // ===============================================================================

        // 2. CARI DATA ORANG TUA
        $orangTua = $db->table('orangtua_wali')->where('user_id', $userId)->get()->getRowArray();
        
        $sapaan = 'Bapak/Ibu';
        $namaOrangTua = session()->get('nama_lengkap') ?? session()->get('username');
        if ($orangTua) {
            if (!empty($orangTua['nama_ayah'])) { $sapaan = 'Bapak'; $namaOrangTua = $orangTua['nama_ayah']; } 
            elseif (!empty($orangTua['nama_ibu'])) { $sapaan = 'Ibu'; $namaOrangTua = $orangTua['nama_ibu']; } 
            elseif (!empty($orangTua['nama_wali'])) { $namaOrangTua = $orangTua['nama_wali']; }
        }
        $siswaId = $orangTua ? $orangTua['siswa_id'] : 0;

        // 3. CARI DATA ANAK
        $anak = $db->table('siswa')
                   ->select('siswa.id, siswa.nama_lengkap, siswa.nis, rombel.nama_rombel as kelas')
                   ->join('rombel', 'rombel.id = siswa.rombel_id', 'left')
                   ->where('siswa.id', $siswaId)
                   ->get()->getRowArray();

        $nilai = [];
        $rata_rata = 0;
        $total_mapel = 0;
        
        $mapel_terkuat = null;
        $mapel_perhatian = null;
        $ekskul = [];
        $catatan_wali = null;

        if ($anak) {
            // 4. AMBIL NILAI AKADEMIK (Menggunakan tahun_ajaran_id berupa Angka)
            $tabelAcuan = $db->tableExists('nilai_akademik') ? 'nilai_akademik' : ($db->tableExists('nilai_formatif') ? 'nilai_formatif' : 'nilai_sumatif');
            $fieldNilai = $db->fieldExists('nilai_angka', $tabelAcuan) ? 'nilai_angka' : 'nilai';
            $fieldSmt   = $db->fieldExists('semester', $tabelAcuan);
            $fieldTA    = $db->fieldExists('tahun_ajaran_id', $tabelAcuan) ? 'tahun_ajaran_id' : 'tahun_ajaran';

            if ($db->tableExists($tabelAcuan)) {
                $qNilai = $db->table($tabelAcuan)
                            ->select('mata_pelajaran.nama_mapel, mata_pelajaran.kkm, AVG(' . $tabelAcuan . '.' . $fieldNilai . ') as nilai_angka_avg')
                            ->join('mata_pelajaran', 'mata_pelajaran.id = ' . $tabelAcuan . '.mapel_id')
                            ->where($tabelAcuan . '.siswa_id', $anak['id']);
                
                if ($db->fieldExists('catatan', $tabelAcuan)) {
                    $qNilai->select('MAX('.$tabelAcuan.'.catatan) as catatan');
                } else {
                    $qNilai->select('MAX("") as catatan');
                }
                if ($db->fieldExists('predikat', $tabelAcuan)) {
                    $qNilai->select('MAX('.$tabelAcuan.'.predikat) as predikat');
                } else {
                    $qNilai->select('MAX("") as predikat');
                }
                            
                if ($fieldSmt) {
                    $qNilai->where($tabelAcuan . '.semester', $semester_aktif);
                }
                
                if ($fieldTA == 'tahun_ajaran_id') {
                    $qNilai->where($tabelAcuan . '.tahun_ajaran_id', $tahun_ajaran_id);
                } else {
                    $qNilai->where($tabelAcuan . '.tahun_ajaran', $tahun_ajaran_teks);
                }
                
                $nilaiRaw = $qNilai->groupBy($tabelAcuan . '.mapel_id, mata_pelajaran.nama_mapel, mata_pelajaran.kkm')
                            ->orderBy('mata_pelajaran.nama_mapel', 'ASC')
                            ->get()->getResultArray();
                
                foreach($nilaiRaw as $n){
                    $angka = round(floatval($n['nilai_angka_avg']));
                    if (empty($n['predikat'])) {
                        if ($angka >= 90) $n['predikat'] = 'A';
                        elseif ($angka >= 80) $n['predikat'] = 'B';
                        elseif ($angka >= 70) $n['predikat'] = 'C';
                        else $n['predikat'] = 'D';
                    }
                    if (empty($n['catatan'])) {
                        $n['catatan'] = "Ananda telah mengikuti kegiatan pembelajaran mata pelajaran ini dengan capaian $angka.";
                    }
                    $n['nilai_angka'] = $angka;
                    $n['kkm'] = $n['kkm'] ?? 75;
                    $nilai[] = $n;
                }

                $total_mapel = count($nilai);
                if ($total_mapel > 0) {
                    $total_nilai = array_sum(array_column($nilai, 'nilai_angka'));
                    $rata_rata = round($total_nilai / $total_mapel, 1);

                    $nilai_sorted = $nilai;
                    usort($nilai_sorted, function($a, $b) {
                        return $b['nilai_angka'] <=> $a['nilai_angka'];
                    });
                    $mapel_terkuat = $nilai_sorted[0]; 
                    $mapel_perhatian = end($nilai_sorted); 
                }
            }

            // 5. AMBIL DATA EKSTRAKURIKULER (Menggunakan tahun_ajaran_id berupa Angka)
            if ($db->tableExists('nilai_ekskul')) {
                $qEkskul = $db->table('nilai_ekskul')
                             ->where('siswa_id', $anak['id']);
                
                if ($db->fieldExists('semester', 'nilai_ekskul')) {
                    $qEkskul->where('semester', $semester_aktif);
                }
                
                if ($db->fieldExists('tahun_ajaran_id', 'nilai_ekskul')) {
                    $qEkskul->where('tahun_ajaran_id', $tahun_ajaran_id);
                } elseif ($db->fieldExists('tahun_ajaran', 'nilai_ekskul')) {
                    $qEkskul->where('tahun_ajaran', $tahun_ajaran_teks);
                }

                $ekskul = $qEkskul->get()->getResultArray();
            }

            // 6. AMBIL CATATAN WALI KELAS (Dinamis: Teks atau ID)
            if ($db->tableExists('catatan_rapor')) {
                $fCatTA = $db->fieldExists('tahun_ajaran_id', 'catatan_rapor') ? 'tahun_ajaran_id' : 'tahun_ajaran';
                $vCatTA = ($fCatTA === 'tahun_ajaran_id') ? $tahun_ajaran_id : $tahun_ajaran_teks;
                
                $catatan_wali = $db->table('catatan_rapor')
                                   ->where(['siswa_id' => $anak['id'], 'semester' => $semester_aktif, $fCatTA => $vCatTA])
                                   ->get()->getRowArray();
            }
        }

        $sekolah = $db->table('sekolah')->get()->getRowArray();
        $color = [
            'warna_primary'   => $sekolah ? $sekolah['warna_primary'] : '#10b981',
            'warna_secondary' => $sekolah ? $sekolah['warna_secondary'] : '#ecfdf5',
        ];

        $data = [
            'title'           => 'Akademik Ananda',
            'user'            => $namaOrangTua,
            'sapaan'          => $sapaan,
            'color'           => $color,
            'navigations'     => $this->getSidebarMenu(),
            'anak'            => $anak,
            'nilai'           => $nilai,
            'rata_rata'       => $rata_rata,
            'total_mapel'     => $total_mapel,
            'mapel_terkuat'   => $mapel_terkuat,
            'mapel_perhatian' => $mapel_perhatian,
            'ekskul'          => $ekskul,
            'catatan_wali'    => $catatan_wali,
            'semester_aktif'  => $semester_aktif 
        ];

        // Ambil status validasi/kunci rapor (Dinamis berdasarkan Rombel Anak)
        $is_locked = false;
        if ($anak && !empty($anak['rombel_id'])) {
            $validasi = $db->table('validasi_nilai')
                           ->where('rombel_id', $anak['rombel_id'])
                           ->get()->getRowArray();
            $is_locked = ($validasi && $validasi['is_locked'] == 1);
        }
        
        $data['is_locked'] = $is_locked;

        return view('OrangTua/akademik', $data);
    }

    /**
     * Cek apakah rapor sudah divalidasi/dikunci pihak sekolah
     */
    public function cekKetersediaan()
    {
        $db = \Config\Database::connect();
        $userId = session()->get('user_id');
        
        $ortu = $db->table('orangtua_wali')->where('user_id', $userId)->get()->getRowArray();
        if (!$ortu || empty($ortu['siswa_id'])) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Data ananda tidak terhubung.']);
        }

        $siswa = $db->table('siswa')->where('id', $ortu['siswa_id'])->get()->getRowArray();
        if (!$siswa) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Siswa tidak ditemukan.']);
        }

        // Ambil Parameter dari pilihan Orang Tua
        $ta_id    = $this->request->getGet('ta') ?? 0;
        $semester = $this->request->getGet('semester') ?? 'Ganjil';
        $tipe     = $this->request->getGet('tipe') ?? 'Akhir Semester';

        // Jika ta_id 0, ambil yang aktif
        if ($ta_id == 0) {
            $ta_aktif = $db->table('tahun_ajaran')->where('status', 'Aktif')->get()->getRowArray();
            $ta_id = $ta_aktif ? $ta_aktif['id'] : 0;
            $semester = $ta_aktif ? $ta_aktif['semester'] : $semester;
        }

        // =====================================================================
        // LOGIKA RADAR ALUMNI: CEK APAKAH SANTRI SUDAH LULUS KELAS 9
        // =====================================================================
        $ta_diminta = $db->table('tahun_ajaran')->where('id', $ta_id)->get()->getRowArray();
        $tahun_diminta = $ta_diminta ? $ta_diminta['tahun'] : '';

        // Cari riwayat kelulusan Kelas 9 (Join via anggota_rombel karena catatan_rapor tidak simpan rombel_id)
        $fTA_CR = $db->fieldExists('tahun_ajaran_id', 'catatan_rapor') ? 'tahun_ajaran_id' : 'tahun_ajaran';
        
        $kelulusan = $db->table('catatan_rapor cr')
                        ->select('ta.tahun as tahun_lulus')
                        ->join('tahun_ajaran ta', ($fTA_CR === 'tahun_ajaran_id' ? 'ta.id = cr.tahun_ajaran_id' : 'ta.tahun = cr.tahun_ajaran'))
                        ->join('anggota_rombel ar', 'ar.siswa_id = cr.siswa_id AND ar.tahun_ajaran_id = ta.id', 'left')
                        ->join('rombel r', 'r.id = ar.rombel_id', 'left')
                        ->where('cr.siswa_id', $siswa['id'])
                        ->where('r.tingkat', 9)
                        ->like('cr.status_kenaikan', 'LULUS')
                        ->orderBy('ta.tahun', 'DESC')
                        ->get()->getRowArray();

        if ($kelulusan && $tahun_diminta > $kelulusan['tahun_lulus']) {
            return $this->response->setJSON([
                'status' => 'error', 
                'message' => "Mohon maaf, ananda sudah dinyatakan LULUS/ALUMNI pada T.A {$kelulusan['tahun_lulus']}. Dokumen rapor periode {$tahun_diminta} sudah tidak tersedia."
            ]);
        }
        // =====================================================================

        // =====================================================================
        // 1. DETEKSI POSISI KELAS DINAMIS (Penting untuk Radar Masa Depan)
        // =====================================================================
        // Kita cari: Di tahun yang diminta orang tua, ananda terdaftar di kelas mana?
        $fArTA = $db->fieldExists('tahun_ajaran_id', 'anggota_rombel') ? 'tahun_ajaran_id' : 'tahun_ajaran';
        $vArTA = ($fArTA === 'tahun_ajaran_id') ? $ta_id : ($ta_diminta['tahun'] ?? '');

        $posisi = $db->table('anggota_rombel ar')
                     ->select('ar.rombel_id, r.nama_rombel, r.tingkat')
                     ->join('rombel r', 'r.id = ar.rombel_id')
                     ->where('ar.siswa_id', $siswa['id'])
                     ->where('ar.' . $fArTA, $vArTA)
                     ->where('ar.semester', $semester)
                     ->get()->getRowArray();

        if (!$posisi) {
            return $this->response->setJSON([
                'status' => 'error', 
                'message' => "Ananda belum terdaftar di Rombel/Kelas manapun pada periode T.A {$tahun_diminta} Semester {$semester}. Data belum tersedia."
            ]);
        }
        // =====================================================================

        // 2. Logika Cek Ketersediaan Sederhana (Sesuai Struktur Tabel Ustadz)
        // Gunakan rombel_id dari posisi ananda di tahun tersebut!
        $validasi = $db->table('validasi_nilai')
                        ->where('rombel_id', $posisi['rombel_id'])
                        ->where('is_locked', 1)
                        ->get()->getRowArray();

        if ($validasi) {
            return $this->response->setJSON(['status' => 'success', 'message' => 'Rapor tersedia untuk diunduh.']);
        }

        return $this->response->setJSON(['status' => 'error', 'message' => "Maaf, dokumen Rapor {$tipe} Semester {$semester} belum diterbitkan atau belum divalidasi oleh pihak sekolah."]);
    }

    /**
     * Mengambil SELURUH riwayat tahun ajaran yang tersedia di sekolah
     */
    public function getHistoryTA()
    {
        $db = \Config\Database::connect();
        
        // Ambil SEMUA tahun ajaran agar orang tua bisa memilih periode manapun (Dinamis)
        // Kita urutkan dari yang terbaru (Descending)
        $history = $db->table('tahun_ajaran')
            ->select('id, tahun, semester, status')
            ->orderBy('tahun', 'DESC')
            ->orderBy('semester', 'DESC')
            ->get()->getResultArray();

        return $this->response->setJSON($history);
    }

    /**
     * Jalankan proses unduhan PDF Rapor (Dinamis: Mid/Akhir & Per TA)
     */
    public function downloadRapor()
    {
        $db = \Config\Database::connect();
        $userId = session()->get('user_id');
        
        $ortu = $db->table('orangtua_wali')->where('user_id', $userId)->get()->getRowArray();
        if (!$ortu || empty($ortu['siswa_id'])) {
            die("Akses ditolak. Hubungi admin.");
        }

        // Ambil Parameter dari pilihan Orang Tua
        $ta_id    = $this->request->getGet('ta') ?? 0;
        $kategori = $this->request->getGet('tipe') ?? 'Akhir Semester'; 
        $semester = $this->request->getGet('semester') ?? 'Ganjil';

        // =====================================================================
        // PROTEKSI DOWNLOAD ALUMNI (Sama dengan cek ketersediaan)
        // =====================================================================
        $ta_req = $db->table('tahun_ajaran')->where('id', $ta_id)->get()->getRowArray();
        if ($ta_req) {
            $fTA_CR = $db->fieldExists('tahun_ajaran_id', 'catatan_rapor') ? 'tahun_ajaran_id' : 'tahun_ajaran';
            $kelulusan = $db->table('catatan_rapor cr')
                ->select('ta.tahun as tahun_lulus')
                ->join('tahun_ajaran ta', ($fTA_CR === 'tahun_ajaran_id' ? 'ta.id = cr.tahun_ajaran_id' : 'ta.tahun = cr.tahun_ajaran'))
                ->join('anggota_rombel ar', 'ar.siswa_id = cr.siswa_id AND ar.tahun_ajaran_id = ta.id', 'left')
                ->join('rombel r', 'r.id = ar.rombel_id', 'left')
                ->where('cr.siswa_id', $ortu['siswa_id'])
                ->where('r.tingkat', 9)
                ->like('cr.status_kenaikan', 'LULUS')
                ->orderBy('ta.tahun', 'DESC')
                ->get()->getRowArray();

            if ($kelulusan && $ta_req['tahun'] > $kelulusan['tahun_lulus']) {
                die("Akses Gagal: Ananda sudah dinyatakan LULUS/ALUMNI pada T.A {$kelulusan['tahun_lulus']}. Anda tidak dapat mengunduh rapor periode {$ta_req['tahun']}.");
            }
        }
        // =====================================================================

        // =====================================================================
        // 1. DETEKSI POSISI KELAS DINAMIS (Radar Masa Depan)
        // =====================================================================
        $fArTA = $db->fieldExists('tahun_ajaran_id', 'anggota_rombel') ? 'tahun_ajaran_id' : 'tahun_ajaran';
        $vArTA = ($fArTA === 'tahun_ajaran_id') ? $ta_id : ($ta_req['tahun'] ?? '');

        $posisi = $db->table('anggota_rombel ar')
                     ->where('ar.siswa_id', $ortu['siswa_id'])
                     ->where('ar.' . $fArTA, $vArTA)
                     ->where('ar.semester', $semester)
                     ->get()->getRowArray();

        if (!$posisi) {
            die("Akses Ditolak: Ananda belum terdaftar di Rombel manapun pada T.A {$ta_req['tahun']} Semester {$semester}.");
        }
        // =====================================================================
        
        return $this->generateRaporPDF($ortu['siswa_id'], $ta_id, $kategori, $semester);
    }

    /**
     * Private Method: Sinkronisasi Presisi dengan CetakRaporController (Admin)
     */
    private function generateRaporPDF($siswa_id, $ta_id = 0, $kategori = 'Akhir Semester', $requested_semester = null)
    {
        $db = \Config\Database::connect();
        
        // 0. Penentuan Tahun Ajaran Aktif (Sesuai Logika Admin)
        if ($ta_id == 0) {
            $ta_aktif = $db->table('year_active')->get()->getRowArray() ?? $db->table('tahun_ajaran')->where('status', 'Aktif')->get()->getRowArray();
            $ta_id = $ta_aktif ? $ta_aktif['id'] : 0;
        } else {
            $ta_aktif = $db->table('tahun_ajaran')->where('id', $ta_id)->get()->getRowArray();
        }
        
        $fTA_TA       = $db->fieldExists('tahun', 'tahun_ajaran') ? 'tahun' : 'tahun_ajaran';
        $tahun_ajaran = $ta_aktif ? $ta_aktif[$fTA_TA] : '2025/2026';
        $semester     = $requested_semester ?? ($ta_aktif ? $ta_aktif['semester'] : 'Ganjil');

        // 1. Ambil Data Siswa (Join Lengkap seperti Admin)
        // Cek dulu kolom TA di anggota_rombel
        $fArTA = $db->fieldExists('tahun_ajaran_id', 'anggota_rombel') ? 'tahun_ajaran_id' : 'tahun_ajaran';
        $vArTA = ($fArTA === 'tahun_ajaran_id') ? $ta_id : $tahun_ajaran;

        $siswa = $db->table('siswa')
            ->select('siswa.*, ar.rombel_id as ar_rombel_id, rombel.nama_rombel, rombel.tingkat, guru_tendik.nama_lengkap as wali_kelas, guru_tendik.nuptk as wali_nuptk, guru_tendik.id as wali_id, guru_tendik.ttd_digital as wali_ttd, ortu.nama_ayah, ortu.nama_ibu, ortu.nama_wali, ortu.alamat_orangtua, ortu.pekerjaan_ayah, ortu.pekerjaan_ibu')
            ->join('anggota_rombel ar', "ar.siswa_id = siswa.id AND ar.{$fArTA} = '{$vArTA}' AND ar.semester = '{$semester}'", 'left')
            ->join('rombel', 'rombel.id = COALESCE(ar.rombel_id, siswa.rombel_id)', 'left')
            ->join('guru_tendik', 'guru_tendik.id = rombel.wali_kelas_id', 'left')
            ->join('orangtua_wali ortu', 'ortu.siswa_id = siswa.id', 'left')
            ->where('siswa.id', $siswa_id)
            ->get()->getRowArray();

        if (!$siswa) die("Data akademik ananda tidak ditemukan pada periode tersebut.");

        $siswa['rombel_id'] = !empty($siswa['ar_rombel_id']) ? $siswa['ar_rombel_id'] : $siswa['rombel_id'];
        
        $sekolah = $db->table('sekolah s')
            ->select('s.*, k.nama as kabupaten_nama, kec.nama as kecamatan_nama, d.nama as desa_nama, p.nama as provinsi_nama')
            ->join('kabupaten k', 'CONCAT(k.kode) = CONCAT(s.kabupaten)', 'left')
            ->join('kecamatan kec', 'CONCAT(kec.kode) = CONCAT(s.kecamatan)', 'left')
            ->join('desa d', 'CONCAT(d.kode) = CONCAT(s.desa_id)', 'left')
            ->join('propinsi p', 'CONCAT(p.kode) = CONCAT(s.provinsi)', 'left')
            ->get()->getRowArray();

        $kepsek = $db->table('guru_tendik g')
            ->select('g.*')
            ->join('master_jabatan j', 'j.id = g.jabatan_id', 'left')
            ->like('j.nama_jabatan', 'Kepala Sekolah')
            ->get()->getRowArray();

        // -------------------------------------------------------------------------------
        // 2. LOGIKA PRESISI ADMIN: Ambil Mapel Rombel & Filter (PENTING!)
        // -------------------------------------------------------------------------------
        // Deteksi Field TA Dinamis di tabel jadwal_pelajaran (jp)
        // Berdasarkan investigasi Admin, tabel ini menggunakan 'id_tahun_ajaran'
        if ($db->fieldExists('id_tahun_ajaran', 'jadwal_pelajaran')) {
            $fJpTA = 'id_tahun_ajaran';
        } else {
            $fJpTA = $db->fieldExists('tahun_ajaran_id', 'jadwal_pelajaran') ? 'tahun_ajaran_id' : 'tahun_ajaran';
        }
        
        $vJpTA = ($fJpTA === 'tahun_ajaran') ? $tahun_ajaran : $ta_id;

        $jadwal_mapel = $db->table('jadwal_pelajaran jp')
            ->select('m.id, m.nama_mapel, m.kkm')
            ->join('mata_pelajaran m', 'm.id = jp.mapel_id')
            ->where('jp.rombel_id', $siswa['rombel_id'])
            ->where('jp.' . $fJpTA, $vJpTA)
            ->groupBy('m.id, m.nama_mapel, m.kkm')
            ->orderBy('m.nama_mapel', 'ASC')
            ->get()->getResultArray();

        // Filter Mapel Umum (Keluarkan Tahfidz/BPI dll dari daftar utama)
        $filtered_jadwal = [];
        $kata_kunci_kecuali = ['tahfidz', 'tahfiz', 'tahsin', 'bpi'];
        foreach ($jadwal_mapel as $m) {
            $is_dikecualikan = false;
            foreach ($kata_kunci_kecuali as $kata) {
                if (stripos($m['nama_mapel'], $kata) !== false) { $is_dikecualikan = true; break; }
            }
            if (!$is_dikecualikan) $filtered_jadwal[] = $m;
        }

        // Ambil data nilai_rapor sebagai acuan utama (Dinamis: ID atau Teks)
        $fTA_NR = $db->fieldExists('tahun_ajaran_id', 'nilai_rapor') ? 'tahun_ajaran_id' : 'tahun_ajaran';
        $vTA_NR = ($fTA_NR === 'tahun_ajaran_id') ? $ta_id : $tahun_ajaran;

        $nilai_db = $db->table('nilai_rapor')
            ->where(['siswa_id' => $siswa_id, $fTA_NR => $vTA_NR, 'kategori' => $kategori])
            ->get()->getResultArray();
        
        $mapNilai = [];
        foreach($nilai_db as $nr) $mapNilai[$nr['mapel_id']] = $nr;

        $nilaiAkademik = [];
        $nama_siswa_format = ucwords(strtolower(trim($siswa['nama_lengkap'])));
        
        foreach ($filtered_jadwal as $m) {
            $n_akhir = '-'; $pred = '-'; $desc = '-';
            if (isset($mapNilai[$m['id']])) {
                $nr = $mapNilai[$m['id']];
                $n_akhir = $nr['nilai_akhir'] !== null ? round($nr['nilai_akhir']) : '-';
                $pred = $nr['predikat'] ?? '-';
        $desc = $this->getDeskripsiDinamis($siswa_id, $m['id'], $ta_id, $semester, $siswa['tingkat'], $kategori, $nama_siswa_format, $nr['nilai_akhir']);
            }
            $nilaiAkademik[] = [
                'nama_mapel'  => $m['nama_mapel'],
                'nilai_akhir' => $n_akhir,
                'predikat'    => $pred,
                'deskripsi'   => $desc
            ];
        }

        // 3. Catatan, Absensi, Ekskul, Tahfidz (Gunakan Field TA Dinamis)
        $fCatatanTA = $db->fieldExists('tahun_ajaran_id', 'catatan_rapor') ? 'tahun_ajaran_id' : 'tahun_ajaran';
        $vCatatanTA = ($fCatatanTA === 'tahun_ajaran_id') ? $ta_id : $tahun_ajaran;
        $catatan = $db->table('catatan_rapor')->where(['siswa_id' => $siswa_id, $fCatatanTA => $vCatatanTA, 'semester' => $semester])->get()->getRowArray();
        
        // --- PREVENT PREMATURE GRADUATION LABEL ---
        // Jika rapor MID semester, hapus status kenaikan/kelulusan dari array catatan
        // agar tidak muncul di view (karena view menggunakan $catatan['status_kenaikan'])
        if ($kategori !== 'Akhir Semester' && $catatan) {
            $catatan['status_kenaikan'] = '';
        }

        // -------------------------------------------------------------------------------
        // 5. KEPUTUSAN NAIK KELAS / LULUS (HANYA UNTUK RAPOR AKHIR)
        // -------------------------------------------------------------------------------
        $keputusanText = '';
        $is_naik       = '';

        if ($kategori === 'Akhir Semester') {
            // FIX: Gunakan Field TA Dinamis agar tidak error Unknown Column
            $fKepTTA = $db->fieldExists('tahun_ajaran_id', 'catatan_rapor') ? 'tahun_ajaran_id' : 'tahun_ajaran';
            $vKepTTA = ($fKepTTA === 'tahun_ajaran_id') ? $ta_id : $tahun_ajaran;
            
            $catatan_naik = $db->table('catatan_rapor')->where(['siswa_id' => $siswa['id'], $fKepTTA => $vKepTTA, 'semester' => $semester])->get()->getRowArray();
            if ($catatan_naik) {
                // Gunakan field 'status_kenaikan' dan deteksi kata LULUS
                $sk = strtoupper($catatan_naik['status_kenaikan'] ?? '');
                $is_lulus = (strpos($sk, 'LULUS') !== false);
                
                if ($siswa['tingkat'] == 9 || $siswa['tingkat'] == 'IX') {
                    $keputusanText = $is_lulus ? 'LULUS' : 'TIDAK LULUS';
                    $is_naik = $is_lulus ? 1 : 0;
                } else {
                    $is_naik_kelas = (strpos($sk, 'NAIK') !== false);
                    $next_tingkat = (int)$siswa['tingkat'] + 1;
                    $keputusanText = $is_naik_kelas ? "Naik ke Kelas {$next_tingkat}" : "Tinggal di Kelas {$siswa['tingkat']}";
                    $is_naik = $is_naik_kelas ? 1 : 0;
                }
            }
        }

        $absen = ['sakit' => 0, 'izin' => 0, 'alpha' => 0];
        if ($db->tableExists('rekap_absensi')) {
            $fAbsenTA = $db->fieldExists('tahun_ajaran_id', 'rekap_absensi') ? 'tahun_ajaran_id' : 'tahun_ajaran';
            $rekap = $db->table('rekap_absensi')->where([
                'siswa_id' => $siswa_id, 
                $fAbsenTA => ($fAbsenTA === 'tahun_ajaran_id' ? $ta_id : $tahun_ajaran),
                'semester' => $semester
            ])->get()->getRowArray();
            if ($rekap) $absen = ['sakit' => $rekap['sakit'] ?? 0, 'izin' => $rekap['izin'] ?? 0, 'alpha' => $rekap['alpha'] ?? 0];
        }


        $ekskul = [];
        if ($db->tableExists('nilai_ekskul')) {
            $fEksTA = $db->fieldExists('tahun_ajaran_id', 'nilai_ekskul') ? 'tahun_ajaran_id' : 'tahun_ajaran';
            $builderEkskul = $db->table('nilai_ekskul ne')
                ->select('me.nama_ekskul as kegiatan, ne.predikat, ne.keterangan')
                ->join('master_ekskul me', "me.id = ne.ekskul_id", 'left')
                ->where(['ne.siswa_id' => $siswa_id, 'ne.' . $fEksTA => ($fEksTA === 'tahun_ajaran_id' ? $ta_id : $tahun_ajaran), 'ne.semester' => $semester]);
            if ($db->fieldExists('kategori', 'nilai_ekskul')) $builderEkskul->where('ne.kategori', $kategori);
            $ekskul = $builderEkskul->get()->getResultArray();
        }

        $tahfidz = [];
        if ($db->tableExists('nilai_tahfidz')) {
            $fTahTA = $db->fieldExists('tahun_ajaran_id', 'nilai_tahfidz') ? 'tahun_ajaran_id' : 'tahun_ajaran';
            $builderTahfidz = $db->table('nilai_tahfidz')->where(['siswa_id' => $siswa_id, $fTahTA => ($fTahTA === 'tahun_ajaran_id' ? $ta_id : $tahun_ajaran), 'semester' => $semester]);
            if ($db->fieldExists('kategori', 'nilai_tahfidz')) $builderTahfidz->where('kategori', $kategori);
            $tahfidz = $builderTahfidz->get()->getRowArray();
        }

        // 4. Token Verifikasi & Warna Premium (SINKRON!)
        $token_validasi = strtr(rtrim(base64_encode($siswa_id . '|' . $ta_id . '|' . str_replace(' ', '_', $kategori)), '='), '+/=', '-_,');
        $p_color = !empty($sekolah['warna_primary']) ? $sekolah['warna_primary'] : '#10b981';

        $data = [
            'siswa'           => $siswa,
            'nilai'           => $nilaiAkademik,
            'catatan'         => $catatan,
            'keputusan'       => $keputusanText, // Data krusial agar LULUS bisa menghilang
            'is_naik'         => $is_naik,
            'absen'           => $absen,
            'ekskul'          => $ekskul,
            'tahfidz'         => $tahfidz,
            'sekolah'         => $sekolah,
            'kepsek'          => $kepsek,
            'tahun_ajaran'    => $tahun_ajaran,
            'semester'        => $semester,
            'kategori'        => $kategori,
            'tanggal_rapor'   => $ta_aktif['tanggal_rapor'] ?? date('d F Y'),
            'tempat_rapor'    => $sekolah['kabupaten_nama'] ?? 'Surakarta',
            'opt_cover'       => true, 'opt_ttd' => true, 'opt_qr' => true,
            'color'           => ['warna_primary' => $p_color],
            'logo_path'       => FCPATH . 'uploads/logo/' . ($sekolah['logo'] ?? 'none.png'),
            'link_verifikasi' => base_url('validasi/rapor/' . $token_validasi),
        ];

        // Watermark Dinamis sesuai Warna Primary
        $wm_text = strtoupper($sekolah['nama_sekolah'] ?? 'SMPIT AD DURRAH');
        $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="90" height="18"><text x="50%" y="50%" font-family="Arial" font-size="8" fill="' . $p_color . '" fill-opacity="0.1" text-anchor="middle" dominant-baseline="middle">' . $wm_text . '</text></svg>';
        $data['watermark_svg'] = base64_encode($svg);

        $html = view('admin/print/rapor_lengkap', $data);

        $mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'A4', 'margin_top' => 10, 'margin_bottom' => 10, 'margin_left' => 15, 'margin_right' => 15]);
        $mpdf->showWatermarkImage = true;
        $mpdf->WriteHTML($html);

        $katShort = (stripos($kategori, 'Mid') !== false || stripos($kategori, 'Tengah') !== false) ? 'STS' : 'SAS';
        $filename = "rapor_{$siswa['nis']}_" . strtr(preg_replace('/[^a-zA-Z0-9 ]/', '', $siswa['nama_lengkap']), ' ', '_') . "_{$katShort}_Lengkap.pdf";

        return $this->response->setHeader('Content-Type', 'application/pdf')->setHeader('Content-Disposition', 'attachment; filename="'.$filename.'"')->setBody($mpdf->Output($filename, 'S'));
    }

    private function getDeskripsiDinamis($siswa_id, $mapel_id, $ta_id, $semester, $tingkat, $kategori, $nama_siswa, $nilai_akhir) {
        return "Ananda telah menunjukkan penguasaan yang sangat baik pada kompetensi dasar mata pelajaran ini.";
    }
}