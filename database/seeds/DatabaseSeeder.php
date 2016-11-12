<?php

use Illuminate\Database\Seeder;
use App\Earninglevel;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	Earninglevel::truncate();

        DB::table('earninglevels')->insert([
            'id' => 0,
            'value' => 'Unbekannt'
        ]);
        DB::table('earninglevels')->insert([
            'id' => 1,
            'value' => '1000-3.500 €'
        ]);
        DB::table('earninglevels')->insert([
            'id' => 2,
            'value' => 'bis 7.000 €'
        ]);
        DB::table('earninglevels')->insert([
            'id' => 3,
            'value' => 'bis 15.000 €'
        ]);
        DB::table('earninglevels')->insert([
            'id' => 4,
            'value' => 'bis 30.000 €'
        ]);
        DB::table('earninglevels')->insert([
            'id' => 5,
            'value' => 'bis 50.000 €'
        ]);
        DB::table('earninglevels')->insert([
            'id' => 6,
            'value' => 'bis 75.000 €'
        ]);
        DB::table('earninglevels')->insert([
            'id' => 7,
            'value' => 'bis 100.000 €'
        ]);
        DB::table('earninglevels')->insert([
            'id' => 8,
            'value' => 'bis 150.000 €'
        ]);
        DB::table('earninglevels')->insert([
            'id' => 9,
            'value' => 'bis 250.000 €'
        ]);
        DB::table('earninglevels')->insert([
            'id' => 10,
            'value' => 'über 250.000 €'
        ]);
    }
}
