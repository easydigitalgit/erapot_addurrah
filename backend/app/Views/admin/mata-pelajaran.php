<?= $this->extend('layout/main') ?>

<?= $this->section('title') ?>
  <?= lang('Admin/MataPelajaran.page_title') ?> - Rapor Digital SMPIT Ad Durrah
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
    :root { --warna-scroll: <?= $color['warna_primary'] ?>; }
</style>
  <link rel="stylesheet" href="<?= base_url('assets/css/Admin/mata-pelajaran.css') ?>">
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="w-full min-w-0">
    <div class="mb-6">
      <div class="flex items-center gap-2 text-sm text-gray-500 dark:text-slate-400 mb-2 transition-colors">
        <span><?= lang('Admin/MataPelajaran.breadcrumb') ?></span>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewbox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>
        <span class="text-[<?= $color['warna_primary'] ?>] font-medium"><?= lang('Admin/MataPelajaran.page_title') ?></span>
      </div>
      <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div class="min-w-0">
          <h1 id="page-title" class="text-xl md:text-3xl font-bold text-gray-800 dark:text-white truncate transition-colors"><?= lang('Admin/MataPelajaran.page_title') ?></h1>
          <p id="page-subtitle" class="text-sm md:text-base text-gray-600 dark:text-slate-400 mt-1 truncate transition-colors"><?= lang('Admin/MataPelajaran.page_desc') ?></p>
        </div>
        <div class="flex flex-wrap items-center gap-2 md:gap-3">
            <button onclick="showAddMapelModal()" class="px-4 py-2.5 bg-[<?= $color['warna_primary'] ?>] hover:bg-[<?= $color['warna_primary'] ?>]/90 text-white font-semibold rounded-xl transition-all shadow-lg shadow-[<?= $color['warna_primary'] ?>]/20 flex items-center gap-2 transform hover:-translate-y-0.5 outline-none">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewbox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
            </svg><span class="hidden sm:inline"><?= lang('Admin/MataPelajaran.btn_add_mapel') ?: 'Tambah Mapel' ?></span> </button> 
            
            <button onclick="showImportModal()" class="px-4 py-2.5 bg-white dark:bg-slate-800 border border-gray-300 dark:border-slate-600 text-gray-700 dark:text-slate-300 font-medium rounded-xl hover:bg-gray-50 dark:hover:bg-slate-700 transition-colors flex items-center gap-2 shadow-sm outline-none">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewbox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3-3m3-3v12" />
            </svg><span class="hidden sm:inline"><?= lang('Admin/MataPelajaran.btn_import') ?: 'Import Excel' ?></span> </button> 
            
            <button onclick="showGroupSettingModal()" class="px-4 py-2.5 bg-white dark:bg-slate-800 border border-gray-300 dark:border-slate-600 text-gray-700 dark:text-slate-300 font-medium rounded-xl hover:bg-gray-50 dark:hover:bg-slate-700 transition-colors flex items-center gap-2 shadow-sm outline-none">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewbox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg><span class="hidden lg:inline"><?= lang('Admin/MataPelajaran.btn_group_setting') ?: 'Statistik Kelompok' ?></span> </button>
        </div>
      </div>
    </div>
    
    <div class="bg-white dark:bg-slate-800 w-full min-w-0 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700 p-5 mb-6 overflow-x-auto transition-colors">
      <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 w-full">
        <div class="flex flex-wrap items-center gap-2">
            <button class="filter-chip active px-4 py-2 rounded-lg text-sm font-semibold border border-transparent transition-colors outline-none"><?= lang('Admin/MataPelajaran.filter_all') ?: 'Semua' ?></button> 
            <span class="text-gray-300 dark:text-slate-600 hidden lg:inline">|</span> 
            <button class="filter-chip px-4 py-2 rounded-lg text-sm font-semibold border border-gray-200 dark:border-slate-600 text-gray-600 dark:text-slate-300 hover:bg-gray-50 dark:hover:bg-slate-700 transition-colors outline-none"><?= lang('Admin/MataPelajaran.filter_general') ?: 'Umum' ?></button> 
            <button class="filter-chip px-4 py-2 rounded-lg text-sm font-semibold border border-gray-200 dark:border-slate-600 text-gray-600 dark:text-slate-300 hover:bg-gray-50 dark:hover:bg-slate-700 transition-colors outline-none"><?= lang('Admin/MataPelajaran.filter_islamic') ?: 'Keislaman' ?></button> 
            <button class="filter-chip px-4 py-2 rounded-lg text-sm font-semibold border border-gray-200 dark:border-slate-600 text-gray-600 dark:text-slate-300 hover:bg-gray-50 dark:hover:bg-slate-700 transition-colors outline-none"><?= lang('Admin/MataPelajaran.filter_local') ?: 'Lokal' ?></button>
        </div>
        <div class="flex items-center gap-2 w-full lg:w-auto relative">
            <input type="text" id="searchMapel" placeholder="<?= lang('Admin/MataPelajaran.search_ph') ?: 'Cari Mapel...' ?>" class="w-full lg:w-64 pl-10 pr-4 py-2 text-sm bg-white dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-xl text-gray-800 dark:text-white placeholder-gray-400 dark:placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[<?= $color['warna_primary'] ?>] transition-colors shadow-sm">
            <svg class="w-4 h-4 text-gray-400 dark:text-slate-400 absolute left-4 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" stroke="currentColor" viewbox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
        </div>
      </div>
    </div>
    
    <div class="bg-white dark:bg-slate-800 w-full min-w-0 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700 overflow-hidden transition-colors">
      <div class="overflow-x-auto w-full custom-scrollbar">
        <table class="w-full min-w-max text-left border-collapse">
          <thead class="bg-gray-50 dark:bg-slate-900/50 border-b border-gray-100 dark:border-slate-700 transition-colors">
            <tr>
              <th class="px-6 py-4 text-left font-bold text-gray-500 dark:text-slate-400 uppercase tracking-wider text-xs"><?= lang('Admin/MataPelajaran.th_code') ?></th>
              <th class="px-6 py-4 text-left font-bold text-gray-500 dark:text-slate-400 uppercase tracking-wider text-xs"><?= lang('Admin/MataPelajaran.th_name') ?></th>
              <th class="px-6 py-4 text-center font-bold text-gray-500 dark:text-slate-400 uppercase tracking-wider text-xs"><?= lang('Admin/MataPelajaran.th_group') ?></th>
              <th class="px-6 py-4 text-center font-bold text-gray-500 dark:text-slate-400 uppercase tracking-wider text-xs"><?= lang('Admin/MataPelajaran.th_curriculum') ?></th>
              <th class="px-6 py-4 text-center font-bold text-gray-500 dark:text-slate-400 uppercase tracking-wider text-xs"><?= lang('Admin/MataPelajaran.th_hours') ?></th>
              <th class="px-6 py-4 text-center font-bold text-gray-500 dark:text-slate-400 uppercase tracking-wider text-xs"><?= lang('Admin/MataPelajaran.th_no_urut') ?></th>
              <th class="px-6 py-4 text-center font-bold text-gray-500 dark:text-slate-400 uppercase tracking-wider text-xs"><?= lang('Admin/MataPelajaran.th_status') ?></th>
              <th class="px-6 py-4 text-center font-bold text-gray-500 dark:text-slate-400 uppercase tracking-wider text-xs"><?= lang('Admin/MataPelajaran.th_action') ?></th>
            </tr>
          </thead>
          <tbody id="mapelTableBody" class="divide-y divide-gray-100 dark:divide-slate-700/50">
              </tbody>
        </table>
      </div>
    </div>
