<?php

namespace App\Controllers\WaliKelas;

use App\Controllers\WaliKelasBaseController;
use App\Models\Admin\SiswaModel; 
use App\Models\AbsensiHarianModel;

class AbsensiKelasController extends WaliKelasBaseController
{
    public function index(): string
    {
        $db = \Config\Database::connect();
        
        $sekolah = $db->table('sekolah')->select('warna_primary, warna_secondary')->get()->getRowArray();
        $warna_primary = $sekolah ? $sekolah['warna_primary'] : '#10b981';
        $warna_secondary = $sekolah ? $sekolah['warna_secondary'] : '#ecfdf5';

        $color = [
            'warna_primary'   => $warna_primary,
            'warna_secondary' => $warna_secondary,
            'warna_hadir'     => '#16a34a',
            'warna_sakit'     => '#ca8a04',
            'warna_izin'      => '#9333ea',
            'warna_alpha'     => '#dc2626'
        ];

        $data = [
            'title'       => 'Absensi Kelas Harian',
            'user'        => session()->get('nama_lengkap') ?? 'Wali Kelas',
            'navigations' => $this->getSidebarMenu(),
            'color'       => $color
        ];
        
        return view('WaliKelas/absensi-kelas', $data); 
    }

    private function getRombelIdWaliKelas()
    {
        $db = \Config\Database::connect();
        $userId = session()->get('user_id');

        $guru = $db->table('guru_tendik')->where('user_id', $userId)->get()->getRowArray();
        
        if ($guru) {
            $rombel = $db->table('rombel')
                         ->where('wali_kelas_id', $guru['id'])
                         ->where('tahun_ajaran', session()->get('tahun_ajaran') ?? '2024/2025')
                         ->where('semester', session()->get('semester') ?? 'Ganjil')
                         ->get()->getRowArray();
            
            if ($rombel) {
                return $rombel['id'];
            }
        }
        return null;
    }

    public function getAbsensiData()
    {
        $rombel_id = $this->getRombelIdWaliKelas();

        if (!$rombel_id) {
            return $this->response->setJSON(['students' => [], 'attendance' => []]);
        }

        $siswaModel = new SiswaModel();
        $absensiModel = new AbsensiHarianModel();

        $students = $siswaModel->where('rombel_id', $rombel_id)
                               ->where('status_siswa', 'Aktif')
                               ->findAll();
        
        $absensiRecords = $absensiModel->where('rombel_id', $rombel_id)
                                       ->orderBy('tanggal', 'ASC')
                                       ->findAll();

        $attendanceMap = [];
        foreach ($absensiRecords as $absen) {
            $tanggal = $absen['tanggal'];
            if (!isset($attendanceMap[$tanggal])) {
                $attendanceMap[$tanggal] = ['date' => $tanggal, 'records' => []];
            }
            
            $kode = 'A'; 
            if ($absen['status'] === 'Hadir') $kode = 'H';
            elseif ($absen['status'] === 'Sakit') $kode = 'S';
            elseif ($absen['status'] === 'Izin') $kode = 'I';
            elseif ($absen['status'] === 'Alpha') $kode = 'A';

            $attendanceMap[$tanggal]['records'][$absen['siswa_id']] = $kode;
        }

        $studentList = [];
        foreach ($students as $s) {
            $studentList[] = [
                'id'   => $s['id'],
                'name' => $s['nama_lengkap'], 
                'nisn' => $s['nisn'] ?? $s['nis'] ?? '-'
            ];
        }

        return $this->response->setJSON([
            'students'   => $studentList,
            'attendance' => array_values($attendanceMap) 
        ]);
    }

    public function saveAbsensi()
    {
        $json = $this->request->getJSON();
        $tanggal = $json->date;
        $records = $json->records;
        
        $rombel_id = $this->getRombelIdWaliKelas();

        if (!$rombel_id) {
            return $this->response->setJSON(['success' => false, 'message' => 'Akses ditolak.']);
        }

        $absensiModel = new AbsensiHarianModel();

        foreach ($records as $siswa_id => $statusKode) {
            $statusDb = 'Alpha';
            if ($statusKode === 'H') $statusDb = 'Hadir';
            elseif ($statusKode === 'S') $statusDb = 'Sakit';
            elseif ($statusKode === 'I') $statusDb = 'Izin';
            elseif ($statusKode === 'A') $statusDb = 'Alpha';

            $existing = $absensiModel->where([
                'siswa_id'  => $siswa_id,
                'rombel_id' => $rombel_id,
                'tanggal'   => $tanggal
            ])->first();

            if ($existing) {
                $absensiModel->update($existing['id'], ['status' => $statusDb]);
            } else {
                $absensiModel->insert([
                    'siswa_id'  => $siswa_id,
                    'rombel_id' => $rombel_id,
                    'tanggal'   => $tanggal,
                    'status'    => $statusDb
                ]);
            }
        }

        return $this->response->setJSON(['success' => true, 'message' => 'Data tersimpan!']);
    }
}