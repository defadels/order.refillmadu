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

class StokMasukController extends Controller
{
  function __construct()
  {
       $this->middleware('permission:inventori.stokmasuk.lihat', ['only' => ['index','show']]);
       $this->middleware('permission:inventori.stokmasuk.post', ['only' => ['post','post_form']]);
       $this->middleware('permission:inventori.stokmasuk.edit', ['only' => ['edit','update','destroy']]);
       $this->middleware('permission:inventori.stokmasuk.tambah', ['only' => ['create','store']]);
  }

  public function index()
  {
    $judul = "Stok Masuk";
    $breadcrumbs = [
      ['link' => '#', 'name' => "Inventori"],
      ['link' => '#', 'name' => "Stok Masuk"],
    ];

    $daftar_stok_masuk = StokKeluarMasuk::with('gudang', 'daftar_detil.produk')
      ->masuk()
      ->orderBy('created_at', 'desc')
      ->simplePaginate(10);
    return view(
      'mimin.inventori.stok-masuk.index',
      compact('judul', 'breadcrumbs', 'daftar_stok_masuk')
    );
  }


  public function create()
  {

    $judul = "Stok Masuk";
    $judul_deskripsi = "";
    $deskripsi = "";
    $breadcrumbs = [
      ['link' => '#', 'name' => "Inventori"],
      ['link' => '#', 'name' => "Stok Masuk"],
      ['link' => '#', 'name' => "Tambah"],
    ];

    $daftar_produk = Produk::pluck('nama', 'id');
    $daftar_gudang = Gudang::where('status', 'Aktif')->pluck('nama', 'id');

    return view(
      'mimin.inventori.stok-masuk.create',
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
    $stok_masuk = StokKeluarMasuk::create([
      'keterangan' => $req->keterangan,
      'dibuat_oleh_id' => Auth::id(),
      'gudang_id' => $req->gudang_id,
      'keluar_masuk' => 'masuk',
      'status' => 'Draft'
    ]);
    $stok_masuk->daftar_detil()->createMany($daftar_detil_produk);
    });

