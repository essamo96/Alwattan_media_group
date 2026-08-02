<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class FixServicesTable extends Command
{
    protected $signature = 'fix:services-table';
    protected $description = 'Restores PRIMARY KEY + AUTO_INCREMENT on services.id (broken by an old DB import) and reports every step';

    public function handle()
    {
        $db = DB::getDatabaseName();

        $hasPk = DB::table('information_schema.STATISTICS')
            ->where('TABLE_SCHEMA', $db)
            ->where('TABLE_NAME', 'services')
            ->where('INDEX_NAME', 'PRIMARY')
            ->exists();
        $this->info('has primary key (before): ' . ($hasPk ? 'yes' : 'no'));

        $dupes = DB::table('services')
            ->select('id', DB::raw('COUNT(*) as c'))
            ->groupBy('id')
            ->having('c', '>', 1)
            ->get();
        if ($dupes->count() > 0) {
            $this->error('Duplicate id values found, cannot add PRIMARY KEY safely: ' . $dupes->pluck('id')->implode(', '));
            return 1;
        }

        $nulls = DB::table('services')->whereNull('id')->count();
        if ($nulls > 0) {
            $this->error("$nulls row(s) have a NULL id, cannot add PRIMARY KEY safely.");
            return 1;
        }

        DB::statement("SET SESSION sql_mode = ''");

        if (!$hasPk) {
            DB::statement('ALTER TABLE services ADD PRIMARY KEY (id)');
            $this->info('Added PRIMARY KEY on services.id');
        }

        $type = DB::table('information_schema.COLUMNS')
            ->where('TABLE_SCHEMA', $db)
            ->where('TABLE_NAME', 'services')
            ->where('COLUMN_NAME', 'id')
            ->value('COLUMN_TYPE');
        $this->info('column type: ' . $type);

        DB::statement("ALTER TABLE services MODIFY id {$type} NOT NULL AUTO_INCREMENT");

        $next = (int) DB::table('services')->max('id') + 1;
        DB::statement("ALTER TABLE services AUTO_INCREMENT = {$next}");

        $extra = DB::table('information_schema.COLUMNS')
            ->where('TABLE_SCHEMA', $db)
            ->where('TABLE_NAME', 'services')
            ->where('COLUMN_NAME', 'id')
            ->value('EXTRA');
        $this->info('extra (after): ' . $extra);
        $this->info("next AUTO_INCREMENT value: {$next}");
        $this->info('Done.');

        return 0;
    }
}
