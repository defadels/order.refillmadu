<?php

namespace App\Http\Controllers\Admin\Pengaturan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\MetodePengiriman;
use Validator;

class PengirimanController extends Controller
{
  function __construct()
  {
       $this->middleware('permission:pengaturan.pengiriman', ['except' => []]);
  }

    public function index(){
      $judul = "Pengiriman";
      $breadcrumbs = [
        ['link'=>'#','name'=>"Pengaturan"],
        ['link'=>'#','name'=>"Pengiriman"],
      ];
      $daftar_pengiriman = MetodePengiriman::paginate(10);
      $jenis = [
        'dijemput'=>'Dijemput',
        'diantar'=>'Diantar',
        'custom'=>'Custom'
      ];
      return view('mimin.pengaturan.pengiriman.index',
      compact('judul','breadcrumbs','daftar_pengiriman','jenis')
      );
    }


    public function create (){

      $judul = "Pengiriman";
      $judul_deskripsi = "";
      $deskripsi = "";
      $breadcrumbs = [
        ['link'=>'#','name'=>"Pengaturan"],
        ['link'=>'#','name'=>"Pengiriman"],
        ['link'=>'#','name'=>"Tambah"],
      ];
      $jenis = [
        'dijemput'=>'Dijemput',
        'diantar'=>'Diantar',
        'custom'=>'Custom'
      ];
      return view('mimin.pengaturan.pengiriman.create',
      compact('judul','judul_deskripsi','breadcrumbs','deskripsi','jenis')
      );

    }

    public function store (Request $req){

      $rules = [
        'nama' =>'required',
        'status' => 'required',
        'jenis'=>'required'
      ];
      $messages =[
          'nama.required'=>'Nama Pengiriman harus diisi',
          'status.required' => 'Status harus diiisi'
      ];

      $input= $req->all();
      $validator = Validator::make($input, $rules, $messages)->validate();

      $pengiriman = MetodePengiriman::create([
          'nama'=>$req->nama,
          'status'=>$req->status,
          'jenis'=>$req->jenis,
          'deskripsi'=>$req->deskripsi
      ]);

      return redirect()->route('mimin.pengaturan.pengiriman.index')->with('sukses','Tambah Pengiriman Sukses');
    }

    public function edit($id){
      $judul = "Pengiriman";
      $judul_deskripsi = "";
      $deskripsi = "";
      $breadcrumbs = [
        ['link'=>'#','name'=>"Pengaturan"],
        ['link'=>'#','name'=>"Pengiriman"],
        ['link'=>'#','name'=>"Edit"],
      ];

      $pengiriman = MetodePengiriman::findOrFail($id);
      $jenis = [
        'dijemput'=>'Dijemput',
        'diantar'=>'Diantar',
        'custom'=>'Custom'
      ];
      return view('mimin.pengaturan.pengiriman.edit',
      compact('judul','breadcrumbs','judul_deskripsi','deskripsi','pengiriman','jenis')
      );
    }

    public function update(Request $req, $id){

      $pengiriman=MetodePengiriman::findOrFail($id);

      $rules = [
        'nama' =>'required',
        'status' => 'required',
        'jenis'=>'required'
      ];
      $messages =[
          'nama.required'=>'Nama Pengiriman harus diisi',
          'status.required' => 'Status harus diiisi'
      ];


      $input= $req->all();
      $validator = Validator::make($input, $rules, $messages)->validate();

      if ($pengiriman->id > 2){

      $pengiriman->nama = $req->nama;
      $pengiriman->status = $req->status;
      $pengiriman->jenis = $req->jenis;
      $pengiriman->deskripsi = $req->deskripsi;
      $pengiriman->save();
      }

      return redirect()->route('mimin.pengaturan.pengiriman.index')->with('sukses', $pengiriman->nama. 'Berhasil diubah');
   }

   public function destroy (Request $req, $id){

    try {
      $pengiriman = MetodePengiriman::findOrFail($id);

      $nama = $pengiriman->nama;
          if($pengiriman->id > 2) {
          $result = $pengiriman->delete();
      } else {
        $result = false;
      }
      if ($result){
        return response()->json([
            'judul' => 'Terhapus!',
            'pesan' => $nama.' Sukses Dihapus',
            'success' => true,
            'redirect'=> route('mimin.pengaturan.pengiriman.index')
            ]);
      } else {
            return response()->json([
            'judul' => 'Gagal Terhapus',
            'pesan' => $nama.' Gagal Dihapus',
            'success' => false,
            'redirect'=> route('mimin.pengaturan.pengiriman.index')
            ]);
      }


    } catch(\Exception $exception){
            return response()->json([
              'judul' =>'Gagal Dihapus',
              'pesan' => 'Terjadi kesalahan atau pengiriman masih terkait dengan data lain',
              'success' => false,
              'redirect'=> route('mimin.pengaturan.pengiriman.index')
          ]);
    }

  }
}
