<?= $this->extend('layout/main') ?>

<?= $this->section('title') ?>
  Progres Tahfidz Ananda - Rapor Digital SMPIT
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
  :root {
    --warna-primary: <?= $color['warna_primary'] ?? '#10b981' ?>;
    --warna-secondary: <?= $color['warna_secondary'] ?? '#ecfdf5' ?>;
    --warna-primary-dark: color-mix(in srgb, var(--warna-primary) 40%, black);
    --warna-primary-transparan: color-mix(in srgb, var(--warna-primary) 15%, transparent);
    --warna-gold: #fbbf24; 
    --warna-scroll: <?= $color['warna_primary'] ?>;
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

  /* Dark Mode Enhancements */
  html.dark .quran-glass { background: rgba(30, 41, 59, 0.95); border-color: #334155; }
  html.dark .text-slate-800 { color: #f1f5f9 !important; }
  html.dark .text-slate-700 { color: #cbd5e1 !important; }
  html.dark .text-slate-600 { color: #94a3b8 !important; }
  html.dark .text-slate-500 { color: #64748b !important; }
  html.dark .bg-slate-50 { background-color: #0f172a !important; }
  html.dark .bg-slate-100 { background-color: #1e293b !important; }
  html.dark .border-slate-100, html.dark .border-slate-200 { border-color: #334155 !important; }
  
  html.dark .timeline-container::before { background: linear-gradient(to bottom, var(--warna-primary), #1e293b, transparent); }
  
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
<div class="fixed top-0 right-0 w-[600px] h-[600px] rounded-full mix-blend-multiply dark:mix-blend-lighten filter blur-[120px] bg-islamic-blob pointer-events-none" style="background-color: var(--warna-primary); z-index: -1;"></div>

<div class="mb-12 w-full mx-auto relative z-10">
    
    <div class="bg-header-dinamis rounded-[2.5rem] p-8 md:p-12 text-white shadow-2xl relative overflow-hidden mb-10 stagger-item" style="animation-delay: 50ms;">
        <div class="absolute top-0 right-0 opacity-10 pointer-events-none">
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
                    Mutaba'ah Yaumiyah
                </span>
                <h1 class="text-4xl md:text-5xl font-black tracking-tight leading-tight mb-2 text-white">
                    Hafalan <span class="text-[#fbbf24] font-light italic">Al-Quran</span>
                </h1>
                <p class="text-white/80 font-medium text-sm">"Sebaik-baik kalian adalah yang mempelajari Al-Qur'an dan mengajarkannya." (HR. Bukhari)</p>
            </div>
            
            <?php if($anak): ?>
            <div class="bg-white/10 backdrop-blur-md border border-white/20 p-4 rounded-3xl flex items-center gap-4 shadow-xl">
                <div class="w-14 h-14 rounded-full bg-white text-dinamis flex items-center justify-center font-black text-xl shadow-inner flex-shrink-0 overflow-hidden border-2 border-white/20">
                    <?php 
                        $inisial = !empty($anak['nama_lengkap']) ? strtoupper(substr($anak['nama_lengkap'], 0, 2)) : 'U';
                        $fotoFinal = $anak['foto_fix'] ?? '';
                    ?>
                    
                    <?php if($fotoFinal !== ''): ?>
                        <?php $cacheBuster = '?v=' . time(); ?>
                        <img src="<?= base_url('assets/uploads/avatars/' . $fotoFinal) . $cacheBuster ?>" 
                             alt="Foto Ananda" 
                             class="w-full h-full object-cover"
                             onerror="this.onerror=function(){ this.outerHTML='<span class=\'text-dinamis\'><?= $inisial ?></span>'; }; this.src='<?= base_url('uploads/siswa/' . $fotoFinal) . $cacheBuster ?>';">
                    <?php else: ?>
                        <span class="text-dinamis"><?= $inisial ?></span>
                    <?php endif; ?>
                </div>
                <div class="pr-2">
                    <p class="text-[10px] text-white/70 font-bold uppercase tracking-widest">Data Ananda</p>
                    <h3 class="font-bold text-white text-base leading-tight mt-0.5"><?= esc($anak['nama_lengkap']) ?></h3>
                    <span class="text-[10px] text-white/80 font-medium mt-1 inline-block">Kelas <?= esc($anak['kelas']) ?></span>
                </div>
            </div>
            <?php endif; ?>
        </div>

        <?php if(!empty($setoran)): ?>
        <div class="mt-12 grid grid-cols-2 md:grid-cols-4 gap-4 relative z-20">
            <div class="bg-white/10 backdrop-blur-sm border border-white/20 rounded-2xl p-4 text-center">
                <p class="text-[10px] text-white/70 uppercase tracking-widest mb-1">Surah Terakhir</p>
                <h4 class="text-lg font-bold text-white truncate" title="<?= esc($tahfidz_terakhir['surah']) ?>"><?= esc($tahfidz_terakhir['surah']) ?></h4>
                <p class="text-xs text-[#fbbf24] mt-0.5">Ayat <?= esc($tahfidz_terakhir['ayat']) ?></p>
            </div>
            <div class="bg-white/10 backdrop-blur-sm border border-white/20 rounded-2xl p-4 text-center">
                <p class="text-[10px] text-white/70 uppercase tracking-widest mb-1">Total Setoran</p>
                <h4 class="text-2xl font-black text-white"><?= $statistik['total_setoran'] ?> <span class="text-xs font-normal">kali</span></h4>
            </div>
            <div class="bg-white/10 backdrop-blur-sm border border-white/20 rounded-2xl p-4 text-center">
                <p class="text-[10px] text-white/70 uppercase tracking-widest mb-1">Ziyadah Baru</p>
                <h4 class="text-2xl font-black text-white"><?= $statistik['ziyadah'] ?> <span class="text-xs font-normal">kali</span></h4>
            </div>
            <div class="bg-white/10 backdrop-blur-sm border border-white/20 rounded-2xl p-4 text-center">
                <p class="text-[10px] text-white/70 uppercase tracking-widest mb-1">Murojaah</p>
                <h4 class="text-2xl font-black text-white"><?= $statistik['murojaah'] ?> <span class="text-xs font-normal">kali</span></h4>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4 stagger-item" style="animation-delay: 80ms;">
        <div class="bg-white dark:bg-slate-800 rounded-xl p-1.5 flex shadow-sm border border-slate-200 dark:border-slate-700">
            <a href="?semester=Ganjil" class="px-5 py-2 rounded-lg <?= $semester_aktif === 'Ganjil' ? 'bg-[var(--warna-primary)] text-white' : 'text-slate-500 hover:bg-slate-50 dark:hover:bg-slate-700' ?> text-xs font-bold transition-colors">Semester Ganjil</a>
            <a href="?semester=Genap" class="px-5 py-2 rounded-lg <?= $semester_aktif === 'Genap' ? 'bg-[var(--warna-primary)] text-white' : 'text-slate-500 hover:bg-slate-50 dark:hover:bg-slate-700' ?> text-xs font-bold transition-colors">Semester Genap</a>
        </div>

        <button onclick="openModalUnduhTahfidz()" class="w-full md:w-auto px-6 py-3 bg-white dark:bg-slate-800 border-2 border-dinamis/20 hover:border-dinamis text-dinamis rounded-2xl font-bold text-sm transition-all flex items-center justify-center gap-2 shadow-sm hover:shadow-lg active:scale-95 group">
             <div class="w-8 h-8 rounded-lg bg-dinamis-light text-dinamis flex items-center justify-center group-hover:scale-110 transition-transform">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
             </div>
             Unduh Rapor Tahfidz
        </button>
    </div>

    <?php if(empty($setoran)): ?>
        <div class="quran-glass p-12 text-center relative z-10 mx-6 border-slate-200 mb-12">
            <div class="w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4 border" style="background-color: var(--warna-secondary); border-color: var(--warna-primary-transparan); color: var(--warna-primary);">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
            </div>
            <h3 class="text-2xl font-bold text-slate-800 dark:text-white mb-2">Belum Ada Riwayat Hafalan</h3>
            <p class="text-slate-500 text-sm">Ananda belum melakukan setoran hafalan pada semester ini.</p>
        </div>
    <?php else: ?>

        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8 ml-2 stagger-item" style="animation-delay: 100ms;">
            <div class="flex items-center gap-3">
                <svg class="w-6 h-6 text-dinamis" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                <h2 class="text-xl font-bold text-slate-800 dark:text-white tracking-tight">Riwayat Setoran Harian</h2>
            </div>
            <div class="relative w-full md:w-64">
                <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <input type="text" id="searchSetoran" placeholder="Cari nama surah..." class="w-full pl-9 pr-4 py-2.5 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-xs font-semibold focus:outline-none focus:border-dinamis focus:ring-1 focus:ring-dinamis transition-all text-slate-700 dark:text-slate-200 shadow-sm" onkeyup="filterRiwayat()">
            </div>
        </div>

        <div class="timeline-container px-2 sm:px-0 w-full mb-12">
            <?php 
            $delay = 150;
            foreach($setoran as $row): 
                $isZiyadah = ($row['jenis_setoran'] == 'Ziyadah');
                $p = strtoupper($row['predikat']);
                
                $predikatIcon = '✨'; 
                $predikatColor = 'text-blue-500 dark:text-blue-400';
                $p_lang = 'Lancar'; 
                
                if(in_array($p, ['SANGAT LANCAR', 'MUMTAZ', 'A'])) { 
                    $predikatIcon = '🌟'; $predikatColor = 'text-emerald-500 dark:text-emerald-400'; $p_lang = 'Mutqin / Sangat Lancar'; 
                } elseif(in_array($p, ['LANCAR', 'JAYYID JIDDAN', 'B'])) { 
                    $predikatIcon = '✨'; $predikatColor = 'text-blue-500 dark:text-blue-400'; $p_lang = 'Jayyid Jiddan / Lancar'; 
                } elseif(in_array($p, ['JAYYID', 'C'])) { 
                    $predikatIcon = '⚠️'; $predikatColor = 'text-amber-500 dark:text-amber-400'; $p_lang = 'Jayyid / Cukup'; 
                } elseif(in_array($p, ['KURANG LANCAR', 'MAQBÜL', 'MAQBUL', 'D'])) { 
                    $predikatIcon = '⚠️'; $predikatColor = 'text-orange-500 dark:text-orange-400'; $p_lang = 'Maqbül / Kurang Lancar'; 
                } elseif(in_array($p, ['BELUM HAFAL', 'MARDÜD', 'MARDUD', 'E'])) { 
                    $predikatIcon = '🚨'; $predikatColor = 'text-rose-500 dark:text-rose-400'; $p_lang = 'Mardüd / Belum Hafal'; 
                }
            ?>
            
            <div class="relative pl-12 md:pl-16 mb-8 timeline-card group stagger-item riwayat-row" style="animation-delay: <?= $delay ?>ms;">
                <?php if($isZiyadah): ?>
                    <div class="timeline-node absolute left-[15px] top-4 w-5 h-5 rounded-full border-4 border-white dark:border-slate-900 shadow-md z-10" style="background-color: var(--warna-primary);"></div>
                <?php else: ?>
                    <div class="timeline-node absolute left-[15px] top-4 w-5 h-5 rounded-full bg-[#fbbf24] border-4 border-white dark:border-slate-900 shadow-md z-10"></div>
                <?php endif; ?>
                
                <div class="quran-glass p-5 md:p-6 flex flex-col md:flex-row gap-5 md:gap-8 items-start md:items-center">
                    <div class="w-full md:w-40 flex-shrink-0 border-b md:border-b-0 md:border-r border-slate-200 dark:border-slate-700 pb-4 md:pb-0 pr-0 md:pr-4">
                        <p class="text-sm font-bold text-slate-800 dark:text-white mb-1"><?= date('d M Y', strtotime($row['tanggal'])) ?></p>
                        <?php if($isZiyadah): ?>
                            <span class="inline-block px-2.5 py-1 text-[10px] font-black uppercase tracking-wider rounded-md border bg-dinamis-light text-dinamis border-dinamis/20">
                                Ziyadah
                            </span>
                        <?php else: ?>
                            <span class="inline-block px-2.5 py-1 bg-amber-50 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400 border-amber-200 dark:border-amber-800/50 text-[10px] font-black uppercase tracking-wider rounded-md border">
                                Muroja'ah
                            </span>
                        <?php endif; ?>
                    </div>

                    <div class="flex-grow">
                        <h4 class="text-xl font-extrabold text-slate-800 dark:text-white leading-tight mb-1 surah-name"><?= esc($row['surah']) ?></h4>
                        <p class="text-sm font-semibold text-slate-500">Ayat ke- <span class="font-bold text-dinamis"><?= esc($row['ayat']) ?></span></p>
                    </div>

                    <div class="w-full md:w-1/3 flex-shrink-0 bg-slate-50/50 dark:bg-slate-900/50 rounded-xl p-4 border border-slate-100 dark:border-slate-800 group-hover:bg-white dark:group-hover:bg-slate-800 transition-colors">
                        <div class="flex items-center gap-2 mb-2">
                            <span class="text-base"><?= $predikatIcon ?></span>
                            <span class="text-xs font-bold <?= $predikatColor ?> uppercase tracking-wide"><?= esc($p_lang) ?></span>
                        </div>
                        <?php if(!empty($row['catatan'])): ?>
                            <p class="text-xs text-slate-500 dark:text-slate-400 italic leading-relaxed">"<?= esc($row['catatan']) ?>"</p>
                        <?php else: ?>
                            <p class="text-[10px] text-slate-400 dark:text-slate-500">Tidak ada catatan ustaz.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            
            <?php 
                $delay += 50;
            endforeach; 
            ?>

            <div id="noResult" class="hidden text-center py-8 text-sm text-slate-500 font-medium">Nama surah yang Anda cari tidak ditemukan.</div>
        </div>

    <?php endif; ?>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-12" style="animation: none; opacity: 1;">
        
        <div class="quran-glass p-8 flex flex-col justify-between relative overflow-hidden">
            <div class="absolute -right-8 -top-8 w-32 h-32 rounded-full opacity-10" style="background-color: var(--warna-primary);"></div>
            
            <div>
                <div class="flex items-center gap-3 mb-5 relative z-10">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center border bg-dinamis-light text-dinamis border-dinamis/20">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    </div>
                    <h3 class="font-extrabold text-slate-800 dark:text-white text-sm uppercase tracking-widest">Target Semester Ini</h3>
                </div>
                
                <?php if(!empty($target)): ?>
                    <h4 class="text-2xl font-black text-slate-800 dark:text-white leading-tight mb-2"><?= esc($target['nama_juz']) ?></h4>
                    <p class="text-sm text-slate-500 dark:text-slate-400 mb-6 leading-relaxed">
                        Siswa diwajibkan menyelesaikan hafalan dari <strong class="text-dinamis">Surah <?= esc($target['surah_mulai']) ?></strong> hingga <strong class="text-dinamis">Surah <?= esc($target['surah_sampai']) ?></strong> pada semester ini.
                    </p>

                    <div class="relative z-10">
                        <div class="flex justify-between items-end mb-2">
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Minimal Hafalan: <?= esc($target['minimal_hafalan']) ?> Baris</span>
                            <span class="text-xs font-black text-dinamis uppercase">Wajib Tuntas</span>
                        </div>
                        <div class="w-full bg-slate-100 dark:bg-slate-700 rounded-full h-2 overflow-hidden border border-slate-200 dark:border-slate-600">
                            <div class="h-full rounded-full transition-all duration-1000 w-[100%] bg-dinamis"></div>
                        </div>
                    </div>
                <?php else: ?>
                    <h4 class="text-xl font-bold text-slate-500 dark:text-slate-400 italic mt-6">Target Belum Ditetapkan</h4>
                    <p class="text-sm text-slate-400 dark:text-slate-500 mt-2">Pihak sekolah belum menginput target hafalan wajib untuk kelas ananda di semester ini.</p>
                <?php endif; ?>
            </div>
        </div>

        <div class="quran-glass p-8 relative overflow-hidden">
            <div class="flex items-center gap-3 mb-6 relative z-10">
                <div class="w-10 h-10 rounded-full flex items-center justify-center bg-amber-50 dark:bg-amber-900/30 text-amber-600 border border-amber-100 dark:border-amber-800/50">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                </div>
                <h3 class="font-extrabold text-slate-800 dark:text-white text-sm uppercase tracking-widest">Buku Panduan Mutaba'ah</h3>
            </div>
            
            <ul class="space-y-4 relative z-10">
                <li class="flex gap-3 items-start">
                    <svg class="w-5 h-5 text-dinamis flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <div>
                        <h5 class="text-sm font-bold text-slate-800 dark:text-slate-200">Ziyadah (Hafalan Baru)</h5>
                        <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-0.5">Penambahan ayat baru yang belum pernah dihafalkan sebelumnya.</p>
                    </div>
                </li>
                <li class="flex gap-3 items-start">
                    <svg class="w-5 h-5 text-dinamis flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <div>
                        <h5 class="text-sm font-bold text-slate-800 dark:text-slate-200">Muroja'ah (Pengulangan)</h5>
                        <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-0.5">Mengulang hafalan lama agar tetap terjaga dan tidak lupa.</p>
                    </div>
                </li>
                <li class="flex gap-3 items-start">
                    <svg class="w-5 h-5 text-dinamis flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <div>
                        <h5 class="text-sm font-bold text-slate-800 dark:text-slate-200">Dukungan Orang Tua</h5>
                        <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-0.5">Mohon Ayah/Bunda untuk terus menyimak hafalan ananda di rumah.</p>
                    </div>
                </li>
            </ul>
        </div>
    </div>

</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    // FUNGSI JAVASCRIPT DITANAM LANGSUNG UNTUK MENGHINDARI CACHE BROWSER
    document.addEventListener('DOMContentLoaded', function() {
        const items = document.querySelectorAll('.stagger-item');
        items.forEach((item, index) => {
            item.style.animationDelay = `${(index + 1) * 80}ms`;
        });
    });

    // Fitur Live Search untuk mencari Surah
    function filterRiwayat() {
        const input = document.getElementById("searchSetoran");
        if(!input) return;
        
        const filter = input.value.toLowerCase();
        const nodes = document.getElementsByClassName('riwayat-row');
        let visibleCount = 0;

        for (let i = 0; i < nodes.length; i++) {
            let surahName = nodes[i].querySelector('.surah-name').innerText.toLowerCase();
            
            if (surahName.includes(filter)) {
                nodes[i].style.display = "";
                visibleCount++;
            } else {
                nodes[i].style.display = "none";
            }
        }
        
        const noResult = document.getElementById('noResult');
        if (noResult) {
            noResult.style.display = visibleCount === 0 ? "block" : "none";
        }
    }

    // Fungsi Popup Unduh Rapor Tahfidz per Juz (Premium Version)
    function openModalUnduhTahfidz() {
        const pColor = '<?= $color['warna_primary'] ?? '#10b981' ?>';
        const logoUrl = '<?= base_url('uploads/logo/' . ($sekolah['logo'] ?? 'none.png')) ?>';

        Swal.fire({
            title: 'Persiapan Undah',
            html: `
                <div class="flex flex-col items-center gap-4 py-4">
                    <img src="${logoUrl}" class="w-16 h-16 object-contain mb-2 animate-bounce">
                    <div class="space-y-1 text-center">
                        <p class="text-sm text-slate-500">Mencari riwayat hafalan ananda...</p>
                        <div class="flex gap-1 justify-center">
                            <span class="w-2 h-2 rounded-full bg-dinamis animate-pulse"></span>
                            <span class="w-2 h-2 rounded-full bg-dinamis animate-pulse" style="animation-delay: 0.2s"></span>
                            <span class="w-2 h-2 rounded-full bg-dinamis animate-pulse" style="animation-delay: 0.4s"></span>
                        </div>
                    </div>
                </div>
            `,
            allowOutsideClick: false,
            showConfirmButton: false,
            didOpen: () => {
                // Ambil daftar Juz secara dinamis
                fetch('<?= base_url('orangtua/tahfidz/get-available-juz') ?>')
                    .then(response => response.json())
                    .then(result => {
                        if (result.status === 'success' && result.data.length > 0) {
                            let options = {};
                            result.data.forEach(item => {
                                options[item.id] = item.nama_juz;
                            });

                            Swal.fire({
                                title: '<span class="text-xl font-bold">Unduh Rapor Tahfidz</span>',
                                html: `
                                    <div class="text-center mb-6">
                                        <div class="w-20 h-20 bg-dinamis-light rounded-2xl flex items-center justify-center mx-auto mb-4">
                                            <svg class="w-10 h-10 text-dinamis" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18 18.247 18.477 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                                        </div>
                                        <p class="text-slate-500 text-sm px-4">Silakan pilih Juz yang ingin ustadz unduh laporannya.</p>
                                    </div>
                                `,
                                input: 'select',
                                inputOptions: options,
                                inputPlaceholder: '-- Pilih Juz Hafalan --',
                                showCancelButton: true,
                                confirmButtonText: 'Mulai Unduh',
                                confirmButtonColor: pColor,
                                cancelButtonText: 'Batal',
                                customClass: {
                                    input: 'rounded-xl border-slate-200 text-sm focus:ring-0 focus:border-dinamis',
                                    confirmButton: 'rounded-xl px-8 font-bold',
                                    cancelButton: 'rounded-xl px-8 font-medium'
                                },
                                inputValidator: (value) => {
                                    if (!value) {
                                        return 'Pilih Juz-nya dulu ya ustadz!';
                                    }
                                }
                            }).then((res) => {
                                if (res.isConfirmed) {
                                    window.open('<?= base_url('orangtua/tahfidz/download-rapor') ?>/' + res.value, '_blank');
                                }
                            });
                        } else {
                            // Kondisi Jika TIDAK ADA PROGRES/SETORAN
                            Swal.fire({
                                title: '<span class="text-xl font-bold">Belum Ada Progres</span>',
                                html: `
                                    <div class="text-center py-4">
                                        <div class="w-24 h-24 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-6">
                                            <svg class="w-12 h-12 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 9.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        </div>
                                        <h4 class="font-bold text-slate-800 mb-2">Opss! Data Belum Tersedia</h4>
                                        <p class="text-sm text-slate-500 px-6 leading-relaxed">
                                            Mohon maaf ustadz, saat ini sistem belum mencatat riwayat setoran hafalan (Ziyadah/Murojaah) untuk ananda. 
                                            <br><br>
                                            <span class="text-dinamis font-medium">Saran:</span> Silakan koordinasi dengan <b>Ustadz Pembimbing Tahfidz</b> untuk sinkronisasi data setoran terbaru.
                                        </p>
                                    </div>
                                `,
                                confirmButtonText: 'Tutup Saja',
                                confirmButtonColor: pColor,
                                customClass: {
                                    confirmButton: 'rounded-xl px-10'
                                }
                            });
                        }
                    })
                    .catch(err => {
                        console.error(err);
                        Swal.fire({ icon: 'error', title: 'Kesalahan', text: 'Gagal terhubung ke sistem.' });
                    });
            }
        });
    }
</script>
<?= $this->endSection() ?>