<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('trainee_evaluation_points', function (Blueprint $table) {
            $table->id();
            $table->foreignId('section_id')->constrained('trainee_evaluation_sections')->onDelete('cascade');
            $table->text('point_text');
            $table->integer('order')->default(0);
            $table->enum('rating', [
                'unsatisfactory',
                'needs_attention',
                'satisfactory',
                'well_above_average'
            ])->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('trainee_evaluation_points');
    }
};