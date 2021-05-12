<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTimecardTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('timecards', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('worker_id')->unsigned()->index('timecards_worker_id_foreign');
			$table->bigInteger('home_id')->unsigned()->index('timecards_home_id_foreign');
			$table->bigInteger('work_id')->unsigned()->index('timecards_work_id_foreign');
			$table->dateTime('checkin_at')->nullable();
			$table->dateTime('checkout_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('timecards');
    }
}
