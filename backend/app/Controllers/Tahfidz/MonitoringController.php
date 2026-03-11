<?php

namespace App\Controllers\Tahfidz;

use App\Controllers\TahfidzBaseController;

class MonitoringController extends TahfidzBaseController
{
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    public function index(): string
    {
        // Ambil data kelas untuk filter dropdown
        $rombels = $this->db->table('rombel')
                            ->select('id, nama_rombel')
                            ->orderBy('nama_rombel', 'ASC')
                            ->get()
                            ->getResultArray();

        $data = [
            'user'        => session()->get('username') ?? 'Guru Tahfidz',
            'navigations' => $this->getSidebarMenu(),
            'color'       => $this->getColor(),
            'rombels'     => $rombels
        ];

        return view('tahfidz/monitoring/index', $data);
    }

    // Fungsi AJAX untuk mengambil rekap hafalan per kelas
    // Fungsi AJAX untuk mengambil rekap hafalan per kelas
    public function getMonitoringData()
    {
        $rombel_id = $this->request->getGet('rombel_id');

        if (!$rombel_id) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Pilih kelas terlebih dahulu.']);
        }

        $siswa = $this->db->table('siswa')
                          ->select('id, nama_lengkap, nis')
                          ->where('rombel_id', $rombel_id)
                          ->orderBy('nama_lengkap', 'ASC')
                          ->get()
                          ->getResultArray();

        $monitoringData = [];

        foreach ($siswa as $s) {
            $total_setor = $this->db->table('setoran_tahfidz')
                                    ->where('siswa_id', $s['id'])
                                    ->countAllResults();

            $hafalan_terakhir = $this->db->table('setoran_tahfidz')
                                         ->where('siswa_id', $s['id'])
                                         ->orderBy('tanggal', 'DESC')
                                         ->orderBy('created_at', 'DESC')
                                         ->limit(1)
                                         ->get()
                                         ->getRowArray();

            // BARU: Ambil 5 riwayat predikat terakhir untuk Habit Tracker (Grafik Titik)
            $riwayat_5 = $this->db->table('setoran_tahfidz')
                                  ->select('predikat')
                                  ->where('siswa_id', $s['id'])
                                  ->orderBy('tanggal', 'DESC')
                                  ->orderBy('created_at', 'DESC')
                                  ->limit(5)
                                  ->get()
                                  ->getResultArray();

            $monitoringData[] = [
                'id'               => $s['id'],
                'nama_lengkap'     => $s['nama_lengkap'],
                'nis'              => $s['nis'],
                'total_setor'      => $total_setor,
                'surah_terakhir'   => $hafalan_terakhir ? $hafalan_terakhir['surah'] : '-',
                'ayat_terakhir'    => $hafalan_terakhir ? $hafalan_terakhir['ayat'] : '-',
                'predikat_terakhir'=> $hafalan_terakhir ? $hafalan_terakhir['predikat'] : '-',
                'riwayat_5'        => array_column($riwayat_5, 'predikat') // Kirim array predikat ke View
            ];
        }

        return $this->response->setJSON(['status' => 'success', 'data' => $monitoringData]);
    }

    // Fungsi AJAX untuk mengambil detail riwayat 10 setoran terakhir per santri
    public function getRiwayat()
    {
        $siswa_id = $this->request->getGet('siswa_id');

        if (!$siswa_id) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'ID Siswa tidak ditemukan.']);
        }

        // Ambil nama santri
        $siswa = $this->db->table('siswa')->select('nama_lengkap')->where('id', $siswa_id)->get()->getRowArray();

        // Ambil 10 riwayat setoran terakhir
        $riwayat = $this->db->table('setoran_tahfidz')
                            ->where('siswa_id', $siswa_id)
                            ->orderBy('tanggal', 'DESC')
                            ->orderBy('created_at', 'DESC')
                            ->limit(10)
                            ->get()
                            ->getResultArray();

        return $this->response->setJSON([
            'status' => 'success', 
            'siswa'  => $siswa ? $siswa['nama_lengkap'] : 'Santri',
            'data'   => $riwayat
        ]);
    }
}