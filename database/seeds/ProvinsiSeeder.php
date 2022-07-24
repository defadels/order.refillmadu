<?php

use Illuminate\Database\Seeder;
use App\Provinsi;
use App\Kecamatan;
use App\Kabupaten;


class ProvinsiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('provinsi')->insert([
          ['nama'=>'Sumatera Utara'],
          ['nama'=>'Sumatera Barat'],
          ['nama'=>'Sumatera Selatan']
        ]);

        DB::table('kabupaten')->insert([
          ['nama'=>'Langkat',
           'provinsi_id'=>1
          ],
          ['nama'=>'Padang',
          'provinsi_id'=>2
          ],
          ['nama'=>'Palembang',
           'provinsi_id'=>3
          ]
        ]);
        DB::table('kecamatan')->insert([
          ['nama'=>'Babalan',
           'kabupaten_id'=>1
          ],
          ['nama'=>'Kecamatan Padang',
          'kabupaten_id'=>2
          ],
          ['nama'=>'Kecamatan Palembang',
           'kabupaten_id'=>3
          ]
        ]);


    }
}
