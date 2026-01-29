<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void
    {
        Schema::create('evaluation_360_responses', function (Blueprint $table) {

            $table->id();

            // 🔗 Link with shared form
            $table->foreignId('share_id')
                  ->constrained('evaluation_360_form_shares')
                  ->cascadeOnDelete();

            // 🔗 Link with section
            $table->foreignId('section_id')
                  ->constrained('evaluation_360_sections')
                  ->cascadeOnDelete();

            // 📊 Scores
            $table->tinyInteger('score_1_5')->nullable();
            $table->tinyInteger('score_6_7')->nullable();

            // 📝 UE + Comments
            $table->text('ue')->nullable();
            $table->text('comments')->nullable();

            $table->timestamps();

            // ⚡ Indexes
            $table->index(['share_id','section_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('evaluation_360_responses');
    }
};
