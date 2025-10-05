<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('absensi', function (Blueprint $table) {
            $table->renameColumn('catatan', 'catatan_masuk');
            $table->string('catatan_pulang')->nullable()->after('catatan');
        });
    }

    public function down(): void
    {
        Schema::table('absensi', function (Blueprint $table) {
            $table->renameColumn('catatan_masuk', 'catatan');
            $table->dropColumn('catatan_pulang');
        });
    }
};
