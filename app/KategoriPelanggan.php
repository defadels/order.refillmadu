<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class KategoriPelanggan extends Model
{
  protected $guarded = [];
  protected $table = 'kategori_pelanggan';
  protected $dates = [
      'created_at',
      'updated_at'
  ];

  public function daftar_pelanggan(){
    return $this->hasMany('App\User','kategori_id');
  }
  public function reseller()
  {
      return $this->belongsTo('App\Cabang','reseller_id')->withDefault();
  }

  public function daftar_harga_khusus(){
    return $this->hasMany('App\HargaKhusus','kategori_id');
  }
}
