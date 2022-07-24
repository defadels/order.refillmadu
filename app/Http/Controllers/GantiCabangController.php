<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Cabang;
use Session;
class GantiCabangController extends Controller
{
  public function swap($cabang_id){
    // cabang yang ada
    $cabang = Cabang::where('status','Aktif')->where('id',$cabang_id)->firstOrFail();

    // check for existing language
    Session::put('cabang_id',$cabang->id);
    Session::put('cabang_nama',$cabang->nama);
    return redirect()->back();
}
}
