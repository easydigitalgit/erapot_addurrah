<?= $this->extend('layout/main') ?>

<?= $this->section('title') ?>
<?= lang('Admin/InputNilai.page_title') ?> - Rapor Digital SMPIT Ad Durrah
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
  <link rel="stylesheet" href="<?= base_url('assets/css/Admin/input-nilai.css') ?>">
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="mb-6">
    <div class="flex items-center gap-2 text-sm text-gray-500 dark:text-slate-400 mb-3 transition-colors">
        <span><?= lang('Admin/InputNilai.breadcrumb') ?></span>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
        <span class="text-[<?= $color['warna_primary'] ?>] font-medium"><?= lang('Admin/InputNilai.page_title') ?></span>
    </div>
  
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
        <div>
           <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white mb-1 transition-colors"><?= lang('Admin/InputNilai.page_header') ?></h1>
           <p class="text-sm text-gray-600 dark:text-slate-400 transition-colors"><?= lang('Admin/InputNilai.page_desc') ?></p>
        </div>
        
        <div class="flex flex-wrap items-center gap-2">
            <button type="button" onclick="exportExcel()" class="px-4 py-2.5 bg-white dark:bg-slate-800 border border-gray-300 dark:border-slate-600 text-gray-700 dark:text-slate-300 font-medium rounded-xl hover:bg-gray-50 dark:hover:bg-slate-700 transition-colors flex items-center gap-2 shadow-sm outline-none">
                <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                <span class="hidden sm:inline"><?= lang('Admin/InputNilai.btn_export') ?></span>
            </button>
            <button onclick="simpanNilai()" class="px-6 py-2.5 bg-[<?= $color['warna_primary'] ?>] hover:bg-[<?= $color['warna_primary'] ?>]/90 text-white font-bold rounded-xl shadow-lg flex items-center gap-2 transition-transform transform hover:-translate-y-0.5 outline-none" style="box-shadow: 0 10px 15px -3px <?= $color['warna_primary'] ?>40;">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" /></svg>
                <span><?= lang('Admin/InputNilai.btn_save') ?></span>
            </button>
        </div>
    </div>
</div>

<div id="statsSection" class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6 hidden">
    <div class="bg-white dark:bg-slate-800 p-4 rounded-2xl border border-gray-100 dark:border-slate-700 shadow-sm flex items-center gap-4 transition-colors">
        <div class="w-12 h-12 rounded-full bg-blue-50 dark:bg-blue-900/30 flex items-center justify-center text-blue-600 dark:text-blue-400 transition-colors">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
        </div>
        <div>
            <p class="text-xs text-gray-500 dark:text-slate-400 font-bold uppercase tracking-wider transition-colors"><?= lang('Admin/InputNilai.stat_avg') ?></p>
            <p class="text-2xl font-black text-gray-800 dark:text-white transition-colors" id="statAvg">0</p>
        </div>
    </div>
    <div class="bg-white dark:bg-slate-800 p-4 rounded-2xl border border-gray-100 dark:border-slate-700 shadow-sm flex items-center gap-4 transition-colors">
        <div class="w-12 h-12 rounded-full bg-emerald-50 dark:bg-emerald-900/30 flex items-center justify-center text-emerald-600 dark:text-emerald-400 transition-colors">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        </div>
        <div>
            <p class="text-xs text-gray-500 dark:text-slate-400 font-bold uppercase tracking-wider transition-colors"><?= lang('Admin/InputNilai.stat_pass') ?></p>
            <p class="text-2xl font-black text-emerald-600 dark:text-emerald-400 transition-colors" id="statPass">0</p>
        </div>
    </div>
    <div class="bg-white dark:bg-slate-800 p-4 rounded-2xl border border-gray-100 dark:border-slate-700 shadow-sm flex items-center gap-4 transition-colors">
        <div class="w-12 h-12 rounded-full bg-red-50 dark:bg-red-900/30 flex items-center justify-center text-red-600 dark:text-red-400 transition-colors">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
        </div>
        <div>
            <p class="text-xs text-gray-500 dark:text-slate-400 font-bold uppercase tracking-wider transition-colors"><?= lang('Admin/InputNilai.stat_fail') ?></p>
            <p class="text-2xl font-black text-red-600 dark:text-red-400 transition-colors" id="statFail">0</p>
        </div>
    </div>
    <div class="bg-white dark:bg-slate-800 p-4 rounded-2xl border border-gray-100 dark:border-slate-700 shadow-sm flex items-center gap-4 transition-colors">
        <div class="w-12 h-12 rounded-full bg-purple-50 dark:bg-purple-900/30 flex items-center justify-center text-purple-600 dark:text-purple-400 transition-colors">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
        </div>
        <div>
            <p class="text-xs text-gray-500 dark:text-slate-400 font-bold uppercase tracking-wider transition-colors"><?= lang('Admin/InputNilai.stat_total') ?></p>
            <p class="text-2xl font-black text-gray-800 dark:text-white transition-colors" id="statTotal">0</p>
        </div>
    </div>
