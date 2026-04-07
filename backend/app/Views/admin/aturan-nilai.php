<?= $this->extend('layout/main') ?>

<?= $this->section('title') ?>
<?= lang('Admin/AturanNilai.page_title') ?> - Rapor Digital SMPIT Ad Durrah
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
  :root {
    --warna-scroll: <?= $color['warna_primary'] ?>;
  }
</style>
<link rel="stylesheet" href="<?= base_url('assets/css/Admin/aturan-nilai.css') ?>">
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="mb-6 w-full min-w-0">
  <div class="flex items-center gap-2 text-sm text-gray-500 dark:text-slate-400 mb-3 transition-colors">
    <span><?= lang('Admin/AturanNilai.breadcrumb_config') ?></span>
    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewbox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
    </svg>
    <span class="text-[<?= $color['warna_primary'] ?>] font-medium"><?= lang('Admin/AturanNilai.page_title') ?></span>
  </div>

  <div class="text-center mb-6 py-4 bg-gradient-to-r from-[<?= $color['warna_secondary'] ?>]/40 dark:from-[<?= $color['warna_primary'] ?>]/10 to-[<?= $color['warna_secondary'] ?>]/20 dark:to-slate-800 rounded-2xl border border-[<?= $color['warna_primary'] ?>]/10 dark:border-[<?= $color['warna_primary'] ?>]/30 shadow-sm transition-colors">
    <p class="text-3xl arabic-text text-[<?= $color['warna_primary'] ?>] mb-2 drop-shadow-sm"><?= lang('Admin/AturanNilai.bismillah') ?></p>
    <p class="text-xs text-gray-600 dark:text-slate-400 italic font-medium transition-colors"><?= lang('Admin/AturanNilai.bismillah_trans') ?></p>
  </div>

  <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
    <div>
      <h1 class="text-2xl md:text-3xl font-bold text-gray-800 dark:text-white mb-2 transition-colors"><?= lang('Admin/AturanNilai.page_title') ?></h1>
      <p class="text-sm md:text-base text-gray-600 dark:text-slate-400 transition-colors">Pengaturan Formula Perhitungan Nilai Rapor Siswa</p>
    </div>
    <div class="flex flex-wrap items-center gap-2">
      <button onclick="showAddRuleModal()" class="px-5 py-2.5 bg-[<?= $color['warna_primary'] ?>]/90 hover:bg-[<?= $color['warna_primary'] ?>] text-white font-bold rounded-xl transition-transform transform hover:-translate-y-0.5 shadow-lg flex items-center gap-2 outline-none" style="box-shadow: 0 10px 15px -3px <?= $color['warna_primary'] ?>40;">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewbox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
        </svg>
        <span><?= lang('Admin/AturanNilai.btn_add_rule') ?></span>
      </button>
      <button onclick="resetToDefault()" class="px-5 py-2.5 bg-white dark:bg-slate-800 border border-gray-300 dark:border-slate-600 text-gray-700 dark:text-slate-300 font-bold rounded-xl hover:bg-gray-50 dark:hover:bg-slate-700 transition-colors shadow-sm flex items-center gap-2 outline-none">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewbox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
        </svg>
        <span class="hidden md:inline"><?= lang('Admin/AturanNilai.btn_reset') ?></span>
      </button>
      <button onclick="showHistory()" class="px-5 py-2.5 bg-white dark:bg-slate-800 border border-gray-300 dark:border-slate-600 text-gray-700 dark:text-slate-300 font-bold rounded-xl hover:bg-gray-50 dark:hover:bg-slate-700 transition-colors shadow-sm flex items-center gap-2 outline-none">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewbox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <span class="hidden md:inline"><?= lang('Admin/AturanNilai.btn_history') ?></span>
      </button>
    </div>
  </div>
</div>

