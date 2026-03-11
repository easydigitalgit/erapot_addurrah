<?= $this->extend('layout/main') ?>

<?= $this->section('title') ?>
<?= lang('GuruMapel/TargetTahfidz.page_title') ?> - Rapor Digital SMPIT Ad Durrah
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
  :root {
    /* Kita oper nilai dari PHP ke Variabel CSS bernama --warna-scroll */
    --warna-scroll: <?= $color['warna_primary'] ?>; 
  }
</style>
<link rel="stylesheet" href="<?= base_url('assets/css/Admin/target-tahfidz.css') ?>">
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="mb-6">
  <div class="flex items-center gap-2 text-sm text-gray-500 mb-3"><span><?= lang('GuruMapel/TargetTahfidz.breadcrumb_config') ?></span>
    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewbox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
    </svg><span class="text-[<?= $color['warna_primary'] ?>] font-medium"><?= lang('GuruMapel/TargetTahfidz.breadcrumb_target') ?></span>
  </div><div class="text-center mb-6 py-4 bg-gradient-to-r from-[<?= $color['warna_secondary'] ?>]/40 to-[<?= $color['warna_secondary'] ?>]/20 rounded-2xl border border-[<?= $color['warna_primary'] ?>]/40">
    <p class="text-3xl arabic-text text-[<?= $color['warna_primary'] ?>] mb-2"><?= lang('GuruMapel/TargetTahfidz.bismillah') ?></p>
    <p class="text-xs text-gray-600 italic"><?= lang('GuruMapel/TargetTahfidz.bismillah_trans') ?></p>
  </div>
  <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
    <div>
      <h1 id="pageTitle" class="text-3xl md:text-3xl font-bold text-gray-800 mb-2"><?= lang('GuruMapel/TargetTahfidz.page_title') ?></h1>
      <p id="pageSubtitle" class="text-sm md:text-base text-gray-600"><?= lang('GuruMapel/TargetTahfidz.page_subtitle') ?></p>
    </div>
    <div class="flex flex-wrap items-center gap-2">
      <button onclick="showAddTargetModal()" class="px-4 py-2.5 bg-[<?= $color['warna_primary'] ?>]/80 hover:bg-[<?= $color['warna_primary'] ?>] text-white font-semibold rounded-xl transition-colors shadow-lg shadow-[<?= $color['warna_primary'] ?>]/20 flex items-center gap-2">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewbox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
        </svg><span><?= lang('GuruMapel/TargetTahfidz.btn_add_target') ?></span> 
      </button> 
      <button class="px-4 py-2.5 bg-white border border-gray-300 text-gray-700 font-semibold rounded-xl hover:bg-gray-50 transition-colors flex items-center gap-2">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewbox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10" />
        </svg><span class="hidden md:inline"><?= lang('GuruMapel/TargetTahfidz.btn_import') ?></span> 
      </button> 
      <button onclick="showTemplateModal()" class="px-4 py-2.5 bg-white border border-gray-300 text-gray-700 font-semibold rounded-xl hover:bg-gray-50 transition-colors flex items-center gap-2">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewbox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
        </svg><span class="hidden md:inline"><?= lang('GuruMapel/TargetTahfidz.btn_template') ?></span> 
      </button> 
      <button class="px-4 py-2.5 bg-white border border-gray-300 text-gray-700 font-semibold rounded-xl hover:bg-gray-50 transition-colors flex items-center gap-2">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewbox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg><span class="hidden md:inline"><?= lang('GuruMapel/TargetTahfidz.btn_history') ?></span> 
      </button>
    </div>
  </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6 mb-6">
  <div class="stat-card hover:border-[<?= $color['warna_primary'] ?>]/80">
    <div class="flex items-center justify-between mb-4">
      <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-[<?= $color['warna_primary'] ?>]/80 to-[<?= $color['warna_primary'] ?>] flex items-center justify-center shadow-lg">
        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewbox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
        </svg>
      </div><span class="text-xs font-bold text-[<?= $color['warna_primary'] ?>] uppercase tracking-wider"><?= lang('GuruMapel/TargetTahfidz.stat_academic_year') ?></span>
    </div>
    <p class="text-3xl font-black text-gray-900 mb-1"><?= get_tahun_ajaran() ?></p>
    <p class="text-sm text-gray-600"><?= lang('GuruMapel/TargetTahfidz.stat_semester', [get_semester()]) ?></p>
  </div>
  
  <div class="stat-card hover:border-[<?= $color['warna_primary'] ?>]/80">
    <div class="flex items-center justify-between mb-4">
      <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center shadow-lg">
        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewbox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
        </svg>
      </div><span class="text-xs font-bold text-blue-600 uppercase tracking-wider"><?= lang('GuruMapel/TargetTahfidz.stat_total_target') ?></span>
    </div>
    <p class="text-3xl font-black text-gray-900 mb-1">4.5 Juz</p>
    <p class="text-sm text-gray-600"><?= lang('GuruMapel/TargetTahfidz.stat_all_levels') ?></p>
  </div>
  
  <div class="stat-card hover:border-[<?= $color['warna_primary'] ?>]/80">
    <div class="flex items-center justify-between mb-4">
      <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-purple-500 to-purple-600 flex items-center justify-center shadow-lg">
        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewbox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
        </svg>
      </div><span class="text-xs font-bold text-purple-600 uppercase tracking-wider"><?= lang('GuruMapel/TargetTahfidz.stat_active_levels') ?></span>
    </div>
    <p class="text-3xl font-black text-gray-900 mb-1">3 Tingkat</p>
    <p class="text-sm text-gray-600">VII, VIII, IX</p>
  </div>
  
  <div class="stat-card hover:border-[<?= $color['warna_primary'] ?>]/80">
    <div class="flex items-center justify-between mb-4">
      <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-amber-500 to-amber-600 flex items-center justify-center shadow-lg">
        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewbox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
      </div><span class="text-xs font-bold text-amber-600 uppercase tracking-wider"><?= lang('GuruMapel/TargetTahfidz.stat_status') ?></span>
    </div>
    <p class="text-3xl font-black text-gray-900 mb-1">100%</p>
    <p class="text-sm text-gray-600"><?= lang('GuruMapel/TargetTahfidz.stat_configured') ?></p>
  </div>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 mb-6">
  <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
    
    <div>
        <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2"><?= lang('GuruMapel/TargetTahfidz.filter_year') ?></label> 
        <select id="filter_tahun" onchange="filterTable()" class="w-full px-4 py-2.5 bg-white border-2 border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[<?= $color['warna_primary'] ?>]/70 focus:border-transparent">
            <option value=""><?= lang('GuruMapel/TargetTahfidz.filter_all_years') ?></option>
            <option value="2024/2025">2024/2025</option>
            <option value="2023/2024">2023/2024</option>
        </select>
    </div>

    <div>
        <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2"><?= lang('GuruMapel/TargetTahfidz.filter_semester') ?></label> 
        <select id="filter_semester" onchange="filterTable()" class="w-full px-4 py-2.5 bg-white border-2 border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[<?= $color['warna_primary'] ?>]/70 focus:border-transparent">
            <option value=""><?= lang('GuruMapel/TargetTahfidz.filter_all_semesters') ?></option>
            <option value="Ganjil">Ganjil</option>
            <option value="Genap">Genap</option>
        </select>
    </div>

    <div>
        <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2"><?= lang('GuruMapel/TargetTahfidz.filter_level') ?></label> 
        <select id="filter_tingkat" onchange="filterTable()" class="w-full px-4 py-2.5 bg-white border-2 border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[<?= $color['warna_primary'] ?>]/70 focus:border-transparent">
            <option value=""><?= lang('GuruMapel/TargetTahfidz.filter_all_levels') ?></option>
            <option value="VII">VII</option>
            <option value="VIII">VIII</option>
            <option value="IX">IX</option>
        </select>
    </div>

    <div>
        <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2"><?= lang('GuruMapel/TargetTahfidz.filter_status') ?></label> 
        <select id="filter_status" onchange="filterTable()" class="w-full px-4 py-2.5 bg-white border-2 border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[<?= $color['warna_primary'] ?>]/70 focus:border-transparent">
            <option value=""><?= lang('GuruMapel/TargetTahfidz.filter_all_status') ?></option>
            <option value="Aktif"><?= lang('GuruMapel/TargetTahfidz.status_active') ?></option>
            <option value="Nonaktif"><?= lang('GuruMapel/TargetTahfidz.status_inactive') ?></option> 
        </select>
    </div>
  </div>

  <div class="flex items-center justify-between pt-4 border-t border-gray-100">
      <label class="flex items-center gap-3 cursor-pointer"> 
          <input type="checkbox" id="check_aktif" onchange="toggleActiveCheck()" class="w-5 h-5 accent-[<?= $color['warna_primary'] ?>] cursor-pointer"> 
          <span class="text-sm font-medium text-gray-700"><?= lang('GuruMapel/TargetTahfidz.filter_active_only') ?></span> 
      </label> 
      
      <button onclick="resetFilter()" class="text-sm text-[<?= $color['warna_primary'] ?>]/80 hover:text-[<?= $color['warna_primary'] ?>] font-semibold flex items-center gap-1">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewbox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
        </svg> <?= lang('GuruMapel/TargetTahfidz.btn_reset_filter') ?> 
      </button>
  </div>
