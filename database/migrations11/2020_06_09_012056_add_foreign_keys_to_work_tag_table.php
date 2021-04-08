<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToWorkTagTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('work_tag', function(Blueprint $table)
		{
			$table->foreign('tag_id')->references('id')->on('tags')->onUpdate('RESTRICT')->onDelete('RESTRICT');
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
		Schema::table('work_tag', function(Blueprint $table)
		{
			$table->dropForeign('work_tag_tag_id_foreign');
			$table->dropForeign('work_tag_work_id_foreign');
		});
	}

}
