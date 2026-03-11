<?= $this->extend('layout/main') ?>

<?= $this->section('title') ?>
  <?= lang('GuruMapel/UploadMateri.page_title') ?> - Rapor Digital
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
  :root { --warna-scroll: <?= $color['warna_primary'] ?>; }
  /* OVERRIDE CSS BAWAAN UNTUK MEMASTIKAN DARK MODE AMAN */
  .dark .form-input, .dark .textarea-field, .dark .multiselect-dropdown button {
      background-color: #0f172a !important; /* bg-slate-900 */
      color: #f1f5f9 !important; /* text-slate-100 */
      border-color: #334155 !important; /* border-slate-700 */
  }
  .dark .multiselect-options { background-color: #1e293b !important; border-color: #334155 !important; }
  .dark .multiselect-option:hover { background-color: #334155 !important; }
  .dark .dropzone { background-color: #1e293b !important; border-color: #334155 !important; }
  .dark .dropzone:hover { background-color: #334155 !important; }
  .dark .filter-button { color: #94a3b8; }
</style>
<link rel="stylesheet" href="<?= base_url('assets/css/GuruMapel/upload-materi.css') ?>">
<?= $this->endSection() ?>

<?= $this->section('content') ?>
     <div class="mb-6">
      <h1 class="text-2xl md:text-3xl font-bold mb-2 text-gray-900 dark:!text-white transition-colors" id="pageTitle"><?= lang('GuruMapel/UploadMateri.page_title') ?></h1>
      <p class="text-base text-gray-600 dark:!text-slate-400 font-medium transition-colors" id="pageSubtitle"><?= lang('GuruMapel/UploadMateri.page_subtitle') ?></p>
     </div>

     <div class="info-card bg-[<?= $color['warna_secondary'] ?>]/80 border-[<?= $color['warna_primary'] ?>]/80 dark:!bg-slate-800 dark:!border-slate-700 mb-6 p-5 rounded-2xl shadow-sm transition-colors">
      <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
       <div class="flex items-center gap-3">
        <div class="islamic-icon bg-emerald-600 flex items-center justify-center w-12 h-12 rounded-xl flex-shrink-0 transition-transform hover:scale-105">
         <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
        </div>
        <div class="min-w-0 pr-1">
         <p class="text-[10px] text-[<?= $color['warna_primary'] ?>] dark:!text-emerald-400 font-bold mb-0.5 uppercase tracking-wide transition-colors"><?= lang('GuruMapel/UploadMateri.info_subject') ?></p>
         <p class="text-sm font-black text-[<?= $color['warna_primary'] ?>] dark:!text-white truncate transition-colors"><?= esc($info['mapel']) ?></p>
        </div>
       </div>
       <div class="flex items-center gap-3">
        <div class="islamic-icon bg-purple-600 flex items-center justify-center w-12 h-12 rounded-xl flex-shrink-0 transition-transform hover:scale-105">
         <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
        </div>
        <div class="min-w-0 pr-1">
         <p class="text-[10px] text-[<?= $color['warna_primary'] ?>] dark:!text-purple-400 font-bold mb-0.5 uppercase tracking-wide transition-colors"><?= lang('GuruMapel/UploadMateri.info_class') ?></p>
         <p class="text-sm font-black text-[<?= $color['warna_primary'] ?>] dark:!text-white truncate transition-colors"><?= esc($info['kelas_gabungan']) ?></p>
        </div>
       </div>
       <div class="flex items-center gap-3">
        <div class="islamic-icon bg-blue-600 flex items-center justify-center w-12 h-12 rounded-xl flex-shrink-0 transition-transform hover:scale-105">
         <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" /></svg>
        </div>
        <div class="min-w-0 pr-1">
         <p class="text-[10px] text-[<?= $color['warna_primary'] ?>] dark:!text-blue-400 font-bold mb-0.5 uppercase tracking-wide transition-colors"><?= lang('GuruMapel/UploadMateri.info_count') ?></p>
         <p class="text-sm font-black text-[<?= $color['warna_primary'] ?>] dark:!text-white transition-colors" id="materialCount">
             <?= lang('GuruMapel/UploadMateri.js_material_count', [$info['total_materi']]) ?>
         </p>
        </div>
       </div>
       <div class="flex items-center gap-3">
        <div class="islamic-icon bg-amber-600 flex items-center justify-center w-12 h-12 rounded-xl flex-shrink-0 transition-transform hover:scale-105">
         <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
        </div>
        <div class="min-w-0 pr-1">
         <p class="text-[10px] text-[<?= $color['warna_primary'] ?>] dark:!text-amber-400 font-bold mb-0.5 uppercase tracking-wide transition-colors"><?= lang('GuruMapel/UploadMateri.info_status') ?></p>
         <span class="status-badge status-published bg-blue-50 text-blue-700 dark:!bg-blue-900/30 dark:!text-blue-400 border border-blue-200 dark:!border-blue-800/50 px-2 py-0.5 rounded text-[10px] font-black uppercase tracking-wider transition-colors"><?= lang('GuruMapel/UploadMateri.status_published') ?></span>
        </div>
       </div>
      </div>
     </div>

     <div class="card-soft bg-white dark:!bg-slate-800 border border-gray-100 dark:!border-slate-700 rounded-3xl p-6 md:p-8 shadow-sm mb-6 transition-colors">
      <h2 class="text-lg font-black text-gray-900 dark:!text-white uppercase tracking-wide mb-6 flex items-center gap-2 transition-colors">
       <svg class="w-5 h-5 text-[<?= $color['warna_primary'] ?>]" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" /></svg> <?= lang('GuruMapel/UploadMateri.form_title') ?>
      </h2>
      
      <form id="uploadForm" enctype="multipart/form-data">
       <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-5">
        <div>
            <label class="form-label block text-sm font-bold text-gray-700 dark:!text-slate-300 mb-2 transition-colors" for="materiTitle"><?= lang('GuruMapel/UploadMateri.label_title') ?></label> 
            <input type="text" id="materiTitle" class="form-input w-full p-3 rounded-xl border border-gray-300 dark:!border-slate-600 focus:ring-2 focus:ring-[<?= $color['warna_primary'] ?>]/50 focus:border-[<?= $color['warna_primary'] ?>] outline-none transition-colors" placeholder="<?= lang('GuruMapel/UploadMateri.ph_title') ?>" required>
        </div>
        <div>
            <label class="form-label block text-sm font-bold text-gray-700 dark:!text-slate-300 mb-2 transition-colors" for="materiType"><?= lang('GuruMapel/UploadMateri.label_type') ?></label> 
            <select id="materiType" class="form-input w-full p-3 rounded-xl border border-gray-300 dark:!border-slate-600 focus:ring-2 focus:ring-[<?= $color['warna_primary'] ?>]/50 focus:border-[<?= $color['warna_primary'] ?>] outline-none transition-colors appearance-none cursor-pointer" required> 
                <option value=""><?= lang('GuruMapel/UploadMateri.opt_select_type') ?></option> 
                <option value="pdf"><?= lang('GuruMapel/UploadMateri.opt_pdf') ?></option> 
                <option value="ppt"><?= lang('GuruMapel/UploadMateri.opt_ppt') ?></option> 
                <option value="video"><?= lang('GuruMapel/UploadMateri.opt_video') ?></option> 
                <option value="audio"><?= lang('GuruMapel/UploadMateri.opt_audio') ?></option> 
                <option value="link"><?= lang('GuruMapel/UploadMateri.opt_link') ?></option> 
            </select>
        </div>
       </div>
       
       <div class="mb-5">
           <label class="form-label block text-sm font-bold text-gray-700 dark:!text-slate-300 mb-2 transition-colors" for="materiDescription"><?= lang('GuruMapel/UploadMateri.label_desc') ?></label> 
           <textarea id="materiDescription" class="form-input w-full p-3 rounded-xl border border-gray-300 dark:!border-slate-600 focus:ring-2 focus:ring-[<?= $color['warna_primary'] ?>]/50 focus:border-[<?= $color['warna_primary'] ?>] outline-none transition-colors resize-y min-h-[100px]" rows="3" placeholder="<?= lang('GuruMapel/UploadMateri.ph_desc') ?>"></textarea>
       </div>
       
       <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-5">
        <div class="relative">
         <label class="form-label block text-sm font-bold text-gray-700 dark:!text-slate-300 mb-2 transition-colors" for="classSelect"><?= lang('GuruMapel/UploadMateri.label_class') ?></label>
         <div class="multiselect-dropdown relative">
             <button type="button" class="form-input w-full p-3 rounded-xl border border-gray-300 dark:!border-slate-600 focus:ring-2 focus:ring-[<?= $color['warna_primary'] ?>]/50 focus:border-[<?= $color['warna_primary'] ?>] text-left flex items-center justify-between transition-colors outline-none cursor-pointer" onclick="toggleMultiselect()"> 
                 <span id="selectedClassesText" class="text-gray-500 dark:!text-slate-400"><?= lang('GuruMapel/UploadMateri.ph_class') ?></span>
                 <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
             </button>
          <div id="multiselectOptions" class="multiselect-options absolute w-full mt-2 bg-white border border-gray-200 rounded-xl shadow-2xl z-50 hidden max-h-60 overflow-y-auto custom-scrollbar">
            <?php foreach($list_kelas as $kelas): ?>
             <label class="multiselect-option flex items-center gap-3 px-4 py-3 hover:bg-gray-50 cursor-pointer transition-colors border-b border-gray-50 dark:!border-slate-700/50 last:border-0"> 
                 <input type="checkbox" value="<?= $kelas['id'] ?>" data-name="<?= $kelas['nama'] ?>" class="w-4 h-4 rounded text-[<?= $color['warna_primary'] ?>] focus:ring-[<?= $color['warna_primary'] ?>]" onchange="updateSelectedClasses()"> 
                 <span class="font-semibold text-gray-700 dark:!text-slate-200"><?= esc($kelas['nama']) ?></span> 
             </label> 
            <?php endforeach; ?>
          </div>
         </div>
         <div id="selectedClassesTags" class="selected-classes mt-3 flex flex-wrap gap-2"></div>
        </div>
        <div>
            <label class="form-label block text-sm font-bold text-gray-700 dark:!text-slate-300 mb-2 transition-colors" for="publishDate"><?= lang('GuruMapel/UploadMateri.label_date') ?></label> 
            <input type="date" id="publishDate" class="form-input w-full p-3 rounded-xl border border-gray-300 dark:!border-slate-600 focus:ring-2 focus:ring-[<?= $color['warna_primary'] ?>]/50 focus:border-[<?= $color['warna_primary'] ?>] outline-none transition-colors" required>
        </div>
       </div>
       
       <div class="mb-6">
           <label class="form-label block text-sm font-bold text-gray-700 dark:!text-slate-300 mb-3 transition-colors"><?= lang('GuruMapel/UploadMateri.label_status') ?></label>
        <div class="flex gap-6">
            <label class="flex items-center gap-2 cursor-pointer bg-gray-50 dark:!bg-slate-900/50 border border-gray-200 dark:!border-slate-700 px-4 py-2.5 rounded-xl shadow-sm transition-colors hover:bg-gray-100 dark:hover:!bg-slate-700"> 
                <input type="radio" name="status" value="draft" class="w-4 h-4 accent-[<?= $color['warna_primary'] ?>]" required> 
                <span class="font-bold text-sm text-gray-700 dark:!text-slate-300 transition-colors"><?= lang('GuruMapel/UploadMateri.status_draft') ?></span> 
            </label> 
            <label class="flex items-center gap-2 cursor-pointer bg-gray-50 dark:!bg-slate-900/50 border border-gray-200 dark:!border-slate-700 px-4 py-2.5 rounded-xl shadow-sm transition-colors hover:bg-gray-100 dark:hover:!bg-slate-700"> 
                <input type="radio" name="status" value="published" class="w-4 h-4 accent-[<?= $color['warna_primary'] ?>]" checked required> 
                <span class="font-bold text-sm text-gray-700 dark:!text-slate-300 transition-colors"><?= lang('GuruMapel/UploadMateri.status_published') ?></span> 
            </label>
        </div>
       </div>
       
       <div class="mb-6">
        <label class="form-label block text-sm font-bold text-gray-700 dark:!text-slate-300 mb-3 transition-colors"><?= lang('GuruMapel/UploadMateri.label_file') ?></label>
        <div id="dropzone" class="dropzone border-2 border-dashed border-gray-300 dark:!border-slate-600 rounded-2xl p-8 text-center cursor-pointer transition-colors" ondrop="handleDrop(event)" ondragover="handleDragOver(event)" ondragleave="handleDragLeave(event)" onclick="document.getElementById('fileInput').click()">
         <svg class="w-16 h-16 text-gray-400 dark:!text-slate-500 mx-auto mb-3 transition-colors" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" /></svg>
         <p class="text-lg font-bold text-gray-700 dark:!text-slate-300 mb-1 transition-colors"><?= lang('GuruMapel/UploadMateri.dropzone_title') ?></p>
         <p class="text-sm text-gray-500 dark:!text-slate-400 font-medium transition-colors"><?= lang('GuruMapel/UploadMateri.dropzone_desc') ?></p>
        </div>
        <input type="file" id="fileInput" class="hidden" accept=".pdf,.ppt,.pptx,.doc,.docx,.mp4,.mp3" onchange="handleFileSelect(event)">
       </div>
       
       <div id="fileList" class="space-y-3 mb-8"></div>

       <div class="flex justify-end pt-4 border-t border-gray-100 dark:!border-slate-700 transition-colors">
           <button type="button" class="btn-primary flex items-center gap-2 px-8 py-3 rounded-xl text-white font-bold bg-[<?= $color['warna_primary'] ?>] hover:brightness-110 shadow-lg shadow-[<?= $color['warna_primary'] ?>]/20 transition-all outline-none" id="publishButton" onclick="submitMateri(event)">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg> <?= lang('GuruMapel/UploadMateri.btn_publish') ?> 
           </button>
       </div>
      </form>
     </div>

     <div class="card-soft bg-white dark:!bg-slate-800 border border-gray-100 dark:!border-slate-700 rounded-3xl p-6 md:p-8 shadow-sm mb-6 transition-colors">
      <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-6 gap-4 border-b border-gray-100 dark:!border-slate-700 pb-4 transition-colors">
       <h2 class="text-lg font-black text-gray-900 dark:!text-white uppercase tracking-wide flex items-center gap-2 transition-colors">
        <svg class="w-5 h-5 text-[<?= $color['warna_primary'] ?>]" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg> <?= lang('GuruMapel/UploadMateri.list_title') ?>
       </h2>
       <div class="flex gap-2 bg-gray-50 dark:!bg-slate-900 p-1 rounded-xl border border-gray-200 dark:!border-slate-700 transition-colors overflow-x-auto custom-scrollbar">
           <button class="filter-button px-4 py-2 text-sm font-bold rounded-lg transition-colors whitespace-nowrap [&.active]:bg-[<?= $color['warna_primary'] ?>] [&.active]:text-white active" onclick="filterMaterials(this, 'all')"> <?= lang('GuruMapel/UploadMateri.filter_all') ?> </button> 
           <button class="filter-button px-4 py-2 text-sm font-bold rounded-lg transition-colors whitespace-nowrap [&.active]:bg-[<?= $color['warna_primary'] ?>] [&.active]:text-white" onclick="filterMaterials(this, 'published')"> <?= lang('GuruMapel/UploadMateri.filter_published') ?> </button> 
           <button class="filter-button px-4 py-2 text-sm font-bold rounded-lg transition-colors whitespace-nowrap [&.active]:bg-[<?= $color['warna_primary'] ?>] [&.active]:text-white" onclick="filterMaterials(this, 'draft')"> <?= lang('GuruMapel/UploadMateri.filter_draft') ?> </button>
       </div>
      </div>
      
      <div id="materialsTableContainer" class="overflow-x-auto custom-scrollbar">
          </div>
      
      <div id="emptyState" class="empty-state text-center py-12" style="display: none;">
       <div class="empty-state-icon mx-auto w-20 h-20 bg-gray-50 dark:!bg-slate-900 rounded-full flex items-center justify-center mb-4 transition-colors">
        <svg class="w-10 h-10 text-gray-300 dark:!text-slate-600 transition-colors" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
       </div>
       <h3 class="text-xl font-black text-gray-900 dark:!text-white mb-2 transition-colors"><?= lang('GuruMapel/UploadMateri.empty_title') ?></h3>
       <p class="text-gray-600 dark:!text-slate-400 font-medium transition-colors" id="emptyStateText"><?= lang('GuruMapel/UploadMateri.empty_desc') ?></p>
      </div>
     </div>
<?= $this->endSection() ?>

<?= $this->section('modals') ?>
<div id="toast" class="fixed top-4 right-4 z-[1000000] flex items-center gap-3 px-5 py-4 bg-white dark:!bg-slate-800 text-gray-800 dark:!text-white border-l-4 rounded-xl shadow-2xl transition-all duration-300 transform translate-x-full opacity-0">
   <div class="w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0 transition-colors">
      <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
   </div>
   <span class="font-bold text-sm tracking-wide pr-2" id="toastMessage"><?= lang('GuruMapel/UploadMateri.toast_success') ?></span>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    const BASE_URL = "<?= rtrim(base_url(), '/') ?>";
    const URL_GET_DATA = "<?= base_url('guru/upload-materi/get-data') ?>";
    const URL_STORE = "<?= base_url('guru/upload-materi/store') ?>";
    const URL_DELETE = "<?= base_url('guru/upload-materi/delete') ?>";
    const URL_UPDATE_STATUS = "<?= base_url('guru/upload-materi/update-status') ?>";
    const ACTIVE_MAPEL_ID = <?= $info['mapel_id'] ?>;
    const THEME_COLOR = "<?= $color['warna_primary'] ?>";
    const URL_ASSETS = "<?= base_url('uploads/materi/') ?>";
    const csrfTokenName = "<?= csrf_token() ?>";
    const csrfTokenHash = "<?= csrf_hash() ?>";

    window.LANG = {
        pub_confirm: "<?= lang('GuruMapel/UploadMateri.js_pub_confirm') ?>",
        succ_pub: "<?= lang('GuruMapel/UploadMateri.js_succ_pub') ?>",
        err_pub: "<?= lang('GuruMapel/UploadMateri.js_err_pub') ?>",
        err_server: "<?= lang('GuruMapel/UploadMateri.js_err_server') ?>",
        material_count: "<?= lang('GuruMapel/UploadMateri.js_material_count') ?>", 
        status_pub: "<?= lang('GuruMapel/UploadMateri.status_published') ?>",
        status_draft: "<?= lang('GuruMapel/UploadMateri.status_draft') ?>",
        btn_pub_now: "<?= lang('GuruMapel/UploadMateri.js_btn_pub_now') ?>",
        btn_download: "<?= lang('GuruMapel/UploadMateri.js_btn_download') ?>",
        btn_delete: "<?= lang('GuruMapel/UploadMateri.js_btn_delete') ?>",
        err_size: "<?= lang('GuruMapel/UploadMateri.js_err_size') ?>",
        sel_class: "<?= lang('GuruMapel/UploadMateri.ph_class') ?>",
        class_selected: "<?= lang('GuruMapel/UploadMateri.js_class_selected') ?>",
        err_req: "<?= lang('GuruMapel/UploadMateri.js_err_req') ?>",
        err_no_class: "<?= lang('GuruMapel/UploadMateri.js_err_no_class') ?>",
        err_no_file: "<?= lang('GuruMapel/UploadMateri.js_err_no_file') ?>",
        uploading: "<?= lang('GuruMapel/UploadMateri.js_uploading') ?>",
        succ_upload: "<?= lang('GuruMapel/UploadMateri.js_succ_upload') ?>",
        err_upload: "<?= lang('GuruMapel/UploadMateri.js_err_upload') ?>",
        err_conn: "<?= lang('GuruMapel/UploadMateri.js_err_conn') ?>",
        del_confirm: "<?= lang('GuruMapel/UploadMateri.js_del_confirm') ?>",
        succ_del: "<?= lang('GuruMapel/UploadMateri.js_succ_del') ?>",
        months: [
            "<?= lang('GuruMapel/UploadMateri.month_1') ?>", "<?= lang('GuruMapel/UploadMateri.month_2') ?>",
            "<?= lang('GuruMapel/UploadMateri.month_3') ?>", "<?= lang('GuruMapel/UploadMateri.month_4') ?>",
            "<?= lang('GuruMapel/UploadMateri.month_5') ?>", "<?= lang('GuruMapel/UploadMateri.month_6') ?>",
            "<?= lang('GuruMapel/UploadMateri.month_7') ?>", "<?= lang('GuruMapel/UploadMateri.month_8') ?>",
            "<?= lang('GuruMapel/UploadMateri.month_9') ?>", "<?= lang('GuruMapel/UploadMateri.month_10') ?>",
            "<?= lang('GuruMapel/UploadMateri.month_11') ?>", "<?= lang('GuruMapel/UploadMateri.month_12') ?>"
        ]
    };
</script>
<script src="<?= base_url('assets/js/GuruMapel/upload-materi.js') ?>?v=<?= time() ?>"></script>
<?= $this->endSection() ?>