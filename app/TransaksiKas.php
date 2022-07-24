<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TransaksiKas extends Model
{
  protected $table = 'transaksi_kas';
  protected $guarded = [];

  protected $dates = [
    'created_at',
    'updated_at',
    'tanggal'
];

  public function kas()
  {
      return $this->belongsTo('App\Kas','kas_id')->withDefault();
  }
}
