<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ImageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('images')->insert([
            [
                'path' => 'image path 1',
                'post_id' => 4,
            ],
            [
                'path' => 'image path 2',
                'post_id' => 5,
            ],
            [
                'path' => 'image path 3',
                'post_id' => 6,
            ],
        ]);
    }
}