</div>

<div class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-gray-100 dark:border-slate-700 p-6 md:p-8 mb-6 relative overflow-hidden transition-colors">
    <div class="absolute top-0 right-0 -mt-4 -mr-4 w-32 h-32 bg-gradient-to-br from-[<?= $color['warna_primary'] ?>] to-transparent opacity-10 dark:opacity-20 rounded-full blur-3xl"></div>
    
    <form id="filterForm" class="grid grid-cols-1 md:grid-cols-12 gap-5 relative z-10">
       <div class="md:col-span-4">
        <label class="block text-[11px] font-black text-gray-500 dark:text-slate-400 uppercase tracking-widest mb-2 transition-colors"><?= lang('Admin/InputNilai.filter_class') ?></label>
        <select id="pilihKelas" class="w-full px-4 py-3.5 bg-gray-50 dark:bg-slate-700/50 border-2 border-gray-200 dark:border-slate-600 text-gray-900 dark:text-white rounded-xl focus:outline-none focus:ring-2 focus:ring-[<?= $color['warna_primary'] ?>] focus:border-[<?= $color['warna_primary'] ?>] transition-colors appearance-none cursor-pointer outline-none">
            <option value=""><?= lang('Admin/InputNilai.select_class') ?></option>
            <?php if(!empty($rombels)): ?>
                <?php 
                $current_tingkat = '';
                foreach($rombels as $r): 
                    if ($current_tingkat !== $r['tingkat']) {
                        if ($current_tingkat !== '') {
                            echo '</optgroup>';
                        }
                        $current_tingkat = $r['tingkat'];
                        echo '<optgroup label="' . lang('Admin/InputNilai.class_lbl') . ' ' . esc($current_tingkat) . '" class="text-gray-500 dark:text-slate-400 font-bold">';
                    }
                ?>
                    <option value="<?= $r['id'] ?>" class="text-gray-900 dark:text-white font-medium"><?= esc($current_tingkat) ?> - <?= esc($r['nama_rombel']) ?></option>
                <?php endforeach; ?>
                
                <?php 
                if ($current_tingkat !== '') {
                    echo '</optgroup>';
                } 
                ?>
            <?php else: ?>
                <option value="" disabled><?= lang('Admin/InputNilai.no_class_data') ?></option>
            <?php endif; ?>
        </select>
    </div>

    <div class="md:col-span-4 ">
        <label class="block text-[11px] font-black text-gray-500 dark:text-slate-400 uppercase tracking-widest mb-2 transition-colors"><?= lang('Admin/InputNilai.filter_subject') ?></label>
        <select id="pilihMapel" class="w-full px-4 py-3.5 bg-gray-50 dark:bg-slate-700/50 border-2 border-gray-200 dark:border-slate-600 text-gray-900 dark:text-white rounded-xl focus:outline-none focus:ring-2 focus:ring-[<?= $color['warna_primary'] ?>] focus:border-[<?= $color['warna_primary'] ?>] transition-colors appearance-none cursor-pointer outline-none">
            <option value=""><?= lang('Admin/InputNilai.select_subject') ?></option>
            <?php if(!empty($mapels)): ?>
                <?php foreach($mapels as $m): ?>
                    <option value="<?= $m['id'] ?>" class="text-gray-900 dark:text-white font-medium"><?= $m['nama_mapel'] ?></option>
                <?php endforeach; ?>
            <?php else: ?>
                <option value="" disabled><?= lang('Admin/InputNilai.no_subj_data') ?></option>
            <?php endif; ?>
        </select>
    </div>
    
        <div class="md:col-span-2">
            <label class="block text-[11px] font-black text-gray-500 dark:text-slate-400 uppercase tracking-widest mb-2 transition-colors"><?= lang('Admin/InputNilai.filter_kkm') ?></label>
            <input type="number" id="kkmValue" value="75" class="w-full px-4 py-3.5 bg-gray-50 dark:bg-slate-700/50 border-2 border-gray-200 dark:border-slate-600 text-gray-900 dark:text-white rounded-xl text-center font-black focus:outline-none focus:ring-2 focus:ring-[<?= $color['warna_primary'] ?>] focus:border-[<?= $color['warna_primary'] ?>] transition-colors outline-none" title="<?= lang('Admin/InputNilai.kkm_title') ?>">
        </div>
        
        <div class="md:col-span-2 flex items-end">
            <button type="button" onclick="loadSiswa()" class="w-full px-4 py-3.5 bg-[<?=  $color['warna_primary'] ?>] hover:bg-[<?=  $color['warna_primary'] ?>]/90 text-white font-bold rounded-xl transition-transform transform hover:-translate-y-0.5 shadow-lg flex justify-center items-center gap-2 outline-none" style="box-shadow: 0 10px 15px -3px <?= $color['warna_primary'] ?>40;">
               <span><?= lang('Admin/InputNilai.btn_show') ?></span>
               <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
            </button>
        </div>
    </form>
