<?= $this->extend('layout/main') ?>

<?= $this->section('title') ?>
<?= lang('Admin/Siswa.page_title_browser') ?>
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
    :root {
        --warna-scroll: <?= $color['warna_primary'] ?>;
    }
</style>
<link rel="stylesheet" href="<?= base_url('assets/css/Admin/siswa.css') ?>">
<?= $this->endSection() ?>
<?= $this->section('content') ?>
<div id="base-url" data-url="<?= base_url() ?>"></div>
<div id="api-url" data-url="<?= base_url('admin/siswa/get-all') ?>"></div>
<div id="delete-url" data-url="<?= base_url('admin/siswa/delete') ?>"></div>

<div class="mb-6">
    <div class="flex items-center gap-2 text-sm text-gray-500 dark:text-slate-400 mb-2 transition-colors">
        <span><?= lang('Admin/Siswa.user_management') ?></span>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>
        <span class="text-[<?= $color['warna_primary'] ?>] font-medium"><?= lang('Admin/Siswa.student') ?></span>
    </div>
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-xl md:text-3xl font-bold text-gray-800 dark:text-white transition-colors"><?= lang('Admin/Siswa.page_title') ?></h1>
            <p class="text-sm md:text-base text-gray-600 dark:text-slate-400 mt-1 transition-colors"><?= lang('Admin/Siswa.page_subtitle') ?></p>
        </div>
        <div class="flex flex-wrap items-center gap-2 md:gap-3">
            <button onclick="showAddModal()" class="px-4 py-2.5 bg-[<?= $color['warna_primary'] ?>] hover:bg-[<?= $color['warna_primary'] ?>]/90 text-white font-semibold rounded-xl transition-all shadow-lg shadow-[<?= $color['warna_primary'] ?>]/20 flex items-center gap-2 transform hover:-translate-y-0.5 outline-none">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                <?= lang('Admin/Siswa.add_student') ?>
            </button>
            <button onclick="showImportModal()" class="px-4 py-2.5 bg-white dark:bg-slate-800 border border-gray-300 dark:border-slate-600 text-gray-700 dark:text-slate-300 font-medium rounded-xl hover:bg-gray-50 dark:hover:bg-slate-700 transition-colors flex items-center gap-2 shadow-sm outline-none">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                </svg>
                <span class="hidden sm:inline"><?= lang('Admin/Siswa.import_excel') ?></span>
            </button>
        </div>
    </div>
</div>

<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="bg-white dark:bg-slate-800 rounded-2xl p-5 shadow-sm border border-gray-100 dark:border-slate-700 transition-colors">
        <div class="flex items-center gap-3 mb-2">
            <div class="w-10 h-10 rounded-xl bg-emerald-100 dark:bg-emerald-900/30 flex items-center justify-center">
                <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
            </div>
        </div>
        <p class="text-sm font-medium text-gray-500 dark:text-slate-400 mb-1"><?= lang('Admin/Siswa.total_active') ?></p>
        <h3 class="text-2xl font-bold text-gray-800 dark:text-white"><?= $total_siswa ?></h3>
    </div>
    <div class="bg-white dark:bg-slate-800 rounded-2xl p-5 shadow-sm border border-gray-100 dark:border-slate-700 transition-colors">
        <div class="flex items-center gap-3 mb-2">
            <div class="w-10 h-10 rounded-xl bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center">
                <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                </svg>
            </div>
        </div>
        <p class="text-sm font-medium text-gray-500 dark:text-slate-400 mb-1"><?= lang('Admin/Siswa.total_alumni') ?></p>
        <h3 class="text-2xl font-bold text-gray-800 dark:text-white"><?= $total_alumni ?></h3>
    </div>
    <div class="bg-white dark:bg-slate-800 rounded-2xl p-5 shadow-sm border border-gray-100 dark:border-slate-700 transition-colors">
        <div class="flex items-center gap-3 mb-2">
            <div class="w-10 h-10 rounded-xl bg-purple-100 dark:bg-purple-900/30 flex items-center justify-center">
                <svg class="w-5 h-5 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
            </div>
        </div>
        <p class="text-sm font-medium text-gray-500 dark:text-slate-400 mb-1"><?= lang('Admin/Siswa.total_male') ?></p>
        <h3 class="text-2xl font-bold text-gray-800 dark:text-white"><?= $total_laki ?></h3>
    </div>
    <div class="bg-white dark:bg-slate-800 rounded-2xl p-5 shadow-sm border border-gray-100 dark:border-slate-700 transition-colors">
        <div class="flex items-center gap-3 mb-2">
            <div class="w-10 h-10 rounded-xl bg-pink-100 dark:bg-pink-900/30 flex items-center justify-center">
                <svg class="w-5 h-5 text-pink-600 dark:text-pink-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
            </div>
        </div>
        <p class="text-sm font-medium text-gray-500 dark:text-slate-400 mb-1"><?= lang('Admin/Siswa.total_female') ?></p>
        <h3 class="text-2xl font-bold text-gray-800 dark:text-white"><?= $total_perempuan ?></h3>
    </div>
</div>

<div class="bg-white dark:bg-slate-800 rounded-2xl p-4 md:p-6 shadow-sm border border-gray-100 dark:border-slate-700 mb-6 relative z-10 transition-colors">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">

        <div class="lg:col-span-2">
            <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-2"><?= lang('Admin/Siswa.search') ?></label>
            <div class="relative">
                <input type="text" id="searchInput" placeholder="<?= lang('Admin/Siswa.search_placeholder') ?>" class="w-full pl-10 pr-4 py-2.5 bg-white dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-xl text-sm font-medium text-gray-700 dark:text-white placeholder-gray-400 dark:placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-emerald-500 transition-all shadow-sm">
                <svg class="w-5 h-5 text-gray-400 dark:text-slate-400 absolute left-3 top-3 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-2"><?= lang('Admin/Siswa.academic_year') ?></label>
            <select id="filterTahunAkurat" onchange="applyFilters()" class="w-full px-4 py-2.5 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-xl text-sm font-medium text-gray-700 dark:text-white focus:bg-white dark:focus:bg-slate-600 focus:outline-none focus:ring-2 focus:ring-[<?= $color['warna_primary'] ?>]/50 cursor-pointer transition-all shadow-sm appearance-none">
                <option value="" selected><?= lang('Admin/Siswa.all_years') ?></option>
                <?php
                $now = date('Y');
                for ($i = 0; $i < 3; $i++):
                    $start = $now - $i;
                    $end = $start + 1;
                ?>
                    <option value="<?= "$start/$end" ?>"><?= "$start/$end" ?></option>
                <?php endfor; ?>
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-2"><?= lang('Admin/Siswa.level') ?></label>
            <select id="filterLevel" class="w-full px-4 py-2.5 bg-white dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-xl text-sm font-medium text-gray-700 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500 cursor-pointer shadow-sm transition-all appearance-none">
                <option value=""><?= lang('Admin/Siswa.all_levels') ?></option>
                <option value="VII"><?= lang('Admin/Siswa.class') ?> VII</option>
                <option value="VIII"><?= lang('Admin/Siswa.class') ?> VIII</option>
                <option value="IX"><?= lang('Admin/Siswa.class') ?> IX</option>
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-2"><?= lang('Admin/Siswa.status') ?></label>
            <select id="filterStatus" class="w-full px-4 py-2.5 bg-white dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-xl text-sm font-medium text-gray-700 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500 cursor-pointer shadow-sm transition-all appearance-none">
                <option value=""><?= lang('Admin/Siswa.all_statuses') ?></option>
                <option value="Aktif" selected><?= lang('Admin/Siswa.active') ?></option>
                <option value="Lulus"><?= lang('Admin/Siswa.alumni') ?></option>
                <option value="Nonaktif">Nonaktif (Pindah/Keluar)</option>
            </select>
        </div>

    </div>
</div>

