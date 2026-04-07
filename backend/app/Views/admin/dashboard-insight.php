<?= $this->extend('layout/main') ?>

<?= $this->section('title') ?>
  <?= lang('Admin/DashboardInsight.page_title') ?> - Rapor Digital SMPIT Ad Durrah
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
    :root { --warna-scroll: <?= $color['warna_primary'] ?>; }
</style>
  <link rel="stylesheet" href="<?= base_url('assets/css/Admin/dashboard-insight.css') ?>">
<?= $this->endSection() ?>

<?= $this->section('content') ?>
     <div class="mb-6">
      <div class="flex items-center gap-2 text-sm text-gray-500 mb-2"><span><?= lang('Admin/DashboardInsight.breadcrumb') ?></span>
       <svg class="w-4 h-4" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
       </svg><span class="text-[<?= $color['warna_primary'] ?>] font-medium"><?= lang('Admin/DashboardInsight.page_title') ?></span>
      </div>
      <h1 class="text-xl md:text-3xl font-bold text-gray-800 dark:text-white"><?= lang('Admin/DashboardInsight.page_title') ?></h1>
      <p class="text-sm md:text-base text-gray-600 mt-1 dark:text-gray-200"><?= lang('Admin/DashboardInsight.page_desc') ?></p>
     </div>

     <div class="bg-white rounded-2xl p-4 md:p-6 shadow-sm border border-gray-100 mb-6 dark:bg-slate-800 dark:border-slate-700">
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
       <div>
           <label class="block text-sm font-medium text-gray-700 mb-2 dark:text-white"><?= lang('Admin/DashboardInsight.filter_year') ?></label> 
           <select id="filter_tahun" onchange="updateRombelDropdown()" class="w-full px-4 py-2.5 bg-white dark:bg-slate-700 dark:border-slate-600 dark:text-gray-200 border border-gray-200 rounded-xl text-sm font-medium text-gray-700 focus:outline-none focus:ring-2 focus:ring-[<?= $color['warna_primary'] ?>] transition-colors cursor-pointer outline-none"> 
             <option value=""><?= lang('Admin/DashboardInsight.all_years') ?></option>
             <?php if(!empty($tahun_ajaran)): ?>
                 <?php foreach($tahun_ajaran as $thn): ?>
                     <option value="<?= esc($thn) ?>" <?= ($thn == $active_tahun) ? 'selected' : '' ?>><?= esc($thn) ?></option>
                 <?php endforeach; ?>
             <?php endif; ?>
           </select>
       </div>
       <div>
           <label class="block text-sm font-medium text-gray-700 mb-2 dark:text-white"><?= lang('Admin/DashboardInsight.filter_semester') ?></label> 
           <select id="filter_semester" onchange="updateRombelDropdown()" class="w-full px-4 py-2.5 bg-white border dark:bg-slate-700 dark:border-slate-600 dark:text-gray-200  border-gray-200 rounded-xl text-sm font-medium text-gray-700 focus:outline-none focus:ring-2 focus:ring-[<?= $color['warna_primary'] ?>] transition-colors cursor-pointer outline-none"> 
               <option value=""><?= lang('Admin/DashboardInsight.all_semesters') ?></option> 
               <option value="Ganjil" <?= ($active_semester == 'Ganjil') ? 'selected' : '' ?>><?= lang('Admin/DashboardInsight.odd_semester') ?></option> 
               <option value="Genap" <?= ($active_semester == 'Genap') ? 'selected' : '' ?>><?= lang('Admin/DashboardInsight.even_semester') ?></option> 
           </select>
       </div>
       <div>
           <label class="block text-sm font-medium text-gray-700 mb-2 dark:text-white"><?= lang('Admin/DashboardInsight.filter_level') ?></label> 
           <select id="filter_tingkat" onchange="fetchDashboardData()" class="w-full px-4 py-2.5 bg-white dark:bg-slate-700 dark:border-slate-600 dark:text-gray-200  border border-gray-200 rounded-xl text-sm font-medium text-gray-700 focus:outline-none focus:ring-2 focus:ring-[<?= $color['warna_primary'] ?>] transition-colors cursor-pointer outline-none"> 
               <option value=""><?= lang('Admin/DashboardInsight.all_levels') ?></option> 
               <option value="VII"><?= lang('Admin/DashboardInsight.class_level') ?> VII</option> 
               <option value="VIII"><?= lang('Admin/DashboardInsight.class_level') ?> VIII</option> 
               <option value="IX"><?= lang('Admin/DashboardInsight.class_level') ?> IX</option> 
           </select>
       </div>
       <div>
           <label class="block text-sm font-medium text-gray-700 mb-2 dark:text-white"><?= lang('Admin/DashboardInsight.filter_room') ?></label> 
           <select id="filter_rombel" onchange="fetchDashboardData()" class="w-full px-4 py-2.5 bg-white dark:bg-slate-700 dark:border-slate-600 dark:text-gray-200  border border-gray-200 rounded-xl text-sm font-medium text-gray-700 focus:outline-none focus:ring-2 focus:ring-[<?= $color['warna_primary'] ?>] transition-colors cursor-pointer outline-none"> 
               <option value=""><?= lang('Admin/DashboardInsight.all_rooms') ?></option> 
                <?php 
                $current_tingkat = '';
                if(!empty($rombels)):
                foreach($rombels as $r): 
                    if ($current_tingkat !== $r['tingkat']) {
                        if ($current_tingkat !== '') echo '</optgroup>';
                        $current_tingkat = $r['tingkat'];
                        echo '<optgroup label="' . lang('Admin/DashboardInsight.class_level') . ' ' . esc($current_tingkat) . '">';
                    }
                ?>
                    <option value="<?= $r['id'] ?>"><?= esc($current_tingkat) ?> - <?= esc($r['nama_rombel']) ?></option>
                <?php endforeach; ?>
                <?php if ($current_tingkat !== '') echo '</optgroup>'; ?>
                <?php endif; ?>
           </select>
       </div>
      </div>
     </div>
     
     <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
         <div class="card-stat bg-white rounded-2xl dark:bg-slate-800 dark:border-slate-700  p-6 shadow-sm border border-gray-100 relative overflow-hidden">
             <div class="loader-overlay absolute inset-0 bg-white/70 dark:bg-slate-800/70 backdrop-blur-sm z-10 hidden flex items-center justify-center"><div class="w-6 h-6 border-4 border-[<?= $color['warna_primary'] ?>] border-t-transparent rounded-full animate-spin"></div></div>
             <div class="flex items-start justify-between mb-4">
              <div class="w-12 h-12 rounded-xl bg-emerald-100 flex items-center justify-center dark:bg-emerald-900/30 border border-emerald-200 dark:border-emerald-800/50">
               <svg class="w-6 h-6 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" /></svg>
              </div>
             </div>
             <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1"><?= lang('Admin/DashboardInsight.stat_avg_school') ?></p>
             <h3 class="text-3xl font-bold dark:text-white text-gray-800" id="ui_avg_sekolah">0</h3>
             <p class="text-xs text-gray-500 dark:text-gray-400  mt-2" id="ui_avg_desc"><?= lang('Admin/DashboardInsight.stat_loading') ?></p>
         </div>
         
         <div class="card-stat bg-white rounded-2xl dark:bg-slate-800 dark:border-slate-700 p-6 shadow-sm border border-gray-100 relative overflow-hidden">
             <div class="loader-overlay absolute inset-0 bg-white/70 dark:bg-slate-800/70 backdrop-blur-sm z-10 hidden flex items-center justify-center"><div class="w-6 h-6 border-4 border-[<?= $color['warna_primary'] ?>] border-t-transparent rounded-full animate-spin"></div></div>
             <div class="flex items-start justify-between mb-4">
              <div class="w-12 h-12 rounded-xl bg-blue-100 dark:bg-blue-900/30 border border-blue-200 dark:border-blue-800/50 flex items-center justify-center">
               <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
              </div>
             </div>
             <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1"><?= lang('Admin/DashboardInsight.stat_completeness') ?></p>
             <h3 class="text-3xl font-bold text-gray-800 dark:text-white" id="ui_tuntas_pct">0%</h3>
             <p class="text-xs text-gray-500 mt-2 dark:text-gray-400" id="ui_tuntas_desc"><?= lang('Admin/DashboardInsight.stat_loading') ?></p>
         </div>
         
         <div class="card-stat bg-white rounded-2xl p-6 dark:bg-slate-800 dark:border-slate-700 shadow-sm border border-gray-100 relative overflow-hidden">
             <div class="loader-overlay absolute inset-0 bg-white/70 dark:bg-slate-800/70 backdrop-blur-sm z-10 hidden flex items-center justify-center"><div class="w-6 h-6 border-4 border-[<?= $color['warna_primary'] ?>] border-t-transparent rounded-full animate-spin"></div></div>
             <div class="flex items-start justify-between mb-4">
              <div class="w-12 h-12 rounded-xl bg-amber-100 dark:bg-amber-900/30 border border-amber-200 dark:border-amber-800/50 flex items-center justify-center">
               <svg class="w-6 h-6 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
              </div>
             </div>
             <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1"><?= lang('Admin/DashboardInsight.stat_need_guide') ?></p>
             <h3 class="text-3xl font-bold text-gray-800 dark:text-white" id="ui_bimbingan_total">0</h3>
             <p class="text-xs text-gray-500 mt-2 dark:text-gray-400" id="ui_bimbingan_desc"><?= lang('Admin/DashboardInsight.stat_loading') ?></p>
         </div>
         
         <div class="card-stat bg-gradient-to-br from-[<?= $color['warna_primary'] ?>] to-[<?= $color['warna_primary'] ?>] rounded-2xl p-6 shadow-lg relative overflow-hidden">
             <div class="flex items-start justify-between mb-4">
              <div class="w-12 h-12 rounded-xl bg-white/20 flex items-center justify-center border border-white/20">
               <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" /></svg>
              </div><span class="text-[10px] font-black uppercase tracking-wider text-white  bg-white/20 px-2.5 py-1 rounded-lg border border-white/20"> <?= lang('Admin/DashboardInsight.stat_on_track') ?> </span>
             </div>
             <p class="text-sm font-medium text-white/90 mb-1"><?= lang('Admin/DashboardInsight.stat_progress') ?></p>
             <h3 class="text-3xl font-bold text-white">78%</h3>
             <p class="text-xs text-white/80 mt-2"><?= lang('Admin/DashboardInsight.stat_target') ?></p>
         </div>
     </div>
     
     <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
         <div class="lg:col-span-2 bg-white rounded-2xl p-6 shadow-sm border border-gray-100 relative dark:bg-slate-800 dark:border-slate-700 transition-colors">
             <div class="loader-overlay absolute inset-0 bg-white/70 dark:bg-slate-800/70 backdrop-blur-sm z-10 hidden flex items-center justify-center"><div class="w-8 h-8 border-4 border-[<?= $color['warna_primary'] ?>] border-t-transparent rounded-full animate-spin"></div></div>
             <div class="flex items-center justify-between mb-6">
              <div>
               <h3 class="text-lg font-bold text-gray-800 dark:text-white"><?= lang('Admin/DashboardInsight.chart_level_title') ?></h3>
               <p class="text-sm text-gray-500 mt-1 dark:text-gray-400"><?= lang('Admin/DashboardInsight.chart_level_desc') ?></p>
              </div>
              <button class="px-4 py-2 bg-[<?= $color['warna_primary'] ?>] border border-transparent rounded-xl text-sm font-bold text-white hover:brightness-110 transition-all shadow-sm outline-none">
                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="white" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                </svg> <?= lang('Admin/DashboardInsight.btn_export') ?> </button>
             </div>
             <div class="chart-container">
              <canvas id="levelChart"></canvas>
             </div>
         </div>
         
         <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 relative dark:bg-slate-800 dark:border-slate-700 transition-colors">
             <div class="loader-overlay absolute inset-0 bg-white/70 dark:bg-slate-800/70 backdrop-blur-sm z-10 hidden flex items-center justify-center"><div class="w-8 h-8 border-4 border-[<?= $color['warna_primary'] ?>] border-t-transparent rounded-full animate-spin"></div></div>
             <div class="mb-6">
              <h3 class="text-lg font-bold text-gray-800 dark:text-white"><?= lang('Admin/DashboardInsight.chart_status_title') ?></h3>
              <p class="text-sm text-gray-500 mt-1 dark:text-gray-400"><?= lang('Admin/DashboardInsight.chart_status_desc') ?></p>
             </div>
             <div class="chart-container h-64">
              <canvas id="statusChart"></canvas>
             </div>
             <div class="space-y-3 mt-6">
              <div class="flex items-center justify-between p-3.5 bg-emerald-50 border border-emerald-100 rounded-xl dark:bg-slate-700/50 dark:border-slate-600 transition-colors">
               <div class="flex items-center gap-3"><span class="w-3 h-3 bg-emerald-500 shadow-[0_0_8px_rgba(16,185,129,0.5)] rounded-full"></span> <span class="text-sm font-bold text-emerald-900 dark:text-emerald-400"><?= lang('Admin/DashboardInsight.status_complete') ?></span>
               </div><span class="text-sm font-black text-emerald-700 dark:text-emerald-300" id="ui_dist_tuntas">0 <?= lang('Admin/DashboardInsight.students') ?></span>
              </div>
              <div class="flex items-center justify-between p-3.5 bg-amber-50 border border-amber-100 rounded-xl dark:bg-slate-700/50 dark:border-slate-600 transition-colors">
               <div class="flex items-center gap-3"><span class="w-3 h-3 bg-amber-500 shadow-[0_0_8px_rgba(245,158,11,0.5)] rounded-full"></span> <span class="text-sm font-bold text-amber-900 dark:text-amber-400"><?= lang('Admin/DashboardInsight.status_guide') ?></span>
               </div><span class="text-sm font-black text-amber-700 dark:text-amber-300" id="ui_dist_bimbingan">0 <?= lang('Admin/DashboardInsight.students') ?></span>
              </div>
              <div class="flex items-center justify-between p-3.5 bg-gray-50 border border-gray-200 rounded-xl dark:bg-slate-700/50 dark:border-slate-600 transition-colors">
               <div class="flex items-center gap-3"><span class="w-3 h-3 bg-gray-400 shadow-[0_0_8px_rgba(156,163,175,0.5)] rounded-full"></span> <span class="text-sm font-bold text-gray-700 dark:text-gray-300"><?= lang('Admin/DashboardInsight.status_remedial') ?></span>
               </div><span class="text-sm font-black text-gray-600 dark:text-gray-400" id="ui_dist_remedial">0 <?= lang('Admin/DashboardInsight.students') ?></span>
              </div>
             </div>
         </div>
     </div>
     
     <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 mb-8 relative dark:bg-slate-800 dark:border-slate-700 transition-colors">
      <div class="loader-overlay absolute inset-0 bg-white/70 dark:bg-slate-800/70 backdrop-blur-sm z-10 hidden flex items-center justify-center"><div class="w-8 h-8 border-4 border-[<?= $color['warna_primary'] ?>] border-t-transparent rounded-full animate-spin"></div></div>
      <div class="flex flex-col md:flex-row items-start md:items-center justify-between mb-6 gap-4">
       <div>
        <h3 class="text-lg font-bold text-gray-800 dark:text-white"><?= lang('Admin/DashboardInsight.chart_trend_title') ?></h3>
        <p class="text-sm text-gray-500 mt-1 dark:text-gray-400"><?= lang('Admin/DashboardInsight.chart_trend_desc') ?></p>
       </div>
       <div class="flex items-center gap-2 bg-gray-50 dark:bg-slate-900/50 p-1 rounded-xl border border-gray-200 dark:border-slate-700 transition-colors" id="trendFilterButtons">
         <button onclick="toggleTrendMode('semua', this)" class="trend-btn active px-4 py-2 bg-[<?= $color['warna_primary'] ?>] border border-transparent rounded-lg text-sm font-bold text-white shadow-sm transition-all outline-none"> 
           <?= lang('Admin/DashboardInsight.btn_all_subjects') ?> 
         </button> 
         <button onclick="toggleTrendMode('per_mapel', this)" class="trend-btn px-4 py-2 bg-transparent border border-transparent rounded-lg text-sm font-bold text-gray-600 dark:text-slate-400 hover:text-gray-900 dark:hover:text-white transition-all outline-none"> 
           <?= lang('Admin/DashboardInsight.btn_per_subject') ?> 
         </button>
       </div>
      </div>
      <div class="chart-container h-80">
       <canvas id="trendChart"></canvas>
      </div>
     </div>

     <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
         <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 dark:bg-slate-800 dark:border-slate-700 transition-colors">
             <div class="mb-6">
              <h3 class="text-lg font-bold text-gray-800 dark:text-white"><?= lang('Admin/DashboardInsight.insight_char_title') ?></h3>
              <p class="text-sm text-gray-500 mt-1 dark:text-gray-400"><?= lang('Admin/DashboardInsight.insight_char_desc') ?></p>
             </div>
             
             <div class="mb-6 p-5 bg-emerald-50 border border-emerald-100 rounded-2xl dark:bg-emerald-900/10 dark:border-emerald-800/30 transition-colors">
              <div class="flex items-center gap-4 mb-4">
               <div class="w-12 h-12 rounded-xl bg-emerald-100 dark:bg-emerald-900/40 border border-emerald-200 dark:border-emerald-800/50 flex items-center justify-center flex-shrink-0 transition-colors">
                <svg class="w-6 h-6 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                </svg>
               </div>
               <div class="flex-1">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm font-bold text-emerald-900 dark:text-emerald-400"><?= lang('Admin/DashboardInsight.tahfidz_achieve') ?></span> 
                    <span id="ui_tahfidz_achieve" class="text-xl font-black text-emerald-600 dark:text-emerald-300">0%</span>
                </div>
                <div class="h-2.5 bg-emerald-200 dark:bg-emerald-900/50 rounded-full overflow-hidden transition-colors">
                 <div id="ui_tahfidz_bar" class="h-full bg-gradient-to-r from-emerald-400 to-emerald-600 rounded-full transition-all duration-1000 ease-out" style="width: 0%"></div>
                </div>
               </div>
              </div>
              <p class="text-xs font-bold text-emerald-700 dark:text-emerald-500 uppercase tracking-widest text-center"><?= lang('Admin/DashboardInsight.tahfidz_avg') ?></p>
             </div>
             
             <div class="space-y-4">
              <div class="flex items-center gap-4">
               <div class="w-10 h-10 rounded-xl bg-blue-100 dark:bg-blue-900/30 border border-blue-200 dark:border-blue-800/50 flex items-center justify-center transition-colors">
                <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5" />
                </svg>
               </div>
               <div class="flex-1">
                <div class="flex items-center justify-between mb-1.5">
                    <span class="text-sm dark:text-blue-400 font-bold text-gray-700"><?= lang('Admin/DashboardInsight.char_excellent') ?></span> 
                    <span id="ui_char_excellent" class="text-sm font-black text-blue-700 dark:text-blue-300">0 <?= lang('Admin/DashboardInsight.students') ?></span>
                </div>
                <div class="h-2 bg-gray-100 rounded-full overflow-hidden dark:bg-slate-700 transition-colors">
                 <div id="ui_char_bar" class="h-full bg-blue-500 rounded-full transition-all duration-1000 ease-out" style="width: 0%"></div>
                </div>
               </div>
              </div>

              <div class="flex items-center gap-4">
               <div class="w-10 h-10 rounded-xl bg-purple-100 dark:bg-purple-900/30 border border-purple-200 dark:border-purple-800/50 flex items-center justify-center transition-colors">
                <svg class="w-5 h-5 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
               </div>
               <div class="flex-1">
                <div class="flex items-center justify-between mb-1.5">
                    <span class="text-sm dark:text-purple-400 font-bold text-gray-700"><?= lang('Admin/DashboardInsight.attendance_rate') ?></span> 
                    <span id="ui_attendance_rate" class="text-sm font-black text-purple-700 dark:text-purple-300">0%</span>
                </div>
                <div class="h-2 bg-gray-100 rounded-full overflow-hidden dark:bg-slate-700 transition-colors">
                 <div id="ui_attendance_bar" class="h-full bg-purple-500 rounded-full transition-all duration-1000 ease-out" style="width: 0%"></div>
                </div>
               </div>
              </div>

              <div class="flex items-center gap-4">
               <div class="w-10 h-10 rounded-xl bg-amber-100 dark:bg-amber-900/30 border border-amber-200 dark:border-amber-800/50 flex items-center justify-center transition-colors">
                <svg class="w-5 h-5 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
               </div>
               <div class="flex-1">
                <div class="flex items-center justify-between mb-1.5">
                    <span class="text-sm font-bold dark:text-amber-400 text-gray-700"><?= lang('Admin/DashboardInsight.special_notes') ?></span> 
                    <span id="ui_special_notes" class="text-sm font-black text-amber-700 dark:text-amber-300">0 <?= lang('Admin/DashboardInsight.students') ?></span>
                </div>
                <div class="h-2 bg-gray-100 rounded-full overflow-hidden dark:bg-slate-700 transition-colors">
                 <div id="ui_notes_bar" class="h-full bg-amber-500 rounded-full transition-all duration-1000 ease-out" style="width: 0%"></div>
                </div>
               </div>
              </div>
             </div>
         </div>
         
         <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 dark:bg-slate-800 dark:border-slate-700 transition-colors">
             <div class="mb-6">
              <h3 class="text-lg font-bold text-gray-800 dark:text-white"><?= lang('Admin/DashboardInsight.recom_title') ?></h3>
              <p class="text-sm text-gray-500 mt-1 dark:text-gray-400"><?= lang('Admin/DashboardInsight.recom_desc') ?></p>
             </div>
             <div class="space-y-4">
                 <div class="insight-card p-5 bg-emerald-50 rounded-2xl border border-emerald-100 dark:bg-emerald-900/10 dark:border-emerald-800/30 transition-colors">
                  <div class="flex items-start gap-4">
                   <div class="w-10 h-10 rounded-xl bg-emerald-100 dark:bg-emerald-900/40 border border-emerald-200 dark:border-emerald-800/50 flex items-center justify-center flex-shrink-0 transition-colors">
                    <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                    </svg>
                   </div>
                   <div class="flex-1">
                    <h4 class="text-sm font-bold text-emerald-900 mb-1 dark:text-emerald-400"><?= lang('Admin/DashboardInsight.alert_increase') ?></h4>
                    <p id="ui_recom_good" class="text-xs font-medium text-emerald-800 leading-relaxed dark:text-emerald-500">Menganalisis data...</p>
                   </div>
                  </div>
                 </div>
                 <div class="insight-card p-5 bg-amber-50 rounded-2xl border border-amber-100 dark:bg-amber-900/10 dark:border-amber-800/30 transition-colors">
                  <div class="flex items-start gap-4">
                   <div class="w-10 h-10 rounded-xl bg-amber-100 dark:bg-amber-900/40 border border-amber-200 dark:border-amber-800/50 flex items-center justify-center flex-shrink-0 transition-colors">
                    <svg class="w-5 h-5 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                   </div>
                   <div class="flex-1">
                    <h4 class="text-sm font-bold text-amber-900 mb-1 dark:text-amber-400"><?= lang('Admin/DashboardInsight.alert_attention') ?></h4>
                    <p id="ui_recom_warn" class="text-xs font-medium text-amber-800 leading-relaxed dark:text-amber-500">Menganalisis data...</p>
                   </div>
                  </div>
                 </div>
             </div>
         </div>
     </div>
