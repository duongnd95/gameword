<?php
use Illuminate\Database\Seeder;

/**
 * Class AdminUserSeeder
 */
class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        try {
            factory(App\User::class)->create([
                    "name" => "Huesoft",
                    "email" => "huesoft.it@gmail.com",
                    "username" => "admin",
                    "password" => bcrypt(env('ADMIN_PWD', 'hs@12345'))]
            );
        } catch (\Illuminate\Database\QueryException $exception) {

        }
    }
}