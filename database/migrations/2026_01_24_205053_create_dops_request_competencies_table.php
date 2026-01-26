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
        Schema::create('dops_request_competencies', function (Blueprint $table) {
            $table->id();
            $table->integer('requestid');
            $table->integer('ratingid')->nullable();
            $table->text('comments')->nullable();
            $table->integer('cddid');
            $table->integer('cdid');
            $table->string('wordcloudid', 500)->nullable();
            $table->string('Negative', 500)->nullable();
            $table->string('Neutral', 500)->nullable();
            $table->string('Positive', 500)->nullable();
            $table->engine = 'MyISAM';
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dops_request_competencies');
    }
};
