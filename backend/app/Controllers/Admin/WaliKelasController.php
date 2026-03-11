<?php

namespace App\Controllers\Admin;

use App\Controllers\AdminBaseController;
use App\Models\Admin\RombelModel;
use App\Models\Admin\GuruTendikModel; // Pastikan model ini ada

class WaliKelasController extends AdminBaseController
{
    protected $rombelModel;
    protected $guruModel;

    public function __construct()
    {
        $this->rombelModel = new RombelModel();
        $this->guruModel   = new GuruTendikModel();
    }

    public function index(): string
    {
        // 1. Ambil Data Rombel
        $rawRombel = $this->rombelModel
            ->select('rombel.*, guru_tendik.nama_lengkap as nama_wali, guru_tendik.nik')
            ->join('guru_tendik', 'guru_tendik.id = rombel.wali_kelas_id', 'left')
            ->orderBy('rombel.tingkat', 'ASC')
            ->orderBy('rombel.nama_rombel', 'ASC')
            ->findAll();

        // 2. Ambil Data Guru (HAPUS filter 'where jabatan' sementara agar semua muncul)
        // Kalau nanti mau filter, pastikan tulisannya sama persis dengan di database (misal 'Guru' vs 'GURU')
        $guruList = $this->guruModel->orderBy('nama_lengkap', 'ASC')->findAll(); 

        // ... (Sisa kode ke bawah SAMA SEPERTI SEBELUMNYA) ...

        // 3. Hitung Statistik
        $totalRombel = count($rawRombel);
        $assigned    = 0;
        $activeWali  = 0; // Menghitung guru unik
        $waliIds     = [];

        // 4. Format Data untuk JS
        $formattedData = array_map(function($row) use (&$assigned, &$waliIds) {
            $isAssigned = !empty($row['wali_kelas_id']);
            
            if ($isAssigned) {
                $assigned++;
                if (!in_array($row['wali_kelas_id'], $waliIds)) {
                    $waliIds[] = $row['wali_kelas_id'];
                }
            }

            // Pecah Nama Rombel (Misal "VII-A" jadi Level: VII, Rombel: A)
            // Asumsi format di DB adalah "VII-A" atau "7-A"
            $parts = explode('-', $row['nama_rombel']);
            $rombelKode = isset($parts[1]) ? $parts[1] : $row['nama_rombel'];

            return [
                'id'          => $row['id'],
                'level'       => $row['tingkat'], // VII, VIII, IX
                'rombel'      => $rombelKode,     // A, B, C
                'full_rombel' => $row['nama_rombel'],
                'teacher'     => $row['nama_wali'] ?? '',
                'teacher_id'  => $row['wali_kelas_id'] ?? '',
                'nip'         => $row['nip'] ?? '-',
                'tahunAjaran' => '2024/2025', // Bisa diambil dari session/config
                'students'    => 32, // Nanti ganti dengan count siswa real
                'status'      => $isAssigned ? 'assigned' : 'unassigned'
            ];
        }, $rawRombel);

        $stats = [
            'total'      => $totalRombel,
            'assigned'   => $assigned,
            'unassigned' => $totalRombel - $assigned,
            'active'     => count($waliIds)
        ];

        $data = [
            'user'        => 'Admin',
            'navigations' => $this->getSidebarMenu(),
            'color'       => $this->getColor(),
            'waliKelasData' => $formattedData, // Data untuk Tabel JS
            'guruList'      => $guruList,      // Data untuk Dropdown Modal
            'stats'         => $stats          // Data untuk Kotak Atas
        ];
        
        return view('admin/wali-kelas', $data); 
    }

    // Assign / Update Wali Kelas
    public function update()
    {
        $rombelId = $this->request->getPost('assign_rombel_id'); // ID Rombel
        $guruId   = $this->request->getPost('assign_guru');      // ID Guru

        // Jika pakai form ganti (modal kedua), name inputnya beda
        if (!$guruId) {
            $rombelId = $this->request->getPost('change_rombel_id');
            $guruId   = $this->request->getPost('change_guru');
        }

        if ($this->rombelModel->update($rombelId, ['wali_kelas_id' => $guruId])) {
            return $this->response->setJSON(['status' => 'success', 'message' => 'Wali kelas berhasil ditetapkan']);
        }

        return $this->response->setJSON(['status' => 'error', 'message' => 'Gagal menyimpan data']);
    }

    // Hapus Wali Kelas (Set Null)
    public function delete()
    {
        $rombelId = $this->request->getPost('rombel_id');

        if ($this->rombelModel->update($rombelId, ['wali_kelas_id' => null])) {
            return $this->response->setJSON(['status' => 'success', 'message' => 'Penugasan berhasil dilepas']);
        }

        return $this->response->setJSON(['status' => 'error', 'message' => 'Gagal melepas penugasan']);
    }
}