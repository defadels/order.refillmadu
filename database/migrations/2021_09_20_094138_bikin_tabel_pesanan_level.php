<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class BikinTabelPesananLevel extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('pesanan_upline', function (Blueprint $table) {

          $table->bigInteger('pesanan_id')->unsigned();
          $table->foreign('pesanan_id')->references('id')->on('pesanan')->onDelete('cascade');

          $table->bigInteger('pelanggan_id')->unsigned()->nullable();
          $table->foreign('pelanggan_id')->references('id')->on('users');

          $table->tinyInteger('pelanggan_level')->unsigned()->default(0);
          $table->bigInteger('nominal_pembelian')->unsigned()->default(0);

      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      Schema::dropIfExists('pesanan_level');
    }
}
