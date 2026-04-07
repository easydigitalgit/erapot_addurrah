<?= $this->extend('layout/main') ?>

<?= $this->section('title') ?>
  Validasi Catatan Guru - Rapor Digital
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
  <link rel="stylesheet" href="<?= base_url('assets/css/WaliKelas/validasi-catatan-guru.css') ?>">
  <style>
    /* Injeksi Warna Dinamis dari Database */
    :root {
      --warna-primary: <?= $color['warna_primary'] ?? '#10b981' ?>;
      --warna-secondary: <?= $color['warna_secondary'] ?? '#ecfdf5' ?>;
      --warna-scroll: <?= $color['warna_primary'] ?>; 
    }
    
    .text-tema { color: var(--warna-primary) !important; }
    .bg-tema { background-color: var(--warna-primary) !important; }
    .bg-tema-light { background-color: var(--warna-secondary) !important; }
    .border-tema { border-color: var(--warna-primary) !important; }
    .focus-tema:focus { border-color: var(--warna-primary) !important; outline: none; box-shadow: 0 0 0 3px color-mix(in srgb, var(--warna-primary) 20%, transparent) !important; }
    
    .validasi-tab-active { border-bottom: 2px solid var(--warna-primary) !important; color: var(--warna-primary) !important; }
    .validasi-tab-inactive { border-bottom: 2px solid transparent; }

    /* Dark Mode Overrides */
    html.dark .text-tema { color: color-mix(in srgb, var(--warna-primary) 80%, white) !important; }
    html.dark .bg-tema-light { background-color: rgba(255, 255, 255, 0.05) !important; }
    html.dark .bg-white { background-color: #1e293b !important; border-color: #334155 !important; }
    html.dark .text-gray-800 { color: #f1f5f9 !important; }
    html.dark .text-gray-600 { color: #cbd5e1 !important; }
    html.dark .text-gray-500 { color: #94a3b8 !important; }
    html.dark .bg-gray-50 { background-color: #0f172a !important; }
    html.dark .bg-gray-100 { background-color: #1e293b !important; }
    html.dark .border-gray-100, html.dark .border-gray-200 { border-color: #334155 !important; }
    
    html.dark .bg-blue-50 { background-color: rgba(59, 130, 246, 0.1) !important; }
    html.dark .bg-amber-50 { background-color: rgba(245, 158, 11, 0.1) !important; }
    html.dark .bg-emerald-50 { background-color: rgba(16, 185, 129, 0.1) !important; }
    html.dark .bg-red-50 { background-color: rgba(239, 68, 68, 0.1) !important; }

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
<div id="validasiContainer">

    <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-gray-100 dark:border-slate-700 p-4 lg:p-5 mb-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="font-semibold text-gray-800 dark:text-slate-100">Filter Pencarian Catatan</h2>
            <button onclick="resetFilter()" class="text-xs font-semibold text-tema hover:underline px-3 py-1.5 bg-tema-light dark:bg-slate-700 rounded-lg transition-all">Setel Ulang</button>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            <div>
                <label class="text-xs font-bold text-gray-600 dark:text-slate-400 mb-1.5 block">Cari Nama Siswa</label>
                <div class="relative">
                    <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <input type="text" id="searchStudent" placeholder="Ketik nama siswa..." class="w-full pl-10 pr-3 py-2.5 bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-600 rounded-xl text-sm text-gray-800 dark:text-slate-200 focus-tema transition-all" onkeyup="filterValidasiCatatan()">
                </div>
            </div>
            <div>
                <label class="text-xs font-bold text-gray-600 dark:text-slate-400 mb-1.5 block">Kategori Catatan</label>
                <select id="filterCategory" class="w-full px-3 py-2.5 bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-600 rounded-xl text-sm text-gray-800 dark:text-slate-200 focus-tema transition-all cursor-pointer" onchange="filterValidasiCatatan()">
                    <option value="">Semua Kategori</option>
                    <option value="Akademik">Akademik</option>
                    <option value="Perilaku">Perilaku / Karakter</option>
                    <option value="Prestasi">Prestasi</option>
                </select>
            </div>
            <div>
                <label class="text-xs font-bold text-gray-600 dark:text-slate-400 mb-1.5 block">Urutkan Berdasarkan</label>
                <select id="sortValidasi" class="w-full px-3 py-2.5 bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-600 rounded-xl text-sm text-gray-800 dark:text-slate-200 focus-tema transition-all cursor-pointer" onchange="filterValidasiCatatan()">
                    <option value="date-new">Tanggal (Terbaru ke Lama)</option>
                    <option value="date-old">Tanggal (Terlama ke Baru)</option>
                    <option value="student">Nama Siswa (A - Z)</option>
                </select>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 lg:gap-4 mb-6">
        <div class="bg-white dark:bg-slate-800 rounded-xl p-4 shadow-sm border border-gray-100 dark:border-slate-700 hover:shadow-md transition-shadow group">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs lg:text-sm text-gray-500 dark:text-slate-400 mb-1 font-medium">Total Catatan</p>
                    <p class="text-2xl lg:text-3xl font-bold text-gray-800 dark:text-white" id="statTotal">0</p>
                </div>
                <div class="w-10 h-10 lg:w-12 lg:h-12 rounded-xl bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 flex items-center justify-center group-hover:scale-110 transition-transform">
                    <svg class="w-5 h-5 lg:w-6 lg:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-slate-800 rounded-xl p-4 shadow-sm border border-gray-100 dark:border-slate-700 hover:shadow-md transition-shadow group">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs lg:text-sm text-gray-500 dark:text-slate-400 mb-1 font-medium">Menunggu</p>
                    <p class="text-2xl lg:text-3xl font-bold text-amber-500" id="statMenunggu">0</p>
                </div>
                <div class="w-10 h-10 lg:w-12 lg:h-12 rounded-xl bg-amber-50 dark:bg-amber-900/30 text-amber-500 flex items-center justify-center group-hover:scale-110 transition-transform">
                    <svg class="w-5 h-5 lg:w-6 lg:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-slate-800 rounded-xl p-4 shadow-sm border border-gray-100 dark:border-slate-700 hover:shadow-md transition-shadow group">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs lg:text-sm text-gray-500 dark:text-slate-400 mb-1 font-medium">Disetujui</p>
                    <p class="text-2xl lg:text-3xl font-bold text-emerald-600 dark:text-emerald-500" id="statDisetujui">0</p>
                </div>
                <div class="w-10 h-10 lg:w-12 lg:h-12 rounded-xl bg-emerald-50 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-500 flex items-center justify-center group-hover:scale-110 transition-transform">
                    <svg class="w-5 h-5 lg:w-6 lg:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-slate-800 rounded-xl p-4 shadow-sm border border-gray-100 dark:border-slate-700 hover:shadow-md transition-shadow group">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs lg:text-sm text-gray-500 dark:text-slate-400 mb-1 font-medium">Ditolak</p>
                    <p class="text-2xl lg:text-3xl font-bold text-red-600 dark:text-red-500" id="statDitolak">0</p>
                </div>
                <div class="w-10 h-10 lg:w-12 lg:h-12 rounded-xl bg-red-50 dark:bg-red-900/30 text-red-600 dark:text-red-500 flex items-center justify-center group-hover:scale-110 transition-transform">
                    <svg class="w-5 h-5 lg:w-6 lg:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10 14l-2-2m0 0l-2-2m2 2l2-2m-2 2l-2 2"/></svg>
                </div>
            </div>
        </div>
    </div>

    <div id="validasiTabsContainer"></div>

</div>
<?= $this->endSection() ?>

<?= $this->section('modals') ?>
<div id="rejectModal" class="fixed inset-0 z-[60] hidden flex items-center justify-center p-4">
    <div class="modal-overlay absolute inset-0 bg-slate-900/70 backdrop-blur-sm transition-opacity" onclick="closeRejectModal()"></div>
    <div class="modal-content relative bg-white dark:bg-slate-800 rounded-3xl shadow-2xl w-full max-w-md overflow-hidden transform scale-95 transition-all">
        <div class="bg-red-600 p-5 text-white flex items-center justify-between shadow-md">
            <h2 class="text-lg font-bold flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                Tolak / Kembalikan Catatan
            </h2>
            <button onclick="closeRejectModal()" class="p-1.5 bg-white/20 hover:bg-white/30 rounded-lg transition-colors"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg></button>
        </div>
        <div class="p-6">
            <input type="hidden" id="rejectCatatanId">
            <label class="text-sm font-bold text-gray-700 dark:text-slate-300 mb-2 block">Berikan alasan penolakan untuk Guru:</label>
            <textarea id="rejectReason" class="w-full px-4 py-3 bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-700 rounded-xl focus:outline-none focus:border-red-500 focus:ring-2 focus:ring-red-500/20 text-gray-800 dark:text-slate-200 resize-none h-28 placeholder:text-gray-400" placeholder="Misal: Kalimat kurang memotivasi siswa, mohon gunakan bahasa yang lebih positif..."></textarea>
        </div>
        <div class="p-5 border-t border-gray-100 dark:border-slate-700 bg-gray-50/50 dark:bg-slate-900/50 flex gap-3">
            <button onclick="closeRejectModal()" class="flex-1 py-2.5 border border-gray-300 dark:border-slate-600 text-gray-600 dark:text-slate-300 font-bold rounded-xl hover:bg-gray-100 dark:hover:bg-slate-700 transition-colors">Batal</button>
            <button onclick="submitReject()" class="flex-1 py-2.5 bg-red-600 text-white font-bold rounded-xl hover:bg-red-700 transition-colors shadow-lg shadow-red-500/30">Kirim Penolakan</button>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
  <script>
    // Jembatan Data PHP ke JS
    window.dynamicCatatanData = <?= $catatanGuruData ?? '[]' ?>;
  </script>
  <script src="<?= base_url('assets/js/WaliKelas/validasi-catatan-guru.js') ?>"></script>
<?= $this->endSection() ?>