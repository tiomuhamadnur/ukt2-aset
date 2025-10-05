<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('absensi', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->bigInteger('user_id')->unsigned()->nullable();
            $table->bigInteger('jenis_absensi_id')->unsigned()->nullable();
            $table->date('tanggal')->nullable();
            $table->time('jam_masuk')->nullable();
            $table->time('jam_pulang')->nullable();
            $table->string('telat_masuk')->nullable();
            $table->string('cepat_pulang')->nullable();
            $table->string('status_masuk')->nullable();
            $table->string('status_pulang')->nullable();
            $table->string('photo_masuk')->nullable();
            $table->string('photo_pulang')->nullable();
            $table->string('latitude_masuk')->nullable();
            $table->string('longitude_masuk')->nullable();
            $table->string('latitude_pulang')->nullable();
            $table->string('longitude_pulang')->nullable();
            $table->bigInteger('known_by_id')->unsigned()->nullable();
            $table->bigInteger('approved_by_id')->unsigned()->nullable();
            $table->string('status')->nullable();
            $table->string('catatan')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('user_id')->on('users')->references('id');
            $table->foreign('jenis_absensi_id')->on('jenis_absensi')->references('id');
            $table->foreign('known_by_id')->on('users')->references('id');
            $table->foreign('approved_by_id')->on('users')->references('id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('absensi');
    }
};
