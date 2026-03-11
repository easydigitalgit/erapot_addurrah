<?= $this->extend('layout/main') ?>

<?= $this->section('title') ?>
  <?= lang('Admin/TargetTahfidz.page_title_browser') ?>
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
  <link rel="stylesheet" href="<?= base_url('assets/css/Admin/target-tahfidz.css') ?>">
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="mb-6">
  <div class="flex items-center gap-2 text-sm text-gray-500 dark:text-slate-400 mb-3 transition-colors">
    <span><?= lang('Admin/TargetTahfidz.academic_config') ?></span>
    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewbox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
    </svg><span class="text-[<?= $color['warna_primary'] ?>] dark:text-[<?= $color['warna_primary'] ?>] font-medium"><?= lang('Admin/TargetTahfidz.page_title') ?></span>
  </div>
  <div class="text-center mb-6 py-4 bg-gradient-to-r from-[<?= $color['warna_secondary'] ?>]/40 to-[<?= $color['warna_secondary'] ?>]/20 dark:from-slate-800 dark:to-slate-800/80 rounded-2xl border border-[<?= $color['warna_primary'] ?>]/40 dark:border-[<?= $color['warna_primary'] ?>]/20 shadow-sm transition-colors">
    <p class="text-3xl arabic-text text-[<?= $color['warna_primary'] ?>] mb-2 font-bold drop-shadow-sm">بِسْمِ اللَّهِ الرَّحْمَٰنِ الرَّحِيمِ</p>
    <p class="text-xs text-gray-600 dark:text-slate-400 italic"><?= lang('Admin/TargetTahfidz.bismillah_translation') ?></p>
  </div>
  <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
    <div>
      <h1 id="pageTitle" class="text-3xl md:text-3xl font-bold text-gray-800 dark:text-white mb-2 transition-colors"><?= lang('Admin/TargetTahfidz.page_title') ?></h1>
      <p id="pageSubtitle" class="text-sm md:text-base text-gray-600 dark:text-slate-400 transition-colors"><?= lang('Admin/TargetTahfidz.page_subtitle') ?></p>
    </div>
    <div class="flex flex-wrap items-center gap-2">
        <button onclick="showAddTargetModal()" class="px-4 py-2.5 bg-[<?= $color['warna_primary'] ?>]/90 hover:bg-[<?= $color['warna_primary'] ?>] text-white font-semibold rounded-xl transition-all shadow-lg shadow-[<?= $color['warna_primary'] ?>]/20 flex items-center gap-2 transform hover:-translate-y-0.5 outline-none">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewbox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
        </svg><span><?= lang('Admin/TargetTahfidz.btn_add_target') ?></span> </button> 
        
        <button onclick="showImportModal()" class="px-4 py-2.5 bg-white dark:bg-slate-800 border border-gray-300 dark:border-slate-600 text-gray-700 dark:text-slate-300 font-semibold rounded-xl hover:bg-gray-50 dark:hover:bg-slate-700 transition-colors flex items-center gap-2 cursor-pointer shadow-sm outline-none">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewbox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10" />
        </svg><span class="hidden md:inline"><?= lang('Admin/TargetTahfidz.btn_import_target') ?></span> </button> 
        
        <button onclick="showTemplateModal()" class="px-4 py-2.5 bg-white dark:bg-slate-800 border border-gray-300 dark:border-slate-600 text-gray-700 dark:text-slate-300 font-semibold rounded-xl hover:bg-gray-50 dark:hover:bg-slate-700 transition-colors flex items-center gap-2 cursor-pointer shadow-sm outline-none">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewbox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
        </svg><span class="hidden md:inline"><?= lang('Admin/TargetTahfidz.btn_template') ?></span> </button> 
        
        <button onclick="showRiwayatModal()" class="px-4 py-2.5 bg-white dark:bg-slate-800 border border-gray-300 dark:border-slate-600 text-gray-700 dark:text-slate-300 font-semibold rounded-xl hover:bg-gray-50 dark:hover:bg-slate-700 transition-colors flex items-center gap-2 cursor-pointer shadow-sm outline-none">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewbox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg><span class="hidden md:inline"><?= lang('Admin/TargetTahfidz.btn_history') ?></span> </button>
    </div>
  </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6 mb-6">
  <div class="stat-card bg-white dark:bg-slate-800 rounded-2xl p-5 shadow-sm border border-gray-100 dark:border-slate-700 hover:shadow-md hover:border-[<?= $color['warna_primary'] ?>] transition-all group">
    <div class="flex items-center justify-between mb-4">
      <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-[<?= $color['warna_primary'] ?>]/80 to-[<?= $color['warna_primary'] ?>] flex items-center justify-center shadow-lg group-hover:scale-105 transition-transform">
        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewbox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
        </svg>
      </div><span class="text-[10px] font-black text-[<?= $color['warna_primary'] ?>] uppercase tracking-wider bg-[<?= $color['warna_primary'] ?>]/10 px-2 py-1 rounded-lg"><?= lang('Admin/TargetTahfidz.academic_year') ?></span>
    </div>
    <p class="text-3xl font-black text-gray-900 dark:text-white mb-1 transition-colors"><?= get_tahun_ajaran() ?></p>
    <p class="text-sm font-medium text-gray-600 dark:text-slate-400 transition-colors"><?= lang('Admin/TargetTahfidz.semester') ?> <?= get_semester() == 'Ganjil' ? lang('Admin/TargetTahfidz.odd') : lang('Admin/TargetTahfidz.even') ?></p>
  </div>
  <div class="stat-card bg-white dark:bg-slate-800 rounded-2xl p-5 shadow-sm border border-gray-100 dark:border-slate-700 hover:shadow-md hover:border-[<?= $color['warna_primary'] ?>] transition-all group">
    <div class="flex items-center justify-between mb-4">
      <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center shadow-lg group-hover:scale-105 transition-transform">
        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewbox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
        </svg>
      </div><span class="text-[10px] font-black text-blue-600 dark:text-blue-400 uppercase tracking-wider bg-blue-50 dark:bg-blue-900/30 px-2 py-1 rounded-lg"><?= lang('Admin/TargetTahfidz.total_target') ?></span>
    </div>
    <p class="text-3xl font-black text-gray-900 dark:text-white mb-1 transition-colors">4.5 Juz</p>
    <p class="text-sm font-medium text-gray-600 dark:text-slate-400 transition-colors"><?= lang('Admin/TargetTahfidz.all_levels') ?></p>
  </div>
  <div class="stat-card bg-white dark:bg-slate-800 rounded-2xl p-5 shadow-sm border border-gray-100 dark:border-slate-700 hover:shadow-md hover:border-[<?= $color['warna_primary'] ?>] transition-all group">
    <div class="flex items-center justify-between mb-4">
      <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-purple-500 to-purple-600 flex items-center justify-center shadow-lg group-hover:scale-105 transition-transform">
        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewbox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
        </svg>
      </div><span class="text-[10px] font-black text-purple-600 dark:text-purple-400 uppercase tracking-wider bg-purple-50 dark:bg-purple-900/30 px-2 py-1 rounded-lg"><?= lang('Admin/TargetTahfidz.active_level') ?></span>
    </div>
    <p class="text-3xl font-black text-gray-900 dark:text-white mb-1 transition-colors">3 <?= lang('Admin/TargetTahfidz.level_count') ?></p>
    <p class="text-sm font-medium text-gray-600 dark:text-slate-400 transition-colors">VII, VIII, IX</p>
  </div>
  <div class="stat-card bg-white dark:bg-slate-800 rounded-2xl p-5 shadow-sm border border-gray-100 dark:border-slate-700 hover:shadow-md hover:border-[<?= $color['warna_primary'] ?>] transition-all group">
    <div class="flex items-center justify-between mb-4">
      <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-amber-500 to-amber-600 flex items-center justify-center shadow-lg group-hover:scale-105 transition-transform">
        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewbox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
      </div><span class="text-[10px] font-black text-amber-600 dark:text-amber-400 uppercase tracking-wider bg-amber-50 dark:bg-amber-900/30 px-2 py-1 rounded-lg"><?= lang('Admin/TargetTahfidz.status') ?></span>
    </div>
    <p class="text-3xl font-black text-gray-900 dark:text-white mb-1 transition-colors">100%</p>
    <p class="text-sm font-medium text-gray-600 dark:text-slate-400 transition-colors"><?= lang('Admin/TargetTahfidz.configured_target') ?></p>
  </div>
