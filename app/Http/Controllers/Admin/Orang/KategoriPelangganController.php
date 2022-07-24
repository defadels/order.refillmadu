<?php

namespace App\Http\Controllers\Admin\Orang;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\KategoriPelanggan;
use Validator;
use Session;
use App\Cabang;


class KategoriPelangganController extends Controller
{
  function __construct()
  {
       $this->middleware('permission:mitra.pelanggan.lihat', ['only' => ['index','show']]);
       $this->middleware('permission:mitra.pelanggan.edit', ['only' => ['edit','update','destroy']]);
       $this->middleware('permission:mitra.pelanggan.tambah', ['only' => ['create','store']]);
  }

  public function index(){
    $judul = "Kategori";
    $breadcrumbs = [
      ['link'=>'#','name'=>"Orang"],
      ['link'=>'#','name'=>"Kategori"],
    ];

    $daftar_kategori = KategoriPelanggan::paginate(10);




    return view('mimin.orang.pelanggan.kategori.index',
    compact('judul','breadcrumbs','daftar_kategori')
    );
  }


  public function create (){

    $judul = "Kategori";
    $judul_deskripsi = "";
    $deskripsi = "";
    $breadcrumbs = [
      ['link'=>'#','name'=>"Orang"],
      ['link'=>'#','name'=>"Kategori"],
      ['link'=>'#','name'=>"Tambah"],
    ];

    return view('mimin.orang.pelanggan.kategori.create',
    compact('judul','judul_deskripsi','breadcrumbs','deskripsi')
    );

  }

  public function store (Request $req){

    $rules = [
      'nama' =>'required',
      'status' => 'required'
    ];
    $messages =[
        'nama.required'=>'Nama Kategori harus diisi',
        'status.required' => 'Status harus diiisi'
    ];

    $input= $req->all();
    $validator = Validator::make($input, $rules, $messages)->validate();


    $kategori = KategoriPelanggan::create([
        'nama'=>$req->nama,
        'keterangan'=>$req->keterangan,
        'status'=>$req->status
    ]);

    return redirect()->route('mimin.orang.pelanggan.kategori.index')->with('sukses','Tambah Kategori Sukses');
  }

  public function edit($id){
    $judul = "Kategori";
    $judul_deskripsi = "";
    $deskripsi = "";
    $breadcrumbs = [
      ['link'=>'#','name'=>"Orang"],
      ['link'=>'#','name'=>"Kategori"],
      ['link'=>'#','name'=>"Edit"],
    ];

    $kategori = KategoriPelanggan::findOrFail($id);

    return view('mimin.orang.pelanggan.kategori.edit',
    compact('judul','breadcrumbs','judul_deskripsi','deskripsi','kategori')
    );
  }

  public function update(Request $req, $id){

    $kategori=KategoriPelanggan::findOrFail($id);

    $rules = [
      'nama' =>'required',
      'status' => 'required'
    ];
    $messages =[
        'nama.required'=>'Nama Kategori harus diisi',
        'status.required' => 'Status harus diiisi'
    ];


    $input= $req->all();
    $validator = Validator::make($input, $rules, $messages)->validate();

    $kategori->nama = $req->nama;
    $kategori->status = $req->status;
    $kategori->keterangan = $req->keterangan;
    $kategori->save();

    return redirect()->route('mimin.orang.pelanggan.kategori.index')->with('sukses', $kategori->nama. 'Berhasil diubah');
 }

 public function destroy (Request $req, $id){

  try {
    $kategori = KategoriPelanggan::findOrFail($id);

    $nama = $kategori->nama;
    $result = $kategori->delete();
    if ($result){
      return response()->json([
          'judul' => 'Terhapus!',
          'pesan' => $nama.' Sukses Dihapus',
          'success' => true,
          'redirect'=> route('mimin.orang.pelanggan.kategori.index')
          ]);
    } else {
          return response()->json([
          'judul' => 'Gagal Terhapus',
          'pesan' => $nama.' Gagal Dihapus',
          'success' => false,
          'redirect'=> route('mimin.orang.pelanggan.kategori.index')
          ]);
    }

  } catch(\Exception $exception){
          return response()->json([
            'judul' =>'Gagal Dihapus',
            'pesan' => 'Terjadi kesalahan atau kategori masih terkait dengan data lain',
            'success' => false,
            'redirect'=> route('mimin.orang.pelanggan.kategori.index')
        ]);
  }

}
}
