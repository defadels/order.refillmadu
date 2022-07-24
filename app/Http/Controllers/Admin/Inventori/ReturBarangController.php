<?php

namespace App\Http\Controllers\Admin\Inventori;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\ReturBarang;
use Validator;
use App\Produk;
use Auth, Session, DB;
use App\Gudang;
use Carbon\Carbon;
use App\Pelanggan;
use App\Kas;
use App\TransaksiKas;
use App\User;
use App\Dompet;

class ReturBarangController extends Controller
{
  function __construct()
  {
       $this->middleware('permission:inventori.returbarang.lihat', ['only' => ['index','show']]);
       $this->middleware('permission:inventori.returbarang.post', ['only' => ['post','post_form']]);
       $this->middleware('permission:inventori.returbarang.edit', ['only' => ['edit','update','destroy']]);
       $this->middleware('permission:inventori.returbarang.tambah', ['only' => ['create','store']]);
  }

  public function index()
  {
    $judul = "Retur Barang";
    $breadcrumbs = [
      ['link' => '#', 'name' => "Inventori"],
      ['link' => '#', 'name' => "Retur Barang"],
    ];

    $daftar_retur_barang = ReturBarang::with('gudang', 'daftar_detil.produk')

      ->orderBy('created_at', 'desc')
      ->simplePaginate(10);
    return view(
      'mimin.inventori.retur-barang.index',
      compact('judul', 'breadcrumbs', 'daftar_retur_barang')
    );
  }


  public function create()
  {

    $judul = "Retur Barang";
    $judul_deskripsi = "";
    $deskripsi = "";
    $breadcrumbs = [
      ['link' => '#', 'name' => "Inventori"],
      ['link' => '#', 'name' => "Retur Barang"],
      ['link' => '#', 'name' => "Tambah"],
    ];

    $daftar_produk = Produk::pluck('nama', 'id');
    $daftar_gudang = Gudang::where('status', 'Aktif')->pluck('nama', 'id');

    $pelanggan_lama_id = Session::get('_old_input.pelanggan_id');

    $daftar_pelanggan = Pelanggan::where('id',$pelanggan_lama_id)->pluck('nama', 'id');

    $daftar_kas = Kas::pluck('nama', 'id');

    return view(
      'mimin.inventori.retur-barang.create',
      compact('judul', 'judul_deskripsi', 'breadcrumbs', 'deskripsi', 'daftar_produk', 'daftar_gudang', 'daftar_pelanggan', 'daftar_kas')
    );
  }

