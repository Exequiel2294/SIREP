<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        for($j=1;$j<=9;$j++)
        {
            for($i=1; $i<=3; $i++)
            {
                DB::table('data')->insert([
                'variable_id' => $i,
                'fecha' => '2022-01-2'.$j,
                'valor' => rand(10, 1000000),
                'estado' => 1
                ]);
            }
            for($i=5; $i<=11; $i++)
            {
                DB::table('data')->insert([
                'variable_id' => $i,
                'fecha' => '2022-01-2'.$j,
                'valor' => rand(10, 1000000),
                'estado' => 1
                ]);
            }
        }
    }
}
