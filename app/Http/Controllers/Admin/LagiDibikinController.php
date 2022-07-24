<?php

namespace App\Http\Controllers\Admin;

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


  return view('mimin.lagidibikin',
  compact('title','judul','judul_deskripsi','deskripsi','breadcrumbs')
  );

  }
}
