<?php

namespace App\Controllers\Tahfidz;

use App\Controllers\TahfidzBaseController;

class MonitoringController extends TahfidzBaseController
{
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    private function getRombelsGuru() {
        $userId = session()->get('user_id');
        $guru = $this->db->table('guru_tendik')->where('user_id', $userId)->get()->getRowArray();
        
        if ($guru) {
            $sess_ta  = session()->get('tahun_ajaran');
            $sess_smt = session()->get('semester');

            if ($sess_ta && $sess_smt) {
                $ta_aktif = $this->db->table('tahun_ajaran')->where('tahun', $sess_ta)->where('semester', $sess_smt)->get()->getRowArray();
            } else {
                $ta_aktif = $this->db->table('tahun_ajaran')->where('status', 'Aktif')->get()->getRowArray();
            }

            $id_ta = $ta_aktif ? $ta_aktif['id'] : 0;

            $rombels = $this->db->table('guru_mapel')
                               ->select('rombel.id, rombel.nama_rombel, rombel.tingkat')
                               ->join('rombel', 'rombel.id = guru_mapel.rombel_id')
                               ->where('guru_mapel.guru_id', $guru['id']) 
                               ->where('rombel.id_tahun_ajaran', $id_ta)
                               ->groupBy('rombel.id')
                               ->orderBy('rombel.tingkat', 'ASC')
                               ->orderBy('rombel.nama_rombel', 'ASC')
                               ->get()->getResultArray();
            
            return $rombels;
        }
        return []; 
    }

