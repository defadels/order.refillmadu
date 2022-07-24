<?php

namespace App\Http\Controllers\Admin\Pesanan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Pesanan;
use Validator;
use App\Produk;
use Auth, Session, DB;
use App\Gudang;
use Carbon\Carbon;
use App\Pelanggan;
use App\Cabang;
use App\MetodePembayaran;
use App\User;
use App\AlamatPelanggan;
use App\MetodePengiriman;
use Str;

class MasukController extends Controller
{

  function __construct()
  {
       $this->middleware('permission:pesanan.masuk.lihat', ['only' => ['index','show']]);
       $this->middleware('permission:pesanan.masuk.tambah', ['only' => ['create','create2','store','cari_produk','cari_pelanggan','harga','cari_alamat']]);
       $this->middleware('permission:pesanan.masuk.edit', ['only' => ['edit','update','cari_produk','cari_pelanggan','harga','cari_alamat']]);

       $this->middleware('permission:pesanan.masuk.batalkan', ['only' => ['destroy']]);
       $this->middleware('permission:pesanan.masuk.proses', ['only' => ['destroy']]);
       $this->middleware('permission:pesanan.masuk.tunda', ['only' => ['destroy']]);

  }
  public function index()
  {
    $judul = "Pesanan Masuk";
    $breadcrumbs = [
      ['link' => '#', 'name' => "Pesanan"],
      ['link' => '#', 'name' => "Masuk"],
    ];

    $daftar_pesanan = Pesanan::masuk()->with('pelanggan', 'metode_pembayaran')
      ->orderBy('created_at', 'desc')
      ->simplePaginate(10);
    return view(
      'mimin.pesanan.masuk.index',
      compact('judul', 'breadcrumbs', 'daftar_pesanan')
    );
  }

  public function create()
  {

    $judul = "Pesanan Masuk";
    $judul_deskripsi = "";
    $deskripsi = "";
    $breadcrumbs = [
      ['link' => '#', 'name' => "Pesanan"],
      ['link' => '#', 'name' => "Masuk"],
      ['link' => '#', 'name' => "Tambah (1/2)"],
    ];

    $daftar_pelanggan = [];
    $daftar_pembayar = [
      'leader' => 'Leader',
      'pelanggan' => 'Pelanggan'
    ];

    $daftar_cabang = Cabang::pluck('nama', 'id');

    return view(
      'mimin.pesanan.masuk.create_1',
      compact(
        'judul',
        'judul_deskripsi',
        'breadcrumbs',
        'deskripsi',
        'daftar_cabang',
        'daftar_pelanggan',
        'daftar_pembayar'
      )
    );
  }

  public function create2(Request $req)
  {

    $judul = "Pesanan Masuk";
    $judul_deskripsi = "";
    $deskripsi = "";
    $breadcrumbs = [
      ['link' => '#', 'name' => "Pesanan"],
      ['link' => '#', 'name' => "Masuk"],
      ['link' => '#', 'name' => "Tambah (2/2)"],
    ];


    $rules = [
      'cabang' => 'required',
      'pelanggan' => 'required',
    ];
    $messages = [
      'cabang.required' => 'Cabang harus diisi',
      'pelanggan.required' => 'Pelanggan harus diisi',
    ];

    $input = $req->all();

    $daftar_produk = [];
    if ($req->old('produk_id')) {
      $daftar_produk = Produk::whereIn('id', $req->old('produk_id'))->pluck('nama', 'id');
    }
    $validator = Validator::make($input, $rules, $messages)->validate();

    $pelanggan = User::findOrFail($req->pelanggan);
    $cabang = Cabang::findOrFail($req->cabang);

    $daftar_harga = $pelanggan->daftar_harga();

    $daftar_pembayaran = MetodePembayaran::pluck('nama', 'id');
    $daftar_pengiriman = MetodePengiriman::pluck('nama', 'id');
    $daftar_alamat = AlamatPelanggan::where('pelanggan_id',$pelanggan->id)->get();
    $daftar_label = $daftar_alamat->pluck('label','id');

    if ($daftar_alamat->count() >0){

      $alamat = $daftar_alamat->first();
      $tujuan_nama = $alamat->nama;
      $tujuan_nomor_hp = $alamat->nomor_hp;
      $tujuan_alamat = $alamat->alamat;

    } else {

      $tujuan_nama = $pelanggan->nama;
      $tujuan_nomor_hp = $pelanggan->nomor_hp;
      $tujuan_alamat = "";

    }

    return view(
      'mimin.pesanan.masuk.create',
      compact(
        'judul',
        'judul_deskripsi',
        'breadcrumbs',
        'deskripsi',
        'daftar_produk',
        'daftar_harga',
        'cabang',
        'pelanggan',
        'daftar_pembayaran',
        'daftar_label',
        'daftar_pengiriman',
        'tujuan_nama',
        'tujuan_nomor_hp',
        'tujuan_alamat'
      )
    );
  }

