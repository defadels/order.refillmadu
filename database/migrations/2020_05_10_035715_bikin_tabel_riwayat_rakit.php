<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class BikinTabelRiwayatRakit extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('rakit_produk', function (Blueprint $table) {
        $table->bigIncrements('id');
        $table->integer('cabang_id')->unsigned()->nullable();
        $table->foreign('cabang_id')->references('id')->on('cabang');
        $table->integer('produk_id')->unsigned()->nullable();
        $table->foreign('produk_id')->references('id')->on('produk');

        $table->integer('gudang_id')->unsigned()->nullable();
        $table->foreign('gudang_id')->references('id')->on('gudang');

        $table->datetime('tanggal')->nullable();
        $table->datetime('posted_at')->nullable();
        $table->integer('stok_hasil')->default(0);
        $table->integer('stok_awal')->default(0);
        $table->bigInteger('harga_pokok_awal')->default(0);
        $table->bigInteger('harga_pokok_akhir')->default(0);

        $table->string('keterangan')->nullable();

        $table->bigInteger('dibuat_oleh_id')->unsigned()->nullable();
        $table->foreign('dibuat_oleh_id')->references('id')->on('users');

        $table->bigInteger('diposting_oleh_id')->unsigned()->nullable();
        $table->foreign('diposting_oleh_id')->references('id')->on('users');

        $table->enum('status',['Draft','Posted'])->default("Draft")->indexed();
        $table->timestamps();
      });

      Schema::create('rakit_produk_detil', function (Blueprint $table) {
        //  $table->bigIncrements('id');
          $table->bigInteger('rakit_produk_id')->unsigned();
          $table->foreign('rakit_produk_id')->references('id')->on('rakit_produk')->onDelete('cascade');;

          $table->integer('bahan_id')->unsigned();
          $table->foreign('bahan_id')->references('id')->on('produk');

          $table->integer('kuantitas')->unsigned();
          $table->bigInteger('harga_pokok')->default(0);
      });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

       Schema::dropIfExists('rakit_produk_detil');
       Schema::dropIfExists('rakit_produk');
    }
}