  public function store(Request $req)
  {
    $rules = [
      'keterangan'    => 'required',
      'produk_id'     => 'required',
      'gudang_id'     => 'required',
      'pelanggan_id'  => 'required',
      'nominal'       => 'required|min:1',
      'bayar_dengan'  => 'required',
      'kas_id'        => 'required_if:bayar_dengan,cash'
    ];
    $messages = [
      'keterangan.required' => 'Keterangan harus diisi',
      'produk_id.required' => 'Produk yang akan dihasilkan harus diiisi',
      'gudang_id.required' => 'Gudang Tujuan harus diiisi',
      'pelanggan_id.required'=>'pelanggan harus diisi',
      'nominal.required'=>'nominal harus diisi',
      'nominal.min'=>'isi nominal minimal 1 rupiah',
      'bayar_dengan.required'=>'pilih mau dibayar dengan apa',
      'kas_id.required_if'=>'kas harus diisi apabila dibayar dengan cash'
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

    DB::transaction(function () use ($req,$daftar_detil_produk) {
    $retur_barang = ReturBarang::create([
      'keterangan' => $req->keterangan,
      'dibuat_oleh_id' => Auth::id(),
      'gudang_id' => $req->gudang_id,
      'bayar_dengan' => $req->bayar_dengan,
      'pelanggan_id' =>$req->pelanggan_id,
      'kas_asal_id'=>$req->kas_id,
      'nominal'=>$req->nominal,
      'status' => 'Draft'
    ]);
    $retur_barang->daftar_detil()->createMany($daftar_detil_produk);
    });

    return redirect()->route('mimin.inventori.retur-barang.index')->with('sukses', 'Tambah Draft Retur Barang Sukses');
  }

  public function edit($id)
  {
    $judul = "Retur Barang";
    $judul_deskripsi = "";
    $deskripsi = "";
    $breadcrumbs = [
      ['link' => '#', 'name' => "Inventori"],
      ['link' => '#', 'name' => "Retur Barang"],
      ['link' => '#', 'name' => "Edit"],
    ];

    $retur_barang = ReturBarang::draft()->findOrFail($id);

    $daftar_gudang = Gudang::where('status', 'Aktif')->pluck('nama', 'id');

    $pelanggan_lama_id = Session::get('_old_input.pelanggan_id',$retur_barang->pelanggan_id);
    $daftar_pelanggan = Pelanggan::where('id',$pelanggan_lama_id)->pluck('nama', 'id');

    $daftar_kas = Kas::pluck('nama', 'id');


    return view(
      'mimin.inventori.retur-barang.edit',
      compact('judul', 'breadcrumbs', 'judul_deskripsi', 'deskripsi', 'retur_barang', 'daftar_gudang','daftar_pelanggan','daftar_kas')
    );
  }

  public function update(Request $req, $id)
  {



    $retur_barang = ReturBarang::draft()->findOrFail($id);

    $rules = [
      'keterangan' => 'required',
      'gudang_id' => 'required',
      'tanggal' => 'required'
    ];
    $messages = [
      'keterangan.required' => 'Keterangan harus diisi',
      'gudang_id.required' => 'Gudang Tujuan harus diiisi'
    ];


    $input = $req->all();
    $validator = Validator::make($input, $rules, $messages)->validate();

    $tanggal = Carbon::createFromFormat('d-m-Y',$req->tanggal);
    $gudang = Gudang::findOrFail($req->gudang_id);
    $retur_barang->gudang_id = $gudang->id;
    $retur_barang->keterangan = $req->keterangan;
    $retur_barang->pelanggan_id = $req->pelanggan_id;
    $retur_barang->nominal = $req->nominal;
    $retur_barang->bayar_dengan = $req->bayar_dengan;
    $retur_barang->kas_asal_id = $req->kas_id;
    $retur_barang->tanggal = $tanggal;
    $retur_barang->save();

    $daftar_detil = $retur_barang->daftar_detil;

    if (count($req->kuantitas) == $daftar_detil->count()) {
      foreach ($daftar_detil as $i => $detil) {
        $retur_barang->daftar_detil()
          ->where('produk_id', $detil->produk_id)
          ->update(['kuantitas' => $req->kuantitas[$i]]);
      }
    }

    return redirect()->route('mimin.inventori.retur-barang.index')->with('sukses', $retur_barang->nama . 'Berhasil diubah');
  }

  public function destroy(Request $req, $id)
  {

    try {
      $retur_barang = ReturBarang::draft()->findOrFail($id);

      $nama = 'Retur Barang tanggal ' . $retur_barang->created_at->format('d-m-Y');
      $result = $retur_barang->delete();
      if ($result) {
        return response()->json([
          'judul' => 'Terhapus!',
          'pesan' => $nama . ' Sukses Dihapus',
          'success' => true,
          'redirect' => route('mimin.inventori.retur-barang.index')
        ]);
      } else {
        return response()->json([
          'judul' => 'Gagal Terhapus',
          'pesan' => $nama . ' Gagal Dihapus',
          'success' => false,
          'redirect' => route('mimin.inventori.retur-barang.index')
        ]);
      }
    } catch (\Exception $exception) {
      return response()->json([
        'judul' => 'Gagal Dihapus',
        'pesan' => 'Terjadi kesalahan atau retur_barang masih terkait dengan data lain',
        'success' => false,
        'redirect' => route('mimin.inventori.retur-barang.index')
      ]);
    }
  }

  public function post($id)
  {


    // masukkan ke detil
    DB::transaction(function () use ($id) {

      $retur_barang = ReturBarang::draft()->findOrFail($id);
      $sekarang = Carbon::now();

      // mencatat sejarah STOK YANG BERTAMBAH
      foreach ($retur_barang->daftar_detil as $detil) {
        $retur_barang->stok_detil()->create([
          'stok_awal' => $detil->produk->stok_gudang($retur_barang->gudang_id),
          'produk_id' => $detil->produk_id,
          'gudang_id' => $retur_barang->gudang_id,
          'kuantitas' => $detil->kuantitas,
          'keluar_masuk' => 'masuk',
          'harga_pokok' => $detil->produk->harga_pokok
        ]);
        $detil->produk->update_stok_gudang($retur_barang->gudang_id, $detil->kuantitas, 'masuk');
      }
      // mencatat sejarah dompet yang bertambah
      if ($retur_barang->bayar_dengan == "dompet"){
        $saldo_akhir = $retur_barang->pelanggan->saldo_tanggal($sekarang->copy());
        $saldo_berjalan = $saldo_akhir + $retur_barang->nominal;
        Dompet::create([
          'tanggal'=>$sekarang,
          'nominal'=>$retur_barang->nominal,
          'keterangan'=>$retur_barang->keterangan,
          'dibayar_oleh'=> $retur_barang->diposting_oleh->nama,
          'dibayar_kepada'=> $retur_barang->pelanggan->nama,
          'user_id'=>$retur_barang->pelanggan->id,
          'saldo_berjalan'=>$saldo_berjalan,
          'debet_kredit'=>'debet'
        ]);
        $retur_barang->pelanggan->update_saldo($sekarang->copy(),$retur_barang->nominal,'d');
      }
      else if ($retur_barang->bayar_dengan == "cash"){
        // mencatat sejarah kas yang keluar
        $trx_kas = TransaksiKas::create([
          'kas_id'=>$retur_barang->kas_asal->id,
          'tanggal'=> $sekarang,
          'keterangan'=>$retur_barang->keterangan,
          'debet_kredit'=>'k',
          'nominal'=>$retur_barang->nominal
        ]);
        $retur_barang->kas_asal->update_saldo($sekarang->copy(),$retur_barang->nominal,'k');
        $retur_barang->transaksi_kas_id = $trx_kas->id;
      }
      // update informasi dari proses produksi
      $retur_barang->diposting_oleh_id = Auth::id();
      $retur_barang->posted_at = $sekarang;
      $retur_barang->status = "Posted";
      $retur_barang->save();
    });


    return redirect()->route('mimin.inventori.retur-barang.index')->with('sukses', 'Berhasil diposting');
  }

  public function show($id)
  {
    $judul = "Retur Barang";
    $judul_deskripsi = "";
    $deskripsi = "";
    $breadcrumbs = [
      ['link' => '#', 'name' => "Inventori"],
      ['link' => '#', 'name' => "Retur Barang"],
      ['link' => '#', 'name' => "Show"],
    ];

    $retur_barang = ReturBarang::posted()->findOrFail($id);


    return view(
      'mimin.inventori.retur-barang.show',
      compact('judul', 'breadcrumbs', 'judul_deskripsi', 'deskripsi', 'retur_barang')
    );
  }

  public function post_form($id)
  {
    $judul = "Retur Barang";
    $judul_deskripsi = "";
    $deskripsi = "";
    $breadcrumbs = [
      ['link' => '#', 'name' => "Inventori"],
      ['link' => '#', 'name' => "Retur Barang"],
      ['link' => '#', 'name' => "Post"],
    ];

    $retur_barang = ReturBarang::draft()->findOrFail($id);


    return view(
      'mimin.inventori.retur-barang.post',
      compact('judul', 'breadcrumbs', 'judul_deskripsi', 'deskripsi', 'retur_barang')
    );
  }

  public function cari_pelanggan(Request $req)
  {

    $key = $req->cari;
    if ($req->has("cari") && $req->cari != "") {

      $daftar_kegiatan = User::where(function ($query) use ($key) {
        $query->where('nama', 'like', '%' . $key . '%');
      })->orWhere('email', $key)
        ->orWhere('nomor_hp', $key)
        ->paginate(5);
    } else {
      $daftar_kegiatan = Pelanggan::paginate(5);
    }
    $results = array(
      "results" => $daftar_kegiatan->toArray()['data'],
      "pagination" => array(
        "more" => $daftar_kegiatan->hasMorePages()
      )
    );

    return response()->json($results);
  }
}
