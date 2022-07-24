<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class BikinCabangTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('cabang', function (Blueprint $table) {
        $table->increments('id');
        $table->integer('kecamatan_id')->unsigned()->nullable();
        $table->foreign('kecamatan_id')->references('id')->on('kecamatan');
        $table->string('nama');
        $table->string('deskripsi')->nullable();
        $table->enum('status',['Aktif','Nonaktif'])->default("Aktif");
        $table->timestamps();
      });
      Schema::table('users', function (Blueprint $table) {
        $table->integer('cabang_id')->unsigned()->nullable();
        $table->foreign('cabang_id')->references('id')->on('cabang');
       });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

      Schema::table('users', function (Blueprint $table) {
        $table->dropForeign(['cabang_id']);
        $table->dropColumn('cabang_id');
       });
       Schema::dropIfExists('cabang');
    }
}
