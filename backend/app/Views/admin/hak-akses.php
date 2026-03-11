<?= $this->extend('layout/main') ?>

<?= $this->section('title') ?>
  <?= lang('Admin/HakAkses.page_title') ?> - Rapor Digital SMPIT Ad Durrah
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
  <link rel="stylesheet" href="<?= base_url('assets/css/Admin/hak-akses.css') ?>">
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<style>
    /* Override untuk tabel matrix Hak Akses */
    html.dark .permission-table,
    html.dark .permission-table tr,
    html.dark .permission-table td {
        background-color: #1e293b !important; 
        color: #f1f5f9 !important; 
        border-color: #334155 !important; 
    }
    
    html.dark tr[id^="expand-"] td,
    html.dark .expandable-section {
        background-color: #0f172a !important; 
        border-color: #334155 !important;
    }
    
    html.dark .expandable-section p {
        color: #cbd5e1 !important; 
    }

    html.dark .role-card {
        background-color: #1e293b !important;
        border-color: #334155 !important;
    }
    html.dark .role-card:hover, html.dark .role-card.active {
        background-color: #334155 !important; 
    }
</style>
<div class="flex items-center gap-2 text-sm text-gray-500 dark:text-slate-400 mb-3 transition-colors">
  <span><?= lang('Admin/HakAkses.breadcrumb') ?></span>
  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
  <span class="text-[<?= $color['warna_primary'] ?>] font-medium"><?= lang('Admin/HakAkses.page_title') ?></span>
</div>

<div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 mb-6">
  <div>
    <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white mb-2 flex items-center gap-3 transition-colors">
      <svg class="w-8 h-8 text-[<?= $color['warna_primary'] ?>]" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg> 
      <?= lang('Admin/HakAkses.page_header') ?>
    </h1>
    <p class="text-sm md:text-base text-gray-600 dark:text-slate-400 transition-colors"><?= lang('Admin/HakAkses.page_desc') ?></p>
  </div>
  <div class="flex flex-wrap items-center gap-2">
    <button onclick="addRole()" class="px-4 py-2.5 bg-white dark:bg-slate-800 border border-gray-300 dark:border-slate-600 text-gray-700 dark:text-slate-300 font-medium rounded-xl hover:bg-gray-50 dark:hover:bg-slate-700 transition-colors flex items-center gap-2 shadow-sm outline-none">
      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
      <span><?= lang('Admin/HakAkses.btn_add_role') ?></span> 
    </button> 
    <button onclick="showSaveModal()" class="px-4 py-2.5 bg-[<?= $color['warna_primary'] ?>] hover:bg-[<?= $color['warna_primary'] ?>]/90 text-white font-semibold rounded-xl transition-all shadow-lg shadow-[<?= $color['warna_primary'] ?>]/20 flex items-center gap-2 transform hover:-translate-y-0.5 outline-none">
      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" /></svg>
      <span><?= lang('Admin/HakAkses.btn_save_changes') ?></span> 
    </button>
  </div>
</div>

