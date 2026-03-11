<?= $this->extend('layout/main') ?>

<?= $this->section('title') ?>
<?= lang('Admin/ValidasiNilai.page_title_browser') ?>
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<link rel="stylesheet" href="<?= base_url('assets/css/Admin/validasi-nilai.css') ?>">
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="mb-6">
    <div class="flex items-center gap-2 text-sm text-gray-500 dark:text-slate-400 mb-3 transition-colors">
        <span><?= lang('Admin/ValidasiNilai.grading_menu') ?></span>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewbox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>
        <span class="text-[<?= $color['warna_primary'] ?>] font-medium"><?= lang('Admin/ValidasiNilai.page_title') ?></span>
    </div>

    <div class="text-center mb-6 py-4 bg-gradient-to-r from-[<?= $color['warna_secondary'] ?>]/30 dark:from-[<?= $color['warna_primary'] ?>]/10 to-white dark:to-slate-800 rounded-2xl border border-emerald-100 dark:border-[<?= $color['warna_primary'] ?>]/30 shadow-sm transition-colors">
        <p class="text-3xl arabic-text text-[<?= $color['warna_primary'] ?>] mb-2 drop-shadow-sm"><?= lang('Admin/ValidasiNilai.bismillah') ?></p>
        <p class="text-xs text-gray-600 dark:text-slate-400 italic font-medium transition-colors"><?= lang('Admin/ValidasiNilai.bismillah_translation') ?></p>
    </div>

    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-gray-800 dark:text-white mb-2 flex items-center gap-3 transition-colors">
                <svg class="w-8 h-8 text-[<?= $color['warna_primary'] ?>]" fill="none" stroke="currentColor" viewbox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                </svg>
                <?= lang('Admin/ValidasiNilai.page_title') ?>
            </h1>
            <p class="text-sm md:text-base text-gray-600 dark:text-slate-400 transition-colors"><?= lang('Admin/ValidasiNilai.page_subtitle') ?></p>
        </div>
        <div class="flex flex-wrap items-center gap-2">
            <button onclick="validasiMassal()" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl transition-transform transform hover:-translate-y-0.5 shadow-lg shadow-blue-600/30 flex items-center gap-2 outline-none">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewbox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span><?= lang('Admin/ValidasiNilai.btn_mass_validation') ?></span>
            </button>
            <button onclick="lockNilaiMassal()" class="px-5 py-2.5 bg-[<?= $color['warna_primary'] ?>] hover:bg-[<?= $color['warna_primary'] ?>]/90 text-white font-bold rounded-xl transition-transform transform hover:-translate-y-0.5 shadow-lg flex items-center gap-2 outline-none" style="box-shadow: 0 10px 15px -3px <?= $color['warna_primary'] ?>40;">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewbox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                </svg>
                <span><?= lang('Admin/ValidasiNilai.btn_mass_lock') ?></span>
            </button>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
    <div class="stat-card bg-white dark:bg-slate-800 border border-gray-100 dark:border-slate-700 shadow-sm hover:shadow-md hover:border-blue-500 rounded-3xl p-5 transition-colors group">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center shadow-lg shadow-blue-500/30 group-hover:scale-105 transition-transform">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewbox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
            </div>
            <span class="text-[10px] font-black text-blue-600 dark:text-blue-400 uppercase tracking-widest transition-colors"><?= lang('Admin/ValidasiNilai.total_classes') ?></span>
        </div>
        <p class="text-3xl font-black text-gray-900 dark:text-white mb-1 transition-colors"><?= $stats['total'] ?></p>
        <p class="text-xs font-medium text-gray-500 dark:text-slate-400 transition-colors"><?= lang('Admin/ValidasiNilai.total_classes_desc') ?></p>
    </div>

    <div class="stat-card bg-white dark:bg-slate-800 border border-gray-100 dark:border-slate-700 shadow-sm hover:shadow-md hover:border-[<?= $color['warna_primary'] ?>] rounded-3xl p-5 transition-colors group">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-[<?= $color['warna_primary'] ?>] to-emerald-600 flex items-center justify-center shadow-lg group-hover:scale-105 transition-transform" style="box-shadow: 0 10px 15px -3px <?= $color['warna_primary'] ?>40;">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewbox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <span class="text-[10px] font-black text-[<?= $color['warna_primary'] ?>] uppercase tracking-widest transition-colors"><?= lang('Admin/ValidasiNilai.ready_to_validate') ?></span>
        </div>
        <p class="text-3xl font-black text-[<?= $color['warna_primary'] ?>] mb-1 transition-colors"><?= $stats['siap'] ?></p>
        <p class="text-xs font-medium text-gray-500 dark:text-slate-400 transition-colors"><?= lang('Admin/ValidasiNilai.ready_to_validate_desc') ?></p>
    </div>

    <div class="stat-card bg-white dark:bg-slate-800 border border-gray-100 dark:border-slate-700 shadow-sm hover:shadow-md hover:border-amber-500 rounded-3xl p-5 transition-colors group">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-amber-500 to-amber-600 flex items-center justify-center shadow-lg shadow-amber-500/30 group-hover:scale-105 transition-transform">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewbox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>
            <span class="text-[10px] font-black text-amber-600 dark:text-amber-400 uppercase tracking-widest transition-colors"><?= lang('Admin/ValidasiNilai.incomplete') ?></span>
        </div>
        <p class="text-3xl font-black text-amber-600 dark:text-amber-400 mb-1 transition-colors"><?= $stats['belum'] ?></p>
        <p class="text-xs font-medium text-gray-500 dark:text-slate-400 transition-colors"><?= lang('Admin/ValidasiNilai.incomplete_desc') ?></p>
    </div>

    <div class="stat-card bg-white dark:bg-slate-800 border border-gray-100 dark:border-slate-700 shadow-sm hover:shadow-md hover:border-gray-500 rounded-3xl p-5 transition-colors group">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-gray-500 to-gray-600 flex items-center justify-center shadow-lg shadow-gray-500/30 group-hover:scale-105 transition-transform">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewbox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                </svg>
            </div>
            <span class="text-[10px] font-black text-gray-600 dark:text-slate-400 uppercase tracking-widest transition-colors"><?= lang('Admin/ValidasiNilai.locked') ?></span>
        </div>
        <p class="text-3xl font-black text-gray-800 dark:text-white mb-1 transition-colors"><?= $stats['locked'] ?></p>
        <p class="text-xs font-medium text-gray-500 dark:text-slate-400 transition-colors"><?= lang('Admin/ValidasiNilai.locked_desc') ?></p>
    </div>
