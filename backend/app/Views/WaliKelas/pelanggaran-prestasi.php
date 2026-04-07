<?= $this->extend('layout/main') ?>

<?= $this->section('title') ?>
  <?= esc($title) ?> - Rapor Digital
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
  <link rel="stylesheet" href="<?= base_url('assets/css/WaliKelas/pelanggaran-prestasi.css') ?>">
  <style>
    /* Injeksi Warna Tema Dinamis dari Database */
    :root {
      --warna-primary: <?= $color['warna_primary'] ?? '#10b981' ?>;
      --warna-secondary: <?= $color['warna_secondary'] ?? '#ecfdf5' ?>;
      --warna-scroll: <?= $color['warna_primary'] ?>; 
    }
    
    .text-tema { color: var(--warna-primary) !important; }
    .bg-tema { background-color: var(--warna-primary) !important; }
    
    /* Background sekunder menyesuaikan opacity */
    .bg-tema-light { background-color: color-mix(in srgb, var(--warna-primary) 12%, transparent) !important; }
    .border-tema { border-color: var(--warna-primary) !important; }
    .focus-tema:focus { 
        border-color: var(--warna-primary) !important; 
        box-shadow: 0 0 0 3px color-mix(in srgb, var(--warna-primary) 20%, transparent) !important; 
        outline: none; 
    }

    /* Penyesuaian khusus Dark Mode untuk teks tema */
    html.dark .text-tema { color: color-mix(in srgb, var(--warna-primary) 80%, white) !important; }
    
    /* Custom Scrollbar */
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
<main id="mainContent" class="min-h-[70vh]">
    <div class="flex flex-col items-center justify-center py-32">
        <div class="animate-spin rounded-full h-12 w-12 border-b-4 mb-4 border-tema"></div>
        <span class="font-medium text-lg text-tema animate-pulse"><?= lang('WaliKelas/Pelanggaran.js_preparing_data') ?></span>
    </div>
