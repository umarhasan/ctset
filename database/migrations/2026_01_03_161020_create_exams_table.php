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
            $table->string('test_type');
            $table->string('exam_name');
            $table->date('exam_date');
            $table->enum('exam_duration_type', ['one_day', 'multiple_day']);
            $table->time('exam_time')->nullable();
            $table->integer('hours')->nullable();
            $table->integer('minutes')->nullable();
            $table->string('long_before')->nullable();
            $table->string('question_type');
            $table->text('exam_requirement')->nullable();
            $table->text('exam_equipment')->nullable();
            $table->string('marketing')->nullable();
            $table->boolean('question_shuffling')->default(false);
            $table->boolean('previous_button')->default(false);
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
