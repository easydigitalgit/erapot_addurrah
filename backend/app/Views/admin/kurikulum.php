<?= $this->extend('layout/main') ?>

<?= $this->section('title') ?>
<?= lang('Admin/Kurikulum.page_title') ?> - Rapor Digital SMPIT Ad Durrah
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<link rel="stylesheet" href="<?= base_url('assets/css/Admin/kurikulum.css') ?>">
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="mb-6">
  <div class="flex items-center gap-2 text-sm text-gray-500 dark:text-slate-400 mb-2 transition-colors">
    <span><?= lang('Admin/Kurikulum.breadcrumb') ?></span>
    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewbox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
    </svg>
    <span class="text-[<?= $color['warna_primary'] ?>] dark:text-[<?= $color['warna_primary'] ?>] font-medium"><?= lang('Admin/Kurikulum.page_title') ?></span>
  </div>
  <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
    <div>
      <h1 class="text-2xl md:text-3xl font-bold text-gray-800 dark:text-white mb-2 transition-colors"><?= lang('Admin/Kurikulum.page_title') ?></h1>
      <p class="text-sm md:text-base text-gray-600 dark:text-slate-400 transition-colors"><?= lang('Admin/Kurikulum.page_desc') ?></p>
    </div>
    <div class="flex flex-wrap items-center gap-2">
      <button onclick="showAddCurriculumModal()" class="px-4 py-2.5 bg-[<?= $color['warna_primary'] ?>] hover:bg-[<?= $color['warna_primary'] ?>]/90 text-white font-semibold rounded-xl transition-all shadow-lg shadow-[<?= $color['warna_primary'] ?>]/20 flex items-center gap-2 transform hover:-translate-y-0.5 outline-none">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewbox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
        </svg><span><?= lang('Admin/Kurikulum.btn_add') ?></span> </button>

      <button onclick="showApplyModal()" class="px-4 py-2.5 bg-white dark:bg-slate-800 border-2 border-[<?= $color['warna_primary'] ?>] dark:border-[<?= $color['warna_primary'] ?>]/80 text-[<?= $color['warna_primary'] ?>] font-semibold rounded-xl hover:bg-[<?= $color['warna_secondary'] ?>]/80 dark:hover:bg-slate-700 transition-colors flex items-center gap-2 shadow-sm outline-none">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewbox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg><span><?= lang('Admin/Kurikulum.btn_apply') ?></span> </button>

      <button onclick="showImportModal()" class="px-4 py-2.5 bg-white dark:bg-slate-800 border border-gray-300 dark:border-slate-600 text-gray-700 dark:text-slate-300 font-semibold rounded-xl hover:bg-gray-50 dark:hover:bg-slate-700 transition-colors flex items-center gap-2 cursor-pointer shadow-sm outline-none">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewbox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10" />
        </svg><span class="hidden md:inline"><?= lang('Admin/Kurikulum.btn_import') ?></span> </button>
    </div>
  </div>
</div>

<div class="primary-card border border-[<?= $color['warna_primary'] ?>]/30 dark:border-[<?= $color['warna_primary'] ?>]/50 bg-[<?= $color['warna_secondary'] ?>]/20 dark:bg-slate-800 rounded-3xl p-6 md:p-8 mb-6 shadow-sm transition-colors">
  <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-6">
    <div class="flex-1">
      <div class="flex items-center gap-3 mb-4">
        <div class="stat-icon w-12 h-12 rounded-xl bg-gradient-to-br from-[<?= $color['warna_primary'] ?>]/70 to-[<?= $color['warna_primary'] ?>] flex items-center justify-center shadow-lg">
          <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewbox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
          </svg>
        </div>
        <div>
          <p class="text-sm font-bold text-[<?= $color['warna_primary'] ?>] uppercase tracking-wide">Kurikulum Aktif Saat Ini</p>
          <h2 class="text-3xl md:text-4xl font-black text-gray-900 dark:text-white transition-colors">
            <?= $activeKurikulum ? esc($activeKurikulum['nama_kurikulum']) : 'Belum Ada Kurikulum Aktif' ?>
          </h2>
        </div>
      </div>
      <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
        <div class="bg-white dark:bg-slate-700/50 rounded-2xl p-4 border border-[<?= $color['warna_secondary'] ?>] dark:border-slate-600 shadow-sm transition-colors">
          <p class="text-xs font-semibold text-gray-500 dark:text-slate-400 uppercase tracking-wider mb-1"><?= lang('Admin/Kurikulum.using_level') ?></p>
          <div class="flex items-center gap-2">
            <p class="text-2xl font-black text-[<?= $color['warna_primary'] ?>]">3</p>
            <p class="text-sm text-gray-600 dark:text-slate-300 font-bold"><?= lang('Admin/Kurikulum.level') ?></p>
          </div>
          <p class="text-xs text-gray-400 dark:text-slate-500 mt-1">VII, VIII, IX</p>
        </div>
        <div class="bg-white dark:bg-slate-700/50 rounded-2xl p-4 border border-[<?= $color['warna_secondary'] ?>] dark:border-slate-600 shadow-sm transition-colors">
          <p class="text-xs font-semibold text-gray-500 dark:text-slate-400 uppercase tracking-wider mb-1"><?= lang('Admin/Kurikulum.total_subjects') ?></p>
          <div class="flex items-center gap-2">
            <p class="text-2xl font-black text-[<?= $color['warna_primary'] ?>]">16</p>
            <p class="text-sm text-gray-600 dark:text-slate-300 font-bold"><?= lang('Admin/Kurikulum.subject') ?></p>
          </div>
          <p class="text-xs text-gray-400 dark:text-slate-500 mt-1"><?= lang('Admin/Kurikulum.general_islamic') ?></p>
        </div>
        <div class="bg-white dark:bg-slate-700/50 rounded-2xl p-4 border border-[<?= $color['warna_secondary'] ?>] dark:border-slate-600 shadow-sm transition-colors">
          <p class="text-xs font-semibold text-gray-500 dark:text-slate-400 uppercase tracking-wider mb-1"><?= lang('Admin/Kurikulum.impl_status') ?></p>
          <span class="inline-block mt-1 px-3 py-1 bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400 text-[10px] font-bold rounded-full border border-emerald-200 dark:border-emerald-800/50"><?= lang('Admin/Kurikulum.status_active') ?></span>
          <p class="text-xs text-gray-400 dark:text-slate-500 mt-2"><?= lang('Admin/Kurikulum.running_normal') ?></p>
        </div>
      </div>
      <div class="bg-white/50 dark:bg-slate-700/30 border border-[<?= $color['warna_primary'] ?>]/40 dark:border-[<?= $color['warna_primary'] ?>]/20 rounded-xl p-4 flex items-start gap-3 transition-colors shadow-sm">
        <svg class="w-5 h-5 text-[<?= $color['warna_primary'] ?>] flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewbox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <div>
          <p class="text-sm font-bold text-[<?= $color['warna_primary'] ?>]"><?= lang('Admin/Kurikulum.important_note') ?></p>
          <p class="text-xs text-gray-600 dark:text-slate-300 mt-1 leading-relaxed"><?= lang('Admin/Kurikulum.important_desc') ?></p>
        </div>
      </div>
    </div>
    <div class="flex flex-col gap-3 lg:w-64">
      <button onclick="showStructureModal(1)" class="w-full px-5 py-3 bg-white dark:bg-slate-700 border-2 border-[<?= $color['warna_primary'] ?>]/60 dark:border-[<?= $color['warna_primary'] ?>]/50 text-[<?= $color['warna_primary'] ?>] dark:text-white font-bold rounded-xl hover:bg-[<?= $color['warna_secondary'] ?>]/20 dark:hover:bg-slate-600 transition-all shadow-sm flex items-center justify-center gap-2 outline-none">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewbox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
        </svg> <?= lang('Admin/Kurikulum.btn_view_struct') ?> </button>
      <button onclick="showImpactModal()" class="w-full px-5 py-3 bg-[<?= $color['warna_primary'] ?>] hover:bg-[<?= $color['warna_primary'] ?>]/90 text-white font-bold rounded-xl transition-all shadow-md transform hover:-translate-y-0.5 flex items-center justify-center gap-2 outline-none">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewbox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg> <?= lang('Admin/Kurikulum.btn_sys_impact') ?> </button>
      <a href="<?= base_url('assets/dokumen/Panduan_Kurikulum.pdf') ?>" target="_blank" download="Panduan_Kurikulum.pdf" class="w-full px-5 py-3 bg-white dark:bg-slate-700 border border-gray-300 dark:border-slate-600 text-gray-700 dark:text-slate-300 font-bold rounded-xl hover:bg-gray-50 dark:hover:bg-slate-600 transition-all shadow-sm flex items-center justify-center gap-2 outline-none cursor-pointer">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewbox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
        </svg> <?= lang('Admin/Kurikulum.btn_doc') ?> </a>
    </div>
  </div>
