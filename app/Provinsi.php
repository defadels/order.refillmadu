<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Provinsi extends Model
{

  protected $guarded = [];
  protected $table = 'provinsi';
  protected $dates = [
      'created_at',
      'updated_at'
  ];

  public function daftar_kabupaten(){
    return $this->hasMany('App\Kabupaten','provinsi_id');
  }

}
