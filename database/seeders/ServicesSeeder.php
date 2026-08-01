<?php

namespace Database\Seeders;

use App\Models\Services;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Seeder;

class ServicesSeeder extends Seeder
{
    /**
     * Replace the placeholder services with Alwattan Media Group's real
     * service catalogue.
     *
     * @return void
     */
    public function run()
    {
        // remove old/placeholder services (and their translations) completely
        Services::withTrashed()->get()->each(function ($service) {
            $service->translations()->delete();
            $service->forceDelete();
        });

        $services = [
            [
                'image' => 'fa-newspaper-o',
                'ar' => [
                    'title' => 'الخدمات الصحفية والإخبارية',
                    'details' => '<ul class="text-start"><li>التغطية الإخبارية المحلية والعربية والدولية</li><li>إعداد التقارير والتحقيقات الصحفية</li><li>اللقاءات والحوار الإعلامي</li><li>التحليل السياسي والاقتصادي والاجتماعي</li></ul>',
                ],
                'en' => [
                    'title' => 'Press & News Services',
                    'details' => '<ul class="text-start"><li>Local, Arab and international news coverage</li><li>Preparing press reports and investigations</li><li>Media interviews and dialogue</li><li>Political, economic and social analysis</li></ul>',
                ],
            ],
            [
                'image' => 'fa-video-camera',
                'ar' => [
                    'title' => 'الإنتاج الإعلامي المرئي والمسموع',
                    'details' => '<ul class="text-start"><li>إنتاج الأفلام والتقارير الوثائقية</li><li>إعداد البرامج التلفزيونية والمنصات الرقمية</li><li>التصوير الفوتوغرافي والفيديو الاحترافي</li><li>المونتاج والإخراج الإعلامي</li></ul>',
                ],
                'en' => [
                    'title' => 'Audiovisual Media Production',
                    'details' => '<ul class="text-start"><li>Producing films and documentary reports</li><li>Preparing TV programs and digital platforms content</li><li>Professional photography and videography</li><li>Media editing and directing</li></ul>',
                ],
            ],
            [
                'image' => 'fa-globe',
                'ar' => [
                    'title' => 'الإعلام الرقمي وإدارة المحتوى',
                    'details' => '<ul class="text-start"><li>إدارة المواقع الإلكترونية والمنصات الإخبارية</li><li>صناعة المحتوى الرقمي</li><li>إدارة صفحات التواصل الاجتماعي</li><li>الحملات الإعلامية والتسويق الرقمي</li></ul>',
                ],
                'en' => [
                    'title' => 'Digital Media & Content Management',
                    'details' => '<ul class="text-start"><li>Managing websites and news platforms</li><li>Digital content creation</li><li>Managing social media pages</li><li>Media campaigns and digital marketing</li></ul>',
                ],
            ],
            [
                'image' => 'fa-line-chart',
                'ar' => [
                    'title' => 'الدراسات والبحوث الإعلامية',
                    'details' => '<ul class="text-start"><li>إعداد الدراسات والتقارير المتخصصة</li><li>رصد وتحليل اتجاهات الرأي العام</li><li>التدريب والتطوير في مجالات الإعلام والاتصال</li></ul>',
                ],
                'en' => [
                    'title' => 'Media Studies & Research',
                    'details' => '<ul class="text-start"><li>Preparing specialized studies and reports</li><li>Monitoring and analyzing public opinion trends</li><li>Training and development in media and communication</li></ul>',
                ],
            ],
            [
                'image' => 'fa-building-o',
                'ar' => [
                    'title' => 'الخدمات الإعلامية للمؤسسات',
                    'details' => '<ul class="text-start"><li>بناء الهوية الإعلامية للمؤسسات</li><li>إعداد البيانات الصحفية والنشرات الإعلامية</li><li>إدارة العلاقات الإعلامية والاتصال المؤسسي</li><li>تنظيم المؤتمرات والفعاليات الإعلامية</li></ul>',
                ],
                'en' => [
                    'title' => 'Corporate Media Services',
                    'details' => '<ul class="text-start"><li>Building institutions\' media identity</li><li>Preparing press releases and media bulletins</li><li>Managing media relations and corporate communication</li><li>Organizing media conferences and events</li></ul>',
                ],
            ],
            [
                'image' => 'fa-graduation-cap',
                'ar' => [
                    'title' => 'التدريب والتأهيل الإعلامي',
                    'details' => '<ul class="text-start"><li>دورات متخصصة في الصحافة والإعلام الرقمي</li><li>تدريب الصحفيين وصناع المحتوى</li><li>تطوير مهارات الاتصال والعلاقات العامة</li></ul>',
                ],
                'en' => [
                    'title' => 'Media Training & Qualification',
                    'details' => '<ul class="text-start"><li>Specialized courses in journalism and digital media</li><li>Training journalists and content creators</li><li>Developing communication and public relations skills</li></ul>',
                ],
            ],
        ];

        foreach ($services as $data) {
            Services::create([
                'image' => $data['image'],
                'url' => '',
                'ar' => $data['ar'],
                'en' => $data['en'],
            ]);
        }

        Cache::forget('services');
    }
}
