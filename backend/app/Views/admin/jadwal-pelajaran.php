<?= $this->extend('layout/main') ?>

<?= $this->section('title') ?>
<?= lang('Admin/JadwalPelajaran.page_title') ?> - Rapor Digital SMPIT Ad Durrah
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
  :root {
    --warna-scroll: <?= $color['warna_primary'] ?>;
  }
</style>
<link rel="stylesheet" href="<?= base_url('assets/css/Admin/jadwal-pelajaran.css') ?>">
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="mb-6">
  <div class="flex items-center gap-2 text-sm text-gray-500 dark:text-slate-400 mb-2 transition-colors">
    <span><?= lang('Admin/JadwalPelajaran.breadcrumb') ?></span>
    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewbox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
    </svg>
    <span class="text-[<?= $color['warna_primary'] ?>] font-medium"><?= lang('Admin/JadwalPelajaran.page_title') ?></span>
  </div>
  <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
    <div>
      <h1 class="text-2xl md:text-3xl font-bold text-gray-800 dark:text-white mb-2 transition-colors"><?= lang('Admin/JadwalPelajaran.page_title') ?></h1>
      <p class="text-sm md:text-base text-gray-600 dark:text-slate-400 transition-colors"><?= lang('Admin/JadwalPelajaran.page_desc') ?></p>
    </div>
    <div class="flex flex-wrap items-center gap-2">
      <button onclick="showCreateScheduleModal()" class="px-4 py-2.5 bg-[<?= $color['warna_primary'] ?>]/90 hover:bg-[<?= $color['warna_primary'] ?>] text-white font-semibold rounded-xl transition-all shadow-lg shadow-[<?= $color['warna_primary'] ?>]/20 flex items-center gap-2 transform hover:-translate-y-0.5 outline-none">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewbox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
        </svg><span><?= lang('Admin/JadwalPelajaran.btn_create') ?></span> </button>

      <button onclick="showImportModal()" class="px-4 py-2.5 bg-white dark:bg-slate-800 border border-gray-300 dark:border-slate-600 text-gray-700 dark:text-slate-300 font-semibold rounded-xl hover:bg-gray-50 dark:hover:bg-slate-700 transition-colors flex items-center gap-2 shadow-sm outline-none">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewbox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10" />
        </svg><span class="hidden md:inline"><?= lang('Admin/JadwalPelajaran.btn_import') ?></span> </button>

      <button id="btnLockToggle" onclick="showLockScheduleModal()" class="px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-xl transition-all shadow-lg shadow-blue-600/30 flex items-center gap-2 transform hover:-translate-y-0.5 outline-none">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewbox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
        </svg><span><?= lang('Admin/JadwalPelajaran.btn_lock') ?></span> </button>
    </div>
  </div>
</div>

