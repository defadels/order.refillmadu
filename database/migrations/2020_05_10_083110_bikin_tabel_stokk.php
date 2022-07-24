<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class BikinTabelStokk extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('riwayat_stok_produk', function (Blueprint $table) {
        $table->bigIncrements('id');
        $table->integer('produk_id')->unsigned()->nullable();
        $table->foreign('produk_id')->references('id')->on('produk');
        $table->integer('gudang_id')->unsigned()->nullable();
        $table->foreign('gudang_id')->references('id')->on('gudang');
        $table->integer('stok_awal')->default(0);
        $table->enum('keluar_masuk',['keluar','masuk'])->default('masuk');
        $table->integer('kuantitas')->default(0);
        $table->bigInteger('harga_pokok')->default(0);

        $table->bigInteger('sumber_id');
        $table->string('sumber_type');

        $table->timestamps();
      });

      Schema::create('stok_produk', function (Blueprint $table) {
    //    $table->bigIncrements('id');
        $table->integer('produk_id')->unsigned()->nullable();
        $table->foreign('produk_id')->references('id')->on('produk')->onDelete('cascade');
        $table->integer('gudang_id')->unsigned()->nullable();
        $table->foreign('gudang_id')->references('id')->on('gudang');

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

      Schema::dropIfExists('stok_produk');

      Schema::dropIfExists('riwayat_stok_produk');
        //
    }
}
