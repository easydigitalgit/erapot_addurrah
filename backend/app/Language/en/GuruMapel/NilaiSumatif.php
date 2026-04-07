<?php

return [
    // Page Info
    'page_title'             => 'Summative Grades',
    'page_subtitle'          => 'Manage student summative grades (Midterm/Final Exams)',
    
    // Top Card Info
    'info_subject'           => 'Subject',
    'info_class'             => 'Class',
    'info_student_count'     => 'Total Students',
    'info_student_text'      => 'Students',
    'info_status'            => 'Status',
    'status_draft'           => 'Draft',
    
    // Warning
    'warning_title'          => 'Warning',
    'warning_desc'           => 'Please make sure you have selected the summative type and loaded the data before entering grades.',
    
    // Configuration
    'config_title'           => 'Grade Configuration',
    'type_label'             => 'Summative Type',
    'type_select'            => '-- Select Type --',
    'type_pts'               => 'Midterm Assessment (PTS)',
    'type_pas'               => 'Final Semester Assessment (PAS)',
    'type_sas'               => 'Final Summative Assessment (SAS)',
    'weight_label'           => 'Weight (%)',
    'kkm_label'              => 'Passing Grade (KKM)',
    'btn_load_data'          => 'Load Data',
    'config_info'            => 'Select the summative type and click "Load Data" to start entering grades.',
    
    // Progress
    'progress_title'         => 'Process Status',
    'step_1'                 => 'Drafting',
    'step_2'                 => 'Ready for Validation',
    'step_3'                 => 'Locked',
    
    // Table
    'th_no'                  => 'No',
    'th_name'                => 'Student Name',
    'th_nis'                 => 'Student ID (NIS)',
    'th_final_grade'         => 'Final Grade',
    'th_predicate'           => 'Predicate',
    'th_desc'                => 'Achievement Description',
    'th_status'              => 'Status',
    
    // Empty State
    'empty_title'            => 'Select Summative Type',
    'empty_desc'             => 'Please select the summative type and click Load Data to begin grading.',
    
    // Toolbar & Buttons
    'toolbar_info'           => 'Edit Mode Active',
    'btn_save_draft'         => 'Save Draft',
    'btn_mark_ready'         => 'Mark Ready for Validation',
    'btn_cancel_ready'       => 'Cancel (Back to Draft)',
    'btn_lock'               => 'Lock Grades',
    'toast_success'          => 'Success',
    
    // Modal Confirm
    'modal_confirm_title'    => 'Action Confirmation',
    'modal_confirm_msg'      => 'Are you sure you want to proceed with this action?',
    'btn_cancel'             => 'Cancel',
    'btn_proceed'            => 'Yes, Proceed',

    // ==========================================
    // JAVASCRIPT KEYS
    // ==========================================
    'js_desc_a'              => 'Shows excellent understanding and can apply concepts perfectly.',
    'js_desc_b'              => 'Shows good understanding and can apply concepts fairly well.',
    'js_desc_c'              => 'Shows fair understanding but needs improvement in applying concepts.',
    'js_desc_d'              => 'Needs further guidance to improve understanding of basic concepts.',
    'js_auto_save'           => '✓ Changes saved automatically',
    'js_loading'             => 'Loading...',
    'js_ready'               => 'Ready for Validation',
    'js_locked'              => 'Locked',
    'js_draft'               => 'Draft',
    'js_err_load_server'     => 'Failed to load data from the server. Check your connection or browser console.',
    'js_err_no_data_filled'  => 'No grades have been entered. Please enter at least one student\'s grade.',
    'js_saving'              => 'Saving...',
    'js_succ_draft'          => 'Grade draft successfully saved!',
    'js_err_save_data'       => 'Failed to save data.',
    'js_err_server_save'     => 'Failed to connect to the server while saving data.',
    'js_err_no_student'      => 'No student data displayed. Please load data first.',
    'js_warn_empty_val'      => 'There are still empty student grades. Are you sure you want to mark this data as Ready for Validation?',
    'js_processing'          => 'Processing...',
    'js_succ_ready'          => '✓ Grades successfully marked as Ready for Validation!',
    'js_succ_ready_alert'    => 'Success! The grade data is now Ready for Validation.',
    'js_err_update_status'   => 'Failed to update status to the server.',
    'js_err_server_update'   => 'Failed to connect to the server while updating status.',
    'js_lock_warning'        => '<strong>IMPORTANT WARNING:</strong><br><br>You are about to lock these final grades. Once locked:<br>• Grades cannot be changed or reverted<br>• Grades will officially go into the report card<br>• Only the Admin can unlock them<br><br>Are you sure you want to proceed?',
    'js_locking'             => 'Locking...',
    'js_succ_lock'           => '✓ Grades successfully locked! Data is now final.',
    'js_succ_lock_alert'     => 'Success! Grade data has been permanently locked.',
    'js_err_lock'            => 'Failed to lock grades.',
    'js_err_server_lock'     => 'Failed to connect to the server while locking grades.',
    'js_warn_cancel_ready'   => 'Are you sure you want to revert this data back to Draft? You will be able to edit the grades again.',
    'js_succ_cancel'         => '✓ Data successfully reverted to Draft!',
    'js_succ_cancel_alert'   => 'Success! You can now edit the student grades again.',
    'js_err_server_conn'     => 'Failed to connect to the server.',
];