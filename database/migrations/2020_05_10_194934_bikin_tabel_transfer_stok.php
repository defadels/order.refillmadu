<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class BikinTabelTransferStok extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('transfer_stok', function (Blueprint $table) {
        $table->bigIncrements('id');

        $table->integer('cabang_id')->unsigned()->nullable();
        $table->foreign('cabang_id')->references('id')->on('cabang');

        $table->integer('gudang_asal_id')->unsigned()->nullable();
        $table->foreign('gudang_asal_id')->references('id')->on('gudang');

        $table->integer('gudang_tujuan_id')->unsigned()->nullable();
        $table->foreign('gudang_tujuan_id')->references('id')->on('gudang');

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
      Schema::create('transfer_stok_detil', function (Blueprint $table) {

        $table->bigInteger('transfer_stok_id')->unsigned();
        $table->foreign('transfer_stok_id')->references('id')->on('transfer_stok')->onDelete('cascade');;

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

      Schema::dropIfExists('transfer_stok_detil');
      Schema::dropIfExists('transfer_stok');
    }
}
