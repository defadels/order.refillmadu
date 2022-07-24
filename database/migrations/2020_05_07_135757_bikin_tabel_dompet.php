<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class BikinTabelDompet extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('dompet', function (Blueprint $table) {
        $table->bigIncrements('id');
        $table->integer('cabang_id')->unsigned()->nullable();
        $table->foreign('cabang_id')->references('id')->on('cabang');
        $table->bigInteger('user_id')->unsigned()->nullable();
        $table->foreign('user_id')->references('id')->on('users');
        $table->datetime('tanggal');
        $table->string('keterangan')->nullable();
        $table->string('dibayar_oleh')->nullable();
        $table->string('dibayar_kepada')->nullable();
        $table->bigInteger('nominal');
        $table->enum('debet_kredit',['debet','kredit']);
        $table->bigInteger('saldo_berjalan');
        $table->timestamps();
      });

      Schema::table('users', function (Blueprint $table) {
        $table->bigInteger('saldo')->default(0);
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
        $table->dropColumn('saldo');
      });
      Schema::dropIfExists('dompet');
    }
}
