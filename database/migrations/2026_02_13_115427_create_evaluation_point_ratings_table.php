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
        Schema::create('evaluation_point_ratings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('evaluation_id')->constrained('trainee_evaluations')->onDelete('cascade');
            $table->foreignId('section_id')->constrained('trainee_evaluation_sections')->onDelete('cascade');
            $table->foreignId('point_id')->constrained('trainee_evaluation_points')->onDelete('cascade');

            
            $table->enum('rating', [
                'unsatisfactory',
                'needs_attention',
                'satisfactory',
                'well_above_average'
            ])->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evaluation_point_ratings');
    }
};
