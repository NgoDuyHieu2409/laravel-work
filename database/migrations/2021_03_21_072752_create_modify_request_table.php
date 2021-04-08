<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateModifyRequestTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('modify_requests', function (Blueprint $table) {
            $table->bigInteger('id', true)->unsigned();
			$table->bigInteger('worker_id')->unsigned()->index('modify_requests_worker_id_foreign');
			$table->bigInteger('user_id')->unsigned()->index('modify_requests_user_id_foreign');
			$table->bigInteger('work_id')->unsigned()->index('modify_requests_work_id_foreign');
			$table->text('comment', 65535)->nullable();
			$table->dateTime('scheduled_worktime_start_at')->nullable();
			$table->dateTime('scheduled_worktime_end_at')->nullable();
			$table->dateTime('modify_worktime_start_at')->nullable();
			$table->dateTime('modify_worktime_end_at')->nullable();
			$table->integer('resttime_minutes')->nullable();
			$table->integer('ovetime_percentages')->nullable();
			$table->integer('nighttime_percentages')->nullable();
			$table->integer('ovetime_wage')->nullable();
			$table->integer('nighttime_wage')->nullable();
			$table->integer('base_wage')->nullable();
			$table->integer('transportation_fee')->nullable();
            $table->integer('approval_status')->nullable();
			$table->dateTime('approved_at')->nullable();
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
        Schema::table('modify_requests', function (Blueprint $table) {
            //
        });
    }
}
