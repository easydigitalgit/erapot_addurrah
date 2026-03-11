<?= $this->extend('layout/main') ?>

<?= $this->section('title') ?>
  Daftar Siswa Kelas Perwalian - Rapor Digital
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
  <link rel="stylesheet" href="<?= base_url('assets/css/WaliKelas/daftar-siswa.css') ?>">
  <style>
    :root {
      --warna-primary: <?= $color['warna_primary'] ?? '#10b981' ?>;
      --warna-secondary: <?= $color['warna_secondary'] ?? '#ecfdf5' ?>;
    }
    .text-tema { color: var(--warna-primary) !important; }
    .bg-tema { background-color: var(--warna-primary) !important; }
    .bg-tema-light { background-color: var(--warna-secondary) !important; }
    .border-tema { border-color: var(--warna-primary) !important; }
  </style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 lg:p-5 mb-6">
      <div class="flex items-center justify-between mb-4">
       <h2 class="font-semibold text-gray-800">Filter &amp; Pencarian</h2><button onclick="resetFilters()" class="text-xs text-emerald-600 hover:text-emerald-700 font-medium"> Setel Ulang </button>
      </div>
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3"><!-- Search -->
       <div class="relative"><label class="text-xs font-medium text-gray-600 mb-1.5 block">Cari Nama / NIS</label>
        <div class="relative">
         <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
         </svg><input type="text" id="searchInput" placeholder="Ketik nama atau NIS..." class="w-full pl-10 pr-3 py-2 border border-gray-200 rounded-lg text-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent" onkeyup="filterTable()">
        </div>
       </div><!-- Status Filter -->
       <div><label class="text-xs font-medium text-gray-600 mb-1.5 block">Status</label> <select id="statusFilter" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent" onchange="filterTable()"> <option value="">Semua Status</option> <option value="Aktif">Aktif</option> <option value="Perlu Pembinaan">Perlu Pembinaan</option> </select>
       </div><!-- Akademik Filter -->
       <div><label class="text-xs font-medium text-gray-600 mb-1.5 block">Status Akademik</label> <select id="akademikFilter" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent" onchange="filterTable()"> <option value="">Semua</option> <option value="Aman">Nilai Aman</option> <option value="Perlu Perhatian">Nilai Bermasalah</option> </select>
       </div><!-- Tahfidz Filter -->
       <div><label class="text-xs font-medium text-gray-600 mb-1.5 block">Progres Tahfidz</label> <select id="tahfidzFilter" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent" onchange="filterTable()"> <option value="">Semua</option> <option value="Sesuai Target">Sesuai Target</option> <option value="Di Bawah Target">Di Bawah Target</option> </select>
       </div>
      </div>
     </div><!-- Stats Cards -->
     <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 lg:gap-4 mb-6">
        <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100 card-hover">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs lg:text-sm text-gray-500 mb-1">Total Siswa</p>
                    <p class="text-2xl lg:text-3xl font-bold text-gray-800"><?= $statistik['total_siswa'] ?? 0 ?></p>
                </div>
                <div class="w-10 h-10 lg:w-12 lg:h-12 rounded-xl bg-tema-light flex items-center justify-center">
                    <svg class="w-5 h-5 lg:w-6 lg:h-6 text-tema" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100 card-hover">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs lg:text-sm text-gray-500 mb-1">Hadir Hari Ini</p>
                    <p class="text-2xl lg:text-3xl font-bold text-blue-600"><?= $statistik['hadir_hari_ini'] ?? 0 ?></p>
                    <p class="text-xs text-gray-400 mt-1"><?= $statistik['persen_hadir'] ?? 0 ?>%</p>
                </div>
                <div class="w-10 h-10 lg:w-12 lg:h-12 rounded-xl bg-blue-50 flex items-center justify-center">
                    <svg class="w-5 h-5 lg:w-6 lg:h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" /></svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100 card-hover">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs lg:text-sm text-gray-500 mb-1">Perlu Pembinaan</p>
                    <p class="text-2xl lg:text-3xl font-bold text-amber-600"><?= $statistik['perlu_pembinaan'] ?? 0 ?></p>
                    <p class="text-xs text-gray-400 mt-1">Siswa</p>
                </div>
                <div class="w-10 h-10 lg:w-12 lg:h-12 rounded-xl bg-amber-50 flex items-center justify-center">
                    <svg class="w-5 h-5 lg:w-6 lg:h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100 card-hover">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs lg:text-sm text-gray-500 mb-1">Tahfidz Target</p>
                    <p class="text-2xl lg:text-3xl font-bold text-purple-600">--</p>
                    <p class="text-xs text-gray-400 mt-1">Sesuai KKM</p>
                </div>
                <div class="w-10 h-10 lg:w-12 lg:h-12 rounded-xl bg-purple-50 flex items-center justify-center">
                    <svg class="w-5 h-5 lg:w-6 lg:h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                </div>
            </div>
        </div>
    </div><!-- Students Table -->
     <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
      <div class="p-4 lg:p-5 border-b border-gray-100">
       <div class="flex items-center justify-between">
        <h2 class="font-semibold text-gray-800">Daftar Siswa Kelas VII-A</h2>
        <div class="flex items-center gap-2"><button class="p-2 text-gray-600 hover:bg-gray-100 rounded-lg transition-colors tooltip-hover">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
          </svg><span class="tooltip-text">Export Excel</span> </button> <button class="p-2 text-gray-600 hover:bg-gray-100 rounded-lg transition-colors tooltip-hover">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4H7a2 2 0 01-2-2v-4a2 2 0 012-2h10a2 2 0 012 2v4a2 2 0 01-2 2zm0 0h2a2 2 0 002-2v-4a2 2 0 00-2-2h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 00-.293.707V17a2 2 0 002 2z" />
          </svg><span class="tooltip-text">Cetak</span> </button>
        </div>
       </div>
      </div><!-- Table Responsive Wrapper -->
      <div class="overflow-x-auto">
       <table class="w-full" id="studentTable">
        <thead class="bg-gray-50">
         <tr>
          <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">No</th>
          <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Siswa</th>
          <th class="px-4 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Akademik</th>
          <th class="px-4 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Kehadiran</th>
          <th class="px-4 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Tahfidz</th>
          <th class="px-4 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Catatan</th>
          <th class="px-4 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Aksi</th>
         </tr>
        </thead>
       <tbody class="divide-y divide-gray-100" id="tableBody">
            <?php if(empty($siswa_kelas)): ?>
                <tr><td colspan="7" class="px-4 py-8 text-center text-gray-500">Belum ada data siswa di kelas ini.</td></tr>
            <?php else: ?>
                <?php $no = 1; foreach($siswa_kelas as $s): 
                    // Konfigurasi Warna & Status
                    $akademik_status = ($s['rata_nilai'] > 0 && $s['rata_nilai'] < 75) ? 'Perlu Perhatian' : 'Aman';
                    $akademik_color  = ($s['rata_nilai'] > 0 && $s['rata_nilai'] < 75) ? 'red' : 'emerald';
                    $akademik_dot    = ($s['rata_nilai'] > 0 && $s['rata_nilai'] < 75) ? 'danger' : 'safe';

                    $tahfidz_status = $s['capaian_tahfidz'] == 'Proses' ? 'Belum Ada' : 'Sesuai Target';
                    $tahfidz_color  = $s['capaian_tahfidz'] == 'Proses' ? 'amber' : 'emerald';
                    $tahfidz_dot    = $s['capaian_tahfidz'] == 'Proses' ? 'warning' : 'safe';
                    
                    $catatan_color = $s['tipe_catatan'] == 'Tidak ada' ? 'blue' : 'red';
                    
                    $themes = ['emerald', 'blue', 'amber', 'purple', 'pink', 'red'];
                    $theme = $themes[strlen($s['nama_lengkap']) % count($themes)];
                    
                    // Escape data siswa untuk Javascript
                    $jsonSiswa = htmlspecialchars(json_encode($s), ENT_QUOTES, 'UTF-8');
                ?>
                <tr class="table-row-hover student-row" 
                    data-name="<?= esc($s['nama_lengkap']) ?>" 
                    data-nis="<?= esc($s['nis']) ?>" 
                    data-status="Aktif" 
                    data-akademik="<?= $akademik_status ?>" 
                    data-tahfidz="<?= $tahfidz_status ?>">
                    
                    <td class="px-4 py-4 text-sm text-gray-600"><?= $no++ ?></td>
                    
                    <td class="px-4 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-<?= $theme ?>-400 to-<?= $theme ?>-500 flex items-center justify-center text-white text-sm font-semibold shadow-md">
                                <?= strtoupper(substr($s['nama_lengkap'], 0, 2)) ?>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-800"><?= esc($s['nama_lengkap']) ?></p>
                                <p class="text-[11px] text-gray-500 mt-0.5">NIS: <?= esc($s['nis']) ?></p>
                            </div>
                        </div>
                    </td>
                    
                    <td class="px-4 py-4 text-center">
                        <div class="flex items-center justify-center gap-2">
                            <span class="status-dot <?= $akademik_dot ?>"></span> 
                            <span class="inline-block px-2.5 py-1 bg-<?= $akademik_color ?>-100 text-<?= $akademik_color ?>-700 text-xs rounded-full font-medium">
                                <?= $akademik_status ?>
                            </span>
                        </div>
                    </td>
                    
                    <td class="px-4 py-4 text-center">
                        <p class="text-sm font-semibold text-gray-800"><?= $s['persen_absen'] ?>%</p>
                        <p class="text-xs text-gray-500"><?= $s['rekap_absen'] ?></p>
                    </td>
                    
                    <td class="px-4 py-4 text-center">
                        <div class="flex items-center justify-center gap-2">
                            <span class="status-dot <?= $tahfidz_dot ?>"></span>
                            <p class="text-xs font-medium text-<?= $tahfidz_color ?>-700"><?= $s['capaian_tahfidz'] ?></p>
                        </div>
                    </td>
                    
                    <td class="px-4 py-4 text-center">
                        <span class="inline-block px-2.5 py-1 bg-<?= $catatan_color ?>-50 text-<?= $catatan_color ?>-700 text-xs rounded-full">
                            <?= $s['tipe_catatan'] ?>
                        </span>
                    </td>
                    
                    <td class="px-4 py-4 text-center">
                        <div class="flex items-center justify-center gap-2">
                            <button onclick="openProfileModal(<?= $jsonSiswa ?>)" class="p-2 text-gray-400 hover:text-emerald-600 hover:bg-emerald-50 rounded-lg transition-colors tooltip-hover">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                <span class="tooltip-text">Profil</span> 
                            </button> 
                            <button onclick="openNoteModal('<?= $s['id'] ?>', '<?= addslashes($s['nama_lengkap']) ?>')" class="p-2 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors tooltip-hover">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                <span class="tooltip-text">Catatan</span> 
                            </button> 
                            <button onclick="openRaporModal(<?= $jsonSiswa ?>)" class="p-2 text-gray-400 hover:text-amber-600 hover:bg-amber-50 rounded-lg transition-colors tooltip-hover">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                                <span class="tooltip-text">Rapor</span> 
                            </button>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
       </table>
      </div>
      
      <div class="p-4 border-t border-gray-100 flex items-center justify-between">
       <p class="text-xs text-gray-500">Menampilkan <span class="font-medium" id="visibleCount"><?= count($siswa_kelas ?? []) ?></span> dari <span class="font-medium"><?= count($siswa_kelas ?? []) ?></span> siswa</p>
       <div class="flex items-center gap-2"><button class="p-2 text-gray-600 hover:bg-gray-100 rounded-lg transition-colors disabled:opacity-50" disabled>
         <svg class="w-4 h-4" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
         </svg></button> <button class="px-2.5 py-1.5 bg-emerald-100 text-emerald-700 text-sm font-semibold rounded-lg">1</button> <button class="p-2 text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">
         <svg class="w-4 h-4" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
         </svg></button>
       </div>
      </div>
     </div><!-- Pagination -->
     </div>