    return redirect()->route('mimin.inventori.stok-masuk.index')->with('sukses', 'Tambah Draft Stok Masuk Sukses');
  }

  public function edit($id)
  {
    $judul = "Stok Masuk";
    $judul_deskripsi = "";
    $deskripsi = "";
    $breadcrumbs = [
      ['link' => '#', 'name' => "Inventori"],
      ['link' => '#', 'name' => "Stok Masuk"],
      ['link' => '#', 'name' => "Edit"],
    ];

    $stok_masuk = StokKeluarMasuk::masuk()->draft()->findOrFail($id);

    $daftar_gudang = Gudang::where('status', 'Aktif')->pluck('nama', 'id');

    return view(
      'mimin.inventori.stok-masuk.edit',
      compact('judul', 'breadcrumbs', 'judul_deskripsi', 'deskripsi', 'stok_masuk', 'daftar_gudang')
    );
  }

  public function update(Request $req, $id)
  {



    $stok_masuk = StokKeluarMasuk::masuk()->draft()->findOrFail($id);

    $rules = [
      'keterangan' => 'required',
      'gudang_id' => 'required',
      'tanggal' => 'required'
    ];
    $messages = [
      'keterangan.required' => 'Keterangan harus diisi',
      'gudang_id.required' => 'Gudang Tujuan harus diiisi'
    ];


    $tanggal = Carbon::createFromFormat('d-m-Y',$req->tanggal);

    $input = $req->all();
    $validator = Validator::make($input, $rules, $messages)->validate();

    $gudang = Gudang::findOrFail($req->gudang_id);
    $stok_masuk->gudang_id = $gudang->id;
    $stok_masuk->keterangan = $req->keterangan;
    $stok_masuk->tanggal = $tanggal;
    $stok_masuk->save();

    $daftar_detil = $stok_masuk->daftar_detil;

    if (count($req->kuantitas) == $daftar_detil->count()) {
      foreach ($daftar_detil as $i => $detil) {
        $stok_masuk->daftar_detil()
          ->where('produk_id', $detil->produk_id)
          ->update(['kuantitas' => $req->kuantitas[$i]]);
      }
    }

    return redirect()->route('mimin.inventori.stok-masuk.index')->with('sukses', $stok_masuk->nama . 'Berhasil diubah');
  }

  public function destroy(Request $req, $id)
  {

    try {
      $stok_masuk = StokKeluarMasuk::masuk()->draft()->findOrFail($id);

      $nama = 'Stok Masuk tanggal ' . $stok_masuk->created_at->format('d-m-Y');
      $result = $stok_masuk->delete();
      if ($result) {
        return response()->json([
          'judul' => 'Terhapus!',
          'pesan' => $nama . ' Sukses Dihapus',
          'success' => true,
          'redirect' => route('mimin.inventori.stok-masuk.index')
        ]);
      } else {
        return response()->json([
          'judul' => 'Gagal Terhapus',
          'pesan' => $nama . ' Gagal Dihapus',
          'success' => false,
          'redirect' => route('mimin.inventori.stok-masuk.index')
        ]);
      }
    } catch (\Exception $exception) {
      return response()->json([
        'judul' => 'Gagal Dihapus',
        'pesan' => 'Terjadi kesalahan atau stok_masuk masih terkait dengan data lain',
        'success' => false,
        'redirect' => route('mimin.inventori.stok-masuk.index')
      ]);
    }
  }

  public function post($id)
  {


    // masukkan ke detil
    DB::transaction(function () use ($id) {

      $stok_masuk = StokKeluarMasuk::masuk()->draft()->findOrFail($id);



      // mencatat sejarah STOK YANG BERTAMBAH
      foreach ($stok_masuk->daftar_detil as $detil) {
        $stok_masuk->stok_detil()->create([
          'stok_awal' => $detil->produk->stok_gudang($stok_masuk->gudang_id),
          'produk_id' => $detil->produk_id,
          'gudang_id' => $stok_masuk->gudang_id,
          'kuantitas' => $detil->kuantitas,
          'keluar_masuk' => 'masuk',
          'harga_pokok' => $detil->produk->harga_pokok
        ]);
        $detil->produk->update_stok_gudang($stok_masuk->gudang_id, $detil->kuantitas, 'masuk');
      }
      // update informasi dari proses produksi
      $stok_masuk->diposting_oleh_id = Auth::id();
      $stok_masuk->posted_at = Carbon::now();
      $stok_masuk->status = "Posted";
      $stok_masuk->save();
    });


    return redirect()->route('mimin.inventori.stok-masuk.index')->with('sukses', 'Berhasil diposting');
  }

  public function show($id)
  {
    $judul = "Stok Masuk";
    $judul_deskripsi = "";
    $deskripsi = "";
    $breadcrumbs = [
      ['link' => '#', 'name' => "Inventori"],
      ['link' => '#', 'name' => "Stok Masuk"],
      ['link' => '#', 'name' => "Show"],
    ];

    $stok_masuk = StokKeluarMasuk::masuk()->posted()->findOrFail($id);


    return view(
      'mimin.inventori.stok-masuk.show',
      compact('judul', 'breadcrumbs', 'judul_deskripsi', 'deskripsi', 'stok_masuk')
    );
  }

  public function post_form($id)
  {
    $judul = "Stok Masuk";
    $judul_deskripsi = "";
    $deskripsi = "";
    $breadcrumbs = [
      ['link' => '#', 'name' => "Inventori"],
      ['link' => '#', 'name' => "Stok Masuk"],
      ['link' => '#', 'name' => "Post"],
    ];

    $stok_masuk = StokKeluarMasuk::masuk()->draft()->findOrFail($id);


    return view(
      'mimin.inventori.stok-masuk.post',
      compact('judul', 'breadcrumbs', 'judul_deskripsi', 'deskripsi', 'stok_masuk')
    );
  }


}
