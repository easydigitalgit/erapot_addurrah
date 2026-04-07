<?= $this->extend('layout/main') ?>

<?= $this->section('title') ?>
  <?= lang('Admin/TargetTahfidz.page_title_browser') ?: 'Target Tahfidz' ?>
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
    :root { --warna-scroll: <?= $color['warna_primary'] ?>; }
</style>
  <link rel="stylesheet" href="<?= base_url('assets/css/Admin/target-tahfidz.css') ?>">
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="mb-6">
  <div class="flex items-center gap-2 text-sm text-gray-500 dark:text-slate-400 mb-3 transition-colors">
    <span><?= lang('Admin/TargetTahfidz.academic_config') ?: 'Konfigurasi Akademik' ?></span>
    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewbox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
    </svg><span class="text-[<?= $color['warna_primary'] ?>] dark:text-[<?= $color['warna_primary'] ?>] font-medium"><?= lang('Admin/TargetTahfidz.page_title') ?: 'Target Tahfidz' ?></span>
  </div>
  <div class="text-center mb-6 py-4 bg-gradient-to-r from-[<?= $color['warna_secondary'] ?>]/40 to-[<?= $color['warna_secondary'] ?>]/20 dark:from-slate-800 dark:to-slate-800/80 rounded-2xl border border-[<?= $color['warna_primary'] ?>]/40 dark:border-[<?= $color['warna_primary'] ?>]/20 shadow-sm transition-colors">
    <p class="text-3xl arabic-text text-[<?= $color['warna_primary'] ?>] mb-2 font-bold drop-shadow-sm">بِسْمِ اللَّهِ الرَّحْمَٰنِ الرَّحِيمِ</p>
    <p class="text-xs text-gray-600 dark:text-slate-400 italic"><?= lang('Admin/TargetTahfidz.bismillah_translation') ?: 'Dengan menyebut nama Allah Yang Maha Pengasih lagi Maha Penyayang' ?></p>
  </div>
  <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
    <div>
      <h1 id="pageTitle" class="text-3xl md:text-3xl font-bold text-gray-800 dark:text-white mb-2 transition-colors"><?= lang('Admin/TargetTahfidz.page_title') ?: 'Target Tahfidz' ?></h1>
      <p id="pageSubtitle" class="text-sm md:text-base text-gray-600 dark:text-slate-400 transition-colors"><?= lang('Admin/TargetTahfidz.page_subtitle') ?: 'Atur target hafalan Al-Qur\'an untuk setiap tingkat kelas.' ?></p>
    </div>
    <div class="flex flex-wrap items-center gap-2">
        <button onclick="showAddTargetModal()" class="px-4 py-2.5 bg-[<?= $color['warna_primary'] ?>]/90 hover:bg-[<?= $color['warna_primary'] ?>] text-white font-semibold rounded-xl transition-all shadow-lg shadow-[<?= $color['warna_primary'] ?>]/20 flex items-center gap-2 transform hover:-translate-y-0.5 outline-none">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewbox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
        </svg><span><?= lang('Admin/TargetTahfidz.btn_add_target') ?: 'Tambah Target' ?></span> </button> 
        
        <button onclick="showImportModal()" class="px-4 py-2.5 bg-white dark:bg-slate-800 border border-gray-300 dark:border-slate-600 text-gray-700 dark:text-slate-300 font-semibold rounded-xl hover:bg-gray-50 dark:hover:bg-slate-700 transition-colors flex items-center gap-2 cursor-pointer shadow-sm outline-none">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewbox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10" />
        </svg><span class="hidden md:inline"><?= lang('Admin/TargetTahfidz.btn_import_target') ?: 'Import Excel' ?></span> </button> 
        
        <button onclick="showTemplateModal()" class="px-4 py-2.5 bg-white dark:bg-slate-800 border border-gray-300 dark:border-slate-600 text-gray-700 dark:text-slate-300 font-semibold rounded-xl hover:bg-gray-50 dark:hover:bg-slate-700 transition-colors flex items-center gap-2 cursor-pointer shadow-sm outline-none">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewbox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
        </svg><span class="hidden md:inline"><?= lang('Admin/TargetTahfidz.btn_template') ?: 'Template' ?></span> </button> 
        
        <button onclick="showRiwayatModal()" class="px-4 py-2.5 bg-white dark:bg-slate-800 border border-gray-300 dark:border-slate-600 text-gray-700 dark:text-slate-300 font-semibold rounded-xl hover:bg-gray-50 dark:hover:bg-slate-700 transition-colors flex items-center gap-2 cursor-pointer shadow-sm outline-none">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewbox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg><span class="hidden md:inline"><?= lang('Admin/TargetTahfidz.btn_history') ?: 'Riwayat' ?></span> </button>
    </div>
  </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6 mb-6">
  <div class="stat-card bg-white dark:bg-slate-800 rounded-2xl p-5 shadow-sm border border-gray-100 dark:border-slate-700 hover:shadow-md hover:border-[<?= $color['warna_primary'] ?>] transition-all group">
    <div class="flex items-center justify-between mb-4">
      <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-[<?= $color['warna_primary'] ?>]/80 to-[<?= $color['warna_primary'] ?>] flex items-center justify-center shadow-lg group-hover:scale-105 transition-transform">
        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewbox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
        </svg>
      </div><span class="text-[10px] font-black text-[<?= $color['warna_primary'] ?>] uppercase tracking-wider bg-[<?= $color['warna_primary'] ?>]/10 px-2 py-1 rounded-lg"><?= lang('Admin/TargetTahfidz.academic_year') ?: 'Tahun Ajaran' ?></span>
    </div>
    <p class="text-3xl font-black text-gray-900 dark:text-white mb-1 transition-colors"><?= esc($tahun_ajaran) ?></p>
    <p class="text-sm font-medium text-gray-600 dark:text-slate-400 transition-colors">Semester <?= esc($semester) ?></p>
  </div>

  <div class="stat-card bg-white dark:bg-slate-800 rounded-2xl p-5 shadow-sm border border-gray-100 dark:border-slate-700 hover:shadow-md hover:border-blue-500 transition-all group">
    <div class="flex items-center justify-between mb-4">
      <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center shadow-lg group-hover:scale-105 transition-transform">
        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewbox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
        </svg>
      </div><span class="text-[10px] font-black text-blue-600 dark:text-blue-400 uppercase tracking-wider bg-blue-50 dark:bg-blue-900/30 px-2 py-1 rounded-lg"><?= lang('Admin/TargetTahfidz.total_target') ?: 'Total Target' ?></span>
    </div>
    <p class="text-3xl font-black text-gray-900 dark:text-white mb-1 transition-colors"><?= $stats['total_target'] ?> Target</p>
    <p class="text-sm font-medium text-gray-600 dark:text-slate-400 transition-colors">Seluruh target aktif</p>
  </div>

  <div class="stat-card bg-white dark:bg-slate-800 rounded-2xl p-5 shadow-sm border border-gray-100 dark:border-slate-700 hover:shadow-md hover:border-purple-500 transition-all group">
    <div class="flex items-center justify-between mb-4">
      <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-purple-500 to-purple-600 flex items-center justify-center shadow-lg group-hover:scale-105 transition-transform">
        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewbox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
        </svg>
      </div><span class="text-[10px] font-black text-purple-600 dark:text-purple-400 uppercase tracking-wider bg-purple-50 dark:bg-purple-900/30 px-2 py-1 rounded-lg"><?= lang('Admin/TargetTahfidz.active_level') ?: 'Tingkat Aktif' ?></span>
    </div>
    <p class="text-3xl font-black text-gray-900 dark:text-white mb-1 transition-colors"><?= $stats['jml_tingkat'] ?> <?= lang('Admin/TargetTahfidz.level_count') ?: 'Tingkat' ?></p>
    <p class="text-sm font-medium text-gray-600 dark:text-slate-400 transition-colors truncate">Tingkat <?= esc($stats['list_tingkat']) ?></p>
  </div>

  <div class="stat-card bg-white dark:bg-slate-800 rounded-2xl p-5 shadow-sm border border-gray-100 dark:border-slate-700 hover:shadow-md hover:border-amber-500 transition-all group">
    <div class="flex items-center justify-between mb-4">
      <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-amber-500 to-amber-600 flex items-center justify-center shadow-lg group-hover:scale-105 transition-transform">
        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewbox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
      </div><span class="text-[10px] font-black text-amber-600 dark:text-amber-400 uppercase tracking-wider bg-amber-50 dark:bg-amber-900/30 px-2 py-1 rounded-lg">Partisipasi Setoran</span>
    </div>
    <p class="text-3xl font-black text-gray-900 dark:text-white mb-1 transition-colors"><?= $stats['persen_partisipasi'] ?>%</p>
    <p class="text-sm font-medium text-gray-600 dark:text-slate-400 transition-colors">Siswa mulai menyetor</p>
  </div>
