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
        Schema::create('kurikulum_approvals', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('kurikulum_id');

            $table->enum('role', [
                'bpm',
                'wadir',
                'direktur',
            ]);

            $table->unsignedBigInteger('approved_by')->nullable();

            $table->enum('status', [
                'pending',
                'approved',
                'rejected',
            ])->default('pending');

            $table->text('note')->nullable();

            $table->timestamp('approved_at')->nullable();
            $table->timestamps();

            $table->foreign('kurikulum_id')
                ->references('id')
                ->on('kurikulums')
                ->onDelete('cascade');

            $table->foreign('approved_by')
                ->references('id')
                ->on('users')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kurikulum_approvals');
    }
};
