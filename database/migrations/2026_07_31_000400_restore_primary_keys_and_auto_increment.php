<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * النسخة المستوردة من قاعدة البيانات فقدت PRIMARY KEY و AUTO_INCREMENT على عدة جداول،
 * وهو ما كان يفشل اي عملية اضافة سجل جديد من لوحة التحكم:
 * SQLSTATE[HY000]: Field 'id' doesn't have a default value
 */
return new class extends Migration
{
    /** جداول عمود المعرف فيها رقمي */
    private array $numeric = [
        'cities', 'contact', 'faqs', 'images', 'languages', 'news', 'notes',
        'pages', 'pages_translations', 'partner', 'permissions',
        'permissions_group', 'roles', 'services', 'services_translations',
    ];

    /** جداول معرفها UUID: مفتاح اساسي فقط بدون ترقيم تلقائي */
    private array $uuid = ['notifications'];

    public function up()
    {
        $database = DB::getDatabaseName();

        // بعض الجداول المستوردة تحمل تواريخ صفرية كقيم افتراضية، وهي مرفوضة في الوضع الصارم
        $sqlMode = DB::selectOne('SELECT @@SESSION.sql_mode AS mode')->mode;
        DB::statement("SET SESSION sql_mode = ''");

        foreach (array_merge($this->numeric, $this->uuid) as $table) {
            if (! Schema::hasTable($table) || ! Schema::hasColumn($table, 'id')) {
                continue;
            }

            if (! $this->hasPrimaryKey($database, $table)) {
                DB::statement("ALTER TABLE `{$table}` ADD PRIMARY KEY (`id`)");
            }
        }

        foreach ($this->numeric as $table) {
            if (! Schema::hasTable($table) || $this->isAutoIncrement($database, $table)) {
                continue;
            }

            $type = $this->columnType($database, $table, 'id');
            DB::statement("ALTER TABLE `{$table}` MODIFY `id` {$type} NOT NULL AUTO_INCREMENT");

            $next = (int) DB::table($table)->max('id') + 1;
            DB::statement("ALTER TABLE `{$table}` AUTO_INCREMENT = {$next}");
        }

        DB::statement("SET SESSION sql_mode = " . DB::getPdo()->quote($sqlMode));
    }

    public function down()
    {
        // لا تراجع: هذه استعادة لبنية كانت مفقودة اصلا
    }

    private function hasPrimaryKey(string $database, string $table): bool
    {
        return DB::table('information_schema.STATISTICS')
            ->where('TABLE_SCHEMA', $database)
            ->where('TABLE_NAME', $table)
            ->where('INDEX_NAME', 'PRIMARY')
            ->exists();
    }

    private function isAutoIncrement(string $database, string $table): bool
    {
        $extra = DB::table('information_schema.COLUMNS')
            ->where('TABLE_SCHEMA', $database)
            ->where('TABLE_NAME', $table)
            ->where('COLUMN_NAME', 'id')
            ->value('EXTRA');

        return str_contains((string) $extra, 'auto_increment');
    }

    private function columnType(string $database, string $table, string $column): string
    {
        return (string) DB::table('information_schema.COLUMNS')
            ->where('TABLE_SCHEMA', $database)
            ->where('TABLE_NAME', $table)
            ->where('COLUMN_NAME', $column)
            ->value('COLUMN_TYPE');
    }
};
