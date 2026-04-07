<?php

namespace App\Controllers\Tahfidz;

use App\Controllers\TahfidzBaseController;

class NilaiTeoriController extends TahfidzBaseController
{
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    // PENDETEKSI KHUSUS GURU TAHFIDZ (Mencari di tabel/kolom yang tepat)
    private function getRombelsGuru() {
        $userId = session()->get('user_id');
        if (!$userId) return [];

        $guru = $this->db->table('guru_tendik')->where('user_id', $userId)->get()->getRowArray();
        if (!$guru) return [];

        $guru_id = $guru['id'];
        
        // Ambil info Tahun Ajaran Aktif
        $sess_ta  = session()->get('tahun_ajaran');
        $sess_smt = session()->get('semester');

        if ($sess_ta && $sess_smt) {
            $ta_aktif = $this->db->table('tahun_ajaran')->where('tahun', $sess_ta)->where('semester', $sess_smt)->get()->getRowArray();
        } else {
            $ta_aktif = $this->db->table('tahun_ajaran')->where('status', 'Aktif')->get()->getRowArray();
        }
        $id_ta = $ta_aktif ? $ta_aktif['id'] : 0;

        $rombels = [];

        // 1. CEK TABEL KHUSUS PEMBINA TAHFIDZ dengan Filter TA Aktif
        if ($this->db->tableExists('pembina_tahfidz')) {
            $rombels = $this->db->table('pembina_tahfidz')
                               ->select('rombel.id, rombel.nama_rombel, rombel.tingkat')
                               ->join('rombel', 'rombel.id = pembina_tahfidz.rombel_id')
                               ->where('pembina_tahfidz.guru_id', $guru_id)
                               ->where('rombel.id_tahun_ajaran', $id_ta)
                               ->groupBy('rombel.id')->get()->getResultArray();
        } elseif ($this->db->tableExists('guru_tahfidz')) {
            $rombels = $this->db->table('guru_tahfidz')
                               ->select('rombel.id, rombel.nama_rombel, rombel.tingkat')
                               ->join('rombel', 'rombel.id = guru_tahfidz.rombel_id')
                               ->where('guru_tahfidz.guru_id', $guru_id)
                               ->where('rombel.id_tahun_ajaran', $id_ta)
                               ->groupBy('rombel.id')->get()->getResultArray();
        }

        // 2. CEK KOLOM TAHFIDZ DI DALAM TABEL ROMBEL dengan Filter TA Aktif
        if (empty($rombels)) {
            $fields = $this->db->getFieldNames('rombel');
            $builder = $this->db->table('rombel')->select('id, nama_rombel, tingkat')->where('id_tahun_ajaran', $id_ta);
            $match = false;
            foreach ($fields as $field) {
                if (preg_match('/tahfidz|tahfiz|pembina|musyrif/i', $field)) {
                    $builder->orWhere($field, $guru_id);
                    $match = true;
                }
            }
            if ($match) {
                $rombels = $builder->get()->getResultArray();
            }
        }

        // 3. CEK GURU MAPEL (Hanya TA Aktif)
        if (empty($rombels)) {
            $rombels = $this->db->table('guru_mapel')
                               ->select('rombel.id, rombel.nama_rombel, rombel.tingkat')
                               ->join('rombel', 'rombel.id = guru_mapel.rombel_id')
                               ->where('guru_mapel.guru_id', $guru_id)
                               ->where('rombel.id_tahun_ajaran', $id_ta)
                               ->groupBy('rombel.id')->get()->getResultArray();
        }

        // Urutkan kelas agar rapi di dropdown
        if (!empty($rombels)) {
            usort($rombels, function($a, $b) {
                if ($a['tingkat'] == $b['tingkat']) {
                    return strcmp($a['nama_rombel'], $b['nama_rombel']);
                }
                return (int)$a['tingkat'] - (int)$b['tingkat'];
            });
        }

        return $rombels;
    }

