<?php

return [
    'breadcrumb'        => 'Academic Configuration',
    'page_title'        => 'Curriculum',
    'page_desc'         => 'Manage academic curriculum and its implementation per level',
    
    // Buttons Top
    'btn_add'           => 'Add Curriculum',
    'btn_apply'         => 'Apply to Level',
    'btn_import'        => 'Import Structure',
    
    // Primary Card
    'active_curr_title' => 'Active Curriculum This Year',
    'using_level'       => 'Levels Using',
    'level'             => 'Level',
    'total_subjects'    => 'Total Subjects',
    'subject'           => 'Subject',
    'general_islamic'   => '12 General + 4 Islamic',
    'impl_status'       => 'Implementation Status',
    'status_active'     => 'ACTIVE',
    'running_normal'    => 'Running Normally',
    'important_note'    => 'Important Note',
    'important_desc'    => 'This curriculum is implemented for the 2024/2025 Academic Year. Changes to the curriculum will affect the report card structure, schedules, teacher mapping, and grading system.',
    
    // Action Cards Sidebar
    'btn_view_struct'   => 'View Full Structure',
    'btn_sys_impact'    => 'System Impact',
    'btn_doc'           => 'Documentation',
    
    // Table Section
    'list_title'        => 'Available Curriculum List',
    'list_desc'         => 'Manage all applicable curriculum types',
    'curr_count'        => 'Curriculums',
    'th_name'           => 'Curriculum Name',
    'th_type'           => 'Type',
    'th_year'           => 'Effective Year',
    'th_status'         => 'Status',
    'th_used_in'        => 'Used In',
    'th_action'         => 'Action',
    'status_inactive'   => 'Inactive',
    'no_data'           => 'No curriculum data in the database yet.',
    'tt_structure'      => 'View Structure',
    'tt_edit'           => 'Edit',
    'tt_activate'       => 'Activate',
    'tt_archive'        => 'Archive',
    
    // Modals Global
    'btn_cancel'        => 'Cancel',
    
    // Edit Modal
    'edit_title'        => 'Edit Curriculum',
    'edit_desc'         => 'Modify existing curriculum information',
    'lbl_name'          => 'Curriculum Name',
    'ph_name'           => 'Example: Revised 2013 Curriculum',
    'lbl_type'          => 'Curriculum Type',
    'ph_type'           => 'Select curriculum type',
    'type_k13'          => '2013 Curriculum (K13)',
    'active_curr_name'  => 'Kurikulum Merdeka',
    'type_internal'     => 'School Internal Curriculum',
    'lbl_year_start'    => 'Start Year',
    'lbl_year_end'      => 'End Year (Optional)',
    'ph_year_end'       => 'Currently Active',
    'lbl_desc'          => 'Description (Optional)',
    'ph_desc'           => 'Add description...',
    'warn_title'        => 'Attention',
    'warn_edit_desc'    => 'Changes to an active curriculum may affect the ongoing academic structure.',
    'btn_save_changes'  => 'Save Changes',
    
    // Add Modal
    'add_title'         => 'Add New Curriculum',
    'add_desc'          => 'Register a new curriculum into the system',
    'warn_add_desc'     => 'New curriculum will be created in <strong>INACTIVE</strong> status.',
    'btn_save_add'      => 'Add Curriculum',
    
    // Apply Modal
    'apply_title'       => 'Apply Curriculum to Level',
    'apply_desc'        => 'Determine the curriculum to be used per level',
    'lbl_select_curr'   => 'Select Curriculum',
    'lbl_select_level'  => 'Select Level',
    'level_7'           => 'Grade VII',
    'level_8'           => 'Grade VIII',
    'level_9'           => 'Grade IX',
    'lbl_apply_year'    => 'Academic Year',
    'ph_apply_year'     => 'Select academic year',
    'apply_options'     => 'Implementation Options',
    'opt_default'       => 'Use Default Structure',
    'opt_def_desc'      => 'Standard subjects and time allocation',
    'opt_custom'        => 'Customize Subjects',
    'opt_cust_desc'     => 'Configure your own subjects to be used',
    'impact_title'      => 'CHANGE IMPACT',
    'impact_1'          => '• Class schedules will be reset',
    'impact_2'          => '• Teacher mapping needs to be re-adjusted',
    'apply_agree'       => '<strong>I understand the impact of applying the curriculum</strong> and am ready to proceed.',
    'btn_apply_now'     => 'Apply Now',
    
    // Structure Modal
    'struct_title'      => 'Curriculum Structure -',
    'struct_desc'       => 'Subject details and time allocation per level',
    'struct_level_7'    => 'Grade VII',
    'struct_level_desc' => '16 Subjects • Total 48 Hours/Week',
    'struct_detail_msg' => 'Subject details for grade VII...',
    'btn_close_panel'   => 'Close Panel',
    
    // Impact Modal
    'sys_impact_title'  => 'System Impact',
    'sys_impact_desc'   => 'Details of curriculum change effects',
    'warn_important'    => 'IMPORTANT WARNING',
    'warn_imp_desc'     => 'Curriculum changes must be done very carefully as they can reset class structures and schedules.',
    'btn_understand'    => 'I Understand',
    
    // Import Modal
    'import_title'      => 'Import Excel Curriculum',
    'import_desc'       => 'Upload Excel file according to template',
    'step_1'            => '1. Download Excel Template:',
    'dl_template'       => 'Download Template',
    'step_2'            => '2. Upload File (.xls or .xlsx):',
    'btn_upload'        => 'Upload & Import',
    
    // Javascript
    'js_loading'        => 'Processing...',
    'js_err_data_not_found' => 'Curriculum data not found.',
    'js_succ_edit'      => 'Curriculum successfully updated!',
    'js_succ_add'       => 'New curriculum successfully added!',
    'js_warn_check'     => 'Please check the confirmation box first',
    'js_succ_apply'     => 'Curriculum successfully applied!',
    'js_succ_activate'  => 'Curriculum successfully activated!',
    'js_succ_archive'   => 'Curriculum successfully archived!',
    'js_notification'   => 'Notification',
    'js_err_fatal'      => 'A fatal error occurred on the server.',
    'js_err_conn'       => 'Connection to the server was lost.'
];