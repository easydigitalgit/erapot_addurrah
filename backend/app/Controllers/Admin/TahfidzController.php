<?php

namespace App\Controllers\Admin;

use App\Controllers\AdminBaseController;
use App\Models\Admin\SiswaModel;
use App\Models\Admin\RombelModel;
use App\Models\Admin\TahunAjaranModel;

class TahfidzController extends AdminBaseController
{
    protected $db;
    protected $siswaModel;
    protected $rombelModel;
    protected $tahunAjaranModel;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
        $this->siswaModel = new SiswaModel();
        $this->rombelModel = new RombelModel();
        $this->tahunAjaranModel = new TahunAjaranModel();
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

    private function getWatermarkBase64($text)
    {
        $text = strtoupper($text ?: 'SCHOOL REPORT');
        $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="120" height="18"><text x="50%" y="13" font-family="Arial" font-size="9" font-weight="bold" fill="#DAA520" fill-opacity="0.16" text-anchor="middle">'.$text.'</text></svg>';
        return base64_encode($svg);
    }

    public function index()
    {
        $this->data['title'] = 'Cetak Nilai Tahfizh';
        $this->data['color'] = $this->getColor();
        // Ambil Tahun Ajaran untuk filter utama
        $this->data['list_tahun_ajaran'] = $this->tahunAjaranModel->orderBy('tahun', 'DESC')->findAll();
        // Inisialisasi awal list rombel (bisa dikosongkan atau ambil yang aktif saja)
        $ta_aktif = $this->db->table('tahun_ajaran')->where('status', 'Aktif')->get()->getRowArray();
        $id_ta_aktif = $ta_aktif ? $ta_aktif['id'] : 0;
        $this->data['id_ta_aktif'] = $id_ta_aktif;
        $this->data['list_rombel'] = $this->rombelModel->where('id_tahun_ajaran', $id_ta_aktif)->orderBy('tingkat', 'ASC')->findAll();
        
        return view('admin/tahfidz/index', $this->data);
    }

