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
        Schema::create('pivot_pl_cpls', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('kurikulum_id');
            $table->unsignedBigInteger('pl_id');
            $table->unsignedBigInteger('cpl_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pivot_pl_cpls');
    }
};
