<?= $this->extend('layout/main') ?>

<?= $this->section('title') ?>
Master Lingkup Materi (LM) - Rapor Digital
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
  :root { 
    --warna-utama: <?= $color['warna_primary'] ?? '#10b981' ?>; 
    --warna-sekunder: <?= $color['warna_secondary'] ?? '#ecfdf5' ?>;
    --warna-scroll: <?= $color['warna_primary'] ?>; 
  }
  .text-dinamis { color: var(--warna-utama) !important; }
  .bg-dinamis { background-color: var(--warna-utama) !important; }
  .bg-sekunder { background-color: var(--warna-sekunder) !important; }
  
  .custom-table-scroll::-webkit-scrollbar { height: 8px; width: 6px; }
  .custom-table-scroll::-webkit-scrollbar-thumb { background: var(--warna-utama); border-radius: 10px; }
  
  .glass-card {
    background: rgba(255, 255, 255, 0.8);
    backdrop-filter: blur(12px);
    border: 1px solid rgba(255, 255, 255, 0.3);
  }
  
  .dark .glass-card {
    background: rgba(30, 41, 59, 0.7);
    border: 1px solid rgba(255, 255, 255, 0.05);
  }

  @keyframes slideUp {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
  }
  .animate-slide-up { animation: slideUp 0.4s ease-out forwards; }

  ::-webkit-scrollbar {
    width: 6px;
  }
  
  ::-webkit-scrollbar-track {
    background: #f1f1f1;
  }
  
  ::-webkit-scrollbar-thumb {
    background-color: var(--warna-scroll);
    border-radius: 3px;
  }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="mb-8">
  <div class="flex items-center gap-2 text-sm text-slate-500 mb-2">
    <span>Master Akademik</span>
    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
    <span class="font-bold text-dinamis">Bank Deskripsi Rapor (LM)</span>
  </div>
  <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
    <div>
      <h1 class="text-3xl font-bold text-slate-900 dark:text-white">Master Data LM</h1>
      <p class="text-slate-500 text-sm mt-1">Kelola materi pembelajaran universal (Tanpa Batas Tahun) untuk Rapor Kurikulum Merdeka.</p>
    </div>
    <div class="flex flex-wrap items-center gap-2 md:gap-3">
        <button onclick="showDownloadModal()" class="px-4 py-2.5 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 font-semibold rounded-xl transition-all flex items-center gap-2 outline-none shadow-sm">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
            <span class="hidden sm:inline">Unduh Template</span>
        </button>
        <button onclick="showImportModal()" class="px-4 py-2.5 bg-amber-500 hover:bg-amber-600 text-white font-semibold rounded-xl transition-all shadow-lg shadow-amber-500/20 flex items-center gap-2 transform hover:-translate-y-0.5 outline-none">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
            <span class="hidden sm:inline">Impor LM</span>
        </button>
        <button onclick="showExportModal()" class="px-4 py-2.5 bg-blue-500 hover:bg-blue-600 text-white font-semibold rounded-xl transition-all shadow-lg shadow-blue-500/20 flex items-center gap-2 transform hover:-translate-y-0.5 outline-none">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            <span class="hidden sm:inline">Ekspor LM</span>
        </button>
        <button onclick="showModal()" class="px-4 py-2.5 bg-dinamis text-white font-bold rounded-xl shadow-lg hover:brightness-110 transition-all flex items-center gap-2 outline-none">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
            <span class="hidden sm:inline">Tambah Data</span>
        </button>
    </div>
  </div>
</div>

