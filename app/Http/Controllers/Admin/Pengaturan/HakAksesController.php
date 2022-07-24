<?php

namespace App\Http\Controllers\Admin\Pengaturan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use DB;
use App\User;

class HakAksesController extends Controller
{
  function __construct()
  {
       $this->middleware('permission:pengaturan.hakakses', ['except' => []]);
  }

  public function index(Request $request)
  {

    $judul="Hak Akses";
    $breadcrumbs = [
      ['link'=>'#','name'=>"Hak Akses"],
    ];

      $roles = Role::orderBy('id','DESC')->paginate(10);
      return view('mimin.pengaturan.hak_akses.index',compact('roles','judul','breadcrumbs'))
          ->with('i', ($request->input('page', 1) - 1) * 5);
  }

  public function edit($id)
  {
    $judul="Hak Akses";
    $breadcrumbs = [
      ['link'=>'#','name'=>"Hak Akses"],
      ['link'=>'#','name'=>"Edit"],
    ];


      $role = Role::find($id);
      $permission = Permission::orderBy('name')->get();
      $rolePermissions = DB::table("role_has_permissions")->where("role_has_permissions.role_id",$id)
          ->pluck('role_has_permissions.permission_id','role_has_permissions.permission_id')
          ->all();

      return view('mimin.pengaturan.hak_akses.edit',compact('role','permission','rolePermissions','judul','breadcrumbs'));

  }

  public function update(Request $request, $id)
  {
      $this->validate($request, [
          'name' => 'required',
          'permission' => 'required',
      ]);

      $role = Role::find($id);
      if ($role->name != "Pelanggan" && $role->name != "Kurir" && $role->name != "Super Admin"){
        $role->name = $request->input('name');
      }
      $role->save();

      if ($role->name != "Super Admin"){
      $role->syncPermissions($request->input('permission'));
      } else {
        $role->givePermissionTo(Permission::all());
      }

      return redirect()->route('mimin.pengaturan.hakakses.index')
                      ->with('sukses','Role updated successfully');
  }

    public function create()
    {
      $judul="Tambah Hak Akses";
      $breadcrumbs = [
        ['link'=>'#','name'=>"Hak Akses"],
        ['link'=>'#','name'=>"Tambah"],
      ];

        $permission = Permission::get();
        return view('mimin.pengaturan.hak_akses.create',compact('permission','judul','breadcrumbs'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|unique:roles,name',
            'permission' => 'required',
        ]);

        $role = Role::create(['name' => $request->input('name')]);
        $role->syncPermissions($request->input('permission'));

        return redirect()->route('mimin.pengaturan.hakakses.index')
                        ->with('sukses','Role created successfully');
    }


    public function show($id)
    {
      $judul="Hak Akses";
      $breadcrumbs = [
        ['link'=>'#','name'=>"Hak Akses"],
        ['link'=>'#','name'=>"Rincian"],
      ];
        $role = Role::find($id);
        $rolePermissions = Permission::join("role_has_permissions","role_has_permissions.permission_id","=","permissions.id")
            ->where("role_has_permissions.role_id",$id)
            ->get();

        return view('mimin.pengaturan.hak_akses.show',compact('role','rolePermissions','judul','breadcrumbs'));
    }

    public function destroy($id)
    {
      try {
        $role = Role::findOrFail($id);
      } catch(\Exception $exception){
              return response()->json([
                'judul' => 'Gagal Dihapus',
                'pesan' => 'Role tersebut Tidak ditemukan',
                'success' => false,
                'redirect'=> route('mimin.pengaturan.hakakses.index')
            ]);
      }
        $masih_ada_user = User::role($role->name)->exists();

        if ($masih_ada_user){
          return response()->json([
            'judul' => 'Gagal Dihapus',
            'pesan' => $role->name.' masih memiliki pengguna yang terkait dengannya',
            'success' => false,
            'redirect'=> route('mimin.pengaturan.hakakses.index')
        ]);
        }
        $nama = $role->name;

        if ($role->name != "Pelanggan" && $role->name != "Kurir" && $role->name != "Super Admin"){

        $sukses = $role->delete();
        } else {
          $sukses = false;
        }

        if($sukses){
          return response()->json([
            'judul'=> 'Sukses Dihapus',
            'pesan' => $nama.' Berhasil Dihapus',
            'success' => true,
            'redirect'=> route('mimin.pengaturan.hakakses.index')
        ]);
        }else{
          return response()->json([
            'judul' => 'Gagal Dihapus',
            'pesan' => $nama.' Gagal Dihapus',
            'success' => false,
            'redirect'=> route('mimin.pengaturan.hakakses.index')
        ]);
        }
    }

}
