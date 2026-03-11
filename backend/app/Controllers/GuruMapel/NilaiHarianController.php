<?php
namespace App\Controllers\GuruMapel;

use App\Controllers\GuruMapelBaseController;

class NilaiHarianController extends GuruMapelBaseController
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

        // 2. Ambil semua penugasan kelas untuk guru ini (Untuk Dropdown Filter)
        $builder = $db->table('guru_mapel gm');
        $builder->select('gm.mapel_id, gm.rombel_id, m.nama_mapel, r.nama_rombel as nama_kelas, r.tingkat, u.nama_lengkap as wali_kelas');
        $builder->join('mata_pelajaran m', 'm.id = gm.mapel_id', 'left');
        $builder->join('rombel r', 'r.id = gm.rombel_id', 'left');
        $builder->join('guru_tendik u', 'u.id = r.wali_kelas_id', 'left');
        $builder->where('gm.guru_id', $guruId); // FIX: Gunakan guru_id
        
        $allPenugasan = $builder->get()->getResultArray();

        // 3. Tentukan Kelas & Mapel yang Sedang Dibuka (Dari Parameter URL GET)
        $activeRombelId = $this->request->getGet('rombel') ?? ($allPenugasan[0]['rombel_id'] ?? 0);
        $activeMapelId  = $this->request->getGet('mapel')  ?? ($allPenugasan[0]['mapel_id'] ?? 0);

        // Cari detail spesifik dari penugasan yang aktif
        $assignment = array_filter($allPenugasan, function($p) use ($activeRombelId, $activeMapelId) {
            return $p['rombel_id'] == $activeRombelId && $p['mapel_id'] == $activeMapelId;
        });
        $assignment = reset($assignment);

        // 4. Hitung jumlah siswa di kelas tersebut
        $jumlah_siswa = 0;
        if ($activeRombelId > 0) {
            $jumlah_siswa = $db->table('siswa')
                               ->where('rombel_id', $activeRombelId)
                               ->where('status_siswa', 'Aktif')
                               ->countAllResults();
        }

        // 5. Variabel Data yang dikirim ke View
        $data = [
            'user'        => session()->get('nama_lengkap') ?? 'Guru Mapel',
            'navigations' => $this->getSidebarMenu(),
            'color'       => $this->getColor(),
            'allRombel'   => $allPenugasan, // Data untuk Dropdown Switch Kelas
            'info'        => [
                'mapel_id'   => $activeMapelId,
                'rombel_id'  => $activeRombelId,
                'mapel_nama' => $assignment['nama_mapel'] ?? 'Belum Pilih Mapel',
                'kelas_nama' => ($assignment['tingkat'] ?? '') . ' ' . ($assignment['nama_kelas'] ?? 'Belum Pilih Kelas'),
                'wali_kelas' => $assignment['wali_kelas'] ?? 'Belum Diset',
                'jml_siswa'  => $jumlah_siswa
            ]
        ];
        
        return view('GuruMapel/nilai-harian', $data); 
    }

    public function getStudents()
    {
        $jenis = $this->request->getGet('jenis');
        $pertemuan = $this->request->getGet('pertemuan');
        
        // FIX: Ambil dari parameter GET, bukan Hardcode!
        $kelas_id = $this->request->getGet('rombel_id'); 
        $mapel_id = $this->request->getGet('mapel_id');  

        if (!$kelas_id || !$mapel_id) {
            return $this->response->setJSON(['status' => 400, 'message' => 'Kelas/Mapel tidak valid']);
        }

        $siswaModel = new \App\Models\GuruMapel\SiswaModel();
        $nilaiModel = new \App\Models\GuruMapel\NilaiHarianModel();

        // Filter Tahun Ajaran & Semester
        $tahun_ajaran = '2026/2027'; // Pastikan sesuai dengan database Anda
        $semester = $this->request->getGet('semester');

        $dataSiswa = $siswaModel->getSiswaByKelas($kelas_id);

        $dataNilaiTersimpan = $nilaiModel->where([
            'mapel_id'        => $mapel_id,
            'rombel_id'       => $kelas_id,
            'jenis_penilaian' => $jenis,
            'pertemuan'       => $pertemuan,
            'tahun_ajaran'    => $tahun_ajaran,
            'semester'        => $semester
        ])->findAll();

        $kamusNilai = [];
        foreach ($dataNilaiTersimpan as $nilai) {
            $kamusNilai[$nilai['siswa_id']] = $nilai;
        }

        $formattedStudents = [];
        foreach ($dataSiswa as $siswa) {
            $siswaId = $siswa['id'];
            $nilaiAngka = isset($kamusNilai[$siswaId]) ? $kamusNilai[$siswaId]['nilai_angka'] : "";
            $catatan    = isset($kamusNilai[$siswaId]) ? $kamusNilai[$siswaId]['catatan'] : "";
            $statusSimpan = isset($kamusNilai[$siswaId]) ? $kamusNilai[$siswaId]['status_simpan'] : "";

            $formattedStudents[] = [
                "id"   => $siswaId,
                "name" => $siswa['nama_lengkap'],
                "nis"  => $siswa['nis'],
                "nilai_tersimpan"      => $nilaiAngka,
                "keterangan_tersimpan" => $catatan,
                "status_simpan"        => $statusSimpan
            ];
        }

        return $this->response->setJSON([
            'status'  => 200,
            'message' => 'Data siswa dan nilai berhasil diambil',
            'data'    => $formattedStudents
        ]);
    }

    public function saveNilai()
    {
        $json = $this->request->getJSON();

        if (!$json || empty($json->nilaiData)) {
            return $this->response->setJSON(['status' => 400, 'message' => 'Data nilai kosong!']);
        }

        $db = \Config\Database::connect();
        $nilaiModel = new \App\Models\GuruMapel\NilaiHarianModel();
        
        $userId = session()->get('id');
        $dataGuru = $db->table('guru_tendik')->select('id')->where('user_id', $userId)->get()->getRowArray();
        $guru_id = $dataGuru ? $dataGuru['id'] : 0;

        // FIX: Ambil dari payload JSON, BUKAN HARDCODE 1!
        $mapel_id = $json->mapel_id ?? 0;   
        $rombel_id = $json->rombel_id ?? 0; 
        
        $tahun_ajaran = '2026/2027'; // Sesuaikan
        $semester = $json->semester;
        $status_simpan = $json->status_simpan ?? 'draft'; 

        $berhasilDisimpan = 0;

        foreach ($json->nilaiData as $siswa_id => $data) {
            if ($data->nilai !== "") {
                $payload = [
                    'siswa_id'          => $siswa_id,
                    'guru_id'           => $guru_id,
                    'mapel_id'          => $mapel_id,
                    'rombel_id'         => $rombel_id,
                    'nilai_angka'       => $data->nilai,
                    'predikat'          => $data->predikat,
                    'catatan'           => $data->keterangan,
                    'tahun_ajaran'      => $tahun_ajaran,
                    'semester'          => $semester,
                    'jenis_penilaian'   => $json->jenis_penilaian,
                    'pertemuan'         => $json->pertemuan,
                    'tanggal_penilaian' => $json->tanggal_penilaian,
                    'status_simpan'     => $status_simpan
                ];

                $cekDataSisa = $nilaiModel->where([
                    'siswa_id'        => $siswa_id,
                    'mapel_id'        => $mapel_id,
                    'jenis_penilaian' => $json->jenis_penilaian,
                    'pertemuan'       => $json->pertemuan
                ])->first();

                if ($cekDataSisa) {
                    $nilaiModel->update($cekDataSisa['id'], $payload);
                } else {
                    $nilaiModel->insert($payload);
                }
                
                $berhasilDisimpan++;
            }
        }

        return $this->response->setJSON([
            'status' => 200, 
            'message' => "Berhasil memproses $berhasilDisimpan data nilai!"
        ]);
    }   
}