  public function store(Request $req)
  {
    $rules = [
      'cabang_id' => 'required',
      'pelanggan_id' => 'required',
      'metode_pembayaran_id' => 'required',
      'metode_pengiriman_id' => 'required',
      'produk_id' => 'required',
      'kuantitas' => 'required',
      'dari_nama' => 'required',
      'dari_nomor_hp' => 'required',
      'kepada_nama' => 'required',
      'kepada_nomor_hp' => 'required',
      'alamat_tujuan' => 'required',
      'ongkos_kirim' => 'required|min:0',
      'biaya_tambahan' => 'required|min:0',
      'biaya_packing' => 'required|min:0',
      'diskon' => 'required|min:0',
      'dibayar' => 'required|min:0'
    ];
    $messages = [
      'cabang_id.required' => 'Cabang yang memproses pesanan harus diisi',
      'pelanggan_id.required' => 'Pelanggan yang melakukan pesanan harus diisi',
      'metode_pembayaran_id.required' => 'Metode pembayaran harus ditentukan',
      'metode_pengiriman_id.required' => 'Metode pengiriman harus ditentukan',
      'produk_id.required' => 'Produk harus dipilih',
      'kuantitas.required' => 'Jumlah Produk yang dipesan harus ditentukan',
      'dari_nama.required' => 'Nama Pengirim harus diisi',
      'dari_nomor_hp.required' => 'Nomor Hp Pengirim harus diisi',
      'kepada_nama.required' => 'Nama Penerima harus diisi',
      'kepada_nomor_hp.required' => 'Nomor Hp Penerima harus diisi',
      'alamat_tujuan.required' => 'Alamat Pengiriman harus diisi',
      'ongkos_kirim.required' => 'Ongkos kirim harus diperkirakan',
      'biaya_tambahan.required' => 'Biaya Tambahan harus diperkirakan',
      'biaya_packing.required' => 'Biaya Packing harus diisi',
      'diskon.required' => 'Diskon harus diisi',
      'dibayar.required' => 'Jumlah yang dibayar harus diisi'
    ];

    $input = $req->all();
    $validator = Validator::make($input, $rules, $messages)->validate();


    $daftar_detil_produk = collect([]);
    $daftar_detil_upline = collect([]);
    $cabang = Cabang::findOrFail($req->cabang_id);
    $pelanggan = User::findOrFail($req->pelanggan_id);
    $daftar_harga_pelanggan = $pelanggan->daftar_harga();

    $now = Carbon::now();
    $nomor_invoice = "INV/".$now->format("Ymd")."/".strtoupper(Str::random(5));

    switch ($pelanggan->level) {
      case  1:
      $daftar_harga_1 = $daftar_harga_pelanggan;
      break;

      case  2:
      $daftar_harga_2 = $daftar_harga_pelanggan;
      $daftar_harga_1 = $pelanggan->parent->daftar_harga();
      break;

      case 3:
      $daftar_harga_3 = $daftar_harga_pelanggan;
      $daftar_harga_2 = $pelanggan->parent->daftar_harga();
      $daftar_harga_1 = $pelanggan->parent->parent->daftar_harga();
      break;

    }

    $metode_pengiriman = MetodePengiriman::findOrFail($req->metode_pengiriman_id);
    $metode_pembayaran = MetodePembayaran::findOrFail($req->metode_pembayaran_id);

    $nominal_pembelian = 0;
    $nominal_pembelian_1 = 0;
    $nominal_pembelian_2 = 0;
    $nominal_pembelian_3 = 0;

    $harga_1 = null;
    $harga_2 = null;
    $harga_3 = null;


    foreach ($req->produk_id as $i => $produk_id) {
      $kuantitas_produk = $req->kuantitas[$i];
      $produk = Produk::findOrFail($produk_id);
      $sub_total_pelanggan = $kuantitas_produk *  $daftar_harga_pelanggan[$produk->id];
      $sub_total_harga_1 = 0;
      $sub_total_harga_2 = 0;
      $sub_total_harga_3 = 0;

      switch ($pelanggan->level) {
        case 1:
          $sub_total_harga_1 = $kuantitas_produk *  $daftar_harga_1[$produk->id];
          $harga_1 =  $daftar_harga_1[$produk->id];
          break;

        case 2:
          $sub_total_harga_1 = $kuantitas_produk *  $daftar_harga_1[$produk->id];
          $harga_1 =  $daftar_harga_1[$produk->id];
          $sub_total_harga_2 = $kuantitas_produk *  $daftar_harga_2[$produk->id];
          $harga_2 =  $daftar_harga_2[$produk->id];
          break;

        case 3:
          $sub_total_harga_1 = $kuantitas_produk *  $daftar_harga_1[$produk->id];
          $harga_1 =  $daftar_harga_1[$produk->id];
          $sub_total_harga_2 = $kuantitas_produk *  $daftar_harga_2[$produk->id];
          $harga_2 =  $daftar_harga_2[$produk->id];
          $sub_total_harga_3 = $kuantitas_produk *  $daftar_harga_3[$produk->id];
          $harga_3 =  $daftar_harga_3[$produk->id];
          break;

      }

      // bikin nominal pemblian di setiap level harga jangan lupa
      $nominal_pembelian    += $sub_total_pelanggan;
      $nominal_pembelian_1  += $sub_total_harga_1;
      $nominal_pembelian_2  += $sub_total_harga_2;
      $nominal_pembelian_3  += $sub_total_harga_3;

      if ($kuantitas_produk > 0) {
        $daftar_detil_produk->push([
          'produk_id' => $produk->id,
          'kuantitas' => $kuantitas_produk,
          'harga' => $daftar_harga_pelanggan [$produk->id], // ganti

          'harga_1' => $harga_1, // ganti
          'harga_2' => $harga_2, // ganti
          'harga_3' => $harga_3, // ganti

          'point'=>$produk->poin

        ]);
      }
    }


    // daftar uppline pesanan

    switch ($pelanggan->level) {
      case 1:
      break;
      case 2:
        $daftar_detil_upline->push([
          'pelanggan_id' => $pelanggan->parent->id,
          'pelanggan_level' => 1,
          'nominal_pembelian' => $nominal_pembelian_1,
        ]);
      break;
      case 3:
        $daftar_detil_upline->push([
          'pelanggan_id' => $pelanggan->parent->parent->id,
          'pelanggan_level' => 1,
          'nominal_pembelian' => $nominal_pembelian_1,
        ]);
        $daftar_detil_upline->push([
          'pelanggan_id' => $pelanggan->parent->id,
          'pelanggan_level' => 2,
          'nominal_pembelian' => $nominal_pembelian_2,
        ]);
      break;
      case 4:
        $daftar_detil_upline->push([
          'pelanggan_id' => $pelanggan->parent->parent->parent->id,
          'pelanggan_level' => 1,
          'nominal_pembelian' => $nominal_pembelian_1,
        ]);
        $daftar_detil_upline->push([
          'pelanggan_id' => $pelanggan->parent->parent->id,
          'pelanggan_level' => 2,
          'nominal_pembelian' => $nominal_pembelian_2,
        ]);
        $daftar_detil_upline->push([
          'pelanggan_id' => $pelanggan->parent->id,
          'pelanggan_level' => 3,
          'nominal_pembelian' => $nominal_pembelian_3,
        ]);
      break;
    }

    $user = Auth::user();
    $dibuat_oleh_cabang_id = 1;
    if (isset($user)) {
      $dibuat_oleh_cabang_id = $user->cabang_id;
    }
    DB::transaction(function () use ( $dibuat_oleh_cabang_id, $nomor_invoice, $daftar_detil_produk, $daftar_detil_upline, $req, $cabang, $nominal_pembelian, $nominal_pembelian_1, $nominal_pembelian_2, $nominal_pembelian_3) {

      $pesanan = Pesanan::create([

        'keterangan' => $req->keterangan,
        'nominal_pembelian' => $nominal_pembelian,
        'pelanggan_id' => $req->pelanggan_id,
        'cabang_id' => $req->cabang_id,
        'metode_pembayaran_id' => $req->metode_pembayaran_id,
        'status_pembayaran' => 'belum_diperiksa',
        'metode_pengiriman_id' => $req->metode_pengiriman_id,
        'status_pengiriman' => 'belum_diperiksa',
        'nominal_yang_dibayar' => $req->dibayar,
        'ongkos_kirim' => $req->ongkos_kirim,
        'biaya_tambahan' => $req->biaya_tambahan,
        'biaya_packing' => $req->biaya_packing,
        'diskon' => $req->diskon,
        'dikirim_oleh' => $req->dari_nama,
        'nomor_hp_pengirim' => $req->dari_nomor_hp,
        'alamat_pengirim' => $cabang->nama,
        'dikirim_kepada' => $req->kepada_nama,
        'nomor_hp_tujuan' => $req->kepada_nomor_hp,
        'dibuat_oleh_id' => Auth::id(),
        'alamat_tujuan' => $req->alamat_tujuan,
        'no_invoice' => $nomor_invoice,
        'status' => 'masuk'

      ]);
      $pesanan->daftar_detil()->createMany($daftar_detil_produk);
      $pesanan->daftar_upline()->createMany($daftar_detil_upline);

      if (!$req->has('label')){
        AlamatPelanggan::create([
          'pelanggan_id'  => $req->pelanggan_id,
          'label'         => 'Rumah',
          'nama'          => $req->kepada_nama,
          'nomor_hp'      => $req->kepada_nomor_hp,
          'alamat'        => $req->alamat_tujuan
        ]);
      }
    });
    return redirect()->route('mimin.pesanan.masuk.index')->with('sukses', 'Tambah Pesanan Masuk Sukses');
  }

