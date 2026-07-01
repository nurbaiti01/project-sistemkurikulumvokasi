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
        Schema::create('realisasi_pengajaran_evaluasis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('realisasi_id')
                ->constrained('realisasi_pengajarans')
                ->cascadeOnDelete();

            $table->decimal('tugas_persen', 5, 2)->default(0);
            $table->decimal('kuis_persen', 5, 2)->default(0);
            $table->decimal('ujian_persen', 5, 2)->default(0);
            $table->timestamps();

            $table->unique(['realisasi_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('realisasi_pengajaran_evaluasis');
    }
};
