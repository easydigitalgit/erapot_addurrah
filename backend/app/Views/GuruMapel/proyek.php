<?= $this->extend('layout/main') ?>

<?= $this->section('title') ?>
  <?= lang('GuruMapel/Proyek.page_title') ?> - Rapor Digital SMPIT Ad Durrah
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
  :root {
    --warna-scroll: <?= $color['warna_primary'] ?>; 
    --warna-primary: <?= $color['warna_primary'] ?>;
    --warna-secondary: <?= $color['warna_secondary'] ?>;
  }
</style>
<link rel="stylesheet" href="<?= base_url('assets/css/GuruMapel/proyek.css') ?>">
<?= $this->endSection() ?>

<?= $this->section('content') ?>
     <div class="mb-6">
      <h1 class="text-2xl md:text-3xl font-bold b-2" id="pageTitle"><?= lang('GuruMapel/Proyek.page_title') ?></h1>
      <p class="text-base text-gray-600 font-medium" id="pageSubtitle"><?= lang('GuruMapel/Proyek.page_subtitle') ?></p>
     </div>

     <div class="info-card bg-gradient-to-br from-[var(--warna-secondary)] to-white border-l-4 border-[var(--warna-primary)] shadow-sm mb-6 p-4 rounded-xl">
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 md:gap-6">
       <div class="flex items-center gap-3">
        <div>
         <p class="text-xs text-[var(--warna-primary)] font-bold mb-0.5 uppercase tracking-wide"><?= lang('GuruMapel/Proyek.info_subject') ?></p>
         <p class="text-sm md:text-base font-black text-[var(--warna-primary)] truncate" id="infoSubject"><?= esc($info['mapel_nama']) ?></p>
        </div>
       </div>
       <div class="flex items-center gap-3">
        <div>
         <p class="text-xs text-[var(--warna-primary)] font-bold mb-0.5 uppercase tracking-wide"><?= lang('GuruMapel/Proyek.info_class') ?></p>
         <p class="text-sm md:text-base font-black text-[var(--warna-primary)]" id="infoClass"><?= esc($info['kelas_nama']) ?></p>
        </div>
       </div>
       <div class="flex items-center gap-3">
        <div class="w-12 h-12 rounded-xl bg-gradient-to-tr from-purple-500 to-purple-700 flex items-center justify-center shadow-md flex-shrink-0">
         <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" /></svg>
        </div>
        <div>
         <p class="text-xs text-[var(--warna-primary)] font-bold mb-0.5 uppercase tracking-wide"><?= lang('GuruMapel/Proyek.info_type') ?></p>
         <p class="text-sm md:text-base font-black text-[var(--warna-primary)]" id="jenisNilai"><?= lang('GuruMapel/Proyek.select_project') ?></p>
        </div>
       </div>
       <div class="flex items-center gap-3">
        <div>
         <p class="text-xs text-[var(--warna-primary)] font-bold mb-0.5 uppercase tracking-wide"><?= lang('GuruMapel/Proyek.info_students') ?></p>
         <p class="text-sm md:text-base font-black text-[var(--warna-primary)]"><?= lang('GuruMapel/Proyek.student_count', [esc($info['jml_siswa'])]) ?></p>
        </div>
       </div>
       <div class="flex items-center gap-3">
        <div class="w-12 h-12 rounded-xl bg-gradient-to-tr from-amber-500 to-amber-700 flex items-center justify-center shadow-md flex-shrink-0">
         <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
        </div>
        <div>
         <p class="text-xs text-[var(--warna-primary)] font-bold mb-0.5 uppercase tracking-wide"><?= lang('GuruMapel/Proyek.info_status') ?></p>
         <span class="badge-status status-draft" id="statusBadge">-</span>
        </div>
       </div>
      </div>
     </div>

     <div class="flex flex-col sm:flex-row justify-between items-center mb-4">
        <h2 class="text-xl font-black text-gray-900 uppercase tracking-wide"><?= lang('GuruMapel/Proyek.list_title') ?></h2>
        <button class="btn-primary bg-[var(--warna-primary)] hover:opacity-90 transition-opacity flex items-center justify-center px-4 py-2.5 rounded-lg text-white font-bold gap-2 shadow-md" onclick="openSetupModal()">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" /></svg> 
            <?= lang('GuruMapel/Proyek.btn_create_project') ?> 
        </button>
     </div>

     <div id="projectListContainer" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5 mb-8">
     </div>

     <hr class="border-gray-200 mb-8" id="dividerLine" style="display: none;">

     <div class="card-soft mb-6 bg-white shadow-sm rounded-xl p-5 border border-[var(--warna-primary)]" id="rubrikCard" style="display: none;">
      <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-4">
       <h2 class="text-lg font-black text-[var(--warna-primary)] uppercase tracking-wide flex items-center gap-2">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" /></svg> 
        <?= lang('GuruMapel/Proyek.rubric_title') ?><span id="namaProyekAktif">-</span>
       </h2>
       <div class="flex items-center gap-3">
        <div class="text-left sm:text-right">
         <p class="text-xs text-gray-500 font-bold uppercase tracking-wide"><?= lang('GuruMapel/Proyek.rubric_weight') ?></p>
         <p class="text-2xl font-black" id="totalBobot" style="color: #F59E0B;">0%</p>
        </div>
       </div>
      </div>
      <div class="space-y-3" id="rubrikContainer"></div>
      
      <div class="flex flex-col sm:flex-row gap-3 mt-5">
        <button class="btn-soft flex-1 flex items-center justify-center gap-2 py-3 bg-gray-50 hover:bg-gray-100 border border-dashed border-gray-300 rounded-lg transition-colors font-medium text-gray-700" onclick="tambahAspekRubrik()">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg> 
          <?= lang('GuruMapel/Proyek.btn_add_aspect') ?> 
        </button>
        
        <button id="btnSimpanRubrik" class="flex-1 flex items-center justify-center gap-2 py-3 bg-[var(--warna-primary)] hover:opacity-90 text-white rounded-lg transition-all font-bold disabled:opacity-50 disabled:cursor-not-allowed shadow-md" onclick="saveRubrikToDB()" disabled>
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" /></svg> 
          <?= lang('GuruMapel/Proyek.btn_save_rubric') ?>
        </button>
      </div>
     </div>
