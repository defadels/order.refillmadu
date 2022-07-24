<?php

namespace App\Http\Controllers\Admin\Laporan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use DB,Session;
use App\Pelanggan;
use App\Dompet;
use App\MetodePengiriman;

class HutangController extends Controller
{
  function __construct()
  {
       $this->middleware('permission:laporan.hutang', ['except' => []]);
  }

  public function index()
  {
    $judul = "Laporan Hutang";
    $breadcrumbs = [
      ['link' => '#', 'name' => "Laporan"],
      ['link' => '#', 'name' => "Hutang"],
    ];
    return view(
      'mimin.laporan.hutang.index',
      compact('judul', 'breadcrumbs')
    );
  }

  public function pengiriman()
  {
    $judul = "Hutang Pengiriman";
    $breadcrumbs = [
      ['link' => '#', 'name' => "Laporan"],
      ['link' => '#', 'name' => "Hutang"],
      ['link' => '#', 'name' => "Pengiriman"],
    ];
    $daftar_pengiriman = MetodePengiriman::paginate(10);
    return view(
      'mimin.laporan.hutang.pengiriman',
      compact('judul', 'breadcrumbs','daftar_pengiriman')
    );
  }
  public function dompet(Request $req)
  {

    $judul = "Hutang Pelanggan";
    $breadcrumbs = [
      ['link' => '#', 'name' => "Laporan"],
      ['link' => '#', 'name' => "Hutang"],
      ['link' => '#', 'name' => "Dompet"],
    ];

    $start_date = Carbon::now()->startOfMonth();
    $end_date = $start_date->copy()->endOfMonth();

    if ($req->has('range')){
      $range = $req->range;
      $tanggal = explode(" - ", $range);

      try
      {
         $start_date = Carbon::createFromFormat('d/m/Y', $tanggal[0]);
         $end_date = Carbon::createFromFormat('d/m/Y', $tanggal[1]);
      }
      catch (\Throwable $t)
      {

          $start_date = Carbon::now()->startOfMonth();
          $end_date = $start_date->copy()->endOfMonth();

      }
    }


    $dompet_terakhir = Dompet::
                    select(DB::raw('max(tanggal) as tanggalx,user_id as user_idx'))
                    ->whereDate('tanggal','<=',$end_date->format('Y-m-d'))
                //    ->orderBy('id','desc')
                    ->groupBy('user_id');

    $daftar_dompet = Dompet::joinSub($dompet_terakhir, 'a', function ($join) {
                          $join->on('dompet.tanggal', '=', 'a.tanggalx')
                               ->on('dompet.user_id','=','a.user_idx');
                      })
                     ->join('users','users.id','=','dompet.user_id')
                     ->where(function ($query) {
                          $query->where('dompet.cabang_id',$this->getCabang())
                               ->orWhere('users.cabang_id',$this->getCabang());
                      })
                     ->where('dompet.saldo_berjalan','>','0')
                     ->paginate(10);

    return view('mimin.laporan.hutang.dompet',
                    compact('judul','breadcrumbs','daftar_dompet','start_date','end_date')
                );
  }

  public function getCabang(){
          $hak_akses_global = true;
          if($hak_akses_global){
            $cabang_id = Session::get('cabang_id',1);
          } else {
            $user = Auth::user();
            $cabang_id = 0;
            if(isset($user)){
              $cabang_id = $user->cabang_id;
            }
          }
          return $cabang_id;
  }

}