</div>

<div class="bg-white dark:bg-slate-800 rounded-3xl border border-gray-100 dark:border-slate-700 shadow-sm p-6 mb-6 transition-colors">
    <h3 class="font-bold text-gray-900 dark:text-white mb-5 flex items-center gap-2 transition-colors border-b border-gray-100 dark:border-slate-700 pb-3">
        <svg class="w-5 h-5 text-[<?= $color['warna_primary'] ?>]" fill="none" stroke="currentColor" viewbox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
        </svg>
        <?= lang('Admin/ValidasiNilai.filter_search') ?>
    </h3>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
        <div>
            <label class="block text-[11px] font-bold text-gray-600 dark:text-slate-400 uppercase tracking-wider mb-2 transition-colors"><?= lang('Admin/ValidasiNilai.level') ?></label>
            <select id="filterTingkat" class="w-full px-4 py-3 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 text-gray-800 dark:text-white text-sm rounded-xl focus:outline-none focus:ring-2 focus:ring-[<?= $color['warna_primary'] ?>] transition-colors appearance-none cursor-pointer outline-none shadow-sm">
                <option value=""><?= lang('Admin/ValidasiNilai.all_levels') ?></option>
                <?php foreach ($list_tingkat as $t): ?>
                    <option value="<?= $t['tingkat'] ?>" <?= ($filter['tingkat'] == $t['tingkat']) ? 'selected' : '' ?>><?= lang('Admin/ValidasiNilai.class') ?> <?= $t['tingkat'] ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div>
            <label class="block text-[11px] font-bold text-gray-600 dark:text-slate-400 uppercase tracking-wider mb-2 transition-colors"><?= lang('Admin/ValidasiNilai.study_group') ?></label>
            <select id="filterRombel" class="w-full px-4 py-3 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 text-gray-800 dark:text-white text-sm rounded-xl focus:outline-none focus:ring-2 focus:ring-[<?= $color['warna_primary'] ?>] transition-colors appearance-none cursor-pointer outline-none shadow-sm">
                <option value=""><?= lang('Admin/ValidasiNilai.all_study_groups') ?></option>
                <?php foreach ($list_rombel as $r): ?>
                    <option value="<?= $r['id'] ?>" <?= ($filter['rombel'] == $r['id']) ? 'selected' : '' ?>><?= $r['nama_rombel'] ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div>
            <label class="block text-[11px] font-bold text-gray-600 dark:text-slate-400 uppercase tracking-wider mb-2 transition-colors"><?= lang('Admin/ValidasiNilai.homeroom_teacher') ?></label>
            <select id="filterWali" class="w-full px-4 py-3 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 text-gray-800 dark:text-white text-sm rounded-xl focus:outline-none focus:ring-2 focus:ring-[<?= $color['warna_primary'] ?>] transition-colors appearance-none cursor-pointer outline-none shadow-sm">
                <option value=""><?= lang('Admin/ValidasiNilai.all_homeroom_teachers') ?></option>
                <?php foreach ($list_wali as $w): ?>
                    <option value="<?= $w['id'] ?>" <?= ($filter['wali'] == $w['id']) ? 'selected' : '' ?>><?= $w['nama_lengkap'] ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div>
            <label class="block text-[11px] font-bold text-gray-600 dark:text-slate-400 uppercase tracking-wider mb-2 transition-colors"><?= lang('Admin/ValidasiNilai.validation_status') ?></label>
            <select id="filterStatus" class="w-full px-4 py-3 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 text-gray-800 dark:text-white text-sm rounded-xl focus:outline-none focus:ring-2 focus:ring-[<?= $color['warna_primary'] ?>] transition-colors appearance-none cursor-pointer outline-none shadow-sm">
                <option value=""><?= lang('Admin/ValidasiNilai.all_statuses') ?></option>
                <option value="siap" <?= ($filter['status'] == 'siap') ? 'selected' : '' ?>><?= lang('Admin/ValidasiNilai.status_ready') ?></option>
                <option value="belum" <?= ($filter['status'] == 'belum') ? 'selected' : '' ?>><?= lang('Admin/ValidasiNilai.status_incomplete') ?></option>
                <option value="terkunci" <?= ($filter['status'] == 'terkunci') ? 'selected' : '' ?>><?= lang('Admin/ValidasiNilai.status_locked') ?></option>
            </select>
        </div>

        <div class="flex items-end gap-3 lg:pb-0.5">
            <button onclick="applyFilter()" class="flex-1 px-4 py-3 bg-[<?= $color['warna_primary'] ?>] hover:bg-[<?= $color['warna_primary'] ?>]/90 text-white font-bold rounded-xl text-sm transition-transform transform hover:-translate-y-0.5 shadow-lg outline-none" style="box-shadow: 0 10px 15px -3px <?= $color['warna_primary'] ?>40;">
                <?= lang('Admin/ValidasiNilai.btn_apply') ?>
            </button>
            <button onclick="resetFilter()" class="px-4 py-3 bg-white dark:bg-slate-800 hover:bg-gray-50 dark:hover:bg-slate-700 text-gray-700 dark:text-slate-300 font-bold rounded-xl text-sm transition-colors border-2 border-gray-200 dark:border-slate-600 shadow-sm outline-none">
                <?= lang('Admin/ValidasiNilai.btn_reset') ?>
            </button>
        </div>
    </div>
