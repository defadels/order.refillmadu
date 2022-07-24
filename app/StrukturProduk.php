<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StrukturProduk extends Model
{
  protected $guarded = [];
  protected $table = 'struktur_produk';
  protected $dates = [
      'created_at',
      'updated_at'
  ];

  public function produk()
  {
      return $this->belongsTo('App\Produk','produk_id')->withDefault();
  }
  public function bahan()
  {
      return $this->belongsTo('App\Produk','bahan_id')->withDefault();
  }
}