<div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 p-5 mb-6 w-full min-w-0 transition-colors">
  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 w-full">
    <div>
      <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Semester</label>
      <select id="filterSemester" class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl text-sm font-semibold text-slate-800 dark:text-white focus:ring-2 focus:ring-dinamis outline-none cursor-pointer">
        <option value="ALL">Semua Semester</option>
        <option value="Ganjil">Ganjil</option>
        <option value="Genap">Genap</option>
      </select>
    </div>
    <div>
      <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Mata Pelajaran</label>
      <select id="filterMapel" class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl text-sm font-semibold text-slate-800 dark:text-white focus:ring-2 focus:ring-dinamis outline-none cursor-pointer">
        <option value="ALL">Semua Mapel</option>
        <?php foreach ($mapelList as $mapel): ?>
          <option value="<?= $mapel['id'] ?>"><?= $mapel['nama_mapel'] ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div>
      <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Tingkat Kelas</label>
      <select id="filterLevel" class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl text-sm font-semibold text-slate-800 dark:text-white focus:ring-2 focus:ring-dinamis outline-none cursor-pointer">
        <option value="ALL">Semua Kelas</option>
      </select>
    </div>
    <div>
      <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Kode LM</label>
      <select id="filterKodeLM" class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl text-sm font-semibold text-slate-800 dark:text-white focus:ring-2 focus:ring-dinamis outline-none cursor-pointer">
        <option value="ALL">Semua Kode LM</option>
      </select>
    </div>
    <div>
      <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2 ml-1">Kategori</label>
      <select id="filterKategori" class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl text-sm font-semibold text-slate-800 dark:text-white focus:ring-2 focus:ring-dinamis outline-none cursor-pointer transition-all">
        <option value="ALL">Semua Kategori</option>
        <option value="Tengah">STS (Tengah)</option>
        <option value="Akhir">SAS (Akhir)</option>
      </select>
    </div>
    <div class="lg:col-span-1">
      <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2 ml-1">Pencarian</label>
      <div class="relative">
        <svg class="w-5 h-5 absolute left-3 top-2.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
        <input type="text" id="searchInput" placeholder="Cari Materi LM..." class="w-full pl-10 pr-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl text-sm focus:ring-2 focus:ring-dinamis outline-none dark:text-white transition-all">
      </div>
    </div>
  </div>
</div>

<div class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
  <div class="overflow-x-auto custom-table-scroll min-h-[300px]">
    <table class="w-full text-left border-collapse whitespace-nowrap">
      <thead class="bg-slate-50 dark:bg-slate-900/80 text-slate-500 dark:text-slate-400 text-xs uppercase tracking-wider">
        <tr>
          <th class="p-4 font-bold text-center w-12">No</th>
          <th class="p-4 font-bold">Mata Pelajaran</th>
          <th class="p-4 font-bold text-center">Kelas</th>
          <th class="p-4 font-bold text-center">Semester</th>
          <th class="p-4 font-bold text-center">Kategori</th>
          <th class="p-4 font-bold text-center">Kode LM</th>
          <th class="p-4 font-bold w-1/3">Judul LM (Materi Pokok)</th>
          <th class="p-4 font-bold text-center w-24">Aksi</th>
        </tr>
      </thead>
      <tbody id="tableBody" class="divide-y divide-slate-100 dark:divide-slate-700 text-sm text-slate-700 dark:text-slate-300">
        <tr><td colspan="7" class="p-8 text-center text-slate-400">Memuat data...</td></tr>
      </tbody>
    </table>
  </div>
</div>

