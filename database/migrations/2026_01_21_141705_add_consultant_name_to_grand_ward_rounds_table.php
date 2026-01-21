<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddConsultantNameToGrandWardRoundsTable extends Migration
{
    public function up()
    {
        Schema::table('grand_ward_rounds', function (Blueprint $table) {
            $table->string('consultant_name')->nullable()->after('consultant_id');
        });
    }

    public function down()
    {
        Schema::table('grand_ward_rounds', function (Blueprint $table) {
            $table->dropColumn('consultant_name');
        });
    }
}
