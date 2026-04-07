<?= $this->extend('layout/main') ?>

<?= $this->section('title') ?>
Mapping Guru Mapel - Rapor Digital SMPIT Ad Durrah
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<link rel="stylesheet" href="<?= base_url('assets/css/Admin/mapping-mapel.css') ?>">
<style>
  :root { --warna-scroll: <?= $color['warna_primary'] ?>; }
  .chip-checkbox input[type="checkbox"] { display: none; }
  .chip-checkbox span { border: 2px solid transparent; }
  .chip-checkbox input[type="checkbox"]:checked+span {
    background-color: <?= $color['warna_primary'] ?> !important;
    color: #fff !important;
    border-color: <?= $color['warna_primary'] ?> !important;
  }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="w-full min-w-0">
  <div class="mb-6">
    <div class="flex items-center gap-2 text-sm text-gray-500 dark:text-slate-400 mb-2 transition-colors">
      <span>Master Data</span>
      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewbox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
      </svg><span class="text-[<?= $color['warna_primary'] ?>] font-medium">Mapping Guru Mapel</span>
    </div>
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
      <div class="min-w-0">
        <h1 id="page-title" class="text-xl md:text-3xl font-bold text-gray-800 dark:text-white truncate transition-colors">Mapping Guru Mapel</h1>
        <p id="page-subtitle" class="text-sm md:text-base text-gray-600 dark:text-slate-400 mt-1 truncate transition-colors">Tetapkan guru pengampu untuk setiap mata pelajaran dan kelas</p>
      </div>
      
      <div class="flex flex-wrap items-center gap-2 md:gap-3">
        <a href="<?= base_url('admin/mapping-mapel/template') ?>" class="px-4 py-2.5 bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 hover:bg-gray-50 dark:hover:bg-slate-700 text-gray-700 dark:text-slate-300 font-semibold rounded-xl transition-all flex items-center gap-2 outline-none shadow-sm">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
          <span class="hidden sm:inline">Template Excel</span>
        </a>
        <button onclick="showImportModal()" class="px-4 py-2.5 bg-amber-500 hover:bg-amber-600 text-white font-semibold rounded-xl transition-all shadow-lg shadow-amber-500/20 flex items-center gap-2 transform hover:-translate-y-0.5 outline-none">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
          <span class="hidden sm:inline">Import Cerdas</span>
        </button>
        <button onclick="showAddModal()" class="px-4 py-2.5 bg-[<?= $color['warna_primary'] ?>] hover:bg-[<?= $color['warna_primary'] ?>]/90 text-white font-semibold rounded-xl transition-all shadow-lg shadow-[<?= $color['warna_primary'] ?>]/20 flex items-center gap-2 transform hover:-translate-y-0.5 outline-none">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewbox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
          </svg><span class="hidden sm:inline">Tambah Manual</span>
        </button>
      </div>
    </div>
  </div>

  <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6 w-full">
    <div class="stat-card bg-white dark:bg-slate-800 rounded-2xl p-5 shadow-sm border border-gray-100 dark:border-slate-700 transition-colors">
      <div class="flex items-center gap-3 mb-2">
        <div class="w-10 h-10 rounded-xl bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center">
          <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
        </div>
      </div>
      <p class="text-sm font-medium text-gray-500 dark:text-slate-400 mb-1">Total Guru</p>
      <h3 class="text-2xl font-bold text-gray-800 dark:text-white"><?= number_format($stats['total_guru'] ?? 0) ?></h3>
    </div>
    <div class="stat-card bg-white dark:bg-slate-800 rounded-2xl p-5 shadow-sm border border-gray-100 dark:border-slate-700 transition-colors">
      <div class="flex items-center gap-3 mb-2">
        <div class="w-10 h-10 rounded-xl bg-purple-100 dark:bg-purple-900/30 flex items-center justify-center">
          <svg class="w-5 h-5 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
        </div>
      </div>
      <p class="text-sm font-medium text-gray-500 dark:text-slate-400 mb-1">Total Mata Pelajaran</p>
      <h3 class="text-2xl font-bold text-gray-800 dark:text-white"><?= number_format($stats['total_mapel'] ?? 0) ?></h3>
    </div>
    <div class="stat-card bg-white dark:bg-slate-800 rounded-2xl p-5 shadow-sm border border-gray-100 dark:border-slate-700 transition-colors">
      <div class="flex items-center gap-3 mb-2">
        <div class="w-10 h-10 rounded-xl bg-emerald-100 dark:bg-emerald-900/30 flex items-center justify-center">
          <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
        </div>
      </div>
      <p class="text-sm font-medium text-gray-500 dark:text-slate-400 mb-1">Mapping Aktif</p>
      <h3 class="text-2xl font-bold text-emerald-600 dark:text-emerald-400"><?= number_format($stats['total_mapping'] ?? 0) ?></h3>
    </div>
    <div class="stat-card bg-white dark:bg-slate-800 rounded-2xl p-5 shadow-sm border border-gray-100 dark:border-slate-700 transition-colors">
      <div class="flex items-center gap-3 mb-2">
        <div class="w-10 h-10 rounded-xl bg-amber-100 dark:bg-amber-900/30 flex items-center justify-center">
          <svg class="w-5 h-5 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
        </div>
      </div>
      <p class="text-sm font-medium text-gray-500 dark:text-slate-400 mb-1">Rombel Kosong</p>
      <h3 class="text-2xl font-bold text-amber-600 dark:text-amber-400"><?= number_format($stats['empty_rombel'] ?? 0) ?></h3>
    </div>
  </div>

  <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700 p-5 mb-6 w-full min-w-0 transition-colors">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-3 2xl:grid-cols-6 gap-4 w-full">
      <div>
        <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-2">Filter Tahun & Semester</label>
        <select id="filterTahun" onchange="window.location.href='?ta=' + this.value" class="w-full px-4 py-2 bg-white dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-xl text-sm text-gray-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-[<?= $color['warna_primary'] ?>] transition-colors cursor-pointer outline-none">
          <?php foreach ($tahunAjaranList as $ta): ?>
            <?php $labelTA = $ta['tahun'] . ' (' . $ta['semester'] . ')'; ?>
            <option value="<?= $ta['id'] ?>" <?= ($ta['id'] == $idTaAktif) ? 'selected' : '' ?>><?= $labelTA ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div>
        <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-2">Filter Tingkat</label>
        <select id="filterLevel" class="w-full px-4 py-2 bg-white dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-xl text-sm text-gray-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-[<?= $color['warna_primary'] ?>] transition-colors cursor-pointer outline-none">
          <option value="">Semua Tingkat</option>
          <?php foreach ($tingkatList as $tingkat): ?>
            <option value="<?= $tingkat ?>">Kelas <?= $tingkat ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div>
        <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-2">Filter Rombel</label>
        <select id="filterRombel" class="w-full px-4 py-2 bg-white dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-xl text-sm text-gray-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-[<?= $color['warna_primary'] ?>] transition-colors cursor-pointer outline-none">
          <option value="">Semua Rombel</option>
          <?php foreach ($rombelList as $rombel): ?>
            <option value="<?= $rombel['id'] ?>"><?= $rombel['nama_rombel'] ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div>
        <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-2">Filter Mata Pelajaran</label>
        <select id="filterMapel" class="w-full px-4 py-2 bg-white dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-xl text-sm text-gray-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-[<?= $color['warna_primary'] ?>] transition-colors cursor-pointer outline-none">
          <option value="">Semua Mapel</option>
          <?php foreach ($mapelList as $mapel): ?>
            <option value="<?= $mapel['id'] ?>"><?= $mapel['nama_mapel'] ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div>
        <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-2">Filter Guru</label>
        <select id="filterGuru" class="w-full px-4 py-2 bg-white dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-xl text-sm text-gray-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-[<?= $color['warna_primary'] ?>] transition-colors cursor-pointer outline-none">
          <option value="">Semua Guru</option>
          <?php foreach ($guruList as $guru): ?>
            <option value="<?= $guru['id'] ?>"><?= $guru['nama_lengkap'] ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div>
        <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-2">Pencarian</label>
        <input type="text" id="searchInput" placeholder="Cari nama guru, NIK, mapel..." class="w-full px-4 py-2 text-sm bg-white dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-xl text-gray-800 dark:text-white placeholder-gray-400 dark:placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[<?= $color['warna_primary'] ?>] transition-colors shadow-sm outline-none">
      </div>
    </div>
    <div class="mt-4 flex items-center gap-3">
      <label class="flex items-center gap-2 cursor-pointer">
        <input type="checkbox" id="toggleActiveOnly" class="w-4 h-4 text-[<?= $color['warna_primary'] ?>] rounded border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-700 focus:ring-[<?= $color['warna_primary'] ?>] cursor-pointer" checked>
        <span class="text-sm font-bold text-gray-700 dark:text-slate-300">Tampilkan Hanya Yang Aktif</span>
      </label>
    </div>
  </div>

  <div class="bg-white dark:bg-slate-800 w-full rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700 overflow-hidden transition-colors" style="max-width: 100%;">
    <div class="overflow-x-auto w-full custom-scrollbar" style="max-width: 100%;">
      <table class="w-full min-w-max border-collapse text-left">
        <thead class="bg-gray-50 dark:bg-slate-900/50 border-b border-gray-100 dark:border-slate-700 transition-colors">
          <tr>
            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 dark:text-slate-400 uppercase tracking-wider">Guru Pengampu</th>
            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 dark:text-slate-400 uppercase tracking-wider">Mata Pelajaran</th>
            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 dark:text-slate-400 uppercase tracking-wider">Tingkat</th>
            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 dark:text-slate-400 uppercase tracking-wider">Rombel</th>
            <th class="px-6 py-4 text-center text-xs font-bold text-gray-500 dark:text-slate-400 uppercase tracking-wider">JP/Minggu</th>
            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 dark:text-slate-400 uppercase tracking-wider">Tahun Ajaran</th>
            <th class="px-6 py-4 text-center text-xs font-bold text-gray-500 dark:text-slate-400 uppercase tracking-wider">Status</th>
            <th class="px-6 py-4 text-center text-xs font-bold text-gray-500 dark:text-slate-400 uppercase tracking-wider">Aksi</th>
          </tr>
        </thead>
        <tbody id="mappingTableBody" class="divide-y divide-gray-100 dark:divide-slate-700/50 transition-colors">
        </tbody>
      </table>
    </div>     <div id="pagination-container" class="flex flex-col sm:flex-row items-center justify-between p-4 border-t border-gray-100 dark:border-slate-700 bg-white dark:bg-slate-800 rounded-b-2xl transition-colors" style="display: none;">
      <div class="text-sm text-gray-500 dark:text-slate-400 mb-4 sm:mb-0 font-medium" id="pagination-info">
        Menampilkan 0 sampai 0 dari 0 entri
      </div>
      <div class="flex items-center gap-1" id="pagination-controls">
          </div>
        </div>      
    </div>
  </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('modals') ?>

