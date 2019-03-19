<?php

namespace Lebenlabs\SimpleCMS\Database\Seeds;

use Illuminate\Database\Seeder;

class PackageDatabaseSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(MenuTableSeeder::class);
        $this->call(MenuItemTableSeeder::class);
        $this->call(CategoriaTableSeeder::class);
        $this->call(PublicacionTableSeeder::class);
    }
}
