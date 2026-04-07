<?php
return [
    'page_title'       => 'Backup Sistem',
    'breadcrumb'       => 'Sistem',
    'title_main'       => 'Backup Data Sistem',
    'title_desc'       => 'Amankan seluruh data akademik dan sistem secara berkala.',
    'btn_backup_now'   => 'Backup Sekarang',

    // Protection Section
    'prot_title'       => 'Proteksi Data Terintegrasi',
    'prot_badge'       => 'Enkripsi AES-256',
    'prot_1'           => 'Setiap backup <strong>terenkripsi</strong> dan tersimpan dengan aman',
    'prot_2'           => 'Semua aktivitas backup dan restore <strong>tercatat dalam audit log</strong>',
    'prot_3'           => 'Restore memerlukan <strong>konfirmasi ganda</strong> untuk mencegah kesalahan',

    // Stats Section
    'stat_files'       => 'Total File',
    'stat_files_desc'  => 'Tersimpan di direktori server',
    'stat_size'        => 'Total Ukuran',
    'stat_size_desc'   => 'Akumulasi seluruh file .sql',
    'stat_sys'         => 'Status Sistem',
    'stat_sys_val'     => 'Normal',
    'stat_sys_desc'    => 'Siap untuk memproses backup',
    'stat_storage'     => 'Storage Warning',
    'stat_stor_val'    => 'Aman',
    'stat_stor_desc'   => 'Cukup untuk jangka panjang',

    // Select Data Section
    'sel_title'        => 'Pilih Data untuk Backup',
    'sel_cat1'         => 'Data Siswa & Orang Tua',
    'sel_cat1_desc'    => 'data',
    'sel_cat2'         => 'Nilai & Rapor Akademik',
    'sel_cat2_desc'    => 'baris',
    'sel_cat3'         => 'Master Data Akademik',
    'sel_cat3_desc'    => 'Kelas, mapel, guru',
    'sel_cat4'         => 'Konfigurasi Sistem',
    'sel_cat4_desc'    => 'Pengaturan & Hak akses',

    // Mode Section
    'mode_title'       => 'Mode Backup',
    'mode_full'        => 'Full Backup',
    'mode_full_desc'   => 'Seluruh data database utuh',
    'mode_part'        => 'Partial (Pilih)',
    'mode_part_desc'   => 'Hanya yang dicentang di atas',

    // Auto Backup Section
    'auto_title'       => 'Jadwal Otomatis',
    'auto_freq'        => 'Frekuensi Backup',
    'auto_freq_daily'  => 'Harian',
    'auto_freq_weekly' => 'Mingguan',
    'auto_freq_monthly'=> 'Bulanan',
    'auto_time'        => 'Waktu Eksekusi (Server)',
    'auto_time_desc'   => 'Backup berjalan via Cronjob pada jam ini',
    'auto_retention'   => 'Retensi Penyimpanan',
    'auto_ret_7'       => 'Simpan selama 7 Hari',
    'auto_ret_30'      => 'Simpan selama 30 Hari',
    'auto_ret_60'      => 'Simpan selama 60 Hari',
    'auto_notify'      => 'Kirim email notifikasi jika berhasil/gagal',
    'btn_save_setting' => 'Simpan Pengaturan Jadwal',

    // History Section
    'hist_title'       => 'Riwayat Backup File',
    'hist_desc'        => 'Daftar arsip database yang tersimpan di server',
    'hist_badge'       => 'File Tersedia',
    'th_date'          => 'Tanggal & Waktu',
    'th_type'          => 'Tipe Backup',
    'th_name'          => 'Nama File',
    'th_size'          => 'Ukuran',
    'th_action'        => 'Aksi',
    'empty_hist'       => 'Belum ada file backup yang tersimpan di direktori.',
    'badge_full'       => 'Full Backup',
    'badge_partial'    => 'Partial',

    // Restore External Section
    'ext_title'        => 'Restore Database dari Eksternal',
    'ext_desc'         => 'Upload file .sql manual dari device Anda',
    'ext_drag'         => 'Klik di sini untuk upload file backup',
    'ext_or'           => 'Atau drag & drop file ke area ini',
    'ext_format'       => 'Format: .sql (Max 500 MB)',
    
    'warn_title'       => 'Peringatan Kritis Restorasi Data',
    'warn_1'           => 'Melakukan restore akan <strong>menghapus dan menimpa seluruh data saat ini</strong> dengan data dari file backup.',
    'warn_2'           => 'Proses restorasi bersifat permanen dan <strong>tidak dapat dibatalkan (Undo)</strong> setelah dimulai.',
    'warn_3'           => 'Sangat disarankan untuk melakukan <strong>Backup Full Manual</strong> terlebih dahulu sebelum melakukan restore ini.',

    // Modals
    'mod_backup_title' => 'Konfirmasi Eksekusi',
    'mod_backup_desc'  => 'Sistem akan mengekstrak tabel sesuai pilihan Anda menjadi arsip (.sql).',
    'mod_backup_chk'   => 'Saya memahami proses backup ini dan siap melanjutkan eksekusi',
    'btn_cancel'       => 'Batal',
    'btn_exec'         => 'Eksekusi',

    'mod_rest_title'   => 'Restore Data Sistem',
    'mod_rest_desc'    => 'Anda akan mengembalikan sistem menggunakan file:',
    'mod_rest_warn'    => 'PERINGATAN KRITIS:',
    'mod_rest_warn_txt'=> 'Data saat ini akan <strong>DIHAPUS & DIGANTI SECARA PERMANEN</strong> dengan isi dari file backup tersebut. Kesalahan proses tidak bisa dibatalkan.',
    'mod_rest_chk1'    => 'Saya paham data saat ini akan tertimpa hancur',
    'mod_rest_chk2'    => 'Saya bertanggung jawab atas restorasi server ini',
    'btn_exec_rest'    => 'Eksekusi Restore',

    // JS Messages
    'js_saving'        => 'Menyimpan...',
    'js_conf_on'       => 'Konfigurasi aktif, silakan simpan.',
    'js_conf_off'      => 'Konfigurasi nonaktif, silakan simpan.',
    'js_err_conn'      => 'Gagal terhubung ke server.',
    'js_warn_check'    => 'Harap centang konfirmasi terlebih dahulu!',
    'js_warn_cat'      => 'Anda harus memilih minimal 1 kategori untuk Partial Backup!',
    'js_backup_prog'   => 'Sedang Mengekstrak Database...',
    'js_backup_desc'   => 'Mohon jangan tutup atau refresh halaman ini.',
    'js_del_conf'      => 'Hapus permanen file:',
    'js_deleting'      => 'Menghapus backup...',
    'js_err_no_file'   => 'Tidak ada file backup yang dipilih!',
    'js_warn_all_chk'  => 'Harap centang semua konfirmasi terlebih dahulu!',
    'js_rest_prog'     => 'Sedang Merestore Database...',
    'js_rest_desc'     => 'Proses ini mungkin memakan waktu beberapa menit. Jangan tutup halaman!',
];