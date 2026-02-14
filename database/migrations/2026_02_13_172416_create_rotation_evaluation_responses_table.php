<?php
// database/migrations/2024_01_01_000004_create_rotation_evaluation_responses_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('rotation_evaluation_responses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rotation_evaluation_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->json('data')->nullable();
            $table->timestamp('submitted_at')->nullable();
            $table->timestamps();
            
            $table->unique(['rotation_evaluation_id', 'user_id'], 'unique_evaluation_response');
        });
    }

    public function down()
    {
        Schema::dropIfExists('rotation_evaluation_responses');
    }
};