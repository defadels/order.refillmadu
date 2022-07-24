<?php

namespace App\Http\Controllers\Admin\Pesanan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Pesanan;
class StatusController extends Controller
{
  function __construct()
  {
       $this->middleware('permission:pesanan.status', ['except' => []]);
  }

  public function index()
  {
    $judul = "Status Pesanan";
    $breadcrumbs = [
      ['link' => '#', 'name' => "Pesanan"],
      ['link' => '#', 'name' => "Status"],
    ];

    $daftar_pesanan = Pesanan::withoutGlobalScope(CabangScope::class)->with('pelanggan', 'metode_pembayaran')
      ->orderBy('created_at', 'desc')
      ->simplePaginate(10);
    return view(
      'mimin.pesanan.status.index',
      compact('judul', 'breadcrumbs', 'daftar_pesanan')
    );
  }

  public function show($id)
  {

   // return $id;
    $judul = "Status Pesanan";
    $judul_deskripsi = "";
    $deskripsi = "";

    $breadcrumbs = [
      ['link' => '#', 'name' => "Pesanan"],
      ['link' => '#', 'name' => "Status"],
      ['link' => '#', 'name' => "Lihat"],
    ];

    $pesanan = Pesanan::withoutGlobalScope(CabangScope::class)->findOrFail($id);



    return view (
      'mimin.pesanan.status.show',
       compact ('judul',
                'breadcrumbs',
                'judul_deskripsi',
                'deskripsi',
                'pesanan')
    );

  }
}
