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
use App\KategoriPelanggan;

class PelangganController extends Controller
{
  function __construct()
  {
       $this->middleware('permission:mitra.pelanggan.lihat', ['only' => ['index','show']]);
       $this->middleware('permission:mitra.pelanggan.edit', ['only' => ['edit','update','destroy']]);
       $this->middleware('permission:mitra.pelanggan.tambah', ['only' => ['create','store']]);
  }

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

      $daftar_pelanggan = Pelanggan::with('kategori');

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


      return view('mimin.orang.pelanggan.index',
                      compact('judul','breadcrumbs','daftar_pelanggan','cari','kategori','daftar_kategori')
                  );
    }


    public function edit ($pelanggan_id){

        $judul            = "Edit Pelanggan";
        $judul_deskripsi  = "";
        $deskripsi        = "";

        $breadcrumbs = [
          ['link'=>'#','name'=>"Pelanggan"],
          ['link'=>'#','name'=>"Edit"],
        ];

        $pelanggan = Pelanggan::findOrFail($pelanggan_id);

        $daftar_kategori = KategoriPelanggan::where('status','Aktif')->pluck('nama','id');
        $nama_leader = $pelanggan->parent->nama;
        $kode_leader = $pelanggan->parent->kode;
        $id_leader = $pelanggan->parent->id;
        $leader_terpilih = [$id_leader=>$nama_leader.'['.$kode_leader.']'];

        return view('mimin.orang.pelanggan.edit',
              compact('judul','breadcrumbs','judul_deskripsi','deskripsi','pelanggan','daftar_kategori','leader_terpilih')
        );

    }

    public function update(Request $req, $id){
        $pelanggan = Pelanggan::findOrFail($id);
        $rules = [
          'nama' =>'required',
          'email' =>'required|email|unique:users,email,'.$pelanggan->id,
          'nomor_hp' =>'required|unique:users,nomor_hp,'.$pelanggan->id,
          'kode' =>'required|unique:users,kode,'.$pelanggan->id,
          'kategori_id' =>'required',
          'password' => 'confirmed',
        //  'leader' => 'required',

        ];
        $messages =[
          'nama.required'=>'Nama Lengkap harus diisi',
          'email.required' => 'Email harus diisi',
          'nomor_hp.required' => 'Nomor Hp harus diisi',
          'kategori_id.required' =>'Kategori harus diisi',
          'kode.required' => 'id pelanggan harus diisi',
          'kode.unique' => 'id pelanggan harus unik'
        ];

        $input= $req->all();
        $validator = Validator::make($input, $rules, $messages)->validate();


        $leader = Pelanggan::find($req->leader);


        $level = 1;
        if ($leader){
           if($leader->id == $pelanggan->id){
             return redirect()->back()->with('error', $pelanggan->nama. ' tidak boleh dijadikan leader dirinya sendiri');
           }
           $level = $leader->level + 1;
        }

        $pelanggan->nama = $req->nama;

        $pesan_tambahan = "";
        // selain pelannggan tidak boleh diedit
        $user = User::findOrFail($pelanggan->id);
        if ($req->has('password') && $req->password != ""){
          $passw = bcrypt ($req->password);
          $pelanggan->password = $passw;
        }
        if ($user->hasRole("Pelanggan")){
          $pelanggan->email = $req->email;
          $pelanggan->nomor_hp = $req->nomor_hp;

        } else if ($user->roles->count()==0) {
          $user->syncRoles(['Pelanggan']);
          $pesan_tambahan = " dan berhasil jadi pelanggan";

        }else {
          $pesan_tambahan = " kecuali email dan Nomor HP nya karena mereka staf atau kurir";
        }

        $pelanggan->kategori_id = $req->kategori_id;
        $pelanggan->kode = $req->kode;
        if ($leader) {
        $pelanggan->parent_id = $leader->id;
        $pelanggan->distributor_id = $leader->distributor_id;
        } else {
          $pelanggan->distributor_id = $pelanggan->id;
        }
        $pelanggan->level = $level;
        $pelanggan->status = $req->status;
        $pelanggan->save();
        return redirect()->route('mimin.orang.pelanggan.index')->with('sukses', $pelanggan->nama. ' berhasil diubah'.$pesan_tambahan);
    }

    public function create (){
        $judul = "Tambah Pelanggan";
        $judul_deskripsi = "";
        $deskripsi = "";

        $breadcrumbs = [
          ['link'=>'#','name'=>"Pelanggan"],
          ['link'=>'#','name'=>"Tambah"],
        ];


        $daftar_kategori = KategoriPelanggan::where('status','Aktif')->pluck('nama','id');
        return view('mimin.orang.pelanggan.create',
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
     // 'leader'=>'required',
     // 'password' => 'required|confirmed',

      ];
      $messages =[

      'nama.required'=>'Nama Lengkap harus diisi',
      'email.required' => 'Email harus diisi',
      'nomor_hp.required' => 'Nomor Hp harus diisi',
      'kategori_id.required' =>'Kategori harus diisi',
      'kode.required' => 'Kode harus diisi'
      ];

      $input= $req->all();
      $validator = Validator::make($input, $rules, $messages)->validate();



  //    $passw = bcrypt ($req->password);
      $passw = bcrypt ("12345678");

   //   $pelanggan->password = $passw;

      $leader = Pelanggan::find($req->leader);
      $level = 1;
      if ($leader){
        $level = $leader->level + 1;
      }

      $user = Pelanggan::create([
          'nama'=>$req->nama,
          'email'=>$req->email,
          'nomor_hp'=>$req->nomor_hp,
          'password'=>$passw,
          'kategori_id'=>$req->kategori_id,
          'kode' => $req->kode,
          'level' =>$level
      ]);

      if($leader){
        $user->parent_id      =  $leader->id;
        $user->distributor_id =  $leader->distributor_id;
        $user->save();
      } else {
        $user->distributor_id =  $user->id;
        $user->save();
      }


      $user = User::findOrFail($user->id);

      $user->syncRoles(['Pelanggan']);

      return redirect()->route('mimin.orang.pelanggan.index')->with('sukses','Tambah Pelanggan Sukses');
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
            'redirect'=> route('mimin.orang.pelanggan.index')
            ]);
          } else {
            return response()->json([
            'pesan' => $nama.' Gagal Dihapus',
            'success' => false,
            'redirect'=> route('mimin.orang.pelanggan.index')
            ]);
          }

          } catch(\Exception $exception){
            return response()->json([
              'pesan' => 'Gagal Dihapus',
              'success' => false,
              'redirect'=> route('mimin.orang.pelanggan.index')
          ]);
          }

      }
/*
      public function getCabangId(){
        $akses_global = Auth::user()->can('akses-global');
        if ($akses_global){
            return null;
        } else {
            return Auth::user()->cabang_id;
        }
      }
      */


      public function cari_pelanggan(Request $req)
      {

        $key = $req->cari;
        if ($req->has("cari") && $req->cari != "") {

          $daftar_kegiatan = User::with('parent','kategori','distributor')->where(function ($query) use ($key) {
            $query->where('nama', 'like', '%' . $key . '%')
                  ->orWhere('email', $key)
                  ->orWhere('nomor_hp', $key);
          })->paginate(5);
        } else {
          $daftar_kegiatan = User::with('parent','kategori','distributor')->paginate(5);
        }
        $results = array(
          "results" => $daftar_kegiatan->toArray()['data'],
          "pagination" => array(
            "more" => $daftar_kegiatan->hasMorePages()
          )
        );

        return response()->json($results);
      }


}
