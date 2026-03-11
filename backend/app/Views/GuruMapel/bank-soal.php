<?= $this->extend('layout/main') ?>

<?= $this->section('title') ?>
  <?= lang('GuruMapel/BankSoal.page_title') ?> - Rapor Digital SMPIT Ad Durrah
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
  :root {
    --warna-scroll: <?= $color['warna_primary'] ?>; 
  }
  /* PAKSA MODAL UNTUK BISA TERTEMBUS DARK MODE */
  .dark .modal-content { background-color: transparent !important; box-shadow: none !important; }
</style>
<link rel="stylesheet" href="<?= base_url('assets/css/GuruMapel/bank-soal.css') ?>">
<?= $this->endSection() ?>

<?= $this->section('content') ?>
     <div class="mb-6">
      <h1 class="text-2xl md:text-3xl font-bold b-2 text-gray-900 dark:!text-white transition-colors" id="pageTitle"><?= lang('GuruMapel/BankSoal.page_title') ?></h1>
      <p class="text-base text-gray-600 dark:!text-slate-400 font-medium transition-colors" id="pageSubtitle"><?= lang('GuruMapel/BankSoal.page_subtitle') ?></p>
     </div>

     <div class="info-card bg-[<?= $color['warna_secondary'] ?>] dark:!bg-slate-800 border-[<?= $color['warna_primary'] ?>]/70 dark:!border-slate-700 mb-6 p-5 rounded-2xl shadow-sm transition-colors">
      <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
       <div class="flex items-center gap-3">
        <div class="islamic-icon bg-emerald-600 flex items-center justify-center w-12 h-12 rounded-xl flex-shrink-0 transition-transform hover:scale-105">
         <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
        </div>
        <div class="min-w-0 pr-1">
         <p class="text-[10px] text-[<?= $color['warna_primary'] ?>] dark:!text-emerald-400 font-bold mb-0.5 uppercase tracking-wide transition-colors"><?= lang('GuruMapel/BankSoal.info_subject') ?></p>
         <p class="text-sm font-black text-[<?= $color['warna_primary'] ?>] dark:!text-white truncate transition-colors" id="infoSubjectName"><?= esc($info['mapel_nama']) ?></p>
        </div>
       </div>
       <div class="flex items-center gap-3">
        <div class="islamic-icon bg-purple-600 flex items-center justify-center w-12 h-12 rounded-xl flex-shrink-0 transition-transform hover:scale-105">
         <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
        </div>
        <div class="min-w-0 pr-1">
         <p class="text-[10px] text-[<?= $color['warna_primary'] ?>] dark:!text-purple-400 font-bold mb-0.5 uppercase tracking-wide transition-colors"><?= lang('GuruMapel/BankSoal.info_level') ?></p>
         <p class="text-sm font-black text-[<?= $color['warna_primary'] ?>] dark:!text-white truncate transition-colors" id="infoClassLevel"><?= esc($info['tingkat']) ?></p>
        </div>
       </div>
       <div class="flex items-center gap-3">
        <div class="islamic-icon bg-blue-600 flex items-center justify-center w-12 h-12 rounded-xl flex-shrink-0 transition-transform hover:scale-105">
         <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" /></svg>
        </div>
        <div class="min-w-0 pr-1">
         <p class="text-[10px] text-[<?= $color['warna_primary'] ?>] dark:!text-blue-400 font-bold mb-0.5 uppercase tracking-wide transition-colors"><?= lang('GuruMapel/BankSoal.info_count') ?></p>
         <p class="text-sm font-black text-[<?= $color['warna_primary'] ?>] dark:!text-white transition-colors" id="questionCount">0 Soal</p>
        </div>
       </div>
      </div>
     </div>

     <div class="card-soft bg-white dark:!bg-slate-800 border border-gray-100 dark:!border-slate-700 rounded-3xl p-6 md:p-8 shadow-sm mb-6 transition-colors">
      <div class="flex flex-col lg:flex-row gap-4 mb-6 justify-between items-start lg:items-center">
       <div class="search-box w-full lg:w-96 relative">
        <svg class="search-icon w-5 h-5 absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 dark:!text-slate-500" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
        <input type="text" class="search-input w-full pl-10 pr-4 py-3 rounded-xl border-2 border-gray-200 dark:!border-slate-600 bg-gray-50 dark:!bg-slate-900 text-gray-800 dark:!text-white focus:border-[<?= $color['warna_primary'] ?>]/80 outline-none transition-colors" placeholder="<?= lang('GuruMapel/BankSoal.search_placeholder') ?>" id="searchInput" oninput="filterQuestions()">
       </div>
       <div class="flex gap-3 flex-wrap">
        <button class="btn-primary flex items-center justify-center gap-2 px-5 py-3 rounded-xl text-white font-bold bg-[<?= $color['warna_primary'] ?>]/90 hover:bg-[<?= $color['warna_primary'] ?>] shadow-lg shadow-[<?= $color['warna_primary'] ?>]/20 transition-all outline-none" onclick="showAddQuestionModal()">
         <svg class="w-5 h-5" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg> <?= lang('GuruMapel/BankSoal.btn_add_question') ?> 
        </button> 
        <button class="btn-secondary flex items-center justify-center gap-2 px-5 py-3 rounded-xl text-[<?= $color['warna_primary'] ?>] font-bold border-2 border-[<?= $color['warna_primary'] ?>]/50 hover:bg-[<?= $color['warna_primary'] ?>]/10 transition-colors outline-none" onclick="showImportModal()">
         <svg class="w-5 h-5" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" /></svg> <?= lang('GuruMapel/BankSoal.btn_import') ?> 
        </button>
        <button class="btn-secondary flex items-center justify-center gap-2 px-5 py-3 rounded-xl text-[<?= $color['warna_primary'] ?>] font-bold border-2 border-[<?= $color['warna_primary'] ?>]/50 hover:bg-[<?= $color['warna_primary'] ?>]/10 transition-colors outline-none" onclick="showAddPaketModal()">
         <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg> <?= lang('GuruMapel/BankSoal.btn_create_packet') ?> 
        </button>
       </div>
      </div>
      
      <div class="flex gap-2 flex-wrap items-center bg-gray-50 dark:!bg-slate-900 p-2 rounded-xl border border-gray-200 dark:!border-slate-700 transition-colors overflow-x-auto custom-scrollbar">
       <button class="filter-button px-4 py-2 text-sm font-bold rounded-lg transition-colors whitespace-nowrap [&.active]:bg-[<?= $color['warna_primary'] ?>] [&.active]:text-white dark:text-slate-300 active" onclick="filterByType(this, 'all')"> <?= lang('GuruMapel/BankSoal.filter_all') ?> </button> 
       <button class="filter-button px-4 py-2 text-sm font-bold rounded-lg transition-colors whitespace-nowrap [&.active]:bg-[<?= $color['warna_primary'] ?>] [&.active]:text-white dark:text-slate-300" onclick="filterByType(this, 'pg')"> <?= lang('GuruMapel/BankSoal.filter_pg') ?> </button> 
       <button class="filter-button px-4 py-2 text-sm font-bold rounded-lg transition-colors whitespace-nowrap [&.active]:bg-[<?= $color['warna_primary'] ?>] [&.active]:text-white dark:text-slate-300" onclick="filterByType(this, 'isian')"> <?= lang('GuruMapel/BankSoal.filter_short') ?> </button> 
       <button class="filter-button px-4 py-2 text-sm font-bold rounded-lg transition-colors whitespace-nowrap [&.active]:bg-[<?= $color['warna_primary'] ?>] [&.active]:text-white dark:text-slate-300" onclick="filterByType(this, 'esai')"> <?= lang('GuruMapel/BankSoal.filter_essay') ?> </button> 
       <span class="mx-2 text-gray-300 dark:!text-slate-600">|</span> 
       <button class="filter-button px-4 py-2 text-sm font-bold rounded-lg transition-colors whitespace-nowrap [&.active]:bg-[<?= $color['warna_primary'] ?>] [&.active]:text-white dark:text-slate-300" onclick="filterByDifficulty(this, 'mudah')"> <?= lang('GuruMapel/BankSoal.filter_easy') ?> </button> 
       <button class="filter-button px-4 py-2 text-sm font-bold rounded-lg transition-colors whitespace-nowrap [&.active]:bg-[<?= $color['warna_primary'] ?>] [&.active]:text-white dark:text-slate-300" onclick="filterByDifficulty(this, 'sedang')"> <?= lang('GuruMapel/BankSoal.filter_medium') ?> </button> 
       <button class="filter-button px-4 py-2 text-sm font-bold rounded-lg transition-colors whitespace-nowrap [&.active]:bg-[<?= $color['warna_primary'] ?>] [&.active]:text-white dark:text-slate-300" onclick="filterByDifficulty(this, 'sulit')"> <?= lang('GuruMapel/BankSoal.filter_hard') ?> </button>
      </div>
     </div>

     <div class="card-soft bg-white dark:!bg-slate-800 border border-gray-100 dark:!border-slate-700 rounded-3xl p-6 md:p-8 shadow-sm mb-6 transition-colors">
      <div class="flex items-center justify-between mb-4 border-b border-gray-100 dark:!border-slate-700 pb-4 transition-colors">
       <h2 class="text-lg font-black text-gray-900 dark:!text-white uppercase tracking-wide flex items-center gap-2 transition-colors">
        <svg class="w-5 h-5 text-[<?= $color['warna_primary'] ?>]" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" /></svg> <?= lang('GuruMapel/BankSoal.list_title') ?>
       </h2>
      </div>
      <div id="questionsTableContainer" class="overflow-x-auto custom-scrollbar">
          </div>
      <div id="emptyState" class="empty-state text-center py-12" style="display: none;">
       <div class="empty-state-icon mx-auto w-20 h-20 bg-gray-50 dark:!bg-slate-900 rounded-full flex items-center justify-center mb-4 transition-colors">
        <svg class="w-10 h-10 text-[<?= $color['warna_primary'] ?>] transition-colors" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
       </div>
       <h3 class="text-xl font-black text-gray-900 dark:!text-white mb-2 transition-colors"><?= lang('GuruMapel/BankSoal.empty_state_title') ?></h3>
       <p class="text-gray-600 dark:!text-slate-400 font-medium mb-6 transition-colors" id="emptyStateText"><?= lang('GuruMapel/BankSoal.empty_state_desc') ?></p>
       <button class="btn-primary inline-flex items-center justify-center gap-2 px-6 py-3 rounded-xl bg-[<?= $color['warna_primary'] ?>]/90 hover:bg-[<?= $color['warna_primary'] ?>] text-white font-bold shadow-lg shadow-[<?= $color['warna_primary'] ?>]/20 transition-all outline-none" onclick="showAddQuestionModal()">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg> <?= lang('GuruMapel/BankSoal.btn_add_now') ?> 
       </button>
      </div>
     </div>

     <div class="card-soft bg-white dark:!bg-slate-800/50 border border-gray-100 dark:!border-slate-700 rounded-3xl p-6 md:p-8 shadow-sm mb-8 transition-colors">
      <h2 class="text-lg font-black text-gray-900 dark:!text-white uppercase tracking-wide flex items-center gap-2 mb-6 border-b border-gray-100 dark:!border-slate-700 pb-4 transition-colors">
       <svg class="w-5 h-5 text-[<?= $color['warna_primary'] ?>]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2-2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg> <?= lang('GuruMapel/BankSoal.packet_title') ?>
      </h2>
      <div id="paketSoalContainer" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
          </div>
      <div id="emptyPaketState" class="text-center py-10 hidden">
          <p class="text-gray-500 dark:!text-slate-400 font-medium transition-colors"><?= lang('GuruMapel/BankSoal.packet_empty') ?></p>
      </div>
     </div>