</div>

<div class="bg-white dark:bg-slate-800 p-4 md:p-5 rounded-2xl border border-gray-100 dark:border-slate-700 shadow-sm mb-6 transition-colors">
  <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
    <div>
      <select id="filter_tingkat" onchange="filterTable()" class="w-full bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 text-gray-800 dark:text-white px-4 py-2.5 rounded-xl text-sm focus:ring-2 focus:ring-[<?= $color['warna_primary'] ?>] transition-colors outline-none cursor-pointer">
        <option value=""><?= lang('Admin/TargetTahfidz.filter_level') ?: 'Pilih Tingkat' ?></option>
        <option value="VII">Kelas VII</option>
        <option value="VIII">Kelas VIII</option>
        <option value="IX">Kelas IX</option>
      </select>
    </div>
    <div>
      <select id="filter_semester" onchange="filterTable()" class="w-full bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 text-gray-800 dark:text-white px-4 py-2.5 rounded-xl text-sm focus:ring-2 focus:ring-[<?= $color['warna_primary'] ?>] transition-colors outline-none cursor-pointer">
        <option value=""><?= lang('Admin/TargetTahfidz.filter_semester') ?: 'Pilih Semester' ?></option>
        <option value="Ganjil">Ganjil</option>
        <option value="Genap">Genap</option>
      </select>
    </div>
    <div>
      <select id="filter_status" onchange="filterTable()" class="w-full bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 text-gray-800 dark:text-white px-4 py-2.5 rounded-xl text-sm focus:ring-2 focus:ring-[<?= $color['warna_primary'] ?>] transition-colors outline-none cursor-pointer">
        <option value=""><?= lang('Admin/TargetTahfidz.filter_status') ?: 'Pilih Status' ?></option>
        <option value="Aktif">Aktif</option>
        <option value="Nonaktif">Nonaktif</option>
      </select>
    </div>
    <div class="flex items-center justify-between gap-3">
      <label class="flex items-center gap-2 cursor-pointer">
        <input type="checkbox" id="check_aktif" onchange="toggleActiveCheck()" class="w-4 h-4 text-[<?= $color['warna_primary'] ?>] rounded border-gray-300 dark:border-slate-500 focus:ring-[<?= $color['warna_primary'] ?>]">
        <span class="text-sm font-bold text-gray-700 dark:text-slate-300"><?= lang('Admin/TargetTahfidz.only_active') ?: 'Hanya Aktif' ?></span>
      </label>
      <button onclick="resetFilter()" class="text-sm font-bold text-gray-500 dark:text-slate-400 hover:text-gray-800 dark:hover:text-white transition-colors outline-none underline"><?= lang('Admin/TargetTahfidz.reset') ?: 'Reset' ?></button>
    </div>
  </div>
