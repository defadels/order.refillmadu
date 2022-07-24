<?php

namespace App\Http\Controllers\Admin\Laporan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ArusKasController extends Controller
{
  function __construct()
  {
       $this->middleware('permission:laporan.aruskas', ['except' => []]);
  }

}
