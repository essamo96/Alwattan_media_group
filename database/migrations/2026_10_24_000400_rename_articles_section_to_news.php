<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * تسمية قسم "مقالات" (القسم blog وعنصر القائمة المرتبط به) إلى "الأخبار".
     * تُغيَّر القيم فقط إن كانت ما تزال الاسم القديم الافتراضي، فلا نكتب فوق اسم عدّله الأدمن
     * من لوحة التحكم. الأسماء مخزّنة بالكاش للأبد (القائمة، الأقسام، قائمة الرئيسية)، فنمسحها هنا.
     */
    public function up()
    {
        $category_ids = [];

        if (Schema::hasTable('categories') && Schema::hasTable('categories_translations')) {
            $category_ids = DB::table('categories')->where('slug', 'blog')->pluck('id')->all();

            foreach (['ar' => ['مقالات', 'الأخبار'], 'en' => ['Articles', 'News']] as $locale => [$old, $new]) {
                DB::table('categories_translations')
                    ->whereIn('categories_id', $category_ids)
                    ->where('locale', $locale)
                    ->where('name', $old)
                    ->update(['name' => $new]);
            }
        }

        if (Schema::hasTable('menus')) {
            DB::table('menus')->where('url', '#section-schedule')->where('name_ar', 'مقالات')->update(['name_ar' => 'الأخبار']);
            DB::table('menus')->where('url', '#section-schedule')->where('name_en', 'Blog')->update(['name_en' => 'News']);
        }

        Cache::forget('menus');
        Cache::forget('categories');
        foreach (['ar', 'en'] as $locale) {
            Cache::forget('lastnews_' . $locale);
        }
        foreach ($category_ids as $id) {
            Cache::forget('category_info_' . $id);
            Cache::forget('category_news_' . $id);
        }
    }

    public function down()
    {
        // تعديل نصوص فقط — لا نعيد الاسم القديم تلقائياً.
    }
};