    // Peta Al-Quran Standar Universal (Proteksi Tingkat Tinggi)
    private function getStandardJuzMap() {
        return [
            "Juz 30" => ["An-Naba'", "An-Nazi'at", "'Abasa", "At-Takwir", "Al-Infitar", "Al-Mutaffifin", "Al-Insyiqaq", "Al-Buruj", "At-Tariq", "Al-A'la", "Al-Ghasyiyah", "Al-Fajr", "Al-Balad", "Asy-Syams", "Al-Lail", "Ad-Duha", "Asy-Syarh", "At-Tin", "Al-'Alaq", "Al-Qadr", "Al-Bayyinah", "Az-Zalzalah", "Al-'Adiyat", "Al-Qari'ah", "At-Takasur", "Al-'Asr", "Al-Humazah", "Al-Fil", "Quraisy", "Al-Ma'un", "Al-Kausar", "Al-Kafirun", "An-Nasr", "Al-Lahab", "Al-Ikhlas", "Al-Falaq", "An-Nas"],
            "Juz 29" => ["Al-Mulk", "Al-Qalam", "Al-Haqqah", "Al-Ma'arij", "Nuh", "Al-Jinn", "Al-Muzzammil", "Al-Muddassir", "Al-Qiyamah", "Al-Insan", "Al-Mursalat"],
            "Juz 28" => ["Al-Mujadilah", "Al-Hashr", "Al-Mumtahanah", "As-Saff", "Al-Jumu'ah", "Al-Munafiqun", "At-Taghabun", "At-Talaq", "At-Tahrim"],
            "Juz 27" => ["Az-Zariyat", "At-Tur", "An-Najm", "Al-Qamar", "Ar-Rahman", "Al-Waqi'ah", "Al-Hadid"],
            "Juz 26" => ["Al-Ahqaf", "Muhammad", "Al-Fath", "Al-Hujurat", "Qaf", "Az-Zariyat"],
            "Juz 25" => ["Fussilat", "Asy-Syura", "Az-Zukhruf", "Ad-Dukhan", "Al-Jasiyah"],
            "Juz 24" => ["Az-Zumar", "Ghafir", "Fussilat"],
            "Juz 23" => ["Ya Sin", "As-Saffat", "Sad", "Az-Zumar"],
            "Juz 22" => ["Al-Ahzab", "Saba'", "Fatir", "Ya Sin"],
            "Juz 21" => ["Al-'Ankabut", "Ar-Rum", "Luqman", "As-Sajdah", "Al-Ahzab"],
            "Juz 20" => ["An-Naml", "Al-Qasas", "Al-'Ankabut"],
            "Juz 19" => ["Al-Furqan", "Asy-Syu'ara'", "An-Naml"],
            "Juz 18" => ["Al-Mu'minun", "An-Nur", "Al-Furqan"],
            "Juz 17" => ["Al-Anbiya'", "Al-Hajj"],
            "Juz 16" => ["Al-Kahf", "Maryam", "Ta Ha"],
            "Juz 15" => ["Al-Isra'", "Al-Kahf"],
            "Juz 14" => ["Al-Hijr", "An-Nahl"],
            "Juz 13" => ["Yusuf", "Ar-Ra'd", "Ibrahim"],
            "Juz 12" => ["Hud", "Yusuf"],
            "Juz 11" => ["At-Taubah", "Yunus", "Hud"],
            "Juz 10" => ["Al Anfal 41-60", "Al Anfal 61-75", "At Taubah 1-18", "At Taubah 19-33", "At Taubah 34-45", "At Taubah 46-59", "At Taubah 60-74", "At Taubah 75-93"],
            "Juz 9"  => ["Al A'raf 88-116", "Al A'raf 117-141", "Al A'raf 142-155", "Al A'raf 156-170", "Al A'raf 171-188", "Al A'raf 189-206", "Al Anfal 1-21", "Al Anfal 22-40"],
            "Juz 8"  => ["Al An'am 111-126", "Al An'am 127-140", "Al An'am 141-150", "Al An'am 151-165", "Al A'raf 1-30", "Al A'raf 31-46", "Al A'raf 47-64", "Al A'raf 65-87"],
            "Juz 7"  => ["Al Maidah 84-96", "Al Maidah 97-108", "Al Maidah 109-120", "Al An'am 1-12", "Al An'am 13-35", "Al An'am 36-58", "Al An'am 59-73", "Al An'am 74-94", "Al An'am 95-110"],
            "Juz 6"  => ["An Nisa' 148-162", "An Nisa' 163-176", "Al Maidah 1-11", "Al Maidah 12-26", "Al Maidah 27-40", "Al Maidah 41-50", "Al Maidah 51-66", "Al Maidah 67-83"],
            "Juz 5"  => ["An-Nisa' 12-23", "An-Nisa' 24-35", "An-Nisa' 36-57", "An-Nisa' 58-73", "An-Nisa' 74-87", "An-Nisa' 88-99", "An-Nisa' 100-113", "An-Nisa' 114-134", "An-Nisa' 135-147"],
            "Juz 4"  => ["Ali 'Imran 92-112", "Ali 'Imran 113-132", "Ali 'Imran 133-152", "Ali 'Imran 153-170", "Ali 'Imran 171-185", "Ali 'Imran 186-200", "An-Nisa' 1-11", "An-Nisa' 12-23"],
            "Juz 3"  => ["Al-Baqarah 253-262", "Al-Baqarah 263-271", "Al-Baqarah 272-282", "Al-Baqarah 283-286", "Ali 'Imran 1-14", "Ali 'Imran 15-32", "Ali 'Imran 33-51", "Ali 'Imran 52-74", "Ali 'Imran 75-91"],
            "Juz 2"  => ["Al-Baqarah 142-157", "Al-Baqarah 158-176", "Al-Baqarah 177-188", "Al-Baqarah 189-202", "Al-Baqarah 203-218", "Al-Baqarah 219-232", "Al-Baqarah 233-242", "Al-Baqarah 243-252"],
            "Juz 1"  => ["Al-Fatihah", "Al-Baqarah 1-25", "Al-Baqarah 26-43", "Al-Baqarah 44-59", "Al-Baqarah 60-74", "Al-Baqarah 75-91", "Al-Baqarah 92-105", "Al-Baqarah 106-123", "Al-Baqarah 124-141"]
        ];
    }

