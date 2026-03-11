<?= $this->extend('layout/main') ?>

<?= $this->section('title') ?>
  <?= lang('GuruMapel/ObservasiSikap.page_title') ?> - Rapor Digital SMPIT Ad Durrah
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
  :root { --warna-scroll: <?= $color['warna_primary'] ?>; }
  /* OVERRIDE CSS BAWAAN AGAR DARK MODE JALAN */
  .dark .modal-content { background-color: transparent !important; box-shadow: none !important; }
</style>
<link rel="stylesheet" href="<?= base_url('assets/css/GuruMapel/observasi-sikap.css') ?>">
<?= $this->endSection() ?>
<?= $this->section('content') ?>
     <div class="mb-6">
      <h1 class="text-2xl md:text-3xl font-bold mb-2 text-gray-900 dark:!text-white transition-colors" id="pageTitle"><?= lang('GuruMapel/ObservasiSikap.page_title') ?></h1>
      <p class="text-base text-gray-600 dark:!text-slate-400 font-medium transition-colors" id="pageSubtitle"><?= lang('GuruMapel/ObservasiSikap.page_subtitle') ?></p>
     </div>
     
     <div class="info-card bg-[<?= $color['warna_secondary'] ?>] dark:!bg-slate-800 border border-[<?= $color['warna_primary'] ?>] dark:!border-slate-700 mb-6 p-5 rounded-2xl shadow-sm transition-colors">
      <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
       <div class="flex items-center gap-3">
        <div class="w-12 h-12 rounded-xl bg-blue-600 flex items-center justify-center shadow-lg shadow-blue-600/30 flex-shrink-0 transition-transform hover:scale-105">
         <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
        </div>
        <div class="min-w-0 pr-1">
         <p class="text-[10px] text-[<?= $color['warna_primary'] ?>] dark:!text-blue-400 font-bold mb-0.5 uppercase tracking-widest truncate transition-colors"><?= lang('GuruMapel/ObservasiSikap.info_class') ?></p>
         <p class="text-sm font-black text-[<?= $color['warna_primary'] ?>] dark:!text-white truncate transition-colors">Kelas <?= esc($info['kelas']) ?></p>
        </div>
       </div>
       <div class="flex items-center gap-3">
        <div class="w-12 h-12 rounded-xl bg-emerald-600 flex items-center justify-center shadow-lg shadow-emerald-600/30 flex-shrink-0 transition-transform hover:scale-105">
         <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
        </div>
        <div class="min-w-0 pr-1">
         <p class="text-[10px] text-[<?= $color['warna_primary'] ?>] dark:!text-emerald-400 font-bold mb-0.5 uppercase tracking-widest truncate transition-colors"><?= lang('GuruMapel/ObservasiSikap.info_subject') ?></p>
         <p class="text-sm font-black text-[<?= $color['warna_primary'] ?>] dark:!text-white truncate transition-colors"><?= esc($info['mapel']) ?></p>
        </div>
       </div>
       <div class="flex items-center gap-3">
        <div class="w-12 h-12 rounded-xl bg-indigo-600 flex items-center justify-center shadow-lg shadow-indigo-600/30 flex-shrink-0 transition-transform hover:scale-105">
         <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
        </div>
        <div class="min-w-0 pr-1">
         <p class="text-[10px] text-[<?= $color['warna_primary'] ?>] dark:!text-indigo-400 font-bold mb-0.5 uppercase tracking-widest truncate transition-colors"><?= lang('GuruMapel/ObservasiSikap.info_role') ?></p>
         <p class="text-sm font-black text-[<?= $color['warna_primary'] ?>] dark:!text-white truncate transition-colors"><?= lang('GuruMapel/ObservasiSikap.role_teacher') ?></p>
        </div>
       </div>
       <div class="flex items-center gap-3">
        <div class="w-12 h-12 rounded-xl bg-purple-600 flex items-center justify-center shadow-lg shadow-purple-600/30 flex-shrink-0 transition-transform hover:scale-105">
         <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
        </div>
        <div class="min-w-0 pr-1">
         <p class="text-[10px] text-[<?= $color['warna_primary'] ?>] dark:!text-purple-400 font-bold mb-0.5 uppercase tracking-widest truncate transition-colors"><?= lang('GuruMapel/ObservasiSikap.info_students') ?></p>
         <p class="text-sm font-black text-[<?= $color['warna_primary'] ?>] dark:!text-white truncate transition-colors"><?= lang('GuruMapel/ObservasiSikap.student_count', [$info['jml_siswa']]) ?></p>
        </div>
       </div>
      </div>
     </div>

     <div class="note-banner bg-amber-50 dark:!bg-amber-900/20 border border-amber-200 dark:!border-amber-800/50 p-4 rounded-2xl mb-6 flex items-start gap-4 transition-colors shadow-sm">
      <svg class="w-6 h-6 mt-0.5 text-amber-600 dark:!text-amber-500 flex-shrink-0" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
      <p class="text-sm text-amber-900 dark:!text-amber-400 font-bold leading-relaxed transition-colors"><?= lang('GuruMapel/ObservasiSikap.alert_info') ?></p>
     </div>
     
     <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-6">
      <div class="stat-card bg-white dark:!bg-slate-800 border border-gray-100 dark:!border-slate-700 p-5 rounded-2xl shadow-sm transition-colors hover:-translate-y-1 hover:shadow-md duration-300">
       <div class="flex items-center justify-between mb-3">
        <div class="w-12 h-12 rounded-xl bg-emerald-100 dark:!bg-emerald-900/30 flex items-center justify-center transition-colors">
         <svg class="w-6 h-6 text-emerald-600 dark:!text-emerald-400" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
        </div>
        <span class="text-3xl font-black text-emerald-600 dark:!text-emerald-400 transition-colors"><?= $stats['minggu_ini'] ?></span>
       </div>
       <p class="text-sm font-bold text-gray-600 dark:!text-slate-400 transition-colors"><?= lang('GuruMapel/ObservasiSikap.stat_weekly') ?></p>
      </div>
      
      <div class="stat-card bg-white dark:!bg-slate-800 border border-gray-100 dark:!border-slate-700 p-5 rounded-2xl shadow-sm transition-colors hover:-translate-y-1 hover:shadow-md duration-300">
       <div class="flex items-center justify-between mb-3">
        <div class="w-12 h-12 rounded-xl bg-amber-100 dark:!bg-amber-900/30 flex items-center justify-center transition-colors">
         <svg class="w-6 h-6 text-amber-600 dark:!text-amber-400" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
        </div>
        <span class="text-3xl font-black text-amber-600 dark:!text-amber-400 transition-colors"><?= $stats['pembinaan'] ?></span>
       </div>
       <p class="text-sm font-bold text-gray-600 dark:!text-slate-400 transition-colors"><?= lang('GuruMapel/ObservasiSikap.stat_guidance') ?></p>
      </div>
      
      <div class="stat-card bg-white dark:!bg-slate-800 border border-gray-100 dark:!border-slate-700 p-5 rounded-2xl shadow-sm transition-colors hover:-translate-y-1 hover:shadow-md duration-300">
       <div class="flex items-center justify-between mb-3">
        <div class="w-12 h-12 rounded-xl bg-blue-100 dark:!bg-blue-900/30 flex items-center justify-center transition-colors">
         <svg class="w-6 h-6 text-blue-600 dark:!text-blue-400" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z" /></svg>
        </div>
        <p class="text-xl font-black text-blue-600 dark:!text-blue-400 truncate max-w-[120px] text-right transition-colors"><?= $stats['dominan'] ?></p>
       </div>
       <p class="text-sm font-bold text-gray-600 dark:!text-slate-400 transition-colors"><?= lang('GuruMapel/ObservasiSikap.stat_dominant') ?></p>
      </div>
     </div>

     <div class="flex flex-col md:flex-row md:items-center justify-between mb-6 gap-4">
      <div class="mode-toggle bg-gray-100 dark:!bg-slate-800 p-1 rounded-xl flex items-center border border-gray-200 dark:!border-slate-700 transition-colors">
        <button class="active [&.active]:text-[<?= $color['warna_primary'] ?>] dark:[&.active]:!text-white dark:text-slate-400 px-4 py-2 font-bold rounded-lg transition-colors text-sm" onclick="switchMode('persiswa')">
            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg> <?= lang('GuruMapel/ObservasiSikap.mode_per_student') ?> 
        </button> 
        <button class="[&.active]:text-[<?= $color['warna_primary'] ?>] dark:[&.active]:!text-white dark:text-slate-400 px-4 py-2 font-bold rounded-lg transition-colors text-sm" onclick="switchMode('massal')">
            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg> <?= lang('GuruMapel/ObservasiSikap.mode_quick_obs') ?> 
        </button>
      </div>
      <button class="btn-primary w-full md:w-auto px-6 py-3 rounded-xl bg-[<?= $color['warna_primary'] ?>] hover:brightness-110 text-white font-bold shadow-lg shadow-[<?= $color['warna_primary'] ?>]/30 transition-all flex items-center justify-center gap-2 outline-none" onclick="tambahObservasi()">
       <svg class="w-5 h-5" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" /></svg> <?= lang('GuruMapel/ObservasiSikap.btn_add_obs') ?> 
      </button>
     </div>

     <div class="bg-white dark:!bg-slate-800 rounded-2xl shadow-xl border border-gray-100 dark:!border-slate-700 overflow-hidden mb-8 transition-colors">
      <div class="overflow-x-auto custom-scrollbar">
       <table class="observasi-table w-full text-left border-collapse min-w-max">
        <thead class="bg-[<?= $color['warna_primary'] ?>]/90 dark:!bg-slate-900/50 transition-colors">
         <tr class="text-[11px] font-black text-white dark:!text-slate-300 uppercase tracking-widest">
          <th class="px-6 py-4 text-center border-b border-[<?= $color['warna_primary'] ?>] dark:!border-slate-700" style="width: 60px;"><?= lang('GuruMapel/ObservasiSikap.table_no') ?></th>
          <th class="px-6 py-4 border-b border-[<?= $color['warna_primary'] ?>] dark:!border-slate-700 min-w-[200px]"><?= lang('GuruMapel/ObservasiSikap.table_name') ?></th>
          <th class="px-6 py-4 border-b border-[<?= $color['warna_primary'] ?>] dark:!border-slate-700 min-w-[150px]"><?= lang('GuruMapel/ObservasiSikap.table_param') ?></th>
          <th class="px-6 py-4 text-center border-b border-[<?= $color['warna_primary'] ?>] dark:!border-slate-700 min-w-[140px]"><?= lang('GuruMapel/ObservasiSikap.table_scale') ?></th>
          <th class="px-6 py-4 border-b border-[<?= $color['warna_primary'] ?>] dark:!border-slate-700 min-w-[250px]"><?= lang('GuruMapel/ObservasiSikap.table_notes') ?></th>
          <th class="px-6 py-4 border-b border-[<?= $color['warna_primary'] ?>] dark:!border-slate-700 min-w-[120px]"><?= lang('GuruMapel/ObservasiSikap.table_date') ?></th>
          <th class="px-6 py-4 text-center border-b border-[<?= $color['warna_primary'] ?>] dark:!border-slate-700" style="width: 100px;"><?= lang('GuruMapel/ObservasiSikap.table_action') ?></th>
         </tr>
        </thead>
        <tbody id="observasiTableBody" class="divide-y divide-gray-50 dark:!divide-slate-700/50 bg-white dark:!bg-slate-800 transition-colors">
            <tr><td colspan="7" class="text-center py-10 font-bold text-gray-500 dark:!text-slate-400 transition-colors"><?= lang('GuruMapel/ObservasiSikap.table_loading') ?></td></tr>
        </tbody>
       </table>
      </div>
     </div>

     <div class="card-soft bg-gray-50 dark:!bg-slate-800/50 border border-gray-100 dark:!border-slate-700 p-6 md:p-8 rounded-3xl transition-colors shadow-sm">
      <h2 class="text-lg font-black text-gray-900 dark:!text-white mb-6 uppercase tracking-widest flex items-center gap-3 transition-colors border-b border-gray-200 dark:!border-slate-700 pb-3">
       <svg class="w-6 h-6 text-[<?= $color['warna_primary'] ?>]" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg> 
       <?= lang('GuruMapel/ObservasiSikap.history_title') ?>
      </h2>
      <div class="space-y-4" id="timelineContainer">
         </div>
     </div>
