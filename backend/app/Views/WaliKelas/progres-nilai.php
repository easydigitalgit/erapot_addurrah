<?= $this->extend('layout/main') ?>

<?= $this->section('title') ?>
  <?= lang('WaliKelas/ProgresNilai.page_title') ?> - Rapor Digital
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
  <link rel="stylesheet" href="<?= base_url('assets/css/WaliKelas/progres-nilai.css') ?>">
  <style>
    /* Injeksi Warna Dinamis dari Database */
    :root {
      --warna-primary: <?= $color['warna_primary'] ?? '#10b981' ?>;
      --warna-secondary: <?= $color['warna_secondary'] ?? '#ecfdf5' ?>;
      --warna-scroll: <?= $color['warna_primary'] ?>; 
    }
    
    .text-tema { color: var(--warna-primary) !important; }
    .bg-tema { background-color: var(--warna-primary) !important; }
    .bg-tema-light { background-color: var(--warna-secondary) !important; }
    .border-tema { border-color: var(--warna-primary) !important; }
    .focus-ring-tema:focus { border-color: var(--warna-primary) !important; box-shadow: 0 0 0 3px color-mix(in srgb, var(--warna-primary) 20%, transparent) !important; }
    
    /* Tab Styling Dinamis */
    .tab-active { border-bottom: 2px solid var(--warna-primary) !important; color: var(--warna-primary) !important; }
    .tab-inactive { border-bottom: 2px solid transparent; }

    /* Dark Mode Overrides */
    html.dark .text-tema { color: color-mix(in srgb, var(--warna-primary) 80%, white) !important; }
    html.dark .bg-tema-light { background-color: rgba(255, 255, 255, 0.05) !important; }
    html.dark .bg-white { background-color: #1e293b !important; border-color: #334155 !important; }
    html.dark .text-gray-800 { color: #f1f5f9 !important; }
    html.dark .text-gray-600 { color: #cbd5e1 !important; }
    html.dark .text-gray-500 { color: #94a3b8 !important; }
    html.dark .bg-gray-50 { background-color: #0f172a !important; }
    html.dark .border-gray-100 { border-color: #334155 !important; }
    html.dark .border-gray-200 { border-color: #475569 !important; }
    
    /* Warna Modal Dark Mode khusus untuk Remedi (Orange) & Umum */
    html.dark .bg-orange-50 { background-color: rgba(249, 115, 22, 0.1) !important; }
    html.dark .border-orange-200 { border-color: rgba(249, 115, 22, 0.2) !important; }
    html.dark .text-orange-900 { color: #fdba74 !important; }
    html.dark .text-orange-800 { color: #fed7aa !important; }
    html.dark .text-orange-700 { color: #f97316 !important; }
    
    /* Custom Scrollbar untuk Modal & Tabel */
    html.dark .custom-scrollbar::-webkit-scrollbar-thumb { background-color: var(--warna-primary); }

    /* Bar Chart Animation */
    .bar { transition: height 1s cubic-bezier(0.4, 0, 0.2, 1); width: 40px; border-radius: 8px 8px 0 0; display: flex; justify-content: center; align-items: flex-start; }
    .bar-value { opacity: 0; transform: translateY(10px); transition: all 0.3s; color: white; font-weight: bold; font-size: 11px; margin-top: 8px; }
    .bar-item:hover .bar-value { opacity: 1; transform: translateY(0); }

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
<?= $this->endSection() ?>

<?= $this->section('content') ?>

    <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-gray-100 dark:border-slate-700 p-4 lg:p-5 mb-6">
      <div class="flex items-center justify-between mb-4">
       <h2 class="font-semibold text-gray-800 dark:text-slate-100"><?= lang('WaliKelas/ProgresNilai.filter_settings') ?></h2>
      </div>
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
       <div>
        <label class="text-xs font-bold text-gray-600 dark:text-slate-400 mb-1.5 block"><?= lang('WaliKelas/ProgresNilai.filter_subject') ?></label> 
        <select id="subjectFilter" class="w-full px-3 py-2.5 bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-600 rounded-xl text-sm text-gray-800 dark:text-slate-200 focus-ring-tema cursor-pointer" onchange="filterData()"> 
         <option value=""><?= lang('WaliKelas/ProgresNilai.opt_all_subjects') ?></option> 
        </select>
       </div>
       
       <div>
        <label class="text-xs font-bold text-gray-600 dark:text-slate-400 mb-1.5 block"><?= lang('WaliKelas/ProgresNilai.filter_status') ?></label> 
        <select id="statusFilter" class="w-full px-3 py-2.5 bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-600 rounded-xl text-sm text-gray-800 dark:text-slate-200 focus-ring-tema cursor-pointer" onchange="filterData()"> 
         <option value=""><?= lang('WaliKelas/ProgresNilai.opt_all_status') ?></option> 
         <option value="Aman"><?= lang('WaliKelas/ProgresNilai.opt_safe_grade') ?></option> 
         <option value="Rawan"><?= lang('WaliKelas/ProgresNilai.opt_warn_grade') ?></option> 
         <option value="Kritis"><?= lang('WaliKelas/ProgresNilai.opt_crit_grade') ?></option> 
        </select>
       </div>
       
       <div>
        <label class="text-xs font-bold text-gray-600 dark:text-slate-400 mb-1.5 block"><?= lang('WaliKelas/ProgresNilai.filter_sort') ?></label> 
        <select id="sortFilter" class="w-full px-3 py-2.5 bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-600 rounded-xl text-sm text-gray-800 dark:text-slate-200 focus-ring-tema cursor-pointer" onchange="filterData()"> 
         <option value="subject"><?= lang('WaliKelas/ProgresNilai.opt_sort_az') ?></option> 
         <option value="average-desc"><?= lang('WaliKelas/ProgresNilai.opt_sort_high') ?></option> 
         <option value="average-asc"><?= lang('WaliKelas/ProgresNilai.opt_sort_low') ?></option> 
        </select>
       </div>
       
       <div>
        <label class="text-xs font-bold text-gray-600 dark:text-slate-400 mb-1.5 block"><?= lang('WaliKelas/ProgresNilai.filter_view') ?></label>
        <div class="flex gap-2">
         <button onclick="setViewMode('grid')" id="viewGrid" class="flex-1 px-3 py-2.5 border-2 border-tema bg-tema-light text-tema text-sm font-bold rounded-xl transition-all shadow-sm">
          <svg class="w-4 h-4 mx-auto" fill="currentColor" viewBox="0 0 24 24"><path d="M3 6h7V3H3v3zm0 7h7v-4H3v4zm9 0h7v-4h-7v4zm0-7h7V3h-7v3zM3 20h7v-4H3v4zm9 0h7v-4h-7v4z" /></svg>
         </button> 
         <button onclick="setViewMode('list')" id="viewList" class="flex-1 px-3 py-2.5 border-2 border-gray-200 dark:border-slate-600 text-gray-600 dark:text-slate-400 text-sm font-bold rounded-xl hover:border-gray-300 dark:hover:border-slate-500 hover:bg-gray-50 dark:hover:bg-slate-700/50 transition-all">
          <svg class="w-4 h-4 mx-auto" fill="currentColor" viewBox="0 0 24 24"><path d="M3 4h18v2H3V4zm0 7h18v-2H3v2zm0 7h18v-2H3v2z" /></svg>
         </button>
        </div>
       </div>
      </div>
    </div>

    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 lg:gap-4 mb-6">
     <div class="bg-white dark:bg-slate-800 rounded-xl p-4 shadow-sm border border-gray-100 dark:border-slate-700 group">
       <div class="flex items-start justify-between">
        <div>
         <p class="text-xs lg:text-sm text-gray-500 dark:text-slate-400 mb-1"><?= lang('WaliKelas/ProgresNilai.stat_total_subject') ?></p>
         <p class="text-2xl lg:text-3xl font-bold text-gray-800 dark:text-white"><?= $statistik_umum['total_mapel'] ?></p>
        </div>
        <div class="w-10 h-10 lg:w-12 lg:h-12 rounded-xl bg-tema-light text-tema flex items-center justify-center transition-transform group-hover:scale-110">
         <svg class="w-5 h-5 lg:w-6 lg:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
        </div>
       </div>
     </div>
     
     <div class="bg-white dark:bg-slate-800 rounded-xl p-4 shadow-sm border border-gray-100 dark:border-slate-700 group">
       <div class="flex items-start justify-between">
        <div>
         <p class="text-xs lg:text-sm text-gray-500 dark:text-slate-400 mb-1"><?= lang('WaliKelas/ProgresNilai.stat_class_avg') ?></p>
         <p class="text-2xl lg:text-3xl font-bold text-tema"><?= $statistik_umum['rata_kelas'] ?></p>
        </div>
        <div class="w-10 h-10 lg:w-12 lg:h-12 rounded-xl bg-tema-light text-tema flex items-center justify-center transition-transform group-hover:scale-110">
         <svg class="w-5 h-5 lg:w-6 lg:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
        </div>
       </div>
     </div>
     
     <div class="bg-white dark:bg-slate-800 rounded-xl p-4 shadow-sm border border-gray-100 dark:border-slate-700 group">
       <div class="flex items-start justify-between">
        <div>
         <p class="text-xs lg:text-sm text-gray-500 dark:text-slate-400 mb-1"><?= lang('WaliKelas/ProgresNilai.stat_safe_subject') ?></p>
         <p class="text-2xl lg:text-3xl font-bold text-blue-600 dark:text-blue-400"><?= $statistik_umum['mapel_aman'] ?></p>
         <p class="text-xs text-gray-400 mt-1"><?= $statistik_umum['persen_aman'] ?? 0 ?>%</p>
        </div>
        <div class="w-10 h-10 lg:w-12 lg:h-12 rounded-xl bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 flex items-center justify-center transition-transform group-hover:scale-110">
         <svg class="w-5 h-5 lg:w-6 lg:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
        </div>
       </div>
     </div>

     <div class="bg-white dark:bg-slate-800 rounded-xl p-4 shadow-sm border border-gray-100 dark:border-slate-700 group">
       <div class="flex items-start justify-between">
        <div>
         <p class="text-xs lg:text-sm text-gray-500 dark:text-slate-400 mb-1"><?= lang('WaliKelas/ProgresNilai.stat_warn_subject') ?></p>
         <p class="text-2xl lg:text-3xl font-bold text-amber-600 dark:text-amber-500"><?= $statistik_umum['mapel_rawan'] ?></p>
         <p class="text-xs text-gray-400 mt-1"><?= $statistik_umum['persen_rawan'] ?? 0 ?>%</p>
        </div>
        <div class="w-10 h-10 lg:w-12 lg:h-12 rounded-xl bg-amber-50 dark:bg-amber-900/30 text-amber-600 dark:text-amber-500 flex items-center justify-center transition-transform group-hover:scale-110">
         <svg class="w-5 h-5 lg:w-6 lg:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v2m0 4v2m0 0v2m0-2H9m3 0h3" /></svg>
        </div>
       </div>
     </div>
    </div>

    <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-gray-100 dark:border-slate-700 p-4 lg:p-6 mb-6">
        <div class="flex items-center justify-between mb-6 pb-4 border-b border-gray-100 dark:border-slate-700">
            <div>
                <h2 class="text-lg font-bold text-gray-800 dark:text-slate-100"><?= lang('WaliKelas/ProgresNilai.chart_title') ?></h2>
                <p class="text-xs text-gray-500 dark:text-slate-400 mt-1"><?= lang('WaliKelas/ProgresNilai.chart_subtitle') ?></p>
            </div>
            <div class="p-2 bg-tema-light text-tema rounded-lg">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" /></svg>
            </div>
        </div>
        
        <div class="relative w-full overflow-x-auto pb-4 custom-scrollbar">
            <div class="bar-chart flex items-end gap-2 lg:gap-4 min-w-[600px] h-64 mt-4 border-b border-gray-100 dark:border-slate-700" id="chartContainer">
                </div>
        </div>

        <div class="flex flex-wrap justify-center items-center gap-4 mt-8 pt-6 border-t border-gray-50 dark:border-slate-700/50" id="legend">
            </div>
    </div>

    <div class="flex gap-0 mb-6 border-b border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800 rounded-t-xl overflow-x-auto custom-scrollbar">
        <button onclick="switchTab('semua', this)" class="tab-active border-b-2 border-tema text-tema px-6 py-3.5 font-bold flex items-center gap-2 whitespace-nowrap transition-all">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" /></svg> <?= lang('WaliKelas/ProgresNilai.tab_all_subjects') ?> 
        </button> 
        <button onclick="switchTab('detail', this)" class="tab-inactive border-b-2 border-transparent text-gray-500 dark:text-slate-400 hover:text-gray-700 dark:hover:text-slate-200 px-6 py-3.5 font-semibold flex items-center gap-2 whitespace-nowrap transition-all">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5a2 2 0 012-2h6a2 2 0 012 2v12a2 2 0 01-2 2H11a2 2 0 01-2-2V5zm0 0V3m0 2V3m6 0V3m0 2V3m0 2h.01" /></svg> <?= lang('WaliKelas/ProgresNilai.tab_subject_detail') ?> 
        </button> 
        <button onclick="switchTab('analisis', this)" class="tab-inactive border-b-2 border-transparent text-gray-500 dark:text-slate-400 hover:text-gray-700 dark:hover:text-slate-200 px-6 py-3.5 font-semibold flex items-center gap-2 whitespace-nowrap transition-all">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" /></svg> <?= lang('WaliKelas/ProgresNilai.tab_trend_analysis') ?> 
        </button>
    </div>

    <div id="tab-semua" class="tab-content block">
      <div id="gridView" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6"></div>
      
      <div id="listView" class="hidden bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-gray-100 dark:border-slate-700 overflow-hidden">
       <table class="w-full">
        <thead class="bg-gray-50 dark:bg-slate-900/50 border-b border-gray-100 dark:border-slate-700">
         <tr>
          <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 dark:text-slate-400 uppercase tracking-wider"><?= lang('WaliKelas/ProgresNilai.th_subject') ?></th>
          <th class="px-6 py-4 text-center text-xs font-bold text-gray-500 dark:text-slate-400 uppercase tracking-wider"><?= lang('WaliKelas/ProgresNilai.th_average') ?></th>
          <th class="px-6 py-4 text-center text-xs font-bold text-gray-500 dark:text-slate-400 uppercase tracking-wider"><?= lang('WaliKelas/ProgresNilai.th_highest') ?></th>
          <th class="px-6 py-4 text-center text-xs font-bold text-gray-500 dark:text-slate-400 uppercase tracking-wider"><?= lang('WaliKelas/ProgresNilai.th_lowest') ?></th>
          <th class="px-6 py-4 text-center text-xs font-bold text-gray-500 dark:text-slate-400 uppercase tracking-wider"><?= lang('WaliKelas/ProgresNilai.th_trend') ?></th>
          <th class="px-6 py-4 text-center text-xs font-bold text-gray-500 dark:text-slate-400 uppercase tracking-wider"><?= lang('WaliKelas/ProgresNilai.th_status') ?></th>
         </tr>
        </thead>
        <tbody class="divide-y divide-gray-100 dark:divide-slate-700" id="tableBody">
            </tbody>
       </table>
      </div>
    </div>

    <div id="tab-detail" class="tab-content hidden">
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
       <div class="lg:col-span-2">
        <label class="text-sm font-bold text-gray-600 dark:text-slate-300 mb-3 block"><?= lang('WaliKelas/ProgresNilai.sel_sub_detail') ?></label>
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3" id="subjectSelect">
            </div>
       </div>
       
       <div class="lg:col-span-2 bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-gray-100 dark:border-slate-700 p-6 transition-all" id="detailPanel">
        <div class="text-center py-12">
         <svg class="w-16 h-16 text-gray-300 dark:text-slate-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
         <p class="text-gray-500 dark:text-slate-400"><?= lang('WaliKelas/ProgresNilai.sel_sub_ph') ?></p>
        </div>
       </div>
      </div>
    </div>

    <div id="tab-analisis" class="tab-content hidden">
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
       <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-gray-100 dark:border-slate-700 p-6">
        <h3 class="text-lg font-bold text-gray-800 dark:text-slate-100 mb-4 pb-3 border-b border-gray-100 dark:border-slate-700">📈 <?= lang('WaliKelas/ProgresNilai.trend_safe') ?></h3>
        <div class="space-y-3" id="trenPositifContainer"></div>
       </div>
       
       <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-gray-100 dark:border-slate-700 p-6">
        <h3 class="text-lg font-bold text-gray-800 dark:text-slate-100 mb-4 pb-3 border-b border-gray-100 dark:border-slate-700">📉 <?= lang('WaliKelas/ProgresNilai.trend_warn') ?></h3>
        <div class="space-y-3" id="trenNegatifContainer"></div>
       </div>
      </div>
    </div>
<?= $this->endSection() ?>

<?= $this->section('modals') ?>

    <div id="studentModal" class="fixed inset-0 z-[60] hidden flex items-center justify-center p-4">
       <div class="modal-overlay absolute inset-0 bg-slate-900/70 backdrop-blur-sm transition-opacity" onclick="closeStudentModal()"></div>
       <div class="modal-content relative bg-white dark:bg-slate-800 rounded-3xl shadow-2xl w-full max-w-4xl max-h-[90vh] overflow-hidden flex flex-col transform scale-95 transition-all duration-300">
        
        <div class="bg-tema px-6 lg:px-8 py-5 flex items-center justify-between text-white shadow-md z-10">
         <div>
          <h2 id="modalTitle" class="text-xl lg:text-2xl font-extrabold tracking-wide"><?= lang('WaliKelas/ProgresNilai.modal_student_data') ?></h2>
          <p id="modalSubtitle" class="text-white/80 text-sm mt-0.5"><?= lang('WaliKelas/ProgresNilai.modal_student_subtitle') ?></p>
         </div>
         <button onclick="closeStudentModal()" class="p-2 hover:bg-white/20 rounded-xl transition-colors border border-transparent hover:border-white/30">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
         </button>
        </div>
        
        <div class="flex-1 overflow-auto custom-scrollbar">
         <table class="w-full whitespace-nowrap">
          <thead class="bg-gray-50/90 dark:bg-slate-900/90 sticky top-0 backdrop-blur-sm z-10 border-b border-gray-200 dark:border-slate-700">
           <tr>
            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 dark:text-slate-400 uppercase tracking-wider"><?= lang('WaliKelas/ProgresNilai.th_no') ?></th>
            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 dark:text-slate-400 uppercase tracking-wider"><?= lang('WaliKelas/ProgresNilai.th_student_name') ?></th>
            <th class="px-6 py-4 text-center text-xs font-bold text-gray-500 dark:text-slate-400 uppercase tracking-wider"><?= lang('WaliKelas/ProgresNilai.th_grade') ?></th>
            <th class="px-6 py-4 text-center text-xs font-bold text-gray-500 dark:text-slate-400 uppercase tracking-wider"><?= lang('WaliKelas/ProgresNilai.th_status') ?></th>
            <th class="px-6 py-4 text-center text-xs font-bold text-gray-500 dark:text-slate-400 uppercase tracking-wider"><?= lang('WaliKelas/ProgresNilai.th_action') ?></th>
           </tr>
          </thead>
          <tbody class="divide-y divide-gray-100 dark:divide-slate-700" id="studentTableBody">
            </tbody>
         </table>
        </div>
        
        <div class="bg-gray-50 dark:bg-slate-900 px-6 lg:px-8 py-5 border-t border-gray-200 dark:border-slate-700 flex justify-end gap-3 z-10">
            <button onclick="closeStudentModal()" class="px-5 py-2.5 border border-gray-300 dark:border-slate-600 text-gray-700 dark:text-slate-300 font-bold rounded-xl hover:bg-gray-100 dark:hover:bg-slate-800 transition-colors"><?= lang('WaliKelas/ProgresNilai.btn_close') ?></button> 
            <button id="exportBtn" class="px-5 py-2.5 bg-tema text-white font-bold rounded-xl hover-bg-tema transition-colors shadow-lg shadow-[var(--warna-primary)]/20 flex items-center gap-2"> 
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                <?= lang('WaliKelas/ProgresNilai.btn_export') ?>
            </button>
        </div>
       </div>
    </div>

    <div id="remediModal" class="fixed inset-0 z-[70] hidden flex items-center justify-center p-4">
       <div class="modal-overlay absolute inset-0 bg-slate-900/80 backdrop-blur-sm transition-opacity" onclick="closeRemediModal()"></div>
       <div class="modal-content relative bg-white dark:bg-slate-800 rounded-3xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-hidden flex flex-col transform scale-95 transition-all duration-300">
        
        <div class="bg-gradient-to-r from-orange-500 to-orange-600 px-6 lg:px-8 py-5 flex items-center justify-between text-white shadow-md z-10">
         <div>
          <h2 id="remediTitle" class="text-xl lg:text-2xl font-extrabold tracking-wide"><?= lang('WaliKelas/ProgresNilai.modal_remedi_title') ?></h2>
          <p id="remediSubtitle" class="text-orange-100 text-sm mt-0.5"><?= lang('WaliKelas/ProgresNilai.modal_remedi_subtitle') ?></p>
         </div>
         <button onclick="closeRemediModal()" class="p-2 hover:bg-orange-700 rounded-xl transition-colors border border-transparent hover:border-orange-400">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
         </button>
        </div>
        
        <div class="flex-1 overflow-y-auto p-6 lg:p-8 custom-scrollbar">
         <form id="remediForm" onsubmit="submitRemediProgram(event)">
          <div class="mb-5">
           <label class="block text-sm font-bold text-gray-800 dark:text-slate-200 mb-2"><?= lang('WaliKelas/ProgresNilai.form_prog_name') ?></label> 
           <input type="text" id="programName" class="w-full px-4 py-3 bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-700 rounded-xl focus:outline-none focus:border-orange-500 focus:ring-4 focus:ring-orange-500/20 text-gray-800 dark:text-slate-200 transition-all" required>
          </div>
          
          <div class="grid grid-cols-2 gap-4 mb-5">
           <div>
            <label class="block text-sm font-bold text-gray-800 dark:text-slate-200 mb-2"><?= lang('WaliKelas/ProgresNilai.form_duration') ?></label> 
            <input type="number" id="duration" min="1" max="12" placeholder="4" class="w-full px-4 py-3 bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-700 rounded-xl focus:outline-none focus:border-orange-500 focus:ring-4 focus:ring-orange-500/20 text-gray-800 dark:text-slate-200 transition-all" required>
           </div>
           <div>
            <label class="block text-sm font-bold text-gray-800 dark:text-slate-200 mb-2"><?= lang('WaliKelas/ProgresNilai.form_frequency') ?></label> 
            <select id="frequency" class="w-full px-4 py-3 bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-700 rounded-xl focus:outline-none focus:border-orange-500 focus:ring-4 focus:ring-orange-500/20 text-gray-800 dark:text-slate-200 transition-all cursor-pointer" required> 
             <option value=""><?= lang('WaliKelas/ProgresNilai.form_freq_sel') ?></option> 
             <option value="1"><?= lang('WaliKelas/ProgresNilai.form_freq_1') ?></option> 
             <option value="2"><?= lang('WaliKelas/ProgresNilai.form_freq_2') ?></option> 
             <option value="3"><?= lang('WaliKelas/ProgresNilai.form_freq_3') ?></option> 
            </select>
           </div>
          </div>
          
          <div class="mb-6">
           <label class="block text-sm font-bold text-gray-800 dark:text-slate-200 mb-3"><?= lang('WaliKelas/ProgresNilai.form_method') ?></label>
           <div class="space-y-3 bg-gray-50 dark:bg-slate-900 p-4 rounded-xl border border-gray-200 dark:border-slate-700">
            <div class="flex items-center"><input type="checkbox" id="method1" class="w-4 h-4 text-orange-500 rounded focus:ring-orange-500 dark:bg-slate-800 dark:border-slate-600"> <label for="method1" class="ml-3 text-sm font-medium text-gray-700 dark:text-slate-300"><?= lang('WaliKelas/ProgresNilai.opt_meth_1') ?></label></div>
            <div class="flex items-center"><input type="checkbox" id="method2" class="w-4 h-4 text-orange-500 rounded focus:ring-orange-500 dark:bg-slate-800 dark:border-slate-600"> <label for="method2" class="ml-3 text-sm font-medium text-gray-700 dark:text-slate-300"><?= lang('WaliKelas/ProgresNilai.opt_meth_2') ?></label></div>
            <div class="flex items-center"><input type="checkbox" id="method3" class="w-4 h-4 text-orange-500 rounded focus:ring-orange-500 dark:bg-slate-800 dark:border-slate-600"> <label for="method3" class="ml-3 text-sm font-medium text-gray-700 dark:text-slate-300"><?= lang('WaliKelas/ProgresNilai.opt_meth_3') ?></label></div>
           </div>
          </div>
          
          <div class="mb-2 bg-orange-50 dark:bg-orange-900/10 border border-orange-200 dark:border-orange-800/30 rounded-xl p-4">
           <p class="text-sm font-bold text-orange-800 dark:text-orange-500 mb-3"><?= lang('WaliKelas/ProgresNilai.form_req_student') ?></p>
           <div id="remediStudentList" class="space-y-2 max-h-40 overflow-y-auto custom-scrollbar">
            </div>
          </div>
         </form>
        </div>
        
        <div class="bg-gray-50 dark:bg-slate-900 px-6 lg:px-8 py-5 border-t border-gray-200 dark:border-slate-700 flex justify-end gap-3 z-10">
            <button onclick="closeRemediModal()" class="px-5 py-2.5 border border-gray-300 dark:border-slate-600 text-gray-700 dark:text-slate-300 font-bold rounded-xl hover:bg-gray-100 dark:hover:bg-slate-800 transition-colors"><?= lang('WaliKelas/ProgresNilai.btn_cancel') ?></button> 
            <button onclick="submitRemediProgram(event)" class="px-5 py-2.5 bg-orange-500 text-white font-bold rounded-xl hover:bg-orange-600 transition-all shadow-lg shadow-orange-500/30 flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8m0 8H8m4 0h4" /></svg> 
                <?= lang('WaliKelas/ProgresNilai.btn_set_program') ?> 
            </button>
        </div>
       </div>
    </div>

    <!-- Modal Profile Siswa (Ringkasan Semua Mapel) -->
    <div id="profileSiswaModal" class="fixed inset-0 z-[80] hidden flex items-center justify-center p-4">
       <div class="modal-overlay absolute inset-0 bg-slate-900/80 backdrop-blur-md transition-opacity" onclick="closeProfileSiswaModal()"></div>
       <div class="modal-content relative bg-white dark:bg-slate-800 rounded-3xl shadow-2xl w-full max-w-2xl max-h-[85vh] overflow-hidden flex flex-col transform scale-95 transition-all duration-300">
        
        <div class="bg-indigo-600 px-6 lg:px-8 py-5 flex items-center justify-between text-white shadow-md z-10">
         <div>
          <h2 id="profileTitle" class="text-xl lg:text-2xl font-black italic tracking-wide">Ringkasan Nilai</h2>
          <p id="profileSiswaName" class="text-indigo-100 text-sm mt-0.5 uppercase font-bold tracking-widest">Nama Siswa</p>
         </div>
         <button onclick="closeProfileSiswaModal()" class="p-2 hover:bg-white/20 rounded-xl transition-colors">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
         </button>
        </div>
        
        <div class="flex-1 overflow-y-auto p-0 lg:p-0 custom-scrollbar bg-gray-50 dark:bg-slate-900">
            <table class="w-full text-left border-collapse">
                <thead class="bg-indigo-50 dark:bg-indigo-900/20 text-indigo-700 dark:text-indigo-400 text-[11px] font-black uppercase tracking-widest sticky top-0 backdrop-blur-md">
                    <tr>
                        <th class="px-6 py-4">Mata Pelajaran</th>
                        <th class="px-6 py-4 text-center">Nilai</th>
                        <th class="px-6 py-4 text-center">Status</th>
                    </tr>
                </thead>
                <tbody id="profileSiswaBody" class="divide-y divide-gray-100 dark:divide-slate-800/50">
                    <!-- Dinamis via JS -->
                </tbody>
            </table>
        </div>
        
        <div class="bg-white dark:bg-slate-800 px-6 lg:px-8 py-5 border-t border-gray-100 dark:border-slate-700 flex justify-end gap-3 z-10">
            <button onclick="closeProfileSiswaModal()" class="px-6 py-2.5 bg-indigo-600 text-white font-black rounded-xl hover:bg-indigo-700 transition-all shadow-lg shadow-indigo-600/20">Selesai</button> 
        </div>
       </div>
    </div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
  <script>
    // Jembatan Data PHP ke Javascript
    window.dynamicSubjectsData = <?= $subjectsData ?? '[]' ?>;
    window.dynamicStudentsData = <?= $studentsData ?? '[]' ?>;
    window.BASE_URL = '<?= rtrim(base_url(), '/') ?>';
    
    // KAMUS JS
    window.LANG = {
        no_data: "<?= lang('WaliKelas/ProgresNilai.no_data') ?>",
        no_detail: "<?= lang('WaliKelas/ProgresNilai.no_detail') ?>",
        lbl_detail: "<?= lang('WaliKelas/ProgresNilai.lbl_detail') ?>",
        lbl_unrated: "<?= lang('WaliKelas/ProgresNilai.lbl_unrated') ?>",
        lbl_critical: "<?= lang('WaliKelas/ProgresNilai.lbl_critical') ?>",
        lbl_warning: "<?= lang('WaliKelas/ProgresNilai.lbl_warning') ?>",
        lbl_safe: "<?= lang('WaliKelas/ProgresNilai.lbl_safe') ?>",
        rec_aman: "<?= lang('WaliKelas/ProgresNilai.rec_aman') ?>",
        rec_rawan: "<?= lang('WaliKelas/ProgresNilai.rec_rawan') ?>",
        rec_belum: "<?= lang('WaliKelas/ProgresNilai.rec_belum') ?>",
        rec_kritis: "<?= lang('WaliKelas/ProgresNilai.rec_kritis') ?>",
        rec_title: "<?= lang('WaliKelas/ProgresNilai.rec_title') ?>",
        btn_view_spread: "<?= lang('WaliKelas/ProgresNilai.btn_view_spread') ?>",
        btn_make_remedy: "<?= lang('WaliKelas/ProgresNilai.btn_make_remedy') ?>",
        trend_safe_lbl: "<?= lang('WaliKelas/ProgresNilai.trend_safe_lbl') ?>",
        trend_warn_lbl: "<?= lang('WaliKelas/ProgresNilai.trend_warn_lbl') ?>",
        trend_no_safe: "<?= lang('WaliKelas/ProgresNilai.trend_no_safe') ?>",
        trend_no_warn: "<?= lang('WaliKelas/ProgresNilai.trend_no_warn') ?>",
        remedi_prog_prefix: "<?= lang('WaliKelas/ProgresNilai.remedi_prog_prefix') ?>",
        remedi_no_student: "<?= lang('WaliKelas/ProgresNilai.remedi_no_student') ?>",
        remedi_final_score: "<?= lang('WaliKelas/ProgresNilai.remedi_final_score') ?>",
        remedi_succ_msg: "<?= lang('WaliKelas/ProgresNilai.remedi_succ_msg') ?>",
        modal_student_title: "<?= lang('WaliKelas/ProgresNilai.modal_student_title') ?>",
        modal_student_sub: "<?= lang('WaliKelas/ProgresNilai.modal_student_sub') ?>"
    };
  </script>
  <script src="<?= base_url('assets/js/WaliKelas/progres-nilai.js') ?>?v=<?= time() ?>"></script>
<?= $this->endSection() ?>