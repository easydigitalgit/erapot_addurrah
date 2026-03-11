<?= $this->extend('layout/main') ?>

<?= $this->section('title') ?>
  Siswa Perlu Pembinaan - Rapor Digital SMPIT Ad Durrah
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
  <link rel="stylesheet" href="<?= base_url('assets/css/WaliKelas/perlu-pembinaan.css') ?>">
  <style>
    /* Injeksi Warna Dinamis dari Database */
    :root {
      --warna-primary: <?= $color['warna_primary'] ?? '#10b981' ?>;
      --warna-secondary: <?= $color['warna_secondary'] ?? '#ecfdf5' ?>;
    }
    .text-tema { color: var(--warna-primary) !important; }
    .bg-tema { background-color: var(--warna-primary) !important; }
    .bg-tema-light { background-color: var(--warna-secondary) !important; }
    .bg-tema-15 { background-color: color-mix(in srgb, var(--warna-primary) 15%, white) !important; }
    .border-tema { border-color: var(--warna-primary) !important; }
    .hover-bg-tema:hover { background-color: color-mix(in srgb, var(--warna-primary) 85%, black) !important; }
    .hover-text-tema:hover { color: color-mix(in srgb, var(--warna-primary) 80%, black) !important; }
  </style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<?php if(!$rombel): ?>
    <div class="bg-amber-50 border border-amber-200 rounded-xl p-6 text-center mb-6">
        <h3 class="text-lg font-bold text-amber-800 mb-2">Akses Terbatas</h3>
        <p class="text-sm text-amber-700">Anda belum ditetapkan sebagai Wali Kelas pada Tahun Ajaran ini.</p>
    </div>
<?php else: ?>