<div id="formModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm hidden z-[100] flex items-center justify-center p-4 overflow-y-auto">
  <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-2xl w-[95%] lg:max-w-2xl xl:max-w-4xl my-auto transform scale-95 opacity-0 transition-all duration-300 flex flex-col" style="max-height: 95vh;" id="modalContent">
    <div class="p-6 border-b border-slate-100 dark:border-slate-700 flex justify-between items-center bg-slate-50 dark:bg-slate-900/50 rounded-t-3xl">
      <div>
        <h3 class="text-xl font-bold text-slate-800 dark:text-white" id="modalTitle">Buat Template Deskripsi</h3>
        <p class="text-xs text-slate-500 mt-1">Sistem akan otomatis memilih kalimat ini berdasarkan nilai rapor siswa.</p>
      </div>
      <button onclick="closeModal()" class="text-slate-400 hover:text-rose-500 transition-colors outline-none">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
      </button>
    </div>
    
    <div class="flex-1 overflow-y-auto custom-table-scroll p-6">
      <form id="lmForm" class="space-y-6">
        <input type="hidden" id="lm_id" name="id">
        
        <div class="bg-slate-50 dark:bg-slate-900/50 p-4 rounded-2xl border border-slate-200 dark:border-slate-700 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-2 xl:grid-cols-4 gap-4">
          <div>
            <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1">Mata Pelajaran <span class="text-rose-500">*</span></label>
            <select name="mapel_id" id="mapel_id" required class="w-full p-2.5 border border-slate-200 dark:border-slate-600 rounded-xl outline-none focus:ring-2 focus:ring-dinamis dark:bg-slate-800 dark:text-white text-sm font-bold text-dinamis cursor-pointer">
              <option value="">-- Pilih Mapel --</option>
              <?php foreach($mapelList as $m): ?>
                <option value="<?= $m['id'] ?>"><?= $m['nama_mapel'] ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div>
            <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1">Kelas <span class="text-rose-500">*</span></label>
            <select name="tingkat" id="tingkat" required class="w-full p-2.5 border border-slate-200 dark:border-slate-600 rounded-xl outline-none focus:ring-2 focus:ring-dinamis dark:bg-slate-800 dark:text-white text-sm font-bold cursor-pointer">
              <?php foreach($tingkatList as $tingkat): ?>
                <option value="<?= $tingkat ?>">Kelas <?= $tingkat ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div>
            <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1">Kategori <span class="text-rose-500">*</span></label>
            <select name="kategori" id="kategori" required class="w-full p-2.5 border border-slate-200 dark:border-slate-600 rounded-xl outline-none focus:ring-2 focus:ring-dinamis dark:bg-slate-800 dark:text-white text-sm font-bold cursor-pointer">
              <option value="Tengah">Tengah Semester (STS)</option>
              <option value="Akhir">Akhir Semester (SAS)</option>
            </select>
          </div>
          <div>
            <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1">Kode LM <span class="text-rose-500">*</span></label>
            <input list="kodeLmList" name="kode_lm" id="kode_lm" required placeholder="Contoh: LM 1" class="w-full p-2.5 border border-slate-200 dark:border-slate-600 rounded-xl outline-none focus:ring-2 focus:ring-dinamis dark:bg-slate-800 dark:text-white text-sm font-bold uppercase">
            <datalist id="kodeLmList">
              <option value="LM 1"><option value="LM 2"><option value="LM 3"><option value="LM 4"><option value="LM 5">
              <option value="LM 6"><option value="LM 7"><option value="LM 8"><option value="LM 9">
            </datalist>
          </div>
          <div>
            <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1">Semester <span class="text-rose-500">*</span></label>
            <select name="semester" id="semester" required class="w-full p-2.5 border border-slate-200 dark:border-slate-600 rounded-xl outline-none focus:ring-2 focus:ring-dinamis dark:bg-slate-800 dark:text-white text-sm font-bold cursor-pointer">
              <option value="Ganjil">Ganjil</option>
              <option value="Genap">Genap</option>
            </select>
          </div>
        </div>

        <div>
            <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1">Judul / Pokok Materi (LM) <span class="text-rose-500">*</span></label>
            <div class="flex gap-2">
                <input type="text" name="deskripsi_lm" id="deskripsi_lm" required placeholder="Contoh: Memahami kandungan surah an nisa dan an nahl" class="flex-1 p-3 border border-slate-200 dark:border-slate-600 rounded-xl outline-none focus:ring-2 focus:ring-dinamis dark:bg-slate-800 dark:text-white text-sm">
                <button type="button" onclick="autoGenerateDeskripsi()" class="px-5 py-3 bg-indigo-500 hover:bg-indigo-600 text-white font-bold rounded-xl shadow-lg transition-transform hover:-translate-y-0.5 flex items-center gap-2 outline-none">
                    <span class="text-xl">🪄</span> Auto Generate
                </button>
            </div>
            <p class="text-[10px] text-slate-400 mt-1.5 italic">*Ketik judul materi, lalu klik tombol <b class="text-indigo-500">Auto Generate</b> untuk merangkai deskripsi A, B, C, D.</p>
        </div>

        <hr class="border-slate-100 dark:border-slate-700 my-2">

        <h4 class="font-bold text-slate-700 dark:text-white flex items-center gap-2">
            <svg class="w-5 h-5 text-dinamis" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
            Template Kalimat Rapor Berdasarkan Predikat Nilai Siswa
        </h4>

        <div class="space-y-4">
            <div class="flex gap-4 items-start">
                <div class="w-20 pt-2 text-center">
                    <span class="bg-emerald-100 text-emerald-700 font-black text-xl px-4 py-2 rounded-xl border-b-4 border-emerald-200 shadow-sm">A</span>
                    <p class="text-[10px] font-bold text-emerald-600 mt-2 uppercase">Sangat Baik</p>
                </div>
                <textarea name="deskripsi_a" id="deskripsi_a" rows="2" placeholder="..." class="flex-1 p-3 border border-emerald-200 bg-emerald-50/50 rounded-xl outline-none focus:ring-2 focus:ring-emerald-500 resize-none text-sm dark:bg-slate-800 dark:border-emerald-900/50 dark:text-white"></textarea>
            </div>
            <div class="flex gap-4 items-start">
                <div class="w-20 pt-2 text-center">
                    <span class="bg-blue-100 text-blue-700 font-black text-xl px-4 py-2 rounded-xl border-b-4 border-blue-200 shadow-sm">B</span>
                    <p class="text-[10px] font-bold text-blue-600 mt-2 uppercase">Baik</p>
                </div>
                <textarea name="deskripsi_b" id="deskripsi_b" rows="2" placeholder="..." class="flex-1 p-3 border border-blue-200 bg-blue-50/50 rounded-xl outline-none focus:ring-2 focus:ring-blue-500 resize-none text-sm dark:bg-slate-800 dark:border-blue-900/50 dark:text-white"></textarea>
            </div>
            <div class="flex gap-4 items-start">
                <div class="w-20 pt-2 text-center">
                    <span class="bg-amber-100 text-amber-700 font-black text-xl px-4 py-2 rounded-xl border-b-4 border-amber-200 shadow-sm">C</span>
                    <p class="text-[10px] font-bold text-amber-600 mt-2 uppercase">Cukup</p>
                </div>
                <textarea name="deskripsi_c" id="deskripsi_c" rows="2" placeholder="..." class="flex-1 p-3 border border-amber-200 bg-amber-50/50 rounded-xl outline-none focus:ring-2 focus:ring-amber-500 resize-none text-sm dark:bg-slate-800 dark:border-amber-900/50 dark:text-white"></textarea>
            </div>
            <div class="flex gap-4 items-start">
                <div class="w-20 pt-2 text-center">
                    <span class="bg-rose-100 text-rose-700 font-black text-xl px-4 py-2 rounded-xl border-b-4 border-rose-200 shadow-sm">D</span>
                    <p class="text-[10px] font-bold text-rose-600 mt-2 uppercase leading-tight">Perlu Bimbingan</p>
                </div>
                <textarea name="deskripsi_d" id="deskripsi_d" rows="2" placeholder="..." class="flex-1 p-3 border border-rose-200 bg-rose-50/50 rounded-xl outline-none focus:ring-2 focus:ring-rose-500 resize-none text-sm dark:bg-slate-800 dark:border-rose-900/50 dark:text-white"></textarea>
            </div>
        </div>
      </form>
    </div>

    <div class="p-6 border-t border-slate-100 dark:border-slate-700 bg-slate-50 dark:bg-slate-900/50 flex justify-end gap-3 rounded-b-3xl">
        <button type="button" onclick="closeModal()" class="px-5 py-2.5 text-slate-600 dark:text-slate-300 font-bold bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 hover:bg-slate-100 rounded-xl transition-colors outline-none">Batal</button>
        <button type="submit" form="lmForm" id="btnSave" class="px-8 py-2.5 text-white font-bold bg-dinamis hover:brightness-110 rounded-xl shadow-lg transition-transform hover:-translate-y-0.5 flex items-center gap-2 outline-none">
          Simpan Template
        </button>
    </div>
  </div>
