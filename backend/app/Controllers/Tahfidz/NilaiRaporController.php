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

    public function index(): string
    {
        // Ambil data kelas untuk filter
        $rombels = $this->db->table('rombel')
                            ->select('id, nama_rombel')
                            ->orderBy('nama_rombel', 'ASC')
                            ->get()
                            ->getResultArray();

        $data = [
            'user'        => session()->get('username') ?? 'Guru Tahfidz',
            'navigations' => $this->getSidebarMenu(),
            'color'       => $this->getColor(),
            'rombels'     => $rombels
        ];

        return view('tahfidz/nilai_rapor/index', $data);
    }

    // Fungsi AJAX mengambil data santri beserta nilai rapornya (jika sudah pernah diinput)
    // Fungsi AJAX mengambil data santri beserta nilai rapornya
    public function getSiswa()
    {
        $rombel_id = $this->request->getGet('rombel_id');
        $semester  = $this->request->getGet('semester');

        if (!$rombel_id) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Pilih kelas.']);
        }

        // Cari Tahun Ajaran Aktif
        $ta = $this->db->table('tahun_ajaran')->where('status', 'Aktif')->get()->getRowArray();
        $ta_id = $ta ? $ta['id'] : 0;

        // Ambil data santri
        $siswa = $this->db->table('siswa')
                          ->select('id, nama_lengkap, nis')
                          ->where('rombel_id', $rombel_id)
                          ->orderBy('nama_lengkap', 'ASC')
                          ->get()
                          ->getResultArray();

        $dataSiswa = [];
        foreach ($siswa as $s) {
            // Cek apakah santri ini sudah punya nilai rapor
            $nilai = $this->db->table('nilai_tahfidz')
                              ->where('siswa_id', $s['id'])
                              ->where('tahun_ajaran_id', $ta_id)
                              ->where('semester', $semester)
                              ->get()
                              ->getRowArray();

            // BARU: Cek hafalan terakhir anak ini dari tabel setoran
            $hafalan_terakhir = $this->db->table('setoran_tahfidz')
                                         ->where('siswa_id', $s['id'])
                                         ->orderBy('created_at', 'DESC')
                                         ->limit(1)
                                         ->get()
                                         ->getRowArray();

            $dataSiswa[] = [
                'id'             => $s['id'],
                'nama_lengkap'   => $s['nama_lengkap'],
                'nis'            => $s['nis'],
                'predikat'       => $nilai ? $nilai['predikat'] : 'Baik',
                'deskripsi'      => $nilai ? $nilai['deskripsi'] : '',
                'surah_terakhir' => $hafalan_terakhir ? $hafalan_terakhir['surah'] : '',
                'ayat_terakhir'  => $hafalan_terakhir ? $hafalan_terakhir['ayat'] : ''
            ];
        }

        return $this->response->setJSON(['status' => 'success', 'data' => $dataSiswa]);
    }
    // Fungsi AJAX menyimpan nilai rapor ke database
    public function save()
    {
        if ($this->request->isAJAX()) {
            $semester  = $this->request->getPost('semester');
            $siswa_id  = $this->request->getPost('siswa_id');
            $predikat  = $this->request->getPost('predikat');
            $deskripsi = $this->request->getPost('deskripsi');

            // Cari Tahun Ajaran Aktif
            $ta = $this->db->table('tahun_ajaran')->where('status', 'Aktif')->get()->getRowArray();
            if (!$ta) return $this->response->setJSON(['status' => 'error', 'message' => 'Tahun Ajaran Aktif belum diatur oleh Admin!']);

            $count = 0;
            for ($i = 0; $i < count($siswa_id); $i++) {
                // Hanya simpan jika guru mengisi kolom catatan/deskripsi
                if (!empty(trim($deskripsi[$i]))) { 
                    $data = [
                        'siswa_id'        => $siswa_id[$i],
                        'tahun_ajaran_id' => $ta['id'],
                        'semester'        => $semester,
                        'predikat'        => $predikat[$i],
                        'deskripsi'       => $deskripsi[$i]
                    ];

                    // Cek apakah nilai sudah ada (Update) atau belum (Insert)
                    $existing = $this->db->table('nilai_tahfidz')
                                         ->where('siswa_id', $siswa_id[$i])
                                         ->where('tahun_ajaran_id', $ta['id'])
                                         ->where('semester', $semester)
                                         ->get()->getRowArray();

                    if ($existing) {
                        $this->db->table('nilai_tahfidz')->where('id', $existing['id'])->update($data);
                    } else {
                        $this->db->table('nilai_tahfidz')->insert($data);
                    }
                    $count++;
                }
            }

            if ($count > 0) {
                return $this->response->setJSON(['status' => 'success', 'message' => "$count Nilai Rapor santri berhasil disimpan!"]);
            } else {
                return $this->response->setJSON(['status' => 'warning', 'message' => 'Tidak ada nilai disimpan. Pastikan Anda mengisi kolom catatan minimal 1 santri.']);
            }
        }
    }
}