<div class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-gray-100 dark:border-slate-700 p-6 mb-6 transition-colors">
  <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">

    <div>
      <label class="block text-xs font-bold text-gray-600 dark:text-slate-400 uppercase tracking-wider mb-2">Tahun Ajaran & Semester</label>
      <select id="tahunSemesterFilter" onchange="window.location.href='?ta=' + this.value.split('_')[0] + '&semester=' + this.value.split('_')[1]" class="w-full px-4 py-2.5 bg-white dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-xl text-sm font-medium text-gray-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-[<?= $color['warna_primary'] ?>] transition-colors appearance-none cursor-pointer outline-none">
        <?php foreach ($ta_list as $ta): ?>
          <option value="<?= $ta['id'] ?>_<?= $ta['semester'] ?>" <?= ($ta['id'] == ($ta_filter_id ?? 0) && $ta['semester'] == ($ta_filter_semester ?? '')) ? 'selected' : '' ?>>
            <?= esc($ta['tahun']) ?> - <?= esc($ta['semester']) ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>

    <div>
      <label class="block text-xs font-bold text-gray-600 dark:text-slate-400 uppercase tracking-wider mb-2"><?= lang('Admin/JadwalPelajaran.room') ?></label>
      <select id="rombelFilter" onchange="filterSchedule()" class="w-full px-4 py-2.5 bg-white dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-xl text-sm font-medium text-gray-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-[<?= $color['warna_primary'] ?>] transition-colors appearance-none cursor-pointer outline-none">
        <option value=""><?= lang('Admin/JadwalPelajaran.select_room') ?></option>
        <?php
        $current_tingkat = '';
        foreach ($rombels as $r):
          if ($current_tingkat !== $r['tingkat']) {
            if ($current_tingkat !== '') echo '</optgroup>';
            $current_tingkat = $r['tingkat'];
            echo '<optgroup label="' . lang('Admin/JadwalPelajaran.class') . ' ' . esc($current_tingkat) . '" class="dark:bg-slate-800 text-gray-500 dark:text-slate-400 font-bold">';
          }
        ?>
          <option value="<?= $r['id'] ?>" class="text-gray-800 dark:text-white font-medium"><?= esc($current_tingkat) ?> - <?= esc($r['nama_rombel']) ?></option>
        <?php endforeach; ?>
        <?php if ($current_tingkat !== '') echo '</optgroup>'; ?>
      </select>
    </div>

    <div>
      <label class="block text-xs font-bold text-gray-600 dark:text-slate-400 uppercase tracking-wider mb-2"><?= lang('Admin/JadwalPelajaran.mode') ?></label>
      <div class="flex items-center gap-2 bg-gray-50 dark:bg-slate-900/50 p-1 rounded-xl border border-gray-100 dark:border-slate-700 transition-colors">
        <button id="btnModeView" class="flex-1 py-1.5 px-3 rounded-lg text-sm font-bold bg-white dark:bg-slate-700 text-[<?= $color['warna_primary'] ?>] shadow-sm transition-colors outline-none flex justify-center items-center" onclick="toggleMode('view')">
          <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewbox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
          </svg> <?= lang('Admin/JadwalPelajaran.mode_view') ?>
        </button>
        <button id="btnModeEdit" class="flex-1 py-1.5 px-3 rounded-lg text-sm font-medium text-gray-500 dark:text-slate-400 hover:text-gray-800 dark:hover:text-white transition-colors outline-none flex justify-center items-center" onclick="toggleMode('edit')">
          <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewbox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
          </svg> <?= lang('Admin/JadwalPelajaran.mode_edit') ?>
        </button>
      </div>
    </div>
  </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
  <div class="stat-card bg-white dark:bg-slate-800 rounded-3xl p-5 shadow-sm border border-gray-100 dark:border-slate-700 hover:shadow-md hover:border-[<?= $color['warna_primary'] ?>] transition-all group">
    <div class="flex items-center justify-between mb-3">
      <div class="w-12 h-12 rounded-xl bg-emerald-100 dark:bg-emerald-900/30 flex items-center justify-center transition-colors">
        <svg class="w-6 h-6 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewbox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
        </svg>
      </div><span class="text-[10px] font-black text-emerald-600 dark:text-emerald-400 uppercase tracking-wider bg-emerald-50 dark:bg-emerald-900/20 px-2 py-1 rounded-lg"><?= lang('Admin/JadwalPelajaran.total_hours') ?></span>
    </div>
    <p id="statTotalJP" class="text-3xl font-black text-gray-900 dark:text-white mb-1">0 JP</p>
    <p class="text-xs font-medium text-gray-500 dark:text-slate-400"><?= lang('Admin/JadwalPelajaran.hours_per_week') ?></p>
  </div>
  <div class="stat-card bg-white dark:bg-slate-800 rounded-3xl p-5 shadow-sm border border-gray-100 dark:border-slate-700 hover:shadow-md hover:border-[<?= $color['warna_primary'] ?>] transition-all group">
    <div class="flex items-center justify-between mb-3">
      <div class="w-12 h-12 rounded-xl bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center transition-colors">
        <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewbox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
        </svg>
      </div><span class="text-[10px] font-black text-blue-600 dark:text-blue-400 uppercase tracking-wider bg-blue-50 dark:bg-blue-900/20 px-2 py-1 rounded-lg"><?= lang('Admin/JadwalPelajaran.teacher_load') ?></span>
    </div>
    <p id="statTeacherLoad" class="text-3xl font-black text-gray-900 dark:text-white mb-1">0 JP</p>
    <p class="text-xs font-medium text-gray-500 dark:text-slate-400"><?= lang('Admin/JadwalPelajaran.avg_per_teacher') ?></p>
  </div>
  <div class="stat-card bg-white dark:bg-slate-800 rounded-3xl p-5 shadow-sm border border-gray-100 dark:border-slate-700 hover:shadow-md hover:border-[<?= $color['warna_primary'] ?>] transition-all group">
    <div class="flex items-center justify-between mb-3">
      <div class="w-12 h-12 rounded-xl bg-amber-100 dark:bg-amber-900/30 flex items-center justify-center transition-colors">
        <svg class="w-6 h-6 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewbox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
      </div><span class="text-[10px] font-black text-amber-600 dark:text-amber-400 uppercase tracking-wider bg-amber-50 dark:bg-amber-900/20 px-2 py-1 rounded-lg"><?= lang('Admin/JadwalPelajaran.empty_slots') ?></span>
    </div>
    <p id="statEmptySlots" class="text-3xl font-black text-gray-900 dark:text-white mb-1">40 Slot</p>
    <p class="text-xs font-medium text-gray-500 dark:text-slate-400"><?= lang('Admin/JadwalPelajaran.needs_filling') ?></p>
  </div>
  <div class="stat-card bg-white dark:bg-slate-800 rounded-3xl p-5 shadow-sm border border-gray-100 dark:border-slate-700 hover:shadow-md hover:border-[<?= $color['warna_primary'] ?>] transition-all group">
    <div class="flex items-center justify-between mb-3">
      <div class="w-12 h-12 rounded-xl bg-purple-100 dark:bg-purple-900/30 flex items-center justify-center transition-colors">
        <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewbox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
        </svg>
      </div><span class="text-[10px] font-black text-purple-600 dark:text-purple-400 uppercase tracking-wider bg-purple-50 dark:bg-purple-900/20 px-2 py-1 rounded-lg"><?= lang('Admin/JadwalPelajaran.status') ?></span>
    </div>
    <p id="statPercentage" class="text-3xl font-black text-gray-900 dark:text-white mb-1">0%</p>
    <p class="text-xs font-medium text-gray-500 dark:text-slate-400"><?= lang('Admin/JadwalPelajaran.schedule_filled') ?></p>
  </div>
