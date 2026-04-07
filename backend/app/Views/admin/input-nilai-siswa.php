<?= $this->extend('layout/main') ?>

<?= $this->section('title') ?>
Monitoring Nilai - Rapor Digital SMPIT Ad Durrah
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
    :root {
        --warna-scroll: <?= $color['warna_primary'] ?>;
    }
</style>
<link rel="stylesheet" href="<?= base_url('/assets/css/Admin/monitoring-input.css') ?>?v=<?= time() ?>">
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="mb-6">
    <div class="flex items-center gap-2 text-sm text-gray-500 dark:text-slate-400 mb-3 transition-colors">
        <span>Penilaian</span>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>
        <span class="text-[<?= $color['warna_primary'] ?>] font-medium">Monitoring Nilai Siswa</span>
    </div>

    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-gray-800 dark:text-white mb-2 transition-colors">Monitoring Nilai Siswa</h1>
            <p class="text-sm md:text-base text-gray-600 dark:text-slate-400 transition-colors">Pantau rekapitulasi rata-rata nilai Tugas, Ulangan, PTS, dan PAS siswa pada kelas tertentu.</p>
        </div>
        <div class="flex flex-wrap items-center gap-3">
            <button type="button" onclick="exportExcel()" class="px-5 py-2.5 bg-white dark:bg-slate-800 border border-gray-300 dark:border-slate-600 text-gray-700 dark:text-slate-300 font-bold rounded-xl hover:bg-gray-50 dark:hover:bg-slate-700 transition-all flex items-center gap-2 shadow-sm outline-none">
                <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <span>Export Rekap Excel</span>
            </button>
        </div>
    </div>

    <div class="bg-white dark:bg-slate-800 p-5 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700 mb-6 transition-colors">
        <form id="filterForm" class="grid grid-cols-1 md:grid-cols-12 gap-4 relative z-10">
            <div class="md:col-span-3">
                <label class="block text-[11px] font-black text-gray-500 dark:text-slate-400 uppercase tracking-widest mb-2 transition-colors">Tahun Ajaran</label>
                
                <select id="pilihTA" onchange="window.location.href='?ta=' + this.value" class="w-full px-4 py-3.5 bg-gray-50 dark:bg-slate-700/50 border-2 border-gray-200 dark:border-slate-600 text-gray-900 dark:text-white rounded-xl focus:outline-none focus:ring-2 focus:ring-[<?= $color['warna_primary'] ?>] transition-colors appearance-none cursor-pointer outline-none">
                    <?php if (!empty($tahun_ajaran)): ?>
                        <?php foreach ($tahun_ajaran as $ta): ?>
                            <option value="<?= $ta['id'] ?>" <?= ($ta['id'] == ($ta_terpilih ?? '')) ? 'selected' : '' ?>>
                                <?= esc($ta[$fTA]) ?> - <?= esc($ta['semester']) ?> <?= ($ta['status'] == 'Aktif') ? '(AKTIF)' : '' ?>
                            </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>

            <div class="md:col-span-3">
                <label class="block text-[11px] font-black text-gray-500 dark:text-slate-400 uppercase tracking-widest mb-2 transition-colors">Pilih Kelas</label>
                <select id="pilihKelas" class="w-full px-4 py-3.5 bg-gray-50 dark:bg-slate-700/50 border-2 border-gray-200 dark:border-slate-600 text-gray-900 dark:text-white rounded-xl focus:outline-none focus:ring-2 focus:ring-[<?= $color['warna_primary'] ?>] transition-colors appearance-none cursor-pointer outline-none">
                    <option value="">-- Pilih Kelas --</option>
                    <?php if (!empty($rombels)): ?>
                        <?php
                        $current_tingkat = '';
                        foreach ($rombels as $r):
                            if ($current_tingkat !== $r['tingkat']) {
                                if ($current_tingkat !== '') echo '</optgroup>';
                                $current_tingkat = $r['tingkat'];
                                echo '<optgroup label="Kelas ' . esc($current_tingkat) . '" class="text-gray-500 dark:text-slate-400 font-bold">';
                            }
                        ?>
                            <option value="<?= $r['id'] ?>" class="text-gray-900 dark:text-white font-medium" <?= ($r['id'] == ($kelas_terpilih ?? '')) ? 'selected' : '' ?>><?= esc($current_tingkat) ?> - <?= esc($r['nama_rombel']) ?></option>
                        <?php endforeach; ?>
                        <?php if ($current_tingkat !== '') echo '</optgroup>'; ?>
                    <?php endif; ?>
                </select>
            </div>

            <div class="md:col-span-3">
                <label class="block text-[11px] font-black text-gray-500 dark:text-slate-400 uppercase tracking-widest mb-2 transition-colors">Mata Pelajaran</label>
                <select id="pilihMapel" class="w-full px-4 py-3.5 bg-gray-50 dark:bg-slate-700/50 border-2 border-gray-200 dark:border-slate-600 text-gray-900 dark:text-white rounded-xl focus:outline-none focus:ring-2 focus:ring-[<?= $color['warna_primary'] ?>] transition-colors appearance-none cursor-pointer outline-none">
                    <option value="">-- Pilih Mapel --</option>
                    <?php if (!empty($mapels)): ?>
                        <?php foreach ($mapels as $m): ?>
                            <option value="<?= $m['id'] ?>" class="text-gray-900 dark:text-white font-medium" <?= ($m['id'] == ($mapel_terpilih ?? '')) ? 'selected' : '' ?>><?= $m['nama_mapel'] ?></option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>

            <div class="md:col-span-2">
                <label class="block text-[11px] font-black text-gray-500 dark:text-slate-400 uppercase tracking-widest mb-2 transition-colors">Kategori Nilai</label>
                <select id="pilihKategori" class="w-full px-4 py-3.5 bg-gray-50 dark:bg-slate-700/50 border-2 border-gray-200 dark:border-slate-600 text-gray-900 dark:text-white rounded-xl focus:outline-none focus:ring-2 focus:ring-[<?= $color['warna_primary'] ?>] transition-colors appearance-none cursor-pointer outline-none">
                    <option value="Akhir Semester" <?= ($kategori == 'Akhir Semester') ? 'selected' : '' ?>>Akhir Semester</option>
                    <option value="Tengah Semester" <?= ($kategori == 'Tengah Semester') ? 'selected' : '' ?>>Tengah Semester</option>
                </select>
            </div>

            <div class="md:col-span-1 flex items-end">
                <button type="button" onclick="loadSiswa()" class="w-full px-4 py-3.5 bg-[<?= $color['warna_primary'] ?>] hover:brightness-110 text-white font-bold rounded-xl transition-all shadow-md flex justify-center items-center outline-none" title="Tampilkan">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </button>
            </div>
        </form>
    </div>

    <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-gray-100 dark:border-slate-700 overflow-hidden relative min-h-[450px] transition-colors">
        <div class="overflow-x-auto custom-scrollbar">
            <table id="mainTable" class="w-full text-sm text-left border-collapse min-w-max hidden">
                <thead id="tableHead" class="bg-gray-50 dark:bg-slate-900/50 text-gray-500 dark:text-slate-400 font-black uppercase tracking-widest text-[11px] border-b border-gray-100 dark:border-slate-700 transition-colors">
                </thead>
                <tbody id="tableBody" class="divide-y divide-gray-100 dark:divide-slate-700/50 transition-colors">
                </tbody>
            </table>
        </div>

        <div id="emptyState" class="absolute inset-0 flex flex-col items-center justify-center text-center p-10 bg-white dark:bg-slate-800 z-10 transition-colors">
            <div class="w-24 h-24 bg-[<?= $color['warna_secondary'] ?>] dark:bg-[<?= $color['warna_primary'] ?>]/20 rounded-full flex items-center justify-center mb-5 shadow-sm transition-colors">
                <svg class="w-10 h-10 text-[<?= $color['warna_primary'] ?>]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                </svg>
            </div>
            <h3 class="text-xl font-black text-gray-900 dark:text-white mb-2 transition-colors">Siap Memantau Nilai Siswa?</h3>
            <p class="text-sm font-medium text-gray-500 dark:text-slate-400 max-w-md mx-auto transition-colors leading-relaxed">Silakan pilih Tahun Ajaran, Kelas, Mata Pelajaran, dan Kategori Nilai pada filter di atas, kemudian klik Icon Search (Pencarian).</p>
        </div>
    </div>