</div>

<div class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-gray-100 dark:border-slate-700 p-6 mb-6 transition-colors">
  <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
    <div>
        <label class="block text-[11px] font-bold text-gray-600 dark:text-slate-400 uppercase tracking-wider mb-2"><?= lang('Admin/TargetTahfidz.academic_year') ?></label> 
        <select id="filter_tahun" onchange="filterTable()" class="w-full px-4 py-2.5 bg-white dark:bg-slate-700 border-2 border-gray-200 dark:border-slate-600 rounded-xl text-sm font-medium text-gray-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-[<?= $color['warna_primary'] ?>]/70 focus:border-transparent transition-colors appearance-none cursor-pointer outline-none shadow-sm">
            <option value=""><?= lang('Admin/TargetTahfidz.all_years') ?></option>
            <option value="2024/2025">2024/2025</option>
            <option value="2023/2024">2023/2024</option>
        </select>
    </div>
    <div>
        <label class="block text-[11px] font-bold text-gray-600 dark:text-slate-400 uppercase tracking-wider mb-2"><?= lang('Admin/TargetTahfidz.semester') ?></label> 
        <select id="filter_semester" onchange="filterTable()" class="w-full px-4 py-2.5 bg-white dark:bg-slate-700 border-2 border-gray-200 dark:border-slate-600 rounded-xl text-sm font-medium text-gray-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-[<?= $color['warna_primary'] ?>]/70 focus:border-transparent transition-colors appearance-none cursor-pointer outline-none shadow-sm">
            <option value=""><?= lang('Admin/TargetTahfidz.all_semesters') ?></option>
            <option value="Ganjil"><?= lang('Admin/TargetTahfidz.odd') ?></option>
            <option value="Genap"><?= lang('Admin/TargetTahfidz.even') ?></option>
        </select>
    </div>
    <div>
        <label class="block text-[11px] font-bold text-gray-600 dark:text-slate-400 uppercase tracking-wider mb-2"><?= lang('Admin/TargetTahfidz.th_level') ?></label> 
        <select id="filter_tingkat" onchange="filterTable()" class="w-full px-4 py-2.5 bg-white dark:bg-slate-700 border-2 border-gray-200 dark:border-slate-600 rounded-xl text-sm font-medium text-gray-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-[<?= $color['warna_primary'] ?>]/70 focus:border-transparent transition-colors appearance-none cursor-pointer outline-none shadow-sm">
            <option value=""><?= lang('Admin/TargetTahfidz.all_levels_filter') ?></option>
            <option value="VII">VII</option>
            <option value="VIII">VIII</option>
            <option value="IX">IX</option>
        </select>
    </div>
    <div>
        <label class="block text-[11px] font-bold text-gray-600 dark:text-slate-400 uppercase tracking-wider mb-2"><?= lang('Admin/TargetTahfidz.status') ?></label> 
        <select id="filter_status" onchange="filterTable()" class="w-full px-4 py-2.5 bg-white dark:bg-slate-700 border-2 border-gray-200 dark:border-slate-600 rounded-xl text-sm font-medium text-gray-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-[<?= $color['warna_primary'] ?>]/70 focus:border-transparent transition-colors appearance-none cursor-pointer outline-none shadow-sm">
            <option value=""><?= lang('Admin/TargetTahfidz.all_status') ?></option>
            <option value="Aktif"><?= lang('Admin/TargetTahfidz.active') ?></option>
            <option value="Nonaktif"><?= lang('Admin/TargetTahfidz.inactive') ?></option> 
        </select>
    </div>
  </div>

  <div class="flex flex-col sm:flex-row sm:items-center justify-between pt-5 border-t border-gray-100 dark:border-slate-700 transition-colors gap-4">
      <label class="flex items-center gap-3 cursor-pointer group"> 
          <input type="checkbox" id="check_aktif" onchange="toggleActiveCheck()" class="w-5 h-5 rounded border-gray-300 dark:border-slate-500 bg-white dark:bg-slate-800 text-[<?= $color['warna_primary'] ?>] focus:ring-[<?= $color['warna_primary'] ?>] cursor-pointer focus:ring-offset-0"> 
          <span class="text-sm font-bold text-gray-700 dark:text-slate-300 group-hover:text-gray-900 dark:group-hover:text-white transition-colors"><?= lang('Admin/TargetTahfidz.show_active_only') ?></span> 
      </label> 
      <button onclick="resetFilter()" class="text-sm text-[<?= $color['warna_primary'] ?>] dark:text-[<?= $color['warna_primary'] ?>] hover:opacity-80 font-bold flex items-center gap-1.5 cursor-pointer outline-none">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewbox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
        </svg> <?= lang('Admin/TargetTahfidz.btn_reset_filter') ?> 
      </button>
  </div>
