<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Scopes\CabangScope;
use Session;
use Auth;

class KategoriProduk extends Model
{
  protected $guarded = [];
  protected $table = 'kategori_produk';
  protected $dates = [
      'created_at',
      'updated_at'
  ];



  public function cabang()
  {
      return $this->belongsTo('App\Cabang','cabang_id')->withDefault();
  }
}
