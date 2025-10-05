<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('barang_pulau', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('barang_id')->unsigned()->nullable();
            $table->bigInteger('gudang_id')->unsigned()->nullable();
            $table->uuid('uuid')->unique()->nullable();
            $table->integer('stock_awal')->nullable();
            $table->integer('stock_aktual')->nullable();
            $table->dateTime('tanggal_terima')->nullable();
            $table->string('no_resi')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('barang_id')->on('barang')->references('id');
            $table->foreign('gudang_id')->on('gudang')->references('id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('barang_pulau');
    }
};
