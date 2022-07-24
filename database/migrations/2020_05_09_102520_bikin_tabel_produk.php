<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class BikinTabelProduk extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('kategori_produk', function (Blueprint $table) {
        $table->increments('id');
        $table->integer('cabang_id')->unsigned()->nullable();
        $table->foreign('cabang_id')->references('id')->on('cabang');
        $table->string('nama');
        $table->string('deskripsi')->nullable();
        $table->enum('status',['Aktif','Nonaktif'])->default("Aktif");
        $table->timestamps();
      });
      Schema::create('produk', function (Blueprint $table) {
        $table->increments('id');
        $table->string('kode')->unique()->indexed();

        $table->integer('kategori_id')->unsigned()->nullable();
        $table->foreign('kategori_id')->references('id')->on('kategori_produk');
        $table->integer('cabang_id')->unsigned()->nullable();
        $table->foreign('cabang_id')->references('id')->on('cabang');

        $table->string('nama');
        $table->string('deskripsi')->nullable();

        $table->string('satuan')->nullable();
        $table->bigInteger('harga_jual')->default(0);
        $table->bigInteger('harga_pokok')->default(0);

        $table->bigInteger('stok')->default(0);
        $table->integer('poin')->default(0);
        $table->double('volume')->default(0);

        $table->enum('status',['Aktif','Nonaktif'])->default("Aktif");
        $table->timestamps();
      });

      Schema::create('harga_khusus', function (Blueprint $table) {
        $table->increments('id');
        $table->integer('produk_id')->unsigned()->nullable();
        $table->foreign('produk_id')->references('id')->on('produk')->onDelete('cascade');;

        $table->integer('kategori_id')->unsigned()->nullable();
        $table->foreign('kategori_id')->references('id')->on('kategori_pelanggan');

        $table->bigInteger('harga_jual')->default(0);

        $table->timestamps();
      });

      Schema::create('struktur_produk', function (Blueprint $table) {
        $table->increments('id');
        $table->integer('produk_id')->unsigned()->nullable();
        $table->foreign('produk_id')->references('id')->on('produk')->onDelete('cascade');;
        $table->integer('bahan_id')->unsigned()->nullable();
        $table->foreign('bahan_id')->references('id')->on('produk');
        $table->integer('qty_bahan')->unsigned()->nullable();
        $table->integer('qty_produk')->unsigned()->nullable();
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

       Schema::dropIfExists('struktur_produk');
       Schema::dropIfExists('harga_khusus');
       Schema::dropIfExists('produk');
       Schema::dropIfExists('kategori_produk');
    }
}
