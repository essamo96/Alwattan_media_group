/**
 * @license Copyright (c) 2003-2018, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see https://ckeditor.com/legal/ckeditor-oss-license
 */

CKEDITOR.editorConfig = function (config) {
    // Define changes to default configuration here.
    // For complete reference see:
    // http://docs.ckeditor.com/#!/api/CKEDITOR.config

    // كل مجموعات الشريط المتاحة فعليا في هذا البناء من CKEditor (تحقق عبر
    // فحص كل CKEDITOR.plugins.add(...) داخل ckeditor.js)، لإظهار كل ميزة
    // مُتاحة بدل الاقتصار على مجموعة مختصرة كما كانت سابقا.
    config.toolbarGroups = [
        {name: 'document', groups: ['mode', 'document', 'doctools']},
        {name: 'clipboard', groups: ['clipboard', 'undo']},
        {name: 'editing', groups: ['find', 'selection', 'spellchecker']},
        {name: 'forms'},
        '/',
        {name: 'basicstyles', groups: ['basicstyles', 'cleanup']},
        {name: 'paragraph', groups: ['list', 'indent', 'blocks', 'align', 'bidi']},
        {name: 'links'},
        {name: 'insert'},
        '/',
        {name: 'styles'},
        {name: 'colors'},
        {name: 'tools'},
        {name: 'others'},
        {name: 'about'}
    ];

    // لا نحذف اي زر: كل الازرار التي توفرها المكوّنات المُحمَّلة فعليا
    // تظهر في الشريط (Bold/Italic/Underline/Strike، القوائم، الجداول،
    // الروابط، الصور، الرموز الخاصة، الخط الفاصل، تكبير المحرر، عرض
    // المصدر HTML، فحص الاملاء SCAYT/WSC، اتجاه النص LTR/RTL، ...).
    config.removeButtons = '';

    // هذا البناء من CKEditor لا يشحن مكوّن bidi الرسمي رغم ان مجموعة
    // 'bidi' معرّفة اصلا في toolbarGroups اعلاه، فتظهر المجموعة فارغة بلا
    // ازرار. plugins/bidi/plugin.js اعلاه يوفر بديلا خفيفا لتبديل اتجاه
    // الفقرة الحالية (LTR/RTL) لدعم النصوص العربية والانجليزية المختلطة.
    config.extraPlugins = 'bidi';

    // Set the most common block elements.
    config.format_tags = 'p;h1;h2;h3;h4;h5;h6;pre;address';

    // اظهار كل تبويبات نوافذ الحوار (بما فيها "متقدم" للصور والروابط)
    // بدل اخفائها كما كان سابقا، لإتاحة كل خيارات الصورة/الرابط.
    config.removeDialogTabs = '';
    config.allowedContent = true;
};
