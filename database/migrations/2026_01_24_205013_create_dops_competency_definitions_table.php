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
        Schema::create('dops_competency_definitions', function (Blueprint $table) {
            $table->id();
            $table->integer('dopsid');
            $table->integer('cdid');
            $table->string('dcdseq', 11);
            $table->string('status', 1);
            $table->string('mNegative', 500)->nullable();
            $table->string('mNeutral', 500)->nullable();
            $table->string('mPositive', 500)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dops_competency_definitions');
    }
};
