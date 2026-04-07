<?php
namespace App\Controllers\Admin;

use App\Controllers\AdminBaseController;
use CodeIgniter\Files\File;

class BackupController extends AdminBaseController
{
    protected $backupPath;

    public function __construct()
    {
        $paths = new \Config\Paths();
        $this->backupPath = $paths->writableDirectory . '/backups/';
        if (!is_dir($this->backupPath)) {
            mkdir($this->backupPath, 0777, true);
        }
    }

    public function index(): string
    {
        helper('filesystem');
        $db = \Config\Database::connect();
        $dbName = $db->getDatabase();

        // 1. Ambil Statistik Database Dinamis (Ukuran & Baris per Tabel)
        $queryInfo = $db->query("SELECT TABLE_NAME, TABLE_ROWS, (DATA_LENGTH + INDEX_LENGTH) AS size_bytes FROM information_schema.TABLES WHERE TABLE_SCHEMA = ?", [$dbName])->getResultArray();
        
        $tableStats = [];
        foreach($queryInfo as $row) {
            $tableStats[$row['TABLE_NAME']] = [
                'rows' => $row['TABLE_ROWS'] ?? 0,
                'size' => $row['size_bytes'] ?? 0
            ];
        }

        // Kelompokkan mapping sesuai UI
        $map = [
            'siswa_ortu' => ['siswa', 'orangtua_wali'],
            'nilai_rapor'=> ['absensi_harian', 'catatan_wali_kelas', 'deskripsi_nilai', 'nilai_akademik', 'nilai_ekskul', 'penilaian_proyek', 'prestasi_siswa', 'rubrik_proyek', 'target_tahfidz'],
            'master'     => ['guru_tendik', 'kkm_mapel', 'mata_pelajaran', 'rombel', 'tahun_ajaran', 'ref_juz', 'ref_surah'],
            'konfigurasi'=> ['identitas_sekolah', 'roles', 'users', 'user_roles', 'audit_logs', 'migrations', 'backup_settings']
        ];

        $stats = [];
        foreach($map as $category => $tables) {
            $catRows = 0; $catSize = 0;
            foreach($tables as $t) {
                if(isset($tableStats[$t])) {
                    $catRows += $tableStats[$t]['rows'];
                    $catSize += $tableStats[$t]['size'];
                }
            }
            $stats[$category] = [
                'rows' => $catRows,
                'size_kb' => round($catSize / 1024, 1),
                'size_mb' => round($catSize / 1048576, 2)
            ];
        }

        // 2. Ambil Setting Otomatis dari DB
        $settings = $db->table('backup_settings')->where('id', 1)->get()->getRowArray();
        if(!$settings) {
            $settings = ['auto_backup'=>1, 'frequency'=>'daily', 'execution_time'=>'02:00:00', 'retention_days'=>30, 'notify_email'=>1];
        }

        // 3. File Explorer Backup
        $files = get_filenames($this->backupPath);
        $backups = [];
        $totalSize = 0;

        foreach ($files as $file) {
            if(pathinfo($file, PATHINFO_EXTENSION) == 'sql' || pathinfo($file, PATHINFO_EXTENSION) == 'enc') {
                $filePath = $this->backupPath . $file;
                $size = filesize($filePath);
                $totalSize += $size;
                $backups[] = [
                    'filename' => $file,
                    'size'     => round($size / 1048576, 2) . ' MB',
                    'date'     => date('Y-m-d H:i:s', filemtime($filePath)),
                    'timestamp'=> filemtime($filePath)
                ];
            }
        }

        usort($backups, function($a, $b) { return $b['timestamp'] - $a['timestamp']; });

        $data = [
            'user'        => 'Admin',
            'navigations' => $this->getSidebarMenu(),
            'color'       => $this->getColor(),
            'backups'     => $backups,
            'total_files' => count($backups),
            'total_size'  => round($totalSize / 1048576, 2) . ' MB',
            'stats'       => $stats,
            'settings'    => $settings
        ];
        
        return view('admin/backup', $data); 
    }

