<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Auth;
use Session;
use App\Cabang;
use App\Pelanggan;
use Illuminate\Support\Str;
use App\KategoriPelanggan;
use App\User;
use App\Dompet;
use App\Kas;
use Carbon\Carbon;
use App\TransaksiKas;
use Validator;
use DB;

class DompetController extends Controller
{
  function __construct()
  {
       $this->middleware('permission:dompet.lihat', ['only' => ['index','dompet','transaksi','lihat_transaksi']]);
       $this->middleware('permission:dompet.tambah', ['only' => ['tambah','tambah_post','tambah_transaksi','tambah_transaksi_post']]);
       $this->middleware('permission:dompet.kurangi', ['only' => ['kurangi','kurangi_post','kurangi_transaksi','kurangi_transaksi_post']]);
  }

  public function index()
  {

    $judul = "Dompet";
    $breadcrumbs = [
        ['link'=>'#','name'=>"Saldo"],
    ];

    $daftar_pelanggan = Pelanggan::with('kategori')->where('saldo','!=',0)->paginate(10);
    //return $daftar_pelanggan;

    return view('mimin.dompet.index',
                    compact('judul','breadcrumbs','daftar_pelanggan')
                );
  }

  public function dompet($id){
    $judul = "Rincian Dompet";
    $breadcrumbs = [
      ['link'=>'#','name'=>"Rincian"],
    ];

    $pelanggan = Pelanggan::findOrFail($id);
    $daftar_rincian = Dompet::where('user_id',$pelanggan->id)
                              ->orderBy('tanggal','desc')
                              ->orderBy('created_at','desc')
                              ->orderBy('id','desc')
                              ->simplePaginate(10);

    return view('mimin.dompet.dompet',
    compact('judul','breadcrumbs','daftar_rincian','pelanggan'));
  }

  public function transaksi(Request $req){
    $judul = "Riwayat Transaksi";
    $breadcrumbs = [
      ['link'=>'#','name'=>"Dompet"],
    ];

    $daftar_rincian = Dompet:: orderBy('tanggal','desc')
                              ->orderBy('created_at','desc')
                              ->simplePaginate(10);

    return view('mimin.dompet.transaksi',
    compact('judul','breadcrumbs','daftar_rincian'));
  }


  public function tambah($id){
    $judul = "Tambah isi Dompet";
    $breadcrumbs = [
      ['link'=>'#','name'=>"Dompet"],
      ['link'=>'#','name'=>"tambah"],
    ];
    $daftar_kas = Kas::pluck('nama','id');
    $pelanggan = Pelanggan::findOrFail($id);

    return view('mimin.dompet.tambah',
    compact('judul','breadcrumbs','daftar_kas','pelanggan'));
  }

  public function tambah_post(Request $req,$id){
    $pelanggan = User::findOrFail($id);

    $rules = [
      'tanggal' =>'required',
      'nominal' => 'required|min:0',
      'kas_id' => 'required',
      'keterangan' => 'required',
      'dibayar_oleh' => 'required',
      'dibayar_kepada' => 'required'
    ];
    $messages =[
        'tanggal.required'=>'Tanggal wajib diisi',
        'nominal.required' => 'Nominal wajib diisi',
        'kas_id.required' => 'Kas wajib diisi',
        'keterangan.required' => 'Keterangan wajib diisi',
        'dibayar_oleh.required' => 'Pembayar wajib diisi',
        'dibayar_kepada.required' => 'yang dibayar wajib disii'
    ];

    $input= $req->all();
    $validator = Validator::make($input, $rules, $messages)->validate();


    DB::transaction(function () use ($req,$pelanggan) {
      $kas_tujuan = Kas::findOrFail($req->kas_id);
      $nominal = $req->nominal;

      $tanggal = Carbon::createFromFormat('d-m-Y',$req->tanggal);
      $saldo_akhir = $pelanggan->saldo_tanggal($tanggal);
      $saldo_berjalan = $saldo_akhir + $nominal;
      //update saldo

      $pelanggan->update_saldo($tanggal,$nominal,'d');

      $trx_kas = TransaksiKas::create([
        'kas_id'=>$kas_tujuan->id,
        'tanggal'=> $tanggal,
        'keterangan'=>$req->keterangan,
        'debet_kredit'=>'d',
        'nominal'=>$nominal
      ]);
      $kas_tujuan->update_saldo($tanggal,$nominal,'d');
      Dompet::create([
        'tanggal'=>$tanggal,
        'nominal'=>$req->nominal,
        'transaksi_kas_id'=>$trx_kas->id,
        'keterangan'=>$req->keterangan,
        'dibayar_oleh'=>$req->dibayar_oleh,
        'dibayar_kepada'=>$req->dibayar_kepada,
        'user_id'=>$pelanggan->id,
        'saldo_berjalan'=>$saldo_berjalan,
        'debet_kredit'=>'debet'
      ]);

    });
    return redirect()->route('mimin.dompet.pelanggan.index',$pelanggan->id)->with('sukses','Tambah Dompet Sukses');

  }


