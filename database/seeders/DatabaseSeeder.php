<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
        $this->call(MenusPermissionsSeeder::class);
        $this->call(MenusTableSeeder::class);
        $this->call(MissingCategorySeeder::class);
        $this->call(MissingTranslationsSeeder::class);
        $this->call(AdminUserSeeder::class);
        $this->call(SyncPermissionsSeeder::class);
    }
}