<?= $this->endSection() ?>
  
<?= $this->section('modals') ?>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
  <script>
    const BASE_URL = "<?= rtrim(base_url(), '/') ?>";
    const themePrimary = '<?= esc($color['warna_primary'] ?? '#10b981') ?>';
    const themeSecondary = '<?= esc($color['warna_secondary'] ?? '#ecfdf5') ?>';
    
    window.LANG = {
        js_avg_value: "<?= lang('Admin/DashboardInsight.js_avg_value') ?: 'Rata-rata Nilai' ?>",
        js_from: "<?= lang('Admin/DashboardInsight.js_from') ?: 'Dari' ?>",
        js_active_students: "<?= lang('Admin/DashboardInsight.js_active_students') ?: 'siswa aktif' ?>",
        js_out_of: "<?= lang('Admin/DashboardInsight.js_out_of') ?: 'dari' ?>",
        students: "<?= lang('Admin/DashboardInsight.students') ?: 'siswa' ?>",
        js_of_total: "<?= lang('Admin/DashboardInsight.js_of_total') ?: '% dari total siswa' ?>",
        js_err_fetch: "<?= lang('Admin/DashboardInsight.js_err_fetch') ?: 'Gagal mengambil data:' ?>"
    };
  </script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="<?= base_url('assets/js/Admin/dashboard-insight.js') ?>?v=<?= time() ?>"></script>
<?= $this->endSection() ?>