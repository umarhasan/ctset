<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('evaluation_360_form_shares', function (Blueprint $table) {
            $table->timestamp('locked_at')->nullable()->after('status');
        });
    }

    public function down(): void
    {
        Schema::table('evaluation_360_form_shares', function (Blueprint $table) {
            $table->dropColumn('locked_at');
        });
    }
};
