<?php
$routes->group('api', ['namespace' => 'App\Models\MasterData\Controllers'], function($routes) {
    $routes->get('siswa', 'Siswa::index');
});
