<?= $this->extend('layout/main') ?>

<?= $this->section('title') ?>
<?= lang('GuruMapel/NilaiHarian.page_title') ?> - Rapor Digital
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
  :root {
    --warna-scroll: <?= $color['warna_primary'] ?>;
  }

  .dark .modal-content {
    background-color: #0f172a !important;
    border-color: #1e293b !important;
  }

  .nilai-table tbody tr:hover {
    background-color: <?= $color['warna_primary'] ?>1A !important;
  }

  .dark .nilai-table tbody tr:hover {
    background-color: <?= $color['warna_primary'] ?>33 !important;
  }
</style>
<link rel="stylesheet" href="<?= base_url('assets/css/GuruMapel/nilai-formatif.css') ?>">
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
  <div>
    <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:!text-white mb-2 transition-colors" id="pageTitle"><?= lang('GuruMapel/NilaiHarian.page_title') ?></h1>
    <p class="text-base text-gray-600 dark:!text-slate-400 font-medium transition-colors" id="pageSubtitle"><?= lang('GuruMapel/NilaiHarian.page_subtitle') ?></p>
  </div>

  <div class="w-full md:w-auto invisible">
    <!-- Dropdown dipindahkan ke area filter agar lebih dinamis -->
  </div>
</div>

<div class="info-card bg-[<?= $color['warna_secondary'] ?>] dark:!bg-slate-800 border border-[<?= $color['warna_primary'] ?>]/80 dark:!border-slate-700 mb-6 p-5 rounded-2xl shadow-sm transition-colors">
  <div class="grid grid-cols-2 lg:grid-cols-5 gap-6">
    <div class="flex items-center gap-4">
      <div class="w-12 h-12 rounded-xl bg-emerald-500 flex items-center justify-center shadow-lg shadow-emerald-500/20 flex-shrink-0 transition-transform hover:scale-105">
        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewbox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
        </svg>
      </div>
      <div class="min-w-0 pr-2">
        <p class="text-[11px] text-[<?= $color['warna_primary'] ?>] font-black uppercase tracking-widest mb-1 truncate transition-colors"><?= lang('GuruMapel/NilaiHarian.info_subject') ?></p>
        <p class="text-base font-bold text-gray-900 dark:!text-white transition-colors truncate" id="infoSubject"><?= esc($info['mapel_nama']) ?></p>
      </div>
    </div>
    <div class="flex items-center gap-4">
      <div class="w-12 h-12 rounded-xl bg-blue-500 flex items-center justify-center shadow-lg shadow-blue-500/20 flex-shrink-0 transition-transform hover:scale-105">
        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewbox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
        </svg>
      </div>
      <div class="min-w-0 pr-2">
        <p class="text-[11px] text-[<?= $color['warna_primary'] ?>] font-black uppercase tracking-widest mb-1 truncate transition-colors"><?= lang('GuruMapel/NilaiHarian.info_class') ?></p>
        <p class="text-base font-bold text-gray-900 dark:!text-white transition-colors truncate" id="infoClass"><?= esc($info['kelas_nama']) ?></p>
      </div>
    </div>
    <div class="flex items-center gap-4">
      <div class="w-12 h-12 rounded-xl bg-purple-500 flex items-center justify-center shadow-lg shadow-purple-500/20 flex-shrink-0 transition-transform hover:scale-105">
        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewbox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
        </svg>
      </div>
      <div class="min-w-0 pr-2">
        <p class="text-[11px] text-[<?= $color['warna_primary'] ?>] font-black uppercase tracking-widest mb-1 truncate transition-colors"><?= lang('GuruMapel/NilaiHarian.info_student_count') ?></p>
        <p class="text-base font-bold text-gray-900 dark:!text-white transition-colors truncate"><?= esc($info['jml_siswa']) ?> <?= lang('GuruMapel/NilaiHarian.info_student_text') ?></p>
      </div>
    </div>
    <div class="flex items-center gap-4">
      <div class="w-12 h-12 rounded-xl bg-amber-500 flex items-center justify-center shadow-lg shadow-amber-500/20 flex-shrink-0 transition-transform hover:scale-105">
        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewbox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
        </svg>
      </div>
      <div class="min-w-0 pr-2">
        <p class="text-[11px] text-[<?= $color['warna_primary'] ?>] font-black uppercase tracking-widest mb-1 truncate transition-colors"><?= lang('GuruMapel/NilaiHarian.info_homeroom') ?></p>
        <p class="text-base font-bold text-gray-900 dark:!text-white transition-colors truncate" id="infoWaliKelas"><?= esc($info['wali_kelas']) ?></p>
      </div>
    </div>
    <div class="flex items-center gap-4">
      <div class="w-12 h-12 rounded-xl bg-red-500 flex items-center justify-center shadow-lg shadow-red-500/20 flex-shrink-0 transition-transform hover:scale-105">
        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewbox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
        </svg>
      </div>
      <div class="min-w-0">
        <p class="text-[11px] text-[<?= $color['warna_primary'] ?>] font-black uppercase tracking-widest mb-1 truncate transition-colors"><?= lang('GuruMapel/NilaiHarian.info_status') ?></p>
        <span class="inline-flex px-2 py-0.5 rounded-lg text-[10px] font-black uppercase tracking-wider transition-all bg-gray-100 dark:!bg-slate-700 text-gray-500 dark:!text-slate-300" id="statusBadge">
          PILIH JENIS NILAI
        </span>
      </div>
    </div>
  </div>