<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6 mb-6 w-full min-w-0">
  <div class="stat-card bg-white dark:bg-slate-800 p-5 rounded-3xl shadow-sm border border-transparent dark:border-slate-700 hover:border-[<?= $color['warna_primary'] ?>] dark:hover:border-[<?= $color['warna_primary'] ?>] transition-colors group">
    <div class="flex items-center justify-between mb-4">
      <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-emerald-500 to-emerald-600 flex items-center justify-center shadow-lg shadow-emerald-500/30 group-hover:scale-105 transition-transform">
        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewbox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
        </svg>
      </div><span class="text-[10px] font-black text-emerald-600 dark:text-emerald-400 uppercase tracking-widest transition-colors">Total Variabel</span>
    </div>
    <p class="text-3xl font-black text-gray-900 dark:text-white mb-1 transition-colors">7</p>
    <p class="text-sm font-medium text-gray-500 dark:text-slate-400 transition-colors">Indikator Nilai</p>
  </div>
  <div class="stat-card bg-white dark:bg-slate-800 p-5 rounded-3xl shadow-sm border border-transparent dark:border-slate-700 hover:border-[<?= $color['warna_primary'] ?>] dark:hover:border-[<?= $color['warna_primary'] ?>] transition-colors group" id="totalBobotCard">
    <div class="flex items-center justify-between mb-4">
      <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-[<?= $color['warna_primary'] ?>] to-emerald-600 flex items-center justify-center shadow-lg group-hover:scale-105 transition-transform" style="box-shadow: 0 10px 15px -3px <?= $color['warna_primary'] ?>40;">
        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewbox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
      </div><span class="text-[10px] font-black text-[<?= $color['warna_primary'] ?>] uppercase tracking-widest transition-colors">Validasi Rumus</span>
    </div>
    <p id="totalBobotValue" class="text-xl font-black text-gray-900 dark:text-white mb-1 transition-colors">STS 100% | SAS 100%</p>
    <div id="bobotStatus"><span class="inline-flex px-2 py-0.5 bg-emerald-50 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400 font-bold text-[10px] rounded border border-emerald-200 dark:border-emerald-800/50 shadow-sm transition-colors">✔️ <?= lang('Admin/AturanNilai.js_valid') ?></span>
    </div>
  </div>
  <div class="stat-card bg-white dark:bg-slate-800 p-5 rounded-3xl shadow-sm border border-transparent dark:border-slate-700 hover:border-blue-500 transition-colors group">
    <div class="flex items-center justify-between mb-4">
      <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center shadow-lg shadow-blue-500/30 group-hover:scale-105 transition-transform">
        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewbox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
        </svg>
      </div><span class="text-[10px] font-black text-blue-600 dark:text-blue-400 uppercase tracking-widest transition-colors"><?= lang('Admin/AturanNilai.curriculum') ?></span>
    </div>
    <p class="text-2xl font-black text-gray-900 dark:text-white mb-1 transition-colors">Merdeka</p>
    <p class="text-sm font-medium text-gray-500 dark:text-slate-400 transition-colors">Kurikulum Nasional</p>
  </div>
  <div class="stat-card bg-white dark:bg-slate-800 p-5 rounded-3xl shadow-sm border border-transparent dark:border-slate-700 hover:border-purple-500 transition-colors group">
    <div class="flex items-center justify-between mb-4">
      <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-purple-500 to-purple-600 flex items-center justify-center shadow-lg shadow-purple-500/30 group-hover:scale-105 transition-transform">
        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewbox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
        </svg>
      </div><span class="text-[10px] font-black text-purple-600 dark:text-purple-400 uppercase tracking-widest transition-colors"><?= lang('Admin/AturanNilai.validation_status') ?></span>
    </div>
    <p class="text-2xl font-black text-emerald-600 dark:text-emerald-400 mb-1 transition-colors"><?= lang('Admin/AturanNilai.validated') ?></p>
    <p class="text-sm font-medium text-gray-500 dark:text-slate-400 transition-colors"><?= lang('Admin/AturanNilai.ready_to_use') ?></p>
  </div>
</div>

<div id="warningAlert" class="bg-amber-50 dark:bg-amber-900/20 border border-amber-300 dark:border-amber-800/50 rounded-2xl p-5 mb-6 hidden transition-colors shadow-sm w-full">
  <div class="flex items-start gap-4">
    <div class="w-12 h-12 rounded-xl bg-amber-500 flex items-center justify-center flex-shrink-0 shadow-sm transition-colors">
      <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewbox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
      </svg>
    </div>
    <div class="flex-1">
      <h3 class="font-bold text-amber-900 dark:text-amber-400 text-lg mb-1 transition-colors"><?= lang('Admin/AturanNilai.warning_title') ?></h3>
      <p class="text-sm font-medium text-amber-800 dark:text-amber-300 mb-4 transition-colors leading-relaxed">Terdapat formulasi rapor yang total bobotnya belum mencapai 100%. Silakan sesuaikan atau gunakan fitur Auto-Balance.</p>
      <button onclick="autoBalance()" class="px-5 py-2.5 bg-amber-600 hover:bg-amber-700 text-white font-bold rounded-xl text-sm transition-transform transform hover:-translate-y-0.5 shadow-md shadow-amber-600/20 outline-none">
        <?= lang('Admin/AturanNilai.btn_auto_balance') ?>
      </button>
    </div>
  </div>
</div>

