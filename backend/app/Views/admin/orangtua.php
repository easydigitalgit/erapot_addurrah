<?= $this->extend('layout/main') ?>

<?= $this->section('title') ?>
  <?= lang('Admin/OrangTua.page_title_browser') ?>
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
    :root { --warna-scroll: <?= $color['warna_primary'] ?>; }
</style>
  <link rel="stylesheet" href="<?= base_url('assets/css/Admin/orangtua.css') ?>">
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="mb-6">
  <div class="flex items-center gap-2 text-sm text-gray-500 dark:text-slate-400 mb-2 transition-colors">
    <span><?= lang('Admin/OrangTua.user_management') ?></span>
    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewbox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
    </svg><span class="text-[<?= $color['warna_primary'] ?>] font-medium"><?= lang('Admin/OrangTua.parent_guardian') ?></span>
  </div>
  <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
    <div>
      <h1 id="page-title" class="text-xl md:text-3xl font-bold text-gray-800 dark:text-white transition-colors"><?= lang('Admin/OrangTua.page_title') ?></h1>
      <p id="page-subtitle" class="text-sm md:text-base text-gray-600 dark:text-slate-400 mt-1 transition-colors"><?= lang('Admin/OrangTua.page_subtitle') ?></p>
    </div>
    <div class="flex flex-wrap items-center gap-2 md:gap-3">
      <button onclick="showAddModal()" class="px-4 py-2.5 bg-[<?= $color['warna_primary'] ?>] hover:bg-[<?= $color['warna_primary'] ?>]/90 text-white font-semibold rounded-xl transition-all shadow-lg shadow-[<?= $color['warna_primary'] ?>]/20 flex items-center gap-2 transform hover:-translate-y-0.5 outline-none">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewbox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
        </svg>
        <span class="hidden sm:inline"><?= lang('Admin/OrangTua.add_parent') ?></span>
        <span class="sm:hidden"><?= lang('Admin/OrangTua.add_short') ?></span>
      </button>

      <button onclick="showImportModal()" class="px-4 py-2.5 bg-white dark:bg-slate-800 border border-gray-300 dark:border-slate-600 text-gray-700 dark:text-slate-300 font-medium rounded-xl hover:bg-gray-50 dark:hover:bg-slate-700 transition-colors flex items-center gap-2 cursor-pointer shadow-sm outline-none">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewbox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
        </svg>
        <span class="hidden sm:inline"><?= lang('Admin/OrangTua.import_data') ?></span>
      </button>

      <button onclick="window.location.href='<?= base_url('admin/orangtua/export') ?>'" class="p-2.5 bg-white dark:bg-slate-800 border border-gray-300 dark:border-slate-600 text-gray-700 dark:text-slate-300 rounded-xl hover:bg-gray-50 dark:hover:bg-slate-700 transition-colors cursor-pointer shadow-sm outline-none" title="<?= lang('Admin/OrangTua.export_data') ?>">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewbox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
        </svg>
      </button>
    </div>
  </div>
</div>

<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
  <div class="stat-card bg-white dark:bg-slate-800 rounded-2xl p-5 shadow-sm border border-gray-100 dark:border-slate-700 transition-colors">
    <div class="flex items-center gap-3 mb-2">
      <div class="w-10 h-10 rounded-xl bg-emerald-100 dark:bg-emerald-900/30 flex items-center justify-center">
        <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewbox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
        </svg>
      </div>
    </div>
    <p class="text-sm font-medium text-gray-500 dark:text-slate-400 mb-1"><?= lang('Admin/OrangTua.total_parents') ?></p>
    <h3 class="text-2xl font-bold text-gray-800 dark:text-white"><?= number_format($stats['total'] ?? 0) ?></h3>
  </div>

  <div class="stat-card bg-white dark:bg-slate-800 rounded-2xl p-5 shadow-sm border border-gray-100 dark:border-slate-700 transition-colors">
    <div class="flex items-center gap-3 mb-2">
      <div class="w-10 h-10 rounded-xl bg-green-100 dark:bg-green-900/30 flex items-center justify-center">
        <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewbox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
      </div>
    </div>
    <p class="text-sm font-medium text-gray-500 dark:text-slate-400 mb-1"><?= lang('Admin/OrangTua.active_accounts') ?></p>
    <h3 class="text-2xl font-bold text-gray-800 dark:text-white"><?= number_format($stats['active'] ?? 0) ?></h3>
  </div>

  <div class="stat-card bg-white dark:bg-slate-800 rounded-2xl p-5 shadow-sm border border-gray-100 dark:border-slate-700 transition-colors">
    <div class="flex items-center gap-3 mb-2">
      <div class="w-10 h-10 rounded-xl bg-amber-100 dark:bg-amber-900/30 flex items-center justify-center">
        <svg class="w-5 h-5 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewbox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
      </div>
    </div>
    <p class="text-sm font-medium text-gray-500 dark:text-slate-400 mb-1"><?= lang('Admin/OrangTua.inactive_accounts') ?></p>
    <h3 class="text-2xl font-bold text-gray-800 dark:text-white"><?= number_format($stats['inactive'] ?? 0) ?></h3>
  </div>

  <div class="stat-card bg-white dark:bg-slate-800 rounded-2xl p-5 shadow-sm border border-gray-100 dark:border-slate-700 transition-colors">
    <div class="flex items-center gap-3 mb-2">
      <div class="w-10 h-10 rounded-xl bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center">
        <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewbox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
        </svg>
      </div>
    </div>
    <p class="text-sm font-medium text-gray-500 dark:text-slate-400 mb-1"><?= lang('Admin/OrangTua.connected_students') ?></p>
    <h3 class="text-2xl font-bold text-gray-800 dark:text-white"><?= number_format($stats['connected'] ?? 0) ?></h3>
  </div>
