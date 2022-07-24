<?php

namespace App\Http\Controllers\Admin\Inventori;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Produk;
use App\StrukturProduk;
use Validator,Auth,Session;

class StrukturProdukController extends Controller
{
  function __construct()
  {
       $this->middleware('permission:inventori.produk.lihat', ['only' => ['index','show']]);
       $this->middleware('permission:inventori.produk.edit', ['only' => ['edit','update','destroy']]);
       $this->middleware('permission:inventori.produk.tambah', ['only' => ['create','store']]);
  }

  public function create($id){
    $judul = "Struktur Produk";
    $judul_deskripsi = "";
    $deskripsi = "";
    $breadcrumbs = [
      ['link'=>'#','name'=>"Produk"],
      ['link'=>'#','name'=>"Struktur Produk"],
      ['link'=>'#','name'=>"Tambah"],
    ];

    $produk = Produk::findOrFail($id);
    $sudah_terpakai = $produk->daftar_struktur()->pluck('bahan_id');
    $sudah_terpakai->push($produk->id);

    $daftar_bahan = Produk::whereNotIn('id',$sudah_terpakai)
//    ->take(5)
    ->pluck('nama','id');

    return view('mimin.inventori.produk.struktur.create',
        compact('judul','judul_deskripsi','breadcrumbs','deskripsi','daftar_bahan','produk')
    );



  }
    //


    public function store (Request $req,$id){

      $rules = [
        'bahan_id' =>'required',
        'qty_bahan' => 'required|min:1',
        'qty_produk' => 'required|min:1'
      ];
      $messages =[
          'bahan_id.required'=>'Bahan harus diisi',
          'qty_bahan.required' => 'Kuantitas Bahan harus diisi',
          'qty_produk.required' => 'Kuantitas Produk harus diisi'
      ];

      $input= $req->all();
      $validator = Validator::make($input, $rules, $messages)->validate();


      $produk = Produk::findOrFail($id);
      $bahan = Produk::findOrFail($req->bahan_id);


      $kategori = StrukturProduk::create([
          'produk_id'=>$produk->id,
          'bahan_id'=>$bahan->id,
          'qty_bahan'=>$req->qty_bahan,
          'qty_produk'=>$req->qty_produk
      ]);

      return redirect()->route('mimin.inventori.produk.show',$produk->id)->with('sukses','Tambah Struktur sukses');
    }


    public function edit($id,$struktur_id){
      $judul = "Struktur";
      $judul_deskripsi = "";
      $deskripsi = "";
      $breadcrumbs = [
        ['link'=>'#','name'=>"Produk"],
        ['link'=>'#','name'=>"Struktur"],
        ['link'=>'#','name'=>"Edit"],
      ];

      $produk = Produk::findOrFail($id);
      $struktur = StrukturProduk::findOrFail($struktur_id);

      return view('mimin.inventori.produk.struktur.edit',
      compact('judul','judul_deskripsi','breadcrumbs','deskripsi','produk','struktur')
      );

    }

    public function update (Request $req,$id,$struktur_id){

      $rules = [
        'qty_bahan' => 'required|min:1',
        'qty_produk' => 'required|min:1'
      ];
      $messages =[
          'qty_bahan.required' => 'Kuantitas Bahan harus diisi',
          'qty_produk.required' => 'Kuantitas Produk harus diisi'
      ];

      $input= $req->all();
      $validator = Validator::make($input, $rules, $messages)->validate();


      $produk = Produk::findOrFail($id);
      $struktur = StrukturProduk::findOrFail($struktur_id);


      $struktur->qty_bahan = $req->qty_bahan;
      $struktur->qty_produk = $req->qty_produk;
      $struktur->save();

      return redirect()->route('mimin.inventori.produk.show',$produk->id)->with('sukses','Edit Struktur sukses');
    }


 public function destroy (Request $req, $id,$struktur_id){

  try {

    $produk = Produk::findOrFail($id);
    $struktur = StrukturProduk::findOrFail($struktur_id);

    $nama = "Bahan ".$struktur->bahan->nama;
    $result = $struktur->delete();

    if ($result){
      return response()->json([
          'judul' => 'Terhapus!',
          'pesan' => $nama.' Sukses Dihapus',
          'success' => true,
          'redirect'=> route('mimin.inventori.produk.show',$produk->id)
          ]);
    } else {
          return response()->json([
          'judul' => 'Gagal Terhapus',
          'pesan' => $nama.' Gagal Dihapus',
          'success' => false,
          'redirect'=> route('mimin.inventori.produk.show',$produk->id)
          ]);
    }
  } catch(\Exception $exception){
          return response()->json([
            'judul' =>'Gagal Dihapus',
            'pesan' => 'Terjadi kesalahan atau harga masih terkait dengan data lain',
            'success' => false,
            'redirect'=> route('mimin.inventori.produk.show',$produk->id)
        ]);
  }

}


}
