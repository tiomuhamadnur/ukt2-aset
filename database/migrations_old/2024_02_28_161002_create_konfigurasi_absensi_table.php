<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('konfigurasi_absensi', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->bigInteger('jenis_absensi_id')->unsigned()->nullable();
            $table->time('jam_masuk')->nullable();
            $table->time('jam_pulang')->nullable();
            $table->time('mulai_absen_masuk')->nullable();
            $table->time('selesai_absen_masuk')->nullable();
            $table->time('mulai_absen_pulang')->nullable();
            $table->time('selesai_absen_pulang')->nullable();
            $table->string('toleransi_masuk')->nullable();
            $table->string('toleransi_pulang')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('jenis_absensi_id')->on('jenis_absensi')->references('id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('konfigurasi_absensi');
    }
};
