<?php

namespace App\Http\Controllers\Pelanggan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LagiDibikinController extends Controller
{
  public function index()
  {

  $title = "Dashboard";
  $judul = "Dashboard";
  $judul_deskripsi = "";
  $deskripsi = "";

  $breadcrumbs = [
    ['link'=>'#','name'=>"Dashboard"],
  ];


  return view('pelanggan.lagidibikin',
      compact('title','judul','judul_deskripsi','deskripsi','breadcrumbs')
  );

  }
}
