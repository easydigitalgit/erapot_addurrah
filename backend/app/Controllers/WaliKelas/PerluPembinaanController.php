<?php
namespace App\Controllers\WaliKelas;

use App\Controllers\WaliKelasBaseController;

class PerluPembinaanController extends WaliKelasBaseController
{
    public function index()
    {
        $db = \Config\Database::connect();
        $userId = session()->get('user_id');
        
        // 1. AMBIL WARNA TEMA & SEKOLAH
        $sekolah = $db->table('sekolah')->select('nama_sekolah, warna_primary, warna_secondary')->get()->getRowArray();
        $warna_primary = $sekolah ? $sekolah['warna_primary'] : '#10b981';
        $warna_secondary = $sekolah ? $sekolah['warna_secondary'] : '#ecfdf5';
        $nama_sekolah = $sekolah ? $sekolah['nama_sekolah'] : 'SMPIT Ad Durrah';

        $guru = $db->table('guru_tendik')->where('user_id', $userId)->get()->getRowArray();
        
        $rombel = null;
        $siswa_kelas = [];
        $siswa_pembinaan = []; 
        $statistik = ['akademik' => 0, 'karakter' => 0, 'tahfidz' => 0, 'absensi' => 0];

        if ($guru) {
            // 2. CARI TAHUN AJARAN AKTIF (Logika Pintar)
            $sess_ta  = session()->get('tahun_ajaran');
            $sess_smt = session()->get('semester');

            if ($sess_ta && $sess_smt) {
                $ta_aktif = $db->table('tahun_ajaran')->where('tahun', $sess_ta)->where('semester', $sess_smt)->get()->getRowArray();
            } else {
                $ta_aktif = $db->table('tahun_ajaran')->where('status', 'Aktif')->get()->getRowArray();
            }

            $id_ta = $ta_aktif ? $ta_aktif['id'] : 0;

            // 3. CARI ROMBEL (Dengan Bypass Bypass Pintar)
            $rombel = $db->table('rombel')
                         ->where('wali_kelas_id', $guru['id'])
                         ->where('id_tahun_ajaran', $id_ta)
                         ->get()->getRowArray();


            if ($rombel) {
                // Ambil string tahun & semester dari id_tahun_ajaran yang didapat
                $ta_asli = $db->table('tahun_ajaran')->where('id', $rombel['id_tahun_ajaran'])->get()->getRowArray();
                $rombel['semester'] = $ta_asli ? $ta_asli['semester'] : 'Ganjil';
                $rombel['tahun_ajaran'] = $ta_asli ? $ta_asli['tahun'] : '2024/2025';

                // 4. Ambil daftar seluruh siswa di kelas untuk Modal Pilihan
                $siswa_kelas = $db->table('siswa')
                                  ->select('id, nama_lengkap, nisn, nis') // Tambah NIS untuk view
                                  ->where('rombel_id', $rombel['id'])
                                  ->where('status_siswa', 'Aktif')
                                  ->get()->getResultArray();

                // 5. DINAMISKAN: Ambil data catatan riwayat pembinaan
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
                        
                        // Hitung statistik atas secara dinamis (menggunakan stripos agar tidak case sensitive)
                        if(stripos($kat, 'akademik') !== false) $statistik['akademik']++;
                        if(stripos($kat, 'karakter') !== false || stripos($kat, 'sikap') !== false) $statistik['karakter']++;
                        if(stripos($kat, 'tahfidz') !== false) $statistik['tahfidz']++;
                        if(stripos($kat, 'absen') !== false) $statistik['absensi']++;

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
                        if (count($grouped_pembinaan[$s_id]['kategori']) > 1 || stripos($kat, 'akademik') !== false) {
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
            'nama_sekolah'    => $nama_sekolah, // Untuk JS Dinamis
            'navigations'     => $this->getSidebarMenu(),
            'guru'            => $guru,
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
            return $this->response->setJSON(['status' => 'error', 'message' => 'Data tidak lengkap. Pastikan semua kolom terisi.']);
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

        try {
            $db->table('catatan_akhlak')->insert($data);
            return $this->response->setJSON(['status' => 'success', 'message' => 'Catatan pembinaan berhasil disimpan!']);
        } catch (\Exception $e) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Terjadi kesalahan pada database.']);
        }
    }
}