<?php

use Illuminate\Database\Seeder;


use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      $pass = bcrypt("rahasia");

        $super_admin = User::create([
          'nama' => 'Super Admin',
          'email'=>'super@admin.com',
          'nomor_hp'=>'085260061002',
          'password'=>$pass
        ]);
        $supervisor = User::create([
          'nama' => 'Supervisor',
          'email'=>'supervisor@supervisor.com',
          'nomor_hp'=>'085760061002',
          'password'=>$pass
        ]);
        $staf = User::create([
          'nama' => 'Staf',
          'email'=>'staf@staf.com',
          'nomor_hp'=>'085360061002',
          'password'=>$pass
        ]);


        $kurir1 = User::create([
          'nama' => 'Kurir me',
          'email'=>'kurir@medan.com',
          'nomor_hp'=>'085860061234',
          'password'=>$pass
        ]);

        $kurir2 = User::create([
          'nama' => 'Kurir 2',
          'email'=>'kurir@chibi.com',
          'nomor_hp'=>'085860063232',
          'password'=>$pass
        ]);


        $super_admin->syncRoles(["Super Admin"]);

        $supervisor->syncRoles(["Supervisor"]);

        $staf->syncRoles(["Staf"]);
        $kurir1->syncRoles(["Kurir"]);
        $kurir2->syncRoles(["Kurir"]);
    }
}
