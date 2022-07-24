<?php

namespace App\Http\Controllers\Admin\Pengaturan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use Spatie\Permission\Models\Role;
use Validator;
use Hash;
use Auth;
use Session;
use App\Cabang;
use Str;

class PenggunaController extends Controller
{
  function __construct()
  {
       $this->middleware('permission:pengaturan.pengguna', ['except' => []]);
  }

  public function getCabangId(){
              $akses_global = Auth::user()->can('akses-global');
              if ($akses_global){
                  return Session::get('cabang_id',Cabang::first()->id);
              } else {
                  return Auth::user()->cabang_id;
              }
  }

  public function index()
    {

        $judul = "Daftar Pengguna";
        $breadcrumbs = [
          ['link'=>'#','name'=>"Pengguna"],
        ];
        $daftar_admin =
        User::notRole(['Pelanggan','Kurir'])->with('roles')->paginate(10);


        return view('mimin.pengaturan.pengguna.index',
          compact('judul','breadcrumbs','daftar_admin')
        );
    }
    public function edit ($id){
        $title = "Edit Pengguna";
        $judul = "Edit Pengguna";
        $judul_deskripsi = "";
        $deskripsi = "";
        $breadcrumbs = [
          ['link'=>'#','name'=>"Pengguna"],
          ['link'=>'#','name'=>"Edit"],
        ];

        $admin = User::notRole(['Pelanggan','Kurir'])->find($id);

        $daftar_hak_akses = Role::whereNotIn('name',['pelanggan','kurir'])->pluck('name','name');

        $daftar_cabang = Cabang::pluck('nama','id');
        return view('mimin.pengaturan.pengguna.edit',
        compact('title','judul','breadcrumbs','judul_deskripsi','deskripsi','admin','daftar_hak_akses','daftar_cabang')
        );
    }

    public function update(Request $req, $id){
        $admin = User::notRole(['Pelanggan','Kurir'])->findOrFail($id);
        $rules = [
            'nama' =>'required',
            'email' =>'required|email|unique:users,email,'.$admin->id,
            'nomor_hp' =>'required|unique:users,nomor_hp,'.$admin->id,
            'hak_akses_id' =>'required',
            'cabang_id'=>'required',
            'password' => 'sometimes|confirmed'

        ];
        $messages =[

            'nama.required'=>'Nama Lengkap harus diisi',
            'email.required' => 'Email harus diisi',
            'nomor_hp.required' => 'Nomor Hp harus diisi',
            'hak_akses_id.required' =>'Hak Akses harus diisi'
        ];

        $input= $req->all();
        $validator = Validator::make($input, $rules, $messages)->validate();


        $admin->nama = $req->nama;
        $admin->email = $req->email;
        $admin->nomor_hp = $req->nomor_hp;
        if($admin->id != 1){ // kalau bukan super admin
        if ($req->has('password') && $req->password != ""){
          $passw = bcrypt ($req->password);
          $admin->password = $passw;
        }
      }
        $admin->cabang_id = $req->cabang_id;
        if ($admin->id != 1){
        $admin->syncRoles([$req->hak_akses_id]);
        }
        $admin->save();

        return redirect()->route('mimin.pengaturan.pengguna.index')->with('sukses', $admin->nama. 'Berhasil diubah');
    }

    public function create (){
        $title = "Tambah Pengguna";
        $judul = "Tambah Pengguna";
        $judul_deskripsi = "";
        $deskripsi = "";
        $breadcrumbs = [
          ['link'=>'#','name'=>"Pengguna"],
          ['link'=>'#','name'=>"Tambah"],
        ];


        $daftar_cabang = Cabang::pluck('nama','id');
        $daftar_hak_akses = Role::whereNotIn('name',['pelanggan','kurir'])->pluck('name','name');
        return view('mimin.pengaturan.pengguna.create',
        compact('title','judul','judul_deskripsi','breadcrumbs','deskripsi','daftar_hak_akses','daftar_cabang')
        );
    }

    public function store (Request $req){

      $rules = [
        'nama' =>'required',
        'email' =>'required|email|unique:users,email',
        'nomor_hp' =>'required|unique:users,nomor_hp',
        'hak_akses_id' =>'required',
        'cabang_id' =>'required',

    //  'password' => 'required|confirmed'

    ];
    $messages =[

        'nama.required'=>'Nama Lengkap harus diisi',
        'email.required' => 'Email harus diisi',
        'nomor_hp.required' => 'Nomor Hp harus diisi',
        'hak_akses_id.required' =>'Hak Akses harus diisi'

    ];

    $input= $req->all();
    $validator = Validator::make($input, $rules, $messages)->validate();


    $password = bcrypt (Str::random(10));
        $user = User::create([
          'nama'=>$req->nama,
          'email'=>$req->email,
          'nomor_hp'=>$req->nomor_hp,
          'password'=>$password,
          'cabang_id'=>$req->cabang_id
        ]);
        $user->syncRoles([$req->hak_akses_id]);

        return redirect()->route('mimin.pengaturan.pengguna.index')->with('sukses','Tambah Pengguna Sukses');
    }

    public function delete ($id){
        $title = "Hapus Pengguna";
        $breadcrumbs = [
          ['link'=>'#','name'=>"Pengguna"],
          ['link'=>'#','name'=>"Hapus"],
        ];

        $judul = "Hapus Pengguna";
        $judul_deskripsi = "";
        $deskripsi = "";
        $admin = User::notRole(['Pelanggan','Kurir'])->find($id);

        return view('mimin.pengaturan.pengguna.delete',compact('title','breadcrumbs','judul','judul_deskripsi','deskripsi','admin'));
    }

  public function destroy (Request $req, $id){

    try {
      $user=User::notRole(['Pelanggan','Kurir'])->where('id',$id)->firstOrFail();

      $nama = $user->nama;

      if ($user->id != 1){
      $result = $user->delete();
      }else {
        $result = false;
      }
      if ($result){
        return response()->json([
            'pesan' => $nama.' Sukses Dihapus',
            'success' => true,
            'redirect'=> route('mimin.pengaturan.pengguna.index')
            ]);
      } else {
            return response()->json([
            'pesan' => $nama.' Gagal Dihapus',
            'success' => false,
            'redirect'=> route('mimin.pengaturan.pengguna.index')
            ]);
      }

    } catch(\Exception $exception){
            return response()->json([
              'pesan' => 'Gagal Dihapus',
              'success' => false,
              'redirect'=> route('mimin.pengaturan.pengguna.index')
          ]);
    }

  }

}
