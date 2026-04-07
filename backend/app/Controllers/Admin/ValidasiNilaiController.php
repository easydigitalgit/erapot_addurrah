<?php

namespace App\Controllers\Admin;

use App\Controllers\AdminBaseController;
use App\Models\Admin\ValidasiNilaiModel;
use App\Models\Admin\SiswaModel;
use App\Models\Admin\NilaiAkademikModel;
use App\Models\Admin\RombelModel;

class ValidasiNilaiController extends AdminBaseController
{
    protected $validasiModel;
    protected $siswaModel;
    protected $nilaiModel;
    protected $rombelModel;
    protected $db;

    public function __construct()
    {
        $this->validasiModel = new ValidasiNilaiModel();
        $this->siswaModel = new SiswaModel();
        $this->nilaiModel = new NilaiAkademikModel();
        $this->rombelModel = new RombelModel();
        $this->db = \Config\Database::connect();
    }

    public function index()
    {
        $ta_aktif = $this->db->table('tahun_ajaran')->where('status', 'Aktif')->get()->getRowArray();
        $tahun_ajaran = $ta_aktif ? $ta_aktif['tahun'] : '2025/2026';
        $semester     = $ta_aktif ? $ta_aktif['semester'] : 'Genap';
        $ta_id        = $ta_aktif ? $ta_aktif['id'] : null;

        $tabelAcuan = $this->db->tableExists('nilai_akademik') ? 'nilai_akademik' : ($this->db->tableExists('nilai_formatif') ? 'nilai_formatif' : 'nilai_sumatif');
        $fieldSemester = $this->db->fieldExists('semester', $tabelAcuan);
        $fieldTahunAjaran = $this->db->fieldExists('tahun_ajaran_id', 'guru_mapel') ? 'tahun_ajaran_id' : 'tahun_ajaran';
        $nilaiTaField = $fieldTahunAjaran == 'tahun_ajaran_id' ? $ta_id : $tahun_ajaran;

        $list_tingkat = $this->db->table('rombel')->select('tingkat')->distinct()->orderBy('tingkat', 'ASC')->get()->getResultArray();
        $list_rombel  = $this->rombelModel->orderBy('nama_rombel', 'ASC')->findAll();
        $list_wali    = $this->db->table('guru_tendik')->select('id, nama_lengkap')->orderBy('nama_lengkap', 'ASC')->get()->getResultArray();

        $f_tingkat = $this->request->getGet('tingkat');
        $f_rombel  = $this->request->getGet('rombel');
        $f_wali    = $this->request->getGet('wali');
        $f_status  = $this->request->getGet('status');

        $builder = $this->db->table('rombel r')
            ->select('r.id, r.nama_rombel, r.tingkat, g.nama_lengkap as wali_kelas, u.foto_profil, v.is_locked, v.locked_at')
            ->join('guru_tendik g', 'g.id = r.wali_kelas_id', 'left')
            ->join('users u', 'u.id = g.user_id', 'left') // JOIN KE TABEL USERS UNTUK FOTO
            ->join('validasi_nilai v', 'v.rombel_id = r.id', 'left')
            ->orderBy('r.tingkat', 'ASC')
            ->orderBy('r.nama_rombel', 'ASC');

        if ($f_tingkat) $builder->where('r.tingkat', $f_tingkat);
        if ($f_rombel)  $builder->where('r.id', $f_rombel);
        if ($f_wali)    $builder->where('r.wali_kelas_id', $f_wali);

        $rombels = $builder->get()->getResultArray();

        $dataKelas = [];
        $stats = ['total' => count($rombels), 'siap' => 0, 'belum' => 0, 'locked' => 0];

        foreach ($rombels as $r) {
            // 🚀 MENGGUNAKAN MESIN WAKTU: Dapatkan array ID siswa aktif di kelas ini pada masa itu
            $siswaData = $this->db->table('anggota_rombel ar')
                ->join('siswa s', 's.id = ar.siswa_id')
                ->where('ar.rombel_id', $r['id'])
                ->where('ar.tahun_ajaran_id', $ta_id)
                ->where('ar.semester', $semester)
                ->where('s.status_siswa', 'Aktif')
                ->select('s.id')
                ->get()->getResultArray();
                
            // Dapatkan array ID siswa aktif di kelas ini
            $siswaIds = array_column($siswaData, 'id');
            $jumlahSiswa = !empty($siswaIds) ? count($siswaIds) : 0;
            
            $jumlahMapel = $this->db->table('guru_mapel')
                ->where('rombel_id', $r['id'])
                ->where($fieldTahunAjaran, $nilaiTaField)
                ->where('status', 'active')
                ->countAllResults(); 
            
            $targetNilai = $jumlahSiswa * ($jumlahMapel > 0 ? $jumlahMapel : 1); 

            // LOGIKA BARU: Hitung berdasarkan siswa_id agar 100% akurat menembus tabel relasi
            $nilaiMasuk = 0;
            if ($jumlahSiswa > 0) {
                $qNilai = $this->db->table($tabelAcuan)->whereIn('siswa_id', $siswaIds);
                if ($fieldSemester) {
                    $qNilai->where('semester', $semester);
                }
                
                // Cek kolom yang menampung nilai (nilai_angka di 42, nilai di 43_sumatif)
                if ($this->db->fieldExists('nilai_angka', $tabelAcuan)) {
                    $qNilai->where('nilai_angka IS NOT NULL')->where('nilai_angka !=', '');
                } else if ($this->db->fieldExists('nilai', $tabelAcuan)) {
                    $qNilai->where('nilai IS NOT NULL')->where('nilai !=', '');
                }

                $nilaiMasuk = $qNilai->countAllResults();
            }

            if ($jumlahSiswa == 0 || $jumlahMapel == 0) {
                $persen = 0; 
            } else {
                $persen = ($targetNilai > 0) ? round(($nilaiMasuk / $targetNilai) * 100) : 0;
            }
            if ($persen > 100) $persen = 100;

            $status_code = '';
            if ($r['is_locked'] == 1) {
                $status = 'Terkunci';
                $badge = 'gray';
                $status_code = 'terkunci';
                $stats['locked']++;
            } elseif ($persen >= 100 && $jumlahSiswa > 0) {
                $status = 'Siap Validasi'; 
                $badge = 'success';
                $status_code = 'siap';
                $stats['siap']++;
            } else {
                $status = 'Belum Lengkap';
                $badge = 'warning';
                $status_code = 'belum';
                $stats['belum']++;
            }

            if ($f_status && $f_status != $status_code) {
                continue;
            }

            $dataKelas[] = [
                'id'         => $r['id'],
                'tingkat'    => $r['tingkat'],
                'rombel'     => $r['nama_rombel'],
                'wali_kelas' => $r['wali_kelas'] ?: 'Belum Diatur',
                'foto_profil'=> $r['foto_profil'] ?? null, // TAMBAHKAN BARIS INI
                'progress'   => $persen,
                'status'     => $status,
                'badge'      => $badge,
                'is_locked'  => $r['is_locked'],
                'locked_at'  => $r['locked_at']
            ];
        }

        $data = [
            'title'       => 'Validasi & Lock Nilai',
            'user'        => 'Admin',
            'navigations' => $this->getSidebarMenu(),
            'color'       => $this->getColor(),
            'list_tingkat'=> $list_tingkat,
            'list_rombel' => $list_rombel,
            'list_wali'   => $list_wali,
            'data_kelas'  => $dataKelas,
            'stats'       => $stats,
            'filter'      => [
                'tingkat' => $f_tingkat,
                'rombel'  => $f_rombel,
                'wali'    => $f_wali,
                'status'  => $f_status
            ]
        ];

        return view('admin/validasi-nilai', $data);
    }

