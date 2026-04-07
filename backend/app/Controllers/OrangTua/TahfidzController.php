<?php

namespace App\Controllers\OrangTua;

use App\Controllers\OrangTuaBaseController;

class TahfidzController extends OrangTuaBaseController
{
    public function index()
    {
        $db = \Config\Database::connect();
        $userId = session()->get('user_id') ?? session()->get('id');

        // Menangkap filter semester jika ada
        $semester_aktif = $this->request->getGet('semester') ?? session()->get('semester') ?? 'Ganjil';

        // 1. CARI DATA ORANG TUA
        $orangTua = $db->table('orangtua_wali')->where('user_id', $userId)->get()->getRowArray();
        
        $sapaan = 'Bapak/Ibu'; 
        $namaOrangTua = session()->get('nama_lengkap') ?? session()->get('username');
        $siswaId = 0;

        if ($orangTua) {
            $siswaId = $orangTua['siswa_id'] ?? 0;
            if (!empty($orangTua['nama_ayah']) && $orangTua['nama_ayah'] !== '-') { $sapaan = 'Bapak'; $namaOrangTua = $orangTua['nama_ayah']; }
            elseif (!empty($orangTua['nama_ibu']) && $orangTua['nama_ibu'] !== '-') { $sapaan = 'Ibu'; $namaOrangTua = $orangTua['nama_ibu']; }
            elseif (!empty($orangTua['nama_wali']) && $orangTua['nama_wali'] !== '-') { $sapaan = 'Bapak/Ibu'; $namaOrangTua = $orangTua['nama_wali']; }
        }

        // 2. CARI DATA ANAK (Murni & Bersih, langsung panggil foto_siswa dan foto_profil)
        $anak = $db->table('siswa')
                   ->select('siswa.id, siswa.nama_lengkap, siswa.nis, rombel.nama_rombel as kelas, rombel.tingkat, rombel.semester as rombel_semester, users.foto_profil, siswa.foto_siswa')
                   ->join('rombel', 'rombel.id = siswa.rombel_id', 'left')
                   ->join('users', 'users.id = siswa.user_id', 'left')
                   ->where('siswa.id', $siswaId)
                   ->get()->getRowArray();

        if ($anak) {
            // --- LOGIKA HYBRID AVATAR ---
            $fotoProfil = $anak['foto_profil'] ?? '';
            $fotoSiswa  = $anak['foto_siswa'] ?? '';
            // Prioritaskan foto_profil, kalau kosong baru pakai foto_siswa
            $anak['foto_fix'] = !empty($fotoProfil) ? $fotoProfil : (!empty($fotoSiswa) ? $fotoSiswa : null);
        }

        $setoran = [];
        $tahfidz_terakhir = ['surah' => '-', 'ayat' => '-', 'tanggal' => date('Y-m-d')];
        $statistik = ['total_setoran' => 0, 'ziyadah' => 0, 'murojaah' => 0, 'sangat_lancar' => 0];
        
        $total_surah = 0;
        $rata_nilai = 0;

        // 3. AMBIL RIWAYAT SETORAN TAHFIDZ
        if ($anak && $db->tableExists('setoran_tahfidz')) {
            $all_setoran = $db->table('setoran_tahfidz')
                          ->where('siswa_id', $anak['id'])
                          ->orderBy('tanggal', 'DESC') 
                          ->orderBy('id', 'DESC')
                          ->get()->getResultArray();

            $setoran = $all_setoran;
            $statistik['total_setoran'] = count($all_setoran);

            if ($statistik['total_setoran'] > 0) {
                $tahfidz_terakhir = $all_setoran[0]; 
                foreach ($all_setoran as $s) {
                    if ($s['jenis_setoran'] == 'Ziyadah') $statistik['ziyadah']++;
                    elseif ($s['jenis_setoran'] == 'Murojaah') $statistik['murojaah']++;
                    
                    if (in_array(strtolower($s['predikat']), ['sangat lancar', 'mumtaz', 'a'])) {
                        $statistik['sangat_lancar']++;
                    }
                }
                
                $surah_unique = array_unique(array_column($all_setoran, 'surah'));
                $total_surah = count($surah_unique);
            }
            
            // 4. AMBIL NILAI UJIAN TAHFIDZ
            if ($db->tableExists('nilai_tahfidz')) {
                $fields = $db->getFieldNames('nilai_tahfidz');
                $kolom_nilai = in_array('nilai_teori', $fields) ? 'nilai_teori' : (in_array('nilai_angka', $fields) ? 'nilai_angka' : (in_array('nilai', $fields) ? 'nilai' : null));
                
                if ($kolom_nilai) {
                    $nilaiDb = $db->table('nilai_tahfidz')
                                  ->selectAvg($kolom_nilai, 'rata')
                                  ->where('siswa_id', $anak['id'])
                                  ->get()->getRowArray();
                    $rata_nilai = $nilaiDb['rata'] ? round($nilaiDb['rata']) : 0;
                }
            }
        }

        // 5. AMBIL TARGET TAHFIDZ 
        $target = null;
        if ($anak && !empty($anak['tingkat'])) {
            $semesterTarget = $semester_aktif;
            
            if ($db->tableExists('target_tahfidz') && $db->tableExists('ref_juz')) {
                $target = $db->table('target_tahfidz')
                             ->select('target_tahfidz.*, ref_juz.nama_juz, s_mulai.nama_surah as surah_mulai, s_sampai.nama_surah as surah_sampai')
                             ->join('ref_juz', 'ref_juz.id = target_tahfidz.juz_id', 'left')
                             ->join('ref_surah as s_mulai', 's_mulai.id = target_tahfidz.surah_mulai_id', 'left')
                             ->join('ref_surah as s_sampai', 's_sampai.id = target_tahfidz.surah_sampai_id', 'left')
                             ->where('target_tahfidz.tingkat', $anak['tingkat'])
                             ->where('target_tahfidz.semester', $semesterTarget)
                             ->where('target_tahfidz.status', 'Aktif')
                             ->get()->getRowArray();
            }
        }

        $sekolah = $db->table('sekolah')->get()->getRowArray();
        $color = [
            'warna_primary'   => $sekolah ? $sekolah['warna_primary'] : '#10b981',
            'warna_secondary' => $sekolah ? $sekolah['warna_secondary'] : '#ecfdf5',
        ];

        $data = [
            'title'            => 'Mutaba\'ah Tahfidz Ananda',
            'user'             => $namaOrangTua,
            'sapaan'           => $sapaan,
            'color'            => $color,
            'navigations'      => $this->getSidebarMenu(),
            'anak'             => $anak,
            'setoran'          => $setoran,
            'tahfidz_terakhir' => $tahfidz_terakhir,
            'statistik'        => $statistik,
            'target'           => $target,
            'total_surah'      => $total_surah,
            'rata_nilai'       => $rata_nilai,
            'semester_aktif'   => $semester_aktif 
        ];

        return view('OrangTua/tahfidz', $data);
    }
}