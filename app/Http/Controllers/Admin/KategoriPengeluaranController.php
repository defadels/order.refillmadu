<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Validator;
use Str;
use App\KategoriPengeluaran;

class KategoriPengeluaranController extends Controller
{
  function __construct()
  {
       $this->middleware('permission:kategori.pengeluaran.lihat', ['only' => ['index','show']]);
       $this->middleware('permission:kategori.pengeluaran.tambah', ['only' => ['create','store']]);
       $this->middleware('permission:kategori.pengeluaran.edit', ['only' => ['edit','update']]);
       $this->middleware('permission:kategori.pengeluaran.hapus', ['only' => ['batalkan','destroy']]);
  }


  public function index()
  {

  $title = "Kategori Pengeluaran";
  $judul = "Kategori Pengeluaran";
  $judul_deskripsi = "";
  $deskripsi = "";

  $breadcrumbs = [
    ['link'=>route('mimin.pengeluaran.index'),'name'=>"Pengeluaran"],
    ['link'=>'#','name'=>"Kategori"],
  ];

  $daftar_kategori_pengeluaran = KategoriPengeluaran::paginate(10);

  return view('mimin.pengeluaran.kategori.index',
  compact('title','judul','judul_deskripsi','deskripsi','daftar_kategori_pengeluaran','breadcrumbs')
  );

  }

  public function create(){
      $title = "Tambah Kategori Pengeluaran";
      $judul = "Tambah Kategori Pengeluaran";
      $judul_deskripsi = "";
      $deskripsi = "";

      $breadcrumbs = [
        ['link'=>route('mimin.pengeluaran.index'),'name'=>"Pengeluaran"],
        ['link'=>route('mimin.pengeluaran.kategori.index'),'name'=>"Kategori"],
        ['link'=>'#','name'=>"Tambah"],
      ];

      return view('mimin.pengeluaran.kategori.create',
      compact('title','judul','judul_deskripsi','deskripsi','breadcrumbs')
      );

  }

  public function store (Request $req){

      $rules = [
          'nama' =>'required',

      ];

      $messages =[
          'nama.required'=>'Nama Lengkap harus diisi'
      ];

      $input= $req->all();
      $validator = Validator::make($input, $rules, $messages)->validate();

      $kategori_pengeluaran = KategoriPengeluaran::create([
          'nama'=>$req->nama,
          'deskripsi'=>$req->deskripsi,
          'publikasi'=>$req->publikasi,

      ]);
      return redirect()->route('mimin.pengeluaran.kategori.index')->with('sukses','Tambah Kategori Pengeluaran Sukses');
  }

  public function edit($id){
      $title = "Edit Kategori Pengeluaran";
      $judul = "Edit Kategori Pengeluaran";
      $judul_deskripsi = "";
      $deskripsi = "";
      $breadcrumbs = [
        ['link'=>route('mimin.pengeluaran.index'),'name'=>"Pengeluaran"],
        ['link'=>route('mimin.pengeluaran.kategori.index'),'name'=>"Kategori"],
        ['link'=>'#','name'=>"Edit"],
      ];

      $kategori_pengeluaran = KategoriPengeluaran::findOrFail($id);



      return view('mimin.pengeluaran.kategori.edit',
      compact('title','judul','judul_deskripsi','deskripsi' ,'kategori_pengeluaran','breadcrumbs')
      );

  }

  public function update (Request $req,$id){

      $kategori_pengeluaran = KategoriPengeluaran::findOrFail($id);

      $rules = [
          'nama' =>'required',
      ];

      $messages =[
          'nama.required'=>'Nama Lengkap harus diisi'
      ];

      $input= $req->all();
      $validator = Validator::make($input, $rules, $messages)->validate();

          if ($kategori_pengeluaran->id != 1){
          $kategori_pengeluaran->nama          = $req->nama;
          }
          $kategori_pengeluaran->deskripsi     = $req->deskripsi;
          $kategori_pengeluaran->publikasi             = $req->publikasi;
          $kategori_pengeluaran->save();

      return redirect()->route('mimin.pengeluaran.kategori.index')->with('sukses','Edit Kategori Pengeluaran Sukses');
  }
}
