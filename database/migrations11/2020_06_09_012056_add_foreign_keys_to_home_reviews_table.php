<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToHomeReviewsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('home_reviews', function(Blueprint $table)
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
		Schema::table('home_reviews', function(Blueprint $table)
		{
			$table->dropForeign('home_reviews_home_id_foreign');
			$table->dropForeign('home_reviews_worker_id_foreign');
		});
	}

}
