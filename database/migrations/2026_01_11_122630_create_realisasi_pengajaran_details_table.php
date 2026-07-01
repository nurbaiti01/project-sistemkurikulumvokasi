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
        Schema::create('realisasi_pengajaran_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('realisasi_id')
                ->constrained('realisasi_pengajarans')
                ->cascadeOnDelete();

            $table->tinyInteger('pertemuan_ke');
            $table->date('tanggal')->nullable();
            $table->text('pokok_bahasan')->nullable();
            $table->time('jam')->nullable();
            $table->boolean('paraf')->default(false);
            $table->timestamps();

            $table->unique(['realisasi_id', 'pertemuan_ke']);
            $table->index('realisasi_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('realisasi_pengajaran_details');
    }
};
