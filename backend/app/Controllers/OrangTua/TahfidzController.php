<?php

namespace App\Controllers\OrangTua;

use App\Controllers\OrangTuaBaseController;

class TahfidzController extends OrangTuaBaseController
{
    public function index()
    {
        $db = \Config\Database::connect();
        $userId = session()->get('user_id');

        // 1. CARI DATA ORANG TUA
        $orangTua = $db->table('orangtua_wali')->where('user_id', $userId)->get()->getRowArray();
        
        $sapaan = 'Bapak/Ibu'; 
        $namaOrangTua = session()->get('nama_lengkap') ?? session()->get('username');
        $siswaId = 0;

        if ($orangTua) {
            $siswaId = $orangTua['siswa_id'];
            if (!empty($orangTua['nama_ayah'])) { $sapaan = 'Bapak'; $namaOrangTua = $orangTua['nama_ayah']; }
            elseif (!empty($orangTua['nama_ibu'])) { $sapaan = 'Ibu'; $namaOrangTua = $orangTua['nama_ibu']; }
            elseif (!empty($orangTua['nama_wali'])) { $sapaan = 'Bapak/Ibu'; $namaOrangTua = $orangTua['nama_wali']; }
        }

        // 2. CARI DATA ANAK (Penting: Ambil tingkat & semester dari rombel)
        $anak = $db->table('siswa')
                   ->select('siswa.id, siswa.nama_lengkap, siswa.nis, rombel.nama_rombel as kelas, rombel.tingkat, rombel.semester')
                   ->join('rombel', 'rombel.id = siswa.rombel_id', 'left')
                   ->where('siswa.id', $siswaId)
                   ->get()->getRowArray();

        $setoran = [];
        $tahfidz_terakhir = ['surah' => '-', 'ayat' => '-', 'tanggal' => date('Y-m-d')];
        $statistik = ['total_setoran' => 0, 'ziyadah' => 0, 'murojaah' => 0, 'sangat_lancar' => 0];

        // 3. AMBIL RIWAYAT SETORAN TAHFIDZ
        if ($anak && $db->tableExists('setoran_tahfidz')) {
            $setoran = $db->table('setoran_tahfidz')
                          ->where('siswa_id', $anak['id'])
                          ->orderBy('tanggal', 'DESC') 
                          ->orderBy('id', 'DESC')
                          ->get()->getResultArray();

            $statistik['total_setoran'] = count($setoran);

            if ($statistik['total_setoran'] > 0) {
                $tahfidz_terakhir = $setoran[0]; 
                foreach ($setoran as $s) {
                    if ($s['jenis_setoran'] == 'Ziyadah') $statistik['ziyadah']++;
                    elseif ($s['jenis_setoran'] == 'Murojaah') $statistik['murojaah']++;
                    if ($s['predikat'] == 'Sangat Lancar') $statistik['sangat_lancar']++;
                }
            }
        }

        // ====================================================================
        // 4. AMBIL TARGET TAHFIDZ (DINAMIS DARI DATABASE)
        // ====================================================================
        $target = null;
        if ($anak && !empty($anak['tingkat']) && !empty($anak['semester'])) {
            if ($db->tableExists('target_tahfidz') && $db->tableExists('ref_juz')) {
                // Query menarik data dari 3 tabel sekaligus (target, juz, dan surah)
                $target = $db->table('target_tahfidz')
                             ->select('target_tahfidz.*, ref_juz.nama_juz, s_mulai.nama_surah as surah_mulai, s_sampai.nama_surah as surah_sampai')
                             ->join('ref_juz', 'ref_juz.id = target_tahfidz.juz_id', 'left')
                             ->join('ref_surah as s_mulai', 's_mulai.id = target_tahfidz.surah_mulai_id', 'left')
                             ->join('ref_surah as s_sampai', 's_sampai.id = target_tahfidz.surah_sampai_id', 'left')
                             ->where('target_tahfidz.tingkat', $anak['tingkat'])
                             ->where('target_tahfidz.semester', $anak['semester'])
                             ->where('target_tahfidz.status', 'Aktif')
                             ->get()->getRowArray();
            }
        }

        $data = [
            'title'            => 'Mutaba\'ah Tahfidz',
            'user'             => $namaOrangTua,
            'sapaan'           => $sapaan,
            'color'            => $this->getColor(),
            'navigations'      => $this->getSidebarMenu(),
            'anak'             => $anak,
            'setoran'          => $setoran,
            'tahfidz_terakhir' => $tahfidz_terakhir,
            'statistik'        => $statistik,
            'target'           => $target // <--- Kirim Variabel Target ke View
        ];

        return view('OrangTua/tahfidz', $data);
    }
}