<?php

namespace App\Http\Controllers\Admin\Pesanan;

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

  function __construct()
  {
       $this->middleware('permission:pesanan.dibatalkan.lihat', ['only' => ['index','show']]);
  }

  public function index()
  {
    $judul = "Pesanan Dibatalkan";
    $breadcrumbs = [
      ['link' => '#', 'name' => "Pesanan"],
      ['link' => '#', 'name' => "Dibatalkan"],
    ];

    $daftar_pesanan = Pesanan::dibatalkan()->with('pelanggan', 'metode_pembayaran')
      ->orderBy('created_at', 'desc')
      ->simplePaginate(10);
    return view(
      'mimin.pesanan.dibatalkan.index',
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

    $pesanan = Pesanan::dibatalkan()->findOrFail($id);



    return view (
      'mimin.pesanan.dibatalkan.show',
       compact ('judul',
                'breadcrumbs',
                'judul_deskripsi',
                'deskripsi',
                'pesanan')
    );

  }

}