</div>

<div class="table-wrapper mb-6">
  <table>
    <thead>
      <tr class="bg-[<?= $color['warna_primary'] ?>]">
        <th><?= lang('GuruMapel/TargetTahfidz.th_level') ?></th>
        <th><?= lang('GuruMapel/TargetTahfidz.th_semester') ?></th>
        <th><?= lang('GuruMapel/TargetTahfidz.th_target_juz') ?></th>
        <th><?= lang('GuruMapel/TargetTahfidz.th_target_surah') ?></th>
        <th><?= lang('GuruMapel/TargetTahfidz.th_min_memorization') ?></th>
        <th><?= lang('GuruMapel/TargetTahfidz.th_status') ?></th>
        <th class="text-center"><?= lang('GuruMapel/TargetTahfidz.th_action') ?></th>
      </tr>
    </thead>
<tbody>
  <?php if (empty($targets)) : ?>
    <tr>
      <td colspan="7" class="text-center p-8 text-gray-500">
        <div class="flex flex-col items-center justify-center">
          <svg class="w-10 h-10 mb-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
          <p><?= lang('GuruMapel/TargetTahfidz.empty_data') ?></p>
        </div>
      </td>
    </tr>
  <?php else : ?>
    
    <?php foreach ($targets as $t) : ?>
      <tr class="target-row hover:bg-gray-50 transition-colors" 
          data-tingkat="<?= $t['tingkat'] ?>" 
          data-semester="<?= $t['semester'] ?>" 
          data-status="<?= $t['status'] ?>">
        <td class="font-bold text-emerald-700">
            <?= $t['tingkat'] ?>
        </td>

        <td>
            <?= $t['semester'] ?>
        </td>

        <td class="font-semibold">
            <?= $t['nama_juz'] ?>
        </td>

        <td>
          <div class="text-sm font-medium text-gray-800">
            <?= $t['surah_mulai'] ?> - <?= $t['surah_sampai'] ?>
          </div>
          <div class="text-xs text-gray-500 mt-1">
              <?= lang('GuruMapel/TargetTahfidz.target_semester') ?>
          </div>
        </td>

        <td>
          <span class="badge badge-success"><?= $t['minimal_hafalan'] ?>%</span>
          <div class="text-xs text-gray-600 mt-1">
            <?= lang('GuruMapel/TargetTahfidz.min_memorization') ?>
          </div>
        </td>

        <td>
            <?php if ($t['status'] == 'Aktif') : ?>
                <span class="px-2 py-1 text-xs font-semibold text-emerald-700 bg-emerald-100 rounded-lg border border-emerald-200">
                    <?= lang('GuruMapel/TargetTahfidz.status_active') ?>
                </span>
            <?php else : ?>
                <span class="px-2 py-1 text-xs font-semibold text-gray-600 bg-gray-100 rounded-lg border border-gray-200">
                    <?= lang('GuruMapel/TargetTahfidz.status_inactive') ?>
                </span>
            <?php endif; ?>
        </td>

        <td>
          <div class="flex items-center justify-center gap-2">
            <button onclick="showDetailDrawer('<?= $t['tingkat'] ?>', '<?= $t['semester'] ?>', '<?= $t['nama_juz'] ?>')" 
        class="p-2 bg-[<?= $color['warna_secondary'] ?>]/70 text-[<?= $color['warna_primary'] ?>] rounded-lg hover:bg-[<?= $color['warna_secondary'] ?>] transition-colors border border-[<?= $color['warna_primary'] ?>]/80 flex items-center gap-1 text-xs font-semibold">
  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
  </svg> 
  <?= lang('GuruMapel/TargetTahfidz.btn_detail') ?>