</div> 
<?= $this->endSection() ?>

<?= $this->section('modals') ?>

<div id="drawer-overlay" class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm z-[99998] hidden opacity-0 transition-opacity duration-300" onclick="closeDrawer()"></div>
<div id="detailDrawer" class="fixed top-0 right-0 h-full w-full sm:w-[400px] bg-white dark:bg-slate-800 shadow-2xl z-[99999] transform translate-x-full transition-transform duration-300 flex flex-col border-l border-gray-200 dark:border-slate-700">
  <div class="p-6 border-b border-gray-100 dark:border-slate-700 flex items-center justify-between bg-white dark:bg-slate-800 z-10 transition-colors">
    <h3 class="text-xl font-bold text-gray-800 dark:text-white"><?= lang('Admin/MataPelajaran.drawer_title') ?></h3>
    <button onclick="closeDrawer()" class="p-2 text-gray-400 hover:text-gray-600 dark:hover:text-slate-300 hover:bg-gray-100 dark:hover:bg-slate-700 rounded-lg transition-colors cursor-pointer outline-none">
      <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
    </button>
  </div>
  <div class="p-6 space-y-6 overflow-y-auto flex-1 custom-scrollbar">
    <div class="text-center pb-6 border-b border-gray-100 dark:border-slate-700 transition-colors">
      <div class="w-20 h-20 mx-auto rounded-2xl bg-gradient-to-br from-emerald-500 to-emerald-700 flex items-center justify-center text-white font-bold text-2xl mb-4 shadow-lg border border-transparent">
        <span id="drawerMapelCode">...</span>
      </div>
      <h4 id="drawerMapelName" class="text-xl font-bold text-gray-800 dark:text-white mb-1">...</h4>
      <p id="drawerMapelGroup" class="text-sm font-medium text-gray-500 dark:text-slate-400">...</p>
    </div>
    <div class="space-y-4">
      <div class="bg-gray-50 dark:bg-slate-900/50 p-4 rounded-xl border border-gray-100 dark:border-slate-700 transition-colors">
        <label class="text-[10px] font-bold text-gray-500 dark:text-slate-400 uppercase tracking-wider block mb-1"><?= lang('Admin/MataPelajaran.lbl_curriculum_id') ?></label>
        <p id="drawerMapelKurikulum" class="text-gray-800 dark:text-white font-medium">...</p>
      </div>
      <div class="bg-gray-50 dark:bg-slate-900/50 p-4 rounded-xl border border-gray-100 dark:border-slate-700 transition-colors">
        <label class="text-[10px] font-bold text-gray-500 dark:text-slate-400 uppercase tracking-wider block mb-1"><?= lang('Admin/MataPelajaran.lbl_learning_hours') ?></label>
        <p id="drawerMapelHours" class="text-gray-800 dark:text-white font-medium">...</p>
      </div>
    </div>
  </div>