</div>

<div class="bg-white dark:!bg-slate-800 rounded-2xl shadow-sm p-6 mb-6 border border-gray-100 dark:!border-slate-700 transition-colors">
  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-5 items-end">

    <div class="flex flex-col">
      <label class="text-[11px] font-black text-gray-500 dark:!text-slate-400 uppercase tracking-widest mb-2 transition-colors">Kategori</label>
      <select id="kategoriFilter" class="px-4 py-2.5 bg-gray-50 dark:!bg-slate-700 border border-gray-200 dark:!border-slate-600 rounded-xl text-gray-900 dark:!text-white focus:ring-2 focus:ring-[<?= $color['warna_primary'] ?>] transition-all outline-none appearance-none cursor-pointer" onchange="handleFilterChange()">
        <option value="Tengah Semester">Tengah Semester</option>
        <option value="Akhir Semester">Akhir Semester</option>
      </select>
    </div>

    <div class="flex flex-col">
      <label class="text-[11px] font-black text-gray-500 dark:!text-slate-400 uppercase tracking-widest mb-2 transition-colors">Tahun Ajaran & Semester</label>
      <select id="tahunAjaran" class="px-4 py-2.5 bg-gray-50 dark:!bg-slate-700 border border-gray-200 dark:!border-slate-600 rounded-xl text-gray-900 dark:!text-white focus:ring-2 focus:ring-[<?= $color['warna_primary'] ?>] transition-all outline-none cursor-pointer" onchange="handleFilterChange()">
        <?php foreach ($tahun_ajaran_list as $ta) : ?>
          <option value="<?= $ta['id'] ?>" data-semester="<?= $ta['semester'] ?>" <?= ($ta['status'] == 'Aktif') ? 'selected' : '' ?>>
            <?= $ta['tahun'] ?> - <?= $ta['semester'] ?> <?= ($ta['status'] == 'Aktif') ? '(AKTIF)' : '' ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>

    <div class="flex flex-col">
      <label class="text-[11px] font-black text-gray-500 dark:!text-slate-400 uppercase tracking-widest mb-2 transition-colors">Pilih Kelas & Mapel</label>
      <select id="rombelMapelSelect" class="px-4 py-2.5 bg-gray-50 dark:!bg-slate-700 border border-gray-200 dark:!border-slate-600 rounded-xl text-gray-900 dark:!text-white focus:ring-2 focus:ring-[<?= $color['warna_primary'] ?>] transition-all outline-none appearance-none cursor-pointer" onchange="handleClassChange()">
        <?php foreach ($allRombel as $rb): ?>
          <option value="<?= $rb['rombel_id'] ?>|<?= $rb['mapel_id'] ?>" data-kelas="<?= esc($rb['nama_kelas']) ?>" data-mapel="<?= esc($rb['nama_mapel']) ?>" data-wali="<?= esc($rb['wali_kelas']) ?>" <?= ($rb['rombel_id'] == $info['rombel_id'] && $rb['mapel_id'] == $info['mapel_id']) ? 'selected' : '' ?>>
            <?= esc($rb['nama_kelas']) ?> - <?= esc($rb['nama_mapel']) ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>

    <div class="flex flex-col">
      <label class="text-[11px] font-black text-gray-500 dark:!text-slate-400 uppercase tracking-widest mb-2 transition-colors"><?= lang('GuruMapel/NilaiHarian.type_label') ?></label>
      <select id="jenisPenilaian" class="px-4 py-2.5 bg-gray-50 dark:!bg-slate-700 border border-gray-200 dark:!border-slate-600 rounded-xl text-gray-900 dark:!text-white focus:ring-2 focus:ring-[<?= $color['warna_primary'] ?>] transition-all outline-none appearance-none cursor-pointer" onchange="checkAndLoadGrades()">
        <option value="">-- Pilih Jenis --</option>
        <option value="Tugas">Nilai Harian (Tugas)</option>
        <option value="Ulangan">Ulangan Harian</option>
      </select>
    </div>

    <div class="flex flex-col">
      <label class="text-[11px] font-black text-gray-500 dark:!text-slate-400 uppercase tracking-widest mb-2 transition-colors"><?= lang('GuruMapel/NilaiHarian.meeting_label') ?></label>
      <select id="pertemuan" class="px-4 py-2.5 bg-gray-50 dark:!bg-slate-700 border border-gray-200 dark:!border-slate-600 rounded-xl text-gray-900 dark:!text-white focus:ring-2 focus:ring-[<?= $color['warna_primary'] ?>] transition-all outline-none appearance-none cursor-pointer" onchange="checkAndLoadGrades()">
        <option value="">-- Pertemuan --</option>
      </select>
    </div>

    <div class="flex flex-col">
      <label class="text-[11px] font-black text-gray-500 dark:!text-slate-400 uppercase tracking-widest mb-2 transition-colors"><?= lang('GuruMapel/NilaiHarian.kkm_label') ?></label>
      <input type="number" id="kkm" value="75" class="px-4 py-2.5 bg-gray-50 dark:!bg-slate-700 border border-gray-200 dark:!border-slate-600 rounded-xl text-center font-bold text-gray-900 dark:!text-white outline-none w-full" onkeyup="refreshKkmColors()" onchange="refreshKkmColors()">
    </div>
  </div>
