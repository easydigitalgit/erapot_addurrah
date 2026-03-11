<?php

return [
    // --- HTML View ---
    'breadcrumb'        => 'Penilaian',
    'page_title'        => 'Input Nilai Siswa',
    'page_header'       => 'Input Nilai Akademik',
    'page_desc'         => 'Kelola nilai harian, tugas, dan ujian siswa.',
    'btn_export'        => 'Export Excel',
    'btn_save'          => 'Simpan Nilai',
    
    // Stats Section
    'stat_avg'          => 'Rata-rata',
    'stat_pass'         => 'Tuntas',
    'stat_fail'         => 'Remedial',
    'stat_total'        => 'Total Siswa',
    
    // Filters
    'filter_class'      => 'Pilih Kelas',
    'select_class'      => '-- Pilih Kelas --',
    'class_lbl'         => 'Kelas',
    'no_class_data'     => 'Belum ada data kelas',
    'filter_subject'    => 'Mata Pelajaran',
    'select_subject'    => '-- Pilih Mapel --',
    'no_subj_data'      => 'Belum ada data mapel',
    'filter_kkm'        => 'KKM',
    'kkm_title'         => 'Kriteria Ketuntasan Minimal',
    'btn_show'          => 'Tampilkan',
    
    // Progress
    'class_progress'    => 'Progres Pengisian Kelas',
    
    // Table
    'th_no'             => 'No',
    'th_student_name'   => 'Nama Siswa',
    'th_grade'          => 'Nilai (0-100)',
    'th_predicate'      => 'Predikat',
    'th_status'         => 'Ket.',
    'th_notes'          => 'Catatan Evaluasi',
    
    // Empty State
    'empty_title'       => 'Siap Input Nilai?',
    'empty_desc'        => 'Silakan pilih <strong class="text-gray-700 dark:text-slate-300">Kelas</strong> dan <strong class="text-gray-700 dark:text-slate-300">Mata Pelajaran</strong> pada filter di atas untuk mulai memasukkan nilai siswa.',
    
    // --- Javascript ---
    'js_status_pass'    => 'Tuntas',
    'js_status_fail'    => 'Remedial',
    'js_swal_warn_title'=> 'Pilih Data Dulu',
    'js_swal_warn_text' => 'Mohon pilih Kelas dan Mata Pelajaran sebelum menampilkan data.',
    'js_swal_btn_ok'    => 'Siap, Mengerti',
    'js_loading_fetch'  => 'Sedang mengambil data siswa...',
    'js_ph_grade'       => '-',
    'js_ph_notes'       => 'Tuliskan catatan apresiasi/evaluasi...',
    'js_no_students'    => 'Tidak ada siswa ditemukan di kelas ini.',
    'js_swal_err_title' => 'Gagal Memuat',
    'js_swal_err_text'  => 'Terjadi kesalahan saat mengambil data. Coba refresh halaman.',
    'js_err_load_table' => 'Gagal memuat data.',
    
    'js_swal_oops'      => 'Oops...',
    'js_swal_sel_save'  => 'Mohon pilih Data Kelas dan Mata Pelajaran dulu ya!',
    'js_swal_no_grade'  => 'Belum ada nilai',
    'js_swal_fill_one'  => 'Silakan isi setidaknya satu nilai siswa sebelum menyimpan.',
    'js_saving'         => 'Menyimpan...',
    
    'js_swal_success'   => 'Alhamdulillah!',
    'js_swal_fail_save' => 'Gagal Menyimpan',
    'js_swal_sys_err'   => 'Terjadi Kesalahan',
    'js_swal_err_conn'  => 'Cek koneksi internet atau hubungi admin.',
    
    'js_swal_sel_exp'   => 'Pilih Kelas dan Mata Pelajaran dulu sebelum export ya!',
    'js_swal_prep_data' => 'Menyiapkan Data...',
    'js_swal_prep_desc' => 'Mohon tunggu sebentar, file Excel sedang dibuat.'
];