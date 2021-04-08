<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeColumnNullableToWorkRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('work_records', function (Blueprint $table) {
            //
	    $table->string('worker_family_name', 255)->nullable()->change();
	    $table->string('worker_last_name', 255)->nullable()->change();
	    $table->string('worker_family_name_kana', 255)->nullable()->change();
	    $table->string('worker_last_name_kana', 255)->nullable()->change();
	    $table->integer('worker_sex')->nullable()->change();
	    $table->integer('base_wage')->nullable()->change();
	    
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('work_records', function (Blueprint $table) {
            //
        });
    }
}
