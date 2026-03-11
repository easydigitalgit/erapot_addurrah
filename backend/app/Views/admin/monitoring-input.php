<?= $this->extend('layout/main') ?>

<?= $this->section('title') ?>
  <?= lang('Admin/MonitoringInput.page_title') ?> - Rapor Digital SMPIT Ad Durrah
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
  <link rel="stylesheet" href="<?= base_url('/assets/css/Admin/monitoring-input.css') ?>">
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="mb-6">
    <div class="flex items-center gap-2 text-sm text-gray-500 dark:text-slate-400 mb-3 transition-colors">
        <span><?= lang('Admin/MonitoringInput.breadcrumb') ?></span>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
        <span class="text-[<?= $color['warna_primary'] ?>] font-medium"><?= lang('Admin/MonitoringInput.page_title') ?></span>
    </div>

    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 mb-6">
       <div>
        <h1 class="text-2xl md:text-3xl font-bold text-gray-800 dark:text-white mb-2 transition-colors">📊 <?= lang('Admin/MonitoringInput.page_title') ?></h1>
        <p class="text-sm md:text-base text-gray-600 dark:text-slate-400 transition-colors"><?= lang('Admin/MonitoringInput.page_desc') ?></p>
       </div>
       <div class="flex flex-wrap items-center gap-2">
         <button onclick="sendReminder()" class="px-5 py-2.5 bg-amber-500 hover:bg-amber-600 text-white font-bold rounded-xl transition-transform transform hover:-translate-y-0.5 shadow-lg shadow-amber-500/30 flex items-center gap-2 outline-none">
           <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" /></svg>
           <span><?= lang('Admin/MonitoringInput.btn_reminder') ?></span> 
         </button> 
       </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        
        <div class="bg-gradient-to-br from-[<?= $color['warna_primary'] ?>] to-emerald-800 dark:to-emerald-950 rounded-3xl p-6 text-white shadow-sm hover:shadow-md transition-shadow relative overflow-hidden group">
            <div class="absolute right-0 top-0 h-full w-32 bg-white/10 dark:bg-white/5 transform skew-x-12 group-hover:translate-x-4 transition-transform duration-500"></div>
            <div class="relative z-10">
                <p class="text-emerald-100 dark:text-emerald-200/80 font-bold tracking-wider uppercase text-xs mb-2"><?= lang('Admin/MonitoringInput.stat_total_completeness') ?></p>
                <div class="flex items-end gap-2 mb-3">
                    <h2 class="text-5xl font-black drop-shadow-sm"><?= $stats['avg_progres_sekolah'] ?>%</h2>
                    <span class="text-emerald-200 dark:text-emerald-400 font-bold mb-1.5"><?= lang('Admin/MonitoringInput.stat_out_of') ?></span>
                </div>
                <div class="w-full bg-black/20 dark:bg-black/40 rounded-full h-2 mb-2">
                    <div class="bg-white h-2 rounded-full transition-all duration-1000 shadow-[0_0_8px_rgba(255,255,255,0.8)]" style="width: <?= $stats['avg_progres_sekolah'] ?>%"></div>
                </div>
                <p class="text-xs font-medium text-emerald-100 dark:text-emerald-300"><?= lang('Admin/MonitoringInput.stat_avg_desc') ?></p>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-800 rounded-3xl p-6 border border-gray-100 dark:border-slate-700 shadow-sm hover:shadow-md hover:border-blue-300 dark:hover:border-blue-500 transition-all flex items-center justify-between group">
            <div>
                <p class="text-gray-500 dark:text-slate-400 text-[11px] font-black uppercase tracking-widest mb-2 transition-colors"><?= lang('Admin/MonitoringInput.stat_total_graded') ?></p>
                <h2 class="text-4xl font-black text-gray-800 dark:text-white transition-colors"><?= number_format($stats['total_siswa_dinilai']) ?></h2>
                <p class="text-xs font-medium text-gray-500 dark:text-slate-400 mt-2 transition-colors"><?= lang('Admin/MonitoringInput.stat_graded_desc') ?></p>
            </div>
            <div class="w-16 h-16 rounded-2xl bg-blue-50 dark:bg-blue-900/30 flex items-center justify-center border border-blue-100 dark:border-blue-800/50 group-hover:scale-110 transition-transform">
                <svg class="w-8 h-8 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-800 rounded-3xl p-6 border border-gray-100 dark:border-slate-700 shadow-sm hover:shadow-md hover:border-amber-300 dark:hover:border-amber-500 transition-all flex items-center justify-between group">
            <div class="min-w-0 pr-4">
                <p class="text-gray-500 dark:text-slate-400 text-[11px] font-black uppercase tracking-widest mb-2 transition-colors"><?= lang('Admin/MonitoringInput.stat_top_subject') ?></p>
                <?php if($stats['mapel_tertinggi'] != '-'): ?>
                    <h2 class="text-xl font-bold text-gray-800 dark:text-white line-clamp-1 transition-colors" title="<?= $stats['mapel_tertinggi'] ?>">
                        <?= $stats['mapel_tertinggi'] ?>
                    </h2>
                    <span class="inline-block mt-2 px-2.5 py-1 bg-amber-50 dark:bg-amber-900/30 border border-amber-200 dark:border-amber-800/50 text-amber-700 dark:text-amber-400 text-xs font-black uppercase tracking-wider rounded-lg shadow-sm transition-colors">
                        <?= lang('Admin/MonitoringInput.stat_class') ?> <?= $stats['rombel_tertinggi'] ?>
                    </span>
                <?php else: ?>
                    <h2 class="text-xl font-bold text-gray-400 dark:text-slate-500 transition-colors"><?= lang('Admin/MonitoringInput.stat_no_data') ?></h2>
                <?php endif; ?>
            </div>
            <div class="w-16 h-16 rounded-2xl bg-amber-50 dark:bg-amber-900/30 flex items-center justify-center flex-shrink-0 border border-amber-100 dark:border-amber-800/50 group-hover:scale-110 transition-transform">
                <svg class="w-8 h-8 text-amber-500 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" /></svg>
            </div>
        </div>
    </div>

    <div class="table-wrapper mb-6 bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-gray-100 dark:border-slate-700 overflow-hidden transition-colors">
      <div class="overflow-x-auto custom-scrollbar">
      <table id="monitoringTable" class="w-full text-left border-collapse min-w-max">
       <thead class="bg-gray-50 dark:bg-slate-900/50 border-b border-gray-100 dark:border-slate-700 transition-colors">
        <tr class="text-[11px] text-gray-500 dark:text-slate-400 uppercase tracking-widest font-black">
         <th class="px-6 py-4"><?= lang('Admin/MonitoringInput.th_teacher') ?></th>
         <th class="px-6 py-4"><?= lang('Admin/MonitoringInput.th_subject') ?></th>
         <th class="px-6 py-4"><?= lang('Admin/MonitoringInput.th_class') ?></th>
         <th class="px-6 py-4 text-center"><?= lang('Admin/MonitoringInput.th_progress') ?></th>
         <th class="px-6 py-4 text-center"><?= lang('Admin/MonitoringInput.th_status') ?></th>
         <th class="px-6 py-4 text-center"><?= lang('Admin/MonitoringInput.th_action') ?></th>
        </tr>
       </thead>
       <tbody class="divide-y divide-gray-100 dark:divide-slate-700/50 transition-colors">
        
        <?php if(empty($monitoring)): ?>
            <tr>
                <td colspan="6" class="px-6 py-16 text-center text-gray-500 dark:text-slate-500 font-medium transition-colors">
                    <div class="flex flex-col items-center gap-3">
                       <svg class="w-12 h-12 text-gray-300 dark:text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                       <span><?= lang('Admin/MonitoringInput.empty_table') ?></span>
                    </div>
                </td>
            </tr>
        <?php else: ?>
            <?php foreach($monitoring as $row): ?>
            <tr class="hover:bg-gray-50 dark:hover:bg-slate-700/30 transition-colors group">
                <td class="px-6 py-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-gray-100 dark:bg-slate-700 flex items-center justify-center font-bold text-gray-600 dark:text-slate-300 border border-gray-200 dark:border-slate-600 shadow-sm transition-colors group-hover:scale-105 transform duration-300">
                            <?= substr($row['guru'], 0, 2) ?>
                        </div>
                        <div>
                            <p class="font-bold text-gray-900 dark:text-white transition-colors group-hover:text-[<?= $color['warna_primary'] ?>]"><?= $row['guru'] ?></p>
                            <p class="text-[11px] font-medium text-gray-500 dark:text-slate-400 font-mono mt-0.5 transition-colors">NUPTK: <?= $row['nuptk'] ?: '-' ?></p>
                        </div>
                    </div>
                </td>
                
                <td class="px-6 py-4 font-bold text-gray-700 dark:text-slate-300 transition-colors"><?= $row['mapel'] ?></td>
                
                <td class="px-6 py-4">
                    <span class="inline-block px-3 py-1 bg-gray-100 dark:bg-slate-700 text-gray-700 dark:text-slate-300 font-bold text-xs rounded-lg border border-gray-200 dark:border-slate-600 shadow-sm transition-colors">
                        <?= $row['kelas'] ?>
                    </span>
                </td>
                
                <td class="px-6 py-4">
                    <div class="w-full max-w-[160px] mx-auto">
                        <div class="flex justify-between text-[11px] font-bold mb-1.5 transition-colors">
                            <span class="text-gray-700 dark:text-slate-300"><?= $row['persen'] ?>%</span>
                            <span class="text-gray-500 dark:text-slate-400"><?= $row['sudah_dinilai'] ?>/<?= $row['total_siswa'] ?></span>
                        </div>
                        <div class="h-2 bg-gray-100 dark:bg-slate-700 rounded-full overflow-hidden transition-colors">
                            <div class="h-full rounded-full 
                                <?= $row['badge'] == 'success' ? 'bg-emerald-500' : 
                                   ($row['badge'] == 'warning' ? 'bg-amber-500' : 'bg-red-500') ?>" 
                                style="width: <?= $row['persen'] ?>%">
                            </div>
                        </div>
                    </div>
                </td>

                <td class="px-6 py-4 text-center">
                    <?php 
                        $badgeClass = '';
                        if($row['badge'] == 'success') $badgeClass = 'bg-emerald-50 dark:bg-emerald-900/30 border-emerald-200 dark:border-emerald-800/50 text-emerald-700 dark:text-emerald-400 shadow-[0_0_5px_rgba(16,185,129,0.2)]';
                        elseif($row['badge'] == 'warning') $badgeClass = 'bg-amber-50 dark:bg-amber-900/30 border-amber-200 dark:border-amber-800/50 text-amber-700 dark:text-amber-400 shadow-[0_0_5px_rgba(245,158,11,0.2)]';
                        else $badgeClass = 'bg-red-50 dark:bg-red-900/30 border-red-200 dark:border-red-800/50 text-red-700 dark:text-red-400 shadow-[0_0_5px_rgba(239,68,68,0.2)]';
                    ?>
                    <span class="inline-flex px-3 py-1 rounded-lg text-[10px] font-black uppercase tracking-wider border transition-colors <?= $badgeClass ?>">
                        <?= $row['status'] ?>
                    </span>
                </td>

                <td class="px-6 py-4 text-center">
                    <button onclick="showDetail('<?= esc(addslashes($row['guru'])) ?>', '<?= esc(addslashes($row['mapel'])) ?>', '<?= esc(addslashes($row['kelas'])) ?>', '<?= $row['persen'] ?>', '<?= $row['status'] ?>', '<?= $row['badge'] ?>', '<?= $row['guru_id'] ?>')" 
                            class="p-2.5 bg-gray-50 dark:bg-slate-700 hover:bg-[<?= $color['warna_primary'] ?>] hover:text-white rounded-xl transition-all shadow-sm text-gray-500 dark:text-slate-400 outline-none inline-flex items-center justify-center transform hover:scale-105 group/btn" title="<?= lang('Admin/MonitoringInput.btn_detail') ?>">
                        <svg class="w-5 h-5 transition-transform group-hover/btn:translate-x-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
                    </button>
                </td>
            </tr>
            <?php endforeach; ?>
        <?php endif; ?>
        
       </tbody>
      </table>
      </div>
    </div>
