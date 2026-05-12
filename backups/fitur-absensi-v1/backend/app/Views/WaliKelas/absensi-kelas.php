<?= $this->extend('layout/main') ?>

<?= $this->section('title') ?>
<?= lang('WaliKelas/Absensi.page_title') ?> - Rapor Digital
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<link rel="stylesheet" href="<?= base_url('assets/css/WaliKelas/absensi-kelas.css') ?>">
<style>
  :root {
    --warna-primary: <?= $color['warna_primary'] ?? '#10b981' ?>;
    --warna-secondary: <?= $color['warna_secondary'] ?? '#ecfdf5' ?>;
    --warna-hadir: <?= $color['warna_hadir'] ?? '#16a34a' ?>;
    --warna-sakit: <?= $color['warna_sakit'] ?? '#ca8a04' ?>;
    --warna-izin: <?= $color['warna_izin'] ?? '#9333ea' ?>;
    --warna-alpha: <?= $color['warna_alpha'] ?? '#dc2626' ?>;
    --warna-scroll: <?= $color['warna_primary'] ?>; 
  }

  .text-tema {
    color: var(--warna-primary) !important;
  }

  .bg-tema {
    background-color: var(--warna-primary) !important;
  }

  .bg-tema-light {
    background-color: var(--warna-secondary) !important;
  }

  .border-tema {
    border-color: var(--warna-primary) !important;
  }

  .focus-tema:focus {
    border-color: var(--warna-primary) !important;
    box-shadow: 0 0 0 3px color-mix(in srgb, var(--warna-primary) 20%, transparent) !important;
    outline: none;
  }

  html.dark .text-tema {
    color: color-mix(in srgb, var(--warna-primary) 80%, white) !important;
  }

  html.dark .bg-tema-light {
    background-color: rgba(255, 255, 255, 0.05) !important;
  }

  html.dark .bg-white {
    background-color: #1e293b !important;
    border-color: #334155 !important;
  }

  html.dark .text-gray-800 {
    color: #f1f5f9 !important;
  }

  html.dark .text-gray-500 {
    color: #94a3b8 !important;
  }

  html.dark .text-gray-400 {
    color: #64748b !important;
  }

  html.dark .bg-gray-50 {
    background-color: #0f172a !important;
  }

  html.dark .border-gray-100 {
    border-color: #334155 !important;
  }

  html.dark .border-gray-200 {
    border-color: #475569 !important;
  }

  html.dark .custom-scrollbar::-webkit-scrollbar-thumb {
    background-color: var(--warna-primary);
  }

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
<div id="mainContent" class="min-h-[70vh] w-full">
  <div class="flex flex-col items-center justify-center py-32">
    <div class="animate-spin rounded-full h-12 w-12 border-b-4 mb-4 border-tema"></div>
    <span class="font-bold text-lg text-tema tracking-wider animate-pulse"><?= lang('WaliKelas/Absensi.js_preparing_recap') ?></span>
  </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
  const BASE_URL = '<?= rtrim(base_url(), '/') ?>';
  window.sekolahConfig = {
    school_name: '<?= esc($nama_sekolah ?? 'SMPIT Ad Durrah') ?>',
    teacher_name: '<?= esc($user) ?>',
    class_name: '<?= esc($nama_rombel ?? '') ?>'
  };

  window.LANG = {
    err_server_load: "<?= lang('WaliKelas/Absensi.js_err_server_load') ?>",
    btn_add_data: "<?= lang('WaliKelas/Absensi.btn_add_data') ?>",
    page_title: "<?= lang('WaliKelas/Absensi.page_title') ?>",
    page_subtitle_1: "<?= lang('WaliKelas/Absensi.page_subtitle_1') ?>",
    stat_total_student: "<?= lang('WaliKelas/Absensi.stat_total_student') ?>",
    stat_attendance: "<?= lang('WaliKelas/Absensi.stat_attendance') ?>",
    stat_sick: "<?= lang('WaliKelas/Absensi.stat_sick') ?>",
    stat_permission_alpha: "<?= lang('WaliKelas/Absensi.stat_permission_alpha') ?>",
    board_title: "<?= lang('WaliKelas/Absensi.board_title') ?>",
    btn_export_csv: "<?= lang('WaliKelas/Absensi.btn_export_csv') ?>",
    th_student_name: "<?= lang('WaliKelas/Absensi.th_student_name') ?>",
    th_percentage: "<?= lang('WaliKelas/Absensi.th_percentage') ?>",
    calendar_title: "<?= lang('WaliKelas/Absensi.calendar_title') ?>",
    cal_safe: "<?= lang('WaliKelas/Absensi.cal_safe') ?>",
    cal_problem: "<?= lang('WaliKelas/Absensi.cal_problem') ?>",
    cal_empty: "<?= lang('WaliKelas/Absensi.cal_empty') ?>",
    cal_help_text: "<?= lang('WaliKelas/Absensi.cal_help_text') ?>",
    eval_title: "<?= lang('WaliKelas/Absensi.eval_title') ?>",
    day_mon: "<?= lang('WaliKelas/Absensi.day_mon') ?>",
    day_tue: "<?= lang('WaliKelas/Absensi.day_tue') ?>",
    day_wed: "<?= lang('WaliKelas/Absensi.day_wed') ?>",
    day_thu: "<?= lang('WaliKelas/Absensi.day_thu') ?>",
    day_fri: "<?= lang('WaliKelas/Absensi.day_fri') ?>",
    day_sat: "<?= lang('WaliKelas/Absensi.day_sat') ?>",
    day_sun: "<?= lang('WaliKelas/Absensi.day_sun') ?>",
    hover_manage: "<?= lang('WaliKelas/Absensi.hover_manage') ?>",
    hover_detail: "<?= lang('WaliKelas/Absensi.hover_detail') ?>",
    err_no_access_add: "Akses ditolak! Anda belum ditetapkan sebagai Wali Kelas di kelas ini.",
    badge_sick: "<?= lang('WaliKelas/Absensi.badge_sick') ?>",
    badge_permit: "<?= lang('WaliKelas/Absensi.badge_permit') ?>",
    badge_alpha: "<?= lang('WaliKelas/Absensi.badge_alpha') ?>",
    all_present: "<?= lang('WaliKelas/Absensi.all_present') ?>",
    btn_edit_data: "<?= lang('WaliKelas/Absensi.btn_edit_data') ?>",
    modal_detail_title: "<?= lang('WaliKelas/Absensi.modal_detail_title') ?>",
    absent_list_title: "<?= lang('WaliKelas/Absensi.absent_list_title') ?>",
    btn_close: "<?= lang('WaliKelas/Absensi.btn_close') ?>",
    eval_great_title: "<?= lang('WaliKelas/Absensi.eval_great_title') ?>",
    eval_great_desc: "<?= lang('WaliKelas/Absensi.eval_great_desc') ?>",
    eval_warn_title: "<?= lang('WaliKelas/Absensi.eval_warn_title') ?>",
    eval_warn_desc: "<?= lang('WaliKelas/Absensi.eval_warn_desc') ?>",
    eval_attn_title: "<?= lang('WaliKelas/Absensi.eval_attn_title') ?>",
    eval_attn_desc_1: "<?= lang('WaliKelas/Absensi.eval_attn_desc_1') ?>",
    eval_attn_desc_2: "<?= lang('WaliKelas/Absensi.eval_attn_desc_2') ?>",
    eval_good_title: "<?= lang('WaliKelas/Absensi.eval_good_title') ?>",
    eval_good_desc: "<?= lang('WaliKelas/Absensi.eval_good_desc') ?>",
    no_data_table: "<?= lang('WaliKelas/Absensi.no_data_table') ?>",
    pred_discipline: "<?= lang('WaliKelas/Absensi.pred_discipline') ?>",
    pred_enough: "<?= lang('WaliKelas/Absensi.pred_enough') ?>",
    pred_warning: "<?= lang('WaliKelas/Absensi.pred_warning') ?>",
    succ_update: "<?= lang('WaliKelas/Absensi.succ_update') ?>",
    err_update_fail: "<?= lang('WaliKelas/Absensi.err_update_fail') ?>",
    modal_add_title: "<?= lang('WaliKelas/Absensi.modal_add_title') ?>",
    modal_edit_title: "<?= lang('WaliKelas/Absensi.modal_edit_title') ?>",
    select_date: "<?= lang('WaliKelas/Absensi.select_date') ?>",
    opt_present: "<?= lang('WaliKelas/Absensi.opt_present') ?>",
    opt_sick: "<?= lang('WaliKelas/Absensi.opt_sick') ?>",
    opt_permit: "<?= lang('WaliKelas/Absensi.opt_permit') ?>",
    opt_alpha: "<?= lang('WaliKelas/Absensi.opt_alpha') ?>",
    btn_cancel: "<?= lang('WaliKelas/Absensi.btn_cancel') ?>",
    btn_save: "<?= lang('WaliKelas/Absensi.btn_save') ?>",
    processing: "<?= lang('WaliKelas/Absensi.processing') ?>",
    err_save_server: "<?= lang('WaliKelas/Absensi.err_save_server') ?>",
    err_export_empty: "<?= lang('WaliKelas/Absensi.err_export_empty') ?>",
    export_filename: "<?= lang('WaliKelas/Absensi.export_filename') ?>"
  };

  const CAN_CREATE = <?= isset($is_wali_kelas_sah) && $is_wali_kelas_sah ? 'true' : 'false' ?>;
</script>
<script src="<?= base_url('assets/js/WaliKelas/absensi-kelas.js') ?>?v=<?= time() ?>"></script>
<?= $this->endSection() ?>