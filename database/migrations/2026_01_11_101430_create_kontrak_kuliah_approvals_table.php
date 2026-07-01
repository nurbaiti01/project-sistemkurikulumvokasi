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
        Schema::create('kontrak_kuliah_approvals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kontrak_kuliah_id')->constrained('kontrak_kuliahs')->cascadeOnDelete();
            $table->foreignId('dosen_id')->nullable()->constrained('dosens')->cascadeOnUpdate()->cascadeOnDelete();
            $table->enum('role_proses', ['perumusan', 'pemeriksaan'])->default('perumusan');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->boolean('approved')->default(false);
            $table->string('catatan')->nullable();
            $table->date('approved_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kontrak_kuliah_approvals');
    }
};
