<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('rotation_evaluation_subitems', function (Blueprint $table) {
            $table->id();
            $table->foreignId('section_id')->constrained('rotation_evaluation_sections')->onDelete('cascade');
            $table->string('subitem_text');
            $table->string('input_type')->default('text'); // text, checkbox, radio
            $table->integer('order')->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('rotation_evaluation_subitems');
    }
};
