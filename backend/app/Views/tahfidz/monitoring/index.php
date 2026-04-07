<?= $this->extend('layout/main') ?>

<?= $this->section('title') ?>
  Klasemen Hafalan Santri - Rapor Digital
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
    :root {
        --warna-scroll: <?= $color['warna_primary'] ?>;
        --color-primary: <?= $color['warna_primary'] ?>;
        --color-primary-light: <?= $color['warna_primary'] ?>15;
        --color-primary-hover: <?= $color['warna_primary'] ?>E6;
    }
    .text-primary { color: var(--color-primary) !important; }
    .bg-primary { background-color: var(--color-primary) !important; }
    .bg-primary-light { background-color: var(--color-primary-light) !important; }
    .border-primary { border-color: var(--color-primary) !important; }
    .hover-bg-primary:hover { background-color: var(--color-primary-hover) !important; }
    .ring-primary { --tw-ring-color: var(--color-primary) !important; }
    .glow-primary { box-shadow: 0 4px 20px var(--color-primary-light); }
    
    .custom-table-scroll::-webkit-scrollbar { height: 8px; width: 8px; }
    .custom-table-scroll::-webkit-scrollbar-track { background: transparent; border-radius: 8px; }
    .custom-table-scroll::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 8px; }
    .custom-table-scroll::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
    
    html.dark .custom-table-scroll::-webkit-scrollbar-thumb { background: #475569; }

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
<link rel="stylesheet" href="<?= base_url('assets/css/GuruMapel/akun-saya.css') ?>">
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="mb-10 relative">
    
    <div class="flex items-center gap-2 text-sm text-slate-500 dark:text-slate-400 mb-5 font-medium tracking-wide">
        <span>Tahfidz</span>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
        <span class="font-bold text-primary">Monitoring & Klasemen</span>
    </div>

    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div>
            <h1 class="text-3xl md:text-4xl font-extrabold text-slate-900 dark:text-white mb-2 tracking-tight">Klasemen Tahfidz</h1>
            <p class="text-slate-500 dark:text-slate-400 text-sm md:text-base">Pantau riwayat pencapaian, intensitas muroja'ah, dan habit hafalan santri Anda.</p>
        </div>
        <div class="bg-[<?= $color['warna_primary'] ?>]/10 px-5 py-2.5 rounded-xl border border-[<?= $color['warna_primary'] ?>]/20 shadow-sm flex items-center gap-2">
            <span class="text-sm font-black text-[<?= $color['warna_primary'] ?>]"><?= $ta_info['semester'] ?? '-' ?> (<?= $ta_info['tahun'] ?? '-' ?>)</span>
        </div>
    </div>

    <div class="bg-white dark:bg-slate-800 rounded-3xl border border-slate-200/60 dark:border-slate-700 shadow-sm p-6 mb-8 relative overflow-hidden group">
        <div class="absolute right-0 top-0 w-32 h-32 bg-primary-light rounded-bl-full opacity-50 transition-transform group-hover:scale-110 duration-700"></div>
        <div class="flex justify-between flex-col md:flex-row gap-5 items-end relative z-10">
            <div class="w-full md:w-1/3">
                <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-2.5 flex items-center gap-2">
                    <svg class="w-4 h-4 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    Pilih Kelas Observasi
                </label>
                <select id="kelasSelect" onchange="loadMonitoring()" class="w-full px-5 py-3 text-sm font-semibold text-slate-700 dark:text-slate-200 border border-slate-200 dark:border-slate-600 rounded-xl focus:ring-2 focus:ring-primary focus:border-primary bg-slate-50 dark:bg-slate-900 hover:bg-white dark:hover:bg-slate-700 transition-all cursor-pointer shadow-inner outline-none">
                    <?php if(!empty($rombels)): ?>
                        <option value="">-- Pilih Kelas --</option>
                        <?php foreach($rombels as $r): ?>
                            <option value="<?= $r['id'] ?>">Kelas <?= esc($r['tingkat']) ?> - <?= esc($r['nama_rombel']) ?></option>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <option value="">-- Anda Belum Membina Kelas --</option>
                    <?php endif; ?>
                </select>
            </div>
            <div class="w-full md:w-1/3">
                <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-2.5 flex items-center gap-2">
                    <svg class="w-4 h-4 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                    Pilih Juz Target
                </label>
                <select id="juzSelect" onchange="loadMonitoring()" class="w-full px-5 py-3 text-sm font-semibold text-slate-700 dark:text-slate-200 border border-slate-200 dark:border-slate-600 rounded-xl focus:ring-2 focus:ring-primary focus:border-primary bg-slate-50 dark:bg-slate-900 hover:bg-white dark:hover:bg-slate-700 transition-all cursor-pointer shadow-inner outline-none">
                    <option value="">-- Pilih Juz --</option>
                    <?php if(!empty($list_juz)): ?>
                        <?php foreach($list_juz as $juz): ?>
                            <option value="<?= esc($juz) ?>"><?= esc($juz) ?></option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>
            <div class="w-full md:w-auto flex-shrink-0">
                <button type="button" onclick="loadMonitoring()" class="w-full md:w-auto py-3 px-8 bg-primary hover-bg-primary text-white outline-none rounded-xl font-bold flex items-center justify-center gap-2 transition-all shadow-md transform hover:-translate-y-1">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                    Tampilkan Analisis
                </button>
            </div>
        </div>
    </div>

    <div id="emptyState" class="bg-white dark:bg-slate-800 rounded-3xl border border-slate-200/60 dark:border-slate-700 shadow-sm p-20 text-center transition-all duration-300 mb-8">
        <div class="w-20 h-20 bg-primary-light rounded-full flex items-center justify-center mx-auto mb-6 border border-white dark:border-slate-700 shadow-sm glow-primary animate-pulse">
            <svg class="w-10 h-10 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
        </div>
        <h3 class="text-xl font-bold text-slate-800 dark:text-white mb-2">Area Tampilan Klasemen</h3>
        <p class="text-slate-500 dark:text-slate-400 text-sm max-w-sm mx-auto">Silakan pilih Kelas dan Juz Target terlebih dahulu untuk memuat data tabel.</p>
    </div>

    <div class="bg-white dark:bg-slate-800 rounded-3xl border border-slate-200/60 dark:border-slate-700 shadow-xl overflow-hidden relative transition-all duration-500 mb-10" id="tableContainer" style="display: none;">
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-0 border-b border-slate-100 dark:border-slate-700 bg-white dark:bg-slate-800">
            <div class="p-6 md:p-8 border-b md:border-b-0 md:border-r border-slate-100 dark:border-slate-700 flex items-center justify-between group cursor-default">
                <div>
                    <p class="text-[11px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-1.5">Total Siswa Aktif</p>
                    <p class="text-4xl font-black tracking-tight text-primary" id="statTotalSantri">0</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-slate-50 dark:bg-slate-900/50 text-slate-400 dark:text-slate-500 flex items-center justify-center group-hover:scale-110 group-hover:text-primary transition-all ring-1 ring-slate-100 dark:ring-slate-700">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                </div>
            </div>
            <div class="p-6 md:p-8 border-b md:border-b-0 md:border-r border-slate-100 dark:border-slate-700 flex items-center justify-between group cursor-default">
                <div>
                    <p class="text-[11px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-1.5">Total Setoran Per Juz</p>
                    <p class="text-4xl font-black tracking-tight text-primary" id="statTotalSetoran">0<span class="text-lg text-slate-400 dark:text-slate-500 font-bold ml-1">Kali</span></p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-slate-50 dark:bg-slate-900/50 text-slate-400 dark:text-slate-500 flex items-center justify-center group-hover:scale-110 group-hover:text-primary transition-all ring-1 ring-slate-100 dark:ring-slate-700">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                </div>
            </div>
            <div class="p-6 md:p-8 flex items-center justify-between group cursor-default">
                <div>
                    <p class="text-[11px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-1.5">Tingkat Keaktifan Juz</p>
                    <p class="text-4xl font-black tracking-tight text-primary" id="statKeaktifan">0<span class="text-lg text-slate-400 dark:text-slate-500 font-bold ml-1">%</span></p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-slate-50 dark:bg-slate-900/50 text-slate-400 dark:text-slate-500 flex items-center justify-center group-hover:scale-110 group-hover:text-primary transition-all ring-1 ring-slate-100 dark:ring-slate-700">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                </div>
            </div>
        </div>

        <div class="p-4 md:p-5 border-b border-slate-100 dark:border-slate-700 bg-slate-50/70 dark:bg-slate-900/50 flex flex-col xl:flex-row justify-between items-center gap-4">
            <div class="flex gap-2 overflow-x-auto w-full xl:w-auto pb-2 xl:pb-0 custom-table-scroll scroll-smooth">
                <button onclick="setPredikatFilter('semua', this)" class="btn-predikat active px-5 py-2.5 rounded-full text-xs font-bold transition-all bg-primary text-white shadow-sm ring-2 ring-primary ring-offset-2 dark:ring-offset-slate-800 whitespace-nowrap">Semua Predikat</button>
                <button onclick="setPredikatFilter('Sangat Lancar', this)" class="btn-predikat px-5 py-2.5 rounded-full text-xs font-bold transition-all bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700 ring-1 ring-slate-200 dark:ring-slate-600 shadow-sm whitespace-nowrap">Hanya Mumtaz</button>
                <button onclick="setPredikatFilter('Kurang Lancar', this)" class="btn-predikat px-5 py-2.5 rounded-full text-xs font-bold transition-all bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700 ring-1 ring-slate-200 dark:ring-slate-600 shadow-sm whitespace-nowrap">Butuh Bimbingan</button>
                <button onclick="setPredikatFilter('Belum Hafal', this)" class="btn-predikat px-5 py-2.5 rounded-full text-xs font-bold transition-all bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700 ring-1 ring-slate-200 dark:ring-slate-600 shadow-sm whitespace-nowrap">Evaluasi (Belum Hafal)</button>
            </div>

            <div class="flex flex-col sm:flex-row w-full xl:w-auto gap-3">
                <div class="relative w-full sm:w-48">
                    <select id="filterKeaktifan" onchange="applyMultiFilter()" class="w-full pl-4 pr-8 py-2.5 text-sm font-bold text-slate-600 dark:text-slate-300 border-0 ring-1 ring-slate-200 dark:ring-slate-600 rounded-full focus:ring-2 focus:ring-primary bg-white dark:bg-slate-800 cursor-pointer shadow-sm appearance-none outline-none">
                        <option value="semua">Semua Status</option>
                        <option value="aktif">Pernah Setor</option>
                        <option value="pasif">Pasif / Belum Setor</option>
                    </select>
                    <div class="absolute inset-y-0 right-0 flex items-center px-3 pointer-events-none text-slate-400 dark:text-slate-500">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </div>
                </div>

                <div class="relative w-full sm:w-64 flex-shrink-0">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg class="w-4 h-4 text-slate-400 dark:text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                    <input type="text" id="searchInput" onkeyup="applyMultiFilter()" placeholder="Cari nama santri..." class="w-full pl-11 pr-4 py-2.5 text-sm font-medium border-0 ring-1 ring-slate-200 dark:ring-slate-600 rounded-full focus:ring-2 focus:ring-primary bg-white dark:bg-slate-800 dark:text-white dark:placeholder-slate-400 transition-all shadow-inner outline-none">
                </div>
            </div>
        </div>
        
        <div class="max-h-[60vh] overflow-y-auto overflow-x-auto custom-table-scroll bg-slate-50/20 dark:bg-slate-900/20">
            <table class="w-full text-left border-collapse min-w-[1000px]">
                <thead class="sticky top-0 z-10 backdrop-blur-xl bg-white/95 dark:bg-slate-800/95 shadow-sm">
                    <tr class="text-slate-400 dark:text-slate-500 text-[11px] uppercase tracking-widest border-b border-slate-200 dark:border-slate-700">
                        <th class="px-5 py-4 font-extrabold w-12 text-center border-r border-slate-100 dark:border-slate-700">No</th>
                        <th class="px-5 py-4 font-extrabold w-72 border-r border-slate-100 dark:border-slate-700">Profil Santri</th>
                        <th class="px-5 py-4 font-extrabold w-32 border-r border-slate-100 dark:border-slate-700 text-center">Target Juz</th>
                        <th class="px-5 py-4 font-extrabold w-48 border-r border-slate-100 dark:border-slate-700 text-center">Progress Hafalan</th>
                        <th class="px-5 py-4 font-extrabold w-40 border-r border-slate-100 dark:border-slate-700 text-center">Statistik Setoran</th>
                        <th class="px-5 py-4 font-extrabold w-64 border-r border-slate-100 dark:border-slate-700">Capaian Terakhir</th>
                        <th class="px-5 py-4 font-extrabold w-32 text-center">Predikat</th>
                    </tr>
                </thead>
                <tbody id="tbodyMonitoring" class="divide-y divide-slate-100 dark:divide-slate-700 bg-white dark:bg-slate-800">
                </tbody>
            </table>
        </div>
    </div>

    <div id="modalRiwayat" class="fixed inset-0 z-[100] hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div id="modalBackdrop" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity opacity-0 duration-300" onclick="tutupModal()"></div>

        <div class="fixed inset-0 z-10 overflow-y-auto">
            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <div id="modalPanel" class="relative transform overflow-hidden rounded-[2rem] bg-slate-50 dark:bg-slate-800 text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-2xl opacity-0 translate-y-8 sm:translate-y-0 sm:scale-95 duration-300">
                    
                    <div class="bg-primary relative overflow-hidden px-8 py-6">
                        <div class="absolute -right-10 -top-10 w-40 h-40 bg-white rounded-full opacity-10 blur-2xl"></div>
                        <div class="absolute -left-5 -bottom-5 w-24 h-24 bg-black rounded-full opacity-10 blur-xl"></div>
                        
                        <div class="relative z-10 flex justify-between items-start">
                            <div class="flex items-center gap-5">
                                <div id="modalAvatar" class="w-16 h-16 rounded-2xl bg-white/20 border border-white/30 flex items-center justify-center text-white font-black text-2xl shadow-inner backdrop-blur-md">
                                </div>
                                <div>
                                    <h3 class="text-2xl font-extrabold text-white tracking-tight mb-1" id="modalNamaSantri">Memuat Profil...</h3>
                                    <p class="text-[11px] text-white/80 font-bold uppercase tracking-widest bg-black/10 inline-block px-3 py-1 rounded-lg backdrop-blur-sm border border-white/10">Timeline 10 Setoran Terakhir Per Juz</p>
                                </div>
                            </div>
                            <button type="button" onclick="tutupModal()" class="rounded-full p-2 text-white/70 hover:bg-white/20 hover:text-white transition-all outline-none">
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                            </button>
                        </div>
                    </div>

                    <div class="px-8 py-8 max-h-[65vh] overflow-y-auto custom-table-scroll bg-slate-50 dark:bg-slate-800 relative">
                        <div id="loadingModal" class="flex flex-col items-center justify-center py-16 hidden">
                            <div class="w-12 h-12 border-4 border-slate-200 dark:border-slate-600 border-t-primary rounded-full animate-spin mb-4"></div>
                            <p class="text-sm font-bold text-slate-500 dark:text-slate-400 animate-pulse tracking-wide">Menganalisis Riwayat...</p>
                        </div>
                        
                        <div id="timelineContainer" class="relative border-l-2 border-slate-200/80 dark:border-slate-700 ml-4 space-y-8 pb-4">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    const CONFIG = {
        primary_color: "<?= $color['warna_primary'] ?>",
        fetch_url: "<?= base_url('tahfidz/monitoring/getData') ?>",
        riwayat_url: "<?= base_url('tahfidz/monitoring/getRiwayat') ?>"
    };
    
    const BASE_URL = '<?= rtrim(base_url(), '/') ?>';
</script>
<script>
    const JUZ_DATA_DB = <?= json_encode($juz_data) ?>;
</script>
<script src="<?= base_url('assets/js/Tahfidz/monitoring.js') ?>?v=<?= time() ?>"></script>
<?= $this->endSection() ?>