<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * يتتبع هل صور الخبر (الرئيسية + المعرض) عليها حالياً علامة مائية محروقة فعلياً
     * بالملف أم لا، حتى يقدر الأدمن يشيلها/يرجعها من قائمة الأخبار بأي وقت بعد
     * النشر — دون الاعتماد على إعادة رفع الصور من جديد (انظر Watermark::applyToNews /
     * removeFromNews في app/Support/Watermark.php).
     */
    public function up()
    {
        Schema::table('news', function (Blueprint $table) {
            if (!Schema::hasColumn('news', 'watermark_applied')) {
                $table->boolean('watermark_applied')->default(false)->after('video_source');
            }
        });
    }

    public function down()
    {
        Schema::table('news', function (Blueprint $table) {
            if (Schema::hasColumn('news', 'watermark_applied')) {
                $table->dropColumn('watermark_applied');
            }
        });
    }
};
