<?php

namespace App\Controllers\Tahfidz;

use App\Models\TahfidzModel;
use App\Controllers\TahfidzBaseController;

class SetoranController extends TahfidzBaseController
{
    protected $tahfidzModel;
    protected $db;

    public function __construct()
    {
        $this->tahfidzModel = new TahfidzModel();
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

    public function index()
    {
        $rombels = $this->getRombelsGuru();
        $ta_aktif = $this->db->table('tahun_ajaran')->where('status', 'Aktif')->get()->getRowArray();

        $surah_db = $this->db->table('ref_surah')->get()->getResultArray();
        $surah_map = [];
        foreach($surah_db as $s) {
            $alphanumeric = preg_replace('/[^a-z0-9]/', '', strtolower(trim($s['nama_surah'])));
            if ($alphanumeric === 'allahab') $alphanumeric = 'almasad'; 
            $surah_map[$alphanumeric] = $s['id'];
        }

        $list_juz = [];
        $juz_data = [];
        $standard_map = $this->getStandardJuzMap();

        if ($this->db->tableExists('ref_juz')) {
            $juz_query = $this->db->table('ref_juz')->orderBy('id', 'ASC')->get()->getResultArray();
            foreach ($juz_query as $juz) {
                $list_juz[] = ['id' => $juz['id'], 'nama_juz' => $juz['nama_juz']];
                
                $juz_nama = trim($juz['nama_juz']);
                $blocks = isset($standard_map[$juz_nama]) ? $standard_map[$juz_nama] : [];
                
                if (!empty($blocks)) {
                    foreach ($blocks as $block) {
                        $s_name = $block;
                        $ayat = 'Semua';
                        if (preg_match('/^(.*?)\s+([0-9-]+)$/', $block, $m)) {
                            $s_name = trim($m[1]);
                            $ayat = trim($m[2]);
                        }
                        
                        $alpha_search = preg_replace('/[^a-z0-9]/', '', strtolower($s_name));
                        if ($alpha_search === 'allahab') $alpha_search = 'almasad';

                        $s_id = isset($surah_map[$alpha_search]) ? $surah_map[$alpha_search] : null;
                        
                        if ($s_id) {
                            $juz_data[$juz['id']][] = [
                                'surah_id' => $s_id,
                                'ayat'     => $ayat,
                                'display'  => $block
                            ];
                        }
                    }
                } else {
                    if ($juz['mulai_surah_id'] && $juz['sampai_surah_id']) {
                        $surahs = $this->db->table('ref_surah')
                                     ->where("id >=", $juz['mulai_surah_id'])
                                     ->where("id <=", $juz['sampai_surah_id'])
                                     ->orderBy('id', 'ASC')
                                     ->get()->getResultArray();
                        foreach($surahs as $s) {
                            $juz_data[$juz['id']][] = [
                                'surah_id' => $s['id'],
                                'ayat'     => 'Semua',
                                'display'  => $s['nama_surah']
                            ];
                        }
                    }
                }
            }
        }

        $data = [
            'title'       => 'Input Setoran Harian',
            'user'        => session()->get('nama_lengkap') ?? session()->get('username') ?? 'Ustadz/ah',
            'navigations' => $this->getSidebarMenu(),
            'color'       => $this->getColor(),
            'rombels'     => $rombels,
            'ta_info'     => $ta_aktif,
            'jml_kelas'   => count($rombels),
            'list_juz'    => $list_juz,
            'juz_data'    => $juz_data 
        ];

        return view('tahfidz/setoran/index', $data);
    }

    public function getSiswa()
    {
        $rombel_id = $this->request->getGet('rombel_id');
        $tanggal   = $this->request->getGet('tanggal') ?? date('Y-m-d');
        $juz_id    = $this->request->getGet('juz_id'); 
        
        if (!$rombel_id) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Pilih kelas terlebih dahulu.']);
        }

        $allowed_rombels = array_column($this->getRombelsGuru(), 'id');
        if (!in_array($rombel_id, $allowed_rombels)) {
            return $this->response->setJSON([
                'status' => 'error', 
                'message' => 'Akses Ditolak: Anda tidak berhak melihat data kelas ini.'
            ]);
        }

        $builder = $this->db->table('siswa')
                          ->select('siswa.id, siswa.nama_lengkap, siswa.nis, siswa.nisn, users.foto_profil')
                          ->join('users', 'users.id = siswa.user_id', 'left')
                          ->where('siswa.rombel_id', $rombel_id)
                          ->where('siswa.status_siswa', 'Aktif')
                          ->orderBy('siswa.nama_lengkap', 'ASC');

        if ($this->db->fieldExists('foto_siswa', 'siswa')) {
            $builder->select('siswa.foto_siswa');
        } elseif ($this->db->fieldExists('foto', 'siswa')) {
            $builder->select('siswa.foto as foto_siswa');
        }

        $siswa = $builder->get()->getResultArray();
        $siswaIds = array_column($siswa, 'id');
        
        $setoranHariIni = [];
        $riwayat_blok = []; 
        $semua_nilai_raw = []; 
        
        if (!empty($siswaIds) && $this->db->tableExists('setoran_tahfidz')) {
            
            $builder_riwayat = $this->db->table('setoran_tahfidz')
                                   ->select('setoran_tahfidz.*, ref_surah.nama_surah')
                                   ->join('ref_surah', 'ref_surah.id = setoran_tahfidz.surah_id', 'left')
                                   ->whereIn('siswa_id', $siswaIds)
                                   ->where('tanggal', $tanggal);
            
            if (!empty($juz_id)) {
                $builder_riwayat->where('setoran_tahfidz.juz_id', $juz_id);
            }

            $riwayatRaw = $builder_riwayat->get()->getResultArray();
            
            foreach ($riwayatRaw as $row) {
                $ay = (!empty($row['ayat']) && $row['ayat'] !== '-') ? trim($row['ayat']) : 'Semua';
                $row['block_val'] = $row['surah_id'] . '|' . $ay;
                
                $blockName = $row['nama_surah'];
                if ($ay !== 'Semua') $blockName .= ' ' . $ay;
                $row['block_name'] = $blockName;
                
                $setoranHariIni[$row['siswa_id']] = $row;
            }
            
            // Ambil SEMUA riwayat tanpa filter tanggal & jenis untuk Rekapitulasi Excel
            $riwayatAll = $this->db->table('setoran_tahfidz')
                                   ->select('siswa_id, surah_id, ayat, nilai')
                                   ->whereIn('siswa_id', $siswaIds)
                                   ->where('surah_id IS NOT NULL')
                                   ->orderBy('updated_at', 'ASC') // Timpa dengan nilai paling baru
                                   ->get()->getResultArray();
                                   
            foreach ($riwayatAll as $ra) {
                $ay = (!empty($ra['ayat']) && $ra['ayat'] !== '-') ? trim($ra['ayat']) : 'Semua';
                $key = $ra['surah_id'] . '|' . $ay;
                
                $riwayat_blok[$ra['siswa_id']][] = $key;
                $semua_nilai_raw[$ra['siswa_id']][$key] = $ra['nilai'];
            }
        }

        foreach ($siswa as &$s) {
            $fotoProfil = $s['foto_profil'] ?? '';
            $fotoSiswa  = $s['foto_siswa'] ?? '';
            $s['foto_fix'] = !empty($fotoProfil) ? $fotoProfil : (!empty($fotoSiswa) ? $fotoSiswa : null);
            
            $s['setoran'] = isset($setoranHariIni[$s['id']]) ? $setoranHariIni[$s['id']] : null;
            
            // KONVERSI KE ARRAY MURNI AGAR 100% AMAN DI JAVASCRIPT (TIDAK KOSONG)
            $nilai_array = [];
            if (isset($semua_nilai_raw[$s['id']])) {
                foreach ($semua_nilai_raw[$s['id']] as $k => $v) {
                    $parts = explode('|', $k);
                    $nilai_array[] = [
                        'surah_id' => $parts[0],
                        'ayat'     => isset($parts[1]) ? $parts[1] : 'Semua',
                        'nilai'    => (int)$v
                    ];
                }
            }
            $s['semua_nilai'] = $nilai_array;
        }

        return $this->response->setJSON(['status' => 'success', 'data' => $siswa]);
    }

