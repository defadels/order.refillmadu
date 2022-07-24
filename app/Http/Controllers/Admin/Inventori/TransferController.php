<?php

namespace App\Http\Controllers\Admin\Inventori;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\TransferStok;
use Validator;
use App\Produk;
use Auth, Session, DB;
use App\Gudang;
use Carbon\Carbon;


class TransferController extends Controller
{
  function __construct()
  {
       $this->middleware('permission:inventori.transferstok.lihat', ['only' => ['index','show']]);
       $this->middleware('permission:inventori.transferstok.post', ['only' => ['post','post_form']]);
       $this->middleware('permission:inventori.transferstok.edit', ['only' => ['edit','update','destroy']]);
       $this->middleware('permission:inventori.transferstok.tambah', ['only' => ['create','store']]);
  }
  public function index()
  {
    $judul = "Transfer Stok";
    $breadcrumbs = [
      ['link' => '#', 'name' => "Inventori"],
      ['link' => '#', 'name' => "Transfer Stok"],
    ];

    $daftar_transfer = TransferStok::with('gudang_asal','gudang_tujuan', 'daftar_detil.produk')
      ->orderBy('created_at', 'desc')
      ->simplePaginate(10);
    return view(
      'mimin.inventori.transfer.index',
      compact('judul', 'breadcrumbs', 'daftar_transfer')
    );
  }


  public function create()
  {

    $judul = "Transfer Stok";
    $judul_deskripsi = "";
    $deskripsi = "";
    $breadcrumbs = [
      ['link' => '#', 'name' => "Inventori"],
      ['link' => '#', 'name' => "Transfer Stok"],
      ['link' => '#', 'name' => "Tambah"],
    ];

    $daftar_produk = Produk::pluck('nama', 'id');
    $daftar_gudang = Gudang::where('status', 'Aktif')->pluck('nama', 'id');

    return view(
      'mimin.inventori.transfer.create',
      compact('judul', 'judul_deskripsi', 'breadcrumbs', 'deskripsi', 'daftar_produk', 'daftar_gudang')
    );
  }

  public function store(Request $req)
  {

    $rules = [
      'keterangan' => 'required',
      'produk_id' => 'required',
      'gudang_asal_id' => 'required|different:gudang_tujuan_id',
      'gudang_tujuan_id' => 'required|different:gudang_asal_id'
    ];
    $messages = [
      'keterangan.required' => 'Keterangan harus diisi',
      'produk_id.required' => 'Produk yang akan dihasilkan harus diiisi',
      'gudang_asal_id.required' => 'Gudang Asal harus diiisi',
      'gudang_tujuan_id.required' => 'Gudang Tujuan harus diiisi',
      'gudang_asal_id.different' =>'Asal dan Tujuan Gudang tidak boleh sama',
      'gudang_tujuan_id.different' =>'Asal dan Tujuan Gudang tidak boleh sama',
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
    $transfer = TransferStok::create([
      'keterangan' => $req->keterangan,
      'dibuat_oleh_id' => Auth::id(),
      'gudang_asal_id' => $req->gudang_asal_id,
      'gudang_tujuan_id' => $req->gudang_tujuan_id,
      'status' => 'Draft'
    ]);
    $transfer->daftar_detil()->createMany($daftar_detil_produk);
    });

