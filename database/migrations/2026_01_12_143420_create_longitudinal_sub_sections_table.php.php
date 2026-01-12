
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('longitudinal_sub_sections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('section_id')->constrained('longitudinal_sections')->onDelete('cascade');
            $table->string('sub_section_title');
            $table->string('sub_section_type')->default('table'); // table, list, meeting, courses, research
            $table->json('table_columns')->nullable(); // For table type: columns like [A/B, C/D]
            $table->integer('order')->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('longitudinal_sub_sections');
    }
};
