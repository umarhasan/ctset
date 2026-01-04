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
        Schema::create('evaluation_points', function (Blueprint $table) {
            $table->id();
            $table->string('title');                // Point title
            $table->text('action')->nullable();     // Action / Notes
            $table->enum('evaluation_type', ['self','trainee','360','rotation'])->default('self');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evaluation_points');
    }
};
