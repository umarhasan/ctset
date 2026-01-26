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
        Schema::create('dops_requests', function (Blueprint $table) {
            $table->id();
            $table->integer('traineeid');
            $table->integer('trainerid')->nullable();
            $table->integer('dopsid');
            $table->text('traineecomments');
            $table->text('trainecomments')->nullable();
            $table->string('status', 1);
            $table->string('dates', 200)->nullable();
            $table->string('ftime', 200)->nullable();
            $table->string('ttime', 200)->nullable();
            $table->string('duration', 200)->nullable();
            $table->integer('rot')->nullable();
            $table->float('neg')->default(0);
            $table->float('neu')->default(0);
            $table->float('pos')->default(0);
            $table->string('mrn', 500)->nullable();
            $table->string('diagnosis', 500)->nullable();
            $table->string('procedure_', 500)->nullable();
            $table->text('comments')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dops_requests');
    }
};
