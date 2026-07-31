<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * جدول categories كان مفقودا من النسخة المستوردة، بينما كل الاخبار تشير الى category_id = 2.
 * هذا السيدر يعيد بناء ذلك القسم حتى تعمل علاقة News::category.
 */
class MissingCategorySeeder extends Seeder
{
    public function run()
    {
        $ids = DB::table('news')->distinct()->pluck('category_id');

        foreach ($ids as $id) {
            if (DB::table('categories')->where('id', $id)->exists()) {
                continue;
            }

            DB::table('categories')->insert([
                'id' => $id,
                'sort' => $id,
                'category_id' => 0,
                'tags' => '',
                'slug' => 'blog',
                'col_no' => 0,
                'status' => 1,
                'in_menu' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            foreach (['ar' => 'مقالات', 'en' => 'Articles'] as $locale => $name) {
                DB::table('categories_translations')->insert([
                    'categories_id' => $id,
                    'locale' => $locale,
                    'name' => $name,
                ]);
            }
        }
    }
}
