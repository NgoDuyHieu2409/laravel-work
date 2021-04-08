<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

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
			$table->bigInteger('work_id')->unsigned()->index('work_applications_work_id_foreign')->comment('仕事ID');
			$table->bigInteger('worker_id')->unsigned()->index('work_applications_worker_id_foreign')->comment('ワーカーID');
			$table->integer('status')->comment('応募ステータス');
			$table->dateTime('canceled_at')->nullable()->comment('キャンセル日時');
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
