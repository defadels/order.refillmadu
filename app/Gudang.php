<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Scopes\CabangScope;
use Session;
use Auth;

class Gudang extends Model
{
  protected $guarded = [];
  protected $table = 'gudang';
  protected $dates = [
      'created_at',
      'updated_at'
  ];



  public function cabang()
  {
      return $this->belongsTo('App\Cabang','cabang_id')->withDefault();
  }
}
