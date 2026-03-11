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
        if ($guru_id) {
            $rombel = $db->table('rombel')
                         ->where('wali_kelas_id', $guru_id)
                         ->where('tahun_ajaran', session()->get('tahun_ajaran') ?? '2024/2025')
                         ->where('semester', session()->get('semester') ?? 'Ganjil')
                         ->get()->getRowArray();
            if ($rombel) return $rombel['id'];
        }
        return 16; // Sandaran (Fallback) kepada Rombel Granit
    }

    public function index(): string
    {
        $db = \Config\Database::connect();
        
        $sekolah = $db->table('sekolah')->select('warna_primary, warna_secondary')->get()->getRowArray();
        $color = [
            'warna_primary'   => $sekolah ? $sekolah['warna_primary'] : '#10b981',
            'warna_secondary' => $sekolah ? $sekolah['warna_secondary'] : '#ecfdf5',
        ];

        $rombel_id = $this->getRombelIdWaliKelas();

        // 1. Ambil Senarai Pelajar
        $students = $db->table('siswa')
                       ->select('id, nama_lengkap as name, nisn')
                       ->where('rombel_id', $rombel_id)
                       ->where('status_siswa', 'Aktif')
                       ->get()->getResultArray();

        $pelanggaran = [];
        $prestasi = [];

        // 2. Ambil Rekod dari catatan_akhlak dan Pisahkan kepada Pelanggaran/Prestasi
        if ($db->tableExists('catatan_akhlak')) {
            $catatanList = $db->table('catatan_akhlak')
                ->select('catatan_akhlak.*, siswa.nama_lengkap as studentName, guru_tendik.nama_lengkap as teacher')
                ->join('siswa', 'siswa.id = catatan_akhlak.siswa_id')
                ->join('guru_tendik', 'guru_tendik.id = catatan_akhlak.guru_id', 'left')
                ->where('catatan_akhlak.rombel_id', $rombel_id)
                ->orderBy('tanggal', 'DESC')
                ->get()->getResultArray();

            foreach ($catatanList as $c) {
                // Analisis format string (Pelanggaran|Kategori|Tahap|Poin atau Prestasi|Kategori|Poin)
                $parts = explode('|', $c['kategori_akhlak']);
                $type = $parts[0] ?? '';

                if ($type === 'Pelanggaran') {
                    $pelanggaran[] = [
                        'id' => $c['id'], 'studentId' => $c['siswa_id'], 'studentName' => $c['studentName'],
                        'category' => $parts[1] ?? 'Lain-lain', 'severity' => $parts[2] ?? 'ringan',
                        'points' => (int)($parts[3] ?? 5), 'description' => $c['catatan'],
                        'date' => date('Y-m-d', strtotime($c['tanggal'])),
                        'teacher' => $c['teacher'] ?: 'Wali Kelas', 'status' => $c['status_pembinaan']
                    ];
                } elseif ($type === 'Prestasi') {
                    $prestasi[] = [
                        'id' => $c['id'], 'studentId' => $c['siswa_id'], 'studentName' => $c['studentName'],
                        'category' => $parts[1] ?? 'Akademik', 'points' => (int)($parts[2] ?? 10),
                        'achievement' => $c['catatan'], 'reward' => $c['tindak_lanjut'],
                        'date' => date('Y-m-d', strtotime($c['tanggal']))
                    ];
                } else {
                    // Data lama/sedia ada (Legacy data)
                    if (stripos($c['kategori_akhlak'], 'Teguran') !== false || stripos($c['kategori_akhlak'], 'Kurang') !== false) {
                        $pelanggaran[] = [
                            'id' => $c['id'], 'studentId' => $c['siswa_id'], 'studentName' => $c['studentName'],
                            'category' => $c['kategori_akhlak'] ?: 'Perilaku', 'severity' => 'sedang', 'points' => 10,
                            'description' => $c['catatan'], 'date' => date('Y-m-d', strtotime($c['tanggal'])),
                            'teacher' => $c['teacher'] ?: 'Wali Kelas', 'status' => $c['status_pembinaan'] ?: 'Tercatat'
                        ];
                    } else {
                        $prestasi[] = [
                            'id' => $c['id'], 'studentId' => $c['siswa_id'], 'studentName' => $c['studentName'],
                            'category' => 'Akademik', 'points' => 15, 'achievement' => $c['kategori_akhlak'] ?: 'Sikap Baik',
                            'reward' => 'Apresiasi Lisan', 'date' => date('Y-m-d', strtotime($c['tanggal'])),
                            'description' => $c['catatan']
                        ];
                    }
                }
            }
        }

        $data = [
            'title'       => 'Pelanggaran & Prestasi',
            'user'        => session()->get('nama_lengkap') ?? 'Wali Kelas',
            'navigations' => $this->getSidebarMenu(),
            'color'       => $color,
            'students'    => json_encode($students),
            'pelanggaran' => json_encode($pelanggaran),
            'prestasi'    => json_encode($prestasi)
        ];
        
        return view('WaliKelas/pelanggaran-prestasi', $data); 
    }

    // ================== API ENDPOINTS (PHP) ==================

    public function savePelanggaran() {
        $json = $this->request->getJSON();
        $db = \Config\Database::connect();
        
        $data = [
            'siswa_id'         => $json->studentId,
            'guru_id'          => $this->getGuruId() ?? 1,
            'rombel_id'        => $this->getRombelIdWaliKelas(),
            // Simpan parameter bergabung guna pipe (|)
            'kategori_akhlak'  => 'Pelanggaran|' . $json->category . '|' . $json->severity . '|' . $json->points,
            'status_pembinaan' => $json->status,
            'catatan'          => $json->description,
            'tanggal'          => $json->date . ' ' . date('H:i:s')
        ];

        if (isset($json->id) && $json->id !== null) {
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
            'kategori_akhlak' => 'Prestasi|' . $json->category . '|' . $json->points,
            'tindak_lanjut'   => $json->reward,
            'catatan'         => $json->achievement,
            'tanggal'         => $json->date . ' ' . date('H:i:s')
        ];

        if (isset($json->id) && $json->id !== null) {
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
        return $this->response->setJSON(['success' => false]);
    }
}