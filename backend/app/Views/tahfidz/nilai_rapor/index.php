<?= $this->extend('layout/main') ?>

<?= $this->section('title') ?>
  <?= lang('Tahfidz/NilaiRapor.page_title') ?>
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<meta name="X-CSRF-TOKEN" content="<?= csrf_hash() ?>">
<style>
    /* ... (Style tetap sama seperti yang Anda buat) ... */
    :root {
        --warna-utama: <?= $color['warna_primary'] ?>;
        --warna-transparan: <?= $color['warna_primary'] ?>20;
        --warna-bayangan: <?= $color['warna_primary'] ?>50;
    }
    .text-dinamis { color: var(--warna-utama) !important; }
    .bg-dinamis { background-color: var(--warna-utama) !important; }
    .bg-dinamis-light { background-color: var(--warna-transparan) !important; }
    .border-dinamis { border-color: var(--warna-utama) !important; }
    .shadow-dinamis { box-shadow: 0 8px 20px var(--warna-bayangan) !important; }
    .glow-dinamis { box-shadow: 0 0 25px var(--warna-bayangan) !important; }
    .hover-bg-dinamis:hover { filter: brightness(0.9) !important; transform: translateY(-2px); }
    .focus-ring-dinamis:focus {
        --tw-ring-color: var(--warna-utama) !important;
        border-color: var(--warna-utama) !important;
    }
    .custom-table-scroll::-webkit-scrollbar { height: 8px; width: 8px; }
    .custom-table-scroll::-webkit-scrollbar-track { background: #f8fafc; border-radius: 8px; }
    .custom-table-scroll::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 8px; }
    .custom-table-scroll::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="mb-10 relative">
    
    <div class="flex items-center gap-2 text-sm text-slate-500 dark:text-slate-400 mb-5 font-medium tracking-wide">
        <span><?= lang('Tahfidz/NilaiRapor.breadcrumb_tahfidz') ?></span>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
        <span class="font-bold text-[<?= $color['warna_primary'] ?>] dark:text-[<?= $color['warna_primary'] ?>]"><?= lang('Tahfidz/NilaiRapor.breadcrumb_rapor') ?></span>
    </div>

    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div>
            <h1 class="text-3xl md:text-4xl font-bold text-slate-900 dark:text-white mb-2 tracking-tight"><?= lang('Tahfidz/NilaiRapor.title_input') ?></h1>
            <p class="text-slate-500 dark:text-slate-400 text-sm md:text-base"><?= lang('Tahfidz/NilaiRapor.subtitle_input') ?> <span class="font-semibold text-slate-700 dark:text-slate-300"><?= lang('Tahfidz/NilaiRapor.ai_magic') ?></span>.</p>
        </div>
        <div class="bg-white dark:bg-slate-800 px-5 py-2.5 rounded-2xl border border-slate-200/60 dark:border-slate-700 shadow-sm flex items-center gap-3">
            <div class="w-2.5 h-2.5 rounded-full bg-red-500 animate-pulse shadow-[0_0_8px_rgba(239,68,68,0.6)]"></div>
            <span class="text-sm font-bold text-slate-700 dark:text-slate-300 tracking-wide" id="realtimeClock"><?= date('H:i') ?></span>
        </div>
    </div>

    <div class="bg-white dark:bg-slate-800 rounded-3xl border border-slate-200/60 dark:border-slate-700 shadow-sm p-6 md:p-8 mb-8 relative overflow-hidden group">
        <div class="absolute right-0 top-0 w-40 h-40 bg-[<?= $color['warna_primary'] ?>]/10 dark:bg-[<?= $color['warna_primary'] ?>]/5 rounded-bl-[100px] transition-transform group-hover:scale-110 duration-700"></div>
        
        <div class="flex flex-col md:flex-row gap-6 items-end relative z-10">
            <div class="w-full md:w-5/12">
                <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-2.5 flex items-center gap-2">
                    <svg class="w-4 h-4 text-[<?= $color['warna_primary'] ?>]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    <?= lang('Tahfidz/NilaiRapor.select_class') ?>
                </label>
                <select id="kelasSelect" class="w-full px-5 py-3.5 text-sm font-semibold text-slate-700 dark:text-slate-200 border border-slate-200 dark:border-slate-600 rounded-xl focus:ring-2 focus:ring-[<?= $color['warna_primary'] ?>] bg-slate-50 dark:bg-slate-900 hover:bg-white dark:hover:bg-slate-700 transition-all cursor-pointer shadow-inner">
                    <option value=""><?= lang('Tahfidz/NilaiRapor.ph_select_class') ?></option>
                    <?php foreach($rombels as $r): ?>
                        <option value="<?= $r['id'] ?>"><?= esc($r['nama_rombel']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="w-full md:w-4/12">
                <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-2.5 flex items-center gap-2">
                    <svg class="w-4 h-4 text-[<?= $color['warna_primary'] ?>]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    <?= lang('Tahfidz/NilaiRapor.select_semester') ?>
                </label>
                <select id="semesterSelect" class="w-full px-5 py-3.5 text-sm font-semibold text-slate-700 dark:text-slate-200 border border-slate-200 dark:border-slate-600 rounded-xl focus:ring-2 focus:ring-[<?= $color['warna_primary'] ?>] bg-slate-50 dark:bg-slate-900 hover:bg-white dark:hover:bg-slate-700 transition-all cursor-pointer shadow-inner">
                    <option value="Ganjil"><?= lang('Tahfidz/NilaiRapor.sem_ganjil') ?></option>
                    <option value="Genap"><?= lang('Tahfidz/NilaiRapor.sem_genap') ?></option>
                </select>
            </div>
            <div class="w-full md:w-3/12 flex-shrink-0">
                <button type="button" onclick="loadSiswa()" class="w-full py-3.5 px-6 text-white rounded-xl font-bold flex items-center justify-center gap-2 transition-all transform hover:-translate-y-1 shadow-md hover:shadow-lg" style="background-color: <?= $color['warna_primary'] ?>;">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <?= lang('Tahfidz/NilaiRapor.btn_open_sheet') ?>
                </button>
            </div>
        </div>
    </div>

    <div id="emptyState" class="bg-white dark:bg-slate-800 rounded-3xl border border-slate-200/60 dark:border-slate-700 shadow-sm p-20 text-center transition-all duration-300 mb-8">
        <div class="w-20 h-20 bg-[<?= $color['warna_primary'] ?>]/10 dark:bg-[<?= $color['warna_primary'] ?>]/20 rounded-2xl rotate-3 flex items-center justify-center mx-auto mb-6 border border-white dark:border-slate-700 animate-pulse">
            <svg class="w-10 h-10 text-[<?= $color['warna_primary'] ?>]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
        </div>
        <h3 class="text-xl font-bold text-slate-800 dark:text-white mb-2 tracking-tight"><?= lang('Tahfidz/NilaiRapor.empty_area_title') ?></h3>
        <p class="text-slate-500 dark:text-slate-400 text-sm max-w-sm mx-auto"><?= lang('Tahfidz/NilaiRapor.empty_area_desc') ?></p>
    </div>

    <div class="bg-white dark:bg-slate-800 rounded-3xl border border-slate-200/60 dark:border-slate-700 shadow-xl overflow-hidden mb-10 transition-all duration-500" id="tableContainer" style="display: none;">
        
        <div class="p-6 md:p-8 border-b border-slate-100 dark:border-slate-700 bg-white dark:bg-slate-800 flex flex-col xl:flex-row justify-between items-start xl:items-center gap-6">
            
            <div class="w-full xl:w-5/12">
                <h3 class="font-extrabold text-slate-900 dark:text-white text-lg flex items-center gap-2 tracking-tight">
                    <svg class="w-6 h-6 text-[<?= $color['warna_primary'] ?>]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <?= lang('Tahfidz/NilaiRapor.sheet_title') ?> <span id="infoKelas" class="text-[<?= $color['warna_primary'] ?>] ml-1"></span>
                </h3>
                
                <div class="mt-4 bg-slate-50 dark:bg-slate-900/50 p-4 rounded-2xl border border-slate-100 dark:border-slate-700">
                    <div class="flex justify-between items-end mb-2">
                        <span class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest"><?= lang('Tahfidz/NilaiRapor.progress_title') ?></span>
                        <span class="text-sm font-black text-[<?= $color['warna_primary'] ?>]" id="progressText">0% (0/0)</span>
                    </div>
                    <div class="w-full bg-slate-200/80 dark:bg-slate-700 rounded-full h-2.5 overflow-hidden">
                        <div class="h-2.5 rounded-full transition-all duration-700 ease-out relative" id="progressBar" style="width: 0%; background-color: <?= $color['warna_primary'] ?>;">
                            <div class="absolute top-0 left-0 bottom-0 right-0 bg-gradient-to-r from-transparent to-white/30"></div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="w-full xl:w-auto flex flex-col sm:flex-row items-center gap-4">
                <div class="hidden md:flex items-center gap-3 pr-4 border-r border-slate-200 dark:border-slate-700">
                    <div class="text-right">
                        <p class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest"><?= lang('Tahfidz/NilaiRapor.ready_to_print') ?></p>
                        <p class="text-sm font-bold text-slate-700 dark:text-slate-300" id="statusCetak"><?= lang('Tahfidz/NilaiRapor.status_waiting') ?></p>
                    </div>
                </div>
                
                <button type="button" onclick="autoFillDeskripsi()" class="w-full sm:w-auto px-6 py-3.5 text-white text-sm font-bold rounded-xl flex items-center justify-center gap-2 transition-all transform hover:-translate-y-0.5 shadow-md hover:shadow-lg" style="background-color: <?= $color['warna_primary'] ?>;">
                    <span class="text-xl">🪄</span>
                    <?= lang('Tahfidz/NilaiRapor.btn_magic_autofill') ?>
                </button>
            </div>
        </div>
        
        <form id="formNilaiRapor" class="relative">
            <div class="max-h-[60vh] overflow-y-auto overflow-x-auto custom-table-scroll bg-slate-50/30 dark:bg-slate-900/20">
                <table class="w-full text-left border-collapse min-w-[950px]">
                    <thead class="sticky top-0 z-20 backdrop-blur-xl bg-white/95 dark:bg-slate-800/95 shadow-sm">
                        <tr class="text-slate-400 dark:text-slate-500 text-[11px] uppercase tracking-widest border-b border-slate-200 dark:border-slate-700">
                            <th class="px-5 py-4 font-extrabold w-12 text-center border-r border-slate-100 dark:border-slate-700"><?= lang('Tahfidz/NilaiRapor.th_no') ?></th>
                            <th class="px-5 py-4 font-extrabold w-72 border-r border-slate-100 dark:border-slate-700"><?= lang('Tahfidz/NilaiRapor.th_profile_context') ?></th>
                            <th class="px-5 py-4 font-extrabold w-48 text-center border-r border-slate-100 dark:border-slate-700"><?= lang('Tahfidz/NilaiRapor.th_predicate') ?></th>
                            <th class="px-5 py-4 font-extrabold"><?= lang('Tahfidz/NilaiRapor.th_narration') ?></th>
                        </tr>
                    </thead>
                    <tbody id="tbodyNilai" class="divide-y divide-slate-100 dark:divide-slate-700 bg-white dark:bg-slate-800">
                        </tbody>
                </table>
            </div>
            
            <div class="p-6 md:p-8 bg-white dark:bg-slate-800 border-t border-slate-200 dark:border-slate-700 flex flex-col md:flex-row justify-between items-center gap-4 shadow-[0_-10px_20px_-5px_rgba(0,0,0,0.03)] relative z-20">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-rose-50 dark:bg-rose-900/30 flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-rose-500 dark:text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    </div>
                    <p class="text-xs text-slate-500 dark:text-slate-400 font-medium leading-relaxed max-w-md">
                        <?= lang('Tahfidz/NilaiRapor.warning_narration') ?> <span class="font-bold text-slate-700 dark:text-slate-300"><?= lang('Tahfidz/NilaiRapor.warning_narration_b') ?></span><?= lang('Tahfidz/NilaiRapor.warning_narration_c') ?>
                    </p>
                </div>
                
                <button type="button" onclick="simpanNilai()" class="w-full md:w-auto py-3.5 px-10 text-white rounded-xl font-bold flex items-center justify-center gap-3 transition-all transform hover:-translate-y-0.5 shadow-md hover:shadow-lg" style="background-color: <?= $color['warna_primary'] ?>;">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                    <?= lang('Tahfidz/NilaiRapor.btn_save_report') ?>
                </button>
            </div>
        </form>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-8">
        
        <div class="lg:col-span-2 bg-white dark:bg-slate-800 rounded-3xl border border-slate-200/60 dark:border-slate-700 shadow-sm p-6 md:p-8 relative overflow-hidden group">
            <div class="absolute -right-6 -bottom-6 w-32 h-32 bg-[<?= $color['warna_primary'] ?>]/10 dark:bg-[<?= $color['warna_primary'] ?>]/5 rounded-full transform group-hover:scale-[2] transition-transform duration-700"></div>
            <div class="absolute -right-12 -bottom-12 w-48 h-48 border border-[<?= $color['warna_primary'] ?>] opacity-10 rounded-full"></div>
            
            <div class="relative z-10">
                <div class="flex items-center justify-between mb-2">
                    <h3 class="font-extrabold text-slate-800 dark:text-white text-lg flex items-center gap-2">
                        <svg class="w-6 h-6 text-[<?= $color['warna_primary'] ?>]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                        <?= lang('Tahfidz/NilaiRapor.guide_title') ?>
                    </h3>
                    <span class="text-[10px] font-bold px-2.5 py-1 bg-amber-100 dark:bg-amber-900/40 text-amber-700 dark:text-amber-400 rounded-full uppercase tracking-wider"><?= lang('Tahfidz/NilaiRapor.guide_badge') ?></span>
                </div>
                <p class="text-sm text-slate-500 dark:text-slate-400 mb-6 font-medium"><?= lang('Tahfidz/NilaiRapor.guide_desc') ?></p>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div class="flex items-start gap-4 p-4 rounded-2xl bg-slate-50 dark:bg-slate-700/50 border border-slate-100 dark:border-slate-700 hover:bg-white dark:hover:bg-slate-700 hover:shadow-md transition-all duration-300">
                        <div class="w-10 h-10 rounded-full bg-white dark:bg-slate-800 flex items-center justify-center flex-shrink-0 shadow-sm text-[<?= $color['warna_primary'] ?>] font-bold">1</div>
                        <div>
                            <h4 class="font-bold text-slate-700 dark:text-white text-sm"><?= lang('Tahfidz/NilaiRapor.guide_1_title') ?></h4>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1.5 leading-relaxed"><?= lang('Tahfidz/NilaiRapor.guide_1_desc') ?></p>
                        </div>
                    </div>
                    
                    <div class="flex items-start gap-4 p-4 rounded-2xl bg-slate-50 dark:bg-slate-700/50 border border-slate-100 dark:border-slate-700 hover:bg-white dark:hover:bg-slate-700 hover:shadow-md transition-all duration-300">
                        <div class="w-10 h-10 rounded-full bg-white dark:bg-slate-800 flex items-center justify-center flex-shrink-0 shadow-sm text-[<?= $color['warna_primary'] ?>] font-bold">2</div>
                        <div>
                            <h4 class="font-bold text-slate-700 dark:text-white text-sm"><?= lang('Tahfidz/NilaiRapor.guide_2_title') ?></h4>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1.5 leading-relaxed"><?= lang('Tahfidz/NilaiRapor.guide_2_desc') ?></p>
                        </div>
                    </div>

                    <div class="flex items-start gap-4 p-4 rounded-2xl bg-slate-50 dark:bg-slate-700/50 border border-slate-100 dark:border-slate-700 hover:bg-white dark:hover:bg-slate-700 hover:shadow-md transition-all duration-300">
                        <div class="w-10 h-10 rounded-full bg-white dark:bg-slate-800 flex items-center justify-center flex-shrink-0 shadow-sm text-[<?= $color['warna_primary'] ?>] font-bold">3</div>
                        <div>
                            <h4 class="font-bold text-slate-700 dark:text-white text-sm"><?= lang('Tahfidz/NilaiRapor.guide_3_title') ?></h4>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1.5 leading-relaxed"><?= lang('Tahfidz/NilaiRapor.guide_3_desc') ?></p>
                        </div>
                    </div>

                    <div class="flex items-start gap-4 p-4 rounded-2xl bg-slate-50 dark:bg-slate-700/50 border border-slate-100 dark:border-slate-700 hover:bg-white dark:hover:bg-slate-700 hover:shadow-md transition-all duration-300 relative overflow-hidden">
                        <div class="w-10 h-10 rounded-full text-white flex items-center justify-center flex-shrink-0 shadow-sm font-bold text-lg relative z-10" style="background-color: <?= $color['warna_primary'] ?>;">🪄</div>
                        <div class="relative z-10">
                            <h4 class="font-bold text-slate-700 dark:text-white text-sm"><?= lang('Tahfidz/NilaiRapor.guide_4_title') ?></h4>
                            <p class="text-xs text-slate-600 dark:text-slate-400 mt-1.5 leading-relaxed"><?= lang('Tahfidz/NilaiRapor.guide_4_desc') ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-gray-100 dark:bg-slate-900 rounded-3xl shadow-xl p-7 relative overflow-hidden ring-1 ring-white/10 dark:ring-white/5 transform hover:scale-[1.02] transition-transform duration-300">
            <div class="absolute -right-8 -bottom-8 w-40 h-40 rounded-full blur-[60px] opacity-40 dark:opacity-20" style="background-color: <?= $color['warna_primary'] ?>;"></div>
            <div class="absolute -left-10 -top-10 w-32 h-32 rounded-full blur-[40px] opacity-20 dark:opacity-10 bg-emerald-400"></div>

            <h3 class="font-bold text-slate-900 dark:text-white text-lg mb-6 flex items-center gap-2 relative z-10">
                <svg class="w-6 h-6 text-[<?= $color['warna_primary'] ?>]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                <?= lang('Tahfidz/NilaiRapor.scale_title') ?>
            </h3>
            
            <div class="space-y-4 relative z-10">
                <div class="flex items-center justify-between border-b border-slate-200 dark:border-slate-800 pb-3">
                    <div class="flex items-center gap-3">
                        <span class="w-8 h-8 rounded-lg bg-emerald-50 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 flex items-center justify-center font-black text-lg border border-emerald-200 dark:border-emerald-800/50">A</span>
                        <span class="text-sm font-semibold text-slate-700 dark:text-slate-300"><?= lang('Tahfidz/NilaiRapor.scale_a_title') ?></span>
                    </div>
                    <span class="text-[10px] font-bold uppercase tracking-wider text-emerald-700 dark:text-emerald-400 bg-emerald-100 dark:bg-emerald-900/30 px-2.5 py-1 rounded"><?= lang('Tahfidz/NilaiRapor.scale_a_badge') ?></span>
                </div>
                
                <div class="flex items-center justify-between border-b border-slate-200 dark:border-slate-800 pb-3">
                    <div class="flex items-center gap-3">
                        <span class="w-8 h-8 rounded-lg bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 flex items-center justify-center font-black text-lg border border-blue-200 dark:border-blue-800/50">B</span>
                        <span class="text-sm font-semibold text-slate-700 dark:text-slate-300"><?= lang('Tahfidz/NilaiRapor.scale_b_title') ?></span>
                    </div>
                    <span class="text-[10px] font-bold uppercase tracking-wider text-blue-700 dark:text-blue-400 bg-blue-100 dark:bg-blue-900/30 px-2.5 py-1 rounded"><?= lang('Tahfidz/NilaiRapor.scale_b_badge') ?></span>
                </div>

                <div class="flex items-center justify-between border-b border-slate-200 dark:border-slate-800 pb-3">
                    <div class="flex items-center gap-3">
                        <span class="w-8 h-8 rounded-lg bg-amber-50 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400 flex items-center justify-center font-black text-lg border border-amber-200 dark:border-amber-800/50">C</span>
                        <span class="text-sm font-semibold text-slate-700 dark:text-slate-300"><?= lang('Tahfidz/NilaiRapor.scale_c_title') ?></span>
                    </div>
                    <span class="text-[10px] font-bold uppercase tracking-wider text-amber-700 dark:text-amber-400 bg-amber-100 dark:bg-amber-900/30 px-2.5 py-1 rounded"><?= lang('Tahfidz/NilaiRapor.scale_c_badge') ?></span>
                </div>

                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <span class="w-8 h-8 rounded-lg bg-rose-50 dark:bg-rose-900/30 text-rose-600 dark:text-rose-400 flex items-center justify-center font-black text-lg border border-rose-200 dark:border-rose-800/50">D</span>
                        <span class="text-sm font-semibold text-slate-700 dark:text-slate-300"><?= lang('Tahfidz/NilaiRapor.scale_d_title') ?></span>
                    </div>
                    <span class="text-[10px] font-bold uppercase tracking-wider text-rose-700 dark:text-rose-400 bg-rose-100 dark:bg-rose-900/30 px-2.5 py-1 rounded"><?= lang('Tahfidz/NilaiRapor.scale_d_badge') ?></span>
                </div>
            </div>
        </div>

    </div>

</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    const LANG = {
        primary_color: "<?= $color['warna_primary'] ?>",
        fetch_url: "<?= base_url('tahfidz/nilai-rapor/get-siswa') ?>",
        save_url: "<?= base_url('tahfidz/nilai-rapor/save') ?>",
        
        alert_title_hi: "<?= lang('Tahfidz/NilaiRapor.js_alert_title_hi') ?>",
        alert_desc_hi: "<?= lang('Tahfidz/NilaiRapor.js_alert_desc_hi') ?>",
        loading_sheet: "<?= lang('Tahfidz/NilaiRapor.js_loading_sheet') ?>",
        no_student: "<?= lang('Tahfidz/NilaiRapor.js_no_student') ?>",
        
        txt_achievement: "<?= lang('Tahfidz/NilaiRapor.js_achievement') ?>",
        txt_not_deposited: "<?= lang('Tahfidz/NilaiRapor.js_not_deposited') ?>",
        txt_times_deposit: "<?= lang('Tahfidz/NilaiRapor.js_times_deposit') ?>",
        ph_narration: "<?= lang('Tahfidz/NilaiRapor.js_ph_narration') ?>",
        err_fetch: "<?= lang('Tahfidz/NilaiRapor.js_err_fetch') ?>",
        
        status_done: '<?= lang('Tahfidz/NilaiRapor.status_done') ?>',
        status_not_yet: '<?= lang('Tahfidz/NilaiRapor.status_not_yet') ?>',
        
        pred_a: "<?= lang('Tahfidz/NilaiRapor.pred_a') ?>",
        pred_b: "<?= lang('Tahfidz/NilaiRapor.pred_b') ?>",
        pred_c: "<?= lang('Tahfidz/NilaiRapor.pred_c') ?>",
        pred_d: "<?= lang('Tahfidz/NilaiRapor.pred_d') ?>",
        
        af_achievement: "<?= lang('Tahfidz/NilaiRapor.af_achievement') ?>",
        af_active: "<?= lang('Tahfidz/NilaiRapor.af_active') ?>",
        af_active_end: "<?= lang('Tahfidz/NilaiRapor.af_active_end') ?>",
        af_inactive: "<?= lang('Tahfidz/NilaiRapor.af_inactive') ?>",
        af_a_text: "<?= lang('Tahfidz/NilaiRapor.af_a_text') ?>",
        af_a_end: "<?= lang('Tahfidz/NilaiRapor.af_a_end') ?>",
        af_b_text: "<?= lang('Tahfidz/NilaiRapor.af_b_text') ?>",
        af_b_end: "<?= lang('Tahfidz/NilaiRapor.af_b_end') ?>",
        af_c_text: "<?= lang('Tahfidz/NilaiRapor.af_c_text') ?>",
        af_c_end: "<?= lang('Tahfidz/NilaiRapor.af_c_end') ?>",
        af_d_text: "<?= lang('Tahfidz/NilaiRapor.af_d_text') ?>",
        af_d_end: "<?= lang('Tahfidz/NilaiRapor.af_d_end') ?>",
        
        toast_af_title: "<?= lang('Tahfidz/NilaiRapor.js_toast_af_title') ?>",
        toast_af_desc: "<?= lang('Tahfidz/NilaiRapor.js_toast_af_desc') ?>",
        af_full_title: "<?= lang('Tahfidz/NilaiRapor.js_af_full_title') ?>",
        af_full_desc: "<?= lang('Tahfidz/NilaiRapor.js_af_full_desc') ?>",
        
        saving: "<?= lang('Tahfidz/NilaiRapor.js_saving') ?>",
        saving_title: "<?= lang('Tahfidz/NilaiRapor.js_saving_title') ?>",
        saving_desc: "<?= lang('Tahfidz/NilaiRapor.js_saving_desc') ?>",
        success_title: "<?= lang('Tahfidz/NilaiRapor.js_success_title') ?>",
        success_default: "<?= lang('Tahfidz/NilaiRapor.js_success_default') ?>",
        warning_title: "<?= lang('Tahfidz/NilaiRapor.js_warning_title') ?>",
        error_title: "<?= lang('Tahfidz/NilaiRapor.js_error_title') ?>",
        server_error: "<?= lang('Tahfidz/NilaiRapor.js_server_error') ?>",
        server_error_desc: "<?= lang('Tahfidz/NilaiRapor.js_server_error_desc') ?>"
    };
</script>
<script src="<?= base_url('assets/js/Tahfidz/nilai-rapor.js') ?>?v=<?= time() ?>"></script>
<?= $this->endSection() ?>