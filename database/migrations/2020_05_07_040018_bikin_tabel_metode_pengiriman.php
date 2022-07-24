<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class BikinTabelMetodePengiriman extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('metode_pengiriman', function (Blueprint $table) {
        $table->increments('id');
        $table->integer('cabang_id')->unsigned()->nullable();
        $table->foreign('cabang_id')->references('id')->on('cabang');
        $table->string('nama');
        $table->enum('jenis',['diantar','dijemput','custom'])->default("custom");
        $table->string('deskripsi')->nullable();
        $table->enum('status',['Aktif','Nonaktif'])->default("Aktif");
        $table->bigInteger('saldo')->default(0);
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
       Schema::dropIfExists('metode_pengiriman');
    }
}
