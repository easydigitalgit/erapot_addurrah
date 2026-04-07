<?php

namespace App\Controllers\GuruMapel;

use App\Controllers\GuruMapelBaseController;
use App\Models\GuruMapel\NilaiSumatifModel;

class NilaiSumatifController extends GuruMapelBaseController
{
    protected $nilaiSumatifModel;
    protected $db;

    public function __construct()
    {
        $this->nilaiSumatifModel = new NilaiSumatifModel();
        $this->db = \Config\Database::connect();
    }

    public function index(): string
    {
        $userId = session()->get('id');
        $dataGuru = $this->db->table('guru_tendik')->select('id')->where('user_id', $userId)->get()->getRowArray();
        $guruId = $dataGuru ? $dataGuru['id'] : 0;

        // 0. AMBIL SEMUA TAHUN AJARAN & CARI YANG AKTIF
        $tahunAjaranList = $this->db->table('tahun_ajaran')->orderBy('id', 'DESC')->get()->getResultArray();
        $activeTaId = 0;
        $activeSemester = 'Ganjil'; // Tambahkan penangkap semester
        foreach ($tahunAjaranList as $ta) {
            if ($ta['status'] === 'Aktif') {
                $activeTaId = $ta['id'];
                $activeSemester = $ta['semester'];
                break;
            }
        }

        // --- PERBAIKAN: Tambahkan m.kkm di dalam select ---
        $builder = $this->db->table('guru_mapel gm');
        $builder->select('gm.mapel_id, gm.rombel_id, m.nama_mapel, m.kkm, r.nama_rombel as kelas_nama, r.tingkat');
        $builder->join('mata_pelajaran m', 'm.id = gm.mapel_id', 'left');
        $builder->join('rombel r', 'r.id = gm.rombel_id', 'left');
        $builder->where(['gm.guru_id' => $guruId, 'r.id_tahun_ajaran' => $activeTaId]);
        $allPenugasan = $builder->get()->getResultArray();

        $activeRombelId = $this->request->getGet('rombel') ?? ($allPenugasan[0]['rombel_id'] ?? 0);
        $activeMapelId  = $this->request->getGet('mapel')  ?? ($allPenugasan[0]['mapel_id'] ?? 0);

        $assignment = array_filter($allPenugasan, function ($p) use ($activeRombelId, $activeMapelId) {
            return $p['rombel_id'] == $activeRombelId && $p['mapel_id'] == $activeMapelId;
        });
        $assignment = reset($assignment);

        $jumlah_siswa = 0;
        if ($activeRombelId > 0 && $activeTaId > 0) {
            // MENGGUNAKAN MESIN WAKTU
            $jumlah_siswa = $this->db->table('anggota_rombel ar')
                ->join('siswa s', 's.id = ar.siswa_id')
                ->where('ar.rombel_id', $activeRombelId)
                ->where('ar.tahun_ajaran_id', $activeTaId)
                ->where('ar.semester', $activeSemester)
                ->where('s.status_siswa', 'Aktif')
                ->countAllResults();
        }

        // --- PERBAIKAN: Ambil data dari tabel setting_bobot_nilai ---
        $bobotSettings = $this->db->table('setting_bobot_nilai')->get()->getResultArray();

        $data = [
            'user'         => session()->get('nama_lengkap') ?? 'Guru Mapel',
            'navigations'  => $this->getSidebarMenu(),
            'color'        => $this->getColor(),
            'allRombel'    => $allPenugasan,
            'tahun_ajaran' => $tahunAjaranList, 
            'active_ta_id' => $activeTaId,
            'bobot_list'   => $bobotSettings, // Kirim data bobot ke view
            'info'         => [
                'mapel_id'   => $activeMapelId,
                'rombel_id'  => $activeRombelId,
                'mapel_nama' => $assignment['nama_mapel'] ?? 'Belum Pilih Mapel',
                'kkm'        => $assignment['kkm'] ?? 75, // Tangkap nilai KKM, default 75
                'kelas_nama' => ($assignment['tingkat'] ?? '') . ' ' . ($assignment['kelas_nama'] ?? 'Belum Pilih Kelas'),
                'jml_siswa'  => $jumlah_siswa
            ]
        ];

        return view('GuruMapel/nilai-sumatif', $data);
    }

    public function getNilaiSiswa()
    {
        if (!$this->request->isAJAX()) return $this->response->setStatusCode(404);

        $jenis_sumatif = $this->request->getGet('jenis');
        $mapel_id      = (int) $this->request->getGet('mapel_id');
        $rombel_id     = (int) $this->request->getGet('rombel_id');
        $ta_id         = (int) $this->request->getGet('ta_id'); // Tangkap Parameter TA

        if (!$rombel_id || !$mapel_id || !$ta_id) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Parameter tidak valid']);
        }

