<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToBlockWorkersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('block_workers', function(Blueprint $table)
		{
			$table->foreign('home_id')->references('id')->on('homes')->onUpdate('RESTRICT')->onDelete('RESTRICT');
			$table->foreign('worker_id')->references('id')->on('workers')->onUpdate('RESTRICT')->onDelete('RESTRICT');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('block_workers', function(Blueprint $table)
		{
			$table->dropForeign('block_workers_home_id_foreign');
			$table->dropForeign('block_workers_worker_id_foreign');
		});
	}

}
