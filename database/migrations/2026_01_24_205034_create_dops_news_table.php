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
        Schema::create('dops_news', function (Blueprint $table) {
            $table->id();
            $table->string('title', 500)->nullable();
            $table->text('steps')->nullable();
            $table->string('status', 1)->nullable();
            $table->integer('reqnum')->nullable();
            $table->engine = 'MyISAM';
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dops_news');
    }
};