<div class="grid grid-cols-1 min-w-0 w-full mb-10">
    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700 overflow-hidden w-full relative transition-colors">

        <div class="px-4 md:px-6 py-4 border-b border-gray-100 dark:border-slate-700 bg-gray-50 dark:bg-slate-900/50 flex items-center justify-between min-h-[60px] transition-colors">
            <div class="flex items-center gap-3">
                <input type="checkbox" id="selectAll" class="w-4 h-4 text-emerald-600 rounded border-gray-300 dark:border-slate-500 bg-white dark:bg-slate-700 focus:ring-emerald-500 cursor-pointer focus:ring-offset-0" onchange="toggleSelectAll(this)">
                <label for="selectAll" class="text-sm font-medium text-gray-700 dark:text-slate-300 cursor-pointer select-none">
                    <?= lang('Admin/Siswa.select_all') ?>
                </label>
                <span id="selectedCount" class="hidden text-sm text-gray-500 dark:text-slate-400 font-normal ml-1">
                    (0 dipilih)
                </span>
            </div>
            <div id="bulkActions" class="hidden flex items-center gap-2 animate-fade-in">
                <button class="px-3 py-1.5 bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400 text-xs font-semibold rounded-lg hover:bg-emerald-200 dark:hover:bg-emerald-900/50 border border-transparent dark:border-emerald-800/50 transition-colors"><?= lang('Admin/Siswa.export_selected') ?></button>
                <button class="px-3 py-1.5 bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400 text-xs font-semibold rounded-lg hover:bg-red-200 dark:hover:bg-red-900/50 border border-transparent dark:border-red-800/50 transition-colors"><?= lang('Admin/Siswa.deactivate') ?></button>
            </div>
        </div>

        <div class="block w-full overflow-x-auto custom-scrollbar">
            <table class="w-full whitespace-nowrap text-left border-collapse">
                <thead class="bg-gray-50 dark:bg-slate-900/50 border-b border-gray-100 dark:border-slate-700 transition-colors">
                    <tr>
                        <th class="pl-4 md:pl-6 py-4 w-12"></th>
                        <th class="px-4 md:px-6 py-4 text-xs font-bold text-gray-500 dark:text-slate-400 uppercase tracking-wider"><?= lang('Admin/Siswa.table_student') ?></th>
                        <th class="px-4 md:px-6 py-4 text-xs font-bold text-gray-500 dark:text-slate-400 uppercase tracking-wider"><?= lang('Admin/Siswa.table_nis_nisn') ?></th>
                        <th class="px-4 md:px-6 py-4 text-xs font-bold text-gray-500 dark:text-slate-400 uppercase tracking-wider"><?= lang('Admin/Siswa.table_gender') ?></th>
                        <th class="px-4 md:px-6 py-4 text-xs font-bold text-gray-500 dark:text-slate-400 uppercase tracking-wider"><?= lang('Admin/Siswa.table_rombel') ?></th>
                        <th class="px-4 md:px-6 py-4 text-xs font-bold text-gray-500 dark:text-slate-400 uppercase tracking-wider"><?= lang('Admin/Siswa.table_homeroom') ?></th>
                        <th class="px-4 md:px-6 py-4 text-xs font-bold text-gray-500 dark:text-slate-400 uppercase tracking-wider"><?= lang('Admin/Siswa.table_status') ?></th>
                        <th class="px-4 md:px-6 py-4 text-xs font-bold text-gray-500 dark:text-slate-400 uppercase tracking-wider text-center"><?= lang('Admin/Siswa.table_action') ?></th>
                    </tr>
                </thead>
                <tbody id="studentTableBody" class="divide-y divide-gray-100 dark:divide-slate-700/50 bg-white dark:bg-slate-800 transition-colors">
                    <tr>
                        <td colspan="8" class="px-6 py-12 text-center text-gray-500 dark:text-slate-400">
                            Memuat data...
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="px-4 md:px-6 py-4 border-t border-gray-100 dark:border-slate-700 flex flex-col md:flex-row items-center justify-between gap-4 bg-white dark:bg-slate-800/50 transition-colors">
            <div class="text-sm text-gray-500 dark:text-slate-400" id="pagination-info">Menampilkan 0 data</div>
            <div class="flex gap-2 pagination-wrapper" id="pagination-buttons"></div>
        </div>

    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('modals') ?>

<div id="drawer-overlay" class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm hidden transition-opacity" style="z-index: 999997 !important;" onclick="closeDrawer()"></div>