  public function edit($id)
  {
    $judul = "Edit Pesanan";
    $judul_deskripsi = "";
    $deskripsi = "";
    $breadcrumbs = [
      ['link' => '#', 'name' => "Pesanan"],
      ['link' => '#', 'name' => "Masuk"],
      ['link' => '#', 'name' => "Edit"],
    ];

    $pesanan = Pesanan::masuk()->findOrFail($id);
    $pelanggan = $pesanan->pelanggan;
    $cabang = $pesanan->cabang;

    $daftar_gudang = Gudang::where('status', 'Aktif')->pluck('nama', 'id');

    $daftar_pembayaran = MetodePembayaran::pluck('nama', 'id');
    $daftar_pengiriman = MetodePengiriman::pluck('nama', 'id');

    $daftar_harga = $pelanggan->daftar_harga();

    $daftar_produk = Produk::whereIn('id', $pesanan->daftar_detil->pluck('produk_id'))->pluck('nama', 'id');

    $daftar_alamat = AlamatPelanggan::where('pelanggan_id',$pelanggan->id)->get();
    $daftar_label = $daftar_alamat->pluck('label','id');



    return view(
      'mimin.pesanan.masuk.edit',
      compact(
        'judul',
        'judul_deskripsi',
        'breadcrumbs',
        'deskripsi',
        'daftar_produk',
        'daftar_harga',
        'pesanan',
        'daftar_gudang',
        'cabang',
        'pelanggan',
        'daftar_pembayaran',
        'daftar_pengiriman',
        'daftar_alamat',
        'daftar_label',
      )
    );
  }

