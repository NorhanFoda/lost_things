<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('posts')->insert([
            [
                'title' => 'سيارات',
                'description' => null,
                'location' => null,
                'found' => 0,
                'category_id' => null,
                'user_id' => null,
            ],
            [
                'title' => 'ملابس',
                'description' => null,
                'location' => null,
                'found' => 0,
                'category_id' => null,
                'user_id' => null,
            ],
            [
                'title' => 'موبايل',
                'description' => null,
                'location' => null,
                'found' => 0,
                'category_id' => null,
                'user_id' => null,
            ],

            [
                'title' => 'سياره جراند شروكى',
                'description' => 'سياره جراند شروكى موديل 2019 بيضاء بها خدش من الجانب',
                'location' => 'الرياض',
                'found' => 0,
                'category_id' => 1,
                'user_id' => 2,
            ],
            [
                'title' => 'جاكت جلد',
                'description' => 'جاكت جلد اسود ماركة شانيل',
                'location' => 'جده',
                'found' => 0,
                'category_id' => 2,
                'user_id' => 2,
            ],
            [
                'title' => 'موبايل ايفون',
                'description' => 'موبايل ايفون ',
                'location' => 'جده',
                'found' => 1,
                'category_id' => 3,
                'user_id' => 2,
            ],
        ]);
    }
}
