<?php

use Illuminate\Database\Seeder;

class MetodePembayaranSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('metode_pembayaran')->insert([
          [
          'nama'=>'Cash On Delivery',
          'jenis'=>'cod',
          'kas_id'=>1
          ],

          [
            'nama'=>'Bayar Di Toko',
            'jenis'=>'cash',
            'kas_id'=>1
          ],

          [
            'nama'=>'Dompet',
            'jenis'=>'dompet',
            'kas_id'=>null
          ],

          [
            'nama'=>'Transfer Bank BNI',
            'jenis'=>'bank',
            'kas_id'=>4
          ],

          [
            'nama'=>'Transfer Bank Mandiri',
            'jenis'=>'bank',
            'kas_id'=>5
          ],

        ]);
    }
}
