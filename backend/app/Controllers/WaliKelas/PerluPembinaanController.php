<?php
namespace App\Controllers\WaliKelas;

use App\Controllers\WaliKelasBaseController;

class PerluPembinaanController extends WaliKelasBaseController
{
    public function index()
    {
        $db = \Config\Database::connect();
        $userId = session()->get('user_id');

        $sekolah = $db->table('sekolah')->select('warna_primary, warna_secondary')->get()->getRowArray();
        $warna_primary = $sekolah ? $sekolah['warna_primary'] : '#10b981';
        $warna_secondary = $sekolah ? $sekolah['warna_secondary'] : '#ecfdf5';

        $guru = $db->table('guru_tendik')->where('user_id', $userId)->get()->getRowArray();
        
        $rombel = null;
        $siswa_kelas = [];
        $siswa_pembinaan = [];
        $statistik = ['akademik' => 0, 'karakter' => 0, 'tahfidz' => 0, 'absensi' => 0];

        if ($guru) {
            $rombel = $db->table('rombel')
                         ->where('wali_kelas_id', $guru['id'])
                         ->where('tahun_ajaran', session()->get('tahun_ajaran') ?? '2024/2025')
                         ->where('semester', session()->get('semester') ?? 'Ganjil')
                         ->get()->getRowArray();

            if ($rombel) {
                // Ambil daftar seluruh siswa di kelas untuk Modal Pilihan
                $siswa_kelas = $db->table('siswa')
                                  ->where('rombel_id', $rombel['id'])
                                  ->where('status_siswa', 'Aktif')
                                  ->get()->getResultArray();

                // Ambil data catatan riwayat pembinaan
                if ($db->tableExists('catatan_akhlak')) {
                    $catatan_db = $db->table('catatan_akhlak')
                                     ->select('catatan_akhlak.*, siswa.nama_lengkap, siswa.nisn')
                                     ->join('siswa', 'siswa.id = catatan_akhlak.siswa_id')
                                     ->where('catatan_akhlak.rombel_id', $rombel['id'])
                                     ->orderBy('catatan_akhlak.tanggal', 'DESC')
                                     ->get()->getResultArray();

                    // Kelompokkan data per siswa agar tidak double di card
                    $grouped_pembinaan = [];
                    foreach($catatan_db as $c) {
                        $s_id = $c['siswa_id'];
                        $kat = $c['kategori_akhlak'] ?? 'Karakter';
                        
                        // Hitung statistik atas
                        if(strtolower($kat) == 'akademik') $statistik['akademik']++;
                        if(strtolower($kat) == 'karakter' || strtolower($kat) == 'sikap') $statistik['karakter']++;
                        if(strtolower($kat) == 'tahfidz') $statistik['tahfidz']++;
                        if(strtolower($kat) == 'absensi') $statistik['absensi']++;

                        if (!isset($grouped_pembinaan[$s_id])) {
                            $grouped_pembinaan[$s_id] = [
                                'siswa_id' => $s_id,
                                'inisial'  => strtoupper(substr($c['nama_lengkap'], 0, 2)),
                                'nama'     => $c['nama_lengkap'],
                                'nisn'     => $c['nisn'],
                                'status'   => 'warning',
                                'kategori' => [$kat],
                                'tema'     => 'amber',
                                'pesan'    => $c['catatan'] // Ambil catatan terbaru
                            ];
                        } else {
                            if (!in_array($kat, $grouped_pembinaan[$s_id]['kategori'])) {
                                $grouped_pembinaan[$s_id]['kategori'][] = $kat;
                            }
                        }

                        // Jika punya > 1 masalah, ubah jadi Urgent (Merah)
                        if (count($grouped_pembinaan[$s_id]['kategori']) > 1 || strtolower($kat) == 'akademik') {
                            $grouped_pembinaan[$s_id]['status'] = 'urgent';
                            $grouped_pembinaan[$s_id]['tema'] = 'red';
                        }
                    }
                    $siswa_pembinaan = array_values($grouped_pembinaan);
                }
            }
        }

        $data = [
            'title'           => 'Siswa Perlu Pembinaan',
            'user'            => session()->get('nama_lengkap') ?? 'Wali Kelas',
            'navigations'     => $this->getSidebarMenu(),
            'rombel'          => $rombel,
            'statistik'       => $statistik,
            'siswa_kelas'     => $siswa_kelas,
            'siswa_pembinaan' => $siswa_pembinaan,
            'color'           => [
                'warna_primary'   => $warna_primary, 
                'warna_secondary' => $warna_secondary
            ]
        ];

        return view('WaliKelas/perlu-pembinaan', $data); 
    }

    // FUNGSI UNTUK MENYIMPAN CATATAN BARU
    public function saveCatatan()
    {
        $db = \Config\Database::connect();
        
        $siswa_id = $this->request->getPost('siswa_id');
        $rombel_id = $this->request->getPost('rombel_id');
        $kategori = $this->request->getPost('kategori');
        $catatan = $this->request->getPost('catatan');
        $tindak_lanjut = $this->request->getPost('tindak_lanjut');

        $userId = session()->get('user_id');
        $guru = $db->table('guru_tendik')->where('user_id', $userId)->get()->getRowArray();

        if (!$siswa_id || !$kategori || !$catatan) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Data tidak lengkap']);
        }

        $data = [
            'siswa_id'         => $siswa_id,
            'guru_id'          => $guru['id'] ?? 0,
            'mapel_id'         => null, // Wali kelas tidak pakai mapel
            'rombel_id'        => $rombel_id,
            'kategori_akhlak'  => $kategori,
            'status_pembinaan' => 'Proses',
            'tindak_lanjut'    => $tindak_lanjut,
            'catatan'          => $catatan,
            'tanggal'          => date('Y-m-d H:i:s')
        ];

        $db->table('catatan_akhlak')->insert($data);

        return $this->response->setJSON(['status' => 'success', 'message' => 'Catatan pembinaan berhasil disimpan!']);
    }
}