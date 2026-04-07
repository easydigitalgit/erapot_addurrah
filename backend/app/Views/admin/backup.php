<?= $this->extend('layout/main') ?>

<?= $this->section('title') ?>
  <?= lang('Admin/Backup.page_title') ?> - Rapor Digital SMPIT Ad Durrah
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
    :root { --warna-scroll: <?= $color['warna_primary'] ?>; }
</style>
  <link rel="stylesheet" href="<?= base_url('assets/css/Admin/backup.css') ?>">
<?= $this->endSection() ?>

<?= $this->section('content') ?>
    <div class="mb-6">
      <div class="flex items-center gap-2 text-sm text-gray-500 dark:text-slate-400 mb-3 transition-colors">
        <span><?= lang('Admin/Backup.breadcrumb') ?></span>
       <svg class="w-4 h-4" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
       </svg><span class="text-[<?= $color['warna_primary'] ?>] font-medium">Backup</span>
      </div>
      <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 mb-6">
       <div>
        <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white mb-2 flex items-center gap-3 transition-colors">
         <svg class="w-8 h-8 text-[<?= $color['warna_primary'] ?>]" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4" />
         </svg> <?= lang('Admin/Backup.title_main') ?></h1>
        <p class="text-sm md:text-base text-gray-600 dark:text-slate-400 transition-colors"><?= lang('Admin/Backup.title_desc') ?></p>
       </div>
       <div class="flex flex-wrap items-center gap-2">
           <button onclick="showBackupModal()" class="px-5 py-2.5 bg-[<?= $color['warna_primary'] ?>]/90 hover:bg-[<?= $color['warna_primary'] ?>] text-white font-bold rounded-xl flex items-center gap-2 transition-transform transform hover:-translate-y-0.5 shadow-lg outline-none" style="box-shadow: 0 10px 15px -3px <?= $color['warna_primary'] ?>40;">
         <svg class="w-5 h-5" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
         </svg><span><?= lang('Admin/Backup.btn_backup_now') ?></span> </button>
       </div>
      </div>
      
      <div class="bg-gradient-to-r from-[<?= $color['warna_secondary'] ?>] to-white dark:from-slate-800 dark:to-slate-800/80 border border-[<?= $color['warna_primary'] ?>]/50 p-5 rounded-2xl mb-8 shadow-sm transition-colors">
       <div class="flex items-start gap-4">
        <div class="w-12 h-12 rounded-xl bg-[<?= $color['warna_primary'] ?>]/10 dark:bg-[<?= $color['warna_primary'] ?>]/20 border border-[<?= $color['warna_primary'] ?>]/30 flex items-center justify-center flex-shrink-0 shadow-sm transition-colors">
         <svg class="w-6 h-6 text-[<?= $color['warna_primary'] ?>]" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
         </svg>
        </div>
        <div class="flex-1 min-w-0">
         <h4 class="font-bold text-[<?= $color['warna_primary'] ?>] text-lg mb-2 flex items-center gap-2"><?= lang('Admin/Backup.prot_title') ?> <span class="inline-flex items-center gap-1 bg-[<?= $color['warna_primary'] ?>]/10 text-[<?= $color['warna_primary'] ?>] border border-[<?= $color['warna_primary'] ?>]/20 px-2 py-0.5 rounded-full text-[10px] uppercase font-black tracking-widest shadow-sm">
           <svg class="w-3 h-3" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
           </svg> <?= lang('Admin/Backup.prot_badge') ?> </span></h4>
         <ul class="text-sm font-medium text-gray-700 dark:text-slate-300 space-y-1.5 transition-colors">
          <li class="flex items-center gap-2">
           <svg class="w-4 h-4 text-[<?= $color['warna_primary'] ?>] flex-shrink-0" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
           </svg> <?= lang('Admin/Backup.prot_1') ?></li>
          <li class="flex items-center gap-2">
           <svg class="w-4 h-4 text-[<?= $color['warna_primary'] ?>] flex-shrink-0" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
           </svg> <?= lang('Admin/Backup.prot_2') ?></li>
          <li class="flex items-center gap-2">
           <svg class="w-4 h-4 text-[<?= $color['warna_primary'] ?>] flex-shrink-0" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
           </svg> <?= lang('Admin/Backup.prot_3') ?></li>
         </ul>
        </div>
       </div>
      </div>
      
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        
       <div class="stat-card bg-white dark:bg-slate-800 border border-gray-100 dark:border-slate-700 rounded-3xl p-5 shadow-sm transition-colors hover:shadow-md hover:border-blue-500 group">
        <div class="flex items-start justify-between mb-4">
         <div class="w-12 h-12 rounded-xl bg-blue-100 dark:bg-blue-900/30 border border-blue-200 dark:border-blue-800/50 flex items-center justify-center transition-colors group-hover:scale-105 transform">
          <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 19a2 2 0 01-2-2V7a2 2 0 012-2h4l2 2h4a2 2 0 012 2v1M5 19h14a2 2 0 002-2v-5a2 2 0 00-2-2H9a2 2 0 00-2 2v5a2 2 0 01-2 2z" /></svg>
         </div>
        </div>
        <h3 class="text-xs font-black text-gray-500 dark:text-slate-400 uppercase tracking-widest mb-1 transition-colors"><?= lang('Admin/Backup.stat_files') ?></h3>
        <p class="text-3xl font-black text-gray-900 dark:text-white mb-1 transition-colors"><?= $total_files ?> File</p>
        <p class="text-xs font-medium text-gray-500 dark:text-slate-400 mt-1"><?= lang('Admin/Backup.stat_files_desc') ?></p>
       </div>
      
       <div class="stat-card bg-white dark:bg-slate-800 border border-gray-100 dark:border-slate-700 rounded-3xl p-5 shadow-sm transition-colors hover:shadow-md hover:border-purple-500 group">
        <div class="flex items-start justify-between mb-4">
         <div class="w-12 h-12 rounded-xl bg-purple-100 dark:bg-purple-900/30 border border-purple-200 dark:border-purple-800/50 flex items-center justify-center transition-colors group-hover:scale-105 transform">
          <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4" /></svg>
         </div>
        </div>
        <h3 class="text-xs font-black text-gray-500 dark:text-slate-400 uppercase tracking-widest mb-1 transition-colors"><?= lang('Admin/Backup.stat_size') ?></h3>
        <p class="text-3xl font-black text-gray-900 dark:text-white mb-1 transition-colors"><?= $total_size ?></p>
        <p class="text-xs font-medium text-gray-500 dark:text-slate-400 mt-1"><?= lang('Admin/Backup.stat_size_desc') ?></p>
       </div>
       
       <div class="stat-card bg-white dark:bg-slate-800 border border-gray-100 dark:border-slate-700 rounded-3xl p-5 shadow-sm transition-colors hover:shadow-md hover:border-emerald-500 group">
        <div class="flex items-start justify-between mb-4">
         <div class="w-12 h-12 rounded-xl bg-emerald-100 dark:bg-emerald-900/30 border border-emerald-200 dark:border-emerald-800/50 flex items-center justify-center transition-colors group-hover:scale-105 transform">
          <svg class="w-6 h-6 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
          </svg>
         </div>
        </div>
        <h3 class="text-xs font-black text-gray-500 dark:text-slate-400 uppercase tracking-widest mb-1 transition-colors"><?= lang('Admin/Backup.stat_sys') ?></h3>
        <p class="text-3xl font-black text-emerald-600 dark:text-emerald-400 mb-1 flex items-center gap-2"><?= lang('Admin/Backup.stat_sys_val') ?> <span class="w-2.5 h-2.5 bg-emerald-500 rounded-full animate-pulse shadow-[0_0_8px_#10b981]"></span></p>
        <p class="text-xs font-medium text-gray-500 dark:text-slate-400 mt-1"><?= lang('Admin/Backup.stat_sys_desc') ?></p>
       </div>
       
       <div class="stat-card bg-white dark:bg-slate-800 border border-gray-100 dark:border-slate-700 rounded-3xl p-5 shadow-sm transition-colors hover:shadow-md hover:border-amber-500 group">
        <div class="flex items-start justify-between mb-4">
         <div class="w-12 h-12 rounded-xl bg-amber-100 dark:bg-amber-900/30 border border-amber-200 dark:border-amber-800/50 flex items-center justify-center transition-colors group-hover:scale-105 transform">
          <svg class="w-6 h-6 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
         </div>
        </div>
        <h3 class="text-xs font-black text-gray-500 dark:text-slate-400 uppercase tracking-widest mb-1 transition-colors"><?= lang('Admin/Backup.stat_storage') ?></h3>
        <p class="text-3xl font-black text-gray-900 dark:text-white mb-2 transition-colors"><?= lang('Admin/Backup.stat_stor_val') ?></p>
        <div class="w-full bg-gray-100 dark:bg-slate-700 rounded-full h-1.5 overflow-hidden transition-colors">
         <div class="bg-amber-500 h-1.5 rounded-full" style="width: 15%"></div>
        </div>
        <p class="text-[10px] font-bold text-gray-500 dark:text-slate-400 mt-2 uppercase tracking-wider"><?= lang('Admin/Backup.stat_stor_desc') ?></p>
       </div>
      </div>
      
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
          <div class="bg-white dark:bg-slate-800 rounded-3xl border border-gray-100 dark:border-slate-700 shadow-sm p-6 transition-colors">
        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-5 flex items-center gap-2">
         <svg class="w-6 h-6 text-[<?=  $color['warna_primary'] ?>]" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
         </svg> <?= lang('Admin/Backup.sel_title') ?></h3>
        
        <div class="space-y-3" id="backupCategoryOptions">
            <label class="flex items-start gap-4 p-4 rounded-xl border border-gray-100 dark:border-slate-700 bg-gray-50 dark:bg-slate-700/50 hover:border-[<?= $color['warna_primary'] ?>] dark:hover:border-[<?= $color['warna_primary'] ?>] cursor-pointer transition-colors group">
                <input type="checkbox" value="siswa_ortu" class="backup-cat-checkbox w-5 h-5 mt-0.5 rounded border-gray-300 text-[<?= $color['warna_primary'] ?>] focus:ring-[<?= $color['warna_primary'] ?>] cursor-pointer outline-none" checked>
                <div class="flex-1">
                    <p class="font-bold text-gray-900 dark:text-white text-sm group-hover:text-[<?= $color['warna_primary'] ?>] transition-colors"><?= lang('Admin/Backup.sel_cat1') ?></p>
                    <p class="text-[11px] font-medium text-gray-500 dark:text-slate-400 mt-1"><?= number_format($stats['siswa_ortu']['rows'] ?? 0) ?> <?= lang('Admin/Backup.sel_cat1_desc') ?> • ~<?= ($stats['siswa_ortu']['size_mb'] ?? 0) > 0 ? ($stats['siswa_ortu']['size_mb'] ?? 0) . ' MB' : ($stats['siswa_ortu']['size_kb'] ?? 0) . ' KB' ?></p>
                </div>
            </label> 
            
            <label class="flex items-start gap-4 p-4 rounded-xl border border-gray-100 dark:border-slate-700 bg-gray-50 dark:bg-slate-700/50 hover:border-[<?= $color['warna_primary'] ?>] dark:hover:border-[<?= $color['warna_primary'] ?>] cursor-pointer transition-colors group"> 
                <input type="checkbox" value="nilai_rapor" class="backup-cat-checkbox w-5 h-5 mt-0.5 rounded border-gray-300 text-[<?= $color['warna_primary'] ?>] focus:ring-[<?= $color['warna_primary'] ?>] cursor-pointer outline-none" checked>
                <div class="flex-1">
                    <p class="font-bold text-gray-900 dark:text-white text-sm group-hover:text-[<?= $color['warna_primary'] ?>] transition-colors"><?= lang('Admin/Backup.sel_cat2') ?></p>
                    <p class="text-[11px] font-medium text-gray-500 dark:text-slate-400 mt-1"><?= number_format($stats['nilai_rapor']['rows'] ?? 0) ?> <?= lang('Admin/Backup.sel_cat2_desc') ?> • ~<?= ($stats['nilai_rapor']['size_mb'] ?? 0) > 0 ? ($stats['nilai_rapor']['size_mb'] ?? 0) . ' MB' : ($stats['nilai_rapor']['size_kb'] ?? 0) . ' KB' ?></p>
                </div>
            </label> 
            
            <label class="flex items-start gap-4 p-4 rounded-xl border border-gray-100 dark:border-slate-700 bg-gray-50 dark:bg-slate-700/50 hover:border-[<?= $color['warna_primary'] ?>] dark:hover:border-[<?= $color['warna_primary'] ?>] cursor-pointer transition-colors group"> 
                <input type="checkbox" value="master" class="backup-cat-checkbox w-5 h-5 mt-0.5 rounded border-gray-300 text-[<?= $color['warna_primary'] ?>] focus:ring-[<?= $color['warna_primary'] ?>] cursor-pointer outline-none" checked>
                <div class="flex-1">
                    <p class="font-bold text-gray-900 dark:text-white text-sm group-hover:text-[<?= $color['warna_primary'] ?>] transition-colors"><?= lang('Admin/Backup.sel_cat3') ?></p>
                    <p class="text-[11px] font-medium text-gray-500 dark:text-slate-400 mt-1"><?= lang('Admin/Backup.sel_cat3_desc') ?> • ~<?= ($stats['master']['size_mb'] ?? 0) > 0 ? ($stats['master']['size_mb'] ?? 0) . ' MB' : ($stats['master']['size_kb'] ?? 0) . ' KB' ?></p>
                </div>
            </label> 
            
            <label class="flex items-start gap-4 p-4 rounded-xl border border-gray-100 dark:border-slate-700 bg-gray-50 dark:bg-slate-700/50 hover:border-[<?= $color['warna_primary'] ?>] dark:hover:border-[<?= $color['warna_primary'] ?>] cursor-pointer transition-colors group"> 
                <input type="checkbox" value="konfigurasi" class="backup-cat-checkbox w-5 h-5 mt-0.5 rounded border-gray-300 text-[<?= $color['warna_primary'] ?>] focus:ring-[<?= $color['warna_primary'] ?>] cursor-pointer outline-none" checked>
                <div class="flex-1">
                    <p class="font-bold text-gray-900 dark:text-white text-sm group-hover:text-[<?= $color['warna_primary'] ?>] transition-colors"><?= lang('Admin/Backup.sel_cat4') ?></p>
                    <p class="text-[11px] font-medium text-gray-500 dark:text-slate-400 mt-1"><?= lang('Admin/Backup.sel_cat4_desc') ?> • ~<?= ($stats['konfigurasi']['size_mb'] ?? 0) > 0 ? ($stats['konfigurasi']['size_mb'] ?? 0) . ' MB' : ($stats['konfigurasi']['size_kb'] ?? 0) . ' KB' ?></p>
                </div>
            </label> 
        </div>
        
        <div class="mt-6 pt-5 border-t border-gray-100 dark:border-slate-700 transition-colors">
         <h4 class="text-xs font-black text-gray-500 dark:text-slate-400 uppercase tracking-widest mb-4"><?= lang('Admin/Backup.mode_title') ?></h4>
         <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
          <label class="flex items-start gap-3 p-4 rounded-xl border-2 border-gray-200 dark:border-slate-600 cursor-pointer transition-colors hover:border-[<?= $color['warna_primary'] ?>] bg-white dark:bg-slate-800 relative"> 
              <input type="radio" name="backupMode" value="full" onchange="toggleCategories(true)" checked class="w-5 h-5 accent-[<?= $color['warna_primary'] ?>] mt-0.5">
              <div class="min-w-0">
               <p class="font-bold text-gray-900 dark:text-white text-sm"><?= lang('Admin/Backup.mode_full') ?></p>
               <p class="text-[10px] font-medium text-gray-500 dark:text-slate-400 mt-1"><?= lang('Admin/Backup.mode_full_desc') ?></p>
              </div>
          </label> 
          <label class="flex items-start gap-3 p-4 rounded-xl border-2 border-gray-200 dark:border-slate-600 cursor-pointer transition-colors hover:border-[<?= $color['warna_primary'] ?>] bg-white dark:bg-slate-800 relative"> 
              <input type="radio" name="backupMode" value="partial" onchange="toggleCategories(false)" class="w-5 h-5 accent-[<?= $color['warna_primary'] ?>] mt-0.5">
              <div class="min-w-0">
               <p class="font-bold text-gray-900 dark:text-white text-sm"><?= lang('Admin/Backup.mode_part') ?></p>
               <p class="text-[10px] font-medium text-gray-500 dark:text-slate-400 mt-1"><?= lang('Admin/Backup.mode_part_desc') ?></p>
              </div>
          </label>
         </div>
        </div>
       </div>
       
       <div class="bg-white dark:bg-slate-800 rounded-3xl border border-gray-100 dark:border-slate-700 shadow-sm p-6 transition-colors relative">
        <form id="formAutoBackup">
            <div class="flex items-center justify-between mb-5 border-b border-gray-100 dark:border-slate-700 pb-4 transition-colors">
             <h3 class="text-xl font-bold text-gray-900 dark:text-white flex items-center gap-2">
              <svg class="w-6 h-6 text-[<?=  $color['warna_primary'] ?>]" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg> <?= lang('Admin/Backup.auto_title') ?></h3>
              <span class="toggle-switch <?= ($settings['auto_backup'] ?? 1) ? 'active' : '' ?> relative inline-flex items-center cursor-pointer" onclick="toggleAutoBackup(this)">
                  <input type="hidden" name="auto_backup" id="val_auto_backup" value="<?= $settings['auto_backup'] ?? 1 ?>">
                  <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-[<?= $color['warna_primary'] ?>]/30 rounded-full peer dark:bg-slate-600 transition-all dark:border-gray-500 toggle-bg"></div>
              </span>
            </div>
            
            <div id="scheduleSettings" class="space-y-5" style="opacity: <?= ($settings['auto_backup'] ?? 1) ? '1' : '0.5' ?>; pointer-events: <?= ($settings['auto_backup'] ?? 1) ? 'auto' : 'none' ?>;">
             <div>
                 <label class="block text-sm font-bold text-gray-700 dark:text-slate-300 mb-2"><?= lang('Admin/Backup.auto_freq') ?></label> 
                 <select name="frequency" class="w-full px-4 py-3 border border-gray-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-gray-800 dark:text-white rounded-xl focus:ring-2 focus:ring-[<?= $color['warna_primary'] ?>] transition-colors appearance-none cursor-pointer outline-none shadow-sm"> 
                     <option value="daily" <?= ($settings['frequency'] ?? '') == 'daily' ? 'selected' : '' ?>><?= lang('Admin/Backup.auto_freq_daily') ?></option> 
                     <option value="weekly" <?= ($settings['frequency'] ?? '') == 'weekly' ? 'selected' : '' ?>><?= lang('Admin/Backup.auto_freq_weekly') ?></option> 
                     <option value="monthly" <?= ($settings['frequency'] ?? '') == 'monthly' ? 'selected' : '' ?>><?= lang('Admin/Backup.auto_freq_monthly') ?></option> 
                 </select>
             </div>
             <div>
                 <label class="block text-sm font-bold text-gray-700 dark:text-slate-300 mb-2"><?= lang('Admin/Backup.auto_time') ?></label> 
                 <input type="time" name="execution_time" value="<?= htmlspecialchars($settings['execution_time'] ?? '02:00') ?>" class="w-full px-4 py-3 border border-gray-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-gray-800 dark:text-white rounded-xl focus:ring-2 focus:ring-[<?= $color['warna_primary'] ?>] transition-colors outline-none shadow-sm color-scheme-dark">
              <p class="text-[11px] font-medium text-gray-500 dark:text-slate-400 mt-1.5"><?= lang('Admin/Backup.auto_time_desc') ?></p>
             </div>
             <div>
                 <label class="block text-sm font-bold text-gray-700 dark:text-slate-300 mb-2"><?= lang('Admin/Backup.auto_retention') ?></label> 
                 <select name="retention_days" class="w-full px-4 py-3 border border-gray-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-gray-800 dark:text-white rounded-xl focus:ring-2 focus:ring-[<?= $color['warna_primary'] ?>] transition-colors appearance-none cursor-pointer outline-none shadow-sm"> 
                     <option value="7" <?= ($settings['retention_days'] ?? 0) == 7 ? 'selected' : '' ?>><?= lang('Admin/Backup.auto_ret_7') ?></option> 
                     <option value="30" <?= ($settings['retention_days'] ?? 30) == 30 ? 'selected' : '' ?>><?= lang('Admin/Backup.auto_ret_30') ?></option> 
                     <option value="60" <?= ($settings['retention_days'] ?? 0) == 60 ? 'selected' : '' ?>><?= lang('Admin/Backup.auto_ret_60') ?></option> 
                 </select>
             </div>
             
             <div class="pt-5 border-t border-gray-100 dark:border-slate-700 transition-colors">
                 <label class="flex items-center gap-3 cursor-pointer group"> 
                     <input type="checkbox" name="notify_email" value="1" <?= ($settings['notify_email'] ?? 1) ? 'checked' : '' ?> class="w-5 h-5 rounded border-gray-300 text-[<?= $color['warna_primary'] ?>] focus:ring-[<?= $color['warna_primary'] ?>] cursor-pointer focus:ring-offset-0 outline-none"> 
                     <span class="text-sm font-bold text-gray-700 dark:text-slate-300 transition-colors"><?= lang('Admin/Backup.auto_notify') ?></span> 
                 </label>
             </div>
             
             <button type="submit" id="btnSaveSetting" class="w-full mt-2 py-3 bg-[<?= $color['warna_secondary'] ?>] text-[<?= $color['warna_primary'] ?>] border border-[<?= $color['warna_primary'] ?>]/50 font-bold rounded-xl hover:bg-[<?= $color['warna_primary'] ?>] hover:text-white transition-colors"><?= lang('Admin/Backup.btn_save_setting') ?></button>
            </div>
        </form>
       </div>
      </div>
      
      <div class="bg-white dark:bg-slate-800 rounded-3xl border border-gray-100 dark:border-slate-700 shadow-sm overflow-hidden mb-6 transition-colors">
       <div class="p-6 border-b border-gray-100 dark:border-slate-700 flex items-center justify-between transition-colors">
           <div>
               <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-1 flex items-center gap-2">
                <svg class="w-5 h-5 text-[<?= $color['warna_primary'] ?>]" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg> <?= lang('Admin/Backup.hist_title') ?></h3>
               <p class="text-sm font-medium text-gray-500 dark:text-slate-400 ml-7"><?= lang('Admin/Backup.hist_desc') ?></p>
           </div>
           <span class="px-3 py-1 bg-[<?= $color['warna_primary'] ?>]/10 text-[<?= $color['warna_primary'] ?>] font-bold text-xs rounded-lg border border-[<?= $color['warna_primary'] ?>]/20 shadow-sm">
               <?= count($backups) ?> <?= lang('Admin/Backup.hist_badge') ?>
           </span>
       </div>
       <div class="overflow-x-auto custom-scrollbar">
        <table class="w-full text-left border-collapse min-w-max">
         <thead class="bg-gray-50 dark:bg-slate-900/50 border-b border-gray-100 dark:border-slate-700 transition-colors">
          <tr class="text-[11px] text-gray-500 dark:text-slate-400 uppercase tracking-widest font-black">
           <th class="px-6 py-4"><?= lang('Admin/Backup.th_date') ?></th>
           <th class="px-6 py-4"><?= lang('Admin/Backup.th_type') ?></th>
           <th class="px-6 py-4"><?= lang('Admin/Backup.th_name') ?></th>
           <th class="px-6 py-4 text-center"><?= lang('Admin/Backup.th_size') ?></th>
           <th class="px-6 py-4 text-center"><?= lang('Admin/Backup.th_action') ?></th>
          </tr>
         </thead>
         <tbody class="divide-y divide-gray-100 dark:divide-slate-700/50 transition-colors">
           <?php if(empty($backups)): ?>
             <tr>
               <td colspan="5" class="text-center py-16 text-gray-500 dark:text-slate-500 font-medium">
                   <div class="flex flex-col items-center gap-3">
                       <svg class="w-12 h-12 text-gray-300 dark:text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                       <span><?= lang('Admin/Backup.empty_hist') ?></span>
                   </div>
               </td>
             </tr>
           <?php else: ?>
             <?php foreach($backups as $file): ?>
             <?php $isFull = strpos($file['filename'], 'Full') !== false; ?>
             <tr class="hover:bg-gray-50/50 dark:hover:bg-slate-700/30 transition-colors group">
              <td class="px-6 py-4 font-bold text-gray-900 dark:text-white transition-colors">
                <?= date('d M Y', strtotime($file['date'])) ?><br>
                <span class="text-[11px] font-medium text-gray-500 dark:text-slate-400 tracking-wider"><?= date('H:i', strtotime($file['date'])) ?> WIB</span>
              </td>
              <td class="px-6 py-4">
                  <?php if($isFull): ?>
                    <span class="inline-flex px-2.5 py-1 text-[10px] font-black uppercase tracking-wider bg-blue-100 text-blue-700 border border-blue-200 rounded-full shadow-sm"><?= lang('Admin/Backup.badge_full') ?></span>
                  <?php else: ?>
                    <span class="inline-flex px-2.5 py-1 text-[10px] font-black uppercase tracking-wider bg-amber-100 text-amber-700 border border-amber-200 rounded-full shadow-sm"><?= lang('Admin/Backup.badge_partial') ?></span>
                  <?php endif; ?>
              </td>
              <td class="px-6 py-4 text-xs font-mono font-bold text-gray-600 dark:text-slate-300 group-hover:text-[<?= $color['warna_primary'] ?>] transition-colors"><?= esc($file['filename']) ?></td>
              <td class="px-6 py-4 text-center font-bold text-gray-800 dark:text-slate-200"><?= $file['size'] ?></td>
              <td class="px-6 py-4 text-center">
               <div class="flex items-center justify-center gap-2 opacity-1 lg:opacity-0 group-hover:opacity-100 transition-opacity">
                <a href="<?= base_url('admin/backup/download/' . $file['filename']) ?>" class="p-2 text-blue-600 dark:text-blue-400 hover:bg-blue-50 dark:hover:bg-blue-900/30 rounded-lg transition-colors shadow-sm outline-none" title="Download SQL">
                 <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" /></svg>
                </a>
                
                <button type="button" onclick="showRestoreModal('<?= $file['filename'] ?>')" class="p-2 text-amber-600 dark:text-amber-400 hover:bg-amber-50 dark:hover:bg-amber-900/30 rounded-lg transition-colors shadow-sm outline-none" title="Restore Database">
                 <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" /></svg>
                </button>

                <button type="button" onclick="deleteBackup('<?= $file['filename'] ?>')" class="p-2 text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/30 rounded-lg transition-colors shadow-sm outline-none" title="Hapus File Permanen">
                 <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                </button>
               </div>
              </td>
             </tr>
             <?php endforeach; ?>
           <?php endif; ?>
         </tbody>
        </table>
       </div>
      </div>
      
      <div class="bg-white dark:bg-slate-800 rounded-3xl border border-gray-100 dark:border-slate-700 shadow-sm p-6 mb-6 transition-colors">
       <div class="flex items-start gap-4 mb-5 border-b border-gray-100 dark:border-slate-700 pb-4 transition-colors">
        <div class="w-12 h-12 rounded-xl bg-amber-100 dark:bg-amber-900/30 border border-amber-200 dark:border-amber-800/50 flex items-center justify-center flex-shrink-0 shadow-sm">
         <svg class="w-6 h-6 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
         </svg>
        </div>
        <div class="flex-1 min-w-0">
         <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-1"><?= lang('Admin/Backup.ext_title') ?></h3>
         <p class="text-sm font-medium text-gray-600 dark:text-slate-400"><?= lang('Admin/Backup.ext_desc') ?></p>
        </div>
       </div>
       <div class="dropzone w-full border-2 border-dashed border-gray-300 dark:border-slate-600 bg-gray-50/50 dark:bg-slate-700/50 hover:bg-gray-100 dark:hover:bg-slate-700 hover:border-[<?= $color['warna_primary'] ?>] rounded-2xl p-8 text-center cursor-pointer transition-all shadow-sm group mb-6" onclick="document.getElementById('fileInput').click()">
           <input type="file" id="fileInput" accept=".enc,.backup,.sql" class="hidden">
        <svg class="w-12 h-12 text-gray-400 dark:text-slate-500 mx-auto mb-3 group-hover:text-[<?= $color['warna_primary'] ?>] transition-colors group-hover:scale-110 transform duration-300" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
        </svg>
        <p class="text-sm font-bold text-gray-800 dark:text-white mb-1 group-hover:text-[<?= $color['warna_primary'] ?>] transition-colors"><?= lang('Admin/Backup.ext_drag') ?></p>
        <p class="text-xs font-medium text-gray-500 dark:text-slate-400"><?= lang('Admin/Backup.ext_or') ?></p>
        <p class="text-[10px] font-black uppercase tracking-wider text-gray-400 dark:text-slate-500 mt-3 border border-gray-200 dark:border-slate-600 inline-block px-3 py-1 rounded-full"><?= lang('Admin/Backup.ext_format') ?></p>
       </div>
       <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800/50 p-5 rounded-2xl shadow-sm transition-colors">
        <div class="flex items-start gap-4">
         <div class="w-10 h-10 bg-white dark:bg-slate-800 border border-red-200 dark:border-red-800/50 rounded-full flex items-center justify-center flex-shrink-0 shadow-sm mt-0.5">
             <svg class="w-6 h-6 text-red-600 dark:text-red-500" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
             </svg>
         </div>
         <div class="flex-1">
          <h4 class="font-black text-red-900 dark:text-red-400 mb-2 uppercase tracking-wider text-[11px]"><?= lang('Admin/Backup.warn_title') ?></h4>
          <ul class="text-xs font-medium text-red-800 dark:text-red-300 space-y-1.5 leading-relaxed">
           <li class="flex items-start gap-2"><span class="text-red-600 font-bold">•</span> <span><?= lang('Admin/Backup.warn_1') ?></span></li>
           <li class="flex items-start gap-2"><span class="text-red-600 font-bold">•</span> <span><?= lang('Admin/Backup.warn_2') ?></span></li>
           <li class="flex items-start gap-2"><span class="text-red-600 font-bold">•</span> <span><?= lang('Admin/Backup.warn_3') ?></span></li>
          </ul>
         </div>
        </div>
       </div>
      </div>
