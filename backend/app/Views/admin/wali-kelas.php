<?= $this->extend('layout/main') ?>

<?= $this->section('title') ?>
<?= lang('Admin/WaliKelas.page_title_browser') ?>
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<link rel="stylesheet" href="<?= base_url('assets/css/Admin/wali-kelas.css') ?>">
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="mb-6">
  <div class="flex items-center gap-2 text-sm text-gray-500 dark:text-slate-400 mb-2 transition-colors">
    <span><?= lang('Admin/WaliKelas.academic_master') ?></span>
    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewbox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
    </svg>
    <span class="text-[<?= $color['warna_primary'] ?>] font-medium"><?= lang('Admin/WaliKelas.page_title') ?></span>
  </div>
  <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
    <div>
      <h1 id="page-title" class="text-xl md:text-3xl font-bold text-gray-800 dark:text-white transition-colors"><?= lang('Admin/WaliKelas.page_title') ?></h1>
      <p id="page-subtitle" class="text-sm md:text-base text-gray-600 dark:text-slate-400 mt-1 transition-colors"><?= lang('Admin/WaliKelas.page_subtitle') ?></p>
    </div>
  </div>
</div>

<div id="alertSection" class="mb-6 space-y-3">
  <?php if ($stats['unassigned'] > 0): ?>
    <div class="alert-box bg-amber-50 dark:bg-amber-900/30 border border-amber-400 dark:border-amber-500/50 rounded-xl p-4 flex items-start justify-between gap-3 transition-colors shadow-sm">
      <div class="flex items-start gap-3">
        <svg class="w-6 h-6 text-amber-600 dark:text-amber-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewbox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
        </svg>
        <div>
          <p class="font-bold text-amber-800 dark:text-amber-400"><?= str_replace('{count}', $stats['unassigned'], lang('Admin/WaliKelas.alert_unassigned_title')) ?></p>
          <p class="text-sm text-amber-700 dark:text-amber-300 mt-1 font-medium"><?= lang('Admin/WaliKelas.alert_unassigned_desc') ?></p>
        </div>
      </div>
      <button onclick="showUnassignedListModal()" class="shrink-0 px-4 py-2 bg-white dark:bg-slate-800 border border-amber-300 dark:border-amber-500/50 text-amber-700 dark:text-amber-400 font-bold rounded-lg hover:bg-amber-50 dark:hover:bg-slate-700 transition-colors shadow-sm text-sm outline-none">
        <?= lang('Admin/WaliKelas.btn_assign_now') ?>
      </button>
    </div>
  <?php endif; ?>
</div>

<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
  <div class="stat-card bg-white dark:bg-slate-800 rounded-2xl p-5 shadow-sm border border-gray-100 dark:border-slate-700 transition-colors">
    <div class="flex items-center gap-3 mb-2">
      <div class="w-10 h-10 rounded-xl bg-emerald-100 dark:bg-emerald-900/30 flex items-center justify-center">
        <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewbox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
        </svg>
      </div>
    </div>
    <p class="text-sm font-medium text-gray-500 dark:text-slate-400 mb-1"><?= lang('Admin/WaliKelas.total_rombel') ?></p>
    <h3 class="text-2xl font-bold text-gray-800 dark:text-white"><?= $stats['total'] ?></h3>
  </div>
  <div class="stat-card bg-white dark:bg-slate-800 rounded-2xl p-5 shadow-sm border border-gray-100 dark:border-slate-700 transition-colors">
    <div class="flex items-center gap-3 mb-2">
      <div class="w-10 h-10 rounded-xl bg-emerald-100 dark:bg-emerald-900/30 flex items-center justify-center">
        <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewbox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
      </div>
    </div>
    <p class="text-sm font-medium text-gray-500 dark:text-slate-400 mb-1"><?= lang('Admin/WaliKelas.assigned_rombel') ?></p>
    <h3 class="text-2xl font-bold text-gray-800 dark:text-white"><?= $stats['assigned'] ?></h3>
  </div>
  <div class="stat-card bg-white dark:bg-slate-800 rounded-2xl p-5 shadow-sm border border-gray-100 dark:border-slate-700 transition-colors">
    <div class="flex items-center gap-3 mb-2">
      <div class="w-10 h-10 rounded-xl bg-amber-100 dark:bg-amber-900/30 flex items-center justify-center">
        <svg class="w-5 h-5 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewbox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
      </div>
    </div>
    <p class="text-sm font-medium text-gray-500 dark:text-slate-400 mb-1"><?= lang('Admin/WaliKelas.unassigned_rombel') ?></p>
    <h3 class="text-2xl font-bold text-amber-600 dark:text-amber-400"><?= $stats['unassigned'] ?></h3>
  </div>
  <div class="stat-card bg-white dark:bg-slate-800 rounded-2xl p-5 shadow-sm border border-gray-100 dark:border-slate-700 transition-colors">
    <div class="flex items-center gap-3 mb-2">
      <div class="w-10 h-10 rounded-xl bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center">
        <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewbox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
        </svg>
      </div>
    </div>
    <p class="text-sm font-medium text-gray-500 dark:text-slate-400 mb-1"><?= lang('Admin/WaliKelas.active_teacher') ?></p>
    <h3 class="text-2xl font-bold text-blue-600 dark:text-blue-400"><?= $stats['active'] ?></h3>
  </div>