    public function index(): string
    {
        $rombels = $this->getRombelsGuru();
        $ta_aktif = $this->db->table('tahun_ajaran')->where('status', 'Aktif')->get()->getRowArray();

        $juz_data = [];
        $isDbMapped = false;
        
        // Deteksi Cerdas apakah tabel ref_surah punya kolom juz_id
        if ($this->db->tableExists('ref_surah')) {
            $fields = $this->db->getFieldNames('ref_surah');
            $builder = $this->db->table('ref_surah');
            
            if (in_array('juz_id', $fields) && $this->db->tableExists('ref_juz')) {
                $builder->select('ref_surah.nama_surah, ref_juz.nama_juz as juz_name')->join('ref_juz', 'ref_juz.id = ref_surah.juz_id', 'left');
                $isDbMapped = true;
            } elseif (in_array('juz', $fields)) {
                $builder->select('ref_surah.nama_surah, ref_surah.juz as juz_name');
                $isDbMapped = true;
            }
            
            if ($isDbMapped) {
                $surahs_db = $builder->orderBy('ref_surah.id', 'ASC')->get()->getResultArray();
                foreach ($surahs_db as $s) {
                    $juz_name = !empty($s['juz_name']) ? trim($s['juz_name']) : 'Juz Tidak Terdefinisi'; 
                    if (is_numeric($juz_name)) $juz_name = 'Juz ' . $juz_name;
                    $juz_name = ucwords(strtolower($juz_name)); 
                    $juz_data[$juz_name][] = trim($s['nama_surah']);
                }
            }
        }

        // Jika DB tidak mendukung pemetaan Juz, kita PAKSA pakai Peta Standar (100% Anti Bug)
        if (!$isDbMapped || empty($juz_data)) {
            $juz_data = $this->getStandardJuzMap();
        }

        $list_juz = array_keys($juz_data);
        usort($list_juz, function($a, $b) {
            $numA = (int) filter_var($a, FILTER_SANITIZE_NUMBER_INT);
            $numB = (int) filter_var($b, FILTER_SANITIZE_NUMBER_INT);
            return $numB - $numA; 
        });

        $data = [
            'title'       => 'Monitoring Klasemen Tahfidz',
            'user'        => session()->get('nama_lengkap') ?? session()->get('username') ?? 'Ustadz/ah',
            'navigations' => $this->getSidebarMenu(),
            'color'       => $this->getColor(),
            'rombels'     => $rombels,
            'ta_info'     => $ta_aktif,
            'list_juz'    => $list_juz,
            'juz_data'    => $juz_data 
        ];

        return view('tahfidz/monitoring/index', $data);
    }

    public function getData()
    {
        $rombel_id = $this->request->getGet('rombel_id');
        $juz       = $this->request->getGet('juz');

        if (!$rombel_id || !$juz) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Pilih kelas dan Juz terlebih dahulu.']);
        }

        $allowed_rombels = array_column($this->getRombelsGuru(), 'id');
        if (!in_array($rombel_id, $allowed_rombels)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Akses Ditolak.']);
        }

        $builder = $this->db->table('siswa')
                          ->select('siswa.id, siswa.nama_lengkap, siswa.nis, users.foto_profil')
                          ->join('users', 'users.id = siswa.user_id', 'left')
                          ->where('siswa.rombel_id', $rombel_id)
                          ->where('siswa.status_siswa', 'Aktif')
                          ->orderBy('siswa.nama_lengkap', 'ASC');

        if ($this->db->fieldExists('foto_siswa', 'siswa')) {
            $builder->select('siswa.foto_siswa as foto_siswa_asli');
        }

        $siswa = $builder->get()->getResultArray();
        $siswaIds = array_column($siswa, 'id');

        // MENGAMBIL LIST SURAH SPESIFIK UNTUK JUZ YANG DIPILIH
        $surahListInJuz = [];
        $isDbMapped = false;
        
        if ($this->db->tableExists('ref_surah')) {
            $fields = $this->db->getFieldNames('ref_surah');
            $b_surah = $this->db->table('ref_surah');
            
            if (in_array('juz_id', $fields) && $this->db->tableExists('ref_juz')) {
                $b_surah->select('ref_surah.nama_surah, ref_juz.nama_juz as juz_name')->join('ref_juz', 'ref_juz.id = ref_surah.juz_id', 'left');
                $isDbMapped = true;
            } elseif (in_array('juz', $fields)) {
                $b_surah->select('ref_surah.nama_surah, ref_surah.juz as juz_name');
                $isDbMapped = true;
            }
            
            if ($isDbMapped) {
                $all_surah_db = $b_surah->get()->getResultArray();
                foreach ($all_surah_db as $s) {
                    $j_name = !empty($s['juz_name']) ? trim($s['juz_name']) : ''; 
                    if (is_numeric($j_name)) $j_name = 'Juz ' . $j_name;
                    $j_name = ucwords(strtolower($j_name)); 
                    if ($j_name === $juz) {
                        $surahListInJuz[] = trim($s['nama_surah']);
                    }
                }
            }
        }

