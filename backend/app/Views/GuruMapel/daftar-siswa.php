<?= $this->extend('layout/main') ?>

<?= $this->section('title') ?>
Daftar Siswa - Rapor Digital
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
    :root {
        --warna-scroll: <?= $color['warna_primary'] ?>;
    }

    .student-table tbody tr:hover {
        background-color: <?= $color['warna_primary'] ?>1A !important;
    }

    .dark .student-table tbody tr:hover {
        background-color: <?= $color['warna_primary'] ?>33 !important;
    }

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
<div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div>
        <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:!text-white mb-2 transition-colors" id="pageTitle">Daftar Siswa</h1>
        <p class="text-base text-gray-600 dark:!text-slate-400 font-medium transition-colors" id="pageSubtitle">Lihat daftar siswa yang terdaftar di kelas dan mata pelajaran yang Anda ampu.</p>
    </div>

    <div class="w-full md:w-auto">
        <label class="block text-[10px] font-black text-gray-500 dark:!text-slate-400 uppercase tracking-widest mb-1 ml-1">Pindah Kelas / Mapel</label>
        <select onchange="window.location.href='?rombel='+this.value.split('|')[0]+'&mapel='+this.value.split('|')[1]"
            class="w-full md:w-64 p-3 bg-white dark:!bg-slate-800 border-2 border-gray-200 dark:!border-slate-700 rounded-xl font-bold text-sm text-gray-800 dark:!text-white outline-none focus:border-[<?= $color['warna_primary'] ?>] transition-colors cursor-pointer shadow-sm">
            <?php if (!empty($allRombel)): ?>
                <?php foreach ($allRombel as $rb): ?>
                    <option value="<?= $rb['rombel_id'] ?>|<?= $rb['mapel_id'] ?>" <?= ($rb['rombel_id'] == $info['rombel_id'] && $rb['mapel_id'] == $info['mapel_id']) ? 'selected' : '' ?>>
                        <?= esc($rb['nama_rombel']) ?> - <?= esc($rb['nama_mapel']) ?>
                    </option>
                <?php endforeach; ?>
            <?php else: ?>
                <option value="">Belum Ada Kelas</option>
            <?php endif; ?>
        </select>
    </div>
</div>

<div class="info-card bg-[<?= $color['warna_secondary'] ?>] dark:!bg-slate-800 border border-[<?= $color['warna_primary'] ?>]/80 dark:!border-slate-700 mb-6 shadow-sm transition-colors rounded-2xl p-5">
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 rounded-xl bg-emerald-500 dark:!bg-emerald-600 flex items-center justify-center shadow-lg flex-shrink-0 transition-colors">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewbox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                </svg>
            </div>
            <div class="min-w-0 pr-2">
                <p class="text-xs text-[<?= $color['warna_primary'] ?>] dark:!text-[<?= $color['warna_primary'] ?>] font-black uppercase tracking-wider mb-0.5 transition-colors">MATA PELAJARAN</p>
                <p class="text-base font-bold text-[<?= $color['warna_primary'] ?>]/80 dark:!text-white truncate transition-colors"><?= $info['mapel'] ?></p>
            </div>
        </div>

        <div class="flex items-center gap-3">
            <div class="w-12 h-12 rounded-xl bg-blue-500 dark:!bg-blue-600 flex items-center justify-center shadow-lg flex-shrink-0 transition-colors">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewbox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
            </div>
            <div class="min-w-0 pr-2">
                <p class="text-xs text-[<?= $color['warna_primary'] ?>] dark:!text-[<?= $color['warna_primary'] ?>] font-black uppercase tracking-wider mb-0.5 transition-colors">KELAS & ROMBEL</p>
                <p class="text-base font-bold text-[<?= $color['warna_primary'] ?>]/80 dark:!text-white truncate transition-colors"><?= $info['rombel'] ?></p>
            </div>
        </div>

        <div class="flex items-center gap-3">
            <div class="w-12 h-12 rounded-xl bg-purple-500 dark:!bg-purple-600 flex items-center justify-center shadow-lg flex-shrink-0 transition-colors">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewbox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
            </div>
            <div class="min-w-0 pr-2">
                <p class="text-xs text-[<?= $color['warna_primary'] ?>] dark:!text-[<?= $color['warna_primary'] ?>] font-black uppercase tracking-wider mb-0.5 transition-colors">JUMLAH SISWA</p>
                <p class="text-base font-bold text-[<?= $color['warna_primary'] ?>]/80 dark:!text-white truncate transition-colors"><?= $info['jumlah_siswa'] ?> Siswa</p>
            </div>
        </div>

        <div class="flex items-center gap-3">
            <div class="w-12 h-12 rounded-xl bg-amber-500 dark:!bg-amber-600 flex items-center justify-center shadow-lg flex-shrink-0 transition-colors">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewbox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div class="min-w-0 pr-2">
                <p class="text-xs text-[<?= $color['warna_primary'] ?>] dark:!text-[<?= $color['warna_primary'] ?>] font-black uppercase tracking-wider mb-0.5 transition-colors">JAM MENGAJAR</p>
                <p class="text-base font-bold text-[<?= $color['warna_primary'] ?>]/80 dark:!text-white truncate transition-colors"><?= $info['jam_mengajar'] ?></p>
            </div>
        </div>
    </div>
</div>