</div>

<div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700 p-5 mb-6 transition-colors">
  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
    <div>
      <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-2"><?= lang('Admin/WaliKelas.level') ?></label>
      <select id="filterLevel" class="w-full px-4 py-2.5 bg-white dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-xl text-sm text-gray-700 dark:text-slate-200 focus:outline-none focus:ring-2 focus:ring-emerald-500 transition-colors cursor-pointer appearance-none">
        <option value=""><?= lang('Admin/WaliKelas.all_levels') ?></option>
        <option value="VII">VII</option>
        <option value="VIII">VIII</option>
        <option value="IX">IX</option>
      </select>
    </div>
    <div class="lg:col-span-2">
      <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-2"><?= lang('Admin/WaliKelas.search') ?></label>
      <div class="relative">
        <input type="text" id="searchInput" placeholder="<?= lang('Admin/WaliKelas.search_placeholder') ?>" class="w-full px-4 py-2.5 text-sm bg-white dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-xl text-gray-700 dark:text-slate-200 placeholder-gray-400 dark:placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-emerald-500 transition-colors shadow-sm">
      </div>
    </div>
  </div>
</div>

<div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700 overflow-hidden transition-colors">
  <div class="overflow-x-auto custom-scrollbar">
    <table class="w-full min-w-max">
      <thead class="bg-gray-50 dark:bg-slate-900/50 border-b border-gray-100 dark:border-slate-700 transition-colors">
        <tr>
          <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 dark:text-slate-400 uppercase tracking-wider"><?= lang('Admin/WaliKelas.th_level') ?></th>
          <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 dark:text-slate-400 uppercase tracking-wider"><?= lang('Admin/WaliKelas.th_rombel') ?></th>
          <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 dark:text-slate-400 uppercase tracking-wider"><?= lang('Admin/WaliKelas.th_homeroom_name') ?></th>
          <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 dark:text-slate-400 uppercase tracking-wider"><?= lang('Admin/WaliKelas.th_nik') ?></th>
          <th class="px-6 py-4 text-center text-xs font-bold text-gray-500 dark:text-slate-400 uppercase tracking-wider"><?= lang('Admin/WaliKelas.th_status') ?></th>
          <th class="px-6 py-4 text-center text-xs font-bold text-gray-500 dark:text-slate-400 uppercase tracking-wider"><?= lang('Admin/WaliKelas.th_action') ?></th>
        </tr>
      </thead>
      <tbody id="waliKelasTableBody" class="divide-y divide-gray-100 dark:divide-slate-700/50 bg-white dark:bg-slate-800 transition-colors">
      </tbody>
    </table>
  </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('modals') ?>

<div id="drawer-overlay" class="drawer-overlay fixed inset-0 hidden bg-gray-900/40 backdrop-blur-sm transition-opacity" style="z-index: 99998;" onclick="closeDrawer()"></div>

