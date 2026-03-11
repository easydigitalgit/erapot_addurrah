<?= $this->extend('layout/main') ?>

<?= $this->section('title') ?>
  <?= lang('Sidebar.dashboard') ?> - Rapor Digital SMPIT Ad Durrah
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
  :root {
    --warna-scroll: <?= $color['warna_primary'] ?>; 
  }
</style>
<link rel="stylesheet" href="<?= base_url('assets/css/GuruMapel/dashboard.css') ?>">
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="welcome-card dark:bg-slate-800 dark:border-slate-700 dashboard-card h-fit flex-none min-h-0 mb-6 p-6 bg-[<?= $color['warna_secondary'] ?>]/50 border border-[<?= $color['warna_secondary'] ?>]/90 rounded-2xl"><h1 class="text-2xl dark:text-white md:text-3xl font-bold text-[<?= $color['warna_primary'] ?>] mb-2" id="greetingText">
    Assalamu'alaikum, <?= esc($user) ?> 👋
</h1>
            <p class="text-base md:text-lg text-[<?= $color['warna_primary'] ?>] mb-4 font-medium">
                <?= lang('GuruMapel/Dashboard.greeting_msg') ?>
            </p>

            <div class="flex flex-wrap gap-4 mt-4">
                <div class="flex items-center gap-2 px-4 py-2 bg-white dark:bg-slate-700 rounded-lg shadow-sm border border-[<?= $color['warna_primary'] ?>]/80">
                    <svg class="w-5 h-5 text-[<?= $color['warna_primary'] ?>]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
                    <div>
                        <p class="text-xs text-gray-500 font-semibold dark:text-gray-200"><?= lang('GuruMapel/Dashboard.subject') ?></p>
                        <p class="font-bold text-gray-900 dark:text-white"><?= esc($mapel_utama) ?></p>
                    </div>
                </div>

                <div class="flex items-center gap-2 px-4 py-2 bg-white rounded-lg dark:bg-slate-700 shadow-sm border border-[<?= $color['warna_primary'] ?>]/80">
                    <svg class="w-5 h-5 text-[<?= $color['warna_primary'] ?>]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                    <div>
                        <p class="text-xs text-gray-500 font-semibold dark:text-gray-200"><?= lang('GuruMapel/Dashboard.class_count') ?></p>
                        <p class="font-bold text-gray-900 dark:text-white"><?= $jumlah_kelas ?> <?= lang('GuruMapel/Dashboard.classes') ?></p>
                    </div>
                </div>

                <div class="flex items-center gap-2 px-4 py-2 bg-white dark:bg-slate-700 rounded-lg shadow-sm border border-[<?= $color['warna_primary'] ?>]">
                    <svg class="w-5 h-5 text-[<?= $color['warna_primary'] ?>]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                    <div>
                        <p class="text-xs text-gray-500 font-semibold dark:text-gray-200"><?= lang('GuruMapel/Dashboard.total_students') ?></p>
                        <p class="font-bold text-gray-900 dark:text-white"><?= $total_siswa ?> <?= lang('GuruMapel/Dashboard.students') ?></p>
                    </div>
                </div>
            </div>
        </div>
        
     <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
      <div class="stat-card dark:bg-slate-800 dark:border-slate-700">
       <div class="stat-icon bg-emerald-100 dark:bg-slate-700">
        <svg class="w-7 h-7 text-emerald-600" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
       </div>
       <div class="flex-1">
        <p class="text-sm text-gray-500 font-semibold mb-1 dark:text-gray-200"><?= lang('GuruMapel/Dashboard.overall_progress') ?></p>
        <p class="text-2xl font-bold text-gray-900 dark:text-white"><?= $quick_stats['persen_lengkap'] ?>%</p>
        <p class="text-xs text-emerald-600 font-semibold mt-1 dark:text-green-400"><?= lang('GuruMapel/Dashboard.grades_filled') ?></p>
       </div>
      </div>

      <div class="stat-card dark:bg-slate-800 dark:border-slate-700">
       <div class="stat-icon bg-amber-100 dark:bg-slate-700">
        <svg class="w-7 h-7 text-amber-600" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
       </div>
       <div class="flex-1">
        <p class="text-sm text-gray-500 font-semibold mb-1 dark:text-gray-200"><?= lang('GuruMapel/Dashboard.not_graded') ?></p>
        <p class="text-2xl font-bold text-gray-900 dark:text-white"><?= $quick_stats['belum_dinilai'] ?> <?= lang('GuruMapel/Dashboard.students') ?></p>
        <p class="text-xs text-amber-600 font-semibold mt-1 dark:text-amber-400"><?= lang('GuruMapel/Dashboard.from_total') ?> <?= $total_siswa ?> <?= lang('GuruMapel/Dashboard.students') ?></p>
       </div>
      </div>


      <div class="stat-card dark:bg-slate-800 dark:border-slate-700">
       <div class="stat-icon bg-blue-100 dark:bg-slate-700">
        <svg class="w-7 h-7 text-blue-600" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" /></svg>
       </div>
       <div class="flex-1">
        <p class="text-sm text-gray-500 font-semibold mb-1 dark:text-gray-200"><?= lang('GuruMapel/Dashboard.average_grade') ?></p>
        <p class="text-2xl font-bold text-gray-900 dark:text-white"><?= $quick_stats['rata_rata'] ?></p>
        <p class="text-xs text-blue-600 font-semibold mt-1 dark:text-blue-400"><?= lang('GuruMapel/Dashboard.all_classes_combined') ?></p>
       </div>
      </div>
     </div>
     
     <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
      
      <div class="lg:col-span-2">
       <div class="dashboard-card dark:bg-slate-800 dark:border-slate-700">
        <div class="flex items-center justify-between mb-4">
         <h3 class="text-xl font-bold text-gray-900 flex items-center gap-2 dark:text-white ">
          <svg class="w-6 h-6 text-[<?= $color['warna_primary'] ?>]" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg> <?= lang('GuruMapel/Dashboard.todays_schedule') ?>
         </h3>
         <span class="badge bg-[<?= $color['warna_secondary'] ?>] dark:bg-slate-800 dark:border-[<?= $color['warna_secondary'] ?>] text-[<?= $color['warna_primary'] ?>] badge-info"><?= count($jadwal_hari_ini) ?> <?= lang('GuruMapel/Dashboard.classes_badge') ?></span>
        </div>
        <div class="space-y-3">
            <?php if(empty($jadwal_hari_ini)): ?>
                <div class="text-center py-8 text-gray-500 dark:bg-slate-900 dark:border-slate-600 bg-gray-50 rounded-lg border border-dashed border-gray-300">
                    <p><?= lang('GuruMapel/Dashboard.no_schedule') ?></p>
                </div>
            <?php else: ?>
                <?php foreach($jadwal_hari_ini as $jadwal): ?>
                    <div class="schedule-item flex gap-4 p-3 rounded-lg hover:bg-gray-50 transition-colors border border-gray-100">
