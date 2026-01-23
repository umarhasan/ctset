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
        Schema::create('daily_ward_rounds', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');          // trainee
            $table->date('date');
            $table->time('from_time');
            $table->time('to_time')->nullable();

            $table->enum('involvement',['A','W']);

            $table->foreignId('hospital_id');
            $table->foreignId('rotation_id')->nullable();
            $table->foreignId('consultant_id')->nullable();

            $table->decimal('lat',10,7)->nullable();
            $table->decimal('long',10,7)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daily_ward_rounds');
    }
};