</div>

<div id="addMapelModal" class="fixed inset-0 z-[99999] hidden flex items-center justify-center overflow-y-auto overflow-x-hidden p-4 md:p-0">
  <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" onclick="closeAddMapelModal()"></div>
  <div class="relative w-full max-w-2xl bg-white dark:bg-slate-800 rounded-2xl shadow-2xl flex flex-col transform transition-colors border border-gray-200 dark:border-slate-700">
    <div class="p-5 border-b border-gray-100 dark:border-slate-700 flex justify-between items-center bg-white dark:bg-slate-800 rounded-t-2xl transition-colors">
        <div>
            <h3 class="text-xl font-bold text-gray-800 dark:text-white"><?= lang('Admin/MataPelajaran.add_modal_title') ?></h3>
            <p class="text-sm text-gray-500 dark:text-slate-400 mt-0.5"><?= lang('Admin/MataPelajaran.add_modal_desc') ?></p>
        </div>
        <button onclick="closeAddMapelModal()" class="p-2 text-gray-400 hover:text-gray-600 dark:hover:text-slate-300 hover:bg-gray-100 dark:hover:bg-slate-700 rounded-lg transition-colors outline-none">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
    </div>
    <div class="p-6">
        <form id="addMapelForm" onsubmit="handleMapelSubmit(event)" class="space-y-5">
            <?= csrf_field() ?>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-2"><?= lang('Admin/MataPelajaran.lbl_mapel_code') ?> <span class="text-red-500">*</span></label>
                    <input type="text" name="mapel_code" required placeholder="<?= lang('Admin/MataPelajaran.ph_mapel_code') ?>" 
                           class="w-full px-4 py-2.5 bg-gray-50 dark:bg-slate-700 border border-gray-300 dark:border-slate-600 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-slate-400 text-sm rounded-xl focus:bg-white dark:focus:bg-slate-800 focus:ring-2 focus:ring-[<?= $color['warna_primary'] ?>]/50 focus:border-[<?= $color['warna_primary'] ?>] block transition-all outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-2"><?= lang('Admin/MataPelajaran.lbl_group') ?> <span class="text-red-500">*</span></label>
                    <select name="group" required class="w-full px-4 py-2.5 bg-gray-50 dark:bg-slate-700 border border-gray-300 dark:border-slate-600 text-gray-900 dark:text-white text-sm rounded-xl focus:bg-white dark:focus:bg-slate-800 focus:ring-2 focus:ring-[<?= $color['warna_primary'] ?>]/50 focus:border-[<?= $color['warna_primary'] ?>] block transition-all appearance-none cursor-pointer outline-none">
                        <option value="" disabled selected><?= lang('Admin/MataPelajaran.ph_select_group') ?></option>
                        <option value="Umum"><?= lang('Admin/MataPelajaran.grp_general') ?: 'Umum' ?></option>
                        <option value="Keislaman"><?= lang('Admin/MataPelajaran.grp_islamic') ?: 'Keislaman' ?></option>
                        <option value="Lokal"><?= lang('Admin/MataPelajaran.grp_local') ?: 'Muatan Lokal' ?></option>
                    </select>
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-2"><?= lang('Admin/MataPelajaran.lbl_mapel_name') ?> <span class="text-red-500">*</span></label>
                <input type="text" name="mapel_name" required placeholder="<?= lang('Admin/MataPelajaran.ph_mapel_name') ?>" 
                       class="w-full px-4 py-2.5 bg-gray-50 dark:bg-slate-700 border border-gray-300 dark:border-slate-600 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-slate-400 text-sm rounded-xl focus:bg-white dark:focus:bg-slate-800 focus:ring-2 focus:ring-[<?= $color['warna_primary'] ?>]/50 focus:border-[<?= $color['warna_primary'] ?>] block transition-all outline-none">
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-2"><?= lang('Admin/MataPelajaran.lbl_curriculum') ?> <span class="text-red-500">*</span></label>
                    <select name="curriculum" required class="w-full px-4 py-2.5 bg-gray-50 dark:bg-slate-700 border border-gray-300 dark:border-slate-600 text-gray-900 dark:text-white text-sm rounded-xl focus:bg-white dark:focus:bg-slate-800 focus:ring-2 focus:ring-[<?= $color['warna_primary'] ?>]/50 focus:border-[<?= $color['warna_primary'] ?>] block transition-all appearance-none cursor-pointer outline-none">
                        <option value="" disabled selected><?= lang('Admin/MataPelajaran.ph_select_curr') ?></option>
                        <option value="1">Kurikulum 2013</option>
                        <option value="2">Kurikulum Merdeka</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-2"><?= lang('Admin/MataPelajaran.lbl_hours_per_week') ?> <span class="text-red-500">*</span></label>
                    <input type="number" name="hours" required placeholder="0" min="1" 
                           class="w-full px-4 py-2.5 bg-gray-50 dark:bg-slate-700 border border-gray-300 dark:border-slate-600 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-slate-400 text-sm rounded-xl focus:bg-white dark:focus:bg-slate-800 focus:ring-2 focus:ring-[<?= $color['warna_primary'] ?>]/50 focus:border-[<?= $color['warna_primary'] ?>] block transition-all outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-2"><?= lang('Admin/MataPelajaran.lbl_no_urut') ?></label>
                    <input type="number" name="nomor_urut" placeholder="0" min="0" 
                           class="w-full px-4 py-2.5 bg-gray-50 dark:bg-slate-700 border border-gray-300 dark:border-slate-600 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-slate-400 text-sm rounded-xl focus:bg-white dark:focus:bg-slate-800 focus:ring-2 focus:ring-[<?= $color['warna_primary'] ?>]/50 focus:border-[<?= $color['warna_primary'] ?>] block transition-all outline-none">
                </div>
            </div>
            <div class="flex gap-3 pt-4 mt-2 border-t border-gray-100 dark:border-slate-700 transition-colors">
                <button type="button" onclick="closeAddMapelModal()" class="flex-1 px-5 py-3 bg-white dark:bg-slate-700 border border-gray-300 dark:border-slate-600 text-gray-700 dark:text-slate-300 font-semibold rounded-xl hover:bg-gray-50 dark:hover:bg-slate-600 transition-colors shadow-sm outline-none">
                    <?= lang('Admin/MataPelajaran.btn_cancel') ?>
                </button>
                <button type="submit" class="flex-1 px-5 py-3 text-white font-semibold rounded-xl shadow-lg transition-transform transform hover:-translate-y-0.5 outline-none" style="background-color: <?= $color['warna_primary'] ?>; box-shadow: 0 10px 15px -3px <?= $color['warna_primary'] ?>40;">
                    <?= lang('Admin/MataPelajaran.btn_save_data') ?>
                </button>
            </div>
        </form>
    </div>
  </div>
