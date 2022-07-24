<?php

namespace App\Http\Controllers\Pelanggan\Pesanan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Pesanan;
use Validator;
use App\Produk;
use Auth, Session, DB;
use App\Gudang;
use Carbon\Carbon;
use App\Pelanggan;
use App\Cabang;
use App\MetodePembayaran;
use App\User;
use App\MetodePengiriman;

class DibatalkanController extends Controller
{



  public function index()
  {
    $judul = "Pesanan Dibatalkan";
    $breadcrumbs = [
      ['link' => '#', 'name' => "Pesanan"],
      ['link' => '#', 'name' => "Dibatalkan"],
    ];
    $pelanggan = Auth::user();
    $daftar_pesanan = Pesanan::dibatalkan()->with('pelanggan', 'metode_pembayaran')
    ->select('pesanan_upline.pelanggan_id as upline_id',
            'pesanan_upline.pelanggan_level as upline_level',
            'pesanan_upline.nominal_pembelian as upline_nominal_pembelian','pesanan.*')
    ->leftjoin('pesanan_upline', 'pesanan.id', '=', 'pesanan_upline.pesanan_id')
    ->where(function($query)
    {
        $query->where('pesanan.pelanggan_id',Auth::id())
              ->orWhere('pesanan_upline.pelanggan_id',Auth::id());
    })
    ->orderBy('created_at', 'desc')
    ->simplePaginate(10);

    return view(
      'pelanggan.pesanan.dibatalkan.index',
      compact('judul', 'breadcrumbs', 'daftar_pesanan')
    );
  }

  public function show($id)
  {

   // return $id;
    $judul = "Pesanan Dibatalkan";
    $judul_deskripsi = "";
    $deskripsi = "";

    $breadcrumbs = [
      ['link' => '#', 'name' => "Pesanan"],
      ['link' => '#', 'name' => "Dibatalkan"],
      ['link' => '#', 'name' => "Lihat"],
    ];

    $pesanan = Pesanan::dibatalkan()
    ->select('pesanan_upline.pelanggan_id as upline_id',
    'pesanan_upline.pelanggan_level as upline_level',
    'pesanan_upline.nominal_pembelian as upline_nominal_pembelian','pesanan.*')
    ->leftjoin('pesanan_upline', 'pesanan.id', '=', 'pesanan_upline.pesanan_id')
    ->where(function($query)
    {
        $query->where('pesanan.pelanggan_id',Auth::id())
              ->orWhere('pesanan_upline.pelanggan_id',Auth::id());
    })->where('pesanan.id',$id)->firstOrFail();


    return view (
      'pelanggan.pesanan.dibatalkan.show',
       compact ('judul',
                'breadcrumbs',
                'judul_deskripsi',
                'deskripsi',
                'pesanan')
    );

  }


}
