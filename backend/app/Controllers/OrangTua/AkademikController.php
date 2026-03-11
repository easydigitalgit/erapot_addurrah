<?php

namespace App\Controllers\OrangTua;

use App\Controllers\OrangTuaBaseController;

class AkademikController extends OrangTuaBaseController
{
    public function index()
    {
        $db = \Config\Database::connect();
        $userId = session()->get('user_id');

        // 1. CARI DATA ORANG TUA
        $orangTua = $db->table('orangtua_wali')->where('user_id', $userId)->get()->getRowArray();
        $namaOrangTua = session()->get('nama_lengkap') ?? session()->get('username');
        if ($orangTua) {
            $namaOrangTua = $orangTua['nama_ayah'] ?: ($orangTua['nama_ibu'] ?: ($orangTua['nama_wali'] ?: $namaOrangTua));
        }
        $siswaId = $orangTua ? $orangTua['siswa_id'] : 0;

        // 2. CARI DATA ANAK
        $anak = $db->table('siswa')
                   ->select('siswa.id, siswa.nama_lengkap, siswa.nis, rombel.nama_rombel as kelas')
                   ->join('rombel', 'rombel.id = siswa.rombel_id', 'left')
                   ->where('siswa.id', $siswaId)
                   ->get()->getRowArray();

        $nilai = [];
        $rata_rata = 0;
        $total_mapel = 0;
        
        $mapel_terkuat = null;
        $mapel_perhatian = null;
        $ekskul = [];
        $catatan_wali = null;

        if ($anak) {
            // 3. AMBIL NILAI AKADEMIK
            if ($db->tableExists('nilai_akademik')) {
                $nilai = $db->table('nilai_akademik')
                            ->select('nilai_akademik.*, mata_pelajaran.nama_mapel, mata_pelajaran.kkm')
                            ->join('mata_pelajaran', 'mata_pelajaran.id = nilai_akademik.mapel_id')
                            ->where('siswa_id', $anak['id'])
                            ->orderBy('mata_pelajaran.nama_mapel', 'ASC')
                            ->get()->getResultArray();
                            
                $total_mapel = count($nilai);
                if ($total_mapel > 0) {
                    $total_nilai = array_sum(array_column($nilai, 'nilai_angka'));
                    $rata_rata = round($total_nilai / $total_mapel, 1);

                    // Cari Mapel Terkuat & Perlu Perhatian (Berdasarkan nilai angka)
                    $nilai_sorted = $nilai;
                    usort($nilai_sorted, function($a, $b) {
                        return $b['nilai_angka'] <=> $a['nilai_angka'];
                    });
                    $mapel_terkuat = $nilai_sorted[0]; // Paling atas (tertinggi)
                    $mapel_perhatian = end($nilai_sorted); // Paling bawah (terendah)
                }
            }

            // 4. AMBIL DATA EKSTRAKURIKULER
            if ($db->tableExists('nilai_ekskul')) {
                $ekskul = $db->table('nilai_ekskul')
                             ->where('siswa_id', $anak['id'])
                             ->get()->getResultArray();
            }

            // 5. AMBIL CATATAN WALI KELAS
            if ($db->tableExists('catatan_rapor')) {
                $catatan_wali = $db->table('catatan_rapor')
                                   ->where('siswa_id', $anak['id'])
                                   ->get()->getRowArray();
            }
        }

        $data = [
            'title'           => 'Akademik Ananda',
            'user'            => $namaOrangTua,
            'color'           => $this->getColor(),
            'navigations'     => $this->getSidebarMenu(),
            'anak'            => $anak,
            'nilai'           => $nilai,
            'rata_rata'       => $rata_rata,
            'total_mapel'     => $total_mapel,
            'mapel_terkuat'   => $mapel_terkuat,
            'mapel_perhatian' => $mapel_perhatian,
            'ekskul'          => $ekskul,
            'catatan_wali'    => $catatan_wali
        ];

        return view('OrangTua/akademik', $data);
    }
}