</div>

<div id="importModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm hidden z-[110] flex items-center justify-center p-4">
  <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-2xl w-full max-w-lg transform scale-95 opacity-0 transition-all duration-300" id="importModalContent">
    <div class="p-6 border-b border-slate-100 dark:border-slate-700 flex justify-between items-center">
      <div>
        <h3 class="text-xl font-bold text-slate-800 dark:text-white">Impor</h3>
        <p class="text-sm text-slate-500 mt-1">Upload file Excel atau Word (.docx) Anda.</p>
      </div>
      <button type="button" onclick="closeImportModal()" class="p-2 hover:bg-slate-100 dark:hover:bg-slate-700 rounded-lg transition-colors cursor-pointer text-slate-400 outline-none">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
      </button>
    </div>

    <div class="p-8">
      <form id="importForm" action="<?= base_url('admin/master-lm/import') ?>" method="POST" enctype="multipart/form-data" onsubmit="handleImportSubmit(event)">
        <div class="bg-blue-50 dark:bg-blue-900/30 p-4 rounded-xl border border-blue-200 dark:border-blue-800/50 mb-6">
            <p class="text-xs text-blue-800 dark:text-blue-300 leading-relaxed font-medium">Sistem <strong>AI-Extractor</strong> kami kini mampu membaca langsung file <strong>Word (.docx)</strong> dan otomatis mengubahnya menjadi database Rapor!</p>
        </div>
        
        <div class="mb-6 grid grid-cols-2 gap-4">
            <div>
                <label class="flex items-center gap-2 text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-2 ml-1">
                    <svg class="w-3.5 h-3.5 text-dinamis" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    Target Semester
                </label>
                <select name="force_semester" class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl text-sm font-bold text-slate-700 dark:text-white focus:ring-2 focus:ring-dinamis outline-none cursor-pointer">
                    <option value="auto">Deteksi Otomatis</option>
                    <option value="Ganjil">Semester Ganjil</option>
                    <option value="Genap">Semester Genap</option>
                </select>
            </div>
            <div>
                <label class="flex items-center gap-2 text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-2 ml-1">
                    <svg class="w-3.5 h-3.5 text-dinamis" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                    Target Kategori
                </label>
                <select name="force_kategori" class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl text-sm font-bold text-slate-700 dark:text-white focus:ring-2 focus:ring-dinamis outline-none cursor-pointer">
                    <option value="auto">Deteksi Otomatis</option>
                    <option value="Tengah">STS (Tengah)</option>
                    <option value="Akhir">SAS (Akhir)</option>
                </select>
            </div>
        </div>

        <div class="border-2 border-dashed border-slate-300 dark:border-slate-600 rounded-2xl p-10 text-center hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors relative cursor-pointer group">
          <input type="file" name="file_excel" id="file_excel" accept=".xlsx, .xls, .csv, .docx, .doc" required class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" onchange="updateFileName(this)">
          <div class="w-16 h-16 bg-amber-50 dark:bg-amber-900/30 text-amber-500 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
          </div>
          <p class="text-sm font-bold text-slate-700 dark:text-slate-300" id="fileNameText">Klik atau Seret file Excel / Word (.docx)</p>
          <p class="text-xs text-slate-500 dark:text-slate-400 mt-2">Mendukung format .xlsx, .xls, .docx</p>
        </div>
        <div class="mt-8 flex gap-3">
          <button type="button" onclick="closeImportModal()" class="flex-1 px-6 py-3.5 bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-300 font-bold rounded-xl hover:bg-slate-200 dark:hover:bg-slate-600 transition-colors outline-none">Batal</button>
          <button type="submit" id="btnImport" class="flex-1 px-6 py-3.5 bg-amber-500 hover:bg-amber-600 text-white font-bold rounded-xl shadow-lg transition-transform transform hover:-translate-y-0.5 outline-none">Mulai Ekstraksi</button>
        </div>
      </form>
    </div>
  </div>
