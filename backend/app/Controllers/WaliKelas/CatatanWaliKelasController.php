<?php
namespace App\Controllers\WaliKelas;

use App\Controllers\WaliKelasBaseController;

class CatatanWaliKelasController extends WaliKelasBaseController
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
        return 16; // Sandaran ke Kelas Granit untuk tujuan ujian UI
    }

    public function index(): string
    {
        $db = \Config\Database::connect();
        $rombel_id = $this->getRombelIdWaliKelas();
        
        $sekolah = $db->table('sekolah')->select('warna_primary, warna_secondary')->get()->getRowArray();
        $color = [
            'warna_primary'   => $sekolah ? $sekolah['warna_primary'] : '#10b981',
            'warna_secondary' => $sekolah ? $sekolah['warna_secondary'] : '#ecfdf5',
        ];

        // 1. Dapatkan Senarai Pelajar
        $students = $db->table('siswa')
                       ->select('id, nama_lengkap as name, nisn')
                       ->where('rombel_id', $rombel_id)
                       ->where('status_siswa', 'Aktif')
                       ->get()->getResultArray();

        // 2. Dapatkan Catatan Wali Kelas dari Jadual 'catatan_rapor'
        $catatanData = [];
        if ($db->tableExists('catatan_rapor')) {
            $catatanData = $db->table('catatan_rapor')
                ->select('catatan_rapor.*, siswa.nama_lengkap as studentName')
                ->join('siswa', 'siswa.id = catatan_rapor.siswa_id')
                ->where('siswa.rombel_id', $rombel_id) // PERBAIKAN: Tapis melalui jadual siswa
                ->orderBy('catatan_rapor.id', 'DESC')
                ->get()->getResultArray();
        }

        $formattedCatatan = [];
        foreach ($catatanData as $c) {
            // Format Data: Priority | Category | Status | Note | FollowUp
            $parts = explode('|', $c['catatan_wali_kelas'] ?? '');
            
            // Sokongan sekiranya data lama wujud tanpa simbol '|'
            if (count($parts) < 2) {
                $priority = 'Sedang';
                $category = 'Akademik';
                $status   = 'Baru';
                $note     = $c['catatan_wali_kelas'];
                $followUp = '- Belum Ada -';
            } else {
                $priority = $parts[0] ?? 'Sedang';
                $category = $parts[1] ?? 'Akademik';
                $status   = $parts[2] ?? 'Baru';
                $note     = $parts[3] ?? '';
                $followUp = $parts[4] ?? '- Belum Ada -'; 
            }

            $formattedCatatan[] = [
                'id'          => $c['id'],
                'studentId'   => $c['siswa_id'],
                'studentName' => $c['studentName'],
                'date'        => date('Y-m-d', strtotime($c['created_at'] ?? 'now')),
                'category'    => $category,
                'priority'    => $priority,
                'status'      => $status,
                'note'        => $note,
                'followUp'    => $followUp
            ];
        }

        $data = [
            'title'       => 'Catatan Wali Kelas',
            'user'        => session()->get('nama_lengkap') ?? 'Wali Kelas',
            'navigations' => $this->getSidebarMenu(),
            'color'       => $color,
            'students'    => json_encode($students),
            'catatan'     => json_encode($formattedCatatan)
        ];
        
        return view('WaliKelas/catatan-walikelas', $data); 
    }

    // ==========================================
    // API Fungsi Simpan & Padam
    // ==========================================
    public function saveCatatan() {
        try {
            $json = $this->request->getJSON();
            $db = \Config\Database::connect();
            
            // Bentuk format gabungan dipisahkan tanda |
            $string_catatan = $json->priority . '|' . $json->category . '|' . $json->status . '|' . $json->note . '|' . $json->followUp;

            // PERBAIKAN: Padam rombel_id & tanggapan_orang_tua kerana ia tiada dalam jadual ini
            $data = [
                'siswa_id'           => $json->studentId,
                'tahun_ajaran'       => session()->get('tahun_ajaran') ?? '2024/2025',
                'semester'           => session()->get('semester') ?? 'Ganjil',
                'catatan_wali_kelas' => $string_catatan
            ];

            if (isset($json->id) && $json->id !== null) {
                $db->table('catatan_rapor')->where('id', $json->id)->update($data);
            } else {
                $db->table('catatan_rapor')->insert($data);
            }

            return $this->response->setJSON(['success' => true]);

        } catch (\Exception $e) {
            return $this->response->setStatusCode(500)->setJSON(['error' => $e->getMessage()]);
        }
    }

    public function deleteCatatan() {
        try {
            $json = $this->request->getJSON();
            if (isset($json->id)) {
                \Config\Database::connect()->table('catatan_rapor')->where('id', $json->id)->delete();
                return $this->response->setJSON(['success' => true]);
            }
            return $this->response->setJSON(['success' => false]);
        } catch (\Exception $e) {
            return $this->response->setStatusCode(500)->setJSON(['error' => $e->getMessage()]);
        }
    }
}