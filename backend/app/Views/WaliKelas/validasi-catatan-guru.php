<?= $this->extend('layout/main') ?>

<?= $this->section('title') ?>
  Validasi Catatan Guru - Rapor Digital
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
  <link rel="stylesheet" href="<?= base_url('assets/css/WaliKelas/validasi-catatan-guru.css') ?>">
  <style>
    :root {
      --warna-primary: <?= $color['warna_primary'] ?? '#10b981' ?>;
      --warna-secondary: <?= $color['warna_secondary'] ?? '#ecfdf5' ?>;
    }
    .text-tema { color: var(--warna-primary) !important; }
    .bg-tema { background-color: var(--warna-primary) !important; }
    .bg-tema-light { background-color: var(--warna-secondary) !important; }
    .border-tema { border-color: var(--warna-primary) !important; }
    .validasi-tab-active { border-bottom-color: var(--warna-primary); background-color: var(--warna-secondary); color: var(--warna-primary); }
  </style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div id="validasiContainer">

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 lg:p-5 mb-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="font-semibold text-gray-800">Filter Pencarian Catatan</h2>
            <button onclick="resetFilter()" class="text-xs font-semibold text-tema hover:underline">Setel Ulang</button>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
            <div>
                <label class="text-xs font-medium text-gray-600 mb-1.5 block">Cari Nama Siswa</label>
                <input type="text" id="searchStudent" placeholder="Ketik nama siswa..." class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:border-tema focus:ring-1 focus:ring-tema transition-colors" onkeyup="filterValidasiCatatan()">
            </div>
            <div>
                <label class="text-xs font-medium text-gray-600 mb-1.5 block">Kategori Catatan</label>
                <select id="filterCategory" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:border-tema focus:ring-1 focus:ring-tema transition-colors" onchange="filterValidasiCatatan()">
                    <option value="">Semua Kategori</option>
                    <option value="Akademik">Akademik</option>
                    <option value="Perilaku">Perilaku / Karakter</option>
                    <option value="Prestasi">Prestasi</option>
                </select>
            </div>
            <div>
                <label class="text-xs font-medium text-gray-600 mb-1.5 block">Urutkan Berdasarkan</label>
                <select id="sortValidasi" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:border-tema focus:ring-1 focus:ring-tema transition-colors" onchange="filterValidasiCatatan()">
                    <option value="date-new">Tanggal (Terbaru ke Lama)</option>
                    <option value="date-old">Tanggal (Terlama ke Baru)</option>
                    <option value="student">Nama Siswa (A - Z)</option>
                </select>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 lg:gap-4 mb-6">
        <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100 card-hover">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs lg:text-sm text-gray-500 mb-1">Total Catatan</p>
                    <p class="text-2xl lg:text-3xl font-bold text-gray-800" id="statTotal">0</p>
                </div>
                <div class="w-10 h-10 lg:w-12 lg:h-12 rounded-xl bg-blue-50 flex items-center justify-center">
                    <svg class="w-5 h-5 lg:w-6 lg:h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100 card-hover">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs lg:text-sm text-gray-500 mb-1">Menunggu</p>
                    <p class="text-2xl lg:text-3xl font-bold text-amber-500" id="statMenunggu">0</p>
                </div>
                <div class="w-10 h-10 lg:w-12 lg:h-12 rounded-xl bg-amber-50 flex items-center justify-center">
                    <svg class="w-5 h-5 lg:w-6 lg:h-6 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100 card-hover">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs lg:text-sm text-gray-500 mb-1">Disetujui</p>
                    <p class="text-2xl lg:text-3xl font-bold text-emerald-600" id="statDisetujui">0</p>
                </div>
                <div class="w-10 h-10 lg:w-12 lg:h-12 rounded-xl bg-emerald-50 flex items-center justify-center">
                    <svg class="w-5 h-5 lg:w-6 lg:h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100 card-hover">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs lg:text-sm text-gray-500 mb-1">Ditolak</p>
                    <p class="text-2xl lg:text-3xl font-bold text-red-600" id="statDitolak">0</p>
                </div>
                <div class="w-10 h-10 lg:w-12 lg:h-12 rounded-xl bg-red-50 flex items-center justify-center">
                    <svg class="w-5 h-5 lg:w-6 lg:h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10 14l-2-2m0 0l-2-2m2 2l2-2m-2 2l-2 2"/></svg>
                </div>
            </div>
        </div>
    </div>

    <div id="validasiTabsContainer"></div>

</div>
<?= $this->endSection() ?>

<script>
    window.dynamicCatatanData = <?= $catatanGuruData ?? '[]' ?>;
</script>

<?= $this->section('scripts') ?>
  <script src="<?= base_url('assets/js/WaliKelas/validasi-catatan-guru.js') ?>"></script>
<?= $this->endSection() ?>