</div>

<div id="downloadModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm hidden z-[110] flex items-center justify-center p-4">
  <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-2xl w-full max-w-md transform scale-95 opacity-0 transition-all duration-300" id="downloadModalContent">
    <div class="p-6 border-b border-slate-100 dark:border-slate-700 flex justify-between items-center">
      <div>
        <h3 class="text-xl font-bold text-slate-800 dark:text-white">Unduh Template LM</h3>
        <p class="text-sm text-slate-500 mt-1">Format ini siap diisi materi untuk diimpor kembali ke sistem.</p>
      </div>
      <button onclick="closeDownloadModal()" class="p-2 hover:bg-slate-100 dark:hover:bg-slate-700 rounded-lg transition-colors text-slate-400">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
      </button>
    </div>
    <div class="p-6 space-y-4">
        <a href="<?= base_url('admin/master-lm/download-template/tengah') ?>" onclick="closeDownloadModal()" class="flex items-center gap-4 w-full p-4 border-2 border-slate-100 dark:border-slate-700 rounded-xl hover:border-dinamis hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-all group">
            <div class="p-3 bg-emerald-100 dark:bg-emerald-900/30 text-emerald-600 rounded-xl group-hover:bg-emerald-200 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
            </div>
            <div class="text-left">
                <h4 class="font-bold text-slate-800 dark:text-white group-hover:text-dinamis transition-colors">Tengah Semester</h4>
                <p class="text-xs text-slate-500 mt-0.5">Template Format LM 1 - 4</p>
            </div>
        </a>
        <a href="<?= base_url('admin/master-lm/download-template/akhir') ?>" onclick="closeDownloadModal()" class="flex items-center gap-4 w-full p-4 border-2 border-slate-100 dark:border-slate-700 rounded-xl hover:border-dinamis hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-all group">
            <div class="p-3 bg-blue-100 dark:bg-blue-900/30 text-blue-600 rounded-xl group-hover:bg-blue-200 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
            </div>
            <div class="text-left">
                <h4 class="font-bold text-slate-800 dark:text-white group-hover:text-dinamis transition-colors">Akhir Semester</h4>
                <p class="text-xs text-slate-500 mt-0.5">Template Format LM 5 - 9</p>
            </div>
        </a>
    </div>
  </div>
