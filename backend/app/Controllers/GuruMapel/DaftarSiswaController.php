<?php

namespace App\Controllers\GuruMapel;

use App\Controllers\GuruMapelBaseController;

class DaftarSiswaController extends GuruMapelBaseController
{
    public function index(): string
    {
        $db = \Config\Database::connect();
        $userId = session()->get('id'); 

        // 1. CARI IDENTITAS GURU (GURU_ID) BERDASARKAN USER_ID LOGIN
        $dataGuru = $db->table('guru_tendik')
                       ->select('id')
                       ->where('user_id', $userId)
                       ->get()
                       ->getRowArray();
                       
        $guruId = $dataGuru ? $dataGuru['id'] : 0; 

        // 2. AMBIL SEMUA PENUGASAN KELAS UNTUK GURU INI (Untuk Dropdown Filter)
        $allPenugasan = $db->table('guru_mapel gm')
            ->select('gm.rombel_id, gm.mapel_id, gm.jam_per_minggu, r.nama_rombel, m.nama_mapel')
            ->join('rombel r', 'r.id = gm.rombel_id', 'left')
            ->join('mata_pelajaran m', 'm.id = gm.mapel_id', 'left')
            ->where('gm.guru_id', $guruId)
            ->get()->getResultArray();

        // 3. TENTUKAN KELAS & MAPEL MANA YANG SEDANG DIBUKA (Dari Parameter URL GET)
        $activeRombelId = $this->request->getGet('rombel') ?? ($allPenugasan[0]['rombel_id'] ?? 0);
        $activeMapelId  = $this->request->getGet('mapel')  ?? ($allPenugasan[0]['mapel_id'] ?? 0);

        // Cari detail spesifik dari kelas yang sedang aktif
        $currentAssignment = array_filter($allPenugasan, function($p) use ($activeRombelId, $activeMapelId) {
            return $p['rombel_id'] == $activeRombelId && $p['mapel_id'] == $activeMapelId;
        });
        $currentAssignment = reset($currentAssignment);

        // 4. HITUNG JUMLAH SISWA DI KELAS TERSEBUT
        $jumlah_siswa = 0;
        if ($activeRombelId > 0) {
            $jumlah_siswa = $db->table('siswa')
                               ->where('rombel_id', $activeRombelId)
                               ->where('status_siswa', 'Aktif')
                               ->countAllResults();
        }

        // 5. SIAPKAN DATA UNTUK VIEW
        $infoCard = [
            'mapel'        => $currentAssignment['nama_mapel'] ?? 'Belum Ada Mapel',
            'rombel'       => $currentAssignment['nama_rombel'] ?? 'Belum Ada Kelas',
            'jumlah_siswa' => $jumlah_siswa,
            'jam_mengajar' => ($currentAssignment['jam_per_minggu'] ?? '0') . ' Jam / Minggu',
            'rombel_id'    => $activeRombelId,
            'mapel_id'     => $activeMapelId
        ];

        $data = [
            'user'        => session()->get('nama_lengkap') ?? session()->get('username') ?? 'Guru Mapel',
            'navigations' => $this->getSidebarMenu(),
            'color'       => $this->getColor(),
            'allRombel'   => $allPenugasan, // Data untuk Dropdown Switch Kelas
            'info'        => $infoCard
        ];

        return view('GuruMapel/daftar-siswa', $data); 
    }

