<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTimecardsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('timecards', function(Blueprint $table)
		{
			$table->bigInteger('id', true)->unsigned();
			$table->bigInteger('worker_id')->unsigned()->index('timecards_worker_id_foreign')->comment('ワーカーID');
			$table->bigInteger('home_id')->unsigned()->index('timecards_home_id_foreign')->comment('施設ID');
			$table->bigInteger('work_id')->unsigned()->index('timecards_work_id_foreign')->comment('仕事ID');
			$table->dateTime('checkin_at')->comment('チェックイン日時');
			$table->dateTime('checkout_at')->nullable()->comment('チェックアウト日時');
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
		Schema::drop('timecards');
	}

}
