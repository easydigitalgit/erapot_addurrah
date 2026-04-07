<?= $this->extend('layout/main') ?>
<?php
// Fungsi untuk memformat tanggal
function formatTanggalIndo($tanggal)
{
  if (empty($tanggal) || $tanggal == '0000-00-00') return '-';
  // Menyesuaikan format standar PHP yang bisa menangkap locale jika dibutuhkan
  // Namun untuk amannya kita biarkan ini tetap output format default aplikasi
  $bulan = [
    1 => 'Januari',
    'Februari',
    'Maret',
    'April',
    'Mei',
    'Juni',
    'Juli',
    'Agustus',
    'September',
    'Oktober',
    'November',
    'Desember'
  ];
  $pecahkan = explode('-', date('Y-m-d', strtotime($tanggal)));
  return $pecahkan[2] . ' ' . $bulan[(int)$pecahkan[1]] . ' ' . $pecahkan[0];
}
?>
<?= $this->section('title') ?>
<?= lang('Admin/TahunAjaran.page_title_browser') ?>
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
    :root { --warna-scroll: <?= $color['warna_primary'] ?>; }
</style>
<link rel="stylesheet" href="<?= base_url('assets/css/Admin/tahun-ajaran.css') ?>">
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="mb-6">
  <div class="flex items-center gap-2 text-sm text-gray-500 dark:text-slate-400 mb-2 transition-colors">
    <span><?= lang('Admin/TahunAjaran.academic_config') ?></span>
    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewbox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
    </svg><span class="text-[<?= $color['warna_primary'] ?>] dark:text-[<?= $color['warna_primary'] ?>] font-medium"><?= lang('Admin/TahunAjaran.page_title') ?></span>
  </div>
  <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
    <div>
      <div class="flex items-center gap-3 mb-2">
        <h1 id="page-title" class="text-xl md:text-3xl font-bold text-gray-800 dark:text-white transition-colors"><?= lang('Admin/TahunAjaran.page_title') ?></h1>
        <span class="badge bg-gradient-to-br from-[<?= $color['warna_primary'] ?>] to-[<?= $color['warna_primary'] ?>]/80 badge-active text-white px-2 py-0.5 rounded shadow-sm text-[10px] font-bold tracking-wider"><?= lang('Admin/TahunAjaran.badge_active') ?></span>
      </div>
      <p id="page-subtitle" class="text-sm md:text-base text-gray-600 dark:text-slate-400 transition-colors"><?= lang('Admin/TahunAjaran.page_subtitle') ?></p>
    </div>
    <div class="flex flex-wrap items-center gap-2 md:gap-3">
      <button onclick="showAddYearModal()" class="px-4 py-2.5 bg-[<?= $color['warna_primary'] ?>] hover:bg-[<?= $color['warna_primary'] ?>]/90 text-white font-semibold rounded-xl transition-all shadow-lg shadow-[<?= $color['warna_primary'] ?>]/20 flex items-center gap-2 transform hover:-translate-y-0.5 outline-none">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewbox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
        </svg><span><?= lang('Admin/TahunAjaran.btn_create_new') ?></span> </button>
    </div>
  </div>
</div>

