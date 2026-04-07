<?php

namespace App\Controllers\Tahfidz;

use App\Controllers\TahfidzBaseController;

class NilaiRaporController extends TahfidzBaseController
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

    public function index(): string
    {
        $ta_aktif = $this->db->table('tahun_ajaran')->where('status', 'Aktif')->get()->getRowArray();
        $rombels = $this->getRombelsGuru();
        
        $juzList = [];
        if ($this->db->tableExists('ref_juz')) {
            $juzList = $this->db->table('ref_juz')->orderBy('id', 'DESC')->get()->getResultArray();
        }

        $data = [
            'title'       => 'Hasil Nilai Rapor Tahfidz',
            'user'        => session()->get('nama_lengkap') ?? session()->get('username') ?? 'Ustadz/ah',
            'navigations' => $this->getSidebarMenu(),
            'color'       => $this->getColor(),
            'rombels'     => $rombels,
            'juzList'     => $juzList,
            'ta_info'     => $ta_aktif 
        ];

        return view('tahfidz/nilai_rapor/index', $data);
    }

    private function getSurahByJuz($juzName) {
        $juzMap = [
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
        return $juzMap[$juzName] ?? [];
    }

    public function getSiswa()
    {
        $rombel_id = $this->request->getGet('rombel_id');
        $juz       = $this->request->getGet('juz'); 
        
        $ta = $this->db->table('tahun_ajaran')->where('status', 'Aktif')->get()->getRowArray();
        if (!$ta) return $this->response->setJSON(['status' => 'error', 'message' => 'Tahun Ajaran Aktif belum diatur oleh Admin!']);
        
        $semester = $ta['semester'];
        $ta_id    = $ta['id'];

        $tahun_str = explode('/', $ta['tahun'])[0]; 
        if ($semester === 'Ganjil') {
            $tgl_mulai = $tahun_str . '-07-01'; 
            $tgl_akhir = $tahun_str . '-12-31'; 
        } else {
            $tahun_genap = (int)$tahun_str + 1; 
            $tgl_mulai = $tahun_genap . '-01-01'; 
            $tgl_akhir = $tahun_genap . '-06-30'; 
        }

        if (!$rombel_id || !$juz) return $this->response->setJSON(['status' => 'error', 'message' => 'Silakan pilih kelas dan Juz terlebih dahulu.']);

        $siswa = $this->db->table('siswa')
                          ->select('siswa.id, siswa.nama_lengkap, siswa.nis, siswa.nisn, siswa.foto_siswa, users.foto_profil')
                          ->join('users', 'users.id = siswa.user_id', 'left')
                          ->where('siswa.rombel_id', $rombel_id)
                          ->where('siswa.status_siswa', 'Aktif')
                          ->orderBy('siswa.nama_lengkap', 'ASC')
                          ->get()->getResultArray();

        $dataSiswa = [];

        foreach ($siswa as $s) {
            // Mengambil Nilai Teori dan Narasi yang ada di tabel nilai_tahfidz
            $nilai = $this->db->table('nilai_tahfidz')
                              ->where('siswa_id', $s['id'])
                              ->where('tahun_ajaran_id', $ta_id)
                              ->where('semester', $semester)
                              ->where('juz_id', $juz)
                              ->get()->getRowArray();

            $total_setor = 0;
            $rata_rata = 0;
            $hafalan_terakhir = null;

            $total_setor = $this->db->table('setoran_tahfidz')
                                    ->where('siswa_id', $s['id'])
                                    ->where('juz_id', $juz)
                                    ->where('tanggal >=', $tgl_mulai)
                                    ->where('tanggal <=', $tgl_akhir)
                                    ->countAllResults();

            $avgQuery = $this->db->table('setoran_tahfidz')
                                 ->selectAvg('nilai', 'rata_rata')
                                 ->selectAvg('nilai_hfl', 'rata_hfl')
                                 ->selectAvg('nilai_hrf', 'rata_hrf')
                                 ->selectAvg('nilai_m', 'rata_m')
                                 ->selectAvg('nilai_t', 'rata_t')
                                 ->where('siswa_id', $s['id'])
                                 ->where('juz_id', $juz)
                                 ->where('tanggal >=', $tgl_mulai)
                                 ->where('tanggal <=', $tgl_akhir)
                                 ->where('nilai >', 0) 
                                 ->get()->getRowArray();
                                 
            $rata_rata = $avgQuery && $avgQuery['rata_rata'] !== null ? round($avgQuery['rata_rata']) : 0;
            $rata_hfl = $avgQuery && $avgQuery['rata_hfl'] !== null ? round($avgQuery['rata_hfl']) : 0;
            $rata_hrf = $avgQuery && $avgQuery['rata_hrf'] !== null ? round($avgQuery['rata_hrf']) : 0;
            $rata_m = $avgQuery && $avgQuery['rata_m'] !== null ? round($avgQuery['rata_m']) : 0;
            $rata_t = $avgQuery && $avgQuery['rata_t'] !== null ? round($avgQuery['rata_t']) : 0;

            $hafalan_terakhir = $this->db->table('setoran_tahfidz')
                                         ->where('siswa_id', $s['id'])
                                         ->where('juz_id', $juz)
                                         ->where('tanggal >=', $tgl_mulai)
                                         ->where('tanggal <=', $tgl_akhir)
                                         ->orderBy('tanggal', 'DESC')
                                         ->orderBy('id', 'DESC')
                                         ->limit(1)->get()->getRowArray();

            // --- LOGIKA HYBRID AVATAR ---
            $foto_profil = $s['foto_profil'] ?? '';
            $foto_siswa  = $s['foto_siswa'] ?? '';
            $foto_fix    = !empty($foto_profil) ? $foto_profil : (!empty($foto_siswa) ? $foto_siswa : null);
            // ----------------------------

            $dataSiswa[] = [
                'id'              => $s['id'],
                'nama_lengkap'    => $s['nama_lengkap'],
                'nis'             => $s['nis'],
                'nisn'            => $s['nisn'],
                'foto_fix'        => $foto_fix, 
                'nilai_teori'     => $nilai && isset($nilai['nilai_teori']) ? $nilai['nilai_teori'] : 0, 
                'deskripsi'       => $nilai ? $nilai['deskripsi'] : '',
                'total_setor'     => $total_setor,
                'surah_terakhir'  => $hafalan_terakhir ? $hafalan_terakhir['surah'] : '-',
                'ayat_terakhir'   => $hafalan_terakhir ? $hafalan_terakhir['ayat'] : '-',
                'rata_rata'       => $rata_rata,
                'rata_hfl'        => $rata_hfl,
                'rata_hrf'        => $rata_hrf,
                'rata_m'          => $rata_m,
                'rata_t'          => $rata_t
            ];
        }

        return $this->response->setJSON([
            'status'   => 'success', 
            'data'     => $dataSiswa, 
            'semester' => $semester, 
            'tahun'    => $ta['tahun']
        ]);
    }

    public function save()
    {
        if ($this->request->isAJAX()) {
            $ta = $this->db->table('tahun_ajaran')->where('status', 'Aktif')->get()->getRowArray();
            if (!$ta) return $this->response->setJSON(['status' => 'error', 'message' => 'Tahun Ajaran Aktif belum ditentukan!']);

            $semester  = $ta['semester'];
            $ta_id     = $ta['id'];
            
            $siswa_id  = $this->request->getPost('siswa_id');
            $predikat  = $this->request->getPost('predikat');
            $deskripsi = $this->request->getPost('deskripsi');
            $juz       = $this->request->getPost('juz');

            if(empty($juz)) return $this->response->setJSON(['status' => 'error', 'message' => 'Juz tidak valid.']);

            $count = 0;
            if (is_array($siswa_id)) {
                for ($i = 0; $i < count($siswa_id); $i++) {
                    if (!empty(trim($deskripsi[$i]))) { 
                        $data = [
                            'siswa_id'        => $siswa_id[$i],
                            'tahun_ajaran_id' => $ta_id,
                            'semester'        => $semester,
                            'juz_id'          => $juz,
                            'predikat'        => $predikat[$i],
                            'deskripsi'       => trim($deskripsi[$i]),
                            'updated_at'      => date('Y-m-d H:i:s')
                        ];

                        $existing = $this->db->table('nilai_tahfidz')
                                             ->where('siswa_id', $siswa_id[$i])
                                             ->where('tahun_ajaran_id', $ta_id)
                                             ->where('semester', $semester)
                                             ->where('juz_id', $juz)
                                             ->get()->getRowArray();

                        if ($existing) {
                            $this->db->table('nilai_tahfidz')->where('id', $existing['id'])->update($data);
                        } else {
                            $data['created_at'] = date('Y-m-d H:i:s');
                            $this->db->table('nilai_tahfidz')->insert($data);
                        }
                        $count++;
                    }
                }
            }

            if ($count > 0) {
                return $this->response->setJSON(['status'  => 'success', 'message' => "Alhamdulillah, $count data rapor semester $semester ($juz) berhasil diamankan."]);
            } else {
                return $this->response->setJSON(['status'  => 'warning', 'message' => "Tidak ada perubahan data yang disimpan karena kolom narasi rapor kosong semua."]);
            }
        }
    }

    public function importCsv()
    {
        $file = $this->request->getFile('file_csv');
        $juz = $this->request->getPost('juz_import'); 
        
        if (empty($juz)) return $this->response->setJSON(['status' => 'error', 'message' => 'Pilih Juz terlebih dahulu sebelum mengimpor.']);
        if (!$file || !$file->isValid() || strtolower($file->getExtension()) !== 'csv') return $this->response->setJSON(['status' => 'error', 'message' => 'Format file tidak valid.']);

        $ta = $this->db->table('tahun_ajaran')->where('status', 'Aktif')->get()->getRowArray();
        if (!$ta) return $this->response->setJSON(['status' => 'error', 'message' => 'Tahun Ajaran Aktif belum ditentukan!']);
        
        $semester = $ta['semester'];
        $ta_id    = $ta['id'];

        $handle = fopen($file->getTempName(), "r");
        
        $firstLine = fgets($handle);
        $delimiter = strpos($firstLine, ';') !== false ? ';' : ',';
        rewind($handle);
        
        // Deteksi Kolom Narasi secara cerdas
        $narasiIndex = -1;
        while (($row = fgetcsv($handle, 2000, $delimiter)) !== FALSE) {
            $rowStr = strtolower(implode(" ", $row));
            if (strpos($rowStr, 'nama') !== false && (strpos($rowStr, 'narasi') !== false || strpos($rowStr, 'deskripsi') !== false)) {
                foreach ($row as $index => $colName) {
                    $cleanCol = trim(strtolower($colName));
                    if (strpos($cleanCol, 'narasi') !== false || strpos($cleanCol, 'deskripsi') !== false) {
                        $narasiIndex = $index;
                    }
                }
                break;
            }
        }

        if ($narasiIndex == -1) {
            fclose($handle);
            return $this->response->setJSON(['status' => 'error', 'message' => 'Format Excel tidak dikenali. Pastikan Anda mengunduh Template Rapor dari sistem.']);
        }

        $count = 0;
        while (($data = fgetcsv($handle, 2000, $delimiter)) !== FALSE) {
            $nisn = preg_replace('/[\x00-\x1F\x7F-\xFF]/', '', trim($data[0] ?? '')); 
            if (empty($nisn) || strtolower($nisn) == 'nisn') continue;

            $siswa = $this->db->table('siswa')->select('id')->where('nisn', $nisn)->orWhere('nis', $nisn)->get()->getRowArray();
            if (!$siswa) continue; 

            $deskripsi = trim($data[$narasiIndex] ?? '');
            if (empty($deskripsi)) continue;

            $insertData = [
                'siswa_id'        => $siswa['id'],
                'tahun_ajaran_id' => $ta_id,
                'semester'        => $semester,
                'juz_id'          => $juz,
                'deskripsi'       => $deskripsi,
                'updated_at'      => date('Y-m-d H:i:s')
            ];

            $existing = $this->db->table('nilai_tahfidz')
                                 ->where('siswa_id', $siswa['id'])
                                 ->where('tahun_ajaran_id', $ta_id)
                                 ->where('semester', $semester)
                                 ->where('juz_id', $juz)
                                 ->get()->getRowArray();

            if ($existing) {
                $this->db->table('nilai_tahfidz')->where('id', $existing['id'])->update($insertData);
            } else {
                $insertData['created_at'] = date('Y-m-d H:i:s');
                $insertData['predikat'] = 'Kurang';
                $this->db->table('nilai_tahfidz')->insert($insertData);
            }
            $count++;
        }
        fclose($handle);

        return $this->response->setJSON(['status' => 'success', 'message' => "$count Narasi Rapor ($juz) berhasil diimpor dari Excel!"]);
    }
    public function preview($siswa_id)
    {
        $adminTahfidz = new \App\Controllers\Admin\TahfidzController();
        return $adminTahfidz->cetakRapor($siswa_id);
    }
}