<div class="w-full mb-8">
  <h2 class="text-xl font-bold text-gray-800 dark:text-white mb-5 flex items-center gap-2 transition-colors">
    <svg class="w-6 h-6 text-[<?= $color['warna_primary'] ?>]" fill="none" stroke="currentColor" viewbox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
    </svg> Formulasi Perhitungan Rapor
  </h2>

  <div class="accordion-item bg-white dark:bg-slate-800 border-2 border-gray-100 dark:border-slate-700 rounded-2xl overflow-hidden mb-4 shadow-sm hover:border-blue-500 dark:hover:border-blue-500 transition-colors">
    <div class="accordion-header p-4 cursor-pointer bg-white dark:bg-slate-800 flex items-center justify-between transition-colors" onclick="toggleAccordion(this)">
      <div class="flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-blue-500 flex items-center justify-center shadow-sm">
          <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewbox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
          </svg>
        </div>
        <div>
          <h3 class="font-bold text-gray-900 dark:text-white text-base transition-colors">Rapor Tengah Semester (STS/PTS)</h3>
          <p class="text-xs font-medium text-gray-500 dark:text-slate-400 mt-0.5 transition-colors">Formulasi nilai untuk rapor sisipan/tengah semester.</p>
        </div>
      </div>
      <div class="flex items-center gap-4">
        <span id="tengahTotal" class="text-lg font-black text-blue-600 dark:text-blue-400">100%</span>
        <svg class="w-5 h-5 text-gray-400 dark:text-slate-500 transition-transform accordion-icon" fill="none" stroke="currentColor" viewbox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
        </svg>
      </div>
    </div>
    <div class="accordion-content open bg-gray-50 dark:bg-slate-900/30 transition-colors border-t border-gray-100 dark:border-slate-700">
      <div class="p-5 grid grid-cols-1 md:grid-cols-3 gap-4">

        <div class="flex flex-col p-4 bg-white dark:bg-slate-800 rounded-xl border border-gray-200 dark:border-slate-700 shadow-sm transition-colors">
          <p class="font-bold text-gray-900 dark:text-white text-sm mb-1">Nilai Harian (NH)</p>
          <p class="text-[11px] text-gray-500 mb-4">Rata-rata nilai tugas/kuis.</p>
          <div class="relative mt-auto">
            <input type="number" class="weight-input tengah-weight w-full pr-8 pl-3 py-2 bg-gray-50 dark:bg-slate-700 border-2 border-gray-200 dark:border-slate-600 text-gray-900 dark:text-white rounded-lg font-bold focus:outline-none focus:border-blue-500 text-right"
              data-kategori="tengah_semester" data-sub="nh" value="<?= $bobot['tengah_semester']['nh'] ?? 35 ?>" min="0" max="100" onchange="updateTotal()">
            <span class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 font-bold text-sm">%</span>
          </div>
        </div>

        <div class="flex flex-col p-4 bg-white dark:bg-slate-800 rounded-xl border border-gray-200 dark:border-slate-700 shadow-sm transition-colors">
          <p class="font-bold text-gray-900 dark:text-white text-sm mb-1">Ulangan Harian (UH)</p>
          <p class="text-[11px] text-gray-500 mb-4">Rata-rata nilai ulangan/sumatif lingkup materi.</p>
          <div class="relative mt-auto">
            <input type="number" class="weight-input tengah-weight w-full pr-8 pl-3 py-2 bg-gray-50 dark:bg-slate-700 border-2 border-gray-200 dark:border-slate-600 text-gray-900 dark:text-white rounded-lg font-bold focus:outline-none focus:border-blue-500 text-right"
              data-kategori="tengah_semester" data-sub="uh" value="<?= $bobot['tengah_semester']['uh'] ?? 35 ?>" min="0" max="100" onchange="updateTotal()">
            <span class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 font-bold text-sm">%</span>
          </div>
        </div>

        <div class="flex flex-col p-4 bg-white dark:bg-slate-800 rounded-xl border border-gray-200 dark:border-slate-700 shadow-sm transition-colors">
          <p class="font-bold text-gray-900 dark:text-white text-sm mb-1">Nilai STS</p>
          <p class="text-[11px] text-gray-500 mb-4">Nilai ujian Sumatif Tengah Semester.</p>
          <div class="relative mt-auto">
            <input type="number" class="weight-input tengah-weight w-full pr-8 pl-3 py-2 bg-gray-50 dark:bg-slate-700 border-2 border-gray-200 dark:border-slate-600 text-gray-900 dark:text-white rounded-lg font-bold focus:outline-none focus:border-blue-500 text-right"
              data-kategori="tengah_semester" data-sub="sts" value="<?= $bobot['tengah_semester']['sts'] ?? 30 ?>" min="0" max="100" onchange="updateTotal()">
            <span class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 font-bold text-sm">%</span>
          </div>
        </div>

      </div>
    </div>
  </div>

  <div class="accordion-item bg-white dark:bg-slate-800 border-2 border-gray-100 dark:border-slate-700 rounded-2xl overflow-hidden mb-4 shadow-sm hover:border-[<?= $color['warna_primary'] ?>] dark:hover:border-[<?= $color['warna_primary'] ?>] transition-colors">
    <div class="accordion-header p-4 cursor-pointer bg-white dark:bg-slate-800 flex items-center justify-between transition-colors" onclick="toggleAccordion(this)">
      <div class="flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-[<?= $color['warna_primary'] ?>] to-emerald-700 flex items-center justify-center shadow-sm">
          <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewbox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
          </svg>
        </div>
        <div>
          <h3 class="font-bold text-gray-900 dark:text-white text-base transition-colors">Rapor Akhir Semester (SAS/PAS)</h3>
          <p class="text-xs font-medium text-gray-500 dark:text-slate-400 mt-0.5 transition-colors">Formulasi nilai untuk rapor kenaikan/akhir semester.</p>
        </div>
      </div>
      <div class="flex items-center gap-4">
        <span id="akhirTotal" class="text-lg font-black text-[<?= $color['warna_primary'] ?>]">100%</span>
        <svg class="w-5 h-5 text-gray-400 dark:text-slate-500 transition-transform accordion-icon" fill="none" stroke="currentColor" viewbox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
        </svg>
      </div>
    </div>
    <div class="accordion-content open bg-gray-50 dark:bg-slate-900/30 transition-colors border-t border-gray-100 dark:border-slate-700">
      <div class="p-5 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">

        <div class="flex flex-col p-4 bg-white dark:bg-slate-800 rounded-xl border border-gray-200 dark:border-slate-700 shadow-sm transition-colors">
          <p class="font-bold text-gray-900 dark:text-white text-sm mb-1">Nilai Harian (NH)</p>
          <div class="relative mt-auto pt-3">
            <input type="number" class="weight-input akhir-weight w-full pr-8 pl-3 py-2 bg-gray-50 dark:bg-slate-700 border-2 border-gray-200 dark:border-slate-600 text-gray-900 dark:text-white rounded-lg font-bold focus:outline-none focus:border-[<?= $color['warna_primary'] ?>] text-right"
              data-kategori="akhir_semester" data-sub="nh" value="<?= $bobot['akhir_semester']['nh'] ?? 30 ?>" min="0" max="100" onchange="updateTotal()">
            <span class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 font-bold text-sm">%</span>
          </div>
        </div>

        <div class="flex flex-col p-4 bg-white dark:bg-slate-800 rounded-xl border border-gray-200 dark:border-slate-700 shadow-sm transition-colors">
          <p class="font-bold text-gray-900 dark:text-white text-sm mb-1">Ulangan Harian (UH)</p>
          <div class="relative mt-auto pt-3">
            <input type="number" class="weight-input akhir-weight w-full pr-8 pl-3 py-2 bg-gray-50 dark:bg-slate-700 border-2 border-gray-200 dark:border-slate-600 text-gray-900 dark:text-white rounded-lg font-bold focus:outline-none focus:border-[<?= $color['warna_primary'] ?>] text-right"
              data-kategori="akhir_semester" data-sub="uh" value="<?= $bobot['akhir_semester']['uh'] ?? 30 ?>" min="0" max="100" onchange="updateTotal()">
            <span class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 font-bold text-sm">%</span>
          </div>
        </div>

        <div class="flex flex-col p-4 bg-white dark:bg-slate-800 rounded-xl border border-gray-200 dark:border-slate-700 shadow-sm transition-colors">
          <p class="font-bold text-gray-900 dark:text-white text-sm mb-1">Nilai STS</p>
          <div class="relative mt-auto pt-3">
            <input type="number" class="weight-input akhir-weight w-full pr-8 pl-3 py-2 bg-gray-50 dark:bg-slate-700 border-2 border-gray-200 dark:border-slate-600 text-gray-900 dark:text-white rounded-lg font-bold focus:outline-none focus:border-[<?= $color['warna_primary'] ?>] text-right"
              data-kategori="akhir_semester" data-sub="sts" value="<?= $bobot['akhir_semester']['sts'] ?? 15 ?>" min="0" max="100" onchange="updateTotal()">
            <span class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 font-bold text-sm">%</span>
          </div>
        </div>

        <div class="flex flex-col p-4 bg-white dark:bg-slate-800 rounded-xl border border-gray-200 dark:border-slate-700 shadow-sm transition-colors">
          <p class="font-bold text-gray-900 dark:text-white text-sm mb-1">Nilai SAS</p>
          <div class="relative mt-auto pt-3">
            <input type="number" class="weight-input akhir-weight w-full pr-8 pl-3 py-2 bg-gray-50 dark:bg-slate-700 border-2 border-gray-200 dark:border-slate-600 text-gray-900 dark:text-white rounded-lg font-bold focus:outline-none focus:border-[<?= $color['warna_primary'] ?>] text-right"
              data-kategori="akhir_semester" data-sub="sas" value="<?= $bobot['akhir_semester']['sas'] ?? 25 ?>" min="0" max="100" onchange="updateTotal()">
            <span class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 font-bold text-sm">%</span>
          </div>
        </div>

      </div>
    </div>
  </div>