</button>

            <button onclick='showEditModal(<?= json_encode($t) ?>)' 
        class="p-2 bg-white text-gray-600 rounded-lg hover:bg-gray-50 transition-colors border border-gray-300 flex items-center gap-1 text-xs font-semibold">
  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
  </svg> 
  <?= lang('GuruMapel/TargetTahfidz.btn_edit') ?>
</button>
          </div>
        </td>
      </tr>
    <?php endforeach; ?>
  <?php endif; ?>
</tbody>
            </table>
      <div class="info-panel mb-6 border-[<?= $color['warna_primary'] ?>]/80 bg-[<?= $color['warna_secondary'] ?>]">
  <div class="flex items-start gap-4">
    <div class="w-14 h-14 rounded-2xl bg-[<?= $color['warna_primary'] ?>]/80 flex items-center justify-center flex-shrink-0 shadow-lg">
      <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewbox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
      </svg>
    </div>
    <div class="flex-1">
      <h3 class="font-bold text-[<?= $color['warna_primary'] ?>] text-lg mb-3"><?= lang('GuruMapel/TargetTahfidz.integration_title') ?></h3>
      <p class="text-sm text-[<?= $color['warna_primary'] ?>]/90 mb-4"><?= lang('GuruMapel/TargetTahfidz.integration_desc') ?></p>
      <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
        <div class="flex items-start gap-3 bg-white bg-opacity-50 rounded-xl p-3">
          <svg class="w-5 h-5 text-[<?= $color['warna_primary'] ?>]/70 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewbox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
          <div>
            <p class="text-sm font-semibold text-gray-900"><?= lang('GuruMapel/TargetTahfidz.integ_spiritual') ?></p>
            <p class="text-xs text-gray-700 mt-1"><?= lang('GuruMapel/TargetTahfidz.integ_spiritual_desc') ?></p>
          </div>
        </div>
        <div class="flex items-start gap-3 bg-white bg-opacity-50 rounded-xl p-3">
          <svg class="w-5 h-5 text-[<?= $color['warna_primary'] ?>] flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewbox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
          <div>
            <p class="text-sm font-semibold text-gray-900"><?= lang('GuruMapel/TargetTahfidz.integ_report') ?></p>
            <p class="text-xs text-gray-700 mt-1"><?= lang('GuruMapel/TargetTahfidz.integ_report_desc') ?></p>
          </div>
        </div>
        <div class="flex items-start gap-3 bg-white bg-opacity-50 rounded-xl p-3">
          <svg class="w-5 h-5 text-[<?= $color['warna_primary'] ?>] flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewbox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
          <div>
            <p class="text-sm font-semibold text-gray-900"><?= lang('GuruMapel/TargetTahfidz.integ_monitor') ?></p>
            <p class="text-xs text-gray-700 mt-1"><?= lang('GuruMapel/TargetTahfidz.integ_monitor_desc') ?></p>
          </div>
        </div>
        <div class="flex items-start gap-3 bg-white bg-opacity-50 rounded-xl p-3">
          <svg class="w-5 h-5 text-[<?= $color['warna_primary'] ?>] flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewbox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
          <div>
            <p class="text-sm font-semibold text-gray-900"><?= lang('GuruMapel/TargetTahfidz.integ_insight') ?></p>
            <p class="text-xs text-gray-700 mt-1"><?= lang('GuruMapel/TargetTahfidz.integ_insight_desc') ?></p>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('modals') ?>