</div>

<div id="progressContainer" class="bg-white dark:!bg-slate-800 rounded-2xl p-5 mb-6 border border-gray-100 dark:!border-slate-700 transition-colors shadow-sm items-center gap-4 flex">
  <svg class="w-8 h-8 text-emerald-500 flex-shrink-0" fill="none" stroke="currentColor" viewbox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
  </svg>
  <div class="flex-1 min-w-0">
    <div class="flex items-center justify-between mb-2">
      <span class="text-[11px] font-black text-gray-500 dark:!text-slate-400 uppercase tracking-widest transition-colors"><?= lang('GuruMapel/NilaiHarian.progress_title') ?></span>
      <span class="text-sm font-black text-emerald-600 dark:!text-emerald-400 transition-colors" id="progressText">0/0 siswa</span>
    </div>
    <div class="w-full bg-gray-100 dark:!bg-slate-700 rounded-full h-2 shadow-inner transition-colors">
      <div class="bg-gradient-to-r from-emerald-400 to-emerald-600 h-2 rounded-full transition-all duration-700 shadow-[0_0_8px_rgba(16,185,129,0.4)]" id="progressFill" style="width: 0%"></div>
    </div>
  </div>
</div>

<div id="tableContainer" class="bg-white dark:!bg-slate-800 rounded-3xl shadow-sm border border-gray-100 dark:!border-slate-700 overflow-hidden transition-colors">
  <div class="overflow-x-auto custom-scrollbar">
    <table class="w-full text-left border-collapse min-w-max nilai-table">
      <thead class="bg-gray-50 dark:!bg-slate-900 border-b border-gray-200 dark:!border-slate-700 transition-colors">
        <tr class="text-[11px] font-black !text-gray-600 dark:!text-slate-300 uppercase tracking-widest transition-colors">
          <th class="dark:!bg-slate-800/70 px-6 py-4 text-center !text-gray-600 dark:!text-slate-300"><?= lang('GuruMapel/NilaiHarian.th_no') ?></th>
          <th class="dark:!bg-slate-800/70 px-6 py-4 !text-gray-600 dark:!text-slate-300"><?= lang('GuruMapel/NilaiHarian.th_name') ?></th>
          <th class="dark:!bg-slate-800/70 px-6 py-4 !text-gray-600 dark:!text-slate-300"><?= lang('GuruMapel/NilaiHarian.th_nis') ?></th>
          <th class="dark:!bg-slate-800/70 px-6 py-4 text-center !text-gray-600 dark:!text-slate-300"><?= lang('GuruMapel/NilaiHarian.th_grade') ?></th>
          <th class="dark:!bg-slate-800/70 px-6 py-4 text-center !text-gray-600 dark:!text-slate-300"><?= lang('GuruMapel/NilaiHarian.th_predicate') ?></th>
          <th class="dark:!bg-slate-800/70 px-6 py-4 !text-gray-600 dark:!text-slate-300"><?= lang('GuruMapel/NilaiHarian.th_notes') ?></th>
        </tr>
      </thead>
      <tbody id="nilaiTableBody" class="divide-y divide-gray-100 dark:!divide-slate-700/50 bg-white dark:!bg-slate-800 transition-colors">
        <tr>
          <td colspan="6" class="text-center py-10 font-bold text-gray-500 dark:!text-slate-400">Memuat data siswa...</td>
        </tr>
      </tbody>
    </table>
  </div>
