<?php

namespace App\Controllers\WaliKelas;

use App\Controllers\WaliKelasBaseController;

class AbsensiKelasController extends WaliKelasBaseController
{
    public function index(): string
    {
        $db = \Config\Database::connect();

        $sekolah = $db->table('sekolah')->select('nama_sekolah, warna_primary, warna_secondary')->get()->getRowArray();
        $warna_primary = $sekolah ? $sekolah['warna_primary'] : '#10b981';
        $warna_secondary = $sekolah ? $sekolah['warna_secondary'] : '#ecfdf5';
        $nama_sekolah = $sekolah ? $sekolah['nama_sekolah'] : 'SMPIT Ad Durrah';

        $color = [
            'warna_primary'   => $warna_primary,
            'warna_secondary' => $warna_secondary,
            'warna_hadir'     => '#16a34a',
            'warna_sakit'     => '#ca8a04',
            'warna_izin'      => '#9333ea',
            'warna_alpha'     => '#dc2626'
        ];

        // Mendapatkan nama kelas untuk dikirim ke JS
        $nama_rombel = 'Kelas Belum Ditentukan';
        $rombel_info = $this->getRombelInfoWaliKelas();
        if ($rombel_info) {
            $nama_rombel = $rombel_info['nama_rombel'];
        }

        // Ambil data filter Tahun Ajaran
        $tahun_ajaran_list = $db->table('tahun_ajaran')->orderBy('tahun', 'DESC')->get()->getResultArray();
        
        // Ambil TA Aktif sebagai default
        $ta_aktif = $db->table('tahun_ajaran')->where('status', 'Aktif')->get()->getRowArray();

        $data = [
            'title'             => 'Absensi Kelas Semester',
            'user'              => session()->get('nama_lengkap') ?? 'Wali Kelas',
            'namaLengkap'       => session()->get('nama_lengkap') ?? session()->get('username') ?? 'Wali Kelas',
            'nama_sekolah'      => $nama_sekolah,
            'nama_rombel'       => $nama_rombel,
            'is_wali_kelas_sah' => ($rombel_info !== null),
            'navigations'       => $this->getSidebarMenu(),
            'color'             => $color,
            'tahun_ajaran_list' => $tahun_ajaran_list,
            'ta_aktif'          => $ta_aktif
        ];

        return view('WaliKelas/absensi-kelas', $data);
    }

    private function getRombelInfoWaliKelas()
    {
        $db = \Config\Database::connect();
        $userId = session()->get('id') ?? session()->get('user_id');

        $guru = $db->table('guru_tendik')->where('user_id', $userId)->get()->getRowArray();

        if ($guru) {
            $ta_aktif = $db->table('tahun_ajaran')->where('status', 'Aktif')->get()->getRowArray();
            $id_ta = $ta_aktif ? $ta_aktif['id'] : 0;

            // Cari Rombel dengan tahun ajaran aktif
            $rombel = $db->table('rombel')
                ->where('wali_kelas_id', $guru['id'])
                ->where('id_tahun_ajaran', $id_ta)
                ->get()->getRowArray();


            if ($rombel) {
                return [
                    'id'              => $rombel['id'],
                    'nama_rombel'     => $rombel['nama_rombel'],
                    'id_tahun_ajaran' => $rombel['id_tahun_ajaran']
                ];
            }
        }
        return null;
    }

