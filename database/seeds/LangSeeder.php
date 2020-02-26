<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('langs')->insert([
            [
                'lang' => 0, //العربيه
                'is_selected' => 1,
            ],
            [
                'lang' => 1,// English
                'is_selected' => 0,
            ],
        ]);
    }
}