<div id="detailDrawer" class="drawer fixed inset-y-0 right-0 hidden bg-white dark:bg-slate-800 shadow-2xl w-80 md:w-96 transition-transform duration-300 transform translate-x-full border-l border-gray-200 dark:border-slate-700" style="z-index: 99999;">
  <div class="p-6 border-b border-gray-100 dark:border-slate-700 flex items-center justify-between sticky top-0 bg-white dark:bg-slate-800 z-10 transition-colors">
    <h3 class="text-xl font-bold text-gray-800 dark:text-white"><?= lang('Admin/WaliKelas.drawer_title') ?></h3>
    <button onclick="closeDrawer()" class="p-2 hover:bg-gray-100 dark:hover:bg-slate-700 rounded-lg transition-colors outline-none text-gray-500 dark:text-slate-400">
      <svg class="w-6 h-6" fill="none" stroke="currentColor" viewbox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
      </svg>
    </button>
  </div>
  <div class="p-6 space-y-6 overflow-y-auto custom-scrollbar flex-1">
    <div class="text-center pb-6 border-b border-gray-100 dark:border-slate-700 transition-colors">
      <div class="w-24 h-24 mx-auto rounded-2xl bg-gradient-to-br from-emerald-500 to-emerald-700 flex items-center justify-center text-white font-bold text-3xl mb-4 shadow-lg border border-transparent">
        <span id="drawerInitial">...</span>
      </div>
      <h4 id="drawerTeacherName" class="text-xl font-bold text-gray-800 dark:text-white mb-1">...</h4>
      <p id="drawerTeacherNIP" class="text-sm font-medium text-gray-500 dark:text-slate-400 font-mono">...</p>
    </div>
    <div class="pt-4 space-y-3">
      <button id="drawerChangeBtn" class="w-full px-4 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl flex items-center justify-center gap-2 shadow-sm transition-transform transform hover:-translate-y-0.5 outline-none">
        <?= lang('Admin/WaliKelas.btn_change_teacher') ?>
      </button>
      <button id="drawerRemoveBtn" onclick="confirmRemoveAssignment()" class="w-full px-4 py-3 bg-white dark:bg-slate-700 border border-red-300 dark:border-red-800/50 text-red-600 dark:text-red-400 font-bold rounded-xl hover:bg-red-50 dark:hover:bg-slate-600 flex items-center justify-center gap-2 transition-colors shadow-sm outline-none">
        <?= lang('Admin/WaliKelas.btn_remove_assignment') ?>
      </button>
    </div>
  </div>
</div>

