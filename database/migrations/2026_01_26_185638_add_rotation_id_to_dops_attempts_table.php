<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('dops_attempts', function (Blueprint $table) {
            $table->foreignId('rotation_id')
                  ->after('trainee_id')
                  ->constrained('rotations')
                  ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('dops_attempts', function (Blueprint $table) {
            $table->dropForeign(['rotation_id']);
            $table->dropColumn('rotation_id');
        });
    }
};
