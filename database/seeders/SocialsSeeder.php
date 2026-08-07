<?php

namespace Database\Seeders;

use App\Models\Socials;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Cache;

class SocialsSeeder extends Seeder
{
    /**
     * منصات التواصل الافتراضية — تُدار روابطها وحالتها من لوحة التحكم.
     * قيمة icon = اسم أيقونة Font Awesome 4 بدون بادئة fa-
     */
    public function run()
    {
        $platforms = [
            ['name' => 'فيسبوك', 'link' => 'https://www.facebook.com/', 'icon' => 'facebook', 'status' => 1],
            ['name' => 'تويتر', 'link' => 'https://twitter.com/', 'icon' => 'twitter', 'status' => 1],
            ['name' => 'انستغرام', 'link' => 'https://www.instagram.com/', 'icon' => 'instagram', 'status' => 1],
            ['name' => 'يوتيوب', 'link' => 'https://www.youtube.com/', 'icon' => 'youtube', 'status' => 1],
            ['name' => 'لينكدإن', 'link' => 'https://www.linkedin.com/', 'icon' => 'linkedin', 'status' => 1],
            ['name' => 'واتساب', 'link' => 'https://wa.me/', 'icon' => 'whatsapp', 'status' => 1],
        ];

        foreach ($platforms as $row) {
            $exists = Socials::where('name', $row['name'])->orWhere('icon', $row['icon'])->first();
            if ($exists) {
                continue;
            }
            (new Socials())->addSocial($row['name'], $row['link'], $row['icon'], $row['status']);
        }

        Cache::forget('social');
        Cache::forever('social', (new Socials())->getAllSocialActive());
    }
}