<div class="bg-white rounded-2xl shadow-lg border border-gray-200 overflow-hidden mb-6" id="nilaiTableCard" style="display: none;">
      
      <div class="p-6 bg-white border-b border-gray-200 flex flex-col md:flex-row justify-between md:items-center gap-4">
       <div>
           <h2 class="text-xl font-black text-gray-900 flex items-center gap-2">
            <svg class="w-6 h-6 text-[var(--warna-primary)]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" /></svg> 
            <?= lang('GuruMapel/Proyek.input_title') ?> <span id="labelTipeInput" class="text-[var(--warna-primary)] ml-1 font-bold"></span>
           </h2>
           <p class="text-sm text-gray-500 font-medium mt-1"><?= lang('GuruMapel/Proyek.input_subtitle') ?></p>
       </div>

       <div id="searchSiswaContainer" style="display: none;" class="w-full md:w-80 relative z-50">
           <div class="relative w-full">
               <input type="text" id="inputSearchSiswa" class="w-full pl-11 pr-4 py-3 border-2 border-gray-200 rounded-xl text-sm font-semibold focus:border-[var(--warna-primary)] focus:ring-0 outline-none shadow-sm transition-all bg-gray-50 hover:bg-white" placeholder="<?= lang('GuruMapel/Proyek.search_student_ph') ?>" onkeyup="filterSearchSiswa()" autocomplete="off">
               <svg class="w-5 h-5 absolute left-4 top-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
               
               <div id="dropdownSearchSiswa" class="absolute left-0 right-0 top-full mt-2 bg-white border border-gray-200 rounded-xl shadow-2xl max-h-60 overflow-y-auto hidden divide-y divide-gray-50" style="z-index: 9999;">
                   </div>
           </div>
       </div>
      </div>
      
      <div class="overflow-x-auto w-full relative z-10">
       <table class="nilai-table min-w-full divide-y divide-gray-200 whitespace-nowrap" id="mainNilaiTable">
        <thead class="bg-gray-50/80">
         <tr>
          <th class="px-6 py-4 text-center text-xs font-black text-gray-500 uppercase tracking-wider w-16"><?= lang('GuruMapel/Proyek.th_no') ?></th>
          <th class="px-6 py-4 text-left text-xs font-black text-gray-500 uppercase tracking-wider w-64"><?= lang('GuruMapel/Proyek.th_name') ?></th>
          <th class="px-6 py-4 text-center text-xs font-black text-gray-500 uppercase tracking-wider bg-blue-50/50" id="aspekHeaders"></th>
          <th class="px-6 py-4 text-center text-xs font-black text-gray-500 uppercase tracking-wider w-28"><?= lang('GuruMapel/Proyek.th_final_grade') ?></th>
          <th class="px-6 py-4 text-center text-xs font-black text-gray-500 uppercase tracking-wider w-24"><?= lang('GuruMapel/Proyek.th_predicate') ?></th>
          <th class="px-6 py-4 text-left text-xs font-black text-gray-500 uppercase tracking-wider min-w-[200px]"><?= lang('GuruMapel/Proyek.th_notes') ?></th>
          <th class="px-6 py-4 text-center text-xs font-black text-gray-500 uppercase tracking-wider w-24" id="thAksi"><?= lang('GuruMapel/Proyek.th_action') ?></th>
         </tr>
        </thead>
        <tbody id="nilaiTableBody" class="bg-white divide-y divide-gray-100">
        </tbody>
       </table>
      </div>
      
      <div id="emptyKelompokState" class="text-center py-16 hidden bg-gray-50">
          <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-white shadow-sm border border-gray-100 mb-5">
              <svg class="w-10 h-10 text-[var(--warna-primary)]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
          </div>
          <h3 class="text-xl font-black text-gray-800 mb-2"><?= lang('GuruMapel/Proyek.empty_group_title') ?></h3>
          <p class="text-gray-500 font-medium"><?= lang('GuruMapel/Proyek.empty_group_desc') ?></p>
      </div>

     </div>
