<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->bigInteger('role_id')->unsigned()->nullable()->after('nip');
            $table->bigInteger('jabatan_id')->unsigned()->nullable()->after('nip');
            $table->bigInteger('employee_type_id')->unsigned()->nullable()->after('nip');
            $table->bigInteger('area_id')->unsigned()->nullable()->after('nip');
            $table->bigInteger('struktur_id')->unsigned()->nullable()->after('nip');

            $table->foreign('role_id')->on('role')->references('id');
            $table->foreign('jabatan_id')->on('jabatan')->references('id');
            $table->foreign('employee_type_id')->on('employee_type')->references('id');
            $table->foreign('area_id')->on('area')->references('id');
            $table->foreign('struktur_id')->on('struktur')->references('id');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['role_id']);
            $table->dropForeign(['jabatan_id']);
            $table->dropForeign(['employee_type_id']);
            $table->dropForeign(['area_id']);
            $table->dropForeign(['struktur_id']);

            $table->dropColumn('role_id');
            $table->dropColumn('jabatan_id');
            $table->dropColumn('employee_type_id');
            $table->dropColumn('area_id');
            $table->dropColumn('struktur_id');
        });
    }
};