<?php

return [
    'page_title'             => 'تقدم درجات المواد',
    
    // Filter Card
    'filter_settings'        => 'عوامل التصفية وإعدادات العرض',
    'filter_subject'         => 'المادة الدراسية',
    'opt_all_subjects'       => 'جميع المواد',
    'filter_status'          => 'حالة الدرجة',
    'opt_all_status'         => 'جميع الحالات',
    'opt_safe_grade'         => 'درجة آمنة (≥75)',
    'opt_warn_grade'         => 'درجة تحذيرية (60-74)',
    'opt_crit_grade'         => 'درجة حرجة (<60)',
    'filter_sort'            => 'فرز حسب',
    'opt_sort_az'            => 'اسم المادة (أ-ي)',
    'opt_sort_high'          => 'أعلى متوسط',
    'opt_sort_low'           => 'أدنى متوسط',
    'filter_view'            => 'عرض البيانات',
    
    // Stats
    'stat_total_subject'     => 'إجمالي المواد',
    'stat_class_avg'         => 'متوسط الفصل',
    'stat_safe_subject'      => 'مواد آمنة',
    'stat_warn_subject'      => 'مواد حرجة/تحذيرية',
    
    // Chart
    'chart_title'            => 'مقارنة متوسط الفصل',
    'chart_subtitle'         => 'رسم بياني لمتوسط الدرجات لكل مادة',
    
    // Tabs
    'tab_all_subjects'       => 'جميع المواد',
    'tab_subject_detail'     => 'تفاصيل المادة',
    'tab_trend_analysis'     => 'تحليل الاتجاهات',
    
    // Table Headers
    'th_subject'             => 'المادة الدراسية',
    'th_average'             => 'المتوسط',
    'th_highest'             => 'الأعلى',
    'th_lowest'              => 'الأدنى',
    'th_trend'               => 'الاتجاه',
    'th_status'              => 'الحالة',
    'th_no'                  => 'رقم',
    'th_student_name'        => 'اسم الطالب',
    'th_grade'               => 'درجة التقرير',
    'th_action'              => 'إجراء',
    
    // Tab Detail
    'sel_sub_detail'         => 'اختر مادة لعرض التفاصيل:',
    'sel_sub_ph'             => 'اختر إحدى المواد أعلاه لعرض التحليل التفصيلي.',
    
    // Tab Tren
    'trend_safe'             => 'اتجاه آمن/إيجابي',
    'trend_warn'             => 'يحتاج إلى انتباه (تحذير/حرج)',
    
    // Modals
    'modal_student_data'     => 'بيانات الطالب',
    'modal_student_subtitle' => 'قائمة درجات الطلاب لكل مادة',
    'btn_close'              => 'إغلاق',
    'btn_export'             => 'تصدير البيانات',
    
    'modal_remedi_title'     => 'إنشاء برنامج علاجي',
    'modal_remedi_subtitle'  => 'برنامج توجيه وتعلم مكثف',
    'form_prog_name'         => 'اسم البرنامج العلاجي',
    'form_duration'          => 'المدة (أسابيع)',
    'form_frequency'         => 'التردد في الأسبوع',
    'form_freq_sel'          => 'اختر التردد',
    'form_freq_1'            => '1x في الأسبوع',
    'form_freq_2'            => '2x في الأسبوع',
    'form_freq_3'            => '3x في الأسبوع',
    'form_method'            => 'طريقة التعلم',
    'opt_meth_1'             => 'دراسة في مجموعات صغيرة',
    'opt_meth_2'             => 'دروس خصوصية فردية',
    'opt_meth_3'             => 'تدريس الأقران (بمساعدة الطلاب المتفوقين)',
    'form_req_student'       => 'الطلاب المطلوب مشاركتهم',
    'btn_cancel'             => 'إلغاء',
    'btn_set_program'        => 'تعيين البرنامج',
    
    // JS Logic Keys
    'no_data'                => 'لم يتم العثور على بيانات.',
    'no_detail'              => 'لا توجد بيانات تفصيلية بعد.',
    'lbl_detail'             => 'تفاصيل',
    'lbl_unrated'            => 'غير مقيّم',
    'lbl_critical'           => 'حرج',
    'lbl_warning'            => 'تحذير',
    'lbl_safe'               => 'آمن',
    'rec_aman'               => 'متوسط الدرجة لـ <b>{name}</b> مرضي للغاية ({avg}). حافظ على طريقة التدريس.',
    'rec_rawan'              => 'المتوسط لـ <b>{name}</b> على خط الحدود ({avg}). يحتاج إلى مراجعة طريقة التدريس وتقييم المجموعة.',
    'rec_belum'              => '<b>{name}</b> ليس لديه بيانات درجات من معلم المادة بعد. يرجى تذكير المعلم المعني.',
    'rec_kritis'             => 'تحذير! الدرجة لـ <b>{name}</b> حرجة للغاية ({avg}). قم بإنشاء برنامج علاجي منظم على الفور.',
    'rec_title'              => 'تحليل نظام الذكاء الاصطناعي',
    'btn_view_spread'        => 'عرض توزيع درجات الطلاب',
    'btn_make_remedy'        => 'إنشاء برنامج علاجي',
    'trend_safe_lbl'         => 'حالة آمنة (المتوسط: {avg})',
    'trend_warn_lbl'         => 'يحتاج إلى انتباه (المتوسط: {avg})',
    'trend_no_safe'          => 'لا توجد مواد في الفئة الآمنة بعد.',
    'trend_no_warn'          => 'الحمد لله، لا توجد مواد في فئة التحذير/الحرجة.',
    'remedi_prog_prefix'     => 'برنامج مكثف',
    'remedi_no_student'      => 'لا يوجد طلاب يحتاجون إلى برنامج علاجي.',
    'remedi_final_score'     => 'الدرجة النهائية',
    'remedi_succ_msg'        => 'تم حفظ البرنامج العلاجي بنجاح وستتم جدولته تلقائيًا بواسطة النظام!',
    'modal_student_title'    => 'توزيع درجات الطلاب',
    'modal_student_sub'      => 'المادة: {name}',
];