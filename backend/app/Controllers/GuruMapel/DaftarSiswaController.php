<?php

namespace App\Controllers\GuruMapel;

use App\Controllers\GuruMapelBaseController;

class DaftarSiswaController extends GuruMapelBaseController
{
    public function index(): string
    {
        $db = \Config\Database::connect();
        $userId = session()->get('id');

        $dataGuru = $db->table('guru_tendik')
            ->select('id')
            ->where('user_id', $userId)
            ->get()
            ->getRowArray();

        $guruId = $dataGuru ? $dataGuru['id'] : 0;

        // AMBIL ID TAHUN AJARAN AKTIF
        $taAktif = $db->table('tahun_ajaran')->where('status', 'Aktif')->get()->getRowArray();
        $id_ta_aktif = $taAktif ? $taAktif['id'] : 0;

        $allPenugasan = $db->table('guru_mapel gm')
            ->select('gm.rombel_id, gm.mapel_id, gm.jam_per_minggu, r.nama_rombel, m.nama_mapel')
            ->join('rombel r', 'r.id = gm.rombel_id', 'left')
            ->join('mata_pelajaran m', 'm.id = gm.mapel_id', 'left')
            ->where(['gm.guru_id' => $guruId, 'r.id_tahun_ajaran' => $id_ta_aktif])
            ->get()->getResultArray();

        $activeRombelId = $this->request->getGet('rombel') ?? ($allPenugasan[0]['rombel_id'] ?? 0);
        $activeMapelId  = $this->request->getGet('mapel')  ?? ($allPenugasan[0]['mapel_id'] ?? 0);

        $currentAssignment = array_filter($allPenugasan, function ($p) use ($activeRombelId, $activeMapelId) {
            return $p['rombel_id'] == $activeRombelId && $p['mapel_id'] == $activeMapelId;
        });
        $currentAssignment = reset($currentAssignment);

        // 🚀 MENGGUNAKAN MESIN WAKTU
        $jumlah_siswa = 0;
        if ($activeRombelId > 0) {
            $jumlah_siswa = $db->table('anggota_rombel ar')
                ->join('siswa s', 's.id = ar.siswa_id')
                ->where('ar.rombel_id', $activeRombelId)
                ->where('ar.tahun_ajaran_id', $id_ta_aktif)
                // ->where('ar.semester', $semester_aktif) // Opsional, bisa dipakai jika guru mapel bisa memfilter semester
                ->where('s.status_siswa', 'Aktif')
                ->countAllResults();
        }

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
            'allRombel'   => $allPenugasan,
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

        $siswas = [];
        if ($rombel_id) {
            // 🚀 MENGGUNAKAN MESIN WAKTU
            // Tangkap tahun ajaran aktif atau filter dari request
            $ta_aktif = $db->table('tahun_ajaran')->where('status', 'Aktif')->get()->getRowArray();
            $id_ta = $this->request->getGet('ta_id') ?? ($ta_aktif ? $ta_aktif['id'] : 0);

            $builder = $db->table('anggota_rombel ar');
            $builder->join('siswa s', 's.id = ar.siswa_id');

            // Pilih kolom standar yang pasti ada + u.foto_profil
            $selects = ['s.id', 's.nis', 's.nama_lengkap as name', 'u.foto_profil'];

            // Pengecekan Aman
            $hasNisn = $db->fieldExists('nisn', 'siswa');
            $hasJk = $db->fieldExists('jenis_kelamin', 'siswa');
            $hasFotoSiswa = $db->fieldExists('foto_siswa', 'siswa');
            $hasFoto = $db->fieldExists('foto', 'siswa');

            if ($hasNisn) $selects[] = 's.nisn';
            if ($hasJk) $selects[] = 's.jenis_kelamin';

            // Ambil kolom foto siswa jika ada
            if ($hasFotoSiswa) {
                $selects[] = 's.foto_siswa';
            } elseif ($hasFoto) {
                $selects[] = 's.foto as foto_siswa';
            }

            $builder->select(implode(', ', $selects));
            $builder->join('users u', 'u.id = s.user_id', 'left'); // JOIN KE USERS
            
            // Filter Mesin Waktu
            $builder->where('ar.rombel_id', $rombel_id);
            $builder->where('ar.tahun_ajaran_id', $id_ta);

            if ($db->fieldExists('status_siswa', 'siswa')) {
                $builder->where('s.status_siswa', 'Aktif');
            }

            $builder->orderBy('s.nama_lengkap', 'ASC');
            $siswas = $builder->get()->getResultArray();

            foreach ($siswas as &$siswa) {
                $siswa['nisn'] = $hasNisn ? ($siswa['nisn'] ?? '-') : '-';

                // --- LOGIKA HYBRID FOTO (BACKEND) ---
                $fotoUser = $siswa['foto_profil'] ?? '';
                $fotoSiswaLama = $siswa['foto_siswa'] ?? '';
                // Cek foto_profil dulu, kalau kosong ambil dari tabel siswa
                $siswa['foto_final'] = !empty($fotoUser) ? $fotoUser : $fotoSiswaLama;
                // ------------------------------------

                if ($hasJk) {
                    $jk = $siswa['jenis_kelamin'] ?? '-';
                    $siswa['jk_text'] = ($jk === 'L') ? 'Laki-laki' : (($jk === 'P') ? 'Perempuan' : '-');
                    $siswa['jk_kode'] = $jk;
                } else {
                    $siswa['jk_text'] = '-';
                    $siswa['jk_kode'] = '-';
                }
            }
        }

        return $this->response->setJSON([
            'status' => 'success',
            'data' => $siswas
        ]);
    }
}
