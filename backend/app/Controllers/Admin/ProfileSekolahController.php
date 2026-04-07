<?php

namespace App\Controllers\Admin;

use App\Controllers\AdminBaseController;
use App\Models\Admin\SekolahModel;
use App\Models\PropinsiModel;
use App\Models\KabupatenModel;
use App\Models\KecamatanModel;
use App\Models\DesaModel;

class ProfileSekolahController extends AdminBaseController
{
    protected $sekolahModel;
    protected $propinsiModel;
    protected $kabupatenModel;
    protected $kecamatanModel;
    protected $desaModel;

    public function __construct()
    {
        $this->sekolahModel   = new SekolahModel();
        $this->propinsiModel  = new PropinsiModel();
        $this->kabupatenModel = new KabupatenModel();
        $this->kecamatanModel = new KecamatanModel();
        $this->desaModel      = new DesaModel();
    }

    public function index(): string
    {
        $sekolah = $this->sekolahModel->first();

        // Data Default jika DB Kosong
        if (!$sekolah) {
            $sekolah = [
                'nama_sekolah'   => '',
                'npsn' => '',
                'nss' => '',
                'jenjang'        => 'SMPIT',
                'status_sekolah' => 'Swasta',
                'tahun_berdiri'  => date('Y'),
                'akreditasi' => 'Belum',
                'alamat' => '',
                'provinsi' => '',
                'kabupaten' => '',
                'kecamatan' => '',
                'kode_pos' => '',
                'telepon' => '',
                'email' => '',
                'website' => '',
                'logo' => 'default_logo.png',
                'warna_primary' => '#1F7A4D',
                'warna_secondary' => '#E6F4EC'
            ];
        }

        $data = [
            'user'          => 'Admin',
            'navigations'   => $this->getSidebarMenu(),
            'sekolah'       => $sekolah,
            'color'         => $this->getColor(),
            'list_propinsi' => $this->propinsiModel->orderBy('nama', 'ASC')->findAll()
        ];

        return view('admin/profile-sekolah', $data);
    }

    public function update()
    {
        // 1. Cek Request AJAX
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(404);
        }

