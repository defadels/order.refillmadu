<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Kas;

use Validator;
use Str;

use Carbon\Carbon;

class KasController extends Controller
{
  function __construct()
  {

       $this->middleware('permission:kas.lihat', ['only' => ['index','show']]);
       $this->middleware('permission:kas.edit', ['only' => ['edit','update','destroy']]);
       $this->middleware('permission:kas.tambah', ['only' => ['create','store']]);



  }
  public function index()
  {
        $title = "Kas";
        $judul = "Kas";
        $judul_deskripsi = "";
        $deskripsi = "";

        $breadcrumbs = [
          ['link'=>'#','name'=>"Kas"],
        ];

        $daftar_kas = Kas::orderBy('jenis','asc')->get();

        return view('mimin.kas.index',
        compact('title','judul','judul_deskripsi','deskripsi','daftar_kas','breadcrumbs')
        );
  }

  public function create(){
      $title = "Tambah Kas";
      $judul = "Tambah Kas";
      $judul_deskripsi = "";
      $deskripsi = "";
      $breadcrumbs = [
        ['link'=>route('mimin.kas.index'),'name'=>"Kas"],
        ['link'=>'#','name'=>"Tambah"],
      ];

      $jenis= ['ditangan'=>'On Hand','bank'=>"Bank"];


      return view('mimin.kas.create',
      compact('title','judul','judul_deskripsi','deskripsi','breadcrumbs','jenis')
      );

  }

  public function store (Request $req){


  $rules = [
      'nama' =>'required',
  //    'saldo_awal' =>'required',
      'jenis' =>'required',
  ];

  $messages =[
  ];
      $input= $req->all();
      $validator = Validator::make($input, $rules, $messages)->validate();

      $kas = Kas::create([
          'nama'=>$req->nama,
          'keterangan'=>$req->keterangan,
          'saldo_awal'=>$req->saldo_awal,
          'saldo'=>$req->saldo_awal,
          'jenis'=>$req->jenis
      ]);

      return redirect()->route('mimin.kas.index')->with('sukses','Tambah Kas Sukses');
  }

  public function edit($id){
      $title = "Edit Kas";
      $judul = "Edit Kas";
      $judul_deskripsi = "";
      $deskripsi = "";

      $breadcrumbs = [
        ['link'=>route('mimin.kas.index'),'name'=>"Kas"],
        ['link'=>'#','name'=>"Edit"],
      ];

      $jenis= ['ditangan'=>'On Hand','bank'=>"Bank"];
      $kas = Kas::findOrFail($id);

      return view('mimin.kas.edit',
      compact('title','judul','judul_deskripsi','deskripsi','kas','breadcrumbs','jenis')
      );

  }

  public function update (Request $req,$id){

      $kas = Kas::findOrFail($id);

      $rules = [
        'nama' =>'required',
     //   'saldo_awal' =>'required',
        'jenis' =>'required',
      ];

    $messages =[
        'judul.required'=>'Judul Harus diisi',
        'isi.required'=>'Isi Harus diisi'
    ];

      $input= $req->all();
      $validator = Validator::make($input, $rules, $messages)->validate();

      if ($kas->id != 1 ) { // 1 kas utama
            $kas->nama               = $req->nama;
            $kas->keterangan         = $req->keterangan;

          if(!$kas->daftar_transaksi()->exists()){
            $kas->saldo_awal         = $req->saldo_awal; // kalau sudah terisi, saldo tidak bs diubah
            $kas->saldo              = $req->saldo_awal;
          }
            $kas->jenis              = $req->jenis;

            $kas->save();
      }

      return redirect()->route('mimin.kas.index')->with('sukses','Edit Kas Sukses');
  }

  public function show(Request $req, $id){

    $kas = Kas::findOrFail($id);
    $breadcrumbs = [
      ['link'=>route('mimin.kas.index'),'name'=>"Kas"],
      ['link'=>'#','name'=>$kas->nama],
      ['link'=>'#','name'=>"Transaksi"],
    ];

      $title = $kas->nama;
      $judul = $kas->nama;
      $judul_deskripsi = "";
      $deskripsi = "";

      $sekarang = Carbon::now();
      $bulan = $sekarang->format('m');
      $tahun = $sekarang->format('Y');

      if ($req->has('bulan')){
        $bulan = $req->bulan;
      }
      if ($req->has('tahun')){
        $tahun = $req->tahun;
      }
      $daftar_bulan = ['01'=>'Januari',
                       '02'=>'Februari',
                       '03'=>'Maret',
                       '04'=>'April',
                       '05'=>'Mei',
                       '06'=>'Juni',
                       '07'=>'Juli',
                       '08'=>'Agustus',
                       '09'=>'September',
                       '10'=>'Oktober',
                       '11'=>'November',
                       '12'=>'Desember',
                      ];

      $daftar_tahun = ['2020'=>'2020','2021'=>'2021','2022'=>'2022','2023'=>'2023','2024'=>'2024','2025'=>'2025',
      '2026'=>'2026','2027'=>'2027','2028'=>'2028','2029'=>'2029','2030'=>'2030'];


      $q = $kas->daftar_transaksi()
      ->whereMonth('tanggal',$bulan)
      ->whereYear('tanggal',$tahun)
      ->orderBy('tanggal','asc')
      ->orderBy('created_at','asc'); // harus ini biar gk berantakan
      $daftar_transaksi = clone $q;

      $bulan_lalu = Carbon::createFromFormat('d-m-Y','15-'.$bulan.'-'.$tahun)->subMonth();
      $daftar_transaksi = $daftar_transaksi->paginate(3);
      $saldo_bulan_lalu = $kas->saldo_tanggal($bulan_lalu);

      $take = $daftar_transaksi->firstItem()-1;

      $daftar_trx_sebelumnya = $q->take($take)->get();
      $total_debet = $daftar_trx_sebelumnya->where('debet_kredit','=',"d")->sum('nominal');
      $total_kredit = $daftar_trx_sebelumnya->where('debet_kredit','=',"k")->sum('nominal');
      $saldo_awal = $saldo_bulan_lalu + $total_debet - $total_kredit;

      return view('mimin.kas.transaksi',
      compact('title','judul','judul_deskripsi','deskripsi','kas','breadcrumbs','daftar_transaksi','daftar_bulan','daftar_tahun','bulan','tahun','saldo_awal')
      );
  }
}
