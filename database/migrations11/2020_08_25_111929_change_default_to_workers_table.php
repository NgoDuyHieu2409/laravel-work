<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeDefaultToWorkersTable extends Migration
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
	    $table->integer('good_percentages')->default(0)->change();
	    $table->integer('total_workcount')->default(0)->change();
	    $table->integer('total_worktime')->default(0)->change();
	    $table->integer('penalty_point')->default(0)->change();
	    $table->integer('cancel_percentages')->default(0)->change();
	    $table->integer('immediately_cancel_percentages')->default(0)->change();
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
