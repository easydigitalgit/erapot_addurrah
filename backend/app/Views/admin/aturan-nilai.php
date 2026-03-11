<?= $this->extend('layout/main') ?>

<?= $this->section('title') ?>
<?= lang('Admin/AturanNilai.page_title') ?> - Rapor Digital SMPIT Ad Durrah
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<link rel="stylesheet" href="<?= base_url('assets/css/Admin/aturan-nilai.css') ?>">
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="mb-6 w-full min-w-0">
  <div class="flex items-center gap-2 text-sm text-gray-500 dark:text-slate-400 mb-3 transition-colors">
      <span><?= lang('Admin/AturanNilai.breadcrumb_config') ?></span>
    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewbox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
    </svg>
    <span class="text-[<?= $color['warna_primary'] ?>] font-medium"><?= lang('Admin/AturanNilai.page_title') ?></span>
  </div>
  
  <div class="text-center mb-6 py-4 bg-gradient-to-r from-[<?= $color['warna_secondary'] ?>]/40 dark:from-[<?= $color['warna_primary'] ?>]/10 to-[<?= $color['warna_secondary'] ?>]/20 dark:to-slate-800 rounded-2xl border border-[<?= $color['warna_primary'] ?>]/10 dark:border-[<?= $color['warna_primary'] ?>]/30 shadow-sm transition-colors">
    <p class="text-3xl arabic-text text-[<?= $color['warna_primary'] ?>] mb-2 drop-shadow-sm"><?= lang('Admin/AturanNilai.bismillah') ?></p>
    <p class="text-xs text-gray-600 dark:text-slate-400 italic font-medium transition-colors"><?= lang('Admin/AturanNilai.bismillah_trans') ?></p>
  </div>
  
  <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
    <div>
      <h1 class="text-2xl md:text-3xl font-bold text-gray-800 dark:text-white mb-2 transition-colors"><?= lang('Admin/AturanNilai.page_title') ?></h1>
      <p class="text-sm md:text-base text-gray-600 dark:text-slate-400 transition-colors"><?= lang('Admin/AturanNilai.page_subtitle') ?></p>
    </div>
    <div class="flex flex-wrap items-center gap-2">
        <button onclick="showAddRuleModal()" class="px-5 py-2.5 bg-[<?= $color['warna_primary'] ?>]/90 hover:bg-[<?= $color['warna_primary'] ?>] text-white font-bold rounded-xl transition-transform transform hover:-translate-y-0.5 shadow-lg flex items-center gap-2 outline-none" style="box-shadow: 0 10px 15px -3px <?= $color['warna_primary'] ?>40;">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewbox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
        </svg>
        <span><?= lang('Admin/AturanNilai.btn_add_rule') ?></span> 
        </button> 
        <button onclick="resetToDefault()" class="px-5 py-2.5 bg-white dark:bg-slate-800 border border-gray-300 dark:border-slate-600 text-gray-700 dark:text-slate-300 font-bold rounded-xl hover:bg-gray-50 dark:hover:bg-slate-700 transition-colors shadow-sm flex items-center gap-2 outline-none">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewbox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
        </svg>
        <span class="hidden md:inline"><?= lang('Admin/AturanNilai.btn_reset') ?></span> 
        </button> 
        <button onclick="showHistory()" class="px-5 py-2.5 bg-white dark:bg-slate-800 border border-gray-300 dark:border-slate-600 text-gray-700 dark:text-slate-300 font-bold rounded-xl hover:bg-gray-50 dark:hover:bg-slate-700 transition-colors shadow-sm flex items-center gap-2 outline-none">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewbox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <span class="hidden md:inline"><?= lang('Admin/AturanNilai.btn_history') ?></span> 
        </button>
    </div>
  </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6 mb-6 w-full min-w-0">
  <div class="stat-card bg-white dark:bg-slate-800 p-5 rounded-3xl shadow-sm border border-transparent dark:border-slate-700 hover:border-[<?= $color['warna_primary'] ?>] dark:hover:border-[<?= $color['warna_primary'] ?>] transition-colors group">
    <div class="flex items-center justify-between mb-4">
      <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-emerald-500 to-emerald-600 flex items-center justify-center shadow-lg shadow-emerald-500/30 group-hover:scale-105 transition-transform">
        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewbox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
        </svg>
      </div><span class="text-[10px] font-black text-emerald-600 dark:text-emerald-400 uppercase tracking-widest transition-colors"><?= lang('Admin/AturanNilai.total_components') ?></span>
    </div>
    <p class="text-3xl font-black text-gray-900 dark:text-white mb-1 transition-colors">12</p>
    <p class="text-sm font-medium text-gray-500 dark:text-slate-400 transition-colors">3 <?= lang('Admin/AturanNilai.active_categories') ?></p>
  </div>
  <div class="stat-card bg-white dark:bg-slate-800 p-5 rounded-3xl shadow-sm border border-transparent dark:border-slate-700 hover:border-[<?= $color['warna_primary'] ?>] dark:hover:border-[<?= $color['warna_primary'] ?>] transition-colors group" id="totalBobotCard">
    <div class="flex items-center justify-between mb-4">
      <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-[<?= $color['warna_primary'] ?>] to-emerald-600 flex items-center justify-center shadow-lg group-hover:scale-105 transition-transform" style="box-shadow: 0 10px 15px -3px <?= $color['warna_primary'] ?>40;">
        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewbox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3" />
        </svg>
      </div><span class="text-[10px] font-black text-[<?= $color['warna_primary'] ?>] uppercase tracking-widest transition-colors"><?= lang('Admin/AturanNilai.total_weight') ?></span>
    </div>
    <p id="totalBobotValue" class="text-3xl font-black text-gray-900 dark:text-white mb-1 transition-colors">100%</p>
    <div id="bobotStatus"><span class="inline-flex px-2 py-0.5 bg-emerald-50 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400 font-bold text-[10px] rounded border border-emerald-200 dark:border-emerald-800/50 shadow-sm transition-colors">✔️ <?= lang('Admin/AturanNilai.js_valid') ?></span>
    </div>
  </div>
  <div class="stat-card bg-white dark:bg-slate-800 p-5 rounded-3xl shadow-sm border border-transparent dark:border-slate-700 hover:border-blue-500 transition-colors group">
    <div class="flex items-center justify-between mb-4">
      <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center shadow-lg shadow-blue-500/30 group-hover:scale-105 transition-transform">
        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewbox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
        </svg>
      </div><span class="text-[10px] font-black text-blue-600 dark:text-blue-400 uppercase tracking-widest transition-colors"><?= lang('Admin/AturanNilai.curriculum') ?></span>
    </div>
    <p class="text-2xl font-black text-gray-900 dark:text-white mb-1 transition-colors">Merdeka</p>
    <p class="text-sm font-medium text-gray-500 dark:text-slate-400 transition-colors">Kurikulum 2024</p>
  </div>
  <div class="stat-card bg-white dark:bg-slate-800 p-5 rounded-3xl shadow-sm border border-transparent dark:border-slate-700 hover:border-purple-500 transition-colors group">
    <div class="flex items-center justify-between mb-4">
      <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-purple-500 to-purple-600 flex items-center justify-center shadow-lg shadow-purple-500/30 group-hover:scale-105 transition-transform">
        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewbox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
        </svg>
      </div><span class="text-[10px] font-black text-purple-600 dark:text-purple-400 uppercase tracking-widest transition-colors"><?= lang('Admin/AturanNilai.validation_status') ?></span>
    </div>
    <p class="text-2xl font-black text-emerald-600 dark:text-emerald-400 mb-1 transition-colors"><?= lang('Admin/AturanNilai.validated') ?></p>
    <p class="text-sm font-medium text-gray-500 dark:text-slate-400 transition-colors"><?= lang('Admin/AturanNilai.ready_to_use') ?></p>
  </div>
</div>

