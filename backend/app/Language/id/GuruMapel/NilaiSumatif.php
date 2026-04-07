<?php

return [
    // Info Halaman
    'page_title'             => 'Nilai Sumatif',
    'page_subtitle'          => 'Kelola nilai sumatif (PTS/PAS/SAS) siswa',
    
    // Info Kartu Atas
    'info_subject'           => 'Mata Pelajaran',
    'info_class'             => 'Kelas',
    'info_student_count'     => 'Jumlah Siswa',
    'info_student_text'      => 'Siswa',
    'info_status'            => 'Status',
    'status_draft'           => 'Draft',
    
    // Peringatan
    'warning_title'          => 'Peringatan',
    'warning_desc'           => 'Pastikan Anda telah memilih jenis sumatif dan memuat data sebelum mengisi nilai.',
    
    // Konfigurasi
    'config_title'           => 'Konfigurasi Nilai',
    'type_label'             => 'Jenis Sumatif',
    'type_select'            => '-- Pilih Jenis --',
    'type_pts'               => 'Penilaian Tengah Semester (PTS)',
    'type_pas'               => 'Penilaian Akhir Semester (PAS)',
    'type_sas'               => 'Sumatif Akhir Semester (SAS)',
    'weight_label'           => 'Bobot (%)',
    'kkm_label'              => 'KKM',
    'btn_load_data'          => 'Tampilkan Data',
    'config_info'            => 'Pilih jenis sumatif lalu klik "Tampilkan Data" untuk mulai mengisi nilai.',
    
    // Progress
    'progress_title'         => 'Status Proses',
    'step_1'                 => 'Drafting',
    'step_2'                 => 'Siap Validasi',
    'step_3'                 => 'Terkunci',
    
    // Tabel
    'th_no'                  => 'No',
    'th_name'                => 'Nama Siswa',
    'th_nis'                 => 'NIS',
    'th_final_grade'         => 'Nilai Akhir',
    'th_predicate'           => 'Predikat',
    'th_desc'                => 'Deskripsi Capaian',
    'th_status'              => 'Status',
    
    // Empty State
    'empty_title'            => 'Pilih Jenis Sumatif',
    'empty_desc'             => 'Silakan pilih jenis sumatif dan klik Tampilkan Data untuk memulai penilaian.',
    
    // Toolbar & Tombol
    'toolbar_info'           => 'Mode Edit Aktif',
    'btn_save_draft'         => 'Simpan Draft',
    'btn_mark_ready'         => 'Tandai Siap Validasi',
    'btn_cancel_ready'       => 'Batal (Kembali ke Draft)',
    'btn_lock'               => 'Kunci Nilai',
    'toast_success'          => 'Berhasil',
    
    // Modal Konfirmasi
    'modal_confirm_title'    => 'Konfirmasi Tindakan',
    'modal_confirm_msg'      => 'Apakah Anda yakin ingin melanjutkan tindakan ini?',
    'btn_cancel'             => 'Batal',
    'btn_proceed'            => 'Ya, Lanjutkan',

    // ==========================================
    // JAVASCRIPT KEYS
    // ==========================================
    'js_desc_a'              => 'Menunjukkan pemahaman yang sangat baik dan mampu menerapkan konsep dengan sempurna.',
    'js_desc_b'              => 'Menunjukkan pemahaman yang baik dan mampu menerapkan konsep dengan cukup baik.',
    'js_desc_c'              => 'Menunjukkan pemahaman cukup baik namun perlu peningkatan dalam penerapan konsep.',
    'js_desc_d'              => 'Perlu bimbingan lebih lanjut untuk meningkatkan pemahaman konsep dasar.',
    'js_auto_save'           => '✓ Perubahan tersimpan otomatis',
    'js_loading'             => 'Memuat...',
    'js_ready'               => 'Siap Validasi',
    'js_locked'              => 'Terkunci',
    'js_draft'               => 'Draft',
    'js_err_load_server'     => 'Gagal memuat data dari server. Periksa koneksi atau console browser.',
    'js_err_no_data_filled'  => 'Belum ada nilai yang diisi. Silakan isi minimal satu nilai siswa.',
    'js_saving'              => 'Menyimpan...',
    'js_succ_draft'          => 'Draft nilai berhasil disimpan!',
    'js_err_save_data'       => 'Gagal menyimpan data.',
    'js_err_server_save'     => 'Gagal terhubung ke server saat menyimpan data.',
    'js_err_no_student'      => 'Tidak ada data siswa yang ditampilkan. Silakan load data terlebih dahulu.',
    'js_warn_empty_val'      => 'Masih ada nilai siswa yang kosong. Anda yakin ingin menandai data ini sebagai Siap Validasi?',
    'js_processing'          => 'Memproses...',
    'js_succ_ready'          => '✓ Nilai berhasil ditandai Siap Validasi!',
    'js_succ_ready_alert'    => 'Berhasil! Data nilai sekarang berstatus Siap Validasi.',
    'js_err_update_status'   => 'Gagal mengupdate status ke server.',
    'js_err_server_update'   => 'Gagal terhubung ke server saat mengupdate status.',
    'js_lock_warning'        => '<strong>PERINGATAN PENTING:</strong><br><br>Anda akan mengunci nilai akhir ini. Setelah dikunci:<br>• Nilai tidak dapat diubah atau ditarik kembali<br>• Nilai akan masuk secara resmi ke rapor<br>• Hanya Admin yang dapat membuka kunci<br><br>Apakah Anda yakin ingin melanjutkan?',
    'js_locking'             => 'Mengunci...',
    'js_succ_lock'           => '✓ Nilai berhasil dikunci! Data telah final.',
    'js_succ_lock_alert'     => 'Berhasil! Data nilai telah terkunci secara permanen.',
    'js_err_lock'            => 'Gagal mengunci nilai.',
    'js_err_server_lock'     => 'Gagal terhubung ke server saat mengunci nilai.',
    'js_warn_cancel_ready'   => 'Apakah Anda yakin ingin menarik kembali data ini menjadi Draft? Anda akan bisa mengedit nilai lagi.',
    'js_succ_cancel'         => '✓ Data berhasil dikembalikan ke Draft!',
    'js_succ_cancel_alert'   => 'Berhasil! Silakan edit kembali nilai siswa.',
    'js_err_server_conn'     => 'Gagal terhubung ke server.',
];