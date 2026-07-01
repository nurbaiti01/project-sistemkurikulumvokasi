<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('rps_pertemuans', function (Blueprint $table) {
            // $table->json('rancangan_penilaian')->nullable()->after('bobots');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rps_pertemuans', function (Blueprint $table) {
            // $table->dropColumn('rancangan_penilaian');
        });
    }
};