<div id="drawerOverlay" class="drawer-overlay fixed inset-0 hidden bg-black/50 transition-opacity" style="z-index: 99998;" onclick="closeDrawer()"></div>

<div id="detailDrawer" class="drawer fixed inset-y-0 right-0 hidden bg-white shadow-2xl w-80 md:w-96 transition-transform duration-300 transform translate-x-full" style="z-index: 99999;">
  <div class="p-6 border-b border-gray-100 bg-gradient-to-r from-[<?= $color['warna_secondary'] ?>]/40 to-white sticky top-0 z-10">
    <div class="flex items-center justify-between mb-4">
      <h3 class="text-xl font-bold text-gray-800"><?= lang('GuruMapel/TargetTahfidz.drawer_title') ?></h3>
      <button onclick="closeDrawer()" class="p-2 hover:bg-gray-100 rounded-lg transition-colors cursor-pointer">
        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg>
      </button>
    </div>
    <div class="flex items-center gap-3">
      <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-[<?= $color['warna_primary'] ?>]/80 to-[<?= $color['warna_primary'] ?>] flex items-center justify-center shadow-lg">
        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
        </svg>
      </div>
      <div>
        <p class="text-lg font-bold text-gray-900" id="drawerTitle">Target Juz 30</p>
        <p class="text-sm text-gray-600" id="drawerSubtitle">Tingkat VII - Semester Ganjil</p>
      </div>
    </div>
  </div>

  <div class="p-6 space-y-5 overflow-y-auto" style="height: calc(100vh - 180px);">
    <div>
      <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2"><?= lang('GuruMapel/TargetTahfidz.stat_academic_year') ?></label>
      <div class="p-4 bg-gray-50 rounded-xl">
        <p class="font-semibold text-gray-800">2024/2025</p>
        <p class="text-sm text-gray-600"><?= lang('GuruMapel/TargetTahfidz.drawer_status', ['Ganjil']) ?></p>
      </div>
    </div>
    <div class="pt-4 border-t border-gray-200 space-y-3">
      <button onclick="showEditModal('VII', 'Ganjil')" class="w-full px-6 py-3 bg-[<?= $color['warna_primary'] ?>]/80 hover:bg-[<?= $color['warna_primary'] ?>] text-white font-semibold rounded-xl transition-colors shadow-lg shadow-[<?= $color['warna_primary'] ?>]/20 flex items-center justify-center gap-2 cursor-pointer">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
        </svg> <?= lang('GuruMapel/TargetTahfidz.btn_edit_target') ?>
      </button>
    </div>
  </div>
