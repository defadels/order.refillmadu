<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth,Session;
class Kas extends Model
{
  protected $table = 'kas';
  protected $guarded = [];


  public function daftar_transaksi(){
    return $this->hasMany('App\TransaksiKas','kas_id');
  }

  public function daftar_saldo_bulanan(){
    return $this->hasMany('App\SaldoKasBulanan','kas_id');
  }

  public function saldo_tanggal ($tanggal){
    $saldo_terakhir = $this->daftar_saldo_bulanan()
                    ->whereDate('bulan','<=',$tanggal->copy()->endOfMonth())->orderBy('bulan','desc')
                    ->sharedLock()
                    ->first();
    $this->sharedLock();

    if ($saldo_terakhir){
      return $saldo_terakhir->saldo_bulan;
    } else {
      return $this->saldo_awal;
    }

  }

  public function update_saldo($tanggal,$nominal,$d_k){

    $bulan = $tanggal->format('m');
    $tahun = $tanggal->format('Y');

    // ubah atau buat saldo di bulan ini
    $this->daftar_saldo_bulanan()
      ->whereMonth('bulan',$bulan)
      ->whereYear('bulan',$tahun)
      ->updateOrInsert(['kas_id'=>$this->id],['bulan'=>$tanggal,'saldo_bulan'=>$this->saldo_tanggal($tanggal)]);

    // udapte saldo bulan ini sampai di masa mendatang
    // tambahin atau kurangin;
    if($d_k == "d"){
    $this->daftar_saldo_bulanan()->whereDate('bulan','>=',$tanggal->copy()->startOfMonth())
         ->increment('saldo_bulan', $nominal);
         $this->increment('saldo',$nominal);
    } else {
      $this->daftar_saldo_bulanan()->whereDate('bulan','>=',$tanggal->copy()->startOfMonth())
         ->decrement('saldo_bulan', $nominal);
         $this->decrement('saldo',$nominal);
    }
    return "sukses";
  }

  public function scopeCabangku($query)
  {
        $user = Auth::user();
        $cabang_id = 0;
        if(isset($user)){
              $cabang_id = $user->cabang_id;
        }
        if ($cabang_id){
              return $query->where('cabang_id', $cabang_id);
        } else {
              return $query;
        }
  }

}
