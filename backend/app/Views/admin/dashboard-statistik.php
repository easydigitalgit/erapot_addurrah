<?= $this->extend('layout/main') ?>

<?= $this->section('title') ?>
<?= lang('Admin/DashboardStatistik.page_title') ?> - Rapor Digital
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
    :root {
        --warna-scroll: <?= $color['warna_primary'] ?>;
        --warna-primary: <?= $color['warna_primary'] ?? '#10b981' ?>;
        --warna-secondary: <?= $color['warna_secondary'] ?? '#ecfdf5' ?>;
    }

    .text-tema {
        color: var(--warna-primary) !important;
    }

    .bg-tema {
        background-color: var(--warna-primary) !important;
    }

    .bg-tema-light {
        background-color: var(--warna-secondary) !important;
    }

    .border-tema {
        border-color: var(--warna-primary) !important;
    }

    .card-stat {
        transition: all 0.3s ease;
    }

    .card-stat:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1);
    }

    /* CSS Tambahan khusus untuk custom scrollbar yang kemas */
    .custom-scrollbar::-webkit-scrollbar {
        width: 6px;
    }

    .custom-scrollbar::-webkit-scrollbar-track {
        background: #f1f5f9;
        border-radius: 10px;
    }

    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: var(--warna-primary);
        border-radius: 10px;
        opacity: 0.8;
    }

    .dark .custom-scrollbar::-webkit-scrollbar-track {
        background: #1e293b;
    }
