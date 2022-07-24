<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Scopes\CabangScope;
use Auth, Session;


class Pesanan extends Model
{
  protected $guarded = [];
  protected $table = 'pesanan';
  protected $dates = [

    'created_at',
    'updated_at',
    'dimasukkan_pada',
    'diproses_pada',
    'dikirim_pada',
    'diantar_pada',
    'diselesaikan_pada',
    'dibatalkan_pada',
    'tanggal_pengiriman',
    'tanggal_pembayaran',

  ];



  public function daftar_detil()
  {
    return $this->hasMany('App\PesananDetil', 'pesanan_id');
  }
  public function daftar_upline()
  {
    return $this->hasMany('App\PesananUpline', 'pesanan_id');
  }

  public function nominal_pembelian_pelanggan($pelanggan_id){
    return $this->daftar_upline()->where('pelanggan_id',$pelanggan_id)->first()->nominal_pembelian;
  }


  public function cabang()
  {
    return $this->belongsTo('App\Cabang', 'cabang_id')->withDefault();
  }

  public function stok_detil()
  {
    return $this->morphMany('App\RiwayatStokProduk', 'sumber');
  }

  public function scopeMasuk($query)
  {
    return $query->where('status', 'masuk');
  }

  public function scopePreorder($query)
  {
    return $query->where('status', 'preorder');
  }
  public function scopeDiproses($query)
  {
    return $query->where('status', 'diproses');
  }
  public function scopeDiantar($query)
  {
    return $query->where('status', 'diantar');
  }
  public function scopeDikirim($query)
  {
    return $query->where('status', 'dikirim');
  }
  public function scopeSelesai($query)
  {
    return $query->where('status', 'selesai');
  }
  public function scopeDibatalkan($query)
  {
    return $query->where('status', 'dibatalkan');
  }

  public function gudang()
  {
    return $this->belongsTo('App\Gudang', 'gudang_id')->withDefault();
  }

  public function metode_pengiriman()
  {
    return $this->belongsTo('App\MetodePengiriman', 'metode_pengiriman_id')->withDefault();
  }

  public function metode_pembayaran()
  {
    return $this->belongsTo('App\MetodePembayaran', 'metode_pembayaran_id')->withDefault();
  }

  public function pelanggan()
  {
    return $this->belongsTo('App\User', 'pelanggan_id')->withDefault();
  }


  public function dibuat_oleh()
  {
    return $this->belongsTo('App\User', 'dibuat_oleh_id')->withDefault();
  }

  public function diproses_oleh()
  {
    return $this->belongsTo('App\User', 'diproses_oleh_id')->withDefault();
  }

  public function pengiriman_diinput_oleh()
  {
    return $this->belongsTo('App\User', 'dikirim_oleh_id')->withDefault();
  }

  public function diantar_oleh()
  {
    return $this->belongsTo('App\User', 'diantar_oleh_id')->withDefault();
  }

  public function diselesaikan_oleh()
  {
    return $this->belongsTo('App\User', 'diselesaikan_oleh_id')->withDefault();
  }

  public function dibatalkan_oleh()
  {
    return $this->belongsTo('App\User', 'dibatalkan_oleh_id')->withDefault();
  }

  public function getGrandTotalAttribute(){
    return $this->nominal_pembelian + $this->ongkos_kirim + $this->biaya_tambahan + $this->biaya_packing - $this->diskon;
  }



}
