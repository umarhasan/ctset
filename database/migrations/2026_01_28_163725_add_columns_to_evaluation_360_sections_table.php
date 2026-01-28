<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('evaluation_360_sections', function (Blueprint $table) {
            $table->string('subtitle')->nullable();
            $table->string('col_1_5')->nullable();
            $table->string('col_6_7')->nullable();
            $table->string('ue')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('evaluation_360_sections', function (Blueprint $table) {
            $table->dropColumn(['subtitle', 'col_1_5', 'col_6_7', 'ue']);
        });
    }
};
