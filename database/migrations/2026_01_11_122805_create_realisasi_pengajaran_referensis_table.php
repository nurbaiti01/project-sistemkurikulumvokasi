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
        Schema::create('realisasi_pengajaran_referensis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('realisasi_id')
                ->constrained('realisasi_pengajarans')
                ->cascadeOnDelete();

            $table->enum('jenis', ['diktat', 'buku']);
            $table->string('judul');
            $table->string('penerbit')->nullable();
            $table->timestamps();

            $table->index(['realisasi_id', 'jenis']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('realisasi_pengajaran_referensis');
    }
};
