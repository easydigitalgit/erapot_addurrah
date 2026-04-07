<?= $this->extend('layout/main') ?>

<?= $this->section('styles') ?>
  <style>
    /* Injeksi Warna Dinamis dari Database */
    :root {
      --warna-scroll: <?= $color['warna_primary'] ?>; 
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

    /* CUSTOM PAGINATION STYLE */
    .pagination-container ul {
        display: flex;
        gap: 0.5rem;
        list-style: none;
        padding: 0;
        margin: 0;
    }
    .pagination-container li a, .pagination-container li span {
        display: flex;
        align-items: center;
        justify-content: center;
        min-width: 2rem;
        height: 2rem;
        padding: 0 0.5rem;
        border-radius: 0.75rem;
        font-size: 0.75rem;
        font-weight: 700;
        transition: all 0.2s;
        background: #fff;
        color: #64748b;
        border: 1px solid #e2e8f0;
        text-decoration: none;
    }
    .dark .pagination-container li a, .dark .pagination-container li span {
        background: #1e293b;
        color: #94a3b8;
        border-color: #334155;
    }
    .pagination-container li.active span {
        background: var(--warna-scroll);
        color: #fff;
        border-color: var(--warna-scroll);
        box-shadow: 0 4px 12px <?= $color['warna_primary'] ?>4d;
    }
    .pagination-container li a:hover {
        background: #f8fafc;
        border-color: var(--warna-scroll);
        color: var(--warna-scroll);
        transform: translateY(-1px);
    }
    .dark .pagination-container li a:hover {
        background: #334155;
    }
  </style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="p-4 sm:p-6 lg:p-8 bg-slate-50 dark:bg-slate-900 min-h-screen">
    
    <!-- HEADER & STATS SECTION -->
    <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-800 dark:text-white flex items-center gap-3">
                <div class="p-2.5 bg-[<?= $color['warna_primary'] ?>] rounded-xl shadow-lg shadow-[<?= $color['warna_primary'] ?>1a]">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <?= lang('Admin/RiwayatGuru.title') ?>
            </h1>
            <p class="mt-1 text-slate-500 dark:text-slate-400 text-sm italic"><?= lang('Admin/RiwayatGuru.subtitle') ?></p>
        </div>

        <div class="flex items-center gap-3">
            <div class="bg-white dark:bg-slate-800 p-3 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 flex items-center gap-4">
                <div class="text-right">
                    <p class="text-xs text-slate-400 uppercase font-semibold"><?= lang('Admin/RiwayatGuru.total_records') ?></p>
                    <p class="text-lg font-bold text-slate-700 dark:text-white"><?= number_format($stats['total_record']) ?></p>
                </div>
                <div class="w-px h-8 bg-slate-200 dark:bg-slate-700"></div>
                <div class="text-right">
                    <p class="text-xs text-slate-400 uppercase font-semibold"><?= lang('Admin/RiwayatGuru.active_teachers') ?></p>
                    <p class="text-lg font-bold text-slate-700 dark:text-white"><?= number_format($stats['total_guru']) ?></p>
                </div>
            </div>
        </div>
    </div>

    <!-- FILTER SECTION -->
    <div class="mb-6 bg-white dark:bg-slate-800 p-4 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700">
        <form action="<?= base_url('admin/riwayat-guru') ?>" method="GET" class="flex flex-wrap items-end gap-4">
            <div class="flex-1 min-w-[200px]">
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-200 mb-1.5"><?= lang('Admin/RiwayatGuru.filter_guru') ?></label>
                <div class="relative">
                    <select name="guru_id" class="w-full pl-4 pr-10 py-2.5 bg-slate-50 dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-xl text-sm focus:ring-2 focus:ring-[<?= $color['warna_primary'] ?>] focus:border-transparent outline-none transition-all cursor-pointer text-slate-700 dark:text-white">
                        <option value=""><?= lang('Admin/RiwayatGuru.all_teachers') ?></option>
                        <?php foreach($guruList as $g): ?>
                            <option value="<?= $g['id'] ?>" <?= ($filterGuruId == $g['id']) ? 'selected' : '' ?>><?= $g['nama_lengkap'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <!-- FILTER JABATAN DINAMIS -->
            <div class="flex-1 min-w-[200px]">
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-200 mb-1.5"><?= lang('Admin/RiwayatGuru.position') ?></label>
                <div class="relative">
                    <select name="jabatan" class="w-full pl-4 pr-10 py-2.5 bg-slate-50 dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-xl text-sm focus:ring-2 focus:ring-[<?= $color['warna_primary'] ?>] focus:border-transparent outline-none transition-all cursor-pointer text-slate-700 dark:text-white">
                        <option value=""><?= lang('Admin/RiwayatGuru.all_positions') ?></option>
                        <?php foreach($jabList as $j): ?>
                            <?php 
                                $label = $j['jabatan'];
                                if ($j['jabatan'] == 'Homeroom Teacher') $label = lang('Admin/RiwayatGuru.pos_walikelas');
                                if ($j['jabatan'] == 'Subject Teacher') $label = lang('Admin/RiwayatGuru.pos_gurumapel');
                            ?>
                            <option value="<?= $j['jabatan'] ?>" <?= ($filterJabatan == $j['jabatan']) ? 'selected' : '' ?>>
                                <?= $label ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <!-- FILTER TAHUN AJARAN -->
            <div class="flex-1 min-w-[200px]">
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-200 mb-1.5">Tahun Ajaran</label>
                <div class="relative">
                    <select name="tahun_ajaran_id" class="w-full pl-4 pr-10 py-2.5 bg-slate-50 dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-xl text-sm focus:ring-2 focus:ring-[<?= $color['warna_primary'] ?>] focus:border-transparent outline-none transition-all cursor-pointer text-slate-700 dark:text-white">
                        <option value="">Semua Tahun</option>
                        <?php foreach($taList as $ta): ?>
                            <option value="<?= $ta['id'] ?>" <?= ($filterTaId == $ta['id']) ? 'selected' : '' ?>>
                                TA <?= $ta['tahun'] ?> (<?= (strtolower($ta['semester']) == 'ganjil') ? 'Ganjil' : 'Genap' ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <button type="submit" class="px-6 py-2.5 bg-[<?= $color['warna_primary'] ?>] hover:bg-[<?= $color['warna_primary'] ?>dd] text-white font-semibold rounded-xl text-sm transition-all shadow-md flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <?= lang('Admin/RiwayatGuru.filter_btn') ?>
            </button>
            <?php if(!empty($filterGuruId) || !empty($filterJabatan) || !empty($filterTaId)): ?>
                <a href="<?= base_url('admin/riwayat-guru') ?>" class="px-6 py-2.5 bg-slate-200 dark:bg-slate-700 text-slate-600 dark:text-slate-300 font-semibold rounded-xl text-sm transition-all hover:bg-slate-300 dark:hover:bg-slate-600">
                    <?= lang('Admin/RiwayatGuru.reset_btn') ?>
                </a>
            <?php endif; ?>
        </form>
    </div>

    <!-- MAIN DATA TABLE / TIMELINE -->
    <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-xl shadow-slate-200/50 dark:shadow-none border border-slate-200 dark:border-slate-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-slate-50 dark:bg-slate-800/80 border-b border-slate-200 dark:border-slate-700">
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 dark:text-slate-300 uppercase tracking-wider"><?= lang('Admin/RiwayatGuru.date') ?></th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 dark:text-slate-300 uppercase tracking-wider"><?= lang('Admin/RiwayatGuru.teacher_name') ?></th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 dark:text-slate-300 uppercase tracking-wider"><?= lang('Admin/RiwayatGuru.academic_period') ?></th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 dark:text-slate-300 uppercase tracking-wider"><?= lang('Admin/RiwayatGuru.position') ?></th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 dark:text-slate-300 uppercase tracking-wider"><?= lang('Admin/RiwayatGuru.detail') ?></th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 dark:text-slate-300 uppercase tracking-wider text-center"><?= lang('Admin/RiwayatGuru.action') ?></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                    <?php if(empty($riwayat)): ?>
                        <tr>
                            <td colspan="6" class="px-6 py-20 text-center">
                                <div class="flex flex-col items-center">
                                    <div class="mb-4 p-4 bg-slate-50 dark:bg-slate-900 rounded-full">
                                        <svg class="w-12 h-12 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                    </div>
                                    <p class="text-slate-400 italic"><?= lang('Admin/RiwayatGuru.no_data') ?></p>
                                </div>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach($riwayat as $r): ?>
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/30 transition-colors group">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-semibold text-slate-700 dark:text-slate-200"><?= date('d M Y', strtotime($r['created_at'])) ?></div>
                                <div class="text-[10px] text-slate-400"><?= date('H:i', strtotime($r['created_at'])) ?> WIB</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-[<?= $color['warna_primary'] ?>] text-white flex items-center justify-center font-bold text-sm shadow-sm">
                                        <?= strtoupper(substr($r['nama_lengkap'], 0, 1)) ?>
                                    </div>
                                    <div>
                                        <div class="text-sm font-bold text-slate-800 dark:text-white"><?= $r['nama_lengkap'] ?></div>
                                        <div class="text-[11px] text-slate-400">NIK: <?= $r['nik'] ?? '-' ?></div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-3 py-1 rounded-full text-[11px] bg-sky-50 dark:bg-sky-900/30 text-sky-600 dark:text-sky-400 font-bold border border-sky-100 dark:border-sky-800">
                                    TA <?= $r['nama_tahun'] ?> (<?= (strtolower($r['semester']) == 'ganjil') ? lang('Admin/RiwayatGuru.semester_ganjil') : lang('Admin/RiwayatGuru.semester_genap') ?>)
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <?php 
                                    $jabatanDisplay = $r['jabatan'];
                                    $badgeClass = "bg-slate-50 dark:bg-slate-900/30 text-slate-600 dark:text-slate-400 border-slate-100 dark:border-slate-800";
                                    
                                    if ($r['jabatan'] == 'Wali Kelas' || $r['jabatan'] == 'Homeroom Teacher') {
                                        $jabatanDisplay = lang('Admin/RiwayatGuru.pos_walikelas');
                                        $badgeClass = "bg-green-50 dark:bg-green-900/30 text-green-600 dark:text-green-400 border-green-100 dark:border-green-800";
                                    } elseif ($r['jabatan'] == 'Guru Mapel' || $r['jabatan'] == 'Subject Teacher') {
                                        $jabatanDisplay = lang('Admin/RiwayatGuru.pos_gurumapel');
                                        $badgeClass = "bg-amber-50 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400 border-amber-100 dark:border-amber-800";
                                    }
                                ?>
                                <span class="px-3 py-1 rounded-lg text-[11px] font-bold border <?= $badgeClass ?>">
                                    <?= $jabatanDisplay ?>
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-slate-600 dark:text-slate-300 font-medium"><?= $r['keterangan'] ?></div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <button onclick="deleteRiwayat(<?= $r['id'] ?>)" class="p-2 text-slate-400 hover:text-rose-500 hover:bg-rose-50 dark:hover:bg-rose-900/30 rounded-lg transition-all opacity-0 group-hover:opacity-100">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- PAGINATION SECTION -->
        <?php if($pager): ?>
        <div class="px-6 py-4 bg-slate-50/50 dark:bg-slate-800/50 border-t border-slate-100 dark:border-slate-700">
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="text-xs text-slate-500 dark:text-slate-400 font-medium">
                    <?= lang('Admin/RiwayatGuru.showing_records', [count($riwayat), $stats['total_record']]) ?>
                </div>
                <div class="pagination-container">
                    <?= $pager ?>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- SWEETALERT 2 SCRIPT -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function deleteRiwayat(id) {
    Swal.fire({
        title: '<?= lang('Admin/RiwayatGuru.confirm_del') ?>',
        text: '<?= lang('Admin/RiwayatGuru.confirm_del_text') ?>',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#64748b',
        confirmButtonText: '<?= lang('Admin/RiwayatGuru.confirm_yes') ?>',
        cancelButtonText: '<?= lang('Admin/RiwayatGuru.confirm_no') ?>'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch('<?= base_url('admin/riwayat-guru/delete') ?>', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'id=' + id
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success') {
                    Swal.fire('<?= lang('Admin/RiwayatGuru.js_deleted') ?>', data.message, 'success').then(() => location.reload());
                } else {
                    Swal.fire('Gagal!', data.message, 'error');
                }
            });
        }
    });
}
</script>
<?= $this->endSection() ?>