<div id="assignModal" class="fixed inset-0 hidden" style="z-index: 99999;">
  <div class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" onclick="closeAssignModal()"></div>
  <div class="absolute inset-0 flex items-center justify-center p-4 pointer-events-none md:left-64">
    <div class="relative w-full bg-white dark:bg-slate-800 rounded-2xl shadow-2xl flex flex-col pointer-events-auto border border-transparent dark:border-slate-700 transition-colors" style="max-width: 600px;">
      <div class="p-6 border-b border-gray-100 dark:border-slate-700 flex justify-between items-center bg-white dark:bg-slate-800 rounded-t-2xl transition-colors">
        <h3 class="text-xl font-bold text-gray-800 dark:text-white"><?= lang('Admin/WaliKelas.modal_assign_title') ?></h3>
        <button onclick="closeAssignModal()" class="p-2 hover:bg-gray-100 dark:hover:bg-slate-700 rounded-lg transition-colors outline-none text-gray-500 dark:text-slate-400">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewbox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
      </div>
      <div class="p-6">
        <form id="assignForm" onsubmit="handleAssignSubmit(event)">
          <input type="hidden" name="assign_rombel_id" id="assign_rombel_id">

          <div class="bg-gray-50 dark:bg-slate-900/50 p-4 rounded-xl border border-gray-200 dark:border-slate-600 mb-5 transition-colors">
            <p class="text-sm font-medium text-gray-600 dark:text-slate-400 mb-1"><?= lang('Admin/WaliKelas.target_rombel') ?></p>
            <p class="font-bold text-gray-800 dark:text-white text-lg" id="assignTargetName">-</p>
          </div>

          <div class="mb-2">
            <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-2"><?= lang('Admin/WaliKelas.select_teacher') ?> <span class="text-red-500">*</span></label>
            <select name="assign_guru" required class="w-full px-4 py-3 bg-white dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-xl text-sm text-gray-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500 transition-all shadow-sm appearance-none cursor-pointer">
              <option value=""><?= lang('Admin/WaliKelas.select_teacher_placeholder') ?></option>
              <?php foreach ($guruList as $guru): ?>
                <option value="<?= $guru['id'] ?>"><?= $guru['nama_lengkap'] ?></option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="flex gap-3 pt-6">
            <button type="button" onclick="closeAssignModal()" class="flex-1 py-3 bg-white dark:bg-slate-700 border border-gray-300 dark:border-slate-600 text-gray-700 dark:text-slate-300 font-bold rounded-xl hover:bg-gray-50 dark:hover:bg-slate-600 transition-colors shadow-sm outline-none"><?= lang('Admin/WaliKelas.btn_cancel') ?></button>
            <button type="submit" class="flex-1 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl transition-all shadow-lg shadow-emerald-600/30 outline-none"><?= lang('Admin/WaliKelas.btn_save') ?></button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<div id="changeTeacherModal" class="fixed inset-0 hidden" style="z-index: 99999;">
  <div class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" onclick="closeChangeTeacherModal()"></div>
  <div class="absolute inset-0 flex items-center justify-center p-4 pointer-events-none md:left-64">
    <div class="relative w-full bg-white dark:bg-slate-800 rounded-2xl shadow-2xl flex flex-col pointer-events-auto border border-transparent dark:border-slate-700 transition-colors" style="max-width: 600px;">
      <div class="p-6 border-b border-gray-100 dark:border-slate-700 flex justify-between items-center bg-white dark:bg-slate-800 rounded-t-2xl transition-colors">
        <h3 class="text-xl font-bold text-gray-800 dark:text-white"><?= lang('Admin/WaliKelas.modal_change_title') ?></h3>
        <button onclick="closeChangeTeacherModal()" class="p-2 hover:bg-gray-100 dark:hover:bg-slate-700 rounded-lg transition-colors outline-none text-gray-500 dark:text-slate-400">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewbox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
      </div>
      <div class="p-6">
        <form id="changeTeacherForm" onsubmit="handleChangeTeacherSubmit(event)">
          <input type="hidden" name="change_rombel_id" id="change_rombel_id">

          <div class="bg-gray-50 dark:bg-slate-900/50 p-4 rounded-xl border border-gray-200 dark:border-slate-600 mb-5 transition-colors">
            <p class="text-sm font-medium text-gray-600 dark:text-slate-400 mb-1"><?= lang('Admin/WaliKelas.current_teacher') ?></p>
            <p class="font-bold text-gray-800 dark:text-white text-lg" id="currentTeacherName">-</p>
          </div>

          <div class="mb-2">
            <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-2"><?= lang('Admin/WaliKelas.replacement_teacher') ?> <span class="text-red-500">*</span></label>
            <select name="change_guru" required class="w-full px-4 py-3 bg-white dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-xl text-sm text-gray-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500 transition-all shadow-sm appearance-none cursor-pointer">
              <option value=""><?= lang('Admin/WaliKelas.replacement_placeholder') ?></option>
              <?php foreach ($guruList as $guru): ?>
                <option value="<?= $guru['id'] ?>"><?= $guru['nama_lengkap'] ?></option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="flex gap-3 pt-6">
            <button type="button" onclick="closeChangeTeacherModal()" class="flex-1 py-3 bg-white dark:bg-slate-700 border border-gray-300 dark:border-slate-600 text-gray-700 dark:text-slate-300 font-bold rounded-xl hover:bg-gray-50 dark:hover:bg-slate-600 transition-colors shadow-sm outline-none"><?= lang('Admin/WaliKelas.btn_cancel') ?></button>
            <button type="submit" class="flex-1 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl transition-all shadow-lg shadow-emerald-600/30 outline-none"><?= lang('Admin/WaliKelas.btn_save_changes') ?></button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<div id="unassignedListModal" class="fixed inset-0 z-[99999] hidden flex items-center justify-center p-4 overflow-y-auto overflow-x-hidden">
  <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" onclick="closeUnassignedListModal()"></div>

  <div class="relative w-full max-w-md bg-white dark:bg-slate-800 rounded-2xl shadow-2xl flex flex-col transform transition-colors scale-100 border border-transparent dark:border-slate-700 md:left-64">
    <div class="p-5 border-b border-gray-100 dark:border-slate-700 flex justify-between items-center bg-amber-50 dark:bg-amber-900/20 rounded-t-2xl transition-colors">
      <div class="flex items-center gap-3">
        <div class="w-10 h-10 rounded-full bg-amber-100 dark:bg-amber-900/40 flex items-center justify-center text-amber-600 dark:text-amber-400 border border-amber-200 dark:border-amber-800/50">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewbox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
          </svg>
        </div>
        <div>
          <h3 class="text-lg font-bold text-gray-800 dark:text-white"><?= lang('Admin/WaliKelas.modal_unassigned_title') ?></h3>
          <p class="text-xs font-medium text-gray-500 dark:text-slate-400"><?= lang('Admin/WaliKelas.modal_unassigned_desc') ?></p>
        </div>
      </div>
      <button onclick="closeUnassignedListModal()" class="p-2 text-gray-400 hover:text-gray-600 dark:hover:text-slate-300 hover:bg-amber-100 dark:hover:bg-slate-700 rounded-lg transition-colors outline-none">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewbox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg>
      </button>
    </div>

    <div class="p-2 max-h-[60vh] overflow-y-auto custom-scrollbar" id="unassignedListContainer">
    </div>

    <div class="p-4 border-t border-gray-100 dark:border-slate-700 bg-gray-50 dark:bg-slate-800/80 rounded-b-2xl text-center transition-colors">
      <button onclick="closeUnassignedListModal()" class="text-sm text-gray-500 dark:text-slate-400 hover:text-gray-700 dark:hover:text-white font-bold outline-none"><?= lang('Admin/WaliKelas.btn_close') ?></button>
    </div>
  </div>
