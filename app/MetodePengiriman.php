<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Scopes\CabangScope;
use Session;
use Auth;

class MetodePengiriman extends Model
{
  protected $guarded = [];
  protected $table = 'metode_pengiriman';
  protected $dates = [
      'created_at',
      'updated_at'
  ];

  public function cabang()
  {
      return $this->belongsTo('App\Cabang','cabang_id')->withDefault();
  }
}