<?= $this->endSection() ?>

<?= $this->section('modals') ?>
<div id="noteModal" class="fixed inset-0 z-50 hidden">
   <div class="modal-overlay absolute inset-0 bg-black bg-opacity-50" onclick="closeNoteModal()"></div>
   <div class="absolute inset-4 lg:inset-10 flex items-center justify-center">
    <div class="modal-content bg-white rounded-2xl shadow-2xl w-full max-w-md max-h-full overflow-hidden flex flex-col">
     <div class="p-5 border-b border-gray-100 flex items-center justify-between flex-shrink-0">
      <h2 id="noteModalTitle" class="text-lg font-bold text-gray-800">Catatan Wali Kelas</h2><button onclick="closeNoteModal()" class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
       <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
       </svg></button>
     </div>
     <div class="flex-1 overflow-y-auto p-5">
      <div class="space-y-4">
       <div><label class="text-sm font-medium text-gray-700 block mb-2">Jenis Catatan</label> <select class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500"> <option>Akademik</option> <option>Karakter</option> <option>Tahfidz</option> <option>Kehadiran</option> <option>Lainnya</option> </select>
       </div>
       <div><label class="text-sm font-medium text-gray-700 block mb-2">Status Urgensi</label>
        <div class="flex gap-2"><button class="flex-1 px-3 py-2 border-2 border-gray-200 text-gray-700 text-sm font-medium rounded-lg hover:border-emerald-500 transition-colors"> Normal </button> <button class="flex-1 px-3 py-2 border-2 border-amber-300 bg-amber-50 text-amber-700 text-sm font-medium rounded-lg"> Perhatian </button> <button class="flex-1 px-3 py-2 border-2 border-gray-200 text-gray-700 text-sm font-medium rounded-lg hover:border-red-500 transition-colors"> Urgent </button>
        </div>
       </div>
       <div><label class="text-sm font-medium text-gray-700 block mb-2">Catatan</label> <textarea placeholder="Tuliskan catatan pembinaan untuk siswa..." class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 resize-none h-28" id="noteText"></textarea>
       </div>
       <div><label class="text-sm font-medium text-gray-700 block mb-2">Tanggal Pencatatan</label> <input type="date" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500" value="2024-12-15">
       </div>
      </div>
     </div>
     <div class="p-5 border-t border-gray-100 flex gap-3 flex-shrink-0"><button onclick="closeNoteModal()" class="flex-1 py-2.5 px-4 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition-colors"> Batal </button> <button onclick="saveNote()" class="flex-1 py-2.5 px-4 bg-emerald-600 text-white font-medium rounded-lg hover:bg-emerald-700 transition-colors"> Simpan Catatan </button>
     </div>
    </div>
   </div>
  </div><!-- Rapor Modal -->
  <div id="profileModal" class="fixed inset-0 z-50 hidden">
   <div class="modal-overlay absolute inset-0 bg-black bg-opacity-50" onclick="closeProfileModal()"></div>
   <div class="absolute inset-4 lg:inset-10 flex items-center justify-center">
    <div class="modal-content bg-white rounded-2xl shadow-2xl w-full max-w-2xl max-h-full overflow-hidden flex flex-col transform scale-95 transition-transform" id="profileModalContent">
     <div class="p-5 border-b border-gray-100 flex items-center justify-between flex-shrink-0">
      <h2 id="profileModalTitle" class="text-lg font-bold text-gray-800">Profil Lengkap Siswa</h2>
      <button onclick="closeProfileModal()" class="p-2 hover:bg-gray-100 rounded-lg transition-colors"><svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg></button>
     </div>
     <div class="flex-1 overflow-y-auto p-5">
      <div class="grid grid-cols-3 gap-4 mb-6 pb-6 border-b border-gray-100">
       <div class="col-span-3 text-center">
        <div id="profileInitial" class="w-20 h-20 rounded-full bg-gradient-to-br from-emerald-400 to-emerald-600 flex items-center justify-center text-white text-4xl font-bold shadow-lg mx-auto mb-3">MR</div>
        <h3 id="profileName" class="text-lg font-bold text-gray-800">Nama Siswa</h3>
        <p class="text-xs text-gray-500 mt-1">NIS: <span id="profileNis">-</span> • NISN: <span id="profileNisn">-</span></p>
       </div>
       <div class="bg-emerald-50 p-3 rounded-lg text-center">
        <p class="text-xs text-gray-600 mb-1">Jenis Kelamin</p>
        <p id="prof_jk" class="font-semibold text-gray-800">-</p>
       </div>
       <div class="bg-blue-50 p-3 rounded-lg text-center">
        <p class="text-xs text-gray-600 mb-1">Tempat Lahir</p>
        <p id="prof_tempat" class="font-semibold text-gray-800">-</p>
       </div>
       <div class="bg-purple-50 p-3 rounded-lg text-center">
        <p class="text-xs text-gray-600 mb-1">Tgl. Lahir</p>
        <p id="prof_tgl" class="font-semibold text-gray-800">-</p>
       </div>
      </div>
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
       <div class="bg-gray-50 p-4 rounded-lg">
        <h4 class="font-semibold text-gray-800 mb-3">Status Akademik</h4>
        <div class="space-y-2">
         <div class="flex items-center justify-between"><span class="text-sm text-gray-600">Rata-rata Nilai</span> <span id="prof_rata" class="font-semibold text-gray-800">0</span></div>
         <div class="flex items-center justify-between"><span class="text-sm text-gray-600">Status</span> <span id="prof_status_akademik" class="px-2 py-1 bg-gray-200 text-gray-700 text-xs rounded-full font-medium">-</span></div>
        </div>
       </div>
       <div class="bg-gray-50 p-4 rounded-lg">
        <h4 class="font-semibold text-gray-800 mb-3">Kehadiran</h4>
        <div class="space-y-2">
         <div class="flex items-center justify-between"><span class="text-sm text-gray-600">Hadir</span> <span id="prof_h" class="font-semibold text-emerald-600">0</span></div>
         <div class="flex items-center justify-between"><span class="text-sm text-gray-600">Sakit & Izin</span> <span class="font-semibold"><span id="prof_s">0</span> / <span id="prof_i">0</span></span></div>
         <div class="pt-2 border-t border-gray-200">
          <div class="flex items-center justify-between"><span class="text-sm font-medium text-gray-700">Persentase Hadir</span> <span id="prof_persen_absen" class="font-bold text-gray-800">0%</span></div>
         </div>
        </div>
       </div>
       <div class="bg-gray-50 p-4 rounded-lg">
        <h4 class="font-semibold text-gray-800 mb-3">Tahfidz</h4>
        <div class="space-y-2">
         <div class="flex items-center justify-between"><span class="text-sm text-gray-600">Capaian Terakhir</span> <span id="prof_capaian_tahfidz" class="font-semibold text-amber-600">-</span></div>
        </div>
       </div>
       <div class="bg-gray-50 p-4 rounded-lg">
        <h4 class="font-semibold text-gray-800 mb-3">Karakter</h4>
        <div class="space-y-2">
         <div class="flex items-center justify-between"><span class="text-sm text-gray-600">Kategori Catatan</span> <span id="prof_tipe_catatan" class="font-semibold text-red-600">-</span></div>
        </div>
       </div>
      </div>
      <div class="mt-6 pt-6 border-t border-gray-100">
       <h4 class="font-semibold text-gray-800 mb-3">Catatan Terbaru Wali Kelas</h4>
       <div class="bg-amber-50 border border-amber-200 rounded-lg p-4">
        <p id="prof_isi_catatan" class="text-sm text-gray-700">Belum ada catatan.</p>
       </div>
      </div>
     </div>
     <div class="p-5 border-t border-gray-100 flex gap-3 flex-shrink-0">
      <button onclick="closeProfileModal()" class="flex-1 py-2.5 px-4 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition-colors"> Tutup </button>
     </div>
    </div>
   </div>