    public function saveSettings()
    {
        if (!$this->request->isAJAX()) return $this->response->setStatusCode(404);
        try {
            $db = \Config\Database::connect();
            $data = [
                'auto_backup'   => $this->request->getPost('auto_backup') ? 1 : 0,
                'frequency'     => $this->request->getPost('frequency'),
                'execution_time'=> $this->request->getPost('execution_time'),
                'retention_days'=> $this->request->getPost('retention_days'),
                'notify_email'  => $this->request->getPost('notify_email') ? 1 : 0,
            ];
            $db->table('backup_settings')->where('id', 1)->update($data);
            return $this->response->setJSON(['status' => 'success', 'message' => 'Pengaturan jadwal berhasil disimpan!']);
        } catch (\Exception $e) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Gagal menyimpan: ' . $e->getMessage()]);
        }
    }

    public function doBackup()
    {
        if (!$this->request->isAJAX()) return $this->response->setStatusCode(404);

        try {
            $db = \Config\Database::connect();
            $mode = $this->request->getPost('mode') ?? 'full'; 
            $catsJson = $this->request->getPost('categories');
            $selectedCats = $catsJson ? json_decode($catsJson, true) : [];

            $map = [
                'siswa_ortu' => ['siswa', 'orangtua_wali'],
                'nilai_rapor'=> ['absensi_harian', 'catatan_wali_kelas', 'deskripsi_nilai', 'nilai_akademik', 'nilai_ekskul', 'penilaian_proyek', 'prestasi_siswa', 'rubrik_proyek', 'target_tahfidz'],
                'master'     => ['guru_tendik', 'kkm_mapel', 'mata_pelajaran', 'rombel', 'tahun_ajaran', 'ref_juz', 'ref_surah'],
                'konfigurasi'=> ['identitas_sekolah', 'roles', 'users', 'user_roles', 'audit_logs', 'migrations', 'backup_settings']
            ];

            $tablesToBackup = [];
            if ($mode == 'full' || empty($selectedCats)) {
                $tablesToBackup = $db->listTables();
            } else {
                foreach($selectedCats as $cat) {
                    if(isset($map[$cat])) {
                        $tablesToBackup = array_merge($tablesToBackup, $map[$cat]);
                    }
                }
                $existingTables = $db->listTables();
                $tablesToBackup = array_intersect($tablesToBackup, $existingTables);
            }

            $sql = "-- Backup Database Rapor SMPIT (" . strtoupper($mode) . ")\n";
            $sql .= "-- Tanggal: " . date('Y-m-d H:i:s') . "\n\n";
            $sql .= "SET FOREIGN_KEY_CHECKS=0;\n\n";

            foreach ($tablesToBackup as $table) {
                $query = $db->query("SHOW CREATE TABLE `$table`");
                $row = $query->getRowArray();
                $sql .= "DROP TABLE IF EXISTS `$table`;\n";
                $sql .= $row['Create Table'] . ";\n\n";

                $queryData = $db->query("SELECT * FROM `$table`");
                $resultData = $queryData->getResultArray();

                if (!empty($resultData)) {
                    foreach ($resultData as $row) {
                        $sql .= "INSERT INTO `$table` VALUES(";
                        $values = [];
                        foreach ($row as $value) {
                            if (is_null($value)) {
                                $values[] = "NULL";
                            } else {
                                $escaped = str_replace(['\\', "\0", "\n", "\r", "'", '"', "\x1a"], ['\\\\', '\\0', '\\n', '\\r', "\\'", '\\"', '\\Z'], $value);
                                $values[] = "'" . $escaped . "'";
                            }
                        }
                        $sql .= implode(',', $values) . ");\n";
                    }
                    $sql .= "\n";
                }
            }
            $sql .= "SET FOREIGN_KEY_CHECKS=1;\n";

            $prefix = ($mode == 'full') ? 'Full' : 'Partial';
            $filename = 'Backup_' . $prefix . '_' . date('Ymd_His') . '.sql';
            file_put_contents($this->backupPath . $filename, $sql);

            return $this->response->setJSON([
                'status'  => 'success',
                'message' => 'Backup ' . $prefix . ' berhasil dibuat!',
                'file'    => $filename
            ]);

        } catch (\Exception $e) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Gagal melakukan backup: ' . $e->getMessage()]);
        }
    }

    public function download($filename)
    {
        $filePath = $this->backupPath . $filename;
        if (file_exists($filePath)) return $this->response->download($filePath, null);
        return redirect()->back()->with('error', 'File backup tidak ditemukan.');
    }

    public function delete()
    {
        if (!$this->request->isAJAX()) return $this->response->setStatusCode(404);
        $filename = $this->request->getPost('filename');
        $filePath = $this->backupPath . $filename;
        if (file_exists($filePath)) {
            unlink($filePath);
            return $this->response->setJSON(['status' => 'success', 'message' => 'File backup berhasil dihapus.']);
        }
        return $this->response->setJSON(['status' => 'error', 'message' => 'File tidak ditemukan.']);
    }

    // ==========================================================
    // MESIN RESTORE ADVANCE (Mampu Menelan File phpMyAdmin 18MB)
    // ==========================================================
    public function restore()
    {
        if (!$this->request->isAJAX()) return $this->response->setStatusCode(404);

        // KUNCI UTAMA 1: Tambah batas memori dan waktu eksekusi untuk menelan file 18MB
        ini_set('memory_limit', '512M');
        ini_set('max_execution_time', '300'); // 5 Menit toleransi loading

        $fileToRestore = '';
        $uploadedFile = $this->request->getFile('backup_file');
        
        if ($uploadedFile) {
            // KUNCI UTAMA 2: Melacak limit upload dari konfigurasi hosting
            if (!$uploadedFile->isValid()) {
                $errCode = $uploadedFile->getError();
                if ($errCode == UPLOAD_ERR_INI_SIZE || $errCode == UPLOAD_ERR_FORM_SIZE) {
                    return $this->response->setJSON(['status' => 'error', 'message' => 'Upload Ditolak: Ukuran file (18MB) melampaui batas maksimal upload di server hosting Anda. Silakan upload via File Manager / CPanel.']);
                }
                return $this->response->setJSON(['status' => 'error', 'message' => 'Gagal membaca file upload: ' . $uploadedFile->getErrorString()]);
            }
            $fileToRestore = $uploadedFile->getTempName();
        } else {
            $filename = $this->request->getPost('filename');
            if ($filename) $fileToRestore = $this->backupPath . $filename;
        }

        if (empty($fileToRestore) || !file_exists($fileToRestore)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'File SQL tidak ditemukan di sistem.']);
        }

        try {
            $db = \Config\Database::connect();
            $sql = file_get_contents($fileToRestore);

            // KUNCI UTAMA 3: Hapus DEFINER (Penyebab utama error saat pindah ke Shared Hosting)
            $sql = preg_replace('/DEFINER[ ]*=[ ]*[^\s]*/', '', $sql);
            
            // Samakan format baris baru Windows/Mac/Linux
            $sql = str_replace("\r\n", "\n", $sql);
            
            // KUNCI UTAMA 4: Pecah berdasarkan titik koma HANYA jika berada di akhir baris
            // Ini mencegah titik koma di dalam text (varchar) ikut terpotong!
            $queries = explode(";\n", $sql);

            $db->query('SET FOREIGN_KEY_CHECKS=0'); // Matikan relasi sementara
            try { $db->query('SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO"'); } catch(\Exception $e) {}

            $successCount = 0;
            $errorCount = 0;
            $lastError = '';

            // KUNCI UTAMA 5: Eksekusi satu per satu TANPA Transaksi
            // Supaya jika ada 1 error peringatan, ribuan data lainnya tetap aman masuk
            foreach ($queries as $query) {
                $query = trim($query);
                if (empty($query)) continue;
                
                // Lewati baris komentar murni
                if (strpos($query, '--') === 0 || (strpos($query, '/*') === 0 && strpos($query, '/*!') !== 0)) {
                    continue;
                }

                try {
                    $db->query($query);
                    $successCount++;
                } catch (\Exception $e) {
                    $errorCount++;
                    $lastError = $e->getMessage();
                }
            }

            $db->query('SET FOREIGN_KEY_CHECKS=1'); // Hidupkan relasi kembali

            if ($successCount === 0 && $errorCount > 0) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Gagal merestore. Error MySQL Terakhir: ' . $lastError]);
            }

            $msg = "Restore berhasil! $successCount struktur/data dieksekusi.";
            if ($errorCount > 0) {
                $msg .= " (Ada $errorCount query dilewati karena konflik data lama).";
            }

            return $this->response->setJSON(['status' => 'success', 'message' => $msg]);

        } catch (\Exception $e) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Terjadi kesalahan sistem backend: ' . $e->getMessage()]);
        }
    }
    // ==========================================================
    // MESIN AUTO BACKUP (DIPANGGIL OLEH CRONJOB CPANEL/SERVER)
    // ==========================================================
    public function runAutoBackup($secret_token = null)
    {
        // 1. Proteksi Keamanan (Agar tidak sembarang orang mengetik URL ini)
        $mySecretToken = 'SMPIT-AdDurrah-Secure-Cron-2026'; // Ganti token ini sesuai selera
        if ($secret_token !== $mySecretToken) {
            return $this->response->setStatusCode(403)->setBody('Akses Ditolak: Token tidak valid.');
        }

        $db = \Config\Database::connect();
        $settings = $db->table('backup_settings')->where('id', 1)->get()->getRowArray();

        // 2. Cek apakah fitur Auto Backup diaktifkan
        if (!$settings || empty($settings['auto_backup'])) {
            return $this->response->setBody('Auto Backup sedang dinonaktifkan di pengaturan sistem.');
        }

        try {
            // 3. Proses Backup (Full Database)
            $tablesToBackup = $db->listTables();
            $sql = "-- AUTO BACKUP FULL DATABASE\n";
            $sql .= "-- Tanggal: " . date('Y-m-d H:i:s') . "\n\n";
            $sql .= "SET FOREIGN_KEY_CHECKS=0;\n\n";

            foreach ($tablesToBackup as $table) {
                $query = $db->query("SHOW CREATE TABLE `$table`")->getRowArray();
                $sql .= "DROP TABLE IF EXISTS `$table`;\n";
                $sql .= $query['Create Table'] . ";\n\n";

                $resultData = $db->query("SELECT * FROM `$table`")->getResultArray();
                if (!empty($resultData)) {
                    foreach ($resultData as $row) {
                        $sql .= "INSERT INTO `$table` VALUES(";
                        $values = [];
                        foreach ($row as $value) {
                            if (is_null($value)) {
                                $values[] = "NULL";
                            } else {
                                $escaped = str_replace(['\\', "\0", "\n", "\r", "'", '"', "\x1a"], ['\\\\', '\\0', '\\n', '\\r', "\\'", '\\"', '\\Z'], $value);
                                $values[] = "'" . $escaped . "'";
                            }
                        }
                        $sql .= implode(',', $values) . ");\n";
                    }
                    $sql .= "\n";
                }
            }
            $sql .= "SET FOREIGN_KEY_CHECKS=1;\n";

            // Simpan File
            $filename = 'Auto_Full_' . date('Ymd_His') . '.sql';
            file_put_contents($this->backupPath . $filename, $sql);

            // 4. Logika Retensi (Hapus file lama sesuai pengaturan: 7, 30, atau 60 hari)
            $retentionDays = isset($settings['retention_days']) ? (int)$settings['retention_days'] : 30;
            $cutoffTime = time() - ($retentionDays * 86400); // 86400 detik = 1 hari
            
            helper('filesystem');
            $files = get_filenames($this->backupPath);
            $deletedCount = 0;

            foreach ($files as $file) {
                $filePath = $this->backupPath . $file;
                // Jika file lebih tua dari batas retensi, hapus!
                if (filemtime($filePath) < $cutoffTime) {
                    unlink($filePath);
                    $deletedCount++;
                }
            }

            // (Opsional) 5. Kirim Email Notifikasi jika fitur notify_email = 1
            // if ($settings['notify_email']) { ... logika kirim email ci4 ... }

            return $this->response->setBody("CRON SUCCESS: Backup [$filename] berhasil dibuat. $deletedCount file lama dihapus (Retensi: $retentionDays hari).");

        } catch (\Exception $e) {
            return $this->response->setStatusCode(500)->setBody('CRON ERROR: ' . $e->getMessage());
        }
    }
    // ==========================================================
    // MESIN AUTO BACKUP (PSEUDO-CRON VIA LOGIN ADMIN)
    // ==========================================================
    public function runPseudoCron()
    {
        if (!$this->request->isAJAX()) return $this->response->setJSON(['status' => 'ignored']);

        // Matikan syarat AJAX sebentar untuk mempermudah deteksi error jika diperlukan
        // if (!$this->request->isAJAX()) return $this->response->setJSON(['status' => 'ignored']);

        $db = \Config\Database::connect();
        $settings = $db->table('backup_settings')->where('id', 1)->get()->getRowArray();
        
        // 1. Cek apakah fitur Auto Backup diaktifkan
        if (!$settings || empty($settings['auto_backup'])) {
            return $this->response->setJSON(['status' => 'failed', 'reason' => 'Auto backup di pengaturan berstatus OFF/0']);
        }

        // 2. Cek apakah sudah waktunya
        $execTime = $settings['execution_time'] ?? '02:00:00';
        $currentTime = date('H:i:s');
        if ($currentTime < $execTime) {
            return $this->response->setJSON([
                'status' => 'failed', 
                'reason' => "Belum waktunya. Sekarang jam $currentTime, jadwal di setting jam $execTime"
            ]);
        }

        // 3. Cek apakah HARI INI sudah pernah dibackup otomatis?
        $today = date('Ymd');
        helper('filesystem');
        $files = get_filenames($this->backupPath);
        foreach ($files as $file) {
            if (strpos($file, 'Auto_Full_' . $today) === 0) {
                return $this->response->setJSON(['status' => 'failed', 'reason' => 'Backup hari ini sudah dilakukan sebelumnya']);
            }
        }

        // 4. JIKA BELUM, LAKUKAN BACKUP SEKARANG!
        try {
            $tablesToBackup = $db->listTables();
            $sql = "-- AUTO BACKUP FULL DATABASE (Pseudo-Cron)\n";
            $sql .= "-- Tanggal: " . date('Y-m-d H:i:s') . "\n\n";
            $sql .= "SET FOREIGN_KEY_CHECKS=0;\n\n";

            foreach ($tablesToBackup as $table) {
                $query = $db->query("SHOW CREATE TABLE `$table`")->getRowArray();
                $sql .= "DROP TABLE IF EXISTS `$table`;\n";
                $sql .= $query['Create Table'] . ";\n\n";

                $resultData = $db->query("SELECT * FROM `$table`")->getResultArray();
                if (!empty($resultData)) {
                    foreach ($resultData as $row) {
                        $sql .= "INSERT INTO `$table` VALUES(";
                        $values = [];
                        foreach ($row as $value) {
                            if (is_null($value)) $values[] = "NULL";
                            else {
                                $escaped = str_replace(['\\', "\0", "\n", "\r", "'", '"', "\x1a"], ['\\\\', '\\0', '\\n', '\\r', "\\'", '\\"', '\\Z'], $value);
                                $values[] = "'" . $escaped . "'";
                            }
                        }
                        $sql .= implode(',', $values) . ");\n";
                    }
                    $sql .= "\n";
                }
            }
            $sql .= "SET FOREIGN_KEY_CHECKS=1;\n";

            $filename = 'Auto_Full_' . date('Ymd_His') . '.sql';
            file_put_contents($this->backupPath . $filename, $sql);

            // 5. Logika Retensi (Hapus file lama agar disk tidak penuh)
            $retentionDays = isset($settings['retention_days']) ? (int)$settings['retention_days'] : 30;
            $cutoffTime = time() - ($retentionDays * 86400);
            foreach ($files as $file) {
                $filePath = $this->backupPath . $file;
                if (filemtime($filePath) < $cutoffTime) {
                    unlink($filePath);
                }
            }

            // 6. KIRIM NOTIFIKASI KE NAVBAR ADMIN
            if ($db->tableExists('notifikasi')) {
                $db->table('notifikasi')->insert([
                    'user_id'    => session()->get('id'),
                    'judul'      => 'Auto Backup Berhasil',
                    'pesan'      => 'Sistem telah mencadangkan database secara otomatis: ' . $filename,
                    'tipe'       => 'success',
                    'link'       => 'admin/backup',
                    'is_read'    => 0,
                    'created_at' => date('Y-m-d H:i:s')
                ]);
            }

            return $this->response->setJSON(['status' => 'ok', 'notif_created' => true]);

        } catch (\Exception $e) {
            // Jika Gagal, kirim notifikasi Error!
            if ($db->tableExists('notifikasi')) {
                $db->table('notifikasi')->insert([
                    'user_id'    => session()->get('id'),
                    'judul'      => 'Auto Backup GAGAL',
                    'pesan'      => 'Peringatan! Auto Backup gagal dieksekusi. Error: ' . substr($e->getMessage(), 0, 100),
                    'tipe'       => 'error',
                    'link'       => 'admin/backup',
                    'is_read'    => 0,
                    'created_at' => date('Y-m-d H:i:s')
                ]);
            }
            return $this->response->setJSON(['status' => 'error']);
        }
    }
}