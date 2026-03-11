<?php

return [
    'page_title_browser' => 'Validasi & Lock Nilai - Rapor Digital',
    'grading_menu' => 'Penilaian',
    'page_title' => 'Validasi & Lock Nilai',
    'bismillah' => 'بِسْمِ اللَّهِ الرَّحْمَٰنِ الرَّحِيمِ',
    'bismillah_translation' => 'Dengan menyebut nama Allah Yang Maha Pengasih lagi Maha Penyayang',
    'page_subtitle' => 'Pastikan seluruh nilai telah lengkap dan sesuai sebelum rapor dikunci.',

    // Buttons
    'btn_mass_validation' => 'Validasi Massal',
    'btn_mass_lock' => 'Lock Nilai',

    // Alert
    'important_info' => 'Informasi Penting',
    'important_info_desc' => 'Nilai yang telah dikunci tidak dapat diubah kecuali oleh Admin Utama. Pastikan semua data sudah akurat sebelum melakukan lock.',

    // Stats
    'total_classes' => 'Total Kelas',
    'total_classes_desc' => 'Kelas aktif semester ini',
    'ready_to_validate' => 'Siap Validasi',
    'ready_to_validate_desc' => 'Nilai lengkap 100%',
    'incomplete' => 'Belum Lengkap',
    'incomplete_desc' => 'Masih ada nilai kosong',
    'locked' => 'Terkunci',
    'locked_desc' => 'Nilai sudah dikunci',

    // Filters
    'filter_search' => 'Filter & Pencarian',
    'level' => 'Tingkat',
    'all_levels' => 'Semua Tingkat',
    'class' => 'Kelas',
    'study_group' => 'Rombel',
    'all_study_groups' => 'Semua Rombel',
    'homeroom_teacher' => 'Wali Kelas',
    'all_homeroom_teachers' => 'Semua Wali Kelas',
    'validation_status' => 'Status Validasi',
    'all_statuses' => 'Semua Status',
    'status_ready' => 'Siap Lock (100%)',
    'status_incomplete' => 'Belum Lengkap',
    'status_locked' => 'Terkunci',
    'btn_apply' => 'Terapkan',
    'btn_reset' => 'Reset',

    // Table
    'th_level' => 'Tingkat',
    'th_study_group' => 'Rombel',
    'th_homeroom' => 'Wali Kelas',
    'th_academic' => 'Akademik',
    'th_character' => 'Karakter',
    'th_tahfidz' => 'Tahfidz',
    'th_status' => 'Status',
    'th_action' => 'Aksi',
    'progress_complete' => 'Lengkap',
    'progress_process' => 'Proses',
    'badge_locked' => 'Locked',
    'tooltip_lock' => 'Lock Nilai',

    // Detail Drawer
    'drawer_title' => 'Detail Validasi',
    'checklist_title' => 'Checklist Kelengkapan',
    'chk_all_subjects' => 'Semua mata pelajaran terisi',
    'chk_subjects_count' => '{count}/13 mata pelajaran',
    'chk_weighting' => 'Bobot penilaian sesuai',
    'chk_weighting_desc' => 'Pengetahuan 40% + Keterampilan 30%...',
    'chk_final_grade' => 'Nilai akhir valid',
    'chk_student_count' => '{count}/{total} siswa memiliki nilai lengkap',
    'chk_notes' => 'Catatan Wali Kelas',
    'chk_notes_desc' => 'Deskripsi karakter sudah dilengkapi',

    'preview_title' => 'Preview Ringkas',
    'avg_class' => 'Rata-rata Kelas',
    'highest_score' => 'Nilai Tertinggi',
    'lowest_score' => 'Nilai Terendah',
    'completed_students' => 'Siswa Tuntas',

    'history_title' => 'Riwayat Validasi',
    'btn_back' => 'Kembali',
    'btn_mark_valid' => 'Tandai Valid',

    // Lock Modal
    'modal_lock_title' => 'Konfirmasi Lock Nilai',
    'modal_lock_subtitle' => 'Anda akan mengunci seluruh nilai untuk kelas',
    'security_warning' => 'Peringatan Keamanan',
    'warn_1' => 'Nilai yang dikunci <strong>tidak dapat diubah</strong> kecuali oleh Admin Utama.',
    'warn_2' => 'Proses ini akan dicatat dalam audit trail sistem.',
    'warn_3' => 'Pastikan <strong>semua data sudah akurat</strong> sebelum melanjutkan.',
    'confirm_checkbox' => 'Saya telah memeriksa seluruh nilai dan siap untuk mengunci.',
    'btn_cancel' => 'Batal',
    'btn_execute_lock' => 'Eksekusi Lock',

    // JS Strings
    'js_drawer_ready' => '✓ Siap Lock',
    'js_please_wait' => 'Sebentar...',
    'js_check_confirm' => 'Mohon centang kotak konfirmasi terlebih dahulu.',
    'js_processing' => 'Memproses...',
    'js_lock_success_title' => 'Berhasil Dikunci!',
    'js_awesome' => 'Mantap!',
    'js_lock_fail_title' => 'Gagal Mengunci',
    'js_check_again' => 'Saya Cek Kembali',
    'js_check_monitoring' => 'Cek Monitoring Input Nilai',
    'js_system_error_title' => 'Terjadi Kesalahan Sistem',
    'js_system_error_desc' => 'Tidak dapat menghubungi server. Cek koneksi internet Anda.',
    'js_mass_validating' => 'Memvalidasi kelas yang siap lock...',
    'js_mass_lock_info' => 'Fitur lock massal akan mengunci semua kelas yang sudah divalidasi',
    'js_filter_applied' => 'Filter berhasil diterapkan',
    'js_filter_reset' => 'Filter direset',
    'js_class_validated' => 'Kelas {kelas} berhasil divalidasi',
    'js_reminder_sent' => 'Reminder dikirim ke wali kelas {kelas}',
    'js_marked_valid' => 'Kelas berhasil ditandai valid dan siap untuk lock',
    'js_applying_filter' => 'Menerapkan filter...',
];
