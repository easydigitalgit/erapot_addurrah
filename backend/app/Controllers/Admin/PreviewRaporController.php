<?php

namespace App\Controllers\Admin;

use App\Controllers\AdminBaseController;

// Pastikan namespace ini sesuai dengan lokasi file Model kamu
use App\Models\Admin\SiswaModel;
use App\Models\Admin\NilaiAkademikModel;
use App\Models\Admin\RombelModel;
use App\Models\Admin\MataPelajaranModel;

class PreviewRaporController extends AdminBaseController
{
    protected $siswaModel;
    protected $nilaiModel;
    protected $rombelModel;
    protected $mapelModel;
    protected $db;

    public function __construct()
    {
        $this->siswaModel = new SiswaModel();
        $this->nilaiModel = new NilaiAkademikModel();
        $this->rombelModel = new RombelModel();
        $this->mapelModel = new MataPelajaranModel();
        $this->db = \Config\Database::connect();
    }

    public function index()
    {
        $this->data['title'] = 'Preview Rapor Siswa';
        $this->data['color'] = $this->getColor();

        // Data untuk Dropdown Filter
        $this->data['list_tingkat'] = $this->db->table('rombel')->select('tingkat')->distinct()->orderBy('tingkat', 'ASC')->get()->getResultArray();
        $this->data['list_rombel']  = $this->rombelModel->orderBy('nama_rombel', 'ASC')->findAll();

        return view('admin/preview-rapor', $this->data);
    }