<div class="primary-card border border-[<?= $color['warna_primary'] ?>]/30 dark:border-[<?= $color['warna_primary'] ?>]/50 bg-[<?= $color['warna_secondary'] ?>]/30 dark:bg-slate-800 rounded-3xl p-6 md:p-8 mb-6 shadow-sm transition-colors">
  <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
    <div class="flex-1">
      <div class="flex items-center gap-3 mb-4">
        <div class="stat-icon w-12 h-12 rounded-xl bg-gradient-to-br from-[<?= $color['warna_primary'] ?>] to-[<?= $color['warna_primary'] ?>]/70 flex items-center justify-center shadow-lg">
          <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewbox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
          </svg>
        </div>
        <div>
          <p class="text-sm font-bold text-[<?= $color['warna_primary'] ?>] uppercase tracking-wide"><?= lang('Admin/TahunAjaran.current_active_year') ?></p>
          <h2 class="text-3xl md:text-4xl font-black text-gray-900 dark:text-white">
            <?= $activeYear ? $activeYear['tahun'] : lang('Admin/TahunAjaran.none') ?>
          </h2>
        </div>
      </div>

      <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
        <div class="bg-white dark:bg-slate-700/50 rounded-2xl p-4 border border-emerald-100 dark:border-emerald-800/30 shadow-sm transition-colors">
          <p class="text-xs font-semibold text-gray-500 dark:text-slate-400 uppercase tracking-wider mb-1"><?= lang('Admin/TahunAjaran.active_semester') ?></p>
          <div class="flex items-center gap-2">
            <div class="w-3 h-3 rounded-full bg-[<?= $color['warna_primary'] ?>] animate-pulse shadow-[0_0_8px_<?= $color['warna_primary'] ?>]"></div>
            <p class="text-xl font-bold text-gray-900 dark:text-white capitalize">
              <?= $activeYear ? $activeYear['semester'] : '-' ?>
            </p>
          </div>
        </div>
        <div class="bg-white dark:bg-slate-700/50 rounded-2xl p-4 border border-emerald-100 dark:border-emerald-800/30 shadow-sm transition-colors">
          <p class="text-xs font-semibold text-gray-500 dark:text-slate-400 uppercase tracking-wider mb-1"><?= lang('Admin/TahunAjaran.system_status') ?></p>
          <p class="text-xl font-bold text-[<?= $color['warna_primary'] ?>]"><?= lang('Admin/TahunAjaran.active_running') ?></p>
        </div>
      </div>

      <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
        <div>
          <p class="text-xs font-medium text-gray-600 dark:text-slate-400 mb-2"><?= lang('Admin/TahunAjaran.start_date') ?></p>
          <p class="text-base font-bold text-gray-900 dark:text-white">
            <?= ($activeYear && !empty($activeYear['tgl_mulai'])) ? formatTanggalIndo($activeYear['tgl_mulai']) : lang('Admin/TahunAjaran.not_set') ?>
          </p>
        </div>
        <div>
          <p class="text-xs font-medium text-gray-600 dark:text-slate-400 mb-2"><?= lang('Admin/TahunAjaran.end_date') ?></p>
          <p class="text-base font-bold text-gray-900 dark:text-white">
            <?= ($activeYear && !empty($activeYear['tgl_akhir'])) ? formatTanggalIndo($activeYear['tgl_akhir']) : lang('Admin/TahunAjaran.not_set') ?>
          </p>
        </div>
      </div>
      <div class="mb-4">
        <div class="flex items-center justify-between mb-2">
          <p class="text-sm font-semibold text-gray-700 dark:text-slate-300"><?= lang('Admin/TahunAjaran.semester_progress') ?> <?= $activeYear ? $activeYear['semester'] : '' ?></p>
          <p class="text-sm font-bold text-[<?= $color['warna_primary'] ?>]"><?= $progressPercent ?>%</p>
        </div>
        <div class="w-full bg-white dark:bg-slate-700 rounded-full h-2.5 overflow-hidden border border-gray-100 dark:border-slate-600 shadow-inner">
          <div class="h-2.5 rounded-full transition-all duration-1000 ease-out relative" style="width: <?= $progressPercent ?>%; background-color: <?= $color['warna_primary'] ?>;">
            <div class="absolute inset-0 bg-gradient-to-r from-transparent to-white/30"></div>
          </div>
        </div>
        <p class="text-xs text-gray-500 dark:text-slate-400 mt-2"><?= $estimasiText ?></p>
      </div>

      <div class="bg-white/50 dark:bg-slate-700/50 border border-[<?= $color['warna_primary'] ?>]/40 dark:border-[<?= $color['warna_primary'] ?>]/30 rounded-xl p-4 flex items-start gap-3 transition-colors shadow-sm">
        <svg class="w-5 h-5 text-[<?= $color['warna_primary'] ?>] flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewbox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <div>
          <p class="text-sm font-bold text-[<?= $color['warna_primary'] ?>]"><?= lang('Admin/TahunAjaran.important_note') ?></p>
          <p class="text-xs text-gray-600 dark:text-slate-300 mt-1 leading-relaxed"><?= lang('Admin/TahunAjaran.important_note_desc') ?></p>
        </div>
      </div>
    </div>

    <div class="flex flex-col gap-3 lg:w-64">
      <button onclick="showChangeSemesterModal('<?= $activeYear ? $activeYear['semester'] : '' ?>')" class="w-full px-5 py-3.5 bg-white dark:bg-slate-700 border-2 border-[<?= $color['warna_primary'] ?>] dark:border-[<?= $color['warna_primary'] ?>]/80 text-[<?= $color['warna_primary'] ?>] font-bold rounded-xl hover:bg-[<?= $color['warna_primary'] ?>]/10 dark:hover:bg-slate-600 transition-all shadow-sm hover:shadow flex items-center justify-center gap-2 outline-none">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewbox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
        </svg> <?= lang('Admin/TahunAjaran.btn_change_semester') ?> </button>
      <button onclick="showImpactModal()" class="w-full px-5 py-3.5 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl transition-all shadow-md hover:shadow-lg flex items-center justify-center gap-2 outline-none">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewbox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg> <?= lang('Admin/TahunAjaran.btn_impact_system') ?> </button>
      <button onclick="showDeactivateModal()" class="w-full px-5 py-3.5 bg-white dark:bg-slate-700 border-2 border-red-300 dark:border-red-800 text-red-600 dark:text-red-400 font-bold rounded-xl hover:bg-red-50 dark:hover:bg-slate-600 transition-all shadow-sm flex items-center justify-center gap-2 outline-none">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewbox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
        </svg> <?= lang('Admin/TahunAjaran.btn_deactivate') ?> </button>
    </div>
  </div>
</div>

<div class="impact-card border border-blue-200 dark:border-blue-900/50 bg-blue-50/50 dark:bg-slate-800 rounded-3xl p-6 mb-8 transition-colors shadow-sm">
  <div class="flex flex-col md:flex-row items-start gap-5">
    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-blue-500 to-blue-700 shadow-lg flex-shrink-0 flex items-center justify-center">
      <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewbox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
      </svg>
    </div>
    <div class="flex-1 w-full">
      <h3 class="font-bold text-gray-900 dark:text-white text-lg mb-4"><?= lang('Admin/TahunAjaran.impact_title') ?></h3>
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="flex items-start gap-3 bg-white dark:bg-slate-700 p-3 rounded-xl border border-gray-100 dark:border-slate-600 shadow-sm">
          <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewbox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
          <div>
            <p class="text-sm font-bold text-gray-800 dark:text-white"><?= lang('Admin/TahunAjaran.impact_1_title') ?></p>
            <p class="text-[11px] text-gray-500 dark:text-slate-400 mt-0.5"><?= lang('Admin/TahunAjaran.impact_1_desc') ?></p>
          </div>
        </div>
        <div class="flex items-start gap-3 bg-white dark:bg-slate-700 p-3 rounded-xl border border-gray-100 dark:border-slate-600 shadow-sm">
          <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewbox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
          <div>
            <p class="text-sm font-bold text-gray-800 dark:text-white"><?= lang('Admin/TahunAjaran.impact_2_title') ?></p>
            <p class="text-[11px] text-gray-500 dark:text-slate-400 mt-0.5"><?= lang('Admin/TahunAjaran.impact_2_desc') ?></p>
          </div>
        </div>
        <div class="flex items-start gap-3 bg-white dark:bg-slate-700 p-3 rounded-xl border border-gray-100 dark:border-slate-600 shadow-sm">
          <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewbox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
          <div>
            <p class="text-sm font-bold text-gray-800 dark:text-white"><?= lang('Admin/TahunAjaran.impact_3_title') ?></p>
            <p class="text-[11px] text-gray-500 dark:text-slate-400 mt-0.5"><?= lang('Admin/TahunAjaran.impact_3_desc') ?></p>
          </div>
        </div>
        <div class="flex items-start gap-3 bg-white dark:bg-slate-700 p-3 rounded-xl border border-gray-100 dark:border-slate-600 shadow-sm">
          <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewbox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
          <div>
            <p class="text-sm font-bold text-gray-800 dark:text-white"><?= lang('Admin/TahunAjaran.impact_4_title') ?></p>
            <p class="text-[11px] text-gray-500 dark:text-slate-400 mt-0.5"><?= lang('Admin/TahunAjaran.impact_4_desc') ?></p>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-gray-100 dark:border-slate-700 overflow-hidden transition-colors">
  <div class="px-6 py-5 border-b border-gray-100 dark:border-slate-700 flex items-center justify-between transition-colors">
    <div>
      <h3 class="text-lg font-bold text-gray-900 dark:text-white"><?= lang('Admin/TahunAjaran.history_title') ?></h3>
      <p class="text-sm text-gray-500 dark:text-slate-400 mt-1"><?= lang('Admin/TahunAjaran.history_subtitle') ?></p>
    </div>
  </div>
  <div class="overflow-x-auto custom-scrollbar">
    <table class="w-full min-w-max">
      <thead class="bg-gray-50 dark:bg-slate-900/50 border-b border-gray-100 dark:border-slate-700 transition-colors">
        <tr>
          <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 dark:text-slate-400 uppercase tracking-wider"><?= lang('Admin/TahunAjaran.th_academic_year') ?></th>
          <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 dark:text-slate-400 uppercase tracking-wider"><?= lang('Admin/TahunAjaran.th_semester') ?></th>
          <th class="px-6 py-4 text-center text-xs font-bold text-gray-500 dark:text-slate-400 uppercase tracking-wider"><?= lang('Admin/TahunAjaran.th_status') ?></th>
          <th class="px-6 py-4 text-center text-xs font-bold text-gray-500 dark:text-slate-400 uppercase tracking-wider"><?= lang('Admin/TahunAjaran.th_student_count') ?></th>
          <th class="px-6 py-4 text-center text-xs font-bold text-gray-500 dark:text-slate-400 uppercase tracking-wider"><?= lang('Admin/TahunAjaran.th_teacher_count') ?></th>
          <th class="px-6 py-4 text-center text-xs font-bold text-gray-500 dark:text-slate-400 uppercase tracking-wider"><?= lang('Admin/TahunAjaran.th_locked_data') ?></th>
          <th class="px-6 py-4 text-center text-xs font-bold text-gray-500 dark:text-slate-400 uppercase tracking-wider"><?= lang('Admin/TahunAjaran.th_action') ?></th>
        </tr>
      </thead>
      <tbody id="yearTableBody" class="divide-y divide-gray-100 dark:divide-slate-700/50 bg-white dark:bg-slate-800 transition-colors">
      </tbody>
    </table>
  </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('modals') ?>