<?= $this->endSection() ?>

<?= $this->section('modals') ?>
<div id="toast" class="fixed top-4 right-4 z-[1000000] flex items-center gap-3 px-5 py-4 bg-white dark:!bg-slate-800 text-gray-800 dark:!text-white border-l-4 border-emerald-500 rounded-xl shadow-2xl transition-all duration-300 transform translate-x-full opacity-0">
   <div class="w-8 h-8 rounded-full bg-emerald-100 dark:!bg-emerald-900/30 flex items-center justify-center flex-shrink-0">
      <svg class="w-5 h-5 text-emerald-600 dark:!text-emerald-400" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
   </div>
   <span class="font-bold text-sm tracking-wide pr-2" id="toastMessage"><?= lang('GuruMapel/ObservasiSikap.toast_success') ?></span>
</div>

<div id="observasiModal" class="modal-overlay fixed inset-0 z-[99999] hidden items-center justify-center p-4 bg-gray-900/70 backdrop-blur-sm transition-all overflow-y-auto" onclick="if(event.target === this) closeObservasiModal()">
   <div class="relative w-full max-w-lg bg-white dark:!bg-slate-900 rounded-3xl shadow-2xl transition-colors border border-transparent dark:!border-slate-800 transform scale-95 opacity-0 animate-in overflow-hidden duration-300 mx-auto">
    <div class="bg-gradient-to-r from-[<?= $color['warna_primary'] ?>]/90 to-[<?= $color['warna_primary'] ?>] px-6 md:px-8 py-5 flex items-center justify-between">
     <h2 class="text-xl font-black text-white uppercase tracking-widest"><?= lang('GuruMapel/ObservasiSikap.modal_add_title') ?></h2>
     <button onclick="closeObservasiModal()" class="text-white/80 hover:text-white bg-white/10 hover:bg-white/20 rounded-xl p-2 transition-colors outline-none">
      <svg class="w-5 h-5" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" /></svg>
     </button>
    </div>
    
    <div class="p-6 md:p-8 space-y-6">
     <div>
         <label class="block text-[11px] font-black text-gray-500 dark:!text-slate-400 uppercase tracking-widest mb-2 transition-colors"><?= lang('GuruMapel/ObservasiSikap.modal_select_student') ?></label> 
         <select id="modalSiswa" class="select-field w-full px-4 py-3.5 bg-gray-50 dark:!bg-slate-800 border-2 border-gray-200 dark:!border-slate-700 rounded-xl text-sm text-gray-900 dark:!text-white focus:outline-none focus:border-[<?= $color['warna_primary'] ?>] transition-all cursor-pointer shadow-sm appearance-none outline-none"> 
             <option value=""><?= lang('GuruMapel/ObservasiSikap.modal_select_ph') ?></option> 
             <?php foreach($siswas as $s): ?>
                <option value="<?= $s['id'] ?>"><?= esc($s['nama_lengkap']) ?></option>
             <?php endforeach; ?>
         </select>
     </div>
     
     <div>
      <label class="block text-[11px] font-black text-gray-500 dark:!text-slate-400 uppercase tracking-widest mb-3 transition-colors"><?= lang('GuruMapel/ObservasiSikap.modal_param_title') ?></label>
      <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
          <label class="sikap-checkbox flex items-center gap-3 p-3.5 border-2 border-gray-100 dark:!border-slate-700 bg-white dark:!bg-slate-800 rounded-xl hover:bg-[<?= $color['warna_secondary'] ?>] dark:hover:!bg-slate-700 transition-colors cursor-pointer accent-[<?= $color['warna_primary'] ?>] shadow-sm"> 
            <input type="radio" name="parameter" value="disiplin" class="w-4 h-4"> <span class="font-bold text-sm text-gray-700 dark:!text-slate-300 transition-colors"><?= lang('GuruMapel/ObservasiSikap.param_discipline') ?></span> 
          </label> 
          <label class="sikap-checkbox flex items-center gap-3 p-3.5 border-2 border-gray-100 dark:!border-slate-700 bg-white dark:!bg-slate-800 rounded-xl hover:bg-[<?= $color['warna_secondary'] ?>] dark:hover:!bg-slate-700 transition-colors cursor-pointer accent-[<?= $color['warna_primary'] ?>] shadow-sm"> 
            <input type="radio" name="parameter" value="tanggung-jawab" class="w-4 h-4"> <span class="font-bold text-sm text-gray-700 dark:!text-slate-300 transition-colors"><?= lang('GuruMapel/ObservasiSikap.param_responsibility') ?></span> 
          </label> 
          <label class="sikap-checkbox flex items-center gap-3 p-3.5 border-2 border-gray-100 dark:!border-slate-700 bg-white dark:!bg-slate-800 rounded-xl hover:bg-[<?= $color['warna_secondary'] ?>] dark:hover:!bg-slate-700 transition-colors cursor-pointer accent-[<?= $color['warna_primary'] ?>] shadow-sm"> 
            <input type="radio" name="parameter" value="kejujuran" class="w-4 h-4"> <span class="font-bold text-sm text-gray-700 dark:!text-slate-300 transition-colors"><?= lang('GuruMapel/ObservasiSikap.param_honesty') ?></span> 
          </label> 
          <label class="sikap-checkbox flex items-center gap-3 p-3.5 border-2 border-gray-100 dark:!border-slate-700 bg-white dark:!bg-slate-800 rounded-xl hover:bg-[<?= $color['warna_secondary'] ?>] dark:hover:!bg-slate-700 transition-colors cursor-pointer accent-[<?= $color['warna_primary'] ?>] shadow-sm"> 
            <input type="radio" name="parameter" value="kerjasama" class="w-4 h-4"> <span class="font-bold text-sm text-gray-700 dark:!text-slate-300 transition-colors"><?= lang('GuruMapel/ObservasiSikap.param_teamwork') ?></span> 
          </label> 
          <label class="sikap-checkbox flex items-center gap-3 p-3.5 border-2 border-gray-100 dark:!border-slate-700 bg-white dark:!bg-slate-800 rounded-xl hover:bg-[<?= $color['warna_secondary'] ?>] dark:hover:!bg-slate-700 transition-colors cursor-pointer accent-[<?= $color['warna_primary'] ?>] shadow-sm"> 
            <input type="radio" name="parameter" value="sopan-santun" class="w-4 h-4"> <span class="font-bold text-sm text-gray-700 dark:!text-slate-300 transition-colors"><?= lang('GuruMapel/ObservasiSikap.param_politeness') ?></span> 
          </label> 
          <label class="sikap-checkbox flex items-center gap-3 p-3.5 border-2 border-gray-100 dark:!border-slate-700 bg-white dark:!bg-slate-800 rounded-xl hover:bg-[<?= $color['warna_secondary'] ?>] dark:hover:!bg-slate-700 transition-colors cursor-pointer accent-[<?= $color['warna_primary'] ?>] shadow-sm"> 
            <input type="radio" name="parameter" value="kepedulian" class="w-4 h-4"> <span class="font-bold text-sm text-gray-700 dark:!text-slate-300 transition-colors"><?= lang('GuruMapel/ObservasiSikap.param_care') ?></span> 
          </label> 
          <label class="sikap-checkbox flex items-center gap-3 p-3.5 border-2 border-gray-100 dark:!border-slate-700 bg-white dark:!bg-slate-800 rounded-xl hover:bg-[<?= $color['warna_secondary'] ?>] dark:hover:!bg-slate-700 transition-colors cursor-pointer accent-[<?= $color['warna_primary'] ?>] shadow-sm md:col-span-2"> 
            <input type="radio" name="parameter" value="ketaatan-ibadah" class="w-4 h-4"> <span class="font-bold text-sm text-gray-700 dark:!text-slate-300 transition-colors"><?= lang('GuruMapel/ObservasiSikap.param_worship') ?></span> 
          </label>
      </div>
     </div>
     
     <div>
      <label class="block text-[11px] font-black text-gray-500 dark:!text-slate-400 uppercase tracking-widest mb-3 transition-colors"><?= lang('GuruMapel/ObservasiSikap.modal_scale_title') ?></label>
      <div class="flex flex-wrap gap-3">
          <button class="skala-badge skala-sangat-baik px-4 py-2 bg-emerald-50 text-emerald-700 border-emerald-200 dark:!bg-emerald-900/30 dark:!text-emerald-400 dark:!border-emerald-800/50 rounded-lg font-bold text-sm border-2 transition-transform hover:scale-105" onclick="selectSkala(this, 'sangat-baik')"><?= lang('GuruMapel/ObservasiSikap.scale_excellent') ?></button> 
          <button class="skala-badge skala-baik px-4 py-2 bg-blue-50 text-blue-700 border-blue-200 dark:!bg-blue-900/30 dark:!text-blue-400 dark:!border-blue-800/50 rounded-lg font-bold text-sm border-2 transition-transform hover:scale-105" onclick="selectSkala(this, 'baik')"><?= lang('GuruMapel/ObservasiSikap.scale_good') ?></button> 
          <button class="skala-badge skala-cukup px-4 py-2 bg-amber-50 text-amber-700 border-amber-200 dark:!bg-amber-900/30 dark:!text-amber-400 dark:!border-amber-800/50 rounded-lg font-bold text-sm border-2 transition-transform hover:scale-105" onclick="selectSkala(this, 'cukup')"><?= lang('GuruMapel/ObservasiSikap.scale_fair') ?></button> 
          <button class="skala-badge skala-perlu-pembinaan px-4 py-2 bg-rose-50 text-rose-700 border-rose-200 dark:!bg-rose-900/30 dark:!text-rose-400 dark:!border-rose-800/50 rounded-lg font-bold text-sm border-2 transition-transform hover:scale-105" onclick="selectSkala(this, 'perlu-pembinaan')"><?= lang('GuruMapel/ObservasiSikap.scale_needs_guide') ?></button>
      </div>
     </div>
     
     <div>
         <label class="block text-[11px] font-black text-gray-500 dark:!text-slate-400 uppercase tracking-widest mb-2 transition-colors"><?= lang('GuruMapel/ObservasiSikap.modal_notes_title') ?></label> 
         <textarea id="modalCatatan" class="textarea-field w-full px-4 py-3 bg-gray-50 dark:!bg-slate-800 border-2 border-gray-200 dark:!border-slate-700 rounded-xl text-sm text-gray-800 dark:!text-white placeholder-gray-400 dark:!placeholder-slate-500 focus:outline-none focus:bg-white dark:focus:!bg-slate-700 focus:border-[<?= $color['warna_primary'] ?>] transition-all resize-none min-h-[100px] shadow-inner outline-none" placeholder="<?= lang('GuruMapel/ObservasiSikap.modal_notes_ph') ?>"></textarea>
     </div>
    </div>
    
    <div class="px-6 md:px-8 py-5 bg-gray-50 dark:!bg-slate-900/80 border-t border-gray-100 dark:!border-slate-800 flex flex-col sm:flex-row items-center justify-end gap-3 rounded-b-3xl transition-colors">
        <button onclick="closeObservasiModal()" class="w-full sm:w-auto px-6 py-3 bg-white dark:!bg-slate-800 border-2 border-gray-200 dark:!border-slate-700 text-gray-700 dark:!text-slate-300 font-bold rounded-xl hover:bg-gray-50 dark:hover:!bg-slate-700 transition-colors shadow-sm outline-none"> <?= lang('GuruMapel/ObservasiSikap.btn_cancel') ?> </button> 
        <button onclick="simpanObservasi()" class="w-full sm:w-auto px-8 py-3 bg-[<?= $color['warna_primary'] ?>] hover:brightness-110 text-white font-bold rounded-xl transition-all shadow-lg flex items-center justify-center gap-2 outline-none">
         <svg class="w-5 h-5" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" /></svg> <?= lang('GuruMapel/ObservasiSikap.btn_save') ?>
        </button>
    </div>
   </div>
