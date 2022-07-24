<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
         $this->call(HakAksesSeeder::class);
         $this->call(PermisiSeeder::class);
         $this->call(UserSeeder::class);
         $this->call(ProvinsiSeeder::class);
         $this->call(CabangSeeder::class);
         $this->call(GudangSeeder::class);

         $this->call(KasSeeder::class);
         $this->call(MetodePembayaranSeeder::class);
         $this->call(MetodePengirimanSeeder::class);
         $this->call(KategoriPelangganSeeder::class);
         $this->call(PelangganSeeder::class);
      //   $this->call(DompetSeeder::class);
         $this->call(SuplierSeeder::class);
         $this->call(PengeluaranSeeder::class);
         $this->call(ProdukSeeder::class);

    }
}
