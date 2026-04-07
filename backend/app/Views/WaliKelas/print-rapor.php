<?= $this->extend('layout/main') ?>

<?= $this->section('title') ?>
  <?= lang('Admin/CetakRapor.page_title_browser') ?> - Rapor Digital SMPIT Ad Durrah
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
    :root { --warna-scroll: <?= $color['warna_primary'] ?>; }
    
    /* Dark Mode Extension */
    html.dark .text-gray-900 { color: #ffffff !important; }
    html.dark .text-gray-800 { color: #f1f5f9 !important; }
    html.dark .text-gray-600 { color: #94a3b8 !important; }
    html.dark .bg-gray-50 { background-color: #0f172a !important; }
    html.dark .bg-gray-100 { background-color: #1e293b !important; }
    html.dark .border-gray-100, html.dark .border-gray-200, html.dark .border-gray-300 { border-color: #334155 !important; }

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
<link rel="stylesheet" href="<?= base_url('assets/css/Admin/cetak-rapor.css') ?>">
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<input type="hidden" id="csrf_token" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">

<div class="mb-6 no-print">
    <div class="flex items-center gap-2 text-sm text-gray-500 dark:text-slate-400 mb-3 transition-colors">
        <span><?= lang('Admin/CetakRapor.breadcrumb_1') ?></span>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
        <span class="text-[<?= $color['warna_primary'] ?>] font-medium"><?= lang('Admin/CetakRapor.breadcrumb_2') ?></span>
    </div>
    
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white mb-2 flex items-center gap-3 transition-colors">
                <svg class="w-8 h-8 text-[<?= $color['warna_primary'] ?>]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" /></svg> 
                <?= lang('Admin/CetakRapor.page_title') ?>
            </h1>
            <p class="text-sm md:text-base text-gray-600 dark:text-slate-400 transition-colors"><?= lang('Admin/CetakRapor.page_subtitle') ?></p>
        </div> 
        <div class="flex flex-wrap items-center gap-2">
            <button onclick="checkAndOpenAction('preview')" class="btn-primary bg-[<?= $color['warna_primary'] ?>]/90 hover:bg-[<?= $color['warna_primary'] ?>] text-white transition-all transform hover:-translate-y-0.5 shadow-lg flex items-center gap-2 px-5 py-2.5 rounded-xl font-bold outline-none" id="btnCetakGlobal" style="box-shadow: 0 10px 15px -3px <?= $color['warna_primary'] ?>40;">
                <svg class="w-5 h-5 " fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                <span><?= lang('Admin/CetakRapor.btn_preview_pdf') ?></span> 
            </button> 
            <button onclick="checkAndOpenAction('download')" class="btn-secondary bg-white dark:bg-slate-800 border-gray-300 dark:border-slate-600 text-gray-700 dark:text-slate-300 hover:bg-gray-50 dark:hover:bg-slate-700 transition-colors flex items-center gap-2 px-5 py-2.5 rounded-xl font-bold shadow-sm outline-none border" id="btnDownloadPDF">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                <span><?= lang('Admin/CetakRapor.btn_download_pdf') ?></span> 
            </button>
        </div>
    </div>

    <div class="alert-box bg-[<?= $color['warna_secondary'] ?>]/50 dark:bg-[<?= $color['warna_secondary'] ?>]/10 border-l-4 border-[<?= $color['warna_primary'] ?>] p-4 rounded-xl flex items-start gap-4 shadow-sm transition-colors mb-6">
        <div class="w-10 h-10 rounded-lg bg-[<?= $color['warna_secondary'] ?>] dark:bg-[<?= $color['warna_primary'] ?>]/20 flex items-center justify-center flex-shrink-0 transition-colors">
            <svg class="w-5 h-5 text-[<?= $color['warna_primary'] ?>]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
        </div>
        <div class="flex-1 mt-0.5">
            <h4 class="font-bold text-[<?= $color['warna_primary'] ?>] mb-1"><?= lang('Admin/CetakRapor.info_title') ?></h4>
            <p class="text-sm font-medium text-[<?= $color['warna_primary'] ?>]/80 dark:text-slate-300 leading-relaxed"><?= lang('Admin/CetakRapor.info_desc') ?></p>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6 no-print w-full min-w-0">
    <div class="lg:col-span-2 space-y-6">
        
        <div class="filter-card bg-white dark:bg-slate-800 rounded-3xl border border-gray-100 dark:border-slate-700 shadow-sm p-6 transition-colors">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-5 flex items-center gap-2 transition-colors border-b border-gray-100 dark:border-slate-700 pb-3">
                <svg class="w-5 h-5 text-[<?= $color['warna_primary'] ?>]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" /></svg> 
                <?= lang('Admin/CetakRapor.filter_title') ?>
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-5">
                <div>
                    <label class="block text-[11px] font-bold text-gray-600 dark:text-slate-400 uppercase tracking-wider mb-2"><?= lang('Admin/CetakRapor.filter_year') ?></label> 
                    <div class="w-full px-4 py-3 bg-gray-100 dark:bg-slate-700/50 border border-gray-200 dark:border-slate-600 text-gray-500 dark:text-slate-400 font-bold rounded-xl shadow-sm flex items-center justify-between cursor-not-allowed select-none">
                        <span><?= esc($tahun_ajaran) ?></span>
                        <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                    </div>
                </div>

                <div>
                    <label class="block text-[11px] font-bold text-gray-600 dark:text-slate-400 uppercase tracking-wider mb-2"><?= lang('Admin/CetakRapor.filter_semester') ?></label> 
                    <div class="w-full px-4 py-3 bg-gray-100 dark:bg-slate-700/50 border border-gray-200 dark:border-slate-600 text-gray-500 dark:text-slate-400 font-bold rounded-xl shadow-sm flex items-center justify-between cursor-not-allowed select-none">
                        <span><?= esc($semester) ?></span>
                        <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                    </div>
                </div>

                <div>
                    <label class="block text-[11px] font-bold text-gray-600 dark:text-slate-400 uppercase tracking-wider mb-2"><?= lang('Admin/CetakRapor.filter_class') ?></label> 
                    <div class="w-full px-4 py-3 bg-gray-100 dark:bg-slate-700/50 border border-gray-200 dark:border-slate-600 text-gray-800 dark:text-white font-bold rounded-xl shadow-sm flex items-center justify-between cursor-not-allowed select-none border-l-4 border-l-[<?= $color['warna_primary'] ?>]">
                        <span><?= esc($rombel_name) ?></span>
                        <svg class="w-4 h-4 text-[<?= $color['warna_primary'] ?>]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                    </div>
                </div>
            </div>
            
            <div class="mb-5 pb-6 border-b border-gray-100 dark:border-slate-700 transition-colors">
                <label class="block text-[11px] font-bold text-gray-600 dark:text-slate-400 uppercase tracking-wider mb-2"><?= lang('Admin/CetakRapor.filter_student') ?></label> 
                <select id="filterSiswa" class="select-custom w-full px-4 py-3 bg-gray-50 dark:bg-slate-700/50 border border-gray-200 dark:border-slate-600 text-gray-800 dark:text-white rounded-xl focus:outline-none focus:ring-2 focus:ring-[<?= $color['warna_primary'] ?>] transition-colors appearance-none cursor-pointer outline-none shadow-sm"> 
                    <option value="">-- <?= lang('Admin/CetakRapor.filter_student_ph') ?> --</option> 
                </select>
            </div>

            <h4 class="font-bold text-gray-800 dark:text-white mb-3 text-sm transition-colors"><?= lang('Admin/CetakRapor.report_type_title') ?></h4>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 mb-6 border-b border-gray-100 dark:border-slate-700 pb-6 transition-colors">
                <label class="flex flex-col items-center gap-2 p-4 border-2 border-gray-200 dark:border-slate-600 rounded-xl cursor-pointer bg-white dark:bg-slate-800 hover:border-[<?= $color['warna_primary'] ?>] dark:hover:border-[<?= $color['warna_primary'] ?>] transition-all shadow-sm group"> 
                    <input type="radio" name="jenisRapor" value="lengkap" class="radio-custom w-4 h-4 text-[<?= $color['warna_primary'] ?>] focus:ring-[<?= $color['warna_primary'] ?>] cursor-pointer outline-none" style="accent-color: <?= $color['warna_primary'] ?>;" checked>
                    <div class="text-center mt-1">
                        <p class="font-bold text-sm text-gray-900 dark:text-white group-hover:text-[<?= $color['warna_primary'] ?>] transition-colors"><?= lang('Admin/CetakRapor.type_full') ?></p>
                        <p class="text-[10px] font-medium text-gray-500 dark:text-slate-400 mt-1"><?= lang('Admin/CetakRapor.type_full_desc') ?></p>
                    </div>
                </label> 
                <label class="flex flex-col items-center gap-2 p-4 border-2 border-gray-200 dark:border-slate-600 rounded-xl cursor-pointer bg-white dark:bg-slate-800 hover:border-[<?= $color['warna_primary'] ?>] dark:hover:border-[<?= $color['warna_primary'] ?>] transition-all shadow-sm group"> 
                    <input type="radio" name="jenisRapor" value="akademik" class="radio-custom w-4 h-4 text-[<?= $color['warna_primary'] ?>] focus:ring-[<?= $color['warna_primary'] ?>] cursor-pointer outline-none" style="accent-color: <?= $color['warna_primary'] ?>;">
                    <div class="text-center mt-1">
                        <p class="font-bold text-sm text-gray-900 dark:text-white group-hover:text-[<?= $color['warna_primary'] ?>] transition-colors"><?= lang('Admin/CetakRapor.type_academic') ?></p>
                        <p class="text-[10px] font-medium text-gray-500 dark:text-slate-400 mt-1"><?= lang('Admin/CetakRapor.type_academic_desc') ?></p>
                    </div>
                </label> 
                <label class="flex flex-col items-center gap-2 p-4 border-2 border-gray-200 dark:border-slate-600 rounded-xl cursor-pointer bg-white dark:bg-slate-800 hover:border-[<?= $color['warna_primary'] ?>] dark:hover:border-[<?= $color['warna_primary'] ?>] transition-all shadow-sm group"> 
                    <input type="radio" name="jenisRapor" value="karakter" class="radio-custom w-4 h-4 text-[<?= $color['warna_primary'] ?>] focus:ring-[<?= $color['warna_primary'] ?>] cursor-pointer outline-none" style="accent-color: <?= $color['warna_primary'] ?>;">
                    <div class="text-center mt-1">
                        <p class="font-bold text-sm text-gray-900 dark:text-white group-hover:text-[<?= $color['warna_primary'] ?>] transition-colors"><?= lang('Admin/CetakRapor.type_character') ?></p>
                        <p class="text-[10px] font-medium text-gray-500 dark:text-slate-400 mt-1"><?= lang('Admin/CetakRapor.type_character_desc') ?></p>
                    </div>
                </label>
            </div>

            <h4 class="font-bold text-gray-800 dark:text-white mb-3 text-sm transition-colors"><?= lang('Admin/CetakRapor.extra_options') ?></h4>
            <div class="space-y-3">
                <label class="flex items-center gap-3 cursor-pointer group"> 
                    <input type="checkbox" id="checkCover" class="checkbox-custom w-5 h-5 rounded border-gray-300 dark:border-slate-500 text-[<?= $color['warna_primary'] ?>] focus:ring-[<?= $color['warna_primary'] ?>] cursor-pointer outline-none" style="accent-color: <?= $color['warna_primary'] ?>;" checked> 
                    <span class="text-sm font-medium text-gray-700 dark:text-slate-300 group-hover:text-gray-900 dark:group-hover:text-white transition-colors"><?= lang('Admin/CetakRapor.opt_cover') ?></span> 
                </label> 
                <label class="flex items-center gap-3 cursor-pointer group">
                    <input type="checkbox" id="checkQR" class="checkbox-custom w-5 h-5 rounded border-gray-300 dark:border-slate-500 text-[<?= $color['warna_primary'] ?>] focus:ring-[<?= $color['warna_primary'] ?>] cursor-pointer outline-none" style="accent-color: <?= $color['warna_primary'] ?>;" checked> 
                    <span class="text-sm font-medium text-gray-700 dark:text-slate-300 group-hover:text-gray-900 dark:group-hover:text-white transition-colors"><?= lang('Admin/CetakRapor.opt_qr') ?></span> 
                </label>
            </div>
        </div>
    </div>

    <div class="space-y-6">
        <div class="info-panel bg-white dark:bg-slate-800 rounded-3xl border border-gray-100 dark:border-slate-700 shadow-sm p-6 transition-colors">
            <h3 class="font-bold text-gray-900 dark:text-white mb-5 flex items-center gap-2 transition-colors border-b border-gray-100 dark:border-slate-700 pb-3">
                <svg class="w-5 h-5 text-[<?= $color['warna_primary'] ?>]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg> 
                <?= lang('Admin/CetakRapor.val_status_title') ?>
            </h3>
            <div class="space-y-4">
                <div class="info-row flex justify-between items-center bg-gray-50 dark:bg-slate-700/50 p-3 rounded-lg transition-colors">
                    <span class="text-[11px] font-bold text-gray-600 dark:text-slate-400 uppercase tracking-wider"><?= lang('Admin/CetakRapor.val_data_status') ?></span> 
                    <span class="text-xs font-bold text-emerald-600 bg-emerald-100 px-2 py-0.5 rounded"><?= lang('Admin/CetakRapor.val_ready') ?></span>
                </div>
                <div class="info-row flex justify-between items-center bg-gray-50 dark:bg-slate-700/50 p-3 rounded-lg transition-colors">
                    <span class="text-[11px] font-bold text-gray-600 dark:text-slate-400 uppercase tracking-wider"><?= lang('Admin/CetakRapor.val_teacher') ?></span> 
                    <span class="text-xs font-black text-gray-900 dark:text-white truncate max-w-[120px]"><?= esc($wali_kelas) ?></span>
                </div>
            </div>
            
            <div class="mt-5 flex items-center justify-center">
                <div id="qrContainer" class="flex flex-col items-center justify-center border-2 border-dashed border-gray-300 dark:border-slate-600 bg-gray-50 dark:bg-slate-700/30 p-6 rounded-xl w-full text-center transition-colors">
                    <svg class="w-12 h-12 text-gray-300 dark:text-slate-500 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" /></svg>
                    <span class="text-xs font-bold text-gray-500 dark:text-slate-400 uppercase tracking-wider"><?= lang('Admin/CetakRapor.val_qr_active') ?></span>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="modalPreviewKertas" class="fixed inset-0 z-[100000] hidden flex items-center justify-center p-4 transition-opacity no-print">
    <div class="modal-overlay absolute inset-0 bg-slate-900/80 backdrop-blur-sm" onclick="closePreviewKertas()"></div>
    <div class="relative bg-gray-200 dark:bg-slate-900 rounded-3xl shadow-2xl w-full max-w-5xl mx-auto overflow-hidden flex flex-col h-[90vh] transform scale-95 transition-all duration-300" id="modalPreviewContent">
        
        <div class="bg-white dark:bg-slate-800 px-6 py-4 flex justify-between items-center border-b border-gray-300 dark:border-slate-700 shadow-sm z-10">
            <div>
                <h3 class="font-black text-lg text-gray-800 dark:text-white flex items-center gap-2">
                    <svg class="w-6 h-6 text-[<?= $color['warna_primary'] ?>]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                    <?= lang('Admin/CetakRapor.modal_preview_title') ?>
                </h3>
                <p class="text-xs text-gray-500 dark:text-slate-400 mt-0.5" id="previewSiswaName"><?= lang('Admin/CetakRapor.js_loading') ?></p>
            </div>
            
            <div class="flex items-center gap-3">
                <button onclick="closePreviewKertas()" class="text-gray-400 hover:bg-gray-100 dark:hover:bg-slate-700 p-2 rounded-xl transition-colors outline-none border border-gray-200 dark:border-slate-600 bg-white dark:bg-slate-800">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
        </div>

        <div class="flex-1 overflow-hidden bg-gray-200 dark:bg-slate-900 relative flex justify-center w-full h-full">
            <div id="iframeLoader" class="absolute inset-0 flex flex-col items-center justify-center bg-gray-200/80 dark:bg-slate-900/80 backdrop-blur-sm z-20">
                <div class="animate-spin rounded-full h-14 w-14 border-b-4 border-[<?= $color['warna_primary'] ?>] mx-auto mb-4"></div>
                <p class="text-gray-600 dark:text-gray-300 font-bold tracking-widest uppercase text-sm"><?= lang('Admin/CetakRapor.modal_rendering') ?></p>
            </div>
            
            <div id="iframeContainer" class="w-full h-full shadow-2xl bg-white transition-all duration-500 flex justify-center"></div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    const BASE_URL = "<?= rtrim(base_url(), '/') ?>";
    const serverStudents = <?= $students ?>;
    
    // KAMUS JS
    window.LANG = {
        js_select_student_first: "<?= lang('Admin/CetakRapor.js_select_student_first') ?>",
        js_err_select_student: "<?= lang('Admin/CetakRapor.js_err_select_student') ?>",
        js_preparing_download: "<?= lang('Admin/CetakRapor.js_preparing_download') ?>",
        js_modal_showing_pdf: "<?= lang('Admin/CetakRapor.js_modal_showing_pdf') ?>",
        js_loading: "<?= lang('Admin/CetakRapor.js_loading') ?>"
    };
</script>
<script src="<?= base_url('assets/js/Admin/preview-rapor.js') ?>?v=<?= time() ?>"></script>
<?= $this->endSection() ?>