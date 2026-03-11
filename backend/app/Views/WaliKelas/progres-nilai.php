<?= $this->extend('layout/main') ?>

<?= $this->section('title') ?>
  Progres Nilai Mata Pelajaran - Rapor Digital
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
  <link rel="stylesheet" href="<?= base_url('assets/css/WaliKelas/progres-nilai.css') ?>">
  <style>
    :root {
      --warna-primary: <?= $color['warna_primary'] ?? '#10b981' ?>;
      --warna-secondary: <?= $color['warna_secondary'] ?? '#ecfdf5' ?>;
    }
    .text-tema { color: var(--warna-primary) !important; }
    .bg-tema { background-color: var(--warna-primary) !important; }
    .bg-tema-light { background-color: var(--warna-secondary) !important; }
    .border-tema { border-color: var(--warna-primary) !important; }
    .tab-active { border-bottom: 2px solid var(--warna-primary); color: var(--warna-primary); }
  </style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 lg:p-5 mb-6">
      <div class="flex items-center justify-between mb-4">
       <h2 class="font-semibold text-gray-800">Filter &amp; Pengaturan Tampilan</h2>
      </div>
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
       <div>
        <label class="text-xs font-medium text-gray-600 mb-1.5 block">Mata Pelajaran</label> 
        <select id="subjectFilter" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus-ring-tema" onchange="filterData()"> 
         <option value="">Semua Mapel</option> 
         </select>
       </div>
       
       <div>
        <label class="text-xs font-medium text-gray-600 mb-1.5 block">Status Nilai</label> 
        <select id="statusFilter" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus-ring-tema" onchange="filterData()"> 
         <option value="">Semua Status</option> 
         <option value="Aman">Nilai Aman (≥75)</option> 
         <option value="Rawan">Nilai Rawan (60-74)</option> 
         <option value="Kritis">Nilai Kritis (&lt;60)</option> 
        </select>
       </div>
       
       <div>
        <label class="text-xs font-medium text-gray-600 mb-1.5 block">Urutkan Berdasarkan</label> 
        <select id="sortFilter" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus-ring-tema" onchange="filterData()"> 
         <option value="subject">Nama Mapel (A-Z)</option> 
         <option value="average-desc">Rata-rata Tertinggi</option> 
         <option value="average-asc">Rata-rata Terendah</option> 
        </select>
       </div>
       
       <div>
        <label class="text-xs font-medium text-gray-600 mb-1.5 block">Tampilan Bawah</label>
        <div class="flex gap-2">
         <button onclick="setViewMode('grid')" id="viewGrid" class="flex-1 px-3 py-2 border-2 border-tema bg-tema-light text-tema text-sm font-medium rounded-lg hover:border-tema transition-colors">
          <svg class="w-4 h-4 mx-auto" fill="currentColor" viewBox="0 0 24 24"><path d="M3 6h7V3H3v3zm0 7h7v-4H3v4zm9 0h7v-4h-7v4zm0-7h7V3h-7v3zM3 20h7v-4H3v4zm9 0h7v-4h-7v4z" /></svg>
         </button> 
         <button onclick="setViewMode('list')" id="viewList" class="flex-1 px-3 py-2 border-2 border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:border-gray-400 transition-colors">
          <svg class="w-4 h-4 mx-auto" fill="currentColor" viewBox="0 0 24 24"><path d="M3 4h18v2H3V4zm0 7h18v-2H3v2zm0 7h18v-2H3v2z" /></svg>
         </button>
        </div>
       </div>
      </div>
     </div><!-- Stats Cards -->
     <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 lg:gap-4 mb-6">
      <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100 card-hover">
       <div class="flex items-start justify-between">
        <div>
         <p class="text-xs lg:text-sm text-gray-500 mb-1">Total Mapel</p>
         <p class="text-2xl lg:text-3xl font-bold text-gray-800"><?= $statistik_umum['total_mapel'] ?></p>
        </div>
        <div class="w-10 h-10 lg:w-12 lg:h-12 rounded-xl bg-tema-light flex items-center justify-center">
         <svg class="w-5 h-5 lg:w-6 lg:h-6 text-tema" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
        </div>
       </div>
      </div>
      <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100 card-hover">
       <div class="flex items-start justify-between">
        <div>
         <p class="text-xs lg:text-sm text-gray-500 mb-1">Rata-rata Kelas</p>
         <p class="text-2xl lg:text-3xl font-bold text-tema"><?= $statistik_umum['rata_kelas'] ?></p>
        </div>
        <div class="w-10 h-10 lg:w-12 lg:h-12 rounded-xl bg-tema-light flex items-center justify-center">
         <svg class="w-5 h-5 lg:w-6 lg:h-6 text-tema" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
        </div>
       </div>
      </div>
      <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100 card-hover">
       <div class="flex items-start justify-between">
        <div>
         <p class="text-xs lg:text-sm text-gray-500 mb-1">Mapel Aman</p>
         <p class="text-2xl lg:text-3xl font-bold text-blue-600"><?= $statistik_umum['mapel_aman'] ?></p>
         <p class="text-xs text-gray-400 mt-1"><?= $statistik_umum['persen_aman'] ?? 0 ?>%</p>
        </div>
        <div class="w-10 h-10 lg:w-12 lg:h-12 rounded-xl bg-blue-50 flex items-center justify-center">
         <svg class="w-5 h-5 lg:w-6 lg:h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
        </div>
       </div>
      </div>

      <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100 card-hover">
       <div class="flex items-start justify-between">
        <div>
         <p class="text-xs lg:text-sm text-gray-500 mb-1">Mapel Rawan/Kritis</p>
         <p class="text-2xl lg:text-3xl font-bold text-amber-600"><?= $statistik_umum['mapel_rawan'] ?></p>
         <p class="text-xs text-gray-400 mt-1"><?= $statistik_umum['persen_rawan'] ?? 0 ?>%</p>
        </div>
        <div class="w-10 h-10 lg:w-12 lg:h-12 rounded-xl bg-amber-50 flex items-center justify-center">
         <svg class="w-5 h-5 lg:w-6 lg:h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v2m0 4v2m0 0v2m0-2H9m3 0h3" /></svg>
        </div>
       </div>
      </div>
     </div><!-- Chart Overview -->
     <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 lg:p-6 mb-6">
        <div class="flex items-center justify-between mb-6 pb-4 border-b border-gray-100">
            <div>
                <h2 class="text-lg font-bold text-gray-800">Perbandingan Rata-rata Kelas</h2>
                <p class="text-xs text-gray-500 mt-1">Grafik nilai rata-rata per mata pelajaran</p>
            </div>
            <div class="p-2 bg-tema-light text-tema rounded-lg">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" /></svg>
            </div>
        </div>
        
        <div class="relative w-full overflow-x-auto pb-4 custom-scrollbar">
            <div class="bar-chart flex items-end gap-2 lg:gap-4 min-w-[600px] h-64 mt-4" id="chartContainer">
                </div>
        </div>

        <div class="flex flex-wrap justify-center items-center gap-4 mt-8 pt-6 border-t border-gray-50" id="legend">
            </div>
    </div><!-- Tab Navigation -->
     <div class="flex gap-0 mb-6 border-b border-gray-200 bg-white rounded-t-xl overflow-x-auto">
        <button onclick="switchTab('semua', this)" class="tab-active border-b-2 border-tema text-tema px-6 py-3 font-bold flex items-center gap-2 whitespace-nowrap transition-all">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" /></svg> Semua Mapel 
        </button> 
        <button onclick="switchTab('detail', this)" class="tab-inactive border-b-2 border-transparent text-gray-500 hover:text-gray-700 px-6 py-3 font-semibold flex items-center gap-2 whitespace-nowrap transition-all">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5a2 2 0 012-2h6a2 2 0 012 2v12a2 2 0 01-2 2H11a2 2 0 01-2-2V5zm0 0V3m0 2V3m6 0V3m0 2V3m0 2h.01" /></svg> Detail Per Mapel 
        </button> 
        <button onclick="switchTab('analisis', this)" class="tab-inactive border-b-2 border-transparent text-gray-500 hover:text-gray-700 px-6 py-3 font-semibold flex items-center gap-2 whitespace-nowrap transition-all">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" /></svg> Analisis Tren 
        </button>
    </div>

    <div id="tab-semua" class="tab-content block">
        <div id="gridView" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6"></div>
        <div id="listView" class="hidden bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Mata Pelajaran</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase">Rata-rata</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase">Tertinggi</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase">Terendah</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase">Tren</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100" id="tableBody"></tbody>
            </table>
        </div>
    </div>

    <div id="tab-detail" class="tab-content hidden">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="lg:col-span-2">
                <label class="text-sm font-medium text-gray-600 mb-3 block">Pilih Mata Pelajaran untuk Melihat Detail:</label>
                <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-6 gap-3" id="subjectSelect"></div>
            </div>
            <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-100 p-6" id="detailPanel">
                <div class="text-center py-12">
                    <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                    <p class="text-gray-500">Pilih salah satu mapel di atas untuk melihat Analisis Detail.</p>
                </div>
            </div>
        </div>
    </div>

    <div id="tab-analisis" class="tab-content hidden">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4 pb-3 border-b border-gray-100">📈 Tren Aman/Positif</h3>
                <div class="space-y-3" id="trenPositifContainer"></div>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4 pb-3 border-b border-gray-100">📉 Perlu Atensi (Rawan/Kritis)</h3>
                <div class="space-y-3" id="trenNegatifContainer"></div>
            </div>
        </div>
    </div><!-- Tab Content: Semua Mapel -->
     <div id="tab-semua" class="tab-content">
      <div id="gridView" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6"></div>
      <div id="listView" class="hidden bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
       <table class="w-full">
        <thead class="bg-gray-50">
         <tr>
          <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Mata Pelajaran</th>
          <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase">Rata-rata</th>
          <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase">Tertinggi</th>
          <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase">Terendah</th>
          <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase">Tren</th>
          <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase">Status</th>
         </tr>
        </thead>
        <tbody class="divide-y divide-gray-100" id="tableBody"><!-- Diisi dengan JavaScript -->
        </tbody>
       </table>
      </div>
     </div><!-- Tab Content: Detail Per Mapel -->
     <div id="tab-detail" class="tab-content hidden">
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-6"><!-- Subject Selection -->
       <div class="lg:col-span-2"><label class="text-sm font-medium text-gray-600 mb-3 block">Pilih Mata Pelajaran untuk Melihat Detail</label>
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3" id="subjectSelect"><!-- Diisi dengan JavaScript -->
        </div>
       </div><!-- Detail View -->
       <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-100 p-6" id="detailPanel">
        <div class="text-center py-12">
         <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
         </svg>
         <p class="text-gray-500">Pilih mata pelajaran untuk melihat detail</p>
        </div>
       </div>
      </div>
     </div><!-- Tab Content: Analisis Tren -->
     <div id="tab-analisis" class="tab-content hidden">
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-6"><!-- Tren Positif -->
       <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4 pb-3 border-b border-gray-100">📈 Tren Positif (Meningkat)</h3>
        <div class="space-y-3">
         <div class="flex items-start gap-3 p-3 bg-green-50 rounded-lg"><span class="text-2xl">✓</span>
          <div class="flex-1">
           <p class="font-semibold text-gray-800">Bahasa Indonesia</p>
           <p class="text-sm text-gray-600">Nilai meningkat rata-rata 5 poin per bulan</p>
           <p class="text-xs text-green-700 font-medium mt-1">Lanjutkan strategi pembelajaran saat ini</p>
          </div>
         </div>
         <div class="flex items-start gap-3 p-3 bg-green-50 rounded-lg"><span class="text-2xl">✓</span>
          <div class="flex-1">
           <p class="font-semibold text-gray-800">PAI</p>
           <p class="text-sm text-gray-600">Nilai konsisten di atas 80</p>
           <p class="text-xs text-green-700 font-medium mt-1">Pertahankan konsistensi prestasi</p>
          </div>
         </div>
        </div>
       </div><!-- Tren Negatif -->
       <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4 pb-3 border-b border-gray-100">📉 Tren Bermasalah (Menurun/Stabil Rendah)</h3>
        <div class="space-y-3">
         <div class="flex items-start gap-3 p-3 bg-red-50 rounded-lg"><span class="text-2xl">⚠</span>
          <div class="flex-1">
           <p class="font-semibold text-gray-800">Matematika</p>
           <p class="text-sm text-gray-600">Nilai menurun rata-rata 3 poin per bulan</p>
           <p class="text-xs text-red-700 font-medium mt-1">Perlu intensifikasi belajar &amp; remidi</p>
          </div>
         </div>
         <div class="flex items-start gap-3 p-3 bg-red-50 rounded-lg"><span class="text-2xl">⚠</span>
          <div class="flex-1">
           <p class="font-semibold text-gray-800">IPA</p>
           <p class="text-sm text-gray-600">Nilai stabil rendah di bawah 60</p>
           <p class="text-xs text-red-700 font-medium mt-1">Perlu bimbingan khusus &amp; program remedial</p>
          </div>
         </div>
        </div>
       </div><!-- Rekomendasi -->
       <div class="lg:col-span-2 bg-emerald-50 border-2 border-emerald-300 rounded-xl p-6">
        <h3 class="text-lg font-bold text-emerald-900 mb-4">💡 Rekomendasi Pembinaan</h3>
        <div class="space-y-3 text-sm text-emerald-900">
         <div class="flex gap-3"><span class="font-bold flex-shrink-0">1.</span>
          <p><strong>Fokus Mapel Kritis:</strong> Prioritaskan pembinaan Matematika dan IPA dengan program remedi intensif 2x per minggu.</p>
         </div>
         <div class="flex gap-3"><span class="font-bold flex-shrink-0">2.</span>
          <p><strong>Pertahankan Prestasi:</strong> Jangan abaikan mapel dengan tren positif, mulai persiapan untuk meningkat ke level lebih tinggi.</p>
         </div>
         <div class="flex gap-3"><span class="font-bold flex-shrink-0">3.</span>
          <p><strong>Metode Pembelajaran:</strong> Gunakan pendekatan pembelajaran yang lebih interaktif khususnya untuk mata pelajaran dengan nilai rendah.</p>
         </div>
        </div>
       </div>
      </div>
     </div>