    return redirect()->route('mimin.inventori.transfer.index')->with('sukses', 'Tambah Draft Transfer Stok Sukses');
  }

  public function edit($id)
  {
    $judul = "Transfer Stok";
    $judul_deskripsi = "";
    $deskripsi = "";
    $breadcrumbs = [
      ['link' => '#', 'name' => "Inventori"],
      ['link' => '#', 'name' => "Transfer Stok"],
      ['link' => '#', 'name' => "Edit"],
    ];

    $transfer = TransferStok::draft()->findOrFail($id);

    $daftar_gudang = Gudang::where('status', 'Aktif')->pluck('nama', 'id');

    return view(
      'mimin.inventori.transfer.edit',
      compact('judul', 'breadcrumbs', 'judul_deskripsi', 'deskripsi', 'transfer', 'daftar_gudang')
    );
  }

  public function update(Request $req, $id)
  {



    $transfer = TransferStok::draft()->findOrFail($id);

    $rules = [
      'keterangan' => 'required',
      'gudang_asal_id' => 'required|different:gudang_tujuan_id',
      'gudang_tujuan_id' => 'required|different:gudang_asal_id',
      'tanggal'=>'required'
    ];
    $messages = [
      'keterangan.required' => 'Keterangan harus diisi',
      'gudang_asal_id.required' => 'Gudang Asal harus diiisi',
      'gudang_tujuan_id.required' => 'Gudang Tujuan harus diiisi',
      'gudang_asal_id.different' =>'Asal dan Tujuan Gudang tidak boleh sama',
      'gudang_tujuan_id.different' =>'Asal dan Tujuan Gudang tidak boleh sama',
    ];


    $input = $req->all();
    $validator = Validator::make($input, $rules, $messages)->validate();


    $tanggal = Carbon::createFromFormat('d-m-Y',$req->tanggal);

    $gudang_asal = Gudang::findOrFail($req->gudang_asal_id);
    $gudang_tujuan = Gudang::findOrFail($req->gudang_tujuan_id);

    $transfer->gudang_asal_id = $gudang_asal->id;
    $transfer->gudang_tujuan_id = $gudang_tujuan->id;
    $transfer->keterangan = $req->keterangan;
    $transfer->tanggal = $tanggal;
    $transfer->save();

    $daftar_detil = $transfer->daftar_detil;

    if (count($req->kuantitas) == $daftar_detil->count()) {
      foreach ($daftar_detil as $i => $detil) {
        $transfer->daftar_detil()
          ->where('produk_id', $detil->produk_id)
          ->update(['kuantitas' => $req->kuantitas[$i]]);
      }
    }

    return redirect()->route('mimin.inventori.transfer.index')->with('sukses', 'Berhasil diubah');
  }

  public function destroy(Request $req, $id)
  {

    try {
      $transfer = TransferStok::draft()->findOrFail($id);

      $nama = 'Transfer Stok tanggal ' . $transfer->created_at->format('d-m-Y');
      $result = $transfer->delete();
      if ($result) {
        return response()->json([
          'judul' => 'Terhapus!',
          'pesan' => $nama . ' Sukses Dihapus',
          'success' => true,
          'redirect' => route('mimin.inventori.transfer.index')
        ]);
      } else {
        return response()->json([
          'judul' => 'Gagal Terhapus',
          'pesan' => $nama . ' Gagal Dihapus',
          'success' => false,
          'redirect' => route('mimin.inventori.transfer.index')
        ]);
      }
    } catch (\Exception $exception) {
      return response()->json([
        'judul' => 'Gagal Dihapus',
        'pesan' => 'Terjadi kesalahan atau transfer masih terkait dengan data lain',
        'success' => false,
        'redirect' => route('mimin.inventori.transfer.index')
      ]);
    }
  }

  public function post($id)
  {


    // masukkan ke detil
    DB::transaction(function () use ($id) {

      $transfer = TransferStok::draft()->findOrFail($id);

      // mencatat sejarah STOK YANG BERTAMBAH
      foreach ($transfer->daftar_detil as $detil) {
        $transfer->stok_detil()->create([
          'stok_awal' => $detil->produk->stok_gudang($transfer->gudang_asal_id),
          'produk_id' => $detil->produk_id,
          'gudang_id' => $transfer->gudang_asal_id,
          'kuantitas' => $detil->kuantitas,
          'keluar_masuk' => 'keluar',
          'harga_pokok' => $detil->produk->harga_pokok
        ]);
        $detil->produk->update_stok_gudang($transfer->gudang_asal_id, $detil->kuantitas, 'keluar');
      }

      // mencatat sejarah STOK YANG BERTAMBAH
      foreach ($transfer->daftar_detil as $detil) {
        $transfer->stok_detil()->create([
          'stok_awal' => $detil->produk->stok_gudang($transfer->gudang_tujuan_id),
          'produk_id' => $detil->produk_id,
          'gudang_id' => $transfer->gudang_tujuan_id,
          'kuantitas' => $detil->kuantitas,
          'keluar_masuk' => 'masuk',
          'harga_pokok' => $detil->produk->harga_pokok
        ]);
        $detil->produk->update_stok_gudang($transfer->gudang_tujuan_id, $detil->kuantitas, 'masuk');
      }

      // update informasi dari proses produksi
      $transfer->diposting_oleh_id = Auth::id();
      $transfer->posted_at = Carbon::now();
      $transfer->status = "Posted";
      $transfer->save();
    });


    return redirect()->route('mimin.inventori.transfer.index')->with('sukses', 'Berhasil diposting');
  }

  public function show($id)
  {
    $judul = "Transfer Stok";
    $judul_deskripsi = "";
    $deskripsi = "";
    $breadcrumbs = [
      ['link' => '#', 'name' => "Inventori"],
      ['link' => '#', 'name' => "Transfer Stok"],
      ['link' => '#', 'name' => "Show"],
    ];

    $transfer = TransferStok::posted()->findOrFail($id);


    return view(
      'mimin.inventori.transfer.show',
      compact('judul', 'breadcrumbs', 'judul_deskripsi', 'deskripsi', 'transfer')
    );
  }

  public function post_form($id)
  {
    $judul = "Transfer Stok";
    $judul_deskripsi = "";
    $deskripsi = "";
    $breadcrumbs = [
      ['link' => '#', 'name' => "Inventori"],
      ['link' => '#', 'name' => "Transfer Stok"],
      ['link' => '#', 'name' => "Post"],
    ];

    $transfer = TransferStok::draft()->findOrFail($id);


    return view(
      'mimin.inventori.transfer.post',
      compact('judul', 'breadcrumbs', 'judul_deskripsi', 'deskripsi', 'transfer')
    );
  }
}
