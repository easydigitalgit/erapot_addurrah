<?= $this->extend('layout/main') ?>

<?= $this->section('title') ?>
  Catatan Rapor Wali - Rapor Digital
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
  <link rel="stylesheet" href="<?= base_url('assets/css/WaliKelas/catatan-rapor.css') ?>">
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<main id="mainContent" class="flex-1 overflow-y-auto p-4 lg:p-6 w-full"><!-- Content akan di-render di sini --></main>
<?= $this->endSection() ?>

<?= $this->section('modals') ?>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
  <script src="<?= base_url('assets/js/WaliKelas/catatan-rapor.js') ?>"></script>
<?= $this->endSection() ?>