<div id="addYearModal" class="fixed inset-0 hidden" style="z-index: 99999;">
  <div class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity pointer-events-auto" onclick="closeAddYearModal()"></div>
  <div class="absolute inset-0 flex items-center justify-center md:pl-64 p-4 pointer-events-none">
    <div class="relative w-full bg-white dark:bg-slate-800 rounded-3xl shadow-2xl flex flex-col max-h-[90vh] pointer-events-auto transition-colors duration-300 border border-transparent dark:border-slate-700" style="max-width: 600px;">
      <div class="p-6 border-b border-gray-100 dark:border-slate-700 flex items-center justify-between bg-white dark:bg-slate-800 rounded-t-3xl z-20 flex-shrink-0 transition-colors">
        <div>
          <h3 class="text-xl font-bold text-gray-800 dark:text-white"><?= lang('Admin/TahunAjaran.modal_add_title') ?></h3>
          <p class="text-sm text-gray-500 dark:text-slate-400 mt-1"><?= lang('Admin/TahunAjaran.modal_add_desc') ?></p>
        </div>
        <button onclick="closeAddYearModal()" class="p-2 hover:bg-gray-100 dark:hover:bg-slate-700 rounded-full transition-colors cursor-pointer relative z-50 text-gray-500 dark:text-slate-400 outline-none">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewbox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
      </div>
      <div class="flex-1 overflow-y-auto p-6 relative z-10 custom-scrollbar">
        <form class="space-y-5" onsubmit="handleAddYear(event)">
          <div>
            <label class="block text-sm font-semibold text-gray-700 dark:text-slate-300 mb-2"><?= lang('Admin/TahunAjaran.form_year') ?> <span class="text-red-500">*</span></label>
            <input type="text" name="new_year" required placeholder="<?= lang('Admin/TahunAjaran.form_year_placeholder') ?>" class="w-full px-4 py-3 bg-white dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-xl text-sm text-gray-800 dark:text-white placeholder-gray-400 dark:placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[<?= $color['warna_primary'] ?>] transition-all shadow-sm outline-none">
            <p class="text-xs text-gray-500 dark:text-slate-400 mt-1.5 font-medium"><?= lang('Admin/TahunAjaran.form_year_format') ?></p>
          </div>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-semibold text-gray-700 dark:text-slate-300 mb-2"><?= lang('Admin/TahunAjaran.form_start_date') ?> <span class="text-red-500">*</span></label>
              <input type="date" name="start_date" required class="w-full px-4 py-3 bg-white dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-xl text-sm text-gray-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-[<?= $color['warna_primary'] ?>] transition-all shadow-sm outline-none color-scheme-dark">
            </div>
            <div>
              <label class="block text-sm font-semibold text-gray-700 dark:text-slate-300 mb-2"><?= lang('Admin/TahunAjaran.form_end_date') ?> <span class="text-red-500">*</span></label>
              <input type="date" name="end_date" required class="w-full px-4 py-3 bg-white dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-xl text-sm text-gray-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-[<?= $color['warna_primary'] ?>] transition-all shadow-sm outline-none color-scheme-dark">
            </div>
          </div>
          <div>
            <label class="block text-sm font-semibold text-gray-700 dark:text-slate-300 mb-2"><?= lang('Admin/TahunAjaran.form_start_semester') ?> <span class="text-red-500">*</span></label>
            <select name="semester_awal" required class="w-full px-4 py-3 bg-white dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-xl text-sm text-gray-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-[<?= $color['warna_primary'] ?>] transition-all shadow-sm appearance-none cursor-pointer outline-none">
              <option value=""><?= lang('Admin/TahunAjaran.select_semester') ?></option>
              <option value="Ganjil"><?= lang('Admin/TahunAjaran.odd_semester') ?></option>
              <option value="Genap"><?= lang('Admin/TahunAjaran.even_semester') ?></option>
            </select>
          </div>
          <div class="bg-amber-50 dark:bg-amber-900/20 rounded-xl p-4 flex items-start gap-3 border border-amber-200 dark:border-amber-800/50 shadow-sm transition-colors">
            <svg class="w-5 h-5 text-amber-600 dark:text-amber-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewbox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
            <div>
              <p class="text-sm font-bold text-amber-900 dark:text-amber-400"><?= lang('Admin/TahunAjaran.attention') ?></p>
              <p class="text-xs text-amber-800 dark:text-amber-300 mt-1"><?= lang('Admin/TahunAjaran.attention_add_desc') ?></p>
            </div>
          </div>
          <div class="flex gap-3 pt-5 border-t border-gray-100 dark:border-slate-700 sticky bottom-0 bg-white dark:bg-slate-800 z-50 pb-2 transition-colors">
            <button type="button" onclick="closeAddYearModal()" class="flex-1 px-6 py-3 bg-white dark:bg-slate-700 border-2 border-gray-300 dark:border-slate-600 text-gray-700 dark:text-slate-300 font-bold rounded-xl hover:bg-gray-50 dark:hover:bg-slate-600 transition-colors cursor-pointer outline-none shadow-sm"> <?= lang('Admin/TahunAjaran.btn_cancel') ?> </button>
            <button type="submit" class="flex-1 px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl transition-transform transform hover:-translate-y-0.5 shadow-lg shadow-emerald-600/30 cursor-pointer outline-none"> <?= lang('Admin/TahunAjaran.btn_save_year') ?> </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<div id="editYearModal" class="fixed inset-0 hidden" style="z-index: 99999;">
  <div class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity pointer-events-auto" onclick="closeEditYearModal()"></div>
  <div class="absolute inset-0 flex items-center justify-center md:pl-64 p-4 pointer-events-none">
    <div class="relative w-full bg-white dark:bg-slate-800 rounded-3xl shadow-2xl flex flex-col max-h-[90vh] pointer-events-auto transition-colors duration-300 border border-transparent dark:border-slate-700" style="max-width: 600px;">
      <div class="p-6 border-b border-gray-100 dark:border-slate-700 flex items-center justify-between bg-white dark:bg-slate-800 rounded-t-3xl z-20 flex-shrink-0 transition-colors">
        <div>
          <h3 class="text-xl font-bold text-gray-800 dark:text-white"><?= lang('Admin/TahunAjaran.modal_edit_title') ?></h3>
          <p class="text-sm text-gray-500 dark:text-slate-400 mt-1"><?= lang('Admin/TahunAjaran.modal_edit_desc') ?></p>
        </div>
        <button onclick="closeEditYearModal()" class="p-2 hover:bg-gray-100 dark:hover:bg-slate-700 rounded-full transition-colors cursor-pointer relative z-50 text-gray-500 dark:text-slate-400 outline-none">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewbox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
      </div>
      <div class="flex-1 overflow-y-auto p-6 relative z-10 custom-scrollbar">
        <form class="space-y-5" onsubmit="handleEditYear(event)">
          <input type="hidden" name="id" id="edit_id">
          <div>
            <label class="block text-sm font-semibold text-gray-700 dark:text-slate-300 mb-2"><?= lang('Admin/TahunAjaran.form_year') ?> <span class="text-red-500">*</span></label>
            <input type="text" name="edit_year" id="edit_year" required class="w-full px-4 py-3 bg-white dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-xl text-sm text-gray-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-[<?= $color['warna_primary'] ?>] transition-all shadow-sm outline-none">
          </div>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-semibold text-gray-700 dark:text-slate-300 mb-2"><?= lang('Admin/TahunAjaran.form_start_date') ?> <span class="text-red-500">*</span></label>
              <input type="date" name="edit_start_date" id="edit_start_date" required class="w-full px-4 py-3 bg-white dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-xl text-sm text-gray-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-[<?= $color['warna_primary'] ?>] transition-all shadow-sm outline-none color-scheme-dark">
            </div>
            <div>
              <label class="block text-sm font-semibold text-gray-700 dark:text-slate-300 mb-2"><?= lang('Admin/TahunAjaran.form_end_date') ?> <span class="text-red-500">*</span></label>
              <input type="date" name="edit_end_date" id="edit_end_date" required class="w-full px-4 py-3 bg-white dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-xl text-sm text-gray-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-[<?= $color['warna_primary'] ?>] transition-all shadow-sm outline-none color-scheme-dark">
            </div>
          </div>
          <div>
            <label class="block text-sm font-semibold text-gray-700 dark:text-slate-300 mb-2"><?= lang('Admin/TahunAjaran.form_semester') ?> <span class="text-red-500">*</span></label>
            <select name="edit_semester" id="edit_semester" required class="w-full px-4 py-3 bg-white dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-xl text-sm text-gray-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-[<?= $color['warna_primary'] ?>] transition-all shadow-sm appearance-none cursor-pointer outline-none">
              <option value="Ganjil"><?= lang('Admin/TahunAjaran.odd_semester') ?></option>
              <option value="Genap"><?= lang('Admin/TahunAjaran.even_semester') ?></option>
            </select>
          </div>
          <div class="flex gap-3 pt-5 border-t border-gray-100 dark:border-slate-700 sticky bottom-0 bg-white dark:bg-slate-800 z-50 pb-2 transition-colors">
            <button type="button" onclick="closeEditYearModal()" class="flex-1 px-6 py-3 bg-white dark:bg-slate-700 border-2 border-gray-300 dark:border-slate-600 text-gray-700 dark:text-slate-300 font-bold rounded-xl hover:bg-gray-50 dark:hover:bg-slate-600 transition-colors cursor-pointer outline-none shadow-sm"> <?= lang('Admin/TahunAjaran.btn_cancel') ?> </button>
            <button type="submit" class="flex-1 px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl transition-transform transform hover:-translate-y-0.5 shadow-lg shadow-blue-600/30 cursor-pointer outline-none"> <?= lang('Admin/TahunAjaran.btn_save_changes') ?> </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<div id="deleteYearModal" class="fixed inset-0 hidden" style="z-index: 99999;">
  <div class="absolute inset-0 bg-gray-900/70 backdrop-blur-sm transition-opacity pointer-events-auto" onclick="closeDeleteYearModal()"></div>
  <div class="absolute inset-0 flex items-center justify-center md:pl-64 p-4 pointer-events-none">
    <div class="relative w-full bg-white dark:bg-slate-800 rounded-3xl shadow-2xl flex flex-col max-h-[90vh] pointer-events-auto border border-transparent dark:border-slate-700 transition-colors duration-300" style="max-width: 450px;">
      <div class="p-8 text-center relative z-10">
        <div class="w-20 h-20 bg-red-50 dark:bg-red-900/30 rounded-full flex items-center justify-center mx-auto mb-5 border-4 border-red-100 dark:border-red-800/50">
          <svg class="w-10 h-10 text-red-500 dark:text-red-400" fill="none" stroke="currentColor" viewbox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
          </svg>
        </div>
        <h3 class="text-2xl font-bold text-gray-800 dark:text-white mb-2"><?= lang('Admin/TahunAjaran.modal_delete_title') ?></h3>
        <p class="text-sm font-medium text-gray-500 dark:text-slate-400 mb-8 px-4 leading-relaxed"><?= lang('Admin/TahunAjaran.modal_delete_desc') ?></p>
        <input type="hidden" id="delete_year_id">
        <div class="flex gap-3">
          <button onclick="closeDeleteYearModal()" class="flex-1 px-5 py-3 bg-white dark:bg-slate-700 border border-gray-300 dark:border-slate-600 text-gray-700 dark:text-slate-300 font-bold rounded-xl hover:bg-gray-50 dark:hover:bg-slate-600 transition-colors outline-none shadow-sm"><?= lang('Admin/TahunAjaran.btn_cancel') ?></button>
          <button onclick="confirmDeleteYear()" class="flex-1 px-5 py-3 bg-red-600 hover:bg-red-700 text-white font-bold rounded-xl shadow-lg shadow-red-600/30 transition-transform transform hover:-translate-y-0.5 outline-none"><?= lang('Admin/TahunAjaran.btn_yes_delete') ?></button>
        </div>
      </div>
    </div>
  </div>
