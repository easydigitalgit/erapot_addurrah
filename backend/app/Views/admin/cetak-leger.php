<?= $this->extend('layout/main') ?>

<?= $this->section('title') ?>
  <?= lang('Admin/CetakLeger.page_title') ?> - Rapor Digital SMPIT Ad Durrah
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
  <link rel="stylesheet" href="<?= base_url('assets/css/Admin/cetak-leger.css') ?>">
<?= $this->endSection() ?>

<?= $this->section('content') ?>
     <div class="mb-6 no-print">
      <div class="flex items-center gap-2 text-sm text-gray-500 mb-3"><span><?= lang('Admin/CetakLeger.breadcrumb_report') ?></span>
       <svg class="w-4 h-4" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
       </svg><span class="text-[<?= $color['warna_primary'] ?>] font-medium"><?= lang('Admin/CetakLeger.page_title') ?></span>
      </div>
      <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 mb-6">
       <div>
        <h1 class="text-2xl md:text-3xl font-bold text-gray-900 mb-2 flex items-center gap-3">
         <svg class="w-8 h-8 text-[<?= $color['warna_primary'] ?>]" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
         </svg> <?= lang('Admin/CetakLeger.page_title') ?></h1>
        <p class="text-sm md:text-base text-gray-600"><?= lang('Admin/CetakLeger.page_subtitle') ?></p>
       </div>
       <div class="flex flex-wrap items-center gap-2"><button onclick="printLeger()" class="btn-primary bg-[<?= $color['warna_primary'] ?>]/80 hover:bg-[<?= $color['warna_primary'] ?>] shadow-md hover:shadow-lg hover:shadow-shadow-[<?= $color['warna_primary'] ?>]/20 hover:-translate-y-[2px] shadow-[<?= $color['warna_primary'] ?>]/20 transition-all duration-300">
         <svg class="w-4 h-4" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
         </svg><span><?= lang('Admin/CetakLeger.btn_print') ?></span> </button> <button onclick="downloadPDF()" class="btn-secondary">
         <svg class="w-4 h-4" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
         </svg><span><?= lang('Admin/CetakLeger.btn_pdf') ?></span> </button> <button onclick="exportExcel()" class="btn-secondary">
         <svg class="w-4 h-4" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
         </svg><span><?= lang('Admin/CetakLeger.btn_excel') ?></span> </button>
       </div>
      </div><div class="alert-info border-[<?= $color['warna_primary'] ?>]/80 bg-[<?= $color['warna_secondary'] ?>]">
       <div class="w-10 h-10 rounded-lg bg-[<?= $color['warna_secondary'] ?>] flex items-center justify-center flex-shrink-0">
        <svg class="w-5 h-5 text-[<?= $color['warna_primary'] ?>]" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
       </div>
       <div class="flex-1">
        <h4 class="font-bold text-[<?= $color['warna_primary'] ?>] mb-1"><?= lang('Admin/CetakLeger.info_title') ?></h4>
        <p class="text-sm text-[<?= $color['warna_primary'] ?>]/80"><?= lang('Admin/CetakLeger.info_desc') ?></p>
       </div>
      </div>
     </div><div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-6 no-print"><div class="lg:col-span-2 filter-card">
       <h3 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
        <svg class="w-5 h-5 text-[<?= $color['warna_primary'] ?>]" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" /></svg> <?= lang('Admin/CetakLeger.filter_title') ?></h3>
       
       <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
        <div>
            <label class="block text-xs font-semibold text-gray-700 mb-2"><?= lang('Admin/CetakLeger.academic_year') ?></label> 
            <select id="filter_tahun" class="select-custom" onchange="loadLegerData()"> 
                <?php foreach($tahun_ajaran as $ta): ?>
                    <option value="<?= $ta ?>"><?= $ta ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div>
            <label class="block text-xs font-semibold text-gray-700 mb-2"><?= lang('Admin/CetakLeger.semester') ?></label> 
            <select id="filter_semester" class="select-custom" onchange="loadLegerData()"> 
                <?php foreach($semester as $smt): ?>
                    <option value="<?= $smt ?>"><?= $smt ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div>
            <label class="block text-xs font-semibold text-gray-700 mb-2"><?= lang('Admin/CetakLeger.class') ?></label> 
            <select id="filter_rombel" class="select-custom" onchange="loadLegerData()"> 
                <?php foreach($list_rombel as $rombel): ?>
                    <option value="<?= $rombel['id'] ?>"><?= $rombel['nama_rombel'] ?></option>
                <?php endforeach; ?>
            </select>
        </div>
       </div>
