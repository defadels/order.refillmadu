<?php

use Illuminate\Database\Seeder;

use Spatie\Permission\Models\Role;
use App\User;

use Spatie\Permission\Models\Permission;

class HakAksesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

      Role::create(['name' => 'Super Admin']);
      Role::create(['name' => 'Supervisor']);
      Role::create(['name' => 'Gudang']);
      Role::create(['name' => 'Staf']);
      Role::create(['name' => 'Kurir']);
      Role::create(['name' => 'Pelanggan']);



    }
}
