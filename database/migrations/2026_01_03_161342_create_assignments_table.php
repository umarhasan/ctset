<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('assignments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('from_type_id');
            $table->string('from');
            $table->string('to');
            $table->json('users');
            $table->unsignedBigInteger('rotation_id')->nullable();
            $table->timestamps();

            $table->foreign('from_type_id')->references('id')->on('from_types')->onDelete('cascade');
            $table->foreign('rotation_id')->references('id')->on('rotations')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('assignments');
    }
};