</style>
<link rel="stylesheet" href="<?= base_url('assets/css/Admin/dashboard-statistik.css') ?>">
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="mb-6 md:mb-8">
    <h1 class="text-2xl md:text-3xl font-bold text-gray-800 dark:text-white transition-colors"><?= lang('Admin/DashboardStatistik.greeting') ?>, <?= esc($user) ?> 👋</h1>
    <p class="text-sm md:text-base text-gray-600 dark:text-slate-400 mt-1 font-medium transition-colors"><?= lang('Admin/DashboardStatistik.welcome_desc') ?> <span class="font-bold text-tema"><?= esc($school_name) ?></span></p>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="card-stat bg-white dark:bg-slate-800 dark:border-slate-700 rounded-3xl p-6 border border-gray-100 relative overflow-hidden group transition-colors">
        <div class="absolute -right-4 -top-4 w-24 h-24 rounded-full bg-tema opacity-5 group-hover:scale-150 transition-transform duration-700"></div>
        <div class="flex items-start justify-between relative z-10">
            <div>
                <p class="text-xs font-bold text-gray-400 dark:text-slate-400 uppercase tracking-widest mb-1 transition-colors"><?= lang('Admin/DashboardStatistik.active_students') ?></p>
                <h3 class="text-4xl dark:text-white font-black text-gray-800 transition-colors"><?= number_format($total_siswa) ?></h3>
            </div>
            <div class="w-14 h-14 rounded-2xl bg-white dark:bg-slate-700 text-tema flex items-center justify-center shadow-inner transition-colors">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
            </div>
        </div>
    </div>

    <div class="card-stat bg-white dark:bg-slate-800 dark:border-slate-700 rounded-3xl p-6 border border-gray-100 relative overflow-hidden group transition-colors">
        <div class="absolute -right-4 -top-4 w-24 h-24 rounded-full bg-blue-500 opacity-5 group-hover:scale-150 transition-transform duration-700"></div>
        <div class="flex items-start justify-between relative z-10">
            <div>
                <p class="text-xs font-bold text-gray-400 dark:text-slate-400 uppercase tracking-widest mb-1 transition-colors"><?= lang('Admin/DashboardStatistik.total_teachers') ?></p>
                <h3 class="text-4xl dark:text-white font-black text-gray-800 transition-colors"><?= number_format($total_guru_tendik) ?></h3>
                <p class="text-xs font-bold text-blue-600 mt-2 bg-blue-50 dark:bg-slate-700 dark:text-blue-400 inline-block px-2 py-0.5 rounded-md transition-colors"><?= $guru_sudah_input ?> <?= lang('Admin/DashboardStatistik.teachers_inputted') ?></p>
            </div>
            <div class="w-14 h-14 rounded-2xl bg-blue-50 dark:bg-slate-700 text-blue-600 dark:text-blue-400 flex items-center justify-center shadow-inner transition-colors">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                </svg>
            </div>
        </div>
    </div>

    <div class="card-stat bg-white rounded-3xl dark:bg-slate-800 dark:border-slate-700 p-6 border border-gray-100 relative overflow-hidden group transition-colors">
        <div class="absolute -right-4 -top-4 w-24 h-24 rounded-full bg-purple-500 opacity-5 group-hover:scale-150 transition-transform duration-700"></div>
        <div class="flex items-start justify-between relative z-10">
            <div>
                <p class="text-xs font-bold text-gray-400 dark:text-slate-400 uppercase tracking-widest mb-1 transition-colors"><?= lang('Admin/DashboardStatistik.study_groups') ?></p>
                <h3 class="text-4xl font-black dark:text-white text-gray-800 transition-colors"><?= number_format($total_rombel) ?></h3>
            </div>
            <div class="w-14 h-14 rounded-2xl bg-purple-50 dark:bg-slate-700 text-purple-600 dark:text-purple-400 flex items-center justify-center shadow-inner transition-colors">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
            </div>
        </div>
    </div>

    <div class="card-stat bg-tema rounded-3xl p-6 shadow-lg relative overflow-hidden group transition-colors" style="background-image: linear-gradient(135deg, rgba(255,255,255,0.1) 0%, transparent 100%);">
        <div class="absolute -right-4 -bottom-4 w-32 h-32 rounded-full bg-white opacity-10 group-hover:scale-150 transition-transform duration-700"></div>
        <div class="flex items-start justify-between relative z-10">
            <div>
                <p class="text-xs font-bold text-white/80 uppercase tracking-widest mb-1 transition-colors"><?= lang('Admin/DashboardStatistik.active_academic_year') ?></p>
                <h3 id="card-academic-year" class="text-3xl font-black text-white tracking-tight transition-colors"><?= esc($academic_year) ?></h3>
                <p class="text-xs font-bold text-tema bg-white/90 mt-2 inline-block px-3 py-1 rounded-lg shadow-sm transition-colors"><?= lang('Admin/DashboardStatistik.semester') ?> <?= esc($semester) ?></p>
            </div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 xl:grid-cols-3 gap-6 mb-8">
    <div class="xl:col-span-2 bg-white rounded-3xl dark:bg-slate-800 p-6 shadow-sm border border-gray-100 dark:border-slate-700 flex flex-col transition-colors">
        <div class="flex items-center justify-between mb-6 pb-4 border-b border-gray-100 dark:border-slate-700 transition-colors">
            <div>
                <h3 class="text-lg font-bold dark:text-white text-gray-800 transition-colors"><?= lang('Admin/DashboardStatistik.grading_progress') ?></h3>
                <p class="text-xs font-bold text-gray-400 dark:text-slate-400 uppercase tracking-widest mt-1 transition-colors"><?= lang('Admin/DashboardStatistik.grading_desc') ?></p>
            </div>
        </div>

        <div class="space-y-5 flex-grow overflow-y-auto max-h-[400px] pr-2 custom-scrollbar">
            <?php if (!empty($progress_guru)): ?>
                <?php foreach ($progress_guru as $pg): ?>
                    <?php
                    $colorClass = 'blue';
                    if ($pg['peratus'] == 100) $colorClass = 'emerald';
                    elseif ($pg['peratus'] < 50) $colorClass = 'red';
                    elseif ($pg['peratus'] < 80) $colorClass = 'amber';
                    ?>
                    <div class="flex items-center gap-4 group">
                        <div class="w-12 h-12 rounded-2xl bg-<?= $colorClass ?>-50 flex dark:bg-slate-700 items-center justify-center text-<?= $colorClass ?>-600 dark:text-<?= $colorClass ?>-400 font-black shadow-inner group-hover:scale-105 transition-all">
                            <?= esc($pg['inisial']) ?>
                        </div>
                        <div class="flex-1">
                            <div class="flex items-center justify-between mb-1.5">
                                <span class="text-sm font-bold text-gray-800 dark:text-white transition-colors"><?= esc($pg['nama']) ?></span>
                                <span class="text-sm font-black text-<?= $colorClass ?>-600 dark:text-<?= $colorClass ?>-400 transition-colors"><?= $pg['peratus'] ?>%</span>
                            </div>
                            <div class="h-2.5 bg-gray-100 rounded-full overflow-hidden dark:bg-slate-600 shadow-inner transition-colors">
                                <div class="h-full bg-<?= $colorClass ?>-500 rounded-full transition-all duration-1000 ease-out" style="width: <?= $pg['peratus'] ?>%"></div>
                            </div>
                        </div>
                        <span class="hidden sm:inline-block w-24 text-center dark:bg-slate-700 dark:border-slate-600 px-2 py-1 bg-<?= $colorClass ?>-50 border border-<?= $colorClass ?>-100 text-<?= $colorClass ?>-700 dark:text-<?= $colorClass ?>-400 text-[10px] font-black uppercase tracking-wider rounded-lg transition-colors">
                            <?= esc($pg['status']) ?>
                        </span>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="flex items-center justify-center h-full text-gray-400 font-bold transition-colors">
                    <?= lang('Admin/DashboardStatistik.no_grades_data') ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="bg-white rounded-3xl p-6 dark:bg-slate-800 dark:border-slate-700 shadow-sm border border-gray-100 flex flex-col transition-colors">
        <h3 class="text-lg font-bold dark:text-white text-gray-800 mb-1 transition-colors"><?= lang('Admin/DashboardStatistik.tahfidz_character') ?></h3>
        <p class="text-xs font-bold text-gray-400 dark:text-slate-400 uppercase tracking-widest mb-6 pb-4 border-b border-gray-100 dark:border-slate-700 transition-colors"><?= lang('Admin/DashboardStatistik.avg_school_stats') ?></p>

        <div class="mb-8 p-5 bg-white dark:border-slate-600 dark:bg-slate-700 rounded-2xl border border-tema/20 transition-colors">
            <div class="flex items-center justify-between mb-3">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-tema text-white flex items-center justify-center shadow-md transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                    </div>
                    <span class="text-sm font-bold text-tema dark:text-emerald-400 transition-colors"><?= lang('Admin/DashboardStatistik.tahfidz_target') ?></span>
                </div>
                <span class="text-2xl font-black text-tema dark:text-emerald-400 transition-colors"><?= $avg_tahfidz ?>%</span>
            </div>
            <div class="h-2.5 bg-gray-100 dark:bg-slate-800 rounded-full overflow-hidden shadow-inner transition-colors">
                <div class="h-full bg-tema rounded-full transition-all" style="width: <?= $avg_tahfidz ?>%"></div>
            </div>
        </div>

        <div class="space-y-3 flex-grow">
            <div class="flex items-center justify-between p-4 bg-emerald-50 border border-emerald-100 dark:bg-slate-700 dark:border-slate-600 rounded-2xl transition-colors">
                <div class="flex items-center gap-3">
                    <span class="w-3 h-3 bg-emerald-500 rounded-full shadow-[0_0_8px_rgba(16,185,129,0.5)] transition-colors"></span>
                    <span class="text-sm dark:text-emerald-400 font-bold text-emerald-900 transition-colors"><?= lang('Admin/DashboardStatistik.very_good') ?></span>
                </div>
                <span class="text-sm font-black text-emerald-700 dark:bg-slate-600 dark:text-emerald-300 bg-white px-2 py-0.5 rounded-lg shadow-sm transition-colors"><?= number_format($stat_karakter['sangat_baik']) ?></span>
            </div>

            <div class="flex items-center justify-between p-4 bg-blue-50 dark:bg-slate-700 dark:border-slate-600 border border-blue-100 rounded-2xl transition-colors">
                <div class="flex items-center gap-3">
                    <span class="w-3 h-3 bg-blue-500 rounded-full shadow-[0_0_8px_rgba(59,130,246,0.5)] transition-colors"></span>
                    <span class="text-sm dark:text-blue-400 font-bold text-blue-900 transition-colors"><?= lang('Admin/DashboardStatistik.good') ?></span>
                </div>
                <span class="text-sm dark:bg-slate-600 font-black text-blue-700 dark:text-blue-300 bg-white px-2 py-0.5 rounded-lg shadow-sm transition-colors"><?= number_format($stat_karakter['baik']) ?></span>
            </div>

            <div class="flex items-center justify-between p-4 bg-amber-50 border border-amber-100 dark:bg-slate-700 dark:border-slate-600 rounded-2xl transition-colors">
                <div class="flex items-center gap-3">
                    <span class="w-3 h-3 bg-amber-500 rounded-full shadow-[0_0_8px_rgba(245,158,11,0.5)] transition-colors"></span>
                    <span class="text-sm dark:text-amber-400 font-bold text-amber-900 transition-colors"><?= lang('Admin/DashboardStatistik.needs_guidance') ?></span>
                </div>
                <span class="text-sm dark:bg-slate-600 font-black text-amber-700 dark:text-amber-300 bg-white px-2 py-0.5 rounded-lg shadow-sm transition-colors"><?= number_format($stat_karakter['perlu_binaan']) ?></span>
            </div>
        </div>
    </div>
