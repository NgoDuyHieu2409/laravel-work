<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToWorkQualificationTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('work_qualification', function(Blueprint $table)
		{
			$table->foreign('qualification_id')->references('id')->on('qualifications')->onUpdate('RESTRICT')->onDelete('RESTRICT');
			$table->foreign('work_id')->references('id')->on('works')->onUpdate('RESTRICT')->onDelete('RESTRICT');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('work_qualification', function(Blueprint $table)
		{
			$table->dropForeign('work_qualification_qualification_id_foreign');
			$table->dropForeign('work_qualification_work_id_foreign');
		});
	}

}