        try {
            // Ambil semester berdasarkan ta_id
            $taData = $this->db->table('tahun_ajaran')->where('id', $ta_id)->get()->getRowArray();
            $semester = $taData ? $taData['semester'] : 'Ganjil';

            // MENGGUNAKAN MESIN WAKTU (anggota_rombel)
            $builder = $this->db->table('anggota_rombel ar');
            $builder->select('s.id as siswa_id, s.nama_lengkap as nama, s.nis, ns.id as nilai_id, ns.nilai, ns.deskripsi, ns.status');
            $builder->join('siswa s', 's.id = ar.siswa_id');

            // Tambahkan tahun_ajaran_id ke dalam Rule Join
            $joinCondition = "ns.siswa_id = s.id 
                              AND ns.mapel_id = {$mapel_id} 
                              AND ns.jenis_sumatif = '{$jenis_sumatif}'
                              AND ns.tahun_ajaran_id = {$ta_id}";

            $builder->join('nilai_sumatif ns', $joinCondition, 'left');
            
            // Filter Mesin Waktu
            $builder->where('ar.rombel_id', $rombel_id);
            $builder->where('ar.tahun_ajaran_id', $ta_id);
            $builder->where('ar.semester', $semester);
            $builder->where('s.status_siswa', 'Aktif');
            $builder->orderBy('s.nama_lengkap', 'ASC');

            return $this->response->setJSON([
                'status' => 'success',
                'data'   => $builder->get()->getResultArray()
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'DB Error: ' . $e->getMessage()]);
        }
    }

    public function saveBulk()
    {
        if (!$this->request->isAJAX()) return $this->response->setStatusCode(404);

        $json = $this->request->getJSON();
        if (!$json || empty($json->data_nilai)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Data nilai kosong!']);
        }

        $jenis_sumatif = $json->jenis_sumatif;
        $mapel_id      = $json->mapel_id;
        $ta_id         = $json->ta_id;
        $dataNilai     = $json->data_nilai;

        $userId   = session()->get('id');
        $dataGuru = $this->db->table('guru_tendik')->select('id')->where('user_id', $userId)->get()->getRowArray();
        $guruId   = $dataGuru ? $dataGuru['id'] : 0;

        $this->db->transStart();

        foreach ($dataNilai as $item) {
            $data = [
                'siswa_id'        => $item->siswa_id,
                //'guru_id'         => $guruId,
                'mapel_id'        => $mapel_id,
                'tahun_ajaran_id' => $ta_id,
                'jenis_sumatif'   => $jenis_sumatif,
                'nilai'           => $item->nilai,
                'deskripsi'       => $item->deskripsi,
                'status'          => 'draft'
            ];

            // ==============================================================
            // FIX MUTLAK: LOGIKA UPSERT (UPDATE OR INSERT) ANTI DUPLIKAT
            // ==============================================================
            $existing = $this->nilaiSumatifModel
                ->where('siswa_id', $item->siswa_id)
                ->where('mapel_id', $mapel_id)
                ->where('tahun_ajaran_id', $ta_id)
                ->where('jenis_sumatif', $jenis_sumatif)
                ->first();

            if ($existing) {
                // Jika sudah pernah tersimpan, Update nilainya
                $this->nilaiSumatifModel->update($existing['id'], $data);
            } else {
                // Jika baru pertama kali diketik, Insert baru
                $this->nilaiSumatifModel->insert($data);
            }
        }

        $this->db->transComplete();

        if ($this->db->transStatus() === FALSE) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Gagal menyimpan data ke database.']);
        }
        return $this->response->setJSON(['status' => 'success', 'message' => 'Draft nilai berhasil disimpan.']);
    }

    public function updateStatus()
    {
        if (!$this->request->isAJAX()) return $this->response->setStatusCode(404);

        $json = $this->request->getJSON();
        if (!$json || !isset($json->jenis_sumatif) || !isset($json->status) || !isset($json->mapel_id) || !isset($json->ta_id)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Data parameter tidak lengkap.']);
        }

        try {
            // Ambil semester berdasarkan ta_id
            $taData = $this->db->table('tahun_ajaran')->where('id', $json->ta_id)->get()->getRowArray();
            $semester = $taData ? $taData['semester'] : 'Ganjil';

            // MENGGUNAKAN MESIN WAKTU
            $siswaList = $this->db->table('anggota_rombel ar')
                ->join('siswa s', 's.id = ar.siswa_id')
                ->where('ar.rombel_id', $json->rombel_id)
                ->where('ar.tahun_ajaran_id', $json->ta_id)
                ->where('ar.semester', $semester)
                ->where('s.status_siswa', 'Aktif')
                ->select('s.id')->get()->getResultArray();

            $siswaIds = array_column($siswaList, 'id');

            if (empty($siswaIds)) return $this->response->setJSON(['status' => 'error', 'message' => 'Tidak ada siswa.']);

            // Pastikan update hanya terjadi pada Tahun Ajaran yang sesuai
            $this->db->table('nilai_sumatif')
                ->where('mapel_id', $json->mapel_id)
                ->where('jenis_sumatif', $json->jenis_sumatif)
                ->where('tahun_ajaran_id', $json->ta_id) // Kunci Spesifik Tahun Ajaran
                ->whereIn('siswa_id', $siswaIds)
                ->update(['status' => $json->status]);

            return $this->response->setJSON(['status'  => 'success', 'message' => 'Status diupdate!']);
        } catch (\Exception $e) {
            return $this->response->setJSON(['status'  => 'error', 'message' => 'DB Error: ' . $e->getMessage()]);
        }
    }
}
