<?= $this->extend('layout/main') ?>

<?= $this->section('title') ?>
  <?= lang('Tahfidz/Monitoring.page_title') ?>
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
    :root {
        --color-primary: <?= $color['warna_primary'] ?>;
        --color-primary-light: <?= $color['warna_primary'] ?>15;
        --color-primary-hover: <?= $color['warna_primary'] ?>E6;
    }
    .text-primary { color: var(--color-primary) !important; }
    .bg-primary { background-color: var(--color-primary) !important; }
    .bg-primary-light { background-color: var(--color-primary-light) !important; }
    .border-primary { border-color: var(--color-primary) !important; }
    .hover-bg-primary:hover { background-color: var(--color-primary-hover) !important; }
    .ring-primary { --tw-ring-color: var(--color-primary) !important; }
    .glow-primary { box-shadow: 0 4px 20px var(--color-primary-light); }
    
    .custom-table-scroll::-webkit-scrollbar { height: 8px; width: 8px; }
    .custom-table-scroll::-webkit-scrollbar-track { background: #f8fafc; border-radius: 8px; }
    .custom-table-scroll::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 8px; }
    .custom-table-scroll::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="mb-10 relative">
    
    <div class="flex items-center gap-2 text-sm text-slate-500 dark:text-slate-400 mb-5 font-medium tracking-wide">
        <span><?= lang('Tahfidz/Monitoring.breadcrumb_tahfidz') ?></span>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
        <span class="font-bold text-primary"><?= lang('Tahfidz/Monitoring.breadcrumb_monitor') ?></span>
    </div>

    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div>
            <h1 class="text-3xl md:text-4xl font-extrabold text-slate-900 dark:text-white mb-2 tracking-tight"><?= lang('Tahfidz/Monitoring.title_main') ?></h1>
            <p class="text-slate-500 dark:text-slate-400 text-sm md:text-base"><?= lang('Tahfidz/Monitoring.subtitle_main') ?></p>
        </div>
    </div>

    <div class="bg-white dark:bg-slate-800 rounded-3xl border border-slate-200/60 dark:border-slate-700 shadow-sm p-6 mb-8 relative overflow-hidden group">
        <div class="absolute right-0 top-0 w-32 h-32 bg-primary-light rounded-bl-full opacity-50 transition-transform group-hover:scale-110 duration-700"></div>
        <div class="flex justify-between flex-col md:flex-row gap-5 items-end relative z-10">
            <div class="w-full md:w-1/2">
                <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-2.5 flex items-center gap-2">
                    <svg class="w-4 h-4 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    <?= lang('Tahfidz/Monitoring.select_class') ?>
                </label>
                <select id="kelasSelect" class="w-full px-5 py-3 text-sm font-semibold text-slate-700 dark:text-slate-200 border border-slate-200 dark:border-slate-600 rounded-xl focus:ring-2 focus:ring-primary focus:border-primary bg-slate-50 dark:bg-slate-900 hover:bg-white dark:hover:bg-slate-700 transition-all cursor-pointer shadow-inner">
                    <option value=""><?= lang('Tahfidz/Monitoring.ph_select_class') ?></option>
                    <?php foreach($rombels as $r): ?>
                        <option value="<?= $r['id'] ?>"><?= esc($r['nama_rombel']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="w-full md:w-auto flex-shrink-0">
                <button type="button" onclick="loadMonitoring()" class="w-full md:w-auto py-3 px-8 bg-primary hover-bg-primary text-white rounded-xl font-bold flex items-center justify-center gap-2 transition-all shadow-md transform hover:-translate-y-1">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                    <?= lang('Tahfidz/Monitoring.btn_analyze') ?>
                </button>
            </div>
        </div>
    </div>

    <div id="emptyState" class="bg-white dark:bg-slate-800 rounded-3xl border border-slate-200/60 dark:border-slate-700 shadow-sm p-20 text-center transition-all duration-300 mb-8">
        <div class="w-20 h-20 bg-primary-light rounded-full flex items-center justify-center mx-auto mb-6 border border-white dark:border-slate-700 shadow-sm glow-primary animate-pulse">
            <svg class="w-10 h-10 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
        </div>
        <h3 class="text-xl font-bold text-slate-800 dark:text-white mb-2"><?= lang('Tahfidz/Monitoring.empty_area_title') ?></h3>
        <p class="text-slate-500 dark:text-slate-400 text-sm max-w-sm mx-auto"><?= lang('Tahfidz/Monitoring.empty_area_desc') ?></p>
    </div>

    <div class="bg-white dark:bg-slate-800 rounded-3xl border border-slate-200/60 dark:border-slate-700 shadow-xl overflow-hidden relative transition-all duration-500 mb-10" id="tableContainer" style="display: none;">
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-0 border-b border-slate-100 dark:border-slate-700 bg-white dark:bg-slate-800">
            <div class="p-6 md:p-8 border-b md:border-b-0 md:border-r border-slate-100 dark:border-slate-700 flex items-center justify-between group cursor-default">
                <div>
                    <p class="text-[11px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-1.5"><?= lang('Tahfidz/Monitoring.stat_total_students') ?></p>
                    <p class="text-4xl font-black tracking-tight text-primary" id="statTotalSantri">0</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-slate-50 dark:bg-slate-900/50 text-slate-400 dark:text-slate-500 flex items-center justify-center group-hover:scale-110 group-hover:text-primary transition-all ring-1 ring-slate-100 dark:ring-slate-700">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                </div>
            </div>
            <div class="p-6 md:p-8 border-b md:border-b-0 md:border-r border-slate-100 dark:border-slate-700 flex items-center justify-between group cursor-default">
                <div>
                    <p class="text-[11px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-1.5"><?= lang('Tahfidz/Monitoring.stat_total_deposits') ?></p>
                    <p class="text-4xl font-black tracking-tight text-primary" id="statTotalSetoran">0<span class="text-lg text-slate-400 dark:text-slate-500 font-bold ml-1"><?= lang('Tahfidz/Monitoring.stat_times') ?></span></p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-slate-50 dark:bg-slate-900/50 text-slate-400 dark:text-slate-500 flex items-center justify-center group-hover:scale-110 group-hover:text-primary transition-all ring-1 ring-slate-100 dark:ring-slate-700">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                </div>
            </div>
            <div class="p-6 md:p-8 flex items-center justify-between group cursor-default">
                <div>
                    <p class="text-[11px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-1.5"><?= lang('Tahfidz/Monitoring.stat_activity_rate') ?></p>
                    <p class="text-4xl font-black tracking-tight text-primary" id="statKeaktifan">0<span class="text-lg text-slate-400 dark:text-slate-500 font-bold ml-1">%</span></p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-slate-50 dark:bg-slate-900/50 text-slate-400 dark:text-slate-500 flex items-center justify-center group-hover:scale-110 group-hover:text-primary transition-all ring-1 ring-slate-100 dark:ring-slate-700">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                </div>
            </div>
        </div>

        <div class="p-4 md:p-5 border-b border-slate-100 dark:border-slate-700 bg-slate-50/70 dark:bg-slate-900/50 flex flex-col xl:flex-row justify-between items-center gap-4">
            <div class="flex gap-2 overflow-x-auto w-full xl:w-auto pb-2 xl:pb-0 custom-table-scroll scroll-smooth">
                <button onclick="setPredikatFilter('semua', this)" class="btn-predikat active px-5 py-2.5 rounded-full text-xs font-bold transition-all bg-primary text-white shadow-sm ring-2 ring-primary ring-offset-2 dark:ring-offset-slate-800 whitespace-nowrap"><?= lang('Tahfidz/Monitoring.filter_all_predicates') ?></button>
                <button onclick="setPredikatFilter('Sangat Lancar', this)" class="btn-predikat px-5 py-2.5 rounded-full text-xs font-bold transition-all bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700 ring-1 ring-slate-200 dark:ring-slate-600 shadow-sm whitespace-nowrap"><?= lang('Tahfidz/Monitoring.filter_mutqin') ?></button>
                <button onclick="setPredikatFilter('Kurang Lancar', this)" class="btn-predikat px-5 py-2.5 rounded-full text-xs font-bold transition-all bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700 ring-1 ring-slate-200 dark:ring-slate-600 shadow-sm whitespace-nowrap"><?= lang('Tahfidz/Monitoring.filter_attention') ?></button>
                <button onclick="setPredikatFilter('Belum Hafal', this)" class="btn-predikat px-5 py-2.5 rounded-full text-xs font-bold transition-all bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700 ring-1 ring-slate-200 dark:ring-slate-600 shadow-sm whitespace-nowrap"><?= lang('Tahfidz/Monitoring.filter_evaluation') ?></button>
            </div>

            <div class="flex flex-col sm:flex-row w-full xl:w-auto gap-3">
                <div class="relative w-full sm:w-48">
                    <select id="filterKeaktifan" onchange="applyMultiFilter()" class="w-full pl-4 pr-8 py-2.5 text-sm font-bold text-slate-600 dark:text-slate-300 border-0 ring-1 ring-slate-200 dark:ring-slate-600 rounded-full focus:ring-2 focus:ring-primary bg-white dark:bg-slate-800 cursor-pointer shadow-sm appearance-none">
                        <option value="semua"><?= lang('Tahfidz/Monitoring.filter_all_status') ?></option>
                        <option value="aktif"><?= lang('Tahfidz/Monitoring.filter_active') ?></option>
                        <option value="pasif"><?= lang('Tahfidz/Monitoring.filter_passive') ?></option>
                    </select>
                    <div class="absolute inset-y-0 right-0 flex items-center px-3 pointer-events-none text-slate-400 dark:text-slate-500">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </div>
                </div>

                <div class="relative w-full sm:w-64 flex-shrink-0">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg class="w-4 h-4 text-slate-400 dark:text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                    <input type="text" id="searchInput" onkeyup="applyMultiFilter()" placeholder="<?= lang('Tahfidz/Monitoring.ph_search_student') ?>" class="w-full pl-11 pr-4 py-2.5 text-sm font-medium border-0 ring-1 ring-slate-200 dark:ring-slate-600 rounded-full focus:ring-2 focus:ring-primary bg-white dark:bg-slate-800 dark:text-white dark:placeholder-slate-400 transition-all shadow-inner">
                </div>
            </div>
        </div>
        
        <div class="max-h-[60vh] overflow-y-auto overflow-x-auto custom-table-scroll bg-slate-50/20 dark:bg-slate-900/20">
            <table class="w-full text-left border-collapse min-w-[1000px]">
                <thead class="sticky top-0 z-10 backdrop-blur-xl bg-white/95 dark:bg-slate-800/95 shadow-sm">
                    <tr class="text-slate-400 dark:text-slate-500 text-[11px] uppercase tracking-widest border-b border-slate-200 dark:border-slate-700">
                        <th class="px-5 py-4 font-extrabold w-12 text-center border-r border-slate-100 dark:border-slate-700"><?= lang('Tahfidz/Monitoring.th_no') ?></th>
                        <th class="px-5 py-4 font-extrabold w-72 border-r border-slate-100 dark:border-slate-700"><?= lang('Tahfidz/Monitoring.th_student_profile') ?></th>
                        <th class="px-5 py-4 font-extrabold w-48 border-r border-slate-100 dark:border-slate-700"><?= lang('Tahfidz/Monitoring.th_target_semester') ?></th>
                        <th class="px-5 py-4 font-extrabold w-48 border-r border-slate-100 dark:border-slate-700 text-center"><?= lang('Tahfidz/Monitoring.th_deposit_stats') ?></th>
                        <th class="px-5 py-4 font-extrabold w-64 border-r border-slate-100 dark:border-slate-700"><?= lang('Tahfidz/Monitoring.th_last_achievement') ?></th>
                        <th class="px-5 py-4 font-extrabold w-32 text-center"><?= lang('Tahfidz/Monitoring.th_status') ?></th>
                    </tr>
                </thead>
                <tbody id="tbodyMonitoring" class="divide-y divide-slate-100 dark:divide-slate-700 bg-white dark:bg-slate-800">
                    </tbody>
            </table>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-8">
        <div class="lg:col-span-2 bg-white dark:bg-slate-800 rounded-3xl border border-slate-200/60 dark:border-slate-700 shadow-sm p-6 md:p-8 relative overflow-hidden group">
            <div class="absolute -right-10 -top-10 w-40 h-40 bg-primary-light rounded-full opacity-50 transform group-hover:scale-150 transition-transform duration-700"></div>
            
            <div class="relative z-10">
                <div class="flex items-center justify-between mb-2">
                    <h3 class="font-extrabold text-slate-800 dark:text-white text-lg flex items-center gap-2">
                        <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                        <?= lang('Tahfidz/Monitoring.guide_title') ?>
                    </h3>
                </div>
                <p class="text-sm text-slate-500 dark:text-slate-400 mb-6 font-medium"><?= lang('Tahfidz/Monitoring.guide_desc') ?></p>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-5">
                    <div class="flex items-start gap-4 p-5 rounded-2xl bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700 shadow-sm hover:shadow-md hover:border-emerald-200 dark:hover:border-emerald-600 transform hover:-translate-y-1 transition-all">
                        <div class="w-12 h-12 rounded-full bg-emerald-50 dark:bg-emerald-900/30 border border-emerald-100 dark:border-emerald-800/50 flex items-center justify-center flex-shrink-0 shadow-inner">
                            <div class="w-4 h-4 rounded-full bg-emerald-500 shadow-[0_0_10px_rgba(16,185,129,0.5)]"></div>
                        </div>
                        <div>
                            <h4 class="font-bold text-slate-800 dark:text-white text-sm"><?= lang('Tahfidz/Monitoring.guide_fluent') ?> <span class="text-[9px] font-bold text-emerald-700 dark:text-emerald-400 bg-emerald-100 dark:bg-emerald-900/50 px-2 py-0.5 rounded-full ml-1"><?= lang('Tahfidz/Monitoring.guide_mutqin') ?></span></h4>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-2 leading-relaxed"><?= lang('Tahfidz/Monitoring.guide_fluent_desc') ?></p>
                        </div>
                    </div>
                    
                    <div class="flex items-start gap-4 p-5 rounded-2xl bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700 shadow-sm hover:shadow-md hover:border-blue-200 dark:hover:border-blue-600 transform hover:-translate-y-1 transition-all">
                        <div class="w-12 h-12 rounded-full bg-blue-50 dark:bg-blue-900/30 border border-blue-100 dark:border-blue-800/50 flex items-center justify-center flex-shrink-0 shadow-inner">
                            <div class="w-4 h-4 rounded-full bg-blue-500 shadow-[0_0_10px_rgba(59,130,246,0.5)]"></div>
                        </div>
                        <div>
                            <h4 class="font-bold text-slate-800 dark:text-white text-sm"><?= lang('Tahfidz/Monitoring.guide_good') ?></h4>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-2 leading-relaxed"><?= lang('Tahfidz/Monitoring.guide_good_desc') ?></p>
                        </div>
                    </div>
                    
                    <div class="flex items-start gap-4 p-5 rounded-2xl bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700 shadow-sm hover:shadow-md hover:border-amber-200 dark:hover:border-amber-600 transform hover:-translate-y-1 transition-all">
                        <div class="w-12 h-12 rounded-full bg-amber-50 dark:bg-amber-900/30 border border-amber-100 dark:border-amber-800/50 flex items-center justify-center flex-shrink-0 shadow-inner">
                            <div class="w-4 h-4 rounded-full bg-amber-500 shadow-[0_0_10px_rgba(245,158,11,0.5)]"></div>
                        </div>
                        <div>
                            <h4 class="font-bold text-slate-800 dark:text-white text-sm"><?= lang('Tahfidz/Monitoring.guide_poor') ?></h4>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-2 leading-relaxed"><?= lang('Tahfidz/Monitoring.guide_poor_desc') ?></p>
                        </div>
                    </div>
                    
                    <div class="flex items-start gap-4 p-5 rounded-2xl bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700 shadow-sm hover:shadow-md hover:border-rose-200 dark:hover:border-rose-600 transform hover:-translate-y-1 transition-all">
                        <div class="w-12 h-12 rounded-full bg-rose-50 dark:bg-rose-900/30 border border-rose-100 dark:border-rose-800/50 flex items-center justify-center flex-shrink-0 shadow-inner">
                            <div class="w-4 h-4 rounded-full bg-rose-500 shadow-[0_0_10px_rgba(244,63,94,0.5)]"></div>
                        </div>
                        <div>
                            <h4 class="font-bold text-slate-800 dark:text-white text-sm"><?= lang('Tahfidz/Monitoring.guide_eval') ?></h4>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-2 leading-relaxed"><?= lang('Tahfidz/Monitoring.guide_eval_desc') ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex flex-col gap-6">
            <div class="bg-primary rounded-3xl shadow-lg p-7 text-white relative overflow-hidden transform hover:scale-[1.02] transition-transform duration-300 glow-primary">
                <div class="absolute -right-4 -bottom-4 w-32 h-32 bg-white rounded-full blur-[40px] opacity-20"></div>
                <div class="relative z-10">
                    <h3 class="font-bold text-white text-lg mb-1 flex items-center gap-2">
                        <svg class="w-5 h-5 text-yellow-300" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        <?= lang('Tahfidz/Monitoring.target_academic') ?>
                    </h3>
                    <p class="text-xs text-white/80 font-medium mb-5"><?= lang('Tahfidz/Monitoring.target_academic_desc') ?></p>
                    <ul class="space-y-3">
                        <li class="flex items-center justify-between border-b border-white/20 pb-2">
                            <span class="text-sm font-semibold text-white"><?= lang('Tahfidz/Monitoring.class_7') ?></span>
                            <span class="text-[10px] font-bold bg-white/20 px-3 py-1 rounded-lg uppercase"><?= lang('Tahfidz/Monitoring.juz_30') ?></span>
                        </li>
                        <li class="flex items-center justify-between border-b border-white/20 pb-2">
                            <span class="text-sm font-semibold text-white"><?= lang('Tahfidz/Monitoring.class_8') ?></span>
                            <span class="text-[10px] font-bold bg-white/20 px-3 py-1 rounded-lg uppercase"><?= lang('Tahfidz/Monitoring.juz_29') ?></span>
                        </li>
                        <li class="flex items-center justify-between">
                            <span class="text-sm font-semibold text-white"><?= lang('Tahfidz/Monitoring.class_9') ?></span>
                            <span class="text-[10px] font-bold bg-white/20 px-3 py-1 rounded-lg uppercase"><?= lang('Tahfidz/Monitoring.juz_28') ?></span>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="bg-white dark:bg-slate-800 rounded-3xl border border-slate-200/60 dark:border-slate-700 shadow-sm p-7 relative">
                <h3 class="font-bold text-slate-800 dark:text-white text-base mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <?= lang('Tahfidz/Monitoring.quick_action_title') ?>
                </h3>
                <div class="space-y-4">
                    <div class="pl-4 border-l-2 border-amber-400 relative">
                        <div class="absolute -left-1 top-1 w-2 h-2 rounded-full bg-amber-400 ring-2 ring-white dark:ring-slate-800"></div>
                        <p class="text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-widest mb-1"><?= lang('Tahfidz/Monitoring.qa_poor_title') ?></p>
                        <p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed font-medium"><?= lang('Tahfidz/Monitoring.qa_poor_desc') ?></p>
                    </div>
                    <div class="pl-4 border-l-2 border-rose-400 relative">
                        <div class="absolute -left-1 top-1 w-2 h-2 rounded-full bg-rose-400 ring-2 ring-white dark:ring-slate-800"></div>
                        <p class="text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-widest mb-1"><?= lang('Tahfidz/Monitoring.qa_passive_title') ?></p>
                        <p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed font-medium"><?= lang('Tahfidz/Monitoring.qa_passive_desc') ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="modalRiwayat" class="fixed inset-0 z-[100] hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div id="modalBackdrop" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity opacity-0 duration-300" onclick="tutupModal()"></div>

        <div class="fixed inset-0 z-10 overflow-y-auto">
            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <div id="modalPanel" class="relative transform overflow-hidden rounded-[2rem] bg-slate-50 dark:bg-slate-800 text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-2xl opacity-0 translate-y-8 sm:translate-y-0 sm:scale-95 duration-300">
                    
                    <div class="bg-primary relative overflow-hidden px-8 py-6">
                        <div class="absolute -right-10 -top-10 w-40 h-40 bg-white rounded-full opacity-10 blur-2xl"></div>
                        <div class="absolute -left-5 -bottom-5 w-24 h-24 bg-black rounded-full opacity-10 blur-xl"></div>
                        
                        <div class="relative z-10 flex justify-between items-start">
                            <div class="flex items-center gap-5">
                                <div id="modalAvatar" class="w-16 h-16 rounded-2xl bg-white/20 border border-white/30 flex items-center justify-center text-white font-black text-2xl shadow-inner backdrop-blur-md">
                                    <svg class="w-8 h-8 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                </div>
                                <div>
                                    <h3 class="text-2xl font-extrabold text-white tracking-tight mb-1" id="modalNamaSantri"><?= lang('Tahfidz/Monitoring.modal_loading_profile') ?></h3>
                                    <p class="text-[11px] text-white/80 font-bold uppercase tracking-widest bg-black/10 inline-block px-3 py-1 rounded-lg backdrop-blur-sm border border-white/10"><?= lang('Tahfidz/Monitoring.modal_timeline_title') ?></p>
                                </div>
                            </div>
                            <button type="button" onclick="tutupModal()" class="rounded-full p-2 text-white/70 hover:bg-white/20 hover:text-white transition-all">
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                            </button>
                        </div>
                    </div>

                    <div class="px-8 py-8 max-h-[65vh] overflow-y-auto custom-table-scroll bg-slate-50 dark:bg-slate-800 relative">
                        <div id="loadingModal" class="flex flex-col items-center justify-center py-16 hidden">
                            <div class="w-12 h-12 border-4 border-slate-200 dark:border-slate-600 border-t-primary rounded-full animate-spin mb-4"></div>
                            <p class="text-sm font-bold text-slate-500 dark:text-slate-400 animate-pulse tracking-wide"><?= lang('Tahfidz/Monitoring.modal_analyzing') ?></p>
                        </div>
                        
                        <div id="timelineContainer" class="relative border-l-2 border-slate-200/80 dark:border-slate-700 ml-4 space-y-8 pb-4">
                            </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    const LANG = {
        fetch_url: "<?= base_url('tahfidz/monitoring/get-data') ?>",
        riwayat_url: "<?= base_url('tahfidz/monitoring/get-riwayat') ?>",
        
        loading_data: "<?= lang('Tahfidz/Monitoring.js_loading_data') ?>",
        no_student: "<?= lang('Tahfidz/Monitoring.js_no_student_data') ?>",
        alert_select: "<?= lang('Tahfidz/Monitoring.js_alert_select_class') ?>",
        err_monitor: "<?= lang('Tahfidz/Monitoring.js_err_fetch_monitor') ?>",
        err_history: "<?= lang('Tahfidz/Monitoring.js_err_fetch_history') ?>",
        
        nis_label: "<?= lang('Tahfidz/Monitoring.nis_label') ?>",
        target_juz: "<?= lang('Tahfidz/Monitoring.target_juz_30') ?>",
        not_deposited: "<?= lang('Tahfidz/Monitoring.not_deposited_yet') ?>",
        surah_lbl: "<?= lang('Tahfidz/Monitoring.surah_label') ?>",
        ayat_lbl: "<?= lang('Tahfidz/Monitoring.ayat_label') ?>",
        
        dep_today: "<?= lang('Tahfidz/Monitoring.deposit_today') ?>",
        dep_yesterday: "<?= lang('Tahfidz/Monitoring.deposit_yesterday') ?>",
        dep_3_days: "<?= lang('Tahfidz/Monitoring.deposit_3_days_ago') ?>",
        dep_never: "<?= lang('Tahfidz/Monitoring.deposit_never') ?>",
        
        modal_loading: "<?= lang('Tahfidz/Monitoring.modal_loading_profile') ?>",
        modal_no_hist: "<?= lang('Tahfidz/Monitoring.modal_no_history') ?>",
        modal_no_desc: "<?= lang('Tahfidz/Monitoring.modal_no_history_desc') ?>"
    };
</script>
<script src="<?= base_url('assets/js/Tahfidz/monitoring.js') ?>?v=<?= time() ?>"></script>
<?= $this->endSection() ?>