<?php

namespace Modules\Product\Database\Seeders;

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
        //$this->call(CompanyUpdateSeeder::class);
        $this->call(ProductDatabaseSeeder::class);
    }
}