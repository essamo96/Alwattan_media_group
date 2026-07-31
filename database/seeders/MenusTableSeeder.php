<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Menus;

/**
 * سيدر قوائم الناف بار في الموقع الخارجي.
 * تم الحفاظ على نفس المسارات والاسماء الحالية مع اضافة ايقونة لكل قائمة،
 * بالاضافة الى قائمة جديدة "جريدة الوطن".
 */
class MenusTableSeeder extends Seeder {

    public function run() {
        $menus = [
            [
                'name_ar' => 'الرئيسية',
                'name_en' => 'Home',
                'url' => '#section-hero',
                'icon' => 'fa fa-home',
                'image' => null,
                'target' => '_self',
                'sort' => 1,
            ],
            [
                'name_ar' => 'من نحن',
                'name_en' => 'About Us',
                'url' => '#section-about',
                'icon' => 'fa fa-info-circle',
                'image' => null,
                'target' => '_self',
                'sort' => 2,
            ],
            [
                'name_ar' => 'خدماتنا',
                'name_en' => 'Our Services',
                'url' => '#section-services',
                'icon' => 'fa fa-cogs',
                'image' => null,
                'target' => '_self',
                'sort' => 3,
            ],
            [
                'name_ar' => 'مقالات',
                'name_en' => 'Blog',
                'url' => '#section-schedule',
                'icon' => 'fa fa-pencil-square-o',
                'image' => null,
                'target' => '_self',
                'sort' => 4,
            ],
            [
                'name_ar' => 'شركاؤنا',
                'name_en' => 'Partners',
                'url' => '#section-partners',
                'icon' => 'fa fa-handshake-o',
                'image' => null,
                'target' => '_self',
                'sort' => 5,
            ],
            [
                'name_ar' => 'اتصل بنا',
                'name_en' => 'Contact Us',
                'url' => '#section-contact',
                'icon' => 'fa fa-envelope',
                'image' => null,
                'target' => '_self',
                'sort' => 6,
            ],
            [
                'name_ar' => 'جريدة الوطن',
                'name_en' => 'Al Wattan Newspaper',
                'url' => 'https://alwattan.ps',
                'icon' => 'fa fa-newspaper-o',
                'image' => 'wattan-newspaper.png',
                'target' => '_blank',
                'sort' => 7,
            ],
        ];

        foreach ($menus as $menu) {
            $exists = Menus::withTrashed()->where('name_ar', $menu['name_ar'])->first();
            if ($exists) {
                $exists->fill($menu);
                $exists->deleted_at = null;
                $exists->save();
                continue;
            }
            $menu['status'] = 1;
            $menu['type'] = 0;
            Menus::create($menu);
        }
    }

}