</div>

<div class="bg-white dark:bg-slate-800 rounded-3xl border border-gray-100 dark:border-slate-700 shadow-sm overflow-hidden mb-6 transition-colors">
  <div class="overflow-x-auto custom-scrollbar">
    <table class="w-full min-w-max text-left border-collapse">
      <thead class="bg-gray-50 dark:bg-slate-900/50 border-b border-gray-100 dark:border-slate-700 transition-colors">
        <tr class="text-[11px] font-black text-gray-500 dark:text-slate-400 uppercase tracking-widest">
          <th class="px-6 py-4"><?= lang('Admin/TargetTahfidz.th_level') ?: 'Tingkat' ?></th>
          <th class="px-6 py-4"><?= lang('Admin/TargetTahfidz.th_semester') ?: 'Semester' ?></th>
          <th class="px-6 py-4"><?= lang('Admin/TargetTahfidz.th_juz_target') ?: 'Target Juz' ?></th>
          <th class="px-6 py-4"><?= lang('Admin/TargetTahfidz.th_surah_target') ?: 'Target Surah' ?></th>
          <th class="px-6 py-4"><?= lang('Admin/TargetTahfidz.th_min_memorization') ?: 'Minimal' ?></th>
          <th class="px-6 py-4 text-center"><?= lang('Admin/TargetTahfidz.th_status') ?: 'Status' ?></th>
          <th class="px-6 py-4 text-center"><?= lang('Admin/TargetTahfidz.th_action') ?: 'Aksi' ?></th>
        </tr>
      </thead>
      <tbody class="divide-y divide-gray-100 dark:divide-slate-700/50 transition-colors">
        <?php if(empty($targets)): ?>
          <tr><td colspan="7" class="text-center py-10 text-gray-500">Belum ada data Target Tahfidz.</td></tr>
        <?php else: ?>
          <?php foreach($targets as $t): ?>
            <tr class="target-row hover:bg-gray-50/50 dark:hover:bg-slate-700/30 transition-colors group" 
                data-tingkat="<?= $t['tingkat'] ?>" 
                data-semester="<?= $t['semester'] ?>" 
                data-status="<?= $t['status'] ?>">
              
              <td class="px-6 py-4">
                <span class="inline-block px-3 py-1 bg-gray-100 dark:bg-slate-700 text-gray-700 dark:text-slate-300 font-bold text-xs rounded-lg border border-gray-200 dark:border-slate-600 shadow-sm group-hover:border-[<?= $color['warna_primary'] ?>] transition-colors">
                  <?= $t['tingkat'] ?>
                </span>
              </td>
              <td class="px-6 py-4 font-bold text-gray-800 dark:text-white"><?= $t['semester'] ?></td>
              <td class="px-6 py-4">
                <div class="flex items-center gap-3">
                  <div class="w-8 h-8 rounded-full bg-[<?= $color['warna_primary'] ?>]/10 text-[<?= $color['warna_primary'] ?>] flex items-center justify-center font-bold text-xs border border-[<?= $color['warna_primary'] ?>]/20">
                    <?= preg_replace('/[^0-9]/', '', $t['nama_juz']) ?>
                  </div>
                  <span class="font-bold text-gray-800 dark:text-white"><?= $t['nama_juz'] ?></span>
                </div>
              </td>
              <td class="px-6 py-4">
                <div class="flex flex-col">
                  <span class="text-sm font-bold text-gray-800 dark:text-white mb-0.5"><?= $t['surah_mulai'] ?></span>
                  <span class="text-[10px] text-gray-400 dark:text-slate-500 uppercase font-bold tracking-widest flex items-center gap-1">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3" /></svg> s/d
                  </span>
                  <span class="text-sm font-bold text-gray-800 dark:text-white mt-0.5"><?= $t['surah_sampai'] ?></span>
                </div>
              </td>
              <td class="px-6 py-4">
                <span class="inline-flex px-2.5 py-1 bg-emerald-50 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400 font-black text-sm rounded-lg border border-emerald-200 dark:border-emerald-800/50 shadow-sm"><?= $t['minimal_hafalan'] ?>%</span>
                <div class="text-[10px] font-bold uppercase tracking-wider text-gray-500 dark:text-slate-500 mt-1">
                  <?= lang('Admin/TargetTahfidz.min_memorization_label') ?: 'Target Capaian' ?>
                </div>
              </td>
              <td class="px-6 py-4 text-center">
                <?php $badge = $t['status'] == 'Aktif' ? 'bg-emerald-50 text-emerald-700 border-emerald-200 dark:bg-emerald-900/30 dark:text-emerald-400 dark:border-emerald-800/50' : 'bg-gray-100 text-gray-600 border-gray-200 dark:bg-slate-700 dark:text-slate-400 dark:border-slate-600'; ?>
                <span class="inline-flex px-2.5 py-1 rounded-lg text-[10px] font-black uppercase tracking-wider border <?= $badge ?>"><?= $t['status'] ?></span>
              </td>
              <td class="px-6 py-4 text-center">
                <div class="flex items-center justify-center gap-2 opacity-1 lg:opacity-0 lg:group-hover:opacity-100 transition-opacity">
                  <button onclick="showDetailDrawer('<?= $t['tingkat'] ?>', '<?= $t['semester'] ?>', '<?= htmlspecialchars($t['nama_juz'], ENT_QUOTES, 'UTF-8') ?>', '<?= htmlspecialchars($t['surah_mulai'], ENT_QUOTES, 'UTF-8') ?>', '<?= htmlspecialchars($t['surah_sampai'], ENT_QUOTES, 'UTF-8') ?>', '<?= $t['minimal_hafalan'] ?>')" 
              class="p-2 bg-[<?= $color['warna_secondary'] ?>]/70 dark:bg-slate-700 text-[<?= $color['warna_primary'] ?>] dark:text-[<?= $color['warna_primary'] ?>] rounded-lg hover:bg-[<?= $color['warna_secondary'] ?>] dark:hover:bg-slate-600 transition-colors border border-[<?= $color['warna_primary'] ?>]/30 dark:border-slate-600 flex items-center gap-1 text-[11px] font-bold uppercase tracking-wider cursor-pointer shadow-sm outline-none">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewbox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg> <span class="hidden xl:inline"><?= lang('Admin/TargetTahfidz.btn_detail') ?: 'Detail' ?></span> 
                  </button>
                  
                  <button onclick='showEditModal(<?= json_encode($t) ?>)' 
              class="p-2 bg-white dark:bg-slate-700 text-gray-600 dark:text-slate-300 rounded-lg hover:bg-gray-50 dark:hover:bg-slate-600 transition-colors border border-gray-300 dark:border-slate-500 flex items-center gap-1 text-[11px] font-bold uppercase tracking-wider cursor-pointer shadow-sm outline-none">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewbox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg> <span class="hidden xl:inline"><?= lang('Admin/TargetTahfidz.btn_edit') ?: 'Edit' ?></span> 
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
<?= $this->endSection() ?>