    public function getAbsensiData()
    {
        try {
            $rombel_info = $this->getRombelInfoWaliKelas();
            if (!$rombel_info) {
                return $this->response->setJSON(['students' => [], 'rekap' => []]);
            }

            $rombel_id = $rombel_info['id'];
            $db = \Config\Database::connect();

            // Ambil filter dari request (jika ada)
            $id_ta = $this->request->getGet('id_ta') ?: $rombel_info['id_tahun_ajaran'];
            $semester = $this->request->getGet('semester') ?: 'Ganjil';

            // 1. Ambil daftar siswa di rombel untuk TA tersebut
            $students = $db->table('anggota_rombel ar')
                ->select('siswa.id, siswa.nama_lengkap, siswa.nisn, siswa.nis, siswa.foto_siswa, users.foto_profil')
                ->join('siswa', 'siswa.id = ar.siswa_id')
                ->join('users', 'users.id = siswa.user_id', 'left')
                ->where('ar.rombel_id', $rombel_id)
                ->where('ar.tahun_ajaran_id', $id_ta)
                ->where('ar.semester', $semester)
                ->where('siswa.status_siswa', 'Aktif')
                ->orderBy('siswa.nama_lengkap', 'ASC')
                ->get()->getResultArray();

            // 2. Ambil data rekap dari tabel rekap_absensi
            $rekapRecords = $db->table('rekap_absensi')
                ->where('tahun_ajaran_id', $id_ta)
                ->where('semester', $semester)
                ->get()->getResultArray();

            $rekapMap = [];
            foreach ($rekapRecords as $r) {
                $rekapMap[$r['siswa_id']] = [
                    'hadir' => $r['hadir'],
                    'sakit' => $r['sakit'],
                    'izin'  => $r['izin'],
                    'alpha' => $r['alpha']
                ];
            }

            $studentList = [];
            foreach ($students as $s) {
                $foto_final = !empty($s['foto_profil']) ? $s['foto_profil'] : $s['foto_siswa'];
                
                $rekap = $rekapMap[$s['id']] ?? ['hadir' => 0, 'sakit' => 0, 'izin' => 0, 'alpha' => 0];

                $studentList[] = [
                    'id'       => $s['id'],
                    'name'     => $s['nama_lengkap'], 
                    'nisn'     => $s['nisn'] ?? $s['nis'] ?? '-',
                    'foto_fix' => $foto_final,
                    'rekap'    => $rekap
                ];
            }

            return $this->response->setJSON([
                'students' => $studentList,
                'filter'   => ['id_ta' => $id_ta, 'semester' => $semester]
            ]);
        } catch (\Throwable $e) {
            return $this->response->setJSON(['error' => 'DB Error: ' . $e->getMessage()])->setStatusCode(500);
        }
    }