</div>

<div id="changeSemesterModal" class="fixed inset-0 hidden" style="z-index: 99999;">
  <div class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity pointer-events-auto" onclick="closeChangeSemesterModal()"></div>
  <div class="absolute inset-0 flex items-center justify-center md:pl-64 p-4 pointer-events-none">
    <div class="relative w-full bg-white dark:bg-slate-800 rounded-3xl shadow-2xl flex flex-col max-h-[90vh] pointer-events-auto border border-transparent dark:border-slate-700 transition-colors duration-300" style="max-width: 600px;">
      <div class="p-6 border-b border-gray-100 dark:border-slate-700 flex items-center justify-between bg-white dark:bg-slate-800 rounded-t-3xl z-20 flex-shrink-0 transition-colors">
        <div>
          <h3 class="text-xl font-bold text-gray-800 dark:text-white"><?= lang('Admin/TahunAjaran.modal_change_sem_title') ?></h3>
          <p class="text-sm text-gray-500 dark:text-slate-400 mt-1"><?= lang('Admin/TahunAjaran.modal_change_sem_desc') ?></p>
        </div>
        <button onclick="closeChangeSemesterModal()" class="p-2 hover:bg-gray-100 dark:hover:bg-slate-700 rounded-full transition-colors cursor-pointer relative z-50 text-gray-500 dark:text-slate-400 outline-none">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewbox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
      </div>
      <div class="flex-1 overflow-y-auto p-6 relative z-10 space-y-6 custom-scrollbar">
        <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800/50 rounded-xl p-5 flex items-start gap-4 shadow-sm transition-colors">
          <svg class="w-7 h-7 text-blue-600 dark:text-blue-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewbox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
          <div>
            <p class="text-base font-bold text-blue-900 dark:text-blue-400"><?= lang('Admin/TahunAjaran.info_change_sem') ?></p>
            <p class="text-sm text-blue-800 dark:text-blue-300 mt-2 leading-relaxed" id="textChangeSemester"></p>
          </div>
        </div>
        <div class="space-y-4 px-2">
          <p class="text-sm font-bold text-gray-800 dark:text-white"><?= lang('Admin/TahunAjaran.impact_change_sem') ?></p>
          <ul class="space-y-3 text-sm text-gray-600 dark:text-slate-300 font-medium">
            <li class="flex items-start gap-3"><span class="text-amber-500 mt-0.5"><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg></span> <?= lang('Admin/TahunAjaran.impact_sem_1') ?></li>
            <li class="flex items-start gap-3"><span class="text-amber-500 mt-0.5"><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg></span> <?= lang('Admin/TahunAjaran.impact_sem_2') ?></li>
            <li class="flex items-start gap-3"><span class="text-emerald-500 mt-0.5"><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg></span> <?= lang('Admin/TahunAjaran.impact_sem_3') ?></li>
          </ul>
        </div>
        <div class="bg-red-50 dark:bg-red-900/20 border-2 border-red-200 dark:border-red-800/50 rounded-xl p-5 shadow-sm transition-colors">
          <label class="flex items-start gap-3 cursor-pointer group">
            <input type="checkbox" id="confirmChangeSemester" class="w-5 h-5 rounded border-gray-300 text-[<?= $color['warna_primary'] ?>] focus:ring-[<?= $color['warna_primary'] ?>] cursor-pointer mt-0.5 focus:ring-offset-0">
            <span class="text-sm text-gray-800 dark:text-slate-300 leading-relaxed group-hover:text-gray-900 dark:group-hover:text-white transition-colors"><?= lang('Admin/TahunAjaran.confirm_change_sem') ?></span>
          </label>
        </div>
        <div class="flex gap-3 pt-5 border-t border-gray-100 dark:border-slate-700 sticky bottom-0 bg-white dark:bg-slate-800 z-50 pb-2 transition-colors">
          <button onclick="closeChangeSemesterModal()" class="flex-1 px-6 py-3 bg-white dark:bg-slate-700 border-2 border-gray-300 dark:border-slate-600 text-gray-700 dark:text-slate-300 font-bold rounded-xl hover:bg-gray-50 dark:hover:bg-slate-600 transition-colors cursor-pointer outline-none shadow-sm"> <?= lang('Admin/TahunAjaran.btn_cancel') ?> </button>
          <button onclick="confirmChangeSemester()" class="flex-1 px-6 py-3 text-white font-bold rounded-xl transition-transform transform hover:-translate-y-0.5 shadow-lg cursor-pointer outline-none" style="background-color: <?= $color['warna_primary'] ?>; box-shadow: 0 10px 15px -3px <?= $color['warna_primary'] ?>40;"> <?= lang('Admin/TahunAjaran.btn_yes_change_sem') ?> </button>
        </div>
      </div>
    </div>
  </div>