  public function update(Request $req, $id)
  {

    $rules = [
      //  'dibayar_oleh' => 'required',
      'metode_pembayaran_id' => 'required',
      'metode_pengiriman_id' => 'required',
      'produk_id'            => 'required',
      'kuantitas'            => 'required',
      'dari_nama'            => 'required',
      'dari_nomor_hp'        => 'required',
      'kepada_nama'          => 'required',
      'kepada_nomor_hp'      => 'required',
      'alamat_tujuan'        => 'required',
      'ongkos_kirim'         => 'required|min:0',
      'biaya_tambahan'         => 'required|min:0',
      'biaya_packing'         => 'required|min:0',
      'diskon'         => 'required|min:0',
      'dibayar'              => 'required|min:0'
    ];
    $messages = [
      //   'dibayar_oleh.required' => 'Siapa yang membayar pesanan harus ditentukan',
      'metode_pembayaran_id.required' => 'Metode pembayaran harus ditentukan',
      'metode_pengiriman_id.required' => 'Metode pengiriman harus ditentukan',
      'produk_id.required' => 'Produk harus dipilih',
      'kuantitas.required' => 'Jumlah Produk yang dipesan harus ditentukan',
      'dari_nama.required' => 'Nama Pengirim harus diisi',
      'dari_nomor_hp.required' => 'Nomor Hp Pengirim harus diisi',
      'kepada_nama.required' => 'Nama Penerima harus diisi',
      'kepada_nomor_hp.required' => 'Nomor Hp Penerima harus diisi',
      'alamat_tujuan.required' => 'Alamat Pengiriman harus diisi',
      'ongkos_kirim.required' => 'Ongkos kirim harus diperkirakan',
      'biaya_tambahan.required' => 'Biaya Tambahan harus diperkirakan',
      'biaya_packing.required' => 'Biaya Packing harus diisi',
      'diskon.required' => 'Diskon harus diisi',
      'dibayar.required' => 'Jumlah yang dibayar harus diisi'
    ];

    $input = $req->all();
    $validator = Validator::make($input, $rules, $messages)->validate();


    $pesanan = Pesanan::masuk()->findOrFail($id);
    $daftar_detil_produk = collect([]);
    $daftar_detil_upline = collect([]);
    $pelanggan = $pesanan->pelanggan;

    $daftar_harga_pelanggan = $pelanggan->daftar_harga();

    switch ($pelanggan->level) {
      case  1:
      $daftar_harga_1 = $daftar_harga_pelanggan;
      break;

      case  2:
      $daftar_harga_2 = $daftar_harga_pelanggan;
      $daftar_harga_1 = $pelanggan->parent->daftar_harga();
      break;

      case 3:
      $daftar_harga_3 = $daftar_harga_pelanggan;
      $daftar_harga_2 = $pelanggan->parent->daftar_harga();
      $daftar_harga_1 = $pelanggan->parent->parent->daftar_harga();
      break;

    }

    $metode_pengiriman = MetodePengiriman::findOrFail($req->metode_pengiriman_id);
    $metode_pembayaran = MetodePembayaran::findOrFail($req->metode_pembayaran_id);

    $nominal_pembelian    = 0;
    $nominal_pembelian_1  = 0;
    $nominal_pembelian_2  = 0;
    $nominal_pembelian_3  = 0;

    $harga_1 = null;
    $harga_2 = null;
    $harga_3 = null;


    foreach ($req->produk_id as $i => $produk_id) {
      $kuantitas_produk = $req->kuantitas[$i];
      $produk = Produk::findOrFail($produk_id);
      $sub_total_pelanggan = $kuantitas_produk *  $daftar_harga_pelanggan[$produk->id];
      $sub_total_harga_1 = 0;
      $sub_total_harga_2 = 0;
      $sub_total_harga_3 = 0;

      switch ($pelanggan->level) {
        case 1:
          $sub_total_harga_1 = $kuantitas_produk *  $daftar_harga_1[$produk->id];
          $harga_1 =  $daftar_harga_1[$produk->id];
          break;

        case 2:
          $sub_total_harga_1 = $kuantitas_produk *  $daftar_harga_1[$produk->id];
          $harga_1 =  $daftar_harga_1[$produk->id];
          $sub_total_harga_2 = $kuantitas_produk *  $daftar_harga_2[$produk->id];
          $harga_2 =  $daftar_harga_2[$produk->id];
          break;

        case 3:
          $sub_total_harga_1 = $kuantitas_produk *  $daftar_harga_1[$produk->id];
          $harga_1 =  $daftar_harga_1[$produk->id];
          $sub_total_harga_2 = $kuantitas_produk *  $daftar_harga_2[$produk->id];
          $harga_2 =  $daftar_harga_2[$produk->id];
          $sub_total_harga_3 = $kuantitas_produk *  $daftar_harga_3[$produk->id];
          $harga_3 =  $daftar_harga_3[$produk->id];
          break;

      }

      // bikin nominal pemblian di setiap level harga jangan lupa
      $nominal_pembelian    += $sub_total_pelanggan;
      $nominal_pembelian_1  += $sub_total_harga_1;
      $nominal_pembelian_2  += $sub_total_harga_2;
      $nominal_pembelian_3  += $sub_total_harga_3;

      if ($kuantitas_produk > 0) {
        $daftar_detil_produk->push([
          'produk_id' => $produk->id,
          'kuantitas' => $kuantitas_produk,
          'harga' => $daftar_harga_pelanggan [$produk->id], // ganti

          'harga_1' => $harga_1, // ganti
          'harga_2' => $harga_2, // ganti
          'harga_3' => $harga_3, // ganti

          'point'=>$produk->poin

        ]);
      }
    }


    // daftar uppline pesanan

    switch ($pelanggan->level) {
      case 1:
      break;
      case 2:
        $daftar_detil_upline->push([
          'pelanggan_id' => $pelanggan->parent->id,
          'pelanggan_level' => 1,
          'nominal_pembelian' => $nominal_pembelian_1,
        ]);
      break;
      case 3:
        $daftar_detil_upline->push([
          'pelanggan_id' => $pelanggan->parent->parent->id,
          'pelanggan_level' => 1,
          'nominal_pembelian' => $nominal_pembelian_1,
        ]);
        $daftar_detil_upline->push([
          'pelanggan_id' => $pelanggan->parent->id,
          'pelanggan_level' => 2,
          'nominal_pembelian' => $nominal_pembelian_2,
        ]);
      break;
      case 4:
        $daftar_detil_upline->push([
          'pelanggan_id' => $pelanggan->parent->parent->parent->id,
          'pelanggan_level' => 1,
          'nominal_pembelian' => $nominal_pembelian_1,
        ]);
        $daftar_detil_upline->push([
          'pelanggan_id' => $pelanggan->parent->parent->id,
          'pelanggan_level' => 2,
          'nominal_pembelian' => $nominal_pembelian_2,
        ]);
        $daftar_detil_upline->push([
          'pelanggan_id' => $pelanggan->parent->id,
          'pelanggan_level' => 3,
          'nominal_pembelian' => $nominal_pembelian_3,
        ]);
      break;
    }



    DB::transaction(function () use ($req, $id,$pesanan,$daftar_detil_produk,$daftar_detil_upline,$nominal_pembelian) {

      $user = Auth::user();

      $pesanan->keterangan                  =  $req->keterangan;
      $pesanan->nominal_pembelian           =  $nominal_pembelian;
      $pesanan->pelanggan_id                =  $req->pelanggan_id;
      $pesanan->metode_pembayaran_id        =  $req->metode_pembayaran_id;
      $pesanan->metode_pengiriman_id        =  $req->metode_pengiriman_id;
      $pesanan->nominal_yang_dibayar        =  $req->dibayar;
      $pesanan->ongkos_kirim                =  $req->ongkos_kirim;
      $pesanan->biaya_tambahan              =  $req->biaya_tambahan;
      $pesanan->biaya_packing               =  $req->biaya_packing;
      $pesanan->diskon              =  $req->diskon;
      $pesanan->dikirim_oleh                =  $req->dari_nama;
      $pesanan->nomor_hp_pengirim           =  $req->dari_nomor_hp;
      $pesanan->dikirim_kepada              =  $req->kepada_nama;
      $pesanan->nomor_hp_tujuan             =  $req->kepada_nomor_hp;
      $pesanan->dibuat_oleh_id              =  Auth::id();
      $pesanan->alamat_tujuan               =  $req->alamat_tujuan;
      $pesanan->save();

      $pesanan->daftar_detil()->delete();
      $pesanan->daftar_detil()->createMany($daftar_detil_produk);

      $pesanan->daftar_upline()->delete();
      $pesanan->daftar_upline()->createMany($daftar_detil_upline);
    });

    return redirect()->route('mimin.pesanan.masuk.index')->with('sukses', 'Berhasil diubah');
  }

