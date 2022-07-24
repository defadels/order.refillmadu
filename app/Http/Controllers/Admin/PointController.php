<?php

namespace App\Http\Controllers\Admin;

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
  function __construct()
  {
       $this->middleware('permission:point.lihat', ['only' => ['index','point','transaksi','lihat_transaksi']]);
       $this->middleware('permission:point.tambah', ['only' => ['tambah','tambah_post','tambah_transaksi','tambah_transaksi_post']]);
       $this->middleware('permission:point.kurangi', ['only' => ['kurangi','kurangi_post','kurangi_transaksi','kurangi_transaksi_post']]);
  }

  public function index()
  {

    $judul = "Point";
    $breadcrumbs = [
        ['link'=>'#','name'=>"Saldo"],
    ];

    $daftar_pelanggan = Pelanggan::with('kategori')->where('point','!=',0)->paginate(10);
    //return $daftar_pelanggan;

    return view('mimin.point.index',
                    compact('judul','breadcrumbs','daftar_pelanggan')
                );
  }

  public function point($id){
    $judul = "Rincian Point";
    $breadcrumbs = [
      ['link'=>'#','name'=>"Rincian"],
    ];

    $pelanggan = Pelanggan::findOrFail($id);
    $daftar_rincian = Point::where('user_id',$pelanggan->id)
                              ->orderBy('tanggal','desc')
                              ->orderBy('created_at','desc')
                              ->orderBy('id','desc')
                              ->simplePaginate(10);

    return view('mimin.point.point',
    compact('judul','breadcrumbs','daftar_rincian','pelanggan'));
  }

  public function transaksi(Request $req){
    $judul = "Riwayat Transaksi";
    $breadcrumbs = [
      ['link'=>'#','name'=>"Point"],
    ];

    $daftar_rincian = Point:: orderBy('tanggal','desc')
                              ->orderBy('created_at','desc')
                              ->simplePaginate(10);

    return view('mimin.point.transaksi',
    compact('judul','breadcrumbs','daftar_rincian'));
  }


  public function tambah($id){
    $judul = "Tambah isi Point";
    $breadcrumbs = [
      ['link'=>'#','name'=>"Point"],
      ['link'=>'#','name'=>"tambah"],
    ];
    $pelanggan = Pelanggan::findOrFail($id);

    return view('mimin.point.tambah',
    compact('judul','breadcrumbs', 'pelanggan'));
  }

  public function tambah_post(Request $req,$id){
    $pelanggan = User::findOrFail($id);

    $rules = [
      'tanggal' =>'required',
      'nominal' => 'required|min:0',
      'keterangan' => 'required',
    ];
    $messages =[
        'tanggal.required'=>'Tanggal wajib diisi',
        'nominal.required' => 'Nominal wajib diisi',
        'keterangan.required' => 'Keterangan wajib diisi',
    ];

    $input= $req->all();
    $validator = Validator::make($input, $rules, $messages)->validate();


    DB::transaction(function () use ($req,$pelanggan) {
      $nominal = $req->nominal;
      $tanggal = Carbon::createFromFormat('d-m-Y',$req->tanggal);
      //update saldo
      $pelanggan->update_point($tanggal,$nominal,'d');

      Point::create([
        'tanggal'=>$tanggal,
        'nominal'=>$req->nominal,
        'keterangan'=>$req->keterangan,
        'user_id'=>$pelanggan->id,
        'debet_kredit'=>'debet'
      ]);

    });
    return redirect()->route('mimin.point.pelanggan.index',$pelanggan->id)->with('sukses','Tambah Point Sukses');

  }


  public function kurangi($id){
    $judul = "Kurangi isi Point";
    $breadcrumbs = [
      ['link'=>'#','name'=>"Point"],
      ['link'=>'#','name'=>"kurangi"],
    ];
    $pelanggan = Pelanggan::findOrFail($id);

    return view('mimin.point.kurangi',
    compact('judul','breadcrumbs' ,'pelanggan'));
  }

  public function kurangi_post(Request $req,$id){
    $pelanggan = User::findOrFail($id);

    $rules = [
      'tanggal' =>'required',
      'nominal' => 'required|min:0',
      'keterangan' => 'required',
    ];
    $messages =[
        'tanggal.required'=>'Tanggal wajib diisi',
        'nominal.required' => 'Nominal wajib diisi',
        'keterangan.required' => 'Keterangan wajib diisi',
    ];

    $input= $req->all();
    $validator = Validator::make($input, $rules, $messages)->validate();


    DB::transaction(function () use ($req,$pelanggan) {
      $nominal = $req->nominal;

      $tanggal = Carbon::createFromFormat('d-m-Y',$req->tanggal);
      //update saldo

      $pelanggan->update_point($tanggal,$nominal,'k');


      Point::create([
        'tanggal'=>$tanggal,
        'nominal'=>$req->nominal,
        'keterangan'=>$req->keterangan,
        'user_id'=>$pelanggan->id,
        'debet_kredit'=>'kredit'
      ]);

    });
    return redirect()->route('mimin.point.pelanggan.index',$pelanggan->id)->with('sukses','Kurangi Point Sukses');

  }


  public function tambah_transaksi(){
    $judul = "Tambah isi Point";
    $breadcrumbs = [
      ['link'=>'#','name'=>"Point"],
      ['link'=>'#','name'=>"Transaksi"],
      ['link'=>'#','name'=>"tambah"],
    ];
    $daftar_pelanggan = [];

    return view('mimin.point.tambah_transaksi',
    compact('judul','breadcrumbs','daftar_pelanggan'));
  }

  public function tambah_transaksi_post(Request $req){
    $pelanggan = User::findOrFail($req->pelanggan_id);

    $rules = [
      'tanggal' =>'required',
      'nominal' => 'required|min:0',
      'keterangan' => 'required',
    ];
    $messages =[
        'tanggal.required'=>'Tanggal wajib diisi',
        'nominal.required' => 'Nominal wajib diisi',
        'keterangan.required' => 'Keterangan wajib diisi',
    ];

    $input= $req->all();
    $validator = Validator::make($input, $rules, $messages)->validate();


    DB::transaction(function () use ($req,$pelanggan) {
      $nominal = $req->nominal;

      $tanggal = Carbon::createFromFormat('d-m-Y',$req->tanggal);
      //update saldo

      $pelanggan->update_point($tanggal,$nominal,'d');

      Point::create([
        'tanggal'=>$tanggal,
        'nominal'=>$req->nominal,
        'keterangan'=>$req->keterangan,
        'user_id'=>$pelanggan->id,
        'debet_kredit'=>'debet'
      ]);

    });
    return redirect()->route('mimin.point.transaksi')->with('sukses','Tambah isi Point Sukses');

  }

  public function kurangi_transaksi(){
    $judul = "Kurangi isi Point";
    $breadcrumbs = [
      ['link'=>'#','name'=>"Point"],
      ['link'=>'#','name'=>"Transaksi"],
      ['link'=>'#','name'=>"kurangi"],
    ];
    $daftar_pelanggan = [];

    return view('mimin.point.kurangi_transaksi',
    compact('judul','breadcrumbs','daftar_pelanggan'));
  }

  public function kurangi_transaksi_post(Request $req){
    $pelanggan = User::findOrFail($req->pelanggan_id);

    $rules = [
      'tanggal' =>'required',
      'nominal' => 'required|min:0',
      'keterangan' => 'required',
    ];
    $messages =[
        'tanggal.required'=>'Tanggal wajib diisi',
        'nominal.required' => 'Nominal wajib diisi',
        'keterangan.required' => 'Keterangan wajib diisi',
    ];

    $input= $req->all();
    $validator = Validator::make($input, $rules, $messages)->validate();


    DB::transaction(function () use ($req,$pelanggan) {
      $tanggal = Carbon::createFromFormat('d-m-Y',$req->tanggal);
      //update saldo

      $nominal = $req->nominal;
      $pelanggan->update_point($tanggal,$nominal,'k');


      Point::create([
        'tanggal'=>$tanggal,
        'nominal'=>$req->nominal,
        'keterangan'=>$req->keterangan,
        'user_id'=>$pelanggan->id,
        'debet_kredit'=>'kredit'
      ]);


    });
    return redirect()->route('mimin.point.transaksi')->with('sukses','Kurangi Point Sukses');

  }

  public function cari_pelanggan (Request $req){


    $key = $req->cari;

    if ($req->has("cari")&&$req->cari!=""){

        $daftar_kegiatan = User::where(function ($query) use($key) {
                                    $query->where('nama','like','%'.$key.'%');
                                })->orWhere('email',$key)
                                ->orWhere('nomor_hp',$key)
                                ->paginate(5);

    } else {
        $daftar_kegiatan = Pelanggan::paginate(5);
    }
    $results = array(
                              "results" => $daftar_kegiatan->toArray()['data'],
                              "pagination" =>array(
                                  "more" => $daftar_kegiatan->hasMorePages()
                              )
    );

    return response()->json($results);
}



  public function lihat_transaksi ($id){
      $judul = "Rincian Transaksi";

      $breadcrumbs = [
        ['link'=>'#','name'=>"Point"],
        ['link'=>'#','name'=>"Transaksi"],
        ['link'=>'#','name'=>"rincian"],
      ];

      $point = Point::findOrFail($id);
      return view('mimin.point.lihat_transaksi',
      compact('judul','breadcrumbs','point'));
  }
}
