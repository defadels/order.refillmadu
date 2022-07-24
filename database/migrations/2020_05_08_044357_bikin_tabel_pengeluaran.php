<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class BikinTabelPengeluaran extends Migration
{
  public function up()
    {

      Schema::create('kategori_pengeluaran', function (Blueprint $table) {
        $table->increments('id');
        $table->string('nama');
        $table->string('deskripsi')->nullable();
        $table->enum('publikasi',['ya','tidak'])->default('ya');
        $table->timestamps();
    });

    echo "kategori pengeluaran sudah dibikin\n";

    Schema::create('pengeluaran', function (Blueprint $table) {

        $table->bigIncrements('id');
        $table->date('tanggal');
        $table->bigInteger('nominal');
        $table->string('nomor_transaksi')->nullable()->indexed(); // di kontrol panel
        $table->string('nomor_invoice')->nullable()->indexed();
        $table->string('bukti_transaksi')->nullable()->indexed();
        $table->string('kode')->nullable()->indexed();

        $table->integer('cabang_id')->unsigned()->nullable();
        $table->foreign('cabang_id')->references('id')->on('cabang');

        $table->integer('kategori_pengeluaran_id')->unsigned()->nullable();
        $table->foreign('kategori_pengeluaran_id')->references('id')->on('kategori_pengeluaran');


        $table->string('keterangan')->nullable();

        $table->bigInteger('transaksi_kas_id')->unsigned()->nullable();
        $table->foreign('transaksi_kas_id')->references('id')->on('transaksi_kas');

        $table->string('dibayar_oleh')->nullable();
        $table->string('dibayar_kepada')->nullable();

        $table->bigInteger('diinput_oleh_id')->unsigned()->nullable();
        $table->foreign('diinput_oleh_id')->references('id')->on('users');

        $table->timestamps();

      });


    echo "pengeluaran sudah dibikin\n";


      Schema::create('pengeluaran_pengiriman', function (Blueprint $table) {

        $table->bigInteger('pengeluaran_id')->unsigned()->nullable();
        $table->foreign('pengeluaran_id')->references('id')
                ->on('pengeluaran')->onDelete('cascade');

        $table->integer('metode_pengiriman_id')->unsigned()->nullable();
        $table->foreign('metode_pengiriman_id')->references('id')->on('metode_pengiriman');

      });

    }



    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

      Schema::dropIfExists('pengeluaran_pengiriman');
      Schema::dropIfExists('pengeluaran');
      Schema::dropIfExists('kategori_pengeluaran');

    }
}
