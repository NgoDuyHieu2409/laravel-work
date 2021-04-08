<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateBlockWorkersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('block_workers', function(Blueprint $table)
		{
			$table->bigInteger('id', true)->unsigned();
			$table->bigInteger('worker_id')->unsigned()->index('block_workers_worker_id_foreign')->comment('ワーカーID');
			$table->bigInteger('home_id')->unsigned()->index('block_workers_home_id_foreign')->comment('施設ID');
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
		Schema::drop('block_workers');
	}

}