<?= $this->section('modals') ?>

<div id="drawerOverlay" class="drawer-overlay fixed inset-0 hidden bg-gray-900/60 backdrop-blur-sm transition-opacity duration-300 opacity-0 z-[99998]" onclick="closeDrawer()"></div>
<div id="detailDrawer" class="drawer fixed inset-y-0 right-0 hidden bg-white dark:bg-slate-800 shadow-2xl w-full sm:w-[400px] transition-transform duration-300 transform translate-x-full border-l border-gray-200 dark:border-slate-700 z-[99999]">
  <div class="p-6 border-b border-gray-100 dark:border-slate-700 flex items-center justify-between bg-white dark:bg-slate-800 transition-colors">
    <h3 class="text-xl font-bold text-gray-800 dark:text-white"><?= lang('Admin/TargetTahfidz.modal_detail_title') ?: 'Detail Target' ?></h3>
    <button onclick="closeDrawer()" class="p-2 text-gray-400 hover:text-gray-600 dark:hover:text-slate-300 hover:bg-gray-100 dark:hover:bg-slate-700 rounded-full transition-colors cursor-pointer outline-none">
      <svg class="w-6 h-6" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
    </button>
  </div>

  <div class="p-6 space-y-6 overflow-y-auto flex-1 custom-scrollbar" style="height: calc(100vh - 100px);">
    <div class="text-center pb-6 border-b border-gray-100 dark:border-slate-700 transition-colors">
      <div class="w-20 h-20 mx-auto rounded-2xl bg-gradient-to-br from-[<?= $color['warna_primary'] ?>] to-[<?= $color['warna_primary'] ?>]/70 flex items-center justify-center text-white font-bold text-2xl mb-4 shadow-lg border border-transparent">
        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
      </div>
      <h4 id="drawerTitle" class="text-xl font-bold text-gray-800 dark:text-white mb-1">...</h4>
      <p id="drawerSubtitle" class="text-sm font-medium text-gray-500 dark:text-slate-400">...</p>
    </div>
    
    <div class="space-y-4">
      <div class="bg-gray-50 dark:bg-slate-900/50 p-4 rounded-xl border border-gray-100 dark:border-slate-700 transition-colors">
        <label class="block text-[10px] font-bold text-gray-500 dark:text-slate-400 uppercase tracking-wider mb-1">Mulai Surah</label>
        <p id="drawerSurahMulai" class="font-bold text-gray-800 dark:text-white text-base">...</p>
      </div>
      <div class="bg-gray-50 dark:bg-slate-900/50 p-4 rounded-xl border border-gray-100 dark:border-slate-700 transition-colors">
        <label class="block text-[10px] font-bold text-gray-500 dark:text-slate-400 uppercase tracking-wider mb-1">Sampai Surah</label>
        <p id="drawerSurahSampai" class="font-bold text-gray-800 dark:text-white text-base">...</p>
      </div>
      <div class="bg-emerald-50 dark:bg-emerald-900/20 p-4 rounded-xl border border-emerald-100 dark:border-emerald-800/50 transition-colors">
        <label class="block text-[10px] font-bold text-emerald-600 dark:text-emerald-400 uppercase tracking-wider mb-1">Target Hafalan Minimal</label>
        <p id="drawerMinimal" class="font-black text-emerald-700 dark:text-emerald-300 text-2xl">100%</p>
      </div>
    </div>
  </div>
