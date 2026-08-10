<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('course_registrations', function (Blueprint $table) {
            $table->boolean('has_disability')->default(false)->after('employer');
            $table->string('disability_description', 500)->nullable()->after('has_disability');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('course_registrations', function (Blueprint $table) {
            $table->dropColumn(['has_disability', 'disability_description']);
        });
    }
};
