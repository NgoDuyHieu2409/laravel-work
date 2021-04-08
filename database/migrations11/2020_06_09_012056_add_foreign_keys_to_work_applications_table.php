<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToWorkApplicationsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('work_applications', function(Blueprint $table)
		{
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
		Schema::table('work_applications', function(Blueprint $table)
		{
			$table->dropForeign('work_applications_work_id_foreign');
			$table->dropForeign('work_applications_worker_id_foreign');
		});
	}

}
