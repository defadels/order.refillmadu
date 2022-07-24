<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cabang extends Model
{
  protected $guarded = [];
  protected $table = 'cabang';
  protected $dates = [
      'created_at',
      'updated_at'
  ];
}
