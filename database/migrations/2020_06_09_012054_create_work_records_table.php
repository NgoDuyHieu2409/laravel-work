<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
			$table->bigInteger('user_id')->unsigned()->index('work_records_user_id_foreign');
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
			$table->string('worker_first_name')->nullable();
			$table->string('worker_last_name')->nullable();
			$table->string('worker_first_name_kana')->nullable();
			$table->string('worker_last_name_kana')->nullable();
			$table->integer('worker_sex')->nullable();
			$table->integer('base_wage')->nullable();
			$table->integer('ovetime_percentages')->nullable();
			$table->integer('nighttime_percentages')->nullable();
			$table->integer('ovetime_wage')->nullable();
			$table->integer('nighttime_wage')->nullable();
			$table->integer('transportation_fee')->nullable();
			$table->integer('total_wage')->nullable();
			$table->integer('transfer_request_status')->nullable();
			$table->dateTime('transfer_requested_at')->nullable();
			$table->dateTime('transfered_at')->nullable();
			$table->integer('commission_fee')->nullable();
			$table->integer('commission_fee_tax')->nullable();
			$table->integer('commission_fee_tax_rate')->nullable();
			$table->string('fixed_yn', 1)->nullable();
			$table->dateTime('fixed_at')->nullable();
			$table->bigInteger('work_application_id')->unsigned()->nullable();
			$table->float('nighttime_worktime', 3, 1)->nullable();
            $table->float('overtime_worktime', 3, 1)->nullable();
            $table->float('base_worktime', 3, 1)->nullable();
			$table->integer('hourly_wage')->nullable();
			$table->bigInteger('company_id')->unsigned();
			$table->integer('bank_commission_fee_tax')->nullable();	    
	    	$table->integer('bank_commission_fee')->nullable();
			$table->integer('withholding_fee')->nullable();
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
