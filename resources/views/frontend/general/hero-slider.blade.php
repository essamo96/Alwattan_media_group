<?php
// قراءة صور السلايدر تلقائياً من: public/assets/front/images/slider
// اي صورة جديدة توضع في المجلد تظهر في السلايدر دون تعديل الكود
$slider_path = public_path('assets/front/images/slider');
// ملاحظة: لا نستخدم GLOB_BRACE لانه غير مدعوم على بعض السيرفرات
$allowed_ext = ['jpg', 'jpeg', 'png', 'webp'];
$slider_files = [];
if (is_dir($slider_path)) {
    foreach ((array) scandir($slider_path) as $file) {
        if ($file === '.' || $file === '..') {
            continue;
        }
        $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
        if (in_array($ext, $allowed_ext, true)) {
            $slider_files[] = $file;
        }
    }
    natcasesort($slider_files);
}
$slider_urls = array_values(array_map(function ($file) {
    return asset('assets/front/images/slider/' . $file);
}, $slider_files));
?>
@if(count($slider_urls) > 0)
<div class="hero-slideshow" data-interval="6000" data-images='@json($slider_urls)'></div>
@endif
