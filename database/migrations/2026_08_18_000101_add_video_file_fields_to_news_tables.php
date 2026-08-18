<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * يميّز فيديو الخبر الرئيسي/المعرض بين "رابط خارجي" و"ملف مرفوع"، لأن الواجهة
     * تحتاج تعرف تعمل iframe (يوتيوب/فيميو) أو <video> (ملف مرفوع) — القيمة نفسها
     * (news.video / news_media.video_url) تبقى تخزّن المسار أو الرابط كما هي.
     */
    public function up()
    {
        Schema::table('news', function (Blueprint $table) {
            if (!Schema::hasColumn('news', 'video_source')) {
                $table->string('video_source')->default('url')->after('video'); // 'url' | 'file'
            }
        });
        Schema::table('news_media', function (Blueprint $table) {
            if (!Schema::hasColumn('news_media', 'video_source')) {
                $table->string('video_source')->default('url')->after('video_url'); // 'url' | 'file'
            }
        });
    }

    public function down()
    {
        Schema::table('news', function (Blueprint $table) {
            if (Schema::hasColumn('news', 'video_source')) {
                $table->dropColumn('video_source');
            }
        });
        Schema::table('news_media', function (Blueprint $table) {
            if (Schema::hasColumn('news_media', 'video_source')) {
                $table->dropColumn('video_source');
            }
        });
    }
};