<div id="warningAlert" class="bg-amber-50 dark:bg-amber-900/20 border border-amber-300 dark:border-amber-800/50 rounded-2xl p-5 mb-6 hidden transition-colors shadow-sm w-full">
  <div class="flex items-start gap-4">
    <div class="w-12 h-12 rounded-xl bg-amber-500 flex items-center justify-center flex-shrink-0 shadow-sm transition-colors">
      <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewbox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
      </svg>
    </div>
    <div class="flex-1">
      <h3 class="font-bold text-amber-900 dark:text-amber-400 text-lg mb-1 transition-colors"><?= lang('Admin/AturanNilai.warning_title') ?></h3>
      <p class="text-sm font-medium text-amber-800 dark:text-amber-300 mb-4 transition-colors leading-relaxed"><?= lang('Admin/AturanNilai.warning_desc') ?><span id="currentBobotWarning" class="font-bold ml-1 mr-1">100%</span><?= lang('Admin/AturanNilai.warning_desc_2') ?></p>
      <button onclick="autoBalance()" class="px-5 py-2.5 bg-amber-600 hover:bg-amber-700 text-white font-bold rounded-xl text-sm transition-transform transform hover:-translate-y-0.5 shadow-md shadow-amber-600/20 outline-none"> 
          <?= lang('Admin/AturanNilai.btn_auto_balance') ?> 
      </button>
    </div>
  </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6 w-full min-w-0">
  
  <div class="lg:col-span-2 min-w-0">
    <h2 class="text-xl font-bold text-gray-800 dark:text-white mb-5 flex items-center gap-2 transition-colors">
      <svg class="w-6 h-6 text-[<?= $color['warna_primary'] ?>]" fill="none" stroke="currentColor" viewbox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
      </svg> <?= lang('Admin/AturanNilai.weight_structure') ?>
    </h2>
    
    <div class="accordion-item bg-white dark:bg-slate-800 border-2 border-gray-100 dark:border-slate-700 rounded-2xl overflow-hidden mb-4 shadow-sm hover:border-[<?= $color['warna_primary'] ?>] dark:hover:border-[<?= $color['warna_primary'] ?>] transition-colors">
      <div class="accordion-header p-4 cursor-pointer bg-white dark:bg-slate-800 flex items-center justify-between transition-colors" onclick="toggleAccordion(this)">
        <div class="flex items-center gap-4">
          <div class="w-12 h-12 rounded-xl bg-[<?= $color['warna_primary'] ?>] flex items-center justify-center shadow-sm">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewbox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
            </svg>
          </div>
          <div>
            <h3 class="font-bold text-gray-900 dark:text-white text-base transition-colors"><?= lang('Admin/AturanNilai.academic') ?></h3>
            <p class="text-xs font-medium text-gray-500 dark:text-slate-400 mt-0.5 transition-colors"><?= lang('Admin/AturanNilai.academic_desc') ?></p>
          </div>
        </div>
        <div class="flex items-center gap-4">
            <span id="akademikTotal" class="text-lg font-black text-[<?= $color['warna_primary'] ?>]">60%</span>
          <svg class="w-5 h-5 text-gray-400 dark:text-slate-500 transition-transform accordion-icon" fill="none" stroke="currentColor" viewbox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
          </svg>
        </div>
      </div>
      <div class="accordion-content open bg-gray-50 dark:bg-slate-900/30 transition-colors border-t border-gray-100 dark:border-slate-700">
        <div class="p-5 space-y-4">
            
            <div class="flex items-center justify-between p-4 bg-white dark:bg-slate-800 rounded-xl border border-gray-200 dark:border-slate-700 shadow-sm transition-colors">
              <div class="flex items-center gap-4">
                  <label class="toggle-switch relative inline-flex items-center cursor-pointer"> 
                      <input type="checkbox" class="sr-only peer" checked onchange="updateTotal()"> 
                      <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-[<?= $color['warna_primary'] ?>]/30 rounded-full peer dark:bg-slate-600 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-500" style="background-color: <?= $color['warna_primary'] ?>;"></div>
                  </label>
                  <div>
                      <p class="font-bold text-gray-900 dark:text-white text-sm transition-colors"><?= lang('Admin/AturanNilai.knowledge') ?></p>
                      <p class="text-[11px] font-medium text-gray-500 dark:text-slate-400 mt-0.5 transition-colors"><?= lang('Admin/AturanNilai.knowledge_desc') ?></p>
                  </div>
              </div>
              <div class="relative w-24">
                  <input type="number" class="weight-input akademik-weight w-full pr-8 pl-3 py-2 bg-gray-50 dark:bg-slate-700 border-2 border-gray-200 dark:border-slate-600 text-gray-900 dark:text-white rounded-lg font-bold focus:outline-none focus:border-[<?= $color['warna_primary'] ?>] transition-colors outline-none text-right" 
                         data-kategori="akademik" 
                         data-sub="pengetahuan" 
                         value="<?= $bobot['akademik']['pengetahuan'] ?? 0 ?>" 
                         min="0" max="100" onchange="updateTotal()"> 
                  <span class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 dark:text-slate-400 font-bold text-sm">%</span>
              </div>
            </div>

            <div class="flex items-center justify-between p-4 bg-white dark:bg-slate-800 rounded-xl border border-gray-200 dark:border-slate-700 shadow-sm transition-colors">
              <div class="flex items-center gap-4">
                  <label class="toggle-switch relative inline-flex items-center cursor-pointer"> 
                      <input type="checkbox" class="sr-only peer" checked onchange="updateTotal()"> 
                      <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-[<?= $color['warna_primary'] ?>]/30 rounded-full peer dark:bg-slate-600 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-500" style="background-color: <?= $color['warna_primary'] ?>;"></div>
                  </label>
                  <div>
                      <p class="font-bold text-gray-900 dark:text-white text-sm transition-colors"><?= lang('Admin/AturanNilai.skills') ?></p>
                      <p class="text-[11px] font-medium text-gray-500 dark:text-slate-400 mt-0.5 transition-colors"><?= lang('Admin/AturanNilai.skills_desc') ?></p>
                  </div>
              </div>
              <div class="relative w-24">
                  <input type="number" class="weight-input akademik-weight w-full pr-8 pl-3 py-2 bg-gray-50 dark:bg-slate-700 border-2 border-gray-200 dark:border-slate-600 text-gray-900 dark:text-white rounded-lg font-bold focus:outline-none focus:border-[<?= $color['warna_primary'] ?>] transition-colors outline-none text-right" 
                         data-kategori="akademik" 
                         data-sub="keterampilan" 
                         value="<?= $bobot['akademik']['keterampilan'] ?? 0 ?>" 
                         min="0" max="100" onchange="updateTotal()"> 
                  <span class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 dark:text-slate-400 font-bold text-sm">%</span>
              </div>
            </div>

            <div class="flex items-center justify-between p-4 bg-white dark:bg-slate-800 rounded-xl border border-gray-200 dark:border-slate-700 shadow-sm transition-colors">
              <div class="flex items-center gap-4">
                  <label class="toggle-switch relative inline-flex items-center cursor-pointer"> 
                      <input type="checkbox" class="sr-only peer" checked onchange="updateTotal()"> 
                      <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-[<?= $color['warna_primary'] ?>]/30 rounded-full peer dark:bg-slate-600 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-500" style="background-color: <?= $color['warna_primary'] ?>;"></div>
                  </label>
                  <div>
                      <p class="font-bold text-gray-900 dark:text-white text-sm transition-colors"><?= lang('Admin/AturanNilai.pts') ?></p>
                      <p class="text-[11px] font-medium text-gray-500 dark:text-slate-400 mt-0.5 transition-colors"><?= lang('Admin/AturanNilai.pts_desc') ?></p>
                  </div>
              </div>
              <div class="relative w-24">
                  <input type="number" class="weight-input akademik-weight w-full pr-8 pl-3 py-2 bg-gray-50 dark:bg-slate-700 border-2 border-gray-200 dark:border-slate-600 text-gray-900 dark:text-white rounded-lg font-bold focus:outline-none focus:border-[<?= $color['warna_primary'] ?>] transition-colors outline-none text-right" 
                         data-kategori="akademik" 
                         data-sub="pts" 
                         value="<?= $bobot['akademik']['pts'] ?? 0 ?>" 
                         min="0" max="100" onchange="updateTotal()"> 
                  <span class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 dark:text-slate-400 font-bold text-sm">%</span>
              </div>
            </div>

            <div class="flex items-center justify-between p-4 bg-white dark:bg-slate-800 rounded-xl border border-gray-200 dark:border-slate-700 shadow-sm transition-colors">
              <div class="flex items-center gap-4">
                  <label class="toggle-switch relative inline-flex items-center cursor-pointer"> 
                      <input type="checkbox" class="sr-only peer" checked onchange="updateTotal()"> 
                      <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-[<?= $color['warna_primary'] ?>]/30 rounded-full peer dark:bg-slate-600 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-500" style="background-color: <?= $color['warna_primary'] ?>;"></div>
                  </label>
                  <div>
                      <p class="font-bold text-gray-900 dark:text-white text-sm transition-colors"><?= lang('Admin/AturanNilai.pas') ?></p>
                      <p class="text-[11px] font-medium text-gray-500 dark:text-slate-400 mt-0.5 transition-colors"><?= lang('Admin/AturanNilai.pas_desc') ?></p>
                  </div>
              </div>
              <div class="relative w-24">
                  <input type="number" class="weight-input akademik-weight w-full pr-8 pl-3 py-2 bg-gray-50 dark:bg-slate-700 border-2 border-gray-200 dark:border-slate-600 text-gray-900 dark:text-white rounded-lg font-bold focus:outline-none focus:border-[<?= $color['warna_primary'] ?>] transition-colors outline-none text-right" 
                         data-kategori="akademik" 
                         data-sub="pas" 
                         value="<?= $bobot['akademik']['pas'] ?? 0 ?>" 
                         min="0" max="100" onchange="updateTotal()"> 
                  <span class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 dark:text-slate-400 font-bold text-sm">%</span>
              </div>
            </div>
            
        </div>
      </div> 
    </div>

    <div class="accordion-item bg-white dark:bg-slate-800 border-2 border-gray-100 dark:border-slate-700 rounded-2xl overflow-hidden mb-4 shadow-sm hover:border-[<?= $color['warna_primary'] ?>] dark:hover:border-[<?= $color['warna_primary'] ?>] transition-colors">
      <div class="accordion-header p-4 cursor-pointer bg-white dark:bg-slate-800 flex items-center justify-between transition-colors" onclick="toggleAccordion(this)">
        <div class="flex items-center gap-4">
          <div class="w-12 h-12 rounded-xl bg-purple-500 flex items-center justify-center shadow-sm">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewbox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
            </svg>
          </div>
          <div>
            <h3 class="font-bold text-gray-900 dark:text-white text-base transition-colors"><?= lang('Admin/AturanNilai.character') ?></h3>
            <p class="text-xs font-medium text-gray-500 dark:text-slate-400 mt-0.5 transition-colors"><?= lang('Admin/AturanNilai.character_desc') ?></p>
          </div>
        </div>
        <div class="flex items-center gap-4"><span id="karakterTotal" class="text-lg font-black text-purple-600 dark:text-purple-400 transition-colors">20%</span>
          <svg class="w-5 h-5 text-gray-400 dark:text-slate-500 transition-transform accordion-icon" fill="none" stroke="currentColor" viewbox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
          </svg>
        </div>
      </div>
      <div class="accordion-content bg-gray-50 dark:bg-slate-900/30 transition-colors border-t border-gray-100 dark:border-slate-700">
        <div class="p-5 space-y-4">
            
          <div class="flex items-center justify-between p-4 bg-white dark:bg-slate-800 rounded-xl border border-gray-200 dark:border-slate-700 shadow-sm transition-colors">
              <div class="flex items-center gap-4">
                  <label class="toggle-switch relative inline-flex items-center cursor-pointer"> 
                      <input type="checkbox" class="sr-only peer" checked onchange="updateTotal()"> 
                      <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-purple-300 rounded-full peer dark:bg-slate-600 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-500 peer-checked:bg-purple-600"></div>
                  </label>
                  <div>
                      <p class="font-bold text-gray-900 dark:text-white text-sm transition-colors"><?= lang('Admin/AturanNilai.morals') ?></p>
                      <p class="text-[11px] font-medium text-gray-500 dark:text-slate-400 mt-0.5 transition-colors"><?= lang('Admin/AturanNilai.morals_desc') ?></p>
                  </div>
              </div>
              <div class="relative w-24">
                  <input type="number" class="weight-input karakter-weight w-full pr-8 pl-3 py-2 bg-gray-50 dark:bg-slate-700 border-2 border-gray-200 dark:border-slate-600 text-gray-900 dark:text-white rounded-lg font-bold focus:outline-none focus:border-purple-500 transition-colors outline-none text-right" 
                         data-kategori="karakter" 
                         data-sub="akhlak" 
                         value="<?= $bobot['karakter']['akhlak'] ?? 0 ?>" 
                         min="0" max="100" onchange="updateTotal()"> 
                  <span class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 dark:text-slate-400 font-bold text-sm">%</span>
              </div>
          </div>

          <div class="flex items-center justify-between p-4 bg-white dark:bg-slate-800 rounded-xl border border-gray-200 dark:border-slate-700 shadow-sm transition-colors">
              <div class="flex items-center gap-4">
                  <label class="toggle-switch relative inline-flex items-center cursor-pointer"> 
                      <input type="checkbox" class="sr-only peer" checked onchange="updateTotal()"> 
                      <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-purple-300 rounded-full peer dark:bg-slate-600 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-500 peer-checked:bg-purple-600"></div>
                  </label>
                  <div>
                      <p class="font-bold text-gray-900 dark:text-white text-sm transition-colors"><?= lang('Admin/AturanNilai.discipline') ?></p>
                      <p class="text-[11px] font-medium text-gray-500 dark:text-slate-400 mt-0.5 transition-colors"><?= lang('Admin/AturanNilai.discipline_desc') ?></p>
                  </div>
              </div>
              <div class="relative w-24">
                  <input type="number" class="weight-input karakter-weight w-full pr-8 pl-3 py-2 bg-gray-50 dark:bg-slate-700 border-2 border-gray-200 dark:border-slate-600 text-gray-900 dark:text-white rounded-lg font-bold focus:outline-none focus:border-purple-500 transition-colors outline-none text-right" 
                         data-kategori="karakter" 
                         data-sub="kedisiplinan" 
                         value="<?= $bobot['karakter']['kedisiplinan'] ?? 0 ?>" 
                         min="0" max="100" onchange="updateTotal()"> 
                  <span class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 dark:text-slate-400 font-bold text-sm">%</span>
              </div>
          </div>

          <div class="flex items-center justify-between p-4 bg-white dark:bg-slate-800 rounded-xl border border-gray-200 dark:border-slate-700 shadow-sm transition-colors">
              <div class="flex items-center gap-4">
                  <label class="toggle-switch relative inline-flex items-center cursor-pointer"> 
                      <input type="checkbox" class="sr-only peer" checked onchange="updateTotal()"> 
                      <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-purple-300 rounded-full peer dark:bg-slate-600 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-500 peer-checked:bg-purple-600"></div>
                  </label>
                  <div>
                      <p class="font-bold text-gray-900 dark:text-white text-sm transition-colors"><?= lang('Admin/AturanNilai.responsibility') ?></p>
                      <p class="text-[11px] font-medium text-gray-500 dark:text-slate-400 mt-0.5 transition-colors"><?= lang('Admin/AturanNilai.responsibility_desc') ?></p>
                  </div>
              </div>
              <div class="relative w-24">
                  <input type="number" class="weight-input karakter-weight w-full pr-8 pl-3 py-2 bg-gray-50 dark:bg-slate-700 border-2 border-gray-200 dark:border-slate-600 text-gray-900 dark:text-white rounded-lg font-bold focus:outline-none focus:border-purple-500 transition-colors outline-none text-right" 
                         data-kategori="karakter" 
                         data-sub="tanggung_jawab" 
                         value="<?= $bobot['karakter']['tanggung_jawab'] ?? 0 ?>" 
                         min="0" max="100" onchange="updateTotal()"> 
                  <span class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 dark:text-slate-400 font-bold text-sm">%</span>
              </div>
          </div>
          
        </div>
      </div>
    </div>

    <div class="accordion-item bg-white dark:bg-slate-800 border-2 border-gray-100 dark:border-slate-700 rounded-2xl overflow-hidden shadow-sm hover:border-[<?= $color['warna_primary'] ?>] dark:hover:border-[<?= $color['warna_primary'] ?>] transition-colors">
      <div class="accordion-header p-4 cursor-pointer bg-white dark:bg-slate-800 flex items-center justify-between transition-colors" onclick="toggleAccordion(this)">
        <div class="flex items-center gap-4">
          <div class="w-12 h-12 rounded-xl bg-emerald-500 flex items-center justify-center shadow-sm">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewbox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
            </svg>
          </div>
          <div>
            <h3 class="font-bold text-gray-900 dark:text-white text-base transition-colors"><?= lang('Admin/AturanNilai.islamic') ?></h3>
            <p class="text-xs font-medium text-gray-500 dark:text-slate-400 mt-0.5 transition-colors"><?= lang('Admin/AturanNilai.islamic_desc') ?></p>
          </div>
        </div>
        <div class="flex items-center gap-4"><span id="keislamanTotal" class="text-lg font-black text-emerald-600 dark:text-emerald-400 transition-colors">20%</span>
          <svg class="w-5 h-5 text-gray-400 dark:text-slate-500 transition-transform accordion-icon" fill="none" stroke="currentColor" viewbox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
          </svg>
        </div>
      </div>
      <div class="accordion-content bg-gray-50 dark:bg-slate-900/30 transition-colors border-t border-gray-100 dark:border-slate-700">
        <div class="p-5 space-y-4">
            
          <div class="flex items-center justify-between p-4 bg-white dark:bg-slate-800 rounded-xl border border-gray-200 dark:border-slate-700 shadow-sm transition-colors">
              <div class="flex items-center gap-4">
                  <label class="toggle-switch relative inline-flex items-center cursor-pointer"> 
                      <input type="checkbox" class="sr-only peer" checked onchange="updateTotal()"> 
                      <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-emerald-300 rounded-full peer dark:bg-slate-600 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-500 peer-checked:bg-emerald-600"></div>
                  </label>
                  <div>
                      <p class="font-bold text-gray-900 dark:text-white text-sm transition-colors"><?= lang('Admin/AturanNilai.tahfidz') ?></p>
                      <p class="text-[11px] font-medium text-gray-500 dark:text-slate-400 mt-0.5 transition-colors"><?= lang('Admin/AturanNilai.tahfidz_desc') ?></p>
                  </div>
              </div>
              <div class="relative w-24">
                  <input type="number" class="weight-input keislaman-weight w-full pr-8 pl-3 py-2 bg-gray-50 dark:bg-slate-700 border-2 border-gray-200 dark:border-slate-600 text-gray-900 dark:text-white rounded-lg font-bold focus:outline-none focus:border-emerald-500 transition-colors outline-none text-right" 
                         data-kategori="keislaman" 
                         data-sub="tahfidz" 
                         value="<?= $bobot['keislaman']['tahfidz'] ?? 0 ?>" 
                         min="0" max="100" onchange="updateTotal()"> 
                  <span class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 dark:text-slate-400 font-bold text-sm">%</span>
              </div>
          </div>
          
          <div class="flex items-center justify-between p-4 bg-white dark:bg-slate-800 rounded-xl border border-gray-200 dark:border-slate-700 shadow-sm transition-colors">
              <div class="flex items-center gap-4">
                  <label class="toggle-switch relative inline-flex items-center cursor-pointer"> 
                      <input type="checkbox" class="sr-only peer" checked onchange="updateTotal()"> 
                      <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-emerald-300 rounded-full peer dark:bg-slate-600 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-500 peer-checked:bg-emerald-600"></div>
                  </label>
                  <div>
                      <p class="font-bold text-gray-900 dark:text-white text-sm transition-colors"><?= lang('Admin/AturanNilai.worship') ?></p>
                      <p class="text-[11px] font-medium text-gray-500 dark:text-slate-400 mt-0.5 transition-colors"><?= lang('Admin/AturanNilai.worship_desc') ?></p>
                  </div>
              </div>
              <div class="relative w-24">
                  <input type="number" class="weight-input keislaman-weight w-full pr-8 pl-3 py-2 bg-gray-50 dark:bg-slate-700 border-2 border-gray-200 dark:border-slate-600 text-gray-900 dark:text-white rounded-lg font-bold focus:outline-none focus:border-emerald-500 transition-colors outline-none text-right" 
                         data-kategori="keislaman" 
                         data-sub="ibadah" 
                         value="<?= $bobot['keislaman']['ibadah'] ?? 0 ?>" 
                         min="0" max="100" onchange="updateTotal()"> 
                  <span class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 dark:text-slate-400 font-bold text-sm">%</span>
              </div>
          </div>
          
          <div class="flex items-center justify-between p-4 bg-white dark:bg-slate-800 rounded-xl border border-gray-200 dark:border-slate-700 shadow-sm transition-colors">
              <div class="flex items-center gap-4">
                  <label class="toggle-switch relative inline-flex items-center cursor-pointer"> 
                      <input type="checkbox" class="sr-only peer" checked onchange="updateTotal()"> 
                      <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-emerald-300 rounded-full peer dark:bg-slate-600 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-500 peer-checked:bg-emerald-600"></div>
                  </label>
                  <div>
                      <p class="font-bold text-gray-900 dark:text-white text-sm transition-colors"><?= lang('Admin/AturanNilai.islamic_morals') ?></p>
                      <p class="text-[11px] font-medium text-gray-500 dark:text-slate-400 mt-0.5 transition-colors"><?= lang('Admin/AturanNilai.islamic_morals_desc') ?></p>
                  </div>
              </div>
              <div class="relative w-24">
                  <input type="number" class="weight-input keislaman-weight w-full pr-8 pl-3 py-2 bg-gray-50 dark:bg-slate-700 border-2 border-gray-200 dark:border-slate-600 text-gray-900 dark:text-white rounded-lg font-bold focus:outline-none focus:border-emerald-500 transition-colors outline-none text-right" 
                         data-kategori="keislaman" 
                         data-sub="akhlak_islami" 
                         value="<?= $bobot['keislaman']['akhlak_islami'] ?? 0 ?>" 
                         min="0" max="100" onchange="updateTotal()"> 
                  <span class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 dark:text-slate-400 font-bold text-sm">%</span>
              </div>
          </div>
          
        </div>
      </div>
    </div>
  </div>
  
  <div class="min-w-0">
    <h2 class="text-xl font-bold text-gray-800 dark:text-white mb-5 flex items-center gap-2 transition-colors">
      <svg class="w-6 h-6 text-[<?= $color['warna_primary'] ?>]" fill="none" stroke="currentColor" viewbox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z" />
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z" />
      </svg> <?= lang('Admin/AturanNilai.preview_formula') ?>
    </h2>
    
    <div class="bg-white dark:bg-slate-800 rounded-3xl border border-gray-100 dark:border-slate-700 p-6 mb-6 shadow-sm transition-colors">
      <h3 class="font-black text-gray-800 dark:text-white mb-6 text-center text-sm uppercase tracking-widest transition-colors"><?= lang('Admin/AturanNilai.weight_distribution') ?></h3>
      
      <div class="pie-chart-container mb-6 relative w-48 h-48 mx-auto">
        <svg viewbox="0 0 200 200" class="w-full h-full drop-shadow-md">
            <circle cx="100" cy="100" r="80" fill="transparent" stroke="#3B82F6" stroke-width="40" stroke-dasharray="502.7 502.7" transform="rotate(-90 100 100)" /> 
            <circle id="pieKarakter" cx="100" cy="100" r="80" fill="transparent" stroke="#A855F7" stroke-width="40" stroke-dasharray="0 502.7" stroke-dashoffset="0" transform="rotate(-90 100 100)" style="transition: stroke-dasharray 0.5s ease;" /> 
            <circle id="pieKeislaman" cx="100" cy="100" r="80" fill="transparent" stroke="#10B981" stroke-width="40" stroke-dasharray="0 502.7" stroke-dashoffset="0" transform="rotate(-90 100 100)" style="transition: stroke-dasharray 0.5s ease, stroke-dashoffset 0.5s ease;" />
            <circle cx="100" cy="100" r="50" class="fill-white dark:fill-slate-800 transition-colors" /> 
            <text id="pieTotalText" x="100" y="95" text-anchor="middle" font-size="28" font-weight="900" class="fill-gray-900 dark:fill-white transition-colors">100%</text> 
            <text x="100" y="115" text-anchor="middle" font-size="10" font-weight="bold" class="fill-gray-500 dark:fill-slate-400 uppercase tracking-widest transition-colors"><?= lang('Admin/AturanNilai.total_weight') ?></text>
        </svg>
      </div>
      
      <div class="space-y-3">
        <div class="flex items-center justify-between p-3 bg-blue-50 dark:bg-blue-900/20 rounded-xl border border-blue-100 dark:border-blue-800/50 transition-colors">
          <div class="flex items-center gap-3">
              <div class="w-3 h-3 rounded-full bg-blue-500 shadow-[0_0_8px_#3b82f6]"></div>
              <p class="text-xs font-bold text-blue-900 dark:text-blue-300 transition-colors"><?= lang('Admin/AturanNilai.academic') ?></p>
          </div>
          <p id="labelAkademikValue" class="text-sm font-black text-blue-700 dark:text-blue-400 transition-colors">60%</p>
        </div>
        <div class="flex items-center justify-between p-3 bg-purple-50 dark:bg-purple-900/20 rounded-xl border border-purple-100 dark:border-purple-800/50 transition-colors">
          <div class="flex items-center gap-3">
              <div class="w-3 h-3 rounded-full bg-purple-500 shadow-[0_0_8px_#a855f7]"></div>
              <p class="text-xs font-bold text-purple-900 dark:text-purple-300 transition-colors"><?= lang('Admin/AturanNilai.character') ?></p>
          </div>
          <p id="labelKarakterValue" class="text-sm font-black text-purple-700 dark:text-purple-400 transition-colors">20%</p>
        </div>
        <div class="flex items-center justify-between p-3 bg-emerald-50 dark:bg-emerald-900/20 rounded-xl border border-emerald-100 dark:border-emerald-800/50 transition-colors">
          <div class="flex items-center gap-3">
              <div class="w-3 h-3 rounded-full bg-emerald-500 shadow-[0_0_8px_#10b981]"></div>
              <p class="text-xs font-bold text-emerald-900 dark:text-emerald-300 transition-colors"><?= lang('Admin/AturanNilai.islamic') ?></p>
          </div>
          <p id="labelKeislamanValue" class="text-sm font-black text-emerald-700 dark:text-emerald-400 transition-colors">20%</p>
        </div>
      </div>
    </div>
    
    <div class="bg-gradient-to-br from-[<?= $color['warna_secondary'] ?>] to-[<?= $color['warna_secondary'] ?>]/50 dark:from-[<?= $color['warna_primary'] ?>]/20 dark:to-slate-800 rounded-3xl border border-[<?= $color['warna_primary'] ?>]/30 p-6 mb-6 shadow-sm transition-colors">
      <h3 class="font-black text-[<?= $color['warna_primary'] ?>] mb-4 text-sm uppercase tracking-widest flex items-center gap-2">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewbox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
        </svg> <?= lang('Admin/AturanNilai.formula_calc') ?>
      </h3>
      <div class="bg-white dark:bg-slate-900 rounded-xl p-5 border border-[<?= $color['warna_primary'] ?>]/30 dark:border-[<?= $color['warna_primary'] ?>]/50 shadow-inner transition-colors">
        <p class="text-[11px] font-bold text-gray-500 dark:text-slate-400 mb-2 font-mono uppercase tracking-wider transition-colors"><?= lang('Admin/AturanNilai.final_grade') ?> =</p>
        <p class="text-sm font-black text-gray-800 dark:text-white font-mono leading-loose transition-colors">
            <span class="text-blue-600 dark:text-blue-400" id="formulaAkademik">(Akademik × 60%)</span> +<br>
            <span class="text-purple-600 dark:text-purple-400" id="formulaKarakter">(Karakter × 20%)</span> +<br>
            <span class="text-emerald-600 dark:text-emerald-400" id="formulaKeislaman">(Keislaman × 20%)</span>
        </p>
      </div>
    </div>
    
  </div>
