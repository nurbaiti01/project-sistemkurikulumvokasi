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
        Schema::create('realisasi_pengajaran_metodes', function (Blueprint $table) {
            $table->id();

            $table->foreignId('realisasi_id')
                ->constrained('realisasi_pengajarans')
                ->cascadeOnDelete();

            $table->enum('jenis', ['kuliah', 'tutorial', 'laboratorium']);
            $table->integer('jam')->default(0);
            $table->timestamps();

            $table->unique([
                'realisasi_id',
                'jenis'
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('realisasi_pengajaran_metodes');
    }
};
