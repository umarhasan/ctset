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
        Schema::create('dops_competency_definition_details', function (Blueprint $table) {
            $table->id();
            $table->text('title');
            $table->integer('dcdid');
            $table->integer('dcddseq')->nullable();
            $table->string('Status', 1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dops_competency_definition_details');
    }
};