</div>

<div class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-gray-100 dark:border-slate-700 overflow-hidden mb-6 transition-colors">
    <div class="overflow-x-auto custom-scrollbar">
        <table id="validasiTable" class="w-full text-left border-collapse min-w-max">
            <thead class="bg-gray-50 dark:bg-slate-900/50 border-b border-gray-100 dark:border-slate-700 transition-colors">
                <tr class="text-[11px] text-gray-500 dark:text-slate-400 uppercase tracking-widest font-black">
                    <th class="px-6 py-4"><?= lang('Admin/ValidasiNilai.th_level') ?></th>
                    <th class="px-6 py-4"><?= lang('Admin/ValidasiNilai.th_study_group') ?></th>
                    <th class="px-6 py-4"><?= lang('Admin/ValidasiNilai.th_homeroom') ?></th>
                    <th class="px-6 py-4 text-center"><?= lang('Admin/ValidasiNilai.th_academic') ?></th>
                    <th class="px-6 py-4 text-center"><?= lang('Admin/ValidasiNilai.th_status') ?></th>
                    <th class="px-6 py-4 text-center"><?= lang('Admin/ValidasiNilai.th_action') ?></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-slate-700/50 transition-colors">
                <?php foreach ($data_kelas as $row): ?>
                   <tr class="hover:bg-gray-50/50 dark:hover:bg-slate-700/30 transition-colors group cursor-pointer">
                        <td class="px-6 py-4">
                            <span class="inline-block px-3 py-1 bg-gray-100 dark:bg-slate-700 text-gray-700 dark:text-slate-300 font-bold text-xs rounded-lg border border-gray-200 dark:border-slate-600 shadow-sm transition-colors group-hover:border-[<?= $color['warna_primary'] ?>]">
                                <?= $row['tingkat'] ?>
                            </span>
                        </td>

                        <td class="px-6 py-4"><span class="font-bold text-gray-900 dark:text-white transition-colors group-hover:text-[<?= $color['warna_primary'] ?>]"><?= $row['rombel'] ?></span></td>

                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-[<?= $color['warna_primary'] ?>]/10 flex items-center justify-center font-bold text-[<?= $color['warna_primary'] ?>] border border-[<?= $color['warna_primary'] ?>]/20 group-hover:scale-105 transition-transform duration-300">
                                    <?= substr($row['wali_kelas'], 0, 2) ?>
                                </div>
                                <div class="min-w-0">
                                    <p class="font-bold text-gray-800 dark:text-white truncate transition-colors"><?= $row['wali_kelas'] ?></p>
                                    <p class="text-[11px] font-medium text-gray-500 dark:text-slate-400 transition-colors"><?= lang('Admin/ValidasiNilai.th_homeroom') ?></p>
                                </div>
                            </div>
                        </td>

                        <td class="px-6 py-4">
                            <div class="w-full max-w-[150px] mx-auto">
                                <div class="flex justify-between text-[11px] font-bold mb-1.5 transition-colors">
                                    <span class="text-gray-700 dark:text-slate-300"><?= $row['progress'] ?>%</span>
                                    <span class="text-gray-500 dark:text-slate-400"><?= $row['progress'] == 100 ? lang('Admin/ValidasiNilai.progress_complete') : lang('Admin/ValidasiNilai.progress_process') ?></span>
                                </div>
                                <div class="h-2 bg-gray-100 dark:bg-slate-700 rounded-full overflow-hidden transition-colors border border-gray-200 dark:border-slate-600">
                                    <div class="h-full rounded-full <?= $row['progress'] == 100 ? 'bg-emerald-500' : 'bg-amber-500' ?>"
                                        style="width: <?= $row['progress'] ?>%"></div>
                                </div>
                            </div>
                        </td>

                        <td class="px-6 py-4 text-center">
                            <?php
                            $badgeColor = 'bg-gray-100 dark:bg-slate-700 border-gray-200 dark:border-slate-600 text-gray-600 dark:text-slate-300';
                            if ($row['badge'] == 'success') $badgeColor = 'bg-emerald-50 dark:bg-emerald-900/30 border-emerald-200 dark:border-emerald-800/50 text-emerald-700 dark:text-emerald-400 shadow-[0_0_5px_rgba(16,185,129,0.2)]';
                            if ($row['badge'] == 'warning') $badgeColor = 'bg-amber-50 dark:bg-amber-900/30 border-amber-200 dark:border-amber-800/50 text-amber-700 dark:text-amber-400 shadow-[0_0_5px_rgba(245,158,11,0.2)]';
                            if ($row['badge'] == 'gray')    $badgeColor = 'bg-gray-100 dark:bg-slate-700 border-gray-200 dark:border-slate-600 text-gray-600 dark:text-slate-300';
                            ?>
                            <span class="inline-flex px-3 py-1 rounded-lg text-[10px] font-black uppercase tracking-wider border transition-colors <?= $badgeColor ?>">
                                <?= $row['status'] == 'Belum Lengkap' ? lang('Admin/ValidasiNilai.incomplete') : ($row['status'] == 'Siap Validasi' ? lang('Admin/ValidasiNilai.ready_to_validate') : $row['status']) ?>
                            </span>
                        </td>

                       <td class="px-6 py-4 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <button onclick="showDetailValidasi(<?= $row['id'] ?>, '<?= htmlspecialchars($row['rombel'], ENT_QUOTES, 'UTF-8') ?>', '<?= htmlspecialchars($row['wali_kelas'], ENT_QUOTES, 'UTF-8') ?>')" 
                                        class="p-2.5 bg-blue-50 dark:bg-blue-900/20 hover:bg-blue-600 text-blue-600 dark:text-blue-400 hover:text-white rounded-xl transition-all shadow-sm outline-none transform hover:scale-105" title="Lihat Detail Progress Mapel">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                </button>

                                <?php if ($row['is_locked'] == 0): ?>
                                    <button onclick="showLockModal('<?= htmlspecialchars($row['rombel'], ENT_QUOTES, 'UTF-8') ?>', <?= $row['id'] ?>)"
                                        class="p-2.5 bg-emerald-50 dark:bg-emerald-900/20 hover:bg-emerald-600 text-emerald-600 dark:text-emerald-400 hover:text-white rounded-xl transition-all shadow-sm outline-none transform hover:scale-105" title="<?= lang('Admin/ValidasiNilai.tooltip_lock') ?>">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
                                    </button>
                                <?php else: ?>
                                    <button onclick="unlockKelas('<?= htmlspecialchars($row['rombel'], ENT_QUOTES, 'UTF-8') ?>', <?= $row['id'] ?>)"
                                        class="p-2.5 bg-red-50 dark:bg-red-900/20 hover:bg-red-600 text-red-600 dark:text-red-400 hover:text-white rounded-xl transition-all shadow-sm outline-none transform hover:scale-105" title="Buka Kunci (Unlock)">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z" /></svg>
                                    </button>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('modals') ?>