</div>

<div id="addTargetModal" class="hidden fixed inset-0 z-[99999] overflow-y-auto">
  <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" onclick="closeAddTargetModal()"></div>

  <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
    <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
      
      <div class="bg-gradient-to-r from-emerald-50 to-white px-4 py-3 sm:px-6 border-b border-gray-100 flex justify-between items-center">
        <div>
          <h3 class="text-lg font-bold leading-6 text-gray-900"><?= lang('GuruMapel/TargetTahfidz.modal_add_title') ?></h3>
          <p class="text-sm text-gray-500 mt-1"><?= lang('GuruMapel/TargetTahfidz.modal_add_desc') ?></p>
        </div>
        <button type="button" onclick="closeAddTargetModal()" class="text-gray-400 hover:text-gray-500">
          <span class="sr-only">Close</span>
          <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
        </button>
      </div>

      <form id="formAddTarget" 
      action="<?= base_url('admin/target-tahfidz/store') ?>" 
      onsubmit="saveTarget(event)">
        <div class="px-4 py-5 sm:p-6 space-y-4">
          
          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1"><?= lang('GuruMapel/TargetTahfidz.form_level') ?> <span class="text-red-500">*</span></label>
              <select name="tingkat" required class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm py-2.5 px-3 border">
                <option value=""><?= lang('GuruMapel/TargetTahfidz.form_select_ph') ?></option>
                <option value="VII">VII</option>
                <option value="VIII">VIII</option>
                <option value="IX">IX</option>
              </select>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1"><?= lang('GuruMapel/TargetTahfidz.form_semester') ?> <span class="text-red-500">*</span></label>
              <select name="semester" required class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm py-2.5 px-3 border">
                <option value=""><?= lang('GuruMapel/TargetTahfidz.form_select_ph') ?></option>
                <option value="Ganjil">Ganjil</option>
                <option value="Genap">Genap</option>
              </select>
            </div>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1"><?= lang('GuruMapel/TargetTahfidz.form_target_juz') ?> <span class="text-red-500">*</span></label>
            <select name="juz_id" required class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm py-2.5 px-3 border">
              <option value=""><?= lang('GuruMapel/TargetTahfidz.form_select_juz') ?></option>
              <?php foreach ($ref_juz as $juz): ?>
                <option value="<?= $juz['id'] ?>"><?= $juz['nama_juz'] ?> <?= $juz['keterangan'] ? "({$juz['keterangan']})" : '' ?></option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1"><?= lang('GuruMapel/TargetTahfidz.form_start_surah') ?> <span class="text-red-500">*</span></label>
              <select name="surah_mulai_id" required class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm py-2.5 px-3 border select2-surah">
                <option value=""><?= lang('GuruMapel/TargetTahfidz.form_select_surah') ?></option>
                <?php foreach ($ref_surah as $s): ?>
                  <option value="<?= $s['id'] ?>"><?= $s['no_surah'] ?>. <?= $s['nama_surah'] ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1"><?= lang('GuruMapel/TargetTahfidz.form_end_surah') ?> <span class="text-red-500">*</span></label>
              <select name="surah_sampai_id" required class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm py-2.5 px-3 border select2-surah">
                <option value=""><?= lang('GuruMapel/TargetTahfidz.form_select_surah') ?></option>
                <?php foreach ($ref_surah as $s): ?>
                  <option value="<?= $s['id'] ?>"><?= $s['no_surah'] ?>. <?= $s['nama_surah'] ?></option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>

        </div>
        
        <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
          <button type="submit" class="inline-flex w-full justify-center rounded-lg bg-emerald-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-emerald-500 sm:ml-3 sm:w-auto"><?= lang('GuruMapel/TargetTahfidz.btn_save') ?></button>
          <button type="button" onclick="closeAddTargetModal()" class="mt-3 inline-flex w-full justify-center rounded-lg bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto"><?= lang('GuruMapel/TargetTahfidz.btn_cancel') ?></button>
        </div>
      </form>
    </div>
  </div>