</div>

<div class="bg-white dark:bg-slate-800 rounded-2xl p-4 md:p-6 shadow-sm border border-gray-100 dark:border-slate-700 mb-6 filter-sticky transition-colors z-10 relative">
  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
    <div class="lg:col-span-2">
      <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-2"><?= lang('Admin/OrangTua.search') ?></label>
      <div class="relative">
        <input type="text" id="searchInput" placeholder="<?= lang('Admin/OrangTua.search_placeholder') ?>" class="w-full pl-10 pr-4 py-2.5 bg-white dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-xl text-sm font-medium text-gray-800 dark:text-white placeholder-gray-400 dark:placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[<?= $color['warna_primary'] ?>] transition-all shadow-sm">
        <svg class="w-5 h-5 text-gray-400 dark:text-slate-400 absolute left-3 top-3 pointer-events-none" fill="none" stroke="currentColor" viewbox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
        </svg>
      </div>
    </div>
    <div>
      <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-2"><?= lang('Admin/OrangTua.relation') ?></label>
      <select id="filterRelation" class="w-full px-4 py-2.5 bg-white dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-xl text-sm font-medium text-gray-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-[<?= $color['warna_primary'] ?>] transition-all shadow-sm appearance-none cursor-pointer">
        <option value=""><?= lang('Admin/OrangTua.all_relations') ?></option>
        <option value="Ayah"><?= lang('Admin/OrangTua.father') ?></option>
        <option value="Ibu"><?= lang('Admin/OrangTua.mother') ?></option>
        <option value="Wali"><?= lang('Admin/OrangTua.guardian') ?></option>
      </select>
    </div>
    <div>
      <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-2"><?= lang('Admin/OrangTua.class_level') ?></label>
      <select id="filterClass" class="w-full px-4 py-2.5 bg-white dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-xl text-sm font-medium text-gray-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-[<?= $color['warna_primary'] ?>] transition-all shadow-sm appearance-none cursor-pointer">
        <option value=""><?= lang('Admin/OrangTua.all_levels') ?></option>
        <?php if (!empty($tingkat_sekolah)): ?>
          <?php foreach ($tingkat_sekolah as $t): ?>
            <option value="<?= $t['tingkat'] ?>"><?= lang('Admin/OrangTua.class') ?> <?= $t['tingkat'] ?></option>
          <?php endforeach; ?>
        <?php else: ?>
          <option value="VII"><?= lang('Admin/OrangTua.class') ?> VII</option>
          <option value="VIII"><?= lang('Admin/OrangTua.class') ?> VIII</option>
          <option value="IX"><?= lang('Admin/OrangTua.class') ?> IX</option>
        <?php endif; ?>
      </select>
    </div>
    <div>
      <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-2"><?= lang('Admin/OrangTua.account_status') ?></label>
      <select id="filterStatus" class="w-full px-4 py-2.5 bg-white dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-xl text-sm font-medium text-gray-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-[<?= $color['warna_primary'] ?>] transition-all shadow-sm appearance-none cursor-pointer">
        <option value=""><?= lang('Admin/OrangTua.all_statuses') ?></option>
        <option value="Aktif"><?= lang('Admin/OrangTua.active') ?></option>
        <option value="Belum Aktivasi"><?= lang('Admin/OrangTua.unactivated') ?></option>
        <option value="Nonaktif"><?= lang('Admin/OrangTua.inactive') ?></option>
      </select>
    </div>
  </div>
</div>

