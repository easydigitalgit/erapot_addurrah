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
        $this->db = \Config\Database::connect(); // Buka koneksi database
    }

    public function index(): string
    {
        // 1. Ambil data kelas
        $rombels = $this->db->table('rombel')
                            ->select('id, nama_rombel')
                            ->orderBy('nama_rombel', 'ASC')
                            ->get()
                            ->getResultArray();

        // 2. Ambil data Surah dari database (BARU)
        // Catatan: Sesuaikan 'surah' dan 'nama_surah' dengan nama tabel & kolom asli di database Mas Zaidan.
        $surahs = $this->db->table('ref_surah')
                           ->select('nama_surah') 
                           ->get()
                           ->getResultArray();

        $data = [
            'user'        => session()->get('username') ?? 'Guru Tahfidz',
            'navigations' => $this->getSidebarMenu(),
            'color'       => $this->getColor(),
            'rombels'     => $rombels,
            'surahs'      => $surahs // <-- Kirim data surah ke View
        ];

        return view('tahfidz/setoran/index', $data);
    }

    // 2. Fungsi API AJAX untuk mengambil data siswa berdasarkan Kelas
    public function getSiswaByKelas()
    {
        $rombel_id = $this->request->getGet('rombel_id');

        if (!$rombel_id) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Pilih kelas terlebih dahulu.']);
        }

        // Ambil data siswa yang berada di kelas tersebut
        $siswa = $this->db->table('siswa')
                          ->select('id, nama_lengkap, nis')
                          ->where('rombel_id', $rombel_id)
                          ->orderBy('nama_lengkap', 'ASC')
                          ->get()
                          ->getResultArray();

        return $this->response->setJSON(['status' => 'success', 'data' => $siswa]);
    }

    // 3. Fungsi AJAX untuk Menyimpan Data Setoran (Mutaba'ah) Masal
    public function save()
    {
        if ($this->request->isAJAX()) {
            // Tangkap semua data dari form
            $tanggal       = $this->request->getPost('tanggal');
            $siswa_id      = $this->request->getPost('siswa_id');
            $jenis_setoran = $this->request->getPost('jenis_setoran');
            $surah         = $this->request->getPost('surah');
            $ayat          = $this->request->getPost('ayat');
            $predikat      = $this->request->getPost('predikat');
            $catatan       = $this->request->getPost('catatan');

            // Ambil ID Guru yang sedang login (Sesuaikan nama session ID Mas Zaidan jika beda, misalnya 'id_user' atau 'user_id')
            $guru_id = session()->get('id_user') ?? session()->get('id') ?? 1; 

            $dataToSave = [];

            // Looping sebanyak jumlah siswa yang tampil
            for ($i = 0; $i < count($siswa_id); $i++) {
                // KUNCI PENTING: Kita hanya menyimpan data santri yang kolom "Surah"-nya diisi.
                // Jika kosong (guru tidak mengisi), maka lewati (jangan disimpan).
                if (!empty(trim($surah[$i]))) {
                    $dataToSave[] = [
                        'siswa_id'      => $siswa_id[$i],
                        'guru_id'       => $guru_id,
                        'tanggal'       => $tanggal,
                        'jenis_setoran' => $jenis_setoran[$i],
                        'surah'         => $surah[$i],
                        'ayat'          => $ayat[$i],
                        'predikat'      => $predikat[$i],
                        'catatan'       => $catatan[$i] ?? null,
                    ];
                }
            }

            // Jika ada data yang siap disimpan
            if (!empty($dataToSave)) {
                $this->tahfidzModel->insertBatch($dataToSave);
                return $this->response->setJSON([
                    'status'  => 'success', 
                    'message' => count($dataToSave) . ' data setoran santri berhasil disimpan!'
                ]);
            } else {
                return $this->response->setJSON([
                    'status'  => 'warning', 
                    'message' => 'Tidak ada setoran yang disimpan. Pastikan Anda mengisi kolom "Surah" pada minimal 1 santri.'
                ]);
            }
        }
        
        return $this->response->setJSON(['status' => 'error', 'message' => 'Akses tidak sah.']);
    }
}