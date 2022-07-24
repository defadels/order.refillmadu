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

class SelesaiController extends Controller
{

  public function index()
  {
    $judul = "Pesanan Selesai";
    $breadcrumbs = [
      ['link' => '#', 'name' => "Pesanan"],
      ['link' => '#', 'name' => "Selesai"],
    ];
    $pelanggan = Auth::user();
    $daftar_pesanan = Pesanan::selesai()->with('pelanggan', 'metode_pembayaran')
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
      'pelanggan.pesanan.selesai.index',
      compact('judul', 'breadcrumbs', 'daftar_pesanan')
    );
  }

  public function show($id)
  {

   // return $id;
    $judul = "Pesanan Selesai";
    $judul_deskripsi = "";
    $deskripsi = "";

    $breadcrumbs = [
      ['link' => '#', 'name' => "Pesanan"],
      ['link' => '#', 'name' => "Selesai"],
      ['link' => '#', 'name' => "Lihat"],
    ];

    $pesanan = Pesanan::selesai()
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
      'pelanggan.pesanan.selesai.show',
       compact ('judul',
                'breadcrumbs',
                'judul_deskripsi',
                'deskripsi',
                'pesanan')
    );

  }


}
