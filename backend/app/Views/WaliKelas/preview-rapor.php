<?= $this->extend('layout/main') ?>

<?= $this->section('title') ?>
  Cetak Rapor Digital - SMPIT Ad Durrah
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
    :root { 
        --warna-primary: <?= $color['warna_primary'] ?>; 
        --warna-secondary: <?= $color['warna_secondary'] ?>;
        --warna-scroll: <?= $color['warna_primary'] ?>; 
    }
    .text-tema { color: var(--warna-primary) !important; }
    .bg-tema { background-color: var(--warna-primary) !important; }
    .border-tema { border-color: var(--warna-primary) !important; }
    
    .radio-custom:checked { background-color: var(--warna-primary); border-color: var(--warna-primary); }
    .checkbox-custom:checked { background-color: var(--warna-primary); border-color: var(--warna-primary); }
    .focus-tema:focus { border-color: var(--warna-primary) !important; box-shadow: 0 0 0 3px color-mix(in srgb, var(--warna-primary) 20%, transparent) !important; outline: none; }

    html.dark .text-tema { color: color-mix(in srgb, var(--warna-primary) 80%, white) !important; }
    html.dark .bg-white { background-color: #1e293b !important; border-color: #334155 !important; }
    html.dark .text-gray-900 { color: #ffffff !important; }
    html.dark .text-gray-800 { color: #f1f5f9 !important; }
    html.dark .text-gray-600 { color: #94a3b8 !important; }
    html.dark .bg-gray-50 { background-color: #0f172a !important; }
    html.dark .bg-gray-100 { background-color: #1e293b !important; }
    html.dark .border-gray-100, html.dark .border-gray-200, html.dark .border-gray-300 { border-color: #334155 !important; }
    html.dark .custom-scrollbar::-webkit-scrollbar-thumb { background-color: var(--warna-primary); }

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

    html::-webkit-scrollbar, body::-webkit-scrollbar {
      display: none;
    }
    
    html, body {
      scrollbar-width: none; 
      -ms-overflow-style: none;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="mb-6 no-print animate-fade-in">
    <div class="flex items-center gap-2 text-sm text-gray-500 dark:text-slate-400 mb-3 transition-colors">
        <span>Dashboard</span>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
        <span class="text-tema font-bold">Cetak Rapor</span>
    </div>
    
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl md:text-3xl font-black text-gray-900 dark:text-white mb-2 flex items-center gap-3 transition-colors tracking-tight">
                <div class="p-2 bg-tema-light dark:bg-slate-800 rounded-xl border border-tema/20">
                    <svg class="w-7 h-7 text-tema" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" /></svg> 
                </div>
                Cetak Rapor Kelasku
            </h1>
            <p class="text-sm text-gray-600 dark:text-slate-400 font-medium ml-1">Manajemen pencetakan rapor untuk kelas yang Anda walikan.</p>
        </div> 
        <div class="flex flex-wrap items-center gap-3">
            <?= csrf_field() ?>
            <button onclick="checkAndOpenAction('preview')" class="bg-tema text-white transition-all transform hover:-translate-y-1 hover:shadow-lg shadow-[var(--warna-primary)]/40 flex items-center gap-2 px-6 py-3 rounded-2xl font-bold outline-none" id="btnCetakGlobal">
                <svg class="w-5 h-5 " fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                <span>Lihat & Cetak Rapor</span> 
            </button> 
            <button onclick="checkAndOpenAction('download')" class="bg-white dark:bg-slate-800 border-2 border-gray-200 dark:border-slate-600 text-gray-700 dark:text-slate-300 hover:bg-gray-50 dark:hover:bg-slate-700 transition-colors flex items-center gap-2 px-6 py-3 rounded-2xl font-bold shadow-sm outline-none" id="btnDownloadPDF">
                <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" /></svg>
                <span>Unduh PDF</span> 
            </button>
        </div>
    </div>

    <?php if (!$rombel_id): ?>
    <div class="bg-rose-50 border border-rose-200 text-rose-700 p-5 rounded-2xl mb-6 shadow-sm flex items-start gap-4">
        <svg class="w-6 h-6 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
        <div>
            <h4 class="font-bold text-base">Akses Terbatas: Anda Belum Memiliki Kelas!</h4>
            <p class="text-sm mt-1 leading-relaxed">Sistem mendeteksi bahwa akun Anda belum ditugaskan sebagai <b>Wali Kelas</b> di Rombongan Belajar manapun. Hubungi Admin jika ini adalah kesalahan.</p>
        </div>
    </div>
    <?php endif; ?>

    <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800/50 p-5 rounded-2xl flex items-start gap-4 shadow-sm transition-colors mb-6">
        <div class="w-10 h-10 rounded-xl bg-blue-100 dark:bg-blue-900/40 flex items-center justify-center flex-shrink-0">
            <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
        </div>
        <div class="flex-1 mt-0.5">
            <h4 class="font-bold text-blue-900 dark:text-blue-400 mb-1 tracking-wide">Pilih Siswa & Tentukan Jenis Rapor</h4>
            <p class="text-sm font-medium text-blue-800 dark:text-blue-300/80 leading-relaxed">Pilih siswa yang akan dicetak rapornya. Anda bisa menentukan tipe laporan (Lengkap/Akademik/Karakter) dan mengklik "Lihat & Cetak Rapor" atau memilih opsi simulasi halaman di bagian bawah.</p>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6 no-print w-full min-w-0">
    <div class="lg:col-span-2 space-y-6">
        
        <div class="bg-white dark:bg-slate-800 rounded-3xl border border-gray-100 dark:border-slate-700 shadow-sm p-6 transition-colors">
            <h3 class="text-lg font-black text-gray-900 dark:text-white mb-5 flex items-center gap-2 border-b border-gray-100 dark:border-slate-700 pb-3">
                <svg class="w-5 h-5 text-tema" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" /></svg> 
                Pengaturan Rapor
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-6">
                <div>
                    <label class="block text-[11px] font-bold text-gray-500 dark:text-slate-400 uppercase tracking-widest mb-2">Kelas Anda</label> 
                    <div class="w-full px-4 py-3.5 bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-700 text-gray-800 dark:text-slate-200 font-black rounded-xl flex items-center justify-between cursor-not-allowed select-none border-l-4 border-l-tema">
                        <span class="truncate">Kelas <?= esc($tingkat) ?> - <?= esc($rombel_name) ?></span>
                        <svg class="w-4 h-4 text-tema flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2-2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                    </div>
                </div>

                <div>
                    <label class="block text-[11px] font-bold text-gray-500 dark:text-slate-400 uppercase tracking-widest mb-2">Tahun Ajaran / Semester</label> 
                    <select id="selectTA" class="w-full px-4 py-3.5 bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-700 text-gray-900 dark:text-white font-black rounded-xl focus-tema transition-all cursor-pointer shadow-sm outline-none border-l-4 border-l-emerald-500" onchange="window.location.href = window.location.pathname + '?ta=' + this.value">
                        <?php foreach($list_ta as $ta): ?>
                            <?php 
                                $fYear = isset($ta['tahun']) ? 'tahun' : 'tahun_ajaran';
                                $valText = $ta[$fYear] . ' - ' . $ta['semester'];
                                $isSelected = ($ta[$fYear] == $tahun_ajaran && $ta['semester'] == $semester) ? 'selected' : '';
                            ?>
                            <option value="<?= $ta['id'] ?>" <?= $isSelected ?>><?= $valText ?> <?= ($ta['status'] == 'Aktif') ? '(Aktif)' : '' ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div>
                    <label class="block text-[11px] font-bold text-gray-500 dark:text-slate-400 uppercase tracking-widest mb-2">Kategori Rapor</label> 
                    <select id="filterKategori" class="w-full px-4 py-3.5 bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-700 text-gray-900 dark:text-white font-black rounded-xl focus-tema transition-all cursor-pointer shadow-sm outline-none border-l-4 border-l-blue-500">
                        <option value="Akhir Semester">Akhir Semester (PAS / PAT)</option>
                        <option value="Tengah Semester">Tengah Semester (PTS / STS)</option>
                    </select>
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-6 pb-6 border-b border-gray-100 dark:border-slate-700">
                <div>
                    <label class="block text-xs font-black text-gray-800 dark:text-slate-200 uppercase tracking-widest mb-3 flex items-center gap-2">
                        <svg class="w-4 h-4 text-tema" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        Pilih Siswa
                    </label> 
                    <select id="filterSiswa" class="w-full px-5 py-4 bg-white dark:bg-slate-800 border-2 border-gray-200 dark:border-slate-600 text-gray-800 dark:text-white font-bold rounded-2xl focus-tema transition-all cursor-pointer shadow-sm"> 
                        <option value="">-- Memuat Data Siswa... --</option> 
                    </select>
                </div>
                <div class="hidden">
                    <input type="hidden" id="tempatRapor" value="<?= esc($tempat_rapor) ?>">
                    <input type="hidden" id="tglRapor" value="<?= esc($tanggal_rapor) ?>">
                </div>
            </div>

            <h4 class="font-black text-gray-800 dark:text-white mb-4 text-sm transition-colors uppercase tracking-widest">Tipe Rapor</h4>
            <div class="grid grid-cols-1 sm:grid-cols-4 gap-4 mb-6 border-b border-gray-100 dark:border-slate-700 pb-6 transition-colors">
                <label class="flex flex-col items-center gap-2 p-5 border-2 border-gray-200 dark:border-slate-600 rounded-2xl cursor-pointer bg-white dark:bg-slate-800 hover:border-tema dark:hover:border-tema transition-all shadow-sm group"> 
                    <input type="radio" name="jenisRapor" value="lengkap" class="flex-shrink-0 outline-none accent-tema m-0" style="width: 20px; height: 20px;" checked>
                    <div class="text-center mt-2">
                        <p class="font-black text-sm text-gray-900 dark:text-white group-hover:text-tema transition-colors">Rapor Lengkap</p>
                        <p class="text-[10px] font-medium text-gray-500 dark:text-slate-400 mt-1.5 leading-relaxed">Gabungan akademik & karakter</p>
                    </div>
                </label> 
                <label class="flex flex-col items-center gap-2 p-5 border-2 border-gray-200 dark:border-slate-600 rounded-2xl cursor-pointer bg-white dark:bg-slate-800 hover:border-tema dark:hover:border-tema transition-all shadow-sm group"> 
                    <input type="radio" name="jenisRapor" value="akademik" class="flex-shrink-0 outline-none accent-tema m-0" style="width: 20px; height: 20px;">
                    <div class="text-center mt-2">
                        <p class="font-black text-sm text-gray-900 dark:text-white group-hover:text-tema transition-colors">Akademik Saja</p>
                        <p class="text-[10px] font-medium text-gray-500 dark:text-slate-400 mt-1.5 leading-relaxed">Hanya nilai mata pelajaran</p>
                    </div>
                </label> 
                <label class="flex flex-col items-center gap-2 p-5 border-2 border-gray-200 dark:border-slate-600 rounded-2xl cursor-pointer bg-white dark:bg-slate-800 hover:border-tema dark:hover:border-tema transition-all shadow-sm group"> 
                    <input type="radio" name="jenisRapor" value="karakter" class="flex-shrink-0 outline-none accent-tema m-0" style="width: 20px; height: 20px;">
                    <div class="text-center mt-2">
                        <p class="font-black text-sm text-gray-900 dark:text-white group-hover:text-tema transition-colors">Karakter & Ekskul</p>
                        <p class="text-[10px] font-medium text-gray-500 dark:text-slate-400 mt-1.5 leading-relaxed">Sikap, ekskul & absensi</p>
                    </div>
                </label>
                </label>
            </div>

            <h4 class="font-black text-gray-800 dark:text-white mb-4 text-sm transition-colors uppercase tracking-widest">Pilihan Tambahan</h4>
            <div class="flex flex-wrap gap-6">
                <label class="flex items-center gap-3 cursor-pointer group"> 
                    <input type="checkbox" id="checkCover" class="flex-shrink-0 rounded outline-none accent-tema m-0" style="width: 20px; height: 20px;" checked> 
                    <span class="text-sm font-bold text-gray-700 dark:text-slate-300 group-hover:text-tema transition-colors">Cetak Halaman Cover Depan</span> 
                </label> 
                <label class="flex items-center gap-3 cursor-pointer group"> 
                    <input type="checkbox" id="checkTTD" class="flex-shrink-0 rounded outline-none accent-tema m-0" style="width: 20px; height: 20px;" checked> 
                    <span class="text-sm font-bold text-gray-700 dark:text-slate-300 group-hover:text-tema transition-colors">Tampilkan Tanda Tangan</span> 
                </label>
                <label class="flex items-center gap-3 cursor-pointer group"> 
                    <input type="checkbox" id="checkQR" class="flex-shrink-0 rounded outline-none accent-tema m-0" style="width: 20px; height: 20px;" checked> 
                    <span class="text-sm font-bold text-gray-700 dark:text-slate-300 group-hover:text-tema transition-colors">Tampilkan Barcode Validasi Kepala Sekolah</span> 
                </label>
            </div>
        </div>
    </div>

    <div class="space-y-6">
        <div class="bg-white dark:bg-slate-800 rounded-3xl border border-gray-100 dark:border-slate-700 shadow-sm p-6 transition-colors">
            <h3 class="font-black text-gray-900 dark:text-white mb-5 flex items-center gap-2 border-b border-gray-100 dark:border-slate-700 pb-3">
                <svg class="w-5 h-5 text-tema" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg> 
                Status Validasi
            </h3>
            <div class="space-y-4">
                <div class="flex justify-between items-center bg-gray-50 dark:bg-slate-900 p-4 rounded-xl border border-gray-100 dark:border-slate-700/50">
                    <span class="text-[10px] font-bold text-gray-500 dark:text-slate-400 uppercase tracking-widest">Status Data</span> 
                    <span class="text-[10px] font-black text-emerald-600 bg-emerald-100 px-2 py-0.5 rounded-md uppercase tracking-widest">Siap Dicetak</span>
                </div>
                <div class="flex justify-between items-center bg-gray-50 dark:bg-slate-900 p-4 rounded-xl border border-gray-100 dark:border-slate-700/50">
                    <span class="text-[10px] font-bold text-gray-500 dark:text-slate-400 uppercase tracking-widest">Wali Kelas</span> 
                    <span class="text-xs font-black text-gray-900 dark:text-white truncate max-w-[120px]" title="<?= esc($wali_kelas) ?>"><?= esc($wali_kelas) ?></span>
                </div>
            </div>
            
            <div class="mt-5 flex items-center justify-center">
                <div class="flex flex-col items-center justify-center border-2 border-dashed border-gray-300 dark:border-slate-600 bg-gray-50 dark:bg-slate-900 p-6 rounded-2xl w-full text-center">
                    <svg class="w-12 h-12 text-gray-300 dark:text-slate-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" /></svg>
                    <span class="text-[10px] font-bold text-gray-400 dark:text-slate-500 uppercase tracking-widest">Barcode QR Aktif</span>
                </div>
            </div>
        </div>

        <!-- Tanda Tangan Digital Wali Kelas -->
        <div class="bg-white dark:bg-slate-800 rounded-3xl border border-gray-100 dark:border-slate-700 shadow-sm p-6 transition-colors">
            <h3 class="font-black text-gray-900 dark:text-white mb-4 flex items-center gap-2 border-b border-gray-100 dark:border-slate-700 pb-3 transition-colors">
                <svg class="w-5 h-5 text-tema" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
                Tanda Tangan Digital Wali Kelas
            </h3>
            
            <div class="mb-4">
                <div id="ttd_container" class="<?= (!empty($guru['ttd_digital']) && file_exists(FCPATH . 'assets/uploads/ttd/' . $guru['ttd_digital'])) ? '' : 'hidden' ?> relative group text-center">
                    <img id="ttd_preview" src="<?= !empty($guru['ttd_digital']) ? base_url('assets/uploads/ttd/' . $guru['ttd_digital']) : '#' ?>" class="max-h-32 mx-auto rounded-xl border border-gray-200 dark:border-slate-600 p-2 bg-white shadow-sm transition-all group-hover:scale-105">
                    <div class="mt-3">
                        <span class="text-[10px] font-black text-tema bg-tema/10 px-3 py-1 rounded-full uppercase tracking-widest border border-tema/20">Tanda Tangan Aktif</span>
                    </div>
                </div>
                <div id="ttd_placeholder" class="<?= (!empty($guru['ttd_digital']) && file_exists(FCPATH . 'assets/uploads/ttd/' . $guru['ttd_digital'])) ? 'hidden' : '' ?> flex flex-col items-center justify-center border-2 border-dashed border-gray-200 dark:border-slate-700 bg-gray-50 dark:bg-slate-900/30 p-8 rounded-2xl text-center">
                    <svg class="w-12 h-12 text-gray-300 dark:text-slate-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
                    <p class="text-[11px] font-black text-gray-400 uppercase tracking-widest leading-loose">Anda Belum Mengupload<br>Tanda Tangan Digital</p>
                </div>
            </div>

            <input type="file" id="inputTtd" accept="image/*" class="hidden" onchange="uploadTtdWali(this)">
            <button onclick="document.getElementById('inputTtd').click()" class="w-full bg-tema hover:brightness-110 text-white font-black py-4 rounded-2xl flex items-center justify-center gap-2 transition-all shadow-lg active:scale-95 shadow-[var(--warna-primary)]/30">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" /></svg>
                <span>Upload Tanda Tangan Saya</span>
            </button>
            <p class="mt-4 text-[10px] text-gray-500 dark:text-slate-400 leading-relaxed text-center font-medium italic bg-gray-100 dark:bg-slate-900/50 p-2 rounded-lg">Gunakan gambar transparan (.PNG) agar hasil cetak rapor terlihat lebih rapi dan profesional.</p>
        </div>

        <div class="bg-gradient-to-br from-indigo-600 to-blue-800 rounded-3xl shadow-xl p-6 text-white relative overflow-hidden">
            <div class="absolute -right-6 -bottom-6 opacity-10">
                <svg class="w-32 h-32" fill="currentColor" viewBox="0 0 24 24"><path d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" /></svg>
            </div>
            <h3 class="font-black mb-2 text-lg flex items-center gap-2 relative z-10">Cetak Keseluruhan (Massal)</h3>
            <p class="text-xs font-medium text-blue-100 mb-5 leading-relaxed relative z-10">Unduh langsung seluruh rapor siswa kelas ini sekaligus ke dalam 1 file .ZIP.</p>
            <button onclick="batchPrint()" class="w-full bg-white text-indigo-700 hover:bg-indigo-50 font-black py-3.5 rounded-xl flex items-center justify-center gap-2 transition-all shadow-lg hover:shadow-xl hover:-translate-y-1 relative z-10">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" /></svg>
                <span>Mulai Cetak Massal</span> 
            </button>
        </div>
    </div>
</div>

<div class="no-print mb-12">
    <div class="bg-white dark:bg-slate-800 rounded-3xl border border-gray-100 dark:border-slate-700 shadow-sm p-6 md:p-8 transition-colors">
        <h3 class="text-xl font-black text-gray-900 dark:text-white mb-6 flex items-center gap-2 border-b border-gray-100 dark:border-slate-700 pb-4">
            <svg class="w-6 h-6 text-tema" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" /></svg> 
            Simulasi Halaman Rapor (Pilih Siswa Terlebih Dahulu)
        </h3>
        
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-5 items-end">
            
            <div class="group cursor-pointer" onclick="showPreview(1)">
                <div class="w-full bg-gradient-to-br from-tema to-teal-700 rounded-xl flex items-center justify-center text-white shadow-md border-2 border-transparent group-hover:border-tema group-hover:shadow-[var(--warna-primary)]/40 transition-all duration-500 transform group-hover:-translate-y-1.5" style="aspect-ratio: 1 / 1.414;">
                    <div class="text-center">
                        <svg class="w-8 h-8 mx-auto mb-2 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477-4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
                        <p class="text-[10px] font-black uppercase tracking-widest drop-shadow-md">COVER</p>
                    </div>
                </div>
                <div class="text-center mt-3"><span class="inline-block bg-gray-100 dark:bg-slate-900 text-gray-500 dark:text-slate-400 font-bold text-[10px] px-3 py-1 rounded-md group-hover:bg-tema group-hover:text-white transition-colors">Hal 1: Cover</span></div>
            </div>

            <div class="group cursor-pointer" onclick="showPreview(2)">
                <div class="w-full bg-white dark:bg-slate-700 p-3 flex flex-col gap-2 rounded-xl shadow-md border-2 border-gray-200 dark:border-slate-600 group-hover:border-tema group-hover:shadow-[var(--warna-primary)]/40 transition-all duration-500 transform group-hover:-translate-y-1.5" style="aspect-ratio: 1 / 1.414;">
                    <div class="w-full h-3 bg-gray-200 dark:bg-slate-500 rounded-sm"></div>
                    <div class="space-y-1 mt-1">
                        <div class="h-1 bg-gray-200 dark:bg-slate-600 w-3/4 rounded-sm"></div>
                        <div class="h-1 bg-gray-200 dark:bg-slate-600 w-1/2 rounded-sm"></div>
                    </div>
                    <div class="mt-3 w-full h-3 bg-gray-200 dark:bg-slate-500 rounded-sm"></div>
                    <div class="space-y-1 mt-1">
                        <div class="h-1 bg-gray-200 dark:bg-slate-600 w-full rounded-sm"></div>
                        <div class="h-1 bg-gray-200 dark:bg-slate-600 w-2/3 rounded-sm"></div>
                    </div>
                </div>
                <div class="text-center mt-3"><span class="inline-block bg-gray-100 dark:bg-slate-900 text-gray-500 dark:text-slate-400 font-bold text-[10px] px-3 py-1 rounded-md group-hover:bg-tema group-hover:text-white transition-colors">Hal 2: Identitas</span></div>
            </div>

            <div class="group cursor-pointer" onclick="showPreview(3)">
                <div class="w-full bg-white dark:bg-slate-700 p-3 rounded-xl shadow-md border-2 border-gray-200 dark:border-slate-600 group-hover:border-tema group-hover:shadow-[var(--warna-primary)]/40 transition-all duration-500 transform group-hover:-translate-y-1.5" style="aspect-ratio: 1 / 1.414;">
                    <div class="w-1/2 h-2 bg-tema/50 mb-3 rounded-sm"></div>
                    <table class="w-full opacity-30 text-[3px] border-collapse border border-gray-400 dark:border-slate-500">
                        <tr class="bg-gray-200 dark:bg-slate-500"><td class="border border-gray-400 p-1"></td><td class="border border-gray-400 p-1"></td><td class="border border-gray-400 p-1"></td></tr>
                        <tr><td class="border border-gray-400 p-1"></td><td class="border border-gray-400 p-1"></td><td class="border border-gray-400 p-1"></td></tr>
                        <tr><td class="border border-gray-400 p-1"></td><td class="border border-gray-400 p-1"></td><td class="border border-gray-400 p-1"></td></tr>
                        <tr><td class="border border-gray-400 p-1"></td><td class="border border-gray-400 p-1"></td><td class="border border-gray-400 p-1"></td></tr>
                        <tr><td class="border border-gray-400 p-1"></td><td class="border border-gray-400 p-1"></td><td class="border border-gray-400 p-1"></td></tr>
                    </table>
                </div>
                <div class="text-center mt-3"><span class="inline-block bg-gray-100 dark:bg-slate-900 text-gray-500 dark:text-slate-400 font-bold text-[10px] px-3 py-1 rounded-md group-hover:bg-tema group-hover:text-white transition-colors">Hal 3: Akademik</span></div>
            </div>

            <div class="group cursor-pointer" onclick="showPreview(4)">
                <div class="w-full bg-white dark:bg-slate-700 p-3 rounded-xl shadow-md border-2 border-gray-200 dark:border-slate-600 group-hover:border-tema group-hover:shadow-[var(--warna-primary)]/40 transition-all duration-500 transform group-hover:-translate-y-1.5" style="aspect-ratio: 1 / 1.414;">
                    <div class="w-2/3 h-2 bg-blue-200 dark:bg-blue-400/50 mb-3 rounded-sm"></div>
                    <div class="border border-gray-300 dark:border-slate-500 h-8 mb-3 rounded-sm bg-gray-100 dark:bg-slate-600"></div>
                    <div class="w-2/3 h-2 bg-blue-200 dark:bg-blue-400/50 mb-3 rounded-sm"></div>
                    <div class="border border-gray-300 dark:border-slate-500 h-8 rounded-sm bg-gray-100 dark:bg-slate-600"></div>
                </div>
                <div class="text-center mt-3"><span class="inline-block bg-gray-100 dark:bg-slate-900 text-gray-500 dark:text-slate-400 font-bold text-[10px] px-3 py-1 rounded-md group-hover:bg-tema group-hover:text-white transition-colors">Hal 4: Ekstrakurikuler</span></div>
            </div>

            <div class="group cursor-pointer" onclick="showPreview(5)">
                <div class="w-full bg-white dark:bg-slate-700 p-3 flex flex-col justify-center rounded-xl shadow-md border-2 border-gray-200 dark:border-slate-600 group-hover:border-tema group-hover:shadow-[var(--warna-primary)]/40 transition-all duration-500 transform group-hover:-translate-y-1.5" style="aspect-ratio: 1 / 1.414;">
                    <div class="bg-amber-100/50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800/50 p-1.5 rounded h-12 mb-2">
                        <div class="h-1 bg-amber-300 dark:bg-amber-600 w-1/2 mb-1.5 rounded-sm"></div>
                        <div class="h-0.5 bg-gray-300 dark:bg-slate-500 w-full mb-1"></div>
                        <div class="h-0.5 bg-gray-300 dark:bg-slate-500 w-full"></div>
                    </div>
                    <div class="grid grid-cols-3 gap-1 text-[4px] font-bold text-center mt-auto">
                        <div class="border border-gray-300 dark:border-slate-500 p-1 bg-gray-100 dark:bg-slate-600 text-gray-400">S</div>
                        <div class="border border-gray-300 dark:border-slate-500 p-1 bg-gray-100 dark:bg-slate-600 text-gray-400">I</div>
                        <div class="border border-gray-300 dark:border-slate-500 p-1 bg-gray-100 dark:bg-slate-600 text-gray-400">A</div>
                    </div>
                </div>
                <div class="text-center mt-3"><span class="inline-block bg-gray-100 dark:bg-slate-900 text-gray-500 dark:text-slate-400 font-bold text-[10px] px-3 py-1 rounded-md group-hover:bg-tema group-hover:text-white transition-colors">Hal 5: Absensi</span></div>
            </div>

            <div class="group cursor-pointer" onclick="showPreview(6)">
                <div class="w-full bg-white dark:bg-slate-700 p-3 rounded-xl shadow-md border-2 border-gray-200 dark:border-slate-600 group-hover:border-tema group-hover:shadow-[var(--warna-primary)]/40 transition-all duration-500 transform group-hover:-translate-y-1.5" style="aspect-ratio: 1 / 1.414;">
                    <div class="w-1/2 h-2 bg-purple-200 dark:bg-purple-500/50 mb-4 rounded-sm"></div>
                    <div class="space-y-2">
                        <div class="flex items-center gap-1.5"><div class="w-3 h-3 bg-gray-200 dark:bg-slate-500 rounded-full"></div><div class="flex-1 h-2 bg-gray-100 dark:bg-slate-600 rounded-sm"></div></div>
                        <div class="flex items-center gap-1.5"><div class="w-3 h-3 bg-gray-200 dark:bg-slate-500 rounded-full"></div><div class="flex-1 h-2 bg-gray-100 dark:bg-slate-600 rounded-sm"></div></div>
                        <div class="flex items-center gap-1.5"><div class="w-3 h-3 bg-gray-200 dark:bg-slate-500 rounded-full"></div><div class="flex-1 h-2 bg-gray-100 dark:bg-slate-600 rounded-sm"></div></div>
                    </div>
                </div>
                <div class="text-center mt-3"><span class="inline-block bg-gray-100 dark:bg-slate-900 text-gray-500 dark:text-slate-400 font-bold text-[10px] px-3 py-1 rounded-md group-hover:bg-tema group-hover:text-white transition-colors">Hal 6: Karakter</span></div>
            </div>

        </div>
    </div>
</div>

        </div>
    </div>
</div>

<!-- MODAL INPUT CATATAN RAPOR -->
<div id="modalInputCatatan" class="fixed inset-0 z-[100001] hidden flex items-center justify-center p-4 transition-opacity no-print">
    <div class="modal-overlay absolute inset-0 bg-slate-900/80 backdrop-blur-sm" onclick="closeInputModal()"></div>
    <div class="relative bg-white dark:bg-slate-900 rounded-3xl shadow-2xl w-full max-w-2xl mx-auto overflow-hidden flex flex-col transform scale-95 transition-all duration-300" id="modalInputContent">
        
        <div class="bg-white dark:bg-slate-800 px-6 py-5 flex justify-between items-center border-b border-gray-100 dark:border-slate-700">
            <div class="flex items-center gap-3">
                <div class="p-2.5 bg-tema/10 rounded-xl">
                    <svg class="w-6 h-6 text-tema" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                </div>
                <div>
                    <h3 class="font-black text-lg text-gray-800 dark:text-white uppercase tracking-tight">Input Catatan Rapor</h3>
                    <p class="text-[10px] font-bold text-gray-400 dark:text-slate-500 uppercase tracking-widest mt-0.5" id="inputSiswaName">Nama Siswa</p>
                </div>
            </div>
            <button onclick="closeInputModal()" class="text-gray-400 hover:bg-gray-100 dark:hover:bg-slate-700 p-2 rounded-xl transition-colors outline-none">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <div class="p-6 space-y-5 max-h-[70vh] overflow-y-auto custom-scrollbar bg-gray-50/50 dark:bg-slate-900/50 text-gray-800 dark:text-gray-200">
            <!-- Hidden Fields -->
            <input type="hidden" id="inputSiswaId">
            <input type="hidden" id="inputActionType">

            <div>
                <label class="block text-[11px] font-black text-gray-500 dark:text-slate-400 uppercase tracking-widest mb-2.5">
                    1. Kata Pengantar / Pesan Wali Kelas
                </label>
                <textarea id="inputPengantar" rows="3" class="w-full px-4 py-3 bg-white dark:bg-slate-800 border-2 border-gray-100 dark:border-slate-700 rounded-2xl focus-tema transition-all placeholder:text-gray-400 text-sm font-medium shadow-sm outline-none" placeholder="Contoh: Alhamdulillah, selamat atas pencapaianmu semester ini..."></textarea>
            </div>

            <div>
                <label class="block text-[11px] font-black text-gray-500 dark:text-slate-400 uppercase tracking-widest mb-2.5">
                    2. Catatan Perkembangan / Kesimpulan
                </label>
                <textarea id="inputCatatan" rows="4" class="w-full px-4 py-3 bg-white dark:bg-slate-800 border-2 border-gray-100 dark:border-slate-700 rounded-2xl focus-tema transition-all placeholder:text-gray-400 text-sm font-medium shadow-sm outline-none" placeholder="Tuliskan catatan detail mengenai perkembangan akademik dan karakter siswa..."></textarea>
            </div>

            <div id="containerKenaikan">
                <label class="block text-[11px] font-black text-gray-500 dark:text-slate-400 uppercase tracking-widest mb-2.5">
                    3. Keputusan Akademik (Naik/Lulus)
                </label>
                <div class="relative">
                    <select id="inputKenaikan" class="w-full px-4 py-3.5 bg-white dark:bg-slate-800 border-2 border-gray-100 dark:border-slate-700 text-gray-900 dark:text-white font-black rounded-2xl focus-tema transition-all cursor-pointer shadow-sm appearance-none outline-none">
                        <option value="">-- Meload Opsi... --</option>
                    </select>
                    <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-gray-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7" /></svg>
                    </div>
                </div>
                <p class="mt-2 text-[10px] text-amber-600 dark:text-amber-400 font-bold bg-amber-50 dark:bg-amber-900/20 p-2 rounded-lg flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    Pilihan ini muncul otomatis berdasarkan tingkat kelas siswa.
                </p>
            </div>
        </div>

        <div class="bg-gray-50 dark:bg-slate-800/50 px-6 py-5 flex items-center justify-between border-t border-gray-100 dark:border-slate-700">
            <button onclick="closeInputModal()" class="px-6 py-3 text-sm font-black text-gray-500 dark:text-slate-400 hover:text-gray-800 dark:hover:text-white transition-colors">
                Batal
            </button>
            <button onclick="saveAndProcess()" class="bg-tema hover:brightness-110 text-white font-black px-8 py-3.5 rounded-2xl flex items-center gap-2 transition-all shadow-lg active:scale-95 shadow-[var(--warna-primary)]/30">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4-4m0 0l-4-4m4 4V4" />
                </svg>
                Simpan & Lanjutkan
            </button>
        </div>
    </div>
</div>

<!-- MODAL PREVIEW PDF RAPOR -->
<div id="modalPreviewKertas" class="fixed inset-0 z-[100002] hidden flex items-center justify-center p-4 transition-opacity no-print">
    <div class="modal-overlay absolute inset-0 bg-slate-900/80 backdrop-blur-sm" onclick="closePreviewKertas()"></div>
    <div class="relative bg-gray-200 dark:bg-slate-900 rounded-3xl shadow-2xl w-full max-w-5xl mx-auto overflow-hidden flex flex-col h-[90vh] transform scale-95 transition-all duration-300" id="modalPreviewContent">

        <!-- Header Modal Preview -->
        <div class="bg-white dark:bg-slate-800 px-6 py-4 flex justify-between items-center border-b border-gray-300 dark:border-slate-700 shadow-sm z-10">
            <div>
                <h3 class="font-black text-lg text-gray-800 dark:text-white flex items-center gap-2">
                    <svg class="w-6 h-6 text-tema" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                    Pratinjau Hasil Rapor
                </h3>
                <p class="text-xs text-gray-500 dark:text-slate-400 mt-0.5 font-bold uppercase tracking-widest" id="previewSiswaName">Memproses...</p>
            </div>

            <div class="flex items-center gap-3">
                <button onclick="printFromIframe()" class="hidden md:flex bg-tema text-white px-4 py-2 rounded-xl font-bold text-sm items-center gap-2 hover:brightness-110 transition-all shadow-md">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" /></svg>
                    Cetak Langsung
                </button>
                <button onclick="closePreviewKertas()" class="text-gray-400 hover:bg-gray-100 dark:hover:bg-slate-700 p-2 rounded-xl transition-colors outline-none border border-gray-200 dark:border-slate-600 bg-white dark:bg-slate-800">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>

        <!-- Body Modal Preview -->
        <div class="flex-1 overflow-hidden bg-gray-200 dark:bg-slate-900 relative flex justify-center w-full h-full">
            <div id="iframeLoader" class="absolute inset-0 flex flex-col items-center justify-center bg-gray-200/80 dark:bg-slate-900/80 backdrop-blur-sm z-20">
                <div class="animate-spin rounded-full h-14 w-14 border-b-4 border-tema mx-auto mb-4"></div>
                <p class="text-gray-600 dark:text-gray-300 font-bold tracking-widest uppercase text-sm">Menyiapkan PDF Rapor...</p>
            </div>
            <div id="iframeContainer" class="w-full h-full shadow-2xl bg-white transition-all duration-500 flex justify-center"></div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    const API_URL = "<?= $api_url ?>";
    const serverStudents = <?= json_encode($students) ?>;

    async function uploadTtdWali(input) {
        if (!input.files || !input.files[0]) return;
        
        const file = input.files[0];
        const formData = new FormData();
        formData.append('ttd', file);
        formData.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');

        Swal.fire({
            title: 'Mengupload...',
            text: 'Sedang memproses tanda tangan digital Anda',
            allowOutsideClick: false,
            didOpen: () => { Swal.showLoading(); }
        });

        try {
            const response = await fetch(`${API_URL}/uploadTtdWali`, {
                method: 'POST',
                body: formData
            });
            const result = await response.json();

            if (result.status === 'success') {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: result.message,
                    timer: 2000,
                    showConfirmButton: false
                });
                
                document.getElementById('ttd_preview').src = result.filename;
                document.getElementById('ttd_container').classList.remove('hidden');
                document.getElementById('ttd_placeholder').classList.add('hidden');
            } else {
                Swal.fire('Gagal!', result.message, 'error');
            }
        } catch (error) {
            console.error(error);
            Swal.fire('Error!', 'Terjadi kesalahan sistem saat mengupload.', 'error');
        } finally {
            input.value = '';
        }
    }
</script>
<script src="<?= base_url('assets/js/WaliKelas/preview-rapor.js') ?>?v=<?= time() ?>"></script>
<?= $this->endSection() ?>