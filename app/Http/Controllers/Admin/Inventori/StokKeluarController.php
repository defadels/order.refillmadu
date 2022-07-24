<?php

namespace App\Http\Controllers\Admin\Inventori;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\StokKeluarMasuk;
use Validator;
use App\Produk;
use Auth, Session, DB;
use App\Gudang;
use Carbon\Carbon;

class StokKeluarController extends Controller
{
  function __construct()
  {
       $this->middleware('permission:inventori.stokkeluar.lihat', ['only' => ['index','show']]);
       $this->middleware('permission:inventori.stokkeluar.post', ['only' => ['post','post_form']]);
       $this->middleware('permission:inventori.stokkeluar.edit', ['only' => ['edit','update','destroy']]);
       $this->middleware('permission:inventori.stokkeluar.tambah', ['only' => ['create','store']]);
  }
  public function index()
  {
    $judul = "Stok Keluar";
    $breadcrumbs = [
      ['link' => '#', 'name' => "Inventori"],
      ['link' => '#', 'name' => "Stok Keluar"],
    ];

    $daftar_stok_keluar = StokKeluarMasuk::with('gudang', 'daftar_detil.produk')
      ->keluar()
      ->orderBy('created_at', 'desc')
      ->simplePaginate(10);
    return view(
      'mimin.inventori.stok-keluar.index',
      compact('judul', 'breadcrumbs', 'daftar_stok_keluar')
    );
  }


  public function create()
  {
    $judul = "Stok Keluar";
    $judul_deskripsi = "";
    $deskripsi = "";
    $breadcrumbs = [
      ['link' => '#', 'name' => "Inventori"],
      ['link' => '#', 'name' => "Stok Keluar"],
      ['link' => '#', 'name' => "Tambah"],
    ];

    $daftar_produk = Produk::pluck('nama', 'id');
    $daftar_gudang = Gudang::where('status', 'Aktif')->pluck('nama', 'id');

    return view(
      'mimin.inventori.stok-keluar.create',
      compact('judul', 'judul_deskripsi', 'breadcrumbs', 'deskripsi', 'daftar_produk', 'daftar_gudang')
    );
  }

  public function store(Request $req)
  {
    $rules = [
      'keterangan' => 'required',
      'produk_id' => 'required',
      'gudang_id' => 'required'
    ];
    $messages = [
      'keterangan.required' => 'Keterangan harus diisi',
      'produk_id.required' => 'Produk yang akan dihasilkan harus diiisi',
      'gudang_id.required' => 'Gudang Tujuan harus diiisi'
    ];

    $input = $req->all();
    $validator = Validator::make($input, $rules, $messages)->validate();


    $daftar_produk = Produk::whereIn('id', $req->produk_id)->get();
    $daftar_detil_produk = collect([]);

    foreach ($daftar_produk as $produk) {
      $kuantitas_produk = 1;
      $daftar_detil_produk->push([
        'produk_id' => $produk->id,
        'kuantitas' => $kuantitas_produk
      ]);
    }

    DB::transaction(function () use ($daftar_detil_produk,$req) {
    $stok_keluar = StokKeluarMasuk::create([
      'keterangan' => $req->keterangan,
      'dibuat_oleh_id' => Auth::id(),
      'gudang_id' => $req->gudang_id,
      'keluar_masuk' => 'keluar',
      'status' => 'Draft'
    ]);
    $stok_keluar->daftar_detil()->createMany($daftar_detil_produk);
    });

    return redirect()->route('mimin.inventori.stok-keluar.index')->with('sukses', 'Tambah Draft Stok Keluar Sukses');
  }

  public function edit($id)
  {
    $judul = "Stok Keluar";
    $judul_deskripsi = "";
    $deskripsi = "";
    $breadcrumbs = [
      ['link' => '#', 'name' => "Inventori"],
      ['link' => '#', 'name' => "Stok Keluar"],
      ['link' => '#', 'name' => "Edit"],
    ];

    $stok_keluar = StokKeluarMasuk::keluar()->draft()->findOrFail($id);

    $daftar_gudang = Gudang::where('status', 'Aktif')->pluck('nama', 'id');

    return view(
      'mimin.inventori.stok-keluar.edit',
      compact('judul', 'breadcrumbs', 'judul_deskripsi', 'deskripsi', 'stok_keluar', 'daftar_gudang')
    );
  }

