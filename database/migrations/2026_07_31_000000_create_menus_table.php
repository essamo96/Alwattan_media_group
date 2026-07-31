<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMenusTable extends Migration {

    /**
     * قائمة الناف بار في الموقع الخارجي
     */
    public function up() {
        if (Schema::hasTable('menus')) {
            return;
        }
        Schema::create('menus', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name_ar');
            $table->string('name_en')->nullable();
            $table->string('url')->nullable();          // المسار كما هو حالياً (#section-hero ..)
            $table->string('icon')->nullable();         // كلاس الايقونة بجانب القائمة
            $table->string('image')->nullable();        // لوجو بديل عن الايقونة
            $table->string('target')->default('_self');
            $table->tinyInteger('type')->default(0);    // 0 = رابط عادي , 1 = مبدل اللغة
            $table->integer('sort')->default(0);
            $table->tinyInteger('status')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down() {
        Schema::dropIfExists('menus');
    }

}
