<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ConditionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('conditions')->insert([
            [
                'text' => 'الشروط و الاحكام 1'
            ],
            [
                'text' => 'الشروط و الاحكام 2'
            ],
            [
                'text' => 'الشروط و الاحكام 3'
            ],
        ]);
    }
}
