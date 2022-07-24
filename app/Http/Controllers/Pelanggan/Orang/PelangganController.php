<?php

namespace App\Http\Controllers\Pelanggan\Orang;

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
use App\KategoriPelanggan;


class PelangganController extends Controller
{


    public function index(Request $req)
    {
      $judul = "Daftar Pelanggan";
      $breadcrumbs = [
          ['link'=>'#','name'=>"Pelanggan"],
      ];

      $daftar_kategori = KategoriPelanggan::pluck("nama","id");
      $daftar_kategori->prepend('Semua','semua');

      $kategori = "semua";
      $cari = "";
      $you = Auth::user();

      if ($you->level == 1){

            $daftar_pelanggan = Pelanggan::with('kategori')->where(function($query) {
              $query->where('parent_id','=',Auth::id())
              ->orWhere('distributor_id',Auth::id());
          });

      } else {
          $daftar_pelanggan = Pelanggan::with('kategori')->where('parent_id','=',Auth::id());
      }

      if ($req->has('cari')){
        $cari = $req->cari;
        $daftar_pelanggan = $daftar_pelanggan
                            ->where(function($query) use ($cari) {
                                $query->where('nama','like','%'.$cari.'%')
                                ->orWhere('nomor_hp',$cari)
                                ->orWhere('kode',$cari);
                            });

      }


      if ($req->has('kategori')){
        $kategori = $req->kategori;

        if ($kategori != "semua"){
            $daftar_pelanggan = $daftar_pelanggan->where('kategori_id',$kategori);

        }
      }

      $daftar_pelanggan = $daftar_pelanggan->orderBy('nama')->paginate(10);


      return view('pelanggan.pelanggan.index',
                      compact('judul','breadcrumbs','daftar_pelanggan','cari','kategori','daftar_kategori')
                  );
    }


    public function edit ($id){

      $judul            = "Edit Pelanggan";
      $judul_deskripsi  = "";
      $deskripsi        = "";

      $breadcrumbs = [
        ['link'=>'#','name'=>"Pelanggan"],
        ['link'=>'#','name'=>"Edit"],
      ];

     // $pelanggan = Pelanggan::findOrFail($pelanggan_id);
$you = Auth::user();
      if ($you->level == 1){

        $pelanggan = Pelanggan::with('kategori')->where(function($query) {
          $query->where('parent_id','=',Auth::id())
          ->orWhere('distributor_id',Auth::id());
      })->where('id',$id)->firstOrFail();

      } else {
          $pelanggan = Pelanggan::with('kategori')->where('parent_id','=',Auth::id())->where('id',$id)->firstOrFail();
      }

      $daftar_kategori = KategoriPelanggan::where('status','Aktif')->pluck('nama','id');

      $nama_leader = $pelanggan->parent->nama;
        $kode_leader = $pelanggan->parent->kode;
        $id_leader = $pelanggan->parent->id;
        $leader_terpilih = [$id_leader=>$nama_leader.'['.$kode_leader.']'];

      return view('pelanggan.pelanggan.edit',
            compact('judul','breadcrumbs','judul_deskripsi','deskripsi','pelanggan','daftar_kategori','leader_terpilih')
      );
    }

    public function update(Request $req, $id){
      $you = Auth::user();
      if ($you->level == 1){

        $pelanggan = Pelanggan::where(function($query) {
          $query->where('parent_id','=',Auth::id())
          ->orWhere('distributor_id',Auth::id());
      })->where('id',$id)->firstOrFail();

      } else {
          $pelanggan = Pelanggan::where('parent_id','=',Auth::id())->where('id',$id)->firstOrFail();
      }

        $rules = [
          'nama' =>'required',
          'email' =>'required|email|unique:users,email,'.$pelanggan->id,
          'nomor_hp' =>'required|unique:users,nomor_hp,'.$pelanggan->id,
          'kode' =>'required|unique:users,kode,'.$pelanggan->id,
          'kategori_id' =>'required',
          'password' => 'confirmed',
          'leader' => 'required',
        ];
        $messages =[

          'nama.required'=>'Nama Lengkap harus diisi',
          'email.required' => 'Email harus diisi',
          'nomor_hp.required' => 'Nomor Hp harus diisi',
          'kategori_id.required' =>'Kategori harus diisi',
          'kode.required' => 'ID Pelanggan Harus Diisi',
          'kode.unique' => 'ID Pelangan Tidak Boleh Sama',
        ];

        $input= $req->all();
        $validator = Validator::make($input, $rules, $messages)->validate();

        $leader = Pelanggan::findOrFail($req->leader);
        $level = 1;
        if ($leader && $leader->id != $pelanggan->id){
          $level = $leader->level + 1;
        }

        if ($pelanggan->status == "Tidak Aktif"){
          $pelanggan->nama = $req->nama;
          $pelanggan->kategori_id = $req->kategori_id;
          $pelanggan->kode = $req->kode;
          if ($leader) {
          $pelanggan->parent_id = $leader->id;
          $pelanggan->distributor_id = $leader->distributor_id;
          }
          $pelanggan->level = $level;
        }

        $pesan_tambahan = "";
        // selain pelannggan tidak boleh diedit
        $user = User::findOrFail($pelanggan->id);
        if ($user->hasRole("Pelanggan")){
          $pelanggan->email = $req->email;
          $pelanggan->nomor_hp = $req->nomor_hp;


        } else if ($user->roles->count()==0) {
          $user->syncRoles(['Pelanggan']);
          $pesan_tambahan = " dan berhasil jadi pelanggan";

        }else {
          $pesan_tambahan = " kecuali email dan Nomor HP nya karena mereka staf atau kurir";
        }




        $pelanggan->save();
        return redirect()->route('pelanggan.pelanggan.index')->with('sukses', $pelanggan->nama. ' berhasil diubah'.$pesan_tambahan);
    }

