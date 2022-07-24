<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Scopes\CabangScope;
use Auth,Session;
class Pengeluaran extends Model
{
  protected $guarded = [];

  protected $table = 'pengeluaran';

  protected $dates = [
    'created_at',
    'updated_at',
    'tanggal'
];


public function cabang()
{
  return $this->belongsTo('App\Cabang','cabang_id')->withDefault();
}

public function kategori()
{
    return $this->belongsTo('App\KategoriPengeluaran','kategori_pengeluaran_id')->withDefault();
}
public function transaksi_kas()
{
    return $this->belongsTo('App\TransaksiKas','transaksi_kas_id')->withDefault();
}


public function diinput_oleh()
{
    return $this->belongsTo('App\User','diinput_oleh_id')->withDefault();
}

public function daftar_pengiriman (){
    return $this->belongsToMany('App\MetodePengiriman', 'pengeluaran_pengiriman', 'pengeluaran_id', 'metode_pengiriman_id');
}




}