</div>

<div class="bg-white rounded-3xl p-6 dark:bg-slate-800 dark:border-slate-700 shadow-sm border border-gray-100 mb-6 transition-colors">
    <div class="flex items-center justify-between mb-6 pb-4 border-b border-gray-100 dark:border-slate-700 transition-colors">
        <div>
            <h3 class="text-lg dark:text-white font-bold text-gray-800 transition-colors"><?= lang('Admin/DashboardStatistik.sys_notifications') ?></h3>
            <p class="text-xs font-bold text-gray-400 dark:text-slate-400 uppercase tracking-widest mt-1 transition-colors"><?= lang('Admin/DashboardStatistik.sys_notif_desc') ?></p>
        </div>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
        <?php
        // TRIK CERDAS: Mengambil angka dinamis dari string bawaan Controller
        $angka_grades = preg_replace('/[^0-9]/', '', $notif_grades_msg) ?: '0';
        $angka_valid  = preg_replace('/[^0-9]/', '', $notif_valid_msg) ?: '0';
        ?>

        <div class="flex items-start gap-4 p-5 <?= $notif_grades_err ? 'bg-red-50 border border-red-100' : 'bg-emerald-50 border border-emerald-100' ?> dark:bg-slate-700 dark:border-slate-600 rounded-2xl hover:-translate-y-1 transition-transform">
            <div class="w-12 h-12 rounded-xl <?= $notif_grades_err ? 'bg-red-100 text-red-500' : 'bg-emerald-100 text-emerald-500' ?> dark:bg-slate-600 flex items-center justify-center flex-shrink-0 shadow-inner transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>
            <div>
                <h4 class="text-sm dark:text-white font-black <?= $notif_grades_err ? 'text-red-900' : 'text-emerald-900' ?> mb-1 transition-colors"><?= lang('Admin/DashboardStatistik.pending_grades') ?></h4>
                <p class="text-xs <?= $notif_grades_err ? 'text-red-700 dark:text-red-400' : 'text-emerald-700 dark:text-emerald-400' ?> font-medium leading-snug transition-colors">
                    <?= lang('Admin/DashboardStatistik.pending_grades_desc', [$angka_grades]) ?>
                </p>
            </div>
        </div>

        <div class="flex items-start gap-4 p-5 <?= $notif_valid_err ? 'bg-amber-50 border border-amber-100' : 'bg-emerald-50 border border-emerald-100' ?> dark:bg-slate-700 dark:border-slate-600 rounded-2xl hover:-translate-y-1 transition-transform">
            <div class="w-12 h-12 rounded-xl <?= $notif_valid_err ? 'bg-amber-100 text-amber-500' : 'bg-emerald-100 text-emerald-500' ?> dark:bg-slate-600 flex items-center justify-center flex-shrink-0 shadow-inner transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
            </div>
            <div>
                <h4 class="text-sm font-black dark:text-white <?= $notif_valid_err ? 'text-amber-900' : 'text-emerald-900' ?> mb-1 transition-colors"><?= lang('Admin/DashboardStatistik.report_validation') ?></h4>
                <p class="text-xs <?= $notif_valid_err ? 'text-amber-700 dark:text-amber-400' : 'text-emerald-700 dark:text-emerald-400' ?> font-medium leading-snug transition-colors">
                    <?= lang('Admin/DashboardStatistik.report_val_desc', [$angka_valid]) ?>
                </p>
            </div>
        </div>

        <div class="flex items-start gap-4 p-5 bg-blue-50 border border-blue-100 dark:bg-slate-700 dark:border-slate-600 rounded-2xl hover:-translate-y-1 transition-transform">
            <div class="w-12 h-12 rounded-xl bg-blue-100 dark:bg-slate-600 text-blue-500 flex items-center justify-center flex-shrink-0 shadow-inner transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div>
                <h4 class="text-sm dark:text-white font-black text-blue-900 mb-1 transition-colors"><?= lang('Admin/DashboardStatistik.backup_system') ?></h4>
                <p class="text-xs text-blue-700 dark:text-blue-400 font-medium leading-snug transition-colors">
                    <?= lang('Admin/DashboardStatistik.backup_desc') ?>
                </p>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    // Melempar variabel PHP ke JS agar Tahun Ajaran dinamis
    window.DYNAMIC_YEAR = "<?= esc($academic_year) ?>";
</script>
<script src="<?= base_url('assets/js/Admin/dashboard-statistik.js') ?>?v=<?= time() ?>"></script>
<?= $this->endSection() ?>