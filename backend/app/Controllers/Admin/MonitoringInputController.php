<?php

namespace App\Controllers\Admin;

use App\Controllers\AdminBaseController;
use App\Models\Admin\NilaiAkademikModel;
use App\Models\Admin\SiswaModel;

class MonitoringInputController extends AdminBaseController
{
    protected $nilaiModel;
    protected $siswaModel;
    protected $db;

    public function __construct()
    {
        $this->nilaiModel = new NilaiAkademikModel();
        $this->siswaModel = new SiswaModel();
        $this->db = \Config\Database::connect();
    }

   public function index()
    {
        $this->data['title'] = 'Monitoring Input Nilai';
        $this->data['color'] = $this->getColor();
        
        // --- 1. DINAMISASI: Ambil Tahun Ajaran Aktif dari tabel tahun_ajaran ---
        $ta_aktif = $this->db->table('tahun_ajaran')->where('status', 'Aktif')->get()->getRowArray();
        $tahun_ajaran = $ta_aktif ? $ta_aktif['tahun'] : '2025/2026';
        $semester     = $ta_aktif ? $ta_aktif['semester'] : 'Genap';
        
        // --- 2. DINAMISASI & OPTIMASI: Ganti ke tabel guru_mapel ---
        $builder = $this->db->table('guru_mapel gm');
        $builder->select('gm.guru_id, gm.mapel_id, gm.rombel_id, 
                          g.nama_lengkap as nama_guru, g.nuptk, g.no_hp as hp_guru,
                          m.nama_mapel, 
                          r.nama_rombel');
        $builder->join('guru_tendik g', 'g.id = gm.guru_id');
        $builder->join('mata_pelajaran m', 'm.id = gm.mapel_id');
        $builder->join('rombel r', 'r.id = gm.rombel_id');
        
        // Hanya ambil penugasan mengajar yang statusnya aktif
        $builder->where('gm.status', 'active'); 
        
        $builder->groupBy(['gm.guru_id', 'gm.mapel_id', 'gm.rombel_id']);
        $builder->orderBy('r.nama_rombel', 'ASC');
        
        $daftarMengajar = $builder->get()->getResultArray();

        // 3. Variable Penampung Statistik
        $monitoringData = [];
        $stats = [
            'total_guru' => count(array_unique(array_column($daftarMengajar, 'guru_id'))),
            'selesai' => 0,
            'proses' => 0,
            'belum' => 0,
            'siap_validasi' => 0,
            
            // --- STATS BARU ---
            'total_siswa_dinilai' => 0, // Akumulasi semua siswa yg sdh dinilai
            'avg_progres_sekolah' => 0, // Rata-rata progres satu sekolah
            'mapel_tertinggi' => '-',   // Nama mapel paling rajin
            'rombel_tertinggi' => '-'   // Kelas paling rajin
        ];
        
        $total_persen_akumulasi = 0;
        $highest_persen = -1; // Untuk cari yang tertinggi

        foreach ($daftarMengajar as $item) {
            // Hitung Total Siswa di Kelas
            $totalSiswa = $this->siswaModel
                ->where('rombel_id', $item['rombel_id'])
                ->where('status_siswa', 'Aktif')
                ->countAllResults();

            if ($totalSiswa == 0) continue; // Abaikan kelas tanpa siswa aktif

            // Hitung Yang Sudah Dinilai (Berdasarkan Tahun Ajaran Aktif)
            $sudahDinilai = $this->nilaiModel
                ->where('mapel_id', $item['mapel_id'])
                ->where('rombel_id', $item['rombel_id'])
                ->where('tahun_ajaran', $tahun_ajaran)
                ->where('semester', $semester)
                ->countAllResults();

            // Hitung Persen
            $persen = ($totalSiswa > 0) ? round(($sudahDinilai / $totalSiswa) * 100) : 0;

            // Update Stats Counter
            if ($persen == 100) {
                $status = 'Selesai';
                $badge = 'success';
                $stats['selesai']++;
                $stats['siap_validasi']++;
            } elseif ($persen > 0) {
                $status = 'Proses';
                $badge = 'warning';
                $stats['proses']++;
            } else {
                $status = 'Belum Input';
                $badge = 'error';
                $stats['belum']++;
            }

            // --- LOGIKA STATS TAMBAHAN ---
            $stats['total_siswa_dinilai'] += $sudahDinilai;
            $total_persen_akumulasi += $persen;

            // Cari Mapel/Kelas dengan progres tertinggi (Juara)
            if ($persen > $highest_persen) {
                $highest_persen = $persen;
                $stats['mapel_tertinggi'] = $item['nama_mapel'];
                $stats['rombel_tertinggi'] = $item['nama_rombel'];
            }

            $monitoringData[] = [
                'guru'          => $item['nama_guru'],
                'nuptk'         => $item['nuptk'],
                'hp_guru'       => $item['hp_guru'],
                'mapel'         => $item['nama_mapel'],
                'kelas'         => $item['nama_rombel'],
                'guru_id'       => $item['guru_id'], 
                'mapel_id'      => $item['mapel_id'],
                'rombel_id'     => $item['rombel_id'],
                'total_siswa'   => $totalSiswa,
                'sudah_dinilai' => $sudahDinilai,
                'persen'        => $persen,
                'status'        => $status,
                'badge'         => $badge
            ];
        }

        // Hitung Rata-rata Sekolah
        $jumlah_data = count($monitoringData);
        if ($jumlah_data > 0) {
            $stats['avg_progres_sekolah'] = round($total_persen_akumulasi / $jumlah_data);
        }

        $this->data['monitoring'] = $monitoringData;
        $this->data['stats'] = $stats;
        // Opsional: Kirim ke view agar Admin tahu sedang memonitor semester apa
        $this->data['info_semester'] = "$tahun_ajaran - $semester"; 

        return view('admin/monitoring-input', $this->data);
    }

    // --- FITUR BARU: KIRIM NOTIFIKASI KE GURU ---
    public function sendNotification()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(404);
        }