</div>

<div id="editMapelModal" class="fixed inset-0 z-[99999] hidden flex items-center justify-center overflow-y-auto overflow-x-hidden p-4 md:p-0">
  <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" onclick="closeEditMapelModal()"></div>
  <div class="relative w-full max-w-2xl bg-white dark:bg-slate-800 rounded-2xl shadow-2xl flex flex-col transform transition-colors border border-gray-200 dark:border-slate-700">
    <div class="p-5 border-b border-gray-100 dark:border-slate-700 flex justify-between items-center bg-white dark:bg-slate-800 rounded-t-2xl transition-colors">
        <div>
            <h3 class="text-xl font-bold text-gray-800 dark:text-white"><?= lang('Admin/MataPelajaran.edit_modal_title') ?></h3>
            <p class="text-sm text-gray-500 dark:text-slate-400 mt-0.5"><?= lang('Admin/MataPelajaran.edit_modal_desc') ?></p>
        </div>
        <button type="button" onclick="closeEditMapelModal()" class="p-2 text-gray-400 hover:text-gray-600 dark:hover:text-slate-300 hover:bg-gray-100 dark:hover:bg-slate-700 rounded-lg transition-colors outline-none">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
    </div>
    <div class="p-6">
        <form id="editMapelForm" onsubmit="handleEditMapelSubmit(event)" class="space-y-5">
            <?= csrf_field() ?>
            <input type="hidden" name="edit_mapel_id" id="edit_mapel_id">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-slate-300 mb-2"><?= lang('Admin/MataPelajaran.lbl_mapel_code') ?></label>
                    <input type="text" name="edit_mapel_code" id="edit_mapel_code" required 
                           class="w-full px-4 py-2.5 bg-gray-50 dark:bg-slate-700 border border-gray-300 dark:border-slate-600 text-gray-900 dark:text-white text-sm rounded-xl focus:bg-white dark:focus:bg-slate-800 focus:ring-2 focus:ring-[<?= $color['warna_primary'] ?>]/50 focus:border-[<?= $color['warna_primary'] ?>] block transition-all outline-none">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-slate-300 mb-2"><?= lang('Admin/MataPelajaran.lbl_group') ?></label>
                    <select name="edit_group" id="edit_group" required 
                            class="w-full px-4 py-2.5 bg-gray-50 dark:bg-slate-700 border border-gray-300 dark:border-slate-600 text-gray-900 dark:text-white text-sm rounded-xl focus:bg-white dark:focus:bg-slate-800 focus:ring-2 focus:ring-[<?= $color['warna_primary'] ?>]/50 focus:border-[<?= $color['warna_primary'] ?>] block transition-all appearance-none cursor-pointer outline-none">
                        <option value="Umum"><?= lang('Admin/MataPelajaran.grp_general') ?: 'Umum' ?></option>
                        <option value="Keislaman"><?= lang('Admin/MataPelajaran.grp_islamic') ?: 'Keislaman' ?></option>
                        <option value="Lokal"><?= lang('Admin/MataPelajaran.grp_local') ?: 'Muatan Lokal' ?></option>
                    </select>
                </div>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 dark:text-slate-300 mb-2"><?= lang('Admin/MataPelajaran.lbl_mapel_name') ?></label>
                <input type="text" name="edit_mapel_name" id="edit_mapel_name" required 
                       class="w-full px-4 py-2.5 bg-gray-50 dark:bg-slate-700 border border-gray-300 dark:border-slate-600 text-gray-900 dark:text-white text-sm rounded-xl focus:bg-white dark:focus:bg-slate-800 focus:ring-2 focus:ring-[<?= $color['warna_primary'] ?>]/50 focus:border-[<?= $color['warna_primary'] ?>] block transition-all outline-none">
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-slate-300 mb-2"><?= lang('Admin/MataPelajaran.lbl_curriculum') ?></label>
                    <select name="edit_curriculum" id="edit_curriculum" required 
                            class="w-full px-4 py-2.5 bg-gray-50 dark:bg-slate-700 border border-gray-300 dark:border-slate-600 text-gray-900 dark:text-white text-sm rounded-xl focus:bg-white dark:focus:bg-slate-800 focus:ring-2 focus:ring-[<?= $color['warna_primary'] ?>]/50 focus:border-[<?= $color['warna_primary'] ?>] block transition-all appearance-none cursor-pointer outline-none">
                        <option value="1">Kurikulum 2013</option>
                        <option value="2">Kurikulum Merdeka</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-slate-300 mb-2"><?= lang('Admin/MataPelajaran.lbl_hours_per_week') ?></label>
                    <input type="number" name="edit_hours" id="edit_hours" required 
                           class="w-full px-4 py-2.5 bg-gray-50 dark:bg-slate-700 border border-gray-300 dark:border-slate-600 text-gray-900 dark:text-white text-sm rounded-xl focus:bg-white dark:focus:bg-slate-800 focus:ring-2 focus:ring-[<?= $color['warna_primary'] ?>]/50 focus:border-[<?= $color['warna_primary'] ?>] block transition-all outline-none">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-slate-300 mb-2"><?= lang('Admin/MataPelajaran.lbl_no_urut') ?></label>
                    <input type="number" name="edit_nomor_urut" id="edit_nomor_urut" placeholder="0" min="0" 
                           class="w-full px-4 py-2.5 bg-gray-50 dark:bg-slate-700 border border-gray-300 dark:border-slate-600 text-gray-900 dark:text-white text-sm rounded-xl focus:bg-white dark:focus:bg-slate-800 focus:ring-2 focus:ring-[<?= $color['warna_primary'] ?>]/50 focus:border-[<?= $color['warna_primary'] ?>] block transition-all outline-none">
                </div>
            </div>
            <div class="flex gap-3 pt-4 mt-2 border-t border-gray-100 dark:border-slate-700 transition-colors">
                <button type="button" onclick="closeEditMapelModal()" class="flex-1 px-5 py-3 bg-white dark:bg-slate-700 border border-gray-300 dark:border-slate-600 text-gray-700 dark:text-slate-300 font-semibold rounded-xl hover:bg-gray-50 dark:hover:bg-slate-600 transition-colors shadow-sm outline-none">
                    <?= lang('Admin/MataPelajaran.btn_cancel') ?>
                </button>
                <button type="submit" class="flex-1 px-5 py-3 text-white font-semibold rounded-xl shadow-lg transition-transform transform hover:-translate-y-0.5 outline-none" style="background-color: <?= $color['warna_primary'] ?>; box-shadow: 0 10px 15px -3px <?= $color['warna_primary'] ?>40;">
                    <?= lang('Admin/MataPelajaran.btn_save_changes') ?>
                </button>
            </div>
        </form>
    </div>
  </div>
