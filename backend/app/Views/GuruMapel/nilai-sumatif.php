<?= $this->extend('layout/main') ?>

<?= $this->section('title') ?>
  <?= lang('GuruMapel/NilaiSumatif.page_title') ?> - Rapor Digital SMPIT Ad Durrah
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
  :root { --warna-scroll: <?= $color['warna_primary'] ?>; }
  .dark .modal-content { background-color: #0f172a !important; border-color: #1e293b !important; }
</style>
<link rel="stylesheet" href="<?= base_url('assets/css/GuruMapel/nilai-sumatif.css') ?>">
<?= $this->endSection() ?>

<?= $this->section('content') ?>
     <div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
      <div>
       <h1 class="text-2xl md:text-3xl font-bold mb-2 text-gray-900 dark:!text-white transition-colors" id="pageTitle"><?= lang('GuruMapel/NilaiSumatif.page_title') ?></h1>
       <p class="text-base text-gray-600 dark:!text-slate-400 font-semibold transition-colors" id="pageSubtitle"><?= lang('GuruMapel/NilaiSumatif.page_subtitle') ?></p>
      </div>

      <div class="w-full md:w-auto">
        <label class="block text-[10px] font-black text-gray-500 dark:!text-slate-400 uppercase tracking-widest mb-1 ml-1">Pindah Kelas / Mapel</label>
        <select onchange="window.location.href='?rombel='+this.value.split('|')[0]+'&mapel='+this.value.split('|')[1]" 
                class="w-full md:w-64 p-3 bg-white dark:!bg-slate-800 border-2 border-gray-200 dark:!border-slate-700 rounded-xl font-bold text-sm text-gray-800 dark:!text-white outline-none focus:border-[<?= $color['warna_primary'] ?>] transition-colors cursor-pointer shadow-sm">
            <?php if(!empty($allRombel)): ?>
                <?php foreach($allRombel as $rb): ?>
                    <option value="<?= $rb['rombel_id'] ?>|<?= $rb['mapel_id'] ?>" <?= ($rb['rombel_id'] == $info['rombel_id'] && $rb['mapel_id'] == $info['mapel_id']) ? 'selected' : '' ?>>
                        <?= esc($rb['kelas_nama']) ?> - <?= esc($rb['nama_mapel']) ?>
                    </option>
                <?php endforeach; ?>
            <?php else: ?>
                <option value="">Belum Ada Kelas</option>
            <?php endif; ?>
        </select>
      </div>
     </div>
     
     <div class="info-card bg-[<?= $color['warna_secondary'] ?>] dark:!bg-slate-800 border border-[<?= $color['warna_primary'] ?>]/80 dark:!border-slate-700 mb-6 p-5 rounded-2xl shadow-sm transition-colors">
      <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
       
       <div class="flex items-center gap-3">
        <div class="w-12 h-12 rounded-xl bg-emerald-600 flex items-center justify-center shadow-lg shadow-emerald-600/30 flex-shrink-0 transition-transform hover:scale-105">
         <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
        </div>
        <div class="min-w-0 pr-1">
         <p class="text-[10px] text-[<?= $color['warna_primary'] ?>] dark:!text-emerald-400 font-black mb-0.5 uppercase tracking-widest truncate transition-colors"><?= lang('GuruMapel/NilaiSumatif.info_subject') ?></p>
         <p class="text-sm font-black text-[<?= $color['warna_primary'] ?>] dark:!text-white truncate transition-colors" id="infoSubject"><?= esc($info['mapel_nama']) ?></p>
        </div>
       </div>
       
       <div class="flex items-center gap-3">
        <div class="w-12 h-12 rounded-xl bg-blue-600 flex items-center justify-center shadow-lg shadow-blue-600/30 flex-shrink-0 transition-transform hover:scale-105">
         <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
        </div>
        <div class="min-w-0 pr-1">
         <p class="text-[10px] text-[<?= $color['warna_primary'] ?>] dark:!text-blue-400 font-black mb-0.5 uppercase tracking-widest truncate transition-colors"><?= lang('GuruMapel/NilaiSumatif.info_class') ?></p>
         <p class="text-sm font-black text-[<?= $color['warna_primary'] ?>] dark:!text-white truncate transition-colors" id="infoClass"><?= esc($info['kelas_nama']) ?></p>
        </div>
       </div>
       
       <div class="flex items-center gap-3">
        <div class="w-12 h-12 rounded-xl bg-purple-600 flex items-center justify-center shadow-lg shadow-purple-600/30 flex-shrink-0 transition-transform hover:scale-105">
         <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
        </div>
        <div class="min-w-0 pr-1">
         <p class="text-[10px] text-[<?= $color['warna_primary'] ?>] dark:!text-purple-400 font-black mb-0.5 uppercase tracking-widest truncate transition-colors"><?= lang('GuruMapel/NilaiSumatif.info_student_count') ?></p>
         <p class="text-sm font-black text-[<?= $color['warna_primary'] ?>] dark:!text-white truncate transition-colors"><?= esc($info['jml_siswa']) ?> <?= lang('GuruMapel/NilaiSumatif.info_student_text') ?></p>
        </div>
       </div>
       
       <div class="flex items-center gap-3">
        <div class="w-12 h-12 rounded-xl bg-red-600 flex items-center justify-center shadow-lg shadow-red-600/30 flex-shrink-0 transition-transform hover:scale-105">
         <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg>
        </div>
        <div class="min-w-0">
         <p class="text-[10px] text-[<?= $color['warna_primary'] ?>] dark:!text-rose-400 font-black mb-0.5 uppercase tracking-widest truncate transition-colors"><?= lang('GuruMapel/NilaiSumatif.info_status') ?></p>
         <span class="status-badge status-draft inline-flex items-center gap-1.5 dark:!bg-slate-700 dark:!text-slate-300 px-2 py-1 rounded-lg text-[10px] font-bold transition-all" id="statusBadge">
          <svg class="w-3 h-3" fill="currentColor" viewbox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" /></svg> 
          <?= lang('GuruMapel/NilaiSumatif.status_draft') ?> 
         </span>
        </div>
       </div>
       
      </div>
     </div>
     
     <div class="warning-banner bg-amber-50 dark:!bg-amber-950/30 border-2 border-amber-300 dark:!border-amber-900/50 rounded-2xl p-4 mb-6 flex items-start gap-4 transition-colors shadow-sm" id="warningBanner">
      <svg class="w-8 h-8 text-amber-600 dark:!text-amber-500 flex-shrink-0" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
      <div class="flex-1">
       <p class="font-black text-amber-900 dark:!text-amber-400 text-base mb-1 uppercase tracking-wide transition-colors"><?= lang('GuruMapel/NilaiSumatif.warning_title') ?></p>
       <p class="text-sm text-amber-800 dark:!text-amber-500 font-bold transition-colors leading-relaxed" id="warningMessage"><?= lang('GuruMapel/NilaiSumatif.warning_desc') ?></p>
      </div>
     </div>
     
     <div class="config-card bg-white dark:!bg-slate-800 border-2 border-gray-100 dark:!border-slate-700 rounded-3xl p-6 mb-6 shadow-sm transition-colors">
      <h2 class="text-lg font-black text-gray-900 dark:!text-white mb-5 uppercase tracking-widest flex items-center gap-3 border-b border-gray-100 dark:!border-slate-700 pb-3 transition-colors">
       <svg class="w-6 h-6 text-[<?= $color['warna_primary'] ?>]" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" /> <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg> 
       <?= lang('GuruMapel/NilaiSumatif.config_title') ?>
      </h2>
      <div class="grid grid-cols-1 md:grid-cols-4 gap-6">        
          <div>
              <label class="block text-[11px] font-black text-gray-500 dark:!text-slate-400 mb-2 uppercase tracking-widest transition-colors"><?= lang('GuruMapel/NilaiSumatif.type_label') ?></label> 
              <select id="jenisSumatif" class="w-full px-4 py-3.5 bg-gray-50 dark:!bg-slate-700 border-2 border-gray-200 dark:!border-slate-600 rounded-xl text-gray-900 dark:!text-white focus:outline-none focus:border-[<?= $color['warna_primary'] ?>] transition-all cursor-pointer shadow-sm outline-none appearance-none" onchange="checkReadyToInput()"> 
                  <option value=""><?= lang('GuruMapel/NilaiSumatif.type_select') ?></option> 
                  <option value="pts"><?= lang('GuruMapel/NilaiSumatif.type_pts') ?></option> 
                  <option value="pas"><?= lang('GuruMapel/NilaiSumatif.type_pas') ?></option> 
                  <option value="sas"><?= lang('GuruMapel/NilaiSumatif.type_sas') ?></option> 
              </select>
          </div>

          <div>
              <label class="block text-[11px] font-black text-gray-500 dark:!text-slate-400 mb-2 uppercase tracking-widest transition-colors"><?= lang('GuruMapel/NilaiSumatif.weight_label') ?></label> 
              <input type="number" id="bobot" class="w-full px-4 py-3.5 bg-gray-100 dark:!bg-slate-700/50 border-2 border-gray-200 dark:!border-slate-600 rounded-xl text-gray-600 dark:!text-slate-400 font-bold shadow-sm cursor-not-allowed transition-colors" value="40" disabled>
          </div>

          <div>
              <label class="block text-[11px] font-black text-gray-500 dark:!text-slate-400 mb-2 uppercase tracking-widest transition-colors"><?= lang('GuruMapel/NilaiSumatif.kkm_label') ?></label> 
              <input type="number" id="kkm" class="w-full px-4 py-3.5 bg-gray-100 dark:!bg-slate-700/50 border-2 border-gray-200 dark:!border-slate-600 rounded-xl text-gray-600 dark:!text-slate-400 font-bold shadow-sm cursor-not-allowed transition-colors" value="75" disabled>
          </div>

          <div class="flex items-end">
                <button id="btnLoadData" 
                        data-url="<?= base_url('guru/nilai-sumatif/get-siswa') ?>" 
                        class="w-full py-3.5 px-6 bg-[<?= $color['warna_primary'] ?>] hover:brightness-110 text-white font-black uppercase tracking-widest rounded-xl shadow-lg transition-all transform hover:-translate-y-1 disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-3 outline-none" 
                        onclick="loadNilaiData()" disabled>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    <?= lang('GuruMapel/NilaiSumatif.btn_load_data') ?> 
                </button>
          </div>
      </div>
      <div class="mt-5 p-4 bg-blue-50 dark:!bg-blue-900/20 border-l-4 border-blue-500 rounded-r-xl transition-colors">
       <p class="text-xs text-blue-800 dark:!text-blue-300 font-bold leading-relaxed">
        <svg class="w-4 h-4 inline mr-1.5 -mt-0.5" fill="currentColor" viewbox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" /></svg> 
        <?= lang('GuruMapel/NilaiSumatif.config_info') ?>
       </p>
      </div>
     </div>
     
     <div id="progressStatusContainer" class="mb-8" style="display: none;">
      <div class="bg-white dark:!bg-slate-800 border-2 border-gray-100 dark:!border-slate-700 rounded-3xl p-6 shadow-sm transition-colors">
       <h3 class="text-[11px] font-black text-gray-500 dark:!text-slate-400 mb-5 uppercase tracking-[0.2em] text-center transition-colors"><?= lang('GuruMapel/NilaiSumatif.progress_title') ?></h3>
       <div class="flex items-center justify-center gap-4 flex-wrap">
        <div class="progress-step active" id="step1">
         <div class="progress-step-number dark:!ring-slate-800">1</div><span class="text-gray-900 dark:!text-white font-bold text-xs transition-colors"><?= lang('GuruMapel/NilaiSumatif.step_1') ?></span>
        </div>
        <svg class="w-6 h-6 text-gray-300 dark:!text-slate-600" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" /></svg>
        
        <div class="progress-step inactive" id="step2">
         <div class="progress-step-number dark:!ring-slate-800">2</div><span class="text-gray-500 dark:!text-slate-400 font-bold text-xs transition-colors"><?= lang('GuruMapel/NilaiSumatif.step_2') ?></span>
        </div>
        <svg class="w-6 h-6 text-gray-300 dark:!text-slate-600" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" /></svg>
        
        <div class="progress-step inactive" id="step3">
         <div class="progress-step-number dark:!ring-slate-800">3</div><span class="text-gray-500 dark:!text-slate-400 font-bold text-xs transition-colors"><?= lang('GuruMapel/NilaiSumatif.step_3') ?></span>
        </div>
       </div>
      </div>
     </div>

    <div id="tableContainer" class="bg-white dark:!bg-slate-800 rounded-3xl shadow-xl border border-gray-100 dark:!border-slate-700 overflow-hidden transition-colors mb-24" style="display: none;">
      <div class="overflow-x-auto custom-scrollbar">
       <table class="w-full text-left border-collapse min-w-max">
        <thead class="bg-gray-50 dark:!bg-slate-900/50 border-b border-gray-100 dark:!border-slate-700 transition-colors">
         <tr class="text-[10px] font-black text-gray-500 dark:!text-slate-400 uppercase tracking-widest transition-colors">
          <th class="px-6 py-4 text-center" style="width: 60px;"><?= lang('GuruMapel/NilaiSumatif.th_no') ?></th>
          <th class="px-6 py-4 min-w-[220px]"><?= lang('GuruMapel/NilaiSumatif.th_name') ?></th>
          <th class="px-6 py-4" style="width: 130px;"><?= lang('GuruMapel/NilaiSumatif.th_nis') ?></th>
          <th class="px-6 py-4 text-center" style="width: 140px;"><?= lang('GuruMapel/NilaiSumatif.th_final_grade') ?></th>
          <th class="px-6 py-4 text-center" style="width: 90px;"><?= lang('GuruMapel/NilaiSumatif.th_predicate') ?></th>
          <th class="px-6 py-4 min-w-[300px]"><?= lang('GuruMapel/NilaiSumatif.th_desc') ?></th>
          <th class="px-6 py-4 text-center" style="width: 100px;"><?= lang('GuruMapel/NilaiSumatif.th_status') ?></th>
         </tr>
        </thead>
        <tbody id="nilaiTableBody" class="divide-y divide-gray-50 dark:!divide-slate-700/50 bg-white dark:!bg-slate-800 transition-colors">
         </tbody>
       </table>
      </div>
    </div>      

     <div id="emptyState" class="bg-white dark:!bg-slate-800 rounded-3xl py-20 px-6 border-2 border-dashed border-gray-200 dark:!border-slate-700 text-center transition-colors shadow-sm">
      <svg class="w-40 h-40 text-gray-300 dark:!text-slate-700 mx-auto mb-6 opacity-60 transition-colors" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
      <h3 class="text-2xl font-black text-gray-800 dark:!text-white mb-2 uppercase tracking-wide transition-colors"><?= lang('GuruMapel/NilaiSumatif.empty_title') ?></h3>
      <p class="text-gray-500 dark:!text-slate-400 font-semibold transition-colors"><?= lang('GuruMapel/NilaiSumatif.empty_desc') ?></p>
     </div>
    
    <div id="actionToolbar" class="fixed bottom-6 left-1/2 transform -translate-x-1/2 w-[90%] md:w-[85%] lg:w-[calc(100%-20rem)] lg:ml-32 bg-white/90 dark:!bg-slate-800/90 backdrop-blur-md rounded-2xl shadow-2xl p-4 border border-gray-200 dark:!border-slate-700 transition-all z-40" style="display: none;">
     <div class="flex items-center justify-between gap-4 flex-wrap">
      <div class="flex items-center gap-3 bg-emerald-50 dark:!bg-emerald-900/20 px-4 py-2 rounded-xl transition-colors">
       <svg class="w-5 h-5 text-emerald-600 dark:!text-emerald-400 animate-pulse" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
       <span class="text-[11px] text-emerald-800 dark:!text-emerald-400 font-black uppercase tracking-wider transition-colors"><?= lang('GuruMapel/NilaiSumatif.toolbar_info') ?></span>
      </div>
        <div class="flex items-center gap-3 w-full sm:w-auto flex-wrap sm:flex-nowrap">
            <button id="btnSaveDraft" class="flex-1 sm:flex-none py-3 px-6 bg-white dark:!bg-slate-700 border-2 border-indigo-600 dark:!border-indigo-500 text-indigo-700 dark:!text-indigo-300 hover:bg-indigo-50 dark:hover:!bg-indigo-900/30 rounded-xl font-black uppercase tracking-widest text-[10px] transition-all shadow-md outline-none" data-url="<?= base_url('guru/nilai-sumatif/save-draft') ?>" onclick="saveDraft()">
                <?= lang('GuruMapel/NilaiSumatif.btn_save_draft') ?> 
            </button>
            
            <button id="btnMarkReady" class="flex-1 sm:flex-none py-3 px-6 bg-amber-500 hover:bg-amber-600 text-white font-black uppercase tracking-widest text-[10px] rounded-xl transition-all shadow-lg shadow-amber-500/30 outline-none" data-url="<?= base_url('guru/nilai-sumatif/update-status') ?>" onclick="markReady()">
                <?= lang('GuruMapel/NilaiSumatif.btn_mark_ready') ?> 
            </button> 

            <button id="btnCancelReady" class="hidden flex-1 sm:flex-none py-3 px-6 bg-gray-500 hover:bg-gray-600 text-white font-black uppercase tracking-widest text-[10px] rounded-xl transition-all outline-none flex items-center justify-center gap-2" data-url="<?= base_url('guru/nilai-sumatif/update-status') ?>" onclick="cancelReady()">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                <?= lang('GuruMapel/NilaiSumatif.btn_cancel_ready') ?>
            </button>
            
            <button id="btnLock" class="w-full sm:w-auto py-3 px-8 bg-gradient-to-r from-rose-600 to-red-600 hover:brightness-110 text-white font-black uppercase tracking-widest text-[10px] rounded-xl transition-all shadow-lg shadow-red-600/30 outline-none flex items-center justify-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed" data-url="<?= base_url('guru/nilai-sumatif/update-status') ?>" onclick="lockNilai()" disabled>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg> 
                <?= lang('GuruMapel/NilaiSumatif.btn_lock') ?> 
            </button>
        </div>
     </div>
    </div>
<?= $this->endSection() ?>

<?= $this->section('modals') ?>
<div id="toast" class="fixed top-4 right-4 z-[1000000] flex items-center gap-3 px-4 py-3 bg-white dark:!bg-slate-800 text-gray-800 dark:!text-white border-l-4 border-emerald-500 rounded-xl shadow-2xl transition-all duration-300 transform translate-x-full opacity-0">
   <div class="w-8 h-8 rounded-full bg-emerald-100 dark:!bg-emerald-900/30 flex items-center justify-center flex-shrink-0">
      <svg class="w-5 h-5 text-emerald-600 dark:!text-emerald-400" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
   </div>
   <span class="font-bold text-sm tracking-wide pr-2" id="toastMessage"><?= lang('GuruMapel/NilaiSumatif.toast_success') ?></span>
</div>

<div id="confirmModal" class="fixed inset-0 z-[99999] hidden flex items-center justify-center p-4 bg-gray-950/80 backdrop-blur-sm transition-all overflow-y-auto" onclick="if(event.target === this) closeConfirmModal()">
 <div class="relative w-full max-w-md bg-white dark:!bg-slate-900 rounded-3xl shadow-2xl transition-colors border border-transparent dark:!border-slate-800 transform scale-95 opacity-0 animate-in overflow-hidden duration-300">
  <div class="bg-gradient-to-r from-red-600 to-rose-700 px-6 py-6 flex items-center justify-between transition-colors">
   <h2 class="text-xl font-black text-white uppercase tracking-widest"><?= lang('GuruMapel/NilaiSumatif.modal_confirm_title') ?></h2>
   <button onclick="closeConfirmModal()" class="text-white/80 hover:text-white bg-white/10 p-2 rounded-xl transition-colors outline-none">
    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" /></svg>
   </button>
  </div>
  <div class="p-8 text-center">
   <div class="w-20 h-20 bg-rose-100 dark:!bg-rose-900/30 rounded-full flex items-center justify-center text-rose-600 dark:!text-rose-400 mx-auto mb-6 shadow-sm transition-colors">
      <svg class="w-12 h-12" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
   </div>
   <p class="text-gray-700 dark:!text-slate-300 font-bold text-lg leading-relaxed transition-colors" id="confirmMessage"><?= lang('GuruMapel/NilaiSumatif.modal_confirm_msg') ?></p>
  </div>
  <div class="px-8 py-6 bg-gray-50 dark:!bg-slate-800/50 border-t border-gray-100 dark:!border-slate-800 flex flex-col sm:flex-row items-center justify-center gap-3 transition-colors">
   <button onclick="closeConfirmModal()" class="w-full sm:w-1/2 px-6 py-3 border-2 border-gray-300 dark:!border-slate-600 bg-white dark:!bg-slate-800 rounded-2xl font-black uppercase tracking-widest text-gray-700 dark:!text-slate-300 hover:bg-gray-100 dark:hover:!bg-slate-700 transition-all text-xs outline-none shadow-sm"> 
      <?= lang('GuruMapel/NilaiSumatif.btn_cancel') ?> 
   </button> 
   <button onclick="confirmAction()" class="w-full sm:w-1/2 px-6 py-3 bg-red-600 hover:bg-red-700 text-white rounded-2xl font-black uppercase tracking-widest transition-all shadow-lg shadow-red-600/30 hover:scale-105 text-xs outline-none"> 
      <?= lang('GuruMapel/NilaiSumatif.btn_proceed') ?> 
   </button>
  </div>
 </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
    <script>
        const ACTIVE_ROMBEL_ID = <?= $info['rombel_id'] ?? 0 ?>;
        const ACTIVE_MAPEL_ID = <?= $info['mapel_id'] ?? 0 ?>;
        const csrfTokenName = "<?= csrf_token() ?>";
        const csrfTokenHash = "<?= csrf_hash() ?>";

        window.LANG = {
            desc_a: "<?= lang('GuruMapel/NilaiSumatif.js_desc_a') ?: 'Menunjukkan pemahaman yang sangat baik dan mampu menerapkan konsep dengan sempurna.' ?>",
            desc_b: "<?= lang('GuruMapel/NilaiSumatif.js_desc_b') ?: 'Menunjukkan pemahaman yang baik dan mampu menerapkan konsep dengan cukup baik.' ?>",
            desc_c: "<?= lang('GuruMapel/NilaiSumatif.js_desc_c') ?: 'Menunjukkan pemahaman cukup baik namun perlu peningkatan dalam penerapan konsep.' ?>",
            desc_d: "<?= lang('GuruMapel/NilaiSumatif.js_desc_d') ?: 'Perlu bimbingan lebih lanjut untuk meningkatkan pemahaman konsep dasar.' ?>",
            auto_save: "<?= lang('GuruMapel/NilaiSumatif.js_auto_save') ?: '✓ Perubahan tersimpan otomatis' ?>",
            loading: "<?= lang('GuruMapel/NilaiSumatif.js_loading') ?: 'Memuat...' ?>",
            ready: "<?= lang('GuruMapel/NilaiSumatif.js_ready') ?: 'Siap Validasi' ?>",
            locked: "<?= lang('GuruMapel/NilaiSumatif.js_locked') ?: 'Terkunci' ?>",
            draft: "<?= lang('GuruMapel/NilaiSumatif.js_draft') ?: 'Draft' ?>",
            err_load_server: "<?= lang('GuruMapel/NilaiSumatif.js_err_load_server') ?: 'Gagal memuat data dari server. Periksa koneksi atau console browser.' ?>",
            err_no_data_filled: "<?= lang('GuruMapel/NilaiSumatif.js_err_no_data_filled') ?: 'Belum ada nilai yang diisi. Silakan isi minimal satu nilai siswa.' ?>",
            saving: "<?= lang('GuruMapel/NilaiSumatif.js_saving') ?: 'Menyimpan...' ?>",
            succ_draft: "<?= lang('GuruMapel/NilaiSumatif.js_succ_draft') ?: 'Draft nilai berhasil disimpan!' ?>",
            err_save_data: "<?= lang('GuruMapel/NilaiSumatif.js_err_save_data') ?: 'Gagal menyimpan data.' ?>",
            err_server_save: "<?= lang('GuruMapel/NilaiSumatif.js_err_server_save') ?: 'Gagal terhubung ke server saat menyimpan data.' ?>",
            err_no_student: "<?= lang('GuruMapel/NilaiSumatif.js_err_no_student') ?: 'Tidak ada data siswa yang ditampilkan. Silakan load data terlebih dahulu.' ?>",
            warn_empty_val: "<?= lang('GuruMapel/NilaiSumatif.js_warn_empty_val') ?: 'Masih ada nilai siswa yang kosong. Anda yakin ingin menandai data ini sebagai Siap Validasi?' ?>",
            processing: "<?= lang('GuruMapel/NilaiSumatif.js_processing') ?: 'Memproses...' ?>",
            succ_ready: "<?= lang('GuruMapel/NilaiSumatif.js_succ_ready') ?: '✓ Nilai berhasil ditandai Siap Validasi!' ?>",
            succ_ready_alert: "<?= lang('GuruMapel/NilaiSumatif.js_succ_ready_alert') ?: 'Berhasil! Data nilai sekarang berstatus Siap Validasi.' ?>",
            err_update_status: "<?= lang('GuruMapel/NilaiSumatif.js_err_update_status') ?: 'Gagal mengupdate status ke server.' ?>",
            err_server_update: "<?= lang('GuruMapel/NilaiSumatif.js_err_server_update') ?: 'Gagal terhubung ke server saat mengupdate status.' ?>",
            lock_warning: `<?= lang('GuruMapel/NilaiSumatif.js_lock_warning') ?: '<strong>PERINGATAN PENTING:</strong><br><br>Anda akan mengunci nilai akhir ini. Setelah dikunci:<br>• Nilai tidak dapat diubah atau ditarik kembali<br>• Nilai akan masuk secara resmi ke rapor<br>• Hanya Admin yang dapat membuka kunci<br><br>Apakah Anda yakin ingin melanjutkan?' ?>`,
            locking: "<?= lang('GuruMapel/NilaiSumatif.js_locking') ?: 'Mengunci...' ?>",
            succ_lock: "<?= lang('GuruMapel/NilaiSumatif.js_succ_lock') ?: '✓ Nilai berhasil dikunci! Data telah final.' ?>",
            succ_lock_alert: "<?= lang('GuruMapel/NilaiSumatif.js_succ_lock_alert') ?: 'Berhasil! Data nilai telah terkunci secara permanen.' ?>",
            err_lock: "<?= lang('GuruMapel/NilaiSumatif.js_err_lock') ?: 'Gagal mengunci nilai.' ?>",
            err_server_lock: "<?= lang('GuruMapel/NilaiSumatif.js_err_server_lock') ?: 'Gagal terhubung ke server saat mengunci nilai.' ?>",
            warn_cancel_ready: "<?= lang('GuruMapel/NilaiSumatif.js_warn_cancel_ready') ?: 'Apakah Anda yakin ingin menarik kembali data ini menjadi Draft? Anda akan bisa mengedit nilai lagi.' ?>",
            succ_cancel: "<?= lang('GuruMapel/NilaiSumatif.js_succ_cancel') ?: '✓ Data berhasil dikembalikan ke Draft!' ?>",
            succ_cancel_alert: "<?= lang('GuruMapel/NilaiSumatif.js_succ_cancel_alert') ?: 'Berhasil! Silakan edit kembali nilai siswa.' ?>",
            err_server_conn: "<?= lang('GuruMapel/NilaiSumatif.js_err_server_conn') ?: 'Gagal terhubung ke server.' ?>"
        };
    </script>
  <script src="<?= base_url('assets/js/GuruMapel/nilai-sumatif.js') ?>?v=<?= time() ?>"></script>
<?= $this->endSection() ?>