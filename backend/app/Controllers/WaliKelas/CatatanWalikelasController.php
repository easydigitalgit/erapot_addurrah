<?php
namespace App\Controllers\WaliKelas;

use App\Controllers\WaliKelasBaseController;

// PERBAIKAN: Huruf 'K' diubah menjadi 'k' kecil agar cocok dengan Routes dan Hosting Linux
class CatatanWalikelasController extends WaliKelasBaseController
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
        $rombel_id = $this->getRombelIdWaliKelas();
        
        $sekolah = $db->table('sekolah')->select('warna_primary, warna_secondary')->get()->getRowArray();
        $color = [
            'warna_primary'   => $sekolah ? $sekolah['warna_primary'] : '#10b981',
            'warna_secondary' => $sekolah ? $sekolah['warna_secondary'] : '#ecfdf5',
        ];

        $students = [];
        $formattedCatatan = [];

        if ($rombel_id > 0) {
            // --- 🚀 SETUP MESIN WAKTU ---
            $tahun_ajaran = session()->get('tahun_ajaran') ?? '2024/2025';
            $semester = session()->get('semester') ?? 'Ganjil';
            $ta_aktif = $db->table('tahun_ajaran')->where('tahun', $tahun_ajaran)->where('semester', $semester)->get()->getRowArray();
            if(!$ta_aktif) $ta_aktif = $db->table('tahun_ajaran')->where('status', 'Aktif')->get()->getRowArray();
            $id_ta = $ta_aktif ? $ta_aktif['id'] : 0;
            $semester_aktif = $ta_aktif ? $ta_aktif['semester'] : 'Ganjil';

            // 1. 🚀 MENGGUNAKAN MESIN WAKTU (anggota_rombel)
            $students = $db->table('anggota_rombel ar')
                           ->select('siswa.id, siswa.nama_lengkap as name, siswa.nisn')
                           ->join('siswa', 'siswa.id = ar.siswa_id')
                           ->where('ar.rombel_id', $rombel_id)
                           ->where('ar.tahun_ajaran_id', $id_ta)
                           ->where('ar.semester', $semester_aktif)
                           ->where('siswa.status_siswa', 'Aktif')
                           ->orderBy('siswa.nama_lengkap', 'ASC')
                           ->get()->getResultArray();

            // 2. Dapatkan Catatan Wali Kelas
            if ($db->tableExists('catatan_rapor') && !empty($students)) {
                $siswaIds = array_column($students, 'id');
                
                $catatanData = $db->table('catatan_rapor')
                    ->select('catatan_rapor.*, siswa.nama_lengkap as studentName')
                    ->join('siswa', 'siswa.id = catatan_rapor.siswa_id')
                    ->whereIn('catatan_rapor.siswa_id', $siswaIds) 
                    ->where('catatan_rapor.tahun_ajaran', session()->get('tahun_ajaran') ?? '2024/2025')
                    ->where('catatan_rapor.semester', session()->get('semester') ?? 'Ganjil')
                    ->orderBy('catatan_rapor.id', 'DESC')
                    ->get()->getResultArray();

                foreach ($catatanData as $c) {
                    $parts = explode('|', $c['catatan_wali_kelas'] ?? '');
                    
                    if (count($parts) < 2) {
                        $priority = 'Sedang';
                        $category = 'Akademik';
                        $status   = 'Baru';
                        $note     = $c['catatan_wali_kelas'];
                        $followUp = '- Belum Ada -';
                    } else {
                        $priority = trim($parts[0] ?? 'Sedang');
                        $category = trim($parts[1] ?? 'Akademik');
                        $status   = trim($parts[2] ?? 'Baru');
                        $note     = trim($parts[3] ?? '');
                        $followUp = trim($parts[4] ?? '- Belum Ada -'); 
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
            }
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

    public function saveCatatan() {
        try {
            $json = $this->request->getJSON();
            $db = \Config\Database::connect();
            
            $priority = $json->priority ?? 'Sedang';
            $category = $json->category ?? 'Akademik';
            $status   = $json->status ?? 'Baru';
            $note     = $json->note ?? '';
            $followUp = $json->followUp ?? '-';

            $string_catatan = $priority . '|' . $category . '|' . $status . '|' . $note . '|' . $followUp;

            $data = [
                'siswa_id'           => $json->studentId,
                'tahun_ajaran'       => session()->get('tahun_ajaran') ?? '2024/2025',
                'semester'           => session()->get('semester') ?? 'Ganjil',
                'catatan_wali_kelas' => $string_catatan
            ];

            if (isset($json->id) && $json->id !== null && $json->id != '') {
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
            return $this->response->setJSON(['success' => false, 'message' => 'ID tidak ditemukan']);
        } catch (\Exception $e) {
            return $this->response->setStatusCode(500)->setJSON(['error' => $e->getMessage()]);
        }
    }
}