<?php

namespace App\Http\Controllers\Admin\Pengaturan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Provinsi;
use Validator;

class ProvinsiController extends Controller
{
  function __construct()
  {
       $this->middleware('permission:pengaturan.wilayah', ['except' => []]);
  }


    public function index(){
      $judul = "Provinsi";
      $breadcrumbs = [
        ['link'=>'#','name'=>"Pengaturan"],
        ['link'=>'#','name'=>"Provinsi"],
      ];
      $daftar_provinsi = Provinsi::paginate(10);
      return view('mimin.pengaturan.provinsi.index',
      compact('judul','breadcrumbs','daftar_provinsi')
      );
    }


    public function create (){

      $judul = "Provinsi";
      $judul_deskripsi = "";
      $deskripsi = "";
      $breadcrumbs = [
        ['link'=>'#','name'=>"Pengaturan"],
        ['link'=>'#','name'=>"Provinsi"],
        ['link'=>'#','name'=>"Tambah"],
      ];

      return view('mimin.pengaturan.provinsi.create',
      compact('judul','judul_deskripsi','breadcrumbs','deskripsi')
      );

    }

    public function store (Request $req){

      $rules = [
        'nama' =>'required',
        'status' => 'required'
      ];
      $messages =[
          'nama.required'=>'Nama Provinsi harus diisi',
          'status.required' => 'Status harus diiisi'
      ];

      $input= $req->all();
      $validator = Validator::make($input, $rules, $messages)->validate();

      $provinsi = Provinsi::create([
          'nama'=>$req->nama,
          'status'=>$req->status
      ]);

      return redirect()->route('mimin.pengaturan.provinsi.index')->with('sukses','Tambah Provinsi Sukses');
    }

    public function edit($id){
      $judul = "Provinsi";
      $judul_deskripsi = "";
      $deskripsi = "";
      $breadcrumbs = [
        ['link'=>'#','name'=>"Pengaturan"],
        ['link'=>'#','name'=>"Provinsi"],
        ['link'=>'#','name'=>"Edit"],
      ];

      $provinsi = Provinsi::findOrFail($id);

      return view('mimin.pengaturan.provinsi.edit',
      compact('judul','breadcrumbs','judul_deskripsi','deskripsi','provinsi')
      );
    }

    public function update(Request $req, $id){

      $provinsi=Provinsi::findOrFail($id);

      $rules = [
        'nama' =>'required',
        'status' => 'required'
      ];
      $messages =[
          'nama.required'=>'Nama Provinsi harus diisi',
          'status.required' => 'Status harus diiisi'
      ];


      $input= $req->all();
      $validator = Validator::make($input, $rules, $messages)->validate();

      $provinsi->nama = $req->nama;
      $provinsi->status = $req->status;
      $provinsi->save();

      return redirect()->route('mimin.pengaturan.provinsi.index')->with('sukses', $provinsi->nama. 'Berhasil diubah');
   }

   public function destroy (Request $req, $id){

    try {
      $provinsi = Provinsi::findOrFail($id);

      $nama = $provinsi->nama;
      $result = $provinsi->delete();
      if ($result){
        return response()->json([
            'judul' => 'Terhapus!',
            'pesan' => $nama.' Sukses Dihapus',
            'success' => true,
            'redirect'=> route('mimin.pengaturan.provinsi.index')
            ]);
      } else {
            return response()->json([
            'judul' => 'Gagal Terhapus',
            'pesan' => $nama.' Gagal Dihapus',
            'success' => false,
            'redirect'=> route('mimin.pengaturan.provinsi.index')
            ]);
      }

    } catch(\Exception $exception){
            return response()->json([
              'judul' =>'Gagal Dihapus',
              'pesan' => 'Terjadi kesalahan atau provinsi masih terkait dengan data lain',
              'success' => false,
              'redirect'=> route('mimin.pengaturan.provinsi.index')
          ]);
    }

  }
}