    public function save()
    {
        if ($this->request->isAJAX()) {
            
            $tanggal       = $this->request->getPost('tanggal');
            $siswa_id      = $this->request->getPost('siswa_id');
            $jenis_setoran = $this->request->getPost('jenis_setoran');
            $juz_id        = $this->request->getPost('juz_id'); 
            $surah_inputs  = $this->request->getPost('surah_id'); 
            $nilai         = $this->request->getPost('nilai'); 
            $nilai_hfl     = $this->request->getPost('nilai_hfl');
            $nilai_hrf     = $this->request->getPost('nilai_hrf');
            $nilai_m       = $this->request->getPost('nilai_m');
            $nilai_t       = $this->request->getPost('nilai_t');
            $catatan       = $this->request->getPost('catatan');

            $userId = session()->get('user_id');
            $guru = $this->db->table('guru_tendik')->where('user_id', $userId)->get()->getRowArray();
            $guru_id = $guru ? $guru['id'] : 1; 

            $count_berhasil = 0;

            if (is_array($siswa_id)) {
                for ($i = 0; $i < count($siswa_id); $i++) {
                    $s_id  = $siswa_id[$i];
                    $j_id  = !empty($juz_id[$i]) ? (int)$juz_id[$i] : null;
                    $s_val = !empty($surah_inputs[$i]) ? trim($surah_inputs[$i]) : null;
                    
                    if ($s_val !== null && $j_id !== null) {
                        
                        $parts = explode('|', $s_val);
                        $s_sur = (int)$parts[0];
                        $ayat_fix = isset($parts[1]) ? $parts[1] : 'Semua';

                        $n_hfl = isset($nilai_hfl[$i]) && $nilai_hfl[$i] !== '' ? (int)$nilai_hfl[$i] : 0;
                        $n_hrf = isset($nilai_hrf[$i]) && $nilai_hrf[$i] !== '' ? (int)$nilai_hrf[$i] : 0;
                        $n_m = isset($nilai_m[$i]) && $nilai_m[$i] !== '' ? (int)$nilai_m[$i] : 0;
                        $n_t = isset($nilai_t[$i]) && $nilai_t[$i] !== '' ? (int)$nilai_t[$i] : 0;
                        $nilai_input = isset($nilai[$i]) && $nilai[$i] !== '' ? (int)$nilai[$i] : 0;
                        $nilai_aman  = max(0, min(100, $nilai_input));
                        
                        $pred = 'Belum Hafal';
                        if ($nilai_aman >= 90) $pred = 'Sangat Lancar';
                        elseif ($nilai_aman >= 80) $pred = 'Lancar';
                        elseif ($nilai_aman >= 70) $pred = 'Kurang Lancar';
                        elseif ($nilai_aman >= 60) $pred = 'Kurang Lancar'; 

                        // Ambil nama surah as it was required by DB column 'surah'. Surah id is priority, but 'surah' varchar still exists.
                        $surah_db = $this->db->table('ref_surah')->where('id', $s_sur)->get()->getRowArray();
                        $nama_surah_db = $surah_db ? $surah_db['nama_surah'] : '';

                        $data = [
                            'guru_id'       => $guru_id,
                            'jenis_setoran' => $jenis_setoran[$i],
                            'juz_id'        => $j_id,
                            'surah_id'      => $s_sur,
                            'surah'         => $nama_surah_db,
                            'ayat'          => $ayat_fix,
                            'predikat'      => $pred,
                            'nilai'         => $nilai_aman, 
                            'nilai_hfl'     => $n_hfl,
                            'nilai_hrf'     => $n_hrf,
                            'nilai_m'       => $n_m,
                            'nilai_t'       => $n_t,
                            'catatan'       => !empty(trim($catatan[$i])) ? trim($catatan[$i]) : null,
                            'updated_at'    => date('Y-m-d H:i:s')
                        ];

                        $existing = $this->db->table('setoran_tahfidz')
                                             ->where('siswa_id', $s_id)
                                             ->where('tanggal', $tanggal)
                                             ->where('juz_id', $j_id)
                                             ->get()->getRowArray();

                        if ($existing) {
                            $this->db->table('setoran_tahfidz')->where('id', $existing['id'])->update($data);
                        } else {
                            $data['siswa_id']   = $s_id;
                            $data['tanggal']    = $tanggal;
                            $data['created_at'] = date('Y-m-d H:i:s');
                            $this->db->table('setoran_tahfidz')->insert($data);
                        }
                        $count_berhasil++;
                    } else {
                        $existing = $this->db->table('setoran_tahfidz')
                                             ->where('siswa_id', $s_id)
                                             ->where('tanggal', $tanggal)
                                             ->where('juz_id', $j_id)
                                             ->get()->getRowArray();
                        if ($existing) {
                            $this->db->table('setoran_tahfidz')->where('id', $existing['id'])->delete();
                        }
                    }
                }
            }

            if ($count_berhasil > 0) {
                return $this->response->setJSON(['status' => 'success', 'message' => $count_berhasil . ' data setoran berhasil direkam!']);
            } else {
                return $this->response->setJSON(['status' => 'warning', 'message' => 'Tidak ada setoran yang disimpan karena kolom Target kosong.']);
            }
        }
        return $this->response->setJSON(['status' => 'error', 'message' => 'Metode tidak diizinkan.']);
    }

