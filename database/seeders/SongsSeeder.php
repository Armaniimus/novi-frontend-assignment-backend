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
            'songText'      =>  'het donderd en het bliksemd en het regent meters bier het word dus pompen of verzuipen',
        ]);

        DB::table('songs')->insert([
            'number'    =>  42,
            'title'     =>  '\'t Is moeilijk bescheiden te blijven',
            'songText'      =>  'moeilijk moeilijk',
        ]);

        DB::table('songs')->insert([
            'number'    =>  80,
            'title'     =>  '\'t Is weer voorbij die mooie zomer',
            'songText'      =>  'la la',
        ]);

        DB::table('songs')->insert([
            'number'    =>  22,
            'title'     =>  '15 miljoen mensen',
            'songText'      =>  '15 miljoen mensen op dit hele kleine stukje aardi die moeten niet het keurslijf in die laat je in hun waarde',
        ]);        
    }
}
