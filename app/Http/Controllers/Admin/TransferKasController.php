<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\TransferKas;
use App\TransaksiKas;
use App\Kas;

use Validator;
use Str;
use DB;
use Carbon\Carbon;

class TransferKasController extends Controller
{
  function __construct()
  {
       $this->middleware('permission:kas.transfer.lihat', ['only' => ['index','show']]);
       $this->middleware('permission:kas.transfer.edit', ['only' => ['edit','update','destroy']]);
       $this->middleware('permission:kas.transfer.tambah', ['only' => ['create','store']]);
  }

  public function index(Request $req)
  {
        $title = "Riwayat Transfer Kas";
        $judul = "Riwayat Transfer Kas";
        $judul_deskripsi = "";
        $deskripsi = "";

        $breadcrumbs = [
          ['link'=>'#','name'=>"Kas"],
          ['link'=>'#','name'=>"TransferKas"],
          ['link'=>'#','name'=>"Riwayat"],
        ];


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

        $daftar_transfer_kas = TransferKas::with('debet','kredit')
                                            ->select('transfer_kas.*','transaksi_kas.tanggal')
                                            ->join('transaksi_kas', 'transaksi_kas.id', '=', 'transfer_kas.trx_debet_id')
                                            ->orderBy('transaksi_kas.tanggal','desc')
                                            ->whereMonth('transaksi_kas.tanggal',$bulan)
                                            ->whereYear('transaksi_kas.tanggal',$tahun)
                                            ->paginate(30);
//                                            return $daftar_transfer_kas;


        return view('mimin.kas.transfer.index',
        compact('title','judul','judul_deskripsi','deskripsi','daftar_transfer_kas','breadcrumbs','bulan','tahun','daftar_bulan','daftar_tahun')
        );
  }

  public function create(Request $req){
      $title = "Transfer Kas";
      $judul = "Transfer Kas";
      $judul_deskripsi = "";
      $deskripsi = "";
      $breadcrumbs = [
        ['link'=>route('mimin.kas.transfer.index'),'name'=>"TransferKas"],
        ['link'=>'#','name'=>"Tambah"],
      ];

      $kas_asal_id = null;

      if ($req->has('asal')){
        $kas_asal_id = $req->asal;
      }

      $kas_asal = Kas::find($kas_asal_id);
      $daftar_asal = Kas::pluck('nama','id');

      $daftar_tujuan = Kas::where('id','!=',$kas_asal_id)->pluck('nama','id');


      return view('mimin.kas.transfer.create',
      compact('title','judul','judul_deskripsi','deskripsi','breadcrumbs','daftar_asal','daftar_tujuan','kas_asal')
      );

  }

  public function store (Request $req){
    if ($req->asal_id == $req->tujuan_id){
      return redirect()->back()->withError("Tidak boleh sama rekening asal dan tujuannya")->withInput();
    }

  $rules = [
    'tanggal'=>'required',
    'asal_id'=>'required',
    'tujuan_id'=>'required',
    'nominal'=>'required|min:0',
    'keterangan'=>'required'
  ];

  $messages =[
  ];
      $input= $req->all();
      $validator = Validator::make($input, $rules, $messages)->validate();

      $tanggal = Carbon::createFromFormat('d-m-Y',$req->tanggal);
      $nominal = $req->nominal;
      $keterangan = $req->keterangan;

      DB::transaction(function () use ($req,$nominal,$tanggal,$keterangan) {

        $kas_asal = Kas::findOrFail($req->asal_id);
        $kas_tujuan = Kas::findOrFail($req->tujuan_id);

        // keluarkan dari asal
        $trx_asal = TransaksiKas::create([
          'kas_id'=>$kas_asal->id,
          'tanggal'=> $tanggal,
          'keterangan'=>$keterangan,
          'debet_kredit'=>'k',
          'nominal'=>$nominal
        ]);
      // masukkan ke tujuan
      $trx_tujuan = TransaksiKas::create([
        'kas_id'=>$kas_tujuan->id,
        'tanggal'=>$tanggal,
        'keterangan'=>$keterangan,
        'debet_kredit'=>'d',
        'nominal'=>$nominal
      ]);
      // update saldo

      $kas_asal->update_saldo($tanggal,$nominal,'k');
      $kas_tujuan->update_saldo($tanggal,$nominal,'d');

      // catat di transfer kas
      $transfer_kas = TransferKas::create([
        'trx_kredit_id'=>$trx_asal->id,
        'trx_debet_id'=>$trx_tujuan->id,
        'oleh'=>$req->oleh
      ]);

    });


      return redirect()->route('mimin.kas.transfer.index')->with('sukses','transfer Kas Sukses');
  }

  // ini sengaja dipake buat hapus, biar gk capek2 bikin route
  public function edit($id){
      $title = "Hapus Transfer Kas";
      $judul = "Hapus Transfer Kas";
      $judul_deskripsi = "";
      $deskripsi = "";

      $breadcrumbs = [
        ['link'=>route('mimin.kas.index'),'name'=>"Kas"],
        ['link'=>route('mimin.kas.transfer.index'),'name'=>"Transfer"],
        ['link'=>'#','name'=>"Hapus"],
      ];

      $transfer_kas = TransferKas::findOrFail($id);

      $daftar = Kas::pluck('nama','id');

      return view('mimin.kas.transfer.hapus',
      compact('title','judul','judul_deskripsi','deskripsi','transfer_kas','breadcrumbs','daftar')
      );
  }

  public function destroy($id){


    DB::transaction(function () use ($id) {

    $transfer_kas = TransferKas::findOrFail($id);

    //balikin saldonya
    $tanggal = $transfer_kas->debet->tanggal;
    $nominal = $transfer_kas->debet->nominal;
    $debet = $transfer_kas->debet;
    $kredit = $transfer_kas->kredit;

    $debet->kas->update_saldo($tanggal,$nominal,"k");
    $kredit->kas->update_saldo($tanggal,$nominal,"d");

    $transfer_kas->delete();
    $debet->delete();
    $kredit->delete();

    });

    return redirect()->route('mimin.kas.transfer.index')->with('sukses','Hapus Transaksi Sukses');
  }

}
