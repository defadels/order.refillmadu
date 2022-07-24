<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Point extends Model
{

    protected $guarded = [];
    protected $table = 'point';
    protected $dates = [
        'created_at',
        'updated_at',
        'tanggal'
    ];

    public function user()
    {
        return $this->belongsTo('App\User','user_id')->withDefault();
    }
    public function pesanan()
    {
        return $this->belongsTo('App\Pesanan','pesanan_id')->withDefault();
    }
}
