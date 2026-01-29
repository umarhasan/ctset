<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void
    {
        Schema::create('evaluation_360_form_shares', function (Blueprint $table) {

            $table->id();

            // 🔗 Link with evaluation_360_forms
            $table->foreignId('evaluation_360_form_id')
                  ->constrained('evaluation_360_forms')
                  ->cascadeOnDelete();


            $table->foreignId('shared_by')
                  ->constrained('users')
                  ->cascadeOnDelete();

            // 👤 Assigned assessor (nullable initially)
            $table->foreignId('assigned_to')
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete();

            // 📋 External / evaluator info
            $table->string('name');
            $table->string('email');
            $table->string('phone')->nullable();
            $table->text('details')->nullable();

            // 🔐 Access pin
            $table->string('pin', 10)->nullable();


            $table->enum('status', ['W','I','U','A'])->default('W');

            $table->timestamps();

            // ⚡ Indexes for speed
            $table->index('evaluation_360_form_id');
            $table->index('shared_by');
            $table->index('assigned_to');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('evaluation_360_form_shares');
    }
};
