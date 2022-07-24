<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Scopes\CabangScope;
use Session;
use Auth;

class RakitProduk extends Model
{
  protected $guarded = [];
  protected $table = 'rakit_produk';
  protected $dates = [
      'created_at',
      'updated_at',
      'posted_at',
      'tanggal'

  ];


    public function daftar_detil(){
      return $this->hasMany('App\RakitProdukDetil','rakit_produk_id');
    }

    public function produk()
    {
        return $this->belongsTo('App\Produk','produk_id')->withDefault();
    }
    public function gudang()
    {
        return $this->belongsTo('App\Gudang','gudang_id')->withDefault();
    }
    public function dibuat_oleh()
    {
        return $this->belongsTo('App\User','dibuat_oleh_id')->withDefault();
    }
    public function diposting_oleh()
    {
        return $this->belongsTo('App\User','diposting_oleh_id')->withDefault();
    }


  public function cabang()
  {
      return $this->belongsTo('App\Cabang','cabang_id')->withDefault();
  }

  public function stok_detil()
  {
      return $this->morphMany('App\RiwayatStokProduk', 'sumber');
  }

  public function scopeDraft($query){
    return $query->where('status', 'Draft');
  }
  public function scopePosted($query){
    return $query->where('status', 'Posted');
  }
}