<div id="detailDrawer" class="fixed inset-y-0 right-0 w-full md:w-[500px] bg-white dark:bg-slate-800 shadow-2xl transform translate-x-full transition-transform duration-300 ease-in-out flex flex-col border-l border-gray-200 dark:border-slate-700" style="z-index: 999998 !important;">

    <div class="p-5 border-b border-gray-100 dark:border-slate-700 flex items-center justify-between bg-white dark:bg-slate-800 z-10 transition-colors">
        <h3 class="text-lg font-bold text-gray-800 dark:text-white"><?= lang('Admin/Siswa.detail_title') ?></h3>
        <button onclick="closeDrawer()" class="p-2 hover:bg-gray-100 dark:hover:bg-slate-700 rounded-full transition-colors text-gray-500 dark:text-slate-400 outline-none">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>

    <div class="flex-1 overflow-y-auto p-5 space-y-6 custom-scrollbar bg-gray-50/50 dark:bg-slate-900/50">

        <div class="text-center bg-white dark:bg-slate-800 p-5 rounded-2xl border border-gray-100 dark:border-slate-700 shadow-sm">
            <div id="detailAvatarContainer" class="w-28 h-28 rounded-full bg-gray-100 dark:bg-slate-700 flex items-center justify-center mx-auto mb-4 border-4 border-emerald-50 dark:border-emerald-900/30 shadow-md overflow-hidden relative transition-colors">
                <span class="text-3xl font-bold text-emerald-600 dark:text-emerald-400">...</span>
            </div>
            <h4 id="detailName" class="text-xl font-bold text-gray-800 dark:text-white mb-1">-</h4>
            <div class="flex items-center justify-center gap-2 text-sm text-gray-500 dark:text-slate-400 mb-3">
                <span id="detailNis" class="font-mono bg-gray-100 dark:bg-slate-700 px-2 py-0.5 rounded text-gray-600 dark:text-slate-300">-</span>
                <span>•</span>
                <span id="detailGender">-</span>
            </div>
            <span id="detailStatus" class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 dark:bg-slate-700 text-gray-600 dark:text-slate-300">-</span>
        </div>

        <div class="bg-white dark:bg-slate-800 rounded-2xl p-5 border border-gray-100 dark:border-slate-700 shadow-sm">
            <h5 class="font-bold text-emerald-600 dark:text-emerald-400 mb-3 text-sm border-b border-emerald-100 dark:border-emerald-800/50 pb-2"><?= lang('Admin/Siswa.identity_population') ?></h5>
            <div class="grid grid-cols-2 gap-y-3 gap-x-4 text-sm">
                <div>
                    <p class="text-xs text-gray-400"><?= lang('Admin/Siswa.nisn') ?></p>
                    <p id="detailNisn" class="font-medium text-gray-800 dark:text-slate-200">-</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400"><?= lang('Admin/Siswa.nik') ?></p>
                    <p id="detailNik" class="font-medium text-gray-800 dark:text-slate-200">-</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400"><?= lang('Admin/Siswa.kk_number') ?></p>
                    <p id="detailKk" class="font-medium text-gray-800 dark:text-slate-200">-</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400"><?= lang('Admin/Siswa.birth_cert_number') ?></p>
                    <p id="detailAkta" class="font-medium text-gray-800 dark:text-slate-200">-</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400"><?= lang('Admin/Siswa.birth_place_date') ?></p>
                    <p id="detailTtl" class="font-medium text-gray-800 dark:text-slate-200">-</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400"><?= lang('Admin/Siswa.religion') ?></p>
                    <p id="detailAgama" class="font-medium text-gray-800 dark:text-slate-200">-</p>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-800 rounded-2xl p-5 border border-gray-100 dark:border-slate-700 shadow-sm">
            <h5 class="font-bold text-pink-600 dark:text-pink-400 mb-3 text-sm border-b border-pink-100 dark:border-pink-800/50 pb-2"><?= lang('Admin/Siswa.physical_family') ?></h5>
            <div class="grid grid-cols-3 gap-y-3 gap-x-2 text-sm">
                <div>
                    <p class="text-xs text-gray-400"><?= lang('Admin/Siswa.child_order') ?></p>
                    <p id="detailAnakKe" class="font-medium text-gray-800 dark:text-slate-200">-</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400"><?= lang('Admin/Siswa.sibling_count') ?></p>
                    <p id="detailSaudara" class="font-medium text-gray-800 dark:text-slate-200">-</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400"><?= lang('Admin/Siswa.special_needs') ?></p>
                    <p id="detailKhusus" class="font-medium text-gray-800 dark:text-slate-200">-</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400"><?= lang('Admin/Siswa.weight') ?></p>
                    <p id="detailBerat" class="font-medium text-gray-800 dark:text-slate-200">-</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400"><?= lang('Admin/Siswa.height') ?></p>
                    <p id="detailTinggi" class="font-medium text-gray-800 dark:text-slate-200">-</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400"><?= lang('Admin/Siswa.head_circumference') ?></p>
                    <p id="detailKepala" class="font-medium text-gray-800 dark:text-slate-200">-</p>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-800 rounded-2xl p-5 border border-gray-100 dark:border-slate-700 shadow-sm">
            <h5 class="font-bold text-blue-600 dark:text-blue-400 mb-3 text-sm border-b border-blue-100 dark:border-blue-800/50 pb-2"><?= lang('Admin/Siswa.address_residence') ?></h5>
            <div class="space-y-3 text-sm">
                <div>
                    <p class="text-xs text-gray-400"><?= lang('Admin/Siswa.full_address') ?></p>
                    <p id="detailAlamatFull" class="font-medium text-gray-800 dark:text-slate-200 leading-relaxed">-</p>
                </div>
                <div class="grid grid-cols-2 gap-3 mt-2">
                    <div>
                        <p class="text-xs text-gray-400"><?= lang('Admin/Siswa.residence_type') ?></p>
                        <p id="detailTinggal" class="font-medium text-gray-800 dark:text-slate-200">-</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400"><?= lang('Admin/Siswa.transportation') ?></p>
                        <p id="detailTransport" class="font-medium text-gray-800 dark:text-slate-200">-</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400"><?= lang('Admin/Siswa.distance_to_school') ?></p>
                        <p id="detailJarak" class="font-medium text-gray-800 dark:text-slate-200">-</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400"><?= lang('Admin/Siswa.postal_code') ?></p>
                        <p id="detailPos" class="font-medium text-gray-800 dark:text-slate-200">-</p>
                    </div>
                </div>
                <div class="p-3 bg-blue-50 dark:bg-blue-900/20 rounded-xl flex flex-col gap-2 mt-3 border border-blue-100 dark:border-blue-800/30">
                    <div id="detailHp" class="flex items-center gap-2 font-medium text-blue-700 dark:text-blue-400">-</div>
                    <div id="detailEmail" class="flex items-center gap-2 font-medium text-blue-700 dark:text-blue-400 text-xs">-</div>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-800 rounded-2xl p-5 border border-gray-100 dark:border-slate-700 shadow-sm">
            <h5 class="font-bold text-purple-600 dark:text-purple-400 mb-3 text-sm border-b border-purple-100 dark:border-purple-800/50 pb-2"><?= lang('Admin/Siswa.academic_assistance') ?></h5>
            <div class="grid grid-cols-2 gap-y-3 gap-x-4 text-sm">
                <div>
                    <p class="text-xs text-gray-400"><?= lang('Admin/Siswa.current_rombel') ?></p>
                    <p id="detailRombel" class="font-medium text-gray-800 dark:text-slate-200">-</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400"><?= lang('Admin/Siswa.accepted_date') ?></p>
                    <p id="detailTglMasuk" class="font-medium text-gray-800 dark:text-slate-200">-</p>
                </div>
                <div class="col-span-2">
                    <p class="text-xs text-gray-400"><?= lang('Admin/Siswa.origin_school') ?></p>
                    <p id="detailSekolahAsal" class="font-medium text-gray-800 dark:text-slate-200">-</p>
                </div>

                <div class="col-span-2 border-t border-dashed border-gray-200 dark:border-slate-700 pt-2 mt-1"></div>
                <div>
                    <p class="text-xs text-gray-400"><?= lang('Admin/Siswa.skhun') ?></p>
                    <p id="detailSkhun" class="font-medium text-gray-800 dark:text-slate-200">-</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400"><?= lang('Admin/Siswa.un_ijazah_number') ?></p>
                    <p id="detailIjazah" class="font-medium text-gray-800 dark:text-slate-200">-</p>
                </div>

                <div class="col-span-2 border-t border-dashed border-gray-200 dark:border-slate-700 pt-2 mt-1"></div>
                <div>
                    <p class="text-xs text-gray-400"><?= lang('Admin/Siswa.kps_status') ?></p>
                    <p id="detailKps" class="font-medium text-gray-800 dark:text-slate-200">-</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400"><?= lang('Admin/Siswa.kip_pip_status') ?></p>
                    <p id="detailKip" class="font-medium text-gray-800 dark:text-slate-200">-</p>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-800 rounded-2xl p-5 border border-gray-100 dark:border-slate-700 shadow-sm">
            <h5 class="font-bold text-amber-600 dark:text-amber-400 mb-3 text-sm border-b border-amber-100 dark:border-amber-800/50 pb-2"><?= lang('Admin/Siswa.parent_guardian_data') ?></h5>

            <div class="space-y-4">
                <div class="p-3 bg-amber-50/50 dark:bg-amber-900/10 rounded-xl border border-amber-100/50 dark:border-amber-800/30">
                    <p class="text-xs font-bold text-amber-600 dark:text-amber-500 uppercase tracking-wider mb-2"><?= lang('Admin/Siswa.father') ?></p>
                    <div class="grid grid-cols-2 gap-2 text-sm">
                        <div class="col-span-2">
                            <p class="text-[11px] text-gray-400"><?= lang('Admin/Siswa.full_name') ?></p>
                            <p id="dtlAyahNama" class="font-medium text-gray-800 dark:text-slate-200">-</p>
                        </div>
                        <div>
                            <p class="text-[11px] text-gray-400"><?= lang('Admin/Siswa.nik') ?></p>
                            <p id="dtlAyahNik" class="font-medium text-gray-800 dark:text-slate-200">-</p>
                        </div>
                        <div>
                            <p class="text-[11px] text-gray-400"><?= lang('Admin/Siswa.birth_year') ?></p>
                            <p id="dtlAyahLahir" class="font-medium text-gray-800 dark:text-slate-200">-</p>
                        </div>
                        <div>
                            <p class="text-[11px] text-gray-400"><?= lang('Admin/Siswa.education') ?></p>
                            <p id="dtlAyahPend" class="font-medium text-gray-800 dark:text-slate-200">-</p>
                        </div>
                        <div>
                            <p class="text-[11px] text-gray-400"><?= lang('Admin/Siswa.occupation') ?></p>
                            <p id="dtlAyahKerja" class="font-medium text-gray-800 dark:text-slate-200">-</p>
                        </div>
                        <div class="col-span-2">
                            <p class="text-[11px] text-gray-400"><?= lang('Admin/Siswa.income') ?></p>
                            <p id="dtlAyahGaji" class="font-medium text-gray-800 dark:text-slate-200">-</p>
                        </div>
                    </div>
                </div>

                <div class="p-3 bg-amber-50/50 dark:bg-amber-900/10 rounded-xl border border-amber-100/50 dark:border-amber-800/30">
                    <p class="text-xs font-bold text-amber-600 dark:text-amber-500 uppercase tracking-wider mb-2"><?= lang('Admin/Siswa.mother') ?></p>
                    <div class="grid grid-cols-2 gap-2 text-sm">
                        <div class="col-span-2">
                            <p class="text-[11px] text-gray-400"><?= lang('Admin/Siswa.full_name') ?></p>
                            <p id="dtlIbuNama" class="font-medium text-gray-800 dark:text-slate-200">-</p>
                        </div>
                        <div>
                            <p class="text-[11px] text-gray-400"><?= lang('Admin/Siswa.nik') ?></p>
                            <p id="dtlIbuNik" class="font-medium text-gray-800 dark:text-slate-200">-</p>
                        </div>
                        <div>
                            <p class="text-[11px] text-gray-400"><?= lang('Admin/Siswa.birth_year') ?></p>
                            <p id="dtlIbuLahir" class="font-medium text-gray-800 dark:text-slate-200">-</p>
                        </div>
                        <div>
                            <p class="text-[11px] text-gray-400"><?= lang('Admin/Siswa.education') ?></p>
                            <p id="dtlIbuPend" class="font-medium text-gray-800 dark:text-slate-200">-</p>
                        </div>
                        <div>
                            <p class="text-[11px] text-gray-400"><?= lang('Admin/Siswa.occupation') ?></p>
                            <p id="dtlIbuKerja" class="font-medium text-gray-800 dark:text-slate-200">-</p>
                        </div>
                        <div class="col-span-2">
                            <p class="text-[11px] text-gray-400"><?= lang('Admin/Siswa.income') ?></p>
                            <p id="dtlIbuGaji" class="font-medium text-gray-800 dark:text-slate-200">-</p>
                        </div>
                    </div>
                </div>

                <div class="p-3 bg-gray-50 dark:bg-slate-700/50 rounded-xl border border-gray-200 dark:border-slate-600">
                    <p class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2"><?= lang('Admin/Siswa.guardian') ?></p>
                    <div class="grid grid-cols-2 gap-2 text-sm">
                        <div class="col-span-2">
                            <p class="text-[11px] text-gray-400"><?= lang('Admin/Siswa.guardian_name') ?></p>
                            <p id="dtlWaliNama" class="font-medium text-gray-800 dark:text-slate-200">-</p>
                        </div>
                        <div>
                            <p class="text-[11px] text-gray-400"><?= lang('Admin/Siswa.guardian_nik') ?></p>
                            <p id="dtlWaliNik" class="font-medium text-gray-800 dark:text-slate-200">-</p>
                        </div>
                        <div>
                            <p class="text-[11px] text-gray-400"><?= lang('Admin/Siswa.occupation') ?></p>
                            <p id="dtlWaliKerja" class="font-medium text-gray-800 dark:text-slate-200">-</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <div class="p-5 border-t border-gray-100 dark:border-slate-700 bg-white dark:bg-slate-800 z-10 flex gap-3 transition-colors shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.05)]">
        <button id="btnDrawerEdit" class="flex-1 px-4 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-xl transition-all flex items-center justify-center gap-2 shadow-lg shadow-emerald-500/20 outline-none hover:-translate-y-0.5">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
            </svg>
            <?= lang('Admin/Siswa.full_edit') ?>
        </button>
        <button onclick="closeDrawer()" class="flex-1 px-4 py-3 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 text-gray-700 dark:text-slate-300 font-semibold rounded-xl hover:bg-gray-100 dark:hover:bg-slate-600 transition-colors outline-none">
            <?= lang('Admin/Siswa.close') ?>
        </button>
    </div>
