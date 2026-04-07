<?php

return [
    'page_title'             => 'Progres Nilai Mata Pelajaran',
    
    // Filter Card
    'filter_settings'        => 'Filter & Pengaturan Tampilan',
    'filter_subject'         => 'Mata Pelajaran',
    'opt_all_subjects'       => 'Semua Mapel',
    'filter_status'          => 'Status Nilai',
    'opt_all_status'         => 'Semua Status',
    'opt_safe_grade'         => 'Nilai Aman (≥75)',
    'opt_warn_grade'         => 'Nilai Rawan (60-74)',
    'opt_crit_grade'         => 'Nilai Kritis (<60)',
    'filter_sort'            => 'Urutkan Berdasarkan',
    'opt_sort_az'            => 'Nama Mapel (A-Z)',
    'opt_sort_high'          => 'Rata-rata Tertinggi',
    'opt_sort_low'           => 'Rata-rata Terendah',
    'filter_view'            => 'Tampilan Data',
    
    // Stats
    'stat_total_subject'     => 'Total Mapel',
    'stat_class_avg'         => 'Rata-rata Kelas',
    'stat_safe_subject'      => 'Mapel Aman',
    'stat_warn_subject'      => 'Mapel Rawan/Kritis',
    
    // Chart
    'chart_title'            => 'Perbandingan Rata-rata Kelas',
    'chart_subtitle'         => 'Grafik nilai rata-rata per mata pelajaran',
    
    // Tabs
    'tab_all_subjects'       => 'Semua Mapel',
    'tab_subject_detail'     => 'Detail Per Mapel',
    'tab_trend_analysis'     => 'Analisis Tren',
    
    // Table Headers
    'th_subject'             => 'Mata Pelajaran',
    'th_average'             => 'Rata-rata',
    'th_highest'             => 'Tertinggi',
    'th_lowest'              => 'Terendah',
    'th_trend'               => 'Tren',
    'th_status'              => 'Status',
    'th_no'                  => 'No.',
    'th_student_name'        => 'Nama Siswa',
    'th_grade'               => 'Nilai Rapor',
    'th_action'              => 'Aksi',
    
    // Tab Detail
    'sel_sub_detail'         => 'Pilih Mata Pelajaran untuk Melihat Detail:',
    'sel_sub_ph'             => 'Pilih salah satu mapel di atas untuk melihat Analisis Detail.',
    
    // Tab Tren
    'trend_safe'             => 'Tren Aman/Positif',
    'trend_warn'             => 'Perlu Atensi (Rawan/Kritis)',
    
    // Modals
    'modal_student_data'     => 'Data Siswa',
    'modal_student_subtitle' => 'Daftar nilai siswa per mata pelajaran',
    'btn_close'              => 'Tutup',
    'btn_export'             => 'Export Data',
    
    'modal_remedi_title'     => 'Buat Program Remedi',
    'modal_remedi_subtitle'  => 'Program Pembinaan dan Pembelajaran Intensif',
    'form_prog_name'         => 'Nama Program Remedi',
    'form_duration'          => 'Durasi (Minggu)',
    'form_frequency'         => 'Frekuensi Per Minggu',
    'form_freq_sel'          => 'Pilih Frekuensi',
    'form_freq_1'            => '1x Per Minggu',
    'form_freq_2'            => '2x Per Minggu',
    'form_freq_3'            => '3x Per Minggu',
    'form_method'            => 'Metode Pembelajaran',
    'opt_meth_1'             => 'Belajar Kelompok Kecil',
    'opt_meth_2'             => 'Bimbingan Privat 1-on-1',
    'opt_meth_3'             => 'Tutor Sebaya (Didampingi Siswa Unggul)',
    'form_req_student'       => 'Siswa yang Wajib Diikutsertakan',
    'btn_cancel'             => 'Batal',
    'btn_set_program'        => 'Tetapkan Program',
    
    // JS Logic Keys
    'no_data'                => 'Data tidak ditemukan.',
    'no_detail'              => 'Belum ada data detail.',
    'lbl_detail'             => 'Detail',
    'lbl_unrated'            => 'Belum Dinilai',
    'lbl_critical'           => 'Kritis',
    'lbl_warning'            => 'Rawan',
    'lbl_safe'               => 'Aman',
    'rec_aman'               => 'Nilai rata-rata <b>{name}</b> sangat memuaskan ({avg}). Pertahankan metode ajar.',
    'rec_rawan'              => 'Rata-rata <b>{name}</b> di ambang batas ({avg}). Perlu peninjauan metode ajar dan evaluasi kelompok.',
    'rec_belum'              => '<b>{name}</b> belum memiliki data nilai dari guru mata pelajaran. Harap ingatkan guru terkait.',
    'rec_kritis'             => 'Peringatan! Nilai <b>{name}</b> sangat kritis ({avg}). Segera buat program remedi terstruktur.',
    'rec_title'              => 'Analisis AI Sistem',
    'btn_view_spread'        => 'Lihat Sebaran Nilai Siswa',
    'btn_make_remedy'        => 'Buat Program Remedi',
    'trend_safe_lbl'         => 'Kondisi Aman (Rata-rata: {avg})',
    'trend_warn_lbl'         => 'Perlu Atensi (Rata-rata: {avg})',
    'trend_no_safe'          => 'Belum ada mapel dalam kategori aman.',
    'trend_no_warn'          => 'Alhamdulillah, tidak ada mapel rawan/kritis.',
    'remedi_prog_prefix'     => 'Program Intensif',
    'remedi_no_student'      => 'Tidak ada siswa yang memerlukan remedi.',
    'remedi_final_score'     => 'Nilai Akhir',
    'remedi_succ_msg'        => 'Program Remedi berhasil disimpan dan akan dijadwalkan otomatis oleh sistem!',
    'modal_student_title'    => 'Sebaran Nilai Siswa',
    'modal_student_sub'      => 'Mata Pelajaran: {name}',
];