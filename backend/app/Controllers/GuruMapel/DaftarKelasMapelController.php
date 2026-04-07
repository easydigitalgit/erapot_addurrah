<?php

namespace App\Controllers\GuruMapel;

use App\Controllers\GuruMapelBaseController;

class DaftarKelasMapelController extends GuruMapelBaseController
{
    public function index(): string
    {
        $db = \Config\Database::connect();
        $userId = session()->get('id');

        // 1. CARI IDENTITAS GURU (GURU_ID) BERDASARKAN USER_ID LOGIN
        $dataGuru = $db->table('guru_tendik')->select('id')->where('user_id', $userId)->get()->getRowArray();
        $guruId = $dataGuru ? $dataGuru['id'] : 0;

        // 2. AMBIL ID TAHUN AJARAN AKTIF
        $taAktif = $db->table('tahun_ajaran')->where('status', 'Aktif')->get()->getRowArray();
        $id_ta_aktif = $taAktif ? $taAktif['id'] : 0;

        // 3. AMBIL PENUGASAN KELAS
        $kelas_assigned = $db->table('guru_mapel gm')
            ->select('gm.rombel_id, gm.mapel_id, gm.jam_per_minggu, m.nama_mapel, r.nama_rombel, r.tingkat')
            ->join('mata_pelajaran m', 'm.id = gm.mapel_id', 'left')
            ->join('rombel r', 'r.id = gm.rombel_id', 'left')
            ->where(['gm.guru_id' => $guruId, 'gm.status' => 'active', 'r.id_tahun_ajaran' => $id_ta_aktif])
            ->get()->getResultArray();

        $total_jam = 0;
        $total_siswa_all = 0;
        $kelas_cards = [];

        foreach ($kelas_assigned as $kelas) {
            $total_jam += (int)$kelas['jam_per_minggu'];

            // Cari murid di kelas tersebut
            $siswa_di_kelas = $db->table('siswa')
                ->select('id')
                ->where(['rombel_id' => $kelas['rombel_id'], 'status_siswa' => 'Aktif'])
                ->get()->getResultArray();

            $jml_siswa = count($siswa_di_kelas);
            $total_siswa_all += $jml_siswa;

            $progress = 0;
            $status_badge = 'belum';

            if ($jml_siswa > 0) {
                $siswa_ids = array_column($siswa_di_kelas, 'id');
                $f_ids = [];
                $s_ids = [];
                $p_ids = [];

                // A. Deteksi Nilai Formatif
                if ($db->tableExists('nilai_formatif')) {
                    $f_data = $db->table('nilai_formatif')->select('siswa_id')->distinct()
                        ->where('mapel_id', $kelas['mapel_id'])
                        ->where('tahun_ajaran_id', $id_ta_aktif)
                        ->whereIn('siswa_id', $siswa_ids)
                        ->get()->getResultArray();
                    $f_ids = array_column($f_data, 'siswa_id');
                }

                // B. Deteksi Nilai Sumatif
                if ($db->tableExists('nilai_sumatif')) {
                    $s_data = $db->table('nilai_sumatif')->select('siswa_id')->distinct()
                        ->where('mapel_id', $kelas['mapel_id'])
                        ->where('tahun_ajaran_id', $id_ta_aktif)
                        ->whereIn('siswa_id', $siswa_ids)
                        ->get()->getResultArray();
                    $s_ids = array_column($s_data, 'siswa_id');
                }

                // C. Deteksi Nilai Proyek
                if ($db->tableExists('nilai_proyek') && $db->tableExists('penilaian_proyek')) {
                    $p_data = $db->table('nilai_proyek')->select('nilai_proyek.siswa_id')->distinct()
                        ->join('penilaian_proyek pp', 'pp.id = nilai_proyek.proyek_id')
                        ->where('pp.mapel_id', $kelas['mapel_id'])
                        ->where('pp.tahun_ajaran_id', $id_ta_aktif)
                        ->whereIn('nilai_proyek.siswa_id', $siswa_ids)
                        ->get()->getResultArray();
                    $p_ids = array_column($p_data, 'siswa_id');
                }

                // MENGGABUNGKAN SEMUA ID SISWA YANG SUDAH DINILAI (UNIQUE)
                $merged_ids = array_unique(array_merge($f_ids, $s_ids, $p_ids));
                $graded_students = count($merged_ids);

                // KALKULASI PROGRESS REAL-TIME
                $progress = round(($graded_students / $jml_siswa) * 100);
                $status_badge = ($progress == 100) ? 'selesai' : (($progress > 0) ? 'proses' : 'belum');
            }

            // AMBIL JADWAL UNTUK KELAS & MAPEL INI
            $jadwal_mapel_ini = [];
            if ($db->tableExists('jadwal_pelajaran')) {
                $jadwal_mapel_ini = $db->table('jadwal_pelajaran')
                    ->select('hari, jam_mulai, jam_selesai')
                    ->where([
                        'guru_id'         => $guruId,
                        'rombel_id'       => $kelas['rombel_id'],
                        'mapel_id'        => $kelas['mapel_id'],
                        'id_tahun_ajaran' => $id_ta_aktif
                    ])
                    ->orderBy('FIELD(hari, "Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu", "Minggu")')
                    ->orderBy('jam_mulai', 'ASC')
                    ->get()->getResultArray();
            }

            $kelas_cards[] = [
                'rombel_id'    => $kelas['rombel_id'],
                'mapel_id'     => $kelas['mapel_id'],
                'nama_mapel'   => $kelas['nama_mapel'],
                'nama_rombel'  => $kelas['nama_rombel'],
                'tingkat'      => $kelas['tingkat'] ?? '-',
                'jumlah_siswa' => $jml_siswa,
                'progress'     => $progress,
                'status'       => $status_badge,
                'jadwal'       => $jadwal_mapel_ini
            ];
        }

        $data = [
            'user'        => session()->get('nama_lengkap') ?? 'Guru Mapel',
            'navigations' => $this->getSidebarMenu(),
            'color'       => $this->getColor(),
            'summary'     => [
                'total_kelas' => count($kelas_assigned),
                'total_jam'   => $total_jam,
                'mapel_utama' => count($kelas_assigned) > 0 ? $kelas_assigned[0]['nama_mapel'] : '-',
                'total_siswa' => $total_siswa_all
            ],
            'kelas_cards' => $kelas_cards
        ];

        return view('GuruMapel/daftar-kelas-mapel', $data);
    }
}
