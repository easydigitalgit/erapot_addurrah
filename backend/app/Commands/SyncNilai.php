<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class SyncNilai extends BaseCommand
{
    /**
     * The Command's Group
     *
     * @var string
     */
    protected $group = 'eRapor';

    /**
     * The Command's Name
     *
     * @var string
     */
    protected $name = 'sync:nilai';

    /**
     * The Command's Description
     *
     * @var string
     */
    protected $description = 'Sinkronisasi massal seluruh nilai rapor (seperti tombol "Sinkronkan Semua Nilai")';

    /**
     * The Command's Usage
     *
     * @var string
     */
    protected $usage = 'sync:nilai [options]';

    /**
     * The Command's Options
     *
     * @var array
     */
    protected $options = [
        '--kategori' => 'Kategori penilaian: "Tengah Semester" (default) atau "Akhir Semester"',
        '--rombel'   => 'ID rombel spesifik (opsional)',
        '--mapel'    => 'ID mapel spesifik (opsional)',
    ];

    /**
     * Actually execute a command.
     *
     * @param array $params
     */
    public function run(array $params)
    {
        $scriptPath = ROOTPATH . '../sync_semua_nilai.php';
        if (!file_exists($scriptPath)) {
            $scriptPath = ROOTPATH . 'sync_semua_nilai.php';
        }

        if (file_exists($scriptPath)) {
            $args = [];
            $kategori = CLI::getOption('kategori');
            $rombel   = CLI::getOption('rombel');
            $mapel    = CLI::getOption('mapel');

            if ($kategori) $args[] = '--kategori=' . escapeshellarg($kategori);
            if ($rombel)   $args[] = '--rombel=' . (int)$rombel;
            if ($mapel)    $args[] = '--mapel=' . (int)$mapel;

            $cmd = 'php ' . escapeshellarg($scriptPath) . ' ' . implode(' ', $args);
            passthru($cmd);
        } else {
            CLI::error("File sync_semua_nilai.php tidak ditemukan di root aplikasi.");
        }
    }
}

