<?= $this->extend('layout/main') ?>

<?= $this->section('title') ?>
  Catatan Wali Kelas - Rapor Digital
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
  <link rel="stylesheet" href="<?= base_url('assets/css/WaliKelas/catatan-walikelas.css') ?>">
  <style>
    :root {
      --warna-primary: <?= $color['warna_primary'] ?? '#10b981' ?>;
      --warna-secondary: <?= $color['warna_secondary'] ?? '#ecfdf5' ?>;
    }
    .text-tema { color: var(--warna-primary) !important; }
    .bg-tema { background-color: var(--warna-primary) !important; }
    .bg-tema-light { background-color: var(--warna-secondary) !important; }
    .border-tema { border-color: var(--warna-primary) !important; }
  </style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<main id="mainContent" class="min-h-[70vh] p-4 lg:p-6 w-full">
    <div class="flex flex-col items-center justify-center py-32">
        <div class="animate-spin rounded-full h-12 w-12 border-b-4 mb-4 border-tema"></div>
        <span class="font-medium text-lg text-tema">Memuat data Catatan Wali Kelas...</span>
    </div>
</main>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
  <script>
      const BASE_URL = '<?= rtrim(base_url(), '/') ?>';
      const serverStudents = <?= $students ?>;
      const serverCatatan = <?= $catatan ?>;
  </script>
  <script src="<?= base_url('assets/js/WaliKelas/catatan-walikelas.js') ?>"></script>
<?= $this->endSection() ?>