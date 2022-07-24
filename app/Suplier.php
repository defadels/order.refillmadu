<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Session;
use Auth;
use App\Scopes\CabangScope;

class Suplier extends Model
{

  protected $guarded = [];
  protected $table = 'suplier';
  protected $dates = [
      'created_at',
      'updated_at'
  ];


  public function cabang()
  {
      return $this->belongsTo('App\Cabang','cabang_id')->withDefault();
  }
}
