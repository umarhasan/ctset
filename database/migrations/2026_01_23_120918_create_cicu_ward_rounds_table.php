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
        Schema::create('cicu_ward_rounds', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->date('date');
            $table->time('from_time');
            $table->time('to_time')->nullable();
            $table->foreignId('hospital_id')->constrained('hospitals');
            $table->enum('involvement', ['A', 'P', 'W'])->default('W');
            $table->string('consultant_sign')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cicu_ward_rounds');
    }
};