</div>


<div id="addTargetModal" class="hidden fixed inset-0 z-[99999] overflow-y-auto">
  <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" onclick="closeAddTargetModal()"></div>
  <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0 md:pl-64">
    <div class="relative transform overflow-hidden rounded-3xl bg-white dark:bg-slate-800 text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-lg border border-transparent dark:border-slate-700">
      <div class="bg-white dark:bg-slate-800 px-6 py-5 border-b border-gray-100 dark:border-slate-700 flex justify-between items-center transition-colors">
        <div>
          <h3 class="text-xl font-bold leading-6 text-gray-900 dark:text-white"><?= lang('Admin/TargetTahfidz.modal_add_title') ?: 'Tambah Target Baru' ?></h3>
        </div>
        <button type="button" onclick="closeAddTargetModal()" class="p-2 text-gray-400 hover:text-gray-600 dark:hover:text-slate-300 hover:bg-gray-100 dark:hover:bg-slate-700 rounded-full transition-colors outline-none"><svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg></button>
      </div>
      <form id="formAddTarget" action="<?= base_url('admin/target-tahfidz/store') ?>" onsubmit="saveTarget(event)" class="p-6 space-y-5">
        <?= csrf_field() ?>
        <div class="grid grid-cols-2 gap-4">
          <div>
            <label class="block text-[11px] font-bold text-gray-500 uppercase tracking-widest mb-2"><?= lang('Admin/TargetTahfidz.th_level') ?: 'Tingkat' ?> <span class="text-red-500">*</span></label>
            <select name="tingkat" required class="block w-full rounded-xl border-gray-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-gray-800 dark:text-white shadow-sm focus:border-[<?= $color['warna_primary'] ?>] focus:ring-[<?= $color['warna_primary'] ?>] sm:text-sm py-3 px-4 border transition-colors appearance-none cursor-pointer outline-none">
              <option value="">Pilih Tingkat</option><option value="VII">Kelas VII</option><option value="VIII">Kelas VIII</option><option value="IX">Kelas IX</option>
            </select>
          </div>
          <div>
            <label class="block text-[11px] font-bold text-gray-500 uppercase tracking-widest mb-2"><?= lang('Admin/TargetTahfidz.th_semester') ?: 'Semester' ?> <span class="text-red-500">*</span></label>
            <select name="semester" required class="block w-full rounded-xl border-gray-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-gray-800 dark:text-white shadow-sm focus:border-[<?= $color['warna_primary'] ?>] focus:ring-[<?= $color['warna_primary'] ?>] sm:text-sm py-3 px-4 border transition-colors appearance-none cursor-pointer outline-none">
              <option value="">Pilih Semester</option><option value="Ganjil">Ganjil</option><option value="Genap">Genap</option>
            </select>
          </div>
        </div>
        <div>
          <label class="block text-[11px] font-bold text-gray-500 uppercase tracking-widest mb-2"><?= lang('Admin/TargetTahfidz.th_juz') ?: 'Pilih Juz' ?> <span class="text-red-500">*</span></label>
          <select name="juz_id" required class="block w-full rounded-xl border-gray-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-gray-800 dark:text-white shadow-sm focus:border-[<?= $color['warna_primary'] ?>] focus:ring-[<?= $color['warna_primary'] ?>] sm:text-sm py-3 px-4 border transition-colors appearance-none cursor-pointer outline-none">
            <option value="">Pilih Juz...</option>
            <?php foreach ($ref_juz as $juz): ?><option value="<?= $juz['id'] ?>"><?= $juz['nama_juz'] ?></option><?php endforeach; ?>
          </select>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
          <div>
            <label class="block text-[11px] font-bold text-gray-500 uppercase tracking-widest mb-2">Mulai Surah <span class="text-red-500">*</span></label>
            <select name="surah_mulai_id" required class="block w-full rounded-xl border-gray-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-gray-800 dark:text-white shadow-sm focus:border-[<?= $color['warna_primary'] ?>] focus:ring-[<?= $color['warna_primary'] ?>] sm:text-sm py-3 px-4 border transition-colors appearance-none cursor-pointer outline-none">
              <option value="">Pilih Juz Dahulu...</option>
            </select>
          </div>
          <div>
            <label class="block text-[11px] font-bold text-gray-500 uppercase tracking-widest mb-2">Sampai Surah <span class="text-red-500">*</span></label>
            <select name="surah_sampai_id" required class="block w-full rounded-xl border-gray-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-gray-800 dark:text-white shadow-sm focus:border-[<?= $color['warna_primary'] ?>] focus:ring-[<?= $color['warna_primary'] ?>] sm:text-sm py-3 px-4 border transition-colors appearance-none cursor-pointer outline-none">
              <option value="">Pilih Juz Dahulu...</option>
            </select>
          </div>
        </div>
        <div class="pt-4 border-t border-gray-100 dark:border-slate-700 flex justify-end gap-3">
          <button type="button" onclick="closeAddTargetModal()" class="px-5 py-2.5 bg-gray-100 dark:bg-slate-700 text-gray-700 dark:text-slate-300 font-bold rounded-xl outline-none">Batal</button>
          <button type="submit" class="px-6 py-2.5 bg-[<?= $color['warna_primary'] ?>] text-white font-bold rounded-xl shadow-lg outline-none">Simpan Target</button>
        </div>
      </form>
    </div>
  </div>