<div class="mb-6">
    <div class="flex items-start justify-between mb-4">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-red-100 to-red-50 flex items-center justify-center">
                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
            </div>
            <div>
                <h2 class="text-xl lg:text-2xl font-bold text-gray-800">Siswa Perlu Pembinaan</h2>
                <p class="text-sm text-gray-500 mt-0.5">Kelas <?= esc($rombel['nama_rombel']) ?> • Monitor siswa yang membutuhkan pendampingan khusus</p>
            </div>
        </div>
        <div class="hidden md:flex gap-2">
            <button onclick="openListModal()" class="bg-white text-tema font-semibold py-2 px-4 rounded-xl border border-gray-200 hover:border-tema transition-all flex items-center gap-2 text-sm shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg> 
                Daftar Lengkap Siswa
            </button>
            <button onclick="openNoteModal()" class="bg-tema hover-bg-tema text-white font-semibold py-2 px-4 rounded-xl transition-all shadow-md shadow-[var(--warna-primary)]/20 flex items-center gap-2 text-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg> 
                Tambah Catatan
            </button>
        </div>
    </div>

    <div class="grid grid-cols-2 sm:grid-cols-4 gap-2 lg:gap-3 mb-6">
        <div class="chip flex items-center gap-2 px-3 py-2 bg-white border border-gray-200 rounded-lg shadow-sm hover:shadow-md transition-all">
            <div class="w-8 h-8 rounded-lg bg-red-50 flex items-center justify-center flex-shrink-0">
                <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
            </div>
            <div class="flex-1">
                <p class="text-xs text-gray-500">Akademik</p>
                <p class="text-lg font-bold text-red-600"><?= $statistik['akademik'] ?? 0 ?></p>
            </div>
        </div>
        <div class="chip flex items-center gap-2 px-3 py-2 bg-white border border-gray-200 rounded-lg shadow-sm hover:shadow-md transition-all">
            <div class="w-8 h-8 rounded-lg bg-amber-50 flex items-center justify-center flex-shrink-0">
                <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            </div>
            <div class="flex-1">
                <p class="text-xs text-gray-500">Karakter</p>
                <p class="text-lg font-bold text-amber-600"><?= $statistik['karakter'] ?? 0 ?></p>
            </div>
        </div>
        <div class="chip flex items-center gap-2 px-3 py-2 bg-white border border-gray-200 rounded-lg shadow-sm hover:shadow-md transition-all">
            <div class="w-8 h-8 rounded-lg bg-blue-50 flex items-center justify-center flex-shrink-0">
                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
            </div>
            <div class="flex-1">
                <p class="text-xs text-gray-500">Tahfidz</p>
                <p class="text-lg font-bold text-blue-600"><?= $statistik['tahfidz'] ?? 0 ?></p>
            </div>
        </div>
        <div class="chip flex items-center gap-2 px-3 py-2 bg-white border border-gray-200 rounded-lg shadow-sm hover:shadow-md transition-all">
            <div class="w-8 h-8 rounded-lg bg-purple-50 flex items-center justify-center flex-shrink-0">
                <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
            </div>
            <div class="flex-1">
                <p class="text-xs text-gray-500">Absensi</p>
                <p class="text-lg font-bold text-purple-600"><?= $statistik['absensi'] ?? 0 ?></p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 lg:gap-6 mb-6">
        
        <div class="lg:col-span-2 space-y-3" id="studentsList">
            <?php if(empty($siswa_pembinaan)): ?>
                <div class="bg-white rounded-xl p-10 border border-gray-100 shadow-sm text-center h-full flex flex-col justify-center items-center">
                    <div class="w-16 h-16 bg-tema-light text-tema rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-800">Alhamdulillah, Nihil Laporan</h3>
                    <p class="text-sm text-gray-500 mt-2 max-w-md mx-auto">Saat ini belum ada siswa di kelas <?= esc($rombel['nama_rombel']) ?> yang terdeteksi membutuhkan pembinaan khusus.</p>
                </div>
            <?php else: ?>
                <?php foreach($siswa_pembinaan as $siswa): ?>
                <div class="student-card <?= $siswa['status'] ?> bg-white rounded-xl p-4 border border-gray-100 shadow-sm hover:shadow-md transition-all cursor-pointer" onclick="openNoteModal('<?= $siswa['siswa_id'] ?? '' ?>')">
                    <div class="flex items-center justify-between mb-3">
                        <div class="flex items-center gap-3 flex-1">
                            <div class="relative">
                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-<?= $siswa['tema'] ?>-400 to-<?= $siswa['tema'] ?>-500 flex items-center justify-center text-white text-sm font-bold shadow-md">
                                    <?= esc($siswa['inisial']) ?>
                                </div>
                                <?php if($siswa['status'] == 'urgent'): ?>
                                <div class="absolute -bottom-1 -right-1 w-5 h-5 rounded-full bg-red-600 border-2 border-white flex items-center justify-center">
                                    <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8zm3.5-9c.83 0 1.5-.67 1.5-1.5S16.33 8 15.5 8 14 8.67 14 9.5s.67 1.5 1.5 1.5zm-7 0c.83 0 1.5-.67 1.5-1.5S9.33 8 8.5 8 7 8.67 7 9.5 7.67 11 8.5 11zm3.5 6.5c2.33 0 4.31-1.46 5.11-3.5H6.89c.8 2.04 2.78 3.5 5.11 3.5z" /></svg>
                                </div>
                                <?php endif; ?>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-800"><?= esc($siswa['nama']) ?></p>
                                <p class="text-xs text-gray-500">NISN: <?= esc($siswa['nisn']) ?> <span class="text-[10px] text-red-500 ml-1 font-semibold">(<?= esc($siswa['pesan'] ?? '') ?>)</span></p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="tooltip"> 
                                <span class="flex items-center justify-center w-6 h-6 bg-<?= $siswa['tema'] ?>-100 text-<?= $siswa['tema'] ?>-600 rounded-full cursor-help text-xs font-bold">!</span> 
                                <span class="tooltiptext"><?= $siswa['status'] == 'urgent' ? 'Perlu Tindakan Segera' : 'Perlu Perhatian' ?></span> 
                            </span>
                        </div>
                    </div>
                    <div class="flex items-center justify-between">
                        <div class="flex flex-wrap gap-1.5">
                            <?php foreach($siswa['kategori'] as $kat): ?>
                                <span class="px-2.5 py-1 bg-<?= $siswa['tema'] ?>-100 text-<?= $siswa['tema'] ?>-700 text-xs font-semibold rounded-full"><?= esc($kat) ?></span>
                            <?php endforeach; ?>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <button onclick="openNoteModal('<?= $siswa['siswa_id'] ?? '' ?>'); event.stopPropagation();" class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-50 rounded-lg transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                            </button> 
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 flex flex-col">
            <div class="p-4 lg:p-5 border-b border-gray-100">
                <h2 class="font-semibold text-gray-800">Karakter &amp; Kedisiplinan</h2>
            </div>
            <div class="p-4 lg:p-5 space-y-5">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-lg bg-tema-15 flex items-center justify-center">
                            <svg class="w-4 h-4 text-tema" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-800">Absensi Kelas</p>
                            <p class="text-xs text-gray-500">Bulan ini</p>
                        </div>
                    </div>
                    <span class="text-lg font-bold text-tema">Terpantau</span>
                </div>
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-lg bg-red-50 flex items-center justify-center">
                            <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-800">Pelanggaran</p>
                            <p class="text-xs text-gray-500">Semester ini</p>
                        </div>
                    </div>
                    <span class="text-lg font-bold text-red-600"><?= $statistik['karakter'] ?? 0 ?></span>
                </div>
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-lg bg-amber-50 flex items-center justify-center">
                            <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" /></svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-800">Prestasi</p>
                            <p class="text-xs text-gray-500">Bulan ini</p>
                        </div>
                    </div>
                    <span class="text-lg font-bold text-amber-600">0</span>
                </div>
                <div class="pt-3 border-t border-gray-100">
                    <div class="flex items-center justify-between mb-2">
                        <p class="text-sm font-medium text-gray-800">Progres Tahfidz Kelas</p>
                        <span class="text-xs font-medium text-tema">Sedang Berjalan</span>
                    </div>
                    <div class="w-full h-2 bg-gray-100 rounded-full overflow-hidden">
                        <div class="h-full bg-tema rounded-full progress-bar" style="width: 50%"></div>
                    </div>
                    <p class="text-[10px] text-gray-400 mt-2">Menunggu update data setoran harian</p>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 mb-6">
        <div class="p-4 lg:p-5 border-b border-gray-100 flex items-center justify-between">
            <h2 class="font-semibold text-gray-800">Riwayat Catatan & Tindakan Terbaru</h2>
            <a href="#" class="text-xs text-tema hover-text-tema font-medium">Lihat Semua →</a>
        </div>
        <div class="p-4 lg:p-5">
            <?php if(empty($siswa_pembinaan)): ?>
                <p class="text-sm text-gray-500 text-center py-4">Belum ada riwayat pembinaan yang tercatat.</p>
            <?php else: ?>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <div class="flex items-start gap-3 p-3 bg-tema-light rounded-lg border border-tema/20">
                        <div class="w-8 h-8 rounded-lg bg-tema-15 flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4 text-tema" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" /></svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-800">Sistem Berjalan</p>
                            <p class="text-xs text-gray-500 mt-0.5">Pemantauan kelas aktif</p>
                            <p class="text-xs text-tema mt-1 font-medium">Otomatis</p>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<div id="noteModal" class="fixed inset-0 z-[60] hidden flex items-center justify-center p-4 sm:p-0">
    <div id="noteBackdrop" class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm opacity-0 transition-opacity duration-300" onclick="closeNoteModal()"></div>
    
    <div id="noteContent" class="relative bg-white rounded-3xl shadow-2xl flex flex-col overflow-hidden w-full max-w-xl z-10 scale-95 opacity-0 transition-all duration-300">
        
        <div class="relative bg-gradient-to-br from-[var(--warna-primary)] to-emerald-700 p-6 text-white overflow-hidden flex-shrink-0">
            <div class="absolute top-0 right-0 -mt-4 -mr-4 w-32 h-32 bg-white/10 rounded-full blur-2xl"></div>
            <div class="absolute bottom-0 left-0 -mb-4 -ml-4 w-24 h-24 bg-black/10 rounded-full blur-xl"></div>
            
            <div class="relative z-10 flex items-start justify-between">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-white/20 rounded-2xl backdrop-blur-md flex items-center justify-center border border-white/30 shadow-inner">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    </div>
                    <div>
                        <h3 class="font-extrabold text-xl tracking-wide">Catat Pembinaan</h3>
                        <p class="text-emerald-100 text-sm mt-0.5">Dokumentasi progres karakter siswa</p>
                    </div>
                </div>
                <button onclick="closeNoteModal()" class="p-2 bg-white/10 hover:bg-white/20 rounded-xl transition-all border border-white/10">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
        </div>
        
        <form id="formPembinaan" action="<?= base_url('wali/perlu-pembinaan/save') ?>" method="POST" onsubmit="savePembinaan(event)" class="flex flex-col max-h-[calc(100vh-8rem)]">
            <input type="hidden" name="rombel_id" value="<?= esc($rombel['id'] ?? '') ?>">
            
            <div class="p-6 space-y-6 overflow-y-auto custom-scrollbar flex-1 bg-slate-50/50">
                <div class="space-y-2">
                    <label class="flex items-center gap-2 text-sm font-bold text-slate-700">
                        <svg class="w-4 h-4 text-tema" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        Pilih Nama Siswa
                    </label>
                    <select name="siswa_id" id="selectSiswa" required class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl focus:ring-4 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none transition-all shadow-sm text-slate-700 font-medium cursor-pointer">
                        <option value="">-- Ketik / Cari Siswa --</option>
                        <?php foreach($siswa_kelas as $s): ?>
                            <option value="<?= $s['id'] ?>"><?= esc($s['nama_lengkap']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="space-y-2">
                    <label class="flex items-center gap-2 text-sm font-bold text-slate-700">
                        <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                        Kategori Permasalahan
                    </label>
                    <select name="kategori" required class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl focus:ring-4 focus:ring-amber-500/20 focus:border-amber-500 outline-none transition-all shadow-sm text-slate-700 font-medium cursor-pointer">
                        <option value="Akademik">Teguran Akademik (Nilai Merosot/Tugas)</option>
                        <option value="Karakter">Karakter / Kedisiplinan</option>
                        <option value="Tahfidz">Progres Tahfidz Terhambat</option>
                        <option value="Absensi">Masalah Kehadiran / Sering Alpha</option>
                    </select>
                </div>

                <div class="space-y-2">
                    <label class="flex items-center gap-2 text-sm font-bold text-slate-700">
                        <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/></svg>
                        Deskripsi Kejadian
                    </label>
                    <textarea name="catatan" required rows="3" class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all shadow-sm text-slate-700 resize-none placeholder:text-slate-400" placeholder="Ceritakan secara detail namun padat..."></textarea>
                </div>

                <div class="space-y-2">
                    <label class="flex items-center gap-2 text-sm font-bold text-slate-700">
                        <svg class="w-4 h-4 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Tindak Lanjut (Opsional)
                    </label>
                    <textarea name="tindak_lanjut" rows="2" class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl focus:ring-4 focus:ring-purple-500/20 focus:border-purple-500 outline-none transition-all shadow-sm text-slate-700 resize-none placeholder:text-slate-400" placeholder="Misal: Memanggil orang tua, diberi teguran lisan..."></textarea>
                </div>
            </div>
            
            <div class="p-5 border-t border-slate-100 bg-white flex justify-end gap-3 flex-shrink-0 rounded-b-3xl">
                <button type="button" onclick="closeNoteModal()" class="px-6 py-2.5 text-slate-600 bg-slate-100 rounded-xl hover:bg-slate-200 font-bold transition-all">Batalkan</button>
                <button type="submit" class="px-6 py-2.5 bg-tema text-white rounded-xl hover-bg-tema font-bold flex items-center gap-2 shadow-lg shadow-[var(--warna-primary)]/30 transition-all transform hover:-translate-y-0.5">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Simpan Catatan
                </button>
            </div>
        </form>
    </div>
</div>

<div id="allStudentsModal" class="fixed inset-0 z-[60] hidden flex items-center justify-center p-4">
    <div id="listBackdrop" class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm opacity-0 transition-opacity duration-300" onclick="closeListModal()"></div>
    
    <div id="listContent" class="relative bg-white rounded-3xl shadow-2xl flex flex-col overflow-hidden w-full max-w-2xl z-10 scale-95 opacity-0 transition-all duration-300 max-h-[85vh]">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between bg-slate-50/80">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-tema-light text-tema flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                </div>
                <div>
                    <h3 class="font-bold text-lg text-slate-800">Daftar Siswa Kelas <?= esc($rombel['nama_rombel']) ?></h3>
                    <p class="text-xs text-slate-500 font-medium">Pilih siswa untuk mencatat pembinaan</p>
                </div>
            </div>
            <button onclick="closeListModal()" class="p-2 text-slate-400 hover:bg-slate-200 hover:text-slate-600 rounded-xl transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        
        <div class="p-5 flex-1 overflow-y-auto bg-white custom-scrollbar">
            <div class="relative mb-5 sticky top-0 z-10 bg-white pb-2">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none pb-2">
                    <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
                <input type="text" id="searchSiswa" onkeyup="filterSiswa()" placeholder="Ketik nama siswa untuk mencari..." class="w-full pl-10 pr-4 py-3 border border-slate-200 rounded-xl text-sm focus:ring-4 focus:ring-tema/20 focus:border-tema outline-none transition-all shadow-sm font-medium">
            </div>
            
            <div class="space-y-2" id="daftarSiswaContainer">
                <?php foreach($siswa_kelas as $index => $sk): ?>
                    <div class="siswa-item p-3 border border-slate-100 rounded-xl hover:bg-slate-50 hover:border-slate-200 flex items-center justify-between transition-all group">
                        <div class="flex items-center gap-3">
                            <span class="w-10 h-10 rounded-full bg-slate-100 text-slate-600 flex items-center justify-center font-bold text-sm border border-slate-200 group-hover:bg-tema-light group-hover:text-tema group-hover:border-tema/30 transition-colors">
                                <?= substr($sk['nama_lengkap'], 0, 2) ?>
                            </span>
                            <div>
                                <p class="font-bold text-slate-800 text-sm nama-siswa"><?= esc($sk['nama_lengkap']) ?></p>
                                <p class="text-[11px] text-slate-500 font-medium">NIS: <?= esc($sk['nis']) ?></p>
                            </div>
                        </div>
                        <button onclick="pilihSiswaDariDaftar('<?= $sk['id'] ?>')" class="px-4 py-2 bg-white border border-slate-200 text-slate-600 text-xs font-bold rounded-xl hover:border-tema hover:bg-tema hover:text-white hover:shadow-lg hover:shadow-[var(--warna-primary)]/20 transition-all flex items-center gap-1.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                            Pilih
                        </button>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

<?php endif; ?>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
  <script src="<?= base_url('assets/js/WaliKelas/perlu-pembinaan.js') ?>"></script>
<?= $this->endSection() ?>