</div>

<div id="quickModal" class="modal-overlay fixed inset-0 z-[100000] hidden items-center justify-center p-4 bg-gray-900/70 backdrop-blur-sm transition-all overflow-y-auto" onclick="if(event.target === this) closeQuickModal()">
   <div class="relative w-full max-w-2xl bg-white dark:!bg-slate-900 rounded-3xl shadow-2xl transition-colors border border-transparent dark:!border-slate-800 transform scale-95 opacity-0 animate-in overflow-hidden duration-300 mx-auto">
    <div class="bg-gradient-to-r from-[<?= $color['warna_primary'] ?>]/90 to-[<?= $color['warna_primary'] ?>] px-6 md:px-8 py-5 flex items-center justify-between">
     <h2 class="text-xl font-black text-white uppercase tracking-widest"><?= lang('GuruMapel/ObservasiSikap.modal_quick_title') ?></h2>
     <button onclick="closeQuickModal()" class="text-white/80 hover:text-white bg-white/10 hover:bg-white/20 rounded-xl p-2 transition-colors outline-none">
      <svg class="w-5 h-5" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" /></svg>
     </button>
    </div>
    
    <div class="p-6 md:p-8 space-y-6">
     <p class="text-sm text-gray-600 dark:!text-slate-400 font-medium transition-colors bg-blue-50 dark:!bg-blue-900/20 border-l-4 border-blue-500 p-3 rounded-r-xl leading-relaxed">
         <?= lang('GuruMapel/ObservasiSikap.modal_quick_desc') ?>
     </p>
     
     <div>
      <label class="block text-[11px] font-black text-gray-500 dark:!text-slate-400 uppercase tracking-widest mb-3 transition-colors"><?= lang('GuruMapel/ObservasiSikap.quick_select_students') ?></label>
      <div class="grid grid-cols-1 md:grid-cols-2 gap-3 max-h-64 overflow-y-auto border border-gray-200 dark:!border-slate-700 bg-gray-50 dark:!bg-slate-800/50 rounded-2xl p-4 transition-colors custom-scrollbar shadow-inner">
          <?php foreach($siswas as $s): ?>
          <label class="student-checkbox flex items-center gap-3 p-3 bg-white dark:!bg-slate-800 border border-gray-100 dark:!border-slate-700 rounded-xl hover:bg-[<?= $color['warna_secondary'] ?>] dark:hover:!bg-slate-700 transition-colors cursor-pointer accent-[<?= $color['warna_primary'] ?>] shadow-sm"> 
              <input type="checkbox" value="<?= $s['id'] ?>" class="w-4 h-4"> 
              <span class="text-sm font-bold text-gray-700 dark:!text-slate-300 transition-colors"><?= esc($s['nama_lengkap']) ?></span> 
          </label>
          <?php endforeach; ?>
      </div>
     </div>
     
     <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
      <div>
          <label class="block text-[11px] font-black text-gray-500 dark:!text-slate-400 uppercase tracking-widest mb-2 transition-colors"><?= lang('GuruMapel/ObservasiSikap.table_param') ?></label> 
          <select id="quickParameter" class="select-field w-full px-4 py-3.5 bg-white dark:!bg-slate-800 border-2 border-gray-200 dark:!border-slate-700 rounded-xl text-sm text-gray-900 dark:!text-white focus:outline-none focus:border-[<?= $color['warna_primary'] ?>] transition-all cursor-pointer shadow-sm appearance-none outline-none"> 
              <option value=""><?= lang('GuruMapel/ObservasiSikap.quick_select_param') ?></option> 
              <option value="disiplin"><?= lang('GuruMapel/ObservasiSikap.param_discipline') ?></option> 
              <option value="tanggung-jawab"><?= lang('GuruMapel/ObservasiSikap.param_responsibility') ?></option> 
              <option value="kejujuran"><?= lang('GuruMapel/ObservasiSikap.param_honesty') ?></option> 
              <option value="kerjasama"><?= lang('GuruMapel/ObservasiSikap.param_teamwork') ?></option> 
              <option value="sopan-santun"><?= lang('GuruMapel/ObservasiSikap.param_politeness') ?></option> 
              <option value="kepedulian"><?= lang('GuruMapel/ObservasiSikap.param_care') ?></option> 
              <option value="ketaatan-ibadah"><?= lang('GuruMapel/ObservasiSikap.param_worship') ?></option> 
          </select>
      </div>
      <div>
          <label class="block text-[11px] font-black text-gray-500 dark:!text-slate-400 uppercase tracking-widest mb-2 transition-colors"><?= lang('GuruMapel/ObservasiSikap.table_scale') ?></label> 
          <select id="quickSkala" class="select-field w-full px-4 py-3.5 bg-white dark:!bg-slate-800 border-2 border-gray-200 dark:!border-slate-700 rounded-xl text-sm text-gray-900 dark:!text-white focus:outline-none focus:border-[<?= $color['warna_primary'] ?>] transition-all cursor-pointer shadow-sm appearance-none outline-none"> 
              <option value=""><?= lang('GuruMapel/ObservasiSikap.quick_select_scale') ?></option> 
              <option value="sangat-baik"><?= lang('GuruMapel/ObservasiSikap.scale_excellent') ?></option> 
              <option value="baik"><?= lang('GuruMapel/ObservasiSikap.scale_good') ?></option> 
              <option value="cukup"><?= lang('GuruMapel/ObservasiSikap.scale_fair') ?></option> 
              <option value="perlu-pembinaan"><?= lang('GuruMapel/ObservasiSikap.scale_needs_guide') ?></option> 
          </select>
      </div>
     </div>
     
     <div>
         <label class="block text-[11px] font-black text-gray-500 dark:!text-slate-400 uppercase tracking-widest mb-2 transition-colors"><?= lang('GuruMapel/ObservasiSikap.quick_notes_title') ?></label> 
         <textarea id="quickCatatan" class="textarea-field w-full px-4 py-3 bg-gray-50 dark:!bg-slate-800 border-2 border-gray-200 dark:!border-slate-700 rounded-xl text-sm text-gray-800 dark:!text-white placeholder-gray-400 dark:!placeholder-slate-500 focus:outline-none focus:bg-white dark:focus:!bg-slate-700 focus:border-[<?= $color['warna_primary'] ?>] transition-all resize-none h-[90px] shadow-inner outline-none" placeholder="<?= lang('GuruMapel/ObservasiSikap.modal_notes_ph') ?>"></textarea>
     </div>
    </div>
    
    <div class="px-6 md:px-8 py-5 bg-gray-50 dark:!bg-slate-900/80 border-t border-gray-100 dark:!border-slate-800 flex flex-col sm:flex-row items-center justify-end gap-3 rounded-b-3xl transition-colors">
        <button onclick="closeQuickModal()" class="w-full sm:w-auto px-6 py-3 bg-white dark:!bg-slate-800 border-2 border-gray-200 dark:!border-slate-700 text-gray-700 dark:!text-slate-300 font-bold rounded-xl hover:bg-gray-50 dark:hover:!bg-slate-700 transition-colors shadow-sm outline-none"> <?= lang('GuruMapel/ObservasiSikap.btn_cancel') ?> </button> 
        <button onclick="simpanQuickObservasi()" class="w-full sm:w-auto px-8 py-3 bg-[<?= $color['warna_primary'] ?>] hover:brightness-110 text-white font-bold rounded-xl transition-all shadow-lg flex items-center justify-center gap-2 outline-none">
         <svg class="w-5 h-5" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" /></svg> <?= lang('GuruMapel/ObservasiSikap.btn_save_all') ?>
        </button>
    </div>
   </div>