    public function saveAbsensi()
    {
        $json = $this->request->getJSON();
        $id_ta = $json->id_ta;
        $semester = $json->semester;
        $records = $json->records; // Array of [siswa_id => [hadir, sakit, izin, alpha]]

        $rombel_info = $this->getRombelInfoWaliKelas();
        if (!$rombel_info) {
            return $this->response->setJSON(['success' => false, 'message' => 'Akses ditolak!']);
        }

        $db = \Config\Database::connect();
        $db->transBegin();

        try {
            foreach ($records as $siswa_id => $data) {
                $existing = $db->table('rekap_absensi')->where([
                    'siswa_id'        => $siswa_id,
                    'tahun_ajaran_id' => $id_ta,
                    'semester'        => $semester
                ])->get()->getRowArray();

                $updateData = [
                    'hadir' => $data->hadir ?? 0,
                    'sakit' => $data->sakit ?? 0,
                    'izin'  => $data->izin ?? 0,
                    'alpha' => $data->alpha ?? 0
                ];

                if ($existing) {
                    $db->table('rekap_absensi')->where('id', $existing['id'])->update($updateData);
                } else {
                    $updateData['siswa_id'] = $siswa_id;
                    $updateData['tahun_ajaran_id'] = $id_ta;
                    $updateData['semester'] = $semester;
                    $db->table('rekap_absensi')->insert($updateData);
                }
            }

            $db->transCommit();
            return $this->response->setJSON(['success' => true, 'message' => 'Data absensi semester berhasil disimpan!']);
        } catch (\Throwable $e) {
            $db->transRollback();
            return $this->response->setJSON(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        }
    }

    public function importAbsensi()
    {
        $file = $this->request->getFile('file_csv');
        $id_ta = $this->request->getPost('id_ta');
        $semester = $this->request->getPost('semester');

        if (!$file || !$file->isValid()) {
            return $this->response->setJSON(['success' => false, 'message' => 'File tidak valid.']);
        }

        $db = \Config\Database::connect();
        $handle = fopen($file->getTempName(), "r");
        
        // Skip header
        fgetcsv($handle, 1000, ",");

        $db->transBegin();
        try {
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                $nisn = trim($data[0]);
                if (empty($nisn)) continue;

                $siswa = $db->table('siswa')->where('nisn', $nisn)->get()->getRowArray();
                if (!$siswa) continue;

                $rekap = [
                    'hadir' => (int)$data[2],
                    'sakit' => (int)$data[3],
                    'izin'  => (int)$data[4],
                    'alpha' => (int)$data[5],
                ];

                $existing = $db->table('rekap_absensi')->where([
                    'siswa_id' => $siswa['id'],
                    'tahun_ajaran_id' => $id_ta,
                    'semester' => $semester
                ])->get()->getRowArray();

                if ($existing) {
                    $db->table('rekap_absensi')->where('id', $existing['id'])->update($rekap);
                } else {
                    $rekap['siswa_id'] = $siswa['id'];
                    $rekap['tahun_ajaran_id'] = $id_ta;
                    $rekap['semester'] = $semester;
                    $db->table('rekap_absensi')->insert($rekap);
                }
            }
            $db->transCommit();
            fclose($handle);
            return $this->response->setJSON(['success' => true, 'message' => 'Import absensi berhasil!']);
        } catch (\Throwable $e) {
            $db->transRollback();
            fclose($handle);
            return $this->response->setJSON(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function downloadTemplate()
    {
        $rombel_info = $this->getRombelInfoWaliKelas();
        if (!$rombel_info) return "Akses ditolak.";

        $db = \Config\Database::connect();
        $id_ta = $this->request->getGet('id_ta');
        $semester = $this->request->getGet('semester');

        $students = $db->table('anggota_rombel ar')
            ->select('siswa.nama_lengkap, siswa.nisn')
            ->join('siswa', 'siswa.id = ar.siswa_id')
            ->where('ar.rombel_id', $rombel_info['id'])
            ->where('ar.tahun_ajaran_id', $id_ta)
            ->where('ar.semester', $semester)
            ->orderBy('siswa.nama_lengkap', 'ASC')
            ->get()->getResultArray();

        $filename = "Template_Absensi_" . str_replace(' ', '_', $rombel_info['nama_rombel']) . ".csv";

        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        $output = fopen('php://output', 'w');
        fputcsv($output, ['NISN', 'Nama Siswa', 'Hadir', 'Sakit', 'Izin', 'Alpha']);

        foreach ($students as $s) {
            fputcsv($output, [$s['nisn'], $s['nama_lengkap'], 0, 0, 0, 0]);
        }

        fclose($output);
        exit();
    }

    private function normalizeImportedDateHeader($value): ?string
    {
        $value = preg_replace('/[\x00-\x1F\x7F-\xFF]/', '', trim((string) $value));
        $value = ltrim($value, "'");

        $formats = ['Y-m-d', 'd/m/Y'];
        foreach ($formats as $format) {
            $date = \DateTime::createFromFormat('!' . $format, $value);
            $errors = \DateTime::getLastErrors();
            $hasErrors = ($errors['warning_count'] ?? 0) > 0 || ($errors['error_count'] ?? 0) > 0;

            if ($date instanceof \DateTime && !$hasErrors && $date->format($format) === $value) {
                return $date->format('Y-m-d');
            }
        }

        return null;
    }

    // FUNGSI SINKRONISASI REKAP ABSENSI YANG SUDAH DIPERBAIKI
    private function syncRekapAbsensi($db, $siswa_id, $rombel_id, $id_ta)
    {
        // Hitung total absensi siswa HANYA di rombel ini
        $harian = $db->table('absensi_harian')
            ->where('siswa_id', $siswa_id)
            ->where('rombel_id', $rombel_id)
            ->get()->getResultArray();

        $sakit = 0;
        $izin = 0;
        $alpha = 0;
        foreach ($harian as $h) {
            if ($h['status'] === 'Sakit') $sakit++;
            if ($h['status'] === 'Izin') $izin++;
            if ($h['status'] === 'Alpha') $alpha++;
        }

        // Terapkan tahun_ajaran_id KUSUS pada rekap_absensi
        $rekap = $db->table('rekap_absensi')
            ->where('siswa_id', $siswa_id)
            ->where('tahun_ajaran_id', $id_ta)
            ->get()->getRowArray();

        if ($rekap) {
            $db->table('rekap_absensi')->where('id', $rekap['id'])->update([
                'sakit' => $sakit,
                'izin' => $izin,
                'alpha' => $alpha
            ]);
        } else {
            $db->table('rekap_absensi')->insert([
                'siswa_id'        => $siswa_id,
                'tahun_ajaran_id' => $id_ta,
                'sakit'           => $sakit,
                'izin'            => $izin,
                'alpha'           => $alpha
            ]);
        }
    }
}