    private function hitungPredikatTeori($nilai) {
        if ($nilai > 87) return 'Mutqin';
        if ($nilai > 70) return 'Jayyid Jiddan';
        if ($nilai > 60) return 'Jayyid';
        if ($nilai > 50) return 'Maqbül';
        return 'Mardüd';
    }

    public function index(): string
    {
        $ta_aktif = $this->db->table('tahun_ajaran')->where('status', 'Aktif')->get()->getRowArray();
        
        $juzList = [];
        if ($this->db->tableExists('ref_juz')) {
            $juzList = $this->db->table('ref_juz')->orderBy('id', 'DESC')->get()->getResultArray();
        }
        
        $data = [
            'title'       => 'Input Nilai Teori Tahfidz',
            'user'        => session()->get('nama_lengkap') ?? session()->get('username') ?? 'Ustadz/ah',
            'navigations' => $this->getSidebarMenu(),
            'color'       => $this->getColor(),
            'rombels'     => $this->getRombelsGuru(),
            'juzList'     => $juzList, 
            'ta_info'     => $ta_aktif 
        ];

        return view('tahfidz/nilai_teori/index', $data);
    }

    public function getSiswa()
    {
        try {
            $rombel_id = $this->request->getGet('rombel_id');
            // GET variable expected from JS is `juz`, not `juz_id`
            $juz       = $this->request->getGet('juz'); 
            
            if (!$rombel_id || !$juz) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Pilih Kelas dan Juz terlebih dahulu.']);
            }

            $ta = $this->db->table('tahun_ajaran')->where('status', 'Aktif')->get()->getRowArray();
            if (!$ta) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Tahun Ajaran Aktif belum diatur!']);
            }
            
            $semester = $ta['semester'];
            $ta_id    = $ta['id'];

            $hasFotoSiswa = $this->db->fieldExists('foto_siswa', 'siswa');
            $hasFoto = $this->db->fieldExists('foto', 'siswa');

            $builder = $this->db->table('siswa')
                              ->select('siswa.id, siswa.nama_lengkap, siswa.nis, siswa.nisn, users.foto_profil')
                              ->join('users', 'users.id = siswa.user_id', 'left')
                              ->where('siswa.rombel_id', $rombel_id)
                              ->where('siswa.status_siswa', 'Aktif')
                              ->orderBy('siswa.nama_lengkap', 'ASC');

            if ($hasFotoSiswa) {
                $builder->select('siswa.foto_siswa');
            } elseif ($hasFoto) {
                $builder->select('siswa.foto');
            }

            $siswa = $builder->get()->getResultArray();

            if (empty($siswa)) {
                return $this->response->setJSON(['status' => 'success', 'data' => [], 'semester' => $semester, 'tahun' => $ta['tahun']]);
            }
            
            $siswaIds = array_column($siswa, 'id');
            $nilai_db = [];
            
            $nilai_query = $this->db->table('nilai_tahfidz')
                                    ->whereIn('siswa_id', $siswaIds)
                                    ->where('tahun_ajaran_id', $ta_id)
                                    ->where('semester', $semester)
                                    ->where('juz_id', $juz)
                                    ->get()->getResultArray();
                                    
            foreach ($nilai_query as $nq) {
                $nilai_db[$nq['siswa_id']] = $nq['nilai_teori'];
            }

            $dataSiswa = [];
            foreach ($siswa as $s) {
                // LOGIKA HYBRID (Prioritaskan foto_profil dari tabel users)
                $fotoProfil = $s['foto_profil'] ?? '';
                $fotoSiswa  = '';
                if ($hasFotoSiswa) $fotoSiswa = $s['foto_siswa'] ?? '';
                elseif ($hasFoto) $fotoSiswa = $s['foto'] ?? '';

                $foto_fix = !empty($fotoProfil) ? $fotoProfil : (!empty($fotoSiswa) ? $fotoSiswa : null);

                $dataSiswa[] = [
                    'id'           => $s['id'],
                    'nama_lengkap' => $s['nama_lengkap'],
                    'nis'          => $s['nis'],
                    'nisn'         => $s['nisn'],
                    'foto_fix'     => $foto_fix, 
                    'nilai_teori'  => isset($nilai_db[$s['id']]) ? $nilai_db[$s['id']] : '', 
                ];
            }

