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
        Schema::create('realisasi_pengajarans', function (Blueprint $table) {
            $table->id();

            $table->foreignId('program_studi_id')
                ->constrained('program_studis')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->foreignId('matakuliah_id')
                ->constrained('matakuliahs')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->foreignId('dosen_id')
                ->nullable()
                ->constrained('dosens')
                ->cascadeOnUpdate()
                ->nullOnDelete();

            $table->string('semester', 10);
            $table->string('tahun_akademik', 20);
            $table->tinyInteger('jumlah_sks');
            $table->text('kelas')->nullable();
            $table->text('tujuan_instruksional_umum')->nullable();
            $table->enum('status', ['draft', 'submitted', 'approved', 'rejected'])->default('draft');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('realisasi_pengajarans');
    }
};
