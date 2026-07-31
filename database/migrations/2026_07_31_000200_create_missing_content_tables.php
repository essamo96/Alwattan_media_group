<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * الجداول التالية يستخدمها الكود لكنها كانت مفقودة من قاعدة البيانات المستوردة.
 * الاعمدة مشتقة من الـ Models (fillable / translatedAttributes) ومن شاشات الادارة،
 * وتتبع نفس نمط الجداول الموجودة (pages / services / partner).
 */
return new class extends Migration
{
    public function up()
    {
        $this->create('socials', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 100);
            $table->string('link', 255)->nullable();
            $table->string('icon', 100)->nullable();
            $table->tinyInteger('status')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });

        $this->create('sliders', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 300)->nullable();
            $table->text('name1')->nullable();
            $table->text('name2')->nullable();
            $table->string('language', 10)->default('ar');
            $table->string('image', 255)->nullable();
            $table->string('link', 255)->nullable();
            $table->tinyInteger('txt_status')->default(1);
            $table->tinyInteger('status')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });

        $this->create('categories', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('sort')->default(0);
            $table->integer('category_id')->default(0);
            $table->string('tags', 250)->nullable();
            $table->string('slug', 191);
            $table->integer('col_no')->default(0);
            $table->tinyInteger('status')->default(1);
            $table->tinyInteger('in_menu')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });

        $this->create('categories_translations', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 191);
            $table->string('locale', 100)->index();
            $table->unsignedInteger('categories_id');
            $table->unique(['categories_id', 'locale']);
        });

        $this->create('comments', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 191);
            $table->string('email', 191)->nullable();
            $table->text('details')->nullable();
            $table->integer('parent_id')->default(0);
            $table->integer('news_id')->default(0);
            $table->tinyInteger('status')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });

        $this->create('contacts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 191);
            $table->string('email', 191)->nullable();
            $table->string('mobile', 50)->nullable();
            $table->string('subject', 255)->nullable();
            $table->text('message')->nullable();
            $table->tinyInteger('status')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });

        $this->create('photo', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title', 300)->nullable();
            $table->text('descs')->nullable();
            $table->string('image', 255)->nullable();
            $table->tinyInteger('status')->default(1);
            $table->integer('user_id')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });

        $this->create('testimonials', function (Blueprint $table) {
            $table->increments('id');
            $table->string('image', 255)->nullable();
            $table->tinyInteger('status')->default(1);
            $table->integer('p_order')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        $this->create('testimonials_translations', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 191)->nullable();
            $table->string('nickname', 191)->nullable();
            $table->string('title', 191)->nullable();
            $table->text('descs')->nullable();
            $table->string('locale', 100)->index();
            $table->unsignedInteger('testimonials_id');
            $table->unique(['testimonials_id', 'locale']);
        });

        $this->create('properties_types', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name_ar', 191);
            $table->string('name_en', 191)->nullable();
            $table->tinyInteger('status')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });

        $this->create('properties_categories', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('sort')->default(0);
            $table->string('slug', 191);
            $table->tinyInteger('status')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });

        $this->create('properties_categories_translations', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title', 191);
            $table->text('descs')->nullable();
            $table->string('locale', 100)->index();
            $table->unsignedInteger('properties_categories_id');
            $table->unique(['properties_categories_id', 'locale'], 'prop_cat_trans_unique');
        });

        $this->create('properties', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('category_id')->default(0);
            $table->integer('property_type')->default(0);
            $table->integer('city')->default(0);
            $table->string('image', 255)->nullable();
            $table->string('price', 100)->nullable();
            $table->string('area', 100)->nullable();
            $table->string('annual_return', 100)->nullable();
            $table->integer('bathroom')->default(0);
            $table->integer('bedroom')->default(0);
            $table->string('longitude', 100)->nullable();
            $table->string('latitude', 100)->nullable();
            $table->tinyInteger('is_new')->default(0);
            $table->tinyInteger('status')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });

        $this->create('properties_translations', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title', 191);
            $table->longText('details')->nullable();
            $table->string('locale', 100)->index();
            $table->unsignedInteger('properties_id');
            $table->unique(['properties_id', 'locale']);
        });
    }

    public function down()
    {
        foreach ([
            'properties_translations',
            'properties',
            'properties_categories_translations',
            'properties_categories',
            'properties_types',
            'testimonials_translations',
            'testimonials',
            'photo',
            'contacts',
            'comments',
            'categories_translations',
            'categories',
            'sliders',
            'socials',
        ] as $table) {
            Schema::dropIfExists($table);
        }
    }

    /**
     * ينشئ الجدول فقط اذا لم يكن موجودا، حتى لا يتعارض مع اي استيراد لاحق للنسخة الاصلية.
     */
    private function create(string $name, callable $definition): void
    {
        if (! Schema::hasTable($name)) {
            Schema::create($name, $definition);
        }
    }
};