  public function destroy(Request $req, $id)
  {

    $user = Auth::user();
    try {
      $pesanan = Pesanan::masuk()->findOrFail($id);
      $now = Carbon::now();
      $nama = 'Pesanan Masuk tanggal ' . $pesanan->created_at->format('d-m-Y');

      if ($req->has('aksi') && ($req->aksi =="proses" || $req->aksi == "batal" || $req->aksi == "tunda")) {

        if ($req->aksi == "proses" && $user->hasPermissionTo('pesanan.masuk.proses') ){
          $keterangan = 'Memproses ';
          $pesanan->status = "diproses";
          $pesanan->diproses_oleh_id = Auth::id();
          $pesanan->diproses_pada = $now;
          $pesanan->save();

        } else if ($req->aksi =="batal"  && $user->hasPermissionTo('pesanan.masuk.batalkan')){
          $keterangan = 'Membatalkan ';
          $pesanan->status = "dibatalkan";
          $pesanan->dibatalkan_oleh_id = Auth::id();
          $pesanan->dibatalkan_pada = $now;
          $pesanan->save();

        } else if ($req->aksi =="tunda"  && $user->hasPermissionTo('pesanan.masuk.tunda')) {

          $keterangan = 'Menunda ';
          $pesanan->status = "preorder";
          $pesanan->dibuat_oleh_id = Auth::id();
          $pesanan->save();

        } else {
          return response()->json([
            'judul' => $nama.' Gagal',
            'pesan' => 'Gagal',
            'success' => false,
            'redirect' => route('mimin.pesanan.masuk.index')
          ]);
        }

        return response()->json([
          'judul' => $keterangan.'sukses!',
          'pesan' => $keterangan.' '.$nama.' Sukses',
          'success' => true,
          'redirect' => route('mimin.pesanan.masuk.index')
        ]);

      } else {
        return response()->json([
          'judul' => $nama.' Gagal',
          'pesan' => 'Gagal',
          'success' => false,
          'redirect' => route('mimin.pesanan.masuk.index')
        ]);
      }
    } catch (\Exception $exception) {
      return response()->json([
        'judul' => 'Gagal',
        'pesan' => 'Terjadi kesalahan',
        'success' => false,
        'redirect' => route('mimin.pesanan.masuk.index')
      ]);
    }
  }



