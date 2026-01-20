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
        Schema::create('grand_ward_rounds', function (Blueprint $table) {
            $table->id();

            $table->date('date');
            $table->time('from_time');
            $table->time('to_time')->nullable();

            $table->foreignId('hospital_id')->constrained()->cascadeOnDelete();
            $table->foreignId('rotation_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('consultant_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            $table->enum('involvement', ['A','P','W'])->default('W');
            $table->json('consultant_fees')->nullable();

            $table->decimal('lat',10,7)->nullable();
            $table->decimal('long',10,7)->nullable();

            // CORE PHP features
            $table->string('token')->nullable();
            $table->timestamp('ended_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grand_ward_rounds');
    }
};
