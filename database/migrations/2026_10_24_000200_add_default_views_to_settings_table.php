<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * عدد المشاهدات الافتراضي للأخبار (77K) — يُستخدم للأخبار الجديدة عند عدم إدخال رقم يدوياً،
     * ويمكن تغييره من لوحة التحكم (الإعدادات).
     */
    public function up()
    {
        Schema::table('settings', function (Blueprint $table) {
            if (!Schema::hasColumn('settings', 'default_views')) {
                $table->unsignedBigInteger('default_views')->default(77000)->after('projects');
            }
        });
    }

    public function down()
    {
        Schema::table('settings', function (Blueprint $table) {
            if (Schema::hasColumn('settings', 'default_views')) {
                $table->dropColumn('default_views');
            }
        });
    }
};
