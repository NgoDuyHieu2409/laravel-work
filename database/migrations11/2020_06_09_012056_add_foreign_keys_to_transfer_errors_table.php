<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToTransferErrorsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('transfer_errors', function(Blueprint $table)
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
		Schema::table('transfer_errors', function(Blueprint $table)
		{
			$table->dropForeign('transfer_errors_worker_id_foreign');
		});
	}

}
