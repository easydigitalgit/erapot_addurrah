<?= $this->extend('layout/main') ?>

<?= $this->section('title') ?>
  Laporan Tahfidz - Rapor Digital SMPIT
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
  :root {
    --warna-primary: <?= $color['warna_primary'] ?? '#10b981' ?>;
    --warna-secondary: <?= $color['warna_secondary'] ?? '#ecfdf5' ?>;
    
    --warna-primary-dark: color-mix(in srgb, var(--warna-primary) 40%, black);
    --warna-primary-light: color-mix(in srgb, var(--warna-primary) 80%, white);
    --warna-primary-transparan: color-mix(in srgb, var(--warna-primary) 15%, transparent);
    
    --warna-gold: #fbbf24; 
  }

  .quran-glass {
      background: rgba(255, 255, 255, 0.95);
      backdrop-filter: blur(12px);
      border: 1px solid var(--warna-primary-transparan);
      border-radius: 1.5rem;
      box-shadow: 0 10px 30px -5px rgba(0, 0, 0, 0.05);
      transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
  }
  .quran-glass:hover {
      box-shadow: 0 20px 40px -5px var(--warna-primary-transparan);
      transform: translateY(-3px);
  }

  .timeline-container { position: relative; }
  .timeline-container::before {
      content: ''; position: absolute; top: 0; bottom: 0; left: 1.5rem; width: 4px;
      background: linear-gradient(to bottom, var(--warna-primary), var(--warna-secondary), transparent);
      border-radius: 999px;
  }
  
  .timeline-node { transition: all 0.3s ease; }
  .timeline-card:hover .timeline-node { transform: scale(1.3); box-shadow: 0 0 15px var(--warna-gold); }

  @keyframes pulseSlow { 0% { transform: scale(1); opacity: 0.1; } 50% { transform: scale(1.2); opacity: 0.15; } 100% { transform: scale(1); opacity: 0.1; } }
  .bg-islamic-blob { animation: pulseSlow 8s infinite ease-in-out; }
  
  @keyframes slideRightFade { from { opacity: 0; transform: translateX(-20px); } to { opacity: 1; transform: translateX(0); } }
  .stagger-item { opacity: 0; animation: slideRightFade 0.6s ease forwards; }

  .bg-header-dinamis { background: linear-gradient(135deg, var(--warna-primary-dark), var(--warna-primary)); }
  .text-dinamis { color: var(--warna-primary); }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="fixed top-0 right-0 w-[600px] h-[600px] rounded-full mix-blend-multiply filter blur-[120px] bg-islamic-blob pointer-events-none" style="background-color: var(--warna-primary); z-index: -1;"></div>

