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
        Schema::create('realisasi_pengajaran_approvals', function (Blueprint $table) {
            $table->id();

            $table->foreignId('realisasi_id')
                ->constrained('realisasi_pengajarans')
                ->cascadeOnDelete();

            $table->foreignId('dosen_id')->nullable()->constrained('dosens')->cascadeOnUpdate()->cascadeOnDelete();
            $table->enum('role_proses', ['perumusan', 'pemeriksaan'])->default('perumusan');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->boolean('approved')->default(false);
            $table->string('catatan')->nullable();
            $table->date('approved_at')->nullable();
            $table->timestamps();
            $table->unique([
                'realisasi_id',
                'role_proses'
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('realisasi_pengajaran_approvals');
    }
};