    public function getStudentsData()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(404);
        }

        $db = \Config\Database::connect();
        
        $rombel_id = $this->request->getGet('rombel_id'); 
        $mapel_id  = $this->request->getGet('mapel_id');
        $tahun_ajaran = '2026/2027'; // Pastikan sesuai dengan tahun ajaran database (seperti kasus sebelumnya)
        $semester = 'Genap';

        $siswas = [];
        if ($rombel_id && $mapel_id) {
            $builder = $db->table('siswa s');
            
            // Masukkan kolom yg dibutuhkan
            $builder->select('s.id, s.nis, s.nama_lengkap as name, nk.harian, nk.uts, nk.uas, nk.proyek, nk.catatan, nk.aspek_kedisiplinan, nk.aspek_tanggung_jawab, nk.aspek_kerjasama, nk.aspek_kejujuran'); 
            
            $builder->join('nilai_komponen nk', "nk.siswa_id = s.id AND nk.mapel_id = $mapel_id AND nk.tahun_ajaran = '$tahun_ajaran' AND nk.semester = '$semester'", 'left');
            $builder->where('s.rombel_id', $rombel_id);
            
            if ($db->fieldExists('status_siswa', 'siswa')) {
                $builder->where('s.status_siswa', 'Aktif');
            }
            
            $builder->orderBy('s.nama_lengkap', 'ASC');
            $siswas = $builder->get()->getResultArray();

            foreach ($siswas as &$siswa) {
                $harian = (int)($siswa['harian'] ?? 0);
                $uts    = (int)($siswa['uts'] ?? 0);
                $uas    = (int)($siswa['uas'] ?? 0);
                $proyek = (int)($siswa['proyek'] ?? 0);

                $siswa['harian'] = $harian;
                $siswa['uts']    = $uts;
                $siswa['uas']    = $uas;
                $siswa['proyek'] = $proyek;
                $siswa['catatan']= $siswa['catatan'] ?? "-";
                
                $siswa['aspek_kedisiplinan']   = (int)($siswa['aspek_kedisiplinan'] ?? 0);
                $siswa['aspek_tanggung_jawab'] = (int)($siswa['aspek_tanggung_jawab'] ?? 0);
                $siswa['aspek_kerjasama']      = (int)($siswa['aspek_kerjasama'] ?? 0);
                $siswa['aspek_kejujuran']      = (int)($siswa['aspek_kejujuran'] ?? 0);

                $filledCount = 0;
                $sumNilai = 0;
                
                if ($harian > 0) { $filledCount++; $sumNilai += $harian; }
                if ($uts > 0) { $filledCount++; $sumNilai += $uts; }
                if ($uas > 0) { $filledCount++; $sumNilai += $uas; }
                if ($proyek > 0) { $filledCount++; $sumNilai += $proyek; }

                if ($filledCount == 0) {
                    $siswa['status'] = "belum";
                } elseif ($filledCount == 4) {
                    $siswa['status'] = "lengkap";
                } else {
                    $siswa['status'] = "proses";
                }
                
                // Set nilai rata-rata dari backend agar JS lebih ringan
                $siswa['rata_rata'] = $filledCount > 0 ? round($sumNilai / $filledCount, 1) : 0;
            }
        }

        return $this->response->setJSON([
            'status' => 'success',
            'data' => $siswas
        ]);
    }

    public function saveDataKomponen()
    {
        if (!$this->request->isAJAX()) return $this->response->setStatusCode(404);

        $db = \Config\Database::connect();
        
        $data = [
            'siswa_id'             => $this->request->getPost('siswa_id'),
            // 'guru_id' biarkan ditangani oleh relasi mapel, atau simpan jika tabel mensyaratkan
            'mapel_id'             => $this->request->getPost('mapel_id'),
            'rombel_id'            => $this->request->getPost('rombel_id'),
            'harian'               => $this->request->getPost('harian') ?: 0,
            'uts'                  => $this->request->getPost('uts') ?: 0,
            'uas'                  => $this->request->getPost('uas') ?: 0,
            'proyek'               => $this->request->getPost('proyek') ?: 0,
            'catatan'              => $this->request->getPost('catatan'),
            'aspek_kedisiplinan'   => $this->request->getPost('aspek_kedisiplinan') ?: 0,
            'aspek_tanggung_jawab' => $this->request->getPost('aspek_tanggung_jawab') ?: 0,
            'aspek_kerjasama'      => $this->request->getPost('aspek_kerjasama') ?: 0,
            'aspek_kejujuran'      => $this->request->getPost('aspek_kejujuran') ?: 0,
            'tahun_ajaran'         => '2026/2027', // Sesuaikan
            'semester'             => 'Genap'      
        ];

        $builder = $db->table('nilai_komponen');
        
        $existing = $builder->where([
            'siswa_id'     => $data['siswa_id'],
            'mapel_id'     => $data['mapel_id'],
            'tahun_ajaran' => $data['tahun_ajaran'],
            'semester'     => $data['semester'] 
        ])->get()->getRow();

        if ($existing) {
            $builder->where('id', $existing->id)->update($data);
        } else {
            $builder->insert($data);
        }

        return $this->response->setJSON(['status' => 'success', 'message' => 'Data berhasil diamankan!']);
    }
}