</div>

<div id="detailDrawerOverlay" class="fixed inset-0 bg-gray-950/60 backdrop-blur-sm z-[99999] hidden transition-opacity duration-300 opacity-0" onclick="closeDetailDrawer()"></div>
<div id="detailDrawer" class="fixed top-0 right-0 h-full w-full max-w-md bg-white dark:bg-slate-900 shadow-2xl z-[100000] transform translate-x-full transition-transform duration-300 flex flex-col border-l border-gray-200 dark:border-slate-800">
    <div class="p-6 border-b border-gray-100 dark:border-slate-800 flex justify-between items-center transition-colors">
        <h3 class="font-black text-xl text-gray-900 dark:text-white flex items-center gap-2">
            <svg class="w-6 h-6 text-[<?= $color['warna_primary'] ?>]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            <?= lang('Admin/MonitoringInput.drawer_title') ?>
        </h3>
        <button onclick="closeDetailDrawer()" class="p-2 text-gray-400 hover:text-gray-600 dark:hover:text-slate-300 hover:bg-gray-100 dark:hover:bg-slate-800 rounded-full transition-colors outline-none"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
    </div>
    <div id="drawerHeader" class="p-0 overflow-y-auto custom-scrollbar flex-1 text-gray-800 dark:text-slate-300">
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // 1. Deklarasi Data PHP ke JS DULUAN!
    window.BASE_URL = "<?= rtrim(base_url(), '/') ?>/";
    window.ALL_MONITORING_DATA = <?= json_encode($monitoring) ?>;
    
    if (!window.ALL_MONITORING_DATA) {
        window.ALL_MONITORING_DATA = [];
    }

    // 2. Deklarasi Bahasa
    window.LANG = {
        drawer_info_title: "<?= lang('Admin/MonitoringInput.drawer_info_title') ?>",
        drawer_subject: "<?= lang('Admin/MonitoringInput.drawer_subject') ?>",
        drawer_target_class: "<?= lang('Admin/MonitoringInput.drawer_target_class') ?>",
        drawer_input_prog: "<?= lang('Admin/MonitoringInput.drawer_input_prog') ?>",
        drawer_quick_action: "<?= lang('Admin/MonitoringInput.drawer_quick_action') ?>",
        btn_send_msg: "Kirim Notifikasi Aplikasi",
        js_server_error: "Terjadi kesalahan pada server",
        js_failed: "Gagal memproses",
        // ... (masukkan lang lainnya jika ada)
    };
</script>
<script defer src="<?= base_url('assets/js/Admin/monitoring-input.js') ?>?v=<?= time() ?>"></script>
<?= $this->endSection() ?>