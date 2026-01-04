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
        Schema::create('longitudinal_requirements', function (Blueprint $table) {
            $table->id();
            $table->string('title');                   // Main title
            $table->text('action')->nullable();        // Action / Description

            // Sections
            $table->text('hospital_activities')->nullable(); // Section A
            $table->text('summary_operations')->nullable(); // Section B
            $table->text('scientific_meetings')->nullable(); // Section C
            $table->text('compulsory_courses')->nullable();  // Section D

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('longitudinal_requirements');
    }
};
