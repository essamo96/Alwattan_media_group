<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * إعدادات العلامة المائية العامة للموقع — تُطبع فعلياً على الصور المرفوعة
     * (خبر رئيسي + معرض)، وتُعرض كطبقة فوق الفيديوهات (مرفوعة أو خارجية) بالواجهة،
     * لأنه لا يمكن حرقها داخل ملف فيديو خارجي (يوتيوب/فيميو) من طرف السيرفر،
     * ولا نضمن توفر ffmpeg على كل بيئة استضافة لحرقها داخل الفيديوهات المرفوعة.
     */
    public function up()
    {
        Schema::table('settings', function (Blueprint $table) {
            if (!Schema::hasColumn('settings', 'watermark_enabled')) {
                $table->boolean('watermark_enabled')->default(false)->after('projects');
            }
            if (!Schema::hasColumn('settings', 'watermark_logo')) {
                $table->string('watermark_logo')->nullable()->after('watermark_enabled');
            }
            if (!Schema::hasColumn('settings', 'watermark_position')) {
                $table->string('watermark_position')->default('bottom-right')->after('watermark_logo');
            }
            if (!Schema::hasColumn('settings', 'watermark_opacity')) {
                $table->unsignedTinyInteger('watermark_opacity')->default(70)->after('watermark_position');
            }
            if (!Schema::hasColumn('settings', 'watermark_size')) {
                $table->unsignedTinyInteger('watermark_size')->default(15)->after('watermark_opacity');
            }
        });
    }

    public function down()
    {
        Schema::table('settings', function (Blueprint $table) {
            foreach (['watermark_enabled', 'watermark_logo', 'watermark_position', 'watermark_opacity', 'watermark_size'] as $col) {
                if (Schema::hasColumn('settings', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
