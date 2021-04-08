<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateModifyRequestsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('modify_requests', function(Blueprint $table)
		{
			$table->bigInteger('id', true)->unsigned();
			$table->bigInteger('worker_id')->unsigned()->index('modify_requests_worker_id_foreign')->comment('ワーカーID');
			$table->bigInteger('home_id')->unsigned()->index('modify_requests_home_id_foreign')->comment('施設ID');
			$table->bigInteger('work_id')->unsigned()->index('modify_requests_work_id_foreign')->comment('仕事ID');
			$table->text('comment', 65535)->nullable()->comment('コメント');
			$table->dateTime('scheduled_worktime_start_at')->nullable()->comment('予定開始日時');
			$table->dateTime('scheduled_worktime_end_at')->nullable()->comment('予定終了日時');
			$table->dateTime('modify_worktime_start_at')->nullable()->comment('修正後開始日時');
			$table->dateTime('modify_worktime_end_at')->nullable()->comment('修正後終了日時');
			$table->integer('resttime_minutes')->nullable()->comment('休憩時間');
			$table->integer('ovetime_percentages')->nullable()->comment('法定時間外割増手当のパーセンテージ');
			$table->integer('nighttime_percentages')->nullable()->comment('深夜割増手当のパーセンテージ');
			$table->integer('ovetime_wage')->nullable()->comment('法定時間外割増手当金額');
			$table->integer('nighttime_wage')->nullable()->comment('深夜割増手当金額');
			$table->integer('base_wage')->nullable()->comment('基本報酬');
			$table->integer('transportation_fee')->nullable()->comment('交通費');
			$table->dateTime('approved_at')->nullable()->comment('承認日時');
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
		Schema::drop('modify_requests');
	}

}
