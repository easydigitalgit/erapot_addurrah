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
                    ->select('siswa.*, rombel.nama_rombel as kelas, users.foto_profil')
                    ->join('rombel', 'rombel.id = siswa.rombel_id')
                    ->join('users', 'users.id = siswa.user_id', 'left')
                    ->where('siswa.id', $orangTua['siswa_id'])
                    ->get()->getRowArray();

        if ($anak) {
            $fotoProfil = $anak['foto_profil'] ?? '';
            $fotoSiswa  = $anak['foto_siswa'] ?? '';
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

    public function getAvailableJuz()
    {
        $db = \Config\Database::connect();
        $userId = session()->get('user_id') ?? session()->get('id');
        $orangTua = $db->table('orangtua_wali')->where('user_id', $userId)->get()->getRowArray();
        
        if (!$orangTua || empty($orangTua['siswa_id'])) return $this->response->setJSON(['status' => 'error']);

        $listJuz = $db->table('setoran_tahfidz')
                      ->select('ref_juz.id, ref_juz.nama_juz')
                      ->join('ref_juz', 'ref_juz.id = setoran_tahfidz.juz_id')
                      ->where('siswa_id', $orangTua['siswa_id'])
                      ->groupBy('ref_juz.id')
                      ->get()->getResultArray();

        return $this->response->setJSON(['status' => 'success', 'data' => $listJuz]);
    }

    public function downloadRaporJuz($juzId)
    {
        $db = \Config\Database::connect();
        $userId = session()->get('user_id') ?? session()->get('id');

        // 1. DATA ORANG TUA & ANAK
        $orangTua = $db->table('orangtua_wali')->where('user_id', $userId)->get()->getRowArray();
        if (!$orangTua || empty($orangTua['siswa_id'])) {
            return "Maaf, data anak tidak ditemukan.";
        }
        $siswaId = $orangTua['siswa_id'];

        $siswa = $db->table('siswa')
                    ->select('siswa.*, rombel.nama_rombel, rombel.tingkat, users.foto_profil, users.username as wali_username, wali.nama_lengkap as wali_kelas, wali.nuptk as wali_nuptk, wali.ttd_digital as wali_ttd')
                    ->join('rombel', 'rombel.id = siswa.rombel_id', 'left')
                    ->join('users', 'users.id = siswa.user_id', 'left')
                    ->join('guru_tendik as wali', 'wali.id = rombel.guru_id', 'left')
                    ->where('siswa.id', $siswaId)
                    ->get()->getRowArray();

        // 2. DATA JUZ & SEKOLAH
        $juz = $db->table('ref_juz')->where('id', $juzId)->get()->getRowArray();
        $sekolah = $db->table('sekolah')->get()->getRowArray();
        if (!$juz || !$sekolah) return "Data tidak valid.";

        $kepsek = $db->table('guru_tendik')->where('jabatan', 'Kepala Sekolah')->get()->getRowArray();
        $tahun_ajaran = session()->get('tahun_ajaran') ?? '2023/2024';
        $semester = session()->get('semester') ?? 'Ganjil';

        // 3. DATA SETORAN TAHFIDZ (KHUSUS JUZ INI)
        $setoran = $db->table('setoran_tahfidz')
                      ->select('setoran_tahfidz.*, ref_surah.nama_surah')
                      ->join('ref_surah', 'ref_surah.id = setoran_tahfidz.surah_id', 'left')
                      ->where('siswa_id', $siswaId)
                      ->where('juz_id', $juzId)
                      ->orderBy('tanggal', 'ASC')
                      ->get()->getResultArray();

        // 4. STATISTIK & PREDIKAT
        $totalNilai = 0;
        foreach ($setoran as $s) {
            $totalNilai += (float)($s['nilai'] ?? 0);
        }
        $avg = count($setoran) > 0 ? round($totalNilai / count($setoran), 1) : 0;
        
        $predikat = '-';
        if ($avg >= 90) $predikat = 'MUMTAZ (Sangat Lancar)';
        elseif ($avg >= 80) $predikat = 'JAYYID JIDDAN (Lancar)';
        elseif ($avg >= 70) $predikat = 'JAYYID (Cukup)';
        elseif ($avg >= 60) $predikat = 'MAQBUL (Kurang)';

        $statistik = [
            'rata_nilai'    => $avg,
            'predikat_umum' => $predikat
        ];

        // 5. KEAMANAN: WATERMARK & QR CODE
        $watermark_svg = base64_encode('<svg xmlns="http://www.w3.org/2000/svg" width="500" height="500" viewBox="0 0 500 500"><text x="50%" y="50%" font-family="Arial" font-size="40" fill="rgba(0,0,0,0.03)" text-anchor="middle" dominant-baseline="middle" transform="rotate(-45 250 250)">RAPOR TAHFIDZ DIGITAL - PROPER SMPIT</text></svg>');
        $token_validasi = md5($siswaId . $juzId . 'TAHFIDZ_RAPOR');
        $link_verifikasi = base_url('validasi/rapor/' . $token_validasi);

        // Paket Data
        $data = [
            'siswa'           => $siswa,
            'sekolah'         => $sekolah,
            'juz'             => $juz,
            'setoran'         => $setoran,
            'statistik'       => $statistik,
            'kepsek'          => $kepsek,
            'tahun_ajaran'    => $tahun_ajaran,
            'semester'        => $semester,
            'color'           => ['warna_primary' => $sekolah['warna_primary'] ?? '#10b981', 'warna_secondary' => $sekolah['warna_secondary'] ?? '#ecfdf5'],
            'logo_path'       => FCPATH . 'uploads/logo/' . ($sekolah['logo'] ?? 'none.png'),
            'watermark_svg'   => $watermark_svg,
            'link_verifikasi' => $link_verifikasi
        ];

        // RENDER PDF
        $html = view('admin/print/rapor_tahfidz_per_juz', $data);
        $dompdf = \Config\Services::dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $namaFile = "Rapor_Tahfidz_" . str_replace(' ', '_', $siswa['nama_lengkap']) . "_" . str_replace(' ', '_', $juz['nama_juz']) . ".pdf";
        return $this->response->setHeader('Content-Type', 'application/pdf')
                              ->setBody($dompdf->output())
                              ->download($namaFile, null);
    }
}