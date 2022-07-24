<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TransferStokDetil extends Model
{
  protected $guarded = [];
  protected $table = 'transfer_stok_detil';

  public $timestamps = false;

  public function produk()
  {
      return $this->belongsTo('App\Produk','produk_id')->withDefault();
  }

  public function transfer_stok()
  {
      return $this->belongsTo('App\StokKeluarMasuk','transfer_stok_id')->withDefault();
  }
    //
}
