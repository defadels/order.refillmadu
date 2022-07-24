<?php

use Illuminate\Database\Seeder;

class CabangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('cabang')->insert([
          [
            'nama'=>'Medan'
          ],
          [
            'nama'=>'Cibinong'
          ]
        ]);

        DB::table('users')->update(['cabang_id'=>1]);
    }
}
