<?php

namespace App\Controllers\Admin;

use App\Controllers\AdminBaseController;
use App\Models\Admin\JadwalModel;
use App\Models\Admin\MataPelajaranModel;
use App\Models\Admin\GuruTendikModel;
use App\Models\Admin\RombelModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;

class JadwalPelajaranController extends AdminBaseController
{
    public function index()
    {
        $mapelModel  = new MataPelajaranModel();
        $guruModel   = new GuruTendikModel();
        $jadwalModel = new JadwalModel();
        $rombelModel = new RombelModel(); // <-- Tambahkan inisialisasi Model Rombel

        $dataMapel  = $mapelModel->orderBy('nama_mapel', 'ASC')->findAll();
        $dataGuru   = $guruModel->orderBy('nama_lengkap', 'ASC')->findAll();
        // <-- Ambil data rombel, urutkan berdasarkan tingkat lalu nama
        $dataRombel = $rombelModel->orderBy('tingkat', 'ASC')->orderBy('nama_rombel', 'ASC')->findAll();

        $rawJadwal = $jadwalModel->select('jadwal_pelajaran.*, mata_pelajaran.nama_mapel, guru_tendik.nama_lengkap, guru_tendik.gelar')
            ->join('mata_pelajaran', 'mata_pelajaran.id = jadwal_pelajaran.mapel_id', 'left')
            ->join('guru_tendik', 'guru_tendik.id = jadwal_pelajaran.guru_id', 'left')
            ->findAll();

        $finalJadwal = [];
        foreach ($rawJadwal as $row) {
            $row['jam_ke'] = $this->timeToPeriod($row['jam_mulai']);
            $finalJadwal[] = $row;
        }

        $data = [
            'user'        => 'Admin',
            'navigations' => $this->getSidebarMenu(),
            'data_mapel'  => $dataMapel,
            'data_guru'   => $dataGuru,
            'rombels'     => $dataRombel, // <--- KIRIM VARIABEL INI KE VIEW
            'list_jadwal' => $finalJadwal,
            'color'       => $this->getColor()
        ];

        return view('admin/jadwal-pelajaran', $data);
    }

    private function timeToPeriod($time)
    {
        $map = [
            '07:30:00' => 1,
            '07:30' => 1,
            '08:10:00' => 2,
            '08:10' => 2,
            '08:50:00' => 3,
            '08:50' => 3,
            '09:45:00' => 4,
            '09:45' => 4,
            '10:25:00' => 5,
            '10:25' => 5,
            '11:05:00' => 6,
            '11:05' => 6,
            '13:00:00' => 7,
            '13:00' => 7,
            '13:40:00' => 8,
            '13:40' => 8,
        ];
        return $map[$time] ?? 0;
    }

    private function getWaktuPelajaran($jamKe)
    {
        $jadwal = [
            1 => ['mulai' => '07:30', 'selesai' => '08:10'],
            2 => ['mulai' => '08:10', 'selesai' => '08:50'],
            3 => ['mulai' => '08:50', 'selesai' => '09:30'],
            4 => ['mulai' => '09:45', 'selesai' => '10:25'],
            5 => ['mulai' => '10:25', 'selesai' => '11:05'],
            6 => ['mulai' => '11:05', 'selesai' => '11:45'],
            7 => ['mulai' => '13:00', 'selesai' => '13:40'],
            8 => ['mulai' => '13:40', 'selesai' => '14:20'],
        ];
        return $jadwal[$jamKe] ?? ['mulai' => '00:00', 'selesai' => '00:00'];
    }

    public function save()
    {
        // KODE SAVE MANUAL ANDA SAMA PERSIS
        if (!$this->validate([
            'mapel_id'  => 'required',
            'guru_id'   => 'required',
            'hari'      => 'required',
            'jam_ke'    => 'required',
            'rombel_id' => 'required'
        ])) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Lengkapi data wajib!']);
        }

