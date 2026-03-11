<?= $this->extend('layout/main') ?>

<?= $this->section('title') ?>
  <?= lang('Tahfidz/Dashboard.page_title') ?>
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
  /* ... (Style tetap sama seperti sebelumnya) ... */
  :root {
    --warna-primary: <?= $color['warna_primary'] ?? '#10b981' ?>;
    --warna-primary-hover: <?= ($color['warna_primary'] ?? '#10b981') . 'E6' ?>;
    --warna-primary-light: <?= ($color['warna_primary'] ?? '#10b981') . '20' ?>;
    --warna-secondary: <?= $color['warna_secondary'] ?? '#f8fafc' ?>;
  }
  
  .stat-card {
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 1rem;
    padding: 1.25rem;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
    position: relative;
    overflow: hidden;
  }
  
  .stat-card::after {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 50%;
    height: 100%;
    background: linear-gradient(to right, transparent, rgba(255,255,255,0.8), transparent);
    transform: skewX(-20deg);
    transition: all 0.5s ease;
  }
  
  .stat-card:hover::after { left: 150%; }

  .stat-card:hover {
    border-color: var(--warna-primary);
    box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1);
    transform: translateY(-4px);
  }

  .stat-card.glow-blue:hover { box-shadow: 0 10px 25px -5px rgba(59, 130, 246, 0.2); border-color: #3b82f6; }
  .stat-card.glow-emerald:hover { box-shadow: 0 10px 25px -5px rgba(16, 185, 129, 0.2); border-color: #10b981; }
  .stat-card.glow-purple:hover { box-shadow: 0 10px 25px -5px rgba(168, 85, 247, 0.2); border-color: #a855f7; }
  .stat-card.glow-amber:hover { box-shadow: 0 10px 25px -5px rgba(245, 158, 11, 0.2); border-color: #f59e0b; }

  .icon-box {
    width: 2.5rem;
    height: 2.5rem;
    border-radius: 0.75rem;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: transform 0.3s ease;
  }
  
  @media (min-width: 768px) {
      .icon-box { width: 3rem; height: 3rem; }
  }
  
  .stat-card:hover .icon-box { transform: scale(1.1) rotate(5deg); }

  .bg-dinamis { background-color: var(--warna-primary) !important; }
  .text-dinamis { color: var(--warna-primary) !important; }
  .hover-bg-dinamis:hover { background-color: var(--warna-primary-hover) !important; }
  
  .progress-fill { width: 0%; transition: width 1.5s cubic-bezier(0.22, 1, 0.36, 1); }
  .custom-scroll::-webkit-scrollbar { width: 4px; height: 4px; }
  .custom-scroll::-webkit-scrollbar-track { background: transparent; }
  .custom-scroll::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="mb-8 w-full overflow-x-hidden mx-auto relative">
    
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4 gap-3">
        <div class="flex items-center gap-2 text-xs md:text-sm text-gray-500 dark:text-slate-400">
            <span><?= lang('Tahfidz/Dashboard.breadcrumb_tahfidz') ?></span>
            <svg class="w-3.5 h-3.5 md:w-4 md:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
            <span class="text-dinamis font-bold"><?= lang('Tahfidz/Dashboard.main_dashboard') ?></span>
        </div>
        
        <div class="flex flex-wrap items-center gap-2 w-full sm:w-auto">
            <button onclick="exportRekap(this)" class="flex-1 sm:flex-none bg-white dark:bg-slate-800 px-3 py-2 rounded-xl border border-gray-200 dark:border-slate-700 shadow-sm text-[11px] md:text-xs font-bold text-gray-700 dark:text-slate-300 hover:text-dinamis transition-all flex items-center justify-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                <span><?= lang('Tahfidz/Dashboard.export_report') ?></span>
            </button>
            <div class="bg-white dark:bg-slate-800 px-3 py-2 rounded-xl border border-gray-200 dark:border-slate-700 shadow-sm text-[11px] md:text-xs font-semibold text-gray-600 dark:text-slate-300 flex items-center justify-center gap-2 flex-1 sm:flex-none">
                <svg class="w-4 h-4 text-dinamis" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <span id="realtimeClock"><?= date('H:i') ?></span> <?= lang('Tahfidz/Dashboard.wib') ?>
            </div>
        </div>
    </div>

    <div class="bg-dinamis rounded-3xl p-6 md:p-10 text-white shadow-lg mb-8 relative overflow-hidden group">
        <div class="absolute -right-20 -top-20 w-64 h-64 bg-white rounded-full opacity-10 blur-3xl transform group-hover:scale-110 transition-transform duration-700"></div>
        <div class="absolute left-0 bottom-0 w-32 h-32 bg-black rounded-tr-full opacity-10 blur-2xl"></div>
        
        <div class="relative z-10">
            <p class="text-white/80 text-[10px] md:text-xs font-bold uppercase tracking-widest mb-1.5" id="greetingTime"><?= lang('Tahfidz/Dashboard.welcome_msg') ?></p>
            <h1 class="text-2xl md:text-4xl font-extrabold mb-3 tracking-tight leading-tight"><?= lang('Tahfidz/Dashboard.ahlan_wa_sahlan') ?><br><span class="text-white/90 text-xl md:text-3xl"><?= esc($user ?? 'Guru') ?>! ✨</span></h1>
            <p class="text-white/90 max-w-xl text-xs md:text-sm font-medium mb-6 md:mb-8 leading-relaxed opacity-90 hidden sm:block"><?= lang('Tahfidz/Dashboard.intro_text') ?></p>
            
            <div class="flex flex-col sm:flex-row gap-3">
                <button onclick="openSetoranModal()" class="bg-white dark:bg-slate-800 text-dinamis px-5 py-3 rounded-xl font-bold text-xs md:text-sm hover:shadow-lg transition-all flex items-center justify-center gap-2 shadow-sm text-center">
                    <svg class="w-4 h-4 md:w-5 md:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    <?= lang('Tahfidz/Dashboard.btn_quick_deposit') ?>
                </button>

                <div class="grid grid-cols-2 sm:flex gap-3">
                    <a href="<?= base_url('tahfidz/monitoring') ?>" class="bg-black/20 text-white backdrop-blur-md px-4 py-3 rounded-xl font-semibold text-xs text-center hover:bg-black/30 transition-all border border-white/20 flex flex-col sm:flex-row items-center justify-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                        <?= lang('Tahfidz/Dashboard.btn_standings') ?>
                    </a>
                    <a href="<?= base_url('tahfidz/nilai-rapor') ?>" class="bg-black/20 text-white backdrop-blur-md px-4 py-3 rounded-xl font-semibold text-xs text-center hover:bg-black/30 transition-all border border-white/20 flex flex-col sm:flex-row items-center justify-center gap-1.5">
                        <svg class="w-4 h-4 text-yellow-300" fill="currentColor" viewBox="0 0 20 20"><path d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z"></path></svg>
                        <?= lang('Tahfidz/Dashboard.btn_fill_report') ?>
                    </a>
                </div>
            </div>
        </div>
        
        <svg class="absolute right-0 top-0 w-48 h-48 md:w-72 md:h-72 text-white opacity-5 transform translate-x-10 translate-y-10 group-hover:rotate-12 transition-transform duration-1000" fill="currentColor" viewBox="0 0 24 24">
            <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/>
        </svg>
    </div>

    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 md:gap-6 mb-8">
        <div class="stat-card glow-blue p-3 md:p-6 bg-white dark:bg-slate-800 border-gray-200 dark:border-slate-700">
            <div class="flex items-start justify-between mb-2 md:mb-4">
                <div class="icon-box bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400">
                    <svg class="w-5 h-5 md:w-6 md:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                </div>
            </div>
            <h3 class="text-gray-500 dark:text-slate-400 text-[9px] md:text-xs font-bold uppercase tracking-wider mb-1 line-clamp-1"><?= lang('Tahfidz/Dashboard.stat_total_students') ?></h3>
            <p class="text-2xl md:text-3xl font-black text-gray-900 dark:text-white tracking-tight"><?= number_format($total_siswa ?? 0) ?></p>
        </div>

        <div class="stat-card glow-emerald relative p-3 md:p-6 bg-white dark:bg-slate-800 border-gray-200 dark:border-slate-700">
            <div class="flex items-start justify-between mb-2 md:mb-4">
                <div class="icon-box bg-emerald-50 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 relative z-10">
                    <svg class="w-5 h-5 md:w-6 md:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                </div>
                <span class="bg-emerald-100 dark:bg-emerald-900/50 text-emerald-700 dark:text-emerald-400 text-[8px] md:text-[10px] font-bold uppercase tracking-wider px-1.5 md:px-2.5 py-1 rounded-md flex items-center gap-1.5 relative z-10 border dark:border-emerald-800">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse hidden md:block"></span> <?= lang('Tahfidz/Dashboard.stat_today') ?>
                </span>
            </div>
            <h3 class="text-gray-500 dark:text-slate-400 text-[9px] md:text-xs font-bold uppercase tracking-wider mb-1 relative z-10 line-clamp-1"><?= lang('Tahfidz/Dashboard.stat_deposited') ?></h3>
            <p class="text-2xl md:text-3xl font-black text-gray-900 dark:text-white tracking-tight relative z-10"><?= number_format($setoran_hari_ini ?? 0) ?></p>
            <div class="absolute -right-4 -bottom-4 w-16 h-16 md:w-20 h-20 bg-emerald-50 dark:bg-emerald-900/20 rounded-full opacity-50"></div>
        </div>

        <div class="stat-card glow-purple p-3 md:p-6 bg-white dark:bg-slate-800 border-gray-200 dark:border-slate-700">
            <div class="flex items-start justify-between mb-2 md:mb-4">
                <div class="icon-box bg-purple-50 dark:bg-purple-900/30 text-purple-600 dark:text-purple-400">
                    <svg class="w-5 h-5 md:w-6 md:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" /></svg>
                </div>
            </div>
            <h3 class="text-gray-500 dark:text-slate-400 text-[9px] md:text-xs font-bold uppercase tracking-wider mb-1 line-clamp-1"><?= lang('Tahfidz/Dashboard.stat_target_reached') ?></h3>
            <p class="text-2xl md:text-3xl font-black text-gray-900 dark:text-white tracking-tight"><?= number_format($target_tercapai ?? 0) ?></p>
        </div>

        <div class="stat-card glow-amber p-3 md:p-6 bg-white dark:bg-slate-800 border-gray-200 dark:border-slate-700">
            <div class="flex items-start justify-between mb-2 md:mb-4">
                <div class="icon-box bg-amber-50 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400">
                    <svg class="w-5 h-5 md:w-6 md:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" /></svg>
                </div>
            </div>
            <h3 class="text-gray-500 dark:text-slate-400 text-[9px] md:text-xs font-bold uppercase tracking-wider mb-1 line-clamp-1"><?= lang('Tahfidz/Dashboard.stat_activity') ?></h3>
            <div class="flex items-end gap-1">
                <p class="text-2xl md:text-3xl font-black text-gray-900 dark:text-white tracking-tight"><?= $persentase ?? 0 ?><span class="text-sm md:text-lg text-gray-400 dark:text-slate-500">%</span></p>
            </div>
            <div class="w-full bg-gray-100 dark:bg-slate-700 rounded-full h-1 md:h-1.5 mt-2 overflow-hidden">
                <div class="bg-amber-500 h-full rounded-full progress-fill" data-width="<?= $persentase ?? 0 ?>%"></div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <div class="lg:col-span-2 flex flex-col gap-6">
            <div class="bg-white dark:bg-slate-800 rounded-2xl border border-rose-100 dark:border-rose-900/50 shadow-sm overflow-hidden flex flex-col relative group">
                <div class="absolute -right-8 -top-8 w-24 h-24 bg-rose-50 dark:bg-rose-900/20 rounded-full opacity-50 group-hover:scale-150 transition-transform duration-500"></div>
                
                <div class="p-4 md:p-5 border-b border-rose-50 dark:border-rose-900/50 flex justify-between items-center relative z-10 bg-rose-50/30 dark:bg-rose-900/10">
                    <h3 class="font-bold text-gray-900 dark:text-white text-sm md:text-base flex items-center gap-2">
                        <svg class="w-4 h-4 md:w-5 md:h-5 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        <?= lang('Tahfidz/Dashboard.needs_attention') ?>
                    </h3>
                </div>
                
                <div class="divide-y divide-gray-50 dark:divide-slate-700/50 overflow-x-auto relative z-10">
                    <?php if(empty($perhatian)): ?>
                        <div class="p-8 text-center bg-white dark:bg-slate-800">
                            <p class="text-emerald-600 dark:text-emerald-400 font-bold text-sm"><?= lang('Tahfidz/Dashboard.no_issues') ?></p>
                        </div>
                    <?php else: ?>
                        <?php foreach($perhatian as $row): ?>
                            <?php 
                                $words = explode(" ", $row['nama_lengkap']);
                                $inisial = strtoupper(substr($words[0], 0, 1) . (isset($words[1]) ? substr($words[1], 0, 1) : ''));
                                
                                $isRose = ($row['predikat'] == 'Belum Hafal');
                                $colorTheme = $isRose ? 'rose' : 'amber';
                                $descText = $isRose ? 'Evaluasi: Belum Hafal / Lahn Jaly' : 'Hafalan kurang lancar';
                                
                                $pesanWA = "Assalamu'alaikum Ayah/Bunda dari ananda " . $row['nama_lengkap'] . ". Menginformasikan ananda perlu bimbingan muroja'ah ekstra di rumah.";
                            ?>
                            <div class="p-4 hover:bg-<?= $colorTheme ?>-50/30 dark:hover:bg-slate-700/50 transition-colors flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                                <div class="flex items-center gap-3 w-full sm:w-3/4">
                                    <div class="w-10 h-10 rounded-full bg-<?= $colorTheme ?>-100 dark:bg-<?= $colorTheme ?>-900/40 text-<?= $colorTheme ?>-600 dark:text-<?= $colorTheme ?>-400 flex items-center justify-center font-bold text-xs flex-shrink-0">
                                        <?= $inisial ?>
                                    </div>
                                    <div class="overflow-hidden">
                                        <p class="text-xs md:text-sm font-bold text-gray-800 dark:text-white truncate" title="<?= esc($row['nama_lengkap']) ?>"><?= esc($row['nama_lengkap']) ?> <span class="text-[9px] text-gray-400 dark:text-slate-500 font-normal ml-1">(<?= esc($row['nama_rombel'] ?? '-') ?>)</span></p>
                                        <p class="text-[10px] md:text-xs text-gray-500 dark:text-slate-400 mt-0.5"><?= $descText ?></p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-2 self-start sm:self-auto ml-12 sm:ml-0">
                                    <a href="https://wa.me/?text=<?= urlencode($pesanWA) ?>" target="_blank" class="px-2 py-1.5 bg-green-50 dark:bg-green-900/30 text-green-600 dark:text-green-400 rounded-lg border border-green-100 dark:border-green-800/50 text-[10px] font-bold flex items-center gap-1 shadow-sm hover:bg-green-500 hover:text-white dark:hover:text-white transition-all">
                                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766.001-3.187-2.575-5.77-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.299.045-.677.063-1.092-.069-.252-.08-.575-.187-.988-.365-1.739-.751-2.874-2.502-2.961-2.617-.087-.116-.708-.94-.708-1.793s.448-1.273.607-1.446c.159-.173.346-.217.462-.217l.332.006c.106.005.249-.04.39.298.144.347.491 1.2.534 1.287.043.087.072.188.014.304-.058.116-.087.188-.173.289l-.26.304c-.087.086-.177.18-.076.327.101.144.447.706.945 1.15.643.574 1.199.754 1.343.843.144.088.231.074.318-.014l.372-.444c.115-.133.242-.11.378-.063l1.196.564c.144.072.241.116.275.18.033.064.033.375-.111.78z"></path></svg>
                                        <span class="hidden md:inline"><?= lang('Tahfidz/Dashboard.contact_parent') ?></span>
                                    </a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>

            <div class="bg-white dark:bg-slate-800 rounded-2xl border border-gray-200 dark:border-slate-700 shadow-sm overflow-hidden flex flex-col">
                <div class="p-4 md:p-5 border-b border-gray-100 dark:border-slate-700 flex justify-between items-center bg-gray-50/50 dark:bg-slate-800/50">
                    <h3 class="font-bold text-gray-900 dark:text-white text-sm md:text-base flex items-center gap-2">
                        <svg class="w-4 h-4 md:w-5 md:h-5 text-dinamis" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"></path></svg>
                        <?= lang('Tahfidz/Dashboard.achievement_dist') ?>
                    </h3>
                </div>
                <div class="p-5 md:p-6 flex-1 flex flex-col justify-center">
                    <div class="space-y-4">
                        <div>
                            <div class="flex justify-between items-end mb-1">
                                <span class="text-[11px] md:text-xs font-bold text-gray-700 dark:text-slate-300"><?= lang('Tahfidz/Dashboard.juz_30') ?> <span class="hidden md:inline text-[9px] text-gray-400 dark:text-slate-500 ml-1"><?= lang('Tahfidz/Dashboard.target_label') ?></span></span>
                                <span class="text-xs font-black text-dinamis"><?= $distribusi['juz30'] ?? 0 ?>%</span>
                            </div>
                            <div class="w-full bg-gray-100 dark:bg-slate-700 rounded-full h-1.5 overflow-hidden">
                                <div class="bg-dinamis h-full rounded-full progress-fill" data-width="<?= $distribusi['juz30'] ?? 0 ?>%"></div>
                            </div>
                        </div>
                        <div>
                            <div class="flex justify-between items-end mb-1">
                                <span class="text-[11px] md:text-xs font-bold text-gray-700 dark:text-slate-300"><?= lang('Tahfidz/Dashboard.juz_29') ?></span>
                                <span class="text-xs font-black text-blue-500 dark:text-blue-400"><?= $distribusi['juz29'] ?? 0 ?>%</span>
                            </div>
                            <div class="w-full bg-gray-100 dark:bg-slate-700 rounded-full h-1.5 overflow-hidden">
                                <div class="bg-blue-500 h-full rounded-full progress-fill" data-width="<?= $distribusi['juz29'] ?? 0 ?>%"></div>
                            </div>
                        </div>
                        <div>
                            <div class="flex justify-between items-end mb-1">
                                <span class="text-[11px] md:text-xs font-bold text-gray-700 dark:text-slate-300"><?= lang('Tahfidz/Dashboard.juz_28_plus') ?></span>
                                <span class="text-xs font-black text-emerald-500 dark:text-emerald-400"><?= $distribusi['juz28'] ?? 0 ?>%</span>
                            </div>
                            <div class="w-full bg-gray-100 dark:bg-slate-700 rounded-full h-1.5 overflow-hidden">
                                <div class="bg-emerald-500 h-full rounded-full progress-fill" data-width="<?= $distribusi['juz28'] ?? 0 ?>%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-gray-200 dark:border-slate-700 shadow-sm overflow-hidden flex flex-col h-full min-h-[400px]">
            <div class="p-4 md:p-5 border-b border-gray-100 dark:border-slate-700 flex justify-between items-center sticky top-0 bg-white dark:bg-slate-800 z-20">
                <h3 class="font-bold text-gray-900 dark:text-white text-sm md:text-base flex items-center gap-2">
                    <svg class="w-4 h-4 md:w-5 md:h-5 text-dinamis" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <?= lang('Tahfidz/Dashboard.live_feed') ?>
                </h3>
                <span class="flex h-2 w-2 relative">
                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                  <span class="relative inline-flex rounded-full h-2 w-2 bg-red-500"></span>
                </span>
            </div>
            
            <div class="p-4 md:p-5 flex-1 relative overflow-y-auto custom-scroll">
                <div class="absolute left-[25px] md:left-[29px] top-6 bottom-4 w-0.5 bg-gray-100 dark:bg-slate-700"></div>

                <div class="space-y-4">
                    <?php if(empty($setoran_terakhir)): ?>
                        <div class="text-center text-xs text-gray-500 dark:text-slate-400 py-8 relative z-10 bg-white dark:bg-slate-800">
                            <?= lang('Tahfidz/Dashboard.no_activity') ?>
                        </div>
                    <?php else: ?>
                        <?php foreach($setoran_terakhir as $row): ?>
                            <div class="flex items-start gap-3 md:gap-4 relative z-10 group cursor-default">
                                <div class="w-2 h-2 md:w-2.5 md:h-2.5 mt-1.5 rounded-full flex-shrink-0 ring-4 ring-white dark:ring-slate-800 transition-transform group-hover:scale-125 <?= ($row['jenis_setoran'] == 'Ziyadah') ? 'bg-emerald-500' : 'bg-blue-500' ?>"></div>
                                
                                <div class="bg-white dark:bg-slate-800 group-hover:bg-gray-50 dark:group-hover:bg-slate-700/50 p-2 -my-2 rounded-lg transition-colors w-full border border-transparent group-hover:border-gray-100 dark:group-hover:border-slate-700 overflow-hidden">
                                    <div class="flex justify-between items-start">
                                        <p class="text-xs md:text-sm font-bold text-gray-900 dark:text-white tracking-tight leading-tight w-3/4 truncate"><?= esc($row['nama_lengkap']) ?></p>
                                        <p class="text-[8px] md:text-[9px] font-bold text-gray-400 dark:text-slate-500 mt-0.5"><?= date('H:i', strtotime($row['created_at'])) ?></p>
                                    </div>
                                    <p class="text-[10px] md:text-xs text-gray-600 dark:text-slate-400 mt-1 truncate">
                                        <?= lang('Tahfidz/Dashboard.surah') ?> <?= esc($row['surah']) ?> <span class="bg-gray-100 dark:bg-slate-700 px-1 rounded ml-0.5"><?= esc($row['ayat']) ?></span> 
                                        <span class="<?= ($row['jenis_setoran'] == 'Ziyadah') ? 'text-emerald-600 dark:text-emerald-400' : 'text-blue-600 dark:text-blue-400' ?> font-bold text-[8px] uppercase tracking-wider ml-1">(<?= $row['jenis_setoran'] ?>)</span>
                                    </p>
                                    
                                    <?php 
                                        $bStyle = 'bg-gray-100 text-gray-600 dark:bg-slate-700 dark:text-slate-300';
                                        if($row['predikat'] == 'Sangat Lancar') $bStyle = 'bg-emerald-50 text-emerald-700 border border-emerald-100 dark:bg-emerald-900/30 dark:text-emerald-400 dark:border-emerald-800/50';
                                        elseif($row['predikat'] == 'Lancar') $bStyle = 'bg-blue-50 text-blue-700 border border-blue-100 dark:bg-blue-900/30 dark:text-blue-400 dark:border-blue-800/50';
                                        elseif($row['predikat'] == 'Kurang Lancar') $bStyle = 'bg-amber-50 text-amber-700 border border-amber-100 dark:bg-amber-900/30 dark:text-amber-400 dark:border-amber-800/50';
                                        elseif($row['predikat'] == 'Belum Hafal') $bStyle = 'bg-red-50 text-red-700 border border-red-100 dark:bg-red-900/30 dark:text-red-400 dark:border-red-800/50';
                                    ?>
                                    <div class="mt-1.5">
                                        <span class="text-[8px] md:text-[9px] font-bold uppercase tracking-wider inline-block px-1.5 py-0.5 rounded <?= $bStyle ?>"><?= esc($row['predikat']) ?></span>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>

    </div>

    <div id="customToast" class="fixed bottom-5 right-5 sm:bottom-10 sm:right-10 z-50 transform translate-y-20 opacity-0 transition-all duration-500 ease-out pointer-events-none">
        <div class="bg-slate-800 text-white px-5 py-4 rounded-2xl shadow-2xl flex items-center gap-4 border border-slate-700 min-w-[280px]">
            <div class="w-10 h-10 rounded-full bg-emerald-500/20 flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            </div>
            <div>
                <h4 class="font-bold text-sm"><?= lang('Tahfidz/Dashboard.toast_download_ok') ?></h4>
                <p class="text-xs text-slate-400 mt-0.5"><?= lang('Tahfidz/Dashboard.toast_download_desc') ?></p>
            </div>
            <button onclick="closeToast()" class="ml-auto text-slate-400 hover:text-white transition-colors pointer-events-auto">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
    </div>

    <div id="modalSetoran" class="fixed inset-0 z-[100] hidden items-center justify-center">
        <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity opacity-0" id="backdropSetoran" onclick="closeSetoranModal()"></div>
        
        <div class="bg-white dark:bg-slate-800 rounded-[2rem] shadow-2xl w-full max-w-lg mx-4 relative z-10 transform scale-95 opacity-0 transition-all duration-300 flex flex-col overflow-hidden border border-gray-100 dark:border-slate-700" id="cardSetoran">
            <div class="px-6 py-5 border-b border-slate-100 dark:border-slate-700 flex justify-between items-center bg-slate-50 dark:bg-slate-800/80">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-emerald-100 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-slate-800 dark:text-white text-lg leading-tight"><?= lang('Tahfidz/Dashboard.modal_deposit_title') ?></h3>
                        <p class="text-[11px] text-slate-500 dark:text-slate-400 font-medium"><?= lang('Tahfidz/Dashboard.modal_deposit_desc') ?></p>
                    </div>
                </div>
                <button onclick="closeSetoranModal()" class="w-8 h-8 flex items-center justify-center rounded-full hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            
            <div class="p-6 space-y-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5 uppercase tracking-wider"><?= lang('Tahfidz/Dashboard.label_student_name') ?></label>
                    <select class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-600 bg-slate-50 dark:bg-slate-700 focus:bg-white dark:focus:bg-slate-800 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none transition-all text-sm font-medium text-slate-700 dark:text-white">
                        <option value=""><?= lang('Tahfidz/Dashboard.select_student') ?></option>
                        <option value="1">Ahmad Zaidan (Kelas VII)</option>
                        <option value="2">Farisha Aqila (Kelas VII)</option>
                    </select>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5 uppercase tracking-wider"><?= lang('Tahfidz/Dashboard.label_surah') ?></label>
                        <input type="text" placeholder="<?= lang('Tahfidz/Dashboard.ph_surah') ?>" class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-600 bg-slate-50 dark:bg-slate-700 focus:bg-white dark:focus:bg-slate-800 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none transition-all text-sm font-medium text-slate-700 dark:text-white placeholder-slate-400">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5 uppercase tracking-wider"><?= lang('Tahfidz/Dashboard.label_ayat') ?></label>
                        <input type="text" placeholder="<?= lang('Tahfidz/Dashboard.ph_ayat') ?>" class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-600 bg-slate-50 dark:bg-slate-700 focus:bg-white dark:focus:bg-slate-800 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none transition-all text-sm font-medium text-slate-700 dark:text-white placeholder-slate-400">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5 uppercase tracking-wider"><?= lang('Tahfidz/Dashboard.label_deposit_type') ?></label>
                        <select class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-600 bg-slate-50 dark:bg-slate-700 focus:bg-white dark:focus:bg-slate-800 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none transition-all text-sm font-medium text-slate-700 dark:text-white">
                            <option value="Ziyadah"><?= lang('Tahfidz/Dashboard.type_ziyadah') ?></option>
                            <option value="Murojaah"><?= lang('Tahfidz/Dashboard.type_murojaah') ?></option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5 uppercase tracking-wider"><?= lang('Tahfidz/Dashboard.label_fluency') ?></label>
                        <select class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-600 bg-slate-50 dark:bg-slate-700 focus:bg-white dark:focus:bg-slate-800 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none transition-all text-sm font-medium text-slate-700 dark:text-white">
                            <option value="Sangat Lancar"><?= lang('Tahfidz/Dashboard.fluency_very_good') ?></option>
                            <option value="Lancar"><?= lang('Tahfidz/Dashboard.fluency_good') ?></option>
                            <option value="Kurang Lancar"><?= lang('Tahfidz/Dashboard.fluency_poor') ?></option>
                            <option value="Belum Hafal"><?= lang('Tahfidz/Dashboard.fluency_memorized') ?></option>
                        </select>
                    </div>
                </div>
            </div>
            
            <div class="px-6 py-4 bg-slate-50 dark:bg-slate-800/80 border-t border-slate-100 dark:border-slate-700 flex justify-between items-center">
                <a href="<?= base_url('tahfidz/setoran') ?>" class="text-xs font-bold text-emerald-600 dark:text-emerald-400 hover:text-emerald-700 dark:hover:text-emerald-300 transition-colors"><?= lang('Tahfidz/Dashboard.link_full_form') ?></a>
                <div class="flex gap-2">
                    <button onclick="closeSetoranModal()" class="px-5 py-2.5 rounded-xl text-sm font-bold text-slate-600 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700 transition-colors"><?= lang('Tahfidz/Dashboard.btn_cancel') ?></button>
                    <button id="btnSimpanSetoran" onclick="simpanSetoranCepat()" class="px-6 py-2.5 rounded-xl text-sm font-bold text-white bg-emerald-500 hover:bg-emerald-600 shadow-lg shadow-emerald-500/30 transition-all flex items-center gap-2 transform hover:-translate-y-0.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        <?= lang('Tahfidz/Dashboard.btn_save_data') ?>
                    </button>
                </div>
            </div>
        </div>
    </div>

</div>

<div id="modalSuccess" class="fixed inset-0 z-[120] hidden items-center justify-center">
    <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-md transition-opacity opacity-0 duration-500" id="backdropSuccess"></div>
    
    <div class="bg-white dark:bg-slate-800 rounded-[2rem] shadow-2xl w-full max-w-sm mx-4 relative z-10 transform scale-50 opacity-0 transition-all duration-500 cubic-bezier(0.34, 1.56, 0.64, 1) p-8 text-center flex flex-col items-center border border-gray-100 dark:border-slate-700" id="cardSuccess">
        
        <div class="w-24 h-24 bg-emerald-50 dark:bg-emerald-900/20 rounded-full flex items-center justify-center mb-6 relative">
            <div class="absolute inset-0 rounded-full border-4 border-emerald-500 border-t-transparent animate-spin opacity-20"></div>
            <svg class="w-12 h-12 text-emerald-500 dark:text-emerald-400 transform transition-transform duration-500 delay-300 scale-0" id="checkIcon" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path>
            </svg>
        </div>

        <h3 class="text-2xl font-black text-slate-800 dark:text-white mb-2 tracking-tight"><?= lang('Tahfidz/Dashboard.alhamdulillah') ?></h3>
        <p class="text-sm text-slate-500 dark:text-slate-400 font-medium mb-8"><?= lang('Tahfidz/Dashboard.success_save_desc') ?></p>
        
        <button onclick="closeSuccessModal()" class="w-full py-3.5 rounded-xl font-bold text-white bg-emerald-500 hover:bg-emerald-600 shadow-lg shadow-emerald-500/30 transition-all transform hover:-translate-y-1">
            <?= lang('Tahfidz/Dashboard.btn_close_continue') ?>
        </button>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    const LANG = {
        greeting_morning: "<?= lang('Tahfidz/Dashboard.greeting_morning') ?>",
        greeting_afternoon: "<?= lang('Tahfidz/Dashboard.greeting_afternoon') ?>",
        greeting_evening: "<?= lang('Tahfidz/Dashboard.greeting_evening') ?>",
        greeting_night: "<?= lang('Tahfidz/Dashboard.greeting_night') ?>",
        js_preparing: "<?= lang('Tahfidz/Dashboard.js_preparing') ?>",
        js_saving: "<?= lang('Tahfidz/Dashboard.js_saving') ?>"
    };
</script>
<script src="<?= base_url('assets/js/Tahfidz/dashboard.js') ?>?v=<?= time() ?>"></script>
<?= $this->endSection() ?>