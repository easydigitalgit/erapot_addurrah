<?php

return [
    'page_title_browser' => 'Tingkat & Rombel - Rapor Digital',
    'academic_master' => 'Master Akademik',
    'page_title' => 'Tingkat & Rombel',
    'page_subtitle' => 'Kelola struktur kelas dan rombongan belajar',
    
    // Header Buttons
    'btn_add_rombel' => 'Tambah Rombel',
    'btn_import' => 'Import',
    'btn_export' => 'Export Data',
    
    // Stats
    'total_level' => 'Total Tingkat',
    'total_rombel' => 'Total Rombel',
    'total_active_student' => 'Total Siswa Aktif',
    'active_rombel' => 'Rombel Aktif',
    
    // Sections & Filters
    'level_list' => 'Daftar Tingkat',
    'study_group' => 'Rombongan Belajar',
    'all_levels' => 'Semua Tingkat',
    'search_rombel_placeholder' => 'Cari rombel...',
    
    // Table Rombel
    'th_rombel_name' => 'Nama Rombel',
    'th_level' => 'Tingkat',
    'th_homeroom' => 'Wali Kelas',
    'th_student' => 'Siswa',
    'th_status' => 'Status',
    'th_action' => 'Aksi',
    'no_rombel_data' => 'Belum ada data rombel di database.',
    'class_word' => 'Kelas',
    'no_homeroom_yet' => 'Belum ada wali',
    'active' => 'Aktif',
    'inactive' => 'Nonaktif',
    'btn_detail' => 'Lihat Detail',
    
    // Drawer Detail
    'drawer_title' => 'Detail Rombongan Belajar',
    'total_student' => 'Total Siswa',
    'male' => 'Laki-laki',
    'female' => 'Perempuan',
    'rombel_student_list' => 'Daftar Siswa Rombel',
    'manage_student' => 'Kelola Siswa',
    'btn_edit_rombel' => 'Edit Info Rombel',
    'btn_delete_rombel' => 'Hapus Rombel',
    
    // Modal Stats Tingkat
    'modal_stats_title' => 'Statistik Tingkat',
    'total_overall_student' => 'Total Keseluruhan Siswa',
    'btn_close_stats' => 'Tutup Statistik',
    
    // Modal Add/Edit Rombel
    'modal_add_title' => 'Tambah Rombel Baru',
    'modal_add_subtitle' => 'Buat rombongan belajar untuk tahun ajaran aktif',
    'modal_edit_title' => 'Edit Data Rombel',
    'form_rombel_name' => 'Nama Rombel',
    'form_rombel_placeholder' => 'Contoh: VII-A',
    'form_level' => 'Tingkat',
    'select_level' => 'Pilih tingkat',
    'form_homeroom' => 'Wali Kelas',
    'select_homeroom' => 'Pilih wali kelas (opsional)',
    'academic_year' => 'Tahun Ajaran',
    'btn_cancel' => 'Batal',
    'btn_save_rombel' => 'Simpan Rombel',
    
    // Modal Student Management
    'modal_manage_title' => 'Kelola Siswa Rombel',
    'modal_manage_subtitle' => 'Tambah, pindah, atau hapus siswa dari rombel',
    'tab_current_student' => 'Siswa Saat Ini',
    'tab_add_student' => 'Tambah Siswa',
    'tab_transfer_student' => 'Pindah Rombel',
    'search_student_placeholder' => 'Cari siswa...',
    'search_add_student_label' => 'Cari & Tambah Siswa',
    'search_add_student_placeholder' => 'Ketik nama atau NIS siswa yang belum memiliki kelas...',
    'transfer_target_label' => 'Pindahkan ke Rombel Tujuan:',
    'select_target_rombel' => 'Pilih rombel tujuan...',
    'btn_close_manage' => 'Tutup Panel Kelola',
    
    // Modal Delete
    'modal_delete_title' => 'Hapus Rombel?',
    'modal_delete_desc' => 'Data rombel yang dihapus tidak dapat dikembalikan. Siswa di dalamnya akan kehilangan kelas.',
    'btn_yes_delete' => 'Ya, Hapus Permanen',
    
    // Modal Import
    'modal_import_title' => 'Import Data Rombel',
    'modal_import_subtitle' => 'Upload file Excel sesuai template',
    'step_1_download' => '1. Download Template Excel (Data Asli):',
    'btn_download_template' => 'Download Template',
    'step_2_upload' => '2. Upload File (.xls atau .xlsx):',
    'btn_upload_import' => 'Upload & Import',
    
    // JS Strings
    'js_loading_level' => 'Memuat data tingkat...',
    'js_class_count' => 'kelas',
    'js_student_count' => 'siswa',
    'js_view_level_stats' => 'Lihat Statistik Tingkat',
    'js_year' => 'Tahun',
    'js_attention_no_teacher' => 'Perhatian: Rombel Tanpa Wali Kelas',
    'js_rombel_no_teacher' => 'rombel belum memiliki wali kelas:',
    'js_loading' => 'Memuat...',
    'js_loading_db' => 'Memuat data dari database...',
    'js_homeroom' => 'Wali Kelas:',
    'js_fail_load_student' => 'Gagal memuat data siswa.',
    'js_no_student_in_class' => 'Belum ada siswa di kelas ini.',
    'js_total' => 'Total',
    'js_in_this_rombel' => 'di rombel ini',
    'js_saving' => 'Menyimpan...',
    'js_success_updated' => 'Berhasil Diperbarui!',
    'js_success_added' => 'Berhasil Ditambahkan!',
    'js_deleted' => 'Terhapus!',
    'js_analyzing' => 'Menganalisis & Upload...',
    'js_success' => 'Berhasil',
    'js_failed' => 'Gagal',
    'js_error_system' => 'Terjadi kesalahan sistem',
    'js_error_server' => 'Gagal menghubungi server',
    'js_error_connection' => 'Koneksi ke server terputus.',
    'js_fatal_error' => 'SERVER CRASH! Cek Console.',

    'js_target' => 'Target',    
    'th_semester' => 'Semester',
    'odd' => 'Ganjil',       // Atau 'Odd' untuk English
    'even' => 'Genap',       // Atau 'Even' untuk English
    'js_editing_target' => 'Sedang mengedit target:', // Atau 'Currently editing target:'
];