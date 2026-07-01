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
        Schema::create('tx_pl_prodis', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pl_id');
            $table->unsignedBigInteger('prodi_id');
            $table->timestamps();

            $table->foreign('pl_id')->references('id')->on('profile_lulusans')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('prodi_id')->references('id')->on('program_studis')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tx_pl_prodis');
    }
};
