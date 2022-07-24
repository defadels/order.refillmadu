<?php

namespace App\Http\Controllers\Admin\Inventori;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\KategoriProduk;
use Validator;

class KategoriProdukController extends Controller
{
  function __construct()
  {
       $this->middleware('permission:inventori.produk.kategori.lihat', ['only' => ['index','show']]);
       $this->middleware('permission:inventori.produk.kategori.edit', ['only' => ['edit','update','destroy']]);
       $this->middleware('permission:inventori.produk.kategori.tambah', ['only' => ['create','store']]);
  }
  public function index(){
    $judul = "Kategori";
    $breadcrumbs = [
      ['link'=>'#','name'=>"Inventori"],
      ['link'=>'#','name'=>"Kategori"],
    ];

    $daftar_kategori = KategoriProduk::paginate(10);




    return view('mimin.inventori.produk.kategori.index',
    compact('judul','breadcrumbs','daftar_kategori')
    );
  }


  public function create (){

    $judul = "Kategori";
    $judul_deskripsi = "";
    $deskripsi = "";
    $breadcrumbs = [
      ['link'=>'#','name'=>"Inventori"],
      ['link'=>'#','name'=>"Kategori"],
      ['link'=>'#','name'=>"Tambah"],
    ];

    return view('mimin.inventori.produk.kategori.create',
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


    $kategori = KategoriProduk::create([
        'nama'=>$req->nama,
        'status'=>$req->status
    ]);

    return redirect()->route('mimin.inventori.produk.kategori.index')->with('sukses','Tambah Kategori Sukses');
  }

  public function edit($id){
    $judul = "Kategori";
    $judul_deskripsi = "";
    $deskripsi = "";
    $breadcrumbs = [
      ['link'=>'#','name'=>"Inventori"],
      ['link'=>'#','name'=>"Kategori"],
      ['link'=>'#','name'=>"Edit"],
    ];

    $kategori = KategoriProduk::findOrFail($id);

    return view('mimin.inventori.produk.kategori.edit',
    compact('judul','breadcrumbs','judul_deskripsi','deskripsi','kategori')
    );
  }

  public function update(Request $req, $id){

    $kategori=KategoriProduk::findOrFail($id);

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
    $kategori->save();

    return redirect()->route('mimin.inventori.produk.kategori.index')->with('sukses', $kategori->nama. 'Berhasil diubah');
 }

 public function destroy (Request $req, $id){

  try {
    $kategori = KategoriProduk::findOrFail($id);

    $nama = $kategori->nama;
    $result = $kategori->delete();
    if ($result){
      return response()->json([
          'judul' => 'Terhapus!',
          'pesan' => $nama.' Sukses Dihapus',
          'success' => true,
          'redirect'=> route('mimin.inventori.produk.kategori.index')
          ]);
    } else {
          return response()->json([
          'judul' => 'Gagal Terhapus',
          'pesan' => $nama.' Gagal Dihapus',
          'success' => false,
          'redirect'=> route('mimin.inventori.produk.kategori.index')
          ]);
    }

  } catch(\Exception $exception){
          return response()->json([
            'judul' =>'Gagal Dihapus',
            'pesan' => 'Terjadi kesalahan atau kategori masih terkait dengan data lain',
            'success' => false,
            'redirect'=> route('mimin.inventori.produk.kategori.index')
        ]);
  }

}
}
