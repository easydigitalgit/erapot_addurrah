<?php

namespace App\Controllers\Admin;

use App\Controllers\AdminBaseController;
use App\Models\Admin\GuruMapelModel;
use App\Models\Admin\GuruTendikModel;
use App\Models\Admin\MataPelajaranModel;
use App\Models\Admin\RombelModel;
use App\Models\Admin\TahunAjaranModel;

class MappingMapelController extends AdminBaseController
{
    protected $guruMapelModel;
    protected $guruModel;
    protected $mapelModel;
    protected $rombelModel;
    protected $tahunAjaranModel;

    public function __construct()
    {
        $this->guruMapelModel   = new GuruMapelModel();
        $this->guruModel        = new GuruTendikModel();
        $this->mapelModel       = new MataPelajaranModel();
        $this->rombelModel      = new RombelModel();
        $this->tahunAjaranModel = new TahunAjaranModel();
    }

    public function index(): string
    {
        $rawMappings = $this->guruMapelModel->getAllMappings();

        $formattedData = array_map(function ($row) {
            return [
                'id'          => $row['id'],
                'teacher'     => $row['teacher'],
                'teacher_id'  => $row['guru_id'],
                'nik'         => $row['nik'] ?? '-',
                'mapel'       => $row['mapel'],
                'mapel_id'    => $row['mapel_id'],
                'level'       => $row['tingkat'],
                'rombel'      => str_replace($row['tingkat'] . '-', '', $row['nama_rombel']),
                'rombel_id'   => $row['rombel_id'],
                'rombel_full' => $row['nama_rombel'],
                'jam'         => $row['jam_per_minggu'],
                'tahunAjaran' => $row['tahun_ajaran'],
                'catatan'     => $row['catatan'] ?? '',
                'status'      => $row['status']
            ];
        }, $rawMappings);

        $totalGuru = $this->guruModel->countAllResults();
        $totalMapel = $this->mapelModel->countAllResults();
        $totalMapping = $this->guruMapelModel->where('status', 'active')->countAllResults();

        $allRombelIds = $this->rombelModel->findColumn('id') ?? [];
        $mappedRombelIds = $this->guruMapelModel->findColumn('rombel_id') ?? [];
        $unmappedRombelCount = count(array_diff($allRombelIds, array_unique($mappedRombelIds)));

        $rombelRaw = $this->rombelModel->orderBy('tingkat', 'ASC')->findAll();
        $tingkatList = array_unique(array_column($rombelRaw, 'tingkat'));

        $activeTahunRow = $this->tahunAjaranModel->where('status', 'Aktif')->first();
        $tahunAjaranAktif = $activeTahunRow ? $activeTahunRow['tahun'] : 'Belum Diset';

        $data = [
            'user'            => 'Admin',
            'navigations'     => $this->getSidebarMenu(),
            'color'           => $this->getColor(),
            'mappingData'     => $formattedData,
            'stats'           => [
                'total_guru'    => $totalGuru,
                'total_mapel'   => $totalMapel,
                'total_mapping' => $totalMapping,
                'empty_rombel'  => $unmappedRombelCount
            ],
            'guruList'        => $this->guruModel->orderBy('nama_lengkap', 'ASC')->findAll(),
            'mapelList'       => $this->mapelModel->orderBy('nama_mapel', 'ASC')->findAll(),
            'rombelList'      => $rombelRaw,
            'tingkatList'     => $tingkatList,
            'tahunAjaranList' => $this->tahunAjaranModel->orderBy('tahun', 'DESC')->findAll(),
            'tahunAjaranAktif'=> $tahunAjaranAktif
        ];

        return view('admin/mapping-mapel', $data);
    }

