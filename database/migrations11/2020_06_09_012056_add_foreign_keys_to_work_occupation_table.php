<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToWorkOccupationTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('work_occupation', function(Blueprint $table)
		{
			$table->foreign('occupation_id')->references('id')->on('occupations')->onUpdate('RESTRICT')->onDelete('RESTRICT');
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
		Schema::table('work_occupation', function(Blueprint $table)
		{
			$table->dropForeign('work_occupation_occupation_id_foreign');
			$table->dropForeign('work_occupation_work_id_foreign');
		});
	}

}