</div>

<div class="flex flex-col sm:flex-row justify-end gap-4 pt-6 mt-4 border-t border-gray-100 dark:border-slate-700 transition-colors mb-10">
  <button onclick="saveChanges()" class="px-8 py-3.5 bg-[<?= $color['warna_primary'] ?>] hover:brightness-90 text-white font-bold rounded-xl transition-transform transform hover:-translate-y-0.5 shadow-lg outline-none flex items-center justify-center gap-2" style="box-shadow: 0 10px 15px -3px <?= $color['warna_primary'] ?>40;">
    <svg class="w-5 h-5 animate-pulse" fill="none" stroke="currentColor" viewbox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
    </svg> <?= lang('Admin/AturanNilai.btn_save_changes') ?>
  </button>
</div>

<div class="mb-6 w-full min-w-0">
  <h2 class="text-xl font-bold text-gray-800 dark:text-white mb-5 flex items-center gap-2 transition-colors">
    <svg class="w-6 h-6 text-[<?= $color['warna_primary'] ?>]" fill="none" stroke="currentColor" viewbox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
    </svg> <?= lang('Admin/AturanNilai.grading_rules') ?>
  </h2>

  <div class="bg-white dark:bg-slate-800 rounded-3xl border border-gray-100 dark:border-slate-700 shadow-sm overflow-hidden transition-colors w-full min-w-0">
    <div class="overflow-x-auto custom-scrollbar w-full">
      <table class="w-full text-left border-collapse min-w-max">
        <thead class="bg-[<?= $color['warna_primary'] ?>] text-white">
          <tr>
            <th class="px-6 py-4 font-black text-[11px] uppercase tracking-widest"><?= lang('Admin/AturanNilai.th_range') ?></th>
            <th class="px-6 py-4 font-black text-[11px] uppercase tracking-widest"><?= lang('Admin/AturanNilai.th_predicate') ?></th>
            <th class="px-6 py-4 font-black text-[11px] uppercase tracking-widest w-96"><?= lang('Admin/AturanNilai.th_desc') ?></th>
            <th class="px-6 py-4 font-black text-[11px] uppercase tracking-widest text-center"><?= lang('Admin/AturanNilai.th_status') ?></th>
            <th class="px-6 py-4 font-black text-[11px] uppercase tracking-widest text-center"><?= lang('Admin/AturanNilai.th_action') ?></th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-100 dark:divide-slate-700/50 transition-colors">
          <?php if (empty($list_aturan)): ?>
            <tr>
              <td colspan="5" class="text-center p-12 text-gray-500 dark:text-slate-400 font-medium transition-colors"><?= lang('Admin/AturanNilai.empty_rules') ?></td>
            </tr>
          <?php else: ?>
            <?php foreach ($list_aturan as $at): ?>
              <tr class="hover:bg-gray-50 dark:hover:bg-slate-700/30 transition-colors group">
                <td class="px-6 py-4 font-bold text-gray-900 dark:text-white transition-colors">
                  <span class="bg-gray-100 dark:bg-slate-700 px-3 py-1 rounded-lg border border-gray-200 dark:border-slate-600 transition-colors"><?= $at['nilai_min'] ?> - <?= $at['nilai_max'] ?></span>
                </td>
                <td class="px-6 py-4">
                  <span class="inline-flex items-center justify-center w-10 h-10 bg-<?= $at['warna_badge'] ?>-100 dark:bg-<?= $at['warna_badge'] ?>-900/30 text-<?= $at['warna_badge'] ?>-700 dark:text-<?= $at['warna_badge'] ?>-400 rounded-xl font-black text-lg border border-<?= $at['warna_badge'] ?>-200 dark:border-<?= $at['warna_badge'] ?>-800/50 shadow-sm group-hover:scale-110 transition-transform">
                    <?= $at['predikat'] ?>
                  </span>
                </td>
                <td class="px-6 py-4 text-sm font-medium text-gray-600 dark:text-slate-300 leading-relaxed transition-colors"><?= $at['deskripsi_predikat'] ?></td>
                <td class="px-6 py-4 text-center">
                  <?php if ($at['is_active']): ?>
                    <span class="inline-flex px-3 py-1 bg-emerald-50 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 text-[10px] font-black uppercase tracking-wider rounded border border-emerald-200 dark:border-emerald-800/50 shadow-sm transition-colors"><?= lang('Admin/AturanNilai.badge_active') ?></span>
                  <?php else: ?>
                    <span class="inline-flex px-3 py-1 bg-red-50 dark:bg-red-900/30 text-red-600 dark:text-red-400 text-[10px] font-black uppercase tracking-wider rounded border border-red-200 dark:border-red-800/50 shadow-sm transition-colors"><?= lang('Admin/AturanNilai.badge_inactive') ?></span>
                  <?php endif; ?>
                </td>
                <td class="px-6 py-4 text-center">
                  <div class="flex items-center justify-center gap-2">
                    <button onclick="deleteAturan(<?= $at['id'] ?>)" class="p-2.5 bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400 hover:bg-red-600 hover:text-white dark:hover:bg-red-600 dark:hover:text-white rounded-xl transition-all shadow-sm outline-none transform hover:scale-105" title="<?= lang('Admin/AturanNilai.btn_delete') ?>">
                      <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                      </svg>
                    </button>
                  </div>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<div class="hidden 
    bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400 border-emerald-200 dark:border-emerald-800/50 hover:bg-emerald-600 hover:text-white dark:hover:bg-emerald-600 dark:hover:text-white
    bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400 border-blue-200 dark:border-blue-800/50 hover:bg-blue-600 hover:text-white dark:hover:bg-blue-600 dark:hover:text-white
    bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-400 border-amber-200 dark:border-amber-800/50 hover:bg-amber-600 hover:text-white dark:hover:bg-amber-600 dark:hover:text-white
    bg-orange-100 dark:bg-orange-900/30 text-orange-700 dark:text-orange-400 border-orange-200 dark:border-orange-800/50 hover:bg-orange-600 hover:text-white dark:hover:bg-orange-600 dark:hover:text-white
    bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400 border-red-200 dark:border-red-800/50 hover:bg-red-600 hover:text-white dark:hover:bg-red-600 dark:hover:text-white