</div>

<div id="removeModal" class="fixed inset-0 z-[100000] hidden flex items-center justify-center p-4 overflow-y-auto overflow-x-hidden">
  <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" onclick="closeRemoveModal()"></div>

  <div class="relative w-full max-w-md bg-white dark:bg-slate-800 rounded-2xl shadow-2xl transform transition-colors scale-100 border border-transparent dark:border-slate-700 md:left-64">
    <div class="p-6 text-center">
      <div class="w-20 h-20 mx-auto bg-red-50 dark:bg-red-900/30 rounded-full flex items-center justify-center mb-5 border-4 border-red-100 dark:border-red-800/50">
        <svg class="w-10 h-10 text-red-500 dark:text-red-400" fill="none" stroke="currentColor" viewbox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
        </svg>
      </div>

      <h3 class="text-xl font-bold text-gray-800 dark:text-white mb-2"><?= lang('Admin/WaliKelas.modal_remove_title') ?></h3>
      <p class="text-sm font-medium text-gray-500 dark:text-slate-400 mb-6">
        <?= lang('Admin/WaliKelas.modal_remove_desc') ?>
      </p>

      <div class="flex gap-3 justify-center">
        <button onclick="closeRemoveModal()" class="px-6 py-2.5 bg-white dark:bg-slate-700 border border-gray-300 dark:border-slate-600 text-gray-700 dark:text-slate-300 font-bold rounded-xl hover:bg-gray-50 dark:hover:bg-slate-600 transition-colors w-full outline-none shadow-sm">
          <?= lang('Admin/WaliKelas.btn_cancel') ?>
        </button>
        <button id="btnConfirmRemove" onclick="handleRemoveSubmit()" class="px-6 py-2.5 bg-red-600 hover:bg-red-700 text-white font-bold rounded-xl shadow-lg shadow-red-600/30 transition-transform transform hover:-translate-y-0.5 w-full outline-none">
          <?= lang('Admin/WaliKelas.btn_yes_remove') ?>
        </button>
      </div>
    </div>
  </div>
