<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Sliders;

/**
 * يعبّئ سلايدر الصفحة الرئيسية بنصوص الوطن ميديا جروب الست.
 * يعمل بشكل idempotent: يبحث بالعنوان قبل الإضافة فلا يكرر السلايدات عند إعادة التشغيل.
 */
class HomeSlidesSeeder extends Seeder
{
    public function run()
    {
        $slides = [
            [
                'name' => 'الوطن ميديا جروب',
                'name1' => 'نصنع المحتوى، ونبني الثقة',
                'name2' => 'إعلام مهني، تغطية شاملة، ورؤية تصنع الفرق.',
            ],
            [
                'name' => 'الحقيقة',
                'name1' => 'أولاً',
                'name2' => 'ننقل الخبر بدقة، ونقدم التحليل بموضوعية، ونلتزم بأعلى معايير المهنية.',
            ],
            [
                'name' => 'منصة إعلامية',
                'name1' => 'متكاملة',
                'name2' => 'أخبار، تقارير، إنتاج إعلامي، وتسويق رقمي... في مكان واحد.',
            ],
            [
                'name' => 'صوت فلسطين',
                'name1' => 'إلى العالم',
                'name2' => 'ننقل نبض الوطن، ونروي قصص النجاح، ونواكب الأحداث بمصداقية واحتراف.',
            ],
            [
                'name' => 'شريكك في',
                'name1' => 'النجاح الإعلامي',
                'name2' => 'حلول إعلامية وإبداعية متكاملة للمؤسسات والشركات والمنظمات.',
            ],
            [
                'name' => 'نصنع الأثر',
                'name1' => 'لا المحتوى فقط',
                'name2' => 'لأن الإعلام رسالة، نؤمن بأن الكلمة الصادقة تصنع التغيير.',
            ],
        ];

        // Placeholder images until real media photography is provided -
        // reuses the two unused files already sitting in uploads/sliders/.
        $fallbackImages = ['image_1671043828.jpg', 'image_1671107327.jpg'];

        foreach ($slides as $index => $slide) {
            $exists = Sliders::withTrashed()
                ->where('name', $slide['name'])
                ->where('language', 'ar')
                ->exists();

            if ($exists) {
                continue;
            }

            (new Sliders())->addSlider(
                $slide['name'],
                $slide['name1'],
                $slide['name2'],
                'ar',
                $fallbackImages[$index % count($fallbackImages)],
                '#',
                1,
                1
            );
        }

        $this->command->info('  سلايدات الوطن ميديا جروب: تمت التعبئة (أو موجودة مسبقاً).');
    }
}