</div>

<div id="scheduleContainer" class="w-full overflow-x-auto custom-scrollbar bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-gray-100 dark:border-slate-700 transition-colors p-4 md:p-6 mb-6 min-h-[300px]"></div>

<div id="drawerOverlay" class="drawer-overlay fixed inset-0 hidden bg-gray-900/60 backdrop-blur-sm transition-opacity" style="z-index: 99998;" onclick="closeDrawer()"></div>
<div id="scheduleDrawer" class="drawer fixed inset-y-0 right-0 hidden bg-white dark:bg-slate-800 shadow-2xl w-80 md:w-96 transition-transform duration-300 transform translate-x-full border-l border-gray-200 dark:border-slate-700" style="z-index: 99999;">
  <input type="hidden" id="selectedScheduleId">
  <div class="p-6 border-b border-gray-100 dark:border-slate-700 transition-colors bg-white dark:bg-slate-800 z-10 sticky top-0">
    <div class="flex items-center justify-between mb-5">
      <h3 class="text-xl font-bold text-gray-800 dark:text-white"><?= lang('Admin/JadwalPelajaran.drawer_title') ?></h3>
      <button onclick="closeDrawer()" class="p-2 hover:bg-gray-100 dark:hover:bg-slate-700 rounded-full transition-colors cursor-pointer text-gray-500 dark:text-slate-400 outline-none"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg></button>
    </div>
    <div class="flex items-center gap-4">
      <div class="w-14 h-14 rounded-2xl flex items-center justify-center shadow-lg flex-shrink-0 bg-[<?= $color['warna_secondary'] ?>] dark:bg-[<?= $color['warna_primary'] ?>]/20">
        <svg class="w-7 h-7 text-[<?= $color['warna_primary'] ?>]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
        </svg>
      </div>
      <div class="min-w-0">
        <p class="text-xl font-bold text-gray-900 dark:text-white truncate" id="drawerSubject">Matematika</p>
        <p class="text-sm font-medium text-gray-500 dark:text-slate-400 mt-1" id="drawerTime">Senin, Jam ke-1 (07:30 - 08:10)</p>
      </div>
    </div>
  </div>
  <div class="p-6 space-y-5 overflow-y-auto flex-1 custom-scrollbar">
    <div>
      <label class="block text-[11px] font-bold text-gray-500 dark:text-slate-400 uppercase tracking-wider mb-2"><?= lang('Admin/JadwalPelajaran.drawer_teacher') ?></label>
      <div class="flex items-center gap-3 p-4 bg-gray-50 dark:bg-slate-700/50 rounded-xl border border-gray-100 dark:border-slate-600 transition-colors">
        <div class="w-10 h-10 rounded-full flex items-center justify-center text-white font-bold text-sm shadow-sm flex-shrink-0" style="background-color: <?= $color['warna_primary'] ?>;">GU</div>
        <div class="min-w-0">
          <p class="font-bold text-gray-800 dark:text-white truncate" id="drawerTeacher"></p>
        </div>
      </div>
    </div>
    <div>
      <label class="block text-[11px] font-bold text-gray-500 dark:text-slate-400 uppercase tracking-wider mb-2"><?= lang('Admin/JadwalPelajaran.drawer_room') ?></label>
      <div class="p-4 bg-gray-50 dark:bg-slate-700/50 rounded-xl border border-gray-100 dark:border-slate-600 transition-colors">
        <p class="font-bold text-gray-800 dark:text-white" id="drawerRombel"></p>
      </div>
    </div>
    <div id="drawerActionButtons" class="pt-6 border-t border-gray-200 dark:border-slate-700 space-y-3 hidden transition-colors">
      <button onclick="prepareEditJadwal('full')" class="w-full px-6 py-3.5 hover:brightness-95 text-white font-bold rounded-xl transition-all transform hover:-translate-y-0.5 shadow-lg flex items-center justify-center gap-2 cursor-pointer outline-none" style="background-color: <?= $color['warna_primary'] ?>; box-shadow: 0 10px 15px -3px <?= $color['warna_primary'] ?>40;">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
        </svg> <?= lang('Admin/JadwalPelajaran.btn_edit_schedule') ?>
      </button>
      <button onclick="deleteScheduleSlot()" class="w-full px-6 py-3.5 bg-white dark:bg-slate-700 border-2 border-red-200 dark:border-red-800/50 text-red-600 dark:text-red-400 font-bold rounded-xl hover:bg-red-50 dark:hover:bg-slate-600 transition-colors flex items-center justify-center gap-2 cursor-pointer outline-none shadow-sm">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
        </svg> <?= lang('Admin/JadwalPelajaran.btn_clear_slot') ?>
      </button>
    </div>
  </div>