</div>

<div id="editTargetModal" class="hidden fixed inset-0 z-[99999] overflow-y-auto">
  <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" onclick="closeEditTargetModal()"></div>
  <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0 md:pl-64">
    <div class="relative transform overflow-hidden rounded-3xl bg-white dark:bg-slate-800 text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-lg border border-transparent dark:border-slate-700">
      <div class="bg-white dark:bg-slate-800 px-6 py-5 border-b border-gray-100 dark:border-slate-700 flex justify-between items-center transition-colors">
        <div>
            <h3 class="text-xl font-black text-gray-800 dark:text-white"><?= lang('Admin/TargetTahfidz.modal_edit_title') ?: 'Edit Target' ?></h3>
            <p class="text-[11px] font-bold text-gray-500 mt-1 uppercase tracking-widest"><span id="text_editing">Editing:</span> <span id="label_tingkat_semester" class="text-[<?= $color['warna_primary'] ?>]"></span></p>
        </div>
        <button onclick="closeEditTargetModal()" class="text-gray-400 hover:bg-gray-200 dark:hover:bg-slate-700 p-2 rounded-full transition-colors outline-none"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg></button>
      </div>
      <form id="formEditTarget" action="<?= base_url('admin/target-tahfidz/update') ?>" onsubmit="updateTarget(event)" class="p-6 space-y-5">
        <?= csrf_field() ?>
        <input type="hidden" name="id" id="edit_id">
        <div>
          <label class="block text-[11px] font-bold text-gray-500 uppercase tracking-widest mb-2"><?= lang('Admin/TargetTahfidz.th_juz') ?: 'Pilih Juz' ?> <span class="text-red-500">*</span></label>
          <select name="juz_id" id="edit_juz_id" required class="block w-full rounded-xl border-gray-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-gray-800 dark:text-white shadow-sm focus:border-[<?= $color['warna_primary'] ?>] focus:ring-[<?= $color['warna_primary'] ?>] sm:text-sm py-3 px-4 border transition-colors appearance-none cursor-pointer outline-none">
            <option value="">Pilih Juz...</option>
            <?php foreach($ref_juz as $j): ?><option value="<?= $j['id'] ?>"><?= $j['nama_juz'] ?></option><?php endforeach; ?>
          </select>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
          <div>
            <label class="block text-[11px] font-bold text-gray-500 uppercase tracking-widest mb-2">Mulai Surah <span class="text-red-500">*</span></label>
            <select name="surah_mulai_id" id="edit_surah_mulai_id" required class="block w-full rounded-xl border-gray-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-gray-800 dark:text-white shadow-sm focus:border-[<?= $color['warna_primary'] ?>] focus:ring-[<?= $color['warna_primary'] ?>] sm:text-sm py-3 px-4 border transition-colors appearance-none cursor-pointer outline-none"></select>
          </div>
          <div>
            <label class="block text-[11px] font-bold text-gray-500 uppercase tracking-widest mb-2">Sampai Surah <span class="text-red-500">*</span></label>
            <select name="surah_sampai_id" id="edit_surah_sampai_id" required class="block w-full rounded-xl border-gray-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-gray-800 dark:text-white shadow-sm focus:border-[<?= $color['warna_primary'] ?>] focus:ring-[<?= $color['warna_primary'] ?>] sm:text-sm py-3 px-4 border transition-colors appearance-none cursor-pointer outline-none"></select>
          </div>
        </div>
        <div class="pt-4 border-t border-gray-100 dark:border-slate-700 flex justify-end gap-3">
          <button type="button" onclick="closeEditTargetModal()" class="px-5 py-2.5 bg-gray-100 dark:bg-slate-700 text-gray-700 dark:text-slate-300 font-bold rounded-xl outline-none">Batal</button>
          <button type="submit" class="px-6 py-2.5 bg-[<?= $color['warna_primary'] ?>] text-white font-bold rounded-xl shadow-lg outline-none">Update Target</button>
        </div>
      </form>
    </div>
  </div>
