<?= $this->extend('layout/main') ?>

<?= $this->section('title') ?>
<?= lang('WaliKelas/DaftarSiswa.page_title') ?> - Rapor Digital
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<link rel="stylesheet" href="<?= base_url('assets/css/WaliKelas/daftar-siswa.css') ?>">
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
    .focus-tema:focus {
        border-color: var(--warna-primary) !important;
        outline: none;
        box-shadow: 0 0 0 3px color-mix(in srgb, var(--warna-primary) 20%, transparent);
    }

    .hover-bg-tema:hover {
        background-color: var(--warna-primary) !important;
        color: white !important;
    }

    /* Dark Mode Overrides */
    html.dark .text-tema { color: color-mix(in srgb, var(--warna-primary) 80%, white) !important; }
    html.dark .bg-tema-light { background-color: rgba(255, 255, 255, 0.05) !important; }
    html.dark .bg-white { background-color: #1e293b !important; border-color: #334155 !important; }
    html.dark .text-gray-800 { color: #f1f5f9 !important; }
    html.dark .text-gray-600 { color: #cbd5e1 !important; }
    html.dark .text-gray-500 { color: #94a3b8 !important; }
    html.dark .bg-gray-50 { background-color: #0f172a !important; }
    html.dark .border-gray-100 { border-color: #334155 !important; }
    html.dark .border-gray-200 { border-color: #475569 !important; }
    html.dark .divide-gray-100> :not([hidden])~ :not([hidden]) { border-color: #334155 !important; }
    html.dark .custom-scrollbar::-webkit-scrollbar-thumb { background-color: var(--warna-primary); }

    .status-dot { display: inline-block; width: 8px; height: 8px; border-radius: 50%; }
    .status-dot.safe { background-color: #10b981; box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.2); }
    .status-dot.warning { background-color: #f59e0b; box-shadow: 0 0 0 3px rgba(245, 158, 11, 0.2); }
    .status-dot.danger { background-color: #ef4444; box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.2); }

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

<?php if (!$rombel): ?>
    <div class="bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800/50 rounded-xl p-6 text-center mb-6">
        <h3 class="text-lg font-bold text-amber-800 dark:text-amber-500 mb-2"><?= lang('WaliKelas/DaftarSiswa.err_no_access_title') ?></h3>
        <p class="text-sm text-amber-700 dark:text-amber-400"><?= lang('WaliKelas/DaftarSiswa.err_no_access_desc') ?></p>
    </div>
<?php else: ?>

    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white"><?= lang('WaliKelas/DaftarSiswa.page_title') ?></h1>
            <p class="text-sm text-gray-500 dark:text-slate-400 mt-1"><?= lang('WaliKelas/DaftarSiswa.class_text') ?> <span class="font-semibold text-tema"><?= esc($rombel['nama_rombel'] ?? '-') ?></span> • <?= esc($tahun_ajaran) ?> <?= lang('WaliKelas/DaftarSiswa.semester_text') ?> <?= esc($semester) ?></p>
        </div>
    </div>

    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 lg:gap-4 mb-6">
        <div class="bg-white dark:bg-slate-800 rounded-xl p-4 shadow-sm border border-gray-100 dark:border-slate-700 hover:shadow-md transition-shadow">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs lg:text-sm text-gray-500 dark:text-slate-400 mb-1"><?= lang('WaliKelas/DaftarSiswa.stat_total') ?></p>
                    <p class="text-2xl lg:text-3xl font-bold text-gray-800 dark:text-white"><?= $statistik['total_siswa'] ?? 0 ?></p>
                </div>
                <div class="w-10 h-10 lg:w-12 lg:h-12 rounded-xl bg-tema-light text-tema flex items-center justify-center">
                    <svg class="w-5 h-5 lg:w-6 lg:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-800 rounded-xl p-4 shadow-sm border border-gray-100 dark:border-slate-700 hover:shadow-md transition-shadow">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs lg:text-sm text-gray-500 dark:text-slate-400 mb-1"><?= lang('WaliKelas/DaftarSiswa.stat_present_today') ?></p>
                    <p class="text-2xl lg:text-3xl font-bold text-blue-600 dark:text-blue-400"><?= $statistik['hadir_hari_ini'] ?? 0 ?></p>
                    <p class="text-xs text-gray-400 mt-1"><?= $statistik['persen_hadir'] ?? 0 ?>%</p>
                </div>
                <div class="w-10 h-10 lg:w-12 lg:h-12 rounded-xl bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 flex items-center justify-center">
                    <svg class="w-5 h-5 lg:w-6 lg:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" /></svg>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-800 rounded-xl p-4 shadow-sm border border-gray-100 dark:border-slate-700 hover:shadow-md transition-shadow">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs lg:text-sm text-gray-500 dark:text-slate-400 mb-1"><?= lang('WaliKelas/DaftarSiswa.stat_needs_guidance') ?></p>
                    <p class="text-2xl lg:text-3xl font-bold text-amber-600 dark:text-amber-500"><?= $statistik['perlu_pembinaan'] ?? 0 ?></p>
                    <p class="text-xs text-gray-400 mt-1"><?= lang('WaliKelas/DaftarSiswa.lbl_students') ?></p>
                </div>
                <div class="w-10 h-10 lg:w-12 lg:h-12 rounded-xl bg-amber-50 dark:bg-amber-900/30 text-amber-600 dark:text-amber-500 flex items-center justify-center">
                    <svg class="w-5 h-5 lg:w-6 lg:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-800 rounded-xl p-4 shadow-sm border border-gray-100 dark:border-slate-700 hover:shadow-md transition-shadow">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs lg:text-sm text-gray-500 dark:text-slate-400 mb-1"><?= lang('WaliKelas/DaftarSiswa.stat_tahfidz_target') ?></p>
                    <p class="text-2xl lg:text-3xl font-bold text-purple-600 dark:text-purple-400">--</p>
                    <p class="text-xs text-gray-400 mt-1"><?= lang('WaliKelas/DaftarSiswa.lbl_waiting_data') ?></p>
                </div>
                <div class="w-10 h-10 lg:w-12 lg:h-12 rounded-xl bg-purple-50 dark:bg-purple-900/30 text-purple-600 dark:text-purple-400 flex items-center justify-center">
                    <svg class="w-5 h-5 lg:w-6 lg:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-gray-100 dark:border-slate-700 p-4 lg:p-5 mb-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="font-semibold text-gray-800 dark:text-slate-100"><?= lang('WaliKelas/DaftarSiswa.filter_title') ?></h2>
            <button onclick="resetFilters()" class="text-xs text-tema hover-text-tema font-bold transition-all px-3 py-1.5 bg-tema-light dark:bg-slate-700 rounded-lg"><?= lang('WaliKelas/DaftarSiswa.btn_reset') ?></button>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="relative">
                <label class="text-xs font-bold text-gray-600 dark:text-slate-400 mb-1.5 block"><?= lang('WaliKelas/DaftarSiswa.filter_search') ?></label>
                <div class="relative">
                    <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                    <input type="text" id="searchInput" placeholder="<?= lang('WaliKelas/DaftarSiswa.search_ph') ?>" class="w-full pl-10 pr-3 py-2 bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-600 rounded-lg text-sm text-gray-800 dark:text-slate-200 placeholder-gray-400 focus-tema transition-all" onkeyup="filterTable()">
                </div>
            </div>

            <div>
                <label class="text-xs font-bold text-gray-600 dark:text-slate-400 mb-1.5 block"><?= lang('WaliKelas/DaftarSiswa.filter_status') ?></label>
                <select id="statusFilter" class="w-full px-3 py-2 bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-600 text-gray-800 dark:text-slate-200 rounded-lg text-sm focus-tema transition-all cursor-pointer" onchange="filterTable()">
                    <option value=""><?= lang('WaliKelas/DaftarSiswa.opt_all_status') ?></option>
                    <option value="Aktif"><?= lang('WaliKelas/DaftarSiswa.opt_active') ?></option>
                    <option value="Perlu Pembinaan"><?= lang('WaliKelas/DaftarSiswa.opt_needs_guidance') ?></option>
                </select>
            </div>

            <div>
                <label class="text-xs font-bold text-gray-600 dark:text-slate-400 mb-1.5 block"><?= lang('WaliKelas/DaftarSiswa.filter_academic') ?></label>
                <select id="akademikFilter" class="w-full px-3 py-2 bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-600 text-gray-800 dark:text-slate-200 rounded-lg text-sm focus-tema transition-all cursor-pointer" onchange="filterTable()">
                    <option value=""><?= lang('WaliKelas/DaftarSiswa.opt_all') ?></option>
                    <option value="Aman"><?= lang('WaliKelas/DaftarSiswa.opt_safe_grade') ?></option>
                    <option value="Perlu Perhatian"><?= lang('WaliKelas/DaftarSiswa.opt_prob_grade') ?></option>
                </select>
            </div>

            <div>
                <label class="text-xs font-bold text-gray-600 dark:text-slate-400 mb-1.5 block"><?= lang('WaliKelas/DaftarSiswa.filter_tahfidz') ?></label>
                <select id="tahfidzFilter" class="w-full px-3 py-2 bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-600 text-gray-800 dark:text-slate-200 rounded-lg text-sm focus-tema transition-all cursor-pointer" onchange="filterTable()">
                    <option value=""><?= lang('WaliKelas/DaftarSiswa.opt_all') ?></option>
                    <option value="Sesuai Target"><?= lang('WaliKelas/DaftarSiswa.opt_on_target') ?></option>
                    <option value="Di Bawah Target"><?= lang('WaliKelas/DaftarSiswa.opt_no_data') ?></option>
                </select>
            </div>
        </div>
    </div>

    <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-gray-100 dark:border-slate-700 overflow-hidden mb-6">
        <div class="p-4 lg:p-5 border-b border-gray-100 dark:border-slate-700 flex items-center justify-between">
            <h2 class="font-bold text-gray-800 dark:text-slate-100"><?= lang('WaliKelas/DaftarSiswa.list_title') ?></h2>
            <div class="flex items-center gap-2">
                <button class="p-2 bg-gray-50 dark:bg-slate-700 text-gray-600 dark:text-slate-300 hover:bg-tema hover:text-white rounded-lg transition-all tooltip relative" aria-label="<?= lang('WaliKelas/DaftarSiswa.btn_export') ?>">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                </button>
            </div>
        </div>

        <div class="overflow-x-auto custom-scrollbar">
            <table class="w-full whitespace-nowrap" id="studentTable">
                <thead class="bg-gray-50/80 dark:bg-slate-900/50">
                    <tr>
                        <th class="px-5 py-4 text-left text-xs font-bold text-gray-500 dark:text-slate-400 uppercase tracking-wider w-16"><?= lang('WaliKelas/DaftarSiswa.th_no') ?></th>
                        <th class="px-5 py-4 text-left text-xs font-bold text-gray-500 dark:text-slate-400 uppercase tracking-wider"><?= lang('WaliKelas/DaftarSiswa.th_student_data') ?></th>
                        <th class="px-5 py-4 text-center text-xs font-bold text-gray-500 dark:text-slate-400 uppercase tracking-wider"><?= lang('WaliKelas/DaftarSiswa.th_academic') ?></th>
                        <th class="px-5 py-4 text-center text-xs font-bold text-gray-500 dark:text-slate-400 uppercase tracking-wider"><?= lang('WaliKelas/DaftarSiswa.th_attendance') ?></th>
                        <th class="px-5 py-4 text-center text-xs font-bold text-gray-500 dark:text-slate-400 uppercase tracking-wider"><?= lang('WaliKelas/DaftarSiswa.th_tahfidz') ?></th>
                        <th class="px-5 py-4 text-center text-xs font-bold text-gray-500 dark:text-slate-400 uppercase tracking-wider"><?= lang('WaliKelas/DaftarSiswa.th_notes_status') ?></th>
                        <th class="px-5 py-4 text-center text-xs font-bold text-gray-500 dark:text-slate-400 uppercase tracking-wider sticky right-0 bg-gray-50/80 dark:bg-slate-900/50 z-10 backdrop-blur-sm"><?= lang('WaliKelas/DaftarSiswa.th_action') ?></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-slate-700/50" id="tableBody">
                    <?php if (empty($siswa_kelas)): ?>
                        <tr>
                            <td colspan="7" class="px-5 py-10 text-center text-gray-500 dark:text-slate-400 font-medium"><?= lang('WaliKelas/DaftarSiswa.empty_data') ?></td>
                        </tr>
                    <?php else: ?>
                        <?php $no = 1;
                        foreach ($siswa_kelas as $s):
                            $akademik_status = ($s['rata_nilai'] > 0 && $s['rata_nilai'] < 75) ? 'Perlu Perhatian' : 'Aman';
                            $akademik_color  = ($s['rata_nilai'] > 0 && $s['rata_nilai'] < 75) ? 'red' : 'emerald';
                            $akademik_dot    = ($s['rata_nilai'] > 0 && $s['rata_nilai'] < 75) ? 'danger' : 'safe';
                            $akademik_lang   = ($s['rata_nilai'] > 0 && $s['rata_nilai'] < 75) ? lang('WaliKelas/DaftarSiswa.badge_needs_attn') : lang('WaliKelas/DaftarSiswa.badge_safe');

                            $tahfidz_status = $s['capaian_tahfidz'] == 'Proses' ? 'Belum Ada' : 'Sesuai Target';
                            $tahfidz_color  = $s['capaian_tahfidz'] == 'Proses' ? 'amber' : 'emerald';
                            $tahfidz_dot    = $s['capaian_tahfidz'] == 'Proses' ? 'warning' : 'safe';
                            $tahfidz_lang   = $s['capaian_tahfidz'] == 'Proses' ? lang('WaliKelas/DaftarSiswa.badge_progress') : esc($s['capaian_tahfidz']);

                            $catatan_color = $s['tipe_catatan'] == 'Tidak ada' ? 'slate' : 'rose';
                            $catatan_bg = $s['tipe_catatan'] == 'Tidak ada' ? 'bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300' : 'bg-rose-100 dark:bg-rose-900/30 text-rose-700 dark:text-rose-400 border border-rose-200 dark:border-rose-800';
                            $catatan_lang = $s['tipe_catatan'] == 'Tidak ada' ? lang('WaliKelas/DaftarSiswa.badge_no_notes') : $s['tipe_catatan'];

                            $themes = ['emerald', 'blue', 'amber', 'purple', 'teal'];
                            $theme = $themes[strlen($s['nama_lengkap']) % count($themes)];

                            $jsonSiswa = htmlspecialchars(json_encode($s), ENT_QUOTES, 'UTF-8');
                        ?>
                            <tr class="hover:bg-gray-50 dark:hover:bg-slate-700/50 transition-colors student-row group"
                                data-name="<?= esc($s['nama_lengkap']) ?>"
                                data-nis="<?= esc($s['nis']) ?>"
                                data-status="Aktif"
                                data-akademik="<?= $akademik_status ?>"
                                data-tahfidz="<?= $tahfidz_status ?>">

                                <td class="px-5 py-4 text-sm text-gray-500 dark:text-slate-400 font-medium"><?= $no++ ?></td>

                                <td class="px-5 py-4">
                                    <div class="flex items-center gap-4">
                                        <div class="w-10 h-10 rounded-full bg-<?= $theme ?>-100 dark:bg-<?= $theme ?>-900/30 text-<?= $theme ?>-600 dark:text-<?= $theme ?>-400 border border-<?= $theme ?>-200 dark:border-<?= $theme ?>-800/50 flex items-center justify-center font-bold text-sm shadow-sm group-hover:scale-105 transition-transform overflow-hidden">
                                    <?php 
                                        // Logika Hybrid Langsung di View
                                        $inisial = strtoupper(substr($s['nama_lengkap'], 0, 2));
                                        $fotoFinal = !empty($s['foto_profil']) ? $s['foto_profil'] : (!empty($s['foto_siswa']) ? $s['foto_siswa'] : ($s['foto_fix'] ?? ''));
                                    ?>
                                    <?php if (!empty($fotoFinal)): ?>
                                        <?php $cacheBuster = '?v=' . time(); ?>
                                        <img src="<?= base_url('assets/uploads/avatars/' . $fotoFinal) . $cacheBuster ?>" 
                                             alt="Foto" 
                                             class="w-full h-full object-cover" 
                                             onerror="this.onerror=function(){ this.outerHTML='<?= $inisial ?>'; }; this.src='<?= base_url('uploads/siswa/' . $fotoFinal) . $cacheBuster ?>';">
                                    <?php else: ?>
                                        <?= $inisial ?>
                                    <?php endif; ?>
                                </div>
                                        <div>
                                            <p class="font-bold text-gray-800 dark:text-slate-200 text-sm group-hover:text-tema transition-colors"><?= esc($s['nama_lengkap']) ?></p>
                                            <p class="text-[11px] text-gray-500 dark:text-slate-400 font-mono mt-0.5 border border-gray-200 dark:border-slate-600 rounded bg-white dark:bg-slate-800 px-1 inline-block">NIS: <?= esc($s['nis']) ?></p>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-5 py-4 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <span class="status-dot <?= $akademik_dot ?>"></span>
                                        <span class="inline-block px-2.5 py-1 bg-<?= $akademik_color ?>-50 dark:bg-<?= $akademik_color ?>-900/20 text-<?= $akademik_color ?>-700 dark:text-<?= $akademik_color ?>-400 text-[11px] rounded-md font-bold uppercase tracking-wider border border-<?= $akademik_color ?>-200 dark:border-<?= $akademik_color ?>-800/50">
                                            <?= $akademik_lang ?>
                                        </span>
                                    </div>
                                </td>

                                <td class="px-5 py-4 text-center">
                                    <div class="inline-flex flex-col items-center justify-center">
                                        <p class="text-sm font-bold text-gray-800 dark:text-slate-200"><?= $s['persen_absen'] ?>%</p>
                                        <p class="text-[10px] text-gray-500 dark:text-slate-400 bg-gray-100 dark:bg-slate-700 px-1.5 rounded mt-0.5"><?= $s['rekap_absen'] ?></p>
                                    </div>
                                </td>

                                <td class="px-5 py-4 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <span class="status-dot <?= $tahfidz_dot ?>"></span>
                                        <p class="text-[12px] font-bold text-<?= $tahfidz_color ?>-600 dark:text-<?= $tahfidz_color ?>-400 bg-<?= $tahfidz_color ?>-50 dark:bg-<?= $tahfidz_color ?>-900/20 px-2 py-0.5 rounded border border-<?= $tahfidz_color ?>-200 dark:border-<?= $tahfidz_color ?>-800/50"><?= $tahfidz_lang ?></p>
                                    </div>
                                </td>

                                <td class="px-5 py-4 text-center">
                                    <span class="inline-block px-3 py-1 text-[11px] rounded-md font-bold <?= $catatan_bg ?>">
                                        <?= $catatan_lang ?>
                                    </span>
                                </td>

                                <td class="px-5 py-4 text-center sticky right-0 bg-white dark:bg-slate-800 group-hover:bg-gray-50 dark:group-hover:bg-slate-700/50 transition-colors border-l border-gray-50 dark:border-slate-700/50 z-10">
                                    <div class="flex items-center justify-center gap-1.5">
                                        <button onclick="openProfileModal(<?= $jsonSiswa ?>)" class="p-1.5 text-gray-400 hover:text-white bg-gray-50 dark:bg-slate-700 hover:bg-blue-500 rounded-md transition-all shadow-sm" title="<?= lang('WaliKelas/DaftarSiswa.tooltip_profile') ?>">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                        </button>

                                        <?php if (has_permission('wali_kelas', 'create')): ?>
                                            <button onclick="openNoteModal('<?= $s['id'] ?>', '<?= addslashes($s['nama_lengkap']) ?>')" class="p-1.5 text-gray-400 hover:text-white bg-gray-50 dark:bg-slate-700 hover:bg-amber-500 rounded-md transition-all shadow-sm" title="<?= lang('WaliKelas/DaftarSiswa.tooltip_note') ?>">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" /></svg>
                                            </button>
                                        <?php endif; ?>
                                        <?php if (has_permission('wali_kelas', 'update')): ?>
                                            <button onclick="openEditModal('<?= $s['id'] ?>', this)" class="p-1.5 text-gray-400 hover:text-white bg-gray-50 dark:bg-slate-700 hover:bg-emerald-500 rounded-md transition-all shadow-sm" title="<?= lang('WaliKelas/DaftarSiswa.tooltip_edit') ?>">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                            </button>
                                        <?php endif; ?>

                                        <button onclick="openRaporModal(<?= $jsonSiswa ?>)" class="p-1.5 text-gray-400 hover:text-white bg-gray-50 dark:bg-slate-700 hover-bg-tema rounded-md transition-all shadow-sm" title="<?= lang('WaliKelas/DaftarSiswa.tooltip_report') ?>">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-gray-100 dark:border-slate-700 flex items-center justify-between bg-gray-50/50 dark:bg-slate-800/80">
            <p class="text-xs text-gray-500 dark:text-slate-400"><?= lang('WaliKelas/DaftarSiswa.showing_text_1') ?> <span class="font-bold text-tema" id="visibleCount"><?= count($siswa_kelas ?? []) ?></span> <?= lang('WaliKelas/DaftarSiswa.showing_text_2') ?></p>
        </div>
    </div>

<?php endif; ?>
<?= $this->endSection() ?>

<?= $this->section('modals') ?>

<div id="noteModal" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4">
    <div class="modal-overlay absolute inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" onclick="closeNoteModal()"></div>
    <div class="modal-content relative bg-white dark:bg-slate-800 rounded-3xl shadow-2xl w-full max-w-md overflow-hidden transform scale-95 transition-all flex flex-col">
        <div class="bg-tema p-5 text-white flex items-center justify-between">
            <h2 id="noteModalTitle" class="text-lg font-bold">Catatan Wali Kelas</h2>
            <button onclick="closeNoteModal()" class="p-1.5 bg-white/20 hover:bg-white/30 rounded-lg transition-colors"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg></button>
        </div>
        <div class="p-6 space-y-4">
            <input type="hidden" id="noteSiswaId">
            <div>
                <label class="text-xs font-bold text-gray-600 dark:text-slate-400 mb-1 block"><?= lang('WaliKelas/DaftarSiswa.modal_note_type') ?></label>
                <select class="w-full px-4 py-3 bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-700 rounded-xl focus-tema outline-none text-sm text-gray-700 dark:text-slate-200">
                    <option><?= lang('WaliKelas/DaftarSiswa.note_opt_1') ?></option>
                    <option><?= lang('WaliKelas/DaftarSiswa.note_opt_2') ?></option>
                    <option><?= lang('WaliKelas/DaftarSiswa.note_opt_3') ?></option>
                </select>
            </div>
            <div>
                <label class="text-xs font-bold text-gray-600 dark:text-slate-400 mb-1 block"><?= lang('WaliKelas/DaftarSiswa.modal_note_content') ?></label>
                <textarea placeholder="<?= lang('WaliKelas/DaftarSiswa.note_placeholder') ?>" class="w-full px-4 py-3 bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-700 rounded-xl focus-tema outline-none text-sm text-gray-700 dark:text-slate-200 resize-none h-28"></textarea>
            </div>
        </div>
        <div class="p-5 border-t border-gray-100 dark:border-slate-700 flex gap-3 bg-gray-50/50 dark:bg-slate-800/80">
            <button onclick="closeNoteModal()" class="flex-1 py-2.5 px-4 border border-gray-300 dark:border-slate-600 text-gray-600 dark:text-slate-300 font-bold rounded-xl hover:bg-gray-100 dark:hover:bg-slate-700 transition-colors"><?= lang('WaliKelas/DaftarSiswa.btn_cancel') ?></button>
            <button onclick="saveNote(event)" class="flex-1 py-2.5 px-4 bg-tema text-white font-bold rounded-xl hover-bg-tema transition-colors shadow-lg shadow-[var(--warna-primary)]/20"><?= lang('WaliKelas/DaftarSiswa.btn_save') ?></button>
        </div>
    </div>
</div>

<div id="profileModal" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4 lg:p-10">
    <div class="modal-overlay absolute inset-0 bg-slate-900/60 backdrop-blur-sm" onclick="closeProfileModal()"></div>
    <div class="relative bg-white dark:bg-slate-800 rounded-3xl shadow-2xl w-full max-w-3xl max-h-[90vh] overflow-hidden flex flex-col transform scale-95 transition-all" id="profileModalContent">
        <div class="p-5 border-b border-gray-100 dark:border-slate-700 flex items-center justify-between bg-slate-50/80 dark:bg-slate-800/80 backdrop-blur-md">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-tema-light dark:bg-slate-700 text-tema flex items-center justify-center"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg></div>
                <h2 id="profileModalTitle" class="text-xl font-bold text-gray-800 dark:text-white"><?= lang('WaliKelas/DaftarSiswa.modal_prof_title') ?></h2>
            </div>
            <button onclick="closeProfileModal()" class="p-2 text-gray-400 hover:bg-gray-100 dark:hover:bg-slate-700 rounded-xl transition-all"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg></button>
        </div>

        <div class="flex-1 overflow-y-auto p-6 custom-scrollbar">
            <div class="flex flex-col md:flex-row items-center md:items-start gap-6 mb-8 border-b border-gray-100 dark:border-slate-700 pb-8">
                <div id="profileInitial" class="w-24 h-24 rounded-full bg-gradient-to-br from-tema to-teal-700 flex items-center justify-center text-white text-4xl font-extrabold shadow-xl shrink-0 border-4 border-white dark:border-slate-800 overflow-hidden"></div>
                
                <div class="text-center md:text-left flex-1">
                    <h3 id="profileName" class="text-2xl font-bold text-gray-800 dark:text-white mb-2">Nama Siswa</h3>
                    <div class="flex flex-wrap justify-center md:justify-start gap-2 text-sm">
                        <span class="bg-gray-100 dark:bg-slate-700 text-gray-600 dark:text-slate-300 px-3 py-1 rounded-lg font-mono font-medium">NIS: <span id="profileNis">-</span></span>
                        <span class="bg-gray-100 dark:bg-slate-700 text-gray-600 dark:text-slate-300 px-3 py-1 rounded-lg font-mono font-medium">NISN: <span id="profileNisn">-</span></span>
                        <span class="bg-tema-light dark:bg-slate-700 text-tema px-3 py-1 rounded-lg font-bold"><span id="prof_jk">-</span></span>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div class="bg-gray-50 dark:bg-slate-900/50 p-5 rounded-2xl border border-gray-100 dark:border-slate-700/50">
                    <div class="flex items-center gap-2 mb-4">
                        <div class="w-8 h-8 rounded-lg bg-blue-100 dark:bg-blue-900/30 text-blue-600 flex items-center justify-center"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg></div>
                        <h4 class="font-bold text-gray-800 dark:text-slate-100"><?= lang('WaliKelas/DaftarSiswa.prof_acad_achieve') ?></h4>
                    </div>
                    <div class="space-y-3">
                        <div class="flex items-center justify-between"><span class="text-sm text-gray-500 dark:text-slate-400"><?= lang('WaliKelas/DaftarSiswa.prof_avg_grade') ?></span> <span id="prof_rata" class="font-bold text-xl text-gray-800 dark:text-white">0</span></div>
                        <div class="flex items-center justify-between"><span class="text-sm text-gray-500 dark:text-slate-400"><?= lang('WaliKelas/DaftarSiswa.prof_grad_status') ?></span> <span id="prof_status_akademik" class="px-3 py-1 bg-gray-200 dark:bg-slate-700 text-gray-700 dark:text-slate-300 text-xs rounded-lg font-bold uppercase">-</span></div>
                    </div>
                </div>

                <div class="bg-gray-50 dark:bg-slate-900/50 p-5 rounded-2xl border border-gray-100 dark:border-slate-700/50">
                    <div class="flex items-center gap-2 mb-4">
                        <div class="w-8 h-8 rounded-lg bg-emerald-100 dark:bg-emerald-900/30 text-emerald-600 flex items-center justify-center"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg></div>
                        <h4 class="font-bold text-gray-800 dark:text-slate-100"><?= lang('WaliKelas/DaftarSiswa.prof_attendance') ?></h4>
                    </div>
                    <div class="grid grid-cols-3 gap-2 text-center mb-3">
                        <div class="bg-white dark:bg-slate-800 py-2 rounded-lg border border-gray-100 dark:border-slate-700">
                            <p class="text-xl font-bold text-emerald-600" id="prof_h">0</p>
                            <p class="text-[10px] text-gray-400 uppercase font-bold"><?= lang('WaliKelas/DaftarSiswa.prof_h') ?></p>
                        </div>
                        <div class="bg-white dark:bg-slate-800 py-2 rounded-lg border border-gray-100 dark:border-slate-700">
                            <p class="text-xl font-bold text-amber-500" id="prof_s">0</p>
                            <p class="text-[10px] text-gray-400 uppercase font-bold"><?= lang('WaliKelas/DaftarSiswa.prof_s') ?></p>
                        </div>
                        <div class="bg-white dark:bg-slate-800 py-2 rounded-lg border border-gray-100 dark:border-slate-700">
                            <p class="text-xl font-bold text-red-500" id="prof_a">0</p>
                            <p class="text-[10px] text-gray-400 uppercase font-bold"><?= lang('WaliKelas/DaftarSiswa.prof_a') ?></p>
                        </div>
                    </div>
                    <div class="flex items-center justify-between pt-2"><span class="text-xs font-bold text-gray-500 dark:text-slate-400"><?= lang('WaliKelas/DaftarSiswa.prof_attn_percent') ?></span> <span id="prof_persen_absen" class="font-bold text-tema">0%</span></div>
                </div>

                <div class="bg-gray-50 dark:bg-slate-900/50 p-5 rounded-2xl border border-gray-100 dark:border-slate-700/50 md:col-span-2">
                    <div class="flex items-center gap-2 mb-3">
                        <div class="w-8 h-8 rounded-lg bg-purple-100 dark:bg-purple-900/30 text-purple-600 flex items-center justify-center"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg></div>
                        <h4 class="font-bold text-gray-800 dark:text-slate-100"><?= lang('WaliKelas/DaftarSiswa.prof_tahfidz_prog') ?></h4>
                    </div>
                    <div class="bg-white dark:bg-slate-800 p-4 rounded-xl border border-gray-100 dark:border-slate-700 flex items-center justify-between">
                        <span class="text-sm text-gray-500 dark:text-slate-400"><?= lang('WaliKelas/DaftarSiswa.prof_last_tahfidz') ?></span>
                        <span id="prof_capaian_tahfidz" class="font-bold text-lg text-purple-600 dark:text-purple-400">-</span>
                    </div>
                </div>
            </div>

            <div class="mt-6 pt-6 border-t border-gray-100 dark:border-slate-700 flex gap-3">
                <?php if (has_permission('wali_kelas', 'update')): ?>
                    <button id="btnOpenEdit" class="flex-1 py-3 px-4 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-2xl transition-all shadow-lg shadow-emerald-500/20 flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                        Edit Biodata Siswa
                    </button>
                <?php endif; ?>
                <button onclick="closeProfileModal()" class="flex-1 py-3 px-4 bg-gray-100 dark:bg-slate-700 text-gray-600 dark:text-slate-300 font-bold rounded-2xl hover:bg-gray-200 transition-all"><?= lang('WaliKelas/DaftarSiswa.btn_cancel') ?></button>
            </div>
        </div>
    </div>
</div>



<div id="raporModal" class="fixed inset-0 z-[70] hidden flex items-center justify-center p-4">
    <div class="modal-overlay absolute inset-0 bg-slate-900/80 backdrop-blur-sm" onclick="closeRaporModal()"></div>
    <div class="relative bg-white dark:bg-slate-800 rounded-3xl shadow-2xl w-full max-w-4xl max-h-[90vh] overflow-hidden flex flex-col transform scale-95 transition-all">
        <div class="bg-tema p-5 text-white flex items-center justify-between">
            <h2 class="text-lg font-bold"><?= lang('WaliKelas/DaftarSiswa.modal_rapor_title') ?></h2>
            <button onclick="closeRaporModal()" class="p-1.5 bg-white/20 hover:bg-white/30 rounded-lg transition-colors"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg></button>
        </div>
        <div class="flex-1 overflow-y-auto p-6 bg-gray-50/50 dark:bg-slate-900/50 custom-scrollbar">
            <div class="bg-white dark:bg-slate-800 max-w-3xl mx-auto shadow-sm border border-gray-200 dark:border-slate-700 rounded-xl p-8 lg:p-12">
                <div class="text-center mb-8 pb-6 border-b-4 border-gray-800 dark:border-slate-500">
                    <h3 class="text-2xl font-black text-gray-900 dark:text-white uppercase tracking-widest mb-1"><?= lang('WaliKelas/DaftarSiswa.rapor_header_1') ?></h3>
                    <p class="text-sm font-bold text-gray-600 dark:text-slate-400 uppercase tracking-widest"><?= esc($nama_sekolah ?? 'SMPIT Ad Durrah') ?></p>
                </div>

                <div class="grid grid-cols-2 gap-4 text-sm mb-8 bg-gray-50 dark:bg-slate-900/50 p-4 rounded-lg font-mono">
                    <div><span class="text-gray-500 block text-xs"><?= lang('WaliKelas/DaftarSiswa.rapor_stu_name') ?></span><strong class="text-base text-gray-800 dark:text-white" id="raporStudentName">-</strong></div>
                    <div><span class="text-gray-500 block text-xs"><?= lang('WaliKelas/DaftarSiswa.rapor_stu_nis') ?></span><strong class="text-base text-gray-800 dark:text-white" id="raporStudentNIS">-</strong></div>
                </div>

                <div class="mb-8">
                    <h4 class="font-bold text-gray-800 dark:text-white mb-3 text-lg bg-tema-light dark:bg-slate-700 px-3 py-1 border-l-4 border-tema"><?= lang('WaliKelas/DaftarSiswa.rapor_sec_a') ?></h4>
                    <p class="text-sm text-gray-700 dark:text-slate-300 italic border border-dashed border-gray-300 dark:border-slate-600 p-4 rounded-lg" id="raporCatatan">-</p>
                </div>

                <div class="mb-8">
                    <h4 class="font-bold text-gray-800 dark:text-white mb-3 text-lg bg-tema-light dark:bg-slate-700 px-3 py-1 border-l-4 border-tema"><?= lang('WaliKelas/DaftarSiswa.rapor_sec_b') ?></h4>
                    <div id="raporNilaiContainer" class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- MODAL EDIT BIODATA LENGKAP -->
<div id="addModal" class="fixed inset-0 z-[100] hidden">
    <div class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" onclick="closeAddModal()"></div>
    <div class="absolute inset-0 flex items-center justify-center p-4 pointer-events-none lg:left-64">
        <div class="relative w-full bg-white dark:bg-slate-800 rounded-2xl shadow-2xl flex flex-col max-h-[95vh] pointer-events-auto border border-transparent dark:border-slate-700 transition-colors duration-300" style="max-width: 1100px;">

            <div class="p-5 border-b border-gray-100 dark:border-slate-700 flex items-center justify-between bg-white dark:bg-slate-800 rounded-t-2xl z-20 flex-shrink-0 transition-colors">
                <div>
                    <h3 class="text-xl font-bold text-gray-800 dark:text-white"><?= lang('Admin/Siswa.js_edit_student') ?></h3>
                    <p class="text-sm text-gray-500 dark:text-slate-400 mt-1"><?= lang('Admin/Siswa.form_subtitle') ?></p>
                </div>
                <button type="button" onclick="closeAddModal()" class="p-2 hover:bg-gray-100 dark:hover:bg-slate-700 rounded-full transition-colors cursor-pointer text-gray-500 dark:text-slate-400 outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <div class="flex-1 overflow-y-auto p-6 relative z-10 custom-scrollbar bg-gray-50/50 dark:bg-slate-900/50">
                <form id="addStudentForm" onsubmit="handleSubmit(event)" class="space-y-6" enctype="multipart/form-data">
                    <?= csrf_field() ?>
                    <input type="hidden" name="id" id="edit_id">

                    <div class="bg-white dark:bg-slate-800 p-5 rounded-2xl shadow-sm border border-emerald-100 dark:border-emerald-800/50">
                        <h4 class="text-md font-bold text-emerald-600 dark:text-emerald-400 border-b border-emerald-100 dark:border-emerald-800/50 pb-2 mb-4"><?= lang('Admin/Siswa.section_a') ?></h4>
                        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-4 text-sm">
                            <div class="md:col-span-2 lg:col-span-2">
                                <label class="block text-xs font-bold text-gray-500 mb-1"><?= lang('Admin/Siswa.nis_auto') ?></label>
                                <div class="relative group">
                                    <input type="text" id="nis" name="nis" readonly 
                                        class="w-full pl-3 pr-10 py-2 bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800/50 rounded-lg outline-none cursor-not-allowed font-mono text-emerald-700 dark:text-emerald-400 font-bold transition-all">
                                </div>
                            </div>
                            <div><label class="block text-xs font-bold text-gray-500 mb-1"><?= lang('Admin/Siswa.nisn') ?></label><input type="text" id="nisn" name="nisn" class="w-full px-3 py-2 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-lg outline-none text-gray-800 dark:text-white"></div>
                            <div><label class="block text-xs font-bold text-gray-500 mb-1"><?= lang('Admin/Siswa.student_nik') ?> <span class="text-red-500">*</span></label><input type="text" id="nik" name="nik" required class="w-full px-3 py-2 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-lg outline-none text-gray-800 dark:text-white"></div>

                            <div class="md:col-span-2"><label class="block text-xs font-bold text-gray-500 mb-1"><?= lang('Admin/Siswa.full_name') ?> <span class="text-red-500">*</span></label><input type="text" id="nama_lengkap" name="nama_lengkap" required class="w-full px-3 py-2 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-lg outline-none text-gray-800 dark:text-white"></div>
                            <div><label class="block text-xs font-bold text-gray-500 mb-1"><?= lang('Admin/Siswa.table_gender') ?></label><select id="jenis_kelamin" name="jenis_kelamin" class="w-full px-3 py-2 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-lg outline-none text-gray-800 dark:text-white">
                                    <option value="L"><?= lang('Admin/Siswa.male') ?></option>
                                    <option value="P"><?= lang('Admin/Siswa.female') ?></option>
                                </select></div>
                            <div><label class="block text-xs font-bold text-gray-500 mb-1"><?= lang('Admin/Siswa.religion') ?></label><select id="agama" name="agama" class="w-full px-3 py-2 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-lg outline-none text-gray-800 dark:text-white">
                                    <option value="Islam"><?= lang('Admin/Siswa.religion_islam') ?></option>
                                    <option value="Kristen"><?= lang('Admin/Siswa.religion_christian') ?></option>
                                    <option value="Katolik"><?= lang('Admin/Siswa.religion_catholic') ?></option>
                                    <option value="Hindu"><?= lang('Admin/Siswa.religion_hindu') ?></option>
                                    <option value="Buddha"><?= lang('Admin/Siswa.religion_buddha') ?></option>
                                </select></div>

                            <div class="md:col-span-2"><label class="block text-xs font-bold text-gray-500 mb-1"><?= lang('Admin/Siswa.birth_place') ?></label><input type="text" id="tempat_lahir" name="tempat_lahir" class="w-full px-3 py-2 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-lg outline-none text-gray-800 dark:text-white"></div>
                            <div class="md:col-span-2"><label class="block text-xs font-bold text-gray-500 mb-1"><?= lang('Admin/Siswa.birth_date') ?></label><input type="date" id="tanggal_lahir" name="tanggal_lahir" class="w-full px-3 py-2 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-lg outline-none text-gray-800 dark:text-white"></div>

                            <div class="md:col-span-2"><label class="block text-xs font-bold text-gray-500 mb-1"><?= lang('Admin/Siswa.kk_number') ?></label><input type="text" id="no_kk" name="no_kk" class="w-full px-3 py-2 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-lg outline-none text-gray-800 dark:text-white"></div>
                            <div class="md:col-span-2"><label class="block text-xs font-bold text-gray-500 mb-1"><?= lang('Admin/Siswa.birth_cert_number') ?></label><input type="text" id="no_registrasi_akta" name="no_registrasi_akta" class="w-full px-3 py-2 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-lg outline-none text-gray-800 dark:text-white"></div>
                        </div>
                    </div>

                    <div class="bg-white dark:bg-slate-800 p-5 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700">
                        <h4 class="text-md font-bold text-pink-600 dark:text-pink-400 border-b border-pink-100 dark:border-pink-800/50 pb-2 mb-4"><?= lang('Admin/Siswa.section_b') ?></h4>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                            <div><label class="block text-xs font-bold text-gray-500 mb-1"><?= lang('Admin/Siswa.family_status') ?></label><input type="text" id="status_dalam_keluarga" name="status_dalam_keluarga" class="w-full px-3 py-2 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-lg outline-none text-gray-800 dark:text-white"></div>
                            <div><label class="block text-xs font-bold text-gray-500 mb-1"><?= lang('Admin/Siswa.child_order') ?></label><input type="number" id="anak_ke" name="anak_ke" class="w-full px-3 py-2 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-lg outline-none text-gray-800 dark:text-white"></div>
                            <div><label class="block text-xs font-bold text-gray-500 mb-1"><?= lang('Admin/Siswa.sibling_count') ?></label><input type="number" id="jml_saudara_kandung" name="jml_saudara_kandung" class="w-full px-3 py-2 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-lg outline-none text-gray-800 dark:text-white"></div>
                            <div><label class="block text-xs font-bold text-gray-500 mb-1"><?= lang('Admin/Siswa.special_needs') ?></label><input type="text" id="kebutuhan_khusus" name="kebutuhan_khusus" class="w-full px-3 py-2 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-lg outline-none text-gray-800 dark:text-white"></div>

                            <div><label class="block text-xs font-bold text-gray-500 mb-1"><?= lang('Admin/Siswa.weight') ?></label><input type="number" id="berat_badan" name="berat_badan" class="w-full px-3 py-2 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-lg outline-none text-gray-800 dark:text-white"></div>
                            <div><label class="block text-xs font-bold text-gray-500 mb-1"><?= lang('Admin/Siswa.height') ?></label><input type="number" id="tinggi_badan" name="tinggi_badan" class="w-full px-3 py-2 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-lg outline-none text-gray-800 dark:text-white"></div>
                            <div><label class="block text-xs font-bold text-gray-500 mb-1"><?= lang('Admin/Siswa.head_circumference') ?></label><input type="number" id="lingkar_kepala" name="lingkar_kepala" class="w-full px-3 py-2 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-lg outline-none text-gray-800 dark:text-white"></div>
                            <div><label class="block text-xs font-bold text-gray-500 mb-1"><?= lang('Admin/Siswa.distance_km') ?></label><input type="text" id="jarak_ke_sekolah" name="jarak_ke_sekolah" class="w-full px-3 py-2 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-lg outline-none text-gray-800 dark:text-white"></div>
                        </div>
                    </div>

                    <div class="bg-white dark:bg-slate-800 p-5 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700">
                        <h4 class="text-md font-bold text-blue-600 dark:text-blue-400 border-b border-blue-100 dark:border-blue-800/50 pb-2 mb-4"><?= lang('Admin/Siswa.section_c') ?></h4>
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 text-sm">
                            <div class="md:col-span-4"><label class="block text-xs font-bold text-gray-500 mb-1"><?= lang('Admin/Siswa.street_address') ?></label><textarea id="alamat_siswa" name="alamat_siswa" rows="2" class="w-full px-3 py-2 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-lg outline-none resize-none text-gray-800 dark:text-white"></textarea></div>

                            <div class="md:col-span-2"><label class="block text-xs font-bold text-gray-500 mb-1"><?= lang('Admin/Siswa.district') ?></label><input type="text" id="kecamatan" name="kecamatan" class="w-full px-3 py-2 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-lg outline-none text-gray-800 dark:text-white"></div>
                            <div class="md:col-span-2"><label class="block text-xs font-bold text-gray-500 mb-1"><?= lang('Admin/Siswa.village') ?></label><input type="text" id="kelurahan" name="kelurahan" class="w-full px-3 py-2 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-lg outline-none text-gray-800 dark:text-white"></div>

                            <div class="md:col-span-2"><label class="block text-xs font-bold text-gray-500 mb-1"><?= lang('Admin/Siswa.hamlet') ?></label><input type="text" id="dusun" name="dusun" class="w-full px-3 py-2 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-lg outline-none text-gray-800 dark:text-white"></div>
                            <div><label class="block text-xs font-bold text-gray-500 mb-1"><?= lang('Admin/Siswa.rt') ?></label><input type="text" id="rt" name="rt" class="w-full px-3 py-2 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-lg outline-none text-center text-gray-800 dark:text-white"></div>
                            <div><label class="block text-xs font-bold text-gray-500 mb-1"><?= lang('Admin/Siswa.rw') ?></label><input type="text" id="rw" name="rw" class="w-full px-3 py-2 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-lg outline-none text-center text-gray-800 dark:text-white"></div>

                            <div><label class="block text-xs font-bold text-gray-500 mb-1"><?= lang('Admin/Siswa.postal_code') ?></label><input type="text" id="kode_pos" name="kode_pos" class="w-full px-3 py-2 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-lg outline-none text-gray-800 dark:text-white"></div>
                            <div><label class="block text-xs font-bold text-gray-500 mb-1"><?= lang('Admin/Siswa.residence_type') ?></label><input type="text" id="jenis_tinggal" name="jenis_tinggal" class="w-full px-3 py-2 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-lg outline-none text-gray-800 dark:text-white"></div>
                            <div class="md:col-span-2"><label class="block text-xs font-bold text-gray-500 mb-1"><?= lang('Admin/Siswa.transport_tool') ?></label><input type="text" id="alat_transportasi" name="alat_transportasi" class="w-full px-3 py-2 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-lg outline-none text-gray-800 dark:text-white"></div>

                            <div class="md:col-span-2"><label class="block text-xs font-bold text-gray-500 mb-1"><?= lang('Admin/Siswa.student_phone') ?></label><input type="text" id="no_hp" name="no_hp" class="w-full px-3 py-2 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-lg outline-none text-gray-800 dark:text-white"></div>
                            <div class="md:col-span-2"><label class="block text-xs font-bold text-gray-500 mb-1"><?= lang('Admin/Siswa.student_email') ?></label><input type="email" id="email_siswa" name="email_siswa" class="w-full px-3 py-2 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-lg outline-none text-gray-800 dark:text-white"></div>
                        </div>
                    </div>

                    <div class="bg-white dark:bg-slate-800 p-5 rounded-2xl shadow-sm border border-purple-100 dark:border-purple-800/50">
                        <h4 class="text-md font-bold text-purple-600 dark:text-purple-400 border-b border-purple-100 dark:border-purple-800/50 pb-2 mb-4"><?= lang('Admin/Siswa.section_d') ?></h4>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                            <div>
                                <label class="block text-xs font-bold text-gray-500 mb-1"><?= lang('Admin/Siswa.accepted_in_class') ?></label>
                                <select id="diterima_dikelas" name="diterima_dikelas" class="w-full px-3 py-2 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-lg outline-none text-gray-800 dark:text-white">
                                    <option value=""><?= lang('Admin/Siswa.select') ?></option>
                                    <option value="VII">VII</option>
                                    <option value="VIII">VIII</option>
                                    <option value="IX">IX</option>
                                </select>
                            </div>
                            <div><label class="block text-xs font-bold text-gray-500 mb-1"><?= lang('Admin/Siswa.accepted_date') ?></label><input type="date" id="tgl_diterima" name="tgl_diterima" class="w-full px-3 py-2 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-lg outline-none text-gray-800 dark:text-white"></div>
                            <div class="md:col-span-1"><label class="block text-xs font-bold text-gray-500 mb-1"><?= lang('Admin/Siswa.origin_school_sd') ?></label><input type="text" id="asal_sekolah" name="asal_sekolah" class="w-full px-3 py-2 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-lg outline-none text-gray-800 dark:text-white"></div>

                            <div><label class="block text-xs font-bold text-gray-500 mb-1"><?= lang('Admin/Siswa.skhun') ?></label><input type="text" id="skhun" name="skhun" class="w-full px-3 py-2 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-lg outline-none text-gray-800 dark:text-white"></div>
                            <div><label class="block text-xs font-bold text-gray-500 mb-1"><?= lang('Admin/Siswa.un_number') ?></label><input type="text" id="no_peserta_un" name="no_peserta_un" class="w-full px-3 py-2 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-lg outline-none text-gray-800 dark:text-white"></div>
                            <div><label class="block text-xs font-bold text-gray-500 mb-1"><?= lang('Admin/Siswa.ijazah_number') ?></label><input type="text" id="no_seri_ijazah" name="no_seri_ijazah" class="w-full px-3 py-2 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-lg outline-none text-gray-800 dark:text-white"></div>
                        </div>
                    </div>

                    <div class="bg-white dark:bg-slate-800 p-5 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700">
                        <h4 class="text-md font-bold text-indigo-600 dark:text-indigo-400 border-b border-indigo-100 dark:border-indigo-800/50 pb-2 mb-4"><?= lang('Admin/Siswa.section_f') ?></h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-4">
                                <p class="text-xs font-bold text-gray-400 uppercase tracking-widest"><?= lang('Admin/Siswa.father_data') ?></p>
                                <div class="grid grid-cols-1 gap-4">
                                    <div><label class="block text-xs font-bold text-gray-500 mb-1"><?= lang('Admin/Siswa.full_name') ?></label><input type="text" id="nama_ayah" name="nama_ayah" class="w-full px-3 py-2 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-lg outline-none text-gray-800 dark:text-white"></div>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div><label class="block text-xs font-bold text-gray-500 mb-1"><?= lang('Admin/Siswa.nik') ?></label><input type="text" id="nik_ayah" name="nik_ayah" class="w-full px-3 py-2 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-lg outline-none text-gray-800 dark:text-white"></div>
                                        <div><label class="block text-xs font-bold text-gray-500 mb-1"><?= lang('Admin/Siswa.birth_year') ?></label><input type="number" id="tahun_lahir_ayah" name="tahun_lahir_ayah" class="w-full px-3 py-2 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-lg outline-none text-gray-800 dark:text-white"></div>
                                    </div>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div><label class="block text-xs font-bold text-gray-500 mb-1"><?= lang('Admin/Siswa.education') ?></label><select id="pendidikan_ayah" name="pendidikan_ayah" class="w-full px-3 py-2 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-lg outline-none text-gray-800 dark:text-white">
                                                <option value=""><?= lang('Admin/Siswa.select') ?></option>
                                                <option value="SD">SD</option>
                                                <option value="SMP">SMP</option>
                                                <option value="SMA">SMA</option>
                                                <option value="S1">S1</option>
                                                <option value="S2">S2</option>
                                            </select></div>
                                        <div><label class="block text-xs font-bold text-gray-500 mb-1"><?= lang('Admin/Siswa.occupation') ?></label><input type="text" id="pekerjaan_ayah" name="pekerjaan_ayah" class="w-full px-3 py-2 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-lg outline-none text-gray-800 dark:text-white"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="space-y-4">
                                <p class="text-xs font-bold text-gray-400 uppercase tracking-widest"><?= lang('Admin/Siswa.mother_data') ?></p>
                                <div class="grid grid-cols-1 gap-4">
                                    <div><label class="block text-xs font-bold text-gray-500 mb-1"><?= lang('Admin/Siswa.full_name') ?></label><input type="text" id="nama_ibu" name="nama_ibu" class="w-full px-3 py-2 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-lg outline-none text-gray-800 dark:text-white"></div>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div><label class="block text-xs font-bold text-gray-500 mb-1"><?= lang('Admin/Siswa.nik') ?></label><input type="text" id="nik_ibu" name="nik_ibu" class="w-full px-3 py-2 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-lg outline-none text-gray-800 dark:text-white"></div>
                                        <div><label class="block text-xs font-bold text-gray-500 mb-1"><?= lang('Admin/Siswa.birth_year') ?></label><input type="number" id="tahun_lahir_ibu" name="tahun_lahir_ibu" class="w-full px-3 py-2 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-lg outline-none text-gray-800 dark:text-white"></div>
                                    </div>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div><label class="block text-xs font-bold text-gray-500 mb-1"><?= lang('Admin/Siswa.education') ?></label><select id="pendidikan_ibu" name="pendidikan_ibu" class="w-full px-3 py-2 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-lg outline-none text-gray-800 dark:text-white">
                                                <option value=""><?= lang('Admin/Siswa.select') ?></option>
                                                <option value="SD">SD</option>
                                                <option value="SMP">SMP</option>
                                                <option value="SMA">SMA</option>
                                                <option value="S1">S1</option>
                                                <option value="S2">S2</option>
                                            </select></div>
                                        <div><label class="block text-xs font-bold text-gray-500 mb-1"><?= lang('Admin/Siswa.occupation') ?></label><input type="text" id="pekerjaan_ibu" name="pekerjaan_ibu" class="w-full px-3 py-2 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-lg outline-none text-gray-800 dark:text-white"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-8 pt-6 border-t border-gray-100 dark:border-slate-700">
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4"><?= lang('Admin/Siswa.parent_contact_address') ?></p>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                                <div><label class="block text-xs font-bold text-gray-500 mb-1"><?= lang('Admin/Siswa.parent_phone') ?> <span class="text-red-500">*</span></label><input type="text" id="no_hp_ortu" name="no_hp_ortu" required class="w-full px-3 py-2 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-lg outline-none text-gray-800 dark:text-white"></div>
                                <div><label class="block text-xs font-bold text-gray-500 mb-1"><?= lang('Admin/Siswa.parent_email') ?></label><input type="email" id="email_ortu" name="email_ortu" class="w-full px-3 py-2 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-lg outline-none text-gray-800 dark:text-white"></div>
                                <div class="md:col-span-2"><label class="block text-xs font-bold text-gray-500 mb-1"><?= lang('Admin/Siswa.parent_address') ?></label><textarea id="alamat_orangtua" name="alamat_orangtua" rows="2" class="w-full px-3 py-2 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-lg outline-none resize-none text-gray-800 dark:text-white"></textarea></div>
                            </div>
                        </div>
                    </div>

                    <!-- EKSTRAKURIKULER -->
                    <div class="bg-white dark:bg-slate-800 p-5 rounded-2xl shadow-sm border border-amber-100 dark:border-emerald-800/50">
                        <h4 class="text-md font-bold text-amber-600 dark:text-amber-400 border-b border-amber-100 dark:border-amber-800/50 pb-2 mb-4">Pengaturan Ekstrakurikuler</h4>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                            <?php for($i=1; $i<=3; $i++): ?>
                            <div>
                                <label class="block text-xs font-bold text-gray-500 mb-1">Ekskul <?= $i ?></label>
                                <select name="ekskul_<?= $i ?>" id="ekskul_<?= $i ?>" class="w-full px-3 py-2 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-lg outline-none text-gray-800 dark:text-white">
                                    <option value="">-- Pilih Ekskul --</option>
                                    <?php foreach($ekskulList as $eks): ?>
                                        <option value="<?= $eks['id'] ?>"><?= esc($eks['nama_ekskul']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <?php endfor; ?>
                        </div>
                    </div>

                    <div class="bg-white dark:bg-slate-800 p-5 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700">
                        <h4 class="text-md font-bold text-gray-600 dark:text-slate-400 border-b border-gray-100 dark:border-slate-700 pb-2 mb-4"><?= lang('Admin/Siswa.section_g') ?></h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-xs font-bold text-gray-500 mb-1"><?= lang('Admin/Siswa.student_active_status') ?></label>
                                <select id="status_siswa" name="status_siswa" class="w-full px-3 py-2 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-lg outline-none text-gray-800 dark:text-white">
                                    <option value="Aktif"><?= lang('Admin/Siswa.active') ?></option>
                                    <option value="Lulus"><?= lang('Admin/Siswa.graduated') ?></option>
                                    <option value="Pindah"><?= lang('Admin/Siswa.moved') ?></option>
                                    <option value="Keluar"><?= lang('Admin/Siswa.dropped_out') ?></option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-500 mb-1"><?= lang('Admin/Siswa.student_photo') ?></label>
                                <input type="file" name="photo" id="photo" accept="image/*" class="w-full px-3 py-1.5 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-lg outline-none text-gray-800 dark:text-white file:mr-4 file:py-1 file:px-4 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100">
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-4 sticky bottom-0 bg-white dark:bg-slate-800 py-4 border-t border-gray-100 dark:border-slate-700 transition-colors z-30">
                        <button type="button" onclick="closeAddModal()" class="px-6 py-2.5 border border-gray-300 dark:border-slate-600 text-gray-600 dark:text-slate-300 font-bold rounded-xl hover:bg-gray-100 dark:hover:bg-slate-700 transition-colors cursor-pointer outline-none"><?= lang('Admin/Siswa.cancel') ?></button>
                        <button type="submit" id="btnSubmit" class="px-8 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl transition-all shadow-lg shadow-emerald-500/20 cursor-pointer outline-none"><?= lang('Admin/Siswa.btn_update') ?></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?> 

<?= $this->section('scripts') ?>
<script>
    const BASE_URL = '<?= rtrim(base_url(), '/') ?>';
    
    // Jembatan Data PHP ke Javascript
    window.sekolahConfig = {
        school_name: '<?= esc($nama_sekolah ?? 'SMPIT Ad Durrah') ?>',
        teacher_name: '<?= esc($guru['nama_lengkap'] ?? 'Guru/Wali Kelas') ?>',
        class_name: '<?= esc($rombel['nama_rombel'] ?? 'Belum Ada Kelas') ?>',
        primary_color: '<?= esc($color['warna_primary'] ?? '#10b981') ?>',
        secondary_color: '<?= esc($color['warna_secondary'] ?? '#ecfdf5') ?>'
    };

    // KAMUS BAHASA JS
    window.LANG = {
        gender_m: "<?= lang('WaliKelas/DaftarSiswa.gender_m') ?>",
        gender_f: "<?= lang('WaliKelas/DaftarSiswa.gender_f') ?>",
        prof_safe: "<?= lang('WaliKelas/DaftarSiswa.prof_safe') ?>",
        prof_warn: "<?= lang('WaliKelas/DaftarSiswa.prof_warn') ?>",
        note_saved: "<?= lang('WaliKelas/DaftarSiswa.note_saved') ?>",
        note_title_prefix: "<?= lang('WaliKelas/DaftarSiswa.note_title_prefix') ?>",
        rapor_no_grade: "<?= lang('WaliKelas/DaftarSiswa.rapor_no_grade') ?>",
        rapor_good_behavior: "<?= lang('WaliKelas/DaftarSiswa.rapor_good_behavior') ?>"
    };

    // IMPLEMENTASI RBAC: Kunci Fitur Edit Modul Daftar Siswa Wali (wali_kelas)
    const CAN_UPDATE = <?= has_permission('wali_kelas', 'update') ? 'true' : 'false' ?>;
</script>
<script src="<?= base_url('assets/js/WaliKelas/daftar-siswa.js') ?>?v=<?= time() ?>"></script>
<?= $this->endSection() ?>