<div class="bg-amber-50 dark:bg-amber-900/20 border-l-4 border-amber-400 dark:border-amber-500/50 p-4 rounded-xl mb-6 shadow-sm transition-colors">
  <div class="flex items-start gap-3">
    <div class="w-10 h-10 rounded-lg bg-amber-400 dark:bg-amber-500/80 flex items-center justify-center flex-shrink-0 shadow-sm">
      <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
    </div>
    <div class="flex-1">
      <h4 class="font-bold text-amber-900 dark:text-amber-400 mb-2 flex flex-wrap items-center gap-2"><?= lang('Admin/HakAkses.sec_protection') ?>
        <span class="inline-flex items-center gap-1 text-[10px] bg-amber-100 dark:bg-amber-900/40 text-amber-700 dark:text-amber-300 px-2 py-0.5 rounded-full border border-amber-200 dark:border-amber-800/50">
          <svg class="w-3 h-3" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg> 
          <?= lang('Admin/HakAkses.sec_badge') ?>
        </span>
      </h4>
      <ul class="text-sm text-amber-800 dark:text-amber-300 space-y-1.5 font-medium">
        <li class="flex items-start gap-2">
          <svg class="w-4 h-4 text-amber-600 dark:text-amber-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg> 
          <span><?= lang('Admin/HakAkses.sec_rule_1') ?></span>
        </li>
        <li class="flex items-start gap-2">
          <svg class="w-4 h-4 text-amber-600 dark:text-amber-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg> 
          <span><?= lang('Admin/HakAkses.sec_rule_2') ?></span>
        </li>
        <li class="flex items-start gap-2">
          <svg class="w-4 h-4 text-amber-600 dark:text-amber-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg> 
          <span><?= lang('Admin/HakAkses.sec_rule_3') ?></span>
        </li>
      </ul>
    </div>
  </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-4 gap-6 mb-6">
  
  <div class="lg:col-span-1">
    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-gray-200 dark:border-slate-700 p-5 shadow-sm transition-colors">
      <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
        <svg class="w-5 h-5 text-[<?= $color['warna_primary'] ?>]" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg> <?= lang('Admin/HakAkses.role_list') ?>
      </h3>
      <div id="roleList" class="space-y-3 custom-scrollbar overflow-y-auto max-h-[600px] pr-1">
          <?php foreach($roles as $role): ?>
          <div class="role-card p-4 rounded-xl border border-gray-100 dark:border-slate-600 bg-gray-50/50 dark:bg-slate-700/30 hover:bg-[<?= $color['warna_secondary'] ?>] dark:hover:bg-slate-700/80 cursor-pointer transition-colors [&.active]:border-[<?= $color['warna_primary'] ?>] [&.active]:bg-[<?= $color['warna_secondary'] ?>] dark:[&.active]:bg-slate-700" 
                onclick="selectRole(this, '<?= $role['id'] ?>')">
              <div class="flex items-start justify-between mb-3">
                  <div class="flex items-center gap-3">
                      <div class="w-10 h-10 rounded-lg bg-[<?= $color['warna_primary'] ?>]/10 dark:bg-[<?= $color['warna_primary'] ?>]/20 flex items-center justify-center flex-shrink-0 border border-[<?= $color['warna_primary'] ?>]/20 dark:border-[<?= $color['warna_primary'] ?>]/30">
                          <svg class="w-5 h-5 text-[<?= $color['warna_primary'] ?>]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                          </svg>
                      </div>
                      <div class="min-w-0">
                          <h4 class="font-bold text-gray-900 dark:text-white text-sm truncate"><?= esc($role['role_name'] ?? lang('Admin/HakAkses.role_name')) ?></h4>
                          <p class="text-[11px] text-gray-500 dark:text-slate-400 truncate mt-0.5 font-medium"><?= esc($role['description'] ?? lang('Admin/HakAkses.role_desc')) ?></p>
                      </div>
                  </div>
              </div>
              <div class="flex items-center justify-between text-xs mt-3 pt-3 border-t border-gray-200 dark:border-slate-600 transition-colors">
                  <span class="text-gray-600 dark:text-slate-400 font-medium"><?= lang('Admin/HakAkses.status') ?></span>
                  <?php if(($role['status'] ?? 'active') == 'active'): ?>
                      <span class="inline-flex px-2 py-0.5 bg-emerald-50 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400 rounded text-[10px] font-bold uppercase tracking-wider border border-emerald-200 dark:border-emerald-800/50 shadow-sm"><?= lang('Admin/HakAkses.status_active') ?></span>
                  <?php else: ?>
                      <span class="inline-flex px-2 py-0.5 bg-gray-100 dark:bg-slate-700 text-gray-500 dark:text-slate-400 rounded text-[10px] font-bold uppercase tracking-wider border border-gray-200 dark:border-slate-600 shadow-sm"><?= lang('Admin/HakAkses.status_inactive') ?></span>
                  <?php endif; ?>
              </div>
          </div>
          <?php endforeach; ?>
      </div>
    </div>
  </div>

  <div class="lg:col-span-3">
    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-gray-200 dark:border-slate-700 shadow-sm overflow-hidden transition-colors">
      <div class="p-6 border-b border-[<?= $color['warna_primary'] ?>] bg-gradient-to-r from-[<?= $color['warna_secondary'] ?>] dark:from-slate-800 to-white dark:to-slate-800 transition-colors">
        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-1 flex items-center gap-2">
          <svg class="w-6 h-6 text-[<?= $color['warna_primary'] ?>]" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg> 
          <?= lang('Admin/HakAkses.matrix_title') ?> <span class="text-[<?= $color['warna_primary'] ?>]"><?= lang('Admin/HakAkses.select_role') ?></span>
        </h3>
        <p class="text-sm font-medium text-gray-600 dark:text-slate-400 mt-1"><?= lang('Admin/HakAkses.matrix_desc') ?></p>
      </div>
      <div class="overflow-x-auto custom-scrollbar">
        <table class="permission-table w-full text-left border-collapse min-w-max">
          <thead class="bg-[<?= $color['warna_primary'] ?>] text-white">
            <tr>
              <th class="w-1/4 px-6 py-4 font-bold text-sm uppercase tracking-wider"><?= lang('Admin/HakAkses.th_module') ?></th>
              <th class="text-center px-4 py-4 font-bold text-xs uppercase tracking-wider">
                <div class="tooltip inline-block relative cursor-help">
                  <?= lang('Admin/HakAkses.th_view') ?> 
                  <span class="tooltiptext invisible opacity-0 absolute z-50 bottom-[120%] left-1/2 transform -translate-x-1/2 bg-gray-900 text-white text-[10px] px-2 py-1 rounded whitespace-nowrap transition-opacity"><?= lang('Admin/HakAkses.tt_view') ?></span>
                </div>
              </th>
              <th class="text-center px-4 py-4 font-bold text-xs uppercase tracking-wider">
                <div class="tooltip inline-block relative cursor-help">
                  <?= lang('Admin/HakAkses.th_create') ?> 
                  <span class="tooltiptext invisible opacity-0 absolute z-50 bottom-[120%] left-1/2 transform -translate-x-1/2 bg-gray-900 text-white text-[10px] px-2 py-1 rounded whitespace-nowrap transition-opacity"><?= lang('Admin/HakAkses.tt_create') ?></span>
                </div>
              </th>
              <th class="text-center px-4 py-4 font-bold text-xs uppercase tracking-wider">
                <div class="tooltip inline-block relative cursor-help">
                  <?= lang('Admin/HakAkses.th_update') ?> 
                  <span class="tooltiptext invisible opacity-0 absolute z-50 bottom-[120%] left-1/2 transform -translate-x-1/2 bg-gray-900 text-white text-[10px] px-2 py-1 rounded whitespace-nowrap transition-opacity"><?= lang('Admin/HakAkses.tt_update') ?></span>
                </div>
              </th>
              <th class="text-center px-4 py-4 font-bold text-xs uppercase tracking-wider">
                <div class="tooltip inline-block relative cursor-help">
                  <?= lang('Admin/HakAkses.th_delete') ?> 
                  <span class="tooltiptext invisible opacity-0 absolute z-50 bottom-[120%] left-1/2 transform -translate-x-1/2 bg-gray-900 text-white text-[10px] px-2 py-1 rounded whitespace-nowrap transition-opacity"><?= lang('Admin/HakAkses.tt_delete') ?></span>
                </div>
              </th>
              <th class="text-center px-4 py-4 font-bold text-xs uppercase tracking-wider">
                <div class="tooltip inline-block relative cursor-help">
                  <?= lang('Admin/HakAkses.th_special') ?> 
                  <span class="tooltiptext invisible opacity-0 absolute z-50 bottom-[120%] left-1/2 transform -translate-x-1/2 bg-gray-900 text-white text-[10px] px-2 py-1 rounded whitespace-nowrap transition-opacity"><?= lang('Admin/HakAkses.tt_special') ?></span>
                </div>
              </th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-100 dark:divide-slate-700/50 transition-colors">

            <tr class="module-row hover:bg-gray-50 dark:hover:bg-slate-700/50 cursor-pointer transition-colors" data-module="dashboard" onclick="toggleExpand('dashboard')">
              <td class="font-bold text-gray-900 dark:text-white px-6 py-4">
                <div class="flex items-center gap-3">
                  <svg class="w-5 h-5 text-[<?= $color['warna_primary'] ?>] expand-icon transition-transform" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                  <span class="module-name hover:text-[<?= $color['warna_primary'] ?>] transition-colors"><?= lang('Admin/HakAkses.mod_dashboard') ?></span>
                </div>
              </td>
              <td class="text-center px-4 py-4"><span class="toggle-switch w-11 h-6 bg-gray-200 dark:bg-slate-600 rounded-full relative inline-block cursor-pointer transition-colors [&.active]:bg-[<?= $color['warna_primary'] ?>]" data-action="view" onclick="event.stopPropagation(); togglePermission(this)"></span></td>
              <td class="text-center px-4 py-4"><span class="toggle-switch w-11 h-6 bg-gray-200 dark:bg-slate-600 rounded-full relative inline-block cursor-pointer transition-colors [&.active]:bg-[<?= $color['warna_primary'] ?>]" data-action="create" onclick="event.stopPropagation(); togglePermission(this)"></span></td>
              <td class="text-center px-4 py-4"><span class="toggle-switch w-11 h-6 bg-gray-200 dark:bg-slate-600 rounded-full relative inline-block cursor-pointer transition-colors [&.active]:bg-[<?= $color['warna_primary'] ?>]" data-action="update" onclick="event.stopPropagation(); togglePermission(this)"></span></td>
              <td class="text-center px-4 py-4"><span class="toggle-switch w-11 h-6 bg-gray-200 dark:bg-slate-600 rounded-full relative inline-block cursor-pointer transition-colors [&.active]:bg-[<?= $color['warna_primary'] ?>]" data-action="delete" onclick="event.stopPropagation(); togglePermission(this)"></span></td>
              <td class="text-center px-4 py-4"><span class="toggle-switch w-11 h-6 bg-gray-200 dark:bg-slate-600 rounded-full relative inline-block cursor-pointer transition-colors [&.active]:bg-[<?= $color['warna_primary'] ?>]" data-action="special" onclick="event.stopPropagation(); togglePermission(this)"></span></td>
            </tr>
            <tr id="expand-dashboard" class="bg-gray-50/50 dark:bg-slate-900/30 transition-colors hidden">
              <td colspan="6" class="p-0">
                <div class="expandable-section px-6 py-4 pl-14">
                  <div class="text-sm text-gray-600 dark:text-slate-400 space-y-2 font-medium">
                    <p class="font-bold text-gray-800 dark:text-slate-200 mb-2 border-b border-gray-200 dark:border-slate-700 pb-1 inline-block"><?= lang('Admin/HakAkses.sub_dash') ?></p>
                    <p class="flex items-center gap-2"><span class="text-[<?= $color['warna_primary'] ?>]">•</span> <?= lang('Admin/HakAkses.dash_stat') ?></p>
                    <p class="flex items-center gap-2"><span class="text-[<?= $color['warna_primary'] ?>]">•</span> <?= lang('Admin/HakAkses.dash_insight') ?></p>
                    <p class="flex items-center gap-2"><span class="text-[<?= $color['warna_primary'] ?>]">•</span> <?= lang('Admin/HakAkses.dash_analytic') ?></p>
                  </div>
                </div>
              </td>
            </tr>

            <tr class="module-row hover:bg-gray-50 dark:hover:bg-slate-700/50 cursor-pointer transition-colors border-t border-gray-100 dark:border-slate-700" data-module="pengguna" onclick="toggleExpand('pengguna')">
              <td class="font-bold text-gray-900 dark:text-white px-6 py-4">
                <div class="flex items-center gap-3">
                  <svg class="w-5 h-5 text-[<?= $color['warna_primary'] ?>] expand-icon transition-transform" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                  <span class="module-name hover:text-[<?= $color['warna_primary'] ?>] transition-colors"><?= lang('Admin/HakAkses.mod_users') ?></span>
                </div>
              </td>
              <td class="text-center px-4 py-4"><span class="toggle-switch w-11 h-6 bg-gray-200 dark:bg-slate-600 rounded-full relative inline-block cursor-pointer transition-colors [&.active]:bg-[<?= $color['warna_primary'] ?>]" data-action="view" onclick="event.stopPropagation(); togglePermission(this)"></span></td>
              <td class="text-center px-4 py-4"><span class="toggle-switch w-11 h-6 bg-gray-200 dark:bg-slate-600 rounded-full relative inline-block cursor-pointer transition-colors [&.active]:bg-[<?= $color['warna_primary'] ?>]" data-action="create" onclick="event.stopPropagation(); togglePermission(this)"></span></td>
              <td class="text-center px-4 py-4"><span class="toggle-switch w-11 h-6 bg-gray-200 dark:bg-slate-600 rounded-full relative inline-block cursor-pointer transition-colors [&.active]:bg-[<?= $color['warna_primary'] ?>]" data-action="update" onclick="event.stopPropagation(); togglePermission(this)"></span></td>
              <td class="text-center px-4 py-4"><span class="toggle-switch w-11 h-6 bg-gray-200 dark:bg-slate-600 rounded-full relative inline-block cursor-pointer transition-colors [&.active]:bg-[<?= $color['warna_primary'] ?>]" data-action="delete" onclick="event.stopPropagation(); togglePermission(this)"></span></td>
              <td class="text-center px-4 py-4"><span class="toggle-switch w-11 h-6 bg-gray-200 dark:bg-slate-600 rounded-full relative inline-block cursor-pointer transition-colors [&.active]:bg-[<?= $color['warna_primary'] ?>]" data-action="special" onclick="event.stopPropagation(); togglePermission(this)"></span></td>
            </tr>
            <tr id="expand-pengguna" class="bg-gray-50/50 dark:bg-slate-900/30 transition-colors hidden">
              <td colspan="6" class="p-0">
                <div class="expandable-section px-6 py-4 pl-14">
                  <div class="text-sm text-gray-600 dark:text-slate-400 space-y-2 font-medium">
                    <p class="font-bold text-gray-800 dark:text-slate-200 mb-2 border-b border-gray-200 dark:border-slate-700 pb-1 inline-block"><?= lang('Admin/HakAkses.sub_users') ?></p>
                    <p class="flex items-center gap-2"><span class="text-[<?= $color['warna_primary'] ?>]">•</span> <?= lang('Admin/HakAkses.usr_students') ?></p>
                    <p class="flex items-center gap-2"><span class="text-[<?= $color['warna_primary'] ?>]">•</span> <?= lang('Admin/HakAkses.usr_teachers') ?></p>
                    <p class="flex items-center gap-2"><span class="text-[<?= $color['warna_primary'] ?>]">•</span> <?= lang('Admin/HakAkses.usr_parents') ?></p>
                    <p class="flex items-center gap-2"><span class="text-[<?= $color['warna_primary'] ?>]">•</span> <?= lang('Admin/HakAkses.usr_import') ?></p>
                  </div>
                </div>
              </td>
            </tr>

            <tr class="module-row hover:bg-gray-50 dark:hover:bg-slate-700/50 cursor-pointer transition-colors border-t border-gray-100 dark:border-slate-700" data-module="akademik" onclick="toggleExpand('akademik')">
              <td class="font-bold text-gray-900 dark:text-white px-6 py-4">
                <div class="flex items-center gap-3">
                  <svg class="w-5 h-5 text-[<?= $color['warna_primary'] ?>] expand-icon transition-transform" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                  <span class="module-name hover:text-[<?= $color['warna_primary'] ?>] transition-colors"><?= lang('Admin/HakAkses.mod_academic') ?></span>
                </div>
              </td>
              <td class="text-center px-4 py-4"><span class="toggle-switch w-11 h-6 bg-gray-200 dark:bg-slate-600 rounded-full relative inline-block cursor-pointer transition-colors [&.active]:bg-[<?= $color['warna_primary'] ?>]" data-action="view" onclick="event.stopPropagation(); togglePermission(this)"></span></td>
              <td class="text-center px-4 py-4"><span class="toggle-switch w-11 h-6 bg-gray-200 dark:bg-slate-600 rounded-full relative inline-block cursor-pointer transition-colors [&.active]:bg-[<?= $color['warna_primary'] ?>]" data-action="create" onclick="event.stopPropagation(); togglePermission(this)"></span></td>
              <td class="text-center px-4 py-4"><span class="toggle-switch w-11 h-6 bg-gray-200 dark:bg-slate-600 rounded-full relative inline-block cursor-pointer transition-colors [&.active]:bg-[<?= $color['warna_primary'] ?>]" data-action="update" onclick="event.stopPropagation(); togglePermission(this)"></span></td>
              <td class="text-center px-4 py-4"><span class="toggle-switch w-11 h-6 bg-gray-200 dark:bg-slate-600 rounded-full relative inline-block cursor-pointer transition-colors [&.active]:bg-[<?= $color['warna_primary'] ?>]" data-action="delete" onclick="event.stopPropagation(); togglePermission(this)"></span></td>
              <td class="text-center px-4 py-4"><span class="toggle-switch w-11 h-6 bg-gray-200 dark:bg-slate-600 rounded-full relative inline-block cursor-pointer transition-colors [&.active]:bg-[<?= $color['warna_primary'] ?>]" data-action="special" onclick="event.stopPropagation(); togglePermission(this)"></span></td>
            </tr>
            <tr id="expand-akademik" class="bg-gray-50/50 dark:bg-slate-900/30 transition-colors hidden">
              <td colspan="6" class="p-0">
                <div class="expandable-section px-6 py-4 pl-14">
                  <div class="text-sm text-gray-600 dark:text-slate-400 space-y-2 font-medium">
                    <p class="font-bold text-gray-800 dark:text-slate-200 mb-2 border-b border-gray-200 dark:border-slate-700 pb-1 inline-block"><?= lang('Admin/HakAkses.sub_academic') ?></p>
                    <p class="flex items-center gap-2"><span class="text-[<?= $color['warna_primary'] ?>]">•</span> <?= lang('Admin/HakAkses.acad_levels') ?></p>
                    <p class="flex items-center gap-2"><span class="text-[<?= $color['warna_primary'] ?>]">•</span> <?= lang('Admin/HakAkses.acad_subjects') ?></p>
                    <p class="flex items-center gap-2"><span class="text-[<?= $color['warna_primary'] ?>]">•</span> <?= lang('Admin/HakAkses.acad_homeroom') ?></p>
                    <p class="flex items-center gap-2"><span class="text-[<?= $color['warna_primary'] ?>]">•</span> <?= lang('Admin/HakAkses.acad_mapping') ?></p>
                  </div>
                </div>
              </td>
            </tr>

            <tr class="module-row hover:bg-gray-50 dark:hover:bg-slate-700/50 cursor-pointer transition-colors border-t border-gray-100 dark:border-slate-700" data-module="konfigurasi" onclick="toggleExpand('konfigurasi')">
              <td class="font-bold text-gray-900 dark:text-white px-6 py-4">
                <div class="flex items-center gap-3">
                  <svg class="w-5 h-5 text-[<?= $color['warna_primary'] ?>] expand-icon transition-transform" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                  <span class="module-name hover:text-[<?= $color['warna_primary'] ?>] transition-colors"><?= lang('Admin/HakAkses.mod_config') ?></span>
                </div>
              </td>
              <td class="text-center px-4 py-4"><span class="toggle-switch w-11 h-6 bg-gray-200 dark:bg-slate-600 rounded-full relative inline-block cursor-pointer transition-colors [&.active]:bg-[<?= $color['warna_primary'] ?>]" data-action="view" onclick="event.stopPropagation(); togglePermission(this)"></span></td>
              <td class="text-center px-4 py-4"><span class="toggle-switch w-11 h-6 bg-gray-200 dark:bg-slate-600 rounded-full relative inline-block cursor-pointer transition-colors [&.active]:bg-[<?= $color['warna_primary'] ?>]" data-action="create" onclick="event.stopPropagation(); togglePermission(this)"></span></td>
              <td class="text-center px-4 py-4"><span class="toggle-switch w-11 h-6 bg-gray-200 dark:bg-slate-600 rounded-full relative inline-block cursor-pointer transition-colors [&.active]:bg-[<?= $color['warna_primary'] ?>]" data-action="update" onclick="event.stopPropagation(); togglePermission(this)"></span></td>
              <td class="text-center px-4 py-4"><span class="toggle-switch w-11 h-6 bg-gray-200 dark:bg-slate-600 rounded-full relative inline-block cursor-pointer transition-colors [&.active]:bg-[<?= $color['warna_primary'] ?>]" data-action="delete" onclick="event.stopPropagation(); togglePermission(this)"></span></td>
              <td class="text-center px-4 py-4"><span class="toggle-switch w-11 h-6 bg-gray-200 dark:bg-slate-600 rounded-full relative inline-block cursor-pointer transition-colors [&.active]:bg-[<?= $color['warna_primary'] ?>]" data-action="special" onclick="event.stopPropagation(); togglePermission(this)"></span></td>
            </tr>
            <tr id="expand-konfigurasi" class="bg-gray-50/50 dark:bg-slate-900/30 transition-colors hidden">
              <td colspan="6" class="p-0">
                <div class="expandable-section px-6 py-4 pl-14">
                  <div class="text-sm text-gray-600 dark:text-slate-400 space-y-2 font-medium">
                    <p class="font-bold text-gray-800 dark:text-slate-200 mb-2 border-b border-gray-200 dark:border-slate-700 pb-1 inline-block"><?= lang('Admin/HakAkses.sub_config') ?></p>
                    <p class="flex items-center gap-2"><span class="text-[<?= $color['warna_primary'] ?>]">•</span> <?= lang('Admin/HakAkses.cfg_year') ?></p>
                    <p class="flex items-center gap-2"><span class="text-[<?= $color['warna_primary'] ?>]">•</span> <?= lang('Admin/HakAkses.cfg_curriculum') ?></p>
                    <p class="flex items-center gap-2"><span class="text-[<?= $color['warna_primary'] ?>]">•</span> <?= lang('Admin/HakAkses.cfg_schedule') ?></p>
                    <p class="flex items-center gap-2"><span class="text-[<?= $color['warna_primary'] ?>]">•</span> <?= lang('Admin/HakAkses.cfg_tahfidz') ?></p>
                    <p class="flex items-center gap-2"><span class="text-[<?= $color['warna_primary'] ?>]">•</span> <?= lang('Admin/HakAkses.cfg_scoring') ?></p>
                  </div>
                </div>
              </td>
            </tr>

            <tr class="module-row hover:bg-gray-50 dark:hover:bg-slate-700/50 cursor-pointer transition-colors border-t border-gray-100 dark:border-slate-700" data-module="penilaian" onclick="toggleExpand('penilaian')">
              <td class="font-bold text-gray-900 dark:text-white px-6 py-4">
                <div class="flex items-center gap-3">
                  <svg class="w-5 h-5 text-[<?= $color['warna_primary'] ?>] expand-icon transition-transform" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                  <span class="module-name hover:text-[<?= $color['warna_primary'] ?>] transition-colors"><?= lang('Admin/HakAkses.mod_grading') ?></span>
                </div>
              </td>
              <td class="text-center px-4 py-4"><span class="toggle-switch w-11 h-6 bg-gray-200 dark:bg-slate-600 rounded-full relative inline-block cursor-pointer transition-colors [&.active]:bg-[<?= $color['warna_primary'] ?>]" data-action="view" onclick="event.stopPropagation(); togglePermission(this)"></span></td>
              <td class="text-center px-4 py-4"><span class="toggle-switch w-11 h-6 bg-gray-200 dark:bg-slate-600 rounded-full relative inline-block cursor-pointer transition-colors [&.active]:bg-[<?= $color['warna_primary'] ?>]" data-action="create" onclick="event.stopPropagation(); togglePermission(this)"></span></td>
              <td class="text-center px-4 py-4"><span class="toggle-switch w-11 h-6 bg-gray-200 dark:bg-slate-600 rounded-full relative inline-block cursor-pointer transition-colors [&.active]:bg-[<?= $color['warna_primary'] ?>]" data-action="update" onclick="event.stopPropagation(); togglePermission(this)"></span></td>
              <td class="text-center px-4 py-4"><span class="toggle-switch w-11 h-6 bg-gray-200 dark:bg-slate-600 rounded-full relative inline-block cursor-pointer transition-colors [&.active]:bg-[<?= $color['warna_primary'] ?>]" data-action="delete" onclick="event.stopPropagation(); togglePermission(this)"></span></td>
              <td class="text-center px-4 py-4">
                <div class="tooltip inline-block relative cursor-help">
                  <span class="toggle-switch w-11 h-6 bg-gray-200 dark:bg-slate-600 rounded-full relative inline-block cursor-pointer transition-colors [&.active]:bg-[<?= $color['warna_primary'] ?>]" data-action="special" onclick="event.stopPropagation(); togglePermission(this)"></span> 
                  <span class="tooltiptext invisible opacity-0 absolute z-50 bottom-[120%] left-1/2 transform -translate-x-1/2 bg-gray-900 text-white text-[10px] px-2 py-1 rounded whitespace-nowrap transition-opacity"><?= lang('Admin/HakAkses.tt_special_grading') ?></span>
                </div>
              </td>
            </tr>
            <tr id="expand-penilaian" class="bg-gray-50/50 dark:bg-slate-900/30 transition-colors hidden">
              <td colspan="6" class="p-0">
                <div class="expandable-section px-6 py-4 pl-14">
                  <div class="text-sm text-gray-600 dark:text-slate-400 space-y-2 font-medium">
                    <p class="font-bold text-gray-800 dark:text-slate-200 mb-2 border-b border-gray-200 dark:border-slate-700 pb-1 inline-block"><?= lang('Admin/HakAkses.sub_grading') ?></p>
                    <p class="flex items-center gap-2"><span class="text-[<?= $color['warna_primary'] ?>]">•</span> <?= lang('Admin/HakAkses.grd_input') ?></p>
                    <p class="flex items-center gap-2"><span class="text-[<?= $color['warna_primary'] ?>]">•</span> <strong class="text-gray-800 dark:text-slate-200"><?= lang('Admin/HakAkses.grd_validate') ?></strong></p>
                    <p class="flex items-center gap-2"><span class="text-[<?= $color['warna_primary'] ?>]">•</span> <strong class="text-gray-800 dark:text-slate-200"><?= lang('Admin/HakAkses.grd_lock') ?></strong></p>
                    <p class="flex items-center gap-2"><span class="text-[<?= $color['warna_primary'] ?>]">•</span> <?= lang('Admin/HakAkses.grd_monitor') ?></p>
                  </div>
                </div>
              </td>
            </tr>

            <tr class="module-row hover:bg-gray-50 dark:hover:bg-slate-700/50 cursor-pointer transition-colors border-t border-gray-100 dark:border-slate-700" data-module="rapor" onclick="toggleExpand('rapor')">
              <td class="font-bold text-gray-900 dark:text-white px-6 py-4">
                <div class="flex items-center gap-3">
                  <svg class="w-5 h-5 text-[<?= $color['warna_primary'] ?>] expand-icon transition-transform" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                  <span class="module-name hover:text-[<?= $color['warna_primary'] ?>] transition-colors"><?= lang('Admin/HakAkses.mod_report') ?></span>
                </div>
              </td>
              <td class="text-center px-4 py-4"><span class="toggle-switch w-11 h-6 bg-gray-200 dark:bg-slate-600 rounded-full relative inline-block cursor-pointer transition-colors [&.active]:bg-[<?= $color['warna_primary'] ?>]" data-action="view" onclick="event.stopPropagation(); togglePermission(this)"></span></td>
              <td class="text-center px-4 py-4"><span class="toggle-switch w-11 h-6 bg-gray-200 dark:bg-slate-600 rounded-full relative inline-block cursor-pointer transition-colors [&.active]:bg-[<?= $color['warna_primary'] ?>]" data-action="create" onclick="event.stopPropagation(); togglePermission(this)"></span></td>
              <td class="text-center px-4 py-4"><span class="toggle-switch w-11 h-6 bg-gray-200 dark:bg-slate-600 rounded-full relative inline-block cursor-pointer transition-colors [&.active]:bg-[<?= $color['warna_primary'] ?>]" data-action="update" onclick="event.stopPropagation(); togglePermission(this)"></span></td>
              <td class="text-center px-4 py-4"><span class="toggle-switch w-11 h-6 bg-gray-200 dark:bg-slate-600 rounded-full relative inline-block cursor-pointer transition-colors [&.active]:bg-[<?= $color['warna_primary'] ?>]" data-action="delete" onclick="event.stopPropagation(); togglePermission(this)"></span></td>
              <td class="text-center px-4 py-4">
                <div class="tooltip inline-block relative cursor-help">
                  <span class="toggle-switch w-11 h-6 bg-gray-200 dark:bg-slate-600 rounded-full relative inline-block cursor-pointer transition-colors [&.active]:bg-[<?= $color['warna_primary'] ?>]" data-action="special" onclick="event.stopPropagation(); togglePermission(this)"></span> 
                  <span class="tooltiptext invisible opacity-0 absolute z-50 bottom-[120%] left-1/2 transform -translate-x-1/2 bg-gray-900 text-white text-[10px] px-2 py-1 rounded whitespace-nowrap transition-opacity"><?= lang('Admin/HakAkses.tt_special_report') ?></span>
                </div>
              </td>
            </tr>
            <tr id="expand-rapor" class="bg-gray-50/50 dark:bg-slate-900/30 transition-colors hidden">
              <td colspan="6" class="p-0">
                <div class="expandable-section px-6 py-4 pl-14">
                  <div class="text-sm text-gray-600 dark:text-slate-400 space-y-2 font-medium">
                    <p class="font-bold text-gray-800 dark:text-slate-200 mb-2 border-b border-gray-200 dark:border-slate-700 pb-1 inline-block"><?= lang('Admin/HakAkses.sub_report') ?></p>
                    <p class="flex items-center gap-2"><span class="text-[<?= $color['warna_primary'] ?>]">•</span> <?= lang('Admin/HakAkses.rep_preview') ?></p>
                    <p class="flex items-center gap-2"><span class="text-[<?= $color['warna_primary'] ?>]">•</span> <strong class="text-gray-800 dark:text-slate-200"><?= lang('Admin/HakAkses.rep_print') ?></strong></p>
                    <p class="flex items-center gap-2"><span class="text-[<?= $color['warna_primary'] ?>]">•</span> <strong class="text-gray-800 dark:text-slate-200"><?= lang('Admin/HakAkses.rep_download') ?></strong></p>
                    <p class="flex items-center gap-2"><span class="text-[<?= $color['warna_primary'] ?>]">•</span> <?= lang('Admin/HakAkses.rep_ledger') ?></p>
                  </div>
                </div>
              </td>
            </tr>

            <tr class="module-row hover:bg-gray-50 dark:hover:bg-slate-700/50 cursor-pointer transition-colors border-t border-gray-100 dark:border-slate-700" data-module="sistem" onclick="toggleExpand('sistem')">
              <td class="font-bold text-gray-900 dark:text-white px-6 py-4">
                <div class="flex items-center gap-3">
                  <svg class="w-5 h-5 text-[<?= $color['warna_primary'] ?>] expand-icon transition-transform" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                  <span class="module-name hover:text-[<?= $color['warna_primary'] ?>] transition-colors"><?= lang('Admin/HakAkses.mod_system') ?></span>
                </div>
              </td>
              <td class="text-center px-4 py-4"><span class="toggle-switch w-11 h-6 bg-gray-200 dark:bg-slate-600 rounded-full relative inline-block cursor-pointer transition-colors [&.active]:bg-[<?= $color['warna_primary'] ?>]" data-action="view" onclick="event.stopPropagation(); togglePermission(this)"></span></td>
              <td class="text-center px-4 py-4"><span class="toggle-switch w-11 h-6 bg-gray-200 dark:bg-slate-600 rounded-full relative inline-block cursor-pointer transition-colors [&.active]:bg-[<?= $color['warna_primary'] ?>]" data-action="create" onclick="event.stopPropagation(); togglePermission(this)"></span></td>
              <td class="text-center px-4 py-4"><span class="toggle-switch w-11 h-6 bg-gray-200 dark:bg-slate-600 rounded-full relative inline-block cursor-pointer transition-colors [&.active]:bg-[<?= $color['warna_primary'] ?>]" data-action="update" onclick="event.stopPropagation(); togglePermission(this)"></span></td>
              <td class="text-center px-4 py-4"><span class="toggle-switch w-11 h-6 bg-gray-200 dark:bg-slate-600 rounded-full relative inline-block cursor-pointer transition-colors [&.active]:bg-[<?= $color['warna_primary'] ?>]" data-action="delete" onclick="event.stopPropagation(); togglePermission(this)"></span></td>
              <td class="text-center px-4 py-4">
                <div class="tooltip inline-block relative cursor-help">
                  <span class="toggle-switch w-11 h-6 bg-gray-200 dark:bg-slate-600 rounded-full relative inline-block cursor-pointer transition-colors [&.active]:bg-[<?= $color['warna_primary'] ?>]" data-action="special" onclick="event.stopPropagation(); togglePermission(this)"></span> 
                  <span class="tooltiptext invisible opacity-0 absolute z-50 bottom-[120%] left-1/2 transform -translate-x-1/2 bg-gray-900 text-white text-[10px] px-2 py-1 rounded whitespace-nowrap transition-opacity"><?= lang('Admin/HakAkses.tt_special_system') ?></span>
                </div>
              </td>
            </tr>
            <tr id="expand-sistem" class="bg-gray-50/50 dark:bg-slate-900/30 transition-colors hidden">
              <td colspan="6" class="p-0">
                <div class="expandable-section px-6 py-4 pl-14">
                  <div class="text-sm text-gray-600 dark:text-slate-400 space-y-2 font-medium">
                    <p class="font-bold text-gray-800 dark:text-slate-200 mb-2 border-b border-gray-200 dark:border-slate-700 pb-1 inline-block"><?= lang('Admin/HakAkses.sub_system') ?></p>
                    <p class="flex items-center gap-2"><span class="text-[<?= $color['warna_primary'] ?>]">•</span> <?= lang('Admin/HakAkses.sys_profile') ?></p>
                    <p class="flex items-center gap-2"><span class="text-[<?= $color['warna_primary'] ?>]">•</span> <strong class="text-gray-800 dark:text-slate-200"><?= lang('Admin/HakAkses.sys_access') ?></strong></p>
                    <p class="flex items-center gap-2"><span class="text-[<?= $color['warna_primary'] ?>]">•</span> <strong class="text-gray-800 dark:text-slate-200"><?= lang('Admin/HakAkses.sys_backup') ?></strong></p>
                  </div>
                </div>
              </td>
            </tr>

          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<div class="mb-6">
  <div class="bg-white dark:bg-slate-800 rounded-2xl border border-gray-200 dark:border-slate-700 p-5 shadow-sm transition-colors">
    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2 flex items-center gap-2 transition-colors">
      <svg class="w-5 h-5 text-[<?= $color['warna_primary'] ?>]" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" /></svg> 
      <?= lang('Admin/HakAkses.preset_title') ?>
    </h3>
    <p class="text-sm text-gray-600 dark:text-slate-400 mb-4 font-medium transition-colors"><?= lang('Admin/HakAkses.preset_desc') ?></p>
    
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
      
      <div class="preset-card p-4 rounded-xl border border-gray-200 dark:border-slate-600 bg-white dark:bg-slate-700/50 hover:border-[<?= $color['warna_primary'] ?>] hover:bg-gray-50 dark:hover:bg-slate-700 transition-colors shadow-sm group">
        <div class="flex items-center gap-3 mb-4">
          <div class="w-10 h-10 rounded-lg bg-purple-100 dark:bg-purple-900/30 flex items-center justify-center flex-shrink-0 transition-colors">
            <svg class="w-5 h-5 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
          </div>
          <div class="min-w-0"> 
            <h4 class="font-bold text-gray-900 dark:text-white text-sm truncate transition-colors"><?= lang('Admin/HakAkses.preset_teacher') ?></h4>
            <p class="text-[11px] font-medium text-gray-500 dark:text-slate-400 truncate transition-colors"><?= lang('Admin/HakAkses.preset_tch_desc') ?></p>
          </div>
        </div>
        <div class="text-xs font-medium text-gray-600 dark:text-slate-300 space-y-1.5 mb-4 transition-colors">
          <p class="flex items-center gap-2"><span class="text-emerald-500 font-bold">✓</span> <?= lang('Admin/HakAkses.view_grading') ?></p>
          <p class="flex items-center gap-2"><span class="text-emerald-500 font-bold">✓</span> <?= lang('Admin/HakAkses.create_update') ?></p>
          <p class="flex items-center gap-2"><span class="text-red-500 font-bold">✗</span> <?= lang('Admin/HakAkses.del_lock') ?></p>
        </div>
        <button onclick="applyPreset('guru')" class="w-full btn-secondary bg-white dark:bg-slate-800 border-gray-300 dark:border-slate-600 text-gray-700 dark:text-slate-300 hover:bg-gray-50 dark:hover:bg-slate-700 text-sm py-2 rounded-lg font-bold flex items-center justify-center gap-1.5 transition-colors outline-none group-hover:border-[<?= $color['warna_primary'] ?>] group-hover:text-[<?= $color['warna_primary'] ?>]">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg> 
          <?= lang('Admin/HakAkses.btn_apply') ?>
        </button>
      </div>

      <div class="preset-card p-4 rounded-xl border border-gray-200 dark:border-slate-600 bg-white dark:bg-slate-700/50 hover:border-[<?= $color['warna_primary'] ?>] hover:bg-gray-50 dark:hover:bg-slate-700 transition-colors shadow-sm group">
        <div class="flex items-center gap-3 mb-4">
          <div class="w-10 h-10 rounded-lg bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center flex-shrink-0 transition-colors">
            <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
          </div>
          <div class="min-w-0">
            <h4 class="font-bold text-gray-900 dark:text-white text-sm truncate transition-colors"><?= lang('Admin/HakAkses.preset_hr') ?></h4>
            <p class="text-[11px] font-medium text-gray-500 dark:text-slate-400 truncate transition-colors"><?= lang('Admin/HakAkses.preset_hr_desc') ?></p>
          </div>
        </div>
        <div class="text-xs font-medium text-gray-600 dark:text-slate-300 space-y-1.5 mb-4 transition-colors">
          <p class="flex items-center gap-2"><span class="text-emerald-500 font-bold">✓</span> <?= lang('Admin/HakAkses.view_all_class') ?></p>
          <p class="flex items-center gap-2"><span class="text-emerald-500 font-bold">✓</span> <?= lang('Admin/HakAkses.validate_lock') ?></p>
          <p class="flex items-center gap-2"><span class="text-emerald-500 font-bold">✓</span> <?= lang('Admin/HakAkses.print_report') ?></p>
        </div>
        <button onclick="applyPreset('walikelas')" class="w-full btn-secondary bg-white dark:bg-slate-800 border-gray-300 dark:border-slate-600 text-gray-700 dark:text-slate-300 hover:bg-gray-50 dark:hover:bg-slate-700 text-sm py-2 rounded-lg font-bold flex items-center justify-center gap-1.5 transition-colors outline-none group-hover:border-[<?= $color['warna_primary'] ?>] group-hover:text-[<?= $color['warna_primary'] ?>]">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg> 
          <?= lang('Admin/HakAkses.btn_apply') ?>
        </button>
      </div>

      <div class="preset-card p-4 rounded-xl border border-[<?= $color['warna_primary'] ?>] dark:border-[<?= $color['warna_primary'] ?>]/70 bg-emerald-50/50 dark:bg-slate-700/80 transition-colors shadow-sm group relative overflow-hidden">
        <div class="absolute top-0 right-0 bg-[<?= $color['warna_primary'] ?>] text-white text-[9px] font-black uppercase tracking-wider py-1 px-2 rounded-bl-lg shadow-sm"><?= lang('Admin/HakAkses.default_badge') ?></div>
        <div class="flex items-center gap-3 mb-4 mt-1">
          <div class="w-10 h-10 rounded-lg bg-[<?= $color['warna_primary'] ?>] flex items-center justify-center flex-shrink-0 shadow-sm transition-colors group-hover:scale-105">
            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg>
          </div>
          <div class="min-w-0">
            <h4 class="font-bold text-gray-900 dark:text-white text-sm truncate transition-colors"><?= lang('Admin/HakAkses.preset_admin') ?></h4>
            <p class="text-[11px] font-medium text-gray-500 dark:text-slate-400 truncate transition-colors"><?= lang('Admin/HakAkses.preset_adm_desc') ?></p>
          </div>
        </div>
        <div class="text-xs font-medium text-gray-600 dark:text-slate-300 space-y-1.5 mb-4 transition-colors">
          <p class="flex items-center gap-2"><span class="text-emerald-500 font-bold">✓</span> <?= lang('Admin/HakAkses.full_crud') ?></p>
          <p class="flex items-center gap-2"><span class="text-emerald-500 font-bold">✓</span> <?= lang('Admin/HakAkses.all_special') ?></p>
          <p class="flex items-center gap-2"><span class="text-emerald-500 font-bold">✓</span> <?= lang('Admin/HakAkses.sys_config') ?></p>
        </div>
        <button onclick="applyPreset('admin')" class="w-full py-2 bg-[<?= $color['warna_primary'] ?>] text-white hover:bg-[<?= $color['warna_primary'] ?>]/90 text-sm rounded-lg font-bold flex items-center justify-center gap-1.5 transition-colors outline-none shadow-sm">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg> 
          <?= lang('Admin/HakAkses.btn_apply') ?>
        </button>
      </div>

      <div class="preset-card p-4 rounded-xl border border-gray-200 dark:border-slate-600 bg-white dark:bg-slate-700/50 hover:border-[<?= $color['warna_primary'] ?>] hover:bg-gray-50 dark:hover:bg-slate-700 transition-colors shadow-sm group">
        <div class="flex items-center gap-3 mb-4">
          <div class="w-10 h-10 rounded-lg bg-amber-100 dark:bg-amber-900/30 flex items-center justify-center flex-shrink-0 transition-colors">
            <svg class="w-5 h-5 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /> <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
          </div>
          <div class="min-w-0">
            <h4 class="font-bold text-gray-900 dark:text-white text-sm truncate transition-colors"><?= lang('Admin/HakAkses.preset_head') ?></h4>
            <p class="text-[11px] font-medium text-gray-500 dark:text-slate-400 truncate transition-colors"><?= lang('Admin/HakAkses.preset_hd_desc') ?></p>
          </div>
        </div>
        <div class="text-xs font-medium text-gray-600 dark:text-slate-300 space-y-1.5 mb-4 transition-colors">
          <p class="flex items-center gap-2"><span class="text-emerald-500 font-bold">✓</span> <?= lang('Admin/HakAkses.view_all_mod') ?></p>
          <p class="flex items-center gap-2"><span class="text-emerald-500 font-bold">✓</span> <?= lang('Admin/HakAkses.dash_report') ?></p>
          <p class="flex items-center gap-2"><span class="text-red-500 font-bold">✗</span> <?= lang('Admin/HakAkses.no_edit_del') ?></p>
        </div>
        <button onclick="applyPreset('kepsek')" class="w-full btn-secondary bg-white dark:bg-slate-800 border-gray-300 dark:border-slate-600 text-gray-700 dark:text-slate-300 hover:bg-gray-50 dark:hover:bg-slate-700 text-sm py-2 rounded-lg font-bold flex items-center justify-center gap-1.5 transition-colors outline-none group-hover:border-[<?= $color['warna_primary'] ?>] group-hover:text-[<?= $color['warna_primary'] ?>]">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg> 
          <?= lang('Admin/HakAkses.btn_apply') ?>
        </button>
      </div>

    </div>
  </div>
