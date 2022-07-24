<?php

namespace App\Http\Controllers\Admin\Inventori;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\RakitProduk;
use Validator;
use App\Produk;
use Auth,Session,DB;
use App\Gudang;
use Carbon\Carbon;


class RakitProdukController extends Controller
{
  function __construct()
  {
       $this->middleware('permission:inventori.rakitproduk.lihat', ['only' => ['index','show']]);
       $this->middleware('permission:inventori.rakitproduk.post', ['only' => ['post','post_form']]);
       $this->middleware('permission:inventori.rakitproduk.edit', ['only' => ['edit','update','destroy']]);
       $this->middleware('permission:inventori.rakitproduk.tambah', ['only' => ['create','store']]);
  }
  public function index(){
    $judul = "RakitProduk";
    $breadcrumbs = [
      ['link'=>'#','name'=>"Inventori"],
      ['link'=>'#','name'=>"RakitProduk"],
    ];

    $daftar_rakit_produk = RakitProduk::with('produk','gudang')->orderBy('created_at','desc')->simplePaginate(10);

    return view('mimin.inventori.rakit-produk.index',
    compact('judul','breadcrumbs','daftar_rakit_produk' )
    );
  }


  public function create (){

    $judul = "RakitProduk";
    $judul_deskripsi = "";
    $deskripsi = "";
    $breadcrumbs = [
      ['link'=>'#','name'=>"Inventori"],
      ['link'=>'#','name'=>"Rakit Produk"],
      ['link'=>'#','name'=>"Tambah"],
    ];

    $daftar_produk = Produk::has('daftar_struktur')->pluck('nama','id');
    $daftar_gudang = Gudang::where('status','Aktif')->pluck('nama','id');
    return view('mimin.inventori.rakit-produk.create',
    compact('judul','judul_deskripsi','breadcrumbs','deskripsi','daftar_produk','daftar_gudang')
    );

  }

  public function store (Request $req){

    $rules = [
      'keterangan' =>'required',
      'produk_id' =>'required',
      'stok_hasil' => 'required|min:1'
    ];
    $messages =[
        'keterangan.required'=>'Keterangan harus diisi',
        'produk_id.required' => 'Produk yang akan dihasilkan harus diiisi',
        'stok_hasil.required' => 'Stok yang akan dihasilkan harus diiisi'
    ];

    $input= $req->all();
    $validator = Validator::make($input, $rules, $messages)->validate();
    $produk = Produk::has('daftar_struktur')->findOrFail($req->produk_id);
    $minimal_qty = $produk->daftar_struktur()->max('qty_produk');

    if ($req->stok_hasil < $minimal_qty){
      return redirect()->back()->withInput()->withError('minimal '.$minimal_qty.' '.$produk->satuan.' '.$produk->nama.' yang harus dibuat' );
    }
    $daftar_detil_rakit = collect([]);
    foreach($produk->daftar_struktur as $struktur){
      $kuantitas_bahan = ceil($req->stok_hasil / $struktur->qty_produk * $struktur->qty_bahan);
      $daftar_detil_rakit->push([
        'bahan_id'=>$struktur->bahan_id,
        'kuantitas'=>$kuantitas_bahan
      ]);
    }
    DB::transaction(function () use ($daftar_detil_rakit,$req,$produk) {
    $rakit_produk = RakitProduk::create([
        'keterangan'=>$req->keterangan,
        'produk_id'=>$produk->id,
        'stok_hasil'=>$req->stok_hasil,
        'dibuat_oleh_id'=>Auth::id(),
        'gudang_id'=>$req->gudang_id,
        'status'=>'Draft'
    ]);
    $rakit_produk->daftar_detil()->createMany($daftar_detil_rakit);
    });

    return redirect()->route('mimin.inventori.rakit-produk.index')->with('sukses','Tambah Draft Rakit Produk Sukses');
  }

  public function edit($id){
    $judul = "RakitProduk";
    $judul_deskripsi = "";
    $deskripsi = "";
    $breadcrumbs = [
      ['link'=>'#','name'=>"Inventori"],
      ['link'=>'#','name'=>"RakitProduk"],
      ['link'=>'#','name'=>"Edit"],
    ];

    $rakit_produk = RakitProduk::draft()->findOrFail($id);

    $daftar_gudang = Gudang::where('status','Aktif')->pluck('nama','id');


    return view('mimin.inventori.rakit-produk.edit',
    compact('judul','breadcrumbs','judul_deskripsi','deskripsi','rakit_produk','daftar_gudang')
    );
  }

  public function update(Request $req, $id){



    $rakit_produk=RakitProduk::draft()->findOrFail($id);

    $rules = [
      'stok_hasil' =>'required|min:1',
      'gudang_id' => 'required',
      'tanggal' => 'required'
    ];
    $messages =[
        'stok_hasil.required'=>'Stok Hasil harus diisi',
        'gudang_id.required' => 'Gudang Tujuan harus diiisi'
    ];


    $input= $req->all();
    $validator = Validator::make($input, $rules, $messages)->validate();

    $tanggal = Carbon::createFromFormat('d-m-Y',$req->tanggal);

    $gudang = Gudang::findOrFail($req->gudang_id);

    $rakit_produk->gudang_id = $req->gudang_id;
    $rakit_produk->stok_hasil = $req->stok_hasil;
    $rakit_produk->keterangan = $req->keterangan;
    $rakit_produk->tanggal = $tanggal;

    $rakit_produk->save();
    $daftar_detil = $rakit_produk->daftar_detil;
    if (count($req->kuantitas) == $daftar_detil->count()){
    foreach($daftar_detil as $i=>$detil){
      $rakit_produk->daftar_detil()
      ->where('bahan_id',$detil->bahan_id)
      ->update(['kuantitas'=>$req->kuantitas[$i]]);
    }
  }

    return redirect()->route('mimin.inventori.rakit-produk.index')->with('sukses', $rakit_produk->nama. 'Berhasil diubah');
 }

