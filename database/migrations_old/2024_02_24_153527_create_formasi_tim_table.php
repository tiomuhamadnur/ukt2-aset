<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('formasi_tim', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('struktur_id')->unsigned()->nullable();
            $table->uuid('uuid')->unique();
            $table->bigInteger('area_id')->unsigned()->nullable();
            $table->bigInteger('koordinator_id')->unsigned()->nullable();
            $table->bigInteger('anggota_id')->unsigned()->nullable();
            $table->year('periode')->nullable();
            $table->string('status')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('struktur_id')->on('struktur')->references('id');
            $table->foreign('area_id')->on('area')->references('id');
            $table->foreign('koordinator_id')->on('users')->references('id');
            $table->foreign('anggota_id')->on('users')->references('id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('formasi_tim');
    }
};