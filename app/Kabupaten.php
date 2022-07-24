<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Kabupaten extends Model
{
  protected $guarded = [];
  protected $table = 'kabupaten';
  protected $dates = [
      'created_at',
      'updated_at'
  ];

  public function provinsi()
  {
      return $this->belongsTo('App\Provinsi','provinsi_id')->withDefault();
  }
  public function daftar_kecamatan(){
    return $this->hasMany('App\Kecamatan','kabupaten_id');
  }
}
