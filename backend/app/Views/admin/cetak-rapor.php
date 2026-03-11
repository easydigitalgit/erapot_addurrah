<?= $this->extend('layout/main') ?>

<?= $this->section('title') ?>
  <?= lang('Admin/CetakRapor.page_title') ?> - Rapor Digital SMPIT Ad Durrah
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<link rel="stylesheet" href="<?= base_url('assets/css/Admin/cetak-rapor.css') ?>">
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<input type="hidden" id="csrf_token" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">

<div class="mb-6 no-print">
    <div class="flex items-center gap-2 text-sm text-gray-500 dark:text-slate-400 mb-3 transition-colors">
        <span><?= lang('Admin/CetakRapor.breadcrumb') ?></span>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
        <span class="text-[<?= $color['warna_primary'] ?>] font-medium"><?= lang('Admin/CetakRapor.page_title') ?></span>
    </div>
    
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white mb-2 flex items-center gap-3 transition-colors">
                <svg class="w-8 h-8 text-[<?= $color['warna_primary'] ?>]" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" /></svg> 
                <?= lang('Admin/CetakRapor.page_header') ?>
            </h1>
            <p class="text-sm md:text-base text-gray-600 dark:text-slate-400 transition-colors"><?= lang('Admin/CetakRapor.page_desc') ?></p>
        </div> 
        <div class="flex flex-wrap items-center gap-2">
            <button onclick="checkAndOpenModal('preview')" class="btn-primary bg-[<?= $color['warna_primary'] ?>]/90 hover:bg-[<?= $color['warna_primary'] ?>] text-white transition-all transform hover:-translate-y-0.5 shadow-lg flex items-center gap-2 px-5 py-2.5 rounded-xl font-bold outline-none" id="btnCetakGlobal" style="box-shadow: 0 10px 15px -3px <?= $color['warna_primary'] ?>40;">
                <svg class="w-5 h-5 " fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                <span><?= lang('Admin/CetakRapor.btn_print') ?></span> 
            </button> 
            <button onclick="checkAndOpenModal('download')" class="btn-secondary bg-white dark:bg-slate-800 border-gray-300 dark:border-slate-600 text-gray-700 dark:text-slate-300 hover:bg-gray-50 dark:hover:bg-slate-700 transition-colors flex items-center gap-2 px-5 py-2.5 rounded-xl font-bold shadow-sm outline-none border" id="btnDownloadPDF">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                <span><?= lang('Admin/CetakRapor.btn_pdf') ?></span> 
            </button>
        </div>
    </div>

    <div class="alert-box bg-[<?= $color['warna_secondary'] ?>]/50 dark:bg-[<?= $color['warna_secondary'] ?>]/10 border-l-4 border-[<?= $color['warna_primary'] ?>] p-4 rounded-xl flex items-start gap-4 shadow-sm transition-colors mb-6">
        <div class="w-10 h-10 rounded-lg bg-[<?= $color['warna_secondary'] ?>] dark:bg-[<?= $color['warna_primary'] ?>]/20 flex items-center justify-center flex-shrink-0 transition-colors">
            <svg class="w-5 h-5 text-[<?= $color['warna_primary'] ?>]" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
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
                <svg class="w-5 h-5 text-[<?= $color['warna_primary'] ?>]" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" /></svg> 
                <?= lang('Admin/CetakRapor.filter_title') ?>
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-5">
                <div>
                    <label class="block text-[11px] font-bold text-gray-600 dark:text-slate-400 uppercase tracking-wider mb-2"><?= lang('Admin/CetakRapor.academic_year') ?></label> 
                    <div class="w-full px-4 py-3 bg-gray-100 dark:bg-slate-700/50 border border-gray-200 dark:border-slate-600 text-gray-500 dark:text-slate-400 font-bold rounded-xl shadow-sm flex items-center justify-between cursor-not-allowed select-none">
                        <span><?= esc($tahun_ajaran_aktif) ?></span>
                        <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                    </div>
                </div>

                <div>
                    <label class="block text-[11px] font-bold text-gray-600 dark:text-slate-400 uppercase tracking-wider mb-2"><?= lang('Admin/CetakRapor.semester') ?></label> 
                    <div class="w-full px-4 py-3 bg-gray-100 dark:bg-slate-700/50 border border-gray-200 dark:border-slate-600 text-gray-500 dark:text-slate-400 font-bold rounded-xl shadow-sm flex items-center justify-between cursor-not-allowed select-none">
                        <span><?= esc($semester_aktif) ?></span>
                        <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                    </div>
                </div>

                <div>
                    <label class="block text-[11px] font-bold text-gray-600 dark:text-slate-400 uppercase tracking-wider mb-2"><?= lang('Admin/CetakRapor.room') ?></label> 
                    <select id="filterRombel" class="select-custom w-full px-4 py-3 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 text-gray-800 dark:text-white font-bold rounded-xl focus:outline-none focus:ring-2 focus:ring-[<?= $color['warna_primary'] ?>] transition-colors appearance-none cursor-pointer outline-none shadow-sm" onchange="loadSiswaOptions()"> 
                        <option value=""><?= lang('Admin/CetakRapor.select_room') ?></option> 
                        <?php foreach($list_rombel as $r): ?>
                            <option value="<?= $r['id'] ?>">Kelas <?= $r['tingkat'] ?> - <?= $r['nama_rombel'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            
            <div class="mb-5 pb-6 border-b border-gray-100 dark:border-slate-700 transition-colors">
                <label class="block text-[11px] font-bold text-gray-600 dark:text-slate-400 uppercase tracking-wider mb-2"><?= lang('Admin/CetakRapor.select_student') ?></label> 
                <select id="filterSiswa" class="select-custom w-full px-4 py-3 bg-gray-50 dark:bg-slate-700/50 border border-gray-200 dark:border-slate-600 text-gray-800 dark:text-white rounded-xl focus:outline-none focus:ring-2 focus:ring-[<?= $color['warna_primary'] ?>] transition-colors appearance-none cursor-pointer outline-none shadow-sm disabled:opacity-60 disabled:cursor-not-allowed" disabled onchange="enablePrintButton()"> 
                    <option value=""><?= lang('Admin/CetakRapor.opt_sel_room_1st') ?></option> 
                </select>
            </div>

            <h4 class="font-bold text-gray-800 dark:text-white mb-3 text-sm transition-colors"><?= lang('Admin/CetakRapor.report_type') ?></h4>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 mb-6 border-b border-gray-100 dark:border-slate-700 pb-6 transition-colors">
                <label class="flex flex-col items-center gap-2 p-4 border-2 border-gray-200 dark:border-slate-600 rounded-xl cursor-pointer bg-white dark:bg-slate-800 hover:border-[<?= $color['warna_primary'] ?>] dark:hover:border-[<?= $color['warna_primary'] ?>] transition-all shadow-sm group"> 
                    <input type="radio" name="jenisRapor" value="lengkap" class="radio-custom w-4 h-4 text-[<?= $color['warna_primary'] ?>] focus:ring-[<?= $color['warna_primary'] ?>] cursor-pointer outline-none" checked>
                    <div class="text-center mt-1">
                        <p class="font-bold text-sm text-gray-900 dark:text-white group-hover:text-[<?= $color['warna_primary'] ?>] transition-colors"><?= lang('Admin/CetakRapor.type_complete') ?></p>
                        <p class="text-[10px] font-medium text-gray-500 dark:text-slate-400 mt-1"><?= lang('Admin/CetakRapor.type_comp_desc') ?></p>
                    </div>
                </label> 
                <label class="flex flex-col items-center gap-2 p-4 border-2 border-gray-200 dark:border-slate-600 rounded-xl cursor-pointer bg-white dark:bg-slate-800 hover:border-[<?= $color['warna_primary'] ?>] dark:hover:border-[<?= $color['warna_primary'] ?>] transition-all shadow-sm group"> 
                    <input type="radio" name="jenisRapor" value="akademik" class="radio-custom w-4 h-4 text-[<?= $color['warna_primary'] ?>] focus:ring-[<?= $color['warna_primary'] ?>] cursor-pointer outline-none">
                    <div class="text-center mt-1">
                        <p class="font-bold text-sm text-gray-900 dark:text-white group-hover:text-[<?= $color['warna_primary'] ?>] transition-colors"><?= lang('Admin/CetakRapor.type_academic') ?></p>
                        <p class="text-[10px] font-medium text-gray-500 dark:text-slate-400 mt-1"><?= lang('Admin/CetakRapor.type_acad_desc') ?></p>
                    </div>
                </label> 
                <label class="flex flex-col items-center gap-2 p-4 border-2 border-gray-200 dark:border-slate-600 rounded-xl cursor-pointer bg-white dark:bg-slate-800 hover:border-[<?= $color['warna_primary'] ?>] dark:hover:border-[<?= $color['warna_primary'] ?>] transition-all shadow-sm group"> 
                    <input type="radio" name="jenisRapor" value="karakter" class="radio-custom w-4 h-4 text-[<?= $color['warna_primary'] ?>] focus:ring-[<?= $color['warna_primary'] ?>] cursor-pointer outline-none">
                    <div class="text-center mt-1">
                        <p class="font-bold text-sm text-gray-900 dark:text-white group-hover:text-[<?= $color['warna_primary'] ?>] transition-colors"><?= lang('Admin/CetakRapor.type_character') ?></p>
                        <p class="text-[10px] font-medium text-gray-500 dark:text-slate-400 mt-1"><?= lang('Admin/CetakRapor.type_char_desc') ?></p>
                    </div>
                </label>
            </div>

            <h4 class="font-bold text-gray-800 dark:text-white mb-3 text-sm transition-colors"><?= lang('Admin/CetakRapor.optional_add') ?></h4>
            <div class="space-y-3">
                <label class="flex items-center gap-3 cursor-pointer group"> 
                    <input type="checkbox" id="checkCover" class="checkbox-custom w-5 h-5 rounded border-gray-300 dark:border-slate-500 text-[<?= $color['warna_primary'] ?>] focus:ring-[<?= $color['warna_primary'] ?>] cursor-pointer outline-none" checked> 
                    <span class="text-sm font-medium text-gray-700 dark:text-slate-300 group-hover:text-gray-900 dark:group-hover:text-white transition-colors"><?= lang('Admin/CetakRapor.opt_cover') ?></span> 
                </label> 
                <label class="flex items-center gap-3 cursor-pointer group"> 
                    <input type="checkbox" id="checkTTD" class="checkbox-custom w-5 h-5 rounded border-gray-300 dark:border-slate-500 text-[<?= $color['warna_primary'] ?>] focus:ring-[<?= $color['warna_primary'] ?>] cursor-pointer outline-none" checked> 
                    <span class="text-sm font-medium text-gray-700 dark:text-slate-300 group-hover:text-gray-900 dark:group-hover:text-white transition-colors"><?= lang('Admin/CetakRapor.opt_signature') ?></span> 
                </label> 
                <label class="flex items-center gap-3 cursor-pointer group"> 
                    <input type="checkbox" id="checkQR" class="checkbox-custom w-5 h-5 rounded border-gray-300 dark:border-slate-500 text-[<?= $color['warna_primary'] ?>] focus:ring-[<?= $color['warna_primary'] ?>] cursor-pointer outline-none" checked> 
                    <span class="text-sm font-medium text-gray-700 dark:text-slate-300 group-hover:text-gray-900 dark:group-hover:text-white transition-colors"><?= lang('Admin/CetakRapor.opt_qr') ?></span> 
                </label>
            </div>
        </div>

        <div class="settings-card bg-white dark:bg-slate-800 rounded-3xl border border-gray-100 dark:border-slate-700 shadow-sm p-6 transition-colors">
            <h3 class="font-bold text-gray-900 dark:text-white mb-4 text-sm flex items-center gap-2 transition-colors border-b border-gray-100 dark:border-slate-700 pb-3">
                <svg class="w-5 h-5 text-[<?= $color['warna_primary'] ?>]" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" /> <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg> 
                <?= lang('Admin/CetakRapor.paper_layout') ?>
            </h3>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-[11px] font-bold text-gray-600 dark:text-slate-400 uppercase tracking-wider mb-2"><?= lang('Admin/CetakRapor.size') ?></label> 
                    <select id="settingUkuran" onchange="updatePreviewLayout()" class="select-custom w-full px-3 py-2 text-sm bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 text-gray-800 dark:text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-[<?= $color['warna_primary'] ?>] transition-colors outline-none cursor-pointer shadow-sm"> 
                        <option value="a4-potrait"><?= lang('Admin/CetakRapor.portrait') ?></option> 
                        <option value="a4-landscape"><?= lang('Admin/CetakRapor.landscape') ?></option> 
                    </select>
                </div>
                <div>
                    <label class="block text-[11px] font-bold text-gray-600 dark:text-slate-400 uppercase tracking-wider mb-2"><?= lang('Admin/CetakRapor.margin') ?></label> 
                    <select id="settingMargin" onchange="updatePreviewLayout()" class="select-custom w-full px-3 py-2 text-sm bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 text-gray-800 dark:text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-[<?= $color['warna_primary'] ?>] transition-colors outline-none cursor-pointer shadow-sm"> 
                        <option value="standard"><?= lang('Admin/CetakRapor.standard') ?></option> 
                        <option value="narrow"><?= lang('Admin/CetakRapor.narrow') ?></option> 
                        <option value="wide"><?= lang('Admin/CetakRapor.wide') ?></option> 
                    </select>
                </div>
                <div>
                    <label class="block text-[11px] font-bold text-gray-600 dark:text-slate-400 uppercase tracking-wider mb-2"><?= lang('Admin/CetakRapor.scale') ?></label> 
                    <select id="settingSkala" onchange="updatePreviewLayout()" class="select-custom w-full px-3 py-2 text-sm bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 text-gray-800 dark:text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-[<?= $color['warna_primary'] ?>] transition-colors outline-none cursor-pointer shadow-sm"> 
                        <option value="100">100%</option> 
                        <option value="95">95%</option> 
                        <option value="90">90%</option> 
                    </select>
                </div>
                <div>
                    <label class="block text-[11px] font-bold text-gray-600 dark:text-slate-400 uppercase tracking-wider mb-2"><?= lang('Admin/CetakRapor.numbering') ?></label> 
                    <select id="settingNomor" onchange="updatePreviewLayout()" class="select-custom w-full px-3 py-2 text-sm bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 text-gray-800 dark:text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-[<?= $color['warna_primary'] ?>] transition-colors outline-none cursor-pointer shadow-sm"> 
                        <option value="on"><?= lang('Admin/CetakRapor.show') ?></option> 
                        <option value="off"><?= lang('Admin/CetakRapor.hide') ?></option> 
                    </select>
                </div>
            </div>
            <div class="mt-4 p-3 bg-gray-50 dark:bg-slate-700/50 border border-gray-200 dark:border-slate-600 rounded-xl transition-colors">
                <div class="flex items-center gap-3">
                    <svg class="w-8 h-8 text-[<?= $color['warna_primary'] ?>]" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" /></svg>
                    <div>
                        <p class="text-xs font-bold text-gray-800 dark:text-white"><?= lang('Admin/CetakRapor.print_format') ?></p>
                        <p class="text-[10px] font-medium text-gray-500 dark:text-slate-400 mt-0.5"><?= lang('Admin/CetakRapor.print_format_desc') ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="space-y-6">
        <div class="info-panel bg-white dark:bg-slate-800 rounded-3xl border border-gray-100 dark:border-slate-700 shadow-sm p-6 transition-colors">
            <h3 class="font-bold text-gray-900 dark:text-white mb-5 flex items-center gap-2 transition-colors border-b border-gray-100 dark:border-slate-700 pb-3">
                <svg class="w-5 h-5 text-[<?= $color['warna_primary'] ?>]" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg> 
                <?= lang('Admin/CetakRapor.legal_status') ?>
            </h3>
            <div class="space-y-4">
                <div class="info-row flex justify-between items-center bg-gray-50 dark:bg-slate-700/50 p-3 rounded-lg transition-colors">
                    <span class="text-[11px] font-bold text-gray-600 dark:text-slate-400 uppercase tracking-wider"><?= lang('Admin/CetakRapor.grade_status') ?></span> 
                    <span class="badge-chip badge-locked text-xs font-bold text-gray-800 dark:text-slate-200" id="statusLock">
                        -
                    </span>
                </div>
                <div class="info-row flex justify-between items-center bg-gray-50 dark:bg-slate-700/50 p-3 rounded-lg transition-colors">
                    <span class="text-[11px] font-bold text-gray-600 dark:text-slate-400 uppercase tracking-wider"><?= lang('Admin/CetakRapor.validator') ?></span> 
                    <span class="text-xs font-black text-gray-900 dark:text-white"><?= lang('Admin/CetakRapor.vice_principal') ?></span>
                </div>
                <div class="info-row flex justify-between items-center bg-gray-50 dark:bg-slate-700/50 p-3 rounded-lg transition-colors">
                    <span class="text-[11px] font-bold text-gray-600 dark:text-slate-400 uppercase tracking-wider"><?= lang('Admin/CetakRapor.lock_time') ?></span> 
                    <span class="text-xs font-bold text-gray-900 dark:text-white" id="waktuLock">-</span>
                </div>
                <div class="info-row flex justify-between items-center bg-gray-50 dark:bg-slate-700/50 p-3 rounded-lg transition-colors">
                    <span class="text-[11px] font-bold text-gray-600 dark:text-slate-400 uppercase tracking-wider"><?= lang('Admin/CetakRapor.verification_code') ?></span> 
                    <span class="text-xs font-mono font-black text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-900/30 px-2 py-0.5 rounded border border-emerald-200 dark:border-emerald-800/50">AUTO-GEN</span>
                </div>
            </div>
            
            <div class="mt-5 p-3 bg-[<?= $color['warna_secondary'] ?>]/50 dark:bg-emerald-900/20 border border-[<?= $color['warna_primary'] ?>] dark:border-emerald-800/50 rounded-xl hidden transition-colors" id="validMessage">
                <div class="flex items-start gap-3">
                    <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    <div>
                        <p class="text-xs font-black text-emerald-900 dark:text-emerald-400 mb-0.5"><?= lang('Admin/CetakRapor.verified_data') ?></p>
                        <p class="text-[10px] font-medium text-emerald-800 dark:text-emerald-300"><?= lang('Admin/CetakRapor.verified_desc') ?></p>
                    </div>
                </div>
            </div>
            
            <div class="mt-5 flex items-center justify-center">
                <div class="qr-placeholder flex flex-col items-center justify-center border-2 border-dashed border-gray-300 dark:border-slate-600 bg-gray-50 dark:bg-slate-700/30 p-6 rounded-xl w-full text-center transition-colors">
                    <svg class="w-12 h-12 text-gray-300 dark:text-slate-500 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" /></svg>
                    <span class="text-xs font-bold text-gray-500 dark:text-slate-400 uppercase tracking-wider"><?= lang('Admin/CetakRapor.qr_placeholder') ?></span>
                </div>
            </div>
        </div>

        <div class="settings-card bg-white dark:bg-slate-800 rounded-3xl border border-gray-100 dark:border-slate-700 shadow-sm p-6 transition-colors">
            <h3 class="font-bold text-gray-900 dark:text-white mb-2 text-base flex items-center gap-2 transition-colors">
                <svg class="w-5 h-5 text-[<?= $color['warna_primary'] ?>]" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" /></svg> 
                <?= lang('Admin/CetakRapor.batch_print') ?>
            </h3>
            <p class="text-xs font-medium text-gray-500 dark:text-slate-400 mb-4 leading-relaxed"><?= lang('Admin/CetakRapor.batch_desc') ?></p>
            <button onclick="batchPrint()" class="w-full btn-secondary bg-gray-100 dark:bg-slate-700 border-gray-200 dark:border-slate-600 text-gray-800 dark:text-white hover:bg-gray-200 dark:hover:bg-slate-600 font-bold py-3 rounded-xl flex items-center justify-center gap-2 transition-all outline-none shadow-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" /></svg>
                <span><?= lang('Admin/CetakRapor.btn_batch') ?></span> 
            </button>
        </div>
    </div>
</div>

<div class="no-print mb-12">
    <div class="bg-white dark:bg-slate-800 rounded-3xl border border-gray-100 dark:border-slate-700 shadow-sm p-6 md:p-8 transition-colors">
        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-6 flex items-center gap-2 transition-colors border-b border-gray-100 dark:border-slate-700 pb-4">
            <svg class="w-6 h-6 text-[<?= $color['warna_primary'] ?>]" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" /></svg> 
            <?= lang('Admin/CetakRapor.preview_template') ?>
        </h3>
        
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-5 items-end">
            
            <div class="group cursor-pointer" onclick="showPreview(1)">
                <div class="kertas-thumbnail w-full bg-gradient-to-br from-[<?= $color['warna_primary'] ?>] to-[<?= $color['warna_secondary'] ?>] dark:to-slate-900 rounded-xl flex items-center justify-center text-white shadow-sm border-2 border-transparent group-hover:border-[<?= $color['warna_primary'] ?>] group-hover:shadow-[<?= $color['warna_primary'] ?>]/30 transition-all duration-500 ease-in-out transform group-hover:-translate-y-1" style="aspect-ratio: 1 / 1.414;">
                    <div class="text-center">
                        <svg class="w-8 h-8 mx-auto mb-2 opacity-80" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
                        <p class="text-[10px] font-black uppercase tracking-widest drop-shadow-sm"><?= lang('Admin/CetakRapor.cover') ?></p>
                    </div>
                </div>
                <div class="text-center mt-3"><span class="inline-block bg-gray-100 dark:bg-slate-700 text-gray-600 dark:text-slate-300 font-bold text-[10px] px-3 py-1 rounded-full group-hover:bg-[<?= $color['warna_primary'] ?>] group-hover:text-white transition-colors"><?= lang('Admin/CetakRapor.page_1') ?></span></div>
            </div>

            <div class="group cursor-pointer" onclick="showPreview(2)">
                <div class="kertas-thumbnail w-full bg-white dark:bg-slate-700 p-3 flex flex-col gap-2 rounded-xl shadow-sm border-2 border-gray-200 dark:border-slate-600 group-hover:border-[<?= $color['warna_primary'] ?>] group-hover:shadow-[<?= $color['warna_primary'] ?>]/30 transition-all duration-500 ease-in-out transform group-hover:-translate-y-1" style="aspect-ratio: 1 / 1.414;">
                    <div class="w-full h-3 bg-gray-200 dark:bg-slate-500 rounded-sm"></div>
                    <div class="space-y-1 mt-1">
                        <div class="h-1 bg-gray-200 dark:bg-slate-600 w-3/4 rounded-sm"></div>
                        <div class="h-1 bg-gray-200 dark:bg-slate-600 w-1/2 rounded-sm"></div>
                    </div>
                    <div class="mt-3 w-full h-3 bg-gray-200 dark:bg-slate-500 rounded-sm"></div>
                    <div class="space-y-1 mt-1">
                        <div class="h-1 bg-gray-200 dark:bg-slate-600 w-full rounded-sm"></div>
                        <div class="h-1 bg-gray-200 dark:bg-slate-600 w-2/3 rounded-sm"></div>
                    </div>
                </div>
                <div class="text-center mt-3"><span class="inline-block bg-gray-100 dark:bg-slate-700 text-gray-600 dark:text-slate-300 font-bold text-[10px] px-3 py-1 rounded-full group-hover:bg-[<?= $color['warna_primary'] ?>] group-hover:text-white transition-colors"><?= lang('Admin/CetakRapor.identity') ?></span></div>
            </div>

            <div class="group cursor-pointer" onclick="showPreview(3)">
                <div class="kertas-thumbnail w-full bg-white dark:bg-slate-700 p-3 rounded-xl shadow-sm border-2 border-gray-200 dark:border-slate-600 group-hover:border-[<?= $color['warna_primary'] ?>] group-hover:shadow-[<?= $color['warna_primary'] ?>]/30 transition-all duration-500 ease-in-out transform group-hover:-translate-y-1" style="aspect-ratio: 1 / 1.414;">
                    <div class="w-1/2 h-2 bg-[<?= $color['warna_primary'] ?>]/50 mb-3 rounded-sm"></div>
                    <table class="w-full opacity-30 text-[3px] border-collapse border border-gray-400 dark:border-slate-500">
                        <tr class="bg-gray-200 dark:bg-slate-500"><td class="border border-gray-400 p-1"></td><td class="border border-gray-400 p-1"></td><td class="border border-gray-400 p-1"></td></tr>
                        <tr><td class="border border-gray-400 p-1"></td><td class="border border-gray-400 p-1"></td><td class="border border-gray-400 p-1"></td></tr>
                        <tr><td class="border border-gray-400 p-1"></td><td class="border border-gray-400 p-1"></td><td class="border border-gray-400 p-1"></td></tr>
                        <tr><td class="border border-gray-400 p-1"></td><td class="border border-gray-400 p-1"></td><td class="border border-gray-400 p-1"></td></tr>
                        <tr><td class="border border-gray-400 p-1"></td><td class="border border-gray-400 p-1"></td><td class="border border-gray-400 p-1"></td></tr>
                    </table>
                </div>
                <div class="text-center mt-3"><span class="inline-block bg-gray-100 dark:bg-slate-700 text-gray-600 dark:text-slate-300 font-bold text-[10px] px-3 py-1 rounded-full group-hover:bg-[<?= $color['warna_primary'] ?>] group-hover:text-white transition-colors"><?= lang('Admin/CetakRapor.academic') ?></span></div>
            </div>

            <div class="group cursor-pointer" onclick="showPreview(4)">
                <div class="kertas-thumbnail w-full bg-white dark:bg-slate-700 p-3 rounded-xl shadow-sm border-2 border-gray-200 dark:border-slate-600 group-hover:border-[<?= $color['warna_primary'] ?>] group-hover:shadow-[<?= $color['warna_primary'] ?>]/30 transition-all duration-500 ease-in-out transform group-hover:-translate-y-1" style="aspect-ratio: 1 / 1.414;">
                    <div class="w-2/3 h-2 bg-blue-200 dark:bg-blue-400/50 mb-3 rounded-sm"></div>
                    <div class="border border-gray-300 dark:border-slate-500 h-8 mb-3 rounded-sm bg-gray-100 dark:bg-slate-600"></div>
                    <div class="w-2/3 h-2 bg-blue-200 dark:bg-blue-400/50 mb-3 rounded-sm"></div>
                    <div class="border border-gray-300 dark:border-slate-500 h-8 rounded-sm bg-gray-100 dark:bg-slate-600"></div>
                </div>
                <div class="text-center mt-3"><span class="inline-block bg-gray-100 dark:bg-slate-700 text-gray-600 dark:text-slate-300 font-bold text-[10px] px-3 py-1 rounded-full group-hover:bg-[<?= $color['warna_primary'] ?>] group-hover:text-white transition-colors"><?= lang('Admin/CetakRapor.extracurricular') ?></span></div>
            </div>

            <div class="group cursor-pointer" onclick="showPreview(5)">
                <div class="kertas-thumbnail w-full bg-white dark:bg-slate-700 p-3 flex flex-col justify-center rounded-xl shadow-sm border-2 border-gray-200 dark:border-slate-600 group-hover:border-[<?= $color['warna_primary'] ?>] group-hover:shadow-[<?= $color['warna_primary'] ?>]/30 transition-all duration-500 ease-in-out transform group-hover:-translate-y-1" style="aspect-ratio: 1 / 1.414;">
                    <div class="bg-amber-100/50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800/50 p-1.5 rounded h-12 mb-2">
                        <div class="h-1 bg-amber-300 dark:bg-amber-600 w-1/2 mb-1.5 rounded-sm"></div>
                        <div class="h-0.5 bg-gray-300 dark:bg-slate-500 w-full mb-1"></div>
                        <div class="h-0.5 bg-gray-300 dark:bg-slate-500 w-full"></div>
                    </div>
                    <div class="grid grid-cols-3 gap-1 text-[4px] font-bold text-center mt-auto">
                        <div class="border border-gray-300 dark:border-slate-500 p-1 bg-gray-100 dark:bg-slate-600 text-gray-400">S</div>
                        <div class="border border-gray-300 dark:border-slate-500 p-1 bg-gray-100 dark:bg-slate-600 text-gray-400">I</div>
                        <div class="border border-gray-300 dark:border-slate-500 p-1 bg-gray-100 dark:bg-slate-600 text-gray-400">A</div>
                    </div>
                </div>
                <div class="text-center mt-3"><span class="inline-block bg-gray-100 dark:bg-slate-700 text-gray-600 dark:text-slate-300 font-bold text-[10px] px-3 py-1 rounded-full group-hover:bg-[<?= $color['warna_primary'] ?>] group-hover:text-white transition-colors"><?= lang('Admin/CetakRapor.attendance') ?></span></div>
            </div>

            <div class="group cursor-pointer" onclick="showPreview(6)">
                <div class="kertas-thumbnail w-full bg-white dark:bg-slate-700 p-3 rounded-xl shadow-sm border-2 border-gray-200 dark:border-slate-600 group-hover:border-[<?= $color['warna_primary'] ?>] group-hover:shadow-[<?= $color['warna_primary'] ?>]/30 transition-all duration-500 ease-in-out transform group-hover:-translate-y-1" style="aspect-ratio: 1 / 1.414;">
                    <div class="w-1/2 h-2 bg-purple-200 dark:bg-purple-500/50 mb-4 rounded-sm"></div>
                    <div class="space-y-2">
                        <div class="flex items-center gap-1.5"><div class="w-3 h-3 bg-gray-200 dark:bg-slate-500 rounded-full"></div><div class="flex-1 h-2 bg-gray-100 dark:bg-slate-600 rounded-sm"></div></div>
                        <div class="flex items-center gap-1.5"><div class="w-3 h-3 bg-gray-200 dark:bg-slate-500 rounded-full"></div><div class="flex-1 h-2 bg-gray-100 dark:bg-slate-600 rounded-sm"></div></div>
                        <div class="flex items-center gap-1.5"><div class="w-3 h-3 bg-gray-200 dark:bg-slate-500 rounded-full"></div><div class="flex-1 h-2 bg-gray-100 dark:bg-slate-600 rounded-sm"></div></div>
                    </div>
                </div>
                <div class="text-center mt-3"><span class="inline-block bg-gray-100 dark:bg-slate-700 text-gray-600 dark:text-slate-300 font-bold text-[10px] px-3 py-1 rounded-full group-hover:bg-[<?= $color['warna_primary'] ?>] group-hover:text-white transition-colors"><?= lang('Admin/CetakRapor.attitude') ?></span></div>
            </div>

        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('modals') ?>

<div id="modalInputRapor" class="fixed inset-0 z-[100000] hidden overflow-y-auto bg-gray-950/80 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-2xl w-full max-w-2xl mx-auto border border-transparent dark:border-slate-700 transition-colors transform overflow-hidden">
        <div class="bg-[<?= $color['warna_primary'] ?>] px-6 py-5 flex justify-between items-center text-white">
            <h3 class="font-bold text-lg flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                <?= lang('Admin/CetakRapor.modal_title') ?>
            </h3>
            <button onclick="closeInputModal()" class="hover:bg-white/20 p-2 rounded-full transition-colors outline-none">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <div class="p-6 md:p-8 space-y-5 custom-scrollbar max-h-[70vh] overflow-y-auto">
            <div>
                <label class="block text-sm font-bold text-gray-700 dark:text-slate-300 mb-2"><?= lang('Admin/CetakRapor.foreword') ?></label>
                <textarea id="inputPengantar" rows="3" class="w-full bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-xl p-3 text-sm text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-[<?= $color['warna_primary'] ?>] transition-colors resize-none shadow-sm outline-none"><?= lang('Admin/CetakRapor.foreword_default') ?></textarea>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 dark:text-slate-300 mb-2"><?= lang('Admin/CetakRapor.homeroom_note') ?></label>
                        <textarea id="inputCatatanWali" rows="4" class="w-full bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-xl p-3 text-sm text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-[<?= $color['warna_primary'] ?>] transition-colors resize-none shadow-sm outline-none placeholder-gray-400 dark:placeholder-slate-400" placeholder="<?= lang('Admin/CetakRapor.homeroom_ph') ?>"></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 dark:text-slate-300 mb-2"><?= lang('Admin/CetakRapor.academic_decision') ?></label>
                        <select id="inputKenaikan" class="w-full bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-xl p-3 text-sm text-gray-900 dark:text-white font-bold focus:outline-none focus:ring-2 focus:ring-[<?= $color['warna_primary'] ?>] transition-colors appearance-none shadow-sm outline-none cursor-pointer">
                            <option value="NAIK KE KELAS : VIII (DELAPAN)"><?= lang('Admin/CetakRapor.promo_8') ?></option>
                            <option value="NAIK KE KELAS : IX (SEMBILAN)"><?= lang('Admin/CetakRapor.promo_9') ?></option>
                            <option value="TINGGAL DI KELAS : VII (TUJUH)"><?= lang('Admin/CetakRapor.retain_7') ?></option>
                            <option value="LULUS"><?= lang('Admin/CetakRapor.graduated') ?></option>
                        </select>
                    </div>
                </div>

                <div class="bg-amber-50 dark:bg-amber-900/20 p-5 rounded-2xl border border-amber-200 dark:border-amber-800/50 shadow-sm transition-colors">
                    <label class="block text-sm font-black text-amber-900 dark:text-amber-400 mb-3 border-b border-amber-200 dark:border-amber-800 pb-2 uppercase tracking-wider">
                        <?= lang('Admin/CetakRapor.input_absence') ?>
                    </label>
                    <div class="grid grid-cols-3 gap-3 mb-2">
                        <div>
                            <label class="block text-[11px] font-bold text-amber-800 dark:text-amber-300 uppercase tracking-widest mb-1.5"><?= lang('Admin/CetakRapor.sick') ?></label>
                            <input type="number" id="inputSakit" value="0" min="0" class="w-full border border-amber-300 dark:border-amber-600 bg-white dark:bg-slate-800 text-gray-900 dark:text-white rounded-lg p-2 text-center font-bold focus:ring-2 focus:ring-amber-500 outline-none shadow-sm transition-colors">
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold text-amber-800 dark:text-amber-300 uppercase tracking-widest mb-1.5"><?= lang('Admin/CetakRapor.excused') ?></label>
                            <input type="number" id="inputIzin" value="0" min="0" class="w-full border border-amber-300 dark:border-amber-600 bg-white dark:bg-slate-800 text-gray-900 dark:text-white rounded-lg p-2 text-center font-bold focus:ring-2 focus:ring-amber-500 outline-none shadow-sm transition-colors">
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold text-red-600 dark:text-red-400 uppercase tracking-widest mb-1.5"><?= lang('Admin/CetakRapor.unexcused') ?></label>
                            <input type="number" id="inputAlpha" value="0" min="0" class="w-full border border-red-300 dark:border-red-600 bg-white dark:bg-slate-800 text-red-700 dark:text-red-400 rounded-lg p-2 text-center font-bold focus:ring-2 focus:ring-red-500 outline-none shadow-sm transition-colors">
                        </div>
                    </div>
                    <p class="text-[10px] font-medium text-amber-700 dark:text-amber-500 mt-3 leading-relaxed"><?= lang('Admin/CetakRapor.absence_note') ?></p>
                </div>
            </div>
        </div>
        <div class="px-6 py-5 bg-gray-50 dark:bg-slate-900/50 border-t border-gray-100 dark:border-slate-700 flex flex-col sm:flex-row justify-end gap-3 transition-colors">
            <button type="button" onclick="closeInputModal()" class="w-full sm:w-auto px-6 py-3 border-2 border-gray-300 dark:border-slate-600 rounded-xl text-gray-700 dark:text-slate-300 hover:bg-gray-100 dark:hover:bg-slate-700 font-bold text-sm outline-none shadow-sm transition-colors">
                <?= lang('Admin/CetakRapor.btn_cancel') ?>
            </button>
            <button type="button" onclick="simpanDanCetak('preview')" class="w-full sm:w-auto px-6 py-3 bg-blue-600 text-white rounded-xl hover:bg-blue-700 font-bold text-sm flex items-center justify-center gap-2 outline-none shadow-lg shadow-blue-600/30 transform transition-transform hover:-translate-y-0.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                <?= lang('Admin/CetakRapor.btn_save_preview') ?>
            </button>
            <button type="button" onclick="simpanDanCetak('download')" class="w-full sm:w-auto px-6 py-3 bg-[<?= $color['warna_primary'] ?>] text-white rounded-xl hover:brightness-95 font-bold text-sm flex items-center justify-center gap-2 outline-none shadow-lg transform transition-transform hover:-translate-y-0.5" style="box-shadow: 0 10px 15px -3px <?= $color['warna_primary'] ?>40;">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" /></svg>
                <?= lang('Admin/CetakRapor.btn_save_download') ?>
            </button>
        </div>
    </div>
</div>

<div id="modalPreviewKertas" class="fixed inset-0 z-[100000] hidden overflow-y-auto bg-gray-950/80 backdrop-blur-sm flex items-center justify-center p-4 transition-opacity">
    <div class="bg-gray-200 dark:bg-slate-900 rounded-2xl shadow-2xl w-full max-w-4xl mx-auto overflow-hidden flex flex-col h-[90vh]">
        <div class="bg-white dark:bg-slate-800 px-6 py-4 flex justify-between items-center border-b border-gray-300 dark:border-slate-700">
            <h3 class="font-bold text-lg text-gray-800 dark:text-white flex items-center gap-2">
                <svg class="w-5 h-5 text-[<?= $color['warna_primary'] ?>]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                Preview Simulasi Layout Rapor
            </h3>
            <button onclick="closePreviewKertas()" class="text-gray-500 hover:text-red-500 p-2 rounded-full transition-colors outline-none">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <div class="flex-1 overflow-y-auto p-8 flex justify-center items-start bg-gray-200 dark:bg-slate-900 custom-scrollbar">
            <div id="kertasSimulasi" class="bg-white shadow-xl transition-all duration-500 ease-in-out relative border border-gray-300" style="width: 21cm; height: 29.7cm; padding: 2cm;">
                <div class="w-full h-full border-2 border-dashed border-gray-300 flex flex-col items-center justify-center p-4 transition-all duration-500">
                    <h2 class="text-2xl font-bold text-gray-800 text-center uppercase tracking-widest mb-4 opacity-50" id="teksHalaman">HALAMAN RAPOR</h2>
                    <div class="w-3/4 h-2 bg-gray-200 rounded mb-2"></div>
                    <div class="w-1/2 h-2 bg-gray-200 rounded mb-8"></div>
                    <div class="w-full flex-1 border border-gray-200 rounded bg-gray-50 flex items-center justify-center">
                        <p class="text-sm font-mono text-gray-400 text-center" id="infoKertas">
                            Ukuran: A4 Potrait<br>
                            Margin: Standar<br>
                            Skala: 100%
                        </p>
                    </div>
                </div>
                <div id="nomorHalaman" class="absolute bottom-4 right-8 text-xs font-mono text-gray-500 font-bold">1</div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    var API_URL = "<?= base_url('admin/cetak-rapor') ?>";
    window.LANG = {
        js_loading: "<?= lang('Admin/CetakRapor.js_loading') ?: 'Memuat data...' ?>",
        js_select_room: "<?= lang('Admin/CetakRapor.js_select_room') ?: '-- Pilih Rombel Terlebih Dahulu --' ?>",
        js_select_student: "<?= lang('Admin/CetakRapor.js_select_student') ?: '-- Pilih Siswa --' ?>",
        js_no_student: "<?= lang('Admin/CetakRapor.js_no_student') ?: 'Tidak ada siswa di rombel ini' ?>",
        js_locked: "<?= lang('Admin/CetakRapor.js_locked') ?: 'Terkunci' ?>",
        js_unlocked: "<?= lang('Admin/CetakRapor.js_unlocked') ?: 'Terbuka' ?>",
        js_ready_print: "<?= lang('Admin/CetakRapor.js_ready_print') ?: 'Siap Cetak' ?>",
        js_valid_report: "<?= lang('Admin/CetakRapor.js_valid_report') ?: 'Rapor siswa ini valid.' ?>",
        js_warning: "<?= lang('Admin/CetakRapor.js_warning') ?: 'Peringatan' ?>",
        js_unlocked_val: "<?= lang('Admin/CetakRapor.js_unlocked_val') ?: 'Nilai belum dikunci.' ?>",
        js_swal_no_room: "<?= lang('Admin/CetakRapor.js_swal_no_room') ?: 'Rombel Belum Dipilih' ?>",
        js_swal_room_desc: "<?= lang('Admin/CetakRapor.js_swal_room_desc') ?: 'Silakan pilih Tingkat dan Rombel terlebih dahulu pada filter di atas.' ?>",
        js_swal_no_stu: "<?= lang('Admin/CetakRapor.js_swal_no_stu') ?: 'Siswa Belum Dipilih' ?>",
        js_swal_stu_desc: "<?= lang('Admin/CetakRapor.js_swal_stu_desc') ?: 'Mohon pilih spesifik satu siswa untuk dicetak rapornya.' ?>",
        js_swal_stu_tip: "<?= lang('Admin/CetakRapor.js_swal_stu_tip') ?: 'Tips: Pilih nama siswa di dropdown sebelah kanan.' ?>",
        js_swal_saving: "<?= lang('Admin/CetakRapor.js_swal_saving') ?: 'Menyimpan Data...' ?>",
        js_swal_wait: "<?= lang('Admin/CetakRapor.js_swal_wait') ?: 'Mohon tunggu sebentar' ?>",
        js_swal_fail: "<?= lang('Admin/CetakRapor.js_swal_fail') ?: 'Gagal' ?>",
        js_swal_fail_desc: "<?= lang('Admin/CetakRapor.js_swal_fail_desc') ?: 'Gagal menyimpan.' ?>",
        js_swal_err: "<?= lang('Admin/CetakRapor.js_swal_err') ?: 'Error' ?>",
        js_swal_err_desc: "<?= lang('Admin/CetakRapor.js_swal_err_desc') ?: 'Terjadi kesalahan sistem.' ?>"
    };
</script>
<script src="<?= base_url('assets/js/Admin/cetak-rapor.js') ?>?v=<?= time() ?>"></script>
<?= $this->endSection() ?>