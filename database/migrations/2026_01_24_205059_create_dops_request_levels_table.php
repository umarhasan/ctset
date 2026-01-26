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
        Schema::create('dops_request_levels', function (Blueprint $table) {
            $table->id();
            $table->integer('requestid');
            $table->integer('dopsid');
            $table->integer('levelid');
            $table->string('status', 1)->nullable();
            $table->engine = 'MyISAM';
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dops_request_levels');
    }
};
