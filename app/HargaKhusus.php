<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HargaKhusus extends Model
{
  protected $guarded = [];
  protected $table = 'harga_khusus';
  protected $dates = [
      'created_at',
      'updated_at'
  ];

  public function produk()
  {
      return $this->belongsTo('App\Produk','produk_id')->withDefault();
  }
  public function kategori()
  {
      return $this->belongsTo('App\KategoriPelanggan','kategori_id')->withDefault();
  }
}
