<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('evaluation_360_sections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('evaluation_360_form_id')->constrained()->onDelete('cascade');
            $table->string('section_title');
            $table->integer('order')->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('evaluation_360_sections');
    }
};