</div>

<div id="deleteModal" class="fixed inset-0 z-[99999] hidden flex items-center justify-center p-4 overflow-x-hidden overflow-y-auto">
    <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" onclick="closeDeleteModal()"></div>
    <div class="relative w-full max-w-md bg-white dark:bg-slate-800 rounded-2xl shadow-2xl transform transition-colors border border-transparent dark:border-slate-700 scale-100">
        <div class="p-6 text-center">
            <div class="w-20 h-20 mx-auto bg-red-50 dark:bg-red-900/30 rounded-full flex items-center justify-center mb-5 border-4 border-red-100 dark:border-red-800/50">
                <svg class="w-10 h-10 text-red-500 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
            </div>
            <h3 class="text-xl font-bold text-gray-800 dark:text-white mb-2"><?= lang('Admin/MataPelajaran.del_modal_title') ?></h3>
            <p class="text-sm text-gray-500 dark:text-slate-400 mb-6">
                <?= lang('Admin/MataPelajaran.del_modal_desc_1') ?> <span id="deleteMapelName" class="font-bold text-gray-700 dark:text-slate-300"></span>? 
                <br><?= lang('Admin/MataPelajaran.del_modal_desc_2') ?>
            </p>
            <div class="flex gap-3 justify-center">
                <button onclick="closeDeleteModal()" class="flex-1 px-6 py-2.5 bg-white dark:bg-slate-700 border border-gray-300 dark:border-slate-600 text-gray-700 dark:text-slate-300 font-semibold rounded-xl hover:bg-gray-50 dark:hover:bg-slate-600 transition-colors outline-none shadow-sm">
                    <?= lang('Admin/MataPelajaran.btn_cancel') ?>
                </button>
                <button onclick="confirmDelete()" class="flex-1 px-6 py-2.5 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-xl shadow-lg shadow-red-600/30 transition-transform transform hover:-translate-y-0.5 outline-none">
                    <?= lang('Admin/MataPelajaran.btn_yes_delete') ?>
                </button>
            </div>
        </div>
    </div>
