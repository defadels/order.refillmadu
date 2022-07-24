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

class DiprosesController extends Controller
{



  public function index()
  {
    $judul = "Pesanan Diproses";
    $breadcrumbs = [
      ['link' => '#', 'name' => "Pesanan"],
      ['link' => '#', 'name' => "Diproses"],
    ];
    $pelanggan = Auth::user();
    $daftar_pesanan = Pesanan::with('pelanggan', 'metode_pembayaran')
    ->select('pesanan_upline.pelanggan_id as upline_id',
            'pesanan_upline.pelanggan_level as upline_level',
            'pesanan_upline.nominal_pembelian as upline_nominal_pembelian','pesanan.*')
    ->leftjoin('pesanan_upline', 'pesanan.id', '=', 'pesanan_upline.pesanan_id')
    ->where(function($query)
    {
        $query->where('pesanan.pelanggan_id',Auth::id())
              ->orWhere('pesanan_upline.pelanggan_id',Auth::id());
    })->where(function($query)
    {
        $query->where('pesanan.status','!=','preorder')
        ->where('pesanan.status','!=','selesai')
        ->where('pesanan.status','!=','dibatalkan');
    })
    ->orderBy('created_at', 'desc')
    ->simplePaginate(10);

    return view(
      'pelanggan.pesanan.diproses.index',
      compact('judul', 'breadcrumbs', 'daftar_pesanan')
    );
  }

  public function show($id)
  {

   // return $id;
    $judul = "Pesanan Masuk";
    $judul_deskripsi = "";
    $deskripsi = "";

    $breadcrumbs = [
      ['link' => '#', 'name' => "Pesanan"],
      ['link' => '#', 'name' => "Masuk"],
      ['link' => '#', 'name' => "Lihat"],
    ];

    $pesanan = Pesanan::select('pesanan_upline.pelanggan_id as upline_id',
    'pesanan_upline.pelanggan_level as upline_level',
    'pesanan_upline.nominal_pembelian as upline_nominal_pembelian','pesanan.*')
    ->leftjoin('pesanan_upline', 'pesanan.id', '=', 'pesanan_upline.pesanan_id')
    ->where(function($query)
    {
        $query->where('pesanan.pelanggan_id',Auth::id())
              ->orWhere('pesanan_upline.pelanggan_id',Auth::id());
    })
    ->where(function($query)
    {
        $query->where('pesanan.status','!=','preorder')
        ->where('pesanan.status','!=','selesai')
        ->where('pesanan.status','!=','dibatalkan');
    })
    ->where('pesanan.id',$id)->firstOrFail();


    return view (
      'pelanggan.pesanan.diproses.show',
       compact ('judul',
                'breadcrumbs',
                'judul_deskripsi',
                'deskripsi',
                'pesanan')
    );

  }

  public function destroy(Request $req, $id)
  {

    try {

      $pesanan = Pesanan::masuk()
      ->select('pesanan_upline.pelanggan_id as upline_id',
      'pesanan_upline.pelanggan_level as upline_level',
      'pesanan_upline.nominal_pembelian as upline_nominal_pembelian','pesanan.*')
      ->leftjoin('pesanan_upline', 'pesanan.id', '=', 'pesanan_upline.pesanan_id')
      ->where(function($query)
      {
          $query->where('pesanan.pelanggan_id',Auth::id())
                ->orWhere('pesanan_upline.pelanggan_id',Auth::id());
      })->where('pesanan.id',$id)->firstOrFail();


      $now = Carbon::now();
      $nama = 'Pesanan tanggal ' . $pesanan->created_at->format('d-m-Y');

      if ($req->has('aksi') && ($req->aksi =="masuk" || $req->aksi == "batal")) {

        if ($req->aksi =="batal"){
          $keterangan = 'Membatalkan ';
          $pesanan->status = "dibatalkan";
          $pesanan->dibatalkan_oleh_id = Auth::id();
          $pesanan->dibatalkan_pada = $now;
          $pesanan->save();
        }

        return response()->json([
          'judul' => $keterangan.'sukses!',
          'pesan' => $keterangan.' '.$nama.' Sukses',
          'success' => true,
          'redirect' => route('pelanggan.pesanan.diproses.index')
        ]);
      } else {
        return response()->json([
          'judul' => $nama.' Gagal',
          'pesan' => 'Gagal',
          'success' => false,
          'redirect' => route('pelanggan.pesanan.diproses.index')
        ]);
      }
    } catch (\Exception $exception) {
      return response()->json([
        'judul' => 'Gagal',
        'pesan' => 'Terjadi kesalahan',
        'success' => false,
        'redirect' => route('pelanggan.pesanan.diproses.index')
      ]);
    }
  }



}
