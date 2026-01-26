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
        Schema::create('dops', function (Blueprint $table) {
            $table->id();
            $table->text('title');
            $table->tinyInteger('level');
            $table->text('steps');
            $table->text('raiting');
            $table->text('competencies');
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
        Schema::dropIfExists('dops');
    }
};
