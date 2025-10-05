<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('gudang', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->uuid('uuid')->unique();
            $table->string('code');
            $table->bigInteger('pulau_id')->unsigned()->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('pulau_id')->on('pulau')->references('id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gudang');
    }
};
