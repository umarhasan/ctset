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
        Schema::create('exam_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exam_id')->constrained()->cascadeOnDelete();
            $table->integer('total_students');
            $table->integer('passed_students');
            $table->integer('failed_students');
            $table->integer('highest_marks')->nullable();
            $table->integer('average_marks')->nullable();
            $table->boolean('is_calculated')->default(0);
            $table->boolean('is_announced')->default(0);
            $table->timestamp('announced_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exam_results');
    }
};