</div>

<div class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-gray-100 dark:border-slate-700 overflow-hidden mb-6 transition-colors">
  <div class="px-6 py-5 border-b border-gray-100 dark:border-slate-700 flex items-center justify-between transition-colors">
    <div>
      <h3 class="text-lg font-bold text-gray-900 dark:text-white transition-colors"><?= lang('Admin/Kurikulum.list_title') ?></h3>
      <p class="text-sm text-gray-500 dark:text-slate-400 mt-1 font-medium transition-colors"><?= lang('Admin/Kurikulum.list_desc') ?></p>
    </div>
    <span class="px-3 py-1 bg-[<?= $color['warna_secondary'] ?>]/50 dark:bg-slate-700 text-[<?= $color['warna_primary'] ?>] dark:text-emerald-400 font-bold text-xs rounded-lg border border-[<?= $color['warna_primary'] ?>]/20 shadow-sm transition-colors">
      <?= count($kurikulum) ?> <?= lang('Admin/Kurikulum.curr_count') ?>
    </span>
  </div>
  <div class="overflow-x-auto custom-scrollbar">
    <table class="w-full text-left border-collapse min-w-max">
      <thead class="bg-gray-50 dark:bg-slate-900/50 border-b border-gray-100 dark:border-slate-700 transition-colors">
        <tr class="text-[11px] text-gray-500 dark:text-slate-400 uppercase tracking-widest font-black">
          <th class="px-6 py-4"><?= lang('Admin/Kurikulum.th_name') ?></th>
          <th class="px-6 py-4"><?= lang('Admin/Kurikulum.th_type') ?></th>
          <th class="px-6 py-4 text-center"><?= lang('Admin/Kurikulum.th_year') ?></th>
          <th class="px-6 py-4 text-center"><?= lang('Admin/Kurikulum.th_status') ?></th>
          <th class="px-6 py-4 text-center"><?= lang('Admin/Kurikulum.th_used_in') ?></th>
          <th class="px-6 py-4 text-center"><?= lang('Admin/Kurikulum.th_action') ?></th>
        </tr>
      </thead>
      <tbody class="divide-y divide-gray-50 dark:divide-slate-700/50 transition-colors">
        <?php if (empty($kurikulum)): ?>
          <tr>
            <td colspan="6" class="px-6 py-16 text-center text-gray-400 dark:text-slate-500">
              <div class="flex flex-col items-center justify-center gap-3">
                <svg class="w-12 h-12 text-gray-200 dark:text-slate-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <span class="text-sm font-medium"><?= lang('Admin/Kurikulum.no_data') ?></span>
              </div>
            </td>
          </tr>
        <?php else: ?>
          <?php foreach ($kurikulum as $item): ?>
            <tr class="hover:bg-gray-50/50 dark:hover:bg-slate-700/30 transition-colors group">
              <td class="px-6 py-4">
                <span class="font-bold text-gray-800 dark:text-white text-sm block group-hover:text-[<?= $color['warna_primary'] ?>] transition-colors">
                  <?= esc($item['nama_kurikulum']) ?>
                </span>
              </td>

              <td class="px-6 py-4">
                <?php $badgeClass = ($item['jenis'] == 'Merdeka') ? 'bg-purple-50 dark:bg-purple-900/30 text-purple-700 dark:text-purple-400 border-purple-200 dark:border-purple-800/50' : 'bg-blue-50 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400 border-blue-200 dark:border-blue-800/50'; ?>
                <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider border <?= $badgeClass ?>">
                  <?= esc($item['jenis']) ?>
                </span>
              </td>

              <td class="px-6 py-4 text-center">
                <span class="font-mono text-xs font-bold text-gray-600 dark:text-slate-300 bg-gray-100 dark:bg-slate-700 px-2 py-1 rounded border border-gray-200 dark:border-slate-600 transition-colors">
                  <?= esc($item['tahun_berlaku']) ?>
                </span>
              </td>

              <td class="px-6 py-4 text-center">
                <?php if ($item['status'] == 'Aktif'): ?>
                  <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest bg-emerald-50 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800/50 shadow-sm">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse shadow-[0_0_4px_#10b981]"></span>
                    Aktif
                  </span>
                <?php else: ?>
                  <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-widest bg-gray-100 dark:bg-slate-700 text-gray-500 dark:text-slate-400 border border-gray-200 dark:border-slate-600 transition-colors">
                    Non-aktif
                  </span>
                <?php endif; ?>
              </td>

              <td class="px-6 py-4 text-center">
                <div class="flex items-center justify-center -space-x-1.5 hover:space-x-1 transition-all">
                  <div class="w-7 h-7 rounded bg-white dark:bg-slate-700 border border-gray-200 dark:border-slate-600 flex items-center justify-center text-[10px] font-black text-gray-500 dark:text-slate-300 shadow-sm transition-colors" title="Kelas 7">7</div>
                  <div class="w-7 h-7 rounded bg-white dark:bg-slate-700 border border-gray-200 dark:border-slate-600 flex items-center justify-center text-[10px] font-black text-gray-500 dark:text-slate-300 shadow-sm transition-colors" title="Kelas 8">8</div>
                  <div class="w-7 h-7 rounded bg-white dark:bg-slate-700 border border-gray-200 dark:border-slate-600 flex items-center justify-center text-[10px] font-black text-gray-500 dark:text-slate-300 shadow-sm transition-colors" title="Kelas 9">9</div>
                </div>
              </td>

              <td class="px-6 py-4 text-center">
                <div class="flex items-center justify-center gap-2 opacity-1 lg:opacity-0 group-hover:opacity-100 transition-opacity">
                  <button onclick="showStructureModal(<?= $item['id'] ?>)" class="p-2 text-blue-600 dark:text-blue-400 hover:bg-blue-50 dark:hover:bg-blue-900/30 rounded-lg transition-colors shadow-sm outline-none" title="<?= lang('Admin/Kurikulum.tt_structure') ?>">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                  </button>
                  <button onclick="showEditModal(<?= $item['id'] ?>)" class="p-2 text-amber-600 dark:text-amber-400 hover:bg-amber-50 dark:hover:bg-amber-900/30 rounded-lg transition-colors shadow-sm outline-none" title="<?= lang('Admin/Kurikulum.tt_edit') ?>">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                    </svg>
                  </button>

                  <?php if ($item['status'] == 'Non-aktif'): ?>
                    <button onclick="activateCurriculum(<?= $item['id'] ?>, '<?= esc($item['nama_kurikulum']) ?>')" class="p-2 text-emerald-600 dark:text-emerald-400 hover:bg-emerald-50 dark:hover:bg-emerald-900/30 rounded-lg transition-colors shadow-sm outline-none" title="Aktifkan Kurikulum">
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                      </svg>
                    </button>
                    <button onclick="deleteCurriculum(<?= $item['id'] ?>, '<?= esc($item['nama_kurikulum']) ?>')" class="p-2 text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/30 rounded-lg transition-colors shadow-sm outline-none" title="Hapus Permanen">
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                      </svg>
                    </button>
                  <?php else: ?>
                    <button onclick="deactivateCurriculum(<?= $item['id'] ?>, '<?= esc($item['nama_kurikulum']) ?>')" class="p-2 text-amber-600 dark:text-amber-400 hover:bg-amber-50 dark:hover:bg-amber-900/30 rounded-lg transition-colors shadow-sm outline-none" title="Nonaktifkan Kurikulum">
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                      </svg>
                    </button>
                    <button disabled class="p-2 text-gray-300 dark:text-gray-600 rounded-lg outline-none cursor-not-allowed" title="Kurikulum yang Aktif tidak dapat dihapus">
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                      </svg>
                    </button>
                  <?php endif; ?>
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

