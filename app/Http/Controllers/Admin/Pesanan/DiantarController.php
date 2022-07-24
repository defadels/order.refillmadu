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
use App\MetodePengiriman;


class DiantarController extends Controller
{
  function __construct()
  {
       $this->middleware('permission:pesanan.diantar.lihat', ['only' => ['index','show']]);
  //   $this->middleware('permission:pesanan.diantar.edit', ['only' => ['edit','update','destroy']]);
       $this->middleware('permission:pesanan.diantar.kirim.simpan', ['only' => ['edit','update']]);

       $this->middleware('permission:pesanan.diantar.batalkan', ['only' => ['destroy']]);
  }

  public function index()
  {
    $judul = "Pesanan Diantar";
    $breadcrumbs = [
      ['link' => '#', 'name' => "Pesanan"],
      ['link' => '#', 'name' => "Diantar"],
    ];

    $daftar_pesanan = Pesanan::diantar()->with('pelanggan', 'metode_pembayaran');
    $user = Auth::user();
    if($user->hasRole('Kurir')){
    $daftar_pesanan = $daftar_pesanan->where('diantar_oleh_id',$user->id);
    }
    $daftar_pesanan = $daftar_pesanan->orderBy('created_at', 'desc')
    ->simplePaginate(10);

    return view(
      'mimin.pesanan.diantar.index',
      compact('judul', 'breadcrumbs', 'daftar_pesanan')
    );
  }
  public function show($id)
  {

   // return $id;
    $judul = "Pesanan Diantar";
    $judul_deskripsi = "";
    $deskripsi = "";

    $breadcrumbs = [
      ['link' => '#', 'name' => "Pesanan"],
      ['link' => '#', 'name' => "Diantar"],
      ['link' => '#', 'name' => "Lihat"],
    ];

    $pesanan = Pesanan::diantar()->findOrFail($id);

    return view (
      'mimin.pesanan.diantar.show',
       compact ('judul',
                'breadcrumbs',
                'judul_deskripsi',
                'deskripsi',
                'pesanan')
    );

  }

  public function edit($id)
  {

   // return $id;
    $judul = "Pesanan Diantar";
    $judul_deskripsi = "";
    $deskripsi = "";

    $breadcrumbs = [
      ['link' => '#', 'name' => "Pesanan"],
      ['link' => '#', 'name' => "Diantar"],
      ['link' => '#', 'name' => "Konfirmasi"],
    ];

    $pesanan = Pesanan::diantar()->findOrFail($id);

    $daftar_gudang = Gudang::where('status','Aktif')->pluck('nama','id');
    $daftar_kurir = User::notRole(['Pelanggan'])->orderBy('nama')->pluck('nama','id');
 //   $daftar_kurir = User::role(['Kurir'])->pluck('nama','id');
    $daftar_metode_pengiriman = MetodePengiriman::where('status','Aktif')->pluck('nama','id');

    return view (
      'mimin.pesanan.diantar.konfirmasi',
       compact ('judul',
                'breadcrumbs',
                'judul_deskripsi',
                'daftar_metode_pengiriman',
                'daftar_gudang',
                'daftar_kurir',
                'deskripsi',
                'pesanan')
    );

  }

  public function update(Request $req, $id){


    $rules = [
   //   'gudang_id' => 'required',
      'metode_pengiriman_id' => 'required',
      'tanggal_pengiriman' => 'required',
   //   'dikirim_oleh_id' => 'required',
      'nomor_resi' => 'required',
      'bobot_pengiriman' => 'required|min:0',
      'ongkos_kirim' => 'required|min:0',
      'ongkos_kurir' => 'required|min:0',
      'biaya_packing' => 'required|min:0',
    ];
    $messages = [
      'gudang_id.required' => 'Gudang Harus ditentukan biar tahu darimana stok dikeluarkan',
      'metode_pengiriman_id.required' => 'Metode Pengiriman harus diisi',
      'tanggal_pengiriman.required' => 'Tanggal pengiriman harus diisi',
      'dikirim_oleh_id.required' => 'Siapa Kurir yang mengirim harus dintentukan',
      'nomor_resi.required' => 'Nomo Resi harus diisi',
      'bobot_pengiriman.required' => 'Bobot Pengiriman wajib diisi berat atau kilometer',
      'ongkos_kirim.required' => 'Ongkos Kirim wajib diisi',
      'ongkos_kurir.required' => 'Ongkos Kurir wajib diisi',
      'biaya_packing.required' => 'Biaya packing wajib diisi',
    ];

    $input = $req->all();
    $validator = Validator::make($input, $rules, $messages)->validate();

    $pesanan = Pesanan::diantar()->findOrFail($id);
    $user = Auth::user();

  //  $pesanan->gudang_id = $req->gudang_id;
    $pesanan->metode_pengiriman_id = $req->metode_pengiriman_id;
    $pesanan->tanggal_pengiriman = Carbon::createFromFormat("d-m-Y",$req->tanggal_pengiriman);
    $pesanan->dikirim_oleh_id = Auth::id();
    $pesanan->diantar_oleh_id = $req->diantar_oleh_id;
    $pesanan->nomor_resi = $req->nomor_resi;
    $pesanan->bobot_pengiriman = $req->bobot_pengiriman;
    $pesanan->ongkos_kirim = $req->ongkos_kirim;
    $pesanan->ongkos_kurir = $req->ongkos_kurir;
    $pesanan->biaya_packing = $req->biaya_packing;

    $pesanan->dikirim_pada = Carbon::now();
    $pesanan->status_pengiriman = "sudah_dikirim";
    if($req->has('konfirmasi') && $user->hasPermissionTo('pesanan.diantar.kirim.konfirmasi')){
      $pesanan->status = "dikirim";

      $pesanan->save();
      return redirect()->route('mimin.pesanan.diantar.index')->with('sukses', 'Konfirmasi Pengiriman Sukses');
    }

    $pesanan->save();
    return redirect()->route('mimin.pesanan.diantar.index')->with('sukses', 'Simpan Pengiriman Sukses');


  }
  public function destroy(Request $req, $id)
  {

    try {
      $pesanan = Pesanan::diantar()->findOrFail($id);
      $now = Carbon::now();
      $nama = 'Pesanan Diantar tanggal ' . $pesanan->created_at->format('d-m-Y');

      if ($req->has('aksi') &&  $req->aksi == "batal") {

          $keterangan = 'Membatalkan ';
          $pesanan->status = "dibatalkan";
          $pesanan->dibatalkan_oleh_id = Auth::id();
          $pesanan->dibatalkan_pada = $now;
          $pesanan->save();

        return response()->json([
          'judul' => $keterangan.'sukses!',
          'pesan' => $keterangan.' '.$nama.' Sukses',
          'success' => true,
          'redirect' => route('mimin.pesanan.diantar.index')
        ]);
      } else {
        return response()->json([
          'judul' => $nama.' Gagal',
          'pesan' => 'Gagal',
          'success' => false,
          'redirect' => route('mimin.pesanan.diantar.index')
        ]);
      }
    } catch (\Exception $exception) {
      return response()->json([
        'judul' => 'Gagal',
        'pesan' => 'Terjadi kesalahan',
        'success' => false,
        'redirect' => route('mimin.pesanan.diantar.index')
      ]);
    }
  }
}
