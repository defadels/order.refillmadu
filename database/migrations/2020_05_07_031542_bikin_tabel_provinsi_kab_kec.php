<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class BikinTabelProvinsiKabKec extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('provinsi', function (Blueprint $table) {
          $table->increments('id');
          $table->string('nama');
          $table->string('kode',15)->nullable();
          $table->enum('status',['Aktif','Nonaktif'])->default("Aktif");
          $table->timestamps();
      });

      Schema::create('kabupaten', function (Blueprint $table) {
        $table->increments('id');
        $table->integer('provinsi_id')->unsigned()->nullable();
        $table->foreign('provinsi_id')->references('id')->on('provinsi');
        $table->string('nama');
        $table->string('kode',15)->nullable();

        $table->enum('status',['Aktif','Nonaktif'])->default("Aktif");

        $table->timestamps();
      });

      Schema::create('kecamatan', function (Blueprint $table) {
        $table->increments('id');
        $table->integer('kabupaten_id')->unsigned()->nullable();
        $table->foreign('kabupaten_id')->references('id')->on('kabupaten');
        $table->string('nama');
        $table->string('kode',15)->nullable();

        $table->enum('status',['Aktif','Nonaktif'])->default("Aktif");

        $table->timestamps();
      });
      Schema::create('desa', function (Blueprint $table) {
        $table->increments('id');
        $table->integer('kecamatan_id')->unsigned()->nullable();
        $table->foreign('kecamatan_id')->references('id')->on('kecamatan');
        $table->string('nama');
        $table->string('kode',15)->nullable();

        $table->enum('status',['Aktif','Nonaktif'])->default("Aktif");

        $table->timestamps();
      });

      Schema::table('users', function (Blueprint $table) {

        $table->integer('provinsi_id')->unsigned()->nullable();
        $table->foreign('provinsi_id')->references('id')->on('provinsi');

        $table->integer('kabupaten_id')->unsigned()->nullable();
        $table->foreign('kabupaten_id')->references('id')->on('kabupaten');

        $table->integer('kecamatan_id')->unsigned()->nullable();
        $table->foreign('kecamatan_id')->references('id')->on('kecamatan');

        $table->integer('desa_id')->unsigned()->nullable();
        $table->foreign('desa_id')->references('id')->on('desa');
       });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

       Schema::dropIfExists('desa');
       Schema::dropIfExists('kecamatan');
       Schema::dropIfExists('kabupaten');
       Schema::dropIfExists('provinsi');
    }
}
