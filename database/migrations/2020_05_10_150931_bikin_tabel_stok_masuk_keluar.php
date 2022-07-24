<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class BikinTabelStokMasukKeluar extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('stok_keluar_masuk', function (Blueprint $table) {
        $table->bigIncrements('id');
        $table->enum('keluar_masuk',['keluar','masuk'])->indexed();

        $table->integer('cabang_id')->unsigned()->nullable();
        $table->foreign('cabang_id')->references('id')->on('cabang');

        $table->integer('gudang_id')->unsigned()->nullable();
        $table->foreign('gudang_id')->references('id')->on('gudang');

        $table->string('keterangan')->nullable();

        $table->bigInteger('dibuat_oleh_id')->unsigned()->nullable();
        $table->foreign('dibuat_oleh_id')->references('id')->on('users');

        $table->bigInteger('diposting_oleh_id')->unsigned()->nullable();
        $table->foreign('diposting_oleh_id')->references('id')->on('users');

        $table->enum('status',['Draft','Posted'])->default("Draft")->indexed();

        $table->datetime('posted_at')->nullable();
        $table->datetime('tanggal')->nullable();
        $table->timestamps();
      });

      Schema::create('stok_keluar_masuk_detil', function (Blueprint $table) {

          $table->bigInteger('stok_keluar_masuk_id')->unsigned();
          $table->foreign('stok_keluar_masuk_id')->references('id')->on('stok_keluar_masuk')->onDelete('cascade');;

          $table->integer('produk_id')->unsigned();
          $table->foreign('produk_id')->references('id')->on('produk');

          $table->integer('kuantitas')->unsigned();
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      Schema::dropIfExists('stok_keluar_masuk_detil');
       Schema::dropIfExists('stok_keluar_masuk');
    }
}
