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
        Schema::create('generated_qrs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('qr_categories')->onDelete('cascade');
            $table->date('date');
            $table->text('qr_data');   // json data
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('generated_qrs');
    }
};
