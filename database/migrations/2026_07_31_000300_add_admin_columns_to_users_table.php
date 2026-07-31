<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * جدول users كان بالبنية الافتراضية لـ Laravel، بينما الكود (App\Models\User و LoginController)
 * يعتمد على username و role و created_by و status و SoftDeletes.
 */
return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            if (! Schema::hasColumn('users', 'username')) {
                $table->string('username', 100)->unique()->after('id');
            }
            if (! Schema::hasColumn('users', 'role')) {
                $table->integer('role')->default(0)->after('email');
            }
            if (! Schema::hasColumn('users', 'created_by')) {
                $table->integer('created_by')->default(0)->after('role');
            }
            if (! Schema::hasColumn('users', 'status')) {
                $table->tinyInteger('status')->default(1)->after('created_by');
            }
            if (! Schema::hasColumn('users', 'deleted_at')) {
                $table->softDeletes();
            }
        });

        // تسجيل الدخول يتم بـ username، فلا داعي لالزامية البريد
        if (Schema::hasColumn('users', 'email')) {
            DB::statement('ALTER TABLE `users` MODIFY `email` VARCHAR(255) NULL');
        }
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            foreach (['username', 'role', 'created_by', 'status'] as $column) {
                if (Schema::hasColumn('users', $column)) {
                    $table->dropColumn($column);
                }
            }
            if (Schema::hasColumn('users', 'deleted_at')) {
                $table->dropSoftDeletes();
            }
        });
    }
};
