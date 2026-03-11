<?= $this->extend('layout/main') ?>

<?= $this->section('title') ?>
<?= lang('Admin/GuruTendik.page_title') ?>
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
  <style>
      :root {
          --warna-scroll: <?= $color['warna_primary'] ?>;
      }
  
      #addModal,
      #importModal,
      #detailDrawer,
      #drawer-overlay {
          z-index: 2147483647 !important;
          position: fixed !important;
      }
  
      #drawer-overlay {
          z-index: 2147483646 !important;
      }
  </style>
  <link rel="stylesheet" href="<?= base_url('assets/css/Admin/guru-tendik.css') ?>">
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div id="base-url" data-url="<?= base_url() ?>"></div>
<div id="api-url" data-url="<?= base_url('admin/guru-tendik/get-all') ?>"></div>
<div id="delete-url" data-url="<?= base_url('admin/guru-tendik/delete') ?>"></div>

<div class="mb-6">
    <div class="flex items-center gap-2 text-sm text-gray-500 dark:text-slate-400 mb-2 transition-colors">
        <span><?= lang('Admin/GuruTendik.breadcrumb') ?></span>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>
        <span class="text-[<?= $color['warna_primary'] ?>] font-medium"><?= lang('Admin/GuruTendik.page_title') ?></span>
    </div>

    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-xl md:text-2xl font-bold text-gray-800 dark:text-white transition-colors"><?= lang('Admin/GuruTendik.page_title') ?></h1>
            <p class="text-sm md:text-base text-gray-600 dark:text-slate-400 mt-1 transition-colors"><?= lang('Admin/GuruTendik.page_desc') ?></p>
        </div>

        <div class="flex flex-wrap items-center gap-2 md:gap-3">
            <button onclick="showAddModal()" class="px-4 py-2.5 bg-[<?= $color['warna_primary'] ?>] hover:bg-[<?= $color['warna_primary'] ?>]/80 text-white font-semibold rounded-xl transition-all shadow-lg shadow-[<?= $color['warna_primary'] ?>]/20 flex items-center gap-2 transform hover:-translate-y-0.5">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                <span class="hidden sm:inline"><?= lang('Admin/GuruTendik.btn_add') ?></span>
                <span class="sm:hidden"><?= lang('Admin/GuruTendik.btn_add_sm') ?></span>
            </button>
            <button onclick="showImportModal()" class="px-4 py-2.5 bg-white dark:bg-slate-800 border border-gray-300 dark:border-slate-600 text-gray-700 dark:text-slate-300 font-medium rounded-xl hover:bg-gray-50 dark:hover:bg-slate-700 transition-colors flex items-center gap-2 cursor-pointer shadow-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                </svg>
                <span class="hidden sm:inline"><?= lang('Admin/GuruTendik.btn_import') ?></span>
            </button>

            <button onclick="window.location.href='<?= base_url('admin/guru-tendik/export') ?>'" class="p-2.5 bg-white dark:bg-slate-800 border border-gray-300 dark:border-slate-600 text-gray-700 dark:text-slate-300 rounded-xl hover:bg-gray-50 dark:hover:bg-slate-700 transition-colors cursor-pointer shadow-sm" title="<?= lang('Admin/GuruTendik.btn_export') ?>">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                </svg>
            </button>
        </div>
    </div>
</div>

<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="bg-white dark:bg-slate-800 p-5 rounded-2xl border border-gray-100 dark:border-slate-700 shadow-sm transition-colors">
        <div class="flex items-center gap-3 mb-2">
            <div class="w-10 h-10 rounded-xl bg-emerald-100 dark:bg-emerald-900/30 flex items-center justify-center">
                <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
            </div>
        </div>
        <p class="text-sm font-medium text-gray-500 dark:text-slate-400 mb-1"><?= lang('Admin/GuruTendik.total_guru') ?></p>
        <h3 class="text-2xl font-bold text-gray-800 dark:text-white"><?= $total_guru ?? 0 ?></h3>
    </div>
    
    <div class="bg-white dark:bg-slate-800 p-5 rounded-2xl border border-gray-100 dark:border-slate-700 shadow-sm transition-colors">
        <div class="flex items-center gap-3 mb-2">
            <div class="w-10 h-10 rounded-xl bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center">
                <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                </svg>
            </div>
        </div>
        <p class="text-sm font-medium text-gray-500 dark:text-slate-400 mb-1"><?= lang('Admin/GuruTendik.total_tahfiz') ?></p>
        <h3 class="text-2xl font-bold text-gray-800 dark:text-white"><?= $total_tahfiz ?? 0 ?></h3>
    </div>

    <div class="bg-white dark:bg-slate-800 p-5 rounded-2xl border border-gray-100 dark:border-slate-700 shadow-sm transition-colors">
        <div class="flex items-center gap-3 mb-2">
            <div class="w-10 h-10 rounded-xl bg-purple-100 dark:bg-purple-900/30 flex items-center justify-center">
                <svg class="w-5 h-5 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2-2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>
            </div>
        </div>
        <p class="text-sm font-medium text-gray-500 dark:text-slate-400 mb-1"><?= lang('Admin/GuruTendik.total_tendik') ?></p>
        <h3 class="text-2xl font-bold text-gray-800 dark:text-white"><?= $total_tendik ?? 0 ?></h3>
    </div>
    
    <div class="bg-white dark:bg-slate-800 p-5 rounded-2xl border border-gray-100 dark:border-slate-700 shadow-sm transition-colors">
        <div class="flex items-center gap-3 mb-2">
            <div class="w-10 h-10 rounded-xl bg-amber-100 dark:bg-amber-900/30 flex items-center justify-center">
                <svg class="w-5 h-5 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2-2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
            </div>
        </div>
        <p class="text-sm font-medium text-gray-500 dark:text-slate-400 mb-1"><?= lang('Admin/GuruTendik.total_wali') ?></p>
        <h3 class="text-2xl font-bold text-gray-800 dark:text-white"><?= $wali_kelas ?? 0 ?></h3>
    </div>