<?= $this->endSection() ?>

<?= $this->section('modals') ?>
<div id="setupModal" class="fixed inset-0 z-[100] hidden items-center justify-center p-4 sm:p-6" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" onclick="closeSetupModal()"></div>
    
    <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-2xl flex flex-col overflow-hidden transform transition-all">
      
      <div class="bg-gradient-to-r from-[var(--warna-primary)] to-gray-800 px-6 py-5 flex items-center justify-between">
        <h2 class="text-xl font-black text-white"><?= lang('GuruMapel/Proyek.modal_setup_title') ?></h2>
        <button onclick="closeSetupModal()" class="text-white hover:bg-white/20 rounded-lg p-2 transition-colors">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
        </button>
      </div>

      <div class="p-6 overflow-y-auto max-h-[70vh] space-y-5">
        <div>
          <label class="block text-sm font-bold text-gray-700 mb-2"><?= lang('GuruMapel/Proyek.form_project_name') ?> <span class="text-red-600">*</span></label> 
          <input type="text" id="namaProyek" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[var(--warna-primary)] outline-none" placeholder="<?= lang('GuruMapel/Proyek.form_name_ph') ?>">
        </div>
        
        <div>
          <label class="block text-sm font-bold text-gray-700 mb-3"><?= lang('GuruMapel/Proyek.form_type') ?> <span class="text-red-600">*</span></label>
          <div class="flex flex-col sm:flex-row gap-4">
            <div class="flex-1 relative">
              <input type="radio" id="jenisIndividu" class="hidden peer" name="jenis" value="Individu" checked> 
              <label for="jenisIndividu" class="flex items-center justify-center gap-2 p-3 border-2 border-gray-200 rounded-lg cursor-pointer peer-checked:border-[var(--warna-primary)] peer-checked:bg-[var(--warna-primary)]/10 peer-checked:text-[var(--warna-primary)] font-bold transition-all">
                <?= lang('GuruMapel/Proyek.type_individual') ?> 
              </label>
            </div>
            <div class="flex-1 relative">
              <input type="radio" id="jenisKelompok" class="hidden peer" name="jenis" value="Kelompok"> 
              <label for="jenisKelompok" class="flex items-center justify-center gap-2 p-3 border-2 border-gray-200 rounded-lg cursor-pointer peer-checked:border-[var(--warna-primary)] peer-checked:bg-[var(--warna-primary)]/10 peer-checked:text-[var(--warna-primary)] font-bold transition-all">
                <?= lang('GuruMapel/Proyek.type_group') ?> 
              </label>
            </div>
          </div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
          <div>
            <label class="block text-sm font-bold text-gray-700 mb-2"><?= lang('GuruMapel/Proyek.form_date') ?> <span class="text-red-600">*</span></label> 
            <input type="date" id="tanggalProyek" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[var(--warna-primary)] outline-none">
          </div>
          <div>
            <label class="block text-sm font-bold text-gray-700 mb-2"><?= lang('GuruMapel/Proyek.form_kkm') ?> <span class="text-red-600">*</span></label> 
            <input type="number" id="kkmProyek" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[var(--warna-primary)] outline-none" value="75" min="0" max="100">
          </div>
        </div>
        
        <div>
          <label class="block text-sm font-bold text-gray-700 mb-2"><?= lang('GuruMapel/Proyek.form_desc') ?></label> 
          <textarea id="deskripsiProyek" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[var(--warna-primary)] outline-none" placeholder="<?= lang('GuruMapel/Proyek.form_desc_ph') ?>"></textarea>
        </div>
      </div>

      <div class="px-6 py-4 bg-gray-50 flex flex-col-reverse sm:flex-row items-center justify-end gap-3 rounded-b-2xl border-t border-gray-200">
        <button onclick="closeSetupModal()" class="w-full sm:w-auto px-4 py-2 rounded-lg font-bold text-gray-600 bg-white border border-gray-300 hover:bg-gray-100 transition-all"> <?= lang('GuruMapel/Proyek.btn_cancel') ?> </button> 
        <button onclick="simpanSetupProyek()" class="w-full sm:w-auto px-6 py-2 rounded-lg text-white font-bold bg-[var(--warna-primary)] hover:opacity-90 shadow-md transition-all">
          <?= lang('GuruMapel/Proyek.btn_save_continue') ?> 
        </button>
      </div>
      
    </div>
