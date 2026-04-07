<?= $this->extend('layout/main') ?>

<?= $this->section('title') ?>
  Data Akademik Ananda - Rapor Digital
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
  
  .card-premium {
      background: rgba(255, 255, 255, 0.95);
      backdrop-filter: blur(10px);
      border: 1px solid #f1f5f9;
      border-radius: 1.5rem;
      box-shadow: 0 4px 20px -5px rgba(0, 0, 0, 0.03);
      transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  }
  .card-premium:hover {
      border-color: var(--border-primary);
      box-shadow: 0 10px 30px -5px var(--glow-primary);
      transform: translateY(-2px);
  }

  .circular-chart { display: block; margin: 0 auto; max-width: 80%; max-height: 250px; }
  .circle-bg { fill: none; stroke: #f1f5f9; stroke-width: 3.8; }
  .circle { fill: none; stroke-width: 2.8; stroke-linecap: round; animation: progress 1.5s ease-out forwards; }
  @keyframes progress { 0% { stroke-dasharray: 0 100; } }
  
  @keyframes slideUpFade { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
  .stagger-item { opacity: 0; animation: slideUpFade 0.6s ease forwards; }

  /* Dark Mode Enhancements */
  html.dark .text-dinamis { color: color-mix(in srgb, var(--warna-primary) 80%, white) !important; }
  html.dark .bg-dinamis-light { background-color: rgba(255, 255, 255, 0.05) !important; }
  html.dark .card-premium { background: #1e293b !important; border-color: #334155 !important; }
  html.dark .card-premium:hover { border-color: var(--warna-primary) !important; box-shadow: 0 10px 30px -5px rgba(0,0,0,0.5); }
  
  html.dark .text-slate-800 { color: #f1f5f9 !important; }
  html.dark .text-slate-700 { color: #e2e8f0 !important; }
  html.dark .text-slate-600 { color: #cbd5e1 !important; }
  html.dark .text-slate-500 { color: #94a3b8 !important; }
  html.dark .text-slate-400 { color: #64748b !important; }
  
  html.dark .bg-slate-100 { background-color: #0f172a !important; border-color: #334155 !important; }
  html.dark .bg-slate-50 { background-color: #1e293b !important; }
  html.dark .border-slate-100, html.dark .border-slate-200 { border-color: #334155 !important; }

  html.dark .circle-bg { stroke: #334155; }

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
<div class="mb-12 w-full mx-auto relative z-10">
    
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-6 stagger-item" style="animation-delay: 50ms;">
        <div>
            <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-white dark:bg-slate-800 text-dinamis text-[10px] font-bold uppercase tracking-widest mb-3 shadow-sm border border-slate-100 dark:border-slate-700">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                Evaluasi Belajar
            </div>
            <h1 class="text-3xl md:text-4xl font-bold text-slate-800 tracking-tight leading-tight">
                Capaian <span class="font-semibold text-slate-800">Akademik</span>
            </h1>
        </div>
        
        <div class="bg-white dark:bg-slate-800 rounded-xl p-1.5 flex shadow-sm border border-slate-200 dark:border-slate-700">
            <a href="?semester=Ganjil" class="px-5 py-2 rounded-lg <?= $semester_aktif === 'Ganjil' ? 'bg-dinamis text-white' : 'text-slate-500 hover:bg-slate-50 dark:hover:bg-slate-700' ?> text-xs font-bold transition-colors">Semester Ganjil</a>
            <a href="?semester=Genap" class="px-5 py-2 rounded-lg <?= $semester_aktif === 'Genap' ? 'bg-dinamis text-white' : 'text-slate-500 hover:bg-slate-50 dark:hover:bg-slate-700' ?> text-xs font-bold transition-colors">Semester Genap</a>
        </div>
    </div>

    <?php if(!$anak): ?>
        <div class="card-premium p-10 text-center">
            <h3 class="text-xl font-bold text-slate-800 mb-2">Data Anak Belum Terhubung</h3>
            <p class="text-slate-500">Silakan hubungi administrator sekolah untuk menyinkronkan data Ananda dengan akun Anda.</p>
        </div>
    
    <?php elseif(empty($nilai)): ?>
        <div class="card-premium p-10 text-center">
            <div class="w-16 h-16 bg-slate-50 dark:bg-slate-900 rounded-full flex items-center justify-center mx-auto mb-4 border border-slate-100 dark:border-slate-700">
                <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            </div>
            <h3 class="text-xl font-bold text-slate-800 mb-2">Penilaian Semester <?= esc($semester_aktif) ?> Belum Tersedia</h3>
            <p class="text-slate-500 text-sm"><?= esc($sapaan) ?>, data nilai akademik ananda untuk semester ini masih dalam proses perekapan oleh wali kelas.</p>
        </div>
    <?php else: ?>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-10">
            
            <div class="card-premium p-6 flex flex-col justify-center items-center text-center stagger-item" style="animation-delay: 100ms;">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Rata-Rata Kelas</p>
                <div class="w-32 h-32 relative mb-2">
                    <svg viewBox="0 0 36 36" class="circular-chart">
                        <path class="circle-bg" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                        <path class="circle text-dinamis" stroke="currentColor" stroke-dasharray="<?= $rata_rata ?>, 100" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                    </svg>
                    <div class="absolute inset-0 flex items-center justify-center flex-col">
                        <span class="text-3xl font-black text-slate-800 dark:text-white"><?= $rata_rata ?></span>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-2 grid grid-cols-1 sm:grid-cols-2 gap-6 stagger-item" style="animation-delay: 150ms;">
                <div class="card-premium p-6 bg-emerald-50/30 dark:bg-emerald-900/10 border-emerald-100 dark:border-emerald-900/30 flex flex-col justify-center">
                    <p class="text-[10px] font-bold text-emerald-600/70 dark:text-emerald-500 uppercase tracking-widest mb-1">Mata Pelajaran Terkuat</p>
                    <h3 class="text-xl font-bold text-slate-800 dark:text-white"><?= esc($mapel_terkuat['nama_mapel'] ?? '-') ?></h3>
                    <p class="text-sm font-semibold text-slate-500 mt-2">Nilai Akhir: <span class="text-emerald-600 font-black text-lg"><?= $mapel_terkuat['nilai_angka'] ?? '-' ?></span></p>
                </div>
                
                <div class="card-premium p-6 bg-amber-50/30 dark:bg-amber-900/10 border-amber-100 dark:border-amber-900/30 flex flex-col justify-center">
                    <p class="text-[10px] font-bold text-amber-600/70 dark:text-amber-500 uppercase tracking-widest mb-1">Perlu Bimbingan Ekstra</p>
                    <h3 class="text-xl font-bold text-slate-800 dark:text-white"><?= esc($mapel_perhatian['nama_mapel'] ?? '-') ?></h3>
                    <p class="text-sm font-semibold text-slate-500 mt-2">Nilai Akhir: <span class="text-amber-600 font-black text-lg"><?= $mapel_perhatian['nilai_angka'] ?? '-' ?></span></p>
                </div>
            </div>
        </div>

        <h3 class="font-extrabold text-slate-800 text-sm uppercase tracking-widest mb-5 px-2">Rincian Nilai per Mata Pelajaran</h3>
        
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6 mb-12">
            <?php foreach($nilai as $n): 
                $angka = floatval($n['nilai_angka']);
                $kkm = intval($n['kkm'] ?? 75);
                if($angka >= 90) { $warna = 'emerald'; } elseif($angka >= $kkm) { $warna = 'blue'; } else { $warna = 'rose'; }
            ?>
                <div class="card-premium p-6 flex flex-col justify-between group">
                    <div>
                        <div class="flex justify-between items-start mb-4">
                            <h4 class="font-bold text-slate-800 text-base leading-tight w-2/3"><?= esc($n['nama_mapel']) ?></h4>
                            <span class="px-2.5 py-1 bg-<?= $warna ?>-50 dark:bg-<?= $warna ?>-900/20 text-<?= $warna ?>-600 dark:text-<?= $warna ?>-400 text-[10px] font-black rounded-lg uppercase tracking-wider border border-<?= $warna ?>-100 dark:border-<?= $warna ?>-800/50 shadow-sm">
                                Predikat <?= esc($n['predikat'] ?? '-') ?>
                            </span>
                        </div>
                        <div class="bg-slate-50 dark:bg-slate-900/50 rounded-xl p-3 mb-5 border-l-4 border-<?= $warna ?>-400 text-sm text-slate-600 dark:text-slate-400 font-medium">
                            <?= esc($n['catatan'] ?? "Nilai ananda pada mata pelajaran ini sudah mencapai ketuntasan.") ?>
                        </div>
                    </div>
                    <div>
                        <div class="flex justify-between items-end mb-1.5">
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">KKM: <?= $kkm ?></span>
                            <span class="text-2xl font-black text-slate-800 dark:text-white"><?= $angka ?></span>
                        </div>
                        <div class="w-full bg-slate-100 dark:bg-slate-700 rounded-full h-1.5 overflow-hidden">
                            <div class="bg-<?= $warna ?>-500 h-full rounded-full transition-all duration-1000" style="width: <?= $angka ?>%"></div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-12" style="animation: none; opacity: 1;">
            
            <div>
                <h3 class="font-extrabold text-slate-800 text-sm uppercase tracking-widest mb-5 px-2">Pengembangan Diri / Ekskul</h3>
                <div class="card-premium p-6">
                    <?php if(empty($ekskul)): ?>
                        <p class="text-sm text-slate-500 text-center py-4">Ananda belum terdaftar dalam kegiatan ekstrakurikuler pada semester ini.</p>
                    <?php else: ?>
                        <div class="space-y-4">
                            <?php foreach($ekskul as $eks): ?>
                                <div class="flex items-center justify-between p-3 rounded-xl bg-slate-50 dark:bg-slate-900 border border-slate-100 dark:border-slate-700">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-dinamis-light text-dinamis flex items-center justify-center font-bold">🎯</div>
                                        <div>
                                            <p class="font-bold text-slate-800 text-sm"><?= esc($eks['nama_kegiatan']) ?></p>
                                            <p class="text-[11px] text-slate-500"><?= esc($eks['keterangan'] ?? 'Berpartisipasi Aktif') ?></p>
                                        </div>
                                    </div>
                                    <span class="px-3 py-1 bg-dinamis text-white font-black text-xs rounded-lg"><?= esc($eks['predikat'] ?? '-') ?></span>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <div>
                <h3 class="font-extrabold text-slate-800 text-sm uppercase tracking-widest mb-5 px-2">Pesan & Kesan Wali Kelas</h3>
                <div class="card-premium p-8 bg-gradient-to-br from-white dark:from-slate-800 to-dinamis-light dark:to-slate-900/50 border-dinamis/20">
                    <?php 
                        $teks_catatan = "Belum ada catatan dari wali kelas.";
                        $status_naik = "Menunggu Evaluasi";

                        if(!empty($catatan_wali) && !empty($catatan_wali['catatan_wali_kelas'])){
                            $parts = explode('|', $catatan_wali['catatan_wali_kelas']);
                            if(count($parts) > 3) {
                                $teks_catatan = $parts[3];
                            } else {
                                $teks_catatan = $catatan_wali['catatan_wali_kelas'];
                            }
                            $status_naik = $catatan_wali['status_kenaikan'] ?? 'Menunggu Evaluasi';
                        }
                    ?>
                    <p class="text-slate-700 dark:text-slate-300 italic leading-relaxed text-[15px] font-medium">
                        "<?= esc($teks_catatan) ?>"
                    </p>
                    <div class="mt-6 flex items-center justify-between border-t border-slate-200 dark:border-slate-700 pt-4">
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Keputusan Akademik</span>
                        <span class="px-3 py-1 bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400 font-bold text-xs rounded-lg border border-emerald-200 dark:border-emerald-800/50"><?= esc($status_naik) ?></span>
                    </div>
                </div>
            </div>
        </div>

    <?php endif; ?> 
    
    <div class="mb-12 mt-8" style="animation: none; opacity: 1;">
        <h3 class="font-extrabold text-slate-800 text-sm uppercase tracking-widest mb-5 px-2">Panduan Skala Predikat</h3>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-10">
            <div class="card-premium p-4 flex items-center gap-4 border-l-4 border-l-emerald-500">
                <div class="w-10 h-10 rounded-full bg-emerald-50 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-500 font-black flex items-center justify-center text-lg">A</div>
                <div>
                    <p class="text-xs font-bold text-slate-800 uppercase">Sangat Baik</p>
                    <p class="text-[10px] font-medium text-slate-500 mt-0.5">Skor: 90 - 100</p>
                </div>
            </div>
            <div class="card-premium p-4 flex items-center gap-4 border-l-4 border-l-blue-500">
                <div class="w-10 h-10 rounded-full bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-500 font-black flex items-center justify-center text-lg">B</div>
                <div>
                    <p class="text-xs font-bold text-slate-800 uppercase">Baik</p>
                    <p class="text-[10px] font-medium text-slate-500 mt-0.5">Skor: 80 - 89</p>
                </div>
            </div>
            <div class="card-premium p-4 flex items-center gap-4 border-l-4 border-l-amber-500">
                <div class="w-10 h-10 rounded-full bg-amber-50 dark:bg-amber-900/30 text-amber-600 dark:text-amber-500 font-black flex items-center justify-center text-lg">C</div>
                <div>
                    <p class="text-xs font-bold text-slate-800 uppercase">Cukup</p>
                    <p class="text-[10px] font-medium text-slate-500 mt-0.5">Skor: 70 - 79</p>
                </div>
            </div>
            <div class="card-premium p-4 flex items-center gap-4 border-l-4 border-l-rose-500">
                <div class="w-10 h-10 rounded-full bg-rose-50 dark:bg-rose-900/30 text-rose-600 dark:text-rose-500 font-black flex items-center justify-center text-lg">D</div>
                <div>
                    <p class="text-xs font-bold text-slate-800 uppercase">Kurang</p>
                    <p class="text-[10px] font-medium text-slate-500 mt-0.5">Skor: < 70</p>
                </div>
            </div>
        </div>
    </div>

    

</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    const BASE_URL = "<?= rtrim(base_url(), '/') ?>";
    const WARNA_PRIMARY = "<?= $color['warna_primary'] ?? '#3b82f6' ?>";
</script>
<script src="<?= base_url('assets/js/OrangTua/akademik.js') ?>?v=<?= time() ?>"></script>
<?= $this->endSection() ?>