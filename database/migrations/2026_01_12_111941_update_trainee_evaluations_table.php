<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('trainee_evaluations', function (Blueprint $table) {

            // action column remove
            if (Schema::hasColumn('trainee_evaluations', 'action')) {
                $table->dropColumn('action');
            }

            // status column add
            if (!Schema::hasColumn('trainee_evaluations', 'status')) {
                $table->enum('status', ['active', 'inactive'])
                      ->default('active')
                      ->after('title'); // agar title ke baad chahiye
            }
        });
    }

    public function down()
    {
        Schema::table('trainee_evaluations', function (Blueprint $table) {

            // wapas action add karna ho to
            $table->string('action')->nullable();

            // status remove
            $table->dropColumn('status');
        });
    }
};