</div>

<div id="templateModal" class="hidden fixed inset-0 z-[99999] overflow-y-auto">
  <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" onclick="closeTemplateModal()"></div>
  <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0 md:pl-64">
    <div class="relative transform overflow-hidden rounded-3xl bg-white dark:bg-slate-800 text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-2xl border border-transparent dark:border-slate-700">
      <div class="bg-white dark:bg-slate-800 px-6 py-5 border-b border-gray-100 dark:border-slate-700 flex justify-between items-center transition-colors">
        <div>
            <h3 class="text-xl font-bold leading-6 text-gray-900 dark:text-white"><?= lang('Admin/TargetTahfidz.modal_template_title') ?: 'Pilih Template' ?></h3>
            <p class="text-sm font-medium text-gray-500 dark:text-slate-400 mt-1"><?= lang('Admin/TargetTahfidz.modal_template_subtitle') ?: 'Download Template Excel yang tersedia' ?></p>
        </div>
        <button type="button" onclick="closeTemplateModal()" class="p-2 text-gray-400 hover:text-gray-600 dark:hover:text-slate-300 hover:bg-gray-100 dark:hover:bg-slate-700 rounded-full transition-colors outline-none">
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
        </button>
      </div>
      <div class="px-6 py-6 space-y-4 custom-scrollbar">
        <a href="<?= base_url('admin/target-tahfidz/template') ?>" onclick="closeTemplateModal()" class="block border-2 border-gray-200 dark:border-slate-600 bg-white dark:bg-slate-700/50 rounded-2xl p-5 hover:border-[<?= $color['warna_primary'] ?>] dark:hover:border-[<?= $color['warna_primary'] ?>] transition-colors cursor-pointer group shadow-sm">
          <div class="flex items-start justify-between mb-2">
            <div class="flex items-center gap-4">
              <div class="w-14 h-14 rounded-xl flex items-center justify-center shadow-md group-hover:scale-105 transition-transform" style="background: linear-gradient(135deg, <?= $color['warna_primary'] ?>, <?= $color['warna_primary'] ?>cc);">
                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
              </div>
              <div>
                <h4 class="font-bold text-gray-900 dark:text-white text-lg group-hover:text-[<?= $color['warna_primary'] ?>] transition-colors">Template Excel Kosong</h4>
                <p class="text-sm font-medium text-gray-500 dark:text-slate-400 mt-0.5">Berisi referensi ID Juz dan Surah untuk diisi manual</p>
              </div>
            </div>
            <span class="px-2.5 py-1 bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400 text-[10px] font-black uppercase tracking-wider rounded-full border border-emerald-200 dark:border-emerald-800/50 shadow-sm">Download</span>
          </div>
        </a>
      </div>
    </div>
  </div>
