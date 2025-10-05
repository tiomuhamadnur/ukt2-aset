<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('struktur', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->bigInteger('unitkerja_id')->unsigned()->nullable();
            $table->bigInteger('seksi_id')->unsigned()->nullable();
            $table->bigInteger('tim_id')->unsigned()->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('unitkerja_id')->on('unitkerja')->references('id');
            $table->foreign('seksi_id')->on('seksi')->references('id');
            $table->foreign('tim_id')->on('tim')->references('id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('struktur');
    }
};