  public function update(Request $req, $id)
  {

    $stok_keluar = StokKeluarMasuk::keluar()->draft()->findOrFail($id);
    $rules = [
      'keterangan' => 'required',
      'gudang_id' => 'required',
      'tanggal' =>'required'
    ];
    $messages = [
      'keterangan.required' => 'Keterangan harus diisi',
      'gudang_id.required' => 'Gudang Tujuan harus diiisi'
    ];

    $tanggal = Carbon::createFromFormat('d-m-Y',$req->tanggal);

    $input = $req->all();
    $validator = Validator::make($input, $rules, $messages)->validate();

    $gudang = Gudang::findOrFail($req->gudang_id);
    $stok_keluar->gudang_id = $gudang->id;
    $stok_keluar->tanggal = $tanggal;
    $stok_keluar->keterangan = $req->keterangan;
    $stok_keluar->save();

    $daftar_detil = $stok_keluar->daftar_detil;

    if (count($req->kuantitas) == $daftar_detil->count()) {
      foreach ($daftar_detil as $i => $detil) {
        $stok_keluar->daftar_detil()
          ->where('produk_id', $detil->produk_id)
          ->update(['kuantitas' => $req->kuantitas[$i]]);
      }
    }

    return redirect()->route('mimin.inventori.stok-keluar.index')->with('sukses', $stok_keluar->nama . 'Berhasil diubah');
  }

  public function destroy(Request $req, $id)
  {

    try {
      $stok_keluar = StokKeluarMasuk::keluar()->draft()->findOrFail($id);

      $nama = 'Stok Keluar tanggal ' . $stok_keluar->created_at->format('d-m-Y');
      $result = $stok_keluar->delete();
      if ($result) {
        return response()->json([
          'judul' => 'Terhapus!',
          'pesan' => $nama . ' Sukses Dihapus',
          'success' => true,
          'redirect' => route('mimin.inventori.stok-keluar.index')
        ]);
      } else {
        return response()->json([
          'judul' => 'Gagal Terhapus',
          'pesan' => $nama . ' Gagal Dihapus',
          'success' => false,
          'redirect' => route('mimin.inventori.stok-keluar.index')
        ]);
      }
    } catch (\Exception $exception) {
      return response()->json([
        'judul' => 'Gagal Dihapus',
        'pesan' => 'Terjadi kesalahan atau stok_keluar masih terkait dengan data lain',
        'success' => false,
        'redirect' => route('mimin.inventori.stok-keluar.index')
      ]);
    }
  }

  public function post($id)
  {


    // keluarkan ke detil
    DB::transaction(function () use ($id) {

      $stok_keluar = StokKeluarMasuk::keluar()->draft()->findOrFail($id);



      // mencatat sejarah STOK YANG BERKUrang
      foreach ($stok_keluar->daftar_detil as $detil) {
        $stok_keluar->stok_detil()->create([
          'stok_awal' => $detil->produk->stok_gudang($stok_keluar->gudang_id),
          'produk_id' => $detil->produk_id,
          'gudang_id' => $stok_keluar->gudang_id,
          'kuantitas' => $detil->kuantitas,
          'keluar_masuk' => 'keluar',
          'harga_pokok' => $detil->produk->harga_pokok
        ]);
        $detil->produk->update_stok_gudang($stok_keluar->gudang_id, $detil->kuantitas, 'keluar');
      }
      // update informasi dari proses produksi
      $stok_keluar->diposting_oleh_id = Auth::id();
      $stok_keluar->posted_at = Carbon::now();
      $stok_keluar->status = "Posted";
      $stok_keluar->save();
    });


    return redirect()->route('mimin.inventori.stok-keluar.index')->with('sukses', 'Berhasil diposting');
  }

  public function show($id)
  {
    $judul = "Stok Keluar";
    $judul_deskripsi = "";
    $deskripsi = "";
    $breadcrumbs = [
      ['link' => '#', 'name' => "Inventori"],
      ['link' => '#', 'name' => "Stok Keluar"],
      ['link' => '#', 'name' => "Show"],
    ];

    $stok_keluar = StokKeluarMasuk::keluar()->posted()->findOrFail($id);


    return view(
      'mimin.inventori.stok-keluar.show',
      compact('judul', 'breadcrumbs', 'judul_deskripsi', 'deskripsi', 'stok_keluar')
    );
  }

  public function post_form($id)
  {
    $judul = "Stok Keluar";
    $judul_deskripsi = "";
    $deskripsi = "";
    $breadcrumbs = [
      ['link' => '#', 'name' => "Inventori"],
      ['link' => '#', 'name' => "Stok Keluar"],
      ['link' => '#', 'name' => "Post"],
    ];

    $stok_keluar = StokKeluarMasuk::keluar()->draft()->findOrFail($id);


    return view(
      'mimin.inventori.stok-keluar.post',
      compact('judul', 'breadcrumbs', 'judul_deskripsi', 'deskripsi', 'stok_keluar')
    );
  }
}
