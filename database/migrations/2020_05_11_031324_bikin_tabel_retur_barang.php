<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class BikinTabelReturBarang extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('retur_barang', function (Blueprint $table) {
        $table->bigIncrements('id');

        $table->integer('cabang_id')->unsigned()->nullable();
        $table->foreign('cabang_id')->references('id')->on('cabang');

        $table->integer('gudang_id')->unsigned()->nullable();
        $table->foreign('gudang_id')->references('id')->on('gudang');

        $table->string('keterangan')->nullable();

        $table->bigInteger('pelanggan_id')->unsigned()->nullable();
        $table->foreign('pelanggan_id')->references('id')->on('users');

        $table->integer('kas_asal_id')->unsigned()->nullable();
        $table->foreign('kas_asal_id')->references('id')->on('kas');

        $table->bigInteger('nominal')->unsignet()->default(0);

        $table->bigInteger('transaksi_kas_id')->unsigned()->nullable();
        $table->foreign('transaksi_kas_id')->references('id')->on('transaksi_kas');

        $table->bigInteger('dibuat_oleh_id')->unsigned()->nullable();
        $table->foreign('dibuat_oleh_id')->references('id')->on('users');

        $table->bigInteger('diposting_oleh_id')->unsigned()->nullable();
        $table->foreign('diposting_oleh_id')->references('id')->on('users');

        $table->enum('status',['Draft','Posted'])->default("Draft")->indexed();
        $table->enum('bayar_dengan',['cash','dompet'])->default("cash")->indexed();

        $table->datetime('tanggal')->nullable();
        $table->datetime('posted_at')->nullable();
        $table->timestamps();
      });

      Schema::create('retur_barang_detil', function (Blueprint $table) {

          $table->bigInteger('retur_barang_id')->unsigned();
          $table->foreign('retur_barang_id')->references('id')->on('retur_barang')->onDelete('cascade');;

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
      Schema::dropIfExists('retur_barang_detil');
      Schema::dropIfExists('retur_barang');
    }
}