</div>

<div id="actionToolbar" class="hidden bg-white dark:!bg-slate-800 rounded-3xl shadow-xl p-5 mt-8 border border-gray-100 dark:!border-slate-700 transition-all">
  <div class="flex flex-col md:flex-row items-center justify-between gap-5">
    <div class="flex items-center gap-3 bg-emerald-50 dark:!bg-emerald-900/20 px-4 py-2 rounded-xl transition-colors">
      <svg class="w-5 h-5 text-emerald-500 animate-pulse" fill="none" stroke="currentColor" viewbox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
      </svg>
      <span class="text-[11px] text-emerald-800 dark:!text-emerald-400 font-black uppercase tracking-wider transition-colors"><?= lang('GuruMapel/NilaiHarian.auto_save_info') ?></span>
    </div>
    <div class="flex flex-wrap items-center justify-center md:justify-end gap-3 w-full md:w-auto">
      <button id="btnImportExcel" class="px-5 py-2.5 bg-blue-50 dark:!bg-blue-900/30 text-blue-600 dark:!text-blue-400 border border-blue-200 dark:!border-blue-800/50 hover:bg-blue-600 hover:text-white dark:hover:!bg-blue-600 font-bold rounded-xl transition-all flex items-center gap-2 outline-none shadow-sm" onclick="openImportModal()">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
        </svg>
        Import Excel
      </button>
      <button class="px-5 py-2.5 border-2 border-red-200 dark:!border-red-900/30 text-red-600 dark:!text-red-400 hover:bg-red-600 hover:text-white font-bold rounded-xl transition-all flex items-center gap-2 outline-none shadow-sm" onclick="resetAllNilai()">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
        </svg>
        <?= lang('GuruMapel/NilaiHarian.btn_reset_all') ?>
      </button>
      <button id="btnSaveDraft" class="px-5 py-2.5 bg-gray-100 dark:!bg-slate-700 text-gray-700 dark:!text-slate-300 hover:bg-gray-200 dark:hover:!bg-slate-600 font-bold rounded-xl transition-all flex items-center gap-2 outline-none shadow-sm" onclick="saveDraft()">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
        </svg>
        <?= lang('GuruMapel/NilaiHarian.btn_save_draft') ?>
      </button>
      <button id="btnSaveLock" class="px-6 py-2.5 bg-gradient-to-r from-emerald-500 to-emerald-600 text-white font-black uppercase tracking-wider text-xs rounded-xl shadow-lg shadow-emerald-500/30 hover:brightness-110 transform hover:-translate-y-1 transition-all flex items-center gap-2 outline-none" onclick="saveLock()">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
        </svg>
        <?= lang('GuruMapel/NilaiHarian.btn_save_lock') ?>
      </button>
    </div>
  </div>
</div>

