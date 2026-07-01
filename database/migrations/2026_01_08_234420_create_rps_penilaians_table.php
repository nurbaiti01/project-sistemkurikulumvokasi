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
        Schema::create('rps_penilaians', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rps_id')->constrained('rps')->cascadeOnDelete();
            $table->string('jenis_penilaian');
            $table->unsignedBigInteger('cpmk_id');
            $table->integer('persentase_penilaian')->default(0);
            $table->integer('bobot_cpmk')->default(0);
            $table->enum('kelompok', ['default', 'kognitif'])->default('default');
            $table->timestamps();

            $table->index(['rps_id', 'jenis_penilaian', 'cpmk_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rps_penilaians');
    }
};
