<?= $this->extend('layout/main') ?>

<?= $this->section('title') ?>
  Input Setoran Hafalan - Rapor Digital
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
    :root { --warna-scroll: <?= $color['warna_primary'] ?>; }
    html.dark .text-slate-900 { color: #f8fafc !important; }
    html.dark .text-slate-800 { color: #f1f5f9 !important; }
    html.dark .text-slate-700 { color: #cbd5e1 !important; }
    html.dark .text-slate-600 { color: #94a3b8 !important; }
    html.dark .text-slate-500 { color: #64748b !important; }
    html.dark .bg-slate-50 { background-color: #1e293b !important; }
    html.dark .bg-white { background-color: #0f172a !important; border-color: #334155 !important; }
    html.dark .border-slate-200, html.dark .border-slate-100 { border-color: #334155 !important; }
    html.dark input, html.dark select { color: white !important; }
    
    .custom-scrollbar::-webkit-scrollbar { height: 6px; width: 6px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: var(--warna-scroll); border-radius: 10px; }

    ::-webkit-scrollbar {
      width: 6px;
    }
    ::-webkit-scrollbar-track {
      background: transparent;
    }
    ::-webkit-scrollbar-thumb {
      background-color: var(--warna-scroll);
      border-radius: 3px;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div id="topSection" class="transition-all duration-500 origin-top">
    
    <div class="flex items-center gap-2 text-sm text-slate-500 dark:text-slate-400 mb-4 font-medium">
        <span>Tahfidz</span>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
        <span class="text-[<?= $color['warna_primary'] ?>] font-bold">Setoran Harian</span>
    </div>

    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-slate-900 dark:text-white mb-1">Input Setoran Hafalan</h1>
            <p class="text-slate-500 dark:text-slate-400 text-sm">Gunakan <span class="font-bold text-[<?= $color['warna_primary'] ?>]">Mode Fokus</span> untuk layar penuh & tekan <span class="font-bold text-[<?= $color['warna_primary'] ?>]">ALT + S</span> untuk simpan cepat.</p>
        </div>
        
        <div class="flex flex-col sm:flex-row items-end sm:items-center gap-3">
            <div class="bg-white dark:bg-slate-800 px-4 py-2 rounded-xl border border-slate-200 dark:border-slate-700 shadow-sm flex items-center gap-3">
                <div class="w-2 h-2 rounded-full bg-red-500 animate-pulse"></div>
                <span class="text-sm font-bold text-slate-700 dark:text-slate-300 tracking-wide" id="realtimeClock"><?= date('d M Y') ?></span>
            </div>
            
            <button onclick="openExcelModal()" class="px-5 py-2.5 bg-emerald-500 hover:bg-emerald-600 text-white text-sm font-bold rounded-xl shadow-sm transition-all flex items-center gap-2 outline-none">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                Import / Export Excel
            </button>
        </div>
    </div>

    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm p-5 mb-6">
        <div class="flex flex-col md:flex-row gap-4 items-end">
            <div class="w-full md:w-1/4">
                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Pilih Kelas</label>
                <select id="kelasSelect" onchange="loadSiswa()" class="w-full px-4 py-3 text-sm font-medium text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-600 rounded-xl focus:ring-2 focus:ring-[<?= $color['warna_primary'] ?>] bg-slate-50 dark:bg-slate-700 outline-none cursor-pointer">
                    <?php if(!empty($rombels)): ?>
                        <option value="">-- Pilih Kelas --</option>
                        <?php foreach($rombels as $r): ?>
                            <option value="<?= $r['id'] ?>" data-name="<?= esc($r['nama_rombel']) ?>">Kelas <?= esc($r['tingkat']) ?> - <?= esc($r['nama_rombel']) ?></option>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <option value="">-- Belum Membina Kelas --</option>
                    <?php endif; ?>
                </select>
            </div>
            
            <div class="w-full md:w-1/4">
                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Pilih Juz Target</label>
                <select id="juzSelect" onchange="loadSiswa()" class="w-full px-4 py-3 text-sm font-medium text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-600 rounded-xl focus:ring-2 focus:ring-[<?= $color['warna_primary'] ?>] bg-slate-50 dark:bg-slate-700 outline-none cursor-pointer">
                    <option value="">-- Pilih Juz --</option>
                    <?php if(!empty($list_juz)): ?>
                        <?php foreach($list_juz as $juz): ?>
                            <option value="<?= esc($juz['id']) ?>"><?= esc($juz['nama_juz']) ?></option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>

            <div class="w-full md:w-1/4">
                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Tanggal Setoran</label>
                <input type="date" id="tanggalSetoran" onchange="loadSiswa()" value="<?= date('Y-m-d') ?>" class="w-full px-4 py-3 text-sm font-medium text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-600 rounded-xl focus:ring-2 focus:ring-[<?= $color['warna_primary'] ?>] bg-slate-50 dark:bg-slate-700 outline-none cursor-pointer">
            </div>
            
            <div class="w-full md:w-1/4">
                <button type="button" onclick="loadSiswa()" class="w-full py-3 px-4 text-white rounded-xl font-bold flex items-center justify-center gap-2 shadow-md outline-none" style="background-color: <?= $color['warna_primary'] ?>;">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                    Refresh Data
                </button>
            </div>
        </div>
    </div>
</div> 

<div id="emptyState" class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm p-16 text-center transition-all duration-300">
    <div class="w-20 h-20 bg-slate-50 dark:bg-slate-700 rounded-full flex items-center justify-center mx-auto mb-5 border border-slate-100 dark:border-slate-600 shadow-inner">
        <svg class="w-10 h-10 text-slate-400 dark:text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477-4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
    </div>
    <h3 class="text-xl font-bold text-slate-800 dark:text-white mb-2">Tentukan Kelas & Juz Terlebih Dahulu</h3>
    <p class="text-slate-500 dark:text-slate-400 text-sm max-w-md mx-auto">Silakan pilih Kelas dan Juz Target dari dropdown di atas untuk memulai input hafalan.</p>
</div>

<div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-lg overflow-hidden transition-all duration-500" id="tableContainer" style="display: none;">
    <div class="p-4 border-b border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 flex justify-between items-center gap-4">
        <div class="relative w-full lg:w-1/3">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>
            <input type="text" id="searchInput" onkeyup="cariSantri()" placeholder="Cari nama santri di kelas ini..." class="w-full pl-10 pr-4 py-2.5 text-sm border border-slate-200 dark:border-slate-600 rounded-xl focus:ring-2 focus:ring-[<?= $color['warna_primary'] ?>] bg-slate-50 dark:bg-slate-700 dark:text-white transition-all shadow-sm outline-none">
        </div>
        <button type="button" onclick="toggleFocusMode()" id="btnFocus" class="px-5 py-2.5 bg-slate-800 dark:bg-slate-700 hover:bg-slate-900 text-white text-sm font-bold rounded-xl flex items-center justify-center gap-2 transition-all shadow-sm outline-none">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"></path></svg>
            <span id="textBtnFocus">Mode Fokus</span>
        </button>
    </div>
    
    <form id="formSetoran" class="relative">
        <script>
            const JUZ_DATA_DB = <?= json_encode($juz_data) ?>;
        </script>

        <div id="scrollTableWrapper" class="max-h-[55vh] overflow-y-auto overflow-x-auto custom-scrollbar transition-all duration-500">
            <table class="w-full text-left border-collapse min-w-[1000px]">
                <thead class="sticky top-0 z-20 backdrop-blur-xl bg-white/95 dark:bg-slate-800/95 shadow-sm">
                    <tr class="text-slate-500 dark:text-slate-400 text-sm border-b border-slate-200 dark:border-slate-700">
                        <th class="p-4 font-semibold w-12 text-center">No</th>
                        <th class="p-4 font-semibold w-64">Nama Santri</th>
                        <th class="p-4 font-semibold w-32">Jenis Setoran</th>
                        <th class="p-4 font-semibold w-56">Target Surah / Blok Ayat <span class="text-red-500">*</span></th>
                        <th class="p-4 font-semibold w-24 text-center">Nilai</th>
                        <th class="p-4 font-semibold w-40 text-center">Derajat & Taqdir</th>
                        <th class="p-4 font-semibold">Catatan (Opsional)</th>
                        <th class="p-4 font-semibold w-12 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody id="tbodySiswa" class="divide-y divide-slate-100 dark:divide-slate-700 bg-white dark:bg-slate-800">
                </tbody>
            </table>
        </div>
        
        <div class="p-5 bg-slate-50 dark:bg-slate-900 border-t border-slate-200 dark:border-slate-700 flex flex-col sm:flex-row justify-between items-center gap-4 shadow-[0_-10px_15px_-3px_rgba(0,0,0,0.05)] relative z-20">
            <p class="text-sm text-slate-500 dark:text-slate-400"><span class="font-bold text-red-500">*</span> Kosongkan kolom target surah untuk membatalkan setoran. <span class="hidden md:inline border-l border-slate-300 dark:border-slate-600 ml-2 pl-2">Tekan (ALT + S) untuk simpan cepat.</span></p>
            <button type="button" onclick="simpanSetoran()" class="w-full sm:w-auto py-3 px-8 text-white rounded-xl font-bold shadow-lg flex items-center justify-center gap-2 transition-all transform hover:-translate-y-1 outline-none" style="background-color: <?= $color['warna_primary'] ?>;">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                <span id="textBtnSaveAll">Simpan Data Setoran Kelas</span>
            </button>
        </div>
    </form>
</div>

<div id="modalExcel" class="fixed inset-0 z-50 hidden items-center justify-center">
    <div id="backdropExcel" onclick="closeExcelModal()" class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm opacity-0 transition-opacity duration-300"></div>
    <div id="cardExcel" class="relative bg-white dark:bg-slate-800 rounded-3xl w-full max-w-md p-6 shadow-2xl scale-95 opacity-0 transition-all duration-300 border border-slate-200 dark:border-slate-700">
        <div class="flex justify-between items-center mb-5">
            <h3 class="text-xl font-bold text-slate-800 dark:text-white flex items-center gap-2">
                <svg class="w-6 h-6 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                Menu Excel (CSV)
            </h3>
            <button onclick="closeExcelModal()" class="p-2 bg-slate-100 dark:bg-slate-700 hover:bg-rose-100 dark:hover:bg-rose-900/30 text-slate-500 hover:text-rose-500 rounded-xl transition-colors outline-none">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>

        <div class="space-y-4">
            <button onclick="downloadTemplate()" class="w-full flex items-center justify-between p-4 bg-emerald-50 dark:bg-emerald-900/20 hover:bg-emerald-100 dark:hover:bg-emerald-900/40 border border-emerald-200 dark:border-emerald-800 rounded-2xl transition-colors group outline-none">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-emerald-500 text-white rounded-xl flex items-center justify-center shadow-sm group-hover:scale-110 transition-transform"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg></div>
                    <div class="text-left"><p class="font-bold text-emerald-700 dark:text-emerald-400">1. Download Template</p><p class="text-xs text-emerald-600/70 dark:text-emerald-500">Unduh format Excel sesuai Juz</p></div>
                </div>
            </button>

            <div class="relative w-full p-4 border-2 border-dashed border-slate-300 dark:border-slate-600 rounded-2xl hover:border-blue-500 dark:hover:border-blue-400 transition-colors bg-slate-50 dark:bg-slate-900/50" id="dropzoneCSV">
                <input type="file" id="fileInputCSV" accept=".csv" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" onchange="handleFileSelect(event)">
                <div class="text-center pointer-events-none">
                    <svg class="w-8 h-8 mx-auto text-slate-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                    <p class="font-bold text-slate-700 dark:text-slate-300 text-sm">2. Import / Upload CSV</p>
                    <p class="text-xs text-slate-500 mt-1">Klik atau seret file CSV ke sini</p>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/exceljs/4.3.0/exceljs.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>

<script>
    const CONFIG = {
        primary_color: "<?= $color['warna_primary'] ?>",
        fetch_url: "<?= base_url('tahfidz/setoran/get-siswa') ?>",
        save_url: "<?= base_url('tahfidz/setoran/save') ?>",
        import_url: "<?= base_url('tahfidz/setoran/importCsv') ?>", // URL untuk import
        class_name: "" 
    };
    let globalStudents = [];
</script>
<script src="<?= base_url('assets/js/Tahfidz/setoran.js') ?>?v=<?= time() ?>"></script>
<?= $this->endSection() ?>