    public function importCsv()
    {
        $file = $this->request->getFile('file_csv');
        $tanggal = $this->request->getPost('tanggal_import');
        $juz_id_import = $this->request->getPost('juz_import');
        
        if (!$file || !$file->isValid() || strtolower($file->getExtension()) !== 'csv') {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Format file tidak valid. Wajib format CSV.']);
        }

        $handle = fopen($file->getTempName(), "r");
        $firstLine = fgets($handle);
        $delimiter = strpos($firstLine, ';') !== false ? ';' : ',';
        rewind($handle);
        
        $userId = session()->get('user_id');
        $guru = $this->db->table('guru_tendik')->where('user_id', $userId)->get()->getRowArray();
        $guru_id = $guru ? $guru['id'] : 1; 

        $standard_map = $this->getStandardJuzMap();
        $juz = $this->db->table('ref_juz')->where('id', $juz_id_import)->get()->getRowArray();
        $juz_nama = trim($juz['nama_juz']);
        
        $surah_db = $this->db->table('ref_surah')->get()->getResultArray();
        $surah_map = [];
        foreach($surah_db as $s) {
            $alphanumeric = preg_replace('/[^a-z0-9]/', '', strtolower(trim($s['nama_surah'])));
            if ($alphanumeric === 'allahab') $alphanumeric = 'almasad'; 
            $surah_map[$alphanumeric] = $s['id'];
        }
        
        $header_to_db_map = [];
        $blocks = isset($standard_map[$juz_nama]) ? $standard_map[$juz_nama] : [];
        
        if (!empty($blocks)) {
            foreach ($blocks as $block) {
                $s_name = $block;
                $ayat = 'Semua';
                if (preg_match('/^(.*?)\s+([0-9-]+)$/', $block, $m)) {
                    $s_name = trim($m[1]);
                    $ayat = trim($m[2]);
                }
                
                $alpha_search = preg_replace('/[^a-z0-9]/', '', strtolower($s_name));
                if ($alpha_search === 'allahab') $alpha_search = 'almasad';
                
                $s_id = isset($surah_map[$alpha_search]) ? $surah_map[$alpha_search] : null;
                if ($s_id) {
                    $clean_block = preg_replace('/[^a-z0-9]/', '', strtolower(trim($block)));
                    $clean_block = str_replace('allahab', 'almasad', $clean_block);
                    $header_to_db_map[$clean_block] = ['surah_id' => $s_id, 'ayat' => $ayat];
                }
            }
        } else {
            if ($juz['mulai_surah_id'] && $juz['sampai_surah_id']) {
                $surahs = $this->db->table('ref_surah')
                             ->where("id >=", $juz['mulai_surah_id'])
                             ->where("id <=", $juz['sampai_surah_id'])
                             ->get()->getResultArray();
                foreach($surahs as $s) {
                    $clean_nama = preg_replace('/[^a-z0-9]/', '', strtolower(trim($s['nama_surah'])));
                    $clean_nama = str_replace('allahab', 'almasad', $clean_nama);
                    $header_to_db_map[$clean_nama] = ['surah_id' => $s['id'], 'ayat' => 'Semua'];
                }
            }
        }

        $isFormatSekolah = false;
        $nisIndex = -1;
        $surahColumns = []; 
        
        while (($row = fgetcsv($handle, 2000, $delimiter)) !== FALSE) {
            $rowStr = strtolower(implode(" ", $row));
            
            if (strpos($rowStr, 'nama') !== false && (strpos($rowStr, 'nis') !== false || strpos($rowStr, 'nisn') !== false) && strpos($rowStr, 'kelas') !== false) {
                $isFormatSekolah = true;
                foreach ($row as $index => $colName) {
                    $cleanCol = trim(preg_replace('/[\x00-\x1F\x7F-\xFF]/', '', $colName));
                    if (strtolower($cleanCol) === 'nis' || strtolower($cleanCol) === 'nisn') {
                        $nisIndex = $index;
                    } elseif ($index >= 4 && !empty($cleanCol)) {
                        $surahColumns[$index] = $cleanCol;
                    }
                }
                break;
            }
        }

        if (!$isFormatSekolah || $nisIndex == -1 || empty($surahColumns)) {
            fclose($handle);
            return $this->response->setJSON(['status' => 'error', 'message' => 'Format Header CSV tidak dikenali. Pastikan Anda menggunakan Template dari sistem.']);
        }

        $count = 0;

        while (($data = fgetcsv($handle, 2000, $delimiter)) !== FALSE) {
            if (!isset($data[$nisIndex])) continue;
            
            $nisn = preg_replace('/[\x00-\x1F\x7F-\xFF]/', '', trim($data[$nisIndex])); 
            if (empty($nisn) || strtolower($nisn) == 'nisn') continue;

            $siswa = $this->db->table('siswa')->select('id')->where('nisn', $nisn)->orWhere('nis', $nisn)->get()->getRowArray();
            if (!$siswa) continue; 

            foreach ($surahColumns as $index => $rawSurahName) {
                if (!isset($data[$index]) || trim($data[$index]) === '') continue; 
                
                $nilai_input = (int)trim($data[$index]);
                if ($nilai_input <= 0) continue; 

                $nilai_aman = max(0, min(100, $nilai_input));
                
                $predikat = 'Belum Hafal';
                if ($nilai_aman >= 90) $predikat = 'Sangat Lancar';
                elseif ($nilai_aman >= 80) $predikat = 'Lancar';
                elseif ($nilai_aman >= 70) $predikat = 'Kurang Lancar';

                $cleanHeader = preg_replace('/[^a-z0-9]/', '', strtolower(trim($rawSurahName)));
                $cleanHeader = str_replace('allahab', 'almasad', $cleanHeader);
                
                $target_data = isset($header_to_db_map[$cleanHeader]) ? $header_to_db_map[$cleanHeader] : null;

                if ($target_data !== null) {
                    $insertData = [
                        'guru_id'       => $guru_id,
                        'jenis_setoran' => 'Ziyadah', 
                        'juz_id'        => $juz_id_import,
                        'surah_id'      => $target_data['surah_id'],
                        'ayat'          => $target_data['ayat'],
                        'predikat'      => $predikat,
                        'nilai'         => $nilai_aman,
                        'catatan'       => 'Impor Excel',
                        'updated_at'    => date('Y-m-d H:i:s')
                    ];

                    $existing = $this->db->table('setoran_tahfidz')
                                         ->where('siswa_id', $siswa['id'])
                                         ->where('tanggal', $tanggal)
                                         ->where('juz_id', $juz_id_import)
                                         ->get()->getRowArray();

                    if ($existing) {
                        $this->db->table('setoran_tahfidz')->where('id', $existing['id'])->update($insertData);
                    } else {
                        $insertData['siswa_id']   = $siswa['id'];
                        $insertData['tanggal']    = $tanggal;
                        $insertData['created_at'] = date('Y-m-d H:i:s');
                        $this->db->table('setoran_tahfidz')->insert($insertData);
                    }
                    $count++;
                }
            }
        }
        fclose($handle);

        return $this->response->setJSON(['status' => 'success', 'message' => "Data dari $count Setoran berhasil diimpor dengan sempurna!"]);
    }
}