</div>

<div id="createScheduleModal" class="fixed inset-0 hidden" style="z-index: 99999;">
  <div class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" onclick="closeCreateScheduleModal()"></div>
  <div class="absolute inset-0 flex items-center justify-center p-4 pointer-events-none md:pl-64">
    <div class="relative w-full bg-white dark:bg-slate-800 rounded-3xl shadow-2xl flex flex-col max-h-[90vh] pointer-events-auto border border-transparent dark:border-slate-700 transition-colors duration-300" style="max-width: 600px;">
      <div class="p-6 border-b border-gray-100 dark:border-slate-700 flex items-center justify-between bg-white dark:bg-slate-800 rounded-t-3xl z-20 flex-shrink-0 transition-colors">
        <div>
          <h3 class="text-xl font-bold text-gray-800 dark:text-white"><?= lang('Admin/JadwalPelajaran.modal_create_title') ?></h3>
          <p class="text-sm font-medium text-gray-500 dark:text-slate-400 mt-1"><?= lang('Admin/JadwalPelajaran.modal_create_desc') ?></p>
        </div>
        <button type="button" onclick="closeCreateScheduleModal()" class="p-2 hover:bg-gray-100 dark:hover:bg-slate-700 rounded-full transition-colors cursor-pointer relative z-50 text-gray-500 dark:text-slate-400 outline-none"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
          </svg></button>
      </div>

      <div class="flex-1 overflow-y-auto p-6 relative z-10 custom-scrollbar">
        <form id="formTambahJadwal" class="space-y-5" onsubmit="handleCreateSchedule(event)">
          <?= csrf_field() ?>
          <input type="hidden" name="id_jadwal" id="inputIdJadwal">

          <div class="grid grid-cols-1 gap-4">
            <div>
              <label class="block text-sm font-bold text-gray-700 dark:text-slate-300 mb-2">Tahun Ajaran & Semester (Aktif) <span class="text-red-500">*</span></label>
              <input type="hidden" name="id_tahun_ajaran" value="<?= $ta_aktif['id'] ?? '' ?>">
              <input type="hidden" name="semester" value="<?= $ta_aktif['semester'] ?? '' ?>">
              <input type="text" readonly value="<?= $ta_aktif['tahun'] ?? 'TIDAK ADA' ?> - <?= $ta_aktif['semester'] ?? 'DATA' ?>" class="w-full px-4 py-3 bg-gray-100 dark:bg-slate-900 border border-gray-200 dark:border-slate-600 rounded-xl text-sm text-gray-500 dark:text-slate-400 cursor-not-allowed outline-none shadow-sm">
            </div>
          </div>

          <div>
            <label class="block text-sm font-bold text-gray-700 dark:text-slate-300 mb-2"><?= lang('Admin/JadwalPelajaran.drawer_room') ?> <span class="text-red-500">*</span></label>
            <select name="rombel_id" id="add_rombel_id" required class="w-full px-4 py-3 bg-white dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-xl text-sm text-gray-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-[<?= $color['warna_primary'] ?>] transition-colors appearance-none cursor-pointer outline-none shadow-sm">
              <option value=""><?= lang('Admin/JadwalPelajaran.select_room') ?></option>
              <?php foreach ($rombels as $r): ?>
                <option value="<?= $r['id'] ?>"><?= $r['tingkat'] ?> - <?= $r['nama_rombel'] ?></option>
              <?php endforeach; ?>
            </select>
          </div>

          <div>
            <label class="block text-sm font-bold text-gray-700 dark:text-slate-300 mb-2"><?= lang('Admin/JadwalPelajaran.lbl_subject') ?> <span class="text-red-500">*</span></label>
            <select name="mapel_id" id="add_mapel_id" required class="w-full px-4 py-3 bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-600 rounded-xl text-sm text-gray-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-[<?= $color['warna_primary'] ?>] transition-colors appearance-none outline-none shadow-sm pointer-events-none opacity-70">
              <option value="">Pilih Rombel Terlebih Dahulu</option>
            </select>
          </div>

          <div>
            <label class="block text-sm font-bold text-gray-700 dark:text-slate-300 mb-2"><?= lang('Admin/JadwalPelajaran.lbl_teacher') ?> <span class="text-red-500">*</span></label>
            <select name="guru_id" id="add_guru_id" required disabled class="w-full px-4 py-3 bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-600 rounded-xl text-sm text-gray-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-[<?= $color['warna_primary'] ?>] transition-colors appearance-none outline-none shadow-sm pointer-events-none opacity-70">
              <option value="">Pilih Mapel Terlebih Dahulu</option>
            </select>
            <p class="text-[11px] text-gray-500 dark:text-slate-400 mt-1.5">*Guru yang memiliki tanda ✓ adalah guru yang ditugaskan (di-mapping) untuk mapel tersebut.</p>
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-bold text-gray-700 dark:text-slate-300 mb-2"><?= lang('Admin/JadwalPelajaran.lbl_day') ?> <span class="text-red-500">*</span></label>
            <select name="hari" id="add_hari" required class="w-full px-4 py-3 bg-white dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-xl text-sm text-gray-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-[<?= $color['warna_primary'] ?>] transition-colors appearance-none cursor-pointer outline-none shadow-sm">
                <option value=""><?= lang('Admin/JadwalPelajaran.ph_day') ?></option>
                <option value="Senin">Senin</option>
                <option value="Selasa">Selasa</option>
                <option value="Rabu">Rabu</option>
                <option value="Kamis">Kamis</option>
                <option value="Jumat">Jumat (Tanpa BPI)</option>
                <option value="Jumat BPI">Jumat (Ada BPI)</option>
              </select>
            </div>
            <div>
              <label class="block text-sm font-bold text-gray-700 dark:text-slate-300 mb-2"><?= lang('Admin/JadwalPelajaran.lbl_period') ?> <span class="text-red-500">*</span></label>
              <select name="jam_ke" id="add_jam_ke" required disabled class="w-full px-4 py-3 bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-600 rounded-xl text-sm text-gray-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-[<?= $color['warna_primary'] ?>] transition-colors appearance-none outline-none shadow-sm pointer-events-none opacity-70">
                <option value="">Pilih Hari Terlebih Dahulu</option>
              </select>
            </div>
          </div>

          <div class="flex gap-3 pt-5 border-t border-gray-100 dark:border-slate-700 sticky bottom-0 bg-white dark:bg-slate-800 z-50 pb-2 transition-colors">
            <button type="button" onclick="closeCreateScheduleModal()" class="flex-1 px-6 py-3.5 bg-white dark:bg-slate-700 border-2 border-gray-300 dark:border-slate-600 text-gray-700 dark:text-slate-300 font-bold rounded-xl hover:bg-gray-50 dark:hover:bg-slate-600 transition-colors cursor-pointer outline-none shadow-sm"><?= lang('Admin/JadwalPelajaran.btn_cancel') ?></button>
            <button type="submit" class="flex-1 px-6 py-3.5 text-white font-bold rounded-xl transition-transform transform hover:-translate-y-0.5 shadow-lg flex justify-center items-center outline-none cursor-pointer" style="background-color: <?= $color['warna_primary'] ?>; box-shadow: 0 10px 15px -3px <?= $color['warna_primary'] ?>40;"><?= lang('Admin/JadwalPelajaran.btn_save_schedule') ?></button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<div id="lockScheduleModal" class="fixed inset-0 hidden" style="z-index: 99999;">
  <div class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity pointer-events-auto" onclick="closeLockScheduleModal()"></div>
  <div class="absolute inset-0 flex items-center justify-center p-4 pointer-events-none md:pl-64">
    <div class="relative w-full bg-white dark:bg-slate-800 rounded-3xl shadow-2xl flex flex-col max-h-[90vh] pointer-events-auto border border-transparent dark:border-slate-700 transition-colors duration-300" style="max-width: 600px;">
      <div class="p-6 border-b border-gray-100 dark:border-slate-700 flex items-center justify-between bg-white dark:bg-slate-800 rounded-t-3xl z-20 flex-shrink-0 transition-colors">
        <div>
          <h3 class="text-xl font-bold text-gray-800 dark:text-white"><?= lang('Admin/JadwalPelajaran.modal_lock_title') ?></h3>
        </div>
        <button onclick="closeLockScheduleModal()" class="p-2 hover:bg-gray-100 dark:hover:bg-slate-700 rounded-full transition-colors cursor-pointer relative z-50 text-gray-500 dark:text-slate-400 outline-none"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
          </svg></button>
      </div>
      <div class="flex-1 overflow-y-auto p-6 relative z-10 space-y-6 custom-scrollbar">
        <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800/50 rounded-2xl p-6 shadow-sm transition-colors">
          <div class="flex items-start gap-4 mb-2">
            <div class="w-12 h-12 rounded-xl bg-blue-600 flex items-center justify-center flex-shrink-0 shadow-md">
              <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
              </svg>
            </div>
            <div>
              <h4 class="font-black text-blue-900 dark:text-blue-400 text-lg mb-1"><?= lang('Admin/JadwalPelajaran.lock_target_title') ?></h4>
              <p class="text-sm font-medium text-blue-800 dark:text-blue-300">Tahun Ajaran Aktif - Semester Aktif</p>
            </div>
          </div>
        </div>
        <div class="bg-red-50 dark:bg-red-900/20 border-2 border-red-200 dark:border-red-800/50 rounded-2xl p-5 shadow-sm transition-colors">
          <div class="flex items-start gap-3">
            <svg class="w-6 h-6 text-red-600 dark:text-red-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewbox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
            <div>
              <p class="text-sm font-black text-red-900 dark:text-red-400 mb-2"><?= lang('Admin/JadwalPelajaran.lock_impact_title') ?></p>
              <ul class="text-sm font-medium text-red-800 dark:text-red-300 space-y-2">
                <li class="flex items-start gap-2"><span class="text-red-600 dark:text-red-500 font-bold">•</span> <span><?= lang('Admin/JadwalPelajaran.lock_impact_1') ?></span></li>
                <li class="flex items-start gap-2"><span class="text-red-600 dark:text-red-500 font-bold">•</span> <span><?= lang('Admin/JadwalPelajaran.lock_impact_2') ?></span></li>
              </ul>
            </div>
          </div>
        </div>
        <div class="bg-gray-50 dark:bg-slate-900/50 border border-gray-200 dark:border-slate-700 rounded-xl p-4 transition-colors">
          <label class="flex items-start gap-3 cursor-pointer group">
            <input type="checkbox" id="confirmLock" class="w-5 h-5 rounded border-gray-300 text-emerald-600 focus:ring-emerald-500 cursor-pointer mt-0.5 focus:ring-offset-0">
            <span class="text-sm font-medium text-gray-800 dark:text-slate-300 group-hover:text-gray-900 dark:group-hover:text-white transition-colors"> <?= lang('Admin/JadwalPelajaran.lock_agree') ?></span>
          </label>
        </div>
        <div class="flex gap-3 pt-5 border-t border-gray-100 dark:border-slate-700 sticky bottom-0 bg-white dark:bg-slate-800 z-50 pb-2 transition-colors">
          <button type="button" onclick="closeLockScheduleModal()" class="flex-1 px-6 py-3.5 bg-white dark:bg-slate-700 border-2 border-gray-300 dark:border-slate-600 text-gray-700 dark:text-slate-300 font-bold rounded-xl hover:bg-gray-50 dark:hover:bg-slate-600 transition-colors cursor-pointer relative shadow-sm outline-none"> <?= lang('Admin/JadwalPelajaran.btn_cancel') ?> </button>
          <button onclick="handleLockSchedule()" class="flex-1 px-6 py-3.5 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl transition-transform transform hover:-translate-y-0.5 shadow-lg shadow-blue-600/30 flex items-center justify-center gap-2 cursor-pointer relative outline-none">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewbox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
            </svg> <?= lang('Admin/JadwalPelajaran.btn_lock') ?>
          </button>
        </div>
      </div>
    </div>
  </div>
