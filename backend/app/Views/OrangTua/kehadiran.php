<?= $this->extend('layout/main') ?>

<?= $this->section('title') ?>
  Presensi Kehadiran Ananda - Rapor Digital
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
  :root {
    --warna-primary: <?= $color['warna_primary'] ?? '#3b82f6' ?>;
    --warna-secondary: <?= $color['warna_secondary'] ?? '#eff6ff' ?>;
    --glow-primary: color-mix(in srgb, var(--warna-primary) 15%, transparent);
    --border-primary: color-mix(in srgb, var(--warna-primary) 30%, transparent);
    --warna-scroll: <?= $color['warna_primary'] ?>;
  }

  .text-dinamis { color: var(--warna-primary) !important; }
  .bg-dinamis { background-color: var(--warna-primary) !important; }
  .bg-dinamis-light { background-color: var(--warna-secondary) !important; }
  .border-dinamis { border-color: var(--warna-primary) !important; }
  .ring-dinamis { box-shadow: 0 0 0 4px var(--glow-primary), 0 0 0 1px var(--warna-primary) !important; }
  
  .card-premium {
      background: rgba(255, 255, 255, 0.95);
      backdrop-filter: blur(10px);
      border: 1px solid #f1f5f9;
      border-radius: 1.5rem;
      box-shadow: 0 4px 20px -5px rgba(0, 0, 0, 0.03);
      transition: all 0.3s ease;
  }

  .chart-circle-bg { fill: none; stroke: #f1f5f9; stroke-width: 4; }
  .chart-circle { fill: none; stroke-width: 3.5; stroke-linecap: round; animation: fillProgress 2s ease-out forwards; }
  @keyframes fillProgress { 0% { stroke-dasharray: 0 100; } }

  @keyframes float { 0% { transform: translate(0px, 0px) scale(1); } 50% { transform: translate(15px, -15px) scale(1.05); } 100% { transform: translate(0px, 0px) scale(1); } }
  .bg-blob { animation: float 8s ease-in-out infinite alternate; }
  @keyframes slideUpFade { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
  .stagger-item { opacity: 0; animation: slideUpFade 0.6s ease forwards; }

  /* Dark Mode Adjustments */
  html.dark .text-dinamis { color: color-mix(in srgb, var(--warna-primary) 80%, white) !important; }
  html.dark .bg-dinamis-light { background-color: rgba(255, 255, 255, 0.05) !important; }
  html.dark .card-premium { background: #1e293b !important; border-color: #334155 !important; }
  html.dark .text-slate-800 { color: #f1f5f9 !important; }
  html.dark .text-slate-700 { color: #e2e8f0 !important; }
  html.dark .text-slate-600 { color: #cbd5e1 !important; }
  html.dark .text-slate-500 { color: #94a3b8 !important; }
  html.dark .text-slate-400 { color: #64748b !important; }
  html.dark .text-slate-300 { color: #475569 !important; }
  
  html.dark .bg-slate-100 { background-color: #0f172a !important; border-color: #334155 !important; }
  html.dark .bg-slate-50 { background-color: #1e293b !important; }
  html.dark .border-slate-100, html.dark .border-slate-200 { border-color: #334155 !important; }
  html.dark .chart-circle-bg { stroke: #334155; }

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
<div class="fixed top-0 right-0 w-[400px] h-[400px] rounded-full mix-blend-multiply dark:mix-blend-lighten filter blur-[80px] opacity-10 bg-blob pointer-events-none" style="background-color: var(--warna-primary); z-index: -1;"></div>

<div class="mb-12 w-full mx-auto relative z-10">
    
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-6 stagger-item" style="animation-delay: 50ms;">
        <div>
            <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-white dark:bg-slate-800 text-dinamis text-[10px] font-bold uppercase tracking-widest mb-3 shadow-sm border border-slate-100 dark:border-slate-700">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                Rekapitulasi Kehadiran
            </div>
            <h1 class="text-3xl md:text-4xl font-bold text-slate-800 tracking-tight leading-tight">
                Presensi <span class="font-semibold text-slate-800">Sekolah</span>
            </h1>
        </div>
        
        <?php if($anak): ?>
        <div class="card-premium px-5 py-3 flex items-center gap-4 w-full md:w-auto">
            <div class="w-12 h-12 rounded-full bg-dinamis-light text-dinamis flex items-center justify-center font-bold text-lg shadow-inner flex-shrink-0 overflow-hidden ring-2 ring-white dark:ring-slate-700">
                <?php 
                    // Logika Hybrid Avatar Langsung di View
                    $inisial = !empty($anak['nama_lengkap']) ? strtoupper(substr($anak['nama_lengkap'], 0, 2)) : 'U';
                    $fotoFinal = $anak['foto_fix'] ?? '';
                ?>
                
                <?php if($fotoFinal !== ''): ?>
                    <?php $cacheBuster = '?v=' . time(); ?>
                    <img src="<?= base_url('assets/uploads/avatars/' . $fotoFinal) . $cacheBuster ?>" 
                         alt="Foto Anak" 
                         class="w-full h-full object-cover"
                         onerror="this.onerror=function(){ this.outerHTML='<span class=\'text-dinamis\'><?= $inisial ?></span>'; }; this.src='<?= base_url('uploads/siswa/' . $fotoFinal) . $cacheBuster ?>';">
                <?php else: ?>
                    <span class="text-dinamis"><?= $inisial ?></span>
                <?php endif; ?>
            </div>
            <div>
                <p class="font-bold text-slate-800 text-sm leading-tight"><?= esc($anak['nama_lengkap']) ?></p>
                <p class="text-[10px] text-slate-500 font-medium mt-0.5 tracking-wider uppercase">Kelas <?= esc($anak['kelas']) ?> • Semester <?= esc($anak['semester']) ?></p>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <?php if(!$anak): ?>
        <div class="card-premium p-10 text-center">
            <h3 class="text-xl font-bold text-slate-800 mb-2">Data Anak Belum Terhubung</h3>
            <p class="text-slate-500">Silakan hubungi administrator sekolah untuk menghubungkan akun Anda.</p>
        </div>
    <?php else: ?>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-10 stagger-item" style="animation-delay: 100ms;">
            
            <div class="lg:col-span-2 card-premium p-8 flex flex-col md:flex-row items-center justify-between gap-8 bg-gradient-to-r from-white dark:from-slate-800 to-dinamis-light dark:to-slate-900/50 border-dinamis/20">
                <div class="flex-grow text-center md:text-left">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Tingkat Kehadiran Semester Ini</p>
                    <h2 class="text-3xl font-black text-slate-800 dark:text-white leading-tight mb-2">Hadir <span class="text-dinamis"><?= $statistik['persentase'] ?>%</span></h2>
                    
                    <p class="text-sm text-slate-500 leading-relaxed mb-6">
                        Ananda telah menghadiri <strong class="text-slate-700 dark:text-slate-300"><?= $statistik['total_hadir'] ?> hari</strong> dari total <?= $statistik['total_hari_sekolah'] ?> hari sekolah efektif yang telah berjalan.
                    </p>
                    
                    <div class="flex flex-wrap gap-4 justify-center md:justify-start">
                        <div class="px-3 py-1.5 rounded-lg bg-emerald-50 dark:bg-emerald-900/30 border border-emerald-200 dark:border-emerald-800/50 flex items-center gap-2">
                            <span class="text-xs font-bold text-emerald-600 dark:text-emerald-400">Hadir: <?= $statistik['total_hadir'] ?></span>
                        </div>
                        <div class="px-3 py-1.5 rounded-lg bg-amber-50 dark:bg-amber-900/30 border border-amber-200 dark:border-amber-800/50 flex items-center gap-2">
                            <span class="text-xs font-bold text-amber-600 dark:text-amber-400">Sakit: <?= $absen['sakit'] ?></span>
                        </div>
                        <div class="px-3 py-1.5 rounded-lg bg-blue-50 dark:bg-blue-900/30 border border-blue-200 dark:border-blue-800/50 flex items-center gap-2">
                            <span class="text-xs font-bold text-blue-600 dark:text-blue-400">Izin: <?= $absen['izin'] ?></span>
                        </div>
                        <div class="px-3 py-1.5 rounded-lg bg-rose-50 dark:bg-rose-900/30 border border-rose-200 dark:border-rose-800/50 flex items-center gap-2">
                            <span class="text-xs font-bold text-rose-600 dark:text-rose-400">Alpha: <?= $absen['alpha'] ?></span>
                        </div>
                    </div>
                </div>
                
                <div class="w-48 h-48 flex-shrink-0 relative">
                    <svg viewBox="0 0 36 36" class="w-full h-full transform -rotate-90 drop-shadow-md">
                        <path class="chart-circle-bg" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                        <path class="chart-circle text-dinamis" stroke="currentColor" stroke-dasharray="<?= $statistik['persentase'] ?>, 100" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                    </svg>
                    <div class="absolute inset-0 flex flex-col items-center justify-center">
                        <span class="text-3xl font-black text-slate-800 dark:text-white"><?= $statistik['persentase'] ?></span>
                        <span class="text-xs font-bold text-slate-400">%</span>
                    </div>
                </div>
            </div>

            <div class="card-premium p-8 flex flex-col justify-center">
                <div class="w-12 h-12 rounded-full bg-slate-50 dark:bg-slate-900 text-slate-400 flex items-center justify-center mb-4 border border-slate-100 dark:border-slate-700">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <h3 class="font-extrabold text-slate-800 text-sm uppercase tracking-widest mb-2">Informasi Presensi</h3>
                
                <p class="text-xs text-slate-500 leading-relaxed mb-4">
                    Data diambil dari absensi harian yang diisi oleh Wali Kelas. Kotak kalender akan berwarna hijau jika ananda hadir.
                </p>
                
                <div class="p-3 bg-dinamis-light rounded-xl border border-dinamis/20">
                    <p class="text-[10px] text-dinamis font-bold uppercase tracking-wider mb-1">Status Sinkronisasi</p>
                    <div class="flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-dinamis animate-pulse"></span>
                        <span class="text-xs font-semibold text-dinamis">Terhubung Langsung (Real-time)</span>
                    </div>
                </div>
            </div>
            
        </div>

        <?php if(!empty($kalender)): ?>
        <div class="card-premium p-6 md:p-8 stagger-item" style="animation-delay: 200ms;">
            
            <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4 border-b border-slate-100 dark:border-slate-700 pb-6">
                <h3 class="font-extrabold text-slate-800 text-lg uppercase tracking-widest flex items-center gap-3">
                    <svg class="w-6 h-6 text-dinamis" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    Kalender Presensi
                </h3>
                
                <div class="flex items-center gap-3">
                    <a href="<?= esc($nav_prev) ?>" class="p-2.5 rounded-xl bg-slate-50 dark:bg-slate-800 text-slate-500 dark:text-slate-400 hover:bg-dinamis hover:text-white dark:hover:text-white transition-colors border border-slate-200 dark:border-slate-700">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                    </a>
                    <div class="px-5 py-2.5 bg-slate-800 dark:bg-slate-950 text-white rounded-xl font-bold text-sm tracking-widest uppercase shadow-md shadow-slate-800/20 w-48 text-center border border-slate-700">
                        <?= esc($nama_bulan) ?>
                    </div>
                    <a href="<?= esc($nav_next) ?>" class="p-2.5 rounded-xl bg-slate-50 dark:bg-slate-800 text-slate-500 dark:text-slate-400 hover:bg-dinamis hover:text-white dark:hover:text-white transition-colors border border-slate-200 dark:border-slate-700">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </a>
                </div>
            </div>

            <div class="grid grid-cols-7 gap-2 md:gap-4 mb-8">
                <?php 
                $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
                foreach($days as $hari): ?>
                    <div class="text-center text-[10px] md:text-xs font-extrabold text-slate-400 uppercase tracking-widest pb-3">
                        <?= $hari ?>
                    </div>
                <?php endforeach; ?>

                <?php for($e=1; $e<$hari_pertama; $e++): ?>
                    <div class="aspect-square rounded-2xl bg-transparent"></div>
                <?php endfor; ?>

                <?php foreach($kalender as $hari): ?>
                    <div class="aspect-square rounded-2xl border flex flex-col items-center justify-center <?= $hari['warna'] ?> relative transition-transform duration-300 hover:scale-[1.05] group <?= $hari['is_today'] ? 'ring-dinamis border-dinamis' : '' ?>">
                        <span class="text-xl md:text-2xl font-black mb-1"><?= $hari['tanggal'] ?></span>
                        
                        <?php if($hari['is_today']): ?>
                            <span class="absolute -top-2 left-1/2 -translate-x-1/2 px-2 py-0.5 bg-dinamis text-[6px] md:text-[8px] text-white font-black uppercase tracking-tighter rounded-full shadow-sm">Hari Ini</span>
                        <?php endif; ?>
                        
                        <?php if($hari['status'] == 'Hadir'): ?>
                            <div class="w-2.5 h-2.5 rounded-full bg-emerald-500 shadow-sm shadow-emerald-500/50 mt-1"></div>
                        <?php elseif($hari['status'] == 'Sakit'): ?>
                            <span class="text-[8px] md:text-[10px] font-black uppercase tracking-wider px-2 py-0.5 rounded-lg bg-amber-100 dark:bg-amber-900/50 text-amber-700 dark:text-amber-400 shadow-sm mt-0.5">Sakit</span>
                        <?php elseif($hari['status'] == 'Izin'): ?>
                            <span class="text-[8px] md:text-[10px] font-black uppercase tracking-wider px-2 py-0.5 rounded-lg bg-blue-100 dark:bg-blue-900/50 text-blue-700 dark:text-blue-400 shadow-sm mt-0.5">Izin</span>
                        <?php elseif($hari['status'] == 'Alpha'): ?>
                            <span class="text-[8px] md:text-[10px] font-black uppercase tracking-wider px-2 py-0.5 rounded-lg bg-rose-100 dark:bg-rose-900/50 text-rose-700 dark:text-rose-400 shadow-sm mt-0.5">Alpha</span>
                        <?php elseif($hari['status'] == 'Belum'): ?>
                            <div class="w-1.5 h-1.5 rounded-full bg-slate-200 dark:bg-slate-600 mt-1"></div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="pt-6 border-t border-slate-100 dark:border-slate-700 flex flex-wrap gap-x-6 gap-y-3 items-center justify-center md:justify-start">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-widest mr-2">Indikator Warna:</span>
                
                <div class="flex items-center gap-2">
                    <div class="w-4 h-4 rounded-md bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-300 dark:border-emerald-800/50 flex items-center justify-center"><div class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></div></div>
                    <span class="text-xs font-bold text-emerald-600 dark:text-emerald-400 uppercase">Hadir</span>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-4 h-4 rounded-md bg-amber-50 dark:bg-amber-900/20 border border-amber-400 dark:border-amber-800/50"></div>
                    <span class="text-xs font-bold text-amber-600 dark:text-amber-400 uppercase">Sakit</span>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-4 h-4 rounded-md bg-blue-50 dark:bg-blue-900/20 border border-blue-400 dark:border-blue-800/50"></div>
                    <span class="text-xs font-bold text-blue-600 dark:text-blue-400 uppercase">Izin</span>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-4 h-4 rounded-md bg-rose-50 dark:bg-rose-900/20 border border-rose-400 dark:border-rose-800/50"></div>
                    <span class="text-xs font-bold text-rose-600 dark:text-rose-400 uppercase">Alpha</span>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-4 h-4 rounded-md bg-slate-100 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-800"></div>
                    <span class="text-xs font-bold text-slate-400 uppercase">Libur</span>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-4 h-4 rounded-md bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 border-dashed"></div>
                    <span class="text-xs font-bold text-slate-400 uppercase">Belum Diabsen</span>
                </div>
            </div>

        </div>
        <?php endif; ?>

    <?php endif; ?>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    // JS DITANAM LANGSUNG AGAR ANTI-CACHE
    document.addEventListener('DOMContentLoaded', function() {
        const items = document.querySelectorAll('.stagger-item');
        items.forEach((item, index) => {
            item.style.animationDelay = `${(index + 1) * 100}ms`;
        });
    });
</script>
<?= $this->endSection() ?>