<div class="border-t border-gray-200 pt-4 mt-4">
    <h4 class="font-semibold text-gray-800 mb-3 text-sm"><?= lang('Admin/CetakLeger.table_view') ?></h4>
    
    <div class="mb-4 flex items-center gap-3">
        <label class="text-sm font-medium text-gray-700"><?= lang('Admin/CetakLeger.sort_by') ?></label>
        <select id="sort_mode" onchange="changeSortMode()" class="px-3 py-1.5 bg-white border border-gray-300 rounded-lg text-sm focus:outline-none focus:border-[<?= $color['warna_primary'] ?>] focus:ring-1 focus:ring-[<?= $color['warna_primary'] ?>]">
            <option value="abjad"><?= lang('Admin/CetakLeger.sort_az') ?></option>
            <option value="rank"><?= lang('Admin/CetakLeger.sort_rank') ?></option>
        </select>
    </div>    
    <div class="flex flex-wrap gap-6">
        
        <label class="flex items-center gap-3 cursor-pointer group select-none">
            <div class="relative inline-flex items-center">
                <input type="checkbox" 
                       checked 
                       onchange="toggleNilaiAngka()" 
                       class="sr-only peer">
                
                <div class="w-11 h-6 bg-gray-200 rounded-full peer 
                            peer-checked:after:translate-x-full 
                            peer-checked:after:border-white 
                            after:content-[''] 
                            after:absolute after:top-[2px] after:left-[2px] 
                            after:bg-white 
                            after:border-gray-300 after:border after:rounded-full 
                            after:h-5 after:w-5 
                            after:transition-all 
                            peer-checked:bg-[<?= $color['warna_primary'] ?>]
                            transition-colors duration-200 ease-in-out">
                </div>
            </div>
            <span class="text-sm font-medium text-gray-700 group-hover:text-gray-900"><?= lang('Admin/CetakLeger.numeric_grade') ?></span>
        </label>

        <label class="flex items-center gap-3 cursor-pointer group select-none">
            <div class="relative inline-flex items-center">
                <input type="checkbox" 
                       checked 
                       onchange="togglePredikat()" 
                       class="sr-only peer">
                
                <div class="w-11 h-6 bg-gray-200 rounded-full peer 
                            peer-checked:after:translate-x-full 
                            peer-checked:after:border-white 
                            after:content-[''] 
                            after:absolute after:top-[2px] after:left-[2px] 
                            after:bg-white 
                            after:border-gray-300 after:border after:rounded-full 
                            after:h-5 after:w-5 
                            after:transition-all 
                            peer-checked:bg-[<?= $color['warna_primary'] ?>]
                            transition-colors duration-200 ease-in-out">
                </div>
            </div>
            <span class="text-sm font-medium text-gray-700 group-hover:text-gray-900"><?= lang('Admin/CetakLeger.predicate_grade') ?></span>
        </label>

    </div>