</div>

<div id="impactModal" class="fixed inset-0 hidden" style="z-index: 99999;">
  <div class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity pointer-events-auto" onclick="closeImpactModal()"></div>
  <div class="absolute inset-0 flex items-center justify-center md:pl-64 p-4 pointer-events-none">
    <div class="relative w-full bg-white dark:bg-slate-800 rounded-3xl shadow-2xl flex flex-col max-h-[90vh] pointer-events-auto border border-transparent dark:border-slate-700 transition-colors duration-300" style="max-width: 800px;">
      <div class="p-6 border-b border-gray-100 dark:border-slate-700 flex items-center justify-between bg-white dark:bg-slate-800 rounded-t-3xl z-20 flex-shrink-0 transition-colors">
        <div>
          <h3 class="text-xl font-bold text-gray-800 dark:text-white"><?= lang('Admin/TahunAjaran.modal_impact_title') ?></h3>
          <p class="text-sm text-gray-500 dark:text-slate-400 mt-1"><?= lang('Admin/TahunAjaran.modal_impact_desc') ?></p>
        </div>
        <button onclick="closeImpactModal()" class="p-2 hover:bg-gray-100 dark:hover:bg-slate-700 rounded-full transition-colors cursor-pointer relative z-50 text-gray-500 dark:text-slate-400 outline-none">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewbox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
      </div>
      <div class="flex-1 overflow-y-auto p-6 relative z-10 space-y-6 custom-scrollbar">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
          <div class="bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800/50 rounded-2xl p-5 shadow-sm transition-colors">
            <h4 class="font-bold text-emerald-900 dark:text-emerald-400 mb-2 flex items-center gap-2">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewbox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
              </svg> <?= lang('Admin/TahunAjaran.impact_1_title') ?>
            </h4>
            <p class="text-sm text-emerald-800 dark:text-emerald-300 leading-relaxed font-medium"><?= lang('Admin/TahunAjaran.impact_det_1') ?></p>
          </div>
          <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800/50 rounded-2xl p-5 shadow-sm transition-colors">
            <h4 class="font-bold text-blue-900 dark:text-blue-400 mb-2 flex items-center gap-2">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewbox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
              </svg> <?= lang('Admin/TahunAjaran.impact_2_title') ?>
            </h4>
            <p class="text-sm text-blue-800 dark:text-blue-300 leading-relaxed font-medium"><?= lang('Admin/TahunAjaran.impact_det_2') ?></p>
          </div>
        </div>
        <div class="bg-red-50 dark:bg-red-900/20 border-2 border-red-200 dark:border-red-800/50 rounded-2xl p-6 shadow-sm transition-colors mt-2">
          <div class="flex items-start gap-4">
            <div class="w-12 h-12 bg-white dark:bg-slate-800 border border-transparent dark:border-red-800/50 rounded-full flex items-center justify-center shadow flex-shrink-0">
              <svg class="w-7 h-7 text-red-600 dark:text-red-500" fill="none" stroke="currentColor" viewbox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
              </svg>
            </div>
            <div>
              <p class="text-base font-bold text-red-900 dark:text-red-400 mb-1"><?= lang('Admin/TahunAjaran.warning_before_activation') ?></p>
              <p class="text-sm text-red-800 dark:text-red-300 font-medium leading-relaxed"><?= lang('Admin/TahunAjaran.warning_activation_desc') ?></p>
            </div>
          </div>
        </div>
        <div class="pt-5 border-t border-gray-100 dark:border-slate-700 sticky bottom-0 bg-white dark:bg-slate-800 z-50 pb-2 transition-colors">
          <button onclick="closeImpactModal()" class="w-full px-6 py-3.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl transition-transform transform hover:-translate-y-0.5 shadow-lg shadow-emerald-600/30 cursor-pointer outline-none"> <?= lang('Admin/TahunAjaran.btn_understand_careful') ?> </button>
        </div>
      </div>
    </div>
  </div>