        if (!$isDbMapped || empty($surahListInJuz)) {
            $juzMap = $this->getStandardJuzMap();
            $surahListInJuz = $juzMap[$juz] ?? [];
        }

        // Pecah list target untuk dicocokkan ke database (karena DB menyimpan field Surah murni)
        $surahPureNames = [];
        foreach ($surahListInJuz as $target) {
            if (preg_match('/^(.*?)\s+[0-9-]+$/', $target, $matches)) {
                $surahPureNames[] = trim($matches[1]);
            } else {
                $surahPureNames[] = trim($target);
            }
        }
        $surahPureNames = array_unique($surahPureNames);

        $juzRow = $this->db->tableExists('ref_juz') ? $this->db->table('ref_juz')->where('nama_juz', $juz)->get()->getRowArray() : null;
        $juz_id_target = $juzRow ? $juzRow['id'] : null;

        $monitoringData = [];

        foreach ($siswa as $s) {
            $total_setor = 0;
            $hafalan_terakhir = null;
            $riwayat_5 = [];

            if ($this->db->tableExists('setoran_tahfidz')) {
                $b_total = $this->db->table('setoran_tahfidz')->where('siswa_id', $s['id']);
                $b_terakhir = $this->db->table('setoran_tahfidz')->where('siswa_id', $s['id']);
                $b_riwayat = $this->db->table('setoran_tahfidz')->select('predikat')->where('siswa_id', $s['id']);
                
                if ($juz_id_target) {
                    $b_total->where('juz_id', $juz_id_target);
                    $b_terakhir->where('juz_id', $juz_id_target);
                    $b_riwayat->where('juz_id', $juz_id_target);
                } elseif (!empty($surahPureNames)) {
                    $b_total->whereIn('surah', $surahPureNames);
                    $b_terakhir->whereIn('surah', $surahPureNames);
                    $b_riwayat->whereIn('surah', $surahPureNames);
                }

                $total_setor = $b_total->countAllResults();


                $hafalan_terakhir = $b_terakhir->orderBy('tanggal', 'DESC')
                                             ->orderBy('id', 'DESC')
                                             ->limit(1)
                                             ->get()
                                             ->getRowArray();

                $riwayat_5 = $b_riwayat->orderBy('tanggal', 'DESC')
                                      ->orderBy('id', 'DESC')
                                      ->limit(5)
                                      ->get()
                                      ->getResultArray();
            }

            // KUNCI: Prioritaskan foto_profil, lalu foto_siswa_asli
            $foto_profil = $s['foto_profil'] ?? '';
            $foto_siswa  = $s['foto_siswa_asli'] ?? '';
            $foto_fix    = !empty($foto_profil) ? $foto_profil : (!empty($foto_siswa) ? $foto_siswa : null);

            // Rangkai kembali nama surah dan ayat untuk "Capaian Terakhir"
            $target_akhir = '-';
            if ($hafalan_terakhir) {
                $target_akhir = trim($hafalan_terakhir['surah']);
                if (!empty($hafalan_terakhir['ayat']) && strtolower(trim($hafalan_terakhir['ayat'])) !== 'semua') {
                    $target_akhir .= ' ' . trim($hafalan_terakhir['ayat']);
                }
            }

            $monitoringData[] = [
                'id'               => $s['id'],
                'nama_lengkap'     => $s['nama_lengkap'],
                'nis'              => $s['nis'],
                'foto_fix'         => $foto_fix,
                'total_setor'      => $total_setor,
                'target_juz'       => $juz, 
                'surah_terakhir'   => $target_akhir,
                'predikat_terakhir'=> $hafalan_terakhir ? $hafalan_terakhir['predikat'] : '-',
                'riwayat_5'        => array_column($riwayat_5, 'predikat') 
            ];
        }