</div>

<div id="editTargetModal" class="hidden fixed inset-0 z-[99999] overflow-y-auto">
  <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" onclick="closeEditTargetModal()"></div>
  <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
    <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
      
      <div class="bg-gradient-to-r from-amber-50 to-white px-4 py-3 border-b border-gray-100">
        <h3 class="text-lg font-bold text-gray-900"><?= lang('GuruMapel/TargetTahfidz.modal_edit_title') ?></h3>
      </div>

      <form id="formEditTarget" 
            action="<?= base_url('admin/target-tahfidz/update') ?>" 
            onsubmit="updateTarget(event)">
        
        <input type="hidden" name="id" id="edit_id"> 
        <?= csrf_field(); ?>

        <div class="px-4 py-5 space-y-4">
          <div class="bg-amber-50 border border-amber-200 rounded-lg p-3 text-sm text-amber-800">
             <?= lang('GuruMapel/TargetTahfidz.edit_info') ?> <span id="label_tingkat_semester" class="font-bold"></span>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700"><?= lang('GuruMapel/TargetTahfidz.form_target_juz') ?></label>
            <select name="juz_id" id="edit_juz_id" class="block w-full rounded-lg border-gray-300 shadow-sm py-2 px-3 border mt-1">
               <?php foreach ($ref_juz as $juz): ?>
                  <option value="<?= $juz['id'] ?>"><?= $juz['nama_juz'] ?></option>
               <?php endforeach; ?>
            </select>
          </div>
          
           <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium text-gray-700"><?= lang('GuruMapel/TargetTahfidz.form_start_surah') ?></label>
              <select name="surah_mulai_id" id="edit_surah_mulai_id" class="block w-full border rounded-lg py-2 px-3 mt-1">
                  <?php foreach ($ref_surah as $s): ?>
                  <option value="<?= $s['id'] ?>"><?= $s['no_surah'] ?>. <?= $s['nama_surah'] ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700"><?= lang('GuruMapel/TargetTahfidz.form_end_surah') ?></label>
              <select name="surah_sampai_id" id="edit_surah_sampai_id" class="block w-full border rounded-lg py-2 px-3 mt-1">
                  <?php foreach ($ref_surah as $s): ?>
                  <option value="<?= $s['id'] ?>"><?= $s['no_surah'] ?>. <?= $s['nama_surah'] ?></option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>
        </div>

        <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse">
          <button type="submit" class="inline-flex w-full justify-center rounded-lg bg-amber-600 px-3 py-2 text-sm font-semibold text-white hover:bg-amber-500 sm:ml-3 sm:w-auto"><?= lang('GuruMapel/TargetTahfidz.btn_update') ?></button>
          <button type="button" onclick="closeEditTargetModal()" class="mt-3 inline-flex w-full justify-center rounded-lg bg-white px-3 py-2 text-sm font-semibold text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto"><?= lang('GuruMapel/TargetTahfidz.btn_cancel') ?></button>
        </div>
      </form>
    </div>
  </div>
