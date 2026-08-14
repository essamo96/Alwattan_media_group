<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * صور/فيديوهات إضافية تابعة لخبر واحد (المعرض/الريبيتر) — مستقلة عن صورة/فيديو
     * الخبر الرئيسي (news.image / news.video).
     *
     * @return void
     */
    public function up()
    {
        // جدول news قديم (من قبل نظام الـ migrations) وعمود id فيه لا يطابق بالضرورة
        // نوع/توقيع bigint unsigned القياسي لموديلات Laravel الحديثة — رُصد فعلياً
        // فشل بـ "Foreign key constraint is incorrectly formed" (errno 150) عند محاولة
        // ربط FK حقيقي بـ news.id على قاعدة الإنتاج. لتفادي أي افتراض خاطئ حول نوع/محرك/
        // ترميز الجدول القديم، نُبقي news_id عمود عادي (بدون قيد FK على مستوى القاعدة)،
        // ونعتمد بدلاً منه على حذف يدوي بمستوى التطبيق عند حذف الخبر (انظر
        // News::deleteNews في app/Models/News.php).
        if (!Schema::hasTable('news_media')) {
            Schema::create('news_media', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('news_id')->index();
                $table->enum('type', ['image', 'video']);
                $table->string('path')->nullable();      // صورة مرفوعة (uploads/news/gallery/..)
                $table->string('video_url')->nullable();  // فيديو خارجي (رابط يوتيوب/فيميو/مباشر)
                $table->unsignedInteger('sort_order')->default(0);
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('news_media');
    }

    // ملاحظة: هاي migration تحل محل نسخة سابقة فشلت جزئياً (أنشأت الجدول ثم فشل
    // إضافة الـFK)، لذلك up() تتحقق من hasTable قبل الإنشاء لتكون آمنة لإعادة التشغيل.
};