</div> <div class="mb-6 w-full min-w-0">
  <h2 class="text-xl font-bold text-gray-800 dark:text-white mb-5 flex items-center gap-2 transition-colors">
    <svg class="w-6 h-6 text-[<?= $color['warna_primary'] ?>]" fill="none" stroke="currentColor" viewbox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
    </svg> <?= lang('Admin/AturanNilai.grading_rules') ?>
  </h2>
  
  <div class="bg-white dark:bg-slate-800 rounded-3xl border border-gray-100 dark:border-slate-700 shadow-sm overflow-hidden transition-colors w-full min-w-0">
   <div class="overflow-x-auto custom-scrollbar w-full">
    <table class="w-full text-left border-collapse min-w-max">
      <thead class="bg-[<?= $color['warna_primary'] ?>] text-white">
        <tr>
          <th class="px-6 py-4 font-black text-[11px] uppercase tracking-widest"><?= lang('Admin/AturanNilai.th_range') ?></th>
          <th class="px-6 py-4 font-black text-[11px] uppercase tracking-widest"><?= lang('Admin/AturanNilai.th_predicate') ?></th>
          <th class="px-6 py-4 font-black text-[11px] uppercase tracking-widest w-96"><?= lang('Admin/AturanNilai.th_desc') ?></th>
          <th class="px-6 py-4 font-black text-[11px] uppercase tracking-widest text-center"><?= lang('Admin/AturanNilai.th_status') ?></th>
          <th class="px-6 py-4 font-black text-[11px] uppercase tracking-widest text-center"><?= lang('Admin/AturanNilai.th_action') ?></th>
        </tr>
      </thead>
      <tbody class="divide-y divide-gray-100 dark:divide-slate-700/50 transition-colors">
          <?php if(empty($list_aturan)): ?>
              <tr><td colspan="5" class="text-center p-12 text-gray-500 dark:text-slate-400 font-medium transition-colors"><?= lang('Admin/AturanNilai.empty_rules') ?></td></tr>
          <?php else: ?>
              <?php foreach ($list_aturan as $at): ?>
              <tr class="hover:bg-gray-50 dark:hover:bg-slate-700/30 transition-colors group">
                  <td class="px-6 py-4 font-bold text-gray-900 dark:text-white transition-colors">
                      <span class="bg-gray-100 dark:bg-slate-700 px-3 py-1 rounded-lg border border-gray-200 dark:border-slate-600 transition-colors"><?= $at['nilai_min'] ?> - <?= $at['nilai_max'] ?></span>
                  </td>
                  <td class="px-6 py-4">
                      <span class="inline-flex items-center justify-center w-10 h-10 bg-<?= $at['warna_badge'] ?>-100 dark:bg-<?= $at['warna_badge'] ?>-900/30 text-<?= $at['warna_badge'] ?>-700 dark:text-<?= $at['warna_badge'] ?>-400 rounded-xl font-black text-lg border border-<?= $at['warna_badge'] ?>-200 dark:border-<?= $at['warna_badge'] ?>-800/50 shadow-sm group-hover:scale-110 transition-transform">
                          <?= $at['predikat'] ?>
                      </span>
                  </td>
                  <td class="px-6 py-4 text-sm font-medium text-gray-600 dark:text-slate-300 leading-relaxed transition-colors"><?= $at['deskripsi_predikat'] ?></td>
                  <td class="px-6 py-4 text-center">
                      <?php if($at['is_active']): ?>
                          <span class="inline-flex px-3 py-1 bg-emerald-50 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 text-[10px] font-black uppercase tracking-wider rounded border border-emerald-200 dark:border-emerald-800/50 shadow-sm transition-colors"><?= lang('Admin/AturanNilai.badge_active') ?></span>
                      <?php else: ?>
                          <span class="inline-flex px-3 py-1 bg-red-50 dark:bg-red-900/30 text-red-600 dark:text-red-400 text-[10px] font-black uppercase tracking-wider rounded border border-red-200 dark:border-red-800/50 shadow-sm transition-colors"><?= lang('Admin/AturanNilai.badge_inactive') ?></span>
                      <?php endif; ?>
                  </td>
                  <td class="px-6 py-4 text-center">
                      <div class="flex items-center justify-center gap-2">
                          <button class="p-2.5 bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400 hover:bg-red-600 hover:text-white dark:hover:bg-red-600 dark:hover:text-white rounded-xl transition-all shadow-sm outline-none transform hover:scale-105" title="<?= lang('Admin/AturanNilai.btn_delete') ?>">
                              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                   <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                              </svg> 
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
</div>

