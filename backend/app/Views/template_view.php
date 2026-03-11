<?= $this->extend('layout/main') ?>

<?= $this->section('title') ?>
  Title Website
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
  <link rel="stylesheet" href="<?= base_url('assets/css/#.css') ?>">
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?= $this->endSection() ?>

<?= $this->section('modals') ?>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
  <script src="<?= base_url('assets/js/#.js') ?>"></script>
<?= $this->endSection() ?>