<?php

use App\Models\Admin\SekolahModel;
use App\Models\TahunAjaranModel;

if (!function_exists('get_identitas_sekolah')) {
    function get_identitas_sekolah()
    {
        // Cek Session dulu biar hemat query (Cache sederhana)
        if (!session()->has('cache_sekolah')) {
            $model = new SekolahModel();
            $data = $model->first(); // Ambil data pertama
            
            // Simpan ke session
            session()->set('cache_sekolah', $data);
        }
        return session()->get('cache_sekolah');
    }
}

if (!function_exists('get_ta_aktif')) {
    function get_ta_aktif()
    {
        if (!session()->has('cache_ta')) {
            $model = new TahunAjaranModel();
            // Ambil tahun ajaran yang statusnya aktif (misal status = 1)
            $data = $model->where('status', '1')->first();
            
            if (!$data) {
                // Default jika tabel kosong
                $data = ['tahun' => '2024/2025', 'semester' => 'Ganjil'];
            }
            session()->set('cache_ta', $data);
        }
        return session()->get('cache_ta');
    }
}