<div id="importModal" class="fixed inset-0 hidden" style="z-index: 99999;">
  <div class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity pointer-events-auto" onclick="closeImportModal()"></div>
  <div class="absolute inset-0 flex items-center justify-center p-4 pointer-events-none md:pl-64">
    <div class="relative w-full bg-white dark:bg-slate-800 rounded-3xl shadow-2xl flex flex-col max-h-[90vh] pointer-events-auto border border-transparent dark:border-slate-700 transition-colors duration-300" style="max-width: 500px;">
      
      <div class="p-6 border-b border-gray-100 dark:border-slate-700 flex items-center justify-between bg-white dark:bg-slate-800 rounded-t-3xl z-20 flex-shrink-0 transition-colors">
        <div>
          <h3 class="text-xl font-bold text-gray-800 dark:text-white">Smart Import Excel</h3>
          <p class="text-sm text-gray-500 dark:text-slate-400 mt-1">Upload file Excel roster Anda ke sini.</p>
        </div>
        <button type="button" onclick="closeImportModal()" class="p-2 hover:bg-gray-100 dark:hover:bg-slate-700 rounded-lg transition-colors cursor-pointer relative z-50 text-gray-600 dark:text-slate-400 outline-none">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
        </button>
      </div>

      <div class="flex-1 overflow-y-auto p-8 relative z-10 custom-scrollbar">
        <form id="importForm" action="<?= base_url('admin/mapping-mapel/import') ?>" method="POST" enctype="multipart/form-data" onsubmit="handleImportSubmit(event)">
          <div class="bg-blue-50 dark:bg-blue-900/30 p-4 rounded-xl border border-blue-200 dark:border-blue-800/50 mb-6">
              <p class="text-xs text-blue-800 dark:text-blue-300 leading-relaxed font-medium">Sistem <strong>AI-Match</strong> akan mendeteksi baris judul di Excel secara otomatis. Anda bahkan dapat menuliskan <code class="bg-white dark:bg-slate-800 px-1 rounded text-blue-600">7 Granit, 7 Topaz</code> sekaligus dalam satu sel kolom Kelas.</p>
          </div>
          <div class="border-2 border-dashed border-gray-300 dark:border-slate-600 rounded-2xl p-10 text-center hover:bg-gray-50 dark:hover:bg-slate-700/50 transition-colors relative cursor-pointer group">
            <input type="file" name="file_excel" id="file_excel" accept=".xlsx, .xls, .csv" required class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" onchange="updateFileName(this)">
            <div class="w-16 h-16 bg-emerald-50 dark:bg-emerald-900/30 text-emerald-500 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform">
              <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
            </div>
            <p class="text-sm font-bold text-gray-700 dark:text-slate-300" id="fileNameText">Klik atau Seret file Excel Anda</p>
            <p class="text-xs text-gray-500 dark:text-slate-400 mt-2">Format: .xlsx atau .xls</p>
          </div>
          <div class="mt-8 flex gap-3">
            <button type="button" onclick="closeImportModal()" class="flex-1 px-6 py-3.5 bg-gray-100 dark:bg-slate-700 text-gray-700 dark:text-slate-300 font-bold rounded-xl hover:bg-gray-200 dark:hover:bg-slate-600 transition-colors shadow-sm">Batal</button>
            <button type="submit" id="btnImport" class="flex-1 px-6 py-3.5 bg-emerald-500 hover:bg-emerald-600 text-white font-bold rounded-xl shadow-lg transition-transform transform hover:-translate-y-0.5">Mulai Ekstraksi</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<div id="addModal" class="fixed inset-0 hidden" style="z-index: 99999;">
  <div class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity pointer-events-auto" onclick="closeAddModal()"></div>
  <div class="absolute inset-0 flex items-center justify-center p-4 pointer-events-none md:pl-64">
    <div class="relative w-full bg-white dark:bg-slate-800 rounded-2xl shadow-2xl flex flex-col max-h-[90vh] pointer-events-auto border border-transparent dark:border-slate-700 transition-colors duration-300" style="max-width: 600px;">
      <div class="p-6 border-b border-gray-100 dark:border-slate-700 flex items-center justify-between bg-white dark:bg-slate-800 rounded-t-2xl z-20 flex-shrink-0 transition-colors">
        <div>
          <h3 class="text-xl font-bold text-gray-800 dark:text-white" id="modalTitle">Tambah Mapping Mapel</h3>
          <p class="text-sm text-gray-500 dark:text-slate-400 mt-1">Tetapkan guru pengampu untuk 1 mata pelajaran dan beberapa kelas.</p>
        </div>
        <button type="button" onclick="closeAddModal()" class="p-2 hover:bg-gray-100 dark:hover:bg-slate-700 rounded-lg transition-colors cursor-pointer relative z-50 text-gray-600 dark:text-slate-400 outline-none">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
        </button>
      </div>
      <div class="flex-1 overflow-y-auto p-6 relative z-10 custom-scrollbar">
        <form id="addMappingForm" class="space-y-5" onsubmit="handleAddSubmit(event)">
          <input type="hidden" name="id" id="editId">
          <div>
            <label for="add_guru" class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-2">Pilih Guru Pengampu <span class="text-red-500">*</span></label>
            <select name="add_guru" id="add_guru" required class="w-full px-4 py-3 bg-white dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-xl text-sm text-gray-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-[<?= $color['warna_primary'] ?>] transition-colors appearance-none cursor-pointer">
              <option value="">-- Pilih Guru --</option>
              <?php foreach ($guruList as $guru): ?>
                <option value="<?= $guru['id'] ?>"><?= $guru['nama_lengkap'] ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div>
            <label for="add_mapel" class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-2">Pilih Mata Pelajaran <span class="text-red-500">*</span></label>
            <select name="add_mapel" id="add_mapel" required class="w-full px-4 py-3 bg-white dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-xl text-sm text-gray-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-[<?= $color['warna_primary'] ?>] transition-colors appearance-none cursor-pointer">
              <option value="">-- Pilih Mapel --</option>
              <?php foreach ($mapelList as $mapel): ?>
                <option value="<?= $mapel['id'] ?>"><?= $mapel['nama_mapel'] ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-2">Rombel (Bisa Pilih Lebih Dari Satu) <span class="text-red-500">*</span></label>
            <div class="flex flex-wrap gap-2 max-h-40 overflow-y-auto custom-scrollbar p-2 bg-gray-50 dark:bg-slate-900/50 rounded-xl border border-gray-100 dark:border-slate-700">
              <?php foreach ($rombelList as $rombel): ?>
                <label class="chip-checkbox cursor-pointer">
                  <input type="checkbox" class="rombel-cb" value="<?= $rombel['id'] ?>">
                  <span class="inline-block px-4 py-2 bg-white dark:bg-slate-800 text-gray-700 dark:text-slate-300 text-sm font-bold rounded-lg shadow-sm hover:bg-gray-100 dark:hover:bg-slate-700 transition-colors">
                    <?= esc($rombel['nama_rombel']) ?>
                  </span>
                </label>
              <?php endforeach; ?>
            </div>
          </div>
          <div class="grid grid-cols-2 gap-4">
            <div>
              <label for="add_jam" class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-2">JP/Minggu <span class="text-red-500">*</span></label>
              <input type="number" name="add_jam" id="add_jam" required min="1" max="40" placeholder="Contoh: 4" class="w-full px-4 py-3 bg-white dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-xl text-sm text-gray-800 dark:text-white placeholder-gray-400 dark:placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[<?= $color['warna_primary'] ?>] transition-colors outline-none shadow-sm">
            </div>
            <div>
              <label for="add_tahun_ajaran" class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-2">Tahun Ajaran <span class="text-red-500">*</span></label>
              <input type="text" name="add_tahun_ajaran" id="add_tahun_ajaran" readonly value="<?= $tahunAjaranAktif ?>" class="w-full px-4 py-3 bg-gray-100 dark:bg-slate-900 border border-gray-200 dark:border-slate-600 rounded-xl text-sm text-gray-500 dark:text-slate-400 font-bold outline-none shadow-sm cursor-not-allowed">
            </div>
          </div>
          <div>
            <label for="add_catatan" class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-2">Catatan Tambahan (Opsional)</label>
            <textarea name="add_catatan" id="add_catatan" rows="3" class="w-full px-4 py-3 bg-white dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-xl text-sm text-gray-800 dark:text-white placeholder-gray-400 dark:placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[<?= $color['warna_primary'] ?>] resize-none transition-colors outline-none shadow-sm"></textarea>
          </div>
          <div class="flex gap-3 pt-4 border-t border-gray-100 dark:border-slate-700 mt-4 transition-colors">
            <button type="button" onclick="closeAddModal()" class="flex-1 px-6 py-3.5 bg-white dark:bg-slate-700 border border-gray-300 dark:border-slate-600 text-gray-700 dark:text-slate-300 font-bold rounded-xl hover:bg-gray-50 dark:hover:bg-slate-600 transition-colors cursor-pointer outline-none shadow-sm"> Batal </button>
            <button type="submit" id="btnSubmitAdd" class="flex-1 px-6 py-3.5 text-white font-bold rounded-xl shadow-lg transition-transform transform hover:-translate-y-0.5 cursor-pointer outline-none" style="background-color: <?= $color['warna_primary'] ?>; box-shadow: 0 10px 15px -3px <?= $color['warna_primary'] ?>40;"> Simpan Mapping </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<div id="deleteModal" class="fixed inset-0 hidden" style="z-index: 99999;">
  <div class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity pointer-events-auto" onclick="closeDeleteModal()"></div>
  <div class="absolute inset-0 flex items-center justify-center p-4 pointer-events-none md:pl-64">
    <div class="relative w-full bg-white dark:bg-slate-800 rounded-3xl shadow-2xl flex flex-col max-h-[90vh] pointer-events-auto border border-transparent dark:border-slate-700 transition-colors duration-300" style="max-width: 450px;">
      <div class="p-8 text-center relative z-10">
        <div class="w-20 h-20 bg-red-50 dark:bg-red-900/30 rounded-full flex items-center justify-center mx-auto mb-5 border-4 border-red-100 dark:border-red-800/50">
          <svg class="w-10 h-10 text-red-500 dark:text-red-400" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
        </div>
        <h3 class="text-2xl font-bold text-gray-800 dark:text-white mb-2">Hapus Mapping?</h3>
        <p class="text-sm font-medium text-gray-500 dark:text-slate-400 mb-8 px-4 leading-relaxed">Mapping guru untuk mata pelajaran dan kelas ini akan dihapus secara permanen dari sistem.</p>
        <div class="flex gap-3">
          <button onclick="closeDeleteModal()" class="flex-1 px-5 py-3 bg-white dark:bg-slate-700 border border-gray-300 dark:border-slate-600 text-gray-700 dark:text-slate-300 font-bold rounded-xl hover:bg-gray-50 dark:hover:bg-slate-600 transition-colors outline-none shadow-sm">Batal</button>
          <button onclick="confirmDelete()" class="flex-1 px-5 py-3 bg-red-600 hover:bg-red-700 text-white font-bold rounded-xl shadow-lg shadow-red-600/30 transition-transform transform hover:-translate-y-0.5 outline-none">Ya, Hapus!</button>
        </div>
      </div>
    </div>
  </div>
