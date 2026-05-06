<?php

namespace App\Controllers\Tahfidz;

use App\Controllers\TahfidzBaseController;

class DashboardController extends TahfidzBaseController
{
    private function getRombelsGuru() {
        $db = \Config\Database::connect();
        $userId = session()->get('user_id');
        $guru = $db->table('guru_tendik')->where('user_id', $userId)->get()->getRowArray();
        
        if ($guru) {
            $sess_ta  = session()->get('tahun_ajaran');
            $sess_smt = session()->get('semester');

            if ($sess_ta && $sess_smt) {
                $ta_aktif = $db->table('tahun_ajaran')->where('tahun', $sess_ta)->where('semester', $sess_smt)->get()->getRowArray();
            } else {
                $ta_aktif = $db->table('tahun_ajaran')->where('status', 'Aktif')->get()->getRowArray();
            }

            $id_ta = $ta_aktif ? $ta_aktif['id'] : 0;

            $rombels = $db->table('guru_mapel')
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
        $db = \Config\Database::connect();
        $hari_ini = date('Y-m-d');
        
        // AMBIL TAHUN AJARAN AKTIF
        $sess_ta  = session()->get('tahun_ajaran');
        $sess_smt = session()->get('semester');
        if ($sess_ta && $sess_smt) {
            $ta_aktif = $db->table('tahun_ajaran')->where('tahun', $sess_ta)->where('semester', $sess_smt)->get()->getRowArray();
        } else {
            $ta_aktif = $db->table('tahun_ajaran')->where('status', 'Aktif')->get()->getRowArray();
        }
        $tahun_ajaran_aktif = $ta_aktif ? $ta_aktif['tahun'] . ' (' . $ta_aktif['semester'] . ')' : 'Belum Diatur';

        $rombels = $this->getRombelsGuru();
        $rombel_ids = array_column($rombels, 'id');
        
        $sekolah = $db->table('sekolah')->select('nama_sekolah, warna_primary, warna_secondary')->get()->getRowArray();
        $color = [
            'warna_primary'   => $sekolah ? $sekolah['warna_primary'] : '#10b981',
            'warna_secondary' => $sekolah ? $sekolah['warna_secondary'] : '#ecfdf5',
        ];

        $userId = session()->get('user_id');
        $userRow = $db->table('users')->select('nama_lengkap')->where('id', $userId)->get()->getRowArray();
        $nama_lengkap_user = !empty($userRow['nama_lengkap']) ? $userRow['nama_lengkap'] : (session()->get('nama_lengkap') ?? 'Ustadz/ah');

        // MAPPING ID KE TEMPLATE SEKOLAH (BLOK)
        $surah_db = $db->table('ref_surah')->get()->getResultArray();
        $surah_map = [];
        foreach($surah_db as $s) {
            $alphanumeric = preg_replace('/[^a-z0-9]/', '', strtolower(trim($s['nama_surah'])));
            $surah_map[$alphanumeric] = $s['id'];
        }

        $list_juz = [];
        $juz_data = [];
        $standard_map = $this->getStandardJuzMap();

        if ($db->tableExists('ref_juz')) {
            $juz_query = $db->table('ref_juz')->orderBy('id', 'ASC')->get()->getResultArray();
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
                        $surahs = $db->table('ref_surah')
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

        $total_siswa = 0;
        $setoran_hari_ini = 0;
        $persentase = 0;
        $target_tercapai = 0;
        $setoran_terakhir = [];
        $perhatian = [];
        $distribusi = ['juz30' => 0, 'juz29' => 0, 'juz28' => 0];
        $siswa_binaan = [];
        $riwayat_blok = []; 

        if (!empty($rombel_ids)) {
            $builder = $db->table('siswa')
                          ->select('siswa.id, siswa.nama_lengkap, rombel.nama_rombel, users.foto_profil')
                          ->join('rombel', 'rombel.id = siswa.rombel_id', 'left')
                          ->join('users', 'users.id = siswa.user_id', 'left')
                          ->whereIn('siswa.rombel_id', $rombel_ids)
                          ->where('siswa.status_siswa', 'Aktif')
                          ->orderBy('siswa.nama_lengkap', 'ASC');

            $siswa_binaan = $builder->get()->getResultArray();
            $total_siswa = count($siswa_binaan);
            $siswa_ids = array_column($siswa_binaan, 'id');

            if (!empty($siswa_ids)) {
                
                if ($db->tableExists('setoran_tahfidz')) {
                    // Cari Block Terakhir Tiap Santri
                    foreach ($siswa_binaan as &$sb) {
                        $latest = $db->table('setoran_tahfidz')->where('siswa_id', $sb['id'])->orderBy('tanggal', 'DESC')->orderBy('id', 'DESC')->limit(1)->get()->getRowArray();
                        if ($latest && $latest['juz_id'] && $latest['surah_id']) {
                            $sb['juz_id_terakhir'] = $latest['juz_id'];
                            $ay = (!empty($latest['ayat']) && $latest['ayat'] !== '-') ? trim($latest['ayat']) : 'Semua';
                            $sb['block_terakhir'] = $latest['surah_id'] . '|' . $ay;
                        } else {
                            $sb['juz_id_terakhir'] = '';
                            $sb['block_terakhir'] = '';
                        }
                    }

                    $setoran_hari_ini = $db->table('setoran_tahfidz')->whereIn('siswa_id', $siswa_ids)->where('tanggal', $hari_ini)->countAllResults();
                                           
                    // Ambil Ziyadah Anti-Double
                    $setoran_data = $db->table('setoran_tahfidz')
                                       ->select('siswa_id, surah_id, ayat')
                                       ->whereIn('siswa_id', $siswa_ids)
                                       ->where('jenis_setoran', 'Ziyadah')
                                       ->where('surah_id IS NOT NULL')
                                       ->get()->getResultArray();
                    foreach ($setoran_data as $sd) {
                        $ay = (!empty($sd['ayat']) && $sd['ayat'] !== '-') ? trim($sd['ayat']) : 'Semua';
                        $riwayat_blok[$sd['siswa_id']][] = $sd['surah_id'] . '|' . $ay;
                    }
                }

                $persentase = ($total_siswa > 0) ? round(($setoran_hari_ini / $total_siswa) * 100) : 0;

                if ($db->tableExists('setoran_tahfidz')) {
                    $target_tercapai = $db->table('setoran_tahfidz')->select('siswa_id')->whereIn('siswa_id', $siswa_ids)->groupBy('siswa_id')->having('COUNT(id) >=', 5)->countAllResults();
                }

                if ($db->tableExists('setoran_tahfidz')) {
                    $setoran_terakhir = $db->table('setoran_tahfidz st')
                                           ->select('st.*, s.nama_lengkap, rs.nama_surah')
                                           ->join('siswa s', 's.id = st.siswa_id', 'left')
                                           ->join('ref_surah rs', 'rs.id = st.surah_id', 'left')
                                           ->whereIn('st.siswa_id', $siswa_ids)
                                           ->orderBy('st.created_at', 'DESC')
                                           ->limit(6)
                                           ->get()->getResultArray();
                }

                if ($db->tableExists('setoran_tahfidz') && $db->tableExists('siswa')) {
                    $tanggal_batas = date('Y-m-d', strtotime('-14 days')); 
                    $inClause = implode(',', $siswa_ids);

                    $sqlPerhatian = "
                        SELECT 
                            s.id, s.nama_lengkap, r.nama_rombel, u.foto_profil,
                            (SELECT st1.predikat FROM setoran_tahfidz st1 WHERE st1.siswa_id = s.id ORDER BY st1.tanggal DESC, st1.id DESC LIMIT 1) as predikat_terakhir,
                            (SELECT st3.tanggal FROM setoran_tahfidz st3 WHERE st3.siswa_id = s.id ORDER BY st3.tanggal DESC, st3.id DESC LIMIT 1) as tanggal_terakhir
                        FROM siswa s
                        LEFT JOIN rombel r ON r.id = s.rombel_id
                        LEFT JOIN users u ON u.id = s.user_id
                        WHERE s.status_siswa = 'Aktif' AND s.id IN ($inClause)
                        HAVING (tanggal_terakhir IS NULL OR tanggal_terakhir < '$tanggal_batas') 
                            OR predikat_terakhir IN ('Kurang Lancar', 'Belum Hafal', 'Mardüd') 
                        ORDER BY tanggal_terakhir ASC LIMIT 6
                    ";
                    $rawPerhatian = $db->query($sqlPerhatian)->getResultArray();

                    foreach($rawPerhatian as $p) {
                        $alasan = '';
                        $predikat_ui = 'Kurang Lancar'; 
                        if (empty($p['tanggal_terakhir']) || $p['tanggal_terakhir'] < $tanggal_batas) {
                            $alasan = 'Jarang Setor (Terakhir: ' . ($p['tanggal_terakhir'] ? date('d M', strtotime($p['tanggal_terakhir'])) : 'Belum Ada') . ')';
                            $predikat_ui = 'Belum Hafal'; 
                        } elseif (in_array($p['predikat_terakhir'], ['Kurang Lancar', 'Belum Hafal', 'Mardüd'])) {
                            $alasan = 'Predikat: ' . $p['predikat_terakhir'];
                            $predikat_ui = $p['predikat_terakhir']; 
                        } else {
                            $alasan = 'Perlu Pendampingan Muroja\'ah';
                            $predikat_ui = 'Kurang Lancar'; 
                        }

                        $perhatian[] = [
                            'nama_lengkap' => $p['nama_lengkap'],
                            'nama_rombel'  => ($p['nama_rombel'] ?? '-') . ' • ' . $alasan,
                            'predikat'     => $predikat_ui,
                            'foto_profil'  => $p['foto_profil'] ?? null, 
                            'created_at'   => date('Y-m-d H:i:s')
                        ];
                    }
                }

                if ($db->tableExists('setoran_tahfidz')) {
                    $juz30 = $db->table('setoran_tahfidz')->whereIn('siswa_id', $siswa_ids)->where('juz_id', 30)->countAllResults();
                    $juz29 = $db->table('setoran_tahfidz')->whereIn('siswa_id', $siswa_ids)->where('juz_id', 29)->countAllResults();
                    $juz28 = $db->table('setoran_tahfidz')->whereIn('siswa_id', $siswa_ids)->where('juz_id', 28)->countAllResults();
                    
                    $total_juz = $juz30 + $juz29 + $juz28;
                    if ($total_juz > 0) {
                        $distribusi['juz30'] = round(($juz30 / $total_juz) * 100);
                        $distribusi['juz29'] = round(($juz29 / $total_juz) * 100);
                        $distribusi['juz28'] = round(($juz28 / $total_juz) * 100);
                    }
                }
            }
        }

        $data = [
            'title'              => 'Dashboard Guru Tahfidz',
            'user'               => $nama_lengkap_user, 
            'color'              => $color, 
            'navigations'        => $this->getSidebarMenu(),
            'total_siswa'        => $total_siswa,
            'setoran_hari_ini'   => $setoran_hari_ini,
            'persentase'         => $persentase,
            'target_tercapai'    => $target_tercapai,
            'setoran_terakhir'   => $setoran_terakhir,
            'perhatian'          => $perhatian,
            'distribusi'         => $distribusi,
            'siswa_binaan'       => $siswa_binaan,
            'list_juz'           => $list_juz,
            'juz_data'           => $juz_data,
            'riwayat_blok'       => json_encode($riwayat_blok),
            'tahun_ajaran_aktif' => $tahun_ajaran_aktif
        ];

        return view('tahfidz/dashboard', $data);    
    }

    public function exportRekap()
    {
        $db = \Config\Database::connect();
        $hari_ini = date('Y-m-d');
        $rombels = $this->getRombelsGuru();
        $rombel_ids = array_column($rombels, 'id');
        
        $builder = $db->table('setoran_tahfidz st')
                      ->select('s.nama_lengkap, r.nama_rombel, rs.nama_surah, st.ayat, st.jenis_setoran, st.nilai, st.predikat, st.created_at')
                      ->join('siswa s', 's.id = st.siswa_id', 'left')
                      ->join('rombel r', 'r.id = s.rombel_id', 'left')
                      ->join('ref_surah rs', 'rs.id = st.surah_id', 'left')
                      ->where('st.tanggal', $hari_ini);
                      
        if (!empty($rombel_ids)) { $builder->whereIn('s.rombel_id', $rombel_ids); } 
        else { $builder->where('s.rombel_id', 0); }

        $setoran = $builder->orderBy('st.created_at', 'DESC')->get()->getResultArray();
        $filename = 'Rekap_Setoran_Tahfidz_' . date('d_M_Y') . '.csv';

        header("Content-Description: File Transfer");
        header("Content-Disposition: attachment; filename=$filename");
        header("Content-Type: application/csv; charset=UTF-8");
        
        $file = fopen('php://output', 'w');
        fputs($file, $bom =( chr(0xEF) . chr(0xBB) . chr(0xBF) ));
        
        $header = array("No", "Waktu Setor", "Nama Santri", "Kelas", "Target Blok Surah", "Jenis Setoran", "Nilai", "Predikat");
        fputcsv($file, $header);

        $no = 1;
        foreach ($setoran as $row) {
            $surah_display = $row['nama_surah'] ?? '-';
            if (!empty($row['ayat']) && strtolower(trim($row['ayat'])) !== 'semua') {
                $surah_display .= ' ' . trim($row['ayat']);
            }
            fputcsv($file, array(
                $no++, date('H:i', strtotime($row['created_at'])) . ' WIB',
                $row['nama_lengkap'], $row['nama_rombel'] ?? '-',
                $surah_display, $row['jenis_setoran'], $row['nilai'] ?? 0, $row['predikat']
            ));
        }
        fclose($file);
        exit;
    }
}