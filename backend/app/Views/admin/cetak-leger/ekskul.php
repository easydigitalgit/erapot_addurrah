<?= $this->extend('layout/main') ?>

<?= $this->section('title') ?>
  <?= lang('Admin/LegerEkskul.page_title') ?>
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
    :root { --warna-scroll: <?= $color['warna_primary'] ?>; }
    .leger-table thead tr th { background-color: var(--warna-scroll); color: white; border: 1px solid rgba(255,255,255,0.2); }
    .dark .leger-table thead tr th { background-color: #334155 !important; border-color: #475569 !important; }
    
    @media print {
        @page { size: legal landscape; margin: 10mm; }
        
        /* Lepaskan gembok template */
        html, body { height: auto !important; min-height: 100% !important; overflow: visible !important; }
        
        /* Sembunyikan SEMUA elemen web asli saat diprint */
        body > * { display: none !important; }
        
        /* Tampilkan HANYA Kanvas Hantu kita (#printArea) */
        body > #printArea { display: block !important; width: 100%; background: white; color: black; }
        
        /* Gaya tabel print */
        #printArea table { width: 100%; border-collapse: collapse; font-family: Arial, sans-serif; font-size: 8.5pt; color: black; margin-bottom: 10px; }
        #printArea th, #printArea td { border: 1px solid black; padding: 6px 4px; text-align: center; color: black; }
        #printArea td.text-left { text-align: left !important; white-space: nowrap; }

        /* Gunting Kertas */
        .potong-kertas { page-break-after: always !important; break-after: page !important; }
    }

    ::-webkit-scrollbar {
      width: 6px;
    }
    
    ::-webkit-scrollbar-track {
      background: #f1f1f1;
    }
    
    ::-webkit-scrollbar-thumb {
      background-color: var(--warna-scroll);
      border-radius: 3px;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="mb-6 no-print">
    <div class="flex items-center gap-2 text-sm text-gray-500 dark:text-slate-400 mb-3">
        <span><?= lang('Admin/LegerEkskul.breadcrumb') ?></span>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7" /></svg>
        <span class="text-[<?= $color['warna_primary'] ?>] font-medium"><?= lang('Admin/LegerEkskul.breadcrumb_active') ?></span>
    </div>
    
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white flex items-center gap-3">
                <svg class="w-8 h-8 text-[<?= $color['warna_primary'] ?>]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" /></svg>
                <?= lang('Admin/LegerEkskul.title') ?>
            </h1>
            <p class="text-sm text-gray-600 dark:text-slate-400"><?= lang('Admin/LegerEkskul.subtitle') ?></p>
        </div>
        <div class="flex gap-2">
            <button onclick="printLegerEkskul()" class="btn-primary bg-[<?= $color['warna_primary'] ?>] text-white px-5 py-2.5 rounded-xl font-bold flex items-center gap-2 shadow-lg hover:opacity-90 outline-none">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" /></svg>
                <?= lang('Admin/LegerEkskul.btn_print') ?>
            </button>
            <button onclick="exportToExcel()" class="btn-secondary bg-white dark:bg-slate-800 border dark:border-slate-700 text-gray-700 dark:text-slate-200 px-5 py-2.5 rounded-xl font-bold flex items-center gap-2 shadow-sm outline-none">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                <?= lang('Admin/LegerEkskul.btn_excel') ?>
            </button>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-4 gap-6 mb-6 no-print">
    <div class="lg:col-span-3 bg-white dark:bg-slate-800 p-6 rounded-3xl border border-gray-100 dark:border-slate-700 shadow-sm">
        <h3 class="font-bold text-gray-800 dark:text-white mb-5 flex items-center gap-2 pb-3 border-b border-gray-100 dark:border-slate-700">
            <svg class="w-5 h-5 text-[<?= $color['warna_primary'] ?>]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" /></svg>
            <?= lang('Admin/LegerEkskul.filter_title') ?>
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
            <div>
                <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-2"><?= lang('Admin/LegerEkskul.lbl_ta_smt') ?></label>
                <select id="filter_ta_smt" onchange="window.location.href='?ta=' + encodeURIComponent(this.value)" class="w-full px-4 py-3 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-xl outline-none focus:ring-2 focus:ring-[<?= $color['warna_primary'] ?>] transition-all cursor-pointer">
                    <?php foreach($list_ta_smt as $ta): ?>
                        <option value="<?= $ta['value'] ?>" <?= ($ta['value'] === ($ta_terpilih ?? '')) ? 'selected' : '' ?>>
                            <?= $ta['text'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-2"><?= lang('Admin/LegerEkskul.lbl_class') ?></label>
                <select id="filter_rombel" class="w-full px-4 py-3 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-xl outline-none focus:ring-2 focus:ring-[<?= $color['warna_primary'] ?>] transition-all">
                    <?php foreach($list_rombel as $rom): ?>
                        <option value="<?= $rom['id'] ?>"><?= $rom['nama'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="flex items-end">
                <button onclick="loadLegerData()" class="w-full bg-[<?= $color['warna_secondary'] ?>] text-[<?= $color['warna_primary'] ?>] font-bold py-3 rounded-xl hover:opacity-80 transition-all"><?= lang('Admin/LegerEkskul.btn_load_data') ?></button>
            </div>
        </div>
    </div>
    
    <div class="bg-gradient-to-br from-[<?= $color['warna_primary'] ?>] to-slate-900 p-6 rounded-3xl text-white shadow-lg overflow-hidden relative group transition-colors">
        <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-white/10 rounded-full blur-2xl group-hover:scale-150 transition-transform"></div>
        <h3 class="font-bold text-white/70 text-xs uppercase tracking-widest mb-4"><?= lang('Admin/LegerEkskul.card_total_title') ?></h3>
        <p id="total_peserta_count" class="text-4xl font-black mb-1">0</p>
        <p class="text-white/60 text-[10px] uppercase font-bold tracking-wider"><?= lang('Admin/LegerEkskul.card_total_desc') ?></p>
    </div>
</div>

<div class="bg-white dark:bg-slate-800 rounded-3xl border border-gray-100 dark:border-slate-700 shadow-sm overflow-hidden transition-colors">
    <div class="leger-table-wrapper w-full overflow-x-auto custom-scrollbar">
        <table id="legerTable" class="leger-table w-full text-left border-collapse min-w-max">
            <thead>
                <tr class="text-[11px] uppercase tracking-wider font-bold text-center">
                    <th rowspan="2" class="px-4 py-4 md:sticky md:left-0 z-40 bg-[<?= $color['warna_primary'] ?>] border-white/10"><?= lang('Admin/LegerEkskul.th_no') ?></th>
                    <th rowspan="2" class="px-4 py-4 md:sticky md:left-[50px] z-40 bg-[<?= $color['warna_primary'] ?>] border-white/10"><?= lang('Admin/LegerEkskul.th_nis') ?></th>
                    <th rowspan="2" class="px-4 py-4 md:sticky md:left-[130px] z-40 bg-[<?= $color['warna_primary'] ?>] border-white/10" style="min-width: 220px;"><?= lang('Admin/LegerEkskul.th_student_name') ?></th>
                    <th colspan="<?= count($list_ekskul) ?>" class="px-4 py-3 border-white/10"><?= lang('Admin/LegerEkskul.th_ekskul_type') ?></th>
                    <th rowspan="2" class="px-4 py-4 bg-[<?= $color['warna_primary'] ?>] border-white/10"><?= lang('Admin/LegerEkskul.th_total') ?></th>
                </tr>
                <tr class="text-[10px] uppercase font-black text-center">
                    <?php foreach($list_ekskul as $ek): ?>
                        <th class="px-3 py-2 border-white/10"><?= $ek['nama_ekskul'] ?></th>
                    <?php endforeach; ?>
                </tr>
            </thead>
            <tbody id="legerTableBody" class="text-sm text-gray-700 dark:text-slate-300">
                <tr>
                    <td colspan="<?= 4 + count($list_ekskul) ?>" class="text-center py-12 text-gray-400 font-medium">
                        <?= lang('Admin/LegerEkskul.js_loading_table') ?>
                    </td>
                </tr>
            </tbody>
            <tfoot id="legerTableFoot" class="bg-gray-50/80 dark:bg-slate-900/40 font-bold border-t border-gray-100 dark:border-slate-700 transition-colors">
                </tfoot>
        </table>
    </div>
</div>

<div id="printHeader" style="display:none;" class="mb-8">
    <div class="text-center border-b-4 border-double border-gray-800 pb-4 mb-6">
        <h2 class="text-2xl font-black text-gray-900"><?= lang('Admin/LegerEkskul.print_header') ?></h2>
        <p class="text-lg font-bold text-gray-700" id="printSubtitle"></p>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    window.BASE_URL = "<?= rtrim(base_url(), '/') ?>/";
    const EKSKUL_LIST = <?= json_encode($list_ekskul) ?>;
    
    window.LANG = {
        loading_data: <?= json_encode(lang('Admin/LegerEkskul.js_loading_data')) ?>,
        no_data: <?= json_encode(lang('Admin/LegerEkskul.js_no_data')) ?>,
        err_server: <?= json_encode(lang('Admin/LegerEkskul.js_err_server')) ?>,
        total_active: <?= json_encode(lang('Admin/LegerEkskul.js_total_active')) ?>,
        class_prefix: <?= json_encode(lang('Admin/LegerEkskul.print_class_prefix')) ?>,
        exporting: <?= json_encode(lang('Admin/LegerEkskul.js_exporting')) ?>
    };
</script>
<script src="<?= base_url('assets/js/Admin/cetak-leger-ekskul.js') ?>?v=<?= time() ?>"></script>
<?= $this->endSection() ?>