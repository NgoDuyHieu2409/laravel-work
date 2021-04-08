<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnWorkIdToHomeReviewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('home_reviews', function (Blueprint $table) {
            //
	    $table->bigInteger('work_id')->unsigned()->after('home_id')->comment('仕事ID');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('home_reviews', function (Blueprint $table) {
            //
        });
    }
}
