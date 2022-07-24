<?php

namespace App\Http\Controllers\Admin\Pengaturan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Cabang;
use Validator;
use Session;

class CabangController extends Controller
{
  function __construct()
  {
       $this->middleware('permission:pengaturan.cabang', ['except' => []]);
  }

    public function index(){
      $judul = "Cabang";
      $breadcrumbs = [
        ['link'=>'#','name'=>"Pengaturan"],
        ['link'=>'#','name'=>"Cabang"],
      ];
      $daftar_cabang = Cabang::paginate(10);
      return view('mimin.pengaturan.cabang.index',
      compact('judul','breadcrumbs','daftar_cabang')
      );

    }


    public function create (){

      $judul = "Cabang";
      $judul_deskripsi = "";
      $deskripsi = "";
      $breadcrumbs = [
        ['link'=>'#','name'=>"Pengaturan"],
        ['link'=>'#','name'=>"Cabang"],
        ['link'=>'#','name'=>"Tambah"],
      ];

      return view('mimin.pengaturan.cabang.create',
      compact('judul','judul_deskripsi','breadcrumbs','deskripsi')
      );

    }

    public function store (Request $req){

      $rules = [
        'nama' =>'required',
        'status' => 'required'
      ];
      $messages =[
          'nama.required'=>'Nama Cabang harus diisi',
          'status.required' => 'Status harus diiisi'
      ];

      $input= $req->all();
      $validator = Validator::make($input, $rules, $messages)->validate();

      $cabang = Cabang::create([
          'nama'=>$req->nama,
          'deskripsi'=>$req->deskripsi,
          'status'=>$req->status
      ]);

      return redirect()->route('mimin.pengaturan.cabang.index')->with('sukses','Tambah Cabang Sukses');
    }

    public function edit($id){
      $judul = "Cabang";
      $judul_deskripsi = "";
      $deskripsi = "";
      $breadcrumbs = [
        ['link'=>'#','name'=>"Pengaturan"],
        ['link'=>'#','name'=>"Cabang"],
        ['link'=>'#','name'=>"Edit"],
      ];

      $cabang = Cabang::findOrFail($id);

      return view('mimin.pengaturan.cabang.edit',
      compact('judul','breadcrumbs','judul_deskripsi','deskripsi','cabang')
      );
    }

    public function update(Request $req, $id){

      $cabang=Cabang::findOrFail($id);

      $rules = [
        'nama' =>'required',
        'status' => 'required'
      ];
      $messages =[
          'nama.required'=>'Nama Cabang harus diisi',
          'status.required' => 'Status harus diiisi'
      ];


      $input= $req->all();
      $validator = Validator::make($input, $rules, $messages)->validate();

      $cabang->nama = $req->nama;
      $cabang->deskripsi = $req->deskripsi;
      $cabang->status = $req->status;
      $cabang->save();

      return redirect()->route('mimin.pengaturan.cabang.index')->with('sukses', $cabang->nama. 'Berhasil diubah');
   }

   public function destroy (Request $req, $id){

    try {
      $cabang = Cabang::findOrFail($id);

      $nama = $cabang->nama;
      $result = $cabang->delete();
      if ($result){
        return response()->json([
            'judul' => 'Terhapus!',
            'pesan' => $nama.' Sukses Dihapus',
            'success' => true,
            'redirect'=> route('mimin.pengaturan.cabang.index')
            ]);
      } else {
            return response()->json([
            'judul' => 'Gagal Terhapus',
            'pesan' => $nama.' Gagal Dihapus',
            'success' => false,
            'redirect'=> route('mimin.pengaturan.cabang.index')
            ]);
      }

    } catch(\Exception $exception){
            return response()->json([
              'judul' =>'Gagal Dihapus',
              'pesan' => 'Terjadi kesalahan atau cabang masih terkait dengan data lain',
              'success' => false,
              'redirect'=> route('mimin.pengaturan.cabang.index')
          ]);
    }

  }
}
