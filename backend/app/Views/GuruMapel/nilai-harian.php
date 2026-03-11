<?= $this->extend('layout/main') ?>

<?= $this->section('title') ?>
  <?= lang('GuruMapel/NilaiHarian.page_title') ?> - Rapor Digital SMPIT Ad Durrah
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
  :root { --warna-scroll: <?= $color['warna_primary'] ?>; }
  .dark .modal-content { background-color: #0f172a !important; border-color: #1e293b !important; }
</style>
<link rel="stylesheet" href="<?= base_url('assets/css/GuruMapel/nilai-harian.css') ?>">
<?= $this->endSection() ?>

<?= $this->section('content') ?>
      <div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
       <div>
        <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white mb-2 transition-colors" id="pageTitle"><?= lang('GuruMapel/NilaiHarian.page_title') ?></h1>
        <p class="text-base text-gray-600 dark:text-slate-400 font-medium transition-colors" id="pageSubtitle"><?= lang('GuruMapel/NilaiHarian.page_subtitle') ?></p>
       </div>

       <div class="w-full md:w-auto">
         <label class="block text-[10px] font-black text-gray-500 dark:!text-slate-400 uppercase tracking-widest mb-1 ml-1">Pindah Kelas / Mapel</label>
         <select onchange="window.location.href='?rombel='+this.value.split('|')[0]+'&mapel='+this.value.split('|')[1]" 
                 class="w-full md:w-64 p-3 bg-white dark:!bg-slate-800 border-2 border-gray-200 dark:!border-slate-700 rounded-xl font-bold text-sm text-gray-800 dark:!text-white outline-none focus:border-[<?= $color['warna_primary'] ?>] transition-colors cursor-pointer shadow-sm">
             <?php if(!empty($allRombel)): ?>
                 <?php foreach($allRombel as $rb): ?>
                     <option value="<?= $rb['rombel_id'] ?>|<?= $rb['mapel_id'] ?>" <?= ($rb['rombel_id'] == $info['rombel_id'] && $rb['mapel_id'] == $info['mapel_id']) ? 'selected' : '' ?>>
                         <?= esc($rb['nama_kelas']) ?> - <?= esc($rb['nama_mapel']) ?>
                     </option>
                 <?php endforeach; ?>
             <?php else: ?>
                 <option value="">Belum Ada Kelas</option>
             <?php endif; ?>
         </select>
       </div>
      </div>
      
      <div class="info-card bg-[<?= $color['warna_secondary'] ?>] dark:bg-slate-800 border border-[<?= $color['warna_primary'] ?>]/80 dark:border-slate-700 mb-6 p-5 rounded-2xl shadow-sm transition-colors">
       <div class="grid grid-cols-2 lg:grid-cols-5 gap-6">
        
        <div class="flex items-center gap-4">
         <div class="w-12 h-12 rounded-xl bg-emerald-500 flex items-center justify-center shadow-lg shadow-emerald-500/20 flex-shrink-0 transition-transform hover:scale-105">
          <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
         </div>
         <div class="min-w-0 pr-2">
          <p class="text-[11px] text-[<?= $color['warna_primary'] ?>] font-black uppercase tracking-widest mb-1 truncate transition-colors"><?= lang('GuruMapel/NilaiHarian.info_subject') ?></p>
          <p class="text-base font-bold text-gray-900 dark:text-white transition-colors truncate" id="infoSubject"><?= esc($info['mapel_nama']) ?></p>
         </div>
        </div>
        
        <div class="flex items-center gap-4">
         <div class="w-12 h-12 rounded-xl bg-blue-500 flex items-center justify-center shadow-lg shadow-blue-500/20 flex-shrink-0 transition-transform hover:scale-105">
          <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
         </div>
         <div class="min-w-0 pr-2">
          <p class="text-[11px] text-[<?= $color['warna_primary'] ?>] font-black uppercase tracking-widest mb-1 truncate transition-colors"><?= lang('GuruMapel/NilaiHarian.info_class') ?></p>
          <p class="text-base font-bold text-gray-900 dark:text-white transition-colors truncate" id="infoClass"><?= esc($info['kelas_nama']) ?></p>
         </div>
        </div>
        
        <div class="flex items-center gap-4">
         <div class="w-12 h-12 rounded-xl bg-purple-500 flex items-center justify-center shadow-lg shadow-purple-500/20 flex-shrink-0 transition-transform hover:scale-105">
          <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
         </div>
         <div class="min-w-0 pr-2">
          <p class="text-[11px] text-[<?= $color['warna_primary'] ?>] font-black uppercase tracking-widest mb-1 truncate transition-colors"><?= lang('GuruMapel/NilaiHarian.info_student_count') ?></p>
          <p class="text-base font-bold text-gray-900 dark:text-white transition-colors truncate"><?= esc($info['jml_siswa']) ?> <?= lang('GuruMapel/NilaiHarian.info_student_text') ?></p>
         </div>
        </div>
        
        <div class="flex items-center gap-4">
         <div class="w-12 h-12 rounded-xl bg-amber-500 flex items-center justify-center shadow-lg shadow-amber-500/20 flex-shrink-0 transition-transform hover:scale-105">
          <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
         </div>
         <div class="min-w-0 pr-2">
          <p class="text-[11px] text-[<?= $color['warna_primary'] ?>] font-black uppercase tracking-widest mb-1 truncate transition-colors"><?= lang('GuruMapel/NilaiHarian.info_homeroom') ?></p>
          <p class="text-base font-bold text-gray-900 dark:text-white transition-colors truncate" id="infoWaliKelas"><?= esc($info['wali_kelas']) ?></p>
         </div>
        </div>
        
        <div class="flex items-center gap-4">
         <div class="w-12 h-12 rounded-xl bg-red-500 flex items-center justify-center shadow-lg shadow-red-500/20 flex-shrink-0 transition-transform hover:scale-105">
          <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg>
         </div>
         <div class="min-w-0">
          <p class="text-[11px] text-[<?= $color['warna_primary'] ?>] font-black uppercase tracking-widest mb-1 truncate transition-colors"><?= lang('GuruMapel/NilaiHarian.info_status') ?></p>
          <span class="inline-flex px-2 py-0.5 rounded-lg text-[10px] font-black uppercase tracking-wider transition-all" id="statusBadge">
             <?= lang('GuruMapel/NilaiHarian.status_draft') ?> 
          </span>
         </div>
        </div>
        
       </div>
      </div> 
      
      <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm p-6 mb-6 border border-gray-100 dark:border-slate-700 transition-colors">
       <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-5 items-end">
        
        <div class="flex flex-col">
         <label class="text-[11px] font-black text-gray-500 dark:text-slate-400 uppercase tracking-widest mb-2 transition-colors"><?= lang('GuruMapel/NilaiHarian.type_label') ?></label> 
         <select id="jenisPenilaian" class="px-4 py-2.5 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-[<?= $color['warna_primary'] ?>] focus:border-[<?= $color['warna_primary'] ?>] transition-all outline-none appearance-none cursor-pointer" onchange="checkReadyToInput()"> 
          <option value=""><?= lang('GuruMapel/NilaiHarian.type_select') ?></option> 
          <option value="uh"><?= lang('GuruMapel/NilaiHarian.type_uh') ?></option> 
          <option value="tugas"><?= lang('GuruMapel/NilaiHarian.type_task') ?></option> 
          <option value="kuis"><?= lang('GuruMapel/NilaiHarian.type_quiz') ?></option> 
         </select>
        </div>
        
        <div class="flex flex-col">
         <label class="text-[11px] font-black text-gray-500 dark:text-slate-400 uppercase tracking-widest mb-2 transition-colors"><?= lang('GuruMapel/NilaiHarian.meeting_label') ?></label> 
         <select id="pertemuan" class="px-4 py-2.5 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-[<?= $color['warna_primary'] ?>] focus:border-[<?= $color['warna_primary'] ?>] transition-all outline-none appearance-none cursor-pointer" onchange="checkReadyToInput()"> 
          <option value=""><?= lang('GuruMapel/NilaiHarian.meeting_select') ?></option> 
          <option value="1"><?= lang('GuruMapel/NilaiHarian.meeting_1') ?></option> 
          <option value="2"><?= lang('GuruMapel/NilaiHarian.meeting_2') ?></option> 
         </select>
        </div>
        
        <div class="flex flex-col">
         <label class="text-[11px] font-black text-gray-500 dark:text-slate-400 uppercase tracking-widest mb-2 transition-colors"><?= lang('GuruMapel/NilaiHarian.date_label') ?></label> 
         <input type="date" id="tanggalPenilaian" class="px-4 py-2.5 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-[<?= $color['warna_primary'] ?>] transition-all outline-none" onchange="checkReadyToInput()">
        </div>
        
         <div class="flex flex-col">
             <label class="text-[11px] font-black text-gray-500 dark:text-slate-400 uppercase tracking-widest mb-2 transition-colors"><?= lang('GuruMapel/NilaiHarian.kkm_label') ?></label>
             <input type="number" id="kkm" value="75" 
                    class="px-4 py-2.5 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-xl text-center font-bold text-gray-900 dark:text-white focus:ring-2 focus:ring-[<?= $color['warna_primary'] ?>] transition-all outline-none w-full" 
                    onkeyup="refreshKkmColors()" onchange="refreshKkmColors()" >
         </div>
         <div class="flex flex-col">
           <label class="text-[11px] font-black text-gray-500 dark:text-slate-400 uppercase tracking-widest mb-2 transition-colors"><?= lang('GuruMapel/NilaiHarian.semester_label') ?></label>
           <select id="semesterFilter" class="px-4 py-2.5 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-[<?= $color['warna_primary'] ?>] focus:border-[<?= $color['warna_primary'] ?>] transition-all outline-none appearance-none cursor-pointer" onchange="checkReadyToInput()"> 
             <option value="Ganjil"><?= lang('GuruMapel/NilaiHarian.semester_odd') ?></option>
             <option value="Genap"><?= lang('GuruMapel/NilaiHarian.semester_even') ?></option>
           </select>
         </div>
  
        <div class="flex flex-col lg:col-span-5 mt-4 md:mt-0">
         <button id="btnLoadData" class="w-full bg-gradient-to-r from-[<?= $color['warna_primary'] ?>] to-[<?= $color['warna_primary'] ?>]/80 text-white font-black uppercase tracking-widest py-3 px-6 rounded-xl shadow-lg hover:shadow-xl transition-all transform hover:-translate-y-1 disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-3 outline-none" onclick="loadNilaiData()" disabled>
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" /></svg> 
          <?= lang('GuruMapel/NilaiHarian.btn_load_data') ?> 
         </button>
        </div>
        
       </div>
      </div>
      
      <div id="progressContainer" class="bg-white dark:bg-slate-800 rounded-2xl p-5 mb-6 border border-gray-100 dark:border-slate-700 transition-colors shadow-sm items-center gap-4" style="display: none;">
       <svg class="w-8 h-8 text-emerald-500 flex-shrink-0" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
       <div class="flex-1 min-w-0">
        <div class="flex items-center justify-between mb-2">
         <span class="text-[11px] font-black text-gray-500 dark:text-slate-400 uppercase tracking-widest transition-colors"><?= lang('GuruMapel/NilaiHarian.progress_title') ?></span> 
         <span class="text-sm font-black text-emerald-600 dark:text-emerald-400 transition-colors" id="progressText">0/0 siswa</span>
        </div>
        <div class="w-full bg-gray-100 dark:bg-slate-700 rounded-full h-2 shadow-inner transition-colors">
         <div class="bg-gradient-to-r from-emerald-400 to-emerald-600 h-2 rounded-full transition-all duration-700 shadow-[0_0_8px_rgba(16,185,129,0.4)]" id="progressFill" style="width: 0%"></div>
        </div>
       </div>
      </div>
      
      <div id="tableContainer" class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-gray-100 dark:border-slate-700 overflow-hidden transition-colors" style="display: none;">
       <div class="overflow-x-auto custom-scrollbar">
        <table class="w-full text-left border-collapse min-w-max">
         <thead class="bg-gray-50 dark:bg-slate-900/50 border-b border-gray-100 dark:border-slate-700 transition-colors">
          <tr class="text-[11px] font-black text-gray-500 dark:text-slate-400 uppercase tracking-widest transition-colors">
           <th class="px-6 py-4 text-center"><?= lang('GuruMapel/NilaiHarian.th_no') ?></th>
           <th class="px-6 py-4"><?= lang('GuruMapel/NilaiHarian.th_name') ?></th>
           <th class="px-6 py-4"><?= lang('GuruMapel/NilaiHarian.th_nis') ?></th>
           <th class="px-6 py-4 text-center"><?= lang('GuruMapel/NilaiHarian.th_grade') ?></th>
           <th class="px-6 py-4 text-center"><?= lang('GuruMapel/NilaiHarian.th_predicate') ?></th>
           <th class="px-6 py-4"><?= lang('GuruMapel/NilaiHarian.th_notes') ?></th>
          </tr>
         </thead>
         <tbody id="nilaiTableBody" class="divide-y divide-gray-100 dark:divide-slate-700/50 bg-white dark:bg-slate-800 transition-colors">
          </tbody>
        </table>
       </div>
      </div>
      
      <div id="emptyState" class="bg-white dark:bg-slate-800 rounded-3xl py-16 px-6 border-2 border-dashed border-gray-200 dark:border-slate-700 text-center shadow-sm transition-colors">
       <div class="w-24 h-24 bg-gray-50 dark:bg-slate-700/30 rounded-full flex items-center justify-center mx-auto mb-5 transition-colors border-2 border-gray-100 dark:border-slate-700">
          <svg class="w-12 h-12 text-gray-300 dark:text-slate-500 transition-colors" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
       </div>
       <h3 class="text-xl font-black text-gray-800 dark:text-white mb-2 transition-colors"><?= lang('GuruMapel/NilaiHarian.empty_title') ?></h3>
       <p class="text-sm font-medium text-gray-500 dark:text-slate-400 max-w-sm mx-auto transition-colors"><?= lang('GuruMapel/NilaiHarian.empty_desc') ?></p>
      </div>
    
    <div id="actionToolbar" class="bg-white dark:bg-slate-800 rounded-3xl shadow-xl p-5 mt-8 border border-gray-100 dark:border-slate-700 transition-all" style="display: none;">
     <div class="flex flex-col md:flex-row items-center justify-between gap-5">
      
      <div class="flex items-center gap-3 bg-emerald-50 dark:bg-emerald-900/20 px-4 py-2 rounded-xl transition-colors">
       <svg class="w-5 h-5 text-emerald-500 animate-pulse" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
       <span class="text-[11px] text-emerald-800 dark:text-emerald-400 font-black uppercase tracking-wider transition-colors"><?= lang('GuruMapel/NilaiHarian.auto_save_info') ?></span>
      </div>
      
      <div class="flex flex-wrap items-center justify-center md:justify-end gap-3 w-full md:w-auto">
       <button class="px-5 py-2.5 border-2 border-red-200 dark:border-red-900/30 text-red-600 dark:text-red-400 hover:bg-red-600 hover:text-white dark:hover:bg-red-600 dark:hover:text-white font-bold rounded-xl transition-all flex items-center gap-2 outline-none shadow-sm" onclick="resetAllNilai()">
         <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
         <?= lang('GuruMapel/NilaiHarian.btn_reset_all') ?> 
       </button> 
       
       <button id="btnSaveDraft" class="px-5 py-2.5 bg-gray-100 dark:bg-slate-700 text-gray-700 dark:text-slate-300 hover:bg-gray-200 dark:hover:bg-slate-600 font-bold rounded-xl transition-all flex items-center gap-2 outline-none shadow-sm" onclick="saveDraft()">
         <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" /></svg>
         <?= lang('GuruMapel/NilaiHarian.btn_save_draft') ?> 
       </button> 
       
       <button id="btnSaveLock" class="px-6 py-2.5 bg-gradient-to-r from-emerald-500 to-emerald-600 text-white font-black uppercase tracking-wider text-xs rounded-xl shadow-lg shadow-emerald-500/30 hover:brightness-110 transform hover:-translate-y-1 transition-all flex items-center gap-2 outline-none" onclick="saveLock()">
         <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
         <?= lang('GuruMapel/NilaiHarian.btn_save_lock') ?> 
       </button>

       <button type="button" onclick="closeTable()" class="px-5 py-2.5 bg-white dark:bg-slate-800 text-gray-700 dark:text-slate-300 border border-gray-300 dark:border-slate-600 rounded-xl font-bold hover:bg-gray-50 dark:hover:bg-slate-700 transition-all outline-none shadow-sm">
         <?= lang('GuruMapel/NilaiHarian.btn_close') ?>
       </button>
      </div>      
     </div>      
    </div>
<?= $this->endSection() ?>

<?= $this->section('modals') ?>
<div id="toast" class="fixed top-4 right-4 z-[1000000] flex items-center gap-3 px-4 py-3 bg-white dark:bg-slate-800 text-gray-800 dark:text-white border-l-4 border-emerald-500 rounded-xl shadow-2xl transition-all duration-300 transform translate-x-full opacity-0">
   <div class="w-8 h-8 rounded-full bg-emerald-100 dark:bg-emerald-900/30 flex items-center justify-center flex-shrink-0">
      <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
   </div>
   <span class="font-bold text-sm" id="toastMessage"><?= lang('GuruMapel/NilaiHarian.toast_success') ?></span>
</div>

<div id="confirmModal" class="fixed inset-0 z-[99999] hidden flex items-center justify-center p-4 bg-gray-950/80 backdrop-blur-sm transition-all overflow-y-auto">
 <div class="relative w-full max-w-md bg-white dark:bg-slate-900 rounded-3xl shadow-2xl transition-colors border border-transparent dark:border-slate-800 transform scale-95 opacity-0 animate-in">
  <div class="bg-gradient-to-r from-red-600 to-red-700 px-6 py-6 flex items-center justify-between rounded-t-3xl transition-colors">
   <h2 class="text-xl font-black text-white uppercase tracking-wider"><?= lang('GuruMapel/NilaiHarian.modal_confirm_title') ?></h2>
   <button onclick="closeConfirmModal()" class="text-white/80 hover:text-white bg-white/10 p-2 rounded-xl transition-colors outline-none">
    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" /></svg>
   </button>
  </div>
  <div class="p-8">
   <div class="w-16 h-16 bg-red-100 dark:bg-red-900/30 rounded-full flex items-center justify-center text-red-600 dark:text-red-400 mx-auto mb-6 shadow-sm">
      <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
   </div>
   <p class="text-gray-700 dark:text-slate-300 font-bold text-center leading-relaxed transition-colors" id="confirmMessage"><?= lang('GuruMapel/NilaiHarian.modal_confirm_msg') ?></p>
  </div>
  <div class="px-8 py-6 bg-gray-50 dark:bg-slate-800/50 border-t border-gray-100 dark:border-slate-800 flex flex-col sm:flex-row items-center justify-center gap-3 rounded-b-3xl transition-colors">
    <button onclick="closeConfirmModal()" class="w-full sm:w-1/2 px-6 py-3 border-2 border-gray-300 dark:border-slate-600 rounded-2xl font-black uppercase tracking-widest text-gray-700 dark:text-slate-300 hover:bg-gray-100 dark:hover:bg-slate-700 transition-all text-xs outline-none"> 
        <?= lang('GuruMapel/NilaiHarian.btn_cancel') ?> 
    </button> 
    <button onclick="confirmAction()" class="w-full sm:w-1/2 px-6 py-3 bg-red-600 hover:bg-red-700 text-white rounded-2xl font-black uppercase tracking-widest transition-all shadow-lg shadow-red-600/30 hover:scale-105 text-xs outline-none"> 
        <?= lang('GuruMapel/NilaiHarian.btn_proceed') ?> 
    </button>
  </div>
 </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    const BASE_URL = "<?= rtrim(base_url(), '/') ?>";
    const ACTIVE_ROMBEL_ID = <?= $info['rombel_id'] ?? 0 ?>;
    const ACTIVE_MAPEL_ID = <?= $info['mapel_id'] ?? 0 ?>;
    const csrfTokenName = "<?= csrf_token() ?>";
    const csrfTokenHash = "<?= csrf_hash() ?>";

    window.LANG = {
        fill_all: "Mohon lengkapi semua field!",
        loading: "Memuat...",
        locked: "Terkunci",
        draft: "Draft",
        load_success: "Data berhasil dimuat!",
        err_load_data: "Gagal memuat data siswa: ",
        err_server: "Terjadi kesalahan pada server saat memuat data.",
        btn_load: "Load Data",
        ph_desc: "Deskripsi capaian siswa...",
        no_input: "Tidak ada nilai yang diinput!",
        saving: "Menyimpan...",
        succ_draft: "✓ Draft berhasil disimpan!",
        fail_prefix: "Gagal: ",
        err_save_draft: "Terjadi kesalahan pada server saat menyimpan draft.",
        lock_confirm_1: "Anda akan mengunci nilai untuk ",
        lock_confirm_2: " - ",
        lock_confirm_3: ". Setelah dikunci, nilai akan masuk ke database. Lanjutkan?",
        err_save: "Terjadi kesalahan pada server saat menyimpan.",
        succ_reset: "✓ Semua nilai berhasil direset!",
        reset_confirm: "Apakah Anda yakin ingin menghapus semua nilai yang sudah diinput? Tindakan ini tidak dapat dibatalkan.",
        auto_save: "✓ Nilai tersimpan otomatis",
        auto_save_silent: "⏳ Nilai tersimpan otomatis",
        exit_edit: "Keluar dari mode edit."
    };
</script>
<script src="<?= base_url('assets/js/GuruMapel/nilai-harian.js') ?>?v=<?= time() ?>"></script>
<?= $this->endSection() ?>