<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class BikinTabelPoin extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('point', function (Blueprint $table) {
        $table->bigIncrements('id');

        $table->bigInteger('user_id')->unsigned()->nullable();
        $table->foreign('user_id')->references('id')->on('users');
        $table->bigInteger('pesanan_id')->unsigned()->nullable();
        $table->foreign('pesanan_id')->references('id')->on('pesanan');

        $table->datetime('tanggal');
        $table->string('keterangan')->nullable();
        $table->bigInteger('nominal');
        $table->enum('debet_kredit',['debet','kredit']);
        $table->bigInteger('saldo_berjalan');
        $table->timestamps();
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      Schema::dropIfExists('point');
    }
}
