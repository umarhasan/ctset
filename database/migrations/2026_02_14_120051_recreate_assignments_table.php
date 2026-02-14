<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('assignments'); // purana table delete

        Schema::create('assignments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('from_type_id'); // FK column
            $table->date('from');
            $table->date('to');
            $table->json('users'); // array of user IDs
            $table->unsignedBigInteger('rotation_id')->nullable();
            $table->timestamps();

            // Foreign keys
            $table->foreign('from_type_id')
                  ->references('id')
                  ->on('assignment_from_types')
                  ->onDelete('cascade');

            $table->foreign('rotation_id')
                  ->references('id')
                  ->on('rotations')
                  ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('assignments');
    }
};