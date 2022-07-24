<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\KategoriPengeluaran;
use App\Pengeluaran;
use App\Kas;
use App\TransaksiKas;
use Carbon\Carbon;
use App\MetodePengiriman;

use Validator;
use Str;
use Auth;
use DB;


class PengeluaranController extends Controller
{
  function __construct()
  {
       $this->middleware('permission:pengeluaran.lihat', ['only' => ['index','show']]);
       $this->middleware('permission:pengeluaran.tambah', ['only' => ['create','store']]);
       $this->middleware('permission:pengeluaran.edit', ['only' => ['edit','update']]);
       $this->middleware('permission:pengeluaran.hapus', ['only' => ['batalkan','destroy']]);
  }

  public function index()
  {

  $title = "Pengeluaran";
  $judul = "Pengeluaran";
  $judul_deskripsi = "";
  $deskripsi = "";

  $breadcrumbs = [
    ['link'=>'#','name'=>"Pengeluaran"],
  ];

  $daftar_pengeluaran = Pengeluaran::orderBy('tanggal','desc')->simplePaginate(10);

  return view('mimin.pengeluaran.index',
  compact('title','judul','judul_deskripsi','deskripsi','daftar_pengeluaran','breadcrumbs')
  );

  }

  public function create(){
      $title = "Tambah Pengeluaran";
      $judul = "Tambah Pengeluaran";
      $judul_deskripsi = "";
      $deskripsi = "";
      $breadcrumbs = [
        ['link'=>route('mimin.pengeluaran.index'),'name'=>"Pengeluaran"],
        ['link'=>'#','name'=>"Tambah"],
      ];

      $daftar_kategori = KategoriPengeluaran::orderBy('nama')->pluck("nama","id");
      $daftar_pengiriman = MetodePengiriman::where('jenis','custom')->pluck("nama","id");
      $daftar_kas = Kas::pluck('nama','id');

      return view('mimin.pengeluaran.create',
      compact('title','judul','judul_deskripsi','deskripsi','daftar_kas','daftar_kategori','breadcrumbs','daftar_pengiriman')
      );

  }
public function store (Request $req){

      $rules = [
          'tanggal' =>'required',
          'nominal' =>'required|min:0',
          'kategori_pengeluaran_id' =>'required',
          'kas_asal_id'=>'required',
          'dibayar_oleh'=>'required',
          'dibayar_kepada'=>'required',
          'metode_pengiriman_id'=>'required_if:kategori_pengeluaran_id,1',
          'keterangan'=>'required'
      ];

      $messages =[
      ];

      $input= $req->all();
      $validator = Validator::make($input, $rules, $messages)->validate();

      DB::transaction(function () use ($req) {
      $nomor_trx = Str::uuid();
      $tanggal = Carbon::createFromFormat('d-m-Y',$req->tanggal);
      $kas_asal = Kas::findOrFail($req->kas_asal_id);
      $nominal = $req->nominal;

      $keterangan = $req->keterangan;
      $trx_kas_asal = TransaksiKas::create([
        'kas_id'=>$kas_asal->id,
        'tanggal'=>$tanggal,
        'keterangan'=>$keterangan,
        'debet_kredit'=>'k',
        'nominal'=>$nominal
      ]);


    $kas_asal->update_saldo($tanggal,$nominal,'k');

      $pengeluaran = Pengeluaran::create([

        'tanggal' => $tanggal,
        'nominal' => $req->nominal,
        'nomor_transaksi' =>$nomor_trx,
        'nomor_invoice' => $req->nomor_invoice,
        'kategori_pengeluaran_id'=>$req->kategori_pengeluaran_id,

        'keterangan'=>$req->keterangan,
        'dibayar_oleh'=>$req->dibayar_oleh,
        'dibayar_kepada'=>$req->dibayar_kepada,
        'diinput_oleh_id'=>Auth::id(),
        'transaksi_kas_id'=>$trx_kas_asal->id
       ]);

       if($req->kategori_pengeluaran_id == 1){
          $metode_pengiriman = MetodePengiriman::findOrFail($req->metode_pengiriman_id);
          $metode_pengiriman->decrement('saldo',$req->nominal);
          $pengeluaran->daftar_pengiriman()->attach( $req->metode_pengiriman_id );
       }

      });


      return redirect()->route('mimin.pengeluaran.index')->with('sukses','Tambah Pengeluaran Sukses');
  }



  public function show ($id){

    $pengeluaran = Pengeluaran::findOrFail($id);



    $title = "Rincian Pengeluaran";
    $judul = "Rincian Pengeluaran";
    $judul_deskripsi = "";
    $deskripsi = "";
    $breadcrumbs = [
      ['link'=>route('mimin.pengeluaran.index'),'name'=>"Pengeluaran"],
      ['link'=>'#','name'=>"Rincian"],
    ];

    return view('mimin.pengeluaran.show',
    compact('title','judul','judul_deskripsi','deskripsi','pengeluaran','breadcrumbs')
    );

  }



  public function batalkan ($id){

    $pengeluaran = Pengeluaran::findOrFail($id);

    $title = "Batalkan Pengeluaran";
    $judul = "Batalkan Pengeluaran";
    $judul_deskripsi = "";
    $deskripsi = "";
    $breadcrumbs = [
      ['link'=>route('mimin.pengeluaran.index'),'name'=>"Pengeluaran"],
      ['link'=>'#','name'=>"Tambah"],
    ];

    return view('mimin.pengeluaran.batalkan',
    compact('title','judul','judul_deskripsi','deskripsi','pengeluaran','breadcrumbs')
    );

  }

  public function destroy (Request $req,$id){

    if ($req->batalkan == "1"){
        DB::transaction(function () use ($id) {
        $pengeluaran = Pengeluaran::findOrFail($id);
        $trx_kas = $pengeluaran->transaksi_kas;

        $trx_kas->kas->update_saldo($pengeluaran->tanggal,$pengeluaran->nominal,'d');
        $pengeluaran->delete();

        $trx_kas->delete();

      });
    }
    return redirect()->route('mimin.pengeluaran.index')->with('sukses','Hapus Pengeluaran Sukses');
  }


}
