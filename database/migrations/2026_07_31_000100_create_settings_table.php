<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (Schema::hasTable('settings')) {
            return;
        }

        Schema::create('settings', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title_ar')->nullable();
            $table->string('title_en')->nullable();
            $table->text('description_ar')->nullable();
            $table->text('description_en')->nullable();
            $table->text('tags_ar')->nullable();
            $table->text('tags_en')->nullable();
            $table->string('contact_email')->nullable();
            $table->string('address')->nullable();
            $table->string('phone1')->nullable();
            $table->string('phone2')->nullable();
            $table->string('award')->nullable();
            $table->string('experinace')->nullable();
            $table->string('idea')->nullable();
            $table->string('projects')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // السجل رقم 1 مطلوب دائما: التطبيق يقرأه عبر Settings::findOrFail(1)
        DB::table('settings')->insert([
            'id' => 1,
            'title_ar' => 'مجموعة الوطن الاعلامية',
            'title_en' => 'Alwattan Media Group',
            'contact_email' => 'info@alwattan.ps',
            'address' => '',
            'phone1' => '',
            'phone2' => '',
            'award' => '0',
            'experinace' => '0',
            'idea' => '0',
            'projects' => '0',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('settings');
    }
};
