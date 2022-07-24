<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\User;
class IsiPasswordSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       $daftar_user =  User::whereNull('password')->get();

        foreach ($daftar_user as $user){
            $password = strtoupper(Str::random(5));
            $hash = bcrypt($password);

            $user->password_default = $password;
            $user->password = $hash;
            $user->save();

            echo ("password was generated: ".$user->nama."\n");
        }

    }
}
