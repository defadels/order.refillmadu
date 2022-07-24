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
use Illuminate\Support\Str;

class KurirController extends Controller
{
  function __construct()
  {
       $this->middleware('permission:mitra.kurirtoko.lihat', ['only' => ['index','show']]);
       $this->middleware('permission:mitra.kurirtoko.edit', ['only' => ['edit','update','destroy']]);
       $this->middleware('permission:mitra.kurirtoko.tambah', ['only' => ['create','store']]);
  }

  public function index(Request $req)
  {

    $judul = "Daftar Kurir";
    $breadcrumbs = [
        ['link'=>'#','name'=>"Kurir"],
    ];


    $cari = "";
    if ($req->has('cari')){
      $cari = $req->cari;
      $daftar_kurir = User::role(["Kurir"])
                          ->where('nama','like','%'.$cari.'%')
                          ->orWhere('nomor_hp',$cari)
                          ->orWhere('kode',$cari)
                          ->paginate(10);

    } else {
      $daftar_kurir = User::role(["Kurir"])->simplePaginate(10);
    }


    return view('mimin.orang.kurir.index',
                    compact('judul','breadcrumbs','daftar_kurir','cari')
                );
  }


  public function edit ($id){

      $judul            = "Edit Kurir";
      $judul_deskripsi  = "";
      $deskripsi        = "";

      $breadcrumbs = [
        ['link'=>'#','name'=>"Kurir"],
        ['link'=>'#','name'=>"Edit"],
      ];

      $kurir = User::role(["Kurir"])->findOrFail($id);

      $daftar_cabang = Cabang::pluck('nama','id');
      return view('mimin.orang.kurir.edit',
      compact('judul','breadcrumbs','judul_deskripsi','deskripsi','kurir','daftar_cabang')
      );
  }

  public function update(Request $req, $id){
      $kurir = User::role(["Kurir"])->findOrFail($id);
      $rules = [
        'nama' =>'required',
        'email' =>'required|email|unique:users,email,'.$kurir->id,
        'nomor_hp' =>'required|unique:users,nomor_hp,'.$kurir->id,

        'password' => 'sometimes|confirmed'

      ];
      $messages =[

        'nama.required'=>'Nama Lengkap harus diisi',
        'email.required' => 'Email harus diisi',
        'nomor_hp.required' => 'Nomor Hp harus diisi'
      ];

      $input= $req->all();
      $validator = Validator::make($input, $rules, $messages)->validate();

      $kurir->nama = $req->nama;

      $pesan_tambahan = "";
      // selain pelannggan tidak boleh diedit
        $kurir->email = $req->email;
        $kurir->nomor_hp = $req->nomor_hp;
        $kurir->cabang_id = $req->cabang_id;

        if ($req->has('password') && $req->password != ""){
          $passw = bcrypt ($req->password);
          $kurir->password = $passw;
        }

      $kurir->save();
      return redirect()->route('mimin.orang.kurir.index')->with('sukses', $kurir->nama. ' berhasil diubah');
  }

  public function create (){
      $judul = "Tambah Kurir";
      $judul_deskripsi = "";
      $deskripsi = "";
      $breadcrumbs = [
        ['link'=>'#','name'=>"Kurir"],
        ['link'=>'#','name'=>"Tambah"],
      ];


      $daftar_cabang = Cabang::pluck('nama','id');
      return view('mimin.orang.kurir.create',
          compact('judul','judul_deskripsi','breadcrumbs','deskripsi','daftar_cabang')
          );

  }

  public function store (Request $req){

    $rules = [
    'nama' =>'required',
    'email' =>'required|email|unique:users,email',
    'nomor_hp' =>'required|unique:users,nomor_hp',

    'password' => 'required|confirmed'

    ];
    $messages =[

    'nama.required'=>'Nama Lengkap harus diisi',
    'email.required' => 'Email harus diisi',
    'nomor_hp.required' => 'Nomor Hp harus diisi'
    ];

    $input= $req->all();
    $validator = Validator::make($input, $rules, $messages)->validate();

    $password = bcrypt ($req->password);

    $user = User::role(["Kurir"])->create([
        'nama'=>$req->nama,
        'email'=>$req->email,
        'nomor_hp'=>$req->nomor_hp,
        'password'=>$password,
        'kategori_id'=>$req->kategori_id,
        'cabang_id'=>$req->cabang_id
    ]);
    $user = User::findOrFail($user->id);
    $user->syncRoles(['Kurir']);

    return redirect()->route('mimin.orang.kurir.index')->with('sukses','Tambah Kurir Sukses');
  }

  public function destroy (Request $req, $id){
        try {


        $user = User::role(["Kurir"])->where('cabang_id',$this->getCabangId())->findOrFail($id);
        $nama = $user->nama;
        $result = $user->delete();

        if ($result){
        return response()->json([
          'pesan' => $nama.' Sukses Dihapus',
          'success' => true,
          'redirect'=> route('mimin.orang.kurir.index')
          ]);
        } else {
          return response()->json([
          'pesan' => $nama.' Gagal Dihapus',
          'success' => false,
          'redirect'=> route('mimin.orang.kurir.index')
          ]);
        }

        } catch(\Exception $exception){
          return response()->json([
            'pesan' => 'Gagal Dihapus',
            'success' => false,
            'redirect'=> route('mimin.orang.kurir.index')
        ]);
        }

    }

    public function getCabangId(){
      $akses_global = Auth::user()->can('akses-global');
      if ($akses_global){
          return Session::get('cabang_id',Cabang::first()->id);
      } else {
          return Auth::user()->cabang_id;
      }
    }

}
