<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('area', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->bigInteger('provinsi_id')->unsigned()->nullable();
            $table->bigInteger('walikota_id')->unsigned()->nullable();
            $table->bigInteger('kecamatan_id')->unsigned()->nullable();
            $table->bigInteger('kelurahan_id')->unsigned()->nullable();
            $table->bigInteger('pulau_id')->unsigned()->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('provinsi_id')->on('provinsi')->references('id');
            $table->foreign('walikota_id')->on('walikota')->references('id');
            $table->foreign('kecamatan_id')->on('kecamatan')->references('id');
            $table->foreign('kelurahan_id')->on('kelurahan')->references('id');
            $table->foreign('pulau_id')->on('pulau')->references('id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('area');
    }
};