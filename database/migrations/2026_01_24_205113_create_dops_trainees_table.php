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
        Schema::create('dops_trainees', function (Blueprint $table) {
            $table->id();
            $table->integer('did');
            $table->integer('uid');
            $table->text('level');
            $table->text('tick');
            $table->tinyInteger('status')->default(1);
            $table->engine = 'MyISAM';
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dops_trainees');
    }
};
