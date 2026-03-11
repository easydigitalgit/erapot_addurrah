<?= $this->extend('layout/main') ?>

<?= $this->section('title') ?>
  <?= lang('GuruMapel/DaftarSiswa.page_title') ?> - Rapor Digital
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
  :root {
    --warna-scroll: <?= $color['warna_primary'] ?>; 
  }
  /* Paksa Modal Background Dark Mode */
  .dark .modal-content { background-color: #0f172a !important; border-color: #1e293b !important; }
</style>
<link rel="stylesheet" href="<?= base_url('assets/css/GuruMapel/daftar-siswa.css') ?>">
<?= $this->endSection() ?>

<?= $this->section('content') ?>
     <div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
      <div>
       <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:!text-white mb-2 transition-colors" id="pageTitle"><?= lang('GuruMapel/DaftarSiswa.page_title') ?></h1>
       <p class="text-base text-gray-600 dark:!text-slate-400 font-medium transition-colors" id="pageSubtitle"><?= lang('GuruMapel/DaftarSiswa.page_subtitle') ?></p>
      </div>

      <div class="w-full md:w-auto">
        <label class="block text-[10px] font-black text-gray-500 dark:!text-slate-400 uppercase tracking-widest mb-1 ml-1">Pindah Kelas / Mapel</label>
        <select onchange="window.location.href='?rombel='+this.value.split('|')[0]+'&mapel='+this.value.split('|')[1]" 
                class="w-full md:w-64 p-3 bg-white dark:!bg-slate-800 border-2 border-gray-200 dark:!border-slate-700 rounded-xl font-bold text-sm text-gray-800 dark:!text-white outline-none focus:border-[<?= $color['warna_primary'] ?>] transition-colors cursor-pointer shadow-sm">
            <?php if(!empty($allRombel)): ?>
                <?php foreach($allRombel as $rb): ?>
                    <option value="<?= $rb['rombel_id'] ?>|<?= $rb['mapel_id'] ?>" <?= ($rb['rombel_id'] == $info['rombel_id'] && $rb['mapel_id'] == $info['mapel_id']) ? 'selected' : '' ?>>
                        <?= esc($rb['nama_rombel']) ?> - <?= esc($rb['nama_mapel']) ?>
                    </option>
                <?php endforeach; ?>
            <?php else: ?>
                <option value="">Belum Ada Kelas</option>
            <?php endif; ?>
        </select>
      </div>
     </div>
     
     <div class="info-card bg-[<?= $color['warna_secondary'] ?>] dark:!bg-slate-800 border border-[<?= $color['warna_primary'] ?>]/80 dark:!border-slate-700 mb-6 shadow-sm transition-colors rounded-2xl p-5">
       <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="flex items-center gap-3">
         <div class="w-12 h-12 rounded-xl bg-emerald-500 dark:!bg-emerald-600 flex items-center justify-center shadow-lg flex-shrink-0 transition-colors">
          <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
         </div>
         <div class="min-w-0 pr-2">
          <p class="text-xs text-[<?= $color['warna_primary'] ?>] dark:!text-[<?= $color['warna_primary'] ?>] font-black uppercase tracking-wider mb-0.5 transition-colors"><?= lang('GuruMapel/DaftarSiswa.info_subject') ?></p>
          <p class="text-base font-bold text-[<?= $color['warna_primary'] ?>]/80 dark:!text-white truncate transition-colors"><?= $info['mapel'] ?></p>
         </div>
        </div>
        
        <div class="flex items-center gap-3">
         <div class="w-12 h-12 rounded-xl bg-blue-500 dark:!bg-blue-600 flex items-center justify-center shadow-lg flex-shrink-0 transition-colors">
          <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
         </div>
         <div class="min-w-0 pr-2">
          <p class="text-xs text-[<?= $color['warna_primary'] ?>] dark:!text-[<?= $color['warna_primary'] ?>] font-black uppercase tracking-wider mb-0.5 transition-colors"><?= lang('GuruMapel/DaftarSiswa.info_class') ?></p>
          <p class="text-base font-bold text-[<?= $color['warna_primary'] ?>]/80 dark:!text-white truncate transition-colors"><?= $info['rombel'] ?></p>
         </div>
        </div>
        
        <div class="flex items-center gap-3">
         <div class="w-12 h-12 rounded-xl bg-purple-500 dark:!bg-purple-600 flex items-center justify-center shadow-lg flex-shrink-0 transition-colors">
          <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
         </div>
         <div class="min-w-0 pr-2">
          <p class="text-xs text-[<?= $color['warna_primary'] ?>] dark:!text-[<?= $color['warna_primary'] ?>] font-black uppercase tracking-wider mb-0.5 transition-colors"><?= lang('GuruMapel/DaftarSiswa.info_student_count') ?></p>
          <p class="text-base font-bold text-[<?= $color['warna_primary'] ?>]/80 dark:!text-white truncate transition-colors"><?= $info['jumlah_siswa'] ?> <?= lang('GuruMapel/DaftarSiswa.info_student_text') ?></p>
         </div>
        </div>
        
        <div class="flex items-center gap-3">
         <div class="w-12 h-12 rounded-xl bg-amber-500 dark:!bg-amber-600 flex items-center justify-center shadow-lg flex-shrink-0 transition-colors">
          <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
         </div>
         <div class="min-w-0 pr-2">
          <p class="text-xs text-[<?= $color['warna_primary'] ?>] dark:!text-[<?= $color['warna_primary'] ?>] font-black uppercase tracking-wider mb-0.5 transition-colors"><?= lang('GuruMapel/DaftarSiswa.info_hours') ?></p>
          <p class="text-base font-bold text-[<?= $color['warna_primary'] ?>]/80 dark:!text-white truncate transition-colors"><?= $info['jam_mengajar'] ?></p>
         </div>
        </div>
       </div>
      </div>
      
      <div class="bg-white dark:!bg-slate-800 rounded-2xl shadow-sm border border-gray-200 dark:!border-slate-700 p-5 mb-6 transition-colors">
       <div class="flex flex-col lg:flex-row gap-4 items-stretch lg:items-center justify-between">
        <div class="flex flex-col sm:flex-row gap-4 flex-1">
          <div class="flex-1 relative">
            <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400 dark:!text-slate-500" fill="none" stroke="currentColor" viewbox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
            <input type="text" id="searchInput" placeholder="<?= lang('GuruMapel/DaftarSiswa.search_ph') ?>" class="search-input w-full pl-10 pr-4 py-3 bg-gray-50 dark:!bg-slate-700 border border-gray-200 dark:!border-slate-600 rounded-xl focus:border-[<?= $color['warna_primary'] ?>] text-gray-900 dark:!text-white outline-none transition-colors" onkeyup="filterStudents()">
          </div>
          
          <div class="flex flex-wrap gap-2" id="filterContainer">
            <button class="filter-button [&.active]:bg-[<?= $color['warna_primary'] ?>] [&.active]:text-white [&.active]:border-[<?= $color['warna_primary'] ?>] active px-5 py-3 rounded-xl border border-gray-200 dark:!border-slate-600 bg-white dark:!bg-slate-800 font-bold text-sm text-gray-600 dark:!text-slate-300 hover:bg-gray-50 dark:hover:!bg-slate-700 transition-all outline-none" onclick="filterByStatus(this, 'all')"><?= lang('GuruMapel/DaftarSiswa.filter_all') ?></button> 
            <button class="filter-button [&.active]:bg-[<?= $color['warna_primary'] ?>] [&.active]:text-white [&.active]:border-[<?= $color['warna_primary'] ?>] px-5 py-3 rounded-xl border border-gray-200 dark:!border-slate-600 bg-white dark:!bg-slate-800 font-bold text-sm text-gray-600 dark:!text-slate-300 hover:bg-gray-50 dark:hover:!bg-slate-700 transition-all outline-none" onclick="filterByStatus(this, 'belum')"><?= lang('GuruMapel/DaftarSiswa.filter_unscored') ?></button> 
            <button class="filter-button [&.active]:bg-[<?= $color['warna_primary'] ?>] [&.active]:text-white [&.active]:border-[<?= $color['warna_primary'] ?>] px-5 py-3 rounded-xl border border-gray-200 dark:!border-slate-600 bg-white dark:!bg-slate-800 font-bold text-sm text-gray-600 dark:!text-slate-300 hover:bg-gray-50 dark:hover:!bg-slate-700 transition-all outline-none" onclick="filterByStatus(this, 'proses')"><?= lang('GuruMapel/DaftarSiswa.filter_progress') ?></button> 
            <button class="filter-button [&.active]:bg-[<?= $color['warna_primary'] ?>] [&.active]:text-white [&.active]:border-[<?= $color['warna_primary'] ?>] px-5 py-3 rounded-xl border border-gray-200 dark:!border-slate-600 bg-white dark:!bg-slate-800 font-bold text-sm text-gray-600 dark:!text-slate-300 hover:bg-gray-50 dark:hover:!bg-slate-700 transition-all outline-none" onclick="filterByStatus(this, 'lengkap')"><?= lang('GuruMapel/DaftarSiswa.filter_complete') ?></button>
          </div>
        </div>
       </div>
      </div>
      
      <div class="bg-white dark:!bg-slate-800 rounded-2xl shadow-sm border border-gray-200 dark:!border-slate-700 overflow-hidden transition-colors">
       <div class="overflow-x-auto custom-scrollbar">
        <table class="student-table w-full text-left border-collapse min-w-max">
         <thead class="bg-[<?= $color['warna_primary'] ?>]/90 dark:!bg-slate-900 border-b border-[<?= $color['warna_primary'] ?>] dark:!border-slate-700 transition-colors">
          <tr>
           <th class="px-6 py-4 text-white font-black text-[11px] uppercase tracking-widest text-center" style="width: 60px;"><?= lang('GuruMapel/DaftarSiswa.th_no') ?></th>
           <th class="px-6 py-4 text-white font-black text-[11px] uppercase tracking-widest text-center" style="width: 60px;"><?= lang('GuruMapel/DaftarSiswa.th_photo') ?></th>
           <th class="px-6 py-4 text-white font-black text-[11px] uppercase tracking-widest" style="width: 120px;"><?= lang('GuruMapel/DaftarSiswa.th_nis') ?></th>
           <th class="px-6 py-4 text-white font-black text-[11px] uppercase tracking-widest"><?= lang('GuruMapel/DaftarSiswa.th_name') ?></th>
           <th class="px-6 py-4 text-white font-black text-[11px] uppercase tracking-widest" style="width: 150px;"><?= lang('GuruMapel/DaftarSiswa.th_status') ?></th>
           <th class="px-6 py-4 text-white font-black text-[11px] uppercase tracking-widest text-center" style="width: 120px;"><?= lang('GuruMapel/DaftarSiswa.th_average') ?></th>
           <th class="px-6 py-4 text-white font-black text-[11px] uppercase tracking-widest" style="width: 150px;"><?= lang('GuruMapel/DaftarSiswa.th_attitude') ?></th>
           <th class="px-6 py-4 text-white font-black text-[11px] uppercase tracking-widest text-center" style="width: 140px;"><?= lang('GuruMapel/DaftarSiswa.th_action') ?></th>
          </tr>
         </thead>
         <tbody id="studentTableBody" class="divide-y divide-gray-100 dark:!divide-slate-700/50 transition-colors">
             </tbody>
        </table>
       </div>
       
       <div id="paginationContainer" class="px-6 py-5 border-t border-gray-200 dark:!border-slate-700 bg-gray-50 dark:!bg-slate-900/50 flex items-center justify-between hidden transition-colors">
         <div class="text-sm font-medium text-gray-500 dark:!text-slate-400 transition-colors">
             <?= lang('GuruMapel/DaftarSiswa.page_showing') ?> <span id="pageStart" class="font-bold text-gray-900 dark:!text-white">0</span> - <span id="pageEnd" class="font-bold text-gray-900 dark:!text-white">0</span> <?= lang('GuruMapel/DaftarSiswa.page_from') ?> <span id="pageTotal" class="font-bold text-gray-900 dark:!text-white">0</span> <?= lang('GuruMapel/DaftarSiswa.page_students') ?>
         </div>
         <div class="flex gap-2">
             <button id="btnPrevPage" onclick="changePage(-1)" class="px-4 py-2.5 rounded-xl border border-gray-300 dark:!border-slate-600 bg-white dark:!bg-slate-800 text-gray-700 dark:!text-slate-300 hover:bg-gray-50 dark:hover:!bg-slate-700 disabled:opacity-50 disabled:cursor-not-allowed transition-colors text-sm font-bold outline-none">
                 <?= lang('GuruMapel/DaftarSiswa.btn_prev') ?>
             </button>
             <div id="pageNumbers" class="flex gap-1"></div>
             <button id="btnNextPage" onclick="changePage(1)" class="px-4 py-2.5 rounded-xl border border-gray-300 dark:!border-slate-600 bg-white dark:!bg-slate-800 text-gray-700 dark:!text-slate-300 hover:bg-gray-50 dark:hover:!bg-slate-700 disabled:opacity-50 disabled:cursor-not-allowed transition-colors text-sm font-bold outline-none">
                 <?= lang('GuruMapel/DaftarSiswa.btn_next') ?>
             </button>
         </div>
       </div>
      </div>
      
      <div id="emptyState" class="empty-state hidden bg-white dark:!bg-slate-800 rounded-3xl border border-gray-200 dark:!border-slate-700 py-16 flex flex-col items-center justify-center text-center shadow-sm transition-colors mt-6">
       <div class="w-24 h-24 bg-gray-50 dark:!bg-slate-900/50 rounded-full flex items-center justify-center mb-4 border-2 border-dashed border-gray-200 dark:!border-slate-600 transition-colors">
          <svg class="w-12 h-12 text-gray-300 dark:!text-slate-500 transition-colors" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
       </div>
       <h3 class="text-xl font-bold text-gray-700 dark:!text-white mb-2 transition-colors"><?= lang('GuruMapel/DaftarSiswa.empty_title') ?></h3>
       <p class="text-gray-500 dark:!text-slate-400 font-medium transition-colors"><?= lang('GuruMapel/DaftarSiswa.empty_desc') ?></p>
      </div>
<?= $this->endSection() ?>

<?= $this->section('modals') ?>
<div id="modalInputNilai" class="fixed inset-0 z-[99999] hidden flex items-center justify-center p-4">
  <div class="absolute inset-0 bg-gray-950/70 backdrop-blur-sm transition-opacity" onclick="closeInputModal()"></div>
  <div class="relative w-full max-w-lg bg-white dark:!bg-slate-800 rounded-3xl shadow-2xl flex flex-col border border-transparent dark:!border-slate-700 transition-colors transform overflow-hidden md:ml-64 z-10">
    <div class="bg-gradient-to-r from-[<?= $color['warna_primary'] ?>]/90 to-[<?= $color['warna_primary'] ?>] px-6 py-5 flex items-center justify-between rounded-t-3xl transition-colors">
     <div>
      <h2 class="text-xl font-black text-white mb-1"><?= lang('GuruMapel/DaftarSiswa.modal_input_title') ?></h2>
      <p class="text-emerald-100 font-bold text-[11px] uppercase tracking-widest" id="modalStudentName"></p>
     </div>
     <button onclick="closeInputModal()" class="text-white hover:bg-white/20 rounded-full p-2 transition-colors outline-none">
      <svg class="w-6 h-6" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
     </button>
    </div>
    <div class="p-6 md:p-8">
     <div class="space-y-5">
      <div>
          <label class="block text-[11px] font-black text-gray-700 dark:!text-slate-300 uppercase tracking-widest mb-2 transition-colors"><?= lang('GuruMapel/DaftarSiswa.modal_input_daily') ?></label> 
          <input type="number" id="inputHarian" min="0" max="100" class="w-full px-4 py-3 border-2 border-gray-300 dark:!border-slate-600 bg-gray-50 dark:!bg-slate-700 text-gray-900 dark:!text-white rounded-xl focus:border-[<?= $color['warna_primary'] ?>] focus:ring-2 focus:ring-[<?= $color['warna_primary'] ?>]/30 outline-none transition-colors text-lg font-bold shadow-sm">
      </div>
      <div>
          <label class="block text-[11px] font-black text-gray-700 dark:!text-slate-300 uppercase tracking-widest mb-2 transition-colors"><?= lang('GuruMapel/DaftarSiswa.modal_input_uts') ?></label> 
          <input type="number" id="inputUTS" min="0" max="100" class="w-full px-4 py-3 border-2 border-gray-300 dark:!border-slate-600 bg-gray-50 dark:!bg-slate-700 text-gray-900 dark:!text-white rounded-xl focus:border-[<?= $color['warna_primary'] ?>] focus:ring-2 focus:ring-[<?= $color['warna_primary'] ?>]/30 outline-none transition-colors text-lg font-bold shadow-sm">
      </div>
      <div>
          <label class="block text-[11px] font-black text-gray-700 dark:!text-slate-300 uppercase tracking-widest mb-2 transition-colors"><?= lang('GuruMapel/DaftarSiswa.modal_input_uas') ?></label> 
          <input type="number" id="inputUAS" min="0" max="100" class="w-full px-4 py-3 border-2 border-gray-300 dark:!border-slate-600 bg-gray-50 dark:!bg-slate-700 text-gray-900 dark:!text-white rounded-xl focus:border-[<?= $color['warna_primary'] ?>] focus:ring-2 focus:ring-[<?= $color['warna_primary'] ?>]/30 outline-none transition-colors text-lg font-bold shadow-sm">
      </div>
      <div>
          <label class="block text-[11px] font-black text-gray-700 dark:!text-slate-300 uppercase tracking-widest mb-2 transition-colors"><?= lang('GuruMapel/DaftarSiswa.modal_input_proj') ?></label> 
          <input type="number" id="inputProyek" min="0" max="100" class="w-full px-4 py-3 border-2 border-gray-300 dark:!border-slate-600 bg-gray-50 dark:!bg-slate-700 text-gray-900 dark:!text-white rounded-xl focus:border-[<?= $color['warna_primary'] ?>] focus:ring-2 focus:ring-[<?= $color['warna_primary'] ?>]/30 outline-none transition-colors text-lg font-bold shadow-sm">
      </div>
     </div>
    </div>
    <div class="px-6 py-5 bg-gray-50 dark:!bg-slate-900/50 border-t border-gray-200 dark:!border-slate-700 flex flex-col sm:flex-row items-center justify-end gap-3 rounded-b-3xl transition-colors">
      <button onclick="closeInputModal()" class="w-full sm:w-auto px-6 py-3.5 border-2 border-gray-300 dark:!border-slate-600 bg-white dark:!bg-slate-700 rounded-xl font-bold text-gray-700 dark:!text-slate-300 hover:bg-gray-100 dark:hover:!bg-slate-600 transition-colors outline-none shadow-sm text-sm"> 
          <?= lang('GuruMapel/DaftarSiswa.btn_cancel') ?> 
      </button> 
      <button onclick="saveNilai()" class="w-full sm:w-auto px-8 py-3.5 bg-[<?= $color['warna_primary'] ?>] hover:brightness-90 text-white font-bold rounded-xl transition-transform transform hover:-translate-y-0.5 shadow-lg flex items-center justify-center gap-2 outline-none text-sm" style="box-shadow: 0 10px 15px -3px <?= $color['warna_primary'] ?>40;">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" /></svg> 
          <?= lang('GuruMapel/DaftarSiswa.btn_save') ?> 
      </button>
    </div>
   </div>
</div>

<div id="modalObservasi" class="fixed inset-0 z-[99999] hidden flex items-center justify-center p-4">
  <div class="absolute inset-0 bg-gray-950/70 backdrop-blur-sm transition-opacity" onclick="closeObservasiModal()"></div>
  <div class="relative w-full max-w-lg bg-white dark:!bg-slate-800 rounded-3xl shadow-2xl flex flex-col border border-transparent dark:!border-slate-700 transition-colors transform overflow-hidden md:ml-64 z-10">
    
    <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-6 py-5 flex items-center justify-between rounded-t-3xl transition-colors">
     <div>
      <h2 class="text-xl font-black text-white mb-1"><?= lang('GuruMapel/DaftarSiswa.modal_obs_title') ?></h2>
      <p class="text-blue-100 font-bold text-[11px] uppercase tracking-widest" id="modalObservasiName"></p>
     </div>
     <button onclick="closeObservasiModal()" class="text-white hover:bg-white/20 rounded-full p-2 transition-colors outline-none">
      <svg class="w-6 h-6" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
     </button>
    </div>
    
    <div class="p-6 md:p-8 space-y-6">
      <div>
        <label class="block text-[11px] font-black text-gray-700 dark:!text-slate-300 uppercase tracking-widest mb-2 transition-colors"><?= lang('GuruMapel/DaftarSiswa.modal_obs_note') ?></label> 
        <textarea id="inputObservasi" rows="4" class="w-full px-4 py-3 border-2 border-gray-300 dark:!border-slate-600 bg-gray-50 dark:!bg-slate-700 text-gray-900 dark:!text-white rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-500/30 outline-none transition-colors resize-none font-medium shadow-sm placeholder-gray-400 dark:placeholder-slate-400" placeholder="<?= lang('GuruMapel/DaftarSiswa.modal_obs_note_ph') ?>"></textarea>
      </div>
      <div>
       <label class="block text-[11px] font-black text-gray-700 dark:!text-slate-300 uppercase tracking-widest mb-3 transition-colors"><?= lang('GuruMapel/DaftarSiswa.modal_obs_aspect') ?></label>
       <div class="grid grid-cols-2 gap-3">
           <label class="flex items-center gap-3 p-3.5 border-2 border-gray-200 dark:!border-slate-600 bg-white dark:!bg-slate-800 rounded-xl hover:border-blue-500 dark:hover:!border-blue-500 cursor-pointer transition-colors shadow-sm group"> 
               <input type="checkbox" id="cekKedisiplinan" class="w-4 h-4 text-blue-600 focus:ring-blue-500 rounded border-gray-300 dark:border-slate-500 cursor-pointer outline-none mt-0.5"> 
               <span class="text-sm font-bold text-gray-700 dark:!text-slate-300 group-hover:text-gray-900 dark:group-hover:!text-white transition-colors"><?= lang('GuruMapel/DaftarSiswa.obs_discipline') ?></span> 
           </label> 
           <label class="flex items-center gap-3 p-3.5 border-2 border-gray-200 dark:!border-slate-600 bg-white dark:!bg-slate-800 rounded-xl hover:border-blue-500 dark:hover:!border-blue-500 cursor-pointer transition-colors shadow-sm group"> 
               <input type="checkbox" id="cekTanggungJawab" class="w-4 h-4 text-blue-600 focus:ring-blue-500 rounded border-gray-300 dark:border-slate-500 cursor-pointer outline-none mt-0.5"> 
               <span class="text-sm font-bold text-gray-700 dark:!text-slate-300 group-hover:text-gray-900 dark:group-hover:!text-white transition-colors"><?= lang('GuruMapel/DaftarSiswa.obs_responsibility') ?></span> 
           </label> 
           <label class="flex items-center gap-3 p-3.5 border-2 border-gray-200 dark:!border-slate-600 bg-white dark:!bg-slate-800 rounded-xl hover:border-blue-500 dark:hover:!border-blue-500 cursor-pointer transition-colors shadow-sm group"> 
               <input type="checkbox" id="cekKerjasama" class="w-4 h-4 text-blue-600 focus:ring-blue-500 rounded border-gray-300 dark:border-slate-500 cursor-pointer outline-none mt-0.5"> 
               <span class="text-sm font-bold text-gray-700 dark:!text-slate-300 group-hover:text-gray-900 dark:group-hover:!text-white transition-colors"><?= lang('GuruMapel/DaftarSiswa.obs_teamwork') ?></span> 
           </label> 
           <label class="flex items-center gap-3 p-3.5 border-2 border-gray-200 dark:!border-slate-600 bg-white dark:!bg-slate-800 rounded-xl hover:border-blue-500 dark:hover:!border-blue-500 cursor-pointer transition-colors shadow-sm group"> 
               <input type="checkbox" id="cekKejujuran" class="w-4 h-4 text-blue-600 focus:ring-blue-500 rounded border-gray-300 dark:border-slate-500 cursor-pointer outline-none mt-0.5"> 
               <span class="text-sm font-bold text-gray-700 dark:!text-slate-300 group-hover:text-gray-900 dark:group-hover:!text-white transition-colors"><?= lang('GuruMapel/DaftarSiswa.obs_honesty') ?></span> 
           </label>
       </div>
      </div>
    </div>
    
    <div class="px-6 py-5 bg-gray-50 dark:!bg-slate-900/50 border-t border-gray-200 dark:!border-slate-700 flex flex-col sm:flex-row items-center justify-end gap-3 rounded-b-3xl transition-colors">
        <button onclick="closeObservasiModal()" class="w-full sm:w-auto px-6 py-3.5 border-2 border-gray-300 dark:!border-slate-600 bg-white dark:!bg-slate-700 rounded-xl font-bold text-gray-700 dark:!text-slate-300 hover:bg-gray-100 dark:hover:!bg-slate-600 transition-colors outline-none shadow-sm text-sm"> 
            <?= lang('GuruMapel/DaftarSiswa.btn_cancel') ?> 
        </button> 
        <button onclick="saveObservasi()" class="w-full sm:w-auto px-8 py-3.5 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl transition-transform transform hover:-translate-y-0.5 shadow-lg shadow-blue-600/30 flex items-center justify-center gap-2 outline-none text-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" /></svg> 
            <?= lang('GuruMapel/DaftarSiswa.btn_save_obs') ?> 
        </button>
    </div>
  </div>
</div>

<div id="modalDetail" class="fixed inset-0 z-[99999] hidden flex items-center justify-center p-4">
  <div class="absolute inset-0 bg-gray-950/70 backdrop-blur-sm transition-opacity" onclick="closeDetailModal()"></div>
  <div class="relative w-full max-w-lg bg-white dark:!bg-slate-800 rounded-3xl shadow-2xl flex flex-col border border-transparent dark:!border-slate-700 transition-colors transform overflow-hidden md:ml-64 z-10">
    <div class="bg-[<?= $color['warna_primary'] ?>] px-6 py-5 flex items-center justify-between rounded-t-3xl transition-colors">
     <h2 class="text-xl font-black text-white"><?= lang('GuruMapel/DaftarSiswa.modal_det_title') ?></h2>
     <button onclick="closeDetailModal()" class="text-white hover:bg-white/20 rounded-full p-2 transition-colors outline-none">
      <svg class="w-6 h-6" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
     </button>
    </div>
    
    <div class="p-6 md:p-8">
     <div class="flex items-center gap-5 mb-6 pb-6 border-b border-gray-100 dark:!border-slate-700 transition-colors">
      <div class="w-20 h-20 rounded-2xl flex items-center justify-center text-white font-black text-2xl bg-[<?= $color['warna_primary'] ?>] shadow-md border-2 border-white dark:!border-slate-800" id="detailAvatar">
       AF
      </div>
      <div class="min-w-0 pr-2">
       <h3 class="text-xl font-black text-gray-900 dark:!text-white mb-1 truncate transition-colors" id="detailName">Ahmad Fauzan</h3>
       <p class="text-[11px] font-bold text-gray-500 dark:!text-slate-400 uppercase tracking-widest transition-colors" id="detailNIS"><?= lang('GuruMapel/DaftarSiswa.th_nis') ?>: 202471001</p>
      </div>
     </div>
     
     <div class="space-y-6">
      <div>
       <h4 class="text-[11px] font-black text-gray-500 dark:!text-slate-400 uppercase tracking-widest mb-3 transition-colors"><?= lang('GuruMapel/DaftarSiswa.det_academic') ?></h4>
       <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
        <div class="bg-gray-50 dark:!bg-slate-700/50 p-4 rounded-xl border border-gray-200 dark:!border-slate-600 transition-colors">
         <p class="text-[10px] text-gray-500 dark:!text-slate-400 font-bold uppercase tracking-wider mb-1 transition-colors"><?= lang('GuruMapel/DaftarSiswa.det_daily') ?></p>
         <p class="text-2xl font-black text-gray-900 dark:!text-white transition-colors" id="detailHarian">85</p>
        </div>
        <div class="bg-gray-50 dark:!bg-slate-700/50 p-4 rounded-xl border border-gray-200 dark:!border-slate-600 transition-colors">
         <p class="text-[10px] text-gray-500 dark:!text-slate-400 font-bold uppercase tracking-wider mb-1 transition-colors"><?= lang('GuruMapel/DaftarSiswa.det_uts') ?></p>
         <p class="text-2xl font-black text-gray-900 dark:!text-white transition-colors" id="detailUTS">88</p>
        </div>
        <div class="bg-gray-50 dark:!bg-slate-700/50 p-4 rounded-xl border border-gray-200 dark:!border-slate-600 transition-colors">
         <p class="text-[10px] text-gray-500 dark:!text-slate-400 font-bold uppercase tracking-wider mb-1 transition-colors"><?= lang('GuruMapel/DaftarSiswa.det_uas') ?></p>
         <p class="text-2xl font-black text-gray-900 dark:!text-white transition-colors" id="detailUAS">90</p>
        </div>
        <div class="bg-gray-50 dark:!bg-slate-700/50 p-4 rounded-xl border border-gray-200 dark:!border-slate-600 transition-colors">
         <p class="text-[10px] text-gray-500 dark:!text-slate-400 font-bold uppercase tracking-wider mb-1 transition-colors"><?= lang('GuruMapel/DaftarSiswa.det_proj') ?></p>
         <p class="text-2xl font-black text-gray-900 dark:!text-white transition-colors" id="detailProyek">92</p>
        </div>
       </div>
      </div>
      
      <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <div>
           <h4 class="text-[11px] font-black text-gray-500 dark:!text-slate-400 uppercase tracking-widest mb-3 transition-colors"><?= lang('GuruMapel/DaftarSiswa.det_average') ?></h4>
           <div class="bg-gradient-to-r from-[<?= $color['warna_primary'] ?>] to-[<?= $color['warna_secondary'] ?>] dark:to-[<?= $color['warna_primary'] ?>]/80 p-5 rounded-2xl text-center shadow-sm">
            <p class="text-white text-4xl font-black mb-1 drop-shadow-sm" id="detailRataRata">88.75</p>
            <p class="text-white/80 text-[11px] font-bold uppercase tracking-widest"><?= lang('GuruMapel/DaftarSiswa.det_predicate') ?>: <span class="font-black text-white ml-1 text-sm" id="detailPredikat">B</span></p>
           </div>
          </div>
          <div>
           <h4 class="text-[11px] font-black text-gray-500 dark:!text-slate-400 uppercase tracking-widest mb-3 transition-colors"><?= lang('GuruMapel/DaftarSiswa.det_attitude') ?></h4>
           <div class="bg-amber-50 dark:!bg-amber-900/20 p-5 rounded-2xl border border-amber-200 dark:!border-amber-800/50 h-[92px] overflow-y-auto custom-scrollbar transition-colors">
            <p class="text-sm font-medium text-amber-900 dark:!text-amber-300 leading-relaxed" id="detailCatatan">-</p>
           </div>
          </div>
      </div>
     </div>
    </div>
    
    <div class="px-6 py-5 bg-gray-50 dark:!bg-slate-900/50 border-t border-gray-100 dark:!border-slate-700 flex justify-end rounded-b-3xl transition-colors">
        <button onclick="closeDetailModal()" class="w-full sm:w-auto px-8 py-3.5 border-2 border-gray-300 dark:!border-slate-600 bg-white dark:!bg-slate-700 text-gray-700 dark:!text-slate-300 font-bold rounded-xl hover:bg-gray-100 dark:hover:!bg-slate-600 transition-colors outline-none shadow-sm text-sm"> 
            <?= lang('GuruMapel/DaftarSiswa.btn_close') ?> 
        </button>
    </div>
  </div>
</div>
  
<div id="toast" class="fixed top-4 right-4 z-[1000000] flex items-center gap-3 px-4 py-3 bg-emerald-500 text-white rounded-xl shadow-2xl transition-all duration-300 transform translate-x-full opacity-0">
    <div class="w-8 h-8 rounded-full bg-white/20 flex items-center justify-center flex-shrink-0">
        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" /></svg>
    </div>
    <span class="font-bold text-sm tracking-wide pr-2" id="toastMessage"><?= lang('GuruMapel/DaftarSiswa.toast_success') ?></span>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
  <script>
     const URL_GET_DATA = "<?= base_url('guru/daftar-siswa/get-data') ?>";
    const URL_SAVE_DATA = "<?= base_url('guru/daftar-siswa/save-data') ?>";
    const ACTIVE_ROMBEL_ID = <?= $info['rombel_id'] ?? 0 ?>;
    const ACTIVE_MAPEL_ID = <?= $info['mapel_id'] ?? 0 ?>;
    const THEME_PRIMARY_COLOR = "<?= $color['warna_primary'] ?>";
    const csrfTokenName = "<?= csrf_token() ?>";
    const csrfTokenHash = "<?= csrf_hash() ?>";

    // KAMUS JS: Daftar Siswa
    const LANG = {
        loading: "<?= lang('GuruMapel/DaftarSiswa.js_loading') ?? 'Memuat data siswa...' ?>",
        err_no_class: "<?= lang('GuruMapel/DaftarSiswa.js_err_no_class') ?? 'Guru belum ditugaskan di kelas manapun.' ?>",
        err_load: "<?= lang('GuruMapel/DaftarSiswa.js_err_load') ?? 'Gagal memuat data' ?>",
        err_server: "<?= lang('GuruMapel/DaftarSiswa.js_err_server') ?? 'Gagal terhubung ke Server.' ?>",
        status_complete: "<?= lang('GuruMapel/DaftarSiswa.js_status_complete') ?? 'LENGKAP' ?>",
        status_progress: "<?= lang('GuruMapel/DaftarSiswa.js_status_progress') ?? 'PROSES' ?>",
        status_unscored: "<?= lang('GuruMapel/DaftarSiswa.js_status_unscored') ?? 'BELUM DINILAI' ?>",
        no_notes: "<?= lang('GuruMapel/DaftarSiswa.js_no_notes') ?? 'Belum ada catatan' ?>",
        btn_input: "<?= lang('GuruMapel/DaftarSiswa.js_btn_input') ?? 'Input Nilai' ?>",
        btn_obs: "<?= lang('GuruMapel/DaftarSiswa.js_btn_obs') ?? 'Observasi Sikap' ?>",
        btn_detail: "<?= lang('GuruMapel/DaftarSiswa.js_btn_detail') ?? 'Detail Siswa' ?>",
        err_range: "<?= lang('GuruMapel/DaftarSiswa.js_err_range') ?? 'Gagal! Pastikan semua nilai berada di antara 0 hingga 100.' ?>",
        saving: "<?= lang('GuruMapel/DaftarSiswa.js_saving') ?? 'Menyimpan...' ?>",
        succ_grade: "<?= lang('GuruMapel/DaftarSiswa.js_succ_grade') ?? 'Berhasil menyimpan nilai ke Database! ✨' ?>",
        err_save: "<?= lang('GuruMapel/DaftarSiswa.js_err_save') ?? 'Gagal menyimpan data' ?>",
        succ_obs: "<?= lang('GuruMapel/DaftarSiswa.js_succ_obs') ?? 'Catatan dan Aspek Sikap berhasil disimpan! 📝' ?>",
        err_mass: "<?= lang('GuruMapel/DaftarSiswa.js_err_mass') ?? 'Fitur input massal belum tersedia' ?>"
    };
  </script>
  <script src="<?= base_url('assets/js/GuruMapel/daftar-siswa.js') ?>"></script>
<?= $this->endSection() ?>