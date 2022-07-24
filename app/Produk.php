<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Scopes\CabangScope;
use Session;
use Auth;
use Illuminate\Support\Str;

class Produk extends Model
{
  protected $guarded = [];
  protected $table = 'produk';
  protected $dates = [
      'created_at',
      'updated_at',
      'tanggal_pembayaran_date'
  ];


  public function cabang()
  {
      return $this->belongsTo('App\Cabang','cabang_id')->withDefault();
  }

  public function kategori()
  {
      return $this->belongsTo('App\KategoriProduk','kategori_id')->withDefault();
  }

  public function daftar_harga_khusus(){
    return $this->hasMany('App\HargaKhusus','produk_id');
  }
  public function daftar_struktur(){
    return $this->hasMany('App\StrukturProduk','produk_id');
  }

  public function daftar_stok(){
    return $this->hasMany('App\StokProduk','produk_id');
  }

  public function stok_gudang($gudang_id){
    return $this->daftar_stok()->firstOrCreate(['gudang_id'=>$gudang_id],['saldo' => 0])->saldo;
  }

  public function update_stok_gudang($gudang_id, $qty, $keluar_masuk){
    if($keluar_masuk == "keluar"){
      $this->decrement('stok',$qty);
      return $this->daftar_stok()->where('gudang_id',$gudang_id)->decrement('saldo',$qty);
    } else if ($keluar_masuk == "masuk") {
      $this->increment('stok',$qty);
      return $this->daftar_stok()->where('gudang_id',$gudang_id)->increment('saldo',$qty);
    }

  }

}
