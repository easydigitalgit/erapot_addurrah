<?php

namespace App\Controllers\WaliKelas;

class NilaiEkskulController extends \App\Controllers\WaliKelasBaseController
{
    private function autoFixDatabase()
    {
        $db = \Config\Database::connect();
        if ($db->tableExists('nilai_ekskul')) {
            $fields = $db->getFieldNames('nilai_ekskul');

            if (!in_array('tahun_ajaran_id', $fields)) {
                $db->query("ALTER TABLE `nilai_ekskul` ADD `tahun_ajaran_id` BIGINT(20) UNSIGNED NULL AFTER `rombel_id`");
            }
            if (!in_array('nama_kegiatan', $fields)) {
                $db->query("ALTER TABLE `nilai_ekskul` ADD `nama_kegiatan` VARCHAR(100) NULL AFTER `ekskul_id`");
            }
            // FITUR BARU: Tambahkan kolom kategori jika belum ada
            if (!in_array('kategori', $fields)) {
                $db->query("ALTER TABLE `nilai_ekskul` ADD `kategori` VARCHAR(50) DEFAULT 'Akhir Semester' AFTER `semester`");
            }
        } else {
            $db->query("CREATE TABLE `nilai_ekskul` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `siswa_id` int(11) NOT NULL,
              `rombel_id` int(11) DEFAULT NULL,
              `tahun_ajaran_id` bigint(20) UNSIGNED DEFAULT NULL,
              `semester` varchar(10) NOT NULL,
              `kategori` varchar(50) DEFAULT 'Akhir Semester',
              `ekskul_id` int(11) NOT NULL,
              `nama_kegiatan` varchar(100) DEFAULT NULL,
              `predikat` char(1) NOT NULL,
              `keterangan` text NULL,
              `deskripsi` text NULL,
              `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
              `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
              PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
        }
    }

    public function index()
    {
        $this->autoFixDatabase();
        $db = \Config\Database::connect();
        $userId = session()->get('id') ?? session()->get('user_id');

        $guru = $db->table('guru_tendik')->where('user_id', $userId)->get()->getRowArray();
        if (!$guru) return redirect()->to(base_url('wali/dashboard'))->with('error', 'Akses ditolak.');

        $taAktif = $db->table('tahun_ajaran')->where('status', 'Aktif')->get()->getRowArray();
        $id_ta = $taAktif ? $taAktif['id'] : 0;
        
        $rombel = $db->table('rombel')
                     ->where('wali_kelas_id', $guru['id'])
                     ->where('id_tahun_ajaran', $id_ta)
                     ->get()->getRowArray();
        $ekskulList = $db->table('master_ekskul')->where('status', 'Aktif')->orderBy('nama_ekskul', 'ASC')->get()->getResultArray();

        $this->data['title']       = 'Input Nilai Ekstrakurikuler';
        $this->data['rombel']      = $rombel;
        $this->data['ekskulList']  = $ekskulList;
        $this->data['color']       = $this->getColor();
        $this->data['navigations'] = $this->getSidebarMenu();

        return view('WaliKelas/nilai-ekskul/index', $this->data);
    }

    public function getData()
    {
        $db = \Config\Database::connect();
        $userId = session()->get('id') ?? session()->get('user_id');

        // Tangkap Filter Kategori
        $kategori = $this->request->getGet('kategori') ?: 'Akhir Semester';

        $guru = $db->table('guru_tendik')->where('user_id', $userId)->get()->getRowArray();
        
        $taAktif = $db->table('tahun_ajaran')->where('status', 'Aktif')->get()->getRowArray();
        $ta_id = $taAktif ? $taAktif['id'] : 0;
        $semester = $taAktif ? $taAktif['semester'] : '';

        $rombel = $db->table('rombel')
                     ->where('wali_kelas_id', $guru['id'])
                     ->where('id_tahun_ajaran', $ta_id)
                     ->get()->getRowArray();

        if (!$rombel) return $this->response->setJSON(['status' => 'error', 'message' => 'Rombel tidak ditemukan.']);

        // MENGGUNAKAN MESIN WAKTU (anggota_rombel)
        $siswaList = $db->table('anggota_rombel ar')
            ->select('siswa.id, siswa.nama_lengkap, siswa.nis, siswa.ekskul_1, siswa.ekskul_2, siswa.ekskul_3, users.foto_profil, siswa.foto_siswa')
            ->join('siswa', 'siswa.id = ar.siswa_id')
            ->join('users', 'users.id = siswa.user_id', 'left')
            ->where('ar.rombel_id', $rombel['id'])
            ->where('ar.tahun_ajaran_id', $ta_id)
            ->where('ar.semester', $semester)
            ->where('siswa.status_siswa', 'Aktif')
            ->orderBy('siswa.nama_lengkap', 'ASC')
            ->get()->getResultArray();

        $nilaiEkskul = $db->table('nilai_ekskul')
            ->select('nilai_ekskul.*, master_ekskul.nama_ekskul')
            ->join('master_ekskul', 'master_ekskul.id = nilai_ekskul.ekskul_id', 'left')
            ->where('nilai_ekskul.tahun_ajaran_id', $ta_id)
            ->where('nilai_ekskul.semester', $semester)
            ->where('nilai_ekskul.kategori', $kategori) // Filter berdasarkan kategori
            ->get()->getResultArray();

        $nilaiGrouped = [];
        foreach ($nilaiEkskul as $ne) {
            $nilaiGrouped[$ne['siswa_id']][] = $ne;
        }

        foreach ($siswaList as &$s) {
            $s['nilai_ekskul'] = $nilaiGrouped[$s['id']] ?? [];

            $fotoProfil = $s['foto_profil'] ?? '';
            $fotoSiswa  = $s['foto_siswa'] ?? '';
            $s['foto_fix'] = !empty($fotoProfil) ? $fotoProfil : (!empty($fotoSiswa) ? $fotoSiswa : null);
        }

        return $this->response->setJSON([
            'status' => 'success',
            'data' => $siswaList,
            'rombel_id' => $rombel['id'],
            'tahun_ajaran_id' => $ta_id,
            'semester' => $semester,
            'kategori' => $kategori
        ]);
    }

    public function saveNilai()
    {
        if ($this->request->isAJAX()) {
            $db = \Config\Database::connect();

            $siswa_id        = $this->request->getPost('siswa_id');
            $rombel_id       = $this->request->getPost('rombel_id');
            $tahun_ajaran_id = $this->request->getPost('tahun_ajaran_id');
            $semester        = $this->request->getPost('semester');
            $kategori        = $this->request->getPost('kategori'); // Terima input kategori

            $ekskul_ids  = (array) $this->request->getPost('ekskul_id');
            $predikats   = (array) $this->request->getPost('predikat');
            $deskripsis  = (array) $this->request->getPost('deskripsi');

            $db->transBegin();
            try {
                foreach ($ekskul_ids as $index => $ekskul_id) {
                    $predikat  = $predikats[$index] ?? '';
                    $deskripsi = trim($deskripsis[$index] ?? '');

                    $cek = $db->table('nilai_ekskul')
                        ->where([
                            'siswa_id' => $siswa_id,
                            'tahun_ajaran_id' => $tahun_ajaran_id,
                            'semester' => $semester,
                            'kategori' => $kategori, // Pengecekan data termasuk kategorinya
                            'ekskul_id' => $ekskul_id
                        ])->get()->getRowArray();

                    if (empty($predikat)) {
                        if ($cek) {
                            $db->table('nilai_ekskul')->where('id', $cek['id'])->delete();
                        }
                        continue;
                    }

                    $mEkskul = $db->table('master_ekskul')->where('id', $ekskul_id)->get()->getRowArray();
                    $nama_ekskul = $mEkskul ? $mEkskul['nama_ekskul'] : '';

                    $dataSimpan = [
                        'siswa_id'        => $siswa_id,
                        'rombel_id'       => $rombel_id,
                        'tahun_ajaran_id' => $tahun_ajaran_id,
                        'semester'        => $semester,
                        'kategori'        => $kategori, // Simpan kategorinya
                        'ekskul_id'       => $ekskul_id,
                        'nama_kegiatan'   => $nama_ekskul,
                        'predikat'        => $predikat,
                        'deskripsi'       => $deskripsi,
                        'keterangan'      => $deskripsi
                    ];

                    if ($cek) {
                        $db->table('nilai_ekskul')->where('id', $cek['id'])->update($dataSimpan);
                    } else {
                        $db->table('nilai_ekskul')->insert($dataSimpan);
                    }
                }

                $db->transCommit();
                return $this->response->setJSON([
                    'status' => 'success',
                    'message' => 'Nilai berhasil disimpan.',
                    'token' => csrf_hash()
                ]);
            } catch (\Exception $e) {
                $db->transRollback();
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => $e->getMessage(),
                    'token' => csrf_hash()
                ]);
            }
        }
    }

    public function deleteNilai()
    {
        $id = $this->request->getPost('id');
        $db = \Config\Database::connect();
        if ($db->table('nilai_ekskul')->where('id', $id)->delete()) {
            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Berhasil dihapus.',
                'token' => csrf_hash()
            ]);
        }
        return $this->response->setJSON([
            'status' => 'error',
            'message' => 'Gagal hapus.',
            'token' => csrf_hash()
        ]);
    }
}
