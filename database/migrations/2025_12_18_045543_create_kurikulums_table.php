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
        Schema::create('kurikulums', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('prodi_id');
            $table->string('name');
            $table->string('year');
            $table->string('version');
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->enum('type', ['new', 'minor_revision', 'major_revision'])->default('new');
            $table->enum('status', ['draft', 'submitted','approved_bpm','approved_wadir','approved_direktur','published','archived'])->default('draft');
            $table->unsignedBigInteger('created_by');
            $table->timestamps();

            $table->foreign('prodi_id')->references('id')->on('program_studis')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('parent_id')->references('id')->on('kurikulums')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kurikulums');
    }
};