<?= $this->endSection() ?>

<?= $this->section('modals') ?>
  <div id="previewModal" class="modal-overlay fixed inset-0 z-[99999] hidden items-center justify-center p-4 bg-gray-900/70 backdrop-blur-sm transition-all overflow-y-auto" onclick="closePreviewModal(event)">
   <div class="relative w-full max-w-2xl bg-white dark:!bg-slate-900 rounded-3xl shadow-2xl transition-colors border border-transparent dark:!border-slate-800 transform scale-95 opacity-0 animate-in overflow-hidden duration-300 mx-auto" onclick="event.stopPropagation()">
    <div class="bg-gray-50 dark:!bg-slate-800 p-6 border-b-2 border-gray-100 dark:!border-slate-700 transition-colors">
     <div class="flex items-center justify-between">
      <h2 class="text-xl font-black text-gray-900 dark:!text-white transition-colors"><?= lang('GuruMapel/BankSoal.modal_preview_title') ?></h2>
      <button onclick="closePreviewModal()" class="p-2 hover:bg-gray-200 dark:hover:!bg-slate-700 rounded-xl text-gray-600 dark:!text-slate-400 transition-colors outline-none">
       <svg class="w-6 h-6" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
      </button>
     </div>
    </div>
    <div class="p-6 md:p-8" id="previewContent">
        </div>
   </div>
  </div>

  <div id="addQuestionModal" class="modal-overlay fixed inset-0 z-[99999] hidden items-center justify-center p-4 bg-gray-900/70 backdrop-blur-sm transition-all overflow-y-auto" onclick="closeAddQuestionModal(event)">
   <div class="relative w-full max-w-2xl bg-white dark:!bg-slate-900 rounded-3xl shadow-2xl transition-colors border border-transparent dark:!border-slate-800 transform scale-95 opacity-0 animate-in overflow-hidden duration-300 mx-auto" onclick="event.stopPropagation()">
    <div class="bg-gradient-to-r from-[<?= $color['warna_primary'] ?>]/90 to-[<?= $color['warna_primary'] ?>] p-6 md:px-8 py-5 flex items-center justify-between">
     <h2 class="text-xl font-black text-white uppercase tracking-widest"><?= lang('GuruMapel/BankSoal.modal_add_title') ?></h2>
     <button onclick="closeAddQuestionModal()" class="text-white/80 hover:text-white bg-white/10 hover:bg-white/20 rounded-xl p-2 transition-colors outline-none">
      <svg class="w-5 h-5" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" /></svg>
     </button>
    </div>
    <form class="p-6 md:p-8 space-y-6" id="addQuestionForm" onsubmit="handleAddQuestion(event)">
     <div>
        <label for="questionType" class="block text-sm font-bold text-gray-700 dark:!text-slate-300 mb-2 transition-colors"><?= lang('GuruMapel/BankSoal.form_type') ?> <span class="text-red-500">*</span></label> 
        <select id="questionType" required class="w-full px-4 py-3.5 border-2 border-gray-200 dark:!border-slate-700 bg-gray-50 dark:!bg-slate-800 rounded-xl font-semibold text-gray-800 dark:!text-white focus:border-[<?= $color['warna_primary'] ?>] focus:ring-1 focus:ring-[<?= $color['warna_primary'] ?>] transition-colors outline-none appearance-none cursor-pointer" onchange="handleQuestionTypeChange()"> 
            <option value=""><?= lang('GuruMapel/BankSoal.form_type_ph') ?></option> 
            <option value="pg"><?= lang('GuruMapel/BankSoal.form_type_pg') ?></option> 
            <option value="isian"><?= lang('GuruMapel/BankSoal.form_type_short') ?></option> 
            <option value="esai"><?= lang('GuruMapel/BankSoal.form_type_essay') ?></option> 
        </select>
     </div>
     <div>
        <label for="questionText" class="block text-sm font-bold text-gray-700 dark:!text-slate-300 mb-2 transition-colors"><?= lang('GuruMapel/BankSoal.form_text') ?> <span class="text-red-500">*</span></label> 
        <textarea id="questionText" required rows="4" class="w-full px-4 py-3 border-2 border-gray-200 dark:!border-slate-700 bg-white dark:!bg-slate-900 rounded-xl font-medium text-gray-800 dark:!text-slate-200 focus:border-[<?= $color['warna_primary'] ?>] transition-colors resize-none outline-none shadow-inner" placeholder="<?= lang('GuruMapel/BankSoal.form_text_ph') ?>"></textarea>
     </div>
     <div id="optionsSection" style="display: none;" class="bg-gray-50 dark:!bg-slate-800/50 p-5 rounded-2xl border border-gray-200 dark:!border-slate-700 transition-colors">
        <label class="block text-sm font-bold text-gray-700 dark:!text-slate-300 mb-4 transition-colors"><?= lang('GuruMapel/BankSoal.form_options') ?> <span class="text-red-500">*</span></label>
      <div class="space-y-3">
       <div class="flex gap-3">
           <span class="flex-shrink-0 w-12 h-12 rounded-xl bg-blue-100 dark:!bg-blue-900/30 flex items-center justify-center font-black text-blue-700 dark:!text-blue-400 border border-blue-200 dark:!border-blue-800/50 transition-colors">A</span> 
           <input type="text" id="optionA" class="flex-1 px-4 py-3 border-2 border-gray-200 dark:!border-slate-700 bg-white dark:!bg-slate-900 rounded-xl font-medium text-gray-800 dark:!text-slate-200 focus:border-[<?= $color['warna_primary'] ?>] transition-colors outline-none" placeholder="<?= lang('GuruMapel/BankSoal.form_opt_ph') ?> A">
       </div>
       <div class="flex gap-3">
           <span class="flex-shrink-0 w-12 h-12 rounded-xl bg-purple-100 dark:!bg-purple-900/30 flex items-center justify-center font-black text-purple-700 dark:!text-purple-400 border border-purple-200 dark:!border-purple-800/50 transition-colors">B</span> 
           <input type="text" id="optionB" class="flex-1 px-4 py-3 border-2 border-gray-200 dark:!border-slate-700 bg-white dark:!bg-slate-900 rounded-xl font-medium text-gray-800 dark:!text-slate-200 focus:border-[<?= $color['warna_primary'] ?>] transition-colors outline-none" placeholder="<?= lang('GuruMapel/BankSoal.form_opt_ph') ?> B">
       </div>
       <div class="flex gap-3">
           <span class="flex-shrink-0 w-12 h-12 rounded-xl bg-emerald-100 dark:!bg-emerald-900/30 flex items-center justify-center font-black text-emerald-700 dark:!text-emerald-400 border border-emerald-200 dark:!border-emerald-800/50 transition-colors">C</span> 
           <input type="text" id="optionC" class="flex-1 px-4 py-3 border-2 border-gray-200 dark:!border-slate-700 bg-white dark:!bg-slate-900 rounded-xl font-medium text-gray-800 dark:!text-slate-200 focus:border-[<?= $color['warna_primary'] ?>] transition-colors outline-none" placeholder="<?= lang('GuruMapel/BankSoal.form_opt_ph') ?> C">
       </div>
       <div class="flex gap-3">
           <span class="flex-shrink-0 w-12 h-12 rounded-xl bg-amber-100 dark:!bg-amber-900/30 flex items-center justify-center font-black text-amber-700 dark:!text-amber-400 border border-amber-200 dark:!border-amber-800/50 transition-colors">D</span> 
           <input type="text" id="optionD" class="flex-1 px-4 py-3 border-2 border-gray-200 dark:!border-slate-700 bg-white dark:!bg-slate-900 rounded-xl font-medium text-gray-800 dark:!text-slate-200 focus:border-[<?= $color['warna_primary'] ?>] transition-colors outline-none" placeholder="<?= lang('GuruMapel/BankSoal.form_opt_ph') ?> D">
       </div>
      </div>
     </div>
     <div id="answerKeyPGSection" style="display: none;">
         <label for="answerKeyPG" class="block text-sm font-bold text-gray-700 dark:!text-slate-300 mb-2 transition-colors"><?= lang('GuruMapel/BankSoal.form_key') ?> <span class="text-red-500">*</span></label> 
         <select id="answerKeyPG" class="w-full px-4 py-3.5 border-2 border-gray-200 dark:!border-slate-700 bg-gray-50 dark:!bg-slate-800 rounded-xl font-semibold text-gray-800 dark:!text-white focus:border-[<?= $color['warna_primary'] ?>] transition-colors outline-none appearance-none cursor-pointer"> 
             <option value=""><?= lang('GuruMapel/BankSoal.form_key_ph') ?></option> 
             <option value="A">A</option> 
             <option value="B">B</option> 
             <option value="C">C</option> 
             <option value="D">D</option> 
         </select>
     </div>
     <div id="answerKeyTextSection" style="display: none;">
         <label for="answerKeyText" class="block text-sm font-bold text-gray-700 dark:!text-slate-300 mb-2 transition-colors"><?= lang('GuruMapel/BankSoal.form_key') ?> <span class="text-red-500">*</span></label> 
         <textarea id="answerKeyText" rows="3" class="w-full px-4 py-3 border-2 border-gray-200 dark:!border-slate-700 bg-white dark:!bg-slate-900 rounded-xl font-medium text-gray-800 dark:!text-slate-200 focus:border-[<?= $color['warna_primary'] ?>] transition-colors resize-none outline-none shadow-inner" placeholder="<?= lang('GuruMapel/BankSoal.form_key_text_ph') ?>"></textarea>
     </div>
     <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
      <div>
          <label for="difficulty" class="block text-sm font-bold text-gray-700 dark:!text-slate-300 mb-2 transition-colors"><?= lang('GuruMapel/BankSoal.form_diff') ?> <span class="text-red-500">*</span></label> 
          <select id="difficulty" required class="w-full px-4 py-3.5 border-2 border-gray-200 dark:!border-slate-700 bg-gray-50 dark:!bg-slate-800 rounded-xl font-semibold text-gray-800 dark:!text-white focus:border-[<?= $color['warna_primary'] ?>] transition-colors outline-none appearance-none cursor-pointer"> 
              <option value=""><?= lang('GuruMapel/BankSoal.form_diff_ph') ?></option> 
              <option value="mudah"><?= lang('GuruMapel/BankSoal.filter_easy') ?></option> 
              <option value="sedang"><?= lang('GuruMapel/BankSoal.filter_medium') ?></option> 
              <option value="sulit"><?= lang('GuruMapel/BankSoal.filter_hard') ?></option> 
          </select>
      </div>
      <div>
          <label for="kd" class="block text-sm font-bold text-gray-700 dark:!text-slate-300 mb-2 transition-colors"><?= lang('GuruMapel/BankSoal.form_kd') ?> <span class="text-red-500">*</span></label> 
          <input type="text" id="kd" required class="w-full px-4 py-3.5 border-2 border-gray-200 dark:!border-slate-700 bg-white dark:!bg-slate-900 rounded-xl font-semibold text-gray-800 dark:!text-white focus:border-[<?= $color['warna_primary'] ?>] transition-colors outline-none" placeholder="<?= lang('GuruMapel/BankSoal.form_kd_ph') ?>">
      </div>
     </div>
     <div>
         <label for="explanation" class="block text-sm font-bold text-gray-700 dark:!text-slate-300 mb-2 transition-colors"><?= lang('GuruMapel/BankSoal.form_exp') ?></label> 
         <textarea id="explanation" rows="3" class="w-full px-4 py-3 border-2 border-gray-200 dark:!border-slate-700 bg-white dark:!bg-slate-900 rounded-xl font-medium text-gray-800 dark:!text-slate-200 focus:border-[<?= $color['warna_primary'] ?>] transition-colors resize-none outline-none shadow-inner" placeholder="<?= lang('GuruMapel/BankSoal.form_exp_ph') ?>"></textarea>
     </div>
     
     <div class="flex flex-col-reverse sm:flex-row gap-3 pt-5 mt-2 border-t border-gray-100 dark:!border-slate-700 transition-colors">
         <button type="button" class="w-full sm:w-auto px-6 py-3 bg-white dark:!bg-slate-800 border-2 border-gray-200 dark:!border-slate-700 text-gray-700 dark:!text-slate-300 font-bold rounded-xl hover:bg-gray-50 dark:hover:!bg-slate-700 transition-colors outline-none" onclick="closeAddQuestionModal()"> <?= lang('GuruMapel/BankSoal.btn_cancel') ?> </button>
         <button type="submit" class="btn-primary w-full sm:flex-1 flex justify-center items-center gap-2 bg-[<?= $color['warna_primary'] ?>] text-white font-bold px-6 py-3 rounded-xl hover:brightness-110 shadow-lg shadow-[<?= $color['warna_primary'] ?>]/30 transition-all outline-none">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" /></svg> <?= lang('GuruMapel/BankSoal.btn_save_q') ?> 
         </button> 
     </div>
    </form>
   </div>
  </div>

  <div id="addPaketModal" class="modal-overlay fixed inset-0 z-[99999] hidden items-center justify-center p-4 bg-gray-900/70 backdrop-blur-sm transition-all overflow-y-auto" onclick="closeAddPaketModal(event)">
   <div class="relative w-full max-w-lg bg-white dark:!bg-slate-900 rounded-3xl shadow-2xl transition-colors border border-transparent dark:!border-slate-800 transform scale-95 opacity-0 animate-in overflow-hidden duration-300 mx-auto" onclick="event.stopPropagation()">
    <div class="bg-gradient-to-r from-[<?= $color['warna_primary'] ?>]/90 to-[<?= $color['warna_primary'] ?>] p-6 md:px-8 py-5 flex items-center justify-between">
     <h2 class="text-xl font-black text-white uppercase tracking-widest"><?= lang('GuruMapel/BankSoal.modal_packet_title') ?></h2>
     <button onclick="closeAddPaketModal()" class="text-white/80 hover:text-white bg-white/10 hover:bg-white/20 rounded-xl p-2 transition-colors outline-none">
      <svg class="w-5 h-5" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" /></svg>
     </button>
    </div>
    <form class="p-6 md:p-8 space-y-6" id="addPaketForm" onsubmit="handleAddPaket(event)">
     <div class="bg-blue-50 dark:!bg-blue-900/20 border border-blue-200 dark:!border-blue-800/50 p-4 rounded-xl transition-colors">
        <p class="text-sm text-blue-800 dark:!text-blue-400 font-medium transition-colors"><?= lang('GuruMapel/BankSoal.packet_info') ?> <strong id="paketSoalCount" class="text-xl font-black text-blue-900 dark:!text-blue-300 mx-1 transition-colors">0</strong> <?= lang('GuruMapel/BankSoal.packet_info_2') ?></p>
     </div>
     <div>
        <label class="block text-sm font-bold text-gray-700 dark:!text-slate-300 mb-2 transition-colors"><?= lang('GuruMapel/BankSoal.packet_name') ?> <span class="text-red-500">*</span></label> 
        <input type="text" id="namaPaket" required class="w-full px-4 py-3.5 border-2 border-gray-200 dark:!border-slate-700 bg-white dark:!bg-slate-900 rounded-xl font-semibold text-gray-800 dark:!text-white focus:border-[<?= $color['warna_primary'] ?>] transition-colors outline-none" placeholder="<?= lang('GuruMapel/BankSoal.packet_name_ph') ?>">
     </div>
     <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
         <div>
             <label class="block text-sm font-bold text-gray-700 dark:!text-slate-300 mb-2 transition-colors"><?= lang('GuruMapel/BankSoal.packet_date') ?> <span class="text-red-500">*</span></label> 
             <input type="date" id="tanggalPaket" required class="w-full px-4 py-3.5 border-2 border-gray-200 dark:!border-slate-700 bg-white dark:!bg-slate-900 rounded-xl font-semibold text-gray-800 dark:!text-white focus:border-[<?= $color['warna_primary'] ?>] transition-colors outline-none">
         </div>
         <div>
             <label class="block text-sm font-bold text-gray-700 dark:!text-slate-300 mb-2 transition-colors"><?= lang('GuruMapel/BankSoal.packet_target') ?> <span class="text-red-500">*</span></label> 
             <input type="text" id="kelasTarget" required class="w-full px-4 py-3.5 border-2 border-gray-200 dark:!border-slate-700 bg-white dark:!bg-slate-900 rounded-xl font-semibold text-gray-800 dark:!text-white focus:border-[<?= $color['warna_primary'] ?>] transition-colors outline-none" placeholder="<?= lang('GuruMapel/BankSoal.packet_target_ph') ?>">
         </div>
     </div>
     
     <div class="flex flex-col-reverse sm:flex-row gap-3 pt-5 mt-2 border-t border-gray-100 dark:!border-slate-700 transition-colors">
        <button type="button" class="w-full sm:w-auto px-6 py-3 bg-white dark:!bg-slate-800 border-2 border-gray-200 dark:!border-slate-700 text-gray-700 dark:!text-slate-300 font-bold rounded-xl hover:bg-gray-50 dark:hover:!bg-slate-700 transition-colors outline-none" onclick="closeAddPaketModal()"> <?= lang('GuruMapel/BankSoal.btn_cancel') ?> </button>
        <button type="submit" class="btn-primary w-full sm:flex-1 flex justify-center items-center gap-2 bg-[<?= $color['warna_primary'] ?>] text-white font-bold px-6 py-3 rounded-xl hover:brightness-110 shadow-lg shadow-[<?= $color['warna_primary'] ?>]/30 transition-all outline-none">
         <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" /></svg> <?= lang('GuruMapel/BankSoal.btn_save_packet') ?> 
        </button> 
     </div>
    </form>
   </div>
  </div>

  <div id="toast" class="fixed top-4 right-4 z-[1000000] flex items-center gap-3 px-5 py-4 bg-white dark:!bg-slate-800 text-gray-800 dark:!text-white border-l-4 rounded-xl shadow-2xl transition-all duration-300 transform translate-x-full opacity-0">
   <div class="w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0 transition-colors">
      <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
   </div>
   <span class="font-bold text-sm tracking-wide pr-2" id="toastMessage"><?= lang('GuruMapel/BankSoal.toast_success') ?></span>
  </div>
  
  <div id="detailPaketModal" class="modal-overlay fixed inset-0 z-[99999] hidden items-center justify-center p-4 bg-gray-900/70 backdrop-blur-sm transition-all overflow-y-auto" onclick="closeDetailPaketModal(event)">
   <div class="relative w-full max-w-4xl bg-white dark:!bg-slate-900 rounded-3xl shadow-2xl transition-colors border border-transparent dark:!border-slate-800 transform scale-95 opacity-0 animate-in overflow-hidden duration-300 mx-auto my-8" onclick="event.stopPropagation()">
    <div class="p-6 md:p-8 border-b border-gray-100 dark:!border-slate-700 flex flex-col sm:flex-row sm:items-center justify-between bg-gray-50 dark:!bg-slate-800 rounded-t-3xl gap-4 transition-colors">
     <h2 class="text-xl font-black text-gray-900 dark:!text-white transition-colors" id="detailPaketTitle"><?= lang('GuruMapel/BankSoal.modal_detail_title') ?></h2>
     <div class="flex items-center gap-3">
      <button id="btnPrintPaket" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl font-bold flex items-center gap-2 shadow-lg shadow-blue-600/30 transition-all outline-none">
       <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg> <?= lang('GuruMapel/BankSoal.btn_print') ?>
      </button>
      <button onclick="closeDetailPaketModal()" class="p-2 hover:bg-gray-200 dark:hover:!bg-slate-700 rounded-xl text-gray-600 dark:!text-slate-400 transition-colors outline-none">
       <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" /></svg>
      </button>
     </div>
    </div>
    <div class="p-6 md:p-8 overflow-y-auto max-h-[70vh] custom-scrollbar">
     <div class="flex flex-wrap gap-4 mb-8 text-sm font-bold text-blue-900 dark:!text-blue-300 bg-blue-50 dark:!bg-blue-900/20 p-5 rounded-xl border border-blue-200 dark:!border-blue-800/50 transition-colors" id="detailPaketInfo">
        </div>
     <h3 class="font-black text-gray-800 dark:!text-slate-200 mb-5 border-b border-gray-100 dark:!border-slate-700 pb-3 transition-colors uppercase tracking-widest text-sm"><?= lang('GuruMapel/BankSoal.detail_list_title') ?></h3>
     <div id="detailPaketQuestions" class="space-y-4">
        </div>
    </div>
   </div>
  </div>
  
  <div id="importModal" class="modal-overlay fixed inset-0 z-[99999] hidden items-center justify-center p-4 bg-gray-900/70 backdrop-blur-sm transition-all overflow-y-auto" onclick="closeImportModal(event)">
   <div class="relative w-full max-w-lg bg-white dark:!bg-slate-900 rounded-3xl shadow-2xl transition-colors border border-transparent dark:!border-slate-800 transform scale-95 opacity-0 animate-in overflow-hidden duration-300 mx-auto" onclick="event.stopPropagation()">
    <div class="bg-gradient-to-r from-[<?= $color['warna_primary'] ?>]/90 to-[<?= $color['warna_primary'] ?>] p-6 md:px-8 py-5 flex items-center justify-between">
     <h2 class="text-xl font-black text-white uppercase tracking-widest"><?= lang('GuruMapel/BankSoal.modal_import_title') ?></h2>
     <button onclick="closeImportModal()" class="text-white/80 hover:text-white bg-white/10 hover:bg-white/20 rounded-xl p-2 transition-colors outline-none">
      <svg class="w-5 h-5" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" /></svg>
     </button>
    </div>
    <form class="p-6 md:p-8 space-y-6" id="importForm" onsubmit="handleImport(event)">
     <div class="bg-blue-50 dark:!bg-blue-900/20 border border-blue-200 dark:!border-blue-800/50 p-5 rounded-xl mb-2 transition-colors">
        <p class="text-sm text-blue-800 dark:!text-blue-400 font-medium mb-3 transition-colors leading-relaxed"><?= lang('GuruMapel/BankSoal.import_info') ?></p>
        <a href="<?= base_url('guru/bank-soal/template') ?>" class="inline-flex items-center gap-1.5 text-sm font-black text-blue-600 dark:!text-blue-400 hover:text-blue-800 dark:hover:!text-blue-300 underline transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg> <?= lang('GuruMapel/BankSoal.import_download') ?>
        </a>
     </div>
     <div>
        <label class="block text-sm font-bold text-gray-700 dark:!text-slate-300 mb-2 transition-colors"><?= lang('GuruMapel/BankSoal.import_label') ?> <span class="text-red-500">*</span></label> 
        <input type="file" id="fileImport" accept=".csv" required class="w-full px-4 py-3 border-2 border-gray-200 dark:!border-slate-700 bg-gray-50 dark:!bg-slate-800 rounded-xl font-semibold text-gray-800 dark:!text-white focus:border-[<?= $color['warna_primary'] ?>] transition-colors outline-none file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-[<?= $color['warna_primary'] ?>]/10 file:text-[<?= $color['warna_primary'] ?>] hover:file:bg-[<?= $color['warna_primary'] ?>]/20">
     </div>
     
     <div class="flex flex-col-reverse sm:flex-row gap-3 pt-5 mt-2 border-t border-gray-100 dark:!border-slate-700 transition-colors">
        <button type="button" class="w-full sm:w-auto px-6 py-3 bg-white dark:!bg-slate-800 border-2 border-gray-200 dark:!border-slate-700 text-gray-700 dark:!text-slate-300 font-bold rounded-xl hover:bg-gray-50 dark:hover:!bg-slate-700 transition-colors outline-none" onclick="closeImportModal()"> <?= lang('GuruMapel/BankSoal.btn_cancel') ?> </button>
        <button type="submit" class="btn-primary w-full sm:flex-1 flex justify-center items-center gap-2 bg-[<?= $color['warna_primary'] ?>] text-white font-bold px-6 py-3 rounded-xl hover:brightness-110 shadow-lg shadow-[<?= $color['warna_primary'] ?>]/30 transition-all outline-none">
         <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg> <?= lang('GuruMapel/BankSoal.btn_process_import') ?> 
        </button> 
     </div>
    </form>
   </div>
  </div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    const BASE_URL = "<?= rtrim(base_url(), '/') ?>";
    const ACTIVE_MAPEL_ID = <?= $info['mapel_id'] ?>;
    const ACTIVE_TINGKAT = "<?= $info['tingkat'] ?>";
    const THEME_COLOR = "<?= $color['warna_primary'] ?>";
    const csrfTokenName = "<?= csrf_token() ?>";
    const csrfTokenHash = "<?= csrf_hash() ?>";
    
    // Kamus Javascript
    const LANG = {
        empty_search: "<?= lang('GuruMapel/BankSoal.empty_search_js') ?? 'Tidak ada soal yang cocok dengan filter/pencarian Anda.' ?>",
        empty_first: "<?= lang('GuruMapel/BankSoal.empty_state_desc') ?>",
        type_pg: "<?= lang('GuruMapel/BankSoal.filter_pg') ?>",
        type_short: "<?= lang('GuruMapel/BankSoal.filter_short') ?>",
        type_essay: "<?= lang('GuruMapel/BankSoal.filter_essay') ?>",
        diff_easy: "<?= lang('GuruMapel/BankSoal.filter_easy') ?>",
        diff_medium: "<?= lang('GuruMapel/BankSoal.filter_medium') ?>",
        diff_hard: "<?= lang('GuruMapel/BankSoal.filter_hard') ?>",
        in_use: "<?= lang('GuruMapel/BankSoal.js_in_use') ?? 'Sedang digunakan' ?>",
        status_active: "<?= lang('GuruMapel/TargetTahfidz.status_active') ?>",
        status_often: "<?= lang('GuruMapel/BankSoal.js_status_often') ?? 'Sering' ?>",
        status_locked: "<?= lang('GuruMapel/BankSoal.js_status_locked') ?? 'Terkunci' ?>",
        btn_preview: "<?= lang('GuruMapel/BankSoal.modal_preview_title') ?>",
        btn_delete: "<?= lang('GuruMapel/BankSoal.js_btn_delete') ?? 'Hapus' ?>",
        th_no: "<?= lang('GuruMapel/BankSoal.th_no') ?? 'No' ?>",
        th_snippet: "<?= lang('GuruMapel/BankSoal.js_th_snippet') ?? 'Cuplikan Soal' ?>",
        th_type: "<?= lang('GuruMapel/BankSoal.js_th_type') ?? 'Jenis' ?>",
        th_diff: "<?= lang('GuruMapel/BankSoal.js_th_diff') ?? 'Kesulitan' ?>",
        th_kd: "<?= lang('GuruMapel/BankSoal.form_kd') ?>",
        th_status: "<?= lang('GuruMapel/TargetTahfidz.th_status') ?>",
        th_action: "<?= lang('GuruMapel/TargetTahfidz.th_action') ?>",
        total_questions: "<?= lang('GuruMapel/BankSoal.js_total_questions') ?? 'Soal' ?>",
        execution: "<?= lang('GuruMapel/BankSoal.js_execution') ?? 'Pelaksanaan:' ?>",
        class: "<?= lang('GuruMapel/BankSoal.js_class') ?? 'Kelas:' ?>",
        total: "<?= lang('GuruMapel/BankSoal.js_total') ?? 'Total:' ?>",
        ans_key: "<?= lang('GuruMapel/BankSoal.form_key') ?>",
        no_exp: "<?= lang('GuruMapel/BankSoal.js_no_exp') ?? 'Tidak ada pembahasan' ?>",
        print_title: "<?= lang('GuruMapel/BankSoal.js_print_title') ?? 'LEMBAR SOAL' ?>",
        print_subject: "<?= lang('GuruMapel/BankSoal.info_subject') ?>",
        print_student: "<?= lang('GuruMapel/BankSoal.th_name') ?? 'Nama Siswa' ?>",
        print_target: "<?= lang('GuruMapel/BankSoal.packet_target') ?>",
        print_absent: "<?= lang('GuruMapel/BankSoal.js_print_absent') ?? 'No. Absen' ?>",
        print_date: "<?= lang('GuruMapel/BankSoal.form_date') ?? 'Tanggal' ?>",
        print_grade: "<?= lang('GuruMapel/BankSoal.js_print_grade') ?? 'Nilai' ?>",
        err_no_q: "<?= lang('GuruMapel/BankSoal.js_err_no_q') ?? '⚠️ Tidak ada soal yang tampil. Tambahkan atau sesuaikan filter soal terlebih dahulu.' ?>",
        saving: "<?= lang('GuruMapel/BankSoal.js_saving') ?? 'Menyimpan...' ?>",
        succ_packet: "<?= lang('GuruMapel/BankSoal.js_succ_packet') ?? '✓ Paket Soal berhasil dibuat!' ?>",
        err_server: "<?= lang('GuruMapel/BankSoal.js_err_server') ?? '⚠️ Terjadi kesalahan server' ?>",
        succ_q: "<?= lang('GuruMapel/BankSoal.js_succ_q') ?? '✓ Soal berhasil ditambahkan!' ?>",
        del_confirm: "<?= lang('GuruMapel/BankSoal.js_del_confirm') ?? 'Yakin ingin menghapus soal ini?' ?>",
        succ_del: "<?= lang('GuruMapel/BankSoal.js_succ_del') ?? '✓ Soal berhasil dihapus' ?>",
        err_del: "<?= lang('GuruMapel/BankSoal.js_err_del') ?? '⚠️ Gagal menghapus soal' ?>",
        err_not_found: "<?= lang('GuruMapel/BankSoal.js_err_not_found') ?? 'Soal dengan ID tersebut tidak ditemukan' ?>",
        preview_q: "<?= lang('GuruMapel/BankSoal.js_preview_q') ?? 'Pertanyaan:' ?>",
        preview_opts: "<?= lang('GuruMapel/BankSoal.form_options') ?>",
        preview_exp: "<?= lang('GuruMapel/BankSoal.form_exp') ?>",
        err_no_csv: "<?= lang('GuruMapel/BankSoal.js_err_no_csv') ?? '⚠️ Silakan pilih file CSV!' ?>",
        processing: "<?= lang('GuruMapel/BankSoal.js_processing') ?? 'Memproses...' ?>",
        err_conn: "<?= lang('GuruMapel/BankSoal.js_err_conn') ?? '⚠️ Terjadi kesalahan koneksi server' ?>"
    };
</script>
<script src="<?= base_url('assets/js/GuruMapel/bank-soal.js') ?>?v=<?= time() ?>"></script>
<?= $this->endSection() ?>