<?php
namespace App\Controllers\WaliKelas;

use App\Controllers\WaliKelasBaseController;

class ValidasiCatatanGuruController extends WaliKelasBaseController
{
    public function index()
    {
        $db = \Config\Database::connect();
        $userId = session()->get('user_id');

        // 1. Ambil Warna Sekolah
        $sekolah = $db->table('sekolah')->select('warna_primary, warna_secondary')->get()->getRowArray();
        $warna_primary = $sekolah ? $sekolah['warna_primary'] : '#10b981';
        $warna_secondary = $sekolah ? $sekolah['warna_secondary'] : '#ecfdf5';

        $guru = $db->table('guru_tendik')->where('user_id', $userId)->get()->getRowArray();
        
        $rombel = null;
        $catatanGuruData = [];

        if ($guru) {
            $rombel = $db->table('rombel')
                         ->where('wali_kelas_id', $guru['id'])
                         ->where('tahun_ajaran', session()->get('tahun_ajaran') ?? '2024/2025')
                         ->where('semester', session()->get('semester') ?? 'Ganjil')
                         ->get()->getRowArray();

            if ($rombel) {
                // 2. Ambil Semua Catatan Akhlak untuk siswa di kelas ini
                if ($db->tableExists('catatan_akhlak')) {
                    $catatanGuruData = $db->table('catatan_akhlak')
                        ->select('catatan_akhlak.id, siswa.nama_lengkap as studentName, rombel.nama_rombel as class, catatan_akhlak.kategori_akhlak as category, catatan_akhlak.catatan, catatan_akhlak.tanggal as date')
                        ->join('siswa', 'siswa.id = catatan_akhlak.siswa_id')
                        ->join('rombel', 'rombel.id = siswa.rombel_id')
                        ->where('siswa.rombel_id', $rombel['id'])
                        ->orderBy('catatan_akhlak.tanggal', 'DESC')
                        ->get()->getResultArray();

                    // Format data agar sesuai dengan struktur Javascript
                    foreach ($catatanGuruData as &$c) {
                        $c['subject'] = 'Bimbingan Kelas'; 
                        $c['teacher'] = 'Wali Kelas / Guru'; 
                        
                        // Karena belum ada kolom status di DB Mas, kita beri default
                        $c['status'] = isset($c['status_validasi']) ? $c['status_validasi'] : 'Menunggu Validasi';
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
            'navigations'     => $this->getSidebarMenu(),
            'catatanGuruData' => json_encode($catatanGuruData), // Suntikkan data JSON ke View
            'color'           => ['warna_primary' => $warna_primary, 'warna_secondary' => $warna_secondary]
        ];

        return view('WaliKelas/validasi-catatan-guru', $data); 
    }
}