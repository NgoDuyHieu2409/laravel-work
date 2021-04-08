<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToTransferRequestsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('transfer_requests', function(Blueprint $table)
		{
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
		Schema::table('transfer_requests', function(Blueprint $table)
		{
			$table->dropForeign('transfer_requests_worker_id_foreign');
		});
	}

}