    // MENGAMBIL DATA DETAIL UNTUK DRAWER (REALTIME)
    public function getDetailRombel($rombel_id)
    {
        if (!$this->request->isAJAX()) return $this->response->setStatusCode(404);

        $ta_aktif = $this->db->table('tahun_ajaran')->where('status', 'Aktif')->get()->getRowArray();
        $tahun_ajaran = $ta_aktif ? $ta_aktif['tahun'] : '2025/2026';
        $semester     = $ta_aktif ? $ta_aktif['semester'] : 'Genap';
        $ta_id        = $ta_aktif ? $ta_aktif['id'] : null;

        $tabelAcuan = $this->db->tableExists('nilai_akademik') ? 'nilai_akademik' : ($this->db->tableExists('nilai_formatif') ? 'nilai_formatif' : 'nilai_sumatif');
        $fieldSemester = $this->db->fieldExists('semester', $tabelAcuan);
        $fieldTahunAjaran = $this->db->fieldExists('tahun_ajaran_id', 'guru_mapel') ? 'tahun_ajaran_id' : 'tahun_ajaran';
        $nilaiTaField = $fieldTahunAjaran == 'tahun_ajaran_id' ? $ta_id : $tahun_ajaran;

        // 🚀 MENGGUNAKAN MESIN WAKTU: Cari ID siswa aktif di kelas ini pada masa itu
        $siswaData = $this->db->table('anggota_rombel ar')
            ->join('siswa s', 's.id = ar.siswa_id')
            ->where('ar.rombel_id', $rombel_id)
            ->where('ar.tahun_ajaran_id', $ta_id)
            ->where('ar.semester', $semester)
            ->where('s.status_siswa', 'Aktif')
            ->select('s.id')
            ->get()->getResultArray();
            
        $siswaIds = array_column($siswaData, 'id');
        $jumlahSiswa = !empty($siswaIds) ? count($siswaIds) : 0;

        // PERBAIKAN: Menambahkan select gm.mapel_id untuk referensi query selanjutnya
        $mapelQuery = $this->db->table('guru_mapel gm')
            ->select('gm.id, m.nama_mapel, g.nama_lengkap as guru, gm.mapel_id')
            ->join('mata_pelajaran m', 'm.id = gm.mapel_id')
            ->join('guru_tendik g', 'g.id = gm.guru_id')
            ->where('gm.rombel_id', $rombel_id)
            ->where('gm.' . $fieldTahunAjaran, $nilaiTaField)
            ->where('gm.status', 'active')
            ->get()->getResultArray();

        $detailMapel = [];
        $mapelSelesai = 0;

        foreach ($mapelQuery as $mq) {
            $nilaiMasuk = 0;

            // LOGIKA BARU: Filter nilai_akademik berdasarkan mapel_id dan siswa yang ada di kelas ini
            if ($jumlahSiswa > 0) {
                $qNilai = $this->db->table($tabelAcuan)
                    ->whereIn('siswa_id', $siswaIds)
                    ->where('mapel_id', $mq['mapel_id']); // Akurat mendeteksi mapel
                if ($fieldSemester) {
                    $qNilai->where('semester', $semester);
                }
                if ($this->db->fieldExists('nilai_angka', $tabelAcuan)) {
                    $qNilai->where('nilai_angka IS NOT NULL')->where('nilai_angka !=', '');
                } else if ($this->db->fieldExists('nilai', $tabelAcuan)) {
                    $qNilai->where('nilai IS NOT NULL')->where('nilai !=', '');
                }
                $nilaiMasuk = $qNilai->countAllResults();
            }

            $persen = ($jumlahSiswa > 0) ? min(round(($nilaiMasuk / $jumlahSiswa) * 100), 100) : 0;
            
            if ($persen >= 100) $mapelSelesai++;

            $detailMapel[] = [
                'mapel'    => $mq['nama_mapel'],
                'guru'     => $mq['guru'],
                'progress' => $persen
            ];
        }

        return $this->response->setJSON([
            'status'        => 'success',
            'siswa_aktif'   => $jumlahSiswa,
            'mapel_total'   => count($mapelQuery),
            'mapel_selesai' => $mapelSelesai,
            'detail_mapel'  => $detailMapel
        ]);
    }

