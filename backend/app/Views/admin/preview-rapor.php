<?= $this->extend('layout/main') ?>

<?= $this->section('title') ?>
  <?= lang('Admin/PreviewRapor.page_title_browser') ?>
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
  <link rel="stylesheet" href="<?= base_url('assets/css/Admin/preview-rapor.css') ?>">
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="mb-6">
    <h1 class="text-2xl md:text-3xl font-black text-gray-900 dark:text-white mb-2 transition-colors" id="pageTitle"><?= lang('Admin/PreviewRapor.page_title') ?></h1>
    <p class="text-base text-gray-600 dark:text-slate-400 font-semibold transition-colors" id="pageSubtitle"><?= lang('Admin/PreviewRapor.page_subtitle') ?></p>
</div>

<div class="card mb-6 bg-white dark:bg-slate-800 rounded-3xl border border-gray-100 dark:border-slate-700 p-6 shadow-sm transition-colors">
    <h2 class="text-base font-black text-gray-900 dark:text-white mb-4 flex items-center gap-2 transition-colors">
        <svg class="w-5 h-5 text-[<?= $color['warna_primary'] ?>]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" /></svg> 
        <?= lang('Admin/PreviewRapor.filter_title') ?>
    </h2>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5 mb-5">
        <div>
            <label class="block text-[11px] font-bold text-gray-600 dark:text-slate-400 uppercase tracking-wider mb-2 transition-colors"><?= lang('Admin/PreviewRapor.level_label') ?></label> 
            <select id="filterTingkat" class="filter-input w-full px-4 py-3 border border-gray-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-gray-800 dark:text-white rounded-xl focus:ring-2 focus:ring-[<?= $color['warna_primary'] ?>] transition-colors appearance-none cursor-pointer outline-none shadow-sm" onchange="updateRombelOptions()"> 
                <option value=""><?= lang('Admin/PreviewRapor.all_levels') ?></option> 
                <?php foreach($list_tingkat as $t): ?>
                    <option value="<?= $t['tingkat'] ?>"><?= lang('Admin/PreviewRapor.class_level') ?> <?= $t['tingkat'] ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div>
            <label class="block text-[11px] font-bold text-gray-600 dark:text-slate-400 uppercase tracking-wider mb-2 transition-colors"><?= lang('Admin/PreviewRapor.rombel_label') ?></label> 
            <select id="filterRombel" class="filter-input w-full px-4 py-3 border border-gray-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-gray-800 dark:text-white rounded-xl focus:ring-2 focus:ring-[<?= $color['warna_primary'] ?>] transition-colors appearance-none cursor-pointer outline-none shadow-sm"> 
                <option value=""><?= lang('Admin/PreviewRapor.all_rombels') ?></option> 
                <?php foreach($list_rombel as $r): ?>
                    <option value="<?= $r['id'] ?>"><?= $r['nama_rombel'] ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="lg:col-span-2">
            <label class="block text-[11px] font-bold text-gray-600 dark:text-slate-400 uppercase tracking-wider mb-2 transition-colors"><?= lang('Admin/PreviewRapor.search_student_label') ?></label> 
            <input type="text" id="searchSiswa" class="filter-input w-full px-4 py-3 border border-gray-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-gray-800 dark:text-white rounded-xl focus:ring-2 focus:ring-[<?= $color['warna_primary'] ?>] transition-colors outline-none placeholder-gray-400 dark:placeholder-slate-400 shadow-sm" placeholder="<?= lang('Admin/PreviewRapor.search_student_placeholder') ?>">
        </div>
    </div>
    <div class="flex justify-end border-t border-gray-100 dark:border-slate-700 pt-5 transition-colors">
        <button class="btn-primary text-white bg-[<?= $color['warna_primary'] ?>] hover:bg-[<?= $color['warna_primary'] ?>]/90 font-bold px-6 py-3 rounded-xl w-full md:w-auto flex items-center justify-center gap-2 transition-transform transform hover:-translate-y-0.5 shadow-lg outline-none" style="box-shadow: 0 10px 15px -3px <?= $color['warna_primary'] ?>40;" onclick="applyFilters()">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg> 
            <?= lang('Admin/PreviewRapor.apply_filter') ?>
        </button>
    </div>
</div>

