<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * يضيف parent_id لدعم قوائم منسدلة (قائمة اب وقوائم فرعية) في الناف بار،
 * بنفس اسلوب category_id في جدول categories: 0 = قائمة رئيسية بلا اب.
 */
return new class extends Migration
{
    public function up()
    {
        if (! Schema::hasColumn('menus', 'parent_id')) {
            Schema::table('menus', function (Blueprint $table) {
                $table->integer('parent_id')->default(0)->after('id');
            });
        }
    }

    public function down()
    {
        if (Schema::hasColumn('menus', 'parent_id')) {
            Schema::table('menus', function (Blueprint $table) {
                $table->dropColumn('parent_id');
            });
        }
    }
};
