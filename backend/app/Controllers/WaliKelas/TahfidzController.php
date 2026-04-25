<?php

namespace App\Controllers\WaliKelas;

use App\Controllers\WaliKelasBaseController;
use App\Models\Admin\SiswaModel;
use App\Models\Admin\RombelModel;
use App\Models\Admin\TahunAjaranModel;

class TahfidzController extends WaliKelasBaseController
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

    public function index()
    {
        $this->data['title'] = 'Manajemen Tahfidz Kelas';
        $this->data['color'] = $this->getColor();
        
        $wali_id = session()->get('id');
        $rombel = $this->db->table('rombel')->where('wali_kelas_id', $wali_id)->get()->getRowArray();
        
        $this->data['rombel'] = $rombel;
        $this->data['surahList'] = $this->db->table('ref_surah')->where('no_surah >=', 78)->orderBy('no_surah', 'ASC')->get()->getResultArray();
        
        return view('WaliKelas/tahfidz/index', $this->data);
    }

    public function getSiswaTahfidz()
    {
        try {
            $userId = session()->get('user_id');
            $guru = $this->db->table('guru_tendik')->where('user_id', $userId)->get()->getRowArray();
            $juz_id = request()->getGet('juz') ?? 30;
            
            if (!$guru) {
                return response()->setJSON(['status' => 'error', 'message' => 'Data guru tidak ditemukan.']);
            }

            // Ambil Tahun Ajaran Aktif
            $ta_aktif = $this->db->table('tahun_ajaran')->where('status', 'Aktif')->get()->getRowArray();
            $id_ta = $ta_aktif ? $ta_aktif['id'] : 0;
            $semester = $ta_aktif ? $ta_aktif['semester'] : '';

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

            // Cari Rombel (Gunakan ID TA Aktif)
            $rombel = $this->db->table('rombel')
                               ->where('wali_kelas_id', $guru['id'])
                               ->where('id_tahun_ajaran', $id_ta)
                               ->get()->getRowArray();
            
            if (!$rombel) {
                $rombel = $this->db->table('rombel')->where('wali_kelas_id', $guru['id'])->orderBy('id_tahun_ajaran', 'DESC')->get()->getRowArray();
            }

            if (!$rombel) {
                return response()->setJSON(['status' => 'error', 'message' => 'Hanya Wali Kelas yang dapat mengakses halaman ini.']);
            }

            // Ambil Mapping Surah (Logic Reuse)
            $adminTahfidz = new \App\Controllers\Admin\TahfidzController();
            $juz_info = $this->db->table('ref_juz')->where('id', $juz_id)->get()->getRowArray();
            $juz_nama_db = $juz_info ? trim($juz_info['nama_juz']) : "Juz $juz_id";
            $standardMap = [
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
                "Juz 30" => ["An-Naba'", "An-Nazi'at", "'Abasa", "At-Takwir", "Al-Infitar", "Al-Mutaffifin", "Al-Insyiqaq", "Al-Buruj", "At-Tariq", "Al-A'la", "Al-Ghasyiyah", "Al-Fajr", "Al-Balad", "Asy-Syams", "Al-Lail", "Ad-Duha", "Asy-Syarh", "At-Tin", "Al-'Alaq", "Al-Qadr", "Al-Bayyinah", "Az-Zalzalah", "Al-'Adiyat", "Al-Qari'ah", "At-Takasur", "Al-'Asr", "Al-Humazah", "Al-Fil", "Quraisy", "Al-Ma'un", "Al-Kausar", "Al-Kafirun", "An-Nasr", "Al-Masad", "Al-Ikhlas", "Al-Falaq", "An-Nas"]
            ];
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

            // 🚀 MENGGUNAKAN MESIN WAKTU (anggota_rombel)
            $siswa = $this->db->table('anggota_rombel ar')
                ->select('s.id, s.nama_lengkap, s.nis')
                ->join('siswa s', 's.id = ar.siswa_id')
                ->where('ar.rombel_id', $rombel['id'])
                ->where('ar.tahun_ajaran_id', $id_ta)
                ->where('ar.semester', $semester)
                ->where('s.status_siswa', 'Aktif')
                ->orderBy('s.nama_lengkap', 'ASC')
                ->get()->getResultArray();

            foreach ($siswa as &$s) {
                $nilai_data = $this->db->table('nilai_tahfidz')->where(['siswa_id' => $s['id'], 'tahun_ajaran_id' => $id_ta, 'semester' => $semester])->get()->getRowArray();
                $s['nilai_teori']     = $nilai_data['nilai_teori'] ?? 0;
                $s['nilai_setoran']   = $nilai_data['nilai_setoran'] ?? 0;
                $s['nilai_rata_rata'] = $nilai_data['nilai_rata_rata'] ?? 0;
                $s['taqdir']          = $nilai_data['taqdir'] ?? '-';

                // Nilai Individual
                $setoran = $this->db->table('setoran_tahfidz')->where(['siswa_id' => $s['id'], 'juz_id' => $juz_id])
                    ->where('tanggal >=', $tgl_mulai)->where('tanggal <=', $tgl_akhir)->get()->getResultArray();
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

    private function calculateTaqdir($score)
    {
        if ($score >= 90) return 'Mumtaz';
        if ($score >= 80) return 'Jayyid Jiddan';
        if ($score >= 70) return 'Jayyid';
        if ($score >= 60) return 'Maqbul';
        return 'Dhaif';
    }

    public function cetakRapor($siswa_id)
    {	
		$id_ta_get = request()->getGet('ta');
        $adminTahfidz = new \App\Controllers\Admin\TahfidzController();
        return $adminTahfidz->cetakRapor($siswa_id,$id_ta_get);
    }
}
