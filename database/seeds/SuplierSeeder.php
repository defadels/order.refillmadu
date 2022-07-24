<?php

use Illuminate\Database\Seeder;

class SuplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      DB::table('suplier')->insert([
        [
          'nama'=>'Suplier 1',
          'cabang_id'=>1
        ],
        [
          'nama'=>'Suplier 2',
          'cabang_id'=>1
        ],
        [
          'nama'=>'Suplier 1',
          'cabang_id'=>2
        ],
        [
          'nama'=>'Suplier 2',
          'cabang_id'=>2
        ],
      ]);
    }
}
