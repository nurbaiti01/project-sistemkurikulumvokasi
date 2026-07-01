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
        Schema::create('tx_cpmk_prodis', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cpmk_id');
            $table->unsignedBigInteger('prodi_id');
            $table->timestamps();

            $table->foreign('cpmk_id')->references('id')->on('capaian_pembelajaran_matakuliahs')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('prodi_id')->references('id')->on('program_studis')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tx_cpmk_prodis');
    }
};
