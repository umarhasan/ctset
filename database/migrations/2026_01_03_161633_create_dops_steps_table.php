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
        Schema::create('dops_steps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dop_id')->constrained('dops')->cascadeOnDelete();   // Related DOP
            $table->foreignId('competency_id')->constrained('competencies')->cascadeOnDelete(); // Related competency
            $table->string('name');       // Step name
            $table->text('action')->nullable(); // Action / notes
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dops_steps');
    }
};
