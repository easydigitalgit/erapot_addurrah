<?php
return [
    'page_title'       => 'System Backup',
    'breadcrumb'       => 'System',
    'title_main'       => 'System Data Backup',
    'title_desc'       => 'Secure all academic and system data periodically.',
    'btn_backup_now'   => 'Backup Now',

    // Protection Section
    'prot_title'       => 'Integrated Data Protection',
    'prot_badge'       => 'AES-256 Encryption',
    'prot_1'           => 'Every backup is <strong>encrypted</strong> and securely stored',
    'prot_2'           => 'All backup & restore activities are <strong>recorded in audit logs</strong>',
    'prot_3'           => 'Restore requires <strong>double confirmation</strong> to prevent errors',

    // Stats Section
    'stat_files'       => 'Total Files',
    'stat_files_desc'  => 'Stored in server directory',
    'stat_size'        => 'Total Size',
    'stat_size_desc'   => 'Accumulation of all .sql files',
    'stat_sys'         => 'System Status',
    'stat_sys_val'     => 'Normal',
    'stat_sys_desc'    => 'Ready to process backup',
    'stat_storage'     => 'Storage Warning',
    'stat_stor_val'    => 'Safe',
    'stat_stor_desc'   => 'Sufficient for long term',

    // Select Data Section
    'sel_title'        => 'Select Data for Backup',
    'sel_cat1'         => 'Student & Parent Data',
    'sel_cat1_desc'    => 'records',
    'sel_cat2'         => 'Grades & Academic Reports',
    'sel_cat2_desc'    => 'rows',
    'sel_cat3'         => 'Academic Master Data',
    'sel_cat3_desc'    => 'Classes, subjects, teachers',
    'sel_cat4'         => 'System Configuration',
    'sel_cat4_desc'    => 'Settings & Access rights',

    // Mode Section
    'mode_title'       => 'Backup Mode',
    'mode_full'        => 'Full Backup',
    'mode_full_desc'   => 'Entire intact database',
    'mode_part'        => 'Partial (Select)',
    'mode_part_desc'   => 'Only the checked ones above',

    // Auto Backup Section
    'auto_title'       => 'Automated Schedule',
    'auto_freq'        => 'Backup Frequency',
    'auto_freq_daily'  => 'Daily',
    'auto_freq_weekly' => 'Weekly',
    'auto_freq_monthly'=> 'Monthly',
    'auto_time'        => 'Execution Time (Server)',
    'auto_time_desc'   => 'Backup runs via Cronjob at this hour',
    'auto_retention'   => 'Storage Retention',
    'auto_ret_7'       => 'Keep for 7 Days',
    'auto_ret_30'      => 'Keep for 30 Days',
    'auto_ret_60'      => 'Keep for 60 Days',
    'auto_notify'      => 'Send email notification on success/failure',
    'btn_save_setting' => 'Save Schedule Settings',

    // History Section
    'hist_title'       => 'Backup File History',
    'hist_desc'        => 'List of database archives stored on the server',
    'hist_badge'       => 'Files Available',
    'th_date'          => 'Date & Time',
    'th_type'          => 'Backup Type',
    'th_name'          => 'File Name',
    'th_size'          => 'Size',
    'th_action'        => 'Action',
    'empty_hist'       => 'No backup files stored in the directory yet.',
    'badge_full'       => 'Full Backup',
    'badge_partial'    => 'Partial',

    // Restore External Section
    'ext_title'        => 'Restore Database from External',
    'ext_desc'         => 'Manually upload .sql file from your device',
    'ext_drag'         => 'Click here to upload backup file',
    'ext_or'           => 'Or drag & drop file into this area',
    'ext_format'       => 'Format: .sql (Max 500 MB)',
    
    'warn_title'       => 'Critical Data Restoration Warning',
    'warn_1'           => 'Performing a restore will <strong>delete and overwrite all current data</strong> with data from the backup file.',
    'warn_2'           => 'The restoration process is permanent and <strong>cannot be undone</strong> once started.',
    'warn_3'           => 'It is highly recommended to perform a <strong>Manual Full Backup</strong> first before doing this restore.',

    // Modals
    'mod_backup_title' => 'Execution Confirmation',
    'mod_backup_desc'  => 'The system will extract tables according to your selection into a (.sql) archive.',
    'mod_backup_chk'   => 'I understand this backup process and am ready to proceed',
    'btn_cancel'       => 'Cancel',
    'btn_exec'         => 'Execute',

    'mod_rest_title'   => 'System Data Restore',
    'mod_rest_desc'    => 'You are about to restore the system using the file:',
    'mod_rest_warn'    => 'CRITICAL WARNING:',
    'mod_rest_warn_txt'=> 'Current data will be <strong>PERMANENTLY DELETED & REPLACED</strong> by the contents of the backup file. Process errors cannot be undone.',
    'mod_rest_chk1'    => 'I understand that current data will be destroyed',
    'mod_rest_chk2'    => 'I take responsibility for this server restoration',
    'btn_exec_rest'    => 'Execute Restore',

    // JS Messages
    'js_saving'        => 'Saving...',
    'js_conf_on'       => 'Configuration active, please save.',
    'js_conf_off'      => 'Configuration inactive, please save.',
    'js_err_conn'      => 'Failed to connect to the server.',
    'js_warn_check'    => 'Please check the confirmation first!',
    'js_warn_cat'      => 'You must select at least 1 category for Partial Backup!',
    'js_backup_prog'   => 'Extracting Database...',
    'js_backup_desc'   => 'Please do not close or refresh this page.',
    'js_del_conf'      => 'Permanently delete file:',
    'js_deleting'      => 'Deleting backup...',
    'js_err_no_file'   => 'No backup file selected!',
    'js_warn_all_chk'  => 'Please check all confirmations first!',
    'js_rest_prog'     => 'Restoring Database...',
    'js_rest_desc'     => 'This process may take a few minutes. Do not close the page!',
];