</div>

<div id="importModal" class="fixed inset-0 z-[99999] hidden flex items-center justify-center p-4 overflow-x-hidden overflow-y-auto">
  <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" onclick="closeImportModal()"></div>
  <div class="relative w-full max-w-lg bg-white dark:bg-slate-800 rounded-2xl shadow-2xl flex flex-col transform transition-colors border border-gray-200 dark:border-slate-700">
    <div class="p-5 border-b border-gray-100 dark:border-slate-700 flex justify-between items-center bg-white dark:bg-slate-800 rounded-t-2xl transition-colors">
        <div>
            <h3 class="text-xl font-bold text-gray-800 dark:text-white"><?= lang('Admin/MataPelajaran.import_title') ?: 'Import Excel' ?></h3>
            <p class="text-sm text-gray-500 dark:text-slate-400 mt-0.5"><?= lang('Admin/MataPelajaran.import_desc') ?: 'Upload data mapel massal via Excel' ?></p>
        </div>
        <button type="button" onclick="closeImportModal()" class="p-2 text-gray-400 hover:text-gray-600 dark:hover:text-slate-300 hover:bg-gray-100 dark:hover:bg-slate-700 rounded-lg transition-colors outline-none">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
    </div>
    <div class="p-6">
        <form id="importForm" onsubmit="handleImportSubmit(event)" action="<?= base_url('admin/mata-pelajaran/import') ?>" enctype="multipart/form-data" class="space-y-5">
            <?= csrf_field() ?>
            <div>
                <p class="text-sm font-medium text-gray-700 dark:text-slate-300 mb-2"><?= lang('Admin/MataPelajaran.step_1_download') ?: 'Langkah 1: Download Template' ?></p>
                <a href="<?= base_url('admin/mata-pelajaran/template') ?>" class="inline-flex items-center gap-2 px-4 py-2 font-bold rounded-lg transition-colors text-sm border border-transparent hover:border-[<?= $color['warna_primary'] ?>]/30 shadow-sm" style="color: <?= $color['warna_primary'] ?>; background-color: <?= $color['warna_primary'] ?>1A;">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" /></svg>
                    <?= lang('Admin/MataPelajaran.btn_download_template') ?: 'Download Template Excel' ?>
                </a>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-700 dark:text-slate-300 mb-2"><?= lang('Admin/MataPelajaran.step_2_upload') ?: 'Langkah 2: Upload File' ?></p>
                <input type="file" name="file_excel" accept=".xlsx, .xls" required class="block w-full text-sm text-gray-500 dark:text-slate-400 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-gray-100 dark:file:bg-slate-700 file:text-gray-700 dark:file:text-slate-200 hover:file:bg-gray-200 dark:hover:file:bg-slate-600 border border-gray-200 dark:border-slate-600 cursor-pointer outline-none focus:ring-2 focus:ring-[<?= $color['warna_primary'] ?>] transition-colors">
            </div>
            <div class="flex gap-3 pt-4 border-t border-gray-100 dark:border-slate-700">
                <button type="button" onclick="closeImportModal()" class="flex-1 px-5 py-2.5 bg-white dark:bg-slate-700 border border-gray-300 dark:border-slate-600 text-gray-700 dark:text-slate-300 font-bold rounded-xl hover:bg-gray-50 dark:hover:bg-slate-600 transition-colors outline-none shadow-sm"><?= lang('Admin/MataPelajaran.btn_cancel') ?></button>
                <button type="submit" class="flex-1 px-5 py-3 text-white font-bold rounded-xl shadow-lg transition-transform flex items-center justify-center gap-2 outline-none transform hover:-translate-y-0.5" style="background-color: <?= $color['warna_primary'] ?>; box-shadow: 0 10px 15px -3px <?= $color['warna_primary'] ?>40;">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" /></svg> <?= lang('Admin/MataPelajaran.btn_upload') ?: 'Upload Data' ?>
                </button>
            </div>
        </form>
    </div>
  </div>
