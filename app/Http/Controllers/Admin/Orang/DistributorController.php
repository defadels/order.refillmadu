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
use App\Distributor;
use Illuminate\Support\Str;
use App\KategoriDistributor;

class DistributorController extends Controller
{

  function __construct()
  {
       $this->middleware('permission:mitra.distributor.lihat', ['only' => ['index','show']]);
       $this->middleware('permission:mitra.distributor.edit', ['only' => ['edit','update','destroy']]);
       $this->middleware('permission:mitra.distributor.tambah', ['only' => ['create','store']]);
  }

  public function index(Request $req)
    {


      $judul = "Daftar Distributor";
      $breadcrumbs = [
          ['link'=>'#','name'=>"Distributor"],
      ];


      $cari = "";
      if ($req->has('cari')){
        $cari = $req->cari;
        $daftar_distributor= Pelanggan::with('kategori')
                            ->where('nama','like','%'.$cari.'%')
                            ->orWhere('nomor_hp',$cari)
                            ->orWhere('kode',$cari)
                            ->paginate(10);

      } else {
        $daftar_distributor = Pelanggan::with('kategori')->simplePaginate(10);
      }


      //return $daftar_distributor;
      return view('mimin.orang.distributor.index',
                      compact('judul','breadcrumbs','daftar_distributor','cari')
                  );
    }


    public function edit ($id){

        $judul            = "Edit Distributor";
        $judul_deskripsi  = "";
        $deskripsi        = "";

        $breadcrumbs = [
          ['link'=>'#','name'=>"Distributor"],
          ['link'=>'#','name'=>"Edit"],
        ];

        $distributor = Pelanggan::findOrFail($id);

        $daftar_kategori = KategoriPelanggan::where('status','Aktif')->pluck('nama','id');

        return view('mimin.orang.distributor.edit',
        compact('judul','breadcrumbs','judul_deskripsi','deskripsi','distributor','daftar_kategori')
        );
    }

    public function update(Request $req, $id){
        $distributor = Pelanggan::findOrFail($id);
        $rules = [
          'nama' =>'required',
          'email' =>'required|email|unique:users,email,'.$distributor->id,
          'nomor_hp' =>'required|unique:users,nomor_hp,'.$distributor->id,
          'kategori_id' =>'required',
          'password' => 'confirmed'

        ];
        $messages =[
          'nama.required'=>'Nama Lengkap harus diisi',
          'email.required' => 'Email harus diisi',
          'nomor_hp.required' => 'Nomor Hp harus diisi',
          'kategori_id.required' =>'Kategori harus diisi'
        ];

        $input= $req->all();
        $validator = Validator::make($input, $rules, $messages)->validate();

        $distributor->nama = $req->nama;

        $pesan_tambahan = "";
        // selain pelannggan tidak boleh diedit
        $user = User::findOrFail($distributor->id);
        if ($req->has('password') && $req->password != ""){
          $passw = bcrypt ($req->password);
          $distributor->password = $passw;
        }
        if ($user->hasRole("Distributor")){
          $distributor->email = $req->email;
          $distributor->nomor_hp = $req->nomor_hp;

        } else if ($user->roles->count()==0) {
          $user->syncRoles(["Pelanggan"]);
          $pesan_tambahan = " dan berhasil jadi distributor";

        }else {
          $pesan_tambahan = " kecuali email dan Nomor HP nya karena mereka staf atau kurir";
        }

        $distributor->kategori_id = $req->kategori_id;
        $distributor->save();
        return redirect()->route('mimin.orang.distributor.index')->with('sukses', $distributor->nama. ' berhasil diubah'.$pesan_tambahan);
    }

    public function create (){
        $judul = "Tambah Distributor";
        $judul_deskripsi = "";
        $deskripsi = "";
        $breadcrumbs = [
          ['link'=>'#','name'=>"Distributor"],
          ['link'=>'#','name'=>"Tambah"],
        ];


        $daftar_kategori = KategoriPelanggan::where('status','Aktif')->pluck('nama','id');
        return view('mimin.orang.distributor.create',
            compact('judul','judul_deskripsi','breadcrumbs','deskripsi','daftar_kategori')
            );
    }

    public function store (Request $req){

      $rules = [
        'nama' =>'required',
        'email' =>'required|email|unique:users,email',
        'nomor_hp' =>'required|unique:users,nomor_hp',
        'kategori_id' =>'required',
        'password' => 'required|confirmed'
      ];
      $messages =[
        'nama.required'=>'Nama Lengkap harus diisi',
        'email.required' => 'Email harus diisi',
        'nomor_hp.required' => 'Nomor Hp harus diisi',
        'kategori_id.required' =>'Kategori harus diisi'
      ];

      $input= $req->all();
      $validator = Validator::make($input, $rules, $messages)->validate();



      $passw = bcrypt ($req->password);

      $distributor->password = $passw;

      $user = Pelanggan::create([
          'nama'=>$req->nama,
          'email'=>$req->email,
          'nomor_hp'=>$req->nomor_hp,
          'password'=>$passw,
          'kategori_id'=>$req->kategori_id,
          'cabang_id'=>$this->getCabangId()
      ]);
      $user = User::findOrFail($user->id);
      $user->syncRoles(["Pelanggan"]);

      return redirect()->route('mimin.orang.distributor.index')->with('sukses','Tambah Distributor Sukses');
    }

    public function destroy (Request $req, $id){
          try {
          $user = Pelanggan::findOrFail($id);
          $nama = $user->nama;
          User::role(["Pelanggan"])->findOrFail($user->id);
          $result = $user->delete();

          if ($result){
          return response()->json([
            'pesan' => $nama.' Sukses Dihapus',
            'success' => true,
            'redirect'=> route('mimin.orang.distributor.index')
            ]);
          } else {
            return response()->json([
            'pesan' => $nama.' Gagal Dihapus',
            'success' => false,
            'redirect'=> route('mimin.orang.distributor.index')
            ]);
          }

          } catch(\Exception $exception){
            return response()->json([
              'pesan' => 'Gagal Dihapus',
              'success' => false,
              'redirect'=> route('mimin.orang.distributor.index')
          ]);
          }

      }

      public function getCabangId(){
        $akses_global = Auth::user()->can('akses-global');
        if ($akses_global){
            return null;
        } else {
            return Auth::user()->cabang_id;
        }
      }
}
