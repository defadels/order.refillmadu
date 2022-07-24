<?php

namespace App\Http\Controllers\Admin\Orang;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Suplier;
use Validator;
use Session;
use App\Cabang;

class SuplierController extends Controller
{
  function __construct()
  {
       $this->middleware('permission:mitra.suplier.lihat', ['only' => ['index','show']]);
       $this->middleware('permission:mitra.suplier.edit', ['only' => ['edit','update','destroy']]);
       $this->middleware('permission:mitra.suplier.tambah', ['only' => ['create','store']]);
  }

  public function index(Request $req){
    $judul = "Suplier";
    $breadcrumbs = [
      ['link'=>'#','name'=>"Orang"],
      ['link'=>'#','name'=>"Suplier"],
    ];



    $cari = "";
    if ($req->has('cari')){
      $cari = $req->cari;
      $daftar_suplier = Suplier::where('nama','like','%'.$cari.'%')
                          ->paginate(10);

    } else {
      $daftar_suplier = Suplier::simplePaginate(10);
    }


    return view('mimin.orang.suplier.index',
    compact('judul','breadcrumbs','daftar_suplier','cari')
    );
  }


  public function create (){

    $judul = "Suplier";
    $judul_deskripsi = "";
    $deskripsi = "";
    $breadcrumbs = [
      ['link'=>'#','name'=>"Orang"],
      ['link'=>'#','name'=>"Suplier"],
      ['link'=>'#','name'=>"Tambah"],
    ];

    return view('mimin.orang.suplier.create',
    compact('judul','judul_deskripsi','breadcrumbs','deskripsi')
    );

  }

  public function store (Request $req){

    $rules = [
      'nama' =>'required',
      'status' => 'required'
    ];
    $messages =[
        'nama.required'=>'Nama Suplier harus diisi',
        'status.required' => 'Status harus diiisi'
    ];

    $input= $req->all();
    $validator = Validator::make($input, $rules, $messages)->validate();


    $suplier = Suplier::create([
        'nama'=>$req->nama,
        'keterangan'=>$req->keterangan,
        'status'=>$req->status
    ]);

    return redirect()->route('mimin.orang.suplier.index')->with('sukses','Tambah Suplier Sukses');
  }

  public function edit($id){
    $judul = "Suplier";
    $judul_deskripsi = "";
    $deskripsi = "";
    $breadcrumbs = [
      ['link'=>'#','name'=>"Orang"],
      ['link'=>'#','name'=>"Suplier"],
      ['link'=>'#','name'=>"Edit"],
    ];

    $suplier = Suplier::findOrFail($id);

    return view('mimin.orang.suplier.edit',
    compact('judul','breadcrumbs','judul_deskripsi','deskripsi','suplier')
    );
  }

  public function update(Request $req, $id){

    $suplier=Suplier::findOrFail($id);

    $rules = [
      'nama' =>'required',
      'status' => 'required'
    ];
    $messages =[
        'nama.required'=>'Nama Suplier harus diisi',
        'status.required' => 'Status harus diiisi'
    ];


    $input= $req->all();
    $validator = Validator::make($input, $rules, $messages)->validate();

    $suplier->nama = $req->nama;
    $suplier->status = $req->status;
    $suplier->keterangan = $req->keterangan;
    $suplier->save();

    return redirect()->route('mimin.orang.suplier.index')->with('sukses', $suplier->nama. 'Berhasil diubah');
 }

 public function destroy (Request $req, $id){

  try {
    $suplier = Suplier::findOrFail($id);

    $nama = $suplier->nama;
    $result = $suplier->delete();
    if ($result){
      return response()->json([
          'judul' => 'Terhapus!',
          'pesan' => $nama.' Sukses Dihapus',
          'success' => true,
          'redirect'=> route('mimin.orang.suplier.index')
          ]);
    } else {
          return response()->json([
          'judul' => 'Gagal Terhapus',
          'pesan' => $nama.' Gagal Dihapus',
          'success' => false,
          'redirect'=> route('mimin.orang.suplier.index')
          ]);
    }

  } catch(\Exception $exception){
          return response()->json([
            'judul' =>'Gagal Dihapus',
            'pesan' => 'Terjadi kesalahan atau suplier masih terkait dengan data lain',
            'success' => false,
            'redirect'=> route('mimin.orang.suplier.index')
        ]);
  }

}
}
