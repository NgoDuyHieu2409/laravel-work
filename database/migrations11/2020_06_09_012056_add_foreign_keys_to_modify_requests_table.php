<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToModifyRequestsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('modify_requests', function(Blueprint $table)
		{
			$table->foreign('home_id')->references('id')->on('homes')->onUpdate('RESTRICT')->onDelete('RESTRICT');
			$table->foreign('work_id')->references('id')->on('works')->onUpdate('RESTRICT')->onDelete('RESTRICT');
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
		Schema::table('modify_requests', function(Blueprint $table)
		{
			$table->dropForeign('modify_requests_home_id_foreign');
			$table->dropForeign('modify_requests_work_id_foreign');
			$table->dropForeign('modify_requests_worker_id_foreign');
		});
	}

}