</div>

<div id="detailModal" class="fixed inset-0 hidden" style="z-index: 99999;">
  <div class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity pointer-events-auto" onclick="closeDetailModal()"></div>
  <div class="absolute inset-0 flex items-center justify-center md:pl-64 p-4 pointer-events-none">
    <div class="relative w-full bg-white dark:bg-slate-800 rounded-3xl shadow-2xl flex flex-col max-h-[90vh] pointer-events-auto border border-transparent dark:border-slate-700 transition-colors duration-300" style="max-width: 700px;">
      <div class="p-6 border-b border-gray-100 dark:border-slate-700 flex items-center justify-between bg-white dark:bg-slate-800 rounded-t-3xl z-20 flex-shrink-0 transition-colors">
        <div>
          <h3 class="text-xl font-bold text-gray-800 dark:text-white"><?= lang('Admin/TahunAjaran.modal_detail_title') ?></h3>
          <p class="text-sm text-gray-500 dark:text-slate-400 mt-1"><?= lang('Admin/TahunAjaran.modal_detail_desc') ?></p>
        </div>
        <button onclick="closeDetailModal()" class="p-2 hover:bg-gray-100 dark:hover:bg-slate-700 rounded-full transition-colors cursor-pointer relative z-50 text-gray-500 dark:text-slate-400 outline-none">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewbox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
      </div>
      <div class="flex-1 overflow-y-auto p-6 relative z-10 space-y-6 custom-scrollbar">
        <div class="bg-gradient-to-br from-emerald-50 to-white dark:from-emerald-900/20 dark:to-slate-800 border-2 border-emerald-200 dark:border-emerald-800/50 rounded-2xl p-6 shadow-sm transition-colors">
          <div class="flex items-center justify-between mb-5">
            <h4 id="detailYear" class="text-2xl md:text-3xl font-black text-gray-900 dark:text-white">...</h4>
            <span id="detailStatusBadge" class="bg-[<?= $color['warna_primary'] ?>] text-white px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider shadow-sm">...</span>
          </div>
          <div class="grid grid-cols-2 gap-5">
            <div>
              <p class="text-xs font-bold text-gray-500 dark:text-slate-400 uppercase tracking-wider mb-1.5"><?= lang('Admin/TahunAjaran.th_semester') ?></p>
              <p id="detailSemester" class="text-lg font-bold text-gray-900 dark:text-white">...</p>
            </div>
            <div>
              <p class="text-xs font-bold text-gray-500 dark:text-slate-400 uppercase tracking-wider mb-1.5"><?= lang('Admin/TahunAjaran.th_locked_data') ?></p>
              <span id="detailLocked" class="inline-flex px-2 py-0.5 bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400 text-xs font-bold rounded border dark:border-blue-800/50">...</span>
            </div>
          </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
          <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800/50 rounded-2xl p-5 shadow-sm transition-colors">
            <div class="flex items-center gap-4">
              <div class="w-14 h-14 rounded-xl bg-blue-600 flex items-center justify-center shadow-lg"><svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewbox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg></div>
              <div>
                <p class="text-xs font-bold text-blue-700 dark:text-blue-400 uppercase tracking-wider mb-0.5"><?= lang('Admin/TahunAjaran.th_student_count') ?></p>
                <p id="detailStudents" class="text-2xl font-black text-blue-900 dark:text-white">...</p>
              </div>
            </div>
          </div>
          <div class="bg-purple-50 dark:bg-purple-900/20 border border-purple-200 dark:border-purple-800/50 rounded-2xl p-5 shadow-sm transition-colors">
            <div class="flex items-center gap-4">
              <div class="w-14 h-14 rounded-xl bg-purple-600 flex items-center justify-center shadow-lg"><svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewbox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg></div>
              <div>
                <p class="text-xs font-bold text-purple-700 dark:text-purple-400 uppercase tracking-wider mb-0.5"><?= lang('Admin/TahunAjaran.th_teacher_count') ?></p>
                <p id="detailTeachers" class="text-2xl font-black text-purple-900 dark:text-white">...</p>
              </div>
            </div>
          </div>
        </div>
        <div class="space-y-3">
          <h5 class="font-bold text-gray-900 dark:text-white"><?= lang('Admin/TahunAjaran.detail_period_info') ?></h5>
          <div class="bg-gray-50 dark:bg-slate-900/50 border border-gray-200 dark:border-slate-700 rounded-xl p-5 space-y-4 transition-colors">
            <div class="flex items-center justify-between border-b border-gray-200 dark:border-slate-700 pb-3"><span class="text-sm font-medium text-gray-600 dark:text-slate-400"><?= lang('Admin/TahunAjaran.start_date') ?></span> <span id="detailStartDate" class="text-sm font-bold text-gray-900 dark:text-white">...</span></div>
            <div class="flex items-center justify-between"><span class="text-sm font-medium text-gray-600 dark:text-slate-400"><?= lang('Admin/TahunAjaran.end_date') ?></span> <span id="detailEndDate" class="text-sm font-bold text-gray-900 dark:text-white">...</span></div>
          </div>
        </div>
        <div class="pt-5 border-t border-gray-100 dark:border-slate-700 sticky bottom-0 bg-white dark:bg-slate-800 z-50 pb-2 transition-colors">
          <button onclick="closeDetailModal()" class="w-full px-6 py-3.5 bg-gray-100 dark:bg-slate-700 hover:bg-gray-200 dark:hover:bg-slate-600 text-gray-800 dark:text-white font-bold rounded-xl transition-colors cursor-pointer outline-none shadow-sm"> <?= lang('Admin/TahunAjaran.btn_close_window') ?> </button>
        </div>
      </div>
    </div>
  </div>
