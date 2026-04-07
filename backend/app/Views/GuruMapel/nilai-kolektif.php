<?= $this->extend('layout/main') ?>

<?= $this->section('title') ?>
<?= lang('GuruMapel/NilaiKolektif.page_title_browser') ?> - Rapor Digital SMPIT Ad Durrah
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
    :root {
        --warna-scroll: <?= $color['warna_primary'] ?>;
    }
    ::-webkit-scrollbar {
      width: 8px;
      height: 8px;
    }
    
    ::-webkit-scrollbar-track {
      background: #f1f1f1;
    }
    
    ::-webkit-scrollbar-thumb {
      background-color: var(--warna-scroll);
      border-radius: 4px;
    }
</style>

<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="mb-6">
    <div class="flex items-center gap-2 text-sm text-gray-500 dark:text-slate-400 mb-3 transition-colors">
        <span><?= lang('GuruMapel/NilaiKolektif.breadcrumb_1') ?></span>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>
        <span class="text-[<?= $color['warna_primary'] ?>] font-medium"><?= lang('GuruMapel/NilaiKolektif.breadcrumb_2') ?></span>
    </div>
    <div>
        <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white mb-1 transition-colors"><?= lang('GuruMapel/NilaiKolektif.page_title') ?></h1>
        <p class="text-sm text-gray-600 dark:text-slate-400 transition-colors"><?= lang('GuruMapel/NilaiKolektif.page_desc') ?></p>
    </div>
</div>

