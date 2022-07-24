<?php

namespace App\Http\Controllers\Pelanggan\Orang;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use Spatie\Permission\Models\Role;
use Validator;
use Hash;
use Auth;
use Session;
use App\Cabang;
use App\Pelanggan;
use Illuminate\Support\Str;
use App\AlamatPelanggan;
use App\KategoriPelanggan;

class AlamatController extends Controller
{
  public function index($pelanggan_id){

    $judul = "Daftar Alamat";
    $breadcrumbs = [
        ['link'=>'#','name'=>"Pelanggan"],
        ['link'=>'#','name'=>"Alamat"],
    ];

    $you = Auth::user();
    if ($you->level == 1){

      $pelanggan = Pelanggan::with('kategori')->where(function($query) {
        $query->where('parent_id','=',Auth::id())
        ->orWhere('distributor_id',Auth::id());
    })->where('id',$pelanggan_id)->firstOrFail();

    } else {
        $pelanggan = Pelanggan::with('kategori')->where('parent_id','=',Auth::id())->where('id',$pelanggan_id)->firstOrFail();
    }


    $daftar_alamat = $pelanggan->daftar_alamat()->orderBy('label')->paginate(10);


    return view('pelanggan.pelanggan.alamat.index',
                    compact('judul','breadcrumbs','daftar_alamat','pelanggan')
                );

  }

  public function create($pelanggan_id){
      $judul = "Daftar Alamat";
      $breadcrumbs = [
          ['link'=>'#','name'=>"Pelanggan"],
          ['link'=>'#','name'=>"Alamat"],
          ['link'=>'#','name'=>"Tambah"],
      ];
      $you = Auth::user();
      if ($you->level == 1){

        $pelanggan = Pelanggan::with('kategori')->where(function($query) {
          $query->where('parent_id','=',Auth::id())
          ->orWhere('distributor_id',Auth::id());
      })->where('id',$pelanggan_id)->firstOrFail();

      } else {
          $pelanggan = Pelanggan::with('kategori')->where('parent_id','=',Auth::id())->where('id',$pelanggan_id)->firstOrFail();
      }


      return view('pelanggan.pelanggan.alamat.create',
            compact('judul','breadcrumbs','pelanggan')
      );
  }


  public function store (Request $req,$pelanggan_id){

    $rules = [
      'label' =>'required',
      'nama' =>'required',
      'nomor_hp' => 'required',
      'alamat' =>'required',

      ];
      $messages =[
      ];

      $input= $req->all();
      $validator = Validator::make($input, $rules, $messages)->validate();
      $you = Auth::user();
      if ($you->level == 1){

        $pelanggan = Pelanggan::with('kategori')->where(function($query) {
          $query->where('parent_id','=',Auth::id())
          ->orWhere('distributor_id',Auth::id());
      })->where('id',$pelanggan_id)->firstOrFail();

      } else {
          $pelanggan = Pelanggan::with('kategori')->where('parent_id','=',Auth::id())->where('id',$pelanggan_id)->firstOrFail();
      }

      $user = AlamatPelanggan::create([
          'pelanggan_id'=>$pelanggan->id,
          'label'=>$req->label,
          'nama'=>$req->nama,
          'nomor_hp'=>$req->nomor_hp,
          'alamat'=>$req->alamat,
      ]);

      return redirect()->route('pelanggan.pelanggan.alamat.index',$pelanggan)->with('sukses','Tambah Alamat Pelanggan Sukses');
  }

  public function edit ($pelanggan_id,$alamat_id){

    $judul            = "Edit Pelanggan";
    $judul_deskripsi  = "";
    $deskripsi        = "";

    $breadcrumbs = [
      ['link'=>'#','name'=>"Pelanggan"],
      ['link'=>'#','name'=>"Alamat"],
      ['link'=>'#','name'=>"Edit"],
    ];
    $you = Auth::user();
    if ($you->level == 1){

      $pelanggan = Pelanggan::with('kategori')->where(function($query) {
        $query->where('parent_id','=',Auth::id())
        ->orWhere('distributor_id',Auth::id());
    })->where('id',$pelanggan_id)->firstOrFail();

    } else {
        $pelanggan = Pelanggan::with('kategori')->where('parent_id','=',Auth::id())->where('id',$pelanggan_id)->firstOrFail();
    }

    $alamat = $pelanggan->daftar_alamat()->where('id',$alamat_id)->firstOrFail();



    return view('pelanggan.pelanggan.alamat.edit',
          compact('judul','breadcrumbs','judul_deskripsi','deskripsi','pelanggan','alamat')
    );

}


public function update(Request $req, $pelanggan_id, $alamat_id){
  $you = Auth::user();
  if ($you->level == 1){

    $pelanggan = Pelanggan::with('kategori')->where(function($query) {
      $query->where('parent_id','=',Auth::id())
      ->orWhere('distributor_id',Auth::id());
  })->where('id',$pelanggan_id)->firstOrFail();

  } else {
      $pelanggan = Pelanggan::with('kategori')->where('parent_id','=',Auth::id())->where('id',$pelanggan_id)->firstOrFail();
  }

  $alamat = $pelanggan->daftar_alamat()->where('id',$alamat_id)->firstOrFail();
    $rules = [
      'label' =>'required',
      'nama' =>'required',
      'nomor_hp' => 'required',
      'alamat' =>'required',
    ];

    $messages =[];

    $input= $req->all();
    $validator = Validator::make($input, $rules, $messages)->validate();

    $alamat->label      = $req->label;
    $alamat->nama       = $req->nama;
    $alamat->nomor_hp   = $req->nomor_hp;
    $alamat->alamat     = $req->alamat;

    $alamat->save();
    return redirect()->route('pelanggan.pelanggan.alamat.index',[$pelanggan->id,$alamat->id])->with('sukses', 'Alamat ' . $pelanggan->nama. ' berhasil diubah');

}

public function destroy (Request $req, $id,$alamat_id){
  try {
    $you = Auth::user();
  if ($you->level == 1){

    $pelanggan = Pelanggan::where(function($query) {
      $query->where('parent_id','=',Auth::id())
      ->orWhere('distributor_id',Auth::id());
  })->where('id',$id)->firstOrFail();

  } else {
      $pelanggan = Pelanggan::where('parent_id','=',Auth::id())->where('id',$id)->firstOrFail();
  }

  $alamat = $pelanggan->daftar_alamat()->where('id',$alamat_id)->firstOrFail();

  $nama = $alamat->label;

  $result = $alamat->delete();

  if ($result){
  return response()->json([
    'pesan' => 'Alamat'.$nama.' dari '.$pelanggan->nama.' Sukses Dihapus',
    'success' => true,
    'redirect'=> route('pelanggan.pelanggan.alamat.index',$pelanggan->id)
    ]);
  } else {
    return response()->json([
    'pesan' => 'Alamat '.$nama.' dari '.$pelanggan->nama.' Gagal Dihapus',
    'success' => false,
    'redirect'=> route('pelanggan.pelanggan.alamat.index',$pelanggan->id)
    ]);
  }

  } catch(\Exception $exception){
    return response()->json([
      'pesan' => 'Gagal Dihapus',
      'success' => false,
      'redirect'=> route('pelanggan.pelanggan.alamat.index',$id)
  ]);
  }

}
}
