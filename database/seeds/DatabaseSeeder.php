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
         //$this->call(CompanyUpdateSeeder::class);
	    $this->call(AdminUserSeeder::class);
        $this->call(PermissionsTableSeeder::class);
        $this->call(ContactsTableSeeder::class);
        $this->call(NewsTableSeeder::class);
        $this->call(NewsletterTableSeeder::class);
        $this->call(ProductTableSeeder::class);
        $this->call(PromotionTableSeeder::class);
        $this->call(SysMenuTableSeeder::class);
	    //$this->call(DataDemoSeeder::class);
    }
}
