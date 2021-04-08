<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToWorkerQualificationTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('worker_qualification', function(Blueprint $table)
		{
			$table->foreign('qualification_id')->references('id')->on('qualifications')->onUpdate('RESTRICT')->onDelete('RESTRICT');
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
		Schema::table('worker_qualification', function(Blueprint $table)
		{
			$table->dropForeign('worker_qualification_qualification_id_foreign');
			$table->dropForeign('worker_qualification_worker_id_foreign');
		});
	}

}
