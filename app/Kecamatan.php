<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Kecamatan extends Model
{
  protected $guarded = [];
  protected $table = 'kecamatan';
  protected $dates = [
      'created_at',
      'updated_at'
  ];

  public function kabupaten()
  {
      return $this->belongsTo('App\Kabupaten','kabupaten_id')->withDefault();
  }

}
