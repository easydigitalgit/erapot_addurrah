<?php

return [
    'breadcrumb'           => 'Academic Master',
    'page_title'           => 'Subject Teacher Mapping',
    'page_header'          => 'Subject Teacher Assignment Mapping',
    'page_desc'            => 'Assign teachers for each subject and class',
    
    // Buttons
    'btn_add_mapping'      => 'Add Mapping',
    'btn_bulk_mapping'     => 'Bulk Mapping',
    'btn_import_excel'     => 'Import Excel',
    
    // Alerts & Info
    'alert_unassigned_title' => 'Attention: 3 Classes Do Not Have Assigned Teachers',
    'alert_unassigned_desc'  => 'Math for VII-D, Arabic for VIII-C, and Science for IX-B have not been assigned a teacher.',
    'btn_assign_now'         => 'Assign Now',
    'alert_overload_title'   => 'Information: 1 Teacher Assigned Over 24 Hours/Week',
    'alert_overload_desc'    => 'Ustadz Ahmad Fauzi, S.Pd is assigned a total of 28 hours/week. Consider teaching load redistribution.',
    
    'impact_title'         => 'System Impact of Mapping',
    'impact_desc'          => 'Subject teacher mapping will automatically affect the following features:',
    'impact_schedule'      => 'Class Schedule',
    'impact_access'        => 'Teacher Access',
    'impact_grade'         => 'Grade Input',
    'impact_report'        => 'Student Report Cards',
    
    // Stats
    'stat_total_teacher'   => 'Total Registered Teachers',
    'stat_total_subject'   => 'Total Subjects',
    'stat_active_mapping'  => 'Total Active Mappings',
    'stat_empty_class'     => 'Empty Classes (0 Teachers)',
    
    // Filters
    'filter_year'          => 'Academic Year',
    'all_years'            => 'All Years',
    'filter_level'         => 'Level',
    'all_levels'           => 'All Levels',
    'level_class'          => 'Class',
    'filter_room'          => 'Room',
    'all_rooms'            => 'All Rooms',
    'filter_subject'       => 'Subject',
    'all_subjects'         => 'All Subjects',
    'filter_teacher'       => 'Teacher',
    'all_teachers'         => 'All Teachers',
    'filter_search'        => 'Search',
    'search_ph'            => 'Search name, NIK...',
    'show_active_only'     => 'Show only active mapping',
    
    // Table
    'th_teacher'           => 'Assigned Teacher',
    'th_subject'           => 'Subject',
    'th_level'             => 'Level',
    'th_room'              => 'Room',
    'th_hours'             => 'Hours/Week',
    'th_year'              => 'Academic Year',
    'th_status'            => 'Status',
    'th_action'            => 'Action',
    
    // Add Modal
    'add_modal_title'      => 'Add Subject Teacher Mapping',
    'add_modal_desc'       => 'Assign a teacher for 1 subject across multiple classes',
    'lbl_select_teacher'   => 'Select Teacher',
    'ph_select_teacher'    => '-- Select Assigned Teacher --',
    'lbl_select_subject'   => 'Subject',
    'ph_select_subject'    => '-- Select Subject --',
    'lbl_select_room'      => 'Room',
    'ph_click_room'        => 'Select room',
    'no_room_data'         => 'No room data available',
    'lbl_hours'            => 'Hours/Week',
    'lbl_year'             => 'Academic Year',
    'lbl_notes'            => 'Notes (Optional)',
    'btn_cancel'           => 'Cancel',
    'btn_save_mapping'     => 'Save Mapping',
    'btn_save_changes'     => 'Save Changes',
    
    // Bulk Modal
    'bulk_modal_title'     => 'Bulk Teacher Mapping',
    'bulk_modal_desc'      => '1 Teacher assigned to MANY Subjects in MANY Classes at once.',
    'ph_teacher_bulk'      => '-- Select 1 Teacher --',
    'lbl_multi_subj'       => 'Select Multiple Subjects',
    'ph_click_subj'        => 'Click to select subjects',
    'lbl_multi_room'       => 'Select Multiple Rooms',
    'lbl_avg_hours'        => 'Hours/Week (Flat Rate)',
    'btn_save_bulk'        => 'Save Bulk Mapping',
    
    // Import Modal
    'import_title'         => 'Import Mapping from Excel',
    'import_desc'          => 'Upload Excel file according to the database template',
    'step_1'               => '1. Download Excel Template (DB Format):',
    'dl_template'          => 'Download Template',
    'step_2'               => '2. Upload File (.xls or .xlsx):',
    'btn_upload'           => 'Upload & Import',
    
    // Drawer
    'drawer_title'         => 'Mapping Details',
    'active_badge'         => 'Active',
    'inactive_badge'       => 'Inactive',
    'drawer_level'         => 'Level',
    'drawer_room'          => 'Room',
    'drawer_year_hour'     => 'Academic Year / Hours',
    'btn_edit_mapping'     => 'Edit Mapping',
    'btn_deactivate'       => 'Deactivate',
    
    // Delete Modal
    'del_modal_title'      => 'Deactivate Mapping?',
    'del_modal_desc'       => 'Are you sure you want to deactivate/delete this mapping? The teacher will no longer be able to access this class.',
    'btn_yes_deactivate'   => 'Yes, Deactivate',
    
    // Javascript
    'js_loading'           => 'Processing...',
    'js_saving'            => 'Saving...',
    'js_analyzing'         => 'Analyzing...',
    'js_no_data'           => 'No matching mapping data found.',
    'js_status_active'     => 'Active',
    'js_status_inactive'   => 'Inactive',
    'js_teacher_not_found' => 'Teacher Not Found',
    'js_err_min_bulk'      => 'At least 1 Subject and 1 Room must be selected!',
    'js_err_server'        => 'A server error occurred.',
    'js_err_conn'          => 'Connection lost.',
    'js_err_fatal'         => 'A fatal server error occurred.',
    'js_fail_prefix'       => 'Failed: '
];