</div>
<?= $this->endSection() ?>
<?= $this->section('scripts') ?>
<script>
  const URL_GET_DATA = "<?= base_url('guru/observasi-sikap/get-data') ?>";
  const URL_STORE = "<?= base_url('guru/observasi-sikap/store') ?>";
  const URL_DELETE = "<?= base_url('guru/observasi-sikap/delete') ?>";
  const ACTIVE_ROMBEL_ID = <?= $info['rombel_id'] ?>;
  const ACTIVE_MAPEL_ID = <?= $info['mapel_id'] ?>;
  const THEME_COLOR = "<?= $color['warna_primary'] ?>";
  const csrfTokenName = "<?= csrf_token() ?>";
  const csrfTokenHash = "<?= csrf_hash() ?>";

  window.LANG = {
      empty_data: "<?= lang('GuruMapel/ObservasiSikap.js_empty_data') ?: 'Belum ada data observasi' ?>",
      advanced_act: "<?= lang('GuruMapel/ObservasiSikap.js_advanced_act') ?: 'Aksi Lanjutan' ?>",
      delete_perm: "<?= lang('GuruMapel/ObservasiSikap.js_delete_perm') ?: 'Hapus Permanen' ?>",
      err_incomplete: "<?= lang('GuruMapel/ObservasiSikap.js_err_incomplete') ?: '⚠️ Mohon lengkapi semua data!' ?>",
      err_quick_inc: "<?= lang('GuruMapel/ObservasiSikap.js_err_quick_inc') ?: '⚠️ Pilih minimal 1 siswa dan lengkapi data!' ?>",
      succ_saved: "<?= lang('GuruMapel/ObservasiSikap.js_succ_saved') ?: '✓ Observasi berhasil disimpan!' ?>",
      del_confirm: "<?= lang('GuruMapel/ObservasiSikap.js_del_confirm') ?: 'Yakin ingin menghapus catatan observasi ini?' ?>",
      succ_del: "<?= lang('GuruMapel/ObservasiSikap.js_succ_del') ?: '🗑️ Data observasi dihapus!' ?>"
  };
</script>
<script src="<?= base_url('assets/js/GuruMapel/observasi-sikap.js') ?>?v=<?= time() ?>"></script>
<?= $this->endSection() ?>