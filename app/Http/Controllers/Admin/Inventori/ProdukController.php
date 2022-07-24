<?php

namespace App\Http\Controllers\Admin\Inventori;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Produk;
use Validator;
use App\KategoriProduk;

class ProdukController extends Controller
{
  function __construct()
  {
       $this->middleware('permission:inventori.produk.lihat', ['only' => ['index','show']]);
       $this->middleware('permission:inventori.produk.edit', ['only' => ['edit','update','destroy']]);
       $this->middleware('permission:inventori.produk.tambah', ['only' => ['create','store']]);
  }
  public function index(Request $req){
    $judul = "Produk";
    $breadcrumbs = [
      ['link'=>'#','name'=>"Inventori"],
      ['link'=>'#','name'=>"Produk"],
    ];

    $daftar_produk = Produk::with('kategori')->orderBy('nama')->paginate(10);
    $daftar_kategori = KategoriProduk::pluck('nama','id');
    $daftar_kategori->prepend('Semua','semua');


    $kategori = "semua";
    $cari = "";
    $daftar_produk = Produk::with('kategori');

    if ($req->has('cari')){
      $cari = $req->cari;
      $daftar_produk = $daftar_produk->where('nama','like','%'.$cari.'%');
    }

    if ($req->has('kategori')){
      $kategori = $req->kategori;
      if ($kategori != "semua"){
           $daftar_produk = $daftar_produk->where('kategori_id',$kategori);
      }
    }

    $daftar_produk = $daftar_produk->orderBy('nama')->paginate(10);


    return view('mimin.inventori.produk.index',
    compact('judul','breadcrumbs','daftar_produk','cari','kategori','daftar_kategori')
    );
  }


  public function create (){

    $judul = "Produk";
    $judul_deskripsi = "";
    $deskripsi = "";
    $breadcrumbs = [
      ['link'=>'#','name'=>"Inventori"],
      ['link'=>'#','name'=>"Produk"],
      ['link'=>'#','name'=>"Tambah"],
    ];

    $daftar_kategori = KategoriProduk::where('status','Aktif')->pluck("nama","id");
    return view('mimin.inventori.produk.create',
    compact('judul','judul_deskripsi','breadcrumbs','deskripsi','daftar_kategori')
    );

  }

  public function store (Request $req){

    $rules = [
      'nama' =>'required',
      'satuan' =>'required',
      'harga_jual' =>'required',
      'harga_pokok' =>'required',
      'harga_jual' =>'required|gt:harga_pokok',
      'status' => 'required'
    ];
    $messages =[
        'nama.required'=>'Nama Produk harus diisi',
        'status.required' => 'Status harus diiisi',
        'harga_jual.gt' =>'Harga Jual harus lebih besar dari harga pokok'
    ];

    $input= $req->all();
    $validator = Validator::make($input, $rules, $messages)->validate();

    $produk = Produk::create([
        'nama'=>$req->nama,
        'kategori_id'=>$req->kategori_id,
        'deskripsi'=>$req->deskripsi,
        'satuan'=>$req->satuan,
        'harga_jual'=>$req->harga_jual,
        'harga_pokok'=>$req->harga_pokok,
        'poin'=>$req->poin,
        'kode'=>$req->kode,
        'status'=>$req->status
    ]);

    return redirect()->route('mimin.inventori.produk.index')->with('sukses','Tambah Produk Sukses');
  }

  public function edit($id){
    $judul = "Produk";
    $judul_deskripsi = "";
    $deskripsi = "";
    $breadcrumbs = [
      ['link'=>'#','name'=>"Inventori"],
      ['link'=>'#','name'=>"Produk"],
      ['link'=>'#','name'=>"Edit"],
    ];

    $produk = Produk::findOrFail($id);

    $daftar_kategori = KategoriProduk::where('status','Aktif')->pluck("nama","id");

    return view('mimin.inventori.produk.edit',
    compact('judul','breadcrumbs','judul_deskripsi','deskripsi','produk','daftar_kategori')
    );
  }

  public function update(Request $req, $id){

    $produk=Produk::findOrFail($id);

    $rules = [
      'nama' =>'required',
      'status' => 'required',
      'kategori_id'=>'required',
      'satuan' =>'required',
      'harga_jual' =>'required|gt:harga_pokok',
      'harga_pokok' =>'required'
    ];
    $messages =[
        'nama.required'=>'Nama Produk harus diisi',
        'status.required' => 'Status harus diiisi',
        'harga_jual.gt' =>'Harga Jual harus lebih besar dari harga Pokok'
    ];


    $input= $req->all();
    $validator = Validator::make($input, $rules, $messages)->validate();

    $produk->nama = $req->nama;
    $produk->kategori_id = $req->kategori_id;
    $produk->deskripsi = $req->deskripsi;
    $produk->satuan = $req->satuan;
    $produk->harga_jual = $req->harga_jual;
    $produk->harga_pokok = $req->harga_pokok;
    $produk->poin = $req->poin;
    $produk->kode = $req->kode;
    $produk->status = $req->status;
    $produk->save();

    return redirect()->route('mimin.inventori.produk.index')->with('sukses', $produk->nama. 'Berhasil diubah');
 }

 public function destroy (Request $req, $id){

  try {
    $produk = Produk::findOrFail($id);

    $nama = $produk->nama;
    $result = $produk->delete();
    if ($result){
      return response()->json([
          'judul' => 'Terhapus!',
          'pesan' => $nama.' Sukses Dihapus',
          'success' => true,
          'redirect'=> route('mimin.inventori.produk.index')
          ]);
    } else {
          return response()->json([
          'judul' => 'Gagal Terhapus',
          'pesan' => $nama.' Gagal Dihapus',
          'success' => false,
          'redirect'=> route('mimin.inventori.produk.index')
          ]);
    }
  } catch(\Exception $exception){
          return response()->json([
            'judul' =>'Gagal Dihapus',
            'pesan' => 'Terjadi kesalahan atau produk masih terkait dengan data lain',
            'success' => false,
            'redirect'=> route('mimin.inventori.produk.index')
        ]);
  }

}

public function show($id){
  $judul = "RincianProduk";
  $judul_deskripsi = "";
  $deskripsi = "";
  $breadcrumbs = [
    ['link'=>'#','name'=>"Inventori"],
    ['link'=>'#','name'=>"Produk"],
    ['link'=>'#','name'=>"Rincian"],
  ];

  $produk = Produk::findOrFail($id);
  $daftar_harga=$produk->daftar_harga_khusus;
  $daftar_struktur=$produk->daftar_struktur;

  return view('mimin.inventori.produk.show',
  compact('judul','breadcrumbs','judul_deskripsi','deskripsi','produk','daftar_harga','daftar_struktur')
  );
}
}
