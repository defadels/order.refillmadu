<?php

namespace App;

use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;
use Auth,Session;

use Illuminate\Database\Eloquent\Builder;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable,HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nama', 'email', 'password','nomor_hp','cabang_id','kategori_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function cabang()
    {
        return $this->belongsTo('App\Cabang','cabang_id')->withDefault();
    }
    public function kategori()
    {
        return $this->belongsTo('App\KategoriPelanggan','kategori_id')->withDefault();
    }

    public function scopeNotRole(Builder $query, $roles, $guard = null): Builder
    {
         if ($roles instanceof Collection) {
             $roles = $roles->all();
         }

         if (! is_array($roles)) {
             $roles = [$roles];
         }

         $roles = array_map(function ($role) use ($guard) {
             if ($role instanceof Role) {
                 return $role;
             }

             $method = is_numeric($role) ? 'findById' : 'findByName';
             $guard = $guard ?: $this->getDefaultGuardName();

             return $this->getRoleClass()->{$method}($role, $guard);
         }, $roles);

         return $query->whereHas('roles', function ($query) use ($roles) {
             $query->where(function ($query) use ($roles) {
                 foreach ($roles as $role) {
                     $query->where(config('permission.table_names.roles').'.id', '!=' , $role->id);
                 }
             });
         });
    }

    public function saldo_tanggal ($tanggal){
      $saldo_terakhir = $this->daftar_dompet()
                      ->whereDate('tanggal','<=',$tanggal->copy()->endOfDay())
                      ->orderBy('tanggal','desc')
                      ->orderBy('created_at','desc')
                      ->sharedLock()
                      ->first();
      $this->sharedLock();
      if ($saldo_terakhir){
        return $saldo_terakhir->saldo_berjalan;
      } else {
        return 0;
      }
    }
    public function daftar_dompet(){
      return $this->hasMany('App\Dompet','user_id');
    }

    public function daftar_point(){
      return $this->hasMany('App\Point','user_id');
      // untuk me-list rincian point dengan user tertentu
    }

    public function update_point($tanggal,$nominal,$d_k){
      // untuk update point di tabel user => komulatif
      if($d_k == "d"){
          $this->increment('point',$nominal);
      } else {
          $this->decrement('point',$nominal);
      }
      return "sukses";
    }

    public function update_saldo($tanggal,$nominal,$d_k){
      if($d_k == "d"){
      $this->daftar_dompet()->whereDate('tanggal','>',$tanggal->copy()->endOfDay())
      //     ->orderBy('tanggal')
     //      ->orderBy('created_at')
           ->increment('saldo_berjalan', $nominal);
           $this->increment('saldo',$nominal);
      } else {
        $this->daftar_dompet()->whereDate('tanggal','>',$tanggal->copy()->endOfDay())
      //     ->orderBy('tanggal')
       //    ->orderBy('created_at')
           ->decrement('saldo_berjalan', $nominal);
           $this->decrement('saldo',$nominal);
      }
      return "sukses";
    }

    public function scopeCabangku($query)
    {
          $hak_akses_global = true;
          if($hak_akses_global){
            $cabang_id = Session::get('cabang_id',1);
          } else {
            $user = Auth::user();
            $cabang_id = 0;
            if(isset($user)){
              $cabang_id = $user->cabang_id;
            }
          }
          return $query->where('cabang_id', $cabang_id);
    }

    public function daftar_harga(){
      // harga umum
      $daftar_harga = Produk::pluck('harga_jual','id');


      // harga kategori
      $daftar_harga_kategori = $this->kategori->daftar_harga_khusus()->pluck('harga_jual','produk_id');
      $daftar_harga =$daftar_harga->replace($daftar_harga_kategori);

      // harga peribadi
      return $daftar_harga;
    }

    public function parent()
    {
        return $this->belongsTo('App\User','parent_id')->withDefault();
    }

    public function distributor()
    {
        return $this->belongsTo('App\User','distributor_id')->withDefault();
    }


}
