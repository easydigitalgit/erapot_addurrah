<?php

namespace App\Controllers\Admin;

use App\Controllers\AdminBaseController;
use CodeIgniter\API\ResponseTrait;

class AturanNilaiController extends AdminBaseController
{
    use ResponseTrait;
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    public function index(): string
    {
        $queryBobot = $this->db->table('setting_bobot_nilai')->get()->getResultArray();
        $bobotData = [];
        foreach ($queryBobot as $row) {
            $bobotData[$row['kategori']][$row['sub_kategori']] = $row['bobot'];
        }

        // AUTO-FALLBACK: Jika database masih kosong atau pakai data lama, set ke default baru
        $bobotData['tengah_semester']['nh']  = $bobotData['tengah_semester']['nh'] ?? 35;
        $bobotData['tengah_semester']['uh']  = $bobotData['tengah_semester']['uh'] ?? 35;
        $bobotData['tengah_semester']['sts'] = $bobotData['tengah_semester']['sts'] ?? 30;

        $bobotData['akhir_semester']['nh']   = $bobotData['akhir_semester']['nh'] ?? 30;
        $bobotData['akhir_semester']['uh']   = $bobotData['akhir_semester']['uh'] ?? 30;
        $bobotData['akhir_semester']['sts']  = $bobotData['akhir_semester']['sts'] ?? 15;
        $bobotData['akhir_semester']['sas']  = $bobotData['akhir_semester']['sas'] ?? 25;

        $aturanData = $this->db->table('setting_aturan_nilai')
            ->orderBy('nilai_max', 'DESC')
            ->get()->getResultArray();

        $data = [
            'user'        => 'Admin',
            'navigations' => $this->getSidebarMenu(),
            'color'       => $this->getColor(),
            'bobot'       => $bobotData,
            'list_aturan' => $aturanData
        ];

        return view('admin/aturan-nilai', $data);
    }

    public function storeAturan()
    {
        if (!$this->request->isAJAX()) return $this->fail('Invalid');

        $data = [
            'predikat'             => $this->request->getPost('predikat'),
            'deskripsi_predikat'   => $this->request->getPost('deskripsi_predikat'),
            'nilai_min'            => $this->request->getPost('nilai_min'),
            'nilai_max'            => $this->request->getPost('nilai_max'),
            'deskripsi_kompetensi' => $this->request->getPost('deskripsi_kompetensi'),
            'warna_badge'          => $this->request->getPost('warna'),
            'is_active'            => $this->request->getPost('status') == 'on' ? 1 : 0
        ];

        $this->db->table('setting_aturan_nilai')->insert($data);
        return $this->respond(['status' => 'success', 'message' => 'Aturan baru ditambahkan']);
    }

    public function updateBobot()
    {
        if (!$this->request->isAJAX()) return $this->fail('Invalid Request');

        $input = $this->request->getJSON();

        $this->db->transStart();

        // Hapus data lama agar tidak menumpuk dengan format lama (akademik/karakter/dkk)
        $this->db->table('setting_bobot_nilai')
            ->whereIn('kategori', ['tengah_semester', 'akhir_semester'])
            ->delete();

        $insertData = [];
        foreach ($input->bobot as $kategori => $items) {
            foreach ($items as $sub => $nilai) {
                $insertData[] = [
                    'kategori'     => $kategori,
                    'sub_kategori' => $sub,
                    'bobot'        => $nilai
                ];
            }
        }

        if (!empty($insertData)) {
            $this->db->table('setting_bobot_nilai')->insertBatch($insertData);
        }

        $this->catatRiwayat('Update Bobot', 'Memperbarui formula rapor Tengah & Akhir Semester.');
        $this->db->transComplete();

        if ($this->db->transStatus() === FALSE) {
            return $this->fail('Gagal menyimpan database');
        }

        return $this->respond(['status' => 'success', 'message' => 'Bobot berhasil diperbarui!']);
    }

    public function resetBobot()
    {
        if (!$this->request->isAJAX()) return $this->fail('Invalid Request');

        $defaults = [
            'tengah_semester' => ['nh' => 35, 'uh' => 35, 'sts' => 30],
            'akhir_semester'  => ['nh' => 30, 'uh' => 30, 'sts' => 15, 'sas' => 25]
        ];

        $this->db->transStart();

        $this->db->table('setting_bobot_nilai')
            ->whereIn('kategori', ['tengah_semester', 'akhir_semester'])
            ->delete();

        $insertData = [];
        foreach ($defaults as $kategori => $items) {
            foreach ($items as $sub => $nilai) {
                $insertData[] = [
                    'kategori'     => $kategori,
                    'sub_kategori' => $sub,
                    'bobot'        => $nilai
                ];
            }
        }
        $this->db->table('setting_bobot_nilai')->insertBatch($insertData);

        $this->catatRiwayat('Reset Default', 'Mengembalikan pengaturan bobot ke standar awal (100%).');
        $this->db->transComplete();

        return $this->respond(['status' => 'success', 'message' => 'Pengaturan dikembalikan ke default']);
    }

    public function getRiwayat()
    {
        if (!$this->request->isAJAX()) return $this->fail('Invalid Request');

        $data = $this->db->table('riwayat_perubahan_nilai')
            ->orderBy('created_at', 'DESC')
            ->limit(10)
            ->get()->getResultArray();

        return $this->respond($data);
    }

    private function catatRiwayat($aksi, $detail)
    {
        $this->db->table('riwayat_perubahan_nilai')->insert([
            'user'   => session()->get('nama_lengkap') ?? session()->get('nama') ?? 'Admin',
            'aksi'   => $aksi,
            'detail' => $detail
        ]);
    }

    public function deleteAturan($id)
    {
        if (!$this->request->isAJAX()) return $this->fail('Invalid Request');

        try {
            $this->db->table('setting_aturan_nilai')->where('id', $id)->delete();
            $this->catatRiwayat('Hapus Aturan', "Menghapus aturan predikat penilaian (ID: $id).");
            return $this->respond(['status' => 'success', 'message' => 'Aturan penilaian berhasil dihapus.']);
        } catch (\Exception $e) {
            return $this->fail('Gagal menghapus data: ' . $e->getMessage());
        }
    }
}