 public function destroy (Request $req, $id){

  try {
    $rakit_produk = RakitProduk::draft()->findOrFail($id);

    $nama = $rakit_produk->nama;
    $result = $rakit_produk->delete();
    if ($result){
      return response()->json([
          'judul' => 'Terhapus!',
          'pesan' => $nama.' Sukses Dihapus',
          'success' => true,
          'redirect'=> route('mimin.inventori.rakit-produk.index')
          ]);
    } else {
          return response()->json([
          'judul' => 'Gagal Terhapus',
          'pesan' => $nama.' Gagal Dihapus',
          'success' => false,
          'redirect'=> route('mimin.inventori.rakit-produk.index')
          ]);
    }

  } catch(\Exception $exception){
          return response()->json([
            'judul' =>'Gagal Dihapus',
            'pesan' => 'Terjadi kesalahan atau rakit_produk masih terkait dengan data lain',
            'success' => false,
            'redirect'=> route('mimin.inventori.rakit-produk.index')
        ]);
  }}

  public function post($id){


    // masukkan ke detil
    DB::transaction(function () use ($id) {

    $rakit_produk=RakitProduk::draft()->findOrFail($id);

    $stok_awal = $rakit_produk->produk->stok;


    // mencatat sejarah STOK YANG BERKURANG
    foreach($rakit_produk->daftar_detil as $detil){
      $rakit_produk->stok_detil()->create([
        'stok_awal' => $detil->bahan->stok_gudang($rakit_produk->gudang_id),
        'produk_id' => $detil->bahan_id,
        'gudang_id' => $rakit_produk->gudang_id,
        'kuantitas' => $detil->kuantitas,
        'keluar_masuk' =>'keluar',
        'harga_pokok'=>$detil->bahan->harga_pokok
      ]);
      $detil->bahan->update_stok_gudang($rakit_produk->gudang_id, $detil->kuantitas, 'keluar');
      $rakit_produk->daftar_detil()->where('bahan_id',$detil->bahan_id)->update(['harga_pokok'=>$detil->bahan->harga_pokok]);
    }
    // hitung hpp baru

    // (hpp_lama * stok_lama + hpp_baru) / stok_lama+stok_baru
    $hpp = (
    ($stok_awal * $rakit_produk->produk->harga_pokok) +
    ($rakit_produk->daftar_detil()->sum(DB::raw('(rakit_produk_detil.harga_pokok * rakit_produk_detil.kuantitas)'))))
    /($rakit_produk->stok_hasil + $stok_awal);

    // update informasi dari proses produksi
    $rakit_produk->diposting_oleh_id = Auth::id();
    $rakit_produk->posted_at = Carbon::now();
    $rakit_produk->status = "Posted";
    $rakit_produk->harga_pokok_awal = $rakit_produk->produk->harga_pokok;
    $rakit_produk->harga_pokok_akhir = $hpp;
    $rakit_produk->save();


      // mencatat sejarah STOK YANG BERTAMBAH
      $rakit_produk->stok_detil()->create([
          'stok_awal' => $rakit_produk->produk->stok_gudang($rakit_produk->gudang_id),
          'produk_id' => $rakit_produk->produk->id,
          'gudang_id' => $rakit_produk->gudang_id,
          'kuantitas' => $rakit_produk->stok_hasil,
          'keluar_masuk' =>'masuk',
          'harga_pokok' => $hpp
      ]);
      $rakit_produk->produk->update_stok_gudang($rakit_produk->gudang_id,$rakit_produk->stok_hasil,'masuk');

      // update hpp produk yang dihasilkan
      $rakit_produk->produk->harga_pokok = $hpp;
      $rakit_produk->produk->save();

    });


    return redirect()->route('mimin.inventori.rakit-produk.index')->with('sukses', 'Berhasil diposting');
 }

 public function show($id){
  $judul = "Rakit Produk";
  $judul_deskripsi = "";
  $deskripsi = "";
  $breadcrumbs = [
    ['link'=>'#','name'=>"Inventori"],
    ['link'=>'#','name'=>"Rakit Produk"],
    ['link'=>'#','name'=>"Show"],
  ];

  $rakit_produk = RakitProduk::posted()->findOrFail($id);


  return view('mimin.inventori.rakit-produk.show',
  compact('judul','breadcrumbs','judul_deskripsi','deskripsi','rakit_produk')
  );
}

public function post_form($id){
  $judul = "Rakit Produk";
  $judul_deskripsi = "";
  $deskripsi = "";
  $breadcrumbs = [
    ['link'=>'#','name'=>"Inventori"],
    ['link'=>'#','name'=>"Rakit Produk"],
    ['link'=>'#','name'=>"Post"],
  ];

  $rakit_produk = RakitProduk::draft()->findOrFail($id);


  return view('mimin.inventori.rakit-produk.post',
  compact('judul','breadcrumbs','judul_deskripsi','deskripsi','rakit_produk')
  );
}


}
