<?= $this->extend('layout/main') ?>

<?= $this->section('title') ?>
Nilai Ekstrakurikuler - Wali Kelas
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<meta name="X-CSRF-TOKEN" content="<?= csrf_hash() ?>">
<style>
  :root {
    --warna-utama: <?= $color['warna_primary'] ?? '#10b981' ?>;
    --warna-scroll: <?= $color['warna_primary'] ?>; 
  }

  .text-dinamis {
    color: var(--warna-utama) !important;
  }

  .bg-dinamis {
    background-color: var(--warna-utama) !important;
  }

  .custom-scrollbar::-webkit-scrollbar {
    height: 8px;
    width: 6px;
  }

  .custom-scrollbar::-webkit-scrollbar-thumb {
    background: var(--warna-utama);
    border-radius: 10px;
  }

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

<?php if (!$rombel): ?>
  <div class="bg-rose-50 border border-rose-200 text-rose-700 p-5 rounded-2xl mb-6 shadow-sm flex items-start gap-4">
    <svg class="w-6 h-6 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
    </svg>
    <div>
      <h4 class="font-bold text-base">Akses Terbatas: Anda Belum Memiliki Kelas!</h4>
      <p class="text-sm mt-1 leading-relaxed">Sistem mendeteksi bahwa akun Anda belum ditugaskan sebagai <b>Wali Kelas</b> di Rombongan Belajar manapun. Hubungi Admin jika ini adalah sebuah kesalahan.</p>
    </div>
  </div>
<?php else: ?>

  <div class="mb-8">
    <div class="flex items-center gap-2 text-sm text-slate-500 mb-2">
      <span>Penilaian Wali Kelas</span>
      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
      </svg>
      <span class="font-bold text-dinamis">Nilai Ekstrakurikuler</span>
    </div>
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
      <div>
        <h1 class="text-3xl font-bold text-slate-900 dark:text-white">Input Nilai Ekstrakurikuler</h1>
        <p class="text-slate-500 text-sm mt-1 font-medium">Kelas: <span class="text-dinamis font-bold"><?= $rombel['tingkat'] ?> - <?= $rombel['nama_rombel'] ?></span></p>
      </div>
    </div>
  </div>

  <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden mb-6">

    <div class="p-5 border-b border-slate-100 dark:border-slate-700 bg-slate-50 dark:bg-slate-900/50 flex flex-col md:flex-row justify-between items-center gap-4">
      <div class="relative w-full max-w-md">
        <svg class="w-5 h-5 absolute left-3 top-2.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
        </svg>
        <input type="text" id="searchInput" placeholder="Cari nama siswa atau NIS..." class="w-full pl-10 pr-4 py-2.5 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-600 rounded-xl text-sm focus:ring-2 focus:ring-dinamis outline-none dark:text-white transition-all shadow-sm">
      </div>
      <div class="w-full md:w-auto flex items-center gap-3">
        <label class="text-sm font-bold text-slate-600 dark:text-slate-300 hidden md:block whitespace-nowrap">Filter Kategori:</label>
        <select id="filterKategori" onchange="loadData()" class="w-full md:w-auto px-4 py-2.5 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-600 rounded-xl text-sm font-bold text-slate-700 dark:text-slate-200 shadow-sm focus:ring-2 focus:ring-dinamis outline-none cursor-pointer transition-colors">
          <option value="Tengah Semester">Tengah Semester</option>
          <option value="Akhir Semester" selected>Akhir Semester</option>
        </select>
      </div>
    </div>

    <div class="overflow-x-auto w-full max-h-[600px] custom-scrollbar">
      <table class="w-full text-left border-collapse">
        <thead class="bg-white dark:bg-slate-800 text-slate-500 dark:text-slate-400 text-xs uppercase tracking-wider sticky top-0 z-10 shadow-sm">
          <tr>
            <th class="p-4 font-bold text-center w-12 border-b border-slate-200 dark:border-slate-700">No</th>
            <th class="p-4 font-bold border-b border-slate-200 dark:border-slate-700 w-1/3">Identitas Siswa</th>
            <th class="p-4 font-bold border-b border-slate-200 dark:border-slate-700">Ekstrakurikuler Siswa (Auto-Detect)</th>
            <th class="p-4 font-bold text-center w-32 border-b border-slate-200 dark:border-slate-700">Aksi</th>
          </tr>
        </thead>
        <tbody id="tableBody" class="divide-y divide-slate-100 dark:divide-slate-700 text-sm text-slate-700 dark:text-slate-300">
          <tr>
            <td colspan="4" class="p-8 text-center text-slate-400">
              <div class="animate-spin w-8 h-8 border-4 border-slate-200 border-t-emerald-500 rounded-full mx-auto mb-2"></div>Memuat data siswa...
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>

  <div id="formModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm hidden z-[100] flex items-center justify-center p-4">
    <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-2xl w-full max-w-2xl my-auto transform scale-95 opacity-0 transition-all duration-300 flex flex-col" id="modalContent">
      <div class="p-6 border-b border-slate-100 dark:border-slate-700 flex justify-between items-center bg-slate-50 dark:bg-slate-900/50 rounded-t-3xl">
        <div>
          <h3 class="text-xl font-bold text-slate-800 dark:text-white">Input Nilai Ekskul</h3>
          <p class="text-xs font-bold text-dinamis mt-1" id="modalStudentName">-</p>
        </div>
        <button onclick="closeModal()" class="text-slate-400 hover:text-rose-500 transition-colors outline-none">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
      </div>

      <div class="p-6 max-h-[60vh] overflow-y-auto custom-scrollbar">
        <form id="nilaiForm" class="space-y-6">
          <input type="hidden" id="siswa_id" name="siswa_id">
          <input type="hidden" id="rombel_id" name="rombel_id">
          <input type="hidden" id="tahun_ajaran_id" name="tahun_ajaran_id">
          <input type="hidden" id="semester" name="semester">
          <input type="hidden" id="kategori" name="kategori">

          <div id="dynamicEkskulContainer" class="space-y-5"></div>
        </form>
      </div>

      <div class="p-6 border-t border-slate-100 dark:border-slate-700 bg-slate-50 dark:bg-slate-900/50 flex justify-end gap-3 rounded-b-3xl">
        <button type="button" onclick="closeModal()" class="px-5 py-2.5 text-slate-600 dark:text-slate-300 font-bold bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 hover:bg-slate-100 rounded-xl transition-colors outline-none shadow-sm">Batal</button>
        <button type="submit" form="nilaiForm" id="btnSave" class="px-8 py-2.5 text-white font-bold bg-dinamis hover:brightness-110 rounded-xl shadow-lg transition-transform hover:-translate-y-0.5 flex items-center gap-2 outline-none">Simpan Semua Nilai</button>
      </div>
    </div>
  </div>
<?php endif; ?>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<?php if ($rombel): ?>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script>
    const API_URL = "<?= base_url('wali/nilai-ekskul') ?>";
    const BASE_URL_IMG = "<?= base_url('uploads/siswa/') ?>";
    const masterEkskul = <?= json_encode($ekskulList) ?>;
  </script>
  <script src="<?= base_url('assets/js/WaliKelas/nilai-ekskul.js?v=' . time()) ?>"></script>
<?php endif; ?>
<?= $this->endSection() ?>