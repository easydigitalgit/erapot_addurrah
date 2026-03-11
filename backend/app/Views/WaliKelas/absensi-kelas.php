<?= $this->extend('layout/main') ?>

<?= $this->section('title') ?>
  Absensi Kelas Harian - Rapor Digital
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
  <link rel="stylesheet" href="<?= base_url('assets/css/WaliKelas/absensi-kelas.css') ?>">
  <style>
    /* Injeksi Variabel Warna Awal */
    :root {
      --warna-primary: <?= $color['warna_primary'] ?? '#10b981' ?>;
      --warna-secondary: <?= $color['warna_secondary'] ?? '#ecfdf5' ?>;
      --warna-hadir: <?= $color['warna_hadir'] ?? '#16a34a' ?>;
      --warna-sakit: <?= $color['warna_sakit'] ?? '#ca8a04' ?>;
      --warna-izin: <?= $color['warna_izin'] ?? '#9333ea' ?>;
      --warna-alpha: <?= $color['warna_alpha'] ?? '#dc2626' ?>;
    }
    .text-tema { color: var(--warna-primary) !important; }
    .bg-tema { background-color: var(--warna-primary) !important; }
    .bg-tema-light { background-color: var(--warna-secondary) !important; }
    .border-tema { border-color: var(--warna-primary) !important; }
  </style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div id="mainContent" class="min-h-[70vh] w-full">
    <div class="flex flex-col items-center justify-center py-32">
        <div class="animate-spin rounded-full h-12 w-12 border-b-4 mb-4 border-tema"></div>
        <span class="font-medium text-lg text-tema">Memuat data absensi kelas...</span>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
  <script>
      // Deklarasi URL agar AJAX tidak nyasar
      const BASE_URL = '<?= rtrim(base_url(), '/') ?>';
  </script>
  <script src="<?= base_url('assets/js/WaliKelas/absensi-kelas.js') ?>"></script>
<?= $this->endSection() ?>