</div>

<div class="bg-white dark:bg-slate-800 rounded-2xl p-4 md:p-6 shadow-sm border border-gray-100 dark:border-slate-700 mb-6 transition-colors">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
        
        <div class="lg:col-span-2">
            <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-2"><?= lang('Admin/GuruTendik.search_lbl') ?></label>
            <div class="relative">
                <input type="text" id="searchInput" placeholder="<?= lang('Admin/GuruTendik.search_ph') ?>" class="w-full pl-10 pr-4 py-2.5 bg-white dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-xl text-sm font-medium text-gray-700 dark:text-white placeholder-gray-400 dark:placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[<?= $color['warna_primary'] ?>] transition-all">
                <svg class="w-5 h-5 text-gray-400 dark:text-slate-400 absolute left-3 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
        </div>
        
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-2"><?= lang('Admin/GuruTendik.role_lbl') ?></label>
            <select id="filterRole" class="w-full px-4 py-2.5 bg-white dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-xl text-sm font-medium text-gray-700 dark:text-white focus:outline-none focus:ring-2 focus:ring-[<?= $color['warna_primary'] ?>] transition-all cursor-pointer">
                <option value=""><?= lang('Admin/GuruTendik.all_roles') ?></option>
                <?php if(isset($jabatan_list)): ?>
                    <?php foreach($jabatan_list as $jabatan): ?>
                        <option value="<?= esc($jabatan['nama_jabatan']) ?>"><?= esc($jabatan['nama_jabatan']) ?></option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
        </div>
        
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-2"><?= lang('Admin/GuruTendik.mapel_lbl') ?></label>
            <select id="filterMapel" class="w-full px-4 py-2.5 bg-white dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-xl text-sm font-medium text-gray-700 dark:text-white focus:outline-none focus:ring-2 focus:ring-[<?= $color['warna_primary'] ?>] transition-all cursor-pointer">
                <option value=""><?= lang('Admin/GuruTendik.all_mapels') ?></option>
                <?php if(isset($mapel_list) && !empty($mapel_list)): ?>
                    <?php foreach($mapel_list as $mapel): ?>
                        <option value="<?= esc($mapel['nama_mapel']) ?>"><?= esc($mapel['nama_mapel']) ?></option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
        </div>
        
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-2"><?= lang('Admin/GuruTendik.status_lbl') ?></label>
            <select id="filterStatus" class="w-full px-4 py-2.5 bg-white dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-xl text-sm font-medium text-gray-700 dark:text-white focus:outline-none focus:ring-2 focus:ring-[<?= $color['warna_primary'] ?>] transition-all cursor-pointer">
                <option value=""><?= lang('Admin/GuruTendik.all_statuses') ?></option>
                <option value="Aktif"><?= lang('Admin/GuruTendik.status_active') ?></option>
                <option value="Nonaktif"><?= lang('Admin/GuruTendik.status_inactive') ?></option>
            </select>
        </div>
        
    </div>
</div>

