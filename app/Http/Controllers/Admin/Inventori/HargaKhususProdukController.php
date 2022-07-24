<?php

namespace App\Http\Controllers\Admin\Inventori;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Produk;
use App\HargaKhusus;
use App\KategoriPelanggan;
use Validator,Auth,Session;

class HargaKhususProdukController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:inventori.produk.lihat', ['only' => ['index','show']]);
        $this->middleware('permission:inventori.produk.edit', ['only' => ['edit','update','destroy']]);
        $this->middleware('permission:inventori.produk.tambah', ['only' => ['create','store']]);
    }

    public function create($id){
      $judul = "Harga Khusus";
      $judul_deskripsi = "";
      $deskripsi = "";
      $breadcrumbs = [
        ['link'=>'#','name'=>"Produk"],
        ['link'=>'#','name'=>"Harga Khusus"],
        ['link'=>'#','name'=>"Tambah"],
      ];

      $produk = Produk::findOrFail($id);
      $sudah_terpakai = $produk->daftar_harga_khusus()->pluck('kategori_id');
      $daftar_kategori = KategoriPelanggan::whereNotIn('id',$sudah_terpakai)->pluck('nama','id');

      return view('mimin.inventori.produk.harga.create',
      compact('judul','judul_deskripsi','breadcrumbs','deskripsi','daftar_kategori','produk')
      );



    }

    public function store (Request $req,$id){

      $rules = [
        'kategori_id' =>'required',
        'harga_jual' => 'required|min:0'
      ];
      $messages =[
          'kategori_id.required'=>'Kategori harus diisi',
          'harga_jual.required' => 'Harga Jual harus diisi'
      ];

      $input= $req->all();
      $validator = Validator::make($input, $rules, $messages)->validate();


      $produk = Produk::findOrFail($id);

      if($produk->harga_pokok > $req->harga_jual){
        return redirect()->back()->withInput()->withError('harga harus lebih besar dari harga pokok');
      }

      $kategori = HargaKhusus::create([
          'produk_id'=>$produk->id,
          'kategori_id'=>$req->kategori_id,
          'harga_jual'=>$req->harga_jual
      ]);

      return redirect()->route('mimin.inventori.produk.show',$produk->id)->with('sukses','Tambah Harga Khusus sukses');
    }

    public function edit($id,$harga_khusus_id){
      $judul = "Harga Khusus";
      $judul_deskripsi = "";
      $deskripsi = "";
      $breadcrumbs = [
        ['link'=>'#','name'=>"Produk"],
        ['link'=>'#','name'=>"Harga Khusus"],
        ['link'=>'#','name'=>"Edit"],
      ];

      $produk = Produk::findOrFail($id);
      $harga_khusus = HargaKhusus::findOrFail($harga_khusus_id);

      return view('mimin.inventori.produk.harga.edit',
      compact('judul','judul_deskripsi','breadcrumbs','deskripsi','produk','harga_khusus')
      );

    }

    public function update (Request $req,$id,$harga_khusus_id){

      $rules = [
        'harga_jual' => 'required|min:0'
      ];
      $messages =[
          'harga_jual.required' => 'Harga Jual harus diisi'
      ];

      $input= $req->all();
      $validator = Validator::make($input, $rules, $messages)->validate();


      $produk = Produk::findOrFail($id);
      $harga_khusus = HargaKhusus::findOrFail($harga_khusus_id);

      if($produk->harga_pokok > $req->harga_jual){
        return redirect()->back()->withInput()->withError('harga harus lebih besar dari harga pokok');
      }

      $harga_khusus->harga_jual = $req->harga_jual;
      $harga_khusus->save();

      return redirect()->route('mimin.inventori.produk.show',$produk->id)->with('sukses','Edit Harga Khusus sukses');
    }


 public function destroy (Request $req, $id,$harga_khusus_id){

  try {

    $produk = Produk::findOrFail($id);
    $harga_khusus = HargaKhusus::findOrFail($harga_khusus_id);

    $nama = "Harga untuk ".$harga_khusus->kategori->nama;
    $result = $harga_khusus->delete();

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
