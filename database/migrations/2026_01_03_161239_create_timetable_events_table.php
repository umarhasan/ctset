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
        Schema::create('timetable_events', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->foreignId('category_id')->constrained('time_table_categories')->onDelete('cascade');
            $table->boolean('is_superviser')->default(false);
            $table->boolean('is_trainee')->default(false);
            $table->date('date');
            $table->string('image')->nullable();
            $table->time('from_time');
            $table->time('to_time');
            $table->integer('reminder_days')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('timetable_events');
    }
};