<div class="mb-12 w-full mx-auto relative z-10">
    
    <div class="bg-header-dinamis rounded-[2.5rem] p-8 md:p-12 text-white shadow-2xl relative overflow-hidden mb-10 stagger-item" style="animation-delay: 50ms;">
        <div class="absolute top-0 right-0 opacity-10">
            <svg width="300" height="300" viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">
                <circle cx="50" cy="50" r="48" stroke="currentColor" stroke-width="2"/>
                <circle cx="50" cy="50" r="35" stroke="currentColor" stroke-width="1" stroke-dasharray="4 4"/>
                <path d="M50 0L50 100M0 50L100 50" stroke="currentColor" stroke-width="1"/>
                <path d="M15 15L85 85M15 85L85 15" stroke="currentColor" stroke-width="0.5"/>
            </svg>
        </div>

        <div class="relative z-10 flex flex-col md:flex-row justify-between items-center md:items-start gap-8">
            <div class="text-center md:text-left">
                <span class="px-4 py-1.5 rounded-full bg-white/10 border border-white/20 text-[#fbbf24] text-[10px] font-bold uppercase tracking-[0.2em] mb-4 inline-block">
                    Mutaba'ah Yaumiyyah
                </span>
                <h1 class="text-4xl md:text-5xl font-black tracking-tight leading-tight mb-2 text-white">
                    Perjalanan <span class="text-[#fbbf24] font-light italic">Hafalan</span>
                </h1>
                <p class="text-white/80 font-medium text-sm">"Sebaik-baik kalian adalah yang mempelajari Al-Qur'an dan mengajarkannya." (HR. Bukhari)</p>
            </div>
            
            <?php if($anak): ?>
            <div class="bg-white/10 backdrop-blur-md border border-white/20 p-4 rounded-3xl flex items-center gap-4 shadow-xl">
                <div class="w-14 h-14 rounded-full bg-white text-dinamis flex items-center justify-center font-black text-xl shadow-inner flex-shrink-0">
                    <?= substr($anak['nama_lengkap'], 0, 1) ?>
                </div>
                <div class="pr-2">
                    <p class="text-[10px] text-white/70 font-bold uppercase tracking-widest">Santri</p>
                    <h3 class="font-bold text-white text-base leading-tight mt-0.5"><?= esc($anak['nama_lengkap']) ?></h3>
                    <span class="text-[10px] text-white/80 font-medium mt-1 inline-block">Kelas <?= esc($anak['kelas']) ?></span>
                </div>
            </div>
            <?php endif; ?>
        </div>

        <?php if(!empty($setoran)): ?>
        <div class="mt-12 grid grid-cols-2 md:grid-cols-4 gap-4 relative z-20">
            <div class="bg-white/10 backdrop-blur-sm border border-white/20 rounded-2xl p-4 text-center">
                <p class="text-[10px] text-white/70 uppercase tracking-widest mb-1">Capaian Terakhir</p>
                <h4 class="text-lg font-bold text-white truncate" title="<?= esc($tahfidz_terakhir['surah']) ?>"><?= esc($tahfidz_terakhir['surah']) ?></h4>
                <p class="text-xs text-[#fbbf24] mt-0.5">Ayat <?= esc($tahfidz_terakhir['ayat']) ?></p>
            </div>
            <div class="bg-white/10 backdrop-blur-sm border border-white/20 rounded-2xl p-4 text-center">
                <p class="text-[10px] text-white/70 uppercase tracking-widest mb-1">Total Setor</p>
                <h4 class="text-2xl font-black text-white"><?= $statistik['total_setoran'] ?></h4>
            </div>
            <div class="bg-white/10 backdrop-blur-sm border border-white/20 rounded-2xl p-4 text-center">
                <p class="text-[10px] text-white/70 uppercase tracking-widest mb-1">Ziyadah</p>
                <h4 class="text-2xl font-black text-white"><?= $statistik['ziyadah'] ?></h4>
            </div>
            <div class="bg-white/10 backdrop-blur-sm border border-white/20 rounded-2xl p-4 text-center">
                <p class="text-[10px] text-white/70 uppercase tracking-widest mb-1">Muroja'ah</p>
                <h4 class="text-2xl font-black text-white"><?= $statistik['murojaah'] ?></h4>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <?php if(empty($setoran)): ?>
        <div class="quran-glass p-12 text-center mt-[-3rem] relative z-10 mx-6 border-slate-200 mb-12">
            <div class="w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4 border" style="background-color: var(--warna-secondary); border-color: var(--warna-primary-transparan); color: var(--warna-primary);">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
            </div>
            <h3 class="text-2xl font-bold text-slate-800 mb-2">Buku Mutaba'ah Masih Kosong</h3>
            <p class="text-slate-500 text-sm">Ananda belum mencatatkan setoran hafalan pada semester ini.</p>
        </div>
    <?php else: ?>

        <div class="flex items-center gap-3 mb-8 ml-2 stagger-item" style="animation-delay: 100ms;">
            <svg class="w-6 h-6 text-dinamis" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            <h2 class="text-xl font-bold text-slate-800 tracking-tight">Riwayat Detail Setoran</h2>
        </div>

        <div class="timeline-container px-2 sm:px-0 w-full mb-12">
            <?php 
            $delay = 150;
            foreach($setoran as $row): 
                $isZiyadah = ($row['jenis_setoran'] == 'Ziyadah');
                $p = $row['predikat'];
                $predikatIcon = '✨'; $predikatColor = 'text-blue-500';
                if($p == 'Sangat Lancar') { $predikatIcon = '🌟'; $predikatColor = 'text-emerald-500'; }
                elseif($p == 'Kurang Lancar') { $predikatIcon = '⚠️'; $predikatColor = 'text-amber-500'; }
                elseif($p == 'Belum Hafal') { $predikatIcon = '🚨'; $predikatColor = 'text-rose-500'; }
            ?>
            
            <div class="relative pl-12 md:pl-16 mb-8 timeline-card group stagger-item" style="animation-delay: <?= $delay ?>ms;">
                <?php if($isZiyadah): ?>
                    <div class="timeline-node absolute left-[15px] top-4 w-5 h-5 rounded-full border-4 border-white shadow-md z-10" style="background-color: var(--warna-primary);"></div>
                <?php else: ?>
                    <div class="timeline-node absolute left-[15px] top-4 w-5 h-5 rounded-full bg-[#fbbf24] border-4 border-white shadow-md z-10"></div>
                <?php endif; ?>
                
                <div class="quran-glass p-5 md:p-6 flex flex-col md:flex-row gap-5 md:gap-8 items-start md:items-center">
                    <div class="w-full md:w-40 flex-shrink-0 border-b md:border-b-0 md:border-r border-slate-200 pb-4 md:pb-0 pr-0 md:pr-4">
                        <p class="text-sm font-bold text-slate-800 mb-1"><?= date('d M Y', strtotime($row['tanggal'])) ?></p>
                        <?php if($isZiyadah): ?>
                            <span class="inline-block px-2.5 py-1 text-[10px] font-black uppercase tracking-wider rounded-md border" style="background-color: var(--warna-secondary); color: var(--warna-primary); border-color: var(--warna-primary-transparan);">
                                <?= esc($row['jenis_setoran']) ?>
                            </span>
                        <?php else: ?>
                            <span class="inline-block px-2.5 py-1 bg-amber-50 text-amber-600 border-amber-200 text-[10px] font-black uppercase tracking-wider rounded-md border">
                                <?= esc($row['jenis_setoran']) ?>
                            </span>
                        <?php endif; ?>
                    </div>

                    <div class="flex-grow">
                        <h4 class="text-xl font-extrabold text-slate-800 leading-tight mb-1"><?= esc($row['surah']) ?></h4>
                        <p class="text-sm font-semibold text-slate-500">Ayat <span class="font-bold text-dinamis"><?= esc($row['ayat']) ?></span></p>
                    </div>

                    <div class="w-full md:w-1/3 flex-shrink-0 bg-slate-50/50 rounded-xl p-4 border border-slate-100 group-hover:bg-white transition-colors">
                        <div class="flex items-center gap-2 mb-2">
                            <span class="<?= $predikatColor ?> text-base"><?= $predikatIcon ?></span>
                            <span class="text-xs font-bold text-slate-700 uppercase tracking-wide"><?= esc($row['predikat']) ?></span>
                        </div>
                        <?php if(!empty($row['catatan'])): ?>
                            <p class="text-xs text-slate-500 italic leading-relaxed">"<?= esc($row['catatan']) ?>"</p>
                        <?php else: ?>
                            <p class="text-[10px] text-slate-400">Tidak ada catatan spesifik.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            
            <?php 
                $delay += 50;
            endforeach; 
            ?>
        </div>

    <?php endif; ?>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-12" style="animation: none; opacity: 1;">
        
        <div class="quran-glass p-8 flex flex-col justify-between relative overflow-hidden">
            <div class="absolute -right-8 -top-8 w-32 h-32 rounded-full opacity-10" style="background-color: var(--warna-primary);"></div>
            
            <div>
                <div class="flex items-center gap-3 mb-5 relative z-10">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center border" style="background-color: var(--warna-secondary); color: var(--warna-primary); border-color: var(--warna-primary-transparan);">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    </div>
                    <h3 class="font-extrabold text-slate-800 text-sm uppercase tracking-widest">Target Semester Ini</h3>
                </div>
                
                <?php if(!empty($target)): ?>
                    <h4 class="text-2xl font-black text-slate-800 leading-tight mb-2">Penyelesaian <?= esc($target['nama_juz']) ?></h4>
                    <p class="text-sm text-slate-500 mb-6 leading-relaxed">
                        Sesuai kurikulum SMPIT, target hafalan ananda pada semester ini adalah menuntaskan setoran mulai dari <strong class="text-dinamis">Surah <?= esc($target['surah_mulai']) ?></strong> hingga <strong class="text-dinamis">Surah <?= esc($target['surah_sampai']) ?></strong> dengan tahsin yang baik.
                    </p>

                    <div class="relative z-10">
                        <div class="flex justify-between items-end mb-2">
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Minimum Hafalan: <?= esc($target['minimal_hafalan']) ?> Ayat</span>
                            <span class="text-lg font-black text-dinamis">Wajib</span>
                        </div>
                        <div class="w-full bg-slate-100 rounded-full h-2 overflow-hidden border border-slate-200">
                            <div class="h-full rounded-full transition-all duration-1000 w-[80%]" style="background-color: var(--warna-primary);"></div>
                        </div>
                    </div>
                <?php else: ?>
                    <h4 class="text-xl font-bold text-slate-500 italic mt-6">Target Belum Ditetapkan</h4>
                    <p class="text-sm text-slate-400 mt-2">Kurikulum untuk target hafalan kelas ananda pada semester ini belum diinput oleh pihak sekolah.</p>
                <?php endif; ?>
            </div>
        </div>

        <div class="quran-glass p-8 relative overflow-hidden">
            <div class="flex items-center gap-3 mb-6 relative z-10">
                <div class="w-10 h-10 rounded-full flex items-center justify-center bg-amber-50 text-amber-600 border border-amber-100">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                </div>
                <h3 class="font-extrabold text-slate-800 text-sm uppercase tracking-widest">Panduan Wali Murid</h3>
            </div>
            
            <ul class="space-y-4 relative z-10">
                <li class="flex gap-3 items-start">
                    <svg class="w-5 h-5 text-dinamis flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <div>
                        <h5 class="text-sm font-bold text-slate-800">Simak Muroja'ah Harian</h5>
                        <p class="text-[11px] text-slate-500 mt-0.5">Luangkan waktu 15 menit ba'da Maghrib/Subuh untuk mendengarkan ananda mengulang hafalannya.</p>
                    </div>
                </li>
                <li class="flex gap-3 items-start">
                    <svg class="w-5 h-5 text-dinamis flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <div>
                        <h5 class="text-sm font-bold text-slate-800">Perdengarkan Murottal</h5>
                        <p class="text-[11px] text-slate-500 mt-0.5">Biasakan menyetel audio Al-Qur'an (Murottal) surah yang sedang dihafal saat berada di rumah atau kendaraan.</p>
                    </div>
                </li>
                <li class="flex gap-3 items-start">
                    <svg class="w-5 h-5 text-dinamis flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <div>
                        <h5 class="text-sm font-bold text-slate-800">Beri Apresiasi Positif</h5>
                        <p class="text-[11px] text-slate-500 mt-0.5">Berikan pujian atau hadiah kecil saat ananda berhasil mencapai target hafalan baru.</p>
                    </div>
                </li>
            </ul>
        </div>
        
    </div>

    <div class="mt-8 pt-8 border-t border-slate-200 w-full" style="animation: none; opacity: 1;">
        <h3 class="font-bold text-slate-800 text-xs uppercase tracking-widest mb-4">Indikator Mutaba'ah</h3>
        <div class="flex flex-wrap gap-4 mb-10">
            <div class="flex items-center gap-2 bg-white px-4 py-2 rounded-xl shadow-sm border border-slate-100">
                <span class="text-emerald-500">🌟</span> <span class="text-[11px] font-bold text-slate-600 uppercase">Sangat Lancar</span>
            </div>
            <div class="flex items-center gap-2 bg-white px-4 py-2 rounded-xl shadow-sm border border-slate-100">
                <span class="text-blue-500">✨</span> <span class="text-[11px] font-bold text-slate-600 uppercase">Lancar</span>
            </div>
            <div class="flex items-center gap-2 bg-white px-4 py-2 rounded-xl shadow-sm border border-slate-100">
                <span class="text-amber-500">⚠️</span> <span class="text-[11px] font-bold text-slate-600 uppercase">Kurang Lancar</span>
            </div>
            <div class="flex items-center gap-2 bg-white px-4 py-2 rounded-xl shadow-sm border border-slate-100">
                <span class="text-rose-500">🚨</span> <span class="text-[11px] font-bold text-slate-600 uppercase">Belum Hafal</span>
            </div>
            <div class="flex items-center gap-2 bg-white px-4 py-2 rounded-xl shadow-sm border border-slate-100">
                <div class="w-3 h-3 rounded-full" style="background-color: var(--warna-primary);"></div> <span class="text-[11px] font-bold text-slate-600 uppercase">Ziyadah (Baru)</span>
            </div>
            <div class="flex items-center gap-2 bg-white px-4 py-2 rounded-xl shadow-sm border border-slate-100">
                <div class="w-3 h-3 rounded-full bg-[#fbbf24]"></div> <span class="text-[11px] font-bold text-slate-600 uppercase">Muroja'ah (Ulang)</span>
            </div>
        </div>

        <div class="quran-glass bg-gradient-to-r from-slate-900 to-slate-800 text-white border-0 p-8 relative overflow-hidden flex flex-col md:flex-row items-center justify-between gap-6 shadow-2xl">
            <div class="absolute -right-20 -top-20 w-64 h-64 bg-white/5 rounded-full blur-3xl"></div>
            <div class="absolute left-0 bottom-0 w-32 h-32 rounded-tr-full blur-2xl opacity-20" style="background-color: var(--warna-primary);"></div>
            
            <div class="relative z-10 flex items-center gap-5 w-full md:w-auto">
                <div class="w-14 h-14 bg-white/10 rounded-2xl flex items-center justify-center backdrop-blur-md border border-white/20 flex-shrink-0">
                    <svg class="w-7 h-7" style="color: var(--warna-gold);" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                </div>
                <div>
                    <h4 class="text-lg font-bold tracking-wide text-white">Buku Mutaba'ah Digital Resmi</h4>
                    <p class="text-sm text-slate-400 mt-1 leading-relaxed">Laporan rekam jejak hafalan ini dikelola dan divalidasi langsung oleh Asatidz penyimak tahfidz SMPIT.</p>
                </div>
            </div>
            
            <div class="relative z-10 w-full md:w-auto text-center md:text-right">
                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mb-2">Arsip Dokumen Fisik</p>
                <a href="#" class="inline-block px-6 py-3 hover:brightness-110 transition-all text-slate-900 font-black text-xs rounded-xl shadow-lg" style="background-color: var(--warna-gold);">
                    Unduh Rekap PDF
                </a>
            </div>
        </div>
    </div>

</div>
<?= $this->endSection() ?>