</div>

<div id="exportModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm hidden z-[110] flex items-center justify-center p-4">
  <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-2xl w-full max-w-sm transform scale-95 opacity-0 transition-all duration-300" id="exportModalContent">
    <div class="p-6 border-b border-slate-100 dark:border-slate-700 flex justify-between items-center">
      <div>
        <h3 class="text-xl font-bold text-slate-800 dark:text-white">Ekspor Data LM</h3>
        <p class="text-sm text-slate-500 mt-1">Unduh data LM yang ada di sistem saat ini</p>
      </div>
      <button onclick="closeExportModal()" class="p-2 hover:bg-slate-100 dark:hover:bg-slate-700 rounded-lg transition-colors text-slate-400">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
      </button>
    </div>
    <div class="p-6 space-y-3">
        <button type="button" onclick="executeExport('tengah')" class="block w-full text-left px-5 py-4 border-2 border-slate-100 dark:border-slate-700 rounded-xl hover:border-blue-500 hover:bg-blue-50 dark:hover:bg-slate-700/50 transition-all group outline-none">
            <h4 class="font-bold text-slate-800 dark:text-white group-hover:text-blue-500">Ekspor Tengah Semester</h4>
            <p class="text-xs text-slate-500 mt-1">Hanya Unduh Data LM 1 - LM 4</p>
        </button>
        <button type="button" onclick="executeExport('akhir')" class="block w-full text-left px-5 py-4 border-2 border-slate-100 dark:border-slate-700 rounded-xl hover:border-blue-500 hover:bg-blue-50 dark:hover:bg-slate-700/50 transition-all group outline-none">
            <h4 class="font-bold text-slate-800 dark:text-white group-hover:text-blue-500">Ekspor Akhir Semester</h4>
            <p class="text-xs text-slate-500 mt-1">Hanya Unduh Data LM 5 - LM 9</p>
        </button>
        <button type="button" onclick="executeExport('semua')" class="block w-full text-left px-5 py-4 border-2 border-slate-100 dark:border-slate-700 rounded-xl hover:border-blue-500 hover:bg-blue-50 dark:hover:bg-slate-700/50 transition-all group outline-none">
            <h4 class="font-bold text-slate-800 dark:text-white group-hover:text-blue-500">Ekspor Semua LM</h4>
            <p class="text-xs text-slate-500 mt-1">Unduh seluruh Data LM di sistem</p>
        </button>
    </div>
  </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
  const BASE_URL = "<?= rtrim(base_url(), '/') ?>";
</script>
<script src="<?= base_url('assets/js/Admin/master-lm.js?v=' . rand(10000, 99999)) ?>"></script>
<?= $this->endSection() ?>