<div id="detailDrawerOverlay" class="fixed inset-0 bg-gray-950/60 backdrop-blur-sm z-[99999] hidden transition-opacity duration-300 opacity-0" onclick="closeDetailDrawer()"></div>

<div id="detailDrawer" class="fixed top-0 right-0 h-full w-full max-w-md bg-white dark:bg-slate-900 shadow-2xl z-[100000] transform translate-x-full transition-transform duration-300 flex flex-col border-l border-gray-200 dark:border-slate-800">
    <div class="p-6 border-b border-gray-100 dark:border-slate-800 flex justify-between items-center transition-colors">
        <h3 class="text-xl font-black text-gray-900 dark:text-white transition-colors">Progress Detail Kelas</h3>
        <button onclick="closeDetailDrawer()" class="p-2 text-gray-400 hover:text-gray-600 dark:hover:text-slate-300 hover:bg-gray-100 dark:hover:bg-slate-800 rounded-full transition-colors outline-none">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
        </button>
    </div>

    <div id="drawerHeader" class="p-6 pb-2 border-b border-gray-100 dark:border-slate-800"></div>

    <div class="p-6 space-y-4 overflow-y-auto custom-scrollbar flex-1">
        <h4 class="font-black text-gray-800 dark:text-white flex items-center gap-2 text-sm uppercase tracking-widest transition-colors">
            <svg class="w-5 h-5 text-[<?= $color['warna_primary'] ?>]" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" /></svg> 
            Status Input Mapel
        </h4>
        
        <div id="drawerMapelList" class="space-y-3">
             <p class="text-sm text-gray-500 text-center py-4">Memuat data...</p>
        </div>
    </div>
