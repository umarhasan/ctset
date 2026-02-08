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
        Schema::create('modal_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('short_code', 10)->unique();
            $table->foreignId('department_id')->constrained()->onDelete('cascade');
            $table->string('table_name')->comment('cicu_ward_rounds, grand_ward_rounds, daily_ward_rounds, dops_trainees');
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->unique(['short_code', 'table_name']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('modal_types');
    }
};
