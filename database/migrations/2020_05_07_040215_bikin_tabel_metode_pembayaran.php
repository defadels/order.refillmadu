<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class BikinTabelMetodePembayaran extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('metode_pembayaran', function (Blueprint $table) {
        $table->increments('id');
        $table->integer('cabang_id')->unsigned()->nullable();
        $table->foreign('cabang_id')->references('id')->on('cabang');
        $table->string('nama');
        $table->string('deskripsi')->nullable();
        $table->enum('jenis',['cod','cash','bank','dompet','custom'])->default("custom");
        $table->enum('status',['Aktif','Nonaktif'])->default("Aktif");
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

       Schema::dropIfExists('metode_pembayaran');
    }
}
