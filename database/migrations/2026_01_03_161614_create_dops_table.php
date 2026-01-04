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
        Schema::create('dops', function (Blueprint $table) {
            $table->id();
            $table->string('title');                        // DOP title
            $table->string('requirement_name')->nullable(); // Requirement name
            $table->json('rotations')->nullable();         // Rotation multiple select (JSON)
            $table->json('levels')->nullable();            // Level multiple select (JSON)
            $table->json('ratings')->nullable();           // Rating multiple select (JSON)
            $table->text('steps')->nullable();             // Steps to be performed
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dops');
    }
};