<div class="card mb-6 bg-white dark:bg-slate-800 rounded-3xl border border-gray-100 dark:border-slate-700 p-6 shadow-sm transition-colors min-h-[400px]">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-6 gap-3 border-b border-gray-100 dark:border-slate-700 pb-4 transition-colors">
        <h2 class="text-lg font-black text-gray-900 dark:text-white flex items-center gap-2 transition-colors">
            <svg class="w-6 h-6 text-[<?= $color['warna_primary'] ?>]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg> 
            <?= lang('Admin/PreviewRapor.student_list_title') ?>
        </h2>
        <span class="inline-flex px-3 py-1 bg-[<?= $color['warna_primary'] ?>]/10 text-[<?= $color['warna_primary'] ?>] text-xs font-black uppercase tracking-wider rounded-lg border border-[<?= $color['warna_primary'] ?>]/20 shadow-sm" id="studentCount">0 <?= lang('Admin/PreviewRapor.student_count') ?></span>
    </div>
    
    <div id="studentsTableContainer" class="w-full overflow-x-auto custom-scrollbar">
        </div>

    <div id="emptyState" class="empty-state py-16 flex flex-col items-center justify-center text-center" style="display: block;"> 
        <div class="empty-state-icon w-20 h-20 bg-gray-50 dark:bg-slate-900/50 rounded-full flex items-center justify-center mb-4 border-2 border-dashed border-gray-200 dark:border-slate-700 transition-colors">
            <svg class="w-10 h-10 text-[<?= $color['warna_primary'] ?>] opacity-60" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
        </div>
        <h3 class="text-xl font-black text-gray-900 dark:text-white mb-2 transition-colors"><?= lang('Admin/PreviewRapor.no_data_title') ?></h3>
        <p class="text-sm text-gray-500 dark:text-slate-400 font-medium max-w-sm transition-colors leading-relaxed" id="emptyStateText"><?= lang('Admin/PreviewRapor.no_data_desc') ?></p>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('modals') ?>
<div id="previewRaporModal" class="fixed inset-0 z-[100000] hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="absolute inset-0 bg-gray-950/80 backdrop-blur-md transition-opacity" onclick="closePreviewModal()"></div>

    <div class="fixed inset-0 z-10 overflow-y-auto custom-scrollbar">
        <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0 md:py-8">
            
            <div class="relative transform overflow-hidden rounded-2xl bg-gray-100 dark:bg-slate-900 text-left shadow-2xl transition-all w-full md:w-auto modal-animate-in border border-gray-300 dark:border-slate-700 mx-auto" style="max-width: 900px;">
                
                <div class="bg-gray-800 dark:bg-gray-950 text-white px-5 py-4 flex justify-between items-center sticky top-0 z-50 shadow-md border-b border-gray-700 transition-colors">
                    <h3 class="text-sm font-bold flex items-center gap-2 tracking-wider uppercase text-gray-300">
                        <svg class="w-5 h-5 text-[<?= $color['warna_primary'] ?>]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                        <?= lang('Admin/PreviewRapor.modal_print_preview') ?>
                    </h3>
                    <div class="flex items-center gap-2">
                        <button onclick="closePreviewModal()" class="text-gray-400 hover:text-white hover:bg-gray-700 dark:hover:bg-slate-800 p-2 rounded-lg transition-colors outline-none">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                        </button>
                    </div>
                </div>

                <div class="bg-gray-200/80 dark:bg-slate-800/50 p-4 md:p-8 overflow-y-auto max-h-[calc(100vh-140px)] custom-scrollbar flex justify-center">
                    <div id="previewContent" class="paper-a4 bg-white text-black p-8 md:p-12 relative shadow-lg mx-auto" style="width: 21cm; min-height: 29.7cm; box-sizing: border-box;">
                        </div>
                </div>

                <div class="bg-white dark:bg-slate-800 px-5 py-4 flex flex-col sm:flex-row sm:justify-end gap-3 border-t border-gray-200 dark:border-slate-700 transition-colors z-50 relative">
                    <button type="button" class="w-full sm:w-auto inline-flex justify-center items-center rounded-xl border border-gray-300 dark:border-slate-600 px-6 py-3 bg-white dark:bg-slate-700 text-sm font-bold text-gray-700 dark:text-slate-300 hover:bg-gray-50 dark:hover:bg-slate-600 focus:outline-none transition-colors shadow-sm outline-none" onclick="closePreviewModal()">
                        <?= lang('Admin/PreviewRapor.modal_close') ?>
                    </button>
                    <button type="button" class="w-full sm:w-auto inline-flex justify-center items-center rounded-xl border border-amber-300 dark:border-amber-800/50 px-6 py-3 bg-amber-50 dark:bg-amber-900/20 text-sm font-bold text-amber-700 dark:text-amber-400 hover:bg-amber-100 dark:hover:bg-amber-900/40 focus:outline-none transition-colors shadow-sm outline-none" onclick="returnToTeacher()">
                        <?= lang('Admin/PreviewRapor.modal_return_revise') ?>
                    </button>
                    <button type="button" class="w-full sm:w-auto inline-flex justify-center items-center rounded-xl border border-transparent px-6 py-3 bg-[<?= $color['warna_primary'] ?>] text-sm font-bold text-white hover:bg-[<?= $color['warna_primary'] ?>]/90 focus:outline-none transition-transform transform hover:-translate-y-0.5 shadow-lg outline-none" style="box-shadow: 0 10px 15px -3px <?= $color['warna_primary'] ?>40;" onclick="lockRapor()">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
                        <?= lang('Admin/PreviewRapor.modal_validate_lock') ?>
                    </button>
                </div>

            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script><script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    const API_URL = "<?= base_url('admin/preview-rapor') ?>";
    // Menarik semua bahasa dari PHP dan merubahnya menjadi objek JS
    const LANG = <?= json_encode(lang('Admin/PreviewRapor')) ?>;
</script>
<script src="<?= base_url('assets/js/Admin/preview-rapor.js') ?>"></script>
<?= $this->endSection() ?>