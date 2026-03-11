<?php
return [
    'page_title'          => 'Tahfidz Report Grades - Digital Report',
    'breadcrumb_tahfidz'  => 'Tahfidz',
    'breadcrumb_rapor'    => 'Report Grading',
    
    // Header & Info
    'title_input'         => 'Report Finalization',
    'subtitle_input'      => 'End of semester evaluation assisted by',
    'ai_magic'            => 'AI Magic Assistant',
    
    // Filter Area
    'select_class'        => 'Select Class / Halaqah',
    'ph_select_class'     => '-- Tap to select class --',
    'select_semester'     => 'Grading Semester',
    'sem_ganjil'          => 'Odd Semester (1)',
    'sem_genap'           => 'Even Semester (2)',
    'btn_open_sheet'      => 'Open Sheet',
    
    // Empty State
    'empty_area_title'    => 'Empty Grading Area',
    'empty_area_desc'     => 'Select a class and semester above, then click "Open Sheet" to load the student grading form.',
    
    // Table Header
    'sheet_title'         => 'Grading Sheet:',
    'progress_title'      => 'Grading Progress',
    'ready_to_print'      => 'Ready to Print',
    'status_waiting'      => 'Waiting...',
    'status_done'         => '✅ Done',
    'status_not_yet'      => '⏳ Not Yet',
    'btn_magic_autofill'  => 'Magic Auto-Fill',
    
    // Table Columns
    'th_no'               => 'No',
    'th_profile_context'  => 'Profile & Achievement Context',
    'th_predicate'        => 'Grade Predicate',
    'th_narration'        => 'Report Narration / Notes',
    
    // Warning & Submit
    'warning_narration'   => 'Ensure all',
    'warning_narration_b' => 'narration columns are filled',
    'warning_narration_c' => '. The system will not save data if the narration is empty.',
    'btn_save_report'     => 'Save Semester Grades',
    
    // Guideline / SOP
    'guide_title'         => 'Report Narration Guidelines',
    'guide_badge'         => 'Teacher Tips',
    'guide_desc'          => 'Ensure the sentences provided in the report are constructive, objective, and informative for parents.',
    'guide_1_title'       => 'Use Positive Language',
    'guide_1_desc'        => 'Start the narration with an appreciative sentence (e.g., "Alhamdulillah, the student...") before giving suggestions for improvement.',
    'guide_2_title'       => 'Mention Specific Achievements',
    'guide_2_desc'        => 'Clearly state the student\'s latest memorization limit (surah/juz) so parents know their exact progress.',
    'guide_3_title'       => 'Provide Concrete Solutions',
    'guide_3_desc'        => 'If memorization is lacking, provide clear solutions. (e.g., "Please guide muroja\'ah after maghrib.")',
    'guide_4_title'       => 'Use Magic Auto-Fill',
    'guide_4_desc'        => 'Click the Magic Auto-Fill button above the table. The AI system will automatically formulate sentences based on student deposits!',
    
    // Predicate Legend
    'scale_title'         => 'Report Predicate Scale',
    'scale_a_title'       => 'Excellent',
    'scale_a_badge'       => 'Mutqin',
    'scale_b_title'       => 'Good',
    'scale_b_badge'       => 'Fluent',
    'scale_c_title'       => 'Fair',
    'scale_c_badge'       => 'Stuttering',
    'scale_d_title'       => 'Poor',
    'scale_d_badge'       => 'Evaluation',
    
    // Javascript Messages
    'js_alert_title_hi'   => 'Hello Teacher!',
    'js_alert_desc_hi'    => 'Please tap to select a class first.',
    'js_loading_sheet'    => 'Preparing Report Sheet...',
    'js_no_student'       => 'No students in this class yet.',
    'js_achievement'      => 'Achievement:',
    'js_not_deposited'    => 'Not Deposited',
    'js_times_deposit'    => 'Deposits',
    'js_ph_narration'     => 'Type report narration here...',
    'js_err_fetch'        => '❌ Failed to load data. Check your internet connection.',
    
    // Predicates Text
    'pred_a'              => '🌟 Excellent (A)',
    'pred_b'              => '✨ Good (B)',
    'pred_c'              => '⚠️ Fair (C)',
    'pred_d'              => '🚨 Poor (D)',
    
    // Auto Fill Text (EN)
    'af_achievement'      => ' The student\'s latest memorization reached Surah',
    'af_active'           => ' The student has been very diligent with a total of',
    'af_active_end'       => ' deposits.',
    'af_inactive'         => ' Unfortunately, the student has not submitted any memorization this semester.',
    'af_a_text'           => 'Alhamdulillah, the student has very strong memorization and perfect tajweed.',
    'af_a_end'            => ' Keep up this outstanding achievement.',
    'af_b_text'           => 'The student\'s memorization flows well and fluently.',
    'af_b_end'            => ' Continue to increase the intensity of muroja\'ah at home so the memorization becomes stronger.',
    'af_c_text'           => 'The student\'s memorization is fair, but still frequently stutters.',
    'af_c_end'            => ' Please spend more time repeating the memorization (muroja\'ah) at home with parental guidance.',
    'af_d_text'           => 'The student\'s memorization needs intensive guidance and attention.',
    'af_d_end'            => ' We request parents\' cooperation to monitor the student\'s ziyadah and muroja\'ah schedule more closely at home.',
    
    'js_toast_af_title'   => '🪄 Voila!',
    'js_toast_af_desc'    => 'report narrations successfully auto-generated!',
    'js_af_full_title'    => 'Already Full',
    'js_af_full_desc'     => 'All narration columns are filled. Magic Auto-Fill only fills empty columns.',
    
    'js_saving'           => 'Saving...',
    'js_saving_title'     => 'Saving Grades...',
    'js_saving_desc'      => 'Recording all grades and narrations to the central database.',
    'js_success_title'    => 'Alhamdulillah!',
    'js_success_default'  => 'Memorization report grades finalized successfully.',
    'js_warning_title'    => 'Partially Saved',
    'js_error_title'      => 'Failed',
    'js_server_error'     => 'Network Error',
    'js_server_error_desc'=> 'A problem occurred connecting to the server.',
];