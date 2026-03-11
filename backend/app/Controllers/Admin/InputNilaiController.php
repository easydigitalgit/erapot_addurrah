<?php

namespace App\Controllers\Admin;

use App\Controllers\AdminBaseController;
use App\Models\Admin\SiswaModel;
use App\Models\Admin\RombelModel;
use App\Models\Admin\MataPelajaranModel;
use App\Models\Admin\NilaiAkademikModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class InputNilaiController extends AdminBaseController
{
    protected $siswaModel;
    protected $rombelModel;
    protected $mapelModel;
    protected $nilaiModel;

    public function __construct()
    {
        $this->siswaModel = new SiswaModel();
        $this->rombelModel = new RombelModel();
        $this->mapelModel = new MataPelajaranModel();
        $this->nilaiModel = new NilaiAkademikModel();
    }

    public function index()
    {
        $this->data['title'] = 'Input Nilai Siswa';
        $this->data['color'] = $this->getColor();
        
        // KUNCI PERBAIKAN TAMPILAN KELAS: Urutkan berdasarkan Tingkat dahulu, lalu Abjad
        $this->data['rombels'] = $this->rombelModel->orderBy('tingkat', 'ASC')->orderBy('nama_rombel', 'ASC')->findAll();
        $this->data['mapels']  = $this->mapelModel->orderBy('nama_mapel', 'ASC')->findAll();

        return view('admin/input-nilai-siswa', $this->data);
    }

    public function getSiswaByKelas()
    {
        $rombel_id = $this->request->getGet('kelas');
        $mapel_id  = $this->request->getGet('mapel');
        
        $tahun_ajaran = '2025/2026';
        $semester     = 'Genap';

        if (!$rombel_id || !$mapel_id) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Parameter kurang']);
        }

        $dataSiswa = $this->siswaModel
            ->select('siswa.id as siswa_id, siswa.nama_lengkap as nama, siswa.nis, 
                      nilai_akademik.nilai_angka, nilai_akademik.predikat, nilai_akademik.catatan')
            ->join('nilai_akademik', "nilai_akademik.siswa_id = siswa.id 
                    AND nilai_akademik.mapel_id = {$mapel_id} 
                    AND nilai_akademik.tahun_ajaran = '{$tahun_ajaran}' 
                    AND nilai_akademik.semester = '{$semester}'", 'left')
            ->where('siswa.rombel_id', $rombel_id)
            ->where('siswa.status_siswa', 'Aktif') 
            ->orderBy('siswa.nama_lengkap', 'ASC')
            ->findAll();

        return $this->response->setJSON([
            'status' => 'success',
            'data'   => $dataSiswa
        ]);
    }

   public function store()
    {
        $rombel_id = $this->request->getPost('rombel_id');
        $mapel_id  = $this->request->getPost('mapel_id');
        
        $raw_nilai = $this->request->getPost('nilai_data');
        $nilai_data = json_decode($raw_nilai ?? '[]');

        if (!$rombel_id || !$mapel_id || empty($nilai_data)) {
            return $this->response->setJSON([
                'status' => 'error', 
                'message' => 'Gagal membaca data. Pastikan form terisi lengkap.'
            ]);
        }

        $tahun_ajaran = '2025/2026';
        $semester     = 'Genap';
        $guru_id      = session()->get('id') ?? 1; 

        $this->nilaiModel->db->transStart();

        try {
            foreach ($nilai_data as $row) {
                if(!isset($row->siswa_id)) continue;

                $existing = $this->nilaiModel->where([
                    'siswa_id'     => $row->siswa_id,
                    'mapel_id'     => $mapel_id,
                    'tahun_ajaran' => $tahun_ajaran,
                    'semester'     => $semester
                ])->first();
    
                $dataToSave = [
                    'siswa_id'     => $row->siswa_id,
                    'mapel_id'     => $mapel_id,
                    'guru_id'      => $guru_id,
                    'rombel_id'    => $rombel_id,
                    'nilai_angka'  => $row->nilai,
                    'predikat'     => $row->predikat,
                    'catatan'      => $row->catatan,
                    'tahun_ajaran' => $tahun_ajaran,
                    'semester'     => $semester
                ];
    
                if ($existing) {
                    $this->nilaiModel->update($existing['id'], $dataToSave);
                } else {
                    $this->nilaiModel->insert($dataToSave);
                }
            }

            $this->nilaiModel->db->transComplete();

            if ($this->nilaiModel->db->transStatus() === false) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Database menolak penyimpanan data.'
                ]);
            }

            return $this->response->setJSON([
                'status' => 'success', 
                'message' => 'Alhamdulillah, data berhasil disimpan!'
            ]);

        } catch (\Exception $e) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'System Error: ' . $e->getMessage()
            ]);
        }
    }

    public function export()
    {
        $rombel_id = $this->request->getGet('kelas');
        $mapel_id  = $this->request->getGet('mapel');
        
        if (!$rombel_id || !$mapel_id) {
            return redirect()->back()->with('error', 'Pilih kelas dan mapel terlebih dahulu.');
        }

        $tahun_ajaran = '2025/2026';
        $semester     = 'Genap';

        $dataSiswa = $this->siswaModel
            ->select('siswa.nama_lengkap as nama, siswa.nis, nilai_akademik.nilai_angka, nilai_akademik.predikat, nilai_akademik.catatan')
            ->join('nilai_akademik', "nilai_akademik.siswa_id = siswa.id 
                    AND nilai_akademik.mapel_id = {$mapel_id} 
                    AND nilai_akademik.tahun_ajaran = '{$tahun_ajaran}' 
                    AND nilai_akademik.semester = '{$semester}'", 'left')
            ->where('siswa.rombel_id', $rombel_id)
            ->where('siswa.status_siswa', 'Aktif')
            ->orderBy('siswa.nama_lengkap', 'ASC')
            ->findAll();

        $rombel = $this->rombelModel->find($rombel_id);
        $mapel  = $this->mapelModel->find($mapel_id);

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        $sheet->setCellValue('A1', 'DATA NILAI SISWA - ' . strtoupper($rombel['nama_rombel']));
        $sheet->setCellValue('A2', 'Mata Pelajaran: ' . $mapel['nama_mapel']);
        $sheet->setCellValue('A3', 'Tahun Ajaran: ' . $tahun_ajaran . ' | Semester: ' . $semester);
        
        $sheet->setCellValue('A5', 'NO');
        $sheet->setCellValue('B5', 'NIS');
        $sheet->setCellValue('C5', 'NAMA LENGKAP');
        $sheet->setCellValue('D5', 'NILAI ANGKA');
        $sheet->setCellValue('E5', 'PREDIKAT');
        $sheet->setCellValue('F5', 'CATATAN GURU');

        $column = 6;
        foreach ($dataSiswa as $key => $siswa) {
            $sheet->setCellValue('A' . $column, ($key + 1));
            $sheet->setCellValue('B' . $column, $siswa['nis']);
            $sheet->setCellValue('C' . $column, $siswa['nama']);
            $sheet->setCellValue('D' . $column, $siswa['nilai_angka'] ?? 0);
            $sheet->setCellValue('E' . $column, $siswa['predikat'] ?? '-');
            $sheet->setCellValue('F' . $column, $siswa['catatan'] ?? '-');
            $column++;
        }

        $filename = 'Rekap_Nilai_' . str_replace(' ', '_', $rombel['nama_rombel']) . '_' . date('Ymd') . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        // KUNCI PERBAIKAN: Instansiasi $writer SEBELUM memanggil save()
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit();
    }
}