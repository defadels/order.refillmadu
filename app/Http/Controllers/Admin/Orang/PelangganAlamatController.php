<?php

namespace App\Http\Controllers\Admin\Orang;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\User;
use Spatie\Permission\Models\Role;
use Validator;
use Hash;
use Auth;
use Session;
use App\Cabang;
use App\Pelanggan;
use Illuminate\Support\Str;
use App\AlamatPelanggan;
use App\KategoriPelanggan;

class PelangganAlamatController extends Controller
{

  function __construct()
  {
       $this->middleware('permission:mitra.pelanggan.lihat', ['only' => ['index','show']]);
       $this->middleware('permission:mitra.pelanggan.edit', ['only' => ['edit','update','destroy']]);
       $this->middleware('permission:mitra.pelanggan.tambah', ['only' => ['create','store']]);
  }
    public function index($pelanggan_id){

      $judul = "Daftar Alamat";
      $breadcrumbs = [
          ['link'=>'#','name'=>"Pelanggan"],
          ['link'=>'#','name'=>"Alamat"],
      ];

      $pelanggan = Pelanggan::findOrFail($pelanggan_id);

      $daftar_alamat = $pelanggan->daftar_alamat()->orderBy('label')->paginate(10);


      return view('mimin.orang.pelanggan.alamat.index',
                      compact('judul','breadcrumbs','daftar_alamat','pelanggan')
                  );

    }

    public function create($pelanggan_id){
        $judul = "Daftar Alamat";
        $breadcrumbs = [
            ['link'=>'#','name'=>"Pelanggan"],
            ['link'=>'#','name'=>"Alamat"],
            ['link'=>'#','name'=>"Tambah"],
        ];

        $pelanggan = Pelanggan::findOrFail($pelanggan_id);


        return view('mimin.orang.pelanggan.alamat.create',
              compact('judul','breadcrumbs','pelanggan')
        );
    }


    public function store (Request $req,$pelanggan_id){

      $rules = [
        'label' =>'required',
        'nama' =>'required',
        'nomor_hp' => 'required',
        'alamat' =>'required',

        ];
        $messages =[
        ];

        $input= $req->all();
        $validator = Validator::make($input, $rules, $messages)->validate();
        $pelanggan = Pelanggan::findOrFail($pelanggan_id);

        $user = AlamatPelanggan::create([
            'pelanggan_id'=>$pelanggan->id,
            'label'=>$req->label,
            'nama'=>$req->nama,
            'nomor_hp'=>$req->nomor_hp,
            'alamat'=>$req->alamat,
        ]);

        return redirect()->route('mimin.orang.pelanggan.alamat.index',$pelanggan)->with('sukses','Tambah Alamat Pelanggan Sukses');
    }

    public function edit ($pelanggan_id,$alamat_id){

      $judul            = "Edit Pelanggan";
      $judul_deskripsi  = "";
      $deskripsi        = "";

      $breadcrumbs = [
        ['link'=>'#','name'=>"Pelanggan"],
        ['link'=>'#','name'=>"Alamat"],
        ['link'=>'#','name'=>"Edit"],
      ];

      $pelanggan = Pelanggan::findOrFail($pelanggan_id);
      $alamat = AlamatPelanggan::findOrFail($alamat_id);

      return view('mimin.orang.pelanggan.alamat.edit',
            compact('judul','breadcrumbs','judul_deskripsi','deskripsi','pelanggan','alamat')
      );

  }


  public function update(Request $req, $pelanggan_id, $alamat_id){
      $pelanggan = Pelanggan::findOrFail($pelanggan_id);

      $alamat = AlamatPelanggan::findOrFail($alamat_id);
      $rules = [
        'label' =>'required',
        'nama' =>'required',
        'nomor_hp' => 'required',
        'alamat' =>'required',
      ];

      $messages =[];

      $input= $req->all();
      $validator = Validator::make($input, $rules, $messages)->validate();

      $alamat->label      = $req->label;
      $alamat->nama       = $req->nama;
      $alamat->nomor_hp   = $req->nomor_hp;
      $alamat->alamat     = $req->alamat;

      $alamat->save();
      return redirect()->route('mimin.orang.pelanggan.alamat.index',[$pelanggan->id,$alamat->id])->with('sukses', 'Alamat ' . $pelanggan->nama. ' berhasil diubah');

  }

  public function destroy (Request $req, $id,$alamat_id){
    try {
    $user = Pelanggan::findOrFail($id);
    $alamat = AlamatPelanggan::findOrFail($alamat_id);
    $nama = $alamat->label;

    $result = $alamat->delete();

    if ($result){
    return response()->json([
      'pesan' => 'Alamat'.$nama.' dari '.$user->nama.' Sukses Dihapus',
      'success' => true,
      'redirect'=> route('mimin.orang.pelanggan.alamat.index',$user->id)
      ]);
    } else {
      return response()->json([
      'pesan' => 'Alamat '.$nama.' dari '.$user->nama.' Gagal Dihapus',
      'success' => false,
      'redirect'=> route('mimin.orang.pelanggan.alamat.index',$user->id)
      ]);
    }

    } catch(\Exception $exception){
      return response()->json([
        'pesan' => 'Gagal Dihapus',
        'success' => false,
        'redirect'=> route('mimin.orang.pelanggan.alamat.index',$id)
    ]);
    }

}

}
