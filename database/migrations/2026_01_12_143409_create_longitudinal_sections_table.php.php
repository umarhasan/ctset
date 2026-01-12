<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('longitudinal_sections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('longitudinal_requirement_id')->constrained()->onDelete('cascade');
            $table->string('section_letter'); // A, B, C, D, E
            $table->string('section_title');
            $table->integer('order')->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('longitudinal_sections');
    }
};
