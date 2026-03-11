<?php

if (!function_exists('get_tahun_ajaran')) {
    function get_tahun_ajaran() {
        $bulan = date('n');
        $tahun = date('Y');

        if ($bulan >= 7) {
            return $tahun . '/' . ($tahun + 1);
        } else {
            return ($tahun - 1) . '/' . $tahun;
        }
    }
}

if (!function_exists('get_semester')) {
    function get_semester() {
        return (date('n') >= 7) ? 'Ganjil' : 'Genap';
    }
}