<?php

use Illuminate\Database\Seeder;

class PengeluaranSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      DB::table('kategori_pengeluaran')->insert([
        [
            'nama' =>'Pengiriman',
        ],
        [
            'nama' =>'Internet',

        ],
        [
            'nama' =>'Listrik',

        ],
        [
            'nama' =>'Pulsa',

        ],
        [
            'nama' =>'Air',

        ],
        [
            'nama' =>'Gaji',
        ],
        [
            'nama' =>'Marketing',
        ],
        [
            'nama' =>'Pembelian Aset',
        ],
        [
            'nama' =>'Penyaluran',
        ]


    ]);
    }
}