</div>

<div id="drawer-overlay" class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm z-[99998] hidden opacity-0 transition-opacity duration-300" onclick="closeDrawer()"></div>
<div id="detailDrawer" class="fixed top-0 right-0 h-full w-full sm:w-[420px] bg-white dark:bg-slate-800 shadow-2xl z-[99999] transform translate-x-full transition-transform duration-300 flex flex-col border-l border-gray-200 dark:border-slate-700">
  
  <div class="p-6 border-b border-gray-100 dark:border-slate-700 flex items-center justify-between bg-white dark:bg-slate-800 z-10 transition-colors">
    <h3 class="text-lg font-bold text-gray-800 dark:text-white flex items-center gap-2">
        <svg class="w-5 h-5 text-[<?= $color['warna_primary'] ?>]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"></path></svg>
        Kartu Identitas Mengajar
    </h3>
    <button onclick="closeDrawer()" class="p-2 text-gray-400 hover:text-gray-600 dark:hover:text-slate-300 hover:bg-gray-100 dark:hover:bg-slate-700 rounded-lg transition-colors cursor-pointer outline-none">
      <svg class="w-6 h-6" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
    </button>
  </div>

  <div class="p-6 space-y-6 overflow-y-auto flex-1 custom-scrollbar">
    <div class="text-center pb-6 border-b border-gray-100 dark:border-slate-700 transition-colors">
      <div id="drawerAvatarContainer" class="w-24 h-24 mx-auto rounded-3xl flex items-center justify-center font-bold text-3xl mb-4 shadow-lg border-4 border-white dark:border-slate-700 overflow-hidden bg-gradient-to-br from-[<?= $color['warna_primary'] ?>] to-[<?= $color['warna_primary'] ?>]/70 text-white">
        </div>
      <h4 id="drawerTeacherName" class="text-xl font-bold text-gray-800 dark:text-white mb-1">...</h4>
      <p id="drawerTeacherNIP" class="text-xs font-bold text-gray-500 dark:text-slate-400 font-mono tracking-wider bg-gray-100 dark:bg-slate-700 inline-block px-3 py-1 rounded-full mt-1">...</p>
    </div>
    
    <div class="space-y-4">
      <div class="grid grid-cols-2 gap-4">
        <div class="bg-gray-50 dark:bg-slate-900/50 p-4 rounded-xl border border-gray-100 dark:border-slate-700 transition-colors">
            <label class="text-[10px] font-bold text-gray-500 dark:text-slate-400 uppercase tracking-wider block mb-1">Mata Pelajaran</label>
            <p id="drawerMapel" class="text-gray-800 dark:text-white font-bold text-sm truncate">...</p>
        </div>
        <div class="bg-gray-50 dark:bg-slate-900/50 p-4 rounded-xl border border-gray-100 dark:border-slate-700 transition-colors">
            <label class="text-[10px] font-bold text-gray-500 dark:text-slate-400 uppercase tracking-wider block mb-1">Kode Mapel</label>
            <p id="drawerKodeMapel" class="text-[<?= $color['warna_primary'] ?>] font-black text-lg">...</p>
        </div>
      </div>
      
      <div class="bg-blue-50 dark:bg-blue-900/20 p-4 rounded-xl border border-blue-100 dark:border-blue-800/50 transition-colors">
        <div class="flex justify-between items-start mb-2">
            <label class="text-[10px] font-bold text-blue-600 dark:text-blue-400 uppercase tracking-wider block">Rombel Diajar (Terkait Mapel Ini)</label>
            <span id="drawerTotalRombel" class="bg-blue-600 text-white text-[10px] font-bold px-2 py-0.5 rounded-md">...</span>
        </div>
        <p id="drawerListRombel" class="text-blue-800 dark:text-blue-300 font-bold text-sm leading-relaxed">...</p>
      </div>

      <div class="grid grid-cols-1 gap-4">
        <div class="bg-gray-50 dark:bg-slate-900/50 p-4 rounded-xl border border-gray-100 dark:border-slate-700 transition-colors w-full">
            <label class="text-[10px] font-bold text-gray-500 dark:text-slate-400 uppercase tracking-wider block mb-3">Hari Mengajar (Kalender Jadwal)</label>
            <div id="drawerHari" class="w-full">
                </div>
        </div>
        <div class="grid grid-cols-2 gap-4">
            <div class="bg-gray-50 dark:bg-slate-900/50 p-4 rounded-xl border border-gray-100 dark:border-slate-700 transition-colors">
                <label class="text-[10px] font-bold text-gray-500 dark:text-slate-400 uppercase tracking-wider block mb-1">Beban Jam</label>
                <p id="drawerJam" class="text-gray-800 dark:text-white font-bold text-base">...</p>
            </div>
            <div class="bg-gray-50 dark:bg-slate-900/50 p-4 rounded-xl border border-gray-100 dark:border-slate-700 transition-colors">
                <label class="text-[10px] font-bold text-gray-500 dark:text-slate-400 uppercase tracking-wider block mb-1">Tahun Ajaran</label>
                <p id="drawerTahunAjaran" class="text-gray-800 dark:text-white font-bold text-base truncate">...</p>
            </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div id="toast-container" class="fixed top-6 right-6 z-[100000] flex flex-col gap-3 pointer-events-none"></div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
  const dbMappingData = <?= !empty($mappingData) ? json_encode($mappingData) : '[]' ?>;
  const BASE_URL = "<?= rtrim(base_url(), '/') ?>";
  const PRIMARY_COLOR = "<?= $color['warna_primary'] ?>";
</script>
<script src="<?= base_url('assets/js/Admin/mapping-mapel.js?v=' . time()) ?>"></script>
<?= $this->endSection() ?>