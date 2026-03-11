<?php

return [
    'breadcrumb'           => 'النظام الأكاديمي الأساسي',
    'page_title'           => 'تعيين معلمي المواد',
    'page_header'          => 'خريطة تعيين معلمي المواد الدراسية',
    'page_desc'            => 'تحديد المعلم المسؤول عن كل مادة دراسية وفصل',
    
    // Buttons
    'btn_add_mapping'      => 'إضافة تعيين',
    'btn_bulk_mapping'     => 'تعيين جماعي',
    'btn_import_excel'     => 'استيراد إكسل',
    
    // Alerts & Info
    'alert_unassigned_title' => 'تنبيه: 3 فصول ليس لها معلمين معينين',
    'alert_unassigned_desc'  => 'الرياضيات لـ VII-D، اللغة العربية لـ VIII-C، والعلوم لـ IX-B لم يتم تعيين معلم لها بعد.',
    'btn_assign_now'         => 'تعيين الآن',
    'alert_overload_title'   => 'معلومات: معلم واحد يتجاوز 24 حصة أسبوعياً',
    'alert_overload_desc'    => 'الأستاذ أحمد فوزي، المجموع 28 حصة أسبوعياً. يرجى النظر في إعادة توزيع عبء التدريس.',
    
    'impact_title'         => 'تأثير التعيين على النظام',
    'impact_desc'          => 'سيؤثر تعيين معلم المادة تلقائياً على الميزات التالية:',
    'impact_schedule'      => 'الجدول الدراسي',
    'impact_access'        => 'وصول المعلم',
    'impact_grade'         => 'إدخال الدرجات',
    'impact_report'        => 'شهادات الطلاب',
    
    // Stats
    'stat_total_teacher'   => 'إجمالي المعلمين المسجلين',
    'stat_total_subject'   => 'إجمالي المواد الدراسية',
    'stat_active_mapping'  => 'إجمالي التعيينات النشطة',
    'stat_empty_class'     => 'فصول فارغة (0 معلمين)',
    
    // Filters
    'filter_year'          => 'العام الدراسي',
    'all_years'            => 'جميع الأعوام',
    'filter_level'         => 'المستوى',
    'all_levels'           => 'جميع المستويات',
    'level_class'          => 'الصف',
    'filter_room'          => 'الفصل',
    'all_rooms'            => 'جميع الفصول',
    'filter_subject'       => 'المادة الدراسية',
    'all_subjects'         => 'جميع المواد',
    'filter_teacher'       => 'المعلم',
    'all_teachers'         => 'جميع المعلمين',
    'filter_search'        => 'بحث',
    'search_ph'            => 'بحث بالاسم، رقم الهوية...',
    'show_active_only'     => 'إظهار التعيينات النشطة فقط',
    
    // Table
    'th_teacher'           => 'المعلم المسؤول',
    'th_subject'           => 'المادة الدراسية',
    'th_level'             => 'المستوى',
    'th_room'              => 'الفصل',
    'th_hours'             => 'حصة/أسبوع',
    'th_year'              => 'العام الدراسي',
    'th_status'            => 'الحالة',
    'th_action'            => 'إجراء',
    
    // Add Modal
    'add_modal_title'      => 'إضافة تعيين معلم مادة',
    'add_modal_desc'       => 'تحديد معلم لمادة واحدة وعدة فصول',
    'lbl_select_teacher'   => 'اختر المعلم',
    'ph_select_teacher'    => '-- اختر المعلم المسؤول --',
    'lbl_select_subject'   => 'المادة الدراسية',
    'ph_select_subject'    => '-- اختر المادة الدراسية --',
    'lbl_select_room'      => 'الفصل',
    'ph_click_room'        => 'اختر الفصل',
    'no_room_data'         => 'لا توجد بيانات للفصول',
    'lbl_hours'            => 'حصة/أسبوع',
    'lbl_year'             => 'العام الدراسي',
    'lbl_notes'            => 'ملاحظات (اختياري)',
    'btn_cancel'           => 'إلغاء',
    'btn_save_mapping'     => 'حفظ التعيين',
    'btn_save_changes'     => 'حفظ التغييرات',
    
    // Bulk Modal
    'bulk_modal_title'     => 'تعيين معلمين جماعي',
    'bulk_modal_desc'      => 'معلم واحد يدرس مواد متعددة في فصول متعددة دفعة واحدة.',
    'ph_teacher_bulk'      => '-- اختر معلم واحد --',
    'lbl_multi_subj'       => 'اختر عدة مواد دراسية',
    'ph_click_subj'        => 'انقر لاختيار المواد',
    'lbl_multi_room'       => 'اختر عدة فصول',
    'lbl_avg_hours'        => 'حصة/أسبوع (معدل ثابت)',
    'btn_save_bulk'        => 'حفظ التعيين الجماعي',
    
    // Import Modal
    'import_title'         => 'استيراد التعيينات من إكسل',
    'import_desc'          => 'رفع ملف إكسل وفقاً لنموذج قاعدة البيانات',
    'step_1'               => '1. تحميل نموذج إكسل (تنسيق DB):',
    'dl_template'          => 'تحميل النموذج',
    'step_2'               => '2. رفع الملف (.xls أو .xlsx):',
    'btn_upload'           => 'رفع واستيراد',
    
    // Drawer
    'drawer_title'         => 'تفاصيل التعيين',
    'active_badge'         => 'نشط',
    'inactive_badge'       => 'غير نشط',
    'drawer_level'         => 'المستوى',
    'drawer_room'          => 'الفصل',
    'drawer_year_hour'     => 'العام الدراسي / الحصص',
    'btn_edit_mapping'     => 'تعديل التعيين',
    'btn_deactivate'       => 'تعطيل',
    
    // Delete Modal
    'del_modal_title'      => 'تعطيل التعيين؟',
    'del_modal_desc'       => 'هل أنت متأكد من تعطيل/حذف هذا التعيين؟ لن يتمكن المعلم من الوصول إلى هذا الفصل بعد الآن.',
    'btn_yes_deactivate'   => 'نعم، تعطيل',
    
    // Javascript
    'js_loading'           => 'جاري المعالجة...',
    'js_saving'            => 'جاري الحفظ...',
    'js_analyzing'         => 'جاري التحليل...',
    'js_no_data'           => 'لا توجد بيانات تعيين مطابقة.',
    'js_status_active'     => 'نشط',
    'js_status_inactive'   => 'غير نشط',
    'js_teacher_not_found' => 'المعلم غير موجود',
    'js_err_min_bulk'      => 'يجب اختيار مادة واحدة وفصل واحد على الأقل!',
    'js_err_server'        => 'حدث خطأ في الخادم.',
    'js_err_conn'          => 'انقطع الاتصال.',
    'js_err_fatal'         => 'حدث خطأ فادح في الخادم.',
    'js_fail_prefix'       => 'فشل: '
];