<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RiwayatStokProduk extends Model
{
  protected $guarded = [];
  protected $table = 'riwayat_stok_produk';
  protected $dates = [
      'created_at',
      'updated_at'
  ];

  public function sumber()
  {
      return $this->morphTo();
  }
}
