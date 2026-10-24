<?php

use Database\Seeders\NewsViewsSeeder;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * ورشة النشر تشغّل `migrate --force` فقط ولا تشغّل `db:seed` عمداً (انظر DEPLOY.md)، فنستدعي
     * NewsViewsSeeder من هنا مرة واحدة ليأخذ كل خبر مشاهداته الابتدائية 77K على الموقع الحي.
     * آمنة: تلمس فقط الأخبار التي مشاهداتها 0/فارغة ولا تكتب فوق أي رقم معدّل يدوياً.
     */
    public function up()
    {
        if (!Schema::hasColumn('news', 'views') || !Schema::hasColumn('settings', 'default_views')) {
            return;
        }

        (new NewsViewsSeeder())->run();
    }

    public function down()
    {
        // بيانات فقط — لا شيء للتراجع عنه.
    }
};
