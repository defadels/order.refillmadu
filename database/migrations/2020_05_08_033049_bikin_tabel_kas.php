<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class BikinTabelKas extends Migration
{

  public function up()
  {
    Schema::create('kas', function (Blueprint $table) {
      $table->increments('id');
      $table->string('nama');
      $table->string('keterangan');
      $table->string('nama_bank')->nullable();
      $table->string('nomor_rekening')->nullable();
      $table->bigInteger('saldo');
      $table->bigInteger('saldo_awal');
      $table->enum('jenis',['ditangan','bank']);
      $table->string('kode')->nullable();

      $table->integer('cabang_id')->unsigned()->nullable();
      $table->foreign('cabang_id')->references('id')->on('cabang');


//      $table->bigInteger('user_id')->unsigned()->nullable();
  //    $table->foreign('user_id')->references('id')->on('users');

      $table->timestamps();

    });

    Schema::create('transaksi_kas', function (Blueprint $table) {
      $table->bigIncrements('id');

      $table->integer('kas_id')->unsigned();
      $table->foreign('kas_id')->references('id')->on('kas');

      $table->date('tanggal');
      $table->string('keterangan');

      $table->enum('debet_kredit',['d','k']);

      $table->bigInteger('nominal');

      $table->timestamps();
    });

    Schema::create('transfer_kas', function (Blueprint $table) {
      $table->bigIncrements('id');
      // tanggal
      // nominal diambil dari trx
      $table->bigInteger('trx_debet_id')->unsigned();
      $table->foreign('trx_debet_id')->references('id')->on('transaksi_kas');

      $table->bigInteger('trx_kredit_id')->unsigned();
      $table->foreign('trx_kredit_id')->references('id')->on('transaksi_kas');

      $table->string('oleh');

      $table->integer('cabang_id')->unsigned()->nullable();
      $table->foreign('cabang_id')->references('id')->on('cabang');

      $table->timestamps();
    });

    Schema::create('saldo_kas_bulanan', function (Blueprint $table) {

      $table->bigIncrements('id');
      $table->integer('kas_id')->unsigned();
      $table->foreign('kas_id')->references('id')->on('kas');
      $table->date('bulan');
      $table->bigInteger('saldo_bulan');

    });

    Schema::table('dompet', function (Blueprint $table) {
      $table->bigInteger('transaksi_kas_id')->unsigned()->nullable();
      $table->foreign('transaksi_kas_id')->references('id')->on('transaksi_kas');
     });

     Schema::table('metode_pembayaran', function (Blueprint $table) {
      $table->integer('kas_id')->unsigned()->nullable();
      $table->foreign('kas_id')->references('id')->on('kas');
     });


  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {

    Schema::table('metode_pembayaran', function (Blueprint $table) {
      $table->dropForeign(['kas_id']);
      $table->dropColumn('kas_id');
     });

    Schema::table('dompet', function (Blueprint $table) {
      $table->dropForeign(['transaksi_kas_id']);
      $table->dropColumn('transaksi_kas_id');
     });

    Schema::dropIfExists('saldo_kas_bulanan');
    Schema::dropIfExists('transfer_kas');
    Schema::dropIfExists('transaksi_kas');
    Schema::dropIfExists('kas');
  }
}