<div class="grid grid-cols-1 min-w-0 w-full mb-10">
    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700 overflow-hidden w-full relative transition-colors">

        <div class="px-4 md:px-6 py-4 border-b border-gray-100 dark:border-slate-700 bg-gray-50 dark:bg-slate-900/50 flex items-center justify-between min-h-[60px]">
            <div class="flex items-center gap-3">
                <input type="checkbox" id="selectAll" class="w-4 h-4 text-[<?= $color['warna_primary'] ?>] rounded border-gray-300 dark:border-slate-500 bg-white dark:bg-slate-700 focus:ring-[<?= $color['warna_primary'] ?>] cursor-pointer">
                <label for="selectAll" class="text-sm font-medium text-gray-700 dark:text-slate-300 cursor-pointer select-none">
                    <?= lang('Admin/GuruTendik.select_all') ?>
                </label>
                <span id="selectedCount" class="hidden text-sm text-gray-500 dark:text-slate-400 font-normal ml-1">
                    (0 <?= lang('Admin/GuruTendik.selected_count') ?>)
                </span>
            </div>

            <div id="bulkActions" class="hidden flex items-center gap-2 animate-fade-in">
                <button onclick="bulkExport()" class="px-3 py-1.5 bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400 text-xs font-semibold rounded-lg hover:bg-emerald-200 dark:hover:bg-emerald-900/50 transition-colors border border-transparent dark:border-emerald-800/50">
                    <?= lang('Admin/GuruTendik.btn_bulk_export') ?>
                </button>
                <button onclick="bulkDelete()" class="px-3 py-1.5 bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400 text-xs font-semibold rounded-lg hover:bg-red-200 dark:hover:bg-red-900/50 transition-colors border border-transparent dark:border-red-800/50">
                    <?= lang('Admin/GuruTendik.btn_bulk_delete') ?>
                </button>
            </div>
        </div>

        <div class="block w-full overflow-x-auto custom-scrollbar">
            <table class="w-full whitespace-nowrap text-left border-collapse">
                <thead class="bg-gray-50 dark:bg-slate-900/50 border-b border-gray-100 dark:border-slate-700 transition-colors">
                    <tr>
                        <th class="pl-4 md:pl-6 py-4 w-12"></th>
                        <th class="px-4 md:px-6 py-4 text-xs font-bold text-gray-500 dark:text-slate-400 uppercase tracking-wider"><?= lang('Admin/GuruTendik.th_name_role') ?></th>
                        <th class="px-4 md:px-6 py-4 text-xs font-bold text-gray-500 dark:text-slate-400 uppercase tracking-wider"><?= lang('Admin/GuruTendik.th_nuptk') ?></th>
                        <th class="px-4 md:px-6 py-4 text-xs font-bold text-gray-500 dark:text-slate-400 uppercase tracking-wider"><?= lang('Admin/GuruTendik.th_nik') ?></th>
                        <th class="px-4 md:px-6 py-4 text-xs font-bold text-gray-500 dark:text-slate-400 uppercase tracking-wider"><?= lang('Admin/GuruTendik.th_email') ?></th>
                        <th class="px-4 md:px-6 py-4 text-xs font-bold text-gray-500 dark:text-slate-400 uppercase tracking-wider"><?= lang('Admin/GuruTendik.th_subject') ?></th>
                        <th class="px-4 md:px-6 py-4 text-xs font-bold text-gray-500 dark:text-slate-400 uppercase tracking-wider"><?= lang('Admin/GuruTendik.th_class') ?></th>
                        <th class="px-4 md:px-6 py-4 text-xs font-bold text-gray-500 dark:text-slate-400 uppercase tracking-wider"><?= lang('Admin/GuruTendik.th_status') ?></th>
                        <th class="px-4 md:px-6 py-4 text-xs font-bold text-gray-500 dark:text-slate-400 uppercase tracking-wider text-center"><?= lang('Admin/GuruTendik.th_action') ?></th>
                    </tr>
                </thead>
                <tbody id="teacherTableBody" class="divide-y divide-gray-100 dark:divide-slate-700/50 bg-white dark:bg-slate-800 transition-colors">
                    <tr>
                        <td colspan="9" class="px-6 py-12 text-center text-gray-500 dark:text-slate-400">
                            <?= lang('Admin/GuruTendik.loading_data') ?>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="px-4 md:px-6 py-4 border-t border-gray-100 dark:border-slate-700 flex flex-col md:flex-row items-center justify-between gap-4 bg-white dark:bg-slate-800/50 transition-colors">
            <div class="text-sm text-gray-500 dark:text-slate-400" id="pagination-info">Menampilkan 0 data</div>
            <div class="flex gap-2" id="pagination-buttons"></div>
        </div>

    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('modals') ?>

