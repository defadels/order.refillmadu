<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class BikinTabelPesanan extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('pesanan', function (Blueprint $table) {
      $table->bigIncrements('id');

      // informasi umum
      $table->string('keterangan')->nullable();

      $table->bigInteger('nominal_pembelian')->unsigned()->default(0);

      // pelanggan di invoice..
      $table->bigInteger('pelanggan_id')->unsigned()->nullable();
      $table->foreign('pelanggan_id')->references('id')->on('users');
      $table->string('no_invoice')->nullable();

      $table->tinyInteger('pelanggan_level')->unsigned()->default(0);

      // step 3 barang dihandle pemrosesannya oleh ... jika tidak sama dengan penjual, berarti dropship
      // jika kosong, maka barang diproses oleh HeadQuearter
      $table->bigInteger('pemroses_id')->unsigned()->nullable(); // pemesanan => kalau kosong pesan di HQ
      $table->foreign('pemroses_id')->references('id')->on('users');

      // jika diproses oleh HQ, maka kolom ini kan terisi....
      $table->integer('cabang_id')->unsigned()->nullable();
      $table->foreign('cabang_id')->references('id')->on('cabang');

      $table->integer('gudang_id')->unsigned()->nullable();
      $table->foreign('gudang_id')->references('id')->on('gudang');

      //**********  INFORMASI PEMBAYARAN *************/
      $table->integer('metode_pembayaran_id')->unsigned()->nullable();
      $table->foreign('metode_pembayaran_id')->references('id')->on('metode_pembayaran');

      $table->enum('status_pembayaran', ['belum_bayar','sudah_bayar','belum_diperiksa']);
      $table->integer('nominal_yang_dibayar')->unsigned()->default(0);

      $table->bigInteger('transaksi_kas_id')->unsigned()->nullable();
      $table->foreign('transaksi_kas_id')->references('id')->on('transaksi_kas');


      //**********  INFORMASI PENGIRIMAN *************/
      $table->integer('metode_pengiriman_id')->unsigned()->nullable();
      $table->foreign('metode_pengiriman_id')->references('id')->on('metode_pengiriman');
      $table->enum('status_pengiriman', ['belum_dikirim','sudah_dikirim','belum_diperiksa']);
      $table->string('nomor_resi')->nullable();
      $table->date('tanggal_pengiriman')->nullable();
      $table->date('tanggal_pembayaran')->nullable();
      $table->integer('ongkos_kirim')->unsigned()->default(0);
      $table->integer('ongkos_kurir')->unsigned()->default(0);
      $table->integer('biaya_tambahan')->unsigned()->default(0);
      $table->integer('bobot_pengiriman')->unsigned()->default(0);

      $table->bigInteger('diantar_oleh_id')->unsigned()->nullable();
      $table->foreign('diantar_oleh_id')->references('id')->on('users');


      // label pengiriman
      $table->string('dikirim_oleh')->nullable();
      $table->string('nomor_hp_pengirim')->nullable();
      $table->string('alamat_pengirim')->nullable();
      $table->string('dikirim_kepada')->nullable();
      $table->string('nomor_hp_tujuan')->nullable();
      $table->string('alamat_tujuan')->nullable();


      //**********  STATUS ORDERAN *************/

      $table->enum('status', [
        'preorder',
        'masuk',
        'diproses',
        'diantar',
        'dikirim',
        'selesai',
        'dibatalkan'
      ])->default("masuk")->indexed();

      // pelaku aktivitas dan waktunya
      $table->bigInteger('dibuat_oleh_id')->unsigned()->nullable();
      $table->foreign('dibuat_oleh_id')->references('id')->on('users');

      $table->bigInteger('diproses_oleh_id')->unsigned()->nullable();
      $table->foreign('diproses_oleh_id')->references('id')->on('users');

      $table->bigInteger('dikirim_oleh_id')->unsigned()->nullable();
      $table->foreign('dikirim_oleh_id')->references('id')->on('users');

      $table->bigInteger('diselesaikan_oleh_id')->unsigned()->nullable();
      $table->foreign('diselesaikan_oleh_id')->references('id')->on('users');

      $table->bigInteger('dibatalkan_oleh_id')->unsigned()->nullable();
      $table->foreign('dibatalkan_oleh_id')->references('id')->on('users');

      $table->datetime('dimasukkan_pada')->nullable();
      $table->datetime('diproses_pada')->nullable();
      $table->datetime('dikirim_pada')->nullable();
      $table->datetime('diantar_pada')->nullable();
      $table->datetime('diselesaikan_pada')->nullable();
      $table->datetime('dibatalkan_pada')->nullable();
      $table->timestamps();
    });

    Schema::create('pesanan_detil', function (Blueprint $table) {
      $table->bigInteger('pesanan_id')->unsigned();
      $table->foreign('pesanan_id')->references('id')->on('pesanan')->onDelete('cascade');
      $table->integer('produk_id')->unsigned();
      $table->foreign('produk_id')->references('id')->on('produk');
      $table->integer('kuantitas')->unsigned();
      $table->integer('harga')->unsigned()->nullable();
      $table->integer('harga_1')->unsigned()->nullable();
      $table->integer('harga_2')->unsigned()->nullable();
      $table->integer('harga_3')->unsigned()->nullable();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('pesanan_detil');
    Schema::dropIfExists('pesanan');
  }
}
