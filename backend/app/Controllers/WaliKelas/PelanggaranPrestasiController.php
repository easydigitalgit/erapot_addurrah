<?php
namespace App\Controllers\WaliKelas;

use App\Controllers\WaliKelasBaseController;

class PelanggaranPrestasiController extends WaliKelasBaseController
{
    private function getGuruId() {
        $db = \Config\Database::connect();
        $guru = $db->table('guru_tendik')->where('user_id', session()->get('user_id'))->get()->getRowArray();
        return $guru ? $guru['id'] : null;
    }

    private function getRombelIdWaliKelas() {
        $db = \Config\Database::connect();
        $guru_id = $this->getGuruId();
        
        $tahun_ajaran = session()->get('tahun_ajaran') ?? '2024/2025';
        $semester = session()->get('semester') ?? 'Ganjil';

        if ($guru_id) {
            // Logika Pintar Pencarian Tahun Ajaran
            $ta_aktif = $db->table('tahun_ajaran')->where('tahun', $tahun_ajaran)->where('semester', $semester)->get()->getRowArray();
            if(!$ta_aktif) $ta_aktif = $db->table('tahun_ajaran')->where('status', 'Aktif')->get()->getRowArray();
            $id_ta = $ta_aktif ? $ta_aktif['id'] : 0;

            $rombel = $db->table('rombel')
                         ->where('wali_kelas_id', $guru_id)
                         ->where('id_tahun_ajaran', $id_ta)
                         ->get()->getRowArray();
            
                         
            if ($rombel) return $rombel['id'];
        }
        return 0;
    }

    public function index(): string
    {
        $db = \Config\Database::connect();
        
        $sekolah = $db->table('sekolah')->select('nama_sekolah, warna_primary, warna_secondary')->get()->getRowArray();
        $nama_sekolah = $sekolah ? $sekolah['nama_sekolah'] : 'SMPIT Ad Durrah';
        $color = [
            'warna_primary'   => $sekolah ? $sekolah['warna_primary'] : '#10b981',
            'warna_secondary' => $sekolah ? $sekolah['warna_secondary'] : '#ecfdf5',
        ];

        $rombel_id = $this->getRombelIdWaliKelas();
        $students = [];
        $pelanggaran = [];
        $prestasi = [];

        if ($rombel_id > 0) {
            // --- 🚀 AMBIL TA & SEMESTER UNTUK MESIN WAKTU ---
            $tahun_ajaran = session()->get('tahun_ajaran') ?? '2024/2025';
            $semester = session()->get('semester') ?? 'Ganjil';
            $ta_aktif = $db->table('tahun_ajaran')->where('tahun', $tahun_ajaran)->where('semester', $semester)->get()->getRowArray();
            if(!$ta_aktif) $ta_aktif = $db->table('tahun_ajaran')->where('status', 'Aktif')->get()->getRowArray();
            
            $id_ta = $ta_aktif ? $ta_aktif['id'] : 0;
            $semester_aktif = $ta_aktif ? $ta_aktif['semester'] : 'Ganjil';

            // --- 🚀 MENGGUNAKAN MESIN WAKTU (anggota_rombel) ---
            $students = $db->table('anggota_rombel ar')
                           ->select('siswa.id, siswa.nama_lengkap as name, siswa.nisn')
                           ->join('siswa', 'siswa.id = ar.siswa_id')
                           ->where('ar.rombel_id', $rombel_id)
                           ->where('ar.tahun_ajaran_id', $id_ta)
                           ->where('ar.semester', $semester_aktif)
                           ->where('siswa.status_siswa', 'Aktif')
                           ->orderBy('siswa.nama_lengkap', 'ASC')
                           ->get()->getResultArray();

            if ($db->tableExists('catatan_akhlak')) {
                $catatanList = $db->table('catatan_akhlak')
                    ->select('catatan_akhlak.*, siswa.nama_lengkap as studentName, guru_tendik.nama_lengkap as teacher')
                    ->join('siswa', 'siswa.id = catatan_akhlak.siswa_id')
                    ->join('guru_tendik', 'guru_tendik.id = catatan_akhlak.guru_id', 'left')
                    ->where('catatan_akhlak.rombel_id', $rombel_id)
                    ->orderBy('catatan_akhlak.tanggal', 'DESC')
                    ->get()->getResultArray();

                foreach ($catatanList as $c) {
                    $kategori_mentah = $c['kategori_akhlak'] ?? '';
                    $parts = explode('|', $kategori_mentah);
                    $type = trim($parts[0] ?? '');

                    if ($type === 'Pelanggaran') {
                        $pelanggaran[] = [
                            'id'          => $c['id'], 
                            'studentId'   => $c['siswa_id'], 
                            'studentName' => $c['studentName'],
                            'category'    => trim($parts[1] ?? 'Lain-lain'), 
                            'severity'    => trim($parts[2] ?? 'ringan'),
                            'points'      => (int)($parts[3] ?? 5), 
                            'description' => $c['catatan'],
                            'date'        => date('Y-m-d', strtotime($c['tanggal'])),
                            'teacher'     => $c['teacher'] ?: 'Wali Kelas', 
                            'status'      => $c['status_pembinaan']
                        ];
                    } elseif ($type === 'Prestasi') {
                        $prestasi[] = [
                            'id'          => $c['id'], 
                            'studentId'   => $c['siswa_id'], 
                            'studentName' => $c['studentName'],
                            'category'    => trim($parts[1] ?? 'Akademik'), 
                            'points'      => (int)($parts[2] ?? 10),
                            'achievement' => $c['catatan'], 
                            'reward'      => $c['tindak_lanjut'],
                            'date'        => date('Y-m-d', strtotime($c['tanggal']))
                        ];
                    } else {
                        if (stripos($kategori_mentah, 'Teguran') !== false || stripos($kategori_mentah, 'Kurang') !== false || stripos($kategori_mentah, 'Perlu Pembinaan') !== false || stripos($kategori_mentah, 'Absensi') !== false) {
                            $pelanggaran[] = [
                                'id'          => $c['id'], 
                                'studentId'   => $c['siswa_id'], 
                                'studentName' => $c['studentName'],
                                'category'    => $kategori_mentah ?: 'Perilaku', 
                                'severity'    => 'sedang', 
                                'points'      => 10,
                                'description' => $c['catatan'], 
                                'date'        => date('Y-m-d', strtotime($c['tanggal'])),
                                'teacher'     => $c['teacher'] ?: 'Wali Kelas', 
                                'status'      => $c['status_pembinaan'] ?: 'Proses'
                            ];
                        } else {
                            $prestasi[] = [
                                'id'          => $c['id'], 
                                'studentId'   => $c['siswa_id'], 
                                'studentName' => $c['studentName'],
                                'category'    => 'Akademik/Sikap', 
                                'points'      => 15, 
                                'achievement' => $kategori_mentah ?: 'Sikap Baik',
                                'reward'      => $c['tindak_lanjut'] ?: 'Apresiasi Lisan', 
                                'date'        => date('Y-m-d', strtotime($c['tanggal'])),
                                'description' => $c['catatan']
                            ];
                        }
                    }
                }
            }
        }

        $data = [
            'title'        => 'Pelanggaran & Prestasi',
            'user'         => session()->get('nama_lengkap') ?? 'Wali Kelas',
            'nama_sekolah' => $nama_sekolah,
            'navigations'  => $this->getSidebarMenu(),
            'color'        => $color,
            'students'     => json_encode($students),
            'pelanggaran'  => json_encode($pelanggaran),
            'prestasi'     => json_encode($prestasi)
        ];
        
        return view('WaliKelas/pelanggaran-prestasi', $data); 
    }

