<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWorkApplicationsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('work_applications', function(Blueprint $table)
		{
			$table->bigInteger('id', true)->unsigned();
			$table->bigInteger('work_id')->unsigned()->index('work_applications_work_id_foreign');
			$table->bigInteger('worker_id')->unsigned()->index('work_applications_worker_id_foreign');
			$table->string('room_id', 255)->nullable();
			$table->integer('status');
			$table->dateTime('assigned_at')->nullable();
			$table->dateTime('confirmed_at')->nullable();
	    	$table->string('confirm_yn', 1)->default('n');
			$table->dateTime('canceled_at')->nullable();
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
		Schema::drop('work_applications');
	}

}