<div class="bg-gradient-to-r from-purple-50 dark:from-purple-900/20 to-white dark:to-slate-800 rounded-3xl border border-purple-200 dark:border-purple-800/50 p-6 md:p-8 mb-8 shadow-sm transition-colors relative overflow-hidden w-full min-w-0">
  <div class="absolute top-0 right-0 w-48 h-48 bg-purple-500 opacity-5 dark:opacity-10 rounded-full blur-3xl transform translate-x-1/2 -translate-y-1/2"></div>
  
  <div class="flex items-start gap-5 relative z-10">
    <div class="w-16 h-16 rounded-2xl bg-purple-100 dark:bg-purple-900/40 border border-purple-200 dark:border-purple-800/50 flex items-center justify-center flex-shrink-0 shadow-sm transition-colors mt-1">
      <svg class="w-8 h-8 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewbox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
      </svg>
    </div>
    <div class="flex-1 min-w-0">
      <h3 class="font-black text-purple-900 dark:text-purple-300 text-xl mb-2 transition-colors"><?= lang('Admin/AturanNilai.sync_impact') ?></h3>
      <p class="text-sm font-medium text-purple-800 dark:text-purple-200 mb-5 leading-relaxed transition-colors"><?= lang('Admin/AturanNilai.impact_desc') ?></p>
      
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="flex items-start gap-4 bg-white/80 dark:bg-slate-900/50 backdrop-blur-sm rounded-2xl p-4 border border-purple-100 dark:border-purple-800/30 shadow-sm transition-colors hover:shadow-md hover:-translate-y-0.5 transform duration-300">
          <div class="mt-1 bg-purple-100 dark:bg-purple-900/50 rounded-full p-1 border border-purple-200 dark:border-purple-800">
              <svg class="w-4 h-4 text-purple-600 dark:text-purple-400 flex-shrink-0" fill="none" stroke="currentColor" viewbox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
          </div>
          <div>
            <p class="text-sm font-bold text-gray-900 dark:text-white transition-colors"><?= lang('Admin/AturanNilai.impact_1_title') ?></p>
            <p class="text-[11px] font-medium text-gray-600 dark:text-slate-400 mt-1 leading-relaxed transition-colors"><?= lang('Admin/AturanNilai.impact_1_desc') ?></p>
          </div>
        </div>
        <div class="flex items-start gap-4 bg-white/80 dark:bg-slate-900/50 backdrop-blur-sm rounded-2xl p-4 border border-purple-100 dark:border-purple-800/30 shadow-sm transition-colors hover:shadow-md hover:-translate-y-0.5 transform duration-300">
          <div class="mt-1 bg-purple-100 dark:bg-purple-900/50 rounded-full p-1 border border-purple-200 dark:border-purple-800">
              <svg class="w-4 h-4 text-purple-600 dark:text-purple-400 flex-shrink-0" fill="none" stroke="currentColor" viewbox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
          </div>
          <div>
            <p class="text-sm font-bold text-gray-900 dark:text-white transition-colors"><?= lang('Admin/AturanNilai.impact_2_title') ?></p>
            <p class="text-[11px] font-medium text-gray-600 dark:text-slate-400 mt-1 leading-relaxed transition-colors"><?= lang('Admin/AturanNilai.impact_2_desc') ?></p>
          </div>
        </div>
        <div class="flex items-start gap-4 bg-white/80 dark:bg-slate-900/50 backdrop-blur-sm rounded-2xl p-4 border border-purple-100 dark:border-purple-800/30 shadow-sm transition-colors hover:shadow-md hover:-translate-y-0.5 transform duration-300">
          <div class="mt-1 bg-purple-100 dark:bg-purple-900/50 rounded-full p-1 border border-purple-200 dark:border-purple-800">
              <svg class="w-4 h-4 text-purple-600 dark:text-purple-400 flex-shrink-0" fill="none" stroke="currentColor" viewbox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
          </div>
          <div>
            <p class="text-sm font-bold text-gray-900 dark:text-white transition-colors"><?= lang('Admin/AturanNilai.impact_3_title') ?></p>
            <p class="text-[11px] font-medium text-gray-600 dark:text-slate-400 mt-1 leading-relaxed transition-colors"><?= lang('Admin/AturanNilai.impact_3_desc') ?></p>
          </div>
        </div>
        <div class="flex items-start gap-4 bg-white/80 dark:bg-slate-900/50 backdrop-blur-sm rounded-2xl p-4 border border-purple-100 dark:border-purple-800/30 shadow-sm transition-colors hover:shadow-md hover:-translate-y-0.5 transform duration-300">
          <div class="mt-1 bg-purple-100 dark:bg-purple-900/50 rounded-full p-1 border border-purple-200 dark:border-purple-800">
              <svg class="w-4 h-4 text-purple-600 dark:text-purple-400 flex-shrink-0" fill="none" stroke="currentColor" viewbox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
          </div>
          <div>
            <p class="text-sm font-bold text-gray-900 dark:text-white transition-colors"><?= lang('Admin/AturanNilai.impact_4_title') ?></p>
            <p class="text-[11px] font-medium text-gray-600 dark:text-slate-400 mt-1 leading-relaxed transition-colors"><?= lang('Admin/AturanNilai.impact_4_desc') ?></p>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="bg-red-50 dark:bg-red-900/10 border border-red-200 dark:border-red-800/40 rounded-3xl p-6 md:p-8 mb-8 transition-colors w-full min-w-0">
  <div class="flex items-start gap-5">
    <div class="w-14 h-14 rounded-2xl bg-red-500 dark:bg-red-600 flex items-center justify-center flex-shrink-0 shadow-lg shadow-red-500/30 mt-1 transition-colors">
      <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewbox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
      </svg>
    </div>
    <div class="flex-1">
      <h3 class="font-black text-red-900 dark:text-red-400 text-xl mb-3 transition-colors"><?= lang('Admin/AturanNilai.sec_policy') ?></h3>
      <ul class="space-y-3 text-sm font-medium text-red-800 dark:text-red-300 transition-colors">
        <li class="flex items-start gap-3">
          <svg class="w-5 h-5 flex-shrink-0 mt-0.5 text-red-500 dark:text-red-400" fill="none" stroke="currentColor" viewbox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
          </svg><span><strong class="font-bold text-red-900 dark:text-red-400"><?= lang('Admin/AturanNilai.sec_1') ?></strong></span>
        </li>
        <li class="flex items-start gap-3">
          <svg class="w-5 h-5 flex-shrink-0 mt-0.5 text-red-500 dark:text-red-400" fill="none" stroke="currentColor" viewbox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
          </svg><span><?= lang('Admin/AturanNilai.sec_2') ?></span>
        </li>
        <li class="flex items-start gap-3">
          <svg class="w-5 h-5 flex-shrink-0 mt-0.5 text-red-500 dark:text-red-400" fill="none" stroke="currentColor" viewbox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77-1.333.192 3 1.732 3z" />
          </svg><span><?= lang('Admin/AturanNilai.sec_3') ?></span>
        </li>
        <li class="flex items-start gap-3">
          <svg class="w-5 h-5 flex-shrink-0 mt-0.5 text-red-500 dark:text-red-400" fill="none" stroke="currentColor" viewbox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
          </svg><span><?= lang('Admin/AturanNilai.sec_4') ?></span>
        </li>
      </ul>
    </div>
  </div>