</div>

<div id="lockModal" class="fixed inset-0 z-[99999] hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="fixed inset-0 bg-gray-950/70 backdrop-blur-sm transition-opacity" onclick="closeLockModal()"></div>
    <div class="fixed inset-0 z-10 overflow-y-auto">
        <div class="flex min-h-full items-center justify-center p-4 text-center">
            <div class="relative transform overflow-hidden rounded-3xl bg-white dark:bg-slate-800 text-left shadow-2xl transition-colors border border-transparent dark:border-slate-700 w-full max-w-md p-6 md:p-8">
                <div class="text-center mb-6">
                    <div class="w-20 h-20 rounded-full bg-emerald-50 dark:bg-emerald-900/30 border-4 border-emerald-100 dark:border-emerald-800/50 flex items-center justify-center mx-auto mb-5 shadow-sm transition-colors">
                        <svg class="w-10 h-10 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
                    </div>
                    <h3 class="text-2xl font-black text-gray-900 dark:text-white mb-2 transition-colors"><?= lang('Admin/ValidasiNilai.modal_lock_title') ?></h3>
                    <p class="text-sm font-medium text-gray-600 dark:text-slate-400 mb-6 transition-colors"><?= lang('Admin/ValidasiNilai.modal_lock_subtitle') ?> <br><span id="lockKelas" class="font-bold text-[<?= $color['warna_primary'] ?>] text-base mt-1 inline-block"></span></p>

                    <div class="p-5 bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800/50 rounded-2xl text-left mb-6 shadow-sm transition-colors">
                        <p class="text-sm font-black text-amber-900 dark:text-amber-400 mb-3 uppercase tracking-wider flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                            <?= lang('Admin/ValidasiNilai.security_warning') ?>
                        </p>
                        <ul class="text-xs font-medium text-amber-800 dark:text-amber-300 space-y-2">
                            <li class="flex items-start gap-2"><span class="text-amber-500">•</span> <span>Guru tidak dapat lagi mengubah nilai setelah ini.</span></li>
                            <li class="flex items-start gap-2"><span class="text-amber-500">•</span> <span>Wali kelas bisa mencetak rapor.</span></li>
                        </ul>
                    </div>

                    <label class="flex items-start gap-3 p-4 bg-white dark:bg-slate-800 border-2 border-gray-200 dark:border-slate-600 rounded-xl cursor-pointer hover:border-[<?= $color['warna_primary'] ?>] transition-colors mb-2 group shadow-sm text-left">
                        <input type="checkbox" id="confirmCheck" class="w-5 h-5 text-[<?= $color['warna_primary'] ?>] focus:ring-[<?= $color['warna_primary'] ?>] rounded border-gray-300 dark:border-slate-500 outline-none cursor-pointer mt-0.5">
                        <span class="text-sm font-bold text-gray-700 dark:text-slate-300 group-hover:text-gray-900 dark:group-hover:text-white transition-colors"><?= lang('Admin/ValidasiNilai.confirm_checkbox') ?></span>
                    </label>
                </div>

                <div class="flex gap-3">
                    <button onclick="closeLockModal()" class="flex-1 px-4 py-3.5 bg-gray-100 dark:bg-slate-700 hover:bg-gray-200 dark:hover:bg-slate-600 text-gray-800 dark:text-white font-bold rounded-xl transition-colors outline-none shadow-sm text-sm">Batal</button>
                    <button onclick="confirmLock()" class="flex-1 px-4 py-3.5 bg-[<?= $color['warna_primary'] ?>] hover:bg-[<?= $color['warna_primary'] ?>]/90 text-white font-bold rounded-xl transition-transform transform hover:-translate-y-0.5 shadow-lg flex items-center justify-center gap-2 outline-none text-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
                        Kunci Sekarang
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    window.BASE_URL = "<?= rtrim(base_url(), '/') ?>/";
    window.CSRF_NAME = "<?= csrf_token() ?>";
    window.CSRF_TOKEN = "<?= csrf_hash() ?>";
</script>
<script src="<?= base_url('assets/js/Admin/validasi-nilai.js') ?>?v=<?= time() ?>"></script>
<?= $this->endSection() ?>