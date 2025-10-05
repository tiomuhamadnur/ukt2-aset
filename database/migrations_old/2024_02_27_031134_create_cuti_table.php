<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cuti', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->bigInteger('user_id')->unsigned()->nullable();
            $table->bigInteger('jenis_cuti_id')->unsigned()->nullable();
            $table->date('tanggal_awal')->nullable();
            $table->date('tanggal_akhir')->nullable();
            $table->string('jumlah')->nullable();
            $table->string('catatan')->nullable();
            $table->string('lampiran')->nullable();
            $table->bigInteger('known_by_id')->unsigned()->nullable();
            $table->bigInteger('approved_by_id')->unsigned()->nullable();
            $table->string('status')->nullable();
            $table->string('no_surat')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('user_id')->on('users')->references('id');
            $table->foreign('jenis_cuti_id')->on('jenis_cuti')->references('id');
            $table->foreign('known_by_id')->on('users')->references('id');
            $table->foreign('approved_by_id')->on('users')->references('id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cuti');
    }
};
