<?php
namespace App\Controllers\Admin;

use App\Controllers\AdminBaseController;
use CodeIgniter\API\ResponseTrait; // Tambahan untuk response JSON

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
        // 1. Ambil data Bobot & Susun Array-nya agar mudah dipanggil di View
        $queryBobot = $this->db->table('setting_bobot_nilai')->get()->getResultArray();
        $bobotData = [];
        foreach ($queryBobot as $row) {
            $bobotData[$row['kategori']][$row['sub_kategori']] = $row['bobot'];
        }

        // 2. Ambil data Aturan Predikat
        $aturanData = $this->db->table('setting_aturan_nilai')
                               ->orderBy('nilai_max', 'DESC') // Urut dari nilai tertinggi
                               ->get()->getResultArray();

        $data = [
            'user' => 'Admin',
            'navigations' => $this->getSidebarMenu(),
            'color' => $this->getColor(),
            // Kirim data ke View
            'bobot' => $bobotData, 
            'list_aturan' => $aturanData 
        ];
        
        return view('admin/aturan-nilai', $data); 
    }

    // --- FUNGSI BARU: Tambah Aturan Predikat (Modal) ---
    public function storeAturan()
    {
        if (!$this->request->isAJAX()) return $this->fail('Invalid');

        $data = [
            'predikat' => $this->request->getPost('predikat'),
            'deskripsi_predikat' => $this->request->getPost('deskripsi_predikat'),
            'nilai_min' => $this->request->getPost('nilai_min'),
            'nilai_max' => $this->request->getPost('nilai_max'),
            'deskripsi_kompetensi' => $this->request->getPost('deskripsi_kompetensi'),
            'warna_badge' => $this->request->getPost('warna'),
            'is_active' => $this->request->getPost('status') == 'on' ? 1 : 0
        ];

        $this->db->table('setting_aturan_nilai')->insert($data);

        return $this->respond(['status' => 'success', 'message' => 'Aturan baru ditambahkan']);
    }

    // 1. UPDATE: Tambahkan Logika Simpan Bobot + Catat Riwayat
    public function updateBobot()
    {
        if (!$this->request->isAJAX()) return $this->fail('Invalid Request');

        $input = $this->request->getJSON();
        
        $this->db->transStart();
        
        // Update Database
        foreach ($input->bobot as $kategori => $items) {
            foreach ($items as $sub => $nilai) {
                $this->db->table('setting_bobot_nilai')
                         ->where('kategori', $kategori)
                         ->where('sub_kategori', $sub)
                         ->update(['bobot' => $nilai]);
            }
        }

        // Catat Riwayat
        $this->catatRiwayat('Update Bobot', 'Melakukan perubahan komposisi bobot penilaian.');

        $this->db->transComplete();

        if ($this->db->transStatus() === FALSE) {
            return $this->fail('Gagal menyimpan database');
        }

        return $this->respond(['status' => 'success', 'message' => 'Bobot berhasil diperbarui!']);
    }

    // 2. BARU: Fitur Reset ke Default
    public function resetBobot()
    {
        if (!$this->request->isAJAX()) return $this->fail('Invalid Request');

        // Definisi Nilai Default (Standar Kurikulum)
        $defaults = [
            'akademik' => [
                'pengetahuan' => 20, 'keterampilan' => 15, 'pts' => 10, 'pas' => 15
            ],
            'karakter' => [
                'akhlak' => 7, 'kedisiplinan' => 7, 'tanggung_jawab' => 6
            ],
            'keislaman' => [
                'tahfidz' => 10, 'ibadah' => 5, 'akhlak_islami' => 5
            ]
        ];

        $this->db->transStart();
        
        // Loop untuk update ke nilai default
        foreach ($defaults as $kategori => $items) {
            foreach ($items as $sub => $nilai) {
                $this->db->table('setting_bobot_nilai')
                         ->where('kategori', $kategori)
                         ->where('sub_kategori', $sub)
                         ->update(['bobot' => $nilai]);
            }
        }

        // Catat Riwayat
        $this->catatRiwayat('Reset Default', 'Mengembalikan pengaturan bobot ke standar awal.');

        $this->db->transComplete();

        return $this->respond(['status' => 'success', 'message' => 'Pengaturan dikembalikan ke default']);
    }

    // 3. BARU: Ambil Data Riwayat (Untuk Modal)
    public function getRiwayat()
    {
        if (!$this->request->isAJAX()) return $this->fail('Invalid Request');

        $data = $this->db->table('riwayat_perubahan_nilai')
                         ->orderBy('created_at', 'DESC')
                         ->limit(10) // Ambil 10 terakhir
                         ->get()->getResultArray();
        
        return $this->respond($data);
    }

    // Helper Private untuk catat log
    private function catatRiwayat($aksi, $detail)
    {
        $this->db->table('riwayat_perubahan_nilai')->insert([
            'user' => session()->get('nama') ?? 'Admin', // Sesuaikan dengan session user Anda
            'aksi' => $aksi,
            'detail' => $detail
        ]);
    }
}