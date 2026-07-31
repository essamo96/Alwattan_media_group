<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * عمود deleted_at في permissions_group وصل NOT NULL بلا قيمة افتراضية من النسخة المستوردة،
 * فكان يمنع اضافة اي مجموعة صلاحيات جديدة.
 */
return new class extends Migration
{
    public function up()
    {
        if (! Schema::hasTable('permissions_group')) {
            return;
        }

        $sqlMode = DB::selectOne('SELECT @@SESSION.sql_mode AS mode')->mode;
        DB::statement("SET SESSION sql_mode = ''");
        DB::statement('ALTER TABLE `permissions_group` MODIFY `deleted_at` TIMESTAMP NULL DEFAULT NULL');
        DB::statement("UPDATE `permissions_group` SET `deleted_at` = NULL WHERE `deleted_at` = '0000-00-00 00:00:00'");
        DB::statement('SET SESSION sql_mode = ' . DB::getPdo()->quote($sqlMode));
    }

    public function down()
    {
        // لا تراجع
    }
};
