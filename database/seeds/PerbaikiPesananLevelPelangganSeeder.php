<?php

use Illuminate\Database\Seeder;
use App\Pesanan;
use App\PesananDetil;
use App\PesananUpline;
use App\Produk;

class PerbaikiPesananLevelPelangganSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

          $daftar_pesanan = DB::table('pesanan')->get();

          foreach ($daftar_pesanan as $pes){

// start

    $pesanan = Pesanan::findOrFail($pes->id);
    $daftar_detil_produk = collect([]);
    $daftar_detil_upline = collect([]);
    $pelanggan = $pesanan->pelanggan;

    $daftar_harga_pelanggan = $pelanggan->daftar_harga();

    switch ($pelanggan->level) {
      case  1:
      $daftar_harga_1 = $daftar_harga_pelanggan;
      break;

      case  2:
      $daftar_harga_2 = $daftar_harga_pelanggan;
      $daftar_harga_1 = $pelanggan->parent->daftar_harga();
      break;

      case 3:
      $daftar_harga_3 = $daftar_harga_pelanggan;
      $daftar_harga_2 = $pelanggan->parent->daftar_harga();
      $daftar_harga_1 = $pelanggan->parent->parent->daftar_harga();
      break;

    }

    $nominal_pembelian    = 0;
    $nominal_pembelian_1  = 0;
    $nominal_pembelian_2  = 0;
    $nominal_pembelian_3  = 0;

    $harga_1 = null;
    $harga_2 = null;
    $harga_3 = null;


    $daftar_detil = $pesanan->daftar_detil;

    foreach ($daftar_detil as $detil) {

      $kuantitas_produk = $detil->kuantitas;
      $produk = Produk::findOrFail($detil->produk_id);
      $sub_total_pelanggan = $kuantitas_produk *  $daftar_harga_pelanggan[$produk->id];
      $sub_total_harga_1 = 0;
      $sub_total_harga_2 = 0;
      $sub_total_harga_3 = 0;

      switch ($pelanggan->level) {
        case 1:
          $sub_total_harga_1 = $kuantitas_produk *  $daftar_harga_1[$produk->id];
          $harga_1 =  $daftar_harga_1[$produk->id];
          break;

        case 2:
          $sub_total_harga_1 = $kuantitas_produk *  $daftar_harga_1[$produk->id];
          $harga_1 =  $daftar_harga_1[$produk->id];
          $sub_total_harga_2 = $kuantitas_produk *  $daftar_harga_2[$produk->id];
          $harga_2 =  $daftar_harga_2[$produk->id];
          break;

        case 3:
          $sub_total_harga_1 = $kuantitas_produk *  $daftar_harga_1[$produk->id];
          $harga_1 =  $daftar_harga_1[$produk->id];
          $sub_total_harga_2 = $kuantitas_produk *  $daftar_harga_2[$produk->id];
          $harga_2 =  $daftar_harga_2[$produk->id];
          $sub_total_harga_3 = $kuantitas_produk *  $daftar_harga_3[$produk->id];
          $harga_3 =  $daftar_harga_3[$produk->id];
          break;

      }

      // bikin nominal pemblian di setiap level harga jangan lupa
      $nominal_pembelian    += $sub_total_pelanggan;
      $nominal_pembelian_1  += $sub_total_harga_1;
      $nominal_pembelian_2  += $sub_total_harga_2;
      $nominal_pembelian_3  += $sub_total_harga_3;

      if ($kuantitas_produk > 0) {
        $daftar_detil_produk->push([
          'produk_id' => $produk->id,
          'kuantitas' => $kuantitas_produk,
          'harga' => $daftar_harga_pelanggan [$produk->id], // ganti

          'harga_1' => $harga_1, // ganti
          'harga_2' => $harga_2, // ganti
          'harga_3' => $harga_3, // ganti

          'point'=>$produk->poin

        ]);
      }
    }


    // daftar uppline pesanan

    switch ($pelanggan->level) {
      case 1:
      break;
      case 2:
        $daftar_detil_upline->push([
          'pelanggan_id' => $pelanggan->parent->id,
          'pelanggan_level' => 1,
          'nominal_pembelian' => $nominal_pembelian_1,
        ]);
      break;
      case 3:
        $daftar_detil_upline->push([
          'pelanggan_id' => $pelanggan->parent->parent->id,
          'pelanggan_level' => 1,
          'nominal_pembelian' => $nominal_pembelian_1,
        ]);
        $daftar_detil_upline->push([
          'pelanggan_id' => $pelanggan->parent->id,
          'pelanggan_level' => 2,
          'nominal_pembelian' => $nominal_pembelian_2,
        ]);
      break;
      case 4:
        $daftar_detil_upline->push([
          'pelanggan_id' => $pelanggan->parent->parent->parent->id,
          'pelanggan_level' => 1,
          'nominal_pembelian' => $nominal_pembelian_1,
        ]);
        $daftar_detil_upline->push([
          'pelanggan_id' => $pelanggan->parent->parent->id,
          'pelanggan_level' => 2,
          'nominal_pembelian' => $nominal_pembelian_2,
        ]);
        $daftar_detil_upline->push([
          'pelanggan_id' => $pelanggan->parent->id,
          'pelanggan_level' => 3,
          'nominal_pembelian' => $nominal_pembelian_3,
        ]);
      break;
    }



    DB::transaction(function () use ($pesanan,$daftar_detil_produk,$daftar_detil_upline) {

      $pesanan->daftar_detil()->delete();
      $pesanan->daftar_detil()->createMany($daftar_detil_produk);

      $pesanan->daftar_upline()->delete();
      $pesanan->daftar_upline()->createMany($daftar_detil_upline);
    });



// end





            echo "pesanan ". $pesanan->id;
            echo "\n";

          }

    }
}