<div id="editCurriculumModal" class="fixed inset-0 hidden" style="z-index: 99999;">
  <div class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity pointer-events-auto" onclick="closeEditModal()"></div>
  <div class="absolute inset-0 flex items-center justify-center p-4 pointer-events-none md:pl-64">
    <div class="relative w-full bg-white dark:bg-slate-800 rounded-3xl shadow-2xl flex flex-col max-h-[90vh] pointer-events-auto border border-transparent dark:border-slate-700 transition-colors duration-300" style="max-width: 600px;">
      <div class="p-6 border-b border-gray-100 dark:border-slate-700 flex items-center justify-between bg-white dark:bg-slate-800 rounded-t-3xl z-20 flex-shrink-0 transition-colors">
        <div>
          <h3 class="text-xl font-bold text-gray-800 dark:text-white"><?= lang('Admin/Kurikulum.edit_title') ?></h3>
          <p class="text-sm text-gray-500 dark:text-slate-400 mt-1 font-medium"><?= lang('Admin/Kurikulum.edit_desc') ?></p>
        </div>
        <button onclick="closeEditModal()" class="p-2 hover:bg-gray-100 dark:hover:bg-slate-700 rounded-full transition-colors cursor-pointer relative z-50 text-gray-500 dark:text-slate-400 outline-none">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
      </div>
      <div class="flex-1 overflow-y-auto p-6 relative z-10 custom-scrollbar">
        <form class="space-y-5" onsubmit="handleEditCurriculum(event)">
          <input type="hidden" id="edit_curriculum_id" name="edit_curriculum_id">
          <div>
            <label for="edit_curriculum_name" class="block text-sm font-bold text-gray-700 dark:text-slate-300 mb-2"><?= lang('Admin/Kurikulum.lbl_name') ?> <span class="text-red-500">*</span></label>
            <input type="text" name="edit_curriculum_name" id="edit_curriculum_name" required placeholder="<?= lang('Admin/Kurikulum.ph_name') ?>" class="w-full px-4 py-3 bg-white dark:bg-slate-700 border-2 border-gray-200 dark:border-slate-600 rounded-xl text-sm text-gray-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-[<?= $color['warna_primary'] ?>] transition-all outline-none shadow-sm placeholder-gray-400 dark:placeholder-slate-400">
          </div>
          <div>
            <label for="edit_curriculum_type" class="block text-sm font-bold text-gray-700 dark:text-slate-300 mb-2"><?= lang('Admin/Kurikulum.lbl_type') ?> <span class="text-red-500">*</span></label>
            <select name="edit_curriculum_type" id="edit_curriculum_type" required class="w-full px-4 py-3 bg-white dark:bg-slate-700 border-2 border-gray-200 dark:border-slate-600 rounded-xl text-sm text-gray-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-[<?= $color['warna_primary'] ?>] transition-all appearance-none cursor-pointer outline-none shadow-sm">
              <option value="">Pilih Jenis Kurikulum</option>
              <option value="K13">Kurikulum 2013 (K13)</option>
              <option value="Merdeka">Kurikulum Merdeka</option>
              <option value="Lainnya">Lainnya</option>
            </select>
          </div>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label for="edit_year_start" class="block text-sm font-bold text-gray-700 dark:text-slate-300 mb-2"><?= lang('Admin/Kurikulum.lbl_year_start') ?> <span class="text-red-500">*</span></label>
              <input type="number" name="edit_year_start" id="edit_year_start" required placeholder="2024" min="2000" max="2100" class="w-full px-4 py-3 bg-white dark:bg-slate-700 border-2 border-gray-200 dark:border-slate-600 rounded-xl text-sm text-gray-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-[<?= $color['warna_primary'] ?>] outline-none shadow-sm">
            </div>
            <div>
              <label for="edit_year_end" class="block text-sm font-bold text-gray-700 dark:text-slate-300 mb-2"><?= lang('Admin/Kurikulum.lbl_year_end') ?></label>
              <input type="number" name="edit_year_end" id="edit_year_end" placeholder="<?= lang('Admin/Kurikulum.ph_year_end') ?>" min="2000" max="2100" class="w-full px-4 py-3 bg-white dark:bg-slate-700 border-2 border-gray-200 dark:border-slate-600 rounded-xl text-sm text-gray-800 dark:text-white placeholder-gray-400 dark:placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[<?= $color['warna_primary'] ?>] outline-none shadow-sm">
            </div>
          </div>
          <div>
            <label for="edit_description" class="block text-sm font-bold text-gray-700 dark:text-slate-300 mb-2"><?= lang('Admin/Kurikulum.lbl_desc') ?></label>
            <textarea name="edit_description" id="edit_description" rows="3" placeholder="<?= lang('Admin/Kurikulum.ph_desc') ?>" class="w-full px-4 py-3 bg-white dark:bg-slate-700 border-2 border-gray-200 dark:border-slate-600 rounded-xl text-sm text-gray-800 dark:text-white placeholder-gray-400 dark:placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[<?= $color['warna_primary'] ?>] resize-none transition-all shadow-sm outline-none"></textarea>
          </div>
          <div class="bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800/50 rounded-xl p-4 flex items-start gap-3 shadow-sm transition-colors">
            <svg class="w-5 h-5 text-amber-600 dark:text-amber-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewbox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
            <div>
              <p class="text-sm font-bold text-amber-900 dark:text-amber-400"><?= lang('Admin/Kurikulum.warn_title') ?></p>
              <p class="text-xs text-amber-800 dark:text-amber-300 mt-1 font-medium leading-relaxed"><?= lang('Admin/Kurikulum.warn_edit_desc') ?></p>
            </div>
          </div>
          <div class="flex gap-3 pt-5 border-t border-gray-100 dark:border-slate-700 sticky bottom-0 bg-white dark:bg-slate-800 z-50 pb-2 transition-colors">
            <button type="button" onclick="closeEditModal()" class="flex-1 px-6 py-3 bg-white dark:bg-slate-700 border-2 border-gray-300 dark:border-slate-600 text-gray-700 dark:text-slate-300 font-bold rounded-xl hover:bg-gray-50 dark:hover:bg-slate-600 transition-colors shadow-sm outline-none"> <?= lang('Admin/Kurikulum.btn_cancel') ?> </button>
            <button type="submit" class="flex-1 px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl transition-transform transform hover:-translate-y-0.5 shadow-lg shadow-emerald-600/30 outline-none"> <?= lang('Admin/Kurikulum.btn_save_changes') ?> </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<div id="addCurriculumModal" class="fixed inset-0 hidden" style="z-index: 99999;">
  <div class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity pointer-events-auto" onclick="closeAddCurriculumModal()"></div>
  <div class="absolute inset-0 flex items-center justify-center p-4 pointer-events-none md:pl-64">
    <div class="relative w-full bg-white dark:bg-slate-800 rounded-3xl shadow-2xl flex flex-col max-h-[90vh] pointer-events-auto border border-transparent dark:border-slate-700 transition-colors duration-300" style="max-width: 600px;">
      <div class="p-6 border-b border-gray-100 dark:border-slate-700 flex items-center justify-between bg-white dark:bg-slate-800 rounded-t-3xl z-20 flex-shrink-0 transition-colors">
        <div>
          <h3 class="text-xl font-bold text-gray-800 dark:text-white"><?= lang('Admin/Kurikulum.add_title') ?></h3>
          <p class="text-sm text-gray-500 dark:text-slate-400 mt-1 font-medium"><?= lang('Admin/Kurikulum.add_desc') ?></p>
        </div>
        <button onclick="closeAddCurriculumModal()" class="p-2 hover:bg-gray-100 dark:hover:bg-slate-700 rounded-full transition-colors cursor-pointer relative z-50 text-gray-500 dark:text-slate-400 outline-none">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewbox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
      </div>
      <div class="flex-1 overflow-y-auto p-6 relative z-10 custom-scrollbar">
        <form class="space-y-5" onsubmit="handleAddCurriculum(event)">
          <div>
            <label for="curriculum_name" class="block text-sm font-bold text-gray-700 dark:text-slate-300 mb-2"><?= lang('Admin/Kurikulum.lbl_name') ?> <span class="text-red-500">*</span></label>
            <input type="text" name="curriculum_name" id="curriculum_name" required placeholder="<?= lang('Admin/Kurikulum.ph_name') ?>" class="w-full px-4 py-3 bg-white dark:bg-slate-700 border-2 border-gray-200 dark:border-slate-600 rounded-xl text-sm text-gray-800 dark:text-white placeholder-gray-400 dark:placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[<?= $color['warna_primary'] ?>] transition-all outline-none shadow-sm">
          </div>
          <div>
            <label for="curriculum_type" class="block text-sm font-bold text-gray-700 dark:text-slate-300 mb-2"><?= lang('Admin/Kurikulum.lbl_type') ?> <span class="text-red-500">*</span></label>
            <select name="curriculum_type" id="curriculum_type" required class="w-full px-4 py-3 bg-white dark:bg-slate-700 border-2 border-gray-200 dark:border-slate-600 rounded-xl text-sm text-gray-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-[<?= $color['warna_primary'] ?>] transition-all appearance-none cursor-pointer outline-none shadow-sm">
              <option value="">Pilih Jenis Kurikulum</option>
              <option value="K13">Kurikulum 2013 (K13)</option>
              <option value="Merdeka">Kurikulum Merdeka</option>
              <option value="Lainnya">Lainnya</option>
            </select>
          </div>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label for="year_start" class="block text-sm font-bold text-gray-700 dark:text-slate-300 mb-2"><?= lang('Admin/Kurikulum.lbl_year_start') ?> <span class="text-red-500">*</span></label>
              <input type="number" name="year_start" id="year_start" required placeholder="2024" min="2000" max="2100" class="w-full px-4 py-3 bg-white dark:bg-slate-700 border-2 border-gray-200 dark:border-slate-600 rounded-xl text-sm text-gray-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-[<?= $color['warna_primary'] ?>] transition-all outline-none shadow-sm">
            </div>
            <div>
              <label for="year_end" class="block text-sm font-bold text-gray-700 dark:text-slate-300 mb-2"><?= lang('Admin/Kurikulum.lbl_year_end') ?></label>
              <input type="number" name="year_end" id="year_end" placeholder="<?= lang('Admin/Kurikulum.ph_year_end') ?>" min="2000" max="2100" class="w-full px-4 py-3 bg-white dark:bg-slate-700 border-2 border-gray-200 dark:border-slate-600 rounded-xl text-sm text-gray-800 dark:text-white placeholder-gray-400 dark:placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[<?= $color['warna_primary'] ?>] transition-all outline-none shadow-sm">
            </div>
          </div>
          <div>
            <label for="description" class="block text-sm font-bold text-gray-700 dark:text-slate-300 mb-2"><?= lang('Admin/Kurikulum.lbl_desc') ?></label>
            <textarea name="description" id="description" rows="3" placeholder="<?= lang('Admin/Kurikulum.ph_desc') ?>" class="w-full px-4 py-3 bg-white dark:bg-slate-700 border-2 border-gray-200 dark:border-slate-600 rounded-xl text-sm text-gray-800 dark:text-white placeholder-gray-400 dark:placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[<?= $color['warna_primary'] ?>] resize-none transition-all shadow-sm outline-none"></textarea>
          </div>
          <div class="bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800/50 rounded-xl p-4 flex items-start gap-3 shadow-sm transition-colors">
            <svg class="w-5 h-5 text-amber-600 dark:text-amber-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewbox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
            <div>
              <p class="text-sm font-bold text-amber-900 dark:text-amber-400"><?= lang('Admin/Kurikulum.warn_title') ?></p>
              <p class="text-xs text-amber-800 dark:text-amber-300 mt-1 font-medium leading-relaxed"><?= lang('Admin/Kurikulum.warn_add_desc') ?></p>
            </div>
          </div>
          <div class="flex gap-3 pt-5 border-t border-gray-100 dark:border-slate-700 sticky bottom-0 bg-white dark:bg-slate-800 z-50 pb-2 transition-colors">
            <button type="button" onclick="closeAddCurriculumModal()" class="flex-1 px-6 py-3 bg-white dark:bg-slate-700 border-2 border-gray-300 dark:border-slate-600 text-gray-700 dark:text-slate-300 font-bold rounded-xl hover:bg-gray-50 dark:hover:bg-slate-600 transition-colors shadow-sm outline-none"> <?= lang('Admin/Kurikulum.btn_cancel') ?> </button>
            <button type="submit" class="flex-1 px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl transition-transform transform hover:-translate-y-0.5 shadow-lg shadow-emerald-600/30 cursor-pointer outline-none"> <?= lang('Admin/Kurikulum.btn_save_add') ?> </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<div id="applyModal" class="fixed inset-0 hidden" style="z-index: 99999;">
  <div class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity pointer-events-auto" onclick="closeApplyModal()"></div>
  <div class="absolute inset-0 flex items-center justify-center p-4 pointer-events-none md:pl-64">
    <div class="relative w-full bg-white dark:bg-slate-800 rounded-3xl shadow-2xl flex flex-col max-h-[90vh] pointer-events-auto border border-transparent dark:border-slate-700 transition-colors duration-300" style="max-width: 600px;">
      <div class="p-6 border-b border-gray-100 dark:border-slate-700 flex items-center justify-between bg-white dark:bg-slate-800 rounded-t-3xl z-20 flex-shrink-0 transition-colors">
        <div>
          <h3 class="text-xl font-bold text-gray-800 dark:text-white"><?= lang('Admin/Kurikulum.apply_title') ?></h3>
          <p class="text-sm text-gray-500 dark:text-slate-400 mt-1 font-medium"><?= lang('Admin/Kurikulum.apply_desc') ?></p>
        </div>
        <button onclick="closeApplyModal()" class="p-2 hover:bg-gray-100 dark:hover:bg-slate-700 rounded-full transition-colors cursor-pointer relative z-50 text-gray-500 dark:text-slate-400 outline-none">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewbox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
      </div>
      <div class="flex-1 overflow-y-auto p-6 relative z-10 custom-scrollbar">
        <form class="space-y-5" onsubmit="handleApplyCurriculum(event)">
          <div>
            <label for="apply_curriculum" class="block text-sm font-bold text-gray-700 dark:text-slate-300 mb-2"><?= lang('Admin/Kurikulum.lbl_select_curr') ?> <span class="text-red-500">*</span></label>
            <select name="apply_curriculum" id="apply_curriculum" required class="w-full px-4 py-3 bg-white dark:bg-slate-700 border-2 border-gray-200 dark:border-slate-600 rounded-xl text-sm text-gray-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-[<?= $color['warna_primary'] ?>] transition-all shadow-sm appearance-none cursor-pointer outline-none">
              <option value=""><?= lang('Admin/Kurikulum.lbl_select_curr') ?></option>
              <option value="1">Kurikulum Merdeka</option>
              <option value="2">Kurikulum 2013 (K13)</option>
            </select>
          </div>
          <div>
            <label class="block text-sm font-bold text-gray-700 dark:text-slate-300 mb-3"><?= lang('Admin/Kurikulum.lbl_select_level') ?> <span class="text-red-500">*</span></label>
            <div class="space-y-2">
              <label class="flex items-center gap-3 p-3 border-2 border-gray-200 dark:border-slate-600 bg-white dark:bg-slate-700 rounded-xl hover:border-[<?= $color['warna_primary'] ?>] cursor-pointer transition-colors shadow-sm group">
                <input type="checkbox" name="level" value="7" class="rounded border-gray-300 text-[<?= $color['warna_primary'] ?>] focus:ring-[<?= $color['warna_primary'] ?>] cursor-pointer">
                <span class="text-sm font-bold text-gray-700 dark:text-slate-300 group-hover:text-[<?= $color['warna_primary'] ?>] transition-colors"><?= lang('Admin/Kurikulum.level_7') ?></span>
              </label>
              <label class="flex items-center gap-3 p-3 border-2 border-gray-200 dark:border-slate-600 bg-white dark:bg-slate-700 rounded-xl hover:border-[<?= $color['warna_primary'] ?>] cursor-pointer transition-colors shadow-sm group">
                <input type="checkbox" name="level" value="8" class="rounded border-gray-300 text-[<?= $color['warna_primary'] ?>] focus:ring-[<?= $color['warna_primary'] ?>] cursor-pointer">
                <span class="text-sm font-bold text-gray-700 dark:text-slate-300 group-hover:text-[<?= $color['warna_primary'] ?>] transition-colors"><?= lang('Admin/Kurikulum.level_8') ?></span>
              </label>
              <label class="flex items-center gap-3 p-3 border-2 border-gray-200 dark:border-slate-600 bg-white dark:bg-slate-700 rounded-xl hover:border-[<?= $color['warna_primary'] ?>] cursor-pointer transition-colors shadow-sm group">
                <input type="checkbox" name="level" value="9" class="rounded border-gray-300 text-[<?= $color['warna_primary'] ?>] focus:ring-[<?= $color['warna_primary'] ?>] cursor-pointer">
                <span class="text-sm font-bold text-gray-700 dark:text-slate-300 group-hover:text-[<?= $color['warna_primary'] ?>] transition-colors"><?= lang('Admin/Kurikulum.level_9') ?></span>
              </label>
            </div>
          </div>
          <div>
            <label for="apply_year" class="block text-sm font-bold text-gray-700 dark:text-slate-300 mb-2"><?= lang('Admin/Kurikulum.lbl_apply_year') ?> <span class="text-red-500">*</span></label>
            <select name="apply_year" id="apply_year" required class="w-full px-4 py-3 bg-white dark:bg-slate-700 border-2 border-gray-200 dark:border-slate-600 rounded-xl text-sm text-gray-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-[<?= $color['warna_primary'] ?>] transition-all shadow-sm appearance-none cursor-pointer outline-none">
              <option value=""><?= lang('Admin/Kurikulum.ph_apply_year') ?></option>
              <option value="2024">2024 / 2025</option>
              <option value="2025">2025 / 2026</option>
            </select>
          </div>
          <div class="bg-gray-50 dark:bg-slate-900/50 border border-gray-200 dark:border-slate-700 rounded-xl p-5 shadow-sm transition-colors">
            <p class="text-sm font-bold text-gray-800 dark:text-white mb-3"><?= lang('Admin/Kurikulum.apply_options') ?></p>
            <label class="flex items-start gap-3 cursor-pointer mb-3 group">
              <input type="radio" name="apply_option" value="default" checked class="mt-1 text-[<?= $color['warna_primary'] ?>] focus:ring-[<?= $color['warna_primary'] ?>] cursor-pointer">
              <div>
                <p class="text-sm font-bold text-gray-800 dark:text-slate-200 group-hover:text-[<?= $color['warna_primary'] ?>] transition-colors"><?= lang('Admin/Kurikulum.opt_default') ?></p>
                <p class="text-xs text-gray-600 dark:text-slate-400 font-medium"><?= lang('Admin/Kurikulum.opt_def_desc') ?></p>
              </div>
            </label>
            <label class="flex items-start gap-3 cursor-pointer group">
              <input type="radio" name="apply_option" value="custom" class="mt-1 text-[<?= $color['warna_primary'] ?>] focus:ring-[<?= $color['warna_primary'] ?>] cursor-pointer">
              <div>
                <p class="text-sm font-bold text-gray-800 dark:text-slate-200 group-hover:text-[<?= $color['warna_primary'] ?>] transition-colors"><?= lang('Admin/Kurikulum.opt_custom') ?></p>
                <p class="text-xs text-gray-600 dark:text-slate-400 font-medium"><?= lang('Admin/Kurikulum.opt_cust_desc') ?></p>
              </div>
            </label>
          </div>
          <div class="bg-red-50 dark:bg-red-900/20 border-2 border-red-200 dark:border-red-800/50 rounded-xl p-5 flex items-start gap-3 shadow-sm transition-colors">
            <svg class="w-6 h-6 text-red-600 dark:text-red-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewbox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
            <div>
              <p class="text-sm font-bold text-red-900 dark:text-red-400 mb-2"><?= lang('Admin/Kurikulum.impact_title') ?></p>
              <ul class="text-xs text-red-800 dark:text-red-300 space-y-1 font-medium">
                <li><?= lang('Admin/Kurikulum.impact_1') ?></li>
                <li><?= lang('Admin/Kurikulum.impact_2') ?></li>
              </ul>
            </div>
          </div>
          <div class="bg-gray-50 dark:bg-slate-900/50 border-2 border-gray-200 dark:border-slate-700 rounded-xl p-4 transition-colors">
            <label class="flex items-start gap-3 cursor-pointer group">
              <input type="checkbox" id="confirmApply" class="rounded border-gray-300 text-[<?= $color['warna_primary'] ?>] focus:ring-[<?= $color['warna_primary'] ?>] cursor-pointer mt-1">
              <span class="text-sm text-gray-800 dark:text-slate-300 font-medium group-hover:text-gray-900 dark:group-hover:text-white transition-colors"><?= lang('Admin/Kurikulum.apply_agree') ?></span>
            </label>
          </div>
          <div class="flex gap-3 pt-5 border-t border-gray-100 dark:border-slate-700 sticky bottom-0 bg-white dark:bg-slate-800 z-50 pb-2 transition-colors">
            <button type="button" onclick="closeApplyModal()" class="flex-1 px-6 py-3 bg-white dark:bg-slate-700 border-2 border-gray-300 dark:border-slate-600 text-gray-700 dark:text-slate-300 font-bold rounded-xl hover:bg-gray-50 dark:hover:bg-slate-600 transition-colors shadow-sm outline-none cursor-pointer"> <?= lang('Admin/Kurikulum.btn_cancel') ?> </button>
            <button type="submit" class="flex-1 px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl transition-transform transform hover:-translate-y-0.5 shadow-lg shadow-emerald-600/30 outline-none cursor-pointer"> <?= lang('Admin/Kurikulum.btn_apply_now') ?> </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<div id="structureModal" class="fixed inset-0 hidden" style="z-index: 99999;">
  <div class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity pointer-events-auto" onclick="closeStructureModal()"></div>
  <div class="absolute inset-0 flex items-center justify-center p-4 pointer-events-none md:pl-64">
    <div class="relative w-full bg-white dark:bg-slate-800 rounded-3xl shadow-2xl flex flex-col max-h-[90vh] pointer-events-auto border border-transparent dark:border-slate-700 transition-colors duration-300" style="max-width: 900px;">
      <div class="p-6 border-b border-gray-100 dark:border-slate-700 flex items-center justify-between bg-white dark:bg-slate-800 rounded-t-3xl z-20 flex-shrink-0 transition-colors">
        <div>
          <h3 class="text-xl font-bold text-gray-800 dark:text-white"><?= lang('Admin/Kurikulum.struct_title') ?> <span id="structureCurriculumName" class="text-[<?= $color['warna_primary'] ?>]">...</span></h3>
          <p class="text-sm text-gray-500 dark:text-slate-400 mt-1 font-medium"><?= lang('Admin/Kurikulum.struct_desc') ?></p>
        </div>
        <button onclick="closeStructureModal()" class="p-2 hover:bg-gray-100 dark:hover:bg-slate-700 rounded-full transition-colors cursor-pointer relative z-50 text-gray-500 dark:text-slate-400 outline-none">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewbox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
      </div>
      <div class="flex-1 overflow-y-auto p-6 relative z-10 space-y-4 custom-scrollbar">
        <div class="border border-gray-200 dark:border-slate-600 rounded-xl overflow-hidden bg-white dark:bg-slate-700 shadow-sm transition-colors">
          <button onclick="toggleAccordion(this)" class="w-full flex items-center justify-between p-5 bg-gradient-to-r from-emerald-50 to-white dark:from-emerald-900/20 dark:to-slate-700 hover:from-emerald-100 dark:hover:from-emerald-900/40 transition-colors cursor-pointer outline-none">
            <div class="flex items-center gap-4">
              <div class="w-12 h-12 rounded-xl bg-emerald-600 shadow flex items-center justify-center"><span class="text-white font-black text-xl">VII</span></div>
              <div class="text-left">
                <p class="font-bold text-gray-900 dark:text-white text-lg"><?= lang('Admin/Kurikulum.struct_level_7') ?></p>
                <p class="text-sm text-gray-600 dark:text-slate-400 font-medium"><?= lang('Admin/Kurikulum.struct_level_desc') ?></p>
              </div>
            </div>
            <svg class="menu-arrow w-6 h-6 text-gray-500 dark:text-slate-400 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
            </svg>
          </button>
          <div class="accordion-panel hidden">
            <div class="p-5 bg-white dark:bg-slate-800 border-t border-gray-100 dark:border-slate-700 transition-colors">
              <p class="text-sm text-gray-600 dark:text-slate-400"><?= lang('Admin/Kurikulum.struct_detail_msg') ?></p>
            </div>
          </div>
        </div>
      </div>
      <div class="p-6 border-t border-gray-100 dark:border-slate-700 sticky bottom-0 bg-white dark:bg-slate-800 z-50 rounded-b-3xl transition-colors">
        <button onclick="closeStructureModal()" class="w-full px-6 py-3 bg-gray-100 dark:bg-slate-700 hover:bg-gray-200 dark:hover:bg-slate-600 text-gray-800 dark:text-white font-bold rounded-xl transition-colors shadow-sm cursor-pointer outline-none"> <?= lang('Admin/Kurikulum.btn_close_panel') ?> </button>
      </div>
    </div>
  </div>