"></div>

<?= $this->endSection() ?>

<?= $this->section('modals') ?>

<div id="addRuleModal" class="fixed inset-0 hidden flex items-center justify-center p-4 z-[99999]">
  <div class="absolute inset-0 bg-gray-950/70 backdrop-blur-sm transition-opacity pointer-events-auto" onclick="closeAddRuleModal()"></div>

  <div class="relative w-full max-w-xl bg-white dark:bg-slate-800 rounded-3xl shadow-2xl flex flex-col max-h-[90vh] pointer-events-auto border border-transparent dark:border-slate-700 transition-colors transform overflow-hidden md:ml-64">

    <div class="p-6 border-b border-gray-100 dark:border-slate-700 bg-gray-50 dark:bg-slate-900/50 z-20 flex-shrink-0 transition-colors">
      <div class="flex items-start justify-between">
        <div>
          <h3 class="text-xl font-black text-gray-900 dark:text-white transition-colors"><?= lang('Admin/AturanNilai.modal_add_title') ?></h3>
          <p class="text-sm font-medium text-gray-500 dark:text-slate-400 mt-1 transition-colors"><?= lang('Admin/AturanNilai.modal_add_desc') ?></p>
        </div>
        <button type="button" onclick="closeAddRuleModal()" class="p-2 hover:bg-gray-200 dark:hover:bg-slate-700 rounded-full transition-colors cursor-pointer outline-none">
          <svg class="w-6 h-6 text-gray-500 dark:text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
      </div>
    </div>

    <div class="flex-1 overflow-y-auto custom-scrollbar p-6 md:p-8 relative z-10 bg-white dark:bg-slate-800 transition-colors">
      <form id="addRuleForm" class="space-y-6">

        <div>
          <label for="predikatInput" class="block text-[11px] font-black text-gray-500 dark:text-slate-400 uppercase tracking-widest mb-2 transition-colors">
            <?= lang('Admin/AturanNilai.lbl_predicate') ?> <span class="text-red-500">*</span>
          </label>
          <input type="text" id="predikatInput" name="predikat" maxlength="2" placeholder="<?= lang('Admin/AturanNilai.ph_predicate') ?>" class="w-full px-4 py-3 bg-gray-50 dark:bg-slate-700 border-2 border-gray-200 dark:border-slate-600 text-gray-900 dark:text-white rounded-xl focus:outline-none focus:ring-2 focus:ring-[<?= $color['warna_primary'] ?>] transition-colors outline-none shadow-sm uppercase font-bold" required>
          <p class="text-[10px] font-medium text-gray-400 dark:text-slate-500 mt-1.5 transition-colors"><?= lang('Admin/AturanNilai.hint_predicate') ?></p>
        </div>

        <div>
          <label for="deskripsiPredikatInput" class="block text-[11px] font-black text-gray-500 dark:text-slate-400 uppercase tracking-widest mb-2 transition-colors">
            <?= lang('Admin/AturanNilai.lbl_desc_pred') ?> <span class="text-red-500">*</span>
          </label>
          <input type="text" id="deskripsiPredikatInput" name="deskripsi_predikat" placeholder="<?= lang('Admin/AturanNilai.ph_desc_pred') ?>" class="w-full px-4 py-3 bg-gray-50 dark:bg-slate-700 border-2 border-gray-200 dark:border-slate-600 text-gray-900 dark:text-white rounded-xl focus:outline-none focus:ring-2 focus:ring-[<?= $color['warna_primary'] ?>] transition-colors outline-none shadow-sm font-medium" required>
        </div>

        <div class="grid grid-cols-2 gap-5">
          <div>
            <label for="nilaiMinInput" class="block text-[11px] font-black text-gray-500 dark:text-slate-400 uppercase tracking-widest mb-2 transition-colors">
              <?= lang('Admin/AturanNilai.lbl_min_val') ?> <span class="text-red-500">*</span>
            </label>
            <input type="number" id="nilaiMinInput" name="nilai_min" min="0" max="100" placeholder="0" class="w-full px-4 py-3 bg-gray-50 dark:bg-slate-700 border-2 border-gray-200 dark:border-slate-600 text-gray-900 dark:text-white rounded-xl focus:outline-none focus:ring-2 focus:ring-[<?= $color['warna_primary'] ?>] transition-colors outline-none shadow-sm font-bold text-center" required>
          </div>
          <div>
            <label for="nilaiMaxInput" class="block text-[11px] font-black text-gray-500 dark:text-slate-400 uppercase tracking-widest mb-2 transition-colors">
              <?= lang('Admin/AturanNilai.lbl_max_val') ?> <span class="text-red-500">*</span>
            </label>
            <input type="number" id="nilaiMaxInput" name="nilai_max" min="0" max="100" placeholder="100" class="w-full px-4 py-3 bg-gray-50 dark:bg-slate-700 border-2 border-gray-200 dark:border-slate-600 text-gray-900 dark:text-white rounded-xl focus:outline-none focus:ring-2 focus:ring-[<?= $color['warna_primary'] ?>] transition-colors outline-none shadow-sm font-bold text-center" required>
          </div>
        </div>

        <div>
          <label for="deskripsiPencapaianInput" class="block text-[11px] font-black text-gray-500 dark:text-slate-400 uppercase tracking-widest mb-2 transition-colors">
            <?= lang('Admin/AturanNilai.lbl_desc_comp') ?> <span class="text-red-500">*</span>
          </label>
          <textarea id="deskripsiPencapaianInput" name="deskripsi_kompetensi" rows="3" placeholder="<?= lang('Admin/AturanNilai.ph_desc_comp') ?>" class="w-full px-4 py-3 bg-gray-50 dark:bg-slate-700 border-2 border-gray-200 dark:border-slate-600 text-gray-900 dark:text-white rounded-xl focus:outline-none focus:ring-2 focus:ring-[<?= $color['warna_primary'] ?>] transition-colors resize-none shadow-sm outline-none font-medium" required></textarea>
        </div>

        <div>
          <label for="warnaInput" class="flex items-center justify-between text-[11px] font-black text-gray-500 dark:text-slate-400 uppercase tracking-widest mb-3 transition-colors">
            <span><?= lang('Admin/AturanNilai.lbl_badge_color') ?></span>
            <span class="text-[9px] bg-gray-100 dark:bg-slate-700 px-2 py-0.5 rounded"><?= lang('Admin/AturanNilai.lbl_optional') ?></span>
          </label>
          <div class="grid grid-cols-5 gap-3">
            <button type="button" onclick="selectColor('emerald')" class="color-option w-full h-12 rounded-xl bg-emerald-500 hover:scale-105 transition-transform border-4 border-transparent focus:outline-none focus:ring-2 focus:ring-offset-2 dark:focus:ring-offset-slate-800 focus:ring-emerald-500 shadow-sm" data-color="emerald"></button>
            <button type="button" onclick="selectColor('blue')" class="color-option w-full h-12 rounded-xl bg-blue-500 hover:scale-105 transition-transform border-4 border-transparent focus:outline-none focus:ring-2 focus:ring-offset-2 dark:focus:ring-offset-slate-800 focus:ring-blue-500 shadow-sm" data-color="blue"></button>
            <button type="button" onclick="selectColor('amber')" class="color-option w-full h-12 rounded-xl bg-amber-500 hover:scale-105 transition-transform border-4 border-transparent focus:outline-none focus:ring-2 focus:ring-offset-2 dark:focus:ring-offset-slate-800 focus:ring-amber-500 shadow-sm" data-color="amber"></button>
            <button type="button" onclick="selectColor('orange')" class="color-option w-full h-12 rounded-xl bg-orange-500 hover:scale-105 transition-transform border-4 border-transparent focus:outline-none focus:ring-2 focus:ring-offset-2 dark:focus:ring-offset-slate-800 focus:ring-orange-500 shadow-sm" data-color="orange"></button>
            <button type="button" onclick="selectColor('red')" class="color-option w-full h-12 rounded-xl bg-red-500 hover:scale-105 transition-transform border-4 border-transparent focus:outline-none focus:ring-2 focus:ring-offset-2 dark:focus:ring-offset-slate-800 focus:ring-red-500 shadow-sm" data-color="red"></button>
          </div>
          <input type="hidden" id="selectedColor" value="emerald">
        </div>

        <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-slate-700/50 border border-gray-100 dark:border-slate-700 rounded-xl transition-colors">
          <div>
            <p class="font-bold text-gray-900 dark:text-white text-sm transition-colors"><?= lang('Admin/AturanNilai.rule_status') ?></p>
            <p class="text-[11px] font-medium text-gray-500 dark:text-slate-400 mt-0.5 transition-colors"><?= lang('Admin/AturanNilai.rule_status_desc') ?></p>
          </div>
          <label class="toggle-switch relative inline-flex items-center cursor-pointer group">
            <input type="checkbox" id="statusToggle" class="sr-only peer" checked>
            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-emerald-300 dark:peer-focus:ring-emerald-800 rounded-full peer dark:bg-slate-600 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-500 group-hover:after:scale-95 shadow-inner"></div>
          </label>
        </div>

        <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800/50 rounded-xl p-4 flex items-start gap-4 transition-colors">
          <div class="w-8 h-8 rounded-full bg-blue-100 dark:bg-blue-800/50 flex items-center justify-center flex-shrink-0 mt-0.5 shadow-sm">
            <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
          </div>
          <div class="text-sm font-medium text-blue-800 dark:text-blue-300 leading-relaxed transition-colors">
            <p class="font-black mb-1.5 uppercase tracking-wider text-[10px]"><?= lang('Admin/AturanNilai.tips_title') ?></p>
            <ul class="list-disc list-outside ml-4 space-y-1 text-[11px]">
              <li><?= lang('Admin/AturanNilai.tip_1') ?></li>
              <li><?= lang('Admin/AturanNilai.tip_2') ?></li>
            </ul>
          </div>
        </div>

        <div class="flex gap-3 pt-5 border-t border-gray-100 dark:border-slate-700 bg-white dark:bg-slate-800 sticky bottom-0 z-50 pb-2 transition-colors">
          <button type="button" onclick="closeAddRuleModal()" class="flex-1 px-6 py-3.5 bg-gray-100 dark:bg-slate-700 border-2 border-transparent dark:border-slate-600 hover:bg-gray-200 dark:hover:bg-slate-600 text-gray-800 dark:text-white font-bold rounded-xl transition-colors cursor-pointer outline-none shadow-sm">
            <?= lang('Admin/AturanNilai.btn_cancel') ?>
          </button>
          <button type="submit" class="flex-1 px-6 py-3.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl transition-transform transform hover:-translate-y-0.5 shadow-lg shadow-emerald-600/30 flex items-center justify-center gap-2 cursor-pointer outline-none">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
            </svg> <?= lang('Admin/AturanNilai.btn_save_rule') ?>
          </button>
        </div>

      </form>
    </div>
  </div>
