<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StokKeluarMasukDetil extends Model
{
  protected $guarded = [];
  protected $table = 'stok_keluar_masuk_detil';

  public $timestamps = false;

  public function produk()
  {
      return $this->belongsTo('App\Produk','produk_id')->withDefault();
  }
  public function stok_keluar_masuk()
  {
      return $this->belongsTo('App\StokKeluarMasuk','stok_keluar_masuk_id')->withDefault();
  }
    //
}
