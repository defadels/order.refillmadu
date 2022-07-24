<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class BikinTabelKategoriPelanggan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('kategori_pelanggan', function (Blueprint $table) {
        $table->increments('id');
        $table->bigInteger('reseller_id')->unsigned()->nullable();
        $table->foreign('reseller_id')->references('id')->on('users');
        $table->string('nama');
        $table->string('keterangan')->nullable();
        $table->enum('status',['Aktif','Nonaktif'])->default("Aktif");
        $table->timestamps();
      });

      Schema::table('users', function (Blueprint $table) {
        $table->integer('kategori_id')->unsigned()->nullable();
        $table->foreign('kategori_id')->references('id')->on('kategori_pelanggan');
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
        $table->dropForeign(['kategori_id']);
        $table->dropColumn('kategori_id');
       });
       Schema::dropIfExists('kategori_pelanggan');
    }
}
