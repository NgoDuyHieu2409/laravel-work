<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToWorkSkillTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('work_skill', function(Blueprint $table)
		{
			$table->foreign('skill_id')->references('id')->on('skills')->onUpdate('RESTRICT')->onDelete('RESTRICT');
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
		Schema::table('work_skill', function(Blueprint $table)
		{
			$table->dropForeign('work_skill_skill_id_foreign');
			$table->dropForeign('work_skill_work_id_foreign');
		});
	}

}
