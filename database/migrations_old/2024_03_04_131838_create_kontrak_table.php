<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kontrak', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->uuid('uuid')->unique();
            $table->string('no_kontrak');
            $table->string('nilai_kontrak');
            $table->bigInteger('seksi_id')->unsigned()->nullable();
            $table->date('tanggal')->nullable();
            $table->string('lampiran')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('seksi_id')->on('seksi')->references('id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kontrak');
    }
};