</div>

<div class="mb-6">
  <div class="bg-white dark:bg-slate-800 rounded-2xl border border-gray-200 dark:border-slate-700 shadow-sm overflow-hidden transition-colors">
    <div class="p-5 border-b border-gray-200 dark:border-slate-700 bg-gray-50/50 dark:bg-slate-900/30 transition-colors">
      <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-1 flex items-center gap-2 transition-colors">
        <svg class="w-5 h-5 text-[<?= $color['warna_primary'] ?>]" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg> 
        <?= lang('Admin/HakAkses.audit_title') ?>
      </h3>
      <p class="text-sm font-medium text-gray-600 dark:text-slate-400 ml-7 transition-colors"><?= lang('Admin/HakAkses.audit_desc') ?></p>
    </div>
    <div class="overflow-x-auto custom-scrollbar">
      <table class="audit-table w-full text-left border-collapse min-w-max">
        <thead class="bg-gray-50 dark:bg-slate-900/50 border-b border-gray-100 dark:border-slate-700 text-xs font-black text-gray-500 dark:text-slate-400 uppercase tracking-widest transition-colors">
          <tr>
            <th class="px-6 py-4"><?= lang('Admin/HakAkses.th_time') ?></th>
            <th class="px-6 py-4"><?= lang('Admin/HakAkses.th_role_audit') ?></th>
            <th class="px-6 py-4"><?= lang('Admin/HakAkses.th_action_audit') ?></th>
            <th class="px-6 py-4"><?= lang('Admin/HakAkses.th_details') ?></th>
            <th class="px-6 py-4"><?= lang('Admin/HakAkses.th_by') ?></th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-100 dark:divide-slate-700/50 transition-colors">
          <?php if(empty($auditLogs)): ?>
            <tr>
              <td colspan="5" class="text-center py-10 text-gray-500 dark:text-slate-500 font-medium"><?= lang('Admin/HakAkses.no_audit') ?></td>
            </tr>
          <?php else: ?>
            <?php foreach($auditLogs as $log): ?>
              <tr class="hover:bg-gray-50 dark:hover:bg-slate-700/30 transition-colors">
                <td class="px-6 py-4 font-bold text-gray-900 dark:text-white transition-colors">
                  <?= date('d M Y', strtotime($log['created_at'])) ?><br>
                  <span class="text-[11px] font-medium text-gray-500 dark:text-slate-400 tracking-wider"><?= date('H:i', strtotime($log['created_at'])) ?> WIB</span>
                </td>
                <td class="px-6 py-4">
                    <span class="inline-flex px-2.5 py-1 rounded-lg text-[10px] font-black uppercase tracking-wider bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400 border border-blue-200 dark:border-blue-800/50 shadow-sm transition-colors"><?= lang('Admin/HakAkses.system_label') ?></span>
                </td>
                <td class="px-6 py-4">
                  <?php if($log['action'] == 'UPDATE_PERMISSION'): ?>
                    <span class="inline-flex px-2.5 py-1 rounded-lg text-[10px] font-black uppercase tracking-wider bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800/50 shadow-sm transition-colors">Update Permission</span>
                  <?php else: ?>
                    <span class="inline-flex px-2.5 py-1 rounded-lg text-[10px] font-black uppercase tracking-wider bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-400 border border-amber-200 dark:border-amber-800/50 shadow-sm transition-colors"><?= esc($log['action']) ?></span>
                  <?php endif; ?>
                </td>
                <td class="px-6 py-4 text-sm font-medium text-gray-700 dark:text-slate-300 leading-relaxed transition-colors"><?= esc($log['description']) ?></td>
                <td class="px-6 py-4 font-bold text-gray-900 dark:text-white transition-colors">
                  <?= esc($log['username'] ?? lang('Admin/HakAkses.unknown_user')) ?><br>
                  <span class="text-[11px] font-medium font-mono text-gray-500 dark:text-slate-400 tracking-wider">IP: <?= esc($log['ip_address']) ?></span>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
    <div class="p-4 border-t border-gray-200 dark:border-slate-700 bg-gray-50/50 dark:bg-slate-900/30 transition-colors">
      <div class="flex items-center justify-between">
        <p class="text-sm font-medium text-gray-600 dark:text-slate-400"><?= lang('Admin/HakAkses.showing_audit') ?> <?= count($auditLogs) ?> <?= lang('Admin/HakAkses.from_audit') ?> <?= $totalLogs ?? 0 ?> <?= lang('Admin/HakAkses.last_changes') ?></p>
        <button class="text-sm text-[<?= $color['warna_primary'] ?>] font-bold flex items-center gap-1 hover:underline outline-none"> 
            <?= lang('Admin/HakAkses.view_all') ?> 
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7-7" /></svg>
        </button>
      </div>
    </div>
  </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('modals') ?>
