<?php

return [
    // --- TEKS VIEW (HTML) ---
    'page_title'          => 'Grading Rules & Weights',
    'page_subtitle'       => 'Manage components, weights, and report card grading rules',
    'breadcrumb_config'   => 'Academic Configuration',
    'bismillah'           => 'بِسْمِ اللَّهِ الرَّحْمَٰنِ الرَّحِيمِ',
    'bismillah_trans'     => 'In the name of Allah, the Entirely Merciful, the Especially Merciful',
    'btn_add_rule'        => 'Add Rule',
    'btn_reset'           => 'Reset to Default',
    'btn_history'         => 'History',
    
    // --- SUMMARY CARDS ---
    'total_components'    => 'Total Components',
    'active_categories'   => 'Active categories',
    'total_weight'        => 'Total Weight',
    'curriculum'          => 'Curriculum',
    'validation_status'   => 'Validation Status',
    'validated'           => 'Validated',
    'ready_to_use'        => 'Ready to use',
    
    // --- WARNING ALERT ---
    'warning_title'       => '⚠️ Unbalanced Total Weight',
    'warning_desc'        => 'Current total grading weight: ',
    'warning_desc_2'      => '. Please adjust the weights so the total reaches 100% before saving.',
    'btn_auto_balance'    => 'Auto Balance Weights',
    
    // --- WEIGHT CONFIGURATION ---
    'weight_structure'    => 'Grading Weight Structure',
    'academic'            => '📘 Academic',
    'academic_desc'       => 'Knowledge & skills assessment',
    'knowledge'           => 'Knowledge',
    'knowledge_desc'      => 'Exams, assignments, quizzes',
    'skills'              => 'Skills',
    'skills_desc'         => 'Practices, projects, portfolios',
    'pts'                 => 'Midterm Exam (PTS)',
    'pts_desc'            => 'Mid-semester examination',
    'pas'                 => 'Final Exam (PAS)',
    'pas_desc'            => 'End-of-semester examination',
    
    'character'           => '🌱 Character',
    'character_desc'      => 'Morals, discipline, responsibility',
    'morals'              => 'Morals',
    'morals_desc'         => 'Politeness, honesty',
    'discipline'          => 'Discipline',
    'discipline_desc'     => 'Attendance, punctuality',
    'responsibility'      => 'Responsibility',
    'responsibility_desc' => 'Task completion',
    
    'islamic'             => '🕌 Islamic Studies',
    'islamic_desc'        => 'Tahfidz, worship, Islamic morals',
    'tahfidz'             => 'Tahfidz',
    'tahfidz_desc'        => 'Quran memorization',
    'worship'             => 'Daily Worship',
    'worship_desc'        => 'Prayers, du\'a, recitation',
    'islamic_morals'      => 'Islamic Morals',
    'islamic_morals_desc' => 'Adab, akhlaqul karimah',
    
    // --- FORMULA PREVIEW ---
    'preview_formula'     => 'Formula Preview',
    'weight_distribution' => 'Grading Weight Distribution',
    'formula_calc'        => 'Calculation Formula',
    'final_grade'         => 'Final Grade',
    'example_calc'        => 'Calculation Example',
    
    // --- GRADING RULES TABLE ---
    'grading_rules'       => 'Grading Rules & Predicates',
    'th_range'            => 'Score Range',
    'th_predicate'        => 'Predicate',
    'th_desc'             => 'Description',
    'th_status'           => 'Status',
    'th_action'           => 'Action',
    'empty_rules'         => 'No grading rules yet.',
    'badge_active'        => 'Active',
    'badge_inactive'      => 'Inactive',
    'btn_delete'          => 'Delete',
    
    // --- IMPACT INFO ---
    'sync_impact'         => 'Synchronization & Change Impact',
    'impact_desc'         => 'Changes to grading rules will impact the following system components:',
    'impact_1_title'      => 'Report Card Calculation',
    'impact_1_desc'       => 'Student final grades will be recalculated',
    'impact_2_title'      => 'Academic Insights',
    'impact_2_desc'       => 'Statistics dashboard will be updated',
    'impact_3_title'      => 'Class Rankings',
    'impact_3_desc'       => 'Student rankings will be re-sorted',
    'impact_4_title'      => 'Homeroom Teacher Reports',
    'impact_4_desc'       => 'Report data will be adjusted',
    
    // --- SECURITY WARNING ---
    'sec_policy'          => '🔒 Rule Change Policy',
    'sec_1'               => 'Changes are only allowed before the grading input period begins',
    'sec_2'               => 'Once report cards are locked, rules cannot be changed for the current semester',
    'sec_3'               => 'All changes will be recorded in the system audit history',
    'sec_4'               => 'Coordinate with the curriculum team and homeroom teachers before changing rules',
    
    // --- BUTTONS ---
    'btn_preview'         => 'Preview Changes',
    'btn_save_changes'    => 'Save Changes',
    
    // --- MODALS ---
    'modal_add_title'     => 'Add Grading Rule',
    'modal_add_desc'      => 'Define a new grade predicate rule',
    'lbl_predicate'       => 'Predicate',
    'ph_predicate'        => 'Example: A+, B-, E',
    'hint_predicate'      => 'Enter predicate letter (A-E) with optional + or -',
    'lbl_desc_pred'       => 'Predicate Description',
    'ph_desc_pred'        => 'Example: Excellent, Good, Sufficient',
    'lbl_min_val'         => 'Minimum Score',
    'lbl_max_val'         => 'Maximum Score',
    'lbl_desc_comp'       => 'Competency Achievement Description',
    'ph_desc_comp'        => 'Describe the level of competency achievement...',
    'lbl_badge_color'     => 'Badge Color',
    'lbl_optional'        => '(Optional)',
    'rule_status'         => 'Rule Status',
    'rule_status_desc'    => 'Activate this rule after saving',
    'tips_title'          => 'Grading Rule Tips:',
    'tip_1'               => 'Ensure score ranges do not overlap',
    'tip_2'               => 'Use consistent predicates',
    'btn_cancel'          => 'Cancel',
    'btn_save_rule'       => 'Save Rule',
    
    'modal_hist_title'    => 'Change History',
    'loading_data'        => 'Loading data...',
    'btn_close'           => 'Close',

    // --- TEKS JAVASCRIPT ---
    'js_valid'            => '✔️ Valid',
    'js_unbalanced'       => '⚠️ Unbalanced',
    'js_saving'           => 'Saving...',
    'js_err_range'        => 'Minimum score cannot be greater than maximum score!',
    'js_succ_save'        => 'Success! Grading weights have been saved.',
    'js_fail_prefix'      => 'Failed: ',
    'js_err_server'       => 'A server error occurred.',
    'js_conf_reset'       => 'Are you sure you want to reset all weights to default? Unsaved changes will be lost.',
    'js_succ_reset'       => 'Successfully reset settings!',
    'js_fail_reset'       => 'Failed to reset: ',
    'js_empty_hist'       => 'No change history yet.',
    'js_err_load_hist'    => 'Failed to load history data.',
    'js_err_auto_bal'     => 'Fill in at least one weight before performing Auto Balance!',
    'js_succ_auto_bal'    => 'Weights successfully auto-balanced to 100%'
];