</div>
<div id="templateModal" class="fixed inset-0 hidden" style="z-index: 99999;">
  <div class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity pointer-events-auto" onclick="closeTemplateModal()"></div>

  <div class="absolute inset-0 flex items-center justify-center md:pl-64 p-4 pointer-events-none">
    <div class="relative w-full bg-white rounded-2xl shadow-2xl flex flex-col max-h-[90vh] pointer-events-auto" style="max-width: 800px;">
      <div class="p-6 border-b border-gray-100 bg-gradient-to-r from-purple-50 to-white rounded-t-2xl z-20 flex-shrink-0">
        <div class="flex items-center justify-between">
          <div>
            <h3 class="text-xl font-bold text-gray-800"><?= lang('GuruMapel/TargetTahfidz.modal_template_title') ?></h3>
            <p class="text-sm text-gray-500 mt-1"><?= lang('GuruMapel/TargetTahfidz.modal_template_desc') ?></p>
          </div>
          <button onclick="closeTemplateModal()" class="p-2 hover:bg-gray-100 rounded-lg transition-colors cursor-pointer relative z-50">
            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>
      </div>
      <div class="flex-1 overflow-y-auto p-6 relative z-10 space-y-4">
        <div class="border-2 border-gray-200 rounded-xl p-5 hover:border-emerald-500 transition-colors cursor-pointer" onclick="selectTemplate('juz-amma')">
          <div class="flex items-start justify-between mb-4">
            <div class="flex items-center gap-3">
              <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-emerald-500 to-emerald-600 flex items-center justify-center">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                </svg>
              </div>
              <div>
                <h4 class="font-bold text-gray-800 text-lg"><?= lang('GuruMapel/TargetTahfidz.template_juz_amma') ?></h4>
                <p class="text-sm text-gray-500"><?= lang('GuruMapel/TargetTahfidz.template_juz_desc') ?></p>
              </div>
            </div>
            <span class="badge badge-success"><?= lang('GuruMapel/TargetTahfidz.badge_popular') ?></span>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="<?= base_url('assets/js/Admin/target-tahfidz.js') ?>"></script>
<?= $this->endSection() ?>