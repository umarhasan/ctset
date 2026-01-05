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
        Schema::create('exams', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('exam_id')->unique();
            $table->string('exam_name');
            $table->foreignId('test_type')->constrained('test_types')->cascadeOnDelete();
            $table->foreignId('question_type')->constrained('question_types')->cascadeOnDelete();
            $table->foreignId('marketing')->nullable()->constrained('marketing_types')->nullOnDelete();
            $table->foreignId('exam_duration_type')->constrained('exam_duration_types')->cascadeOnDelete();
            $table->date('exam_date');
            $table->time('exam_time')->nullable();
            $table->integer('hours')->nullable();
            $table->integer('minutes')->nullable();
            $table->text('exam_requirement')->nullable();
            $table->text('exam_equipment')->nullable();
            $table->text('long_before')->nullable();
            $table->tinyInteger('question_shuffling')->default(0);
            $table->tinyInteger('previous_button')->default(0);
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exams');
    }
};
