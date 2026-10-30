<?php

return [
    'required' => 'حقل :attribute مطلوب.',
    'email' => 'أدخل بريدًا إلكترونيًا صالحًا في حقل :attribute.',
    'string' => 'يجب أن يكون حقل :attribute نصًا.',
    'integer' => 'يجب أن يكون حقل :attribute رقمًا صحيحًا.',
    'boolean' => 'قيمة حقل :attribute غير صالحة.',
    'confirmed' => 'تأكيد حقل :attribute غير مطابق.',
    'unique' => 'قيمة حقل :attribute مستخدمة بالفعل.',
    'url' => 'أدخل رابطًا صالحًا يبدأ بـ http أو https في حقل :attribute.',
    'regex' => 'صيغة حقل :attribute غير صالحة.',
    'min' => ['string' => 'يجب ألا يقل حقل :attribute عن :min أحرف.', 'numeric' => 'أقل قيمة لحقل :attribute هي :min.'],
    'max' => ['string' => 'يجب ألا يزيد حقل :attribute عن :max حرفًا.', 'numeric' => 'أكبر قيمة لحقل :attribute هي :max.'],
    'between' => ['numeric' => 'يجب أن تكون قيمة :attribute بين :min و:max.'],
    'attributes' => [
        'name'=>'الاسم', 'email'=>'البريد الإلكتروني', 'password'=>'كلمة المرور', 'subject'=>'الموضوع',
        'message'=>'الرسالة', 'title'=>'العنوان', 'tagline'=>'العبارة التعريفية', 'bio'=>'النبذة',
        'location'=>'الموقع', 'github_url'=>'رابط GitHub', 'linkedin_url'=>'رابط LinkedIn',
        'project_url'=>'رابط المشروع', 'description'=>'الوصف', 'category'=>'التصنيف', 'slug'=>'المعرّف',
        'level'=>'مستوى الإتقان', 'sort_order'=>'الترتيب', 'accent_color'=>'اللون', 'tech_stack'=>'التقنيات',
        'website'=>'الحقل المخفي', 'phone'=>'رقم الهاتف', 'instagram_url'=>'رابط إنستغرام',
    ],
];
