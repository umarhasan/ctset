<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {

            // Basic
            $table->string('fname')->nullable()->after('name');
            $table->string('lname')->nullable()->after('fname');
            $table->tinyInteger('gender')->nullable();
            $table->text('address')->nullable();
            // Academic
            $table->unsignedBigInteger('sem_id')->nullable();
            $table->unsignedBigInteger('rotation_id')->nullable();
            // Sub type
            $table->tinyInteger('sub_utype')->nullable()->comment('1=doctor,2=master');

        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'fname','lname','gender','address',
                'sem_id','rotation_id','sub_utype',]);
        });
    }
};