<div class="grid grid-cols-1 min-w-0 w-full mb-10">
  <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700 overflow-hidden w-full relative transition-colors">
    <div class="px-4 md:px-6 py-4 border-b border-gray-100 dark:border-slate-700 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 bg-gray-50 dark:bg-slate-900/50 transition-colors">
      <div class="flex items-center gap-3">
        <input type="checkbox" id="selectAll" class="w-4 h-4 text-[<?= $color['warna_primary'] ?>] rounded border-gray-300 dark:border-slate-500 bg-white dark:bg-slate-700 focus:ring-[<?= $color['warna_primary'] ?>] cursor-pointer focus:ring-offset-0" onchange="toggleSelectAll(this)">
        <span class="text-sm font-medium text-gray-700 dark:text-slate-300 cursor-pointer select-none"><?= lang('Admin/OrangTua.select_all') ?></span>
        <span id="selectedCount" class="text-sm text-gray-500 dark:text-slate-400 hidden"></span>
      </div>
      <div id="bulkActions" class="flex items-center gap-2 hidden animate-fade-in">
        <button class="px-4 py-2 bg-emerald-50 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400 text-sm font-medium rounded-lg hover:bg-emerald-100 dark:hover:bg-emerald-900/50 border border-transparent dark:border-emerald-800/50 transition-colors"> <?= lang('Admin/OrangTua.export_selected') ?> </button>
        <button class="px-4 py-2 bg-[#25D366] hover:bg-[#128C7E] text-white text-sm font-medium rounded-lg transition-colors flex items-center gap-1.5 shadow-sm">
          <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z" />
        </button>
      </div>
    </div>
    
    <div class="block w-full overflow-x-auto overflow-y-hidden custom-scrollbar" style="max-width: 100%;">
      <table class="w-full whitespace-nowrap text-left border-collapse">
        <thead class="bg-gray-50 dark:bg-slate-900/50 border-b border-gray-100 dark:border-slate-700 transition-colors">
          <tr>
            <th class="px-4 md:px-6 py-4 text-left w-12"></th>
            <th class="px-4 md:px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-slate-400 uppercase tracking-wider"><?= lang('Admin/OrangTua.table_parent_name_relation') ?></th>
            <th class="px-4 md:px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-slate-400 uppercase tracking-wider"><?= lang('Admin/OrangTua.table_child_name') ?></th>
            <th class="px-4 md:px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-slate-400 uppercase tracking-wider"><?= lang('Admin/OrangTua.table_class') ?></th>
            <th class="px-4 md:px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-slate-400 uppercase tracking-wider"><?= lang('Admin/OrangTua.table_contact') ?></th>
            <th class="px-4 md:px-6 py-4 text-center text-xs font-semibold text-gray-500 dark:text-slate-400 uppercase tracking-wider"><?= lang('Admin/OrangTua.table_account_status') ?></th>
            <th class="px-4 md:px-6 py-4 text-center text-xs font-semibold text-gray-500 dark:text-slate-400 uppercase tracking-wider"><?= lang('Admin/OrangTua.table_action') ?></th>
          </tr>
        </thead>
        <tbody id="parentTableBody" class="divide-y divide-gray-100 dark:divide-slate-700/50 bg-white dark:bg-slate-800 transition-colors">
            <tr>
              <td colspan="7" class="text-center py-6 text-gray-500 dark:text-slate-400"><?= lang('Admin/OrangTua.loading_data') ?></td>
            </tr>
        </tbody>
      </table>
    </div>
    
    <div class="px-4 md:px-6 py-4 border-t border-gray-100 dark:border-slate-700 flex flex-col sm:flex-row items-center justify-between gap-4 bg-white dark:bg-slate-800/50 transition-colors">
      <div class="text-sm text-gray-500 dark:text-slate-400"><?= lang('Admin/OrangTua.showing') ?> <span class="font-semibold text-gray-800 dark:text-white" id="displayRange">0-0</span> <?= lang('Admin/OrangTua.from') ?> <span class="font-semibold text-gray-800 dark:text-white" id="totalData">0</span> <?= lang('Admin/OrangTua.data') ?></div>
      <div class="flex items-center gap-2 pagination-wrapper" id="pagination-buttons"></div>
    </div>
  </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('modals') ?>

<div id="drawer-overlay" class="fixed inset-0 bg-gray-900/40 backdrop-blur-sm hidden transition-opacity" style="z-index: 999997 !important;" onclick="closeDrawer()"></div>

