<?php

namespace App\Http\Controllers\Admin\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\PesananDetil;
use App\Pesanan;
use App\Produk;
use Carbon\Carbon;
use DB;
class Model1Controller extends Controller
{
  public function index()
  {

      $title = "Dashboard";
      $judul = "Dashboard";
      $judul_deskripsi = "";
      $deskripsi = "";

      $breadcrumbs = [
        ['link'=>'#','name'=>"Dashboard"],
      ];



$now = Carbon::createFromFormat('d-m-Y','8-11-2021');


$batas_awal = $now->copy()->startOfDay();
$batas_akhir = $now->copy()->endOfDay();


      $daftar_penjualan_tahunan = $this->penjualan_tahun_ini($now);

      $rupiah_tahunan = $daftar_penjualan_tahunan;
      $rupiah_tahunan_kilo = $daftar_penjualan_tahunan->pluck("total_kilo");
      $rupiah_tahunan_rupiah = $daftar_penjualan_tahunan->pluck("total_rupiah");
      $rupiah_tahunan_tahun = $daftar_penjualan_tahunan->pluck("tahun");
      $rupiah_tahunan_bulan = $daftar_penjualan_tahunan->pluck("bulan");


      $kilo_today = $this->kilo_hari_ini($now);
      $kilo_thismonth = $this->kilo_bulan_ini($now);
      $rupiah_today = $this->rupiah_hari_ini ($now);
      $rupiah_thismonth = $this->rupiah_bulan_ini ($now);


      return view('mimin.dashboard.model1',
      compact('title','judul','judul_deskripsi','deskripsi','breadcrumbs','kilo_today','kilo_thismonth','rupiah_today','rupiah_thismonth','rupiah_tahunan_kilo','rupiah_tahunan_bulan','rupiah_tahunan_tahun','rupiah_tahunan_rupiah')
      );

  }

  public function kilo_hari_ini ($now){

    $batas_awal = $now->copy()->startOfDay();
    $batas_akhir = $now->copy()->endOfDay();

    $total_omset = PesananDetil:: select(DB::raw('sum(pesanan_detil.kuantitas * produk.bobot) as total_kilo'))
          ->whereDate('pesanan.tanggal_pembayaran','>=',$batas_awal->format('Y-m-d'))
          ->whereDate('pesanan.tanggal_pembayaran','<=',$batas_akhir->format('Y-m-d'))
          ->where('pesanan.status','selesai')
          ->join('pesanan','pesanan.id','=','pesanan_detil.pesanan_id')
          ->join('produk','pesanan_detil.produk_id','=','produk.id')
          ->value('total_kilo');


    $batas_awal = $now->copy()->subDays(7)->startOfDay();
    $batas_akhir = $now->copy()->endOfDay();

    $daftar_omset = PesananDetil:: select(DB::raw('sum(pesanan_detil.kuantitas * produk.bobot)/1000 as total_kilo'))
    ->whereDate('pesanan.tanggal_pembayaran','>=',$batas_awal->format('Y-m-d'))
    ->whereDate('pesanan.tanggal_pembayaran','<=',$batas_akhir->format('Y-m-d'))
    ->where('pesanan.status','selesai')
    ->join('pesanan','pesanan.id','=','pesanan_detil.pesanan_id')
    ->join('produk','pesanan_detil.produk_id','=','produk.id')
    ->groupBy(DB::raw('date(pesanan.tanggal_pembayaran)'))
    ->pluck('total_kilo');

    $series = collect([
                  'name'=> 'Kilo',
                  'data' => $daftar_omset
                ]);

    $hasil = [
      'jumlah' => number_format($total_omset/1000,1,",","."),
      'series' => $series->toJson()
    ];

    return $hasil;
  }

  public function kilo_bulan_ini ($now){

    $batas_awal = $now->copy()->startOfMonth();
    $batas_akhir = $now->copy()->endOfMonth();

    $total_omset = PesananDetil:: select(DB::raw('sum(pesanan_detil.kuantitas * produk.bobot) as total_kilo'))
          ->whereDate('pesanan.tanggal_pembayaran','>=',$batas_awal->format('Y-m-d'))
          ->whereDate('pesanan.tanggal_pembayaran','<=',$batas_akhir->format('Y-m-d'))
          ->where('pesanan.status','selesai')
          ->join('pesanan','pesanan.id','=','pesanan_detil.pesanan_id')
          ->join('produk','pesanan_detil.produk_id','=','produk.id')
          ->value('total_kilo');

    $daftar_omset = PesananDetil:: select(DB::raw('sum(pesanan_detil.kuantitas * produk.bobot)/1000 as total_kilo'))
    ->whereDate('pesanan.tanggal_pembayaran','>=',$batas_awal->format('Y-m-d'))
    ->whereDate('pesanan.tanggal_pembayaran','<=',$batas_akhir->format('Y-m-d'))
    ->where('pesanan.status','selesai')
    ->join('pesanan','pesanan.id','=','pesanan_detil.pesanan_id')
    ->join('produk','pesanan_detil.produk_id','=','produk.id')
    ->groupBy(DB::raw('DATE (pesanan.tanggal_pembayaran)'))
    ->pluck('total_kilo');


    $series = collect([
                  'name'=> 'Kilo',
                  'data' => $daftar_omset
                ]);
    $hasil = [
      'jumlah' => number_format($total_omset/1000,1,",","."),
      'series' => $series->toJson()
    ];

    return $hasil;
  }

