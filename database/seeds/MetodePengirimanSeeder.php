<?php

use Illuminate\Database\Seeder;

class MetodePengirimanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('metode_pengiriman')->insert([
          [
            'nama'=>'Kurir Toko',
            'jenis'=>'diantar',
          ],

          [
            'nama'=>'Ambil di Toko',
            'jenis'=>'dijemput',
          ],

          [
            'nama'=>'JNE',
            'jenis'=>'custom',
          ],
          [
            'nama'=>'JNT',
            'jenis'=>'custom',
          ],
          [
            'nama'=>'POS',
            'jenis'=>'custom',
          ],
          [
            'nama'=>'Tiki',
            'jenis'=>'custom',
          ]
        ]);
    }
}