<div id="detailDrawer" class="drawer fixed inset-y-0 right-0 w-full md:w-[450px] bg-white dark:bg-slate-800 shadow-2xl transform translate-x-full transition-transform duration-300 ease-in-out flex flex-col border-l border-gray-200 dark:border-slate-700" style="z-index: 999998 !important;">
  <div class="p-6 border-b border-gray-100 dark:border-slate-700 flex items-center justify-between bg-white dark:bg-slate-800 z-10 transition-colors">
    <h3 class="text-xl font-bold text-gray-800 dark:text-white"><?= lang('Admin/OrangTua.detail_parent') ?></h3>
    <button onclick="closeDrawer()" class="p-2 hover:bg-gray-100 dark:hover:bg-slate-700 rounded-lg transition-colors cursor-pointer text-gray-500 dark:text-slate-400 outline-none">
      <svg class="w-6 h-6" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
    </button>
  </div>

  <div class="p-6 space-y-6 overflow-y-auto custom-scrollbar flex-1">
    <div class="text-center pb-6 border-b border-gray-100 dark:border-slate-700 transition-colors">
      <div id="drawerAvatar" class="w-24 h-24 rounded-full bg-gray-100 dark:bg-slate-700 mx-auto mb-4 shadow-lg border border-gray-200 dark:border-slate-600 overflow-hidden flex items-center justify-center"></div>
      <h4 id="drawerName" class="text-xl font-bold text-gray-800 dark:text-white mb-1">-</h4>
      <div class="flex items-center justify-center gap-2 flex-wrap mb-3">
        <span id="drawerRelation" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider">-</span>
        <span id="drawerStatus" class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium">-</span>
      </div>
    </div>

    <div>
      <h5 class="font-bold text-gray-800 dark:text-white mb-3 flex items-center gap-2 text-sm text-blue-600 dark:text-blue-400 transition-colors">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg> <?= lang('Admin/OrangTua.login_contact_info') ?>
      </h5>
      <div class="space-y-3 text-sm p-3 bg-blue-50/50 dark:bg-blue-900/10 rounded-xl border border-blue-100 dark:border-blue-800/30">
        <div class="flex justify-between items-center"><span class="text-gray-500 dark:text-slate-400"><?= lang('Admin/OrangTua.phone_wa') ?></span><span id="drawerPhone" class="font-medium text-gray-800 dark:text-slate-200 font-mono">-</span></div>
        <div class="flex justify-between"><span class="text-gray-500 dark:text-slate-400"><?= lang('Admin/OrangTua.email') ?></span> <span id="drawerEmail" class="font-medium text-gray-800 dark:text-slate-200 truncate max-w-[200px]">-</span></div>
        <div class="flex justify-between"><span class="text-gray-500 dark:text-slate-400"><?= lang('Admin/OrangTua.address') ?></span> <span id="drawerAddress" class="font-medium text-gray-800 dark:text-slate-200 text-right max-w-[200px] leading-relaxed">-</span></div>
      </div>
    </div>

    <div class="pt-4 border-t border-gray-100 dark:border-slate-700">
      <h5 class="font-bold text-gray-800 dark:text-white mb-3 flex items-center gap-2 text-sm text-emerald-600 dark:text-emerald-400"><?= lang('Admin/OrangTua.father_data') ?></h5>
      <div class="space-y-2 text-sm">
        <div class="flex justify-between"><span class="text-gray-500"><?= lang('Admin/OrangTua.name') ?></span> <span id="dtlAyahNama" class="font-medium text-gray-800 dark:text-slate-200">-</span></div>
        <div class="flex justify-between"><span class="text-gray-500"><?= lang('Admin/OrangTua.nik') ?></span> <span id="dtlAyahNik" class="font-medium text-gray-800 dark:text-slate-200 font-mono">-</span></div>
        <div class="flex justify-between"><span class="text-gray-500"><?= lang('Admin/OrangTua.birth_year') ?></span> <span id="dtlAyahLahir" class="font-medium text-gray-800 dark:text-slate-200">-</span></div>
        <div class="flex justify-between"><span class="text-gray-500"><?= lang('Admin/OrangTua.education') ?></span> <span id="dtlAyahPend" class="font-medium text-gray-800 dark:text-slate-200">-</span></div>
        <div class="flex justify-between"><span class="text-gray-500"><?= lang('Admin/OrangTua.occupation') ?></span> <span id="dtlAyahKerja" class="font-medium text-gray-800 dark:text-slate-200">-</span></div>
        <div class="flex justify-between"><span class="text-gray-500"><?= lang('Admin/OrangTua.income') ?></span> <span id="dtlAyahGaji" class="font-medium text-gray-800 dark:text-slate-200">-</span></div>
      </div>
    </div>

    <div class="pt-4 border-t border-gray-100 dark:border-slate-700">
      <h5 class="font-bold text-gray-800 dark:text-white mb-3 flex items-center gap-2 text-sm text-pink-600 dark:text-pink-400"><?= lang('Admin/OrangTua.mother_data') ?></h5>
      <div class="space-y-2 text-sm">
        <div class="flex justify-between"><span class="text-gray-500"><?= lang('Admin/OrangTua.name') ?></span> <span id="dtlIbuNama" class="font-medium text-gray-800 dark:text-slate-200">-</span></div>
        <div class="flex justify-between"><span class="text-gray-500"><?= lang('Admin/OrangTua.nik') ?></span> <span id="dtlIbuNik" class="font-medium text-gray-800 dark:text-slate-200 font-mono">-</span></div>
        <div class="flex justify-between"><span class="text-gray-500"><?= lang('Admin/OrangTua.birth_year') ?></span> <span id="dtlIbuLahir" class="font-medium text-gray-800 dark:text-slate-200">-</span></div>
        <div class="flex justify-between"><span class="text-gray-500"><?= lang('Admin/OrangTua.education') ?></span> <span id="dtlIbuPend" class="font-medium text-gray-800 dark:text-slate-200">-</span></div>
        <div class="flex justify-between"><span class="text-gray-500"><?= lang('Admin/OrangTua.occupation') ?></span> <span id="dtlIbuKerja" class="font-medium text-gray-800 dark:text-slate-200">-</span></div>
        <div class="flex justify-between"><span class="text-gray-500"><?= lang('Admin/OrangTua.income') ?></span> <span id="dtlIbuGaji" class="font-medium text-gray-800 dark:text-slate-200">-</span></div>
      </div>
    </div>

    <div class="pt-4 border-t border-gray-100 dark:border-slate-700 mb-10">
      <h5 class="font-bold text-gray-800 dark:text-white mb-3 flex items-center gap-2 text-sm text-purple-600 dark:text-purple-400"><?= lang('Admin/OrangTua.guardian_data') ?></h5>
      <div class="space-y-2 text-sm">
        <div class="flex justify-between"><span class="text-gray-500"><?= lang('Admin/OrangTua.name') ?></span> <span id="dtlWaliNama" class="font-medium text-gray-800 dark:text-slate-200">-</span></div>
        <div class="flex justify-between"><span class="text-gray-500"><?= lang('Admin/OrangTua.nik') ?></span> <span id="dtlWaliNik" class="font-medium text-gray-800 dark:text-slate-200 font-mono">-</span></div>
        <div class="flex justify-between"><span class="text-gray-500"><?= lang('Admin/OrangTua.occupation') ?></span> <span id="dtlWaliKerja" class="font-medium text-gray-800 dark:text-slate-200">-</span></div>
      </div>
    </div>
  </div>
  
  <div class="p-5 border-t border-gray-100 dark:border-slate-700 bg-white dark:bg-slate-800 z-10">
      <button id="btnDrawerEdit" class="w-full py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-xl transition-all shadow-lg flex justify-center items-center gap-2">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg> <?= lang('Admin/OrangTua.full_edit') ?>
      </button>
  </div>
</div>

