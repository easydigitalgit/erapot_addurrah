<?php

return [
    // --- TEKS VIEW (HTML) ---
    'page_title'          => 'Aturan & Bobot Penilaian',
    'page_subtitle'       => 'Atur komponen, bobot, dan aturan perhitungan nilai rapor',
    'breadcrumb_config'   => 'Konfigurasi Akademik',
    'bismillah'           => 'بِسْمِ اللَّهِ الرَّحْمَٰنِ الرَّحِيمِ',
    'bismillah_trans'     => 'Dengan menyebut nama Allah Yang Maha Pengasih lagi Maha Penyayang',
    'btn_add_rule'        => 'Tambah Aturan',
    'btn_reset'           => 'Reset ke Default',
    'btn_history'         => 'Riwayat',
    
    // --- SUMMARY CARDS ---
    'total_components'    => 'Total Komponen',
    'active_categories'   => 'Kategori aktif',
    'total_weight'        => 'Total Bobot',
    'curriculum'          => 'Kurikulum',
    'validation_status'   => 'Status Validasi',
    'validated'           => 'Tervalidasi',
    'ready_to_use'        => 'Siap digunakan',
    
    // --- WARNING ALERT ---
    'warning_title'       => '⚠️ Total Bobot Tidak Seimbang',
    'warning_desc'        => 'Total bobot penilaian saat ini: ',
    'warning_desc_2'      => '. Harap sesuaikan bobot agar total mencapai 100% sebelum disimpan.',
    'btn_auto_balance'    => 'Auto Balance Bobot',
    
    // --- WEIGHT CONFIGURATION ---
    'weight_structure'    => 'Struktur Bobot Penilaian',
    'academic'            => '📘 Akademik',
    'academic_desc'       => 'Penilaian pengetahuan & keterampilan',
    'knowledge'           => 'Pengetahuan',
    'knowledge_desc'      => 'Ulangan, tugas, kuis',
    'skills'              => 'Keterampilan',
    'skills_desc'         => 'Praktik, proyek, portofolio',
    'pts'                 => 'PTS (Penilaian Tengah Semester)',
    'pts_desc'            => 'Ujian tengah semester',
    'pas'                 => 'PAS (Penilaian Akhir Semester)',
    'pas_desc'            => 'Ujian akhir semester',
    
    'character'           => '🌱 Karakter',
    'character_desc'      => 'Akhlak, kedisiplinan, tanggung jawab',
    'morals'              => 'Akhlak',
    'morals_desc'         => 'Sopan santun, kejujuran',
    'discipline'          => 'Kedisiplinan',
    'discipline_desc'     => 'Kehadiran, ketepatan waktu',
    'responsibility'      => 'Tanggung Jawab',
    'responsibility_desc' => 'Penyelesaian tugas',
    
    'islamic'             => '🕌 Keislaman',
    'islamic_desc'        => 'Tahfidz, ibadah, akhlak islami',
    'tahfidz'             => 'Tahfidz',
    'tahfidz_desc'        => 'Hafalan Al-Qur\'an',
    'worship'             => 'Ibadah Harian',
    'worship_desc'        => 'Shalat, doa, tilawah',
    'islamic_morals'      => 'Akhlak Islami',
    'islamic_morals_desc' => 'Adab, akhlakul karimah',
    
    // --- FORMULA PREVIEW ---
    'preview_formula'     => 'Preview Formula',
    'weight_distribution' => 'Distribusi Bobot Penilaian',
    'formula_calc'        => 'Formula Perhitungan',
    'final_grade'         => 'Nilai Akhir',
    'example_calc'        => 'Contoh Perhitungan',
    
    // --- GRADING RULES TABLE ---
    'grading_rules'       => 'Aturan Penilaian & Predikat',
    'th_range'            => 'Rentang Nilai',
    'th_predicate'        => 'Predikat',
    'th_desc'             => 'Deskripsi',
    'th_status'           => 'Status',
    'th_action'           => 'Aksi',
    'empty_rules'         => 'Belum ada aturan nilai.',
    'badge_active'        => 'Aktif',
    'badge_inactive'      => 'Non-Aktif',
    'btn_delete'          => 'Hapus',
    
    // --- IMPACT INFO ---
    'sync_impact'         => 'Sinkronisasi & Dampak Perubahan',
    'impact_desc'         => 'Perubahan aturan penilaian akan berdampak pada komponen sistem berikut:',
    'impact_1_title'      => 'Perhitungan Rapor',
    'impact_1_desc'       => 'Nilai akhir siswa akan dihitung ulang',
    'impact_2_title'      => 'Insight Akademik',
    'impact_2_desc'       => 'Dashboard statistik akan terupdate',
    'impact_3_title'      => 'Peringkat Kelas',
    'impact_3_desc'       => 'Ranking siswa akan diurutkan ulang',
    'impact_4_title'      => 'Laporan Wali Kelas',
    'impact_4_desc'       => 'Data laporan akan disesuaikan',
    
    // --- SECURITY WARNING ---
    'sec_policy'          => '🔒 Kebijakan Perubahan Aturan',
    'sec_1'               => 'Perubahan hanya diizinkan sebelum periode input nilai dimulai',
    'sec_2'               => 'Setelah rapor dikunci, aturan tidak dapat diubah untuk semester berjalan',
    'sec_3'               => 'Seluruh perubahan akan dicatat dalam riwayat audit sistem',
    'sec_4'               => 'Koordinasikan dengan tim kurikulum dan wali kelas sebelum mengubah aturan',
    
    // --- BUTTONS ---
    'btn_preview'         => 'Preview Perubahan',
    'btn_save_changes'    => 'Simpan Perubahan',
    
    // --- MODALS ---
    'modal_add_title'     => 'Tambah Aturan Penilaian',
    'modal_add_desc'      => 'Tentukan aturan predikat nilai baru',
    'lbl_predicate'       => 'Predikat',
    'ph_predicate'        => 'Contoh: A+, B-, E',
    'hint_predicate'      => 'Masukkan huruf predikat (A-E) dengan opsional + atau -',
    'lbl_desc_pred'       => 'Deskripsi Predikat',
    'ph_desc_pred'        => 'Contoh: Sangat Baik, Baik, Cukup',
    'lbl_min_val'         => 'Nilai Minimum',
    'lbl_max_val'         => 'Nilai Maksimum',
    'lbl_desc_comp'       => 'Deskripsi Pencapaian Kompetensi',
    'ph_desc_comp'        => 'Jelaskan tingkat pencapaian kompetensi...',
    'lbl_badge_color'     => 'Warna Badge',
    'lbl_optional'        => '(Opsional)',
    'rule_status'         => 'Status Aturan',
    'rule_status_desc'    => 'Aktifkan aturan ini setelah disimpan',
    'tips_title'          => 'Tips Aturan Penilaian:',
    'tip_1'               => 'Pastikan rentang nilai tidak tumpang tindih',
    'tip_2'               => 'Gunakan predikat yang konsisten',
    'btn_cancel'          => 'Batal',
    'btn_save_rule'       => 'Simpan Aturan',
    
    'modal_hist_title'    => 'Riwayat Perubahan',
    'loading_data'        => 'Memuat data...',
    'btn_close'           => 'Tutup',

    // --- TEKS JAVASCRIPT ---
    'js_valid'            => '✔️ Valid',
    'js_unbalanced'       => '⚠️ Tidak Seimbang',
    'js_saving'           => 'Menyimpan...',
    'js_err_range'        => 'Nilai minimum tidak boleh lebih besar dari nilai maksimum!',
    'js_succ_save'        => 'Berhasil! Bobot penilaian telah disimpan.',
    'js_fail_prefix'      => 'Gagal: ',
    'js_err_server'       => 'Terjadi kesalahan server.',
    'js_conf_reset'       => 'Apakah Anda yakin ingin mereset semua bobot ke pengaturan awal? Perubahan yang belum disimpan akan hilang.',
    'js_succ_reset'       => 'Berhasil mereset pengaturan!',
    'js_fail_reset'       => 'Gagal reset: ',
    'js_empty_hist'       => 'Belum ada riwayat perubahan.',
    'js_err_load_hist'    => 'Gagal memuat data riwayat.',
    'js_err_auto_bal'     => 'Isi minimal satu bobot sebelum melakukan Auto Balance!',
    'js_succ_auto_bal'    => 'Bobot berhasil disesuaikan otomatis menjadi 100%'
];