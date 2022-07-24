<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ReturBarangDetil extends Model
{
  protected $guarded = [];
  protected $table = 'retur_barang_detil';

  public $timestamps = false;

  public function produk()
  {
      return $this->belongsTo('App\Produk','produk_id')->withDefault();
  }
  public function retur_barang()
  {
      return $this->belongsTo('App\ReturBarang','retur_barang_id')->withDefault();
  }
}
