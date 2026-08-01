<?php

namespace Database\Seeders;

use App\Models\Menus;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Cache;

/**
 * ينشئ قائمة اب "المنصات الإعلامية" في الناف بار مع 4 قوائم فرعية تحتها.
 * آمن التكرار: لا يُنشئ نسخا مكررة اذا شُغّل اكثر من مرة.
 * الروابط غير المؤكدة تُترك "#" مؤقتا؛ يمكن تعديلها لاحقا من admin/menus.
 */
class MediaPlatformsMenuSeeder extends Seeder
{
    public function run()
    {
        $menu = new Menus();

        $parent = $menu->where('parent_id', 0)->where('name_ar', 'المنصات الإعلامية')->first();

        if (! $parent) {
            $parent = $menu->addMenu(0, 'المنصات الإعلامية', 'Media Platforms', '#', '', null, '_self', $menu->getNextSort(0), 1);
        }

        $children = [
            ['name_ar' => 'صحيفة الوطن', 'name_en' => 'Alwattan Newspaper', 'url' => 'https://alwattan.ps', 'target' => '_blank'],
            ['name_ar' => 'مركز الإعلام والدراسات MSC', 'name_en' => 'Media Studies Center (MSC)', 'url' => '#', 'target' => '_self'],
            ['name_ar' => 'يبوس للادارة والاستشارات', 'name_en' => 'Yabous Management & Consultancy', 'url' => '#', 'target' => '_self'],
            ['name_ar' => 'يبوس لحلول تكنولوجيا المعلومات', 'name_en' => 'Yabous IT Solutions', 'url' => '#', 'target' => '_self'],
        ];

        foreach ($children as $c) {
            $exists = $menu->where('parent_id', $parent->id)->where('name_ar', $c['name_ar'])->exists();
            if ($exists) {
                continue;
            }
            (new Menus())->addMenu(
                $parent->id,
                $c['name_ar'],
                $c['name_en'],
                $c['url'],
                '',
                null,
                $c['target'],
                $menu->getNextSort($parent->id),
                1
            );
        }

        Cache::forget('menus');

        $this->command->info('تم انشاء قائمة "المنصات الإعلامية" مع القوائم الفرعية الاربع (او التأكد من وجودها).');
    }
}
