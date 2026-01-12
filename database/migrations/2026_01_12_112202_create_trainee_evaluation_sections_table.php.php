<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('trainee_evaluation_sections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('trainee_evaluation_id')->constrained()->onDelete('cascade');
            $table->string('section_title');
            $table->integer('order')->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('trainee_evaluation_sections');
    }
};
