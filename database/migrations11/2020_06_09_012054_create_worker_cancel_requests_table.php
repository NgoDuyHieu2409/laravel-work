<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateWorkerCancelRequestsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('worker_cancel_requests', function(Blueprint $table)
		{
			$table->bigInteger('id', true)->unsigned();
			$table->bigInteger('home_id')->unsigned()->index('worker_cancel_requests_home_id_foreign')->comment('施設ID');
			$table->bigInteger('work_id')->unsigned()->index('worker_cancel_requests_work_id_foreign')->comment('仕事ID');
			$table->text('reason', 65535)->nullable()->comment('理由');
			$table->dateTime('approval_at')->nullable()->comment('キャンセル承認日時');
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
		Schema::drop('worker_cancel_requests');
	}

}
