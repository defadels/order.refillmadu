<?php

namespace App\Http\Controllers\Admin\Laporan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Pesanan;
use App\PesananUpline;
use App\Produk;
use App\PesananDetil;
use Carbon\Carbon;
use App\Cabang;
use App\User;
use App\KategoriPelanggan;
use Session;
use DB;
use App\Pelanggan;

class PenjualanController extends Controller
{  function __construct()
  {
       $this->middleware('permission:laporan.penjualan', ['except' => []]);
  }

  public function index()
  {
    $judul = "Laporan Penjualan";
    $breadcrumbs = [
      ['link' => '#', 'name' => "Laporan"],
      ['link' => '#', 'name' => "Penjualan"],
    ];
    return view(
      'mimin.laporan.penjualan.index',
      compact('judul', 'breadcrumbs')
    );
  }

  public function laporan_1(Request $req)
  {
    $judul = "Laporan Penjualan";
    $breadcrumbs = [
      ['link' => '#', 'name' => "Laporan"],
      ['link' => '#', 'name' => "Penjualan"],
      ['link' => '#', 'name' => "Per Produk"],
    ];

    $start_date = Carbon::now()->startOfMonth();
    $end_date = $start_date->copy()->endOfMonth();

    if ($req->has('range')){
      $range = $req->range;
      $tanggal = explode(" - ", $range);

      try
      {
         $start_date = Carbon::createFromFormat('d/m/Y', $tanggal[0]);
         $end_date = Carbon::createFromFormat('d/m/Y', $tanggal[1]);
      }
      catch (\Throwable $t)
      {

          $start_date = Carbon::now()->startOfMonth();
          $end_date = $start_date->copy()->endOfMonth();

      }
    }

    $daftar_cabang = Cabang::pluck("nama","id");
    $daftar_cabang->prepend("Semua Cabang",0);
    $cabang = 0;
    $resi = "";
    if ($req->has("cabang")){
      $cabang = $req->cabang;
    }

    $daftar_pelanggan = User::skip(9)->take(5)->pluck("nama","id");
    $daftar_pelanggan->prepend("Semua Pelanggan",0);

    $pelanggan = 0;
    $resi = "";
    if ($req->has("pelanggan")){
      $pelanggan = $req->pelanggan;
      $daftar_pelanggan = User::where('id',$pelanggan)->pluck("nama","id");
      $daftar_pelanggan->prepend("Semua Pelanggan",0);
    }


    $sum_pesanan = PesananDetil::
                      select(DB::raw('sum(pesanan_detil.kuantitas) as total_kuantitas,
                      round(avg(pesanan_detil.harga)) as harga_penjualan,
                      sum(pesanan_detil.kuantitas*pesanan_detil.harga) as total_penjualan,pesanan_detil.produk_id'))
                      ->whereDate('pesanan.tanggal_pembayaran','>=',$start_date->format('Y-m-d'))
                      ->whereDate('pesanan.tanggal_pembayaran','<=',$end_date->format('Y-m-d'))
                      ->join('pesanan','pesanan.id','=','pesanan_detil.pesanan_id')
                      ->groupBy('pesanan_detil.produk_id');

    if ($cabang != 0){
           $sum_pesanan =  $sum_pesanan->where('pesanan.cabang_id','=',$cabang);
    }

    if ($pelanggan != 0){
      $sum_pesanan = $sum_pesanan->leftjoin('pesanan_upline', 'pesanan.id', '=', 'pesanan_upline.pesanan_id')
                                  ->where(function($query) use ($pelanggan)
                                  {
                                      $query->where('pesanan.pelanggan_id',$pelanggan)
                                            ->orWhere('pesanan_upline.pelanggan_id',$pelanggan);
                                  });
    }

    $daftar_pesanan = Produk::
              joinSub($sum_pesanan, 'sum_pesanan', function ($join) {
                  $join->on('produk.id', '=', 'sum_pesanan.produk_id');
              })->paginate(10);

    return view(
      'mimin.laporan.penjualan.laporan1',
      compact('judul', 'breadcrumbs', 'daftar_pesanan','start_date','end_date','daftar_cabang','cabang','pelanggan','daftar_pelanggan')
    );
  }

  public function laporan_2(Request $req)
  {
    $judul = "Laporan Penjualan";
    $breadcrumbs = [
      ['link' => '#', 'name' => "Laporan"],
      ['link' => '#', 'name' => "Penjualan"],
      ['link' => '#', 'name' => "Per Produk Per Tanggal"],
    ];

    $start_date = Carbon::now()->startOfMonth();
    $end_date = $start_date->copy()->endOfMonth();

    if ($req->has('range')){
      $range = $req->range;
      $tanggal = explode(" - ", $range);

      try
      {
         $start_date = Carbon::createFromFormat('d/m/Y', $tanggal[0]);
         $end_date = Carbon::createFromFormat('d/m/Y', $tanggal[1]);
      }
      catch (\Throwable $t)
      {

          $start_date = Carbon::now()->startOfMonth();
          $end_date = $start_date->copy()->endOfMonth();

      }
    }


    $daftar_cabang = Cabang::pluck("nama","id");
    $daftar_cabang->prepend("Semua Cabang",0);
    $cabang = 0;
    $resi = "";
    if ($req->has("cabang")){
      $cabang = $req->cabang;
    }

    $daftar_pelanggan = User::skip(9)->take(5)->pluck("nama","id");
    $daftar_pelanggan->prepend("Semua Pelanggan",0);

    $pelanggan = 0;
    $resi = "";
    if ($req->has("pelanggan")){
      $pelanggan = $req->pelanggan;
      $daftar_pelanggan = User::where('id',$pelanggan)->pluck("nama","id");
      $daftar_pelanggan->prepend("Semua Pelanggan",0);
    }


    $sum_pesanan = PesananDetil::
                      select(DB::raw('sum(pesanan_detil.kuantitas) as total_kuantitas,
                      round(avg(pesanan_detil.harga)) as harga_penjualan,
                      sum(pesanan_detil.kuantitas*pesanan_detil.harga) as total_penjualan,
                      pesanan_detil.produk_id,
                      date(pesanan.tanggal_pembayaran) as tanggal_pembayaran_date'))
                      ->whereDate('pesanan.tanggal_pembayaran','>=',$start_date->format('Y-m-d'))
                      ->whereDate('pesanan.tanggal_pembayaran','<=',$end_date->format('Y-m-d'))
                      ->join('pesanan','pesanan.id','=','pesanan_detil.pesanan_id')
                      ->groupBy('tanggal_pembayaran_date','pesanan_detil.produk_id');


    if ($cabang != 0){
      $sum_pesanan =  $sum_pesanan->where('pesanan.cabang_id','=',$cabang);
    }

    if ($pelanggan != 0){
      $sum_pesanan = $sum_pesanan->leftjoin('pesanan_upline', 'pesanan.id', '=', 'pesanan_upline.pesanan_id')
                                  ->where(function($query) use ($pelanggan)
                                  {
                                      $query->where('pesanan.pelanggan_id',$pelanggan)
                                            ->orWhere('pesanan_upline.pelanggan_id',$pelanggan);
                                  });
    }

    $daftar_pesanan = Produk::
              joinSub($sum_pesanan, 'sum_pesanan', function ($join) {
                  $join->on('produk.id', '=', 'sum_pesanan.produk_id');
              })->paginate(10);


    return view(
      'mimin.laporan.penjualan.laporan2',
      compact('judul', 'breadcrumbs', 'daftar_pesanan','start_date','end_date','daftar_cabang','cabang','daftar_pelanggan','pelanggan')
    );
  }

  public function laporan_3(Request $req)
  {
    $judul = "Laporan Penjualan";
    $breadcrumbs = [
      ['link' => '#', 'name' => "Laporan"],
      ['link' => '#', 'name' => "Penjualan"],
      ['link' => '#', 'name' => "Per Transaksi"],
    ];

    $start_date = Carbon::now()->startOfMonth();
    $end_date = $start_date->copy()->endOfMonth();

    if ($req->has('range')){
      $range = $req->range;
      $tanggal = explode(" - ", $range);

      try
      {
         $start_date = Carbon::createFromFormat('d/m/Y', $tanggal[0]);
         $end_date = Carbon::createFromFormat('d/m/Y', $tanggal[1]);
      }
      catch (\Throwable $t)
      {

          $start_date = Carbon::now()->startOfMonth();
          $end_date = $start_date->copy()->endOfMonth();

      }
    }


    $daftar_cabang = Cabang::pluck("nama","id");
    $daftar_cabang->prepend("Semua Cabang",0);
    $cabang = 0;
    $resi = "";
    if ($req->has("cabang")){
      $cabang = $req->cabang;
    }


    $daftar_pelanggan = User::skip(9)->take(5)->pluck("nama","id");
    $daftar_pelanggan->prepend("Semua Pelanggan",0);

    $pelanggan = 0;
    $resi = "";
    if ($req->has("pelanggan")){
      $pelanggan = $req->pelanggan;
      $daftar_pelanggan = User::where('id',$pelanggan)->pluck("nama","id");
      $daftar_pelanggan->prepend("Semua Pelanggan",0);
    }


    $daftar_pesanan = Pesanan::selesai()->with('pelanggan', 'metode_pembayaran')
      ->whereDate('tanggal_pembayaran','>=',$start_date->format('Y-m-d'))
      ->whereDate('tanggal_pembayaran','<=',$end_date->format('Y-m-d'))
      ->orderBy('created_at', 'desc');

      if ($cabang != 0){
        $daftar_pesanan =  $daftar_pesanan->where('pesanan.cabang_id','=',$cabang);
      }

      if ($pelanggan != 0){
        $daftar_pesanan = $daftar_pesanan->leftjoin('pesanan_upline', 'pesanan.id', '=', 'pesanan_upline.pesanan_id')
                                    ->where(function($query) use ($pelanggan)
                                    {
                                        $query->where('pesanan.pelanggan_id',$pelanggan)
                                              ->orWhere('pesanan_upline.pelanggan_id',$pelanggan);
                                    });
      }


    $daftar_pesanan = $daftar_pesanan->paginate(10);


    return view(
      'mimin.laporan.penjualan.laporan3',
      compact('judul', 'breadcrumbs', 'daftar_pesanan','start_date','end_date','daftar_cabang','cabang','daftar_pelanggan','pelanggan')
    );
  }


  public function laporan_4 (Request $req)
  {
    $judul = "Laporan Penjualan Per Pelanggan";
    $breadcrumbs = [
      ['link' => '#', 'name' => "Laporan"],
      ['link' => '#', 'name' => "Penjualan"],
      ['link' => '#', 'name' => "Per Pelanggan"],
    ];

    $start_date = Carbon::now()->startOfMonth();
    $end_date = $start_date->copy()->endOfMonth();

    if ($req->has('range')){
      $range = $req->range;
      $tanggal = explode(" - ", $range);

      try
      {
         $start_date = Carbon::createFromFormat('d/m/Y', $tanggal[0]);
         $end_date = Carbon::createFromFormat('d/m/Y', $tanggal[1]);
      }
      catch (\Throwable $t)
      {

          $start_date = Carbon::now()->startOfMonth();
          $end_date = $start_date->copy()->endOfMonth();

      }
    }


    $daftar_cabang = Cabang::pluck("nama","id");
    $daftar_cabang->prepend("Semua Cabang",0);
    $cabang = 0;
    $resi = "";
    if ($req->has("cabang")){
      $cabang = $req->cabang;
    }

    $daftar_kategori = KategoriPelanggan::pluck("nama","id");
    $daftar_kategori->prepend("Semua Kategori",0);
    $kategori = 0;
    $resi = "";
    if ($req->has("kategori")){
      $kategori = $req->kategori;
    }

    $cari = "";
    if ($req->has("cari")){
      $cari = $req->cari;
    }

    $all_pesanan = Pesanan::select('pesanan_upline.pelanggan_id as ortu_id','pesanan.*')
    ->join('pesanan_upline','pesanan_upline.pesanan_id','=','pesanan.id');


     $total_omset_kilo = PesananDetil::
     select(DB::raw('sum(pesanan_detil.kuantitas*produk.bobot) as total_omset_kilo'))
    ->whereDate('pesanan.tanggal_pembayaran','>=',$start_date->format('Y-m-d'))
    ->whereDate('pesanan.tanggal_pembayaran','<=',$end_date->format('Y-m-d'))
    ->join('pesanan','pesanan.id','=','pesanan_detil.pesanan_id')
    ->join('produk','pesanan_detil.produk_id','=','produk.id');




    $sum_pesanan_downline = PesananDetil::
    select(DB::raw('sum(pesanan_detil.kuantitas*produk.bobot) as omset_kilo_downline,
        sum(pesanan_detil.kuantitas*pesanan_detil.harga) as total_penjualan_downline,
        pesanan.ortu_id as pelanggan_id'))
    ->whereDate('pesanan.tanggal_pembayaran','>=',$start_date->format('Y-m-d'))
    ->whereDate('pesanan.tanggal_pembayaran','<=',$end_date->format('Y-m-d'))
    ->joinSub($all_pesanan, 'pesanan', function ($join) {
      $join->on('pesanan.id', '=', 'pesanan_detil.pesanan_id');
      })
    //    ->join('pesanan','pesanan.id','=','pesanan_detil.pesanan_id')
    ->join('produk','pesanan_detil.produk_id','=','produk.id')
    ->groupBy('pesanan.ortu_id');


    $sum_pesanan = PesananDetil::
    select(DB::raw('sum(pesanan_detil.kuantitas*produk.bobot) as omset_kilo,
        sum(pesanan_detil.kuantitas*pesanan_detil.harga) as total_penjualan,
        pesanan.pelanggan_id'))
    ->whereDate('pesanan.tanggal_pembayaran','>=',$start_date->format('Y-m-d'))
    ->whereDate('pesanan.tanggal_pembayaran','<=',$end_date->format('Y-m-d'))
    ->join('pesanan','pesanan.id','=','pesanan_detil.pesanan_id')
    ->join('produk','pesanan_detil.produk_id','=','produk.id')
    ->groupBy('pesanan.pelanggan_id');

    if ($cabang != 0){
      $sum_pesanan =  $sum_pesanan->where('pesanan.cabang_id','=',$cabang);
      $sum_pesanan_downline =  $sum_pesanan_downline->where('pesanan.cabang_id','=',$cabang);
      $total_omset_kilo = $total_omset_kilo->where('pesanan.cabang_id','=',$cabang)
      ->value('total_omset_kilo');
    } else {
      $total_omset_kilo = $total_omset_kilo
      ->value('total_omset_kilo');
    }

    $daftar_pelanggan = Pelanggan::where('id',"!=",1);

    if ($kategori != 0){
      $daftar_pelanggan = $daftar_pelanggan->where('kategori_id',$kategori);
    }

    if ($cari != ""){
      $daftar_pelanggan = $daftar_pelanggan->where(function($query) use ($cari)
                                                  {
                                                      $query->where('users.nama','like','%'.$cari.'%')
                                                            ->orWhere('users.kode',$cari);
                                                  });
    }

    if (($kategori == 0 && $cari == "")){
      $daftar_pelanggan = $daftar_pelanggan->whereNull('parent_id');
    }

    $daftar_pelanggan = $daftar_pelanggan
    ->leftJoinSub($sum_pesanan, 'sum_pesanan', function ($join) {
    $join->on('users.id', '=', 'sum_pesanan.pelanggan_id');
    })
    ->leftJoinSub($sum_pesanan_downline, 'sum_pesanan_downline', function ($join) {
      $join->on('users.id', '=', 'sum_pesanan_downline.pelanggan_id');
      })->paginate(10);

    return view(
    'mimin.laporan.penjualan.laporan4',
    compact('judul', 'breadcrumbs', 'daftar_pelanggan','start_date','end_date','daftar_cabang','cabang','daftar_kategori','kategori','cari','total_omset_kilo')
    );


  }

  public function laporan_5 (Request $req)
  {

    $judul = "Laporan Penjualan";
    $breadcrumbs = [
      ['link' => '#', 'name' => "Laporan"],
      ['link' => '#', 'name' => "Penjualan"],
      ['link' => '#', 'name' => "Per 3 Bulan"],
    ];

    $daftar_bulan = [
        "1"=>"Januari",
        "2"=>"Februari",
        "3"=>"Maret",
        "4"=>"April",
        "5"=>"Mei",
        "6"=>"Juni",
        "7"=>"Juli",
        "8"=>"Agustus",
        "9"=>"September",
        "10"=>"Oktober",
        "11"=>"November",
        "12"=>"Desember"];

    $tahun = Carbon::now()->format("Y");
    $daftar_tahun = collect([]);


    for ($i=1;$i <= 5; $i++){
      $daftar_tahun = $daftar_tahun->put($tahun-5+$i,$tahun-5+$i);
    }

    $bulan = Carbon::now()->format("m");

    if ($req->has('bulan')){
      $bulan = $req->bulan;
    }
    if ($req->has('tahun')){
      $tahun = $req->tahun;
    }

    $start_date = Carbon::createFromFormat("m Y",$bulan." ".$tahun)->startOfMonth();
    $end_date = $start_date->copy()->endOfMonth();

    $sebulan_lalu_start = $start_date->copy()->subMonth(1)->startOfMonth();
    $sebulan_lalu_end = $sebulan_lalu_start->copy()->endOfMonth();

    $duabulan_lalu_start = $start_date->copy()->subMonth(2)->startOfMonth();
    $duabulan_lalu_end = $duabulan_lalu_start->copy()->endOfMonth();

    $daftar_cabang = Cabang::pluck("nama","id");
    $daftar_cabang->prepend("Semua Cabang",0);
    $cabang = 0;
    $resi = "";
    if ($req->has("cabang")){
      $cabang = $req->cabang;
    }

    $daftar_kategori = KategoriPelanggan::pluck("nama","id");
    $daftar_kategori->prepend("Semua Kategori",0);
    $kategori = 0;
    $resi = "";
    if ($req->has("kategori")){
      $kategori = $req->kategori;
    }

    $cari = "";
    if ($req->has("cari")){
      $cari = $req->cari;
    }

    $all_pesanan = Pesanan::select('pesanan_upline.pelanggan_id as ortu_id','pesanan.*')
    ->join('pesanan_upline','pesanan_upline.pesanan_id','=','pesanan.id');


     $total_omset_kilo = PesananDetil::
     select(DB::raw('sum(pesanan_detil.kuantitas*produk.bobot) as total_omset_kilo'))
    ->whereDate('pesanan.tanggal_pembayaran','>=',$start_date->format('Y-m-d'))
    ->whereDate('pesanan.tanggal_pembayaran','<=',$end_date->format('Y-m-d'))
    ->join('pesanan','pesanan.id','=','pesanan_detil.pesanan_id')
    ->join('produk','pesanan_detil.produk_id','=','produk.id');




    $sum_pesanan_downline = PesananDetil::
    select(DB::raw('sum(pesanan_detil.kuantitas*produk.bobot) as omset_kilo_downline,
        sum(pesanan_detil.kuantitas*pesanan_detil.harga) as total_penjualan_downline,
        pesanan.ortu_id as pelanggan_id'))
    ->whereDate('pesanan.tanggal_pembayaran','>=',$start_date->format('Y-m-d'))
    ->whereDate('pesanan.tanggal_pembayaran','<=',$end_date->format('Y-m-d'))
    ->joinSub($all_pesanan, 'pesanan', function ($join) {
      $join->on('pesanan.id', '=', 'pesanan_detil.pesanan_id');
      })
    //    ->join('pesanan','pesanan.id','=','pesanan_detil.pesanan_id')
    ->join('produk','pesanan_detil.produk_id','=','produk.id')
    ->groupBy('pesanan.ortu_id');


    $sum_pesanan = PesananDetil::
    select(DB::raw('sum(pesanan_detil.kuantitas*produk.bobot) as omset_kilo,
        sum(pesanan_detil.kuantitas*pesanan_detil.harga) as total_penjualan,
        pesanan.pelanggan_id'))
    ->whereDate('pesanan.tanggal_pembayaran','>=',$start_date->format('Y-m-d'))
    ->whereDate('pesanan.tanggal_pembayaran','<=',$end_date->format('Y-m-d'))
    ->join('pesanan','pesanan.id','=','pesanan_detil.pesanan_id')
    ->join('produk','pesanan_detil.produk_id','=','produk.id')
    ->groupBy('pesanan.pelanggan_id');


    $sum_pesanan_downline_1bln_lalu = PesananDetil::
    select(DB::raw('sum(pesanan_detil.kuantitas*produk.bobot) as omset_kilo_downline_1bln_lalu,
        sum(pesanan_detil.kuantitas*pesanan_detil.harga) as total_penjualan_downline_1bln_lalu,
        pesanan.ortu_id as pelanggan_id'))
    ->whereDate('pesanan.tanggal_pembayaran','>=',$sebulan_lalu_start->format('Y-m-d'))
    ->whereDate('pesanan.tanggal_pembayaran','<=',$sebulan_lalu_end->format('Y-m-d'))
    ->joinSub($all_pesanan, 'pesanan', function ($join) {
      $join->on('pesanan.id', '=', 'pesanan_detil.pesanan_id');
      })
    //    ->join('pesanan','pesanan.id','=','pesanan_detil.pesanan_id')
    ->join('produk','pesanan_detil.produk_id','=','produk.id')
    ->groupBy('pesanan.ortu_id');


    $sum_pesanan_1bln_lalu = PesananDetil::
    select(DB::raw('sum(pesanan_detil.kuantitas*produk.bobot) as omset_kilo_1bln_lalu,
        sum(pesanan_detil.kuantitas*pesanan_detil.harga) as total_penjualan_1bln_lalu,
        pesanan.pelanggan_id'))
    ->whereDate('pesanan.tanggal_pembayaran','>=',$sebulan_lalu_start->format('Y-m-d'))
    ->whereDate('pesanan.tanggal_pembayaran','<=',$sebulan_lalu_end->format('Y-m-d'))
    ->join('pesanan','pesanan.id','=','pesanan_detil.pesanan_id')
    ->join('produk','pesanan_detil.produk_id','=','produk.id')
    ->groupBy('pesanan.pelanggan_id');


    $sum_pesanan_downline_2bln_lalu = PesananDetil::
    select(DB::raw('sum(pesanan_detil.kuantitas*produk.bobot) as omset_kilo_downline_2bln_lalu,
        sum(pesanan_detil.kuantitas*pesanan_detil.harga) as total_penjualan_downline_2bln_lalu,
        pesanan.ortu_id as pelanggan_id'))
    ->whereDate('pesanan.tanggal_pembayaran','>=',$duabulan_lalu_start->format('Y-m-d'))
    ->whereDate('pesanan.tanggal_pembayaran','<=',$duabulan_lalu_end->format('Y-m-d'))
    ->joinSub($all_pesanan, 'pesanan', function ($join) {
      $join->on('pesanan.id', '=', 'pesanan_detil.pesanan_id');
      })
    //    ->join('pesanan','pesanan.id','=','pesanan_detil.pesanan_id')
    ->join('produk','pesanan_detil.produk_id','=','produk.id')
    ->groupBy('pesanan.ortu_id');


    $sum_pesanan_2bln_lalu = PesananDetil::
    select(DB::raw('sum(pesanan_detil.kuantitas*produk.bobot) as omset_kilo_2bln_lalu,
        sum(pesanan_detil.kuantitas*pesanan_detil.harga) as total_penjualan_2bln_lalu,
        pesanan.pelanggan_id'))
    ->whereDate('pesanan.tanggal_pembayaran','>=',$duabulan_lalu_start->format('Y-m-d'))
    ->whereDate('pesanan.tanggal_pembayaran','<=',$duabulan_lalu_end->format('Y-m-d'))
    ->join('pesanan','pesanan.id','=','pesanan_detil.pesanan_id')
    ->join('produk','pesanan_detil.produk_id','=','produk.id')
    ->groupBy('pesanan.pelanggan_id');



    if ($cabang != 0){
      $sum_pesanan =  $sum_pesanan->where('pesanan.cabang_id','=',$cabang);
      $sum_pesanan_downline =  $sum_pesanan_downline->where('pesanan.cabang_id','=',$cabang);
      $total_omset_kilo = $total_omset_kilo->where('pesanan.cabang_id','=',$cabang)
      ->value('total_omset_kilo');
    } else {
      $total_omset_kilo = $total_omset_kilo
      ->value('total_omset_kilo');
    }

    $daftar_pelanggan = Pelanggan::where('id',"!=",1);

    if ($kategori != 0){
      $daftar_pelanggan = $daftar_pelanggan->where('kategori_id',$kategori);
    }

    if ($cari != ""){
      $daftar_pelanggan = $daftar_pelanggan->where(function($query) use ($cari)
                                                  {
                                                      $query->where('users.nama','like','%'.$cari.'%')
                                                            ->orWhere('users.kode',$cari);
                                                  });
    }

    if (($kategori == 0 && $cari == "")){
      $daftar_pelanggan = $daftar_pelanggan->whereNull('parent_id');
    }

    $daftar_pelanggan = $daftar_pelanggan
    ->leftJoinSub($sum_pesanan, 'sum_pesanan', function ($join) {
    $join->on('users.id', '=', 'sum_pesanan.pelanggan_id');
    })
    ->leftJoinSub($sum_pesanan_downline, 'sum_pesanan_downline', function ($join) {
      $join->on('users.id', '=', 'sum_pesanan_downline.pelanggan_id');
      })

    ->leftJoinSub($sum_pesanan_1bln_lalu, 'sum_pesanan_1bln_lalu', function ($join) {
        $join->on('users.id', '=', 'sum_pesanan_1bln_lalu.pelanggan_id');
        })
    ->leftJoinSub($sum_pesanan_downline_1bln_lalu, 'sum_pesanan_downline_1bln_lalu', function ($join) {
          $join->on('users.id', '=', 'sum_pesanan_downline_1bln_lalu.pelanggan_id');
          })

    ->leftJoinSub($sum_pesanan_2bln_lalu, 'sum_pesanan_2bln_lalu', function ($join) {
            $join->on('users.id', '=', 'sum_pesanan_2bln_lalu.pelanggan_id');
          })
    ->leftJoinSub($sum_pesanan_downline_2bln_lalu, 'sum_pesanan_downline_2bln_lalu', function ($join) {
           $join->on('users.id', '=', 'sum_pesanan_downline_2bln_lalu.pelanggan_id');
      })->paginate(10);

    return view(
    'mimin.laporan.penjualan.laporan5',
    compact('judul', 'breadcrumbs', 'daftar_pelanggan','daftar_cabang','cabang','daftar_kategori','kategori','cari','total_omset_kilo','daftar_bulan','daftar_tahun','bulan','tahun','duabulan_lalu_start','sebulan_lalu_start','start_date')
    );


  }


  public function cari_pelanggan(Request $req)
      {

        $key = $req->cari;
        if ($req->has("cari") && $req->cari != "") {

          $daftar_kegiatan = User::with('parent','kategori','distributor')->where(function ($query) use ($key) {
            $query->where('nama', 'like', '%' . $key . '%')
                  ->orWhere('email', $key)
                  ->orWhere('nomor_hp', $key);
          })->paginate(5);
          $pilihan = collect($daftar_kegiatan->toArray()['data']);
          $pilihan->prepend(['id'=>0,'nama'=>"Semua Pelanggan",'email'=>'']);

        } else {
          $daftar_kegiatan = User::with('parent','kategori','distributor')->paginate(5);
          $pilihan = collect($daftar_kegiatan->toArray()['data']);
          $pilihan->prepend(['id'=>0,'nama'=>"Semua Pelanggan",'email'=>'']);
        }



        $results = array(
          "results" => $pilihan->toArray(),
          "pagination" => array(
            "more" => $daftar_kegiatan->hasMorePages()
          )
        );

        return response()->json($results);
      }



}