  public function kurangi($id){
    $judul = "Kurangi isi Dompet";
    $breadcrumbs = [
      ['link'=>'#','name'=>"Dompet"],
      ['link'=>'#','name'=>"kurangi"],
    ];
    $daftar_kas = Kas::pluck('nama','id');
    $pelanggan = Pelanggan::findOrFail($id);

    return view('mimin.dompet.kurangi',
    compact('judul','breadcrumbs','daftar_kas','pelanggan'));
  }

  public function kurangi_post(Request $req,$id){
    $pelanggan = User::findOrFail($id);

    $rules = [
      'tanggal' =>'required',
      'nominal' => 'required|min:0',
      'kas_id' => 'required',
      'keterangan' => 'required',
      'dibayar_oleh' => 'required',
      'dibayar_kepada' => 'required'
    ];
    $messages =[
        'tanggal.required'=>'Tanggal wajib diisi',
        'nominal.required' => 'Nominal wajib diisi',
        'kas_id.required' => 'Kas wajib diisi',
        'keterangan.required' => 'Keterangan wajib diisi',
        'dibayar_oleh.required' => 'Pembayar wajib diisi',
        'dibayar_kepada.required' => 'yang dibayar wajib disii'
    ];

    $input= $req->all();
    $validator = Validator::make($input, $rules, $messages)->validate();


    DB::transaction(function () use ($req,$pelanggan) {
      $kas_asal = Kas::findOrFail($req->kas_id);
      $nominal = $req->nominal;

      $tanggal = Carbon::createFromFormat('d-m-Y',$req->tanggal);
      $saldo_akhir = $pelanggan->saldo_tanggal($tanggal);
      $saldo_berjalan = $saldo_akhir - $nominal;
      //update saldo

      $pelanggan->update_saldo($tanggal,$nominal,'k');

      $trx_kas = TransaksiKas::create([
        'kas_id'=>$kas_asal->id,
        'tanggal'=> $tanggal,
        'keterangan'=>$req->keterangan,
        'debet_kredit'=>'k',
        'nominal'=>$nominal
      ]);
      $kas_asal->update_saldo($tanggal,$nominal,'k');
      Dompet::create([
        'tanggal'=>$tanggal,
        'nominal'=>$req->nominal,
        'transaksi_kas_id'=>$trx_kas->id,
        'keterangan'=>$req->keterangan,
        'dibayar_oleh'=>$req->dibayar_oleh,
        'dibayar_kepada'=>$req->dibayar_kepada,
        'user_id'=>$pelanggan->id,
        'saldo_berjalan'=>$saldo_berjalan,
        'debet_kredit'=>'kredit'
      ]);

    });
    return redirect()->route('mimin.dompet.pelanggan.index',$pelanggan->id)->with('sukses','Kurangi Dompet Sukses');

  }


  public function tambah_transaksi(){
    $judul = "Tambah isi Dompet";
    $breadcrumbs = [
      ['link'=>'#','name'=>"Dompet"],
      ['link'=>'#','name'=>"Transaksi"],
      ['link'=>'#','name'=>"tambah"],
    ];
    $daftar_kas = Kas::pluck('nama','id');
    $daftar_pelanggan = [];

    return view('mimin.dompet.tambah_transaksi',
    compact('judul','breadcrumbs','daftar_kas','daftar_pelanggan'));
  }

  public function tambah_transaksi_post(Request $req){
    $pelanggan = User::findOrFail($req->pelanggan_id);

    $rules = [
      'tanggal' =>'required',
      'nominal' => 'required|min:0',
      'kas_id' => 'required',
      'keterangan' => 'required',
      'dibayar_oleh' => 'required',
      'dibayar_kepada' => 'required'
    ];
    $messages =[
        'tanggal.required'=>'Tanggal wajib diisi',
        'nominal.required' => 'Nominal wajib diisi',
        'kas_id.required' => 'Kas wajib diisi',
        'keterangan.required' => 'Keterangan wajib diisi',
        'dibayar_oleh.required' => 'Pembayar wajib diisi',
        'dibayar_kepada.required' => 'yang dibayar wajib disii'
    ];

    $input= $req->all();
    $validator = Validator::make($input, $rules, $messages)->validate();


    DB::transaction(function () use ($req,$pelanggan) {
      $kas_tujuan = Kas::findOrFail($req->kas_id);
      $nominal = $req->nominal;

      $tanggal = Carbon::createFromFormat('d-m-Y',$req->tanggal);
      $saldo_akhir = $pelanggan->saldo_tanggal($tanggal);
      $saldo_berjalan = $saldo_akhir + $nominal;
      //update saldo

      $pelanggan->update_saldo($tanggal,$nominal,'d');

      $trx_kas = TransaksiKas::create([
        'kas_id'=>$kas_tujuan->id,
        'tanggal'=> $tanggal,
        'keterangan'=>$req->keterangan,
        'debet_kredit'=>'d',
        'nominal'=>$nominal
      ]);
      $kas_tujuan->update_saldo($tanggal,$nominal,'d');
      Dompet::create([
        'tanggal'=>$tanggal,
        'nominal'=>$req->nominal,
        'transaksi_kas_id'=>$trx_kas->id,
        'keterangan'=>$req->keterangan,
        'dibayar_oleh'=>$req->dibayar_oleh,
        'dibayar_kepada'=>$req->dibayar_kepada,
        'user_id'=>$pelanggan->id,
        'saldo_berjalan'=>$saldo_berjalan,
        'debet_kredit'=>'debet'
      ]);

    });
    return redirect()->route('mimin.dompet.transaksi')->with('sukses','Tambah isi Dompet Sukses');

  }

