<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class RakitProdukDetil extends Model

{
  protected $guarded = [];
  protected $table = 'rakit_produk_detil';

  public $timestamps = false;

  public function bahan()
  {
      return $this->belongsTo('App\Produk','bahan_id')->withDefault();
  }
  public function rakit_produk()
  {
      return $this->belongsTo('App\RakitProduk','rakit_produk_id')->withDefault();
  }


}
