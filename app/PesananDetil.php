<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PesananDetil extends Model
{
  protected $guarded = [];
  protected $table = 'pesanan_detil';

  public $timestamps = false;

  public function produk()
  {
      return $this->belongsTo('App\Produk','produk_id')->withDefault();
  }

  public function pesanan()
  {
      return $this->belongsTo('App\Pesanan','pesanan_id')->withDefault();
  }

  public function getSubTotalPelangganAttribute(){
    return $this->harga * $this->kuantitas;
  }




  public function getSubTotalAttribute(){

      return $this->sub_total_pelanggan;

  }
}
