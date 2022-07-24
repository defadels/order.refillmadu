<?php

namespace App\Http\Controllers\Admin\Pengaturan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Kabupaten;
use Validator;
use App\Provinsi;

class KabupatenController extends Controller
{
  function __construct()
  {
       $this->middleware('permission:pengaturan.wilayah', ['except' => []]);
  }

    public function index($id){
      $judul = "Kabupaten";
      $breadcrumbs = [
        ['link'=>'#','name'=>"Pengaturan"],
        ['link'=>'#','name'=>"Kabupaten"],
      ];
      $provinsi = Provinsi::findOrFail($id);
      $daftar_kabupaten = Kabupaten::where('provinsi_id',$provinsi->id)->paginate(10);
      return view('mimin.pengaturan.provinsi.kabupaten.index',
      compact('judul','breadcrumbs','daftar_kabupaten','provinsi')
      );
    }


    public function create ($id){

      $judul = "Kabupaten";
      $judul_deskripsi = "";
      $deskripsi = "";
      $breadcrumbs = [
        ['link'=>'#','name'=>"Pengaturan"],
        ['link'=>'#','name'=>"Kabupaten"],
        ['link'=>'#','name'=>"Tambah"],
      ];
      $provinsi = Provinsi::findOrFail($id);

      return view('mimin.pengaturan.provinsi.kabupaten.create',
      compact('judul','judul_deskripsi','breadcrumbs','deskripsi','provinsi')
      );

    }

    public function store (Request $req,$id){
      $provinsi = Provinsi::findOrFail($id);

      $rules = [
        'nama' =>'required',
        'status' => 'required'
      ];
      $messages =[
          'nama.required'=>'Nama Kabupaten harus diisi',
          'status.required' => 'Status harus diiisi'
      ];

      $input= $req->all();
      $validator = Validator::make($input, $rules, $messages)->validate();

      $kabupaten = Kabupaten::create([
          'nama'=>$req->nama,
          'status'=>$req->status,
          'provinsi_id'=>$provinsi->id
      ]);

      return redirect()->route('mimin.pengaturan.provinsi.kabupaten.index',$provinsi->id)->with('sukses','Tambah Kabupaten Sukses');
    }

    public function edit($provinsi_id,$id){
      $provinsi = Provinsi::findOrFail($provinsi_id);
      $judul = "Kabupaten";
      $judul_deskripsi = "";
      $deskripsi = "";
      $breadcrumbs = [
        ['link'=>'#','name'=>"Pengaturan"],
        ['link'=>'#','name'=>"Kabupaten"],
        ['link'=>'#','name'=>"Edit"],
      ];

      $kabupaten = Kabupaten::findOrFail($id);

      return view('mimin.pengaturan.provinsi.kabupaten.edit',
      compact('judul','breadcrumbs','judul_deskripsi','deskripsi','kabupaten','provinsi')
      );
    }

    public function update(Request $req,$provinsi_id, $id){

      $provinsi = Provinsi::findOrFail($provinsi_id);
      $kabupaten=Kabupaten::findOrFail($id);

      $rules = [
        'nama' =>'required',
        'status' => 'required'
      ];
      $messages =[
          'nama.required'=>'Nama Kabupaten harus diisi',
          'status.required' => 'Status harus diiisi'
      ];


      $input= $req->all();
      $validator = Validator::make($input, $rules, $messages)->validate();

      $kabupaten->nama = $req->nama;
      $kabupaten->status = $req->status;
      $kabupaten->save();

      return redirect()->route('mimin.pengaturan.provinsi.kabupaten.index',$provinsi->id)->with('sukses', $kabupaten->nama. 'Berhasil diubah');
   }

   public function destroy (Request $req,$provinsi_id, $id){

    try {
      $kabupaten = Kabupaten::findOrFail($id);

      $nama = $kabupaten->nama;
      $result = $kabupaten->delete();
      if ($result){
        return response()->json([
            'judul' => 'Terhapus!',
            'pesan' => $nama.' Sukses Dihapus',
            'success' => true,
            'redirect'=> route('mimin.pengaturan.provinsi.kabupaten.index',$provinsi_id)
            ]);
      } else {
            return response()->json([
            'judul' => 'Gagal Terhapus',
            'pesan' => $nama.' Gagal Dihapus',
            'success' => false,
            'redirect'=> route('mimin.pengaturan.provinsi.kabupaten.index',$provinsi_id)
            ]);
      }

    } catch(\Exception $exception){
            return response()->json([
              'judul' =>'Gagal Dihapus',
              'pesan' => 'Terjadi kesalahan atau kabupaten masih terkait dengan data lain',
              'success' => false,
              'redirect'=> route('mimin.pengaturan.provinsi.kabupaten.index',$provinsi_id)
          ]);
    }

  }
}