        $jamKe = $this->request->getVar('jam_ke');
        $waktu = $this->getWaktuPelajaran($jamKe);
        $rombelId = $this->request->getVar('rombel_id');

        $data = [
            'rombel_id'   => $rombelId,
            'mapel_id'    => $this->request->getVar('mapel_id'),
            'guru_id'     => $this->request->getVar('guru_id'),
            'hari'        => $this->request->getVar('hari'),
            'jam_mulai'   => $waktu['mulai'],
            'jam_selesai' => $waktu['selesai']
        ];

        $jadwalModel = new JadwalModel();
        try {
            $jadwalModel->insert($data);
            return $this->response->setJSON(['status' => 'success', 'message' => 'Jadwal berhasil disimpan!']);
        } catch (\Exception $e) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Database Error: ' . $e->getMessage()]);
        }
    }

    public function update($id = null)
    {
        // KODE UPDATE MANUAL ANDA SAMA PERSIS
        if ($id == null) $id = $this->request->getVar('id_jadwal');

        $jamKe = $this->request->getVar('jam_ke');
        $waktu = $this->getWaktuPelajaran($jamKe);

        $data = [
            'rombel_id'   => $this->request->getVar('rombel_id'),
            'mapel_id'    => $this->request->getVar('mapel_id'),
            'guru_id'     => $this->request->getVar('guru_id'),
            'hari'        => $this->request->getVar('hari'),
            'jam_mulai'   => $waktu['mulai'],
            'jam_selesai' => $waktu['selesai']
        ];

        $jadwalModel = new JadwalModel();
        try {
            $jadwalModel->update($id, $data);
            return $this->response->setJSON(['status' => 'success', 'message' => 'Jadwal berhasil diperbarui!']);
        } catch (\Exception $e) {
            return $this->response->setJSON(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    public function delete($id)
    {
        $jadwalModel = new JadwalModel();
        try {
            $jadwalModel->delete($id);
            return $this->response->setJSON(['status' => 'success', 'message' => 'Jadwal berhasil dihapus!']);
        } catch (\Exception $e) {
            return $this->response->setJSON(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    public function downloadTemplate()
    {
        // ANDA TIDAK PERLU TEMPLATE EXCEL LAGI KARENA KITA MENGGUNAKAN FILE WAKA KURIKULUM LANGSUNG.
        // Bisa dibiarkan atau dihapus isinya.
        return redirect()->back();
    }

    // ==============================================================================
    // FUNGSI IMPORT MATRIKS ROSTER (INI YANG BARU)
    // ==============================================================================
    // ==============================================================================
    // FUNGSI IMPORT MATRIKS ROSTER (SUDAH DIPERKUAT ANTI-TYPO)
    // ==============================================================================
    public function import()
    {
        ini_set('memory_limit', '1024M');
        ini_set('max_execution_time', '300');
        if (ob_get_length()) ob_clean();

        if (empty($_FILES)) return $this->response->setJSON(['status' => 'error', 'message' => 'File ditolak server.']);
        $file = $this->request->getFile('file_excel');
        if (!$file || !$file->isValid()) return $this->response->setJSON(['status' => 'error', 'message' => 'File corrupt.']);

        $ext = strtolower($file->getClientExtension());
        if (!in_array($ext, ['xls', 'xlsx', 'csv'])) return $this->response->setJSON(['status' => 'error', 'message' => 'Harus Excel (.xlsx / .csv)']);

        $db = \Config\Database::connect();
        $db->transBegin();

        try {
            $spreadsheet = IOFactory::load($file->getTempName());
            $sheet = $spreadsheet->getSheet(0)->toArray(null, true, true, true);

            // 1. Ambil Data Referensi
            $rombelModel = new RombelModel();
            $dbRombel = $rombelModel->findAll();
            $dbGuru   = (new GuruTendikModel())->select('id, nama_lengkap')->findAll();
            $dbMapel  = (new MataPelajaranModel())->select('id, nama_mapel')->findAll();

            // 2. Baca Header Baris 1
            $header = $sheet[1];
            $colToRombelId = [];
            $colKode = '';
            $colNamaGuru = '';
            $colNamaMapel = '';

            foreach ($header as $col => $val) {
                $valStr = strtolower(trim($val ?? ''));
                if (empty($valStr)) continue;

                // Cari kolom Kamus
                if (strpos($valStr, 'kode mapel') !== false) $colKode = $col;
                if (strpos($valStr, 'nama guru') !== false) $colNamaGuru = $col;
                if (strpos($valStr, 'mata pelajaran') !== false) $colNamaMapel = $col;

                // Cari kolom Rombel (Diperkuat Anti-Typo)
                // Hapus angka 7,8,9 di depan nama kelas dari Excel
                $namaKelasExcel = trim(preg_replace('/^[789]\s+/i', '', $valStr));

                if (!empty($namaKelasExcel) && !in_array($namaKelasExcel, ['jam', 'hari', 'les', 'no'])) {
                    // Cari ID rombel di database yang namanya mirip dengan Excel (misal: "Emerald" ≈ "Emeral")
                    foreach ($dbRombel as $r) {
                        $namaRombelDB = strtolower(trim($r['nama_rombel']));
                        if (strpos($namaKelasExcel, $namaRombelDB) !== false || strpos($namaRombelDB, $namaKelasExcel) !== false) {
                            $colToRombelId[$col] = $r['id'];
                            break; // Lanjut ke kolom Excel berikutnya
                        }
                    }
                }
            }

            if (empty($colToRombelId)) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Tidak menemukan nama Rombel di baris pertama Excel. Pastikan nama kelas di baris ke-1.']);
            }

            // 3. Bangun Kamus Kode
            $kamus = [];
            if ($colKode && $colNamaGuru && $colNamaMapel) {
                for ($i = 2; $i <= count($sheet); $i++) {
                    $kodeRaw  = trim($sheet[$i][$colKode] ?? '');
                    $guruRaw  = trim($sheet[$i][$colNamaGuru] ?? '');
                    $mapelRaw = trim($sheet[$i][$colNamaMapel] ?? '');

                    if ($kodeRaw) {
                        $guruId = null;
                        $mapelId = null;
                        foreach ($dbGuru as $g) {
                            if (stripos($guruRaw, $g['nama_lengkap']) !== false || stripos($g['nama_lengkap'], $guruRaw) !== false) {
                                $guruId = $g['id'];
                                break;
                            }
                        }
                        foreach ($dbMapel as $m) {
                            if (strtolower(trim($m['nama_mapel'])) == strtolower($mapelRaw)) {
                                $mapelId = $m['id'];
                                break;
                            }
                        }
                        $kamus[$kodeRaw] = ['guru_id' => $guruId, 'mapel_id' => $mapelId];
                    }
                }
            }

            // Kosongkan tabel agar bersih (Timpa yang lama)
            $db->table('jadwal_pelajaran')->truncate();

            // 4. Ekstrak Matriks Jadwal
            $currentHari = '';
            $insertData = [];

            for ($i = 2; $i <= count($sheet); $i++) {
                $row = $sheet[$i];

                if (!empty(trim($row['B'] ?? ''))) $currentHari = trim($row['B']);
                if (empty($currentHari)) continue;

                $jamRaw = trim($row['A'] ?? ''); // "07.00 - 07.50"
                $les    = trim($row['C'] ?? ''); // "1", "2", dll.

                if (empty($jamRaw) || strpos($jamRaw, '-') === false) continue;
                if (!is_numeric($les)) continue; // Hanya ambil angka (abaikan Apel/Istirahat)

                $hariFormat = ucfirst(strtolower(str_replace(['`', "'"], "", $currentHari)));
                $waktu = $this->getWaktuPelajaran($les);

                foreach ($colToRombelId as $col => $rombelId) {
                    $kodeSel = trim($row[$col] ?? ''); // misal: "IPS/AAM"
                    if (!empty($kodeSel)) {
                        $guruId = $kamus[$kodeSel]['guru_id'] ?? null;
                        $mapelId = $kamus[$kodeSel]['mapel_id'] ?? null;

                        // Pastikan hanya save yg ada Guru & Mapelnya untuk Rapor
                        if ($guruId && $mapelId) {
                            $insertData[] = [
                                'rombel_id'         => $rombelId,
                                'guru_id'           => $guruId,
                                'mapel_id'          => $mapelId,
                                'kode_jadwal_excel' => $kodeSel,
                                'hari'              => $hariFormat,
                                'jam_ke'            => $les,
                                'jam_mulai'         => $waktu['mulai'],
                                'jam_selesai'       => $waktu['selesai'],
                                'jenis_jadwal'      => 'Reguler'
                            ];
                        }
                    }
                }
            }

            if (!empty($insertData)) {
                // Gunakan chunking untuk menghindari max_allowed_packet error jika datanya terlalu besar
                $chunks = array_chunk($insertData, 100);
                foreach ($chunks as $chunk) {
                    $db->table('jadwal_pelajaran')->insertBatch($chunk);
                }
            }

            if ($db->transStatus() === false) {
                $db->transRollback();
                return $this->response->setJSON(['status' => 'error', 'message' => 'Gagal menyimpan ke database.']);
            }

            $db->transCommit();
            return $this->response->setJSON(['status' => 'success', 'message' => count($insertData) . ' Jadwal Pelajaran berhasil diimport!']);
        } catch (\Throwable $e) {
            $db->transRollback();
            return $this->response->setJSON(['status' => 'error', 'message' => 'System Error: ' . $e->getMessage()]);
        }
    }

    /**
     * Mengambil data mapping (Mapel & Guru) berdasarkan Rombel ID,
     * sekaligus menghitung sisa JP yang belum di-jadwalkan.
     */
    public function getMappingByRombel()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Invalid request']);
        }

        $rombelId = $this->request->getGet('rombel_id');

        if (empty($rombelId)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Rombel ID tidak boleh kosong']);
        }

        $db = \Config\Database::connect();

        // 1. Ambil data mapping aktif untuk rombel ini
        $mappings = $db->table('guru_mapel')
            ->select('guru_mapel.id as mapping_id, guru_mapel.guru_id, guru_mapel.mapel_id, guru_mapel.jam_per_minggu as target_jp, guru_tendik.nama_lengkap as nama_guru, mata_pelajaran.nama_mapel')
            ->join('guru_tendik', 'guru_tendik.id = guru_mapel.guru_id', 'left')
            ->join('mata_pelajaran', 'mata_pelajaran.id = guru_mapel.mapel_id', 'left')
            ->where('guru_mapel.rombel_id', $rombelId)
            ->where('guru_mapel.status', 'active')
            ->get()
            ->getResultArray();

        $availableMappings = [];

        // 2. Hitung berapa kali kombinasi Guru-Mapel ini sudah masuk ke jadwal_pelajaran
        foreach ($mappings as $map) {
            $terjadwal = $db->table('jadwal_pelajaran')
                ->where('rombel_id', $rombelId)
                ->where('guru_id', $map['guru_id'])
                ->where('mapel_id', $map['mapel_id'])
                ->countAllResults();

            $sisaJp = $map['target_jp'] - $terjadwal;

            // Jika sisa JP > 0, berarti mapel ini masih bisa di-jadwalkan
            if ($sisaJp > 0) {
                $map['sisa_jp'] = $sisaJp;
                $availableMappings[] = $map;
            }
        }

        return $this->response->setJSON([
            'status' => 'success',
            'data'   => $availableMappings
        ]);
    }
}
