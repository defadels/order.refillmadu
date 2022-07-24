<?php

use Illuminate\Database\Seeder;

class UpdateLevelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

//       $daftar_user = DB::table('users')->orderBy('parent_id','asc')->whereNotNull('parent_id')->get();

//       foreach ($daftar_user as $user){

//           if ($user->parent_id == 10 || $user->parent_id == 11 || $user->parent_id == 12){
//               DB::table('users')->where('id',$user->id)->update(['level'=>2]);
//               echo "id => ".$user->id;
//               echo " diubah levelnya menjadi 2";
//               echo "\n";
//           }
//       }

//       //foreach ($daftar_user as $user){
// //
//   //            echo "id => ".$user->id;
//     ///          echo " , parent_id => ".$user->parent_id;
//        //       $parent = DB::table('users')->where('id',$user->parent_id)->first();
//          //     echo " , level_parent => ".$parent->level;
//            //   DB::table('users')->where('id',$user->id)->update(['level'=>$parent->level + 1]);
//              // echo ", sukses diubah levelnya menjadi ".($parent->level + 1);
//            //   echo "\n";
// //      }

    }
}
