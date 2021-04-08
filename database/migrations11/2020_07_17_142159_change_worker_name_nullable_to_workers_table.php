<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeWorkerNameNullableToWorkersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('workers', function (Blueprint $table) {
            //
	    $table->string('first_name', 255)->nullable()->change();
	    $table->string('last_name', 255)->nullable()->change();
	    $table->string('first_name_kana', 255)->nullable()->change();
	    $table->string('last_name_kana', 255)->nullable()->change();
	    $table->date('birthday')->nullable()->change();
	    $table->text('address')->nullable()->change();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('workers', function (Blueprint $table) {
            //
        });
    }
}
