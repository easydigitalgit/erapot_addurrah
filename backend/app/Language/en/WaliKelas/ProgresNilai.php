<?php

return [
    'page_title'             => 'Subject Grade Progress',
    
    // Filter Card
    'filter_settings'        => 'Filter & View Settings',
    'filter_subject'         => 'Subject',
    'opt_all_subjects'       => 'All Subjects',
    'filter_status'          => 'Grade Status',
    'opt_all_status'         => 'All Statuses',
    'opt_safe_grade'         => 'Safe Grade (≥75)',
    'opt_warn_grade'         => 'Warning Grade (60-74)',
    'opt_crit_grade'         => 'Critical Grade (<60)',
    'filter_sort'            => 'Sort By',
    'opt_sort_az'            => 'Subject Name (A-Z)',
    'opt_sort_high'          => 'Highest Average',
    'opt_sort_low'           => 'Lowest Average',
    'filter_view'            => 'Data View',
    
    // Stats
    'stat_total_subject'     => 'Total Subjects',
    'stat_class_avg'         => 'Class Average',
    'stat_safe_subject'      => 'Safe Subjects',
    'stat_warn_subject'      => 'Critical/Warning Subjects',
    
    // Chart
    'chart_title'            => 'Class Average Comparison',
    'chart_subtitle'         => 'Average grade graph per subject',
    
    // Tabs
    'tab_all_subjects'       => 'All Subjects',
    'tab_subject_detail'     => 'Subject Detail',
    'tab_trend_analysis'     => 'Trend Analysis',
    
    // Table Headers
    'th_subject'             => 'Subject',
    'th_average'             => 'Average',
    'th_highest'             => 'Highest',
    'th_lowest'              => 'Lowest',
    'th_trend'               => 'Trend',
    'th_status'              => 'Status',
    'th_no'                  => 'No.',
    'th_student_name'        => 'Student Name',
    'th_grade'               => 'Report Grade',
    'th_action'              => 'Action',
    
    // Tab Detail
    'sel_sub_detail'         => 'Select a Subject to View Details:',
    'sel_sub_ph'             => 'Select one of the subjects above to view Detailed Analysis.',
    
    // Tab Tren
    'trend_safe'             => 'Safe/Positive Trend',
    'trend_warn'             => 'Needs Attention (Warning/Critical)',
    
    // Modals
    'modal_student_data'     => 'Student Data',
    'modal_student_subtitle' => 'List of student grades per subject',
    'btn_close'              => 'Close',
    'btn_export'             => 'Export Data',
    
    'modal_remedi_title'     => 'Create Remedial Program',
    'modal_remedi_subtitle'  => 'Intensive Coaching and Learning Program',
    'form_prog_name'         => 'Remedial Program Name',
    'form_duration'          => 'Duration (Weeks)',
    'form_frequency'         => 'Frequency Per Week',
    'form_freq_sel'          => 'Select Frequency',
    'form_freq_1'            => '1x Per Week',
    'form_freq_2'            => '2x Per Week',
    'form_freq_3'            => '3x Per Week',
    'form_method'            => 'Learning Method',
    'opt_meth_1'             => 'Small Group Study',
    'opt_meth_2'             => '1-on-1 Private Tutoring',
    'opt_meth_3'             => 'Peer Tutoring (Assisted by Top Students)',
    'form_req_student'       => 'Required Students to Participate',
    'btn_cancel'             => 'Cancel',
    'btn_set_program'        => 'Set Program',
    
    // JS Logic Keys
    'no_data'                => 'Data not found.',
    'no_detail'              => 'No detail data yet.',
    'lbl_detail'             => 'Detail',
    'lbl_unrated'            => 'Unrated',
    'lbl_critical'           => 'Critical',
    'lbl_warning'            => 'Warning',
    'lbl_safe'               => 'Safe',
    'rec_aman'               => 'The average grade for <b>{name}</b> is highly satisfactory ({avg}). Maintain the teaching method.',
    'rec_rawan'              => 'The average for <b>{name}</b> is on the borderline ({avg}). Needs teaching method review and group evaluation.',
    'rec_belum'              => '<b>{name}</b> does not have grade data from the subject teacher yet. Please remind the respective teacher.',
    'rec_kritis'             => 'Warning! The grade for <b>{name}</b> is very critical ({avg}). Immediately create a structured remedial program.',
    'rec_title'              => 'System AI Analysis',
    'btn_view_spread'        => 'View Student Grade Distribution',
    'btn_make_remedy'        => 'Create Remedial Program',
    'trend_safe_lbl'         => 'Safe Condition (Average: {avg})',
    'trend_warn_lbl'         => 'Needs Attention (Average: {avg})',
    'trend_no_safe'          => 'No subjects in the safe category yet.',
    'trend_no_warn'          => 'Alhamdulillah, no subjects in warning/critical category.',
    'remedi_prog_prefix'     => 'Intensive Program',
    'remedi_no_student'      => 'No students require a remedial program.',
    'remedi_final_score'     => 'Final Score',
    'remedi_succ_msg'        => 'Remedial Program successfully saved and will be scheduled automatically by the system!',
    'modal_student_title'    => 'Student Grade Distribution',
    'modal_student_sub'      => 'Subject: {name}',
];