    public function store()
    {
        $guruId      = $this->request->getPost('add_guru');
        $mapelId     = $this->request->getPost('add_mapel');

        $rombelIdsRaw = $this->request->getPost('add_rombel');
        $rombelIds = is_string($rombelIdsRaw) ? json_decode($rombelIdsRaw, true) : $rombelIdsRaw;

        $jam         = $this->request->getPost('add_jam');
        $tahunAjaran = $this->request->getPost('add_tahun_ajaran');
        $catatan     = $this->request->getPost('add_catatan');

        if (empty($guruId) || empty($mapelId) || empty($rombelIds)) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Guru, Mapel, dan minimal satu Rombel harus dipilih!'
            ]);
        }

        $successCount = 0;
        $existCount = 0;

        foreach ($rombelIds as $rombelId) {
            $exists = $this->guruMapelModel->checkDuplicate($guruId, $mapelId, $rombelId);
            if (!$exists) {
                $this->guruMapelModel->insert([
                    'guru_id'        => $guruId,
                    'mapel_id'       => $mapelId,
                    'rombel_id'      => $rombelId,
                    'tahun_ajaran'   => $tahunAjaran,
                    'jam_per_minggu' => $jam,
                    'catatan'        => $catatan,
                    'status'         => 'active'
                ]);
                $successCount++;
            } else {
                $existCount++;
            }
        }

        if ($successCount > 0) {
            $msg = "$successCount data berhasil disimpan.";
            if ($existCount > 0) $msg .= " ($existCount data dilewati karena sudah ada)";
            return $this->response->setJSON(['status' => 'success', 'message' => $msg]);
        }

        return $this->response->setJSON(['status' => 'error', 'message' => 'Data mapping sudah ada sebelumnya.']);
    }

    public function update($id = null)
    {
        $id = $id ?? $this->request->getPost('id');

        if (empty($id)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'ID data tidak ditemukan.']);
        }

        $guruId      = $this->request->getPost('add_guru');
        $mapelId     = $this->request->getPost('add_mapel');

        $rombelIdsRaw = $this->request->getPost('add_rombel');
        $rombelIds = json_decode($rombelIdsRaw, true);
        if (!is_array($rombelIds)) {
            $rombelIds = [$rombelIdsRaw]; 
        }

        $jam         = $this->request->getPost('add_jam');
        $tahunAjaran = $this->request->getPost('add_tahun_ajaran');
        $catatan     = $this->request->getPost('add_catatan');

        if (empty($rombelIds) || empty($rombelIds[0])) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Rombel tidak boleh kosong!']);
        }

        $db = \Config\Database::connect();
        $db->transStart();

        $firstRombelId = array_shift($rombelIds); 
        
        $dataUpdate = [
            'guru_id'        => $guruId,
            'mapel_id'       => $mapelId,
            'rombel_id'      => $firstRombelId,
            'tahun_ajaran'   => $tahunAjaran,
            'jam_per_minggu' => $jam,
            'catatan'        => $catatan
        ];
        
        $this->guruMapelModel->update($id, $dataUpdate);

        foreach ($rombelIds as $rombelId) {
            if (!empty($rombelId)) {
                $exists = $this->guruMapelModel->checkDuplicate($guruId, $mapelId, $rombelId);
                if (!$exists) {
                    $this->guruMapelModel->insert([
                        'guru_id'        => $guruId,
                        'mapel_id'       => $mapelId,
                        'rombel_id'      => $rombelId,
                        'tahun_ajaran'   => $tahunAjaran,
                        'jam_per_minggu' => $jam,
                        'catatan'        => $catatan,
                        'status'         => 'active'
                    ]);
                }
            }
        }

        $db->transComplete();

        if ($db->transStatus() === false) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Gagal memperbarui data di database.']);
        }

        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'Data mapping berhasil diperbarui.'
        ]);
    }

    public function delete()
    {
        $id = $this->request->getPost('id');

        if ($this->guruMapelModel->delete($id)) {
            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Data berhasil dihapus'
            ]);
        }

        return $this->response->setJSON(['status' => 'error', 'message' => 'Gagal menghapus data']);
    }

    public function bulkStore()
    {
        $guruId      = $this->request->getPost('bulk_guru');
        $mapelIds    = $this->request->getPost('bulk_mapel'); 
        $rombelIds   = $this->request->getPost('bulk_rombel'); 
        $tahunAjaran = $this->request->getPost('bulk_tahun_ajaran');

        if (empty($guruId) || empty($mapelIds) || empty($rombelIds)) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Guru, minimal 1 Mapel, dan minimal 1 Rombel wajib dipilih!'
            ]);
        }

        $successCount = 0;
        $existCount = 0;

        $db = \Config\Database::connect();

        foreach ($mapelIds as $mapelId) {
            foreach ($rombelIds as $rombelId) {
                $exists = $this->guruMapelModel->checkDuplicate($guruId, $mapelId, $rombelId);

                if (!$exists) {
                    // Jika tabel jadwal belum ada / kosong, default ke 2. 
                    // Pastikan tabel jadwal_pelajaran ada jika ingin ini bekerja persis seperti logika sebelumnya.
                    if ($db->tableExists('jadwal_pelajaran')) {
                        $hitungJp = $db->table('jadwal_pelajaran')
                            ->where('mapel_id', $mapelId)
                            ->where('rombel_id', $rombelId)
                            ->countAllResults();
                        if ($hitungJp == 0) $hitungJp = 4; // Fallback jika di jadwal kosong
                    } else {
                        $hitungJp = 4;
                    }

                    $this->guruMapelModel->insert([
                        'guru_id'        => $guruId,
                        'mapel_id'       => $mapelId,
                        'rombel_id'      => $rombelId,
                        'tahun_ajaran'   => $tahunAjaran,
                        'jam_per_minggu' => $hitungJp, 
                        'status'         => 'active'
                    ]);
                    $successCount++;
                } else {
                    $existCount++;
                }
            }
        }

        if ($successCount > 0) {
            $msg = "Bulk Mapping Selesai! $successCount relasi ditambahkan dengan JP Otomatis.";
            if ($existCount > 0) $msg .= " ($existCount relasi dilewati karena sudah ada)";
            return $this->response->setJSON(['status' => 'success', 'message' => $msg]);
        }

        return $this->response->setJSON(['status' => 'error', 'message' => 'Semua kombinasi mapping yang dipilih sudah ada di database.']);
    }
}