</div>

<div id="importModal" class="fixed inset-0 hidden" style="z-index: 100000 !important;">
  <div class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity pointer-events-auto" onclick="closeImportModal()"></div>
  <div class="absolute inset-0 flex items-center justify-center p-4 pointer-events-none md:pl-64">
    <div class="relative w-full bg-white dark:bg-slate-800 rounded-3xl shadow-2xl flex flex-col max-h-[90vh] pointer-events-auto border border-transparent dark:border-slate-700 transition-colors duration-300" style="max-width: 500px; z-index: 100001;">
      <div class="p-6 border-b border-gray-100 dark:border-slate-700 flex items-center justify-between bg-white dark:bg-slate-800 rounded-t-3xl z-20 flex-shrink-0 transition-colors">
        <div>
          <h3 class="text-xl font-bold text-gray-800 dark:text-white"><?= lang('Admin/JadwalPelajaran.import_title') ?? 'Import Jadwal Excel' ?></h3>
        </div>
        <button type="button" onclick="closeImportModal()" class="p-2 hover:bg-gray-100 dark:hover:bg-slate-700 rounded-full transition-colors cursor-pointer text-gray-500 dark:text-slate-400 outline-none"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
          </svg></button>
      </div>
      <div class="p-6">
        <form id="importForm" action="<?= base_url('admin/jadwal/import') ?>" method="POST" onsubmit="handleImportSubmit(event)" enctype="multipart/form-data">
          <?= csrf_field() ?>

          <input type="hidden" name="ta_id" value="<?= $ta_filter_id ?? 0 ?>">
          <input type="hidden" name="semester" value="<?= $ta_filter_semester ?? '' ?>">

          <div class="mb-5">
            <p class="text-sm font-bold text-gray-700 dark:text-slate-300 mb-2">Langkah 1: Baca Panduan Import</p>
            <a href="<?= base_url('assets/docs/Panduan_Import_Jadwal.pdf') ?>" target="_blank" class="inline-flex items-center gap-2 px-4 py-2 font-bold rounded-lg transition-colors text-sm shadow-sm hover:opacity-80 text-blue-600 bg-blue-50 dark:bg-blue-900/30 dark:text-blue-400">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
              </svg>
              Lihat Panduan Import
            </a>
            <p class="text-[11px] text-gray-500 dark:text-slate-400 mt-2 leading-tight">Sangat disarankan membaca panduan ini agar file excel sesuai standar sistem.</p>
          </div>
          <hr class="border-gray-100 dark:border-slate-700 mb-5">

          <div class="mb-5">
            <p class="text-sm font-bold text-gray-700 dark:text-slate-300 mb-2">Langkah 2: Download Template</p>
            <a href="<?= base_url('admin/jadwal/template') ?>" class="inline-flex items-center gap-2 px-4 py-2 font-bold rounded-lg transition-colors text-sm shadow-sm hover:opacity-80" style="color: <?= $color['warna_primary'] ?>; background-color: <?= $color['warna_primary'] ?>1A;">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
              </svg>
              Download Template Excel
            </a>
          </div>
          <hr class="border-gray-100 dark:border-slate-700 mb-5">

          <div class="mb-6">
            <p class="text-sm font-bold text-gray-700 dark:text-slate-300 mb-2">Langkah 3: Pilih File Excel (.xlsx)</p>
            <input type="file" name="file_excel" accept=".xlsx, .xls" required class="block w-full text-sm text-gray-500 dark:text-slate-400 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-gray-100 dark:file:bg-slate-700 file:text-gray-700 dark:file:text-slate-200 hover:file:bg-gray-200 dark:hover:file:bg-slate-600 border border-gray-200 dark:border-slate-600 cursor-pointer outline-none focus:ring-2 focus:ring-[<?= $color['warna_primary'] ?>] transition-colors">
          </div>

          <div class="flex justify-end gap-3 pt-4 border-t border-gray-100 dark:border-slate-700 transition-colors">
            <button type="button" onclick="closeImportModal()" class="px-5 py-2.5 bg-white dark:bg-slate-700 border border-gray-300 dark:border-slate-600 text-gray-700 dark:text-slate-300 font-bold rounded-xl hover:bg-gray-50 dark:hover:bg-slate-600 transition-colors shadow-sm outline-none"><?= lang('Admin/JadwalPelajaran.btn_cancel') ?? 'Batal' ?></button>
            <button type="submit" class="px-5 py-2.5 text-white font-bold rounded-xl shadow-lg transition-transform transform hover:-translate-y-0.5 flex items-center gap-2 outline-none cursor-pointer" style="background-color: <?= $color['warna_primary'] ?>;"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
              </svg> <?= lang('Admin/JadwalPelajaran.btn_upload_import') ?? 'Upload & Import' ?></button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
  const BASE_URL = "<?= base_url() ?>";
  const DB_SCHEDULES = <?= json_encode($list_jadwal) ?>;
  window.DATA_GURU = <?= json_encode($data_guru) ?>; // <-- TAMBAHKAN BARIS INI
  // EXPOSE DATA MAPEL TO JS FOR EDIT MODAL
  window.DATA_MAPEL = <?= json_encode($data_mapel) ?>;

  window.LANG = {
    js_unlock_title: "<?= lang('Admin/JadwalPelajaran.js_unlock_title') ?: 'Buka Kunci Jadwal?' ?>",
    js_unlock_desc: "<?= lang('Admin/JadwalPelajaran.js_unlock_desc') ?: 'Jadwal akan kembali bisa diedit.' ?>",
    js_btn_yes_unlock: "<?= lang('Admin/JadwalPelajaran.js_btn_yes_unlock') ?: 'Ya, Buka Kunci' ?>",
    js_btn_cancel: "<?= lang('Admin/JadwalPelajaran.js_btn_cancel') ?: 'Batal' ?>",
    js_unlock_success: "<?= lang('Admin/JadwalPelajaran.js_unlock_success') ?: 'Kunci berhasil dibuka.' ?>",
    js_locked_warn: "<?= lang('Admin/JadwalPelajaran.js_locked_warn') ?: 'Terkunci!' ?>",
    js_locked_desc: "<?= lang('Admin/JadwalPelajaran.js_locked_desc') ?: 'Jadwal sedang terkunci.' ?>",
    js_empty_schedule: "<?= lang('Admin/JadwalPelajaran.js_empty_schedule') ?: 'Jadwal masih kosong untuk kelas ini.' ?>",
    js_period: "<?= lang('Admin/JadwalPelajaran.js_period') ?: 'Jam' ?>",
    js_period_ke: "<?= lang('Admin/JadwalPelajaran.js_period_ke') ?: 'Jam ke-' ?>",
    js_empty_slot: "<?= lang('Admin/JadwalPelajaran.js_empty_slot') ?: 'Kosong' ?>",
    js_select_room_warn: "<?= lang('Admin/JadwalPelajaran.js_select_room_warn') ?: 'Mohon pilih Rombel terlebih dahulu!' ?>",
    js_saving: "<?= lang('Admin/JadwalPelajaran.js_saving') ?: 'Menyimpan...' ?>",
    js_err_no_room: "<?= lang('Admin/JadwalPelajaran.js_err_no_room') ?: 'Error: Rombel belum dipilih.' ?>",
    js_err_sys: "<?= lang('Admin/JadwalPelajaran.js_err_sys') ?: 'Terjadi kesalahan sistem.' ?>",
    js_lock_check_warn: "<?= lang('Admin/JadwalPelajaran.js_lock_check_warn') ?: 'Harap centang persetujuan terlebih dahulu' ?>",
    js_lock_success: "<?= lang('Admin/JadwalPelajaran.js_lock_success') ?: 'Berhasil!' ?>",
    js_lock_success_desc: "<?= lang('Admin/JadwalPelajaran.js_lock_success_desc') ?: 'Jadwal telah dikunci.' ?>",
    js_clear_slot_title: "<?= lang('Admin/JadwalPelajaran.js_clear_slot_title') ?: 'Kosongkan Slot?' ?>",
    js_clear_slot_desc: "<?= lang('Admin/JadwalPelajaran.js_clear_slot_desc') ?: 'Anda yakin ingin mengosongkan jadwal ini?' ?>",
    js_btn_yes_clear: "<?= lang('Admin/JadwalPelajaran.js_btn_yes_clear') ?: 'Ya, Kosongkan!' ?>",
    js_clear_success: "<?= lang('Admin/JadwalPelajaran.js_clear_success') ?: 'Dikosongkan!' ?>",
    js_clear_success_desc: "<?= lang('Admin/JadwalPelajaran.js_clear_success_desc') ?: 'Slot jadwal berhasil dikosongkan.' ?>",
    js_import_locked: "<?= lang('Admin/JadwalPelajaran.js_import_locked') ?: 'Jadwal telah dikunci. Tidak dapat import.' ?>",
    js_processing: "<?= lang('Admin/JadwalPelajaran.js_processing') ?: 'Memproses...' ?>",
  };
</script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="<?= base_url('assets/js/Admin/jadwal-pelajaran.js') ?>?v=<?= time() ?>"></script>
<?= $this->endSection() ?>