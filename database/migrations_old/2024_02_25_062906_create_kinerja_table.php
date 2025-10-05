<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kinerja', function (Blueprint $table) {
            $table->id();
            $table->string('ticket_number')->unique();
            $table->uuid('uuid')->unique();
            $table->bigInteger('formasi_tim_id')->unsigned()->nullable();
            $table->bigInteger('unitkerja_id')->unsigned()->nullable();
            $table->bigInteger('seksi_id')->unsigned()->nullable();
            $table->bigInteger('tim_id')->unsigned()->nullable();
            $table->bigInteger('pulau_id')->unsigned()->nullable();
            $table->bigInteger('koordinator_id')->unsigned()->nullable();
            $table->bigInteger('anggota_id')->unsigned()->nullable();
            $table->bigInteger('kategori_id')->unsigned()->nullable();
            $table->string('kegiatan')->nullable();
            $table->string('deskripsi')->nullable();
            $table->string('lokasi')->nullable();
            $table->date('tanggal')->nullable();
            $table->time('waktu_mulai')->nullable();
            $table->time('waktu_selesai')->nullable();
            $table->json('photo')->nullable();
            $table->string('status')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('formasi_tim_id')->on('formasi_tim')->references('id');
            $table->foreign('unitkerja_id')->on('unitkerja')->references('id');
            $table->foreign('seksi_id')->on('seksi')->references('id');
            $table->foreign('tim_id')->on('tim')->references('id');
            $table->foreign('pulau_id')->on('pulau')->references('id');
            $table->foreign('koordinator_id')->on('users')->references('id');
            $table->foreign('anggota_id')->on('users')->references('id');
            $table->foreign('kategori_id')->on('kategori')->references('id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kinerja');
    }
};