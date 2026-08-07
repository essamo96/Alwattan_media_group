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
        Schema::create('course_registrations', function (Blueprint $table) {
            $table->id();
            $table->string('full_name');
            $table->string('national_id', 20);
            $table->enum('gender', ['male', 'female']);
            $table->date('birth_date');
            $table->string('general_specialization');
            $table->string('specific_specialization');
            $table->unsignedSmallInteger('graduation_year');
            $table->string('university');
            $table->decimal('gpa', 5, 2);
            $table->string('nationality');
            $table->string('current_address');
            $table->string('birth_place');
            $table->string('employer');
            $table->enum('marital_status', ['single', 'married', 'divorced', 'widowed']);
            $table->string('mobile', 20);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('course_registrations');
    }
};