    public function getData()
    {
        try {
            $rombel_id = request()->getGet('rombel');
            $juz_id    = request()->getGet('juz') ?? 30;
            $id_ta_req = request()->getGet('id_ta');
            
            // Ambil Tahun Ajaran (Prioritas dari request, fallback ke Aktif)
            if ($id_ta_req) {
                $ta_aktif = $this->db->table('tahun_ajaran')->where('id', $id_ta_req)->get()->getRowArray();
            } else {
                $ta_aktif = $this->db->table('tahun_ajaran')->where('status', 'Aktif')->get()->getRowArray();
            }
            
            $id_ta = $ta_aktif ? $ta_aktif['id'] : 0;
            $semester     = $ta_aktif ? $ta_aktif['semester'] : '';
            $tgl_mulai = date('Y-m-d');
            $tgl_akhir = date('Y-m-d');

            if ($ta_aktif && !empty($ta_aktif['tahun'])) {
                // --- LOGIKA SEMESTER DARI GURU TAHFIZH ---
                $tahun_str = explode('/', $ta_aktif['tahun'])[0]; 
                if ($semester === 'Ganjil') {
                    $tgl_mulai = $tahun_str . '-07-01'; 
                    $tgl_akhir = $tahun_str . '-12-31'; 
                } else {
                    $tahun_genap = (int)$tahun_str + 1; 
                    $tgl_mulai = $tahun_genap . '-01-01'; 
                    $tgl_akhir = $tahun_genap . '-06-30'; 
                }
            }

            // Ambil Mapping Surah untuk Juz ini
            $juz_info = $this->db->table('ref_juz')->where('id', $juz_id)->get()->getRowArray();
            $juz_nama_db = $juz_info ? trim($juz_info['nama_juz']) : "Juz $juz_id";
            $standardMap = $this->getStandardJuzMap();
            $surahList   = [];
            $surahDb = $this->db->table('ref_surah')->get()->getResultArray();
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
                $surahQuery = $this->db->table('ref_surah')->where("id >=", $juz_info['mulai_surah_id'])->where("id <=", $juz_info['sampai_surah_id'])->orderBy('id', 'ASC')->get()->getResultArray();
                foreach($surahQuery as $s) { $surahList[] = ['surah_id' => $s['id'], 'nama_surah' => $s['nama_surah'], 'ayat' => 'Semua', 'display' => $s['nama_surah']]; }
            }

            $builder = $this->siswaModel->select('siswa.id, siswa.nama_lengkap, siswa.nis, rombel.nama_rombel as kelas');
            $builder->join('rombel', 'rombel.id = siswa.rombel_id', 'left');
            $builder->where('siswa.status_siswa', 'Aktif');
            if ($rombel_id) $builder->where('siswa.rombel_id', $rombel_id);
            $siswa = $builder->findAll();

            foreach ($siswa as &$s) {
                $nilai_data = $this->db->table('nilai_tahfidz')->where(['siswa_id' => $s['id'], 'tahun_ajaran_id' => $id_ta, 'semester' => $semester])->get()->getRowArray();
                $s['nilai_teori']     = $nilai_data['nilai_teori'] ?? 0;
                $s['nilai_setoran']   = $nilai_data['nilai_setoran'] ?? 0;
                $s['nilai_rata_rata'] = $nilai_data['nilai_rata_rata'] ?? 0;
                $s['taqdir']          = $nilai_data['taqdir'] ?? '-';

                // Nilai Individual per Surah/Segmen
                $setoran = $this->db->table('setoran_tahfidz')
                    ->where(['siswa_id' => $s['id'], 'juz_id' => $juz_id])
                    ->where('tanggal >=', $tgl_mulai)->where('tanggal <=', $tgl_akhir)
                    ->get()->getResultArray();
                $s['setoranMap'] = [];
                foreach($setoran as $st) {
                    $db_ayat = (!empty($st['ayat']) && $st['ayat'] !== '-') ? trim($st['ayat']) : 'Semua';
                    $s['setoranMap'][$st['surah_id'] . '|' . $db_ayat] = $st['nilai'];
                }
            }

            return response()->setJSON(['status' => 'success', 'data' => $siswa, 'surahList' => $surahList]);
        } catch (\Exception $e) {
            return response()->setStatusCode(500)->setJSON(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    public function getRombelByTA($id_ta)
    {
        try {
            $rombels = $this->rombelModel->where('id_tahun_ajaran', $id_ta)
                                         ->orderBy('tingkat', 'ASC')
                                         ->findAll();
            return response()->setJSON(['status' => 'success', 'data' => $rombels]);
        } catch (\Exception $e) {
            return response()->setStatusCode(500)->setJSON(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    private function calculateTaqdir($score)
    {
        $m = $this->getGradeMetrics($score);
        return $m['derajat'];
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

    public function cetakRapor($siswa_id,$ta_id=0)
    {
        // AMBIL PARAMETER TA DARI URL (UNTUK MESIN WAKTU)
        $id_ta_get = request()->getGet('ta')?? $ta_id;

        if ($id_ta_get) {
            $ta_aktif = $this->db->table('tahun_ajaran')->where('id', $id_ta_get)->get()->getRowArray();
        } else {
            $ta_aktif = $this->db->table('tahun_ajaran')->where('status', 'Aktif')->get()->getRowArray();
        }

        $id_ta = $ta_aktif ? $ta_aktif['id'] : 0;
        $tahun_ajaran = $ta_aktif ? $ta_aktif['tahun'] : '';
        $semester     = $ta_aktif ? $ta_aktif['semester'] : '';

        // SEARCH SISWA DENGAN LOGIKA HISTORIS (CARI KELAS DI TAHUN TERSEBUT)
        $siswa = $this->db->table('siswa')
            ->select('siswa.*, ar.rombel_id as hist_rombel_id, rombel.nama_rombel, rombel.tingkat, guru_tendik.nama_lengkap as wali_kelas')
            ->join('anggota_rombel ar', "ar.siswa_id = siswa.id AND ar.tahun_ajaran_id = {$id_ta} AND ar.semester = '{$semester}'", 'left')
            ->join('rombel', 'rombel.id = ar.rombel_id', 'left') // Join ke rombel historis
            ->join('guru_tendik', 'guru_tendik.id = rombel.wali_kelas_id', 'left')
            ->where('siswa.id', $siswa_id)
            ->get()->getRowArray();

        // Fallback jika tidak ditemukan di anggota_rombel (mungkin data baru/belum diproses)
        if (!$siswa || empty($siswa['nama_rombel'])) {
            $siswaCurrent = $this->siswaModel
                ->select('siswa.*, rombel.nama_rombel, rombel.tingkat, guru_tendik.nama_lengkap as wali_kelas')
                ->join('rombel', 'rombel.id = siswa.rombel_id', 'left')
                ->join('guru_tendik', 'guru_tendik.id = rombel.wali_kelas_id', 'left')
                ->find($siswa_id);
            
            if ($siswaCurrent) {
                $siswa = array_merge($siswa ?: [], $siswaCurrent);
            }
        }

        if (!$siswa) return "Siswa tidak ditemukan.";

        $sekolah = $this->db->table('sekolah')->get()->getRowArray();
        
        $kepsek = $this->db->table('guru_tendik g')
            ->select('g.*')
            ->join('master_jabatan j', 'j.id = g.jabatan_id', 'left')
            ->like('j.nama_jabatan', 'Kepala Sekolah')
            ->get()->getRowArray();

        $nilai = $this->db->table('nilai_tahfidz')
            ->where(['siswa_id' => $siswa_id, 'tahun_ajaran_id' => $id_ta, 'semester' => $semester])
            ->get()->getRowArray();

        // --- DINAMISASI GURU TAHFIDZ (AMBIL DARI MAPPING GURU MAPEL) ---
        $guruTahfidz = $this->db->table('guru_mapel gm')
            ->select('gt.nama_lengkap, gt.nik, gt.nuptk')
            ->join('guru_tendik gt', 'gt.id = gm.guru_id')
            ->join('mata_pelajaran mp', 'mp.id = gm.mapel_id')
            ->where('gm.rombel_id', $siswa['rombel_id'])
            ->where('gm.tahun_ajaran_id', $id_ta)
            ->groupStart()
                ->like('mp.nama_mapel', 'Tahfizh')
                ->orLike('mp.nama_mapel', 'Tahfidz')
            ->groupEnd()
            ->get()->getRowArray();

        // --- LOGIKA SEMESTER DARI GURU TAHFIZH ---
        $tahun_str = explode('/', $ta_aktif['tahun'])[0]; 
        if ($semester === 'Ganjil') {
            $tgl_mulai = $tahun_str . '-07-01'; 
            $tgl_akhir = $tahun_str . '-12-31'; 
        } else {
            $tahun_genap = (int)$tahun_str + 1; 
            $tgl_mulai = $tahun_genap . '-01-01'; 
            $tgl_akhir = $tahun_genap . '-06-30'; 
        }

        $juz_id = request()->getGet('juz') ?? 30;
        
        // Ambil info Juz (terutama mulai_surah_id & sampai_surah_id untuk fallback)
        $juz_info = $this->db->table('ref_juz')->where('id', $juz_id)->get()->getRowArray();
        $juz_nama_db = $juz_info ? trim($juz_info['nama_juz']) : "Juz $juz_id";

        // Logic Dinamis Berdasarkan Relasi Database Segmen 'Setoran'
        $standardMap = $this->getStandardJuzMap();
        $surahList   = [];
        
        $surahDb = $this->db->table('ref_surah')->get()->getResultArray();
        $surahIdMap = [];
        foreach($surahDb as $s) {
            $alpha = preg_replace('/[^a-z0-9]/', '', strtolower(trim($s['nama_surah'])));
            if ($alpha === 'allahab') $alpha = 'almasad';
            $surahIdMap[$alpha] = $s['id'];
        }

        $blocks = isset($standardMap[$juz_nama_db]) ? $standardMap[$juz_nama_db] : [];
        if (!empty($blocks)) {
            foreach ($blocks as $block) {
                $ayat   = 'Semua';
                if (preg_match('/^(.*?)\s+([0-9-]+)$/', $block, $m)) {
                    $s_name = trim($m[1]);
                    $ayat   = trim($m[2]);
                } else {
                    $s_name = trim($block);
                }
                
                $alpha_search = preg_replace('/[^a-z0-9]/', '', strtolower($s_name));
                if ($alpha_search === 'allahab') $alpha_search = 'almasad';
                $s_id = $surahIdMap[$alpha_search] ?? null;

                $surahList[] = [
                    'surah_id'   => $s_id,
                    'nama_surah' => $s_name,
                    'ayat'       => $ayat,
                    'display'    => $block 
                ];
            }
        } else if ($juz_info && $juz_info['mulai_surah_id'] && $juz_info['sampai_surah_id']) {
            // Fallback untuk Juz 11-29 yang tidak mendefinisikan segmentasi khusus
            $surahQuery = $this->db->table('ref_surah')
                ->where("id >=", $juz_info['mulai_surah_id'])
                ->where("id <=", $juz_info['sampai_surah_id'])
                ->orderBy('id', 'ASC')
                ->get()->getResultArray();
            foreach($surahQuery as $s) {
                $surahList[] = [
                    'surah_id'   => $s['id'],
                    'nama_surah' => $s['nama_surah'],
                    'ayat'       => 'Semua',
                    'display'    => $s['nama_surah']
                ];
            }
        }

        $setoran = $this->db->table('setoran_tahfidz')
            ->where('siswa_id', $siswa_id)
            ->where('juz_id', $juz_id)
            ->where('tanggal >=', $tgl_mulai)->where('tanggal <=', $tgl_akhir)
            ->get()->getResultArray();
        
        $setoranMap = [];
        foreach($setoran as $st) {
            $db_ayat = (!empty($st['ayat']) && $st['ayat'] !== '-') ? trim($st['ayat']) : 'Semua';
            $key = $st['surah_id'] . '|' . $db_ayat;
            $setoranMap[$key] = $st['nilai'];
        }

        // --- PROSES ALAMAT LENGKAP & CERDAS ROMAWI ---
        $nama_desa = '';
        if (!empty($sekolah['desa_id'])) {
            $desa = $this->db->table('desa')->where('id', $sekolah['desa_id'])->orWhere('kode', $sekolah['desa_id'])->get()->getRowArray();
            if ($desa) $nama_desa = $desa['nama'];
        }
        $nama_kecamatan = '';
        if (!empty($sekolah['kecamatan'])) {
            $kec = $this->db->table('kecamatan')->where('id', $sekolah['kecamatan'])->orWhere('kode', $sekolah['kecamatan'])->get()->getRowArray();
            if ($kec) $nama_kecamatan = $kec['nama'];
        }
        $nama_kabupaten = '';
        if (!empty($sekolah['kabupaten'])) {
            $kab = $this->db->table('kabupaten')->where('id', $sekolah['kabupaten'])->orWhere('kode', $sekolah['kabupaten'])->get()->getRowArray();
            if ($kab) $nama_kabupaten = $kab['nama'];
        }
        $nama_provinsi = '';
        if (!empty($sekolah['provinsi'])) {
            $tbl_prov = $this->db->tableExists('propinsi') ? 'propinsi' : 'provinsi';
            $prov = $this->db->table($tbl_prov)->where('id', $sekolah['provinsi'])->orWhere('kode', $sekolah['provinsi'])->get()->getRowArray();
            if ($prov) $nama_provinsi = $prov['nama'];
        }

        $alamat_full = $this->titleCaseWithRoman($sekolah['alamat'] ?? '-');
        if (!empty($nama_desa)) $alamat_full .= ', Kel/Desa ' . $this->titleCaseWithRoman($nama_desa);
        if (!empty($nama_kecamatan)) $alamat_full .= ', Kec. ' . $this->titleCaseWithRoman($nama_kecamatan);
        if (!empty($nama_kabupaten)) $alamat_full .= ', ' . $this->titleCaseWithRoman($nama_kabupaten);
        if (!empty($nama_provinsi)) $alamat_full .= ', ' . $this->titleCaseWithRoman($nama_provinsi);
        if (!empty($sekolah['kode_pos'])) $alamat_full .= ' ' . esc($sekolah['kode_pos']);

        // --- LOKASI TTD ---
        $lokasi_ttd = $this->titleCaseWithRoman($nama_kabupaten ?: 'Medan');

        $data = [
            'siswa'            => $siswa,
            'sekolah'          => $sekolah,
            'alamat_sekolah'   => $alamat_full,
            'lokasi_ttd'       => $lokasi_ttd,
            'kepsek'           => $kepsek,
            'nilai'            => $nilai,
            'metrics_teori'    => $this->getGradeMetrics($nilai['nilai_teori'] ?? 0),
            'metrics_setoran'  => $this->getGradeMetrics($nilai['nilai_setoran'] ?? 0),
            'surahList'        => $surahList,
            'juz_info'         => $juz_info,
            'setoranMap'       => $setoranMap,
            'tahun_ajaran'     => $tahun_ajaran,
            'semester'         => $semester,
            'guru_tahfidz'     => $guruTahfidz ? $guruTahfidz['nama_lengkap'] : '-', 
            'tanggal_rapor'    => date('d F Y'),
            'watermark_base64' => $this->getWatermarkBase64($sekolah['nama_sekolah'] ?? ''),
            'link_verifikasi'  => base_url('validasi/rapor/' . strtr(rtrim(base64_encode($siswa_id . '|' . $id_ta . '|' . 'Tahfidz'), '='), '+/=', '-_,'))
        ];

        // --- SISTEM WATERMARK GANDA (LOGIKA SAMA DENGAN CETAK RAPOR UTAMA) ---
        $p_color  = !empty($sekolah['warna_primary']) ? $sekolah['warna_primary'] : '#10b981';
        $wm_text  = strtoupper($sekolah['nama_sekolah'] ?? 'SMPIT AD DURRAH');
        $wm_color = $this->blendWithWhite($p_color, 0.23); 

        $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="90" height="18">
                  <text x="50%" y="50%" font-family="Arial" font-size="8" fill="' . $wm_color . '" text-anchor="middle" dominant-baseline="middle">' . $wm_text . '</text>
                </svg>';
        $data['watermark_svg'] = base64_encode($svg);
        $data['logo_path']     = FCPATH . 'uploads/logo/' . ($sekolah['logo'] ?? 'none.png');
        $data['color']         = ['warna_primary' => $p_color, 'warna_secondary' => ($sekolah['warna_secondary'] ?? '#ecfdf5')];

        $html = view('admin/print/rapor_tahfidz', $data);

        $mpdf = new \Mpdf\Mpdf([
            'mode' => 'utf-8', 
            'format' => 'A4',
            'margin_top' => 15,
            'margin_bottom' => 15,
            'margin_left' => 15,
            'margin_right' => 15
        ]);

        $mpdf->showWatermarkImage = true;
        
        $mpdf->WriteHTML($html);

        // FORMULASI NAMA FILE SESUAI PERMINTAAN PEMBIMBING PKL
        $namaSiswaFix = str_replace(' ', '_', preg_replace('/[^a-zA-Z0-9 ]/', '', $siswa['nama_lengkap'] ?? 'Student'));
        $nisSiswa     = $siswa['nis'] ?? '000';
        $juzId        = $juz_id ?? '30';
        $filename     = "Rapor_Tahfidz_Juz{$juzId}_{$nisSiswa}_{$namaSiswaFix}.pdf";

        return response()
            ->setHeader('Content-Type', 'application/pdf')
            ->setHeader('Content-Disposition', 'inline; filename="' . $filename . '"')
            ->setBody($mpdf->Output($filename, 'S'));
    }

    /**
     * Helper cerdas untuk menaruh format Title Case dengan tetap menjaga Angka Romawi tetap kapital.
     * Contoh: "jalan sumarsono ii" -> "Jalan Sumarsono II"
     */
    private function titleCaseWithRoman($text)
    {
        if (empty($text)) return "";
        
        $words = explode(' ', strtolower($text));
        $romans = ['i', 'ii', 'iii', 'iv', 'v', 'vi', 'vii', 'viii', 'ix', 'x', 'xi', 'xii'];
        
        foreach ($words as &$word) {
            if (in_array($word, $romans)) {
                $word = strtoupper($word);
            } else {
                $word = ucwords($word);
            }
        }
        
        return implode(' ', $words);
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
