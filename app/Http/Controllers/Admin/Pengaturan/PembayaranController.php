<?php

namespace App\Http\Controllers\Admin\Pengaturan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\MetodePembayaran;
use App\Kas;
use Validator;

class PembayaranController extends Controller
{
  function __construct()
  {
       $this->middleware('permission:pengaturan.pembayaran', ['except' => []]);
  }

    public function index(){
      $judul = "Pembayaran";
      $breadcrumbs = [
        ['link'=>'#','name'=>"Pengaturan"],
        ['link'=>'#','name'=>"Pembayaran"],
      ];
      $daftar_pembayaran = MetodePembayaran::paginate(10);
      return view('mimin.pengaturan.pembayaran.index',
      compact('judul','breadcrumbs','daftar_pembayaran')
      );
    }


    public function create (){

      $judul = "Pembayaran";
      $judul_deskripsi = "";
      $deskripsi = "";
      $breadcrumbs = [
        ['link'=>'#','name'=>"Pengaturan"],
        ['link'=>'#','name'=>"Pembayaran"],
        ['link'=>'#','name'=>"Tambah"],
      ];
      $jenis = [
        'cod'=>'COD',
        'cash'=>'Cash',
        'bank'=>'Bank',
        'dompet'=>'Dompet',
        'custom'=>'Custom'
      ];

      $daftar_kas= Kas::cabangku()->pluck('nama','id');

      return view('mimin.pengaturan.pembayaran.create',
      compact('judul','judul_deskripsi','breadcrumbs','deskripsi','jenis','daftar_kas')
      );

    }

    public function store (Request $req){

      $rules = [
        'nama' =>'required',
        'status' => 'required',
        'jenis'=>'required',

      ];
      $messages =[
          'nama.required'=>'Nama Pembayaran harus diisi',
          'status.required' => 'Status harus diiisi'
      ];

      $input= $req->all();
      $validator = Validator::make($input, $rules, $messages)->validate();

      $pembayaran = MetodePembayaran::create([
          'nama'=>$req->nama,
          'status'=>$req->status,
          'jenis'=>$req->jenis,
          'deskripsi'=>$req->deskripsi
      ]);

      return redirect()->route('mimin.pengaturan.pembayaran.index')->with('sukses','Tambah Pembayaran Sukses');
    }

    public function edit($id){
      $judul = "Pembayaran";
      $judul_deskripsi = "";
      $deskripsi = "";
      $breadcrumbs = [
        ['link'=>'#','name'=>"Pengaturan"],
        ['link'=>'#','name'=>"Pembayaran"],
        ['link'=>'#','name'=>"Edit"],
      ];
      $jenis = [
        'cod'=>'COD',
        'cash'=>'Cash',
        'bank'=>'Bank',
        'dompet'=>'Dompet',
        'custom'=>'Custom'
      ];

      $pembayaran = MetodePembayaran::findOrFail($id);

      $daftar_kas= Kas::cabangku()->pluck('nama','id');

      return view('mimin.pengaturan.pembayaran.edit',
      compact('judul','breadcrumbs','judul_deskripsi','deskripsi','pembayaran','jenis','daftar_kas')
      );
    }

    public function update(Request $req, $id){

      $pembayaran=MetodePembayaran::findOrFail($id);

      $rules = [
        'nama' =>'required',
        'status' => 'required',
        'jenis'=>'required'
      ];
      $messages =[
          'nama.required'=>'Nama Pembayaran harus diisi',
          'status.required' => 'Status harus diiisi'
      ];


      $input= $req->all();
      $validator = Validator::make($input, $rules, $messages)->validate();

      if($pembayaran->id > 3) {

        // ID 1 sampai 3 tidak boleh diedit...
        // 1 COD,
        // 2 Bayar di Toko,
        // 3 Dompet.

            $pembayaran->nama = $req->nama;
            $pembayaran->status = $req->status;
            $pembayaran->jenis = $req->jenis;
            if ($pembayaran->jenis=="dompet"){
              $pembayaran->kas_id = null;
            } else{
            $pembayaran->kas_id = $req->kas_id;
            }
            $pembayaran->deskripsi = $req->deskripsi;
            $pembayaran->save();

      }

      return redirect()->route('mimin.pengaturan.pembayaran.index')->with('sukses', $pembayaran->nama. 'Berhasil diubah');
   }

   public function destroy (Request $req, $id){

    try {
      $pembayaran = MetodePembayaran::findOrFail($id);

      $nama = $pembayaran->nama;
      if($pembayaran->id > 3) {
      $result = $pembayaran->delete();
      } else {
        $result = false;
      }
      if ($result){
        return response()->json([
            'judul' => 'Terhapus!',
            'pesan' => $nama.' Sukses Dihapus',
            'success' => true,
            'redirect'=> route('mimin.pengaturan.pembayaran.index')
            ]);
      } else {
            return response()->json([
            'judul' => 'Gagal Terhapus',
            'pesan' => $nama.' Gagal Dihapus',
            'success' => false,
            'redirect'=> route('mimin.pengaturan.pembayaran.index')
            ]);
      }

    } catch(\Exception $exception){
            return response()->json([
              'judul' =>'Gagal Dihapus',
              'pesan' => 'Terjadi kesalahan atau pembayaran masih terkait dengan data lain',
              'success' => false,
              'redirect'=> route('mimin.pengaturan.pembayaran.index')
          ]);
    }

  }
}