</div>

<div id="importModal" class="hidden fixed inset-0 z-[99999] flex items-center justify-center p-4 overflow-y-auto">
  <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" onclick="closeImportModal()"></div>
  <div class="relative w-full max-w-lg bg-white dark:bg-slate-800 rounded-3xl shadow-2xl flex flex-col transform transition-colors border border-gray-200 dark:border-slate-700">
    <div class="p-5 border-b border-gray-100 dark:border-slate-700 flex justify-between items-center bg-white dark:bg-slate-800 rounded-t-3xl transition-colors">
        <div>
            <h3 class="text-xl font-bold text-gray-800 dark:text-white">Import Data Target</h3>
            <p class="text-sm text-gray-500 dark:text-slate-400 mt-0.5">Upload file excel massal</p>
        </div>
        <button type="button" onclick="closeImportModal()" class="p-2 text-gray-400 hover:text-gray-600 dark:hover:text-slate-300 hover:bg-gray-100 dark:hover:bg-slate-700 rounded-lg transition-colors outline-none"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
    </div>
    <div class="p-6">
        <form action="<?= base_url('admin/target-tahfidz/import') ?>" onsubmit="handleImportSubmit(event)" method="POST" enctype="multipart/form-data" class="space-y-5">
            <?= csrf_field() ?>
            <div>
                <p class="text-sm font-medium text-gray-700 dark:text-slate-300 mb-2">1. Download Template</p>
                <a href="<?= base_url('admin/target-tahfidz/template') ?>" class="inline-flex items-center gap-2 px-4 py-2 font-bold rounded-lg transition-colors text-sm border shadow-sm hover:opacity-80" style="color: <?= $color['warna_primary'] ?>; border-color: <?= $color['warna_primary'] ?>; background-color: <?= $color['warna_primary'] ?>1A;">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" /></svg> Download Template Excel
                </a>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-700 dark:text-slate-300 mb-2">2. Upload File</p>
                <input type="file" name="file_excel" accept=".xlsx, .xls" required class="block w-full text-sm text-gray-500 dark:text-slate-400 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-gray-100 dark:file:bg-slate-700 file:text-gray-700 dark:file:text-slate-200 hover:file:bg-gray-200 dark:hover:file:bg-slate-600 border dark:border-slate-600 cursor-pointer outline-none focus:ring-2 focus:ring-[<?= $color['warna_primary'] ?>] transition-colors">
            </div>
            <div class="flex gap-3 pt-4 border-t border-gray-100 dark:border-slate-700">
                <button type="button" onclick="closeImportModal()" class="flex-1 px-5 py-2.5 bg-white dark:bg-slate-700 border border-gray-300 dark:border-slate-600 text-gray-700 dark:text-slate-300 font-bold rounded-xl shadow-sm outline-none">Batal</button>
                <button type="submit" class="flex-1 px-5 py-3 text-white font-bold rounded-xl shadow-lg outline-none" style="background-color: <?= $color['warna_primary'] ?>;">Upload Data</button>
            </div>
        </form>
    </div>
  </div>
</div>

<div id="riwayatModal" class="hidden fixed inset-0 z-[100000] flex items-center justify-center p-4">
  <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" onclick="closeRiwayatModal()"></div>
  <div class="relative w-full max-w-lg bg-gray-50 dark:bg-slate-900 rounded-3xl shadow-2xl overflow-hidden transform transition-all h-[80vh] flex flex-col border border-gray-200 dark:border-slate-700">
    <div class="p-6 border-b border-gray-200 dark:border-slate-800 flex justify-between items-center bg-white dark:bg-slate-800 z-10 shadow-sm transition-colors">
      <h3 class="text-xl font-black text-gray-800 dark:text-white">Riwayat Aktivitas</h3>
      <button onclick="closeRiwayatModal()" class="text-gray-400 hover:bg-gray-100 dark:hover:bg-slate-700 p-2 rounded-full outline-none transition-colors"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg></button>
    </div>
    <div class="p-6 overflow-y-auto flex-1 custom-scrollbar bg-gray-50 dark:bg-slate-900 transition-colors">
        <ul id="listRiwayatContainer" class="space-y-0"></ul>
    </div>
  </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    const BASE_URL = "<?= base_url() ?>";
    
    // Perbaikan: Pastikan LANG selalu berupa Object
    let rawLang = <?= json_encode(lang('Admin/TargetTahfidz')) ?>;
    const LANG = typeof rawLang === 'object' ? rawLang : {};
</script>
<script src="<?= base_url('assets/js/Admin/target-tahfidz.js') ?>?v=<?= time() ?>"></script>
<?= $this->endSection() ?>