</div>

<div class="flex flex-col sm:flex-row justify-end gap-4 pt-6 mt-4 border-t border-gray-100 dark:border-slate-700 transition-colors">
    <button onclick="previewChanges()" class="px-8 py-3.5 bg-white dark:bg-slate-800 border-2 border-gray-300 dark:border-slate-600 text-gray-800 dark:text-white font-bold rounded-xl hover:bg-gray-50 dark:hover:bg-slate-700 transition-colors shadow-sm outline-none flex items-center justify-center gap-2">
        <svg class="w-5 h-5 text-gray-500 dark:text-slate-400" fill="none" stroke="currentColor" viewbox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
        </svg> <?= lang('Admin/AturanNilai.btn_preview') ?> 
    </button> 
    <button onclick="saveChanges()" class="px-8 py-3.5 bg-[<?= $color['warna_primary'] ?>] hover:brightness-90 text-white font-bold rounded-xl transition-transform transform hover:-translate-y-0.5 shadow-lg outline-none flex items-center justify-center gap-2" style="box-shadow: 0 10px 15px -3px <?= $color['warna_primary'] ?>40;">
        <svg class="w-5 h-5 animate-pulse" fill="none" stroke="currentColor" viewbox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
        </svg> <?= lang('Admin/AturanNilai.btn_save_changes') ?> 
    </button>