<div id="importModal" class="fixed inset-0 z-[100000] hidden items-center justify-center p-4">
  <div class="absolute inset-0 bg-gray-950/70 backdrop-blur-sm transition-opacity" onclick="closeImportModal()"></div>
  <div class="relative w-full max-w-lg bg-white dark:!bg-slate-800 rounded-3xl shadow-2xl flex flex-col border border-transparent dark:!border-slate-700 transition-colors transform overflow-hidden z-10">
    <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-5 flex items-center justify-between rounded-t-3xl transition-colors">
      <h2 class="text-xl font-black text-white">Import Nilai via Excel</h2>
      <button onclick="closeImportModal()" class="text-white hover:bg-white/20 rounded-full p-2 transition-colors outline-none"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg></button>
    </div>
    <div class="flex border-b border-gray-200 dark:!border-slate-700 bg-gray-50 dark:!bg-slate-900/50 text-sm font-bold text-center">
      <button id="tabSingle" onclick="switchImportTab('single')" class="flex-1 py-4 text-blue-600 border-b-2 border-blue-600 dark:text-blue-400 dark:border-blue-400 transition-colors outline-none">1 Pertemuan Saja</button>
      <button id="tabGlobal" onclick="switchImportTab('global')" class="flex-1 py-4 text-gray-500 hover:text-gray-700 dark:text-slate-400 dark:hover:text-slate-200 border-b-2 border-transparent transition-colors outline-none">Semua Pertemuan (Global)</button>
    </div>
    <div class="p-6">
      <div id="contentSingle">
        <div class="bg-blue-50 dark:!bg-blue-900/20 p-4 rounded-xl border border-blue-200 dark:!border-blue-800/50 mb-5">
          <p class="text-sm text-blue-800 dark:!text-blue-300 font-bold mb-2">PANDUAN (1 PERTEMUAN):</p>
          <ol class="text-xs text-blue-700 dark:!text-blue-400 list-decimal list-inside space-y-1 ml-1 font-medium">
            <li>Pilih Pertemuan di layar, lalu <button type="button" onclick="downloadTemplateExcel()" class="font-bold underline text-blue-600 hover:text-blue-800">Download Template Ini</button>.</li>
            <li>Isi <b>Nilai Angka (0-100)</b>. Biarkan Catatan kosong agar diisi otomatis oleh Master LM.</li>
            <li>Upload file yang sudah diisi ke formulir di bawah ini.</li>
          </ol>
        </div>
        <form id="formImportExcel" onsubmit="submitImport(event)">
          <?= csrf_field() ?>
          <label class="block text-[11px] font-black text-gray-500 dark:!text-slate-400 uppercase tracking-widest mb-2">Upload File Excel (1 Pertemuan)</label>
          <input type="file" accept=".xlsx, .xls" required class="block w-full text-sm text-gray-500 dark:!text-slate-400 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-bold file:bg-blue-100 dark:file:!bg-blue-900/40 file:text-blue-700 dark:file:!text-blue-400 hover:file:bg-blue-200 border border-gray-200 dark:!border-slate-600 rounded-xl mb-5 outline-none cursor-pointer">
          <button type="submit" id="btnSubmitImport" class="w-full px-6 py-3.5 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl transition-all shadow-lg outline-none">Mulai Import (1 Pertemuan)</button>
        </form>
      </div>
      <div id="contentGlobal" class="hidden">
        <div class="bg-indigo-50 dark:!bg-indigo-900/20 p-4 rounded-xl border border-indigo-200 dark:!border-indigo-800/50 mb-5">
          <p class="text-sm text-indigo-800 dark:!text-indigo-300 font-bold mb-2">PANDUAN (SEMUA PERTEMUAN):</p>
          <ol class="text-xs text-indigo-700 dark:!text-indigo-400 list-decimal list-inside space-y-1 ml-1 font-medium">
            <li>Pilih "Jenis Penilaian", lalu <button type="button" onclick="downloadTemplateAllExcel()" class="font-bold underline text-indigo-600 hover:text-indigo-800">Download Template Global Ini</button>.</li>
            <li>Isi Nilai di tiap kolom LM.</li>
            <li>Catatan tidak perlu diisi, sistem otomatis ditarik dari Master LM semua pertemuan.</li>
            <li>Upload file yang sudah diisi ke formulir di bawah ini.</li>
          </ol>
        </div>
        <form id="formImportExcelAll" onsubmit="submitImportAll(event)">
          <?= csrf_field() ?>
          <label class="block text-[11px] font-black text-gray-500 dark:!text-slate-400 uppercase tracking-widest mb-2">Upload File Excel (Semua Pertemuan)</label>
          <input type="file" accept=".xlsx, .xls" required class="block w-full text-sm text-gray-500 dark:!text-slate-400 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-bold file:bg-indigo-100 dark:file:!bg-indigo-900/40 file:text-indigo-700 dark:file:!text-indigo-400 hover:file:bg-indigo-200 border border-gray-200 dark:!border-slate-600 rounded-xl mb-5 outline-none cursor-pointer">
          <button type="submit" id="btnSubmitImportAll" class="w-full px-6 py-3.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl transition-all shadow-lg outline-none">Mulai Import Global</button>
        </form>
      </div>
    </div>
  </div>
</div>

