<?= $this->extend('layout/main') ?>

<?= $this->section('title') ?>
  <?= lang('WaliKelas/Pembinaan.page_title') ?> - Rapor Digital SMPIT Ad Durrah
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
  <link rel="stylesheet" href="<?= base_url('assets/css/WaliKelas/perlu-pembinaan.css') ?>">
  <style>
    /* Injeksi Warna Dinamis dari Database */
    :root {
      --warna-primary: <?= $color['warna_primary'] ?? '#10b981' ?>;
      --warna-secondary: <?= $color['warna_secondary'] ?? '#ecfdf5' ?>;
      --warna-scroll: <?= $color['warna_primary'] ?>; 
    }
    
    .text-tema { color: var(--warna-primary) !important; }
    .bg-tema { background-color: var(--warna-primary) !important; }
    .bg-tema-light { background-color: var(--warna-secondary) !important; }
    .bg-tema-15 { background-color: color-mix(in srgb, var(--warna-primary) 15%, white) !important; }
    .border-tema { border-color: var(--warna-primary) !important; }
    .hover-bg-tema:hover { background-color: color-mix(in srgb, var(--warna-primary) 85%, black) !important; }
    .hover-text-tema:hover { color: color-mix(in srgb, var(--warna-primary) 80%, black) !important; }

    /* Dark Mode Overrides */
    html.dark .text-tema { color: color-mix(in srgb, var(--warna-primary) 80%, white) !important; }
    html.dark .bg-tema-light { background-color: rgba(255, 255, 255, 0.05) !important; }
    html.dark .bg-white { background-color: #1e293b !important; border-color: #334155 !important; }
    html.dark .text-gray-800 { color: #f1f5f9 !important; }
    html.dark .text-gray-500 { color: #94a3b8 !important; }
    html.dark .bg-gray-50 { background-color: #0f172a !important; }
    html.dark .border-gray-100 { border-color: #334155 !important; }
    html.dark .border-gray-200 { border-color: #475569 !important; }
    
    /* Warna Chip Statistik Dark Mode */
    html.dark .bg-red-50 { background-color: rgba(239, 68, 68, 0.1) !important; }
    html.dark .bg-amber-50 { background-color: rgba(245, 158, 11, 0.1) !important; }
    html.dark .bg-blue-50 { background-color: rgba(59, 130, 246, 0.1) !important; }
    html.dark .bg-purple-50 { background-color: rgba(168, 85, 247, 0.1) !important; }
    
    /* Scrollbar */
    html.dark .custom-scrollbar::-webkit-scrollbar-thumb { background-color: var(--warna-primary); }

    .focus-tema:focus {
        border-color: var(--warna-primary) !important;
        box-shadow: 0 0 0 4px color-mix(in srgb, var(--warna-primary) 20%, transparent) !important;
        outline: none;
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

<?php if(!$rombel): ?>
    <div class="bg-amber-50 border border-amber-200 rounded-xl p-6 text-center mb-6">
        <h3 class="text-lg font-bold text-amber-800 mb-2"><?= lang('WaliKelas/Pembinaan.err_no_access_title') ?></h3>
        <p class="text-sm text-amber-700"><?= lang('WaliKelas/Pembinaan.err_no_access_desc') ?></p>
    </div>
<?php else: ?>

<?php 
  $canCreate = has_permission('wali_dashboard', 'create'); 
?>

<div class="mb-6">
    <div class="flex items-start justify-between mb-4">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-red-100 to-red-50 flex items-center justify-center">
                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
            </div>
            <div>
                <h2 class="text-xl lg:text-2xl font-bold text-gray-800"><?= lang('WaliKelas/Pembinaan.page_title') ?></h2>
                <p class="text-sm text-gray-500 mt-0.5"><?= lang('WaliKelas/Pembinaan.class_text') ?> <?= esc($rombel['nama_rombel']) ?> • <?= lang('WaliKelas/Pembinaan.page_subtitle') ?></p>
            </div>
        </div>
        
        <?php if($canCreate): ?>
        <div class="hidden md:flex gap-2">
            <button onclick="openListModal()" class="bg-white text-tema font-semibold py-2 px-4 rounded-xl border border-gray-200 hover:border-tema transition-all flex items-center gap-2 text-sm shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg> 
                <?= lang('WaliKelas/Pembinaan.btn_full_list') ?>
            </button>
            <button onclick="openNoteModal()" class="bg-tema hover-bg-tema text-white font-semibold py-2 px-4 rounded-xl transition-all shadow-md shadow-[var(--warna-primary)]/20 flex items-center gap-2 text-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg> 
                <?= lang('WaliKelas/Pembinaan.btn_add_note') ?>
            </button>
        </div>
        <?php endif; ?>
    </div>

    <div class="grid grid-cols-2 sm:grid-cols-4 gap-2 lg:gap-3 mb-6">
        <div class="chip flex items-center gap-2 px-3 py-2 bg-white border border-gray-200 rounded-lg shadow-sm hover:shadow-md transition-all">
            <div class="w-8 h-8 rounded-lg bg-red-50 flex items-center justify-center flex-shrink-0">
                <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
            </div>
            <div class="flex-1">
                <p class="text-xs text-gray-500"><?= lang('WaliKelas/Pembinaan.stat_academic') ?></p>
                <p class="text-lg font-bold text-red-600"><?= $statistik['akademik'] ?? 0 ?></p>
            </div>
        </div>
        <div class="chip flex items-center gap-2 px-3 py-2 bg-white border border-gray-200 rounded-lg shadow-sm hover:shadow-md transition-all">
            <div class="w-8 h-8 rounded-lg bg-amber-50 flex items-center justify-center flex-shrink-0">
                <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            </div>
            <div class="flex-1">
                <p class="text-xs text-gray-500"><?= lang('WaliKelas/Pembinaan.stat_character') ?></p>
                <p class="text-lg font-bold text-amber-600"><?= $statistik['karakter'] ?? 0 ?></p>
            </div>
        </div>
        <div class="chip flex items-center gap-2 px-3 py-2 bg-white border border-gray-200 rounded-lg shadow-sm hover:shadow-md transition-all">
            <div class="w-8 h-8 rounded-lg bg-blue-50 flex items-center justify-center flex-shrink-0">
                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
            </div>
            <div class="flex-1">
                <p class="text-xs text-gray-500"><?= lang('WaliKelas/Pembinaan.stat_tahfidz') ?></p>
                <p class="text-lg font-bold text-blue-600"><?= $statistik['tahfidz'] ?? 0 ?></p>
            </div>
        </div>
        <div class="chip flex items-center gap-2 px-3 py-2 bg-white border border-gray-200 rounded-lg shadow-sm hover:shadow-md transition-all">
            <div class="w-8 h-8 rounded-lg bg-purple-50 flex items-center justify-center flex-shrink-0">
                <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2-2v12a2 2 0 002 2z" /></svg>
            </div>
            <div class="flex-1">
                <p class="text-xs text-gray-500"><?= lang('WaliKelas/Pembinaan.stat_attendance') ?></p>
                <p class="text-lg font-bold text-purple-600"><?= $statistik['absensi'] ?? 0 ?></p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 lg:gap-6 mb-6">
        
        <div class="lg:col-span-2 space-y-3 lg:space-y-4" id="studentsList">
            <?php if(empty($siswa_pembinaan)): ?>
                <div class="bg-white dark:bg-slate-800 rounded-2xl p-10 border border-gray-100 dark:border-slate-700 shadow-sm text-center h-full flex flex-col justify-center items-center min-h-[300px]">
                    <div class="w-20 h-20 bg-tema-light dark:bg-slate-700/50 text-tema rounded-full flex items-center justify-center mx-auto mb-5">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 dark:text-slate-100"><?= lang('WaliKelas/Pembinaan.empty_title') ?></h3>
                    <p class="text-sm text-gray-500 dark:text-slate-400 mt-2 max-w-md mx-auto leading-relaxed"><?= lang('WaliKelas/Pembinaan.empty_desc_1') ?> <span class="font-bold text-gray-700 dark:text-slate-300"><?= esc($rombel['nama_rombel']) ?></span> <?= lang('WaliKelas/Pembinaan.empty_desc_2') ?></p>
                </div>
            <?php else: ?>
                <?php foreach($siswa_pembinaan as $siswa): ?>
                    <div class="group relative bg-white dark:bg-slate-800 rounded-2xl p-4 sm:p-5 border border-gray-100 dark:border-slate-700 shadow-sm transition-all duration-300 overflow-hidden <?= $canCreate ? 'cursor-pointer hover:shadow-xl hover:border-' . $siswa['tema'] . '-300 dark:hover:border-' . $siswa['tema'] . '-600' : '' ?>" <?= $canCreate ? 'onclick="openNoteModal(\'' . ($siswa['siswa_id'] ?? '') . '\')"' : '' ?>>
                        
                        <div class="absolute top-0 left-0 w-1.5 h-full bg-gradient-to-b from-<?= $siswa['tema'] ?>-400 to-<?= $siswa['tema'] ?>-600"></div>

                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pl-2 sm:pl-3">
                            
                            <div class="flex items-start sm:items-center gap-4 flex-1">
                                <div class="relative flex-shrink-0">
                                    <div class="w-12 h-12 rounded-full bg-<?= $siswa['tema'] ?>-50 dark:bg-<?= $siswa['tema'] ?>-900/30 border border-<?= $siswa['tema'] ?>-100 dark:border-<?= $siswa['tema'] ?>-800/50 flex items-center justify-center text-<?= $siswa['tema'] ?>-600 dark:text-<?= $siswa['tema'] ?>-400 font-extrabold text-sm shadow-sm group-hover:scale-105 transition-transform duration-300">
                                        <?= esc($siswa['inisial']) ?>
                                    </div>
                                    <?php if($siswa['status'] == 'urgent'): ?>
                                        <div class="absolute -top-1 -right-1 w-4 h-4 rounded-full bg-red-500 border-2 border-white dark:border-slate-800 flex items-center justify-center">
                                            <div class="w-2 h-2 rounded-full bg-white animate-ping"></div>
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <div>
                                    <div class="flex items-center gap-2 mb-0.5">
                                        <h3 class="font-bold text-gray-800 dark:text-slate-100 text-sm md:text-base group-hover:text-<?= $siswa['tema'] ?>-600 dark:group-hover:text-<?= $siswa['tema'] ?>-400 transition-colors"><?= esc($siswa['nama']) ?></h3>
                                        <?php if($siswa['status'] == 'urgent'): ?>
                                            <span class="px-2 py-0.5 bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400 text-[9px] font-bold uppercase tracking-wider rounded-md border border-red-200 dark:border-red-800/50"><?= lang('WaliKelas/Pembinaan.badge_urgent') ?></span>
                                        <?php endif; ?>
                                    </div>
                                    <div class="flex items-center gap-2 mb-1.5">
                                        <span class="text-[11px] text-gray-500 dark:text-slate-400 font-medium">NISN: <span class="font-mono"><?= esc($siswa['nisn']) ?></span></span>
                                    </div>
                                    
                                    <p class="text-[12px] text-gray-600 dark:text-slate-300 italic line-clamp-1 flex items-center gap-1.5 bg-gray-50 dark:bg-slate-900/50 px-2.5 py-1 rounded-lg border border-gray-100 dark:border-slate-700/50 w-fit max-w-full">
                                        <svg class="w-3.5 h-3.5 text-<?= $siswa['tema'] ?>-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path></svg>
                                        "<?= esc($siswa['pesan'] ?? lang('WaliKelas/Pembinaan.no_detail')) ?>"
                                    </p>
                                </div>
                            </div>

                            <div class="flex sm:flex-col items-center sm:items-end justify-between sm:justify-center gap-3 border-t sm:border-t-0 border-gray-100 dark:border-slate-700 pt-3 sm:pt-0 w-full sm:w-auto mt-2 sm:mt-0">
                                <div class="flex flex-wrap sm:justify-end gap-1.5">
                                    <?php foreach($siswa['kategori'] as $kat): ?>
                                        <span class="px-2.5 py-1 bg-<?= $siswa['tema'] ?>-50 dark:bg-<?= $siswa['tema'] ?>-900/20 text-<?= $siswa['tema'] ?>-600 dark:text-<?= $siswa['tema'] ?>-400 text-[10px] font-extrabold uppercase tracking-wider rounded-lg border border-<?= $siswa['tema'] ?>-200 dark:border-<?= $siswa['tema'] ?>-800/50 shadow-sm">
                                            <?= esc($kat) ?>
                                        </span>
                                    <?php endforeach; ?>
                                </div>
                                
                                <?php if($canCreate): ?>
                                <div class="flex items-center gap-1.5 text-[11px] font-bold text-gray-400 dark:text-slate-500 group-hover:text-<?= $siswa['tema'] ?>-500 dark:group-hover:text-<?= $siswa['tema'] ?>-400 transition-colors bg-white dark:bg-slate-800 px-3 py-1.5 rounded-lg border border-gray-100 dark:border-slate-700 group-hover:border-<?= $siswa['tema'] ?>-200 dark:group-hover:border-<?= $siswa['tema'] ?>-800">
                                    <span><?= lang('WaliKelas/Pembinaan.btn_add_note') ?></span>
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
                                </div>
                                <?php endif; ?>
                            </div>

                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 flex flex-col dark:bg-slate-800 dark:border-slate-700">
            <div class="p-4 lg:p-5 border-b border-gray-100 dark:border-slate-700">
                <h2 class="font-semibold text-gray-800 dark:text-white"><?= lang('WaliKelas/Pembinaan.card_char_discipline') ?></h2>
            </div>
            <div class="p-4 lg:p-5 space-y-5">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-lg bg-tema-15 flex items-center justify-center">
                            <svg class="w-4 h-4 text-tema" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-800 dark:text-slate-200"><?= lang('WaliKelas/Pembinaan.stat_attendance') ?></p>
                            <p class="text-xs text-gray-500 dark:text-slate-400"><?= lang('WaliKelas/Pembinaan.lbl_this_month') ?></p>
                        </div>
                    </div>
                    <span class="text-lg font-bold text-tema"><?= lang('WaliKelas/Pembinaan.lbl_monitored') ?></span>
                </div>
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-lg bg-red-50 dark:bg-red-900/30 flex items-center justify-center">
                            <svg class="w-4 h-4 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-800 dark:text-slate-200"><?= lang('WaliKelas/Pembinaan.lbl_infractions') ?></p>
                            <p class="text-xs text-gray-500 dark:text-slate-400"><?= lang('WaliKelas/Pembinaan.lbl_this_semester') ?></p>
                        </div>
                    </div>
                    <span class="text-lg font-bold text-red-600 dark:text-red-400"><?= $statistik['karakter'] ?? 0 ?></span>
                </div>
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-lg bg-amber-50 dark:bg-amber-900/30 flex items-center justify-center">
                            <svg class="w-4 h-4 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" /></svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-800 dark:text-slate-200"><?= lang('WaliKelas/Pembinaan.lbl_achievements') ?></p>
                            <p class="text-xs text-gray-500 dark:text-slate-400"><?= lang('WaliKelas/Pembinaan.lbl_this_month') ?></p>
                        </div>
                    </div>
                    <span class="text-lg font-bold text-amber-600 dark:text-amber-400">0</span>
                </div>
                <div class="pt-3 border-t border-gray-100 dark:border-slate-700">
                    <div class="flex items-center justify-between mb-2">
                        <p class="text-sm font-medium text-gray-800 dark:text-slate-200"><?= lang('WaliKelas/Pembinaan.lbl_tahfidz_prog') ?></p>
                        <span class="text-xs font-medium text-tema"><?= lang('WaliKelas/Pembinaan.lbl_in_progress') ?></span>
                    </div>
                    <div class="w-full h-2 bg-gray-100 dark:bg-slate-700 rounded-full overflow-hidden">
                        <div class="h-full bg-tema rounded-full progress-bar" style="width: 50%"></div>
                    </div>
                    <p class="text-[10px] text-gray-400 mt-2"><?= lang('WaliKelas/Pembinaan.lbl_wait_update') ?></p>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-gray-100 dark:border-slate-700 mb-6 transition-all duration-300">
        <div class="p-4 lg:p-5 border-b border-gray-100 dark:border-slate-700 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-tema-light dark:bg-slate-700 flex items-center justify-center text-tema shadow-inner">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <h2 class="font-bold text-gray-800 dark:text-white"><?= lang('WaliKelas/Pembinaan.history_title') ?></h2>
            </div>
            <a href="#" class="text-xs text-tema hover-text-tema font-bold bg-tema-light dark:bg-slate-700 px-3 py-1.5 rounded-lg transition-all border border-transparent hover:border-tema/30"><?= lang('WaliKelas/Pembinaan.btn_see_all') ?> →</a>
        </div>
        
        <div class="p-4 lg:p-5">
            <?php if(empty($siswa_pembinaan)): ?>
                <div class="flex flex-col items-center justify-center py-8 text-center">
                    <div class="w-14 h-14 bg-gray-50 dark:bg-slate-700/50 rounded-full flex items-center justify-center mb-3 text-gray-400 dark:text-slate-500 border border-gray-100 dark:border-slate-600">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    </div>
                    <p class="text-sm text-gray-500 dark:text-slate-400 font-medium"><?= lang('WaliKelas/Pembinaan.no_history') ?></p>
                </div>
            <?php else: ?>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <?php foreach($siswa_pembinaan as $riwayat): ?>
                        <div class="flex flex-col p-4 bg-gray-50 dark:bg-slate-900/50 rounded-2xl border border-gray-100 dark:border-slate-700/70 hover:shadow-md hover:-translate-y-0.5 transition-all duration-300 group">
                            
                            <div class="flex items-start justify-between mb-3">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-<?= $riwayat['tema'] ?>-100 dark:bg-<?= $riwayat['tema'] ?>-900/40 text-<?= $riwayat['tema'] ?>-600 dark:text-<?= $riwayat['tema'] ?>-400 flex items-center justify-center font-bold text-sm border border-<?= $riwayat['tema'] ?>-200 dark:border-<?= $riwayat['tema'] ?>-800 shadow-sm">
                                        <?= esc($riwayat['inisial']) ?>
                                    </div>
                                    <div>
                                        <p class="font-bold text-sm text-gray-800 dark:text-slate-200 line-clamp-1 group-hover:text-tema transition-colors"><?= esc($riwayat['nama']) ?></p>
                                        <p class="text-[10px] text-gray-500 dark:text-slate-400 font-mono mt-0.5">NISN: <?= esc($riwayat['nisn']) ?></p>
                                    </div>
                                </div>
                                <span class="px-2 py-1 bg-white dark:bg-slate-800 text-<?= $riwayat['tema'] ?>-600 dark:text-<?= $riwayat['tema'] ?>-400 text-[9px] font-extrabold uppercase tracking-wide rounded-md border border-gray-200 dark:border-slate-600 shadow-sm">
                                    <?= esc($riwayat['kategori'][0]) ?>
                                </span>
                            </div>
                            
                            <div class="relative bg-white dark:bg-slate-800 rounded-xl p-3.5 border border-gray-100 dark:border-slate-700 flex-1 shadow-sm">
                                <svg class="absolute top-2 right-2 w-5 h-5 text-gray-100 dark:text-slate-700" fill="currentColor" viewBox="0 0 24 24"><path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z"/></svg>
                                <p class="text-[13px] text-gray-600 dark:text-slate-300 italic pr-6 leading-relaxed line-clamp-3">
                                    "<?= esc($riwayat['pesan']) ?>"
                                </p>
                            </div>
                            
                            <div class="mt-3 pt-3 border-t border-gray-200 dark:border-slate-700/80 flex justify-between items-center">
                                <span class="text-[10px] text-gray-400 dark:text-slate-500 font-medium flex items-center gap-1.5">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    <?= lang('WaliKelas/Pembinaan.lbl_last_record') ?>
                                </span>
                                <?php if($canCreate): ?>
                                <button onclick="openNoteModal('<?= $riwayat['siswa_id'] ?>')" class="text-[10px] font-bold text-tema hover-text-tema bg-white dark:bg-slate-800 px-3 py-1.5 rounded-lg border border-gray-200 dark:border-slate-600 shadow-sm hover:border-tema transition-all flex items-center gap-1">
                                    <?= lang('WaliKelas/Pembinaan.btn_follow_up') ?>
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7"/></svg>
                                </button>
                                <?php endif; ?>
                            </div>
                            
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<div id="noteModal" class="fixed inset-0 z-[60] hidden flex items-center justify-center p-4 sm:p-0">
    <div id="noteBackdrop" class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm opacity-0 transition-opacity duration-300" onclick="closeNoteModal()"></div>
    
    <div id="noteContent" class="relative bg-white dark:bg-slate-800 rounded-3xl shadow-2xl flex flex-col overflow-hidden w-full max-w-xl z-10 scale-95 opacity-0 transition-all duration-300">
        
        <div class="relative bg-tema p-6 text-white overflow-hidden flex-shrink-0">
            <div class="absolute top-0 right-0 -mt-4 -mr-4 w-32 h-32 bg-white/10 rounded-full blur-2xl"></div>
            <div class="absolute bottom-0 left-0 -mb-4 -ml-4 w-24 h-24 bg-black/10 rounded-full blur-xl"></div>
            
            <div class="relative z-10 flex items-start justify-between">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-white/20 rounded-2xl backdrop-blur-md flex items-center justify-center border border-white/30 shadow-inner">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    </div>
                    <div>
                        <h3 class="font-extrabold text-xl tracking-wide"><?= lang('WaliKelas/Pembinaan.modal_note_title') ?></h3>
                        <p class="text-white/80 text-sm mt-0.5"><?= lang('WaliKelas/Pembinaan.modal_note_subtitle') ?></p>
                    </div>
                </div>
                <button onclick="closeNoteModal()" type="button" class="p-2 bg-white/10 hover:bg-white/20 rounded-xl transition-all border border-white/10">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
        </div>
        
        <form id="formPembinaan" action="<?= base_url('wali/perlu-pembinaan/save') ?>" method="POST" onsubmit="savePembinaan(event)" class="flex flex-col max-h-[calc(100vh-8rem)]">
            <input type="hidden" name="rombel_id" value="<?= esc($rombel['id'] ?? '') ?>">
            
            <div class="p-6 space-y-6 overflow-y-auto custom-scrollbar flex-1 bg-slate-50/50 dark:bg-slate-900/50">
                <div class="space-y-2">
                    <label class="flex items-center gap-2 text-sm font-bold text-slate-700 dark:text-slate-300">
                        <svg class="w-4 h-4 text-tema" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        <?= lang('WaliKelas/Pembinaan.form_select_student') ?>
                    </label>
                    <select name="siswa_id" id="selectSiswa" required class="w-full px-4 py-3 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl focus-tema transition-all shadow-sm text-slate-700 dark:text-slate-200 font-medium cursor-pointer">
                        <option value="">-- <?= lang('WaliKelas/Pembinaan.form_student_ph') ?> --</option>
                        <?php foreach($siswa_kelas as $s): ?>
                            <option value="<?= $s['id'] ?>"><?= esc($s['nama_lengkap']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="space-y-2">
                    <label class="flex items-center gap-2 text-sm font-bold text-slate-700 dark:text-slate-300">
                        <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                        <?= lang('WaliKelas/Pembinaan.form_category') ?>
                    </label>
                    <select name="kategori" required class="w-full px-4 py-3 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl focus-tema transition-all shadow-sm text-slate-700 dark:text-slate-200 font-medium cursor-pointer">
                        <option value="Akademik"><?= lang('WaliKelas/Pembinaan.opt_cat_academic') ?></option>
                        <option value="Karakter"><?= lang('WaliKelas/Pembinaan.opt_cat_character') ?></option>
                        <option value="Tahfidz"><?= lang('WaliKelas/Pembinaan.opt_cat_tahfidz') ?></option>
                        <option value="Absensi"><?= lang('WaliKelas/Pembinaan.opt_cat_absence') ?></option>
                    </select>
                </div>

                <div class="space-y-2">
                    <label class="flex items-center gap-2 text-sm font-bold text-slate-700 dark:text-slate-300">
                        <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/></svg>
                        <?= lang('WaliKelas/Pembinaan.form_description') ?>
                    </label>
                    <textarea name="catatan" required rows="3" class="w-full px-4 py-3 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl focus-tema transition-all shadow-sm text-slate-700 dark:text-slate-200 resize-none placeholder:text-slate-400 dark:placeholder:text-slate-500" placeholder="<?= lang('WaliKelas/Pembinaan.form_desc_ph') ?>"></textarea>
                </div>

                <div class="space-y-2">
                    <label class="flex items-center gap-2 text-sm font-bold text-slate-700 dark:text-slate-300">
                        <svg class="w-4 h-4 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <?= lang('WaliKelas/Pembinaan.form_follow_up') ?>
                    </label>
                    <textarea name="tindak_lanjut" rows="2" class="w-full px-4 py-3 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl focus-tema transition-all shadow-sm text-slate-700 dark:text-slate-200 resize-none placeholder:text-slate-400 dark:placeholder:text-slate-500" placeholder="<?= lang('WaliKelas/Pembinaan.form_follow_ph') ?>"></textarea>
                </div>
            </div>
            
            <div class="p-5 border-t border-slate-100 dark:border-slate-700 bg-white dark:bg-slate-800 flex justify-end gap-3 flex-shrink-0 rounded-b-3xl">
                <button type="button" onclick="closeNoteModal()" class="px-6 py-2.5 text-slate-600 dark:text-slate-300 bg-slate-100 dark:bg-slate-700 rounded-xl hover:bg-slate-200 dark:hover:bg-slate-600 font-bold transition-all"><?= lang('WaliKelas/Pembinaan.btn_cancel') ?></button>
                <button type="submit" class="px-6 py-2.5 bg-tema text-white rounded-xl hover-bg-tema font-bold flex items-center gap-2 shadow-lg shadow-[var(--warna-primary)]/30 transition-all transform hover:-translate-y-0.5">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    <?= lang('WaliKelas/Pembinaan.btn_save') ?>
                </button>
            </div>
        </form>
    </div>
</div>

<div id="allStudentsModal" class="fixed inset-0 z-[60] hidden flex items-center justify-center p-4">
    <div id="listBackdrop" class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm opacity-0 transition-opacity duration-300" onclick="closeListModal()"></div>
    
    <div id="listContent" class="relative bg-white dark:bg-slate-800 rounded-3xl shadow-2xl flex flex-col overflow-hidden w-full max-w-2xl z-10 scale-95 opacity-0 transition-all duration-300 max-h-[85vh]">
        
        <div class="p-5 border-b border-slate-100 dark:border-slate-700 flex items-center justify-between bg-slate-50/80 dark:bg-slate-800/80 backdrop-blur-sm">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-tema-light dark:bg-slate-700 text-tema flex items-center justify-center shadow-inner">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                </div>
                <div>
                    <h3 class="font-bold text-lg text-slate-800 dark:text-slate-100 tracking-wide"><?= lang('WaliKelas/Pembinaan.modal_list_title') ?> <?= esc($rombel['nama_rombel']) ?></h3>
                    <p class="text-[11px] text-slate-500 dark:text-slate-400 font-medium uppercase tracking-wider"><?= lang('WaliKelas/Pembinaan.modal_list_subtitle') ?></p>
                </div>
            </div>
            <button onclick="closeListModal()" class="p-2 text-slate-400 hover:bg-red-50 hover:text-red-500 dark:hover:bg-slate-700 dark:hover:text-red-400 rounded-xl transition-all">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        
        <div class="p-5 flex-1 overflow-y-auto bg-white dark:bg-slate-800 custom-scrollbar">
            
            <div class="relative mb-6 sticky top-0 z-10 bg-white dark:bg-slate-800 pb-2 pt-1">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none pb-1 mt-1">
                    <svg class="w-5 h-5 text-slate-400 dark:text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
                <input type="text" id="searchSiswa" onkeyup="filterSiswa()" placeholder="<?= lang('WaliKelas/Pembinaan.search_student_ph') ?>" class="w-full pl-11 pr-4 py-3.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-2xl text-sm focus-tema outline-none transition-all shadow-sm font-medium text-slate-700 dark:text-slate-200 placeholder:text-slate-400">
            </div>
            
            <div class="space-y-3" id="daftarSiswaContainer">
                <?php foreach($siswa_kelas as $index => $sk): ?>
                    <div class="siswa-item p-3 border border-slate-100 dark:border-slate-700 rounded-2xl hover:bg-slate-50 dark:hover:bg-slate-700/50 flex items-center justify-between transition-all duration-300 group cursor-pointer" onclick="pilihSiswaDariDaftar('<?= $sk['id'] ?>')">
                        <div class="flex items-center gap-4">
                            <span class="w-12 h-12 rounded-full bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300 flex items-center justify-center font-bold text-sm border border-slate-200 dark:border-slate-600 group-hover:bg-tema group-hover:text-white group-hover:border-transparent transition-colors shadow-sm">
                                <?= substr($sk['nama_lengkap'], 0, 2) ?>
                            </span>
                            <div>
                                <p class="font-bold text-slate-800 dark:text-slate-200 text-sm nama-siswa group-hover:text-tema dark:group-hover:text-tema transition-colors"><?= esc($sk['nama_lengkap']) ?></p>
                                <div class="flex items-center gap-2 mt-0.5">
                                    <span class="text-[10px] text-slate-400 dark:text-slate-500 font-mono bg-white dark:bg-slate-800 px-1.5 rounded border border-slate-100 dark:border-slate-700 nis-siswa">NIS: <?= esc($sk['nis']) ?></span>
                                    <span class="text-[10px] text-slate-400 dark:text-slate-500 font-mono bg-white dark:bg-slate-800 px-1.5 rounded border border-slate-100 dark:border-slate-700">NISN: <?= esc($sk['nisn']) ?></span>
                                </div>
                            </div>
                        </div>
                        
                        <button class="px-5 py-2.5 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-600 text-slate-600 dark:text-slate-300 text-xs font-bold rounded-xl group-hover:border-tema group-hover:bg-tema group-hover:text-white transition-all duration-300 flex items-center gap-2 transform group-hover:scale-105 shadow-sm group-hover:shadow-[var(--warna-primary)]/30">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                            <?= lang('WaliKelas/Pembinaan.btn_select_student') ?>
                        </button>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <div id="noResult" class="hidden flex-col items-center justify-center py-10 text-center">
                <div class="w-16 h-16 bg-slate-100 dark:bg-slate-700 rounded-full flex items-center justify-center mb-4 text-slate-400">
                   <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
                <p class="text-sm font-bold text-slate-600 dark:text-slate-300"><?= lang('WaliKelas/Pembinaan.no_student_found') ?></p>
                <p class="text-xs text-slate-400 mt-1"><?= lang('WaliKelas/Pembinaan.no_student_desc') ?></p>
            </div>

        </div>
    </div>
</div>

<?php endif; ?>
<?= $this->endSection() ?> 

<?= $this->section('scripts') ?>
    <script>
        window.sekolahConfig = {
            school_name: '<?= esc($nama_sekolah ?? 'SMPIT Ad Durrah') ?>',
            teacher_name: '<?= esc($guru['nama_lengkap'] ?? 'Guru/Wali Kelas') ?>',
            class_name: '<?= esc($rombel['nama_rombel'] ?? 'Belum Ada Kelas') ?>',
            primary_color: '<?= esc($color['warna_primary'] ?? '#10b981') ?>',
            secondary_color: '<?= esc($color['warna_secondary'] ?? '#ecfdf5') ?>'
        };

        // Kamus JS
        window.LANG = {
            js_processing: "<?= lang('WaliKelas/Pembinaan.js_processing') ?>",
            js_succ_saved: "<?= lang('WaliKelas/Pembinaan.js_succ_saved') ?>",
            js_err_failed: "<?= lang('WaliKelas/Pembinaan.js_err_failed') ?>",
            js_err_network: "<?= lang('WaliKelas/Pembinaan.js_err_network') ?>"
        };
    </script>

    <script src="<?= base_url('assets/js/WaliKelas/perlu-pembinaan.js') ?>?v=<?= time() ?>"></script>
<?= $this->endSection() ?>