        return $this->response->setJSON(['status' => 'success', 'data' => $monitoringData]);
    }

    public function getRiwayat()
    {
        $siswa_id = $this->request->getGet('siswa_id');
        $juz      = $this->request->getGet('juz');

        if (!$siswa_id) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'ID Siswa tidak ditemukan.']);
        }

        // AMBIL JUGA FOTO DARI TABEL SISWA & USERS
        $builderSiswa = $this->db->table('siswa')
                                 ->select('siswa.nama_lengkap, users.foto_profil')
                                 ->join('users', 'users.id = siswa.user_id', 'left')
                                 ->where('siswa.id', $siswa_id);
                                 
        if ($this->db->fieldExists('foto_siswa', 'siswa')) {
            $builderSiswa->select('siswa.foto_siswa as foto_siswa_asli');
        }
        
        $siswa = $builderSiswa->get()->getRowArray();
        
        $nama_siswa = $siswa ? $siswa['nama_lengkap'] : 'Santri';
        $foto_profil = $siswa['foto_profil'] ?? '';
        $foto_siswa  = $siswa['foto_siswa_asli'] ?? '';
        $foto_fix    = !empty($foto_profil) ? $foto_profil : (!empty($foto_siswa) ? $foto_siswa : null);

        $riwayat = [];
        if ($this->db->tableExists('setoran_tahfidz') && $juz) {
            
            $surahListInJuz = [];
            $isDbMapped = false;
            
            if ($this->db->tableExists('ref_surah')) {
                $fields = $this->db->getFieldNames('ref_surah');
                $b_surah = $this->db->table('ref_surah');
                
                if (in_array('juz_id', $fields) && $this->db->tableExists('ref_juz')) {
                    $b_surah->select('ref_surah.nama_surah, ref_juz.nama_juz as juz_name')->join('ref_juz', 'ref_juz.id = ref_surah.juz_id', 'left');
                    $isDbMapped = true;
                } elseif (in_array('juz', $fields)) {
                    $b_surah->select('ref_surah.nama_surah, ref_surah.juz as juz_name');
                    $isDbMapped = true;
                }
                
                if ($isDbMapped) {
                    $all_surah_db = $b_surah->get()->getResultArray();
                    foreach ($all_surah_db as $s) {
                        $j_name = !empty($s['juz_name']) ? trim($s['juz_name']) : ''; 
                        if (is_numeric($j_name)) $j_name = 'Juz ' . $j_name;
                        $j_name = ucwords(strtolower($j_name)); 
                        if ($j_name === $juz) {
                            $surahListInJuz[] = trim($s['nama_surah']);
                        }
                    }
                }
            }

            $juzRow = $this->db->tableExists('ref_juz') ? $this->db->table('ref_juz')->where('nama_juz', $juz)->get()->getRowArray() : null;
            $juz_id_target = $juzRow ? $juzRow['id'] : null;

            if (!$isDbMapped || empty($surahListInJuz)) {
                $juzMap = $this->getStandardJuzMap();
                $surahListInJuz = $juzMap[$juz] ?? [];
            }

            $surahPureNames = [];
            foreach ($surahListInJuz as $target) {
                if (preg_match('/^(.*?)\s+[0-9-]+$/', $target, $matches)) {
                    $surahPureNames[] = trim($matches[1]);
                } else {
                    $surahPureNames[] = trim($target);
                }
            }
            if ($juz_id_target) {
                $riwayat = $this->db->table('setoran_tahfidz')
                                    ->where('siswa_id', $siswa_id)
                                    ->where('juz_id', $juz_id_target)
                                    ->orderBy('tanggal', 'DESC')
                                    ->orderBy('id', 'DESC')
                                    ->limit(10)
                                    ->get()
                                    ->getResultArray();
            } elseif (!empty($surahPureNames)) {
                $riwayat = $this->db->table('setoran_tahfidz')
                                    ->where('siswa_id', $siswa_id)
                                    ->whereIn('surah', $surahPureNames)
                                    ->orderBy('tanggal', 'DESC')
                                    ->orderBy('id', 'DESC')
                                    ->limit(10)
                                    ->get()
                                    ->getResultArray();
            }
        }

        return $this->response->setJSON([
            'status' => 'success', 
            'siswa'  => $nama_siswa,
            'foto' => $foto_fix,
            'data'   => $riwayat
        ]);
    }
}