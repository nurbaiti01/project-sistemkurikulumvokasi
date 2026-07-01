<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('kontrak_kuliahs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('prodi_id')->nullable();
            $table->unsignedBigInteger('matakuliah_id');
            $table->unsignedBigInteger('dosen_id');
            $table->string('tahun_akademik')->nullable();
            $table->string('kelas');
            $table->integer('total_jam')->nullable();
            $table->text('tujuan_pembelajaran')->nullable();
            $table->text('strategi_perkuliahan')->nullable();
            $table->text('materi_pembelajaran')->nullable();
            $table->text('kriteria_penilaian')->nullable();
            $table->text('tata_tertib')->nullable();
            $table->enum('status', ['draft', 'submitted', 'published', 'rejected'])->default('draft');
            $table->timestamps();

            $table->foreign('matakuliah_id')->references('id')->on('matakuliahs')->onDelete('cascade');
            $table->foreign('dosen_id')->references('id')->on('dosens')->onDelete('cascade');
            $table->foreign('prodi_id')->references('id')->on('program_studis')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kontrak_kuliahs');
    }
};
