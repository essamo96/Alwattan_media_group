<?php

namespace Database\Seeders;

use App\Models\News;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NewsViewsSeeder extends Seeder
{
    /**
     * يضبط مشاهدات الأخبار الحالية على الرقم الافتراضي (77K، أو القيمة المحفوظة في الإعدادات).
     *
     * يلمس فقط الأخبار التي مشاهداتها صفر/فارغة، فلا يكتب فوق رقم عُدّل يدوياً من لوحة التحكم
     * أو من قاعدة البيانات، وبالتالي يمكن إعادة تشغيله بأمان:
     *   php artisan db:seed --class=NewsViewsSeeder
     */
    public function run()
    {
        $views = News::defaultViews();

        $updated = DB::table('news')
            ->where(function ($query) {
                $query->whereNull('views')->orWhere('views', 0);
            })
            ->update(['views' => $views]);

        News::forgetListingCache();

        // ينفَّذ أيضاً من داخل migration (بدون $this->command) لأن النشر يشغّل migrate فقط ولا يشغّل db:seed.
        if ($this->command) {
            $this->command->info("News views seeded: {$updated} rows set to {$views}.");
        }
    }
}