<div id="saveModal" class="modal hidden fixed inset-0 z-[99999] flex items-center justify-center p-4 bg-gray-900/60 backdrop-blur-sm transition-opacity">
  <div class="modal-content relative w-full max-w-md bg-white dark:bg-slate-800 rounded-3xl shadow-2xl p-6 md:p-8 transform transition-all border border-transparent dark:border-slate-700 scale-100 md:pl-64">
    <div class="text-center mb-6">
      <div class="w-20 h-20 rounded-full bg-amber-50 dark:bg-amber-900/30 border-4 border-amber-100 dark:border-amber-800/50 flex items-center justify-center mx-auto mb-5 shadow-sm transition-colors">
        <svg class="w-10 h-10 text-amber-500 dark:text-amber-400" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
      </div>
      <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-2 transition-colors"><?= lang('Admin/HakAkses.save_modal_title') ?></h3>
      <p class="text-sm font-medium text-gray-600 dark:text-slate-400 mb-5 leading-relaxed transition-colors"><?= lang('Admin/HakAkses.save_modal_desc') ?></p>
      
      <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800/50 rounded-xl p-4 mb-5 text-left shadow-sm transition-colors">
        <p class="text-sm font-medium text-blue-800 dark:text-blue-300 leading-relaxed">
          <svg class="w-5 h-5 inline mr-1.5 align-text-bottom" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg> 
          <?= lang('Admin/HakAkses.save_modal_note') ?>
        </p>
      </div>
      
      <label class="flex items-start gap-3 bg-gray-50 dark:bg-slate-700/50 border border-gray-200 dark:border-slate-600 p-4 rounded-xl cursor-pointer hover:bg-gray-100 dark:hover:bg-slate-700 transition-colors mb-2 group"> 
        <input type="checkbox" id="confirmCheck" class="mt-0.5 w-5 h-5 rounded border-gray-300 dark:border-slate-500 text-emerald-600 focus:ring-emerald-500 cursor-pointer outline-none transition-colors"> 
        <span class="text-sm font-bold text-gray-700 dark:text-slate-300 text-left group-hover:text-gray-900 dark:group-hover:text-white transition-colors"> <?= lang('Admin/HakAkses.save_modal_check') ?> </span> 
      </label>
    </div>
    <div class="flex gap-3">
      <button onclick="closeModal()" class="flex-1 px-5 py-3.5 bg-white dark:bg-slate-700 border-2 border-gray-300 dark:border-slate-600 text-gray-700 dark:text-slate-300 font-bold rounded-xl hover:bg-gray-50 dark:hover:bg-slate-600 transition-colors shadow-sm outline-none flex items-center justify-center gap-2">
        <?= lang('Admin/HakAkses.btn_cancel') ?> 
      </button> 
      <button onclick="confirmSave()" class="flex-1 px-5 py-3.5 bg-[<?= $color['warna_primary'] ?>] hover:bg-[<?= $color['warna_primary'] ?>]/90 text-white font-bold rounded-xl transition-transform transform hover:-translate-y-0.5 shadow-lg flex items-center justify-center gap-2 outline-none" style="box-shadow: 0 10px 15px -3px <?= $color['warna_primary'] ?>40;">
        <?= lang('Admin/HakAkses.btn_save') ?> 
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
      
      window.LANG = {
          js_loading_matrix: "<?= lang('Admin/HakAkses.js_loading_matrix') ?: 'Memuat matriks akses untuk' ?>",
          js_success_load: "<?= lang('Admin/HakAkses.js_success_load') ?: 'Hak akses berhasil dimuat' ?>",
          js_err_load: "<?= lang('Admin/HakAkses.js_err_load') ?: 'Gagal memuat data hak akses.' ?>",
          js_preset_apply: "<?= lang('Admin/HakAkses.js_preset_apply') ?: 'Template berhasil diterapkan.' ?>",
          js_add_role_title: "<?= lang('Admin/HakAkses.js_add_role_title') ?: 'Tambah Role Baru' ?>",
          js_lbl_role_name: "<?= lang('Admin/HakAkses.js_lbl_role_name') ?: 'Nama Role' ?>",
          js_ph_role_name: "<?= lang('Admin/HakAkses.js_ph_role_name') ?: 'Contoh: Koordinator Akademik' ?>",
          js_lbl_role_desc: "<?= lang('Admin/HakAkses.js_lbl_role_desc') ?: 'Deskripsi' ?>",
          js_ph_role_desc: "<?= lang('Admin/HakAkses.js_ph_role_desc') ?: 'Contoh: Monitoring & Koordinasi' ?>",
          js_lbl_color: "<?= lang('Admin/HakAkses.js_lbl_color') ?: 'Pilih Warna Icon' ?>",
          js_lbl_status: "<?= lang('Admin/HakAkses.js_lbl_status') ?: 'Status Awal' ?>",
          status_active: "<?= lang('Admin/HakAkses.status_active') ?: 'Aktif' ?>",
          status_inactive: "<?= lang('Admin/HakAkses.status_inactive') ?: 'Nonaktif' ?>",
          js_role_note: "<?= lang('Admin/HakAkses.js_role_note') ?: 'Setelah membuat role, Anda dapat mengatur detail hak aksesnya pada panel <strong class=\"font-bold\">Matrix Hak Akses</strong>.' ?>",
          btn_cancel: "<?= lang('Admin/HakAkses.btn_cancel') ?: 'Batal' ?>",
          js_btn_create_role: "<?= lang('Admin/HakAkses.js_btn_create_role') ?: 'Buat Role' ?>",
          js_saving_role: "<?= lang('Admin/HakAkses.js_saving_role') ?: 'Sedang menyimpan role baru...' ?>",
          js_err_net_save: "<?= lang('Admin/HakAkses.js_err_net_save') ?: 'Terjadi kesalahan jaringan saat menyimpan role.' ?>",
          js_check_confirm: "<?= lang('Admin/HakAkses.js_check_confirm') ?: 'Harap centang konfirmasi terlebih dahulu.' ?>",
          js_select_role_1st: "<?= lang('Admin/HakAkses.js_select_role_1st') ?: 'Pilih role terlebih dahulu dari daftar di sebelah kiri.' ?>",
          js_saving_changes: "<?= lang('Admin/HakAkses.js_saving_changes') ?: 'Menyimpan perubahan...' ?>",
          js_err_sys_save: "<?= lang('Admin/HakAkses.js_err_sys_save') ?: 'Terjadi kesalahan sistem saat menyimpan data.' ?>",
          js_sys_notif: "<?= lang('Admin/HakAkses.js_sys_notif') ?: 'Notifikasi Sistem' ?>"
      };
  </script>
  <script src="<?= base_url('assets/js/Admin/hak-aksesk.js') ?>"></script>
<?= $this->endSection() ?>