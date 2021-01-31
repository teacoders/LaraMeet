<?php

use App\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
        User::create([
            'name' => 'Moolchand Sharma',
            'email' => 'sharma@gmail.com',
            'password' => bcrypt('pass'),
        ]);
        User::create([
            'name' => 'Sunil Tiwari',
            'email' => 'tiwari@gmail.com',
            'password' => bcrypt('pass'),
        ]);
    }
}
