<?php
return [
    'page_title'          => 'Nilai Rapor Tahfidz - Rapor Digital',
    'breadcrumb_tahfidz'  => 'Tahfidz',
    'breadcrumb_rapor'    => 'Penilaian Rapor',
    
    // Header & Info
    'title_input'         => 'Finalisasi Rapor',
    'subtitle_input'      => 'Evaluasi akhir semester dengan bantuan',
    'ai_magic'            => 'AI Magic Assistant',
    
    // Filter Area
    'select_class'        => 'Pilih Kelas / Halaqoh',
    'ph_select_class'     => '-- Ketuk untuk memilih kelas --',
    'select_semester'     => 'Semester Penilaian',
    'sem_ganjil'          => 'Semester Ganjil (1)',
    'sem_genap'           => 'Semester Genap (2)',
    'btn_open_sheet'      => 'Buka Lembar',
    
    // Empty State
    'empty_area_title'    => 'Area Pengisian Rapor Kosong',
    'empty_area_desc'     => 'Pilih kelas dan semester di atas, lalu klik "Buka Lembar" untuk memuat form penilaian santri.',
    
    // Table Header
    'sheet_title'         => 'Lembar Penilaian:',
    'progress_title'      => 'Progres Pengisian Rapor',
    'ready_to_print'      => 'Siap Cetak',
    'status_waiting'      => 'Menunggu...',
    'status_done'         => '✅ Selesai',
    'status_not_yet'      => '⏳ Belum',
    'btn_magic_autofill'  => 'Magic Auto-Fill',
    
    // Table Columns
    'th_no'               => 'No',
    'th_profile_context'  => 'Profil & Konteks Capaian',
    'th_predicate'        => 'Predikat Nilai',
    'th_narration'        => 'Narasi / Catatan Rapor',
    
    // Warning & Submit
    'warning_narration'   => 'Pastikan semua',
    'warning_narration_b' => 'kolom narasi terisi',
    'warning_narration_c' => '. Sistem tidak akan menyimpan data jika narasinya kosong.',
    'btn_save_report'     => 'Simpan Nilai Semester',
    
    // Guideline / SOP
    'guide_title'         => 'Pedoman Penulisan Narasi Rapor',
    'guide_badge'         => 'Tips Ustadz',
    'guide_desc'          => 'Pastikan kalimat yang diberikan di rapor bersifat membangun, objektif, dan informatif bagi wali murid.',
    'guide_1_title'       => 'Gunakan Bahasa Positif',
    'guide_1_desc'        => 'Awali narasi dengan kalimat apresiasi (contoh: "Alhamdulillah, ananda...") sebelum memberikan saran perbaikan.',
    'guide_2_title'       => 'Sebutkan Capaian Spesifik',
    'guide_2_desc'        => 'Sebutkan secara jelas batas hafalan terakhir santri (surah/juz) agar orang tua tahu persis perkembangan anaknya.',
    'guide_3_title'       => 'Berikan Solusi Konkret',
    'guide_3_desc'        => 'Jika hafalan kurang lancar, berikan solusi jelas. (contoh: "Mohon bimbingan muroja\'ah ba\'da maghrib.")',
    'guide_4_title'       => 'Gunakan Magic Auto-Fill',
    'guide_4_desc'        => 'Klik tombol Magic Auto-Fill di atas tabel. Sistem AI otomatis meracik kalimat sesuai setoran santri!',
    
    // Predicate Legend
    'scale_title'         => 'Skala Predikat Rapor',
    'scale_a_title'       => 'Sangat Baik',
    'scale_a_badge'       => 'Mutqin',
    'scale_b_title'       => 'Baik',
    'scale_b_badge'       => 'Lancar',
    'scale_c_title'       => 'Cukup',
    'scale_c_badge'       => 'Terbata',
    'scale_d_title'       => 'Kurang',
    'scale_d_badge'       => 'Evaluasi',
    
    // Javascript Messages
    'js_alert_title_hi'   => 'Halo Ustadz!',
    'js_alert_desc_hi'    => 'Silakan ketuk untuk memilih kelas terlebih dahulu ya.',
    'js_loading_sheet'    => 'Menyiapkan Lembar Rapor...',
    'js_no_student'       => 'Belum ada santri di kelas ini.',
    'js_achievement'      => 'Capaian:',
    'js_not_deposited'    => 'Belum Setor',
    'js_times_deposit'    => 'x Setor',
    'js_ph_narration'     => 'Ketik narasi rapor di sini...',
    'js_err_fetch'        => '❌ Gagal memuat data. Periksa koneksi internet Anda.',
    
    // Predicates Text
    'pred_a'              => '🌟 Sangat Baik (A)',
    'pred_b'              => '✨ Baik (B)',
    'pred_c'              => '⚠️ Cukup (C)',
    'pred_d'              => '🚨 Kurang (D)',
    
    // Auto Fill Text (ID)
    'af_achievement'      => ' Capaian hafalan terakhir ananda sampai pada Surah',
    'af_active'           => ' Ananda sangat rajin dengan total',
    'af_active_end'       => ' kali setoran.',
    'af_inactive'         => ' Sayangnya, ananda belum menyetorkan hafalan sama sekali semester ini.',
    'af_a_text'           => 'Alhamdulillah, ananda memiliki hafalan yang sangat mutqin dan tajwid yang sempurna.',
    'af_a_end'            => ' Pertahankan prestasi luar biasa ini.',
    'af_b_text'           => 'Hafalan ananda mengalir dengan baik dan lancar.',
    'af_b_end'            => ' Terus tingkatkan intensitas muroja\'ah di rumah agar hafalan semakin melekat kuat.',
    'af_c_text'           => 'Hafalan ananda cukup, namun masih sering terbata-bata.',
    'af_c_end'            => ' Mohon perbanyak waktu mengulang hafalan (muroja\'ah) di rumah bersama bimbingan orang tua.',
    'af_d_text'           => 'Hafalan ananda perlu bimbingan dan perhatian intensif.',
    'af_d_end'            => ' Dimohon kerja sama orang tua untuk lebih ketat memantau jadwal ziyadah dan muroja\'ah ananda di rumah.',
    
    'js_toast_af_title'   => '🪄 Voila!',
    'js_toast_af_desc'    => 'narasi rapor berhasil diracik otomatis!',
    'js_af_full_title'    => 'Sudah Penuh',
    'js_af_full_desc'     => 'Semua kolom narasi sudah terisi. Magic Auto-Fill hanya mengisi kolom yang masih kosong.',
    
    'js_saving'           => 'Menyimpan...',
    'js_saving_title'     => 'Menyimpan Nilai...',
    'js_saving_desc'      => 'Merekap seluruh nilai dan narasi ke database pusat.',
    'js_success_title'    => 'Alhamdulillah!',
    'js_success_default'  => 'Nilai rapor hafalan berhasil difinalisasi.',
    'js_warning_title'    => 'Disimpan Sebagian',
    'js_error_title'      => 'Gagal',
    'js_server_error'     => 'Error Jaringan',
    'js_server_error_desc'=> 'Terjadi masalah pada koneksi ke server.',
];