</div>

<div id="groupSettingModal" class="fixed inset-0 z-[99999] hidden flex items-center justify-center p-4 overflow-x-hidden overflow-y-auto">
  <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" onclick="closeGroupSettingModal()"></div>
  <div class="relative w-full max-w-md bg-white dark:bg-slate-800 rounded-2xl shadow-2xl flex flex-col transform transition-colors border border-gray-200 dark:border-slate-700">
    <div class="p-5 border-b border-gray-100 dark:border-slate-700 flex justify-between items-center bg-white dark:bg-slate-800 rounded-t-2xl transition-colors">
        <div>
            <h3 class="text-xl font-bold text-gray-800 dark:text-white"><?= lang('Admin/MataPelajaran.group_modal_title') ?: 'Statistik Kelompok' ?></h3>
            <p class="text-sm text-gray-500 dark:text-slate-400 mt-0.5"><?= lang('Admin/MataPelajaran.group_modal_desc') ?: 'Sebaran mata pelajaran per kelompok' ?></p>
        </div>
        <button type="button" onclick="closeGroupSettingModal()" class="p-2 text-gray-400 hover:text-gray-600 dark:hover:text-slate-300 hover:bg-gray-100 dark:hover:bg-slate-700 rounded-lg transition-colors outline-none">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
    </div>
    <div class="p-6 space-y-4">
        <div class="flex items-center justify-between p-4 bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-100 dark:border-emerald-800/50 rounded-xl">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-emerald-100 dark:bg-emerald-800/50 text-emerald-600 dark:text-emerald-400 flex items-center justify-center"><span class="font-bold">U</span></div>
                <span class="font-bold text-emerald-800 dark:text-emerald-300"><?= lang('Admin/MataPelajaran.grp_general') ?: 'Kelompok Umum (A/B)' ?></span>
            </div>
            <span class="text-2xl font-black text-emerald-700 dark:text-emerald-400"><?= $stats['umum'] ?></span>
        </div>
        
        <div class="flex items-center justify-between p-4 bg-purple-50 dark:bg-purple-900/20 border border-purple-100 dark:border-purple-800/50 rounded-xl">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-purple-100 dark:bg-purple-800/50 text-purple-600 dark:text-purple-400 flex items-center justify-center"><span class="font-bold">I</span></div>
                <span class="font-bold text-purple-800 dark:text-purple-300"><?= lang('Admin/MataPelajaran.grp_islamic') ?: 'Kelompok Keislaman' ?></span>
            </div>
            <span class="text-2xl font-black text-purple-700 dark:text-purple-400"><?= $stats['islam'] ?></span>
        </div>
        
        <div class="flex items-center justify-between p-4 bg-amber-50 dark:bg-amber-900/20 border border-amber-100 dark:border-amber-800/50 rounded-xl">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-amber-100 dark:bg-amber-800/50 text-amber-600 dark:text-amber-400 flex items-center justify-center"><span class="font-bold">L</span></div>
                <span class="font-bold text-amber-800 dark:text-amber-300"><?= lang('Admin/MataPelajaran.grp_local') ?: 'Muatan Lokal' ?></span>
            </div>
            <span class="text-2xl font-black text-amber-700 dark:text-amber-400"><?= $stats['lokal'] ?></span>
        </div>
        
        <div class="pt-4 border-t border-gray-100 dark:border-slate-700">
            <button onclick="closeGroupSettingModal()" class="w-full px-5 py-3 bg-gray-100 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 text-gray-700 dark:text-slate-300 font-bold rounded-xl hover:bg-gray-200 dark:hover:bg-slate-600 transition-colors shadow-sm outline-none">
                Tutup
            </button>
        </div>
    </div>
  </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    const dbMapelData = <?= !empty($mapelData) ? json_encode($mapelData) : '[]' ?>;
    const BASE_URL = "<?= rtrim(base_url(), '/') ?>";
    const csrfTokenName = "<?= csrf_token() ?>";
    const csrfTokenHash = "<?= csrf_hash() ?>";
    
    window.LANG = {
        js_no_data: "<?= lang('Admin/MataPelajaran.js_no_data') ?: 'Data tidak ditemukan.' ?>",
        js_status_active: "<?= lang('Admin/MataPelajaran.js_status_active') ?: 'Aktif' ?>",
        js_status_inactive: "<?= lang('Admin/MataPelajaran.js_status_inactive') ?: 'Nonaktif' ?>",
        js_tooltip_edit: "<?= lang('Admin/MataPelajaran.js_tooltip_edit') ?: 'Edit' ?>",
        js_tooltip_delete: "<?= lang('Admin/MataPelajaran.js_tooltip_delete') ?: 'Hapus' ?>",
        js_saving: "<?= lang('Admin/MataPelajaran.js_saving') ?: 'Menyimpan...' ?>",
        js_success: "<?= lang('Admin/MataPelajaran.js_success') ?: 'Berhasil' ?>",
        js_fail: "<?= lang('Admin/MataPelajaran.js_fail') ?: 'Gagal' ?>",
        js_err_conn: "<?= lang('Admin/MataPelajaran.js_err_conn') ?: 'Kesalahan koneksi' ?>",
        js_err_del_fail: "<?= lang('Admin/MataPelajaran.js_err_del_fail') ?: 'Gagal menghapus data' ?>",
        js_succ_del: "<?= lang('Admin/MataPelajaran.js_succ_del') ?: 'Data mata pelajaran dihapus' ?>",
        js_error: "<?= lang('Admin/MataPelajaran.js_error') ?: 'Error' ?>",
        js_notif_title: "<?= lang('Admin/MataPelajaran.js_notif_title') ?: 'Notifikasi' ?>",
        js_analyzing: "<?= lang('Admin/MataPelajaran.js_analyzing') ?: 'Menganalisis & Upload...' ?>"
    };
</script>
<script src="<?= base_url('assets/js/Admin/mata-pelajaran.js') ?>?v=<?= time() ?>"></script>
<?= $this->endSection() ?>