<div id="addModal" class="fixed inset-0 hidden" style="z-index: 99999;">
  <div class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity pointer-events-auto" onclick="closeAddModal()"></div>
  <div class="absolute inset-0 flex items-center justify-center p-4 pointer-events-none md:pl-64">
    <div class="relative w-full bg-white dark:bg-slate-800 rounded-2xl shadow-2xl flex flex-col max-h-[95vh] pointer-events-auto border border-transparent dark:border-slate-700 transition-colors duration-300" style="max-width: 1000px;">

      <div class="p-5 border-b border-gray-100 dark:border-slate-700 flex items-center justify-between bg-white dark:bg-slate-800 rounded-t-2xl z-20 flex-shrink-0 transition-colors">
        <div>
          <h3 class="text-xl font-bold text-gray-800 dark:text-white"><?= lang('Admin/OrangTua.form_title') ?></h3>
          <p class="text-sm text-gray-500 dark:text-slate-400 mt-1"><?= lang('Admin/OrangTua.form_subtitle') ?></p>
        </div>
        <button type="button" onclick="closeAddModal()" class="p-2 hover:bg-gray-100 dark:hover:bg-slate-700 rounded-lg transition-colors cursor-pointer relative z-50 text-gray-500 dark:text-slate-400 outline-none">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
        </button>
      </div>

      <div class="flex-1 overflow-y-auto p-6 relative z-10 custom-scrollbar bg-gray-50/50 dark:bg-slate-900/50">
        <form id="addParentForm" class="space-y-6" onsubmit="handleSubmit(event)">
          <?= csrf_field() ?>

          <div class="bg-white dark:bg-slate-800 p-5 rounded-2xl shadow-sm border border-purple-100 dark:border-purple-900/30">
            <div class="flex items-center gap-2 pb-2 mb-4 border-b border-purple-100 dark:border-purple-800/50">
              <div class="w-8 h-8 rounded-lg bg-purple-100 dark:bg-purple-900/40 flex items-center justify-center">
                <svg class="w-5 h-5 text-purple-700 dark:text-purple-400" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
              </div>
              <h4 class="text-base font-bold text-gray-800 dark:text-white"><?= lang('Admin/OrangTua.student_connection') ?></h4>
            </div>
            <div class="relative">
              <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-2"><?= lang('Admin/OrangTua.select_student') ?> <span class="text-red-500">*</span></label>
              <input type="text" id="student_search" class="w-full px-4 py-2.5 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-xl text-sm font-medium text-gray-800 dark:text-white placeholder-gray-400 dark:placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-purple-500 transition-all shadow-sm" placeholder="<?= lang('Admin/OrangTua.search_student_placeholder') ?>" autocomplete="off">
              <input type="hidden" name="student" id="student_id_hidden" required>
              <div id="student_results" class="hidden absolute z-50 w-full bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-600 rounded-xl shadow-xl mt-1 max-h-60 overflow-y-auto custom-scrollbar"></div>
              <p class="text-xs text-gray-500 dark:text-slate-500 mt-1"><?= lang('Admin/OrangTua.student_connection_help') ?></p>
            </div>
          </div>

          <div class="bg-white dark:bg-slate-800 p-5 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700">
            <h4 class="text-md font-bold text-blue-600 dark:text-blue-400 border-b border-blue-100 dark:border-blue-800/50 pb-2 mb-4"><?= lang('Admin/OrangTua.form_father_data') ?></h4>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-gray-500 dark:text-slate-400 mb-1"><?= lang('Admin/OrangTua.father_full_name') ?></label>
                    <input type="text" name="nama_ayah" class="w-full px-3 py-2 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-lg text-sm text-gray-800 dark:text-white outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 dark:text-slate-400 mb-1"><?= lang('Admin/OrangTua.father_nik') ?></label>
                    <input type="text" name="nik_ayah" maxlength="16" class="w-full px-3 py-2 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-lg text-sm text-gray-800 dark:text-white outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 dark:text-slate-400 mb-1"><?= lang('Admin/OrangTua.birth_year') ?></label>
                    <input type="text" name="tahun_lahir_ayah" placeholder="<?= lang('Admin/OrangTua.year_example_80') ?>" maxlength="4" class="w-full px-3 py-2 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-lg text-sm text-gray-800 dark:text-white dark:placeholder-slate-400 outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 dark:text-slate-400 mb-1"><?= lang('Admin/OrangTua.father_education') ?></label>
                    <select name="pendidikan_ayah" class="w-full px-3 py-2 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-lg text-sm text-gray-800 dark:text-white outline-none cursor-pointer appearance-none">
                        <option value=""><?= lang('Admin/OrangTua.select_option') ?></option>
                        <option value="Tidak Sekolah"><?= lang('Admin/OrangTua.edu_none') ?></option>
                        <option value="SD/Sederajat"><?= lang('Admin/OrangTua.edu_sd') ?></option>
                        <option value="SMP/Sederajat"><?= lang('Admin/OrangTua.edu_smp') ?></option>
                        <option value="SMA/Sederajat"><?= lang('Admin/OrangTua.edu_sma') ?></option>
                        <option value="D1-D3"><?= lang('Admin/OrangTua.edu_diploma') ?></option>
                        <option value="S1/D4"><?= lang('Admin/OrangTua.edu_bachelor') ?></option>
                        <option value="S2"><?= lang('Admin/OrangTua.edu_master') ?></option>
                        <option value="S3"><?= lang('Admin/OrangTua.edu_doctorate') ?></option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 dark:text-slate-400 mb-1"><?= lang('Admin/OrangTua.father_occupation') ?></label>
                    <input type="text" name="pekerjaan_ayah" class="w-full px-3 py-2 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-lg text-sm text-gray-800 dark:text-white outline-none">
                </div>
                <div class="md:col-span-3">
                    <label class="block text-xs font-bold text-gray-500 dark:text-slate-400 mb-1"><?= lang('Admin/OrangTua.monthly_income') ?></label>
                    <select name="penghasilan_ayah" class="w-full px-3 py-2 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-lg text-sm text-gray-800 dark:text-white outline-none cursor-pointer appearance-none">
                        <option value=""><?= lang('Admin/OrangTua.select_income_range') ?></option>
                        <option value="Kurang dari Rp 1.000.000"><?= lang('Admin/OrangTua.income_less_1m') ?></option>
                        <option value="Rp 1.000.000 - Rp 2.000.000"><?= lang('Admin/OrangTua.income_1m_2m') ?></option>
                        <option value="Rp 2.000.000 - Rp 5.000.000"><?= lang('Admin/OrangTua.income_2m_5m') ?></option>
                        <option value="Rp 5.000.000 - Rp 20.000.000"><?= lang('Admin/OrangTua.income_5m_20m') ?></option>
                        <option value="Lebih dari Rp 20.000.000"><?= lang('Admin/OrangTua.income_more_20m') ?></option>
                    </select>
                </div>
            </div>
          </div>

          <div class="bg-white dark:bg-slate-800 p-5 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700">
            <h4 class="text-md font-bold text-pink-600 dark:text-pink-400 border-b border-pink-100 dark:border-pink-800/50 pb-2 mb-4"><?= lang('Admin/OrangTua.form_mother_data') ?></h4>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-gray-500 dark:text-slate-400 mb-1"><?= lang('Admin/OrangTua.mother_full_name') ?></label>
                    <input type="text" name="nama_ibu" class="w-full px-3 py-2 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-lg text-sm text-gray-800 dark:text-white outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 dark:text-slate-400 mb-1"><?= lang('Admin/OrangTua.mother_nik') ?></label>
                    <input type="text" name="nik_ibu" maxlength="16" class="w-full px-3 py-2 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-lg text-sm text-gray-800 dark:text-white outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 dark:text-slate-400 mb-1"><?= lang('Admin/OrangTua.birth_year') ?></label>
                    <input type="text" name="tahun_lahir_ibu" placeholder="<?= lang('Admin/OrangTua.year_example_82') ?>" maxlength="4" class="w-full px-3 py-2 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-lg text-sm text-gray-800 dark:text-white dark:placeholder-slate-400 outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 dark:text-slate-400 mb-1"><?= lang('Admin/OrangTua.mother_education') ?></label>
                    <select name="pendidikan_ibu" class="w-full px-3 py-2 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-lg text-sm text-gray-800 dark:text-white outline-none cursor-pointer appearance-none">
                        <option value=""><?= lang('Admin/OrangTua.select_option') ?></option>
                        <option value="Tidak Sekolah"><?= lang('Admin/OrangTua.edu_none') ?></option>
                        <option value="SD/Sederajat"><?= lang('Admin/OrangTua.edu_sd') ?></option>
                        <option value="SMP/Sederajat"><?= lang('Admin/OrangTua.edu_smp') ?></option>
                        <option value="SMA/Sederajat"><?= lang('Admin/OrangTua.edu_sma') ?></option>
                        <option value="D1-D3"><?= lang('Admin/OrangTua.edu_diploma') ?></option>
                        <option value="S1/D4"><?= lang('Admin/OrangTua.edu_bachelor') ?></option>
                        <option value="S2"><?= lang('Admin/OrangTua.edu_master') ?></option>
                        <option value="S3"><?= lang('Admin/OrangTua.edu_doctorate') ?></option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 dark:text-slate-400 mb-1"><?= lang('Admin/OrangTua.mother_occupation') ?></label>
                    <input type="text" name="pekerjaan_ibu" class="w-full px-3 py-2 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-lg text-sm text-gray-800 dark:text-white outline-none">
                </div>
                <div class="md:col-span-3">
                    <label class="block text-xs font-bold text-gray-500 dark:text-slate-400 mb-1"><?= lang('Admin/OrangTua.monthly_income') ?></label>
                    <select name="penghasilan_ibu" class="w-full px-3 py-2 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-lg text-sm text-gray-800 dark:text-white outline-none cursor-pointer appearance-none">
                        <option value=""><?= lang('Admin/OrangTua.select_income_range') ?></option>
                        <option value="Kurang dari Rp 1.000.000"><?= lang('Admin/OrangTua.income_less_1m') ?></option>
                        <option value="Rp 1.000.000 - Rp 2.000.000"><?= lang('Admin/OrangTua.income_1m_2m') ?></option>
                        <option value="Rp 2.000.000 - Rp 5.000.000"><?= lang('Admin/OrangTua.income_2m_5m') ?></option>
                        <option value="Rp 5.000.000 - Rp 20.000.000"><?= lang('Admin/OrangTua.income_5m_20m') ?></option>
                        <option value="Lebih dari Rp 20.000.000"><?= lang('Admin/OrangTua.income_more_20m') ?></option>
                    </select>
                </div>
            </div>
          </div>

          <div class="bg-white dark:bg-slate-800 p-5 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700">
            <h4 class="text-md font-bold text-gray-600 dark:text-gray-400 border-b border-gray-200 dark:border-gray-700 pb-2 mb-4"><?= lang('Admin/OrangTua.form_guardian_data') ?></h4>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-gray-500 dark:text-slate-400 mb-1"><?= lang('Admin/OrangTua.guardian_name') ?></label>
                    <input type="text" name="nama_wali" class="w-full px-3 py-2 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-lg text-sm text-gray-800 dark:text-white outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 dark:text-slate-400 mb-1"><?= lang('Admin/OrangTua.guardian_nik') ?></label>
                    <input type="text" name="nik_wali" maxlength="16" class="w-full px-3 py-2 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-lg text-sm text-gray-800 dark:text-white outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 dark:text-slate-400 mb-1"><?= lang('Admin/OrangTua.guardian_occupation') ?></label>
                    <input type="text" name="pekerjaan_wali" class="w-full px-3 py-2 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-lg text-sm text-gray-800 dark:text-white outline-none">
                </div>
            </div>
          </div>

          <div class="bg-white dark:bg-slate-800 p-5 rounded-2xl shadow-sm border border-emerald-100 dark:border-emerald-900/30">
            <div class="flex items-center gap-2 pb-2 mb-4 border-b border-emerald-100 dark:border-emerald-800/50">
              <div class="w-8 h-8 rounded-lg bg-emerald-100 dark:bg-emerald-900/40 flex items-center justify-center">
                <svg class="w-5 h-5 text-emerald-700 dark:text-emerald-400" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
              </div>
              <h4 class="text-base font-bold text-gray-800 dark:text-white"><?= lang('Admin/OrangTua.contact_login_access') ?></h4>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-2"><?= lang('Admin/OrangTua.phone_whatsapp') ?> <span class="text-red-500">*</span></label>
                <input type="tel" name="phone" id="phone" required placeholder="<?= lang('Admin/OrangTua.phone_format') ?>" class="w-full px-4 py-2.5 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-xl text-sm font-medium text-gray-800 dark:text-white dark:placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-emerald-500 transition-all shadow-sm">
                <p class="text-xs text-red-500 font-medium mt-1"><?= lang('Admin/OrangTua.phone_username_help') ?></p>
              </div>
              
              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-2"><?= lang('Admin/OrangTua.active_email') ?></label>
                <input type="email" name="email" id="email" placeholder="emailortu@gmail.com" class="w-full px-4 py-2.5 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-xl text-sm font-medium text-gray-800 dark:text-white dark:placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-emerald-500 transition-all shadow-sm">
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-2"><?= lang('Admin/OrangTua.account_status') ?></label>
                <select name="status_akun" id="status_akun" class="w-full px-4 py-2.5 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-xl text-sm font-medium text-gray-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500 transition-all shadow-sm cursor-pointer appearance-none">
                  <option value="1"><?= lang('Admin/OrangTua.active') ?></option>
                  <option value="0"><?= lang('Admin/OrangTua.inactive') ?></option>
                </select>
              </div>

              <div class="md:col-span-3">
                <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-2"><?= lang('Admin/OrangTua.full_address') ?></label>
                <textarea name="address" id="address" rows="2" placeholder="<?= lang('Admin/OrangTua.address_placeholder') ?>" class="w-full px-4 py-2.5 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-xl text-sm font-medium text-gray-800 dark:text-white dark:placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-emerald-500 resize-none transition-all shadow-sm"></textarea>
              </div>
            </div>
          </div> <div class="flex flex-col sm:flex-row gap-3 pt-6 sticky bottom-0 bg-gray-50/90 dark:bg-slate-900/90 backdrop-blur z-50 pb-2 transition-colors border-t border-gray-200 dark:border-slate-700">
            <button type="button" onclick="closeAddModal()" class="flex-1 px-6 py-3 bg-white dark:bg-slate-700 border border-gray-300 dark:border-slate-600 text-gray-700 dark:text-slate-300 font-semibold rounded-xl hover:bg-gray-50 dark:hover:bg-slate-600 transition-colors shadow-sm outline-none"> <?= lang('Admin/OrangTua.cancel') ?> </button>
            <button type="submit" class="flex-1 px-6 py-3 text-white font-semibold rounded-xl transition-all shadow-lg flex items-center justify-center gap-2 transform hover:-translate-y-0.5 outline-none" style="background-color: <?= $color['warna_primary'] ?>; box-shadow: 0 10px 15px -3px <?= $color['warna_primary'] ?>40;">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg> <?= lang('Admin/OrangTua.save_parent_data') ?>
            </button>
          </div>

        </form>
      </div>
    </div>
  </div>