</div>

<div id="historyModal" class="fixed inset-0 hidden flex items-center justify-center p-4 z-[99999]">
  <div class="absolute inset-0 bg-gray-950/70 backdrop-blur-sm transition-opacity" onclick="closeHistoryModal()"></div>

  <div class="relative w-full max-w-lg bg-white dark:bg-slate-800 rounded-3xl shadow-2xl flex flex-col max-h-[80vh] pointer-events-auto border border-transparent dark:border-slate-700 transition-colors transform overflow-hidden md:ml-64">

    <div class="p-6 border-b border-gray-100 dark:border-slate-700 flex justify-between items-center bg-gray-50 dark:bg-slate-900/50 transition-colors">
      <h3 class="text-xl font-black text-gray-900 dark:text-white transition-colors flex items-center gap-2">
        <svg class="w-6 h-6 text-[<?= $color['warna_primary'] ?>]" fill="none" stroke="currentColor" viewbox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <?= lang('Admin/AturanNilai.modal_hist_title') ?>
      </h3>
      <button type="button" onclick="closeHistoryModal()" class="p-2 text-gray-400 hover:text-gray-600 dark:hover:text-slate-300 hover:bg-gray-200 dark:hover:bg-slate-700 rounded-full transition-colors outline-none cursor-pointer">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg>
      </button>
    </div>

    <div class="overflow-y-auto custom-scrollbar flex-1 bg-white dark:bg-slate-800 transition-colors" id="historyListContainer">
      <div class="p-12 text-center text-gray-500 dark:text-slate-400 font-medium flex flex-col items-center gap-3 transition-colors">
        <svg class="w-8 h-8 animate-spin text-[<?= $color['warna_primary'] ?>]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
          <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
          <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        <?= lang('Admin/AturanNilai.loading_data') ?>
      </div>
    </div>

    <div class="p-5 border-t border-gray-100 dark:border-slate-700 bg-gray-50 dark:bg-slate-900/50 transition-colors">
      <button type="button" onclick="closeHistoryModal()" class="w-full px-6 py-3.5 bg-white dark:bg-slate-700 border-2 border-gray-300 dark:border-slate-600 rounded-xl text-sm font-bold text-gray-700 dark:text-slate-300 hover:bg-gray-100 dark:hover:bg-slate-600 transition-colors shadow-sm outline-none">
        <?= lang('Admin/AturanNilai.btn_close') ?>
      </button>
    </div>

  </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
  const BASE_URL = "<?= base_url() ?>";
</script>
<script src="<?= base_url('assets/js/Admin/aturan-nilai.js') ?>?v=<?= time() ?>"></script>
<?= $this->endSection() ?>