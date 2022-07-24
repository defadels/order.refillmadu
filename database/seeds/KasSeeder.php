<?php

use Illuminate\Database\Seeder;

class KasSeeder extends Seeder
{

//  Kas kecil : 2.283.000
// Kas Besar : 21.947.000
// Rekening Bni 0329758122 : 0
// Rekening Bni 0905223883 : 9.235.964
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      DB::table('kas')->insert([
        [
          'nama'=>'Kas',
          'keterangan'=>'Kas Kecil',
          'saldo'=>0,
          'saldo_awal'=>0,
          'jenis'=>'ditangan',
          'kode'=>'utama',
          'cabang_id'=>1
        ]
        ]);
        DB::table('kas')->insert([
          [
            'nama'=>'Kas',
            'keterangan'=>'Kas Besar',
            'saldo'=>0,
            'saldo_awal'=>0,
            'jenis'=>'ditangan',
            'kode'=>'utama',
            'cabang_id'=>1
          ]
          ]);
        DB::table('kas')->insert([
          [
            'nama'=>'Kas',
            'keterangan'=>'Kas Ditangan',
            'saldo'=>0,
            'saldo_awal'=>0,
            'jenis'=>'ditangan',
            'kode'=>'utama',
            'cabang_id'=>2
          ]
          ]);

        DB::table('kas')->insert([
          [
            'nama'=>'BNI Syariah 03**122',
            'keterangan'=>'No Rekening 0329758122',
            'saldo'=>0,
            'saldo_awal'=>0,
            'nama_bank'=>'BNI Syariah',
            'nomor_rekening'=>'0329758122',
            'jenis'=>'bank',
            'kode'=>'bank',
            'cabang_id'=>1
          ],
          [
            'nama'=>'BNI Syariah 09**883',
            'keterangan'=>'No Rekening 0905223883',
            'saldo'=>0,
            'saldo_awal'=>0,
            'nama_bank'=>'mandiri Syariah',
            'nomor_rekening'=>'0905223883',
            'jenis'=>'bank',
            'kode'=>'bank',
            'cabang_id'=>1
          ],
          [
            'nama'=>'Mandiri Syariah',
            'keterangan'=>'No Rekening xxxxxxxxxx',
            'saldo'=>0,
            'saldo_awal'=>0,
            'nama_bank'=>'Mandiri Syariah',
            'nomor_rekening'=>'0000000000000',
            'jenis'=>'bank',
            'kode'=>'bank',
            'cabang_id'=>2
          ]

        ]);

    }
}
