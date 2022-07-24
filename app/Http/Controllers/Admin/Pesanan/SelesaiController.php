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
use PDF;


class SelesaiController extends Controller
{

  function __construct()
  {
       $this->middleware('permission:pesanan.selesai.lihat', ['only' => ['index','show','cetak']]);
  }

  public function index()
  {
    $judul = "Pesanan Selesai";
    $breadcrumbs = [
      ['link' => '#', 'name' => "Pesanan"],
      ['link' => '#', 'name' => "Selesai"],
    ];

    $daftar_pesanan = Pesanan::selesai()->with('pelanggan', 'metode_pembayaran')
      ->orderBy('created_at', 'desc')
      ->simplePaginate(10);
    return view(
      'mimin.pesanan.selesai.index',
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

    $pesanan = Pesanan::selesai()->findOrFail($id);

    return view (
      'mimin.pesanan.selesai.show',
       compact ('judul',
                'breadcrumbs',
                'judul_deskripsi',
                'deskripsi',
                'pesanan')
    );

  }

  public function cetak($id){


    $pesanan = Pesanan::selesai()->findOrFail($id);

    // share data to view
    view()->share('pesanan',$pesanan);
    $pdf = PDF::loadView('mimin.pesanan.selesai.cetak', $pesanan)->setPaper('a4', 'portrait');

    // download PDF file with download method
    return $pdf->stream('invoice_'.$pesanan->id.'.pdf');

  }

}
