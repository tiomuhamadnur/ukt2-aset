<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengiriman_barang', function (Blueprint $table) {
            $table->id();
            $table->string('no_resi')->nullable();
            $table->bigInteger('submitter_id')->unsigned()->nullable();
            $table->bigInteger('gudang_id')->unsigned()->nullable();
            $table->bigInteger('barang_id')->unsigned()->nullable();
            $table->bigInteger('receiver_id')->unsigned()->nullable();
            $table->integer('qty')->nullable();
            $table->string('photo_kirim')->nullable();
            $table->string('photo_terima')->nullable();
            $table->dateTime('tanggal_kirim')->nullable();
            $table->dateTime('tanggal_terima')->nullable();
            $table->string('catatan')->nullable();
            $table->string('status')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('submitter_id')->on('users')->references('id');
            $table->foreign('gudang_id')->on('gudang')->references('id');
            $table->foreign('barang_id')->on('barang')->references('id');
            $table->foreign('receiver_id')->on('users')->references('id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengiriman_barang');
    }
};