</div>

<?= $this->endSection() ?>

<?= $this->section('modals') ?>

<div id="addRuleModal" class="fixed inset-0 hidden flex items-center justify-center p-4 z-[99999]">
  <div class="absolute inset-0 bg-gray-950/70 backdrop-blur-sm transition-opacity pointer-events-auto" onclick="closeAddRuleModal()"></div>

  <div class="relative w-full max-w-xl bg-white dark:bg-slate-800 rounded-3xl shadow-2xl flex flex-col max-h-[90vh] pointer-events-auto border border-transparent dark:border-slate-700 transition-colors transform overflow-hidden md:ml-64">

    <div class="p-6 border-b border-gray-100 dark:border-slate-700 bg-gray-50 dark:bg-slate-900/50 z-20 flex-shrink-0 transition-colors">
      <div class="flex items-start justify-between">
        <div>
          <h3 class="text-xl font-black text-gray-900 dark:text-white transition-colors"><?= lang('Admin/AturanNilai.modal_add_title') ?></h3>
          <p class="text-sm font-medium text-gray-500 dark:text-slate-400 mt-1 transition-colors"><?= lang('Admin/AturanNilai.modal_add_desc') ?></p>
        </div>
        <button type="button" onclick="closeAddRuleModal()" class="p-2 hover:bg-gray-200 dark:hover:bg-slate-700 rounded-full transition-colors cursor-pointer outline-none">
          <svg class="w-6 h-6 text-gray-500 dark:text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
      </div>
    </div>

    <div class="flex-1 overflow-y-auto custom-scrollbar p-6 md:p-8 relative z-10 bg-white dark:bg-slate-800 transition-colors">
      <form id="addRuleForm" class="space-y-6">
                              
        <div>
          <label for="predikatInput" class="block text-[11px] font-black text-gray-500 dark:text-slate-400 uppercase tracking-widest mb-2 transition-colors"> 
              <?= lang('Admin/AturanNilai.lbl_predicate') ?> <span class="text-red-500">*</span> 
          </label>
          <input type="text" id="predikatInput" name="predikat" maxlength="2" placeholder="<?= lang('Admin/AturanNilai.ph_predicate') ?>" class="w-full px-4 py-3 bg-gray-50 dark:bg-slate-700 border-2 border-gray-200 dark:border-slate-600 text-gray-900 dark:text-white rounded-xl focus:outline-none focus:ring-2 focus:ring-[<?= $color['warna_primary'] ?>] transition-colors outline-none shadow-sm uppercase font-bold" required>
          <p class="text-[10px] font-medium text-gray-400 dark:text-slate-500 mt-1.5 transition-colors"><?= lang('Admin/AturanNilai.hint_predicate') ?></p>
        </div>
                              
        <div>
          <label for="deskripsiPredikatInput" class="block text-[11px] font-black text-gray-500 dark:text-slate-400 uppercase tracking-widest mb-2 transition-colors"> 
              <?= lang('Admin/AturanNilai.lbl_desc_pred') ?> <span class="text-red-500">*</span> 
          </label>
          <input type="text" id="deskripsiPredikatInput" name="deskripsi_predikat" placeholder="<?= lang('Admin/AturanNilai.ph_desc_pred') ?>" class="w-full px-4 py-3 bg-gray-50 dark:bg-slate-700 border-2 border-gray-200 dark:border-slate-600 text-gray-900 dark:text-white rounded-xl focus:outline-none focus:ring-2 focus:ring-[<?= $color['warna_primary'] ?>] transition-colors outline-none shadow-sm font-medium" required>
        </div>
                              
        <div class="grid grid-cols-2 gap-5">
          <div>
            <label for="nilaiMinInput" class="block text-[11px] font-black text-gray-500 dark:text-slate-400 uppercase tracking-widest mb-2 transition-colors"> 
                <?= lang('Admin/AturanNilai.lbl_min_val') ?> <span class="text-red-500">*</span> 
            </label>
            <input type="number" id="nilaiMinInput" name="nilai_min" min="0" max="100" placeholder="0" class="w-full px-4 py-3 bg-gray-50 dark:bg-slate-700 border-2 border-gray-200 dark:border-slate-600 text-gray-900 dark:text-white rounded-xl focus:outline-none focus:ring-2 focus:ring-[<?= $color['warna_primary'] ?>] transition-colors outline-none shadow-sm font-bold text-center" required>
          </div>
          <div>
            <label for="nilaiMaxInput" class="block text-[11px] font-black text-gray-500 dark:text-slate-400 uppercase tracking-widest mb-2 transition-colors"> 
                <?= lang('Admin/AturanNilai.lbl_max_val') ?> <span class="text-red-500">*</span> 
            </label>
            <input type="number" id="nilaiMaxInput" name="nilai_max" min="0" max="100" placeholder="100" class="w-full px-4 py-3 bg-gray-50 dark:bg-slate-700 border-2 border-gray-200 dark:border-slate-600 text-gray-900 dark:text-white rounded-xl focus:outline-none focus:ring-2 focus:ring-[<?= $color['warna_primary'] ?>] transition-colors outline-none shadow-sm font-bold text-center" required>
          </div>
        </div>
                              
        <div>
          <label for="deskripsiPencapaianInput" class="block text-[11px] font-black text-gray-500 dark:text-slate-400 uppercase tracking-widest mb-2 transition-colors"> 
              <?= lang('Admin/AturanNilai.lbl_desc_comp') ?> <span class="text-red-500">*</span> 
          </label>
          <textarea id="deskripsiPencapaianInput" name="deskripsi_kompetensi" rows="3" placeholder="<?= lang('Admin/AturanNilai.ph_desc_comp') ?>" class="w-full px-4 py-3 bg-gray-50 dark:bg-slate-700 border-2 border-gray-200 dark:border-slate-600 text-gray-900 dark:text-white rounded-xl focus:outline-none focus:ring-2 focus:ring-[<?= $color['warna_primary'] ?>] transition-colors resize-none shadow-sm outline-none font-medium" required></textarea>
        </div>
                              
        <div>
          <label for="warnaInput" class="flex items-center justify-between text-[11px] font-black text-gray-500 dark:text-slate-400 uppercase tracking-widest mb-3 transition-colors"> 
              <span><?= lang('Admin/AturanNilai.lbl_badge_color') ?></span>
              <span class="text-[9px] bg-gray-100 dark:bg-slate-700 px-2 py-0.5 rounded"><?= lang('Admin/AturanNilai.lbl_optional') ?></span> 
          </label>
          <div class="grid grid-cols-5 gap-3">
            <button type="button" onclick="selectColor('emerald')" class="color-option w-full h-12 rounded-xl bg-emerald-500 hover:scale-105 transition-transform border-4 border-transparent focus:outline-none focus:ring-2 focus:ring-offset-2 dark:focus:ring-offset-slate-800 focus:ring-emerald-500 shadow-sm" data-color="emerald"></button>
            <button type="button" onclick="selectColor('blue')" class="color-option w-full h-12 rounded-xl bg-blue-500 hover:scale-105 transition-transform border-4 border-transparent focus:outline-none focus:ring-2 focus:ring-offset-2 dark:focus:ring-offset-slate-800 focus:ring-blue-500 shadow-sm" data-color="blue"></button>
            <button type="button" onclick="selectColor('amber')" class="color-option w-full h-12 rounded-xl bg-amber-500 hover:scale-105 transition-transform border-4 border-transparent focus:outline-none focus:ring-2 focus:ring-offset-2 dark:focus:ring-offset-slate-800 focus:ring-amber-500 shadow-sm" data-color="amber"></button>
            <button type="button" onclick="selectColor('orange')" class="color-option w-full h-12 rounded-xl bg-orange-500 hover:scale-105 transition-transform border-4 border-transparent focus:outline-none focus:ring-2 focus:ring-offset-2 dark:focus:ring-offset-slate-800 focus:ring-orange-500 shadow-sm" data-color="orange"></button>
            <button type="button" onclick="selectColor('red')" class="color-option w-full h-12 rounded-xl bg-red-500 hover:scale-105 transition-transform border-4 border-transparent focus:outline-none focus:ring-2 focus:ring-offset-2 dark:focus:ring-offset-slate-800 focus:ring-red-500 shadow-sm" data-color="red"></button>
          </div>
          <input type="hidden" id="selectedColor" value="emerald">
        </div>
                              
        <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-slate-700/50 border border-gray-100 dark:border-slate-700 rounded-xl transition-colors">
          <div>
            <p class="font-bold text-gray-900 dark:text-white text-sm transition-colors"><?= lang('Admin/AturanNilai.rule_status') ?></p>
            <p class="text-[11px] font-medium text-gray-500 dark:text-slate-400 mt-0.5 transition-colors"><?= lang('Admin/AturanNilai.rule_status_desc') ?></p>
          </div>
          <label class="toggle-switch relative inline-flex items-center cursor-pointer group">
            <input type="checkbox" id="statusToggle" class="sr-only peer" checked>
            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-emerald-300 dark:peer-focus:ring-emerald-800 rounded-full peer dark:bg-slate-600 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-500 group-hover:after:scale-95 shadow-inner"></div>
          </label>
        </div>
                              
        <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800/50 rounded-xl p-4 flex items-start gap-4 transition-colors">
          <div class="w-8 h-8 rounded-full bg-blue-100 dark:bg-blue-800/50 flex items-center justify-center flex-shrink-0 mt-0.5 shadow-sm">
              <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
          </div>
          <div class="text-sm font-medium text-blue-800 dark:text-blue-300 leading-relaxed transition-colors">
            <p class="font-black mb-1.5 uppercase tracking-wider text-[10px]"><?= lang('Admin/AturanNilai.tips_title') ?></p>
            <ul class="list-disc list-outside ml-4 space-y-1 text-[11px]">
              <li><?= lang('Admin/AturanNilai.tip_1') ?></li>
              <li><?= lang('Admin/AturanNilai.tip_2') ?></li>
            </ul>
          </div>
        </div>
                              
        <div class="flex gap-3 pt-5 border-t border-gray-100 dark:border-slate-700 bg-white dark:bg-slate-800 sticky bottom-0 z-50 pb-2 transition-colors">
          <button type="button" onclick="closeAddRuleModal()" class="flex-1 px-6 py-3.5 bg-gray-100 dark:bg-slate-700 border-2 border-transparent dark:border-slate-600 hover:bg-gray-200 dark:hover:bg-slate-600 text-gray-800 dark:text-white font-bold rounded-xl transition-colors cursor-pointer outline-none shadow-sm"> 
              <?= lang('Admin/AturanNilai.btn_cancel') ?> 
          </button>
          <button type="submit" class="flex-1 px-6 py-3.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl transition-transform transform hover:-translate-y-0.5 shadow-lg shadow-emerald-600/30 flex items-center justify-center gap-2 cursor-pointer outline-none">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
            </svg> <?= lang('Admin/AturanNilai.btn_save_rule') ?>
          </button>
        </div>
                              
      </form>
    </div>
  </div>
