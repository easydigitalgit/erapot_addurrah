<?= $this->extend('layout/main') ?>

<?= $this->section('title') ?>
  <?= esc($title) ?> - Rapor Digital
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
  <link rel="stylesheet" href="<?= base_url('assets/css/WaliKelas/preview-rapor.css') ?>">
  <style>
    :root {
      --warna-primary: <?= $color['warna_primary'] ?? '#10b981' ?>;
      --warna-secondary: <?= $color['warna_secondary'] ?? '#ecfdf5' ?>;
    }
    .text-tema { color: var(--warna-primary) !important; }
    .bg-tema { background-color: var(--warna-primary) !important; }
    .bg-tema-light { background-color: var(--warna-secondary) !important; }
    .border-tema { border-color: var(--warna-primary) !important; }
    
    /* Ciri Khusus untuk Mencetak (Print) */
    @media print {
        body { background-color: white !important; }
        .no-print, #sidebar, header { display: none !important; }
        .rapor-card { border: none !important; box-shadow: none !important; }
        main { padding: 0 !important; margin: 0 !important; }
    }
  </style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<main id="mainContent" class="min-h-[70vh] p-4 lg:p-6 w-full">
    </main>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
  <script>
      const BASE_URL = '<?= rtrim(base_url(), '/') ?>';
      const serverStudents = <?= $students ?>;
      const rombelName = "<?= $rombel_name ?>";
  </script>
  <script src="<?= base_url('assets/js/WaliKelas/preview-rapor.js') ?>"></script>
<?= $this->endSection() ?>