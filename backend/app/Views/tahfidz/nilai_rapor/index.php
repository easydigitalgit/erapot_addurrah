<?= $this->extend('layout/main') ?>

<?= $this->section('title') ?>
  Hasil Nilai Rapor Tahfidz - Rapor Digital
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
    :root { 
        --warna-utama: <?= $color['warna_primary'] ?>;
        --warna-transparan: <?= $color['warna_primary'] ?>15;
        --warna-scroll: <?= $color['warna_primary'] ?>;
    }
    .text-dinamis { color: var(--warna-utama) !important; }
    .bg-dinamis { background-color: var(--warna-utama) !important; }
    .border-dinamis { border-color: var(--warna-utama) !important; }
    
    html.dark .bg-white { background-color: #1e293b !important; border-color: #334155 !important; }
    html.dark .bg-slate-50 { background-color: #0f172a !important; }
    html.dark .text-slate-900 { color: #f8fafc !important; }
    html.dark .text-slate-700 { color: #cbd5e1 !important; }
    html.dark .text-slate-500 { color: #94a3b8 !important; }
    html.dark .border-slate-200, html.dark .border-slate-100 { border-color: #334155 !important; }
    html.dark textarea, html.dark select { background-color: #1e293b !important; color: white !important; border-color: #475569 !important; }

    .custom-table-scroll::-webkit-scrollbar { height: 8px; width: 6px; }
    .custom-table-scroll::-webkit-scrollbar-thumb { background: var(--warna-utama); border-radius: 10px; }

    .dropzone-active {
        border-color: var(--warna-utama) !important;
        background-color: color-mix(in srgb, var(--warna-utama) 10%, transparent) !important;
        transform: scale(1.02);
    }

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
<link rel="stylesheet" href="<?= base_url('assets/css/GuruMapel/akun-saya.css') ?>">
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="mb-10 relative">
    
    <div class="flex items-center gap-2 text-sm text-slate-500 dark:text-slate-400 mb-5 font-medium tracking-wide">
        <span>Tahfidz</span>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
        <span class="font-bold text-dinamis">Hasil Nilai Rapor Akhir</span>
    </div>

    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div>
            <h1 class="text-3xl md:text-4xl font-bold text-slate-900 dark:text-white mb-2 tracking-tight">Hasil Nilai Rapor</h1>
            <p class="text-slate-500 dark:text-slate-400 text-sm md:text-base">Sistem akan menyajikan Indeks Hafalan dan Hasil Teori Per Juz untuk Rapor.</p>
        </div>
        <div class="bg-white dark:bg-slate-800 px-5 py-2.5 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm flex items-center gap-3">
            <div class="w-2.5 h-2.5 rounded-full bg-red-500 animate-pulse shadow-[0_0_8px_rgba(239,68,68,0.6)]"></div>
            <span class="text-sm font-bold text-slate-700 dark:text-slate-300" id="realtimeClock"><?= date('H:i') ?> WIB</span>
        </div>
    </div>

    <div class="bg-white dark:bg-slate-800 rounded-3xl border border-slate-200/60 dark:border-slate-700 shadow-sm p-6 md:p-8 mb-8 relative overflow-hidden group">
        <div class="absolute right-0 top-0 w-40 h-40 bg-dinamis opacity-5 rounded-bl-[100px] transition-transform group-hover:scale-110 duration-700"></div>
        <div class="flex flex-col md:flex-row gap-6 items-end relative z-10">
            
            <div class="w-full md:w-5/12">
                <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-2.5 flex items-center gap-2">
                    <svg class="w-4 h-4 text-dinamis" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    Pilih Rombongan Belajar
                </label>
                <select id="kelasSelect" onchange="loadSiswa()" class="w-full px-5 py-3.5 text-sm font-semibold text-slate-700 dark:text-slate-200 border border-slate-200 dark:border-slate-600 rounded-xl focus:ring-2 focus:ring-dinamis bg-slate-50 dark:bg-slate-900 hover:bg-white dark:hover:bg-slate-700 transition-all cursor-pointer shadow-inner outline-none">
                    <?php if(!empty($rombels)): ?>
                        <option value="">-- Pilih Kelas --</option>
                        <?php foreach($rombels as $r): ?>
                            <option value="<?= $r['id'] ?>" data-name="<?= esc($r['nama_rombel']) ?>">Kelas <?= esc($r['tingkat']) ?> - <?= esc($r['nama_rombel']) ?></option>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <option value="">-- Anda Belum Membina Kelas --</option>
                    <?php endif; ?>
                </select>
            </div>

            <div class="w-full md:w-3/12">
                <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-2.5 flex items-center gap-2">
                    <svg class="w-4 h-4 text-dinamis" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                    Pilih Juz
                </label>
                <select id="juzSelect" onchange="loadSiswa()" class="w-full px-5 py-3.5 text-sm font-semibold text-slate-700 dark:text-slate-200 border border-slate-200 dark:border-slate-600 rounded-xl focus:ring-2 focus:ring-dinamis bg-slate-50 dark:bg-slate-900 hover:bg-white dark:hover:bg-slate-700 transition-all cursor-pointer shadow-inner outline-none">
                    <option value="">-- Pilih Juz --</option>
                    <?php if(!empty($juzList)): ?>
                        <?php foreach($juzList as $j): ?>
                            <option value="<?= $j['id'] ?>"><?= esc($j['nama_juz']) ?></option>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <?php for($i=30; $i>=1; $i--): ?>
                            <option value="<?= $i ?>">Juz <?= $i ?></option>
                        <?php endfor; ?>
                    <?php endif; ?>
                </select>
            </div>
            
            <div class="w-full md:w-4/12">
                <div class="bg-dinamis/5 dark:bg-slate-900 border border-dinamis/20 p-4 rounded-2xl flex items-center justify-between">
                    <div>
                        <p class="text-[9px] font-bold text-dinamis uppercase tracking-widest">Semester Aktif</p>
                        <p class="text-sm font-black text-slate-700 dark:text-white"><?= $ta_info['semester'] ?? '-' ?> (<?= $ta_info['tahun'] ?? '-' ?>)</p>
                    </div>
                    <svg class="w-6 h-6 text-dinamis opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                </div>
            </div>
        </div>
    </div>

    <div id="emptyState" class="bg-white dark:bg-slate-800 rounded-3xl border border-slate-200/60 dark:border-slate-700 shadow-sm p-20 text-center mb-8 transition-all duration-300">
        <div class="w-20 h-20 bg-dinamis/10 rounded-2xl rotate-3 flex items-center justify-center mx-auto mb-6 border border-white dark:border-slate-700 animate-pulse">
            <svg class="w-10 h-10 text-dinamis" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
        </div>
        <h3 class="text-xl font-bold text-slate-800 dark:text-white mb-2 tracking-tight">Tentukan Kelas & Juz Terlebih Dahulu</h3>
        <p class="text-slate-500 dark:text-slate-400 text-sm max-w-md mx-auto">Sistem akan memuat Indeks Hafalan Kumulatif dan Hasil Evaluasi Teori secara bersamaan.</p>
    </div>

    <div class="bg-white dark:bg-slate-800 rounded-3xl border border-slate-200/60 dark:border-slate-700 shadow-xl overflow-hidden mb-10 transition-all duration-500 hidden" id="tableContainer">
        
        <div class="p-6 md:p-8 border-b border-slate-100 dark:border-slate-700 bg-white dark:bg-slate-800 flex flex-col xl:flex-row justify-between items-start xl:items-center gap-6">
            <div class="w-full xl:w-7/12">
                <h3 class="font-extrabold text-slate-900 dark:text-white text-lg flex items-center gap-2 tracking-tight">
                    <svg class="w-6 h-6 text-dinamis" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Lembar Narasi Rapor <span id="infoKelas" class="text-dinamis ml-1"></span>
                </h3>
                <div class="mt-4 bg-slate-50 dark:bg-slate-900/50 p-4 rounded-2xl border border-slate-100 dark:border-slate-700">
                    <div class="flex justify-between items-end mb-2">
                        <span class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest">Progress Pengisian Rapor</span>
                        <span class="text-sm font-black text-dinamis" id="progressText">0% (0/0)</span>
                    </div>
                    <div class="w-full bg-slate-200 dark:bg-slate-700 rounded-full h-2.5 overflow-hidden">
                        <div class="h-2.5 rounded-full bg-dinamis transition-all duration-700 ease-out relative" id="progressBar" style="width: 0%;"></div>
                    </div>
                </div>
            </div>
            
            <div class="w-full xl:w-auto flex flex-col sm:flex-row items-center gap-4 ml-auto">
                <div class="relative w-full sm:w-auto">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                    <input type="text" id="searchInput" onkeyup="cariSantri()" placeholder="Cari santri..." class="w-full pl-10 pr-4 py-3 text-sm border border-slate-200 dark:border-slate-600 rounded-xl focus:ring-2 focus:ring-dinamis bg-slate-50 dark:bg-slate-700 dark:text-white transition-all outline-none">
                </div>
                <button type="button" onclick="openExcelModal()" class="w-full sm:w-auto px-6 py-3.5 bg-gradient-to-r from-blue-500 to-indigo-500 hover:from-blue-600 hover:to-indigo-600 text-white text-sm font-bold rounded-xl flex items-center justify-center gap-2 transition-all shadow-md shadow-blue-500/30 transform hover:-translate-y-0.5">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    Manajemen Excel Rapor
                </button>
            </div>
        </div>
        
        <form id="formNilaiRapor" class="relative">
            <input type="hidden" name="juz" id="formJuzInput"> 

            <div class="max-h-[60vh] overflow-y-auto overflow-x-auto custom-table-scroll bg-slate-50/30 dark:bg-slate-900/20">
                <table class="w-full text-left border-collapse min-w-[1250px]">
                    <thead class="sticky top-0 z-20 backdrop-blur-xl bg-white/95 dark:bg-slate-800/95 shadow-sm">
                        <tr class="text-slate-500 dark:text-slate-400 text-[11px] uppercase tracking-widest border-b border-slate-200 dark:border-slate-700">
                            <th rowspan="2" class="px-5 py-4 font-extrabold w-12 text-center border-r border-slate-200 dark:border-slate-600 align-middle">No</th>
                            <th rowspan="2" class="px-5 py-4 font-extrabold w-64 border-r border-slate-200 dark:border-slate-600 align-middle">Profil Santri</th>
                            <th colspan="4" class="px-5 py-3 font-extrabold text-center border-r border-b border-slate-200 dark:border-slate-600 bg-slate-50 dark:bg-slate-800/80">Indeks Hafalan Kumulatif (Setoran)</th>
                            <th colspan="4" class="px-5 py-3 font-extrabold text-center border-r border-b border-slate-200 dark:border-slate-600 bg-emerald-50/50 dark:bg-emerald-900/10">Hasil Evaluasi Teori</th>
                            <th rowspan="2" class="px-5 py-4 font-extrabold border-r border-slate-200 dark:border-slate-600 align-middle min-w-[350px]">Narasi Capaian (Muncul di Rapor)</th>
                            <th rowspan="2" class="px-5 py-4 font-extrabold border-slate-200 dark:border-slate-600 align-middle w-24 text-center">Aksi</th>
                        </tr>
                        <tr class="text-slate-400 dark:text-slate-400 text-[10px] uppercase tracking-widest border-b border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-800/40">
                            <th class="px-3 py-2 font-bold text-center border-r border-slate-200 dark:border-slate-600 w-16">Angka</th>
                            <th class="px-3 py-2 font-bold text-center border-r border-slate-200 dark:border-slate-600 w-16">Huruf</th>
                            <th class="px-3 py-2 font-bold text-center border-r border-slate-200 dark:border-slate-600 w-28">Derajat</th>
                            <th class="px-3 py-2 font-bold text-center border-r border-slate-200 dark:border-slate-600 w-20">Taqdir</th>
                            
                            <th class="px-3 py-2 font-bold text-center border-r border-slate-200 dark:border-slate-600 w-16">Angka</th>
                            <th class="px-3 py-2 font-bold text-center border-r border-slate-200 dark:border-slate-600 w-16">Huruf</th>
                            <th class="px-3 py-2 font-bold text-center border-r border-slate-200 dark:border-slate-600 w-28">Derajat</th>
                            <th class="px-3 py-2 font-bold text-center border-r border-slate-200 dark:border-slate-600 w-20">Taqdir</th>
                        </tr>
                    </thead>
                    <tbody id="tbodyNilai" class="divide-y divide-slate-100 dark:divide-slate-700 bg-white dark:bg-slate-800">
                    </tbody>
                </table>
            </div>
            
            <div class="p-6 md:p-8 bg-white dark:bg-slate-800 border-t border-slate-200 dark:border-slate-700 flex flex-col md:flex-row justify-between items-center gap-4 shadow-[0_-10px_20px_-5px_rgba(0,0,0,0.03)] relative z-20">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-blue-50 dark:bg-blue-900/30 flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-blue-500 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <p class="text-xs text-slate-500 dark:text-slate-400 font-medium leading-relaxed max-w-md">
                        Sistem telah merangkai predikat & narasi secara otomatis berdasarkan nilai hafalan dan teori. Anda tetap dapat menyesuaikan narasinya.
                    </p>
                </div>
                
                <button type="button" onclick="simpanNilai()" class="w-full md:w-auto py-3.5 px-10 bg-dinamis hover:brightness-110 text-white rounded-xl font-bold flex items-center justify-center gap-3 transition-all shadow-lg transform hover:-translate-y-0.5">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                    Simpan Nilai Rapor
                </button>
            </div>
        </form>
    </div>

</div>
<?= $this->endSection() ?>

<?= $this->section('modals') ?>
<!-- Modal Manajemen Excel Rapor -->
<div id="modalExcel" class="fixed inset-0 z-[100] hidden items-center justify-center p-4">
    <div class="absolute inset-0 bg-slate-900/70 backdrop-blur-sm transition-opacity opacity-0" id="backdropExcel" onclick="closeExcelModal()"></div>
    
    <div class="bg-white dark:bg-slate-800 rounded-[2rem] shadow-2xl w-full max-w-2xl relative z-10 transform scale-95 opacity-0 transition-all duration-300 flex flex-col overflow-hidden border border-gray-100 dark:border-slate-700" id="cardExcel">
        
        <div class="px-6 py-5 border-b border-slate-100 dark:border-slate-700 flex justify-between items-center bg-blue-50 dark:bg-blue-900/20">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-blue-500 text-white flex items-center justify-center shadow-md">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                </div>
                <div>
                    <h3 class="font-bold text-slate-800 dark:text-white text-lg leading-tight">Manajemen Excel Rapor</h3>
                    <p class="text-[11px] text-slate-500 dark:text-slate-400 font-medium">Ekspor data per Juz untuk diisi offline & Impor kembali</p>
                </div>
            </div>
            <button onclick="closeExcelModal()" class="w-8 h-8 flex items-center justify-center rounded-full hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-400 hover:text-slate-600 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        
        <div class="p-6 md:p-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                <button onclick="downloadTemplateRapor()" class="p-4 border-2 border-dashed border-indigo-200 dark:border-indigo-800 bg-indigo-50/50 dark:bg-indigo-900/10 rounded-2xl hover:bg-indigo-50 dark:hover:bg-indigo-900/30 transition-all flex flex-col items-center justify-center gap-2 group">
                    <div class="w-12 h-12 bg-indigo-100 dark:bg-indigo-900/50 text-indigo-500 rounded-full flex items-center justify-center group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                    </div>
                    <span class="font-bold text-indigo-700 dark:text-indigo-400 text-sm">Unduh Template Rapor</span>
                </button>
                
                <button onclick="exportDataRapor()" class="p-4 border-2 border-dashed border-blue-200 dark:border-blue-800 bg-blue-50/50 dark:bg-blue-900/10 rounded-2xl hover:bg-blue-50 dark:hover:bg-blue-900/30 transition-all flex flex-col items-center justify-center gap-2 group">
                    <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/50 text-blue-500 rounded-full flex items-center justify-center group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    </div>
                    <span class="font-bold text-blue-700 dark:text-blue-400 text-sm">Ekspor Nilai Rapor Ini</span>
                </button>
            </div>

            <div class="relative">
                <div class="absolute inset-0 flex items-center" aria-hidden="true">
                    <div class="w-full border-t border-gray-200 dark:border-slate-700"></div>
                </div>
                <div class="relative flex justify-center">
                    <span class="px-3 bg-white dark:bg-slate-800 text-xs font-bold text-gray-400 uppercase tracking-widest">ATAU IMPOR FILE EXCEL</span>
                </div>
            </div>

            <div class="mt-6">
                <div id="dropzoneCSV" class="border-2 border-dashed border-gray-300 dark:border-slate-600 rounded-3xl p-8 text-center bg-gray-50 dark:bg-slate-900/50 transition-all duration-300 cursor-pointer hover:border-[<?= $color['warna_primary'] ?>] hover:bg-gray-100 dark:hover:bg-slate-800 relative">
                    <input type="file" id="fileInputCSV" accept=".csv, .xls, .xlsx" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" onchange="handleFileSelect(event)">
                    
                    <div class="w-16 h-16 mx-auto bg-white dark:bg-slate-800 shadow-sm rounded-full flex items-center justify-center mb-4 text-blue-500">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                    </div>
                    <h4 class="text-sm font-bold text-gray-800 dark:text-white mb-1">Tarik & Lepas file Excel Rapor (.xlsx) ke sini</h4>
                    <p class="text-xs text-gray-500 dark:text-slate-400 mb-4">Pastikan Juz yang dipilih di halaman utama sama dengan Juz di file excel.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Preview Rapor PDF -->
<div id="modalPreviewRapor" class="fixed inset-0 z-[110] hidden items-center justify-center p-4">
    <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-md transition-opacity opacity-0" id="backdropPreview" onclick="closePreviewModal()"></div>
    
    <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] shadow-2xl w-full max-w-5xl h-[90vh] relative z-20 transform scale-95 opacity-0 transition-all duration-500 overflow-hidden border border-white/20" id="cardPreview">
        
        <div class="absolute top-6 right-6 z-30">
            <button onclick="closePreviewModal()" class="w-10 h-10 flex items-center justify-center rounded-full bg-white/10 hover:bg-white/20 backdrop-blur-md text-white border border-white/20 transition-all transform hover:rotate-90">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>

        <div class="h-full flex flex-col">
            <div class="px-8 py-6 bg-gradient-to-r from-dinamis to-blue-600 flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-white/20 backdrop-blur-sm flex items-center justify-center text-white shadow-inner">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                </div>
                <div>
                    <h3 class="font-black text-white text-xl tracking-tight leading-none" id="previewStudentName">Pratinjau Rapor</h3>
                    <p class="text-blue-100 text-[10px] font-bold uppercase tracking-widest mt-2 opacity-80" id="previewStudentInfo">Memuat Dokumen PDF...</p>
                </div>
            </div>

            <div class="flex-1 bg-slate-100 dark:bg-slate-950 relative">
                <div id="previewLoader" class="absolute inset-0 flex flex-col items-center justify-center z-10 bg-slate-50 dark:bg-slate-900">
                    <div class="w-16 h-16 border-4 border-slate-200 border-t-dinamis rounded-full animate-spin mb-4"></div>
                    <p class="text-slate-500 font-bold animate-pulse uppercase tracking-widest text-[10px]">Menyiapkan Dokumen Rapor...</p>
                </div>
                <iframe id="previewIframe" class="w-full h-full border-none relative z-0" src="" onload="document.getElementById('previewLoader').style.display='none'"></iframe>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://cdn.sheetjs.com/xlsx-latest/package/dist/xlsx.full.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/exceljs/4.3.0/exceljs.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    const CONFIG = {
        fetch_url: "<?= base_url('tahfidz/nilai-rapor/get-siswa') ?>",
        save_url: "<?= base_url('tahfidz/nilai-rapor/save') ?>",
        import_url: "<?= base_url('tahfidz/nilai-rapor/importCsv') ?>",
        class_name: "" 
    };
    let globalStudentsRapor = [];
</script>
<script src="<?= base_url('assets/js/Tahfidz/nilai-rapor.js') ?>?v=<?= time() ?>"></script>
<?= $this->endSection() ?>