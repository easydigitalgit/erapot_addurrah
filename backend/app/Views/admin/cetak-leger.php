<?= $this->extend('layout/main') ?>

<?= $this->section('title') ?>
  <?= lang('Admin/CetakLeger.page_title') ?> - Rapor Digital SMPIT Ad Durrah
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
    :root { --warna-scroll: <?= $color['warna_primary'] ?>; }
    
    /* Warna Solid Header Tabel agar tidak tembus pandang */
    .leger-table thead tr:nth-child(1) th { background-color: var(--warna-scroll); }
    .leger-table thead tr:nth-child(2) th { background-color: color-mix(in srgb, var(--warna-scroll) 90%, black); }
    
    .dark .leger-table thead tr:nth-child(1) th { background-color: #334155 !important; }
    .dark .leger-table thead tr:nth-child(2) th { background-color: #1e293b !important; }

    /* ========================================================
       🔥 SIHIR CHUNKING HALAMAN KHUSUS PRINT BROWSER 🔥
       ======================================================== */
    @media print {
        @page { size: legal landscape; margin: 10mm; }
        
        /* 💥 KUNCI PENYELESAIAN: Lepaskan gembok tinggi (height) dari template web! */
        html, body { 
            height: auto !important; 
            min-height: 100% !important; 
            overflow: visible !important; 
        }
        
        /* Sembunyikan SEMUA elemen web asli saat diprint */
        body > * { display: none !important; }
        
        /* Tampilkan HANYA Kanvas Hantu kita (#printArea) */
        body > #printArea { display: block !important; width: 100%; background: white; color: black; }
        
        /* Gaya khusus untuk Tabel di dalam Kanvas Hantu */
        #printArea table { width: 100%; border-collapse: collapse; font-family: Arial, sans-serif; font-size: 8.5pt; color: black; }
        #printArea th, #printArea td { border: 1px solid black; padding: 5px 3px; text-align: center; color: black; }
        #printArea td.text-left { text-align: left !important; white-space: nowrap; }

        /* ✂️ CSS Sakti Pemotong Kertas ✂️ */
        .potong-kertas { 
            page-break-after: always !important; 
            break-after: page !important; 
        }
    }
</style>
  <link rel="stylesheet" href="<?= base_url('assets/css/Admin/cetak-leger.css') ?>?v=<?= time() ?>">
<?= $this->endSection() ?>

<?= $this->section('content') ?>
     <div class="mb-6 no-print">
      <div class="flex items-center gap-2 text-sm text-gray-500 dark:text-slate-400 mb-3 transition-colors">
        <span><?= lang('Admin/CetakLeger.breadcrumb_report') ?></span>
       <svg class="w-4 h-4" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
       </svg><span class="text-[<?= $color['warna_primary'] ?>] font-medium"><?= lang('Admin/CetakLeger.page_title') ?></span>
      </div>
      <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 mb-6">
       <div>
        <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white mb-2 flex items-center gap-3 transition-colors">
         <svg class="w-8 h-8 text-[<?= $color['warna_primary'] ?>]" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
         </svg> <?= lang('Admin/CetakLeger.page_title') ?></h1>
        <p class="text-sm md:text-base text-gray-600 dark:text-slate-400 transition-colors"><?= lang('Admin/CetakLeger.page_subtitle') ?></p>
       </div>
       <div class="flex flex-wrap items-center gap-2">
           <button onclick="printLeger()" class="btn-primary bg-[<?= $color['warna_primary'] ?>]/90 hover:bg-[<?= $color['warna_primary'] ?>] text-white transition-all transform hover:-translate-y-0.5 shadow-lg flex items-center gap-2 px-5 py-2.5 rounded-xl font-bold outline-none" style="box-shadow: 0 10px 15px -3px <?= $color['warna_primary'] ?>40;">
         <svg class="w-5 h-5" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
         </svg><span><?= lang('Admin/CetakLeger.btn_print') ?></span> </button>
         
         <button onclick="exportExcel()" class="btn-secondary bg-white dark:bg-slate-800 border border-gray-300 dark:border-slate-600 text-gray-700 dark:text-slate-300 hover:bg-gray-50 dark:hover:bg-slate-700 transition-colors flex items-center gap-2 px-5 py-2.5 rounded-xl font-bold shadow-sm outline-none">
         <svg class="w-5 h-5" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
         </svg><span><?= lang('Admin/CetakLeger.btn_excel') ?></span> </button>
       </div>
      </div>
      
      <div class="alert-info bg-[<?= $color['warna_secondary'] ?>]/50 dark:bg-[<?= $color['warna_secondary'] ?>]/10 border-l-4 border-[<?= $color['warna_primary'] ?>] p-4 rounded-xl flex items-start gap-4 shadow-sm transition-colors mb-6">
       <div class="w-10 h-10 rounded-lg bg-[<?= $color['warna_secondary'] ?>] dark:bg-[<?= $color['warna_primary'] ?>]/20 flex items-center justify-center flex-shrink-0 transition-colors">
        <svg class="w-5 h-5 text-[<?= $color['warna_primary'] ?>]" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
       </div>
       <div class="flex-1 mt-0.5">
        <h4 class="font-bold text-[<?= $color['warna_primary'] ?>] mb-1"><?= lang('Admin/CetakLeger.info_title') ?></h4>
        <p class="text-sm font-medium text-[<?= $color['warna_primary'] ?>]/80 dark:text-slate-300 leading-relaxed"><?= lang('Admin/CetakLeger.info_desc') ?></p>
       </div>
      </div>
     </div>
     
     <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6 no-print w-full min-w-0">
         <div class="lg:col-span-2 filter-card bg-white dark:bg-slate-800 border border-gray-100 dark:border-slate-700 shadow-sm p-6 rounded-3xl transition-colors">
       <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-5 flex items-center gap-2 transition-colors border-b border-gray-100 dark:border-slate-700 pb-3">
        <svg class="w-5 h-5 text-[<?= $color['warna_primary'] ?>]" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" /></svg> <?= lang('Admin/CetakLeger.filter_title') ?></h3>
       
       <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-5">
        <div>
            <label class="block text-[11px] font-bold text-gray-600 dark:text-slate-400 uppercase tracking-wider mb-2">TAHUN AJARAN / SEMESTER</label> 
            <select id="filter_ta_smt" class="select-custom w-full px-4 py-3 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 text-gray-800 dark:text-white rounded-xl focus:outline-none focus:ring-2 focus:ring-[<?= $color['warna_primary'] ?>] transition-colors appearance-none cursor-pointer outline-none shadow-sm" onchange="window.location.href='?ta=' + encodeURIComponent(this.value)">
                <?php foreach($list_ta_smt as $ta): ?>
                    <option value="<?= $ta['value'] ?>" <?= ($ta['value'] === ($ta_terpilih ?? '')) ? 'selected' : '' ?>>
                        <?= $ta['text'] ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        
        <div>
            <label class="block text-[11px] font-bold text-gray-600 dark:text-slate-400 uppercase tracking-wider mb-2">KATEGORI</label> 
            <select id="filter_kategori" class="select-custom w-full px-4 py-3 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 text-gray-800 dark:text-white rounded-xl focus:outline-none focus:ring-2 focus:ring-[<?= $color['warna_primary'] ?>] transition-colors appearance-none cursor-pointer outline-none shadow-sm" onchange="loadLegerData()">
                <option value="Tengah Semester">Tengah Semester</option>
                <option value="Akhir Semester">Akhir Semester</option>
            </select>
        </div>
        
        <div>
            <label class="block text-[11px] font-bold text-gray-600 dark:text-slate-400 uppercase tracking-wider mb-2"><?= lang('Admin/CetakLeger.class') ?></label> 
            <select id="filter_rombel" class="select-custom w-full px-4 py-3 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 text-gray-800 dark:text-white rounded-xl focus:outline-none focus:ring-2 focus:ring-[<?= $color['warna_primary'] ?>] transition-colors appearance-none cursor-pointer outline-none shadow-sm" onchange="loadLegerData()"> 
                <?php foreach($list_rombel as $rombel): ?>
                    <option value="<?= $rombel['id'] ?>"><?= $rombel['nama_rombel'] ?></option>
                <?php endforeach; ?>
            </select>
        </div>
       </div>
<div class="mt-4 p-4 bg-gray-50 dark:bg-slate-700/50 border border-gray-200 dark:border-slate-600 rounded-xl transition-colors">
    <h4 class="font-bold text-gray-800 dark:text-white mb-4 text-sm transition-colors"><?= lang('Admin/CetakLeger.table_view') ?></h4>
    
    <div class="flex flex-col sm:flex-row sm:items-center gap-6">
        <div class="flex items-center gap-3">
            <label class="text-xs font-bold text-gray-600 dark:text-slate-400 uppercase tracking-wider"><?= lang('Admin/CetakLeger.sort_by') ?></label>
            <select id="sort_mode" onchange="changeSortMode()" class="px-4 py-2 bg-white dark:bg-slate-700 border border-gray-300 dark:border-slate-600 text-gray-800 dark:text-white rounded-lg text-sm focus:outline-none focus:border-[<?= $color['warna_primary'] ?>] focus:ring-2 focus:ring-[<?= $color['warna_primary'] ?>] transition-colors shadow-sm cursor-pointer outline-none">
                <option value="abjad"><?= lang('Admin/CetakLeger.sort_az') ?></option>
                <option value="rank"><?= lang('Admin/CetakLeger.sort_rank') ?></option>
            </select>
        </div>    
        
        <div class="h-8 w-px bg-gray-300 dark:bg-slate-600 hidden sm:block mx-2"></div>

        <div class="flex flex-wrap gap-6">
            <label class="flex items-center gap-3 cursor-pointer group select-none">
                <div class="relative inline-flex items-center">
                    <input type="checkbox" 
                           checked 
                           onchange="toggleNilaiAngka()" 
                           class="sr-only peer">
                    
                    <div class="w-11 h-6 bg-gray-300 dark:bg-slate-600 rounded-full peer 
                                peer-checked:after:translate-x-full 
                                peer-checked:after:border-white 
                                after:content-[''] 
                                after:absolute after:top-[2px] after:left-[2px] 
                                after:bg-white 
                                after:border-gray-300 after:border after:rounded-full 
                                after:h-5 after:w-5 
                                after:transition-all 
                                peer-checked:bg-[<?= $color['warna_primary'] ?>]
                                transition-colors duration-200 ease-in-out">
                    </div>
                </div>
                <span class="text-sm font-bold text-gray-700 dark:text-slate-300 group-hover:text-[<?= $color['warna_primary'] ?>] transition-colors"><?= lang('Admin/CetakLeger.numeric_grade') ?></span>
            </label>

            <label class="flex items-center gap-3 cursor-pointer group select-none">
                <div class="relative inline-flex items-center">
                    <input type="checkbox" 
                           checked 
                           onchange="togglePredikat()" 
                           class="sr-only peer">
                    
                    <div class="w-11 h-6 bg-gray-300 dark:bg-slate-600 rounded-full peer 
                                peer-checked:after:translate-x-full 
                                peer-checked:after:border-white 
                                after:content-[''] 
                                after:absolute after:top-[2px] after:left-[2px] 
                                after:bg-white 
                                after:border-gray-300 after:border after:rounded-full 
                                after:h-5 after:w-5 
                                after:transition-all 
                                peer-checked:bg-[<?= $color['warna_primary'] ?>]
                                transition-colors duration-200 ease-in-out">
                    </div>
                </div>
                <span class="text-sm font-bold text-gray-700 dark:text-slate-300 group-hover:text-[<?= $color['warna_primary'] ?>] transition-colors"><?= lang('Admin/CetakLeger.predicate_grade') ?></span>
            </label>
        </div>
    </div>
</div>
      </div>
      
      <div class="info-panel bg-white dark:bg-slate-800 rounded-3xl border border-gray-100 dark:border-slate-700 shadow-sm p-6 transition-colors h-full">
       <h3 class="font-bold text-gray-900 dark:text-white mb-5 flex items-center gap-2 transition-colors border-b border-gray-100 dark:border-slate-700 pb-3">
        <svg class="w-5 h-5 text-[<?= $color['warna_primary'] ?>]" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg> <?= lang('Admin/CetakLeger.class_info') ?></h3>
       <div class="space-y-4">
        <div class="info-row flex justify-between items-center bg-gray-50 dark:bg-slate-700/50 p-3 rounded-lg transition-colors">
            <span class="text-[11px] font-bold text-gray-600 dark:text-slate-400 uppercase tracking-wider"><?= lang('Admin/CetakLeger.class') ?></span> 
            <span id="info_nama_rombel" class="text-xs font-black text-gray-900 dark:text-white">...</span>
        </div>
        <div class="info-row flex justify-between items-center bg-gray-50 dark:bg-slate-700/50 p-3 rounded-lg transition-colors">
            <span class="text-[11px] font-bold text-gray-600 dark:text-slate-400 uppercase tracking-wider"><?= lang('Admin/CetakLeger.homeroom_teacher') ?></span> 
            <span id="info_wali_kelas" class="text-xs font-bold text-gray-900 dark:text-white">...</span>
        </div>
        <div class="info-row flex justify-between items-center bg-gray-50 dark:bg-slate-700/50 p-3 rounded-lg transition-colors">
            <span class="text-[11px] font-bold text-gray-600 dark:text-slate-400 uppercase tracking-wider"><?= lang('Admin/CetakLeger.student_count') ?></span> 
            <span class="text-xs font-black text-[<?= $color['warna_primary'] ?>]"><span id="info_jumlah_siswa">0</span> <?= lang('Admin/CetakLeger.students') ?></span>
        </div>
        <div class="info-row flex justify-between items-center bg-gray-50 dark:bg-slate-700/50 p-3 rounded-lg transition-colors">
            <span class="text-[11px] font-bold text-gray-600 dark:text-slate-400 uppercase tracking-wider"><?= lang('Admin/CetakLeger.curriculum') ?></span> 
            <span id="info_kurikulum" class="text-xs font-bold text-gray-900 dark:text-white">...</span>
        </div>
        <div class="info-row flex justify-between items-center bg-gray-50 dark:bg-slate-700/50 p-3 rounded-lg transition-colors">
            <span class="text-[11px] font-bold text-gray-600 dark:text-slate-400 uppercase tracking-wider"><?= lang('Admin/CetakLeger.status') ?></span> 
            <span id="info_status" class="badge-chip text-[10px] font-bold px-2 py-1 rounded">
             ...
            </span>
        </div>
       </div>
      </div>
     </div>
     
     <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6 no-print">
    
    <div class="stat-card bg-white dark:bg-slate-800 border border-gray-100 dark:border-slate-700 shadow-sm p-5 rounded-3xl transition-colors hover:shadow-md group">
        <div class="flex items-center justify-between mb-2">
            <span class="text-xs font-bold text-gray-500 dark:text-slate-400 uppercase tracking-wider group-hover:text-[<?= $color['warna_primary'] ?>] transition-colors"><?= lang('Admin/CetakLeger.avg_class') ?></span>
            <div class="w-8 h-8 rounded-full bg-emerald-50 dark:bg-emerald-900/20 flex items-center justify-center">
                <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" /></svg>
            </div>
        </div>
        <p id="stat_rata_kelas" class="text-3xl font-black text-gray-900 dark:text-white mt-2 transition-colors">0</p>
        <div class="w-full bg-gray-100 dark:bg-slate-700 rounded-full h-1.5 mt-4 overflow-hidden">
            <div id="bar_rata_kelas" class="bg-emerald-500 h-1.5 rounded-full transition-all duration-1000" style="width: 0%"></div>
        </div>
    </div>

    <div class="stat-card bg-white dark:bg-slate-800 border border-gray-100 dark:border-slate-700 shadow-sm p-5 rounded-3xl transition-colors hover:shadow-md group">
        <div class="flex items-center justify-between mb-2">
            <span class="text-xs font-bold text-gray-500 dark:text-slate-400 uppercase tracking-wider group-hover:text-[<?= $color['warna_primary'] ?>] transition-colors"><?= lang('Admin/CetakLeger.highest_grade') ?></span>
            <div class="w-8 h-8 rounded-full bg-blue-50 dark:bg-blue-900/20 flex items-center justify-center">
                <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" /></svg>
            </div>
        </div>
        <p id="stat_nilai_tertinggi" class="text-3xl font-black text-gray-900 dark:text-white mt-2 transition-colors">0</p>
        <p id="text_nilai_tertinggi" class="text-[10px] font-medium text-gray-400 dark:text-slate-500 mt-2 truncate">-</p>
    </div>

    <div class="stat-card bg-white dark:bg-slate-800 border border-gray-100 dark:border-slate-700 shadow-sm p-5 rounded-3xl transition-colors hover:shadow-md group">
        <div class="flex items-center justify-between mb-2">
            <span class="text-xs font-bold text-gray-500 dark:text-slate-400 uppercase tracking-wider group-hover:text-[<?= $color['warna_primary'] ?>] transition-colors"><?= lang('Admin/CetakLeger.lowest_grade') ?></span>
            <div class="w-8 h-8 rounded-full bg-red-50 dark:bg-red-900/20 flex items-center justify-center">
                <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6" /></svg>
            </div>
        </div>
        <p id="stat_nilai_terendah" class="text-3xl font-black text-gray-900 dark:text-white mt-2 transition-colors">0</p>
        <p id="text_nilai_terendah" class="text-[10px] font-medium text-gray-400 dark:text-slate-500 mt-2 truncate">-</p>
    </div>

    <div class="stat-card bg-white dark:bg-slate-800 border border-gray-100 dark:border-slate-700 shadow-sm p-5 rounded-3xl transition-colors hover:shadow-md group">
        <div class="flex items-center justify-between mb-2">
            <span class="text-xs font-bold text-gray-500 dark:text-slate-400 uppercase tracking-wider group-hover:text-[<?= $color['warna_primary'] ?>] transition-colors"><?= lang('Admin/CetakLeger.completeness') ?></span>
            <div class="w-8 h-8 rounded-full bg-amber-50 dark:bg-amber-900/20 flex items-center justify-center">
                <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            </div>
        </div>
        <p id="stat_ketuntasan" class="text-3xl font-black text-gray-900 dark:text-white mt-2 transition-colors">0%</p>
        <div class="w-full bg-gray-100 dark:bg-slate-700 rounded-full h-1.5 mt-4 overflow-hidden">
            <div id="bar_ketuntasan" class="bg-amber-500 h-1.5 rounded-full transition-all duration-1000" style="width: 0%"></div>
        </div>
    </div>

</div>

     <div class="table-container bg-white dark:bg-slate-800 rounded-3xl border border-gray-100 dark:border-slate-700 shadow-sm overflow-hidden transition-colors">
      <div class="p-4 bg-gray-50/50 dark:bg-slate-900/30 border-b border-gray-100 dark:border-slate-700 flex items-center gap-3 transition-colors no-print">
       <svg class="w-5 h-5 text-gray-400 dark:text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7" />
       </svg><span class="text-xs font-bold text-gray-500 dark:text-slate-400 uppercase tracking-wider"><?= lang('Admin/CetakLeger.table_hint') ?></span>
      </div>
       <div class="leger-table-wrapper w-full max-h-[65vh] overflow-auto custom-scrollbar border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800 transition-colors relative">
            <table class="leger-table w-full text-left border-collapse min-w-max">
                <thead class="text-white font-bold text-center text-[11px] uppercase tracking-wider transition-colors">
                    <tr class="transition-colors" style="height: 50px;">
                        <th rowspan="2" class="border border-white/20 dark:border-slate-600 sticky top-0 md:left-0 z-50" style="z-index: 60; width: 40px; padding: 0.75rem;"><?= lang('Admin/CetakLeger.th_no') ?></th>
                        <th rowspan="2" class="border border-white/20 dark:border-slate-600 sticky top-0 md:left-[40px] z-50" style="z-index: 60; width: 80px; padding: 0.75rem;"><?= lang('Admin/CetakLeger.th_nis') ?></th>
                        <th rowspan="2" class="border border-white/20 dark:border-slate-600 sticky top-0 left-0 md:left-[120px]" style="z-index: 60; min-width: 200px; padding: 0.75rem;"><?= lang('Admin/CetakLeger.th_name') ?></th>

                        <?php foreach($list_mapel as $mapel): ?>
                            <th colspan="2" class="border border-white/20 dark:border-slate-600 sticky top-0" style="z-index: 40; padding: 0.75rem;">
                                <?= esc($mapel['nama_mapel']) ?>
                            </th>
                        <?php endforeach; ?>
                        
                        <th rowspan="2" class="border border-white/20 dark:border-slate-600 sticky top-0" style="z-index: 40; padding: 0.75rem;"><?= lang('Admin/CetakLeger.th_avg') ?></th>
                        <th rowspan="2" class="border border-white/20 dark:border-slate-600 sticky top-0" style="z-index: 40; padding: 0.75rem;"><?= lang('Admin/CetakLeger.th_rank') ?></th>
                    </tr>
                        
                    <tr class="transition-colors" style="height: 40px;">
                        <?php foreach($list_mapel as $mapel): ?>
                            <th class="border border-white/20 dark:border-slate-600 sticky px-3 py-2" style="top: 50px; z-index: 30;"><?= lang('Admin/CetakLeger.th_grade') ?></th>
                            <th class="border border-white/20 dark:border-slate-600 sticky px-3 py-2" style="top: 50px; z-index: 30;"><?= lang('Admin/CetakLeger.th_pred') ?></th>
                        <?php endforeach; ?>
                    </tr>
                </thead>

                <tbody id="legerTableBody" class="divide-y divide-gray-200 dark:divide-slate-700/80 transition-colors bg-white dark:bg-slate-800">
                    <tr>
                        <td colspan="25" class="text-center py-12 text-gray-500 dark:text-slate-400 font-medium text-sm border-b border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800">
                            <div class="flex flex-col items-center justify-center gap-2">
                                <svg class="w-8 h-8 text-gray-300 dark:text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" /></svg>
                                Belum ada data nilai terkunci untuk kelas ini.
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
     </div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    window.BASE_URL = "<?= rtrim(base_url(), '/') ?>/";
    window.LANG = {
        js_loading: "<?= lang('Admin/CetakLeger.js_loading') ?: 'Memuat data dari database...' ?>",
        js_err_load: "<?= lang('Admin/CetakLeger.js_err_load') ?: 'Gagal memuat data' ?>",
        js_err_net: "<?= lang('Admin/CetakLeger.js_err_net') ?: 'Terjadi kesalahan jaringan' ?>",
        js_empty_data: "<?= lang('Admin/CetakLeger.js_empty_data') ?: 'Belum ada data nilai terkunci untuk kelas ini.' ?>",
        js_show_num: "<?= lang('Admin/CetakLeger.js_show_num') ?: 'Nilai angka ditampilkan' ?>",
        js_hide_num: "<?= lang('Admin/CetakLeger.js_hide_num') ?: 'Nilai angka disembunyikan' ?>",
        js_show_pred: "<?= lang('Admin/CetakLeger.js_show_pred') ?: 'Predikat ditampilkan' ?>",
        js_hide_pred: "<?= lang('Admin/CetakLeger.js_hide_pred') ?: 'Predikat disembunyikan' ?>",
        js_preparing: "<?= lang('Admin/CetakLeger.js_preparing') ?: 'Sedang disiapkan...' ?>",
        js_preparing_print: "<?= lang('Admin/CetakLeger.js_preparing_print') ?: 'Menyiapkan dokumen untuk dicetak...' ?>"
    };
    const MAPEL_DATA = <?= json_encode($list_mapel) ?>;
</script>
<script src="<?= base_url('assets/js/Admin/cetak-leger.js') ?>?v=<?= time() ?>"></script>
<?= $this->endSection() ?>