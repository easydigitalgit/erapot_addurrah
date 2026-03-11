<?php

return [
    // --- HTML View ---
    'breadcrumb'        => 'التقييم',
    'page_title'        => 'إدخال درجات الطلاب',
    'page_header'       => 'إدخال الدرجات الأكاديمية',
    'page_desc'         => 'إدارة الدرجات اليومية للطلاب، الواجبات، والاختبارات.',
    'btn_export'        => 'تصدير إكسل',
    'btn_save'          => 'حفظ الدرجات',
    
    // Stats Section
    'stat_avg'          => 'المتوسط',
    'stat_pass'         => 'ناجح',
    'stat_fail'         => 'راسب (Remedial)',
    'stat_total'        => 'إجمالي الطلاب',
    
    // Filters
    'filter_class'      => 'اختر الفصل',
    'select_class'      => '-- اختر الفصل --',
    'class_lbl'         => 'الفصل',
    'no_class_data'     => 'لا توجد بيانات للفصل بعد',
    'filter_subject'    => 'المادة الدراسية',
    'select_subject'    => '-- اختر المادة --',
    'no_subj_data'      => 'لا توجد بيانات للمادة بعد',
    'filter_kkm'        => 'الحد الأدنى للنجاح',
    'kkm_title'         => 'معايير الحد الأدنى للنجاح',
    'btn_show'          => 'عرض',
    
    // Progress
    'class_progress'    => 'تقدم إدخال درجات الفصل',
    
    // Table
    'th_no'             => 'الرقم',
    'th_student_name'   => 'اسم الطالب',
    'th_grade'          => 'الدرجة (0-100)',
    'th_predicate'      => 'التقدير',
    'th_status'         => 'الحالة',
    'th_notes'          => 'ملاحظات التقييم',
    
    // Empty State
    'empty_title'       => 'جاهز لإدخال الدرجات؟',
    'empty_desc'        => 'يرجى اختيار <strong class="text-gray-700 dark:text-slate-300">الفصل</strong> و<strong class="text-gray-700 dark:text-slate-300">المادة الدراسية</strong> في الفلتر أعلاه للبدء بإدخال درجات الطلاب.',
    
    // --- Javascript ---
    'js_status_pass'    => 'ناجح',
    'js_status_fail'    => 'راسب',
    'js_swal_warn_title'=> 'اختر البيانات أولاً',
    'js_swal_warn_text' => 'يرجى اختيار الفصل والمادة الدراسية قبل عرض البيانات.',
    'js_swal_btn_ok'    => 'حسناً، فهمت',
    'js_loading_fetch'  => 'جاري جلب بيانات الطلاب...',
    'js_ph_grade'       => '-',
    'js_ph_notes'       => 'اكتب ملاحظات التقدير/التقييم...',
    'js_no_students'    => 'لم يتم العثور على طلاب في هذا الفصل.',
    'js_swal_err_title' => 'فشل التحميل',
    'js_swal_err_text'  => 'حدث خطأ أثناء جلب البيانات. حاول تحديث الصفحة.',
    'js_err_load_table' => 'فشل تحميل البيانات.',
    
    'js_swal_oops'      => 'عذراً...',
    'js_swal_sel_save'  => 'يرجى اختيار بيانات الفصل والمادة أولاً!',
    'js_swal_no_grade'  => 'لا توجد درجات بعد',
    'js_swal_fill_one'  => 'يرجى إدخال درجة طالب واحد على الأقل قبل الحفظ.',
    'js_saving'         => 'جاري الحفظ...',
    
    'js_swal_success'   => 'تم بنجاح!',
    'js_swal_fail_save' => 'فشل الحفظ',
    'js_swal_sys_err'   => 'حدث خطأ',
    'js_swal_err_conn'  => 'تحقق من اتصال الإنترنت أو اتصل بالمسؤول.',
    
    'js_swal_sel_exp'   => 'اختر الفصل والمادة قبل التصدير!',
    'js_swal_prep_data' => 'جاري تجهيز البيانات...',
    'js_swal_prep_desc' => 'يرجى الانتظار قليلاً، يتم إنشاء ملف الإكسل.'
];