</div>
      </div><div class="info-card border-[<?= $color['warna_primary'] ?>]/80 bg-[<?= $color['warna_secondary'] ?>]">
       <h3 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
        <svg class="w-5 h-5 text-[<?= $color['warna_primary'] ?>]" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg> <?= lang('Admin/CetakLeger.class_info') ?></h3>
       <div class="space-y-3 text-sm">
        <div class="flex justify-between"><span class="text-gray-600"><?= lang('Admin/CetakLeger.class') ?></span> <span class="font-bold text-gray-900">7A</span>
        </div>
        <div class="flex justify-between"><span class="text-gray-600"><?= lang('Admin/CetakLeger.homeroom_teacher') ?></span> <span class="font-semibold text-gray-900">Ustadz Ahmad Sholeh</span>
        </div>
        <div class="flex justify-between"><span class="text-gray-600"><?= lang('Admin/CetakLeger.student_count') ?></span> <span class="font-bold text-emerald-700">32 <?= lang('Admin/CetakLeger.students') ?></span>
        </div>
        <div class="flex justify-between"><span class="text-gray-600"><?= lang('Admin/CetakLeger.curriculum') ?></span> <span class="font-semibold text-gray-900">K13 Revisi</span>
        </div>
        <div class="flex justify-between"><span class="text-gray-600"><?= lang('Admin/CetakLeger.status') ?></span> <span class="badge-locked">
          <svg class="w-3 h-3" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
          </svg> <?= lang('Admin/CetakLeger.locked') ?> </span>
        </div>
        <div class="flex justify-between"><span class="text-gray-600"><?= lang('Admin/CetakLeger.lock_date') ?></span> <span class="font-semibold text-gray-900 text-xs">15 Jan 2025</span>
        </div>
       </div>
      </div>
     </div><div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6 no-print">
    
    <div class="stat-card">
        <div class="flex items-center justify-between mb-2"><span class="text-xs font-semibold text-gray-600"><?= lang('Admin/CetakLeger.avg_class') ?></span>
            <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" /></svg>
        </div>
        <p id="stat_rata_kelas" class="text-3xl font-black text-emerald-700">0</p>
        <div class="progress-bar mt-3">
            <div id="bar_rata_kelas" class="progress-fill" style="width: 0%"></div>
        </div>
    </div>

    <div class="stat-card">
        <div class="flex items-center justify-between mb-2"><span class="text-xs font-semibold text-gray-600"><?= lang('Admin/CetakLeger.highest_grade') ?></span>
            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" /></svg>
        </div>
        <p id="stat_nilai_tertinggi" class="text-3xl font-black text-green-700">0</p>
        <p id="text_nilai_tertinggi" class="text-xs text-gray-500 mt-2 truncate">-</p>
    </div>

    <div class="stat-card">
        <div class="flex items-center justify-between mb-2"><span class="text-xs font-semibold text-gray-600"><?= lang('Admin/CetakLeger.lowest_grade') ?></span>
            <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6" /></svg>
        </div>
        <p id="stat_nilai_terendah" class="text-3xl font-black text-red-700">0</p>
        <p id="text_nilai_terendah" class="text-xs text-gray-500 mt-2 truncate">-</p>
    </div>

    <div class="stat-card">
        <div class="flex items-center justify-between mb-2"><span class="text-xs font-semibold text-gray-600"><?= lang('Admin/CetakLeger.completeness') ?></span>
            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
        </div>
        <p id="stat_ketuntasan" class="text-3xl font-black text-blue-700">0%</p>
        <div class="progress-bar mt-3">
            <div id="bar_ketuntasan" class="progress-fill" style="width: 0%; background: #2563EB;"></div>
        </div>
    </div>