        // 2. Validasi Input
        $rules = [
            'nama_sekolah'  => 'required',
            'npsn'          => 'required|numeric',
            'tahun_berdiri' => 'required|numeric',
            // PERBAIKAN: Menambahkan dukungan untuk format image/webp jika admin mengunggah file WEBP secara langsung
            'logo_sekolah'  => [
                'rules'  => 'max_size[logo_sekolah,5000]|is_image[logo_sekolah]|mime_in[logo_sekolah,image/jpg,image/jpeg,image/png,image/webp]',
                'errors' => [
                    'max_size' => 'Ukuran logo max 5MB',
                    'is_image' => 'File bukan gambar',
                    'mime_in'  => 'Format harus JPG/PNG/WEBP'
                ]
            ]
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Validasi gagal.',
                'errors'  => $this->validator->getErrors()
            ]);
        }

        // 3. Proses Update dengan Try-Catch (Anti-Crash)
        try {
            $existing = $this->sekolahModel->first();
            $id = $existing ? $existing['id'] : null;

            // Menyusun data update persis dengan struktur field di tabel `sekolah` database
            $updateData = [
                'id'             => $id,
                'nama_sekolah'   => $this->request->getPost('nama_sekolah'),
                'npsn'           => $this->request->getPost('npsn'),
                'nss'            => $this->request->getPost('nss'),
                'jenjang'        => $this->request->getPost('jenjang'),
                'status_sekolah' => $this->request->getPost('status_sekolah'),
                'tahun_berdiri'  => $this->request->getPost('tahun_berdiri'),
                'akreditasi'     => $this->request->getPost('akreditasi'),
                'alamat'         => $this->request->getPost('alamat'),
                'provinsi'       => $this->request->getPost('provinsi'),
                'kabupaten'      => $this->request->getPost('kabupaten'),
                'kecamatan'      => $this->request->getPost('kecamatan'),
                'kode_pos'       => $this->request->getPost('kode_pos'),
                'desa_id'        => $this->request->getPost('desa_id'), // <--- TANGKAP INPUTAN DESA DI SINI
                'telepon'        => $this->request->getPost('telepon'),
                'email'          => $this->request->getPost('email'),
                'website'        => $this->request->getPost('website'),
                'warna_primary'  => $this->request->getPost('warna_primary'),
                'warna_secondary' => $this->request->getPost('warna_secondary'),
            ];
            // =========================================================================
            // 4. UPLOAD LOGO SEKOLAH (TANPA KONVERSI AGAR TRANSPARAN AMAN)
            // =========================================================================
            $fileLogo = $this->request->getFile('logo_sekolah');

            if ($fileLogo && $fileLogo->isValid() && !$fileLogo->hasMoved()) {
                // Buat folder jika belum ada
                if (!is_dir(FCPATH . 'uploads/logo')) {
                    mkdir(FCPATH . 'uploads/logo', 0755, true);
                }

                // 4a. Hapus logo lama secara aman
                if ($existing && !empty($existing['logo']) && $existing['logo'] != 'default_logo.png') {
                    $pathLama = FCPATH . 'uploads/logo/' . $existing['logo'];
                    if (file_exists($pathLama)) {
                        @unlink($pathLama);
                    }
                }

                // 4b. Pindahkan file ASLI tanpa manipulasi (Menjaga PNG tetap transparan)
                $namaLogoBaru = $fileLogo->getRandomName(); // Menghasilkan nama random dengan ekstensi aslinya (misal: .png)

                // Pindahkan ke folder tujuan
                $fileLogo->move(FCPATH . 'uploads/logo/', $namaLogoBaru);

                // Simpan nama file ke array update database
                $updateData['logo'] = $namaLogoBaru;
            }
            // =========================================================================
            // 5. Simpan Data ke Database
            if ($this->sekolahModel->save($updateData)) {
                // Hapus cache sekolah agar layout langsung memperbarui warnanya
                session()->remove('cache_sekolah');
                return $this->response->setJSON([
                    'status' => 'success',
                    'message' => 'Data berhasil disimpan!'
                ]);
            } else {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Gagal menyimpan ke database.',
                    'errors' => $this->sekolahModel->errors()
                ]);
            }
        } catch (\Exception $e) {
            return $this->response->setStatusCode(500)->setJSON([
                'status' => 'error',
                'message' => 'Server Error: ' . $e->getMessage()
            ]);
        }
    }

    // === API WILAYAH ===
    public function get_kabupaten()
    {
        if (!$this->request->isAJAX()) return;
        $kode_prop = $this->request->getGet('kode_propinsi');
        if (empty($kode_prop)) return $this->response->setJSON([]);

        $data = $this->kabupatenModel->like('kode', $kode_prop . '.', 'after')->orderBy('nama', 'ASC')->findAll();
        return $this->response->setJSON($data);
    }

    public function get_kecamatan()
    {
        if (!$this->request->isAJAX()) return;
        $kab_kode = $this->request->getGet('kode_kabupaten');
        if (empty($kab_kode)) return $this->response->setJSON([]);

        $data = $this->kecamatanModel->where('kab_kode', $kab_kode)->orderBy('nama', 'ASC')->findAll();
        return $this->response->setJSON($data);
    }

    public function get_desa()
    {
        if (!$this->request->isAJAX()) return;
        $kec_kode = $this->request->getGet('kode_kecamatan');

        if (empty($kec_kode)) return $this->response->setJSON([]);

        try {
            $clean_kode = rtrim($kec_kode, '.');
            $data = $this->desaModel->like('kode', $clean_kode . '.', 'after')->orderBy('nama', 'ASC')->findAll();
            return $this->response->setJSON($data);
        } catch (\Exception $e) {
            return $this->response->setStatusCode(500)->setJSON(['error' => $e->getMessage()]);
        }
    }
}
