<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kategori', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->uuid('uuid')->unique();
            $table->bigInteger('seksi_id')->unsigned()->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('seksi_id')->on('seksi')->references('id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kategori');
    }
};