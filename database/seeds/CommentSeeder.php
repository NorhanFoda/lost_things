<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('comments')->insert([
            [
                'text' => 'تعليق 1',
                'post_id' => 4,
                'user_id' => 2,
            ],
            [
                'text' => 'تعليق 2',
                'post_id' => 4,
                'user_id' => 3,
            ],
            [
                'text' => 'تعليق 3',
                'post_id' => 5,
                'user_id' => 3,
            ],
            [
                'text' => 'تعليق 4',
                'post_id' => 5,
                'user_id' => 4,
            ],
            [
                'text' => 'تعليق 5',
                'post_id' => 6,
                'user_id' => 2,
            ],
            [
                'text' => 'تعليق 6',
                'post_id' => 5,
                'user_id' => 4,
            ],
        ]);
    }
}
