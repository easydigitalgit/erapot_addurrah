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
        $dataGuru = $db->table('guru_tendik')
                       ->select('id')
                       ->where('user_id', $userId)
                       ->get()
                       ->getRowArray();
                       
        $guruId = $dataGuru ? $dataGuru['id'] : 0; 

        // 2. Ambil semua penugasan kelas untuk guru ini (Gunakan guru_id)
        $builder = $db->table('guru_mapel gm');
        $builder->select('gm.rombel_id, gm.mapel_id, gm.jam_per_minggu, m.nama_mapel, r.nama_rombel'); 
        $builder->join('mata_pelajaran m', 'm.id = gm.mapel_id', 'left');
        $builder->join('rombel r', 'r.id = gm.rombel_id', 'left'); 
        $builder->where('gm.guru_id', $guruId); // FIX: Gunakan guru_id
        
        $kelas_assigned = $builder->get()->getResultArray();

        // 3. Siapkan Variabel untuk Summary Card
        $total_kelas = count($kelas_assigned);
        $total_jam = 0;
        $total_siswa_all = 0;
        $nama_mapel_utama = $total_kelas > 0 ? $kelas_assigned[0]['nama_mapel'] : 'Belum Ada';

        $kelas_cards = [];
        // Sebaiknya ini diambil dari session atau setting aktif
        $tahun_ajaran = session()->get('tahun_ajaran_aktif') ?? '2024/2025'; 
        $semester = session()->get('semester_aktif') ?? 'Genap';

        // 4. Looping untuk menghitung detail per-kelas (Card)
        foreach ($kelas_assigned as $kelas) {
            $total_jam += (int)$kelas['jam_per_minggu'];

            // Hitung jumlah siswa aktif di kelas ini
            $jml_siswa = $db->table('siswa')
                            ->where('rombel_id', $kelas['rombel_id'])
                            ->where('status_siswa', 'Aktif')
                            ->countAllResults();
            
            $total_siswa_all += $jml_siswa;

            // Hitung Progress Penilaian
            $progress = 0;
            $status_badge = 'belum';

            if ($jml_siswa > 0) {
                // Ambil ID siswa di rombel ini
                $subQuery = $db->table('siswa')
                               ->select('id')
                               ->where('rombel_id', $kelas['rombel_id'])
                               ->where('status_siswa', 'Aktif');

                // Hitung berapa siswa yang sudah memiliki setidaknya satu nilai di mapel ini
                $graded_students_query = $db->table('nilai_komponen')
                                          ->select('siswa_id')
                                          ->distinct()
                                          ->whereIn('siswa_id', $subQuery)
                                          ->where('mapel_id', $kelas['mapel_id'])
                                          ->where('tahun_ajaran', $tahun_ajaran)
                                          ->where('semester', $semester)
                                          ->get()
                                          ->getResultArray();
                                          
                $graded_students = count($graded_students_query);
                $progress = round(($graded_students / $jml_siswa) * 100);

                if ($progress == 0) {
                    $status_badge = 'belum';
                } elseif ($progress == 100) {
                    $status_badge = 'selesai';
                } else {
                    $status_badge = 'proses';
                }
            }

            $kelas_cards[] = [
                'rombel_id'    => $kelas['rombel_id'],
                'mapel_id'     => $kelas['mapel_id'],
                'nama_mapel'   => $kelas['nama_mapel'],
                'nama_rombel'  => $kelas['nama_rombel'],
                'jumlah_siswa' => $jml_siswa,
                'progress'     => $progress,
                'status'       => $status_badge
            ];
        }

        $data = [
            'user'        => session()->get('nama_lengkap') ?? 'Guru Mapel',
            'navigations' => $this->getSidebarMenu(),
            'color'       => $this->getColor(),
            'summary'     => [
                'total_kelas'  => $total_kelas,
                'total_jam'    => $total_jam,
                'mapel_utama'  => $nama_mapel_utama,
                'total_siswa'  => $total_siswa_all
            ],
            'kelas_cards' => $kelas_cards
        ];

        return view('GuruMapel/daftar-kelas-mapel', $data); 
    }
}