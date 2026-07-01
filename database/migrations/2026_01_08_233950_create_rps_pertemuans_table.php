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
        Schema::create('rps_pertemuans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rps_id')->constrained('rps')->cascadeOnDelete();
            $table->integer('pertemuan_ke');
            $table->text('materi_ajar')->nullable();
            $table->text('indikator')->nullable();
            $table->text('bentuk_pembelajaran')->nullable();
            $table->unsignedBigInteger('cpmk_id')->nullable();
            $table->boolean('pemberian_tugas')->default(false);
            $table->json('alokasi')->nullable(); // array of tipe, jumlah, menit
            $table->json('bobots')->nullable(); // array of jenis, bobot
            $table->json('rancangan_penilaian')->nullable();
            $table->timestamps();

            $table->index(['rps_id', 'pertemuan_ke']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rps_pertemuans');
    }
};