    public function savePelanggaran() {
        $json = $this->request->getJSON();
        $db = \Config\Database::connect();
        
        $data = [
            'siswa_id'         => $json->studentId,
            'guru_id'          => $this->getGuruId() ?? 1,
            'rombel_id'        => $this->getRombelIdWaliKelas(),
            'kategori_akhlak'  => 'Pelanggaran|' . ($json->category ?? '') . '|' . ($json->severity ?? '') . '|' . ($json->points ?? 0),
            'status_pembinaan' => $json->status ?? 'Proses',
            'catatan'          => $json->description ?? '',
            'tanggal'          => ($json->date ?? date('Y-m-d')) . ' ' . date('H:i:s')
        ];

        if (isset($json->id) && $json->id !== null && $json->id != "") {
            $db->table('catatan_akhlak')->where('id', $json->id)->update($data);
        } else {
            $db->table('catatan_akhlak')->insert($data);
        }
        return $this->response->setJSON(['success' => true]);
    }

    public function savePrestasi() {
        $json = $this->request->getJSON();
        $db = \Config\Database::connect();
        
        $data = [
            'siswa_id'        => $json->studentId,
            'guru_id'         => $this->getGuruId() ?? 1,
            'rombel_id'       => $this->getRombelIdWaliKelas(),
            'kategori_akhlak' => 'Prestasi|' . ($json->category ?? '') . '|' . ($json->points ?? 0),
            'tindak_lanjut'   => $json->reward ?? '',
            'catatan'         => $json->achievement ?? '',
            'tanggal'         => ($json->date ?? date('Y-m-d')) . ' ' . date('H:i:s')
        ];

        if (isset($json->id) && $json->id !== null && $json->id != "") {
            $db->table('catatan_akhlak')->where('id', $json->id)->update($data);
        } else {
            $db->table('catatan_akhlak')->insert($data);
        }
        return $this->response->setJSON(['success' => true]);
    }

    public function deleteRecord() {
        $json = $this->request->getJSON();
        if (isset($json->id)) {
            \Config\Database::connect()->table('catatan_akhlak')->where('id', $json->id)->delete();
            return $this->response->setJSON(['success' => true]);
        }
        return $this->response->setJSON(['success' => false, 'message' => 'ID tidak ditemukan']);
    }
}