<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $tables = [
            'cicu_ward_rounds',
            'grand_ward_rounds',
            'daily_ward_rounds',
            'dops_trainees',
        ];

        foreach ($tables as $tableName) {
            Schema::table($tableName, function (Blueprint $table) {
                $table->unsignedBigInteger('modal_type_id')->after('id'); // modal_type_id column
            });
        }
    }

    public function down(): void
    {
        $tables = [
            'cicu_ward_rounds',
            'grand_ward_rounds',
            'daily_ward_rounds',
            'dops_trainees',
        ];

        foreach ($tables as $tableName) {
            Schema::table($tableName, function (Blueprint $table) {
                $table->dropColumn('modal_type_id');
            });
        }
    }
};