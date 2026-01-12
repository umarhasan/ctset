<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('rotation_evaluation_sections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rotation_evaluation_id')->constrained()->onDelete('cascade');
            $table->string('section_title');
            $table->string('section_type')->default('text'); // text, yes_no, scale_5, scale_5_with_desc
            $table->text('description')->nullable();
            $table->json('options')->nullable(); // For storing scale options or yes/no options
            $table->integer('order')->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('rotation_evaluation_sections');
    }
};
