<?php

return [
    'breadcrumb'          => 'Master Akademik',
    'page_title'          => 'Mapping Guru Mapel',
    'page_header'         => 'Mapping Guru Mata Pelajaran',
    'page_desc'           => 'Tetapkan guru pengampu untuk setiap mata pelajaran dan kelas',
    
    // Buttons
    'btn_add_mapping'     => 'Tambah Mapping',
    'btn_bulk_mapping'    => 'Bulk Mapping',
    'btn_import_excel'    => 'Import Excel',
    
    // Alerts & Info
    'alert_unassigned_title' => 'Perhatian: 3 Kelas Belum Memiliki Guru Pengampu',
    'alert_unassigned_desc'  => 'Matematika untuk VII-D, Bahasa Arab untuk VIII-C, dan IPA untuk IX-B belum ditugaskan guru.',
    'btn_assign_now'         => 'Tetapkan Sekarang',
    'alert_overload_title'   => 'Informasi: 1 Guru Mengampu di Atas 24 JP/Minggu',
    'alert_overload_desc'    => 'Ustadz Ahmad Fauzi, S.Pd mengampu total 28 JP/minggu. Pertimbangkan redistribusi beban mengajar.',
    
    'impact_title'        => 'Dampak Mapping Terhadap Sistem',
    'impact_desc'         => 'Mapping guru mata pelajaran akan secara otomatis memengaruhi fitur-fitur berikut:',
    'impact_schedule'     => 'Jadwal Pelajaran',
    'impact_access'       => 'Akses Guru',
    'impact_grade'        => 'Input Nilai',
    'impact_report'       => 'Rapor Siswa',
    
    // Stats
    'stat_total_teacher'  => 'Total Guru Terdaftar',
    'stat_total_subject'  => 'Total Mata Pelajaran',
    'stat_active_mapping' => 'Total Mapping Aktif',
    'stat_empty_class'    => 'Kelas Kosong (0 Guru)',
    
    // Filters
    'filter_year'         => 'Tahun Ajaran',
    'all_years'           => 'Semua Tahun',
    'filter_level'        => 'Tingkat',
    'all_levels'          => 'Semua Tingkat',
    'level_class'         => 'Kelas',
    'filter_room'         => 'Rombel',
    'all_rooms'           => 'Semua Rombel',
    'filter_subject'      => 'Mata Pelajaran',
    'all_subjects'        => 'Semua Mapel',
    'filter_teacher'      => 'Guru',
    'all_teachers'        => 'Semua Guru',
    'filter_search'       => 'Cari',
    'search_ph'           => 'Cari nama, NIK...',
    'show_active_only'    => 'Tampilkan hanya mapping aktif',
    
    // Table
    'th_teacher'          => 'Guru Pengampu',
    'th_subject'          => 'Mata Pelajaran',
    'th_level'            => 'Tingkat',
    'th_room'             => 'Rombel',
    'th_hours'            => 'Jam/Minggu',
    'th_year'             => 'Tahun Ajaran',
    'th_status'           => 'Status',
    'th_action'           => 'Aksi',
    
    // Add Modal
    'add_modal_title'     => 'Tambah Mapping Guru Mata Pelajaran',
    'add_modal_desc'      => 'Tetapkan guru pengampu untuk 1 mata pelajaran dan beberapa kelas',
    'lbl_select_teacher'  => 'Pilih Guru',
    'ph_select_teacher'   => '-- Pilih Guru Pengampu --',
    'lbl_select_subject'  => 'Mata Pelajaran',
    'ph_select_subject'   => '-- Pilih Mata Pelajaran --',
    'lbl_select_room'     => 'Rombel',
    'ph_click_room'       => 'Pilih rombel',
    'no_room_data'        => 'Belum ada data rombel',
    'lbl_hours'           => 'JP/Minggu',
    'lbl_year'            => 'Tahun Ajaran',
    'lbl_notes'           => 'Catatan (Opsional)',
    'btn_cancel'          => 'Batal',
    'btn_save_mapping'    => 'Simpan Mapping',
    'btn_save_changes'    => 'Simpan Perubahan',
    
    // Bulk Modal
    'bulk_modal_title'    => 'Bulk Mapping Guru',
    'bulk_modal_desc'     => '1 Guru mengampu BANYAK Mapel di BANYAK Kelas sekaligus.',
    'ph_teacher_bulk'     => '-- Pilih 1 Guru --',
    'lbl_multi_subj'      => 'Pilih Banyak Mata Pelajaran',
    'ph_click_subj'       => 'Klik untuk memilih mapel',
    'lbl_multi_room'      => 'Pilih Banyak Rombel',
    'lbl_avg_hours'       => 'JP/Minggu (Pukul Rata)',
    'btn_save_bulk'       => 'Simpan Bulk Mapping',
    
    // Import Modal
    'import_title'        => 'Import Mapping Excel',
    'import_desc'         => 'Upload file Excel sesuai template DB asli',
    'step_1'              => '1. Download Template Excel (Data DB):',
    'dl_template'         => 'Download Template',
    'step_2'              => '2. Upload File (.xls atau .xlsx):',
    'btn_upload'          => 'Upload & Import',
    
    // Drawer
    'drawer_title'        => 'Detail Mapping',
    'active_badge'        => 'Aktif',
    'inactive_badge'      => 'Nonaktif',
    'drawer_level'        => 'Tingkat',
    'drawer_room'         => 'Rombel',
    'drawer_year_hour'    => 'Tahun Ajaran / Jam',
    'btn_edit_mapping'    => 'Edit Mapping',
    'btn_deactivate'      => 'Nonaktifkan',
    
    // Delete Modal
    'del_modal_title'     => 'Nonaktifkan Mapping?',
    'del_modal_desc'      => 'Apakah Anda yakin ingin menonaktifkan/menghapus mapping ini? Guru tidak akan bisa mengakses kelas ini lagi.',
    'btn_yes_deactivate'  => 'Ya, Nonaktifkan',
    
    // Javascript
    'js_loading'          => 'Memproses...',
    'js_saving'           => 'Menyimpan...',
    'js_analyzing'        => 'Menganalisis...',
    'js_no_data'          => 'Tidak ada data mapping yang cocok.',
    'js_status_active'    => 'Aktif',
    'js_status_inactive'  => 'Nonaktif',
    'js_teacher_not_found'=> 'Guru Tidak Ditemukan',
    'js_err_min_bulk'     => 'Minimal 1 Mapel dan 1 Rombel wajib dipilih!',
    'js_err_server'       => 'Terjadi kesalahan server.',
    'js_err_conn'         => 'Koneksi terputus.',
    'js_err_fatal'        => 'Terjadi kesalahan fatal server.',
    'js_fail_prefix'      => 'Gagal: '
];