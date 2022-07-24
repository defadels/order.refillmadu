<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Scopes\CabangScope;
use Auth,Session;

class TransferKas extends Model
{
  protected $table = 'transfer_kas';
  protected $guarded = [];



  public function cabang()
  {
    return $this->belongsTo('App\Cabang','cabang_id')->withDefault();
  }

  public function debet()
  {
      return $this->belongsTo('App\TransaksiKas', 'trx_debet_id');
  }

  public function kredit()
  {
      return $this->belongsTo('App\TransaksiKas', 'trx_kredit_id');
  }
}
