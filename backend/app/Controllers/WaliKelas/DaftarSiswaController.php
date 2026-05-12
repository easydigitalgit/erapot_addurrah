<?php

namespace App\Controllers\WaliKelas;

use App\Controllers\WaliKelasBaseController;
use App\Models\Admin\SiswaModel;

class DaftarSiswaController extends WaliKelasBaseController
{
    public function index()
    {
        $db = \Config\Database::connect();
        $userId = session()->get('user_id');

        // 1. AMBIL INFO SEKOLAH & TEMA
        $sekolah = $db->table('sekolah')->select('nama_sekolah, warna_primary, warna_secondary')->get()->getRowArray();
        $nama_sekolah = $sekolah ? $sekolah['nama_sekolah'] : 'SMPIT Ad Durrah';
        $warna_primary = $sekolah ? $sekolah['warna_primary'] : '#10b981';
        $warna_secondary = $sekolah ? $sekolah['warna_secondary'] : '#ecfdf5';

        // 2. CARI DATA GURU
        $guru = $db->table('guru_tendik')->where('user_id', $userId)->get()->getRowArray();

        $rombel = null;
        $siswa_kelas = [];
        $statistik = [
            'total_siswa' => 0, 
            'hadir_hari_ini' => 0, 
            'persen_hadir' => 0
        ];

        // 3. CARI TAHUN AJARAN (Logika Dinamis & Global)
        $ta_id_get = $this->request->getGet('ta');
        if ($ta_id_get) {
            $ta_aktif = $db->table('tahun_ajaran')->where('id', $ta_id_get)->get()->getRowArray();
        } else {
            $sess_ta  = session()->get('tahun_ajaran');
            $sess_smt = session()->get('semester');
            if ($sess_ta && $sess_smt) {
                $ta_aktif = $db->table('tahun_ajaran')->where('tahun', $sess_ta)->where('semester', $sess_smt)->get()->getRowArray();
            } else {
                $ta_aktif = $db->table('tahun_ajaran')->where('status', 'Aktif')->get()->getRowArray();
            }
        }

        $id_ta        = $ta_aktif ? $ta_aktif['id'] : 0;
        $tahun_ajaran = $ta_aktif ? $ta_aktif['tahun'] : '2024/2025';
        $semester     = $ta_aktif ? $ta_aktif['semester'] : 'Ganjil';

        if ($guru) {
            // 4. CARI ROMBEL 
            $rombel = $db->table('rombel')
                         ->where('wali_kelas_id', $guru['id'])
                         ->where('id_tahun_ajaran', $id_ta)
                         ->get()->getRowArray();

            if ($rombel) {
                // 5. AMBIL DAFTAR SISWA (Menggunakan Anggota Rombel untuk Konsistensi Waktu)
                $siswa_kelas = $db->table('anggota_rombel ar')
                    ->select('siswa.*, users.foto_profil')
                    ->join('siswa', 'siswa.id = ar.siswa_id')
                    ->join('users', 'users.id = siswa.user_id', 'left')
                    ->where('ar.rombel_id', $rombel['id'])
                    ->where('ar.tahun_ajaran_id', $id_ta)
                    ->where('ar.semester', $semester)
                    ->where('siswa.status_siswa', 'Aktif')
                    ->orderBy('siswa.nama_lengkap', 'ASC')
                    ->get()->getResultArray();

                $statistik['total_siswa'] = count($siswa_kelas);

                // Kumpulkan ID siswa untuk query borongan (Optimasi)
                $siswaIds = array_column($siswa_kelas, 'id');

                if (!empty($siswaIds)) {
                    // --- BATCH QUERY OPTIMIZATION ---

                    // A. Batch Absensi
                    // A. Batch Absensi (Rekap Semester)
                    $absenMap = [];
                    if ($db->tableExists('rekap_absensi')) {
                        $fTA_R = $db->fieldExists('tahun_ajaran_id', 'rekap_absensi') ? 'tahun_ajaran_id' : 'tahun_ajaran';
                        $absenData = $db->table('rekap_absensi')
                            ->whereIn('siswa_id', $siswaIds)
                            ->where($fTA_R, ($fTA_R === 'tahun_ajaran_id' ? $id_ta : $tahun_ajaran))
                            ->where('semester', $semester)
                            ->get()->getResultArray();
                        
                        foreach ($absenData as $ad) {
                            $absenMap[$ad['siswa_id']] = [
                                'Hadir' => $ad['hadir'] ?? 0,
                                'Sakit' => $ad['sakit'] ?? 0,
                                'Izin'  => $ad['izin'] ?? 0,
                                'Alpa'  => $ad['alpha'] ?? 0
                            ];
                        }
                    }


                        // Statistik Kehadiran Hari Ini
                        $hari_ini = date('Y-m-d');
                        $hadir_hari_ini = $db->table('absensi_harian')
                                             ->where('rombel_id', $rombel['id'])
                                             ->where('tanggal', $hari_ini)
                                             ->where('status', 'Hadir')
                                             ->countAllResults();
                        $statistik['hadir_hari_ini'] = $hadir_hari_ini;
                        if ($statistik['total_siswa'] > 0) {
                            $statistik['persen_hadir'] = round(($hadir_hari_ini / $statistik['total_siswa']) * 100, 1);
                        }
                    }

                    // B. Batch Tahfidz (Hafalan Terakhir)
                    $tahfidzMap = [];
                    if ($db->tableExists('tahfidz')) {
                        $tahfidzData = $db->table('tahfidz')
                            ->select('tahfidz.siswa_id, tahfidz.surah, tahfidz.ayat')
                            ->whereIn('tahfidz.siswa_id', $siswaIds)
                            ->orderBy('tahfidz.tanggal', 'DESC')
                            ->get()->getResultArray();
                        
                        foreach ($tahfidzData as $td) {
                            if (!isset($tahfidzMap[$td['siswa_id']])) $tahfidzMap[$td['siswa_id']] = $td;
                        }
                    }

                    // C. Batch Catatan Wali Kelas
                    $catatanMap = [];
                    if ($db->tableExists('catatan_akhlak')) {
                        $catatanData = $db->table('catatan_akhlak')
                            ->select('siswa_id, kategori_akhlak, catatan')
                            ->whereIn('siswa_id', $siswaIds)
                            ->where('rombel_id', $rombel['id'])
                            ->get()->getResultArray();
                        foreach ($catatanData as $cd) $catatanMap[$cd['siswa_id']] = $cd;
                    }

                    // D. Batch Nilai Akademik
                    $nilaiMap = [];
                    if ($db->tableExists('nilai_sumatif') && $db->tableExists('mata_pelajaran')) {
                        $nilaiData = $db->table('nilai_sumatif')
                            ->select('nilai_sumatif.siswa_id, nilai_sumatif.nilai, mata_pelajaran.nama_mapel')
                            ->join('mata_pelajaran', 'mata_pelajaran.id = nilai_sumatif.mapel_id', 'left')
                            ->whereIn('nilai_sumatif.siswa_id', $siswaIds)
                            ->get()->getResultArray();
                        foreach ($nilaiData as $nd) {
                            $nilaiMap[$nd['siswa_id']][] = $nd;
                        }
                    }

                    // --- MAPPING DATA KE ARRAY SISWA ---
                    $statistik['perlu_pembinaan'] = 0;
                    foreach ($siswa_kelas as &$s) {
                        $sId = $s['id'];

                        // Map Absensi
                        $s['absen_h'] = $absenMap[$sId]['Hadir'] ?? 0;
                        $s['absen_s'] = $absenMap[$sId]['Sakit'] ?? 0;
                        $s['absen_i'] = $absenMap[$sId]['Izin'] ?? 0;
                        $s['absen_a'] = $absenMap[$sId]['Alpa'] ?? 0;
                        $total_hari = $s['absen_h'] + $s['absen_s'] + $s['absen_i'] + $s['absen_a'];
                        $s['persen_absen'] = $total_hari > 0 ? round(($s['absen_h'] / $total_hari) * 100) : 100;
                        $s['rekap_absen'] = "({$s['absen_h']}/{$total_hari})";

                        // Map Tahfidz
                        $s['capaian_tahfidz'] = isset($tahfidzMap[$sId]) ? ($tahfidzMap[$sId]['surah'] ?? 'Ada Setoran') : 'Proses';

                        // Map Catatan
                        $s['tipe_catatan'] = isset($catatanMap[$sId]) ? $catatanMap[$sId]['kategori_akhlak'] : 'Tidak ada';
                        $s['isi_catatan'] = isset($catatanMap[$sId]) ? $catatanMap[$sId]['catatan'] : 'Belum ada catatan khusus dari wali kelas.';
                        if ($s['tipe_catatan'] != 'Tidak ada') $statistik['perlu_pembinaan']++;

                        // Map Nilai
                        $s['nilai_mapel'] = [];
                        $s['rata_nilai'] = 0;
                        if (isset($nilaiMap[$sId])) {
                            $total_nilai = 0;
                            foreach ($nilaiMap[$sId] as $ndb) {
                                $mapel = $ndb['nama_mapel'] ?? 'Mapel';
                                $s['nilai_mapel'][$mapel] = $ndb['nilai'];
                                $total_nilai += $ndb['nilai'];
                            }
                            $s['rata_nilai'] = round($total_nilai / count($nilaiMap[$sId]), 1);
                        }
                    }
                }
            }
        }