</div>

<div id="historyModal" class="fixed inset-0 hidden flex items-center justify-center p-4 z-[99999]">
  <div class="absolute inset-0 bg-gray-950/70 backdrop-blur-sm transition-opacity" onclick="closeHistoryModal()"></div>
  
  <div class="relative w-full max-w-lg bg-white dark:bg-slate-800 rounded-3xl shadow-2xl flex flex-col max-h-[80vh] pointer-events-auto border border-transparent dark:border-slate-700 transition-colors transform overflow-hidden md:ml-64">
    
    <div class="p-6 border-b border-gray-100 dark:border-slate-700 flex justify-between items-center bg-gray-50 dark:bg-slate-900/50 transition-colors">
      <h3 class="text-xl font-black text-gray-900 dark:text-white transition-colors flex items-center gap-2">
          <svg class="w-6 h-6 text-[<?= $color['warna_primary'] ?>]" fill="none" stroke="currentColor" viewbox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
          <?= lang('Admin/AturanNilai.modal_hist_title') ?>
      </h3>
      <button type="button" onclick="closeHistoryModal()" class="p-2 text-gray-400 hover:text-gray-600 dark:hover:text-slate-300 hover:bg-gray-200 dark:hover:bg-slate-700 rounded-full transition-colors outline-none cursor-pointer">
         <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
      </button>
    </div>

    <div class="overflow-y-auto custom-scrollbar flex-1 bg-white dark:bg-slate-800 transition-colors" id="historyListContainer">
       <div class="p-12 text-center text-gray-500 dark:text-slate-400 font-medium flex flex-col items-center gap-3 transition-colors">
           <svg class="w-8 h-8 animate-spin text-[<?= $color['warna_primary'] ?>]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
           <?= lang('Admin/AturanNilai.loading_data') ?>
       </div>
    </div>

    <div class="p-5 border-t border-gray-100 dark:border-slate-700 bg-gray-50 dark:bg-slate-900/50 transition-colors">
      <button type="button" onclick="closeHistoryModal()" class="w-full px-6 py-3.5 bg-white dark:bg-slate-700 border-2 border-gray-300 dark:border-slate-600 rounded-xl text-sm font-bold text-gray-700 dark:text-slate-300 hover:bg-gray-100 dark:hover:bg-slate-600 transition-colors shadow-sm outline-none">
          <?= lang('Admin/AturanNilai.btn_close') ?>
      </button>
    </div>

  </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    const BASE_URL = "<?= base_url() ?>"; 
    window.LANG = {
        js_valid: "<?= lang('Admin/AturanNilai.js_valid') ?: '✔️ Valid' ?>",
        js_unbalanced: "<?= lang('Admin/AturanNilai.js_unbalanced') ?: '⚠️ Tidak Seimbang' ?>",
        js_saving: "<?= lang('Admin/AturanNilai.js_saving') ?: 'Menyimpan...' ?>",
        js_err_range: "<?= lang('Admin/AturanNilai.js_err_range') ?: 'Nilai minimum tidak boleh lebih besar dari nilai maksimum!' ?>",
        js_succ_save: "<?= lang('Admin/AturanNilai.js_succ_save') ?: 'Berhasil! Bobot penilaian telah disimpan.' ?>",
        js_fail_prefix: "<?= lang('Admin/AturanNilai.js_fail_prefix') ?: 'Gagal: ' ?>",
        js_err_server: "<?= lang('Admin/AturanNilai.js_err_server') ?: 'Terjadi kesalahan server.' ?>",
        js_conf_reset: "<?= lang('Admin/AturanNilai.js_conf_reset') ?: 'Apakah Anda yakin ingin mereset semua bobot ke pengaturan awal? Perubahan yang belum disimpan akan hilang.' ?>",
        js_succ_reset: "<?= lang('Admin/AturanNilai.js_succ_reset') ?: 'Berhasil mereset pengaturan!' ?>",
        js_fail_reset: "<?= lang('Admin/AturanNilai.js_fail_reset') ?: 'Gagal reset: ' ?>",
        js_empty_hist: "<?= lang('Admin/AturanNilai.js_empty_hist') ?: 'Belum ada riwayat perubahan.' ?>",
        js_err_load_hist: "<?= lang('Admin/AturanNilai.js_err_load_hist') ?: 'Gagal memuat data riwayat.' ?>",
        js_err_auto_bal: "<?= lang('Admin/AturanNilai.js_err_auto_bal') ?: 'Isi minimal satu bobot sebelum melakukan Auto Balance!' ?>",
        js_succ_auto_bal: "<?= lang('Admin/AturanNilai.js_succ_auto_bal') ?: 'Bobot berhasil disesuaikan otomatis menjadi 100%' ?>"
    };
</script>
<script src="<?= base_url('assets/js/Admin/aturan-nilai.js') ?>"></script>
<?= $this->endSection() ?>