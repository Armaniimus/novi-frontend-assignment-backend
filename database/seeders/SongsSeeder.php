<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SongsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('songs')->insert([
            'number'    =>  99,
            'title'     =>  '\'t Dondert en \'t bliksemt',
        ]);

        DB::table('songs')->insert([
            'number'    =>  42,
            'title'     =>  '\'t Is moeilijk bescheiden te blijven',
        ]);

        DB::table('songs')->insert([
            'number'    =>  80,
            'title'     =>  '\'t Is weer voorbij die mooie zomer',
        ]);

        DB::table('songs')->insert([
            'number'    =>  22,
            'title'     =>  '15 miljoen mensen',
        ]);        
    }
}
