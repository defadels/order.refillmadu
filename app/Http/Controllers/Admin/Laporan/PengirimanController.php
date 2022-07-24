<?php

namespace App\Http\Controllers\Admin\Laporan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Pesanan;
use App\MetodePengiriman;

class PengirimanController extends Controller
{  function __construct()
  {
       $this->middleware('permission:laporan.pengiriman', ['except' => []]);
  }

  public function index(Request $req)
  {
    $judul = "Laporan Pengiriman";
    $breadcrumbs = [
      ['link' => '#', 'name' => "Laporan"],
      ['link' => '#', 'name' => "Pengiriman"],
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

    $daftar_pengiriman = MetodePengiriman::pluck("nama","id");
    $daftar_pengiriman->prepend("Semua Pengiriman",0);
    $pengiriman = 0;
    $resi = "";
    if ($req->has("pengiriman")){
      $pengiriman = $req->pengiriman;
    }



    $daftar_pesanan = Pesanan::selesai()->with('pelanggan', 'metode_pembayaran')
      ->whereDate('tanggal_pembayaran','>=',$start_date->format('Y-m-d'))
      ->whereDate('tanggal_pembayaran','<=',$end_date->format('Y-m-d'))
      ->orderBy('created_at', 'desc');

    if ($pengiriman != 0){
      $daftar_pesanan =  $daftar_pesanan->where("metode_pengiriman_id",$pengiriman);
    }
    if ($req->has("resi")){
      $resi = $req->resi;
      $daftar_pesanan =  $daftar_pesanan->where("nomor_resi","like","%".$resi."%");
    }
      $daftar_pesanan = $daftar_pesanan->paginate(10);


    return view(
      'mimin.laporan.pengiriman.index',
      compact('judul', 'breadcrumbs', 'daftar_pesanan','start_date','end_date','resi',
      'daftar_pengiriman','pengiriman')
    );
  }
}
