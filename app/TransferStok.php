<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Scopes\CabangScope;
use Auth, Session;

class TransferStok extends Model
{
  protected $guarded = [];
  protected $table = 'transfer_stok';
  protected $dates = [
    'created_at',
    'updated_at',
    'posted_at',
    'tanggal',
  ];


  public function daftar_detil()
  {
    return $this->hasMany('App\TransferStokDetil', 'transfer_stok_id');
  }

  public function gudang_tujuan()
  {
    return $this->belongsTo('App\Gudang', 'gudang_tujuan_id')->withDefault();
  }
  public function gudang_asal()
  {
    return $this->belongsTo('App\Gudang', 'gudang_asal_id')->withDefault();
  }
  public function dibuat_oleh()
  {
    return $this->belongsTo('App\User', 'dibuat_oleh_id')->withDefault();
  }
  public function diposting_oleh()
  {
    return $this->belongsTo('App\User', 'diposting_oleh_id')->withDefault();
  }


  public function cabang()
  {
    return $this->belongsTo('App\Cabang', 'cabang_id')->withDefault();
  }

  public function stok_detil()
  {
    return $this->morphMany('App\RiwayatStokProduk', 'sumber');
  }

  public function scopeDraft($query)
  {
    return $query->where('status', 'Draft');
  }
  public function scopePosted($query)
  {
    return $query->where('status', 'Posted');
  }
}
