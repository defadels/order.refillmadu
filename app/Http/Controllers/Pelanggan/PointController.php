<?php

namespace App\Http\Controllers\Pelanggan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Auth;
use Session;
use App\Cabang;
use App\Pelanggan;
use Illuminate\Support\Str;
use App\KategoriPelanggan;
use App\User;
use App\Point;
use App\Kas;
use Carbon\Carbon;
use App\TransaksiKas;
use Validator;
use DB;

class PointController extends Controller
{
  public function index(){
    $judul = "Rincian Point";
    $breadcrumbs = [
      ['link'=>'#','name'=>"Rincian"],
    ];

    $pelanggan = Pelanggan::findOrFail(Auth::id());
    $daftar_rincian = Point::where('user_id',$pelanggan->id)
                              ->orderBy('tanggal','desc')
                              ->orderBy('created_at','desc')
                              ->orderBy('id','desc')
                              ->simplePaginate(10);

    return view('pelanggan.point.point',
    compact('judul','breadcrumbs','daftar_rincian','pelanggan'));
  }
/*
  public function transaksi(Request $req){
    $judul = "Riwayat Transaksi";
    $breadcrumbs = [
      ['link'=>'#','name'=>"Point"],
    ];

    $daftar_rincian = Point::orderBy('tanggal','desc')
                              ->orderBy('created_at','desc')
                              ->simplePaginate(10);

    return view('pelanggan.point.transaksi',
    compact('judul','breadcrumbs','daftar_rincian'));
  }
*/
  public function lihat_transaksi ($id){
    $judul = "Rincian Transaksi";

    $breadcrumbs = [
      ['link'=>'#','name'=>"Point"]
    ];

    $point = Point::findOrFail($id);
    return view('pelanggan.point.lihat_transaksi',
    compact('judul','breadcrumbs','point'));

  }
}
