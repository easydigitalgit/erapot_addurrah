<?php

return [
    // --- HTML View ---
    'breadcrumb'        => 'Grading',
    'page_title'        => 'Input Student Grades',
    'page_header'       => 'Input Academic Grades',
    'page_desc'         => 'Manage student daily grades, assignments, and exams.',
    'btn_export'        => 'Export Excel',
    'btn_save'          => 'Save Grades',
    
    // Stats Section
    'stat_avg'          => 'Average',
    'stat_pass'         => 'Passed',
    'stat_fail'         => 'Remedial',
    'stat_total'        => 'Total Students',
    
    // Filters
    'filter_class'      => 'Select Class',
    'select_class'      => '-- Select Class --',
    'class_lbl'         => 'Class',
    'no_class_data'     => 'No class data yet',
    'filter_subject'    => 'Subject',
    'select_subject'    => '-- Select Subject --',
    'no_subj_data'      => 'No subject data yet',
    'filter_kkm'        => 'Passing Grade (KKM)',
    'kkm_title'         => 'Minimum Passing Criteria',
    'btn_show'          => 'Show',
    
    // Progress
    'class_progress'    => 'Class Grading Progress',
    
    // Table
    'th_no'             => 'No',
    'th_student_name'   => 'Student Name',
    'th_grade'          => 'Grade (0-100)',
    'th_predicate'      => 'Predicate',
    'th_status'         => 'Status',
    'th_notes'          => 'Evaluation Notes',
    
    // Empty State
    'empty_title'       => 'Ready to Input Grades?',
    'empty_desc'        => 'Please select a <strong class="text-gray-700 dark:text-slate-300">Class</strong> and <strong class="text-gray-700 dark:text-slate-300">Subject</strong> in the filter above to start entering student grades.',
    
    // --- Javascript ---
    'js_status_pass'    => 'Passed',
    'js_status_fail'    => 'Remedial',
    'js_swal_warn_title'=> 'Select Data First',
    'js_swal_warn_text' => 'Please select a Class and Subject before displaying data.',
    'js_swal_btn_ok'    => 'Got it',
    'js_loading_fetch'  => 'Fetching student data...',
    'js_ph_grade'       => '-',
    'js_ph_notes'       => 'Write appreciation/evaluation notes...',
    'js_no_students'    => 'No students found in this class.',
    'js_swal_err_title' => 'Failed to Load',
    'js_swal_err_text'  => 'An error occurred while fetching data. Try refreshing the page.',
    'js_err_load_table' => 'Failed to load data.',
    
    'js_swal_oops'      => 'Oops...',
    'js_swal_sel_save'  => 'Please select Class and Subject data first!',
    'js_swal_no_grade'  => 'No grades yet',
    'js_swal_fill_one'  => 'Please enter at least one student grade before saving.',
    'js_saving'         => 'Saving...',
    
    'js_swal_success'   => 'Success!',
    'js_swal_fail_save' => 'Failed to Save',
    'js_swal_sys_err'   => 'Error Occurred',
    'js_swal_err_conn'  => 'Check internet connection or contact admin.',
    
    'js_swal_sel_exp'   => 'Select Class and Subject before exporting!',
    'js_swal_prep_data' => 'Preparing Data...',
    'js_swal_prep_desc' => 'Please wait a moment, the Excel file is being generated.'
];