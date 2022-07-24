<?php

namespace App;

use Auth;
use Illuminate\Database\Eloquent\Model;
use Session;
use App\Scopes\CabangScope;

class Pelanggan extends Model
{
  protected $guarded = [];
  protected $table = 'users';
  protected $dates = [
      'created_at',
      'updated_at',
  ];


  public function kategori()
  {
      return $this->belongsTo('App\KategoriPelanggan','kategori_id')->withDefault();
  }
  public function cabang()
  {
      return $this->belongsTo('App\Cabang','cabang_id')->withDefault();
  }

  public function daftar_dompet(){
    return $this->hasMany('App\Dompet','user_id');
  }

  public function parent()
  {
      return $this->belongsTo('App\User','parent_id')->withDefault();
  }


  public function daftar_alamat()
  {
    return $this->hasMany('App\AlamatPelanggan', 'pelanggan_id');
  }


  public function distributor()
  {
      return $this->belongsTo('App\User','distributor_id')->withDefault();
  }




}
