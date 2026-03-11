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
// 1. DEFAULT
// ====================================================================
$routes->get('/', '\App\Controllers\Auth\LoginController::index');

// ====================================================================
// 2. AUTHENTIFICATION
// ====================================================================
$routes->get('/login', '\App\Controllers\Auth\LoginController::index');
$routes->post('/login/process', '\App\Controllers\Auth\LoginController::process');
$routes->get('/logout', '\App\Controllers\Auth\LoginController::logout');
// Route API Group
$routes->group('api', function($routes) {
    $routes->post('login', 'AuthController::login');
});

// ====================================================================
// 3. ADMIN
// ====================================================================
$routes->get('/admin/akun-saya',            '\App\Controllers\Admin\AkunSayaController::index');
$routes->get('/admin/aturan-nilai',         '\App\Controllers\Admin\AturanNilaiController::index');
$routes->get('/admin/backup',               '\App\Controllers\Admin\backupController::index');
$routes->get('/admin/cetak-leger',          '\App\Controllers\Admin\CetakLegerController::index');
$routes->get('/admin/cetak-rapor',          '\App\Controllers\Admin\CetakRaporController::index');
$routes->get('/admin/dashboard-insight',    '\App\Controllers\Admin\DashboardInsightController::index');
$routes->get('/admin/dashboard-statistik',  '\App\Controllers\Admin\DashboardStatistikController::index');
$routes->get('/admin/guru-tendik',          '\App\Controllers\Admin\GuruTendikController::index');
$routes->get('/admin/hak-akses',            '\App\Controllers\Admin\HakAksesController::index');
$routes->get('/admin/jadwal-pelajaran',     '\App\Controllers\Admin\JadwalPelajaranController::index');
$routes->get('/admin/kurikulum',            '\App\Controllers\Admin\KurikulumController::index');
$routes->get('/admin/mapping-mapel',        '\App\Controllers\Admin\MappingMapelController::index');
$routes->get('/admin/mata-pelajaran',       '\App\Controllers\Admin\MataPelajaranController::index');
$routes->get('/admin/monitoring-input',     '\App\Controllers\Admin\MonitoringInputController::index');
$routes->get('/admin/orangtua',             '\App\Controllers\Admin\OrangtuaController::index');
$routes->get('/admin/preview-rapor',        '\App\Controllers\Admin\PreviewRaporController::index');
$routes->get('/admin/profile-sekolah',      '\App\Controllers\Admin\ProfileSekolahController::index');
$routes->get('/admin/siswa',                '\App\Controllers\Admin\SiswaController::index');
$routes->get('/admin/tahun-ajaran',         '\App\Controllers\Admin\TahunAjaranController::index');
$routes->get('/admin/target-tahfidz',       '\App\Controllers\Admin\TargetTahfidzController::index');
$routes->get('/admin/tingkat-rombel',       '\App\Controllers\Admin\TingkatRombelController::index');
$routes->get('/admin/validasi-nilai',       '\App\Controllers\Admin\ValidasiNilaiController::index');
$routes->get('/admin/wali-kelas',           '\App\Controllers\Admin\WaliKelasController::index');


// ====================================================================
// 4. GURUMAPEL AKU GAK TAU HARUS NGAPAIN
// ====================================================================
$routes->get('/guru/akhlak-siswa',         '\App\Controllers\GuruMapel\AkhlakSiswaController::index');
$routes->get('/guru/bank-soal',            '\App\Controllers\GuruMapel\BankSoalController::index');
$routes->get('/guru/daftar-kelas-mapel',   '\App\Controllers\GuruMapel\DaftarKelasMapelController::index');
$routes->get('/guru/daftar-siswa',         '\App\Controllers\GuruMapel\DaftarSiswaController::index');
$routes->get('/guru/dashboard',            '\App\Controllers\GuruMapel\DashboardController::index');
$routes->get('/guru/nilai-harian',         '\App\Controllers\GuruMapel\NilaiHarianController::index');
$routes->get('/guru/nilai-sumatif',        '\App\Controllers\GuruMapel\NilaiSumatifController::index');
$routes->get('/guru/observasi-sikap',      '\App\Controllers\GuruMapel\ObservasiSikapController::index');
$routes->get('/guru/proyek',               '\App\Controllers\GuruMapel\ProyekController::index');
$routes->get('/guru/upload-materi',        '\App\Controllers\GuruMapel\UploadMateriController::index');