</div>

<div id="raporModal" class="fixed inset-0 z-50 hidden">
   <div class="modal-overlay absolute inset-0 bg-black bg-opacity-50" onclick="closeRaporModal()"></div>
   <div class="absolute inset-4 lg:inset-10 flex items-center justify-center">
    <div class="modal-content bg-white rounded-2xl shadow-2xl w-full max-w-4xl max-h-full overflow-hidden flex flex-col transform scale-95 transition-transform" id="raporModalContent">
     <div class="p-5 border-b border-gray-100 flex items-center justify-between flex-shrink-0">
      <h2 id="raporModalTitle" class="text-lg font-bold text-gray-800">Preview Rapor Peserta Didik</h2><button onclick="closeRaporModal()" class="p-2 hover:bg-gray-100 rounded-lg transition-colors"><svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg></button>
     </div>
     <div class="flex-1 overflow-y-auto p-6">
      <div class="text-center mb-8 pb-6 border-b-2 border-gray-300">
       <h3 class="text-xl font-bold text-gray-800 mb-1">RAPOR PESERTA DIDIK</h3>
       <p class="text-sm text-gray-600 mb-4">SMPIT Ad Durrah | Semester 1 | Tahun Akademik 2024/2025</p>
       <div class="grid grid-cols-3 gap-4 text-sm">
        <div><p class="text-gray-600">Nama Siswa</p><p id="raporStudentName" class="font-semibold text-gray-800">-</p></div>
        <div><p class="text-gray-600">NIS</p><p id="raporStudentNIS" class="font-semibold text-gray-800">-</p></div>
        <div><p class="text-gray-600">Kelas</p><p class="font-semibold text-gray-800"><?= esc($rombel['nama_rombel'] ?? '-') ?></p></div>
       </div>
      </div>
      <div class="mb-8">
       <h4 class="text-lg font-bold text-gray-800 mb-4 pb-2 border-b-2 border-emerald-500">Nilai Akademik</h4>
       <div id="raporNilaiContainer" class="grid grid-cols-2 lg:grid-cols-3 gap-4">
            </div>
       <div class="mt-4 bg-emerald-50 border border-emerald-200 rounded-lg p-4">
        <div class="flex items-center justify-between"><span class="font-semibold text-gray-800">Rata-rata Nilai</span> <span id="raporRataRata" class="text-3xl font-bold text-emerald-700">0</span></div>
       </div>
      </div>
      <div class="mb-8">
       <h4 class="text-lg font-bold text-gray-800 mb-4 pb-2 border-b-2 border-purple-500">Kehadiran</h4>
       <div class="grid grid-cols-4 gap-3">
        <div class="bg-green-50 p-4 rounded-lg text-center"><p class="text-sm text-gray-600 mb-1">Hadir</p><p id="raporHadir" class="text-3xl font-bold text-green-600">0</p></div>
        <div class="bg-yellow-50 p-4 rounded-lg text-center"><p class="text-sm text-gray-600 mb-1">Sakit</p><p id="raporSakit" class="text-3xl font-bold text-yellow-600">0</p></div>
        <div class="bg-blue-50 p-4 rounded-lg text-center"><p class="text-sm text-gray-600 mb-1">Izin</p><p id="raporIzin" class="text-3xl font-bold text-blue-600">0</p></div>
        <div class="bg-red-50 p-4 rounded-lg text-center"><p class="text-sm text-gray-600 mb-1">Alfa</p><p id="raporAlfa" class="text-3xl font-bold text-red-600">0</p></div>
       </div>
      </div>
      <div class="mb-8">
       <h4 class="text-lg font-bold text-gray-800 mb-4 pb-2 border-b-2 border-green-500">Program Tahfidz</h4>
       <div class="bg-green-50 p-4 rounded-lg flex justify-between items-center">
        <p class="text-gray-600">Capaian Terakhir</p><p id="raporTahfidz" class="font-bold text-green-700 text-lg">-</p>
       </div>
      </div>
      <div class="bg-amber-50 border border-amber-300 rounded-lg p-4">
       <h4 class="font-bold text-amber-900 mb-2">Catatan Wali Kelas</h4>
       <p id="raporCatatan" class="text-sm text-gray-800">-</p>
      </div>
     </div>
     <div class="p-5 border-t border-gray-100 flex gap-3 flex-shrink-0">
      <button onclick="closeRaporModal()" class="flex-1 py-2.5 px-4 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition-colors"> Tutup </button>
     </div>
    </div>
   </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
  <script src="<?= base_url('assets/js/WaliKelas/daftar-siswa.js') ?>"></script>
<?= $this->endSection() ?>