<?= $this->endSection() ?>

<?= $this->section('modals') ?>
<div id="remediModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4 overflow-y-auto">
   <div class="bg-white rounded-xl shadow-xl max-w-2xl w-full my-auto"><!-- Modal Header -->
    <div class="bg-gradient-to-r from-orange-600 to-orange-700 px-6 lg:px-8 py-4 flex items-center justify-between text-white">
     <div>
      <h2 id="remediTitle" class="text-xl lg:text-2xl font-bold">Buat Program Remedi</h2>
      <p id="remediSubtitle" class="text-orange-100 text-sm">Program Pembinaan dan Pembelajaran Intensif</p>
     </div><button onclick="closeRemediModal()" class="p-2 hover:bg-orange-500 rounded-lg transition-colors">
      <svg class="w-6 h-6" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
      </svg></button>
    </div><!-- Modal Content -->
    <div class="p-6 lg:p-8 max-h-96 overflow-y-auto">
     <form id="remediForm" onsubmit="submitRemediProgram(event)"><!-- Program Name -->
      <div class="mb-5"><label class="block text-sm font-semibold text-gray-800 mb-2">Nama Program Remedi</label> <input type="text" id="programName" placeholder="Misal: Program Intensif Matematika Bulan Januari" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent" required>
      </div><!-- Durasi -->
      <div class="grid grid-cols-2 gap-4 mb-5">
       <div><label class="block text-sm font-semibold text-gray-800 mb-2">Durasi (Minggu)</label> <input type="number" id="duration" min="1" max="12" placeholder="4" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent" required>
       </div>
       <div><label class="block text-sm font-semibold text-gray-800 mb-2">Frekuensi Per Minggu</label> <select id="frequency" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent" required> <option value="">Pilih Frekuensi</option> <option value="1">1x Per Minggu</option> <option value="2">2x Per Minggu</option> <option value="3">3x Per Minggu</option> <option value="4">4x Per Minggu</option> <option value="5">5x Per Minggu</option> </select>
       </div>
      </div><!-- Target Nilai -->
      <div class="mb-5"><label class="block text-sm font-semibold text-gray-800 mb-2">Target Nilai Akhir</label> <input type="number" id="targetScore" min="0" max="100" placeholder="75" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent" required>
      </div><!-- Metode Pembelajaran -->
      <div class="mb-5"><label class="block text-sm font-semibold text-gray-800 mb-2">Metode Pembelajaran</label>
       <div class="space-y-2">
        <div class="flex items-center"><input type="checkbox" id="method1" class="w-4 h-4 text-orange-600 rounded focus:ring-orange-500"> <label for="method1" class="ml-3 text-sm text-gray-700">Belajar Kelompok Kecil (3-5 siswa)</label>
        </div>
        <div class="flex items-center"><input type="checkbox" id="method2" class="w-4 h-4 text-orange-600 rounded focus:ring-orange-500"> <label for="method2" class="ml-3 text-sm text-gray-700">Bimbingan Privat 1-on-1</label>
        </div>
        <div class="flex items-center"><input type="checkbox" id="method3" class="w-4 h-4 text-orange-600 rounded focus:ring-orange-500"> <label for="method3" class="ml-3 text-sm text-gray-700">Pembelajaran Menggunakan Teknologi/App</label>
        </div>
        <div class="flex items-center"><input type="checkbox" id="method4" class="w-4 h-4 text-orange-600 rounded focus:ring-orange-500"> <label for="method4" class="ml-3 text-sm text-gray-700">Tutor Sebaya</label>
        </div>
       </div>
      </div><!-- Catatan -->
      <div class="mb-6"><label class="block text-sm font-semibold text-gray-800 mb-2">Catatan Khusus</label> <textarea id="notes" placeholder="Masukkan strategi dan catatan penting untuk program ini..." rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent"></textarea>
      </div><!-- Assigned Students -->
      <div class="mb-6 bg-orange-50 border border-orange-200 rounded-lg p-4">
       <p class="text-sm font-semibold text-orange-900 mb-3">Siswa yang Diikutsertakan</p>
       <div id="remediStudentList" class="space-y-2"><!-- Akan diisi otomatis berdasarkan status nilai -->
       </div>
      </div>
     </form>
    </div><!-- Modal Footer -->
    <div class="bg-gray-50 px-6 lg:px-8 py-4 border-t border-gray-100 flex justify-end gap-3"><button onclick="closeRemediModal()" class="px-4 py-2 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-100 transition-colors"> Batal </button> <button onclick="submitRemediProgram()" class="px-6 py-2 bg-orange-600 text-white font-medium rounded-lg hover:bg-orange-700 transition-colors flex items-center gap-2">
      <svg class="w-5 h-5" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8m0 8H8m4 0h4" />
      </svg> Buat Program </button>
    </div>
   </div>
  </div><!-- Modal Data Siswa -->
  <div id="studentModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4 overflow-y-auto">
   <div class="bg-white rounded-xl shadow-xl max-w-4xl w-full my-auto"><!-- Modal Header -->
    <div class="bg-gradient-to-r from-emerald-600 to-emerald-700 px-6 lg:px-8 py-4 flex items-center justify-between text-white">
     <div>
      <h2 id="modalTitle" class="text-xl lg:text-2xl font-bold">Data Siswa - Mata Pelajaran</h2>
      <p id="modalSubtitle" class="text-emerald-100 text-sm">Daftar nilai siswa per mata pelajaran</p>
     </div><button onclick="closeStudentModal()" class="p-2 hover:bg-emerald-500 rounded-lg transition-colors">
      <svg class="w-6 h-6" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
      </svg></button>
    </div><!-- Modal Content -->
    <div class="p-6 lg:p-8 overflow-x-auto">
     <table class="w-full">
      <thead class="bg-gray-50 sticky top-0">
       <tr>
        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">No.</th>
        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Nama Siswa</th>
        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-600 uppercase">Nilai</th>
        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-600 uppercase">Status</th>
        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-600 uppercase">Aksi</th>
       </tr>
      </thead>
      <tbody class="divide-y divide-gray-100" id="studentTableBody"><!-- Diisi dengan JavaScript -->
      </tbody>
     </table>
    </div><!-- Modal Footer -->
    <div class="bg-gray-50 px-6 lg:px-8 py-4 border-t border-gray-100 flex justify-end gap-3"><button onclick="closeStudentModal()" class="px-4 py-2 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-100 transition-colors"> Tutup </button> <button class="px-4 py-2 bg-emerald-600 text-white font-medium rounded-lg hover:bg-emerald-700 transition-colors"> Export Data </button>
    </div>
   </div>
  </div>
<?= $this->endSection() ?>

<script>
    // Menyimpan data dari Controller ke variabel Global Window agar bisa dibaca oleh progres-nilai.js
    window.dynamicSubjectsData = <?= $subjectsData ?? '[]' ?>;
    window.dynamicStudentsData = <?= $studentsData ?? '[]' ?>;
    window.sekolahConfig = {
        school_name: '<?= esc($sekolah['nama_sekolah'] ?? 'SMPIT Ad Durrah') ?>',
        teacher_name: '<?= esc($user) ?>',
        class_name: '<?= esc($rombel['nama_rombel'] ?? '') ?>'
    };
</script>

<?= $this->section('scripts') ?>
  <script src="<?= base_url('assets/js/WaliKelas/progres-nilai.js') ?>"></script>
<?= $this->endSection() ?>