</div>

<div id="toast" class="fixed bottom-5 right-5 z-[200] transform translate-y-10 opacity-0 transition-all duration-300 bg-gray-900 text-white px-6 py-3 rounded-lg shadow-xl flex items-center gap-3">
   <svg class="w-6 h-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
   <span class="font-bold text-sm" id="toastMessage"><?= lang('GuruMapel/Proyek.toast_success') ?></span>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
  const BASE_URL = "<?= rtrim(base_url(), '/') ?>";
  const ACTIVE_ROMBEL_ID = <?= $info['rombel_id'] ?>;
  const ACTIVE_MAPEL_ID = <?= $info['mapel_id'] ?>;
  const csrfTokenName = "<?= csrf_token() ?>";
  const csrfTokenHash = "<?= csrf_hash() ?>";
  const initialProyekList = <?php echo (isset($list_proyek) && !empty($list_proyek)) ? json_encode($list_proyek) : '[]'; ?>;
  window.LANG = {
      no_project_title: "<?= lang('GuruMapel/Proyek.js_no_project_title') ?: 'Belum Ada Proyek' ?>",
      no_project_desc: "<?= lang('GuruMapel/Proyek.js_no_project_desc') ?: 'Silakan klik \"Buat Proyek Baru\" untuk memulai.' ?>",
      no_desc: "<?= lang('GuruMapel/Proyek.js_no_desc') ?: 'Tidak ada deskripsi.' ?>",
      individual: "<?= lang('GuruMapel/Proyek.js_individual') ?: 'Proyek Individu' ?>",
      group: "<?= lang('GuruMapel/Proyek.js_group') ?: 'Proyek Kelompok' ?>",
      draft: "<?= lang('GuruMapel/Proyek.js_draft') ?: 'Draft' ?>",
      loading_rubric: "<?= lang('GuruMapel/Proyek.js_loading_rubric') ?: 'Memuat rubrik...' ?>",
      err_get_rubric: "<?= lang('GuruMapel/Proyek.js_err_get_rubric') ?: '⚠️ Gagal mengambil data rubrik!' ?>",
      saving: "<?= lang('GuruMapel/Proyek.js_saving') ?: 'Menyimpan...' ?>",
      err_field_req: "<?= lang('GuruMapel/Proyek.js_err_field_req') ?: '⚠️ Lengkapi field yang wajib diisi!' ?>",
      succ_saved: "<?= lang('GuruMapel/Proyek.js_succ_saved') ?: '✓ Berhasil disimpan!' ?>",
      fail_prefix: "<?= lang('GuruMapel/Proyek.js_fail_prefix') ?: '⚠️ Gagal: ' ?>",
      err_conn: "<?= lang('GuruMapel/Proyek.js_err_conn') ?: '⚠️ Kesalahan koneksi server!' ?>",
      save_btn: "<?= lang('GuruMapel/Proyek.btn_save_continue') ?: 'Simpan & Lanjutkan' ?>",
      aspect_label: "<?= lang('GuruMapel/Proyek.js_aspect_label') ?: 'Aspek Penilaian' ?>",
      weight_label: "<?= lang('GuruMapel/Proyek.js_weight_label') ?: 'Bobot (%)' ?>",
      new_aspect: "<?= lang('GuruMapel/Proyek.js_new_aspect') ?: 'Aspek Baru' ?>",
      del_aspect_conf: "<?= lang('GuruMapel/Proyek.js_del_aspect_conf') ?: 'Hapus aspek ini?' ?>",
      err_del_aspect: "<?= lang('GuruMapel/Proyek.js_err_del_aspect') ?: '⚠️ Gagal menghapus aspek!' ?>",
      save_rubric: "<?= lang('GuruMapel/Proyek.btn_save_rubric') ?: 'Simpan Rubrik' ?>",
      succ_rubric: "<?= lang('GuruMapel/Proyek.js_succ_rubric') ?: '✓ Rubrik berhasil disimpan!' ?>",
      err_server: "<?= lang('GuruMapel/Proyek.js_err_server') ?: '⚠️ Terjadi kesalahan server' ?>",
      not_found: "<?= lang('GuruMapel/Proyek.js_not_found') ?: 'Siswa tidak ditemukan (atau sudah ditambah)' ?>",
      add_btn: "<?= lang('GuruMapel/Proyek.js_add_btn') ?: '+ Tambah' ?>",
      kick_conf: "<?= lang('GuruMapel/Proyek.js_kick_conf') ?: 'Keluarkan siswa ini dan hapus nilainya permanen?' ?>",
      succ_kick: "<?= lang('GuruMapel/Proyek.js_succ_kick') ?: '✓ Siswa dikeluarkan.' ?>",
      err_kick: "<?= lang('GuruMapel/Proyek.js_err_kick') ?: '⚠️ Gagal mengeluarkan siswa.' ?>",
      succ_add_stu: "<?= lang('GuruMapel/Proyek.js_succ_add_stu') ?: ' berhasil ditambahkan!' ?>",
      succ_save_stu: "<?= lang('GuruMapel/Proyek.js_succ_save_stu') ?: ' berhasil disimpan!' ?>",
      err_save_stu: "<?= lang('GuruMapel/Proyek.js_err_save_stu') ?: '⚠️ Gagal menyimpan: ' ?>",
      err_server_html: "<?= lang('GuruMapel/Proyek.js_err_server_html') ?: '⚠️ Gagal! Cek Console (F12) untuk detail error.' ?>",
      err_server_conn: "<?= lang('GuruMapel/Proyek.js_err_server_conn') ?: '⚠️ Terjadi kesalahan saat menghubungi server!' ?>",
      ph_notes: "<?= lang('GuruMapel/Proyek.th_notes') ?: 'Ketik catatan opsional...' ?>",
      btn_save_grade: "<?= lang('GuruMapel/Proyek.js_btn_save_grade') ?: 'Simpan Nilai Siswa' ?>",
      btn_kick_group: "<?= lang('GuruMapel/Proyek.js_btn_kick_group') ?: 'Keluarkan dari kelompok' ?>",
      err_load_grade: "<?= lang('GuruMapel/Proyek.js_err_load_grade') ?: 'Gagal memuat nilai siswa' ?>",
      loading: "<?= lang('GuruMapel/Proyek.js_loading') ?: 'Memuat...' ?>",
      ready: "<?= lang('GuruMapel/Proyek.js_ready') ?: 'Siap Validasi' ?>",
      locked: "<?= lang('GuruMapel/Proyek.js_locked') ?: 'Terkunci' ?>",
      err_no_data_filled: "<?= lang('GuruMapel/Proyek.js_err_no_data_filled') ?: 'Belum ada nilai yang diisi. Silakan isi minimal satu nilai siswa.' ?>",
      succ_draft: "<?= lang('GuruMapel/Proyek.js_succ_draft') ?: 'Draft nilai berhasil disimpan!' ?>",
      warn_empty_val: "<?= lang('GuruMapel/Proyek.js_warn_empty_val') ?: 'Masih ada nilai siswa yang kosong. Anda yakin ingin menandai data ini sebagai Siap Validasi?' ?>",
      processing: "<?= lang('GuruMapel/Proyek.js_processing') ?: 'Memproses...' ?>",
      succ_ready: "<?= lang('GuruMapel/Proyek.js_succ_ready') ?: '✓ Nilai berhasil ditandai Siap Validasi!' ?>",
      succ_ready_alert: "<?= lang('GuruMapel/Proyek.js_succ_ready_alert') ?: 'Berhasil! Data nilai sekarang berstatus Siap Validasi.' ?>",
      lock_warning: `<?= lang('GuruMapel/Proyek.js_lock_warning') ?: '<strong>PERINGATAN PENTING:</strong><br><br>Anda akan mengunci nilai akhir ini. Setelah dikunci:<br>• Nilai tidak dapat diubah atau ditarik kembali<br>• Nilai akan masuk secara resmi ke rapor<br>• Hanya Admin yang dapat membuka kunci<br><br>Apakah Anda yakin ingin melanjutkan?' ?>`,
      locking: "<?= lang('GuruMapel/Proyek.js_locking') ?: 'Mengunci...' ?>",
      succ_lock: "<?= lang('GuruMapel/Proyek.js_succ_lock') ?: '✓ Nilai berhasil dikunci! Data telah final.' ?>",
      succ_lock_alert: "<?= lang('GuruMapel/Proyek.js_succ_lock_alert') ?: 'Berhasil! Data nilai telah terkunci secara permanen.' ?>",
      warn_cancel_ready: "<?= lang('GuruMapel/Proyek.js_warn_cancel_ready') ?: 'Apakah Anda yakin ingin menarik kembali data ini menjadi Draft? Anda akan bisa mengedit nilai lagi.' ?>",
      succ_cancel: "<?= lang('GuruMapel/Proyek.js_succ_cancel') ?: '✓ Data berhasil dikembalikan ke Draft!' ?>",
      succ_cancel_alert: "<?= lang('GuruMapel/Proyek.js_succ_cancel_alert') ?: 'Berhasil! Silakan edit kembali nilai siswa.' ?>"
  };
</script>
<script src="<?= base_url('assets/js/GuruMapel/proyek.js') ?>?v=<?= time() ?>"></script>
<?= $this->endSection() ?>