</div>

<div id="statusModal" class="fixed inset-0 z-[100001] hidden flex items-center justify-center p-4 overflow-y-auto overflow-x-hidden">
  <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" onclick="closeStatusModal()"></div>

  <div class="relative w-full max-w-sm bg-white dark:bg-slate-800 rounded-2xl shadow-2xl transform transition-colors scale-100 overflow-hidden border border-transparent dark:border-slate-700 md:left-64">
    <div class="p-6 text-center">

      <div id="statusIconSuccess" class="w-20 h-20 mx-auto bg-emerald-100 dark:bg-emerald-900/30 rounded-full flex items-center justify-center mb-5 hidden border-4 border-emerald-200 dark:border-emerald-800/50">
        <svg class="w-10 h-10 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewbox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
        </svg>
      </div>

      <div id="statusIconError" class="w-20 h-20 mx-auto bg-red-100 dark:bg-red-900/30 rounded-full flex items-center justify-center mb-5 hidden border-4 border-red-200 dark:border-red-800/50">
        <svg class="w-10 h-10 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewbox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg>
      </div>

      <h3 id="statusTitle" class="text-xl font-bold text-gray-800 dark:text-white mb-2"><?= lang('Admin/WaliKelas.modal_status_success') ?></h3>
      <p id="statusMessage" class="text-sm font-medium text-gray-500 dark:text-slate-400 mb-6"><?= lang('Admin/WaliKelas.modal_status_success_desc') ?></p>

      <button onclick="closeStatusModal()" class="w-full px-6 py-3 bg-gray-100 dark:bg-slate-700 hover:bg-gray-200 dark:hover:bg-slate-600 text-gray-800 dark:text-white font-bold rounded-xl transition-colors outline-none shadow-sm">
        <?= lang('Admin/WaliKelas.btn_close') ?>
      </button>
    </div>
    <div class="h-1.5 w-full bg-gray-100 dark:bg-slate-700">
      <div id="statusProgressBar" class="h-full bg-emerald-500 w-full transition-all duration-[3000ms] ease-linear"></div>
    </div>
  </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
  const dbWaliKelas = <?= !empty($waliKelasData) ? json_encode($waliKelasData) : '[]' ?>;
  const BASE_URL = "<?= base_url() ?>";

  const LANG = {
    js_no_data: "<?= lang('Admin/WaliKelas.js_no_data') ?>",
    js_unassigned: "<?= lang('Admin/WaliKelas.js_unassigned') ?>",
    js_assigned: "<?= lang('Admin/WaliKelas.js_assigned') ?>",
    js_detail: "<?= lang('Admin/WaliKelas.js_detail') ?>",
    js_change: "<?= lang('Admin/WaliKelas.js_change') ?>",
    js_assign: "<?= lang('Admin/WaliKelas.js_assign') ?>",
    js_loading: "<?= lang('Admin/WaliKelas.js_loading') ?>",
    js_processing: "<?= lang('Admin/WaliKelas.js_processing') ?>",
    js_saving: "<?= lang('Admin/WaliKelas.js_saving') ?>",
    js_success: "<?= lang('Admin/WaliKelas.js_success') ?>",
    js_failed: "<?= lang('Admin/WaliKelas.js_failed') ?>",
    js_error: "<?= lang('Admin/WaliKelas.js_error') ?>",
    js_fail_connect: "<?= lang('Admin/WaliKelas.js_fail_connect') ?>",
    js_all_safe: "<?= lang('Admin/WaliKelas.js_all_safe') ?>",
    js_all_safe_desc: "<?= lang('Admin/WaliKelas.js_all_safe_desc') ?>"
  };
</script>
<script src="<?= base_url('assets/js/Admin/wali-kelas.js') ?>?v=<?= time() ?>"></script>
<?= $this->endSection() ?>