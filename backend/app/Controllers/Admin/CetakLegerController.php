<?php
namespace App\Controllers\Admin;

use App\Controllers\AdminBaseController;

class CetakLegerController extends AdminBaseController
{
   public function index(): string
    {
        $db = \Config\Database::connect();
        
        $data = [
            'user' => 'Admin',
            'navigations' => $this->getSidebarMenu(),
            'color' => $this->getColor(),
            
            // Ambil data untuk Dropdown Filter
            'list_rombel' => $db->table('rombel')->orderBy('nama_rombel', 'ASC')->get()->getResultArray(),
            'tahun_ajaran' => ['2025/2026', '2024/2025'], 
            'semester' => ['Ganjil', 'Genap']
        ];
        
        return view('admin/cetak-leger', $data); 
    }

    // Fungsi AJAX untuk mengambil dan meracik data leger
    public function getData()
    {
        if (!$this->request->isAJAX()) return $this->response->setStatusCode(404);

        // Ambil filter dari View
        $tahun_ajaran = $this->request->getPost('tahun_ajaran');
        $semester = $this->request->getPost('semester');
        $rombel_id = $this->request->getPost('rombel_id');

        $db = \Config\Database::connect();
        $builder = $db->table('nilai_akademik n');
        $builder->select('n.siswa_id, n.mapel_id, n.nilai_angka, n.predikat, s.nis, s.nama_lengkap');
        $builder->join('siswa s', 's.id = n.siswa_id');
        
        // Terapkan Filter
        $builder->where('n.tahun_ajaran', $tahun_ajaran);
        $builder->where('n.semester', $semester);
        $builder->where('s.rombel_id', $rombel_id); 
        
        $results = $builder->get()->getResultArray();

        $leger = [];
        $no = 1;
        $mapelKeys = [
            1 => 'pai', 2 => 'bindo', 3 => 'barab', 4 => 'bing',
            5 => 'mtk', 6 => 'ipa', 7 => 'ips', 8 => 'tahfidz'
        ];

        foreach ($results as $row) {
            $siswaId = $row['siswa_id'];
            if (!isset($leger[$siswaId])) {
                $leger[$siswaId] = [
                    'no'   => $no++,
                    'nis'  => $row['nis'],
                    'nama' => $row['nama_lengkap'],
                    'pai' => 0, 'bindo' => 0, 'barab' => 0, 'bing' => 0, 
                    'mtk' => 0, 'ipa' => 0, 'ips' => 0, 'tahfidz' => 0,
                    'pai_pred' => '-', 'bindo_pred' => '-', 'barab_pred' => '-', 'bing_pred' => '-',
                    'mtk_pred' => '-', 'ipa_pred' => '-', 'ips_pred' => '-', 'tahfidz_pred' => '-'
                ];
            }
            $mapelId = $row['mapel_id'];
            if (array_key_exists($mapelId, $mapelKeys)) {
                $key = $mapelKeys[$mapelId];
                $leger[$siswaId][$key] = (float) $row['nilai_angka'];
                $leger[$siswaId][$key . '_pred'] = $row['predikat'];
            }
        }

        return $this->response->setJSON([
            'status' => 'success',
            'data' => array_values($leger)
        ]);
    }
}