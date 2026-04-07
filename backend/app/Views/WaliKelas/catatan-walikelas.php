<?= $this->extend('layout/main') ?>

<?= $this->section('title') ?>
<?= lang('WaliKelas/Catatan.page_title') ?> - Rapor Digital
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<link rel="stylesheet" href="<?= base_url('assets/css/WaliKelas/catatan-walikelas.css') ?>">
<style>
  :root {
    --warna-primary: <?= $color['warna_primary'] ?? '#10b981' ?>;
    --warna-secondary: <?= $color['warna_secondary'] ?? '#ecfdf5' ?>;
    --warna-scroll: <?= $color['warna_primary'] ?>; 
  }

  .text-tema { color: var(--warna-primary) !important; }
  .bg-tema { background-color: var(--warna-primary) !important; }
  .bg-tema-light { background-color: color-mix(in srgb, var(--warna-primary) 12%, transparent) !important; }
  .border-tema { border-color: var(--warna-primary) !important; }
  .focus-tema:focus {
    border-color: var(--warna-primary) !important;
    box-shadow: 0 0 0 3px color-mix(in srgb, var(--warna-primary) 20%, transparent) !important;
    outline: none;
  }

  /* Tab Styles */
  .tab-active { border-bottom: 2px solid var(--warna-primary) !important; color: var(--warna-primary) !important; }
  .tab-inactive { border-bottom: 2px solid transparent; }

  /* Dark Mode Overrides */
  html.dark .text-tema { color: color-mix(in srgb, var(--warna-primary) 80%, white) !important; }
  html.dark .bg-tema-light { background-color: rgba(255, 255, 255, 0.05) !important; }
  html.dark .bg-white { background-color: #1e293b !important; border-color: #334155 !important; }
  html.dark .text-gray-800 { color: #f1f5f9 !important; }
  html.dark .text-gray-600 { color: #cbd5e1 !important; }
  html.dark .text-gray-500 { color: #94a3b8 !important; }
  html.dark .text-gray-400 { color: #64748b !important; }
  html.dark .bg-gray-50 { background-color: #0f172a !important; }
  html.dark .bg-gray-100 { background-color: #1e293b !important; }
  html.dark .border-gray-100, html.dark .border-gray-200 { border-color: #334155 !important; }
  html.dark .bg-blue-50 { background-color: rgba(59, 130, 246, 0.1) !important; }
  html.dark .bg-amber-50 { background-color: rgba(245, 158, 11, 0.1) !important; }
  html.dark .bg-emerald-50 { background-color: rgba(16, 185, 129, 0.1) !important; }
  html.dark .bg-teal-50 { background-color: rgba(20, 184, 166, 0.1) !important; }
  html.dark .border-emerald-100 { border-color: rgba(16, 185, 129, 0.2) !important; }
  html.dark .bg-red-50 { background-color: rgba(239, 68, 68, 0.1) !important; }
  html.dark .custom-scrollbar::-webkit-scrollbar-thumb { background-color: var(--warna-primary); }

  ::-webkit-scrollbar {
    width: 6px;
  }
  
  ::-webkit-scrollbar-track {
    background: #f1f1f1;
  }
  
  ::-webkit-scrollbar-thumb {
    background-color: var(--warna-scroll);
    border-radius: 3px;
  }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<main id="mainContent" class="min-h-[70vh] p-4 lg:p-6 w-full">
  <div class="flex flex-col items-center justify-center py-32">
    <div class="animate-spin rounded-full h-12 w-12 border-b-4 mb-4 border-tema"></div>
    <span class="font-medium text-lg text-tema animate-pulse"><?= lang('WaliKelas/Catatan.js_preparing_data') ?></span>
  </div>
</main>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
  const BASE_URL = '<?= rtrim(base_url(), '/') ?>';
  const serverStudents = <?= $students ?>;
  const serverCatatan = <?= $catatan ?>;

  // KAMUS BAHASA JS
  window.LANG = {
    stat_total: "<?= lang('WaliKelas/Catatan.stat_total') ?>",
    stat_positive: "<?= lang('WaliKelas/Catatan.stat_positive') ?>",
    stat_warning: "<?= lang('WaliKelas/Catatan.stat_warning') ?>",
    stat_students: "<?= lang('WaliKelas/Catatan.stat_students') ?>",
    tab_analytic: "<?= lang('WaliKelas/Catatan.tab_analytic') ?>",
    tab_board: "<?= lang('WaliKelas/Catatan.tab_board') ?>",
    chart_title: "<?= lang('WaliKelas/Catatan.chart_title') ?>",
    chart_student: "<?= lang('WaliKelas/Catatan.chart_student') ?>",
    no_data_chart: "<?= lang('WaliKelas/Catatan.no_data_chart') ?>",
    rec_title: "<?= lang('WaliKelas/Catatan.rec_title') ?>",
    rec_1_pt1: "<?= lang('WaliKelas/Catatan.rec_1_pt1') ?>",
    rec_1_pt2: "<?= lang('WaliKelas/Catatan.rec_1_pt2') ?>",
    rec_2: "<?= lang('WaliKelas/Catatan.rec_2') ?>",
    search_ph: "<?= lang('WaliKelas/Catatan.search_ph') ?>",
    filter_cat_all: "<?= lang('WaliKelas/Catatan.filter_cat_all') ?>",
    filter_cat_academic: "<?= lang('WaliKelas/Catatan.filter_cat_academic') ?>",
    filter_cat_social: "<?= lang('WaliKelas/Catatan.filter_cat_social') ?>",
    filter_cat_talent: "<?= lang('WaliKelas/Catatan.filter_cat_talent') ?>",
    filter_pri_all: "<?= lang('WaliKelas/Catatan.filter_pri_all') ?>",
    filter_pri_low: "<?= lang('WaliKelas/Catatan.filter_pri_low') ?>",
    filter_pri_med: "<?= lang('WaliKelas/Catatan.filter_pri_med') ?>",
    filter_pri_high: "<?= lang('WaliKelas/Catatan.filter_pri_high') ?>",
    no_data_filter: "<?= lang('WaliKelas/Catatan.no_data_filter') ?>",
    lbl_note: "<?= lang('WaliKelas/Catatan.lbl_note') ?>",
    lbl_followup: "<?= lang('WaliKelas/Catatan.lbl_followup') ?>",
    btn_update: "<?= lang('WaliKelas/Catatan.btn_update') ?>",
    btn_delete: "<?= lang('WaliKelas/Catatan.btn_delete') ?>",
    btn_add_new: "<?= lang('WaliKelas/Catatan.btn_add_new') ?>",
    modal_title_add: "<?= lang('WaliKelas/Catatan.modal_title_add') ?>",
    modal_title_edit: "<?= lang('WaliKelas/Catatan.modal_title_edit') ?>",
    modal_subtitle: "<?= lang('WaliKelas/Catatan.modal_subtitle') ?>",
    form_student: "<?= lang('WaliKelas/Catatan.form_student') ?>",
    form_student_ph: "<?= lang('WaliKelas/Catatan.form_student_ph') ?>",
    form_category: "<?= lang('WaliKelas/Catatan.form_category') ?>",
    form_priority: "<?= lang('WaliKelas/Catatan.form_priority') ?>",
    form_note_ph: "<?= lang('WaliKelas/Catatan.form_note_ph') ?>",
    form_followup_ph: "<?= lang('WaliKelas/Catatan.form_followup_ph') ?>",
    btn_cancel: "<?= lang('WaliKelas/Catatan.btn_cancel') ?>",
    btn_save: "<?= lang('WaliKelas/Catatan.btn_save') ?>",
    succ_saved: "<?= lang('WaliKelas/Catatan.succ_saved') ?>",
    del_title: "<?= lang('WaliKelas/Catatan.del_title') ?>",
    del_desc: "<?= lang('WaliKelas/Catatan.del_desc') ?>",
    btn_yes_delete: "<?= lang('WaliKelas/Catatan.btn_yes_delete') ?>",
    succ_deleted: "<?= lang('WaliKelas/Catatan.succ_deleted') ?>"
  };

  // IMPLEMENTASI RBAC: Kunci Fitur CRUD Modul Catatan (wali_karakter)
  const CAN_CREATE = <?= has_permission('wali_karakter', 'create') ? 'true' : 'false' ?>;
  const CAN_UPDATE = <?= has_permission('wali_karakter', 'update') ? 'true' : 'false' ?>;
  const CAN_DELETE = <?= has_permission('wali_karakter', 'delete') ? 'true' : 'false' ?>;
</script>
<script src="<?= base_url('assets/js/WaliKelas/catatan-walikelas.js') ?>?v=<?= time() ?>"></script>
<?= $this->endSection() ?>