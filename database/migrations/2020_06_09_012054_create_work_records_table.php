<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateWorkRecordsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('work_records', function(Blueprint $table)
		{
			$table->bigInteger('id', true)->unsigned();
			$table->bigInteger('invoice_id')->unsigned()->nullable();
			$table->bigInteger('worker_id')->unsigned()->index('work_records_worker_id_foreign');
			$table->bigInteger('home_id')->unsigned()->index('work_records_home_id_foreign');
			$table->bigInteger('work_id')->unsigned()->index('work_records_work_id_foreign');
			$table->text('title', 65535)->nullable();
			$table->date('work_date')->nullable();
			$table->dateTime('scheduled_worktime_start_at')->nullable();
			$table->dateTime('scheduled_worktime_end_at')->nullable();
			$table->dateTime('worktime_start_at')->nullable();
			$table->dateTime('worktime_end_at')->nullable();
			$table->dateTime('scheduled_resttime_start_at')->nullable();
			$table->dateTime('scheduled_resttime_end_at')->nullable();
			$table->dateTime('resttime_start_at')->nullable();
			$table->dateTime('resttime_end_at')->nullable();
			$table->integer('resttime_minutes')->nullable();
			$table->string('worker_family_name');
			$table->string('worker_last_name');
			$table->string('worker_family_name_kana');
			$table->string('worker_last_name_kana');
			$table->integer('worker_sex');
			$table->integer('base_wage');
			$table->integer('ovetime_percentages')->nullable();
			$table->integer('nighttime_percentages')->nullable();
			$table->integer('ovetime_wage')->nullable();
			$table->integer('nighttime_wage')->nullable();
			$table->integer('transportation_fee')->nullable();
			$table->integer('total_wage');
			$table->integer('transfer_request_status');
			$table->dateTime('transfer_requested_at');
			$table->dateTime('transfered_at')->nullable();
			$table->integer('commissoin_fee');
			$table->integer('commissoin_fee_tax');
			$table->integer('commissoin_fee_tax_rate');
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
		Schema::drop('work_records');
	}

}