<?= $this->endSection() ?>

<?= $this->section('modals') ?>

  <div id="backupModal" class="fixed inset-0 z-[99999] hidden flex items-center justify-center p-4 overflow-y-auto overflow-x-hidden">
    <div class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" onclick="closeBackupModal()"></div>
    
    <div class="relative w-full max-w-md bg-white dark:bg-slate-800 rounded-3xl shadow-2xl p-6 md:p-8 transform transition-colors border border-transparent dark:border-slate-700 scale-100 mx-auto">
      <div class="text-center mb-6">
        <div class="w-20 h-20 rounded-full bg-emerald-50 dark:bg-emerald-900/30 border-4 border-emerald-100 dark:border-emerald-800/50 flex items-center justify-center mx-auto mb-5 shadow-sm transition-colors">
          <svg class="w-10 h-10 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" /></svg>
        </div>
        <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-2 transition-colors"><?= lang('Admin/Backup.mod_backup_title') ?></h3>
        <p class="text-sm font-medium text-gray-600 dark:text-slate-400 mb-6 transition-colors"><?= lang('Admin/Backup.mod_backup_desc') ?></p>
        <label class="flex items-start gap-3 bg-white dark:bg-slate-800 border-2 border-gray-200 dark:border-slate-600 p-4 rounded-xl cursor-pointer hover:border-[<?= $color['warna_primary'] ?>] dark:hover:border-[<?= $color['warna_primary'] ?>] transition-colors mb-2 group shadow-sm">
          <input type="checkbox" id="confirmBackupModal" class="mt-0.5 w-5 h-5 text-[<?= $color['warna_primary'] ?>] rounded border-gray-300 focus:ring-[<?= $color['warna_primary'] ?>] cursor-pointer outline-none">
          <span class="text-sm font-bold text-gray-700 dark:text-slate-300 text-left group-hover:text-gray-900 dark:group-hover:text-white transition-colors"><?= lang('Admin/Backup.mod_backup_chk') ?></span>
        </label>
      </div>
      <div class="flex gap-3">
        <button type="button" onclick="closeBackupModal()" class="flex-1 py-3.5 px-4 bg-gray-100 dark:bg-slate-700 hover:bg-gray-200 dark:hover:bg-slate-600 text-gray-800 dark:text-white rounded-xl font-bold transition-colors outline-none shadow-sm">
          <?= lang('Admin/Backup.btn_cancel') ?>
        </button>
        <button type="button" onclick="startBackup()" class="flex-1 py-3.5 px-4 bg-[<?= $color['warna_primary'] ?>] hover:bg-[<?= $color['warna_primary'] ?>]/90 text-white rounded-xl font-bold transition-transform transform hover:-translate-y-0.5 shadow-lg outline-none flex justify-center items-center gap-2" style="box-shadow: 0 10px 15px -3px <?= $color['warna_primary'] ?>40;">
            <svg class="w-5 h-5 animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" /></svg>
            <?= lang('Admin/Backup.btn_exec') ?>
        </button>
      </div>
    </div>
  </div>

  <div id="restoreModal" class="fixed inset-0 z-[99999] hidden flex items-center justify-center p-4 overflow-y-auto overflow-x-hidden">
    <div class="absolute inset-0 bg-gray-900/70 backdrop-blur-sm transition-opacity" onclick="closeRestoreModal()"></div>
    
    <div class="relative w-full max-w-md bg-white dark:bg-slate-800 rounded-3xl shadow-2xl p-6 md:p-8 transform transition-colors border border-transparent dark:border-slate-700 scale-100 mx-auto">
      <div class="text-center mb-6">
        <div class="w-20 h-20 rounded-full bg-red-50 dark:bg-red-900/30 border-4 border-red-100 dark:border-red-800/50 flex items-center justify-center mx-auto mb-5 shadow-sm transition-colors">
          <svg class="w-10 h-10 text-red-500 dark:text-red-400" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
        </div>
        <h3 class="text-2xl font-black text-gray-900 dark:text-white mb-2 transition-colors"><?= lang('Admin/Backup.mod_rest_title') ?></h3>
        <p class="text-sm font-medium text-gray-600 dark:text-slate-400 mb-4 transition-colors"><?= lang('Admin/Backup.mod_rest_desc') ?></p>
        
        <div class="bg-gray-100 dark:bg-slate-900/50 border border-gray-200 dark:border-slate-700 rounded-xl p-3 mb-6 shadow-inner transition-colors">
          <span class="font-mono text-xs font-bold text-blue-600 dark:text-blue-400 break-all" id="restoreFileName">Memuat nama file...</span>
        </div>
        
        <div class="bg-red-50 dark:bg-red-900/20 border-2 border-red-200 dark:border-red-800/50 p-4 rounded-xl mb-6 text-left shadow-sm transition-colors">
          <h4 class="font-black text-red-900 dark:text-red-400 mb-2 text-xs uppercase tracking-wider"><?= lang('Admin/Backup.mod_rest_warn') ?></h4>
          <p class="text-xs font-medium text-red-800 dark:text-red-300 leading-relaxed"><?= lang('Admin/Backup.mod_rest_warn_txt') ?></p>
        </div>

        <div class="space-y-3 mb-6">
            <label class="flex items-start gap-3 bg-white dark:bg-slate-800 border-2 border-gray-200 dark:border-slate-600 p-3.5 rounded-xl cursor-pointer hover:border-red-500 dark:hover:border-red-500 transition-colors group">
              <input type="checkbox" id="confirmRestore1" class="w-5 h-5 text-red-600 rounded border-gray-300 focus:ring-red-500 mt-0.5 cursor-pointer outline-none">
              <span class="text-sm font-bold text-gray-700 dark:text-slate-300 text-left group-hover:text-gray-900 dark:group-hover:text-white transition-colors"><?= lang('Admin/Backup.mod_rest_chk1') ?></span>
            </label>

            <label class="flex items-start gap-3 bg-white dark:bg-slate-800 border-2 border-gray-200 dark:border-slate-600 p-3.5 rounded-xl cursor-pointer hover:border-red-500 dark:hover:border-red-500 transition-colors group">
              <input type="checkbox" id="confirmRestore2" class="w-5 h-5 text-red-600 rounded border-gray-300 focus:ring-red-500 mt-0.5 cursor-pointer outline-none">
              <span class="text-sm font-bold text-gray-700 dark:text-slate-300 text-left group-hover:text-gray-900 dark:group-hover:text-white transition-colors"><?= lang('Admin/Backup.mod_rest_chk2') ?></span>
            </label>
        </div>
      </div>

      <div class="flex gap-3">
        <button type="button" onclick="closeRestoreModal()" class="flex-1 py-3.5 px-4 bg-gray-100 dark:bg-slate-700 hover:bg-gray-200 dark:hover:bg-slate-600 text-gray-800 dark:text-white rounded-xl font-bold transition-colors outline-none shadow-sm">
          <?= lang('Admin/Backup.btn_cancel') ?>
        </button>
        <button type="button" onclick="startRestore()" class="flex-1 py-3.5 px-4 bg-red-600 hover:bg-red-700 text-white rounded-xl font-bold transition-transform transform hover:-translate-y-0.5 shadow-lg shadow-red-600/30 outline-none flex justify-center items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
            <?= lang('Admin/Backup.btn_exec_rest') ?>
        </button>
      </div>
    </div>
  </div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
  <script>
      const BASE_URL = "<?= base_url() ?>";
      const CSRF_TOKEN = "<?= csrf_hash() ?>";
      const CSRF_NAME = "<?= csrf_token() ?>";

      // Transfer data bahasa ke Javascript
      const LANG_BACKUP = {
          saving: "<?= lang('Admin/Backup.js_saving') ?>",
          conf_on: "<?= lang('Admin/Backup.js_conf_on') ?>",
          conf_off: "<?= lang('Admin/Backup.js_conf_off') ?>",
          err_conn: "<?= lang('Admin/Backup.js_err_conn') ?>",
          warn_check: "<?= lang('Admin/Backup.js_warn_check') ?>",
          warn_cat: "<?= lang('Admin/Backup.js_warn_cat') ?>",
          prog: "<?= lang('Admin/Backup.js_backup_prog') ?>",
          desc: "<?= lang('Admin/Backup.js_backup_desc') ?>",
          del_conf: "<?= lang('Admin/Backup.js_del_conf') ?>",
          deleting: "<?= lang('Admin/Backup.js_deleting') ?>",
          err_no_file: "<?= lang('Admin/Backup.js_err_no_file') ?>",
          warn_all_chk: "<?= lang('Admin/Backup.js_warn_all_chk') ?>",
          rest_prog: "<?= lang('Admin/Backup.js_rest_prog') ?>",
          rest_desc: "<?= lang('Admin/Backup.js_rest_desc') ?>"
      };
  </script>
  
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="<?= base_url('assets/js/Admin/backup.js') ?>?v=<?= time() ?>"></script>
<?= $this->endSection() ?>