<div id="importModal" class="fixed inset-0 z-[100] hidden">
    <div class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" onclick="closeImportModal()"></div>

    <div class="absolute inset-0 flex items-center justify-center p-4 pointer-events-none md:left-64">
        <div class="relative w-full bg-white dark:bg-slate-800 rounded-2xl shadow-2xl flex flex-col max-h-[90vh] pointer-events-auto border border-transparent dark:border-slate-700 transition-colors" style="max-width: 500px;">
            <div class="p-6 border-b border-gray-100 dark:border-slate-700 flex items-center justify-between">
                <h3 class="text-xl font-bold text-gray-800 dark:text-white"><?= lang('Admin/GuruTendik.import_title') ?></h3>
                <button onclick="closeImportModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-slate-300 transition-colors outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <div class="p-6">
                <form id="importForm" action="<?= base_url('admin/guru-tendik/import') ?>" method="POST" onsubmit="handleImport(event)" enctype="multipart/form-data">
                    <div class="mb-4">
                        <p class="text-sm text-gray-600 dark:text-slate-400 mb-2"><?= lang('Admin/GuruTendik.import_step_1') ?></p>
                        <a href="<?= base_url('admin/guru-tendik/template') ?>" class="inline-flex items-center gap-2 text-[<?= $color['warna_primary'] ?>]/90 hover:text-[<?= $color['warna_primary'] ?>] dark:text-[<?= $color['warna_primary'] ?>] font-medium text-sm transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                            </svg>
                            <?= lang('Admin/GuruTendik.import_dl_temp') ?>
                        </a>
                    </div>
                    <div class="mb-6">
                        <p class="text-sm text-gray-600 dark:text-slate-400 mb-2"><?= lang('Admin/GuruTendik.import_step_2') ?></p>
                        <input type="file" name="file_excel" accept=".xlsx, .xls" required class="block w-full text-sm text-gray-500 dark:text-slate-400 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-emerald-50 dark:file:bg-emerald-900/30 file:text-[<?= $color['warna_primary'] ?>] hover:file:bg-emerald-100 dark:hover:file:bg-emerald-800/50 cursor-pointer outline-none transition-colors">
                    </div>
                    <div class="flex justify-end gap-3">
                        <button type="button" onclick="closeImportModal()" class="px-5 py-2.5 bg-white dark:bg-slate-700 border border-gray-300 dark:border-slate-600 text-gray-700 dark:text-slate-300 font-medium rounded-xl hover:bg-gray-50 dark:hover:bg-slate-600 transition-colors shadow-sm outline-none"><?= lang('Admin/GuruTendik.btn_cancel') ?></button>
                        <button type="submit" class="px-5 py-2.5 bg-[<?= $color['warna_primary'] ?>]/90 hover:bg-[<?= $color['warna_primary'] ?>] text-white font-medium rounded-xl transition-all shadow-lg shadow-[<?= $color['warna_primary'] ?>]/20 outline-none"><?= lang('Admin/GuruTendik.btn_upload_import') ?></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div id="addModal" class="fixed inset-0 z-[100] hidden">
    <div class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" onclick="closeAddModal()"></div>

    <div class="absolute inset-0 flex items-center justify-center p-4 pointer-events-none md:left-64">
        <div class="relative w-full bg-white dark:bg-slate-800 rounded-2xl shadow-2xl flex flex-col max-h-[90vh] pointer-events-auto border border-transparent dark:border-slate-700 transition-colors duration-300" style="max-width: 900px;">

            <div class="px-8 py-6 border-b border-gray-100 dark:border-slate-700 flex items-start justify-between bg-white dark:bg-slate-800 rounded-t-2xl z-20 flex-shrink-0 transition-colors">
                <div>
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white"><?= lang('Admin/GuruTendik.add_modal_title') ?></h3>
                    <p class="text-sm text-gray-500 dark:text-slate-400 mt-1"><?= lang('Admin/GuruTendik.add_modal_desc') ?></p>
                </div>
                <button type="button" onclick="closeAddModal()" class="p-2 hover:bg-gray-100 dark:hover:bg-slate-700 rounded-full transition-colors cursor-pointer text-gray-400 hover:text-gray-600 dark:hover:text-slate-300 outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <div class="flex-1 overflow-y-auto px-8 py-6 relative z-10 custom-scrollbar">
                <form id="addTeacherForm" action="<?= base_url('admin/guru-tendik/store') ?>" onsubmit="handleSubmit(event)" method="POST" enctype="multipart/form-data" class="space-y-8" novalidate>

                    <div class="space-y-6">
                        <div class="flex items-center gap-3 pb-3 border-b border-emerald-500 dark:border-emerald-600/50">
                            <div class="w-8 h-8 rounded bg-emerald-100 dark:bg-emerald-900/40 flex items-center justify-center text-emerald-600 dark:text-emerald-400 font-bold text-sm">A</div>
                            <h4 class="text-lg font-bold text-gray-800 dark:text-white"><?= lang('Admin/GuruTendik.personal_data') ?></h4>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="md:col-span-2 space-y-1">
                                <label class="block text-sm font-medium text-gray-700 dark:text-slate-300"><?= lang('Admin/GuruTendik.lbl_fullname') ?> <span class="text-red-500">*</span></label>
                                <input type="text" name="fullname" required placeholder="<?= lang('Admin/GuruTendik.ph_fullname') ?>" class="w-full px-4 py-3 bg-white dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-xl text-sm text-gray-800 dark:text-white placeholder-gray-400 dark:placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[<?= $color['warna_primary'] ?>]/50 focus:border-[<?= $color['warna_primary'] ?>] transition-all shadow-sm">
                            </div>

                            <div class="space-y-1">
                                <label class="block text-sm font-medium text-gray-700 dark:text-slate-300"><?= lang('Admin/GuruTendik.lbl_nuptk') ?></label>
                                <input type="text" name="nuptk" placeholder="<?= lang('Admin/GuruTendik.ph_nuptk') ?>" class="w-full px-4 py-3 bg-white dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-xl text-sm text-gray-800 dark:text-white placeholder-gray-400 dark:placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[<?= $color['warna_primary'] ?>]/50 focus:border-[<?= $color['warna_primary'] ?>] transition-all shadow-sm">
                            </div>

                            <div class="space-y-1">
                                <label class="block text-sm font-medium text-gray-700 dark:text-slate-300"><?= lang('Admin/GuruTendik.lbl_nik') ?> <span class="text-red-500">*</span></label>
                                <input type="text" name="nik" required placeholder="<?= lang('Admin/GuruTendik.ph_nik') ?>" class="w-full px-4 py-3 bg-white dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-xl text-sm text-gray-800 dark:text-white placeholder-gray-400 dark:placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[<?= $color['warna_primary'] ?>]/50 focus:border-[<?= $color['warna_primary'] ?>] transition-all shadow-sm">
                            </div>

                            <div class="md:col-span-2 space-y-1">
                                <label class="block text-sm font-medium text-gray-700 dark:text-slate-300"><?= lang('Admin/GuruTendik.lbl_email') ?> <span class="text-red-500">*</span></label>
                                <input type="email" name="email" required placeholder="<?= lang('Admin/GuruTendik.ph_email') ?>" class="w-full px-4 py-3 bg-white dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-xl text-sm text-gray-800 dark:text-white placeholder-gray-400 dark:placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[<?= $color['warna_primary'] ?>]/50 focus:border-[<?= $color['warna_primary'] ?>] transition-all shadow-sm">
                            </div>

                            <div class="space-y-1">
                                <label class="block text-sm font-medium text-gray-700 dark:text-slate-300"><?= lang('Admin/GuruTendik.lbl_phone') ?></label>
                                <input type="text" name="phone" placeholder="<?= lang('Admin/GuruTendik.ph_phone') ?>" class="w-full px-4 py-3 bg-white dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-xl text-sm text-gray-800 dark:text-white placeholder-gray-400 dark:placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[<?= $color['warna_primary'] ?>]/50 focus:border-[<?= $color['warna_primary'] ?>] transition-all shadow-sm">
                            </div>
                            
                            <div class="space-y-1">
                                <label class="block text-sm font-medium text-gray-700 dark:text-slate-300"><?= lang('Admin/GuruTendik.lbl_gender') ?> <span class="text-red-500">*</span></label>
                                <select name="gender" required class="w-full px-4 py-3 bg-white dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-xl text-sm text-gray-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-[<?= $color['warna_primary'] ?>]/50 focus:border-[<?= $color['warna_primary'] ?>] transition-all shadow-sm outline-none appearance-none cursor-pointer">
                                    <option value=""><?= lang('Admin/GuruTendik.ph_gender') ?></option>
                                    <option value="L"><?= lang('Admin/GuruTendik.gender_m') ?></option>
                                    <option value="P"><?= lang('Admin/GuruTendik.gender_f') ?></option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-6">
                        <div class="flex items-center gap-3 pb-3 border-b border-blue-500 dark:border-blue-600/50">
                            <div class="w-8 h-8 rounded bg-blue-100 dark:bg-blue-900/40 flex items-center justify-center text-blue-600 dark:text-blue-400 font-bold text-sm">B</div>
                            <h4 class="text-lg font-bold text-gray-800 dark:text-white"><?= lang('Admin/GuruTendik.job_data') ?></h4>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-1">
                                <label class="block text-sm font-medium text-gray-700 dark:text-slate-300"><?= lang('Admin/GuruTendik.lbl_main_role') ?> <span class="text-red-500">*</span></label>
                                <select name="role" required class="w-full px-4 py-3 bg-white dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-xl text-sm text-gray-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-[<?= $color['warna_primary'] ?>]/50 transition-all shadow-sm appearance-none cursor-pointer">
                                    <option value=""><?= lang('Admin/GuruTendik.ph_role') ?></option>
                                    <?php if(isset($jabatan_list)): ?>
                                        <?php foreach($jabatan_list as $jabatan): ?>
                                            <option value="<?= esc($jabatan['nama_jabatan']) ?>"><?= esc($jabatan['nama_jabatan']) ?></option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                            
                            <div class="space-y-1">
                                <label class="block text-sm font-medium text-gray-700 dark:text-slate-300"><?= lang('Admin/GuruTendik.lbl_emp_status') ?></label>
                                <select name="employment_status" class="w-full px-4 py-3 bg-white dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-xl text-sm text-gray-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-[<?= $color['warna_primary'] ?>]/50 transition-all shadow-sm appearance-none cursor-pointer">
                                    <option value=""><?= lang('Admin/GuruTendik.ph_emp_status') ?></option>
                                    <?php if(isset($status_list)): ?>
                                        <?php foreach($status_list as $status): ?>
                                            <option value="<?= esc($status['nama_status']) ?>"><?= esc($status['nama_status']) ?></option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                            
                            <div class="md:col-span-2 space-y-1">
                                <label class="block text-sm font-medium text-gray-700 dark:text-slate-300"><?= lang('Admin/GuruTendik.lbl_guidance_subj') ?></label>
                                <select name="subject" class="w-full px-4 py-3 bg-white dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-xl text-sm text-gray-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-[<?= $color['warna_primary'] ?>]/50 transition-all shadow-sm appearance-none cursor-pointer">
                                    <option value="-"><?= lang('Admin/GuruTendik.ph_no_subj') ?></option>
                                    <?php if(isset($mapel_list)): ?>
                                        <?php foreach($mapel_list as $mapel): ?>
                                            <option value="<?= esc($mapel['nama_mapel']) ?>"><?= esc($mapel['nama_mapel']) ?></option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                                <p class="text-xs text-gray-400 dark:text-slate-500 mt-1"><?= lang('Admin/GuruTendik.desc_subj') ?></p>
                            </div>

                            <div class="md:col-span-2 space-y-2">
                                <label class="block text-sm font-medium text-gray-700 dark:text-slate-300"><?= lang('Admin/GuruTendik.lbl_photo') ?></label>
                                <div class="flex items-start gap-4 p-4 border border-gray-100 dark:border-slate-700 rounded-xl bg-gray-50/50 dark:bg-slate-800/50 transition-colors">
                                    <div id="photoPreview" class="w-20 h-20 bg-gray-200 dark:bg-slate-700 rounded-lg flex-shrink-0 flex items-center justify-center text-xs text-gray-500 dark:text-slate-400 overflow-hidden border border-gray-300 dark:border-slate-600 transition-colors">
                                        <?= lang('Admin/GuruTendik.no_image') ?>
                                    </div>
                                    <div class="flex-1">
                                        <input type="file" name="photo" accept="image/*" onchange="previewPhoto(event)" class="block w-full text-sm text-gray-500 dark:text-slate-400 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-emerald-600 dark:file:bg-emerald-600 file:text-white hover:file:bg-emerald-700 dark:hover:file:bg-emerald-500 cursor-pointer border border-gray-300 dark:border-slate-600 rounded-xl bg-white dark:bg-slate-700 focus:outline-none transition-colors">
                                        <p class="text-xs text-gray-500 dark:text-slate-500 mt-2"><?= lang('Admin/GuruTendik.photo_format_desc') ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3 pt-6 border-t border-gray-100 dark:border-slate-700 sticky bottom-0 bg-white dark:bg-slate-800 z-10 pb-2 transition-colors">
                        <button type="button" onclick="closeAddModal()" class="w-full px-4 py-3 bg-white dark:bg-slate-700 border border-gray-300 dark:border-slate-600 text-gray-700 dark:text-slate-300 font-bold rounded-xl hover:bg-gray-50 dark:hover:bg-slate-600 transition-colors shadow-sm outline-none">
                            <?= lang('Admin/GuruTendik.btn_cancel') ?>
                        </button>
                        <button type="button" onclick="resetTeacherForm()" class="w-full px-4 py-3 bg-white dark:bg-slate-700 border border-amber-400 dark:border-amber-600/50 text-amber-600 dark:text-amber-500 font-bold rounded-xl hover:bg-amber-50 dark:hover:bg-slate-600 transition-colors shadow-sm outline-none">
                            <?= lang('Admin/GuruTendik.btn_reset_form') ?>
                        </button>
                        <button type="submit" class="w-full px-4 py-3 bg-[<?= $color['warna_primary'] ?>]/90 hover:bg-[<?= $color['warna_primary'] ?>] text-white font-bold rounded-xl transition-all shadow-lg shadow-[<?= $color['warna_primary'] ?>]/20 flex items-center justify-center gap-2 outline-none">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <span id="btnSubmitText"><?= lang('Admin/GuruTendik.btn_save_emp') ?></span>
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

