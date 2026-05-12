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

        $data = [
            'title'             => 'Absensi Kelas Harian',
            'user'              => session()->get('nama_lengkap') ?? 'Wali Kelas',
            'namaLengkap'       => session()->get('nama_lengkap') ?? session()->get('username') ?? 'Wali Kelas',
            'nama_sekolah'      => $nama_sekolah,
            'nama_rombel'       => $nama_rombel,
            'is_wali_kelas_sah' => ($rombel_info !== null),
            'navigations'       => $this->getSidebarMenu(),
            'color'             => $color
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
                return $this->response->setJSON(['students' => [], 'attendance' => []]);
            }

            $rombel_id = $rombel_info['id'];
            $id_ta = $rombel_info['id_tahun_ajaran'];
            $db = \Config\Database::connect();

            // 1. Ambil info semester dari TA aktif
            $ta_aktif = $db->table('tahun_ajaran')->where('id', $id_ta)->get()->getRowArray();
            $semester = $ta_aktif ? $ta_aktif['semester'] : 'Ganjil';

            // 2. MENGGUNAKAN MESIN WAKTU UNTUK MENGAMBIL DAFTAR ABSENSI
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

            // PERBAIKAN: Tidak menggunakan tahun_ajaran_id di absensi_harian
            $absensiRecords = $db->table('absensi_harian')
                ->where('rombel_id', $rombel_id)
                ->orderBy('tanggal', 'ASC')
                ->get()->getResultArray();

            $attendanceMap = [];
            foreach ($absensiRecords as $absen) {
                $tanggal = $absen['tanggal'];
                if (!isset($attendanceMap[$tanggal])) {
                    $attendanceMap[$tanggal] = ['date' => $tanggal, 'records' => []];
                }

                $kode = 'A';
                if ($absen['status'] === 'Hadir') $kode = 'H';
                elseif ($absen['status'] === 'Sakit') $kode = 'S';
                elseif ($absen['status'] === 'Izin') $kode = 'I';
                elseif ($absen['status'] === 'Alpha') $kode = 'A';

                $attendanceMap[$tanggal]['records'][$absen['siswa_id']] = $kode;
            }

            $studentList = [];
            foreach ($students as $s) {
                // Evaluasi fallback foto di backend (Prioritas: users.foto_profil -> siswa.foto_siswa)
                $foto_profil = $s['foto_profil'] ?? '';
                $foto_siswa  = $s['foto_siswa'] ?? '';
                $foto_final  = !empty($foto_profil) ? $foto_profil : $foto_siswa;
            
                $studentList[] = [
                    'id'       => $s['id'],
                    'name'     => $s['nama_lengkap'], 
                    'nisn'     => $s['nisn'] ?? $s['nis'] ?? '-',
                    'foto_fix' => $foto_final // Kita tetap pakai nama 'foto_fix' agar JS tidak error
                ];
            }

            return $this->response->setJSON([
                'students'   => $studentList,
                'attendance' => array_values($attendanceMap)
            ]);
        } catch (\Throwable $e) {
            return $this->response->setJSON(['error' => 'DB Error: ' . $e->getMessage()])->setStatusCode(500);
        }
    }

    public function saveAbsensi()
    {
        $json = $this->request->getJSON();
        $tanggal = $json->date;
        $records = $json->records;

        $rombel_info = $this->getRombelInfoWaliKelas();

        if (!$rombel_info) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Akses ditolak! Anda belum ditetapkan sebagai Wali Kelas.'
            ]);
        }

        $rombel_id = $rombel_info['id'];
        $id_ta = $rombel_info['id_tahun_ajaran'];

        date_default_timezone_set('Asia/Jakarta');
        if ($tanggal > date('Y-m-d')) {
            return $this->response->setJSON(['success' => false, 'message' => 'Tidak dapat mengisi absensi untuk tanggal di masa depan.']);
        }

        $db = \Config\Database::connect();
        $db->transBegin();

        try {
            $siswa_ids_updated = [];

            foreach ($records as $siswa_id => $statusKode) {
                $statusDb = 'Alpha';
                if ($statusKode === 'H') $statusDb = 'Hadir';
                elseif ($statusKode === 'S') $statusDb = 'Sakit';
                elseif ($statusKode === 'I') $statusDb = 'Izin';
                elseif ($statusKode === 'A') $statusDb = 'Alpha';

                // PERBAIKAN: Tidak menggunakan tahun_ajaran_id di absensi_harian
                $existing = $db->table('absensi_harian')->where([
                    'siswa_id'  => $siswa_id,
                    'rombel_id' => $rombel_id,
                    'tanggal'   => $tanggal
                ])->get()->getRowArray();

                if ($existing) {
                    if ($existing['status'] !== $statusDb) {
                        $db->table('absensi_harian')->where('id', $existing['id'])->update(['status' => $statusDb]);
                        $siswa_ids_updated[] = $siswa_id;
                    }
                } else {
                    $db->table('absensi_harian')->insert([
                        'siswa_id'  => $siswa_id,
                        'rombel_id' => $rombel_id,
                        'tanggal'   => $tanggal,
                        'status'    => $statusDb
                    ]);
                    $siswa_ids_updated[] = $siswa_id;
                }
            }

            // AUTO SYNC KE TABEL REKAP_ABSENSI
            if (!empty($siswa_ids_updated) && $db->tableExists('rekap_absensi')) {
                foreach (array_unique($siswa_ids_updated) as $sid) {
                    $this->syncRekapAbsensi($db, $sid, $rombel_id, $id_ta);
                }
            }

            $db->transCommit();
            return $this->response->setJSON(['success' => true, 'message' => 'Data absensi berhasil disimpan!']);
        } catch (\Throwable $e) {
            $db->transRollback();
            return $this->response->setJSON(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        }
    }

    public function importAbsensi()
    {
        $file = $this->request->getFile('file_csv');
        $rombel_info = $this->getRombelInfoWaliKelas();

        if (!$rombel_info) {
            return $this->response->setJSON(['success' => false, 'message' => 'Akses ditolak!']);
        }

        $rombel_id = $rombel_info['id'];
        $id_ta = $rombel_info['id_tahun_ajaran'];

        if (!$file || !$file->isValid() || strtolower($file->getExtension()) !== 'csv') {
            return $this->response->setJSON(['success' => false, 'message' => 'Harap unggah file CSV.']);
        }

        $db = \Config\Database::connect();
        $handle = fopen($file->getTempName(), "r");

        $firstLine = fgets($handle);
        $delimiter = strpos($firstLine, ';') !== false ? ';' : ',';
        rewind($handle);

        $dateColumns = [];

        while (($row = fgetcsv($handle, 1000, $delimiter)) !== FALSE) {
            foreach ($row as $index => $colName) {
                $normalizedDate = $this->normalizeImportedDateHeader($colName);
                if ($normalizedDate !== null) {
                    $dateColumns[$index] = $normalizedDate;
                }
            }
            if (!empty($dateColumns)) break;
        }

        if (empty($dateColumns)) {
            fclose($handle);
            return $this->response->setJSON(['success' => false, 'message' => 'Format kolom tanggal YYYY-MM-DD atau DD/MM/YYYY tidak ditemukan.']);
        }

        $jumlah_diupdate = 0;
        date_default_timezone_set('Asia/Jakarta');
        $hari_ini = date('Y-m-d');

        $db->transBegin();
        $siswa_ids_updated = [];

        try {
            while (($data = fgetcsv($handle, 1000, $delimiter)) !== FALSE) {
                $nisn = preg_replace('/[\x00-\x1F\x7F-\xFF]/', '', trim($data[0]));
                if (empty($nisn) || strtolower($nisn) == 'nisn') continue;

                $siswa = $db->table('siswa')->select('id')->where('nisn', $nisn)->get()->getRowArray();
                if (!$siswa) continue;

                $siswa_id = $siswa['id'];

                foreach ($dateColumns as $index => $tanggal) {
                    if (!isset($data[$index])) continue;
                    if ($tanggal > $hari_ini) continue;

                    $statusRaw = strtoupper(trim($data[$index]));
                    $statusDb = null;

                    if (in_array($statusRaw, ['H', 'HADIR', 'HADIR '])) $statusDb = 'Hadir';
                    elseif (in_array($statusRaw, ['S', 'SAKIT'])) $statusDb = 'Sakit';
                    elseif (in_array($statusRaw, ['I', 'IZIN', 'IJIN'])) $statusDb = 'Izin';
                    elseif (in_array($statusRaw, ['A', 'ALPHA', 'ALPA'])) $statusDb = 'Alpha';

                    if ($statusDb !== null) {
                        // PERBAIKAN: Tidak menggunakan tahun_ajaran_id di absensi_harian
                        $existing = $db->table('absensi_harian')->where([
                            'siswa_id'  => $siswa_id,
                            'rombel_id' => $rombel_id,
                            'tanggal'   => $tanggal
                        ])->get()->getRowArray();

                        if ($existing) {
                            if ($existing['status'] !== $statusDb) {
                                $db->table('absensi_harian')->where('id', $existing['id'])->update(['status' => $statusDb]);
                                $jumlah_diupdate++;
                                $siswa_ids_updated[] = $siswa_id;
                            }
                        } else {
                            $db->table('absensi_harian')->insert([
                                'siswa_id'  => $siswa_id,
                                'rombel_id' => $rombel_id,
                                'tanggal'   => $tanggal,
                                'status'    => $statusDb
                            ]);
                            $jumlah_diupdate++;
                            $siswa_ids_updated[] = $siswa_id;
                        }
                    }
                }
            }

            if (!empty($siswa_ids_updated) && $db->tableExists('rekap_absensi')) {
                foreach (array_unique($siswa_ids_updated) as $sid) {
                    $this->syncRekapAbsensi($db, $sid, $rombel_id, $id_ta);
                }
            }

            fclose($handle);
            $db->transCommit();
            return $this->response->setJSON(['success' => true, 'message' => "Impor Selesai! $jumlah_diupdate data absensi berhasil diproses."]);
        } catch (\Throwable $e) {
            fclose($handle);
            $db->transRollback();
            return $this->response->setJSON(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        }
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
