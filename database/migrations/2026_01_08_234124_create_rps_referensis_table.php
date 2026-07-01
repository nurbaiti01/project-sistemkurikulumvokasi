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
        Schema::create('rps_referensis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rps_id')->constrained('rps')->cascadeOnDelete();
            $table->enum('jenis', ['utama', 'pendukung']);
            $table->text('deskripsi');
            $table->timestamps();

            $table->index(['rps_id', 'jenis']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rps_referensis');
    }
};