<div id="toast" class="fixed top-4 right-4 z-[1000000] flex items-center gap-3 px-4 py-3 bg-white dark:!bg-slate-800 text-gray-800 dark:!text-white border-l-4 border-emerald-500 rounded-xl shadow-2xl transition-all duration-300 transform translate-x-full opacity-0">
  <div class="w-8 h-8 rounded-full bg-emerald-100 dark:!bg-emerald-900/30 flex items-center justify-center flex-shrink-0">
    <svg class="w-5 h-5 text-emerald-600 dark:!text-emerald-400" id="toastIcon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
    </svg>
  </div>
  <span class="font-bold text-sm" id="toastMessage">Sukses!</span>
</div>

<div id="confirmModal" class="fixed inset-0 z-[99999] hidden items-center justify-center p-4">
  <div class="absolute inset-0 bg-gray-950/80 backdrop-blur-sm z-40 transition-opacity" onclick="closeConfirmModal()"></div>
  <div class="relative w-full max-w-md bg-white dark:!bg-slate-800 rounded-3xl shadow-2xl border border-gray-200 dark:!border-slate-700 z-50 transition-all overflow-hidden">
    <div class="bg-gradient-to-r from-red-600 to-red-700 px-6 py-6 flex items-center justify-between rounded-t-3xl transition-colors">
      <h2 class="text-xl font-black text-white uppercase tracking-wider">Konfirmasi</h2>
      <button onclick="closeConfirmModal()" class="text-white/80 hover:text-white bg-white/10 p-2 rounded-xl transition-colors outline-none"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewbox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
        </svg></button>
    </div>
    <div class="p-8">
      <div class="w-16 h-16 bg-red-100 dark:!bg-red-900/30 rounded-full flex items-center justify-center text-red-600 dark:!text-red-400 mx-auto mb-6 shadow-sm"><svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
        </svg></div>
      <p class="text-gray-700 dark:!text-slate-300 font-bold text-center leading-relaxed transition-colors" id="confirmMessage">Apakah Anda yakin?</p>
    </div>
    <div class="px-8 py-6 bg-gray-50 dark:!bg-slate-900/50 border-t border-gray-100 dark:!border-slate-700 flex flex-col sm:flex-row items-center justify-center gap-3 rounded-b-3xl transition-colors">
      <button onclick="closeConfirmModal()" class="w-full sm:w-1/2 px-6 py-3 border-2 border-gray-300 dark:!border-slate-600 rounded-2xl font-black uppercase tracking-widest text-gray-700 dark:!text-slate-300 hover:bg-gray-100 dark:hover:!bg-slate-700 transition-all text-xs outline-none"> Batal </button>
      <button onclick="confirmAction()" class="w-full sm:w-1/2 px-6 py-3 bg-red-600 hover:bg-red-700 text-white rounded-2xl font-black uppercase tracking-widest transition-all shadow-lg shadow-red-600/30 hover:scale-105 text-xs outline-none"> Lanjutkan </button>
    </div>
  </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
  const URL_GET_STUDENTS = "<?= base_url('guru/nilai-formatif/get-students') ?>";
  const URL_GET_GRADES = "<?= base_url('guru/nilai-formatif/get-grades') ?>";
  const URL_SAVE_DATA = "<?= base_url('guru/nilai-formatif/save-nilai') ?>";
  const URL_DOWNLOAD_TEMPLATE = "<?= base_url('guru/nilai-formatif/template') ?>";
  const URL_IMPORT_EXCEL = "<?= base_url('guru/nilai-formatif/import') ?>";
  const URL_DOWNLOAD_TEMPLATE_ALL = "<?= base_url('guru/nilai-formatif/template-all') ?>";
  const URL_IMPORT_EXCEL_ALL = "<?= base_url('guru/nilai-formatif/import-all') ?>";

  // URL BARU UNTUK FETCH JUMLAH PERTEMUAN DINAMIS
  const URL_GET_JUMLAH_LM = "<?= base_url('guru/nilai-formatif/get-jumlah-lm') ?>";
  const URL_GET_ASSIGNMENTS = "<?= base_url('guru/nilai-formatif/get-assignments') ?>";

  let ACTIVE_ROMBEL_ID = <?= $info['rombel_id'] ?? 0 ?>;
  let ACTIVE_MAPEL_ID = <?= $info['mapel_id'] ?? 0 ?>;
  const csrfTokenName = "<?= csrf_token() ?>";
  const csrfTokenHash = "<?= csrf_hash() ?>";
</script>
<script src="<?= base_url('assets/js/GuruMapel/nilai-formatif.js') ?>?v=<?= time() ?>"></script>
<?= $this->endSection() ?>