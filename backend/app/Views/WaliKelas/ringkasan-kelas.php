<?= $this->extend('layout/main') ?>

<?= $this->section('title') ?>
Dashboard Wali Kelas - Rapor Digital SMPIT Ad Durrah
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<link rel="stylesheet" href="<?= base_url('assets/css/WaliKelas/rinkasan-kelas.css') ?>">
<style>
    :root {
        --warna-primary: <?= $color['warna_primary'] ?? '#10b981' ?>;
        --warna-secondary: <?= $color['warna_secondary'] ?? '#ecfdf5' ?>;
        --warna-scroll: <?= $color['warna_primary'] ?>; 
    }

    /* Utility dinamis yang mendukung Dark Mode */
    .text-dinamis {
        color: var(--warna-primary) !important;
    }

    .bg-dinamis {
        background-color: var(--warna-primary) !important;
    }

    .bg-dinamis-light {
        background-color: var(--warna-secondary) !important;
    }

    .border-dinamis {
        border-color: var(--warna-primary) !important;
    }

    /* Dark Mode Overrides (menyesuaikan referensi Hak Akses) */
    html.dark .text-dinamis {
        color: color-mix(in srgb, var(--warna-primary) 80%, white) !important;
    }

    html.dark .bg-dinamis-light {
        background-color: rgba(255, 255, 255, 0.05) !important;
    }

    html.dark .bg-white {
        background-color: #1e293b !important;
        /* slate-800 */
        border-color: #334155 !important;
        /* slate-700 */
    }

    /* Custom scrollbar untuk Dark Mode */
    html.dark .custom-scrollbar::-webkit-scrollbar-thumb {
        background-color: var(--warna-primary);
    }

    ::-webkit-scrollbar {
      width: 6px;
    }
    
    ::-webkit-scrollbar-track {
      background: #f1f1f1;
    }
    
    ::-webkit-scrollbar-thumb {
      background-color: var(--warna-scroll);
      border-radius: 3px;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<?php if (!$rombel): ?>
    <div class="bg-amber-50 border border-amber-200 rounded-xl p-6 text-center mb-6">
        <h3 class="text-lg font-bold text-amber-800 mb-2">Akses Terbatas</h3>
        <p class="text-sm text-amber-700">Anda belum ditetapkan sebagai Wali Kelas pada Tahun Ajaran ini. Silakan hubungi Administrator Sekolah.</p>
    </div>
<?php else: ?>

    <div class="mb-6 flex flex-col md:flex-row justify-between items-start md:items-center gap-4 animate-fade-in">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Ringkasan Kelas <?= esc($rombel['nama_rombel'] ?? '-') ?></h1>
            <p class="text-sm text-gray-500 dark:text-slate-400">Tahun Ajaran <?= esc($tahun_ajaran) ?> • Semester <?= esc($semester) ?></p>
        </div>

        <?php if (has_permission('wali_dashboard', 'create')): ?>
            <a href="<?= base_url('wali/absensi-kelas') ?>" class="px-5 py-2.5 bg-dinamis hover-bg-dinamis text-white text-sm font-bold rounded-xl shadow-lg shadow-[var(--warna-primary)]/20 transition-all flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                </svg>
                Input Absen Hari Ini
            </a>
        <?php endif; ?>
    </div>

    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 lg:gap-4 mb-6">
        <div class="bg-white dark:bg-slate-800 rounded-xl p-4 lg:p-5 shadow-sm border border-gray-100 dark:border-slate-700 card-hover">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs lg:text-sm text-gray-500 dark:text-slate-400 mb-1">Total Siswa</p>
                    <p class="text-2xl lg:text-3xl font-bold text-gray-800 dark:text-white"><?= $statistik['total_siswa'] ?></p>
                    <p class="text-xs text-gray-400 dark:text-slate-500 mt-1"><?= $statistik['siswa_l'] ?>L • <?= $statistik['siswa_p'] ?>P</p>
                </div>
                <div class="w-10 h-10 lg:w-12 lg:h-12 rounded-xl bg-dinamis-15 flex items-center justify-center">
                    <svg class="w-5 h-5 lg:w-6 lg:h-6 text-dinamis" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-800 rounded-xl p-4 lg:p-5 shadow-sm border border-gray-100 dark:border-slate-700 card-hover">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs lg:text-sm text-gray-500 dark:text-slate-400 mb-1">Kehadiran Hari Ini</p>
                    <?php if ($statistik['persen_hadir'] > 0): ?>
                        <p class="text-2xl lg:text-3xl font-bold text-gray-800 dark:text-white"><?= $statistik['hadir_hari_ini'] ?></p>
                        <p class="text-xs text-dinamis mt-1 font-bold"><?= $statistik['persen_hadir'] ?>% Hadir</p>
                    <?php else: ?>
                        <p class="text-xl lg:text-2xl font-bold text-gray-800 dark:text-white">-</p>
                        <p class="text-xs text-amber-500 mt-1 font-medium">Belum Diabsen</p>
                    <?php endif; ?>
                </div>
                <div class="w-10 h-10 lg:w-12 lg:h-12 rounded-xl bg-dinamis-light flex items-center justify-center">
                    <svg class="w-5 h-5 lg:w-6 lg:h-6 text-dinamis" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-800 rounded-xl p-4 lg:p-5 shadow-sm border border-gray-100 dark:border-slate-700 card-hover">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs lg:text-sm text-gray-500 dark:text-slate-400 mb-1">Perlu Pembinaan</p>
                    <p class="text-2xl lg:text-3xl font-bold text-amber-600 dark:text-amber-500"><?= $statistik['total_pembinaan'] ?></p>
                    <p class="text-xs text-gray-400 dark:text-slate-500 mt-1">Siswa</p>
                </div>
                <div class="w-10 h-10 lg:w-12 lg:h-12 rounded-xl bg-amber-50 dark:bg-amber-900/30 flex items-center justify-center">
                    <svg class="w-5 h-5 lg:w-6 lg:h-6 text-amber-600 dark:text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-800 rounded-xl p-4 lg:p-5 shadow-sm border border-gray-100 dark:border-slate-700 card-hover">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs lg:text-sm text-gray-500 dark:text-slate-400 mb-1">Kelengkapan Nilai</p>
                    <p class="text-2xl lg:text-3xl font-bold text-gray-800 dark:text-white"><?= $statistik['persen_nilai'] ?>%</p>
                    <p class="text-xs text-dinamis mt-1 font-medium">Input Mapel</p>
                </div>
                <div class="w-10 h-10 lg:w-12 lg:h-12 rounded-xl bg-dinamis-15 flex items-center justify-center">
                    <svg class="w-5 h-5 lg:w-6 lg:h-6 text-dinamis" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 lg:gap-6 mb-6">

        <div class="lg:col-span-2 bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-gray-100 dark:border-slate-700">
            <div class="p-4 lg:p-5 border-b border-gray-100 dark:border-slate-700">
                <div class="flex items-center justify-between">
                    <h2 class="font-semibold text-gray-800 dark:text-white">Siswa Perlu Pembinaan</h2>
                    <a href="<?= base_url('wali/perlu-pembinaan') ?>" class="text-xs text-dinamis hover:opacity-80 font-bold transition-all">Lihat Detail →</a>
                </div>
            </div>
            <div class="p-4 lg:p-5 max-h-80 overflow-y-auto custom-scrollbar">
                <?php if (empty($pembinaan_siswa)): ?>
                    <div class="text-center py-8">
                        <div class="w-12 h-12 bg-dinamis-light dark:bg-slate-700 text-dinamis rounded-full flex items-center justify-center mx-auto mb-3">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                        <p class="text-sm text-gray-500 dark:text-slate-400 font-medium">Alhamdulillah, kondisi kelas kondusif.</p>
                        <p class="text-xs text-gray-400 mt-1">Belum ada catatan pelanggaran atau pembinaan khusus.</p>
                    </div>
                <?php else: ?>
                    <div class="space-y-3">
                        <?php foreach ($pembinaan_siswa as $siswa): ?>
                            <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-slate-700/50 rounded-lg border border-transparent dark:border-slate-600 hover:bg-gray-100 dark:hover:bg-slate-700 transition-colors">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-full bg-dinamis flex items-center justify-center text-white text-xs font-bold shadow-sm">
                                        <?= substr($siswa['nama'], 0, 2) ?>
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-800 dark:text-slate-200 text-sm"><?= esc($siswa['nama']) ?></p>
                                        <p class="text-xs text-gray-500 dark:text-slate-400 font-mono">NIS: <?= esc($siswa['nis']) ?></p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="px-2 py-1 bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-400 text-[10px] font-bold uppercase tracking-wider rounded-full"><?= esc($siswa['kategori']) ?></span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-gray-100 dark:border-slate-700 flex flex-col justify-between">
            <div class="p-4 lg:p-5 border-b border-gray-100 dark:border-slate-700">
                <h2 class="font-semibold text-gray-800 dark:text-white">Karakter &amp; Kedisiplinan</h2>
            </div>
            <div class="p-4 lg:p-5 space-y-5">

                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-lg bg-dinamis-light dark:bg-slate-700 flex items-center justify-center">
                            <svg class="w-4 h-4 text-dinamis" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-800 dark:text-slate-200">Absensi Kelas</p>
                            <p class="text-xs text-gray-500 dark:text-slate-400">Semester ini</p>
                        </div>
                    </div>
                    <span class="text-[11px] font-bold text-dinamis bg-dinamis-light dark:bg-slate-700 px-2.5 py-1 rounded-md">Terpantau</span>
                </div>

                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-lg bg-dinamis-light dark:bg-slate-700 flex items-center justify-center">
                            <svg class="w-4 h-4 text-dinamis" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-800 dark:text-slate-200">Prestasi Positif</p>
                            <p class="text-xs text-gray-500 dark:text-slate-400">Bulan ini</p>
                        </div>
                    </div>
                    <span class="text-lg font-bold text-dinamis">0</span>
                </div>

            </div>
        </div>

    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 lg:gap-6 mb-6">
        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-gray-100 dark:border-slate-700">
            <div class="p-4 lg:p-5 border-b border-gray-100 dark:border-slate-700">
                <div class="flex items-center justify-between">
                    <h2 class="font-semibold text-gray-800 dark:text-white">Progres Input Nilai Guru</h2>
                    <span class="text-xs text-gray-500 dark:text-slate-400"><?= count($progres_mapel) ?> Mapel Terjadwal</span>
                </div>
            </div>
            <div class="p-4 lg:p-5 space-y-4 max-h-80 overflow-y-auto sidebar-scroll custom-scrollbar">
                <?php if (empty($progres_mapel)): ?>
                    <div class="text-center py-6">
                        <p class="text-sm text-gray-500 dark:text-slate-400 font-medium">Jadwal Mapel Belum Ditetapkan</p>
                        <p class="text-xs text-gray-400 mt-1">Admin belum mengatur jadwal pelajaran untuk kelas ini.</p>
                    </div>
                <?php else: ?>
                    <div class="space-y-4">
                        <?php foreach ($progres_mapel as $mapel): ?>
                            <div class="flex items-center gap-3">
                                <div class="flex-1">
                                    <div class="flex items-center justify-between mb-1">
                                        <p class="text-sm font-medium text-gray-800 dark:text-slate-200"><?= esc($mapel['mapel']) ?></p>
                                        <span class="text-xs font-bold text-dinamis"><?= $mapel['persentase'] ?>%</span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <div class="flex-1 h-1.5 bg-gray-100 dark:bg-slate-700 rounded-full overflow-hidden">
                                            <div class="h-full bg-dinamis rounded-full transition-all duration-500 ease-out" style="width: <?= $mapel['persentase'] ?>%"></div>
                                        </div>
                                        <?php if ($mapel['persentase'] >= 100): ?>
                                            <span class="px-1.5 py-0.5 bg-dinamis-light text-dinamis text-[10px] rounded font-bold uppercase tracking-wider">Selesai</span>
                                        <?php else: ?>
                                            <span class="px-1.5 py-0.5 bg-gray-100 dark:bg-slate-700 text-gray-600 dark:text-slate-400 text-[10px] rounded font-medium uppercase tracking-wider">Menunggu</span>
                                        <?php endif; ?>
                                    </div>
                                    <p class="text-[10px] text-gray-400 dark:text-slate-500 mt-1 font-medium">Guru: <?= esc($mapel['guru']) ?></p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-gray-100 dark:border-slate-700 flex flex-col justify-between">
            <div class="p-4 lg:p-5 border-b border-gray-100 dark:border-slate-700">
                <h2 class="font-semibold text-gray-800 dark:text-white">Status Validasi Rapor</h2>
            </div>
            <div class="p-4 lg:p-5">
                <div class="space-y-3 mb-5">

                    <div class="flex items-center gap-3 p-3 bg-gray-50 dark:bg-slate-700/50 rounded-lg border border-transparent dark:border-slate-600">
                        <div class="w-6 h-6 rounded-full bg-dinamis flex items-center justify-center flex-shrink-0">
                            <?php if ($statistik['persen_nilai'] >= 100): ?>
                                <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                                </svg>
                            <?php else: ?>
                                <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 12h.01M12 12h.01M19 12h.01M6 12a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0z" />
                                </svg>
                            <?php endif; ?>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-800 dark:text-slate-200">Nilai & Deskripsi Mapel</p>
                            <p class="text-xs text-gray-400 dark:text-slate-400"><?= $statistik['persen_nilai'] >= 100 ? 'Sudah lengkap' : 'Menunggu input dari guru mapel' ?></p>
                        </div>
                    </div>

                    <div class="flex items-center gap-3 p-3 bg-gray-50 dark:bg-slate-700/50 rounded-lg border border-transparent dark:border-slate-600">
                        <div class="w-6 h-6 rounded-full bg-gray-200 dark:bg-slate-600 flex items-center justify-center flex-shrink-0">
                            <svg class="w-3.5 h-3.5 text-gray-400 dark:text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 8v4m0 4h.01" />
                            </svg>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-600 dark:text-slate-300">Catatan Wali Kelas</p>
                            <p class="text-xs text-gray-400">Menunggu validasi Anda</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-3 p-3 bg-gray-50 dark:bg-slate-700/50 rounded-lg border border-transparent dark:border-slate-600">
                        <div class="w-6 h-6 rounded-full bg-gray-200 dark:bg-slate-600 flex items-center justify-center flex-shrink-0">
                            <svg class="w-3.5 h-3.5 text-gray-400 dark:text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 8v4m0 4h.01" />
                            </svg>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-600 dark:text-slate-300">Kehadiran & Ekstrakurikuler</p>
                            <p class="text-xs text-gray-400">Menunggu finalisasi data</p>
                        </div>
                    </div>

                </div>

                <button class="w-full py-3 <?= $statistik['persen_nilai'] >= 100 ? 'bg-dinamis text-white hover:opacity-90 shadow-lg' : 'bg-gray-100 dark:bg-slate-700 text-gray-400 dark:text-slate-500 cursor-not-allowed' ?> font-semibold rounded-xl transition-all flex items-center justify-center gap-2" <?= $statistik['persen_nilai'] >= 100 ? '' : 'disabled' ?>>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                    <?= $statistik['persen_nilai'] >= 100 ? 'Validasi & Cetak Rapor' : 'Validasi & Cetak Rapor (Belum Siap)' ?>
                </button>
            </div>
        </div>
    <?php endif; ?>

    <?= $this->endSection() ?>