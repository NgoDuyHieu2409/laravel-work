<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateHomeCancelRequestTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('home_cancel_request', function(Blueprint $table)
		{
			$table->bigInteger('id', true)->unsigned();
			$table->bigInteger('home_id')->unsigned()->index('home_cancel_request_home_id_foreign')->comment('施設ID');
			$table->bigInteger('work_id')->unsigned()->index('home_cancel_request_work_id_foreign')->comment('仕事ID');
			$table->integer('reason')->nullable()->comment('理由');
			$table->text('memo', 65535)->nullable()->comment('メモ');
			$table->dateTime('admin_canceled_at')->nullable()->comment('管理者キャンセル日時');
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
		Schema::drop('home_cancel_request');
	}

}
