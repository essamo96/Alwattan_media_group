<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * جدول services_translations وصل فارغا من النسخة المستوردة، فبقيت الخدمات بلا عناوين،
 * ولا تظهر اصلا في لوحة التحكم لان الاستعلام يستخدم whereHas('translations').
 * هذا السيدر يضيف عناوين مبدئية فقط للسجلات التي لا ترجمة لها، ليمكن تعديلها من اللوحة.
 */
class MissingTranslationsSeeder extends Seeder
{
    public function run()
    {
        $this->fill('services', 'services_translations', 'services_id', function ($row, $locale) {
            return $locale === 'ar'
                ? ['title' => 'خدمة رقم ' . $row->id, 'details' => '']
                : ['title' => 'Service #' . $row->id, 'details' => ''];
        });

        $this->fill('pages', 'pages_translations', 'pages_id', function ($row, $locale) {
            return $locale === 'ar'
                ? ['title' => $row->slug, 'details' => '']
                : ['title' => $row->slug, 'details' => ''];
        });
    }

    private function fill(string $table, string $translations, string $foreignKey, callable $values): void
    {
        $rows = DB::table($table)->whereNull('deleted_at')->get();
        $added = 0;

        foreach ($rows as $row) {
            foreach (['ar', 'en'] as $locale) {
                $exists = DB::table($translations)
                    ->where($foreignKey, $row->id)
                    ->where('locale', $locale)
                    ->exists();

                if ($exists) {
                    continue;
                }

                DB::table($translations)->insert(array_merge(
                    $values($row, $locale),
                    [$foreignKey => $row->id, 'locale' => $locale]
                ));

                $added++;
            }
        }

        $this->command->info("  {$translations}: اضيف {$added} سجل ترجمة مبدئي");
    }
}
