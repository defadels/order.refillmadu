<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Session;
use App\Cabang;
use Auth;

class CabangScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $builder
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return void
     */
    public function apply(Builder $builder, Model $model)
    {
        $hak_akses_global = Auth::user()->can('akses-global');
        if($hak_akses_global){
          $cabang_id = Session::get('cabang_id',Cabang::first()->id);
        } else {
          $user = Auth::user();
          $cabang_id = 0;
          if(isset($user)){
            $cabang_id = $user->cabang_id;
          }
        }
        $builder->where('cabang_id', $cabang_id);
    }
}