</div>

<div class="bg-white dark:bg-slate-800 w-full min-w-0 rounded-3xl shadow-sm border border-gray-100 dark:border-slate-700 overflow-hidden mb-6 transition-colors">
  <div class="overflow-x-auto w-full custom-scrollbar">
    <table class="w-full text-left border-collapse min-w-max">
      <thead class="bg-gray-50 dark:bg-slate-900/50 border-b border-gray-100 dark:border-slate-700 transition-colors">
        <tr class="text-[11px] text-gray-500 dark:text-slate-400 uppercase tracking-widest font-black">
          <th class="px-6 py-4"><?= lang('Admin/TargetTahfidz.th_level') ?></th>
          <th class="px-6 py-4"><?= lang('Admin/TargetTahfidz.th_semester') ?></th>
          <th class="px-6 py-4"><?= lang('Admin/TargetTahfidz.th_juz_target') ?></th>
          <th class="px-6 py-4"><?= lang('Admin/TargetTahfidz.th_surah_target') ?></th>
          <th class="px-6 py-4"><?= lang('Admin/TargetTahfidz.th_min_memorization') ?></th>
          <th class="px-6 py-4"><?= lang('Admin/TargetTahfidz.th_status') ?></th>
          <th class="px-6 py-4 text-center"><?= lang('Admin/TargetTahfidz.th_action') ?></th>
        </tr>
      </thead>
      <tbody class="divide-y divide-gray-50 dark:divide-slate-700/50 transition-colors">
        <?php if (empty($targets)) : ?>
          <tr>
            <td colspan="7" class="text-center py-16 text-gray-400 dark:text-slate-500">
              <div class="flex flex-col items-center justify-center gap-3">
                <svg class="w-12 h-12 text-gray-300 dark:text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <span class="text-sm font-medium"><?= lang('Admin/TargetTahfidz.no_data') ?></span>
              </div>
            </td>
          </tr>
        <?php else : ?>
          <?php foreach ($targets as $t) : ?>
            <tr class="target-row hover:bg-gray-50/50 dark:hover:bg-slate-700/30 transition-colors group" 
                data-tingkat="<?= $t['tingkat'] ?>" 
                data-semester="<?= $t['semester'] ?>" 
                data-status="<?= $t['status'] ?>">
              <td class="px-6 py-4 font-black text-emerald-700 dark:text-emerald-400">
                  <?= $t['tingkat'] ?>
              </td>
              <td class="px-6 py-4 font-bold text-gray-800 dark:text-slate-200">
                  <?= $t['semester'] == 'Ganjil' ? lang('Admin/TargetTahfidz.odd') : lang('Admin/TargetTahfidz.even') ?>
              </td>
              <td class="px-6 py-4 font-black text-[<?= $color['warna_primary'] ?>]">
                  <?= $t['nama_juz'] ?>
              </td>
              <td class="px-6 py-4">
                <div class="text-sm font-bold text-gray-800 dark:text-slate-200">
                  <?= $t['surah_mulai'] ?> - <?= $t['surah_sampai'] ?>
                </div>
                <div class="text-[10px] font-bold uppercase tracking-wider text-gray-500 dark:text-slate-500 mt-1">
                   <?= lang('Admin/TargetTahfidz.target_semester_label') ?>
                </div>
              </td>
              <td class="px-6 py-4">
                <span class="inline-flex px-2.5 py-1 bg-emerald-50 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400 font-black text-sm rounded-lg border border-emerald-200 dark:border-emerald-800/50 shadow-sm"><?= $t['minimal_hafalan'] ?>%</span>
                <div class="text-[10px] font-bold uppercase tracking-wider text-gray-500 dark:text-slate-500 mt-1">
                  <?= lang('Admin/TargetTahfidz.min_memorization_label') ?>
                </div>
              </td>
              <td class="px-6 py-4">
                  <?php if ($t['status'] == 'Aktif') : ?>
                      <span class="inline-flex items-center gap-1.5 px-3 py-1 text-[10px] font-black uppercase tracking-widest text-emerald-700 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-900/30 rounded-full border border-emerald-200 dark:border-emerald-800/50 shadow-sm">
                          <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse shadow-[0_0_4px_#10b981]"></span>
                          <?= lang('Admin/TargetTahfidz.active') ?>
                      </span>
                  <?php else : ?>
                      <span class="inline-flex px-3 py-1 text-[10px] font-bold uppercase tracking-widest text-gray-600 dark:text-slate-400 bg-gray-100 dark:bg-slate-700 rounded-full border border-gray-200 dark:border-slate-600 shadow-sm">
                          <?= lang('Admin/TargetTahfidz.inactive') ?>
                      </span>
                  <?php endif; ?>
              </td>
              <td class="px-6 py-4 text-center">
                <div class="flex items-center justify-center gap-2 opacity-1 lg:opacity-0 lg:group-hover:opacity-100 transition-opacity">
                  <button onclick="showDetailDrawer('<?= $t['tingkat'] ?>', '<?= $t['semester'] == 'Ganjil' ? lang('Admin/TargetTahfidz.odd') : lang('Admin/TargetTahfidz.even') ?>', '<?= $t['nama_juz'] ?>')" 
              class="p-2 bg-[<?= $color['warna_secondary'] ?>]/70 dark:bg-slate-700 text-[<?= $color['warna_primary'] ?>] dark:text-[<?= $color['warna_primary'] ?>] rounded-lg hover:bg-[<?= $color['warna_secondary'] ?>] dark:hover:bg-slate-600 transition-colors border border-[<?= $color['warna_primary'] ?>]/30 dark:border-slate-600 flex items-center gap-1 text-[11px] font-bold uppercase tracking-wider cursor-pointer shadow-sm outline-none">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg> <span class="hidden xl:inline"><?= lang('Admin/TargetTahfidz.btn_detail') ?></span> 
                  </button>
                  
                  <button onclick='showEditModal(<?= json_encode($t) ?>)' 
              class="p-2 bg-white dark:bg-slate-700 text-gray-600 dark:text-slate-300 rounded-lg hover:bg-gray-50 dark:hover:bg-slate-600 transition-colors border border-gray-300 dark:border-slate-500 flex items-center gap-1 text-[11px] font-bold uppercase tracking-wider cursor-pointer shadow-sm outline-none">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg> <span class="hidden xl:inline"><?= lang('Admin/TargetTahfidz.btn_edit') ?></span> 
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

<?= $this->endSection() ?>

<?= $this->section('modals') ?>

<div id="drawerOverlay" class="drawer-overlay fixed inset-0 hidden bg-gray-900/60 backdrop-blur-sm transition-opacity" style="z-index: 99998;" onclick="closeDrawer()"></div>
<div id="detailDrawer" class="drawer fixed inset-y-0 right-0 hidden bg-white dark:bg-slate-800 shadow-2xl w-80 md:w-96 transition-transform duration-300 transform translate-x-full border-l border-gray-200 dark:border-slate-700" style="z-index: 99999;">
  <div class="p-6 border-b border-gray-100 dark:border-slate-700 bg-white dark:bg-slate-800 sticky top-0 z-10 transition-colors">
    <div class="flex items-center justify-between mb-5">
      <h3 class="text-xl font-bold text-gray-800 dark:text-white"><?= lang('Admin/TargetTahfidz.modal_detail_title') ?></h3>
      <button onclick="closeDrawer()" class="p-2 hover:bg-gray-100 dark:hover:bg-slate-700 rounded-full transition-colors cursor-pointer text-gray-500 dark:text-slate-400 outline-none">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewbox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg>
      </button>
    </div>
    <div class="flex items-center gap-4">
      <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-[<?= $color['warna_primary'] ?>]/80 to-[<?= $color['warna_primary'] ?>] flex items-center justify-center shadow-lg flex-shrink-0">
        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewbox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
        </svg>
      </div>
      <div class="min-w-0">
        <p class="text-xl font-bold text-gray-900 dark:text-white truncate" id="drawerTitle">...</p>
        <p class="text-sm font-medium text-gray-500 dark:text-slate-400 mt-1 truncate" id="drawerSubtitle">...</p>
      </div>
    </div>
  </div>
  <div class="p-6 space-y-6 overflow-y-auto custom-scrollbar" style="height: calc(100vh - 180px);">
    <div>
      <label class="block text-[11px] font-bold text-gray-500 dark:text-slate-400 uppercase tracking-wider mb-2"><?= lang('Admin/TargetTahfidz.academic_year') ?></label>
      <div class="p-4 bg-gray-50 dark:bg-slate-700/50 rounded-xl border border-gray-100 dark:border-slate-600 transition-colors">
        <p class="font-bold text-gray-800 dark:text-white text-base"><?= get_tahun_ajaran() ?></p>
        <p class="text-sm font-medium text-gray-500 dark:text-slate-400 mt-1">
            <?= lang('Admin/TargetTahfidz.semester') ?> <?= get_semester() == 'Ganjil' ? lang('Admin/TargetTahfidz.odd') : lang('Admin/TargetTahfidz.even') ?> 
            <span class="text-emerald-600 dark:text-emerald-400 font-bold">(<?= lang('Admin/TargetTahfidz.active') ?>)</span>
        </p>
      </div>
    </div>
  </div>
</div>

<div id="addTargetModal" class="hidden fixed inset-0 z-[99999] overflow-y-auto">
  <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" onclick="closeAddTargetModal()"></div>
  <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0 md:pl-64">
    <div class="relative transform overflow-hidden rounded-3xl bg-white dark:bg-slate-800 text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-lg border border-transparent dark:border-slate-700">
      <div class="bg-white dark:bg-slate-800 px-6 py-5 border-b border-gray-100 dark:border-slate-700 flex justify-between items-center transition-colors">
        <div>
          <h3 class="text-xl font-bold leading-6 text-gray-900 dark:text-white"><?= lang('Admin/TargetTahfidz.modal_add_title') ?></h3>
          <p class="text-sm font-medium text-gray-500 dark:text-slate-400 mt-1"><?= lang('Admin/TargetTahfidz.modal_add_subtitle') ?></p>
        </div>
        <button type="button" onclick="closeAddTargetModal()" class="p-2 text-gray-400 hover:text-gray-600 dark:hover:text-slate-300 hover:bg-gray-100 dark:hover:bg-slate-700 rounded-full transition-colors outline-none">
          <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
        </button>
      </div>
      <form id="formAddTarget" action="<?= base_url('admin/target-tahfidz/store') ?>" onsubmit="saveTarget(event)">
        <div class="px-6 py-6 space-y-5 custom-scrollbar">
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
            <div>
              <label class="block text-sm font-bold text-gray-700 dark:text-slate-300 mb-2"><?= lang('Admin/TargetTahfidz.form_level') ?> <span class="text-red-500">*</span></label>
              <select name="tingkat" required class="block w-full rounded-xl border-gray-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-gray-800 dark:text-white shadow-sm focus:border-[<?= $color['warna_primary'] ?>] focus:ring-[<?= $color['warna_primary'] ?>] sm:text-sm py-3 px-4 border transition-colors appearance-none cursor-pointer outline-none">
                <option value=""><?= lang('Admin/TargetTahfidz.select_option') ?></option>
                <option value="VII">VII</option>
                <option value="VIII">VIII</option>
                <option value="IX">IX</option>
              </select>
            </div>
            <div>
              <label class="block text-sm font-bold text-gray-700 dark:text-slate-300 mb-2"><?= lang('Admin/TargetTahfidz.form_semester') ?> <span class="text-red-500">*</span></label>
              <select name="semester" required class="block w-full rounded-xl border-gray-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-gray-800 dark:text-white shadow-sm focus:border-[<?= $color['warna_primary'] ?>] focus:ring-[<?= $color['warna_primary'] ?>] sm:text-sm py-3 px-4 border transition-colors appearance-none cursor-pointer outline-none">
                <option value=""><?= lang('Admin/TargetTahfidz.select_option') ?></option>
                <option value="Ganjil"><?= lang('Admin/TargetTahfidz.odd') ?></option>
                <option value="Genap"><?= lang('Admin/TargetTahfidz.even') ?></option>
              </select>
            </div>
          </div>
          <div>
            <label class="block text-sm font-bold text-gray-700 dark:text-slate-300 mb-2"><?= lang('Admin/TargetTahfidz.form_target_juz') ?> <span class="text-red-500">*</span></label>
            <select name="juz_id" required class="block w-full rounded-xl border-gray-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-gray-800 dark:text-white shadow-sm focus:border-[<?= $color['warna_primary'] ?>] focus:ring-[<?= $color['warna_primary'] ?>] sm:text-sm py-3 px-4 border transition-colors appearance-none cursor-pointer outline-none">
              <option value=""><?= lang('Admin/TargetTahfidz.select_juz') ?></option>
              <?php foreach ($ref_juz as $juz): ?>
                <option value="<?= $juz['id'] ?>"><?= $juz['nama_juz'] ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
            <div>
              <label class="block text-sm font-bold text-gray-700 dark:text-slate-300 mb-2"><?= lang('Admin/TargetTahfidz.form_start_surah') ?> <span class="text-red-500">*</span></label>
              <select name="surah_mulai_id" required class="block w-full rounded-xl border-gray-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-gray-800 dark:text-white shadow-sm focus:border-[<?= $color['warna_primary'] ?>] focus:ring-[<?= $color['warna_primary'] ?>] sm:text-sm py-3 px-4 border transition-colors appearance-none cursor-pointer outline-none">
                <option value=""><?= lang('Admin/TargetTahfidz.select_surah') ?></option>
              </select>
            </div>
            <div>
              <label class="block text-sm font-bold text-gray-700 dark:text-slate-300 mb-2"><?= lang('Admin/TargetTahfidz.form_end_surah') ?> <span class="text-red-500">*</span></label>
              <select name="surah_sampai_id" required class="block w-full rounded-xl border-gray-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-gray-800 dark:text-white shadow-sm focus:border-[<?= $color['warna_primary'] ?>] focus:ring-[<?= $color['warna_primary'] ?>] sm:text-sm py-3 px-4 border transition-colors appearance-none cursor-pointer outline-none">
                <option value=""><?= lang('Admin/TargetTahfidz.select_surah') ?></option>
              </select>
            </div>
          </div>
        </div>
        <div class="bg-gray-50 dark:bg-slate-800/80 px-6 py-4 flex flex-col sm:flex-row sm:justify-end gap-3 border-t border-gray-100 dark:border-slate-700 transition-colors">
          <button type="button" onclick="closeAddTargetModal()" class="w-full sm:w-auto px-6 py-3 bg-white dark:bg-slate-700 border border-gray-300 dark:border-slate-600 text-gray-700 dark:text-slate-300 font-bold rounded-xl hover:bg-gray-50 dark:hover:bg-slate-600 transition-colors shadow-sm outline-none"><?= lang('Admin/TargetTahfidz.btn_cancel') ?></button>
          <button type="submit" class="w-full sm:w-auto px-6 py-3 text-white font-bold rounded-xl shadow-lg transition-transform transform hover:-translate-y-0.5 outline-none" style="background-color: <?= $color['warna_primary'] ?>; box-shadow: 0 10px 15px -3px <?= $color['warna_primary'] ?>40;"><?= lang('Admin/TargetTahfidz.btn_save_target') ?></button>
        </div>
      </form>
    </div>
  </div>
</div>

<div id="editTargetModal" class="hidden fixed inset-0 z-[99999] overflow-y-auto">
  <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" onclick="closeEditTargetModal()"></div>
  <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0 md:pl-64">
    <div class="relative transform overflow-hidden rounded-3xl bg-white dark:bg-slate-800 text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-lg border border-transparent dark:border-slate-700">
      <div class="bg-white dark:bg-slate-800 px-6 py-5 border-b border-gray-100 dark:border-slate-700 flex justify-between items-center transition-colors">
        <div>
            <h3 class="text-xl font-bold leading-6 text-gray-900 dark:text-white"><?= lang('Admin/TargetTahfidz.modal_edit_title') ?></h3>
            <p class="text-sm font-medium text-gray-500 dark:text-slate-400 mt-1"><?= lang('Admin/TargetTahfidz.modal_edit_subtitle') ?></p>
        </div>
        <button type="button" onclick="closeEditTargetModal()" class="p-2 text-gray-400 hover:text-gray-600 dark:hover:text-slate-300 hover:bg-gray-100 dark:hover:bg-slate-700 rounded-full transition-colors outline-none">
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
        </button>
      </div>
      <form id="formEditTarget" action="<?= base_url('admin/target-tahfidz/update') ?>" onsubmit="updateTarget(event)">
        <input type="hidden" name="id" id="edit_id"> 
        <?= csrf_field(); ?>
        <div class="px-6 py-6 space-y-5 custom-scrollbar">
          <div class="bg-[<?= $color['warna_primary'] ?>]/10 dark:bg-[<?= $color['warna_primary'] ?>]/20 border border-[<?= $color['warna_primary'] ?>]/30 dark:border-[<?= $color['warna_primary'] ?>]/50 rounded-xl p-4 text-sm text-[<?= $color['warna_primary'] ?>] dark:text-[<?= $color['warna_primary'] ?>] flex items-center gap-3 transition-colors shadow-sm">
             <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
             <div>
                 <span id="text_editing"></span> <span id="label_tingkat_semester" class="font-black ml-1 uppercase tracking-wide"></span>
             </div>
          </div>
          <div>
            <label class="block text-sm font-bold text-gray-700 dark:text-slate-300 mb-2"><?= lang('Admin/TargetTahfidz.form_target_juz') ?></label>
            <select name="juz_id" id="edit_juz_id" class="block w-full rounded-xl border-gray-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-gray-800 dark:text-white shadow-sm focus:border-[<?= $color['warna_primary'] ?>] focus:ring-[<?= $color['warna_primary'] ?>] sm:text-sm py-3 px-4 border transition-colors appearance-none cursor-pointer outline-none">
               <?php foreach ($ref_juz as $juz): ?>
                  <option value="<?= $juz['id'] ?>"><?= $juz['nama_juz'] ?></option>
               <?php endforeach; ?>
            </select>
          </div>
           <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
            <div>
              <label class="block text-sm font-bold text-gray-700 dark:text-slate-300 mb-2"><?= lang('Admin/TargetTahfidz.form_start_surah') ?></label>
              <select name="surah_mulai_id" id="edit_surah_mulai_id" class="block w-full rounded-xl border-gray-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-gray-800 dark:text-white shadow-sm focus:border-[<?= $color['warna_primary'] ?>] focus:ring-[<?= $color['warna_primary'] ?>] sm:text-sm py-3 px-4 border transition-colors appearance-none cursor-pointer outline-none">
                  <?php foreach ($ref_surah as $s): ?>
                  <option value="<?= $s['id'] ?>"><?= $s['no_surah'] ?>. <?= $s['nama_surah'] ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div>
              <label class="block text-sm font-bold text-gray-700 dark:text-slate-300 mb-2"><?= lang('Admin/TargetTahfidz.form_end_surah') ?></label>
              <select name="surah_sampai_id" id="edit_surah_sampai_id" class="block w-full rounded-xl border-gray-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-gray-800 dark:text-white shadow-sm focus:border-[<?= $color['warna_primary'] ?>] focus:ring-[<?= $color['warna_primary'] ?>] sm:text-sm py-3 px-4 border transition-colors appearance-none cursor-pointer outline-none">
                  <?php foreach ($ref_surah as $s): ?>
                  <option value="<?= $s['id'] ?>"><?= $s['no_surah'] ?>. <?= $s['nama_surah'] ?></option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>
        </div>
        <div class="bg-gray-50 dark:bg-slate-800/80 px-6 py-4 flex flex-col sm:flex-row sm:justify-end gap-3 border-t border-gray-100 dark:border-slate-700 transition-colors">
          <button type="button" onclick="closeEditTargetModal()" class="w-full sm:w-auto px-6 py-3 bg-white dark:bg-slate-700 border border-gray-300 dark:border-slate-600 text-gray-700 dark:text-slate-300 font-bold rounded-xl hover:bg-gray-50 dark:hover:bg-slate-600 transition-colors shadow-sm outline-none"><?= lang('Admin/TargetTahfidz.btn_cancel') ?></button>
          <button type="submit" class="w-full sm:w-auto px-6 py-3 text-white font-bold rounded-xl shadow-lg transition-transform transform hover:-translate-y-0.5 outline-none" style="background-color: <?= $color['warna_primary'] ?>; box-shadow: 0 10px 15px -3px <?= $color['warna_primary'] ?>40;"><?= lang('Admin/TargetTahfidz.btn_save_changes') ?></button>
        </div>
      </form>
    </div>
  </div>
</div>

<div id="templateModal" class="hidden fixed inset-0 z-[99999] overflow-y-auto">
  <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" onclick="closeTemplateModal()"></div>
  <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0 md:pl-64">
    <div class="relative transform overflow-hidden rounded-3xl bg-white dark:bg-slate-800 text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-2xl border border-transparent dark:border-slate-700">
      <div class="bg-white dark:bg-slate-800 px-6 py-5 border-b border-gray-100 dark:border-slate-700 flex justify-between items-center transition-colors">
        <div>
            <h3 class="text-xl font-bold leading-6 text-gray-900 dark:text-white"><?= lang('Admin/TargetTahfidz.modal_template_title') ?></h3>
            <p class="text-sm font-medium text-gray-500 dark:text-slate-400 mt-1"><?= lang('Admin/TargetTahfidz.modal_template_subtitle') ?></p>
        </div>
        <button type="button" onclick="closeTemplateModal()" class="p-2 text-gray-400 hover:text-gray-600 dark:hover:text-slate-300 hover:bg-gray-100 dark:hover:bg-slate-700 rounded-full transition-colors outline-none">
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
        </button>
      </div>
      <div class="px-6 py-6 space-y-4 custom-scrollbar">
        <div class="border-2 border-gray-200 dark:border-slate-600 bg-white dark:bg-slate-700/50 rounded-2xl p-5 hover:border-[<?= $color['warna_primary'] ?>] dark:hover:border-[<?= $color['warna_primary'] ?>] transition-colors cursor-pointer group shadow-sm" onclick="selectTemplate('juz-amma')">
          <div class="flex items-start justify-between mb-2">
            <div class="flex items-center gap-4">
              <div class="w-14 h-14 rounded-xl flex items-center justify-center shadow-md group-hover:scale-105 transition-transform" style="background: linear-gradient(135deg, <?= $color['warna_primary'] ?>, <?= $color['warna_primary'] ?>cc);">
                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
              </div>
              <div>
                <h4 class="font-bold text-gray-900 dark:text-white text-lg group-hover:text-[<?= $color['warna_primary'] ?>] transition-colors"><?= lang('Admin/TargetTahfidz.template_juz_amma') ?></h4>
                <p class="text-sm font-medium text-gray-500 dark:text-slate-400 mt-0.5"><?= lang('Admin/TargetTahfidz.template_juz_amma_desc') ?></p>
              </div>
            </div>
            <span class="px-2.5 py-1 bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400 text-[10px] font-black uppercase tracking-wider rounded-full border border-emerald-200 dark:border-emerald-800/50 shadow-sm"><?= lang('Admin/TargetTahfidz.popular') ?></span>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div id="importModal" class="hidden fixed inset-0 z-[100000] overflow-y-auto">
  <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" onclick="closeImportModal()"></div>
  <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0 md:pl-64">
    <div class="relative transform overflow-hidden rounded-3xl bg-white dark:bg-slate-800 text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-md border border-transparent dark:border-slate-700">
      <div class="bg-white dark:bg-slate-800 px-6 py-5 border-b border-gray-100 dark:border-slate-700 flex justify-between items-center transition-colors">
        <div>
          <h3 class="text-xl font-bold leading-6 text-gray-900 dark:text-white"><?= lang('Admin/TargetTahfidz.modal_import_title') ?></h3>
          <p class="text-sm font-medium text-gray-500 dark:text-slate-400 mt-1"><?= lang('Admin/TargetTahfidz.modal_import_subtitle') ?></p>
        </div>
        <button type="button" onclick="closeImportModal()" class="p-2 text-gray-400 hover:text-gray-600 dark:hover:text-slate-300 hover:bg-gray-100 dark:hover:bg-slate-700 rounded-full transition-colors outline-none">
          <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
        </button>
      </div>
      <form id="importForm" action="<?= base_url('admin/target-tahfidz/import') ?>" method="POST" onsubmit="handleImportSubmit(event)" enctype="multipart/form-data">
        <?= csrf_field() ?>
        <div class="p-6">
            <div class="mb-5">
                <p class="text-sm font-bold text-gray-700 dark:text-slate-300 mb-2 transition-colors"><?= lang('Admin/TargetTahfidz.step_1_download') ?></p>
                <a href="<?= base_url('admin/target-tahfidz/template') ?>" class="inline-flex items-center gap-2 px-4 py-2 font-bold rounded-lg transition-colors text-sm shadow-sm hover:opacity-80" style="color: <?= $color['warna_primary'] ?>; background-color: <?= $color['warna_primary'] ?>1A;">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" /></svg> <?= lang('Admin/TargetTahfidz.btn_download_template') ?>
                </a>
            </div>
            <div class="mb-2">
                <p class="text-sm font-bold text-gray-700 dark:text-slate-300 mb-2 transition-colors"><?= lang('Admin/TargetTahfidz.step_2_upload') ?></p>
                <input type="file" name="file_excel" accept=".xlsx, .xls" required class="block w-full text-sm text-gray-500 dark:text-slate-400 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-gray-100 dark:file:bg-slate-700 file:text-gray-700 dark:file:text-slate-200 hover:file:bg-gray-200 dark:hover:file:bg-slate-600 border dark:border-slate-600 cursor-pointer outline-none focus:ring-2 focus:ring-[<?= $color['warna_primary'] ?>] transition-colors">
            </div>
        </div>
        <div class="bg-gray-50 dark:bg-slate-800/80 px-6 py-4 flex flex-col sm:flex-row sm:justify-end gap-3 border-t border-gray-100 dark:border-slate-700 transition-colors">
          <button type="button" onclick="closeImportModal()" class="w-full sm:w-auto px-6 py-3 bg-white dark:bg-slate-700 border border-gray-300 dark:border-slate-600 text-gray-700 dark:text-slate-300 font-bold rounded-xl hover:bg-gray-50 dark:hover:bg-slate-600 transition-colors shadow-sm outline-none"><?= lang('Admin/TargetTahfidz.btn_cancel') ?></button>
          <button type="submit" class="w-full sm:w-auto px-6 py-3 text-white font-bold rounded-xl shadow-lg transition-transform transform hover:-translate-y-0.5 flex items-center justify-center gap-2 outline-none" style="background-color: <?= $color['warna_primary'] ?>; box-shadow: 0 10px 15px -3px <?= $color['warna_primary'] ?>40;">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" /></svg> <?= lang('Admin/TargetTahfidz.btn_upload_import') ?>
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<div id="riwayatModal" class="hidden fixed inset-0 z-[100000] overflow-y-auto">
  <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" onclick="closeRiwayatModal()"></div>
  <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0 md:pl-64">
    <div class="relative transform overflow-hidden rounded-3xl bg-white dark:bg-slate-800 text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-2xl border border-transparent dark:border-slate-700">
      <div class="bg-white dark:bg-slate-800 px-6 py-5 border-b border-gray-100 dark:border-slate-700 flex justify-between items-center transition-colors">
        <div>
          <h3 class="text-xl font-bold leading-6 text-gray-900 dark:text-white"><?= lang('Admin/TargetTahfidz.modal_history_title') ?></h3>
          <p class="text-sm font-medium text-gray-500 dark:text-slate-400 mt-1"><?= lang('Admin/TargetTahfidz.modal_history_subtitle') ?></p>
        </div>
        <button type="button" onclick="closeRiwayatModal()" class="p-2 text-gray-400 hover:text-gray-600 dark:hover:text-slate-300 hover:bg-gray-100 dark:hover:bg-slate-700 rounded-full transition-colors cursor-pointer outline-none">
          <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
        </button>
      </div>
      <div class="p-6 h-96 overflow-y-auto custom-scrollbar bg-gray-50 dark:bg-slate-900/50 transition-colors">
          <ul id="listRiwayatContainer" class="space-y-4">
              <li class="text-center text-sm font-medium text-gray-500 dark:text-slate-400 py-16 flex flex-col items-center gap-3">
                 <svg class="animate-spin h-8 w-8 text-[<?= $color['warna_primary'] ?>]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                 <span><?= lang('Admin/TargetTahfidz.loading_history') ?></span>
              </li>
          </ul>
      </div>
    </div>
  </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    const BASE_URL = "<?= base_url() ?>";
    
    // Perbaikan: Pastikan LANG selalu berupa Object, bukan String
    let rawLang = <?= json_encode(lang('Admin/TargetTahfidz')) ?>;
    const LANG = typeof rawLang === 'object' ? rawLang : {};
</script>
<script src="<?= base_url('assets/js/Admin/target-tahfidz.js') ?>?v=<?= time() ?>"></script>
<?= $this->endSection() ?>