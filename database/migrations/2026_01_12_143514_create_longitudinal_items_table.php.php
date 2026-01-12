
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('longitudinal_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sub_section_id')->constrained('longitudinal_sub_sections')->onDelete('cascade');
            $table->string('item_number'); // 1., 2., 3.
            $table->string('item_title');
            $table->text('description')->nullable();
            $table->json('table_values')->nullable(); // For table items: [A/B: false, C/D: false]
            $table->boolean('is_checked')->default(false);
            $table->string('alternative_text')->nullable(); // For "Or" alternatives
            $table->integer('order')->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('longitudinal_items');
    }
};
