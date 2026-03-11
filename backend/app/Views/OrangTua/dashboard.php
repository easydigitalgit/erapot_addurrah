<?= $this->extend('layout/main') ?>

<?= $this->section('title') ?>
  Dashboard Wali Murid - Rapor Digital SMPIT
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
  
  /* Blending gaya Futuristik dengan gaya Role Lain (Clean SaaS) */
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

  /* Animasi Latar Belakang (Subtle) */
  @keyframes float {
      0% { transform: translate(0px, 0px) scale(1); }
      50% { transform: translate(15px, -15px) scale(1.05); }
      100% { transform: translate(0px, 0px) scale(1); }
  }
  .bg-blob {
      animation: float 8s ease-in-out infinite alternate;
  }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="fixed top-0 right-0 w-[400px] h-[400px] rounded-full mix-blend-multiply filter blur-[80px] opacity-10 bg-blob pointer-events-none" style="background-color: var(--warna-primary); z-index: -1;"></div>

<div class="mb-10 w-full  mx-auto relative z-10">
    
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-6">
        <div>
            <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-dinamis-light text-dinamis text-[10px] font-bold uppercase tracking-widest mb-3 border border-dinamis/20">
                <span class="w-1.5 h-1.5 rounded-full bg-dinamis animate-pulse"></span>
                Portal Wali Murid
            </div>
            <h1 class="text-3xl md:text-4xl font-extrabold text-slate-800 tracking-tight leading-tight">
                Ahlan wa Sahlan,<br>
                <span class="text-slate-500 font-medium text-2xl md:text-3xl"><?= esc($user) ?></span>
            </h1>
        </div>
        
        <div class="card-premium px-5 py-3 flex items-center gap-4 border border-slate-100 w-full md:w-auto">
            <div class="w-12 h-12 rounded-full bg-slate-100 flex items-center justify-center font-bold text-lg text-slate-400 overflow-hidden ring-2 ring-white shadow-sm flex-shrink-0">
                <?php if(!empty($anak['foto']) && file_exists(FCPATH . 'uploads/siswa/' . $anak['foto'])): ?>
                    <img src="<?= base_url('uploads/siswa/' . $anak['foto']) ?>" alt="Foto Anak" class="w-full h-full object-cover">
                <?php else: ?>
                    <span class="text-dinamis"><?= substr($anak['nama_lengkap'], 0, 1) ?></span>
                <?php endif; ?>
            </div>
            <div>
                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mb-0.5">Memantau Ananda</p>
                <h3 class="font-bold text-slate-800 text-sm leading-tight"><?= esc($anak['nama_lengkap']) ?></h3>
                <div class="flex items-center gap-2 mt-1">
                    <span class="text-[10px] text-slate-500 font-medium bg-slate-100 px-2 py-0.5 rounded">NIS: <?= esc($anak['nis']) ?></span>
                    <span class="text-[10px] text-slate-500 font-medium bg-slate-100 px-2 py-0.5 rounded">Kelas <?= esc($anak['kelas']) ?></span>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-8">
        
        <div class="card-premium p-6 flex flex-col justify-between">
            <div class="flex justify-between items-start mb-4">
                <div class="w-10 h-10 rounded-full bg-blue-50 text-blue-500 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                </div>
                <span class="bg-blue-50 text-blue-600 text-[10px] font-bold px-2 py-1 rounded-md">Akademik</span>
            </div>
            <div>
                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-widest mb-1">Rata-rata Nilai</p>
                <h3 class="text-3xl font-black text-slate-800 tracking-tight"><?= $statistik['rata_nilai'] ?></h3>
            </div>
        </div>

        <div class="card-premium p-6 flex flex-col justify-between">
            <div class="flex justify-between items-start mb-4">
                <div class="w-10 h-10 rounded-full bg-emerald-50 text-emerald-500 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                </div>
                <span class="bg-emerald-50 text-emerald-600 text-[10px] font-bold px-2 py-1 rounded-md">Hafalan</span>
            </div>
            <div>
                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-widest mb-1">Capaian Terakhir</p>
                <h3 class="text-lg font-bold text-slate-800 tracking-tight leading-snug line-clamp-2" title="<?= esc($statistik['hafalan_terakhir']) ?>">
                    <?= esc($statistik['hafalan_terakhir']) ?>
                </h3>
            </div>
        </div>

        <div class="card-premium p-6 flex flex-col justify-between">
            <div class="flex justify-between items-start mb-4">
                <div class="w-10 h-10 rounded-full bg-amber-50 text-amber-500 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                </div>
                <span class="bg-amber-50 text-amber-600 text-[10px] font-bold px-2 py-1 rounded-md">Absensi</span>
            </div>
            <div>
                <p class="text-[11px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-1">Kehadiran</p>
                <div class="flex items-baseline gap-1">
                    <h3 class="text-3xl font-black text-slate-800 dark:text-white tracking-tight"><?= $statistik['kehadiran'] ?></h3>
                    <span class="text-lg font-bold text-slate-400">%</span>
                </div>
            </div>
        </div>

    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <div class="lg:col-span-2 card-premium p-6 md:p-8">
            <div class="flex justify-between items-center mb-8">
                <h3 class="font-extrabold text-slate-800 text-base uppercase tracking-widest flex items-center gap-2">
                    <svg class="w-5 h-5 text-dinamis" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Aktivitas Terbaru
                </h3>
            </div>
            
            <div class="relative">
                <div class="absolute left-[15px] top-2 bottom-2 w-0.5 bg-slate-100"></div>

                <div class="space-y-8">
                    <?php if(empty($aktivitas)): ?>
                        <div class="pl-12 py-4 text-sm text-slate-400">Belum ada aktivitas tercatat untuk ananda.</div>
                    <?php else: ?>
                        <?php foreach($aktivitas as $act): ?>
                            <?php 
                                // Penentuan warna icon berdasarkan jenis dari Controller
                                $bgColor = 'bg-slate-100'; $textColor = 'text-slate-500';
                                if($act['color'] == 'emerald') { $bgColor = 'bg-emerald-50'; $textColor = 'text-emerald-500'; }
                                elseif($act['color'] == 'blue') { $bgColor = 'bg-blue-50'; $textColor = 'text-blue-500'; }
                            ?>
                            <div class="relative pl-12 group">
                                <div class="absolute left-[-5px] top-1 w-4 h-4 rounded-full bg-white border-[3px] z-10 transition-transform duration-300 group-hover:scale-125 <?= $textColor ?>" style="border-color: currentColor;"></div>
                                
                                <div class="flex flex-col sm:flex-row sm:items-baseline gap-2 mb-1">
                                    <h4 class="font-bold text-slate-800 text-[15px]"><?= esc($act['judul']) ?></h4>
                                    <span class="text-[10px] font-bold text-slate-400 tracking-wider uppercase bg-slate-50 px-2 py-0.5 rounded w-fit border border-slate-100"><?= esc($act['waktu']) ?></span>
                                </div>
                                <p class="text-sm text-slate-500 leading-relaxed"><?= esc($act['deskripsi']) ?></p>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="space-y-6">
            
            <div class="card-premium p-6 relative flex flex-col items-center text-center overflow-hidden">
                <div class="absolute top-0 left-0 right-0 h-1 bg-dinamis"></div>
                
                <div class="w-16 h-16 rounded-2xl bg-slate-50 flex items-center justify-center mb-4 transform rotate-3 border border-slate-100 shadow-sm mt-2">
                    <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"></path></svg>
                </div>
                
                <h4 class="font-bold text-slate-800 text-base"><?= esc($wali_kelas['nama_lengkap']) ?></h4>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1 mb-5">Wali Kelas</p>
                
                <a href="https://wa.me/<?= $wali_kelas['no_wa'] ?>?text=<?= urlencode($wali_kelas['pesan_default']) ?>" target="_blank" class="w-full py-3 rounded-xl font-bold text-xs uppercase tracking-widest transition-all duration-300 flex items-center justify-center gap-2 border bg-white hover:bg-green-500 hover:border-green-500 hover:text-white" style="color: var(--warna-primary); border-color: var(--border-primary);">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                    <span>Hubungi Via WA</span>
                </a>
            </div>

            <div class="card-premium p-6 relative flex flex-col items-center text-center overflow-hidden">
                <div class="w-12 h-12 rounded-full bg-slate-50 flex items-center justify-center mb-3 border border-slate-100">
                    <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                </div>
                <h4 class="font-bold text-slate-800 text-sm">Dokumen e-Rapor</h4>
                <p class="text-[11px] text-slate-400 mt-1 mb-5 leading-relaxed px-2">Arsip digital hasil evaluasi belajar ananda tersedia untuk diunduh.</p>
                
                <button class="w-full py-2.5 bg-slate-800 hover:bg-slate-900 text-white rounded-xl text-xs font-bold transition-all shadow-sm">
                    Unduh PDF
                </button>
            </div>

        </div>
    </div>
</div>
<?= $this->endSection() ?>