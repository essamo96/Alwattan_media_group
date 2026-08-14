<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * جدول news قديم (من قبل نظام الـ migrations، مستورد أصلاً)، والموديل News.php
     * كان أصلاً بـ $fillable عنده 'type' و 'video' — يبدو إنها كانت مخطط لها بالتصميم
     * الأصلي بس ما انربطت أبداً بنموذج الإضافة/التعديل. نتأكد من وجودها فعلياً
     * بأمان (hasColumn) بدل الافتراض، ونضيفها إذا ناقصة فقط.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('news', function (Blueprint $table) {
            if (!Schema::hasColumn('news', 'type')) {
                $table->string('type')->default('image')->after('image'); // 'image' | 'video'
            }
            if (!Schema::hasColumn('news', 'video')) {
                $table->string('video')->nullable()->after('type'); // رابط فيديو خارجي (يوتيوب/فيميو) أو رابط ملف مباشر
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('news', function (Blueprint $table) {
            if (Schema::hasColumn('news', 'video')) {
                $table->dropColumn('video');
            }
            if (Schema::hasColumn('news', 'type')) {
                $table->dropColumn('type');
            }
        });
    }
};
