<?php

namespace App\Controllers\Admin;

use App\Controllers\AdminBaseController;
use App\Models\Admin\GuruTendikModel;
use App\Models\Admin\UserModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;

class GuruTendikController extends AdminBaseController
{
    protected $guruTendikModel;
    protected $userModel;

    public function __construct()
    {
        $this->guruTendikModel = new GuruTendikModel();
        $this->userModel = new UserModel();
    }
    
    public function index()
    {
        $db = \Config\Database::connect();
        
        // 1. Total Wali Kelas (Hitung jumlah ID guru unik yang jadi wali kelas)
        // Mencegah Crash Query Builder dengan Raw Query yang sangat aman
        $queryWali = $db->query("SELECT COUNT(DISTINCT wali_kelas_id) as total FROM rombel WHERE wali_kelas_id IS NOT NULL AND wali_kelas_id != 0");
        $waliKelas = $queryWali->getRow()->total ?? 0;

        // 2. Total Guru Mapel (Hitung jumlah ID guru unik di tabel guru_mapel)
        $queryMapel = $db->query("SELECT COUNT(DISTINCT guru_id) as total FROM guru_mapel");
        $totalGuruMapel = $queryMapel->getRow()->total ?? 0;

        // 3. Total Pembina Tahfiz (Dihitung dari tabel 'guru_tendik' kolom jabatan)
        $totalTahfiz = $this->guruTendikModel
            ->where('jabatan', 'Pembina Tahfiz')
            ->countAllResults();

        // 4. Total Staf/Tendik (Dihitung dari tabel 'guru_tendik' kolom jabatan)
        $totalTendik = $this->guruTendikModel
            ->where('jabatan', 'Tendik')
            ->countAllResults();
        
        // Data Tambahan untuk Modal & Filter
        $mapelList = $db->table('mata_pelajaran')->where('status', 'Aktif')->orderBy('nama_mapel', 'ASC')->get()->getResultArray();
        $jabatanList = $db->table('master_jabatan')->orderBy('nama_jabatan', 'ASC')->get()->getResultArray();
        $statusList = $db->table('master_status_pegawai')->orderBy('id', 'ASC')->get()->getResultArray();

        $data = [
            'title'          => 'Manajemen Guru & Tendik',
            'total_guru'     => $totalGuruMapel, 
            'total_tendik'   => $totalTendik,
            'total_tahfiz'   => $totalTahfiz,
            'wali_kelas'     => $waliKelas,      
            'navigations'    => $this->getSidebarMenu(),
            'color'          => $this->getColor(),
            'mapel_list'     => $mapelList,
            'jabatan_list'   => $jabatanList, 
            'status_list'    => $statusList 
        ];
        
        return view('admin/guru-tendik', $data);
    }
    
    public function getAll()
    {
        $db = \Config\Database::connect();
        
        // 1. Ambil data dasar Guru & Tendik
        $builder = $this->guruTendikModel->builder();
        $builder->select('guru_tendik.*, users.is_active, users.username, users.foto_profil');
        $builder->join('users', 'users.id = guru_tendik.user_id', 'left');
        $builder->orderBy('guru_tendik.id', 'DESC');
        $dataGuru = $builder->get()->getResultArray();

        // 2. Ambil SEMUA relasi kelas mengajar dari tabel guru_mapel & rombel
        $assignments = $db->table('guru_mapel gm')
            ->select('gm.guru_id, r.nama_rombel, r.tingkat')
            ->join('rombel r', 'r.id = gm.rombel_id', 'left')
            ->get()
            ->getResultArray();

        // 3. Kelompokkan data kelas berdasarkan guru_id
        $kelasMap = [];
        foreach ($assignments as $assign) {
            $gid = $assign['guru_id'];
            if (!isset($kelasMap[$gid])) {
                $kelasMap[$gid] = [];
            }
            $kelasMap[$gid][] = $assign['tingkat'] . ' ' . $assign['nama_rombel'];
        }

        // 4. Masukkan data kelas yang sudah digabung (dipisah koma) ke dalam array dataGuru
        foreach ($dataGuru as &$guru) {
            $gid = $guru['id']; // ID Guru Tendik, bukan User ID
            if (isset($kelasMap[$gid]) && !empty($kelasMap[$gid])) {
                $uniqueClasses = array_unique($kelasMap[$gid]); 
                $guru['kelas_mengajar'] = implode(', ', $uniqueClasses);
            } else {
                $guru['kelas_mengajar'] = '-';
            }
        }

        return $this->response->setJSON($dataGuru);
    }