 public function getSiswa()
    {
        try {
            $tingkat = $this->request->getGet('tingkat');
            $rombel  = $this->request->getGet('rombel');
            $search  = $this->request->getGet('search');

            // 1. Ambil Tahun Ajaran Aktif
            $ta_aktif = $this->db->table('tahun_ajaran')->where('status', 'Aktif')->get()->getRowArray();
            $tahun_ajaran = $ta_aktif ? $ta_aktif['tahun'] : '2025/2026';
            $semester     = $ta_aktif ? $ta_aktif['semester'] : 'Genap';

            $builder = $this->siswaModel->select('siswa.id, siswa.nama_lengkap, siswa.nis, siswa.rombel_id, rombel.nama_rombel as kelas');
            $builder->join('rombel', 'rombel.id = siswa.rombel_id', 'left');
            $builder->where('siswa.status_siswa', 'Aktif');
            
            if ($tingkat) $builder->where('rombel.tingkat', $tingkat);
            if ($rombel)  $builder->where('siswa.rombel_id', $rombel);
            if ($search) {
                $builder->groupStart()
                        ->like('siswa.nama_lengkap', $search)
                        ->orLike('siswa.nis', $search)
                        ->groupEnd();
            }

            $siswa = $builder->findAll();

            // --- LOGIKA DINAMIS STATUS ---
            foreach ($siswa as &$s) {
                // Hitung Target Mapel menggunakan guru_mapel (Lebih Akurat)
                $targetMapel = $this->db->table('guru_mapel')
                                        ->where('rombel_id', $s['rombel_id'])
                                        ->where('tahun_ajaran', $tahun_ajaran)
                                        ->where('status', 'active')
                                        ->countAllResults();
                
                if ($targetMapel == 0) $targetMapel = 1; // Fallback anti error division by zero

                // Hitung Nilai yang sudah diinput untuk siswa ini
                // Menggunakan groupBy mapel_id agar jika guru input banyak nilai (UH, UTS), tetap dihitung 1 mapel selesai.
                $nilaiMasuk = $this->db->table('nilai_akademik')
                                       ->select('mapel_id')
                                       ->where('siswa_id', $s['id'])
                                       ->where('tahun_ajaran', $tahun_ajaran)
                                       ->where('semester', $semester)
                                       ->groupBy('mapel_id')
                                       ->countAllResults();

                $persen = ($nilaiMasuk / $targetMapel) * 100;
                if ($persen > 100) $persen = 100;

                $status = ($nilaiMasuk >= $targetMapel) ? 'lengkap' : 'belum-lengkap';

                $s['statusNilai'] = $status;
                $s['nilaiMasuk']  = $nilaiMasuk; 
                $s['targetMapel'] = $targetMapel; 
                $s['persentase']  = round($persen); 
            }

            return $this->response->setJSON(['status' => 'success', 'data' => $siswa]);

        } catch (\Exception $e) {
            return $this->response->setStatusCode(500)->setJSON([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }

    // API: Ambil Detail Rapor (Untuk Modal/Print Preview)
    public function getDetailRapor($siswa_id)
    {
        // 1. Ambil Tahun Ajaran Aktif
        $ta_aktif = $this->db->table('tahun_ajaran')->where('status', 'Aktif')->get()->getRowArray();
        $tahun_ajaran = $ta_aktif ? $ta_aktif['tahun'] : '2025/2026';
        $semester     = $ta_aktif ? $ta_aktif['semester'] : 'Genap';

        // 2. Data Siswa & Kelas
        $siswa = $this->siswaModel
            ->select('siswa.*, rombel.nama_rombel as kelas, rombel.wali_kelas_id')
            ->join('rombel', 'rombel.id = siswa.rombel_id', 'left')
            ->find($siswa_id);

        if (!$siswa) return $this->response->setJSON(['status' => 'error', 'message' => 'Siswa tidak ditemukan']);

        // 3. Data Nilai Akademik
        $nilai = $this->db->table('nilai_akademik n')
            ->select('n.mapel_id, m.nama_mapel, AVG(n.nilai_angka) as nilai_akhir, MIN(m.kkm) as kkm_mapel')
            ->join('mata_pelajaran m', 'm.id = n.mapel_id', 'left')
            ->where('n.siswa_id', $siswa_id)
            ->where('n.tahun_ajaran', $tahun_ajaran)
            ->where('n.semester', $semester)
            ->groupBy('n.mapel_id, m.nama_mapel')
            ->get()->getResultArray();

        $aturanPredikat = $this->db->table('setting_aturan_nilai')->get()->getResultArray();
        
        foreach ($nilai as &$n) {
            $n['nilai_akhir'] = round($n['nilai_akhir']);
            $n['kkm'] = $n['kkm_mapel'] ?? 75; 
            $n['predikat'] = 'D'; 
            $n['deskripsi'] = 'Perlu bimbingan lebih lanjut.'; 

            foreach ($aturanPredikat as $aturan) {
                if ($n['nilai_akhir'] >= $aturan['nilai_min'] && $n['nilai_akhir'] <= $aturan['nilai_max']) {
                    $n['predikat'] = $aturan['predikat'];
                    $n['deskripsi'] = $aturan['deskripsi_kompetensi'] ?? 'Mencapai target kompetensi dengan baik.';
                    break;
                }
            }
        }

        // 4. Data Wali Kelas (Tarik Nama dan NUPTK/NIK)
        $wali = $this->db->table('guru_tendik')
            ->select('nama_lengkap, nuptk, nik')
            ->where('id', $siswa['wali_kelas_id'])
            ->get()->getRowArray();

        // 5. Data Profil Sekolah
        $sekolah = $this->db->table('sekolah')->get()->getRowArray();

        // 6. Data Kepala Sekolah (Cari jabatan yang mengandung kata "Kepala Sekolah")
        $kepsek = $this->db->table('guru_tendik')
            ->select('nama_lengkap, nuptk, nik')
            ->like('jabatan', 'Kepala Sekolah', 'both')
            ->get()->getRowArray();

        // Kirim semua paket lengkap ke Javascript!
        return $this->response->setJSON([
            'status' => 'success',
            'data' => [
                'siswa'          => $siswa,
                'nilai'          => $nilai,
                'wali_kelas'     => $wali['nama_lengkap'] ?? 'Belum Diatur',
                'wali_nuptk'     => $wali['nuptk'] ?? ($wali['nik'] ?? '-'),
                'sekolah'        => $sekolah,
                'kepala_sekolah' => $kepsek['nama_lengkap'] ?? 'Belum Diatur',
                'kepsek_nuptk'   => $kepsek['nuptk'] ?? ($kepsek['nik'] ?? '-'),
                'tahun_ajaran'   => $tahun_ajaran,
                'semester'       => $semester
            ]
        ]);
    }
}