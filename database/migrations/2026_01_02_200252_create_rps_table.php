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
        Schema::create('rps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('matakuliah_id')->constrained('matakuliahs')->cascadeOnDelete();
            $table->foreignId('program_studi_id')->constrained('program_studis')->cascadeOnDelete();
            $table->string('class')->nullable();
            $table->foreignId('dosen_id')->constrained('dosens')->cascadeOnDelete();
            $table->string('academic_year');
            $table->integer('revision')->default(0);
            $table->json('cpmk_bobot')->nullable();
            $table->text('learning_method')->nullable();
            $table->text('learning_experience')->nullable();
            $table->enum('status', ['draft', 'submitted', 'approved', 'published', 'rejected'])->default('draft');
            $table->timestamps();

            $table->index(['matakuliah_id', 'program_studi_id', 'class', 'academic_year']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rps');
    }
};
