<?php

namespace App\Http\Controllers\Admin\Laporan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\RiwayatStokProduk;
use Carbon\Carbon;
use App\Produk;
use DB;
use App\Gudang;

class StokController extends Controller
{
  function __construct()
  {
       $this->middleware('permission:laporan.stok', ['except' => []]);
  }

  public function index(Request $req){

      $judul = "Laporan Stok";

      $breadcrumbs = [
        ['link' => '#', 'name' => "Laporan"],
        ['link' => '#', 'name' => "Stok"],
      ];

      $start_date = Carbon::now()->startOfMonth();
      $end_date = $start_date->copy()->endOfMonth();
      $daftar_gudang = Gudang::pluck("nama","id");
      $daftar_gudang->prepend("Semua Gudang","semua");

      $gudang_id = "semua";

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

      if ($req->has('gudang_id')){
          $gudang_id = $req->gudang_id;
      }

      $jumlah_keluar = RiwayatStokProduk::
                      select(DB::raw('sum(kuantitas) as jumlah_keluar,
                      produk_id'))
                      ->whereDate('created_at','>=',$start_date->format('Y-m-d'))
                      ->whereDate('created_at','<=',$end_date->format('Y-m-d'))
                      ->where('keluar_masuk','keluar')
                      ->groupBy('produk_id');


      $jumlah_masuk = RiwayatStokProduk::
                      select(DB::raw('sum(kuantitas) as jumlah_masuk,
                      produk_id'))
                      ->whereDate('created_at','>=',$start_date->format('Y-m-d'))
                      ->whereDate('created_at','<=',$end_date->format('Y-m-d'))
                      ->where('keluar_masuk','masuk')
                      ->groupBy('produk_id');

      $id_stok_awal = RiwayatStokProduk::
                      select(DB::raw('min(id) as ids'))
                      ->whereDate('created_at','>=',$start_date->format('Y-m-d'))
                      ->whereDate('created_at','<=',$end_date->format('Y-m-d'))
                      ->groupBy('produk_id')
                      ->pluck('ids');

      $saldo_awal = RiwayatStokProduk::
                      select('produk_id','stok_awal')
                      ->whereIn('id',$id_stok_awal);

      if ($gudang_id  != "semua"){
            $jumlah_keluar  = $jumlah_keluar  ->where('gudang_id',$gudang_id);
            $jumlah_masuk   = $jumlah_masuk   ->where('gudang_id',$gudang_id);
            $id_stok_awal   = $id_stok_awal   ->where('gudang_id',$gudang_id);
            $saldo_awal     = $saldo_awal     ->where('gudang_id',$gudang_id);
      }

      $daftar_stok = Produk::
                select("produk.nama",DB::raw("IFNULL(jumlah_masuk.jumlah_masuk, 0) as jumlah_masuk"),DB::raw("IFNULL(jumlah_keluar.jumlah_keluar,0) as jumlah_keluar"),
                DB::raw("IFNULL(saldo_awal.stok_awal, 0) as stok_awal"),DB::raw('(IFNULL(saldo_awal.stok_awal, 0) + IFNULL(jumlah_masuk.jumlah_masuk, 0)-IFNULL(jumlah_keluar.jumlah_keluar, 0)) as saldo'))
                ->leftJoinSub($jumlah_masuk, 'jumlah_masuk', function ($join) {
                    $join->on('produk.id', '=', 'jumlah_masuk.produk_id');
                })
                ->leftJoinSub($jumlah_keluar, 'jumlah_keluar', function ($join) {
                    $join->on('produk.id', '=', 'jumlah_keluar.produk_id');
                })
                ->joinSub($saldo_awal, 'saldo_awal', function ($join) {
                    $join->on('produk.id', '=', 'saldo_awal.produk_id');
                })
                ->paginate(10);

      return view(
        'mimin.laporan.stok.index',
        compact('judul', 'breadcrumbs','daftar_stok','start_date','end_date','gudang_id','daftar_gudang')
      );

    }
}