        $guru_id = $this->request->getPost('guru_id');
        $mapel   = $this->request->getPost('mapel');
        $kelas   = $this->request->getPost('kelas');

        $db = \Config\Database::connect();

        // 1. Cari user_id milik guru tersebut
        $guru = $db->table('guru_tendik')->where('id', $guru_id)->get()->getRowArray();
        
        if (!$guru || empty($guru['user_id'])) {
            return $this->response->setJSON([
                'status' => 'error', 
                'message' => 'Guru tidak memiliki akun login, tidak dapat mengirim notifikasi.'
            ]);
        }

        $userId = $guru['user_id'];

        // 2. Siapkan Pesan
        $judul = '⏳ Reminder Input Nilai';
        if ($mapel && $kelas) {
            $pesan = "Mohon segera selesaikan input nilai untuk mata pelajaran <b>$mapel</b> di kelas <b>$kelas</b>. Progres Anda belum 100%.";
        } else {
            // Mode Massal (Banyak Kelas/Mapel)
            $pesan = "Pemberitahuan dari Admin: Mohon segera lengkapi pengisian nilai rapor pada kelas yang Anda ajar.";
        }

        // 3. Simpan ke tabel notifikasi
        $dataNotif = [
            'user_id' => $userId,
            'judul'   => $judul,
            'pesan'   => $pesan,
            'tipe'    => 'warning',
            'link'    => '/guru/nilai-harian', // Arahkan guru ke halaman input nilai
            'is_read' => 0,
            'created_at' => date('Y-m-d H:i:s')
        ];

        if ($db->table('notifikasi')->insert($dataNotif)) {
            return $this->response->setJSON(['status' => 'success', 'message' => 'Notifikasi berhasil dikirim ke akun Guru!']);
        } else {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Gagal menyimpan notifikasi ke database.']);
        }
    }
}