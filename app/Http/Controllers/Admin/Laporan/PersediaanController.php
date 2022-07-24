<?php

namespace App\Http\Controllers\Admin\Laporan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\RiwayatStokProduk;
use Carbon\Carbon;
use App\Produk;
use DB;

class PersediaanController extends Controller
{
  function __construct()
  {
       $this->middleware('permission:laporan.persediaan', ['except' => []]);
  }

  public function index(Request $req){

    $judul = "Laporan Persediaan";
    $breadcrumbs = [
      ['link' => '#', 'name' => "Laporan"],
      ['link' => '#', 'name' => "Persediaan"],
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



    $id_stok_akhir = RiwayatStokProduk::
                    select(DB::raw('max(id) as ids'))
                    ->whereDate('created_at','<=',$end_date->format('Y-m-d'))
                    ->groupBy('produk_id')
                    ->pluck('ids');

    $saldo_akhir = RiwayatStokProduk::
                    select('produk_id','stok_awal','keluar_masuk','kuantitas')
                    ->whereIn('id',$id_stok_akhir);

    $daftar_stok = Produk::
              joinSub($saldo_akhir, 'saldo_akhir', function ($join) {
                  $join->on('produk.id', '=', 'saldo_akhir.produk_id');
              })
              ->paginate(10);

    return view(
      'mimin.laporan.persediaan.index',
      compact('judul', 'breadcrumbs','daftar_stok','start_date','end_date')
    );

  }
}
