<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BlockSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('blocks')->insert([
            [
                'user_id' => 2,
                'blocked_id' => 3,
            ],
            [
                'user_id' => 4,
                'blocked_id' => 2,
            ],
        ]);
    }
}