</div>

<div id="deactivateModal" class="fixed inset-0 hidden" style="z-index: 100000;">
  <div class="absolute inset-0 bg-gray-900/70 backdrop-blur-sm transition-opacity pointer-events-auto" onclick="closeDeactivateModal()"></div>
  <div class="absolute inset-0 flex items-center justify-center md:pl-64 p-4 pointer-events-none">
    <div class="relative w-full bg-white dark:bg-slate-800 rounded-3xl shadow-2xl flex flex-col max-h-[90vh] pointer-events-auto border border-transparent dark:border-slate-700 transition-colors duration-300" style="max-width: 600px;">
      <div class="p-6 border-b border-gray-100 dark:border-slate-700 flex items-center justify-between bg-white dark:bg-slate-800 rounded-t-3xl z-20 flex-shrink-0 transition-colors">
        <div>
          <h3 class="text-xl font-black text-red-600 dark:text-red-500"><?= lang('Admin/TahunAjaran.modal_deactivate_title') ?></h3>
          <p class="text-sm text-gray-500 dark:text-slate-400 mt-1 font-medium"><?= lang('Admin/TahunAjaran.modal_deactivate_desc') ?></p>
        </div>
        <button onclick="closeDeactivateModal()" class="p-2 hover:bg-gray-100 dark:hover:bg-slate-700 rounded-full transition-colors cursor-pointer relative z-50 text-gray-400 dark:text-slate-500 outline-none">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewbox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
      </div>
      <div class="flex-1 overflow-y-auto p-6 relative z-10 space-y-6 custom-scrollbar">
        <div class="bg-red-50 dark:bg-red-900/20 border-2 border-red-200 dark:border-red-800/50 rounded-2xl p-5 flex items-start gap-4 shadow-sm transition-colors">
          <svg class="w-7 h-7 text-red-600 dark:text-red-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewbox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
          </svg>
          <div>
            <p class="text-base font-black text-red-900 dark:text-red-400"><?= lang('Admin/TahunAjaran.danger_action') ?></p>
            <p class="text-sm text-red-800 dark:text-red-300 mt-2 leading-relaxed font-medium"><?= lang('Admin/TahunAjaran.danger_action_desc') ?></p>
          </div>
        </div>
        <div class="bg-gray-50 dark:bg-slate-900/50 border-2 border-gray-300 dark:border-slate-600 rounded-2xl p-5 space-y-4 transition-colors">
          <label class="flex items-center gap-3 cursor-pointer group">
            <input type="checkbox" id="confirmDeactivate1" class="w-5 h-5 rounded border-gray-300 dark:border-slate-500 bg-white dark:bg-slate-800 text-red-600 focus:ring-red-500 cursor-pointer focus:ring-offset-0">
            <span class="text-sm font-medium text-gray-800 dark:text-slate-300 group-hover:text-gray-900 dark:group-hover:text-white transition-colors"><?= lang('Admin/TahunAjaran.chk_backup') ?></span>
          </label>
          <label class="flex items-center gap-3 cursor-pointer group">
            <input type="checkbox" id="confirmDeactivate2" class="w-5 h-5 rounded border-gray-300 dark:border-slate-500 bg-white dark:bg-slate-800 text-red-600 focus:ring-red-500 cursor-pointer focus:ring-offset-0">
            <span class="text-sm font-medium text-gray-800 dark:text-slate-300 group-hover:text-gray-900 dark:group-hover:text-white transition-colors"><?= lang('Admin/TahunAjaran.chk_print') ?></span>
          </label>
          <label class="flex items-center gap-3 cursor-pointer group">
            <input type="checkbox" id="confirmDeactivate3" class="w-5 h-5 rounded border-gray-300 dark:border-slate-500 bg-white dark:bg-slate-800 text-red-600 focus:ring-red-500 cursor-pointer focus:ring-offset-0">
            <span class="text-sm font-medium text-gray-800 dark:text-slate-300 group-hover:text-gray-900 dark:group-hover:text-white transition-colors"><?= lang('Admin/TahunAjaran.chk_understand') ?></span>
          </label>
        </div>
        <div class="flex gap-3 pt-5 border-t border-gray-100 dark:border-slate-700 sticky bottom-0 bg-white dark:bg-slate-800 z-50 pb-2 transition-colors">
          <button onclick="closeDeactivateModal()" class="flex-1 px-6 py-3.5 bg-white dark:bg-slate-700 border-2 border-gray-300 dark:border-slate-600 text-gray-700 dark:text-slate-300 font-bold rounded-xl hover:bg-gray-50 dark:hover:bg-slate-600 transition-colors cursor-pointer outline-none shadow-sm"> <?= lang('Admin/TahunAjaran.btn_cancel') ?> </button>
          <button onclick="confirmDeactivate()" class="flex-1 px-6 py-3.5 bg-red-600 hover:bg-red-700 text-white font-bold rounded-xl transition-transform transform hover:-translate-y-0.5 shadow-lg shadow-red-600/30 cursor-pointer outline-none"> <?= lang('Admin/TahunAjaran.btn_force_deactivate') ?> </button>
        </div>
      </div>
    </div>
  </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
  const dbYearData = <?= json_encode($yearData) ?>;
  const BASE_URL = "<?= base_url() ?>";
  const APP_LANG = document.documentElement.lang || 'id-ID';
  const LANG = {
    js_no_data_year: "<?= lang('Admin/TahunAjaran.js_no_data_year') ?>",
    js_status_active: "<?= lang('Admin/TahunAjaran.js_status_active') ?>",
    js_status_archived: "<?= lang('Admin/TahunAjaran.js_status_archived') ?>",
    js_locked: "<?= lang('Admin/TahunAjaran.js_locked') ?>",
    js_unlocked: "<?= lang('Admin/TahunAjaran.js_unlocked') ?>",
    js_btn_activate: "<?= lang('Admin/TahunAjaran.js_btn_activate') ?>",
    js_btn_detail: "<?= lang('Admin/TahunAjaran.js_btn_detail') ?>",
    js_btn_edit: "<?= lang('Admin/TahunAjaran.js_btn_edit') ?>",
    js_btn_delete: "<?= lang('Admin/TahunAjaran.js_btn_delete') ?>",
    js_tooltip_cannot_delete: "<?= lang('Admin/TahunAjaran.js_tooltip_cannot_delete') ?>",
    js_notification: "<?= lang('Admin/TahunAjaran.js_notification') ?>",
    js_saving: "<?= lang('Admin/TahunAjaran.js_saving') ?>",
    js_fail_server: "<?= lang('Admin/TahunAjaran.js_fail_server') ?>",
    js_fail_fetch: "<?= lang('Admin/TahunAjaran.js_fail_fetch') ?>",
    js_change_from: "<?= lang('Admin/TahunAjaran.js_change_from') ?>",
    js_change_to: "<?= lang('Admin/TahunAjaran.js_change_to') ?>",
    js_warn_checkbox: "<?= lang('Admin/TahunAjaran.js_warn_checkbox') ?>",
    js_warn_all_checkbox: "<?= lang('Admin/TahunAjaran.js_warn_all_checkbox') ?>",
    js_deactivate_success: "<?= lang('Admin/TahunAjaran.js_deactivate_success') ?>",
    js_confirm_activate: "<?= lang('Admin/TahunAjaran.js_confirm_activate') ?>",
    js_data_not_found: "<?= lang('Admin/TahunAjaran.js_data_not_found') ?>",
    th_semester: "<?= lang('Admin/TahunAjaran.th_semester') ?>",
    th_locked_data: "<?= lang('Admin/TahunAjaran.th_locked_data') ?>",
    th_student_count: "<?= lang('Admin/TahunAjaran.th_student_count') ?>",
    th_teacher_count: "<?= lang('Admin/TahunAjaran.th_teacher_count') ?>",
    start_date: "<?= lang('Admin/TahunAjaran.start_date') ?>",
    end_date: "<?= lang('Admin/TahunAjaran.end_date') ?>"
  };
</script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="<?= base_url('assets/js/Admin/tahun-ajaran.js') ?>?v=<?= time() ?>"></script>
<?= $this->endSection() ?>