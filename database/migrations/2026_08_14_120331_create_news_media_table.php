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
        Schema::create('news_media', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('news_id');
            $table->enum('type', ['image', 'video']);
            $table->string('path')->nullable();      // صورة مرفوعة (uploads/news/gallery/..)
            $table->string('video_url')->nullable();  // فيديو خارجي (رابط يوتيوب/فيميو/مباشر)
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();

            $table->foreign('news_id')->references('id')->on('news')->cascadeOnDelete();
        });
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
};
