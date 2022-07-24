<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('nama');
            $table->string('email')->unique()->nullable();
            $table->string('nomor_hp')->unique();

            $table->bigInteger('parent_id')->unsigned()->nullable();
            $table->foreign('parent_id')->references('id')->on('users');

            $table->bigInteger('distributor_id')->unsigned()->nullable();
            $table->foreign('distributor_id')->references('id')->on('users');

//          $table->tinyInteger('level')->default(0);

            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('kode')->nullable();
            $table->string('alamat')->nullable();
            $table->string('alamat_2')->nullable();
            $table->bigInteger('poin')->default(0);
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
