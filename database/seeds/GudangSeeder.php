<?php

use Illuminate\Database\Seeder;

class GudangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('gudang')->insert([
          [
            'nama'=>'Toko Medan',
            'cabang_id'=>1
          ],
          [
            'nama'=>'Gudang Medan',
            'cabang_id'=>1
          ],
          [
            'nama'=>'Toko Cibinong',
            'cabang_id'=>2
          ],
          [
            'nama'=>'Gudang Cibinong',
            'cabang_id'=>2
          ],
        ]);
    }
}
