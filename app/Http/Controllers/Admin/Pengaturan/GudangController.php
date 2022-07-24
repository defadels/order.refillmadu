<?php

namespace App\Http\Controllers\Admin\Pengaturan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Gudang;
use Validator;
use App\Cabang;
class GudangController extends Controller
{
  function __construct()
  {
       $this->middleware('permission:pengaturan.gudang', ['except' => []]);
  }

    public function index(){
      $judul = "Gudang";
      $breadcrumbs = [
        ['link'=>'#','name'=>"Pengaturan"],
        ['link'=>'#','name'=>"Gudang"],
      ];

      $daftar_gudang = Gudang::paginate(10);




      return view('mimin.pengaturan.gudang.index',
      compact('judul','breadcrumbs','daftar_gudang')
      );
    }


    public function create (){

      $judul = "Gudang";
      $judul_deskripsi = "";
      $deskripsi = "";
      $breadcrumbs = [
        ['link'=>'#','name'=>"Pengaturan"],
        ['link'=>'#','name'=>"Gudang"],
        ['link'=>'#','name'=>"Tambah"],
      ];

      $daftar_cabang = Cabang::pluck('nama','id');

      return view('mimin.pengaturan.gudang.create',
      compact('judul','judul_deskripsi','breadcrumbs','deskripsi','daftar_cabang')
      );

    }

    public function store (Request $req){

      $rules = [
        'nama' =>'required',
        'status' => 'required'
      ];
      $messages =[
          'nama.required'=>'Nama Gudang harus diisi',
          'status.required' => 'Status harus diiisi'
      ];

      $input= $req->all();
      $validator = Validator::make($input, $rules, $messages)->validate();


      $gudang = Gudang::create([
          'nama'=>$req->nama,
          'status'=>$req->status,
          'cabang_id'=>$req->cabang_id
      ]);

      return redirect()->route('mimin.pengaturan.gudang.index')->with('sukses','Tambah Gudang Sukses');
    }

    public function edit($id){
      $judul = "Gudang";
      $judul_deskripsi = "";
      $deskripsi = "";
      $breadcrumbs = [
        ['link'=>'#','name'=>"Pengaturan"],
        ['link'=>'#','name'=>"Gudang"],
        ['link'=>'#','name'=>"Edit"],
      ];

      $gudang = Gudang::findOrFail($id);

      $daftar_cabang = Cabang::pluck('nama','id');

      return view('mimin.pengaturan.gudang.edit',
      compact('judul','breadcrumbs','judul_deskripsi','deskripsi','gudang','daftar_cabang')
      );
    }

    public function update(Request $req, $id){

      $gudang=Gudang::findOrFail($id);

      $rules = [
        'nama' =>'required',
        'status' => 'required',
        'cabang_id' => 'required'
      ];
      $messages =[
          'nama.required'=>'Nama Gudang harus diisi',
          'status.required' => 'Status harus diiisi'
      ];


      $input= $req->all();
      $validator = Validator::make($input, $rules, $messages)->validate();

      $gudang->nama = $req->nama;
      $gudang->status = $req->status;
      $gudang->cabang_id = $req->cabang_id;
      $gudang->save();

      return redirect()->route('mimin.pengaturan.gudang.index')->with('sukses', $gudang->nama. 'Berhasil diubah');
   }

   public function destroy (Request $req, $id){

    try {
      $gudang = Gudang::findOrFail($id);

      $nama = $gudang->nama;
      $result = $gudang->delete();
      if ($result){
        return response()->json([
            'judul' => 'Terhapus!',
            'pesan' => $nama.' Sukses Dihapus',
            'success' => true,
            'redirect'=> route('mimin.pengaturan.gudang.index')
            ]);
      } else {
            return response()->json([
            'judul' => 'Gagal Terhapus',
            'pesan' => $nama.' Gagal Dihapus',
            'success' => false,
            'redirect'=> route('mimin.pengaturan.gudang.index')
            ]);
      }

    } catch(\Exception $exception){
            return response()->json([
              'judul' =>'Gagal Dihapus',
              'pesan' => 'Terjadi kesalahan atau gudang masih terkait dengan data lain',
              'success' => false,
              'redirect'=> route('mimin.pengaturan.gudang.index')
          ]);
    }

  }
}
