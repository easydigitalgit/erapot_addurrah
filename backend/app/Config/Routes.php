<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

/*
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
// $routes->setAutoRoute(false);

// ====================================================================
// 1. DEFAULT & AUTHENTICATION
// ====================================================================
// 1. PUBLIC ROUTES
$routes->get('/', 'Auth\LoginController::index');
$routes->get('login', 'Auth\LoginController::index');
$routes->get('logout', 'Auth\LoginController::logout');
$routes->get('validasi/rapor/(:any)', 'ValidationController::rapor/$1');
$routes->post('/login/process', 'Auth\LoginController::process');
$routes->get('/logout', 'Auth\LoginController::logout');
$routes->post('/auth/lupa-password/proses', 'Auth\LoginController::prosesLupaPassword');
$routes->get('/reset-password/(:any)', 'Auth\LoginController::resetPasswordForm/$1');
$routes->post('/auth/lupa-password/update', 'Auth\LoginController::updatePasswordFromReset');
$routes->post('auth/logincontroller/setRoleSession', 'Auth\LoginController::setRoleSession');

// PERBAIKAN FATAL: Rute Radar dipindah ke zona Global/Netral
$routes->get('api/check-rbac-update', 'Auth\LoginController::checkRbacUpdate');

// ====================================================================
// 2. ADMIN ROUTES
// ====================================================================
$routes->group('admin', ['namespace' => 'App\Controllers\Admin', 'filter' => 'role:admin'], function ($routes) {

    // -- Dashboard & Statistik --
    $routes->get('/', 'DashboardStatistikController::index');
    $routes->get('dashboard-statistik', 'DashboardStatistikController::index');
    $routes->get('dashboard-insight', 'DashboardInsightController::index');
    $routes->get('dashboard-insight/get-data', 'DashboardInsightController::getChartData');

    // -- Akun Saya & Profil Sekolah --
    $routes->get('akun-saya', 'AkunSayaController::index');
    $routes->post('akun-saya/update-profile', 'AkunSayaController::updateProfile');
    $routes->post('akun-saya/update-password', 'AkunSayaController::updatePassword');
    $routes->post('akun-saya/update-personal', 'AkunSayaController::updatePersonal');
    $routes->post('akun-saya/update-preferences', 'AkunSayaController::updatePreferences');
    $routes->post('akun-saya/upload-avatar', 'AkunSayaController::uploadAvatar');

    // -- Hak Akses & Role --
    // PERBAIKAN: Tambahkan rute index dan bersihkan prefix Admin\ karena sudah di dalam grup
    $routes->get('hak-akses', 'HakAksesController::index');
    $routes->get('hak-akses/getRolePermissions/(:num)', 'HakAksesController::getRolePermissions/$1');
    $routes->post('hak-akses/saveRolePermissions', 'HakAksesController::saveRolePermissions');
    $routes->post('hak-akses/addRole', 'HakAksesController::addRole');
    $routes->get('hak-akses/getAllAuditLogs', 'HakAksesController::getAllAuditLogs');

    $routes->post('preview-rapor/lock/(:num)', 'PreviewRaporController::lockRaporSiswa/$1');
    // ---> TAMBAHKAN RUTE INI UNTUK BUKA KUNCI <---
    $routes->post('preview-rapor/unlock/(:num)', 'PreviewRaporController::unlockRaporSiswa/$1');

    $routes->post('aturan-nilai/delete/(:num)', 'AturanNilaiController::deleteAturan/$1');

    $routes->get('profile-sekolah', 'ProfileSekolahController::index');
    $routes->post('profile-sekolah/update', 'ProfileSekolahController::update');
    $routes->get('profile-sekolah/get_kabupaten', 'ProfileSekolahController::get_kabupaten');
    $routes->get('profile-sekolah/get_kecamatan', 'ProfileSekolahController::get_kecamatan');
    $routes->get('profile-sekolah/get_desa', 'ProfileSekolahController::get_desa');

    // -- Manajemen Pengguna --
    $routes->group('users', function ($routes) {
        $routes->get('/', 'UsersController::index');
        $routes->post('store', 'UsersController::store');
        $routes->post('update', 'UsersController::update');
        $routes->post('delete', 'UsersController::delete');
        $routes->get('getDetail', 'UsersController::getDetail');
        $routes->post('activate', 'UsersController::activate');
        $routes->post('deactivate', 'UsersController::deactivate');
        $routes->post('bulk-delete', 'UsersController::bulkDelete');
        $routes->get('export', 'UsersController::export');
        $routes->get('get-all', 'UsersController::getAll'); // Typo sebelumnya
        $routes->post('force-logout', 'UsersController::forceLogout');
        $routes->post('update-inline-roles', 'UsersController::updateInlineRoles');
        $routes->get('getRoles/(:num)', 'UsersController::getUserRoles/$1');
    }); // -- Master Data: Rombel --
    $routes->get('tingkat-rombel', 'TingkatRombelController::index');
    $routes->group('rombel', function ($routes) {
        $routes->get('/', 'TingkatRombelController::index');
        $routes->get('show/(:num)', 'TingkatRombelController::show/$1');
        $routes->post('store', 'TingkatRombelController::store');
        $routes->post('update', 'TingkatRombelController::update');
        $routes->post('delete', 'TingkatRombelController::delete');
        $routes->get('export', 'TingkatRombelController::export');
        $routes->get('template', 'TingkatRombelController::downloadTemplate');
        $routes->post('import', 'TingkatRombelController::import');

        // ---> TAMBAHKAN RUTE MIGRASI DI SINI <---
        $routes->post('migrate', 'TingkatRombelController::migrate');
        $routes->post('migrateMassal', 'TingkatRombelController::migrateMassal'); // <--- TAMBAHKAN INI
        // ---> API KELOLA SISWA DI DALAM ROMBEL <---
        $routes->get('searchUnassignedStudents', 'TingkatRombelController::searchUnassignedStudents');
        $routes->post('addStudentToRombel', 'TingkatRombelController::addStudentToRombel');
        $routes->post('removeStudentFromRombel', 'TingkatRombelController::removeStudentFromRombel');
        $routes->post('transferStudents', 'TingkatRombelController::transferStudents');
    });

    // -- Master Data: Siswa --
    $routes->group('siswa', function ($routes) {
        $routes->get('/', 'SiswaController::index');
        $routes->get('get-all', 'SiswaController::getAll');
        $routes->get('get-rombel', 'SiswaController::getRombel');
        $routes->get('generateNextNis', 'SiswaController::generateNextNis');
        $routes->post('store', 'SiswaController::store');
        $routes->post('update/(:num)', 'SiswaController::update/$1');
        $routes->delete('delete/(:num)', 'SiswaController::delete/$1');
        $routes->get('template', 'SiswaController::downloadTemplate');
        $routes->post('import', 'SiswaController::import');
        $routes->get('getKecamatan', 'SiswaController::getKecamatan');
        $routes->get('getKelurahan', 'SiswaController::getKelurahan');
    });

    // -- Master Data: Guru & Tendik --
    $routes->group('guru-tendik', function ($routes) {
        $routes->get('/', 'GuruTendikController::index');
        $routes->get('get-all', 'GuruTendikController::getAll');
        $routes->post('store', 'GuruTendikController::store');
        $routes->get('show/(:num)', 'GuruTendikController::show/$1');
        $routes->post('update/(:num)', 'GuruTendikController::update/$1');
        $routes->delete('delete/(:num)', 'GuruTendikController::delete/$1');
        $routes->get('export', 'GuruTendikController::export');
        $routes->post('import', 'GuruTendikController::import');
        $routes->get('template', 'GuruTendikController::downloadTemplate');
        $routes->post('bulk-delete', 'GuruTendikController::bulkDelete');
        $routes->post('toggle-status/(:num)', 'GuruTendikController::toggleStatus/$1');
    });

    // -- Master Data: Orang Tua --
    $routes->group('orangtua', function ($routes) {
        $routes->get('/', 'OrangTuaController::index');
        $routes->post('store', 'OrangTuaController::store');
        $routes->get('searchSiswa', 'OrangTuaController::searchSiswa');
        $routes->get('show/(:num)', 'OrangTuaController::show/$1');
        $routes->delete('delete/(:num)', 'OrangTuaController::delete/$1');
        $routes->get('fetchData', 'OrangTuaController::fetchData');
        $routes->get('export', 'OrangTuaController::export');
        $routes->get('template', 'OrangTuaController::downloadTemplate');
        $routes->post('import', 'OrangTuaController::import');
    });

    // ==========================================================
    // MATA PELAJARAN ROUTES
    // ==========================================================
    $routes->get('mata-pelajaran', 'MataPelajaranController::index');
    $routes->post('mata-pelajaran/store', 'MataPelajaranController::store');
    $routes->post('mata-pelajaran/update/(:num)', 'MataPelajaranController::update/$1');

    // Route untuk Delete (Mendukung method POST dan DELETE via JS)
    $routes->post('mata-pelajaran/delete/(:num)', 'MataPelajaranController::delete/$1');
    $routes->delete('mata-pelajaran/delete/(:num)', 'MataPelajaranController::delete/$1');

    // Route untuk Import & Template Excel (Ini yang menyebabkan 404 tadi)
    $routes->get('mata-pelajaran/template', 'MataPelajaranController::downloadTemplate');
    $routes->post('mata-pelajaran/import', 'MataPelajaranController::import');

    // -- Konfigurasi: Kurikulum --
    $routes->group('kurikulum', function ($routes) {
        $routes->get('/', 'KurikulumController::index');
        $routes->post('store', 'KurikulumController::store');
        $routes->post('update', 'KurikulumController::update');
        $routes->post('activate/(:num)', 'KurikulumController::activate/$1');
        $routes->post('deactivate/(:num)', 'KurikulumController::deactivate/$1');
        $routes->post('delete/(:num)', 'KurikulumController::delete/$1');
        $routes->get('template', 'KurikulumController::downloadTemplate');
        $routes->post('import', 'KurikulumController::import');
        $routes->post('upload-dokumen', 'KurikulumController::uploadDokumen');
    });

    // -- Konfigurasi: Tahun Ajaran --
    $routes->group('tahun-ajaran', function ($routes) {
        $routes->get('/', 'TahunAjaranController::index');
        $routes->post('store', 'TahunAjaranController::store');
        $routes->get('show/(:num)', 'TahunAjaranController::show/$1');
        $routes->post('update', 'TahunAjaranController::update');
        $routes->post('activate', 'TahunAjaranController::activate');
        $routes->post('delete', 'TahunAjaranController::delete');
        $routes->post('changeSemester', 'TahunAjaranController::changeSemester');
        // ---> TAMBAHKAN RUTE INI UNTUK NONAKTIFKAN GLOBAL <---
        $routes->post('deactivate-all', 'TahunAjaranController::deactivateAll');
    });

    // -- Konfigurasi: Mapping Guru & Mapel --
    $routes->get('mapping-guru', 'MappingGuruController::index');
    $routes->post('mapping-guru/store', 'MappingGuruController::store');
    $routes->get('validasi-nilai/detail/(:num)', 'ValidasiNilaiController::getDetailRombel/$1');
    $routes->group('mapping-mapel', function ($routes) {
        $routes->get('/', 'MappingMapelController::index');
        $routes->post('store', 'MappingMapelController::store');
        $routes->post('update', 'MappingMapelController::update');
        $routes->post('delete', 'MappingMapelController::delete');
        $routes->post('bulk-store', 'MappingMapelController::bulkStore');
        $routes->get('template', 'MappingMapelController::downloadTemplate');
        $routes->post('import', 'MappingMapelController::import');
    });

    // -- Konfigurasi: Wali Kelas --
    $routes->group('wali-kelas', function ($routes) {
        $routes->get('/', 'WaliKelasController::index');
        $routes->post('update', 'WaliKelasController::update');
        $routes->post('delete', 'WaliKelasController::delete');
    });

    // -- Konfigurasi: Target Tahfidz --
    $routes->group('target-tahfidz', function ($routes) {
        $routes->get('/', 'TargetTahfidzController::index');
        $routes->post('store', 'TargetTahfidzController::store');
        $routes->post('update', 'TargetTahfidzController::update');
        $routes->post('delete', 'TargetTahfidzController::delete');
        $routes->get('get-surah', 'TargetTahfidzController::get_surah');
        $routes->get('template', 'TargetTahfidzController::downloadTemplate');
        $routes->post('import', 'TargetTahfidzController::import');
        $routes->get('riwayat', 'TargetTahfidzController::getRiwayat');
    });

    // -- Konfigurasi: Aturan Nilai --
    $routes->group('aturan-nilai', function ($routes) {
        $routes->get('/', 'AturanNilaiController::index');
        $routes->post('store-aturan', 'AturanNilaiController::storeAturan');
        $routes->post('update-bobot', 'AturanNilaiController::updateBobot');
        $routes->post('reset-bobot', 'AturanNilaiController::resetBobot');
        $routes->get('get-riwayat', 'AturanNilaiController::getRiwayat');
    });

    // -- Jadwal Pelajaran --
    $routes->get('jadwal-pelajaran', 'JadwalPelajaranController::index');
    $routes->group('jadwal', function ($routes) {
        $routes->post('save', 'JadwalPelajaranController::save');
        $routes->post('update/(:num)', 'JadwalPelajaranController::update/$1');
        $routes->delete('delete/(:num)', 'JadwalPelajaranController::delete/$1');
        $routes->get('template', 'JadwalPelajaranController::downloadTemplate');
        $routes->post('import', 'JadwalPelajaranController::import');
        $routes->get('get-mapping-by-rombel', 'JadwalPelajaranController::getMappingByRombel');
    });

    // -- Monitoring & Validasi --
    $routes->get('monitoring-input', 'MonitoringInputController::index');
    $routes->get('validasi-nilai', 'ValidasiNilaiController::index');
    $routes->post('validasi-nilai/prosesLock', 'ValidasiNilaiController::prosesLock');
    $routes->post('monitoring-input/send-notif', 'MonitoringInputController::sendNotification');
    $routes->get('validasi-nilai', 'ValidasiNilaiController::index');
    $routes->post('validasi-nilai/lockMassal', 'ValidasiNilaiController::lockMassal');

    // -- Cetak & Laporan --
    $routes->group('cetak-leger', function ($routes) {
        $routes->get('/', 'CetakLegerController::index');
        $routes->post('get-data', 'CetakLegerController::getData');
        $routes->get('export-excel', 'CetakLegerController::exportExcel');
    });

    $routes->group('cetak-leger-ekskul', function ($routes) {
        $routes->get('/', 'CetakLegerEkskulController::index');
        $routes->post('get-data', 'CetakLegerEkskulController::getData');
        $routes->get('export-excel', 'CetakLegerEkskulController::exportExcel');
    });

    $routes->group('cetak-rapor', function ($routes) {
        $routes->get('/', 'CetakRaporController::index');
        $routes->get('getSiswaByRombel', 'CetakRaporController::getSiswaByRombel');
        $routes->get('getCatatanSiswa', 'CetakRaporController::getCatatanSiswa'); // <--- Route yang hilang
        $routes->get('printPDF/(:num)/(:segment)', 'CetakRaporController::printPDF/$1/$2');
        $routes->post('saveCatatanRapor', 'CetakRaporController::saveCatatanRapor');
        $routes->post('uploadTtdKepsek', 'CetakRaporController::uploadTtdKepsek');
    });



    // -- Riwayat Jabatan Guru --
    $routes->group('riwayat-guru', function($routes) {
        $routes->get('/', 'RiwayatGuruController::index');
        $routes->post('delete', 'RiwayatGuruController::delete');
    });


    $routes->group('monitoring-nilai-siswa', function ($routes) {
        $routes->get('/', 'MonitoringNilaiSiswaController::index');
        $routes->get('get-siswa', 'MonitoringNilaiSiswaController::getSiswaByKelas');
        $routes->post('store', 'MonitoringNilaiSiswaController::store');
        $routes->get('export', 'MonitoringNilaiSiswaController::export');
    });

    // -- Sistem Backup --
    $routes->group('backup', ['filter' => 'permission:sistem,special'], function ($routes) {
        $routes->get('/', 'BackupController::index');
        $routes->post('do-backup', 'BackupController::doBackup');
        $routes->get('download/(:any)', 'BackupController::download/$1');
        $routes->post('delete', 'BackupController::delete');
        $routes->post('restore', 'BackupController::restore');
        $routes->post('save-settings', 'BackupController::saveSettings'); // <--- INI DIA SUMBER MASALAHNYA
        $routes->get('cron/run-backup/(:any)', 'Admin\BackupController::runAutoBackup/$1');
        $routes->get('run-pseudo-cron', 'BackupController::runPseudoCron');
    });

    $routes->get('migrate-data', 'MigrateUsers::index');

    // -- Master Data: Lingkup Materi (LM) --
    $routes->group('master-lm', function ($routes) {
        $routes->get('/', 'MasterLmController::index');
        $routes->get('get-data', 'MasterLmController::getData');
        $routes->post('store', 'MasterLmController::store');
        $routes->post('update', 'MasterLmController::update');
        $routes->post('delete', 'MasterLmController::delete');

        // FITUR EXCEL LM YANG SUDAH DIBERSIHKAN (HANYA LM, TIDAK ADA DKN)
        $routes->get('export', 'MasterLmController::export');
        $routes->get('export/(:segment)', 'MasterLmController::export/$1');
        $routes->get('download-template/(:segment)', 'MasterLmController::downloadTemplate/$1');
        $routes->post('import', 'MasterLmController::import');
    });

    // -- Master Data: Ekstrakurikuler --
    $routes->group('master-ekskul', function ($routes) {
        $routes->get('/', 'MasterEkskulController::index');
        $routes->get('get-data', 'MasterEkskulController::getData');
        $routes->post('store', 'MasterEkskulController::store');
        $routes->post('update', 'MasterEkskulController::update');
        $routes->post('delete', 'MasterEkskulController::delete');
    });

    // -- Tahfidz (Admin) --
    $routes->get('tahfidz', 'TahfidzController::index');
    $routes->group('tahfidz', function ($routes) {
        $routes->get('get-data', 'TahfidzController::getData');
        $routes->get('cetak-rapor/(:num)', 'TahfidzController::cetakRapor/$1');
    });
});

// ====================================================================
// 3. GURU MAPEL ROUTES
// ====================================================================
$routes->group('guru', ['namespace' => 'App\Controllers\GuruMapel', 'filter' => 'role:guru'], function ($routes) {
    // -- Dashboard & Profil --
    $routes->get('dashboard', 'DashboardController::index');
    $routes->get('akun-saya', 'AkunSayaController::index');
    $routes->post('akun-saya/update-profile', 'AkunSayaController::updateProfile');
    $routes->post('akun-saya/update-password', 'AkunSayaController::updatePassword');
    $routes->post('akun-saya/update-preferences', 'AkunSayaController::updatePreferences');
    $routes->post('akun-saya/upload-avatar', 'AkunSayaController::uploadAvatar');

    // -- Daftar Kelas & Siswa --
    $routes->get('daftar-kelas-mapel', 'DaftarKelasMapelController::index');
    $routes->get('daftar-siswa', 'DaftarSiswaController::index');
    $routes->get('daftar-siswa/get-data', 'DaftarSiswaController::getStudentsData');
    $routes->post('daftar-siswa/save-data', 'DaftarSiswaController::saveDataKomponen');

    // -- Penilaian Akademik --
    $routes->group('nilai-formatif', function ($routes) {
        $routes->get('/', 'NilaiFormatifController::index');
        $routes->get('get-students', 'NilaiFormatifController::getStudentsOnly');
        $routes->get('get-grades', 'NilaiFormatifController::getGrades');
        $routes->post('save-nilai', 'NilaiFormatifController::saveNilai');
        $routes->get('template', 'NilaiFormatifController::downloadTemplate');
        $routes->post('import', 'NilaiFormatifController::importExcel');
        $routes->get('template-all', 'NilaiFormatifController::downloadTemplateAll');
        $routes->post('import-all', 'NilaiFormatifController::importExcelAll');

        // Rute Dinamis Baru
        $routes->get('get-assignments', 'NilaiFormatifController::getAssignmentsByYear');
        $routes->get('get-jumlah-lm', 'NilaiFormatifController::getJumlahLmDinamis');
    });
    // Nilai Sumatif
    $routes->get('nilai-sumatif', 'NilaiSumatifController::index');
    $routes->get('nilai-sumatif/get-siswa', 'NilaiSumatifController::getNilaiSiswa');
    $routes->post('nilai-sumatif/save-draft', 'NilaiSumatifController::saveBulk');
    $routes->post('nilai-sumatif/update-status', 'NilaiSumatifController::updateStatus');
    // Gunakan GET karena fetch() di JS mengambil data tanpa method POST
    $routes->get('nilai-sumatif/getNilaiSiswa', 'NilaiSumatifController::getNilaiSiswa');
    // RUTE NILAI KOLEKTIF (GURU)
    // RUTE NILAI KOLEKTIF (GURU)
    $routes->group('nilai-kolektif', function ($routes) {
        $routes->get('/', 'NilaiKolektifController::index');
        $routes->get('download', 'NilaiKolektifController::downloadTemplate');
        $routes->post('import', 'NilaiKolektifController::importExcel');
    });

    $routes->group('nilai-rapor', function ($routes) {
        $routes->get('/', 'NilaiRaporController::index');
        $routes->get('get-penugasan/(:num)', 'NilaiRaporController::getPenugasan/$1');
        $routes->post('get-data', 'NilaiRaporController::getData');
        $routes->post('sync', 'NilaiRaporController::syncNilai');
    });

    $routes->get('proyek', 'ProyekController::index');
    $routes->post('proyek/simpanProyek', 'ProyekController::simpanProyek');
    $routes->get('proyek/getRubrik/(:num)', 'ProyekController::getRubrik/$1');
    $routes->post('proyek/simpanRubrik', 'ProyekController::simpanRubrik');
    $routes->post('proyek/deleteRubrik/(:num)', 'ProyekController::deleteRubrik/$1');
    $routes->get('proyek/getSiswaByRombel', 'ProyekController::getSiswaByRombel');
    $routes->get('proyek/getNilaiProyek/(:num)', 'ProyekController::getNilaiProyek/$1');
    $routes->post('proyek/simpanNilaiSiswa', 'ProyekController::simpanNilaiSiswa');
    $routes->post('proyek/hapusNilaiSiswa', 'ProyekController::hapusNilaiSiswa');

    // -- Sikap & Karakter --
    $routes->get('akhlak-siswa', 'AkhlakSiswaController::index');
    $routes->get('akhlak-siswa/get-siswa', 'AkhlakSiswaController::getSiswaData');
    $routes->post('akhlak-siswa/store', 'AkhlakSiswaController::store');

    $routes->get('observasi-sikap', 'ObservasiSikapController::index');
    $routes->get('observasi-sikap/get-data', 'ObservasiSikapController::getData');
    $routes->post('observasi-sikap/store', 'ObservasiSikapController::store');
    $routes->post('observasi-sikap/delete', 'ObservasiSikapController::delete');

    // -- Materi & Bank Soal --
    $routes->group('bank-soal', function ($routes) {
        $routes->get('/', 'BankSoalController::index');
        $routes->get('get-data', 'BankSoalController::getData');
        $routes->post('store', 'BankSoalController::store');
        $routes->post('delete/(:num)', 'BankSoalController::delete/$1');
        $routes->get('template', 'BankSoalController::downloadTemplate');
        $routes->post('import', 'BankSoalController::import');
        $routes->get('get-paket', 'BankSoalController::getPaket');
        $routes->post('store-paket', 'BankSoalController::storePaket');
    });

    $routes->group('upload-materi', function ($routes) {
        $routes->get('/', 'UploadMateriController::index');
        $routes->get('get-data', 'UploadMateriController::getData');
        $routes->post('store', 'UploadMateriController::store');
        $routes->post('delete', 'UploadMateriController::delete');
        $routes->post('update-status', 'UploadMateriController::updateStatus');
    });
});

// ====================================================================
// 4. WALI KELAS ROUTES
// ====================================================================
$routes->group('wali', ['namespace' => 'App\Controllers\WaliKelas', 'filter' => 'role:wali_kelas'], function ($routes) {
    // -- Dashboard / Ringkasan --
    $routes->get('ringkasan-kelas', 'RingkasanKelasController::index');
    $routes->get('daftar-siswa', 'DaftarSiswaController::index');

    // -- Absensi --
    $routes->get('absensi-kelas', 'AbsensiKelasController::index');
    $routes->get('absensi/get-data', 'AbsensiKelasController::getAbsensiData');
    $routes->post('absensi/save', 'AbsensiKelasController::saveAbsensi');
    $routes->post('absensi/import', 'WaliKelas\AbsensiKelasController::importAbsensi');

    // -- Progres --
    $routes->get('progres-nilai', 'ProgresNilaiController::index');
    $routes->get('progres-tahfidz', 'ProgresTahfidzController::index');

    // -- Catatan Rapor & Wali Kelas --
    $routes->get('catatan-rapor', 'CatatanRaporController::index');
    $routes->get('catatan-walikelas', 'CatatanWalikelasController::index');
    $routes->post('catatan-walikelas/save', 'CatatanWalikelasController::saveCatatan');
    $routes->post('catatan-walikelas/delete', 'CatatanWalikelasController::deleteCatatan');

    // -- Pelanggaran & Prestasi --
    $routes->get('pelanggaran-prestasi', 'PelanggaranPrestasiController::index');
    $routes->post('pelanggaran-prestasi/save-pelanggaran', 'PelanggaranPrestasiController::savePelanggaran');
    $routes->post('pelanggaran-prestasi/save-prestasi', 'PelanggaranPrestasiController::savePrestasi');
    $routes->post('pelanggaran-prestasi/delete', 'PelanggaranPrestasiController::deleteRecord');

    // -- Pembinaan & Validasi --
    $routes->get('perlu-pembinaan', 'PerluPembinaanController::index');
    $routes->post('perlu-pembinaan/save', 'PerluPembinaanController::saveCatatan');
    $routes->get('validasi-catatan-guru', 'ValidasiCatatanGuruController::index');

    // -- Preview Rapor (Wali Kelas) --
    $routes->get('preview-rapor', 'PreviewRaporController::index');
    $routes->get('preview-rapor/get-data/(:num)', 'PreviewRaporController::getDataRaporSiswa/$1');
    $routes->get('preview-rapor/get-catatan', 'PreviewRaporController::getCatatanSiswa');
    $routes->post('preview-rapor/save-catatan', 'PreviewRaporController::saveCatatanRapor');
    $routes->get('preview-rapor/printPDF/(:num)/(:segment)', 'PreviewRaporController::printPDF/$1/$2');

    // --- RUTE AKUN SAYA (WALI KELAS) ---
    $routes->get('akun-saya', 'AkunSayaController::index');

    // Tambahkan 4 baris POST ini agar tombol-tombol simpan berfungsi:
    $routes->post('akun-saya/update', 'AkunSayaController::updateProfile');
    $routes->post('akun-saya/upload-avatar', 'AkunSayaController::uploadAvatar');
    $routes->post('akun-saya/update-password', 'AkunSayaController::updatePassword');
    $routes->post('akun-saya/update-preferences', 'AkunSayaController::updatePreferences'); // <- Ini yang tadi dicari oleh sistem
    $routes->get('preview-rapor/printPDF/(:num)/(:any)', 'PreviewRaporController::printPDF/$1/$2');
    $routes->get('preview-rapor/cetak/(:num)', 'PreviewRaporController::cetak/$1');
    $routes->post('preview-rapor/uploadTtdWali', 'PreviewRaporController::uploadTtdWali');

    // -- Nilai Ekstrakurikuler (Wali Kelas) --
    $routes->group('nilai-ekskul', function ($routes) {
        $routes->get('/', 'NilaiEkskulController::index');
        $routes->get('get-data', 'NilaiEkskulController::getData');
        $routes->post('save', 'NilaiEkskulController::saveNilai');
        $routes->post('delete', 'NilaiEkskulController::deleteNilai');
    });

    // -- Tahfidz (Wali Kelas) --
    $routes->get('tahfidz', 'TahfidzController::index');
    $routes->group('tahfidz', function ($routes) {
        $routes->get('get-data', 'TahfidzController::getSiswaTahfidz');
        $routes->post('save', 'TahfidzController::saveNilai');
        $routes->get('cetak-rapor/(:num)', 'TahfidzController::cetakRapor/$1');
    });
});

// ====================================================================
// 5. GURU TAHFIDZ ROUTES
// ====================================================================
$routes->group('tahfidz', ['namespace' => 'App\Controllers\Tahfidz', 'filter' => 'role:guru_tahfidz'], function ($routes) {
    $routes->get('dashboard', 'DashboardController::index');
    $routes->get('dashboard/exportRekap', 'DashboardController::exportRekap');

    $routes->get('setoran', 'SetoranController::index');

    // PERBAIKAN: Arahkan URL 'get-siswa' ke fungsi 'getSiswa' di Controller
    $routes->get('setoran/get-siswa', 'SetoranController::getSiswa');

    $routes->post('setoran/save', 'SetoranController::save');

    $routes->get('monitoring', 'MonitoringController::index');
    $routes->get('monitoring/getData', 'MonitoringController::getData');
    $routes->get('monitoring/getRiwayat', 'MonitoringController::getRiwayat');

    $routes->get('nilai-rapor', 'NilaiRaporController::index');
    $routes->get('nilai-rapor/get-siswa', 'NilaiRaporController::getSiswa');
    $routes->post('nilai-rapor/save', 'NilaiRaporController::save');
    $routes->get('nilai-rapor/preview/(:num)', 'NilaiRaporController::preview/$1');

    // GURU TAHFIDZ: Akun Saya
    $routes->get('akun-saya', 'AkunSayaController::index');
    $routes->post('akun-saya/update', 'AkunSayaController::updateProfile');
    $routes->post('akun-saya/upload-avatar', 'AkunSayaController::uploadAvatar');
    $routes->post('akun-saya/update-password', 'AkunSayaController::updatePassword');
    $routes->post('akun-saya/update-preferences', 'AkunSayaController::updatePreferences');

    // ==========================================
    // ROUTES UNTUK NILAI TEORI TAHFIDZ
    // ==========================================
    $routes->get('nilai-teori', 'NilaiTeoriController::index');
    $routes->get('nilai-teori/get-siswa', 'NilaiTeoriController::getSiswa');
    $routes->post('nilai-teori/save', 'NilaiTeoriController::save');
    $routes->post('nilai-teori/importCsv', 'NilaiTeoriController::importCsv');
});

// ====================================================================
// 6. ORANG TUA ROUTES
// ====================================================================
$routes->group('orangtua', ['namespace' => 'App\Controllers\OrangTua', 'filter' => 'role:orang_tua'], function ($routes) {
    $routes->get('dashboard', 'DashboardController::index');
    $routes->get('akademik', 'AkademikController::index');
    $routes->get('tahfidz', 'TahfidzController::index');
    $routes->get('kehadiran', 'KehadiranController::index');
    // -- Rute Rapor (Unduh & Cek) --
    $routes->get('get-history-ta', 'AkademikController::getHistoryTA');
    $routes->get('rapor/cek-ketersediaan', 'AkademikController::cekKetersediaan');
    $routes->get('rapor/download', 'AkademikController::downloadRapor');


    // ---> TAMBAHKAN RUTE AKUN SAYA DI SINI <---
    $routes->get('akun-saya', 'AkunSayaController::index');
    $routes->post('akun-saya/updateProfile', 'AkunSayaController::updateProfile');
    $routes->post('akun-saya/uploadAvatar', 'AkunSayaController::uploadAvatar');
    $routes->post('akun-saya/updatePassword', 'AkunSayaController::updatePassword');
    $routes->post('akun-saya/updatePreferences', 'AkunSayaController::updatePreferences');
});

// ====================================================================
// 6. SISWA ROUTES
// ====================================================================
$routes->group('siswa', ['namespace' => 'App\Controllers\Siswa', 'filter' => 'role:siswa'], function ($routes) {
    $routes->get('dashboard', 'DashboardController::index');
    $routes->post('upload-avatar', 'DashboardController::uploadAvatar');

    // Rute yang hilang untuk fitur Ganti Foto, Password, dan Preferensi
    $routes->post('update-foto', 'DashboardController::updateFoto');
    $routes->post('update-password', 'DashboardController::updatePassword');
    $routes->post('update-prefs', 'DashboardController::updatePrefs');
});

// ====================================================================
// 7. NOTIFIKASI
// ====================================================================
$routes->group('notifikasi', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'NotifikasiController::index');
    $routes->post('mark-all-read', 'NotifikasiController::markAllRead');
    $routes->post('mark-read/(:num)', 'NotifikasiController::markAsRead/$1');
});
