<?= $this->extend('layout/main') ?>

<?= $this->section('title') ?>
  Laporan Akademik - Rapor Digital
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
  :root {
    --warna-primary: <?= $color['warna_primary'] ?? '#3b82f6' ?>;
    --warna-secondary: <?= $color['warna_secondary'] ?? '#eff6ff' ?>;
    --glow-primary: color-mix(in srgb, var(--warna-primary) 15%, transparent);
    --border-primary: color-mix(in srgb, var(--warna-primary) 30%, transparent);
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
      transition: all 0.3s ease;
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
  
  @keyframes pulseGlow { 0% { box-shadow: 0 0 0 0 var(--glow-primary); } 70% { box-shadow: 0 0 0 10px transparent; } 100% { box-shadow: 0 0 0 0 transparent; } }
  .pulse-aksen { animation: pulseGlow 2s infinite; }

  @keyframes slideUpFade { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
  .stagger-item { opacity: 0; animation: slideUpFade 0.6s ease forwards; }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="mb-12 w-full  mx-auto relative z-10">
    
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-6 stagger-item" style="animation-delay: 50ms;">
        <div>
            <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-white text-dinamis text-[10px] font-bold uppercase tracking-widest mb-3 shadow-sm border border-slate-100">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                Evaluasi Akademik
            </div>
            <h1 class="text-3xl md:text-4xl font-extrabold text-slate-800 tracking-tight leading-tight">
                Laporan <span class="font-light text-slate-500">Nilai Siswa</span>
            </h1>
        </div>
        
        <div class="bg-white rounded-xl p-1.5 flex shadow-sm border border-slate-200">
            <button class="px-5 py-2 rounded-lg bg-dinamis text-white text-xs font-bold transition-colors">Sem. Ganjil</button>
            <button class="px-5 py-2 rounded-lg text-slate-500 hover:bg-slate-50 text-xs font-bold transition-colors">Sem. Genap</button>
        </div>
    </div>

    <?php if(!$anak): ?>
        <div class="card-premium p-10 text-center">
            <h3 class="text-xl font-bold text-slate-800 mb-2">Data Anak Belum Terhubung</h3>
            <p class="text-slate-500">Mohon hubungi Administrator Sekolah untuk menghubungkan akun Anda.</p>
        </div>
    <?php elseif(empty($nilai)): ?>
        <div class="card-premium p-10 text-center">
            <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4 border border-slate-100">
                <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            </div>
            <h3 class="text-xl font-bold text-slate-800 mb-2">Belum Ada Nilai Akademik</h3>
            <p class="text-slate-500 text-sm">Nilai untuk semester ini sedang dalam proses input oleh guru mata pelajaran.</p>
        </div>
    <?php else: ?>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-10">
            <div class="card-premium p-6 flex flex-col justify-center items-center text-center stagger-item" style="animation-delay: 100ms;">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Rata-Rata Nilai</p>
                <div class="w-32 h-32 relative mb-2">
                    <svg viewBox="0 0 36 36" class="circular-chart">
                        <path class="circle-bg" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                        <path class="circle text-dinamis" stroke="currentColor" stroke-dasharray="<?= $rata_rata ?>, 100" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                    </svg>
                    <div class="absolute inset-0 flex items-center justify-center flex-col">
                        <span class="text-3xl font-black text-slate-800"><?= $rata_rata ?></span>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-2 grid grid-cols-1 sm:grid-cols-2 gap-6 stagger-item" style="animation-delay: 150ms;">
                <div class="card-premium p-6 bg-emerald-50/30 border-emerald-100 flex flex-col justify-center">
                    <p class="text-[10px] font-bold text-emerald-600/70 uppercase tracking-widest mb-1">Terkuat</p>
                    <h3 class="text-xl font-bold text-slate-800"><?= esc($mapel_terkuat['nama_mapel'] ?? '-') ?></h3>
                    <p class="text-sm font-semibold text-slate-500 mt-2">Skor: <span class="text-emerald-600 font-black text-lg"><?= $mapel_terkuat['nilai_angka'] ?? '-' ?></span></p>
                </div>
                <div class="card-premium p-6 bg-amber-50/30 border-amber-100 flex flex-col justify-center">
                    <p class="text-[10px] font-bold text-amber-600/70 uppercase tracking-widest mb-1">Perlu Bimbingan</p>
                    <h3 class="text-xl font-bold text-slate-800"><?= esc($mapel_perhatian['nama_mapel'] ?? '-') ?></h3>
                    <p class="text-sm font-semibold text-slate-500 mt-2">Skor: <span class="text-amber-600 font-black text-lg"><?= $mapel_perhatian['nilai_angka'] ?? '-' ?></span></p>
                </div>
            </div>
        </div>

        <h3 class="font-extrabold text-slate-800 text-sm uppercase tracking-widest mb-5 px-2">Rincian Nilai Akademik</h3>
        
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6 mb-12">
            <?php foreach($nilai as $n): 
                $angka = floatval($n['nilai_angka']);
                $kkm = intval($n['kkm'] ?? 75);
                if($angka >= 90) { $warna = 'emerald'; } elseif($angka >= $kkm) { $warna = 'blue'; } else { $warna = 'rose'; }
            ?>
                <div class="card-premium p-6 flex flex-col justify-between">
                    <div>
                        <div class="flex justify-between items-start mb-4">
                            <h4 class="font-bold text-slate-800 text-base leading-tight w-2/3"><?= esc($n['nama_mapel']) ?></h4>
                            <span class="px-2.5 py-1 bg-<?= $warna ?>-50 text-<?= $warna ?>-600 text-[10px] font-black rounded-lg uppercase tracking-wider border border-<?= $warna ?>-100 shadow-sm">
                                <?= esc($n['predikat'] ?? '-') ?>
                            </span>
                        </div>
                        <div class="bg-slate-50 rounded-xl p-3 mb-5 border-l-4 border-<?= $warna ?>-400 text-sm text-slate-600 font-medium">
                            <?= esc($n['catatan'] ?? 'Belum ada catatan khusus dari pengampu.') ?>
                        </div>
                    </div>
                    <div>
                        <div class="flex justify-between items-end mb-1.5">
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">KKM: <?= $kkm ?></span>
                            <span class="text-2xl font-black text-slate-800"><?= $angka ?></span>
                        </div>
                        <div class="w-full bg-slate-100 rounded-full h-1.5 overflow-hidden">
                            <div class="bg-<?= $warna ?>-500 h-full rounded-full" style="width: <?= $angka ?>%"></div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-12" style="animation: none; opacity: 1;">
            
            <div>
                <h3 class="font-extrabold text-slate-800 text-sm uppercase tracking-widest mb-5 px-2">Pengembangan Diri</h3>
                <div class="card-premium p-6">
                    <?php if(empty($ekskul)): ?>
                        <p class="text-sm text-slate-500 text-center py-4">Belum ada data ekstrakurikuler.</p>
                    <?php else: ?>
                        <div class="space-y-4">
                            <?php foreach($ekskul as $eks): ?>
                                <div class="flex items-center justify-between p-3 rounded-xl bg-slate-50 border border-slate-100">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-dinamis-light text-dinamis flex items-center justify-center font-bold">🎯</div>
                                        <div>
                                            <p class="font-bold text-slate-800 text-sm"><?= esc($eks['nama_kegiatan']) ?></p>
                                            <p class="text-[11px] text-slate-500"><?= esc($eks['keterangan'] ?? 'Aktif mengikuti kegiatan') ?></p>
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
                <h3 class="font-extrabold text-slate-800 text-sm uppercase tracking-widest mb-5 px-2">Pesan Wali Kelas</h3>
                <div class="card-premium p-8 bg-gradient-to-br from-white to-dinamis-light border-dinamis/20">
                    <?php 
                        $teks_catatan = (!empty($catatan_wali) && !empty($catatan_wali['catatan_wali_kelas'])) ? $catatan_wali['catatan_wali_kelas'] : 'Ananda telah mengikuti pembelajaran dengan baik. Tingkatkan terus belajarnya.';
                        $status_naik = (!empty($catatan_wali) && !empty($catatan_wali['status_kenaikan'])) ? $catatan_wali['status_kenaikan'] : 'Menunggu Evaluasi Akhir';
                    ?>
                    <p class="text-slate-700 italic leading-relaxed text-[15px] font-medium">
                        "<?= esc($teks_catatan) ?>"
                    </p>
                    <div class="mt-6 flex items-center justify-between border-t border-slate-200 pt-4">
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Keputusan:</span>
                        <span class="px-3 py-1 bg-emerald-100 text-emerald-700 font-bold text-xs rounded-lg"><?= esc($status_naik) ?></span>
                    </div>
                </div>
            </div>
        </div>

    <?php endif; ?> <div class="mb-12 mt-8" style="animation: none; opacity: 1;">
        <h3 class="font-extrabold text-slate-800 text-sm uppercase tracking-widest mb-5 px-2">Panduan Skala Penilaian</h3>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-10">
            <div class="card-premium p-4 flex items-center gap-4 border-l-4 border-l-emerald-500">
                <div class="w-10 h-10 rounded-full bg-emerald-50 text-emerald-600 font-black flex items-center justify-center text-lg">A</div>
                <div>
                    <p class="text-xs font-bold text-slate-800 uppercase">Sangat Baik</p>
                    <p class="text-[10px] font-medium text-slate-500 mt-0.5">Skor: 90 - 100</p>
                </div>
            </div>
            <div class="card-premium p-4 flex items-center gap-4 border-l-4 border-l-blue-500">
                <div class="w-10 h-10 rounded-full bg-blue-50 text-blue-600 font-black flex items-center justify-center text-lg">B</div>
                <div>
                    <p class="text-xs font-bold text-slate-800 uppercase">Baik</p>
                    <p class="text-[10px] font-medium text-slate-500 mt-0.5">Skor: 80 - 89</p>
                </div>
            </div>
            <div class="card-premium p-4 flex items-center gap-4 border-l-4 border-l-amber-500">
                <div class="w-10 h-10 rounded-full bg-amber-50 text-amber-600 font-black flex items-center justify-center text-lg">C</div>
                <div>
                    <p class="text-xs font-bold text-slate-800 uppercase">Cukup</p>
                    <p class="text-[10px] font-medium text-slate-500 mt-0.5">Skor: 70 - 79</p>
                </div>
            </div>
            <div class="card-premium p-4 flex items-center gap-4 border-l-4 border-l-rose-500">
                <div class="w-10 h-10 rounded-full bg-rose-50 text-rose-600 font-black flex items-center justify-center text-lg">D</div>
                <div>
                    <p class="text-xs font-bold text-slate-800 uppercase">Kurang</p>
                    <p class="text-[10px] font-medium text-slate-500 mt-0.5">Skor: < 70</p>
                </div>
            </div>
        </div>
    </div>

    <div style="animation: none; opacity: 1;">
        <div class="card-premium bg-gray-500 text-white border-0 p-8 relative overflow-hidden flex flex-col md:flex-row items-center justify-between gap-6 shadow-2xl">
            <div class="absolute -right-20 -top-20 w-64 h-64 bg-white/5 rounded-full blur-3xl"></div>
            <div class="absolute left-0 bottom-0 w-32 h-32 bg-dinamis/20 rounded-tr-full blur-2xl"></div>
            
            <div class="relative z-10 flex items-center gap-5">
                <div class="w-14 h-14 bg-white/10 rounded-2xl flex items-center justify-center backdrop-blur-md border border-white/10">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                </div>
                <div>
                    <h4 class="text-lg font-bold tracking-wide">Dokumen Tervalidasi Sistem</h4>
                    <p class="text-sm text-slate-400 mt-1">Laporan akademik ini dikelola dan divalidasi langsung oleh sistem Rapor Digital SMPIT.</p>
                </div>
            </div>
            
            <div class="relative z-10 w-full md:w-auto text-center md:text-right">
                <a href="/orangtua/dashboard" class="inline-block px-6 py-3 bg-dinamis hover:brightness-110 transition-all text-white font-bold text-xs rounded-xl shadow-lg">
                    Unduh Versi PDF
                </a>
            </div>
        </div>
    </div>

</div>
<?= $this->endSection() ?>