</div>

<div id="impactModal" class="fixed inset-0 hidden" style="z-index: 99999;">
  <div class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity pointer-events-auto" onclick="closeImpactModal()"></div>
  <div class="absolute inset-0 flex items-center justify-center p-4 pointer-events-none md:pl-64">
    <div class="relative w-full bg-white dark:bg-slate-800 rounded-3xl shadow-2xl flex flex-col max-h-[90vh] pointer-events-auto border border-transparent dark:border-slate-700 transition-colors duration-300" style="max-width: 800px;">
      <div class="p-6 border-b border-gray-100 dark:border-slate-700 flex items-center justify-between bg-white dark:bg-slate-800 rounded-t-3xl z-20 flex-shrink-0 transition-colors">
        <div>
          <h3 class="text-xl font-bold text-gray-800 dark:text-white"><?= lang('Admin/Kurikulum.sys_impact_title') ?></h3>
          <p class="text-sm text-gray-500 dark:text-slate-400 mt-1 font-medium"><?= lang('Admin/Kurikulum.sys_impact_desc') ?></p>
        </div>
        <button onclick="closeImpactModal()" class="p-2 hover:bg-gray-100 dark:hover:bg-slate-700 rounded-full transition-colors cursor-pointer relative z-50 text-gray-500 dark:text-slate-400 outline-none">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewbox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
      </div>
      <div class="flex-1 overflow-y-auto p-6 relative z-10 space-y-5 custom-scrollbar">
        <div class="bg-red-50 dark:bg-red-900/20 border-2 border-red-200 dark:border-red-800/50 rounded-2xl p-6 shadow-sm transition-colors">
          <div class="flex items-start gap-4">
            <div class="w-12 h-12 bg-white dark:bg-slate-800 border border-transparent dark:border-red-800/50 rounded-full flex items-center justify-center shadow flex-shrink-0">
              <svg class="w-7 h-7 text-red-600 dark:text-red-500" fill="none" stroke="currentColor" viewbox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
              </svg>
            </div>
            <div>
              <p class="text-base font-bold text-red-900 dark:text-red-400 mb-1"><?= lang('Admin/Kurikulum.warn_important') ?></p>
              <p class="text-sm text-red-800 dark:text-red-300 font-medium leading-relaxed"><?= lang('Admin/Kurikulum.warn_imp_desc') ?></p>
            </div>
          </div>
        </div>
      </div>
      <div class="p-6 border-t border-gray-100 dark:border-slate-700 sticky bottom-0 bg-white dark:bg-slate-800 z-50 rounded-b-3xl transition-colors">
        <button onclick="closeImpactModal()" class="w-full px-6 py-3.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl transition-transform transform hover:-translate-y-0.5 shadow-lg shadow-emerald-600/30 cursor-pointer outline-none"> <?= lang('Admin/Kurikulum.btn_understand') ?> </button>
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
          <h3 class="text-xl font-bold text-gray-800 dark:text-white"><?= lang('Admin/Kurikulum.import_title') ?></h3>
          <p class="text-sm font-medium text-gray-500 dark:text-slate-400 mt-1"><?= lang('Admin/Kurikulum.import_desc') ?></p>
        </div>
        <button type="button" onclick="closeImportModal()" class="p-2 hover:bg-gray-100 dark:hover:bg-slate-700 rounded-full transition-colors cursor-pointer text-gray-500 dark:text-slate-400 outline-none">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
      </div>
      <div class="p-6">
        <form id="importForm" action="<?= base_url('admin/kurikulum/import') ?>" method="POST" onsubmit="handleImportSubmit(event)" enctype="multipart/form-data">
          <?= csrf_field() ?>
          <div class="mb-5">
            <p class="text-sm font-bold text-gray-700 dark:text-slate-300 mb-2"><?= lang('Admin/Kurikulum.step_1') ?></p>
            <a href="<?= base_url('admin/kurikulum/template') ?>" class="inline-flex items-center gap-2 px-4 py-2 font-bold rounded-lg transition-all text-sm shadow-sm hover:opacity-80" style="color: <?= $color['warna_primary'] ?>; background-color: <?= $color['warna_primary'] ?>1A;">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
              </svg>
              <?= lang('Admin/Kurikulum.dl_template') ?>
            </a>
          </div>
          <div class="mb-6">
            <p class="text-sm font-bold text-gray-700 dark:text-slate-300 mb-2"><?= lang('Admin/Kurikulum.step_2') ?></p>
            <input type="file" name="file_excel" accept=".xlsx, .xls" required class="block w-full text-sm text-gray-500 dark:text-slate-400 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-gray-100 dark:file:bg-slate-700 file:text-gray-700 dark:file:text-slate-200 hover:file:bg-gray-200 dark:hover:file:bg-slate-600 border dark:border-slate-600 cursor-pointer outline-none focus:ring-2 focus:ring-[<?= $color['warna_primary'] ?>] transition-colors">
          </div>
          <div class="flex justify-end gap-3 pt-4 border-t border-gray-100 dark:border-slate-700 transition-colors">
            <button type="button" onclick="closeImportModal()" class="px-5 py-2.5 bg-white dark:bg-slate-700 border border-gray-300 dark:border-slate-600 text-gray-700 dark:text-slate-300 font-bold rounded-xl hover:bg-gray-50 dark:hover:bg-slate-600 transition-colors shadow-sm outline-none cursor-pointer"><?= lang('Admin/Kurikulum.btn_cancel') ?></button>
            <button type="submit" class="px-5 py-2.5 text-white font-bold rounded-xl shadow-lg transition-transform transform hover:-translate-y-0.5 flex items-center gap-2 outline-none cursor-pointer" style="background-color: <?= $color['warna_primary'] ?>; box-shadow: 0 10px 15px -3px <?= $color['warna_primary'] ?>40;">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
              </svg> <?= lang('Admin/Kurikulum.btn_upload') ?>
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
  const curriculumData = <?= json_encode($kurikulum) ?>;
  const BASE_URL = "<?= base_url() ?>";

  window.LANG = {
    js_loading: "<?= lang('Admin/Kurikulum.js_loading') ?: 'Memproses...' ?>",
    js_err_data_not_found: "<?= lang('Admin/Kurikulum.js_err_data_not_found') ?: 'Data kurikulum tidak ditemukan.' ?>",
    js_succ_edit: "<?= lang('Admin/Kurikulum.js_succ_edit') ?: 'Kurikulum berhasil diperbarui!' ?>",
    js_succ_add: "<?= lang('Admin/Kurikulum.js_succ_add') ?: 'Kurikulum baru berhasil ditambahkan!' ?>",
    js_warn_check: "<?= lang('Admin/Kurikulum.js_warn_check') ?: 'Harap centang konfirmasi terlebih dahulu' ?>",
    js_succ_apply: "<?= lang('Admin/Kurikulum.js_succ_apply') ?: 'Kurikulum berhasil diterapkan!' ?>",
    js_succ_activate: "<?= lang('Admin/Kurikulum.js_succ_activate') ?: 'Kurikulum berhasil diaktifkan!' ?>",
    js_succ_archive: "<?= lang('Admin/Kurikulum.js_succ_archive') ?: 'Kurikulum berhasil diarsipkan!' ?>",
    js_notification: "<?= lang('Admin/Kurikulum.js_notification') ?: 'Notifikasi' ?>",
    js_err_fatal: "<?= lang('Admin/Kurikulum.js_err_fatal') ?: 'Terjadi kesalahan fatal pada server.' ?>",
    js_err_conn: "<?= lang('Admin/Kurikulum.js_err_conn') ?: 'Koneksi ke server terputus.' ?>"
  };
</script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="<?= base_url('assets/js/Admin/kurikulum.js') ?>?v=<?= time() ?>"></script>
<?= $this->endSection() ?>