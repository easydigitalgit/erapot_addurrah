<?= $this->extend('layout/main') ?>

<?= $this->section('title') ?>
  <?= lang('GuruMapel/DaftarKelas.page_title') ?> - Rapor Digital
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
  :root {
    --warna-scroll: <?= $color['warna_primary'] ?>; 
  }
</style>
<link rel="stylesheet" href="<?= base_url('assets/css/GuruMapel/daftarkelas-mapel.css') ?>">
<?= $this->endSection() ?>
<?= $this->section('content') ?>
     <div class="mb-6">
      <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white mb-2 transition-colors" id="pageTitle"><?= lang('GuruMapel/DaftarKelas.page_title') ?></h1>
      <p class="text-base text-gray-600 dark:text-slate-400 font-medium transition-colors" id="pageSubtitle"><?= lang('GuruMapel/DaftarKelas.page_subtitle') ?></p>
     </div>

     <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
      <div class="summary-card bg-[<?= $color['warna_secondary']?>]/40 dark:bg-slate-800 border-[<?= $color['warna_primary'] ?>] dark:border-slate-700 shadow-sm transition-colors">
       <div class="flex items-center gap-3 mb-2">
        <div class="stat-icon bg-emerald-500 shadow-sm">
         <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
        </div>
       </div>
       <p class="text-sm text-[<?= $color['warna_primary'] ?>] dark:text-[<?= $color['warna_primary'] ?>] font-bold mb-1 transition-colors"><?= lang('GuruMapel/DaftarKelas.stat_total_class') ?></p>
       <p class="text-3xl font-black text-[<?= $color['warna_primary'] ?>]/70 dark:text-white transition-colors"><?= $summary['total_kelas'] ?></p>
      </div>
      
      <div class="summary-card bg-[<?= $color['warna_secondary']?>]/40 dark:bg-slate-800 border-[<?= $color['warna_primary'] ?>] dark:border-slate-700 shadow-sm transition-colors">
       <div class="flex items-center gap-3 mb-2">
        <div class="stat-icon bg-blue-500 shadow-sm">
         <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
        </div>
       </div>
       <p class="text-sm text-[<?= $color['warna_primary'] ?>] dark:text-[<?= $color['warna_primary'] ?>] font-bold mb-1 transition-colors"><?= lang('GuruMapel/DaftarKelas.stat_hours') ?></p>
       <p class="text-3xl font-black text-[<?= $color['warna_primary'] ?>]/70 dark:text-white transition-colors"><?= $summary['total_jam'] ?></p>
      </div>
      
      <div class="summary-card bg-[<?= $color['warna_secondary']?>]/40 dark:bg-slate-800 border-[<?= $color['warna_primary'] ?>] dark:border-slate-700 shadow-sm transition-colors">
       <div class="flex items-center gap-3 mb-2">
        <div class="stat-icon bg-purple-500 shadow-sm">
         <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
        </div>
       </div>
       <p class="text-sm text-[<?= $color['warna_primary'] ?>] dark:text-[<?= $color['warna_primary'] ?>] font-bold mb-1 transition-colors"><?= lang('GuruMapel/DaftarKelas.stat_subject') ?></p>
       <p class="text-2xl font-black text-[<?= $color['warna_primary'] ?>]/70 dark:text-white truncate transition-colors"><?= $summary['mapel_utama'] ?></p>
      </div>
      
      <div class="summary-card bg-[<?= $color['warna_secondary']?>]/40 dark:bg-slate-800 border-[<?= $color['warna_primary'] ?>] dark:border-slate-700 shadow-sm transition-colors">
       <div class="flex items-center gap-3 mb-2">
        <div class="stat-icon bg-amber-500 shadow-sm">
         <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
        </div>
       </div>
       <p class="text-sm text-[<?= $color['warna_primary'] ?>] dark:text-[<?= $color['warna_primary'] ?>] font-bold mb-1 transition-colors"><?= lang('GuruMapel/DaftarKelas.stat_total_stud') ?></p>
       <p class="text-3xl font-black text-[<?= $color['warna_primary'] ?>]/70 dark:text-white transition-colors"><?= $summary['total_siswa'] ?></p>
      </div>
     </div>
     
     <div class="dashboard-card bg-white dark:bg-slate-800 h-fit flex-none min-h-0 mb-6 shadow-sm border border-gray-100 dark:border-slate-700 transition-colors">
      <div class="flex flex-col lg:flex-row gap-4 p-4 lg:p-6">
       <div class="flex-1 relative">
        <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400 dark:text-slate-400" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
        <input type="text" id="searchInput" placeholder="<?= lang('GuruMapel/DaftarKelas.search_ph') ?>" class="search-input w-full pl-10 pr-4 py-3 rounded-xl bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 text-gray-800 dark:text-white focus:outline-none focus:border-[<?= $color['warna_primary'] ?>] dark:focus:border-[<?= $color['warna_primary'] ?>] focus:ring-1 focus:ring-[<?= $color['warna_primary'] ?>] transition-colors outline-none" onkeyup="filterClasses()">
       </div>
       <div class="flex flex-wrap gap-2">
        <button class="filter-button active px-4 py-2.5 rounded-xl font-bold text-sm bg-[<?= $color['warna_primary'] ?>] text-white hover:brightness-110 outline-none flex items-center gap-2 shadow-sm transition-colors" onclick="filterByStatus(this, 'all')">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" /></svg> <?= lang('GuruMapel/DaftarKelas.filter_all') ?> 
        </button> 
        <button class="filter-button px-4 py-2.5 rounded-xl font-bold text-sm bg-gray-100 dark:bg-slate-700 text-gray-600 dark:text-slate-300 hover:bg-gray-200 dark:hover:bg-slate-600 hover:text-[<?= $color['warna_primary'] ?>] outline-none flex items-center gap-2 transition-colors" onclick="filterByStatus(this, 'selesai')">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg> <?= lang('GuruMapel/DaftarKelas.filter_done') ?> 
        </button> 
        <button class="filter-button px-4 py-2.5 rounded-xl font-bold text-sm bg-gray-100 dark:bg-slate-700 text-gray-600 dark:text-slate-300 hover:bg-gray-200 dark:hover:bg-slate-600 hover:text-[<?= $color['warna_primary'] ?>] outline-none flex items-center gap-2 transition-colors" onclick="filterByStatus(this, 'proses')">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg> <?= lang('GuruMapel/DaftarKelas.filter_progress') ?> 
        </button> 
        <button class="filter-button px-4 py-2.5 rounded-xl font-bold text-sm bg-gray-100 dark:bg-slate-700 text-gray-600 dark:text-slate-300 hover:bg-gray-200 dark:hover:bg-slate-600 hover:text-[<?= $color['warna_primary'] ?>] outline-none flex items-center gap-2 transition-colors" onclick="filterByStatus(this, 'belum')">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg> <?= lang('GuruMapel/DaftarKelas.filter_not_start') ?> 
        </button>
       </div>
      </div>
     </div>

     <div id="classGrid" class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6 mb-6">
      
      <?php foreach($kelas_cards as $card): ?>
      <?php 
         // Styling dinamis berdasarkan status
         $badge_class = $card['status'] == 'selesai' ? 'badge-success bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400 border-emerald-200 dark:border-emerald-800/50' : 
                        ($card['status'] == 'proses' ? 'badge-warning bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-400 border-amber-200 dark:border-amber-800/50' : 'badge-gray bg-gray-100 dark:bg-slate-700 text-gray-600 dark:text-slate-300 border-gray-200 dark:border-slate-600');
         $status_text = strtoupper($card['status'] == 'belum' ? lang('GuruMapel/DaftarKelas.filter_not_start') : lang('GuruMapel/DaftarKelas.filter_'.$card['status']));
         $progress_color = $card['status'] == 'selesai' ? 'emerald' : ($card['status'] == 'proses' ? 'amber' : 'gray');
      ?>
      
      <div class="class-card bg-white dark:bg-slate-800 rounded-3xl border border-gray-100 dark:border-slate-700 p-6 shadow-sm hover:shadow-lg hover:border-[<?= $color['warna_primary'] ?>] dark:hover:border-[<?= $color['warna_primary'] ?>] transition-all group" data-status="<?= $card['status'] ?>" data-search="<?= strtolower($card['nama_mapel'] . ' ' . $card['nama_rombel']) ?>">
       <div class="flex-1">
        <div class="flex items-start justify-between mb-4">
         <div class="flex-1 min-w-0 pr-2">
          <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-1 truncate transition-colors group-hover:text-[<?= $color['warna_primary'] ?>]"><?= $card['nama_mapel'] ?></h3>
          <div class="flex items-center gap-2 mb-2">
            <span class="text-sm font-black text-[<?= $color['warna_primary'] ?>] uppercase tracking-wider bg-[<?= $color['warna_primary'] ?>]/10 px-2.5 py-0.5 rounded-lg border border-[<?= $color['warna_primary'] ?>]/20">Kelas <?= $card['nama_rombel'] ?></span> 
          </div>
         </div>
         <span class="px-2.5 py-1 text-[10px] font-black uppercase tracking-wider rounded-lg border shadow-sm transition-colors <?= $badge_class ?>"><?= $status_text ?></span>
        </div>
        
        <div class="space-y-4 mb-5">
         <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-slate-400 transition-colors">
          <svg class="w-5 h-5 text-[<?= $color['warna_primary'] ?>]" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
          <span class="font-bold text-gray-800 dark:text-slate-200"><?= $card['jumlah_siswa'] ?></span> <?= lang('GuruMapel/DaftarKelas.active_students') ?>
         </div>
         
         <div class="bg-<?= $progress_color ?>-50 dark:bg-slate-700/50 rounded-xl p-4 border border-<?= $progress_color ?>-200 dark:border-slate-600 transition-colors">
          <div class="flex items-center justify-between mb-2.5">
            <span class="text-[11px] font-black text-gray-500 dark:text-slate-400 uppercase tracking-widest transition-colors"><?= lang('GuruMapel/DaftarKelas.progress_text') ?></span> 
            <span class="text-xs font-bold text-<?= $progress_color ?>-700 dark:text-<?= $progress_color ?>-400 transition-colors"><?= $card['progress'] ?>%</span>
          </div>
          <div class="w-full h-2 bg-gray-200 dark:bg-slate-600 rounded-full overflow-hidden transition-colors">
           <div class="h-full bg-<?= $progress_color ?>-500 transition-all duration-700 shadow-[0_0_8px_rgba(255,255,255,0.4)]" style="width: <?= $card['progress'] ?>%"></div>
          </div>
         </div>
        </div>
       </div>
       
       <div class="flex gap-2 pt-4 border-t border-gray-100 dark:border-slate-700 transition-colors">
            <a href="<?= base_url('guru/daftar-siswa?rombel=' . $card['rombel_id'] . '&mapel=' . $card['mapel_id']) ?>" 
              class="px-4 py-3 bg-[<?= $color['warna_primary'] ?>] hover:bg-[<?= $color['warna_primary'] ?>]/90 text-white text-sm font-bold rounded-xl transition-transform transform group-hover:-translate-y-1 shadow-md outline-none flex flex-1 items-center justify-center gap-2" 
              style="box-shadow: 0 10px 15px -3px <?= $color['warna_primary'] ?>40;">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewbox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg> 
                <?= lang('GuruMapel/DaftarKelas.btn_open_class') ?>
            </a> 
        </div>
      </div>
      <?php endforeach; ?>

     </div>
     
     <div id="emptyState" class="empty-state hidden bg-white dark:bg-slate-800 rounded-3xl border border-gray-100 dark:border-slate-700 py-16 flex flex-col items-center justify-center text-center shadow-sm transition-colors">
      <div class="w-24 h-24 bg-gray-50 dark:bg-slate-900/50 rounded-full flex items-center justify-center mb-4 border-2 border-dashed border-gray-200 dark:border-slate-600 transition-colors">
          <svg class="w-12 h-12 text-gray-400 dark:text-slate-500 transition-colors" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
      </div>
      <h3 class="text-xl font-bold text-gray-800 dark:text-white mb-2 transition-colors"><?= lang('GuruMapel/DaftarKelas.empty_title') ?></h3>
      <p class="text-sm font-medium text-gray-500 dark:text-slate-400 max-w-sm transition-colors"><?= lang('GuruMapel/DaftarKelas.empty_desc') ?></p>
     </div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
  <script src="<?= base_url('assets/js/GuruMapel/daftarkelas-mapel.js') ?>"></script>
<?= $this->endSection() ?>