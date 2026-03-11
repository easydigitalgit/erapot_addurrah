<?php

return [
    'breadcrumb'        => 'Konfigurasi Akademik',
    'page_title'        => 'Kurikulum',
    'page_desc'         => 'Atur kurikulum akademik dan implementasinya per tingkat',
    
    // Buttons Top
    'btn_add'           => 'Tambah Kurikulum',
    'btn_apply'         => 'Terapkan ke Tingkat',
    'btn_import'        => 'Import Struktur',
    
    // Primary Card
    'active_curr_title' => 'Kurikulum Aktif Tahun Ini',
    'using_level'       => 'Tingkat Menggunakan',
    'level'             => 'Tingkat',
    'total_subjects'    => 'Total Mata Pelajaran',
    'subject'           => 'Mapel',
    'general_islamic'   => '12 Umum + 4 Keislaman',
    'impl_status'       => 'Status Implementasi',
    'status_active'     => 'AKTIF',
    'running_normal'    => 'Berjalan Normal',
    'important_note'    => 'Catatan Penting',
    'important_desc'    => 'Kurikulum ini diterapkan untuk Tahun Ajaran 2024/2025. Perubahan kurikulum akan memengaruhi struktur rapor, jadwal, mapping guru, dan sistem penilaian.',
    
    // Action Cards Sidebar
    'btn_view_struct'   => 'Lihat Struktur Lengkap',
    'btn_sys_impact'    => 'Dampak Sistem',
    'btn_doc'           => 'Dokumentasi',
    
    // Table Section
    'list_title'        => 'Daftar Kurikulum Tersedia',
    'list_desc'         => 'Kelola semua jenis kurikulum yang dapat diterapkan',
    'curr_count'        => 'Kurikulum',
    'th_name'           => 'Nama Kurikulum',
    'th_type'           => 'Jenis',
    'th_year'           => 'Tahun Berlaku',
    'th_status'         => 'Status',
    'th_used_in'        => 'Digunakan Di',
    'th_action'         => 'Aksi',
    'status_inactive'   => 'Non-Aktif',
    'no_data'           => 'Belum ada data kurikulum di database.',
    'tt_structure'      => 'Lihat Struktur',
    'tt_edit'           => 'Edit',
    'tt_activate'       => 'Aktifkan',
    'tt_archive'        => 'Arsipkan',
    
    // Modals Global
    'btn_cancel'        => 'Batal',
    
    // Edit Modal
    'edit_title'        => 'Edit Kurikulum',
    'edit_desc'         => 'Ubah informasi kurikulum yang sudah ada',
    'lbl_name'          => 'Nama Kurikulum',
    'ph_name'           => 'Contoh: Kurikulum 2013 Revisi',
    'lbl_type'          => 'Jenis Kurikulum',
    'ph_type'           => 'Pilih jenis kurikulum',
    'type_k13'          => 'Kurikulum 2013 (K13)',
    'active_curr_name'      => 'Kurikulum Merdeka',
    'type_internal'     => 'Kurikulum Internal Sekolah',
    'lbl_year_start'    => 'Tahun Mulai',
    'lbl_year_end'      => 'Tahun Berakhir (Opsional)',
    'ph_year_end'       => 'Masih Berlaku',
    'lbl_desc'          => 'Deskripsi (Opsional)',
    'ph_desc'           => 'Tambahkan deskripsi...',
    'warn_title'        => 'Perhatian',
    'warn_edit_desc'    => 'Perubahan pada kurikulum yang sedang aktif dapat mempengaruhi struktur akademik yang sedang berjalan.',
    'btn_save_changes'  => 'Simpan Perubahan',
    
    // Add Modal
    'add_title'         => 'Tambah Kurikulum Baru',
    'add_desc'          => 'Daftarkan kurikulum baru ke dalam sistem',
    'warn_add_desc'     => 'Kurikulum baru akan dibuat dalam status <strong>NONAKTIF</strong>.',
    'btn_save_add'      => 'Tambah Kurikulum',
    
    // Apply Modal
    'apply_title'       => 'Terapkan Kurikulum ke Tingkat',
    'apply_desc'        => 'Tentukan kurikulum yang akan digunakan per tingkat',
    'lbl_select_curr'   => 'Pilih Kurikulum',
    'lbl_select_level'  => 'Pilih Tingkat',
    'level_7'           => 'Tingkat VII',
    'level_8'           => 'Tingkat VIII',
    'level_9'           => 'Tingkat IX',
    'lbl_apply_year'    => 'Tahun Ajaran',
    'ph_apply_year'     => 'Pilih tahun ajaran',
    'apply_options'     => 'Opsi Penerapan',
    'opt_default'       => 'Gunakan Struktur Default',
    'opt_def_desc'      => 'Mata pelajaran dan alokasi jam sesuai standar',
    'opt_custom'        => 'Sesuaikan Mata Pelajaran',
    'opt_cust_desc'     => 'Atur sendiri mata pelajaran yang akan digunakan',
    'impact_title'      => 'DAMPAK PERUBAHAN',
    'impact_1'          => '• Jadwal pelajaran akan direset',
    'impact_2'          => '• Mapping guru perlu disesuaikan ulang',
    'apply_agree'       => '<strong>Saya memahami dampak penerapan kurikulum</strong> dan siap untuk melanjutkan.',
    'btn_apply_now'     => 'Terapkan Sekarang',
    
    // Structure Modal
    'struct_title'      => 'Struktur Kurikulum -',
    'struct_desc'       => 'Detail mata pelajaran dan alokasi jam per tingkat',
    'struct_level_7'    => 'Tingkat VII',
    'struct_level_desc' => '16 Mata Pelajaran • Total 48 JP/Minggu',
    'struct_detail_msg' => 'Detail mata pelajaran untuk kelas VII...',
    'btn_close_panel'   => 'Tutup Panel',
    
    // Impact Modal
    'sys_impact_title'  => 'Dampak Sistem',
    'sys_impact_desc'   => 'Detail pengaruh perubahan kurikulum',
    'warn_important'    => 'PERINGATAN PENTING',
    'warn_imp_desc'     => 'Perubahan kurikulum harus dilakukan dengan sangat hati-hati karena dapat me-reset struktur kelas dan jadwal.',
    'btn_understand'    => 'Saya Mengerti',
    
    // Import Modal
    'import_title'      => 'Import Kurikulum Excel',
    'import_desc'       => 'Upload file Excel sesuai template',
    'step_1'            => '1. Download Template Excel:',
    'dl_template'       => 'Download Template',
    'step_2'            => '2. Upload File (.xls atau .xlsx):',
    'btn_upload'        => 'Upload & Import',
    
    // Javascript
    'js_loading'        => 'Memproses...',
    'js_err_data_not_found' => 'Data kurikulum tidak ditemukan.',
    'js_succ_edit'      => 'Kurikulum berhasil diperbarui!',
    'js_succ_add'       => 'Kurikulum baru berhasil ditambahkan!',
    'js_warn_check'     => 'Harap centang konfirmasi terlebih dahulu',
    'js_succ_apply'     => 'Kurikulum berhasil diterapkan!',
    'js_succ_activate'  => 'Kurikulum berhasil diaktifkan!',
    'js_succ_archive'   => 'Kurikulum berhasil diarsipkan!',
    'js_notification'   => 'Notifikasi',
    'js_err_fatal'      => 'Terjadi kesalahan fatal pada server.',
    'js_err_conn'       => 'Koneksi ke server terputus.'
];