<div class="bg-white dark:!bg-slate-800 rounded-2xl shadow-sm border border-gray-200 dark:!border-slate-700 p-5 mb-6 transition-colors">
    <div class="relative max-w-md">
        <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400 dark:!text-slate-500" fill="none" stroke="currentColor" viewbox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
        </svg>
        <input type="text" id="searchInput" placeholder="Cari NIS atau Nama Siswa..." class="search-input w-full pl-10 pr-4 py-3 bg-gray-50 dark:!bg-slate-700 border border-gray-200 dark:!border-slate-600 rounded-xl focus:border-[<?= $color['warna_primary'] ?>] text-gray-900 dark:!text-white outline-none transition-colors" onkeyup="filterStudents()">
    </div>
</div>

<div class="bg-white dark:!bg-slate-800 rounded-2xl shadow-sm border border-gray-200 dark:!border-slate-700 overflow-hidden transition-colors">
    <div class="overflow-x-auto custom-scrollbar">
        <table class="student-table w-full text-left border-collapse min-w-max">
            <thead class="bg-[<?= $color['warna_primary'] ?>]/90 dark:!bg-slate-900 border-b border-[<?= $color['warna_primary'] ?>] dark:!border-slate-700 transition-colors">
                <tr>
                    <th class="px-6 py-4 text-white font-black text-[11px] uppercase tracking-widest text-center" style="width: 60px;">NO</th>
                    <th class="px-6 py-4 text-white font-black text-[11px] uppercase tracking-widest text-center" style="width: 80px;">FOTO</th>
                    <th class="px-6 py-4 text-white font-black text-[11px] uppercase tracking-widest" style="width: 150px;">NIS / NISN</th>
                    <th class="px-6 py-4 text-white font-black text-[11px] uppercase tracking-widest">NAMA LENGKAP SISWA</th>
                    <th class="px-6 py-4 text-white font-black text-[11px] uppercase tracking-widest text-center" style="width: 150px;">JENIS KELAMIN</th>
                </tr>
            </thead>
            <tbody id="studentTableBody" class="divide-y divide-gray-100 dark:!divide-slate-700/50 transition-colors">
            </tbody>
        </table>
    </div>

    <div id="paginationContainer" class="px-6 py-5 border-t border-gray-200 dark:!border-slate-700 bg-gray-50 dark:!bg-slate-900/50 flex items-center justify-between hidden transition-colors">
        <div class="text-sm font-medium text-gray-500 dark:!text-slate-400 transition-colors">
            Menampilkan <span id="pageStart" class="font-bold text-gray-900 dark:!text-white">0</span> - <span id="pageEnd" class="font-bold text-gray-900 dark:!text-white">0</span> dari <span id="pageTotal" class="font-bold text-gray-900 dark:!text-white">0</span> Siswa
        </div>
        <div class="flex gap-2">
            <button id="btnPrevPage" onclick="changePage(-1)" class="px-4 py-2.5 rounded-xl border border-gray-300 dark:!border-slate-600 bg-white dark:!bg-slate-800 text-gray-700 dark:!text-slate-300 hover:bg-gray-50 dark:hover:!bg-slate-700 disabled:opacity-50 disabled:cursor-not-allowed transition-colors text-sm font-bold outline-none">
                Sebelumnya
            </button>
            <div id="pageNumbers" class="flex gap-1"></div>
            <button id="btnNextPage" onclick="changePage(1)" class="px-4 py-2.5 rounded-xl border border-gray-300 dark:!border-slate-600 bg-white dark:!bg-slate-800 text-gray-700 dark:!text-slate-300 hover:bg-gray-50 dark:hover:!bg-slate-700 disabled:opacity-50 disabled:cursor-not-allowed transition-colors text-sm font-bold outline-none">
                Selanjutnya
            </button>
        </div>
    </div>
</div>

<div id="emptyState" class="empty-state hidden bg-white dark:!bg-slate-800 rounded-3xl border border-gray-200 dark:!border-slate-700 py-16 flex flex-col items-center justify-center text-center shadow-sm transition-colors mt-6">
    <div class="w-24 h-24 bg-gray-50 dark:!bg-slate-900/50 rounded-full flex items-center justify-center mb-4 border-2 border-dashed border-gray-200 dark:!border-slate-600 transition-colors">
        <svg class="w-12 h-12 text-gray-300 dark:!text-slate-500 transition-colors" fill="none" stroke="currentColor" viewbox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
        </svg>
    </div>
    <h3 class="text-xl font-bold text-gray-700 dark:!text-white mb-2 transition-colors">Tidak ada data</h3>
    <p class="text-gray-500 dark:!text-slate-400 font-medium transition-colors">Tidak ada siswa yang ditemukan di kelas ini.</p>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    const URL_GET_DATA = "<?= base_url('guru/daftar-siswa/get-data') ?>";
    const ACTIVE_ROMBEL_ID = <?= $info['rombel_id'] ?? 0 ?>;
    const ACTIVE_MAPEL_ID = <?= $info['mapel_id'] ?? 0 ?>;
    const THEME_PRIMARY_COLOR = "<?= $color['warna_primary'] ?? '#3b82f6' ?>";
    const BASE_URL = "<?= base_url() ?>";
</script>
<script src="<?= base_url('assets/js/GuruMapel/daftar-siswa.js') ?>?v=<?= time() ?>"></script>
<?= $this->endSection() ?>