</div>

<div class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-gray-100 dark:border-slate-700 overflow-hidden relative min-h-[450px] transition-colors">
    
    <div id="progressSection" class="hidden px-6 pt-6 pb-4 border-b border-gray-100 dark:border-slate-700 transition-colors">
        <div class="flex justify-between items-end mb-2">
            <span class="text-[11px] font-black text-gray-500 dark:text-slate-400 uppercase tracking-widest transition-colors"><?= lang('Admin/InputNilai.class_progress') ?></span>
            <span class="text-sm font-black text-[<?= $color['warna_primary'] ?>]" id="progressText">0%</span>
        </div>
        <div class="w-full bg-gray-100 dark:bg-slate-700 rounded-full h-2 overflow-hidden transition-colors">
            <div id="progressBar" class="bg-[<?= $color['warna_primary'] ?>] h-full rounded-full transition-all duration-700" style="width: 0%"></div>
        </div>
    </div>

    <div class="overflow-x-auto custom-scrollbar">
        <table class="w-full text-sm text-left border-collapse min-w-max">
            <thead class="bg-gray-50 dark:bg-slate-900/50 text-gray-500 dark:text-slate-400 font-black uppercase tracking-widest text-[11px] border-b border-gray-100 dark:border-slate-700 transition-colors">
                <tr>
                    <th class="px-6 py-4 w-12 text-center"><?= lang('Admin/InputNilai.th_no') ?></th>
                    <th class="px-6 py-4 min-w-[200px]"><?= lang('Admin/InputNilai.th_student_name') ?></th>
                    <th class="px-4 py-4 text-center w-36"><?= lang('Admin/InputNilai.th_grade') ?></th>
                    <th class="px-4 py-4 text-center w-32"><?= lang('Admin/InputNilai.th_predicate') ?></th>
                    <th class="px-4 py-4 text-center w-32"><?= lang('Admin/InputNilai.th_status') ?></th>
                    <th class="px-4 py-4 text-left w-56"><?= lang('Admin/InputNilai.th_notes') ?></th>
                </tr>
            </thead>
            <tbody id="tableBody" class="divide-y divide-gray-100 dark:divide-slate-700/50 transition-colors">
                </tbody>
        </table>
    </div>
    
    <div id="emptyState" class="absolute inset-0 flex flex-col items-center justify-center text-center p-10 bg-white dark:bg-slate-800 z-10 transition-colors">
        <div class="w-24 h-24 bg-[<?= $color['warna_secondary'] ?>] dark:bg-[<?= $color['warna_primary'] ?>]/20 rounded-full flex items-center justify-center mb-5 shadow-sm animate-pulse transition-colors">
             <svg class="w-10 h-10 text-[<?= $color['warna_primary'] ?>]" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" /></svg>
        </div>
        <h3 class="text-xl font-black text-gray-900 dark:text-white mb-2 transition-colors"><?= lang('Admin/InputNilai.empty_title') ?></h3>
        <p class="text-sm font-medium text-gray-500 dark:text-slate-400 max-w-md mx-auto transition-colors leading-relaxed"><?= lang('Admin/InputNilai.empty_desc') ?></p>
    </div>
</div>