</div>

<div id="importModal" class="fixed inset-0 hidden" style="z-index: 100000 !important;">
  <div class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity pointer-events-auto" onclick="closeImportModal()"></div>
  <div class="absolute inset-0 flex items-center justify-center p-4 pointer-events-none md:pl-64">
    <div class="relative w-full bg-white dark:bg-slate-800 rounded-2xl shadow-2xl flex flex-col max-h-[90vh] pointer-events-auto border border-transparent dark:border-slate-700 transition-colors duration-300" style="max-width: 500px; z-index: 100001;">
      <div class="p-6 border-b border-gray-100 dark:border-slate-700 flex items-center justify-between bg-white dark:bg-slate-800 rounded-t-2xl z-20 flex-shrink-0 transition-colors">
        <div>
          <h3 class="text-xl font-bold text-gray-800 dark:text-white"><?= lang('Admin/OrangTua.import_title') ?></h3>
          <p class="text-sm text-gray-500 dark:text-slate-400 mt-1"><?= lang('Admin/OrangTua.import_subtitle') ?></p>
        </div>
        <button type="button" onclick="closeImportModal()" class="p-2 hover:bg-gray-100 dark:hover:bg-slate-700 rounded-full transition-colors cursor-pointer text-gray-500 dark:text-slate-400 outline-none">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewbox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
      </div>
      <div class="p-6">
        <form id="importForm" action="<?= base_url('admin/orangtua/import') ?>" method="POST" onsubmit="handleImport(event)" enctype="multipart/form-data">
          <?= csrf_field() ?>
          <div class="mb-5">
            <p class="text-sm font-medium text-gray-700 dark:text-slate-300 mb-2"><?= lang('Admin/OrangTua.download_template_step') ?></p>
            <a href="<?= base_url('admin/orangtua/template') ?>" class="inline-flex items-center gap-2 px-4 py-2 font-medium rounded-lg transition-colors text-sm" style="color: <?= $color['warna_primary'] ?>; background-color: <?= $color['warna_primary'] ?>1A;">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewbox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
              </svg>
              <?= lang('Admin/OrangTua.download_template') ?>
            </a>
          </div>
          <div class="mb-6">
            <p class="text-sm font-medium text-gray-700 dark:text-slate-300 mb-2"><?= lang('Admin/OrangTua.upload_file_step') ?></p>
            <input type="file" name="file_excel" accept=".xlsx, .xls" required class="block w-full text-sm text-gray-500 dark:text-slate-400 file:mr-4 file:py-2.5 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-gray-100 dark:file:bg-slate-700 file:text-gray-700 dark:file:text-slate-200 hover:file:bg-gray-200 dark:hover:file:bg-slate-600 border dark:border-slate-600 cursor-pointer focus:outline-none focus:ring-1" style="border-color: <?= $color['warna_primary'] ?>; outline-color: <?= $color['warna_primary'] ?>;">
          </div>
          <div class="flex justify-end gap-3 pt-4 border-t border-gray-100 dark:border-slate-700">
            <button type="button" onclick="closeImportModal()" class="px-5 py-2.5 bg-white dark:bg-slate-700 border border-gray-300 dark:border-slate-600 text-gray-700 dark:text-slate-300 font-semibold rounded-xl hover:bg-gray-50 dark:hover:bg-slate-600 transition-colors outline-none shadow-sm"><?= lang('Admin/OrangTua.cancel') ?></button>
            <button type="submit" class="px-5 py-2.5 text-white font-semibold rounded-xl shadow-lg transition-transform flex items-center gap-2 outline-none transform hover:-translate-y-0.5" style="background-color: <?= $color['warna_primary'] ?>; box-shadow: 0 10px 15px -3px <?= $color['warna_primary'] ?>40;">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewbox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
              </svg> <?= lang('Admin/OrangTua.upload_import') ?>
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<?= $this->endSection() ?>
<?= $this->section('scripts') ?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
  const BASE_URL = "<?= base_url() ?>";
  // Menarik semua bahasa dari PHP dan merubahnya menjadi objek JS
  const LANG = {
        father: <?= json_encode(lang('Admin/OrangTua.father')) ?>,
        mother: <?= json_encode(lang('Admin/OrangTua.mother')) ?>,
        guardian: <?= json_encode(lang('Admin/OrangTua.guardian')) ?>,
        js_loading_data: <?= json_encode(lang('Admin/OrangTua.js_loading_data')) ?>,
        js_failed_load: <?= json_encode(lang('Admin/OrangTua.js_failed_load')) ?>,
        js_server_error: <?= json_encode(lang('Admin/OrangTua.js_server_error')) ?>,
        js_no_data: <?= json_encode(lang('Admin/OrangTua.js_no_data')) ?>,
        js_no_class: <?= json_encode(lang('Admin/OrangTua.js_no_class')) ?>,
        js_active: <?= json_encode(lang('Admin/OrangTua.js_active')) ?>,
        js_inactive: <?= json_encode(lang('Admin/OrangTua.js_inactive')) ?>,
        js_view_detail: <?= json_encode(lang('Admin/OrangTua.js_view_detail')) ?>,
        js_edit: <?= json_encode(lang('Admin/OrangTua.js_edit')) ?>,
        js_searching: <?= json_encode(lang('Admin/OrangTua.js_searching')) ?>,
        js_student_not_found: <?= json_encode(lang('Admin/OrangTua.js_student_not_found')) ?>,
        js_add_new_parent: <?= json_encode(lang('Admin/OrangTua.js_add_new_parent')) ?>,
        js_add_new_parent_desc: <?= json_encode(lang('Admin/OrangTua.js_add_new_parent_desc')) ?>,
        js_edit_parent: <?= json_encode(lang('Admin/OrangTua.js_edit_parent')) ?>,
        js_edit_parent_desc: <?= json_encode(lang('Admin/OrangTua.js_edit_parent_desc')) ?>,
        js_student_deleted: <?= json_encode(lang('Admin/OrangTua.js_student_deleted')) ?>,
        js_saving: <?= json_encode(lang('Admin/OrangTua.js_saving')) ?>,
        js_success: <?= json_encode(lang('Admin/OrangTua.js_success')) ?>,
        js_failed: <?= json_encode(lang('Admin/OrangTua.js_failed')) ?>,
        js_system_error: <?= json_encode(lang('Admin/OrangTua.js_system_error')) ?>,
        js_analyzing: <?= json_encode(lang('Admin/OrangTua.js_analyzing')) ?>,
        js_import_warning: <?= json_encode(lang('Admin/OrangTua.js_import_warning')) ?>,
        js_info: <?= json_encode(lang('Admin/OrangTua.js_info')) ?>,
        js_connection_lost: <?= json_encode(lang('Admin/OrangTua.js_connection_lost')) ?>,
        js_selected: <?= json_encode(lang('Admin/OrangTua.js_selected')) ?>,
        js_deactivate_title: <?= json_encode(lang('Admin/OrangTua.js_deactivate_title')) ?>,
        js_deactivate_desc: <?= json_encode(lang('Admin/OrangTua.js_deactivate_desc')) ?>,
        js_yes_deactivate: <?= json_encode(lang('Admin/OrangTua.js_yes_deactivate')) ?>,
        js_cancel: <?= json_encode(lang('Admin/OrangTua.js_cancel')) ?>,
        js_fetching_data: <?= json_encode(lang('Admin/OrangTua.js_fetching_data')) ?>
    };
</script>
<script src="<?= base_url('assets/js/Admin/orangtua.js') ?>?v=<?= time() ?>"></script>
<?= $this->endSection() ?>