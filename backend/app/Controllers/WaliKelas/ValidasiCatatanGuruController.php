<?php
namespace App\Controllers\WaliKelas;

use App\Controllers\WaliKelasBaseController;

class ValidasiCatatanGuruController extends WaliKelasBaseController
{
    public function index()
    {
        $db = \Config\Database::connect();
        $userId = session()->get('user_id');

        // 1. Ambil Warna Sekolah & Nama Sekolah
        $sekolah = $db->table('sekolah')->select('nama_sekolah, warna_primary, warna_secondary')->get()->getRowArray();
        $warna_primary = $sekolah ? $sekolah['warna_primary'] : '#10b981';
        $warna_secondary = $sekolah ? $sekolah['warna_secondary'] : '#ecfdf5';
        $nama_sekolah = $sekolah ? $sekolah['nama_sekolah'] : 'SMPIT Ad Durrah';

        $guru = $db->table('guru_tendik')->where('user_id', $userId)->get()->getRowArray();
        
        $rombel = null;
        $catatanGuruData = [];

        if ($guru) {
            $tahun_ajaran = session()->get('tahun_ajaran') ?? '2024/2025';
            $semester = session()->get('semester') ?? 'Ganjil';

            // 1. FIX ERROR: CARI ROMBEL DENGAN JOIN TABEL TAHUN AJARAN
            $rombel = $db->table('rombel')
                         ->select('rombel.*, tahun_ajaran.tahun as tahun_ajaran_nama, tahun_ajaran.semester as semester_ta')
                         ->join('tahun_ajaran', 'tahun_ajaran.id = rombel.id_tahun_ajaran', 'inner')
                         ->where('rombel.wali_kelas_id', $guru['id'])
                         ->where('tahun_ajaran.tahun', $tahun_ajaran)
                         ->where('tahun_ajaran.semester', $semester)
                         ->get()->getRowArray();

            if ($rombel) {
                // 2. DINAMISKAN & OPTIMALKAN: Ambil Catatan Akhlak beserta Nama Guru dan Mapel
                if ($db->tableExists('catatan_akhlak')) {
                    $catatanGuruData = $db->table('catatan_akhlak')
                        ->select('
                            catatan_akhlak.id, 
                            siswa.nama_lengkap as studentName, 
                            rombel.nama_rombel as class, 
                            catatan_akhlak.kategori_akhlak as category, 
                            catatan_akhlak.catatan, 
                            catatan_akhlak.status_pembinaan, 
                            catatan_akhlak.tanggal as date,
                            guru_tendik.nama_lengkap as teacher,
                            mata_pelajaran.nama_mapel as subject
                        ')
                        ->join('siswa', 'siswa.id = catatan_akhlak.siswa_id')
                        ->join('rombel', 'rombel.id = catatan_akhlak.rombel_id')
                        ->join('guru_tendik', 'guru_tendik.id = catatan_akhlak.guru_id', 'left') // Ambil nama guru pembuat catatan
                        ->join('mata_pelajaran', 'mata_pelajaran.id = catatan_akhlak.mapel_id', 'left') // Ambil mapel jika ada
                        ->where('catatan_akhlak.rombel_id', $rombel['id'])
                        ->orderBy('catatan_akhlak.tanggal', 'DESC')
                        ->get()->getResultArray();

                    // Format data agar sesuai dengan struktur Javascript di View
                    foreach ($catatanGuruData as &$c) {
                        // Jika mapel kosong (berarti catatan dari wali kelas/BK), set default
                        $c['subject'] = $c['subject'] ?? 'Bimbingan Kelas'; 
                        // Jika nama guru tidak ketemu, set default
                        $c['teacher'] = $c['teacher'] ?? 'Wali Kelas'; 
                        
                        // Format tanggal agar lebih rapi di tampilan
                        $c['date'] = date('Y-m-d', strtotime($c['date']));

                        // Di database Anda, fieldnya bernama status_pembinaan, BUKAN status_validasi
                        // Kita asumsikan 'Proses' = 'Menunggu Validasi', 'Selesai' = 'Disetujui'
                        if ($c['status_pembinaan'] == 'Proses' || $c['status_pembinaan'] == 'Perlu Pembinaan') {
                            $c['status'] = 'Menunggu Validasi';
                        } else {
                            $c['status'] = 'Disetujui'; // Atur default jika nilainya lain
                        }
                        
                        $c['approvedBy'] = '-';
                        $c['approvedDate'] = '-';
                        $c['reason'] = '-';
                    }
                }
            }
        }

        $data = [
            'title'           => 'Validasi Catatan Guru',
            'user'            => session()->get('nama_lengkap') ?? 'Wali Kelas',
            'nama_sekolah'    => $nama_sekolah,
            'navigations'     => $this->getSidebarMenu(),
            'catatanGuruData' => json_encode($catatanGuruData), // Suntikkan data JSON ke View
            'color'           => ['warna_primary' => $warna_primary, 'warna_secondary' => $warna_secondary]
        ];

        return view('WaliKelas/validasi-catatan-guru', $data); 
    }
}