<div class="time-badge flex flex-col items-center justify-center bg-gray-800 text-white rounded-lg p-2 min-w-[70px]">
    <div class="text-[10px] uppercase opacity-75"><?= lang('GuruMapel/Dashboard.schedule_badge') ?></div>
    <div class="font-bold text-sm"><?= substr($jadwal['jam_mulai'] ?? '00:00', 0, 5) ?></div>
</div>
                        <div class="flex-1">
                            <div class="flex items-start justify-between mb-1">
                                <div>
                                    <h4 class="font-bold text-gray-900 text-base"><?= esc($mapel_utama) ?> - <?= esc($jadwal['nama_kelas']) ?></h4>
                                    <p class="text-xs text-gray-600 font-medium"><?= lang('GuruMapel/Dashboard.period') ?> <?= $jadwal['jam_ke'] ?></p>
                                </div>
                                <?php 
                                    date_default_timezone_set('Asia/Jakarta');
                                    $now = date('H:i:s');
                                    if($now >= $jadwal['jam_mulai'] && $now <= $jadwal['jam_selesai']) {
                                        echo '<span class="px-2 py-0.5 bg-green-100 text-green-700 rounded text-[10px] font-bold">' . lang('GuruMapel/Dashboard.status_ongoing') . '</span>';
                                    } elseif ($now < $jadwal['jam_mulai']) {
                                        echo '<span class="px-2 py-0.5 bg-yellow-100 text-yellow-700 rounded text-[10px] font-bold">' . lang('GuruMapel/Dashboard.status_upcoming') . '</span>';
                                    } else {
                                        echo '<span class="px-2 py-0.5 bg-gray-100 text-gray-600 rounded text-[10px] font-bold">' . lang('GuruMapel/Dashboard.status_completed') . '</span>';
                                    }
                                ?>
                            </div>
                            <div class="flex items-center gap-4 text-xs text-gray-500">
                                <span class="flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg> 
                                    <?= substr($jadwal['jam_mulai'], 0, 5) ?> - <?= substr($jadwal['jam_selesai'], 0, 5) ?>
                                </span>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        <div class="mt-4 pt-4 border-t border-gray-200 ">
            <a href="<?= base_url('guru/daftar-kelas-mapel') ?>" class=" dark:bg-[<?= $color['warna_primary'] ?>] dark:text-white dark:border-slate-800 btn-outline w-full justify-center flex items-center">
             <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg> 
             <?= lang('GuruMapel/Dashboard.view_full_schedule') ?>
            </a>
        </div>
       </div>
      </div>
      
      <div>
       <div class="dashboard-card h-full dark:bg-slate-800 dark:border-slate-700">
        <div class="flex items-center justify-between mb-4">
         <h3 class="text-xl font-bold text-gray-900 flex items-center gap-2 dark:text-white">
          <svg class="w-6 h-6 text-[<?= $color['warna_primary'] ?>]" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" /></svg> <?= lang('GuruMapel/Dashboard.notifications') ?>
         </h3>
        </div>
        <div class="space-y-3">
         
         <?php if($quick_stats['belum_dinilai'] > 0): ?>
         <div class="notification-item dark:bg-slate-700 dark:border-slate-600">
          <div class="w-10 h-10 rounded-lg bg-red-100 flex items-center justify-center flex-shrink-0 dark:bg-slate-600">
           <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
          </div>
          <div class="flex-1 min-w-0">
           <p class="text-sm font-bold text-gray-900 mb-1 dark:text-white"><?= lang('GuruMapel/Dashboard.incomplete_grades') ?></p>
           <p class="text-xs text-gray-600 dark:text-gray-200"><?= $quick_stats['belum_dinilai'] ?> <?= lang('GuruMapel/Dashboard.students_not_graded') ?></p>
           <a href="<?= base_url('guru/daftar-siswa') ?>" class="text-xs text-red-600 font-semibold mt-1 hover:underline"><?= lang('GuruMapel/Dashboard.fill_now') ?></a>
          </div>
         </div>
         <?php endif; ?>

         <?php if($insights['proyek_kosong'] > 0): ?>
         <div class="notification-item dark:bg-slate-700 dark:border-slate-600">
          <div class="w-10 h-10 rounded-lg bg-amber-100 flex items-center justify-center flex-shrink-0 dark:bg-slate-600  ">
           <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
          </div>
          <div class="flex-1 min-w-0">
           <p class="text-sm font-bold text-gray-900 mb-1 dark:text-items"><?= lang('GuruMapel/Dashboard.empty_projects') ?></p>
           <p class="text-xs text-gray-600 dark:text-gray-200"><?= $insights['proyek_kosong'] ?> <?= lang('GuruMapel/Dashboard.students_no_projects') ?></p>
          </div>
         </div>
         <?php endif; ?>

         <?php if($quick_stats['belum_dinilai'] == 0 && $insights['proyek_kosong'] == 0): ?>
         <div class="notification-item dark:bg-slate-700 dark:border-slate-600">
          <div class="w-10 h-10 rounded-lg bg-emerald-100 flex items-center justify-center flex-shrink-0 dark:bg-slate-600">
           <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
          </div>
          <div class="flex-1 min-w-0">
           <p class="text-sm font-bold text-gray-900 mb-1 dark:text-white"><?= lang('GuruMapel/Dashboard.input_completed') ?></p>
           <p class="text-xs text-gray-600 dark:text-gray-200"><?= lang('GuruMapel/Dashboard.all_grades_filled') ?></p>
          </div>
         </div>
         <?php endif; ?>

        </div>
       </div>
      </div>
     </div>
     
     <div class="dashboard-card h-fit flex-none min-h-0 mb-6 dark:bg-slate-800 dark:border-slate-700">
      <div class="flex items-center justify-between mb-6">
       <h3 class="text-xl font-bold text-gray-900 flex items-center gap-2 dark:text-white">
        <svg class="w-6 h-6 text-[<?= $color['warna_primary'] ?>]" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" /></svg> <?= lang('GuruMapel/Dashboard.grade_input_status') ?>
       </h3>
       <a href="<?= base_url('guru/daftar-siswa') ?>" class="btn-primary bg-gradient-to-br from-[<?= $color['warna_primary'] ?>]/80 to-[<?= $color['warna_primary'] ?>] hover:bg-[<?= $color['warna_primary'] ?>]/80 text-white flex items-center">
        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg> <?= lang('GuruMapel/Dashboard.continue_input') ?> 
       </a>
      </div>
      
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
       <div>
        <div class="flex items-center justify-between mb-3">
         <h4 class="font-bold text-gray-900 dark:text-white"><?= lang('GuruMapel/Dashboard.daily_grades') ?></h4><span class="text-2xl font-bold text-emerald-600"><?= $status_input['harian_pct'] ?>%</span>
        </div>
        <div class="progress-bar-container mb-2 bg-gray-100 rounded-full h-2 dark:bg-slate-700">
         <div class="progress-bar bg-emerald-500 h-full rounded-full" style="width: <?= $status_input['harian_pct'] ?>%;"></div>
        </div>
        <div class="flex items-center justify-between text-xs">
         <span class="text-gray-600 font-semibold"><?= $status_input['harian_done'] ?> <?= lang('GuruMapel/Dashboard.of') ?> <?= $total_siswa ?> <?= lang('GuruMapel/Dashboard.students') ?></span> 
        </div>
       </div>
       
       <div>
        <div class="flex items-center justify-between mb-3">
         <h4 class="font-bold text-gray-900 dark:text-white"><?= lang('GuruMapel/Dashboard.summative_grades') ?></h4><span class="text-2xl font-bold text-amber-600"><?= $status_input['sumatif_pct'] ?>%</span>
        </div>
        <div class="progress-bar-container mb-2 bg-gray-100 rounded-full h-2 dark:bg-slate-700">
         <div class="progress-bar bg-amber-500 h-full rounded-full" style="width: <?= $status_input['sumatif_pct'] ?>%;"></div>
        </div>
        <div class="flex items-center justify-between text-xs">
         <span class="text-gray-600 font-semibold"><?= $status_input['sumatif_done'] ?> <?= lang('GuruMapel/Dashboard.of') ?> <?= $total_siswa ?> <?= lang('GuruMapel/Dashboard.students') ?></span> 
        </div>
       </div>
       
       <div>
        <div class="flex items-center justify-between mb-3">
         <h4 class="font-bold text-gray-900 dark:text-white"><?= lang('GuruMapel/Dashboard.project_grades') ?></h4><span class="text-2xl font-bold text-blue-600"><?= $status_input['proyek_pct'] ?>%</span>
        </div>
        <div class="progress-bar-container mb-2 bg-gray-100 rounded-full h-2 dark:bg-slate-700">
         <div class="progress-bar bg-blue-500 h-full rounded-full" style="width: <?= $status_input['proyek_pct'] ?>%;"></div>
        </div>
        <div class="flex items-center justify-between text-xs">
         <span class="text-gray-600 font-semibold"><?= $status_input['proyek_done'] ?> <?= lang('GuruMapel/Dashboard.of') ?> <?= $total_siswa ?> <?= lang('GuruMapel/Dashboard.students') ?></span> 
        </div>
       </div>
      </div>
     </div>
     
     <div class="dashboard-card h-fit flex-none min-h-0 mb-6 dark:bg-slate-800 dark:border-slate-700">
      <div class="flex items-center justify-between mb-6">
       <h3 class="text-xl font-bold text-gray-900 flex items-center gap-2 dark:text-white">
        <svg class="w-6 h-6 text-[<?= $color['warna_primary'] ?>]" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg> <?= lang('GuruMapel/Dashboard.teaching_classes') ?>
       </h3>
       <a href="<?= base_url('guru/daftar-kelas-mapel') ?>" class="btn-secondary border-[<?= $color['warna_primary'] ?>] hover:bg-[<?= $color['warna_primary'] ?>] dark:bg-slate-700 dark:text-white"> <?= lang('GuruMapel/Dashboard.view_all') ?> </a>
      </div>
      
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 dark:bg-slate-700">
        <?php if(empty($kelas_ajar)): ?>
            <div class="col-span-full text-center py-8 text-gray-500 bg-white rounded-xl border border-gray-100 dark:bg-slate-700 dark:border-slate-600">
               <p><?= lang('GuruMapel/Dashboard.no_classes') ?></p>
            </div>
        <?php else: ?>
            <?php foreach(array_slice($kelas_ajar, 0, 3) as $kelas): ?> 
               <div class="class-card bg-white p-5 rounded-xl border border-gray-200 shadow-sm hover:shadow-md transition-shadow">
                  <div class="flex items-start justify-between mb-3">
                     <div>
                        <h4 class="font-bold text-gray-900 text-lg mb-0.5"><?= lang('GuruMapel/Dashboard.class') ?> <?= esc($kelas['nama_kelas']) ?></h4>
                        <p class="text-xs text-gray-600 font-medium"><?= lang('GuruMapel/Dashboard.level') ?> <?= esc($kelas['tingkat']) ?></p>
                     </div>
                     <span class="px-2 py-1 bg-green-100 text-green-700 rounded text-[10px] font-bold"><?= lang('GuruMapel/Dashboard.active') ?></span>
                  </div>
                  
                  <div class="flex items-center gap-2 mb-4 text-xs text-gray-600 bg-gray-50 p-2 rounded-lg">
                     <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                     <span class="font-semibold"><?= $kelas['jumlah_siswa'] ?> <?= lang('GuruMapel/Dashboard.students') ?></span>
                  </div>
                  
                  <div class="flex justify-between text-xs mb-1 font-bold">
                      <span class="text-emerald-700"><?= lang('GuruMapel/Dashboard.progress') ?></span>
                      <span class="text-emerald-700"><?= $kelas['progress'] ?>%</span>
                  </div>
                  <div class="progress-bar-container mb-3 bg-gray-100 rounded-full h-2 w-full overflow-hidden">
                     <div class="progress-bar bg-emerald-500 h-full rounded-full" style="width: <?= $kelas['progress'] ?>%;"></div>
                  </div>

                  <div class="flex gap-2 mt-4">
                     <a href="<?= base_url('guru/daftar-siswa?rombel=' . $kelas['id']) ?>" class="flex-1 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 text-xs font-semibold flex justify-center items-center gap-1 transition-colors">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg> 
                        <?= lang('GuruMapel/Dashboard.open_class') ?>
                     </a>
                  </div>
               </div>
            <?php endforeach; ?>
        <?php endif; ?>
      </div>
     </div>
     
     <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
      <div class="insight-card bg-[<?= $color['warna_secondary'] ?>]/50 border-[<?= $color['warna_primary'] ?>]" >
       <div class="flex items-center justify-between mb-4">
        <h4 class="font-bold text-gray-900"><?= lang('GuruMapel/Dashboard.avg_grade_per_class') ?></h4>
        <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" /></svg>
       </div>
       <div class="space-y-2">
        <?php if(empty($insights['rata_kelas'])): ?>
            <p class="text-sm text-gray-500 italic"><?= lang('GuruMapel/Dashboard.no_grades_input') ?></p>
        <?php else: ?>
            <?php foreach($insights['rata_kelas'] as $rk): ?>
            <div class="flex items-center justify-between text-sm border-b border-gray-200 pb-1">
                <span class="text-gray-700 font-semibold"><?= esc($rk['nama']) ?></span> 
                <span class="font-bold text-emerald-700"><?= $rk['rata'] ?></span>
            </div>
            <?php endforeach; ?>
        <?php endif; ?>
       </div>
      </div>
      
      <div class="insight-card bg-[<?= $color['warna_secondary'] ?>]/50 border-[<?= $color['warna_primary'] ?>]" >
       <div class="flex items-center justify-between mb-4">
        <h4 class="font-bold text-gray-900"><?= lang('GuruMapel/Dashboard.students_need_attention') ?></h4>
        <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
       </div>
       <div class="space-y-2">
        <div class="flex items-center justify-between text-sm">
            <span class="text-gray-700 font-semibold"><?= lang('GuruMapel/Dashboard.avg_below_70') ?></span> 
            <span class="font-bold <?= $insights['kurang_perhatian'] > 0 ? 'text-red-600' : 'text-emerald-600' ?>">
                <?= $insights['kurang_perhatian'] ?> <?= lang('GuruMapel/Dashboard.students') ?>
            </span>
        </div>
        <div class="flex items-center justify-between text-sm">
            <span class="text-gray-700 font-semibold"><?= lang('GuruMapel/Dashboard.empty_projects_short') ?></span> 
            <span class="font-bold <?= $insights['proyek_kosong'] > 0 ? 'text-amber-600' : 'text-emerald-600' ?>">
                <?= $insights['proyek_kosong'] ?> <?= lang('GuruMapel/Dashboard.students') ?>
            </span>
        </div>
       </div>
      </div>
     </div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
    <script>
        window.LANG = {
            days: [
                "<?= lang('GuruMapel/Dashboard.day_sun') ?>", 
                "<?= lang('GuruMapel/Dashboard.day_mon') ?>", 
                "<?= lang('GuruMapel/Dashboard.day_tue') ?>", 
                "<?= lang('GuruMapel/Dashboard.day_wed') ?>", 
                "<?= lang('GuruMapel/Dashboard.day_thu') ?>", 
                "<?= lang('GuruMapel/Dashboard.day_fri') ?>", 
                "<?= lang('GuruMapel/Dashboard.day_sat') ?>"
            ],
            months: [
                "<?= lang('GuruMapel/Dashboard.month_jan') ?>", 
                "<?= lang('GuruMapel/Dashboard.month_feb') ?>", 
                "<?= lang('GuruMapel/Dashboard.month_mar') ?>", 
                "<?= lang('GuruMapel/Dashboard.month_apr') ?>", 
                "<?= lang('GuruMapel/Dashboard.month_may') ?>", 
                "<?= lang('GuruMapel/Dashboard.month_jun') ?>", 
                "<?= lang('GuruMapel/Dashboard.month_jul') ?>", 
                "<?= lang('GuruMapel/Dashboard.month_aug') ?>", 
                "<?= lang('GuruMapel/Dashboard.month_sep') ?>", 
                "<?= lang('GuruMapel/Dashboard.month_oct') ?>", 
                "<?= lang('GuruMapel/Dashboard.month_nov') ?>", 
                "<?= lang('GuruMapel/Dashboard.month_dec') ?>"
            ]
        };
    </script>
  <script src="<?= base_url('assets/js/GuruMapel/dashboard.js') ?>"></script>
<?= $this->endSection() ?>