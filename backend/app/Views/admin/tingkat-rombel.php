<?= $this->extend('layout/main') ?>

<?= $this->section('title') ?>
<?= lang('Admin/TingkatRombel.page_title_browser') ?> - Rapor Digital
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<link rel="stylesheet" href="<?= base_url('assets/css/Admin/tingkat-rombel.css') ?>">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<style>
    :root {
        --warna-scroll: <?= $color['warna_primary'] ?>;
    }

    .level-card.selected {
        border-color: <?= $color['warna_primary'] ?? '#10b981' ?> !important;
        background-color: <?= $color['warna_secondary'] ?? '#ecfdf5' ?> !important;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    }

    .dark .modal-content {
        background-color: #0f172a !important;
        border-color: #1e293b !important;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="mb-6">
    <div class="flex items-center gap-2 text-sm text-gray-500 dark:text-slate-400 mb-2 transition-colors">
        <span><?= lang('Admin/TingkatRombel.academic_master') ?></span>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewbox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>
        <span style="color: <?= $color['warna_primary'] ?? '#10b981' ?>;" class="font-medium"><?= lang('Admin/TingkatRombel.page_title') ?></span>
    </div>
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 id="page-title" class="text-xl md:text-3xl font-bold text-gray-800 dark:text-white transition-colors"><?= lang('Admin/TingkatRombel.page_title') ?></h1>
            <p id="page-subtitle" class="text-sm md:text-base text-gray-600 dark:text-slate-400 mt-1 transition-colors"><?= lang('Admin/TingkatRombel.page_subtitle') ?></p>
        </div>
        <div class="flex flex-wrap items-center gap-2 md:gap-3">
            <button onclick="showMigrateMassalModal()" class="px-4 py-2.5 bg-amber-500 hover:bg-amber-600 text-white font-semibold rounded-xl transition-all shadow-lg flex items-center gap-2 transform hover:-translate-y-0.5 outline-none">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                </svg>
                <span class="hidden sm:inline">Migrasi Massal</span>
            </button>
            <button onclick="showAddRombelModal()" style="background-color: <?= $color['warna_primary'] ?? '#10b981' ?>;" class="px-4 py-2.5 hover:brightness-95 text-white font-semibold rounded-xl transition-all shadow-lg flex items-center gap-2 transform hover:-translate-y-0.5 outline-none">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewbox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                <span class="hidden sm:inline"><?= lang('Admin/TingkatRombel.btn_add_rombel') ?></span>
            </button>

            <button onclick="showImportModal()" class="px-4 py-2.5 bg-white dark:bg-slate-800 border border-gray-300 dark:border-slate-600 text-gray-700 dark:text-slate-300 font-medium rounded-xl hover:bg-gray-50 dark:hover:bg-slate-700 transition-colors flex items-center gap-2 cursor-pointer shadow-sm outline-none">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewbox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3-3m3-3v12" />
                </svg>
                <span class="hidden sm:inline"><?= lang('Admin/TingkatRombel.btn_import') ?></span>
            </button>

            <button onclick="window.location.href='<?= base_url('admin/rombel/export') ?>'" class="p-2.5 bg-white dark:bg-slate-800 border border-gray-300 dark:border-slate-600 text-gray-700 dark:text-slate-300 rounded-xl hover:bg-gray-50 dark:hover:bg-slate-700 transition-colors cursor-pointer shadow-sm outline-none" title="<?= lang('Admin/TingkatRombel.btn_export') ?>">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewbox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                </svg>
            </button>
        </div>
    </div>
</div>

<div id="alertsContainer" class="mb-6 space-y-3"></div>

<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="stat-card bg-white dark:bg-slate-800 rounded-2xl p-5 shadow-sm border border-gray-100 dark:border-slate-700 transition-colors">
        <div class="flex items-center gap-3 mb-2">
            <div class="w-10 h-10 rounded-xl bg-emerald-100 dark:bg-emerald-900/30 flex items-center justify-center">
                <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewbox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z" />
                </svg>
            </div>
        </div>
        <p class="text-sm font-medium text-gray-500 dark:text-slate-400 mb-1"><?= lang('Admin/TingkatRombel.total_level') ?></p>
        <h3 class="text-2xl font-bold text-gray-800 dark:text-white">3</h3>
    </div>
    <div class="stat-card bg-white dark:bg-slate-800 rounded-2xl p-5 shadow-sm border border-gray-100 dark:border-slate-700 transition-colors">
        <div class="flex items-center gap-3 mb-2">
            <div class="w-10 h-10 rounded-xl bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center">
                <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewbox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                </svg>
            </div>
        </div>
        <p class="text-sm font-medium text-gray-500 dark:text-slate-400 mb-1"><?= lang('Admin/TingkatRombel.total_rombel') ?></p>
        <h3 class="text-2xl font-bold text-gray-800 dark:text-white" id="headerStatTotalRombel">0</h3>
    </div>
    <div class="stat-card bg-white dark:bg-slate-800 rounded-2xl p-5 shadow-sm border border-gray-100 dark:border-slate-700 transition-colors">
        <div class="flex items-center gap-3 mb-2">
            <div class="w-10 h-10 rounded-xl bg-purple-100 dark:bg-purple-900/30 flex items-center justify-center">
                <svg class="w-5 h-5 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewbox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
            </div>
        </div>
        <p class="text-sm font-medium text-gray-500 dark:text-slate-400 mb-1"><?= lang('Admin/TingkatRombel.total_active_student') ?></p>
        <h3 class="text-2xl font-bold text-gray-800 dark:text-white" id="headerStatTotalSiswa">0</h3>
    </div>
    <div class="stat-card bg-white dark:bg-slate-800 rounded-2xl p-5 shadow-sm border border-gray-100 dark:border-slate-700 transition-colors">
        <div class="flex items-center gap-3 mb-2">
            <div class="w-10 h-10 rounded-xl bg-green-100 dark:bg-green-900/30 flex items-center justify-center">
                <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewbox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
        </div>
        <p class="text-sm font-medium text-gray-500 dark:text-slate-400 mb-1"><?= lang('Admin/TingkatRombel.active_rombel') ?></p>
        <h3 class="text-2xl font-bold text-gray-800 dark:text-white" id="headerStatRombelAktif">0</h3>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-1">
        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700 overflow-hidden transition-colors">
            <div class="px-6 py-4 border-b border-gray-100 dark:border-slate-700">
                <h3 class="text-lg font-bold text-gray-800 dark:text-white flex items-center gap-2">
                    <svg class="w-5 h-5" style="color: <?= $color['warna_primary'] ?? '#10b981' ?>;" fill="none" stroke="currentColor" viewbox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z" />
                    </svg> <?= lang('Admin/TingkatRombel.level_list') ?>
                </h3>
            </div>
            <div id="levelList" class="p-4 space-y-3"></div>
        </div>
    </div>

    <div class="lg:col-span-2">
        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700 overflow-hidden transition-colors">
            <div class="px-6 py-4 border-b border-gray-100 dark:border-slate-700 flex flex-col sm:flex-row sm:items-center justify-between gap-4 transition-colors">
                <h3 class="text-lg font-bold text-gray-800 dark:text-white flex items-center gap-2">
                    <svg class="w-5 h-5" style="color: <?= $color['warna_primary'] ?? '#10b981' ?>;" fill="none" stroke="currentColor" viewbox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg> <?= lang('Admin/TingkatRombel.study_group') ?>
                    <span id="selectedLevelName" style="background-color: <?= $color['warna_primary'] ?? '#10b981' ?>20; color: <?= $color['warna_primary'] ?? '#10b981' ?>;" class="px-2.5 py-1 text-[11px] font-bold uppercase tracking-wider rounded-lg shadow-sm border border-transparent"><?= lang('Admin/TingkatRombel.all_levels') ?></span>
                </h3>

                <div class="flex flex-col sm:flex-row items-center gap-2 w-full sm:w-auto">
                    <select id="filterTahunAjaranTable" class="w-full sm:w-auto px-4 py-2.5 text-sm bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-xl text-gray-800 dark:text-white focus:bg-white dark:focus:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-[<?= $color['warna_primary'] ?>] transition-all outline-none font-semibold cursor-pointer">
                        <option value="">-- Semua Tahun Ajaran --</option>
                        <?php foreach ($tahun_ajaran_list as $ta): ?>
                            <?php
                            $status_badge = $ta['status'] === 'Aktif' ? ' (AKTIF)' : '';
                            $is_selected = $ta['id'] == $idTaAktif ? 'selected' : '';
                            ?>
                            <option value="<?= $ta['id'] ?>" <?= $is_selected ?>>
                                <?= $ta['tahun'] ?> - <?= $ta['semester'] ?><?= $status_badge ?>
                            </option>
                        <?php endforeach; ?>
                    </select>

                    <input type="text" id="searchRombel" placeholder="<?= lang('Admin/TingkatRombel.search_rombel_placeholder') ?>" class="w-full sm:w-auto px-4 py-2.5 text-sm bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-xl text-gray-800 dark:text-white placeholder-gray-400 dark:placeholder-slate-400 focus:bg-white dark:focus:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-[<?= $color['warna_primary'] ?>] transition-all outline-none">
                </div>
            </div>

            <div class="overflow-x-auto custom-scrollbar">
                <table class="w-full min-w-max">
                    <thead class="bg-gray-50 dark:bg-slate-900/50 border-b border-gray-100 dark:border-slate-700 transition-colors">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 dark:text-slate-400 uppercase tracking-wider"><?= lang('Admin/TingkatRombel.th_rombel_name') ?></th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 dark:text-slate-400 uppercase tracking-wider"><?= lang('Admin/TingkatRombel.th_level') ?></th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 dark:text-slate-400 uppercase tracking-wider"><?= lang('Admin/TingkatRombel.th_homeroom') ?></th>
                            <th class="px-6 py-4 text-center text-xs font-bold text-gray-500 dark:text-slate-400 uppercase tracking-wider"><?= lang('Admin/TingkatRombel.th_student') ?></th>
                            <th class="px-6 py-4 text-center text-xs font-bold text-gray-500 dark:text-slate-400 uppercase tracking-wider"><?= lang('Admin/TingkatRombel.th_status') ?></th>
                            <th class="px-6 py-4 text-center text-xs font-bold text-gray-500 dark:text-slate-400 uppercase tracking-wider"><?= lang('Admin/TingkatRombel.th_action') ?></th>
                        </tr>
                    </thead>
                    <tbody id="rombelTableBody" class="divide-y divide-gray-100 dark:divide-slate-700/50 bg-white dark:bg-slate-800 transition-colors">
                        <?php if (empty($rombel_list)) : ?>
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-gray-500 dark:text-slate-400 italic">
                                    <?= lang('Admin/TingkatRombel.no_rombel_data') ?>
                                </td>
                            </tr>
                        <?php else : ?>
                            <?php foreach ($rombel_list as $row) : ?>
                                <tr class="hover:bg-gray-50 dark:hover:bg-slate-700/50 transition-colors group" data-ta-id="<?= $row['id_tahun_ajaran'] ?>">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center gap-3">
                                            <?php
                                            $bgClass = "bg-[{$color['warna_primary']}]/20 text-[{$color['warna_primary']}]";
                                            if ($row['tingkat'] == 'VIII') $bgClass = 'bg-blue-100 dark:bg-blue-900/40 text-blue-700 dark:text-blue-400';
                                            if ($row['tingkat'] == 'IX') $bgClass = 'bg-purple-100 dark:bg-purple-900/40 text-purple-700 dark:text-purple-400';
                                            ?>
                                            <div class="w-10 h-10 rounded-lg <?= $bgClass ?> flex items-center justify-center font-bold text-sm group-hover:bg-opacity-80 transition-colors border border-transparent">
                                                <?= substr($row['nama_rombel'], 0, 1) ?>
                                            </div>
                                            <div>
                                                <p class="font-bold text-gray-800 dark:text-white text-sm group-hover:text-[<?= $color['warna_primary'] ?>] transition-colors"><?= esc($row['nama_rombel']) ?></p>
                                                <p class="text-[11px] text-gray-500 dark:text-slate-400 font-medium"><?= lang('Admin/TingkatRombel.academic_year') ?>: <?= esc($row['nama_tahun_ajaran'] ?? 'Belum Diset') ?> (<?= $row['semester_ta'] ?? '' ?>)</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-3 py-1 bg-gray-100 dark:bg-slate-700 text-gray-700 dark:text-slate-300 rounded-lg text-xs font-bold border border-gray-200 dark:border-slate-600 shadow-sm transition-colors">
                                            <?= lang('Admin/TingkatRombel.class_word') ?> <?= esc($row['tingkat']) ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center gap-2">
                                            <?php if (!empty($row['nama_wali_kelas'])) : ?>
                                                <div class="w-6 h-6 rounded-full bg-gray-200 dark:bg-slate-700 flex items-center justify-center text-[10px] font-bold text-gray-600 dark:text-slate-300 border border-gray-300 dark:border-slate-600 transition-colors shadow-sm">
                                                    <?= substr($row['nama_wali_kelas'], 0, 1) ?>
                                                </div>
                                                <span class="text-sm font-semibold text-gray-700 dark:text-slate-200 truncate max-w-[150px]">
                                                    <?= esc($row['nama_wali_kelas']) ?>
                                                </span>
                                            <?php else : ?>
                                                <span class="text-xs text-amber-600 dark:text-amber-500 font-bold flex items-center gap-1 bg-amber-50 dark:bg-amber-900/30 px-2 py-0.5 rounded border border-amber-200 dark:border-amber-800/50">
                                                    Belum ada wali
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <span class="font-bold text-gray-800 dark:text-white">
                                            <?= isset($row['jumlah_siswa']) ? $row['jumlah_siswa'] : 0 ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <?php if (isset($row['is_lulus']) && $row['is_lulus'] == 1) : ?>
                                            <span class="px-2.5 py-1 text-[11px] font-bold uppercase tracking-wider rounded-full bg-purple-50 dark:bg-purple-900/30 text-purple-700 dark:text-purple-400 border border-purple-200 dark:border-purple-800/50 transition-colors shadow-sm">
                                                Lulus
                                            </span>
                                        <?php else : ?>
                                            <span class="px-2.5 py-1 text-[11px] font-bold uppercase tracking-wider rounded-full bg-emerald-50 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800/50 transition-colors shadow-sm">
                                                <?= lang('Admin/TingkatRombel.active') ?>
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <div class="flex items-center justify-center gap-2 opacity-1 md:opacity-0 md:group-hover:opacity-100 transition-opacity">
                                            <button onclick="showDetail(<?= $row['id'] ?>)" class="p-2 hover:bg-gray-100 dark:hover:bg-slate-700 rounded-lg transition-colors group relative" style="color: <?= $color['warna_primary'] ?? '#10b981' ?>;" title="<?= lang('Admin/TingkatRombel.btn_detail') ?>">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewbox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                            </button>

                                            <?php
                                            $isTingkatAkhir = ($row['tingkat'] == 'IX' || $row['tingkat'] == '9');
                                            $isLulus = (isset($row['is_lulus']) && $row['is_lulus'] == 1);

                                            $btnColor = $isTingkatAkhir ? 'text-emerald-500 hover:bg-emerald-100 dark:hover:bg-emerald-900/30' : 'text-amber-500 hover:bg-amber-100 dark:hover:bg-amber-900/30';
                                            $btnTitle = $isTingkatAkhir ? 'Luluskan Kelas' : 'Migrasi / Naik Tingkat';
                                            $btnIcon = $isTingkatAkhir
                                                ? '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222" /></svg>'
                                                : '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7"></path></svg>';
                                            ?>

                                            <?php if (!$isLulus): ?>
                                                <button onclick="openMigrateModal(<?= $row['id'] ?>)" class="p-2 rounded-lg transition-colors <?= $btnColor ?>" title="<?= $btnTitle ?>">
                                                    <?= $btnIcon ?>
                                                </button>
                                            <?php endif; ?>

                                            <button onclick="editRombel(<?= $row['id'] ?>)" class="p-2 hover:bg-blue-100 dark:hover:bg-blue-900/30 text-blue-500 rounded-lg transition-colors" title="Edit">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                </svg>
                                            </button>
                                            <button onclick="deleteRombelPrompt(<?= $row['id'] ?>)" class="p-2 hover:bg-red-100 dark:hover:bg-red-900/30 text-red-500 rounded-lg transition-colors" title="Hapus">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('modals') ?>

<div id="migrateMassalModal" class="fixed inset-0 z-[100] hidden">
    <div class="absolute inset-0 bg-gray-900/70 backdrop-blur-sm transition-opacity" onclick="closeMigrateMassalModal()"></div>
    <div class="absolute inset-0 flex items-center justify-center p-4 pointer-events-none md:left-64">
        <div class="relative w-full bg-white dark:bg-slate-800 rounded-2xl shadow-2xl flex flex-col max-h-[90vh] pointer-events-auto border border-transparent dark:border-slate-700 transition-colors modal-content" style="max-width: 500px;">
            <div class="p-6 border-b border-amber-500 dark:border-amber-600 flex items-center justify-between bg-amber-50 dark:bg-amber-900/20 rounded-t-2xl">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-amber-100 dark:bg-amber-800/50 flex items-center justify-center text-amber-600 dark:text-amber-400">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-xl font-black text-amber-700 dark:text-amber-400">Migrasi Massal</h3>
                        <p class="text-xs font-bold text-amber-600/70 dark:text-amber-500 mt-0.5 uppercase tracking-wider">Kenaikan Kelas & Kelulusan</p>
                    </div>
                </div>
                <button type="button" onclick="closeMigrateMassalModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-slate-300 transition-colors outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewbox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <form id="formMigrateMassal" class="p-6 space-y-5 overflow-y-auto custom-scrollbar" onsubmit="handleMigrateMassalSubmit(event)">
                <?= csrf_field() ?>

                <div class="bg-gray-50 dark:bg-slate-700/50 p-4 rounded-xl border border-gray-200 dark:border-slate-600">
                    <p class="text-sm text-gray-600 dark:text-slate-300 leading-relaxed font-medium">Aksi ini akan menarik <b class="text-gray-800 dark:text-white">semua rombel aktif</b> dari TA Asal. Kelas VII naik ke VIII, Kelas VIII naik ke IX, dan Kelas IX akan <b class="text-emerald-600 dark:text-emerald-400">Otomatis Diluluskan</b> beserta siswanya.</p>
                </div>

                <div>
                    <label class="block text-[11px] font-black text-gray-500 dark:text-slate-400 uppercase tracking-widest mb-2">Tahun Ajaran Asal (Yang Saat Ini)</label>
                    <select name="asal_tahun_ajaran" required class="w-full px-4 py-3 bg-white dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-xl text-sm text-gray-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-amber-500 transition-all shadow-sm outline-none font-semibold cursor-pointer">
                        <option value="">-- Pilih TA Asal --</option>
                        <?php foreach ($tahun_ajaran_list as $ta): ?>
                            <option value="<?= $ta['id'] ?>" <?= $ta['id'] == $idTaAktif ? 'selected' : '' ?>><?= $ta['tahun'] ?> - <?= $ta['semester'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="flex justify-center text-amber-500 dark:text-amber-400">
                    <svg class="w-6 h-6 animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                    </svg>
                </div>

                <div>
                    <label class="block text-[11px] font-black text-amber-600 dark:text-amber-400 uppercase tracking-widest mb-2">Tahun Ajaran Tujuan (Yang Baru)</label>
                    <select name="target_tahun_ajaran" required class="w-full px-4 py-3 bg-amber-50 dark:bg-slate-700 border-2 border-amber-200 dark:border-amber-700/50 rounded-xl text-sm text-gray-800 dark:text-white focus:outline-none focus:border-amber-500 transition-all shadow-sm outline-none font-bold cursor-pointer">
                        <option value="">-- Pilih TA Tujuan --</option>
                        <?php foreach ($tahun_ajaran_list as $ta): ?>
                            <option value="<?= $ta['id'] ?>"><?= $ta['tahun'] ?> - <?= $ta['semester'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="flex gap-3 pt-4 border-t border-gray-100 dark:border-slate-700 mt-6">
                    <button type="button" onclick="closeMigrateMassalModal()" class="flex-1 px-5 py-3.5 bg-white dark:bg-slate-700 border border-gray-300 dark:border-slate-600 text-gray-700 dark:text-slate-300 font-bold rounded-xl hover:bg-gray-50 dark:hover:bg-slate-600 transition-colors outline-none shadow-sm">Batal</button>
                    <button type="submit" class="flex-1 px-5 py-3.5 text-white font-bold rounded-xl shadow-lg transition-all flex items-center justify-center gap-2 outline-none transform hover:-translate-y-0.5 bg-amber-500 hover:bg-amber-600 hover:shadow-amber-500/30">Mulai Sapu Jagat!</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="migrateRombelModal" class="fixed inset-0 z-[100] hidden">
    <div class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" onclick="closeMigrateModal()"></div>
    <div class="absolute inset-0 flex items-center justify-center p-4 pointer-events-none md:left-64">
        <div class="relative w-full bg-white dark:bg-slate-800 rounded-2xl shadow-2xl flex flex-col max-h-[90vh] pointer-events-auto border border-transparent dark:border-slate-700 transition-colors modal-content" style="max-width: 500px;">
            <div class="p-6 border-b border-gray-100 dark:border-slate-700 flex items-center justify-between bg-amber-500 rounded-t-2xl" id="migrateHeaderBg">
                <div>
                    <h3 class="text-xl font-bold text-white" id="modalMigrateTitle">Migrasi / Naik Tingkat</h3>
                    <p class="text-sm text-white/80 mt-1" id="modalMigrateDesc">Duplikasi rombel ke semester atau TA baru</p>
                </div>
                <button type="button" onclick="closeMigrateModal()" class="text-white hover:text-gray-200 transition-colors outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewbox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <form id="formMigrateRombel" class="p-6 space-y-4 overflow-y-auto custom-scrollbar" onsubmit="handleMigrateSubmit(event)">
                <?= csrf_field() ?>
                <input type="hidden" name="rombel_id_lama" id="migrasiRombelId">
                <input type="hidden" name="jenis_migrasi" id="jenisMigrasiInput" value="naik_kelas">

                <div class="bg-gray-50 dark:bg-slate-700/50 p-3 rounded-xl border border-gray-200 dark:border-slate-600 mb-2">
                    <p class="text-sm font-semibold text-gray-600 dark:text-slate-300">Rombel Saat Ini: <span id="migrasiInfoAsal" class="font-black text-gray-800 dark:text-white"></span></p>
                </div>

                <div id="formAreaNaikKelas" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-2">Tujuan Tahun Ajaran & Semester <span class="text-red-500">*</span></label>
                        <select name="target_tahun_ajaran" required class="w-full px-4 py-2.5 bg-white dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-xl text-sm text-gray-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-amber-500 transition-all shadow-sm outline-none font-semibold">
                            <option value="">-- Pilih Tujuan --</option>
                            <?php foreach ($tahun_ajaran_list as $ta): ?>
                                <option value="<?= $ta['id'] ?>"><?= $ta['tahun'] ?> - <?= $ta['semester'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-2">Tingkat Baru <span class="text-red-500">*</span></label>
                        <select name="target_tingkat" id="migrasiTingkat" required class="w-full px-4 py-2.5 bg-white dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-xl text-sm text-gray-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-amber-500 transition-all shadow-sm outline-none">
                            <option value="VII">Kelas 7 (VII)</option>
                            <option value="VIII">Kelas 8 (VIII)</option>
                            <option value="IX">Kelas 9 (IX)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-2">Nama Rombel Baru <span class="text-red-500">*</span></label>
                        <input type="text" name="target_nama_rombel" id="migrasiNamaRombel" required class="w-full px-4 py-2.5 bg-white dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-xl text-sm text-gray-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-amber-500 transition-all shadow-sm outline-none">
                    </div>

                    <div class="mt-4 pt-4 border-t border-gray-100 dark:border-slate-700">
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="checkbox" name="copy_students" value="1" checked class="w-5 h-5 text-amber-500 border-gray-300 rounded focus:ring-amber-500 dark:bg-slate-700 dark:border-slate-600">
                            <span class="text-sm font-bold text-gray-700 dark:text-slate-300">Pindahkan semua siswa ke Rombel Baru</span>
                        </label>
                        <p class="text-[11px] text-gray-500 mt-1 ml-8 leading-relaxed">Siswa akan dimutasi otomatis (Naik kelas atau Lanjut semester). Tidak mengganggu nilai siswa di semester/kelas sebelumnya.</p>
                    </div>
                </div>

                <div id="formAreaLulus" class="hidden">
                    <div id="normalLulusMessage" class="bg-emerald-50 dark:bg-emerald-900/20 p-5 rounded-xl border border-emerald-200 dark:border-emerald-800/50 text-emerald-800 dark:text-emerald-400 text-center">
                        <svg class="w-14 h-14 mx-auto mb-3 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222" />
                        </svg>
                        <h4 class="font-black text-lg mb-1">Luluskan Kelas Ini?</h4>
                        <p class="text-sm font-medium leading-relaxed">Kelas ini berada di tingkat akhir (IX). Melanjutkan aksi ini akan mengubah status seluruh siswa aktif di dalam kelas menjadi <b>"Lulus"</b> dan tidak lagi terhitung di statistik kelas berjalan.</p>
                    </div>

                    <div id="emptyStudentWarningLulus" class="hidden bg-rose-50 dark:bg-rose-900/20 p-5 rounded-xl border border-rose-200 dark:border-rose-800/50 text-rose-800 dark:text-rose-400 text-center">
                        <svg class="w-14 h-14 mx-auto mb-3 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        <h4 class="font-black text-lg mb-1">Tidak Ada Siswa!</h4>
                        <p class="text-sm font-medium leading-relaxed">Tidak ada murid aktif di rombel ini. Aksi kelulusan tidak dapat diproses karena tidak ada data siswa yang bisa diluluskan.</p>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row gap-3 pt-4 border-t border-gray-100 dark:border-slate-700 mt-4">
                    <button type="button" onclick="closeMigrateModal()" class="flex-1 px-5 py-3.5 bg-white dark:bg-slate-700 border border-gray-300 dark:border-slate-600 text-gray-700 dark:text-slate-300 font-bold rounded-xl hover:bg-gray-50 dark:hover:bg-slate-600 transition-colors outline-none shadow-sm">Batal</button>
                    <button type="submit" id="btnSubmitMigrasi" class="flex-1 px-5 py-3.5 text-white font-bold rounded-xl shadow-lg transition-all flex items-center justify-center gap-2 outline-none transform hover:-translate-y-0.5 bg-amber-500 hover:bg-amber-600">Proses Migrasi</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="addRombelModal" class="fixed inset-0 z-[100] hidden">
    <div class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" onclick="closeAddRombelModal()"></div>
    <div class="absolute inset-0 flex items-center justify-center p-4 pointer-events-none md:left-64">
        <div class="relative w-full bg-white dark:bg-slate-800 rounded-2xl shadow-2xl flex flex-col max-h-[90vh] pointer-events-auto border border-transparent dark:border-slate-700 transition-colors modal-content" style="max-width: 500px;">
            <div class="p-6 border-b border-gray-100 dark:border-slate-700 flex items-center justify-between">
                <div>
                    <h3 id="modalRombelTitle" class="text-xl font-bold text-gray-800 dark:text-white"><?= lang('Admin/TingkatRombel.modal_add_title') ?></h3>
                    <p class="text-sm text-gray-500 dark:text-slate-400 mt-1"><?= lang('Admin/TingkatRombel.modal_add_subtitle') ?></p>
                </div>
                <button type="button" onclick="closeAddRombelModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-slate-300 transition-colors outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewbox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <form id="formTambahRombel" class="p-6 space-y-4" onsubmit="handleRombelSubmit(event)">
                <input type="hidden" name="id" id="rombelId">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-2"><?= lang('Admin/TingkatRombel.form_rombel_name') ?> <span class="text-red-500">*</span></label>
                    <input type="text" name="rombel_name" required placeholder="<?= lang('Admin/TingkatRombel.form_rombel_placeholder') ?>" class="w-full px-4 py-2.5 bg-white dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-xl text-sm text-gray-800 dark:text-white placeholder-gray-400 dark:placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[<?= $color['warna_primary'] ?>] transition-all shadow-sm outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-2"><?= lang('Admin/TingkatRombel.form_level') ?> <span class="text-red-500">*</span></label>
                    <select name="level" required class="w-full px-4 py-2.5 bg-white dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-xl text-sm text-gray-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-[<?= $color['warna_primary'] ?>] transition-all shadow-sm appearance-none cursor-pointer outline-none">
                        <option value=""><?= lang('Admin/TingkatRombel.select_level') ?></option>
                        <option value="VII"><?= lang('Admin/TingkatRombel.class_word') ?> 7 (VII)</option>
                        <option value="VIII"><?= lang('Admin/TingkatRombel.class_word') ?> 8 (VIII)</option>
                        <option value="IX"><?= lang('Admin/TingkatRombel.class_word') ?> 9 (IX)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-2"><?= lang('Admin/TingkatRombel.form_homeroom') ?></label>
                    <select name="homeroom_teacher" class="w-full px-4 py-2.5 bg-white dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-xl text-sm text-gray-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-[<?= $color['warna_primary'] ?>] transition-all shadow-sm appearance-none cursor-pointer outline-none">
                        <option value=""><?= lang('Admin/TingkatRombel.select_homeroom') ?></option>
                        <?php if (!empty($guru_list)) : ?>
                            <?php foreach ($guru_list as $guru) : ?>
                                <option value="<?= $guru['id'] ?>"><?= esc($guru['nama_lengkap']) ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-2"><?= lang('Admin/TingkatRombel.academic_year') ?> <span class="text-red-500">*</span></label>
                    <select name="id_tahun_ajaran" required class="w-full px-4 py-2.5 bg-white dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-xl text-sm text-gray-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-[<?= $color['warna_primary'] ?>] transition-all shadow-sm appearance-none cursor-pointer outline-none font-semibold">
                        <option value="">-- Pilih Tahun Ajaran --</option>
                        <?php foreach ($tahun_ajaran_list as $ta): ?>
                            <option value="<?= $ta['id'] ?>" <?= $ta['id'] == $idTaAktif ? 'selected' : '' ?>>
                                <?= $ta['tahun'] ?> - <?= $ta['semester'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="flex gap-3 pt-4 border-t border-gray-100 dark:border-slate-700">
                    <button type="button" onclick="closeAddRombelModal()" class="flex-1 px-5 py-2.5 bg-white dark:bg-slate-700 border border-gray-300 dark:border-slate-600 text-gray-700 dark:text-slate-300 font-semibold rounded-xl hover:bg-gray-50 dark:hover:bg-slate-600 transition-colors outline-none shadow-sm"><?= lang('Admin/TingkatRombel.btn_cancel') ?></button>
                    <button type="submit" class="flex-1 px-5 py-2.5 text-white font-semibold rounded-xl shadow-lg transition-transform flex items-center justify-center gap-2 outline-none transform hover:-translate-y-0.5" style="background-color: <?= $color['warna_primary'] ?? '#10b981' ?>; box-shadow: 0 10px 15px -3px <?= $color['warna_primary'] ?? '#10b981' ?>40;"><?= lang('Admin/TingkatRombel.btn_save_rombel') ?></button>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="drawer-overlay" class="drawer-overlay fixed inset-0 bg-gray-900/40 backdrop-blur-sm hidden transition-opacity" style="z-index: 999997 !important;" onclick="closeDrawer()"></div>
<div id="detailDrawer" class="drawer fixed inset-y-0 right-0 w-full md:w-[450px] bg-white dark:bg-slate-800 shadow-2xl transform translate-x-full transition-transform duration-300 ease-in-out flex flex-col border-l border-gray-200 dark:border-slate-700" style="z-index: 999998 !important;">
    <div class="p-6 border-b border-gray-100 dark:border-slate-700 flex items-center justify-between bg-white dark:bg-slate-800 z-10 transition-colors">
        <h3 class="text-lg font-bold text-gray-800 dark:text-white"><?= lang('Admin/TingkatRombel.drawer_title') ?></h3>
        <button onclick="closeDrawer()" class="p-2 hover:bg-gray-100 dark:hover:bg-slate-700 rounded-full transition-colors text-gray-500 dark:text-slate-400 outline-none">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewbox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>

    <div class="flex-1 overflow-y-auto p-6 space-y-6 custom-scrollbar">
        <div class="text-center pb-6 border-b border-gray-100 dark:border-slate-700 transition-colors">
            <div id="drawerRombelIcon" class="w-20 h-20 rounded-2xl flex items-center justify-center mx-auto mb-4 border-4 border-white dark:border-slate-800 shadow-lg text-3xl font-black text-white" style="background-color: <?= $color['warna_primary'] ?? '#10b981' ?>;">V</div>
            <h4 id="drawerRombelName" class="text-2xl font-bold text-gray-800 dark:text-white mb-1">-</h4>
            <div class="flex items-center justify-center gap-2 text-sm font-semibold mb-3">
                <span id="drawerRombelLevel" class="bg-gray-100 dark:bg-slate-700 px-3 py-1 rounded-lg text-gray-700 dark:text-slate-300 border border-gray-200 dark:border-slate-600 shadow-sm">-</span>
                <span id="drawerRombelYear" class="bg-gray-100 dark:bg-slate-700 px-3 py-1 rounded-lg text-gray-700 dark:text-slate-300 border border-gray-200 dark:border-slate-600 shadow-sm">-</span>
            </div>
            <p id="drawerWaliKelas" class="text-sm font-medium text-gray-600 dark:text-slate-400 bg-gray-50 dark:bg-slate-900/50 py-2 px-4 rounded-xl border border-gray-100 dark:border-slate-700 inline-block"><?= lang('Admin/TingkatRombel.th_homeroom') ?>: -</p>
        </div>

        <div class="grid grid-cols-3 gap-4">
            <div class="text-center p-4 bg-gray-50 dark:bg-slate-700/50 rounded-xl border border-gray-200 dark:border-slate-600 transition-colors">
                <p class="text-2xl font-bold text-gray-800 dark:text-white" id="drawerStudentCount">0</p>
                <p class="text-[11px] font-bold text-gray-500 dark:text-slate-400 mt-1 uppercase tracking-wider"><?= lang('Admin/TingkatRombel.total_student') ?></p>
            </div>
            <div class="text-center p-4 bg-blue-50 dark:bg-blue-900/30 rounded-xl border border-blue-100 dark:border-blue-800/50 transition-colors">
                <p class="text-2xl font-bold text-blue-600 dark:text-blue-400" id="drawerMaleCount">0</p>
                <p class="text-[11px] font-bold text-blue-600/70 dark:text-blue-400 mt-1 uppercase tracking-wider"><?= lang('Admin/TingkatRombel.male') ?></p>
            </div>
            <div class="text-center p-4 bg-pink-50 dark:bg-pink-900/30 rounded-xl border border-pink-100 dark:border-pink-800/50 transition-colors">
                <p class="text-2xl font-bold text-pink-600 dark:text-pink-400" id="drawerFemaleCount">0</p>
                <p class="text-[11px] font-bold text-pink-600/70 dark:text-pink-400 mt-1 uppercase tracking-wider"><?= lang('Admin/TingkatRombel.female') ?></p>
            </div>
        </div>

        <div class="pt-2">
            <div class="flex items-center justify-between mb-4">
                <h5 class="font-bold text-gray-800 dark:text-white flex items-center gap-2 text-sm">
                    <svg class="w-5 h-5 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewbox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg> <?= lang('Admin/TingkatRombel.rombel_student_list') ?>
                </h5>
                <button onclick="openStudentManagementFromDrawer()" class="text-xs font-bold text-[<?= $color['warna_primary'] ?>] hover:underline outline-none"><?= lang('Admin/TingkatRombel.manage_student') ?></button>
            </div>
            <div class="space-y-2 max-h-80 overflow-y-auto custom-scrollbar pr-2" id="drawerStudentList">
                <p class="text-center text-sm font-medium text-gray-400 dark:text-slate-500 py-6 bg-gray-50 dark:bg-slate-900/50 rounded-xl border border-dashed border-gray-200 dark:border-slate-700">Memuat data siswa...</p>
            </div>
        </div>
    </div>

    <div class="p-5 border-t border-gray-100 dark:border-slate-700 bg-gray-50 dark:bg-slate-800/80 z-10 flex gap-3 transition-colors">
        <button onclick="editRombel(document.getElementById('detailDrawer').dataset.rombelId)" class="flex-1 px-4 py-2.5 text-white font-bold rounded-xl transition-all transform hover:-translate-y-0.5 shadow-md flex items-center justify-center gap-2 outline-none" style="background-color: <?= $color['warna_primary'] ?? '#10b981' ?>;">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewbox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
            </svg> <?= lang('Admin/TingkatRombel.btn_edit_rombel') ?>
        </button>
        <button onclick="deleteRombelPrompt(document.getElementById('detailDrawer').dataset.rombelId)" class="px-4 py-2.5 bg-white dark:bg-slate-700 border border-red-200 dark:border-red-800/50 text-red-600 dark:text-red-400 font-bold rounded-xl hover:bg-red-50 dark:hover:bg-red-900/30 transition-colors shadow-sm outline-none" title="<?= lang('Admin/TingkatRombel.btn_delete_rombel') ?>">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewbox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
            </svg>
        </button>
    </div>
</div>

<div id="detailTingkatModal" class="fixed inset-0 z-[100] hidden">
    <div class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" onclick="closeDetailTingkatModal()"></div>
    <div class="absolute inset-0 flex items-center justify-center p-4 pointer-events-none md:left-64">
        <div class="relative w-full bg-white dark:bg-slate-800 rounded-3xl shadow-2xl flex flex-col max-w-md pointer-events-auto border border-transparent dark:border-slate-700 transition-colors modal-content">
            <div class="p-6 border-b border-gray-100 dark:border-slate-700 flex items-center justify-between">
                <div>
                    <h3 class="text-xl font-bold text-gray-800 dark:text-white"><?= lang('Admin/TingkatRombel.modal_stats_title') ?></h3>
                    <p id="detailTingkatTitle" class="text-sm text-gray-500 dark:text-slate-400 mt-1 font-bold" style="color: <?= $color['warna_primary'] ?? '#10b981' ?>;">-</p>
                </div>
                <button type="button" onclick="closeDetailTingkatModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-slate-300 transition-colors outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewbox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <div class="p-6 space-y-4">
                <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-slate-700/50 rounded-xl border border-gray-200 dark:border-slate-600">
                    <span class="font-bold text-gray-600 dark:text-slate-300"><?= lang('Admin/TingkatRombel.total_rombel') ?></span>
                    <span id="detailTingkatRombel" class="font-black text-xl text-gray-800 dark:text-white">0</span>
                </div>
                <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-slate-700/50 rounded-xl border border-gray-200 dark:border-slate-600">
                    <span class="font-bold text-gray-600 dark:text-slate-300"><?= lang('Admin/TingkatRombel.total_overall_student') ?></span>
                    <span id="detailTingkatSiswa" class="font-black text-xl text-gray-800 dark:text-white">0</span>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div class="text-center p-4 bg-blue-50 dark:bg-blue-900/30 rounded-xl border border-blue-100 dark:border-blue-800/50">
                        <p id="detailTingkatLaki" class="text-2xl font-bold text-blue-600 dark:text-blue-400">0</p>
                        <p class="text-[11px] font-bold text-blue-600/70 dark:text-blue-400 mt-1 uppercase tracking-wider"><?= lang('Admin/TingkatRombel.male') ?></p>
                    </div>
                    <div class="text-center p-4 bg-pink-50 dark:bg-pink-900/30 rounded-xl border border-pink-100 dark:border-pink-800/50">
                        <p id="detailTingkatPerempuan" class="text-2xl font-bold text-pink-600 dark:text-pink-400">0</p>
                        <p class="text-[11px] font-bold text-pink-600/70 dark:text-pink-400 mt-1 uppercase tracking-wider"><?= lang('Admin/TingkatRombel.female') ?></p>
                    </div>
                </div>
            </div>
            <div class="p-6 border-t border-gray-100 dark:border-slate-700 bg-gray-50 dark:bg-slate-800/80 rounded-b-3xl">
                <button onclick="closeDetailTingkatModal()" class="w-full px-5 py-3 text-white font-bold rounded-xl shadow-lg transition-transform transform hover:-translate-y-0.5 outline-none" style="background-color: <?= $color['warna_primary'] ?? '#10b981' ?>; box-shadow: 0 10px 15px -3px <?= $color['warna_primary'] ?? '#10b981' ?>40;"><?= lang('Admin/TingkatRombel.btn_close_stats') ?></button>
            </div>
        </div>
    </div>
</div>

<div id="deleteRombelModal" class="fixed inset-0 z-[100] hidden">
    <div class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" onclick="closeDeleteModal()"></div>
    <div class="absolute inset-0 flex items-center justify-center p-4 pointer-events-none md:left-64">
        <div class="relative w-full max-w-md bg-white dark:bg-slate-800 rounded-3xl shadow-2xl flex flex-col pointer-events-auto border border-transparent dark:border-slate-700 transition-colors overflow-hidden">
            <div class="p-8 text-center">
                <div class="w-20 h-20 bg-rose-50 dark:bg-rose-900/30 rounded-full flex items-center justify-center mx-auto mb-6 border-4 border-rose-100 dark:border-rose-800/50">
                    <svg class="w-10 h-10 text-rose-500 dark:text-rose-400" fill="none" stroke="currentColor" viewbox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>

                <h3 class="text-2xl font-bold text-gray-800 dark:text-white mb-3"><?= lang('Admin/TingkatRombel.modal_delete_title') ?></h3>
                <p class="text-sm font-medium text-gray-500 dark:text-slate-400 mb-8 px-2 leading-relaxed">
                    <?= lang('Admin/TingkatRombel.modal_delete_desc') ?>
                </p>

                <input type="hidden" id="deleteRombelId">

                <div class="flex flex-col sm:flex-row gap-3">
                    <button type="button" onclick="closeDeleteModal()" class="flex-1 px-5 py-3.5 bg-white dark:bg-slate-700 border border-gray-300 dark:border-slate-600 text-gray-700 dark:text-slate-300 font-bold rounded-xl hover:bg-gray-50 dark:hover:bg-slate-600 transition-colors outline-none shadow-sm">
                        <?= lang('Admin/TingkatRombel.btn_cancel') ?>
                    </button>
                    <button type="button" onclick="confirmDeleteRombel()" class="flex-1 px-5 py-3.5 bg-red-600 text-white font-bold rounded-xl hover:bg-red-700 shadow-lg shadow-red-600/30 transition-colors outline-none">
                        <?= lang('Admin/TingkatRombel.btn_yes_delete') ?>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="importModal" class="fixed inset-0 z-[100] hidden">
    <div class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" onclick="closeImportModal()"></div>
    <div class="absolute inset-0 flex items-center justify-center p-4 pointer-events-none md:left-64">
        <div class="relative w-full bg-white dark:bg-slate-800 rounded-2xl shadow-2xl flex flex-col max-h-[90vh] pointer-events-auto border border-transparent dark:border-slate-700 transition-colors" style="max-width: 500px;">
            <div class="p-6 border-b border-gray-100 dark:border-slate-700 flex items-center justify-between">
                <div>
                    <h3 class="text-xl font-bold text-gray-800 dark:text-white"><?= lang('Admin/TingkatRombel.modal_import_title') ?></h3>
                    <p class="text-sm text-gray-500 dark:text-slate-400 mt-1"><?= lang('Admin/TingkatRombel.modal_import_subtitle') ?></p>
                </div>
                <button type="button" onclick="closeImportModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-slate-300 transition-colors outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewbox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <form id="importForm" action="<?= base_url('admin/rombel/import') ?>" method="POST" onsubmit="handleImport(event)" enctype="multipart/form-data" class="p-6 space-y-5">
                <?= csrf_field() ?>
                <div>
                    <p class="text-sm font-medium text-gray-700 dark:text-slate-300 mb-2"><?= lang('Admin/TingkatRombel.step_1_download') ?></p>
                    <a href="<?= base_url('admin/rombel/template') ?>" class="inline-flex items-center gap-2 px-4 py-2 font-bold rounded-lg transition-colors text-sm border border-transparent hover:border-[<?= $color['warna_primary'] ?>]/30 shadow-sm" style="color: <?= $color['warna_primary'] ?? '#10b981' ?>; background-color: <?= $color['warna_primary'] ?? '#10b981' ?>1A;">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewbox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                        </svg>
                        <?= lang('Admin/TingkatRombel.btn_download_template') ?>
                    </a>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-700 dark:text-slate-300 mb-2"><?= lang('Admin/TingkatRombel.step_2_upload') ?></p>
                    <input type="file" name="file_excel" accept=".xlsx, .xls" required class="block w-full text-sm text-gray-500 dark:text-slate-400 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-gray-100 dark:file:bg-slate-700 file:text-gray-700 dark:file:text-slate-200 hover:file:bg-gray-200 dark:hover:file:bg-slate-600 border border-gray-200 dark:border-slate-600 cursor-pointer outline-none focus:ring-2 focus:ring-[<?= $color['warna_primary'] ?>] transition-colors">
                </div>
                <div class="flex gap-3 pt-4 border-t border-gray-100 dark:border-slate-700">
                    <button type="button" onclick="closeImportModal()" class="flex-1 px-5 py-2.5 bg-white dark:bg-slate-700 border border-gray-300 dark:border-slate-600 text-gray-700 dark:text-slate-300 font-bold rounded-xl hover:bg-gray-50 dark:hover:bg-slate-600 transition-colors outline-none shadow-sm"><?= lang('Admin/TingkatRombel.btn_cancel') ?></button>
                    <button type="submit" class="flex-1 px-5 py-3 text-white font-bold rounded-xl shadow-lg transition-transform flex items-center justify-center gap-2 outline-none transform hover:-translate-y-0.5" style="background-color: <?= $color['warna_primary'] ?? '#10b981' ?>; box-shadow: 0 10px 15px -3px <?= $color['warna_primary'] ?? '#10b981' ?>40;">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewbox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                        </svg> <?= lang('Admin/TingkatRombel.btn_upload_import') ?>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="studentManagementModal" class="fixed inset-0 z-[100] hidden">
    <div class="absolute inset-0 bg-gray-950/80 backdrop-blur-sm transition-opacity" onclick="closeStudentManagementModal()"></div>
    <div class="absolute inset-0 flex items-center justify-center p-4 pointer-events-none md:left-64">
        <div class="relative w-full bg-white dark:!bg-slate-900 rounded-3xl shadow-2xl flex flex-col max-h-[90vh] pointer-events-auto border border-transparent dark:!border-slate-700 transition-colors modal-content" style="max-width: 900px;">
            <div class="p-6 md:px-8 border-b border-gray-100 dark:!border-slate-800 flex items-center justify-between bg-gray-50 dark:!bg-slate-800/50 rounded-t-3xl">
                <div>
                    <h3 class="text-xl font-black text-gray-900 dark:!text-white tracking-wide"><?= lang('Admin/TingkatRombel.modal_manage_title') ?></h3>
                    <p class="text-sm font-medium text-gray-500 dark:!text-slate-400 mt-1"><?= lang('Admin/TingkatRombel.modal_manage_subtitle') ?></p>
                </div>
                <div class="text-right flex items-center gap-4">
                    <div class="hidden sm:block">
                        <p class="text-sm font-bold text-gray-800 dark:!text-white" id="studentModalRombelName">-</p>
                        <p class="text-[11px] font-black text-[<?= $color['warna_primary'] ?>] uppercase tracking-widest bg-[<?= $color['warna_primary'] ?>]/10 px-2.5 py-1 rounded-md mt-1" id="studentModalLevel">-</p>
                    </div>
                    <button onclick="closeStudentManagementModal()" class="text-gray-400 hover:text-gray-600 dark:hover:!text-white bg-white dark:!bg-slate-800 hover:bg-gray-100 dark:hover:!bg-slate-700 p-2 rounded-xl border border-gray-200 dark:!border-slate-600 transition-colors outline-none shadow-sm">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewbox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>

            <div class="p-6 md:p-8 flex-1 overflow-y-auto custom-scrollbar">
                <div class="flex gap-2 sm:gap-6 mb-6 border-b border-gray-200 dark:!border-slate-700 overflow-x-auto custom-scrollbar pb-0">
                    <button onclick="switchStudentTab('current')" class="tab-button active px-4 py-3 font-bold text-sm border-b-4 whitespace-nowrap outline-none transition-colors" data-tab="current"> <?= lang('Admin/TingkatRombel.tab_current_student') ?> </button>
                    <button onclick="switchStudentTab('add')" class="tab-button px-4 py-3 font-bold text-sm border-b-4 border-transparent text-gray-500 dark:!text-slate-400 hover:text-gray-800 dark:hover:!text-slate-200 whitespace-nowrap outline-none transition-colors" data-tab="add"> <?= lang('Admin/TingkatRombel.tab_add_student') ?> </button>
                    <button onclick="switchStudentTab('transfer')" class="tab-button px-4 py-3 font-bold text-sm border-b-4 border-transparent text-gray-500 dark:!text-slate-400 hover:text-gray-800 dark:hover:!text-slate-200 whitespace-nowrap outline-none transition-colors" data-tab="transfer"> <?= lang('Admin/TingkatRombel.tab_transfer_student') ?> </button>
                </div>

                <div id="currentStudentsTab" class="student-tab">
                    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-4 gap-3">
                        <p class="text-sm font-medium text-gray-600 dark:!text-slate-400" id="currentStudentCountText">Total <span class="font-bold text-gray-900 dark:!text-white">0 siswa</span> di rombel ini</p>
                        <div class="relative w-full sm:w-auto">
                            <input type="text" id="searchCurrentStudent" placeholder="Cari nama atau NISN..." class="w-full px-10 py-3 text-sm bg-gray-50 dark:!bg-slate-800 border border-gray-200 dark:!border-slate-600 rounded-xl text-gray-800 dark:!text-white placeholder-gray-400 dark:!placeholder-slate-500 focus:bg-white dark:focus:!bg-slate-900 focus:outline-none focus:ring-2 focus:ring-[<?= $color['warna_primary'] ?>] transition-all shadow-sm outline-none">
                            <svg class="w-5 h-5 text-gray-400 dark:!text-slate-500 absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" stroke="currentColor" viewbox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                    </div>
                    <div id="listCurrentStudents" class="bg-gray-50 dark:!bg-slate-800/50 rounded-2xl border border-gray-200 dark:!border-slate-700 min-h-[250px] max-h-80 overflow-y-auto p-4 space-y-3 custom-scrollbar shadow-inner">
                    </div>
                </div>

                <div id="addStudentsTab" class="student-tab hidden">
                    <div class="mb-4 relative">
                        <label class="block text-[11px] font-black text-gray-500 dark:!text-slate-400 uppercase tracking-widest mb-2">Cari & Tambah Siswa Baru</label>
                        <input type="text" id="searchUnassignedStudent" placeholder="<?= lang('Admin/TingkatRombel.search_add_student_placeholder') ?>" class="w-full pl-12 pr-4 py-3 bg-gray-50 dark:!bg-slate-800 border border-gray-200 dark:!border-slate-600 rounded-xl text-sm font-semibold text-gray-800 dark:!text-white placeholder-gray-400 dark:!placeholder-slate-500 focus:bg-white dark:focus:!bg-slate-900 focus:outline-none focus:ring-2 focus:ring-[<?= $color['warna_primary'] ?>] transition-all shadow-sm outline-none">
                        <svg class="w-5 h-5 text-gray-400 dark:!text-slate-500 absolute left-4 top-9 pointer-events-none" fill="none" stroke="currentColor" viewbox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <div id="listUnassignedStudents" class="bg-gray-50 dark:!bg-slate-800/50 rounded-2xl border border-gray-200 dark:!border-slate-700 min-h-[250px] max-h-72 overflow-y-auto p-4 space-y-3 custom-scrollbar shadow-inner">
                    </div>
                </div>

                <div id="transferStudentsTab" class="student-tab hidden">
                    <div class="mb-5 bg-blue-50 dark:!bg-blue-900/20 p-4 rounded-xl border border-blue-200 dark:!border-blue-800/50">
                        <label class="block text-[11px] font-black text-blue-800 dark:!text-blue-400 uppercase tracking-widest mb-2"><?= lang('Admin/TingkatRombel.transfer_target_label') ?></label>
                        <select id="transferTargetRombel" class="w-full px-4 py-3 bg-white dark:!bg-slate-900 border border-blue-200 dark:!border-slate-700 rounded-xl text-sm font-bold text-gray-800 dark:!text-white focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all shadow-sm appearance-none cursor-pointer outline-none">
                            <option value=""><?= lang('Admin/TingkatRombel.select_target_rombel') ?></option>
                            <?php foreach ($rombel_list as $r) : ?>
                                <option value="<?= $r['id'] ?>"><?= $r['nama_rombel'] ?> (<?= $r['tingkat'] ?>) - <?= $r['nama_tahun_ajaran'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="flex items-center justify-between mb-3 px-2">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" id="selectAllTransfer" class="w-4 h-4 text-[<?= $color['warna_primary'] ?>] rounded border-gray-300 dark:!border-slate-500 bg-white dark:!bg-slate-800 focus:ring-[<?= $color['warna_primary'] ?>]">
                            <span class="text-sm font-bold text-gray-700 dark:!text-slate-300">Pilih Semua Siswa</span>
                        </label>
                        <button onclick="executeTransfer()" class="text-sm font-black uppercase tracking-wider text-white bg-[<?= $color['warna_primary'] ?>] px-5 py-2.5 rounded-xl shadow-lg hover:brightness-110 outline-none transition-all transform hover:-translate-y-0.5">Pindahkan</button>
                    </div>
                    <div id="listTransferStudents" class="bg-gray-50 dark:!bg-slate-800/50 rounded-2xl border border-gray-200 dark:!border-slate-700 min-h-[200px] max-h-60 overflow-y-auto p-4 space-y-3 custom-scrollbar shadow-inner">
                    </div>
                </div>
            </div>
            <div class="p-6 border-t border-gray-100 dark:!border-slate-800 bg-white dark:!bg-slate-900 rounded-b-3xl transition-colors text-right">
                <button onclick="closeStudentManagementModal()" class="w-full sm:w-auto px-8 py-3.5 bg-gray-100 dark:!bg-slate-800 border border-gray-300 dark:!border-slate-700 text-gray-700 dark:!text-slate-300 font-bold uppercase tracking-widest text-[11px] rounded-xl hover:bg-gray-200 dark:hover:!bg-slate-700 transition-colors shadow-sm outline-none">
                    <?= lang('Admin/TingkatRombel.btn_close_manage') ?>
                </button>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
<?= $this->section('scripts') ?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    const BASE_URL = "<?= base_url() ?>/";

    window.LANG = {
        class_word: "<?= lang('Admin/TingkatRombel.class_word') ?>",
        active: "<?= lang('Admin/TingkatRombel.active') ?>",
        study_group: "<?= lang('Admin/TingkatRombel.study_group') ?>",
        total_student: "<?= lang('Admin/TingkatRombel.total_student') ?>",
        js_class_count: "<?= lang('Admin/TingkatRombel.js_class_count') ?>",
        js_student_count: "<?= lang('Admin/TingkatRombel.js_student_count') ?>",
        js_view_level_stats: "<?= lang('Admin/TingkatRombel.js_view_level_stats') ?>",
        js_loading: "<?= lang('Admin/TingkatRombel.js_loading') ?>",
        js_homeroom: "<?= lang('Admin/TingkatRombel.js_homeroom') ?>",
        js_loading_db: "<?= lang('Admin/TingkatRombel.js_loading_db') ?>",
        male: "<?= lang('Admin/TingkatRombel.male') ?>",
        female: "<?= lang('Admin/TingkatRombel.female') ?>",
        th_level: "<?= lang('Admin/TingkatRombel.th_level') ?>",
        page_title: "<?= lang('Admin/TingkatRombel.page_title') ?>",
        page_subtitle: "<?= lang('Admin/TingkatRombel.page_subtitle') ?>",
        all_levels: "<?= lang('Admin/TingkatRombel.all_levels') ?>"
    };

    window.rombels = <?= json_encode($rombel_list ?? []) ?>;

    window.rawRombelStats = <?= json_encode($raw_rombel_stats ?? []) ?>;
    window.rawSiswaStats = <?= json_encode($raw_siswa_stats ?? []) ?>;

    window.themePrimary = "<?= $color['warna_primary'] ?? '#10b981' ?>";
    window.DYNAMIC_YEAR = "<?= esc($strTaAktif ?? 'Belum Diset') ?>";
</script>
<script src="<?= base_url('assets/js/Admin/tingkat-rombel.js') ?>?v=<?= time() ?>"></script>
<?= $this->endSection() ?>