            return $this->response->setJSON([
                'status'   => 'success', 
                'data'     => $dataSiswa, 
                'semester' => $semester, 
                'tahun'    => $ta['tahun']
            ]);

        } catch (\Exception $e) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'System Error: ' . $e->getMessage()]);
        }
    }

    public function save()
    {
        if ($this->request->isAJAX()) {
            try {
                $ta = $this->db->table('tahun_ajaran')->where('status', 'Aktif')->get()->getRowArray();
                if (!$ta) return $this->response->setJSON(['status' => 'error', 'message' => 'Tahun Ajaran Aktif belum ditentukan!']);

                $semester    = $ta['semester'];
                $ta_id       = $ta['id'];
                $siswa_id    = $this->request->getPost('siswa_id');
                $nilai_teori = $this->request->getPost('nilai_teori');
                $juz         = $this->request->getPost('juz'); 

                if(empty($juz)) return $this->response->setJSON(['status' => 'error', 'message' => 'Juz tidak valid.']);

                $count_simpan = 0;
                $count_hapus  = 0;

                if (is_array($siswa_id)) {
                    for ($i = 0; $i < count($siswa_id); $i++) {
                        $s_id = $siswa_id[$i];
                        
                        $existing = $this->db->table('nilai_tahfidz')
                                             ->where('siswa_id', $s_id)
                                             ->where('tahun_ajaran_id', $ta_id)
                                             ->where('semester', $semester)
                                             ->where('juz_id', $juz) 
                                             ->get()->getRowArray();

                        if (isset($nilai_teori[$i]) && trim($nilai_teori[$i]) !== '') {
                            $nilai_input = (int)$nilai_teori[$i];
                            $nilai_aman  = max(0, min(100, $nilai_input));
                            $predikat    = $this->hitungPredikatTeori($nilai_aman);

                            $data = [
                                'siswa_id'        => $s_id,
                                'tahun_ajaran_id' => $ta_id,
                                'semester'        => $semester,
                                'juz_id'          => $juz,
                                'nilai_teori'     => $nilai_aman,
                                'predikat'        => $predikat,
                                'deskripsi'       => '', 
                                'updated_at'      => date('Y-m-d H:i:s')
                            ];

                            if ($existing) {
                                $this->db->table('nilai_tahfidz')->where('id', $existing['id'])->update($data);
                            } else {
                                $data['created_at'] = date('Y-m-d H:i:s');
                                $this->db->table('nilai_tahfidz')->insert($data);
                            }
                            $count_simpan++;
                            
                        } else {
                            if ($existing) {
                                $this->db->table('nilai_tahfidz')->where('id', $existing['id'])->delete();
                                $count_hapus++;
                            }
                        }
                    }
                }

                if ($count_simpan > 0 || $count_hapus > 0) {
                    $msg = "Alhamdulillah, data tersimpan.";
                    if ($count_hapus > 0) $msg .= " ($count_hapus dibatalkan).";
                    return $this->response->setJSON(['status' => 'success', 'message' => $msg]);
                } else {
                    return $this->response->setJSON(['status' => 'warning', 'message' => "Tidak ada perubahan nilai yang dilakukan."]);
                }

            } catch (\Exception $e) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Gagal menyimpan: ' . $e->getMessage()]);
            }
        }
    }

    public function importCsv()
    {
        try {
            $file = $this->request->getFile('file_csv');
            $juz = $this->request->getPost('juz_import'); 
            
            if (empty($juz)) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Silakan pilih Juz terlebih dahulu.']);
            }

            if (!$file || !$file->isValid() || strtolower($file->getExtension()) !== 'csv') {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Format file tidak valid.']);
            }

            $ta = $this->db->table('tahun_ajaran')->where('status', 'Aktif')->get()->getRowArray();
            if (!$ta) return $this->response->setJSON(['status' => 'error', 'message' => 'Tahun Ajaran Aktif belum ditentukan!']);
            
            $semester = $ta['semester'];
            $ta_id    = $ta['id'];

            $handle = fopen($file->getTempName(), "r");
            $firstLine = fgets($handle);
            $delimiter = strpos($firstLine, ';') !== false ? ';' : ',';
            rewind($handle);
            
            $isFormatTeori = false;
            $nisIndex = -1;
            $nilaiIndex = -1;
            
            while (($row = fgetcsv($handle, 2000, $delimiter)) !== FALSE) {
                $rowStr = strtolower(implode(" ", $row));
                if (strpos($rowStr, 'nama') !== false && (strpos($rowStr, 'nis') !== false || strpos($rowStr, 'nisn') !== false)) {
                    $isFormatTeori = true;
                    foreach ($row as $index => $colName) {
                        $cleanCol = trim(preg_replace('/[\x00-\x1F\x7F-\xFF]/', '', strtolower($colName)));
                        if ($cleanCol === 'nis' || $cleanCol === 'nisn') $nisIndex = $index;
                        if ($cleanCol === 'nilai' || $cleanCol === 'nilai teori' || $cleanCol === 'angka') $nilaiIndex = $index;
                    }
                    if ($nilaiIndex !== -1) break;
                }
            }

            if (!$isFormatTeori || $nisIndex == -1 || $nilaiIndex == -1) {
                fclose($handle);
                return $this->response->setJSON(['status' => 'error', 'message' => 'Format Excel tidak dikenali. Pastikan ada kolom Nama, NIS/NISN, dan Angka.']);
            }

            $count = 0;
            while (($data = fgetcsv($handle, 2000, $delimiter)) !== FALSE) {
                if (!isset($data[$nisIndex])) continue;

                $nisn = preg_replace('/[\x00-\x1F\x7F-\xFF]/', '', trim($data[$nisIndex])); 
                if (empty($nisn) || strtolower($nisn) == 'nisn') continue;

                $siswa = $this->db->table('siswa')->select('id')->where('nisn', $nisn)->orWhere('nis', $nisn)->get()->getRowArray();
                if (!$siswa) continue; 

                if (!isset($data[$nilaiIndex]) || trim($data[$nilaiIndex]) === '') continue; 
                
                $nilai_input = (int)trim($data[$nilaiIndex]);
                $nilai_aman = max(0, min(100, $nilai_input));
                $predikat = $this->hitungPredikatTeori($nilai_aman);

                $existing = $this->db->table('nilai_tahfidz')
                                     ->where('siswa_id', $siswa['id'])
                                     ->where('tahun_ajaran_id', $ta_id)
                                     ->where('semester', $semester)
                                     ->where('juz_id', $juz)
                                     ->get()->getRowArray();

                if ($existing) {
                    $this->db->table('nilai_tahfidz')->where('id', $existing['id'])->update([
                        'nilai_teori' => $nilai_aman, 
                        'predikat'    => $predikat,
                        'updated_at'  => date('Y-m-d H:i:s')
                    ]);
                } else {
                    $insertData = [
                        'siswa_id'        => $siswa['id'],
                        'tahun_ajaran_id' => $ta_id,
                        'semester'        => $semester,
                        'juz_id'          => $juz,
                        'nilai_teori'     => $nilai_aman,
                        'predikat'        => $predikat, 
                        'deskripsi'       => '', 
                        'created_at'      => date('Y-m-d H:i:s'),
                        'updated_at'      => date('Y-m-d H:i:s')
                    ];
                    $this->db->table('nilai_tahfidz')->insert($insertData);
                }
                $count++;
            }
            fclose($handle);

            return $this->response->setJSON(['status' => 'success', 'message' => "$count Nilai Teori ($juz) berhasil diimpor dari Excel!"]);
            
        } catch (\Exception $e) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Gagal Impor Excel: ' . $e->getMessage()]);
        }
    }
}