</div>

<div id="detailModal" class="fixed inset-0 z-[99999] hidden items-center justify-center p-4">
    <div class="absolute inset-0 bg-gray-950/80 backdrop-blur-sm transition-opacity" onclick="closeDetailModal()"></div>
    <div class="relative w-full max-w-2xl bg-white dark:bg-slate-800 rounded-3xl shadow-2xl flex flex-col border border-transparent dark:border-slate-700 transition-colors transform overflow-hidden z-10">
        <div class="bg-gradient-to-r from-[<?= $color['warna_primary'] ?>] to-[<?= $color['warna_primary'] ?>] px-6 py-5 flex items-center justify-between rounded-t-3xl transition-colors">
            <h2 class="text-xl font-black text-white" id="detailModalTitle">Rincian Nilai Siswa</h2>
            <button onclick="closeDetailModal()" class="text-white hover:bg-white/20 rounded-full p-2 transition-colors outline-none">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <div class="p-6 overflow-y-auto max-h-[60vh] custom-scrollbar">
            <table class="w-full text-sm text-left border-collapse border border-gray-200 dark:border-slate-700 rounded-xl overflow-hidden">
                <thead class="bg-gray-100 dark:bg-slate-900/80 text-gray-600 dark:text-slate-300 font-bold uppercase text-[11px] tracking-widest sticky top-0 z-10 shadow-sm">
                    <tr>
                        <th class="px-4 py-4 text-center border-b border-gray-200 dark:border-slate-700">Pertemuan</th>
                        <th class="px-4 py-4 text-center border-b border-gray-200 dark:border-slate-700">Nilai Tugas/Harian</th>
                        <th class="px-4 py-4 text-center border-b border-gray-200 dark:border-slate-700">Ulangan Harian</th>
                    </tr>
                </thead>
                <tbody id="detailModalBody" class="divide-y divide-gray-100 dark:divide-slate-700 bg-white dark:bg-slate-800 text-gray-800 dark:text-slate-300 font-medium">
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    const API_URL = "<?= base_url('admin/monitoring-nilai-siswa') ?>";
    const COLOR_PRIMARY = "<?= $color['warna_primary'] ?>";
    window.LANG = {
        js_swal_warn_title: "Pilih Data Dulu",
        js_swal_warn_text: "Semua filter harus diisi dengan lengkap sebelum mencari.",
        js_swal_btn_ok: "Siap, Mengerti",
        js_loading_fetch: "Sedang mengambil data siswa...",
        js_no_students: "Tidak ada siswa ditemukan atau data Master LM belum diatur.",
        js_swal_err_title: "Gagal Memuat",
        js_swal_err_text: "Terjadi kesalahan saat mengambil data. Coba refresh halaman.",
        js_err_load_table: "Gagal memuat data.",
        js_swal_oops: "Oops...",
        js_swal_sel_exp: "Pilih filter dengan lengkap sebelum export!",
        js_swal_prep_data: "Menyiapkan Data...",
        js_swal_prep_desc: "Mohon tunggu sebentar, file Excel sedang dibuat."
    };
</script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="<?= base_url('assets/js/Admin/input-nilai.js') ?>?v=<?= time() ?>"></script>
<?= $this->endSection() ?>