<?php 
function hexToRgb($hex) {
    $hex = str_replace("#", "", $hex);
    if(strlen($hex) == 3) {
        $r = hexdec(substr($hex,0,1).substr($hex,0,1));
        $g = hexdec(substr($hex,1,1).substr($hex,1,1));
        $b = hexdec(substr($hex,2,1).substr($hex,2,1));
    } else {
        $r = hexdec(substr($hex,0,2));
        $g = hexdec(substr($hex,2,2));
        $b = hexdec(substr($hex,4,2));
    }
    return "$r, $g, $b";
}
?>

<?= $this->endSection() ?>
<?= $this->section('scripts') ?>
<script>
    const API_URL = "<?= base_url('admin/input-nilai-siswa') ?>";
    window.LANG = {
        js_status_pass: "<?= lang('Admin/InputNilai.js_status_pass') ?: 'Tuntas' ?>",
        js_status_fail: "<?= lang('Admin/InputNilai.js_status_fail') ?: 'Remedial' ?>",
        js_swal_warn_title: "<?= lang('Admin/InputNilai.js_swal_warn_title') ?: 'Pilih Data Dulu' ?>",
        js_swal_warn_text: "<?= lang('Admin/InputNilai.js_swal_warn_text') ?: 'Mohon pilih Kelas dan Mata Pelajaran sebelum menampilkan data.' ?>",
        js_swal_btn_ok: "<?= lang('Admin/InputNilai.js_swal_btn_ok') ?: 'Siap, Mengerti' ?>",
        js_loading_fetch: "<?= lang('Admin/InputNilai.js_loading_fetch') ?: 'Sedang mengambil data siswa...' ?>",
        js_ph_grade: "<?= lang('Admin/InputNilai.js_ph_grade') ?: '-' ?>",
        js_ph_notes: "<?= lang('Admin/InputNilai.js_ph_notes') ?: 'Tuliskan catatan apresiasi/evaluasi...' ?>",
        js_no_students: "<?= lang('Admin/InputNilai.js_no_students') ?: 'Tidak ada siswa ditemukan di kelas ini.' ?>",
        js_swal_err_title: "<?= lang('Admin/InputNilai.js_swal_err_title') ?: 'Gagal Memuat' ?>",
        js_swal_err_text: "<?= lang('Admin/InputNilai.js_swal_err_text') ?: 'Terjadi kesalahan saat mengambil data. Coba refresh halaman.' ?>",
        js_err_load_table: "<?= lang('Admin/InputNilai.js_err_load_table') ?: 'Gagal memuat data.' ?>",
        js_swal_oops: "<?= lang('Admin/InputNilai.js_swal_oops') ?: 'Oops...' ?>",
        js_swal_sel_save: "<?= lang('Admin/InputNilai.js_swal_sel_save') ?: 'Mohon pilih Data Kelas dan Mata Pelajaran dulu ya!' ?>",
        js_swal_no_grade: "<?= lang('Admin/InputNilai.js_swal_no_grade') ?: 'Belum ada nilai' ?>",
        js_swal_fill_one: "<?= lang('Admin/InputNilai.js_swal_fill_one') ?: 'Silakan isi setidaknya satu nilai siswa sebelum menyimpan.' ?>",
        js_saving: "<?= lang('Admin/InputNilai.js_saving') ?: 'Menyimpan...' ?>",
        js_swal_success: "<?= lang('Admin/InputNilai.js_swal_success') ?: 'Alhamdulillah!' ?>",
        js_swal_fail_save: "<?= lang('Admin/InputNilai.js_swal_fail_save') ?: 'Gagal Menyimpan' ?>",
        js_swal_sys_err: "<?= lang('Admin/InputNilai.js_swal_sys_err') ?: 'Terjadi Kesalahan' ?>",
        js_swal_err_conn: "<?= lang('Admin/InputNilai.js_swal_err_conn') ?: 'Cek koneksi internet atau hubungi admin.' ?>",
        js_swal_sel_exp: "<?= lang('Admin/InputNilai.js_swal_sel_exp') ?: 'Pilih Kelas dan Mata Pelajaran dulu sebelum export ya!' ?>",
        js_swal_prep_data: "<?= lang('Admin/InputNilai.js_swal_prep_data') ?: 'Menyiapkan Data...' ?>",
        js_swal_prep_desc: "<?= lang('Admin/InputNilai.js_swal_prep_desc') ?: 'Mohon tunggu sebentar, file Excel sedang dibuat.' ?>"
    };
</script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="<?= base_url('assets/js/Admin/input-nilai.js') ?>?v=<?= time() ?>"></script>
<?= $this->endSection() ?>