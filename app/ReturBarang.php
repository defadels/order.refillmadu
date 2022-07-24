<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\Scopes\CabangScope;
use Auth,Session;

class ReturBarang extends Model
{
  protected $guarded = [];
  protected $table = 'retur_barang';
  protected $dates = [
    'created_at',
    'updated_at',
    'posted_at',
    'tanggal'

  ];




  public function daftar_detil()
  {
    return $this->hasMany('App\ReturBarangDetil', 'retur_barang_id');
  }

  public function gudang()
  {
    return $this->belongsTo('App\Gudang', 'gudang_id')->withDefault();
  }
  public function pelanggan()
  {
    return $this->belongsTo('App\User', 'pelanggan_id')->withDefault();
  }

  public function kas_asal()
  {
    return $this->belongsTo('App\Kas', 'kas_asal_id')->withDefault();
  }

  public function transaksi_kas()
  {
    return $this->belongsTo('App\TransaksiKas', 'transaksi_kas_id')->withDefault();
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
