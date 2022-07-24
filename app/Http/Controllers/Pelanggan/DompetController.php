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
use App\Dompet;
use App\Kas;
use Carbon\Carbon;
use App\TransaksiKas;
use Validator;
use DB;

class DompetController extends Controller
{
  public function index(){
    $judul = "Rincian Dompet";
    $breadcrumbs = [
      ['link'=>'#','name'=>"Rincian"],
    ];

    $pelanggan = Pelanggan::findOrFail(Auth::id());
    $daftar_rincian = Dompet::where('user_id',$pelanggan->id)
                              ->orderBy('tanggal','desc')
                              ->orderBy('created_at','desc')
                              ->orderBy('id','desc')
                              ->simplePaginate(10);

    return view('pelanggan.dompet.dompet',
    compact('judul','breadcrumbs','daftar_rincian','pelanggan'));
  }

  public function transaksi(Request $req){
    $judul = "Riwayat Transaksi";
    $breadcrumbs = [
      ['link'=>'#','name'=>"Dompet"],
    ];

    $daftar_rincian = Dompet::orderBy('tanggal','desc')
                              ->orderBy('created_at','desc')
                              ->simplePaginate(10);

    return view('pelanggan.dompet.transaksi',
    compact('judul','breadcrumbs','daftar_rincian'));
  }

  public function lihat_transaksi ($id){
    $judul = "Rincian Transaksi";

    $breadcrumbs = [
      ['link'=>'#','name'=>"Dompet"],
      ['link'=>'#','name'=>"Transaksi"],
      ['link'=>'#','name'=>"rincian"],
    ];

    $dompet = Dompet::findOrFail($id);
    return view('pelanggan.dompet.lihat_transaksi',
    compact('judul','breadcrumbs','dompet'));

}
}