    public function create (){
        $judul = "Tambah Pelanggan";
        $judul_deskripsi = "";
        $deskripsi = "";
        $breadcrumbs = [
          ['link'=>'#','name'=>"Pelanggan"],
          ['link'=>'#','name'=>"Tambah"],
        ];


        $user = Pelanggan::where('id',Auth::id())->first();


        $daftar_kategori = KategoriPelanggan::where('status','Aktif')->where('id','>',$user->kategori_id)->pluck('nama','id');
        return view('pelanggan.pelanggan.create',
            compact('judul','judul_deskripsi','breadcrumbs','deskripsi','daftar_kategori')
            );
    }

    public function store (Request $req){

      $rules = [

      'nama' =>'required',
      'email' =>'email|unique:users,email',
      'nomor_hp' =>'required|unique:users,nomor_hp',
      'kategori_id' =>'required',
      'kode' => 'required|unique:users,kode',
      'leader'=>'required'

      ];
      $messages =[

      'nama.required'=>'Nama Lengkap harus diisi',
      'email.required' => 'Email harus diisi',
      'nomor_hp.required' => 'Nomor Hp harus diisi',
      'kategori_id.required' =>'Kategori harus diisi',
      'kode.required' => 'ID Pelanggan Harus Diisi',
      'kode.unique' => 'ID Pelangan Tidak Boleh Sama',

      ];



      $input= $req->all();
      $validator = Validator::make($input, $rules, $messages)->validate();

      $user = Pelanggan::where('id',Auth::id())->first();
      if($req->kategori_id <= $user->kategori_id){
        $req->kategori_id = 5 ; // kalau apa,jadikan ecer
      }

      $password = bcrypt("12345678");
      $leader = Pelanggan::findOrFail($req->leader);
      $level = 1;
      if ($leader){
        $level = $leader->level + 1;
      }

      $user = Pelanggan::create([
          'nama'=>$req->nama,
          'email'=>$req->email,
          'nomor_hp'=>$req->nomor_hp,
          'password'=>$password,
          'kategori_id'=>$req->kategori_id,
          'kode' => $req->kode,
          'parent_id' => $leader->id,
          'distributor_id' => $leader->distributor_id,
          'level' =>$level
      ]);
      $user = User::findOrFail($user->id);
      $user->syncRoles(['Pelanggan']);

      return redirect()->route('pelanggan.pelanggan.index')
                       ->with('sukses','Tambah Pelanggan Sukses');
    }

    public function destroye (Request $req, $id){
          try {


          $user = Pelanggan::where('parent_id',Auth::id())
                          ->where('id',$id)
                          ->first();
          $nama = $user->nama;
          User::role(["Pelanggan"])->findOrFail($user->id);
          $result = $user->delete();

          if ($result){
          return response()->json([
            'pesan' => $nama.' Sukses Dihapus',
            'success' => true,
            'redirect'=> route('pelanggan.pelanggan.index')
            ]);
          } else {
            return response()->json([
            'pesan' => $nama.' Gagal Dihapus',
            'success' => false,
            'redirect'=> route('pelanggan.pelanggan.index')
            ]);
          }

          } catch(\Exception $exception){
            return response()->json([
              'pesan' => 'Gagal Dihapus',
              'success' => false,
              'redirect'=> route('pelanggan.pelanggan.index')
          ]);
          }

      }

      public function cari_pelanggan(Request $req)
      {
        $key = $req->cari;

        $you = Auth::user();
        if ($req->has("cari") && $req->cari != "") {

          if ($you->level == 1){

          $daftar_pelanggan = User::with('parent','kategori','distributor')->where(function ($query) use ($key) {
            $query->where('nama', 'like', '%' . $key . '%')
                  ->orWhere('email', $key)
                  ->orWhere('nomor_hp', $key);
          })->where(function ($query)  {
            $query->where('parent_id',Auth::id())
                  ->where('distributor_id',Auth::id()) ;
          })
            ->paginate(5);

        } else {
          $daftar_pelanggan = User::with('parent','kategori','distributor')->where(function ($query) use ($key) {
            $query->where('nama', 'like', '%' . $key . '%')
                  ->orWhere('email', $key)
                  ->orWhere('nomor_hp', $key);
          })->where('parent_id',Auth::id())
            ->paginate(5);

        }



        } else {

            if ($you->level == 1){

              $daftar_pelanggan = Pelanggan::with('parent','kategori','distributor')
              ->where(function ($query)  {
                $query->where('parent_id',Auth::id())
                      ->where('distributor_id',Auth::id()) ;
              })
              ->paginate(5);


          } else {
            $daftar_pelanggan = Pelanggan::with('parent','kategori','distributor')
            ->where('parent_id',Auth::id())
            ->paginate(5);
         }





        }
        $results = array(
          "results" => $daftar_pelanggan->toArray()['data'],
          "pagination" => array(
            "more" => $daftar_pelanggan->hasMorePages()
          )
        );

        return $results;
      }

/*
      public function getCabangId(){
        $akses_global = Auth::user()->can('akses-global');
        if ($akses_global){
            return Session::get('cabang_id',Cabang::first()->id);
        } else {
            return Auth::user()->cabang_id;
        }
      }
      */

}
