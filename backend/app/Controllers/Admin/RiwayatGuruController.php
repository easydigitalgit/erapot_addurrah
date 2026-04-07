<?php

namespace App\Controllers\Admin;

use App\Controllers\AdminBaseController;

class RiwayatGuruController extends AdminBaseController
{
    public function index(): string
    {
        $db = \Config\Database::connect();
        
        $guruId  = $this->request->getGet('guru_id');
        $jabatan = $this->request->getGet('jabatan');
        $taId    = $this->request->getGet('tahun_ajaran_id'); // FILTER BARU
        $perPage = 10;

        // 1. Ambil Semua Guru, Tahun Ajaran, & Daftar Jabatan Unik untuk Filter
        $guruList = $db->table('guru_tendik')->select('id, nama_lengkap')->orderBy('nama_lengkap', 'ASC')->get()->getResultArray();
        $taList   = $db->table('tahun_ajaran')->select('id, tahun, semester')->orderBy('id', 'DESC')->get()->getResultArray();
        $jabList  = $db->table('riwayat_jabatan_guru')->select('jabatan')->distinct()->get()->getResultArray();

        // 2. Build Query Riwayat dengan Pagination Manual
        $page = $this->request->getVar('page') ?: 1;

        $builder = $db->table('riwayat_jabatan_guru r')
                      ->select('r.*, g.nama_lengkap, g.nik, ta.tahun as nama_tahun')
                      ->join('guru_tendik g', 'g.id = r.guru_id')
                      ->join('tahun_ajaran ta', 'ta.id = r.tahun_ajaran_id', 'left');

        if (!empty($guruId)) $builder->where('r.guru_id', $guruId);
        if (!empty($jabatan)) $builder->where('r.jabatan', $jabatan);
        if (!empty($taId)) $builder->where('r.tahun_ajaran_id', $taId); // APPLY FILTER TA

        // Kloning builder untuk menghitung total baris sebelum dipotong LIMIT
        $totalBuilder = clone $builder;
        $total = $totalBuilder->countAllResults();

        // Ambil data dengan LIMIT & OFFSET
        $riwayatData = $builder->orderBy('r.created_at', 'DESC')->get($perPage, ($page - 1) * $perPage)->getResultArray();

        $pager = \Config\Services::pager();

        // 3. STATISTIK DINAMIS SESUAI FILTER
        $stats = [
            'total_record' => $total,
            'total_guru'   => (clone $totalBuilder)->select('r.guru_id')->distinct()->countAllResults(),
            'last_sync'    => !empty($riwayatData) ? $riwayatData[0]['created_at'] : '-'
        ];

        $data = [
            'user'          => 'Admin',
            'navigations'   => $this->getSidebarMenu(),
            'color'         => $this->getColor(),
            'riwayat'       => $riwayatData,
            'pager'         => $pager->makeLinks($page, $perPage, $total, 'default_full'),
            'guruList'      => $guruList,
            'taList'        => $taList,
            'jabList'       => $jabList, // KIRIM DAFTAR JABATAN DINAMIS
            'filterGuruId'  => $guruId,
            'filterJabatan' => $jabatan,
            'filterTaId'    => $taId,
            'stats'         => $stats
        ];

        return view('admin/riwayat-guru', $data);
    }

    public function delete()
    {
        $db = \Config\Database::connect();
        $id = $this->request->getPost('id');

        if ($db->table('riwayat_jabatan_guru')->where('id', $id)->delete()) {
            return $this->response->setJSON(['status' => 'success', 'message' => 'Catatan riwayat berhasil dihapus']);
        }
        return $this->response->setJSON(['status' => 'error', 'message' => 'Gagal menghapus catatan']);
    }
}
