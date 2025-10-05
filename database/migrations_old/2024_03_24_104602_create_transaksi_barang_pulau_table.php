<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transaksi_barang_pulau', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('barang_pulau_id')->unsigned()->nullable();
            $table->bigInteger('user_id')->unsigned()->nullable();
            $table->uuid('uuid')->unique()->nullable();
            $table->integer('qty')->nullable();
            $table->dateTime('tanggal')->nullable();
            $table->string('kegiatan')->nullable();
            $table->string('photo')->nullable();
            $table->string('catatan')->nullable();
            $table->timestamps();

            $table->foreign('barang_pulau_id')->on('barang_pulau')->references('id');
            $table->foreign('user_id')->on('users')->references('id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transaksi_barang_pulau');
    }
};
