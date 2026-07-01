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
        Schema::create('rps_approvals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rps_id')
                ->constrained('rps')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->foreignId('dosen_id')
                ->nullable()
                ->constrained('dosens')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->boolean('approved')->default(false);
            $table->enum('role_proses', ['perumusan', 'pemeriksaan', 'persetujuan', 'penetapan', 'pengendalian'])->default('perumusan');
            $table->text('catatan')->nullable(); // optional, alasan tolak
            $table->date('approved_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rps_approvals');
    }
};