    public function prosesLock()
    {
        if ($this->request->isAJAX()) {
            $rombel_id = $this->request->getPost('rombel_id');
            
            if (!$rombel_id) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'ID Kelas tidak ditemukan.']);
            }

            $ta_aktif = $this->db->table('tahun_ajaran')->where('status', 'Aktif')->get()->getRowArray();
            $tahun_ajaran = $ta_aktif ? $ta_aktif['tahun'] : '2025/2026';
            $semester     = $ta_aktif ? $ta_aktif['semester'] : 'Genap';
            $ta_id        = $ta_aktif ? $ta_aktif['id'] : null;

            $tabelAcuan = $this->db->tableExists('nilai_akademik') ? 'nilai_akademik' : ($this->db->tableExists('nilai_formatif') ? 'nilai_formatif' : 'nilai_sumatif');
            $fieldSemester = $this->db->fieldExists('semester', $tabelAcuan);
            $fieldTahunAjaran = $this->db->fieldExists('tahun_ajaran_id', 'guru_mapel') ? 'tahun_ajaran_id' : 'tahun_ajaran';
            $nilaiTaField = $fieldTahunAjaran == 'tahun_ajaran_id' ? $ta_id : $tahun_ajaran;

            // 🚀 MENGGUNAKAN MESIN WAKTU
            $siswaData = $this->db->table('anggota_rombel ar')
                ->join('siswa s', 's.id = ar.siswa_id')
                ->where('ar.rombel_id', $rombel_id)
                ->where('ar.tahun_ajaran_id', $ta_id)
                ->where('ar.semester', $semester)
                ->where('s.status_siswa', 'Aktif')
                ->select('s.id')
                ->get()->getResultArray();
                
            $siswaIds = array_column($siswaData, 'id');
            $jumlahSiswa = !empty($siswaIds) ? count($siswaIds) : 0;

            $jumlahMapel = $this->db->table('guru_mapel')->where('rombel_id', $rombel_id)->where($fieldTahunAjaran, $nilaiTaField)->where('status', 'active')->countAllResults();
            
            if ($jumlahSiswa == 0 || $jumlahMapel == 0) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Kelas ini kosong atau belum ada mapping guru.']);
            }

            $targetNilai = $jumlahSiswa * $jumlahMapel;
            
            $qNilai = $this->db->table($tabelAcuan)->whereIn('siswa_id', $siswaIds);
            if ($fieldSemester) {
                $qNilai->where('semester', $semester);
            }
            if ($this->db->fieldExists('nilai_angka', $tabelAcuan)) {
                $qNilai->where('nilai_angka IS NOT NULL')->where('nilai_angka !=', '');
            } else if ($this->db->fieldExists('nilai', $tabelAcuan)) {
                $qNilai->where('nilai IS NOT NULL')->where('nilai !=', '');
            }
            $nilaiMasuk = $qNilai->countAllResults();

            if ($nilaiMasuk < $targetNilai) {
                $kurang = $targetNilai - $nilaiMasuk;
                return $this->response->setJSON([
                    'status'  => 'error', 
                    'title'   => 'Nilai Belum Lengkap!',
                    'message' => "Masih ada <b>{$kurang} data nilai</b> yang belum diinput oleh para guru. Harap periksa detail kelas sebelum melakukan Lock."
                ]);
            }

            $cek = $this->validasiModel->where('rombel_id', $rombel_id)->first();
            $dataLock = [
                'rombel_id'         => $rombel_id,
                'is_locked'         => 1,
                'locked_at'         => date('Y-m-d H:i:s'),
                'locked_by'         => session()->get('id') ?? 1,
                'progress_akademik' => 100
            ];

            if ($cek) {
                $this->validasiModel->update($cek['id'], $dataLock);
            } else {
                $this->validasiModel->insert($dataLock);
            }

            return $this->response->setJSON(['status'  => 'success', 'message' => 'Kelas berhasil dikunci (Locked).']);
        }
    }

    public function unlock()
    {
        if ($this->request->isAJAX()) {
            $rombel_id = $this->request->getPost('rombel_id');
            $cek = $this->validasiModel->where('rombel_id', $rombel_id)->first();

            if ($cek) {
                $this->validasiModel->update($cek['id'], [
                    'is_locked' => 0,
                    'locked_at' => null,
                    'locked_by' => null
                ]);
                return $this->response->setJSON(['status' => 'success', 'message' => 'Kunci kelas berhasil dibuka.']);
            }
            return $this->response->setJSON(['status' => 'error', 'message' => 'Data tidak ditemukan.']);
        }
    }

    public function lockMassal()
    {
        if (!$this->request->isAJAX()) return $this->response->setStatusCode(404);

        $ta_aktif = $this->db->table('tahun_ajaran')->where('status', 'Aktif')->get()->getRowArray();
        $tahun_ajaran = $ta_aktif ? $ta_aktif['tahun'] : '2025/2026';
        $semester     = $ta_aktif ? $ta_aktif['semester'] : 'Genap';
        $ta_id        = $ta_aktif ? $ta_aktif['id'] : null;

        $tabelAcuan = $this->db->tableExists('nilai_akademik') ? 'nilai_akademik' : ($this->db->tableExists('nilai_formatif') ? 'nilai_formatif' : 'nilai_sumatif');
        $fieldSemester = $this->db->fieldExists('semester', $tabelAcuan);
        $fieldTahunAjaran = $this->db->fieldExists('tahun_ajaran_id', 'guru_mapel') ? 'tahun_ajaran_id' : 'tahun_ajaran';
        $nilaiTaField = $fieldTahunAjaran == 'tahun_ajaran_id' ? $ta_id : $tahun_ajaran;

        $rombels = $this->db->table('rombel')->get()->getResultArray();
        $lockedCount = 0;
        $skippedCount = 0;

        foreach ($rombels as $r) {
            $cekLock = $this->validasiModel->where('rombel_id', $r['id'])->where('is_locked', 1)->first();
            if ($cekLock) continue; 

            // 🚀 MENGGUNAKAN MESIN WAKTU
            $siswaData = $this->db->table('anggota_rombel ar')
                ->join('siswa s', 's.id = ar.siswa_id')
                ->where('ar.rombel_id', $r['id'])
                ->where('ar.tahun_ajaran_id', $ta_id)
                ->where('ar.semester', $semester)
                ->where('s.status_siswa', 'Aktif')
                ->select('s.id')
                ->get()->getResultArray();
                
            $siswaIds = array_column($siswaData, 'id');
            $jumlahSiswa = !empty($siswaIds) ? count($siswaIds) : 0;

            $jumlahMapel = $this->db->table('guru_mapel')->where('rombel_id', $r['id'])->where($fieldTahunAjaran, $nilaiTaField)->where('status', 'active')->countAllResults();
            
            if ($jumlahSiswa == 0 || $jumlahMapel == 0) {
                $skippedCount++; continue;
            }

            $targetNilai = $jumlahSiswa * $jumlahMapel;
            
            $qNilai = $this->db->table($tabelAcuan)->whereIn('siswa_id', $siswaIds);
            if ($fieldSemester) {
                $qNilai->where('semester', $semester);
            }
            if ($this->db->fieldExists('nilai_angka', $tabelAcuan)) {
                $qNilai->where('nilai_angka IS NOT NULL')->where('nilai_angka !=', '');
            } else if ($this->db->fieldExists('nilai', $tabelAcuan)) {
                $qNilai->where('nilai IS NOT NULL')->where('nilai !=', '');
            }
            $nilaiMasuk = $qNilai->countAllResults();

            if ($nilaiMasuk >= $targetNilai) {
                $cek = $this->validasiModel->where('rombel_id', $r['id'])->first();
                $dataLock = [
                    'rombel_id'         => $r['id'],
                    'is_locked'         => 1,
                    'locked_at'         => date('Y-m-d H:i:s'),
                    'locked_by'         => session()->get('id') ?? 1,
                    'progress_akademik' => 100
                ];

                if ($cek) $this->validasiModel->update($cek['id'], $dataLock);
                else $this->validasiModel->insert($dataLock);
                
                $lockedCount++;
            } else {
                $skippedCount++;
            }
        }

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => "Berhasil mengunci <b>$lockedCount Kelas</b> secara otomatis. <br> <small class='text-gray-500 mt-2 block'>($skippedCount kelas dilewati karena nilai belum lengkap)</small>"
        ]);
    }
}