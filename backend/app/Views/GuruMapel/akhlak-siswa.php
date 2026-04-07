<?= $this->extend('layout/main') ?>

<?= $this->section('title') ?>
  <?= lang('GuruMapel/Akhlak.page_title') ?> - Rapor Digital
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
  :root { --warna-scroll: <?= $color['warna_primary'] ?>; }
</style>
<link rel="stylesheet" href="<?= base_url('assets/css/GuruMapel/akhlak-siswa.css') ?>">
<?= $this->endSection() ?>

<?= $this->section('content') ?>
     <div class="mb-6 flex flex-col md:flex-row md:items-end justify-between gap-4 transition-colors">
      <div>
       <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white mb-2 transition-colors" id="pageTitle"> <?= lang('GuruMapel/Akhlak.page_title') ?></h1>
       <p class="text-base text-gray-600 dark:text-slate-400 font-medium transition-colors"><?= lang('GuruMapel/Akhlak.page_subtitle') ?></p>
      </div>
      
      <div class="w-full md:w-1/3">
        <label class="block text-sm font-bold text-gray-700 dark:text-slate-300 mb-2 transition-colors"><?= lang('GuruMapel/Akhlak.select_student') ?>:</label>
        <select id="selectSiswa" class="w-full p-3 border-2 border-[<?= $color['warna_primary'] ?>]/50 dark:border-slate-600 rounded-xl bg-white dark:bg-slate-800 focus:ring-2 focus:ring-[<?= $color['warna_primary'] ?>] font-semibold text-gray-800 dark:text-white transition-colors outline-none cursor-pointer shadow-sm" onchange="changeSiswa(this.value)">
            <option value="">-- <?= lang('GuruMapel/Akhlak.option_select') ?> --</option>
            <?php foreach($siswas as $s): ?>
                <option value="<?= $s['id'] ?>"><?= esc($s['nis']) ?> - <?= esc($s['nama_lengkap']) ?></option>
            <?php endforeach; ?>
        </select>
      </div>
     </div>

     <div id="mainArea" class="hidden">
         <div class="info-card border-[<?= $color['warna_primary'] ?>]/80 dark:border-slate-600 bg-[<?= $color['warna_secondary'] ?>]/80 dark:bg-slate-800/80 mb-6 p-4 rounded-xl shadow-sm transition-colors">
          <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
           <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-lg bg-emerald-600 flex items-center justify-center flex-shrink-0 shadow-sm">
             <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
            </div>
            <div class="overflow-hidden">
             <p class="text-[10px] text-[<?= $color['warna_primary'] ?>] dark:text-slate-400 font-bold mb-0.5 uppercase tracking-wider transition-colors"><?= lang('GuruMapel/Akhlak.info_name') ?></p>
             <p class="text-sm md:text-base font-black text-[<?= $color['warna_primary'] ?>] dark:text-white truncate transition-colors" id="infoName">-</p>
            </div>
           </div>
           
           <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-lg bg-blue-600 flex items-center justify-center flex-shrink-0 shadow-sm">
             <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" /></svg>
            </div>
            <div>
             <p class="text-[10px] text-[<?= $color['warna_primary'] ?>] dark:text-slate-400 font-bold mb-0.5 uppercase tracking-wider transition-colors"><?= lang('GuruMapel/Akhlak.info_nis') ?></p>
             <p class="text-sm md:text-base font-black text-[<?= $color['warna_primary'] ?>] dark:text-white transition-colors" id="infoNis">-</p>
            </div>
           </div>

           <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-lg bg-purple-600 flex items-center justify-center flex-shrink-0 shadow-sm">
             <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
            </div>
            <div>
             <p class="text-[10px] text-[<?= $color['warna_primary'] ?>] dark:text-slate-400 font-bold mb-0.5 uppercase tracking-wider transition-colors"><?= lang('GuruMapel/Akhlak.info_class') ?></p>
             <p class="text-sm md:text-base font-black text-[<?= $color['warna_primary'] ?>] dark:text-white transition-colors"><?= esc($info['kelas']) ?></p>
            </div>
           </div>

           <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-lg bg-amber-600 flex items-center justify-center flex-shrink-0 shadow-sm">
             <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            </div>
            <div>
             <p class="text-[10px] text-[<?= $color['warna_primary'] ?>] dark:text-slate-400 font-bold mb-0.5 uppercase tracking-wider transition-colors"><?= lang('GuruMapel/Akhlak.info_stats') ?></p>
             <span class="inline-block mt-0.5" id="statBadge">-</span>
            </div>
           </div>
          </div>
         </div>

        <div class="flex items-start gap-3 bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800/50 px-5 py-4 rounded-xl mb-6 shadow-sm transition-colors">
            <div class="bg-amber-100 dark:bg-amber-900/50 p-1.5 rounded-lg flex-shrink-0 transition-colors mt-0.5">
                <svg class="w-5 h-5 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            </div>
            <p class="text-sm text-amber-900 dark:text-amber-300 font-medium leading-relaxed transition-colors">
                <strong class="font-bold uppercase tracking-wider text-xs"><?= lang('GuruMapel/Akhlak.alert_info_title') ?></strong><br>
                <?= lang('GuruMapel/Akhlak.alert_info_desc') ?>
            </p>
        </div>

        <div class="bg-white dark:bg-slate-800 rounded-3xl border border-gray-100 dark:border-slate-700 shadow-sm p-6 mb-6 transition-colors">
          
          <h2 class="text-lg font-black text-gray-900 dark:text-white uppercase tracking-wide mb-4 flex items-center gap-2 transition-colors">
           <svg class="w-5 h-5 text-[<?= $color['warna_primary'] ?>]" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" /></svg> <?= lang('GuruMapel/Akhlak.category_title') ?>
          </h2>
          <div class="flex flex-wrap gap-2 mb-8" id="categoryContainer">
            <button class="px-4 py-2 text-sm font-bold text-gray-600 dark:text-slate-300 bg-gray-50 dark:bg-slate-700/50 border border-gray-200 dark:border-slate-600 rounded-xl hover:bg-gray-100 dark:hover:bg-slate-700 transition-all outline-none" onclick="toggleCategory(this, 'Adab Guru')"><?= lang('GuruMapel/Akhlak.cat_teacher') ?></button> 
            <button class="px-4 py-2 text-sm font-bold text-gray-600 dark:text-slate-300 bg-gray-50 dark:bg-slate-700/50 border border-gray-200 dark:border-slate-600 rounded-xl hover:bg-gray-100 dark:hover:bg-slate-700 transition-all outline-none" onclick="toggleCategory(this, 'Adab Teman')"><?= lang('GuruMapel/Akhlak.cat_friend') ?></button> 
            <button class="px-4 py-2 text-sm font-bold text-gray-600 dark:text-slate-300 bg-gray-50 dark:bg-slate-700/50 border border-gray-200 dark:border-slate-600 rounded-xl hover:bg-gray-100 dark:hover:bg-slate-700 transition-all outline-none" onclick="toggleCategory(this, 'Disiplin')"><?= lang('GuruMapel/Akhlak.cat_discipline') ?></button> 
            <button class="px-4 py-2 text-sm font-bold text-gray-600 dark:text-slate-300 bg-gray-50 dark:bg-slate-700/50 border border-gray-200 dark:border-slate-600 rounded-xl hover:bg-gray-100 dark:hover:bg-slate-700 transition-all outline-none" onclick="toggleCategory(this, 'Ibadah')"><?= lang('GuruMapel/Akhlak.cat_worship') ?></button> 
            <button class="px-4 py-2 text-sm font-bold text-gray-600 dark:text-slate-300 bg-gray-50 dark:bg-slate-700/50 border border-gray-200 dark:border-slate-600 rounded-xl hover:bg-gray-100 dark:hover:bg-slate-700 transition-all outline-none" onclick="toggleCategory(this, 'Kejujuran')"><?= lang('GuruMapel/Akhlak.cat_honesty') ?></button> 
            <button class="px-4 py-2 text-sm font-bold text-gray-600 dark:text-slate-300 bg-gray-50 dark:bg-slate-700/50 border border-gray-200 dark:border-slate-600 rounded-xl hover:bg-gray-100 dark:hover:bg-slate-700 transition-all outline-none" onclick="toggleCategory(this, 'Sosial')"><?= lang('GuruMapel/Akhlak.cat_social') ?></button>
          </div>

          <h2 class="text-lg font-black text-gray-900 dark:text-white uppercase tracking-wide mb-4 flex items-center gap-2 transition-colors border-t border-gray-100 dark:border-slate-700 pt-6">
           <svg class="w-5 h-5 text-[<?= $color['warna_primary'] ?>]" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" /></svg> <?= lang('GuruMapel/Akhlak.status_title') ?>
          </h2>
          <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-8" id="statusContainer">
            <button class="flex items-center justify-center px-4 py-3 text-sm font-bold rounded-xl border transition-all outline-none bg-emerald-50 dark:bg-emerald-900/20 text-emerald-700 dark:text-emerald-400 border-emerald-200 dark:border-emerald-800/50 hover:bg-emerald-100 dark:hover:bg-emerald-900/40" onclick="selectStatus(this, 'Sangat Baik')"><?= lang('GuruMapel/Akhlak.status_excellent') ?></button> 
            <button class="flex items-center justify-center px-4 py-3 text-sm font-bold rounded-xl border transition-all outline-none bg-blue-50 dark:bg-blue-900/20 text-blue-700 dark:text-blue-400 border-blue-200 dark:border-blue-800/50 hover:bg-blue-100 dark:hover:bg-blue-900/40" onclick="selectStatus(this, 'Baik')"><?= lang('GuruMapel/Akhlak.status_good') ?></button> 
            <button class="flex items-center justify-center px-4 py-3 text-sm font-bold rounded-xl border transition-all outline-none bg-amber-50 dark:bg-amber-900/20 text-amber-700 dark:text-amber-400 border-amber-200 dark:border-amber-800/50 hover:bg-amber-100 dark:hover:bg-amber-900/40" onclick="selectStatus(this, 'Perlu Pembinaan')"><?= lang('GuruMapel/Akhlak.status_needs_guide') ?></button> 
            <button class="flex items-center justify-center px-4 py-3 text-sm font-bold rounded-xl border transition-all outline-none bg-rose-50 dark:bg-rose-900/20 text-rose-700 dark:text-rose-400 border-rose-200 dark:border-rose-800/50 hover:bg-rose-100 dark:hover:bg-rose-900/40" onclick="selectStatus(this, 'Pembinaan Intensif')"><?= lang('GuruMapel/Akhlak.status_intensive') ?></button>
          </div>

          <h2 class="text-lg font-black text-gray-900 dark:text-white uppercase tracking-wide mb-4 flex items-center gap-2 transition-colors border-t border-gray-100 dark:border-slate-700 pt-6">
           <svg class="w-5 h-5 text-[<?= $color['warna_primary'] ?>]" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" /></svg> <?= lang('GuruMapel/Akhlak.recommendation_title') ?>
          </h2>
          <div class="flex flex-col gap-3 mb-8">
            <label class="checkbox-item flex items-center gap-3 cursor-pointer group">
                <input type="checkbox" value="Nasihat personal" class="w-5 h-5 rounded border-gray-300 dark:border-slate-600 text-[<?= $color['warna_primary'] ?>] focus:ring-[<?= $color['warna_primary'] ?>] bg-gray-50 dark:bg-slate-700 transition-colors cursor-pointer"> 
                <span class="font-semibold text-sm text-gray-700 dark:text-slate-300 group-hover:text-[<?= $color['warna_primary'] ?>] transition-colors"><?= lang('GuruMapel/Akhlak.rec_advice') ?></span>
            </label> 
            <label class="checkbox-item flex items-center gap-3 cursor-pointer group">
                <input type="checkbox" value="Pemantauan wali kelas" class="w-5 h-5 rounded border-gray-300 dark:border-slate-600 text-[<?= $color['warna_primary'] ?>] focus:ring-[<?= $color['warna_primary'] ?>] bg-gray-50 dark:bg-slate-700 transition-colors cursor-pointer"> 
                <span class="font-semibold text-sm text-gray-700 dark:text-slate-300 group-hover:text-[<?= $color['warna_primary'] ?>] transition-colors"><?= lang('GuruMapel/Akhlak.rec_monitor') ?></span>
            </label> 
            <label class="checkbox-item flex items-center gap-3 cursor-pointer group">
                <input type="checkbox" value="Komunikasi orang tua" class="w-5 h-5 rounded border-gray-300 dark:border-slate-600 text-[<?= $color['warna_primary'] ?>] focus:ring-[<?= $color['warna_primary'] ?>] bg-gray-50 dark:bg-slate-700 transition-colors cursor-pointer"> 
                <span class="font-semibold text-sm text-gray-700 dark:text-slate-300 group-hover:text-[<?= $color['warna_primary'] ?>] transition-colors"><?= lang('GuruMapel/Akhlak.rec_parents') ?></span>
            </label> 
          </div>

          <div class="bg-white dark:bg-slate-800 rounded-2xl border border-gray-200 dark:border-slate-700 overflow-hidden shadow-sm transition-colors">
              <div class="px-5 py-4 border-b border-gray-100 dark:border-slate-700 bg-gray-50/50 dark:bg-slate-700/30 transition-colors">
                  <h3 class="font-bold text-gray-800 dark:text-white text-sm uppercase tracking-wider"><?= lang('GuruMapel/Akhlak.note_title') ?></h3>
              </div>
              <div class="p-5 bg-gray-50/20 dark:bg-slate-800/50 transition-colors">
                  <textarea id="catatanText" onkeyup="updateCharCounter()" class="w-full p-4 rounded-xl text-gray-700 dark:text-white bg-white dark:bg-slate-700 border border-gray-300 dark:border-slate-600 focus:border-[<?= $color['warna_primary'] ?>] dark:focus:border-[<?= $color['warna_primary'] ?>] focus:ring-2 focus:ring-[<?= $color['warna_primary'] ?>]/20 transition-all outline-none resize-y min-h-[120px] text-sm shadow-inner" placeholder="<?= lang('GuruMapel/Akhlak.note_placeholder') ?>"></textarea>
                  <div id="charCounter" class="text-xs font-medium text-gray-400 dark:text-slate-500 mt-2 text-right transition-colors">0 <?= lang('GuruMapel/Akhlak.js_characters') ?></div>
              </div>
              <div class="px-5 py-4 bg-gray-50 dark:bg-slate-800/80 border-t border-gray-100 dark:border-slate-700 flex justify-end transition-colors">
                  <button type="button" id="btnSubmit" onclick="kirimKeWaliKelas()" class="inline-flex justify-center items-center gap-2 px-6 py-2.5 rounded-xl bg-[<?= $color['warna_primary'] ?>] hover:brightness-95 text-white font-bold shadow-lg shadow-[<?= $color['warna_primary'] ?>]/30 transition-all active:scale-95 outline-none">
                      <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5" /></svg>
                      <?= lang('GuruMapel/Akhlak.btn_submit') ?>
                  </button>
              </div>
          </div>
         </div>

         <div class="bg-white dark:bg-slate-800 rounded-3xl border border-gray-100 dark:border-slate-700 shadow-sm p-6 transition-colors">
          <div class="flex items-center justify-between mb-6">
           <h2 class="text-lg font-black text-gray-900 dark:text-white uppercase tracking-wide flex items-center gap-2 transition-colors">
            <svg class="w-5 h-5 text-[<?= $color['warna_primary'] ?>]" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg> <?= lang('GuruMapel/Akhlak.history_title') ?>
           </h2>
          </div>
          <div class="space-y-4" id="historyContainer">
           </div>
         </div>
     </div> 
     
     <div id="toast" class="fixed top-4 right-4 z-[1000000] flex items-center gap-3 px-5 py-4 bg-white dark:bg-slate-800 text-gray-800 dark:text-white border-l-4 rounded-xl shadow-2xl transition-all duration-300 transform translate-x-full opacity-0">
        <div class="w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
        </div>
        <span class="font-bold text-sm" id="toastMessage"><?= lang('GuruMapel/Akhlak.toast_success') ?>!</span>
     </div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    const URL_GET_SISWA = "<?= base_url('guru/akhlak-siswa/get-siswa') ?>";
    const URL_STORE = "<?= base_url('guru/akhlak-siswa/store') ?>";
    const ACTIVE_ROMBEL_ID = <?= $info['rombel_id'] ?? 0 ?>;
    const ACTIVE_MAPEL_ID = <?= $info['mapel_id'] ?? 0 ?>;
    const THEME_COLOR = "<?= $color['warna_primary'] ?>";
    const CURRENT_TEACHER_NAME = "<?= esc($user) ?>";
    const csrfTokenName = "<?= csrf_token() ?>";
    const csrfTokenHash = "<?= csrf_hash() ?>";

    // KAMUS JS: Akhlak Siswa
    const LANG = {
        loading: "<?= lang('GuruMapel/Akhlak.js_loading') ?? 'Memuat data...' ?>",
        not_found: "<?= lang('GuruMapel/Akhlak.js_not_found') ?? 'Siswa tidak ditemukan' ?>",
        err_load: "<?= lang('GuruMapel/Akhlak.js_err_load') ?? 'Data siswa gagal dimuat.' ?>",
        err_server: "<?= lang('GuruMapel/Akhlak.js_err_server') ?? 'Gagal terhubung ke Server.' ?>",
        notes_count: "<?= lang('GuruMapel/Akhlak.js_notes_count') ?? 'Catatan' ?>",
        empty_history: "<?= lang('GuruMapel/Akhlak.js_empty_history') ?? 'Belum ada catatan akhlak untuk siswa ini.' ?>",
        follow_up: "<?= lang('GuruMapel/Akhlak.js_follow_up') ?? 'Tindak Lanjut:' ?>",
        err_select: "<?= lang('GuruMapel/Akhlak.js_err_select') ?? 'Pilih siswa terlebih dahulu!' ?>",
        err_empty: "<?= lang('GuruMapel/Akhlak.js_err_empty') ?? 'Kategori, Status, dan Catatan wajib diisi!' ?>",
        sending: "<?= lang('GuruMapel/Akhlak.js_sending') ?? 'Mengirim...' ?>",
        succ_send: "<?= lang('GuruMapel/Akhlak.js_succ_send') ?? 'Berhasil Diteruskan ke Wali Kelas!' ?>",
        err_save: "<?= lang('GuruMapel/Akhlak.js_err_save') ?? 'Gagal menyimpan data' ?>",
        characters: "<?= lang('GuruMapel/Akhlak.js_characters') ?? 'karakter' ?>"
    };
</script>
<script src="<?= base_url('assets/js/GuruMapel/akhlak-siswa.js') ?>?v=<?= time() ?>"></script>
<?= $this->endSection() ?>