  public function kurangi_transaksi(){
    $judul = "Kurangi isi Dompet";
    $breadcrumbs = [
      ['link'=>'#','name'=>"Dompet"],
      ['link'=>'#','name'=>"Transaksi"],
      ['link'=>'#','name'=>"kurangi"],
    ];
    $daftar_kas = Kas::pluck('nama','id');
    $daftar_pelanggan = [];

    return view('mimin.dompet.kurangi_transaksi',
    compact('judul','breadcrumbs','daftar_kas','daftar_pelanggan'));
  }

  public function kurangi_transaksi_post(Request $req){
    $pelanggan = User::findOrFail($req->pelanggan_id);

    $rules = [
      'tanggal' =>'required',
      'nominal' => 'required|min:0',
      'kas_id' => 'required',
      'keterangan' => 'required',
      'dibayar_oleh' => 'required',
      'dibayar_kepada' => 'required'
    ];
    $messages =[
        'tanggal.required'=>'Tanggal wajib diisi',
        'nominal.required' => 'Nominal wajib diisi',
        'kas_id.required' => 'Kas wajib diisi',
        'keterangan.required' => 'Keterangan wajib diisi',
        'dibayar_oleh.required' => 'Pembayar wajib diisi',
        'dibayar_kepada.required' => 'yang dibayar wajib disii'
    ];

    $input= $req->all();
    $validator = Validator::make($input, $rules, $messages)->validate();


    DB::transaction(function () use ($req,$pelanggan) {
      $kas_asal = Kas::findOrFail($req->kas_id);
      $nominal = $req->nominal;

      $tanggal = Carbon::createFromFormat('d-m-Y',$req->tanggal);
      $saldo_akhir = $pelanggan->saldo_tanggal($tanggal);
      $saldo_berjalan = $saldo_akhir - $nominal;
      //update saldo

      $pelanggan->update_saldo($tanggal,$nominal,'k');

      $trx_kas = TransaksiKas::create([
        'kas_id'=>$kas_asal->id,
        'tanggal'=> $tanggal,
        'keterangan'=>$req->keterangan,
        'debet_kredit'=>'k',
        'nominal'=>$nominal
      ]);
      $kas_asal->update_saldo($tanggal,$nominal,'k');
      Dompet::create([
        'tanggal'=>$tanggal,
        'nominal'=>$req->nominal,
        'transaksi_kas_id'=>$trx_kas->id,
        'keterangan'=>$req->keterangan,
        'dibayar_oleh'=>$req->dibayar_oleh,
        'dibayar_kepada'=>$req->dibayar_kepada,
        'user_id'=>$pelanggan->id,
        'saldo_berjalan'=>$saldo_berjalan,
        'debet_kredit'=>'kredit'
      ]);

    });
    return redirect()->route('mimin.dompet.transaksi')->with('sukses','Kurangi Dompet Sukses');

  }

  public function cari_pelanggan (Request $req){


    $key = $req->cari;

    if ($req->has("cari")&&$req->cari!=""){

        $daftar_kegiatan = User::where(function ($query) use($key) {
                                    $query->where('nama','like','%'.$key.'%');
                                })->orWhere('email',$key)
                                ->orWhere('nomor_hp',$key)
                                ->paginate(5);

    } else {
        $daftar_kegiatan = Pelanggan::paginate(5);
    }
    $results = array(
                              "results" => $daftar_kegiatan->toArray()['data'],
                              "pagination" =>array(
                                  "more" => $daftar_kegiatan->hasMorePages()
                              )
    );

    return response()->json($results);
}



  public function lihat_transaksi ($id){
      $judul = "Rincian Transaksi";

      $breadcrumbs = [
        ['link'=>'#','name'=>"Dompet"],
        ['link'=>'#','name'=>"Transaksi"],
        ['link'=>'#','name'=>"rincian"],
      ];

      $dompet = Dompet::findOrFail($id);
      return view('mimin.dompet.lihat_transaksi',
      compact('judul','breadcrumbs','dompet'));
  }
}
