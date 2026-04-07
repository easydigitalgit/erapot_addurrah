<?= $this->extend('layout/main') ?>

<?= $this->section('title') ?>
Sinkronisasi Nilai Rapor - Guru Mapel
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
    :root {
        --warna-primary: <?= $color['warna_primary'] ?? '#3b82f6' ?>;
        --warna-secondary: <?= $color['warna_secondary'] ?? '#eff6ff' ?>;
        --warna-scroll: <?= $color['warna_primary'] ?>;
    }

    .text-tema {
        color: var(--warna-primary) !important;
    }

    .bg-tema {
        background-color: var(--warna-primary) !important;
    }

    .border-tema {
        border-color: var(--warna-primary) !important;
    }

    .focus-tema:focus {
        border-color: var(--warna-primary) !important;
        box-shadow: 0 0 0 3px color-mix(in srgb, var(--warna-primary) 20%, transparent) !important;
        outline: none;
    }

    html.dark .text-tema {
        color: color-mix(in srgb, var(--warna-primary) 80%, white) !important;
    }

    html.dark .bg-white {
        background-color: #1e293b !important;
        border-color: #334155 !important;
    }

    html.dark .text-gray-800 {
        color: #f1f5f9 !important;
    }

    html.dark .text-gray-500 {
        color: #94a3b8 !important;
    }

    html.dark .bg-gray-50 {
        background-color: #0f172a !important;
    }

    html.dark .border-gray-100 {
        border-color: #334155 !important;
    }

    html.dark .border-gray-200 {
        border-color: #475569 !important;
    }

    html.dark .custom-scrollbar::-webkit-scrollbar-thumb {
        background-color: var(--warna-primary);
    }

    ::-webkit-scrollbar {
      width: 8px;
      height: 8px;
    }
    
    ::-webkit-scrollbar-track {
      background: #f1f1f1;
    }
    
    ::-webkit-scrollbar-thumb {
      background-color: var(--warna-scroll);
      border-radius: 4px;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div id="base-url" data-url="<?= rtrim(base_url(), '/') ?>/"></div>

<div class="mb-6">
    <div class="flex items-center gap-2 text-sm text-gray-500 dark:text-slate-400 mb-2">
        <span>Guru Mapel</span>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>
        <span class="text-tema font-medium">Nilai Akhir Rapor</span>
    </div>

    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-xl md:text-2xl font-bold text-gray-800 dark:text-white">Kalkulasi & Sinkronisasi Nilai Rapor</h1>
            <p class="text-sm md:text-base text-gray-600 dark:text-slate-400 mt-1">Mengambil rekap dari nilai formatif dan sumatif sesuai bobot rumus secara otomatis.</p>
        </div>

        <div class="flex items-center gap-3">
            <button id="btnSync" onclick="window.syncNilai()" disabled class="px-6 py-3 text-white font-bold rounded-2xl transition-all shadow-lg shadow-[var(--warna-primary)]/30 hover:-translate-y-0.5 flex items-center justify-center gap-2 bg-tema disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:translate-y-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                </svg>
                Sinkronisasi Kelas Ini
            </button>
        </div>
    </div>
</div>

<div class="bg-white dark:bg-slate-800 rounded-3xl p-5 md:p-6 shadow-sm border border-gray-100 dark:border-slate-700 mb-6">
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div>
            <label class="block text-sm font-bold text-gray-700 dark:text-slate-300 mb-2">Kategori Rapor</label>
            <select id="filterKategori" class="w-full px-4 py-3 bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-700 rounded-xl text-sm font-bold text-gray-800 dark:text-slate-200 focus-tema cursor-pointer transition-all">
                <option value="Tengah Semester">Rapor Tengah Semester</option>
                <option value="Akhir Semester" selected>Rapor Akhir Semester</option>
            </select>
        </div>
        <div>
            <label class="block text-sm font-bold text-gray-700 dark:text-slate-300 mb-2">Tahun Ajaran</label>
            <select id="filterTahunAjaran" class="w-full px-4 py-3 bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-700 rounded-xl text-sm font-bold text-gray-800 dark:text-slate-200 focus-tema cursor-pointer transition-all">
                <option value="">-- Pilih Tahun Ajaran --</option>
                <?php foreach ($tahun_ajaran_list as $ta): ?>
                    <?php
                    $status_badge = $ta['status'] === 'Aktif' ? ' (AKTIF)' : '';
                    $is_selected = $ta['id'] == $id_ta_aktif ? 'selected' : '';
                    ?>
                    <option value="<?= $ta['id'] ?>" <?= $is_selected ?>>
                        <?= $ta['tahun'] ?> - Semester <?= $ta['semester'] ?><?= $status_badge ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div>
            <label class="block text-sm font-bold text-gray-700 dark:text-slate-300 mb-2">Kelas (Rombel)</label>
            <select id="filterRombel" disabled class="w-full px-4 py-3 bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-700 rounded-xl text-sm font-bold text-gray-800 dark:text-slate-200 focus-tema cursor-pointer transition-all disabled:opacity-60 disabled:cursor-not-allowed">
                <option value="">-- Pilih Tahun Ajaran Dulu --</option>
            </select>
        </div>
        <div>
            <label class="block text-sm font-bold text-gray-700 dark:text-slate-300 mb-2">Mata Pelajaran</label>
            <select id="filterMapel" disabled class="w-full px-4 py-3 bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-700 rounded-xl text-sm font-bold text-gray-800 dark:text-slate-200 focus-tema cursor-pointer transition-all disabled:opacity-60 disabled:cursor-not-allowed">
                <option value="">-- Pilih Kelas Dulu --</option>
            </select>
        </div>
    </div>
</div>

<div class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-gray-100 dark:border-slate-700 overflow-hidden">
    <div class="overflow-x-auto custom-scrollbar">
        <table class="w-full text-left border-collapse whitespace-nowrap">
            <thead class="bg-gray-50/80 dark:bg-slate-900/50 backdrop-blur-sm border-b border-gray-200 dark:border-slate-700">
                <tr>
                    <th rowspan="2" class="px-6 py-4 text-xs font-bold text-gray-500 dark:text-slate-400 uppercase tracking-widest min-w-[250px] border-r border-gray-100 dark:border-slate-700 align-middle">Nama Siswa</th>
                    <th colspan="2" class="px-4 py-2 text-center text-xs font-bold text-blue-600 dark:text-blue-400 uppercase tracking-widest bg-blue-50/50 dark:bg-blue-900/10 border-b border-r border-gray-100 dark:border-slate-700">FORMATIF</th>
                    <th id="headerSumatif" colspan="2" class="px-4 py-2 text-center text-xs font-bold text-amber-600 dark:text-amber-400 uppercase tracking-widest bg-amber-50/50 dark:bg-amber-900/10 border-b border-r border-gray-100 dark:border-slate-700">SUMATIF</th>
                    <th rowspan="2" class="px-6 py-4 text-center text-xs font-bold text-emerald-600 dark:text-emerald-400 uppercase tracking-widest bg-emerald-50/50 dark:bg-emerald-900/10 border-r border-gray-100 dark:border-slate-700 align-middle">Nilai Akhir Rapor</th>
                    <th rowspan="2" class="px-6 py-4 text-center text-xs font-bold text-gray-400 dark:text-slate-500 uppercase tracking-widest bg-gray-50 dark:bg-slate-800 align-middle">Status</th>
                </tr>
                <tr>
                    <th class="px-2 py-2 text-center text-[10px] font-bold text-blue-500 bg-blue-50/20">Rata NH</th>
                    <th class="px-2 py-2 text-center text-[10px] font-bold text-blue-500 bg-blue-50/20 border-r border-gray-100 dark:border-slate-700">Rata UH</th>
                    <th id="colSTS" class="px-2 py-2 text-center text-[10px] font-bold text-amber-500 bg-amber-50/20 border-gray-100 dark:border-slate-700">Rata PAS</th>
                    <th id="colSAS" class="px-2 py-2 text-center text-[10px] font-bold text-amber-500 bg-amber-50/20 border-l border-gray-100 dark:border-slate-700">Rata SAS</th>
                </tr>
            </thead>
            <tbody id="tableBodyRapor" class="divide-y divide-gray-100/80 dark:divide-slate-700/50">
                <tr>
                    <td colspan="7" class="px-6 py-20 text-center text-gray-500 dark:text-slate-400">
                        <svg class="w-16 h-16 mx-auto mb-4 text-gray-300 dark:text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2-2v12a2 2 0 002 2z" />
                        </svg>
                        <p class="font-bold text-lg mb-1">Memproses Filter...</p>
                        <p class="text-sm">Silakan Pilih Kelas dan Mata Pelajaran untuk melihat data nilai.</p>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    const csrfTokenName = "<?= csrf_token() ?>";
    const csrfTokenHash = "<?= csrf_hash() ?>";
</script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="<?= base_url('assets/js/GuruMapel/nilai-rapor.js') ?>?v=<?= time() ?>"></script>
<?= $this->endSection() ?>