  public function show($id)
  {

   // return $id;
    $judul = "Pesanan Masuk";
    $judul_deskripsi = "";
    $deskripsi = "";

    $breadcrumbs = [
      ['link' => '#', 'name' => "Pesanan"],
      ['link' => '#', 'name' => "Masuk"],
      ['link' => '#', 'name' => "Lihat"],
    ];

    $pesanan = Pesanan::masuk()->findOrFail($id);

    return view (
      'mimin.pesanan.masuk.show',
       compact ('judul',
                'breadcrumbs',
                'judul_deskripsi',
                'deskripsi',
                'pesanan')
    );

  }


  public function cari_produk(Request $req)
  {

    $selain = explode(',', urldecode($req->selain));
    $key = $req->cari;
    if ($req->has("cari") && $req->cari != "") {
      $daftar_produk = Produk::where('nama', 'like', '%' . $req->cari . '%')->whereNotIn('id', $selain)->paginate(5);
    } else {
      $daftar_produk = Produk::whereNotIn('id', $selain)->paginate(5);
    }
    $results = array(
      "results" => $daftar_produk->toArray()['data'],
      "pagination" => array(
        "more" => $daftar_produk->hasMorePages()
      )
    );

    return response()->json($results);
  }

  public function harga(Request $req)
  {
    $id_produk = $req->id_produk;
    $harga = Produk::where('id', $id_produk)->value('harga_jual');

    return response()->json($harga);
  }

  public function cari_pelanggan(Request $req)
  {

    $key = $req->cari;
    if ($req->has("cari") && $req->cari != "") {

      $daftar_kegiatan = User::with('parent','kategori','distributor')->where(function ($query) use ($key) {
        $query->where('nama', 'like', '%' . $key . '%')
              ->orWhere('email', $key)
              ->orWhere('nomor_hp', $key);
      })
      ->where('status','Aktif')
      ->paginate(5);
    } else {
      $daftar_kegiatan = User::with('parent','kategori','distributor')
      ->where('status','Aktif')
      ->paginate(5);
    }
    $results = array(
      "results" => $daftar_kegiatan->toArray()['data'],
      "pagination" => array(
        "more" => $daftar_kegiatan->hasMorePages()
      )
    );

    return response()->json($results);
  }

  public function cari_alamat(Request $req)
  {

    $alamat = AlamatPelanggan::find($req->id);
    if ($alamat){
    $results = array(
      "results" => $alamat,
      "status" => 1
    );
  } else {
    $results = array(
      "status" => 0
    );
  }

    return response()->json($results);
  }

}