    public function show($id)
    {
        $data = $this->guruTendikModel->find($id);
        if ($data) {
            return $this->response->setJSON($data);
        }
        return $this->response->setJSON(['status' => 'error', 'message' => 'Data tidak ditemukan'])->setStatusCode(404);
    }

    public function store()
    {
        $db = \Config\Database::connect();
        $db->transBegin();

        try {
            $fileFoto = $this->request->getFile('photo');
            $namaFotoDB = null;

            if ($fileFoto && $fileFoto->isValid() && !$fileFoto->hasMoved()) {
                $namaFotoDB = $fileFoto->getRandomName();
                $fileFoto->move('uploads/guru', $namaFotoDB);
            }

            $emailInput = $this->request->getPost('email');
            $fullnameInput = $this->request->getPost('fullname');
            $roleJabatan = $this->request->getPost('role'); 
            
            if ($roleJabatan === 'Tendik') {
                $roleId = 1; 
            } elseif ($roleJabatan === 'Pembina Tahfiz') {
                $roleId = 7; 
            } elseif ($roleJabatan === 'Wali Kelas') {
                $roleId = 3; 
            } else {
                $roleId = 2; 
            }
            
            $existingUser = $this->userModel->where('email', $emailInput)->first();
            $userId = null;

            if ($existingUser) {
                $userId = is_array($existingUser) ? $existingUser['id'] : $existingUser->id;
                
                $cekRole = $db->table('user_roles')
                              ->where('user_id', $userId)
                              ->where('role_id', $roleId)
                              ->countAllResults();
                              
                if ($cekRole == 0) {
                    $db->table('user_roles')->insert([
                        'user_id' => $userId,
                        'role_id' => $roleId
                    ]);
                }
            } else {
                $userData = [
                    'username'  => $this->request->getPost('nuptk') ?: $this->request->getPost('nik'),
                    'email'     => $emailInput,
                    'password'  => password_hash('12345678', PASSWORD_BCRYPT),
                    'role_id'   => $roleId,
                    'is_active' => 1
                ];

                if (!$this->userModel->insert($userData)) {
                    throw new \Exception("Gagal membuat akun user baru.");
                }
                
                $userId = $this->userModel->getInsertID();

                $db->table('user_roles')->insert([
                    'user_id' => $userId,
                    'role_id' => $roleId
                ]);
            }

            $guruData = [
                'user_id'            => $userId,
                'nama_lengkap'       => $fullnameInput,
                'nuptk'              => $this->request->getPost('nuptk'),
                'nik'                => $this->request->getPost('nik'),
                'email'              => $emailInput,
                'no_hp'              => $this->request->getPost('phone'),
                'jabatan'            => $roleJabatan,
                'status_kepegawaian' => $this->request->getPost('employment_status'),
                'mapel_utama'        => $this->request->getPost('subject') ?? '-', 
                'foto'               => $namaFotoDB
            ];

            if (!$this->guruTendikModel->insert($guruData)) {
                 throw new \Exception("Gagal simpan biodata pegawai.");
            }
            
            $db->transCommit();

            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Data Pegawai Berhasil Disimpan & Akun Terhubung!'
            ]);

        } catch (\Exception $e) {
            $db->transRollback();
            return $this->response->setJSON([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }

    public function update($id)
    {
        $rules = [
            'fullname' => 'required',
            'nik'      => "required|is_unique[guru_tendik.nik,id,{$id}]", 
            'email'    => "required|valid_email|is_unique[guru_tendik.email,id,{$id}]", 
        ];
    
        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'status' => 'error',
                'errors' => $this->validator->getErrors(),
                'message' => 'Validasi gagal'
            ]);
        }
        $existing = $this->guruTendikModel->find($id);
        
        $fileFoto = $this->request->getFile('photo');
        $namaFotoDB = $existing['foto'];

        if ($fileFoto && $fileFoto->isValid() && !$fileFoto->hasMoved()) {
            if ($existing['foto'] && file_exists('uploads/guru/' . $existing['foto'])) {
                unlink('uploads/guru/' . $existing['foto']);
            }
            $namaFotoDB = $fileFoto->getRandomName();
            $fileFoto->move('uploads/guru', $namaFotoDB);
        }

        $data = [
            'nama_lengkap'       => $this->request->getPost('fullname'),
            'nuptk'              => $this->request->getPost('nuptk'),
            'nik'                => $this->request->getPost('nik'),
            'email'              => $this->request->getPost('email'),
            'no_hp'              => $this->request->getPost('phone'),
            'jabatan'            => $this->request->getPost('role'),
            'status_kepegawaian' => $this->request->getPost('employment_status'),
            'mapel_utama'        => $this->request->getPost('subject'),
            'foto'               => $namaFotoDB
        ];

        $this->guruTendikModel->update($id, $data);

        return $this->response->setJSON(['status' => 'success', 'message' => 'Data berhasil diperbarui']);
    }

    public function delete($id)
    {
        $data = $this->guruTendikModel->find($id);
        if ($data) {
            if ($data['foto'] && file_exists('uploads/guru/' . $data['foto'])) {
                unlink('uploads/guru/' . $data['foto']);
            }
            $this->userModel->delete($data['user_id']);
            $this->guruTendikModel->delete($id);

            return $this->response->setJSON(['status' => 'success', 'message' => 'Data berhasil dihapus']);
        }
        return $this->response->setJSON(['status' => 'error', 'message' => 'Gagal menghapus data']);
    }

    public function export()
    {
        $ids = $this->request->getGet('ids');
        
        if ($ids) {
            $idArray = explode(',', $ids);
            $dataGuru = $this->guruTendikModel->whereIn('id', $idArray)->findAll();
        } else {
            $dataGuru = $this->guruTendikModel->findAll();
        }

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        $filename = 'Data_Guru_Tendik_' . date('Y-m-d_H-i') . '.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }

    public function downloadTemplate()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $headers = ['Nama Lengkap', 'NUPTK', 'NIK', 'Email', 'No HP', 'Jabatan', 'Mapel Utama', 'Status Kepegawaian'];
        $col = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($col . '1', $header);
            $sheet->getColumnDimension($col)->setAutoSize(true);
            $col++;
        }

        $sheet->setCellValue('A2', 'Contoh: Budi Santoso, S.Pd');
        $sheet->setCellValue('B2', '19800101...');
        $sheet->setCellValue('C2', '320101...');
        $sheet->setCellValue('D2', 'budi@sekolah.sch.id');
        $sheet->setCellValue('E2', '08123456789');
        $sheet->setCellValue('F2', 'Pembina Tahfiz'); 
        $sheet->setCellValue('G2', '-');
        $sheet->setCellValue('H2', 'Tetap');

        $filename = 'Template_Import_Guru.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }

    public function import()
    {
        $file = $this->request->getFile('file_excel');

        if ($file && $file->isValid() && !$file->hasMoved()) {
            $extension = $file->getExtension();
            
            if (!in_array($extension, ['xls', 'xlsx'])) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Format file harus .xls atau .xlsx']);
            }

            try {
                $spreadsheet = IOFactory::load($file->getTempName());
                $sheet = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);
                
                $db = \Config\Database::connect();
                $db->transStart();

                $count = 0;
                foreach ($sheet as $idx => $row) {
                    if ($idx == 1) continue; 
                    if (empty($row['A']) || empty($row['D'])) continue; 

                    $emailInput = trim($row['D']);
                    $jabatanInput = trim($row['F']);
                    
                    $roleId = 2; // Default Guru Mapel
                    if ($jabatanInput === 'Tendik') $roleId = 1;
                    if ($jabatanInput === 'Wali Kelas') $roleId = 3;
                    if ($jabatanInput === 'Pembina Tahfiz' || $jabatanInput === 'Guru Tahfiz') {
                        $roleId = 7;
                        $jabatanInput = 'Pembina Tahfiz'; 
                    }

                    $existingUser = $this->userModel->where('email', $emailInput)->first();
                    $userId = null;

                    if ($existingUser) {
                        $userId = is_array($existingUser) ? $existingUser['id'] : $existingUser->id;
                        
                        $cekRole = $db->table('user_roles')->where(['user_id' => $userId, 'role_id' => $roleId])->countAllResults();
                        if ($cekRole == 0) {
                            $db->table('user_roles')->insert(['user_id' => $userId, 'role_id' => $roleId]);
                        }
                    } else {
                        $userData = [
                            'username'  => $row['B'] ?: $row['C'],
                            'email'     => $emailInput,
                            'password'  => password_hash('12345678', PASSWORD_BCRYPT),
                            'role_id'   => $roleId,
                            'is_active' => 1
                        ];
                        $this->userModel->insert($userData);
                        $userId = $this->userModel->getInsertID();

                        $db->table('user_roles')->insert(['user_id' => $userId, 'role_id' => $roleId]);
                    }

                    $cekGuru = $this->guruTendikModel->where('nik', $row['C'])->orWhere('email', $emailInput)->countAllResults();
                    if ($cekGuru > 0) continue; 

                    $guruData = [
                        'user_id'            => $userId,
                        'nama_lengkap'       => trim($row['A']),
                        'nuptk'              => trim($row['B']),
                        'nik'                => trim($row['C']),
                        'email'              => $emailInput,
                        'no_hp'              => trim($row['E']),
                        'jabatan'            => $jabatanInput,
                        'mapel_utama'        => trim($row['G']),
                        'status_kepegawaian' => trim($row['H']),
                    ];
                    $this->guruTendikModel->insert($guruData);
                    $count++;
                }

                $db->transComplete();

                if ($db->transStatus() === FALSE) {
                    return $this->response->setJSON(['status' => 'error', 'message' => 'Gagal menyimpan data import.']);
                }

                return $this->response->setJSON([
                    'status' => 'success', 
                    'message' => "Berhasil mengimport $count data pegawai baru."
                ]);

            } catch (\Exception $e) {
                return $this->response->setJSON(['status' => 'error', 'message' => $e->getMessage()]);
            }
        }

        return $this->response->setJSON(['status' => 'error', 'message' => 'File tidak valid']);
    }

    public function bulkDelete()
    {
        $json = $this->request->getJSON();
        $ids = $json->ids ?? [];

        if (empty($ids)) return $this->response->setJSON(['status' => 'error', 'message' => 'Tidak ada data yang dipilih']);

        $db = \Config\Database::connect();
        $db->transStart();

        try {
            $guruList = $this->guruTendikModel->whereIn('id', $ids)->findAll();
            $userIds = array_column($guruList, 'user_id');

            if (!empty($userIds)) {
                $this->userModel->whereIn('id', $userIds)->delete();
            }
            
            $this->guruTendikModel->whereIn('id', $ids)->delete();

            $db->transComplete();

            return $this->response->setJSON([
                'status' => 'success', 
                'message' => count($ids) . ' data berhasil dihapus permanen.'
            ]);

        } catch (\Exception $e) {
            $db->transRollback();
            return $this->response->setJSON(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }
}