<div class="mb-8 p-5 rounded-2xl bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800/50 flex gap-4">
    <div class="mt-1 flex-shrink-0">
        <div class="w-10 h-10 rounded-full bg-yellow-100 dark:bg-yellow-800/50 flex items-center justify-center text-yellow-600 dark:text-yellow-400">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
            </svg>
        </div>
    </div>
    <div>
        <h4 class="text-base font-black text-yellow-800 dark:text-yellow-400 uppercase tracking-wide mb-1"><?= lang('GuruMapel/NilaiKolektif.warning_title') ?></h4>
        <p class="text-sm text-yellow-700 dark:text-yellow-500 font-medium leading-relaxed">
            <?= lang('GuruMapel/NilaiKolektif.warning_desc_1') ?> <br />
            <?= lang('GuruMapel/NilaiKolektif.warning_desc_2') ?>
        </p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
    <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-gray-100 dark:border-slate-700 p-6 md:p-8 transition-colors relative overflow-hidden">
        <div class="absolute top-0 right-0 -mt-4 -mr-4 w-32 h-32 bg-gradient-to-br from-[<?= $color['warna_primary'] ?>] to-transparent opacity-10 dark:opacity-20 rounded-full blur-2xl"></div>

        <h2 class="text-lg font-black text-gray-800 dark:text-white mb-5 flex items-center gap-2">
            <svg class="w-6 h-6 text-[<?= $color['warna_primary'] ?>]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
            </svg>
            <?= lang('GuruMapel/NilaiKolektif.step_1_title') ?>
        </h2>

        <form id="formDownload" class="space-y-5 relative z-10">
            <div>
                <label class="block text-[11px] font-black text-gray-500 dark:text-slate-400 uppercase tracking-widest mb-2">TAHUN AJARAN</label>
                
                <select id="dl_ta" onchange="window.location.href='?ta=' + this.value" class="w-full px-4 py-3 bg-gray-50 dark:bg-slate-700/50 border-2 border-gray-200 dark:border-slate-600 text-gray-900 dark:text-white rounded-xl focus:outline-none focus:ring-2 focus:ring-[<?= $color['warna_primary'] ?>] transition-colors outline-none cursor-pointer">
                    <?php if (!empty($tahun_ajaran)): ?>
                        <?php foreach ($tahun_ajaran as $ta): ?>
                            <option value="<?= $ta['id'] ?>" <?= ($ta['id'] == $id_ta_aktif) ? 'selected' : '' ?>>
                                <?= esc($ta[$fTA]) ?> - <?= esc($ta['semester']) ?> <?= ($ta['status'] == 'Aktif') ? '(AKTIF)' : '' ?>
                            </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>

            <div>
                <label class="block text-[11px] font-black text-gray-500 dark:text-slate-400 uppercase tracking-widest mb-2"><?= lang('GuruMapel/NilaiKolektif.select_class') ?></label>
                <select id="dl_kelas" class="w-full px-4 py-3 bg-gray-50 dark:bg-slate-700/50 border-2 border-gray-200 dark:border-slate-600 text-gray-900 dark:text-white rounded-xl focus:outline-none focus:ring-2 focus:ring-[<?= $color['warna_primary'] ?>] transition-colors outline-none">
                    <option value="">-- <?= lang('GuruMapel/NilaiKolektif.opt_class_ph') ?> --</option>
                    <?php foreach ($rombels as $r): ?>
                        <option value="<?= $r['id'] ?>"><?= $r['tingkat'] ?> - <?= $r['nama_rombel'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div>
                <label class="block text-[11px] font-black text-gray-500 dark:text-slate-400 uppercase tracking-widest mb-2"><?= lang('GuruMapel/NilaiKolektif.select_subject') ?></label>
                <select id="dl_mapel" class="w-full px-4 py-3 bg-gray-50 dark:bg-slate-700/50 border-2 border-gray-200 dark:border-slate-600 text-gray-900 dark:text-white rounded-xl focus:outline-none focus:ring-2 focus:ring-[<?= $color['warna_primary'] ?>] transition-colors outline-none">
                    <option value="">-- <?= lang('GuruMapel/NilaiKolektif.opt_subject_ph') ?> --</option>
                    <?php foreach ($mapels as $m): ?>
                        <option value="<?= $m['id'] ?>"><?= $m['nama_mapel'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div>
                <label class="block text-[11px] font-black text-gray-500 dark:text-slate-400 uppercase tracking-widest mb-2"><?= lang('GuruMapel/NilaiKolektif.select_format') ?></label>
                <select id="dl_jenis" class="w-full px-4 py-3 bg-gray-50 dark:bg-slate-700/50 border-2 border-gray-200 dark:border-slate-600 text-gray-900 dark:text-white rounded-xl focus:outline-none focus:ring-2 focus:ring-[<?= $color['warna_primary'] ?>] transition-colors outline-none">
                    <option value="tengah"><?= lang('GuruMapel/NilaiKolektif.opt_format_mid') ?></option>
                    <option value="akhir"><?= lang('GuruMapel/NilaiKolektif.opt_format_final') ?></option>
                </select>
            </div>

            <button type="button" onclick="downloadTemplate()" class="w-full px-4 py-3.5 bg-[<?= $color['warna_primary'] ?>] hover:bg-[<?= $color['warna_primary'] ?>]/90 text-white font-bold rounded-xl shadow-lg flex justify-center items-center gap-2 transition-transform transform hover:-translate-y-0.5 outline-none">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <span><?= lang('GuruMapel/NilaiKolektif.btn_download_temp') ?></span>
            </button>
        </form>
    </div>

    <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-gray-100 dark:border-slate-700 p-6 md:p-8 transition-colors relative overflow-hidden h-fit">
        <div class="absolute top-0 right-0 -mt-4 -mr-4 w-32 h-32 bg-gradient-to-br from-[<?= $color['warna_secondary'] ?>] to-transparent opacity-10 dark:opacity-20 rounded-full blur-2xl"></div>

        <div>
            <h2 class="text-lg font-black text-gray-800 dark:text-white mb-2 flex items-center gap-2">
                <svg class="w-6 h-6 text-[<?= $color['warna_secondary'] ?>]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                </svg>
                <?= lang('GuruMapel/NilaiKolektif.step_2_title') ?>
            </h2>
            <p class="text-sm font-medium text-gray-500 dark:text-slate-400 mb-8">
                <?= lang('GuruMapel/NilaiKolektif.step_2_desc') ?>
            </p>
        </div>

        <form id="formImport" class="space-y-5 relative z-10" onsubmit="importExcel(event)">
            <div>
                <label class="block text-[11px] font-black text-gray-500 dark:text-slate-400 uppercase tracking-widest mb-2"><?= lang('GuruMapel/NilaiKolektif.upload_lbl') ?></label>
                <div class="relative w-full">
                    <input type="file" name="file_excel" id="file_excel" accept=".xlsx" required class="w-full px-4 py-10 bg-gray-50 dark:bg-slate-700/50 border-2 border-dashed border-gray-300 dark:border-slate-500 text-gray-900 dark:text-white rounded-xl focus:outline-none focus:border-[<?= $color['warna_primary'] ?>] transition-colors cursor-pointer text-center file:hidden">
                </div>
            </div>

            <button type="submit" id="btnImport" class="w-full px-4 py-3.5 bg-[<?= $color['warna_primary'] ?>] hover:bg-[<?= $color['warna_primary'] ?>]/90 text-white font-bold rounded-xl shadow-lg flex justify-center items-center gap-2 transition-transform transform hover:-translate-y-0.5 outline-none" style="box-shadow: 0 10px 15px -3px <?= $color['warna_primary'] ?>40;">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                </svg>
                <span><?= lang('GuruMapel/NilaiKolektif.btn_import') ?></span>
            </button>
        </form>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    const API_DOWNLOAD = "<?= base_url('guru/nilai-kolektif/download') ?>";
    const API_IMPORT = "<?= base_url('guru/nilai-kolektif/import') ?>";

    window.LANG = {
        swal_warn_title: "<?= lang('GuruMapel/NilaiKolektif.swal_warn_title') ?>",
        swal_warn_text: "<?= lang('GuruMapel/NilaiKolektif.swal_warn_text') ?>",
        swal_loading_title: "<?= lang('GuruMapel/NilaiKolektif.swal_loading_title') ?>",
        swal_loading_text: "<?= lang('GuruMapel/NilaiKolektif.swal_loading_text') ?>",
        js_processing: "<?= lang('GuruMapel/NilaiKolektif.js_processing') ?>",
        swal_succ_title: "<?= lang('GuruMapel/NilaiKolektif.swal_succ_title') ?>",
        swal_fail_title: "<?= lang('GuruMapel/NilaiKolektif.swal_fail_title') ?>",
        swal_err_title: "<?= lang('GuruMapel/NilaiKolektif.swal_err_title') ?>",
        swal_err_text: "<?= lang('GuruMapel/NilaiKolektif.swal_err_text') ?>"
    };
</script>
<script src="<?= base_url('assets/js/GuruMapel/nilai-kolektif.js') ?>?v=<?= time() ?>"></script>
<?= $this->endSection() ?>