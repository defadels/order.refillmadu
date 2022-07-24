<?php

namespace App\Http\Controllers\Admin\Pengaturan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Kecamatan;
use App\Kabupaten;
use App\Provinsi;
use Validator;

class KecamatanController extends Controller
{
  function __construct()
  {
       $this->middleware('permission:pengaturan.wilayah', ['except' => []]);
  }

    public function index($provinsi_id,$kabupaten_id){
      $provinsi = Provinsi::findOrFail($provinsi_id);
      $kabupaten = Kabupaten::findOrFail($kabupaten_id);
      $judul = "Kecamatan";
      $breadcrumbs = [
        ['link'=>'#','name'=>"Pengaturan"],
        ['link'=>'#','name'=>"Kecamatan"],
      ];
      $daftar_kecamatan = Kecamatan::where('kabupaten_id',$kabupaten->id)->paginate(10);
      return view('mimin.pengaturan.provinsi.kabupaten.kecamatan.index',
      compact('judul','breadcrumbs','daftar_kecamatan','provinsi','kabupaten')
      );
    }


    public function create ($provinsi_id,$kabupaten_id){

      $provinsi = Provinsi::findOrFail($provinsi_id);
      $kabupaten = Kabupaten::findOrFail($kabupaten_id);
      $judul = "Kecamatan";
      $judul_deskripsi = "";
      $deskripsi = "";
      $breadcrumbs = [
        ['link'=>'#','name'=>"Pengaturan"],
        ['link'=>'#','name'=>"Kecamatan"],
        ['link'=>'#','name'=>"Tambah"],
      ];

      return view('mimin.pengaturan.provinsi.kabupaten.kecamatan.create',
      compact('judul','judul_deskripsi','breadcrumbs','deskripsi','provinsi','kabupaten')
      );

    }

    public function store (Request $req,$provinsi_id,$kabupaten_id){

      $rules = [
        'nama' =>'required',
        'status' => 'required'
      ];
      $messages =[
          'nama.required'=>'Nama Kecamatan harus diisi',
          'status.required' => 'Status harus diiisi'
      ];

      $input= $req->all();
      $validator = Validator::make($input, $rules, $messages)->validate();

      $provinsi = Provinsi::findOrFail($provinsi_id);
      $kabupaten = Kabupaten::findOrFail($kabupaten_id);
      $kecamatan = Kecamatan::create([
          'nama'=>$req->nama,
          'status'=>$req->status,
          'kabupaten_id'=>$kabupaten->id
      ]);

      return redirect()->route('mimin.pengaturan.provinsi.kabupaten.kecamatan.index',[$provinsi->id,$kabupaten->id])->with('sukses','Tambah Kecamatan Sukses');
    }

    public function edit($provinsi_id,$kabupaten_id,$id){
      $judul = "Kecamatan";
      $judul_deskripsi = "";
      $deskripsi = "";
      $breadcrumbs = [
        ['link'=>'#','name'=>"Pengaturan"],
        ['link'=>'#','name'=>"Kecamatan"],
        ['link'=>'#','name'=>"Edit"],
      ];

      $provinsi = Provinsi::findOrFail($provinsi_id);
      $kabupaten = Kabupaten::findOrFail($kabupaten_id);

      $kecamatan = Kecamatan::findOrFail($id);

      return view('mimin.pengaturan.provinsi.kabupaten.kecamatan.edit',
      compact('judul','breadcrumbs','judul_deskripsi','deskripsi','kecamatan','provinsi','kabupaten')
      );
    }

    public function update(Request $req, $provinsi_id,$kabupaten_id, $id){

      $kecamatan=Kecamatan::findOrFail($id);

      $provinsi = Provinsi::findOrFail($provinsi_id);
      $kabupaten = Kabupaten::findOrFail($kabupaten_id);

      $rules = [
        'nama' =>'required',
        'status' => 'required'
      ];
      $messages =[
          'nama.required'=>'Nama Kecamatan harus diisi',
          'status.required' => 'Status harus diiisi'
      ];


      $input= $req->all();
      $validator = Validator::make($input, $rules, $messages)->validate();

      $kecamatan->nama = $req->nama;
      $kecamatan->status = $req->status;
      $kecamatan->save();

      return redirect()->route('mimin.pengaturan.provinsi.kabupaten.kecamatan.index',[$provinsi->id,$kabupaten->id])->with('sukses', $kecamatan->nama. 'Berhasil diubah');
   }

   public function destroy (Request $req, $provinsi_id,$kabupaten_id, $id){

    try {
      $kecamatan = Kecamatan::findOrFail($id);

      $provinsi = Provinsi::findOrFail($provinsi_id);
      $kabupaten = Kabupaten::findOrFail($kabupaten_id);
      $nama = $kecamatan->nama;
      $result = $kecamatan->delete();
      if ($result){
        return response()->json([
            'judul' => 'Terhapus!',
            'pesan' => $nama.' Sukses Dihapus',
            'success' => true,
            'redirect'=> route('mimin.pengaturan.provinsi.kabupaten.kecamatan.index',[$provinsi->id,$kabupaten->id])
            ]);
      } else {
            return response()->json([
            'judul' => 'Gagal Terhapus',
            'pesan' => $nama.' Gagal Dihapus',
            'success' => false,
            'redirect'=> route('mimin.pengaturan.provinsi.kabupaten.kecamatan.index',[$provinsi->id,$kabupaten->id])
            ]);
      }

    } catch(\Exception $exception){
            return response()->json([
              'judul' =>'Gagal Dihapus',
              'pesan' => 'Terjadi kesalahan atau kecamatan masih terkait dengan data lain',
              'success' => false,
              'redirect'=> route('mimin.pengaturan.provinsi.kabupaten.kecamatan.index',[$provinsi_id,$kabupaten_id])
          ]);
    }

  }
}
