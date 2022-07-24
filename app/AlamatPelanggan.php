<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AlamatPelanggan extends Model
{
  protected $guarded = [];
  protected $table = 'alamat_pelanggan';
  protected $dates = [
      'created_at',
      'updated_at'
  ];
}
