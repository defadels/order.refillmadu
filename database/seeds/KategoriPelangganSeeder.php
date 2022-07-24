<?php

use Illuminate\Database\Seeder;

class KategoriPelangganSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('kategori_pelanggan')->insert([

          ['nama'=>'Distributor'],
          ['nama'=>'Agen'],
          ['nama'=>'Reseller'],
          ['nama'=>'Sub Reseller'],
          ['nama'=>'Ecer'],
        ]);
    }
}
