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
        Schema::create('beban_ajar_dosens', function (Blueprint $table) {
            $table->id();

            $table->foreignId('dosen_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('matakuliah_id')
                ->constrained()
                ->cascadeOnDelete();

            // Prodi tempat mengajar
            $table->foreignId('taught_prodi_id')
                ->constrained('program_studis')
                ->cascadeOnDelete();

            // Prodi asal MK
            $table->foreignId('home_prodi_id')
                ->constrained('program_studis')
                ->cascadeOnDelete();

            $table->string('kelas', 10);

            $table->string('tahun_ajaran', 9);
            $table->enum('semester', ['ganjil', 'genap', 'antara']);

            $table->enum('peran', ['koordinator', 'pengampu', 'asisten'])
                ->default('pengampu');

            $table->decimal('sks_beban', 3, 1)->nullable();

            $table->timestamps();

            $table->unique([
                'dosen_id',
                'matakuliah_id',
                'taught_prodi_id',
                'kelas',
                'tahun_ajaran',
                'semester'
            ], 'uq_beban_ajar_periode');

            $table->index([
                'taught_prodi_id',
                'tahun_ajaran',
                'semester'
            ], 'idx_beban_prodi_periode');

            $table->index([
                'dosen_id',
                'tahun_ajaran',
                'semester'
            ], 'idx_beban_dosen_periode');

            $table->index([
                'home_prodi_id',
                'taught_prodi_id'
            ], 'idx_beban_lintas_prodi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('beban_ajar_dosens');
    }
};
