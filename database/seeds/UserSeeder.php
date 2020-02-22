<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            [
                'name' => 'admin',
                'email' => 'admin@gmail.com',
                'password' => bcrypt('123456'),
                'birth_date' => '1990-08-21 00:00:00',
                'is_admin' => 1,
            ],
            [
                'name' => 'user1',
                'email' => 'user1@gmail.com',
                'password' => bcrypt('123456'),
                'birth_date' => '1986-07-1 00:00:00',
                'is_admin' => 0,
            ],
            [
                'name' => 'user2',
                'email' => 'user2@gmail.com',
                'password' => bcrypt('123456'),
                'birth_date' => '2008-11-13 00:00:00',
                'is_admin' => 0,
            ],
            [
                'name' => 'user3',
                'email' => 'user3@gmail.com',
                'password' => bcrypt('123456'),
                'birth_date' => '1987-12-12 00:00:00',
                'is_admin' => 0,
            ],
        ]);
    }
}