  public function rupiah_hari_ini ($now){

    $batas_awal = $now->copy()->startOfDay();
    $batas_akhir = $now->copy()->endOfDay();

    $total_omset = Pesanan:: select(DB::raw('sum(pesanan.nominal_pembelian)/1000 as total'))
          ->whereDate('pesanan.tanggal_pembayaran','>=',$batas_awal->format('Y-m-d'))
          ->whereDate('pesanan.tanggal_pembayaran','<=',$batas_akhir->format('Y-m-d'))
          ->where('pesanan.status','selesai')
      //    ->join('pesanan','pesanan.id','=','pesanan_detil.pesanan_id')
        //  ->join('produk','pesanan_detil.produk_id','=','produk.id')
          ->value('total');


    $batas_awal = $now->copy()->subDays(7)->startOfDay();
    $batas_akhir = $now->copy()->endOfDay();

    $daftar_omset = Pesanan:: select(DB::raw('sum(pesanan.nominal_pembelian) as total'))
    ->whereDate('pesanan.tanggal_pembayaran','>=',$batas_awal->format('Y-m-d'))
    ->whereDate('pesanan.tanggal_pembayaran','<=',$batas_akhir->format('Y-m-d'))
    ->where('pesanan.status','selesai')
  //  ->join('pesanan','pesanan.id','=','pesanan_detil.pesanan_id')
  //  ->join('produk','pesanan_detil.produk_id','=','produk.id')
    ->groupBy(DB::raw('date(pesanan.tanggal_pembayaran)'))
    ->pluck('total');


    $series = collect([
                  'name'=> 'Rupiah',
                  'data' => $daftar_omset
                ]);
    $hasil = [
      'jumlah' => $total_omset,
      'series' => $series->toJson()
    ];

    return $hasil;
  }

  public function rupiah_bulan_ini ($now){

    $batas_awal = $now->copy()->startOfMonth();
    $batas_akhir = $now->copy()->endOfMonth();

    $total_omset = Pesanan:: select(DB::raw('sum(pesanan.nominal_pembelian)/1000 as total'))
          ->whereDate('pesanan.tanggal_pembayaran','>=',$batas_awal->format('Y-m-d'))
          ->whereDate('pesanan.tanggal_pembayaran','<=',$batas_akhir->format('Y-m-d'))
          ->where('pesanan.status','selesai')
       //   ->join('pesanan','pesanan.id','=','pesanan_detil.pesanan_id')
        //  ->join('produk','pesanan_detil.produk_id','=','produk.id')
          ->value('total');

    $daftar_omset = Pesanan:: select(DB::raw('sum(pesanan.nominal_pembelian) as total'))
    ->whereDate('pesanan.tanggal_pembayaran','>=',$batas_awal->format('Y-m-d'))
    ->whereDate('pesanan.tanggal_pembayaran','<=',$batas_akhir->format('Y-m-d'))
    ->where('pesanan.status','selesai')
 //   ->join('pesanan','pesanan.id','=','pesanan_detil.pesanan_id')
 //   ->join('produk','pesanan_detil.produk_id','=','produk.id')
    ->groupBy(DB::raw('DATE (pesanan.tanggal_pembayaran)'))
    ->pluck('total');

    $series = collect([
                  'name'=> 'Rupiah',
                  'data' => $daftar_omset
                ]);
    $hasil = [
      'jumlah' => $total_omset,
      'series' => $series->toJson()
    ];

    return $hasil;
  }


  public function penjualan_tahun_ini ($now){

      $batas_awal = $now->copy()->startOfYear();
//      $batas_akhir = $now->copy()->addYear()->endOfMonth();
      $batas_akhir = $now->copy()->addYear()->endOfYear();

      $daftar_omset =
      PesananDetil:: select(DB::raw('sum(pesanan_detil.kuantitas * produk.bobot) as total_kilo, sum(pesanan_detil.kuantitas * pesanan_detil.harga) as total_rupiah, year(pesanan.tanggal_pembayaran) as tahun,month(pesanan.tanggal_pembayaran) as bulan'))
        ->whereDate('pesanan.tanggal_pembayaran','>=',$batas_awal->format('Y-m-d'))
        ->whereDate('pesanan.tanggal_pembayaran','<=',$batas_akhir->format('Y-m-d'))
        ->where('pesanan.status','selesai')
        ->join('pesanan','pesanan.id','=','pesanan_detil.pesanan_id')
        ->join('produk','pesanan_detil.produk_id','=','produk.id')
        ->groupBy(DB::raw('YEAR( pesanan.tanggal_pembayaran),MONTH(pesanan.tanggal_pembayaran)'))
        ->get();

      return $daftar_omset;

      $series = collect([
                    'name'=> 'Kilo',
                    'data' => $daftar_omset
                  ]);

      $hasil = [
        'jumlah' => number_format($total_omset/1000,1,",","."),
        'series' => $series->toJson()
      ];

      return $hasil;
  }
}