        // 7. AMBIL DAFTAR EKSKUL AKTIF
        $ekskulList = $db->table('master_ekskul')->where('status', 'Aktif')->orderBy('nama_ekskul', 'ASC')->get()->getResultArray();

        // 8. AMBIL DAFTAR TAHUN AJARAN (Untuk Filter)
        $list_ta = $db->table('tahun_ajaran')->orderBy('id', 'DESC')->get()->getResultArray();

        $data = [
            'title'       => 'Daftar Siswa Kelas Perwalian',
            'user'        => session()->get('nama_lengkap') ?? 'Wali Kelas',
            'namaLengkap' => session()->get('nama_lengkap') ?? session()->get('username') ?? 'Wali Kelas',
            'nama_sekolah' => $nama_sekolah, 
            'navigations' => $this->getSidebarMenu(),
            'rombel'      => $rombel,
            'siswa_kelas' => $siswa_kelas,
            'statistik'   => $statistik,
            'tahun_ajaran' => $tahun_ajaran,
            'semester'     => $semester,
            'list_ta'     => $list_ta,
            'id_ta'       => $id_ta,
            'ekskulList'  => $ekskulList,
            'color'       => [
                'warna_primary'   => $warna_primary,
                'warna_secondary' => $warna_secondary
            ]
        ];

