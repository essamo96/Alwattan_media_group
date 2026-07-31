<?php

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
        // require صريح حتى يعمل السيدر دون الحاجة الى composer dump-autoload
        require_once __DIR__ . '/MenusPermissionsSeeder.php';
        require_once __DIR__ . '/MenusTableSeeder.php';

        $this->call(MenusPermissionsSeeder::class);
        $this->call(MenusTableSeeder::class);
    }
}
