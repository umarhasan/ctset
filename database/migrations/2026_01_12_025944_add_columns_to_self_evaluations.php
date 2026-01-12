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
        Schema::table('self_evaluations', function (Blueprint $table) {
            $table->text('goals')->nullable();
            $table->json('goal_plan_actions')->nullable();
            $table->json('question_actions')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('self_evaluations', function (Blueprint $table) {
            $table->dropColumn([
                'goal_plan_actions',
                'question_actions',
                'status',
            ]);
        });
    }
};