</div>

<div id="addModal" class="fixed inset-0 z-[100] hidden">
    <div class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" onclick="closeAddModal()"></div>
    <div class="absolute inset-0 flex items-center justify-center p-4 pointer-events-none md:left-64">
        <div class="relative w-full bg-white dark:bg-slate-800 rounded-2xl shadow-2xl flex flex-col max-h-[95vh] pointer-events-auto border border-transparent dark:border-slate-700 transition-colors duration-300" style="max-width: 1100px;">

            <div class="p-5 border-b border-gray-100 dark:border-slate-700 flex items-center justify-between bg-white dark:bg-slate-800 rounded-t-2xl z-20 flex-shrink-0 transition-colors">
                <div>
                    <h3 class="text-xl font-bold text-gray-800 dark:text-white"><?= lang('Admin/Siswa.form_title') ?></h3>
                    <p class="text-sm text-gray-500 dark:text-slate-400 mt-1"><?= lang('Admin/Siswa.form_subtitle') ?></p>
                </div>
                <button type="button" onclick="closeAddModal()" class="p-2 hover:bg-gray-100 dark:hover:bg-slate-700 rounded-full transition-colors cursor-pointer text-gray-500 dark:text-slate-400 outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <div class="flex-1 overflow-y-auto p-6 relative z-10 custom-scrollbar bg-gray-50/50 dark:bg-slate-900/50">
                <form id="addStudentForm" action="<?= base_url('admin/siswa/store') ?>" onsubmit="handleSubmit(event)" class="space-y-6" enctype="multipart/form-data">
                    <?= csrf_field() ?>
                    <input type="hidden" name="id">

                    <div class="bg-white dark:bg-slate-800 p-5 rounded-2xl shadow-sm border border-emerald-100 dark:border-emerald-800/50">
                        <h4 class="text-md font-bold text-emerald-600 dark:text-emerald-400 border-b border-emerald-100 dark:border-emerald-800/50 pb-2 mb-4"><?= lang('Admin/Siswa.section_a') ?></h4>
                        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-4 text-sm">
                            <div class="md:col-span-2 lg:col-span-2">
                                <label class="block text-xs font-bold text-gray-500 mb-1"><?= lang('Admin/Siswa.nis_auto') ?></label>
                                <input type="text" id="nis" name="nis" readonly placeholder="<?= lang('Admin/Siswa.nis_placeholder') ?>" class="w-full px-3 py-2 bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800/50 rounded-lg outline-none cursor-not-allowed font-mono text-emerald-700 dark:text-emerald-400 font-bold">
                            </div>
                            <div><label class="block text-xs font-bold text-gray-500 mb-1"><?= lang('Admin/Siswa.nisn') ?></label><input type="text" id="nisn" name="nisn" class="w-full px-3 py-2 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-lg outline-none text-gray-800 dark:text-white placeholder-gray-400 dark:placeholder-slate-400"></div>
                            <div><label class="block text-xs font-bold text-gray-500 mb-1"><?= lang('Admin/Siswa.student_nik') ?> <span class="text-red-500">*</span></label><input type="text" id="nik" name="nik" required class="w-full px-3 py-2 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-lg outline-none text-gray-800 dark:text-white placeholder-gray-400 dark:placeholder-slate-400"></div>

                            <div class="md:col-span-2"><label class="block text-xs font-bold text-gray-500 mb-1"><?= lang('Admin/Siswa.full_name') ?> <span class="text-red-500">*</span></label><input type="text" id="nama_lengkap" name="nama_lengkap" required class="w-full px-3 py-2 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-lg outline-none text-gray-800 dark:text-white placeholder-gray-400 dark:placeholder-slate-400"></div>
                            <div><label class="block text-xs font-bold text-gray-500 mb-1"><?= lang('Admin/Siswa.table_gender') ?></label><select id="jenis_kelamin" name="jenis_kelamin" class="w-full px-3 py-2 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-lg outline-none text-gray-800 dark:text-white">
                                    <option value="L"><?= lang('Admin/Siswa.male') ?></option>
                                    <option value="P"><?= lang('Admin/Siswa.female') ?></option>
                                </select></div>
                            <div><label class="block text-xs font-bold text-gray-500 mb-1"><?= lang('Admin/Siswa.religion') ?></label><select id="agama" name="agama" class="w-full px-3 py-2 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-lg outline-none text-gray-800 dark:text-white">
                                    <option value="Islam"><?= lang('Admin/Siswa.religion_islam') ?></option>
                                    <option value="Kristen"><?= lang('Admin/Siswa.religion_christian') ?></option>
                                    <option value="Katolik"><?= lang('Admin/Siswa.religion_catholic') ?></option>
                                    <option value="Hindu"><?= lang('Admin/Siswa.religion_hindu') ?></option>
                                    <option value="Buddha"><?= lang('Admin/Siswa.religion_buddha') ?></option>
                                </select></div>

                            <div class="md:col-span-2"><label class="block text-xs font-bold text-gray-500 mb-1"><?= lang('Admin/Siswa.birth_place') ?></label><input type="text" id="tempat_lahir" name="tempat_lahir" class="w-full px-3 py-2 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-lg outline-none text-gray-800 dark:text-white placeholder-gray-400 dark:placeholder-slate-400"></div>
                            <div class="md:col-span-2"><label class="block text-xs font-bold text-gray-500 mb-1"><?= lang('Admin/Siswa.birth_date') ?></label><input type="date" id="tanggal_lahir" name="tanggal_lahir" class="w-full px-3 py-2 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-lg outline-none color-scheme-dark text-gray-800 dark:text-white placeholder-gray-400 dark:placeholder-slate-400"></div>

                            <div class="md:col-span-2"><label class="block text-xs font-bold text-gray-500 mb-1"><?= lang('Admin/Siswa.kk_number') ?></label><input type="text" id="no_kk" name="no_kk" class="w-full px-3 py-2 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-lg outline-none text-gray-800 dark:text-white placeholder-gray-400 dark:placeholder-slate-400"></div>
                            <div class="md:col-span-2"><label class="block text-xs font-bold text-gray-500 mb-1"><?= lang('Admin/Siswa.birth_cert_number') ?></label><input type="text" id="no_registrasi_akta" name="no_registrasi_akta" class="w-full px-3 py-2 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-lg outline-none text-gray-800 dark:text-white placeholder-gray-400 dark:placeholder-slate-400"></div>
                        </div>
                    </div>

                    <div class="bg-white dark:bg-slate-800 p-5 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700">
                        <h4 class="text-md font-bold text-pink-600 dark:text-pink-400 border-b border-pink-100 dark:border-pink-800/50 pb-2 mb-4"><?= lang('Admin/Siswa.section_b') ?></h4>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                            <div><label class="block text-xs font-bold text-gray-500 mb-1"><?= lang('Admin/Siswa.family_status') ?></label><input type="text" id="status_dalam_keluarga" name="status_dalam_keluarga" placeholder="<?= lang('Admin/Siswa.biological_child') ?>" class="w-full px-3 py-2 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-lg outline-none text-gray-800 dark:text-white placeholder-gray-400 dark:placeholder-slate-400"></div>
                            <div><label class="block text-xs font-bold text-gray-500 mb-1"><?= lang('Admin/Siswa.child_order') ?></label><input type="number" id="anak_ke" name="anak_ke" class="w-full px-3 py-2 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-lg outline-none text-gray-800 dark:text-white placeholder-gray-400 dark:placeholder-slate-400"></div>
                            <div><label class="block text-xs font-bold text-gray-500 mb-1"><?= lang('Admin/Siswa.sibling_count') ?></label><input type="number" id="jml_saudara_kandung" name="jml_saudara_kandung" class="w-full px-3 py-2 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-lg outline-none text-gray-800 dark:text-white placeholder-gray-400 dark:placeholder-slate-400"></div>
                            <div><label class="block text-xs font-bold text-gray-500 mb-1"><?= lang('Admin/Siswa.special_needs') ?></label><input type="text" id="kebutuhan_khusus" name="kebutuhan_khusus" placeholder="<?= lang('Admin/Siswa.none') ?>" class="w-full px-3 py-2 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-lg outline-none text-gray-800 dark:text-white placeholder-gray-400 dark:placeholder-slate-400"></div>

                            <div><label class="block text-xs font-bold text-gray-500 mb-1"><?= lang('Admin/Siswa.weight') ?></label><input type="number" id="berat_badan" name="berat_badan" class="w-full px-3 py-2 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-lg outline-none text-gray-800 dark:text-white placeholder-gray-400 dark:placeholder-slate-400"></div>
                            <div><label class="block text-xs font-bold text-gray-500 mb-1"><?= lang('Admin/Siswa.height') ?></label><input type="number" id="tinggi_badan" name="tinggi_badan" class="w-full px-3 py-2 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-lg outline-none text-gray-800 dark:text-white placeholder-gray-400 dark:placeholder-slate-400"></div>
                            <div><label class="block text-xs font-bold text-gray-500 mb-1"><?= lang('Admin/Siswa.head_circumference') ?></label><input type="number" id="lingkar_kepala" name="lingkar_kepala" class="w-full px-3 py-2 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-lg outline-none text-gray-800 dark:text-white placeholder-gray-400 dark:placeholder-slate-400"></div>
                            <div><label class="block text-xs font-bold text-gray-500 mb-1"><?= lang('Admin/Siswa.distance_km') ?></label><input type="text" id="jarak_ke_sekolah" name="jarak_ke_sekolah" placeholder="1 KM" class="w-full px-3 py-2 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-lg outline-none text-gray-800 dark:text-white placeholder-gray-400 dark:placeholder-slate-400"></div>
                        </div>
                    </div>

                    <div class="bg-white dark:bg-slate-800 p-5 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700">
                        <h4 class="text-md font-bold text-blue-600 dark:text-blue-400 border-b border-blue-100 dark:border-blue-800/50 pb-2 mb-4"><?= lang('Admin/Siswa.section_c') ?></h4>
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 text-sm">
                            <div class="md:col-span-4"><label class="block text-xs font-bold text-gray-500 mb-1"><?= lang('Admin/Siswa.street_address') ?></label><textarea id="alamat_siswa" name="alamat_siswa" rows="2" class="w-full px-3 py-2 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-lg outline-none resize-none text-gray-800 dark:text-white placeholder-gray-400 dark:placeholder-slate-400"></textarea></div>

                            <div class="md:col-span-2">
                                <label class="block text-xs font-bold text-gray-500 mb-1"><?= lang('Admin/Siswa.district') ?></label>
                                <select id="kecamatan" name="kecamatan" class="w-full px-3 py-2 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-lg outline-none appearance-none cursor-pointer text-gray-800 dark:text-white">
                                    <option value=""><?= lang('Admin/Siswa.select_district') ?></option>
                                </select>
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-xs font-bold text-gray-500 mb-1"><?= lang('Admin/Siswa.village') ?></label>
                                <select id="kelurahan" name="kelurahan" class="w-full px-3 py-2 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-lg outline-none appearance-none cursor-pointer text-gray-800 dark:text-white">
                                    <option value=""><?= lang('Admin/Siswa.select_village') ?></option>
                                </select>
                            </div>

                            <div class="md:col-span-2"><label class="block text-xs font-bold text-gray-500 mb-1"><?= lang('Admin/Siswa.hamlet') ?></label><input type="text" id="dusun" name="dusun" class="w-full px-3 py-2 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-lg outline-none text-gray-800 dark:text-white placeholder-gray-400 dark:placeholder-slate-400"></div>
                            <div><label class="block text-xs font-bold text-gray-500 mb-1"><?= lang('Admin/Siswa.rt') ?></label><input type="text" id="rt" name="rt" class="w-full px-3 py-2 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-lg outline-none text-center text-gray-800 dark:text-white placeholder-gray-400 dark:placeholder-slate-400"></div>
                            <div><label class="block text-xs font-bold text-gray-500 mb-1"><?= lang('Admin/Siswa.rw') ?></label><input type="text" id="rw" name="rw" class="w-full px-3 py-2 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-lg outline-none text-center text-gray-800 dark:text-white placeholder-gray-400 dark:placeholder-slate-400"></div>

                            <div><label class="block text-xs font-bold text-gray-500 mb-1"><?= lang('Admin/Siswa.postal_code') ?></label><input type="text" id="kode_pos" name="kode_pos" class="w-full px-3 py-2 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-lg outline-none text-gray-800 dark:text-white placeholder-gray-400 dark:placeholder-slate-400"></div>
                            <div><label class="block text-xs font-bold text-gray-500 mb-1"><?= lang('Admin/Siswa.residence_type') ?></label><input type="text" id="jenis_tinggal" name="jenis_tinggal" placeholder="<?= lang('Admin/Siswa.with_parents') ?>" class="w-full px-3 py-2 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-lg outline-none text-gray-800 dark:text-white placeholder-gray-400 dark:placeholder-slate-400"></div>
                            <div class="md:col-span-2"><label class="block text-xs font-bold text-gray-500 mb-1"><?= lang('Admin/Siswa.transport_tool') ?></label><input type="text" id="alat_transportasi" name="alat_transportasi" placeholder="<?= lang('Admin/Siswa.transport_placeholder') ?>" class="w-full px-3 py-2 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-lg outline-none text-gray-800 dark:text-white placeholder-gray-400 dark:placeholder-slate-400"></div>

                            <div class="md:col-span-2"><label class="block text-xs font-bold text-gray-500 mb-1"><?= lang('Admin/Siswa.student_phone') ?></label><input type="text" id="no_hp" name="no_hp" class="w-full px-3 py-2 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-lg outline-none text-gray-800 dark:text-white placeholder-gray-400 dark:placeholder-slate-400"></div>
                            <div class="md:col-span-2"><label class="block text-xs font-bold text-gray-500 mb-1"><?= lang('Admin/Siswa.student_email') ?></label><input type="email" id="email_siswa" name="email_siswa" class="w-full px-3 py-2 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-lg outline-none text-gray-800 dark:text-white placeholder-gray-400 dark:placeholder-slate-400"></div>
                        </div>
                    </div>

                    <div class="bg-white dark:bg-slate-800 p-5 rounded-2xl shadow-sm border border-purple-100 dark:border-purple-800/50">
                        <h4 class="text-md font-bold text-purple-600 dark:text-purple-400 border-b border-purple-100 dark:border-purple-800/50 pb-2 mb-4"><?= lang('Admin/Siswa.section_d') ?></h4>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                            <div><label class="block text-xs font-bold text-gray-500 mb-1"><?= lang('Admin/Siswa.current_rombel') ?></label><select name="rombel_id" id="rombel_id" class="w-full px-3 py-2 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-lg outline-none text-gray-800 dark:text-white">
                                    <option value=""><?= lang('Admin/Siswa.not_in_class') ?></option>
                                </select></div>
                            <div><label class="block text-xs font-bold text-gray-500 mb-1"><?= lang('Admin/Siswa.accepted_in_class') ?></label><input type="text" id="diterima_dikelas" name="diterima_dikelas" placeholder="<?= lang('Admin/Siswa.example_7') ?>" class="w-full px-3 py-2 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-lg outline-none text-gray-800 dark:text-white placeholder-gray-400 dark:placeholder-slate-400"></div>
                            <div><label class="block text-xs font-bold text-gray-500 mb-1"><?= lang('Admin/Siswa.accepted_date') ?></label><input type="date" id="tgl_diterima" name="tgl_diterima" class="w-full px-3 py-2 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-lg outline-none color-scheme-dark text-gray-800 dark:text-white placeholder-gray-400 dark:placeholder-slate-400"></div>

                            <div class="md:col-span-3"><label class="block text-xs font-bold text-gray-500 mb-1"><?= lang('Admin/Siswa.origin_school_sd') ?></label><input type="text" id="asal_sekolah" name="asal_sekolah" class="w-full px-3 py-2 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-lg outline-none text-gray-800 dark:text-white placeholder-gray-400 dark:placeholder-slate-400"></div>

                            <div><label class="block text-xs font-bold text-gray-500 mb-1"><?= lang('Admin/Siswa.skhun') ?></label><input type="text" id="skhun" name="skhun" class="w-full px-3 py-2 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-lg outline-none text-gray-800 dark:text-white placeholder-gray-400 dark:placeholder-slate-400"></div>
                            <div><label class="block text-xs font-bold text-gray-500 mb-1"><?= lang('Admin/Siswa.un_number') ?></label><input type="text" id="no_peserta_un" name="no_peserta_un" class="w-full px-3 py-2 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-lg outline-none text-gray-800 dark:text-white placeholder-gray-400 dark:placeholder-slate-400"></div>
                            <div><label class="block text-xs font-bold text-gray-500 mb-1"><?= lang('Admin/Siswa.ijazah_number') ?></label><input type="text" id="no_seri_ijazah" name="no_seri_ijazah" class="w-full px-3 py-2 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-lg outline-none text-gray-800 dark:text-white placeholder-gray-400 dark:placeholder-slate-400"></div>
                        </div>
                    </div>

                    <div class="bg-white dark:bg-slate-800 p-5 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700">
                        <h4 class="text-md font-bold text-indigo-600 dark:text-indigo-400 border-b border-indigo-100 dark:border-indigo-800/50 pb-2 mb-4"><?= lang('Admin/Siswa.section_e') ?></h4>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                            <div><label class="block text-xs font-bold text-gray-500 mb-1"><?= lang('Admin/Siswa.is_kps_receiver') ?></label><select id="penerima_kps" name="penerima_kps" class="w-full px-3 py-2 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-lg outline-none text-gray-800 dark:text-white">
                                    <option value="Tidak"><?= lang('Admin/Siswa.no') ?></option>
                                    <option value="Ya"><?= lang('Admin/Siswa.yes') ?></option>
                                </select></div>
                            <div class="md:col-span-2"><label class="block text-xs font-bold text-gray-500 mb-1"><?= lang('Admin/Siswa.kps_pkh_number') ?></label><input type="text" id="no_kps" name="no_kps" class="w-full px-3 py-2 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-lg outline-none text-gray-800 dark:text-white placeholder-gray-400 dark:placeholder-slate-400"></div>

                            <div><label class="block text-xs font-bold text-gray-500 mb-1"><?= lang('Admin/Siswa.is_kip_receiver') ?></label><select id="penerima_kip" name="penerima_kip" class="w-full px-3 py-2 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-lg outline-none text-gray-800 dark:text-white">
                                    <option value="Tidak"><?= lang('Admin/Siswa.no') ?></option>
                                    <option value="Ya"><?= lang('Admin/Siswa.yes') ?></option>
                                </select></div>
                            <div><label class="block text-xs font-bold text-gray-500 mb-1"><?= lang('Admin/Siswa.kip_number') ?></label><input type="text" id="nomor_kip" name="nomor_kip" class="w-full px-3 py-2 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-lg outline-none text-gray-800 dark:text-white placeholder-gray-400 dark:placeholder-slate-400"></div>
                            <div><label class="block text-xs font-bold text-gray-500 mb-1"><?= lang('Admin/Siswa.name_on_kip') ?></label><input type="text" id="nama_di_kip" name="nama_di_kip" class="w-full px-3 py-2 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-lg outline-none text-gray-800 dark:text-white placeholder-gray-400 dark:placeholder-slate-400"></div>

                            <div><label class="block text-xs font-bold text-gray-500 mb-1"><?= lang('Admin/Siswa.kks_number') ?></label><input type="text" id="nomor_kks" name="nomor_kks" class="w-full px-3 py-2 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-lg outline-none text-gray-800 dark:text-white placeholder-gray-400 dark:placeholder-slate-400"></div>
                            <div><label class="block text-xs font-bold text-gray-500 mb-1"><?= lang('Admin/Siswa.is_pip_eligible') ?></label><select id="layak_pip" name="layak_pip" class="w-full px-3 py-2 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-lg outline-none text-gray-800 dark:text-white">
                                    <option value="Tidak"><?= lang('Admin/Siswa.no') ?></option>
                                    <option value="Ya"><?= lang('Admin/Siswa.yes') ?></option>
                                </select></div>
                            <div><label class="block text-xs font-bold text-gray-500 mb-1"><?= lang('Admin/Siswa.pip_reason') ?></label><input type="text" id="alasan_layak_pip" name="alasan_layak_pip" class="w-full px-3 py-2 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-lg outline-none text-gray-800 dark:text-white placeholder-gray-400 dark:placeholder-slate-400"></div>
                        </div>
                    </div>

                    <div class="bg-white dark:bg-slate-800 p-5 rounded-2xl shadow-sm border border-amber-100 dark:border-amber-900/30">
                        <h4 class="text-md font-bold text-amber-600 dark:text-amber-400 border-b border-amber-100 dark:border-amber-800/50 pb-2 mb-4"><?= lang('Admin/Siswa.section_f') ?></h4>

                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <div class="space-y-3">
                                <p class="text-xs font-bold text-gray-400 uppercase"><?= lang('Admin/Siswa.father_data') ?></p>
                                <div class="grid grid-cols-2 gap-3 text-sm">
                                    <div class="col-span-2"><label class="block text-xs font-bold text-gray-500 mb-1"><?= lang('Admin/Siswa.full_name') ?></label><input type="text" name="nama_ayah" id="nama_ayah" class="w-full px-3 py-2 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-lg outline-none text-gray-800 dark:text-white placeholder-gray-400 dark:placeholder-slate-400"></div>
                                    <div><label class="block text-xs font-bold text-gray-500 mb-1"><?= lang('Admin/Siswa.nik') ?></label><input type="text" name="nik_ayah" id="nik_ayah" class="w-full px-3 py-2 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-lg outline-none text-gray-800 dark:text-white placeholder-gray-400 dark:placeholder-slate-400"></div>
                                    <div><label class="block text-xs font-bold text-gray-500 mb-1"><?= lang('Admin/Siswa.birth_year') ?></label><input type="text" name="tahun_lahir_ayah" id="tahun_lahir_ayah" class="w-full px-3 py-2 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-lg outline-none text-gray-800 dark:text-white placeholder-gray-400 dark:placeholder-slate-400"></div>

                                    <div>
                                        <label class="block text-xs font-bold text-gray-500 mb-1"><?= lang('Admin/Siswa.education') ?></label>
                                        <select name="pendidikan_ayah" id="pendidikan_ayah" class="w-full px-3 py-2 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-lg outline-none text-gray-800 dark:text-white">
                                            <option value=""><?= lang('Admin/Siswa.select') ?></option>
                                            <option value="Tidak Sekolah">Tidak Sekolah</option>
                                            <option value="SD/Sederajat">SD/Sederajat</option>
                                            <option value="SMP/Sederajat">SMP/Sederajat</option>
                                            <option value="SMA/Sederajat">SMA/Sederajat</option>
                                            <option value="D1-D3">D1-D3</option>
                                            <option value="S1/D4">S1/D4</option>
                                            <option value="S2">S2</option>
                                            <option value="S3">S3</option>
                                        </select>
                                    </div>
                                    <div><label class="block text-xs font-bold text-gray-500 mb-1"><?= lang('Admin/Siswa.occupation') ?></label><input type="text" name="pekerjaan_ayah" id="pekerjaan_ayah" class="w-full px-3 py-2 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-lg outline-none text-gray-800 dark:text-white placeholder-gray-400 dark:placeholder-slate-400"></div>

                                    <div class="col-span-2">
                                        <label class="block text-xs font-bold text-gray-500 mb-1"><?= lang('Admin/Siswa.income') ?></label>
                                        <select name="penghasilan_ayah" id="penghasilan_ayah" class="w-full px-3 py-2 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-lg outline-none text-gray-800 dark:text-white">
                                            <option value=""><?= lang('Admin/Siswa.select') ?></option>
                                            <option value="Kurang dari Rp 1.000.000">Kurang dari Rp 1.000.000</option>
                                            <option value="Rp 1.000.000 - Rp 2.000.000">Rp 1.000.000 - Rp 2.000.000</option>
                                            <option value="Rp 2.000.000 - Rp 5.000.000">Rp 2.000.000 - Rp 5.000.000</option>
                                            <option value="Rp 5.000.000 - Rp 20.000.000">Rp 5.000.000 - Rp 20.000.000</option>
                                            <option value="Lebih dari Rp 20.000.000">Lebih dari Rp 20.000.000</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="space-y-3">
                                <p class="text-xs font-bold text-gray-400 uppercase"><?= lang('Admin/Siswa.mother_data') ?></p>
                                <div class="grid grid-cols-2 gap-3 text-sm">
                                    <div class="col-span-2"><label class="block text-xs font-bold text-gray-500 mb-1"><?= lang('Admin/Siswa.full_name') ?></label><input type="text" name="nama_ibu" id="nama_ibu" class="w-full px-3 py-2 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-lg outline-none text-gray-800 dark:text-white placeholder-gray-400 dark:placeholder-slate-400"></div>
                                    <div><label class="block text-xs font-bold text-gray-500 mb-1"><?= lang('Admin/Siswa.nik') ?></label><input type="text" name="nik_ibu" id="nik_ibu" class="w-full px-3 py-2 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-lg outline-none text-gray-800 dark:text-white placeholder-gray-400 dark:placeholder-slate-400"></div>
                                    <div><label class="block text-xs font-bold text-gray-500 mb-1"><?= lang('Admin/Siswa.birth_year') ?></label><input type="text" name="tahun_lahir_ibu" id="tahun_lahir_ibu" class="w-full px-3 py-2 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-lg outline-none text-gray-800 dark:text-white placeholder-gray-400 dark:placeholder-slate-400"></div>

                                    <div>
                                        <label class="block text-xs font-bold text-gray-500 mb-1"><?= lang('Admin/Siswa.education') ?></label>
                                        <select name="pendidikan_ibu" id="pendidikan_ibu" class="w-full px-3 py-2 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-lg outline-none text-gray-800 dark:text-white">
                                            <option value=""><?= lang('Admin/Siswa.select') ?></option>
                                            <option value="Tidak Sekolah">Tidak Sekolah</option>
                                            <option value="SD/Sederajat">SD/Sederajat</option>
                                            <option value="SMP/Sederajat">SMP/Sederajat</option>
                                            <option value="SMA/Sederajat">SMA/Sederajat</option>
                                            <option value="D1-D3">D1-D3</option>
                                            <option value="S1/D4">S1/D4</option>
                                            <option value="S2">S2</option>
                                            <option value="S3">S3</option>
                                        </select>
                                    </div>
                                    <div><label class="block text-xs font-bold text-gray-500 mb-1"><?= lang('Admin/Siswa.occupation') ?></label><input type="text" name="pekerjaan_ibu" id="pekerjaan_ibu" class="w-full px-3 py-2 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-lg outline-none text-gray-800 dark:text-white placeholder-gray-400 dark:placeholder-slate-400"></div>

                                    <div class="col-span-2">
                                        <label class="block text-xs font-bold text-gray-500 mb-1"><?= lang('Admin/Siswa.income') ?></label>
                                        <select name="penghasilan_ibu" id="penghasilan_ibu" class="w-full px-3 py-2 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-lg outline-none text-gray-800 dark:text-white">
                                            <option value=""><?= lang('Admin/Siswa.select') ?></option>
                                            <option value="Kurang dari Rp 1.000.000">Kurang dari Rp 1.000.000</option>
                                            <option value="Rp 1.000.000 - Rp 2.000.000">Rp 1.000.000 - Rp 2.000.000</option>
                                            <option value="Rp 2.000.000 - Rp 5.000.000">Rp 2.000.000 - Rp 5.000.000</option>
                                            <option value="Rp 5.000.000 - Rp 20.000.000">Rp 5.000.000 - Rp 20.000.000</option>
                                            <option value="Lebih dari Rp 20.000.000">Lebih dari Rp 20.000.000</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="col-span-1 lg:col-span-2 border-t border-dashed border-gray-200 dark:border-slate-700 pt-4 mt-2">
                                <p class="text-xs font-bold text-gray-400 uppercase mb-3"><?= lang('Admin/Siswa.guardian_data_opt') ?></p>
                                <div class="grid grid-cols-2 md:grid-cols-3 gap-4 text-sm">
                                    <div class="col-span-2"><label class="block text-xs font-bold text-gray-500 mb-1"><?= lang('Admin/Siswa.guardian_name') ?></label><input type="text" name="nama_wali" id="nama_wali" class="w-full px-3 py-2 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-lg outline-none text-gray-800 dark:text-white placeholder-gray-400 dark:placeholder-slate-400"></div>
                                    <div><label class="block text-xs font-bold text-gray-500 mb-1"><?= lang('Admin/Siswa.guardian_nik') ?></label><input type="text" name="nik_wali" id="nik_wali" class="w-full px-3 py-2 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-lg outline-none text-gray-800 dark:text-white placeholder-gray-400 dark:placeholder-slate-400"></div>

                                    <div><label class="block text-xs font-bold text-gray-500 mb-1"><?= lang('Admin/Siswa.birth_year') ?></label><input type="text" name="tahun_lahir_wali" id="tahun_lahir_wali" class="w-full px-3 py-2 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-lg outline-none text-gray-800 dark:text-white placeholder-gray-400 dark:placeholder-slate-400"></div>
                                    <div>
                                        <label class="block text-xs font-bold text-gray-500 mb-1"><?= lang('Admin/Siswa.education') ?></label>
                                        <select name="pendidikan_wali" id="pendidikan_wali" class="w-full px-3 py-2 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-lg outline-none text-gray-800 dark:text-white">
                                            <option value=""><?= lang('Admin/Siswa.select') ?></option>
                                            <option value="Tidak Sekolah">Tidak Sekolah</option>
                                            <option value="SD/Sederajat">SD/Sederajat</option>
                                            <option value="SMP/Sederajat">SMP/Sederajat</option>
                                            <option value="SMA/Sederajat">SMA/Sederajat</option>
                                            <option value="D1-D3">D1-D3</option>
                                            <option value="S1/D4">S1/D4</option>
                                            <option value="S2">S2</option>
                                            <option value="S3">S3</option>
                                        </select>
                                    </div>
                                    <div><label class="block text-xs font-bold text-gray-500 mb-1"><?= lang('Admin/Siswa.occupation') ?></label><input type="text" name="pekerjaan_wali" id="pekerjaan_wali" class="w-full px-3 py-2 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-lg outline-none text-gray-800 dark:text-white placeholder-gray-400 dark:placeholder-slate-400"></div>

                                    <div class="col-span-2 md:col-span-3">
                                        <label class="block text-xs font-bold text-gray-500 mb-1"><?= lang('Admin/Siswa.income') ?></label>
                                        <select name="penghasilan_wali" id="penghasilan_wali" class="w-full px-3 py-2 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-lg outline-none text-gray-800 dark:text-white">
                                            <option value=""><?= lang('Admin/Siswa.select') ?></option>
                                            <option value="Kurang dari Rp 1.000.000">Kurang dari Rp 1.000.000</option>
                                            <option value="Rp 1.000.000 - Rp 2.000.000">Rp 1.000.000 - Rp 2.000.000</option>
                                            <option value="Rp 2.000.000 - Rp 5.000.000">Rp 2.000.000 - Rp 5.000.000</option>
                                            <option value="Rp 5.000.000 - Rp 20.000.000">Rp 5.000.000 - Rp 20.000.000</option>
                                            <option value="Lebih dari Rp 20.000.000">Lebih dari Rp 20.000.000</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="col-span-1 lg:col-span-2 border-t border-dashed border-gray-200 dark:border-slate-700 pt-4 mt-2">
                                <p class="text-xs font-bold text-gray-400 uppercase mb-3"><?= lang('Admin/Siswa.parent_contact_address') ?></p>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                                    <div><label class="block text-xs font-bold text-blue-600 dark:text-blue-400 mb-1"><?= lang('Admin/Siswa.parent_phone') ?> <span class="text-red-500">*</span></label><input type="text" name="no_hp_ortu" id="no_hp_ortu" required class="w-full px-3 py-2 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800/50 rounded-lg outline-none text-blue-900 dark:text-blue-100 placeholder-blue-400 dark:placeholder-blue-300"></div>
                                    <div><label class="block text-xs font-bold text-gray-500 mb-1"><?= lang('Admin/Siswa.parent_email') ?></label><input type="email" name="email_ortu" id="email_ortu" class="w-full px-3 py-2 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-lg outline-none text-gray-800 dark:text-white placeholder-gray-400 dark:placeholder-slate-400"></div>
                                    <div class="md:col-span-2"><label class="block text-xs font-bold text-gray-500 mb-1"><?= lang('Admin/Siswa.parent_address') ?></label><textarea name="alamat_orangtua" id="alamat_orangtua" rows="2" class="w-full px-3 py-2 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-lg outline-none resize-none text-gray-800 dark:text-white placeholder-gray-400 dark:placeholder-slate-400"></textarea></div>
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="bg-white dark:bg-slate-800 p-5 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700">
                        <h4 class="text-md font-bold text-teal-600 dark:text-teal-400 border-b border-teal-100 dark:border-teal-800/50 pb-2 mb-4"><?= lang('Admin/Siswa.section_g') ?></h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-center">
                            <div>
                                <label class="block text-xs font-bold text-gray-500 mb-1"><?= lang('Admin/Siswa.student_active_status') ?></label>
                                <select name="status_siswa" id="status_siswa" class="w-full px-3 py-2 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-lg outline-none text-gray-800 dark:text-white">
                                    <option value="Aktif"><?= lang('Admin/Siswa.active') ?></option>
                                    <option value="Lulus"><?= lang('Admin/Siswa.graduated') ?></option>
                                    <option value="Pindah"><?= lang('Admin/Siswa.moved') ?></option>
                                    <option value="Keluar"><?= lang('Admin/Siswa.dropped_out') ?></option>
                                </select>
                            </div>
                            <div class="flex gap-4 items-center">
                                <div class="flex-1">
                                    <label class="block text-xs font-bold text-gray-500 mb-1"><?= lang('Admin/Siswa.student_photo') ?></label>
                                    <input type="file" name="photo" accept="image/*" onchange="previewPhoto(event)" class="w-full text-xs text-gray-500 dark:text-gray-400 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:bg-emerald-50 file:text-emerald-700 dark:file:bg-emerald-900/30 dark:file:text-emerald-400 hover:file:bg-emerald-100 outline-none">
                                </div>
                                <div id="photoPreview" class="w-16 h-16 rounded-xl bg-gray-100 dark:bg-slate-700 flex items-center justify-center overflow-hidden border border-gray-200 dark:border-slate-600 shrink-0">
                                    <svg class="w-8 h-8 text-gray-300 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white dark:bg-slate-800 p-5 rounded-2xl shadow-sm border border-emerald-100 dark:border-emerald-800/50 mt-6">
                        <h4 class="text-md font-bold text-emerald-600 dark:text-emerald-400 border-b border-emerald-100 dark:border-emerald-800/50 pb-2 mb-4">H. Ekstrakurikuler (Maksimal 3)</h4>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                            <div>
                                <label class="block text-xs font-bold text-gray-500 mb-1">Pilihan Ekskul 1</label>
                                <select name="ekskul_1" id="ekskul_1" class="w-full px-3 py-2 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-lg outline-none text-gray-800 dark:text-white cursor-pointer font-semibold">
                                    <option value="">-- Tidak Memilih --</option>
                                    <?php foreach ($ekskulList as $ek): ?>
                                        <option value="<?= $ek['id'] ?>"><?= $ek['nama_ekskul'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-500 mb-1">Pilihan Ekskul 2</label>
                                <select name="ekskul_2" id="ekskul_2" class="w-full px-3 py-2 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-lg outline-none text-gray-800 dark:text-white cursor-pointer font-semibold">
                                    <option value="">-- Tidak Memilih --</option>
                                    <?php foreach ($ekskulList as $ek): ?>
                                        <option value="<?= $ek['id'] ?>"><?= $ek['nama_ekskul'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-500 mb-1">Pilihan Ekskul 3</label>
                                <select name="ekskul_3" id="ekskul_3" class="w-full px-3 py-2 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-lg outline-none text-gray-800 dark:text-white cursor-pointer font-semibold">
                                    <option value="">-- Tidak Memilih --</option>
                                    <?php foreach ($ekskulList as $ek): ?>
                                        <option value="<?= $ek['id'] ?>"><?= $ek['nama_ekskul'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-3 pt-4 sticky bottom-0 bg-gray-50/90 dark:bg-slate-900/90 backdrop-blur z-10 pb-2 transition-colors mt-4">
                        <button type="button" onclick="closeAddModal()" class="flex-1 px-6 py-3 bg-white dark:bg-slate-700 border border-gray-300 dark:border-slate-600 text-gray-700 dark:text-slate-300 font-semibold rounded-xl hover:bg-gray-50 dark:hover:bg-slate-600 transition-colors shadow-sm outline-none"><?= lang('Admin/Siswa.cancel') ?></button>
                        <button type="submit" class="flex-1 px-6 py-3 bg-[<?= $color['warna_primary'] ?>] text-white font-semibold rounded-xl transition-all shadow-lg hover:scale-[1.02] flex items-center justify-center gap-2 outline-none">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg> <?= lang('Admin/Siswa.save_all_data') ?>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div id="importModal" class="fixed inset-0 z-[100] hidden">
    <div class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" onclick="closeImportModal()"></div>
    <div class="absolute inset-0 flex items-center justify-center p-4 pointer-events-none md:left-64">
        <div class="relative w-full bg-white dark:bg-slate-800 rounded-2xl shadow-2xl flex flex-col max-h-[90vh] pointer-events-auto border border-transparent dark:border-slate-700 transition-colors duration-300" style="max-width: 500px;">
            <div class="p-6 border-b border-gray-100 dark:border-slate-700 flex items-center justify-between transition-colors">
                <h3 class="text-xl font-bold text-gray-800 dark:text-white"><?= lang('Admin/Siswa.import_title') ?></h3>
                <button onclick="closeImportModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-slate-300 transition-colors outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <div class="p-6">
                <form id="importForm" action="<?= base_url('admin/siswa/import') ?>" method="POST" onsubmit="handleImport(event)" enctype="multipart/form-data">
                    <?= csrf_field() ?>
                    <div class="mb-4">
                        <p class="text-sm text-gray-600 dark:text-slate-400 mb-2"><?= lang('Admin/Siswa.download_template_step') ?></p>
                        <a href="<?= base_url('admin/siswa/template') ?>" class="inline-flex items-center gap-2 text-[<?= $color['warna_primary'] ?>]/90 hover:text-[<?= $color['warna_primary'] ?>] dark:text-[<?= $color['warna_primary'] ?>] font-medium text-sm transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                            </svg>
                            <?= lang('Admin/Siswa.download_template') ?>
                        </a>
                    </div>
                    <div class="mb-6">
                        <p class="text-sm text-gray-600 dark:text-slate-400 mb-2"><?= lang('Admin/Siswa.upload_file_step') ?></p>
                        <input type="file" name="file_excel" accept=".xlsx, .xls" required class="block w-full text-sm text-gray-500 dark:text-slate-400 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-emerald-50 dark:file:bg-emerald-900/30 file:text-[<?= $color['warna_primary'] ?>] hover:file:bg-emerald-100 dark:hover:file:bg-emerald-800/50 cursor-pointer outline-none transition-colors">
                    </div>
                    <div class="flex justify-end gap-3">
                        <button type="button" onclick="closeImportModal()" class="px-5 py-2.5 bg-white dark:bg-slate-700 border border-gray-300 dark:border-slate-600 text-gray-700 dark:text-slate-300 font-medium rounded-xl hover:bg-gray-50 dark:hover:bg-slate-600 transition-colors shadow-sm outline-none"><?= lang('Admin/Siswa.cancel') ?></button>
                        <button type="submit" class="px-5 py-2.5 bg-[<?= $color['warna_primary'] ?>]/90 hover:bg-[<?= $color['warna_primary'] ?>] text-white font-medium rounded-xl transition-all shadow-lg shadow-[<?= $color['warna_primary'] ?>]/20 outline-none"><?= lang('Admin/Siswa.upload_import') ?></button>
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
    // Memetakan kunci bahasa secara manual agar terbaca sempurna oleh JavaScript
    const LANG = {
        js_loading_data: <?= json_encode(lang('Admin/Siswa.js_loading_data')) ?>,
        js_server_error: <?= json_encode(lang('Admin/Siswa.js_server_error')) ?>,
        js_select_rombel: <?= json_encode(lang('Admin/Siswa.js_select_rombel')) ?>,
        not_in_class: <?= json_encode(lang('Admin/Siswa.not_in_class')) ?>,
        js_candidate: <?= json_encode(lang('Admin/Siswa.js_candidate')) ?>,
        js_not_set: <?= json_encode(lang('Admin/Siswa.js_not_set')) ?>,
        js_no_data: <?= json_encode(lang('Admin/Siswa.js_no_data')) ?>,
        js_showing: <?= json_encode(lang('Admin/Siswa.js_showing')) ?>,
        js_from: <?= json_encode(lang('Admin/Siswa.js_from')) ?>,
        js_data: <?= json_encode(lang('Admin/Siswa.js_data')) ?>,
        js_view_detail: <?= json_encode(lang('Admin/Siswa.js_view_detail')) ?>,
        js_edit: <?= json_encode(lang('Admin/Siswa.js_edit')) ?>,
        js_add_student: <?= json_encode(lang('Admin/Siswa.js_add_student')) ?>,
        js_edit_student: <?= json_encode(lang('Admin/Siswa.js_edit_student')) ?>,
        btn_update: <?= json_encode(lang('Admin/Siswa.btn_update')) ?>,
        js_saving: <?= json_encode(lang('Admin/Siswa.js_saving')) ?>,
        js_success: <?= json_encode(lang('Admin/Siswa.js_success')) ?>,
        js_failed: <?= json_encode(lang('Admin/Siswa.js_failed')) ?>,
        js_select_district: <?= json_encode(lang('Admin/Siswa.js_select_district')) ?>,
        js_db_error: <?= json_encode(lang('Admin/Siswa.js_db_error')) ?>,
        js_select_village: <?= json_encode(lang('Admin/Siswa.js_select_village')) ?>,
        js_loading_village: <?= json_encode(lang('Admin/Siswa.js_loading_village')) ?>,
        js_processing: <?= json_encode(lang('Admin/Siswa.js_processing')) ?>,
        js_info: <?= json_encode(lang('Admin/Siswa.js_info')) ?>,
        js_import_warning: <?= json_encode(lang('Admin/Siswa.js_import_warning')) ?>,
        js_import_success_title: <?= json_encode(lang('Admin/Siswa.js_import_success_title')) ?>,
        js_connection_lost: <?= json_encode(lang('Admin/Siswa.js_connection_lost')) ?>,
        js_selected: <?= json_encode(lang('Admin/Siswa.js_selected')) ?>,
        active: <?= json_encode(lang('Admin/Siswa.active')) ?>,
        graduated: <?= json_encode(lang('Admin/Siswa.graduated')) ?>,
        moved: <?= json_encode(lang('Admin/Siswa.moved')) ?>,
        dropped_out: <?= json_encode(lang('Admin/Siswa.dropped_out')) ?>,
        form_subtitle: <?= json_encode(lang('Admin/Siswa.form_subtitle')) ?>,
        save_all_data: <?= json_encode(lang('Admin/Siswa.save_all_data')) ?>
    };
    const APP_LANG = document.documentElement.lang || 'id-ID';
</script>
<script src="<?= base_url('assets/js/Admin/siswa.js') ?>?v=<?= time() ?>"></script>
<?= $this->endSection() ?>