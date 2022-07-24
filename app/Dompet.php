<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Auth,Session;

class Dompet extends Model
{
  protected $guarded = [];
  protected $table = 'dompet';
  protected $dates = [
      'created_at',
      'updated_at',
      'tanggal'
  ];

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

  public function cabang()
  {
      return $this->belongsTo('App\Cabang','cabang_id')->withDefault();
  }

  public function user()
  {
      return $this->belongsTo('App\User','user_id')->withDefault();
  }
  public function transaksi_kas()
  {
      return $this->belongsTo('App\TransaksiKas','transaksi_kas_id')->withDefault();
  }
}
