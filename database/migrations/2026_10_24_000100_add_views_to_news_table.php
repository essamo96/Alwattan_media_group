<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * عدّاد مشاهدات الخبر. العمود موجود أصلاً على قاعدة الإنتاج (جاءت من dump قديم) ولا توجد
     * migration سابقة تنشئه، لذلك نضيفه فقط إن كان ناقصاً حتى يشتغل الـ migrate على أي بيئة.
     * القيمة الابتدائية 77K تُضبط عبر NewsViewsSeeder وليس هنا.
     */
    public function up()
    {
        if (!Schema::hasColumn('news', 'views')) {
            Schema::table('news', function (Blueprint $table) {
                $table->unsignedBigInteger('views')->default(0)->after('publish');
            });
        }
    }

    public function down()
    {
        // العمود قد يكون سابقاً لهذه الـ migration (من الـ dump الأصلي) فلا نحذفه عند التراجع.
    }
};