        return view('WaliKelas/daftar-siswa', $data);
    }

    /**
     * API: Ambil Detail Siswa untuk Modal Edit (Wali Kelas)
     * Proteksi: Hanya bisa ambil data siswa di kelas perwaliannya sendiri.
     */
    public function getDetail($id)
    {
        $db = \Config\Database::connect();
        $userId = session()->get('user_id');

        // 1. Identifikasi Guru
        $guru = $db->table('guru_tendik')->where('user_id', $userId)->get()->getRowArray();
        if (!$guru) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Identitas Guru tidak ditemukan.']);
        }

        // 2. Identifikasi Tahun Ajaran Aktif (Mesin Waktu)
        $ta_aktif = $db->table('tahun_ajaran')->where('status', 'Aktif')->get()->getRowArray();
        if (!$ta_aktif) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Tahun Ajaran Aktif tidak ditemukan.']);
        }

        // 3. Identifikasi Rombel Perwalian
        $rombel = $db->table('rombel')
            ->where('wali_kelas_id', $guru['id'])
            ->where('id_tahun_ajaran', $ta_aktif['id'])
            ->get()->getRowArray();

        if (!$rombel) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Anda tidak memiliki akses sebagai Wali Kelas pada periode ini.']);
        }

        // 4. Verifikasi Kepemilikan Siswa (Cegah ID Injection dari Kelas Lain)
        $isAnggota = $db->table('anggota_rombel')
            ->where('siswa_id', $id)
            ->where('rombel_id', $rombel['id'])
            ->where('tahun_ajaran_id', $ta_aktif['id'])
            ->countAllResults();

        if ($isAnggota == 0) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Akses Ditolak! Siswa ini bukan anggota kelas perwalian Anda.']);
        }

        // 5. Ambil Data Lengkap (Siswa + Ortu + User)
        $siswa = $db->table('siswa s')
            ->select('s.*, u.username, u.foto_profil, ow.nama_ayah, ow.nik_ayah, ow.tahun_lahir_ayah, ow.pendidikan_ayah, ow.pekerjaan_ayah, ow.penghasilan_ayah, ow.nama_ibu, ow.nik_ibu, ow.tahun_lahir_ibu, ow.pendidikan_ibu, ow.pekerjaan_ibu, ow.penghasilan_ibu, ow.nama_wali, ow.nik_wali, ow.tahun_lahir_wali, ow.pendidikan_wali, ow.pekerjaan_wali, ow.penghasilan_wali, ow.no_hp_ortu, ow.email_ortu, ow.alamat_orangtua')
            ->join('users u', 'u.id = s.user_id', 'left')
            ->join('orangtua_wali ow', 'ow.siswa_id = s.id', 'left')
            ->where('s.id', $id)
            ->get()->getRowArray();

        return $this->response->setJSON($siswa);
    }

    /**
     * API: Update Data Siswa (Wali Kelas)
     * Proteksi: Hanya bisa update siswa di kelas perwaliannya sendiri.
     */
    public function update($id)
    {
        $db = \Config\Database::connect();
        $userId = session()->get('user_id');
        $siswaModel = new SiswaModel();

        // 1. Identifikasi Guru & Tahun Ajaran
        $guru = $db->table('guru_tendik')->where('user_id', $userId)->get()->getRowArray();
        $ta_aktif = $db->table('tahun_ajaran')->where('status', 'Aktif')->get()->getRowArray();

        if (!$guru || !$ta_aktif) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Sesi tidak valid. Silakan login kembali.']);
        }

        // 2. Identifikasi Rombel Perwalian
        $rombel = $db->table('rombel')
            ->where('wali_kelas_id', $guru['id'])
            ->where('id_tahun_ajaran', $ta_aktif['id'])
            ->get()->getRowArray();

        if (!$rombel) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Anda tidak memiliki hak akses Wali Kelas.']);
        }

        // 3. Verifikasi Kepemilikan Siswa
        $isAnggota = $db->table('anggota_rombel')
            ->where('siswa_id', $id)
            ->where('rombel_id', $rombel['id'])
            ->where('tahun_ajaran_id', $ta_aktif['id'])
            ->countAllResults();

        if ($isAnggota == 0) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Gagal! Siswa bukan bagian dari kelas Anda.']);
        }

        // 4. Proses Validasi & Update (Adaptasi dari SiswaController Admin)
        $getNull = function ($key) {
            $val = $this->request->getPost($key);
            return ($val === '' || $val === null) ? null : $val;
        };

        $dataSiswa = [
            'nis' => $this->request->getPost('nis'),
            'nisn' => $getNull('nisn'),
            'nik' => $getNull('nik'),
            'nama_lengkap' => $this->request->getPost('nama_lengkap'),
            'jenis_kelamin' => $getNull('jenis_kelamin'),
            'tempat_lahir' => $getNull('tempat_lahir'),
            'tanggal_lahir' => $getNull('tanggal_lahir'),
            'agama' => $getNull('agama'),
            'no_kk' => $getNull('no_kk'),
            'no_registrasi_akta' => $getNull('no_registrasi_akta'),
            'status_dalam_keluarga' => $getNull('status_dalam_keluarga'),
            'anak_ke' => $getNull('anak_ke'),
            'jml_saudara_kandung' => $getNull('jml_saudara_kandung'),
            'kebutuhan_khusus' => $getNull('kebutuhan_khusus'),
            'berat_badan' => $getNull('berat_badan'),
            'tinggi_badan' => $getNull('tinggi_badan'),
            'lingkar_kepala' => $getNull('lingkar_kepala'),
            'alamat_siswa' => $getNull('alamat_siswa'),
            'rt' => $getNull('rt'),
            'rw' => $getNull('rw'),
            'dusun' => $getNull('dusun'),
            'kelurahan' => $getNull('kelurahan'),
            'kecamatan' => $getNull('kecamatan'),
            'kode_pos' => $getNull('kode_pos'),
            'jenis_tinggal' => $getNull('jenis_tinggal'),
            'alat_transportasi' => $getNull('alat_transportasi'),
            'jarak_ke_sekolah' => $getNull('jarak_ke_sekolah'),
            'no_telp_rumah' => $getNull('no_telp_rumah'),
            'no_hp' => $getNull('no_hp'),
            'email_siswa' => $getNull('email_siswa'),
            'asal_sekolah' => $getNull('asal_sekolah'),
            'skhun' => $getNull('skhun'),
            'no_peserta_un' => $getNull('no_peserta_un'),
            'no_seri_ijazah' => $getNull('no_seri_ijazah'),
            'diterima_dikelas' => $getNull('diterima_dikelas'),
            'tgl_diterima' => $getNull('tgl_diterima'),
            'ekskul_1' => $getNull('ekskul_1'),
            'ekskul_2' => $getNull('ekskul_2'),
            'ekskul_3' => $getNull('ekskul_3'),
            'status_siswa' => $this->request->getPost('status_siswa') ?: 'Aktif'
        ];

        // Validasi Duplikasi (Kecuali record sendiri)
        if (!empty($dataSiswa['nisn']) && $siswaModel->where('nisn', $dataSiswa['nisn'])->where('id !=', $id)->countAllResults() > 0) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'NISN sudah digunakan siswa lain.']);
        }
        if (!empty($dataSiswa['nik']) && $siswaModel->where('nik', $dataSiswa['nik'])->where('id !=', $id)->countAllResults() > 0) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'NIK sudah digunakan siswa lain.']);
        }

        $db->transBegin();
        try {
            // Update Siswa
            $siswaModel->update($id, $dataSiswa);

            // Update Foto (Jika ada)
            $fileFoto = $this->request->getFile('photo');
            if ($fileFoto && $fileFoto->isValid() && !$fileFoto->hasMoved()) {
                $path = FCPATH . 'assets/uploads/avatars/';
                $newName = $fileFoto->getRandomName();
                $namaFotoWebp = pathinfo($newName, PATHINFO_FILENAME) . '.webp';
                
                \Config\Services::image()
                    ->withFile($fileFoto->getTempName())
                    ->convert(IMAGETYPE_WEBP)
                    ->save($path . $namaFotoWebp, 75);

                $db->table('users')->where('id', $db->table('siswa')->where('id', $id)->get()->getRowArray()['user_id'])->update(['foto_profil' => $namaFotoWebp]);
                $siswaModel->update($id, ['foto_siswa' => $namaFotoWebp]);
            }

            // Update Ortu
            $hpOrtu = $this->request->getPost('no_hp_ortu');
            $dataOrtu = [
                'nama_ayah' => $getNull('nama_ayah') ?: '-',
                'nik_ayah' => $getNull('nik_ayah'),
                'tahun_lahir_ayah' => $getNull('tahun_lahir_ayah'),
                'pendidikan_ayah' => $getNull('pendidikan_ayah'),
                'pekerjaan_ayah' => $getNull('pekerjaan_ayah') ?: '-',
                'penghasilan_ayah' => $getNull('penghasilan_ayah'),
                'nama_ibu' => $getNull('nama_ibu') ?: '-',
                'nik_ibu' => $getNull('nik_ibu'),
                'tahun_lahir_ibu' => $getNull('tahun_lahir_ibu'),
                'pendidikan_ibu' => $getNull('pendidikan_ibu'),
                'pekerjaan_ibu' => $getNull('pekerjaan_ibu') ?: '-',
                'penghasilan_ibu' => $getNull('penghasilan_ibu'),
                'nama_wali' => $getNull('nama_wali') ?: '-',
                'nik_wali' => $getNull('nik_wali'),
                'tahun_lahir_wali' => $getNull('tahun_lahir_wali'),
                'pendidikan_wali' => $getNull('pendidikan_wali'),
                'pekerjaan_wali' => $getNull('pekerjaan_wali') ?: '-',
                'penghasilan_wali' => $getNull('penghasilan_wali'),
                'no_hp_ortu' => $hpOrtu,
                'email_ortu' => $getNull('email_ortu'),
                'alamat_orangtua' => $getNull('alamat_orangtua')
            ];

            $db->table('orangtua_wali')->where('siswa_id', $id)->update($dataOrtu);

            $db->transCommit();
            return $this->response->setJSON(['status' => 'success', 'message' => 'Biodata siswa berhasil diperbarui.']);
        } catch (\Exception $e) {
            $db->transRollback();
            return $this->response->setJSON(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }
}