</div>
     <div class="table-container">
      <div class="scroll-hint">
       <svg class="w-5 h-5" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7" />
       </svg><span><?= lang('Admin/CetakLeger.table_hint') ?></span>
      </div>
      <div class="leger-table-wrapper">
       <table class="leger-table">
       <thead class="text-white font-bold text-center text-sm">
          <tr style="height: 50px;">
            
            <th rowspan="2" class="border border-[<?= $color['warna_primary'] ?>] md:sticky md:left-0 z-50 bg-[<?= $color['warna_primary'] ?>]" style=" 
            z-index: 60; width: 40px;">
              <?= lang('Admin/CetakLeger.th_no') ?>
            </th>

            <th rowspan="2" class="border border-[<?= $color['warna_primary'] ?>] md:sticky md:left-[40px] z-50 bg-[<?= $color['warna_primary'] ?>]" style=" z-index: 60; width: 80px;">
              <?= lang('Admin/CetakLeger.th_nis') ?>
            </th>

            <th rowspan="2" class="border border-[<?= $color['warna_primary'] ?>] sticky left-0 md:left-[120px] bg-[<?= $color['warna_primary'] ?>]" style=" z-index: 60; min-width: 150px;">
              <?= lang('Admin/CetakLeger.th_name') ?>
            </th>

            <th colspan="2" class="border border-[<?= $color['warna_primary'] ?>] sticky top-0 bg-[<?= $color['warna_primary'] ?>]" style=" z-index: 40;">PAI</th>
            <th colspan="2" class="border border-[<?= $color['warna_primary'] ?>] sticky top-0 bg-[<?= $color['warna_primary'] ?>]" style=" z-index: 40;">B. Indo</th>
            <th colspan="2" class="border border-[<?= $color['warna_primary'] ?>] sticky top-0 bg-[<?= $color['warna_primary'] ?>]" style=" z-index: 40;">B. Arab</th>
            <th colspan="2" class="border border-[<?= $color['warna_primary'] ?>] sticky top-0 bg-[<?= $color['warna_primary'] ?>]" style=" z-index: 40;">B. Inggris</th>
            <th colspan="2" class="border border-[<?= $color['warna_primary'] ?>] sticky top-0 bg-[<?= $color['warna_primary'] ?>]" style=" z-index: 40;">Matematika</th>
            <th colspan="2" class="border border-[<?= $color['warna_primary'] ?>] sticky top-0 bg-[<?= $color['warna_primary'] ?>]" style=" z-index: 40;">IPA</th>
            <th colspan="2" class="border border-[<?= $color['warna_primary'] ?>] sticky top-0 bg-[<?= $color['warna_primary'] ?>]" style=" z-index: 40;">IPS</th>
            <th colspan="2" class="border border-[<?= $color['warna_primary'] ?>] sticky top-0 bg-[<?= $color['warna_primary'] ?>]" style=" z-index: 40;">Tahfidz</th>

            <th rowspan="2" class="border border-[<?= $color['warna_primary'] ?>] sticky top-0 bg-[<?= $color['warna_primary'] ?>]" style=" z-index: 40;"><?= lang('Admin/CetakLeger.th_avg') ?></th>
            <th rowspan="2" class="border border-[<?= $color['warna_primary'] ?>] sticky top-0 bg-[<?= $color['warna_primary'] ?>]" style=" z-index: 40;"><?= lang('Admin/CetakLeger.th_rank') ?></th>
          </tr>

          <tr style="height: 40px;">
            
            <th class="border border-[<?= $color['warna_primary'] ?>] sticky px-2 bg-[<?= $color['warna_primary'] ?>]" style=" top: 50px; z-index: 30;"><?= lang('Admin/CetakLeger.th_grade') ?></th>
            <th class="border border-[<?= $color['warna_primary'] ?>] sticky px-2 bg-[<?= $color['warna_primary'] ?>]" style=" top: 50px; z-index: 30;"><?= lang('Admin/CetakLeger.th_pred') ?></th>

            <th class="border border-[<?= $color['warna_primary'] ?>] sticky px-2 bg-[<?= $color['warna_primary'] ?>]" style=" top: 50px; z-index: 30;"><?= lang('Admin/CetakLeger.th_grade') ?></th>
            <th class="border border-[<?= $color['warna_primary'] ?>] sticky px-2 bg-[<?= $color['warna_primary'] ?>]" style=" top: 50px; z-index: 30;"><?= lang('Admin/CetakLeger.th_pred') ?></th>

            <th class="border border-[<?= $color['warna_primary'] ?>] sticky px-2 bg-[<?= $color['warna_primary'] ?>]" style=" top: 50px; z-index: 30;"><?= lang('Admin/CetakLeger.th_grade') ?></th>
            <th class="border border-[<?= $color['warna_primary'] ?>] sticky px-2 bg-[<?= $color['warna_primary'] ?>]" style=" top: 50px; z-index: 30;"><?= lang('Admin/CetakLeger.th_pred') ?></th>

            <th class="border border-[<?= $color['warna_primary'] ?>] sticky px-2 bg-[<?= $color['warna_primary'] ?>]" style=" top: 50px; z-index: 30;"><?= lang('Admin/CetakLeger.th_grade') ?></th>
            <th class="border border-[<?= $color['warna_primary'] ?>] sticky px-2 bg-[<?= $color['warna_primary'] ?>]" style=" top: 50px; z-index: 30;"><?= lang('Admin/CetakLeger.th_pred') ?></th>

            <th class="border border-[<?= $color['warna_primary'] ?>] sticky px-2 bg-[<?= $color['warna_primary'] ?>]" style=" top: 50px; z-index: 30;"><?= lang('Admin/CetakLeger.th_grade') ?></th>
            <th class="border border-[<?= $color['warna_primary'] ?>] sticky px-2 bg-[<?= $color['warna_primary'] ?>]" style=" top: 50px; z-index: 30;"><?= lang('Admin/CetakLeger.th_pred') ?></th>

            <th class="border border-[<?= $color['warna_primary'] ?>] sticky px-2 bg-[<?= $color['warna_primary'] ?>]" style=" top: 50px; z-index: 30;"><?= lang('Admin/CetakLeger.th_grade') ?></th>
            <th class="border border-[<?= $color['warna_primary'] ?>] sticky px-2 bg-[<?= $color['warna_primary'] ?>]" style=" top: 50px; z-index: 30;"><?= lang('Admin/CetakLeger.th_pred') ?></th>

            <th class="border border-[<?= $color['warna_primary'] ?>] sticky px-2 bg-[<?= $color['warna_primary'] ?>]" style=" top: 50px; z-index: 30;"><?= lang('Admin/CetakLeger.th_grade') ?></th>
            <th class="border border-[<?= $color['warna_primary'] ?>] sticky px-2 bg-[<?= $color['warna_primary'] ?>]" style=" top: 50px; z-index: 30;"><?= lang('Admin/CetakLeger.th_pred') ?></th>

            <th class="border border-[<?= $color['warna_primary'] ?>] sticky px-2 bg-[<?= $color['warna_primary'] ?>]" style=" top: 50px; z-index: 30;"><?= lang('Admin/CetakLeger.th_grade') ?></th>
            <th class="border border-[<?= $color['warna_primary'] ?>] sticky px-2 bg-[<?= $color['warna_primary'] ?>]" style=" top: 50px; z-index: 30;"><?= lang('Admin/CetakLeger.th_pred') ?></th>
          </tr>
        </thead>
        <tbody id="legerTableBody"></tbody>
       </table>
      </div>
     </div><div class="print-footer" style="display: none;">
      <div class="grid grid-cols-3 gap-8 text-center mb-8">
       <div class="signature-box">
        <p class="text-sm font-semibold mb-1"><?= lang('Admin/CetakLeger.homeroom_teacher') ?> 7A</p>
        <div class="signature-line"></div>
        <p class="text-sm font-bold">Ustadz Ahmad Sholeh</p>
        <p class="text-xs text-gray-600">NIP: 19850412001</p>
       </div>
       <div class="signature-box">
        <p class="text-sm font-semibold mb-1"><?= lang('Admin/CetakLeger.print_signature_1') ?></p>
        <div class="signature-line"></div>
        <p class="text-sm font-bold">Ustadzah Siti Maryam, M.Pd</p>
        <p class="text-xs text-gray-600">NIP: 19800315002</p>
       </div>
       <div class="signature-box">
        <p class="text-sm font-semibold mb-1"><?= lang('Admin/CetakLeger.print_signature_2') ?></p>
        <div class="signature-line"></div>
        <p class="text-sm font-bold">Dr. H. Abdullah, M.Pd</p>
        <p class="text-xs text-gray-600">NIP: 19750315001</p>
       </div>
      </div>
      <div class="text-center py-4 border-t-2 border-[<?= $color['warna_primary'] ?>]">
       <p class="text-xs text-gray-600 mb-1"><strong>SMPIT Ad Durrah</strong> • Tahun Ajaran 2024/2025 Semester Ganjil</p>
       <p class="text-xs text-gray-500"><?= lang('Admin/CetakLeger.print_official') ?> • No. Leger: LGR/VII-A/2025/001 • <?= lang('Admin/CetakLeger.status') ?>: <strong><?= mb_strtoupper(lang('Admin/CetakLeger.locked')) ?></strong></p>
      </div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    window.LANG = {
        js_loading: "<?= lang('Admin/CetakLeger.js_loading') ?: 'Memuat data dari database...' ?>",
        js_err_load: "<?= lang('Admin/CetakLeger.js_err_load') ?: 'Gagal memuat data' ?>",
        js_err_net: "<?= lang('Admin/CetakLeger.js_err_net') ?: 'Terjadi kesalahan jaringan' ?>",
        js_empty_data: "<?= lang('Admin/CetakLeger.js_empty_data') ?: 'Belum ada data nilai terkunci untuk kelas ini.' ?>",
        js_show_num: "<?= lang('Admin/CetakLeger.js_show_num') ?: 'Nilai angka ditampilkan' ?>",
        js_hide_num: "<?= lang('Admin/CetakLeger.js_hide_num') ?: 'Nilai angka disembunyikan' ?>",
        js_show_pred: "<?= lang('Admin/CetakLeger.js_show_pred') ?: 'Predikat ditampilkan' ?>",
        js_hide_pred: "<?= lang('Admin/CetakLeger.js_hide_pred') ?: 'Predikat disembunyikan' ?>",
        js_preparing: "<?= lang('Admin/CetakLeger.js_preparing') ?: 'Sedang disiapkan...' ?>",
        js_preparing_print: "<?= lang('Admin/CetakLeger.js_preparing_print') ?: 'Menyiapkan dokumen untuk dicetak...' ?>"
    };
</script>
<script src="<?= base_url('assets/js/Admin/cetak-leger.js') ?>"></script>
<?= $this->endSection() ?>