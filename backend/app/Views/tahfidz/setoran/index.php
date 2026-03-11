<?= $this->extend('layout/main') ?>

<?= $this->section('title') ?>
  <?= lang('Tahfidz/Setoran.page_title') ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div id="topSection" class="transition-all duration-500 origin-top">
    <div class="flex items-center gap-2 text-sm text-slate-500 dark:text-slate-400 mb-4 font-medium">
        <span><?= lang('Tahfidz/Setoran.breadcrumb_tahfidz') ?></span>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
        <span class="text-[<?= $color['warna_primary'] ?>] dark:text-[<?= $color['warna_primary'] ?>]"><?= lang('Tahfidz/Setoran.breadcrumb_setoran') ?></span>
    </div>

    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-slate-900 dark:text-white mb-1"><?= lang('Tahfidz/Setoran.title_input') ?></h1>
            <p class="text-slate-500 dark:text-slate-400 text-sm"><?= lang('Tahfidz/Setoran.subtitle_input') ?> <span class="font-bold text-[<?= $color['warna_primary'] ?>]"><?= lang('Tahfidz/Setoran.focus_mode') ?></span> & <span class="font-bold text-[<?= $color['warna_primary'] ?>]"><?= lang('Tahfidz/Setoran.shortcut_info') ?></span>.</p>
        </div>
        <div class="bg-white dark:bg-slate-800 px-4 py-2 rounded-xl border border-slate-200 dark:border-slate-700 shadow-sm flex items-center gap-3">
            <div class="w-2 h-2 rounded-full bg-red-500 animate-pulse"></div>
            <span class="text-sm font-bold text-slate-700 dark:text-slate-300 tracking-wide" id="realtimeClock"><?= date('d M Y') ?></span>
        </div>
    </div>

    <div class="rounded-2xl shadow-lg p-6 mb-6 text-white flex items-start md:items-center gap-5 relative overflow-hidden transition-transform hover:scale-[1.01] duration-300" style="background-color: <?= $color['warna_primary'] ?>; background-image: linear-gradient(135deg, rgba(255,255,255,0.15) 0%, rgba(0,0,0,0.15) 100%);">
        <svg class="absolute right-0 top-0 w-48 h-48 text-white/10 transform translate-x-8 -translate-y-12" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/></svg>
        <div class="bg-white/20 p-3.5 rounded-2xl backdrop-blur-md shadow-inner flex-shrink-0 z-10">
            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
        </div>
       <div class="relative z-10">
            <h3 class="text-sm font-bold text-white/90 uppercase tracking-widest mb-1.5 flex items-center gap-2">
                <span class="w-1.5 h-1.5 rounded-full bg-yellow-300"></span> <?= lang('Tahfidz/Setoran.daily_inspiration') ?>
            </h3>
            
            <?php 
                // KITA RANDOM LANGSUNG DARI PHP (ANTI GAGAL)
                $kumpulan_quote = [
                    lang('Tahfidz/Setoran.quote_1'),
                    lang('Tahfidz/Setoran.quote_2'),
                    lang('Tahfidz/Setoran.quote_3'),
                    lang('Tahfidz/Setoran.quote_4')
                ];
                $quote_harian = $kumpulan_quote[array_rand($kumpulan_quote)];
            ?>
            <p class="text-lg md:text-xl font-medium text-white leading-relaxed text-shadow-sm">
                <?= esc($quote_harian) ?>
            </p>
        </div>
    </div>

    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm p-5 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
            <div>
                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2"><?= lang('Tahfidz/Setoran.select_class') ?></label>
                <select id="kelasSelect" class="w-full px-4 py-2.5 text-sm font-medium text-slate-700 dark:text-slate-300 border-0 ring-1 ring-slate-200 dark:ring-slate-600 rounded-xl focus:ring-2 focus:ring-[<?= $color['warna_primary'] ?>] bg-slate-50 dark:bg-slate-700 hover:bg-white dark:hover:bg-slate-600 transition-all shadow-sm cursor-pointer">
                    <option value=""><?= lang('Tahfidz/Setoran.ph_select_class') ?></option>
                    <?php foreach($rombels as $r): ?>
                        <option value="<?= $r['id'] ?>"><?= esc($r['nama_rombel']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2"><?= lang('Tahfidz/Setoran.deposit_date') ?></label>
                <input type="date" id="tanggalSetoran" value="<?= date('Y-m-d') ?>" class="w-full px-4 py-2.5 text-sm font-medium text-slate-700 dark:text-slate-300 border-0 ring-1 ring-slate-200 dark:ring-slate-600 rounded-xl focus:ring-2 focus:ring-[<?= $color['warna_primary'] ?>] bg-slate-50 dark:bg-slate-700 hover:bg-white dark:hover:bg-slate-600 transition-all shadow-sm cursor-pointer color-scheme-dark">
            </div>
            <div>
                <button type="button" onclick="loadSiswa()" class="w-full py-2.5 px-4 text-white rounded-xl font-bold flex items-center justify-center gap-2 transition-all shadow-md hover:shadow-lg transform hover:-translate-y-0.5" style="background-color: <?= $color['warna_primary'] ?>;">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                    <?= lang('Tahfidz/Setoran.btn_show_worksheet') ?>
                </button>
            </div>
        </div>
    </div>
</div> 

<div id="emptyState" class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm p-16 text-center transition-all duration-300">
    <div class="w-20 h-20 bg-slate-50 dark:bg-slate-700 rounded-full flex items-center justify-center mx-auto mb-5 border border-slate-100 dark:border-slate-600 shadow-inner">
        <svg class="w-10 h-10 text-slate-400 dark:text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
    </div>
    <h3 class="text-xl font-bold text-slate-800 dark:text-white mb-2"><?= lang('Tahfidz/Setoran.empty_area_title') ?></h3>
    <p class="text-slate-500 dark:text-slate-400 text-sm max-w-md mx-auto"><?= lang('Tahfidz/Setoran.empty_area_desc') ?></p>
</div>

<div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-lg overflow-hidden transition-all duration-500" id="tableContainer" style="display: none;">
    
    <div class="grid grid-cols-2 md:grid-cols-4 gap-0 border-b border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-800/50">
        <div class="p-5 border-r border-b md:border-b-0 border-slate-200 dark:border-slate-700 flex items-center gap-4 hover:bg-white dark:hover:bg-slate-700 transition-colors">
            <div class="w-12 h-12 rounded-full flex items-center justify-center shadow-inner text-white" style="background-color: <?= $color['warna_primary'] ?>; opacity: 0.9;">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
            </div>
            <div>
                <p class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider"><?= lang('Tahfidz/Setoran.stat_total_student') ?></p>
                <p class="text-2xl font-black text-slate-800 dark:text-white" id="statTotal">0</p>
            </div>
        </div>
        <div class="p-5 border-r border-b md:border-b-0 border-slate-200 dark:border-slate-700 flex items-center gap-4 hover:bg-white dark:hover:bg-slate-700 transition-colors">
            <div class="w-12 h-12 rounded-full flex items-center justify-center shadow-inner text-white" style="background-color: <?= $color['warna_primary'] ?>; opacity: 0.7;">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
            </div>
            <div>
                <p class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider"><?= lang('Tahfidz/Setoran.stat_ziyadah') ?></p>
                <p class="text-2xl font-black text-slate-800 dark:text-white" id="statZiyadah">0</p>
            </div>
        </div>
        <div class="p-5 border-r border-slate-200 dark:border-slate-700 flex items-center gap-4 hover:bg-white dark:hover:bg-slate-700 transition-colors">
            <div class="w-12 h-12 rounded-full flex items-center justify-center shadow-inner text-white" style="background-color: <?= $color['warna_primary'] ?>; opacity: 0.5;">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
            </div>
            <div>
                <p class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider"><?= lang('Tahfidz/Setoran.stat_murojaah') ?></p>
                <p class="text-2xl font-black text-slate-800 dark:text-white" id="statMurojaah">0</p>
            </div>
        </div>
        <div class="p-5 flex items-center gap-4 hover:bg-white dark:hover:bg-slate-700 transition-colors">
            <div class="w-12 h-12 rounded-full bg-emerald-100 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 flex items-center justify-center shadow-inner">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path></svg>
            </div>
            <div>
                <p class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider"><?= lang('Tahfidz/Setoran.stat_very_fluent') ?></p>
                <p class="text-2xl font-black text-slate-800 dark:text-white" id="statSangatLancar">0</p>
            </div>
        </div>
    </div>

    <div class="p-4 border-b border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 flex flex-col lg:flex-row justify-between items-center gap-4">
        <div class="relative w-full lg:w-1/3">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="w-5 h-5 text-slate-400 dark:text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>
            <input type="text" id="searchInput" onkeyup="cariSantri()" placeholder="<?= lang('Tahfidz/Setoran.search_student') ?>" class="w-full pl-10 pr-4 py-2.5 text-sm border-0 ring-1 ring-slate-200 dark:ring-slate-600 rounded-xl focus:ring-2 focus:ring-[<?= $color['warna_primary'] ?>] bg-slate-50 dark:bg-slate-700 dark:text-white hover:bg-white dark:hover:bg-slate-600 transition-all shadow-sm placeholder-slate-400">
        </div>
        
        <div class="w-full lg:w-auto flex flex-col sm:flex-row gap-3">
            <button type="button" onclick="toggleFocusMode()" id="btnFocus" class="px-4 py-2.5 bg-slate-800 dark:bg-slate-700 hover:bg-slate-900 dark:hover:bg-slate-600 text-white text-sm font-bold rounded-xl flex items-center justify-center gap-2 transition-all shadow-sm border border-slate-700 dark:border-slate-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"></path></svg>
                <span id="textBtnFocus"><?= lang('Tahfidz/Setoran.btn_enter_focus') ?></span>
            </button>
            <button type="button" onclick="setSemuaLancar()" class="px-5 py-2.5 bg-emerald-50 dark:bg-emerald-900/30 hover:bg-emerald-100 dark:hover:bg-emerald-900/50 text-emerald-700 dark:text-emerald-400 text-sm font-bold rounded-xl flex items-center justify-center gap-2 transition-all border border-emerald-200 dark:border-emerald-800/50 shadow-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                <?= lang('Tahfidz/Setoran.btn_set_all_ziyadah') ?>
            </button>
        </div>
    </div>
    
    <form id="formSetoran" class="relative">
        <datalist id="listSurah">
            <?php if(!empty($surahs)): ?>
                <?php foreach($surahs as $s): ?>
                    <option value="<?= esc($s['nama_surah'] ?? '') ?>">
                <?php endforeach; ?>
            <?php endif; ?>
        </datalist>

        <div id="scrollTableWrapper" class="max-h-[55vh] overflow-y-auto overflow-x-auto custom-scrollbar transition-all duration-500">
            <table class="w-full text-left border-collapse min-w-[1000px]">
                <thead class="sticky top-0 z-20 backdrop-blur-xl bg-white/95 dark:bg-slate-800/95 shadow-sm">
                    <tr class="text-slate-500 dark:text-slate-400 text-sm border-b border-slate-200 dark:border-slate-700">
                        <th class="p-4 font-semibold w-12 text-center"><?= lang('Tahfidz/Setoran.th_no') ?></th>
                        <th class="p-4 font-semibold w-64"><?= lang('Tahfidz/Setoran.th_student_profile') ?></th>
                        <th class="p-4 font-semibold w-40"><?= lang('Tahfidz/Setoran.th_type') ?></th>
                        <th class="p-4 font-semibold w-64"><?= lang('Tahfidz/Setoran.th_surah_ayat') ?> <span class="text-red-500">*</span></th>
                        <th class="p-4 font-semibold w-48"><?= lang('Tahfidz/Setoran.th_predicate') ?></th>
                        <th class="p-4 font-semibold"><?= lang('Tahfidz/Setoran.th_notes') ?></th>
                        <th class="p-4 font-semibold w-12 text-center"></th>
                    </tr>
                </thead>
                <tbody id="tbodySiswa" class="divide-y divide-slate-100 dark:divide-slate-700 bg-white dark:bg-slate-800">
                    </tbody>
            </table>
        </div>
        
        <div class="p-5 bg-slate-50 dark:bg-slate-900/50 border-t border-slate-200 dark:border-slate-700 flex flex-col sm:flex-row justify-between items-center gap-4 shadow-[0_-10px_15px_-3px_rgba(0,0,0,0.05)] relative z-20">
            <p class="text-sm text-slate-500 dark:text-slate-400"><span class="font-semibold text-red-500">*</span> <?= lang('Tahfidz/Setoran.form_note_empty') ?> <span class="hidden md:inline border-l border-slate-300 dark:border-slate-600 ml-2 pl-2"><?= lang('Tahfidz/Setoran.form_note_shortcut') ?></span></p>
            <button type="button" onclick="simpanSetoran()" class="w-full sm:w-auto py-3 px-8 text-white rounded-xl font-bold shadow-lg flex items-center justify-center gap-2 transition-all transform hover:-translate-y-1" style="background-color: <?= $color['warna_primary'] ?>;">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                <span id="textBtnSaveAll"><?= lang('Tahfidz/Setoran.btn_save_all') ?></span>
            </button>
        </div>
    </form>
</div>

<div id="legendPenilaian" style="display: none;" class="mt-8 transition-all duration-500 origin-top">
    <h3 class="text-sm font-bold text-slate-700 dark:text-slate-300 mb-3 flex items-center gap-2">
        <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        <?= lang('Tahfidz/Setoran.legend_title') ?>
    </h3>
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4">
        <div class="bg-white dark:bg-slate-800 p-4 rounded-xl border border-emerald-100 dark:border-emerald-900/50 shadow-sm flex gap-3 items-start">
            <div class="w-3 h-3 rounded-full bg-emerald-500 mt-1 flex-shrink-0 ring-4 ring-emerald-50 dark:ring-emerald-900/30"></div>
            <div>
                <h4 class="font-bold text-slate-800 dark:text-white text-sm"><?= lang('Tahfidz/Setoran.legend_very_fluent') ?></h4>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1 leading-relaxed"><?= lang('Tahfidz/Setoran.desc_very_fluent') ?></p>
            </div>
        </div>
        <div class="bg-white dark:bg-slate-800 p-4 rounded-xl border border-blue-100 dark:border-blue-900/50 shadow-sm flex gap-3 items-start">
            <div class="w-3 h-3 rounded-full bg-blue-500 mt-1 flex-shrink-0 ring-4 ring-blue-50 dark:ring-blue-900/30"></div>
            <div>
                <h4 class="font-bold text-slate-800 dark:text-white text-sm"><?= lang('Tahfidz/Setoran.legend_fluent') ?></h4>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1 leading-relaxed"><?= lang('Tahfidz/Setoran.desc_fluent') ?></p>
            </div>
        </div>
        <div class="bg-white dark:bg-slate-800 p-4 rounded-xl border border-amber-100 dark:border-amber-900/50 shadow-sm flex gap-3 items-start">
            <div class="w-3 h-3 rounded-full bg-amber-500 mt-1 flex-shrink-0 ring-4 ring-amber-50 dark:ring-amber-900/30"></div>
            <div>
                <h4 class="font-bold text-slate-800 dark:text-white text-sm"><?= lang('Tahfidz/Setoran.legend_poor') ?></h4>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1 leading-relaxed"><?= lang('Tahfidz/Setoran.desc_poor') ?></p>
            </div>
        </div>
        <div class="bg-white dark:bg-slate-800 p-4 rounded-xl border border-red-100 dark:border-red-900/50 shadow-sm flex gap-3 items-start">
            <div class="w-3 h-3 rounded-full bg-red-500 mt-1 flex-shrink-0 ring-4 ring-red-50 dark:ring-red-900/30"></div>
            <div>
                <h4 class="font-bold text-slate-800 dark:text-white text-sm"><?= lang('Tahfidz/Setoran.legend_memorized') ?></h4>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1 leading-relaxed"><?= lang('Tahfidz/Setoran.desc_memorized') ?></p>
            </div>
        </div>
    </div>
</div>
<br><br>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    const LANG = {
        primary_color: "<?= $color['warna_primary'] ?>",
        fetch_url: "<?= base_url('tahfidz/setoran/get-siswa') ?>",
        save_url: "<?= base_url('tahfidz/setoran/save') ?>",
        
        loading_data: "<?= lang('Tahfidz/Setoran.loading_data') ?>",
        no_student: "<?= lang('Tahfidz/Setoran.no_student_in_class') ?>",
        nis_label: "<?= lang('Tahfidz/Setoran.nis_label') ?>",
        ph_surah: "<?= lang('Tahfidz/Setoran.ph_surah') ?>",
        ph_ayat: "<?= lang('Tahfidz/Setoran.ph_ayat') ?>",
        ph_notes: "<?= lang('Tahfidz/Setoran.ph_notes') ?>",
        btn_clear: "<?= lang('Tahfidz/Setoran.btn_clear_row_title') ?>",
        
        type_ziyadah: "<?= lang('Tahfidz/Setoran.js_type_ziyadah') ?>",
        type_murojaah: "<?= lang('Tahfidz/Setoran.js_type_murojaah') ?>",
        pred_very_fluent: "<?= lang('Tahfidz/Setoran.js_pred_very_fluent') ?>",
        pred_fluent: "<?= lang('Tahfidz/Setoran.js_pred_fluent') ?>",
        pred_poor: "<?= lang('Tahfidz/Setoran.js_pred_poor') ?>",
        pred_memorized: "<?= lang('Tahfidz/Setoran.js_pred_memorized') ?>",
        
        btn_exit_focus: "<?= lang('Tahfidz/Setoran.btn_exit_focus') ?>",
        btn_enter_focus: "<?= lang('Tahfidz/Setoran.btn_enter_focus') ?>",
        
        toast_all_set: "<?= lang('Tahfidz/Setoran.js_toast_all_set') ?>",
        alert_select_class: "<?= lang('Tahfidz/Setoran.js_alert_select_class') ?>",
        alert_fetch_fail: "<?= lang('Tahfidz/Setoran.js_alert_fetch_fail') ?>",
        
        saving: "<?= lang('Tahfidz/Setoran.js_saving') ?>",
        saving_title: "<?= lang('Tahfidz/Setoran.js_saving_title') ?>",
        saving_desc: "<?= lang('Tahfidz/Setoran.js_saving_desc') ?>",
        success_title: "<?= lang('Tahfidz/Setoran.js_success_title') ?>",
        success_default: "<?= lang('Tahfidz/Setoran.js_success_default') ?>",
        warning_title: "<?= lang('Tahfidz/Setoran.js_warning_title') ?>",
        error_title: "<?= lang('Tahfidz/Setoran.js_error_title') ?>",
        server_error: "<?= lang('Tahfidz/Setoran.js_server_error') ?>",
        server_error_desc: "<?= lang('Tahfidz/Setoran.js_server_error_desc') ?>",

        quote_1: '<?= lang('Tahfidz/Setoran.quote_1') ?>',
        quote_2: '<?= lang('Tahfidz/Setoran.quote_2') ?>',
        quote_3: '<?= lang('Tahfidz/Setoran.quote_3') ?>',
        quote_4: '<?= lang('Tahfidz/Setoran.quote_4') ?>',
    };
</script>
<script src="<?= base_url('assets/js/Tahfidz/setoran.js') ?>?v=<?= time() ?>"></script>
<?= $this->endSection() ?>