</main>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
  <script>
      const BASE_URL = '<?= rtrim(base_url(), '/') ?>';
      window.sekolahConfig = {
          school_name: '<?= esc($nama_sekolah ?? 'SMPIT Ad Durrah') ?>'
      };
      
      // KAMUS BAHASA JS
      window.LANG = {
          stat_tot_pelanggaran: "<?= lang('WaliKelas/Pelanggaran.stat_tot_pelanggaran') ?>",
          stat_kasus_berat: "<?= lang('WaliKelas/Pelanggaran.stat_kasus_berat') ?>",
          stat_dari_keseluruhan: "<?= lang('WaliKelas/Pelanggaran.stat_dari_keseluruhan') ?>",
          stat_tot_prestasi: "<?= lang('WaliKelas/Pelanggaran.stat_tot_prestasi') ?>",
          stat_prestasi_akademik: "<?= lang('WaliKelas/Pelanggaran.stat_prestasi_akademik') ?>",
          tab_analitik: "<?= lang('WaliKelas/Pelanggaran.tab_analitik') ?>",
          tab_pelanggaran: "<?= lang('WaliKelas/Pelanggaran.tab_pelanggaran') ?>",
          tab_prestasi: "<?= lang('WaliKelas/Pelanggaran.tab_prestasi') ?>",
          title_pelanggaran_terbanyak: "<?= lang('WaliKelas/Pelanggaran.title_pelanggaran_terbanyak') ?>",
          lbl_kasus: "<?= lang('WaliKelas/Pelanggaran.lbl_kasus') ?>",
          no_data: "<?= lang('WaliKelas/Pelanggaran.no_data') ?>",
          title_prestasi_terbanyak: "<?= lang('WaliKelas/Pelanggaran.title_prestasi_terbanyak') ?>",
          lbl_capaian: "<?= lang('WaliKelas/Pelanggaran.lbl_capaian') ?>",
          title_butuh_perhatian: "<?= lang('WaliKelas/Pelanggaran.title_butuh_perhatian') ?>",
          lbl_poin_penalti: "<?= lang('WaliKelas/Pelanggaran.lbl_poin_penalti') ?>",
          no_heavy_problem: "<?= lang('WaliKelas/Pelanggaran.no_heavy_problem') ?>",
          search_ph: "<?= lang('WaliKelas/Pelanggaran.search_ph') ?>",
          filter_all_level: "<?= lang('WaliKelas/Pelanggaran.filter_all_level') ?>",
          filter_light: "<?= lang('WaliKelas/Pelanggaran.filter_light') ?>",
          filter_medium: "<?= lang('WaliKelas/Pelanggaran.filter_medium') ?>",
          filter_heavy: "<?= lang('WaliKelas/Pelanggaran.filter_heavy') ?>",
          btn_catat_pelanggaran: "<?= lang('WaliKelas/Pelanggaran.btn_catat_pelanggaran') ?>",
          btn_catat_prestasi: "<?= lang('WaliKelas/Pelanggaran.btn_catat_prestasi') ?>",
          no_pelanggaran_record: "<?= lang('WaliKelas/Pelanggaran.no_pelanggaran_record') ?>",
          no_prestasi_record: "<?= lang('WaliKelas/Pelanggaran.no_prestasi_record') ?>",
          modal_title_update: "<?= lang('WaliKelas/Pelanggaran.modal_title_update') ?>",
          modal_title_add: "<?= lang('WaliKelas/Pelanggaran.modal_title_add') ?>",
          modal_pelanggaran_subtitle: "<?= lang('WaliKelas/Pelanggaran.modal_pelanggaran_subtitle') ?>",
          form_student: "<?= lang('WaliKelas/Pelanggaran.form_student') ?>",
          form_student_ph: "<?= lang('WaliKelas/Pelanggaran.form_student_ph') ?>",
          form_category: "<?= lang('WaliKelas/Pelanggaran.form_category') ?>",
          form_severity: "<?= lang('WaliKelas/Pelanggaran.form_severity') ?>",
          form_date: "<?= lang('WaliKelas/Pelanggaran.form_date') ?>",
          form_action_status: "<?= lang('WaliKelas/Pelanggaran.form_action_status') ?>",
          form_desc: "<?= lang('WaliKelas/Pelanggaran.form_desc') ?>",
          form_desc_pel_ph: "<?= lang('WaliKelas/Pelanggaran.form_desc_pel_ph') ?>",
          btn_cancel: "<?= lang('WaliKelas/Pelanggaran.btn_cancel') ?>",
          btn_save: "<?= lang('WaliKelas/Pelanggaran.btn_save') ?>",
          btn_update: "<?= lang('WaliKelas/Pelanggaran.btn_update') ?>",
          modal_prestasi_subtitle: "<?= lang('WaliKelas/Pelanggaran.modal_prestasi_subtitle') ?>",
          form_achieve_name: "<?= lang('WaliKelas/Pelanggaran.form_achieve_name') ?>",
          form_achieve_ph: "<?= lang('WaliKelas/Pelanggaran.form_achieve_ph') ?>",
          form_reward: "<?= lang('WaliKelas/Pelanggaran.form_reward') ?>",
          form_reward_ph: "<?= lang('WaliKelas/Pelanggaran.form_reward_ph') ?>",
          form_points: "<?= lang('WaliKelas/Pelanggaran.form_points') ?>",
          succ_saved: "<?= lang('WaliKelas/Pelanggaran.succ_saved') ?>",
          err_save: "<?= lang('WaliKelas/Pelanggaran.err_save') ?>",
          del_pelanggaran_title: "<?= lang('WaliKelas/Pelanggaran.del_pelanggaran_title') ?>",
          del_prestasi_title: "<?= lang('WaliKelas/Pelanggaran.del_prestasi_title') ?>",
          del_desc: "<?= lang('WaliKelas/Pelanggaran.del_desc') ?>",
          btn_yes_delete: "<?= lang('WaliKelas/Pelanggaran.btn_yes_delete') ?>",
          succ_deleted: "<?= lang('WaliKelas/Pelanggaran.succ_deleted') ?>",
          err_delete: "<?= lang('WaliKelas/Pelanggaran.err_delete') ?>",
          
          // Kategori & Status (Hanya untuk UI, DB tetap aman)
          cat_late: "<?= lang('WaliKelas/Pelanggaran.cat_late') ?>",
          cat_not_ready: "<?= lang('WaliKelas/Pelanggaran.cat_not_ready') ?>",
          cat_clothes: "<?= lang('WaliKelas/Pelanggaran.cat_clothes') ?>",
          cat_noise: "<?= lang('WaliKelas/Pelanggaran.cat_noise') ?>",
          cat_other: "<?= lang('WaliKelas/Pelanggaran.cat_other') ?>",
          cat_academic: "<?= lang('WaliKelas/Pelanggaran.cat_academic') ?>",
          cat_non_academic: "<?= lang('WaliKelas/Pelanggaran.cat_non_academic') ?>",
          cat_character: "<?= lang('WaliKelas/Pelanggaran.cat_character') ?>",
          
          stat_recorded: "<?= lang('WaliKelas/Pelanggaran.stat_recorded') ?>",
          stat_warned: "<?= lang('WaliKelas/Pelanggaran.stat_warned') ?>",
          stat_parent_call: "<?= lang('WaliKelas/Pelanggaran.stat_parent_call') ?>"
      };

      // Menerima Data JSON dari Controller
      const serverStudents = <?= $students ?>;
      const serverPelanggaran = <?= $pelanggaran ?>;
      const serverPrestasi = <?= $prestasi ?>;
  </script>
  <script src="<?= base_url('assets/js/WaliKelas/pelanggaran-prestasi.js') ?>?v=<?= time() ?>"></script>
<?= $this->endSection() ?>