<div id="drawer-overlay" class="drawer-overlay fixed inset-0 bg-black/50 backdrop-blur-sm transition-opacity z-[100001] hidden opacity-0" onclick="closeDrawer()"></div>

<div id="detailDrawer" class="drawer fixed inset-y-0 right-0 hidden bg-white dark:bg-slate-800 shadow-2xl w-80 md:w-96 transition-all duration-300 transform translate-x-full z-[100002] flex flex-col border-l border-gray-200 dark:border-slate-700">
    <div class="px-6 py-4 border-b border-gray-100 dark:border-slate-700 flex items-center justify-between bg-white dark:bg-slate-800 z-10 transition-colors">
        <h3 class="text-lg font-bold text-gray-800 dark:text-white"><?= lang('Admin/GuruTendik.drawer_title') ?></h3>
        <button onclick="closeDrawer()" class="p-2 text-gray-400 hover:text-gray-600 dark:hover:text-slate-300 hover:bg-gray-100 dark:hover:bg-slate-700 rounded-lg transition-colors outline-none">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>

    <div class="flex-1 overflow-y-auto p-6 space-y-6 custom-scrollbar">
        <div class="flex flex-col items-center text-center pb-6 border-b border-gray-100 dark:border-slate-700 transition-colors">
            <div id="drawerAvatar" class="w-24 h-24 rounded-2xl bg-emerald-600 flex items-center justify-center text-white text-3xl font-bold shadow-lg mb-4 object-cover overflow-hidden"></div>
            <h4 id="drawerName" class="text-xl font-bold text-gray-800 dark:text-white px-4">-</h4>
            <p id="drawerNip" class="text-sm text-gray-500 dark:text-slate-400 mt-1 font-mono">NUPTK: -</p>
            <div class="flex items-center gap-2 mt-3">
                <span id="drawerRole" class="px-3 py-1 rounded-full text-xs font-semibold bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 border border-blue-100 dark:border-blue-800/50">-</span>
            </div>
        </div>

        <div>
            <h5 class="flex items-center gap-2 text-sm font-bold text-gray-800 dark:text-white mb-3 text-emerald-600 dark:text-emerald-400 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg> <?= lang('Admin/GuruTendik.drawer_personal') ?>
            </h5>
            <div class="space-y-3 text-sm">
                <div class="flex justify-between items-start"><span class="text-gray-500 dark:text-slate-400">NIK</span><span id="drawerNik" class="font-medium text-gray-800 dark:text-slate-200 font-mono text-right">-</span></div>
                <div class="flex justify-between items-start"><span class="text-gray-500 dark:text-slate-400"><?= lang('Admin/GuruTendik.drawer_dob') ?></span><span id="drawerBirth" class="font-medium text-gray-800 dark:text-slate-200 text-right">-</span></div>
                <div class="flex justify-between items-start"><span class="text-gray-500 dark:text-slate-400">Email</span><span id="drawerEmail" class="font-medium text-gray-800 dark:text-slate-200 text-right break-all">-</span></div>
                <div class="flex justify-between items-start"><span class="text-gray-500 dark:text-slate-400">No. HP</span><span id="drawerPhone" class="font-medium text-gray-800 dark:text-slate-200 text-right">-</span></div>
            </div>
        </div>

        <div class="pt-4 border-t border-gray-100 dark:border-slate-700 transition-colors">
            <h5 class="flex items-center gap-2 text-sm font-bold text-gray-800 dark:text-white mb-3 text-emerald-600 dark:text-emerald-400 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2-2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg> <?= lang('Admin/GuruTendik.drawer_emp') ?>
            </h5>
            <div class="space-y-3 text-sm">
                <div class="flex justify-between"><span class="text-gray-500 dark:text-slate-400">Status</span><span id="drawerEmpStatus" class="font-medium text-gray-800 dark:text-slate-200">-</span></div>
            </div>
        </div>

        <div class="pt-4 border-t border-gray-100 dark:border-slate-700 transition-colors">
            <h5 class="flex items-center gap-2 text-sm font-bold text-gray-800 dark:text-white mb-3 text-emerald-600 dark:text-emerald-400 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                </svg> <?= lang('Admin/GuruTendik.drawer_teach_data') ?>
            </h5>
            <div class="space-y-3 text-sm">
                <div class="flex justify-between items-center">
                    <span class="text-gray-500 dark:text-slate-400">Mata Pelajaran</span>
                    <span id="drawerSubject" class="font-medium text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/30 border border-blue-100 dark:border-blue-800/50 px-3 py-1 rounded-lg text-xs">-</span>
                </div>
            </div>
        </div>
    </div>

    <div class="p-4 border-t border-gray-100 dark:border-slate-700 bg-gray-50 dark:bg-slate-800/80 transition-colors">
        <button id="drawerEditBtn" class="w-full py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-xl transition-colors flex items-center justify-center gap-2 shadow-sm outline-none">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
            </svg> <?= lang('Admin/GuruTendik.btn_edit_data') ?>
        </button>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    window.LANG = {
        js_loading: "<?= lang('Admin/GuruTendik.js_loading') ?: 'Memuat data...' ?>",
        js_load_fail: "<?= lang('Admin/GuruTendik.js_load_fail') ?: 'Gagal memuat data.' ?>",
        js_no_data: "<?= lang('Admin/GuruTendik.js_no_data') ?: 'Data tidak ditemukan' ?>",
        showing_data: "<?= lang('Admin/GuruTendik.showing_data') ?: 'Menampilkan' ?>",
        from_data: "<?= lang('Admin/GuruTendik.from_data') ?: 'dari' ?>",
        data: "<?= lang('Admin/GuruTendik.data') ?: 'data' ?>",
        js_status_active: "<?= lang('Admin/GuruTendik.js_status_active') ?: 'Aktif' ?>",
        js_status_inactive: "<?= lang('Admin/GuruTendik.js_status_inactive') ?: 'Nonaktif' ?>",
        js_role_employee: "<?= lang('Admin/GuruTendik.js_role_employee') ?: 'Pegawai' ?>",
        js_btn_view: "<?= lang('Admin/GuruTendik.js_btn_view') ?: 'Lihat' ?>",
        js_btn_edit: "<?= lang('Admin/GuruTendik.js_btn_edit') ?: 'Edit' ?>",
        js_title_add: "<?= lang('Admin/GuruTendik.js_title_add') ?: 'Tambah Pegawai Baru' ?>",
        js_title_edit: "<?= lang('Admin/GuruTendik.js_title_edit') ?: 'Edit Data Pegawai' ?>",
        js_btn_save_add: "<?= lang('Admin/GuruTendik.js_btn_save_add') ?: 'Simpan Data Pegawai' ?>",
        js_btn_save_edit: "<?= lang('Admin/GuruTendik.js_btn_save_edit') ?: 'Simpan Perubahan' ?>",
        js_saving: "<?= lang('Admin/GuruTendik.js_saving') ?: 'Menyimpan...' ?>",
        js_uploading: "<?= lang('Admin/GuruTendik.js_uploading') ?: 'Mengupload...' ?>",
        js_err_server: "<?= lang('Admin/GuruTendik.js_err_server') ?: 'Terjadi kesalahan server.' ?>",
        js_err_upload: "<?= lang('Admin/GuruTendik.js_err_upload') ?: 'Gagal upload.' ?>",
        js_confirm_del: "<?= lang('Admin/GuruTendik.js_confirm_del') ?: 'Hapus data?' ?>",
        js_confirm_del_desc: "<?= lang('Admin/GuruTendik.js_confirm_del_desc') ?: 'Data akan dihapus permanen!' ?>",
        js_btn_yes_del: "<?= lang('Admin/GuruTendik.js_btn_yes_del') ?: 'Ya, Hapus' ?>",
        js_del_success: "<?= lang('Admin/GuruTendik.js_del_success') ?: 'Terhapus' ?>",
        js_del_fail: "<?= lang('Admin/GuruTendik.js_del_fail') ?: 'Gagal: ' ?>",
        js_bulk_no_select: "<?= lang('Admin/GuruTendik.js_bulk_no_select') ?: 'Pilih data dulu' ?>",
        js_bulk_del_conf: "<?= lang('Admin/GuruTendik.js_bulk_del_conf') ?: 'Hapus data terpilih?' ?>",
        js_bulk_del_desc: "<?= lang('Admin/GuruTendik.js_bulk_del_desc') ?: 'Data tidak bisa dikembalikan!' ?>",
        js_success: "<?= lang('Admin/GuruTendik.js_success') ?: 'Berhasil' ?>",
        js_fail: "<?= lang('Admin/GuruTendik.js_fail') ?: 'Gagal' ?>"
    };
</script>
<script src="<?= base_url('assets/js/Admin/guru-tendik.js') ?>?v=<?= time() ?>"></script>
<?= $this->endSection() ?>